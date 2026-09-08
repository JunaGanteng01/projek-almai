<?php

namespace App\Controllers\Webhook;

use App\Controllers\BaseController;
use App\Models\TransaksiModel;

class XenditWebhook extends BaseController
{
    public function incoming()
    {
        // 1. Verify Authentication Header
        $callbackToken = env('XENDIT_CALLBACK_TOKEN');
        $headerToken = $this->request->getHeaderLine('x-callback-token');

        if (!empty($callbackToken) && $headerToken !== $callbackToken) {
            log_message('error', 'Xendit Webhook Unauthorized: Invalid Token');
            return $this->response->setStatusCode(401)->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        // 2. Parse Payload
        $json = $this->request->getJSON(true);
        if (!$json) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Invalid payload']);
        }

        // Log incoming webhook
        log_message('info', 'Xendit Webhook received payload for ID: ' . ($json['id'] ?? 'unknown'));

        // 3. Process Invoice Status
        $externalId = $json['external_id'] ?? null;
        $status = $json['status'] ?? null;

        if (!$externalId || !$status) {
            return $this->response->setJSON(['success' => true, 'message' => 'Ignored, missing external_id or status']);
        }

        $transaksiModel = new TransaksiModel();
        $transaksi = $transaksiModel->where('invoice_number', $externalId)->first();

        if (!$transaksi) {
            log_message('error', "Xendit Webhook: Transaction not found for invoice $externalId");
            return $this->response->setJSON(['success' => true, 'message' => 'Transaction not found']);
        }

        // We only care if it's currently pending or paid
        if (in_array($transaksi['status'], ['pending', 'paid'])) {
            if ($status === 'PAID' || $status === 'SETTLED') {
                // Update to confirmed
                $data = [
                    'status' => 'confirmed',
                    'paid_at' => date('Y-m-d H:i:s'),
                    'confirmed_at' => date('Y-m-d H:i:s')
                ];
                $transaksiModel->update($transaksi['id'], $data);

                // Re-fetch to get updated data
                $transaksi = $transaksiModel->find($transaksi['id']);

                // Process Commissions & Logics
                
                // 1. Process poin purchase (Service Fee)
                if ($transaksi['product_type'] === 'poin') {
                    $this->processPoinPurchase($transaksi);
                }

                // 2. Process referral poin
                $transaksiModel->processReferralPoin($transaksi['id']);

                // 3. Process WPA Commission
                $transaksiModel->processWpaCommission($transaksi['id']);

                // 4. Process EA License Generation
                $transaksiModel->processEaLicense($transaksi['id']);

                // 5. Process Advocacy Activation
                if (strpos($transaksi['product_name'], 'Advokasi') !== false) {
                    $pengaduanModel = new \App\Models\LayananPengaduanModel();
                    $pendingComplaints = $pengaduanModel->where('user_id', $transaksi['user_id'])
                                                      ->where('status', 'pending_payment')
                                                      ->findAll();
                    if (!empty($pendingComplaints)) {
                        foreach ($pendingComplaints as $pc) {
                            $pengaduanModel->update($pc['id'], ['status' => 'pending']);
                        }
                    }
                }
                
                log_message('info', "Xendit Webhook: Transaction $externalId successfully confirmed.");
                
            } elseif ($status === 'EXPIRED') {
                $transaksiModel->update($transaksi['id'], ['status' => 'expired']);
                log_message('info', "Xendit Webhook: Transaction $externalId expired.");
            }
        } else {
            log_message('info', "Xendit Webhook: Transaction $externalId ignored because current status is " . $transaksi['status']);
        }

        return $this->response->setJSON(['success' => true]);
    }

    /**
     * Process poin purchase when webhook confirms payment
     */
    protected function processPoinPurchase($transaksi)
    {
        $poinModel = new \App\Models\PoinModel();
        $notifModel = new \App\Models\NotificationModel();

        // Check if poin already added (prevent duplicate)
        $existing = $poinModel->where('pointable_type', 'purchase')
            ->where('pointable_id', $transaksi['id'])
            ->first();
        if ($existing) {
            return 0; // Already processed
        }

        // Parse poin from notes (format: "Poin: 1000 + Bonus: 100 | ...")
        $notes = $transaksi['notes'] ?? '';
        preg_match('/Poin: ([\d,]+)/', $notes, $poinMatch);
        preg_match('/Bonus: ([\d,]+)/', $notes, $bonusMatch);

        $poinAmount = isset($poinMatch[1]) ? (int) str_replace(',', '', $poinMatch[1]) : 0;
        $bonusAmount = isset($bonusMatch[1]) ? (int) str_replace(',', '', $bonusMatch[1]) : 0;

        if ($poinAmount <= 0) {
            log_message('error', 'Poin purchase via Webhook: Invalid poin amount for transaksi ' . $transaksi['id'] . ' - notes: ' . $notes);
            return 0;
        }

        // Add main poin
        $poinModel->insert([
            'user_id' => (int) $transaksi['user_id'],
            'type' => 'earn',
            'point' => $poinAmount,
            'description' => 'Pembelian Service Fee: ' . number_format($poinAmount) . ' Poin',
            'pointable_type' => 'purchase',
            'pointable_id' => (int) $transaksi['id'],
        ]);

        // Add bonus poin if any
        if ($bonusAmount > 0) {
            $poinModel->insert([
                'user_id' => (int) $transaksi['user_id'],
                'type' => 'bonus',
                'point' => $bonusAmount,
                'description' => 'Bonus pembelian Service Fee',
                'pointable_type' => 'purchase_bonus',
                'pointable_id' => (int) $transaksi['id'],
            ]);
        }

        $totalPoin = $poinAmount + $bonusAmount;

        // Send notification to user
        $notifModel->createNotification(
            (int) $transaksi['user_id'],
            'Poin Berhasil Ditambahkan',
            'Selamat! ' . number_format($totalPoin) . ' poin telah ditambahkan ke akun Anda.' . ($bonusAmount > 0 ? ' (termasuk bonus ' . number_format($bonusAmount) . ' poin)' : ''),
            'success',
            '/user/poin'
        );

        log_message('info', 'Poin purchase processed via Webhook: User ' . $transaksi['user_id'] . ' received ' . $totalPoin . ' poin');

        return $totalPoin;
    }
}
