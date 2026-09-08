<?= $this->extend('user/partials/layout') ?>

<?= $this->section('content') ?>

<!-- Hero Banner -->
<div class="mb-5 bg-gradient-to-br from-accent/20 via-green-500/10 to-emerald-600/20 rounded-3xl p-6 relative overflow-hidden border border-accent/20">
    <div class="relative z-10">
        <p class="text-[10px] text-accent font-semibold mb-1 uppercase tracking-wide">Layanan Trading</p>
        <h1 class="text-xl md:text-2xl font-bold mb-2 leading-tight">Tingkatkan Skill<br>Trading Anda</h1>
        <p class="text-xs text-gray-400 mb-3">Pilih layanan terbaik dari expert kami</p>
        
        <form action="<?= base_url('user/layanan') ?>" method="get" class="relative max-w-md">
            <input type="text" name="search" value="<?= esc($searchQuery ?? '') ?>"
                placeholder="Cari layanan..."
                class="w-full bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl px-4 py-2.5 pl-10 text-xs placeholder:text-gray-400 focus:border-accent/50 focus:bg-white/15 focus:outline-none transition-all">
            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            <?php if (isset($currentCategory) && $currentCategory): ?>
                <input type="hidden" name="category" value="<?= esc($currentCategory) ?>">
            <?php endif; ?>
        </form>
    </div>
    
    <!-- Decorative Elements -->
    <div class="absolute -right-8 -top-8 w-32 h-32 bg-accent/10 rounded-full blur-3xl"></div>
    <div class="absolute -left-4 -bottom-4 w-24 h-24 bg-green-500/10 rounded-full blur-2xl"></div>
</div>

<!-- Category Tabs -->
<div class="mb-5">
    <div class="flex items-center justify-between mb-3">
        <h2 class="text-base font-bold">Kategori Layanan</h2>
        <?php if (!empty($subcategories)): ?>
            <a href="#" class="text-xs text-accent hover:text-white transition">Lihat Semua</a>
        <?php endif; ?>
    </div>
    
    <div class="flex gap-2 overflow-x-auto pb-2 no-scrollbar">
        <?php
        $cats = [
            'Advokasi' => ['icon' => 'fa-briefcase', 'emoji' => '💼'],
            'Expert Advisor' => ['icon' => 'fa-robot', 'emoji' => '🤖'],
            'Ultimate' => ['icon' => 'fa-crown', 'emoji' => '👑'],
        ];
        $uniqueCategories = $categories ?? array_keys($cats);
        ?>
        
        <!-- All Category -->
        <a href="<?= base_url('user/layanan') ?>"
            class="flex-shrink-0 px-4 py-2 rounded-xl text-xs font-medium transition-all <?= (!isset($currentCategory) || !$currentCategory) ? 'bg-white text-black' : 'bg-white/5 text-gray-400 hover:bg-white/10' ?>">
            <i class="fas fa-th-large mr-1.5"></i>Semua
        </a>
        
        <?php foreach ($uniqueCategories as $cat):
            $isActive = (isset($currentCategory) && $currentCategory === $cat);
            $style = $cats[$cat] ?? ['icon' => 'fa-layer-group', 'emoji' => '📦'];
            $displayCat = $cat;
        ?>
            <a href="<?= base_url('user/layanan?category=' . urlencode($cat)) ?>"
                class="flex-shrink-0 px-4 py-2 rounded-xl text-xs font-medium transition-all <?= $isActive ? 'bg-white text-black' : 'bg-white/5 text-gray-400 hover:bg-white/10' ?>">
                <i class="fas <?= $style['icon'] ?> mr-1.5"></i><?= esc($displayCat) ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<!-- Sub Category Chips -->
<?php if (!empty($subcategories)): ?>
    <div class="mb-5">
        <div class="flex gap-2 overflow-x-auto pb-2 no-scrollbar">
            <a href="<?= base_url('user/layanan?category=' . urlencode($currentCategory)) ?>"
                class="flex-shrink-0 px-3 py-1.5 rounded-lg text-[11px] font-medium transition-all <?= (!isset($currentSubcategory) || !$currentSubcategory) ? 'bg-accent/20 text-accent border border-accent/30' : 'bg-white/5 text-gray-400 hover:bg-white/10' ?>">
                Semua
            </a>
            <?php foreach ($subcategories as $sub):
                $isActiveSub = (isset($currentSubcategory) && $currentSubcategory === $sub);
            ?>
                <a href="<?= base_url('user/layanan?category=' . urlencode($currentCategory) . '&subcategory=' . urlencode($sub)) ?>"
                    class="flex-shrink-0 px-3 py-1.5 rounded-lg text-[11px] font-medium transition-all <?= $isActiveSub ? 'bg-accent/20 text-accent border border-accent/30' : 'bg-white/5 text-gray-400 hover:bg-white/10' ?>">
                    <?= esc($sub) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<!-- Layanan Grid -->
<div class="mb-16">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-base font-bold">Daftar Layanan</h2>
        <span class="text-[10px] text-gray-500"><?= count($layananList ?? []) ?> layanan tersedia</span>
    </div>

    <?php if (empty($layananList ?? [])): ?>
        <div class="text-center py-16 bg-white/5 rounded-2xl border border-white/5">
            <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-search text-2xl text-gray-600"></i>
            </div>
            <p class="text-sm text-gray-400">Tidak ada layanan ditemukan</p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-4">
            <?php foreach ($layananList as $item): ?>
                <a href="<?= base_url('user/layanan/' . ($item['slug'] ?? '')) ?>"
                    class="group bg-white/5 backdrop-blur-sm border border-white/5 rounded-2xl overflow-hidden hover:bg-white/10 hover:border-accent/30 transition-all duration-300 flex flex-col">

                    <!-- Thumbnail -->
                    <div class="relative aspect-[4/3] overflow-hidden bg-gradient-to-br from-accent/10 to-green-500/10">
                        <?php
                        $thumbnail = $item['thumbnail'] ?? '';
                        if (empty($thumbnail)) {
                            $thumbnail = 'https://images.unsplash.com/photo-1544531586-fde5298cdd40?w=400&h=300&fit=crop';
                        }
                        ?>
                        <img src="<?= $thumbnail ?>" alt="<?= esc($item['name'] ?? 'Layanan') ?>"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                        <!-- Favorite Icon -->
                        <div class="absolute top-2 right-2">
                            <div class="w-7 h-7 bg-black/50 backdrop-blur-sm rounded-full flex items-center justify-center">
                                <i class="far fa-heart text-white text-xs"></i>
                            </div>
                        </div>

                        <!-- Category Badge -->
                        <div class="absolute bottom-2 left-2">
                            <span class="px-2 py-0.5 bg-white/90 backdrop-blur-sm rounded-md text-[9px] font-semibold text-black">
                                <?= esc($item['subcategory'] ?? $item['category'] ?? 'Layanan') ?>
                            </span>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-3 flex flex-col flex-1">
                        <h3 class="font-bold text-xs md:text-sm mb-2 line-clamp-2 leading-tight group-hover:text-accent transition">
                            <?= esc($item['name'] ?? 'Layanan') ?>
                        </h3>

                        <!-- Meta Info -->
                        <div class="flex items-center gap-2 text-[9px] md:text-[10px] text-gray-500 mb-2">
                            <span class="flex items-center gap-0.5">
                                <i class="fas fa-star text-yellow-500"></i>
                                <?= number_format($item['rating'] ?? 5.0, 1) ?>
                            </span>
                            <span class="flex items-center gap-0.5">
                                <i class="fas fa-users"></i>
                                <?= number_format($item['students'] ?? 0) ?>
                            </span>
                        </div>

                        <!-- Price -->
                        <div class="mt-auto pt-2 border-t border-white/5">
                            <?php if (isset($item['price']) && $item['price'] > 0): ?>
                                <p class="text-white font-bold text-sm md:text-base">
                                    Rp <?= number_format($item['min_price'] ?? $item['price'], 0, ',', '.') ?>
                                </p>
                            <?php else: ?>
                                <p class="text-accent text-xs font-bold uppercase">Gratis</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<?= $this->endSection() ?>