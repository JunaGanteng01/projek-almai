<?= $this->extend('admin_wpa/layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-6 flex items-center gap-4">
    <a href="<?= base_url('admin-wpa/layanan') ?>" class="w-10 h-10 flex items-center justify-center bg-white/5 rounded-xl hover:bg-white/10 transition">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-white">Detail Layanan</h1>
        <p class="text-gray-400">Informasi lengkap layanan WPA.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column: Image & Basic Info -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
            <div class="aspect-video bg-blue-500/10 flex items-center justify-center overflow-hidden">
                <?php if (!empty($layanan['photo'])): ?>
                    <img src="<?= base_url($layanan['photo']) ?>" alt="<?= esc($layanan['name']) ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <i class="fas fa-image text-5xl text-blue-500/20"></i>
                <?php endif; ?>
            </div>
            <div class="p-6">
                <h2 class="text-xl font-bold mb-4"><?= esc($layanan['name']) ?></h2>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Harga</span>
                        <span class="text-blue-400 font-bold">Rp <?= number_format($layanan['price'] ?? 0, 0, ',', '.') ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Poin</span>
                        <span class="text-yellow-500 font-bold"><?= number_format($layanan['poin_price'] ?? 0) ?> Poin</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Status</span>
                        <span class="px-2 py-0.5 bg-green-500/20 text-green-400 rounded text-[10px] font-bold uppercase"><?= esc($layanan['status'] ?? 'active') ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Description & Details -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 md:p-8">
            <h3 class="text-lg font-bold mb-4 border-b border-white/5 pb-2">Deskripsi Layanan</h3>
            <div class="prose prose-invert max-w-none text-gray-400 text-sm leading-relaxed">
                <?= nl2br(esc((string)($layanan['description'] ?? 'Tidak ada deskripsi.'))) ?>
            </div>
        </div>

        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 md:p-8">
            <h3 class="text-lg font-bold mb-4 border-b border-white/5 pb-2">Detail Lainnya</h3>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-[10px] text-gray-500 uppercase font-black mb-1">Kategori</p>
                    <p class="text-sm font-bold"><?= esc($layanan['category'] ?? '-') ?></p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-500 uppercase font-black mb-1">Spesialis</p>
                    <p class="text-sm font-bold"><?= esc($layanan['specialist'] ?? '-') ?></p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-500 uppercase font-black mb-1">Dibuat Pada</p>
                    <p class="text-sm font-bold"><?= date('d F Y', strtotime($layanan['created_at'])) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
