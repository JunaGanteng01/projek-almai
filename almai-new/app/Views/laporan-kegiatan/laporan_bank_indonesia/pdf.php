<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($title) ?></title>
    <style>
        @page { size: 297mm 210mm; margin: 20px; }
        
        body { font-family: sans-serif; font-size: 11px; color: #000; line-height: 1.4; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-4 { margin-bottom: 1rem; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background-color: #f0f0f0; text-align: center; font-weight: bold; }
        
        .header-title { font-size: 16px; font-weight: bold; margin: 0; text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>

    <h1 class="header-title">Laporan Daftar Wakil Penasihat Derivatif PUVA dan Kepemilikan Sertifikasi Kompetensi</h1>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Jabatan</th>
                <th>Tanggal Menjabat</th>
                <th>Nomor WPA Bank Indonesia</th>
                <th>Nomor Sertifikat</th>
                <th>Tanggal Kadaluarsa Sertifikat</th>
                <th>Penyelenggara Sertifikasi</th>
                <th>Nomor Anggota Asosiasi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($wpaList)): ?>
            <tr>
                <td colspan="9" class="text-center" style="font-style: italic; color: #666;">Belum ada data laporan Bank Indonesia</td>
            </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($wpaList as $wpa): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td><?= htmlspecialchars($wpa['name'] ?? '-') ?></td>
                    <td class="text-center"><?= htmlspecialchars($wpa['jabatan'] ?? '-') ?></td>
                    <td class="text-center"><?= (!empty($wpa['tanggal_menjabat']) && $wpa['tanggal_menjabat'] !== '0000-00-00') ? date('d-m-Y', strtotime($wpa['tanggal_menjabat'])) : '-' ?></td>
                    <td class="text-center"><?= htmlspecialchars($wpa['no_sertifikat_bi'] ?? '-') ?></td>
                    <td class="text-center"><?= htmlspecialchars($wpa['nomor_izin_wpa'] ?? '-') ?></td>
                    <td class="text-center"><?= (!empty($wpa['masa_berlaku']) && $wpa['masa_berlaku'] !== '0000-00-00') ? date('d-m-Y', strtotime($wpa['masa_berlaku'])) : '-' ?></td>
                    <td class="text-center">Bappebti / Aspebtindo</td>
                    <td class="text-center"><?= htmlspecialchars($wpa['no_sertifikat_bnsp'] ?? $wpa['no_sertifikat_aspebtindo'] ?? '-') ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
