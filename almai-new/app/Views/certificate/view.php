<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&family=Crimson+Text:ital,wght@0,400;0,600;0,700;1,400&family=Dancing+Script:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --accent: #33e818;
            --accent-dark: #22c55e;
            --bg-dark: #0a0a0a;
        }

        html, body {
            background-color: #1a1a1a;
            color: #ffffff;
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
        }

        .certificate-bg {
            background-color: #0c0c0c;
            background-image: 
                radial-gradient(circle at center, rgba(255,255,255,0.05) 0%, transparent 70%),
                url("https://www.transparenttextures.com/patterns/dark-leather.png");
            position: relative;
            width: 297mm;
            height: 210mm;
            margin: 0 auto;
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 40px 100px rgba(0,0,0,0.8);
            overflow: hidden;
        }

        /* Noise overlay for paper effect */
        .certificate-bg::after {
            content: "";
            position: absolute;
            inset: 0;
            opacity: 0.15;
            pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
            z-index: 1;
        }

        /* The green double border from image */
        .outer-border {
            position: absolute;
            inset: 15px;
            border: 1px solid rgba(51, 232, 24, 0.3);
            pointer-events: none;
        }

        .inner-border {
            position: absolute;
            inset: 22px;
            border: 2px solid rgba(51, 232, 24, 0.5);
            pointer-events: none;
        }

        .inner-border::before {
            content: '';
            position: absolute;
            inset: -4px;
            border: 1px solid rgba(51, 232, 24, 0.2);
        }

        .serif {
            font-family: 'Crimson Text', serif;
        }

        .signature-font {
            font-family: 'Dancing Script', cursive;
        }

        .gold-text {
            color: var(--accent);
            text-shadow: 0 0 10px rgba(51, 232, 24, 0.3);
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 15rem;
            font-weight: 900;
            color: rgba(51, 232, 24, 0.03);
            pointer-events: none;
            z-index: 0;
            white-space: nowrap;
        }

        @media print {
            body { background: white !important; }
            .no-print { display: none !important; }
            .certificate-container { box-shadow: none !important; margin: 0 !important; }
            @page { size: A4 landscape; margin: 0; }
            .certificate-bg { 
                width: 297mm; 
                height: 210mm; 
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        @media screen and (max-width: 1200px) {
            .certificate-bg {
                width: 100%;
                height: auto;
                aspect-ratio: 297 / 210;
            }
        }
    </style>
</head>

<body class="antialiased min-h-screen">
    <!-- Header Controls -->
    <header class="bg-[#0a0a0a] border-b border-white/10 px-4 md:px-8 py-4 no-print relative z-50">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <a href="<?= base_url('user/dashboard') ?>" class="flex items-center gap-2 text-gray-400 hover:text-white transition">
                <i class="fas fa-arrow-left"></i>
                <span class="text-sm">Kembali ke Dashboard</span>
            </a>
            <div class="flex items-center gap-2">
                <a href="<?= base_url('certificate/' . $certificate['certificate_number'] . '/download') ?>" class="px-6 py-2.5 bg-white/5 border border-white/10 text-white font-black uppercase tracking-widest rounded-xl hover:bg-white hover:text-black transition text-xs">
                    <i class="fas fa-download mr-2"></i> Download PDF
                </a>
                <button onclick="window.print()" class="px-6 py-2.5 bg-accent text-black font-black uppercase tracking-widest rounded-xl hover:bg-white transition text-xs shadow-lg shadow-accent/20">
                    <i class="fas fa-print mr-2"></i> Cetak Sertifikat
                </button>
            </div>
        </div>
    </header>

    <!-- Certificate Container -->
    <main class="p-4 md:p-8 flex justify-center items-center">
        <div class="certificate-bg relative shadow-2xl">
            <!-- Borders -->
            <div class="outer-border"></div>
            <div class="inner-border"></div>

            <!-- Watermark -->
            <div class="watermark">ALMAI</div>

            <!-- Content Area -->
            <div class="relative z-10 h-full w-full flex flex-col items-center pt-16 px-20">
                <!-- Logo -->
                <div class="mb-6">
                    <img src="https://almai.id/images/alma.gif" alt="ALMAI" class="h-16 w-auto object-contain">
                </div>

                <!-- Company Info -->
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold tracking-[0.2em] text-white serif">PT. ALMA INDONESIA RAYA</h1>
                    <p class="text-[10px] text-gray-400 uppercase tracking-[0.4em] mt-1">Platform Penasihat Berjangka</p>
                    <div class="w-64 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent mx-auto mt-4"></div>
                </div>

                <!-- Certificate Title -->
                <div class="text-center mb-10">
                    <p class="text-[10px] text-gray-400 uppercase tracking-[0.4em] mb-3">Certificate of Professional Completion</p>
                    <h2 class="text-5xl font-black gold-text tracking-[0.1em] serif uppercase italic">SERTIFIKAT</h2>
                </div>

                <!-- Recipient Info -->
                <div class="text-center mb-10 w-full">
                    <p class="text-gray-400 text-xs italic mb-4">Diberikan kepada</p>
                    <h3 class="text-4xl font-bold text-white serif tracking-wide border-b border-white/10 pb-4 inline-block min-w-[500px]">
                        <?= strtoupper(esc($certificate['user_name'])) ?>
                    </h3>
                </div>

                <!-- Accomplishment Text -->
                <div class="text-center mb-8 max-w-2xl mx-auto">
                    <p class="text-gray-300 text-xs leading-relaxed mb-6">
                        Atas keberhasilan memenuhi standar kompetensi dan menyelesaikan Program Profesional:
                    </p>
                    <h4 class="text-3xl font-black gold-text serif tracking-wider uppercase mb-2">
                        <?= esc($certificate['layanan_name']) ?>
                    </h4>
                    <p class="text-gray-500 text-[10px] uppercase tracking-widest">
                        AIWE Expert Advisor Strategy
                    </p>
                </div>

                <!-- Verification Paragraph -->
                <div class="text-center mb-12 max-w-2xl">
                    <p class="text-[9px] text-gray-600 leading-relaxed uppercase tracking-wider">
                        Sertifikat ini diterbitkan secara elektronik sebagai pengakuan resmi atas penyelesaian<br>
                        program sesuai standar internal perusahaan dan berlaku sebagai dokumen verifikasi
                    </p>
                </div>

                <!-- Signatures & Date -->
                <div class="w-full grid grid-cols-3 items-end mt-auto pb-16">
                    <!-- Left Signature -->
                    <div class="text-center">
                        <div class="mb-2 h-16 flex items-end justify-center">
                            <span class="signature-font text-3xl gold-text opacity-80">Rendy M Prayogie</span>
                        </div>
                        <div class="w-48 h-px bg-white/20 mx-auto mb-2"></div>
                        <p class="text-xs font-bold text-white serif">Rendy M Prayogie</p>
                        <p class="text-[8px] text-gray-500 uppercase tracking-widest">Direktur Utama</p>
                    </div>

                    <!-- Date -->
                    <div class="text-center pb-4">
                        <p class="text-xs text-gray-400 serif italic"><?= date('d F Y', strtotime($certificate['issued_at'])) ?></p>
                    </div>

                    <!-- Right Signature -->
                    <div class="text-center">
                        <div class="mb-2 h-16 flex items-end justify-center">
                            <span class="signature-font text-2xl text-white opacity-80"><?= esc($certificate['wpa_name'] ?? 'I Gd Eka Chandra Maheswara, S.E') ?></span>
                        </div>
                        <div class="w-48 h-px bg-white/20 mx-auto mb-2"></div>
                        <p class="text-xs font-bold text-white serif"><?= esc($certificate['wpa_name'] ?? 'I Gd Eka Chandra Maheswara, S.E') ?></p>
                        <p class="text-[8px] text-gray-500 uppercase tracking-widest">Wakil Penasihat Berjangka</p>
                    </div>
                </div>

                <!-- Bottom Footer -->
                <div class="absolute bottom-6 left-20 right-20 flex justify-between items-end border-t border-white/5 pt-4">
                    <div class="flex gap-8 text-[8px] text-gray-600 uppercase tracking-widest">
                        <div>
                            <span>Issued Date:</span> <span class="text-gray-400"><?= date('d F Y', strtotime($certificate['issued_at'])) ?></span>
                        </div>
                        <div>
                            <span>Credential ID:</span> <span class="text-accent"><?= esc($certificate['certificate_number']) ?></span>
                        </div>
                        <div>
                            <span>Verify:</span> <span class="text-gray-400">almai.id/verify/<?= esc($certificate['certificate_number']) ?></span>
                        </div>
                    </div>
                    <div class="text-[7px] text-gray-700 italic">
                        Digitally Authenticated Certificate • Verification Required for Official Validation
                    </div>
                </div>

                <!-- QR Code -->
                <div class="absolute bottom-6 right-10">
                    <div class="p-1.5 bg-white/90 rounded-md shadow-lg border border-accent/30">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=<?= base_url('verify/' . $certificate['certificate_number']) ?>" alt="QR" class="w-14 h-14">
                        <p class="text-[5px] text-black font-bold text-center mt-1">VERIFIED</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="no-print pb-20 text-center">
        <p class="text-gray-500 text-xs tracking-widest uppercase">© 2026 PT. ALMA INDONESIA RAYA</p>
    </footer>

    <script>
        // No extra JS needed for now, window.print is handled by button
    </script>
</body>

</html>

</html>