<?= $this->extend('admin_wpa/layouts/main') ?>

<?= $this->section('content') ?>


<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-[#111] border border-white/10 p-6 rounded-2xl relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition">
            <i class="fas fa-users text-4xl"></i>
        </div>
        <p class="text-gray-400 text-sm mb-1 uppercase tracking-wider font-semibold">Total User</p>
        <h3 class="text-4xl font-bold text-white"><?= number_format($overviewStats['total'] ?? 0) ?></h3>
        <p class="text-xs text-gray-500 mt-2">Network up to 10 levels</p>
    </div>
    
    <div class="bg-[#111] border border-white/10 p-6 rounded-2xl relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition text-green-500">
            <i class="fas fa-user-check text-4xl"></i>
        </div>
        <p class="text-gray-400 text-sm mb-1 uppercase tracking-wider font-semibold">Active User</p>
        <h3 class="text-4xl font-bold text-green-500"><?= number_format($overviewStats['active'] ?? 0) ?></h3>
        <p class="text-xs text-gray-500 mt-2">With confirmed transactions</p>
    </div>

    <div class="bg-[#111] border border-white/10 p-6 rounded-2xl relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition text-accent">
            <i class="fas fa-user-plus text-4xl"></i>
        </div>
        <p class="text-gray-400 text-sm mb-1 uppercase tracking-wider font-semibold">New User (Month)</p>
        <h3 class="text-4xl font-bold text-accent"><?= number_format($overviewStats['new'] ?? 0) ?></h3>
        <p class="text-xs text-gray-500 mt-2">Joined since <?= date('01 M Y') ?></p>
    </div>
</div>

<!-- Stats per WPA -->
<?php if (count($statsPerWpa) > 0): ?>
<div class="mb-4 flex items-center justify-between">
    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest">Detail Per WPA</h3>
</div>
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-8">
    <?php foreach ($statsPerWpa as $wpaStat): ?>
        <div class="bg-[#111] border border-white/10 p-5 rounded-2xl hover:border-accent/30 transition group">
            <div class="flex justify-between items-start mb-3">
                <div class="w-10 h-10 rounded-xl bg-accent/10 flex items-center justify-center text-accent group-hover:bg-accent group-hover:text-black transition">
                    <i class="fas fa-briefcase text-sm"></i>
                </div>
                <span class="text-[10px] bg-green-500/10 text-green-500 px-2 py-1 rounded-full font-bold uppercase tracking-tighter"><?= $wpaStat['active'] ?> Active</span>
            </div>
            <p class="text-white font-bold text-sm mb-1 truncate" title="<?= esc($wpaStat['wpa_name']) ?>"><?= esc($wpaStat['wpa_name']) ?></p>
            <div class="flex items-baseline gap-1">
                <span class="text-2xl font-black text-white"><?= number_format($wpaStat['total']) ?></span>
                <span class="text-[10px] text-gray-500 font-bold uppercase">Users</span>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Filters -->
<div class="flex flex-col lg:flex-row justify-between items-center gap-4 mb-6">
    <form action="<?= base_url('admin-wpa/users') ?>" method="get" class="flex flex-row flex-wrap items-center gap-2 w-full lg:w-auto flex-1 text-sm">
        <div class="flex gap-2 w-full sm:w-auto sm:flex-1 lg:flex-none">
            <input type="text" name="search" value="<?= esc($currentSearch ?? '') ?>" placeholder="Cari nama/email..."
                class="flex-1 min-w-[200px] px-4 py-2 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm">
        </div>
        
        <div class="flex gap-2 w-full sm:w-auto">
            <select name="wpa" class="w-full sm:w-auto px-4 py-2 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm">
                <option value="">Semua WPA</option>
                <?php foreach ($WPAs as $wpa): ?>
                    <option value="<?= $wpa['id'] ?>" <?= ($wpaFilter == $wpa['id']) ? 'selected' : '' ?>>
                        <?= esc($wpa['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="px-6 py-2 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center gap-2">
                <i class="fas fa-filter"></i>
                <span class="hidden sm:inline">Filter</span>
            </button>
            
            <?php if (($currentSearch ?? '') || ($wpaFilter ?? '')): ?>
                <a href="<?= base_url('admin-wpa/users') ?>" class="px-4 py-2 bg-white/10 text-white font-medium rounded-xl hover:bg-white/20 transition flex items-center gap-2">
                    <i class="fas fa-times text-red-500"></i>
                    <span class="hidden sm:inline text-xs">Clear</span>
                </a>
            <?php endif; ?>
        </div>
    </form>

    <div class="flex items-center gap-2 w-full lg:w-auto">
        <a href="<?= base_url('admin-wpa/users/export') ?>" class="flex-1 lg:flex-none px-4 py-2 bg-green-500/20 text-green-400 font-bold rounded-xl hover:bg-green-500 hover:text-white transition flex items-center justify-center gap-2 text-sm">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
        <a href="<?= base_url('admin-wpa/users/create') ?>" class="flex-1 lg:flex-none px-4 py-2 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center justify-center gap-2 text-sm">
            <i class="fas fa-plus"></i> Tambah User
        </a>
    </div>
</div>

<!-- Users Table -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-black/50">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">User</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Email</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Tanggal Daftar</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Daftar Lewat</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Phone</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Status</th>
                    <th class="text-center px-4 py-3 text-xs font-medium text-gray-400">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($usersList)): ?>
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-users text-4xl mb-4"></i>
                            <p>Tidak ada user ditemukan</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($usersList as $user): ?>
                        <tr class="border-t border-white/5 hover:bg-white/5">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-accent/20 flex items-center justify-center text-accent font-bold">
                                        <?= strtoupper(substr($user['name'], 0, 2)) ?>
                                    </div>
                                    <div>
                                        <p class="font-medium text-sm"><?= esc($user['name']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-400 text-sm"><?= esc($user['email']) ?></td>
                            <td class="px-4 py-3 text-gray-400 text-sm">
                                <?= date('d M Y', strtotime($user['created_at'])) ?>
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <?php if ($user['upline_name']): ?>
                                    <span class="text-white font-medium"><?= esc($user['upline_name']) ?></span>
                                    <p class="text-[10px] text-gray-500 font-mono"><?= esc($user['affiliator_code']) ?></p>
                                <?php else: ?>
                                    <span class="text-gray-600 italic text-xs">Direct/NA</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-gray-400 text-sm"><?= esc($user['phone'] ?? '-') ?></td>
                            <td class="px-4 py-3">
                                <?php if (($user['status'] ?? 'active') === 'active'): ?>
                                    <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded-full text-xs">Active</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 bg-red-500/20 text-red-400 rounded-full text-xs">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center">
                                    <a href="<?= base_url('admin-wpa/users/view/' . $user['id']) ?>" class="w-8 h-8 flex items-center justify-center bg-accent/20 text-accent rounded-lg hover:bg-accent hover:text-black transition">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($pager): ?>
        <div class="px-4 py-4 border-t border-white/5 flex justify-center">
            <?= $pager->links('default', 'admin_pagination') ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
