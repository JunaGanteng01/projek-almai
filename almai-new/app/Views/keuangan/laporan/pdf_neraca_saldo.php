<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Neraca Saldo</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 5px 0 0 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th, table td { border: 1px solid #000; padding: 6px; }
        table th { background-color: #f0f0f0; text-transform: uppercase; font-size: 11px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .bg-gray { background-color: #e0e0e0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>NERACA SALDO</h1>
        <h2 style="margin: 5px 0; font-size: 14px;">PT. ALMA INDONESIA RAYA</h2>
        <p>Per Tanggal: <?= date('d/m/Y', strtotime($endDate)) ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="15%">Kode Akun</th>
                <th width="35%">Nama Akun</th>
                <th width="20%">Kategori</th>
                <th width="15%">Debit (Rp)</th>
                <th width="15%">Kredit (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $totalDebitSum = 0;
            $totalKreditSum = 0;
            foreach ($reportData as $row): 
                $deb = (float)($row['total_debit'] ?? 0);
                $kred = (float)($row['total_kredit'] ?? 0);
                if ($deb == 0 && $kred == 0) continue; 
                
                $kategori = strtolower($row['kategori']);
                $isDebitNormal = in_array($kategori, ['kas & bank', 'akun piutang', 'persediaan', 'aktiva lancar lainya', 'aktiva tetap', 'aktiva lainya', 'harga pokok penjualan', 'beban', 'beban lainya']);
                if ($kategori === 'depresiasi & amortisasi') $isDebitNormal = false;

                $debitVal = 0; $kreditVal = 0;
                if ($isDebitNormal) {
                    $net = $deb - $kred;
                    if ($net >= 0) $debitVal = $net; else $kreditVal = abs($net);
                } else {
                    $net = $kred - $deb;
                    if ($net >= 0) $kreditVal = $net; else $debitVal = abs($net);
                }
                
                $totalDebitSum += $debitVal;
                $totalKreditSum += $kreditVal;
            ?>
            <tr>
                <td class="text-center"><?= esc($row['kode_akun']) ?></td>
                <td><?= esc($row['nama_akun']) ?></td>
                <td><?= esc($row['kategori']) ?></td>
                <td class="text-right"><?= $debitVal > 0 ? number_format($debitVal, 0, ',', '.') : '-' ?></td>
                <td class="text-right"><?= $kreditVal > 0 ? number_format($kreditVal, 0, ',', '.') : '-' ?></td>
            </tr>
            <?php endforeach; ?>
            
            <?php if (empty($reportData) || ($totalDebitSum == 0 && $totalKreditSum == 0)): ?>
            <tr><td colspan="5" class="text-center">Tidak ada transaksi atau saldo aktif.</td></tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr class="bg-gray">
                <td colspan="3" class="font-bold text-right" style="padding-right:20px;">TOTAL</td>
                <td class="text-right font-bold"><?= number_format($totalDebitSum, 0, ',', '.') ?></td>
                <td class="text-right font-bold"><?= number_format($totalKreditSum, 0, ',', '.') ?></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>