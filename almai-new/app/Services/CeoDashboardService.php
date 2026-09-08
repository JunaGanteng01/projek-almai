<?php

namespace App\Services;

use CodeIgniter\Database\BaseConnection;
use DateInterval;
use DateTimeImmutable;
use DateTimeZone;

class CeoDashboardService
{
    private BaseConnection $db;
    private DateTimeZone $timezone;

    public function __construct(?BaseConnection $db = null)
    {
        $this->db = $db ?? \Config\Database::connect();
        $this->timezone = new DateTimeZone(config('App')->appTimezone ?: 'Asia/Singapore');
    }

    public function getDashboard(string $period = 'mtd', ?string $customStart = null, ?string $customEnd = null): array
    {
        [$start, $end, $previousStart, $previousEnd, $label] = $this->resolveRange($period, $customStart, $customEnd);
        $now = new DateTimeImmutable('now', $this->timezone);

        $revenue = $this->transactionRevenue($start, $end);
        $previousRevenue = $this->transactionRevenue($previousStart, $previousEnd);
        $confirmedTransactions = $this->countTransactions($start, $end, ['confirmed']);
        $allTransactions = $this->countTransactions($start, $end);
        $newUsers = $this->countRowsByDate('users', 'created_at', $start, $end);
        $previousUsers = $this->countRowsByDate('users', 'created_at', $previousStart, $previousEnd);

        $approvals = $this->approvalSummary($now);
        $withdrawals = $this->withdrawalSummary();
        $invoices = $this->invoiceSummary($now);
        $events = $this->eventSummary($now);
        $crm = $this->crmSummary($now);
        $bills = $this->billSummary($now);
        $goals = $this->goalSummary($now);

        $conversion = $newUsers > 0 ? round(($confirmedTransactions / $newUsers) * 100, 1) : null;

        $summary = [
            'revenue' => $revenue,
            'revenue_growth' => $this->growth($revenue, $previousRevenue),
            'transactions_confirmed' => $confirmedTransactions,
            'transactions_total' => $allTransactions,
            'new_users' => $newUsers,
            'user_growth' => $this->growth($newUsers, $previousUsers),
            'conversion_rate' => $conversion,
            'cash_balance' => $this->cashBalance(),
            'approvals' => $approvals,
            'withdrawals' => $withdrawals,
            'invoices' => $invoices,
            'events' => $events,
            'crm' => $crm,
            'bills' => $bills,
            'goals' => $goals,
        ];

        return [
            'range' => [
                'period' => $period,
                'label' => $label,
                'start' => $start->format('Y-m-d'),
                'end' => $end->format('Y-m-d'),
                'previous_start' => $previousStart->format('Y-m-d'),
                'previous_end' => $previousEnd->format('Y-m-d'),
            ],
            'summary' => $summary,
            'transaction_statuses' => $this->transactionStatuses($start, $end),
            'trend' => $this->revenueTrend($start, $end),
            'recent_transactions' => $this->recentTransactions($start, $end),
            'upcoming_events' => $this->upcomingEvents($now),
            'pending_approvals' => $this->pendingApprovals(),
            'critical_conversations' => $this->criticalConversations($now),
            'goals' => $this->activeGoals(),
            'alerts' => $this->buildAlerts($summary),
            'generated_at' => $now->format(DATE_ATOM),
            'definitions' => [
                'revenue' => 'Total transaksi berstatus confirmed, tidak termasuk pembayaran poin, pada periode terpilih.',
                'cash_balance' => 'Saldo debit dikurangi kredit pada akun berkategori kas & bank.',
                'conversion_rate' => 'Transaksi confirmed dibagi pengguna baru pada periode yang sama.',
                'crm_sla' => 'Percakapan aktif yang waktu mulai SLA atau pesan terakhirnya lebih dari 24 jam.',
            ],
        ];
    }

    private function resolveRange(string $period, ?string $customStart, ?string $customEnd): array
    {
        $today = new DateTimeImmutable('today', $this->timezone);
        $end = $today->setTime(23, 59, 59);

        switch ($period) {
            case 'today':
                $start = $today;
                $label = 'Hari ini';
                break;
            case '7d':
                $start = $today->sub(new DateInterval('P6D'));
                $label = '7 hari terakhir';
                break;
            case '30d':
                $start = $today->sub(new DateInterval('P29D'));
                $label = '30 hari terakhir';
                break;
            case 'quarter':
                $quarterMonth = ((int) floor(((int) $today->format('n') - 1) / 3) * 3) + 1;
                $start = $today->setDate((int) $today->format('Y'), $quarterMonth, 1);
                $label = 'Kuartal berjalan';
                break;
            case 'year':
                $start = $today->setDate((int) $today->format('Y'), 1, 1);
                $label = 'Tahun berjalan';
                break;
            case 'custom':
                $start = $this->safeDate($customStart) ?? $today->modify('first day of this month');
                $customEndDate = $this->safeDate($customEnd) ?? $today;
                if ($customEndDate < $start) {
                    [$start, $customEndDate] = [$customEndDate, $start];
                }
                $end = $customEndDate->setTime(23, 59, 59);
                $label = 'Rentang khusus';
                break;
            case 'mtd':
            default:
                $period = 'mtd';
                $start = $today->modify('first day of this month');
                $label = 'Bulan berjalan';
                break;
        }

        $days = max(1, (int) $start->diff($end)->format('%a') + 1);
        $previousEnd = $start->sub(new DateInterval('P1D'))->setTime(23, 59, 59);
        $previousStart = $previousEnd->sub(new DateInterval('P' . ($days - 1) . 'D'))->setTime(0, 0, 0);

        return [$start, $end, $previousStart, $previousEnd, $label];
    }

    private function safeDate(?string $value): ?DateTimeImmutable
    {
        if (!$value || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return null;
        }
        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $value, $this->timezone);
        return $date && $date->format('Y-m-d') === $value ? $date : null;
    }

    private function transactionRevenue(DateTimeImmutable $start, DateTimeImmutable $end): float
    {
        if (!$this->db->tableExists('transaksi')) {
            return 0;
        }
        $row = $this->db->table('transaksi')
            ->selectSum('total', 'value')
            ->where('status', 'confirmed')
            ->where('created_at >=', $start->format('Y-m-d H:i:s'))
            ->where('created_at <=', $end->format('Y-m-d H:i:s'))
            ->groupStart()->where('payment_method !=', 'poin')->orWhere('payment_method', null)->groupEnd()
            ->get()->getRowArray();
        return (float) ($row['value'] ?? 0);
    }

    private function countTransactions(DateTimeImmutable $start, DateTimeImmutable $end, ?array $statuses = null): int
    {
        if (!$this->db->tableExists('transaksi')) {
            return 0;
        }
        $builder = $this->db->table('transaksi')
            ->where('created_at >=', $start->format('Y-m-d H:i:s'))
            ->where('created_at <=', $end->format('Y-m-d H:i:s'));
        if ($statuses) {
            $builder->whereIn('status', $statuses);
        }
        return $builder->countAllResults();
    }

    private function countRowsByDate(string $table, string $column, DateTimeImmutable $start, DateTimeImmutable $end): int
    {
        if (!$this->db->tableExists($table) || !$this->db->fieldExists($column, $table)) {
            return 0;
        }
        return $this->db->table($table)
            ->where($column . ' >=', $start->format('Y-m-d H:i:s'))
            ->where($column . ' <=', $end->format('Y-m-d H:i:s'))
            ->countAllResults();
    }

    private function transactionStatuses(DateTimeImmutable $start, DateTimeImmutable $end): array
    {
        if (!$this->db->tableExists('transaksi')) {
            return [];
        }
        $rows = $this->db->table('transaksi')
            ->select('status, COUNT(*) AS total')
            ->where('created_at >=', $start->format('Y-m-d H:i:s'))
            ->where('created_at <=', $end->format('Y-m-d H:i:s'))
            ->groupBy('status')->get()->getResultArray();
        return array_column($rows, 'total', 'status');
    }

    private function approvalSummary(DateTimeImmutable $now): array
    {
        if (!$this->db->tableExists('ea_approvals')) {
            return ['pending' => 0, 'urgent' => 0, 'overdue' => 0, 'amount' => 0.0];
        }
        $pendingStatuses = ['pending', 'waiting', 'submitted'];
        $base = $this->db->table('ea_approvals')->whereIn('LOWER(status)', $pendingStatuses);
        $pending = $base->countAllResults(false);
        $amountRow = $base->selectSum('amount', 'value')->get()->getRowArray();
        $urgent = $this->db->table('ea_approvals')->whereIn('LOWER(status)', $pendingStatuses)->where('LOWER(priority)', 'urgent')->countAllResults();
        $overdue = $this->db->fieldExists('due_date', 'ea_approvals')
            ? $this->db->table('ea_approvals')->whereIn('LOWER(status)', $pendingStatuses)->where('due_date <', $now->format('Y-m-d H:i:s'))->countAllResults()
            : 0;
        return ['pending' => $pending, 'urgent' => $urgent, 'overdue' => $overdue, 'amount' => (float) ($amountRow['value'] ?? 0)];
    }

    private function withdrawalSummary(): array
    {
        if (!$this->db->tableExists('withdrawals')) {
            return ['pending' => 0, 'amount' => 0.0];
        }
        $builder = $this->db->table('withdrawals')->whereIn('status', ['pending', 'approved']);
        $count = $builder->countAllResults(false);
        $row = $builder->selectSum('amount', 'value')->get()->getRowArray();
        return ['pending' => $count, 'amount' => (float) ($row['value'] ?? 0)];
    }

    private function invoiceSummary(DateTimeImmutable $now): array
    {
        if (!$this->db->tableExists('customer_invoices')) {
            return ['open' => 0, 'overdue' => 0, 'amount' => 0.0];
        }
        $openStatuses = ['pending', 'unpaid', 'overdue', 'sent'];
        $builder = $this->db->table('customer_invoices')->whereIn('LOWER(status)', $openStatuses);
        $open = $builder->countAllResults(false);
        $amountRow = $builder->selectSum('total', 'value')->get()->getRowArray();
        $overdue = $this->db->table('customer_invoices')->whereIn('LOWER(status)', $openStatuses)
            ->where('jatuh_tempo <', $now->format('Y-m-d'))->countAllResults();
        return ['open' => $open, 'overdue' => $overdue, 'amount' => (float) ($amountRow['value'] ?? 0)];
    }

    private function eventSummary(DateTimeImmutable $now): array
    {
        if (!$this->db->tableExists('layanan_event')) {
            return ['upcoming' => 0, 'participants' => 0, 'capacity' => 0, 'occupancy' => null];
        }
        $row = $this->db->table('layanan_event')
            ->select('COUNT(*) AS upcoming, COALESCE(SUM(current_participants),0) AS participants, COALESCE(SUM(max_participants),0) AS capacity')
            ->where('event_date >=', $now->format('Y-m-d H:i:s'))
            ->whereNotIn('LOWER(status)', ['cancelled', 'tidak aktif', 'draft'])
            ->get()->getRowArray() ?? [];
        $capacity = (int) ($row['capacity'] ?? 0);
        $participants = (int) ($row['participants'] ?? 0);
        return [
            'upcoming' => (int) ($row['upcoming'] ?? 0),
            'participants' => $participants,
            'capacity' => $capacity,
            'occupancy' => $capacity > 0 ? round(($participants / $capacity) * 100, 1) : null,
        ];
    }

    private function crmSummary(DateTimeImmutable $now): array
    {
        if (!$this->db->tableExists('cs_conversations')) {
            return ['open' => 0, 'unassigned' => 0, 'sla_breach' => 0];
        }
        $active = ['NEW', 'AUTO_REPLIED', 'UNREAD', 'IN_PROGRESS', 'FOLLOW_UP'];
        $open = $this->db->table('cs_conversations')->whereIn('UPPER(status)', $active)->countAllResults();
        $unassigned = $this->db->table('cs_conversations')->whereIn('UPPER(status)', $active)->where('assigned_to', null)->countAllResults();
        $threshold = $now->sub(new DateInterval('P1D'))->format('Y-m-d H:i:s');
        $sla = $this->db->table('cs_conversations')->whereIn('UPPER(status)', $active)
            ->groupStart()->where('sla_started_at <', $threshold)->orGroupStart()->where('sla_started_at', null)->where('last_message_at <', $threshold)->groupEnd()->groupEnd()
            ->countAllResults();
        return ['open' => $open, 'unassigned' => $unassigned, 'sla_breach' => $sla];
    }

    private function billSummary(DateTimeImmutable $now): array
    {
        if (!$this->db->tableExists('ceo_bills')) {
            return ['open' => 0, 'overdue' => 0, 'due_soon' => 0, 'amount' => 0.0];
        }
        $statuses = ['pending', 'submitted', 'approved', 'overdue'];
        $builder = $this->db->table('ceo_bills')->whereIn('LOWER(status)', $statuses);
        $open = $builder->countAllResults(false);
        $amount = $builder->selectSum('amount', 'value')->get()->getRowArray();
        $overdue = $this->db->table('ceo_bills')->whereIn('LOWER(status)', $statuses)->where('due_date <', $now->format('Y-m-d'))->countAllResults();
        $dueSoon = $this->db->table('ceo_bills')->whereIn('LOWER(status)', $statuses)->where('due_date >=', $now->format('Y-m-d'))->where('due_date <=', $now->add(new DateInterval('P7D'))->format('Y-m-d'))->countAllResults();
        return ['open' => $open, 'overdue' => $overdue, 'due_soon' => $dueSoon, 'amount' => (float) ($amount['value'] ?? 0)];
    }

    private function goalSummary(DateTimeImmutable $now): array
    {
        if (!$this->db->tableExists('ceo_goals')) {
            return ['active' => 0, 'off_track' => 0, 'overdue' => 0];
        }
        $active = $this->db->table('ceo_goals')->whereNotIn('LOWER(status)', ['completed', 'cancelled'])->countAllResults();
        $offTrack = $this->db->table('ceo_goals')->whereIn('LOWER(status)', ['at_risk', 'off_track'])->countAllResults();
        $overdue = $this->db->table('ceo_goals')->whereNotIn('LOWER(status)', ['completed', 'cancelled'])->where('period_end <', $now->format('Y-m-d'))->countAllResults();
        return ['active' => $active, 'off_track' => $offTrack, 'overdue' => $overdue];
    }

    private function cashBalance(): ?float
    {
        if (!$this->db->tableExists('akun') || !$this->db->tableExists('jurnal')) {
            return null;
        }
        $row = $this->db->table('jurnal')
            ->select('COALESCE(SUM(jurnal.debit - jurnal.kredit), 0) AS balance', false)
            ->join('akun', 'akun.id = jurnal.akun_id', 'inner')
            ->whereIn('LOWER(akun.kategori)', ['kas & bank', 'kas', 'bank'])
            ->where('jurnal.deleted_at', null)
            ->where('akun.deleted_at', null)
            ->get()->getRowArray();
        return isset($row['balance']) ? (float) $row['balance'] : null;
    }

    private function revenueTrend(DateTimeImmutable $start, DateTimeImmutable $end): array
    {
        if (!$this->db->tableExists('transaksi')) {
            return [];
        }
        $rows = $this->db->table('transaksi')
            ->select('DATE(created_at) AS bucket, COALESCE(SUM(total),0) AS revenue, COUNT(*) AS transactions', false)
            ->where('status', 'confirmed')
            ->where('created_at >=', $start->format('Y-m-d H:i:s'))
            ->where('created_at <=', $end->format('Y-m-d H:i:s'))
            ->groupStart()->where('payment_method !=', 'poin')->orWhere('payment_method', null)->groupEnd()
            ->groupBy('DATE(created_at)', false)->orderBy('bucket', 'ASC')->get()->getResultArray();
        return array_map(static fn(array $row): array => [
            'date' => $row['bucket'],
            'revenue' => (float) $row['revenue'],
            'transactions' => (int) $row['transactions'],
        ], $rows);
    }

    private function recentTransactions(DateTimeImmutable $start, DateTimeImmutable $end): array
    {
        if (!$this->db->tableExists('transaksi')) {
            return [];
        }
        return $this->db->table('transaksi')
            ->select('transaksi.id, transaksi.invoice_number, transaksi.product_name, transaksi.total, transaksi.status, transaksi.created_at, users.name AS user_name')
            ->join('users', 'users.id = transaksi.user_id', 'left')
            ->where('transaksi.created_at >=', $start->format('Y-m-d H:i:s'))
            ->where('transaksi.created_at <=', $end->format('Y-m-d H:i:s'))
            ->orderBy('transaksi.created_at', 'DESC')->limit(8)->get()->getResultArray();
    }

    private function upcomingEvents(DateTimeImmutable $now): array
    {
        if (!$this->db->tableExists('layanan_event')) {
            return [];
        }
        return $this->db->table('layanan_event')
            ->select('id, title, type, event_date, event_end_date, location, zoom_link, current_participants, max_participants, status')
            ->where('event_date >=', $now->format('Y-m-d H:i:s'))
            ->whereNotIn('LOWER(status)', ['cancelled', 'tidak aktif', 'draft'])
            ->orderBy('event_date', 'ASC')->limit(6)->get()->getResultArray();
    }

    private function pendingApprovals(): array
    {
        if (!$this->db->tableExists('ea_approvals')) {
            return [];
        }
        return $this->db->table('ea_approvals')
            ->whereIn('LOWER(status)', ['pending', 'waiting', 'submitted'])
            ->orderBy("CASE LOWER(priority) WHEN 'urgent' THEN 1 WHEN 'high' THEN 2 ELSE 3 END", '', false)
            ->orderBy('due_date', 'ASC')->limit(8)->get()->getResultArray();
    }

    private function criticalConversations(DateTimeImmutable $now): array
    {
        if (!$this->db->tableExists('cs_conversations')) {
            return [];
        }
        return $this->db->table('cs_conversations')
            ->select('id, customer_name, platform, status, assigned_to, last_message, last_message_at, sla_started_at')
            ->whereIn('UPPER(status)', ['NEW', 'AUTO_REPLIED', 'UNREAD', 'IN_PROGRESS', 'FOLLOW_UP'])
            ->orderBy('last_message_at', 'ASC')->limit(5)->get()->getResultArray();
    }

    private function activeGoals(): array
    {
        if (!$this->db->tableExists('ceo_goals')) {
            return [];
        }
        return $this->db->table('ceo_goals')->whereNotIn('LOWER(status)', ['cancelled'])
            ->orderBy('period_end', 'ASC')->limit(6)->get()->getResultArray();
    }

    private function buildAlerts(array $summary): array
    {
        $alerts = [];
        if ($summary['approvals']['overdue'] > 0) {
            $alerts[] = $this->alert('critical', 'Approval melewati tenggat', $summary['approvals']['overdue'] . ' keputusan menunggu melewati due date.', 'ea_approvals', '/ceo/dashboard#approvals');
        }
        if ($summary['invoices']['overdue'] > 0) {
            $alerts[] = $this->alert('high', 'Invoice overdue', $summary['invoices']['overdue'] . ' invoice terbuka telah melewati jatuh tempo.', 'customer_invoices', '/ceo/dashboard#finance');
        }
        if ($summary['bills']['overdue'] > 0) {
            $alerts[] = $this->alert('critical', 'Tagihan operasional overdue', $summary['bills']['overdue'] . ' tagihan belum diselesaikan.', 'ceo_bills', '/ceo/dashboard#finance');
        }
        if ($summary['crm']['sla_breach'] > 0) {
            $alerts[] = $this->alert('high', 'SLA CRM terlewati', $summary['crm']['sla_breach'] . ' percakapan aktif lebih dari 24 jam.', 'cs_conversations', '/ceo/dashboard#crm');
        }
        if ($summary['goals']['off_track'] > 0) {
            $alerts[] = $this->alert('medium', 'Target strategis off-track', $summary['goals']['off_track'] . ' target membutuhkan intervensi.', 'ceo_goals', '/ceo/dashboard#goals');
        }
        return $alerts;
    }

    private function alert(string $severity, string $title, string $reason, string $source, string $link): array
    {
        return ['severity' => $severity, 'title' => $title, 'reason' => $reason, 'source' => $source, 'link' => $link];
    }

    private function growth(float|int $current, float|int $previous): ?float
    {
        if ((float) $previous === 0.0) {
            return (float) $current === 0.0 ? 0.0 : null;
        }
        return round((($current - $previous) / abs($previous)) * 100, 1);
    }
}
