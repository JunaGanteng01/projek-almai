<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
    <div>
        <h2 class="text-xl font-bold">Merchandise</h2>
        <p class="text-gray-500 text-sm">Kelola merchandise untuk penukaran poin</p>
    </div>
    <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">
        <a href="<?= base_url('admin/merchandise/redemptions') ?>" class="w-full sm:w-auto px-4 py-2 border border-white/20 rounded-xl hover:border-accent hover:text-accent transition text-sm text-center">
            <i class="fas fa-exchange-alt mr-2"></i> Lihat Penukaran
        </a>
        <a href="<?= base_url('admin/merchandise/create') ?>" class="w-full sm:w-auto px-4 py-2 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-sm text-center">
            <i class="fas fa-plus mr-2"></i> Tambah Merchandise
        </a>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-store text-purple-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= $stats['total'] ?></p>
                <p class="text-xs text-gray-500">Total Item</p>
            </div>
        </div>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-accent/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-accent"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= $stats['active'] ?></p>
                <p class="text-xs text-gray-500">Aktif</p>
            </div>
        </div>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-exchange-alt text-blue-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= $stats['redemptions'] ?></p>
                <p class="text-xs text-gray-500">Total Penukaran</p>
            </div>
        </div>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-yellow-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= $stats['pending'] ?></p>
                <p class="text-xs text-gray-500">Pending</p>
            </div>
        </div>
    </div>
</div>

<!-- Merchandise Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php if (empty($merchandise)): ?>
    <div class="col-span-full bg-[#111] border border-white/10 rounded-xl p-12 text-center">
        <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-store text-gray-600 text-2xl"></i>
        </div>
        <p class="text-gray-500">Belum ada merchandise</p>
        <a href="<?= base_url('admin/merchandise/create') ?>" class="inline-block mt-4 px-4 py-2 bg-accent text-black font-bold rounded-xl text-sm">Tambah Merchandise</a>
    </div>
    <?php else: ?>
    <?php foreach ($merchandise as $item): ?>
    <div class="bg-[#111] border border-white/10 rounded-xl overflow-hidden hover:border-accent/50 transition">
        <div class="aspect-video bg-gray-800 overflow-hidden">
            <?php if (!empty($item['image'])): ?>
            <img src="<?= base_url($item['image']) ?>" alt="<?= esc($item['name']) ?>" class="w-full h-full object-cover">
            <?php else: ?>
            <div class="w-full h-full flex items-center justify-center">
                <i class="fas fa-image text-gray-600 text-4xl"></i>
            </div>
            <?php endif; ?>
        </div>
        <div class="p-4">
            <div class="flex items-start justify-between mb-2">
                <h3 class="font-bold"><?= esc($item['name']) ?></h3>
                <span class="px-2 py-1 rounded-full text-xs <?= $item['status'] === 'active' ? 'bg-accent/20 text-accent' : 'bg-gray-500/20 text-gray-400' ?>">
                    <?= $item['status'] === 'active' ? 'Aktif' : 'Nonaktif' ?>
                </span>
            </div>
            <p class="text-sm text-gray-500 mb-4 line-clamp-2"><?= esc($item['description'] ?? '-') ?></p>
            <div class="flex items-center justify-between mb-4">
                <span class="text-yellow-500 font-bold"><i class="fas fa-coins mr-1"></i> <?= number_format($item['points_required']) ?></span>
                <span class="text-xs text-gray-500">
                    Stok: <?= $item['unlimited_stock'] ? '∞' : number_format($item['stock']) ?>
                </span>
            </div>
            <div class="flex gap-2">
                <a href="<?= base_url('admin/merchandise/edit/' . $item['id']) ?>" class="flex-1 py-2 border border-white/20 rounded-lg text-center text-sm hover:border-accent hover:text-accent transition">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <form action="<?= base_url('admin/merchandise/delete/' . $item['id']) ?>" method="post" class="flex-1" onsubmit="return confirm('Hapus merchandise ini?')">
                    <?= csrf_field() ?>
                    <button type="submit" class="w-full py-2 border border-red-500/30 text-red-400 rounded-lg text-sm hover:bg-red-500/10 transition">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
