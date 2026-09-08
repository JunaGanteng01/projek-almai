<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Laba Rugi</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 16px; text-transform: uppercase; }
        .header p { margin: 5px 0 0 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table th, table td { border: 1px solid #000; padding: 6px; }
        table th { background-color: #f0f0f0; text-transform: uppercase; font-size: 11px; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .bg-gray { background-color: #e0e0e0; }
        .title-row td { font-weight: bold; font-size: 13px; text-transform: uppercase; background: #fff; border:none; border-bottom: 1px solid #000; padding-top:15px;}
        .indent { padding-left: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN LABA RUGI</h1>
        <h2 style="margin: 5px 0; font-size: 14px;">PT. ALMA INDONESIA RAYA</h2>
        <p>Periode: <?= date('d/m/Y', strtotime($startDate)) ?> s/d <?= date('d/m/Y', strtotime($endDate)) ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="15%">Kode Akun</th>
                <th width="55%">Nama Akun</th>
                <th width="30%">Nilai (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr><td colspan="3" class="title-row">I. PENDAPATAN USAHA</td></tr>
            <?php foreach ($groupedData['pendapatan'] as $row): ?>
            <tr>
                <td><?= esc($row['kode_akun']) ?></td>
                <td><?= esc($row['nama_akun']) ?></td>
                <td class="text-right"><?= number_format($row['net'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="bg-gray">
                <td colspan="2" class="font-bold">Subtotal Pendapatan</td>
                <td class="text-right font-bold"><?= number_format($totals['pendapatan'], 0, ',', '.') ?></td>
            </tr>

            <tr><td colspan="3" class="title-row">II. HARGA POKOK PENJUALAN</td></tr>
            <?php foreach ($groupedData['hpp'] as $row): ?>
            <tr>
                <td><?= esc($row['kode_akun']) ?></td>
                <td><?= esc($row['nama_akun']) ?></td>
                <td class="text-right">(<?= number_format($row['net'], 0, ',', '.') ?>)</td>
            </tr>
            <?php endforeach; ?>
            <tr class="bg-gray">
                <td colspan="2" class="font-bold">Subtotal HPP</td>
                <td class="text-right font-bold">(<?= number_format($totals['hpp'], 0, ',', '.') ?>)</td>
            </tr>

            <tr>
                <td colspan="2" class="font-bold" style="font-size:14px; padding-top:10px;">LABA KOTOR (GROSS PROFIT)</td>
                <td class="text-right font-bold" style="font-size:14px; padding-top:10px;"><?= number_format($grossProfit, 0, ',', '.') ?></td>
            </tr>

            <tr><td colspan="3" class="title-row">III. BEBAN OPERASIONAL</td></tr>
            <?php foreach ($groupedData['beban'] as $row): ?>
            <tr>
                <td><?= esc($row['kode_akun']) ?></td>
                <td><?= esc($row['nama_akun']) ?></td>
                <td class="text-right"><?= number_format($row['net'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="bg-gray">
                <td colspan="2" class="font-bold">Subtotal Beban Operasional</td>
                <td class="text-right font-bold">(<?= number_format($totals['beban'], 0, ',', '.') ?>)</td>
            </tr>

            <tr>
                <td colspan="2" class="font-bold" style="font-size:14px; padding-top:10px;">LABA OPERASIONAL</td>
                <td class="text-right font-bold" style="font-size:14px; padding-top:10px;"><?= number_format($operatingProfit, 0, ',', '.') ?></td>
            </tr>

            <tr><td colspan="3" class="title-row">IV. PENDAPATAN & BEBAN LAIN</td></tr>
            <?php foreach ($groupedData['pendapatan_lain'] as $row): ?>
            <tr>
                <td><?= esc($row['kode_akun']) ?></td>
                <td><?= esc($row['nama_akun']) ?></td>
                <td class="text-right"><?= number_format($row['net'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
            <?php foreach ($groupedData['beban_lain'] as $row): ?>
            <tr>
                <td><?= esc($row['kode_akun']) ?></td>
                <td><?= esc($row['nama_akun']) ?></td>
                <td class="text-right" style="color:red">(<?= number_format($row['net'], 0, ',', '.') ?>)</td>
            </tr>
            <?php endforeach; ?>

            <tr>
                <td colspan="2" class="font-bold" style="font-size:16px; padding-top:15px;">LABA BERSIH (NET PROFIT)</td>
                <td class="text-right font-bold" style="font-size:16px; padding-top:15px;"><?= number_format($netProfit, 0, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>
</body>
</html>