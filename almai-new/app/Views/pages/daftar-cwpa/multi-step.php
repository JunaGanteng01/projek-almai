<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Daftar CWPA - Almai E-Learning') ?></title>
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

        /* Progress Line */
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

        /* File upload custom */
        .file-upload-label {
            cursor: pointer;
            transition: all 0.3s;
        }

        .file-upload-label:hover {
            border-color: var(--accent);
            background: rgba(51, 232, 24, 0.05);
        }

        /* Hide file input */
        input[type="file"] {
            display: none;
        }

        /* Smooth transitions */
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
    </style>
</head>

<body class="antialiased min-h-screen relative overflow-x-hidden">
    <!-- Navbar -->
    <?= $this->include('partials/navbar') ?>

    <div class="absolute inset-0 bg-grid z-0 pointer-events-none"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[300px] md:w-[800px] h-[300px] md:h-[500px] bg-accent/20 blur-[120px] rounded-full pointer-events-none"></div>

    <div class="relative z-10 w-full max-w-2xl px-6 mx-auto pt-28 pb-12 min-h-screen flex flex-col justify-center">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="<?= base_url('/') ?>" class="inline-flex items-center gap-2 text-2xl font-bold">
                <img src="<?= base_url('images/alma.gif') ?>" alt="ALMAI" class="h-10">
                <span>ALMAI</span>
            </a>
            <p class="text-gray-400 mt-2">Pendaftaran CWPA - E-Learning WPA Bersertifikat</p>
        </div>

        <!-- Progress Indicators -->
        <div class="mb-8">
            <div class="flex justify-between items-center relative mb-4">
                <div class="progress-line">
                    <div class="progress-line-fill" id="progressFill" style="width: 0%;"></div>
                </div>
                <div class="progress-step flex flex-col items-center relative z-10" data-step="1">
                    <div class="w-10 h-10 rounded-full bg-accent text-black flex items-center justify-center font-bold mb-2 step-circle">1</div>
                    <span class="text-xs text-gray-400 hidden md:block step-label">Akun</span>
                </div>
                <div class="progress-step flex flex-col items-center relative z-10" data-step="2">
                    <div class="w-10 h-10 rounded-full bg-white/10 text-gray-400 flex items-center justify-center font-bold mb-2 step-circle">2</div>
                    <span class="text-xs text-gray-400 hidden md:block step-label">Data</span>
                </div>
                <div class="progress-step flex flex-col items-center relative z-10" data-step="3">
                    <div class="w-10 h-10 rounded-full bg-white/10 text-gray-400 flex items-center justify-center font-bold mb-2 step-circle">3</div>
                    <span class="text-xs text-gray-400 hidden md:block step-label">Medsos</span>
                </div>
                <div class="progress-step flex flex-col items-center relative z-10" data-step="4">
                    <div class="w-10 h-10 rounded-full bg-white/10 text-gray-400 flex items-center justify-center font-bold mb-2 step-circle">4</div>
                    <span class="text-xs text-gray-400 hidden md:block step-label">Skill</span>
                </div>
                <div class="progress-step flex flex-col items-center relative z-10" data-step="5">
                    <div class="w-10 h-10 rounded-full bg-white/10 text-gray-400 flex items-center justify-center font-bold mb-2 step-circle">5</div>
                    <span class="text-xs text-gray-400 hidden md:block step-label">Syarat</span>
                </div>
                <div class="progress-step flex flex-col items-center relative z-10" data-step="6">
                    <div class="w-10 h-10 rounded-full bg-white/10 text-gray-400 flex items-center justify-center font-bold mb-2 step-circle">6</div>
                    <span class="text-xs text-gray-400 hidden md:block step-label">Dokumen</span>
                </div>
                <div class="progress-step flex flex-col items-center relative z-10" data-step="7">
                    <div class="w-10 h-10 rounded-full bg-white/10 text-gray-400 flex items-center justify-center font-bold mb-2 step-circle">7</div>
                    <span class="text-xs text-gray-400 hidden md:block step-label">Setuju</span>
                </div>
                <div class="progress-step flex flex-col items-center relative z-10" data-step="8">
                    <div class="w-10 h-10 rounded-full bg-white/10 text-gray-400 flex items-center justify-center font-bold mb-2 step-circle">8</div>
                    <span class="text-xs text-gray-400 hidden md:block step-label">Bayar</span>
                </div>
            </div>
        </div>

        <!-- Form Container -->
        <div class="bg-[#111] rounded-2xl border border-white/10 p-6 md:p-8">
            <div id="errorMessage"></div>

            <form id="cwpaForm" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <!-- Step 1: Buat Akun CWPA -->
                <div class="step-container" data-step="1">
                    <h2 class="text-2xl font-bold mb-6">Buat Akun CWPA</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Nama Lengkap <span class="text-red-400">*</span></label>
                            <input type="text" name="name" value="<?= isset($user) ? esc($user['name']) : '' ?>" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" placeholder="Nama lengkap Anda">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Email <span class="text-red-400">*</span></label>
                            <input type="email" name="email" id="emailInput" value="<?= isset($user) ? esc($user['email']) : '' ?>" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" placeholder="email@example.com">
                        </div>

                        <div>
                            <label class="block text-sm text-gray-400 mb-2">No WhatsApp <span class="text-red-400">*</span></label>
                            <input type="tel" name="whatsapp" id="waInput" value="<?= isset($user) ? esc($user['phone'] ?? $user['whatsapp'] ?? '') : '' ?>" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" placeholder="08123456789">
                        </div>
                        <?php if (!session()->get('isLoggedIn')): ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">
                                        Password <span class="text-red-400">*</span>
                                        <span class="text-xs text-gray-500 ml-2">(Min. 8 karakter, uppercase, number, special char)</span>
                                    </label>
                                    <div class="relative">
                                        <input type="password" name="password" id="password" required minlength="8" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" placeholder="Min. 8 karakter - Cth: P@ssw0rd123" data-toggle-pwd="password">
                                        <button type="button" class="toggle-password-btn absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition" data-target="password" aria-label="Toggle password visibility">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Password harus berisi kombinasi huruf besar, angka, dan karakter khusus</p>
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">Konfirmasi Password <span class="text-red-400">*</span></label>
                                    <div class="relative">
                                        <input type="password" name="password_confirm" id="password_confirm" required minlength="8" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" placeholder="Ulangi password" data-toggle-pwd="password_confirm">
                                        <button type="button" class="toggle-password-btn absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white transition" data-target="password_confirm" aria-label="Toggle password visibility">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                                <div id="passwordStatus" class="col-span-1 md:col-span-2 text-xs font-semibold mt-1 hidden"></div>
                            </div>
                        <?php endif; ?>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Kode Affiliator <span class="text-gray-600 text-xs">(Opsional)</span></label>
                            <input type="text" name="kode_affiliator" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none uppercase" placeholder="Masukkan kode affiliator">
                        </div>
                        <label class="flex items-start gap-2 text-gray-400 text-sm">
                            <input type="checkbox" name="terms" id="terms1" <?= session()->get('isLoggedIn') ? 'checked' : 'required' ?> class="accent-accent mt-1">
                            <span>Saya setuju dengan <a href="<?= base_url('syarat-ketentuan') ?>" target="_blank" class="text-accent hover:underline">Syarat & Ketentuan</a> serta <a href="<?= base_url('kebijakan-privasi') ?>" target="_blank" class="text-accent hover:underline">Kebijakan Privasi</a></span>
                        </label>
                    </div>
                </div>

                <!-- Step 2: Data Diri -->
                <div class="step-container hidden" data-step="2">
                    <h2 class="text-2xl font-bold mb-6">Data Diri</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Nomor KTP (16 Digit) <span class="text-red-400">*</span></label>
                            <input type="text" name="ktp_number" required maxlength="16" pattern="[0-9]{16}" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" placeholder="3201234567890123">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Nomor NPWP <span class="text-red-400">*</span></label>
                            <input type="text" name="npwp_number" id="npwp_input" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" placeholder="123456789012345 (Tanpa titik & tanda hubung)">
                            <p class="text-[10px] text-gray-500 mt-1">Hanya angka, otomatis dibersihkan dari titik (.) dan tanda hubung (-)</p>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Alamat Lengkap <span class="text-red-400">*</span></label>
                            <textarea name="address" id="address_input" required rows="4" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none resize-none" placeholder="Masukkan alamat lengkap sesuai KTP (Min. 10 karakter)"></textarea>
                            <div id="address_error" class="text-xs text-red-400 mt-1 hidden">Alamat harus minimal 10 karakter.</div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Akun Media Sosial -->
                <div class="step-container hidden" data-step="3">
                    <h2 class="text-2xl font-bold mb-6">Akun Media Sosial</h2>
                    <p class="text-gray-400 text-sm mb-4">Isi minimal salah satu akun media sosial</p>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-400 mb-2"><i class="fab fa-instagram text-pink-500 mr-2"></i>Instagram</label>
                            <input type="text" name="instagram" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" placeholder="@username">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2"><i class="fab fa-facebook text-blue-500 mr-2"></i>Facebook</label>
                            <input type="text" name="facebook" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" placeholder="https://facebook.com/...">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2"><i class="fab fa-tiktok mr-2"></i>TikTok</label>
                            <input type="text" name="tiktok" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" placeholder="@username">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2"><i class="fab fa-linkedin text-blue-600 mr-2"></i>LinkedIn</label>
                            <input type="text" name="linkedin" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" placeholder="https://linkedin.com/in/...">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2"><i class="fab fa-youtube text-red-500 mr-2"></i>YouTube</label>
                            <input type="text" name="youtube" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" placeholder="https://youtube.com/@...">
                        </div>
                    </div>
                </div>

                <!-- Step 4: Pengalaman & Spesialisasi -->
                <div class="step-container hidden" data-step="4">
                    <h2 class="text-2xl font-bold mb-6">Pengalaman & Spesialisasi</h2>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 p-4 bg-white/5 rounded-xl border border-white/10 hover:border-accent transition cursor-pointer">
                            <input type="checkbox" name="specialties[]" value="kripto" class="accent-accent w-5 h-5">
                            <span class="text-gray-300">Specialist Kripto, Index, Aset Digital</span>
                        </label>
                        <label class="flex items-center gap-3 p-4 bg-white/5 rounded-xl border border-white/10 hover:border-accent transition cursor-pointer">
                            <input type="checkbox" name="specialties[]" value="forex" class="accent-accent w-5 h-5">
                            <span class="text-gray-300">Specialist Forex</span>
                        </label>
                        <label class="flex items-center gap-3 p-4 bg-white/5 rounded-xl border border-white/10 hover:border-accent transition cursor-pointer">
                            <input type="checkbox" name="specialties[]" value="komoditi" class="accent-accent w-5 h-5">
                            <span class="text-gray-300">Spesialis Komoditi</span>
                        </label>
                        <label class="flex items-center gap-3 p-4 bg-accent/10 rounded-xl border border-accent/30 hover:border-accent transition cursor-pointer">
                            <input type="checkbox" id="selectAll" class="accent-accent w-5 h-5">
                            <span class="text-accent font-semibold">Pilih Semua</span>
                        </label>
                    </div>
                </div>

                <!-- Step 5: Persyaratan C-WPA -->
                <div class="step-container hidden" data-step="5">
                    <h2 class="text-2xl font-bold mb-6">Persyaratan C-WPA</h2>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm text-gray-400 mb-3">Apakah Anda memiliki Ijazah S1? <span class="text-red-400">*</span></label>
                            <div class="space-y-2">
                                <label for="ijazah_yes" class="flex items-center gap-3 p-4 bg-white/5 rounded-xl border border-white/10 hover:border-accent transition cursor-pointer">
                                    <input type="radio" id="ijazah_yes" name="has_ijazah_s1" value="yes" required class="accent-accent w-5 h-5 require-check-disqualification">
                                    <span class="text-gray-300">Ya</span>
                                </label>
                                <label for="ijazah_no" class="flex items-center gap-3 p-4 bg-white/5 rounded-xl border border-white/10 hover:border-accent transition cursor-pointer">
                                    <input type="radio" id="ijazah_no" name="has_ijazah_s1" value="no" class="accent-accent w-5 h-5 require-check-disqualification">
                                    <span class="text-gray-300">Tidak</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-400 mb-3">Apakah Anda memiliki SKCK? <span class="text-red-400">*</span></label>
                            <div class="space-y-2">
                                <label for="skck_yes" class="flex items-center gap-3 p-4 bg-white/5 rounded-xl border border-white/10 hover:border-accent transition cursor-pointer">
                                    <input type="radio" id="skck_yes" name="has_skck_card" value="yes" required class="accent-accent w-5 h-5 require-toggle-skck-willingness require-check-disqualification">
                                    <span class="text-gray-300">Ya</span>
                                </label>
                                <label for="skck_no" class="flex items-center gap-3 p-4 bg-white/5 rounded-xl border border-white/10 hover:border-accent transition cursor-pointer">
                                    <input type="radio" id="skck_no" name="has_skck_card" value="no" class="accent-accent w-5 h-5 require-toggle-skck-willingness require-check-disqualification">
                                    <span class="text-gray-300">Tidak</span>
                                </label>
                            </div>
                        </div>

                        <!-- Conditional: Bersedia Membuat SKCK -->
                        <div id="skckWillingnessSection" class="hidden">
                            <label class="block text-sm text-gray-400 mb-3">Apakah Anda bersedia membuat SKCK? <span class="text-red-400">*</span></label>
                            <div class="space-y-2">
                                <label for="willing_skck_yes" class="flex items-center gap-3 p-4 bg-white/5 rounded-xl border border-white/10 hover:border-accent transition cursor-pointer">
                                    <input type="radio" id="willing_skck_yes" name="willing_to_make_skck" value="yes" class="accent-accent w-5 h-5 require-check-disqualification">
                                    <span class="text-gray-300">Ya</span>
                                </label>
                                <label for="willing_skck_no" class="flex items-center gap-3 p-4 bg-white/5 rounded-xl border border-white/10 hover:border-accent transition cursor-pointer">
                                    <input type="radio" id="willing_skck_no" name="willing_to_make_skck" value="no" class="accent-accent w-5 h-5 require-check-disqualification">
                                    <span class="text-gray-300">Tidak</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-400 mb-3">Apakah Anda pernah divonis sebagai terpidana dengan hukuman lebih dari 5 (lima) tahun? <span class="text-red-400">*</span></label>
                            <div class="space-y-2">
                                <label for="felony_yes" class="flex items-center gap-3 p-4 bg-white/5 rounded-xl border border-white/10 hover:border-accent transition cursor-pointer">
                                    <input type="radio" id="felony_yes" name="has_felony_record" value="yes" required class="accent-accent w-5 h-5 require-toggle-felony-willingness require-check-disqualification">
                                    <span class="text-gray-300">Ya</span>
                                </label>
                                <label for="felony_no" class="flex items-center gap-3 p-4 bg-white/5 rounded-xl border border-white/10 hover:border-accent transition cursor-pointer">
                                    <input type="radio" id="felony_no" name="has_felony_record" value="no" class="accent-accent w-5 h-5 require-toggle-felony-willingness require-check-disqualification">
                                    <span class="text-gray-300">Tidak</span>
                                </label>
                            </div>
                        </div>

                        <!-- Conditional: Bersedia Membuat Surat Keterangan Tidak Pernah Terpidana -->
                        <div id="felonyWillingnessSection" class="hidden">
                            <label class="block text-sm text-gray-400 mb-3">Apakah bersedia membuat <strong>SURAT KETERANGAN TIDAK PERNAH SEBAGAI TERPIDANA</strong>? <span class="text-red-400">*</span></label>
                            <div class="space-y-2">
                                <label for="willing_felony_yes" class="flex items-center gap-3 p-4 bg-white/5 rounded-xl border border-white/10 hover:border-accent transition cursor-pointer">
                                    <input type="radio" id="willing_felony_yes" name="willing_to_make_felony_statement" value="yes" class="accent-accent w-5 h-5 require-check-disqualification">
                                    <span class="text-gray-300">Ya</span>
                                </label>
                                <label for="willing_felony_no" class="flex items-center gap-3 p-4 bg-white/5 rounded-xl border border-white/10 hover:border-accent transition cursor-pointer">
                                    <input type="radio" id="willing_felony_no" name="willing_to_make_felony_statement" value="no" class="accent-accent w-5 h-5 require-check-disqualification">
                                    <span class="text-gray-300">Tidak</span>
                                </label>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm text-gray-400 mb-3">Apakah Anda pernah dinyatakan pailit atau menjadi direktur/komisaris yang dinyatakan bersalah menyebabkan suatu perusahaan pailit dalam jangka waktu 5 (lima) tahun terakhir? <span class="text-red-400">*</span></label>
                            <div class="space-y-2">
                                <label for="bankruptcy_yes" class="flex items-center gap-3 p-4 bg-white/5 rounded-xl border border-white/10 hover:border-accent transition cursor-pointer">
                                    <input type="radio" id="bankruptcy_yes" name="has_bankruptcy_record" value="yes" required class="accent-accent w-5 h-5 require-check-disqualification">
                                    <span class="text-gray-300">Ya</span>
                                </label>
                                <label for="bankruptcy_no" class="flex items-center gap-3 p-4 bg-white/5 rounded-xl border border-white/10 hover:border-accent transition cursor-pointer">
                                    <input type="radio" id="bankruptcy_no" name="has_bankruptcy_record" value="no" class="accent-accent w-5 h-5 require-check-disqualification">
                                    <span class="text-gray-300">Tidak</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 6: Lengkapi Dokumen -->
                <div class="step-container hidden" data-step="6">
                    <h2 class="text-2xl font-bold mb-6">Lengkapi Dokumen</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">CV <span class="text-red-400">*</span></label>
                            <label for="cv" class="file-upload-label block border-2 border-dashed border-white/20 rounded-xl p-6 text-center">
                                <i class="fas fa-cloud-upload-alt text-3xl text-accent mb-2"></i>
                                <p class="text-gray-400 text-sm">Klik untuk upload CV (PDF, DOC, DOCX - Max 5MB)</p>
                                <p class="text-accent text-sm mt-2 file-name" id="cv_name"></p>
                            </label>
                            <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx" required>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">KTP <span class="text-red-400">*</span></label>
                            <label for="ktp_file" class="file-upload-label block border-2 border-dashed border-white/20 rounded-xl p-6 text-center">
                                <i class="fas fa-cloud-upload-alt text-3xl text-accent mb-2"></i>
                                <p class="text-gray-400 text-sm">Klik untuk upload KTP (PDF, JPG, PNG - Max 5MB)</p>
                                <p class="text-accent text-sm mt-2 file-name" id="ktp_file_name"></p>
                            </label>
                            <input type="file" id="ktp_file" name="ktp_file" accept=".pdf,.jpg,.jpeg,.png" required>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">NPWP <span class="text-red-400">*</span></label>
                            <label for="npwp_file" class="file-upload-label block border-2 border-dashed border-white/20 rounded-xl p-6 text-center">
                                <i class="fas fa-cloud-upload-alt text-3xl text-accent mb-2"></i>
                                <p class="text-gray-400 text-sm">Klik untuk upload NPWP (PDF, JPG, PNG - Max 5MB)</p>
                                <p class="text-accent text-sm mt-2 file-name" id="npwp_file_name"></p>
                            </label>
                            <input type="file" id="npwp_file" name="npwp_file" accept=".pdf,.jpg,.jpeg,.png" required>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Ijazah <span class="text-red-400">*</span></label>
                            <label for="ijazah" class="file-upload-label block border-2 border-dashed border-white/20 rounded-xl p-6 text-center">
                                <i class="fas fa-cloud-upload-alt text-3xl text-accent mb-2"></i>
                                <p class="text-gray-400 text-sm">Klik untuk upload Ijazah (PDF, JPG, PNG - Max 5MB)</p>
                                <p class="text-accent text-sm mt-2 file-name" id="ijazah_name"></p>
                            </label>
                            <input type="file" id="ijazah" name="ijazah" accept=".pdf,.jpg,.jpeg,.png" required>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">SKCK <span class="text-gray-600 text-xs">(Opsional)</span></label>
                            <label for="skck" class="file-upload-label block border-2 border-dashed border-white/20 rounded-xl p-6 text-center">
                                <i class="fas fa-cloud-upload-alt text-3xl text-accent mb-2"></i>
                                <p class="text-gray-400 text-sm">Klik untuk upload SKCK (PDF, JPG, PNG - Max 5MB)</p>
                                <p class="text-accent text-sm mt-2 file-name" id="skck_name"></p>
                            </label>
                            <input type="file" id="skck" name="skck" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                    </div>
                </div>

                <!-- Step 7: Perjanjian -->
                <div class="step-container hidden" data-step="7">
                    <h2 class="text-2xl font-bold mb-6">Perjanjian</h2>
                    <div class="bg-black border border-white/20 rounded-xl p-6 max-h-96 overflow-y-auto mb-4" id="legalDocumentContent">
                        <div class="text-center py-8">
                            <i class="fas fa-spinner fa-spin text-3xl text-accent mb-3"></i>
                            <p class="text-gray-400">Memuat dokumen perjanjian...</p>
                        </div>
                    </div>
                    <label class="flex items-start gap-2 text-gray-400 text-sm">
                        <input type="checkbox" name="agreement" id="agreement" required class="accent-accent mt-1">
                        <span>Saya telah membaca dan menyetujui seluruh syarat dan ketentuan di atas</span>
                    </label>
                </div>

                <!-- Step 8: Pembayaran Manual -->
                <div class="step-container hidden" data-step="8">
                    <h2 class="text-2xl font-bold mb-4">Pembayaran Pendaftaran</h2>
                    <p class="text-gray-400 text-sm mb-6">Silakan lakukan transfer manual untuk biaya pendaftaran CWPA.</p>

                    <div class="bg-accent/5 border border-accent/20 rounded-2xl p-6 mb-8">
                        <div class="flex items-center gap-4 mb-6 pb-6 border-b border-white/5">
                            <div class="w-12 h-12 bg-accent/10 rounded-xl flex items-center justify-center">
                                <i class="fas fa-university text-accent text-xl"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 uppercase tracking-wider">Metode Pembayaran</p>
                                <p class="font-bold text-lg text-white">Transfer Bank Mandiri</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400 text-sm">Nama Bank</span>
                                <span class="text-white font-semibold">Bank Mandiri</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400 text-sm">Atas Nama</span>
                                <span class="text-white font-semibold uppercase">PT. Alma Indonesia Raya</span>
                            </div>
                            <div class="flex justify-between items-center bg-black/40 p-4 rounded-xl">
                                <div>
                                    <span class="text-gray-500 text-[10px] block mb-1">Nomor Rekening</span>
                                    <span class="text-accent font-mono text-xl font-bold" id="bankAccountNumber">145-00-5007000-8</span>
                                </div>
                                <button type="button" onclick="copyAccountNumber()" class="p-2 hover:bg-white/10 rounded-lg transition text-gray-400 hover:text-white" title="Salin Rekening">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            <div class="flex justify-between items-center pt-2">
                                <span class="text-gray-400 text-sm">Total Biaya</span>
                                <span class="text-accent font-bold text-xl">Rp 23.500.000</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <label class="block text-sm text-gray-400 mb-2">Upload Bukti Transfer <span class="text-red-400">*</span></label>
                        <label for="transfer_proof" class="file-upload-label block border-2 border-dashed border-white/20 rounded-xl p-8 text-center transition hover:border-accent group">
                            <i class="fas fa-file-invoice-dollar text-4xl text-accent mb-3 group-hover:scale-110 transition-transform"></i>
                            <p class="text-gray-400 text-sm">Klik untuk upload bukti transfer (JPG, PNG, PDF - Max 5MB)</p>
                            <p class="text-accent text-sm mt-3 font-semibold file-name" id="transfer_proof_name"></p>
                        </label>
                        <input type="file" id="transfer_proof" name="transfer_proof" accept=".pdf,.jpg,.jpeg,.png" required>
                    </div>

                    <div class="mt-6 p-4 bg-white/5 rounded-xl border border-white/10">
                        <p class="text-[10px] text-gray-500 leading-relaxed">
                            <i class="fas fa-info-circle mr-1 text-accent"></i>
                            Pendaftaran Anda akan diproses setelah tim administrasi kami memverifikasi bukti transfer yang diunggah. Proses verifikasi biasanya memakan waktu 1x24 jam.
                        </p>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex gap-4 mt-8">
                    <button type="button" id="prevBtn" class="hidden flex-1 py-4 bg-white/10 text-white font-bold rounded-xl hover:bg-white/20 transition">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </button>
                    <button type="button" id="nextBtn" class="flex-1 py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition shadow-[0_0_20px_rgba(51,232,24,0.3)]">
                        Selanjutnya <i class="fas fa-arrow-right ml-2"></i>
                    </button>
                    <button type="submit" id="submitBtn" class="hidden flex-1 py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition shadow-[0_0_20px_rgba(51,232,24,0.3)]">
                        <i class="fas fa-check-circle mr-2"></i> Lanjut ke Pembayaran
                    </button>
                </div>
            </form>
        </div>

        <!-- Dual OTP Verification Modal -->
        <div id="dualOtpModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/95 backdrop-blur-xl">
            <div class="bg-[#0a0a0a] border border-white/10 rounded-3xl p-8 max-w-lg mx-4 w-full shadow-[0_0_50px_rgba(0,0,0,0.5)]">
                <div class="text-center mb-10">
                    <div class="w-20 h-20 bg-accent/10 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-user-shield text-4xl text-accent"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Verifikasi Keamanan</h3>
                    <p class="text-gray-400 text-sm">Demi keamanan, silakan verifikasi WhatsApp dan Email Anda</p>
                </div>

                <div class="space-y-8">
                    <!-- WhatsApp Verification Card -->
                    <div id="waVerificationCard" class="bg-white/5 border border-white/10 rounded-2xl p-5 group transition hover:border-green-500/30">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-500 text-black rounded-full flex items-center justify-center">
                                    <i class="fab fa-whatsapp text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white">WhatsApp</h4>
                                    <p class="text-[10px] text-gray-500 font-mono" id="waDisplayTarget"></p>
                                </div>
                            </div>
                            <div id="waStatusBadge" class="text-xs font-bold text-gray-500 px-3 py-1 bg-white/5 rounded-full">Belum Verifikasi</div>
                        </div>

                        <div id="waActionArea">
                            <button onclick="sendDualOtp('whatsapp')" id="btnSendWa" class="send-otp-btn w-full py-3 bg-green-500 text-black font-bold rounded-xl hover:bg-white transition text-sm" data-channel="whatsapp">
                                Kirim Kode OTP
                            </button>
                        </div>

                        <div id="waInputArea" class="hidden mt-4 space-y-3">
                            <div class="flex justify-between gap-2">
                                <?php for ($i = 0; $i < 6; $i++): ?>
                                    <input type="text" maxlength="1" class="wa-otp-digit w-full h-12 bg-black border border-white/20 rounded-lg text-center text-xl font-bold focus:border-green-500 focus:outline-none">
                                <?php endfor; ?>
                            </div>
                            <div id="waTimer" class="text-center text-[10px] text-gray-500">Kirim ulang dalam <span class="wa-seconds text-white">60</span>s</div>
                            <button onclick="verifyDualOtp('whatsapp')" class="verify-otp-btn w-full py-3 bg-white text-black font-bold rounded-xl hover:bg-accent transition text-sm" data-channel="whatsapp">
                                Verifikasi WhatsApp
                            </button>
                        </div>
                    </div>

                    <!-- Email Verification Card -->
                    <div id="emailVerificationCard" class="bg-white/5 border border-white/10 rounded-2xl p-5 group transition hover:border-blue-500/30">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-500 text-white rounded-full flex items-center justify-center">
                                    <i class="fas fa-envelope text-lg"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-white">Email</h4>
                                    <p class="text-[10px] text-gray-500 font-mono" id="emailDisplayTarget"></p>
                                </div>
                            </div>
                            <div id="emailStatusBadge" class="text-xs font-bold text-gray-500 px-3 py-1 bg-white/5 rounded-full">Belum Verifikasi</div>
                        </div>

                        <div id="emailActionArea">
                            <button onclick="sendDualOtp('email')" id="btnSendEmail" class="send-otp-btn w-full py-3 bg-blue-500 text-white font-bold rounded-xl hover:bg-white hover:text-black transition text-sm" data-channel="email">
                                Kirim Kode OTP
                            </button>
                        </div>

                        <div id="emailInputArea" class="hidden mt-4 space-y-3">
                            <div class="flex justify-between gap-2">
                                <?php for ($i = 0; $i < 6; $i++): ?>
                                    <input type="text" maxlength="1" class="email-otp-digit w-full h-12 bg-black border border-white/20 rounded-lg text-center text-xl font-bold focus:border-blue-500 focus:outline-none">
                                <?php endfor; ?>
                            </div>
                            <div id="emailTimer" class="text-center text-[10px] text-gray-500">Kirim ulang dalam <span class="email-seconds text-white">60</span>s</div>
                            <button onclick="verifyDualOtp('email')" class="verify-otp-btn w-full py-3 bg-white text-black font-bold rounded-xl hover:bg-accent transition text-sm" data-channel="email">
                                Verifikasi Email
                            </button>
                        </div>
                    </div>

                    <div class="pt-4 flex gap-4">
                        <button type="button" class="close-otp-modal-btn flex-1 py-4 bg-white/5 text-gray-400 font-bold rounded-xl hover:bg-white/10 transition text-sm" aria-label="Close verification modal">
                            Batal
                        </button>
                        <button type="button" id="btnFinishOtp" disabled onclick="finishDualVerification()" class="flex-[2] py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-sm disabled:opacity-30 disabled:cursor-not-allowed shadow-[0_0_20px_rgba(51,232,24,0.3)]">
                            Selesai & Lanjutkan
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="text-center mt-6">
            <a href="<?= base_url('/') ?>" class="text-gray-400 hover:text-accent transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Home
            </a>
        </div>
    </div>

    <!-- Disqualification Modal -->
    <div id="disqualificationModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm">
        <div class="bg-[#111] border border-white/10 rounded-2xl p-8 max-w-md mx-4 w-full">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-yellow-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-3xl text-yellow-500"></i>
                </div>
                <h3 class="text-xl font-bold mb-4">Mohon Maaf</h3>
                <p class="text-gray-400 mb-2">Berdasarkan peraturan perundang-undangan dan persyaratan untuk menjadi WPA, Anda belum memenuhi syarat.</p>
                <p class="text-gray-400">Silahkan konsultasikan perihal ini melalui WhatsApp.</p>
            </div>
            <div class="flex gap-3">
                <a href="https://wa.me/6281234567890?text=Halo,%20saya%20ingin%20konsultasi%20mengenai%20persyaratan%20CWPA" target="_blank" class="flex-1 py-4 bg-green-500 text-white font-bold rounded-xl hover:bg-green-600 transition text-center">
                    <i class="fab fa-whatsapp mr-2"></i> WhatsApp
                </a>
                <button onclick="closeDisqualificationModal()" class="flex-1 py-4 bg-white/10 text-white font-bold rounded-xl hover:bg-white/20 transition">
                    Kembali
                </button>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm">
        <div class="text-center">
            <div class="w-16 h-16 border-4 border-white/20 border-t-accent rounded-full animate-spin mx-auto mb-4"></div>
            <p class="text-accent font-semibold">Mohon tunggu, sedang mengupload berkas...</p>
        </div>
    </div>

    <script>
        // ============================================
        // HTTPS/Protocol Safety Helper
        // ============================================
        
        // Helper function to ensure URLs use the same protocol as current page
        function getSecureUrl(path) {
            const protocol = window.location.protocol; // 'https:' or 'http:'
            const host = window.location.host; // 'example.com' or 'example.com:8080'
            
            // Remove leading slash if present
            const cleanPath = path.startsWith('/') ? path : '/' + path;
            
            return protocol + '//' + host + cleanPath;
        }

        // Check if user is logged in
        const isLoggedIn = <?= session()->get('isLoggedIn') ? 'true' : 'false' ?>;
        const userData = isLoggedIn ? {
            name: <?= !empty($user['name']) ? json_encode($user['name']) : json_encode(session()->get('name') ?? '') ?>,
            email: <?= !empty($user['email']) ? json_encode($user['email']) : json_encode(session()->get('email') ?? '') ?>,
            whatsapp: <?= !empty($user['phone']) ? json_encode($user['phone']) : json_encode(session()->get('phone') ?? '') ?>
        } : null;

        // Set initial step: Skip Step 1 if logged in
        let currentStep = isLoggedIn ? 2 : 1;
        const totalSteps = 8;

        // Auto-fill Step 1 data for logged-in users
        if (isLoggedIn && userData) {
            document.querySelector('input[name="name"]').value = userData.name;
            document.querySelector('input[name="email"]').value = userData.email;
            document.querySelector('input[name="whatsapp"]').value = userData.whatsapp;

            // Make Step 1 fields readonly
            document.querySelector('input[name="name"]').readOnly = true;
            document.querySelector('input[name="email"]').readOnly = true;
            document.querySelector('input[name="whatsapp"]').readOnly = true;

            // Hide and disable password fields for logged-in users so validation doesn't fail
            const passwordFields = document.querySelectorAll('input[name="password"], input[name="password_confirm"]');
            passwordFields.forEach(field => {
                field.removeAttribute('required'); // Remove required
                field.disabled = true; // Disable input
                field.closest('div').parentElement.style.display = 'none';
            });

            // Handle terms checkbox in Step 1
            const termsCheckbox = document.getElementById('terms1');
            if (termsCheckbox) {
                termsCheckbox.removeAttribute('required');
                termsCheckbox.checked = true; // Auto-check for logic
                termsCheckbox.closest('label').style.display = 'none';
            }
        }

        // Toggle Password
        function togglePassword(id, btn) {
            const input = document.getElementById(id);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // File upload handlers
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                const fileName = this.files[0]?.name || '';
                const displayElement = document.getElementById(this.id + '_name');
                if (displayElement) {
                    displayElement.innerHTML = fileName ? `<i class="fas fa-check-circle mr-2"></i> ${fileName}` : '';
                }
            });
        });

        // NPWP Sanitization
        const npwpInput = document.getElementById('npwp_input');
        if (npwpInput) {
            npwpInput.addEventListener('input', function() {
                this.value = this.value.replace(/[\.\-]/g, '');
            });
            npwpInput.addEventListener('blur', function() {
                this.value = this.value.replace(/[\.\-]/g, '');
            });
        }

        // Address real-time validation
        const addressInput = document.getElementById('address_input');
        const addressError = document.getElementById('address_error');
        if (addressInput) {
            addressInput.addEventListener('input', function() {
                if (this.value.length > 0 && this.value.length < 10) {
                    addressError.classList.remove('hidden');
                    this.classList.add('border-red-500/50');
                } else {
                    addressError.classList.add('hidden');
                    this.classList.remove('border-red-500/50');
                }
            });
        }

        // Copy Account Number
        function copyAccountNumber() {
            const accNo = document.getElementById('bankAccountNumber').textContent;
            navigator.clipboard.writeText(accNo).then(() => {
                alert('Nomor rekening berhasil disalin!');
            });
        }

        // Select all specialties
        document.getElementById('selectAll')?.addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[name="specialties[]"]');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });

        // Toggle SKCK Willingness Section
        function toggleSkckWillingness() {
            const hasSkck = document.querySelector('input[name="has_skck_card"]:checked')?.value;
            const willingnessSection = document.getElementById('skckWillingnessSection');
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

        // Toggle Felony Statement Willingness
        function toggleFelonyWillingness() {
            const hasRecord = document.querySelector('input[name="has_felony_record"]:checked')?.value;
            const willingnessSection = document.getElementById('felonyWillingnessSection');
            const willingnessInputs = willingnessSection.querySelectorAll('input[name="willing_to_make_felony_statement"]');

            // If they answer NO to "Have you been convicted?", then they must sign a statement
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

        // Check Disqualification
        function checkDisqualification() {
            const hasS1 = document.querySelector('input[name="has_ijazah_s1"]:checked')?.value;
            const hasFelony = document.querySelector('input[name="has_felony_record"]:checked')?.value;
            const hasBankruptcy = document.querySelector('input[name="has_bankruptcy_record"]:checked')?.value;

            // Conditional triggers
            const willingSkck = document.querySelector('input[name="willing_to_make_skck"]:checked')?.value;
            const willingFelony = document.querySelector('input[name="willing_to_make_felony_statement"]:checked')?.value;

            // Disqualification Rules:
            // 1. Ijazah S1: Must have S1. If "No", disqualified.
            // 2. Felony: If "Yes", disqualified.
            // 3. Bankruptcy: If "Yes", disqualified.
            // 4. Willingness: If "No" to required statements, disqualified.

            if (hasS1 === 'no' || hasFelony === 'yes' || hasBankruptcy === 'yes' || willingSkck === 'no' || willingFelony === 'no') {
                showDisqualificationModal();
            }
        }

        function showDisqualificationModal() {
            const modal = document.getElementById('disqualificationModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDisqualificationModal() {
            const modal = document.getElementById('disqualificationModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');

            // Reset the disqualifying answers
            document.querySelectorAll('input[name="has_ijazah_s1"]').forEach(input => input.checked = false);
            document.querySelectorAll('input[name="has_felony_record"]').forEach(input => {
                input.checked = false;
                toggleFelonyWillingness(); // Hide the sub-section too
            });
            document.querySelectorAll('input[name="has_bankruptcy_record"]').forEach(input => input.checked = false);

            // Also reset willingness
            document.querySelectorAll('input[name="willing_to_make_skck"]').forEach(input => input.checked = false);
            document.querySelectorAll('input[name="willing_to_make_felony_statement"]').forEach(input => input.checked = false);
        }

        // Real-time password match validation
        function validatePasswordStatus() {
            const pass = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirm').value;
            const statusDiv = document.getElementById('passwordStatus');

            if (!pass && !confirm) {
                statusDiv.classList.add('hidden');
                return;
            }

            statusDiv.classList.remove('hidden');
            if (pass === confirm && pass.length >= 6) {
                statusDiv.innerHTML = '<i class="fas fa-check-circle mr-1"></i> Password cocok';
                statusDiv.className = 'col-span-1 md:col-span-2 text-xs font-semibold mt-1 text-green-500';
            } else if (pass !== confirm && confirm.length > 0) {
                statusDiv.innerHTML = '<i class="fas fa-times-circle mr-1"></i> Password tidak sama';
                statusDiv.className = 'col-span-1 md:col-span-2 text-xs font-semibold mt-1 text-red-500';
            } else if (pass.length < 6) {
                statusDiv.innerHTML = '<i class="fas fa-info-circle mr-1"></i> Password minimal 6 karakter';
                statusDiv.className = 'col-span-1 md:col-span-2 text-xs font-semibold mt-1 text-yellow-500';
            } else {
                statusDiv.classList.add('hidden');
            }
        }


        // Navigation
        document.getElementById('nextBtn').addEventListener('click', async () => {
            // Hard stop for disqualification on Step 5
            if (currentStep === 5) {
                checkDisqualification();
                if (!document.getElementById('disqualificationModal').classList.contains('hidden')) {
                    return; // Prevent any progress if disqualified
                }
            }

            // Strict validation: cannot proceed if current step is invalid
            if (validateStep(currentStep)) {

                // Check User Exists (Step 1 Only for Guests)
                if (currentStep === 1 && !isLoggedIn) {
                    const email = document.querySelector('input[name="email"]').value;
                    const whatsapp = document.querySelector('input[name="whatsapp"]').value;
                    const nextBtn = document.getElementById('nextBtn');

                    // If already verified, allow proceed
                    if (window.emailVerified && window.waVerified) {
                        currentStep++;
                        showStep(currentStep);
                        return;
                    }

                    // Show loading on button
                    const originalText = nextBtn.innerHTML;
                    nextBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memeriksa...';
                    nextBtn.disabled = true;

                    try {
                        const params = new URLSearchParams();
                        params.append('email', email);
                        params.append('whatsapp', whatsapp);
                        params.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

                        const response = await fetch(getSecureUrl('daftar-cwpa/check-user'), {
                            method: 'POST',
                            body: params,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Content-Type': 'application/x-www-form-urlencoded'
                            }
                        });

                        const result = await response.json();

                        if (result.status === 'exists') {
                            showError(result.message);
                            window.scrollTo({
                                top: 0,
                                behavior: 'smooth'
                            });
                            nextBtn.innerHTML = originalText;
                            nextBtn.disabled = false;
                            return; // STOP HERE
                        }

                        // Trigger Dual OTP Modal
                        showDualOtpModal();

                    } catch (e) {
                        console.error('Check user error', e);
                        showError('Gagal memeriksa data. Pastikan koneksi internet lancar.');
                    } finally {
                        nextBtn.innerHTML = originalText;
                        nextBtn.disabled = false;
                    }
                    return;
                }

                if (currentStep < totalSteps) {
                    currentStep++;
                    showStep(currentStep);
                }
            }
        });

        document.getElementById('prevBtn').addEventListener('click', () => {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        });

        function showStep(step) {
            // Clear error messages when switching steps
            const errorDiv = document.getElementById('errorMessage');
            if (errorDiv) errorDiv.innerHTML = '';

            // Check for referral code in URL
            const urlParams = new URLSearchParams(window.location.search);
            const referralCode = urlParams.get('reff');
            if (referralCode) {
                const affiliateInput = document.querySelector('input[name="kode_affiliator"]');
                if (affiliateInput) {
                    affiliateInput.value = referralCode;
                    affiliateInput.readOnly = true;
                    affiliateInput.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }

            // Hide all steps
            document.querySelectorAll('.step-container').forEach(s => s.classList.add('hidden'));

            // Show current step
            document.querySelector(`.step-container[data-step="${step}"]`).classList.remove('hidden');

            // Update progress indicators
            document.querySelectorAll('.progress-step').forEach((indicator, index) => {
                const circle = indicator.querySelector('.step-circle');
                const label = indicator.querySelector('.step-label');

                if (index + 1 < step) {
                    circle.className = 'w-10 h-10 rounded-full bg-accent text-black flex items-center justify-center font-bold mb-2 step-circle';
                    circle.innerHTML = '<i class="fas fa-check"></i>';
                    label.classList.remove('text-gray-400');
                    label.classList.add('text-accent');
                } else if (index + 1 === step) {
                    circle.className = 'w-10 h-10 rounded-full bg-accent text-black flex items-center justify-center font-bold mb-2 step-circle';
                    circle.textContent = index + 1;
                    label.classList.remove('text-gray-400');
                    label.classList.add('text-accent');
                } else {
                    circle.className = 'w-10 h-10 rounded-full bg-white/10 text-gray-400 flex items-center justify-center font-bold mb-2 step-circle';
                    circle.textContent = index + 1;
                    label.classList.remove('text-accent');
                    label.classList.add('text-gray-400');
                }
            });

            // Update progress bar
            const totalVisualSteps = 8;
            const progress = ((step - 1) / (totalVisualSteps - 1)) * 100;
            document.getElementById('progressFill').style.width = progress + '%';

            // Update buttons (use starting step for logged-in vs guest)
            const startingStep = isLoggedIn ? 2 : 1;
            document.getElementById('prevBtn').classList.toggle('hidden', step === startingStep);
            document.getElementById('nextBtn').classList.toggle('hidden', step === totalSteps);
            document.getElementById('submitBtn').classList.toggle('hidden', step !== totalSteps);

            // Load legal document on step 7
            if (step === 7) {
                loadLegalDocument();
            }

            // Scroll to top
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });

            // Update next button text for Step 1 if not logged in
            if (step === 1 && !isLoggedIn) {
                updateDualOtpButtons();
            }
        }

        async function loadLegalDocument() {
            const container = document.getElementById('legalDocumentContent');

            // Prevent multiple loads if already loaded
            if (container.dataset.loaded === 'true') return;

            try {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-spinner fa-spin text-3xl text-accent mb-3"></i>
                        <p class="text-gray-400">Memuat dokumen perjanjian...</p>
                    </div>
                `;

                // Fetch document with slug 'cwpa' - Match explicit route definition
                // Use dashed-case 'legal-document' to be safe on case-sensitive servers
                const response = await fetch(getSecureUrl('api/legal-document/cwpa'));

                if (!response.ok) {
                    throw new Error('Server responded with ' + response.status);
                }

                const result = await response.json();

                if (result.success && result.data) {
                    let content = result.data.content;

                    // --- Retrieve Data from Forum Inputs ---
                    // Step 1 Data
                    const name = document.querySelector('input[name="name"]')?.value || '....................';
                    const email = document.querySelector('input[name="email"]')?.value || '....................';
                    const whatsapp = document.querySelector('input[name="whatsapp"]')?.value || '....................';

                    // Step 2 Data (KTP, NPWP, Address)
                    const ktp = document.querySelector('input[name="ktp_number"]')?.value || '....................';
                    const npwp = document.querySelector('input[name="npwp_number"]')?.value || '-';
                    const address = document.querySelector('textarea[name="address"]')?.value || '....................';

                    // Date & Number Format
                    const now = new Date();
                    const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    const romanMonths = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

                    const day = now.getDate();
                    const monthVal = now.getMonth() + 1;
                    const monthName = months[now.getMonth()];
                    const romanMonth = romanMonths[now.getMonth()];
                    const year = now.getFullYear();
                    const fullDate = `${day} ${monthName} ${year}`;

                    const hariTanggal = new Intl.DateTimeFormat('id-ID', {
                        dateStyle: 'full'
                    }).format(now);

                    const randomNo = Math.floor(Math.random() * 1000).toString().padStart(3, '0');

                    // Specific Replacements for CWPA Document
                    // Format: {NAMA_USER}, {NO_KTP}, {NO_NPWP}, {ALAMAT}, {NO_TELP_USER}

                    // Helper regex builder for flexible brackets (handling HTML entities)
                    const createRegex = (key) => new RegExp(`(\\{|&#123;|&lbrace;)${key}(\\}|&#125;|&rbrace;)`, 'gi');

                    content = content
                        // Standard placeholders
                        .replace(createRegex('NAMA_USER'), name)
                        .replace(createRegex('NO_KTP'), ktp)
                        .replace(createRegex('NO_NPWP'), npwp)
                        .replace(createRegex('ALAMAT'), address)
                        .replace(createRegex('NO_TELP_USER'), whatsapp)

                        // Newly discovered placeholders from screenshot
                        .replace(createRegex('NO'), randomNo)
                        .replace(createRegex('BULAN'), romanMonth)
                        .replace(createRegex('TAHUN'), year)
                        .replace(createRegex('HARI_TANGGAL'), hariTanggal)

                        // Common variations (just in case)
                        .replace(createRegex('nama'), name)
                        .replace(createRegex('ktp'), ktp)
                        .replace(createRegex('nik'), ktp)
                        .replace(createRegex('npwp'), npwp)
                        .replace(createRegex('alamat'), address)
                        .replace(createRegex('whatsapp'), whatsapp)
                        .replace(createRegex('tanggal'), fullDate);

                    // Fallback for simple brackets
                    content = content
                        .replace(/\{NAMA_USER\}/g, name)
                        .replace(/\{NO_KTP\}/g, ktp)
                        .replace(/\{NO_NPWP\}/g, npwp)
                        .replace(/\{ALAMAT\}/g, address)
                        .replace(/\{NO_TELP_USER\}/g, whatsapp);


                    // Force Light Text Color & Remove Backgrounds
                    // 1. Remove specific color: black/000
                    content = content.replace(/color:\s*#000000;?/gi, '')
                        .replace(/color:\s*black;?/gi, '')
                        .replace(/color:\s*rgb\(0,\s*0,\s*0\);?/gi, '');

                    // 2. Remove specific background-color: white/#fff
                    content = content.replace(/background-color:\s*#ffffff;?/gi, '')
                        .replace(/background-color:\s*white;?/gi, '')
                        .replace(/background-color:\s*rgb\(255,\s*255,\s*255\);?/gi, '')
                        .replace(/background:\s*white;?/gi, '')
                        .replace(/background:\s*#ffffff;?/gi, '');

                    container.innerHTML = `
                        <div class="prose prose-invert prose-p:text-gray-300 prose-headings:text-white max-w-none p-4 [&_*]:text-gray-300">
                            <h3 class="text-xl font-bold text-center mb-6 uppercase border-b border-white/10 pb-4 text-white">${result.data.title}</h3>
                            <div class="text-gray-300 text-sm leading-relaxed text-justify space-y-4 undo-black-text">
                                ${content}
                            </div>
                        </div>
                        <style>
                            /* Force override styles */
                            .undo-black-text * {
                                color: #d1d5db !important; /* gray-300 text */
                                background-color: transparent !important; /* Force transparent bg */
                            }
                            .undo-black-text strong, .undo-black-text b, .undo-black-text h1, .undo-black-text h2, .undo-black-text h3 {
                                color: #ffffff !important;
                            }
                        </style>
                    `;
                    container.dataset.loaded = 'true';
                } else {
                    throw new Error(result.message || 'Dokumen tidak ditemukan');
                }

            } catch (error) {
                console.error('Legal Doc Error:', error);
                container.innerHTML = `
                    <div class="text-center py-8 text-red-500">
                        <i class="fas fa-exclamation-triangle text-3xl mb-3"></i>
                        <p class="mb-2">Gagal memuat dokumen perjanjian.</p>
                        <p class="text-xs text-gray-500 mb-4">${error.message}</p>
                        <button type="button" onclick="loadLegalDocument()" class="px-4 py-2 bg-white/10 rounded-lg hover:bg-white/20 transition text-white text-sm">
                            <i class="fas fa-sync-alt mr-2"></i> Coba Lagi
                        </button>
                    </div>
                `;
                // Reset loaded state so retry works
                container.dataset.loaded = 'false';
            }
        }

        function validateStep(step) {
            const currentStepElement = document.querySelector(`.step-container[data-step="${step}"]`);
            const inputs = currentStepElement.querySelectorAll('input[required], textarea[required], select[required]');

            const fieldMapping = {
                'has_ijazah_s1': 'Status Ijazah S1',
                'has_skck_card': 'Status SKCK',
                'has_felony_record': 'Status Terpidana',
                'has_bankruptcy_record': 'Status Pailit',
                'ktp_number': 'Nomor KTP',
                'npwp_number': 'Nomor NPWP',
                'address': 'Alamat',
                'password': 'Password',
                'password_confirm': 'Konfirmasi Password',
                'willing_to_make_skck': 'Kesediaan Membuat SKCK',
                'willing_to_make_felony_statement': 'Kesediaan Membuat Surat Pernyataan Terpidana',
                'cv': 'File CV',
                'ktp_file': 'File KTP',
                'npwp_file': 'File NPWP',
                'ijazah': 'File Ijazah',
                'skck': 'File SKCK',
                'agreement': 'Persetujuan Syarat & Ketentuan'
            };

            let isValid = true;
            let errorMsgs = [];

            inputs.forEach(input => {
                let currentValid = input.checkValidity();

                // Double check for radio buttons because browser validation can be tricky with hidden elements or dynamic required
                if (input.type === 'radio') {
                    const group = currentStepElement.querySelectorAll(`input[name="${input.name}"]`);
                    currentValid = Array.from(group).some(r => r.checked);
                }

                if (!currentValid) {
                    isValid = false;
                    const fieldName = input.name;
                    const readableLabel = fieldMapping[fieldName] ||
                        currentStepElement.querySelector(`label[for="${input.id}"]`)?.textContent.replace('*', '').trim() ||
                        fieldName;

                    if (!errorMsgs.includes(readableLabel)) {
                        errorMsgs.push(readableLabel);
                    }
                }

                // Custom validation for KTP number
                if (input.name === 'ktp_number' && input.value.trim() !== '') {
                    const ktpValue = input.value.trim();
                    if (ktpValue.length !== 16 || !/^\d+$/.test(ktpValue)) {
                        isValid = false;
                        errorMsgs.push('No. KTP harus 16 digit angka');
                    }
                }

                // Custom validation for Address (Step 2)
                if (input.name === 'address') {
                    if (input.value.trim().length < 10) {
                        isValid = false;
                        errorMsgs.push('Alamat harus minimal 10 karakter');
                        if (addressError) addressError.classList.remove('hidden');
                        input.classList.add('border-red-500/50');
                    }
                }
            });

            // Special validation for Step 1 (Password)
            if (step === 1) {
                const password = currentStepElement.querySelector('input[name="password"]');
                const confirm = currentStepElement.querySelector('input[name="password_confirm"]');

                if (password && confirm) {
                    if (password.value.length < 6) {
                        showError('Password minimal 6 karakter!');
                        return false;
                    }
                    if (password.value !== confirm.value) {
                        showError('Password dan Konfirmasi Password tidak sama!');
                        return false;
                    }
                }
            }

            // Special validation for Step 3 (Social Media)
            if (step === 3) {
                const socialInputs = ['instagram', 'facebook', 'tiktok', 'linkedin', 'youtube'];
                const hasAnySocial = socialInputs.some(name => {
                    const selector = `input[name="${name}"]`;
                    const field = currentStepElement.querySelector(selector);
                    return field && field.value.trim() !== '';
                });

                if (!hasAnySocial) {
                    showError('Isi minimal satu akun media sosial');
                    return false;
                }
            }

            // Special validation for Step 4 (Specialties)
            if (step === 4) {
                const specialties = currentStepElement.querySelectorAll('input[name="specialties[]"]:checked');
                if (specialties.length === 0) {
                    showError('Pilih minimal satu spesialisasi');
                    return false;
                }
            }

            // Special validation for Step 7 (Agreement)
            if (step === 7) {
                const agreement = currentStepElement.querySelector('#agreement');
                if (agreement && !agreement.checked) {
                    showError('Sertakan persetujuan Anda dengan mencentang kotak yang tersedia');
                    return false;
                }
            }

            // Special validation for Step 8 (Transfer Proof)
            if (step === 8) {
                const proof = currentStepElement.querySelector('#transfer_proof');
                if (proof && (!proof.files || proof.files.length === 0)) {
                    showError('Silakan upload bukti transfer Anda');
                    return false;
                }
            }

            if (!isValid) {
                showError('Mohon lengkapi semua field yang diperlukan: ' + errorMsgs.join(', '));
            }

            return isValid;
        }

        function showError(message) {
            const errorDiv = document.getElementById('errorMessage');
            errorDiv.innerHTML = `<div class="bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl mb-6 shadow-lg">
                <div class="flex items-center gap-3">
                    <i class="fas fa-exclamation-circle text-xl"></i>
                    <div>${message}</div>
                </div>
            </div>`;
            errorDiv.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }

        // Form submission
        document.getElementById('cwpaForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            // Final validation of all relevant steps to ensure everything is filled
            // This prevents hidden steps from blocking submission silently via browser validation
            const startCheck = isLoggedIn ? 2 : 1;
            for (let i = startCheck; i <= totalSteps; i++) {
                if (!validateStep(i)) {
                    showStep(i);
                    return;
                }
            }

            const loadingOverlay = document.getElementById('loadingOverlay');
            const loadingTextDiv = loadingOverlay.querySelector('.text-center');
            const originalContent = loadingTextDiv ? loadingTextDiv.innerHTML : '';

            loadingOverlay.classList.remove('hidden');
            loadingOverlay.classList.add('flex');

            const formData = new FormData(this);
            let shouldHideLoading = true;

            try {
                const response = await fetch(getSecureUrl('daftar-cwpa/submit-multi-step'), {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const result = await response.json();

                if (result.success) {
                    shouldHideLoading = false;

                    if (loadingTextDiv) {
                        loadingTextDiv.innerHTML = `
                            <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce">
                                <i class="fas fa-check text-black text-4xl"></i>
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-2">Pendaftaran Berhasil!</h3>
                            <p class="text-gray-400 mb-6">Data Anda telah diterima. Mengalihkan ke halaman pembayaran...</p>
                            <a href="${result.redirect_url}" class="text-accent underline text-sm mt-4 inline-block font-semibold">Klik di sini jika tidak beralih otomatis</a>
                        `;
                    }

                    if (document.activeElement) document.activeElement.blur();

                    setTimeout(() => {
                        window.location.assign(result.redirect_url);
                    }, 2000);
                } else {
                    let errorMessage = result.message || 'Terjadi kesalahan saat memproses data';

                    if (result.errors) {
                        let errorList = '<ul class="list-disc pl-5 mt-2 text-left text-sm">';
                        for (const [field, msg] of Object.entries(result.errors)) {
                            errorList += `<li>${msg}</li>`;
                        }
                        errorList += '</ul>';
                        errorMessage += errorList;
                    }

                    showError(errorMessage);
                }
            } catch (error) {
                let msg = 'Tidak dapat terhubung ke server. Silakan coba lagi.';
                if (error.message && error.message.includes('404')) {
                    msg = 'Endpoint submit tidak ditemukan (404). Hubungi admin.';
                } else if (error.message && error.message.includes('413')) {
                    msg = 'Ukuran file terlalu besar untuk server. Mohon perkecil ukuran file upload.';
                }
                showError(msg);
            } finally {
                if (shouldHideLoading) {
                    loadingOverlay.classList.remove('flex');
                    loadingOverlay.classList.add('hidden');
                    if (loadingTextDiv && originalContent) loadingTextDiv.innerHTML = originalContent;
                }
            }
        });


        // Initialize file size validation (Client Side)
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            input.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const fileSize = this.files[0].size; // in bytes
                    // Revert limit to 5MB as requested
                    const maxSizeMB = 5;
                    const maxSizeBytes = maxSizeMB * 1024 * 1024;

                    if (fileSize > maxSizeBytes) {
                        // Reset input
                        this.value = '';

                        // Show Error Modal
                        showError(`File terlalu besar! Ukuran file maksimal adalah ${maxSizeMB}MB. File Anda: ${(fileSize / (1024 * 1024)).toFixed(2)}MB. Silakan kompres file Anda terlebih dahulu.`);
                    }
                }
            });
        });

        // OTP Logic - Integrated Dual Verification
        window.emailVerified = isLoggedIn;
        window.waVerified = false;

        function showDualOtpModal() {
            document.getElementById('emailDisplayTarget').textContent = document.getElementById('emailInput').value;
            document.getElementById('waDisplayTarget').textContent = document.getElementById('waInput').value;
            document.getElementById('dualOtpModal').classList.remove('hidden');
            document.getElementById('dualOtpModal').classList.add('flex');

            // Auto restart if already partially verified or state reset
            updateDualOtpButtons();
        }

        function closeDualOtpModal() {
            document.getElementById('dualOtpModal').classList.add('hidden');
            document.getElementById('dualOtpModal').classList.remove('flex');
        }

        function updateDualOtpButtons() {
            const nextBtn = document.getElementById('nextBtn');
            if (currentStep === 1 && !isLoggedIn && (!window.emailVerified || !window.waVerified)) {
                nextBtn.innerHTML = 'Verifikasi OTP <i class="fas fa-shield-alt ml-2"></i>';
            } else {
                nextBtn.innerHTML = 'Selanjutnya <i class="fas fa-arrow-right ml-2"></i>';
            }

            // Modal Finnish Button
            document.getElementById('btnFinishOtp').disabled = !(window.emailVerified && window.waVerified);
        }

        async function sendDualOtp(channel) {
            const email = document.getElementById('emailInput').value;
            const phone = document.getElementById('waInput').value;
            const name = document.querySelector('input[name="name"]').value;

            const btn = (channel === 'email') ? document.getElementById('btnSendEmail') : document.getElementById('btnSendWa');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            btn.disabled = true;

            try {
                const response = await fetch(getSecureUrl('checkout/send-otp'), {
                    method: 'POST',
                    body: JSON.stringify({
                        email,
                        phone,
                        name,
                        channel
                    }),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
                    }
                });

                const result = await response.json();
                if (result.success) {
                    if (channel === 'email') {
                        document.getElementById('emailActionArea').classList.add('hidden');
                        document.getElementById('emailInputArea').classList.remove('hidden');
                        startDualTimer('email');
                    } else {
                        document.getElementById('waActionArea').classList.add('hidden');
                        document.getElementById('waInputArea').classList.remove('hidden');
                        startDualTimer('wa');
                    }

                    if (result.otp_dev) {
                        const digits = result.otp_dev.split('');
                        const inputs = document.querySelectorAll(`.${channel === 'whatsapp' ? 'wa' : channel}-otp-digit`);
                        digits.forEach((d, i) => inputs[i] ? inputs[i].value = d : null);
                    }
                } else {
                    alert(result.message);
                }
            } catch (e) {
                // Error handling handled by UI
            } finally {
                btn.innerHTML = (channel === 'email') ? 'Kirim Kode OTP' : 'Kirim Kode OTP';
                btn.disabled = false;
            }
        }

        async function verifyDualOtp(channel) {
            let otp = '';
            const chClass = (channel === 'whatsapp') ? 'wa' : channel;
            document.querySelectorAll(`.${chClass}-otp-digit`).forEach(i => otp += i.value);
            const target = (channel === 'email') ? document.getElementById('emailInput').value : document.getElementById('waInput').value;

            if (otp.length < 6) return alert('Lengkapi digit OTP');

            try {
                const response = await fetch(getSecureUrl('checkout/verify-single-otp'), {
                    method: 'POST',
                    body: JSON.stringify({
                        channel: channel,
                        email: document.getElementById('emailInput').value,
                        phone: document.getElementById('waInput').value,
                        otp: otp
                    }),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
                    }
                });

                const result = await response.json();
                if (result.success) {
                    if (channel === 'email') {
                        window.emailVerified = true;
                        document.getElementById('emailInputArea').classList.add('hidden');
                        document.getElementById('emailStatusBadge').innerHTML = '<i class="fas fa-check text-green-500 mr-1"></i> Terverifikasi';
                        document.getElementById('emailStatusBadge').className = 'text-xs font-bold text-green-500 px-3 py-1 bg-green-500/10 rounded-full';
                        document.getElementById('emailVerificationCard').classList.add('border-green-500/50');
                    } else {
                        window.waVerified = true;
                        document.getElementById('waInputArea').classList.add('hidden');
                        document.getElementById('waStatusBadge').innerHTML = '<i class="fas fa-check text-green-500 mr-1"></i> Terverifikasi';
                        document.getElementById('waStatusBadge').className = 'text-xs font-bold text-green-500 px-3 py-1 bg-green-500/10 rounded-full';
                        document.getElementById('waVerificationCard').classList.add('border-green-500/50');
                    }
                    updateDualOtpButtons();
                } else {
                    alert(result.message);
                }
            } catch (e) {
                console.error(e);
            }
        }

        function startDualTimer(channel) {
            let s = 60;
            const el = document.querySelector(`.${channel}-seconds`);
            const itv = setInterval(() => {
                s--;
                if (el) el.textContent = s;
                if (s <= 0) {
                    clearInterval(itv);
                    // Show Resend logic here if needed
                }
            }, 1000);
        }

        function finishDualVerification() {
            closeDualOtpModal();
            updateDualOtpButtons();
            // Automatically move to next step since verification is the main hurdle
            document.getElementById('nextBtn').click();
        }

        // Initialize button text
        updateDualOtpButtons();

        // OTP inputs auto-focus logic for dual modal
        ['wa', 'email'].forEach(ch => {
            const inputs = document.querySelectorAll(`.${ch}-otp-digit`);
            inputs.forEach((input, index) => {
                input.addEventListener('input', (e) => {
                    if (e.data && index < inputs.length - 1) inputs[index + 1].focus();
                });
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !input.value && index > 0) inputs[index - 1].focus();
                });
            });
        });

        // Reset if data changes
        ['emailInput', 'waInput'].forEach(id => {
            document.getElementById(id)?.addEventListener('input', () => {
                if (!isLoggedIn) {
                    window.emailVerified = false;
                    window.waVerified = true;
                    // Reset modal state
                    document.getElementById('waStatusBadge').className = 'text-xs font-bold text-gray-500 px-3 py-1 bg-white/5 rounded-full';
                    document.getElementById('waStatusBadge').textContent = 'Belum Verifikasi';
                    document.getElementById('waActionArea').classList.remove('hidden');
                    document.getElementById('waInputArea').classList.add('hidden');

                    document.getElementById('emailStatusBadge').className = 'text-xs font-bold text-gray-500 px-3 py-1 bg-white/5 rounded-full';
                    document.getElementById('emailStatusBadge').textContent = 'Belum Verifikasi';
                    document.getElementById('emailActionArea').classList.remove('hidden');
                    document.getElementById('emailInputArea').classList.add('hidden');

                    updateDualOtpButtons();
                }
            });
        });

        // ============================================
        // SECURE EVENT HANDLING - Remove inline handlers
        // ============================================

        // Toggle password visibility (replace onclick="togglePassword(...)")
        document.querySelectorAll('.toggle-password-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.dataset.target;
                const inputEl = document.getElementById(targetId);
                if (inputEl) {
                    const isPassword = inputEl.type === 'password';
                    inputEl.type = isPassword ? 'text' : 'password';
                    this.querySelector('i').className = isPassword ? 'fas fa-eye-slash' : 'fas fa-eye';
                }
            });
        });

        // Form field event listeners (replace oninput/onchange)
        document.querySelectorAll('input[name="password"], input[name="password_confirm"]').forEach(input => {
            input.addEventListener('input', function() {
                validatePasswordStatus();
            });
        });

        // Disqualification check (replace onchange="checkDisqualification()")
        document.querySelectorAll('.require-check-disqualification').forEach(input => {
            input.addEventListener('change', function() {
                checkDisqualification();
            });
        });

        // SKCK willingness toggle (replace onchange="toggleSkckWillingness()")
        document.querySelectorAll('.require-toggle-skck-willingness').forEach(input => {
            input.addEventListener('change', function() {
                toggleSkckWillingness();
            });
        });

        // Felony willingness toggle (replace onchange="toggleFelonyWillingness()")
        document.querySelectorAll('.require-toggle-felony-willingness').forEach(input => {
            input.addEventListener('change', function() {
                toggleFelonyWillingness();
            });
        });

        // OTP send buttons (replace onclick="sendDualOtp(...)")
        document.querySelectorAll('.send-otp-btn').forEach(btn => {
            btn.addEventListener('click', async function(e) {
                e.preventDefault();
                const channel = this.dataset.channel;
                if (channel) {
                    await sendDualOtp(channel);
                }
            });
        });

        // OTP verify buttons (replace onclick="verifyDualOtp(...)")
        document.querySelectorAll('.verify-otp-btn').forEach(btn => {
            btn.addEventListener('click', async function(e) {
                e.preventDefault();
                const channel = this.dataset.channel;
                if (channel) {
                    await verifyDualOtp(channel);
                }
            });
        });

        // Close OTP modal button (replace onclick="closeDualOtpModal()")
        document.querySelectorAll('.close-otp-modal-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                closeDualOtpModal();
            });
        });

        // ============================================
        // FETCH ERROR HANDLING for rate limiting
        // ============================================

        // Wrap fetch functions to handle 429 (rate limit) responses
        const originalFetch = window.fetch;
        window.fetchWithErrorHandling = async function(url, options = {}) {
            try {
                const response = await fetch(url, options);
                
                // Handle rate limiting (429 Too Many Requests)
                if (response.status === 429) {
                    const data = await response.json().catch(() => ({}));
                    
                    // Show friendly error message
                    const errorDiv = document.getElementById('errorMessage') || document.createElement('div');
                    errorDiv.id = 'errorMessage';
                    errorDiv.innerHTML = `
                        <div class="bg-red-500/10 border border-red-500/50 text-red-400 p-4 rounded-xl mb-6 flex items-center gap-3">
                            <i class="fas fa-exclamation-triangle"></i>
                            <div>
                                <strong>Terlalu Banyak Percobaan</strong>
                                <p class="text-sm mt-1">${data.message || 'Anda telah melebihi batas jumlah percobaan. Silakan coba lagi dalam beberapa menit.'}</p>
                            </div>
                        </div>
                    `;
                    
                    // Insert at top of form
                    const formContainer = document.querySelector('.bg-\\[\\#111\\]');
                    if (formContainer && !document.getElementById('errorMessage')) {
                        formContainer.insertBefore(errorDiv, formContainer.firstChild);
                    }
                    
                    return response;
                }
                
                return response;
            } catch (error) {
                console.error('Fetch error:', error);
                throw error;
            }
        };

        // Initialize at appropriate step (Step 1 for guests, Step 2 for logged-in users)
        showStep(currentStep);
    </script>
</body>

</html>