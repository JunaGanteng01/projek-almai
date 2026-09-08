<?= $this->extend('wpa/layouts/main') ?>

<?= $this->section('content') ?>
<?php $pageTitle = 'Transaksi';
$pageSubtitle = 'Riwayat transaksi kelas Anda'; ?>

<!-- Search and Filter -->
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div class="flex flex-col md:flex-row gap-4 flex-1">
        <!-- Search -->
        <div class="w-full md:w-80">
            <form action="<?= base_url('wpa/dashboard/transaksi') ?>" method="get" class="relative">
                <input type="text" name="search" value="<?= esc($searchQuery ?? '') ?>"
                    placeholder="Cari invoice, user, atau produk..."
                    class="w-full bg-[#111] border border-white/20 rounded-xl px-4 py-2 pl-10 text-sm focus:border-accent focus:outline-none h-10">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                <?php if ($currentStatus): ?>
                    <input type="hidden" name="status" value="<?= esc($currentStatus) ?>">
                <?php endif; ?>
            </form>
        </div>

        <!-- Filter Status -->
        <div class="w-full md:w-48">
            <form action="<?= base_url('wpa/dashboard/transaksi') ?>" method="get">
                <?php if ($searchQuery): ?>
                    <input type="hidden" name="search" value="<?= esc($searchQuery) ?>">
                <?php endif; ?>
                <select name="status" onchange="this.form.submit()"
                    class="w-full bg-[#111] border border-white/20 rounded-xl px-4 py-2 text-sm focus:border-accent focus:outline-none h-10 appearance-none pointer-events-auto">
                    <option value="all">Semua Status</option>
                    <option value="confirmed" <?= $currentStatus === 'confirmed' ? 'selected' : '' ?>>Sukses</option>
                    <option value="pending" <?= $currentStatus === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="canceled" <?= $currentStatus === 'canceled' ? 'selected' : '' ?>>Canceled</option>
                    <option value="expired" <?= $currentStatus === 'expired' ? 'selected' : '' ?>>Expired</option>
                </select>
            </form>
        </div>
    </div>
</div>

<div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl overflow-hidden">
    <div class="table-responsive">
        <table class="w-full min-w-[900px]">
            <thead class="bg-black/50">
                <tr>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400">Invoice</th>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400">User</th>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400">Produk</th>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400">Total</th>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400">Status</th>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400">Tanggal</th>
                    <th class="text-center px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($transactions)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-receipt text-4xl mb-4"></i>
                                <p>Belum ada transaksi</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($transactions as $trx): ?>
                        <tr class="border-t border-white/10 hover:bg-white/5 transition">
                            <td class="px-4 md:px-6 py-3 md:py-4">
                                <span class="font-mono text-sm text-accent"><?= esc($trx['invoice_number']) ?></span>
                            </td>
                            <td class="px-4 md:px-6 py-3 md:py-4">
                                <div>
                                    <p class="font-medium text-sm"><?= esc($trx['user_name'] ?? '-') ?></p>
                                    <p class="text-xs text-gray-500"><?= esc($trx['user_email'] ?? '') ?></p>
                                </div>
                            </td>
                            <td class="px-4 md:px-6 py-3 md:py-4">
                                <p class="text-sm"><?= esc($trx['product_name']) ?></p>
                            </td>
                            <td class="px-4 md:px-6 py-3 md:py-4">
                                <span class="text-accent font-bold">Rp <?= number_format($trx['total'], 0, ',', '.') ?></span>
                            </td>
                            <td class="px-4 md:px-6 py-3 md:py-4">
                                <?php if ($trx['status'] === 'confirmed'): ?>
                                    <span class="px-2 py-1 bg-accent/20 text-accent rounded-full text-xs">Sukses</span>
                                <?php elseif ($trx['status'] === 'pending'): ?>
                                    <span class="px-2 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-xs">Pending</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 bg-red-500/20 text-red-400 rounded-full text-xs"><?= ucfirst($trx['status']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 md:px-6 py-3 md:py-4 text-gray-400 text-sm">
                                <?= date('d M Y, H:i', strtotime($trx['created_at'])) ?>
                            </td>
                            <td class="px-4 md:px-6 py-3 md:py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?= base_url('wpa/dashboard/transaksi/invoice/' . $trx['invoice_number']) ?>" target="_blank"
                                        class="w-8 h-8 flex items-center justify-center bg-white/5 hover:bg-white/10 rounded-lg text-gray-400 hover:text-white transition" title="Invoice">
                                        <i class="fas fa-file-invoice text-sm"></i>
                                    </a>
                                    <a href="<?= base_url('wpa/dashboard/transaksi/download-perjanjian/' . $trx['invoice_number']) ?>"
                                        class="w-8 h-8 flex items-center justify-center bg-white/5 hover:bg-white/10 rounded-lg text-gray-400 hover:text-white transition" title="Perjanjian Pemberi Nasihat">
                                        <i class="fas fa-file-contract text-sm"></i>
                                    </a>
                                    <a href="<?= base_url('wpa/dashboard/transaksi/download-risiko/' . $trx['invoice_number']) ?>"
                                        class="w-8 h-8 flex items-center justify-center bg-white/5 hover:bg-white/10 rounded-lg text-gray-400 hover:text-white transition" title="Risiko">
                                        <i class="fas fa-exclamation-triangle text-sm"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($pager && $pager->getPageCount() > 1): ?>
        <div class="px-6 py-4 border-t border-white/10 flex justify-center gap-2">
            <?= $pager->links('default', 'admin_pagination') ?>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>