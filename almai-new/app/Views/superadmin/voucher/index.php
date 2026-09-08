<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>
<?php 
$pageTitle = 'Kelola Voucher'; 
$pageSubtitle = 'Buat dan kelola voucher diskon';
$currentPage = $pager->getCurrentPage();
$perPage = 20;
?>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-ticket-alt text-purple-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= $totalVouchers ?></p>
                <p class="text-xs text-gray-500">Total Voucher</p>
            </div>
        </div>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-accent/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-accent"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= $activeVouchers ?></p>
                <p class="text-xs text-gray-500">Aktif</p>
            </div>
        </div>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-blue-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= $totalUsage ?></p>
                <p class="text-xs text-gray-500">Total Penggunaan</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters & Actions -->
<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
    <div class="flex gap-2 w-full lg:w-auto overflow-x-auto pb-2 lg:pb-0">
        <a href="?status=all" class="flex-1 lg:flex-none px-4 py-2 rounded-xl text-sm text-center whitespace-nowrap <?= !$currentStatus || $currentStatus === 'all' ? 'bg-accent text-black font-bold' : 'bg-white/10 hover:bg-white/20' ?>">Semua</a>
        <a href="?status=active" class="flex-1 lg:flex-none px-4 py-2 rounded-xl text-sm text-center whitespace-nowrap <?= $currentStatus === 'active' ? 'bg-accent text-black font-bold' : 'bg-white/10 hover:bg-white/20' ?>">Aktif</a>
        <a href="?status=inactive" class="flex-1 lg:flex-none px-4 py-2 rounded-xl text-sm text-center whitespace-nowrap <?= $currentStatus === 'inactive' ? 'bg-accent text-black font-bold' : 'bg-white/10 hover:bg-white/20' ?>">Nonaktif</a>
    </div>
    <a href="<?= base_url('superadmin/voucher/create') ?>" class="w-full lg:w-auto px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-center whitespace-nowrap">
        <i class="fas fa-plus mr-2"></i> Buat Voucher
    </a>
</div>

<!-- Voucher List -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-black/50">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400 w-12">No</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Kode</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Nama</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Diskon</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Produk</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Penggunaan</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Berlaku</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Status</th>
                    <th class="text-center px-4 py-3 text-xs font-medium text-gray-400">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($vouchers)): ?>
                <tr>
                    <td colspan="9" class="px-4 py-12 text-center text-gray-500">
                        <i class="fas fa-ticket-alt text-4xl mb-4"></i>
                        <p>Belum ada voucher</p>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($vouchers as $index => $voucher): ?>
                <tr class="border-t border-white/5 hover:bg-white/5">
                    <td class="px-4 py-3 text-gray-400 text-sm"><?= ($currentPage - 1) * $perPage + $index + 1 ?></td>
                    <td class="px-4 py-3">
                        <span class="font-mono text-accent font-bold"><?= esc($voucher['code']) ?></span>
                    </td>
                    <td class="px-4 py-3">
                        <p class="font-medium text-sm"><?= esc($voucher['name']) ?></p>
                        <?php if ($voucher['description']): ?>
                        <p class="text-xs text-gray-500 line-clamp-1"><?= esc($voucher['description']) ?></p>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <?php if ($voucher['discount_type'] === 'percentage'): ?>
                        <span class="text-yellow-500 font-bold"><?= $voucher['discount_value'] ?>%</span>
                        <?php if ($voucher['max_discount']): ?>
                        <p class="text-xs text-gray-500">Max: Rp <?= number_format($voucher['max_discount'], 0, ',', '.') ?></p>
                        <?php endif; ?>
                        <?php else: ?>
                        <span class="text-yellow-500 font-bold">Rp <?= number_format($voucher['discount_value'], 0, ',', '.') ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <?php
                        $typeColors = [
                            'all' => 'bg-purple-500/20 text-purple-400',
                            'layanan' => 'bg-accent/20 text-accent',
                            'kelas' => 'bg-blue-500/20 text-blue-400',
                            'tools' => 'bg-yellow-500/20 text-yellow-400',
                            'artikel' => 'bg-pink-500/20 text-pink-400',
                        ];
                        $typeColor = $typeColors[$voucher['product_type']] ?? 'bg-gray-500/20 text-gray-400';
                        $productIds = json_decode($voucher['product_ids'] ?? '[]', true);
                        $productCount = is_array($productIds) ? count($productIds) : 0;
                        ?>
                        <div class="flex flex-col gap-1">
                            <span class="px-2 py-1 rounded-full text-[10px] w-fit font-bold uppercase tracking-wider <?= $typeColor ?>">
                                <?= (!$voucher['product_type'] || $voucher['product_type'] === 'all') ? 'Semua Produk' : ucfirst($voucher['product_type']) ?>
                            </span>
                            <?php if ($voucher['product_type'] !== 'all' && $productCount > 0): ?>
                            <span class="text-[10px] text-gray-500 ml-1">
                                <?= $productCount ?> Item Terpilih
                            </span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-sm"><?= $voucher['used_count'] ?><?= $voucher['usage_limit'] ? '/' . $voucher['usage_limit'] : '' ?></span>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-400">
                        <?php if ($voucher['start_date'] || $voucher['end_date']): ?>
                            <?= $voucher['start_date'] ? date('d/m/y', strtotime($voucher['start_date'])) : '-' ?>
                            <br>s/d <?= $voucher['end_date'] ? date('d/m/y', strtotime($voucher['end_date'])) : '-' ?>
                        <?php else: ?>
                            <span class="text-accent">Selamanya</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <?php if ($voucher['status'] === 'active'): ?>
                        <span class="px-2 py-1 bg-accent/20 text-accent rounded-full text-xs">Aktif</span>
                        <?php else: ?>
                        <span class="px-2 py-1 bg-gray-500/20 text-gray-400 rounded-full text-xs">Nonaktif</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1">
                            <a href="<?= base_url('superadmin/voucher/usages/' . $voucher['id']) ?>" class="p-2 bg-blue-500/20 text-blue-400 rounded-lg hover:bg-blue-500 hover:text-white transition" title="Riwayat">
                                <i class="fas fa-history text-xs"></i>
                            </a>
                            <a href="<?= base_url('superadmin/voucher/edit/' . $voucher['id']) ?>" class="p-2 bg-accent/20 text-accent rounded-lg hover:bg-accent hover:text-black transition" title="Edit">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                            <form action="<?= base_url('superadmin/voucher/toggle-status/' . $voucher['id']) ?>" method="post" class="inline">
                                <?= csrf_field() ?>
                                <button type="submit" class="p-2 bg-yellow-500/20 text-yellow-400 rounded-lg hover:bg-yellow-500 hover:text-black transition" title="Toggle Status">
                                    <i class="fas fa-power-off text-xs"></i>
                                </button>
                            </form>
                            <form action="<?= base_url('superadmin/voucher/delete/' . $voucher['id']) ?>" method="post" class="inline" onsubmit="return confirm('Hapus voucher ini?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="p-2 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500 hover:text-white transition" title="Hapus">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if ($pager->getPageCount() > 1): ?>
    <div class="px-6 py-4 border-t border-white/10">
        <?= $pager->links('default', 'admin_pagination') ?>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
