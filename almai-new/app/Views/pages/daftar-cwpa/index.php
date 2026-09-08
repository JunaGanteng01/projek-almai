<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<section class="py-20 relative overflow-hidden min-h-screen flex items-center">
    <div class="absolute inset-0 bg-accent/5 skew-y-1 transform origin-top-left -z-10"></div>
    <div class="container mx-auto px-6">
        <div class="max-w-3xl mx-auto">
            <div class="text-center mb-8" data-aos="fade-up">
                <span class="text-accent font-bold tracking-widest text-xs uppercase mb-2 block">PENDAFTARAN</span>
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Daftar Menjadi <span class="text-accent">CWPA</span></h1>
                <p class="text-gray-400">Lengkapi data diri Anda untuk memulai perjalanan karir profesional</p>
            </div>

            <div class="bg-[#111] p-6 md:p-10 rounded-3xl border border-white/10 relative" data-aos="fade-up" data-aos-delay="100">

                <!-- Progress Bar -->
                <div class="mb-8 relative">
                    <div class="flex justify-between mb-2">
                        <span class="text-xs font-bold text-accent step-label" id="label-step-1">Data Pribadi</span>
                        <span class="text-xs font-bold text-gray-600 step-label" id="label-step-2">Media Sosial</span>
                        <span class="text-xs font-bold text-gray-600 step-label" id="label-step-3">Spesialisasi</span>
                        <span class="text-xs font-bold text-gray-600 step-label" id="label-step-4">Persyaratan</span>
                    </div>
                    <div class="w-full bg-gray-800 rounded-full h-2.5">
                        <div class="bg-accent h-2.5 rounded-full transition-all duration-300" id="progress-bar" style="width: 25%"></div>
                    </div>
                </div>

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="bg-red-500/10 border border-red-500/20 text-red-500 p-4 rounded-xl mb-6">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('errors')) : ?>
                    <div class="bg-red-500/10 border border-red-500/20 text-red-500 p-4 rounded-xl mb-6">
                        <ul class="list-disc pl-5">
                            <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('daftar-cwpa/store') ?>" method="post" id="registrationForm">
                    <?= csrf_field() ?>

                    <!-- STEP 1: Data Pribadi -->
                    <div class="step-content" id="step-1">
                        <h3 class="text-xl font-bold text-white mb-6 border-b border-white/10 pb-4">1. Data Pribadi</h3>
                        <div class="space-y-5">
                            <div>
                                <label class="block text-gray-400 text-sm mb-2">Nama Lengkap*</label>
                                <input type="text" name="name" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent focus:outline-none transition-colors" value="<?= old('name', session()->get('userName')) ?>" placeholder="Masukkan nama lengkap sesuai KTP" required>
                            </div>
                            <div>
                                <label class="block text-gray-400 text-sm mb-2">Email*</label>
                                <input type="email" name="email" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent focus:outline-none transition-colors" value="<?= old('email', session()->get('userEmail')) ?>" placeholder="email@contoh.com" required>
                            </div>
                            <div>
                                <label class="block text-gray-400 text-sm mb-2">Nomor Whatsapp*</label>
                                <input type="text" name="whatsapp" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent focus:outline-none transition-colors" value="<?= old('whatsapp') ?>" placeholder="08123456789" required>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 2: Media Sosial -->
                    <div class="step-content hidden" id="step-2">
                        <h3 class="text-xl font-bold text-white mb-6 border-b border-white/10 pb-4">2. Akun Media Sosial</h3>
                        <p class="text-sm text-gray-400 mb-4">Wajib mengisi minimal satu akun media sosial.</p>
                        <div class="grid md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-gray-400 text-sm mb-2"><i class="fab fa-instagram text-accent mr-2"></i>Instagram</label>
                                <input type="text" name="ig" class="social-input w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent focus:outline-none transition-colors" value="<?= old('ig') ?>" placeholder="@username">
                            </div>
                            <div>
                                <label class="block text-gray-400 text-sm mb-2"><i class="fab fa-facebook text-accent mr-2"></i>Facebook</label>
                                <input type="text" name="fb" class="social-input w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent focus:outline-none transition-colors" value="<?= old('fb') ?>" placeholder="Link Profil Facebook">
                            </div>
                            <div>
                                <label class="block text-gray-400 text-sm mb-2"><i class="fab fa-youtube text-accent mr-2"></i>Youtube</label>
                                <input type="text" name="youtube" class="social-input w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent focus:outline-none transition-colors" value="<?= old('youtube') ?>" placeholder="Link Channel Youtube">
                            </div>
                            <div>
                                <label class="block text-gray-400 text-sm mb-2"><i class="fab fa-tiktok text-accent mr-2"></i>Tiktok</label>
                                <input type="text" name="tiktok" class="social-input w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent focus:outline-none transition-colors" value="<?= old('tiktok') ?>" placeholder="@username">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-gray-400 text-sm mb-2"><i class="fab fa-linkedin text-accent mr-2"></i>LinkedIn</label>
                                <input type="text" name="linkedin" class="social-input w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent focus:outline-none transition-colors" value="<?= old('linkedin') ?>" placeholder="Link Profil LinkedIn">
                            </div>
                        </div>
                        <div id="social-error" class="text-red-500 text-sm mt-2 hidden">Harap isi setidaknya satu akun media sosial.</div>
                    </div>

                    <!-- STEP 3: Experience & Specialization -->
                    <div class="step-content hidden" id="step-3">
                        <h3 class="text-xl font-bold text-white mb-6 border-b border-white/10 pb-4">3. Pengalaman & Spesialisasi</h3>
                        <div>
                            <label class="block text-gray-400 text-sm mb-3">Spesialisasi Trading (Pilih yang sesuai)*</label>
                            <div class="space-y-3">
                                <label class="flex items-center gap-3 p-3 bg-black/50 border border-white/10 rounded-xl cursor-pointer hover:border-accent/50 transition-colors">
                                    <input type="checkbox" name="specialties[]" value="Kripto, Index, Aset Digital" class="specialty-checkbox w-5 h-5 rounded border-gray-600 text-accent focus:ring-accent bg-gray-700">
                                    <span class="text-gray-300 text-sm">Spesialis Kripto, Index, Aset Digital</span>
                                </label>
                                <label class="flex items-center gap-3 p-3 bg-black/50 border border-white/10 rounded-xl cursor-pointer hover:border-accent/50 transition-colors">
                                    <input type="checkbox" name="specialties[]" value="Forex" class="specialty-checkbox w-5 h-5 rounded border-gray-600 text-accent focus:ring-accent bg-gray-700">
                                    <span class="text-gray-300 text-sm">Spesialis Forex</span>
                                </label>
                                <label class="flex items-center gap-3 p-3 bg-black/50 border border-white/10 rounded-xl cursor-pointer hover:border-accent/50 transition-colors">
                                    <input type="checkbox" name="specialties[]" value="Komoditi" class="specialty-checkbox w-5 h-5 rounded border-gray-600 text-accent focus:ring-accent bg-gray-700">
                                    <span class="text-gray-300 text-sm">Spesialis Komoditi</span>
                                </label>
                                <label class="flex items-center gap-3 p-3 bg-black/50 border border-white/10 rounded-xl cursor-pointer hover:border-accent/50 transition-colors">
                                    <input type="checkbox" id="check-all" class="w-5 h-5 rounded border-gray-600 text-accent focus:ring-accent bg-gray-700">
                                    <span class="text-gray-300 text-sm font-bold">Pilih Semua</span>
                                </label>
                            </div>
                            <div id="specialty-error" class="text-red-500 text-sm mt-2 hidden">Harap pilih setidaknya satu spesialisasi.</div>
                        </div>
                    </div>

                    <!-- STEP 4: Persyaratan CWPA -->
                    <div class="step-content hidden" id="step-4">
                        <h3 class="text-xl font-bold text-white mb-6 border-b border-white/10 pb-4">4. Persyaratan C-WPA</h3>

                        <div class="space-y-6">
                            <!-- 1. Ijazah -->
                            <div class="form-group">
                                <p class="text-gray-300 mb-2 text-sm">Apakah anda memiliki Ijazah S1?*</p>
                                <div class="flex gap-6">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="has_ijazah_s1" value="yes" class="w-5 h-5 text-accent focus:ring-accent bg-gray-700 border-gray-600" required>
                                        <span class="text-white">Ya</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="has_ijazah_s1" value="no" class="w-5 h-5 text-accent focus:ring-accent bg-gray-700 border-gray-600">
                                        <span class="text-white">Tidak</span>
                                    </label>
                                </div>
                            </div>

                            <!-- 2. SKCK -->
                            <div class="form-group">
                                <p class="text-gray-300 mb-2 text-sm">Apakah anda memiliki SKCK?*</p>
                                <div class="flex gap-6">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="has_skck_card" value="yes" class="w-5 h-5 text-accent focus:ring-accent bg-gray-700 border-gray-600" onchange="toggleSkckWillingness(false)" required>
                                        <span class="text-white">Ya</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="has_skck_card" value="no" class="w-5 h-5 text-accent focus:ring-accent bg-gray-700 border-gray-600" onchange="toggleSkckWillingness(true)">
                                        <span class="text-white">Tidak</span>
                                    </label>
                                </div>
                            </div>

                            <!-- SKCK Willingness (Hidden by default) -->
                            <div class="form-group hidden pl-4 border-l-2 border-accent/30" id="skck-willingness-group">
                                <p class="text-gray-300 mb-2 text-sm text-accent">Apakah anda bersedia membuat SKCK?</p>
                                <div class="flex gap-6">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="willing_to_make_skck" value="yes" class="w-5 h-5 text-accent focus:ring-accent bg-gray-700 border-gray-600">
                                        <span class="text-white">Ya</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="willing_to_make_skck" value="no" class="w-5 h-5 text-accent focus:ring-accent bg-gray-700 border-gray-600">
                                        <span class="text-white">Tidak</span>
                                    </label>
                                </div>
                            </div>

                            <!-- 3. Clean Record (Vonis) -->
                            <div class="form-group">
                                <p class="text-gray-300 mb-2 text-sm">Apakah anda pernah divonis sebagai terpidana dengan hukuman lebih dari 5 (lima) tahun?*</p>
                                <div class="flex gap-6">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="has_felony_record" value="yes" class="w-5 h-5 text-accent focus:ring-accent bg-gray-700 border-gray-600" required>
                                        <span class="text-white">Ya</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="has_felony_record" value="no" class="w-5 h-5 text-accent focus:ring-accent bg-gray-700 border-gray-600">
                                        <span class="text-white">Tidak</span>
                                    </label>
                                </div>
                            </div>

                            <!-- 4. Bankruptcy -->
                            <div class="form-group">
                                <p class="text-gray-300 mb-2 text-sm text-justify">Apakah anda pernah dinyatakan pailit atau menjadi direktur atau komisaris yang dinyatakan bersalah menyebabkan suatu perusahaan dinyatakan pailit dalam jangka waktu 5 (lima) tahun terakhir?*</p>
                                <div class="flex gap-6">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="has_bankruptcy_record" value="yes" class="w-5 h-5 text-accent focus:ring-accent bg-gray-700 border-gray-600" required>
                                        <span class="text-white">Ya</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" name="has_bankruptcy_record" value="no" class="w-5 h-5 text-accent focus:ring-accent bg-gray-700 border-gray-600">
                                        <span class="text-white">Tidak</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between mt-10 pt-6 border-t border-white/10">
                        <button type="button" id="prevBtn" class="hidden px-6 py-3 rounded-xl border border-white/20 text-white hover:bg-white/10 transition">
                            <i class="fas fa-arrow-left mr-2"></i> Sebelumnya
                        </button>
                        <div class="ml-auto">
                            <button type="button" id="nextBtn" class="px-8 py-3 rounded-xl bg-accent text-black font-bold hover:bg-accent/90 transition shadow-[0_0_15px_rgba(51,232,24,0.3)]">
                                Selanjutnya <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                            <button type="submit" id="submitBtn" class="hidden px-8 py-3 rounded-xl bg-accent text-black font-bold hover:bg-accent/90 transition shadow-[0_0_15px_rgba(51,232,24,0.3)]">
                                Submit Pendaftaran
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Disqualification Modal -->
<div id="disqualificationModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 py-6 text-center sm:block sm:p-0">

        <div class="fixed inset-0 bg-black/90 transition-opacity backdrop-blur-sm" aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="relative inline-block align-middle bg-[#111] rounded-2xl text-center overflow-hidden transform transition-all sm:max-w-lg w-full px-8 py-10 border border-white/10 shadow-2xl">

            <!-- Icon -->
            <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full border-[3px] border-orange-400 mb-8 animate-[pulse_2s_infinite]">
                <span class="text-6xl text-orange-400 font-light">!</span>
            </div>

            <!-- Title -->
            <h3 class="text-3xl font-bold text-white mb-4" id="modal-title">
                Mohon Maaf
            </h3>

            <!-- Description -->
            <div class="mt-2 mb-10">
                <p class="text-gray-400 text-base leading-relaxed">
                    Berdasarkan peraturan perundang-undangan dan persyaratan untuk menjadi WPA, anda belum memenuhi syarat.<br><br>
                    Silahkan konsultasikan perihal ini melalui Whatsapp.
                </p>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="https://wa.me/6285183231800?text=Halo%20Admin,%20saya%20mendapat%20pesan%20bahwa%20saya%20belum%20memenuhi%20syarat%20WPA" target="_blank" class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-3 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 focus:outline-none transition shadow-lg shadow-red-600/30">
                    Hubungi WhatsApp
                </a>
                <button type="button" onclick="closeDisqualificationModal()" class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-3 bg-blue-500 text-white font-bold rounded-lg hover:bg-blue-600 focus:outline-none transition shadow-lg shadow-blue-500/30">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentStep = 1;
        const totalSteps = 4;

        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const submitBtn = document.getElementById('submitBtn');
        const checkAll = document.getElementById('check-all');
        const specialtyCheckboxes = document.querySelectorAll('.specialty-checkbox');

        // Initial State
        updateButtons();
        updateProgressBar();

        // Button Listeners
        nextBtn.addEventListener('click', () => {
            if (validateStep(currentStep)) {
                changeStep(1);
            }
        });

        prevBtn.addEventListener('click', () => {
            changeStep(-1);
        });

        // Submit Logic with Disqualification Check
        submitBtn.addEventListener('click', function(e) {
            e.preventDefault();

            // Check if user answered "Yes" to disqualifying questions
            const felonyYes = document.querySelector('input[name="has_felony_record"][value="yes"]');
            const bankruptcyYes = document.querySelector('input[name="has_bankruptcy_record"][value="yes"]');

            if ((felonyYes && felonyYes.checked) || (bankruptcyYes && bankruptcyYes.checked)) {
                // Show Disqualification Modal
                document.getElementById('disqualificationModal').classList.remove('hidden');
            } else {
                // If clean, validate and submit
                if (validateStep(4)) {
                    document.getElementById('registrationForm').submit();
                }
            }
        });

        // Check All Logic
        checkAll.addEventListener('change', function() {
            specialtyCheckboxes.forEach(cb => {
                cb.checked = this.checked;
            });
        });

        // If any specialty uncheck, uncheck 'Check All'
        specialtyCheckboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                if (!this.checked) {
                    checkAll.checked = false;
                }
            });
        });

        function changeStep(direction) {
            // Hide current
            document.getElementById(`step-${currentStep}`).classList.add('hidden');
            document.getElementById(`label-step-${currentStep}`).classList.remove('text-accent');
            document.getElementById(`label-step-${currentStep}`).classList.add('text-gray-600');

            currentStep += direction;

            // Show new
            document.getElementById(`step-${currentStep}`).classList.remove('hidden');
            document.getElementById(`label-step-${currentStep}`).classList.remove('text-gray-600');
            document.getElementById(`label-step-${currentStep}`).classList.add('text-accent');

            updateButtons();
            updateProgressBar();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        function updateButtons() {
            if (currentStep === 1) {
                prevBtn.classList.add('hidden');
            } else {
                prevBtn.classList.remove('hidden');
            }

            if (currentStep === totalSteps) {
                nextBtn.classList.add('hidden');
                submitBtn.classList.remove('hidden');
            } else {
                nextBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
            }
        }

        function updateProgressBar() {
            const progress = (currentStep / totalSteps) * 100;
            document.getElementById('progress-bar').style.width = `${progress}%`;
        }

        function validateStep(step) {
            let isValid = true;
            const currentStepEl = document.getElementById(`step-${step}`);

            // Validate Required Inputs
            const requiredInputs = currentStepEl.querySelectorAll('input[required]');
            requiredInputs.forEach(input => {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    isValid = false;
                    return;
                }
            });

            if (!isValid) return false;

            // Specific Step Validation
            if (step === 2) {
                // Social Media: At least one
                const socialInputs = currentStepEl.querySelectorAll('.social-input');
                let filled = 0;
                socialInputs.forEach(input => {
                    if (input.value.trim() !== '') filled++;
                });

                if (filled === 0) {
                    document.getElementById('social-error').classList.remove('hidden');
                    return false;
                } else {
                    document.getElementById('social-error').classList.add('hidden');
                }
            }

            if (step === 3) {
                // Specialties: At least one? (Not explicitly asked to force, but good UX)
                const checked = currentStepEl.querySelectorAll('.specialty-checkbox:checked');
                if (checked.length === 0) {
                    document.getElementById('specialty-error').classList.remove('hidden');
                    return false;
                } else {
                    document.getElementById('specialty-error').classList.add('hidden');
                }
            }

            return isValid;
        }

        // Global function for layout access
        window.toggleSkckWillingness = function(show) {
            const group = document.getElementById('skck-willingness-group');
            const inputs = group.querySelectorAll('input');

            if (show) {
                group.classList.remove('hidden');
                inputs.forEach(input => input.setAttribute('required', 'required'));
            } else {
                group.classList.add('hidden');
                inputs.forEach(input => {
                    input.removeAttribute('required');
                    input.checked = false;
                });
            }
        };

        window.closeDisqualificationModal = function() {
            document.getElementById('disqualificationModal').classList.add('hidden');
        };
    });
</script>
<?= $this->endSection(); ?>