<?php

namespace App\Controllers\LaporanKegiatan;

use App\Controllers\BaseController;
use App\Models\WpaModel;
use App\Models\TransaksiModel;

class Kpi extends BaseController
{
    public function index()
    {
        $wpaModel = new WpaModel();
        $db = \Config\Database::connect();
        
        $wpaList = $wpaModel->select('wpa.*, users.name as user_name, users.email as user_email')
            ->join('users', 'users.id = wpa.user_id')
            ->orderBy('wpa.id', 'DESC')
            ->findAll();

        foreach ($wpaList as &$wpa) {
            // Count total transactions managed by this WPA
            // We join with the four possible product tables to find the wpa_id
            $wpa['total_transactions'] = $db->table('transaksi')
                ->join('layanan', 'layanan.id = transaksi.layanan_id', 'left')
                ->join('layanan_event', 'layanan_event.id = transaksi.layanan_id', 'left')
                ->join('layanan_tools', 'layanan_tools.id = transaksi.layanan_id', 'left')
                ->join('layanan_subscription', 'layanan_subscription.id = transaksi.layanan_id', 'left')
                ->where("COALESCE(layanan.wpa_id, layanan_event.wpa_id, layanan_tools.wpa_id, layanan_subscription.wpa_id) = {$wpa['id']}", null, false)
                ->countAllResults();
            
            // Total revenue for this WPA
            $wpa['total_revenue'] = $db->table('transaksi')
                ->selectSum('transaksi.total')
                ->join('layanan', 'layanan.id = transaksi.layanan_id', 'left')
                ->join('layanan_event', 'layanan_event.id = transaksi.layanan_id', 'left')
                ->join('layanan_tools', 'layanan_tools.id = transaksi.layanan_id', 'left')
                ->join('layanan_subscription', 'layanan_subscription.id = transaksi.layanan_id', 'left')
                ->where("COALESCE(layanan.wpa_id, layanan_event.wpa_id, layanan_tools.wpa_id, layanan_subscription.wpa_id) = {$wpa['id']}", null, false)
                ->where('transaksi.status', 'confirmed')
                ->get()->getRowArray()['total'] ?? 0;
                
            // Total commission earned
            // Calculated from the points earned by the WPA user
            $wpa['total_commission'] = $db->table('points')
                ->where('user_id', $wpa['user_id'])
                ->where('type', 'earn')
                ->where('pointable_type', 'transaksi')
                ->selectSum('point')
                ->get()->getRowArray()['point'] ?? 0;
        }

        return view('laporan-kegiatan/kpi/index', [
            'title' => 'KPI WPA Performance - Partnership Admin',
            'wpaList' => $wpaList,
            'activeMenu' => 'kpi'
        ]);
    }
}
