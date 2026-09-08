<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Perubahan Ekuitas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 8px; /* small font to fit 11 columns in A4 landscape */
        }
        .header {
            text-align: center;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .header .company-name {
            font-size: 11px;
        }
        .header .title {
            font-size: 11px;
            margin: 3px 0;
            text-transform: uppercase;
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
            padding: 4px;
            vertical-align: middle;
        }
        th {
            font-weight: bold;
            text-align: center;
            background-color: #f0f0f0;
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
    </style>
</head>
<body>

    <div class="header">
        <div class="company-name">PT ALMA INDONESIA RAYA</div>
        <div class="title">LAPORAN PERUBAHAN EKUITAS</div>
        <div class="period">Per <?= date('d M Y', strtotime($endDate)) ?></div>
        <div class="period">(Dalam Rupiah)</div>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 12%;">Keterangan</th>
                <th rowspan="2" style="width: 10%;">Modal disetor dan<br>tambahan modal disetor</th>
                <th colspan="2" style="width: 14%;">Saldo laba</th>
                <th rowspan="2" style="width: 8%;">Selisih penilaian<br>Aset Keuangan</th>
                <th rowspan="2" style="width: 8%;">Keuntungan dan<br>kerugian aktuarial</th>
                <th rowspan="2" style="width: 8%;">Surplus<br>Revaluasi</th>
                <th rowspan="2" style="width: 10%;">Selisih kurs penjabaran<br>laporan keuangan dalam<br>mata uang asing</th>
                <th rowspan="2" style="width: 8%;">Ekuitas Lainnya</th>
                <th rowspan="2" style="width: 10%;">Kepentingan<br>nonpengendali</th>
                <th rowspan="2" style="width: 12%;">Jumlah Ekuitas</th>
            </tr>
            <tr>
                <th>Ditentukan<br>penggunaannya</th>
                <th>Belum ditentukan<br>penggunaannya</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $printRow = function($ket, $modal, $labaDitentukan, $labaBelum, $isBold = false) {
                $jumlah = $modal + $labaDitentukan + $labaBelum;
                $fw = $isBold ? 'font-weight-bold' : '';
                echo "<tr class='{$fw}'>";
                echo "<td>{$ket}</td>";
                echo "<td class='text-right'>" . ($modal == 0 ? '-' : number_format($modal, 2, ',', '.')) . "</td>";
                echo "<td class='text-right'>" . ($labaDitentukan == 0 ? '0,00' : number_format($labaDitentukan, 2, ',', '.')) . "</td>";
                echo "<td class='text-right'>" . ($labaBelum == 0 ? '0,00' : number_format($labaBelum, 2, ',', '.')) . "</td>";
                echo "<td class='text-right'>0,00</td>";
                echo "<td class='text-right'>0,00</td>";
                echo "<td class='text-right'>0,00</td>";
                echo "<td class='text-right'>0,00</td>";
                echo "<td class='text-right'>0,00</td>";
                echo "<td class='text-right'>0,00</td>";
                echo "<td class='text-right'>" . ($jumlah == 0 ? '0,00' : number_format($jumlah, 2, ',', '.')) . "</td>";
                echo "</tr>";
            };

            // Saldo Prev Prev
            $printRow("Saldo per " . date('d M Y', strtotime($prevPrevMonthEnd)), $saldoPrevPrev['modal'], 0, $saldoPrevPrev['laba'], true);
            $printRow("Setoran Modal", $mutasiPrev['modal'], 0, 0);
            $printRow("Laba Bersih", 0, 0, $mutasiPrev['laba']);
            $printRow("Cadangan umum", 0, 0, 0);
            $printRow("Cadangan tujuan", 0, 0, 0);
            $printRow("Dividen", 0, 0, $mutasiPrev['dividen']);
            $printRow("Penghasilan komprehensif tahun", 0, 0, 0);
            
            // Saldo Prev
            $printRow("Saldo per " . date('d M Y', strtotime($prevMonthEnd)), $saldoPrev['modal'], 0, $saldoPrev['laba'], true);
            $printRow("Setoran Modal", $mutasiCurr['modal'], 0, 0);
            $printRow("Cadangan umum", 0, 0, 0);
            $printRow("Cadangan tujuan", 0, 0, 0);
            $printRow("Dividen", 0, 0, $mutasiCurr['dividen']);
            $printRow("Penghasilan komprehensif tahun", 0, 0, 0);

            // Saldo Curr
            $printRow("Saldo per " . date('d M Y', strtotime($currMonthEnd)), $saldoCurr['modal'], 0, $saldoCurr['laba'], true);
            ?>
        </tbody>
    </table>

</body>
</html>