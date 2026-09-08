<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Posisi Keuangan (Neraca)</title>
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
        .pl-6 { padding-left: 40px; }
    </style>
</head>
<body>

    <div class="header">
        <div class="company-name">PT ALMA INDONESIA RAYA</div>
        <div class="title">LAPORAN POSISI KEUANGAN</div>
        <div class="period">Per <?= date('d M Y', strtotime($endDate)) ?></div>
        <div class="period">(Dalam Rupiah)</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 25%;">ASET</th>
                <th style="width: 12.5%;"><?= $currMonthName ?></th>
                <th style="width: 12.5%;"><?= $prevMonthName ?></th>
                <th style="width: 25%;">LIABILITAS DAN EKUITAS</th>
                <th style="width: 12.5%;"><?= $currMonthName ?></th>
                <th style="width: 12.5%;"><?= $prevMonthName ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Prepare arrays exactly like the Python patch does for Excel
            $getAllNames = function($key) use ($currData, $prevData) {
                $accounts = [];
                foreach (array_merge($currData['neracaData'][$key], $prevData['neracaData'][$key]) as $ac) {
                    $accounts[$ac['nama_akun']] = 1;
                }
                return array_keys($accounts);
            };

            $getNet = function($key, $name, $data) {
                foreach ($data['neracaData'][$key] as $ac) {
                    if ($ac['nama_akun'] === $name) return $ac['total'];
                }
                return 0;
            };

            $asetLancarNames = $getAllNames('aset_lancar');
            $asetTetapNames = $getAllNames('aset_tetap');
            $kewajibanPendekNames = $getAllNames('kewajiban');
            $kewajibanPanjangNames = $getAllNames('kewajiban_panjang');
            
            $leftRows = [];
            $leftRows[] = ["ASET", "", "", true];
            $leftRows[] = ["", "", "", false];
            $leftRows[] = ["ASET LANCAR", "", "", true];
            foreach ($asetLancarNames as $n) {
                $leftRows[] = ["<span class='pl-3'>{$n}</span>", $getNet('aset_lancar', $n, $currData), $getNet('aset_lancar', $n, $prevData), false];
            }
            $leftRows[] = ["<span class='pl-3'>Biaya dibayar dimuka</span>", 0, 0, false];
            $leftRows[] = ["<span class='pl-3'>Pajak dibayar dimuka</span>", 0, 0, false];
            $leftRows[] = ["<span class='pl-3'>PPN Masukan</span>", 0, 0, false];
            $leftRows[] = ["<span class='pl-3'>Aset keuangan lancar lain</span>", 0, 0, false];
            $leftRows[] = ["<span class='pl-3'>Total Aset Lancar</span>", $currData['totals']['aset_lancar'], $prevData['totals']['aset_lancar'], true];
            
            $leftRows[] = ["", "", "", false];
            $leftRows[] = ["ASET TIDAK LANCAR", "", "", true];
            foreach ($asetTetapNames as $n) {
                $leftRows[] = ["<span class='pl-3'>{$n}</span>", $getNet('aset_tetap', $n, $currData), $getNet('aset_tetap', $n, $prevData), false];
            }
            $leftRows[] = ["<span class='pl-3'>Aset keuangan tidak lancar lain</span>", 0, 0, false];
            $leftRows[] = ["<span class='pl-3'>Total Aset Tidak Lancar</span>", $currData['totals']['aset_tetap'], $prevData['totals']['aset_tetap'], true];
            $leftRows[] = ["", "", "", false];
            $leftRows[] = ["TOTAL ASET*", $currData['totals']['aset'], $prevData['totals']['aset'], true];

            $rightRows = [];
            $rightRows[] = ["LIABILITAS DAN EKUITAS", "", "", true];
            $rightRows[] = ["LIABILITAS", "", "", true];
            $rightRows[] = ["LIABILITAS JANGKA PENDEK", "", "", true];
            foreach ($kewajibanPendekNames as $n) {
                $rightRows[] = ["<span class='pl-3'>{$n}</span>", $getNet('kewajiban', $n, $currData), $getNet('kewajiban', $n, $prevData), false];
            }
            $rightRows[] = ["<span class='pl-3'>Utang kepada pihak terafiliasi</span>", 0, 0, false];
            $rightRows[] = ["<span class='pl-3'>Utang pajak</span>", 0, 0, false];
            $rightRows[] = ["<span class='pl-3'>Beban akrual/biaya masih harus dibayar</span>", 0, 0, false];
            $rightRows[] = ["<span class='pl-3'>Liabilitas keuangan jangka pendek lain</span>", 0, 0, false];
            $rightRows[] = ["<span class='pl-3'>Total Liabilitas Jangka Pendek</span>", $currData['totals']['kewajiban_pendek'], $prevData['totals']['kewajiban_pendek'], true];

            $rightRows[] = ["", "", "", false];
            $rightRows[] = ["LIABILITAS JANGKA PANJANG", "", "", true];
            foreach ($kewajibanPanjangNames as $n) {
                $rightRows[] = ["<span class='pl-3'>{$n}</span>", $getNet('kewajiban_panjang', $n, $currData), $getNet('kewajiban_panjang', $n, $prevData), false];
            }
            $rightRows[] = ["<span class='pl-3'>Imbalan kerja jangka panjang</span>", 0, 0, false];
            $rightRows[] = ["<span class='pl-3'>Utang subordinasi</span>", 0, 0, false];
            $rightRows[] = ["<span class='pl-3'>Liabilitas keuangan jangka panjang lain</span>", 0, 0, false];
            $rightRows[] = ["<span class='pl-3'>Total Liabilitas Jangka Panjang</span>", $currData['totals']['kewajiban_panjang'], $prevData['totals']['kewajiban_panjang'], true];
            
            $rightRows[] = ["", "", "", false];
            $rightRows[] = ["TOTAL LIABILITAS", $currData['totals']['kewajiban'], $prevData['totals']['kewajiban'], true];
            $rightRows[] = ["EKUITAS", "", "", true];
            $rightRows[] = ["Ekuitas yang diatribusikan pada pemilik entitas induk:", "", "", false];
            $rightRows[] = ["<span class='pl-3'>Modal saham:</span>", "", "", false];
            foreach ($getAllNames('ekuitas') as $n) {
                $rightRows[] = ["<span class='pl-6'>{$n}</span>", $getNet('ekuitas', $n, $currData), $getNet('ekuitas', $n, $prevData), false];
            }
            $rightRows[] = ["<span class='pl-6'>Tambahan modal disetor</span>", 0, 0, false];
            $rightRows[] = ["<span class='pl-3'>Saldo laba/rugi</span>", $currData['labaBerjalan'], $prevData['labaBerjalan'], false];
            $rightRows[] = ["<span class='pl-3'>Komponen ekuitas lain</span>", 0, 0, false];
            $rightRows[] = ["Kepentingan nonpengendali", 0, 0, false];
            
            $rightRows[] = ["", "", "", false];
            $rightRows[] = ["TOTAL EKUITAS", $currData['totals']['ekuitas'], $prevData['totals']['ekuitas'], true];
            $rightRows[] = ["TOTAL KEWAJIBAN DAN EKUITAS", $currData['totals']['kewajiban'] + $currData['totals']['ekuitas'], $prevData['totals']['kewajiban'] + $prevData['totals']['ekuitas'], true];

            $maxRows = max(count($leftRows), count($rightRows));

            $fmt = function($val) {
                if ($val === "") return "";
                if ($val == 0) return "-";
                return number_format($val, 0, ',', '.');
            };

            for ($i = 0; $i < $maxRows; $i++) {
                $lr = $leftRows[$i] ?? ["", "", "", false];
                $rr = $rightRows[$i] ?? ["", "", "", false];

                $fwL = $lr[3] ? 'font-weight-bold' : '';
                $fwR = $rr[3] ? 'font-weight-bold' : '';
                
                echo "<tr>";
                echo "<td class='text-left {$fwL}'>{$lr[0]}</td>";
                echo "<td class='text-right {$fwL}'>" . $fmt($lr[1]) . "</td>";
                echo "<td class='text-right {$fwL}'>" . $fmt($lr[2]) . "</td>";
                
                echo "<td class='text-left {$fwR}'>{$rr[0]}</td>";
                echo "<td class='text-right {$fwR}'>" . $fmt($rr[1]) . "</td>";
                echo "<td class='text-right {$fwR}'>" . $fmt($rr[2]) . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>

</body>
</html>