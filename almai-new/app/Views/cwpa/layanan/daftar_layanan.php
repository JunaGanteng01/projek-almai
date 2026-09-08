<?= $this->extend('cwpa/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Filters and Search -->
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <!-- Category Filter -->
    <div class="overflow-x-auto pb-2 -mx-4 px-4 md:mx-0 md:px-0 flex-1">
        <div class="flex gap-2 min-w-max">
            <?php $searchParam = $searchQuery ? '&search=' . urlencode($searchQuery) : ''; ?>
            <a href="<?= base_url('cwpa/dashboard/daftar-layanan') . ($searchQuery ? '?search=' . urlencode($searchQuery) : '') ?>"
                class="px-4 py-2 rounded-xl border text-xs font-medium transition <?= !$currentCategory || $currentCategory === 'all' ? 'bg-accent text-black border-accent' : 'border-white/20 hover:border-accent hover:text-accent' ?>">
                Semua
            </a>

            <a href="<?= base_url('cwpa/dashboard/daftar-layanan?category=Advokasi' . $searchParam) ?>"
                class="px-4 py-2 rounded-xl border text-xs font-medium transition flex items-center gap-2 <?= $currentCategory === 'Advokasi' ? 'bg-accent text-black border-accent' : 'border-white/20 hover:border-accent hover:text-accent' ?>">
                <i class="fas fa-user-tie"></i> Advokasi
            </a>



            <a href="<?= base_url('cwpa/dashboard/daftar-layanan?category=Expert+Advisor' . $searchParam) ?>"
                class="px-4 py-2 rounded-xl border text-xs font-medium transition flex items-center gap-2 <?= $currentCategory === 'Expert Advisor' ? 'bg-accent text-black border-accent' : 'border-white/20 hover:border-accent hover:text-accent' ?>">
                <i class="fas fa-robot"></i> Expert Advisor
            </a>

            <a href="<?= base_url('cwpa/dashboard/daftar-layanan?category=Ultimate' . $searchParam) ?>"
                class="px-4 py-2 rounded-xl border text-xs font-medium transition flex items-center gap-2 <?= $currentCategory === 'Ultimate' ? 'bg-accent text-black border-accent' : 'border-white/20 hover:border-accent hover:text-accent' ?>">
                <i class="fas fa-crown"></i> Ultimate
            </a>
        </div>
    </div>

    <!-- Search Box -->
    <div class="w-full md:w-auto">
        <form action="<?= base_url('cwpa/dashboard/daftar-layanan') ?>" method="get" class="relative" id="searchForm">
            <input type="text" name="search" value="<?= esc($searchQuery ?? '') ?>"
                placeholder="Cari layanan..."
                oninput="clearTimeout(this.delay); this.delay = setTimeout(() => this.form.submit(), 500)"
                class="w-full md:w-64 bg-[#111] border border-white/20 rounded-xl px-4 py-2 text-sm focus:border-accent focus:outline-none h-10">
            <?php if ($currentCategory): ?>
                <input type="hidden" name="category" value="<?= esc($currentCategory) ?>">
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Subcategory Filter -->
<?php if ($currentCategory && $currentCategory !== 'all' && !empty($subcategories)): ?>
    <div class="mb-6 flex flex-wrap gap-2">
        <a href="<?= base_url('cwpa/dashboard/daftar-layanan?category=' . urlencode($currentCategory) . $searchParam) ?>"
            class="px-3 py-1.5 rounded-lg border text-xs font-medium transition <?= !$currentSubcategory || $currentSubcategory === 'all' ? 'bg-white/20 text-white border-white/20' : 'border-white/10 hover:border-white/30 text-gray-400' ?>">
            Semua Sub
        </a>
        <?php foreach ($subcategories as $sub): ?>
            <a href="<?= base_url('cwpa/dashboard/daftar-layanan?category=' . urlencode($currentCategory) . '&subcategory=' . urlencode($sub) . $searchParam) ?>"
                class="px-3 py-1.5 rounded-lg border text-xs font-medium transition <?= $currentSubcategory === $sub ? 'bg-white/20 text-white border-white/20' : 'border-white/10 hover:border-white/30 text-gray-400' ?>">
                <?= esc($sub) ?>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Layanan Grid -->
<?php if (empty($layananList)): ?>
    <div class="text-center py-12 text-gray-400 bg-[#111] border border-white/10 rounded-2xl">
        <i class="fas fa-search text-4xl mb-4"></i>
        <p>Tidak ada layanan yang ditemukan.</p>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($layananList as $item): ?>
            <div class="group bg-[#111] border border-white/10 rounded-2xl overflow-hidden hover:border-accent/50 transition-all duration-300">
                <!-- Thumbnail -->
                <div class="relative aspect-video overflow-hidden">
                    <?php
                    $thumbnail = $item['thumbnail'];
                    if (empty($thumbnail)) {
                        $placeholders = [
                            'Advokasi' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=400&h=225&fit=crop',
                            'Expert Advisor' => 'https://images.unsplash.com/photo-1642790106117-e829e14a795f?w=400&h=225&fit=crop',
                            'Ultimate' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=400&h=225&fit=crop',
                            'Event' => 'https://images.unsplash.com/photo-1544531586-fde5298cdd40?w=400&h=225&fit=crop',
                        ];
                        $thumbnail = $placeholders[$item['category']] ?? $placeholders['Advokasi'];
                    }
                    ?>
                    <img src="<?= $thumbnail ?>" alt="<?= esc($item['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                    <!-- Badges -->
                    <div class="absolute top-3 left-3 flex flex-wrap gap-2">
                        <span class="px-2 py-1 bg-black/70 backdrop-blur-sm rounded text-[10px] font-medium"><?= esc($item['category']) ?></span>
                        <span class="px-2 py-1 bg-accent text-black rounded text-[10px] font-medium"><?= esc($item['subcategory']) ?></span>
                    </div>

                    <?php if (!empty($item['is_premium'])): ?>
                        <div class="absolute top-3 right-3">
                            <span class="px-2 py-1 bg-accent text-black rounded text-[10px] font-bold">PRO</span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Content -->
                <div class="p-4">
                    <h3 class="font-bold text-base mb-2 group-hover:text-accent transition line-clamp-2 h-12"><?= esc($item['name']) ?></h3>

                    <!-- CWPA Information -->
                    <div class="flex items-center gap-2 mb-3">
                        <img src="<?= esc($item['cwpa_photo'] ?? 'https://almai.id/images/alma.gif') ?>" alt="" class="w-6 h-6 rounded-full object-cover">
                        <span class="text-xs text-gray-400"><?= esc($item['cwpa_name'] ?? 'Tim Almai') ?></span>
                    </div>

                    <!-- Stats -->
                    <div class="flex items-center gap-3 text-[10px] text-gray-500 mb-4">
                        <span><i class="fas fa-clock mr-1"></i> <?= esc($item['duration'] ?? 'Lifetime') ?></span>
                        <span><i class="fas fa-users mr-1"></i> <?= number_format($item['students'] ?? 0) ?></span>
                        <?php if (!empty($item['location'])): ?>
                            <span><i class="fas fa-map-marker-alt mr-1"></i> <?= esc($item['location']) ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Price -->
                    <div class="mb-4">
                        <?php if (!empty($item['has_packages'])): ?>
                            <p class="text-[10px] text-gray-400 mb-1">Mulai dari</p>
                            <span class="text-accent font-bold text-base">Rp <?= number_format($item['min_price'] ?? $item['price'], 0, ',', '.') ?></span>
                        <?php elseif ($item['price']): ?>
                            <p class="text-accent font-bold text-base">Rp <?= number_format($item['price'], 0, ',', '.') ?></p>
                        <?php elseif (isset($item['poin_price']) && $item['poin_price'] > 0): ?>
                            <p class="text-accent font-bold text-base"><?= number_format($item['poin_price'], 0, ',', '.') ?> Poin</p>
                        <?php else: ?>
                            <p class="text-gray-400 text-xs">Gratis / Hubungi Admin</p>
                        <?php endif; ?>
                    </div>

                    <!-- CTA -->
                    <a href="<?= base_url('layanan/' . $item['slug']) ?>" target="_blank"
                        class="block text-center py-2.5 bg-white/5 text-white font-bold rounded-xl hover:bg-accent hover:text-black transition text-sm">
                        Lihat Detail <i class="fas fa-external-link-alt ml-1 text-[10px]"></i>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>

