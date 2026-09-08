<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;
use App\Models\KelasModel;
use App\Models\TransaksiModel;
use App\Models\WpaModel;

class Belajar extends BaseController
{
    public function index()
    {
        $userId = session()->get('userId');
        $transaksiModel = new TransaksiModel();

        // Get user's confirmed layanan (limit check to those with valid layanan_id)
        $transactions = $transaksiModel
            ->where('user_id', $userId)
            ->whereIn('status', ['confirmed', 'paid', 'settled', 'success'])
            ->where('layanan_id IS NOT NULL')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Enrich with layanan details
        $layananModel = new \App\Models\LayananModel();
        $myLayanan = [];

        foreach ($transactions as $trx) {
            $layanan = $layananModel->find($trx['layanan_id']);
            if ($layanan) {
                // Map to compatible format
                $item = $trx;
                $item['kelas_id'] = $trx['layanan_id'];
                $item['title'] = $layanan['name'];
                $item['thumbnail'] = $layanan['thumbnail'];
                $myLayanan[] = $item;
            }
        }

        return view('user/belajar/index', [
            'title' => 'Kelas Saya - Almai',
            'myKelas' => $myLayanan,
        ]);
    }

    /**
     * Special Handler for CWPA Page
     */
    public function cwpa()
    {
        $userId = session()->get('userId');
        $transaksiModel = new TransaksiModel();

        // 1. Verify access via product_type
        $purchase = $transaksiModel
            ->where('user_id', $userId)
            ->groupStart()
            ->where('product_type', 'cwpa')
            ->orLike('product_name', 'CWPA')
            ->groupEnd()
            ->whereIn('status', ['confirmed', 'paid', 'settled', 'success'])
            ->first();

        if (!$purchase) {
            return redirect()->to('/user/dashboard')->with('error', 'Anda belum terdaftar di program CWPA');
        }

        // 2. Construct Mock Layanan Object for View
        $layanan = [
            'id' => 0,
            'kelas_id' => 'cwpa', // for active menu
            'title' => 'Pendampingan CWPA',
            'name' => 'Pendampingan CWPA',
            'slug' => 'pendampingan-cwpa',
            'description' => 'Program pendampingan intensif untuk persiapan ujian sertifikasi Wakil Penasihat Berjangka (WPA). Silakan hubungi admin atau cek grup WhatsApp untuk jadwal bimbingan.',
            'thumbnail' => 'uploads/cwpa.jpg',
            'modules' => 0,
            'duration' => 'Fleksibel',
            'level' => 'Professional',
            'mode' => 'Hybrid',
            'rating' => 5.0,
            'students' => 100,
            'features' => ['Bimbingan Intensif', 'Try Out Ujian', 'Materi Lengkap', 'Grup Diskusi'],
            'is_cwpa' => true // Flag for View
        ];

        return view('user/belajar/kelas', [
            'title' => $layanan['name'] . ' - Almai',
            'kelas' => $layanan,
            'wpa' => ['name' => 'Tim Almai', 'photo' => 'default.jpg', 'specialty' => 'Education'],
        ]);
    }

    public function kelas($id)
    {
        $userId = session()->get('userId');
        $transaksiModel = new TransaksiModel();
        $layananModel = new \App\Models\LayananModel();

        // Check if user has purchased this layanan
        $purchase = $transaksiModel
            ->where('user_id', $userId)
            ->where('layanan_id', $id)
            ->whereIn('status', ['confirmed', 'paid', 'settled', 'success'])
            ->first();

        if (!$purchase) {
            return redirect()->to('/user/dashboard')->with('error', 'Anda belum membeli layanan ini');
        }

        // 1. Try Main Layanan Table
        $layanan = $layananModel->find($id);

        // 2. Try Layanan Event Table
        if (!$layanan) {
            $db = \Config\Database::connect();
            $event = $db->table('layanan_event')->where('id', $id)->get()->getRowArray();

            if ($event) {
                $layanan = [
                    'id' => $event['id'],
                    'name' => $event['title'],
                    'slug' => $event['slug'],
                    'description' => $event['description'],
                    'thumbnail' => $event['thumbnail'],
                    'wpa_id' => $event['wpa_id'],
                    'modules' => 0,
                    'duration' => '-',
                    'level' => 'All Level',
                    'mode' => ($event['type'] == 'webinar') ? 'Online' : 'Offline',
                    'rating' => 0,
                ];
            }
        }

        if (!$layanan) {
            return redirect()->to('/user/dashboard')->with('error', 'Layanan tidak ditemukan');
        }

        // Map fields
        $layanan['title'] = $layanan['name'];
        // Assume view uses $kelas ... mapping it to $kelas variable in view

        $wpaModel = new WpaModel();
        $wpa = null;
        if (!empty($layanan['wpa_id'])) {
            $wpa = $wpaModel->find($layanan['wpa_id']);
        }

        return view('user/belajar/kelas', [
            'title' => $layanan['name'] . ' - Almai',
            'kelas' => $layanan, // Passing as $kelas
            'wpa' => $wpa,
        ]);
    }
}
