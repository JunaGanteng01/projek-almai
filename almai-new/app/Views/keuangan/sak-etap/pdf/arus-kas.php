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
        <div class="period">Per <?= date('d M Y', strtotime($arusKas['periode']['end_date'])) ?></div>
        <div class="period">(Dalam Rupiah)</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 50%;">Keterangan</th>
                <th style="width: 10%;">Catatan</th>
                <th style="width: 20%;"><?= date('M Y', strtotime($arusKas['periode']['end_date'])) ?></th>
                <th style="width: 20%;"><?= date('M Y', strtotime($arusKas['periode']['start_date'])) ?></th>
            </tr>
        </thead>
        <tbody>
            <!-- AKTIVITAS OPERASI -->
            <tr>
                <td colspan="4" class="font-weight-bold text-underline">ARUS KAS DARI AKTIVITAS OPERASI</td>
            </tr>
            <tr>
                <td>Laba/Rugi Tahun Berjalan</td>
                <td></td>
                <td class="text-right"><?= number_format($arusKas['aktivitas_operasi']['laba_rugi_tahun_berjalan'], 2, ',', '.') ?></td>
                <td class="text-right">0,00</td>
            </tr>
            <tr>
                <td>Penyusutan & Amortisasi</td>
                <td></td>
                <td class="text-right"><?= number_format($arusKas['aktivitas_operasi']['penyusutan_amortisasi']['total'], 2, ',', '.') ?></td>
                <td class="text-right">0,00</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Jumlah arus kas dari aktivitas operasi</td>
                <td></td>
                <td class="font-weight-bold text-right"><?= number_format($arusKas['aktivitas_operasi']['arus_kas_operasi'], 2, ',', '.') ?></td>
                <td class="font-weight-bold text-right">0,00</td>
            </tr>

            <!-- AKTIVITAS INVESTASI -->
            <tr>
                <td colspan="4" class="font-weight-bold text-underline">ARUS KAS DARI AKTIVITAS INVESTASI</td>
            </tr>
            <tr>
                <td>Arus Kas dari Aktivitas Investasi</td>
                <td></td>
                <td class="text-right"><?= number_format($arusKas['aktivitas_investasi']['arus_kas_investasi'], 2, ',', '.') ?></td>
                <td class="text-right">0,00</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Jumlah arus dari aktivitas investasi</td>
                <td></td>
                <td class="font-weight-bold text-right"><?= number_format($arusKas['aktivitas_investasi']['arus_kas_investasi'], 2, ',', '.') ?></td>
                <td class="font-weight-bold text-right">0,00</td>
            </tr>

            <!-- AKTIVITAS PENDANAAN -->
            <tr>
                <td colspan="4" class="font-weight-bold text-underline">ARUS KAS DARI AKTIVITAS PENDANAAN</td>
            </tr>
            <tr>
                <td>Arus Kas dari Aktivitas Pendanaan</td>
                <td></td>
                <td class="text-right"><?= number_format($arusKas['aktivitas_pendanaan']['arus_kas_pendanaan'], 2, ',', '.') ?></td>
                <td class="text-right">0,00</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Jumlah arus kas dari aktivitas pendanaan</td>
                <td></td>
                <td class="font-weight-bold text-right"><?= number_format($arusKas['aktivitas_pendanaan']['arus_kas_pendanaan'], 2, ',', '.') ?></td>
                <td class="font-weight-bold text-right">0,00</td>
            </tr>

            <!-- NET KAS -->
            <tr>
                <td class="font-weight-bold">Kenaikan (penurunan) arus kas neto</td>
                <td></td>
                <td class="font-weight-bold text-right"><?= number_format($arusKas['perubahan_kas_bersih'], 2, ',', '.') ?></td>
                <td class="font-weight-bold text-right">0,00</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Saldo kas dan setara kas awal periode</td>
                <td></td>
                <td class="font-weight-bold text-right"><?= number_format($arusKas['kas_awal_periode'], 2, ',', '.') ?></td>
                <td class="font-weight-bold text-right">0,00</td>
            </tr>
            <tr>
                <td class="font-weight-bold">Saldo kas dan setara kas akhir periode</td>
                <td></td>
                <td class="font-weight-bold text-right"><?= number_format($arusKas['kas_akhir_periode'], 2, ',', '.') ?></td>
                <td class="font-weight-bold text-right">0,00</td>
            </tr>
        </tbody>
    </table>

</body>
</html>
