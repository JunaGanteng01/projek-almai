<?php

namespace App\Controllers\LaporanKegiatan;

use App\Controllers\BaseController;
use App\Models\WpaModel;
use App\Models\KelasModel;
use App\Models\UserModel;
use App\Models\ArtikelModel;
use App\Models\ToolsModel;
use App\Models\TransaksiModel;
use App\Models\LevelModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $wpaModel = new WpaModel();
        $userModel = new UserModel();
        $transaksiModel = new TransaksiModel();
        
        $seminarModel = new \App\Models\SeminarModel();
        $pelatihanModel = new \App\Models\PelatihanModel();
        $signalModel = new \App\Models\SignalModel();
        $konsultasiModel = new \App\Models\KonsultasiModel();
        $eaModel = new \App\Models\ExpertAdvisorModel();
        $lainnyaModel = new \App\Models\KegiatanLainnyaModel();

        // 1. Klien Aktif (Semua user)
        $klienAktif = $userModel->countAllResults();

        // 2. Klien Baru (Bulan ini)
        $klienBaru = $userModel->where('created_at >=', date('Y-m-01 00:00:00'))
                               ->where('created_at <=', date('Y-m-t 23:59:59'))
                               ->countAllResults();

        // 3. WPA Aktif
        $wpaAktif = $wpaModel->where('status', 'active')->countAllResults();

        $totalSeminar = $seminarModel->countAllResults();
        $totalPelatihan = $pelatihanModel->countAllResults();
        $totalSignal = $signalModel->countAllResults();
        $totalKonsultasi = $konsultasiModel->countAllResults();
        $totalEA = $eaModel->countAllResults();
        $totalLainnya = $lainnyaModel->countAllResults();

        // 4. Total Kegiatan (Semua event dari form Laporan Kegiatan)
        $totalKegiatan = $totalSeminar + $totalPelatihan + $totalSignal + $totalKonsultasi + $totalEA + $totalLainnya;

        $startOfMonth = date('Y-m-01 00:00:00');
        $endOfMonth = date('Y-m-t 23:59:59');

        // Seminar
        $jmlSeminarBulanIni = $seminarModel->where('tanggal >=', $startOfMonth)->where('tanggal <=', $endOfMonth)->countAllResults();
        $pesertaSeminarBulanIni = $seminarModel->selectSum('jml_peserta')->where('tanggal >=', $startOfMonth)->where('tanggal <=', $endOfMonth)->first()['jml_peserta'] ?? 0;

        // Pelatihan
        $jmlPelatihanBulanIni = $pelatihanModel->where('tanggal >=', $startOfMonth)->where('tanggal <=', $endOfMonth)->countAllResults();
        $pesertaPelatihanBulanIni = $pelatihanModel->selectSum('jml_peserta')->where('tanggal >=', $startOfMonth)->where('tanggal <=', $endOfMonth)->first()['jml_peserta'] ?? 0;

        // Signal
        $jmlSignalBulanIni = $signalModel->where('tanggal >=', $startOfMonth)->where('tanggal <=', $endOfMonth)->countAllResults();
        $pesertaSignalBulanIni = $signalModel->selectSum('jml_peserta')->where('tanggal >=', $startOfMonth)->where('tanggal <=', $endOfMonth)->first()['jml_peserta'] ?? 0;

        // Konsultasi (1 konsultasi = 1 klien)
        $jmlKonsultasiBulanIni = $konsultasiModel->where('tanggal >=', $startOfMonth)->where('tanggal <=', $endOfMonth)->countAllResults();
        $pesertaKonsultasiBulanIni = $jmlKonsultasiBulanIni;

        // Expert Advisor
        $jmlEABulanIni = $eaModel->where('tanggal >=', $startOfMonth)->where('tanggal <=', $endOfMonth)->countAllResults();
        $pesertaEABulanIni = $eaModel->selectSum('jml_klien')->where('tanggal >=', $startOfMonth)->where('tanggal <=', $endOfMonth)->first()['jml_klien'] ?? 0;

        // Table 1: Rekap Kegiatan Bulanan Terbaru (Real Data)
        $rekapKegiatan = [
            ['no' => 1, 'jenis_kegiatan' => 'Seminar/FGD', 'jml_kegiatan' => $jmlSeminarBulanIni, 'total_peserta' => $pesertaSeminarBulanIni . ' Peserta', 'status' => $jmlSeminarBulanIni > 0 ? 'Selesai' : '-'],
            ['no' => 2, 'jenis_kegiatan' => 'Pelatihan Simulasi', 'jml_kegiatan' => $jmlPelatihanBulanIni, 'total_peserta' => $pesertaPelatihanBulanIni . ' Peserta', 'status' => $jmlPelatihanBulanIni > 0 ? 'Selesai' : '-'],
            ['no' => 3, 'jenis_kegiatan' => 'Pemberian Signal', 'jml_kegiatan' => $jmlSignalBulanIni, 'total_peserta' => $pesertaSignalBulanIni . ' Klien', 'status' => $jmlSignalBulanIni > 0 ? 'Berjalan' : '-'],
            ['no' => 4, 'jenis_kegiatan' => 'Konsultasi', 'jml_kegiatan' => $jmlKonsultasiBulanIni, 'total_peserta' => $pesertaKonsultasiBulanIni . ' Klien', 'status' => $jmlKonsultasiBulanIni > 0 ? 'Selesai' : '-'],
            ['no' => 5, 'jenis_kegiatan' => 'Expert Advisor', 'jml_kegiatan' => $jmlEABulanIni, 'total_peserta' => $pesertaEABulanIni . ' Klien', 'status' => $jmlEABulanIni > 0 ? 'Berjalan' : '-'],
        ];

        // Table 2: KPI DIRESKI (Mock data)
        $kpiDireski = [
            ['no' => 1, 'kpi' => 'Pertumbuhan Klien Baru', 'target' => '50 Klien', 'realisasi' => '35 Klien', 'pencapaian' => 70, 'status' => 'On Track'],
            ['no' => 2, 'kpi' => 'Tingkat Keaktifan WPA', 'target' => '90%', 'realisasi' => '85%', 'pencapaian' => 94, 'status' => 'On Track'],
            ['no' => 3, 'kpi' => 'Volume Kegiatan Seminar', 'target' => '10 Event', 'realisasi' => '5 Event', 'pencapaian' => 50, 'status' => 'Warning'],
            ['no' => 4, 'kpi' => 'Akurasi Signal WPA', 'target' => '80%', 'realisasi' => '75%', 'pencapaian' => 93, 'status' => 'On Track'],
            ['no' => 5, 'kpi' => 'Kepuasan Klien Konsultasi', 'target' => '4.5/5', 'realisasi' => '4.8/5', 'pencapaian' => 106, 'status' => 'Achieved'],
        ];

        $data = [
            'title' => 'Laporan Kegiatan Dashboard - Almai',
            'pageTitle' => 'Laporan Kegiatan Dashboard',
            'activeMenu' => 'dashboard',
            'isPartnershipAdmin' => true,
            
            // New 10 KPIs
            'namaPerusahaan' => 'PT. Alma Indonesia Raya',
            'nomorIzinBappebti' => '0005/UPTP/SI-WPA/2/2024',
            'klienAktif' => $klienAktif,
            'klienBaru' => $klienBaru,
            'wpaAktif' => $wpaAktif,
            'totalKegiatan' => $totalKegiatan,
            'totalSeminar' => $totalSeminar,
            'totalSignal' => $totalSignal,
            'totalKonsultasi' => $totalKonsultasi,
            'totalEA' => $totalEA,

            // Tables
            'rekapKegiatan' => $rekapKegiatan,
            'kpiDireski' => $kpiDireski
        ];

        return view('laporan-kegiatan/dashboard', $data);
    }

    private function getRevenueByCategory($transaksiModel)
    {
        $confirmedTransactions = $transaksiModel->where('status', 'confirmed')->findAll();

        // Get all layanan data for mapping
        $layananController = new \App\Controllers\Layanan();
        $method = new \ReflectionMethod(\App\Controllers\Layanan::class, 'getLayananData');
        $method->setAccessible(true);
        $allLayanan = $method->invoke($layananController);

        $map = [];
        foreach ($allLayanan as $l) {
            $map[$l['name']] = $l['subcategory'];
        }

        $revenueByCategory = [];

        foreach ($confirmedTransactions as $trx) {
            if (($trx['payment_method'] ?? '') === 'poin') continue;

            $subcategory = 'Lain-lain';

            if ($trx['product_type'] === 'kelas') {
                $subcategory = 'Kelas';
            } elseif ($trx['product_type'] === 'tools') {
                $subcategory = 'Tools';
            } elseif ($trx['product_type'] === 'layanan') {
                $name = $trx['product_name'];
                $nameParts = explode(' - ', $name);
                $baseName = $nameParts[0];

                if (isset($map[$name])) {
                    $subcategory = $map[$name];
                } elseif (isset($map[$baseName])) {
                    $subcategory = $map[$baseName];
                } else {
                    foreach ($map as $mName => $mSub) {
                        if (stripos($name, $mName) !== false) {
                            $subcategory = $mSub;
                            break;
                        }
                    }
                }
            } elseif ($trx['product_type'] === 'poin') {
                $subcategory = 'Top Up Poin';
            }

            if (!isset($revenueByCategory[$subcategory])) {
                $revenueByCategory[$subcategory] = 0;
            }
            $revenueByCategory[$subcategory] += $trx['total'];
        }

        arsort($revenueByCategory);
        $labels = array_keys($revenueByCategory);
        $data = array_values($revenueByCategory);

        $colors = ['#33e818', '#3b82f6', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899', '#10b981', '#06b6d4', '#6366f1', '#f97316'];

        return [
            'labels' => $labels,
            'data' => $data,
            'colors' => array_slice($colors, 0, count($labels))
        ];
    }

    public function revenueData()
    {
        $days = (int) ($this->request->getGet('days') ?? 365);
        $transaksiModel = new TransaksiModel();
        
        $months = [];
        $revenues = [];
        $periods = min(12, max(1, ceil($days / 30)));

        for ($i = $periods - 1; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i months"));
            $monthName = date('M', strtotime("-$i months"));
            $months[] = $monthName;

            $startDate = $date . '-01 00:00:00';
            $endDate = date('Y-m-t 23:59:59', strtotime($date . '-01'));

            $revenue = $transaksiModel->where('status', 'confirmed')
                ->where('created_at >=', $startDate)
                ->where('created_at <=', $endDate)
                ->where('payment_method !=', 'poin')
                ->selectSum('total')
                ->first()['total'] ?? 0;

            $revenues[] = (int) $revenue;
        }

        return $this->response->setJSON(['labels' => $months, 'data' => $revenues]);
    }

    public function userGrowthData()
    {
        $days = (int) ($this->request->getGet('days') ?? 365);
        $db = \Config\Database::connect();
        
        $months = [];
        $users = [];
        $periods = min(12, max(1, ceil($days / 30)));

        for ($i = $periods - 1; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i months"));
            $monthName = date('M', strtotime("-$i months"));
            $months[] = $monthName;

            $startDate = $date . '-01 00:00:00';
            $endDate = date('Y-m-t 23:59:59', strtotime($date . '-01'));

            $result = $db->query("SELECT COUNT(*) as count FROM users WHERE created_at >= ? AND created_at <= ?", [$startDate, $endDate])->getRow();
            $users[] = (int) ($result->count ?? 0);
        }

        return $this->response->setJSON(['labels' => $months, 'data' => $users]);
    }

    private function getMonthlyRevenue($transaksiModel)
    {
        $months = [];
        $revenues = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i months"));
            $monthName = date('M', strtotime("-$i months"));
            $months[] = $monthName;

            $startDate = $date . '-01 00:00:00';
            $endDate = date('Y-m-t 23:59:59', strtotime($date . '-01'));

            $revenue = $transaksiModel->where('status', 'confirmed')
                ->where('created_at >=', $startDate)
                ->where('created_at <=', $endDate)
                ->where('payment_method !=', 'poin')
                ->selectSum('total')
                ->first()['total'] ?? 0;

            $revenues[] = (int) $revenue;
        }

        return [
            'labels' => $months,
            'data' => $revenues
        ];
    }

    private function getMonthlyUserGrowth($userModel)
    {
        $months = [];
        $users = [];
        $db = \Config\Database::connect();

        for ($i = 11; $i >= 0; $i--) {
            $date = date('Y-m', strtotime("-$i months"));
            $monthName = date('M', strtotime("-$i months"));
            $months[] = $monthName;

            $startDate = $date . '-01 00:00:00';
            $endDate = date('Y-m-t 23:59:59', strtotime($date . '-01'));

            $result = $db->query(
                "SELECT COUNT(*) as count FROM users WHERE created_at >= ? AND created_at <= ?",
                [$startDate, $endDate]
            )->getRow();

            $users[] = (int) ($result->count ?? 0);
        }

        return [
            'labels' => $months,
            'data' => $users
        ];
    }
}
