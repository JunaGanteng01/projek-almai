<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kegiatan Bulanan</title>
    <style>
        @page { size: 210mm 297mm; margin: 20px; }
        @page landscape_page { size: 297mm 210mm; margin: 20px; }
        .page-landscape { page: landscape_page; }
        
        body { font-family: sans-serif; font-size: 11px; color: #000; line-height: 1.4; }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-4 { margin-bottom: 1rem; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; margin-top: 5px; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background-color: #f0f0f0; text-align: center; }
        
        .header-title { font-size: 14px; font-weight: bold; margin: 0; text-align: center; }
        
        .section-title { font-weight: bold; margin-top: 20px; margin-bottom: 5px; }
        .catatan { font-size: 10px; margin-top: 5px; margin-bottom: 15px; }
        .catatan-desc { font-size: 10px; margin-top: 5px; margin-bottom: 10px; line-height: 1.5; }
        ul, ol { margin-top: 5px; margin-bottom: 5px; padding-left: 20px; }
        
        .signature-table { border: none; width: 100%; margin-top: 40px; }
        .signature-table td { border: none; text-align: center; width: 50%; padding-top: 20px; }
    </style>
</head>
<body>

    <div class="mb-4">
        <h1 class="header-title"><?= htmlspecialchars($namaPerusahaan) ?></h1>
        <h1 class="header-title">Laporan Kegiatan Bulanan</h1>
        <h1 class="header-title">Per <?= $bulanList[$bulan] ?? date('F', mktime(0, 0, 0, $bulan, 10)) ?> <?= $tahun ?></h1>
    </div>



    <!-- A. WPA -->
    <div class="section-title">a. Daftar Wakil Penasihat Berjangka (WPA):</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th>Nama Wakil Penasihat Berjangka</th>
                <th>Nomor Perizinan</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($listWpa)): ?>
                <?php foreach($listWpa as $i => $row): ?>
                <tr>
                    <td class="text-center"><?= $i+1 ?>.</td>
                    <td><?= htmlspecialchars($row['nama_wpa']) ?></td>
                    <td><?= htmlspecialchars($row['nomor_izin']) ?></td>
                    <td><?= htmlspecialchars($row['keterangan']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td class="text-center">1.</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <!-- B. KLIEN -->
    <div class="section-title">b. Jumlah Klien aktif sesuai jenis atau kategori Klien:</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th>Kategori Klien</th>
                <th>Jumlah Klien</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1.</td>
                <td class="text-center">Perorangan</td>
                <td class="text-center"><?= $klien['perorangan'] ?></td>
                <td><?= $klien['perorangan'] ?> Klien aktif (berbayar)</td>
            </tr>
            <tr>
                <td class="text-center">2.</td>
                <td class="text-center">Perusahaan</td>
                <td class="text-center"><?= $klien['perusahaan'] ?></td>
                <td><?= $klien['perusahaan_baru'] ?> Perusahaan baru bulan ini</td>
            </tr>
            <tr>
                <td class="text-center">3.</td>
                <td class="text-center">User</td>
                <td class="text-center"><?= $klien['total_aktif'] ?></td>
                <td><?= $klien['baru'] ?> User baru bulan ini</td>
            </tr>
        </tbody>
    </table>
    <!-- C. SEMINAR -->
    <div class="section-title">c. Kegiatan Seminar/Sosialisasi/Focus Group Discussion (FGD):</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th>Judul</th>
                <th>Jumlah Klien/Peserta</th>
                <th>Produk</th>
                <th>Nama WPA</th>
                <th>Lokasi</th>
                <th>Tanggal</th>
                <th>Topik Seminar</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($detailSeminar)): ?>
                <?php foreach($detailSeminar as $i => $row): ?>
                <tr>
                    <td class="text-center"><?= $i+1 ?>.</td>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td class="text-center"><?= $row['jml_peserta'] ?></td>
                    <td><?= htmlspecialchars($row['produk'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['nama_wpa'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['lokasi']) ?></td>
                    <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                    <td><?= htmlspecialchars($row['topik']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td class="text-center">1.</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                <tr><td class="text-center">2.</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <!-- D. PELATIHAN -->
    <div class="section-title">d. Kegiatan Pelatihan atau Simulasi Perdagangan:</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th>Judul</th>
                <th>Jumlah Klien</th>
                <th>Produk</th>
                <th>Nama WPA</th>
                <th>Lokasi</th>
                <th>Tanggal</th>
                <th>Topik Seminar</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($detailPelatihan)): ?>
                <?php foreach($detailPelatihan as $i => $row): ?>
                <tr>
                    <td class="text-center"><?= $i+1 ?>.</td>
                    <td><?= htmlspecialchars($row['judul']) ?></td>
                    <td class="text-center"><?= $row['jml_peserta'] ?></td>
                    <td><?= htmlspecialchars($row['produk'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['nama_wpa'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['lokasi']) ?></td>
                    <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                    <td><?= htmlspecialchars($row['topik']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td class="text-center">1.</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                <tr><td class="text-center">2.</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <!-- E. SIGNAL -->
    <div class="section-title">e. Kegiatan pemberian Signal:</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th>Jumlah Klien</th>
                <th>Jumlah Nasihat</th>
                <th>Produk</th>
                <th>Nama WPA</th>
                <th>Media</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($detailSignal)): ?>
                <?php foreach($detailSignal as $i => $row): ?>
                <tr>
                    <td class="text-center"><?= $i+1 ?>.</td>
                    <td class="text-center"><?= $row['jml_peserta'] ?></td>
                    <td class="text-center"><?= $row['jml_nasihat'] ?></td>
                    <td><?= htmlspecialchars($row['produk'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['nama_wpa'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['media'] ?? '') ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td class="text-center">1.</td><td></td><td></td><td></td><td></td><td></td></tr>
                <tr><td class="text-center">2.</td><td></td><td></td><td></td><td></td><td></td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <!-- F. KONSULTASI -->
    <div class="section-title">f. Kegiatan pemberian Konsultasi:</div>
    <table>
        <thead>
            <tr>
                <th width="5%">No.</th>
                <th>Nama Klien</th>
                <th>Jumlah Nasihat</th>
                <th>Produk</th>
                <th>Nama WPA</th>
                <th>Media</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($detailKonsultasi)): ?>
                <?php foreach($detailKonsultasi as $i => $row): ?>
                <tr>
                    <td class="text-center"><?= $i+1 ?>.</td>
                    <td><?= htmlspecialchars($row['nama_klien'] ?? '') ?></td>
                    <td class="text-center"><?= $row['jml_nasihat'] ?></td>
                    <td><?= htmlspecialchars($row['produk'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['nama_wpa'] ?? '') ?></td>
                    <td><?= htmlspecialchars($row['media'] ?? '') ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td class="text-center">1.</td><td></td><td></td><td></td><td></td><td></td></tr>
                <tr><td class="text-center">2.</td><td></td><td></td><td></td><td></td><td></td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <!-- G. Expert Advisor -->
    <div class="section-title">g. Kegiatan pelaksanaan penyampaian Nasihat berbasis teknologi informasi berupa <i>Expert Advisor</i>:</div>
    <table class="table">
        <thead>
            <tr>
                <th style="width: 5%">No.</th>
                <th>Nama Layanan</th>
                <th>Penjelasan terkait Layanan</th>
                <th>Jumlah Klien</th>
                <th>Produk</th>
                <th>Nama WPA</th>
                <th>Winning Rate</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($klien['expert_advisor'])): ?>
            <tr>
                <td colspan="7" class="text-center" style="font-style: italic; color: #666;">Tidak ada data untuk bulan ini</td>
            </tr>
            <?php else: ?>
            <?php $no = 1; foreach ($klien['expert_advisor'] as $ea): ?>
            <tr>
                <td class="text-center"><?= $no++ ?>.</td>
                <td><?= htmlspecialchars($ea['nama_layanan'] ?? '-') ?></td>
                <td><?= htmlspecialchars($ea['penjelasan_layanan'] ?? '-') ?></td>
                <td class="text-center"><?= htmlspecialchars($ea['jml_klien'] ?? '-') ?></td>
                <td class="text-center"><?= htmlspecialchars($ea['produk'] ?? '-') ?></td>
                <td class="text-center"><?= htmlspecialchars($ea['nama_wpa'] ?? '-') ?></td>
                <td class="text-center"><?= htmlspecialchars($ea['winning_rate'] ?? '-') ?></td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- H. Pedoman Perilaku -->
    <div class="page-landscape">
        <div class="section-title">h. Pelaksanaan pedoman perilaku:</div>
    <table class="table text-center" style="font-size: 7pt; table-layout: fixed; word-wrap: break-word; width: 100%;">
        <thead>
            <tr>
                <th rowspan="2" style="vertical-align: middle; width: 3%;">No.</th>
                <th rowspan="2" style="vertical-align: middle;">Nama Klien</th>
                <th rowspan="2" style="vertical-align: middle;">Nomor Akun</th>
                <th colspan="3">dokumen pernyataan adanya risiko</th>
                <th colspan="2">Dokumen Perjanjian Pemberian Jasa</th>
                <th colspan="1">Dokumen Keterangan Perusahaan</th>
                <th colspan="1">dokumen pernyataan adanya risiko</th>
            </tr>
            <tr>
                <th>Latar Belakang*</th>
                <th>Keadaan Keuangan*</th>
                <th>Profil Risiko*</th>
                <th>Apakah telah dilakukan penjelasan dan disetujui*</th>
                <th>Nomor Dokumen</th>
                <th>Apakah telah dilakukan penjelasan dan disetujui*</th>
                <th>Apakah telah dilakukan penjelasan dan disetujui*</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($klien['pedoman_perilaku'])): ?>
            <tr>
                <td colspan="10" style="font-style: italic; color: #666;">Tidak ada data untuk bulan ini</td>
            </tr>
            <?php else: ?>
            <?php $no = 1; foreach ($klien['pedoman_perilaku'] as $pp): ?>
            <tr>
                <td><?= $no++ ?>.</td>
                <td style="text-align: left;"><?= htmlspecialchars($pp['nama_klien'] ?? '-') ?></td>
                <td><?= htmlspecialchars($pp['nomor_akun'] ?? '-') ?></td>
                <td><?= !empty($pp['latar_belakang']) ? '<span style="font-family: DejaVu Sans, sans-serif;">&#10003;</span>' : '-' ?></td>
                <td><?= !empty($pp['keadaan_keuangan']) ? '<span style="font-family: DejaVu Sans, sans-serif;">&#10003;</span>' : '-' ?></td>
                <td><?= !empty($pp['profil_risiko']) ? '<span style="font-family: DejaVu Sans, sans-serif;">&#10003;</span>' : '-' ?></td>
                <td><?= (!empty($pp['jasa_penjelasan']) || !empty($pp['jasa_disetujui'])) ? '<span style="font-family: DejaVu Sans, sans-serif;">&#10003;</span>' : '-' ?></td>
                <td><?= htmlspecialchars($pp['nomor_dokumen'] ?? '-') ?></td>
                <td><?= (!empty($pp['ket_penjelasan']) || !empty($pp['ket_disetujui'])) ? '<span style="font-family: DejaVu Sans, sans-serif;">&#10003;</span>' : '-' ?></td>
                <td><?= (!empty($pp['risiko_penjelasan']) || !empty($pp['risiko_disetujui'])) ? '<span style="font-family: DejaVu Sans, sans-serif;">&#10003;</span>' : '-' ?></td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <table class="signature-table" style="page-break-inside: avoid;">
        <tr>
            <td style="vertical-align: bottom; padding-top: 10px;">
                <div style="margin-bottom: 5px;">Dibuat Oleh,</div>
                <div style="font-size: 10px; color: #555; margin-bottom: 5px;"><?= $tanggalDibuat ?></div>
                <div style="height: 50px; margin-bottom: 5px;"></div>
                <div>( <strong>Svylliva Elvira Valreine Polii</strong> )</div>
                <div>SPI</div>
            </td>
            <td style="vertical-align: bottom; padding-top: 10px;">
                <div style="margin-bottom: 5px;">Diketahui Oleh,</div>
                <div style="font-size: 10px; color: #555; margin-bottom: 5px;"><?= $tanggalDibuat ?></div>
                <div style="height: 50px; margin-bottom: 5px;"></div>
                <div>( <strong><?= htmlspecialchars(!empty($direktur) ? $direktur : 'Nama Direktur') ?></strong> )</div>
                <div>Direktur Utama</div>
            </td>
        </tr>
    </table>
    </div>

</body>
</html>
