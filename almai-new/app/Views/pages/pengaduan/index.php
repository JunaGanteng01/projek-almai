<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Daftar Advokasi - Almai Platform') ?></title>
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <link rel="stylesheet" href="<?= base_url('css/tailwind.min.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Cloudflare Turnstile -->
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .swal2-popup {
            background: #111 !important;
            border: 1px solid rgba(51, 232, 24, 0.2) !important;
            border-radius: 30px !important;
            color: #fff !important;
        }
        .swal2-title { color: #fff !important; font-family: 'Public Sans', sans-serif !important; font-weight: 800 !important; }
        .swal2-html-container { color: #aaa !important; font-size: 14px !important; }
        .swal2-confirm { background-color: #33e818 !important; color: #000 !important; font-weight: 900 !important; border-radius: 15px !important; padding: 12px 30px !important; text-transform: uppercase !important; letter-spacing: 1px !important; }
        .swal2-cancel { background-color: rgba(255,255,255,0.05) !important; color: #fff !important; border-radius: 15px !important; }
    </style>

    <style>
        :root {
            --accent: #33E818;
            --accent-dark: #28b813;
        }

        html,
        body {
            background-color: #050505;
            color: #ffffff;
            font-family: 'Montserrat', sans-serif;
            overflow-x: hidden;
        }

        .bg-grid {
            background-size: 40px 40px;
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px), linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
        }

        .progress-line {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 2px;
            background: rgba(255, 255, 255, 0.1);
            z-index: 0;
        }

        .progress-line-fill {
            height: 100%;
            background: var(--accent);
            transition: width 0.4s ease;
        }

        .file-upload-label {
            cursor: pointer;
            transition: all 0.3s;
        }

        .file-upload-label:hover {
            border-color: var(--accent);
            background: rgba(51, 232, 24, 0.05);
        }

        input[type="file"] {
            display: none;
        }

        .step-container {
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        select option {
            background-color: #111;
            color: white;
        }

        /* Scrollbar custom for legal doc */
        #legalDocumentContent::-webkit-scrollbar {
            width: 6px;
        }
        #legalDocumentContent::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
        }
        #legalDocumentContent::-webkit-scrollbar-thumb {
            background: var(--accent);
            border-radius: 10px;
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
            border-radius: 1.5rem !important;
        }
        /* Checkbox checkmark fix */
        #agreed:checked + div svg {
            opacity: 1 !important;
            transform: scale(1.2) !important;
        }

        /* Force light color in legal content to prevent black text */
        .legal-content-body, .legal-content-body * {
            color: #e5e7eb !important; /* gray-200 */
        }
    </style>
</head>

<body class="antialiased min-h-screen relative overflow-x-hidden">
    <!-- Navbar -->
    <?= $this->include('partials/navbar') ?>

    <div class="absolute inset-0 bg-grid z-0 pointer-events-none"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[300px] md:w-[800px] h-[300px] md:h-[500px] bg-accent/20 blur-[120px] rounded-full pointer-events-none"></div>

    <div class="relative z-10 w-full max-w-2xl px-6 mx-auto pt-28 pb-12 min-h-screen flex flex-col justify-center">
        <div class="text-center mb-10">
            <a href="<?= base_url('/') ?>" class="inline-flex items-center gap-2 text-3xl font-bold group">
                <img src="<?= base_url('images/alma.gif') ?>" alt="ALMAI" class="h-12 group-hover:scale-110 transition-transform">
                <span class="tracking-tighter">ALMAI</span>
            </a>
            <h1 class="text-xl font-bold text-gray-400 mt-6 uppercase tracking-[0.3em]">Daftar Advokasi</h1>
        </div>

        <!-- Form Content -->

        <!-- Progress Indicators (3 STEPS) -->
        <div class="mb-10">
            <div class="flex justify-between items-center relative mb-4 px-2">
                <div class="progress-line">
                    <div class="progress-line-fill" id="progressFill" style="width: 0%;"></div>
                </div>
                <div class="progress-step flex flex-col items-center relative z-10" data-step="1">
                    <div class="w-10 h-10 rounded-full bg-accent text-black flex items-center justify-center font-bold mb-2 step-circle shadow-[0_0_15px_rgba(51,232,24,0.3)] text-sm">1</div>
                    <span class="text-[10px] text-gray-400 uppercase tracking-widest font-bold step-label">Akun</span>
                </div>
                <div class="progress-step flex flex-col items-center relative z-10" data-step="2">
                    <div class="w-10 h-10 rounded-full bg-white/10 text-gray-400 flex items-center justify-center font-bold mb-2 step-circle text-sm">2</div>
                    <span class="text-[10px] text-gray-400 uppercase tracking-widest font-bold step-label">Setuju</span>
                </div>
                <div class="progress-step flex flex-col items-center relative z-10" data-step="3">
                    <div class="w-10 h-10 rounded-full bg-white/10 text-gray-400 flex items-center justify-center font-bold mb-2 step-circle text-sm">3</div>
                    <span class="text-[10px] text-gray-400 uppercase tracking-widest font-bold step-label">Bayar</span>
                </div>
            </div>
        </div>

        <!-- Form Container -->
        <div class="bg-[#111] rounded-2xl border border-white/10 p-6 md:p-8">
            <form id="complaintForm" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <!-- Step 1: Akun -->
                <div class="step-container" data-step="1">
                    <h2 class="text-2xl font-bold mb-6">Konfirmasi Akun</h2>
                    <div class="space-y-4">
                        <?php 
                        $uName = ($user ? ($user['name'] ?? null) : null) ?: session()->get('userName') ?: session()->get('name') ?: '';
                        $uEmail = ($user ? ($user['email'] ?? null) : null) ?: session()->get('userEmail') ?: session()->get('email') ?: '';
                        $uPhone = ($user ? ($user['phone'] ?? null) : null) ?: session()->get('userPhone') ?: session()->get('phone') ?: session()->get('whatsapp') ?: '';
                        ?>

                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Nama Lengkap <span class="text-red-400">*</span></label>
                            <input type="text" name="name" id="reg_name" value="<?= esc($uName) ?>" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none transition-all placeholder-gray-600" placeholder="Nama lengkap" <?= session()->get('isLoggedIn') ? 'readonly' : '' ?>>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Email <span class="text-red-400">*</span></label>
                            <input type="email" name="email" id="reg_email" value="<?= esc($uEmail) ?>" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none transition-all placeholder-gray-600" placeholder="email@contoh.com" <?= session()->get('isLoggedIn') ? 'readonly' : '' ?>>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">No WhatsApp <span class="text-red-400">*</span></label>
                            <input type="tel" name="whatsapp" id="reg_whatsapp" value="<?= esc($uPhone) ?>" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none transition-all placeholder-gray-600" placeholder="08123456789" <?= session()->get('isLoggedIn') ? 'readonly' : '' ?>>
                        </div>

                        <?php if (!session()->get('isLoggedIn')): ?>
                        <!-- OTP Section for Guests -->
                        <div id="otpSection" class="mt-6 pt-6 border-t border-white/5 space-y-4">
                            <div id="otpRequestArea">
                                <button type="button" onclick="sendOtp()" id="btnSendOtp" class="w-full py-4 bg-white/5 border border-white/10 text-white font-bold rounded-xl hover:bg-white/10 transition flex items-center justify-center gap-2 group">
                                    <i class="fas fa-paper-plane text-accent group-hover:translate-x-1 transition-transform"></i> Kirim Kode Verifikasi (OTP)
                                </button>
                                <p class="text-[10px] text-gray-500 text-center mt-3 mb-4">Kode akan dikirimkan ke WhatsApp Anda untuk verifikasi.</p>

                                <!-- Turnstile Widget -->
                                <div class="turnstile-wrap mb-4">
                                    <div class="cf-turnstile w-full"
                                        data-sitekey="<?= env('TURNSTILE_SITE_KEY', '0x4AAAAAAAzM8JmJ8PGJ_YbS') ?>"
                                        data-theme="dark"
                                        data-size="flexible">
                                    </div>
                                </div>
                            </div>

                            <div id="otpVerifyArea" class="hidden space-y-4">
                                <div>
                                    <label class="block text-[10px] font-black text-accent uppercase tracking-widest mb-2 text-center">Masukkan 6 Digit Kode</label>
                                    <input type="text" id="otp_code" maxlength="6" class="w-full bg-[#111] border-2 border-accent/20 rounded-2xl px-6 py-4 text-center text-3xl font-black tracking-[0.5em] focus:border-accent focus:outline-none transition-all text-white" placeholder="••••••">
                                </div>
                                <button type="button" onclick="verifyOtp()" id="btnVerifyOtp" class="w-full py-5 bg-accent text-black font-black uppercase tracking-widest rounded-2xl hover:shadow-[0_0_30px_rgba(51,232,24,0.3)] transition-all">
                                    Verifikasi & Buat Akun
                                </button>
                                <div class="text-center">
                                    <button type="button" onclick="sendOtp()" id="btnResendOtp" class="text-[10px] text-gray-500 hover:text-white transition underline">Kirim Ulang Kode</button>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Step 2: Perjanjian -->
                <div class="step-container hidden" data-step="2">
                    <h2 class="text-2xl font-bold mb-6">Syarat & Ketentuan</h2>
                    <div class="bg-black/50 border border-white/10 rounded-2xl p-6 max-h-[300px] overflow-y-auto mb-6 custom-scrollbar" id="legalDocumentContent">
                        <div class="text-center py-10">
                            <i class="fas fa-spinner fa-spin text-3xl text-accent mb-3"></i>
                            <p class="text-gray-400 text-sm italic">Memuat dokumen perjanjian...</p>
                        </div>
                    </div>
                    <label class="flex items-start gap-4 p-5 bg-white/5 rounded-2xl border border-white/5 hover:border-accent transition-all cursor-pointer group">
                        <div class="relative flex items-center mt-0.5">
                            <input type="checkbox" name="agreed" id="agreed" class="sr-only peer">
                            <div class="w-6 h-6 rounded-md border-2 border-white/20 peer-checked:bg-accent peer-checked:border-accent transition-all flex items-center justify-center overflow-hidden">
                                <svg class="w-4 h-4 text-white opacity-0 scale-50 transition-all duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                        <span class="text-gray-300 text-sm leading-relaxed group-hover:text-white transition-colors font-medium">SAYA TELAH MEMBACA DAN MENYETUJUI SELURUH SYARAT DAN KETENTUAN LAYANAN ADVOKASI ALMAI</span>
                    </label>
                </div>

                <!-- Step 3: Konfirmasi & Bayar -->
                <div class="step-container hidden" data-step="3">
                    <div class="text-center py-4">
                        <div class="w-20 h-20 bg-accent/20 rounded-full flex items-center justify-center mx-auto mb-6 shadow-[0_0_50px_rgba(51,232,24,0.15)] animate-pulse">
                            <i class="fas fa-shield-halved text-accent text-3xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold mb-3">Siap Dilanjutkan</h2>
                        <p class="text-gray-400 text-sm mb-8 max-w-sm mx-auto leading-relaxed">
                            Layanan Advokasi Almai akan segera aktif. Silakan selesaikan pembayaran biaya administrasi investigasi di bawah ini.
                        </p>
                        
                        <div class="bg-gradient-to-b from-white/5 to-transparent border border-white/10 rounded-3xl p-8 mb-6 max-w-[280px] mx-auto shadow-2xl">
                            <span class="text-gray-500 text-[10px] uppercase tracking-[4px] font-bold block mb-3">Biaya Layanan</span>
                            <div class="flex items-center justify-center gap-1">
                                <span class="text-accent font-bold text-lg mb-4">Rp</span>
                                <h3 class="text-6xl font-black text-white tracking-tighter">88k</h3>
                            </div>
                            <p class="text-[10px] text-gray-500 mt-4 italic font-medium leading-relaxed">Biaya administrasi <br>& registrasi berkas digital</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex gap-4 mt-12">
                    <button type="button" id="prevBtn" class="hidden flex-1 py-4 bg-white/10 text-white font-bold rounded-xl hover:bg-white/20 transition">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </button>
                    <button type="button" id="nextBtn" class="flex-1 py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition shadow-[0_0_20px_rgba(51,232,24,0.3)]">
                        Selanjutnya <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                    <button type="submit" id="submitBtn" class="hidden flex-1 py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition shadow-[0_0_20px_rgba(51,232,24,0.3)] text-sm">
                        <i class="fas fa-check-circle mr-2"></i> Bayar Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm">
        <div class="text-center">
            <div class="w-16 h-16 border-4 border-white/20 border-t-accent rounded-full animate-spin mx-auto mb-4"></div>
            <p class="text-accent font-semibold italic">Processing...</p>
        </div>
    </div>

    <script>
        const subCategories = {
            transaksi: ["Order tidak tereksekusi", "Slippage besar", "Pending order tidak jalan", "Manipulasi Harga"],
            keuangan: ["Deposit tidak masuk", "Withdraw tertunda", "Penolakan penarikan"],
            sistem: ["Aplikasi crash", "Tidak bisa login", "Data chart tidak akurat"],
            akun: ["Akun diblokir", "KYC ditolak", "Akun diretas"],
            legal: ["Broker tidak teregulasi", "Indikasi scam", "Konflik kepentingan"],
            edukasi: ["Sinyal tidak sesuai", "Mentor misleading", "Robot trading bermasalah"],
            bonus: ["Bonus tidak bisa ditarik", "Syarat tersembunyi"],
            lainnya: ["Masalah lainnya"]
        };

        function updateSubCategories() {
            const main = document.getElementById('mainCategory').value;
            const subArea = document.getElementById('subCategoryArea');
            const subSelect = document.getElementById('subCategory');
            if (subCategories[main]) {
                subSelect.innerHTML = subCategories[main].map(c => `<option value="${c}">${c}</option>`).join('');
                subArea.classList.remove('hidden');
            } else { subArea.classList.add('hidden'); }
        }

        let currentStep = 1;
        const totalSteps = 3;
        let isLoggedIn = <?= session()->get('isLoggedIn') ? 'true' : 'false' ?>;
        let otpVerified = false;
        
        // CSRF Token Management
        let csrfName = '<?= csrf_token() ?>';
        let csrfHash = '<?= csrf_hash() ?>';

        async function sendOtp() {
            const name = document.getElementById('reg_name').value;
            const email = document.getElementById('reg_email').value;
            const whatsapp = document.getElementById('reg_whatsapp').value;
            const turnstileToken = document.querySelector('[name="cf-turnstile-response"]')?.value;

            if (!name || !email || !whatsapp) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Lengkapi Data',
                    text: 'Silakan lengkapi Nama, Email, dan WhatsApp terlebih dahulu.'
                });
                return;
            }

            if (!turnstileToken) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Verifikasi Captcha',
                    text: 'Silakan selesaikan verifikasi Captcha terlebih dahulu.'
                });
                return;
            }

            const btn = document.getElementById('btnSendOtp');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';

            try {
                const response = await fetch('<?= base_url('register/send-otp') ?>', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfHash
                    },
                    body: JSON.stringify({ 
                        name, 
                        email, 
                        phone: whatsapp, 
                        channel: 'whatsapp',
                        turnstile_token: turnstileToken
                    })
                });
                const result = await response.json();
                
                // Update CSRF token for next request
                if (result.csrfHash) csrfHash = result.csrfHash;

                // Reset Turnstile 
                if (typeof turnstile !== 'undefined') {
                    turnstile.reset();
                }
                
                if (result.success) {
                    Swal.fire({
                        icon: 'info',
                        title: 'OTP Terkirim',
                        text: result.message,
                        timer: 3000,
                        showConfirmButton: false
                    });
                    document.getElementById('otpRequestArea').classList.add('hidden');
                    document.getElementById('otpVerifyArea').classList.remove('hidden');
                } else {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                    if (result.is_registered) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Sudah Terdaftar',
                            text: result.message,
                            showCancelButton: true,
                            confirmButtonText: 'Login Sekarang',
                            cancelButtonText: 'Nanti',
                            confirmButtonColor: '#33E818'
                        }).then((res) => {
                            if (res.isConfirmed) {
                                window.location.href = '<?= base_url('login') ?>?redirect=daftar-advokasi';
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: result.message
                        });
                    }
                }
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Terjadi kesalahan sistem. Cek koneksi Anda.'
                });
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }

        async function verifyOtp() {
            const name = document.getElementById('reg_name').value;
            const email = document.getElementById('reg_email').value;
            const whatsapp = document.getElementById('reg_whatsapp').value;
            const otpCode = document.getElementById('otp_code').value;

            if (!otpCode || otpCode.length < 6) {
                alert('Ketik 6 digit kode OTP Anda.');
                return;
            }

            const btn = document.getElementById('btnVerifyOtp');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifikasi...';

            try {
                const response = await fetch('<?= base_url('register/verify-otp') ?>', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfHash
                    },
                    body: JSON.stringify({ 
                        name, 
                        email, 
                        phone: whatsapp, 
                        otp: otpCode,
                        password: 'User' + Math.floor(1000 + Math.random() * 9000) + '!', // Random temp password
                        affiliate_code: '<?= session()->get('checkout_ref') ?? request()->getGet('ref') ?>'
                    })
                });
                const result = await response.json();
                
                // Update CSRF token for next request
                if (result.csrfHash) csrfHash = result.csrfHash;
                
                if (result.success) {
                    otpVerified = true;
                    isLoggedIn = true;
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Verifikasi berhasil! Akun Anda telah dibuat secara otomatis.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    // Hide OTP section and proceed
                    document.getElementById('otpSection').innerHTML = `
                        <div class="p-4 bg-accent/10 border border-accent/20 rounded-2xl flex items-center gap-3">
                            <i class="fas fa-check-circle text-accent text-xl"></i>
                            <div>
                                <p class="text-white font-bold text-xs leading-none mb-1">Akun Terverifikasi</p>
                                <p class="text-gray-400 text-[10px]">Silakan klik Lanjut ke step berikutnya.</p>
                            </div>
                        </div>`;
                    
                    // Unlock next button or auto show next step
                    currentStep++;
                    showStep(currentStep);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'OTP Salah',
                        text: result.message
                    });
                }
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan',
                    text: 'Gagal verifikasi. Silakan coba lagi.'
                });
            } finally {
                btn.disabled = false;
                btn.innerHTML = 'Verifikasi & Buat Akun';
            }
        }

        async function fetchLegalDocument() {
            const container = document.getElementById('legalDocumentContent');
            try {
                const response = await fetch('<?= base_url('api/legal-document/surat-advokasi') ?>');
                const result = await response.json();
                if (result.success && result.data) {
                    let content = result.data.content;
                    
                    const name = document.querySelector('input[name="name"]')?.value || '....................';
                    const email = document.querySelector('input[name="email"]')?.value || '....................';
                    const whatsapp = document.querySelector('input[name="whatsapp"]')?.value || '....................';
                    
                    const now = new Date();
                    const romanMonths = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
                    const hariTanggal = new Intl.DateTimeFormat('id-ID', { dateStyle: 'full' }).format(now);
                    const romanMonth = romanMonths[now.getMonth()];
                    const year = now.getFullYear();
                    const timestamp = now.toLocaleString('id-ID');
                    const randomNo = Math.floor(Math.random() * 1000).toString().padStart(3, '0');

                    const placeholders = {
                        'NAMA_USER': name,
                        'EMAIL_USER': email,
                        'NO_KTP': '....................',
                        'ALAMAT': '....................',
                        'NO_TELP_USER': whatsapp,
                        'NO': randomNo,
                        'BULAN': romanMonth,
                        'TAHUN': year,
                        'HARI_TANGGAL': hariTanggal,
                        'TIMESTAMP': timestamp
                    };

                    Object.keys(placeholders).forEach(key => {
                        const regex = new RegExp(`\\{${key}\\}`, 'g');
                        content = content.replace(regex, placeholders[key]);
                    });

                    const displayTitle = result.data.title === 'SURAT ADVOKASI' ? 'SYARAT & KETENTUAN' : result.data.title;
                    container.innerHTML = `
                        <h3 class="text-lg font-bold mb-4 text-accent text-center uppercase tracking-wider">${displayTitle}</h3>
                        <div class="prose prose-invert max-w-none text-[11px] text-gray-200 leading-relaxed font-medium legal-content-body">
                            ${content}
                        </div>`;
                } else {
                    container.innerHTML = '<p class="text-red-400 text-center py-10 font-bold">Gagal memuat dokumen.</p>';
                }
            } catch (error) {
                container.innerHTML = '<p class="text-red-400 text-center py-10 font-bold">Terjadi kesalahan koneksi.</p>';
            }
        }

        function showStep(step) {
            document.querySelectorAll('.step-container').forEach(c => c.classList.add('hidden'));
            document.querySelector(`.step-container[data-step="${step}"]`).classList.remove('hidden');
            
            document.getElementById('prevBtn').classList.toggle('hidden', step === 1);
            document.getElementById('nextBtn').classList.toggle('hidden', step === totalSteps);
            document.getElementById('submitBtn').classList.toggle('hidden', step !== totalSteps);
            
            const progress = ((step - 1) / (totalSteps - 1)) * 100;
            document.getElementById('progressFill').style.width = `${progress}%`;
            
            document.querySelectorAll('.progress-step').forEach(el => {
                const s = parseInt(el.dataset.step);
                const circle = el.querySelector('.step-circle');
                const label = el.querySelector('.step-label');
                
                if (s < step) { 
                    circle.className = 'w-10 h-10 rounded-full bg-accent text-white flex items-center justify-center font-bold mb-2 step-circle border-none'; 
                    circle.innerHTML = '<i class="fas fa-check"></i>';
                    label.classList.replace('text-gray-400', 'text-accent');
                } else if (s === step) { 
                    circle.className = 'w-10 h-10 rounded-full bg-accent text-black flex items-center justify-center font-bold mb-2 step-circle border-none shadow-[0_0_20px_rgba(51,232,24,0.4)]'; 
                    circle.innerHTML = s;
                    label.classList.replace('text-gray-400', 'text-white');
                } else { 
                    circle.className = 'w-10 h-10 rounded-full bg-white/5 text-gray-500 flex items-center justify-center font-bold mb-2 step-circle border border-white/10'; 
                    circle.innerHTML = s;
                    label.classList.add('text-gray-400');
                    label.classList.remove('text-white', 'text-accent');
                }
            });
            
            if (step === 2) fetchLegalDocument();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function validateStep(step) {
            if (step === 1 && !isLoggedIn && !otpVerified) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Verifikasi Diperlukan',
                    text: 'Silakan verifikasi OTP terlebih dahulu untuk membuat akun otomatis.'
                });
                return false;
            }
            const container = document.querySelector(`.step-container[data-step="${step}"]`);
            const inputs = container.querySelectorAll('input:not([disabled]), select:not([disabled]), textarea:not([disabled])');
            let isValid = true;
            let firstInvalid = null;
            let customError = "";

            inputs.forEach(i => {
                // Determine if field is optional
                // In Step 3, everything except chronology is optional
                let isOptional = false;
                if (step === 3) {
                    if (i.name !== 'chronology') isOptional = true;
                }
                
                if (i.name === 'sub_category' || i.name === 'kode_affiliator') isOptional = true;

                if (isOptional) return;

                if (i.type === 'checkbox') {
                    if (i.hasAttribute('required') || i.name === 'agreed') {
                        if (!i.checked) {
                            isValid = false;
                            if (!firstInvalid) firstInvalid = i;
                        }
                    }
                } else if (i.type === 'radio') {
                    const name = i.name;
                    const checked = container.querySelector(`input[name="${name}"]:checked`);
                    if (!checked) {
                        isValid = false;
                        if (!firstInvalid) firstInvalid = i;
                    }
                } else if (i.tagName === 'SELECT') {
                    if (!i.value || i.value === "" || i.value === i.options[0].value) {
                         isValid = false;
                         if (!firstInvalid) firstInvalid = i;
                    }
                } else {
                    const val = i.value.trim();
                    if (!val) {
                        isValid = false;
                        if (!firstInvalid) firstInvalid = i;
                    } else {
                        // Advanced format validation
                        if (i.name === 'email' && !val.includes('@')) {
                            isValid = false;
                            customError = "Format email tidak valid.";
                            if (!firstInvalid) firstInvalid = i;
                        } else if (i.name === 'whatsapp' && val.length < 10) {
                            isValid = false;
                            customError = "Nomor WhatsApp minimal 10 digit.";
                            if (!firstInvalid) firstInvalid = i;
                        } else if (i.name === 'ktp_number' && val.length !== 16) {
                            isValid = false;
                            customError = "Nomor KTP harus tepat 16 digit.";
                            if (!firstInvalid) firstInvalid = i;
                        }
                    }
                }
            });

            if (!isValid) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Lengkapi Form',
                    text: customError || 'Mohon lengkapi seluruh data wajib (*) dengan benar sebelum melanjutkan.'
                });
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstInvalid.focus();
                }
            }
            return isValid;
        }

        document.getElementById('nextBtn').addEventListener('click', () => { if(validateStep(currentStep)) { currentStep++; showStep(currentStep); } });
        document.getElementById('prevBtn').addEventListener('click', () => { currentStep--; showStep(currentStep); });
        function handleFileChange(i) { document.getElementById('file_display_name').textContent = i.files[0] ? '✓ ' + i.files[0].name : ''; }

        document.getElementById('complaintForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const overlay = document.getElementById('loadingOverlay');
            const loadingTextDiv = overlay.querySelector('.text-center');
            const originalContent = loadingTextDiv ? loadingTextDiv.innerHTML : '';

            overlay.classList.remove('hidden');
            overlay.classList.add('flex');

            try {
                const formData = new FormData(this);
                // No need to manually add CSRF for FormData if using standard POST, 
                // but since we include it in headers for consistency:
                const response = await fetch('<?= base_url('daftar-advokasi/submit') ?>', { 
                    method: 'POST', 
                    body: formData, 
                    headers: { 
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfHash
                    } 
                });
                
                const responseText = await response.text();
                let result;
                try {
                    // Strip potential debug info (HTML comments) before parsing
                    const cleanJson = responseText.split('<!--')[0].trim();
                    result = JSON.parse(cleanJson);
                    if (result.csrfHash) csrfHash = result.csrfHash; // Update token
                } catch (jsonErr) {
                    console.error('Raw Server Response:', responseText);
                    throw new Error('Format data server tidak valid. Silakan hubungi Admin.');
                }

                if (response.ok && result.success) {
                    if (loadingTextDiv) {
                        loadingTextDiv.innerHTML = `
                            <div class="w-20 h-20 bg-accent rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce shadow-[0_0_30px_rgba(51,232,24,0.5)]">
                                <i class="fas fa-check text-black text-4xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-2">Laporan Terkirim!</h3>
                            <p class="text-gray-400 mb-6 italic">Data Anda telah diterima. Mengalihkan ke halaman pembayaran...</p>
                        `;
                    }

                    setTimeout(() => {
                        window.location.assign(result.redirect || '<?= base_url('checkout/layanan/advokasi') ?>');
                    }, 2000);
                } else {
                    overlay.classList.replace('flex', 'hidden');
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: result.message || 'Gagal mengirim pengaduan.'
                    });
                }
            } catch (err) {
                overlay.classList.replace('flex', 'hidden');
                console.error(err);
                Swal.fire({
                    icon: 'error',
                    title: 'Koneksi Error',
                    text: 'Terjadi kesalahan koneksi saat mengirim data.'
                });
            }
        });
        showStep(1);
    </script>
</body>
</html>
