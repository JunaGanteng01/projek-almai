<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Login - Almai E-Learning') ?></title>
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <link rel="stylesheet" href="<?= base_url('css/tailwind.min.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Register v2.0 Script -->
    <script src="<?= base_url('assets/js/register_v2.js') ?>" defer></script>

    <style>
        html, body {
            background-color: #050505;
            color: #ffffff;
            overflow-x: hidden;
        }

        .bg-grid {
            background-size: 40px 40px;
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px), linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
        }
    </style>
</head>

<body class="antialiased min-h-screen relative overflow-x-hidden">
    <!-- Navbar -->
    <?= $this->include('partials/navbar') ?>

    <div class="absolute inset-0 bg-grid z-0 pointer-events-none"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[300px] md:w-[800px] h-[300px] md:h-[500px] bg-accent/20 blur-[120px] rounded-full pointer-events-none"></div>

    <div class="relative z-10 w-full max-w-md px-6 mx-auto pt-28 pb-12 min-h-screen flex flex-col justify-center">
        <!-- Logo -->
        <div class="flex flex-col items-center justify-center mb-8">
            <a href="<?= base_url('/') ?>" class="flex items-center justify-center gap-1 text-2xl font-bold mb-1 tracking-tight">
                <span id="logoText"><?= empty($showRegister) ? 'LOGIN' : 'DAFTAR ALMAI v2.0' ?></span>
            </a>
            <p id="subLogoText" class="text-gray-400 text-sm tracking-widest mt-1"><?= empty($showRegister) ? 'Selamat Datang Kembali' : 'Sistem Registrasi Baru' ?></p>
        </div>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-accent/20 border border-accent/30 text-accent px-4 py-3 rounded-xl mb-4 text-sm">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl mb-4 text-sm">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Tab Buttons -->
        <div class="flex mb-6 bg-[#111] rounded-full p-1 border border-white/10">
            <button id="loginTab" onclick="showTab('login')" class="flex-1 py-3 rounded-full font-bold transition <?= empty($showRegister) ? 'bg-accent text-black' : 'text-gray-400' ?>">Login</button>
            <button id="registerTab" onclick="showTab('register')" class="flex-1 py-3 rounded-full font-bold transition <?= !empty($showRegister) ? 'bg-accent text-black' : 'text-gray-400' ?>">Daftar</button>
        </div>

        <!-- Login Form -->
        <div id="loginForm" class="bg-[#111] rounded-2xl border border-white/10 p-8 <?= !empty($showRegister) ? 'hidden' : '' ?>">
            <form action="<?= base_url('login') ?>" method="post" class="space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Email</label>
                    <input type="text" name="email" required value="<?= old('email') ?>" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 text-white focus:border-accent focus:outline-none" placeholder="Masukan Email Anda">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Kata Sandi</label>
                    <input type="password" name="password" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 text-white focus:border-accent focus:outline-none" placeholder="••••••••">
                </div>
                <div class="flex justify-between items-center text-sm">
                    <label class="flex items-center gap-2 text-gray-400">
                        <input type="checkbox" name="remember" class="accent-accent"> Ingat saya
                    </label>
                    <a href="<?= base_url('forgot-password') ?>" class="text-accent hover:underline">Lupa Kata Sandi?</a>
                </div>
                <button type="submit" class="w-full py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition shadow-[0_0_20px_rgba(51,232,24,0.3)]">
                    Login
                </button>
            </form>
        </div>

        <!-- Register Section (ALMAI REGISTRATION SYSTEM v2.0) -->
        <div id="registerSection" class="bg-[#111] rounded-2xl border border-white/10 p-6 md:p-8 <?= empty($showRegister) ? 'hidden' : '' ?>">
            <div id="reg_error_box" class="hidden bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl mb-4 text-sm"></div>

            <form id="formRegisterV2" class="space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Nama Lengkap <span class="text-red-400">*</span></label>
                    <input type="text" id="reg_name" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 text-white focus:border-accent focus:outline-none" placeholder="Nama lengkap sesuai identitas">
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Email <span class="text-red-400">*</span></label>
                    <input type="email" id="reg_email" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 text-white focus:border-accent focus:outline-none" placeholder="contoh@domain.com">
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Kata Sandi <span class="text-red-400">*</span></label>
                    <input type="password" id="reg_password" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 text-white focus:border-accent focus:outline-none" placeholder="Minimal 8 karakter">
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Konfirmasi Kata Sandi <span class="text-red-400">*</span></label>
                    <input type="password" id="reg_confirm_password" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 text-white focus:border-accent focus:outline-none" placeholder="Ulangi kata sandi">
                </div>

                <!-- Referral / WPA Selection (WAJIB) -->
                <div>
                    <label class="block text-sm text-gray-400 mb-2">WPA / Affiliator Pendamping <span class="text-red-400">*</span></label>
                    <?php 
                    $getRef = service('request')->getGet('ref');
                    $sessionRef = session()->get('affiliate_code');
                    $cookieRef = get_cookie('affiliate_code');

                    $hasReferral = !empty($getRef) || !empty($sessionRef) || !empty($cookieRef);
                    $finalRef = !empty($getRef) ? $getRef : ($sessionRef ?: $cookieRef);
                    $initialCode = $hasReferral ? $finalRef : 'wpa-bali';

                    $referrerName = strtoupper(esc($initialCode));
                    if ($hasReferral) {
                        $userModel = new \App\Models\UserModel();
                        $refUser = $userModel->findByReferralCode($finalRef);
                        if ($refUser && !empty($refUser['name'])) {
                            $referrerName = esc($refUser['name']);
                        }
                    }
                    ?>
                    <input type="hidden" id="hiddenAffiliateCode" value="<?= esc($initialCode) ?>">
                    
                    <?php if ($hasReferral): ?>
                        <!-- Referral Link Mode: Exact Requested Locked Format -->
                        <div class="bg-black/90 border border-accent/40 rounded-xl p-4 space-y-1.5">
                            <div class="flex items-center justify-between text-xs font-bold text-accent tracking-wider uppercase">
                                <span><i class="fas fa-lock text-[10px] mr-1"></i> REFERRAL TERKUNCI</span>
                            </div>
                            <div class="text-sm font-extrabold text-white tracking-wide">
                                REFERRAL ANDA : <span class="text-accent"><?= esc($referrerName) ?></span>
                            </div>
                            <p class="text-[11px] text-gray-400">Anda mendaftar menggunakan link referral resmi.</p>
                        </div>
                    <?php else: ?>
                        <!-- Direct Mode: Show Selector Button -->
                        <div class="flex items-center justify-between bg-black border border-white/20 rounded-xl p-3">
                            <span id="displayAffiliateName" class="text-sm font-semibold text-accent">
                                <?= strtoupper(esc($initialCode)) ?>
                            </span>
                            <button type="button" id="btnSelectAffiliate" class="px-3.5 py-1.5 bg-accent/20 hover:bg-accent text-accent hover:text-black border border-accent/40 rounded-lg text-xs font-bold transition-all">
                                🤝 PILIH WPA / AFFILIATOR
                            </button>
                        </div>
                        <p class="text-[11px] text-gray-500 mt-1">Silakan pilih penasihat resmi pendamping aktivitas edukasi Anda.</p>
                    <?php endif; ?>
                </div>

                <button type="submit" id="btnSubmitRegV2" class="w-full py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition shadow-[0_0_20px_rgba(51,232,24,0.3)] mt-2">
                    Daftar & Lanjutkan via WhatsApp ➔
                </button>
            </form>
        </div>
    </div>

    <!-- MODAL 1: WPA / Affiliator Selector -->
    <div id="modalAffiliateSelector" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-slate-900 border border-slate-700 w-full max-w-lg rounded-2xl p-6 relative max-h-[85vh] overflow-y-auto">
            <button type="button" id="closeModalAffiliate" class="absolute top-4 right-4 text-slate-400 hover:text-white">
                <i class="fas fa-times text-xl"></i>
            </button>

            <h3 class="text-lg font-bold text-white mb-1">Pilih WPA / Affiliator Anda</h3>
            <p class="text-xs text-slate-400 mb-4">Pilih penasihat resmi yang akan mendampingi aktivitas edukasi dan analisa Anda di ALMAI.</p>

            <div class="space-y-3">
                <?php
                $wpas = [
                    ['slug' => 'wpa-bali', 'name' => 'WPA BALI'],
                    ['slug' => 'wpa-surabaya', 'name' => 'WPA SURABAYA'],
                    ['slug' => 'wpa-jakarta', 'name' => 'WPA JAKARTA']
                ];
                foreach ($wpas as $w):
                    $code = $w['slug'];
                    $name = $w['name'];
                ?>
                <div class="bg-slate-800 border border-slate-700/80 rounded-xl p-4 flex items-center justify-between hover:border-emerald-500/50 transition-all">
                    <div class="font-bold text-white text-base tracking-wide"><?= esc($name) ?></div>
                    <button type="button" data-code="<?= esc($code) ?>" data-name="<?= esc($name) ?>" class="btn-choose-wpa px-5 py-2 bg-accent hover:bg-white text-slate-950 font-extrabold rounded-xl text-xs transition-all shadow-[0_0_15px_rgba(51,232,24,0.3)]">
                        Pilih
                    </button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- MODAL 2: WA Verification Waiting Screen -->
    <div id="modalWaWaiting" class="fixed inset-0 bg-black/90 backdrop-blur-md z-50 flex items-center justify-center p-4 hidden">
        <div class="bg-slate-900 border border-slate-700 w-full max-w-md rounded-2xl p-8 text-center relative shadow-2xl">
            <!-- Spinner -->
            <div class="w-16 h-16 border-4 border-emerald-500/30 border-t-emerald-400 rounded-full animate-spin mx-auto mb-6"></div>

            <h3 class="text-xl font-bold text-white mb-2">Verifikasi WhatsApp Berlangsung</h3>
            <p class="text-sm text-slate-400 mb-6">Aplikasi WhatsApp Anda telah dibuka. Silakan tekan tombol **KIRIM** pada percakapan WhatsApp yang telah disiapkan.</p>

            <a id="btnLaunchWa" href="#" target="_blank" class="w-full py-3.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold rounded-xl flex items-center justify-center gap-2 transition-all text-sm mb-4 shadow-lg shadow-emerald-500/20">
                <i class="fab fa-whatsapp text-lg"></i> 🟢 BUKA WHATSAPP & KIRIM VERIFIKASI
            </a>

            <p class="text-xs text-slate-500 animate-pulse">Sistem sedang mendeteksi konfirmasi WhatsApp Anda... (Polling otomatis)</p>
        </div>
    </div>

    <script>
        function showTab(tab) {
            if (tab === 'login') {
                document.getElementById('loginForm').classList.remove('hidden');
                document.getElementById('registerSection').classList.add('hidden');
                document.getElementById('loginTab').classList.add('bg-accent', 'text-black');
                document.getElementById('loginTab').classList.remove('text-gray-400');
                document.getElementById('registerTab').classList.remove('bg-accent', 'text-black');
                document.getElementById('registerTab').classList.add('text-gray-400');
                document.getElementById('logoText').textContent = 'LOGIN';
            } else {
                document.getElementById('loginForm').classList.add('hidden');
                document.getElementById('registerSection').classList.remove('hidden');
                document.getElementById('registerTab').classList.add('bg-accent', 'text-black');
                document.getElementById('registerTab').classList.remove('text-gray-400');
                document.getElementById('loginTab').classList.remove('bg-accent', 'text-black');
                document.getElementById('loginTab').classList.add('text-gray-400');
                document.getElementById('logoText').textContent = 'DAFTAR ALMAI v2.0';
            }
        }
    </script>
</body>
</html>
