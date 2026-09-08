<?= $this->extend('cwpa/layouts/main') ?>
<?= $this->section('content') ?>

<!-- Full Page KYC Form -->
<div class="min-h-screen flex flex-col bg-black">
    <!-- Header -->
    <div class="sticky top-0 z-40 bg-black/95 backdrop-blur-lg border-b border-white/10 px-4 py-4">
        <div class="flex items-center gap-4 max-w-lg mx-auto w-full">
            <a href="<?= base_url('cwpa/dashboard') ?>" class="text-white hover:text-accent transition">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-lg font-bold">Verifikasi Identitas CWPA</h1>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto pb-24">
        <form id="kycForm" action="<?= base_url('cwpa/dashboard/kyc/submit') ?>" method="POST" enctype="multipart/form-data" class="max-w-lg mx-auto w-full px-4 py-6">
            <?= csrf_field() ?>
            <!-- Hidden file input container -->
            <div id="fileInputContainer" style="position: absolute; left: -9999px; opacity: 0;">
                <input type="file" name="ktp_photo" id="ktp_photo_hidden">
            </div>

            <!-- Step 1: Informasi Data Diri -->
            <div id="step1" class="space-y-6">

                <!-- Personal Info Card -->
                <div class="bg-[#111] border border-white/10 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-6">
                        <i class="fas fa-user-edit text-accent text-lg"></i>
                        <h2 class="font-bold text-lg text-white">Informasi Data Diri</h2>
                    </div>

                    <div class="space-y-4">
                        <!-- Full Name -->
                        <div class="relative">
                            <input type="text" name="full_name" id="full_name" required value="<?= esc($userData['full_name'] ?? $user['name']) ?>" placeholder="Nama Lengkap (Sesuai KTP)"
                                class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white placeholder-gray-500 transition-all duration-300">
                            <span class="validation-icon absolute right-3 top-1/2 -translate-y-1/2 opacity-0 transition-all duration-300"></span>
                        </div>

                        <!-- NIK -->
                        <div class="relative">
                            <input type="text" name="nik" id="nik" maxlength="16" pattern="[0-9]{16}" required value="<?= esc($userData['nik'] ?? '') ?>" placeholder="NIK 16 Digit"
                                class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white placeholder-gray-500 transition-all duration-300"
                                oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                            <span class="validation-icon absolute right-3 top-1/2 -translate-y-1/2 opacity-0 transition-all duration-300"></span>
                        </div>
                        <p id="nikError" class="text-red-500 text-xs mt-1 hidden">NIK harus 16 digit angka</p>

                        <!-- NPWP -->
                        <div class="relative">
                            <input type="text" name="npwp" id="npwp" maxlength="20" required value="<?= esc($userData['npwp'] ?? '') ?>" placeholder="NPWP"
                                class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white placeholder-gray-500 transition-all duration-300">
                            <span class="validation-icon absolute right-3 top-1/2 -translate-y-1/2 opacity-0 transition-all duration-300"></span>
                        </div>

                        <!-- Birth Place -->
                        <div class="relative">
                            <input type="text" name="birth_place" id="birth_place" required value="<?= esc($userData['birth_place'] ?? '') ?>" placeholder="Tempat Lahir"
                                class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white placeholder-gray-500 transition-all duration-300">
                            <span class="validation-icon absolute right-3 top-1/2 -translate-y-1/2 opacity-0 transition-all duration-300"></span>
                        </div>

                        <!-- Birth Date -->
                        <div class="relative">
                            <input type="date" name="birth_date" id="birth_date" required value="<?= esc($userData['birth_date'] ?? '') ?>" placeholder="dd/mm/yyyy"
                                class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white placeholder-gray-500 transition-all duration-300 cursor-pointer text-left"
                                style="color-scheme: dark;">
                            <span class="validation-icon absolute right-3 top-1/2 -translate-y-1/2 opacity-0 transition-all duration-300"></span>
                        </div>

                        <!-- Gender -->
                        <div class="relative">
                            <select name="gender" id="gender" required class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white transition-all duration-300 appearance-none">
                                <option value="" disabled selected class="text-gray-500">Jenis Kelamin</option>
                                <option value="male" <?= ($userData['gender'] ?? '') == 'male' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="female" <?= ($userData['gender'] ?? '') == 'female' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none text-xs"></i>
                            <span class="validation-icon absolute right-8 top-1/2 -translate-y-1/2 opacity-0 transition-all duration-300"></span>
                        </div>

                        <!-- Phone -->
                        <div class="relative">
                            <input type="tel" name="phone" id="phone" required value="<?= esc($userData['phone'] ?? $user['phone'] ?? '') ?>" placeholder="Nomor telepon/Whatsapp"
                                class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white placeholder-gray-500 transition-all duration-300">
                            <span class="validation-icon absolute right-3 top-1/2 -translate-y-1/2 opacity-0 transition-all duration-300"></span>
                        </div>

                        <!-- Address -->
                        <div class="relative">
                            <textarea name="address" id="address" rows="3" required placeholder="Alamat Lengkap"
                                class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white placeholder-gray-500 resize-none transition-all duration-300"><?= esc($userData['address'] ?? '') ?></textarea>
                            <span class="validation-icon absolute right-3 top-4 opacity-0 transition-all duration-300"></span>
                        </div>

                        <!-- Province & City -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="relative">
                                <select name="province" id="province" required onchange="loadRegencies(this.value)"
                                    class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white appearance-none transition-all duration-300">
                                    <option value="" disabled selected>Provinsi</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none text-[10px]"></i>
                            </div>
                            <div class="relative">
                                <select name="city" id="city" required onchange="loadDistricts(this.value)"
                                    class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white appearance-none transition-all duration-300">
                                    <option value="" disabled selected>Kota/Kab</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none text-[10px]"></i>
                            </div>
                        </div>

                        <!-- Kecamatan & Desa -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="relative">
                                <select name="district" id="district" required onchange="loadVillages(this.value)"
                                    class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white appearance-none transition-all duration-300">
                                    <option value="" disabled selected>Kecamatan</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none text-[10px]"></i>
                            </div>
                            <div class="relative">
                                <select name="village" id="village" required
                                    class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white appearance-none transition-all duration-300">
                                    <option value="" disabled selected>Kelurahan/Desa</option>
                                </select>
                                <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none text-[10px]"></i>
                            </div>
                        </div>
                        <!-- Postal Code -->
                        <div class="relative">
                            <input type="text" name="postal_code" id="postal_code" required value="<?= esc($userData['postal_code'] ?? '') ?>" placeholder="Kode Pos"
                                class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white placeholder-gray-500 transition-all duration-300">
                        </div>

                        <!-- Profession & Purpose -->
                        <div class="relative">
                            <select name="profession" id="profession" required class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white transition-all duration-300 appearance-none">
                                <option value="" disabled selected>Pekerjaan</option>
                                <option value="pelajar" <?= ($userData['profession'] ?? '') == 'pelajar' ? 'selected' : '' ?>>Pelajar/Mahasiswa</option>
                                <option value="swasta" <?= ($userData['profession'] ?? '') == 'swasta' ? 'selected' : '' ?>>Karyawan Swasta</option>
                                <option value="pns" <?= ($userData['profession'] ?? '') == 'pns' ? 'selected' : '' ?>>Pegawai Negeri Sipil</option>
                                <option value="wiraswasta" <?= ($userData['profession'] ?? '') == 'wiraswasta' ? 'selected' : '' ?>>Wiraswasta</option>
                                <option value="profesional" <?= ($userData['profession'] ?? '') == 'profesional' ? 'selected' : '' ?>>Profesional</option>
                                <option value="lainnya" <?= ($userData['profession'] ?? '') == 'lainnya' ? 'selected' : '' ?>>Lainnya</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none text-xs"></i>
                        </div>
                        <div class="relative">
                            <select name="registration_purpose" id="registration_purpose" required class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white transition-all duration-300 appearance-none">
                                <option value="" disabled selected>Tujuan Pembukaan Akun</option>
                                <option value="pelatihan" <?= ($userData['registration_purpose'] ?? '') == 'pelatihan' ? 'selected' : '' ?>>Pelatihan</option>
                                <option value="pendampingan" <?= ($userData['registration_purpose'] ?? '') == 'pendampingan' ? 'selected' : '' ?>>Pendampingan</option>
                                <option value="lainnya" <?= ($userData['registration_purpose'] ?? '') == 'lainnya' ? 'selected' : '' ?>>Lainnya</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none text-xs"></i>
                        </div>

                        <!-- KTP Photo -->
                        <div class="relative">
                            <div onclick="document.getElementById('ktp_photo_display').click()" class="w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 border-dashed rounded-xl cursor-pointer hover:border-accent transition-all flex items-center justify-between group relative overflow-hidden">
                                <span id="ktp_upload_text" class="text-sm text-gray-500 group-hover:text-accent transition">Upload Foto KTP</span>
                                <i id="ktp_upload_icon" class="fas fa-camera text-gray-500 group-hover:text-accent transition"></i>
                            </div>
                            <input type="file" id="ktp_photo_display" accept=".jpg,.jpeg,.png" required onchange="handleKtpUpload(this)" class="hidden kyc-input">
                            <span id="ktp_filename" class="text-xs text-accent mt-1 hidden">File terupload</span>
                        </div>
                        <div id="ktpPreviewContainer" style="<?= !empty($userData['ktp_photo']) ? 'display:block;' : 'display:none;' ?>" class="mt-2 text-center">
                            <img id="ktpPreview" src="<?= !empty($userData['ktp_photo']) ? base_url($userData['ktp_photo']) : '' ?>" class="max-h-48 rounded-lg border border-white/20 mx-auto">
                        </div>
                    </div>
                </div>

                <!-- Bank Info Card -->
                <div class="bg-[#111] border border-white/10 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-6">
                        <i class="fas fa-university text-accent text-lg"></i>
                        <h2 class="font-bold text-lg text-white">Data Rekening</h2>
                    </div>
                    <div class="space-y-4">
                        <div class="relative">
                            <input type="text" name="bank_name" id="bank_name" required value="<?= esc($userData['bank_name'] ?? '') ?>" placeholder="Nama Bank"
                                class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white placeholder-gray-500 transition-all duration-300">
                        </div>
                        <div class="relative">
                            <input type="text" name="account_number" id="account_number" required value="<?= esc($userData['account_number'] ?? '') ?>" placeholder="Nomor Rekening"
                                class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white placeholder-gray-500 transition-all duration-300">
                        </div>
                        <div class="relative">
                            <input type="text" name="account_name" id="account_name" required value="<?= esc($userData['account_name'] ?? '') ?>" placeholder="Nama Pemilik Rekening"
                                class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white placeholder-gray-500 transition-all duration-300">
                        </div>
                        <input type="hidden" name="bank_branch" value="-">
                    </div>
                </div>

                <!-- Trading Experience -->
                <div class="bg-[#111] border border-white/10 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-6">
                        <i class="fas fa-chart-line text-accent text-lg"></i>
                        <h2 class="font-bold text-lg text-white">Pengalaman</h2>
                    </div>
                    <div class="space-y-4">
                        <div class="relative">
                            <select name="experience" id="experience" required class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white transition-all duration-300 appearance-none">
                                <option value="" disabled selected>Pengalaman Trading</option>
                                <option value="none" <?= ($userData['experience'] ?? '') == 'none' ? 'selected' : '' ?>>Belum pernah</option>
                                <option value="less_1_year" <?= ($userData['experience'] ?? '') == 'less_1_year' ? 'selected' : '' ?>>Kurang dari 1 tahun</option>
                                <option value="1_3_years" <?= ($userData['experience'] ?? '') == '1_3_years' ? 'selected' : '' ?>>1-3 tahun</option>
                                <option value="more_3_years" <?= ($userData['experience'] ?? '') == 'more_3_years' ? 'selected' : '' ?>>Lebih dari 3 tahun</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none text-xs"></i>
                        </div>
                        <div class="relative">
                            <select name="trading_goal" id="trading_goal" required class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white transition-all duration-300 appearance-none">
                                <option value="" disabled selected>Tujuan Trading</option>
                                <option value="hedging" <?= ($userData['trading_goal'] ?? '') == 'hedging' ? 'selected' : '' ?>>Hedging</option>
                                <option value="spekulasi" <?= ($userData['trading_goal'] ?? '') == 'spekulasi' ? 'selected' : '' ?>>Spekulasi</option>
                                <option value="investasi" <?= ($userData['trading_goal'] ?? '') == 'investasi' ? 'selected' : '' ?>>Investasi</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none text-xs"></i>
                        </div>
                        <div class="relative">
                            <select name="monthly_income" id="monthly_income" required class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white transition-all duration-300 appearance-none">
                                <option value="" disabled selected>Pendapatan Per Bulan</option>
                                <option value="less_5m" <?= ($userData['monthly_income'] ?? '') == 'less_5m' ? 'selected' : '' ?>>< 5 Juta</option>
                                <option value="5m_15m" <?= ($userData['monthly_income'] ?? '') == '5m_15m' ? 'selected' : '' ?>>5 - 15 Juta</option>
                                <option value="15m_50m" <?= ($userData['monthly_income'] ?? '') == '15m_50m' ? 'selected' : '' ?>>15 - 50 Juta</option>
                                <option value="more_50m" <?= ($userData['monthly_income'] ?? '') == 'more_50m' ? 'selected' : '' ?>>> 50 Juta</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none text-xs"></i>
                        </div>
                        <div class="relative">
                            <input type="text" id="risk_profile_display" readonly value="<?= esc($userData['risk_profile'] ?? '') ?>" placeholder="Profil Risiko (Terisi Otomatis)"
                                class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-gray-400 cursor-not-allowed">
                            <input type="hidden" name="risk_profile" id="risk_profile" value="<?= esc($userData['risk_profile'] ?? 'moderate') ?>">
                        </div>
                    </div>
                </div>

                <!-- Next Button -->
                <div class="mt-8 pt-4">
                    <button type="button" onclick="goToStep2()" class="w-full py-4 bg-accent text-black font-bold rounded-2xl hover:bg-white transition shadow-lg shadow-accent/20">
                        Lanjutkan
                    </button>
                </div>
            </div>

            <!-- Step 2: Dokumen Pernyataan -->
            <div id="step2" class="space-y-6 hidden">
                <div class="bg-[#111] border border-white/10 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-6">
                        <i class="fas fa-file-contract text-accent text-lg"></i>
                        <h2 class="font-bold text-lg text-white">Dokumen Pernyataan</h2>
                    </div>

                    <div class="space-y-6">
                        <!-- Profil Perusahaan -->
                        <div class="flex items-start gap-4">
                            <div class="relative top-1">
                                <input type="checkbox" name="agree_profil" id="agree_profil" value="1" disabled
                                    class="peer appearance-none w-6 h-6 border-2 border-white/20 rounded-lg bg-[#1a1a1a] checked:bg-accent checked:border-accent transition cursor-pointer">
                                <i class="fas fa-check absolute top-1 left-1 text-black text-xs opacity-0 peer-checked:opacity-100 pointer-events-none"></i>
                            </div>
                            <div class="flex-1">
                                <button type="button" onclick="openDoc('profil')" class="text-left font-bold text-accent hover:text-white text-sm hover:underline">Pernyataan Membaca Profil Perusahaan</button>
                                <p class="text-gray-400 text-xs mt-1">Saya menyatakan telah membaca, memahami, dan menyetujui peraturan perusahaan.</p>
                            </div>
                        </div>

                        <!-- Risiko -->
                        <div class="flex items-start gap-4">
                            <div class="relative top-1">
                                <input type="checkbox" name="agree_resiko" id="agree_resiko" value="1" disabled
                                    class="peer appearance-none w-6 h-6 border-2 border-white/20 rounded-lg bg-[#1a1a1a] checked:bg-accent checked:border-accent transition cursor-pointer">
                                <i class="fas fa-check absolute top-1 left-1 text-black text-xs opacity-0 peer-checked:opacity-100 pointer-events-none"></i>
                            </div>
                            <div class="flex-1">
                                <button type="button" onclick="openDoc('resiko')" class="text-left font-bold text-accent hover:text-white text-sm hover:underline">Pernyataan Pemberitahuan Risiko</button>
                                <p class="text-gray-400 text-xs mt-1">Saya memahami dan menerima risiko yang terkait dengan penggunaan layanan ini.</p>
                            </div>
                        </div>

                        <!-- SnK -->
                        <div class="flex items-start gap-4">
                            <div class="relative top-1">
                                <input type="checkbox" name="agree_snk" id="agree_snk" value="1" disabled
                                    class="peer appearance-none w-6 h-6 border-2 border-white/20 rounded-lg bg-[#1a1a1a] checked:bg-accent checked:border-accent transition cursor-pointer">
                                <i class="fas fa-check absolute top-1 left-1 text-black text-xs opacity-0 peer-checked:opacity-100 pointer-events-none"></i>
                            </div>
                            <div class="flex-1">
                                <button type="button" onclick="openDoc('snk')" class="text-left font-bold text-accent hover:text-white text-sm hover:underline">Pernyataan Syarat dan Ketentuan</button>
                                <p class="text-gray-400 text-xs mt-1">Saya menyetujui syarat dan ketentuan yang berlaku.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 pt-4 space-y-3">
                    <button type="button" onclick="submitKycForm()" id="submitBtn" class="w-full py-4 bg-accent text-black font-bold rounded-2xl hover:bg-white transition shadow-lg shadow-accent/20">Kirim</button>
                    <button type="button" onclick="goToStep1()" class="w-full py-4 border border-white/20 text-white font-bold rounded-2xl hover:bg-white/5 transition">Kembali</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modals & Toasts (Identical to user/kyc.php) -->
<div id="toastContainer" class="fixed top-6 right-6 z-[100] space-y-3"></div>
<div id="docModal" class="fixed inset-0 bg-black/95 z-[90] hidden items-center justify-center p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl w-full max-w-2xl max-h-[85vh] overflow-hidden flex flex-col">
        <div class="p-4 border-b border-white/10 flex items-center justify-between">
            <h3 id="docTitle" class="font-bold text-white">Dokumen</h3>
            <button onclick="closeDoc()" class="text-gray-400"><i class="fas fa-times"></i></button>
        </div>
        <div id="docContent" class="p-6 overflow-y-auto flex-1 text-xs text-gray-300 whitespace-pre-wrap leading-relaxed"></div>
        <div class="p-4 border-t border-white/10">
            <button type="button" onclick="acceptDoc()" class="w-full py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">Saya Setuju</button>
        </div>
    </div>
</div>

<script>
    // Logic & Validation Cloned from user/kyc.php
    let userData = {};

    function goToStep2() {
        if (!validateStep1()) return;
        document.getElementById('step1').classList.add('hidden');
        document.getElementById('step2').classList.remove('hidden');
        window.scrollTo(0, 0);
    }

    function goToStep1() {
        document.getElementById('step2').classList.add('hidden');
        document.getElementById('step1').classList.remove('hidden');
    }

    function validateStep1() {
        const requiredIds = ['full_name', 'nik', 'npwp', 'birth_place', 'birth_date', 'gender', 'phone', 'address', 'province', 'city', 'district', 'village', 'postal_code', 'profession', 'registration_purpose', 'bank_name', 'account_number', 'account_name', 'experience', 'trading_goal', 'monthly_income'];
        
        const hiddenFile = document.getElementById('ktp_photo_hidden');
        if (hiddenFile.files.length === 0 && !document.getElementById('ktpPreview').src.includes('uploads')) {
            showToast('Foto KTP wajib diupload', 'error');
            return false;
        }

        let valid = true;
        requiredIds.forEach(id => {
            const el = document.getElementById(id);
            if (!el || !el.value) {
                if(el) el.classList.add('border-red-500');
                valid = false;
            } else {
                el.classList.remove('border-red-500');
            }
        });

        if (!valid) showToast('Mohon lengkapi semua data', 'error');
        return valid;
    }

    function handleKtpUpload(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            reader.onload = (e) => {
                document.getElementById('ktpPreview').src = e.target.result;
                document.getElementById('ktpPreviewContainer').style.display = 'block';
            };
            reader.readAsDataURL(file);

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            document.getElementById('ktp_photo_hidden').files = dataTransfer.files;
            document.getElementById('ktp_filename').classList.remove('hidden');
        }
    }

    // Docs Logic
    const docs = {
        profil: { title: 'Profil Perusahaan', content: `PT. Alma Indonesia Raya | Almai\n\nSaya telah membaca, memahami, dan setuju terhadap profil perusahaan.` },
        resiko: { title: 'Pemberitahuan Risiko', content: `DOKUMEN PEMBERITAHUAN RISIKO\n\nSaya memahami dan menerima risiko yang terkait.` },
        snk: { title: 'Syarat dan Ketentuan', content: `SYARAT DAN KETENTUAN\n\nSaya menyetujui syarat dan ketentuan yang berlaku.` }
    };

    let currentDocType = '';
    function openDoc(type) {
        currentDocType = type;
        document.getElementById('docTitle').textContent = docs[type].title;
        document.getElementById('docContent').textContent = docs[type].content;
        document.getElementById('docModal').classList.remove('hidden');
        document.getElementById('docModal').classList.add('flex');
    }

    function closeDoc() {
        document.getElementById('docModal').classList.add('hidden');
        document.getElementById('docModal').classList.remove('flex');
    }

    function acceptDoc() {
        const checkbox = document.getElementById('agree_' + currentDocType);
        checkbox.disabled = false;
        checkbox.checked = true;
        closeDoc();
    }

    function submitKycForm() {
        if (!document.getElementById('agree_profil').checked || !document.getElementById('agree_resiko').checked || !document.getElementById('agree_snk').checked) {
            showToast('Mohon baca dan setujui semua dokumen', 'error');
            return;
        }
        document.getElementById('kycForm').submit();
    }

    function showToast(msg, type) {
        const container = document.getElementById('toastContainer');
        const div = document.createElement('div');
        div.className = 'bg-[#111] border border-white/20 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3';
        div.innerHTML = `<i class="fas fa-info-circle text-${type === 'error' ? 'red-500' : 'accent'}"></i> ${msg}`;
        container.appendChild(div);
        setTimeout(() => div.remove(), 3000);
    }

    // Risk Profile Auto
    function calculateRisk() {
        const experience = document.getElementById('experience').value;
        const goal = document.getElementById('trading_goal').value;
        const income = document.getElementById('monthly_income').value;
        if (!experience || !goal || !income) return;
        
        let profile = (goal === 'spekulasi') ? 'Agresif' : 'Moderat';
        document.getElementById('risk_profile').value = profile.toLowerCase();
        document.getElementById('risk_profile_display').value = profile + ' (Terisi Otomatis)';
    }
    ['experience', 'trading_goal', 'monthly_income'].forEach(id => document.getElementById(id).addEventListener('change', calculateRisk));

    // Wilayah API Integration
    async function fetchData(urlPath) {
        try {
            const response = await fetch('<?= base_url('user/kyc/wilayah') ?>/' + urlPath);
            const result = await response.json();
            return result.data || [];
        } catch (e) { return []; }
    }

    async function loadProvinces() {
        const data = await fetchData('provinces.json');
        const select = document.getElementById('province');
        select.innerHTML = '<option value="" disabled selected>Provinsi</option>';
        data.forEach(item => { select.innerHTML += `<option value="${item.code}" data-name="${item.name}">${item.name}</option>`; });
    }

    async function loadRegencies(p) {
        const data = await fetchData(`regencies/${p}.json`);
        const select = document.getElementById('city');
        select.innerHTML = '<option value="" disabled selected>Kota/Kab</option>';
        data.forEach(item => { select.innerHTML += `<option value="${item.code}" data-name="${item.name}">${item.name}</option>`; });
    }

    async function loadDistricts(r) {
        const data = await fetchData(`districts/${r}.json`);
        const select = document.getElementById('district');
        select.innerHTML = '<option value="" disabled selected>Kecamatan</option>';
        data.forEach(item => { select.innerHTML += `<option value="${item.code}" data-name="${item.name}">${item.name}</option>`; });
    }

    async function loadVillages(d) {
        const data = await fetchData(`villages/${d}.json`);
        const select = document.getElementById('village');
        select.innerHTML = '<option value="" disabled selected>Kelurahan/Desa</option>';
        data.forEach(item => { select.innerHTML += `<option value="${item.code}" data-name="${item.name}">${item.name}</option>`; });
    }

    document.addEventListener('DOMContentLoaded', loadProvinces);
    
    // Mapping Codes to Names on Submit
    document.getElementById('kycForm').addEventListener('submit', function() {
        ['province', 'city', 'district', 'village'].forEach(id => {
            const el = document.getElementById(id);
            const name = el.options[el.selectedIndex]?.getAttribute('data-name');
            if (name) {
                const h = document.createElement('input');
                h.type = 'hidden'; h.name = id; h.value = name;
                this.appendChild(h);
                el.name = id + '_code';
            }
        });
    });
</script>

<?= $this->endSection() ?>
