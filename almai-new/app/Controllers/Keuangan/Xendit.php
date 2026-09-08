<?php

namespace App\Controllers\Keuangan;

use App\Controllers\BaseController;
use App\Libraries\XenditService;

class Xendit extends BaseController
{
    protected $xendit;

    public function __construct()
    {
        $this->xendit = new XenditService();
    }

    public function index()
    {
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');
        $search = $this->request->getGet('search');

        // Build Filters
        $filters = ['limit' => 50];
        if ($startDate) $filters['created[gte]'] = $startDate . 'T00:00:00Z';
        if ($endDate) $filters['created[lte]'] = $endDate . 'T23:59:59Z';
        if ($search) $filters['reference_id'] = $search;

        // Get Balances (All Types)
        $cashBalance = $this->xendit->getBalance('CASH');
        $holdingBalance = $this->xendit->getBalance('HOLDING');
        $taxBalance = $this->xendit->getBalance('TAX');
        
        // Get Transactions
        $transactionsResult = $this->xendit->getTransactions($filters);

        // Get Disbursements (API based)
        $disbursementsResult = $this->xendit->getDisbursements($filters);

        // Merge and calculate total withdraw
        $allDisbursements = $disbursementsResult['success'] ? $disbursementsResult['data'] : [];
        $totalWithdraw = 0;

        // 1. Sum up from Disbursements API (Automated payouts)
        if ($disbursementsResult['success']) {
            foreach ($disbursementsResult['data'] as $disb) {
                if (in_array($disb['status'], ['COMPLETED', 'SUCCESS'])) {
                    $totalWithdraw += abs($disb['amount']);
                }
            }
        }
        
        // 2. Sum up Manual Withdrawals from Transactions List
        // We only add types that are NOT Disbursements to avoid double counting
        if ($transactionsResult['success']) {
            foreach ($transactionsResult['data']['data'] as $trx) {
                if ($trx['status'] === 'SUCCESS') {
                    // Manual withdrawals or transfers out are not in the disbursements API
                    if (in_array($trx['type'], ['WITHDRAWAL', 'TRANSFER_OUT', 'REMITTANCE_PAYOUT'])) {
                        $totalWithdraw += abs($trx['amount']);
                        
                        // Also add to the list for the UI tab if it's not already there
                        // (Manual withdrawals don't have the same ID format as disbursements)
                        $allDisbursements[] = [
                            'bank_code' => $trx['channel_code'] ?? 'N/A',
                            'account_number' => $trx['account_identifier'] ?? '-',
                            'external_id' => $trx['reference_id'] ?? $trx['id'],
                            'amount' => abs($trx['amount']),
                            'status' => $trx['status'],
                            'created' => $trx['created']
                        ];
                    }
                }
            }
        }

        // Sort by date DESC
        usort($allDisbursements, function($a, $b) {
            return strtotime($b['created']) - strtotime($a['created']);
        });

        $cash = $cashBalance['success'] ? $cashBalance['data']['balance'] : 0;
        $holding = $holdingBalance['success'] ? $holdingBalance['data']['balance'] : 0;
        $tax = $taxBalance['success'] ? $taxBalance['data']['balance'] : 0;

        $data = [
            'title' => 'Xendit Dashboard',
            'activeMenu' => 'xendit',
            'balances' => [
                'CASH' => $cash,
                'HOLDING' => $holding,
                'TAX' => $tax,
                'TOTAL' => $cash + $holding + $tax,
                'WITHDRAW' => $totalWithdraw
            ],
            'transactions' => $transactionsResult['success'] ? $transactionsResult['data']['data'] : [],
            'disbursements' => $allDisbursements,
            'filters' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'search' => $search
            ],
            'error' => (!$cashBalance['success'] || !$transactionsResult['success']) ? ($cashBalance['error'] ?? $transactionsResult['error']) : null
        ];

        return view('keuangan/xendit/index', $data);
    }

    public function createReport()
    {
        $type = $this->request->getPost('type') ?? 'TRANSACTION';
        $from = $this->request->getPost('from') . 'T00:00:00Z';
        $to = $this->request->getPost('to') . 'T23:59:59Z';

        $result = $this->xendit->createReport($type, $from, $to);

        if ($result['success']) {
            return redirect()->back()->with('success', 'Permintaan laporan berhasil dibuat. ID: ' . $result['data']['id']);
        } else {
            return redirect()->back()->with('error', 'Gagal membuat laporan: ' . $result['error']);
        }
    }
}
