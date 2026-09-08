<?php 
$this->setVar('pageTitle', 'Katalog Merchandise');
$this->setVar('pageSubtitle', 'Daftar merchandise yang tersedia untuk penukaran poin (Read Only)');
?>
<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
    <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">
        <a href="<?= base_url('laporan-kegiatan/merchandise/redemptions') ?>" class="w-full sm:w-auto px-4 py-2 border border-white/20 rounded-xl hover:border-accent hover:text-accent transition text-sm text-center font-bold">
            <i class="fas fa-exchange-alt mr-2 text-blue-500"></i> Lihat Penukaran
        </a>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
    <div class="bg-[#111] border border-white/10 rounded-xl p-4 shadow-xl">
        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Total Item</p>
        <p class="text-3xl font-black text-purple-500"><?= $stats['total'] ?></p>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-4 shadow-xl">
        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Status Aktif</p>
        <p class="text-3xl font-black text-accent"><?= $stats['active'] ?></p>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-4 shadow-xl">
        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Total Penukaran</p>
        <p class="text-3xl font-black text-blue-500"><?= $stats['redemptions'] ?></p>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-4 shadow-xl">
        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Pending Request</p>
        <p class="text-3xl font-black text-yellow-500"><?= $stats['pending'] ?></p>
    </div>
</div>

<!-- Merchandise Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php if (empty($merchandise)): ?>
    <div class="col-span-full bg-[#111] border border-white/10 rounded-xl p-12 text-center shadow-2xl">
        <i class="fas fa-store text-gray-600 text-4xl mb-4 opacity-20"></i>
        <p class="text-gray-500 font-bold uppercase tracking-widest">Belum ada merchandise</p>
    </div>
    <?php else: ?>
    <?php foreach ($merchandise as $item): ?>
    <div class="bg-[#111] border border-white/10 rounded-xl overflow-hidden hover:border-accent/30 transition shadow-2xl group flex flex-col h-full">
        <div class="aspect-video bg-gray-900 overflow-hidden relative">
            <?php if (!empty($item['image'])): ?>
            <img src="<?= base_url($item['image']) ?>" alt="<?= esc($item['name']) ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
            <?php else: ?>
            <div class="w-full h-full flex items-center justify-center opacity-20">
                <i class="fas fa-image text-gray-600 text-4xl"></i>
            </div>
            <?php endif; ?>
            <div class="absolute top-3 right-3">
                <span class="px-2 py-1 rounded text-[10px] font-black uppercase tracking-widest shadow-xl <?= $item['status'] === 'active' ? 'bg-accent text-black' : 'bg-red-500 text-white' ?>">
                    <?= $item['status'] === 'active' ? 'Aktif' : 'Nonaktif' ?>
                </span>
            </div>
        </div>
        <div class="p-5 flex flex-col flex-1">
            <h3 class="font-black text-white text-lg mb-1 leading-tight"><?= esc($item['name']) ?></h3>
            <p class="text-xs text-gray-500 mb-4 line-clamp-2"><?= esc($item['description'] ?? '-') ?></p>
            <div class="mt-auto pt-4 border-t border-white/5 flex items-center justify-between">
                <span class="text-yellow-500 font-black text-base italic"><i class="fas fa-coins mr-1"></i> <?= number_format($item['points_required']) ?> Poin</span>
                <span class="text-[10px] text-gray-500 font-black uppercase tracking-widest">
                   <i class="fas fa-box mr-1"></i> Stok: <?= $item['unlimited_stock'] ? '∞' : number_format($item['stock']) ?>
                </span>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
