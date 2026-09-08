<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Login - Almai E-Learning') ?></title>
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'accent': '#33e818',
                        'accent-hover': '#2bc214',
                    },
                    fontFamily: {
                        sans: ['Montserrat', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Cloudflare Turnstile -->
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

    <style>
        html,
        body {
            background-color: #050505;
            color: #ffffff;
            overflow-x: hidden;
        }

        .bg-grid {
            background-size: 40px 40px;
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px), linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
        }

        /* Turnstile Full Width */
        .turnstile-wrap {
            width: 100%;
            max-width: 100%;
            overflow: visible;
        }

        .cf-turnstile {
            width: 100% !important;
            display: flex;
            justify-content: center;
            overflow: visible;
        }

        .cf-turnstile > div {
            width: 100% !important;
        }

        .cf-turnstile iframe {
            width: 100% !important;
            max-width: 100% !important;
            border-radius: 0.75rem !important;
        }

        @media (max-width: 420px) {
            .cf-turnstile {
                min-height: 70px;
            }

            .turnstile-wrap {
                transform: scale(0.9);
                transform-origin: top left;
                width: calc(100% / 0.9);
            }
        }

        @media (max-width: 360px) {
            .turnstile-wrap {
                transform: scale(0.85);
                transform-origin: top left;
                width: calc(100% / 0.85);
            }
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
                <span id="logoText"><?= empty($showRegister) ? 'LOGIN' : 'DAFTAR' ?></span>
            </a>
            <p id="subLogoText" class="text-gray-400 text-sm tracking-widest mt-1"><?= empty($showRegister) ? 'Selamat Datang' : 'Buat Akun Baru' ?></p>
        </div>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-accent/20 border border-accent/30 text-accent px-4 py-3 rounded-xl mb-4">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl mb-4">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl mb-4">
                <ul class="list-disc list-inside">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($redirect)): ?>
            <div class="bg-blue-500/20 border border-blue-500/30 text-blue-400 px-4 py-3 rounded-xl mb-4 text-sm">
                <i class="fas fa-info-circle mr-2"></i> Silakan login atau daftar untuk melanjutkan pembelian
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
                    <input type="text" name="email" required value="<?= old('email') ?>" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" placeholder="Masukan Email Anda">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Kata Sandi</label>
                    <div class="relative">
                        <input type="password" name="password" id="loginPassword" autocomplete="current-password" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" placeholder="••••••••">
                        <button type="button" onclick="togglePassword('loginPassword', this)" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <label class="flex items-center gap-2 text-gray-400">
                        <input type="checkbox" name="remember" class="accent-accent"> Ingat saya
                    </label>
                    <?php 
                    $text = urlencode("Halo Tim ALMAI, saya lupa password login Almai.id. \n\nMohon diproses.\n\nTerima kasih");
                    ?>
                    <a href="https://wa.me/<?= env('NO_OTP') ?>?text=<?= $text ?>" class="text-accent hover:underline">Lupa Kata Sandi?</a>
                    <!-- <a href="<?= base_url('forgot-password') ?>" class="text-accent hover:underline">Lupa Kata Sandi?</a> -->
                </div>

                <!-- Cloudflare Turnstile Widget -->
                <div class="turnstile-wrap">
                    <div class="cf-turnstile w-full"
                        data-sitekey="<?= env('TURNSTILE_SITE_KEY', '0x4AAAAAAAzM8JmJ8PGJ_YbS') ?>"
                        data-theme="dark"
                        data-size="flexible"
                        style="width: 100%;">
                    </div>
                </div>

                <button type="submit" class="w-full py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition shadow-[0_0_20px_rgba(51,232,24,0.3)]">
                    Login
                </button>
            </form>
        </div>

        <!-- Register Section -->
        <div id="registerSection" class="bg-[#111] rounded-2xl border border-white/10 p-6 md:p-8 <?= empty($showRegister) ? 'hidden' : '' ?>">
            
            <!-- Register Type Chooser (Inside Form) -->
            <div class="flex bg-[#0a0a0a] rounded-full p-1 border border-white/10 mb-6" id="regTypeToggle">
                <button id="btnRegUser" onclick="selectRegType('user')" class="flex-1 py-2.5 rounded-full text-sm font-semibold transition-all duration-200 flex items-center justify-center gap-2 text-gray-300 hover:text-white">
                    <i class="fas fa-user text-xs"></i> Daftar User
                </button>
                <button id="btnRegWpa" onclick="selectRegType('wpa')" class="flex-1 py-2.5 rounded-full text-sm font-semibold transition-all duration-200 flex items-center justify-center gap-2 text-gray-300 hover:text-white">
                    <i class="fas fa-briefcase text-xs"></i> Daftar WPA
                </button>
            </div>

            <!-- Register Form (User) -->
            <div id="registerForm" class="hidden">
                <form id="registerFormEl" class="space-y-4">
                    <?= csrf_field() ?>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Nama Lengkap <span class="text-red-400">*</span></label>
                    <input type="text" name="name" id="regName" required value="<?= old('name') ?>" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" placeholder="Nama lengkap Anda">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Email <span class="text-red-400">*</span></label>
                    <input type="email" name="email" id="regEmail" required value="<?= old('email') ?>" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" placeholder="Masukan Email Anda">
                </div>
                <!-- <div>
                    <label class="block text-sm text-gray-400 mb-2">No. WhatsApp <span class="text-red-400">*</span></label>
                    <input type="tel" name="phone" id="regPhone" required value="<?= old('phone') ?>" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" placeholder="08xxxxxxxxxx">
                </div> -->
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Kode Referral <span class="text-red-400">*</span></label>
                    <div class="relative">
                        <?php
                        $getRef = service('request')->getGet('ref');
                        $redirectUrl = service('request')->getGet('redirect');
                        if (empty($getRef) && $redirectUrl) {
                            $parsedRedirect = parse_url($redirectUrl);
                            if (isset($parsedRedirect['query'])) {
                                parse_str($parsedRedirect['query'], $redirectQuery);
                                $getRef = $redirectQuery['ref'] ?? $redirectQuery['reff'] ?? '';
                            }
                        }
                        
                        $sessionRef = session()->get('affiliate_code');
                        $cookieRef = get_cookie('affiliate_code');

                        $finalRef = !empty($getRef) ? $getRef : ($sessionRef ?? $cookieRef);

                        $inputValue = old('affiliate_code') ?: $finalRef;
                        ?>
                        <input type="text" name="affiliate_code" id="regAffiliateCode" required value="<?= esc($inputValue) ?>" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 pl-12 focus:border-accent focus:outline-none uppercase cursor-not-allowed <?= !empty($finalRef) ? 'text-accent font-bold border-accent/50' : 'text-gray-400' ?>" placeholder="Pilih di 'Lihat Daftar WPA' di bawah" readonly onkeydown="return false;">
                        <i class="fas fa-user-tag absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
                        <?php if (!empty($finalRef)): ?>
                            <i class="fas fa-lock absolute right-4 top-1/2 -translate-y-1/2 text-accent" title="Kode Referral Terkunci"></i>
                        <?php endif; ?>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Jika belum punya, silakan pilih salah satu WPA dan ikuti otomatis di profilnya <a href="<?= base_url('wpa') ?>" class="text-accent hover:underline font-bold">Lihat Daftar WPA</a>.</p>
                </div>
                <label class="flex items-start gap-2 text-gray-400 text-sm">
                    <input type="checkbox" name="terms" id="regTerms" required class="accent-accent mt-1">
                    <span>Saya setuju dengan <a href="<?= base_url('syarat-ketentuan') ?>" class="text-accent hover:underline">Syarat & Ketentuan</a> serta <a href="<?= base_url('kebijakan-privasi') ?>" class="text-accent hover:underline">Kebijakan Privasi</a></span>
                </label>

                <!-- Cloudflare Turnstile Widget -->
                <div class="turnstile-wrap mt-2">
                    <div class="cf-turnstile w-full"
                        data-sitekey="<?= env('TURNSTILE_SITE_KEY', '0x4AAAAAAAzM8JmJ8PGJ_YbS') ?>"
                        data-theme="dark"
                        data-size="flexible"
                        style="width: 100%;">
                    </div>
                </div>

                <button type="submit" id="registerBtn" class="w-full py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition shadow-[0_0_20px_rgba(51,232,24,0.3)] disabled:opacity-50 disabled:cursor-not-allowed">
                    Daftar Sekarang
                </button>
            </form>
        </div>

        <!-- Register WPA Multi-Step Form -->
        <div id="registerWpaCard" class="hidden">
            <div class="w-full">
                <!-- WPA Step Progress -->
                <div class="flex items-center gap-1 mb-5" id="wpaProgressBar">
                    <div class="wpa-step-dot flex-1 h-1 rounded-full bg-accent transition-all duration-300" data-wpa-step="1"></div>
                    <div class="wpa-step-dot flex-1 h-1 rounded-full bg-white/10 transition-all duration-300" data-wpa-step="2"></div>
                    <div class="wpa-step-dot flex-1 h-1 rounded-full bg-white/10 transition-all duration-300" data-wpa-step="3"></div>
                    <div class="wpa-step-dot flex-1 h-1 rounded-full bg-white/10 transition-all duration-300" data-wpa-step="4"></div>
                    <div class="wpa-step-dot flex-1 h-1 rounded-full bg-white/10 transition-all duration-300" data-wpa-step="5"></div>
                    <div class="wpa-step-dot flex-1 h-1 rounded-full bg-white/10 transition-all duration-300" data-wpa-step="6"></div>
                    <div class="wpa-step-dot flex-1 h-1 rounded-full bg-white/10 transition-all duration-300" data-wpa-step="7"></div>
                    <div class="wpa-step-dot flex-1 h-1 rounded-full bg-white/10 transition-all duration-300" data-wpa-step="8"></div>
                </div>

                <form id="wpaFormEl" enctype="multipart/form-data" novalidate>
                    <?= csrf_field() ?>

                    <!-- WPA Step 1: Akun -->
                    <div class="wpa-step space-y-4" data-wpa-step="1">
                        <h3 class="font-bold text-white">Buat Akun WPA</h3>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Nama Lengkap <span class="text-red-400">*</span></label>
                            <input type="text" name="name" id="wpaName" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none text-white" placeholder="Nama lengkap Anda">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Email <span class="text-red-400">*</span></label>
                            <input type="email" name="email" id="wpaEmail" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none text-white" placeholder="Masukan Email Anda">
                        </div>
                        <!-- Hidden WhatsApp input: user will send message to admin via wa.me -->
                        <input type="hidden" name="whatsapp" id="wpaPhone" value="">
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Kode Referral <span class="text-red-400">*</span></label>
                            <div class="relative">
                                <?php
                                $getRef = service('request')->getGet('ref');
                                $redirectUrl = service('request')->getGet('redirect');
                                if (empty($getRef) && $redirectUrl) {
                                    $parsedRedirect = parse_url($redirectUrl);
                                    if (isset($parsedRedirect['query'])) {
                                        parse_str($parsedRedirect['query'], $redirectQuery);
                                        $getRef = $redirectQuery['ref'] ?? $redirectQuery['reff'] ?? '';
                                    }
                                }
                                $sessionRef = session()->get('affiliate_code');
                                $cookieRef = get_cookie('affiliate_code');
                                $finalRef = !empty($getRef) ? $getRef : ($sessionRef ?? $cookieRef);
                                $inputValue = old('kode_affiliator') ?: $finalRef;
                                ?>
                                <input type="text" name="kode_affiliator" id="wpaAffiliateCode" required value="<?= esc($inputValue) ?>" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 pl-12 focus:border-accent focus:outline-none uppercase cursor-not-allowed <?= !empty($finalRef) ? 'text-accent font-bold border-accent/50' : 'text-gray-400' ?>" placeholder="Pilih di 'Lihat Daftar WPA' di bawah" readonly onkeydown="return false;">
                                <i class="fas fa-user-tag absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
                                <?php if (!empty($finalRef)): ?>
                                    <i class="fas fa-lock absolute right-4 top-1/2 -translate-y-1/2 text-accent" title="Kode Referral Terkunci"></i>
                                <?php endif; ?>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Jika belum punya, silakan pilih salah satu WPA dan ikuti otomatis di profilnya <a href="<?= base_url('wpa') ?>" class="text-accent hover:underline font-bold">Lihat Daftar WPA</a>.</p>
                        </div>
                        <label class="flex items-start gap-2 text-gray-400 text-sm mt-2">
                            <input type="checkbox" name="terms_wpa" id="regTermsWpa" required class="accent-accent mt-1">
                            <span>Saya setuju dengan <a href="<?= base_url('syarat-ketentuan') ?>" target="_blank" class="text-accent hover:underline">Syarat &amp; Ketentuan</a> serta <a href="<?= base_url('kebijakan-privasi') ?>" target="_blank" class="text-accent hover:underline">Kebijakan Privasi</a></span>
                        </label>

                        <!-- Cloudflare Turnstile Widget -->
                        <div class="turnstile-wrap mt-2">
                            <div class="cf-turnstile w-full"
                                data-sitekey="<?= env('TURNSTILE_SITE_KEY', '0x4AAAAAAAzM8JmJ8PGJ_YbS') ?>"
                                data-theme="dark"
                                data-size="flexible"
                                style="width: 100%;">
                            </div>
                        </div>
                    </div>

                    <!-- WPA Step 2: Data Diri -->
                    <div class="wpa-step hidden space-y-4" data-wpa-step="2">
                        <h3 class="font-bold text-white">Data Diri</h3>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Nomor KTP (16 Digit) <span class="text-red-400">*</span></label>
                            <input type="text" name="ktp_number" maxlength="16" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" placeholder="3201234567890123">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Nomor NPWP <span class="text-red-400">*</span></label>
                            <input type="text" name="npwp_number" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" placeholder="123456789012345">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Alamat Lengkap <span class="text-red-400">*</span></label>
                            <textarea name="address" required rows="3" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none resize-none" placeholder="Alamat sesuai KTP"></textarea>
                        </div>
                    </div>

                    <!-- WPA Step 3: Media Sosial -->
                    <div class="wpa-step hidden space-y-4" data-wpa-step="3">
                        <h3 class="font-bold text-white">Akun Media Sosial</h3>
                        <p class="text-gray-400 text-xs">Isi minimal salah satu akun media sosial</p>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2"><i class="fab fa-instagram text-pink-500 mr-1"></i> Instagram</label>
                            <input type="text" name="instagram" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" placeholder="@username">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2"><i class="fab fa-tiktok mr-1"></i> TikTok</label>
                            <input type="text" name="tiktok" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" placeholder="@username">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2"><i class="fab fa-facebook text-blue-500 mr-1"></i> Facebook</label>
                            <input type="text" name="facebook" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" placeholder="URL profil">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2"><i class="fab fa-youtube text-red-500 mr-1"></i> YouTube</label>
                            <input type="text" name="youtube" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" placeholder="URL channel">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2"><i class="fab fa-linkedin text-blue-600 mr-1"></i> LinkedIn</label>
                            <input type="text" name="linkedin" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" placeholder="URL profil">
                        </div>
                    </div>

                    <!-- WPA Step 4: Spesialisasi -->
                    <div class="wpa-step hidden space-y-3" data-wpa-step="4">
                        <h3 class="font-bold text-white">Pengalaman &amp; Spesialisasi</h3>
                        <p class="text-gray-400 text-xs mb-2">Pilih minimal satu bidang</p>
                        <label class="flex items-center gap-3 p-3 bg-white/5 rounded-xl border border-white/10 hover:border-accent transition cursor-pointer">
                            <input type="checkbox" name="specialties[]" value="kripto" class="w-4 h-4">
                            <span class="text-gray-300 text-sm">Kripto, Index &amp; Aset Digital</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-white/5 rounded-xl border border-white/10 hover:border-accent transition cursor-pointer">
                            <input type="checkbox" name="specialties[]" value="forex" class="w-4 h-4">
                            <span class="text-gray-300 text-sm">Forex</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-white/5 rounded-xl border border-white/10 hover:border-accent transition cursor-pointer">
                            <input type="checkbox" name="specialties[]" value="komoditi" class="w-4 h-4">
                            <span class="text-gray-300 text-sm">Komoditi</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 bg-accent/10 rounded-xl border border-accent/30 hover:border-accent transition cursor-pointer">
                            <input type="checkbox" id="wpaSelectAll" class="w-4 h-4">
                            <span class="text-accent font-semibold text-sm">Pilih Semua</span>
                        </label>
                    </div>

                    <!-- WPA Step 5: Persyaratan -->
                    <div class="wpa-step hidden space-y-5" data-wpa-step="5">
                        <h3 class="font-bold text-white">Persyaratan C-WPA</h3>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Apakah Anda memiliki Ijazah S1? <span class="text-red-400">*</span></label>
                            <div class="flex gap-3">
                                <label class="flex-1 flex items-center gap-2 p-3 bg-white/5 rounded-xl border border-white/10 hover:border-accent cursor-pointer">
                                    <input type="radio" name="has_ijazah_s1" value="yes" required class="require-check-disqualification"> <span class="text-sm">Ya</span>
                                </label>
                                <label class="flex-1 flex items-center gap-2 p-3 bg-white/5 rounded-xl border border-white/10 hover:border-accent cursor-pointer">
                                    <input type="radio" name="has_ijazah_s1" value="no" class="require-check-disqualification"> <span class="text-sm">Tidak</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Apakah Anda memiliki SKCK? <span class="text-red-400">*</span></label>
                            <div class="flex gap-3">
                                <label class="flex-1 flex items-center gap-2 p-3 bg-white/5 rounded-xl border border-white/10 hover:border-accent cursor-pointer">
                                    <input type="radio" name="has_skck_card" value="yes" required class="require-toggle-skck-willingness require-check-disqualification"> <span class="text-sm">Ya</span>
                                </label>
                                <label class="flex-1 flex items-center gap-2 p-3 bg-white/5 rounded-xl border border-white/10 hover:border-accent cursor-pointer">
                                    <input type="radio" name="has_skck_card" value="no" class="require-toggle-skck-willingness require-check-disqualification"> <span class="text-sm">Tidak</span>
                                </label>
                            </div>
                        </div>
                        
                        <div id="skckWillingnessSection" class="hidden">
                            <label class="block text-sm text-gray-400 mb-2">Bersedia membuat SKCK? <span class="text-red-400">*</span></label>
                            <div class="flex gap-3">
                                <label class="flex-1 flex items-center gap-2 p-3 bg-white/5 rounded-xl border border-white/10 hover:border-accent cursor-pointer">
                                    <input type="radio" name="willing_to_make_skck" value="yes" class="require-check-disqualification"> <span class="text-sm">Ya</span>
                                </label>
                                <label class="flex-1 flex items-center gap-2 p-3 bg-white/5 rounded-xl border border-white/10 hover:border-accent cursor-pointer">
                                    <input type="radio" name="willing_to_make_skck" value="no" class="require-check-disqualification"> <span class="text-sm">Tidak</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Pernah dipidana &gt;5 tahun? <span class="text-red-400">*</span></label>
                            <div class="flex gap-3">
                                <label class="flex-1 flex items-center gap-2 p-3 bg-white/5 rounded-xl border border-white/10 hover:border-accent cursor-pointer">
                                    <input type="radio" name="has_felony_record" value="yes" required class="require-toggle-felony-willingness require-check-disqualification"> <span class="text-sm">Ya</span>
                                </label>
                                <label class="flex-1 flex items-center gap-2 p-3 bg-white/5 rounded-xl border border-white/10 hover:border-accent cursor-pointer">
                                    <input type="radio" name="has_felony_record" value="no" class="require-toggle-felony-willingness require-check-disqualification"> <span class="text-sm">Tidak</span>
                                </label>
                            </div>
                        </div>
                        
                        <div id="felonyWillingnessSection" class="hidden">
                            <label class="block text-sm text-gray-400 mb-2 text-xs">Bersedia membuat SURAT KETERANGAN TIDAK PERNAH SEBAGAI TERPIDANA? <span class="text-red-400">*</span></label>
                            <div class="flex gap-3">
                                <label class="flex-1 flex items-center gap-2 p-3 bg-white/5 rounded-xl border border-white/10 hover:border-accent cursor-pointer">
                                    <input type="radio" name="willing_to_make_felony_statement" value="yes" class="require-check-disqualification"> <span class="text-sm">Ya</span>
                                </label>
                                <label class="flex-1 flex items-center gap-2 p-3 bg-white/5 rounded-xl border border-white/10 hover:border-accent cursor-pointer">
                                    <input type="radio" name="willing_to_make_felony_statement" value="no" class="require-check-disqualification"> <span class="text-sm">Tidak</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Pernah dinyatakan pailit? <span class="text-red-400">*</span></label>
                            <div class="flex gap-3">
                                <label class="flex-1 flex items-center gap-2 p-3 bg-white/5 rounded-xl border border-white/10 hover:border-accent cursor-pointer">
                                    <input type="radio" name="has_bankruptcy_record" value="yes" required class="require-check-disqualification"> <span class="text-sm">Ya</span>
                                </label>
                                <label class="flex-1 flex items-center gap-2 p-3 bg-white/5 rounded-xl border border-white/10 hover:border-accent cursor-pointer">
                                    <input type="radio" name="has_bankruptcy_record" value="no" class="require-check-disqualification"> <span class="text-sm">Tidak</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- WPA Step 6: Dokumen -->
                    <div class="wpa-step hidden space-y-4" data-wpa-step="6">
                        <h3 class="font-bold text-white">Lengkapi Dokumen</h3>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">CV <span class="text-red-400">*</span></label>
                            <label for="wpa_cv" class="flex flex-col items-center gap-2 border-2 border-dashed border-white/20 rounded-xl p-5 cursor-pointer hover:border-accent transition text-center">
                                <i class="fas fa-cloud-upload-alt text-2xl text-accent"></i>
                                <span class="text-gray-400 text-xs">PDF, DOC, DOCX – Max 10MB</span>
                                <span id="wpa_cv_name" class="text-accent text-xs font-semibold"></span>
                            </label>
                            <input type="file" id="wpa_cv" name="cv" accept=".pdf,.doc,.docx" required class="hidden" onchange="document.getElementById('wpa_cv_name').textContent = this.files[0]?.name || ''">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">KTP <span class="text-red-400">*</span></label>
                            <label for="wpa_ktp" class="flex flex-col items-center gap-2 border-2 border-dashed border-white/20 rounded-xl p-5 cursor-pointer hover:border-accent transition text-center">
                                <i class="fas fa-id-card text-2xl text-accent"></i>
                                <span class="text-gray-400 text-xs">PDF, JPG, PNG – Max 10MB</span>
                                <span id="wpa_ktp_name" class="text-accent text-xs font-semibold"></span>
                            </label>
                            <input type="file" id="wpa_ktp" name="ktp_file" accept=".pdf,.jpg,.jpeg,.png" required class="hidden" onchange="document.getElementById('wpa_ktp_name').textContent = this.files[0]?.name || ''">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">NPWP <span class="text-red-400">*</span></label>
                            <label for="wpa_npwp" class="flex flex-col items-center gap-2 border-2 border-dashed border-white/20 rounded-xl p-5 cursor-pointer hover:border-accent transition text-center">
                                <i class="fas fa-file-alt text-2xl text-accent"></i>
                                <span class="text-gray-400 text-xs">PDF, JPG, PNG – Max 10MB</span>
                                <span id="wpa_npwp_name" class="text-accent text-xs font-semibold"></span>
                            </label>
                            <input type="file" id="wpa_npwp" name="npwp_file" accept=".pdf,.jpg,.jpeg,.png" required class="hidden" onchange="document.getElementById('wpa_npwp_name').textContent = this.files[0]?.name || ''">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Ijazah/SKL <span class="text-red-400">*</span></label>
                            <label for="wpa_ijazah" class="flex flex-col items-center gap-2 border-2 border-dashed border-white/20 rounded-xl p-5 cursor-pointer hover:border-accent transition text-center">
                                <i class="fas fa-graduation-cap text-2xl text-accent"></i>
                                <span class="text-gray-400 text-xs">PDF, JPG, PNG – Max 10MB</span>
                                <span id="wpa_ijazah_name" class="text-accent text-xs font-semibold"></span>
                            </label>
                            <input type="file" id="wpa_ijazah" name="ijazah" accept=".pdf,.jpg,.jpeg,.png" required class="hidden" onchange="document.getElementById('wpa_ijazah_name').textContent = this.files[0]?.name || ''">
                        </div>
                    </div>

                    <!-- WPA Step 7: Perjanjian -->
                    <div class="wpa-step hidden space-y-4" data-wpa-step="7">
                        <h3 class="font-bold text-white">Perjanjian</h3>
                        <div class="mb-4 p-4 bg-accent/5 border border-accent/20 rounded-xl space-y-3">
                            <p class="text-white font-medium text-sm">Saya telah memahami dan menyetujui:</p>
                            
                            <div class="flex items-start gap-3 p-3 rounded-xl border border-white/10 bg-black/50 hover:border-accent/50 transition cursor-pointer" onclick="openWpaPerjanjianModal()">
                                <div class="mt-0.5">
                                    <input type="checkbox" name="agreement_wpa" id="agreement_wpa" required disabled class="w-5 h-5 accent-accent rounded cursor-not-allowed opacity-50 flex-shrink-0" onclick="event.stopPropagation();">
                                </div>
                                <div class="flex-1">
                                    <label class="text-sm text-gray-300 font-medium cursor-pointer flex items-center" onclick="event.stopPropagation();">
                                        <i class="fas fa-file-contract mr-1 text-accent"></i> Perjanjian Pendaftaran CWPA
                                    </label>
                                    <span class="text-xs text-gray-500 block mt-1">Klik untuk membaca dan menyetujui</span>
                                </div>
                                <i class="fas fa-chevron-right text-gray-600 self-center"></i>
                            </div>
                        </div>
                    </div>

                    <!-- WPA Step 8: Bayar -->
                    <div class="wpa-step hidden space-y-4" data-wpa-step="8">
                        <h3 class="font-bold text-white">Pembayaran Pendaftaran</h3>
                        <div class="bg-accent/5 border border-accent/20 rounded-xl p-5 space-y-3">
                            <div class="flex justify-between items-center pb-3 border-b border-white/10">
                                <div>
                                    <p class="text-gray-500 text-xs">Bank Tujuan</p>
                                    <p class="text-white font-semibold">BCA - PT Alma Indonesia Raya</p>
                                </div>
                                <i class="fas fa-university text-accent text-xl"></i>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-white/10">
                                <div>
                                    <p class="text-gray-500 text-xs">No. Rekening</p>
                                    <p class="text-accent font-mono font-bold">145-00-5007000-8</p>
                                </div>
                                <button type="button" onclick="navigator.clipboard.writeText('14500500700008')" class="text-gray-400 hover:text-accent transition"><i class="fas fa-copy"></i></button>
                            </div>
                            <div class="flex justify-between pt-1">
                                <span class="text-gray-400 text-sm">Total Biaya</span>
                                <span class="text-accent font-bold">Rp 23.500.000</span>
                            </div>
                            <label class="block text-sm text-gray-400 mb-2">Upload Bukti Transfer <span class="text-xs text-gray-500">(Opsional)</span></label>
                            <label for="wpa_transfer" class="flex flex-col items-center gap-2 border-2 border-dashed border-white/20 rounded-xl p-5 cursor-pointer hover:border-accent transition text-center">
                                <i class="fas fa-file-invoice-dollar text-2xl text-accent"></i>
                                <span id="wpa_transfer_name" class="text-accent text-xs font-semibold mt-2"></span>
                            </label>
                            <input type="file" id="wpa_transfer" name="transfer_proof" accept=".pdf,.jpg,.jpeg,.png" class="hidden" onchange="document.getElementById('wpa_transfer_name').textContent = this.files[0]?.name || ''">
                            <p class="text-xs text-gray-500 mt-2 text-center">Format: JPG, PNG, PDF (Max. 10MB)</p>
                        </div>
                        <label class="flex items-start gap-2 text-gray-400 text-sm">
                            <input type="checkbox" name="agreement" required class="mt-1">
                            <span>Saya setuju dengan <a href="<?= base_url('syarat-ketentuan') ?>" target="_blank" class="text-accent hover:underline">Syarat &amp; Ketentuan</a> program CWPA</span>
                        </label>
                    </div>

                    <!-- WPA Nav Buttons -->
                    <div class="flex gap-3 pt-6 mt-8 border-t border-white/10">
                        <button type="button" id="wpaPrevBtn" onclick="wpaStepNav(-1)" class="hidden flex-1 py-3 bg-white/10 text-white font-semibold rounded-xl hover:bg-white/20 transition text-sm">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </button>
                        <button type="button" id="wpaNextBtn" onclick="wpaStepNav(1)" class="flex-1 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-sm shadow-[0_0_16px_rgba(51,232,24,0.25)]">
                            Selanjutnya <i class="fas fa-arrow-right ml-1"></i>
                        </button>
                        <button type="submit" id="wpaSubmitBtn" class="hidden flex-1 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-sm shadow-[0_0_16px_rgba(51,232,24,0.25)]">
                            <i class="fas fa-check-circle mr-1"></i> Kirim Pendaftaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
        </div> <!-- End of registerSection -->

        <!-- OTP Modal -->
        <div id="otpModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm">
            <div class="bg-[#111] border border-white/10 rounded-2xl p-8 max-w-sm mx-4 w-full">
                <div class="text-center mb-6">
                    <div id="otpIcon" class="w-16 h-16 bg-accent/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-mobile-alt text-3xl text-accent"></i>
                    </div>
                    <h3 id="otpTitle" class="text-xl font-bold">Verifikasi Kode OTP</h3>
                    <p id="otpDesc" class="text-gray-400 text-sm mt-2">Kode OTP telah dikirim</p>
                    <p id="otpPhone" class="text-accent font-medium mt-1"></p>
                </div>

                <div id="otpInputContainer" class="flex gap-2 justify-center mb-4">
                    <input type="text" maxlength="1" class="otp-input w-12 h-14 bg-black border border-white/20 rounded-xl text-center text-2xl font-bold focus:border-accent focus:outline-none" data-index="0">
                    <input type="text" maxlength="1" class="otp-input w-12 h-14 bg-black border border-white/20 rounded-xl text-center text-2xl font-bold focus:border-accent focus:outline-none" data-index="1">
                    <input type="text" maxlength="1" class="otp-input w-12 h-14 bg-black border border-white/20 rounded-xl text-center text-2xl font-bold focus:border-accent focus:outline-none" data-index="2">
                    <input type="text" maxlength="1" class="otp-input w-12 h-14 bg-black border border-white/20 rounded-xl text-center text-2xl font-bold focus:border-accent focus:outline-none" data-index="3">
                    <input type="text" maxlength="1" class="otp-input w-12 h-14 bg-black border border-white/20 rounded-xl text-center text-2xl font-bold focus:border-accent focus:outline-none" data-index="4">
                    <input type="text" maxlength="1" class="otp-input w-12 h-14 bg-black border border-white/20 rounded-xl text-center text-2xl font-bold focus:border-accent focus:outline-none" data-index="5">
                </div>

                <div id="waActionContainer" class="hidden mb-6 mt-4">
                    <a id="waVerifyBtn" href="#" target="_blank" class="w-full flex items-center justify-center gap-2 py-4 bg-green-500 text-white font-bold rounded-xl hover:bg-green-600 transition shadow-[0_0_20px_rgba(34,197,94,0.3)]">
                        <i class="fab fa-whatsapp text-xl"></i> Kirim via WhatsApp
                    </a>
                    <p class="text-xs text-gray-400 text-center mt-3">Klik tombol di atas untuk mengirim kode OTP ke WhatsApp Admin kami. Status verifikasi Anda akan diperbarui otomatis.</p>
                </div>

                <p id="otpError" class="text-red-400 text-sm text-center mb-4 hidden"></p>

                <button id="verifyOtpBtn" onclick="verifyEmailOtp()" class="w-full py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition mb-4">
                    Verifikasi
                </button>

                <button onclick="closeOtpModal()" class="w-full py-3 border border-white/20 rounded-xl text-gray-400 hover:text-white hover:border-white/40 transition">
                    Batal
                </button>
            </div>
        </div>

        <!-- Disqualification Modal -->
        <div id="disqualificationModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm">
            <div class="bg-[#111] border border-red-500/30 rounded-2xl p-8 max-w-sm mx-4 w-full transform scale-95 opacity-0 transition-all duration-300 relative">
                <div class="text-center">
                    <div class="w-16 h-16 rounded-full bg-red-500/20 flex items-center justify-center mx-auto mb-4 border border-red-500/50">
                        <i class="fas fa-times text-2xl text-red-500"></i>
                    </div>
                    <h3 class="text-xl font-bold text-red-400 mb-3">Mohon Maaf</h3>
                    <p class="text-gray-400 mb-6 text-sm leading-relaxed">
                        Berdasarkan persyaratan yang berlaku, Anda saat ini <strong>belum memenuhi kualifikasi</strong> untuk menjadi Calon Wakil Penasihat Berjangka (CWPA).
                    </p>
                    <button type="button" onclick="closeDisqualificationModal()" class="w-full py-3 bg-white/5 border border-white/10 rounded-xl text-white hover:bg-white/10 transition font-medium">
                        Kembali & Perbaiki Data
                    </button>
                </div>
            </div>
        </div>

        <!-- Alert Modal -->
        <div id="alertModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm">
            <div class="bg-[#111] border border-white/10 rounded-2xl p-8 max-w-sm mx-4 w-full transform scale-95 opacity-0 transition-all duration-300" id="alertContent">
                <div class="text-center">
                    <div id="alertIcon" class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4"></div>
                    <h3 id="alertTitle" class="text-xl font-bold mb-2"></h3>
                    <p id="alertMessage" class="text-gray-400 mb-6"></p>
                    <button onclick="closeAlertModal()" class="px-8 py-3 bg-accent text-black font-bold rounded-full hover:bg-white transition">
                        OK
                    </button>
                </div>
            </div>
        </div>

        <!-- Company Info -->
        <div class="text-center mt-8 text-sm text-gray-400">
            <p class="font-bold text-white">PT. Alma Indonesia Raya</p>
            <p>Diawasi Oleh Otoritas Jasa Keuangan(OJK)</p>
        </div>

        <!-- Legal Logos -->
        <div class="flex flex-wrap items-center justify-center gap-3 mt-4 grayscale opacity-70">
            <img src="<?= base_url('images/legal/ojk.png') ?>" alt="OJK" class="h-5 object-contain">
            <img src="<?= base_url('images/legal/bappebti.svg') ?>" alt="Bappebti" class="h-5 object-contain invert">
            <img src="<?= base_url('images/legal/jfx.png') ?>" alt="JFX" class="h-5 object-contain">
            <img src="<?= base_url('images/legal/icdx.png') ?>" alt="ICDX" class="h-5 object-contain">
            <img src="<?= base_url('images/legal/komdigi.png') ?>" alt="Komdigi" class="h-5 object-contain">
            <img src="<?= base_url('images/legal/aspebtindo.avif') ?>" alt="Aspebtindo" class="h-5 object-contain">
            <img src="<?= base_url('images/legal/lpk.png') ?>" alt="LPK" class="h-5 object-contain">
            <img src="<?= base_url('images/legal/bank-indonesia.png') ?>" alt="Bank Indonesia" class="h-5 object-contain">
            <img src="<?= base_url('images/legal/cfx.webp') ?>" alt="CFX" class="h-5 object-contain">
        </div>
    </div>

    <!-- Modal Perjanjian WPA -->
    <div id="wpaPerjanjianModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
        <div class="bg-[#111] border border-white/10 rounded-2xl w-full max-w-3xl flex flex-col max-h-[90vh] shadow-2xl transform scale-95 opacity-0 transition-all duration-300" id="wpaPerjanjianModalContent">
            <div class="p-5 border-b border-white/10 flex justify-between items-center bg-[#0a0a0a] rounded-t-2xl">
                <h3 class="text-xl font-bold text-white">Perjanjian Pendaftaran CWPA</h3>
                <button type="button" onclick="closeWpaPerjanjianModal()" class="text-gray-400 hover:text-white transition w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white/10">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto custom-scrollbar flex-1 text-gray-300 space-y-4 text-sm leading-relaxed" id="wpaPerjanjianModalBody">
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-3xl text-accent mb-3"></i>
                    <p class="text-gray-400">Memuat dokumen perjanjian...</p>
                </div>
            </div>
            <div class="p-5 border-t border-white/10 bg-[#0a0a0a] rounded-b-2xl flex justify-end gap-3">
                <button type="button" onclick="closeWpaPerjanjianModal()" class="px-6 py-3 border border-white/20 text-gray-400 font-bold rounded-xl hover:text-white hover:border-white/40 transition">
                    Tutup
                </button>
                <button type="button" onclick="agreeWpaPerjanjian()" class="px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition shadow-[0_0_15px_rgba(51,232,24,0.3)]">
                    Saya Setuju
                </button>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('i');
            const showPassword = input.type === 'password';

            input.type = showPassword ? 'text' : 'password';
            icon.classList.toggle('fa-eye', !showPassword);
            icon.classList.toggle('fa-eye-slash', showPassword);
        }

        // CSRF Token Helper
        function getCsrfToken() {
            const metaToken = document.querySelector('meta[name="csrf-token"]');
            if (metaToken) {
                return metaToken.getAttribute('content');
            }

            const name = 'almai_csrf=';
            const decodedCookie = decodeURIComponent(document.cookie);
            const ca = decodedCookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return '';
        }

        // Update CSRF token from response header
        function updateCsrfToken(response) {
            const newToken = response.headers.get('X-CSRF-TOKEN');
            if (newToken) {
                const metaToken = document.querySelector('meta[name="csrf-token"]');
                if (metaToken) {
                    metaToken.setAttribute('content', newToken);
                }
            }
        }

        // Alert Modal Functions
        function showAlert(type, title, message) {
            const modal = document.getElementById('alertModal');
            const content = document.getElementById('alertContent');
            const icon = document.getElementById('alertIcon');
            const titleEl = document.getElementById('alertTitle');
            const messageEl = document.getElementById('alertMessage');

            titleEl.textContent = title;
            messageEl.innerHTML = message;

            if (type === 'error') {
                icon.className = 'w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 bg-red-500/20';
                icon.innerHTML = '<i class="fas fa-times text-3xl text-red-500"></i>';
            } else if (type === 'warning') {
                icon.className = 'w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 bg-accent/20';
                icon.innerHTML = '<i class="fas fa-exclamation-triangle text-3xl text-accent"></i>';
            } else if (type === 'success') {
                icon.className = 'w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 bg-accent/20';
                icon.innerHTML = '<i class="fas fa-check text-3xl text-accent"></i>';
            } else {
                icon.className = 'w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4 bg-blue-500/20';
                icon.innerHTML = '<i class="fas fa-info text-3xl text-blue-500"></i>';
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeAlertModal() {
            const modal = document.getElementById('alertModal');
            const content = document.getElementById('alertContent');

            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }

        // WPA Perjanjian Modal Functions
        function openWpaPerjanjianModal() {
            const modal = document.getElementById('wpaPerjanjianModal');
            const content = document.getElementById('wpaPerjanjianModalContent');
            const body = document.getElementById('wpaPerjanjianModalBody');
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            setTimeout(() => {
                const userName = document.getElementById('wpaName')?.value || '__________________';
                const userEmail = document.getElementById('wpaEmail')?.value || '__________________';
                const userPhone = document.getElementById('wpaPhone')?.value || '__________________';
                
                const today = new Date();
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                const formattedDate = today.toLocaleDateString('id-ID', options);
                
                const draftContract = `CWPA-DRAFT-${Date.now().toString().slice(-6)}`;

                <?php if (isset($legalWpa) && $legalWpa): ?>
                let dynamicContent = <?= json_encode($legalWpa['content']) ?>;
                dynamicContent = dynamicContent.replace(/{NAMA_USER}/g, userName);
                dynamicContent = dynamicContent.replace(/{EMAIL_USER}/g, userEmail);
                dynamicContent = dynamicContent.replace(/{NO_TELP}/g, userPhone);
                dynamicContent = dynamicContent.replace(/{NOMOR_KONTRAK}/g, draftContract);
                dynamicContent = dynamicContent.replace(/{HARI_TANGGAL}/g, formattedDate);

                body.innerHTML = `
                    <div class="space-y-4">
                        <h4 class="text-white font-bold text-lg text-center mb-6"><?= esc($legalWpa['title']) ?></h4>
                        
                        <div class="prose prose-invert max-w-none !text-white [&_*]:!text-white">
                            ${dynamicContent}
                        </div>

                        <p class="mt-6 p-4 bg-accent/10 border border-accent/20 rounded-xl text-white font-medium text-center">
                            Dengan mengklik tombol "Saya Setuju" di bawah, saya menyatakan bahwa saya telah membaca, memahami, dan setuju untuk terikat dengan seluruh persyaratan ini tanpa paksaan dari pihak mana pun.
                        </p>
                    </div>
                `;
                <?php else: ?>
                body.innerHTML = `
                    <div class="space-y-4">
                        <h4 class="text-white font-bold text-lg text-center mb-6">PERJANJIAN PENDAFTARAN CALON WAKIL PENASIHAT BERJANGKA (CWPA)</h4>
                        
                        <p class="text-white">Dengan mendaftar sebagai Calon Wakil Penasihat Berjangka (CWPA), Anda menyetujui syarat dan ketentuan berikut:</p>
                        
                        <ol class="list-decimal list-inside space-y-2 ml-2 text-white">
                            <li>Nama: <strong>${userName}</strong></li>
                            <li>Email: <strong>${userEmail}</strong></li>
                            <li>Telepon: <strong>${userPhone}</strong></li>
                            <li>Bahwa seluruh dokumen dan informasi yang saya sampaikan adalah <strong>benar, sah, dan terkini</strong>.</li>
                            <li>Saya bersedia mengikuti program pendampingan CWPA yang diselenggarakan oleh <strong>PT. Alma Indonesia Raya</strong>.</li>
                            <li>Biaya pendaftaran dan pendampingan yang telah dibayarkan <strong>tidak dapat ditarik atau dikembalikan (non-refundable)</strong> dengan alasan apa pun.</li>
                        </ol>

                        <p class="mt-6 p-4 bg-accent/10 border border-accent/20 rounded-xl text-white font-medium text-center">
                            Dengan mengklik tombol "Saya Setuju" di bawah, saya menyatakan bahwa saya telah membaca, memahami, dan setuju untuk terikat dengan seluruh persyaratan ini tanpa paksaan dari pihak mana pun.
                        </p>
                    </div>
                `;
                <?php endif; ?>
            }, 100);

            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeWpaPerjanjianModal() {
            const modal = document.getElementById('wpaPerjanjianModal');
            const content = document.getElementById('wpaPerjanjianModalContent');
            const body = document.getElementById('wpaPerjanjianModalBody');

            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                body.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-3xl text-accent mb-3"></i><p class="text-gray-400">Memuat dokumen perjanjian...</p></div>';
            }, 300);
        }

        function agreeWpaPerjanjian() {
            const checkbox = document.getElementById('agreement_wpa');
            if (checkbox) {
                checkbox.checked = true;
                checkbox.disabled = false;
                
                const container = checkbox.closest('.flex.items-start');
                if (container) {
                    container.classList.add('border-accent', 'bg-accent/10');
                    container.classList.remove('border-white/10', 'bg-black/50');
                }
            }
            closeWpaPerjanjianModal();
        }

        function showTab(tab) {
            const loginTab = document.getElementById('loginTab');
            const registerTab = document.getElementById('registerTab');
            const loginForm = document.getElementById('loginForm');
            const registerSection = document.getElementById('registerSection');
            const logoText = document.getElementById('logoText');
            const subLogoText = document.getElementById('subLogoText');

            if (tab === 'login') {
                if(logoText) logoText.textContent = 'LOGIN';
                if(subLogoText) subLogoText.textContent = 'Selamat Datang';
                loginTab.classList.add('bg-accent', 'text-black');
                loginTab.classList.remove('text-gray-400');
                registerTab.classList.remove('bg-accent', 'text-black');
                registerTab.classList.add('text-gray-400');
                loginForm.classList.remove('hidden');
                if(registerSection) registerSection.classList.add('hidden');
                window.history.pushState({}, '', '<?= base_url('login') ?>' + window.location.search);
            } else {
                if(logoText) logoText.textContent = 'DAFTAR';
                if(subLogoText) subLogoText.textContent = 'Pilih Jenis Pendaftaran';
                registerTab.classList.add('bg-accent', 'text-black');
                registerTab.classList.remove('text-gray-400');
                loginTab.classList.remove('bg-accent', 'text-black');
                loginTab.classList.add('text-gray-400');
                if(registerSection) registerSection.classList.remove('hidden');
                loginForm.classList.add('hidden');
                
                resetRegTypeButtons();
                selectRegType('user');
                
                window.history.pushState({}, '', '<?= base_url('daftar') ?>' + window.location.search);
            }
        }

        function resetRegTypeButtons() {
            const btnUser = document.getElementById('btnRegUser');
            const btnWpa = document.getElementById('btnRegWpa');
            if (btnUser) { btnUser.classList.remove('bg-accent', 'text-black'); btnUser.classList.add('text-gray-300'); }
            if (btnWpa)  { btnWpa.classList.remove('bg-accent', 'text-black'); btnWpa.classList.add('text-gray-300'); }
        }

        function selectRegType(type) {
            const registerForm = document.getElementById('registerForm');
            const registerWpaCard = document.getElementById('registerWpaCard');
            const subLogoText = document.getElementById('subLogoText');

            resetRegTypeButtons();

            if (type === 'user') {
                document.getElementById('btnRegUser').classList.remove('text-gray-300');
                document.getElementById('btnRegUser').classList.add('bg-accent', 'text-black');
                if(registerForm) registerForm.classList.remove('hidden');
                if(registerWpaCard) registerWpaCard.classList.add('hidden');
                if(subLogoText) subLogoText.textContent = 'Buat Akun User';
            } else {
                const btnWpa = document.getElementById('btnRegWpa');
                btnWpa.classList.remove('text-gray-300');
                btnWpa.classList.add('bg-accent', 'text-black');
                
                if(registerWpaCard) registerWpaCard.classList.remove('hidden');
                if(registerForm) registerForm.classList.add('hidden');
                wpaCurrentStep = 1;
                wpaRenderStep();
                if(subLogoText) subLogoText.textContent = 'Buat Akun WPA';
            }
        }

        const WPA_STEPS = ['Buat Akun', 'Data Diri', 'Media Sosial', 'Spesialisasi', 'Persyaratan', 'Dokumen', 'Perjanjian', 'Pembayaran'];
        let wpaCurrentStep = 1;
        let wpaOtpVerified = false;
        const WPA_TOTAL = 8;

        function wpaRenderStep() {
            document.querySelectorAll('.wpa-step').forEach(el => {
                el.classList.toggle('hidden', parseInt(el.dataset.wpaStep) !== wpaCurrentStep);
            });
            document.querySelectorAll('.wpa-step-dot').forEach(dot => {
                const s = parseInt(dot.dataset.wpaStep);
                dot.classList.toggle('bg-accent', s <= wpaCurrentStep);
                dot.classList.toggle('bg-white/10', s > wpaCurrentStep);
            });
            const prevBtn = document.getElementById('wpaPrevBtn');
            const nextBtn = document.getElementById('wpaNextBtn');
            const submitBtn = document.getElementById('wpaSubmitBtn');
            if (prevBtn) prevBtn.classList.toggle('hidden', wpaCurrentStep === 1);
            if (nextBtn) nextBtn.classList.toggle('hidden', wpaCurrentStep === WPA_TOTAL);
            if (submitBtn) submitBtn.classList.toggle('hidden', wpaCurrentStep !== WPA_TOTAL);
        }

        function wpaStepNav(dir) {
            const newStep = wpaCurrentStep + dir;
            if (newStep < 1 || newStep > WPA_TOTAL) return;
            if (dir > 0) {
                const currentStepEl = document.querySelector(`.wpa-step[data-wpa-step="${wpaCurrentStep}"]`);
                if (currentStepEl) {
                    const requiredInputs = currentStepEl.querySelectorAll('[required]');
                    let valid = true;
                    let errorMsg = 'Harap lengkapi semua field wajib.';
                    requiredInputs.forEach(input => {
                        if (input.type === 'radio') {
                            const name = input.name;
                            if (!currentStepEl.querySelector(`input[name="${name}"]:checked`)) valid = false;
                        } else if (input.type === 'checkbox') {
                            if (!input.checked) {
                                valid = false;
                                if (input.name === 'terms_wpa') {
                                    errorMsg = 'Anda harus menyetujui Syarat & Ketentuan serta Kebijakan Privasi.';
                                }
                            }
                        } else {
                            const val = input.value.trim();
                            if (!val) {
                                valid = false;
                                input.classList.add('border-red-500');
                                if (input.name === 'kode_affiliator') {
                                    errorMsg = 'KODE_REFERRAL_EMPTY';
                                } else if (input.name === 'name') {
                                    errorMsg = 'Nama Lengkap wajib diisi.';
                                } else if (input.name === 'email') {
                                    errorMsg = 'Email wajib diisi.';
                                } else if (input.name === 'whatsapp') {
                                    errorMsg = 'No. WhatsApp wajib diisi.';
                                } else {
                                    errorMsg = 'Harap lengkapi semua field wajib.';
                                }
                            } else {
                                if (input.name === 'whatsapp' && val.length < 10) {
                                    valid = false;
                                    input.classList.add('border-red-500');
                                    errorMsg = 'No. WhatsApp minimal 10 digit.';
                                } else if (input.name === 'ktp_number' && val.length !== 16) {
                                    valid = false;
                                    input.classList.add('border-red-500');
                                    errorMsg = 'Nomor KTP harus tepat 16 digit.';
                                } else if (input.name === 'npwp_number' && val.length < 15) {
                                    valid = false;
                                    input.classList.add('border-red-500');
                                    errorMsg = 'Nomor NPWP minimal 15 digit.';
                                } else if (input.name === 'address' && val.length < 10) {
                                    valid = false;
                                    input.classList.add('border-red-500');
                                    errorMsg = 'Alamat KTP minimal 10 karakter.';
                                } else {
                                    input.classList.remove('border-red-500');
                                }
                            }
                        }
                    });
                    if (!valid) { 
                        if (errorMsg === 'KODE_REFERRAL_EMPTY') {
                            showAlert('warning', 'Kode Referral Wajib', 'Jika belum punya, silakan pilih salah satu WPA dan daftar dengan cara klik tombol +ikuti.<br><br><a href="<?= base_url('wpa') ?>" class="inline-block px-4 py-2 bg-accent text-black rounded-lg font-bold" target="_blank">Lihat Daftar WPA</a>');
                        } else {
                            showAlert('warning', 'Validasi Gagal', errorMsg);
                        }
                        return; 
                    }
                    requiredInputs.forEach(i => i.classList && i.classList.remove('border-red-500'));

                    if (wpaCurrentStep === 1 && !wpaOtpVerified) {
                        sendWpaOtp();
                        return;
                    }

                    if (wpaCurrentStep === 3) {
                        const socialInputs = ['instagram', 'facebook', 'tiktok', 'linkedin', 'youtube'];
                        const hasAnySocial = socialInputs.some(name => {
                            const field = currentStepEl.querySelector(`input[name="${name}"]`);
                            return field && field.value.trim() !== '';
                        });
                        if (!hasAnySocial) {
                            showAlert('warning', 'Validasi Gagal', 'Isi minimal satu akun media sosial');
                            return;
                        }
                    }

                    if (wpaCurrentStep === 4) {
                        const specialties = currentStepEl.querySelectorAll('input[name="specialties[]"]:checked');
                        if (specialties.length === 0) {
                            showAlert('warning', 'Validasi Gagal', 'Pilih minimal satu spesialisasi');
                            return;
                        }
                    }

                    if (wpaCurrentStep === 7) {
                        const agreement = currentStepEl.querySelector('#agreement');
                        if (agreement && !agreement.checked) {
                            showAlert('warning', 'Validasi Gagal', 'Sertakan persetujuan Anda dengan mencentang kotak yang tersedia');
                            return;
                        }
                    }
                }
            }
            wpaCurrentStep = newStep;
            wpaRenderStep();
        }

        async function sendWpaOtp() {
            const wpaPhone = document.getElementById('wpaPhone').value.trim();
            const wpaEmail = document.getElementById('wpaEmail')?.value.trim() || '';
            const wpaName = document.getElementById('wpaName').value.trim();
            const wpaAffiliateCode = document.getElementById('wpaAffiliateCode')?.value.trim() || '';
            
            const btn = document.getElementById('wpaNextBtn');
            const originalHtml = btn ? btn.innerHTML : '';

            // If no WhatsApp provided (WA-first flow), open wa.me modal and start polling by email
            if (!wpaPhone) {
                registrationChannel = 'wpa_whatsapp';
                registrationData = { phone: wpaPhone, email: wpaEmail };
                showOtpModal('whatsapp', wpaEmail, '', '<?= env('NO_OTP') ?>', { name: wpaName, email: wpaEmail, affiliate_code: wpaAffiliateCode });
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                }
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            try {
                const csrfToken = getCsrfToken();
                const response = await fetch('<?= base_url('daftar-cwpa/send-otp') ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ 
                        email: wpaEmail, 
                        phone: wpaPhone, 
                        name: wpaName, 
                        kode_affiliator: wpaAffiliateCode,
                        channel: 'whatsapp', 
                        almai_sec_token: csrfToken 
                    })
                });
                
                updateCsrfToken(response);
                const result = await response.json();
                
                if (result.success) {
                    registrationChannel = 'wpa_whatsapp';
                    registrationData = { phone: wpaPhone, email: wpaEmail };
                    // Pass otherData object so modal builds prefilled message and polling can use email
                    showOtpModal('whatsapp', wpaEmail, result.otp_code, result.admin_wa, { name: wpaName, email: wpaEmail, affiliate_code: wpaAffiliateCode });
                } else {
                    showAlert('error', 'Gagal', result.message || 'Gagal mengirim OTP.');
                }
            } catch (err) {
                showAlert('error', 'Error', 'Terjadi kesalahan sistem.');
            }
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }

        const wpaFormEl = document.getElementById('wpaFormEl');
        if (wpaFormEl) {
            wpaFormEl.addEventListener('submit', async function(e) {
                e.preventDefault();
                const submitBtn = document.getElementById('wpaSubmitBtn');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Mengirim...';
                try {
                    const formData = new FormData(this);
                    const resp = await fetch('<?= base_url('daftar-cwpa/submit-multi-step') ?>', {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': getCsrfToken() },
                        body: formData
                    });
                    const result = await resp.json();
                    if (result.success) {
                        showAlert('success', 'Pendaftaran WPA Berhasil!', 'Tim kami akan menghubungi Anda setelah verifikasi berkas selesai.');
                        const okBtn = document.querySelector('#alertModal button');
                        if (okBtn) okBtn.onclick = () => { window.location.href = result.redirect_url || '<?= base_url('user/dashboard') ?>'; };
                    } else {
                        showAlert('error', 'Gagal', result.message || 'Terjadi kesalahan.');
                        const okBtn = document.querySelector('#alertModal button');
                        if (okBtn) okBtn.onclick = closeAlertModal;
                    }
                } catch(err) {
                    showAlert('error', 'Error', 'Tidak dapat terhubung ke server.');
                }
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Kirim Pendaftaran';
            });
        }

        const wpaSelectAll = document.getElementById('wpaSelectAll');
        if (wpaSelectAll) {
            wpaSelectAll.addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('input[name="specialties[]"]');
                checkboxes.forEach(cb => cb.checked = this.checked);
            });
        }

        function toggleSkckWillingness() {
            const hasSkck = document.querySelector('input[name="has_skck_card"]:checked')?.value;
            const willingnessSection = document.getElementById('skckWillingnessSection');
            if(!willingnessSection) return;
            const willingnessInputs = willingnessSection.querySelectorAll('input[name="willing_to_make_skck"]');

            if (hasSkck === 'no') {
                willingnessSection.classList.remove('hidden');
                willingnessInputs.forEach(input => input.required = true);
            } else {
                willingnessSection.classList.add('hidden');
                willingnessInputs.forEach(input => {
                    input.required = false;
                    input.checked = false;
                });
            }
        }

        function toggleFelonyWillingness() {
            const hasRecord = document.querySelector('input[name="has_felony_record"]:checked')?.value;
            const willingnessSection = document.getElementById('felonyWillingnessSection');
            if(!willingnessSection) return;
            const willingnessInputs = willingnessSection.querySelectorAll('input[name="willing_to_make_felony_statement"]');

            if (hasRecord === 'no') {
                willingnessSection.classList.remove('hidden');
                willingnessInputs.forEach(input => input.required = true);
            } else {
                willingnessSection.classList.add('hidden');
                willingnessInputs.forEach(input => {
                    input.required = false;
                    input.checked = false;
                });
            }
        }

        function checkDisqualification() {
            const hasS1 = document.querySelector('input[name="has_ijazah_s1"]:checked')?.value;
            const hasFelony = document.querySelector('input[name="has_felony_record"]:checked')?.value;
            const hasBankruptcy = document.querySelector('input[name="has_bankruptcy_record"]:checked')?.value;

            const willingSkck = document.querySelector('input[name="willing_to_make_skck"]:checked')?.value;
            const willingFelony = document.querySelector('input[name="willing_to_make_felony_statement"]:checked')?.value;

            if (hasS1 === 'no' || hasFelony === 'yes' || hasBankruptcy === 'yes' || willingSkck === 'no' || willingFelony === 'no') {
                showDisqualificationModal();
            }
        }

        function showDisqualificationModal() {
            const modal = document.getElementById('disqualificationModal');
            if(!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modal.children[0].classList.remove('scale-95', 'opacity-0');
                modal.children[0].classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeDisqualificationModal() {
            const modal = document.getElementById('disqualificationModal');
            if(!modal) return;
            modal.children[0].classList.add('scale-95', 'opacity-0');
            modal.children[0].classList.remove('scale-100', 'opacity-100');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);

            document.querySelectorAll('input[name="has_ijazah_s1"]').forEach(input => input.checked = false);
            document.querySelectorAll('input[name="has_felony_record"]').forEach(input => {
                input.checked = false;
                toggleFelonyWillingness();
            });
            document.querySelectorAll('input[name="has_bankruptcy_record"]').forEach(input => input.checked = false);

            document.querySelectorAll('input[name="willing_to_make_skck"]').forEach(input => input.checked = false);
            document.querySelectorAll('input[name="willing_to_make_felony_statement"]').forEach(input => input.checked = false);
        }

        document.querySelectorAll('.require-check-disqualification').forEach(input => {
            input.addEventListener('change', checkDisqualification);
        });

        document.querySelectorAll('.require-toggle-skck-willingness').forEach(input => {
            input.addEventListener('change', toggleSkckWillingness);
        });

        document.querySelectorAll('.require-toggle-felony-willingness').forEach(input => {
            input.addEventListener('change', toggleFelonyWillingness);
        });

        let registrationChannel = 'email';

        document.getElementById('registerFormEl').addEventListener('submit', async function(e) {
            e.preventDefault();

            const name = document.getElementById('regName').value.trim();
            const email = document.getElementById('regEmail').value.trim();
            const phone = document.getElementById('regPhone')?.value?.trim();
            const affiliate_code = document.getElementById('regAffiliateCode').value.trim();
            const terms = document.getElementById('regTerms').checked;

            if (!affiliate_code) {
                showAlert('warning', 'Kode Referral Wajib', 'Jika belum punya, silakan pilih salah satu WPA dan daftar dengan cara klik tombol +ikuti.<br><br><a href="<?= base_url('wpa') ?>" class="inline-block px-4 py-2 bg-accent text-black rounded-lg font-bold" target="_blank">Lihat Daftar WPA</a>'); 
                return; 
            }

            if (!name || name.length < 3) { showAlert('warning', 'Nama Tidak Valid', 'Nama lengkap minimal 3 karakter.'); return; }
            if (!email) { showAlert('warning', 'Email Tidak Valid', 'Email wajib diisi.'); return; }
            // if (!phone || phone.length < 10) { showAlert('warning', 'No. WhatsApp Tidak Valid', 'Masukkan nomor WhatsApp yang valid (min 10 digit).'); return; }
            if (!terms) { showAlert('warning', 'Syarat & Ketentuan', 'Anda harus menyetujui Syarat & Ketentuan.'); return; }

            const turnstileResponse = this.querySelector('input[name="cf-turnstile-response"]')?.value;
            if (!turnstileResponse) { showAlert('warning', 'Verifikasi Keamanan', 'Harap selesaikan Captcha.'); return; }

            registrationData = { name, email, phone, password: '', affiliate_code, turnstile_token: turnstileResponse };

            const btn = document.getElementById('registerBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';

            try {
                const response = await fetch('<?= base_url('register/check-email') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    body: JSON.stringify({ email })
                });

                const emailResult = await response.json();
                if (!response.ok || !emailResult.success) {
                    btn.disabled = false;
                    btn.innerHTML = 'Daftar Sekarang';
                    showAlert('warning', 'Email Tidak Valid', emailResult.message || 'Email sudah terdaftar');
                    return;
                }
            } catch (error) {
                btn.disabled = false;
                btn.innerHTML = 'Daftar Sekarang';
                showAlert('error', 'Terjadi Kesalahan', 'Gagal memeriksa email. Silakan coba lagi.');
                return;
            }

            showOtpModal('whatsapp', '', '', '<?= env('NO_OTP') ?>', {
                "name": name,
                "email": email,
                "affiliate_code": affiliate_code
            });
            
            // try {
            //     const csrfToken = getCsrfToken();
            //     const response = await fetch('/register/send-otp', {
            //         method: 'POST',
            //         headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken },
            //         body: JSON.stringify({ 
            //             email: email, 
            //             phone, 
            //             name, 
            //             password: '', 
            //             affiliate_code, 
            //             channel: 'whatsapp',
            //             turnstile_token: turnstileResponse,
            //             almai_sec_token: csrfToken
            //         })
            //     });
            //     const result = await response.json();

            //     if (result.success && result.channel === 'whatsapp') {
            //         registrationChannel = 'whatsapp';
            //         showOtpModal('whatsapp', registrationData.phone, result.otp_code, result.admin_wa);
            //         if (result.otp_dev) console.log('OTP (dev):', result.otp_dev);
            //     } else if (result.success && result.channel === 'email') {
            //         registrationChannel = 'email';
            //         showOtpModal('email', registrationData.email);
            //         if (result.otp_dev) console.log('OTP (dev):', result.otp_dev);
            //     } else {
            //         if (result.is_registered) {
            //             showAlert('error', 'Gagal', 'Nomor/Email sudah terdaftar. Silakan <a href="<?= base_url('login') ?>" class="text-accent hover:underline">login</a> atau <a href="<?= base_url('forgot-password') ?>" class="text-accent hover:underline">reset password</a>.');
            //             const okBtn = document.querySelector('#alertModal button');
            //             if (okBtn) {
            //                 okBtn.onclick = function() {
            //                     window.location.href = '<?= base_url('login') ?>';
            //                 };
            //             }
            //         } else {
            //             showAlert('error', 'Gagal', result.message || 'Terjadi kesalahan.');
            //             const okBtn = document.querySelector('#alertModal button');
            //             if (okBtn) {
            //                 okBtn.onclick = closeAlertModal;
            //             }
            //         }
            //         if (typeof turnstile !== 'undefined') turnstile.reset();
            //     }
            // } catch (error) {
            //     showAlert('error', 'Terjadi Kesalahan', 'Tidak dapat terhubung ke server.');
            //     const okBtn = document.querySelector('#alertModal button');
            //     if (okBtn) okBtn.onclick = closeAlertModal;
            // }

            // btn.disabled = false;
            // btn.innerHTML = 'Daftar Sekarang';
        });

        let waPollingInterval;

        function showOtpModal(channel, targetDisplay, otpCode = '', adminWa = '', otherData = null) {
            document.getElementById('otpPhone').textContent = targetDisplay;
            const otherDataIsObject = typeof otherData === 'object' && otherData !== null;
            
            const title = document.getElementById('otpTitle');
            const desc = document.getElementById('otpDesc');
            const icon = document.getElementById('otpIcon');
            const otpInputContainer = document.getElementById('otpInputContainer');
            const waActionContainer = document.getElementById('waActionContainer');
            const verifyBtn = document.getElementById('verifyOtpBtn');
            
            if (channel === 'whatsapp') {
                if (title) title.textContent = otherDataIsObject ? 'PENDAFTARAN' : 'Verifikasi Kode OTP';
                desc.textContent = otherDataIsObject
                    ? 'Kirim pesan ke WhatsApp Admin untuk melanjutkan pendaftaran dan verifikasi.'
                    : `Silakan kirim kode berikut ke WhatsApp Admin:\n\n`;
                
                icon.innerHTML = '<i class="fab fa-whatsapp text-2xl text-green-500"></i>';
                icon.className = 'w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-4';
                
                if(otpInputContainer) otpInputContainer.classList.add('hidden');
                if(verifyBtn) verifyBtn.classList.add('hidden');
                if(waActionContainer) {
                    waActionContainer.classList.remove('hidden');
                    
                    const cleanAdminWa = adminWa.replace(/\D/g, '');
                    const waText = otherDataIsObject ? `Halo Tim ALMAI,\n\nBerikut data saya:\n\nNama Lengkap:\n${otherData['name']}\n\nEmail:\n${otherData['email']}\n\nKode Referral:\n${otherData['affiliate_code']}\n\nMohon diproses.\n\nTerima kasih` : `Halo Tim ALMAI,\n\nBerikut kode otp saya untuk verifikasi :\n\nOTP : ${otpCode}\n\nMohon diproses , Terima kasih`;
                    const waLink = `https://wa.me/${cleanAdminWa}?text=${encodeURIComponent(waText)}`;
                    
                    waActionContainer.innerHTML = `
                        <a href="${waLink}" target="_blank" class="w-full flex items-center justify-center gap-2 py-4 bg-green-500 hover:bg-green-600 text-white font-bold rounded-xl transition shadow-[0_0_20px_rgba(34,197,94,0.3)] hover:scale-[1.02]">
                            <i class="fab fa-whatsapp text-xl"></i> Kirim via WhatsApp
                        </a>
                        <p class="text-xs text-gray-400 text-center mt-3">Verifikasi dilakukan otomatis setelah pesan terkirim.</p>
                    `;

                    // If otherData is an object (we sent name/email/affiliate), start polling by email
                    if (otherDataIsObject && otherData.email) {
                        if (waPollingInterval) clearInterval(waPollingInterval);
                        waPollingInterval = setInterval(() => checkWaStatus(otherData.email), 3000);
                    }
                }
            } else {
                if (title) title.textContent = 'Verifikasi Kode OTP';
                desc.textContent = 'Kode OTP 6-digit telah dikirim ke Email';
                icon.innerHTML = '<i class="fas fa-envelope text-2xl text-accent"></i>';
                icon.className = 'w-16 h-16 bg-accent/20 rounded-full flex items-center justify-center mx-auto mb-4';
                
                if(otpInputContainer) otpInputContainer.classList.remove('hidden');
                if(verifyBtn) verifyBtn.classList.remove('hidden');
                if(waActionContainer) waActionContainer.classList.add('hidden');
                
                document.querySelectorAll('.otp-input').forEach(input => input.value = '');
                setTimeout(() => document.querySelector('.otp-input').focus(), 100);
            }
            
            document.getElementById('otpModal').classList.remove('hidden');
            document.getElementById('otpModal').classList.add('flex');
            document.getElementById('otpError').classList.add('hidden');
        }

        async function checkWaStatus(phone) {
                try {
                const response = await fetch('<?= base_url('daftar-cwpa/check-wa-registration') ?>?identifier=' + encodeURIComponent(phone), {headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest'}});
                const result = await response.json();
                
                if (result.verified) {
                    if (waPollingInterval) clearInterval(waPollingInterval);
                    
                    const waContainer = document.getElementById('waActionContainer');
                    if (waContainer) {
                        waContainer.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin text-3xl text-accent mb-3"></i><p class="text-white font-medium">Memproses pendaftaran...</p></div>';
                    }
                    
                    try {
                        const csrfToken = getCsrfToken();
                        let endpoint = '/register/verify-otp';
                        let bodyData = { ...registrationData, otp: '', channel: 'whatsapp', almai_sec_token: csrfToken };
                        
                        if (registrationChannel === 'wpa_whatsapp') {
                            endpoint = '<?= base_url('daftar-cwpa/verify-otp') ?>';
                            const identifier = registrationData.email || registrationData.phone || phone;
                            bodyData = { identifier: identifier, otp: '', almai_sec_token: csrfToken };
                        }

                        const regResponse = await fetch(endpoint, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken },
                            body: JSON.stringify(bodyData)
                        });

                        updateCsrfToken(regResponse);
                        const regResult = await regResponse.json();

                        if (regResult.success) {
                            closeOtpModal();
                            
                            if (registrationChannel === 'wpa_whatsapp') {
                                wpaOtpVerified = true;
                                showAlert('success', 'Registrasi Berhasil!', 'Nomor WhatsApp berhasil diverifikasi. Lanjutkan ke tahap berikutnya.');
                                wpaStepNav(1);
                            } else {
                                showAlert('success', 'Registrasi Berhasil!', 'Selamat datang di ALMAI! Mengalihkan ke dashboard...');
                                setTimeout(() => {
                                    window.location.href = regResult.redirect || '<?= base_url('user/dashboard') ?>';
                                }, 1500);
                            }
                        } else {
                            closeOtpModal();
                            showAlert('error', 'Gagal', regResult.message || 'Terjadi kesalahan.');
                        }
                    } catch (error) {
                        closeOtpModal();
                        showAlert('error', 'Terjadi Kesalahan', 'Tidak dapat terhubung ke server.');
                    }
                }
            } catch (e) {
                console.error('Polling error', e);
            }
        }

        function closeOtpModal() {
            if (waPollingInterval) clearInterval(waPollingInterval);
            document.getElementById('otpModal').classList.add('hidden');
            document.getElementById('otpModal').classList.remove('flex');
        }

        document.querySelectorAll('.otp-input').forEach((input, index, inputs) => {
            input.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '');
                if (this.value.length === 1 && index < inputs.length - 1) inputs[index + 1].focus();
            });
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value === '' && index > 0) inputs[index - 1].focus();
            });
            input.addEventListener('paste', function(e) {
                e.preventDefault();
                const paste = (e.clipboardData || window.clipboardData).getData('text');
                const digits = paste.replace(/\D/g, '').slice(0, 6);
                digits.split('').forEach((digit, i) => { if (inputs[i]) inputs[i].value = digit; });
                if (digits.length > 0) inputs[Math.min(digits.length - 1, 5)].focus();
            });
        });

        async function verifyEmailOtp() {
            const otp = Array.from(document.querySelectorAll('.otp-input')).map(i => i.value).join('');
            if (otp.length !== 6) {
                document.getElementById('otpError').textContent = 'Masukkan 6 digit kode OTP';
                document.getElementById('otpError').className = 'text-red-400 text-sm text-center mb-4';
                return;
            }

            const btn = document.getElementById('verifyOtpBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memverifikasi...';

            try {
                const csrfToken = getCsrfToken();
                
                let endpoint = '/register/verify-otp';
                let bodyData = { ...registrationData, otp, channel: registrationChannel, almai_sec_token: csrfToken };
                
                if (registrationChannel === 'wpa_whatsapp') {
                    endpoint = '<?= base_url('daftar-cwpa/verify-otp') ?>';
                    bodyData = { identifier: registrationData.phone, otp, almai_sec_token: csrfToken };
                }

                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify(bodyData)
                });

                updateCsrfToken(response);
                const result = await response.json();

                if (result.success) {
                    closeOtpModal();
                    
                    if (registrationChannel === 'wpa_whatsapp') {
                        wpaOtpVerified = true;
                        showAlert('success', 'Registrasi Berhasil!', 'Nomor WhatsApp berhasil diverifikasi. Lanjutkan ke tahap berikutnya.');
                        wpaStepNav(1);
                    } else {
                        showAlert('success', 'Registrasi Berhasil!', 'Selamat datang di ALMAI! Mengalihkan ke dashboard...');
                        
                        setTimeout(() => {
                            window.location.href = result.redirect || '<?= base_url('user/dashboard') ?>';
                        }, 1500);

                        const okBtn = document.querySelector('#alertModal button');
                        if (okBtn) {
                            okBtn.onclick = function() {
                                const modal = document.getElementById('alertModal');
                                const content = document.getElementById('alertContent');
                                content.classList.remove('scale-100', 'opacity-100');
                                content.classList.add('scale-95', 'opacity-0');
                                setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); okBtn.onclick = closeAlertModal; window.location.href = result.redirect || '<?= base_url('user/dashboard') ?>'; }, 300);
                            };
                        }
                    }
                } else {
                    document.getElementById('otpError').textContent = result.message || 'Kode OTP tidak valid';
                    document.getElementById('otpError').className = 'text-red-400 text-sm text-center mb-4';
                }
            } catch (error) {
                document.getElementById('otpError').textContent = 'Terjadi kesalahan.';
                document.getElementById('otpError').className = 'text-red-400 text-sm text-center mb-4';
            }
            btn.disabled = false;
            btn.innerHTML = 'Verifikasi';
        }

        document.addEventListener('DOMContentLoaded', () => {
            const isRegisterDefault = <?= !empty($showRegister) ? 'true' : 'false' ?>;
            const activeTab = '<?= !empty($activeTab) ? $activeTab : '' ?>';
            const isLoggedIn = <?= !empty($currentUser) ? 'true' : 'false' ?>;
            
            if (activeTab === 'wpa') {
                selectRegType('wpa');
                if (isLoggedIn) {
                    wpaOtpVerified = true;
                    const wpaName = document.getElementById('wpaName');
                    const wpaEmail = document.getElementById('wpaEmail');
                    const wpaPhone = document.getElementById('wpaPhone');
                    if (wpaName) {
                        wpaName.value = "<?= !empty($currentUser) ? esc($currentUser['name']) : '' ?>";
                        wpaName.readOnly = true;
                    }
                    if (wpaEmail) {
                        wpaEmail.value = "<?= !empty($currentUser) ? esc($currentUser['email']) : '' ?>";
                        wpaEmail.readOnly = true;
                    }
                    if (wpaPhone) {
                        wpaPhone.value = "<?= !empty($currentUser) ? esc($currentUser['phone']) : '' ?>";
                        wpaPhone.readOnly = true;
                    }
                    setTimeout(() => wpaStepNav(2), 100);
                }
            } else if (isRegisterDefault) {
                selectRegType('user');
            }
        });
    </script>
</body>

</html>
