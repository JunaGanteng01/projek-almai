<?php

namespace App\Controllers\LaporanKegiatan;

use App\Controllers\BaseController;
use App\Models\TransaksiModel;

class Transaksi extends BaseController
{
    protected $transaksiModel;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiModel();
    }

    public function index()
    {
        $status = $this->request->getGet('status');
        $search = $this->request->getGet('search');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $product = $this->request->getGet('product');

        $query = $this->transaksiModel->getWithUser();

        if ($status && $status !== 'all') {
            $query->where('transaksi.status', $status);
        }

        if ($search) {
            $query->groupStart()
                ->like('users.name', $search)
                ->orLike('transaksi.invoice_number', $search)
                ->orLike('transaksi.product_name', $search)
                ->groupEnd();
        }

        if ($startDate) {
            $query->where('DATE(transaksi.created_at) >=', $startDate);
        }
        if ($endDate) {
            $query->where('DATE(transaksi.created_at) <=', $endDate);
        }

        if ($product && $product !== 'all') {
            $query->where('transaksi.product_name', $product);
        }

        $transaksiList = $query->paginate(20, 'transaksi');

        // Get unique products for filter dropdown
        $productList = $this->transaksiModel->select('product_name')
            ->distinct()
            ->where('product_name IS NOT NULL')
            ->orderBy('product_name', 'ASC')
            ->findAll();

        return view('laporan-kegiatan/transaksi/index', [
            'title' => 'Data Transaksi',
            'activeMenu' => 'transaksi',
            'transaksiList' => $transaksiList,
            'pager' => $this->transaksiModel->pager,
            'currentStatus' => $status,
            'search' => $search,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currentProduct' => $product,
            'productList' => $productList
        ]);
    }

    public function view($id)
    {
        $transaksi = $this->transaksiModel->getWithUser()->where('transaksi.id', $id)->first();
        if (!$transaksi) {
            return redirect()->to('/laporan-kegiatan/transaksi')->with('error', 'Transaksi tidak ditemukan');
        }

        return view('laporan-kegiatan/transaksi/view', [
            'title' => 'Detail Transaksi',
            'activeMenu' => 'transaksi',
            'transaksi' => $transaksi
        ]);
    }

    public function exportCsv()
    {
        $status = $this->request->getGet('status');
        $search = $this->request->getGet('search');
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $product = $this->request->getGet('product');

        $query = $this->transaksiModel->getWithUser();

        if ($status && $status !== 'all') {
            $query->where('transaksi.status', $status);
        }

        if ($search) {
            $query->groupStart()
                ->like('users.name', $search)
                ->orLike('transaksi.invoice_number', $search)
                ->orLike('transaksi.product_name', $search)
                ->groupEnd();
        }

        if ($startDate) {
            $query->where('DATE(transaksi.created_at) >=', $startDate);
        }
        if ($endDate) {
            $query->where('DATE(transaksi.created_at) <=', $endDate);
        }

        if ($product && $product !== 'all') {
            $query->where('transaksi.product_name', $product);
        }

        $transaksiList = $query->orderBy('transaksi.created_at', 'DESC')->findAll();

        // Dynamic Filename based on filters
        $filenameParts = ['Transaksi'];
        if ($status && $status !== 'all') $filenameParts[] = ucfirst($status);
        if ($product && $product !== 'all') $filenameParts[] = preg_replace('/[^A-Za-z0-9]/', '', $product);
        if ($startDate) $filenameParts[] = 'Dari_' . $startDate;
        if ($endDate) $filenameParts[] = 'Sampai_' . $endDate;
        $filenameParts[] = date('Ymd_His');
        
        $filename = implode('_', $filenameParts) . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        
        // BOM for Excel UTF-8
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Header
        fputcsv($output, ['No', 'Invoice', 'Tanggal', 'User', 'Email', 'Produk', 'Total', 'Metode Bayar', 'Status'], ';');

        // Data
        $no = 1;
        foreach ($transaksiList as $row) {
            fputcsv($output, [
                $no++,
                $row['invoice_number'],
                date('d/m/Y H:i', strtotime($row['created_at'])),
                $row['user_name'] ?? 'Guest',
                $row['user_email'] ?? '-',
                $row['product_name'] ?? ($row['layanan_name'] ?? 'N/A'),
                $row['total'],
                ucfirst($row['payment_method']),
                ucfirst($row['status'])
            ], ';');
        }

        fclose($output);
        exit;
    }
}
