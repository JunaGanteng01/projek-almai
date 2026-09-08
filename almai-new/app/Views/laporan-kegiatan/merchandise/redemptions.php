<?php 
$this->setVar('pageTitle', 'Penukaran Merchandise');
$this->setVar('pageSubtitle', 'Riwayat penukaran merchandise oleh user (Read Only)');
?>
<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>

<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-[#111] p-6 rounded-2xl border border-white/10 shadow-2xl">
    <div>
        <h1 class="text-2xl font-black text-white mb-1 tracking-tight">Status Penukaran</h1>
        <p class="text-xs text-gray-500 uppercase tracking-widest font-bold">Monitor Logistik & Pengiriman</p>
    </div>
    <div class="flex gap-2">
        <a href="<?= base_url('laporan-kegiatan/merchandise') ?>" class="px-4 py-2 bg-white/5 hover:bg-white/10 text-gray-400 rounded-xl transition text-sm font-bold border border-white/10">
            <i class="fas fa-arrow-left mr-2"></i> Katalog
        </a>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-2 lg:grid-cols-5 gap-3 mb-8">
    <div class="bg-[#111] border border-white/10 rounded-xl p-4 shadow-xl">
        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1">Total</p>
        <p class="text-2xl font-black text-white"><?= $stats['total'] ?></p>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-4 shadow-xl">
        <p class="text-[10px] font-black text-yellow-500 uppercase tracking-widest mb-1">Pending</p>
        <p class="text-2xl font-black text-yellow-500"><?= $stats['pending'] ?></p>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-4 shadow-xl">
        <p class="text-[10px] font-black text-blue-500 uppercase tracking-widest mb-1">Proses</p>
        <p class="text-2xl font-black text-blue-500"><?= $stats['processing'] ?></p>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-4 shadow-xl">
        <p class="text-[10px] font-black text-purple-500 uppercase tracking-widest mb-1">Kirim</p>
        <p class="text-2xl font-black text-purple-500"><?= $stats['shipped'] ?></p>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-4 shadow-xl">
        <p class="text-[10px] font-black text-accent uppercase tracking-widest mb-1">Selesai</p>
        <p class="text-2xl font-black text-accent"><?= $stats['completed'] ?></p>
    </div>
</div>

<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl relative">
    <div class="overflow-x-auto scrollbar-hide">
        <table class="w-full min-w-[1000px]">
            <thead class="bg-black/50 border-b border-white/5">
                <tr>
                    <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-widest text-left">User Identity</th>
                    <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-widest text-left">Item Name</th>
                    <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-widest text-left">Points Cost</th>
                    <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-widest text-center">Current Status</th>
                    <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-widest text-left">Tracking Info</th>
                    <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-widest text-left">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($redemptions)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-20 text-center text-gray-600">
                        <i class="fas fa-box-open text-4xl mb-4 opacity-10"></i>
                        <p class="text-sm font-bold uppercase tracking-widest">No redemption logs found</p>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($redemptions as $row): ?>
                <tr class="hover:bg-white/[0.02] transition group">
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="text-white font-black text-sm"><?= esc($row['user_name']) ?></span>
                            <span class="text-[10px] text-gray-500 font-bold"><?= esc($row['user_email']) ?></span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-gray-200 font-bold text-sm"><?= esc($row['merchandise_name']) ?></p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-yellow-500 font-black text-sm italic"><?= number_format($row['points_used']) ?> Poin</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <?php
                        $statusClass = [
                            'pending' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                            'processing' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                            'shipped' => 'bg-purple-500/10 text-purple-500 border-purple-500/20',
                            'completed' => 'bg-accent/10 text-accent border-accent/20',
                            'cancelled' => 'bg-red-500/10 text-red-500 border-red-500/20',
                        ][$row['status']] ?? 'bg-gray-500/10 text-gray-400';
                        ?>
                        <span class="px-3 py-1 <?= $statusClass ?> border rounded text-[9px] font-black uppercase tracking-wider">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-xs text-gray-300 font-mono italic"><?= esc($row['tracking_number'] ?: '---') ?></p>
                    </td>
                    <td class="px-6 py-4 text-[10px] text-gray-600 font-black uppercase">
                        <?= date('d M Y - H:i', strtotime($row['created_at'])) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
