<?php
$this->setVar('pageTitle', 'Data Layanan');
$this->setVar('pageSubtitle', 'Daftar produk & layanan platform (Read Only)');
?>
<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-6">
    <div class="bg-[#111] border border-white/10 rounded-xl p-3 md:p-4">
        <div class="flex items-center gap-2 md:gap-3">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-blue-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-concierge-bell text-blue-500 text-sm md:text-base"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-lg md:text-2xl font-bold truncate leading-tight"><?= $stats['total'] ?></p>
                <p class="text-[9px] md:text-xs text-gray-400 truncate uppercase tracking-tighter md:tracking-normal">Total Produk</p>
            </div>
        </div>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-3 md:p-4">
        <div class="flex items-center gap-2 md:gap-3">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-green-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-file-alt text-green-500 text-sm md:text-base"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-lg md:text-2xl font-bold text-green-500 truncate leading-tight"><?= $stats['artikel'] ?></p>
                <p class="text-[9px] md:text-xs text-gray-400 truncate uppercase tracking-tighter md:tracking-normal">Artikel</p>
            </div>
        </div>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-3 md:p-4">
        <div class="flex items-center gap-2 md:gap-3">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-yellow-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-calendar-alt text-yellow-500 text-sm md:text-base"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-lg md:text-2xl font-bold text-yellow-500 truncate leading-tight"><?= $stats['event'] ?></p>
                <p class="text-[9px] md:text-xs text-gray-400 truncate uppercase tracking-tighter md:tracking-normal">Webinar</p>
            </div>
        </div>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-3 md:p-4">
        <div class="flex items-center gap-2 md:gap-3">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-purple-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-robot text-purple-500 text-sm md:text-base"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-lg md:text-2xl font-bold text-purple-500 truncate leading-tight"><?= $stats['tools'] ?></p>
                <p class="text-[9px] md:text-xs text-gray-400 truncate uppercase tracking-tighter md:tracking-normal">Tools</p>
            </div>
        </div>
    </div>
</div>

<!-- Header Actions & Filters -->
<div class="flex flex-col gap-4 mb-6">
    <div class="flex items-center gap-2 overflow-x-auto scrollbar-hide -mx-4 px-4">
        <a href="<?= base_url('laporan-kegiatan/layanan') ?>" class="px-4 py-2 rounded-full text-[11px] md:text-sm font-bold whitespace-nowrap transition <?= empty($currentKategori) ? 'bg-accent text-black' : 'bg-white/5 text-gray-400 hover:bg-white/10' ?>">SEMUA</a>
        <?php foreach ($kategoriList as $kat): ?>
            <a href="?kategori=<?= $kat['slug'] ?>" class="px-4 py-2 rounded-full text-[11px] md:text-sm font-bold whitespace-nowrap transition <?= ($currentKategori ?? '') === $kat['slug'] ? 'bg-accent text-black' : 'bg-white/5 text-gray-400 hover:bg-white/10' ?>"><?= strtoupper(esc($kat['name'])) ?></a>
        <?php endforeach; ?>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <select onchange="window.location.href='?kategori=<?= $currentKategori ?>&subcategory='+this.value" class="w-full bg-[#111] border border-white/20 rounded-xl px-4 py-3 text-sm focus:border-accent focus:outline-none cursor-pointer">
            <option value="">Semua Subkategori</option>
            <option value="artikel" <?= ($currentSubcategory ?? '') === 'artikel' ? 'selected' : '' ?>>Artikel</option>
            <option value="webinar" <?= ($currentSubcategory ?? '') === 'webinar' ? 'selected' : '' ?>>Webinar</option>
            <option value="workshop" <?= ($currentSubcategory ?? '') === 'workshop' ? 'selected' : '' ?>>Workshop</option>
            <option value="ea" <?= ($currentSubcategory ?? '') === 'ea' ? 'selected' : '' ?>>Expert Advisor</option>
            <option value="toolkit" <?= ($currentSubcategory ?? '') === 'toolkit' ? 'selected' : '' ?>>Almai Toolkits</option>
            <option value="vip_member" <?= ($currentSubcategory ?? '') === 'vip_member' ? 'selected' : '' ?>>VIP Member</option>
        </select>
    </div>
</div>

<!-- Services List -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto scrollbar-hide">
        <table class="w-full min-w-[750px]">
            <thead class="bg-black/80">
                <tr>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 w-12 text-center uppercase tracking-widest">No</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Layanan</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Kategori</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Spesialis</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest hide-mobile">Harga</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Status</th>
                    <th class="text-center px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($layananList)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center text-gray-500">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center">
                                    <i class="fas fa-concierge-bell text-2xl"></i>
                                </div>
                                <p class="text-sm font-medium">Tidak ada layanan ditemukan</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1;
                    foreach ($layananList as $layanan): ?>
                        <tr class="hover:bg-white/5 transition group">
                            <td class="px-4 py-4 text-gray-500 text-sm text-center font-mono"><?= sprintf('%02d', $no++) ?></td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <?php if (!empty($layanan['thumbnail'])): ?>
                                        <img src="<?= base_url('file/' . $layanan['thumbnail']) ?>" class="w-11 h-11 rounded-xl object-cover border border-white/10 group-hover:border-accent/30 transition">
                                    <?php else: ?>
                                        <div class="w-11 h-11 rounded-xl bg-accent/10 flex items-center justify-center text-accent">
                                            <i class="fas fa-cube text-sm"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="min-w-0">
                                        <p class="font-bold text-sm truncate text-gray-200 group-hover:text-white transition"><?= esc($layanan['name'] ?? $layanan['title'] ?? '-') ?></p>
                                        <p class="text-[10px] text-gray-500 uppercase tracking-widest font-black mt-0.5 opacity-60"><?= esc($layanan['subcategory']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="px-2.5 py-1 text-[10px] font-black rounded-lg uppercase tracking-wider <?= $layanan['kategori'] === 'Advokasi' ? 'bg-blue-500/20 text-blue-400' : ($layanan['kategori'] === 'Expert Advisor' ? 'bg-purple-500/20 text-purple-400' : 'bg-accent/20 text-accent') ?>">
                                    <?= esc($layanan['kategori']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <?php if (!empty($layanan['specialist'])): ?>
                                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg border border-white/10 bg-white/5 text-gray-300 uppercase">
                                        <?= esc($layanan['specialist']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-600 text-xs">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-4 hide-mobile">
                                <div class="flex flex-col gap-1">
                                    <?php if (!empty($layanan['has_packages'])): ?>
                                        <div class="text-sm">
                                            <span class="text-accent font-bold">Rp <?= number_format($layanan['min_price'], 0, ',', '.') ?></span>
                                            <?php if ($layanan['min_price'] != $layanan['max_price']): ?>
                                                <span class="text-gray-500 mx-1">-</span>
                                                <span class="text-accent font-bold">Rp <?= number_format($layanan['max_price'], 0, ',', '.') ?></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php elseif (!empty($layanan['price']) && $layanan['price'] > 0): ?>
                                        <span class="text-sm font-black text-accent">Rp <?= number_format($layanan['price'], 0, ',', '.') ?></span>
                                    <?php else: ?>
                                        <span class="text-[10px] font-black text-gray-600 uppercase tracking-widest">FREE</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="px-2.5 py-1 text-[10px] font-black rounded-lg uppercase tracking-wider <?= ($layanan['status'] === 'aktif' || $layanan['status'] === 'active') ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' ?>">
                                    <?= strtoupper(esc($layanan['status'])) ?>
                                </span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="text-xs text-gray-500 italic">Read Only</span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
<?= $this->endSection() ?>
