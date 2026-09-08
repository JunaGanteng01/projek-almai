<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Absensi Peserta</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 5px 0 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; font-weight: bold; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .footer { text-align: right; font-size: 10px; color: #666; margin-top: 30px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Absensi Peserta</h1>
        <p>Dicetak pada: <?= date('d M Y H:i') ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 30px;">No</th>
                <th>Nama Peserta</th>
                <th>Email</th>
                <th>No. HP</th>
                <th>Jenis Kegiatan</th>
                <th>Nama Kegiatan</th>
                <th class="text-center">Poin</th>
                <th class="text-center">Waktu Check-in</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($usersList)): ?>
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data absensi ditemukan</td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($usersList as $row): ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= esc($row['name'] ?? 'Unknown') ?></td>
                        <td><?= esc($row['email'] ?? '-') ?></td>
                        <td><?= esc($row['no_hp'] ?? '-') ?></td>
                        <td><?= esc(str_replace('_', ' ', $row['kegiatan_type'])) ?></td>
                        <td><?= esc($row['kegiatan_name'] ?? '-') ?></td>
                        <td class="text-center">+<?= esc($row['poin_awarded']) ?></td>
                        <td class="text-center"><?= date('d M Y H:i', strtotime($row['created_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        Dicetak oleh Sistem ALMA WPA
    </div>

</body>
</html>
