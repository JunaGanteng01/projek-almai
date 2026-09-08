<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'accent': '#33e818'
                    },
                    fontFamily: {
                        sans: ['Montserrat', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        html, body { 
            background-color: #050505; 
            color: #ffffff; 
        }
        .verification-bg {
            background: linear-gradient(135deg, #0a0a0a 0%, #111 50%, #0a0a0a 100%);
        }
    </style>
</head>
<body class="antialiased min-h-screen">
    <!-- Header -->
    <header class="bg-[#0a0a0a] border-b border-white/10 px-4 md:px-8 py-4">
        <div class="max-w-6xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="https://almai.id/images/alma.gif" alt="ALMAI" class="h-8">
                <h1 class="text-xl font-bold">ALMAI</h1>
            </div>
            <div class="text-sm text-gray-400">
                Verifikasi Sertifikat
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="p-4 md:p-8">
        <div class="max-w-4xl mx-auto">
            <!-- Verification Header -->
            <div class="text-center mb-8">
                <div class="w-20 h-20 mx-auto mb-4 rounded-full <?= $isValid ? 'bg-accent/20' : 'bg-red-500/20' ?> flex items-center justify-center">
                    <i class="fas <?= $isValid ? 'fa-shield-check text-accent' : 'fa-shield-times text-red-500' ?> text-3xl"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold mb-2">
                    <?= $isValid ? 'Sertifikat Terverifikasi' : 'Sertifikat Tidak Ditemukan' ?>
                </h2>
                <p class="text-gray-400">
                    <?= $isValid ? 'Sertifikat ini sah dan telah diverifikasi oleh ALMAI' : 'Nomor sertifikat yang Anda masukkan tidak valid atau tidak ditemukan' ?>
                </p>
            </div>

            <?php if ($isValid && $certificate): ?>
            <!-- Certificate Details -->
            <div class="verification-bg rounded-2xl p-6 md:p-8 border border-white/10 mb-8">
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Certificate Info -->
                    <div>
                        <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
                            <i class="fas fa-certificate text-accent"></i>
                            Detail Sertifikat
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-400 mb-1">Nomor Sertifikat</p>
                                <p class="font-mono text-accent font-bold"><?= esc($certificate['certificate_number']) ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400 mb-1">Penerima</p>
                                <p class="font-bold text-lg"><?= esc($certificate['user_name']) ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400 mb-1">Layanan</p>
                                <p class="font-medium"><?= esc($certificate['layanan_name']) ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400 mb-1">Instruktur</p>
                                <p class="font-medium"><?= esc($certificate['wpa_name'] ?? 'ALMAI Team') ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-400 mb-1">Tanggal Terbit</p>
                                <p class="font-medium"><?= date('d F Y', strtotime($certificate['issued_at'])) ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Verification Status -->
                    <div>
                        <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
                            <i class="fas fa-check-circle text-accent"></i>
                            Status Verifikasi
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3 p-3 bg-accent/10 border border-accent/30 rounded-lg">
                                <i class="fas fa-check text-accent"></i>
                                <span class="text-sm">Sertifikat Valid</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-accent/10 border border-accent/30 rounded-lg">
                                <i class="fas fa-check text-accent"></i>
                                <span class="text-sm">Diterbitkan oleh ALMAI</span>
                            </div>
                            <div class="flex items-center gap-3 p-3 bg-accent/10 border border-accent/30 rounded-lg">
                                <i class="fas fa-check text-accent"></i>
                                <span class="text-sm">Layanan Diselesaikan</span>
                            </div>
                            <?php if ($completion): ?>
                            <div class="flex items-center gap-3 p-3 bg-accent/10 border border-accent/30 rounded-lg">
                                <i class="fas fa-check text-accent"></i>
                                <span class="text-sm">Diselesaikan pada <?= date('d M Y', strtotime($completion['completed_at'])) ?></span>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- QR Code -->
                        <div class="mt-6 text-center">
                            <div class="w-32 h-32 bg-white rounded-lg flex items-center justify-center mx-auto mb-3">
                                <div class="text-black text-center">
                                    <i class="fas fa-qrcode text-4xl"></i>
                                    <p class="text-xs mt-1">QR CODE</p>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500">Scan untuk verifikasi cepat</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?= base_url('certificate/' . $certificate['certificate_number']) ?>" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                    <i class="fas fa-eye"></i> Lihat Sertifikat
                </a>
                <a href="<?= base_url('certificate/' . $certificate['certificate_number'] . '/download') ?>" class="inline-flex items-center justify-center gap-2 px-6 py-3 border border-accent text-accent font-bold rounded-xl hover:bg-accent hover:text-black transition">
                    <i class="fas fa-download"></i> Download PDF
                </a>
                <button onclick="copyVerifyUrl()" class="inline-flex items-center justify-center gap-2 px-6 py-3 border border-white/20 text-white font-bold rounded-xl hover:border-accent hover:text-accent transition">
                    <i class="fas fa-share"></i> Share
                </button>
            </div>

            <?php else: ?>
            <!-- Certificate Not Found -->
            <div class="verification-bg rounded-2xl p-8 border border-red-500/30 text-center">
                <div class="w-20 h-20 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-exclamation-triangle text-red-500 text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-4">Sertifikat Tidak Valid</h3>
                <p class="text-gray-400 mb-6">
                    Nomor sertifikat <span class="font-mono text-red-400"><?= esc($certificateNumber) ?></span> tidak ditemukan dalam database kami.
                </p>
                <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4 mb-6">
                    <p class="text-sm text-red-400">
                        <i class="fas fa-info-circle mr-2"></i>
                        Pastikan Anda memasukkan nomor sertifikat yang benar atau hubungi ALMAI untuk bantuan.
                    </p>
                </div>
                <a href="<?= base_url() ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                    <i class="fas fa-home"></i> Kembali ke Beranda
                </a>
            </div>
            <?php endif; ?>

            <!-- Verification Info -->
            <div class="mt-8 text-center">
                <div class="bg-[#111] border border-white/10 rounded-xl p-6">
                    <h4 class="font-bold mb-4">Tentang Verifikasi Sertifikat</h4>
                    <p class="text-sm text-gray-400 mb-4">
                        Sistem verifikasi ALMAI memastikan keaslian setiap sertifikat yang diterbitkan. 
                        Setiap sertifikat memiliki nomor unik yang dapat diverifikasi secara online.
                    </p>
                    <div class="grid md:grid-cols-3 gap-4 text-xs">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-shield-alt text-accent"></i>
                            <span>Keamanan Terjamin</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-database text-accent"></i>
                            <span>Database Terpusat</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-clock text-accent"></i>
                            <span>Verifikasi Real-time</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-[#0a0a0a] border-t border-white/10 px-4 md:px-8 py-6 mt-12">
        <div class="max-w-6xl mx-auto text-center">
            <p class="text-sm text-gray-500">
                © 2026 ALMAI. Semua hak dilindungi. | 
                <a href="<?= base_url() ?>" class="text-accent hover:text-white transition">almai.id</a>
            </p>
        </div>
    </footer>

    <script>
        function copyVerifyUrl() {
            const url = window.location.href;
            navigator.clipboard.writeText(url).then(function() {
                alert('Link verifikasi berhasil disalin!');
            });
        }
    </script>
</body>
</html>