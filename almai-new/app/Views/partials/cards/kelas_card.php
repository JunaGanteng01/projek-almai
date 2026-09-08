<?php 
helper('number');
$modeColor = $kelas['mode'] === 'Online' ? 'bg-blue-500' : ($kelas['mode'] === 'Offline' ? 'bg-orange-500' : 'bg-purple-500');
$modeIcon = $kelas['mode'] === 'Online' ? 'fa-video' : ($kelas['mode'] === 'Offline' ? 'fa-building' : 'fa-arrows-rotate');
?>
<div class="bg-[#111] rounded-2xl border border-white/10 overflow-hidden hover:border-accent transition group flex flex-col h-full" data-aos="fade-up">
    <div class="relative flex-shrink-0">
        <img src="<?= esc($kelas['thumbnail']) ?>" alt="<?= esc($kelas['title']) ?>" class="w-full h-48 object-cover group-hover:scale-105 transition duration-500">
        <div class="absolute top-4 left-4 flex gap-2">
            <span class="bg-accent text-black px-3 py-1 rounded-full text-xs font-bold"><?= esc($kelas['level']) ?></span>
            <span class="<?= $modeColor ?> text-white px-3 py-1 rounded-full text-xs font-bold">
                <i class="fas <?= $modeIcon ?> mr-1"></i><?= esc($kelas['mode']) ?>
            </span>
        </div>
        <div class="absolute top-4 right-4 bg-black/80 text-white px-3 py-1 rounded-full text-xs">
            ⭐ <?= esc($kelas['rating']) ?>
        </div>
    </div>
    <div class="p-6 flex flex-col flex-grow">
        <p class="text-accent text-xs mb-2"><?= esc($kelas['category']) ?></p>
        <h3 class="text-lg font-bold mb-2 line-clamp-2 min-h-[3.5rem]"><?= esc($kelas['title']) ?></h3>
        <div class="flex items-center gap-2 mb-4">
            <img src="<?= esc($kelas['wpa_photo'] ?? '') ?>" alt="<?= esc($kelas['wpa_name'] ?? '') ?>" class="w-8 h-8 rounded-full object-cover flex-shrink-0">
            <span class="text-gray-400 text-sm line-clamp-1"><?= esc($kelas['wpa_name'] ?? '') ?></span>
        </div>
        <div class="flex items-center gap-2 mb-3 text-xs text-gray-500">
            <i class="fas fa-map-marker-alt text-accent flex-shrink-0"></i>
            <span class="line-clamp-1"><?= esc($kelas['location']) ?></span>
        </div>
        <div class="flex items-center justify-between text-sm text-gray-400 mb-4 mt-auto">
            <span><i class="fas fa-clock mr-1"></i> <?= esc($kelas['duration']) ?></span>
            <span><i class="fas fa-book mr-1"></i> <?= esc($kelas['modules']) ?> Modul</span>
            <span><i class="fas fa-users mr-1"></i> <?= number_to_amount($kelas['students'], 0) ?></span>
        </div>
        <div class="flex items-center justify-between mt-2 pt-4 border-t border-white/10">
            <div class="flex flex-col">
                <?php if($kelas['original_price'] > $kelas['price']): ?>
                <span class="text-gray-500 line-through text-xs">Rp <?= number_format($kelas['original_price'], 0, ',', '.') ?></span>
                <?php endif; ?>
                <span class="text-accent font-bold text-lg">Rp <?= number_format($kelas['price'], 0, ',', '.') ?></span>
            </div>
        </div>
        <a href="<?= base_url('kelas/' . $kelas['id']) ?>" class="block text-center py-3 mt-4 bg-accent text-black rounded-lg font-bold hover:bg-white transition">
            Lihat Layanan
        </a>
    </div>
</div>
