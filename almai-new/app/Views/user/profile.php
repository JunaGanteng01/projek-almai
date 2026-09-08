<?= $this->extend('user/partials/layout') ?>

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
// Use variables passed from controller
$userLevelId = (int)($user['level_id'] ?? 1);
$levelName = 'Member';
$badgeClass = 'bg-gray-800 text-gray-400';
$badgeIcon = '';

if ($userLevelId == \App\Models\LevelModel::LEVEL_PRO) {
    $levelName = 'Member Pro';
    $badgeClass = 'bg-[#4B3B00] text-[#FFD700] border border-[#FFD700]/30';
    $badgeIcon = '<i class="fas fa-crown text-xs mr-1"></i>';
}
if ($userLevelId == \App\Models\LevelModel::LEVEL_CWPA) {
    $levelName = 'CWPA';
    $badgeClass = 'bg-blue-900/30 text-blue-400 border border-blue-400/30';
    $badgeIcon = '<i class="fas fa-user-check text-xs mr-1"></i>';
}
if ($userLevelId == \App\Models\LevelModel::LEVEL_WPA) {
    $levelName = 'WPA';
    $badgeClass = 'bg-purple-900/30 text-purple-400 border border-purple-400/30';
    $badgeIcon = '<i class="fas fa-user-tie text-xs mr-1"></i>';
}

$avatarUrl = $user['avatar'] ?? null;
?>

<div class="container mx-auto max-w-7xl text-white pb-24 font-sans md:py-8">

    <!-- Mobile Header Title (Hidden on Desktop) -->
    <div class="pt-6 pb-6 text-center md:hidden">
        <h1 class="text-lg font-bold">Profil</h1>
    </div>

    <!-- Main Menu View -->
    <div id="profileMainView" class="animate-fade-in <?= isset($isEdit) && $isEdit ? 'hidden' : '' ?>">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 lg:gap-8 max-w-[1400px] mx-auto">

            <!-- Left Column: User Info & Upgrade -->
            <div class="md:col-span-4 lg:col-span-4 space-y-6">
                <!-- User Profile Info Card -->
                <div class="flex md:flex-col md:items-center md:text-center items-center gap-4 md:bg-[#111] md:p-6 md:rounded-2xl md:border md:border-white/5">
                    <!-- Avatar -->
                    <div class="relative">
                        <div class="w-20 h-20 md:w-32 md:h-32 rounded-full overflow-hidden border-2 border-[#222] md:border-[#33E818]/20">
                            <?php if ($avatarUrl): ?>
                                <img src="<?= base_url('file/' . $avatarUrl) ?>" alt="Avatar" class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center bg-[#222] text-gray-500">
                                    <i class="fas fa-user text-3xl md:text-5xl"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <!-- Camera Icon for Upload -->
                        <button onclick="document.getElementById('avatarInput').click()" class="absolute bottom-0 right-0 w-6 h-6 md:w-8 md:h-8 bg-[#222] border border-white/10 rounded-full flex items-center justify-center text-gray-400 hover:text-white hover:bg-[#33E818] transition group">
                            <i class="fas fa-camera text-[10px] md:text-xs group-hover:text-black"></i>
                        </button>
                    </div>

                    <!-- Info -->
                    <div class="flex-1 md:w-full">
                        <h2 class="text-lg md:text-xl font-bold text-white leading-tight mb-1"><?= esc($user['name']) ?></h2>

                        <div class="flex md:justify-center flex-wrap items-center gap-2 mb-1.5 mt-2">
                            <span class="px-2 py-0.5 rounded text-[10px] md:text-xs font-medium <?= $badgeClass ?> flex items-center">
                                <?= $badgeIcon ?><?= $levelName ?>
                            </span>
                            <span class="text-gray-500 text-[10px] md:text-xs">Member sejak <?= date('M Y', strtotime($user['created_at'])) ?></span>
                        </div>

                        <div class="flex md:justify-center items-center gap-1.5 text-gray-400 text-xs mt-2">
                            <i class="fas fa-envelope text-[#33E818]"></i>
                            <span class="truncate max-w-[200px]"><?= esc($user['email']) ?></span>
                        </div>
                    </div>
                </div>

                <?php
                $isEmailVerified = !empty($user['email_verified_at']);
                $isWaVerified = !empty($user['otp_status']);
                $isAddressFilled = !empty($user['address']);

                $completedCount = 0;
                if ($isEmailVerified) $completedCount++;
                if ($isWaVerified) $completedCount++;
                if ($isAddressFilled) $completedCount++;

                $progressPercent = round(($completedCount / 3) * 100);
                ?>

                <!-- Status Kelengkapan Profil -->
                <div class="bg-[#111] border border-white/5 rounded-2xl p-5">
                    <h3 class="text-white font-bold text-sm mb-3">Kelengkapan Profil</h3>
                    
                    <div class="mb-4">
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-gray-400">Progress</span>
                            <span class="text-[#33E818] font-bold"><?= $progressPercent ?>%</span>
                        </div>
                        <div class="w-full bg-white/10 rounded-full h-1.5">
                            <div class="bg-[#33E818] h-1.5 rounded-full transition-all duration-500" style="width: <?= $progressPercent ?>%"></div>
                        </div>
                    </div>

                    <?php if ($progressPercent < 100): ?>
                        <ul class="space-y-3">
                            <li class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full flex items-center justify-center <?= $isEmailVerified ? 'bg-[#33E818]/20 text-[#33E818]' : 'bg-white/5 text-gray-500' ?>">
                                        <i class="fas <?= $isEmailVerified ? 'fa-check' : 'fa-envelope' ?> text-[10px]"></i>
                                    </div>
                                    <span class="text-xs <?= $isEmailVerified ? 'text-gray-200' : 'text-gray-500' ?>">Verifikasi Email</span>
                                </div>
                                <?php if (!$isEmailVerified): ?>
                                    <?php if (empty($user['email'])): ?>
                                        <a href="<?= base_url('user/profile/edit') ?>" class="text-[10px] text-[#33E818] hover:underline">Lengkapi</a>
                                    <?php else: ?>
                                        <button type="button" onclick="sendProfileOtp('email', this)" class="text-[10px] text-[#33E818] hover:underline">Verifikasi</button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </li>
                            <li class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full flex items-center justify-center <?= $isWaVerified ? 'bg-[#33E818]/20 text-[#33E818]' : 'bg-white/5 text-gray-500' ?>">
                                        <i class="fab <?= $isWaVerified ? 'fa-whatsapp' : 'fa-whatsapp' ?> text-[10px]"></i>
                                    </div>
                                    <span class="text-xs <?= $isWaVerified ? 'text-gray-200' : 'text-gray-500' ?>">Verifikasi WhatsApp</span>
                                </div>
                                <?php if (!$isWaVerified): ?>
                                    <?php if (empty($user['phone'])): ?>
                                        <a href="<?= base_url('user/profile/edit') ?>" class="text-[10px] text-[#33E818] hover:underline">Lengkapi</a>
                                    <?php else: ?>
                                        <button type="button" onclick="sendProfileOtp('whatsapp', this)" class="text-[10px] text-[#33E818] hover:underline">Verifikasi</button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </li>
                            <li class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full flex items-center justify-center <?= $isAddressFilled ? 'bg-[#33E818]/20 text-[#33E818]' : 'bg-white/5 text-gray-500' ?>">
                                        <i class="fas <?= $isAddressFilled ? 'fa-check' : 'fa-map-marker-alt' ?> text-[10px]"></i>
                                    </div>
                                    <span class="text-xs <?= $isAddressFilled ? 'text-gray-200' : 'text-gray-500' ?>">Alamat Lengkap</span>
                                </div>
                                <?php if (!$isAddressFilled): ?>
                                    <a href="<?= base_url('user/profile/edit') ?>" class="text-[10px] text-[#33E818] hover:underline">Lengkapi</a>
                                <?php endif; ?>
                            </li>
                        </ul>
                    <?php else: ?>
                        <div class="text-center py-2 mt-2 border-t border-white/5">
                            <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-[#33E818]/20 text-[#33E818] mb-2 mt-2">
                                <i class="fas fa-check text-lg"></i>
                            </div>
                            <p class="text-xs text-[#33E818] font-bold">Profil Anda sudah 100% lengkap!</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Upgrade Card (Only if NOT Pro/Higher) -->
                <?php if ($userLevelId == \App\Models\LevelModel::LEVEL_USER): ?>
                    <div class="relative w-full rounded-2xl p-5 overflow-hidden group" style="background: linear-gradient(135deg, #051a05 0%, #000000 100%); border: 1px solid #1a331a;">
                        <!-- Background Decoration -->
                        <div class="absolute right-0 top-0 bottom-0 w-1/3 opacity-20 pointer-events-none group-hover:opacity-30 transition" style="background: radial-gradient(circle at center, #33E818 0%, transparent 70%);"></div>

                        <div class="relative z-10 pr-24">
                            <h3 class="text-white font-bold text-base mb-1">Upgrade ke PRO</h3>
                            <p class="text-gray-400 text-[10px] mb-3 leading-relaxed">Dapatkan akses eksklusif ke fitur premium</p>

                            <ul class="space-y-1.5 mb-4">
                                <li class="flex items-start gap-2">
                                    <i class="fas fa-check text-[#33E818] text-[10px] mt-0.5"></i>
                                    <span class="text-gray-300 text-[10px]">Akses Portofolio</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="fas fa-check text-[#33E818] text-[10px] mt-0.5"></i>
                                    <span class="text-gray-300 text-[10px]">Akses penuh tools premium</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="fas fa-check text-[#33E818] text-[10px] mt-0.5"></i>
                                    <span class="text-gray-300 text-[10px]">Priority support</span>
                                </li>
                            </ul>

                            <a href="<?= base_url('user/kyc') ?>" class="inline-block px-5 py-2 bg-[#33E818] hover:bg-[#2bc214] text-black text-xs font-bold rounded-lg transition shadow-[0_0_15px_rgba(51,232,24,0.3)] hover:shadow-[0_0_25px_rgba(51,232,24,0.5)] transform hover:-translate-y-0.5">
                                Upgrade Sekarang
                            </a>
                        </div>

                        <!-- Mascot Image -->
                        <div class="absolute right-2 top-1/2 -translate-y-1/2 w-28 h-28 pointer-events-none">
                            <img src="<?= base_url('images/angel.png') ?>" alt="Upgrade" class="w-full h-full object-contain drop-shadow-2xl grayscale-[30%] group-hover:grayscale-0 transition">
                        </div>
                    </div>
                <?php elseif ($userLevelId == \App\Models\LevelModel::LEVEL_PRO): ?>
                    <div class="relative w-full rounded-2xl p-5 overflow-hidden group" style="background: linear-gradient(135deg, #051a05 0%, #000000 100%); border: 1px solid #1a331a;">
                        <!-- Background Decoration -->
                        <div class="absolute right-0 top-0 bottom-0 w-1/3 opacity-20 pointer-events-none group-hover:opacity-30 transition" style="background: radial-gradient(circle at center, #33E818 0%, transparent 70%);"></div>

                        <div class="relative z-10 pr-24">
                            <h3 class="text-white font-bold text-base mb-1">Upgrade ke CWPA</h3>
                            <p class="text-gray-400 text-[10px] mb-3 leading-relaxed">Raih sertifikasi profesi & karir impian</p>

                            <ul class="space-y-1.5 mb-4">
                                <li class="flex items-start gap-2">
                                    <i class="fas fa-check text-[#33E818] text-[10px] mt-0.5"></i>
                                    <span class="text-gray-300 text-[10px]">Gelar profesi CWPA</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="fas fa-check text-[#33E818] text-[10px] mt-0.5"></i>
                                    <span class="text-gray-300 text-[10px]">Lisensi praktik resmi</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="fas fa-check text-[#33E818] text-[10px] mt-0.5"></i>
                                    <span class="text-gray-300 text-[10px]">Akses komunitas eksklusif</span>
                                </li>
                            </ul>

                            <a href="<?= base_url('user/upgrade-cwpa') ?>" class="inline-block px-5 py-2 bg-[#33E818] hover:bg-[#2bc214] text-black text-xs font-bold rounded-lg transition shadow-[0_0_15px_rgba(51,232,24,0.3)] hover:shadow-[0_0_25px_rgba(51,232,24,0.5)] transform hover:-translate-y-0.5">
                                Formulir CWPA
                            </a>
                        </div>

                        <!-- Mascot Image -->
                        <div class="absolute right-2 top-1/2 -translate-y-1/2 w-28 h-28 pointer-events-none">
                            <img src="<?= base_url('images/angel.png') ?>" alt="Upgrade" class="w-full h-full object-contain drop-shadow-2xl grayscale-[30%] group-hover:grayscale-0 transition">
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right Column: Menu Links -->
            <div class="md:col-span-8 lg:col-span-8 mt-6 md:mt-0">
                <!-- Desktop Title -->
                <div class="hidden md:block mb-6 pt-2">
                    <h3 class="text-2xl font-bold text-white">Menu Profil</h3>
                    <p class="text-gray-400 text-sm">Kelola akun dan pengaturan Anda</p>
                </div>

                <div class="space-y-3 md:bg-[#111] md:p-6 md:rounded-2xl md:border md:border-white/5">

                    <!-- Data Pribadi -->
                    <a href="<?= base_url('user/profile/edit') ?>" class="w-full flex items-center justify-between p-4 rounded-xl hover:bg-white/5 transition group border border-transparent hover:border-white/5 relative bg-[#151515] md:bg-transparent">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-[#33E818]/10 flex items-center justify-center text-[#33E818]">
                                <i class="fas fa-user-circle text-lg"></i>
                            </div>
                            <div class="text-left">
                                <span class="block text-sm font-medium text-gray-200 group-hover:text-white">Data Pribadi</span>
                                <span class="hidden md:block text-xs text-gray-500">Edit nama, email, dan password</span>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-600 text-xs"></i>
                    </a>

                    <!-- Ajak Teman (Referral Wrapper) -->
                    <a href="<?= base_url('user/referral') ?>" class="w-full flex items-center justify-between p-4 rounded-xl hover:bg-white/5 transition group border border-transparent hover:border-white/5 relative bg-[#151515] md:bg-transparent">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-[#33E818]/10 flex items-center justify-center text-[#33E818]">
                                <i class="fas fa-user-friends text-lg"></i>
                            </div>
                            <div class="text-left">
                                <span class="block text-sm font-medium text-gray-200 group-hover:text-white">Ajak Teman</span>
                                <span class="hidden md:block text-xs text-gray-500">Bagikan kode referral dan dapatkan bonus</span>
                                <span class="md:hidden text-[10px] text-gray-500">Dapatkan bonus referral</span>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-600 text-xs transition-transform duration-300"></i>
                    </a>

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

                    <!-- Kebijakan Privasi -->
                    <a href="<?= base_url('kebijakan-privasi') ?>" target="_blank" rel="noopener noreferrer" class="w-full flex items-center justify-between p-4 rounded-xl hover:bg-white/5 transition group border border-transparent hover:border-white/5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-[#33E818]/10 flex items-center justify-center text-[#33E818]">
                                <i class="fas fa-shield-alt text-lg"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-200 group-hover:text-white">Kebijakan Privasi</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-600 text-xs"></i>
                    </a>

                    <!-- FAQs -->
                    <a href="https://almai.id/faq" target="_blank" rel="noopener noreferrer" class="w-full flex items-center justify-between p-4 rounded-xl hover:bg-white/5 transition group border border-transparent hover:border-white/5">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-[#33E818]/10 flex items-center justify-center text-[#33E818]">
                                <i class="fas fa-question-circle text-lg"></i>
                            </div>
                            <span class="text-sm font-medium text-gray-200 group-hover:text-white">FAQs</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-600 text-xs"></i>
                    </a>

                    <div class="h-px bg-white/5 my-2"></div>

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

    <!-- Edit Data Pribadi View -->
    <div id="profileEditView" class="animate-slide-up <?= isset($isEdit) && $isEdit ? '' : 'hidden' ?>">
        <div class="max-w-3xl mx-auto bg-[#121212] border border-white/5 rounded-2xl p-6 md:p-10">
            <div class="flex items-center gap-4 mb-8">
                <a href="<?= base_url('user/profile') ?>" class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 hover:text-white hover:bg-white/10 transition">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h3 class="text-xl font-bold text-white">Edit Data Pribadi</h3>
                    <p class="text-xs text-gray-500">Perbarui informasi akun Anda</p>
                </div>
            </div>

            <form action="<?= base_url('user/profile/update') ?>" method="POST" class="space-y-6">
                <?= csrf_field() ?>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs text-gray-400 uppercase font-bold tracking-wider">Nama Lengkap</label>
                        <input type="text" name="name" value="<?= esc($user['name']) ?>" class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-[#33E818] focus:ring-1 focus:ring-[#33E818] outline-none text-white transition">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs text-gray-400 uppercase font-bold tracking-wider">Email</label>
                        <input type="email" name="email" value="<?= esc($user['email']) ?>" placeholder="Masukkan email Anda" class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-[#33E818] focus:ring-1 focus:ring-[#33E818] outline-none text-white transition">
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs text-gray-400 uppercase font-bold tracking-wider">WhatsApp</label>
                        <div class="flex gap-2">
                            <input type="tel" name="phone" value="<?= esc($user['phone']) ?>" readonly class="w-full bg-white/5 border border-transparent rounded-xl px-4 py-3 text-sm text-gray-500 cursor-not-allowed">
                            <?php if (empty($user['otp_status'])): ?>
                                <button type="button" onclick="sendProfileOtp('whatsapp', this)" id="btnVerifyWa" class="px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-sm font-bold text-[#33E818] hover:bg-white/10 transition whitespace-nowrap flex items-center gap-2">
                                    <i class="fab fa-whatsapp"></i> Verifikasi
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs text-gray-400 uppercase font-bold tracking-wider">Alamat</label>
                    <textarea name="address" rows="3" class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-[#33E818] focus:ring-1 focus:ring-[#33E818] outline-none text-white transition resize-none"><?= esc($user['address']) ?></textarea>
                </div>

                <div class="pt-8 border-t border-white/5">
                    <h4 class="text-sm font-bold text-white mb-6 flex items-center gap-2">
                        <i class="fas fa-lock text-[#33E818]"></i> Keamanan
                    </h4>
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <label class="text-xs text-gray-400">Password Lama</label>
                            <input type="password" name="old_password" placeholder="••••••••" class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-[#33E818] focus:ring-1 focus:ring-[#33E818] outline-none text-white transition">
                        </div>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs text-gray-400">Password Baru</label>
                                <input type="password" name="new_password" placeholder="••••••••" class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-[#33E818] focus:ring-1 focus:ring-[#33E818] outline-none text-white transition">
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs text-gray-400">Konfirmasi Password</label>
                                <input type="password" name="confirm_password" placeholder="••••••••" class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-[#33E818] focus:ring-1 focus:ring-[#33E818] outline-none text-white transition">
                            </div>
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

    <!-- Hidden Avatar Form -->
    <form id="avatarForm" action="<?= base_url('user/profile/avatar') ?>" method="POST" enctype="multipart/form-data" class="hidden">
        <?= csrf_field() ?>
        <input type="file" name="avatar" id="avatarInput" accept="image/*" onchange="this.form.submit()">
    </form>

</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- OTP Modal -->
<div id="otpModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-[#1a1a1a] border border-white/10 rounded-2xl p-6 md:p-8 max-w-md w-full relative">
        <button onclick="closeOtpModal()" class="absolute top-4 right-4 text-gray-500 hover:text-white transition">
            <i class="fas fa-times"></i>
        </button>
        <div class="text-center mb-6">
            <div id="otpIconWrapper" class="w-16 h-16 bg-[#33E818]/10 rounded-full flex items-center justify-center mx-auto mb-4 text-[#33E818]">
                <i class="fas fa-shield-alt text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Verifikasi OTP</h3>
            <p class="text-sm text-gray-400">Masukkan kode OTP yang telah dikirim ke <span id="otpChannelDisplay" class="font-bold text-white"></span> Anda.</p>
        </div>
        <div class="space-y-4">
            <input type="hidden" id="otpChannel" value="">
            <input type="text" id="otpCode" placeholder="Masukkan 6 Digit OTP" class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-center text-lg tracking-widest font-mono focus:border-[#33E818] focus:ring-1 focus:ring-[#33E818] outline-none text-white transition" maxlength="6">
            <button onclick="verifyProfileOtp()" id="btnVerifyOtp" class="w-full py-3 bg-[#33E818] text-black font-bold rounded-xl hover:bg-[#28c912] transition">
                Verifikasi
            </button>
        </div>
    </div>
</div>

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



    function closeOtpModal() {
        document.getElementById('otpModal').classList.add('hidden');
        document.getElementById('otpModal').classList.remove('flex');
    }

    async function sendProfileOtp(channel, btnElement = null) {
        let btn = btnElement;
        if (!btn) {
            btn = channel === 'email' ? document.getElementById('btnVerifyEmail') : document.getElementById('btnVerifyWa');
        }
        
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> ...';
        btn.disabled = true;

        let inputValue = '';
        if (channel === 'email') {
            const emailInput = document.querySelector('input[name="email"]');
            if (emailInput) inputValue = emailInput.value;
        } else if (channel === 'whatsapp') {
            const phoneInput = document.querySelector('input[name="phone"]');
            if (phoneInput) inputValue = phoneInput.value;
        }

        try {
            const response = await fetch('<?= base_url('user/profile/send-otp') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
                },
                body: JSON.stringify({ channel: channel, value: inputValue })
            });
            const data = await response.json();

            if (data.success) {
                document.getElementById('otpChannel').value = channel;
                document.getElementById('otpChannelDisplay').innerText = channel === 'email' ? 'Email' : 'WhatsApp';
                document.getElementById('otpIconWrapper').innerHTML = channel === 'email' ? '<i class="fas fa-envelope text-2xl"></i>' : '<i class="fab fa-whatsapp text-2xl"></i>';
                
                const modal = document.getElementById('otpModal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                modal.style.display = 'flex';
                modal.style.visibility = 'visible';
                modal.style.opacity = '1';
                
                document.getElementById('otpCode').value = '';
                setTimeout(() => { document.getElementById('otpCode').focus(); }, 100);

                if (typeof showToast === 'function') {
                    showToast('Kode OTP berhasil dikirim ke ' + channel + ' Anda!', 'success');
                } else {
                    setTimeout(() => alert('Kode OTP berhasil dikirim ke ' + channel + ' Anda!'), 100);
                }
            } else {
                if (typeof showToast === 'function') {
                    showToast(data.message, 'error');
                } else {
                    alert('Gagal: ' + data.message);
                }
            }
        } catch (err) {
            alert('Terjadi kesalahan koneksi.');
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }

    async function verifyProfileOtp() {
        const btn = document.getElementById('btnVerifyOtp');
        const code = document.getElementById('otpCode').value;
        const channel = document.getElementById('otpChannel').value;

        let inputValue = '';
        if (channel === 'email') {
            const emailInput = document.querySelector('input[name="email"]');
            if (emailInput) inputValue = emailInput.value;
        } else if (channel === 'whatsapp') {
            const phoneInput = document.querySelector('input[name="phone"]');
            if (phoneInput) inputValue = phoneInput.value;
        }

        if (code.length < 6) {
            alert('Masukkan 6 digit kode OTP');
            return;
        }

        const originalText = btn.innerHTML;
        btn.innerHTML = 'Memverifikasi...';
        btn.disabled = true;

        try {
            const response = await fetch('<?= base_url('user/profile/verify-otp') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
                },
                body: JSON.stringify({ otp: code, channel: channel, value: inputValue })
            });
            const data = await response.json();

            if (data.success) {
                alert(data.message);
                window.location.reload();
            } else {
                alert('Gagal: ' + data.message);
            }
        } catch (err) {
            alert('Terjadi kesalahan koneksi.');
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }

    function copyToClipboard(elementId, message) {
        // Handle span or input
        const el = document.getElementById(elementId);
        let text = el.innerText.trim();
        if (!text) text = el.value;

        navigator.clipboard.writeText(text).then(() => {
            // Show a better feedback (toast or alert) - for now using alert as per original
            // specific to the new button text change?
            // Let's stick to simple alert for now or maybe update the button text temporarily
            const btn = document.querySelector(`button[onclick="copyToClipboard('${elementId}', '${message}')"] span`);
            if (btn) {
                const originalText = btn.innerText;
                btn.innerText = 'Disalin!';
                setTimeout(() => btn.innerText = originalText, 2000);
            } else {
                alert(message);
            }
        }).catch(err => {
            console.error('Failed to copy text: ', err);
        });
    }
</script>
<?= $this->endSection() ?>