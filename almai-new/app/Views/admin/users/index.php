<?php
$pageTitle = 'Kelola Users';
$pageSubtitle = 'Manage semua pengguna';
?>
<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<?php
$currentPage = $pager->getCurrentPage();
$perPage = 20;
$startNumber = (($page - 1) * $perPage) + 1;
?>

<!-- Stats -->
<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 md:gap-4 mb-6">
    <!-- Total User -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-white"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= $grandTotal ?? 0 ?></p>
                <p class="text-xs text-gray-500">Total User</p>
            </div>
        </div>
    </div>

    <!-- Total Pembelian -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-shopping-bag text-emerald-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= $totalConfirmedTransactions ?? 0 ?></p>
                <p class="text-xs text-gray-500">Total Pembelian</p>
            </div>
        </div>
    </div>



    <!-- SPI -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-cyan-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-building text-cyan-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= $totalSpi ?? 0 ?></p>
                <p class="text-xs text-gray-500">SPI</p>
            </div>
        </div>
    </div>

    <!-- Keuangan -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-indigo-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-wallet text-indigo-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= $totalKeuangan ?? 0 ?></p>
                <p class="text-xs text-gray-500">Keuangan</p>
            </div>
        </div>
    </div>

    <!-- Kampus -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-orange-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-university text-orange-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= $totalKampus ?? 0 ?></p>
                <p class="text-xs text-gray-500">Kampus</p>
            </div>
        </div>
    </div>

    <!-- WPA -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-user-tie text-blue-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= $totalWpa ?? 0 ?></p>
                <p class="text-xs text-gray-500">WPA</p>
            </div>
        </div>
    </div>

    <!-- CWPA -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-user-graduate text-green-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= $totalCwpa ?? 0 ?></p>
                <p class="text-xs text-gray-500">CWPA</p>
            </div>
        </div>
    </div>

    <!-- PRO -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-crown text-yellow-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= $totalUserPro ?? 0 ?></p>
                <p class="text-xs text-gray-500">PRO</p>
            </div>
        </div>
    </div>

    <!-- User -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-accent/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-user text-accent"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= $totalUser ?? 0 ?></p>
                <p class="text-xs text-gray-500">User</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="flex flex-col lg:flex-row justify-between items-center gap-4 mb-6">
    <form action="<?= base_url('admin/users') ?>" method="get" class="flex flex-row flex-wrap items-center gap-2 w-full lg:w-auto flex-1">
        <select name="role" onchange="this.form.submit()" class="px-4 py-2 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm cursor-pointer min-w-[120px]">
            <option value="all" <?= $currentRole === 'all' || !$currentRole ? 'selected' : '' ?>>Semua Role</option>
            <option value="user" <?= $currentRole === 'user' ? 'selected' : '' ?>>User</option>
            <option value="user_pro" <?= $currentRole === 'user_pro' ? 'selected' : '' ?>>User PRO</option>
            <option value="wpa" <?= $currentRole === 'wpa' ? 'selected' : '' ?>>WPA</option>
            <option value="cwpa" <?= $currentRole === 'cwpa' ? 'selected' : '' ?>>CWPA</option>
            <option value="admin-wpa" <?= $currentRole === 'admin-wpa' ? 'selected' : '' ?>>Admin WPA</option>
            <option value="admin-partnership" <?= $currentRole === 'admin-partnership' ? 'selected' : '' ?>>Admin Partnership</option>
            <option value="admin" <?= $currentRole === 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>

        <select name="affiliator_code" onchange="this.form.submit()" class="px-4 py-2 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm cursor-pointer min-w-[120px]">
            <option value="" <?= empty($currentAffiliator) ? 'selected' : '' ?>>Semua Afiliator</option>
            <option value="none" <?= $currentAffiliator === 'none' ? 'selected' : '' ?>>Tanpa Afiliator (Organik) (<?= $totalDirect ?? 0 ?>)</option>
            <?php if (!empty($affiliatorStats)): ?>
                <?php foreach ($affiliatorStats as $stat): ?>
                    <option value="<?= esc($stat['affiliator_code']) ?>" <?= $currentAffiliator === $stat['affiliator_code'] ? 'selected' : '' ?>>
                        <?= esc($stat['affiliator_code']) ?> (<?= $stat['total_usage'] ?>)
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>

        <select name="time_filter" onchange="this.form.submit()" class="px-4 py-2 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm cursor-pointer min-w-[120px]">
            <option value="" <?= empty($currentTimeFilter) ? 'selected' : '' ?>>Semua Waktu</option>
            <option value="today" <?= $currentTimeFilter === 'today' ? 'selected' : '' ?>>Hari Ini</option>
            <option value="week" <?= $currentTimeFilter === 'week' ? 'selected' : '' ?>>Minggu Ini</option>
            <option value="month" <?= $currentTimeFilter === 'month' ? 'selected' : '' ?>>Bulan Ini</option>
            <option value="year" <?= $currentTimeFilter === 'year' ? 'selected' : '' ?>>Tahun Ini</option>
        </select>

        <select name="sort" onchange="this.form.submit()" class="px-4 py-2 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm cursor-pointer min-w-[120px]">
            <option value="newest" <?= $currentSort === 'newest' ? 'selected' : '' ?>>Terbaru</option>
            <option value="oldest" <?= $currentSort === 'oldest' ? 'selected' : '' ?>>Terlama</option>
        </select>

        <div class="flex gap-2 w-full sm:w-auto sm:flex-1 lg:flex-none">
            <input type="text" name="search" value="<?= esc($currentSearch) ?>" placeholder="Cari nama/email..."
                class="flex-1 min-w-[150px] px-4 py-2 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm">
            <button type="submit" class="px-4 py-2 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>
    <?php if ($canWrite ?? false): ?>
        <div class="flex flex-wrap gap-2 w-full sm:w-auto">

            <a href="<?= base_url('admin/users/create') ?>" class="px-6 py-2 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-center whitespace-nowrap">
                <i class="fas fa-plus mr-2"></i> Tambah User
            </a>
        </div>
    <?php endif; ?>
</div>


<!-- Users Table -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-black/50">
                <tr>

                    <th class="text-center px-3 py-3 text-xs font-medium text-gray-400 w-12">No</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">User</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400 hide-mobile">Email</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400 hide-mobile">Tanggal Daftar</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400 hide-mobile">Daftar Lewat</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400 hide-mobile">Phone</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Role</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Status</th>
                    <th class="text-center px-4 py-3 text-xs font-medium text-gray-400">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($usersList)): ?>
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-users text-4xl mb-4"></i>
                            <p>Tidak ada user ditemukan</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($usersList as $index => $user):
                        $avatarUrl = '';
                        if (!empty($user['avatar'])) {
                            if (strpos($user['avatar'], 'http') === 0) {
                                $avatarUrl = $user['avatar'];
                            } elseif (strpos($user['avatar'], 'uploads/') === 0) {
                                $avatarUrl = base_url($user['avatar']);
                            } else {
                                $avatarUrl = base_url('uploads/avatars/' . $user['avatar']);
                            }
                        }

                        $levelId = (int)($user['level_id'] ?? \App\Models\LevelModel::LEVEL_USER);
                        $roleDisplay = \App\Models\LevelModel::getRoleName($levelId);
                        $roleBgClass = \App\Models\LevelModel::getRoleBadgeClass($levelId);
                        $isUserPro = \App\Models\LevelModel::isProLevelName($levelId);

                        // For deletion check
                        $isAdmin = \App\Models\LevelModel::isAdminLevel($levelId);
                        $canDelete = $user['id'] != session()->get('userId') && !$isAdmin && !$isUserPro;
                    ?>
                        <tr class="border-t border-white/5 hover:bg-white/5">

                            <td class="px-3 py-3 text-center text-gray-500 text-sm"><?= $startNumber + $index ?></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <?php if ($avatarUrl): ?>
                                        <img src="<?= esc($avatarUrl) ?>" alt="" class="w-10 h-10 rounded-full object-cover">
                                    <?php else: ?>
                                        <div class="w-10 h-10 rounded-full bg-accent/20 flex items-center justify-center text-accent font-bold">
                                            <?= strtoupper(substr($user['name'], 0, 2)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <p class="font-medium text-sm"><?= esc($user['name']) ?></p>
                                        <p class="text-xs text-gray-500 md:hidden"><?= esc($user['email']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-400 text-sm hide-mobile"><?= esc($user['email']) ?></td>
                            <td class="px-4 py-3 text-gray-400 text-sm hide-mobile">
                                <?= date('d M Y H:i', strtotime($user['created_at'])) ?>
                            </td>
                            <td class="px-4 py-3 text-sm hide-mobile">
                                <?php
                                $affCode = $user['affiliator_code'] ?? '';
                                $referrerName = $user['referrer_name'] ?? '';
                                ?>
                                <?php if (!empty($affCode)): ?>
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500 mb-1">Via Referral</span>
                                        <span class="px-2 py-1 bg-blue-500/10 text-blue-400 rounded text-xs w-fit font-medium" title="Kode: <?= esc($affCode) ?>">
                                            <?= esc($referrerName ?: $affCode) ?>
                                        </span>
                                    </div>
                                <?php else: ?>
                                    <span class="px-2 py-1 bg-white/5 text-gray-400 rounded text-xs w-fit">Organic/Direct</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-gray-400 text-sm hide-mobile"><?= esc($user['phone'] ?? '-') ?></td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs <?= $roleBgClass ?>">
                                    <?php if ($roleDisplay === 'User PRO'): ?><i class="fas fa-crown mr-1"></i><?php endif; ?>
                                    <?= $roleDisplay ?>
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <?php if (($user['status'] ?? 'active') === 'active' || ($user['status'] ?? 1) == 1): ?>
                                    <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded-full text-xs">Active</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 bg-red-500/20 text-red-400 rounded-full text-xs">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="<?= base_url('admin/users/view/' . $user['id']) ?>" class="w-8 h-8 flex items-center justify-center bg-accent/20 text-accent rounded-lg hover:bg-accent hover:text-black transition" title="Lihat">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    <?php if ($canWrite ?? false): ?>
                                        <a href="<?= base_url('admin/users/edit/' . $user['id']) ?>" class="w-8 h-8 flex items-center justify-center bg-blue-500/20 text-blue-400 rounded-lg hover:bg-blue-500 hover:text-white transition" title="Edit">
                                            <i class="fas fa-edit text-xs"></i>
                                        </a>
                                        <a href="<?= base_url('admin/users/toggle-status/' . $user['id']) ?>" class="w-8 h-8 flex items-center justify-center bg-yellow-500/20 text-yellow-400 rounded-lg hover:bg-yellow-500 hover:text-black transition" title="Toggle Status">
                                            <i class="fas fa-power-off text-xs"></i>
                                        </a>
                                        <?php if ($canDelete): ?>
                                            <button type="button" onclick="confirmDeleteIndex(<?= $user['id'] ?>, '<?= esc($user['name'], 'js') ?>')" class="w-8 h-8 flex items-center justify-center bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500 hover:text-white transition" title="Hapus">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        <?php elseif ($isUserPro): ?>
                                            <span class="w-8 h-8 flex items-center justify-center bg-gray-500/20 text-gray-500 rounded-lg cursor-not-allowed" title="User PRO tidak bisa dihapus">
                                                <i class="fas fa-lock text-xs"></i>
                                            </span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>


    <!-- Pagination -->
    <?php if (!empty($usersList) && $pager && $pager->getPageCount() > 1): ?>
        <?php
        $totalPages = ceil($total / $perPage);
        $queryParams = [];
        if ($currentRole) $queryParams['role'] = $currentRole;
        if ($currentSearch) $queryParams['search'] = $currentSearch;
        $queryString = $queryParams ? '&' . http_build_query($queryParams) : '';
        ?>
        <div class="px-4 py-4 border-t border-white/5 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-sm text-gray-500">
                Menampilkan <?= $startNumber ?> - <?= min($startNumber + count($usersList) - 1, $total) ?> dari <?= $total ?> user
            </p>
            <div class="flex items-center gap-1 flex-wrap justify-center">
                <?php if ($page > 1): ?>
                    <a href="?page=1<?= $queryString ?>" class="px-3 py-2 bg-white/10 rounded-lg hover:bg-white/20 transition text-sm">
                        <i class="fas fa-angle-double-left"></i>
                    </a>
                    <a href="?page=<?= $page - 1 ?><?= $queryString ?>" class="px-3 py-2 bg-white/10 rounded-lg hover:bg-white/20 transition text-sm">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                <?php endif; ?>

                <?php
                $startPage = max(1, $page - 2);
                $endPage = min($totalPages, $page + 2);
                for ($i = $startPage; $i <= $endPage; $i++):
                ?>
                    <a href="?page=<?= $i ?><?= $queryString ?>" class="px-3 py-2 rounded-lg text-sm <?= $i === $page ? 'bg-accent text-black font-bold' : 'bg-white/10 hover:bg-white/20' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?><?= $queryString ?>" class="px-3 py-2 bg-white/10 rounded-lg hover:bg-white/20 transition text-sm">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <a href="?page=<?= $totalPages ?><?= $queryString ?>" class="px-3 py-2 bg-white/10 rounded-lg hover:bg-white/20 transition text-sm">
                        <i class="fas fa-angle-double-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>


    function confirmDeleteIndex(id, name) {
        Swal.fire({
            title: 'Hapus User?',
            html: `Apakah Anda yakin ingin menghapus user <strong>${name}</strong>?<br><span class="text-sm text-gray-400">Tindakan ini tidak dapat dibatalkan.</span>`,
            icon: 'warning',
            background: '#111',
            color: '#fff',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'border border-white/10 rounded-2xl'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteFormGlobal');
                form.action = '<?= base_url('admin/users/delete') ?>/' + id;
                form.submit();
            }
        })
    }
</script>

<form id="deleteFormGlobal" method="post" style="display: none;">
    <?= csrf_field() ?>
</form>
<?= $this->endSection() ?>

<?= $this->endSection() ?>