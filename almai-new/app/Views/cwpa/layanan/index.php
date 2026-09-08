<?= $this->extend('cwpa/layouts/main') ?>

<?= $this->section('content') ?>
<?php $pageTitle = 'Kelola Layanan';
$pageSubtitle = 'Kelola layanan yang Anda buat'; ?>

<div class="flex flex-col gap-6">
    <!-- Header with Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
        <div class="bg-[#111] border border-white/10 rounded-xl p-3 md:p-6 hover:border-accent/30 transition shadow-lg flex flex-col justify-center">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 md:w-10 md:h-10 bg-accent/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-cubes text-accent text-sm md:text-base"></i>
                </div>
                <p class="text-gray-500 text-[8px] md:text-[10px] uppercase font-black tracking-widest leading-tight">Total<br class="md:hidden"> Layanan</p>
            </div>
            <h3 class="text-xl md:text-2xl font-black text-white"><?= $totalLayanan ?></h3>
        </div>
        <div class="bg-[#111] border border-white/10 rounded-xl p-3 md:p-6 hover:border-blue-500/30 transition shadow-lg flex flex-col justify-center">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 md:w-10 md:h-10 bg-blue-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-bullhorn text-blue-400 text-sm md:text-base"></i>
                </div>
                <p class="text-gray-500 text-[8px] md:text-[10px] uppercase font-black tracking-widest leading-tight">Total<br class="md:hidden"> Advokasi</p>
            </div>
            <h3 class="text-xl md:text-2xl font-black text-white"><?= $countAdvokasi ?></h3>
        </div>
        <div class="bg-[#111] border border-white/10 rounded-xl p-3 md:p-6 hover:border-yellow-500/30 transition shadow-lg flex flex-col justify-center">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 md:w-10 md:h-10 bg-yellow-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-robot text-yellow-400 text-sm md:text-base"></i>
                </div>
                <p class="text-gray-500 text-[8px] md:text-[10px] uppercase font-black tracking-widest leading-tight">Expert<br class="md:hidden"> Advisor</p>
            </div>
            <h3 class="text-xl md:text-2xl font-black text-white"><?= $countEA ?></h3>
        </div>
        <div class="bg-[#111] border border-white/10 rounded-xl p-3 md:p-6 hover:border-purple-500/30 transition shadow-lg flex flex-col justify-center">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 md:w-10 md:h-10 bg-purple-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-gem text-purple-400 text-sm md:text-base"></i>
                </div>
                <p class="text-gray-500 text-[8px] md:text-[10px] uppercase font-black tracking-widest leading-tight">Almai<br class="md:hidden"> Ultimate</p>
            </div>
            <h3 class="text-xl md:text-2xl font-black text-white"><?= $countUltimate ?></h3>
        </div>
    </div>

    <!-- Actions Bar -->
    <div class="flex flex-col md:flex-row justify-between items-stretch md:items-center gap-4">
        <form action="<?= base_url('cwpa/dashboard/kelola-layanan') ?>" method="get" class="flex flex-col sm:flex-row gap-3 flex-grow max-w-2xl">
            <div class="relative flex-grow">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                <input type="text" name="search" value="<?= esc($search ?? '') ?>" placeholder="Cari layanan..."
                    class="w-full bg-[#111] border border-white/10 rounded-xl pl-10 pr-4 py-3 text-sm focus:border-accent outline-none transition">
            </div>
            <select name="status" onchange="this.form.submit()" class="bg-[#111] border border-white/10 rounded-xl px-4 py-3 text-sm outline-none focus:border-accent transition appearance-none cursor-pointer">
                <option value="all" <?= ($currentStatus ?? '') === 'all' ? 'selected' : '' ?>>Semua Status</option>
                <option value="active" <?= in_array($currentStatus ?? '', ['active', 'published']) ? 'selected' : '' ?>>Published / Active</option>
                <option value="draft" <?= ($currentStatus ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
                <option value="upcoming" <?= ($currentStatus ?? '') === 'upcoming' ? 'selected' : '' ?>>Upcoming</option>
                <option value="ongoing" <?= ($currentStatus ?? '') === 'ongoing' ? 'selected' : '' ?>>Ongoing</option>
                <option value="completed" <?= ($currentStatus ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
            </select>
        </form>

        <a href="<?= base_url('cwpa/dashboard/layanan/create') ?>" class="px-6 py-3.5 bg-accent text-black font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-white transition flex items-center justify-center gap-2 shadow-xl shadow-accent/5 active:scale-95">
            <i class="fas fa-plus"></i>
            Buat Layanan Baru
        </a>
    </div>

    <!-- Mobile Cards View (Visible on Small Screens) -->
    <div class="block md:hidden space-y-4">
        <?php if (empty($layananList)): ?>
            <div class="bg-[#111] border border-white/10 rounded-xl p-8 text-center text-gray-500">
                <i class="fas fa-folder-open text-4xl mb-3 block"></i>
                Belum ada layanan.
            </div>
        <?php else: ?>
            <?php foreach ($layananList as $item): ?>
                <?php
                $status = $item['status'] ?? 'draft';
                $statusClass = 'bg-gray-500/10 text-gray-500 border-gray-500/20';
                $statusLabel = ucfirst($status);

                if ($status === 'active' || $status === 'published') {
                    $statusClass = 'bg-green-500/10 text-green-400 border-green-500/20';
                    $statusLabel = 'Published';
                } elseif ($status === 'draft') {
                    $statusClass = 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20';
                } elseif ($status === 'upcoming') {
                    $statusClass = 'bg-blue-500/10 text-blue-400 border-blue-500/20';
                }
                ?>
                <div class="bg-[#111] border border-white/10 rounded-2xl p-4 relative overflow-hidden group">
                    <div class="flex gap-4 mb-4">
                        <div class="w-16 h-16 rounded-xl overflow-hidden border border-white/5 flex-shrink-0">
                            <img src="<?= esc($item['thumbnail'] ?? base_url('favicon.ico')) ?>" class="w-full h-full object-cover" onerror="this.src='<?= base_url('favicon.ico') ?>'">
                        </div>
                        <div class="flex-grow min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <span class="px-2 py-0.5 <?= $statusClass ?> text-[8px] font-black rounded-lg uppercase tracking-widest border">
                                    <?= $statusLabel ?>
                                </span>
                                <span class="text-[9px] text-gray-600 font-mono"><?= esc($item['slug'] ?? '-') ?></span>
                            </div>
                            <h4 class="font-black text-white text-sm mt-1 mb-1 truncate"><?= esc($item['name'] ?? $item['title'] ?? 'Untitled') ?></h4>
                            <p class="text-[9px] text-accent uppercase font-black tracking-widest"><?= esc($item['category'] ?? '-') ?> / <?= esc($item['subcategory'] ?? $item['type'] ?? '-') ?></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 border-t border-white/5 pt-4 mb-4">
                        <div class="bg-black/30 p-2 rounded-xl border border-white/5">
                            <p class="text-[8px] text-gray-500 uppercase font-black tracking-tighter mb-1">Harga Rupiah</p>
                            <?php if (($item['type'] ?? '') === 'artikel' || ($item['subcategory'] ?? '') === 'Artikel'): ?>
                                <p class="text-xs font-bold text-gray-500">-</p>
                            <?php else: ?>
                                <p class="text-sm font-black text-white">Rp <?= number_format($item['price'] ?? 0, 0, ',', '.') ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="bg-black/30 p-2 rounded-xl border border-white/5">
                            <p class="text-[8px] text-gray-500 uppercase font-black tracking-tighter mb-1">Harga Poin</p>
                            <div class="flex items-center gap-1">
                                <i class="fas fa-coins text-yellow-500 text-[10px]"></i>
                                <p class="text-sm font-black text-yellow-500"><?= number_format($item['poin_price'] ?? 0, 0, ',', '.') ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <a href="<?= base_url('layanan/' . $item['slug']) ?>" target="_blank" class="flex-1 py-2.5 bg-white/5 border border-white/10 rounded-xl hover:bg-accent hover:text-black transition text-center text-xs font-bold">
                            <i class="fas fa-external-link-alt mr-1"></i> Lihat
                        </a>
                        <a href="<?= base_url('cwpa/dashboard/layanan/edit/' . ($item['type'] ?? 'layanan') . '/' . $item['id']) ?>" class="flex-1 py-2.5 bg-white/5 border border-white/10 rounded-xl hover:bg-blue-500 hover:text-white transition text-center text-xs font-bold">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </a>
                        <button onclick="confirmDelete('<?= $item['type'] ?? 'layanan' ?>', <?= $item['id'] ?>, '<?= esc($item['name'] ?? $item['title'] ?? 'Layanan') ?>')" class="w-12 py-2.5 bg-white/5 border border-white/10 rounded-xl hover:bg-red-500 hover:text-white transition text-center">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Desktop Table View (Visible on Medium screens and Above) -->
    <div class="hidden md:block bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-black/50 border-b border-white/10">
                        <th class="px-6 py-5 text-[10px] font-black text-gray-500 uppercase tracking-widest">Layanan</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-500 uppercase tracking-widest">Kategori</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-500 uppercase tracking-widest">Harga Rupiah</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-500 uppercase tracking-widest">Harga Poin</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-500 uppercase tracking-widest">Status</th>
                        <th class="px-6 py-5 text-[10px] font-black text-gray-500 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php if (empty($layananList)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-folder-open text-4xl mb-3 block"></i>
                                Belum ada layanan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($layananList as $item): ?>
                            <tr class="hover:bg-white/[0.02] transition">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl overflow-hidden border border-white/10 flex-shrink-0">
                                            <img src="<?= esc($item['thumbnail'] ?? base_url('favicon.ico')) ?>" class="w-full h-full object-cover" onerror="this.src='<?= base_url('favicon.ico') ?>'">
                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="font-black text-sm text-white truncate"><?= esc($item['name'] ?? $item['title'] ?? 'Untitled') ?></h4>
                                            <p class="text-[10px] text-gray-600 font-mono truncate"><?= esc($item['slug'] ?? '-') ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-xs font-black text-white"><?= esc($item['category'] ?? '-') ?></span>
                                        <span class="text-[9px] text-gray-500 uppercase tracking-wider font-bold mt-0.5"><?= esc($item['subcategory'] ?? $item['type'] ?? '-') ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <?php if (($item['type'] ?? '') === 'artikel' || ($item['subcategory'] ?? '') === 'Artikel'): ?>
                                            <span class="text-sm font-medium text-gray-600">-</span>
                                        <?php else: ?>
                                            <span class="text-sm font-black text-white">Rp <?= number_format($item['price'] ?? 0, 0, ',', '.') ?></span>
                                            <?php if ($item['has_packages'] ?? false): ?>
                                                <span class="text-[9px] text-gray-500 mt-1 uppercase font-bold tracking-tighter">Mulai dari</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-1.5">
                                        <i class="fas fa-coins text-yellow-500 text-[10px]"></i>
                                        <span class="text-sm font-black text-yellow-500 font-mono"><?= number_format($item['poin_price'] ?? 0, 0, ',', '.') ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <?php
                                    $status = $item['status'] ?? 'draft';
                                    $statusClass = 'bg-gray-500/10 text-gray-500 border-gray-500/20';
                                    $statusLabel = ucfirst($status);

                                    if ($status === 'active' || $status === 'published') {
                                        $statusClass = 'bg-green-500/10 text-green-400 border-green-500/20';
                                        $statusLabel = 'Published';
                                    } elseif ($status === 'draft') {
                                        $statusClass = 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20';
                                    } elseif ($status === 'upcoming') {
                                        $statusClass = 'bg-blue-500/10 text-blue-400 border-blue-500/20';
                                    }
                                    ?>
                                    <span class="px-2 py-1 <?= $statusClass ?> text-[9px] font-black rounded-lg uppercase tracking-widest border">
                                        <?= $statusLabel ?>
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="<?= base_url('layanan/' . $item['slug']) ?>" target="_blank" class="w-8 h-8 flex items-center justify-center bg-white/5 border border-white/10 rounded-lg hover:bg-accent hover:text-black transition" title="Lihat">
                                            <i class="fas fa-external-link-alt text-[10px]"></i>
                                        </a>
                                        <a href="<?= base_url('cwpa/dashboard/layanan/edit/' . ($item['type'] ?? 'layanan') . '/' . $item['id']) ?>" class="w-8 h-8 flex items-center justify-center bg-white/5 border border-white/10 rounded-lg hover:bg-blue-500 hover:text-white transition" title="Edit">
                                            <i class="fas fa-edit text-[10px]"></i>
                                        </a>
                                        <button onclick="confirmDelete('<?= $item['type'] ?? 'layanan' ?>', <?= $item['id'] ?>, '<?= esc($item['name'] ?? $item['title'] ?? 'Layanan') ?>')" class="w-8 h-8 flex items-center justify-center bg-white/5 border border-white/10 rounded-lg hover:bg-red-500 hover:text-white transition" title="Hapus">
                                            <i class="fas fa-trash text-[10px]"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 md:p-8 max-w-sm w-full relative z-10 text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center bg-red-500/20">
            <i class="fas fa-trash text-3xl text-red-500"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Hapus Layanan?</h3>
        <p class="text-gray-400 text-sm mb-6">Apakah Anda yakin ingin menghapus <br><span id="deleteItemName" class="text-white font-medium"></span>?</p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 py-3 border border-white/20 rounded-xl hover:bg-white/5 transition text-sm">Batal</button>
            <form id="deleteForm" method="post" class="flex-1">
                <?= csrf_field() ?>
                <button type="submit" class="w-full py-3 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600 transition text-sm">Hapus</button>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmDelete(type, id, name) {
        document.getElementById('deleteItemName').textContent = name;
        document.getElementById('deleteForm').action = `<?= base_url('cwpa/dashboard/layanan/delete') ?>/${type}/${id}`;

        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>

<?= $this->endSection() ?>

