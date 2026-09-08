<?= $this->extend('wpa/layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold">Artikel Saya</h1>
            <p class="text-gray-400 text-sm">Kelola artikel yang Anda buat</p>
        </div>
        <a href="<?= base_url('wpa/dashboard/artikel/create') ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-accent text-black font-medium rounded-lg hover:bg-accent/90 transition">
            <i class="fas fa-plus"></i> Buat Artikel
        </a>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
    <div class="bg-accent/20 border border-accent/30 text-accent px-4 py-3 rounded-xl"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
    <div class="bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <!-- Stats -->
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-500"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-yellow-500"><?= $pendingCount ?></p>
                    <p class="text-xs text-gray-400">Menunggu</p>
                </div>
            </div>
        </div>
        <div class="bg-accent/10 border border-accent/30 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-accent/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check text-accent"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-accent"><?= $approvedCount ?></p>
                    <p class="text-xs text-gray-400">Disetujui</p>
                </div>
            </div>
        </div>
        <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-times text-red-500"></i>
                </div>
                <div>
                    <p class="text-2xl font-bold text-red-500"><?= $rejectedCount ?></p>
                    <p class="text-xs text-gray-400">Ditolak</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Artikel List -->
    <div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
        <?php if (empty($artikelList)): ?>
        <div class="p-8 text-center text-gray-500">
            <i class="fas fa-newspaper text-4xl mb-4"></i>
            <p>Belum ada artikel</p>
            <a href="<?= base_url('wpa/dashboard/artikel/create') ?>" class="inline-block mt-4 px-6 py-2 bg-accent text-black rounded-xl font-bold text-sm hover:bg-white transition">
                Buat Artikel Pertama
            </a>
        </div>
        <?php else: ?>
        <div class="divide-y divide-white/5">
            <?php foreach ($artikelList as $artikel): ?>
            <div class="p-4 hover:bg-white/5 transition">
                <div class="flex gap-4">
                    <img src="<?= esc($artikel['thumbnail']) ?>" alt="" class="w-24 h-16 md:w-32 md:h-20 object-cover rounded-lg flex-shrink-0">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <h3 class="font-bold text-sm md:text-base line-clamp-1"><?= esc($artikel['title']) ?></h3>
                                <p class="text-xs text-gray-500 mt-1"><?= esc($artikel['category']) ?> • <?= esc($artikel['read_time']) ?></p>
                            </div>
                            <div class="flex-shrink-0">
                                <?php if ($artikel['verification_status'] === 'approved'): ?>
                                <span class="px-2 py-1 bg-accent/20 text-accent rounded-full text-xs"><i class="fas fa-check mr-1"></i>Approved</span>
                                <?php elseif ($artikel['verification_status'] === 'rejected'): ?>
                                <span class="px-2 py-1 bg-red-500/20 text-red-400 rounded-full text-xs"><i class="fas fa-times mr-1"></i>Rejected</span>
                                <?php else: ?>
                                <span class="px-2 py-1 bg-yellow-500/20 text-yellow-500 rounded-full text-xs"><i class="fas fa-clock mr-1"></i>Pending</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if ($artikel['verification_status'] === 'rejected' && $artikel['rejection_reason']): ?>
                        <div class="mt-2 p-2 bg-red-500/10 border border-red-500/20 rounded-lg">
                            <p class="text-xs text-red-400"><i class="fas fa-exclamation-circle mr-1"></i> <?= esc($artikel['rejection_reason']) ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <div class="flex items-center gap-4 mt-3">
                            <span class="text-xs text-gray-500">
                                <?php if ($artikel['is_free'] || $artikel['poin_price'] == 0): ?>
                                <span class="text-accent font-bold">GRATIS</span>
                                <?php else: ?>
                                <span class="text-yellow-500"><i class="fas fa-coins mr-1"></i><?= number_format($artikel['poin_price']) ?> Poin</span>
                                <?php endif; ?>
                            </span>
                            <span class="text-xs text-gray-500"><?= date('d M Y', strtotime($artikel['created_at'])) ?></span>
                            
                            <div class="flex items-center gap-2 ml-auto">
                                <?php if ($artikel['verification_status'] !== 'approved'): ?>
                                <a href="<?= base_url('wpa/dashboard/artikel/edit/' . $artikel['id']) ?>" class="p-2 bg-blue-500/20 text-blue-400 rounded-lg hover:bg-blue-500 hover:text-white transition text-xs" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?= base_url('wpa/dashboard/artikel/delete/' . $artikel['id']) ?>" method="post" class="inline" onsubmit="return confirm('Hapus artikel ini?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="p-2 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500 hover:text-white transition text-xs" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                <?php else: ?>
                                <a href="<?= base_url('artikel/' . $artikel['id']) ?>" target="_blank" class="p-2 bg-accent/20 text-accent rounded-lg hover:bg-accent hover:text-black transition text-xs" title="Lihat">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
