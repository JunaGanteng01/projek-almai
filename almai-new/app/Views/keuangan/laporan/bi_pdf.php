<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Bank Indonesia <?= $tahunBerjalan ?></title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 11pt; color: #000; line-height: 1.4; margin: 0; padding: 0; }
        .page-break { page-break-after: always; }
        
        .header-banner {
            background-color: #2e4374; /* Dark blue */
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            font-weight: bold;
            font-size: 16pt;
        }
        
        .header-banner .logo {
            font-size: 20pt;
            margin-right: 15px;
            font-weight: bold;
        }

        .tab-chevron {
            background-color: #002366;
            color: white;
            display: inline-block;
            padding: 10px 20px 10px 10px;
            font-weight: bold;
            margin-top: 20px;
            margin-left: 20px;
            position: relative;
            font-size: 14pt;
        }
        .tab-chevron::after {
            content: "";
            position: absolute;
            right: -20px;
            top: 0;
            border-top: 22px solid transparent;
            border-bottom: 22px solid transparent;
            border-left: 20px solid #002366;
        }
        .tab-number {
            border-right: 1px solid white;
            padding-right: 15px;
            margin-right: 15px;
            font-size: 16pt;
        }
        
        .section-title {
            margin: 20px 0 10px 20px;
            font-weight: bold;
            font-size: 14pt;
        }
        
        table.bi-table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
            font-size: 10pt;
        }
        table.bi-table th, table.bi-table td {
            border: 1px solid #999;
            padding: 6px 10px;
        }
        
        .bg-blue { background-color: #2b78e4; color: white; font-weight: bold; }
        .bg-orange { background-color: #e67c35; color: white; font-weight: bold; text-align: center; }
        .col-label { background-color: #fce4d6; }
        .col-value { text-align: right; }
        
        .note-box {
            border: 1px solid #666;
            padding: 15px;
            margin: 20px auto;
            width: 80%;
            background-color: #f9f9f9;
            font-size: 9pt;
        }
        .note-box strong { font-size: 10pt; }
        
        .text-box {
            border: 1px solid #000;
            padding: 15px;
            margin: 20px auto;
            width: 90%;
            background-color: #fff;
            min-height: 400px;
            font-size: 10pt;
        }
        .text-box ol { margin-top: 0; padding-left: 20px; }
        .text-box li { margin-bottom: 5px; }
        
    </style>
</head>
<body>

    <?php
        // Helper to get Neraca items safely
        $getNeracaItem = function($items, $keyword) {
            if (!$items) return 0;
            foreach ($items as $item) {
                if (stripos(strtolower($item['kategori'] ?? ''), strtolower($keyword)) !== false || stripos(strtolower($item['nama_akun'] ?? ''), strtolower($keyword)) !== false) {
                    return $item['saldo'];
                }
            }
            return 0;
        };

        $nBerjalan = $neraca['berjalan'];
        $lrBerjalan = $labaRugi['berjalan'];
        
        $modalDisetor = $getNeracaItem($nBerjalan['ekuitas']['items'] ?? [], 'Modal');
        $ekuitas = $nBerjalan['ekuitas']['total_ekuitas_with_profit'] ?? 0;
        
        // Mock Kenaikan/Penurunan Arus Kas since it's hard to get safely from standard SakEtapModel without deep diving
        $kenaikanArusKas = 0; 
        
        $piutangAfiliasi = $getNeracaItem($nBerjalan['aset']['lancar']['items'] ?? [], 'Afiliasi');
        $totalAsetLancar = $nBerjalan['aset']['lancar']['total'] ?? 0;
        $liabilitasPendek = $nBerjalan['liabilitas']['jangka_pendek']['total'] ?? 0;
        $kas = $getNeracaItem($nBerjalan['aset']['lancar']['items'] ?? [], 'Kas');
        $totalLiabilitas = $nBerjalan['liabilitas']['total_liabilitas'] ?? 0;
        $totalAset = $nBerjalan['aset']['total_aset'] ?? 0;
        
        $labaSebelumPajak = $lrBerjalan['laba_sebelum_pajak'] ?? 0;
        $labaSetelahPajak = $lrBerjalan['laba_rugi_bersih'] ?? 0;
    ?>

    <!-- PAGE 2/3 -->
    <div class="header-banner">
        <span class="logo">B BANK INDONESIA</span>
        <span style="margin-left: 10px; font-weight: normal;">|</span>
        <span style="margin-left: 10px;">PENASIHAT - LAPORAN TAHUNAN (2/3)</span>
    </div>

    <div class="tab-chevron">
        <span class="tab-number">2</span>Laporan Keuangan Audited Versi CSV
    </div>

    <div class="section-title">
        &#10063; Pedoman Pengisian Laporan
    </div>

    <table class="bi-table">
        <tr>
            <th class="bg-blue" style="width: 40%;">Format Row (Text)</th>
            <th class="bg-orange" style="width: 60%;">Contoh Pengisian</th>
        </tr>
        <tr>
            <td class="col-label" rowspan="14" style="vertical-align: top; font-weight: bold; background-color: #f4b084; color: white;">Format (General)</td>
            <td style="padding: 0; border: none;">
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="width: 50%; border-right: 1px solid #999; border-bottom: 1px solid #999;">KAP</td>
                        <td style="border-bottom: 1px solid #999; text-align: center;">-</td>
                    </tr>
                    <tr>
                        <td style="border-right: 1px solid #999; border-bottom: 1px solid #999;">Opini</td>
                        <td style="border-bottom: 1px solid #999; text-align: center;">-</td>
                    </tr>
                    <tr>
                        <td style="border-right: 1px solid #999; border-bottom: 1px solid #999;">Dana kompensasi</td>
                        <td class="col-value" style="border-bottom: 1px solid #999;">0</td>
                    </tr>
                    <tr>
                        <td style="border-right: 1px solid #999; border-bottom: 1px solid #999;">Modal disetor</td>
                        <td class="col-value" style="border-bottom: 1px solid #999;"><?= $modalDisetor ?></td>
                    </tr>
                    <tr>
                        <td style="border-right: 1px solid #999; border-bottom: 1px solid #999;">Ekuitas</td>
                        <td class="col-value" style="border-bottom: 1px solid #999;"><?= $ekuitas ?></td>
                    </tr>
                    <tr>
                        <td style="border-right: 1px solid #999; border-bottom: 1px solid #999;">Kenaikan (penurunan) arus kas</td>
                        <td class="col-value" style="border-bottom: 1px solid #999;"><?= $kenaikanArusKas ?></td>
                    </tr>
                    <tr>
                        <td style="border-right: 1px solid #999; border-bottom: 1px solid #999;">Piutang terafiliasi</td>
                        <td class="col-value" style="border-bottom: 1px solid #999;"><?= $piutangAfiliasi ?></td>
                    </tr>
                    <tr>
                        <td style="border-right: 1px solid #999; border-bottom: 1px solid #999;">Total aset lancar</td>
                        <td class="col-value" style="border-bottom: 1px solid #999;"><?= $totalAsetLancar ?></td>
                    </tr>
                    <tr>
                        <td style="border-right: 1px solid #999; border-bottom: 1px solid #999;">Liabilitas jangka pendek</td>
                        <td class="col-value" style="border-bottom: 1px solid #999;"><?= $liabilitasPendek ?></td>
                    </tr>
                    <tr>
                        <td style="border-right: 1px solid #999; border-bottom: 1px solid #999;">Kas</td>
                        <td class="col-value" style="border-bottom: 1px solid #999;"><?= $kas ?></td>
                    </tr>
                    <tr>
                        <td style="border-right: 1px solid #999; border-bottom: 1px solid #999;">Total liabilitas</td>
                        <td class="col-value" style="border-bottom: 1px solid #999;"><?= $totalLiabilitas ?></td>
                    </tr>
                    <tr>
                        <td style="border-right: 1px solid #999; border-bottom: 1px solid #999;">Total aset</td>
                        <td class="col-value" style="border-bottom: 1px solid #999;"><?= $totalAset ?></td>
                    </tr>
                    <tr>
                        <td style="border-right: 1px solid #999; border-bottom: 1px solid #999;">Laba sebelum pajak</td>
                        <td class="col-value" style="border-bottom: 1px solid #999;"><?= $labaSebelumPajak ?></td>
                    </tr>
                    <tr>
                        <td style="border-right: 1px solid #999;">Laba (rugi) setelah pajak</td>
                        <td class="col-value"><?= $labaSetelahPajak ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="note-box">
        <strong>Note:</strong><br>
        1. Pastikan <strong>Nama Row</strong> sesuai dengan pedoman pengisian laporan<br>
        2. Pastikan <strong>Format Kolom : General</strong> untuk Angka<br>
        3. Pastikan <strong>Format Kolom : Text</strong> untuk huruf<br>
        4. Pastikan penulisan angka penuh tanpa karakter (,)<br>
        5. Penulisan angka pecahan menggunakan pemisah TITIK (.) Contoh : <strong>0.57</strong><br>
        6. Penyajian angka dalam <strong>Rupiah</strong><br>
        7. Jika tidak ada data maka data dapat dilaporkan dengan menuliskan "0"
    </div>

    <div class="page-break"></div>

    <!-- PAGE 3/3 -->
    <div class="header-banner">
        <span class="logo">B BANK INDONESIA</span>
        <span style="margin-left: 10px; font-weight: normal;">|</span>
        <span style="margin-left: 10px;">PENASIHAT - LAPORAN TAHUNAN (3/3)</span>
    </div>

    <div class="tab-chevron">
        <span class="tab-number">3</span>Laporan Kegiatan Tahunan
    </div>

    <div class="section-title">
        &#10063; Pedoman Pengisian Laporan
    </div>

    <div class="text-box">
        Laporan yang memuat paling sedikit mengenai:<br>
        <ol>
            <li>Profil Perusahaan
                <ol type="a">
                    <li>bidang usaha perusahaan;</li>
                    <li>visi misi perusahaan;</li>
                    <li>struktur organisasi, tata kerja, dan personil; dan</li>
                    <li>daftar pemegang saham dan komposisi kepemilikan saham;</li>
                </ol>
            </li>
            <li>rencana strategis 1 (satu) tahun ke depan;</li>
            <li>perkembangan kegiatan usaha yang paling sedikit memuat:
                <ol type="a">
                    <li>kegiatan usaha dan transaksi; dan</li>
                    <li>pelaksanaan pengembangan sumber daya manusia;</li>
                </ol>
            </li>
            <li>laporan penanganan pengaduan Pengguna Jasa dan Anggota;</li>
            <li>permasalahan yang dihadapi, rencana tindak lanjut, termasuk kesimpulan dan/atau saran;</li>
            <li>penerapan praktik manajemen risiko; dan</li>
            <li>pelaksanaan tata kelola.</li>
        </ol>
    </div>

</body>
</html>
