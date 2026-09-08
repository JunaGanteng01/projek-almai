<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<?php
$totalPoin = $poinBalance ?? 0;
?>

<!-- Poin Dashboard Stats - Mobile Optimized -->
<div class="mb-6">
    <div class="bg-[#050505] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
        <!-- Header Stats (Global System Context) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 border-b border-white/10">
            <div class="p-5 md:p-8 border-b sm:border-b-0 sm:border-r border-white/10 relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent opacity-50"></div>
                <div class="flex flex-col items-center justify-center text-center relative z-10">
                     <div class="flex items-center gap-2 mb-2">
                        <span class="text-[9px] md:text-sm font-bold text-gray-400 uppercase tracking-widest bg-black/50 px-2 py-1 rounded-full border border-white/5 backdrop-blur-sm">Total Almai Poin (Pool)</span>
                    </div>
                    <div class="flex items-baseline gap-1 mb-3">
                        <span class="text-3xl md:text-5xl font-black text-white drop-shadow-sm"><?= number_format((($maxSupply - $totalActive) / $maxSupply) * 100, 2) ?>%</span>
                    </div>
                    <div class="w-full flex items-center justify-center gap-2 bg-emerald-500/10 px-4 py-2.5 rounded-xl border border-emerald-500/20 shadow-[0_0_15px_rgba(16,185,129,0.1)]">
                        <i class="fas fa-coins text-yellow-500 text-xs md:text-base"></i>
                        <span class="text-xs md:text-xl font-black text-white font-mono tracking-tight truncate"><?= number_format($maxSupply - $totalActive) ?></span>
                        <span class="text-[8px] md:text-xs font-bold text-gray-500 uppercase tracking-widest ml-0.5">Limit</span>
                    </div>
                </div>
            </div>
            
            <div class="p-5 md:p-8 relative overflow-hidden group">
                 <div class="absolute inset-0 bg-gradient-to-br from-accent/5 to-transparent opacity-50"></div>
                 <div class="flex flex-col items-center justify-center text-center relative z-10">
                    <div class="flex items-center gap-2 mb-2">
                         <span class="text-[9px] md:text-sm font-bold text-gray-400 uppercase tracking-widest bg-black/50 px-2 py-1 rounded-full border border-white/5 backdrop-blur-sm">Poin Beredar (Circulating)</span>
                    </div>
                    <div class="flex items-baseline gap-1 mb-3">
                        <span class="text-3xl md:text-5xl font-black text-accent drop-shadow-sm"><?= number_format(($totalActive / $maxSupply) * 100, 3) ?><span class="text-sm md:text-3xl ml-0.5">%</span></span>
                    </div>
                    <div class="w-full flex items-center justify-center gap-2 bg-accent/10 px-4 py-2.5 rounded-xl border border-accent/20 shadow-[0_0_15px_rgba(255,215,0,0.1)]">
                        <i class="fas fa-coins text-yellow-500 text-xs md:text-base"></i>
                        <span class="text-xs md:text-xl font-black text-white font-mono tracking-tight truncate"><?= number_format($totalActive) ?></span>
                        <span class="text-[8px] md:text-xs font-bold text-gray-500 uppercase tracking-widest ml-0.5">Poin</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Detailed Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2">
            <!-- Usage -->
            <div class="border-b md:border-b-0 md:border-r border-white/10">
                <div class="p-2.5 text-[9px] md:text-xs font-black text-center text-red-400 uppercase tracking-widest bg-white/5 border-b border-white/5">
                    Penggunaan Poin Pribadi
                </div>
                <div class="divide-y divide-white/5">
                    <?php 
                    $usageItems = [
                        ['label' => 'Total Redeem', 'value' => $stats['redeem'] ?? 0, 'color' => 'text-red-400'],
                        ['label' => 'Artikel', 'value' => $stats['artikel'] ?? 0],
                        ['label' => 'Layanan', 'value' => $stats['layanan_usage'] ?? 0],
                        ['label' => 'Merchandise', 'value' => $stats['merchandise'] ?? 0],
                        ['label' => 'Berbagi Poin', 'value' => $stats['share_out'] ?? 0],
                    ];
                    foreach ($usageItems as $item): ?>
                    <div class="flex items-center justify-between px-5 py-3 hover:bg-white/5 transition">
                        <span class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-widest"><?= $item['label'] ?></span>
                        <span class="text-xs md:text-sm font-black <?= $item['color'] ?? 'text-white' ?>"><?= number_format($item['value']) ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Earnings -->
            <div>
                <div class="p-2.5 text-[9px] md:text-xs font-black text-center text-emerald-400 uppercase tracking-widest bg-white/5 border-b border-white/5">
                    Penghasilan Poin Pribadi
                </div>
                <div class="divide-y divide-white/5">
                    <?php 
                    $earnItems = [
                        ['label' => 'Total Bonus', 'value' => $stats['bonus_total'] ?? 0, 'color' => 'text-emerald-400'],
                        ['label' => 'Poin Register', 'value' => $stats['register'] ?? 0],
                        ['label' => 'Poin Referral', 'value' => $stats['referral'] ?? 0],
                        ['label' => 'Poin Layanan', 'value' => $stats['layanan_earn'] ?? 0],
                        ['label' => 'Terima Poin', 'value' => $stats['share_in'] ?? 0],
                    ];
                    foreach ($earnItems as $item): ?>
                    <div class="flex items-center justify-between px-5 py-3 hover:bg-white/5 transition">
                        <span class="text-[10px] md:text-xs font-bold text-gray-400 uppercase tracking-widest"><?= $item['label'] ?></span>
                        <span class="text-xs md:text-sm font-black <?= $item['color'] ?? 'text-white' ?>"><?= number_format($item['value']) ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Actions - Grid optimized -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Buy Point Card (Mobile version) -->
    <div class="md:col-span-1">
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 h-full flex flex-row md:flex-col items-center justify-between md:justify-center gap-4 text-center">
            <div class="flex items-center gap-4 md:flex-col md:gap-2">
                <div class="w-12 h-12 md:w-16 md:h-16 bg-accent/10 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-cart-plus text-accent text-xl md:text-2xl"></i>
                </div>
                <div class="text-left md:text-center">
                    <h3 class="font-black text-white text-[11px] md:text-sm uppercase tracking-widest">Top Up Poin</h3>
                    <p class="text-[9px] text-gray-500 font-bold uppercase tracking-widest">Beli poin instan</p>
                </div>
            </div>
            <a href="<?= base_url('admin/poin/buy') ?>" class="bg-accent hover:bg-white text-black font-black text-[10px] md:text-xs uppercase tracking-widest px-6 md:w-full py-3 md:py-4 rounded-xl transition shadow-xl shadow-green-900/20 active:scale-95">
                Beli Point
            </a>
        </div>
    </div>

    <!-- How to Earn Points -->
    <div class="md:col-span-2">
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 h-full">
            <h4 class="font-bold mb-4 text-[11px] md:text-sm uppercase tracking-widest text-gray-300">Cara Dapat Almai Poin</h4>
            <div class="grid grid-cols-2 sm:grid-cols-2 gap-3 md:gap-4">
                <div class="flex items-center gap-3 p-3 bg-white/5 rounded-xl border border-white/5">
                    <div class="w-8 h-8 bg-accent/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-user-plus text-accent text-[10px]"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-tight truncate">Ajak User</p>
                        <p class="text-[8px] text-gray-500 font-medium truncate">Bonus Daftar</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 bg-white/5 rounded-xl border border-white/5">
                    <div class="w-8 h-8 bg-purple-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-briefcase text-purple-400 text-[10px]"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-tight truncate">Layanan</p>
                        <p class="text-[8px] text-gray-500 font-medium truncate">Komisi Jual</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 bg-white/5 rounded-xl border border-white/5">
                    <div class="w-8 h-8 bg-yellow-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-shopping-cart text-yellow-500 text-[10px]"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-tight truncate">Referral</p>
                        <p class="text-[8px] text-gray-500 font-medium truncate">Beli Produk</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 bg-white/5 rounded-xl border border-white/5">
                    <div class="w-8 h-8 bg-emerald-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-calendar-check text-emerald-400 text-[10px]"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-tight truncate">Checkin</p>
                        <p class="text-[8px] text-gray-500 font-medium truncate">Poin Harian</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Points History -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
    <div class="p-4 md:p-5 border-b border-white/10 flex justify-between items-center">
        <h4 class="font-bold uppercase tracking-widest text-[11px] md:text-sm text-gray-300">Riwayat Poin</h4>
    </div>
    <?php if (empty($poinHistory)): ?>
        <div class="p-10 text-center">
            <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-coins text-gray-600 text-2xl"></i>
            </div>
            <p class="text-gray-500 text-xs font-medium">Belum ada riwayat poin</p>
        </div>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-black/50">
                    <tr>
                        <th class="px-5 py-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Keterangan</th>
                        <th class="px-5 py-4 text-[10px] font-bold text-gray-500 uppercase tracking-widest text-right">Poin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php foreach ($poinHistory as $poin): ?>
                        <tr class="hover:bg-white/5 transition">
                            <td class="px-5 py-4">
                                <p class="text-xs font-bold text-white mb-0.5"><?= esc($poin['description']) ?></p>
                                <div class="flex items-center gap-2">
                                    <span class="text-[9px] text-gray-500 font-mono"><?= date('d M Y', strtotime($poin['created_at'])) ?></span>
                                    <?php
                                    $typeLabels = [
                                        'earn' => 'Terima',
                                        'redeem' => 'Keluar',
                                        'bonus' => 'Bonus',
                                        'share_in' => 'Masuk',
                                        'share_out' => 'Kirim',
                                    ];
                                    $label = $typeLabels[$poin['type']] ?? ucfirst($poin['type']);
                                    ?>
                                    <span class="text-[8px] uppercase tracking-tighter font-black px-1.5 py-0.5 rounded-sm bg-white/5 text-gray-400 border border-white/5"><?= $label ?></span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <span class="text-sm font-black <?= $poin['point'] > 0 ? 'text-accent' : 'text-red-400' ?>">
                                    <?= ($poin['point'] > 0 ? '+' : '') . number_format($poin['point']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<div class="h-10"></div>

<?= $this->endSection() ?>
