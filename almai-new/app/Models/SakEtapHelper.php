<?php

namespace App\Models;

use CodeIgniter\Model;

class SakEtapHelper extends Model
{
    protected $jurnalModel;
    protected $akunModel;

    public function __construct()
    {
        parent::__construct();
        $this->jurnalModel = new JurnalModel();
        $this->akunModel = new AkunModel();
    }

    /**
     * Get Neraca (Balance Sheet) data
     */
    public function getNeraca($startDate, $endDate)
    {
        $query = $this->jurnalModel
            ->select('akun.kategori, akun.nama_akun, akun.kode_akun, SUM(jurnal.debit) as total_debit, SUM(jurnal.kredit) as total_kredit')
            ->join('akun', 'akun.id = jurnal.akun_id')
            ->where('jurnal.tanggal >=', $startDate)
            ->where('jurnal.tanggal <=', $endDate)
            ->whereIn('akun.kategori', [
                'kas & bank',
                'akun piutang',
                'persediaan',
                'aktiva lancar lainya',
                'aktiva tetap',
                'depresiasi & amortisasi',
                'aktiva lainya',
                'akun hutang',
                'kewajiban lancar lainya',
                'kewajiban jangka panjang',
                'ekuitas'
            ])
            ->groupBy('akun.id')
            ->orderBy('akun.kode_akun', 'ASC')
            ->findAll();

        return $this->groupNeracaData($query);
    }

    /**
     * Get Laba Rugi (Income Statement) data
     */
    public function getLabaRugi($startDate, $endDate)
    {
        $query = $this->jurnalModel
            ->select('akun.kategori, akun.nama_akun, akun.kode_akun, SUM(jurnal.debit) as total_debit, SUM(jurnal.kredit) as total_kredit')
            ->join('akun', 'akun.id = jurnal.akun_id')
            ->where('jurnal.tanggal >=', $startDate)
            ->where('jurnal.tanggal <=', $endDate)
            ->whereIn('akun.kategori', [
                'pendapatan',
                'harga pokok penjualan',
                'beban',
                'pendapatan lainya',
                'beban lainya'
            ])
            ->groupBy('akun.id')
            ->orderBy('akun.kode_akun', 'ASC')
            ->findAll();

        return $this->groupLabaRugiData($query);
    }

    /**
     * Get Arus Kas (Cash Flow) data
     */
    public function getArusKas($startDate, $endDate)
    {
        $query = $this->jurnalModel
            ->select('akun.kategori, akun.nama_akun, akun.kode_akun, SUM(jurnal.debit) as total_debit, SUM(jurnal.kredit) as total_kredit')
            ->join('akun', 'akun.id = jurnal.akun_id')
            ->where('jurnal.tanggal >=', $startDate)
            ->where('jurnal.tanggal <=', $endDate)
            ->whereIn('akun.kategori', ['kas & bank'])
            ->groupBy('akun.id')
            ->orderBy('akun.kode_akun', 'ASC')
            ->findAll();

        return $query;
    }

    /**
     * Get Perubahan Ekuitas (Changes in Equity) data
     */
    public function getPerubahanEkuitas($startDate, $endDate)
    {
        $query = $this->jurnalModel
            ->select('akun.kategori, akun.nama_akun, akun.kode_akun, SUM(jurnal.debit) as total_debit, SUM(jurnal.kredit) as total_kredit')
            ->join('akun', 'akun.id = jurnal.akun_id')
            ->where('jurnal.tanggal >=', $startDate)
            ->where('jurnal.tanggal <=', $endDate)
            ->whereIn('akun.kategori', ['ekuitas'])
            ->groupBy('akun.id')
            ->orderBy('akun.kode_akun', 'ASC')
            ->findAll();

        return $query;
    }

    /**
     * Group Neraca data by category
     */
    private function groupNeracaData($data)
    {
        $grouped = [
            'aset_lancar' => [],
            'aset_tidak_lancar' => [],
            'liabilitas_jangka_pendek' => [],
            'liabilitas_jangka_panjang' => [],
            'ekuitas' => []
        ];

        $totals = [
            'aset_lancar' => 0,
            'aset_tidak_lancar' => 0,
            'liabilitas_jangka_pendek' => 0,
            'liabilitas_jangka_panjang' => 0,
            'ekuitas' => 0
        ];

        foreach ($data as $row) {
            $kategori = strtolower($row['kategori']);
            $saldo = $row['total_debit'] - $row['total_kredit'];

            if (in_array($kategori, ['kas & bank', 'akun piutang', 'persediaan', 'aktiva lancar lainya'])) {
                $grouped['aset_lancar'][] = array_merge($row, ['saldo' => $saldo]);
                $totals['aset_lancar'] += $saldo;
            } elseif (in_array($kategori, ['aktiva tetap', 'depresiasi & amortisasi', 'aktiva lainya'])) {
                $grouped['aset_tidak_lancar'][] = array_merge($row, ['saldo' => $saldo]);
                $totals['aset_tidak_lancar'] += $saldo;
            } elseif (in_array($kategori, ['akun hutang', 'kewajiban lancar lainya'])) {
                $grouped['liabilitas_jangka_pendek'][] = array_merge($row, ['saldo' => $saldo]);
                $totals['liabilitas_jangka_pendek'] += $saldo;
            } elseif ($kategori === 'kewajiban jangka panjang') {
                $grouped['liabilitas_jangka_panjang'][] = array_merge($row, ['saldo' => $saldo]);
                $totals['liabilitas_jangka_panjang'] += $saldo;
            } elseif ($kategori === 'ekuitas') {
                $grouped['ekuitas'][] = array_merge($row, ['saldo' => $saldo]);
                $totals['ekuitas'] += $saldo;
            }
        }

        return [
            'grouped' => $grouped,
            'totals' => $totals,
            'total_aset' => $totals['aset_lancar'] + $totals['aset_tidak_lancar'],
            'total_liabilitas_ekuitas' => $totals['liabilitas_jangka_pendek'] + $totals['liabilitas_jangka_panjang'] + $totals['ekuitas']
        ];
    }

    /**
     * Group Laba Rugi data by category
     */
    private function groupLabaRugiData($data)
    {
        $grouped = [
            'pendapatan' => [],
            'hpp' => [],
            'beban' => [],
            'pendapatan_lain' => [],
            'beban_lain' => []
        ];

        $totals = [
            'pendapatan' => 0,
            'hpp' => 0,
            'beban' => 0,
            'pendapatan_lain' => 0,
            'beban_lain' => 0
        ];

        foreach ($data as $row) {
            $kategori = strtolower($row['kategori']);
            $saldo = ($kategori === 'pendapatan' || $kategori === 'pendapatan lainya') 
                ? ($row['total_kredit'] - $row['total_debit']) 
                : ($row['total_debit'] - $row['total_kredit']);

            if ($kategori === 'pendapatan') {
                $grouped['pendapatan'][] = array_merge($row, ['saldo' => $saldo]);
                $totals['pendapatan'] += $saldo;
            } elseif ($kategori === 'harga pokok penjualan') {
                $grouped['hpp'][] = array_merge($row, ['saldo' => $saldo]);
                $totals['hpp'] += $saldo;
            } elseif ($kategori === 'beban') {
                $grouped['beban'][] = array_merge($row, ['saldo' => $saldo]);
                $totals['beban'] += $saldo;
            } elseif ($kategori === 'pendapatan lainya') {
                $grouped['pendapatan_lain'][] = array_merge($row, ['saldo' => $saldo]);
                $totals['pendapatan_lain'] += $saldo;
            } elseif ($kategori === 'beban lainya') {
                $grouped['beban_lain'][] = array_merge($row, ['saldo' => $saldo]);
                $totals['beban_lain'] += $saldo;
            }
        }

        $laba_kotor = $totals['pendapatan'] - $totals['hpp'];
        $laba_operasional = $laba_kotor - $totals['beban'];
        $laba_bersih = $laba_operasional + $totals['pendapatan_lain'] - $totals['beban_lain'];

        return [
            'grouped' => $grouped,
            'totals' => $totals,
            'laba_kotor' => $laba_kotor,
            'laba_operasional' => $laba_operasional,
            'laba_bersih' => $laba_bersih
        ];
    }

    /**
     * Format currency to Rupiah
     */
    public function formatRupiah($value)
    {
        return 'Rp ' . number_format($value, 0, ',', '.');
    }
}
