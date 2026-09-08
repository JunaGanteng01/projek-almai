<?php 
$this->setVar('pageTitle', 'Detail Poin User');
$this->setVar('pageSubtitle', 'Saldo & Riwayat Aktivitas Poin Individual');
?>
<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>

<!-- User Stats Card -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="md:col-span-1">
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 text-center shadow-xl h-full flex flex-col justify-center">
            <div class="w-20 h-20 bg-accent/20 rounded-full flex items-center justify-center mx-auto mb-4 text-accent text-3xl font-black">
                <?= strtoupper(substr($user['name'], 0, 1)) ?>
            </div>
            <h3 class="text-xl font-black text-white truncate px-4"><?= esc($user['name']) ?></h3>
            <p class="text-sm text-gray-500 mb-6 truncate px-4"><?= esc($user['email']) ?></p>
            <div class="p-4 bg-black/40 rounded-xl border border-white/5 inline-block mx-auto min-w-[200px]">
                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-black mb-1">Saldo Poin Saat Ini</p>
                <p class="text-3xl font-black text-accent"><?= number_format($balance) ?> <span class="text-xs text-gray-500">POIN</span></p>
            </div>
            <p class="text-xs text-emerald-400 mt-4 font-bold">Saldo Rupiah: Rp <?= number_format($user['balance'], 0, ',', '.') ?></p>
        </div>
    </div>
    
    <div class="md:col-span-2">
        <div class="bg-[#111] border border-white/10 rounded-2xl shadow-xl h-full flex flex-col">
            <div class="p-6 border-b border-white/5 flex justify-between items-center">
                <h3 class="text-sm font-black uppercase tracking-widest text-gray-400">Riwayat Transaksi Poin Terbaru</h3>
                <a href="<?= base_url('laporan-kegiatan/poin') ?>" class="text-xs text-accent hover:underline flex items-center gap-2">
                   <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                </a>
            </div>
            
            <div class="flex-1 overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-black/40">
                        <tr>
                            <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Aktivitas</th>
                            <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Jumlah</th>
                            <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <?php if (empty($history)): ?>
                        <tr>
                            <td colspan="3" class="px-6 py-20 text-center text-gray-600">
                                <p class="text-sm">Belum ada riwayat transaksi poin</p>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($history as $h): ?>
                        <tr class="hover:bg-white/5 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full <?= $h['point'] > 0 ? 'bg-emerald-500' : 'bg-red-500' ?>"></div>
                                    <div class="min-w-0">
                                        <p class="text-xs font-bold text-gray-200 uppercase tracking-wide truncate max-w-[250px]"><?= esc($h['description']) ?></p>
                                        <p class="text-[10px] text-gray-500 mt-0.5 opacity-60"><?= esc($h['type']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <p class="text-sm font-black <?= $h['point'] > 0 ? 'text-emerald-400' : 'text-red-400' ?>">
                                    <?= $h['point'] > 0 ? '+' : '' ?><?= $h['point'] ?>
                                </p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <p class="text-[10px] text-gray-500 font-mono"><?= date('d M Y H:i', strtotime($h['created_at'])) ?></p>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
             <!-- Pagination -->
            <?php if ($pager && $pager->getPageCount() > 1): ?>
                <div class="px-4 py-4 border-t border-white/5 flex items-center justify-center gap-1 flex-wrap">
                    <?= $pager->links('default', 'admin_pagination') ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
