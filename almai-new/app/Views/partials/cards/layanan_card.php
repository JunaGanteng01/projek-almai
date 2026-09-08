<div class="bg-[#111] rounded-2xl border border-white/10 overflow-hidden group hover:border-accent/50 transition-all duration-300">
    <?php 
        $detailPath = !empty($is_cwpa) ? 'cwpa/layanan/' : 'layanan/';
        if (!empty($use_dashboard_path)) {
            $currentURI = uri_string();
            if (strpos($currentURI, 'user/dashboard') === 0) {
                $detailPath = 'user/dashboard/daftar-wpa/layanan/';
            } elseif (strpos($currentURI, 'wpa/dashboard') === 0) {
                $detailPath = 'wpa/dashboard/daftar-wpa/layanan/';
            } else {
                $detailPath = 'cwpa/dashboard/daftar-wpa/layanan/';
            }
        }
    ?>
    <!-- Thumbnail -->
    <a href="<?= base_url($detailPath . $layanan['slug']) ?>" class="block relative aspect-video overflow-hidden">
        <img src="<?= $layanan['thumbnail'] ?>" alt="<?= esc($layanan['name']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        
        <!-- Rating Badge -->
        <div class="absolute top-3 right-3 px-2 py-1 bg-black/70 backdrop-blur-sm rounded text-xs font-medium flex items-center gap-1">
            <i class="fas fa-star text-yellow-500"></i>
            <?= number_format($layanan['rating'] ?? 0, 1) ?>
        </div>
        
        <!-- Category & Subcategory Badges -->
        <div class="absolute top-3 left-3 flex flex-wrap gap-2">
            <span class="px-2 py-1 bg-black/70 backdrop-blur-sm rounded text-xs font-medium"><?= esc($layanan['category'] ?? 'Layanan') ?></span>
            <span class="px-2 py-1 bg-accent text-black rounded text-xs font-medium"><?= esc($layanan['subcategory'] ?? '') ?></span>
        </div>
        
        <?php if (!empty($layanan['badge'])): ?>
        <div class="absolute bottom-3 left-3">
            <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded"><?= esc($layanan['badge']) ?></span>
        </div>
        <?php endif; ?>
    </a>
    
    <div class="p-5">
        <!-- Title -->
        <h3 class="font-bold text-lg mb-3 group-hover:text-accent transition line-clamp-2">
            <a href="<?= base_url($detailPath . $layanan['slug']) ?>">
                <?= esc($layanan['name']) ?>
            </a>
        </h3>
        
        <!-- Provider -->
        <div class="flex items-center gap-2 mb-3">
            <?php 
                $providerPhoto = '';
                $providerName = 'Tim Almai';
                
                if (!empty($is_cwpa) || !empty($layanan['cwpa_name'])) {
                    $providerName = $layanan['cwpa_name'] ?? 'CWPA';
                    $providerPhoto = $layanan['cwpa_photo'] ?? '';
                } else {
                    $providerName = $layanan['wpa_name'] ?? 'Tim Almai';
                    $providerPhoto = $layanan['wpa_photo'] ?? '';
                }

                // Append base_url if the photo doesn't start with http (to handle relative paths like images/cwpa/...)
                if (!empty($providerPhoto) && strpos($providerPhoto, 'http') !== 0) {
                    $providerPhoto = base_url('file/' . ltrim($providerPhoto, '/'));
                }
            ?>
            <?php if (!empty($providerPhoto)): ?>
            <img src="<?= esc($providerPhoto) ?>" alt="" class="w-7 h-7 rounded-full object-cover shrink-0">
            <?php else: ?>
            <div class="w-7 h-7 rounded-full bg-accent/20 flex items-center justify-center shrink-0">
                <i class="fas fa-user text-accent text-xs"></i>
            </div>
            <?php endif; ?>
            <span class="text-sm text-gray-400 truncate"><?= esc($providerName) ?></span>
        </div>
        
        <!-- Location -->
        <p class="text-xs text-gray-500 mb-3">
            <i class="fas fa-map-marker-alt mr-1"></i> <?= esc($layanan['location'] ?? 'Online') ?>
        </p>
        
        <!-- Stats -->
        <div class="flex items-center gap-4 text-xs text-gray-500 mb-4">
            <?php if (!empty($layanan['duration'])): ?>
            <span><i class="fas fa-clock mr-1"></i> <?= esc($layanan['duration']) ?></span>
            <?php endif; ?>
            <span><i class="fas fa-users mr-1"></i> <?= number_format($layanan['students'] ?? 0) ?></span>
        </div>
        
        <!-- Price -->
        <div class="mb-4">
            <?php if (!empty($is_cwpa)): ?>
                <?php if (!empty($layanan['poin_price']) && $layanan['poin_price'] > 0): ?>
                    <p class="text-accent font-bold text-lg"><i class="fas fa-coins text-yellow-500 mr-1"></i> <?= number_format($layanan['poin_price'], 0, ',', '.') ?> Poin</p>
                <?php else: ?>
                    <p class="text-gray-400 text-sm">Gratis / Hubungi untuk harga</p>
                <?php endif; ?>
            <?php else: ?>
                <?php if (!empty($layanan['has_packages'])): ?>
                    <p class="text-xs text-gray-400 mb-1">Mulai dari</p>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-accent font-bold text-lg">Rp <?= number_format($layanan['min_price'] ?? $layanan['price'], 0, ',', '.') ?></span>
                        <?php if (isset($layanan['max_price']) && ($layanan['min_price'] ?? 0) != $layanan['max_price']): ?>
                        <span class="text-gray-500">-</span>
                        <span class="text-accent font-bold text-lg">Rp <?= number_format($layanan['max_price'], 0, ',', '.') ?></span>
                        <?php endif; ?>
                    </div>
                <?php elseif (!empty($layanan['price']) && $layanan['price'] > 0): ?>
                    <?php if (!empty($layanan['original_price']) && $layanan['original_price'] > $layanan['price']): ?>
                    <p class="text-xs text-gray-500 line-through">Rp <?= number_format($layanan['original_price'], 0, ',', '.') ?></p>
                    <?php endif; ?>
                    <p class="text-accent font-bold text-lg">Rp <?= number_format($layanan['price'], 0, ',', '.') ?></p>
                <?php else: ?>
                    <p class="text-gray-400 text-sm">Hubungi untuk harga</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
        
        <!-- CTA -->
        <a href="<?= base_url($detailPath . $layanan['slug']) ?>"
           class="block text-center py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
            Lihat Layanan
        </a>
    </div>
</div>
