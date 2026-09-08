<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Arus Kas</title>
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
        .title-row td { font-weight: bold; font-size: 13px; text-transform: uppercase; background: #fff; border:none; border-bottom: 1px solid #000; padding-top:15px;}
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN ARUS KAS (CASH FLOW)</h1>
        <h2 style="margin: 5px 0; font-size: 14px;">PT. ALMA INDONESIA RAYA</h2>
        <p>Periode: <?= date('d/m/Y', strtotime($startDate)) ?> s/d <?= date('d/m/Y', strtotime($endDate)) ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="70%">Aktivitas</th>
                <th width="30%">Nilai (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr><td colspan="2" class="title-row">I. Aktivitas Operasional</td></tr>
            <?php foreach ($cashData['operasional'] as $item): ?>
            <tr>
                <td><?= $item['nama'] ?></td>
                <td class="text-right"><?= number_format($item['total'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="bg-gray font-bold">
                <td>Total Arus Kas dari Aktivitas Operasional</td>
                <td class="text-right"><?= number_format($totalOperasional, 0, ',', '.') ?></td>
            </tr>

            <tr><td colspan="2" class="title-row">II. Aktivitas Investasi</td></tr>
            <?php foreach ($cashData['investasi'] as $item): ?>
            <tr>
                <td><?= $item['nama'] ?></td>
                <td class="text-right"><?= number_format($item['total'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="bg-gray font-bold">
                <td>Total Arus Kas dari Aktivitas Investasi</td>
                <td class="text-right"><?= number_format($totalInvestasi, 0, ',', '.') ?></td>
            </tr>

            <tr><td colspan="2" class="title-row">III. Aktivitas Pendanaan</td></tr>
            <?php foreach ($cashData['pendanaan'] as $item): ?>
            <tr>
                <td><?= $item['nama'] ?></td>
                <td class="text-right"><?= number_format($item['total'], 0, ',', '.') ?></td>
            </tr>
            <?php endforeach; ?>
            <tr class="bg-gray font-bold">
                <td>Total Arus Kas dari Aktivitas Pendanaan</td>
                <td class="text-right"><?= number_format($totalPendanaan, 0, ',', '.') ?></td>
            </tr>

            <tr><td colspan="2" class="title-row" style="text-align:center; background:#f0f0f0;">REKAPITULASI</td></tr>
            <tr>
                <td>Kas & Setara Kas (Awal Periode)</td>
                <td class="text-right font-bold"><?= number_format($openingBalance, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td>Perubahan Kas Neto</td>
                <td class="text-right font-bold"><?= number_format($netChange, 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td class="font-bold" style="font-size:14px; text-transform:uppercase;">Kas & Setara Kas (Akhir Periode)</td>
                <td class="text-right font-bold" style="font-size:14px;"><?= number_format($openingBalance + $netChange, 0, ',', '.') ?></td>
            </tr>
        </tbody>
    </table>
</body>
</html>