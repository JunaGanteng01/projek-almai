<?php

namespace App\Services;

use CodeIgniter\Database\BaseConnection;
use RuntimeException;

class ExecutiveApprovalService
{
    private BaseConnection $db;

    public function __construct(?BaseConnection $db = null)
    {
        $this->db = $db ?? \Config\Database::connect();
    }

    public function decide(int $id, string $decision, string $reason, int $expectedVersion, int $actorId): array
    {
        $this->db->transBegin();
        try {
            $result = $this->decideWithinTransaction($id, $decision, $reason, $expectedVersion, $actorId);
            if ($this->db->transStatus() === false) throw new RuntimeException('Transaksi database gagal.');
            $this->db->transCommit();
            return $result;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    public function decideBatch(array $ids, string $decision, string $reason, int $actorId): array
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $ids), static fn(int $id): bool => $id > 0)));
        if (!$ids || count($ids) > 50) throw new RuntimeException('Pilih 1 sampai 50 approval.');

        $this->db->transBegin();
        try {
            $rows = $this->db->table('ea_approvals')->whereIn('id', $ids)->get()->getResultArray();
            if (count($rows) !== count($ids)) throw new RuntimeException('Sebagian approval tidak ditemukan.');
            $results = [];
            foreach ($rows as $row) {
                $results[] = $this->decideWithinTransaction((int) $row['id'], $decision, $reason, (int) ($row['version'] ?? 1), $actorId);
            }
            if ($this->db->transStatus() === false) throw new RuntimeException('Batch approval gagal disimpan.');
            $this->db->transCommit();
            return $results;
        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }

    private function decideWithinTransaction(int $id, string $decision, string $reason, int $expectedVersion, int $actorId): array
    {
        $decision = strtolower($decision);
        if (!in_array($decision, ['approved', 'rejected'], true)) {
            throw new RuntimeException('Keputusan approval tidak valid.');
        }
        if (trim($reason) === '' || mb_strlen(trim($reason)) < 5) {
            throw new RuntimeException('Alasan keputusan minimal 5 karakter.');
        }

        try {
            $approval = $this->db->table('ea_approvals')->where('id', $id)->get()->getRowArray();
            if (!$approval) {
                throw new RuntimeException('Approval tidak ditemukan.');
            }
            if (!in_array(strtolower((string) $approval['status']), ['pending', 'waiting', 'submitted'], true)) {
                throw new RuntimeException('Approval sudah diproses atau tidak lagi dapat diputuskan.');
            }
            $currentVersion = (int) ($approval['version'] ?? 1);
            if ($currentVersion !== $expectedVersion) {
                throw new RuntimeException('Data approval telah berubah. Muat ulang halaman sebelum mengambil keputusan.');
            }

            $now = date('Y-m-d H:i:s');
            $payload = [
                'status' => $decision,
                'approver_id' => $actorId,
                'decision_reason' => trim($reason),
                'updated_at' => $now,
                $decision === 'approved' ? 'approved_at' : 'rejected_at' => $now,
            ];

            $builder = $this->db->table('ea_approvals')->where('id', $id)->where('version', $expectedVersion);
            $builder->set($payload)->set('version', 'version + 1', false)->update();
            if ($this->db->affectedRows() !== 1) {
                throw new RuntimeException('Keputusan tidak disimpan karena terjadi pembaruan bersamaan.');
            }

            $this->syncSource($approval, $decision, $reason);
            $this->writeAudit($actorId, $id, $approval, array_merge($approval, $payload, ['version' => $currentVersion + 1]));
            $this->notifyRequester($approval, $decision, $reason);

            return ['id' => $id, 'status' => $decision, 'version' => $currentVersion + 1];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    private function syncSource(array $approval, string $decision, string $reason): void
    {
        $sourceType = strtolower((string) ($approval['source_type'] ?? ''));
        $sourceId = (int) ($approval['source_id'] ?? 0);
        if ($sourceId <= 0) {
            return;
        }

        if ($sourceType === 'bill' && $this->db->tableExists('ceo_bills')) {
            $this->db->table('ceo_bills')->where('id', $sourceId)->update([
                'status' => $decision === 'approved' ? 'authorized' : 'rejected',
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        if ($sourceType === 'withdrawal' && $this->db->tableExists('withdrawals')) {
            $this->db->table('withdrawals')->where('id', $sourceId)->where('status', 'pending')->update([
                'status' => $decision,
                'admin_notes' => trim($reason),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    private function writeAudit(int $actorId, int $approvalId, array $before, array $after): void
    {
        if (!$this->db->tableExists('audit_logs')) {
            return;
        }
        $request = service('request');
        $this->db->table('audit_logs')->insert([
            'user_id' => $actorId,
            'action' => 'Executive approval decision',
            'module' => 'ceo_approval',
            'target_id' => $approvalId,
            'details' => json_encode([
                'before' => ['status' => $before['status'] ?? null, 'version' => $before['version'] ?? 1],
                'after' => ['status' => $after['status'] ?? null, 'version' => $after['version'] ?? null, 'reason' => $after['decision_reason'] ?? null],
            ], JSON_UNESCAPED_UNICODE),
            'ip_address' => $request->getIPAddress(),
            'user_agent' => substr($request->getUserAgent()->getAgentString(), 0, 500),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function notifyRequester(array $approval, string $decision, string $reason): void
    {
        $requesterId = (int) ($approval['requested_by_user_id'] ?? 0);
        if ($requesterId <= 0 || !$this->db->tableExists('notifications')) {
            return;
        }
        $this->db->table('notifications')->insert([
            'user_id' => $requesterId,
            'title' => $decision === 'approved' ? 'Pengajuan disetujui CEO' : 'Pengajuan ditolak CEO',
            'message' => ($approval['title'] ?? 'Pengajuan') . ': ' . trim($reason),
            'type' => $decision === 'approved' ? 'success' : 'error',
            'link' => '/ceo/dashboard#approvals',
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
