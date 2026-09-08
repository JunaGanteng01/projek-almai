<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CertificateModel;
use App\Models\UserModel;
use App\Models\LayananCompletionModel;

class Certificate extends BaseController
{
    public function view($certificateNumber)
    {
        $certificateModel = new CertificateModel();

        $certificate = $certificateModel->findByNumber($certificateNumber);

        if (!$certificate) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Sertifikat tidak ditemukan');
        }

        return view('certificate/view', [
            'title' => 'Sertifikat - ' . $certificate['certificate_number'],
            'certificate' => $certificate,
        ]);
    }

    public function verify($certificateNumber)
    {
        $certificateModel = new CertificateModel();

        $certificate = $certificateModel->findByNumber($certificateNumber);

        if (!$certificate) {
            return view('certificate/verify', [
                'title' => 'Verifikasi Sertifikat - ALMAI',
                'certificate' => null,
                'certificateNumber' => $certificateNumber,
                'isValid' => false,
            ]);
        }

        // Get additional info
        $userModel = new \App\Models\UserModel();
        $completionModel = new \App\Models\LayananCompletionModel();

        $user = $userModel->find($certificate['user_id']);
        $completion = null;

        if ($certificate['completion_id']) {
            $completion = $completionModel->find($certificate['completion_id']);
        }

        return view('certificate/verify', [
            'title' => 'Verifikasi Sertifikat - ALMAI',
            'certificate' => $certificate,
            'user' => $user,
            'completion' => $completion,
            'certificateNumber' => $certificateNumber,
            'isValid' => true,
        ]);
    }

    public function download($certificateNumber)
    {
        $certificateModel = new CertificateModel();

        $certificate = $certificateModel->findByNumber($certificateNumber);

        if (!$certificate) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Sertifikat tidak ditemukan');
        }

        // Generate PDF certificate
        $this->generateCertificatePDF($certificate);
    }

    private function generateCertificatePDF($certificate)
    {
        // Generate the same beautiful HTML as the view page but optimized for PDF
        $html = $this->getCertificateHTML($certificate);

        // Set headers for PDF download
        $filename = 'Sertifikat_' . $certificate['certificate_number'] . '.pdf';

        // Return HTML that can be printed as PDF by browser
        header('Content-Type: text/html; charset=utf-8');
        header('Content-Disposition: inline; filename="' . $filename . '"');

        echo $html;
        exit;
    }

    private function getCertificateHTML($certificate)
    {
        $issuedDate = date('d F Y', strtotime($certificate['issued_at']));

        return '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sertifikat - ' . $certificate['certificate_number'] . '</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&family=Crimson+Text:ital,wght@0,400;0,600;0,700;1,400&family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --accent: #33e818;
            --accent-dark: #22c55e;
            --bg-dark: #0a0a0a;
        }
        @page { size: A4 landscape; margin: 0; }
        body { 
            background-color: #1a1a1a; 
            margin: 0; 
            padding: 0;
            font-family: "Montserrat", sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .certificate-bg {
            background-color: #0c0c0c;
            background-image: 
                radial-gradient(circle at center, rgba(255,255,255,0.05) 0%, transparent 70%),
                url("https://www.transparenttextures.com/patterns/dark-leather.png");
            position: relative;
            width: 297mm;
            height: 210mm;
            overflow: hidden;
        }
        .certificate-bg::after {
            content: "";
            position: absolute;
            inset: 0;
            opacity: 0.15;
            pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox=\'0 0 200 200\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cfilter id=\'noiseFilter\'%3E%3CfeTurbulence type=\'fractalNoise\' baseFrequency=\'0.65\' numOctaves=\'3\' stitchTiles=\'stitch\'/%3E%3C/filter%3E%3Crect width=\'100%25\' height=\'100%25\' filter=\'url(%23noiseFilter)\'/%3E%3C/svg%3E");
            z-index: 1;
        }
        .outer-border {
            position: absolute;
            inset: 15px;
            border: 1px solid rgba(51, 232, 24, 0.3);
        }
        .inner-border {
            position: absolute;
            inset: 22px;
            border: 2px solid rgba(51, 232, 24, 0.5);
        }
        .serif { font-family: "Crimson Text", serif; }
        .signature-font { font-family: "Dancing Script", cursive; }
        .gold-text { color: var(--accent); }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 15rem;
            font-weight: 900;
            color: rgba(51, 232, 24, 0.03);
            white-space: nowrap;
        }
    </style>
    <script>
        window.onload = function() {
            setTimeout(function() { window.print(); }, 1000);
        }
    </script>
</head>
<body class="antialiased">
    <div class="certificate-bg">
        <div class="outer-border"></div>
        <div class="inner-border"></div>
        <div class="watermark">ALMAI</div>
        
        <div class="relative z-10 h-full w-full flex flex-col items-center pt-16 px-20">
            <div class="mb-6 text-center">
                <img src="https://almai.id/images/alma.gif" alt="ALMAI" style="height: 64px;">
            </div>

            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold tracking-widest text-white serif" style="letter-spacing: 0.2em;">PT. ALMA INDONESIA RAYA</h1>
                <p class="text-[10px] text-gray-400 uppercase tracking-widest mt-1" style="letter-spacing: 0.4em;">Platform Penasihat Berjangka</p>
                <div style="width: 256px; height: 1px; background: linear-gradient(to right, transparent, rgba(255,255,255,0.2), transparent); margin: 16px auto 0;"></div>
            </div>

            <div class="text-center mb-10">
                <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-3" style="letter-spacing: 0.4em;">Certificate of Professional Completion</p>
                <h2 class="text-5xl font-black gold-text tracking-wide serif uppercase italic" style="letter-spacing: 0.1em;">SERTIFIKAT</h2>
            </div>

            <div class="text-center mb-10 w-full">
                <p class="text-gray-400 text-xs italic mb-4">Diberikan kepada</p>
                <h3 class="text-4xl font-bold text-white serif tracking-wide" style="border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 16px; display: inline-block; min-width: 500px;">
                    ' . strtoupper(htmlspecialchars($certificate['user_name'])) . '
                </h3>
            </div>

            <div class="text-center mb-8 max-w-2xl mx-auto">
                <p class="text-gray-300 text-xs leading-relaxed mb-6">
                    Atas keberhasilan memenuhi standar kompetensi dan menyelesaikan Program Profesional:
                </p>
                <h4 class="text-3xl font-black gold-text serif tracking-wider uppercase mb-2">
                    ' . htmlspecialchars($certificate['layanan_name']) . '
                </h4>
                <p class="text-gray-500 text-[10px] uppercase tracking-widest">
                    AIWE Expert Advisor Strategy
                </p>
            </div>

            <div class="text-center mb-12 max-w-2xl">
                <p class="text-[9px] text-gray-600 leading-relaxed uppercase tracking-wider">
                    Sertifikat ini diterbitkan secara elektronik sebagai pengakuan resmi atas penyelesaian<br>
                    program sesuai standar internal perusahaan dan berlaku sebagai dokumen verifikasi
                </p>
            </div>

            <div class="w-full grid grid-cols-3 items-end mt-auto pb-16">
                <div class="text-center">
                    <div class="mb-2 h-16 flex items-end justify-center">
                        <span class="signature-font text-3xl gold-text opacity-80">Rendy M Prayogie</span>
                    </div>
                    <div style="width: 192px; height: 1px; background: rgba(255,255,255,0.2); margin: 0 auto 8px;"></div>
                    <p class="text-xs font-bold text-white serif">Rendy M Prayogie</p>
                    <p class="text-[8px] text-gray-500 uppercase tracking-widest">Direktur Utama</p>
                </div>

                <div class="text-center pb-4">
                    <p class="text-xs text-gray-400 serif italic">' . $issuedDate . '</p>
                </div>

                <div class="text-center">
                    <div class="mb-2 h-16 flex items-end justify-center">
                        <span class="signature-font text-2xl text-white opacity-80">' . htmlspecialchars($certificate['wpa_name'] ?? 'I Gd Eka Chandra Maheswara, S.E') . '</span>
                    </div>
                    <div style="width: 192px; height: 1px; background: rgba(255,255,255,0.2); margin: 0 auto 8px;"></div>
                    <p class="text-xs font-bold text-white serif">' . htmlspecialchars($certificate['wpa_name'] ?? 'I Gd Eka Chandra Maheswara, S.E') . '</p>
                    <p class="text-[8px] text-gray-500 uppercase tracking-widest">Wakil Penasihat Berjangka</p>
                </div>
            </div>

            <div class="absolute bottom-6 left-20 right-20 flex justify-between items-end" style="border-top: 1px solid rgba(255,255,255,0.05); padding-top: 16px;">
                <div class="flex gap-8 text-[8px] text-gray-600 uppercase tracking-widest">
                    <div>
                        <span>Issued Date:</span> <span class="text-gray-400">' . $issuedDate . '</span>
                    </div>
                    <div>
                        <span>Credential ID:</span> <span class="text-accent">' . htmlspecialchars($certificate['certificate_number']) . '</span>
                    </div>
                    <div>
                        <span>Verify:</span> <span class="text-gray-400">almai.id/verify/' . htmlspecialchars($certificate['certificate_number']) . '</span>
                    </div>
                </div>
                <div class="text-[7px] text-gray-700 italic">
                    Digitally Authenticated Certificate • Verification Required for Official Validation
                </div>
            </div>

            <div class="absolute bottom-6 right-10">
                <div class="p-1.5 bg-white rounded-md border border-accent/30" style="background: rgba(255,255,255,0.9);">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=' . base_url('verify/' . $certificate['certificate_number']) . '" style="width: 56px; height: 56px;">
                    <p class="text-[5px] text-black font-bold text-center mt-1">VERIFIED</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>';
    }
}
