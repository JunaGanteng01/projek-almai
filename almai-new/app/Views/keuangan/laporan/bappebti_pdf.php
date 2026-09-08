<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan Bappebti <?= $tahunBerjalan ?></title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 11pt; color: #000; line-height: 1.4; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        .page-break { page-break-after: always; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 6px 4px; vertical-align: middle; }
        
        .header { margin-bottom: 30px; }
        .header h1 { font-size: 16pt; margin: 0 0 5px 0; letter-spacing: 1px; }
        .header h2 { font-size: 14pt; margin: 0 0 5px 0; }
        .header p { margin: 0; font-size: 11pt; font-style: italic; }
        
        .indent-1 { padding-left: 20px; }
        .indent-2 { padding-left: 40px; }
        
        .report-table th { text-align: center; border-top: 2px solid #000; border-bottom: 2px solid #000; padding: 10px 4px; }
        .subtotal-row td { border-top: 1px solid #000; font-weight: bold; padding-top: 10px; padding-bottom: 10px; }
        .total-row td { border-top: 2px solid #000; border-bottom: 4px double #000; font-weight: bold; padding-top: 10px; padding-bottom: 10px; }
        .table-title { font-weight: bold; padding-top: 15px; padding-bottom: 5px; }
        .section-title { font-weight: bold; margin-top: 20px; margin-bottom: 10px; }
        .text-justify { text-align: justify; }
    </style>
</head>
<body>

    <style>
        .cover-box {
            border: 2px solid #000;
            padding: 50px 20px;
            margin: 15% auto 0 auto;
            width: 75%;
            text-align: center;
        }
        .draft-box {
            border: 1px solid #000;
            width: 250px;
            float: right;
            margin-top: 100px;
            text-align: center;
            font-size: 9pt;
            color: #4a235a; /* purple-ish tone from screenshot */
        }
        .draft-box table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0;
        }
        .draft-box th, .draft-box td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            font-weight: normal;
        }
        .draft-note {
            text-align: right;
            font-size: 8pt;
            color: #4a235a;
            margin-top: 2px;
            float: right;
            width: 100%;
        }
    </style>

    <!-- COVER PAGE -->
    <div class="cover-box">
        <h1 class="uppercase font-bold" style="font-size: 20pt; margin-bottom: 20px;">PT ALMA INDONESIA RAYA</h1>
        <h2 class="uppercase font-bold" style="font-size: 16pt; margin-bottom: 20px;">LAPORAN KEUANGAN AUDITAN</h2>
        <p class="font-bold" style="font-size: 14pt; margin-top: 10px;">31 DESEMBER <?= $tahunBerjalan ?></p>
    </div>

    <div style="clear: both;"></div>

    <div class="draft-box">
        <table>
            <tr><th colspan="2" class="font-bold">DRAFT<br>LAPORAN KEUANGAN</th></tr>
            <tr><td colspan="2">SETUJU / TIDAK SETUJU *</td></tr>
            <tr>
                <td style="width: 40%; text-align: left;">TANGGAL</td>
                <td></td>
            </tr>
            <tr>
                <td style="text-align: left; vertical-align: top;">JABATAN<br>TANDA<br>TANGAN</td>
                <td></td>
            </tr>
            <tr><td colspan="2">DILARANG DIGANDAKAN</td></tr>
        </table>
    </div>
    <div class="draft-note">*coret yang tidak perlu</div>
    
    <div class="page-break"></div>

    <!-- DAFTAR ISI -->
    <div class="header text-center" style="margin-bottom: 20px;">
        <h1 class="font-bold" style="font-size: 14pt;">DAFTAR ISI</h1>
    </div>

    <table style="width: 100%; border: none; margin-bottom: 20px;">
        <tr>
            <td style="border: none; font-weight: bold; width: 80%; padding-bottom: 15px;">ISI</td>
            <td style="border: none; font-weight: bold; text-align: right; padding-bottom: 15px;">HALAMAN</td>
        </tr>
        <tr><td colspan="2" style="border: none; font-weight: bold;">SURAT PERNYATAAN DIREKSI</td></tr>
        
        <tr><td colspan="2" style="border: none; padding-top: 10px;">DAFTAR ISI</td></tr>
        <tr>
            <td style="border: none;">NERACA ......................................................................................................................................</td>
            <td style="border: none; text-align: right;">i &ndash; ii</td>
        </tr>
        <tr>
            <td style="border: none;">LAPORAN LABA (RUGI) .............................................................................................................</td>
            <td style="border: none; text-align: right;">iii</td>
        </tr>
        <tr>
            <td style="border: none;">LAPORAN PERUBAHAN EKUITAS ...........................................................................................</td>
            <td style="border: none; text-align: right;">iv</td>
        </tr>
        <tr>
            <td style="border: none;">LAPORAN ARUS KAS ................................................................................................................</td>
            <td style="border: none; text-align: right;">v</td>
        </tr>
        <tr>
            <td style="border: none;">CATATAN ATAS LAPORAN KEUANGAN .................................................................................</td>
            <td style="border: none; text-align: right;">1 &ndash; 18</td>
        </tr>
        
        <tr><td colspan="2" style="border: none; padding-top: 15px; font-weight: bold;">LAPORAN AUDITOR INDEPENDEN</td></tr>
        
        <tr><td colspan="2" style="border: none; padding-top: 15px; padding-bottom: 5px;">LAMPIRAN - LAMPIRAN</td></tr>
        <tr><td colspan="2" style="border: none;">DAFTAR ASET TETAP DAN PENYUSUTANNYA</td></tr>
        <tr><td colspan="2" style="border: none;">DAFTAR ASET TAKBERWUJUD DAN AMORTISASI</td></tr>
        <tr><td colspan="2" style="border: none;">ANALISIS RASIO LAPORAN KEUANGAN</td></tr>
        <tr><td colspan="2" style="border: none;">TENTATIVE DRAFT</td></tr>
    </table>

    <div class="page-break"></div>

    <!-- 1. NERACA -->
    <div class="header text-center">
        <h1 class="uppercase font-bold">PT ALMA INDONESIA RAYA</h1>
        <h2 class="uppercase font-bold">Laporan Posisi Keuangan (Neraca)</h2>
        <p>Per 31 Desember <?= $tahunBerjalan ?> dan <?= $tahunSebelumnya ?></p>
        <p>(Disajikan dalam Rupiah, kecuali dinyatakan lain)</p>
    </div>

    <table class="report-table">
        <tr>
            <th class="text-left">A S E T</th>
            <th class="text-right" style="width: 25%;"><?= $tahunBerjalan ?></th>
            <th class="text-right" style="width: 25%;"><?= $tahunSebelumnya ?></th>
        </tr>
        
        <tr><td colspan="3" class="table-title">ASET LANCAR</td></tr>
        <?php foreach ($neraca['berjalan']['aset']['aset_lancar']['detail'] as $idx => $row): ?>
            <tr>
                <td class="indent-1"><?= esc($row['nama_akun']) ?></td>
                <td class="text-right"><?= number_format($row['saldo'], 0, ',', '.') ?></td>
                <td class="text-right"><?= number_format($neraca['sebelumnya']['aset']['aset_lancar']['detail'][$idx]['saldo'] ?? 0, 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        <tr class="subtotal-row">
            <td class="text-right">Jumlah Aset Lancar</td>
            <td class="text-right"><?= number_format($neraca['berjalan']['aset']['aset_lancar']['total'], 0, ',', '.') ?></td>
            <td class="text-right"><?= number_format($neraca['sebelumnya']['aset']['aset_lancar']['total'], 0, ',', '.') ?></td>
        </tr>

        <tr><td colspan="3" class="table-title">ASET TIDAK LANCAR</td></tr>
        <?php foreach ($neraca['berjalan']['aset']['aset_tidak_lancar']['detail'] as $idx => $row): ?>
            <tr>
                <td class="indent-1"><?= esc($row['nama_akun']) ?></td>
                <td class="text-right"><?= number_format($row['saldo'], 0, ',', '.') ?></td>
                <td class="text-right"><?= number_format($neraca['sebelumnya']['aset']['aset_tidak_lancar']['detail'][$idx]['saldo'] ?? 0, 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        <tr class="subtotal-row">
            <td class="text-right">Jumlah Aset Tidak Lancar</td>
            <td class="text-right"><?= number_format($neraca['berjalan']['aset']['aset_tidak_lancar']['total'], 0, ',', '.') ?></td>
            <td class="text-right"><?= number_format($neraca['sebelumnya']['aset']['aset_tidak_lancar']['total'], 0, ',', '.') ?></td>
        </tr>
        
        <tr class="total-row">
            <td class="uppercase">Total Aset</td>
            <td class="text-right"><?= number_format($neraca['berjalan']['aset']['total_aset'], 0, ',', '.') ?></td>
            <td class="text-right"><?= number_format($neraca['sebelumnya']['aset']['total_aset'], 0, ',', '.') ?></td>
        </tr>

        <tr>
            <th class="text-left" style="border-top: none;">LIABILITAS DAN EKUITAS</th>
            <th style="border-top: none;"></th>
            <th style="border-top: none;"></th>
        </tr>
        
        <tr><td colspan="3" class="table-title">LIABILITAS JANGKA PENDEK</td></tr>
        <?php foreach ($neraca['berjalan']['liabilitas']['liabilitas_jangka_pendek']['detail'] as $idx => $row): ?>
            <tr>
                <td class="indent-1"><?= esc($row['nama_akun']) ?></td>
                <td class="text-right"><?= number_format($row['saldo'], 0, ',', '.') ?></td>
                <td class="text-right"><?= number_format($neraca['sebelumnya']['liabilitas']['liabilitas_jangka_pendek']['detail'][$idx]['saldo'] ?? 0, 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        <tr class="subtotal-row">
            <td class="text-right">Jumlah Liabilitas Jangka Pendek</td>
            <td class="text-right"><?= number_format($neraca['berjalan']['liabilitas']['liabilitas_jangka_pendek']['total'], 0, ',', '.') ?></td>
            <td class="text-right"><?= number_format($neraca['sebelumnya']['liabilitas']['liabilitas_jangka_pendek']['total'], 0, ',', '.') ?></td>
        </tr>

        <tr><td colspan="3" class="table-title">LIABILITAS JANGKA PANJANG</td></tr>
        <?php foreach ($neraca['berjalan']['liabilitas']['liabilitas_jangka_panjang']['detail'] as $idx => $row): ?>
            <tr>
                <td class="indent-1"><?= esc($row['nama_akun']) ?></td>
                <td class="text-right"><?= number_format($row['saldo'], 0, ',', '.') ?></td>
                <td class="text-right"><?= number_format($neraca['sebelumnya']['liabilitas']['liabilitas_jangka_panjang']['detail'][$idx]['saldo'] ?? 0, 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        <tr class="subtotal-row">
            <td class="text-right">Jumlah Liabilitas Jangka Panjang</td>
            <td class="text-right"><?= number_format($neraca['berjalan']['liabilitas']['liabilitas_jangka_panjang']['total'], 0, ',', '.') ?></td>
            <td class="text-right"><?= number_format($neraca['sebelumnya']['liabilitas']['liabilitas_jangka_panjang']['total'], 0, ',', '.') ?></td>
        </tr>

        <tr><td colspan="3" class="table-title">EKUITAS</td></tr>
        <?php foreach ($neraca['berjalan']['ekuitas']['detail'] as $idx => $row): ?>
            <tr>
                <td class="indent-1"><?= esc($row['nama_akun']) ?></td>
                <td class="text-right"><?= number_format($row['saldo'], 0, ',', '.') ?></td>
                <td class="text-right"><?= number_format($neraca['sebelumnya']['ekuitas']['detail'][$idx]['saldo'] ?? 0, 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td class="indent-1">Laba / (Rugi) Tahun Berjalan</td>
            <td class="text-right"><?= number_format($neraca['berjalan']['ekuitas']['laba_rugi_tahun_berjalan'], 0, ',', '.') ?></td>
            <td class="text-right"><?= number_format($neraca['sebelumnya']['ekuitas']['laba_rugi_tahun_berjalan'], 0, ',', '.') ?></td>
        </tr>
        <tr class="subtotal-row">
            <td class="text-right">Jumlah Ekuitas</td>
            <td class="text-right"><?= number_format($neraca['berjalan']['ekuitas']['total_ekuitas_with_profit'], 0, ',', '.') ?></td>
            <td class="text-right"><?= number_format($neraca['sebelumnya']['ekuitas']['total_ekuitas_with_profit'], 0, ',', '.') ?></td>
        </tr>
        
        <tr class="total-row">
            <td class="uppercase">Total Liabilitas dan Ekuitas</td>
            <td class="text-right"><?= number_format($neraca['berjalan']['total_liabilitas_ekuitas'], 0, ',', '.') ?></td>
            <td class="text-right"><?= number_format($neraca['sebelumnya']['total_liabilitas_ekuitas'], 0, ',', '.') ?></td>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- 2. LABA RUGI -->
    <div class="header text-center">
        <h1 class="uppercase font-bold">PT ALMA INDONESIA RAYA</h1>
        <h2 class="uppercase font-bold">Laporan Laba (Rugi) Komprehensif</h2>
        <p>Untuk Tahun yang Berakhir Pada 31 Desember <?= $tahunBerjalan ?> dan <?= $tahunSebelumnya ?></p>
        <p>(Disajikan dalam Rupiah, kecuali dinyatakan lain)</p>
    </div>

    <table class="report-table">
        <tr>
            <th class="text-left">KETERANGAN</th>
            <th class="text-right" style="width: 25%;"><?= $tahunBerjalan ?></th>
            <th class="text-right" style="width: 25%;"><?= $tahunSebelumnya ?></th>
        </tr>
        
        <tr><td colspan="3" class="table-title">PENDAPATAN USAHA</td></tr>
        <?php foreach ($labaRugi['berjalan']['pendapatan']['pendapatan_usaha']['detail'] as $idx => $row): ?>
            <tr>
                <td class="indent-1"><?= esc($row['nama_akun']) ?></td>
                <td class="text-right"><?= number_format($row['saldo'], 0, ',', '.') ?></td>
                <td class="text-right"><?= number_format($labaRugi['sebelumnya']['pendapatan']['pendapatan_usaha']['detail'][$idx]['saldo'] ?? 0, 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        <tr class="subtotal-row">
            <td class="text-right">Jumlah Pendapatan Usaha</td>
            <td class="text-right"><?= number_format($labaRugi['berjalan']['pendapatan']['pendapatan_usaha']['total'], 0, ',', '.') ?></td>
            <td class="text-right"><?= number_format($labaRugi['sebelumnya']['pendapatan']['pendapatan_usaha']['total'], 0, ',', '.') ?></td>
        </tr>

        <tr><td colspan="3" class="table-title">BEBAN POKOK PENJUALAN</td></tr>
        <?php foreach ($labaRugi['berjalan']['hpp']['detail'] as $idx => $row): ?>
            <tr>
                <td class="indent-1"><?= esc($row['nama_akun']) ?></td>
                <td class="text-right">(<?= number_format($row['saldo'], 0, ',', '.') ?>)</td>
                <td class="text-right">(<?= number_format($labaRugi['sebelumnya']['hpp']['detail'][$idx]['saldo'] ?? 0, 0, ',', '.') ?>)</td>
            </tr>
        <?php endforeach; ?>
        <tr class="subtotal-row">
            <td class="text-right">Jumlah Beban Pokok Penjualan</td>
            <td class="text-right">(<?= number_format($labaRugi['berjalan']['hpp']['total'], 0, ',', '.') ?>)</td>
            <td class="text-right">(<?= number_format($labaRugi['sebelumnya']['hpp']['total'], 0, ',', '.') ?>)</td>
        </tr>

        <tr class="total-row" style="border-bottom: 2px solid #000;">
            <td class="uppercase">Laba (Rugi) Kotor</td>
            <td class="text-right"><?= number_format($labaRugi['berjalan']['laba_kotor'], 0, ',', '.') ?></td>
            <td class="text-right"><?= number_format($labaRugi['sebelumnya']['laba_kotor'], 0, ',', '.') ?></td>
        </tr>

        <tr><td colspan="3" class="table-title">BEBAN OPERASIONAL</td></tr>
        <?php foreach ($labaRugi['berjalan']['beban']['beban_operasional']['detail'] as $idx => $row): ?>
            <tr>
                <td class="indent-1"><?= esc($row['nama_akun']) ?></td>
                <td class="text-right">(<?= number_format($row['saldo'], 0, ',', '.') ?>)</td>
                <td class="text-right">(<?= number_format($labaRugi['sebelumnya']['beban']['beban_operasional']['detail'][$idx]['saldo'] ?? 0, 0, ',', '.') ?>)</td>
            </tr>
        <?php endforeach; ?>
        <tr class="subtotal-row">
            <td class="text-right">Jumlah Beban Operasional</td>
            <td class="text-right">(<?= number_format($labaRugi['berjalan']['beban']['beban_operasional']['total'], 0, ',', '.') ?>)</td>
            <td class="text-right">(<?= number_format($labaRugi['sebelumnya']['beban']['beban_operasional']['total'], 0, ',', '.') ?>)</td>
        </tr>

        <tr class="total-row" style="border-bottom: 2px solid #000;">
            <td class="uppercase">Laba (Rugi) Operasional</td>
            <td class="text-right"><?= number_format($labaRugi['berjalan']['laba_rugi_operasional'], 0, ',', '.') ?></td>
            <td class="text-right"><?= number_format($labaRugi['sebelumnya']['laba_rugi_operasional'], 0, ',', '.') ?></td>
        </tr>

        <tr><td colspan="3" class="table-title">PENDAPATAN (BEBAN) LAIN-LAIN</td></tr>
        <?php foreach ($labaRugi['berjalan']['pendapatan']['pendapatan_lainnya']['detail'] as $idx => $row): ?>
            <tr>
                <td class="indent-1"><?= esc($row['nama_akun']) ?></td>
                <td class="text-right"><?= number_format($row['saldo'], 0, ',', '.') ?></td>
                <td class="text-right"><?= number_format($labaRugi['sebelumnya']['pendapatan']['pendapatan_lainnya']['detail'][$idx]['saldo'] ?? 0, 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        <?php foreach ($labaRugi['berjalan']['beban']['beban_lainnya']['detail'] as $idx => $row): ?>
            <tr>
                <td class="indent-1"><?= esc($row['nama_akun']) ?></td>
                <td class="text-right">(<?= number_format($row['saldo'], 0, ',', '.') ?>)</td>
                <td class="text-right">(<?= number_format($labaRugi['sebelumnya']['beban']['beban_lainnya']['detail'][$idx]['saldo'] ?? 0, 0, ',', '.') ?>)</td>
            </tr>
        <?php endforeach; ?>
        <tr class="subtotal-row">
            <td class="text-right">Jumlah Pendapatan (Beban) Lain-lain</td>
            <td class="text-right"><?= number_format($labaRugi['berjalan']['pendapatan']['pendapatan_lainnya']['total'] - $labaRugi['berjalan']['beban']['beban_lainnya']['total'], 0, ',', '.') ?></td>
            <td class="text-right"><?= number_format($labaRugi['sebelumnya']['pendapatan']['pendapatan_lainnya']['total'] - $labaRugi['sebelumnya']['beban']['beban_lainnya']['total'], 0, ',', '.') ?></td>
        </tr>

        <tr class="total-row">
            <td class="uppercase">Laba (Rugi) Bersih</td>
            <td class="text-right"><?= number_format($labaRugi['berjalan']['laba_rugi_tahun_berjalan'], 0, ',', '.') ?></td>
            <td class="text-right"><?= number_format($labaRugi['sebelumnya']['laba_rugi_tahun_berjalan'], 0, ',', '.') ?></td>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- 3. PERUBAHAN EKUITAS -->
    <div class="header text-center">
        <h1 class="uppercase font-bold">PT ALMA INDONESIA RAYA</h1>
        <h2 class="uppercase font-bold">Laporan Perubahan Ekuitas</h2>
        <p>Untuk Tahun yang Berakhir Pada 31 Desember <?= $tahunBerjalan ?> dan <?= $tahunSebelumnya ?></p>
        <p>(Disajikan dalam Rupiah, kecuali dinyatakan lain)</p>
    </div>

    <table class="report-table">
        <tr>
            <th class="text-left">KETERANGAN</th>
            <th class="text-right" style="width: 25%;"><?= $tahunBerjalan ?></th>
            <th class="text-right" style="width: 25%;"><?= $tahunSebelumnya ?></th>
        </tr>
        
        <tr>
            <td>Ekuitas Awal Periode</td>
            <td class="text-right"><?= number_format($perubahanEkuitas['berjalan']['ekuitas_awal_periode'], 0, ',', '.') ?></td>
            <td class="text-right"><?= number_format($perubahanEkuitas['sebelumnya']['ekuitas_awal_periode'], 0, ',', '.') ?></td>
        </tr>
        <tr>
            <td>Tambahan Modal</td>
            <td class="text-right"><?= number_format($perubahanEkuitas['berjalan']['tambahan_modal'], 0, ',', '.') ?></td>
            <td class="text-right"><?= number_format($perubahanEkuitas['sebelumnya']['tambahan_modal'], 0, ',', '.') ?></td>
        </tr>
        <tr>
            <td>Dividen</td>
            <td class="text-right">(<?= number_format($perubahanEkuitas['berjalan']['dividen'], 0, ',', '.') ?>)</td>
            <td class="text-right">(<?= number_format($perubahanEkuitas['sebelumnya']['dividen'], 0, ',', '.') ?>)</td>
        </tr>
        <tr>
            <td>Laba (Rugi) Tahun Berjalan</td>
            <td class="text-right"><?= number_format($perubahanEkuitas['berjalan']['laba_rugi_tahun_berjalan'], 0, ',', '.') ?></td>
            <td class="text-right"><?= number_format($perubahanEkuitas['sebelumnya']['laba_rugi_tahun_berjalan'], 0, ',', '.') ?></td>
        </tr>
        
        <tr class="total-row">
            <td class="uppercase">Saldo Ekuitas Akhir</td>
            <td class="text-right"><?= number_format($perubahanEkuitas['berjalan']['ekuitas_akhir_periode'], 0, ',', '.') ?></td>
            <td class="text-right"><?= number_format($perubahanEkuitas['sebelumnya']['ekuitas_akhir_periode'], 0, ',', '.') ?></td>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- 4. ARUS KAS -->
    <div class="header text-center">
        <h1 class="uppercase font-bold">PT ALMA INDONESIA RAYA</h1>
        <h2 class="uppercase font-bold">Laporan Arus Kas</h2>
        <p>Untuk Tahun yang Berakhir Pada 31 Desember <?= $tahunBerjalan ?> dan <?= $tahunSebelumnya ?></p>
        <p>(Disajikan dalam Rupiah, kecuali dinyatakan lain)</p>
    </div>

    <table class="report-table">
        <tr>
            <th class="text-left">KETERANGAN</th>
            <th class="text-right" style="width: 25%;"><?= $tahunBerjalan ?></th>
            <th class="text-right" style="width: 25%;"><?= $tahunSebelumnya ?></th>
        </tr>

        <tr><td colspan="3" class="table-title">ARUS KAS DARI AKTIVITAS OPERASI</td></tr>
        <tr>
            <td class="indent-1">Laba (Rugi) Tahun Berjalan</td>
            <td class="text-right"><?= number_format($arusKas['berjalan']['aktivitas_operasi']['laba_rugi_tahun_berjalan'], 0, ',', '.') ?></td>
            <td class="text-right"><?= number_format($arusKas['sebelumnya']['aktivitas_operasi']['laba_rugi_tahun_berjalan'], 0, ',', '.') ?></td>
        </tr>
        <?php foreach ($arusKas['berjalan']['aktivitas_operasi']['penyusutan_amortisasi']['detail'] as $idx => $row): ?>
            <tr>
                <td class="indent-1"><?= esc($row['nama_akun']) ?></td>
                <td class="text-right"><?= number_format($row['saldo'], 0, ',', '.') ?></td>
                <td class="text-right"><?= number_format($arusKas['sebelumnya']['aktivitas_operasi']['penyusutan_amortisasi']['detail'][$idx]['saldo'] ?? 0, 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        <tr class="subtotal-row">
            <td class="text-right">Laba Operasi Sebelum Perubahan Modal Kerja</td>
            <td class="text-right"><?= number_format($arusKas['berjalan']['aktivitas_operasi']['laba_operasi_sebelum_perubahan_mk'], 0, ',', '.') ?></td>
            <td class="text-right"><?= number_format($arusKas['sebelumnya']['aktivitas_operasi']['laba_operasi_sebelum_perubahan_mk'], 0, ',', '.') ?></td>
        </tr>
        <?php foreach ($arusKas['berjalan']['aktivitas_operasi']['perubahan_modal_kerja']['aset_lancar_non_kas'] as $idx => $row): ?>
            <tr>
                <td class="indent-1"><?= esc($row['nama_akun']) ?></td>
                <td class="text-right"><?= number_format(-$row['saldo'], 0, ',', '.') ?></td>
                <td class="text-right"><?= number_format(-($arusKas['sebelumnya']['aktivitas_operasi']['perubahan_modal_kerja']['aset_lancar_non_kas'][$idx]['saldo'] ?? 0), 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        <?php foreach ($arusKas['berjalan']['aktivitas_operasi']['perubahan_modal_kerja']['liabilitas_pendek'] as $idx => $row): ?>
            <tr>
                <td class="indent-1"><?= esc($row['nama_akun']) ?></td>
                <td class="text-right"><?= number_format($row['saldo'], 0, ',', '.') ?></td>
                <td class="text-right"><?= number_format($arusKas['sebelumnya']['aktivitas_operasi']['perubahan_modal_kerja']['liabilitas_pendek'][$idx]['saldo'] ?? 0, 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        <tr class="subtotal-row">
            <td class="text-right">Kas Bersih Aktivitas Operasi</td>
            <td class="text-right"><?= number_format($arusKas['berjalan']['aktivitas_operasi']['arus_kas_operasi'], 0, ',', '.') ?></td>
            <td class="text-right"><?= number_format($arusKas['sebelumnya']['aktivitas_operasi']['arus_kas_operasi'], 0, ',', '.') ?></td>
        </tr>

        <tr><td colspan="3" class="table-title">ARUS KAS DARI AKTIVITAS INVESTASI</td></tr>
        <?php foreach ($arusKas['berjalan']['aktivitas_investasi']['detail'] as $idx => $row): ?>
            <tr>
                <td class="indent-1"><?= esc($row['nama_akun']) ?></td>
                <td class="text-right"><?= number_format(-$row['saldo'], 0, ',', '.') ?></td>
                <td class="text-right"><?= number_format(-($arusKas['sebelumnya']['aktivitas_investasi']['detail'][$idx]['saldo'] ?? 0), 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        <tr class="subtotal-row">
            <td class="text-right">Kas Bersih Aktivitas Investasi</td>
            <td class="text-right"><?= number_format($arusKas['berjalan']['aktivitas_investasi']['arus_kas_investasi'], 0, ',', '.') ?></td>
            <td class="text-right"><?= number_format($arusKas['sebelumnya']['aktivitas_investasi']['arus_kas_investasi'], 0, ',', '.') ?></td>
        </tr>

        <tr><td colspan="3" class="table-title">ARUS KAS DARI AKTIVITAS PENDANAAN</td></tr>
        <?php foreach ($arusKas['berjalan']['aktivitas_pendanaan']['detail_liabilitas_panjang'] as $idx => $row): ?>
            <tr>
                <td class="indent-1"><?= esc($row['nama_akun']) ?></td>
                <td class="text-right"><?= number_format($row['saldo'], 0, ',', '.') ?></td>
                <td class="text-right"><?= number_format($arusKas['sebelumnya']['aktivitas_pendanaan']['detail_liabilitas_panjang'][$idx]['saldo'] ?? 0, 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        <?php foreach ($arusKas['berjalan']['aktivitas_pendanaan']['detail_ekuitas'] as $idx => $row): ?>
            <?php if($row['nama_akun'] === 'Laba (Rugi) Tahun Berjalan') continue; ?>
            <tr>
                <td class="indent-1"><?= esc($row['nama_akun']) ?></td>
                <td class="text-right"><?= number_format($row['saldo'], 0, ',', '.') ?></td>
                <td class="text-right"><?= number_format($arusKas['sebelumnya']['aktivitas_pendanaan']['detail_ekuitas'][$idx]['saldo'] ?? 0, 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        <tr class="subtotal-row">
            <td class="text-right">Kas Bersih Aktivitas Pendanaan</td>
            <td class="text-right"><?= number_format($arusKas['berjalan']['aktivitas_pendanaan']['arus_kas_pendanaan'], 0, ',', '.') ?></td>
            <td class="text-right"><?= number_format($arusKas['sebelumnya']['aktivitas_pendanaan']['arus_kas_pendanaan'], 0, ',', '.') ?></td>
        </tr>

        <tr class="total-row" style="border-bottom: none;">
            <td class="uppercase">Kenaikan (Penurunan) Kas Bersih</td>
            <td class="text-right"><?= number_format($arusKas['berjalan']['perubahan_kas_bersih'], 0, ',', '.') ?></td>
            <td class="text-right"><?= number_format($arusKas['sebelumnya']['perubahan_kas_bersih'], 0, ',', '.') ?></td>
        </tr>
        <tr>
            <td class="indent-1">Kas dan Setara Kas Awal Periode</td>
            <td class="text-right"><?= number_format($arusKas['berjalan']['kas_awal_periode'], 0, ',', '.') ?></td>
            <td class="text-right"><?= number_format($arusKas['sebelumnya']['kas_awal_periode'], 0, ',', '.') ?></td>
        </tr>
        <tr class="total-row">
            <td class="uppercase">Kas dan Setara Kas Akhir Periode</td>
            <td class="text-right"><?= number_format($arusKas['berjalan']['kas_akhir_periode'], 0, ',', '.') ?></td>
            <td class="text-right"><?= number_format($arusKas['sebelumnya']['kas_akhir_periode'], 0, ',', '.') ?></td>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- 5. LAMPIRAN BAPPEBTI (RASIO & Z-SCORE) -->
    <div class="header text-center">
        <h1 class="uppercase font-bold">PT ALMA INDONESIA RAYA</h1>
        <h2 class="uppercase font-bold">Lampiran Khusus Bappebti</h2>
        <p>Untuk Tahun yang Berakhir Pada 31 Desember <?= $tahunBerjalan ?></p>
    </div>

    <div class="section-title">A. ANALISIS RASIO KEUANGAN</div>
    <table class="report-table">
        <thead>
            <tr>
                <th class="text-left">Indikator</th>
                <th class="text-right" style="width: 30%;">Nilai / Rasio</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Current Ratio (Rasio Lancar)</td>
                <td class="text-right"><?= number_format($rasio['currentRatio'] ?? 0, 2, ',', '.') ?> x</td>
            </tr>
            <tr>
                <td>Cash Ratio</td>
                <td class="text-right"><?= number_format($rasio['cashRatio'] ?? 0, 2, ',', '.') ?> x</td>
            </tr>
            <tr>
                <td>Solvabilitas Aset (Debt to Asset)</td>
                <td class="text-right"><?= number_format(($rasio['solvabilitasAset'] ?? 0) * 100, 2, ',', '.') ?> %</td>
            </tr>
            <tr>
                <td>Solvabilitas Ekuitas (Debt to Equity)</td>
                <td class="text-right"><?= number_format(($rasio['solvabilitasEkuitas'] ?? 0) * 100, 2, ',', '.') ?> %</td>
            </tr>
            <tr>
                <td>Return on Asset (ROA)</td>
                <td class="text-right"><?= number_format(($rasio['roa'] ?? 0) * 100, 2, ',', '.') ?> %</td>
            </tr>
            <tr>
                <td>Return on Equity (ROE)</td>
                <td class="text-right"><?= number_format(($rasio['roe'] ?? 0) * 100, 2, ',', '.') ?> %</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title" style="margin-top: 40px;">B. ANALISIS KEBANGKRUTAN (Z-SCORE)</div>
    <table class="report-table">
        <tbody>
            <tr>
                <td width="70%">Z-Score Value</td>
                <td class="text-right font-bold"><?= number_format($zScore['z'] ?? 0, 2, ',', '.') ?></td>
            </tr>
            <tr>
                <td>Status Prediksi</td>
                <td class="text-right font-bold"><?= esc($zScore['prediksi'] ?? '-') ?></td>
            </tr>
        </tbody>
    </table>

</body>
</html>
