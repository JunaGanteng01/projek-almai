<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Performance Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6">
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                <i class="fas fa-user-tie text-white"></i>
            </div>
            <div>
                <p class="text-xl font-bold text-white"><?= number_format(count($wpaList)) ?></p>
                <p class="text-xs text-gray-500">Total WPA</p>
            </div>
        </div>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-shopping-cart text-blue-500"></i>
            </div>
            <div>
                <p class="text-xl font-bold text-white"><?= number_format(array_sum(array_column($wpaList, 'total_transactions'))) ?></p>
                <p class="text-xs text-gray-500">Transactions</p>
            </div>
        </div>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-coins text-green-500"></i>
            </div>
            <div>
                <p class="text-xl font-bold text-white"><?= number_format(array_sum(array_column($wpaList, 'total_revenue')), 0, ',', '.') ?></p>
                <p class="text-xs text-gray-500">Revenue (IDR)</p>
            </div>
        </div>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-hand-holding-usd text-yellow-500"></i>
            </div>
            <div>
                <p class="text-xl font-bold text-white"><?= number_format(array_sum(array_column($wpaList, 'total_commission')), 0, ',', '.') ?></p>
                <p class="text-xs text-gray-500">Commission (IDR)</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters Bar (Empty for now to match style) -->
<div class="flex justify-between items-center mb-6">
    <div class="flex items-center gap-2">
        <h2 class="text-lg font-bold text-white uppercase tracking-tighter">WPA Performance List</h2>
    </div>
    <div class="flex gap-2">
        <button onclick="window.location.reload()" class="px-4 py-2 bg-white/5 border border-white/10 hover:bg-white/10 text-white rounded-xl text-sm transition font-bold flex items-center gap-2">
            <i class="fas fa-sync-alt"></i> Refresh Data
        </button>
    </div>
</div>

<!-- Performance Table -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-black/50">
                <tr>
                    <th class="text-center px-4 py-3 text-xs font-medium text-gray-400 w-12">No</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">WPA Account</th>
                    <th class="text-center px-4 py-3 text-xs font-medium text-gray-400">Transactions</th>
                    <th class="text-right px-4 py-3 text-xs font-medium text-gray-400">Revenue</th>
                    <th class="text-right px-4 py-3 text-xs font-medium text-gray-400">Commission</th>
                    <th class="text-center px-4 py-3 text-xs font-medium text-gray-400">Score</th>
                    <th class="text-center px-4 py-3 text-xs font-medium text-gray-400">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($wpaList)): ?>
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center text-gray-500">
                            <i class="fas fa-chart-line text-4xl mb-4 opacity-10"></i>
                            <p class="text-sm">Tidak ada data performa ditemukan</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($wpaList as $index => $wpa): ?>
                        <tr class="hover:bg-white/5 transition group">
                            <td class="px-4 py-4 text-center text-gray-500 text-xs"><?= $index + 1 ?></td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-accent/20 flex items-center justify-center text-accent font-bold text-xs">
                                        <?= strtoupper(substr($wpa['name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <p class="font-bold text-white text-sm tracking-tight"><?= esc($wpa['name']) ?></p>
                                        <p class="text-[10px] text-gray-500 uppercase tracking-widest"><?= esc($wpa['user_email']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <span class="px-2.5 py-1 bg-blue-500/10 text-blue-400 rounded-lg text-xs font-bold border border-blue-500/20">
                                    <?= number_format($wpa['total_transactions']) ?> TXS
                                </span>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <p class="text-white font-bold text-sm">Rp <?= number_format($wpa['total_revenue'], 0, ',', '.') ?></p>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <p class="text-accent font-bold text-sm">Rp <?= number_format($wpa['total_commission'], 0, ',', '.') ?></p>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex items-center justify-center gap-0.5 text-yellow-500 text-[9px]">
                                    <?php 
                                        $rating = $wpa['total_transactions'] > 10 ? 5 : ($wpa['total_transactions'] > 5 ? 4 : ($wpa['total_transactions'] > 0 ? 3 : 0));
                                        for ($i = 1; $i <= 5; $i++) echo '<i class="'.($i <= $rating ? 'fas' : 'far').' fa-star"></i>';
                                    ?>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <a href="<?= base_url('laporan-kegiatan/wpa/view/' . $wpa['id'] ?? '') ?>" class="inline-flex items-center gap-2 px-3 py-1 bg-white/5 border border-white/10 text-white rounded-lg hover:bg-white hover:text-black transition text-[10px] font-bold">
                                    <i class="fas fa-external-link-alt"></i> Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
