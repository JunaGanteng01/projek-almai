<?php

namespace App\Controllers\Keuangan;

use App\Controllers\BaseController;
use App\Models\TransaksiModel;
use App\Models\WithdrawalModel;
use App\Models\NotificationModel;

class Transaksi extends BaseController
{
    protected $wdModel;

    public function __construct()
    {
        $this->wdModel = new WithdrawalModel();
    }

    // This handles both 'Transaksi' overview and 'Penjualan' as per previous unified view
    public function index()
    {
        $transaksiModel = new TransaksiModel();

        $search = $this->request->getGet('search');
        $status = $this->request->getGet('status');
        $productType = $this->request->getGet('product_type');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');

        $query = $transaksiModel->select('transaksi.*, users.name as user_name, users.email as user_email, layanan.name as layanan_name, layanan.subcategory as layanan_subcategory')
            ->join('users', 'users.id = transaksi.user_id', 'left')
            ->join('layanan', 'layanan.id = transaksi.layanan_id', 'left');

        if ($search) {
            $query->groupStart()
                ->like('transaksi.invoice_number', $search)
                ->orLike('users.name', $search)
                ->groupEnd();
        }

        if ($status && $status !== 'all') {
            $query->where('transaksi.status', $status);
        }

        if ($productType && $productType !== 'all') {
            $query->where('transaksi.product_type', $productType);
        }

        if ($dateFrom) {
            $query->where('transaksi.created_at >=', $dateFrom . ' 00:00:00');
        }

        if ($dateTo) {
            $query->where('transaksi.created_at <=', $dateTo . ' 23:59:59');
        }

        $transaksiList = $query->orderBy('transaksi.created_at', 'DESC')->paginate(20);

        // Get Statistics for Dashboard Cards
        $totalRevenue = $transaksiModel->where('status', 'confirmed')->selectSum('total')->first()['total'] ?? 0;
        $totalPending = (clone $transaksiModel)->where('status', 'pending')->countAllResults();
        $totalPaid = (clone $transaksiModel)->where('status', 'paid')->countAllResults();
        $totalConfirmed = (clone $transaksiModel)->where('status', 'confirmed')->countAllResults();
        $totalRefunded = (clone $transaksiModel)->where('status', 'refunded')->countAllResults();

        // Get unique product types for filter dropdown
        $productTypes = $transaksiModel->select('product_type')->groupBy('product_type')->findAll();

        // Active menu logic based on route usually, but here we can force or detect
        // If route is 'penjualan', visually it's sales.
        $uri = service('uri');
        $activeMenu = $uri->getSegment(2) === 'penjualan' ? 'penjualan' : 'transaksi';

        $data = [
            'title' => 'Daftar Transaksi',
            'activeMenu' => $activeMenu,
            'transaksi' => $transaksiList,
            'pager' => $transaksiModel->pager,
            'totalRevenue' => $totalRevenue,
            'totalPending' => $totalPending,
            'totalPaid' => $totalPaid,
            'totalConfirmed' => $totalConfirmed,
            'totalRefunded' => $totalRefunded,
            'productTypes' => $productTypes,
            'currentSearch' => $search,
            'currentStatus' => $status,
            'currentProductType' => $productType,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo
        ];

        return view('keuangan/transaksi/index', $data);
    }

    public function invoice($invoiceNumber)
    {
        $transaksiModel = new TransaksiModel();

        $transaksi = $transaksiModel->select('transaksi.*, users.name as user_name, users.email as user_email, layanan.name as layanan_name, layanan.subcategory as layanan_subcategory')
            ->join('users', 'users.id = transaksi.user_id', 'left')
            ->join('layanan', 'layanan.id = transaksi.layanan_id', 'left')
            ->where('invoice_number', $invoiceNumber)
            ->first();

        if (!$transaksi) {
            return redirect()->back()->with('error', 'Invoice not found');
        }

        $data = [
            'title' => 'Invoice ' . $invoiceNumber,
            'trx' => $transaksi
        ];

        return view('keuangan/transaksi/invoice', $data);
    }

    public function withdrawals()
    {
        $status = $this->request->getGet('status');

        $builder = $this->wdModel->select('withdrawals.*, wpa.name as wpa_name')
            ->join('wpa', 'wpa.id = withdrawals.wpa_id');

        if ($status) {
            $builder->where('withdrawals.status', $status);
        }

        $withdrawals = $builder->orderBy('withdrawals.created_at', 'DESC')->paginate(20);

        $stats = [
            'total_pending' => $this->wdModel->where('status', 'pending')->selectSum('amount')->first()['amount'] ?? 0,
            'total_approved' => $this->wdModel->where('status', 'approved')->selectSum('amount')->first()['amount'] ?? 0,
            'total_completed' => $this->wdModel->where('status', 'completed')->selectSum('amount')->first()['amount'] ?? 0,
            'count_pending' => $this->wdModel->where('status', 'pending')->countAllResults(),
            'count_approved' => $this->wdModel->where('status', 'approved')->countAllResults(),
            'count_completed' => $this->wdModel->where('status', 'completed')->countAllResults(),
        ];

        $data = [
            'title' => 'Manajemen Penarikan WPA',
            'activeMenu' => 'withdrawals',
            'withdrawals' => $withdrawals,
            'pager' => $this->wdModel->pager,
            'currentStatus' => $status,
            'stats' => $stats
        ];

        return view('keuangan/withdrawals/index', $data);
    }

    public function updateWithdrawalStatus($id)
    {
        $status = $this->request->getPost('status');
        $notes = $this->request->getPost('admin_notes');

        $withdrawal = $this->wdModel->find($id);

        if (!$withdrawal) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $this->wdModel->update($id, [
            'status' => $status,
            'admin_notes' => $notes,
            'processed_at' => ($status == 'processed') ? date('Y-m-d H:i:s') : null
        ]);

        // Send Notification
        $notifModel = new NotificationModel();
        $title = ($status == 'processed') ? 'Penarikan Disetujui' : 'Penarikan Ditolak';
        $message = ($status == 'processed')
            ? "Permintaan penarikan Rp " . number_format($withdrawal['amount'], 0, ',', '.') . " telah diproses."
            : "Permintaan penarikan ditolak. Alasan: $notes";

        $notifModel->createNotification(
            $withdrawal['wpa_id'],
            $title,
            $message,
            'withdrawal',
            base_url('wpa/dashboard/withdrawals')
        );

        return redirect()->back()->with('success', 'Status penarikan diperbarui');
    }
}
