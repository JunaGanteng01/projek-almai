<?= $this->extend('user/partials/layout') ?>
<?= $this->section('content') ?>

<!-- Full Page KYC Form -->
<div class="min-h-screen flex flex-col bg-black">
    <!-- Header -->
    <div class="sticky top-0 z-40 bg-black/95 backdrop-blur-lg border-b border-white/10 px-4 py-4">
        <div class="flex items-center gap-4 max-w-lg mx-auto w-full">
            <a href="<?= base_url('user/profile') ?>" class="text-white hover:text-accent transition">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h1 class="text-lg font-bold">Upgrade ke Member Pro</h1>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-y-auto pb-24">
        <form id="kycForm" action="<?= base_url('user/kyc/submit') ?>" method="POST" enctype="multipart/form-data" class="max-w-lg mx-auto w-full px-4 py-6">
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
                            <input type="text" name="full_name" id="full_name" required placeholder="Nama Lengkap (Sesuai KTP)"
                                class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white placeholder-gray-500 transition-all duration-300">
                            <span class="validation-icon absolute right-3 top-1/2 -translate-y-1/2 opacity-0 transition-all duration-300"></span>
                        </div>

                        <!-- NIK -->
                        <div class="relative">
                            <input type="text" name="nik" id="nik" maxlength="16" pattern="[0-9]{16}" required placeholder="NIK 16 Digit"
                                class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white placeholder-gray-500 transition-all duration-300"
                                oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                            <span class="validation-icon absolute right-3 top-1/2 -translate-y-1/2 opacity-0 transition-all duration-300"></span>
                        </div>
                        <p id="nikError" class="text-red-500 text-xs mt-1 hidden">NIK harus 16 digit angka</p>

                        <!-- NPWP -->
                        <div class="relative">
                            <input type="text" name="npwp" id="npwp" maxlength="20" required placeholder="NPWP"
                                class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white placeholder-gray-500 transition-all duration-300">
                            <span class="validation-icon absolute right-3 top-1/2 -translate-y-1/2 opacity-0 transition-all duration-300"></span>
                        </div>

                        <!-- Birth Place -->
                        <div class="relative">
                            <input type="text" name="birth_place" id="birth_place" required placeholder="Tempat Lahir"
                                class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white placeholder-gray-500 transition-all duration-300">
                            <span class="validation-icon absolute right-3 top-1/2 -translate-y-1/2 opacity-0 transition-all duration-300"></span>
                        </div>

                        <!-- Birth Date -->
                        <div class="relative">
                            <input type="date" name="birth_date" id="birth_date" required placeholder="dd/mm/yyyy"
                                class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white placeholder-gray-500 transition-all duration-300 cursor-pointer text-left"
                                style="color-scheme: dark;">
                            <span class="validation-icon absolute right-3 top-1/2 -translate-y-1/2 opacity-0 transition-all duration-300"></span>
                        </div>

                        <!-- Gender -->
                        <div class="relative">
                            <select name="gender" id="gender" required class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white transition-all duration-300 appearance-none">
                                <option value="" disabled selected class="text-gray-500">Jenis Kelamin</option>
                                <option value="male">Laki-laki</option>
                                <option value="female">Perempuan</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none text-xs"></i>
                            <span class="validation-icon absolute right-8 top-1/2 -translate-y-1/2 opacity-0 transition-all duration-300"></span>
                        </div>

                        <!-- Phone -->
                        <div class="relative">
                            <input type="tel" name="phone" id="phone" required placeholder="Nomor telepon/Whatsapp"
                                class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white placeholder-gray-500 transition-all duration-300">
                            <span class="validation-icon absolute right-3 top-1/2 -translate-y-1/2 opacity-0 transition-all duration-300"></span>
                        </div>

                        <!-- Address -->
                        <div class="relative">
                            <textarea name="address" id="address" rows="3" required placeholder="Alamat Lengkap"
                                class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white placeholder-gray-500 resize-none transition-all duration-300"></textarea>
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
                            <input type="text" name="postal_code" id="postal_code" required placeholder="Kode Pos"
                                class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white placeholder-gray-500 transition-all duration-300">
                        </div>

                        <!-- Profession & Purpose -->
                        <div class="relative">
                            <select name="profession" id="profession" required class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white transition-all duration-300 appearance-none">
                                <option value="" disabled selected>Pekerjaan</option>
                                <option value="pelajar">Pelajar/Mahasiswa</option>
                                <option value="swasta">Karyawan Swasta</option>
                                <option value="pns">Pegawai Negeri Sipil</option>
                                <option value="wiraswasta">Wiraswasta</option>
                                <option value="profesional">Profesional</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none text-xs"></i>
                        </div>
                        <div class="relative">
                            <select name="registration_purpose" id="registration_purpose" required class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white transition-all duration-300 appearance-none">
                                <option value="" disabled selected>Tujuan Pembukaan Akun</option>
                                <option value="pelatihan">Pelatihan</option>
                                <option value="pendampingan">Pendampingan</option>
                                <option value="lainnya">Lainnya</option>
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
                        <div id="ktpPreviewContainer" style="display:none;" class="mt-2 text-center">
                            <img id="ktpPreview" src="" class="max-h-48 rounded-lg border border-white/20 mx-auto">
                        </div>
                    </div>
                </div>

                <!-- Bank Info Card (Included for functionality, styled same) -->
                <div class="bg-[#111] border border-white/10 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-6">
                        <i class="fas fa-university text-accent text-lg"></i>
                        <h2 class="font-bold text-lg text-white">Data Rekening</h2>
                    </div>
                    <div class="space-y-4">
                        <div class="relative">
                            <input type="text" name="bank_name" id="bank_name" required placeholder="Nama Bank"
                                class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white placeholder-gray-500 transition-all duration-300">
                        </div>
                        <div class="relative">
                            <input type="text" name="account_number" id="account_number" required placeholder="Nomor Rekening"
                                class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white placeholder-gray-500 transition-all duration-300">
                        </div>
                        <div class="relative">
                            <input type="text" name="account_name" id="account_name" required placeholder="Nama Pemilik Rekening"
                                class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white placeholder-gray-500 transition-all duration-300">
                        </div>
                        <!-- Branch Optional -->
                        <div class="relative">
                            <input type="hidden" name="bank_branch" value="-">
                        </div>
                    </div>
                </div>

                <!-- Trading Experience (Minimalist) -->
                <div class="bg-[#111] border border-white/10 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-6">
                        <i class="fas fa-chart-line text-accent text-lg"></i>
                        <h2 class="font-bold text-lg text-white">Pengalaman</h2>
                    </div>
                    <div class="space-y-4">
                        <div class="relative">
                            <select name="experience" id="experience" required class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white transition-all duration-300 appearance-none">
                                <option value="" disabled selected>Pengalaman Trading</option>
                                <option value="none">Belum pernah</option>
                                <option value="less_1_year">Kurang dari 1 tahun</option>
                                <option value="1_3_years">1-3 tahun</option>
                                <option value="more_3_years">Lebih dari 3 tahun</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none text-xs"></i>
                        </div>
                        <div class="relative">
                            <select name="trading_goal" id="trading_goal" required class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white transition-all duration-300 appearance-none">
                                <option value="" disabled selected>Tujuan Trading</option>
                                <option value="hedging">Hedging</option>
                                <option value="spekulasi">Spekulasi</option>
                                <option value="investasi">Investasi</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none text-xs"></i>
                        </div>
                        <div class="relative">
                            <select name="monthly_income" id="monthly_income" required class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white transition-all duration-300 appearance-none">
                                <option value="" disabled selected>Pendapatan Per Bulan</option>
                                <option value="less_5m">
                                    < 5 Juta</option>
                                <option value="5m_15m">5 - 15 Juta</option>
                                <option value="15m_50m">15 - 50 Juta</option>
                                <option value="more_50m">> 50 Juta</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none text-xs"></i>
                        </div>
                        <!-- Auto Risk Profile (Visible Readonly) -->
                        <div class="relative">
                            <input type="text" id="risk_profile_display" readonly placeholder="Profil Risiko (Terisi Otomatis oleh Sistem)"
                                class="kyc-input w-full px-4 py-3.5 bg-[#1a1a1a] border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-gray-400 cursor-not-allowed">
                            <input type="hidden" name="risk_profile" id="risk_profile" value="moderate">
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
                <!-- Document Card -->
                <div class="bg-[#111] border border-white/10 rounded-2xl p-5">
                    <div class="flex items-center gap-3 mb-6">
                        <i class="fas fa-file-contract text-accent text-lg"></i>
                        <h2 class="font-bold text-lg text-white">Dokumen Pernyataan</h2>
                    </div>

                    <div class="space-y-6">
                        <!-- Doc 1 -->
                        <div class="flex items-start gap-4">
                            <div class="relative top-1">
                                <input type="checkbox" name="agree_profil" id="agree_profil" value="1" disabled
                                    class="peer appearance-none w-6 h-6 border-2 border-white/20 rounded-lg bg-[#1a1a1a] checked:bg-accent checked:border-accent transition cursor-pointer">
                                <i class="fas fa-check absolute top-1 left-1 text-black text-xs opacity-0 peer-checked:opacity-100 pointer-events-none"></i>
                            </div>
                            <div class="flex-1">
                                <button type="button" onclick="openDoc('profil')" class="text-left font-bold text-accent hover:text-white text-sm hover:underline">
                                    Pernyataan Membaca Profil Perusahaan
                                </button>
                                <p class="text-gray-400 text-xs mt-1 leading-relaxed">
                                    Dengan ini saya menyatakan telah membaca, memahami, dan menyetujui peraturan perusahaan.
                                </p>
                            </div>
                        </div>

                        <!-- Doc 2 -->
                        <div class="flex items-start gap-4">
                            <div class="relative top-1">
                                <input type="checkbox" name="agree_resiko" id="agree_resiko" value="1" disabled
                                    class="peer appearance-none w-6 h-6 border-2 border-white/20 rounded-lg bg-[#1a1a1a] checked:bg-accent checked:border-accent transition cursor-pointer">
                                <i class="fas fa-check absolute top-1 left-1 text-black text-xs opacity-0 peer-checked:opacity-100 pointer-events-none"></i>
                            </div>
                            <div class="flex-1">
                                <button type="button" onclick="openDoc('resiko')" class="text-left font-bold text-accent hover:text-white text-sm hover:underline">
                                    Pernyataan Pemberitahuan Risiko
                                </button>
                                <p class="text-gray-400 text-xs mt-1 leading-relaxed">
                                    Saya memahami dan menerima risiko yang terkait dengan penggunaan layanan ini.
                                </p>
                            </div>
                        </div>

                        <!-- Doc 3 -->
                        <div class="flex items-start gap-4">
                            <div class="relative top-1">
                                <input type="checkbox" name="agree_snk" id="agree_snk" value="1" disabled
                                    class="peer appearance-none w-6 h-6 border-2 border-white/20 rounded-lg bg-[#1a1a1a] checked:bg-accent checked:border-accent transition cursor-pointer">
                                <i class="fas fa-check absolute top-1 left-1 text-black text-xs opacity-0 peer-checked:opacity-100 pointer-events-none"></i>
                            </div>
                            <div class="flex-1">
                                <button type="button" onclick="openDoc('snk')" class="text-left font-bold text-accent hover:text-white text-sm hover:underline">
                                    Pernyataan Syarat dan Ketentuan
                                </button>
                                <p class="text-gray-400 text-xs mt-1 leading-relaxed">
                                    Saya menyetujui syarat dan ketentuan yang berlaku.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="mt-8 pt-4 space-y-3">
                    <button type="button" onclick="submitKycForm()" id="submitBtn" class="w-full py-4 bg-accent text-black font-bold rounded-2xl hover:bg-white transition shadow-lg shadow-accent/20">
                        Kirim
                    </button>
                    <button type="button" onclick="goToStep1()" class="w-full py-4 border border-white/20 text-white font-bold rounded-2xl hover:bg-white/5 transition">
                        Kembali
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

<!-- Toast Notification -->
<div id="toastContainer" class="fixed top-6 right-6 z-[100] space-y-3"></div>

<!-- Validation Error Modal -->
<div id="validationModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[70] hidden items-center justify-center p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl w-full max-w-md overflow-hidden transform scale-95 opacity-0 transition-all duration-300" id="validationModalContent">
        <div class="p-6 text-center">
            <div class="w-16 h-16 mx-auto mb-4 bg-red-500/10 rounded-full flex items-center justify-center">
                <i class="fas fa-exclamation text-red-500 text-2xl"></i>
            </div>
            <h3 class="text-lg font-bold mb-2">Data Belum Lengkap</h3>
            <p class="text-gray-400 text-sm mb-4">Mohon lengkapi data yang ditandai merah.</p>
            <div id="validationErrorList" class="hidden"></div>
            <button onclick="closeValidationModal()" class="w-full py-3 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600 transition text-sm">
                Oke, Mengerti
            </button>
        </div>
    </div>
</div>

<!-- Document Modal -->
<div id="docModal" class="fixed inset-0 bg-black/95 z-[90] hidden items-center justify-center p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl w-full max-w-2xl max-h-[85vh] overflow-hidden flex flex-col">
        <div class="p-4 border-b border-white/10 flex items-center justify-between">
            <h3 id="docTitle" class="font-bold text-white">Dokumen</h3>
            <button onclick="closeDoc()" class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center hover:bg-white/10 text-gray-400">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="docContent" class="p-6 overflow-y-auto flex-1 text-xs md:text-sm text-gray-300 whitespace-pre-wrap leading-relaxed"></div>
        <div class="p-4 border-t border-white/10 bg-[#0a0a0a]">
            <button type="button" onclick="acceptDoc()" class="w-full py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-sm">
                Saya Setuju
            </button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Logic for Single Page Application feel
    let userData = {};

    function goToStep2() {
        if (!validateStep1()) return;

        // Grab values for template
        userData.name = document.getElementById('full_name').value;
        userData.phone = document.getElementById('phone').value;
        userData.nik = document.getElementById('nik').value;

        // Animate transition
        document.getElementById('step1').classList.add('hidden');
        document.getElementById('step2').classList.remove('hidden');
        window.scrollTo(0, 0);
    }

    function goToStep1() {
        document.getElementById('step2').classList.add('hidden');
        document.getElementById('step1').classList.remove('hidden');
        window.scrollTo(0, 0);
    }

    // Validation Logic
    function validateStep1() {
        const requiredIds = [
            'full_name', 'nik', 'npwp', 'birth_place', 'birth_date', 'gender',
            'phone', 'address', 'province', 'city', 'district', 'village', 'postal_code',
            'profession', 'registration_purpose', 'bank_name',
            'account_number', 'account_name', 'experience', 'trading_goal', 'monthly_income'
        ];

        // Check file
        const fileInput = document.getElementById('ktp_photo_display');
        const hiddenFile = document.getElementById('ktp_photo_hidden');
        let fileValid = (hiddenFile.files.length > 0);
        if (!fileValid) {
            document.getElementById('ktp_photo_display').parentElement.classList.add('border-red-500');
            showToast('Foto KTP wajib diupload', 'error');
            return false;
        }

        let valid = true;
        requiredIds.forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            if (!el.value || el.value.trim() === '') {
                setFieldError(el);
                valid = false;
            } else {
                setFieldSuccess(el);
            }
        });

        // NIK validation
        const nikEl = document.getElementById('nik');
        if (nikEl.value.length !== 16) {
            setFieldError(nikEl);
            document.getElementById('nikError').classList.remove('hidden');
            valid = false;
        } else {
            document.getElementById('nikError').classList.add('hidden');
        }

        if (!valid) {
            showToast('Mohon lengkapi semua data', 'error');
            const firstError = document.querySelector('.border-red-500');
            if (firstError) firstError.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }

        return valid;
    }

    function setFieldError(el) {
        el.classList.add('border-red-500');
        el.classList.remove('border-white/10', 'focus:border-accent');
    }

    function setFieldSuccess(el) {
        el.classList.remove('border-red-500');
        el.classList.add('border-white/10', 'focus:border-accent');
    }

    // Real-time validation listener
    document.querySelectorAll('.kyc-input').forEach(input => {
        input.addEventListener('input', function() {
            if (this.value) setFieldSuccess(this);
        });
    });

    // KTP Upload Handler
    function handleKtpUpload(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            if (file.size > 5 * 1024 * 1024) {
                showToast('Ukuran file maksimal 5MB', 'error');
                return;
            }

            // Preview
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('ktpPreview').src = e.target.result;
                document.getElementById('ktpPreviewContainer').style.display = 'block';
            };
            reader.readAsDataURL(file);

            // Transfer to hidden input
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            document.getElementById('ktp_photo_hidden').files = dataTransfer.files;

            // Visual feedback
            document.getElementById('ktp_filename').classList.remove('hidden');
            document.getElementById('ktp_photo_display').parentElement.classList.remove('border-red-500');
            document.getElementById('ktp_photo_display').parentElement.classList.add('border-accent', 'border-solid');
            document.getElementById('ktp_photo_display').parentElement.classList.remove('border-dashed');


        }
    }

    // Docs Logic
    const docs = {
        profil: {
            title: 'Profil Perusahaan',
            content: `Nomor Kontrak: 47/ALMAI/PP/VIII/2024

| PROFIL PERUSAHAAN
Nama Perusahaan : PT. Alma Indonesia Raya | Almai
Alamat : ALMAI | Jl. Badak Agung No.22 Kav. 3, Kel. Renon, Kec. Denpasar Selatan, Denpasar, Bali 80226.
Nomor Tlf / Fax : (0361)3610019
Chat Support : +62 851-8339-0019 dan +62 851-8323-1800
Email : support@almai.id
Website : www.almai.id
Tempat, Berdirinya Usaha : Denpasar, 17 Desember 2021

| MEDIA SOSIAL
Almai_id : Instagram, Facebook, X, Youtube

| SEJARAH DAN REPUTASI PENASIHAT BERJANGKA
Para pengurus, baik para Pemegang Saham, Komisaris, Direksi dan Penasihat Perusahaan merupakan orang-orang yang memiliki pengalaman mendalam dibidang Perdagangan Berjangka.

| SUSUNAN PENGURUS
Direktur Utama : Rendy Mahameru Prayogie (YOGI)
Direktur : Alit Widiastika SE., MH.
Komisaris : Kadek Aldo Nagata S.Par, MM
Penasihat Perusahaan : Drs,Ec I Gede Raka Tantra

| PERMODALAN
Modal Dasar : Rp 5.000.000.000
Modal Disetor : Rp 5.000.000.000

| PERIZINAN USAHA
A. KEMENTERIAN HUKUM DAN HAK ASASI MANUSIA
SK. Menteri Hukum dan Hak Asasi Manusia Republik Indonesia.
NOMOR : AHU 00138.AH.02.02. TAHUN 2023
NIB : 2612210008651

B. KEMENTERIAN KOMUNIKASI DAN INFORMATIKA REPUBLIK INDONESIA
Nomor Tanda Daftar PSE : 003510.01/DJAI.PSE/08/2023

C. KEMENTERIAN PERDAGANGAN REPUBLIK INDONESIA
Badan Pengawas Perdagangan Berjangka dan Komiditi (BAPPEBTI)
IZIN USAHA PENASIHAT BERJANGKA : NOMOR 02/BAPPEBTI/SI-PNB/02/2024
IZIN USAHA PENASIHAT BERJANGKA EXPERT ADVISOR : NOMOR : 01/BAPPEBTI/SP-PBEA/06/2024

| JASA NASIHAT YANG DITAWARKAN
- Pelatihan Perdagangan Berjangka
- Pendampingan Penasihat
- Expert Advisor (Robot Trading)

---
PERNYATAAN KLIEN TELAH MEMBACA PROFIL PERUSAHAAN PT. ALMA INDONESIA RAYA.

Dengan menyetujui, menyatakan bahwa "Saya telah membaca, memahami, setuju terhadap semua ketentuan yang tercantum dalam DOKUMEN PROFIL PERUSAHAAN PT. ALMA INDONESIA RAYA | ALMAI, serta menerima.`
        },
        resiko: {
            title: 'Pemberitahuan Risiko',
            content: `Nomor Kontrak: 48/ALMAI/PAR/VIII/2024

DOKUMEN PEMBERITAHUAN ADANYA RESIKO PENGGUNAAN LAYANAN PENASIHAT BERJANGKA DAN NASIHAT BERBASIS TEKNOLOGI INFORMASI BERUPA EXPERT ADVISOR DARI PT. ALMA INDONESIA RAYA | ALMAI.

1. PEMBERITAHUAN ADANYA RESIKO PENGGUNAAN LAYANAN PENASIHAT BERJANGKA
1.1. Hanya Wakil Penasihat Berjangka yang berwenang untuk mewakili PT. Alma Indonesia Raya | Almai dalam berhubungan dan memberikan layanan Nasihat kepada Klien secara langsung.
1.2. Mendapatkan nasihat yang tidak akurat atau tidak tepat, yang dapat mengakibatkan keputusan yang merugikan bagi klien.
1.3. Kemungkinan Kehilangan uang atau aset akibat investasi atau transaksi yang direkomendasikan.

2. PEMBERITAHUAN RESIKO PELAKSANAAN TRANSAKSI PERDAGANGAN BERJANGKA
2.1. Perdagangan Berjangka belum tentu layak bagi semua investor. Klien dapat menderita kerugian dalam jumlah besar dan dalam jangka waktu singkat.
2.2. Berhati-hatilah terhadap pernyataan bahwa Perdagangan Berjangka Anda pasti mendapatkan keuntungan besar.

3. PEMBERITAHUAN ADANYA RESIKO PENGGUNAAN PORTAL PENASIHAT BERJANGKA ALMAI.ID
3.1. Tidak akuratnya informasi, rekomendasi yang terdapat pada Portal Penasihat Berjangka Almai.id.
3.2. Keamanan data, potensi resiko keamanan data.

4. PEMBERITAHUAN RESIKO PENGGUNAAN EXPERT ADVISOR (EA)
4.1. Tidak ada jaminan keberhasilan.
4.2. Risiko kehilangan modal.
4.3. Ketergantungan pada teknologi.

---
Dengan menyetujui, menyatakan bahwa "Saya telah membaca, memahami, setuju terhadap semua ketentuan yang tercantum dalam DOKUMEN PEMBERITAHUAN ADANYA RESIKO, serta menerima.`
        },
        snk: {
            title: 'Syarat dan Ketentuan',
            content: `Nomor Kontrak: 1913/ALMAI/S&K/XII/2025

PERHATIAN!
SYARAT DAN KETENTUAN INI MERUPAKAN BAGIAN BENTUK KONTRAK KERJA ANTARA PT. ALMA INDONESIA RAYA DENGAN KLIEN DAN SALING MENGIKAT SERTA DILINDUNGI OLEH UNDANG-UNDANG REPUBLIK INDONESIA.

SYARAT DAN KETENTUAN LAYANAN PENASIHAT BERJANGKA PT. ALMA INDONESIA RAYA | ALMAI.
Berlaku sejak 12 Desember 2025

1. SYARAT & KETENTUAN
Syarat dan Ketentuan ini adalah perjanjian hukum antara entitas hukum PT. Alma Indonesia Raya | Almai yang merupakan perusahaan Penasihat Perdagangan Berjangka.

2. RUANG LINGKUP LAYANAN
2.1 Layanan Gratis
- Artikel Perdagangan Berjangka Komoditi
- Artikel Analisis
- Kelas Gratis
2.2 Layanan Berbayar
- Kelas Pelatihan
- Pendampingan Penasihat
- Penyediaan Expert Advisor (BOT)

3. HAK DAN KEWAJIBAN
3.1. Hak Klien
A. Menerima informasi dan rekomendasi investasi yang akurat dan tepat waktu.
B. Mengajukan pertanyaan terkait layanan yang diberikan.
3.2. Kewajiban Klien
A. Memberikan informasi yang akurat dan lengkap.
B. Membayar biaya layanan sesuai dengan ketentuan yang disepakati.

4. BIAYA LAYANAN
Klien setuju untuk membayar biaya layanan penasihat berjangka sesuai dengan tarif yang ditetapkan.

5. KERAHASIAAN
Kedua belah pihak sepakat untuk menjaga kerahasiaan informasi yang diperoleh selama masa perjanjian.

6. HUKUM YANG MENGATUR
Perjanjian ini tunduk secara eksklusif pada hukum yang berlaku di Republik Indonesia.

---
Dengan menyetujui perjanjian ini, "Klien" dan "Penasihat Berjangka" menyatakan bahwa mereka telah membaca, memahami, dan setuju untuk terikat oleh ketentuan-ketentuan yang diatur dalam perjanjian ini.`
        }
    };

    let currentDocType = '';

    function openDoc(type) {
        currentDocType = type;
        const doc = docs[type];
        document.getElementById('docTitle').textContent = doc.title;

        // Replace placeholders if any
        let content = doc.content;

        document.getElementById('docContent').textContent = content; // Simplified
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
        const checks = ['agree_profil', 'agree_resiko', 'agree_snk'];
        let allChecked = true;
        checks.forEach(id => {
            if (!document.getElementById(id).checked) allChecked = false;
        });

        if (!allChecked) {
            showToast('Mohon baca dan setujui semua dokumen', 'error');
            return;
        }

        document.getElementById('kycForm').submit();
    }

    function showToast(msg, type) {
        const container = document.getElementById('toastContainer');
        const div = document.createElement('div');
        div.className = 'bg-[#111] border border-white/20 text-white px-6 py-4 rounded-xl shadow-2xl flex items-center gap-3 animate-bounce-in';
        div.innerHTML = `<i class="fas fa-info-circle text-${type === 'error' ? 'red-500' : 'accent'}"></i> ${msg}`;
        container.appendChild(div);
        setTimeout(() => div.remove(), 3000);
    }

    function closeValidationModal() {
        document.getElementById('validationModal').classList.add('hidden');
        document.getElementById('validationModal').classList.remove('flex');
    }

    // Auto Risk Profile Calculation
    function calculateRisk() {
        const experience = document.getElementById('experience').value;
        const goal = document.getElementById('trading_goal').value;
        const income = document.getElementById('monthly_income').value;
        const riskInput = document.getElementById('risk_profile');
        const riskDisplay = document.getElementById('risk_profile_display');

        if (!experience || !goal || !income) {
            riskDisplay.value = '';
            return;
        }

        let score = 0;

        // Experience Score
        if (experience === 'less_1_year') score += 1;
        else if (experience === '1_3_years') score += 2;
        else if (experience === 'more_3_years') score += 3;

        // Goal Score
        if (goal === 'investasi') score += 1;
        else if (goal === 'spekulasi') score += 3;
        else score += 1; // Hedging (conservative)

        // Income Score
        if (income === '5m_15m') score += 1;
        else if (income === '15m_50m') score += 2;
        else if (income === 'more_50m') score += 3;

        let profile = 'konservatif';
        let profileValue = 'conservative';

        if (score >= 7) {
            profile = 'Agresif';
            profileValue = 'aggressive';
        } else if (score >= 4) {
            profile = 'Moderat';
            profileValue = 'moderate';
        } else {
            profile = 'Konservatif';
            profileValue = 'conservative';
        }

        // Force aggressive if speculation is chosen
        if (goal === 'spekulasi') {
            profile = 'Agresif';
            profileValue = 'aggressive';
        }

        riskInput.value = profileValue;
        riskDisplay.value = profile + ' (Terisi Otomatis)';

        // Visual feedback
        setFieldSuccess(riskDisplay);
    }

    // Add listeners to factors
    document.getElementById('monthly_income').addEventListener('change', calculateRisk);

    // Wilayah API Integration
    async function fetchData(urlPath) {
        try {
            const response = await fetch('<?= base_url('user/kyc/wilayah') ?>/' + urlPath);
            const result = await response.json();
            console.log('Wilayah API Result for ' + urlPath + ':', result);
            return result.data || [];
        } catch (error) {
            console.error('Fetch Error:', error);
            return [];
        }
    }

    async function loadProvinces() {
        const data = await fetchData('provinces.json');
        if (!Array.isArray(data)) return;
        const select = document.getElementById('province');
        select.innerHTML = '<option value="" disabled selected>Provinsi</option>';
        data.forEach(item => {
            select.innerHTML += `<option value="${item.code}" data-name="${item.name}">${item.name}</option>`;
        });
    }

    async function loadRegencies(provinceCode) {
        const data = await fetchData(`regencies/${provinceCode}.json`);
        if (!Array.isArray(data)) return;
        const select = document.getElementById('city');
        select.innerHTML = '<option value="" disabled selected>Kota/Kab</option>';
        document.getElementById('district').innerHTML = '<option value="" disabled selected>Kecamatan</option>';
        document.getElementById('village').innerHTML = '<option value="" disabled selected>Kelurahan/Desa</option>';
        
        data.forEach(item => {
            select.innerHTML += `<option value="${item.code}" data-name="${item.name}">${item.name}</option>`;
        });
    }

    async function loadDistricts(regencyCode) {
        const data = await fetchData(`districts/${regencyCode}.json`);
        if (!Array.isArray(data)) return;
        const select = document.getElementById('district');
        select.innerHTML = '<option value="" disabled selected>Kecamatan</option>';
        document.getElementById('village').innerHTML = '<option value="" disabled selected>Kelurahan/Desa</option>';

        data.forEach(item => {
            select.innerHTML += `<option value="${item.code}" data-name="${item.name}">${item.name}</option>`;
        });
    }

    async function loadVillages(districtCode) {
        const data = await fetchData(`villages/${districtCode}.json`);
        if (!Array.isArray(data)) return;
        const select = document.getElementById('village');
        select.innerHTML = '<option value="" disabled selected>Kelurahan/Desa</option>';

        data.forEach(item => {
            select.innerHTML += `<option value="${item.code}" data-name="${item.name}">${item.name}</option>`;
        });
    }

    // Initialize provinces on load
    document.addEventListener('DOMContentLoaded', loadProvinces);

    // Before submit, replace codes with names
    document.getElementById('kycForm').addEventListener('submit', function(e) {
        const province = document.getElementById('province');
        const city = document.getElementById('city');
        const district = document.getElementById('district');
        const village = document.getElementById('village');

        if(province.value && province.options[province.selectedIndex].dataset.name) {
            const hiddenProvince = document.createElement('input');
            hiddenProvince.type = 'hidden';
            hiddenProvince.name = 'province_name';
            hiddenProvince.value = province.options[province.selectedIndex].dataset.name;
            this.appendChild(hiddenProvince);
        }
        // Actually, it's easier to just change the value before submission
        // But better to have names in hidden fields if we want to keep codes for some reason.
        // For now, let's just make sure the names are sent.
    });

    // Override submitKycForm to handle name mapping
    const originalSubmitKycForm = window.submitKycForm;
    window.submitKycForm = function() {
        const province = document.getElementById('province');
        const city = document.getElementById('city');
        const district = document.getElementById('district');
        const village = document.getElementById('village');

        // Remove any existing hidden fields we created before to avoid duplicates
        ['province', 'city', 'district', 'village'].forEach(name => {
            const existing = document.querySelector(`input[type="hidden"][name="${name}"][data-proxy="true"]`);
            if (existing) existing.remove();
        });

        // We want to send names to the server, not codes
        const mapping = [
            { el: province, name: 'province' },
            { el: city, name: 'city' },
            { el: district, name: 'district' },
            { el: village, name: 'village' }
        ];

        mapping.forEach(item => {
            const name = item.el.options[item.el.selectedIndex]?.getAttribute('data-name');
            if (name) {
                const h = document.createElement('input');
                h.type = 'hidden';
                h.name = item.name;
                h.value = name;
                h.setAttribute('data-proxy', 'true');
                document.getElementById('kycForm').appendChild(h);
                
                // Temporarily rename the select so it doesn't conflict with our hidden input
                if (!item.el.dataset.originalName) {
                    item.el.dataset.originalName = item.el.name;
                }
                item.el.name = item.el.dataset.originalName + '_code';
            }
        });

        originalSubmitKycForm();
    };
</script>
<?= $this->endSection() ?>