<?= $this->extend('cwpa/layouts/main') ?>

<?= $this->section('content') ?>
<?php $pageTitle = 'Transaksi';
$pageSubtitle = 'Riwayat transaksi penjualan dan pembelian'; ?>

<!-- Tab Navigation -->
<div class="mb-6 flex border-b border-white/10">
    <a href="<?= base_url('cwpa/dashboard/transaksi?tab=penjualan') ?>" 
       class="px-6 py-3 text-sm font-bold border-b-2 transition <?= ($tab === 'penjualan') ? 'border-accent text-accent' : 'border-transparent text-gray-500 hover:text-gray-300' ?>">
        <i class="fas fa-store mr-2"></i>Penjualan Layanan
    </a>
    <a href="<?= base_url('cwpa/dashboard/transaksi?tab=pembelian') ?>" 
       class="px-6 py-3 text-sm font-bold border-b-2 transition <?= ($tab === 'pembelian') ? 'border-accent text-accent' : 'border-transparent text-gray-500 hover:text-gray-300' ?>">
        <i class="fas fa-shopping-cart mr-2"></i>Pembelian Saya
    </a>
</div>

<!-- Search and Filter -->
<div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <form action="<?= base_url('cwpa/dashboard/transaksi') ?>" method="get" class="flex flex-col sm:flex-row gap-3 w-full max-w-3xl">
        <input type="hidden" name="tab" value="<?= esc($tab) ?>">
        
        <!-- Search -->
        <div class="relative flex-grow">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
            <input type="text" name="search" value="<?= esc($searchQuery ?? '') ?>"
                placeholder="Cari invoice, nama, atau produk..."
                class="w-full bg-[#111] border border-white/10 rounded-xl px-4 py-3 pl-12 text-xs text-white focus:border-accent outline-none transition shadow-lg">
        </div>

        <!-- Filter Status -->
        <div class="sm:w-48 relative">
            <select name="status" onchange="this.form.submit()"
                class="w-full bg-[#111] border border-white/10 rounded-xl px-4 py-3 text-[10px] uppercase font-black tracking-widest text-gray-400 focus:border-accent outline-none transition appearance-none cursor-pointer shadow-lg text-center">
                <option value="">Semua Status</option>
                <option value="confirmed" <?= $currentStatus === 'confirmed' ? 'selected' : '' ?>>Sukses</option>
                <option value="pending" <?= $currentStatus === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="canceled" <?= $currentStatus === 'canceled' ? 'selected' : '' ?>>Canceled</option>
                <option value="expired" <?= $currentStatus === 'expired' ? 'selected' : '' ?>>Expired</option>
            </select>
            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-600 text-[10px] pointer-events-none"></i>
        </div>
        
        <?php if ($searchQuery || $currentStatus): ?>
            <a href="<?= base_url('cwpa/dashboard/transaksi?tab=' . $tab) ?>" class="px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-center text-[10px] uppercase font-black text-gray-500 hover:text-white transition whitespace-nowrap">
                Reset
            </a>
        <?php endif; ?>
    </form>
</div>

<?php 
// Determine which transactions to display based on tab
$transactions = ($tab === 'penjualan') ? $penjualanTransactions : $pembelianTransactions;
$pager = ($tab === 'penjualan') ? $penjualanPager : $pembelianPager;
$showUserColumn = ($tab === 'penjualan'); // Only show user column in penjualan tab
?>

<!-- Mobile Cards View -->
<div class="block md:hidden space-y-4">
    <?php if (empty($transactions)): ?>
        <div class="bg-[#111] border border-white/10 rounded-2xl p-12 text-center text-gray-500">
            <i class="fas fa-receipt text-4xl mb-4"></i>
            <p class="font-bold uppercase tracking-widest text-xs">Belum ada transaksi</p>
        </div>
    <?php else: ?>
        <?php foreach ($transactions as $trx): ?>
            <div class="bg-[#111] border border-white/10 rounded-2xl p-5 relative overflow-hidden group">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <p class="text-[9px] text-accent font-black uppercase tracking-widest mb-1"><?= esc($trx['invoice_number']) ?></p>
                        <h4 class="font-black text-white text-base leading-tight"><?= esc($trx['product_name']) ?></h4>
                    </div>
                    <?php if ($trx['status'] === 'confirmed'): ?>
                        <span class="px-2 py-0.5 bg-accent/10 text-accent rounded-lg text-[8px] font-black uppercase tracking-widest border border-accent/20">Success</span>
                    <?php elseif ($trx['status'] === 'pending'): ?>
                        <span class="px-2 py-0.5 bg-yellow-500/10 text-yellow-500 rounded-lg text-[8px] font-black uppercase tracking-widest border border-yellow-500/10">Pending</span>
                    <?php else: ?>
                        <span class="px-2 py-0.5 bg-red-500/10 text-red-400 rounded-lg text-[8px] font-black uppercase tracking-widest border border-red-500/10"><?= strtoupper(esc($trx['status'])) ?></span>
                    <?php endif; ?>
                </div>

                <?php if ($showUserColumn): ?>
                <div class="flex items-center gap-3 mb-4 p-3 bg-black/30 rounded-xl border border-white/5">
                    <div class="w-10 h-10 rounded-lg bg-white/5 flex items-center justify-center border border-white/5 text-gray-400">
                        <i class="fas fa-user text-sm"></i>
                    </div>
                    <div>
                        <p class="text-white text-xs font-black"><?= esc($trx['user_name'] ?? '-') ?></p>
                        <p class="text-[9px] text-gray-500 font-bold"><?= esc($trx['user_email'] ?? '') ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <div class="grid grid-cols-2 gap-2 mb-4">
                    <div class="bg-black/30 p-2.5 rounded-xl border border-white/5">
                        <p class="text-[8px] text-gray-500 uppercase font-black tracking-widest mb-1">Total</p>
                        <p class="text-xs font-black text-white">
                            <?= $trx['payment_method'] === 'poin' ? number_format($trx['total'], 0, ',', '.') . ' Poin' : 'Rp ' . number_format($trx['total'], 0, ',', '.') ?>
                        </p>
                    </div>
                    <?php if ($showUserColumn && isset($trx['poin_cwpa'])): ?>
                    <div class="bg-black/30 p-2.5 rounded-xl border border-white/5">
                        <p class="text-[8px] text-gray-500 uppercase font-black tracking-widest mb-1">Poin CWPA</p>
                        <div class="flex items-center gap-1">
                            <i class="fas fa-coins text-yellow-500 text-[10px]"></i>
                            <p class="text-xs font-black text-yellow-500"><?= number_format($trx['poin_cwpa'] ?? 0, 0, ',', '.') ?></p>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="bg-black/30 p-2.5 rounded-xl border border-white/5">
                        <p class="text-[8px] text-gray-500 uppercase font-black tracking-widest mb-1">Metode</p>
                        <p class="text-xs font-black text-white capitalize"><?= esc($trx['payment_method']) ?></p>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-white/5">
                    <p class="text-[9px] text-gray-600 font-bold uppercase italic tracking-widest"><?= date('d M Y, H:i', strtotime($trx['created_at'])) ?></p>
                    <div class="flex gap-2">
                        <a href="<?= base_url('cwpa/dashboard/transaksi/invoice/' . $trx['invoice_number']) ?>" target="_blank"
                            class="w-9 h-9 flex items-center justify-center bg-white/5 border border-white/10 rounded-xl text-gray-400 hover:bg-accent hover:text-black transition shadow-lg">
                            <i class="fas fa-file-invoice text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Desktop Table View -->
<div class="hidden md:block bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-black/50 border-b border-white/10">
                <tr>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-500 uppercase tracking-widest">Invoice</th>
                    <?php if ($showUserColumn): ?>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-500 uppercase tracking-widest">User</th>
                    <?php endif; ?>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-500 uppercase tracking-widest">Produk</th>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-500 uppercase tracking-widest text-right">Total</th>
                    <?php if ($showUserColumn): ?>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-500 uppercase tracking-widest text-right">Poin CWPA</th>
                    <?php else: ?>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-500 uppercase tracking-widest text-center">Metode</th>
                    <?php endif; ?>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-500 uppercase tracking-widest text-center">Status</th>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-500 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($transactions)): ?>
                    <tr>
                        <td colspan="<?= $showUserColumn ? '7' : '6' ?>" class="px-6 py-12 text-center text-gray-500">
                             <i class="fas fa-receipt text-4xl mb-4"></i>
                             <p class="font-bold">Belum ada transaksi</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($transactions as $trx): ?>
                        <tr class="hover:bg-white/[0.02] transition">
                            <td class="px-6 py-5">
                                <span class="font-black text-accent text-xs tracking-tighter"><?= esc($trx['invoice_number']) ?></span>
                                <p class="text-[9px] text-gray-600 font-bold mt-1 tracking-widest"><?= date('d/m/y H:i', strtotime($trx['created_at'])) ?></p>
                            </td>
                            <?php if ($showUserColumn): ?>
                            <td class="px-6 py-5">
                                <h4 class="font-black text-sm text-white"><?= esc($trx['user_name'] ?? '-') ?></h4>
                                <p class="text-[10px] text-gray-600 font-bold"><?= esc($trx['user_email'] ?? '') ?></p>
                            </td>
                            <?php endif; ?>
                            <td class="px-6 py-5">
                                <p class="text-xs font-bold text-white"><?= esc($trx['product_name']) ?></p>
                            </td>
                            <td class="px-6 py-5 text-right font-black text-sm text-white">
                                <?= $trx['payment_method'] === 'poin' ? number_format($trx['total'], 0, ',', '.') . ' Poin' : 'Rp ' . number_format($trx['total'], 0, ',', '.') ?>
                            </td>
                            <?php if ($showUserColumn): ?>
                            <td class="px-6 py-5 text-right font-black text-sm text-yellow-500">
                                <div class="flex items-center justify-end gap-1">
                                    <i class="fas fa-coins text-[10px]"></i>
                                    <span><?= number_format($trx['poin_cwpa'] ?? 0, 0, ',', '.') ?></span>
                                </div>
                            </td>
                            <?php else: ?>
                            <td class="px-6 py-5 text-center">
                                <span class="px-3 py-1 bg-white/5 text-gray-400 rounded-lg text-[9px] font-black uppercase tracking-widest border border-white/10"><?= esc($trx['payment_method']) ?></span>
                            </td>
                            <?php endif; ?>
                            <td class="px-6 py-5 text-center">
                                <?php if ($trx['status'] === 'confirmed'): ?>
                                    <span class="px-3 py-1 bg-accent/10 text-accent rounded-lg text-[9px] font-black uppercase tracking-widest border border-accent/20">Success</span>
                                <?php elseif ($trx['status'] === 'pending'): ?>
                                    <span class="px-3 py-1 bg-yellow-500/10 text-yellow-500 rounded-lg text-[9px] font-black uppercase tracking-widest border border-yellow-500/10">Pending</span>
                                <?php else: ?>
                                    <span class="px-3 py-1 bg-red-500/10 text-red-500 rounded-lg text-[9px] font-black uppercase tracking-widest border border-red-500/10"><?= strtoupper(esc($trx['status'])) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?= base_url('cwpa/dashboard/transaksi/invoice/' . $trx['invoice_number']) ?>" target="_blank"
                                        class="w-8 h-8 flex items-center justify-center bg-white/5 border border-white/10 rounded-lg text-gray-500 hover:bg-accent hover:text-black transition" title="Invoice">
                                        <i class="fas fa-file-invoice text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($pager && $pager->getPageCount() > 1): ?>
    <div class="mt-8 flex justify-center">
        <?= $pager->links('default', 'admin_pagination') ?>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
