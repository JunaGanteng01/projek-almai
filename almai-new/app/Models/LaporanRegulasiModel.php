<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanRegulasiModel extends Model
{
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    public function getRekapBulanan($bulan, $tahun)
    {
        $startDate = "$tahun-$bulan-01";
        $endDate = date('Y-m-t', strtotime($startDate));

        $data = [
            'seminar' => $this->getStats('seminar_fgd', $startDate, $endDate),
            'pelatihan' => $this->getStats('pelatihan_simulasi', $startDate, $endDate),
            'signal' => $this->getStats('signals', $startDate, $endDate),
            'konsultasi' => $this->getStats('konsultasi', $startDate, $endDate, true), // true if nama_klien counts as 1
            'ea' => $this->getStatsEA('expert_advisor', $startDate, $endDate),
            'lainnya' => $this->getStats('kegiatan_lainnya', $startDate, $endDate),
        ];

        return $data;
    }

    public function getRekapTahunan($tahun)
    {
        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $bulan = str_pad($i, 2, '0', STR_PAD_LEFT);
            $data[$bulan] = $this->getRekapBulanan($bulan, $tahun);
        }
        return $data;
    }

    private function getStats($table, $start, $end, $isKonsultasi = false)
    {
        $results = $this->getDetailActivities($table, $start, $end);
        
        $jmlKegiatan = count($results);
        $jmlKlien = 0;
        $jmlNasihat = 0;

        foreach ($results as $row) {
            if ($isKonsultasi) {
                $jmlKlien += 1;
            } else {
                $jmlKlien += isset($row['jml_peserta']) ? (int)$row['jml_peserta'] : (isset($row['jml_klien']) ? (int)$row['jml_klien'] : 0);
            }
            $jmlNasihat += isset($row['jml_nasihat']) ? (int)$row['jml_nasihat'] : 0;
        }

        return [
            'kegiatan' => $jmlKegiatan,
            'klien' => $jmlKlien,
            'nasihat' => $jmlNasihat
        ];
    }

    private function getStatsEA($table, $start, $end)
    {
        $results = $this->getDetailActivities($table, $start, $end);
        
        $jmlKegiatan = count($results);
        $jmlKlien = 0;
        $jmlNasihat = 0; // EA typically doesn't have jml_nasihat

        foreach ($results as $row) {
            $jmlKlien += isset($row['jml_klien']) ? (int)$row['jml_klien'] : 0;
        }

        return [
            'kegiatan' => $jmlKegiatan,
            'klien' => $jmlKlien,
            'nasihat' => $jmlNasihat
        ];
    }

    public function getDataKlien($bulan, $tahun)
    {
        $startDate = "$tahun-$bulan-01 00:00:00";
        $endDate = date('Y-m-t 23:59:59', strtotime($startDate));

        $builder = $this->db->table('users');
        $totalKlienAktif = $builder->where('status !=', 'banned')->countAllResults();
        
        $klienPerusahaan = $this->db->table('data_perusahaan')
                                    ->where('status', 'active')
                                    ->countAllResults();
        
        $klienBaru = $this->db->table('users')
                              ->where('created_at >=', $startDate)
                              ->where('created_at <=', $endDate)
                              ->countAllResults();

        $expertAdvisor = $this->getDetailActivities('expert_advisor', $startDate, $endDate);
                                  
        $pedomanPerilaku = $this->db->table('pedoman_perilaku')
                                    ->where('created_at >=', $startDate)
                                    ->where('created_at <=', $endDate)
                                    ->get()->getResultArray();

        $totalWpaAktif = $this->db->table('wpa')
                                  ->where('status', 'active')
                                  ->countAllResults();

        $transaksiUsers = $this->db->table('transaksi')
            ->select('transaksi.user_id, transaksi.invoice_number, transaksi.created_at as tx_created, transaksi.product_name, users.name, users.email')
            ->join('users', 'users.id = transaksi.user_id', 'left')
            ->whereIn('transaksi.status', ['confirmed', 'paid', 'success', 'settled', 'PAID', 'SUCCESS', 'SETTLED'])
            ->where('transaksi.created_at >=', $startDate)
            ->where('transaksi.created_at <=', $endDate)
            ->get()->getResultArray();

        $uniqueUsers = [];
        foreach ($transaksiUsers as $tu) {
            if (!empty($tu['user_id'])) {
                // Keep the first transaction's invoice for that user
                if (!isset($uniqueUsers[$tu['user_id']])) {
                    $uniqueUsers[$tu['user_id']] = $tu;
                }
            }
        }
        $klienPeroranganBeli = count($uniqueUsers);

        $klienPerusahaanBaru = $this->db->table('data_perusahaan')
                                        ->where('created_at >=', $startDate)
                                        ->where('created_at <=', $endDate)
                                        ->countAllResults();

        // Populate pedoman_perilaku with individual clients if empty
        if (empty($pedomanPerilaku) && !empty($uniqueUsers)) {
            foreach ($uniqueUsers as $tu) {
                // Generate Nomor Kontrak as generated by LegalDocumentPdfService
                $txCreated = !empty($tu['tx_created']) ? $tu['tx_created'] : date('Y-m-d H:i:s');
                $date = new \DateTime($txCreated);
                $romanMonths = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
                $romanMonth = $romanMonths[max(0, ((int)$date->format('n')) - 1)];
                $year = $date->format('Y');
                
                $slug = 'LAYANAN';
                if (!empty($tu['product_name'])) {
                    $slug = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '_', $tu['product_name']));
                }
                
                $invoiceNumber = !empty($tu['invoice_number']) ? $tu['invoice_number'] : '0000';
                $uniqueNumber = substr($invoiceNumber, -4);
                $nomorKontrak = "{$uniqueNumber}/ALMAI/{$slug}/{$romanMonth}/{$year}";

                $pedomanPerilaku[] = [
                    'nama_klien' => !empty($tu['name']) ? $tu['name'] : $tu['email'],
                    'nomor_akun' => '-',
                    'latar_belakang' => '1',
                    'keadaan_keuangan' => '1',
                    'profil_risiko' => '1',
                    'jasa_penjelasan' => '1',
                    'jasa_disetujui' => '1',
                    'nomor_dokumen' => $nomorKontrak,
                    'ket_penjelasan' => '1',
                    'ket_disetujui' => '1',
                    'risiko_penjelasan' => '1',
                    'risiko_disetujui' => '1',
                ];
            }
        }

        return [
            'total_aktif' => $totalKlienAktif, // Seluruh user web
            'perorangan' => $klienPeroranganBeli, // Klien yang beli layanan
            'perusahaan' => $klienPerusahaan,
            'perusahaan_baru' => $klienPerusahaanBaru,
            'baru' => $klienBaru,
            'wpa_aktif' => $totalWpaAktif,
            'expert_advisor' => $expertAdvisor,
            'pedoman_perilaku' => $pedomanPerilaku
        ];
    }

    public function getKlienBaruBulanan($tahun)
    {
        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $bulan = str_pad($i, 2, '0', STR_PAD_LEFT);
            $startDate = "$tahun-$bulan-01 00:00:00";
            $endDate = date('Y-m-t 23:59:59', strtotime($startDate));

            $klienBaru = $this->db->table('users')
                                  ->where('created_at >=', $startDate)
                                  ->where('created_at <=', $endDate)
                                  ->countAllResults();
            $data[$bulan] = $klienBaru;
        }
        return $data;
    }

    public function getListWpa()
    {
        return $this->db->table('wpa')
            ->select('name as nama_wpa, nomor_izin_wpa as nomor_izin, keterangan')
            ->where('status', 'active')
            ->get()->getResultArray();
    }

    public function getDetailActivities($modul, $start, $end)
    {
        // Convert the old table names to the new 'jenis_modul' if necessary
        // 'seminar_fgd' => 'Seminar FGD', 'pelatihan_simulasi' => 'Pelatihan Simulasi', 'signals' => 'Signal'
        $modulMap = [
            'seminar_fgd' => 'Seminar FGD',
            'pelatihan_simulasi' => 'Pelatihan Simulasi',
            'signals' => 'Signal',
            'konsultasi' => 'Konsultasi',
            'expert_advisor' => 'Expert Advisor',
            'kegiatan_lainnya' => 'Kegiatan Lainnya'
        ];
        
        $jenisModul = isset($modulMap[$modul]) ? $modulMap[$modul] : $modul;

        $queries = [];

        // We filter by event_date OR is_recurring = 1 for event tables.
        // For simplicity, we just fetch active ones matching the modul.
        
        // 1. layanan table
        $queries[] = "SELECT l.id, l.name as judul, l.category as produk, l.subcategory as topik, l.location as lokasi, l.event_date as tanggal, l.status, l.jenis_modul, 'layanan' as table_name, w.name as nama_wpa, 0 as is_recurring, NULL as recurring_day, NULL as recurring_time, l.description as keterangan FROM layanan l LEFT JOIN wpa w ON l.wpa_id = w.id WHERE l.jenis_modul = '{$jenisModul}' AND l.status IN ('active', 'aktif')";
        
        // 2. layanan_artikel table
        $queries[] = "SELECT l.id, l.title as judul, 'Advokasi' as produk, 'Artikel' as topik, NULL as lokasi, NULL as tanggal, l.status, l.jenis_modul, 'layanan_artikel' as table_name, w.name as nama_wpa, 0 as is_recurring, NULL as recurring_day, NULL as recurring_time, l.excerpt as keterangan FROM layanan_artikel l LEFT JOIN wpa w ON l.wpa_id = w.id WHERE l.jenis_modul = '{$jenisModul}' AND l.status IN ('active', 'aktif')";
        
        // 3. layanan_event table
        $queries[] = "SELECT l.id, l.title as judul, 'Advokasi' as produk, l.type as topik, l.location as lokasi, l.event_date as tanggal, l.status, l.jenis_modul, 'layanan_event' as table_name, w.name as nama_wpa, l.is_recurring, l.recurring_day, l.recurring_time, l.description as keterangan FROM layanan_event l LEFT JOIN wpa w ON l.wpa_id = w.id WHERE l.jenis_modul = '{$jenisModul}' AND l.status IN ('active', 'aktif')";
        
        // 4. layanan_tools table
        $queries[] = "SELECT l.id, l.name as judul, l.type as produk, l.type as topik, NULL as lokasi, NULL as tanggal, l.status, l.jenis_modul, 'layanan_tools' as table_name, w.name as nama_wpa, 0 as is_recurring, NULL as recurring_day, NULL as recurring_time, l.description as keterangan FROM layanan_tools l LEFT JOIN wpa w ON l.wpa_id = w.id WHERE l.jenis_modul = '{$jenisModul}' AND l.status IN ('active', 'aktif')";
        
        // 5. layanan_subscription table
        $queries[] = "SELECT l.id, l.name as judul, l.type as produk, l.type as topik, NULL as lokasi, NULL as tanggal, l.status, l.jenis_modul, 'layanan_subscription' as table_name, w.name as nama_wpa, 0 as is_recurring, NULL as recurring_day, NULL as recurring_time, l.description as keterangan FROM layanan_subscription l LEFT JOIN wpa w ON l.wpa_id = w.id WHERE l.jenis_modul = '{$jenisModul}' AND l.status IN ('active', 'aktif')";

        // 6. Old tables
        if ($modul == 'seminar_fgd') {
            $queries[] = "SELECT s.id, s.judul as judul, s.produk, s.topik, s.lokasi, s.tanggal, 'active' as status, 'Seminar FGD' as jenis_modul, 'seminar_fgd' as table_name, w.name as nama_wpa, 0 as is_recurring, NULL as recurring_day, NULL as recurring_time, s.keterangan FROM seminar_fgd s LEFT JOIN wpa w ON s.wpa_id = w.id";
        } elseif ($modul == 'pelatihan_simulasi') {
            $queries[] = "SELECT s.id, s.judul as judul, s.produk, s.topik, s.lokasi, s.tanggal, 'active' as status, 'Pelatihan Simulasi' as jenis_modul, 'pelatihan_simulasi' as table_name, w.name as nama_wpa, 0 as is_recurring, NULL as recurring_day, NULL as recurring_time, s.keterangan FROM pelatihan_simulasi s LEFT JOIN wpa w ON s.wpa_id = w.id";
        } elseif ($modul == 'signals') {
            $queries[] = "SELECT s.id, s.keterangan as judul, s.produk, s.keterangan as topik, s.media as lokasi, s.tanggal, 'active' as status, 'Signal' as jenis_modul, 'signals' as table_name, w.name as nama_wpa, 0 as is_recurring, NULL as recurring_day, NULL as recurring_time, s.keterangan FROM signals s LEFT JOIN wpa w ON s.wpa_id = w.id";
        } elseif ($modul == 'konsultasi') {
            $queries[] = "SELECT s.id, s.keterangan as judul, s.produk, s.keterangan as topik, s.media as lokasi, s.tanggal, 'active' as status, 'Konsultasi' as jenis_modul, 'konsultasi' as table_name, w.name as nama_wpa, 0 as is_recurring, NULL as recurring_day, NULL as recurring_time, s.keterangan FROM konsultasi s LEFT JOIN wpa w ON s.wpa_id = w.id";
        } elseif ($modul == 'expert_advisor') {
            $queries[] = "SELECT s.id, s.nama_layanan as judul, s.produk, s.penjelasan_layanan as topik, NULL as lokasi, s.tanggal, 'active' as status, 'Expert Advisor' as jenis_modul, 'expert_advisor' as table_name, w.name as nama_wpa, 0 as is_recurring, NULL as recurring_day, NULL as recurring_time, s.keterangan FROM expert_advisor s LEFT JOIN wpa w ON s.wpa_id = w.id";
        } elseif ($modul == 'kegiatan_lainnya') {
            $queries[] = "SELECT s.id, s.nama_kegiatan as judul, s.produk, s.keterangan as topik, s.media as lokasi, s.tanggal, 'active' as status, 'Kegiatan Lainnya' as jenis_modul, 'kegiatan_lainnya' as table_name, w.name as nama_wpa, 0 as is_recurring, NULL as recurring_day, NULL as recurring_time, s.keterangan FROM kegiatan_lainnya s LEFT JOIN wpa w ON s.wpa_id = w.id";
        }

        $unionQuery = implode(" UNION ALL ", $queries);
        
        // Final Query
        $finalQuery = "SELECT * FROM ({$unionQuery}) as combined_table WHERE (tanggal IS NULL OR CAST(tanggal AS CHAR) LIKE '0000-00-00%' OR is_recurring = 1 OR (tanggal >= '{$start}' AND tanggal <= '{$end} 23:59:59')) ORDER BY tanggal DESC";
        $results = $this->db->query($finalQuery)->getResultArray();
        
        // Get Jml Peserta (buyers / absensi)
        $layananModel = new \App\Models\LayananModel();
        $absensiModel = new \App\Models\AbsensiPesertaModel();

        foreach ($results as &$resItem) {
            // Tentukan tipe kegiatan untuk absensi
            $absensiTypes = [];
            switch ($resItem['jenis_modul']) {
                case 'Seminar FGD': $absensiTypes = ['seminar_fgd', 'seminar']; break;
                case 'Pelatihan Simulasi': $absensiTypes = ['pelatihan_simulasi', 'pelatihan']; break;
                case 'Signal': $absensiTypes = ['signals', 'signal']; break;
                case 'Konsultasi': $absensiTypes = ['konsultasi']; break;
                case 'Expert Advisor': $absensiTypes = ['expert_advisor']; break;
                case 'Kegiatan Lainnya': $absensiTypes = ['kegiatan_lainnya']; break;
            }
            
            $jmlAbsensi = 0;
            if (!empty($absensiTypes)) {
                $jmlAbsensi = $absensiModel->whereIn('kegiatan_type', $absensiTypes)
                                           ->where('kegiatan_id', $resItem['id'])
                                           ->countAllResults();
            }

            // Jika ada yang absen, ambil jumlah absen. Jika 0, jatuh kembali ke data pembelian
            if ($jmlAbsensi > 0) {
                $buyers = $jmlAbsensi;
            } else {
                $buyers = $layananModel->getBuyerCount($resItem['id'], $resItem['table_name']);
            }

            $resItem['jml_peserta'] = $buyers;
            $resItem['jml_klien'] = $buyers; // Alias
            $resItem['jml_nasihat'] = 0; // Alias
        }
        
        return $results;
    }
}
