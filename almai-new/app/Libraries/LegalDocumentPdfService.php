<?php

namespace App\Libraries;

use Dompdf\Dompdf;
use Dompdf\Options;

class LegalDocumentPdfService
{
    protected $legalDocumentModel;

    public function __construct()
    {
        $this->legalDocumentModel = new \App\Models\LegalDocumentModel();
    }

    /**
     * Create individual Dompdf instance with settings optimized for standard hosting
     */
    private function createDompdfInstance()
    {
        // Increase limits for PDF generation
        ini_set('memory_limit', '512M');
        set_time_limit(300);

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true); // Keep for compatibility but fallback to local
        $options->set('chroot', FCPATH); // Allow access to local images
        $options->set('defaultFont', 'sans-serif'); // Use system fonts to avoid loading issues

        // Use a standard temp directory
        $tempDir = WRITEPATH . 'cache';
        if (!is_dir($tempDir)) {
            @mkdir($tempDir, 0755, true);
        }
        $options->set('tempDir', $tempDir);
        $options->set('logOutputFile', $tempDir . '/dompdf_log.html');

        return new Dompdf($options);
    }

    /**
     * Generate Perjanjian Pemberian Jasa PDF
     */
    public function generatePerjanjianPdf($transaksi, $user, $layanan, $wpaName = 'Tim Almai')
    {
        $dompdf = null;
        try {
            $nomorKontrak = $this->generateNomorKontrak($transaksi, $layanan);
            $html = $this->getPerjanjianHtml($transaksi, $user, $layanan, $wpaName, $nomorKontrak);

            // Create fresh instance
            $dompdf = $this->createDompdfInstance();

            // Critical: Ensure no output buffer pollution
            if (ob_get_level() > 0) {
                ob_clean();
            }

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $output = $dompdf->output();

            // Explicitly clear memory
            unset($dompdf);

            if (empty($output)) {
                log_message('error', 'Dompdf returned empty string for Perjanjian');
                return null;
            }

            return $output;
        } catch (\Throwable $e) {
            log_message('error', 'PDF Generation Exception (Perjanjian): ' . $e->getMessage());
            if ($dompdf) unset($dompdf);
            return null;
        }
    }

    /**
     * Generate Dokumen Pemberitahuan Risiko PDF
     */
    public function generateRisikoPdf($transaksi, $user, $layanan)
    {
        $dompdf = null;
        try {
            $html = $this->getRisikoHtml($transaksi, $user, $layanan);

            $dompdf = $this->createDompdfInstance();

            if (ob_get_level() > 0) {
                ob_clean();
            }

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $output = $dompdf->output();
            unset($dompdf);

            if (empty($output)) {
                log_message('error', 'Dompdf returned empty string for Risiko');
                return null;
            }

            return $output;
        } catch (\Throwable $e) {
            log_message('error', 'PDF Generation Exception (Risiko): ' . $e->getMessage());
            if ($dompdf) unset($dompdf);
            return null;
        }
    }

    /**
     * Generate Dokumen Profil Perusahaan PDF
     */
    public function generateProfilPerusahaanPdf($transaksi, $user)
    {
        $dompdf = null;
        try {
            $html = $this->getProfilPerusahaanHtml($transaksi, $user);

            $dompdf = $this->createDompdfInstance();

            if (ob_get_level() > 0) {
                ob_clean();
            }

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $output = $dompdf->output();
            unset($dompdf);

            if (empty($output)) {
                log_message('error', 'Dompdf returned empty string for Profil Perusahaan');
                return null;
            }

            return $output;
        } catch (\Throwable $e) {
            log_message('error', 'PDF Generation Exception (Profil Perusahaan): ' . $e->getMessage());
            if ($dompdf) unset($dompdf);
            return null;
        }
    }

    /**
     * Generate Dokumen Perjanjian WPA PDF
     */
    public function generatePerjanjianWpaPdf($transaksi, $user, $layanan)
    {
        $dompdf = null;
        try {
            $html = $this->getPerjanjianWpaHtml($transaksi, $user, $layanan);

            $dompdf = $this->createDompdfInstance();

            if (ob_get_level() > 0) {
                ob_clean();
            }

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $output = $dompdf->output();
            unset($dompdf);

            if (empty($output)) {
                log_message('error', 'Dompdf returned empty string for Perjanjian WPA');
                return null;
            }

            return $output;
        } catch (\Throwable $e) {
            log_message('error', 'PDF Generation Exception (Perjanjian WPA): ' . $e->getMessage());
            if ($dompdf) unset($dompdf);
            return null;
        }
    }

    /**
     * Generate nomor kontrak
     */
    private function generateNomorKontrak($transaksi, $layanan)
    {
        try {
            $createdAt = !empty($transaksi['created_at']) ? $transaksi['created_at'] : date('Y-m-d H:i:s');
            $date = new \DateTime($createdAt);
            $year = $date->format('Y');
            $month = (int)$date->format('n');

            $romanMonths = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
            $romanMonth = $romanMonths[max(0, $month - 1)];

            $slug = 'LAYANAN';
            if (!empty($layanan['slug'])) {
                $slug = strtoupper($layanan['slug']);
            } elseif (!empty($transaksi['product_name'])) {
                $slug = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '_', $transaksi['product_name']));
            }

            $invoiceNumber = !empty($transaksi['invoice_number']) ? $transaksi['invoice_number'] : '0000';
            $uniqueNumber = substr($invoiceNumber, -4);

            return "{$uniqueNumber}/ALMAI/{$slug}/{$romanMonth}/{$year}";
        } catch (\Throwable $e) {
            return "ALMAI/DOC/" . date('Ymd');
        }
    }

    /**
     * Get Perjanjian HTML
     */
    private function getPerjanjianHtml($transaksi, $user, $layanan, $wpaName, $nomorKontrak)
    {
        $createdAt = !empty($transaksi['created_at']) ? $transaksi['created_at'] : date('Y-m-d H:i:s');
        $tanggalFormatted = date('d F Y', strtotime($createdAt));
        $hariTanggal = $this->getIndonesianDate($createdAt);

        $doc = $this->legalDocumentModel->getByType('perjanjian');
        $dbContent = !empty($doc) ? $doc[0]['content'] : '';

        $content = empty($dbContent) ? $this->getDefaultPerjanjianHtml($transaksi, $user, $wpaName, $nomorKontrak, $hariTanggal) : $dbContent;

        $variables = $this->getLegalVariables($transaksi, $user, $layanan, $wpaName, $nomorKontrak, $tanggalFormatted, $hariTanggal);
        $htmlContent = str_replace(array_keys($variables), array_values($variables), $content);

        return '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 10pt; line-height: 1.4; color: #333; margin: 0; padding: 10px; }
        .company-header { 
            text-align: center; 
            margin-bottom: 25px; 
            padding-bottom: 15px;
            border-bottom: 2px solid #33e818;
        }
        .company-header p { margin: 2px 0; font-size: 8pt; color: #666; }
        .company-header .company-name { font-weight: bold; font-size: 11pt; color: #000; margin-bottom: 4px; }
        .header { text-align: center; margin-bottom: 15px; }
        .header h1 { font-size: 14pt; margin: 5px 0; color: #000; }
        .section { margin: 12px 0; }
        .section-title { font-weight: bold; font-size: 11pt; margin-bottom: 5px; border-bottom: 1px solid #ddd; }
        .info-box { background: #f5f5f5; padding: 10px; margin: 8px 0; border: 1px solid #eee; }
        .signature-table { width: 100%; margin-top: 30px; page-break-inside: avoid; }
        .signature-table td { text-align: center; width: 50%; }
        table { border-collapse: collapse; width: 100%; table-layout: fixed; word-wrap: break-word; }
        p, div, td { page-break-inside: auto; }
        tr { page-break-inside: avoid; page-break-after: auto; }
        thead { display: table-header-group; }
        tfoot { display: table-footer-group; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <div class="company-header">
        <img src="' . FCPATH . 'images/alma.gif" width="60">
        <div class="company-name">PT. Alma Indonesia Raya</div>
        <p>Jl. Badak Agung No. 22 Kav. 3, Kel. Renon, Denpasar - Bali 80226</p>
        <p>Telp: 0361-3610019 | Email: cs@almai.id | Website: www.almai.id</p>
    </div>
' . $htmlContent . '
</body>
</html>';
    }

    private function getDefaultPerjanjianHtml($transaksi, $user, $wpaName, $nomorKontrak, $hariTanggal)
    {
        return '
    <div class="header">
        <h1>PERJANJIAN PENASIHAT BERJANGKA</h1>
        <p><strong>Nomor: ' . htmlspecialchars($nomorKontrak) . '</strong></p>
    </div>
    <div class="section">
        <p>Pada hari ini <strong>' . htmlspecialchars($hariTanggal) . '</strong>, telah disepakati perjanjian antara PT. ALMA INDONESIA RAYA dan Klien (' . htmlspecialchars($user['name'] ?? 'User') . ') terkait layanan ' . htmlspecialchars($transaksi['product_name'] ?? 'Layanan ALMAI') . '.</p>
    </div>
    <div class="section">
        <p class="section-title">PERNYATAAN</p>
        <p>Klien menyatakan telah memahami segala risiko perdagangan berjangka dan menggunakan layanan ini atas kemauan sendiri tanpa paksaan.</p>
    </div>
    <table class="signature-table">
        <tr>
            <td>
                <p>Pihak Pertama</p>
                <div style="height: 50px;"></div>
                <p><strong>' . htmlspecialchars($wpaName) . '</strong></p>
            </td>
            <td>
                <p>Pihak Kedua</p>
                <div style="height: 50px;"></div>
                <p><strong>' . htmlspecialchars($user['name'] ?? 'User') . '</strong></p>
            </td>
        </tr>
    </table>';
    }

    private function getRisikoHtml($transaksi, $user, $layanan)
    {
        $createdAt = !empty($transaksi['created_at']) ? $transaksi['created_at'] : date('Y-m-d H:i:s');
        $tanggalFormatted = date('d F Y', strtotime($createdAt));

        $doc = $this->legalDocumentModel->getByType('risiko');
        $dbContent = !empty($doc) ? $doc[0]['content'] : '';

        $content = empty($dbContent) ? $this->getDefaultRisikoHtml($user, $tanggalFormatted) : $dbContent;
        $variables = $this->getLegalVariables($transaksi, $user, $layanan, 'Tim Almai', null, $tanggalFormatted);
        $htmlContent = str_replace(array_keys($variables), array_values($variables), $content);

        return '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 10pt; line-height: 1.4; color: #333; margin: 0; padding: 10px; }
        .company-header { 
            text-align: center; 
            margin-bottom: 25px; 
            padding-bottom: 15px;
            border-bottom: 2px solid #33e818;
        }
        .company-header p { margin: 2px 0; font-size: 8pt; color: #666; }
        .company-header .company-name { font-weight: bold; font-size: 11pt; color: #000; margin-bottom: 4px; }
        .header { text-align: center; margin-bottom: 15px; }
        .warning-box { background: #fffde7; padding: 12px; border: 1px solid #fbc02d; color: #000; page-break-inside: avoid; }
        .section-title { font-weight: bold; border-bottom: 1px solid #ddd; }
        table { border-collapse: collapse; width: 100%; table-layout: fixed; word-wrap: break-word; }
        p, div, td { page-break-inside: auto; }
        tr { page-break-inside: avoid; page-break-after: auto; }
    </style>
</head>
<body>
    <div class="company-header">
        <img src="' . FCPATH . 'images/alma.gif" width="60">
        <div class="company-name">PT. Alma Indonesia Raya</div>
        <p>Jl. Badak Agung No. 22 Kav. 3, Kel. Renon, Denpasar - Bali 80226</p>
        <p>Telp: 0361-3610019 | Email: cs@almai.id | Website: www.almai.id</p>
    </div>
' . $htmlContent . '
</body>
</html>';
    }

    private function getDefaultRisikoHtml($user, $tanggal)
    {
        return '
    <div class="header">
        <h1>PEMBERITAHUAN RISIKO</h1>
        <p>PT. ALMA INDONESIA RAYA</p>
    </div>
    <div class="warning-box">
        <p><strong>PERINGATAN:</strong> Perdagangan berjangka mengandung risiko tinggi. Anda dapat kehilangan seluruh modal investasi.</p>
    </div>
    <div class="section">
        <p>Klien: ' . htmlspecialchars($user['name'] ?? 'User') . '</p>
        <p>Tanggal: ' . htmlspecialchars($tanggal) . '</p>
    </div>';
    }

    private function getProfilPerusahaanHtml($transaksi, $user)
    {
        $doc = $this->legalDocumentModel->getBySlug('profil-perusahaan');
        $dbContent = !empty($doc) ? $doc['content'] : '';

        $content = empty($dbContent) ? '<div class="header"><h1>PROFIL PERUSAHAAN</h1><p>PT. ALMA INDONESIA RAYA</p></div>' : $dbContent;
        $variables = $this->getLegalVariables($transaksi, $user, null);
        $htmlContent = str_replace(array_keys($variables), array_values($variables), $content);

        return $this->wrapHtml($htmlContent);
    }

    private function getPerjanjianWpaHtml($transaksi, $user, $layanan)
    {
        // Try multiple slugs for CWPA Agreement
        $slugs = [
            'perjanjian-wpa',
            'surat-perjanjian-pendampingan-calon-wakil-penasihat-berjangka',
            'surat-perjanjian-pendampingan-calon-wakil-penasiha'
        ];

        $doc = null;
        foreach ($slugs as $slug) {
            $doc = $this->legalDocumentModel->getBySlug($slug);
            if ($doc) break;
        }

        // If still not found, try searching by title containing CWPA or Pendampingan
        if (!$doc) {
            $doc = $this->legalDocumentModel->groupStart()
                ->like('slug', 'pendampingan')
                ->orLike('slug', 'cwpa')
                ->groupEnd()
                ->where('type', 'other')
                ->first();
        }

        $dbContent = !empty($doc) ? $doc['content'] : '';

        $content = empty($dbContent) ? '<div class="header"><h1>PERJANJIAN WPA</h1><p>Program Pendampingan CWPA</p></div>' : $dbContent;
        $variables = $this->getLegalVariables($transaksi, $user, $layanan);
        $htmlContent = str_replace(array_keys($variables), array_values($variables), $content);

        return $this->wrapHtml($htmlContent);
    }

    private function wrapHtml($htmlContent)
    {
        return '
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 10pt; line-height: 1.4; color: #333; margin: 0; padding: 10px; }
        .company-header { 
            text-align: center; 
            margin-bottom: 25px; 
            padding-bottom: 15px;
            border-bottom: 2px solid #33e818;
        }
        .company-header p { margin: 2px 0; font-size: 8pt; color: #666; }
        .company-header .company-name { font-weight: bold; font-size: 11pt; color: #000; margin-bottom: 4px; }
        .header { text-align: center; margin-bottom: 15px; }
        .header h1 { font-size: 14pt; margin: 5px 0; color: #000; }
        .section { margin: 12px 0; }
        .section-title { font-weight: bold; font-size: 11pt; margin-bottom: 5px; border-bottom: 1px solid #ddd; }
        table { border-collapse: collapse; width: 100%; table-layout: fixed; word-wrap: break-word; }
        p, div, td { page-break-inside: auto; }
        tr { page-break-inside: avoid; page-break-after: auto; }
    </style>
</head>
<body>
    <div class="company-header">
        <img src="' . FCPATH . 'images/alma.gif" width="60">
        <div class="company-name">PT. Alma Indonesia Raya</div>
        <p>Jl. Badak Agung No. 22 Kav. 3, Kel. Renon, Denpasar - Bali 80226</p>
        <p>Telp: 0361-3610019 | Email: cs@almai.id | Website: www.almai.id</p>
    </div>
' . $htmlContent . '
</body>
</html>';
    }

    /**
     * Get variables for legal document replacement
     */
    private function getLegalVariables($transaksi = null, $user = null, $layanan = null, $wpaName = 'Tim Almai', $nomorKontrak = null, $tanggal = null, $hariTanggal = null)
    {
        $createdAt = !empty($transaksi['created_at']) ? $transaksi['created_at'] : date('Y-m-d H:i:s');
        $ts = strtotime($createdAt);
        $romanMonths = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

        $variables = [
            '{NAMA_USER}' => htmlspecialchars($user['name'] ?? 'User'),
            '{EMAIL_USER}' => htmlspecialchars($user['email'] ?? '-'),
            '{NO_TELP_USER}' => htmlspecialchars($user['phone'] ?? '-'),
            '{NAMA_WPA}' => htmlspecialchars($wpaName),
            '{NAMA_PRODUK}' => htmlspecialchars($transaksi['product_name'] ?? 'Produk'),
            '{NAMA_LAYANAN}' => htmlspecialchars($transaksi['product_name'] ?? 'Layanan'),
            '{HARGA_RUPIAH_PRODUK}' => 'Rp ' . number_format($transaksi['amount'] ?? 0, 0, ',', '.'),
            '{HARGA_LAYANAN}' => 'Rp ' . number_format($transaksi['amount'] ?? 0, 0, ',', '.'),
            '{HARGA_YANG_DI_BAYAR}' => 'Rp ' . number_format($transaksi['total'] ?? 0, 0, ',', '.'),
            '{TOTAL_BAYAR}' => 'Rp ' . number_format($transaksi['total'] ?? 0, 0, ',', '.'),
            '{NOMOR_KONTRAK}' => htmlspecialchars($nomorKontrak ?? '-'),
            '{TANGGAL}' => htmlspecialchars($tanggal ?? date('d F Y', $ts)),
            '{HARI_TANGGAL}' => htmlspecialchars($hariTanggal ?? $this->getIndonesianDate($createdAt)),
            '{LAYANAN_UTAMA}' => htmlspecialchars($layanan['layanan_utama'] ?? '-'),
            '{NIK}' => '',
            '{NPWP}' => '',
            '{ALAMAT}' => htmlspecialchars($user['address'] ?? '-'),
            '{PEKERJAAN}' => '',
            '{TIMESTAMP}' => date('d/m/Y H:i:s'),

            // Components for custom contract number formats
            '{NO}' => !empty($transaksi['invoice_number']) ? substr($transaksi['invoice_number'], -4) : '0000',
            '{BULAN}' => $romanMonths[date('n', $ts) - 1],
            '{TAHUN}' => date('Y', $ts),
        ];

        // Add Aliases for better compatibility with different templates
        $variables['{NO_KTP}'] = &$variables['{NIK}'];
        $variables['{NO_NPWP}'] = &$variables['{NPWP}'];
        $variables['{KTP_USER}'] = &$variables['{NIK}'];
        $variables['{NPWP_USER}'] = &$variables['{NPWP}'];
        $variables['{ALAMAT_USER}'] = &$variables['{ALAMAT}'];
        $variables['{PHONE_USER}'] = &$variables['{NO_TELP_USER}'];
        $variables['{WHATSAPP_USER}'] = &$variables['{NO_TELP_USER}'];

        // Enrich with submission data if exists
        $submissionModel = new \App\Models\CwpaSubmissionModel();
        $submission = $submissionModel->getByUserId($user['id']);
        if ($submission) {
            $variables['{NIK}'] = htmlspecialchars($submission['ktp_number'] ?? '-');
            $variables['{NPWP}'] = htmlspecialchars($submission['npwp_number'] ?? '-');
            $variables['{PEKERJAAN}'] = htmlspecialchars($submission['specialties'] ?? '-');
            if (!empty($submission['address'])) {
                $variables['{ALAMAT}'] = htmlspecialchars($submission['address']);
            }
            if (!empty($submission['whatsapp'])) {
                $variables['{NO_TELP_USER}'] = htmlspecialchars($submission['whatsapp']);
            }
        }

        return $variables;
    }

    private function getIndonesianDate($date)
    {
        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $timestamp = strtotime($date);
        if (!$timestamp) $timestamp = time();

        $day = $days[date('w', $timestamp)];
        $date_num = date('d', $timestamp);
        $month = $months[date('n', $timestamp) - 1];
        $year = date('Y', $timestamp);

        return "{$day}, {$date_num} {$month} {$year}";
    }
}
