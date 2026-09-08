<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Perubahan Modal</title>
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
        <h1>LAPORAN PERUBAHAN MODAL</h1>
        <h2 style="margin: 5px 0; font-size: 14px;">PT. ALMA INDONESIA RAYA</h2>
        <p>Periode: <?= date('d/m/Y', strtotime($startDate)) ?> s/d <?= date('d/m/Y', strtotime($endDate)) ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="40%">Keterangan Akun</th>
                <th width="20%">Saldo Awal (Rp)</th>
                <th width="20%">Perubahan (Rp)</th>
                <th width="20%">Saldo Akhir (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($equityData)): ?>
            <tr><td colspan="4" class="text-center">Belum ada data ekuitas</td></tr>
            <?php else: ?>
                <?php 
                $totalStart = 0; $totalEnd = 0;
                foreach ($equityData as $equity): 
                    $totalStart += $equity['total'];
                    $totalEnd += $equity['total'];
                ?>
                <tr>
                    <td><?= esc($equity['nama_akun']) ?> (<?= esc($equity['kode_akun']) ?>)</td>
                    <td class="text-right"><?= number_format($equity['total'], 0, ',', '.') ?></td>
                    <td class="text-right">0</td>
                    <td class="text-right font-bold"><?= number_format($equity['total'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
                
                <tr>
                    <td>Laba Bersih Tahun Berjalan</td>
                    <td class="text-right">0</td>
                    <td class="text-right"><?= number_format($labaBerjalan, 0, ',', '.') ?></td>
                    <td class="text-right font-bold"><?= number_format($labaBerjalan, 0, ',', '.') ?></td>
                </tr>
                
                <tr class="bg-gray">
                    <td class="font-bold text-center" style="font-size:14px; text-transform:uppercase;">Total Ekuitas</td>
                    <td class="text-right font-bold"><?= number_format($totalStart, 0, ',', '.') ?></td>
                    <td class="text-right font-bold"><?= number_format($labaBerjalan, 0, ',', '.') ?></td>
                    <td class="text-right font-bold" style="font-size:14px;"><?= number_format($totalEnd + $labaBerjalan, 0, ',', '.') ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>