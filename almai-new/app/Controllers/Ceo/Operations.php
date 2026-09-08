<?php

namespace App\Controllers\Ceo;

use App\Controllers\BaseController;
use App\Models\AuditLogModel;
use App\Models\CeoGoalModel;
use App\Models\NotificationModel;

class Operations extends BaseController
{
    public function storeBill()
    {
        $rules = [
            'vendor' => 'required|max_length[190]',
            'reference' => 'permit_empty|max_length[100]',
            'amount' => 'required|decimal|greater_than[0]',
            'currency' => 'required|in_list[IDR,USD,SGD]',
            'due_date' => 'required|valid_date[Y-m-d]',
            'priority' => 'required|in_list[medium,high,urgent]',
            'description' => 'permit_empty|max_length[2000]',
        ];
        if (!$this->validate($rules)) return redirect()->to('/ceo/dashboard#finance')->withInput()->with('error', implode(' ', $this->validator->getErrors()));

        $db = \Config\Database::connect();
        $db->transBegin();
        try {
            $now = date('Y-m-d H:i:s');
            $bill = [
                'vendor' => trim((string) $this->request->getPost('vendor')),
                'reference' => trim((string) $this->request->getPost('reference')) ?: null,
                'description' => trim((string) $this->request->getPost('description')) ?: null,
                'amount' => (float) $this->request->getPost('amount'),
                'currency' => (string) $this->request->getPost('currency'),
                'due_date' => (string) $this->request->getPost('due_date'),
                'status' => 'submitted',
                'created_by' => (int) session()->get('userId'),
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $db->table('ceo_bills')->insert($bill);
            $billId = (int) $db->insertID();
            $approval = [
                'approval_code' => 'BILL-' . date('Ymd') . '-' . str_pad((string) $billId, 5, '0', STR_PAD_LEFT),
                'title' => 'Otorisasi tagihan ' . $bill['vendor'],
                'module' => 'Finance',
                'source_type' => 'bill',
                'source_id' => $billId,
                'requested_by' => (string) (session()->get('userName') ?: 'CEO'),
                'requested_by_user_id' => (int) session()->get('userId'),
                'amount' => $bill['amount'],
                'description' => $bill['description'],
                'priority' => (string) $this->request->getPost('priority'),
                'due_date' => $bill['due_date'] . ' 17:00:00',
                'status' => 'pending',
                'version' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $db->table('ea_approvals')->insert($approval);
            $approvalId = (int) $db->insertID();
            $db->table('ceo_bills')->where('id', $billId)->update(['approval_id' => $approvalId]);
            if ($db->transStatus() === false) throw new \RuntimeException('Gagal menyimpan tagihan dan approval.');
            $db->transCommit();
            AuditLogModel::record('Submit executive bill', 'ceo_bill', $billId, ['vendor' => $bill['vendor'], 'amount' => $bill['amount'], 'approval_id' => $approvalId]);
            (new NotificationModel())->createNotification((int) session()->get('userId'), 'Tagihan menunggu keputusan', $approval['title'] . ' telah masuk Approval Center.', 'info', '/ceo/dashboard#approvals');
            return redirect()->to('/ceo/dashboard#approvals')->with('success', 'Tagihan disimpan dan otomatis masuk Approval Center.');
        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', 'Create CEO bill failed: ' . $e->getMessage());
            return redirect()->to('/ceo/dashboard#finance')->withInput()->with('error', 'Tagihan gagal disimpan.');
        }
    }

    public function storeGoal()
    {
        $rules = [
            'objective' => 'required|max_length[255]', 'owner' => 'required|max_length[190]',
            'period_start' => 'required|valid_date[Y-m-d]', 'period_end' => 'required|valid_date[Y-m-d]',
            'target_value' => 'required|decimal|greater_than[0]', 'actual_value' => 'required|decimal|greater_than_equal_to[0]',
            'unit' => 'required|in_list[number,percent,IDR,users,events]', 'confidence' => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[100]',
            'status' => 'required|in_list[on_track,at_risk,off_track,completed]', 'update_note' => 'permit_empty|max_length[2000]',
        ];
        if (!$this->validate($rules)) return redirect()->to('/ceo/dashboard#goals')->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        if ($this->request->getPost('period_end') < $this->request->getPost('period_start')) return redirect()->to('/ceo/dashboard#goals')->withInput()->with('error', 'Tanggal akhir sasaran tidak boleh sebelum tanggal mulai.');

        $model = new CeoGoalModel();
        $payload = [
            'objective' => trim((string) $this->request->getPost('objective')), 'owner' => trim((string) $this->request->getPost('owner')),
            'period_start' => $this->request->getPost('period_start'), 'period_end' => $this->request->getPost('period_end'),
            'target_value' => (float) $this->request->getPost('target_value'), 'actual_value' => (float) $this->request->getPost('actual_value'),
            'unit' => $this->request->getPost('unit'), 'confidence' => (int) $this->request->getPost('confidence'),
            'status' => $this->request->getPost('status'), 'update_note' => trim((string) $this->request->getPost('update_note')) ?: null,
            'created_by' => (int) session()->get('userId'),
        ];
        $id = (int) $model->insert($payload);
        AuditLogModel::record('Create strategic goal', 'ceo_goal', $id, $payload);
        return redirect()->to('/ceo/dashboard#goals')->with('success', 'Sasaran strategis berhasil disimpan.');
    }
}
