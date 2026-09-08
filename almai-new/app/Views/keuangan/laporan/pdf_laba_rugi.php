<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Laba Rugi Komprehensif</title>
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
        .header .company-name { font-size: 13px; }
        .header .title { font-size: 13px; margin: 3px 0; text-transform: uppercase; }
        .header .period { font-weight: normal; }
        
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
            background-color: #f0f0f0;
        }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-weight-bold { font-weight: bold; }
        .pl-3 { padding-left: 20px; }
        
    </style>
</head>
<body>

    <div class="header">
        <div class="company-name">PT ALMA INDONESIA RAYA</div>
        <div class="title">LAPORAN LABA RUGI KOMPREHENSIF</div>
        <div class="period">Per <?= date('d M Y', strtotime($endDate)) ?></div>
        <div class="period">(Dalam Rupiah)</div>
    </div>

    <table>
        <thead>
            <tr>
                <th colspan="2" style="width: 50%;"></th>
                <th style="width: 25%;"><?= $currMonthName ?></th>
                <th style="width: 25%;"><?= $prevMonthName ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $printRow = function($colA, $colB, $colC, $colD, $isBold = false, $mergeAB = false) {
                $fw = $isBold ? 'font-weight-bold' : '';
                echo "<tr class='{$fw}'>";
                
                if ($mergeAB) {
                    echo "<td colspan='2' class='text-left'>{$colA}</td>";
                } else {
                    echo "<td class='text-left'>{$colA}</td>";
                    echo "<td class='text-left'>{$colB}</td>";
                }
                
                $valC = is_numeric($colC) ? ($colC == 0 ? '0,00' : number_format($colC, 2, ',', '.')) : $colC;
                $valD = is_numeric($colD) ? ($colD == 0 ? '0,00' : number_format($colD, 2, ',', '.')) : $colD;
                
                echo "<td class='text-right'>{$valC}</td>";
                echo "<td class='text-right'>{$valD}</td>";
                
                echo "</tr>";
            };

            $getAllAccounts = function($key) use ($currData, $prevData) {
                $accounts = [];
                foreach (array_merge($currData['groupedData'][$key], $prevData['groupedData'][$key]) as $ac) {
                    $accounts[$ac['nama_akun']] = 1;
                }
                return array_keys($accounts);
            };

            $getNet = function($key, $name, $data) {
                foreach ($data['groupedData'][$key] as $ac) {
                    if ($ac['nama_akun'] === $name) return $ac['net'];
                }
                return 0;
            };

            // PENGHASILAN
            $printRow("PENGHASILAN", "", "", "", true, true);
            foreach ($getAllAccounts('pendapatan') as $name) {
                $printRow($name, "", $getNet('pendapatan', $name, $currData), $getNet('pendapatan', $name, $prevData), false, true);
            }
            foreach ($getAllAccounts('pendapatan_lain') as $name) {
                $printRow($name, "", $getNet('pendapatan_lain', $name, $currData), $getNet('pendapatan_lain', $name, $prevData), false, true);
            }
            $printRow("TOTAL PENDAPATAN", "", $currData['totals']['pendapatan'] + $currData['totals']['pendapatan_lain'], $prevData['totals']['pendapatan'] + $prevData['totals']['pendapatan_lain'], true, true);

            // BEBAN POKOK
            foreach ($getAllAccounts('hpp') as $name) {
                $printRow($name, "", $getNet('hpp', $name, $currData), $getNet('hpp', $name, $prevData), false, true);
            }
            $printRow("TOTAL BEBAN POKOK", "", $currData['totals']['hpp'], $prevData['totals']['hpp'], true, true);
            $printRow("LABA KOTOR", "", $currData['grossProfit'], $prevData['grossProfit'], true, true);

            // BEBAN
            $printRow("BEBAN", "", "", "", true, true);
            $bebanLainTitleShown = false;
            foreach ($getAllAccounts('beban') as $name) {
                $printRow($name, "", $getNet('beban', $name, $currData), $getNet('beban', $name, $prevData), false, true);
            }
            foreach ($getAllAccounts('beban_lain') as $name) {
                if (!$bebanLainTitleShown) {
                    $printRow("Beban lain-lain (uraikan", $name, $getNet('beban_lain', $name, $currData), $getNet('beban_lain', $name, $prevData), false, false);
                    $bebanLainTitleShown = true;
                } else {
                    $printRow("", $name, $getNet('beban_lain', $name, $currData), $getNet('beban_lain', $name, $prevData), false, false);
                }
            }
            $printRow("Total beban", "", $currData['totals']['beban'] + $currData['totals']['beban_lain'], $prevData['totals']['beban'] + $prevData['totals']['beban_lain'], true, true);
            
            $printRow("&nbsp;", "", "", "", false, true);

            $printRow("Laba (rugi) sebelum pajak", "", $currData['netProfit'], $prevData['netProfit'], true, true);
            $printRow("Pajak Penghasilan kini", "", 0, 0, false, true);
            $printRow("Pajak Penghasilan Tangguhan", "", 0, 0, false, true);
            $printRow("Laba (rugi) tahun berjalan", "", $currData['netProfit'], $prevData['netProfit'], true, true);

            $printRow("&nbsp;", "", "", "", false, true);
            $printRow("Pendapatan Komprehensif lain", "", "", "", true, true);
            $printRow("Pos-pos yang tidak akan direklasifikasi ke laba rugi:", "", "", "", true, true);
            $printRow("<span class='pl-3'>Keuntungan dari perubahan nilai aset keuangan yang diukur pada nilai wajar melalui</span>", "", 0, 0, false, true);
            $printRow("<span class='pl-3'>Keuntungan dari revaluasi atas aset tetap</span>", "", 0, 0, false, true);
            $printRow("<span class='pl-3'>Keuntungan (kerugian) aktuarial dari program pascakerja imbalan pasti</span>", "", 0, 0, false, true);
            $printRow("<span class='pl-3'>Bagian pendapatan komprehensif lain dari entitas asosiasi dan pengendalian bersama</span>", "", 0, 0, false, true);
            $printRow("<span class='pl-3'>Pajak penghasilan terkait</span>", "", 0, 0, false, true);
            
            $printRow("Pos-pos yang akan direklasifikasi ke laba rugi:", "", "", "", true, true);
            $printRow("<span class='pl-3'>Keuntungan dari perubahan nilai aset keuangan yang diukur pada nilai wajar melalui</span>", "", 0, 0, false, true);
            $printRow("<span class='pl-3'>Pajak penghasilan terkait</span>", "", 0, 0, false, true);
            
            $printRow("Total Pendapatan Komprehensif lain", "", 0, 0, true, true);
            $printRow("Laba (rugi) komprehensif tahun berjalan", "", $currData['netProfit'], $prevData['netProfit'], true, true);
            
            $printRow("Laba (rugi) yang dapat didistribusikan kepada :", "", "", "", true, true);
            $printRow("<span class='pl-3'>Pemilik entitas induk</span>", "", 0, 0, false, true);
            $printRow("<span class='pl-3'>Kepentingan nonpengendali</span>", "", 0, 0, false, true);
            
            $printRow("Laba (rugi) komprehensif yang dapat didistribusikan kepada :", "", "", "", true, true);
            $printRow("<span class='pl-3'>Pemilik entitas induk</span>", "", 0, 0, false, true);
            $printRow("<span class='pl-3'>Kepentingan nonpengendali</span>", "", 0, 0, false, true);
            ?>
        </tbody>
    </table>

</body>
</html>