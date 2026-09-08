<?= $this->extend('wpa/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Header -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <h2 class="text-lg md:text-xl font-bold">Earnings</h2>
    <form method="get" class="w-full md:w-auto">
        <select name="period" onchange="this.form.submit()" class="w-full md:w-auto px-4 py-2 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm">
            <option value="month" <?= $currentPeriod === 'month' ? 'selected' : '' ?>>Bulan Ini</option>
            <option value="3month" <?= $currentPeriod === '3month' ? 'selected' : '' ?>>3 Bulan</option>
            <option value="year" <?= $currentPeriod === 'year' ? 'selected' : '' ?>>Tahun Ini</option>
            <option value="all" <?= $currentPeriod === 'all' ? 'selected' : '' ?>>Semua</option>
        </select>
    </form>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-6">
    <!-- Total Earnings -->
    <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6">
        <p class="text-gray-500 text-xs md:text-sm mb-2">Total Earnings</p>
        <h3 class="text-2xl md:text-3xl font-bold text-accent">Rp <?= number_format($totalEarnings, 0, ',', '.') ?></h3>
        <p class="text-xs text-gray-500 mt-2">
            <?php if ($growth > 0): ?>
            <span class="text-accent"><i class="fas fa-arrow-up mr-1"></i>+<?= $growth ?>%</span> dari periode sebelumnya
            <?php elseif ($growth < 0): ?>
            <span class="text-red-400"><i class="fas fa-arrow-down mr-1"></i><?= $growth ?>%</span> dari periode sebelumnya
            <?php else: ?>
            <span class="text-gray-400">Sama dengan periode sebelumnya</span>
            <?php endif; ?>
        </p>
    </div>
    
    <!-- Pending -->
    <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6">
        <p class="text-gray-500 text-xs md:text-sm mb-2">Pending</p>
        <h3 class="text-2xl md:text-3xl font-bold">Rp <?= number_format($pendingEarnings, 0, ',', '.') ?></h3>
        <p class="text-xs text-gray-500 mt-2">Menunggu konfirmasi admin</p>
    </div>
    
    <!-- Transaksi -->
    <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6">
        <p class="text-gray-500 text-xs md:text-sm mb-2">Transaksi</p>
        <h3 class="text-2xl md:text-3xl font-bold"><?= $confirmedTransactions ?></h3>
        <p class="text-xs text-gray-500 mt-2"><?= $totalTransactions ?> total transaksi</p>
    </div>
</div>

<!-- Earnings by Kelas -->
<?php if (!empty($earningsByKelas)): ?>
<div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 mb-6">
    <h3 class="font-bold mb-4 text-sm md:text-base">Earnings per Kelas</h3>
    <div class="space-y-3">
        <?php foreach ($earningsByKelas as $item): ?>
        <div class="flex items-center justify-between p-3 bg-black/50 rounded-xl">
            <div class="flex items-center gap-3">
                <?php if (!empty($item['kelas']['thumbnail'])): ?>
                <img src="<?= base_url($item['kelas']['thumbnail']) ?>" alt="" class="w-10 h-10 rounded-lg object-cover">
                <?php else: ?>
                <div class="w-10 h-10 bg-accent/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-graduation-cap text-accent"></i>
                </div>
                <?php endif; ?>
                <div>
                    <p class="font-medium text-sm"><?= esc($item['kelas']['title']) ?></p>
                    <p class="text-xs text-gray-500"><?= $item['transactions'] ?> transaksi</p>
                </div>
            </div>
            <div class="text-right">
                <p class="font-bold text-accent">Rp <?= number_format($item['earnings'], 0, ',', '.') ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Transaction History -->
<div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6">
    <h3 class="font-bold mb-4 text-sm md:text-base">Riwayat Transaksi</h3>
    
    <?php if (empty($transactionHistory)): ?>
    <div class="text-center py-8 text-gray-500">
        <i class="fas fa-receipt text-4xl mb-3 opacity-50"></i>
        <p>Belum ada transaksi</p>
    </div>
    <?php else: ?>
    <div class="table-responsive">
        <table class="w-full min-w-[500px]">
            <thead class="bg-black/50">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Invoice</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Tanggal</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Student</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Kelas</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Amount</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactionHistory as $trx): ?>
                <tr class="border-t border-white/10 hover:bg-white/5">
                    <td class="px-4 py-3 font-mono text-xs text-accent"><?= esc($trx['invoice_number']) ?></td>
                    <td class="px-4 py-3 text-xs text-gray-400"><?= date('d M Y', strtotime($trx['created_at'])) ?></td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-accent/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-accent text-xs"></i>
                            </div>
                            <span class="text-sm"><?= esc($trx['user_name'] ?? 'Unknown') ?></span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-sm"><?= esc($trx['kelas_title'] ?? $trx['product_name']) ?></td>
                    <td class="px-4 py-3 text-accent font-bold text-sm">Rp <?= number_format($trx['total'], 0, ',', '.') ?></td>
                    <td class="px-4 py-3">
                        <?php
                        $statusColors = [
                            'pending' => 'bg-yellow-500/20 text-yellow-400',
                            'paid' => 'bg-blue-500/20 text-blue-400',
                            'confirmed' => 'bg-accent/20 text-accent',
                            'cancelled' => 'bg-red-500/20 text-red-400',
                        ];
                        $color = $statusColors[$trx['status']] ?? 'bg-gray-500/20 text-gray-400';
                        ?>
                        <span class="px-2 py-1 rounded-full text-xs <?= $color ?>"><?= ucfirst($trx['status']) ?></span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
