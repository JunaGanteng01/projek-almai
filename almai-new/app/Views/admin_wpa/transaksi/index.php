<?= $this->extend('admin_wpa/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-black text-white uppercase tracking-tight">Transaksi WPA</h1>
        <p class="text-gray-500 text-sm">Kelola transaksi dari WPA yang Anda kelola</p>
    </div>
    <div class="flex items-center gap-3">
        <a href="<?= base_url('admin-wpa/transaksi/create-manual') ?>" class="px-6 py-2.5 bg-accent text-black font-black rounded-xl hover:bg-white transition flex items-center gap-2 shadow-lg shadow-accent/20">
            <i class="fas fa-plus-circle"></i> Tambah Transaksi
        </a>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <?php foreach ($stats as $s): ?>
    <div class="bg-[#111] border border-white/10 rounded-2xl p-4">
        <p class="text-[10px] text-gray-500 uppercase font-black mb-1 truncate"><?= esc($s['wpa_name']) ?></p>
        <div class="flex items-end justify-between">
            <div>
                <p class="text-lg font-bold text-white">Rp <?= number_format($s['revenue'], 0, ',', '.') ?></p>
                <p class="text-[10px] text-accent font-bold"><?= $s['sales'] ?> Sales</p>
            </div>
            <div class="w-8 h-8 rounded-lg bg-accent/10 flex items-center justify-center">
                <i class="fas fa-chart-line text-accent text-xs"></i>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Filters -->
<div class="bg-[#111] border border-white/10 rounded-2xl p-4 mb-6">
    <form action="<?= base_url('admin-wpa/transaksi') ?>" method="get" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <label class="block text-[10px] text-gray-500 uppercase font-bold mb-1 ml-1">Filter WPA</label>
            <select name="wpa_id" class="w-full bg-black border border-white/10 rounded-xl px-4 py-2 text-sm focus:border-accent focus:outline-none">
                <option value="">Semua WPA</option>
                <?php foreach ($assignedWPAs as $wpa): ?>
                    <option value="<?= $wpa['id'] ?>" <?= $currentWpa == $wpa['id'] ? 'selected' : '' ?>>
                        <?= esc($wpa['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="flex-1">
            <label class="block text-[10px] text-gray-500 uppercase font-bold mb-1 ml-1">Filter Status</label>
            <select name="status" class="w-full bg-black border border-white/10 rounded-xl px-4 py-2 text-sm focus:border-accent focus:outline-none">
                <option value="">Semua Status</option>
                <option value="pending" <?= $currentStatus == 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="paid" <?= $currentStatus == 'paid' ? 'selected' : '' ?>>Paid</option>
                <option value="confirmed" <?= $currentStatus == 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                <option value="failed" <?= $currentStatus == 'failed' ? 'selected' : '' ?>>Failed</option>
                <option value="expired" <?= $currentStatus == 'expired' ? 'selected' : '' ?>>Expired</option>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="px-6 py-2 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex-1 md:flex-none">
                <i class="fas fa-filter mr-2"></i> Filter
            </button>
            <a href="<?= base_url('admin-wpa/transaksi/export?' . http_build_query($_GET)) ?>" class="px-6 py-2 bg-green-600 text-white font-bold rounded-xl hover:bg-green-500 transition flex-1 md:flex-none text-center">
                <i class="fas fa-file-excel mr-2"></i> Export Excel
            </a>
        </div>
    </form>
</div>
<!-- Transactions Table -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-black/50">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Invoice</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">User</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Produk</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Total</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Status</th>
                    <th class="text-center px-4 py-3 text-xs font-medium text-gray-400">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($transactions)): ?>
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-receipt text-4xl mb-4"></i>
                            <p>Tidak ada transaksi ditemukan</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($transactions as $trx): ?>
                        <tr class="border-t border-white/5 hover:bg-white/5">
                            <td class="px-4 py-3">
                                <p class="text-sm font-mono text-gray-300"><?= esc($trx['invoice_number'] ?? 'INV-000') ?></p>
                                <p class="text-[10px] text-gray-500"><?= date('d M Y H:i', strtotime($trx['created_at'])) ?></p>
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-sm font-medium"><?= esc($trx['user_name'] ?? 'Guest') ?></p>
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-sm"><?= esc($trx['product_name']) ?></p>
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-sm font-bold text-accent">Rp <?= number_format($trx['total'], 0, ',', '.') ?></p>
                            </td>
                            <td class="px-4 py-3">
                                <?php 
                                $statusClass = 'bg-gray-500/20 text-gray-400';
                                if ($trx['status'] === 'confirmed') $statusClass = 'bg-accent/20 text-accent';
                                if ($trx['status'] === 'pending') $statusClass = 'bg-yellow-500/20 text-yellow-400';
                                if ($trx['status'] === 'expired' || $trx['status'] === 'failed') $statusClass = 'bg-red-500/20 text-red-400';
                                ?>
                                <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase <?= $statusClass ?>">
                                    <?= esc($trx['status']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center">
                                    <a href="<?= base_url('admin-wpa/transaksi/view/' . $trx['id']) ?>" class="w-8 h-8 flex items-center justify-center bg-accent/20 text-accent rounded-lg hover:bg-accent hover:text-black transition">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($pager): ?>
        <div class="px-4 py-4 border-t border-white/5 flex justify-center">
            <?= $pager->links('default', 'admin_pagination') ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
