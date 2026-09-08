<?= $this->extend('cwpa/layouts/main') ?>

<?= $this->section('content') ?>
<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fade-in 0.3s ease-out forwards;
    }
</style>
<?php
$photo = $cwpa['photo'] ?? '';
if (!empty($photo)) {
    if (strpos($photo, 'writable/') === 0) $photo = str_replace('writable/', '', $photo);
    $photoUrl = (strpos($photo, 'http') !== 0) ? base_url('file/' . $photo) : $photo;
} else {
    $photoUrl = 'https://ui-avatars.com/api/?name=' . urlencode($cwpa['name']) . '&background=33E818&color=000&size=200';
}
?>

<div class="container mx-auto max-w-7xl text-white pb-24 font-sans md:py-8">

    <!-- Notification -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="mb-6 p-4 bg-[#33E818]/20 border border-[#33E818]/20 rounded-2xl text-[#33E818] text-sm font-bold animate-pulse">
            <i class="fas fa-check-circle mr-2"></i> <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <!-- Mobile Header Title (Hidden on Desktop) -->
    <div class="pt-6 pb-6 text-center md:hidden">
        <h1 class="text-lg font-bold">Profil CWPA</h1>
    </div>

    <!-- Main View -->
    <div id="profileMainView" class="px-5 md:px-0">
        <div class="grid md:grid-cols-12 md:gap-8">

            <!-- Left Column: User Info & Setup -->
            <div class="md:col-span-4 lg:col-span-4 space-y-6">
                <!-- User Profile Info Card -->
                <div class="flex md:flex-col md:items-center md:text-center items-center gap-4 md:bg-[#111] md:p-6 md:rounded-2xl md:border md:border-white/5">
                    <!-- Avatar -->
                    <div class="relative">
                        <div class="w-20 h-20 md:w-32 md:h-32 rounded-full overflow-hidden border-2 border-[#222] md:border-[#33E818]/20">
                            <img src="<?= esc($photoUrl) ?>" alt="Avatar" class="w-full h-full object-cover">
                        </div>
                        <!-- Camera Icon for Upload -->
                        <button onclick="document.getElementById('photoInput').click()" class="absolute bottom-0 right-0 w-6 h-6 md:w-8 md:h-8 bg-[#222] border border-white/10 rounded-full flex items-center justify-center text-gray-400 hover:text-white hover:bg-[#33E818] transition group">
                            <i class="fas fa-camera text-[10px] md:text-xs group-hover:text-black"></i>
                        </button>
                    </div>

                    <!-- Info -->
                    <div class="flex-1 md:w-full">
                        <div class="flex items-center md:justify-center gap-2 mb-1">
                            <h2 class="text-lg md:text-xl font-bold text-white leading-tight"><?= esc($cwpa['name']) ?></h2>
                            <?php if ($cwpa['is_verified']): ?>
                                <i class="fas fa-check-circle text-[#33E818] text-sm" title="Terverifikasi"></i>
                            <?php endif; ?>
                        </div>

                        <div class="flex md:justify-center flex-wrap items-center gap-2 mb-1.5 mt-2">
                            <span class="px-2 py-0.5 rounded text-[10px] md:text-xs font-medium bg-[#33E818]/20 text-[#33E818] border border-[#33E818]/30 flex items-center">
                                <i class="fas fa-user-tie text-xs mr-1"></i>CWPA
                            </span>
                            <span class="text-gray-500 text-[10px] md:text-xs">Batch <?= esc($cwpa['batch'] ?? '-') ?></span>
                        </div>

                        <div class="flex md:justify-center items-center gap-1.5 text-gray-400 text-xs mt-2">
                            <i class="fas fa-envelope text-[#33E818]"></i>
                            <span class="truncate max-w-[200px]"><?= esc($cwpa['email']) ?></span>
                        </div>
                        <?php if (!empty($cwpa['phone'])): ?>
                        <div class="flex md:justify-center items-center gap-1.5 text-gray-400 text-xs mt-1">
                            <i class="fab fa-whatsapp text-[#33E818]"></i>
                            <span class="truncate max-w-[200px]"><?= esc($cwpa['phone']) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Expert Summary Card -->
                <div class="bg-[#111] border border-white/5 rounded-2xl p-5">
                    <h3 class="text-white font-bold text-sm mb-3">Expert Summary</h3>
                    
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="px-3 py-1 bg-white/5 border border-white/10 rounded-lg text-gray-400 text-[10px] font-bold uppercase tracking-wide">
                            <?= esc($cwpa['specialty'] ?? 'CWPA specialist') ?>
                        </span>
                        <span class="px-3 py-1 bg-white/5 border border-white/10 rounded-lg text-gray-400 text-[10px] font-bold uppercase tracking-wide">
                            XP: <?= esc($cwpa['experience'] ?? '-') ?>
                        </span>
                        <span class="px-3 py-1 bg-yellow-500/10 border border-yellow-500/20 rounded-lg text-yellow-500 text-[10px] font-bold uppercase tracking-wide">
                            <i class="fas fa-star mr-1"></i> <?= number_format($cwpa['rating'] ?? 5.0, 1) ?>
                        </span>
                        <span class="px-3 py-1 bg-[#33E818]/10 border border-[#33E818]/20 rounded-lg text-[#33E818] text-[10px] font-bold uppercase tracking-wide">
                            <i class="fas fa-layer-group mr-1"></i> Phase <?= $cwpa['current_phase'] ?? 1 ?>
                        </span>
                    </div>

                    <?php if (!empty($cwpa['bio'])): ?>
                    <div class="mb-4">
                        <p class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">About Me</p>
                        <p class="text-gray-300 text-xs leading-relaxed italic line-clamp-3">
                            "<?= esc($cwpa['bio']) ?>"
                        </p>
                    </div>
                    <?php endif; ?>

                    <?php 
                    $certifications = isset($cwpa['certifications']) ? explode("\n", $cwpa['certifications']) : [];
                    if (!empty($certifications) && count(array_filter($certifications)) > 0): 
                    ?>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-2">Sertifikasi</p>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach (array_slice($certifications, 0, 3) as $cert): ?>
                                <?php if (!empty(trim($cert))): ?>
                                    <span class="px-2 py-1 bg-white/5 border border-white/10 rounded text-[9px] text-gray-300 font-medium leading-none">
                                        <i class="fas fa-certificate text-[#33E818] mr-1"></i> <?= esc($cert) ?>
                                    </span>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <?php if (count($certifications) > 3): ?>
                                <span class="text-[9px] text-gray-500 font-bold self-center"> +<?= count($certifications) - 3 ?> More</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Copy Referral -->
                <div class="bg-[#111] border border-white/5 rounded-2xl p-5">
                    <h3 class="text-white font-bold text-sm mb-3">Kode Referral</h3>
                    <div class="flex gap-2">
                        <input type="text" id="referralCodeInput" value="<?= esc($cwpa['code_referral'] ?? '-') ?>" readonly class="bg-black/20 border border-white/10 rounded-xl px-4 py-2 text-xs font-bold text-[#33E818] flex-grow text-center focus:outline-none">
                        <button onclick="copyToClipboard('referralCodeInput', 'Kode Referral berhasil disalin!')" class="w-10 h-10 flex items-center justify-center bg-[#33E818]/10 text-[#33E818] rounded-xl hover:bg-[#33E818]/20 transition group">
                            <span class="hidden">Copy</span>
                            <i class="fas fa-copy text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column: Menu Links -->
            <div class="md:col-span-8 lg:col-span-8 mt-6 md:mt-0">
                <!-- Desktop Title -->
                <div class="hidden md:block mb-6 pt-2">
                    <h3 class="text-2xl font-bold text-white">Menu Profil</h3>
                    <p class="text-gray-400 text-sm">Kelola profil CWPA dan portofolio Anda</p>
                </div>

                <div class="space-y-3 md:bg-[#111] md:p-6 md:rounded-2xl md:border md:border-white/5">

                    <!-- Data Pribadi -->
                    <button onclick="toggleEditView(true)" class="w-full flex items-center justify-between p-4 rounded-xl hover:bg-white/5 transition group border border-transparent hover:border-white/5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-[#33E818]/10 flex items-center justify-center text-[#33E818]">
                                <i class="fas fa-user-edit text-lg"></i>
                            </div>
                            <div class="text-left">
                                <span class="block text-sm font-medium text-gray-200 group-hover:text-white">Edit Profil</span>
                                <span class="hidden md:block text-xs text-gray-500">Perbarui informasi, bio, dan keahlian Anda</span>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-600 text-xs"></i>
                    </button>

                    <!-- Lihat Profil Publik -->
                    <a href="<?= base_url('cwpa/' . ($cwpa['slug'] ?? $cwpa['id'])) ?>" target="_blank" class="w-full flex items-center justify-between p-4 rounded-xl hover:bg-white/5 transition group border border-transparent hover:border-white/5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-[#33E818]/10 flex items-center justify-center text-[#33E818]">
                                <i class="fas fa-external-link-alt text-lg"></i>
                            </div>
                            <div class="text-left">
                                <span class="block text-sm font-medium text-gray-200 group-hover:text-white">Lihat Profil Publik</span>
                                <span class="hidden md:block text-xs text-gray-500">Lihat tampilan profil Anda yang dapat dilihat klien</span>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-600 text-xs"></i>
                    </a>

                    <div class="h-px bg-white/5 my-2"></div>

                    <!-- Social Assets -->
                    <div class="px-4 py-2">
                        <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">Social Assets</span>
                    </div>

                    <?php if (!empty($cwpa['instagram'])): ?>
                    <a href="<?= esc(\App\Models\CwpaModel::instagramUrl($cwpa['instagram'])) ?>" target="_blank" rel="noopener noreferrer" class="w-full flex items-center justify-between p-4 rounded-xl hover:bg-white/5 transition group border border-transparent hover:border-white/5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-pink-500/10 flex items-center justify-center text-pink-500">
                                <i class="fab fa-instagram text-lg"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-200 group-hover:text-white">Instagram (@<?= esc(\App\Models\CwpaModel::normalizeInstagram($cwpa['instagram'])) ?>)</span>
                        </div>
                        <i class="fas fa-external-link-alt text-gray-600 text-xs group-hover:text-pink-500"></i>
                    </a>
                    <?php endif; ?>

                    <?php if (!empty($cwpa['youtube'])): ?>
                    <a href="https://youtube.com/channel/<?= esc($cwpa['youtube']) ?>" target="_blank" class="w-full flex items-center justify-between p-4 rounded-xl hover:bg-white/5 transition group border border-transparent hover:border-white/5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center text-red-500">
                                <i class="fab fa-youtube text-lg"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-200 group-hover:text-white">YouTube</span>
                        </div>
                        <i class="fas fa-external-link-alt text-gray-600 text-xs group-hover:text-red-500"></i>
                    </a>
                    <?php endif; ?>

                    <?php if (!empty($cwpa['mql5_widget_url'])): ?>
                    <a href="<?= esc($cwpa['mql5_widget_url']) ?>" target="_blank" class="w-full flex items-center justify-between p-4 rounded-xl hover:bg-white/5 transition group border border-transparent hover:border-white/5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-blue-500/10 flex items-center justify-center text-blue-400">
                                <i class="fas fa-chart-line text-lg"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-200 group-hover:text-white">MQL5 Signal</span>
                        </div>
                        <i class="fas fa-external-link-alt text-gray-600 text-xs group-hover:text-blue-400"></i>
                    </a>
                    <?php endif; ?>

                    <div class="h-px bg-white/5 my-2"></div>

                    <!-- Syarat dan Ketentuan -->
                    <a href="<?= base_url('syarat-ketentuan') ?>" target="_blank" rel="noopener noreferrer" class="w-full flex items-center justify-between p-4 rounded-xl hover:bg-white/5 transition group border border-transparent hover:border-white/5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-[#33E818]/10 flex items-center justify-center text-[#33E818]">
                                <i class="fas fa-file-contract text-lg"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-200 group-hover:text-white">Syarat dan Ketentuan</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-600 text-xs"></i>
                    </a>

                    <!-- Logout -->
                    <a href="<?= base_url('logout') ?>" class="w-full flex items-center justify-between p-4 rounded-xl hover:bg-red-500/10 transition group border border-transparent hover:border-red-500/20">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center text-red-500">
                                <i class="fas fa-sign-out-alt text-lg"></i>
                            </div>
                            <span class="text-sm font-medium text-red-400 group-hover:text-red-300">Keluar</span>
                        </div>
                    </a>

                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Edit View -->
    <div id="profileEditView" class="hidden px-5 md:px-0">
        <div class="max-w-4xl mx-auto bg-[#121212] border border-white/5 rounded-2xl p-6 md:p-10">
            <div class="flex items-center gap-4 mb-8">
                <button onclick="toggleEditView(false)" class="w-10 h-10 flex items-center justify-center bg-white/5 rounded-full hover:bg-white/10 transition group">
                    <i class="fas fa-arrow-left text-sm text-gray-400 group-hover:text-white"></i>
                </button>
                <div>
                    <h3 class="text-xl font-bold text-white">Edit Profil CWPA</h3>
                    <p class="text-xs text-gray-500">Perbarui informasi, bio, dan keahlian Anda</p>
                </div>
            </div>

            <form action="<?= base_url('cwpa/dashboard/profile/update') ?>" method="POST" class="space-y-6">
                <?= csrf_field() ?>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs text-gray-400 uppercase font-bold tracking-wider">Nama Lengkap</label>
                        <input type="text" name="name" value="<?= esc($cwpa['name']) ?>" required class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-[#33E818] focus:ring-1 focus:ring-[#33E818] outline-none text-white transition">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs text-gray-400 uppercase font-bold tracking-wider">Batch Angkatan</label>
                        <input type="text" name="batch" value="<?= esc($cwpa['batch'] ?? '') ?>" placeholder="e.g. Batch 12" class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-[#33E818] focus:ring-1 focus:ring-[#33E818] outline-none text-white transition">
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs text-gray-400 uppercase font-bold tracking-wider">Email</label>
                        <input type="email" name="email" value="<?= esc($cwpa['email']) ?>" required class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-[#33E818] focus:ring-1 focus:ring-[#33E818] outline-none text-white transition">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs text-gray-400 uppercase font-bold tracking-wider">WhatsApp / Phone</label>
                        <input type="text" name="phone" value="<?= esc($cwpa['phone'] ?? '') ?>" placeholder="0812..." class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-[#33E818] focus:ring-1 focus:ring-[#33E818] outline-none text-white transition">
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs text-gray-400 uppercase font-bold tracking-wider">Specialty / Keahlian</label>
                        <select name="specialty" class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-[#33E818] focus:ring-1 focus:ring-[#33E818] outline-none text-white transition appearance-none">
                            <option value="">Pilih Keahlian Utama</option>
                            <?php foreach ($specialties ?? [] as $spec): ?>
                                <option value="<?= esc($spec) ?>" <?= ($cwpa['specialty'] ?? '') === $spec ? 'selected' : '' ?>><?= esc($spec) ?></option>
                            <?php endforeach; ?>
                            <!-- Fallback options if specialties array is not passed properly -->
                            <?php if (empty($specialties)): ?>
                                <option value="Forex Trader" <?= ($cwpa['specialty'] ?? '') === 'Forex Trader' ? 'selected' : '' ?>>Forex Trader</option>
                                <option value="Crypto Trader" <?= ($cwpa['specialty'] ?? '') === 'Crypto Trader' ? 'selected' : '' ?>>Crypto Trader</option>
                                <option value="Stock Trader" <?= ($cwpa['specialty'] ?? '') === 'Stock Trader' ? 'selected' : '' ?>>Stock Trader</option>
                                <option value="Fund Manager" <?= ($cwpa['specialty'] ?? '') === 'Fund Manager' ? 'selected' : '' ?>>Fund Manager</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs text-gray-400 uppercase font-bold tracking-wider">Pengalaman (Tahun)</label>
                        <input type="text" name="experience" value="<?= esc($cwpa['experience'] ?? '') ?>" placeholder="e.g. 5+ Years" class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-[#33E818] focus:ring-1 focus:ring-[#33E818] outline-none text-white transition">
                    </div>
                </div>

                <div class="grid lg:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs text-gray-400 uppercase font-bold tracking-wider">Instagram</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold">@</span>
                            <input type="text" name="instagram" value="<?= esc($cwpa['instagram'] ?? '') ?>" placeholder="username" class="w-full pl-9 pr-4 py-3 bg-black/20 border border-white/10 rounded-xl text-sm focus:border-[#33E818] focus:ring-1 focus:ring-[#33E818] outline-none text-white transition">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs text-gray-400 uppercase font-bold tracking-wider">YouTube Channel ID</label>
                        <input type="text" name="youtube" value="<?= esc($cwpa['youtube'] ?? '') ?>" placeholder="e.g. UCJGrPU..." class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-[#33E818] focus:ring-1 focus:ring-[#33E818] outline-none text-white transition">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs text-gray-400 uppercase font-bold tracking-wider">MQL5 Signal URL</label>
                        <input type="text" name="mql5_widget_url" value="<?= esc($cwpa['mql5_widget_url'] ?? '') ?>" placeholder="https://www.mql5.com/..." class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-[#33E818] focus:ring-1 focus:ring-[#33E818] outline-none text-white transition">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs text-gray-400 uppercase font-bold tracking-wider">Bio Singkat</label>
                    <textarea name="bio" rows="4" placeholder="Tuliskan pengalaman atau moto trading Anda..." class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-[#33E818] focus:ring-1 focus:ring-[#33E818] outline-none text-white transition resize-none"><?= esc($cwpa['bio'] ?? '') ?></textarea>
                </div>

                <div class="space-y-2">
                    <label class="text-xs text-gray-400 uppercase font-bold tracking-wider">Sertifikasi Lainnya (Satu per baris)</label>
                    <textarea name="certifications" rows="3" placeholder="Contoh:&#10;Certified Technical Analyst&#10;Professional Fund Manager" class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-[#33E818] focus:ring-1 focus:ring-[#33E818] outline-none text-white transition resize-none font-mono"><?= esc($cwpa['certifications'] ?? '') ?></textarea>
                </div>

                <div class="pt-8 border-t border-white/5">
                    <h4 class="text-sm font-bold text-white mb-6 flex items-center gap-2">
                        <i class="fas fa-shield-alt text-[#33E818]"></i> Keamanan
                    </h4>
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-xs text-gray-400">Ganti Password (Opsional)</label>
                            <input type="password" name="password" placeholder="Isi hanya jika ingin mengganti" class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-[#33E818] focus:ring-1 focus:ring-[#33E818] outline-none text-white transition">
                            <p class="text-[10px] text-gray-500 italic mt-1">Kosongkan jika tidak ingin mengubah password.</p>
                        </div>
                    </div>
                </div>

                <div class="pt-6 flex items-center gap-4">
                    <button type="button" onclick="toggleEditView(false)" class="flex-1 py-3.5 text-sm font-bold text-gray-400 hover:text-white bg-white/5 hover:bg-white/10 rounded-xl transition">
                        Batal
                    </button>
                    <button type="submit" class="flex-[2] bg-[#33E818] text-black font-bold py-3.5 rounded-xl hover:bg-[#2bc214] transition shadow-lg shadow-[#33E818]/20 transform hover:-translate-y-0.5">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Hidden Photo Form -->
    <form id="photoForm" action="<?= base_url('cwpa/dashboard/profile/photo') ?>" method="POST" enctype="multipart/form-data" class="hidden">
        <?= csrf_field() ?>
        <input type="file" name="photo" id="photoInput" accept="image/*" onchange="this.form.submit()">
    </form>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function toggleEditView(show) {
        const mainView = document.getElementById('profileMainView');
        const editView = document.getElementById('profileEditView');

        if (show) {
            mainView.classList.add('hidden');
            editView.classList.remove('hidden');
        } else {
            mainView.classList.remove('hidden');
            editView.classList.add('hidden');
        }
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    function copyToClipboard(elementId, message) {
        const el = document.getElementById(elementId);
        let text = el.value;

        if (navigator.clipboard) {
            navigator.clipboard.writeText(text).then(() => {
                alert(message);
            }).catch(err => {
                console.error('Failed to copy text: ', err);
            });
        } else {
            el.select();
            document.execCommand('copy');
            alert(message);
        }
    }
</script>
<?= $this->endSection() ?>
