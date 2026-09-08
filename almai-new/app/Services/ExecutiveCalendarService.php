<?php

namespace App\Services;

use CodeIgniter\Database\BaseConnection;

class ExecutiveCalendarService
{
    private BaseConnection $db;

    public function __construct(?BaseConnection $db = null)
    {
        $this->db = $db ?? \Config\Database::connect();
    }

    public function getEvents(?string $start = null, ?string $end = null): array
    {
        $events = [];
        $start ??= date('Y-m-01 00:00:00');
        $end ??= date('Y-m-t 23:59:59');

        if ($this->db->tableExists('ea_meetings')) {
            $rows = $this->db->table('ea_meetings')->where('start_time >=', $start)->where('start_time <=', $end)
                ->whereNotIn('LOWER(status)', ['cancelled'])->get()->getResultArray();
            foreach ($rows as $row) {
                $events[] = $this->event('meeting', $row['id'], $row['title'], $row['start_time'], $row['end_time'] ?? null, $row['location'] ?? null, $row['meet_url'] ?? null, '#3b82f6', $row['priority'] ?? 'medium');
            }
        }

        if ($this->db->tableExists('ea_tasks')) {
            $rows = $this->db->table('ea_tasks')->where('deadline >=', $start)->where('deadline <=', $end)
                ->whereNotIn('LOWER(status)', ['completed', 'cancelled'])->get()->getResultArray();
            foreach ($rows as $row) {
                $events[] = $this->event('task', $row['id'], 'Deadline: ' . $row['title'], $row['deadline'], $row['deadline'], $row['assigned_to'] ?? 'Internal', null, '#10b981', $row['priority'] ?? 'medium');
            }
        }

        if ($this->db->tableExists('ea_reminders')) {
            $rows = $this->db->table('ea_reminders')->where('reminder_time >=', $start)->where('reminder_time <=', $end)
                ->whereNotIn('LOWER(status)', ['completed', 'cancelled', 'inactive'])->get()->getResultArray();
            foreach ($rows as $row) {
                $events[] = $this->event('reminder', $row['id'], 'Reminder: ' . $row['title'], $row['reminder_time'], $row['reminder_time'], 'System', null, '#eab308', $row['type'] ?? 'normal');
            }
        }

        if ($this->db->tableExists('layanan_event')) {
            $rows = $this->db->table('layanan_event')->where('event_date >=', $start)->where('event_date <=', $end)
                ->whereNotIn('LOWER(status)', ['cancelled', 'tidak aktif', 'draft'])->get()->getResultArray();
            foreach ($rows as $row) {
                $events[] = $this->event('webinar', $row['id'], $row['title'], $row['event_date'], $row['event_end_date'] ?? null, $row['location'] ?? 'Online', $this->validMeetingUrl($row['zoom_link'] ?? null), '#a855f7', 'medium');
            }
        }

        if ($this->db->tableExists('customer_invoices')) {
            $rows = $this->db->table('customer_invoices')->where('jatuh_tempo >=', substr($start, 0, 10))->where('jatuh_tempo <=', substr($end, 0, 10))
                ->whereIn('LOWER(status)', ['pending', 'unpaid', 'overdue', 'sent'])->get()->getResultArray();
            foreach ($rows as $row) {
                $events[] = $this->event('invoice', $row['id'], 'Jatuh tempo: ' . $row['invoice_number'], $row['jatuh_tempo'] . ' 09:00:00', $row['jatuh_tempo'] . ' 09:00:00', $row['customer_name'] ?? null, null, '#f97316', 'high');
            }
        }

        if ($this->db->tableExists('ea_approvals') && $this->db->fieldExists('due_date', 'ea_approvals')) {
            $rows = $this->db->table('ea_approvals')->where('due_date >=', $start)->where('due_date <=', $end)
                ->whereIn('LOWER(status)', ['pending', 'waiting', 'submitted'])->get()->getResultArray();
            foreach ($rows as $row) {
                $events[] = $this->event('approval', $row['id'], 'Keputusan: ' . $row['title'], $row['due_date'], $row['due_date'], $row['module'] ?? null, null, '#ef4444', $row['priority'] ?? 'high');
            }
        }

        if ($this->db->tableExists('ceo_bills')) {
            $rows = $this->db->table('ceo_bills')->where('due_date >=', substr($start, 0, 10))->where('due_date <=', substr($end, 0, 10))
                ->whereIn('LOWER(status)', ['pending', 'submitted', 'approved', 'overdue'])->get()->getResultArray();
            foreach ($rows as $row) {
                $events[] = $this->event('bill', $row['id'], 'Tagihan: ' . $row['vendor'], $row['due_date'] . ' 09:00:00', $row['due_date'] . ' 09:00:00', $row['reference'] ?? null, null, '#f59e0b', 'high');
            }
        }

        usort($events, static fn(array $a, array $b): int => strcmp($a['start'], $b['start']));
        return $events;
    }

    private function event(string $type, int|string $id, string $title, string $start, ?string $end, ?string $location, ?string $url, string $color, string $priority): array
    {
        return [
            'id' => $type . '_' . $id,
            'source_id' => $id,
            'type' => $type,
            'title' => $title,
            'start' => $start,
            'end' => $end ?: $start,
            'location' => $location,
            'url' => $url,
            'colorCode' => $color,
            'priority' => strtolower($priority),
        ];
    }

    private function validMeetingUrl(?string $url): ?string
    {
        if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }
        return in_array(strtolower((string) parse_url($url, PHP_URL_SCHEME)), ['https'], true) ? $url : null;
    }
}
