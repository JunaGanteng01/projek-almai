<?php
$certifications = isset($client['certifications']) ? (is_string($client['certifications']) ? json_decode($client['certifications'], true) : $client['certifications']) : [];
if (empty($certifications)) {
    $certifications = ['BAPPEBTI', 'LSP PBK', 'OJK']; // Default certifications for CWPA
}
$cwpaSlug = $client['slug'] ?? $client['id'];

$photoUrl = $client['photo'];
if (empty($photoUrl)) {
    $photoUrl = base_url('images/wpa/default.jpg');
} elseif (strpos($photoUrl, 'uploads/') === 0) {
    $photoUrl = base_url('file/' . $photoUrl);
} elseif (strpos($photoUrl, 'images/') === 0) {
    $photoUrl = base_url($photoUrl);
} else {
    $photoUrl = base_url('file/' . ltrim(preg_replace('/^writable\//', '', $photoUrl), '/\\'));
}
?>
<div class="bg-[#111] rounded-2xl border border-white/10 overflow-hidden hover:border-accent transition group flex flex-col" data-aos="fade-up">
    <div class="relative aspect-square border-b border-white/10">
        <img src="<?= esc($photoUrl) ?>" alt="<?= esc($client['name']) ?>" class="w-full h-full object-cover object-top group-hover:scale-105 transition duration-500 grayscale group-hover:grayscale-0">
        <div class="absolute top-4 right-4 bg-accent text-black px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
            <i class="fas fa-star text-[10px]"></i> <?= (float)($client['rating'] ?? 4.9) ?>
        </div>
        <div class="absolute bottom-4 left-4 flex flex-wrap gap-1">
            <?php foreach ($certifications as $cert): ?>
                <span class="bg-black/80 text-accent text-[10px] uppercase font-black px-2 py-1 rounded-md border border-accent/20"><?= esc($cert) ?></span>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="p-6 flex flex-col h-full">
        <div class="mb-4">
            <h3 class="text-xl font-bold text-white mb-2 line-clamp-2 min-h-[3.5rem]"><?= esc($client['name']) ?></h3>
            <p class="text-accent text-sm font-bold"><?= esc($client['specialty'] ?? '') ?></p>
        </div>
        
        <div class="flex flex-col gap-2.5 text-xs text-gray-400 mb-6 font-medium">
            <div class="flex items-center gap-3" title="Pengalaman">
                <i class="fas fa-briefcase text-accent/70 w-4"></i>
                <span><?= esc($client['experience'] ?? '') ?></span>
            </div>
            <div class="flex items-center gap-3" title="Total Layanan">
                <i class="fas fa-layer-group text-accent/70 w-4"></i>
                <span><?= esc($client['total_classes'] ?? 0) ?> Layanan</span>
            </div>
            <div class="flex items-center gap-3" title="Total User">
                <i class="fas fa-users text-accent/70 w-4"></i>
                <span><?= number_format($client['referral_count'] ?? 0) ?> User</span>
            </div>
        </div>
        
        <a href="<?= base_url('cwpa/' . $cwpaSlug) ?>" class="block text-center py-3.5 border border-white/10 rounded-xl font-black text-sm uppercase tracking-widest hover:bg-accent hover:text-black hover:border-accent transition-all mt-auto group-hover:border-accent/40">
            Lihat Profil
        </a>
    </div>
</div>
