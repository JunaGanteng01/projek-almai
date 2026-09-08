<!DOCTYPE html>
<html>
<head>
    <title>Rekap Laporan Tahunan</title>
    <style>
        @page { size: A4 landscape; margin: 40px; }
        body { font-family: sans-serif; font-size: 11px; color: #333; line-height: 1.5; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-4 { margin-bottom: 1rem; }
        .mb-8 { margin-bottom: 2rem; }
        .mt-8 { margin-top: 2rem; }
        .w-full { width: 100%; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 1rem; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background-color: #f4f4f4; text-align: center; font-weight: bold; font-size: 10px; }
        
        .header-title { font-size: 18px; font-weight: bold; margin: 0; }
        .header-subtitle { font-size: 14px; margin: 0 0 20px 0; color: #555; }
        
        .info-table { border: none; margin-bottom: 20px; width: auto; }
        .info-table td { border: none; padding: 4px 8px 4px 0; }
        
        .signature-table { border: none; width: 100%; margin-top: 40px; }
        .signature-table td { border: none; text-align: center; width: 50%; padding-top: 20px; }
    </style>
</head>
<body>

    <div class="text-center mb-8">
        <h1 class="header-title">REKAP LAPORAN KEGIATAN TAHUNAN</h1>
        <p class="header-subtitle">Penasihat Berjangka | Peraturan Bappebti No. 7 Tahun 2025</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="150">Nama Perusahaan</td>
            <td width="20">:</td>
            <td class="font-bold"><?= htmlspecialchars($namaPerusahaan) ?></td>
        </tr>
        <tr>
            <td>No Izin</td>
            <td>:</td>
            <td class="font-bold"><?= htmlspecialchars($noIzin) ?></td>
        </tr>
        <tr>
            <td>Periode</td>
            <td>:</td>
            <td class="font-bold">Januari - Desember <?= $tahun ?></td>
        </tr>
        <tr>
            <td>Tanggal Dibuat</td>
            <td>:</td>
            <td class="font-bold"><?= $tanggalDibuat ?></td>
        </tr>
    </table>

    <h3 class="mb-2">Rekap Kegiatan</h3>
    <table>
        <thead>
            <tr>
                <th style="text-align: left;">Jenis Kegiatan</th>
                <?php 
                $bulanList = ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
                foreach($bulanList as $b): 
                ?>
                    <th width="30"><?= substr($b, 0, 3) ?></th>
                <?php endforeach; ?>
                <th width="50">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $types = [
                'seminar' => 'Seminar/Sosialisasi/FGD',
                'pelatihan' => 'Pelatihan/Simulasi Perdagangan',
                'signal' => 'Pemberian Signal',
                'konsultasi' => 'Pemberian Konsultasi',
                'ea' => 'Expert Advisor (EA)',
                'lainnya' => 'Kegiatan Lainnya'
            ];
            foreach($types as $key => $label): 
                $rowTotal = 0;
            ?>
            <tr>
                <td class="font-bold"><?= $label ?></td>
                <?php for($i=1; $i<=12; $i++): 
                    $b = str_pad($i, 2, '0', STR_PAD_LEFT);
                    $val = $rekapTahunan[$b][$key]['kegiatan'];
                    $rowTotal += $val;
                ?>
                    <td class="text-center"><?= $val ?: '-' ?></td>
                <?php endfor; ?>
                <td class="text-center font-bold" style="background-color: #f9f9f9;"><?= $rowTotal ?></td>
            </tr>
            <?php endforeach; ?>
            
            <!-- Klien Baru -->
            <tr>
                <td class="font-bold">Klien Baru</td>
                <?php 
                $totBaru = 0;
                for($i=1; $i<=12; $i++): 
                    $b = str_pad($i, 2, '0', STR_PAD_LEFT);
                    $val = $klienBaru[$b];
                    $totBaru += $val;
                ?>
                    <td class="text-center"><?= $val ?: '-' ?></td>
                <?php endfor; ?>
                <td class="text-center font-bold" style="background-color: #f9f9f9;"><?= $totBaru ?></td>
            </tr>

            <!-- Total Klien Aktif -->
            <tr style="background-color: #f4f4f4;">
                <td colspan="13" class="font-bold">Total Klien Aktif (Hingga Saat Ini)</td>
                <td class="text-center font-bold"><?= $klienAktif ?></td>
            </tr>
        </tbody>
    </table>

    <h3 class="mb-2 mt-8">Tanda Tangan dan Pengesahan</h3>
    <table class="signature-table">
        <tr>
            <td style="vertical-align: bottom; padding-top: 10px;">
                <div style="margin-bottom: 5px;">Dibuat Oleh,</div>
                <div style="font-size: 10px; color: #555; margin-bottom: 5px;"><?= $tanggalDibuat ?></div>
                <div style="height: 50px; margin-bottom: 5px;"></div>
                <div>( .................................... )</div>
                <div>Nama & Jabatan</div>
            </td>
            <td style="vertical-align: bottom; padding-top: 10px;">
                <div style="margin-bottom: 5px;">Diketahui Oleh,</div>
                <div style="font-size: 10px; color: #555; margin-bottom: 5px;"><?= $tanggalDibuat ?></div>
                <div style="height: 50px; margin-bottom: 5px;">

                </div>
                <div>( <strong><?= htmlspecialchars(!empty($direktur) ? $direktur : 'Nama Direktur') ?></strong> )</div>
                <div>Direktur Utama</div>
            </td>
        </tr>
    </table>

</body>
</html>
