<?= $this->extend('user/partials/layout') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <h2 class="text-lg md:text-xl font-bold">Sertifikat Saya</h2>
    <p class="text-gray-500 text-sm">Sertifikat yang Anda peroleh setelah menyelesaikan layanan</p>
</div>

<?php if (empty($sertifikatList)): ?>
<div class="bg-[#111] border border-white/10 rounded-xl p-12 text-center">
    <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-certificate text-gray-600 text-3xl"></i>
    </div>
    <h3 class="font-bold mb-2">Belum Ada Sertifikat</h3>
    <p class="text-gray-500 text-sm mb-4">Selesaikan layanan untuk mendapatkan sertifikat</p>
    <a href="<?= base_url('layanan') ?>" class="inline-block px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-sm">Jelajahi Layanan</a>
</div>
<?php else: ?>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
    <?php foreach ($sertifikatList as $cert): ?>
    <div class="bg-gradient-to-br from-yellow-500/10 to-orange-500/10 border border-yellow-500/30 rounded-xl p-6 hover:border-yellow-500/50 transition">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-14 h-14 bg-yellow-500/20 rounded-full flex items-center justify-center">
                <i class="fas fa-certificate text-yellow-500 text-2xl"></i>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="font-bold truncate"><?= esc($cert['layanan_name']) ?></h3>
                <p class="text-xs text-gray-500">oleh <?= esc($cert['wpa_name'] ?? 'ALMAI Team') ?></p>
            </div>
        </div>
        
        <div class="bg-black/30 rounded-lg p-3 mb-4">
            <p class="text-xs text-gray-500 mb-1">Nomor Sertifikat</p>
            <p class="font-mono text-sm font-bold text-yellow-500 truncate"><?= esc($cert['certificate_number']) ?></p>
        </div>
        
        <p class="text-xs text-gray-500 mb-4">
            <i class="fas fa-calendar mr-1"></i> Diterbitkan: <?= date('d M Y', strtotime($cert['issued_at'])) ?>
        </p>
        
        <div class="flex gap-2">
            <a href="<?= base_url('certificate/' . $cert['certificate_number']) ?>" target="_blank" class="flex-1 text-center py-2 bg-yellow-500 text-black font-bold rounded-lg text-sm hover:bg-yellow-400 transition">
                <i class="fas fa-eye mr-1"></i> Lihat
            </a>
            <a href="<?= base_url('certificate/' . $cert['certificate_number'] . '/download') ?>" class="px-4 py-2 border border-yellow-500/50 rounded-lg text-sm text-yellow-500 hover:bg-yellow-500/10 transition">
                <i class="fas fa-download"></i>
            </a>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<?= $this->endSection() ?>
