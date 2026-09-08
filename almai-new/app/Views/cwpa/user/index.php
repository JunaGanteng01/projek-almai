<?= $this->extend('cwpa/layouts/main') ?>

<?= $this->section('content') ?>
<?php $pageTitle = 'User';
$pageSubtitle = 'User referral dan pembeli kelas Anda'; ?>

<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-5 gap-3 md:gap-4 mb-6">
    <!-- Total User -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-3 md:p-6 hover:border-white/20 transition group">
        <div class="flex items-center gap-2 md:gap-3 mb-2">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-white/10 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-users text-white text-xs md:text-sm"></i>
            </div>
            <span class="text-gray-400 text-[8px] md:text-xs uppercase font-black tracking-widest leading-tight">Total<br class="md:hidden"> User</span>
        </div>
        <h3 class="text-xl md:text-2xl font-black text-white"><?= number_format($totalUserCount ?? 0) ?></h3>
    </div>

    <!-- Total Pembelian -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-3 md:p-6 hover:border-purple-500/20 transition group">
        <div class="flex items-center gap-2 md:gap-3 mb-2">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-purple-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-shopping-bag text-purple-400 text-xs md:text-sm"></i>
            </div>
            <span class="text-gray-400 text-[8px] md:text-xs uppercase font-black tracking-widest leading-tight">Total<br class="md:hidden"> Order</span>
        </div>
        <h3 class="text-xl md:text-2xl font-black text-white"><?= number_format($totalTransactions ?? 0) ?></h3>
    </div>

    <!-- Direct User -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-3 md:p-6 hover:border-teal-500/20 transition group">
        <div class="flex items-center gap-2 md:gap-3 mb-2">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-teal-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-paper-plane text-teal-400 text-xs md:text-sm"></i>
            </div>
            <span class="text-gray-400 text-[8px] md:text-xs uppercase font-black tracking-widest leading-tight">Direct<br class="md:hidden"> User</span>
        </div>
        <h3 class="text-xl md:text-2xl font-black text-white"><?= number_format($totalDirectCount ?? 0) ?></h3>
    </div>

    <!-- PRO -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-3 md:p-6 hover:border-yellow-500/20 transition group">
        <div class="flex items-center gap-2 md:gap-3 mb-2">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-yellow-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-crown text-yellow-400 text-xs md:text-sm"></i>
            </div>
            <span class="text-gray-400 text-[8px] md:text-xs uppercase font-black tracking-widest leading-tight">Member<br class="md:hidden"> PRO</span>
        </div>
        <h3 class="text-xl md:text-2xl font-black text-white"><?= number_format($totalProCount ?? 0) ?></h3>
    </div>

    <!-- User -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-3 md:p-6 hover:border-accent/20 transition group">
        <div class="flex items-center gap-2 md:gap-3 mb-2">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-accent/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-user text-accent text-xs md:text-sm"></i>
            </div>
            <span class="text-gray-400 text-[8px] md:text-xs uppercase font-black tracking-widest leading-tight">Member<br class="md:hidden"> Basic</span>
        </div>
        <h3 class="text-xl md:text-2xl font-black text-white"><?= number_format($totalStandardUserCount ?? 0) ?></h3>
    </div>
</div>

<!-- Referral Code Info -->
<?php if ($referralCode): ?>
    <div class="bg-accent/10 border border-accent/30 rounded-2xl p-4 md:p-6 mb-8 relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:rotate-12 transition-transform">
            <i class="fas fa-link text-6xl"></i>
        </div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-accent/20 flex items-center justify-center text-accent">
                    <i class="fas fa-share-nodes text-lg"></i>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest">Kode Referral Anda</p>
                    <p class="text-xl font-black text-white tracking-tighter"><?= esc($referralCode) ?></p>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 flex-grow max-w-xl">
                <input type="text" id="refLink" value="<?= base_url('register?ref=' . $referralCode) ?>" class="bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-xs w-full font-mono text-gray-400" readonly>
                <button onclick="copyRefLink()" class="px-6 py-3 bg-accent text-black font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-white transition whitespace-nowrap active:scale-95">
                    <i class="fas fa-copy mr-2"></i> Salin Link
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Filter Tabs & Search -->
<div class="flex flex-col md:flex-row gap-4 mb-8">
    <div class="grid grid-cols-3 sm:flex gap-2">
        <a href="<?= base_url('cwpa/dashboard/user?tab=all') ?>" class="px-4 py-3 text-center sm:text-left rounded-xl text-[10px] uppercase font-black tracking-widest transition <?= $tab === 'all' ? 'bg-accent text-black' : 'bg-[#111] border border-white/10 text-gray-400 hover:bg-white/5' ?>">
            Semua
        </a>
        <a href="<?= base_url('cwpa/dashboard/user?tab=referral') ?>" class="px-4 py-3 text-center sm:text-left rounded-xl text-[10px] uppercase font-black tracking-widest transition <?= $tab === 'referral' ? 'bg-accent text-black' : 'bg-[#111] border border-white/10 text-gray-400 hover:bg-white/5' ?>">
            Referral
        </a>
        <a href="<?= base_url('cwpa/dashboard/user?tab=buyer') ?>" class="px-4 py-3 text-center sm:text-left rounded-xl text-[10px] uppercase font-black tracking-widest transition <?= $tab === 'buyer' ? 'bg-accent text-black' : 'bg-[#111] border border-white/10 text-gray-400 hover:bg-white/5' ?>">
            Pembeli
        </a>
    </div>
    <form action="<?= base_url('cwpa/dashboard/user') ?>" method="get" class="flex-grow md:max-w-md">
        <input type="hidden" name="tab" value="<?= esc($tab) ?>">
        <div class="relative">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
            <input type="text" name="search" value="<?= esc($search ?? '') ?>" placeholder="Cari nama atau email..."
                class="w-full bg-[#111] border border-white/10 rounded-xl px-4 py-3 pl-12 text-xs text-white focus:border-accent outline-none transition">
        </div>
    </form>
</div>

<!-- Mobile User Cards (Visible on Mobile) -->
<div class="block md:hidden space-y-4">
    <?php if (empty($users)): ?>
        <div class="bg-[#111] border border-white/10 rounded-2xl p-12 text-center text-gray-500">
            <i class="fas fa-users-slash text-4xl mb-4"></i>
            <p class="font-bold">Belum ada user</p>
        </div>
    <?php else: ?>
        <?php foreach ($users as $user): ?>
            <div class="bg-[#111] border border-white/10 rounded-2xl p-5 relative overflow-hidden">
                <div class="flex items-center gap-4 mb-4">
                    <?php
                    $avatarUrl = $user['avatar'] ?? null;
                    if ($avatarUrl && strpos($avatarUrl, 'http') !== 0) {
                        $avatarUrl = base_url('file/' . $avatarUrl);
                    }
                    ?>
                    <div class="relative">
                        <?php if ($avatarUrl): ?>
                            <img src="<?= $avatarUrl ?>" alt="" class="w-14 h-14 rounded-2xl object-cover border border-white/10 shadow-lg">
                        <?php else: ?>
                            <div class="w-14 h-14 bg-accent/20 rounded-2xl flex items-center justify-center border border-white/5">
                                <i class="fas fa-user text-accent text-xl"></i>
                            </div>
                        <?php endif; ?>
                        <?php if ($user['is_pro'] ?? false): ?>
                            <div class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-500 rounded-lg flex items-center justify-center text-black text-[10px] shadow-lg border-2 border-[#111]">
                                <i class="fas fa-crown"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h4 class="font-black text-white text-base leading-none mb-1"><?= esc($user['name']) ?></h4>
                        <div class="flex flex-wrap gap-1 mt-2">
                             <?php if ($user['is_referral'] ?? false): ?>
                                <span class="px-2 py-0.5 bg-blue-500/10 text-blue-400 rounded-lg text-[8px] font-black uppercase tracking-widest border border-blue-500/10">Referral</span>
                            <?php endif; ?>
                            <?php if (($user['total_purchases'] ?? 0) > 0): ?>
                                <span class="px-2 py-0.5 bg-purple-500/10 text-purple-400 rounded-lg text-[8px] font-black uppercase tracking-widest border border-purple-500/10">Pembeli</span>
                            <?php endif; ?>
                            <?php if (!($user['is_pro'] ?? false)): ?>
                                <span class="px-2 py-0.5 bg-gray-500/10 text-gray-500 rounded-lg text-[8px] font-black uppercase tracking-widest border border-gray-500/10">Basic</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2 mt-4 pt-4 border-t border-white/5">
                    <div class="bg-black/30 p-2.5 rounded-xl border border-white/5">
                        <p class="text-[8px] text-gray-500 uppercase font-black tracking-widest mb-1">Transaksi</p>
                        <p class="text-xs font-black text-white"><?= $user['total_purchases'] ?? 0 ?> Item</p>
                    </div>
                    <div class="bg-black/30 p-2.5 rounded-xl border border-white/5">
                        <p class="text-[8px] text-gray-500 uppercase font-black tracking-widest mb-1">Total Belanja</p>
                        <p class="text-xs font-black text-accent">Rp <?= number_format($user['total_spent'] ?? 0, 0, ',', '.') ?></p>
                    </div>
                </div>
                <div class="mt-3 text-right">
                    <p class="text-[9px] text-gray-600 font-bold uppercase italic tracking-widest">Member Sejak: <?= date('d M Y', strtotime($user['created_at'])) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Desktop Table View (Visible on Medium screens and Above) -->
<div class="hidden md:block bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-black/50 border-b border-white/10">
                <tr>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-500 uppercase tracking-widest">Nama User</th>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-500 uppercase tracking-widest">Status</th>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-500 uppercase tracking-widest">Label</th>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-500 uppercase tracking-widest">Pembelian</th>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-500 uppercase tracking-widest">Total Spent</th>
                    <th class="px-6 py-5 text-[10px] font-black text-gray-500 uppercase tracking-widest text-right">Sejak</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                             <i class="fas fa-users-slash text-4xl mb-4"></i>
                             <p class="font-bold">Belum ada user</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-white/[0.02] transition">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <?php
                                    $avatarUrl = $user['avatar'] ?? null;
                                    if ($avatarUrl && strpos($avatarUrl, 'http') !== 0) {
                                        $avatarUrl = base_url('file/' . $avatarUrl);
                                    }
                                    ?>
                                    <div class="w-10 h-10 rounded-xl overflow-hidden border border-white/5 flex-shrink-0">
                                        <?php if ($avatarUrl): ?>
                                            <img src="<?= $avatarUrl ?>" alt="" class="w-full h-full object-cover">
                                        <?php else: ?>
                                            <div class="w-full h-full bg-accent/20 flex items-center justify-center">
                                                <i class="fas fa-user text-accent text-sm"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <h4 class="font-black text-sm text-white"><?= esc($user['name']) ?></h4>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <?php if ($user['is_pro'] ?? false): ?>
                                    <span class="px-2.5 py-1 bg-yellow-500/10 text-yellow-400 rounded-lg text-[9px] font-black uppercase tracking-widest border border-yellow-500/10"><i class="fas fa-crown mr-1"></i>PRO</span>
                                <?php else: ?>
                                    <span class="px-2.5 py-1 bg-gray-500/10 text-gray-500 rounded-lg text-[9px] font-black uppercase tracking-widest border border-gray-500/10">Basic</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-wrap gap-2">
                                    <?php if ($user['is_referral'] ?? false): ?>
                                        <span class="px-2.5 py-1 bg-blue-500/10 text-blue-400 rounded-lg text-[9px] font-black uppercase tracking-widest border border-blue-500/10">Referral</span>
                                    <?php endif; ?>
                                    <?php if (($user['total_purchases'] ?? 0) > 0): ?>
                                        <span class="px-2.5 py-1 bg-purple-500/10 text-purple-400 rounded-lg text-[9px] font-black uppercase tracking-widest border border-purple-500/10">Pembeli</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <span class="text-sm font-black text-white"><?= $user['total_purchases'] ?? 0 ?> <span class="text-[10px] text-gray-600 ml-1 uppercase">Items</span></span>
                            </td>
                            <td class="px-6 py-5">
                                <span class="text-sm font-black text-accent font-mono">Rp <?= number_format($user['total_spent'] ?? 0, 0, ',', '.') ?></span>
                            </td>
                            <td class="px-6 py-5 text-right text-gray-500 font-bold text-[11px] italic">
                                <?= date('d M Y', strtotime($user['created_at'])) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($pager && $pager->getPageCount() > 1): ?>
    <div class="mt-8 flex justify-center">
        <?= $pager->links('default', 'admin_pagination') ?>
    </div>
<?php endif; ?>


<script>
    function copyRefLink() {
        const input = document.getElementById('refLink');
        input.select();
        document.execCommand('copy');

        // Show toast
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-20 md:bottom-8 left-1/2 -translate-x-1/2 bg-accent text-black px-4 py-2 rounded-lg font-medium text-sm z-50';
        toast.textContent = 'Link referral berhasil disalin!';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2000);
    }
</script>
<?= $this->endSection() ?>
