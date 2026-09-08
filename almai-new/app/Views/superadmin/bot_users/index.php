<?php
$pageTitle = 'Bot Users';
$pageSubtitle = 'Monitor users using the bot';
?>
<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
    <div class="bg-[#111] p-4 rounded-xl border border-white/10">
        <p class="text-xs text-gray-400 mb-1">Total Users</p>
        <p class="text-2xl font-bold text-white"><?= $stats['total_users'] ?? 0 ?></p>
    </div>
    <div class="bg-[#111] p-4 rounded-xl border border-white/10">
        <p class="text-xs text-gray-400 mb-1">Active Bots</p>
        <p class="text-2xl font-bold text-green-400"><?= $stats['total_active'] ?? 0 ?></p>
    </div>
    <div class="bg-[#111] p-4 rounded-xl border border-white/10">
        <p class="text-xs text-gray-400 mb-1">Inactive Bots</p>
        <p class="text-2xl font-bold text-red-400"><?= $stats['total_inactive'] ?? 0 ?></p>
    </div>
    <div class="bg-[#111] p-4 rounded-xl border border-white/10">
        <p class="text-xs text-gray-400 mb-1">Live Bots</p>
        <p class="text-2xl font-bold text-yellow-400"><?= $stats['total_live'] ?? 0 ?></p>
    </div>
    <div class="bg-[#111] p-4 rounded-xl border border-white/10">
        <p class="text-xs text-gray-400 mb-1">Demo Bots</p>
        <p class="text-2xl font-bold text-blue-400"><?= $stats['total_demo'] ?? 0 ?></p>
    </div>
</div>

<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden mb-6 p-4">
    <form method="get" action="" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
            <input type="text" name="search" value="<?= esc($filterSearch ?? '') ?>" placeholder="Cari user, bot, atau symbol..." class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2 text-sm text-white focus:outline-none focus:border-accent">
        </div>
        <div class="w-full md:w-48">
            <select name="status" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2 text-sm text-white focus:outline-none focus:border-accent">
                <option value="all" <?= ($filterStatus ?? 'all') === 'all' ? 'selected' : '' ?>>Semua Status</option>
                <option value="active" <?= ($filterStatus ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="stopped" <?= ($filterStatus ?? '') === 'stopped' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <div class="w-full md:w-48">
            <select name="mode" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2 text-sm text-white focus:outline-none focus:border-accent">
                <option value="all" <?= ($filterMode ?? 'all') === 'all' ? 'selected' : '' ?>>Semua Mode</option>
                <option value="live" <?= ($filterMode ?? '') === 'live' ? 'selected' : '' ?>>LIVE</option>
                <option value="demo" <?= ($filterMode ?? '') === 'demo' ? 'selected' : '' ?>>DEMO</option>
            </select>
        </div>
        <button type="submit" class="bg-accent text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-accent/80 transition-colors">Filter</button>
        <a href="<?= base_url('superadmin/bot-users/export-pdf?' . http_build_query($_GET)) ?>" class="bg-red-500 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-red-600 transition-colors text-center" target="_blank"><i class="fas fa-file-pdf mr-2"></i>Export PDF</a>
        <?php if(!empty($filterSearch) || !empty($filterStatus) || !empty($filterMode)): ?>
            <a href="<?= base_url('superadmin/bot-users') ?>" class="bg-white/10 text-white px-6 py-2 rounded-lg text-sm font-semibold hover:bg-white/20 transition-colors text-center">Reset</a>
        <?php endif; ?>
    </form>
</div>

<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-black/50">
                <tr>
                    <th class="text-center px-3 py-3 text-xs font-medium text-gray-400 w-12">No</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">User Name</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400 w-32">Total Profit</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Bots & Strategies</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($groupedUsers)): ?>
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-robot text-4xl mb-4"></i>
                            <p>Tidak ada data pengguna bot</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($groupedUsers as $index => $group): ?>
                        <tr class="border-t border-white/5 hover:bg-white/5">
                            <td class="px-3 py-4 text-center text-gray-500 text-sm align-top"><?= $index + 1 ?></td>
                            <td class="px-4 py-4 text-sm font-medium align-top"><?= esc($group['user_name'] ?? '-') ?></td>
                            <td class="px-4 py-4 text-sm font-bold align-top <?= ($group['total_profit_all'] >= 0) ? 'text-green-400' : 'text-red-400' ?>">
                                Rp <?= number_format($group['total_profit_all'] ?? 0, 0, ',', '.') ?>
                            </td>
                            <td class="px-4 py-4 text-sm align-top">
                                <div class="flex flex-col gap-2">
                                    <?php foreach ($group['bots'] as $bot): ?>
                                        <?php 
                                            $config = json_decode($bot->config ?? '{}', true);
                                            $isLive = !empty($config['liveMode']);
                                        ?>
                                        <div class="bg-white/5 rounded-lg p-3 border border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                            <div>
                                                <div class="font-semibold text-gray-200"><?= esc($bot->bot_name) ?> <span class="text-xs text-gray-500 font-normal ml-1">(<?= esc($bot->symbol) ?>)</span></div>
                                                <div class="text-xs text-gray-400 mt-1"><?= esc($bot->strategy) ?></div>
                                                <?php if ($bot->exchange): ?>
                                                    <div class="text-xs text-gray-500 mt-1"><i class="fas fa-exchange-alt mr-1"></i> <?= esc($bot->exchange) ?> <?= $bot->account_name ? ' - ' . esc($bot->account_name) : '' ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex items-center gap-2 shrink-0">
                                                <div class="text-sm font-bold mr-2 <?= ($bot->total_profit >= 0) ? 'text-green-400' : 'text-red-400' ?>">
                                                    Rp <?= number_format($bot->total_profit ?? 0, 0, ',', '.') ?>
                                                </div>
                                                <?php if ($isLive): ?>
                                                    <span class="px-2 py-1 bg-yellow-500/20 text-yellow-400 rounded text-xs font-bold">LIVE</span>
                                                <?php else: ?>
                                                    <span class="px-2 py-1 bg-blue-500/20 text-blue-400 rounded text-xs font-bold">DEMO</span>
                                                <?php endif; ?>
                                                <?php if ($bot->status === 'active'): ?>
                                                    <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded-full text-xs">Active</span>
                                                <?php else: ?>
                                                    <span class="px-2 py-1 bg-red-500/20 text-red-400 rounded-full text-xs">Inactive</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
