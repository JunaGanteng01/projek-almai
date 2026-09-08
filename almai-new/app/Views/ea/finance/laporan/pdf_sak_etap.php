<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan SAK ETAP</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            color: #000;
            line-height: 1.4;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }
        
        .page-break { page-break-after: always; }
        
        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 6px 4px;
            vertical-align: middle;
        }
        
        /* Borders for Financial Reports */
        .border-top-thick { border-top: 2px solid #000; }
        .border-bottom-thick { border-bottom: 2px solid #000; }
        .border-top { border-top: 1px solid #000; }
        .border-bottom { border-bottom: 1px solid #000; }
        .border-double-bottom { border-bottom: 4px double #000; }
        
        /* Layouts */
        .header { margin-bottom: 30px; }
        .header h1 { font-size: 16pt; margin: 0 0 5px 0; letter-spacing: 1px; }
        .header h2 { font-size: 14pt; margin: 0 0 5px 0; }
        .header p { margin: 0; font-size: 11pt; font-style: italic; }
        
        .indent-1 { padding-left: 20px; }
        .indent-2 { padding-left: 40px; }
        
        .section-title {
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .text-justify { text-align: justify; }

        /* Report specific layouts */
        .report-table th {
            text-align: center;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
            padding: 10px 4px;
        }
        
        .subtotal-row td {
            border-top: 1px solid #000;
            font-weight: bold;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .total-row td {
            border-top: 2px solid #000;
            border-bottom: 4px double #000;
            font-weight: bold;
            padding-top: 10px;
            padding-bottom: 10px;
        }
        
        .table-title {
            font-weight: bold;
            padding-top: 15px;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>

    <!-- COVER PAGE -->
    <div class="text-center" style="margin-top: 35%;">
        <h1 class="uppercase font-bold" style="font-size: 24pt; margin-bottom: 20px;">PT ALMA INDONESIA RAYA</h1>
        <h2 class="uppercase" style="font-size: 18pt; margin-bottom: 40px; color: #333;">Laporan Keuangan SAK ETAP</h2>
        <p style="font-size: 14pt;">Untuk Periode yang Berakhir Pada:</p>
        <p class="font-bold" style="font-size: 16pt; margin-top: 10px;"><?= date('d F Y', strtotime($endDate)) ?></p>
    </div>
    
    <div class="page-break"></div>

    <!-- 1. NERACA -->
    <div class="header text-center">
        <h1 class="uppercase font-bold">PT ALMA INDONESIA RAYA</h1>
        <h2 class="uppercase font-bold">Laporan Posisi Keuangan (Neraca)</h2>
        <p>Per Tanggal <?= date('d F Y', strtotime($endDate)) ?></p>
        <p>(Disajikan dalam Rupiah, kecuali dinyatakan lain)</p>
    </div>

    <!-- Dual Column Layout for Neraca using Table -->
    <table style="width: 100%; border: none;">
        <tr>
            <!-- LEFT COLUMN: ASET -->
            <td style="width: 48%; vertical-align: top; border: none; padding: 0;">
                <table class="report-table">
                    <tr><th colspan="2" class="text-left font-bold uppercase">A S E T</th></tr>
                    
                    <tr><td colspan="2" class="table-title">ASET LANCAR</td></tr>
                    <?php foreach ($neracaData['aset_lancar'] as $row): ?>
                        <tr>
                            <td class="indent-1"><?= esc($row['nama_akun']) ?></td>
                            <td class="text-right"><?= number_format($row['total'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>

                    <tr><td colspan="2" class="table-title">ASET TIDAK LANCAR (ASET TETAP)</td></tr>
                    <?php foreach ($neracaData['aset_tetap'] as $row): ?>
                        <tr>
                            <td class="indent-1"><?= esc($row['nama_akun']) ?></td>
                            <td class="text-right"><?= number_format($row['total'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    
                    <tr class="total-row">
                        <td class="uppercase">Total Aset</td>
                        <td class="text-right"><?= number_format($neracaTotals['aset'], 0, ',', '.') ?></td>
                    </tr>
                </table>
            </td>

            <td style="width: 4%; border: none;"></td> <!-- Spacer -->

            <!-- RIGHT COLUMN: LIABILITAS & EKUITAS -->
            <td style="width: 48%; vertical-align: top; border: none; padding: 0;">
                <table class="report-table">
                    <tr><th colspan="2" class="text-left font-bold uppercase">LIABILITAS DAN EKUITAS</th></tr>
                    
                    <tr><td colspan="2" class="table-title">LIABILITAS JANGKA PENDEK</td></tr>
                    <?php foreach ($neracaData['kewajiban'] as $row): ?>
                        <tr>
                            <td class="indent-1"><?= esc($row['nama_akun']) ?></td>
                            <td class="text-right"><?= number_format($row['total'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="subtotal-row">
                        <td class="indent-1">Total Liabilitas</td>
                        <td class="text-right"><?= number_format($neracaTotals['kewajiban'], 0, ',', '.') ?></td>
                    </tr>

                    <tr><td colspan="2" class="table-title">EKUITAS</td></tr>
                    <?php foreach ($neracaData['ekuitas'] as $row): ?>
                        <tr>
                            <td class="indent-1"><?= esc($row['nama_akun']) ?></td>
                            <td class="text-right"><?= number_format($row['total'], 0, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                    
                    <tr class="total-row">
                        <td class="uppercase">Total Liabilitas dan Ekuitas</td>
                        <td class="text-right"><?= number_format($neracaTotals['kewajiban'] + $neracaTotals['ekuitas'], 0, ',', '.') ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- 2. LABA RUGI -->
    <div class="header text-center">
        <h1 class="uppercase font-bold">PT ALMA INDONESIA RAYA</h1>
        <h2 class="uppercase font-bold">Laporan Laba (Rugi)</h2>
        <p>Untuk Periode yang Berakhir Pada <?= date('d F Y', strtotime($endDate)) ?></p>
        <p>(Disajikan dalam Rupiah, kecuali dinyatakan lain)</p>
    </div>

    <table class="report-table">
        <tr>
            <th class="text-left">KETERANGAN</th>
            <th class="text-right" style="width: 30%;">JUMLAH</th>
        </tr>
        
        <tr><td colspan="2" class="table-title">PENDAPATAN USAHA</td></tr>
        <?php foreach ($lrGrouped['pendapatan'] as $row): ?>
            <tr>
                <td class="indent-1"><?= esc($row['nama_akun']) ?></td>
                <td class="text-right"><?= number_format($row['net'], 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        <tr class="subtotal-row">
            <td class="text-right">Jumlah Pendapatan Usaha</td>
            <td class="text-right"><?= number_format($lrTotals['pendapatan'], 0, ',', '.') ?></td>
        </tr>

        <tr><td colspan="2" class="table-title">BEBAN POKOK PENJUALAN</td></tr>
        <?php foreach ($lrGrouped['hpp'] as $row): ?>
            <tr>
                <td class="indent-1"><?= esc($row['nama_akun']) ?></td>
                <td class="text-right">(<?= number_format($row['net'], 0, ',', '.') ?>)</td>
            </tr>
        <?php endforeach; ?>
        <tr class="subtotal-row">
            <td class="text-right">Jumlah Beban Pokok Penjualan</td>
            <td class="text-right">(<?= number_format($lrTotals['hpp'], 0, ',', '.') ?>)</td>
        </tr>

        <tr class="total-row" style="border-bottom: 2px solid #000;">
            <td class="uppercase">Laba (Rugi) Kotor</td>
            <td class="text-right"><?= number_format($grossProfit, 0, ',', '.') ?></td>
        </tr>

        <tr><td colspan="2" class="table-title">BEBAN OPERASIONAL</td></tr>
        <?php foreach ($lrGrouped['beban'] as $row): ?>
            <tr>
                <td class="indent-1"><?= esc($row['nama_akun']) ?></td>
                <td class="text-right">(<?= number_format($row['net'], 0, ',', '.') ?>)</td>
            </tr>
        <?php endforeach; ?>
        <tr class="subtotal-row">
            <td class="text-right">Jumlah Beban Operasional</td>
            <td class="text-right">(<?= number_format($lrTotals['beban'], 0, ',', '.') ?>)</td>
        </tr>

        <tr class="total-row" style="border-bottom: 2px solid #000;">
            <td class="uppercase">Laba (Rugi) Operasional</td>
            <td class="text-right"><?= number_format($operatingProfit, 0, ',', '.') ?></td>
        </tr>

        <tr><td colspan="2" class="table-title">PENDAPATAN (BEBAN) LAIN-LAIN</td></tr>
        <?php foreach ($lrGrouped['pendapatan_lain'] as $row): ?>
            <tr>
                <td class="indent-1"><?= esc($row['nama_akun']) ?></td>
                <td class="text-right"><?= number_format($row['net'], 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        <?php foreach ($lrGrouped['beban_lain'] as $row): ?>
            <tr>
                <td class="indent-1"><?= esc($row['nama_akun']) ?></td>
                <td class="text-right">(<?= number_format($row['net'], 0, ',', '.') ?>)</td>
            </tr>
        <?php endforeach; ?>
        <tr class="subtotal-row">
            <td class="text-right">Jumlah Pendapatan (Beban) Lain-lain</td>
            <td class="text-right"><?= number_format($lrTotals['pendapatan_lain'] - $lrTotals['beban_lain'], 0, ',', '.') ?></td>
        </tr>

        <tr class="total-row">
            <td class="uppercase">Laba (Rugi) Bersih</td>
            <td class="text-right"><?= number_format($netProfit, 0, ',', '.') ?></td>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- 3. PERUBAHAN EKUITAS -->
    <div class="header text-center">
        <h1 class="uppercase font-bold">PT ALMA INDONESIA RAYA</h1>
        <h2 class="uppercase font-bold">Laporan Perubahan Ekuitas</h2>
        <p>Untuk Periode yang Berakhir Pada <?= date('d F Y', strtotime($endDate)) ?></p>
        <p>(Disajikan dalam Rupiah, kecuali dinyatakan lain)</p>
    </div>

    <table class="report-table">
        <tr>
            <th class="text-left">KETERANGAN</th>
            <th class="text-right" style="width: 30%;">JUMLAH</th>
        </tr>
        
        <?php foreach ($equityData as $row): ?>
            <tr>
                <td><?= esc($row['nama_akun']) ?></td>
                <td class="text-right"><?= number_format($row['total'], 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        <tr>
            <td>Laba (Rugi) Tahun Berjalan</td>
            <td class="text-right"><?= number_format($labaBerjalan, 0, ',', '.') ?></td>
        </tr>
        
        <?php
            $totalModal = $labaBerjalan;
            foreach ($equityData as $row) { $totalModal += $row['total']; }
        ?>
        <tr class="total-row">
            <td class="uppercase">Saldo Ekuitas Akhir</td>
            <td class="text-right"><?= number_format($totalModal, 0, ',', '.') ?></td>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- 4. ARUS KAS -->
    <div class="header text-center">
        <h1 class="uppercase font-bold">PT ALMA INDONESIA RAYA</h1>
        <h2 class="uppercase font-bold">Laporan Arus Kas</h2>
        <p>Untuk Periode yang Berakhir Pada <?= date('d F Y', strtotime($endDate)) ?></p>
        <p>(Disajikan dalam Rupiah, kecuali dinyatakan lain)</p>
    </div>

    <table class="report-table">
        <tr>
            <th class="text-left">KETERANGAN</th>
            <th class="text-right" style="width: 30%;">JUMLAH</th>
        </tr>

        <tr><td colspan="2" class="table-title">ARUS KAS DARI AKTIVITAS OPERASI</td></tr>
        <?php 
        $totOps = 0;
        foreach ($cashData['operasional'] as $row): 
            $totOps += $row['total'];
        ?>
            <tr>
                <td class="indent-1"><?= esc($row['nama']) ?></td>
                <td class="text-right"><?= number_format($row['total'], 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        <tr class="subtotal-row">
            <td class="text-right">Kas Bersih Diperoleh dari (Digunakan untuk) Aktivitas Operasi</td>
            <td class="text-right"><?= number_format($totOps, 0, ',', '.') ?></td>
        </tr>

        <tr><td colspan="2" class="table-title">ARUS KAS DARI AKTIVITAS INVESTASI</td></tr>
        <?php 
        $totInv = 0;
        foreach ($cashData['investasi'] as $row): 
            $totInv += $row['total'];
        ?>
            <tr>
                <td class="indent-1"><?= esc($row['nama']) ?></td>
                <td class="text-right"><?= number_format($row['total'], 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        <tr class="subtotal-row">
            <td class="text-right">Kas Bersih Diperoleh dari (Digunakan untuk) Aktivitas Investasi</td>
            <td class="text-right"><?= number_format($totInv, 0, ',', '.') ?></td>
        </tr>

        <tr><td colspan="2" class="table-title">ARUS KAS DARI AKTIVITAS PENDANAAN</td></tr>
        <?php 
        $totPen = 0;
        foreach ($cashData['pendanaan'] as $row): 
            $totPen += $row['total'];
        ?>
            <tr>
                <td class="indent-1"><?= esc($row['nama']) ?></td>
                <td class="text-right"><?= number_format($row['total'], 0, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        <tr class="subtotal-row">
            <td class="text-right">Kas Bersih Diperoleh dari (Digunakan untuk) Aktivitas Pendanaan</td>
            <td class="text-right"><?= number_format($totPen, 0, ',', '.') ?></td>
        </tr>
        
        <?php
        $kenaikan = $totOps + $totInv + $totPen;
        $saldoAwal = $openingBalance;
        $saldoAkhir = $saldoAwal + $kenaikan;
        ?>

        <tr class="total-row" style="border-bottom: none;">
            <td class="uppercase">Kenaikan (Penurunan) Bersih Kas dan Setara Kas</td>
            <td class="text-right"><?= number_format($kenaikan, 0, ',', '.') ?></td>
        </tr>
        <tr>
            <td class="uppercase font-bold">Kas dan Setara Kas Awal Periode</td>
            <td class="text-right font-bold"><?= number_format($saldoAwal, 0, ',', '.') ?></td>
        </tr>
        <tr class="total-row">
            <td class="uppercase">Kas dan Setara Kas Akhir Periode</td>
            <td class="text-right"><?= number_format($saldoAkhir, 0, ',', '.') ?></td>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- 5. CALK -->
    <div class="header text-center">
        <h1 class="uppercase font-bold">PT ALMA INDONESIA RAYA</h1>
        <h2 class="uppercase font-bold">Catatan Atas Laporan Keuangan</h2>
        <p>Untuk Tahun yang Berakhir Pada <?= date('d F Y', strtotime($endDate)) ?></p>
        <p>(Disajikan dalam Rupiah, kecuali dinyatakan lain)</p>
    </div>

    <div class="text-justify" style="font-size: 11pt;">
        <div class="section-title">1. GAMBARAN UMUM</div>
        <p><?= nl2br(esc($calkData['gambaran_umum'])) ?></p>

        <div class="section-title">2. IKHTISAR KEBIJAKAN AKUNTANSI PENTING</div>
        <p><?= nl2br(esc($calkData['kebijakan_akuntansi'])) ?></p>

        <div class="section-title">3. KAS DAN BANK</div>
        <p><?= nl2br(esc($calkData['rincian_kas'])) ?></p>

        <div class="section-title">4. PIUTANG USAHA DAN LAINNYA</div>
        <p><?= nl2br(esc($calkData['rincian_piutang'])) ?></p>

        <div class="section-title">5. ASET TETAP</div>
        <p><?= nl2br(esc($calkData['rincian_aset_tetap'])) ?></p>

        <div class="section-title">6. LIABILITAS (HUTANG USAHA)</div>
        <p><?= nl2br(esc($calkData['rincian_hutang'])) ?></p>

        <div style="margin-top: 60px; padding-top: 15px; border-top: 1px solid #000; text-align: center; font-style: italic; color: #333; font-size: 10pt;">
            Catatan atas laporan keuangan ini merupakan bagian yang tidak terpisahkan dari laporan keuangan secara keseluruhan sesuai dengan Standar Akuntansi Keuangan untuk Entitas Tanpa Akuntabilitas Publik (SAK ETAP).
        </div>
    </div>

</body>
</html>
