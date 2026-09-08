<?= $this->extend('user/partials/layout') ?>

<?= $this->section('content') ?>
<?php 
$pageTitle = 'Daftar WPA';
$pageSubtitle = 'Cari dan lihat profil Wakil Penasihat Berjangka ALMAI'; 
?>

<!-- Tab Navigation + WPA Title -->
<div class="mb-6 flex items-center justify-between flex-wrap gap-3">
    <a href="<?= base_url('user/dashboard') ?>" class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition">
        <i class="fas fa-arrow-left"></i>
        <span class="text-sm">Kembali ke Dashboard</span>
    </a>
    <div class="flex bg-[#111] border border-white/10 rounded-xl p-1 gap-1">
        <a href="<?= base_url('user/advokasi') ?>" class="px-5 py-2 rounded-lg text-sm font-bold transition text-gray-400 hover:text-white">
            <i class="fas fa-shield-halved mr-1.5"></i>Advokasi
        </a>
        <a href="<?= base_url('user/dashboard/daftar-wpa') ?>" class="px-5 py-2 rounded-lg text-sm font-bold transition bg-accent text-black">
            <i class="fas fa-id-badge mr-1.5"></i>DAFTAR WPA
        </a>
    </div>
</div>
<div class="mb-8">
    <h2 class="text-3xl font-black uppercase tracking-tight text-white leading-none">WPA</h2>
    <div class="w-16 h-1 bg-accent mt-4"></div>
</div>


<!-- Search Bar -->
<div class="mb-6">
    <form action="<?= base_url('user/dashboard/daftar-wpa') ?>" method="get" class="max-w-md">
        <div class="relative">
            <input type="text" name="search" value="<?= esc($search ?? '') ?>" placeholder="CARI NAMA ATAU KEAHLIAN WPA..."
                class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 pl-12 text-sm focus:border-accent focus:outline-none uppercase tracking-wider">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-lg"></i>
        </div>
    </form>
</div>

<!-- WPA Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    <?php if (empty($wpas)): ?>
        <div class="col-span-full py-20 text-center bg-[#111] border border-white/10 rounded-2xl">
            <i class="fas fa-user-tie text-5xl text-gray-700 mb-4"></i>
            <p class="text-gray-400">Belum ada WPA yang terdaftar atau ditemukan.</p>
            <?php if ($search): ?>
                <p class="text-sm text-gray-500 mt-2">Tidak ada hasil untuk "<?= esc($search) ?>"</p>
                <a href="<?= base_url('user/dashboard/daftar-wpa') ?>" class="inline-block mt-4 text-accent hover:underline text-sm uppercase font-bold">Lihat Semua</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <?php foreach ($wpas as $wpa): ?>
            <div class="group bg-[#111] border border-white/10 rounded-2xl overflow-hidden hover:border-accent/50 transition-all duration-300 transform hover:-translate-y-1 shadow-lg">
                <!-- Banner/Header Color -->
                <div class="h-16 bg-gradient-to-r from-accent/20 to-accent/5"></div>
                
                <div class="px-6 pb-6 -mt-8">
                    <!-- Photo -->
                    <div class="relative inline-block mb-4">
                        <?php
                        $wpaPhoto = $wpa['photo'] ?? null;
                        if ($wpaPhoto && strpos($wpaPhoto, 'http') !== 0) {
                            $wpaPhoto = base_url('file/' . ltrim($wpaPhoto, '/'));
                        } else {
                            $wpaPhoto = $wpaPhoto ?? 'https://almai.id/images/alma.gif';
                        }
                        ?>
                        <img src="<?= $wpaPhoto ?>" alt="<?= esc($wpa['name']) ?>" class="w-20 h-20 rounded-2xl object-cover border-4 border-[#111] shadow-xl">
                        <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-accent rounded-full flex items-center justify-center border-2 border-[#111]">
                            <i class="fas fa-check text-[10px] text-black"></i>
                        </div>
                    </div>

                    <!-- Info -->
                    <h3 class="font-bold text-white text-xs sm:text-sm mb-1 leading-tight line-clamp-2 uppercase tracking-tight min-h-[2.5rem]"><?= esc($wpa['name']) ?></h3>
                    <p class="text-accent text-[10px] sm:text-xs font-bold uppercase tracking-widest mb-3 opacity-90"><?= esc($wpa['specialty'] ?? 'Financial Advisor') ?></p>
                    
                    <!-- Bio (Clamped) -->
                    <p class="text-gray-400 text-xs leading-relaxed line-clamp-2 mb-4 h-8 italic">
                        "<?= esc($wpa['bio'] ?? 'ALMAI Certified Advisor ready to help you grow.') ?>"
                    </p>

                    <!-- Stats Row -->
                    <div class="flex items-center justify-between py-3 border-t border-white/5 mb-4 px-1">
                        <div class="text-center">
                            <p class="text-[10px] text-gray-500 uppercase font-black">User</p>
                            <p class="text-sm font-bold text-white"><?= number_format($wpa['total_users'] ?? 0) ?></p>
                        </div>
                        <div class="text-center">
                            <p class="text-[10px] text-gray-500 uppercase font-black">Rating</p>
                            <div class="flex items-center gap-1">
                                <i class="fas fa-star text-yellow-500 text-[10px]"></i>
                                <span class="text-sm font-bold text-white"><?= number_format($wpa['rating'] ?? 5.0, 1) ?></span>
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="text-[10px] text-gray-500 uppercase font-black">Layanan</p>
                            <p class="text-sm font-bold text-white"><?= esc($wpa['total_layanan'] ?? '0') ?></p>
                        </div>
                    </div>

                    <!-- Action -->
                    <a href="<?= base_url('user/dashboard/daftar-wpa/' . $wpa['slug']) ?>" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-xs font-bold text-white hover:bg-accent hover:text-black hover:border-accent transition-all duration-300 uppercase tracking-widest">
                        <span>Lihat Profil</span>
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Pagination -->
<?php if (isset($pager_links) && $pager_links): ?>
    <div class="mt-8 flex justify-center">
        <?= $pager_links ?>
    </div>
<?php elseif (isset($pager) && method_exists($pager, 'getPageCount') && $pager->getPageCount() > 1): ?>
    <div class="mt-8 flex justify-center">
        <?= $pager->links('default', 'admin_pagination') ?>
    </div>
<?php endif; ?>

<!-- PENGURUS Section -->
<?php if (!empty($teams)): ?>
    <div class="mt-16 pt-12 border-t border-white/10">
        <div class="mb-10">
            <h2 class="text-3xl font-black uppercase tracking-tight text-white leading-none">PENGURUS</h2>
            <div class="w-16 h-1 bg-accent mt-4"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
            <?php foreach ($teams as $member): ?>
                <div class="group bg-[#111] border border-white/10 rounded-2xl overflow-hidden hover:border-accent/50 transition-all duration-300 transform hover:-translate-y-1 shadow-lg">
                    <!-- Banner/Header Color -->
                    <div class="h-16 bg-gradient-to-r from-accent/20 to-accent/5"></div>
                    
                    <div class="px-6 pb-6 -mt-8">
                        <!-- Photo -->
                        <div class="relative inline-block mb-4">
                            <?php
                            $photo = $member['photo'];
                            if ($photo && strpos($photo, 'http') !== 0) {
                                $photo = base_url('file/' . ltrim($photo, '/'));
                            } else {
                                $photo = $photo ?? 'https://almai.id/images/alma.gif';
                            }
                            ?>
                            <img src="<?= $photo ?>" alt="<?= esc($member['name']) ?>" class="w-20 h-20 rounded-2xl object-cover border-4 border-[#111] shadow-xl">
                            <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-accent rounded-full flex items-center justify-center border-2 border-[#111]">
                                <i class="fas fa-check text-[10px] text-black"></i>
                            </div>
                        </div>

                        <!-- Info -->
                        <h3 class="font-bold text-white text-xs sm:text-sm mb-1 leading-tight line-clamp-2 uppercase tracking-tight min-h-[2.5rem]"><?= esc($member['name']) ?></h3>
                        <p class="text-accent text-[10px] sm:text-xs font-bold uppercase tracking-widest mb-3 opacity-90"><?= esc($member['role']) ?></p>
                        
                        <!-- Decorative bio placeholder for visual consistency -->
                        <p class="text-gray-400 text-xs leading-relaxed line-clamp-2 h-8 italic">
                            ALMAI Management Team
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
