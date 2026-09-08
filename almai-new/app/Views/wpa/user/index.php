<?= $this->extend('wpa/layouts/main') ?>

<?= $this->section('content') ?>
<?php $pageTitle = 'User';
$pageSubtitle = 'User referral dan pembeli kelas Anda'; ?>

<!-- Stats Cards -->
<!-- Stats Cards -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
    <!-- Total User -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-4 md:p-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-white"></i>
            </div>
            <span class="text-gray-400 text-sm">Total User</span>
        </div>
        <h3 class="text-2xl font-bold"><?= number_format($totalUserCount ?? 0) ?></h3>
    </div>

    <!-- Total Pembelian -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-4 md:p-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-shopping-bag text-purple-400"></i>
            </div>
            <span class="text-gray-400 text-sm">Total Pembelian</span>
        </div>
        <h3 class="text-2xl font-bold"><?= number_format($totalTransactions ?? 0) ?></h3>
    </div>

    <!-- Direct -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-4 md:p-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-teal-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-paper-plane text-teal-400"></i>
            </div>
            <span class="text-gray-400 text-sm">Direct</span>
        </div>
        <h3 class="text-2xl font-bold"><?= number_format($totalDirectCount ?? 0) ?></h3>
    </div>

    <!-- CWPA -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-4 md:p-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-user-graduate text-green-400"></i>
            </div>
            <span class="text-gray-400 text-sm">CWPA</span>
        </div>
        <h3 class="text-2xl font-bold"><?= number_format($totalCwpaCount ?? 0) ?></h3>
    </div>

    <!-- PRO -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-4 md:p-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-crown text-yellow-400"></i>
            </div>
            <span class="text-gray-400 text-sm">PRO</span>
        </div>
        <h3 class="text-2xl font-bold"><?= number_format($totalProCount ?? 0) ?></h3>
    </div>

    <!-- User -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-4 md:p-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-accent/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-user text-accent"></i>
            </div>
            <span class="text-gray-400 text-sm">User</span>
        </div>
        <h3 class="text-2xl font-bold"><?= number_format($totalStandardUserCount ?? 0) ?></h3>
    </div>
</div>

<!-- Referral Code Info -->
<?php if ($referralCode): ?>
    <div class="bg-accent/10 border border-accent/30 rounded-xl p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <i class="fas fa-link text-accent"></i>
                <div>
                    <p class="text-sm text-gray-400">Kode Referral Anda</p>
                    <p class="font-bold text-accent"><?= esc($referralCode) ?></p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <input type="text" id="refLink" value="<?= base_url('register?ref=' . $referralCode) ?>" class="bg-black/50 border border-white/20 rounded-lg px-3 py-2 text-sm w-full md:w-auto" readonly>
                <button onclick="copyRefLink()" class="px-4 py-2 bg-accent text-black font-bold rounded-lg hover:bg-white transition text-sm">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Filter Tabs -->
<div class="flex flex-col md:flex-row gap-4 mb-6">
    <div class="flex gap-2">
        <a href="<?= base_url('wpa/dashboard/user?tab=all') ?>" class="px-4 py-2 rounded-lg text-sm font-medium transition <?= $tab === 'all' ? 'bg-accent text-black' : 'bg-white/10 hover:bg-white/20' ?>">
            Semua
        </a>
        <a href="<?= base_url('wpa/dashboard/user?tab=referral') ?>" class="px-4 py-2 rounded-lg text-sm font-medium transition <?= $tab === 'referral' ? 'bg-accent text-black' : 'bg-white/10 hover:bg-white/20' ?>">
            <i class="fas fa-user-plus mr-1"></i> Referral
        </a>
        <a href="<?= base_url('wpa/dashboard/user?tab=buyer') ?>" class="px-4 py-2 rounded-lg text-sm font-medium transition <?= $tab === 'buyer' ? 'bg-accent text-black' : 'bg-white/10 hover:bg-white/20' ?>">
            <i class="fas fa-shopping-cart mr-1"></i> Pembeli
        </a>
    </div>
    <form action="<?= base_url('wpa/dashboard/user') ?>" method="get" class="flex-1 md:max-w-xs">
        <input type="hidden" name="tab" value="<?= esc($tab) ?>">
        <div class="relative">
            <input type="text" name="search" value="<?= esc($search ?? '') ?>" placeholder="Cari nama..."
                class="w-full bg-black border border-white/20 rounded-lg px-4 py-2 pl-10 text-sm focus:border-accent focus:outline-none">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
        </div>
    </form>
</div>

<!-- User Table -->
<div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl overflow-hidden">
    <div class="table-responsive">
        <table class="w-full min-w-[700px]">
            <thead class="bg-black/50">
                <tr>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400">User</th>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400">Status</th>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400">Tipe</th>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400">Pembelian</th>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400">Total Spent</th>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400">Bergabung</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i class="fas fa-users text-4xl mb-4"></i>
                                <p>Belum ada user</p>
                                <?php if ($search): ?>
                                    <p class="text-sm mt-2">Tidak ditemukan hasil untuk "<?= esc($search) ?>"</p>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr class="border-t border-white/10 hover:bg-white/5 transition">
                            <td class="px-4 md:px-6 py-3 md:py-4">
                                <div class="flex items-center gap-3">
                                    <?php
                                    $avatarUrl = $user['avatar'] ?? null;
                                    if ($avatarUrl && strpos($avatarUrl, 'http') !== 0) {
                                        $avatarUrl = base_url('file/' . $avatarUrl);
                                    }
                                    ?>
                                    <?php if ($avatarUrl): ?>
                                        <img src="<?= $avatarUrl ?>" alt="" class="w-10 h-10 rounded-full object-cover">
                                    <?php else: ?>
                                        <div class="w-10 h-10 bg-accent/20 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-accent text-sm"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <p class="font-medium text-sm"><?= esc($user['name']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 md:px-6 py-3 md:py-4">
                                <?php if ($user['is_pro'] ?? false): ?>
                                    <span class="px-2 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-xs"><i class="fas fa-crown mr-1"></i>PRO</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 bg-gray-500/20 text-gray-400 rounded-full text-xs">Basic</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 md:px-6 py-3 md:py-4">
                                <div class="flex flex-wrap gap-1">
                                    <?php if ($user['is_referral'] ?? false): ?>
                                        <span class="px-2 py-1 bg-blue-500/20 text-blue-400 rounded-full text-xs"><i class="fas fa-user-plus mr-1"></i>Referral</span>
                                    <?php endif; ?>
                                    <?php if (($user['total_purchases'] ?? 0) > 0): ?>
                                        <span class="px-2 py-1 bg-purple-500/20 text-purple-400 rounded-full text-xs"><i class="fas fa-shopping-cart mr-1"></i>Pembeli</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-4 md:px-6 py-3 md:py-4">
                                <span class="text-sm"><?= $user['total_purchases'] ?? 0 ?> layanan</span>
                            </td>
                            <td class="px-4 md:px-6 py-3 md:py-4">
                                <span class="text-accent font-medium">Rp <?= number_format($user['total_spent'] ?? 0, 0, ',', '.') ?></span>
                            </td>
                            <td class="px-4 md:px-6 py-3 md:py-4 text-gray-400 text-sm">
                                <?= date('d M Y', strtotime($user['created_at'])) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($pager && $pager->getPageCount() > 1): ?>
        <div class="px-6 py-4 border-t border-white/10 flex justify-center gap-2">
            <?= $pager->links('default', 'admin_pagination') ?>
        </div>
    <?php endif; ?>
</div>

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