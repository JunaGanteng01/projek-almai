<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\WpaSignalModel;
use App\Models\UserUnlockedSignalModel;
use App\Models\UserModel; // Assuming standard user model for points

class SignalMarket extends BaseController
{
    protected $signalModel;
    protected $unlockedModel;
    protected $userModel;

    public function __construct()
    {
        $this->signalModel = new WpaSignalModel();
        $this->unlockedModel = new UserUnlockedSignalModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $userId = session()->get('userId');
        
        // Ambil semua signal aktif
        $signals = $this->signalModel->getSignalsWithWpa()->where('wpa_signals.status', 'ACTIVE')->orderBy('wpa_signals.created_at', 'DESC')->findAll();
        
        // Ambil ID signal yang sudah diunlock oleh user ini
        $unlockedRecords = $this->unlockedModel->where('user_id', $userId)->findAll();
        $unlockedIds = array_column($unlockedRecords, 'signal_id');

        $data = [
            'title' => 'Signal Marketplace',
            'signals' => $signals,
            'unlockedIds' => $unlockedIds
        ];

        return view('user/signals/index', $data);
    }

    public function buy($signalId)
    {
        $userId = session()->get('userId');
        
        // Cari signal
        $signal = $this->signalModel->find($signalId);
        if (!$signal || $signal['status'] !== 'ACTIVE') {
            return redirect()->back()->with('error', 'Signal tidak tersedia.');
        }

        // Cek apakah sudah pernah beli
        $alreadyUnlocked = $this->unlockedModel->where(['user_id' => $userId, 'signal_id' => $signalId])->first();
        if ($alreadyUnlocked) {
            return redirect()->back()->with('info', 'Anda sudah membeli signal ini.');
        }

        $paymentMethod = $this->request->getPost('payment_method'); // 'points' or 'xendit'

        if ($paymentMethod === 'points') {
            $pointsNeeded = $signal['price_points'];
            $poinModel = new \App\Models\PoinModel();
            $userPoints = $poinModel->getUserBalance($userId);

            if ($userPoints < $pointsNeeded) {
                return redirect()->back()->with('error', 'Poin Anda tidak mencukupi untuk membeli signal ini.');
            }

            // Kurangi poin via tabel points (negative point)
            $poinModel->insert([
                'user_id' => $userId,
                'point' => -$pointsNeeded,
                'type' => 'REDEEM',
                'description' => 'Membeli Signal Market ' . $signal['pair'],
                'pointable_type' => 'App\Models\WpaSignalModel',
                'pointable_id' => $signalId,
                'purchaser_id' => $userId
            ]);

            // Catat unlock
            $this->unlockedModel->insert([
                'user_id' => $userId,
                'signal_id' => $signalId,
                'payment_method' => 'points',
                'amount_paid' => $pointsNeeded
            ]);

            return redirect()->back()->with('success', 'Signal berhasil di-unlock menggunakan Poin!');
        } else if ($paymentMethod === 'xendit') {
            // TODO: Integrasi Xendit API
            // Buat invoice, arahkan ke URL Xendit
            return redirect()->back()->with('info', 'Fitur pembayaran Xendit sedang dalam tahap pengembangan.');
        }

        return redirect()->back()->with('error', 'Metode pembayaran tidak valid.');
    }
}
