<?php
$this->setVar('pageTitle', 'Data Transaksi');
$this->setVar('pageSubtitle', 'Daftar transaksi platform (Read Only)');
?>
<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Filters & Actions -->
<div class="bg-[#111] border border-white/10 rounded-2xl p-4 mb-6 shadow-xl">
    <form action="<?= base_url('laporan-kegiatan/transaksi') ?>" method="get" class="space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Status Filter -->
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] uppercase tracking-widest font-black text-gray-500 ml-1">Status</label>
                <select name="status" onchange="this.form.submit()" class="w-full px-4 py-2.5 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm cursor-pointer appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:20px_20px] bg-right-4 bg-no-repeat">
                    <option value="all" <?= $currentStatus === 'all' || !$currentStatus ? 'selected' : '' ?>>Semua Status</option>
                    <option value="pending" <?= $currentStatus === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="paid" <?= $currentStatus === 'paid' ? 'selected' : '' ?>>Paid</option>
                    <option value="confirmed" <?= $currentStatus === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                    <option value="cancelled" <?= $currentStatus === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                </select>
            </div>

            <!-- Product Filter -->
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] uppercase tracking-widest font-black text-gray-500 ml-1">Layanan</label>
                <select name="product" onchange="this.form.submit()" class="w-full px-4 py-2.5 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm cursor-pointer appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:20px_20px] bg-right-4 bg-no-repeat">
                    <option value="all">Semua Layanan</option>
                    <?php foreach($productList as $p): ?>
                        <option value="<?= esc($p['product_name']) ?>" <?= $currentProduct === $p['product_name'] ? 'selected' : '' ?>><?= esc($p['product_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Start Date -->
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] uppercase tracking-widest font-black text-gray-500 ml-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="<?= esc($startDate) ?>" class="w-full px-4 py-2.5 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white [color-scheme:dark]">
            </div>

            <!-- End Date -->
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] uppercase tracking-widest font-black text-gray-500 ml-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="<?= esc($endDate) ?>" class="w-full px-4 py-2.5 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-white [color-scheme:dark]">
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 items-end">
            <div class="flex-1 w-full relative">
                <input type="text" name="search" value="<?= esc($search) ?>" placeholder="Cari invoice, produk, atau nama user..." class="w-full px-4 py-2.5 pl-10 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm transition font-medium">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-xs"></i>
            </div>
            <div class="flex gap-2 w-full sm:w-auto">
                <button type="submit" class="flex-1 sm:px-8 py-2.5 bg-accent text-black font-black rounded-xl hover:bg-white transition flex items-center justify-center gap-2 shadow-lg shadow-accent/10 whitespace-nowrap uppercase tracking-widest text-xs">
                    <i class="fas fa-filter text-[10px]"></i> Terapkan Filter
                </button>
                <a href="<?= base_url('laporan-kegiatan/transaksi') ?>" class="px-4 py-2.5 bg-white/5 text-gray-400 border border-white/10 rounded-xl hover:bg-white/10 hover:text-white transition flex items-center justify-center">
                    <i class="fas fa-redo-alt text-xs"></i>
                </a>
                <a href="<?= base_url('laporan-kegiatan/transaksi/export-csv') ?>?status=<?= $currentStatus ?>&search=<?= $search ?>&start_date=<?= $startDate ?>&end_date=<?= $endDate ?>&product=<?= $currentProduct ?>" 
                   class="px-4 py-2.5 bg-green-500/10 text-green-400 border border-green-500/20 font-bold rounded-xl hover:bg-green-500 hover:text-white transition flex items-center justify-center gap-2" title="Export CSV (Excel Compatible)">
                    <i class="fas fa-file-csv"></i>
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Transactions Table -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto scrollbar-hide">
        <table class="w-full min-w-[850px]">
            <thead class="bg-black/80">
                <tr>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 w-12 text-center uppercase tracking-widest">No</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Invoice / Tanggal</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">User / Layanan</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest text-right">Total</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest text-center">Status</th>
                    <th class="text-center px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($transaksiList)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center text-gray-500">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center">
                                    <i class="fas fa-receipt text-2xl"></i>
                                </div>
                                <p class="text-sm font-medium">Tidak ada transaksi ditemukan</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($transaksiList as $index => $trx): ?>
                        <tr class="hover:bg-white/5 transition group">
                            <td class="px-4 py-4 text-gray-500 text-sm text-center font-mono"><?= $index + 1 ?></td>
                            <td class="px-4 py-4">
                                <div class="min-w-0">
                                    <p class="font-bold text-sm text-accent group-hover:text-white transition font-mono"><?= esc($trx['invoice_number']) ?></p>
                                    <p class="text-[10px] text-gray-500 mt-0.5"><?= date('d M Y, H:i', strtotime($trx['created_at'])) ?></p>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="min-w-0">
                                    <p class="font-bold text-sm text-gray-200 truncate"><?= esc($trx['user_name'] ?? 'Guest') ?></p>
                                    <p class="text-[10px] text-gray-400 mt-0.5 truncate"><?= esc($trx['layanan_name'] ?? $trx['product_name']) ?></p>
                                </div>
                            </td>
                            <td class="px-4 py-4 font-black text-sm text-right">
                                <?php if (($trx['payment_method'] ?? '') === 'poin'): ?>
                                    <span class="text-yellow-400"><?= number_format($trx['total'], 0, ',', '.') ?> <i class="fas fa-coins ml-1"></i></span>
                                <?php else: ?>
                                    <span class="text-accent">Rp <?= number_format($trx['total'], 0, ',', '.') ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <?php
                                $statusColors = [
                                    'pending' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                                    'paid' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                                    'confirmed' => 'bg-accent/20 text-accent border-accent/30',
                                    'cancelled' => 'bg-red-500/20 text-red-400 border-red-500/30',
                                    'refunded' => 'bg-gray-500/20 text-gray-400 border-gray-500/30',
                                ];
                                $colorClass = $statusColors[$trx['status']] ?? 'bg-gray-500/20 text-gray-400 border-gray-500/30';
                                ?>
                                <span class="px-2.5 py-1 text-[10px] font-black rounded-lg uppercase tracking-wider border <?= $colorClass ?>">
                                    <?= ucfirst($trx['status']) ?>
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

    <!-- Pagination -->
    <?php if ($pager->getPageCount() > 1): ?>
        <div class="p-4 border-t border-white/5 bg-black/20">
            <?= $pager->links('transaksi', 'admin_pagination') ?>
        </div>
    <?php endif; ?>
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
