<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\TransaksiModel;
use App\Models\SettingModel;

class Invoice extends BaseController
{
    protected $transaksiModel;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiModel();
    }

    public function index()
    {
        $userId = session()->get('userId');

        $transaksiList = $this->transaksiModel
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('user/invoice/index', [
            'title' => 'Riwayat Transaksi - Almai',
            'transaksiList' => $transaksiList,
            'activeMenu' => 'invoice'
        ]);
    }

    public function detail($invoiceNumber)
    {
        $userId = session()->get('userId');

        $transaksiItems = $this->transaksiModel
            ->select('transaksi.*, users.name as user_name, users.email as user_email')
            ->join('users', 'users.id = transaksi.user_id', 'left')
            ->where('invoice_number', $invoiceNumber)
            ->where('user_id', $userId)
            ->findAll();

        if (empty($transaksiItems)) {
            return redirect()->to('/user/invoice')->with('error', 'Invoice tidak ditemukan');
        }

        $transaksi = $transaksiItems[0]; // Use first item for header info

        // Get payment settings
        $settingModel = new SettingModel();
        $bankInfo = [
            'bank_name' => $settingModel->get('bank_name', 'Bank BCA'),
            'account_number' => $settingModel->get('bank_account', '1234567890'),
            'account_name' => $settingModel->get('bank_account_name', 'PT Alma Indonesia Raya'),
        ];

        // Get Xendit payment URL if payment method is xendit and status is pending
        $xenditUrl = null;
        if ($transaksi['payment_method'] === 'xendit' && $transaksi['status'] === 'pending') {
            // Try to get existing invoice from Xendit
            $xendit = new \App\Libraries\XenditService();
            $result = $xendit->getInvoiceByExternalId($invoiceNumber);

            if ($result['success'] && !empty($result['data'])) {
                $invoiceData = is_array($result['data']) && isset($result['data'][0]) ? $result['data'][0] : $result['data'];
                if (isset($invoiceData['invoice_url']) && $invoiceData['status'] === 'PENDING') {
                    $xenditUrl = $invoiceData['invoice_url'];
                }
            }
        }

        return view('user/invoice/detail', [
            'title' => 'Invoice ' . $invoiceNumber . ' - Almai',
            'transaksi' => $transaksi,
            'items' => $transaksiItems,
            'bankInfo' => $bankInfo,
            'xenditUrl' => $xenditUrl,
            'activeMenu' => 'invoice'
        ]);
    }
    public function ticket($invoiceNumber)
    {
        $userId = session()->get('userId');

        $transaksi = $this->transaksiModel
            ->where('invoice_number', $invoiceNumber)
            ->where('user_id', $userId)
            ->first();

        if (!$transaksi || $transaksi['product_type'] !== 'event' || $transaksi['status'] !== 'confirmed') {
            return redirect()->back()->with('error', 'Tiket tidak ditemukan atau belum lunas.');
        }

        // Fetch event details
        $events = \App\Controllers\Event::getEvents();
        $eventData = null;
        foreach ($events as $e) {
            if ($e['id'] == $transaksi['layanan_id']) {
                $eventData = $e;
                break;
            }
        }

        return view('user/invoice/ticket', [
            'transaksi' => $transaksi,
            'event' => $eventData,
            'user' => (new \App\Models\UserModel())->find($userId)
        ]);
    }
}
