<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Arus Kas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        .header {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .header .company-name {
            font-size: 14px;
        }
        .header .title {
            font-size: 14px;
            margin: 5px 0;
        }
        .header .period {
            font-weight: normal;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: middle;
        }
        th {
            font-weight: bold;
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-weight-bold {
            font-weight: bold;
        }
        .text-underline {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="company-name">PT ALMA INDONESIA RAYA</div>
        <div class="title">LAPORAN ARUS KAS</div>
        <div class="period">Periode <?= date('d M Y', strtotime($startDate)) ?> s/d <?= date('d M Y', strtotime($endDate)) ?></div>
        <div class="period">(Dalam Rupiah)</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 50%;">Keterangan</th>
                <th style="width: 10%;">Catatan</th>
                <th style="width: 20%;"><?= date('M Y', strtotime($endDate)) ?></th>
                <th style="width: 20%;"><?= date('M Y', strtotime($startDate . ' -1 month')) ?></th>
            </tr>
        </thead>
        <tbody>
            <!-- AKTIVITAS OPERASI -->
            <tr>
                <td colspan="4" class="font-weight-bold text-underline">ARUS KAS DARI AKTIVITAS OPERASI</td>
            </tr>
            <tr>
                <td colspan="4">Penerimaan kas dari:</td>
            </tr>
            <?php 
                $penerimaanOperasi = [];
                $pengeluaranOperasi = [];
                foreach ($cashData['operasional'] as $item) {
                    if ($item['total'] > 0) $penerimaanOperasi[] = $item;
                    else $pengeluaranOperasi[] = $item;
                }
                foreach ($penerimaanOperasi as $item): 
            ?>
            <tr>
                <td style="padding-left: 15px;"><?= $item['nama'] ?></td>
                <td></td>
                <td class="text-right"><?= number_format($item['total'], 2, ',', '.') ?></td>
                <td class="text-right">0,00</td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td class="font-weight-bold">Total penerimaan</td>
                <td></td>
                <td class="text-right font-weight-bold"><?= number_format(array_sum(array_column($penerimaanOperasi, 'total')), 2, ',', '.') ?></td>
                <td class="text-right font-weight-bold">0,00</td>
            </tr>

            <tr>
                <td colspan="4">Pembayaran kas untuk:</td>
            </tr>
            <?php foreach ($pengeluaranOperasi as $item): ?>
            <tr>
                <td style="padding-left: 15px;"><?= $item['nama'] ?></td>
                <td></td>
                <td class="text-right"><?= number_format($item['total'], 2, ',', '.') ?></td>
                <td class="text-right">0,00</td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td class="font-weight-bold">Total pengeluaran</td>
                <td></td>
                <td class="text-right font-weight-bold"><?= number_format(array_sum(array_column($pengeluaranOperasi, 'total')), 2, ',', '.') ?></td>
                <td class="text-right font-weight-bold">0,00</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Jumlah arus kas dari aktivitas operasi</td>
                <td></td>
                <td class="font-weight-bold text-right"><?= number_format($totalOperasional, 2, ',', '.') ?></td>
                <td class="font-weight-bold text-right">0,00</td>
            </tr>

            <!-- AKTIVITAS INVESTASI -->
            <tr>
                <td colspan="4" class="font-weight-bold text-underline">ARUS KAS DARI AKTIVITAS INVESTASI</td>
            </tr>
            <tr>
                <td colspan="4">Penerimaan kas dari:</td>
            </tr>
            <?php 
                $penerimaanInv = [];
                $pengeluaranInv = [];
                foreach ($cashData['investasi'] as $item) {
                    if ($item['total'] > 0) $penerimaanInv[] = $item;
                    else $pengeluaranInv[] = $item;
                }
                foreach ($penerimaanInv as $item): 
            ?>
            <tr>
                <td style="padding-left: 15px;"><?= $item['nama'] ?></td>
                <td></td>
                <td class="text-right"><?= number_format($item['total'], 2, ',', '.') ?></td>
                <td class="text-right">0,00</td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td class="font-weight-bold">Total penerimaan</td>
                <td></td>
                <td class="text-right font-weight-bold"><?= number_format(array_sum(array_column($penerimaanInv, 'total')), 2, ',', '.') ?></td>
                <td class="text-right font-weight-bold">0,00</td>
            </tr>
            <tr>
                <td colspan="4">Pembayaran kas untuk:</td>
            </tr>
            <?php foreach ($pengeluaranInv as $item): ?>
            <tr>
                <td style="padding-left: 15px;"><?= $item['nama'] ?></td>
                <td></td>
                <td class="text-right"><?= number_format($item['total'], 2, ',', '.') ?></td>
                <td class="text-right">0,00</td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td class="font-weight-bold">Total pengeluaran</td>
                <td></td>
                <td class="text-right font-weight-bold"><?= number_format(array_sum(array_column($pengeluaranInv, 'total')), 2, ',', '.') ?></td>
                <td class="text-right font-weight-bold">0,00</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Jumlah arus dari aktivitas investasi</td>
                <td></td>
                <td class="font-weight-bold text-right"><?= number_format($totalInvestasi, 2, ',', '.') ?></td>
                <td class="font-weight-bold text-right">0,00</td>
            </tr>

            <!-- AKTIVITAS PENDANAAN -->
            <tr>
                <td colspan="4" class="font-weight-bold text-underline">ARUS KAS DARI AKTIVITAS PENDANAAN</td>
            </tr>
            <tr>
                <td colspan="4">Penerimaan kas dari:</td>
            </tr>
            <?php 
                $penerimaanPen = [];
                $pengeluaranPen = [];
                foreach ($cashData['pendanaan'] as $item) {
                    if ($item['total'] > 0) $penerimaanPen[] = $item;
                    else $pengeluaranPen[] = $item;
                }
                foreach ($penerimaanPen as $item): 
            ?>
            <tr>
                <td style="padding-left: 15px;"><?= $item['nama'] ?></td>
                <td></td>
                <td class="text-right"><?= number_format($item['total'], 2, ',', '.') ?></td>
                <td class="text-right">0,00</td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td class="font-weight-bold">Total penerimaan</td>
                <td></td>
                <td class="text-right font-weight-bold"><?= number_format(array_sum(array_column($penerimaanPen, 'total')), 2, ',', '.') ?></td>
                <td class="text-right font-weight-bold">0,00</td>
            </tr>
            <tr>
                <td colspan="4">Pembayaran kas untuk:</td>
            </tr>
            <?php foreach ($pengeluaranPen as $item): ?>
            <tr>
                <td style="padding-left: 15px;"><?= $item['nama'] ?></td>
                <td></td>
                <td class="text-right"><?= number_format($item['total'], 2, ',', '.') ?></td>
                <td class="text-right">0,00</td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td class="font-weight-bold">Total pengeluaran</td>
                <td></td>
                <td class="text-right font-weight-bold"><?= number_format(array_sum(array_column($pengeluaranPen, 'total')), 2, ',', '.') ?></td>
                <td class="text-right font-weight-bold">0,00</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Jumlah arus kas dari aktivitas pendanaan</td>
                <td></td>
                <td class="font-weight-bold text-right"><?= number_format($totalPendanaan, 2, ',', '.') ?></td>
                <td class="font-weight-bold text-right">0,00</td>
            </tr>

            <!-- NET KAS -->
            <tr>
                <td class="font-weight-bold">Kenaikan (penurunan) arus kas neto</td>
                <td></td>
                <td class="font-weight-bold text-right"><?= number_format($netChange, 2, ',', '.') ?></td>
                <td class="font-weight-bold text-right">0,00</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Saldo kas dan setara kas awal periode</td>
                <td></td>
                <td class="font-weight-bold text-right"><?= number_format($openingBalance, 2, ',', '.') ?></td>
                <td class="font-weight-bold text-right">0,00</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Saldo kas dan setara kas akhir periode</td>
                <td></td>
                <td class="font-weight-bold text-right"><?= number_format($openingBalance + $netChange, 2, ',', '.') ?></td>
                <td class="font-weight-bold text-right">0,00</td>
            </tr>
        </tbody>
    </table>

</body>
</html>