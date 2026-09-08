<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Hero -->
<section class="relative pt-32 pb-8 overflow-hidden">
    <div class="absolute inset-0 bg-grid z-0 pointer-events-none"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-accent/20 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="container mx-auto px-6 relative z-10 text-center">
        <p class="text-accent font-bold tracking-widest text-sm mb-4 uppercase">Layanan Profesional</p>
        <h1 class="text-5xl md:text-6xl font-bold mb-4 tracking-tighter">Semua <span class="text-accent">Layanan</span></h1>
        <p class="text-gray-400">Layanan nasihat perdagangan berjangka dari WPA bersertifikat BAPPEBTI</p>
    </div>
</section>

<!-- Search -->
<section class="py-6">
    <div class="container mx-auto px-6">
        <div class="max-w-xl mx-auto">
            <form action="<?= base_url('layanan') ?>" method="get" class="relative">
                <input type="text" name="search" value="<?= esc($searchQuery ?? '') ?>"
                    placeholder="Cari layanan..."
                    class="w-full bg-[#111] border border-white/20 rounded-full px-6 py-4 pl-14 focus:border-accent focus:outline-none">
                <i class="fas fa-search absolute left-6 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <?php if ($currentCategory): ?>
                    <input type="hidden" name="category" value="<?= esc($currentCategory) ?>">
                <?php endif; ?>
            </form>
        </div>
    </div>
</section>

<!-- Category Filter -->
<section class="py-4">
    <div class="container mx-auto px-6">
        <!-- Main Category Filter -->
        <div class="flex flex-wrap gap-3 justify-center mb-4">
            <?php $searchParam = $searchQuery ? '&search=' . urlencode($searchQuery) : ''; ?>
            <a href="<?= base_url('layanan') . ($searchQuery ? '?search=' . urlencode($searchQuery) : '') ?>"
                class="px-6 py-2 rounded-full border text-sm font-medium transition <?= !$currentCategory ? 'bg-accent text-black border-accent' : 'border-white/20 hover:border-accent hover:text-accent' ?>">
                Semua
            </a>

            <a href="<?= base_url('layanan?category=Advokasi' . $searchParam) ?>"
                class="px-6 py-2 rounded-full border text-sm font-medium transition flex items-center gap-2 <?= $currentCategory === 'Advokasi' ? 'bg-accent text-black border-accent' : 'border-white/20 hover:border-accent hover:text-accent' ?>">
                <i class="fas fa-user-tie"></i> Layanan WPA
            </a>

            <a href="<?= base_url('layanan?category=Expert+Advisor' . $searchParam) ?>"
                class="px-6 py-2 rounded-full border text-sm font-medium transition flex items-center gap-2 <?= $currentCategory === 'Expert Advisor' ? 'bg-accent text-black border-accent' : 'border-white/20 hover:border-accent hover:text-accent' ?>">
                <i class="fas fa-robot"></i> Expert Advisor
            </a>

            <a href="<?= base_url('layanan?category=Ultimate' . $searchParam) ?>"
                class="px-6 py-2 rounded-full border text-sm font-medium transition flex items-center gap-2 <?= $currentCategory === 'Ultimate' ? 'bg-accent text-black border-accent' : 'border-white/20 hover:border-accent hover:text-accent' ?>">
                <i class="fas fa-crown"></i> Ultimate
            </a>
        </div>

        <!-- Subcategory Filter -->
        <?php if ($currentCategory && !empty($subcategories)): ?>
            <div class="flex flex-wrap gap-2 justify-center">
                <a href="<?= base_url('layanan?category=' . urlencode($currentCategory) . $searchParam) ?>"
                    class="px-4 py-1.5 rounded-full border text-xs font-medium transition <?= !$currentSubcategory ? 'bg-white/20 text-white border-white/20' : 'border-white/10 hover:border-white/30 text-gray-400' ?>">
                    Semua Sub
                </a>
                <?php foreach ($subcategories as $sub): ?>
                    <a href="<?= base_url('layanan?category=' . urlencode($currentCategory) . '&subcategory=' . urlencode($sub) . $searchParam) ?>"
                        class="px-4 py-1.5 rounded-full border text-xs font-medium transition <?= $currentSubcategory === $sub ? 'bg-white/20 text-white border-white/20' : 'border-white/10 hover:border-white/30 text-gray-400' ?>">
                        <?= esc($sub) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Layanan Grid -->
<section class="py-12">
    <div class="container mx-auto px-6">
        <?php if (empty($layananList)): ?>
            <div class="text-center py-12 text-gray-400">
                <i class="fas fa-search text-4xl mb-4"></i>
                <p>Tidak ada layanan yang ditemukan.</p>
            </div>
        <?php else: ?>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($layananList as $item): ?>
                    <div class="group bg-[#111] border border-white/10 rounded-2xl overflow-hidden hover:border-accent/50 transition-all duration-300 hover:-translate-y-1">
                        <!-- Thumbnail -->
                        <div class="relative aspect-video overflow-hidden">
                            <?php
                            // Use placeholder image if thumbnail is missing
                            if (empty($item['thumbnail'])) {
                                $placeholders = [
                                    'Advokasi' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=400&h=225&fit=crop',
                                    'Expert Advisor' => 'https://images.unsplash.com/photo-1642790106117-e829e14a795f?w=400&h=225&fit=crop',
                                    'Ultimate' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=400&h=225&fit=crop',
                                ];
                                $thumbnail = $placeholders[$item['category']] ?? $placeholders['Advokasi'];
                            } else {
                                $thumbnail = $item['thumbnail'];
                            }
                            ?>
                            <img src="<?= $thumbnail ?>" alt="<?= esc($item['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                            <!-- Badges - Category & Subcategory -->
                            <div class="absolute top-3 left-3 flex flex-wrap gap-2">
                                <span class="px-2 py-1 bg-black/70 backdrop-blur-sm rounded text-xs font-medium"><?= esc($item['category']) ?></span>
                                <span class="px-2 py-1 bg-accent text-black rounded text-xs font-medium"><?= esc($item['subcategory']) ?></span>
                            </div>

                            <!-- Rating -->
                            <div class="absolute top-3 right-3 px-2 py-1 bg-black/70 backdrop-blur-sm rounded text-xs font-medium flex items-center gap-1">
                                <i class="fas fa-star text-yellow-500"></i>
                                <?= number_format($item['rating'], 1) ?>
                            </div>

                            <!-- Premium Badge -->
                            <?php if (!empty($item['is_premium'])): ?>
                                <div class="absolute bottom-3 right-3">
                                    <span class="px-2 py-1 bg-accent text-black rounded text-xs font-bold">PRO</span>
                                </div>
                            <?php endif; ?>

                            <!-- Certification Badge -->
                            <?php if (!empty($item['badge'])): ?>
                                <div class="absolute bottom-3 left-3">
                                    <span class="px-2 py-1 bg-blue-500 text-white rounded text-xs font-medium"><?= esc($item['badge']) ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Content -->
                        <div class="p-5">
                            <!-- Title -->
                            <h3 class="font-bold text-lg mb-3 group-hover:text-accent transition line-clamp-2"><?= esc($item['name']) ?></h3>

                            <!-- WPA -->
                            <div class="flex items-center gap-2 mb-3">
                                <img src="<?= esc($item['wpa_photo']) ?>" alt="" class="w-7 h-7 rounded-full object-cover">
                                <span class="text-sm text-gray-400"><?= esc($item['wpa_name']) ?></span>
                            </div>

                            <!-- Location -->
                            <p class="text-xs text-gray-500 mb-3">
                                <i class="fas fa-map-marker-alt mr-1"></i> <?= esc($item['location']) ?>
                            </p>

                            <!-- Stats -->
                            <div class="flex items-center gap-4 text-xs text-gray-500 mb-4">
                                <?php if (!empty($item['total_sessions']) && $item['total_sessions'] > 0): ?>
                                    <span><i class="fas fa-chalkboard-teacher mr-1"></i> <?= $item['total_sessions'] ?> Sesi</span>
                                <?php else: ?>
                                    <span><i class="fas fa-clock mr-1"></i> <?= esc($item['duration']) ?></span>
                                <?php endif; ?>
                                <?php if ($item['modules'] > 0): ?>
                                    <span><i class="fas fa-book mr-1"></i> <?= $item['modules'] ?> Modul</span>
                                <?php endif; ?>
                                <span><i class="fas fa-users mr-1"></i> <?= number_format($item['students']) ?></span>
                            </div>

                            <!-- Price -->
                            <div class="mb-4">
                                <?php if (!empty($item['has_packages'])): ?>
                                    <p class="text-xs text-gray-400 mb-1">Mulai dari</p>
                                    <div class="flex items-center gap-2 flex-wrap mb-1">
                                        <span class="text-accent font-bold text-lg">Rp <?= number_format($item['min_price'] ?? $item['price'], 0, ',', '.') ?></span>
                                        <?php if (isset($item['max_price']) && ($item['min_price'] ?? 0) != $item['max_price']): ?>
                                            <span class="text-gray-500">-</span>
                                            <span class="text-accent font-bold text-lg">Rp <?= number_format($item['max_price'], 0, ',', '.') ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php elseif ($item['price']): ?>
                                    <?php if ($item['original_price'] && $item['original_price'] > $item['price']): ?>
                                        <p class="text-xs text-gray-500 line-through">Rp <?= number_format($item['original_price'], 0, ',', '.') ?></p>
                                    <?php endif; ?>
                                    <p class="text-accent font-bold text-lg mb-1">Rp <?= number_format($item['price'], 0, ',', '.') ?></p>
                                <?php endif; ?>

                                <!-- Poin Price -->
                                <?php if (!empty($item['poin_price']) && $item['poin_price'] > 0): ?>
                                    <div class="flex items-center gap-1.5 text-xs text-green-400 font-medium bg-green-400/10 px-2 py-1 rounded inline-flex">
                                        <i class="fas fa-coins"></i>
                                        <span><?= number_format($item['poin_price'], 0, ',', '.') ?> Almai Poin</span>
                                    </div>
                                <?php elseif (empty($item['price']) && empty($item['has_packages'])): ?>
                                    <p class="text-gray-400 text-sm">Hubungi untuk harga</p>
                                <?php endif; ?>
                            </div>

                            <!-- CTA -->
                            <a href="<?= base_url('layanan/' . $item['slug']) ?>"
                                class="block text-center py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                                Lihat Layanan
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Info Banner -->
<section class="py-12">
    <div class="container mx-auto px-6">
        <div class="bg-gradient-to-r from-accent/10 to-green-600/10 border border-accent/30 rounded-2xl p-8 text-center">
            <div class="w-16 h-16 bg-accent/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-shield-alt text-2xl text-accent"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Layanan Resmi & Terpercaya</h3>
            <p class="text-gray-400 mb-4 max-w-2xl mx-auto">PT. Alma Indonesia Raya adalah perusahaan Penasihat Berjangka yang terdaftar dan diawasi oleh BAPPEBTI, OJK dan Bank Indonesia juga mendapatkan rekomendasi dari Bursa Komoditi JFX dan Bursa Kripto CFX.</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="https://wa.me/6285183231800" target="_blank" class="px-6 py-3 bg-accent text-black font-bold rounded-full hover:bg-white transition">
                    <i class="fab fa-whatsapp mr-2"></i> Konsultasi Gratis
                </a>
                <a href="<?= base_url('about') ?>" class="px-6 py-3 border border-white/20 rounded-full hover:border-accent hover:text-accent transition">
                    Tentang Kami
                </a>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>