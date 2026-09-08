<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Regulasi</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 10px; color: #333; margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 20px; line-height: 1.5; }
        .header h1 { font-size: 16px; margin: 0 0 5px 0; padding: 0; text-transform: uppercase; }
        .header h2 { font-size: 14px; margin: 0 0 5px 0; padding: 0; }
        .header p { margin: 0 0 2px 0; padding: 0; font-size: 12px; }
        .section-title { font-size: 12px; font-weight: bold; margin-top: 20px; margin-bottom: 10px; text-transform: uppercase; background: #eee; padding: 5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; vertical-align: top; }
        th { background-color: #f5f5f5; font-weight: bold; }
        .text-center { text-align: center; }
    </style>
</head>
<body>

<div class="header">
    <h1>Laporan Regulasi <?= esc($monthName) ?> <?= esc($selectedYear) ?></h1>
    <h2>PT. Alma Indonesia Raya</h2>
    <p>Jl. Badak Agung No. 22 Kav. 3, Renon, Denpasar - Bali. 80226</p>
    <p>Telf/Fax : 03613610019</p>
    <p>Chat Support : 085183231800, 085183390019</p>
</div>

<!-- Daftar WPA -->
<div class="section-title">Daftar WPA</div>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Penasihat Berjangka</th>
            <th>Nama WPA</th>
            <th>NIK WPA</th>
            <th>Nomor Izin WPA</th>
            <th>Tanggal Pemberian Izin WPA</th>
            <th>No. Sertifikat ASPEBTINDO</th>
            <th>No. Sertifikat Bank Indonesia</th>
            <th>No. Sertifikat Aspebtindo/BNSP</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($wpaList)) : ?>
            <tr>
                <td colspan="9" class="text-center">Data belum tersedia</td>
            </tr>
        <?php else : ?>
            <?php foreach ($wpaList as $index => $wpa) : ?>
                <tr>
                    <td class="text-center"><?= $index + 1 ?></td>
                    <td>PT. Alma Indonesia Raya</td>
                    <td><?= esc($wpa['name']) ?></td>
                    <td><?= esc($wpa['nik_wpa'] ?? '-') ?></td>
                    <td><?= esc($wpa['nomor_izin_wpa'] ?? '-') ?></td>
                    <td><?= esc($wpa['tanggal_izin_wpa'] ?? '-') ?></td>
                    <td><?= esc($wpa['no_sertifikat_aspebtindo'] ?? '-') ?></td>
                    <td><?= esc($wpa['no_sertifikat_bi'] ?? '-') ?></td>
                    <td><?= esc($wpa['no_sertifikat_bnsp'] ?? '-') ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<!-- Jumlah Klien -->
<div class="section-title">Jumlah Klien</div>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kategori Klien</th>
            <th>Masalah & Mitigasi</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="4" class="text-center">Data belum tersedia</td>
        </tr>
    </tbody>
</table>

<!-- A. KEGIATAN SEMINAR/SOSIALISASI/FOCUS GROUP DISCUSSION (FGD) -->
<div class="section-title">A. KEGIATAN SEMINAR/SOSIALISASI/FOCUS GROUP DISCUSSION (FGD)</div>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Jumlah Klien</th>
            <th>Jumlah Peserta (Non-Klien)</th>
            <th>Produk</th>
            <th>Nama WPA</th>
            <th>Lokasi</th>
            <th>Topik</th>
            <th>Tanggal Kegiatan</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($kegiatanSeminar)) : ?>
            <tr>
                <td colspan="9" class="text-center">Data belum tersedia</td>
            </tr>
        <?php else : ?>
            <?php foreach ($kegiatanSeminar as $index => $event) : ?>
                <?php
                    $tanggalKegiatan = '-';
                    if ($event['is_recurring']) {
                        $hari = [
                            'monday' => 'Senin', 'tuesday' => 'Selasa', 'wednesday' => 'Rabu',
                            'thursday' => 'Kamis', 'friday' => 'Jumat', 'saturday' => 'Sabtu', 'sunday' => 'Minggu'
                        ];
                        $dayIndo = $hari[strtolower($event['recurring_day'])] ?? $event['recurring_day'];
                        $tanggalKegiatan = 'Setiap ' . $dayIndo . ' (' . substr($event['recurring_time'], 0, 5) . ' WIB)';
                    } else {
                        $tanggalKegiatan = date('d M Y H:i', strtotime($event['event_date'])) . ' WIB';
                    }
                ?>
                <tr>
                    <td class="text-center"><?= $index + 1 ?></td>
                    <td><?= esc($event['title']) ?></td>
                    <td class="text-center"><?= esc($event['jumlah_klien']) ?></td>
                    <td class="text-center">0</td>
                    <td><?= esc($event['specialist'] ?? '-') ?></td>
                    <td><?= esc($event['wpa_name'] ?? '-') ?></td>
                    <td><?= esc($event['location'] ?? '-') ?></td>
                    <td><?= esc($event['layanan_utama'] ?? '-') ?></td>
                    <td><?= $tanggalKegiatan ?></td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<!-- B. KEGIATAN PELATIHAN ATAU SIMULASI PERDAGANGAN -->
<div class="section-title">B. KEGIATAN PELATIHAN ATAU SIMULASI PERDAGANGAN</div>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Judul</th>
            <th>Jumlah Klien</th>
            <th>Produk</th>
            <th>Nama WPA</th>
            <th>Lokasi</th>
            <th>Topik</th>
            <th>Tanggal Pelatihan</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="8" class="text-center">Data belum tersedia</td>
        </tr>
    </tbody>
</table>

<!-- C. KEGIATAN PEMBERIAN SIGNAL -->
<div class="section-title">C. KEGIATAN PEMBERIAN SIGNAL</div>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Jumlah Klien</th>
            <th>Jumlah Nasihat</th>
            <th>Produk</th>
            <th>Nama WPA</th>
            <th>Media</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="6" class="text-center">Data belum tersedia</td>
        </tr>
    </tbody>
</table>

<!-- D. KEGIATAN PEMBERIAN KONSULTASI -->
<div class="section-title">D. KEGIATAN PEMBERIAN KONSULTASI</div>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Klien</th>
            <th>Jumlah Nasihat</th>
            <th>Produk</th>
            <th>Media</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="5" class="text-center">Data belum tersedia</td>
        </tr>
    </tbody>
</table>

<!-- E. PELAKSANAAN PENYAMPAIAN NASIHAT BERBASIS TEKNOLOGI INFORMASI BERUPA EXPERT ADVISOR -->
<div class="section-title">E. PELAKSANAAN PENYAMPAIAN NASIHAT BERBASIS TEKNOLOGI INFORMASI BERUPA EXPERT ADVISOR</div>
<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Layanan</th>
            <th>Penjelasan Terkait Layanan</th>
            <th>Jumlah Klien</th>
            <th>Produk</th>
            <th>Nama WPA</th>
            <th>Winning Rate</th>
            <th>Masalah & Mitigasi</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="8" class="text-center">Data belum tersedia</td>
        </tr>
    </tbody>
</table>

</body>
</html>
