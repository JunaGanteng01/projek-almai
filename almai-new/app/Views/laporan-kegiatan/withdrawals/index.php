<?php 
$this->setVar('pageTitle', 'Penarikan Dana WPA');
$this->setVar('pageSubtitle', 'Daftar pengajuan penarikan dana oleh WPA (Read Only)');
?>
<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Stats Dashboard -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
        <div class="flex items-center gap-4 mb-2">
            <div class="w-10 h-10 bg-yellow-500/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-clock text-yellow-500"></i>
            </div>
            <p class="text-gray-500 text-sm font-medium">Pending</p>
        </div>
        <h3 class="text-2xl font-bold">Rp <?= number_format($stats['total_pending'], 0, ',', '.') ?></h3>
        <p class="text-xs text-gray-500 mt-1"><?= $stats['count_pending'] ?> pengajuan</p>
    </div>

    <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
        <div class="flex items-center gap-4 mb-2">
            <div class="w-10 h-10 bg-blue-500/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-check-circle text-blue-500"></i>
            </div>
            <p class="text-gray-500 text-sm font-medium">Disetujui</p>
        </div>
        <h3 class="text-2xl font-bold">Rp <?= number_format($stats['total_approved'], 0, ',', '.') ?></h3>
        <p class="text-xs text-gray-500 mt-1"><?= $stats['count_approved'] ?> dalam proses</p>
    </div>

    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 text-accent">
        <div class="flex items-center gap-4 mb-2">
            <div class="w-10 h-10 bg-accent/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-hand-holding-usd text-accent"></i>
            </div>
            <p class="text-accent/60 text-sm font-medium">Berhasil</p>
        </div>
        <h3 class="text-2xl font-bold text-accent">Rp <?= number_format($stats['total_completed'], 0, ',', '.') ?></h3>
        <p class="text-xs text-accent/50 mt-1"><?= $stats['count_completed'] ?> penarikan selesai</p>
    </div>
</div>

<!-- List Filters -->
<div class="flex gap-2 mb-6 overflow-x-auto pb-4 scrollbar-hide">
    <a href="?status=" class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider whitespace-nowrap transition <?= empty($currentStatus) ? 'bg-accent text-black' : 'bg-white/5 text-gray-400 hover:bg-white/10' ?>">SEMUA</a>
    <a href="?status=pending" class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider whitespace-nowrap transition <?= $currentStatus === 'pending' ? 'bg-yellow-500 text-black' : 'bg-yellow-500/10 text-yellow-500 hover:bg-yellow-500/20' ?>">PENDING</a>
    <a href="?status=approved" class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider whitespace-nowrap transition <?= $currentStatus === 'approved' ? 'bg-blue-500 text-black' : 'bg-blue-500/10 text-blue-400 hover:bg-blue-500/20' ?>">APPROVED</a>
    <a href="?status=completed" class="px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider whitespace-nowrap transition <?= $currentStatus === 'completed' ? 'bg-accent text-black' : 'bg-accent/10 text-accent hover:bg-accent/20' ?>">COMPLETED</a>
</div>

<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto scrollbar-hide">
        <table class="w-full min-w-[800px]">
            <thead class="bg-black/50">
                <tr>
                    <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-widest text-left">WPA Profile</th>
                    <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-widest text-left">Rekening Tujuan</th>
                    <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-widest text-right">Jumlah (IDR)</th>
                    <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-widest text-center">Status</th>
                    <th class="px-6 py-4 text-xs font-black text-gray-500 uppercase tracking-widest text-left">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($withdrawals)): ?>
                <tr>
                    <td colspan="5" class="px-6 py-20 text-center text-gray-500">
                        <div class="flex flex-col items-center gap-4">
                            <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center">
                                <i class="fas fa-money-bill-wave text-2xl"></i>
                            </div>
                            <p class="text-sm font-medium">Tidak ada data penarikan</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($withdrawals as $wd): ?>
                <tr class="hover:bg-white/5 transition group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-accent/20 flex items-center justify-center font-bold text-accent text-[10px]">
                                <?= substr($wd['wpa_name'], 0, 1) ?>
                            </div>
                            <p class="font-bold text-gray-200 group-hover:text-accent transition"><?= esc($wd['wpa_name']) ?></p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-xs">
                            <p class="text-gray-300 font-bold mb-0.5"><?= esc($wd['bank_name']) ?></p>
                            <p class="text-gray-500 font-mono text-[10px]"><?= esc($wd['account_number']) ?></p>
                            <p class="text-[9px] text-gray-600 font-black mt-1"><?= esc($wd['account_holder']) ?></p>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <span class="text-white font-black text-sm">Rp <?= number_format($wd['amount'], 0, ',', '.') ?></span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <?php
                        $statusMap = [
                            'pending' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                            'approved' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',
                            'completed' => 'bg-accent/10 text-accent border-accent/20',
                            'rejected' => 'bg-red-500/10 text-red-500 border-red-500/20',
                        ];
                        $stClass = $statusMap[$wd['status']] ?? 'bg-gray-500/10 text-gray-400';
                        ?>
                        <span class="px-3 py-1 <?= $stClass ?> border rounded text-[9px] font-black uppercase tracking-wider">
                            <?= ucfirst($wd['status']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-[10px] text-gray-500 font-mono">
                        <p class="text-gray-300"><?= date('d M Y', strtotime($wd['created_at'])) ?></p>
                        <p><?= date('H:i', strtotime($wd['created_at'])) ?> WIB</p>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
