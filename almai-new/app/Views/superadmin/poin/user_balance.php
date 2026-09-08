<?php 
$this->setVar('pageTitle', 'Saldo & Riwayat Poin User');
$this->setVar('pageSubtitle', 'Detail poin untuk ' . esc($user['name']));
?>
<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Back Link -->
<div class="mb-6">
    <a href="<?= base_url('superadmin/poin') ?>" class="flex items-center gap-2 text-gray-400 hover:text-white transition group">
        <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        <span>Kembali ke Ringkasan</span>
    </a>
</div>

<!-- User Info & Balance Cards -->
<div class="grid md:grid-cols-4 gap-4 md:gap-6 mb-6">
    <div class="md:col-span-2 bg-[#111] border border-white/10 rounded-2xl p-6 flex items-center gap-6 shadow-xl">
        <div class="w-20 h-20 bg-accent/20 rounded-full flex items-center justify-center border-2 border-accent/20">
            <i class="fas fa-user text-3xl text-accent"></i>
        </div>
        <div>
            <h2 class="text-2xl font-black text-white"><?= esc($user['name']) ?></h2>
            <p class="text-gray-500"><?= esc($user['email']) ?></p>
            <div class="flex gap-2 mt-2">
                <span class="px-2 py-0.5 bg-white/5 border border-white/10 rounded text-[10px] text-gray-400 uppercase tracking-widest font-bold">User ID: <?= $user['id'] ?></span>
            </div>
        </div>
    </div>
    
    <div class="bg-accent rounded-2xl p-6 flex flex-col justify-center items-center text-black shadow-xl shadow-accent/20">
        <p class="text-[10px] font-black uppercase tracking-widest opacity-60 mb-1">Saldo Poin</p>
        <p class="text-3xl font-black"><?= number_format($balance) ?></p>
        <p class="text-[9px] font-bold mt-1 opacity-60">Tersedia untuk digunakan</p>
    </div>

    <div class="bg-emerald-500 rounded-2xl p-6 flex flex-col justify-center items-center text-black shadow-xl shadow-emerald-500/20">
        <p class="text-[10px] font-black uppercase tracking-widest opacity-60 mb-1 text-center">Saldo Rupiah</p>
        <p class="text-2xl font-black text-center">Rp <?= number_format($user['balance'] ?? 0, 0, ',', '.') ?></p>
        <p class="text-[9px] font-bold mt-1 opacity-60 text-center">Saldo utama user</p>
    </div>
</div>

<!-- Summary Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-2 mb-2">
            <div class="w-7 h-7 bg-green-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-arrow-down text-green-400 text-xs"></i>
            </div>
            <p class="text-[10px] text-gray-500 uppercase tracking-wider font-bold">Total Poin Masuk</p>
        </div>
        <p class="text-xl font-black text-green-400">+<?= number_format($totalEarned) ?></p>
        <p class="text-[9px] text-gray-600 mt-0.5">Semua poin yang pernah diterima</p>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-2 mb-2">
            <div class="w-7 h-7 bg-red-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-arrow-up text-red-400 text-xs"></i>
            </div>
            <p class="text-[10px] text-gray-500 uppercase tracking-wider font-bold">Total Poin Keluar</p>
        </div>
        <p class="text-xl font-black text-red-400">-<?= number_format($totalRedeemed) ?></p>
        <p class="text-[9px] text-gray-600 mt-0.5">Redeem & penggunaan poin</p>
    </div>
    <div class="bg-[#111] border border-accent/20 rounded-xl p-4">
        <div class="flex items-center gap-2 mb-2">
            <div class="w-7 h-7 bg-accent/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-share-alt text-accent text-xs"></i>
            </div>
            <p class="text-[10px] text-gray-500 uppercase tracking-wider font-bold">Poin Referral</p>
        </div>
        <p class="text-xl font-black text-accent">+<?= number_format($totalReferralPoin) ?></p>
        <p class="text-[9px] text-gray-600 mt-0.5">Komisi dari referral chain</p>
    </div>
    <div class="bg-[#111] border border-emerald-500/20 rounded-xl p-4">
        <div class="flex items-center gap-2 mb-2">
            <div class="w-7 h-7 bg-emerald-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-money-bill-wave text-emerald-400 text-xs"></i>
            </div>
            <p class="text-[10px] text-gray-500 uppercase tracking-wider font-bold">Saldo Cash</p>
        </div>
        <p class="text-xl font-black text-emerald-400">Rp <?= number_format($totalReferralCash, 0, ',', '.') ?></p>
        <p class="text-[9px] text-gray-600 mt-0.5">Saldo rupiah saat ini</p>
    </div>
</div>

<!-- History Table -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
    <div class="p-4 border-b border-white/10 flex justify-between items-center">
        <h3 class="font-black uppercase tracking-wider text-sm">Riwayat Transaksi</h3>
        <i class="fas fa-history text-gray-600"></i>
    </div>
    <div class="overflow-x-auto scrollbar-hide">
        <table class="w-full min-w-[600px]">
            <thead class="bg-black/80">
                <tr>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 w-12 text-center uppercase tracking-widest">No</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Tipe</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Jumlah</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Keterangan</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($history)): ?>
                <tr>
                    <td colspan="5" class="px-6 py-20 text-center text-gray-500">
                        <div class="flex flex-col items-center gap-4">
                            <i class="fas fa-folder-open text-2xl opacity-20"></i>
                            <p class="text-sm font-medium">Belum ada riwayat poin untuk user ini</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php $no = 1; foreach ($history as $item): ?>
                <tr class="hover:bg-white/5 transition">
                    <td class="px-4 py-4 text-gray-500 text-sm text-center font-mono"><?= sprintf('%02d', $no++) ?></td>
                    <td class="px-4 py-4">
                        <?php
                        $typeColors = [
                            'earn' => 'bg-green-500/20 text-green-400 border-green-500/30',
                            'redeem' => 'bg-red-500/20 text-red-400 border-red-500/30',
                            'bonus' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                            'expired' => 'bg-gray-500/20 text-gray-400 border-gray-500/30',
                        ];
                        $colorClass = $typeColors[$item['type']] ?? 'bg-gray-500/20 text-gray-400 border-gray-500/30';
                        ?>
                        <span class="px-2.5 py-1 text-[10px] font-black rounded-lg uppercase tracking-wider border <?= $colorClass ?>">
                            <?= esc($item['type']) ?>
                        </span>
                    </td>
                    <td class="px-4 py-4">
                        <span class="font-black text-sm <?= $item['point'] > 0 ? 'text-accent' : 'text-red-400' ?>">
                            <?= $item['point'] > 0 ? '+' : '' ?><?= number_format($item['point']) ?>
                        </span>
                    </td>
                    <td class="px-4 py-4 text-gray-400 text-[11px]">
                        <?= esc($item['description']) ?>
                    </td>
                    <td class="px-4 py-4">
                        <p class="text-[11px] text-gray-500"><?= date('d M Y', strtotime($item['created_at'])) ?></p>
                        <p class="text-[9px] text-gray-700 font-mono"><?= date('H:i', strtotime($item['created_at'])) ?> WIB</p>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($pager && $pager->getPageCount() > 1): ?>
        <div class="px-4 py-4 border-t border-white/5 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-sm text-gray-500">
                Menampilkan halaman <?= $pager->getCurrentPage() ?> dari <?= $pager->getPageCount() ?>
            </p>
            <div class="flex items-center gap-1 flex-wrap justify-center">
                <?= $pager->links('default', 'admin_pagination') ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<?= $this->endSection() ?>
