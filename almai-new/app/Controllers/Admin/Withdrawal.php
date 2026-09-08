<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\WithdrawalModel;

class Withdrawal extends BaseController
{
    protected $wdModel;

    public function __construct()
    {
        $this->wdModel = new WithdrawalModel();
    }

    public function index()
    {
        $status = $this->request->getGet('status');
        
        $builder = $this->wdModel->select('withdrawals.*, wpa.name as wpa_name, cwpa.name as cwpa_name')
            ->join('wpa', 'wpa.id = withdrawals.wpa_id', 'left')
            ->join('cwpa', 'cwpa.id = withdrawals.cwpa_id', 'left');

        if ($status) {
            $builder->where('withdrawals.status', $status);
        }

        $withdrawals = $builder->orderBy('withdrawals.created_at', 'DESC')->paginate(20);

        // Calculate stats
        $stats = [
            'total_pending' => $this->wdModel->where('status', 'pending')->selectSum('amount')->first()['amount'] ?? 0,
            'count_pending' => $this->wdModel->where('status', 'pending')->countAllResults(),
            'total_approved' => $this->wdModel->where('status', 'approved')->selectSum('amount')->first()['amount'] ?? 0,
            'count_approved' => $this->wdModel->where('status', 'approved')->countAllResults(),
            'total_completed' => $this->wdModel->where('status', 'completed')->selectSum('amount')->first()['amount'] ?? 0,
            'count_completed' => $this->wdModel->where('status', 'completed')->countAllResults(),
        ];

        return view('admin/withdrawals/index', [
            'title' => 'Manajemen Withdraw',
            'activeMenu' => 'withdrawals',
            'withdrawals' => $withdrawals,
            'pager' => $this->wdModel->pager,
            'currentStatus' => $status,
            'stats' => $stats
        ]);
    }

    public function updateStatus($id)
    {
        $status = $this->request->getPost('status');
        $notes = $this->request->getPost('admin_notes');

        if (!in_array($status, ['pending', 'approved', 'rejected', 'completed'])) {
            return redirect()->back()->with('error', 'Status tidak valid.');
        }

        $this->wdModel->update($id, [
            'status' => $status,
            'admin_notes' => $notes
        ]);

        // --- NOTIFY USER OF STATUS CHANGE ---
        $wd = $this->wdModel->find($id);
        $notifModel = new \App\Models\NotificationModel();
        
        // Get User ID from WPA or CWPA
        $userId = null;
        if (!empty($wd['wpa_id'])) {
            $wpa = (new \App\Models\WpaModel())->find($wd['wpa_id']);
            $userId = $wpa['user_id'];
            $targetUrl = '/wpa/dashboard/withdraw';
        } elseif (!empty($wd['cwpa_id'])) {
            $cwpa = (new \App\Models\CwpaModel())->find($wd['cwpa_id']);
            $userId = $cwpa['user_id'];
            $targetUrl = '/cwpa/dashboard/withdraw';
        }

        if ($userId) {
            $statusLabels = [
                'approved' => ['Disetujui ✅', 'success'],
                'completed' => ['Selesai Ditransfer 💸', 'success'],
                'rejected' => ['Ditolak ❌', 'error'],
                'pending' => ['Kembali ke Pending ⏳', 'info'],
            ];
            $label = $statusLabels[$status] ?? ['Status Diperbarui', 'info'];

            $notifModel->createNotification(
                $userId,
                'Status Withdraw: ' . $label[0],
                'Penarikan dana Anda sebesar Rp ' . number_format($wd['amount'], 0, ',', '.') . ' berstatus ' . strtoupper($status) . '.' . ($notes ? ' Catatan: ' . $notes : ''),
                $label[1],
                $targetUrl
            );
        }
        // --- END NOTIFY ---

        return redirect()->back()->with('success', 'Status penarikan berhasil diperbarui.');
    }
}
