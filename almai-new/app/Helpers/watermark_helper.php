<?php

if (!function_exists('add_watermark')) {
    /**
     * Menambahkan watermark teks Full Screen (Tiled)
     * * @param string $sourcePath Path ke file asli
     * @param string $watermarkText Teks yang ingin ditampilkan
     * @param string $outputPath Path hasil simpan (null = timpa file asli)
     * @return bool
     */
    function add_watermark($sourcePath, $watermarkText = 'COPYRIGHT ALMAI.ID', $outputPath = null)
    {
        // Log bahwa fungsi dipanggil
        log_message('info', '=== WATERMARK START === Source: ' . $sourcePath);

        try {
            if ($outputPath === null) $outputPath = $sourcePath;

            if (!file_exists($sourcePath)) {
                log_message('error', 'Watermark: File tidak ditemukan: ' . $sourcePath);
                return false;
            }

            log_message('info', 'Watermark: File ditemukan, ukuran: ' . filesize($sourcePath) . ' bytes');

            $imageInfo = getimagesize($sourcePath);
            if ($imageInfo === false) return false;

            $width     = $imageInfo[0];
            $height    = $imageInfo[1];
            $mimeType  = $imageInfo['mime'];

            // 1. Buat Resource Gambar
            switch ($mimeType) {
                case 'image/jpeg':
                case 'image/jpg':
                    $image = imagecreatefromjpeg($sourcePath);
                    break;
                case 'image/png':
                    $image = imagecreatefrompng($sourcePath);
                    break;
                case 'image/gif':
                    $image = imagecreatefromgif($sourcePath);
                    break;
                case 'image/webp':
                    $image = imagecreatefromwebp($sourcePath);
                    break;
                default:
                    return false;
            }

            imagealphablending($image, true);
            imagesavealpha($image, true);

            // 2. Konfigurasi Tampilan
            $fontSize = 30; // Ukuran teks
            $angle    = 35; // Kemiringan teks (diagonal)

            // Cari Font
            $fontPaths = [
                ROOTPATH . 'vendor/dompdf/dompdf/lib/fonts/DejaVuSans-Bold.ttf',
                APPPATH . 'Fonts/Arial-Bold.ttf',
                '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
                'C:\\Windows\\Fonts\\arialbd.ttf'
            ];

            $fontPath = null;
            foreach ($fontPaths as $path) {
                if (file_exists($path)) {
                    $fontPath = $path;
                    log_message('info', 'Watermark: Font found at ' . $path);
                    break;
                }
            }

            // Jika tidak ada font yang ditemukan, log error
            if (!$fontPath) {
                log_message('error', 'Watermark: No TrueType font found. Checked paths: ' . implode(', ', $fontPaths));
                log_message('error', 'Watermark: ROOTPATH = ' . ROOTPATH);
                log_message('error', 'Watermark: APPPATH = ' . APPPATH);

                // Coba gunakan built-in GD font sebagai fallback
                // Meskipun tidak support rotasi, setidaknya ada watermark
                $builtInFont = 5; // Font terbesar
                $textWidth = imagefontwidth($builtInFont) * strlen($watermarkText);
                $textHeight = imagefontheight($builtInFont);
                $greenAccent = imagecolorallocatealpha($image, 51, 232, 24, 100);

                // Tambahkan watermark sederhana tanpa rotasi
                for ($y = 0; $y < $height; $y += 100) {
                    for ($x = 0; $x < $width; $x += 300) {
                        imagestring($image, $builtInFont, $x, $y, $watermarkText, $greenAccent);
                    }
                }

                log_message('info', 'Watermark: Applied using built-in font fallback');
            } else {
                // Warna Hijau Transparan untuk TrueType font
                $greenAccent = imagecolorallocatealpha($image, 51, 232, 24, 100);

                // Hitung ukuran teks untuk menentukan jarak
                $bbox = imagettfbbox($fontSize, $angle, $fontPath, $watermarkText);
                $textWidth  = abs($bbox[4] - $bbox[0]);
                $textHeight = abs($bbox[5] - $bbox[1]);

                // Pengaturan jarak antar watermark
                $spacingX = $textWidth * 1.5;  // Jarak ke samping
                $spacingY = $textHeight * 6.0; // Jarak ke bawah (dibuat agak renggang agar rapi)

                // 3. Loop Full Screen (Mencakup koordinat negatif agar pojok kiri atas tidak kosong)
                $row = 0;
                for ($y = -$height; $y < ($height * 2); $y += $spacingY) {
                    // Berikan offset (geser samping) pada baris genap agar pola terlihat zigzag/selang-seling
                    $offsetX = ($row % 2 == 0) ? 0 : ($spacingX / 2);

                    for ($x = -$width; $x < ($width * 2); $x += $spacingX) {
                        imagettftext($image, $fontSize, $angle, $x + $offsetX, $y, $greenAccent, $fontPath, $watermarkText);
                    }
                    $row++;
                }
            }

            // 4. Simpan Hasil
            $success = false;
            switch ($mimeType) {
                case 'image/jpeg':
                case 'image/jpg':
                    $success = imagejpeg($image, $outputPath, 90);
                    break;
                case 'image/png':
                    $success = imagepng($image, $outputPath, 8);
                    break;
                case 'image/gif':
                    $success = imagegif($image, $outputPath);
                    break;
                case 'image/webp':
                    $success = imagewebp($image, $outputPath, 85);
                    break;
            }


            imagedestroy($image);

            if ($success) {
                log_message('info', '=== WATERMARK SUCCESS === File saved: ' . $outputPath);
            } else {
                log_message('error', '=== WATERMARK FAILED === Could not save file: ' . $outputPath);
            }

            return $success;
        } catch (Exception $e) {
            log_message('error', '=== WATERMARK ERROR === ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());
            return false;
        }
    }
}
