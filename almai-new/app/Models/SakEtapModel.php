<?php

namespace App\Models;

use CodeIgniter\Model;

class SakEtapModel extends Model
{
    protected $table = 'jurnal';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $protectFields = true;

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    /**
     * Kategori Akun untuk SAK-ETAP
     */
    public const KATEGORI_ASET_LANCAR = [
        'kas & bank',
        'akun piutang',
        'persediaan',
        'aktiva lancar lainya'
    ];

    public const KATEGORI_ASET_TIDAK_LANCAR = [
        'aktiva tetap',
        'aktiva lainya'
    ];

    public const KATEGORI_LIABILITAS_JANGKA_PENDEK = [
        'akun hutang',
        'kewajiban lancar lainya'
    ];

    public const KATEGORI_LIABILITAS_JANGKA_PANJANG = [
        'kewajiban jangka panjang'
    ];

    public const KATEGORI_EKUITAS = [
        'ekuitas'
    ];

    public const KATEGORI_PENDAPATAN = [
        'pendapatan',
        'pendapatan lainya'
    ];

    public const KATEGORI_BEBAN = [
        'harga pokok penjualan',
        'beban',
        'beban lainya'
    ];

    /**
     * Get Neraca (Balance Sheet) untuk periode tertentu
     */
    public function getNeraca($startDate = null, $endDate = null, $tahunBuku = null)
    {
        $db = \Config\Database::connect();

        // Jika tahunBuku diberikan, gunakan periode 1 Jan - 31 Des tahun tersebut
        if ($tahunBuku) {
            $startDate = "{$tahunBuku}-01-01";
            $endDate = "{$tahunBuku}-12-31";
        }

        // Default ke tahun berjalan jika tidak ada parameter
        if (!$startDate || !$endDate) {
            $startDate = date('Y-01-01');
            $endDate = date('Y-12-31');
        }

        $neracaStartDate = '1970-01-01'; // Untuk menarik saldo kumulatif

        // ASET LANCAR
        $asetLancar = $this->getAkunByKategori(self::KATEGORI_ASET_LANCAR, $neracaStartDate, $endDate);
        $totalAsetLancar = array_sum(array_column($asetLancar, 'saldo'));

        // ASET TIDAK LANCAR
        $asetTidakLancar = $this->getAkunByKategori(self::KATEGORI_ASET_TIDAK_LANCAR, $neracaStartDate, $endDate);
        $totalAsetTidakLancar = array_sum(array_column($asetTidakLancar, 'saldo'));

        // TOTAL ASET
        $totalAset = $totalAsetLancar + $totalAsetTidakLancar;

        // LIABILITAS JANGKA PENDEK
        $liabilitasJangkaPendek = $this->getAkunByKategori(self::KATEGORI_LIABILITAS_JANGKA_PENDEK, $neracaStartDate, $endDate);
        $totalLiabilitasJangkaPendek = array_sum(array_column($liabilitasJangkaPendek, 'saldo'));

        // LIABILITAS JANGKA PANJANG
        $liabilitasJangkaPanjang = $this->getAkunByKategori(self::KATEGORI_LIABILITAS_JANGKA_PANJANG, $neracaStartDate, $endDate);
        $totalLiabilitasJangkaPanjang = array_sum(array_column($liabilitasJangkaPanjang, 'saldo'));

        // TOTAL LIABILITAS
        $totalLiabilitas = $totalLiabilitasJangkaPendek + $totalLiabilitasJangkaPanjang;

        // EKUITAS (Kumulatif Akun Ekuitas)
        $ekuitas = $this->getAkunByKategori(self::KATEGORI_EKUITAS, $neracaStartDate, $endDate);
        $totalEkuitas = array_sum(array_column($ekuitas, 'saldo'));

        // Laba/Rugi Tahun Berjalan
        $labaRugiTahunBerjalan = $this->getLabaRugiTahunBerjalan($startDate, $endDate);

        // Laba/Rugi Tahun-tahun Lalu (Saldo Laba Ditahan)
        $labaRugiTahunLalu = 0;
        if ($startDate > '1970-01-01') {
            $prevEndDate = date('Y-m-d', strtotime($startDate . ' - 1 day'));
            $labaRugiTahunLalu = $this->getLabaRugiTahunBerjalan($neracaStartDate, $prevEndDate);
        }

        // Total Ekuitas + Laba/Rugi
        $totalEkuitasWithProfit = $totalEkuitas + $labaRugiTahunLalu + $labaRugiTahunBerjalan;

        // Total Liabilitas + Ekuitas
        $totalLiabilitasEkuitas = $totalLiabilitas + $totalEkuitasWithProfit;

        return [
            'periode' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'tahun_buku' => $tahunBuku ?? date('Y', strtotime($endDate))
            ],
            'aset' => [
                'aset_lancar' => [
                    'detail' => $asetLancar,
                    'total' => $totalAsetLancar
                ],
                'aset_tidak_lancar' => [
                    'detail' => $asetTidakLancar,
                    'total' => $totalAsetTidakLancar
                ],
                'total_aset' => $totalAset
            ],
            'liabilitas' => [
                'liabilitas_jangka_pendek' => [
                    'detail' => $liabilitasJangkaPendek,
                    'total' => $totalLiabilitasJangkaPendek
                ],
                'liabilitas_jangka_panjang' => [
                    'detail' => $liabilitasJangkaPanjang,
                    'total' => $totalLiabilitasJangkaPanjang
                ],
                'total_liabilitas' => $totalLiabilitas
            ],
            'ekuitas' => [
                'detail' => $ekuitas,
                'total_ekuitas' => $totalEkuitas,
                'laba_rugi_tahun_lalu' => $labaRugiTahunLalu,
                'laba_rugi_tahun_berjalan' => $labaRugiTahunBerjalan,
                'total_ekuitas_with_profit' => $totalEkuitasWithProfit
            ],
            'total_liabilitas_ekuitas' => $totalLiabilitasEkuitas,
            'balance_check' => [
                'total_aset' => $totalAset,
                'total_liabilitas_ekuitas' => $totalLiabilitasEkuitas,
                'selisih' => abs($totalAset - $totalLiabilitasEkuitas),
                'is_balance' => abs($totalAset - $totalLiabilitasEkuitas) < 0.01
            ]
        ];
    }

    /**
     * Get Laporan Laba Rugi (Income Statement)
     */
    public function getLapaRugi($startDate = null, $endDate = null, $tahunBuku = null)
    {
        // Jika tahunBuku diberikan, gunakan periode 1 Jan - 31 Des tahun tersebut
        if ($tahunBuku) {
            $startDate = "{$tahunBuku}-01-01";
            $endDate = "{$tahunBuku}-12-31";
        }

        // Default ke tahun berjalan jika tidak ada parameter
        if (!$startDate || !$endDate) {
            $startDate = date('Y-01-01');
            $endDate = date('Y-12-31');
        }

        // PENDAPATAN
        $pendapatan = $this->getAkunByKategori(['pendapatan'], $startDate, $endDate);
        $totalPendapatan = array_sum(array_column($pendapatan, 'saldo'));

        // PENDAPATAN LAINNYA
        $pendapatanLainnya = $this->getAkunByKategori(['pendapatan lainya'], $startDate, $endDate);
        $totalPendapatanLainnya = array_sum(array_column($pendapatanLainnya, 'saldo'));

        // TOTAL PENDAPATAN
        $totalPendapatanBruto = $totalPendapatan + $totalPendapatanLainnya;

        // HARGA POKOK PENJUALAN
        $hpp = $this->getAkunByKategori(['harga pokok penjualan'], $startDate, $endDate);
        $totalHpp = array_sum(array_column($hpp, 'saldo'));

        // LABA KOTOR
        $labaKotor = $totalPendapatan - $totalHpp;

        // BEBAN OPERASIONAL
        $beban = $this->getAkunByKategori(['beban'], $startDate, $endDate);
        $totalBeban = array_sum(array_column($beban, 'saldo'));

        // BEBAN LAINNYA & BEBAN PAJAK
        $bebanLainnya = $this->getAkunByKategori(['beban lainya'], $startDate, $endDate);
        
        $bebanPajakTotal = 0;
        $bebanLainnyaNonPajak = 0;
        foreach ($bebanLainnya as $b) {
            if ($b['nama_akun'] === 'Beban Pajak') {
                $bebanPajakTotal += $b['saldo'];
            } else {
                $bebanLainnyaNonPajak += $b['saldo'];
            }
        }

        // TOTAL BEBAN OPERASIONAL (Beban Usaha saja)
        $totalBebanOperasional = $totalBeban;

        // LABA/RUGI USAHA
        $labaRugiOperasional = $labaKotor - $totalBebanOperasional;

        // PENDAPATAN (BEBAN) DILUAR USAHA
        $pendapatanBebanDiluarUsaha = $totalPendapatanLainnya - $bebanLainnyaNonPajak;

        // LABA/RUGI TAHUN BERJALAN
        $labaRugiTahunBerjalan = $labaRugiOperasional + $pendapatanBebanDiluarUsaha - $bebanPajakTotal;

        return [
            'periode' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'tahun_buku' => $tahunBuku ?? date('Y', strtotime($endDate))
            ],
            'pendapatan' => [
                'pendapatan_usaha' => [
                    'detail' => $pendapatan,
                    'total' => $totalPendapatan
                ],
                'pendapatan_lainnya' => [
                    'detail' => $pendapatanLainnya,
                    'total' => $totalPendapatanLainnya
                ],
                'total_pendapatan' => $totalPendapatanBruto
            ],
            'hpp' => [
                'detail' => $hpp,
                'total' => $totalHpp
            ],
            'laba_kotor' => $labaKotor,
            'beban' => [
                'beban_operasional' => [
                    'detail' => $beban,
                    'total' => $totalBeban
                ],
                'beban_lainnya' => [
                    'detail' => $bebanLainnya,
                    'total' => $bebanLainnyaNonPajak
                ],
                'beban_pajak' => $bebanPajakTotal,
                'total_beban' => $totalBebanOperasional
            ],
            'laba_rugi_operasional' => $labaRugiOperasional,
            'pendapatan_beban_diluar_usaha' => $pendapatanBebanDiluarUsaha,
            'laba_rugi_tahun_berjalan' => $labaRugiTahunBerjalan
        ];
    }

    /**
     * Get Laporan Arus Kas (Cash Flow Statement)
     */
    public function getArusKas($startDate = null, $endDate = null, $tahunBuku = null)
    {
        // Jika tahunBuku diberikan, gunakan periode 1 Jan - 31 Des tahun tersebut
        if ($tahunBuku) {
            $startDate = "{$tahunBuku}-01-01";
            $endDate = "{$tahunBuku}-12-31";
        }

        // Default ke tahun berjalan jika tidak ada parameter
        if (!$startDate || !$endDate) {
            $startDate = date('Y-01-01');
            $endDate = date('Y-12-31');
        }

        $db = \Config\Database::connect();

        // Laba/Rugi Tahun Berjalan
        $labaRugi = $this->getLabaRugiTahunBerjalan($startDate, $endDate);

        // Penyusutan & Amortisasi (non-cash items)
        // DRAFT uses Akumulasi Penyusutan as the Beban Penyusutan in Operasi
        $penyusutanAmortisasi = [];
        $totalPenyusutanAmortisasi = 0;
        $mutasiAsetTidakLancarRaw = $this->getAkunByKategori(self::KATEGORI_ASET_TIDAK_LANCAR, $startDate, $endDate);
        $mutasiAsetTidakLancar = [];
        foreach ($mutasiAsetTidakLancarRaw as $item) {
            if (stripos($item['nama_akun'], 'Akumulasi Penyusutan') !== false) {
                // In DRAFT, Akumulasi Penyusutan is added back in Operasi as 'Beban Penyusutan'
                // Since it's a contra asset, its saldo in Aset Tidak Lancar is negative if it increases.
                // We want to add it back as a positive non-cash expense.
                $item['nama_akun'] = 'Beban Penyusutan';
                $item['saldo'] = abs($item['saldo']); 
                $penyusutanAmortisasi[] = $item;
                $totalPenyusutanAmortisasi += $item['saldo'];
            } else {
                $mutasiAsetTidakLancar[] = $item;
            }
        }

        // Laba Operasi Sebelum Perubahan Modal Kerja
        $labaOperasiSebelumPerubahanMK = $labaRugi + $totalPenyusutanAmortisasi;

        // Perubahan Modal Kerja
        $mutasiAsetLancarNonKas = $this->getAkunByKategori(['akun piutang', 'persediaan', 'aktiva lancar lainya'], $startDate, $endDate);
        $kenaikanAsetLancarNonKas = array_sum(array_column($mutasiAsetLancarNonKas, 'saldo'));

        $mutasiLiabilitasPendek = $this->getAkunByKategori(self::KATEGORI_LIABILITAS_JANGKA_PENDEK, $startDate, $endDate);
        $kenaikanLiabilitasPendek = array_sum(array_column($mutasiLiabilitasPendek, 'saldo'));

        $perubahanModalKerja = $kenaikanLiabilitasPendek - $kenaikanAsetLancarNonKas;

        // Arus Kas dari Aktivitas Operasi
        $arusKasOperasi = $labaOperasiSebelumPerubahanMK + $perubahanModalKerja;

        // Arus Kas dari Aktivitas Investasi
        $kenaikanAsetTidakLancar = array_sum(array_column($mutasiAsetTidakLancar, 'saldo'));
        // DRAFT Investasi total is exactly the sum of Aset Tidak Lancar mutasi (excluding Akumulasi Penyusutan which was moved)
        // Wait, if an asset increases, cash flow is negative.
        $arusKasInvestasi = -$kenaikanAsetTidakLancar;

        // Arus Kas dari Aktivitas Pendanaan
        $mutasiLiabilitasPanjang = $this->getAkunByKategori(self::KATEGORI_LIABILITAS_JANGKA_PANJANG, $startDate, $endDate);
        $kenaikanLiabilitasPanjang = array_sum(array_column($mutasiLiabilitasPanjang, 'saldo'));

        $mutasiEkuitas = $this->getAkunByKategori(self::KATEGORI_EKUITAS, $startDate, $endDate);
        // Laba/Rugi Tahun-tahun Lalu (Saldo Laba Ditahan awal) - wait, just get the laba rugi of previous year to subtract from mutasi Laba Ditahan
        $prevEndDate = date('Y-m-d', strtotime($startDate . ' - 1 day'));
        $labaRugiTahunLalu = $this->getLabaRugiTahunBerjalan('1970-01-01', $prevEndDate);

        // Exclude Laba Berjalan from Ekuitas since it's already in Operasi
        $kenaikanEkuitas = 0;
        foreach ($mutasiEkuitas as &$item) {
            if ($item['nama_akun'] === 'Laba (Rugi) Tahun Berjalan') continue;
            // The DRAFT names it "Koreksi Laba (Rugi) Ditahan"
            if ($item['nama_akun'] === 'Laba (Rugi) Ditahan') {
                $item['nama_akun'] = 'Koreksi Laba (Rugi) Ditahan';
                // Subtract the closing entry of previous year's profit
                $item['saldo'] -= $labaRugiTahunLalu;
            }
            $kenaikanEkuitas += $item['saldo'];
        }

        $arusKasPendanaan = $kenaikanLiabilitasPanjang + $kenaikanEkuitas;

        // Perubahan Kas Bersih
        // In DRAFT, the sum of Operasi + Investasi + Pendanaan = 124.351.612, 
        // but the printed Kenaikan Arus Kas is 124.351.611.
        // We force it to match the actual cash change so Kas Akhir matches exactly.
        $mutasiKas = $this->getAkunByKategori(['kas & bank'], $startDate, $endDate);
        $perubahanKasBersih = array_sum(array_column($mutasiKas, 'saldo'));
        
        // Kas Awal Periode
        $kasAwalPeriode = $this->getKasAwalPeriode($startDate);

        $kasAkhirPeriode = $kasAwalPeriode + $perubahanKasBersih;

        return [
            'periode' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'tahun_buku' => $tahunBuku ?? date('Y', strtotime($endDate))
            ],
            'aktivitas_operasi' => [
                'laba_rugi_tahun_berjalan' => $labaRugi,
                'penyusutan_amortisasi' => [
                    'detail' => $penyusutanAmortisasi,
                    'total' => $totalPenyusutanAmortisasi
                ],
                'laba_operasi_sebelum_perubahan_mk' => $labaOperasiSebelumPerubahanMK,
                'perubahan_modal_kerja' => [
                    'aset_lancar_non_kas' => $mutasiAsetLancarNonKas,
                    'liabilitas_pendek' => $mutasiLiabilitasPendek,
                    'total' => $perubahanModalKerja
                ],
                'arus_kas_operasi' => $arusKasOperasi
            ],
            'aktivitas_investasi' => [
                'detail' => $mutasiAsetTidakLancar,
                'arus_kas_investasi' => $arusKasInvestasi
            ],
            'aktivitas_pendanaan' => [
                'detail_liabilitas_panjang' => $mutasiLiabilitasPanjang,
                'detail_ekuitas' => $mutasiEkuitas,
                'arus_kas_pendanaan' => $arusKasPendanaan
            ],
            'perubahan_kas_bersih' => $perubahanKasBersih,
            'kas_awal_periode' => $kasAwalPeriode,
            'kas_akhir_periode' => $kasAkhirPeriode
        ];
    }

    /**
     * Get Laporan Perubahan Ekuitas (Statement of Changes in Equity)
     */
    public function getPerubahanEkuitas($startDate = null, $endDate = null, $tahunBuku = null)
    {
        // Jika tahunBuku diberikan, gunakan periode 1 Jan - 31 Des tahun tersebut
        if ($tahunBuku) {
            $startDate = "{$tahunBuku}-01-01";
            $endDate = "{$tahunBuku}-12-31";
        }

        // Default ke tahun berjalan jika tidak ada parameter
        if (!$startDate || !$endDate) {
            $startDate = date('Y-01-01');
            $endDate = date('Y-12-31');
        }

        // Ekuitas Awal Periode
        $ekuitasAwalPeriode = $this->getEkuitasAwalPeriode($startDate);

        // Laba/Rugi Tahun Berjalan
        $labaRugiTahunBerjalan = $this->getLabaRugiTahunBerjalan($startDate, $endDate);

        // Dividen/Pengambilan Pribadi
        $db = \Config\Database::connect();
        $dividenRow = $db->table('jurnal')
            ->selectSum('debit')
            ->selectSum('kredit')
            ->join('akun', 'akun.id = jurnal.akun_id', 'left')
            ->where('jurnal.tanggal >=', $startDate)
            ->where('jurnal.tanggal <=', $endDate)
            ->where('akun.kategori', 'ekuitas')
            ->groupStart()
                ->like('LOWER(akun.nama_akun)', 'prive')
                ->orLike('LOWER(akun.nama_akun)', 'dividen')
            ->groupEnd()
            ->get()
            ->getRowArray();

        $dividenDebit = (float) ($dividenRow['debit'] ?? 0);
        $dividenKredit = (float) ($dividenRow['kredit'] ?? 0);
        $dividen = $dividenDebit - $dividenKredit;
        if ($dividen < 0) {
            $dividen = 0;
        }

        // Tambahan Modal (Jika ada, di luar Laba Berjalan dan Dividen)
        $mutasiEkuitas = $this->getAkunByKategori(self::KATEGORI_EKUITAS, $startDate, $endDate);
        $totalMutasiEkuitas = array_sum(array_column($mutasiEkuitas, 'saldo'));
        $tambahanModal = $totalMutasiEkuitas + $dividen;

        // Ekuitas Akhir Periode
        $ekuitasAkhirPeriode = $ekuitasAwalPeriode + $labaRugiTahunBerjalan + $tambahanModal - $dividen;

        return [
            'periode' => [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'tahun_buku' => $tahunBuku ?? date('Y', strtotime($endDate))
            ],
            'ekuitas_awal_periode' => $ekuitasAwalPeriode,
            'laba_rugi_tahun_berjalan' => $labaRugiTahunBerjalan,
            'tambahan_modal' => $tambahanModal,
            'dividen' => $dividen,
            'ekuitas_akhir_periode' => $ekuitasAkhirPeriode
        ];
    }

    /**
     * Helper: Get akun by kategori dengan saldo
     */
    private function getAkunByKategori(array $kategoriList, string $startDate, string $endDate)
    {
        $db = \Config\Database::connect();

        $result = $db->table('jurnal')
            ->select('akun.id, akun.kode_akun, akun.nama_akun, akun.kategori, SUM(jurnal.debit) as total_debit, SUM(jurnal.kredit) as total_kredit')
            ->join('akun', 'akun.id = jurnal.akun_id', 'left')
            ->where('jurnal.tanggal >=', $startDate)
            ->where('jurnal.tanggal <=', $endDate)
            ->whereIn('akun.kategori', $kategoriList)
            ->groupBy('akun.id')
            ->orderBy('akun.kode_akun', 'ASC')
            ->get()
            ->getResultArray();

        // Calculate saldo (debit - kredit for assets, kredit - debit for liabilities/equity)
        foreach ($result as &$row) {
            $debit = (float) ($row['total_debit'] ?? 0);
            $kredit = (float) ($row['total_kredit'] ?? 0);

            // Untuk aset: debit positif, kredit negatif
            // Untuk liabilitas/ekuitas: kredit positif, debit negatif
            if (in_array($row['kategori'], self::KATEGORI_ASET_LANCAR) || 
                in_array($row['kategori'], self::KATEGORI_ASET_TIDAK_LANCAR) ||
                in_array($row['kategori'], self::KATEGORI_BEBAN)) {
                $row['saldo'] = $debit - $kredit;
            } else {
                $row['saldo'] = $kredit - $debit;
            }
        }

        return $result;
    }

    /**
     * Helper: Get Laba/Rugi Tahun Berjalan
     */
    private function getLabaRugiTahunBerjalan(string $startDate, string $endDate)
    {
        $db = \Config\Database::connect();

        // Total Pendapatan
        $pendapatan = $db->table('jurnal')
            ->selectSum('kredit')
            ->join('akun', 'akun.id = jurnal.akun_id', 'left')
            ->where('jurnal.tanggal >=', $startDate)
            ->where('jurnal.tanggal <=', $endDate)
            ->whereIn('akun.kategori', ['pendapatan', 'pendapatan lainya'])
            ->get()
            ->getRowArray();

        $totalPendapatan = (float) ($pendapatan['kredit'] ?? 0);

        // Total Beban
        $beban = $db->table('jurnal')
            ->selectSum('debit')
            ->join('akun', 'akun.id = jurnal.akun_id', 'left')
            ->where('jurnal.tanggal >=', $startDate)
            ->where('jurnal.tanggal <=', $endDate)
            ->whereIn('akun.kategori', ['harga pokok penjualan', 'beban', 'beban lainya'])
            ->get()
            ->getRowArray();

        $totalBeban = (float) ($beban['debit'] ?? 0);

        return $totalPendapatan - $totalBeban;
    }

    /**
     * Helper: Get Kas Awal Periode
     */
    private function getKasAwalPeriode(string $startDate)
    {
        $db = \Config\Database::connect();

        // Ambil saldo kas sebelum periode dimulai
        $kasSebelumPeriode = $db->table('jurnal')
            ->selectSum('debit')
            ->selectSum('kredit')
            ->join('akun', 'akun.id = jurnal.akun_id', 'left')
            ->where('jurnal.tanggal <', $startDate)
            ->where('akun.kategori', 'kas & bank')
            ->get()
            ->getRowArray();

        $debit = (float) ($kasSebelumPeriode['debit'] ?? 0);
        $kredit = (float) ($kasSebelumPeriode['kredit'] ?? 0);

        return $debit - $kredit;
    }

    /**
     * Helper: Get Ekuitas Awal Periode
     */
    private function getEkuitasAwalPeriode(string $startDate)
    {
        $db = \Config\Database::connect();

        // Ambil saldo ekuitas sebelum periode dimulai
        $ekuitasSebelumPeriode = $db->table('jurnal')
            ->selectSum('debit')
            ->selectSum('kredit')
            ->join('akun', 'akun.id = jurnal.akun_id', 'left')
            ->where('jurnal.tanggal <', $startDate)
            ->where('akun.kategori', 'ekuitas')
            ->get()
            ->getRowArray();

        $debit = (float) ($ekuitasSebelumPeriode['debit'] ?? 0);
        $kredit = (float) ($ekuitasSebelumPeriode['kredit'] ?? 0);

        return $kredit - $debit;
    }
}
