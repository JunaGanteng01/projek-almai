<?php 
$this->setVar('pageTitle', 'Data Voucher');
$this->setVar('pageSubtitle', 'Daftar voucher diskon yang sedang aktif & riwayat (Read Only)');
$currentPage = $pager->getCurrentPage();
$perPage = 20;
?>
<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Stats Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-2xl relative overflow-hidden group">
        <div class="absolute -right-10 -bottom-10 opacity-10 group-hover:scale-110 transition duration-700">
            <i class="fas fa-ticket-alt text-8xl text-purple-500"></i>
        </div>
        <p class="text-xs font-black text-gray-500 uppercase tracking-widest mb-2 italic">Total Vouchers</p>
        <h3 class="text-3xl font-black text-white"><?= $totalVouchers ?></h3>
        <p class="text-[10px] text-gray-600 font-bold mt-1">Sistem Database Log</p>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-2xl relative overflow-hidden group">
        <div class="absolute -right-10 -bottom-10 opacity-10 group-hover:scale-110 transition duration-700">
            <i class="fas fa-check-circle text-8xl text-accent"></i>
        </div>
        <p class="text-xs font-black text-gray-500 uppercase tracking-widest mb-2 italic">Active Vouchers</p>
        <h3 class="text-3xl font-black text-accent"><?= $activeVouchers ?></h3>
        <p class="text-[10px] text-accent/50 font-bold mt-1">Ready for Redemption</p>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-2xl relative overflow-hidden group">
        <div class="absolute -right-10 -bottom-10 opacity-10 group-hover:scale-110 transition duration-700">
            <i class="fas fa-users text-8xl text-blue-500"></i>
        </div>
        <p class="text-xs font-black text-gray-500 uppercase tracking-widest mb-2 italic">Total Usage</p>
        <h3 class="text-3xl font-black text-blue-500"><?= $totalUsage ?></h3>
        <p class="text-[10px] text-blue-500/50 font-bold mt-1">Total Redeemed Times</p>
    </div>
</div>

<div class="mb-6 flex gap-2 overflow-x-auto pb-4 scrollbar-hide">
    <a href="?status=all" class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest whitespace-nowrap transition <?= !$currentStatus || $currentStatus === 'all' ? 'bg-accent text-black' : 'bg-white/5 text-gray-500 border border-white/10' ?>">ALL STATUS</a>
    <a href="?status=active" class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest whitespace-nowrap transition <?= $currentStatus === 'active' ? 'bg-accent text-black' : 'bg-white/5 text-gray-500 border border-white/10' ?>">ACTIVE</a>
    <a href="?status=inactive" class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest whitespace-nowrap transition <?= $currentStatus === 'inactive' ? 'bg-accent text-black' : 'bg-white/5 text-gray-500 border border-white/10' ?>">INACTIVE</a>
</div>

<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto scrollbar-hide">
        <table class="w-full min-w-[1000px]">
            <thead class="bg-black/50 border-b border-white/10">
                <tr>
                    <th class="px-4 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest w-12 text-center">#</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-left">Code & Name</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-left">Discount Value</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-center">Product Type</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-center">Usage Limit</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-center">Expiry</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-right">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($vouchers)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-20 text-center text-gray-600">
                        <i class="fas fa-ticket-alt text-4xl mb-4 opacity-10"></i>
                        <p class="text-sm font-black uppercase tracking-widest">No vouchers in system</p>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($vouchers as $index => $voucher): ?>
                <tr class="hover:bg-white/[0.02] transition group">
                    <td class="px-4 py-4 text-center font-mono text-[10px] text-gray-600 italic">
                        <?= ($currentPage - 1) * $perPage + $index + 1 ?>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="text-accent font-mono font-black text-sm tracking-widest uppercase mb-1"><?= esc($voucher['code']) ?></span>
                            <span class="text-[11px] text-gray-300 font-bold"><?= esc($voucher['name']) ?></span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <?php if ($voucher['discount_type'] === 'percentage'): ?>
                            <div class="flex flex-col">
                                <span class="text-white font-black text-sm"><?= $voucher['discount_value'] ?>% OFF</span>
                                <?php if ($voucher['max_discount']): ?>
                                    <span class="text-[9px] text-gray-500 font-bold uppercase tracking-tighter">MAX: Rp <?= number_format($voucher['max_discount'], 0, ',', '.') ?></span>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <span class="text-white font-black text-sm">Rp <?= number_format($voucher['discount_value'], 0, ',', '.') ?> OFF</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <?php
                        $typeClass = [
                            'all' => 'bg-purple-500/10 text-purple-400 border-purple-500/20',
                            'layanan' => 'bg-accent/10 text-accent border-accent/20',
                        ][$voucher['product_type']] ?? 'bg-blue-500/10 text-blue-400 border-blue-500/20';
                        ?>
                        <span class="px-3 py-1 <?= $typeClass ?> border rounded text-[9px] font-black uppercase tracking-widest">
                            <?= (!$voucher['product_type'] || $voucher['product_type'] === 'all') ? 'Global' : ucfirst($voucher['product_type']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex flex-col items-center">
                            <span class="text-white font-mono font-black text-sm"><?= $voucher['used_count'] ?><?= $voucher['usage_limit'] ? '<span class="text-gray-600">/</span>' . $voucher['usage_limit'] : '' ?></span>
                            <span class="text-[8px] text-gray-600 font-black uppercase tracking-tighter">Times Used</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <?php if ($voucher['start_date'] || $voucher['end_date']): ?>
                            <div class="flex flex-col text-[10px] font-mono text-gray-500 font-bold italic">
                                <span><?= $voucher['start_date'] ? date('d.m.y', strtotime($voucher['start_date'])) : '--' ?></span>
                                <span class="text-gray-700">TO</span>
                                <span><?= $voucher['end_date'] ? date('d.m.y', strtotime($voucher['end_date'])) : '--' ?></span>
                            </div>
                        <?php else: ?>
                            <span class="text-accent font-black text-[10px] italic uppercase">Forever</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <span class="px-2 py-1 rounded text-[9px] font-black uppercase tracking-widest italic <?= $voucher['status'] === 'active' ? 'bg-accent/20 text-accent border border-accent/30' : 'bg-red-500/10 text-red-500 border border-red-500/20' ?>">
                            <?= $voucher['status'] === 'active' ? 'Active' : 'Offline' ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
