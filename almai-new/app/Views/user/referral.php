<?= $this->extend('user/partials/layout') ?>

<?= $this->section('content') ?>
<div class="w-full max-w-full px-5 md:px-0 pb-32 pt-4 md:pt-0">
    <!-- Header -->
    <div class="mb-6 md:mb-8">
        <a href="<?= base_url('user/profile') ?>" class="inline-flex items-center text-gray-400 hover:text-white mb-4 transition text-sm">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Profil
        </a>
        <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Ajak Teman</h1>
        <p class="text-gray-400 text-sm md:text-base break-words pr-2">Bagikan kode referral Anda dan dapatkan bonus menarik setiap kali teman Anda bergabung dan bertransaksi.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Main Referral Card -->
        <div class="lg:col-span-12 space-y-6">
            <!-- Code & Link Card -->
            <div class="bg-[#111] border border-white/10 rounded-2xl p-5 md:p-6 relative overflow-hidden">
                <!-- Decorative Glow -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-[#33E818]/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>

                <div class="relative z-10">
                    <h2 class="text-lg md:text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <i class="fas fa-gift text-[#33E818]"></i> Kode Referral Anda
                    </h2>

                    <!-- Referral Code Box -->
                    <div class="bg-black/40 border border-[#33E818]/20 rounded-xl p-6 text-center mb-6">
                        <p class="text-gray-400 text-xs md:text-sm mb-2">Kode Unik Anda</p>
                        <div class="text-3xl md:text-4xl font-mono font-bold text-[#33E818] tracking-wider mb-4 select-all break-all">
                            <?= esc($user['code_referral'] ?? 'N/A') ?>
                        </div>
                        <button onclick="copyToClipboard('<?= esc($user['code_referral'] ?? '') ?>')" class="inline-flex items-center gap-2 text-sm text-gray-300 hover:text-white transition group bg-white/5 px-4 py-2 rounded-lg hover:bg-white/10">
                            <i class="fas fa-copy group-hover:text-[#33E818]"></i> Salin Kode
                        </button>
                    </div>

                    <!-- Custom Referral Code (PRO Only) -->
                    <?php
                    $userLevel = (int)($user['level_id'] ?? 1);
                    $isPro = $userLevel >= \App\Models\LevelModel::LEVEL_PRO;
                    $hasCustomized = !empty($user['is_referral_customized']);
                    ?>

                    <?php if ($isPro && !$hasCustomized): ?>
                        <div class="bg-gradient-to-r from-[#33E818]/10 to-transparent border border-[#33E818]/20 rounded-xl p-4 mb-6">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-[#33E818]/20 flex items-center justify-center shrink-0 mt-0.5">
                                    <i class="fas fa-crown text-[#33E818] text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-sm font-bold text-white mb-1">Custom Kode Referral</h3>
                                    <p class="text-xs text-gray-400 mb-3">Eksklusif User PRO, CWPA, & WPA: Personalisasi kode referral Anda agar lebih mudah diingat. Hanya bisa diubah 1x.</p>

                                    <form action="<?= base_url('user/referral/update') ?>" method="POST" class="flex flex-col sm:flex-row gap-3" id="customReferralForm">
                                        <?= csrf_field() ?>
                                        <input type="text" name="code_referral" placeholder="KODEUNIK"
                                            class="w-full sm:flex-1 bg-black/50 border border-white/20 rounded-lg px-3 py-2 text-white focus:border-[#33E818] outline-none uppercase font-mono text-sm placeholder:text-gray-600"
                                            minlength="4" maxlength="20" pattern="[A-Za-z0-9]+" title="Hanya huruf dan angka" required>
                                        <button type="button" onclick="confirmUpdateReferral()" class="w-full sm:w-auto bg-[#33E818] hover:bg-[#2bc214] text-black px-4 py-2 rounded-lg transition text-xs font-bold whitespace-nowrap">
                                            Simpan Permanen
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php elseif ($isPro && $hasCustomized): ?>
                        <div class="text-center mb-6">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#33E818]/10 text-[#33E818] text-xs font-medium border border-[#33E818]/20">
                                <i class="fas fa-check-circle"></i> Kode Referral Custom Aktif
                            </span>
                        </div>
                    <?php endif; ?>


                    <!-- Share Link Section -->
                    <div class="space-y-3">
                        <label class="text-sm font-medium text-gray-300">Link Referral</label>
                        <div class="flex flex-col md:flex-row gap-3">
                            <div class="flex-1 bg-black/50 border border-white/10 rounded-xl px-4 py-3.5 text-xs md:text-sm text-gray-300 font-mono flex items-center overflow-hidden">
                                <span class="truncate w-full select-all" id="referralUrl"><?= base_url('referral/' . ($user['code_referral'] ?? '')) ?></span>
                            </div>
                            <button onclick="copyToClipboard(document.getElementById('referralUrl').innerText)" class="shrink-0 bg-[#33E818] hover:bg-[#2bc214] text-black font-bold px-6 py-3.5 rounded-xl transition shadow-[0_0_15px_rgba(51,232,24,0.2)] hover:shadow-[0_0_20px_rgba(51,232,24,0.4)] active:scale-95 flex items-center justify-center gap-2">
                                <i class="fas fa-link"></i> Salin
                            </button>
                        </div>
                    </div>

                    <!-- Social Share Buttons -->
                    <div class="mt-8 pt-6 border-t border-white/5">
                        <p class="text-sm text-gray-400 mb-4">Bagikan langsung ke sosial media:</p>
                        <div class="grid grid-cols-3 gap-3 md:gap-4">
                            <a href="https://wa.me/?text=<?= urlencode('Daftar di Almai.id dan dapatkan keuntungan eksklusif! Klik di sini: ' . base_url('referral/' . ($user['code_referral'] ?? ''))) ?>" target="_blank" class="flex flex-col items-center justify-center p-3 md:p-4 rounded-xl bg-[#25D366]/10 hover:bg-[#25D366]/20 text-[#25D366] transition group hover:-translate-y-1 border border-transparent hover:border-[#25D366]/30">
                                <i class="fab fa-whatsapp text-2xl mb-1.5 group-hover:scale-110 transition"></i>
                                <span class="text-[10px] md:text-xs font-semibold">WhatsApp</span>
                            </a>
                            <a href="https://t.me/share/url?url=<?= urlencode(base_url('referral/' . ($user['code_referral'] ?? ''))) ?>&text=<?= urlencode('Daftar di Almai.id sekarang!') ?>" target="_blank" class="flex flex-col items-center justify-center p-3 md:p-4 rounded-xl bg-[#0088cc]/10 hover:bg-[#0088cc]/20 text-[#0088cc] transition group hover:-translate-y-1 border border-transparent hover:border-[#0088cc]/30">
                                <i class="fab fa-telegram text-2xl mb-1.5 group-hover:scale-110 transition"></i>
                                <span class="text-[10px] md:text-xs font-semibold">Telegram</span>
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(base_url('referral/' . ($user['code_referral'] ?? ''))) ?>" target="_blank" class="flex flex-col items-center justify-center p-3 md:p-4 rounded-xl bg-[#1877F2]/10 hover:bg-[#1877F2]/20 text-[#1877F2] transition group hover:-translate-y-1 border border-transparent hover:border-[#1877F2]/30">
                                <i class="fab fa-facebook text-2xl mb-1.5 group-hover:scale-110 transition"></i>
                                <span class="text-[10px] md:text-xs font-semibold">Facebook</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- How it Works -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-[#111] border border-white/10 rounded-xl p-5 text-center hover:border-white/20 transition flex flex-row md:flex-col items-center md:items-center gap-4 md:gap-0 text-left md:text-center">
                    <div class="w-12 h-12 rounded-full bg-blue-500/10 flex items-center justify-center text-blue-400 shrink-0 md:mx-auto md:mb-3">
                        <i class="fas fa-share-alt text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-white mb-0.5 md:mb-1">1. Bagikan</h3>
                        <p class="text-xs text-gray-400">Bagikan kode atau link referral ke teman Anda</p>
                    </div>
                </div>
                <div class="bg-[#111] border border-white/10 rounded-xl p-5 text-center hover:border-white/20 transition flex flex-row md:flex-col items-center md:items-center gap-4 md:gap-0 text-left md:text-center">
                    <div class="w-12 h-12 rounded-full bg-purple-500/10 flex items-center justify-center text-purple-400 shrink-0 md:mx-auto md:mb-3">
                        <i class="fas fa-user-plus text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-white mb-0.5 md:mb-1">2. Teman Daftar</h3>
                        <p class="text-xs text-gray-400">Teman mendaftar menggunakan kode Anda</p>
                    </div>
                </div>
                <div class="bg-[#111] border border-white/10 rounded-xl p-5 text-center hover:border-white/20 transition flex flex-row md:flex-col items-center md:items-center gap-4 md:gap-0 text-left md:text-center">
                    <div class="w-12 h-12 rounded-full bg-[#33E818]/10 flex items-center justify-center text-[#33E818] shrink-0 md:mx-auto md:mb-3">
                        <i class="fas fa-coins text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-white mb-0.5 md:mb-1">3. Dapat Bonus</h3>
                        <p class="text-xs text-gray-400">Dapatkan poin bonus untuk setiap teman aktif</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" class="hidden fixed bottom-10 left-1/2 -translate-x-1/2 px-6 py-3 bg-[#33E818] text-black font-bold rounded-full shadow-[0_0_20px_rgba(51,232,24,0.4)] z-[100] transition-all transform translate-y-10 opacity-0 scale-90">
    <span id="toastMessage">Berhasil disalin!</span>
</div>

<!-- Custom Confirmation Modal -->
<div id="confirmModal" class="fixed inset-0 z-[120] hidden flex items-center justify-center bg-black/90 backdrop-blur-sm p-4 opacity-0 transition-opacity duration-300">
    <div class="bg-[#111] border border-white/10 rounded-2xl max-w-sm w-full p-6 transform scale-95 transition-all duration-300 shadow-[0_0_50px_rgba(51,232,24,0.1)] text-center">

        <div class="w-16 h-16 bg-yellow-500/20 rounded-full flex items-center justify-center mx-auto mb-5 relative">
            <i class="fas fa-exclamation-triangle text-yellow-500 text-2xl"></i>
            <div class="absolute inset-0 bg-yellow-500 rounded-full animate-ping opacity-20"></div>
        </div>

        <h3 class="text-xl font-bold text-white mb-2">Apakah Anda yakin?</h3>
        <p class="text-gray-400 text-sm mb-6 leading-relaxed">
            Kode referral hanya bisa diubah <strong class="text-[#33E818]">1 kali selamanya</strong>. Pastikan kode yang Anda pilih sudah benar.
        </p>

        <div class="flex gap-3">
            <button onclick="closeConfirmModal()" class="flex-1 py-3 border border-white/20 text-white font-bold rounded-xl hover:bg-white/5 transition text-sm">
                Batal
            </button>
            <button onclick="submitReferralForm()" class="flex-1 py-3 bg-[#33E818] text-black font-bold rounded-xl hover:bg-[#2bc214] transition shadow-lg shadow-[#33E818]/20 text-sm">
                Ya, Simpan Selamanya
            </button>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            showToast('Berhasil disalin ke clipboard!');
        }).catch(err => {
            console.error('Failed to copy: ', err);
            // Fallback
            const textArea = document.createElement("textarea");
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            try {
                document.execCommand('copy');
                showToast('Berhasil disalin ke clipboard!');
            } catch (err) {
                console.error('Fallback copy failed', err);
                showToast('Gagal menyalin');
            }
            document.body.removeChild(textArea);
        });
    }

    function showToast(message) {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toastMessage');

        toastMessage.textContent = message;
        toast.classList.remove('hidden');

        // Small delay to allow display:block to apply before adding opacity class
        setTimeout(() => {
            toast.classList.remove('translate-y-10', 'opacity-0', 'scale-90');
        }, 10);

        setTimeout(() => {
            toast.classList.add('translate-y-10', 'opacity-0', 'scale-90');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 300);
        }, 3000);
    }

    // Modal Logic
    function confirmUpdateReferral() {
        // Validate input first
        const input = document.querySelector('input[name="code_referral"]');
        if (!input.checkValidity()) {
            input.reportValidity();
            return;
        }

        const modal = document.getElementById('confirmModal');
        modal.classList.remove('hidden');
        // Trigger reflow
        void modal.offsetWidth;
        modal.classList.remove('opacity-0');
        modal.querySelector('div').classList.remove('scale-95');
        modal.querySelector('div').classList.add('scale-100');
    }

    function closeConfirmModal() {
        const modal = document.getElementById('confirmModal');
        modal.classList.add('opacity-0');
        modal.querySelector('div').classList.add('scale-95');
        modal.querySelector('div').classList.remove('scale-100');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function submitReferralForm() {
        document.getElementById('customReferralForm').submit();
    }
</script>
<?= $this->endSection() ?>