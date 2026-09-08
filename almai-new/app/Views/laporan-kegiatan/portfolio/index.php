<?php 
$this->setVar('pageTitle', 'Data Portofolio EA');
$this->setVar('pageSubtitle', 'Daftar seluruh akun trading dari Expert Advisor (Read Only)');
?>
<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>

<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-[#111] p-6 rounded-2xl border border-white/10 shadow-2xl overflow-hidden relative group">
    <div class="absolute -right-10 -bottom-10 opacity-10 blur-3xl w-40 h-40 bg-accent transition duration-700 pointer-events-none group-hover:scale-110"></div>
    <div class="relative">
        <h1 class="text-2xl font-black text-white mb-1 tracking-tight">Monitor Portofolio Global</h1>
        <p class="text-xs text-gray-500 uppercase tracking-widest font-bold italic">Real-time Trading Accounts Surveillance</p>
    </div>
</div>

<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl relative">
    <div class="overflow-x-auto scrollbar-hide">
        <table class="w-full min-w-[1000px]">
            <thead class="bg-black/50 border-b border-white/10">
                <tr>
                    <th class="px-4 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest w-12 text-center">#</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-left">Owner Identity</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-left">Trading Profile</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-left">Broker Infrastructure</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-right">Account Equity</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-center">Live Status</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if(empty($accounts)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center text-gray-600">
                            <i class="fas fa-microchip text-4xl mb-4 opacity-10"></i>
                            <p class="text-sm font-black uppercase tracking-widest">No active portfolio found in network</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($accounts as $index => $acc): ?>
                        <tr class="hover:bg-white/[0.02] transition group">
                            <td class="px-4 py-4 text-center font-mono text-[10px] text-gray-600 italic"><?= $index + 1 ?></td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-white font-black text-sm"><?= esc($acc['owner_name'] ?? 'N/A') ?></span>
                                    <span class="text-[10px] text-gray-600 font-bold tracking-widest"><?= esc($acc['owner_email'] ?? 'N/A') ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-accent font-black text-sm uppercase tracking-tighter"><?= esc($acc['account_name']) ?></span>
                                    <span class="text-[10px] text-gray-500 font-mono italic">LOGIN ID: <?= esc($acc['account_login']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-white font-black text-xs uppercase"><?= esc($acc['broker']) ?></span>
                                    <span class="text-[9px] text-gray-600 font-black uppercase tracking-widest leading-none"><?= esc($acc['server']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <p class="text-white font-mono font-black text-sm italic">$<?= number_format($acc['equity'], 2) ?></p>
                                <p class="text-[8px] text-gray-700 font-black tracking-widest uppercase">Live Balance Pool</p>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-col items-center gap-1">
                                    <?php if($acc['is_online']): ?>
                                        <span class="px-2 py-0.5 bg-accent/10 text-accent rounded text-[8px] font-black uppercase tracking-widest border border-accent/20">ONLINE</span>
                                        <div class="w-1.5 h-1.5 bg-accent rounded-full animate-pulse shadow-[0_0_8px_rgba(34,255,100,0.5)]"></div>
                                    <?php else: ?>
                                        <span class="px-2 py-0.5 bg-red-500/10 text-red-500 rounded text-[8px] font-black uppercase tracking-widest border border-red-500/20 grayscale opacity-50">STALL</span>
                                        <div class="w-1.5 h-1.5 bg-red-500/50 rounded-full"></div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="<?= base_url('portofolio/' . $acc['account_login']) ?>" target="_blank" class="w-8 h-8 mx-auto flex items-center justify-center bg-white/5 text-gray-500 rounded-lg hover:bg-white hover:text-black transition border border-white/5" title="View Public Link">
                                    <i class="fas fa-external-link-alt text-xs"></i>
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
