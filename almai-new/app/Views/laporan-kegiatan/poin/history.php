<?php 
$this->setVar('pageTitle', 'Riwayat Poin');
$this->setVar('pageSubtitle', 'Log Seluruh Aktivitas Transaksi & Distribusi Poin');
?>
<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Filters -->
<div class="bg-[#111] border border-white/10 rounded-2xl p-4 mb-6 shadow-xl">
    <form action="<?= base_url('laporan-kegiatan/poin/history') ?>" method="get" class="flex flex-col md:flex-row gap-4">
        <div class="relative flex-1">
            <input type="text" name="search" value="<?= esc($currentSearch) ?>" placeholder="Cari nama, email, atau deskripsi..." class="w-full px-4 py-2.5 pl-10 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm transition text-white">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-xs"></i>
        </div>
        <div class="flex gap-2">
            <select name="type" class="px-4 py-2.5 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm transition text-white">
                <option value="all" <?= ($currentType ?? '') === 'all' ? 'selected' : '' ?>>Semua Tipe</option>
                <option value="earn" <?= ($currentType ?? '') === 'earn' ? 'selected' : '' ?>>Pemasukan (Earn)</option>
                <option value="redeem" <?= ($currentType ?? '') === 'redeem' ? 'selected' : '' ?>>Pengeluaran (Redeem)</option>
                <option value="bonus" <?= ($currentType ?? '') === 'bonus' ? 'selected' : '' ?>>Bonus</option>
                <option value="share_in" <?= ($currentType ?? '') === 'share_in' ? 'selected' : '' ?>>Share In</option>
                <option value="share_out" <?= ($currentType ?? '') === 'share_out' ? 'selected' : '' ?>>Share Out</option>
            </select>
            <button type="submit" class="px-6 py-2.5 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center justify-center gap-2">
                <i class="fas fa-filter"></i> Filter
            </button>
            <a href="<?= base_url('laporan-kegiatan/poin') ?>" class="px-6 py-2.5 bg-white/5 border border-white/10 text-white font-bold rounded-xl hover:bg-white/10 transition flex items-center justify-center gap-2">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </form>
</div>

<!-- History Table -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[700px]">
            <thead class="bg-black/80">
                <tr>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest text-center w-16">No</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">User / Anggota</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Tipe & Deskripsi</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest text-center">Jumlah</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest text-center">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($poinList)): ?>
                <tr>
                    <td colspan="5" class="px-6 py-20 text-center text-gray-500">
                        <div class="flex flex-col items-center gap-4">
                            <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center">
                                <i class="fas fa-history text-2xl"></i>
                            </div>
                            <p class="text-sm font-medium">Tidak ada riwayat ditemukan</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php $no = 1 + (($pager->getCurrentPage() - 1) * 20); foreach ($poinList as $poin): ?>
                <tr class="hover:bg-white/5 transition group">
                    <td class="px-4 py-4 text-gray-500 text-sm text-center font-mono"><?= sprintf('%02d', $no++) ?></td>
                    <td class="px-4 py-4">
                        <div>
                            <p class="font-bold text-sm text-gray-200"><?= esc($poin['name'] ?? 'Unknown User') ?></p>
                            <p class="text-[10px] text-gray-500 opacity-70"><?= esc($poin['email'] ?? '-') ?></p>
                        </div>
                    </td>
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-2 mb-1">
                            <?php 
                                $badgeClass = 'bg-gray-500/10 text-gray-500';
                                if ($poin['type'] === 'earn' || $poin['type'] === 'bonus' || $poin['type'] === 'share_in') $badgeClass = 'bg-emerald-500/10 text-emerald-500';
                                if ($poin['type'] === 'redeem' || $poin['type'] === 'share_out') $badgeClass = 'bg-red-500/10 text-red-500';
                            ?>
                            <span class="px-2 py-0.5 <?= $badgeClass ?> rounded text-[10px] font-black uppercase tracking-wider"><?= esc($poin['type']) ?></span>
                        </div>
                        <p class="text-xs text-gray-400"><?= esc($poin['description']) ?></p>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <p class="text-sm font-black <?= $poin['point'] > 0 ? 'text-emerald-400' : 'text-red-400' ?>">
                            <?= $poin['point'] > 0 ? '+' : '' ?><?= $poin['point'] ?>
                        </p>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <p class="text-[11px] text-gray-500 font-mono"><?= date('d M Y', strtotime($poin['created_at'])) ?></p>
                        <p class="text-[10px] text-gray-600 font-mono"><?= date('H:i', strtotime($poin['created_at'])) ?> WIB</p>
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

<?= $this->endSection() ?>
