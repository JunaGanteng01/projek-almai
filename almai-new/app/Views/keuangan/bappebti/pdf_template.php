<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan Bappebti <?= $tahunBerjalan ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10pt; line-height: 1.4; color: #000; }
        .page-break { page-break-after: always; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .mt-5 { margin-top: 50px; }
        .mt-2 { margin-top: 20px; }
        .mb-2 { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 6px; border: 1px solid #000; }
        .no-border th, .no-border td { border: none; padding: 4px; }
        .border-bottom { border-bottom: 1px solid #000; }
        .border-top { border-top: 1px solid #000; }
        
        .cover { height: 100%; display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; padding-top: 300px; }
        .cover-title { font-size: 16pt; font-weight: bold; margin-bottom: 20px; }
        
        /* Table Layout for Financials */
        .fin-table th { border: none; border-top: 2px solid #000; border-bottom: 2px solid #000; padding: 10px 5px; }
        .fin-table td { border: none; padding: 5px; }
        .fin-table .total-row td { border-top: 1px solid #000; border-bottom: 2px solid #000; font-weight: bold; }
        .fin-table .subtotal-row td { border-top: 1px solid #000; font-weight: bold; }
        
        .full-border th { border: 1px solid #000 !important; }
        .full-border td { border: 1px solid #000 !important; }
    </style>
</head>
<body>

<?php
// Helper to find previous year amount
if (!function_exists('getPrevAmount')) {
    function getPrevAmount($items, $kode_akun) {
        foreach ($items as $item) {
            if ($item['kode_akun'] == $kode_akun) {
                return $item['saldo'];
            }
        }
        return 0;
    }
}
function formatRp($val) {
    if ($val < 0) return "(" . number_format(abs($val), 0, ',', '.') . ")";
    if ($val == 0) return "-";
    return number_format($val, 0, ',', '.');
}
function getTanggalIndo() {
    $bulanIndo = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    return date('j') . ' ' . $bulanIndo[(int)date('m')] . ' ' . date('Y');
}

function getCatatan($nama_akun) {
    $map = [
        'Kas' => '2b, 5',
        'Piutang Pemegang Saham' => '2c, 6',
        'Piutang Lain' => '2c, 7',
        'Biaya Dibayar' => '2e, 8',
        'Aset Lancar Lainnya' => '2f, 9',
        'Aset Tetap' => '2f, 10',
        'Akumulasi Penyusutan' => '2f, 10',
        'Beban Penyusutan' => '2j, 19',
        'Aset TakBerwujud' => '2f, 11',
        'Akumulasi Amortisasi' => '2f, 11',
        'Beban Amortisasi' => '2j, 20',
        'Administrasi' => '2j, 17',
        'Operasional' => '2j, 18',
        'Utang Pajak' => '2g, 12',
        'Utang Usaha' => '2g, 13',
        'Hutang' => '2g, 13',
        'Modal' => '2i, 14',
        'Laba' => '14',
    ];
    foreach ($map as $key => $val) {
        if (stripos($nama_akun, $key) !== false) return $val;
    }
    return '';
}
?>



<!-- COVER -->
<div class="cover page-break">
    <div style="border: 4px solid #000; padding: 50px; width: 80%; margin: 0 auto;">
        <div class="cover-title"><?= strtoupper($companyName) ?></div>
        <div class="cover-title">LAPORAN KEUANGAN AUDITAN</div>
        <div class="cover-title">31 DESEMBER <?= $tahunBerjalan ?></div>
    </div>
    
    <div style="margin-top: 100px;">
        <table style="width: 50%; margin: 0 auto; border: 1px solid #000; text-align: left;">
            <tr><th colspan="2" class="text-center" style="background:#f0f0f0; color:#500050;">DRAFT LAPORAN KEUANGAN</th></tr>
            <tr><td colspan="2" class="text-center" style="color:#500050;">SETUJU / TIDAK SETUJU *</td></tr>
            <tr><td style="color:#500050;">TANGGAL</td><td></td></tr>
            <tr><td style="color:#500050;">OLEH</td><td></td></tr>
            <tr><td style="color:#500050;">JABATAN</td><td></td></tr>
            <tr><td style="color:#500050; height: 50px;">TANDA TANGAN</td><td></td></tr>
            <tr><td colspan="2" class="text-center" style="color:#500050;">DILARANG DIGANDAKAN</td></tr>
        </table>
        <p style="font-size: 8pt; color:#500050;">*coret yang tidak perlu</p>
    </div>
</div>


<!-- DAFTAR ISI -->
<div class="page-break">
    <div class="text-center font-bold mb-4" style="font-size: 14pt;">
        DAFTAR ISI
    </div>
    <div style="margin-top: 30px; font-size: 12pt; line-height: 2; width: 80%; margin-left: auto; margin-right: auto;">
        <div style="display: flex; justify-content: space-between;"><span style="flex-grow: 1;">NERACA</span></div>
        <div style="display: flex; justify-content: space-between;"><span style="flex-grow: 1;">LAPORAN LABA (RUGI)</span></div>
        <div style="display: flex; justify-content: space-between;"><span style="flex-grow: 1;">LAPORAN PERUBAHAN EKUITAS</span></div>
        <div style="display: flex; justify-content: space-between;"><span style="flex-grow: 1;">LAPORAN ARUS KAS</span></div>
        <div style="display: flex; justify-content: space-between;"><span style="flex-grow: 1;">CATATAN ATAS LAPORAN KEUANGAN</span></div>
        <div style="display: flex; justify-content: space-between;"><span style="flex-grow: 1;">LAMPIRAN - LAMPIRAN</span></div>
        <div style="display: flex; justify-content: space-between;"><span style="flex-grow: 1;">ANALISIS RASIO LAPORAN KEUANGAN</span></div>
        <div style="display: flex; justify-content: space-between;"><span style="flex-grow: 1;">ANALISIS RASIO KESINAMBUNGAN Z-SCORE MODEL</span></div>
    </div>
</div>

<!-- NERACA -->
<div class="page-break">
    <div class="text-center font-bold mb-2">
        <?= strtoupper($companyName) ?><br>
        NERACA<br>
        Per 31 Desember <?= $tahunBerjalan ?> dan <?= $tahunSebelumnya ?><br>
        (Dinyatakan Dalam Rupiah)
    </div>

    <table class="fin-table mt-5">
        <thead>
            <tr>
                <th class="text-left" style="width: 45%;">ASET</th>
                <th class="text-center" style="width: 15%;">Catatan</th>
                <th class="text-right" style="width: 20%;"><?= $tahunBerjalan ?></th>
                <th class="text-right" style="width: 20%;"><?= $tahunSebelumnya ?></th>
            </tr>
        </thead>
        <tbody>
            <tr><td colspan="3" class="font-bold">ASET LANCAR</td></tr>
            <?php foreach ($neraca['berjalan']['aset']['aset_lancar']['detail'] as $item): 
                $prev = getPrevAmount($neraca['sebelumnya']['aset']['aset_lancar']['detail'] ?? [], $item['kode_akun']);
            ?>
            <tr>
                <td style="padding-left: 20px;"><?= $item['nama_akun'] ?></td>
                <td class="text-center"><?= getCatatan($item['nama_akun']) ?></td>
                <td class="text-right"><?= formatRp($item['saldo']) ?></td>
                <td class="text-right"><?= formatRp($prev) ?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="subtotal-row">
                <td>Jumlah Aset Lancar</td>
                <td></td>
                <td class="text-right"><?= formatRp($neraca['berjalan']['aset']['aset_lancar']['total']) ?></td>
                <td class="text-right"><?= formatRp($neraca['sebelumnya']['aset']['aset_lancar']['total'] ?? 0) ?></td>
            </tr>
            
            <tr><td colspan="4">&nbsp;</td></tr>
            
            <tr><td colspan="4" class="font-bold">ASET TIDAK LANCAR</td></tr>
            <?php foreach ($neraca['berjalan']['aset']['aset_tidak_lancar']['detail'] as $item): 
                $prev = getPrevAmount($neraca['sebelumnya']['aset']['aset_tidak_lancar']['detail'] ?? [], $item['kode_akun']);
            ?>
            <tr>
                <td style="padding-left: 20px;"><?= $item['nama_akun'] ?></td>
                <td class="text-center"><?= getCatatan($item['nama_akun']) ?></td>
                <td class="text-right"><?= formatRp($item['saldo']) ?></td>
                <td class="text-right"><?= formatRp($prev) ?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="subtotal-row">
                <td>Jumlah Aset Tidak Lancar</td>
                <td></td>
                <td class="text-right"><?= formatRp($neraca['berjalan']['aset']['aset_tidak_lancar']['total']) ?></td>
                <td class="text-right"><?= formatRp($neraca['sebelumnya']['aset']['aset_tidak_lancar']['total'] ?? 0) ?></td>
            </tr>
            
            <tr class="total-row">
                <td>JUMLAH ASET</td>
                <td></td>
                <td class="text-right"><?= formatRp($neraca['berjalan']['aset']['total_aset']) ?></td>
                <td class="text-right"><?= formatRp($neraca['sebelumnya']['aset']['total_aset'] ?? 0) ?></td>
            </tr>
        </tbody>
    </table>

    <div class="text-center mt-5" style="font-size: 12px; margin-bottom: 20px;">
        Lihat Catatan atas Laporan Keuangan yang merupakan bagian<br>
        yang tidak terpisahkan dari laporan keuangan
    </div>
    <div class="text-center" style="font-size: 12px; margin-top: 30px;">
        Denpasar, <?= getTanggalIndo() ?><br>
        Direktur Utama<br><br><br><br><br>
        <strong>Rendy Mahameru Prayogie</strong><br>
        <br>
        i
    </div>

    <div style="page-break-before: always;"></div>

    <div class="text-center font-bold mb-2">
        PT ALMA INDONESIA RAYA<br>
        NERACA<br>
        Per 31 Desember <?= $tahunBerjalan ?> dan <?= $tahunSebelumnya ?><br>
        (Dinyatakan Dalam Rupiah)
    </div>

    <table class="fin-table mt-5">
        <thead>
            <tr>
                <th class="text-left" style="width: 45%;">LIABILITAS DAN EKUITAS</th>
                <th class="text-center" style="width: 15%;">Catatan</th>
                <th class="text-right" style="width: 20%;"><?= $tahunBerjalan ?></th>
                <th class="text-right" style="width: 20%;"><?= $tahunSebelumnya ?></th>
            </tr>
        </thead>
        <tbody>
            <tr><td colspan="4" class="font-bold">LIABILITAS JANGKA PENDEK</td></tr>
            <?php foreach ($neraca['berjalan']['liabilitas']['liabilitas_jangka_pendek']['detail'] as $item): 
                $prev = getPrevAmount($neraca['sebelumnya']['liabilitas']['liabilitas_jangka_pendek']['detail'] ?? [], $item['kode_akun']);
            ?>
            <tr>
                <td style="padding-left: 20px;"><?= $item['nama_akun'] ?></td>
                <td class="text-center"><?= getCatatan($item['nama_akun']) ?></td>
                <td class="text-right"><?= formatRp($item['saldo']) ?></td>
                <td class="text-right"><?= formatRp($prev) ?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="subtotal-row">
                <td>Jumlah Liabilitas Jangka Pendek</td>
                <td></td>
                <td class="text-right"><?= formatRp($neraca['berjalan']['liabilitas']['liabilitas_jangka_pendek']['total']) ?></td>
                <td class="text-right"><?= formatRp($neraca['sebelumnya']['liabilitas']['liabilitas_jangka_pendek']['total'] ?? 0) ?></td>
            </tr>
            
            <tr><td colspan="4">&nbsp;</td></tr>
            
            <tr><td colspan="4" class="font-bold">EKUITAS</td></tr>
            <?php foreach ($neraca['berjalan']['ekuitas']['detail'] as $item): 
                $prev = getPrevAmount($neraca['sebelumnya']['ekuitas']['detail'] ?? [], $item['kode_akun']);
            ?>
            <tr>
                <td style="padding-left: 20px;"><?= $item['nama_akun'] ?></td>
                <td class="text-center"><?= getCatatan($item['nama_akun']) ?></td>
                <td class="text-right"><?= formatRp($item['saldo']) ?></td>
                <td class="text-right"><?= formatRp($prev) ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td style="padding-left: 20px;">Laba (Rugi) Tahun Berjalan</td>
                <td class="text-center">14</td>
                <td class="text-right"><?= formatRp($labaRugi['berjalan']['laba_rugi_tahun_berjalan']) ?></td>
                <td class="text-right"><?= formatRp($labaRugi['sebelumnya']['laba_rugi_tahun_berjalan'] ?? 0) ?></td>
            </tr>
            <tr class="subtotal-row">
                <td>Jumlah Ekuitas</td>
                <td></td>
                <td class="text-right"><?= formatRp($neraca['berjalan']['ekuitas']['total_ekuitas_with_profit']) ?></td>
                <td class="text-right"><?= formatRp($neraca['sebelumnya']['ekuitas']['total_ekuitas_with_profit'] ?? 0) ?></td>
            </tr>
            
            <tr class="total-row">
                <td>JUMLAH LIABILITAS DAN EKUITAS</td>
                <td></td>
                <td class="text-right"><?= formatRp($neraca['berjalan']['liabilitas']['total_liabilitas'] + $neraca['berjalan']['ekuitas']['total_ekuitas_with_profit']) ?></td>
                <td class="text-right"><?= formatRp(($neraca['sebelumnya']['liabilitas']['total_liabilitas'] ?? 0) + ($neraca['sebelumnya']['ekuitas']['total_ekuitas_with_profit'] ?? 0)) ?></td>
            </tr>
        </tbody>
    </table>
    <div class="text-center mt-5" style="font-size: 12px; margin-bottom: 20px;">
        Lihat Catatan atas Laporan Keuangan yang merupakan bagian<br>
        yang tidak terpisahkan dari laporan keuangan
    </div>
    <div class="text-center" style="font-size: 12px; margin-top: 30px;">
        Denpasar, <?= getTanggalIndo() ?><br>
        Direktur Utama<br><br><br><br><br>
        <strong>Rendy Mahameru Prayogie</strong><br>
        <br>
        ii
    </div>
</div>

<!-- LABA RUGI -->
<div class="page-break">
    <div class="text-center font-bold mb-2">
        <?= strtoupper($companyName) ?><br>
        LAPORAN LABA (RUGI)<br>
        Untuk Tahun yang Berakhir pada Tanggal 31 Desember <?= $tahunBerjalan ?> dan <?= $tahunSebelumnya ?><br>
        (Dinyatakan Dalam Rupiah)
    </div>

    <table class="fin-table mt-5">
        <thead>
            <tr>
                <th class="text-left">URAIAN</th>
                <th class="text-center" style="width: 15%">Catatan</th>
                <th class="text-right"><?= $tahunBerjalan ?></th>
                <th class="text-right"><?= $tahunSebelumnya ?></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="font-bold">PENDAPATAN</td>
                <td class="text-center">2j, 15</td>
                <td class="text-right"><?= formatRp($labaRugi['berjalan']['pendapatan']['pendapatan_usaha']['total']) ?></td>
                <td class="text-right"><?= formatRp($labaRugi['sebelumnya']['pendapatan']['pendapatan_usaha']['total'] ?? 0) ?></td>
            </tr>
            <tr>
                <td class="font-bold">BEBAN POKOK PENDAPATAN</td>
                <td class="text-center">2j, 16</td>
                <td class="text-right"><?= formatRp($labaRugi['berjalan']['hpp']['total'] * -1) ?></td>
                <td class="text-right"><?= formatRp(($labaRugi['sebelumnya']['hpp']['total'] ?? 0) * -1) ?></td>
            </tr>
            <tr class="subtotal-row">
                <td class="font-bold">LABA (RUGI) KOTOR</td>
                <td></td>
                <td class="text-right font-bold"><?= formatRp($labaRugi['berjalan']['laba_kotor']) ?></td>
                <td class="text-right font-bold"><?= formatRp($labaRugi['sebelumnya']['laba_kotor'] ?? 0) ?></td>
            </tr>
            
            <tr><td colspan="4">&nbsp;</td></tr>
            
            <tr><td colspan="4" class="font-bold">BEBAN USAHA</td></tr>
            <?php foreach ($labaRugi['berjalan']['beban']['beban_operasional']['detail'] as $item): 
                $prev = getPrevAmount($labaRugi['sebelumnya']['beban']['beban_operasional']['detail'] ?? [], $item['kode_akun']);
            ?>
            <tr>
                <td style="padding-left: 20px;"><?= $item['nama_akun'] ?></td>
                <td class="text-center"><?= getCatatan($item['nama_akun']) ?></td>
                <td class="text-right"><?= formatRp($item['saldo'] * -1) ?></td>
                <td class="text-right"><?= formatRp($prev * -1) ?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="subtotal-row">
                <td>Jumlah Beban Usaha</td>
                <td></td>
                <td class="text-right"><?= formatRp($labaRugi['berjalan']['beban']['beban_operasional']['total'] * -1) ?></td>
                <td class="text-right"><?= formatRp(($labaRugi['sebelumnya']['beban']['beban_operasional']['total'] ?? 0) * -1) ?></td>
            </tr>
            
            <tr class="subtotal-row">
                <td class="font-bold">LABA (RUGI) USAHA</td>
                <td></td>
                <td class="text-right font-bold"><?= formatRp($labaRugi['berjalan']['laba_rugi_operasional']) ?></td>
                <td class="text-right font-bold"><?= formatRp($labaRugi['sebelumnya']['laba_rugi_operasional'] ?? 0) ?></td>
            </tr>
            
            <tr><td colspan="4">&nbsp;</td></tr>
            
            <tr>
                <td class="font-bold">PENDAPATAN (BEBAN) DILUAR USAHA</td>
                <td class="text-center">2j, 21</td>
                <td class="text-right"><?= formatRp($labaRugi['berjalan']['pendapatan_beban_diluar_usaha']) ?></td>
                <td class="text-right"><?= formatRp($labaRugi['sebelumnya']['pendapatan_beban_diluar_usaha'] ?? 0) ?></td>
            </tr>

            <tr>
                <td class="font-bold" style="padding-left: 0px;">Beban Pajak</td>
                <td class="text-center">2j, 2k, 22</td>
                <td class="text-right"><?= $labaRugi['berjalan']['beban']['beban_pajak'] == 0 ? '-' : formatRp($labaRugi['berjalan']['beban']['beban_pajak'] * -1) ?></td>
                <td class="text-right"><?= ($labaRugi['sebelumnya']['beban']['beban_pajak'] ?? 0) == 0 ? '-' : formatRp(($labaRugi['sebelumnya']['beban']['beban_pajak'] ?? 0) * -1) ?></td>
            </tr>
            
            <tr class="total-row">
                <td class="font-bold">LABA (RUGI) TAHUN BERJALAN</td>
                <td></td>
                <td class="text-right font-bold"><?= formatRp($labaRugi['berjalan']['laba_rugi_tahun_berjalan']) ?></td>
                <td class="text-right font-bold"><?= formatRp($labaRugi['sebelumnya']['laba_rugi_tahun_berjalan'] ?? 0) ?></td>
            </tr>
        </tbody>
    </table>

    <div class="text-center mt-5" style="font-size: 12px; margin-bottom: 20px;">
        Lihat Catatan atas Laporan Keuangan yang merupakan bagian<br>
        yang tidak terpisahkan dari laporan keuangan
    </div>
    <div class="text-center" style="font-size: 12px; margin-top: 30px;">
        Denpasar, <?= getTanggalIndo() ?><br>
        Direktur Utama<br><br><br><br><br>
        <strong>Rendy Mahameru Prayogie</strong><br>
        <br>
        iii
    </div>
</div>

<!-- PERUBAHAN EKUITAS -->
<?php
// Extract Ekuitas Data
$modal2024 = 0; $labaDitahan2024 = 0; $labaBerjalan2024 = $labaRugi['sebelumnya']['laba_rugi_tahun_berjalan'] ?? 0;
if (isset($neraca['sebelumnya']['ekuitas']['detail'])) {
    foreach ($neraca['sebelumnya']['ekuitas']['detail'] as $item) {
        if ($item['nama_akun'] === 'Modal Saham') $modal2024 = $item['saldo'];
        if ($item['nama_akun'] === 'Laba (Rugi) Ditahan') $labaDitahan2024 = $item['saldo'];
    }
}
$modal2025 = 0; $labaDitahan2025 = 0; $labaBerjalan2025 = $labaRugi['berjalan']['laba_rugi_tahun_berjalan'] ?? 0;
if (isset($neraca['berjalan']['ekuitas']['detail'])) {
    foreach ($neraca['berjalan']['ekuitas']['detail'] as $item) {
        if ($item['nama_akun'] === 'Modal Saham') $modal2025 = $item['saldo'];
        if ($item['nama_akun'] === 'Laba (Rugi) Ditahan') $labaDitahan2025 = $item['saldo'];
    }
}

// 2024 Calculations
$saldoAwalModal2024 = $modal2024;
$saldoAwalLaba2024 = $labaDitahan2024;
$saldoAkhirModal2024 = $modal2024;
$saldoAkhirLaba2024 = $labaDitahan2024 + $labaBerjalan2024;

// 2025 Calculations
$saldoAwalModal2025 = $saldoAkhirModal2024;
$saldoAwalLaba2025 = $saldoAkhirLaba2024;
// Koreksi is whatever is needed to make Saldo Awal Laba 2025 + Koreksi = Laba Ditahan 2025
$koreksiLaba2025 = $labaDitahan2025 - $saldoAwalLaba2025;
$saldoAkhirModal2025 = $modal2025;
$saldoAkhirLaba2025 = $labaDitahan2025 + $labaBerjalan2025;
?>
<div class="page-break">
    <div class="text-center font-bold mb-2">
        <?= strtoupper($companyName) ?><br>
        LAPORAN PERUBAHAN EKUITAS<br>
        Untuk Tahun-tahun yang Berakhir pada Tanggal 31 Desember <?= $tahunBerjalan ?> dan <?= $tahunSebelumnya ?><br>
        (Dinyatakan Dalam Rupiah)
    </div>

    <table class="fin-table mt-5">
        <thead>
            <tr>
                <th class="text-left">URAIAN</th>
                <th class="text-center">CATATAN</th>
                <th class="text-right">MODAL</th>
                <th class="text-right">SALDO<br>LABA (RUGI)</th>
                <th class="text-right">JUMLAH</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Saldo Awal, 1 Januari <?= $tahunSebelumnya ?></td>
                <td class="text-center">2i, 14</td>
                <td class="text-right"><?= formatRp($saldoAwalModal2024) ?></td>
                <td class="text-right"><?= formatRp($saldoAwalLaba2024) ?></td>
                <td class="text-right"><?= formatRp($saldoAwalModal2024 + $saldoAwalLaba2024) ?></td>
            </tr>
            <tr>
                <td style="padding-left: 20px;">Laba (Rugi) Tahun Berjalan</td>
                <td class="text-center">14</td>
                <td class="text-right">-</td>
                <td class="text-right"><?= formatRp($labaBerjalan2024) ?></td>
                <td class="text-right"><?= formatRp($labaBerjalan2024) ?></td>
            </tr>
            <tr class="font-bold border-top-thick">
                <td>Saldo Akhir 31 Desember <?= $tahunSebelumnya ?></td>
                <td></td>
                <td class="text-right"><?= formatRp($saldoAkhirModal2024) ?></td>
                <td class="text-right"><?= formatRp($saldoAkhirLaba2024) ?></td>
                <td class="text-right"><?= formatRp($saldoAkhirModal2024 + $saldoAkhirLaba2024) ?></td>
            </tr>
            <tr>
                <td colspan="5">&nbsp;</td>
            </tr>
            <tr>
                <td>Saldo Awal, 1 Januari <?= $tahunBerjalan ?></td>
                <td class="text-center">2i, 14</td>
                <td class="text-right"><?= formatRp($saldoAwalModal2025) ?></td>
                <td class="text-right"><?= formatRp($saldoAwalLaba2025) ?></td>
                <td class="text-right"><?= formatRp($saldoAwalModal2025 + $saldoAwalLaba2025) ?></td>
            </tr>
            <?php if ($koreksiLaba2025 != 0): ?>
            <tr>
                <td style="padding-left: 20px;">Koreksi Laba (Rugi) Ditahan</td>
                <td class="text-center">14</td>
                <td class="text-right">-</td>
                <td class="text-right"><?= formatRp($koreksiLaba2025) ?></td>
                <td class="text-right"><?= formatRp($koreksiLaba2025) ?></td>
            </tr>
            <?php endif; ?>
            <tr>
                <td style="padding-left: 20px;">Laba (Rugi) Tahun Berjalan</td>
                <td class="text-center">14</td>
                <td class="text-right">-</td>
                <td class="text-right"><?= formatRp($labaBerjalan2025) ?></td>
                <td class="text-right"><?= formatRp($labaBerjalan2025) ?></td>
            </tr>
            <tr class="font-bold border-top-thick">
                <td>Saldo Akhir 31 Desember <?= $tahunBerjalan ?></td>
                <td></td>
                <td class="text-right"><?= formatRp($saldoAkhirModal2025) ?></td>
                <td class="text-right"><?= formatRp($saldoAkhirLaba2025) ?></td>
                <td class="text-right"><?= formatRp($saldoAkhirModal2025 + $saldoAkhirLaba2025) ?></td>
            </tr>
        </tbody>
    </table>

    <div class="text-center mt-3" style="font-size: 12px;">
        Lihat Catatan atas Laporan Keuangan yang merupakan bagian<br>
        yang tidak terpisahkan dari laporan keuangan
    </div>
    <div class="text-center" style="font-size: 12px; margin-top: 30px;">
        iv
    </div>
</div>

<!-- ARUS KAS -->
<div class="page-break">
    <div class="text-center font-bold mb-2">
        <?= strtoupper($companyName) ?><br>
        LAPORAN ARUS KAS<br>
        Untuk Tahun-tahun yang Berakhir pada Tanggal 31 Desember <?= $tahunBerjalan ?> dan <?= $tahunSebelumnya ?><br>
        (Dalam Rupiah)
    </div>

    <style>
        .fin-table-compact td { padding: 3px 5px; font-size: 9pt; }
        .fin-table-compact th { padding: 5px 5px; font-size: 9pt; }
    </style>
    <table class="fin-table fin-table-compact mt-3">
        <thead>
            <tr>
                <th class="text-left" style="width: 50%;">URAIAN</th>
                <th class="text-right" style="width: 25%;"></th>
                <th class="text-right" style="width: 25%;"><?= $tahunBerjalan ?></th>
            </tr>
        </thead>
        <tbody>
            <tr><td colspan="3" class="font-bold">ARUS KAS DARI AKTIVITAS OPERASI</td></tr>
            <tr>
                <td style="padding-left: 20px;">Laba (Rugi) Tahun Berjalan</td>
                <td class="text-right"><?= formatRp($arusKas['berjalan']['aktivitas_operasi']['laba_rugi_tahun_berjalan']) ?></td>
                <td></td>
            </tr>
            <?php foreach ($arusKas['berjalan']['aktivitas_operasi']['penyusutan_amortisasi']['detail'] as $item): ?>
            <tr>
                <td style="padding-left: 20px;"><?= $item['nama_akun'] ?></td>
                <td class="text-right"><?= formatRp($item['saldo']) ?></td>
                <td></td>
            </tr>
            <?php endforeach; ?>
            <tr class="subtotal-row">
                <td style="padding-left: 20px;">Laba (Rugi) Operasi Sebelum Perubahan Modal Kerja</td>
                <td></td>
                <td class="text-right"><?= formatRp($arusKas['berjalan']['aktivitas_operasi']['laba_operasi_sebelum_perubahan_mk']) ?></td>
            </tr>

            <tr><td colspan="3">&nbsp;</td></tr>
            <tr><td colspan="3" style="padding-left: 20px;">Perubahan Modal Kerja:</td></tr>
            <?php foreach ($arusKas['berjalan']['aktivitas_operasi']['perubahan_modal_kerja']['aset_lancar_non_kas'] as $item): ?>
            <tr>
                <td style="padding-left: 40px;"><?= $item['saldo'] > 0 ? 'Kenaikan' : 'Penurunan' ?> <?= $item['nama_akun'] ?></td>
                <td class="text-right"><?= formatRp($item['saldo'] * -1) ?></td>
                <td></td>
            </tr>
            <?php endforeach; ?>
            <?php foreach ($arusKas['berjalan']['aktivitas_operasi']['perubahan_modal_kerja']['liabilitas_pendek'] as $item): ?>
            <tr>
                <td style="padding-left: 40px;"><?= $item['saldo'] > 0 ? 'Kenaikan' : 'Penurunan' ?> <?= $item['nama_akun'] ?></td>
                <td class="text-right"><?= formatRp($item['saldo']) ?></td>
                <td></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td style="padding-left: 20px;">Jumlah Perubahan Modal Kerja</td>
                <td class="text-right" style="text-decoration: underline;"><?= formatRp($arusKas['berjalan']['aktivitas_operasi']['perubahan_modal_kerja']['total']) ?></td>
                <td></td>
            </tr>
            <tr class="font-bold">
                <td>Arus Kas dari Aktivitas Operasi</td>
                <td></td>
                <td class="text-right"><?= formatRp($arusKas['berjalan']['aktivitas_operasi']['arus_kas_operasi']) ?></td>
            </tr>

            <tr><td colspan="3">&nbsp;</td></tr>
            <tr><td colspan="3" class="font-bold">ARUS KAS DARI AKTIVITAS INVESTASI</td></tr>
            <?php foreach ($arusKas['berjalan']['aktivitas_investasi']['detail'] as $item): ?>
            <tr>
                <td style="padding-left: 20px;"><?= $item['saldo'] > 0 ? 'Kenaikan' : 'Penurunan' ?> <?= $item['nama_akun'] ?></td>
                <td class="text-right"><?= formatRp($item['saldo'] * -1) ?></td>
                <td></td>
            </tr>
            <?php endforeach; ?>
            <tr class="font-bold">
                <td>Arus Kas dari Aktivitas Investasi</td>
                <td></td>
                <td class="text-right"><?= formatRp($arusKas['berjalan']['aktivitas_investasi']['arus_kas_investasi']) ?></td>
            </tr>

            <tr><td colspan="3">&nbsp;</td></tr>
            <tr><td colspan="3" class="font-bold">ARUS KAS DARI AKTIVITAS PENDANAAN</td></tr>
            <?php foreach ($arusKas['berjalan']['aktivitas_pendanaan']['detail_liabilitas_panjang'] as $item): ?>
            <tr>
                <td style="padding-left: 20px;"><?= $item['saldo'] > 0 ? 'Kenaikan' : 'Penurunan' ?> <?= $item['nama_akun'] ?></td>
                <td class="text-right"><?= formatRp($item['saldo']) ?></td>
                <td></td>
            </tr>
            <?php endforeach; ?>
            <?php foreach ($arusKas['berjalan']['aktivitas_pendanaan']['detail_ekuitas'] as $item): 
                if ($item['nama_akun'] === 'Modal Saham') continue; // skip basic
            ?>
            <tr>
                <td style="padding-left: 20px;"><?= $item['saldo'] > 0 ? 'Kenaikan' : 'Penurunan' ?> <?= $item['nama_akun'] ?></td>
                <td class="text-right"><?= formatRp($item['saldo']) ?></td>
                <td></td>
            </tr>
            <?php endforeach; ?>
            <tr class="font-bold">
                <td>Arus Kas dari Aktivitas Pendanaan</td>
                <td></td>
                <td class="text-right"><?= formatRp($arusKas['berjalan']['aktivitas_pendanaan']['arus_kas_pendanaan']) ?></td>
            </tr>

            <tr><td colspan="3">&nbsp;</td></tr>
            <tr class="font-bold">
                <td>Kenaikan (Penurunan) Arus Kas</td>
                <td class="text-right"><?= formatRp($arusKas['berjalan']['perubahan_kas_bersih']) ?></td>
                <td></td>
            </tr>
            <tr class="font-bold">
                <td>Kas dan Setara Kas Awal Tahun</td>
                <td class="text-right"><?= formatRp($arusKas['berjalan']['kas_awal_periode']) ?></td>
                <td></td>
            </tr>
            <tr class="font-bold border-top-thick border-bottom-thick">
                <td>Kas dan Setara Kas Akhir Tahun</td>
                <td></td>
                <td class="text-right"><?= formatRp($arusKas['berjalan']['kas_akhir_periode']) ?></td>
            </tr>
        </tbody>
    </table>

    <div class="text-center mt-3" style="font-size: 12px;">
        Lihat Catatan atas Laporan Keuangan yang merupakan bagian<br>
        yang tidak terpisahkan dari laporan keuangan
    </div>
    <div class="text-center" style="font-size: 12px; margin-top: 30px;">
        v
    </div>
</div>

<!-- CATATAN ATAS LAPORAN KEUANGAN (CALK) -->
<div class="page-break">
    <div class="text-center font-bold mb-2">
        <?= strtoupper($companyName) ?><br>
        CATATAN ATAS LAPORAN KEUANGAN<br>
        Untuk Tahun yang Berakhir pada Tanggal 31 Desember <?= $tahunBerjalan ?> dan <?= $tahunSebelumnya ?><br>
        (Dinyatakan Dalam Rupiah)
    </div>
    
    <div class="mt-5" style="text-align: justify;">
        <?php if (!empty($calk['sections'])): ?>
            <?php foreach ($calk['sections'] as $index => $section): ?>
                <?php if (!empty($section['page_break'])): ?>
                    <div style="page-break-before: always;"></div>
                    <div class="text-center font-bold mb-4" style="margin-top: 20px;">
                        <?= strtoupper($companyName) ?><br>
                        CATATAN ATAS LAPORAN KEUANGAN<br>
                        Untuk Tahun - Tahun yang Berakhir pada Tanggal 31 Desember <?= $tahunBerjalan ?> dan <?= $tahunSebelumnya ?><br>
                        (Dinyatakan Dalam Rupiah)
                    </div>
                    <hr style="border-top: 1px solid #000; margin-bottom: 20px;">
                <?php endif; ?>

                <?php if (empty($section['hide_title'])): ?>
                    <h4 style="margin-top: 15px; margin-bottom: 5px;"><?= (empty($section['hide_number']) ? ($index + 1) . '. ' : '') . $section['title'] ?></h4>
                <?php endif; ?>
                
                <?php if ($section['type'] === 'narrative' && !empty($section['subsections'])): ?>
                    <?php 
                    $alpha = 'a';
                    foreach ($section['subsections'] as $sub): 
                    ?>
                        <div style="page-break-inside: avoid; margin-bottom: 20px;">
                            <?php if (empty($sub['hide_title'])): ?>
                                <p style="font-weight: bold; margin-left: 20px; margin-bottom: 10px;"><?= (empty($sub['hide_letter']) ? $alpha . '. ' : '') . $sub['title'] ?></p>
                            <?php endif; ?>
                            <?php if ($sub['type'] === 'narrative'): ?>
                                <div style="margin-left: <?= !empty($sub['hide_letter']) ? '0px' : '40px' ?>; text-align: justify; line-height: 1.5;">
                                    <?php 
                                    $lines = explode("\n", $sub['content']);
                                    foreach ($lines as $line):
                                        $line = trim($line);
                                        if (empty($line)) continue;
                                        if (strpos($line, '•') === 0 || strpos($line, '-') === 0) {
                                            // Bullet point line with hanging indent
                                            $text = trim(substr($line, 1));
                                            echo '<div style="padding-left: 15px; text-indent: -15px; margin-bottom: 5px;">&bull; ' . htmlspecialchars($text) . '</div>';
                                        } else {
                                            // Normal paragraph
                                            echo '<p style="margin-bottom: 10px;">' . htmlspecialchars($line) . '</p>';
                                        }
                                    endforeach;
                                    ?>
                                </div>
                            <?php elseif ($sub['type'] === 'table' || $sub['type'] === 'financial_table'): ?>
                                <table class="fin-table <?= !empty($sub['full_border']) ? 'full-border' : '' ?>" style="width: 100%; margin-left: <?= !empty($sub['hide_letter']) ? '0px' : '40px' ?>; margin-bottom: 10px;">
                                    <thead>
                                        <tr>
                                            <?php foreach ($sub['columns'] as $col): ?>
                                                <th class="<?= ($col['type'] ?? 'text') === 'currency' ? 'text-right' : 'text-left' ?>"><?= htmlspecialchars($col['name']) ?></th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($sub['rows'] as $row): ?>
                                            <tr>
                                                <?php foreach ($sub['columns'] as $col): ?>
                                                    <td class="<?= ($col['type'] ?? 'text') === 'currency' ? 'text-right' : 'text-left' ?>">
                                                        <?php 
                                                        $val = $row[$col['name']] ?? '';
                                                        if (($col['type'] ?? 'text') === 'currency' && is_numeric($val)) {
                                                            echo ($val < 0 ? '(' : '') . number_format(abs($val), 0, ',', '.') . ($val < 0 ? ')' : '');
                                                        } else {
                                                            echo str_replace('&amp;nbsp;', '&nbsp;', htmlspecialchars((string)$val));
                                                        }
                                                        ?>
                                                    </td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                        <?php $alpha++; ?>
                    <?php endforeach; ?>
                <?php elseif (in_array($section['type'], ['table', 'financial_table']) && !empty($section['rows'])): ?>
                    <table class="fin-table" style="width: 100%; margin-bottom: 20px; margin-left: 20px;">
                        <thead>
                            <tr>
                                <?php foreach ($section['columns'] as $col): ?>
                                    <th class="<?= ($col['type'] ?? 'text') === 'currency' ? 'text-right' : 'text-left' ?>"><?= htmlspecialchars($col['name']) ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($section['rows'] as $row): ?>
                                <tr>
                                    <?php foreach ($section['columns'] as $col): ?>
                                        <td class="<?= ($col['type'] ?? 'text') === 'currency' ? 'text-right' : 'text-left' ?>">
                                            <?php 
                                            $val = $row[$col['name']] ?? '';
                                            if (($col['type'] ?? 'text') === 'currency' && is_numeric($val)) {
                                                echo ($val < 0 ? '(' : '') . number_format(abs($val), 0, ',', '.') . ($val < 0 ? ')' : '');
                                            } else {
                                                echo str_replace('&amp;nbsp;', '&nbsp;', htmlspecialchars((string)$val));
                                            }
                                            ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
                
            <?php endforeach; ?>
        <?php else: ?>
            <p>Data Catatan Atas Laporan Keuangan belum diisi secara lengkap.</p>
        <?php endif; ?>
    </div>
</div>

<!-- LAMPIRAN - LAMPIRAN ASET TETAP -->
<div class="page-break landscape-mode">
    <div class="text-center font-bold mb-4" style="font-size: 14pt;">
        LAMPIRAN - LAMPIRAN
    </div>
    
    <div class="font-bold mb-2" style="font-size: 10pt;">
        <?= strtoupper($companyName) ?><br>
        DAFTAR ASET TETAP DAN PERHITUNGAN PENYUSUTANNYA<br>
        Per 31 Desember <?= $tahunBerjalan ?><br>
        (Dalam Rupiah)
    </div>

    <style>
        .table-lampiran th, .table-lampiran td {
            border: 1px solid #000;
            padding: 3px;
        }
    </style>

    <table class="fin-table table-lampiran" style="font-size: 6pt; width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th class="text-center align-middle" style="width: 2%;">No</th>
                <th class="text-center align-middle" style="width: 15%;">Uraian</th>
                <th class="text-center align-middle" style="width: 6%;">Tanggal<br>Beli</th>
                <th class="text-center align-middle" style="width: 3%;">%</th>
                <th class="text-center">Harga Perolehan<br>(<?= $tahunSebelumnya ?>)</th>
                <th class="text-center">Beban<br>Penyusutan<br>(<?= $tahunSebelumnya ?>)</th>
                <th class="text-center">Akumulasi<br>Penyusutan<br>(<?= $tahunSebelumnya ?>)</th>
                <th class="text-center">Nilai Buku<br>(<?= $tahunSebelumnya ?>)</th>
                <th class="text-center align-middle" style="width: 7%;">Mutasi</th>
                <th class="text-center">Harga Perolehan<br>(<?= $tahunBerjalan ?>)</th>
                <th class="text-center">Beban<br>Penyusutan<br>(<?= $tahunBerjalan ?>)</th>
                <th class="text-center">Akumulasi<br>Penyusutan<br>(<?= $tahunBerjalan ?>)</th>
                <th class="text-center">Nilai Buku<br>(<?= $tahunBerjalan ?>)</th>
            </tr>
        </thead>
            <?php 
            $romawi = ['I', 'II', 'III', 'IV', 'V'];
            $i = 0;
            
            $maxRows = 38; // adjust this value based on how many fit on one landscape page
            $currentRowCount = 0;
            
            $grandTotalPrevHarga = 0;
            $grandTotalPrevBeban = 0;
            $grandTotalPrevAkum = 0;
            $grandTotalPrevNilai = 0;
            $grandTotalMutasi = 0;
            $grandTotalCurrHarga = 0;
            $grandTotalCurrBeban = 0;
            $grandTotalCurrAkum = 0;
            $grandTotalCurrNilai = 0;
            
            $headerTable = '
            <table class="fin-table table-lampiran" style="font-size: 6pt; width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th class="text-center align-middle" style="width: 2%;">No</th>
                        <th class="text-center align-middle" style="width: 15%;">Uraian</th>
                        <th class="text-center align-middle" style="width: 6%;">Tanggal<br>Beli</th>
                        <th class="text-center align-middle" style="width: 3%;">%</th>
                        <th class="text-center">Harga Perolehan<br>('.$tahunSebelumnya.')</th>
                        <th class="text-center">Beban<br>Penyusutan<br>('.$tahunSebelumnya.')</th>
                        <th class="text-center">Akumulasi<br>Penyusutan<br>('.$tahunSebelumnya.')</th>
                        <th class="text-center">Nilai Buku<br>('.$tahunSebelumnya.')</th>
                        <th class="text-center align-middle" style="width: 7%;">Mutasi</th>
                        <th class="text-center">Harga Perolehan<br>('.$tahunBerjalan.')</th>
                        <th class="text-center">Beban<br>Penyusutan<br>('.$tahunBerjalan.')</th>
                        <th class="text-center">Akumulasi<br>Penyusutan<br>('.$tahunBerjalan.')</th>
                        <th class="text-center">Nilai Buku<br>('.$tahunBerjalan.')</th>
                    </tr>
                </thead>
                <tbody>';

            foreach ($lampiran['asetTetap'] as $groupName => $items): 
                $groupRomawi = $romawi[$i++];
                
                if ($currentRowCount >= $maxRows) {
                    echo '</tbody></table><div class="page-break landscape-mode"></div>';
                    echo '<div class="font-bold mb-2" style="font-size: 10pt;">
                        '.strtoupper($companyName).'<br>
                        DAFTAR ASET TETAP DAN PERHITUNGAN PENYUSUTANNYA<br>
                        Per 31 Desember '.$tahunBerjalan.'<br>
                        (Dalam Rupiah)
                    </div>';
                    echo $headerTable;
                    $currentRowCount = 0;
                }
                
                echo '<tr class="font-bold bg-gray-100">
                    <td class="text-center">'.$groupRomawi.'</td>
                    <td colspan="12">'.$groupName.'</td>
                </tr>';
                $currentRowCount++;

                $no = 1; 
                $sumPrevHarga = 0; $sumPrevBeban = 0; $sumPrevAkum = 0; $sumPrevNilai = 0;
                $sumMutasi = 0;
                $sumCurrHarga = 0; $sumCurrBeban = 0; $sumCurrAkum = 0; $sumCurrNilai = 0;
                
                foreach ($items as $item): 
                    if ($currentRowCount >= $maxRows) {
                        echo '</tbody></table><div class="page-break landscape-mode"></div>';
                        echo '<div class="font-bold mb-2" style="font-size: 10pt;">
                            '.strtoupper($companyName).'<br>
                            DAFTAR ASET TETAP DAN PERHITUNGAN PENYUSUTANNYA<br>
                            Per 31 Desember '.$tahunBerjalan.'<br>
                            (Dalam Rupiah)
                        </div>';
                        echo $headerTable;
                        echo '<tr class="font-bold bg-gray-100">
                            <td class="text-center">'.$groupRomawi.'</td>
                            <td colspan="12">'.$groupName.' (Lanjutan)</td>
                        </tr>';
                        $currentRowCount = 1;
                    }

                    $sumPrevHarga += $item['prev']['harga'];
                    $sumPrevBeban += $item['prev']['beban'];
                    $sumPrevAkum += $item['prev']['akumulasi'];
                    $sumPrevNilai += $item['prev']['nilai_buku'];
                    $sumMutasi += $item['mutasi'];
                    $sumCurrHarga += $item['curr']['harga'];
                    $sumCurrBeban += $item['curr']['beban'];
                    $sumCurrAkum += $item['curr']['akumulasi'];
                    $sumCurrNilai += $item['curr']['nilai_buku'];
            ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= $item['nama'] ?></td>
                <td class="text-center"><?= $item['tanggal'] ?></td>
                <td class="text-center"><?= $item['tarif'] ?>%</td>
                <td class="text-right"><?= $item['prev']['harga'] > 0 ? formatRp($item['prev']['harga']) : '-' ?></td>
                <td class="text-right"><?= $item['prev']['beban'] > 0 ? formatRp($item['prev']['beban']) : '-' ?></td>
                <td class="text-right"><?= $item['prev']['akumulasi'] > 0 ? formatRp($item['prev']['akumulasi']) : '-' ?></td>
                <td class="text-right"><?= $item['prev']['nilai_buku'] > 0 ? formatRp($item['prev']['nilai_buku']) : '-' ?></td>
                <td class="text-right"><?= $item['mutasi'] > 0 ? formatRp($item['mutasi']) : '-' ?></td>
                <td class="text-right"><?= $item['curr']['harga'] > 0 ? formatRp($item['curr']['harga']) : '-' ?></td>
                <td class="text-right"><?= $item['curr']['beban'] > 0 ? formatRp($item['curr']['beban']) : '-' ?></td>
                <td class="text-right"><?= $item['curr']['akumulasi'] > 0 ? formatRp($item['curr']['akumulasi']) : '-' ?></td>
                <td class="text-right"><?= $item['curr']['nilai_buku'] > 0 ? formatRp($item['curr']['nilai_buku']) : '-' ?></td>
            </tr>
            <?php 
                    $currentRowCount++;
                endforeach; 
                
                if ($currentRowCount >= $maxRows) {
                    echo '</tbody></table><div class="page-break landscape-mode"></div>';
                    echo '<div class="font-bold mb-2" style="font-size: 10pt;">
                        '.strtoupper($companyName).'<br>
                        DAFTAR ASET TETAP DAN PERHITUNGAN PENYUSUTANNYA<br>
                        Per 31 Desember '.$tahunBerjalan.'<br>
                        (Dalam Rupiah)
                    </div>';
                    echo $headerTable;
                    $currentRowCount = 0;
                }
            ?>
            <tr class="font-bold bg-blue-100">
                <td></td>
                <td>Jumlah <?= $groupName ?></td>
                <td></td>
                <td></td>
                <td class="text-right"><?= $sumPrevHarga > 0 ? formatRp($sumPrevHarga) : '-' ?></td>
                <td class="text-right"><?= $sumPrevBeban > 0 ? formatRp($sumPrevBeban) : '-' ?></td>
                <td class="text-right"><?= $sumPrevAkum > 0 ? formatRp($sumPrevAkum) : '-' ?></td>
                <td class="text-right"><?= $sumPrevNilai > 0 ? formatRp($sumPrevNilai) : '-' ?></td>
                <td class="text-right"><?= $sumMutasi > 0 ? formatRp($sumMutasi) : '-' ?></td>
                <td class="text-right"><?= $sumCurrHarga > 0 ? formatRp($sumCurrHarga) : '-' ?></td>
                <td class="text-right"><?= $sumCurrBeban > 0 ? formatRp($sumCurrBeban) : '-' ?></td>
                <td class="text-right"><?= $sumCurrAkum > 0 ? formatRp($sumCurrAkum) : '-' ?></td>
                <td class="text-right"><?= $sumCurrNilai > 0 ? formatRp($sumCurrNilai) : '-' ?></td>
            </tr>
            <?php 
                $grandTotalPrevHarga += $sumPrevHarga;
                $grandTotalPrevBeban += $sumPrevBeban;
                $grandTotalPrevAkum += $sumPrevAkum;
                $grandTotalPrevNilai += $sumPrevNilai;
                $grandTotalMutasi += $sumMutasi;
                $grandTotalCurrHarga += $sumCurrHarga;
                $grandTotalCurrBeban += $sumCurrBeban;
                $grandTotalCurrAkum += $sumCurrAkum;
                $grandTotalCurrNilai += $sumCurrNilai;
                $currentRowCount++;
            endforeach; 
            ?>
            <tr class="font-bold bg-blue-200">
                <td colspan="4" class="text-center">Jumlah</td>
                <td class="text-right"><?= $grandTotalPrevHarga > 0 ? formatRp($grandTotalPrevHarga) : '-' ?></td>
                <td class="text-right"><?= $grandTotalPrevBeban > 0 ? formatRp($grandTotalPrevBeban) : '-' ?></td>
                <td class="text-right"><?= $grandTotalPrevAkum > 0 ? formatRp($grandTotalPrevAkum) : '-' ?></td>
                <td class="text-right"><?= $grandTotalPrevNilai > 0 ? formatRp($grandTotalPrevNilai) : '-' ?></td>
                <td class="text-right"><?= $grandTotalMutasi > 0 ? formatRp($grandTotalMutasi) : '-' ?></td>
                <td class="text-right"><?= $grandTotalCurrHarga > 0 ? formatRp($grandTotalCurrHarga) : '-' ?></td>
                <td class="text-right"><?= $grandTotalCurrBeban > 0 ? formatRp($grandTotalCurrBeban) : '-' ?></td>
                <td class="text-right"><?= $grandTotalCurrAkum > 0 ? formatRp($grandTotalCurrAkum) : '-' ?></td>
                <td class="text-right"><?= $grandTotalCurrNilai > 0 ? formatRp($grandTotalCurrNilai) : '-' ?></td>
            </tr>
        </tbody>
    </table>
</div>

<!-- LAMPIRAN - LAMPIRAN ASET TAKBERWUJUD -->
<div class="page-break">
    <div class="font-bold mb-2" style="font-size: 10pt;">
        <?= strtoupper($companyName) ?><br>
        DAFTAR ASET TAKBERWUJUD DAN AMORTISASI<br>
        Per 31 Desember <?= $tahunBerjalan ?><br>
        (Dalam Rupiah)
    </div>

    <table class="fin-table table-lampiran" style="font-size: 6pt; width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th class="text-center align-middle" style="width: 2%;">No</th>
                <th class="text-center align-middle" style="width: 15%;">Uraian</th>
                <th class="text-center align-middle" style="width: 6%;">Tanggal<br>Beli</th>
                <th class="text-center align-middle" style="width: 3%;">%</th>
                <th class="text-center">Harga Perolehan<br>(<?= $tahunSebelumnya ?>)</th>
                <th class="text-center">Beban<br>Amortisasi<br>(<?= $tahunSebelumnya ?>)</th>
                <th class="text-center">Akumulasi<br>Amortisasi<br>(<?= $tahunSebelumnya ?>)</th>
                <th class="text-center">Nilai Buku<br>(<?= $tahunSebelumnya ?>)</th>
                <th class="text-center align-middle" style="width: 7%;">Mutasi</th>
                <th class="text-center">Harga Perolehan<br>(<?= $tahunBerjalan ?>)</th>
                <th class="text-center">Beban<br>Amortisasi<br>(<?= $tahunBerjalan ?>)</th>
                <th class="text-center">Akumulasi<br>Amortisasi<br>(<?= $tahunBerjalan ?>)</th>
                <th class="text-center">Nilai Buku<br>(<?= $tahunBerjalan ?>)</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1; 
            $sumPrevHarga = 0; $sumPrevBeban = 0; $sumPrevAkum = 0; $sumPrevNilai = 0;
            $sumMutasi = 0;
            $sumCurrHarga = 0; $sumCurrBeban = 0; $sumCurrAkum = 0; $sumCurrNilai = 0;
            foreach ($lampiran['asetTakBerwujud'] as $item): 
                $sumPrevHarga += $item['prev']['harga'];
                $sumPrevBeban += $item['prev']['beban'];
                $sumPrevAkum += $item['prev']['akumulasi'];
                $sumPrevNilai += $item['prev']['nilai_buku'];
                $sumMutasi += $item['mutasi'];
                $sumCurrHarga += $item['curr']['harga'];
                $sumCurrBeban += $item['curr']['beban'];
                $sumCurrAkum += $item['curr']['akumulasi'];
                $sumCurrNilai += $item['curr']['nilai_buku'];
            ?>
            <tr>
                <td class="text-center"><?= $no++ ?></td>
                <td><?= $item['nama'] ?></td>
                <td class="text-center"><?= $item['tanggal'] ?></td>
                <td class="text-center"><?= $item['tarif'] ?>%</td>
                <td class="text-right"><?= $item['prev']['harga'] > 0 ? formatRp($item['prev']['harga']) : '-' ?></td>
                <td class="text-right"><?= $item['prev']['beban'] > 0 ? formatRp($item['prev']['beban']) : '-' ?></td>
                <td class="text-right"><?= $item['prev']['akumulasi'] > 0 ? formatRp($item['prev']['akumulasi']) : '-' ?></td>
                <td class="text-right"><?= $item['prev']['nilai_buku'] > 0 ? formatRp($item['prev']['nilai_buku']) : '-' ?></td>
                <td class="text-right"><?= $item['mutasi'] > 0 ? formatRp($item['mutasi']) : '-' ?></td>
                <td class="text-right"><?= $item['curr']['harga'] > 0 ? formatRp($item['curr']['harga']) : '-' ?></td>
                <td class="text-right"><?= $item['curr']['beban'] > 0 ? formatRp($item['curr']['beban']) : '-' ?></td>
                <td class="text-right"><?= $item['curr']['akumulasi'] > 0 ? formatRp($item['curr']['akumulasi']) : '-' ?></td>
                <td class="text-right"><?= $item['curr']['nilai_buku'] > 0 ? formatRp($item['curr']['nilai_buku']) : '-' ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (count($lampiran['asetTakBerwujud']) > 0): ?>
            <tr class="font-bold">
                <td></td>
                <td>Jumlah Aset TakBerwujud</td>
                <td></td>
                <td></td>
                <td class="text-right"><?= $sumPrevHarga > 0 ? formatRp($sumPrevHarga) : '-' ?></td>
                <td class="text-right"><?= $sumPrevBeban > 0 ? formatRp($sumPrevBeban) : '-' ?></td>
                <td class="text-right"><?= $sumPrevAkum > 0 ? formatRp($sumPrevAkum) : '-' ?></td>
                <td class="text-right"><?= $sumPrevNilai > 0 ? formatRp($sumPrevNilai) : '-' ?></td>
                <td class="text-right"><?= $sumMutasi > 0 ? formatRp($sumMutasi) : '-' ?></td>
                <td class="text-right"><?= $sumCurrHarga > 0 ? formatRp($sumCurrHarga) : '-' ?></td>
                <td class="text-right"><?= $sumCurrBeban > 0 ? formatRp($sumCurrBeban) : '-' ?></td>
                <td class="text-right"><?= $sumCurrAkum > 0 ? formatRp($sumCurrAkum) : '-' ?></td>
                <td class="text-right"><?= $sumCurrNilai > 0 ? formatRp($sumCurrNilai) : '-' ?></td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>


<div class="page-break">
    <div class="text-center font-bold mb-2">
        <?= strtoupper($companyName) ?><br>
        ANALISIS RASIO LAPORAN KEUANGAN<br>
        Per 31 Desember <?= $tahunBerjalan ?><br>
        (Dalam Rupiah)
    </div>
    
    <div class="mt-5">
        <p class="font-bold">I RASIO LIKUIDITAS</p>
        <table class="no-border">
            <tr>
                <td width="30%">1. Rasio Lancar / Current Ratio</td>
                <td width="5%">=</td>
                <td width="20%" class="text-center border-bottom">Aset Lancar</td>
                <td width="5%">=</td>
                <td width="20%" class="text-center border-bottom"><?= formatRp($rasio['asetLancar']) ?></td>
                <td width="20%">= <?= number_format($rasio['currentRatio'] * 100, 2) ?>%</td>
            </tr>
            <tr>
                <td></td><td></td><td class="text-center">Liabilitas Jk. Pendek</td><td></td><td class="text-center"><?= formatRp($rasio['liabilitasPendek']) ?></td><td></td>
            </tr>
        </table>
        <p style="font-size: 9pt; margin-left:20px; font-style: italic;">Current ratio <?= number_format($rasio['currentRatio'] * 100, 2) ?>%, artinya liabilitas jangka pendek dijamin oleh aset lancar.</p>

        <table class="no-border mt-2">
            <tr>
                <td width="30%">2. Rasio Kas / Cash Ratio</td>
                <td width="5%">=</td>
                <td width="20%" class="text-center border-bottom">Kas dan Setara Kas</td>
                <td width="5%">=</td>
                <td width="20%" class="text-center border-bottom"><?= formatRp($rasio['kasSetaraKas']) ?></td>
                <td width="20%">= <?= number_format($rasio['cashRatio'] * 100, 2) ?>%</td>
            </tr>
            <tr>
                <td></td><td></td><td class="text-center">Liabilitas Jk. Pendek</td><td></td><td class="text-center"><?= formatRp($rasio['liabilitasPendek']) ?></td><td></td>
            </tr>
        </table>

        <p class="font-bold mt-5">II RASIO SOLVABILITAS</p>
        <table class="no-border">
            <tr>
                <td width="30%">1. Rasio Antara Liabilitas Dengan Aset</td>
                <td width="5%">=</td>
                <td width="20%" class="text-center border-bottom">Liabilitas</td>
                <td width="5%">=</td>
                <td width="20%" class="text-center border-bottom"><?= formatRp($rasio['totalLiabilitas']) ?></td>
                <td width="20%">= <?= number_format($rasio['solvabilitasAset'] * 100, 2) ?>%</td>
            </tr>
            <tr>
                <td></td><td></td><td class="text-center">Aset</td><td></td><td class="text-center"><?= formatRp($rasio['totalAset']) ?></td><td></td>
            </tr>
        </table>

        <table class="no-border mt-2">
            <tr>
                <td width="30%">2. Rasio Antara Liabilitas Dengan Ekuitas</td>
                <td width="5%">=</td>
                <td width="20%" class="text-center border-bottom">Liabilitas</td>
                <td width="5%">=</td>
                <td width="20%" class="text-center border-bottom"><?= formatRp($rasio['totalLiabilitas']) ?></td>
                <td width="20%">= <?= number_format($rasio['solvabilitasEkuitas'] * 100, 2) ?>%</td>
            </tr>
            <tr>
                <td></td><td></td><td class="text-center">Ekuitas</td><td></td><td class="text-center"><?= formatRp($rasio['totalEkuitas']) ?></td><td></td>
            </tr>
        </table>

        <p class="font-bold mt-5">III RASIO AKTIVITAS</p>
        <table class="no-border">
            <tr>
                <td width="30%">1. Perputaran Aset</td>
                <td width="5%">=</td>
                <td width="20%" class="text-center border-bottom">Pendapatan</td>
                <td width="5%">=</td>
                <td width="20%" class="text-center border-bottom"><?= formatRp($rasio['pendapatan']) ?></td>
                <td width="20%">= <?= number_format($rasio['perputaranAset'], 2) ?> kali</td>
            </tr>
            <tr>
                <td></td><td></td><td class="text-center">Aset</td><td></td><td class="text-center"><?= formatRp($rasio['totalAset']) ?></td><td></td>
            </tr>
        </table>

        <p class="font-bold mt-5">IV RASIO PROFITABILITAS</p>
        <table class="no-border">
            <tr>
                <td width="30%">1. Net Profit Margin</td>
                <td width="5%">=</td>
                <td width="20%" class="text-center border-bottom">Laba (Rugi) Berjalan</td>
                <td width="5%">=</td>
                <td width="20%" class="text-center border-bottom"><?= formatRp($rasio['labaBerjalan']) ?></td>
                <td width="20%">= <?= number_format($rasio['netProfitMargin'], 4) ?></td>
            </tr>
            <tr>
                <td></td><td></td><td class="text-center">Pendapatan</td><td></td><td class="text-center"><?= formatRp($rasio['pendapatan']) ?></td><td></td>
            </tr>
        </table>
        
        <table class="no-border mt-2">
            <tr>
                <td width="30%">2. Return on Asset (ROA)</td>
                <td width="5%">=</td>
                <td width="20%" class="text-center border-bottom">Laba (Rugi) Berjalan</td>
                <td width="5%">=</td>
                <td width="20%" class="text-center border-bottom"><?= formatRp($rasio['labaBerjalan']) ?></td>
                <td width="20%">= <?= number_format($rasio['roa'], 2) ?></td>
            </tr>
            <tr>
                <td></td><td></td><td class="text-center">Aset</td><td></td><td class="text-center"><?= formatRp($rasio['totalAset']) ?></td><td></td>
            </tr>
        </table>
    </div>
</div>

<!-- Z-SCORE -->
<div class="page-break">
    <div class="text-center font-bold mb-2">
        <?= strtoupper($companyName) ?><br>
        ANALISIS RASIO KESINAMBUNGAN Z-SCORE MODEL<br>
        Per 31 Desember <?= $tahunBerjalan ?><br>
        (Dalam Rupiah)
    </div>

    <div class="mt-5">
        <p class="font-bold">RASIO KESINAMBUNGAN Z-Score Model</p>
        <p>Model Altman ini dinyatakan dengan persamaan sebagai berikut:</p>
        <div style="border: 2px solid #000; padding: 10px; font-weight: bold; width: 60%; margin: 10px 0;">
            Z = 6,56 X1 + 3,26 X2 + 6,72 X3 + 1,05 X4
        </div>
        <p><b>Keterangan:</b><br>
        X1 = Modal Kerja Bersih / Total Aset<br>
        X2 = Saldo Laba / Total Aset<br>
        X3 = EBIT / Total Aset<br>
        X4 = Modal / Total Kewajiban
        </p>
        <ul style="list-style-type: circle;">
            <li>Jika hasilnya Z < 1,23 mengindikasikan kelangsungan usaha dalam prediksi <b>Pailit</b></li>
            <li>Jika hasilnya 1,23 < Z < 2,90 mengindikasikan kelangsungan usaha dalam prediksi <b>Grey Area</b></li>
            <li>Jika hasilnya Z > 2,90 mengindikasikan kelangsungan usaha dalam prediksi <b>Tidak Pailit</b></li>
        </ul>

        <p class="font-bold mt-5">Perhitungan Z-Score tahun <?= $tahunBerjalan ?> adalah sebagai berikut:</p>
        
        <table class="no-border" style="width: 80%;">
            <tr>
                <td width="10%">6,56</td><td width="5%">x</td>
                <td width="20%" class="text-center border-bottom"><?= formatRp($zScore['modalKerjaBersih']) ?></td>
                <td width="5%">=</td>
                <td width="20%" class="text-right"><?= number_format($zScore['x1'] * 6.56, 2) ?></td>
            </tr>
            <tr>
                <td></td><td></td><td class="text-center"><?= formatRp($zScore['totalAset']) ?></td><td></td><td></td>
            </tr>
            <tr><td colspan="5" class="text-right"><b>+</b></td></tr>
            
            <tr>
                <td>3,26</td><td>x</td>
                <td class="text-center border-bottom"><?= formatRp($zScore['saldoLaba']) ?></td>
                <td>=</td>
                <td class="text-right"><?= number_format($zScore['x2'] * 3.26, 2) ?></td>
            </tr>
            <tr>
                <td></td><td></td><td class="text-center"><?= formatRp($zScore['totalAset']) ?></td><td></td><td></td>
            </tr>
            <tr><td colspan="5" class="text-right"><b>+</b></td></tr>
            
            <tr>
                <td>6,72</td><td>x</td>
                <td class="text-center border-bottom"><?= formatRp($zScore['ebit']) ?></td>
                <td>=</td>
                <td class="text-right"><?= number_format($zScore['x3'] * 6.72, 2) ?></td>
            </tr>
            <tr>
                <td></td><td></td><td class="text-center"><?= formatRp($zScore['totalAset']) ?></td><td></td><td></td>
            </tr>
            <tr><td colspan="5" class="text-right"><b>+</b></td></tr>
            
            <tr>
                <td>1,05</td><td>x</td>
                <td class="text-center border-bottom"><?= formatRp($zScore['modalEkuitas']) ?></td>
                <td>=</td>
                <td class="text-right border-bottom"><?= number_format($zScore['x4'] * 1.05, 2) ?></td>
            </tr>
            <tr>
                <td></td><td></td><td class="text-center"><?= formatRp($zScore['totalKewajiban']) ?></td><td></td>
                <td class="text-right font-bold"><?= number_format($zScore['z'], 2) ?></td>
            </tr>
        </table>
        
        <p class="mt-5">Dari hasil Z-score diatas tahun <?= $tahunBerjalan ?> sebesar <b><?= number_format($zScore['z'], 2) ?></b> dimana Z-score mengindikasikan perusahaan dalam keadaan <b><?= $zScore['prediksi'] ?></b>.</p>
    </div>
</div>

</body>
</html>

