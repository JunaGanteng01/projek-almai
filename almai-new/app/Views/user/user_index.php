<?= $this->extend('user/partials/layout') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <h1 class="text-2xl font-bold text-white mb-1"><?= esc($pageTitle) ?></h1>
    <p class="text-gray-400 text-sm"><?= esc($pageSubtitle) ?></p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
    <!-- Total User -->
    <div class="bg-[#121212] border border-white/5 rounded-xl p-4 md:p-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-white"></i>
            </div>
            <span class="text-gray-400 text-sm">Total User</span>
        </div>
        <h3 class="text-2xl font-bold text-white"><?= number_format($totalUserCount ?? 0) ?></h3>
    </div>

    <!-- Total Pembelian -->
    <div class="bg-[#121212] border border-white/5 rounded-xl p-4 md:p-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-shopping-bag text-purple-400"></i>
            </div>
            <span class="text-gray-400 text-sm">Total Pembelian</span>
        </div>
        <!-- Count of transactions, not sum -->
        <h3 class="text-2xl font-bold text-white"><?= number_format($totalTransactions ?? 0) ?></h3>
    </div>

    <!-- Direct User -->
    <div class="bg-[#121212] border border-white/5 rounded-xl p-4 md:p-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-teal-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-paper-plane text-teal-400"></i>
            </div>
            <span class="text-gray-400 text-sm">Direct User</span>
        </div>
        <h3 class="text-2xl font-bold text-white"><?= number_format($totalDirectCount ?? 0) ?></h3>
    </div>

    <!-- User -->
    <div class="bg-[#121212] border border-white/5 rounded-xl p-4 md:p-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-accent/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-user text-accent"></i>
            </div>
            <span class="text-gray-400 text-sm">User</span>
        </div>
        <h3 class="text-2xl font-bold text-white"><?= number_format($totalStandardUserCount ?? 0) ?></h3>
    </div>

    <!-- CWPA -->
    <?php if (($currentUser['level_id'] ?? 0) == \App\Models\LevelModel::LEVEL_CWPA): ?>
        <div class="bg-[#121212] border border-white/5 rounded-xl p-4 md:p-6">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-graduate text-green-400"></i>
                </div>
                <span class="text-gray-400 text-sm">CWPA</span>
            </div>
            <h3 class="text-2xl font-bold text-white"><?= number_format($totalCwpaCount ?? 0) ?></h3>
        </div>
    <?php endif; ?>

    <!-- PRO -->
    <?php if (($currentUser['level_id'] ?? 0) >= \App\Models\LevelModel::LEVEL_PRO): ?>
        <div class="bg-[#121212] border border-white/5 rounded-xl p-4 md:p-6">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-crown text-yellow-400"></i>
                </div>
                <span class="text-gray-400 text-sm">Pro User</span>
            </div>
            <h3 class="text-2xl font-bold text-white"><?= number_format($totalProCount ?? 0) ?></h3>
        </div>
    <?php endif; ?>
</div>

<!-- Referral Code Info -->
<?php if ($referralCode): ?>
    <div class="bg-accent/10 border border-accent/20 rounded-xl p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <i class="fas fa-link text-accent"></i>
                <div>
                    <p class="text-sm text-gray-400">Kode Referral Anda</p>
                    <p class="font-bold text-accent text-lg"><?= esc($referralCode) ?></p>
                </div>
            </div>
            <div class="flex items-center gap-2 w-full md:w-auto">
                <input type="text" id="refLink" value="<?= base_url('register?ref=' . $referralCode) ?>" class="bg-black/50 border border-white/10 rounded-lg px-3 py-2 text-sm w-full md:w-80 text-gray-300 focus:outline-none focus:border-accent" readonly>
                <button onclick="copyRefLink()" class="px-4 py-2 bg-accent text-black font-bold rounded-lg hover:bg-white transition text-sm flex-shrink-0">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Search & Filter -->
<div class="flex flex-col md:flex-row gap-4 mb-6">
    <div class="flex-1"></div> <!-- Spacer -->
    <form action="<?= base_url('user/user') ?>" method="get" class="flex-1 md:max-w-xs">
        <div class="relative">
            <input type="text" name="search" value="<?= esc($search ?? '') ?>" placeholder="Cari nama atau email..."
                class="w-full bg-[#121212] border border-white/10 rounded-lg px-4 py-2.5 pl-10 text-sm focus:border-accent focus:outline-none text-white placeholder-gray-600">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
        </div>
    </form>
</div>

<!-- User Table -->
<div class="bg-[#121212] border border-white/5 rounded-xl md:rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[700px]">
            <thead class="bg-white/5">
                <tr>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">User</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Level</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Pembelian</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Spent</th>
                    <th class="text-left px-6 py-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">Bergabung</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-users text-2xl opacity-50"></i>
                                </div>
                                <p class="font-medium">Belum ada user</p>
                                <?php if ($search): ?>
                                    <p class="text-sm mt-1">Tidak ditemukan hasil untuk "<?= esc($search) ?>"</p>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-white/5 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <?php
                                    $avatarUrl = $user['avatar'] ?? null;
                                    if ($avatarUrl && strpos($avatarUrl, 'http') !== 0) {
                                        $avatarUrl = base_url('file/' . $avatarUrl);
                                    }
                                    ?>
                                    <?php if ($avatarUrl): ?>
                                        <img src="<?= $avatarUrl ?>" alt="" class="w-10 h-10 rounded-full object-cover border border-white/10">
                                    <?php else: ?>
                                        <div class="w-10 h-10 bg-accent/10 border border-accent/20 rounded-full flex items-center justify-center text-accent">
                                            <i class="fas fa-user text-sm"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <p class="font-medium text-white text-sm"><?= esc($user['name']) ?></p>
                                        <p class="text-xs text-gray-500"><?= esc($user['email']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <?php if (($user['status'] ?? 'active') === 'active' || ($user['status'] ?? 0) == 1): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-500/10 text-green-400">
                                        Active
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-500/10 text-red-400">
                                        Inactive
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if (($user['level_id'] ?? 0) == \App\Models\LevelModel::LEVEL_PRO): ?>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-500/10 text-yellow-500 border border-yellow-500/20">
                                        <i class="fas fa-crown text-[10px] mr-1.5"></i> PRO
                                    </span>
                                <?php elseif (($user['level_id'] ?? 0) == \App\Models\LevelModel::LEVEL_CWPA): ?>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-500/10 text-green-500 border border-green-500/20">
                                        <i class="fas fa-user-graduate text-[10px] mr-1.5"></i> CWPA
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-500/10 text-gray-400 border border-gray-500/20">
                                        User
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-300"><?= number_format($user['total_purchases'] ?? 0) ?>x</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-medium text-accent">Rp <?= number_format($user['total_spent'] ?? 0, 0, ',', '.') ?></span>
                            </td>
                            <td class="px-6 py-4 text-gray-400 text-sm">
                                <?= date('d M Y', strtotime($user['created_at'])) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($pager && $pager->getPageCount() > 1): ?>
        <div class="px-6 py-4 border-t border-white/5 flex justify-center">
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
        toast.className = 'fixed bottom-8 left-1/2 -translate-x-1/2 bg-accent text-black px-4 py-2 rounded-lg font-medium text-sm z-50 shadow-lg shadow-accent/20 flex items-center gap-2 animate-bounce-in';
        toast.innerHTML = '<i class="fas fa-check-circle"></i> Link referral berhasil disalin!';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2000);
    }
</script>

<?= $this->endSection() ?>