<div class="bg-[#111] rounded-2xl border border-white/10 overflow-hidden hover:border-accent transition group" data-aos="fade-up">
    <div class="relative">
        <img src="<?= esc($artikel['thumbnail']) ?>" alt="<?= esc($artikel['title']) ?>" class="w-full h-48 object-cover group-hover:scale-105 transition duration-500">
        <div class="absolute top-4 left-4 bg-accent text-black px-3 py-1 rounded-full text-xs font-bold">
            <?= esc($artikel['category']) ?>
        </div>
        <?php if (!($artikel['is_free'] ?? false) && ($artikel['poin_price'] ?? 0) > 0): ?>
        <div class="absolute top-4 right-4 bg-yellow-500/90 text-black px-2 py-1 rounded-full text-xs font-bold">
            <i class="fas fa-coins mr-1"></i><?= number_format($artikel['poin_price']) ?>
        </div>
        <?php elseif (($artikel['is_free'] ?? false) || ($artikel['poin_price'] ?? 0) == 0): ?>
        <div class="absolute top-4 right-4 bg-accent/90 text-black px-2 py-1 rounded-full text-xs font-bold">
            GRATIS
        </div>
        <?php endif; ?>
    </div>
    <div class="p-6">
        <div class="flex items-center gap-2 mb-3 text-sm text-gray-400">
            <span><?= date('d M Y', strtotime($artikel['created_at'])) ?></span>
            <span>•</span>
            <span><?= esc($artikel['read_time']) ?> read</span>
        </div>
        <h3 class="text-lg font-bold mb-2 line-clamp-2"><?= esc($artikel['title']) ?></h3>
        <p class="text-gray-400 text-sm mb-4 line-clamp-2"><?= esc($artikel['excerpt']) ?></p>
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
                <img src="<?= esc($artikel['wpa_photo'] ?? '') ?>" alt="<?= esc($artikel['wpa_name'] ?? '') ?>" class="w-8 h-8 rounded-full object-cover">
                <span class="text-gray-400 text-sm"><?= esc($artikel['wpa_name'] ?? '') ?></span>
            </div>
            <a href="<?= base_url('artikel/' . $artikel['id']) ?>" class="text-accent hover:underline text-sm font-bold">
                Baca <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>
</div>
