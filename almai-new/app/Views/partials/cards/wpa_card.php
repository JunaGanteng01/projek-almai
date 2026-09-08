<?php
$certifications = is_string($wpa['certifications']) ? json_decode($wpa['certifications'], true) : ($wpa['certifications'] ?? []);
$wpaSlug = $wpa['slug'] ?? $wpa['id'];
$refCode = !empty($wpa['referral_code']) ? $wpa['referral_code'] : $wpaSlug;

$photoUrl = $wpa['photo'];
if (strpos($photoUrl, 'uploads/') === 0) {
    $photoUrl = base_url('file/' . $photoUrl);
} elseif (strpos($photoUrl, 'images/') === 0) {
    $photoUrl = base_url($photoUrl);
}
?>
<div class="bg-[#111] rounded-2xl border border-white/10 overflow-hidden hover:border-accent transition group flex flex-col" data-aos="fade-up">
    <div class="relative aspect-square border-b border-white/10">
        <img src="<?= esc($photoUrl) ?>" alt="<?= esc($wpa['name']) ?>" class="w-full h-full object-cover object-top group-hover:scale-105 transition duration-500">
        <div class="absolute top-4 right-4 bg-accent text-black px-3 py-1 rounded-full text-xs font-bold">
            ⭐ <?= (float)($wpa['rating'] ?? 5.0) ?>
        </div>
        <div class="absolute bottom-4 left-4 flex flex-wrap gap-1">
            <?php foreach ($certifications as $cert): ?>
                <span class="bg-black/80 text-accent text-xs px-2 py-1 rounded"><?= esc($cert) ?></span>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="p-6 flex flex-col h-full">
        <h3 class="text-xl font-bold mb-2 min-h-[3.5rem] line-clamp-2"><?= esc($wpa['name']) ?></h3>
        <p class="text-accent text-sm mb-4 min-h-[2.5rem] line-clamp-2"><?= esc($wpa['specialty']) ?></p>
        <div class="flex flex-col gap-2 text-xs text-gray-400 mb-4">
            <div class="flex items-center gap-1.5" title="Pengalaman">
                <i class="fas fa-briefcase text-accent/70"></i>
                <span><?= esc($wpa['experience']) ?></span>
            </div>
            <div class="flex items-center gap-1.5" title="Total Layanan">
                <i class="fas fa-layer-group text-accent/70"></i>
                <span><?= esc($wpa['total_classes']) ?> Layanan</span>
            </div>
            <div class="flex items-center gap-1.5" title="Total User">
                <i class="fas fa-users text-accent/70"></i>
                <span><?= number_format($wpa['referral_count'] ?? 0) ?> User</span>
            </div>
        </div>
        <div class="flex gap-2 mt-auto">
            <a href="<?= base_url('wpa/' . $wpaSlug) ?>" class="flex-1 text-center py-2.5 border border-white/20 rounded-lg font-bold hover:bg-accent hover:text-black hover:border-accent transition text-xs">
                Lihat Profil
            </a>
            <a href="<?= base_url('register?ref=' . esc($refCode)) ?>" class="flex-1 text-center py-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold rounded-lg transition text-xs shadow-[0_0_15px_rgba(51,232,24,0.3)] flex items-center justify-center">
                <i class="fas fa-plus mr-1.5 text-[11px]"></i> + IKUTI WPA
            </a>
        </div>
    </div>
</div>
