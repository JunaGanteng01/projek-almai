<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Stats -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-accent/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-tools text-accent"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= $totalTools ?></p>
                <p class="text-xs text-gray-400">Total Tools</p>
            </div>
        </div>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-blue-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-blue-500"><?= $activeTools ?></p>
                <p class="text-xs text-gray-400">Aktif</p>
            </div>
        </div>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-shopping-cart text-yellow-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-yellow-500"><?= number_format($totalSales, 0, ',', '.') ?></p>
                <p class="text-xs text-gray-400">Total Terjual</p>
            </div>
        </div>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-money-bill-wave text-green-500"></i>
            </div>
            <div>
                <p class="text-xl font-bold text-green-500">Rp <?= number_format($totalRevenue, 0, ',', '.') ?></p>
                <p class="text-xs text-gray-400">Total Pendapatan</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="flex flex-col md:flex-row gap-4 mb-6 items-start md:items-center justify-between">
    <div class="flex gap-2 w-full md:w-auto overflow-x-auto pb-2 md:pb-0">
        <a href="?filter=all" class="flex-1 md:flex-none px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap text-center <?= $currentFilter === 'all' ? 'bg-accent text-black' : 'bg-white/10 hover:bg-white/20' ?>">Semua</a>
        <a href="?filter=active" class="flex-1 md:flex-none px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap text-center <?= $currentFilter === 'active' ? 'bg-accent text-black' : 'bg-white/10 hover:bg-white/20' ?>">Aktif</a>
        <a href="?filter=inactive" class="flex-1 md:flex-none px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap text-center <?= $currentFilter === 'inactive' ? 'bg-accent text-black' : 'bg-white/10 hover:bg-white/20' ?>">Nonaktif</a>
    </div>
    <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
        <select onchange="window.location.href='?filter=<?= $currentFilter ?>&category='+this.value" class="w-full sm:w-auto bg-[#111] border border-white/20 rounded-xl px-4 py-2 text-sm focus:border-accent focus:outline-none">
            <option value="">Semua Kategori</option>
            <?php foreach ($categories as $cat): ?>
            <option value="<?= esc($cat) ?>" <?= $currentCategory === $cat ? 'selected' : '' ?>><?= esc($cat) ?></option>
            <?php endforeach; ?>
        </select>
        <a href="<?= base_url('admin/tools/create') ?>" class="w-full sm:w-auto px-4 py-2 bg-accent text-black rounded-xl font-bold text-sm hover:bg-white transition text-center whitespace-nowrap">
            <i class="fas fa-plus mr-2"></i> Tambah Tools
        </a>
    </div>
</div>

<!-- Tools List -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
    <?php if (empty($toolsList)): ?>
    <div class="p-8 text-center text-gray-500">
        <i class="fas fa-tools text-4xl mb-4"></i>
        <p>Tidak ada tools</p>
    </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-black/50">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400 w-12">No</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Tools</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Kategori</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Harga</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Terjual</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Status</th>
                    <th class="text-center px-4 py-3 text-xs font-medium text-gray-400">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = ($pager->getCurrentPage() - 1) * 20 + 1; ?>
                <?php foreach ($toolsList as $tool): ?>
                <tr class="border-t border-white/5 hover:bg-white/5">
                    <td class="px-4 py-3 text-sm text-gray-400"><?= $no++ ?></td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <img src="<?= esc($tool['thumbnail']) ?>" alt="" class="w-16 h-12 object-cover rounded-lg">
                            <div>
                                <p class="font-medium text-sm line-clamp-1"><?= esc($tool['name']) ?></p>
                                <p class="text-xs text-gray-500"><?= esc($tool['platform']) ?> • ⭐ <?= esc($tool['rating']) ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 bg-blue-500/20 text-blue-400 rounded-full text-xs"><?= esc($tool['category']) ?></span>
                    </td>
                    <td class="px-4 py-3">
                        <div>
                            <?php if ($tool['original_price'] > $tool['price']): ?>
                            <span class="text-gray-500 line-through text-xs">Rp <?= number_format($tool['original_price'], 0, ',', '.') ?></span><br>
                            <?php endif; ?>
                            <span class="text-accent font-bold">Rp <?= number_format($tool['price'], 0, ',', '.') ?></span>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-yellow-500 font-bold"><?= number_format($tool['real_sales'] ?? $tool['sales'], 0, ',', '.') ?></span>
                    </td>
                    <td class="px-4 py-3">
                        <?php if ($tool['status'] === 'active'): ?>
                        <span class="px-2 py-1 bg-accent/20 text-accent rounded-full text-xs">Aktif</span>
                        <?php else: ?>
                        <span class="px-2 py-1 bg-gray-500/20 text-gray-400 rounded-full text-xs">Nonaktif</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-2">
                            <a href="<?= base_url('tools/' . $tool['id']) ?>" target="_blank" class="p-2 bg-accent/20 text-accent rounded-lg hover:bg-accent hover:text-black transition" title="Lihat">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                            <a href="<?= base_url('admin/tools/edit/' . $tool['id']) ?>" class="p-2 bg-blue-500/20 text-blue-400 rounded-lg hover:bg-blue-500 hover:text-white transition" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?= base_url('admin/tools/toggle-status/' . $tool['id']) ?>" method="post" class="inline">
                                <?= csrf_field() ?>
                                <button type="submit" class="p-2 bg-yellow-500/20 text-yellow-400 rounded-lg hover:bg-yellow-500 hover:text-black transition" title="Toggle Status">
                                    <i class="fas fa-power-off"></i>
                                </button>
                            </form>
                            <form action="<?= base_url('admin/tools/delete/' . $tool['id']) ?>" method="post" class="inline" onsubmit="return confirm('Hapus tools ini?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="p-2 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500 hover:text-white transition" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if ($pager->getPageCount() > 1): ?>
    <div class="p-4 border-t border-white/10 flex items-center justify-between">
        <p class="text-sm text-gray-500">Menampilkan <?= count($toolsList) ?> dari <?= $pager->getTotal() ?> data</p>
        <div class="flex gap-1">
            <?= $pager->links('default', 'admin_pagination') ?>
        </div>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
