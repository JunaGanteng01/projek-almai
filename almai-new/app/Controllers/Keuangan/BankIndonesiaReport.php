<?php

namespace App\Controllers\Keuangan;

use App\Controllers\BaseController;
use App\Models\SakEtapModel;
use App\Services\Keuangan\CalkService;
use App\Libraries\BankIndonesiaPdfGenerator;

class BankIndonesiaReport extends BaseController
{
    protected SakEtapModel $sakEtapModel;
    protected CalkService $calkService;

    public function __construct()
    {
        $this->sakEtapModel = new SakEtapModel();
        $this->calkService = new CalkService();
    }

    public function generatePdf()
    {
        $tahunBerjalan = (int) ($this->request->getGet('tahun'));
        if (!$tahunBerjalan) {
            $endDate = $this->request->getGet('end_date');
            if ($endDate) {
                $tahunBerjalan = (int) date('Y', strtotime($endDate));
            } else {
                $tahunBerjalan = (int) date('Y');
            }
        }
        $tahunSebelumnya = $tahunBerjalan - 1;

        // Data Tahun Berjalan
        $startBerjalan = "{$tahunBerjalan}-01-01";
        $endBerjalan = "{$tahunBerjalan}-12-31";
        $neracaBerjalan = $this->sakEtapModel->getNeraca($startBerjalan, $endBerjalan, (string)$tahunBerjalan);
        $labaRugiBerjalan = $this->sakEtapModel->getLapaRugi($startBerjalan, $endBerjalan, (string)$tahunBerjalan);
        $perubahanEkuitasBerjalan = $this->sakEtapModel->getPerubahanEkuitas($startBerjalan, $endBerjalan, (string)$tahunBerjalan);
        $arusKasBerjalan = $this->sakEtapModel->getArusKas($startBerjalan, $endBerjalan, (string)$tahunBerjalan);

        // Data Tahun Sebelumnya
        $startSebelumnya = "{$tahunSebelumnya}-01-01";
        $endSebelumnya = "{$tahunSebelumnya}-12-31";
        $neracaSebelumnya = $this->sakEtapModel->getNeraca($startSebelumnya, $endSebelumnya, (string)$tahunSebelumnya);
        $labaRugiSebelumnya = $this->sakEtapModel->getLapaRugi($startSebelumnya, $endSebelumnya, (string)$tahunSebelumnya);
        $perubahanEkuitasSebelumnya = $this->sakEtapModel->getPerubahanEkuitas($startSebelumnya, $endSebelumnya, (string)$tahunSebelumnya);
        $arusKasSebelumnya = $this->sakEtapModel->getArusKas($startSebelumnya, $endSebelumnya, (string)$tahunSebelumnya);

        // Data CALK
        $calkData = $this->calkService->getCalkData($tahunBerjalan);

        // Analisis Rasio Keuangan
        $rasio = $this->calculateRatios($neracaBerjalan, $labaRugiBerjalan);

        // Z-Score Model
        $zScore = $this->calculateZScore($neracaBerjalan, $labaRugiBerjalan);

        // Data Lampiran (Aset Tetap & Amortisasi)
        $lampiran = $this->getLampiranData($tahunBerjalan);

        $reportData = [
            'tahunBerjalan' => $tahunBerjalan,
            'tahunSebelumnya' => $tahunSebelumnya,
            'neraca' => [
                'berjalan' => $neracaBerjalan,
                'sebelumnya' => $neracaSebelumnya
            ],
            'labaRugi' => [
                'berjalan' => $labaRugiBerjalan,
                'sebelumnya' => $labaRugiSebelumnya
            ],
            'perubahanEkuitas' => [
                'berjalan' => $perubahanEkuitasBerjalan,
                'sebelumnya' => $perubahanEkuitasSebelumnya
            ],
            'arusKas' => [
                'berjalan' => $arusKasBerjalan,
                'sebelumnya' => $arusKasSebelumnya
            ],
            'calk' => $calkData,
            'rasio' => $rasio,
            'zScore' => $zScore,
            'lampiran' => $lampiran
        ];

        $pdfGenerator = new BankIndonesiaPdfGenerator();
        return $pdfGenerator->generate($reportData);
    }

    private function calculateRatios($neraca, $labaRugi)
    {
        $asetLancar = $neraca['aset']['lancar']['total'] ?? 0;
        $liabilitasPendek = $neraca['liabilitas']['jangka_pendek']['total'] ?? 0;
        $kasSetaraKas = 0; // Fetch from neraca details
        if (isset($neraca['aset']['lancar']['items'])) {
            foreach ($neraca['aset']['lancar']['items'] as $item) {
                if (stripos(strtolower($item['kategori']), 'kas') !== false) {
                    $kasSetaraKas += $item['saldo'];
                }
            }
        }
        
        $totalAset = $neraca['aset']['total_aset'] ?? 0;
        $totalLiabilitas = $neraca['liabilitas']['total_liabilitas'] ?? 0;
        $totalEkuitas = $neraca['ekuitas']['total_ekuitas_with_profit'] ?? 0;
        
        $pendapatan = $labaRugi['pendapatan']['total'] ?? 0;
        $labaBerjalan = $labaRugi['laba_rugi_bersih'] ?? 0;

        $currentRatio = $liabilitasPendek != 0 ? ($asetLancar / $liabilitasPendek) : 0;
        $cashRatio = $liabilitasPendek != 0 ? ($kasSetaraKas / $liabilitasPendek) : 0;
        
        $solvabilitasAset = $totalAset != 0 ? ($totalLiabilitas / $totalAset) : 0;
        $solvabilitasEkuitas = $totalEkuitas != 0 ? ($totalLiabilitas / $totalEkuitas) : 0;

        $perputaranAset = $totalAset != 0 ? ($pendapatan / $totalAset) : 0;
        
        $netProfitMargin = $pendapatan != 0 ? ($labaBerjalan / $pendapatan) : 0;
        $roa = $totalAset != 0 ? ($labaBerjalan / $totalAset) : 0;
        $roe = $totalEkuitas != 0 ? ($labaBerjalan / $totalEkuitas) : 0;

        return [
            'asetLancar' => $asetLancar,
            'liabilitasPendek' => $liabilitasPendek,
            'kasSetaraKas' => $kasSetaraKas,
            'totalAset' => $totalAset,
            'totalLiabilitas' => $totalLiabilitas,
            'totalEkuitas' => $totalEkuitas,
            'pendapatan' => $pendapatan,
            'labaBerjalan' => $labaBerjalan,
            'currentRatio' => $currentRatio,
            'cashRatio' => $cashRatio,
            'solvabilitasAset' => $solvabilitasAset,
            'solvabilitasEkuitas' => $solvabilitasEkuitas,
            'perputaranAset' => $perputaranAset,
            'netProfitMargin' => $netProfitMargin,
            'roa' => $roa,
            'roe' => $roe
        ];
    }

    private function calculateZScore($neraca, $labaRugi)
    {
        $asetLancar = $neraca['aset']['lancar']['total'] ?? 0;
        $liabilitasPendek = $neraca['liabilitas']['jangka_pendek']['total'] ?? 0;
        $modalKerjaBersih = $asetLancar - $liabilitasPendek;
        
        $totalAset = $neraca['aset']['total_aset'] ?? 0;
        // Saldo Laba = total laba ditahan + laba berjalan
        $saldoLaba = 0;
        if (isset($neraca['ekuitas']['items'])) {
            foreach ($neraca['ekuitas']['items'] as $item) {
                if (stripos(strtolower($item['nama_akun']), 'laba') !== false) {
                    $saldoLaba += $item['saldo'];
                }
            }
        }
        $saldoLaba += ($labaRugi['laba_rugi_bersih'] ?? 0);

        $ebit = $labaRugi['laba_rugi_operasional'] ?? 0; // Or laba kotor - beban usaha
        $totalKewajiban = $neraca['liabilitas']['total_liabilitas'] ?? 0;
        $modalEkuitas = $neraca['ekuitas']['total_ekuitas_with_profit'] ?? 0;

        $x1 = $totalAset != 0 ? ($modalKerjaBersih / $totalAset) : 0;
        $x2 = $totalAset != 0 ? ($saldoLaba / $totalAset) : 0;
        $x3 = $totalAset != 0 ? ($ebit / $totalAset) : 0;
        $x4 = $totalKewajiban != 0 ? ($modalEkuitas / $totalKewajiban) : 0;

        $z = (6.56 * $x1) + (3.26 * $x2) + (6.72 * $x3) + (1.05 * $x4);

        $prediksi = 'Tidak Pailit';
        if ($z < 1.23) {
            $prediksi = 'Pailit';
        } elseif ($z >= 1.23 && $z <= 2.90) {
            $prediksi = 'Grey Area';
        }

        return [
            'modalKerjaBersih' => $modalKerjaBersih,
            'totalAset' => $totalAset,
            'saldoLaba' => $saldoLaba,
            'ebit' => $ebit,
            'totalKewajiban' => $totalKewajiban,
            'modalEkuitas' => $modalEkuitas,
            'x1' => $x1,
            'x2' => $x2,
            'x3' => $x3,
            'x4' => $x4,
            'z' => $z,
            'prediksi' => $prediksi
        ];
    }

    private function calculateDepreciation($aset, $tahun)
    {
        $tgl = strtotime($aset['tanggal_pembelian']);
        $tahunBeli = (int)date('Y', $tgl);
        $bulanBeli = (int)date('n', $tgl);
        $harga = (float)$aset['harga_beli'];
        $tarif = (float)$aset['persen_penyusutan'] / 100;
        $deprePerYear = $harga * $tarif;

        if ($tahunBeli > $tahun) {
            return [
                'harga' => 0,
                'beban' => 0,
                'akumulasi' => 0,
                'nilai_buku' => 0
            ];
        }

        $akumulasi = 0;
        $beban = 0;
        for ($y = $tahunBeli; $y <= $tahun; $y++) {
            if ($y == $tahunBeli) {
                $months = 12 - $bulanBeli + 1;
                $bebanTahunIni = $deprePerYear * ($months / 12);
            } else {
                $bebanTahunIni = $deprePerYear;
            }

            if ($akumulasi + $bebanTahunIni > $harga) {
                $bebanTahunIni = $harga - $akumulasi;
            }

            if ($y == $tahun) {
                $beban = $bebanTahunIni;
                $akumulasi += $beban;
                return [
                    'harga' => $harga,
                    'beban' => $beban,
                    'akumulasi' => $akumulasi,
                    'nilai_buku' => $harga - $akumulasi
                ];
            }

            $akumulasi += $bebanTahunIni;
        }

        return [
            'harga' => $harga,
            'beban' => $beban,
            'akumulasi' => $akumulasi,
            'nilai_buku' => $harga - $akumulasi
        ];
    }

    private function getLampiranData($tahunBerjalan)
    {
        $db = \Config\Database::connect();
        
        $asetList = [];
        if ($db->tableExists('aset')) {
            $asetList = $db->table('aset')
                ->select('aset.*, akun.nama_akun as kategori_aset')
                ->join('akun', 'akun.kode_akun = aset.akun_aset_kode', 'left')
                ->orderBy('aset.tanggal_pembelian', 'ASC')
                ->get()
                ->getResultArray();
        }

        $tahunSebelumnya = $tahunBerjalan - 1;

        $asetTetap = [
            'Bangunan' => [],
            'Inventaris dan Mebel Kantor' => [],
            'Inventaris Peralatan Kantor' => []
        ];
        $asetTakBerwujud = [];

        foreach ($asetList as $aset) {
            $deprePrev = $this->calculateDepreciation($aset, $tahunSebelumnya);
            $depreCurr = $this->calculateDepreciation($aset, $tahunBerjalan);
            $mutasi = $depreCurr['harga'] - $deprePrev['harga'];

            $item = [
                'nama' => $aset['nama_aset'],
                'tanggal' => date('d/m/Y', strtotime($aset['tanggal_pembelian'])),
                'tarif' => (float)$aset['persen_penyusutan'],
                'prev' => $deprePrev,
                'mutasi' => $mutasi,
                'curr' => $depreCurr
            ];

            if ($aset['akun_aset_kode'] === '1-1175' || stripos(strtolower($aset['kategori_aset'] ?? ''), 'takberwujud') !== false || stripos(strtolower($aset['kategori_aset'] ?? ''), 'tak berwujud') !== false) {
                $asetTakBerwujud[] = $item;
            } else {
                if ($aset['akun_aset_kode'] === '1-1171' || stripos(strtolower($aset['kategori_aset'] ?? ''), 'bangunan') !== false) {
                    $asetTetap['Bangunan'][] = $item;
                } elseif ($aset['akun_aset_kode'] === '1-1174' || stripos(strtolower($aset['kategori_aset'] ?? ''), 'mebel') !== false) {
                    $asetTetap['Inventaris dan Mebel Kantor'][] = $item;
                } else {
                    $asetTetap['Inventaris Peralatan Kantor'][] = $item;
                }
            }
        }

        return [
            'asetTetap' => $asetTetap,
            'asetTakBerwujud' => $asetTakBerwujud
        ];
    }
}
