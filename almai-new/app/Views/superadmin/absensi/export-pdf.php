<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export PDF - <?= esc($kegiatan['nama'] ?? 'Kegiatan') ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #33e818;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 20px;
            color: #111;
            margin-bottom: 5px;
        }

        .header p {
            color: #666;
            font-size: 14px;
            font-weight: bold;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 5px;
            vertical-align: top;
        }

        .info-table td.label {
            font-weight: bold;
            width: 150px;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #ddd;
            padding: 8px 10px;
            text-align: left;
        }

        table.data-table th {
            background: #111;
            color: #fff;
            font-weight: 600;
            font-size: 11px;
        }

        table.data-table tr:nth-child(even) {
            background: #f9f9f9;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }

        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #33e818; color: #000; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">
            <i class="fas fa-print"></i> Print / Save PDF
        </button>
    </div>

    <div class="header">
        <h1>Absensi Kegiatan</h1>
        <p>PT. Alma Indonesia Raya</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Kegiatan</td>
            <td>: <?= esc($kegiatan['nama'] ?? '-') ?></td>
        </tr>
        <tr>
            <td class="label">WPA</td>
            <td>: <?= esc($kegiatan['wpa_name'] ?? '-') ?></td>
        </tr>
        <tr>
            <td class="label">CWPA</td>
            <td>: <?= esc($kegiatan['cwpa_name'] ?? '-') ?></td>
        </tr>
        <tr>
            <td class="label">Tanggal</td>
            <td>: <?= !empty($kegiatan['tanggal']) ? date('d M Y', strtotime($kegiatan['tanggal'])) : '-' ?></td>
        </tr>
        <tr>
            <td class="label">Produk</td>
            <td>: <?= esc($kegiatan['produk'] ?? '-') ?></td>
        </tr>
        <tr>
            <td class="label">Lokasi</td>
            <td>: <?= esc($kegiatan['lokasi'] ?? $kegiatan['media'] ?? '-') ?></td>
        </tr>
        <tr>
            <td class="label">Topik</td>
            <td>: <?= esc($kegiatan['topik'] ?? $kegiatan['penjelasan_layanan'] ?? '-') ?></td>
        </tr>
    </table>

    <div class="section-title">Daftar Peserta Absen</div>
    
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 40px; text-align: center;">No</th>
                <th>Nama Peserta</th>
                <th>Email</th>
                <th>No WhatsApp</th>
                <th>Referral</th>
                <th>Waktu Absen</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($peserta)): ?>
                <tr>
                    <td colspan="4" style="text-align: center; padding: 20px; color: #999;">Belum ada peserta yang melakukan absensi</td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($peserta as $p): ?>
                    <tr>
                        <td style="text-align: center;"><?= $no++ ?></td>
                        <td><?= esc($p['user_name']) ?></td>
                        <td><?= esc($p['email']) ?></td>
                        <td><?= esc($p['phone'] ?? '-') ?></td>
                        <td><?= esc($p['referrer_name'] ?? $p['affiliator_code'] ?? '-') ?></td>
                        <td><?= date('d M Y, H:i', strtotime($p['created_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis dari sistem WPA Platform</p>
        <p>&copy; <?= date('Y') ?> PT. Alma Indonesia Raya - All Rights Reserved</p>
    </div>
</body>
</html>
