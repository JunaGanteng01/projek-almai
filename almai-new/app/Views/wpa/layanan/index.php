<?= $this->extend('wpa/layouts/main') ?>

<?= $this->section('content') ?>
<?php $pageTitle = 'Layanan Saya';
$pageSubtitle = 'Kelola layanan yang Anda buat'; ?>

<div class="flex flex-col gap-6">
    <!-- Header with Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-[#111] border border-white/10 rounded-xl p-4 md:p-6">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-accent/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-cubes text-accent"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold"><?= $totalLayanan ?></h3>
            <p class="text-gray-500 text-xs text-uppercase">Total Layanan</p>
        </div>
        <div class="bg-[#111] border border-white/10 rounded-xl p-4 md:p-6">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-bullhorn text-blue-400"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold"><?= $countAdvokasi ?></h3>
            <p class="text-gray-500 text-xs text-uppercase">Advokasi</p>
        </div>
        <div class="bg-[#111] border border-white/10 rounded-xl p-4 md:p-6">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-robot text-yellow-400"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold"><?= $countEA ?></h3>
            <p class="text-gray-500 text-xs text-uppercase">Expert Advisor</p>
        </div>
        <div class="bg-[#111] border border-white/10 rounded-xl p-4 md:p-6">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-gem text-purple-400"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold"><?= $countUltimate ?></h3>
            <p class="text-gray-500 text-xs text-uppercase">Ultimate</p>
        </div>
    </div>

    <!-- Actions Bar -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
        <form action="<?= base_url('wpa/dashboard/layanan') ?>" method="get" class="flex flex-wrap gap-3 w-full lg:w-auto">
            <div class="relative w-full md:w-64">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
                <input type="text" name="search" value="<?= esc($search ?? '') ?>" placeholder="Cari layanan..."
                    class="w-full bg-[#111] border border-white/10 rounded-lg pl-10 pr-4 py-2 text-sm focus:border-accent outline-none">
            </div>
            <select name="status" onchange="this.form.submit()" class="bg-[#111] border border-white/10 rounded-lg px-4 py-2 text-sm outline-none focus:border-accent">
                <option value="all" <?= ($currentStatus ?? '') === 'all' ? 'selected' : '' ?>>Semua Status</option>
                <option value="aktif" <?= ($currentStatus ?? '') === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                <option value="tidak aktif" <?= ($currentStatus ?? '') === 'tidak aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                <option value="menunggu verifikasi" <?= ($currentStatus ?? '') === 'menunggu verifikasi' ? 'selected' : '' ?>>Menunggu Verifikasi</option>
            </select>
        </form>

        <a href="<?= base_url('wpa/dashboard/layanan/create') ?>" class="w-full lg:w-auto px-6 py-2.5 bg-accent text-black font-bold rounded-lg hover:bg-white transition flex items-center justify-center gap-2">
            <i class="fas fa-plus"></i>
            Buat Layanan Baru
        </a>
    </div>

    <!-- Content Table/Grid -->
    <div class="bg-[#111] border border-white/10 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-black/50 border-b border-white/10">
                        <th class="px-6 py-4 text-sm font-bold text-gray-400">Layanan</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-400">Kategori</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-400">Spesialis</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-400">Harga Rupiah</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-400">Harga Poin</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-400">Status</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-400 text-right">Aksi</th>
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
                            <tr class="hover:bg-white/5 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-lg overflow-hidden border border-white/10 flex-shrink-0">
                                            <img src="<?= esc($item['thumbnail'] ?? base_url('favicon.ico')) ?>" class="w-full h-full object-cover" onerror="this.src='<?= base_url('favicon.ico') ?>'">
                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="font-bold text-sm truncate"><?= esc($item['name'] ?? $item['title'] ?? 'Untitled') ?></h4>
                                            <p class="text-xs text-gray-500 truncate"><?= esc($item['slug'] ?? '-') ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium"><?= esc($item['category'] ?? $item['kategori'] ?? '-') ?></span>
                                        <span class="text-[10px] text-gray-500 uppercase tracking-wider"><?= esc($item['subcategory'] ?? $item['type'] ?? '-') ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if (!empty($item['specialist'])): ?>
                                        <span class="px-2.5 py-1 text-[10px] font-bold rounded-lg border border-white/10 bg-white/5 text-gray-300 uppercase inline-block">
                                            <?= esc($item['specialist']) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-gray-600 text-xs">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <?php if ($item['type'] === 'artikel' || $item['subcategory'] === 'Artikel'): ?>
                                            <span class="text-sm font-medium text-gray-500">-</span>
                                        <?php else: ?>
                                            <span class="text-sm font-bold border-b border-white/5 pb-1">Rp <?= number_format($item['price'], 0, ',', '.') ?></span>
                                            <?php if ($item['has_packages'] ?? false): ?>
                                                <span class="text-[10px] text-gray-500 mt-1">Mulai dari</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-1.5">
                                        <i class="fas fa-coins text-yellow-500 text-xs"></i>
                                        <span class="text-sm font-bold text-yellow-500"><?= number_format($item['poin_price'] ?? 0, 0, ',', '.') ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                    $status = $item['status'] ?? 'tidak aktif';
                                    $statusClass = 'bg-gray-500/20 text-gray-400 border-gray-500/20';
                                    $statusLabel = ucfirst($status);

                                    if ($status === 'aktif') {
                                        $statusClass = 'bg-green-500/20 text-green-400 border-green-500/20';
                                        $statusLabel = 'Aktif';
                                    } elseif ($status === 'tidak aktif') {
                                        $statusClass = 'bg-red-500/20 text-red-400 border-red-500/20';
                                        $statusLabel = 'Tidak Aktif';
                                    } elseif ($status === 'menunggu verifikasi') {
                                        $statusClass = 'bg-yellow-500/20 text-yellow-500 border-yellow-500/20';
                                        $statusLabel = 'Menunggu Verifikasi';
                                    }
                                    ?>
                                    <span class="px-2 py-1 <?= $statusClass ?> text-[10px] font-bold rounded uppercase tracking-wider border">
                                        <?= $statusLabel ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="<?= base_url('layanan/' . ($item['slug'] ?? $item['id'])) ?>" target="_blank" class="p-2 bg-white/5 border border-white/10 rounded hover:bg-accent hover:text-black transition" title="Lihat">
                                            <i class="fas fa-external-link-alt text-xs"></i>
                                        </a>
                                        <a href="<?= base_url('wpa/dashboard/layanan/edit/' . ($item['type'] ?? 'layanan') . '/' . $item['id']) ?>" class="p-2 bg-white/5 border border-white/10 rounded hover:bg-blue-500 hover:text-white transition" title="Edit">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        <button onclick="confirmDelete('<?= $item['type'] ?? 'layanan' ?>', <?= $item['id'] ?>, '<?= esc($item['name'] ?? $item['title'] ?? 'item ini') ?>')" class="p-2 bg-white/5 border border-white/10 rounded hover:bg-red-500 hover:text-white transition" title="Hapus">
                                            <i class="fas fa-trash text-xs"></i>
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
        document.getElementById('deleteForm').action = `<?= base_url('wpa/dashboard/layanan/delete') ?>/${type}/${id}`;

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