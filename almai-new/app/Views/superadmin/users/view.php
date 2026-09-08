<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>
<?php
// Avatar URL
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

// Role display
$levelId = (int)($user['level_id'] ?? \App\Models\LevelModel::LEVEL_USER);
$roleDisplay = \App\Models\LevelModel::getRoleName($levelId);
$roleBgClass = \App\Models\LevelModel::getRoleBadgeClass($levelId);
$isUserPro = \App\Models\LevelModel::isProLevelName($levelId);
?>

<div class="mb-6">
    <a href="<?= base_url('superadmin/users') ?>" class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar User
    </a>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <!-- Left Column - Profile Card -->
    <div class="space-y-6">
        <!-- Profile Card -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 text-center">
            <?php if ($avatarUrl): ?>
                <img src="<?= esc($avatarUrl) ?>" alt="" class="w-24 h-24 rounded-full object-cover mx-auto mb-4 border-4 border-accent/20">
            <?php else: ?>
                <div class="w-24 h-24 rounded-full bg-accent/20 flex items-center justify-center text-accent font-bold text-3xl mx-auto mb-4 border-4 border-accent/20">
                    <?= strtoupper(substr($user['name'], 0, 2)) ?>
                </div>
            <?php endif; ?>

            <h2 class="text-xl font-bold mb-1"><?= esc($user['name']) ?></h2>
            <p class="text-gray-500 text-sm mb-4"><?= esc($user['email']) ?></p>

            <div class="flex justify-center gap-2 flex-wrap mb-4">
                <span class="px-3 py-1 rounded-full text-xs <?= $roleBgClass ?>">
                    <?php if ($roleDisplay === 'User PRO'): ?><i class="fas fa-crown mr-1"></i><?php endif; ?>
                    <?= $roleDisplay ?>
                </span>
                <span class="px-3 py-1 rounded-full text-xs <?= ($user['status'] ?? 'active') === 'active' || ($user['status'] ?? 1) == 1 ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' ?>">
                    <?= ($user['status'] ?? 'active') === 'active' || ($user['status'] ?? 1) == 1 ? 'Active' : 'Inactive' ?>
                </span>
            </div>

            <?php if ($isUserPro): ?>
                <div class="p-3 bg-gradient-to-r from-yellow-500/20 to-orange-500/20 border border-yellow-500/30 rounded-xl mb-4 text-center">
                    <p class="text-sm text-yellow-400 font-medium"><i class="fas fa-crown mr-2"></i>Member PRO</p>
                    <?php if (!empty($user['pro_expires_at'])): ?>
                        <p class="text-xs text-gray-400 mt-1">Berlaku hingga: <?= date('d M Y', strtotime($user['pro_expires_at'])) ?></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="text-left space-y-2 text-sm">
                <div class="flex items-center gap-2 text-gray-400">
                    <i class="fas fa-phone w-4"></i>
                    <span><?= esc($user['phone'] ?? '-') ?></span>
                </div>
                <div class="flex items-center gap-2 text-gray-400">
                    <i class="fas fa-calendar w-4"></i>
                    <span>Bergabung <?= date('d M Y', strtotime($user['created_at'])) ?></span>
                </div>
                <?php
                // The referral code is now taken from code_referral
                $userRefCode = $user['code_referral'] ?? '';
                if (!empty($userRefCode)):
                ?>
                    <div class="flex items-center gap-2 text-gray-400">
                        <i class="fas fa-link w-4"></i>
                        <span>Kode: <span class="text-accent font-mono"><?= esc($userRefCode) ?></span></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Stats Card -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
            <h3 class="font-bold mb-4 flex items-center gap-2">
                <i class="fas fa-chart-bar text-accent"></i> Statistik
            </h3>
            <div class="space-y-3">
                <!-- Total Poin & Rupiah -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-black/50 rounded-xl p-3">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-gray-400 text-xs">Total Poin</span>
                            <i class="fas fa-coins text-accent text-xs"></i>
                        </div>
                        <p class="text-xl font-bold text-accent"><?= number_format($poinBalance ?? 0) ?></p>
                    </div>
                    <div class="bg-black/50 rounded-xl p-3">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-gray-400 text-xs">Total Rupiah</span>
                            <i class="fas fa-wallet text-yellow-400 text-xs"></i>
                        </div>
                        <p class="text-xl font-bold text-yellow-400">Rp <?= number_format($rupiahBalance ?? 0, 0, ',', '.') ?></p>
                    </div>
                </div>
                <!-- Poin Diterima & Digunakan -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-black/50 rounded-xl p-3 text-center">
                        <p class="text-lg font-bold text-green-400">+<?= number_format($poinEarned ?? 0) ?></p>
                        <p class="text-xs text-gray-500">Poin Diterima</p>
                    </div>
                    <div class="bg-black/50 rounded-xl p-3 text-center">
                        <p class="text-lg font-bold text-red-400">-<?= number_format($poinUsed ?? 0) ?></p>
                        <p class="text-xs text-gray-500">Poin Digunakan</p>
                    </div>
                </div>
                <!-- Transaksi & Pembelian -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-black/50 rounded-xl p-3 text-center">
                        <p class="text-lg font-bold text-blue-400"><?= $totalTransaksi ?? 0 ?></p>
                        <p class="text-xs text-gray-500">Transaksi</p>
                    </div>
                    <div class="bg-black/50 rounded-xl p-3 text-center">
                        <p class="text-lg font-bold text-purple-400"><?= number_format(($totalPembelian ?? 0) / 1000000, 1) ?>jt</p>
                        <p class="text-xs text-gray-500">Pembelian</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pertumbuhan Downline Card - In Left Column -->
        <?php if (!empty($userRefCode)): ?>
            <?php
            // Prepare chart data - group downlines by month
            $chartData = [];
            $chartLabels = [];

            // Get last 12 months
            for ($i = 11; $i >= 0; $i--) {
                $month = date('Y-m', strtotime("-$i months"));
                $chartLabels[] = date('M Y', strtotime("-$i months"));
                $chartData[$month] = 0;
            }

            // Count downlines per month
            foreach ($downlineList as $dl) {
                $month = date('Y-m', strtotime($dl['created_at']));
                if (isset($chartData[$month])) {
                    $chartData[$month]++;
                }
            }

            // Convert to cumulative for growth chart
            $cumulativeData = [];
            $runningTotal = 0;

            // Count downlines before chart period
            foreach ($downlineList as $dl) {
                $dlMonth = date('Y-m', strtotime($dl['created_at']));
                if ($dlMonth < array_key_first($chartData)) {
                    $runningTotal++;
                }
            }

            foreach ($chartData as $month => $count) {
                $runningTotal += $count;
                $cumulativeData[] = $runningTotal;
            }

            // Stats for quick info
            $todayCount = 0;
            $weekCount = 0;
            $monthCount = 0;
            $yearCount = 0;

            $todayStart = strtotime('today');
            $weekStart = strtotime('-7 days');
            $monthStart = strtotime('-30 days');
            $yearStart = strtotime('-365 days');

            foreach ($downlineList as $dl) {
                $ts = strtotime($dl['created_at']);
                if ($ts >= $todayStart) $todayCount++;
                if ($ts >= $weekStart) $weekCount++;
                if ($ts >= $monthStart) $monthCount++;
                if ($ts >= $yearStart) $yearCount++;
            }
            ?>
            <div class="bg-[#111] border border-white/10 rounded-2xl p-5">
                <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
                    <i class="fas fa-chart-line text-accent"></i> Pertumbuhan Downline
                </h3>

                <!-- Stats Cards -->
                <div class="grid grid-cols-2 gap-2 mb-4">
                    <div class="bg-black/30 rounded-lg p-3 text-center cursor-pointer hover:bg-black/50 transition" onclick="filterDownline('today')">
                        <p class="text-xl font-bold text-accent"><?= $todayCount ?></p>
                        <p class="text-[9px] text-gray-500">Hari Ini</p>
                    </div>
                    <div class="bg-black/30 rounded-lg p-3 text-center cursor-pointer hover:bg-black/50 transition" onclick="filterDownline('week')">
                        <p class="text-xl font-bold text-blue-400"><?= $weekCount ?></p>
                        <p class="text-[9px] text-gray-500">7 Hari</p>
                    </div>
                    <div class="bg-black/30 rounded-lg p-3 text-center cursor-pointer hover:bg-black/50 transition" onclick="filterDownline('month')">
                        <p class="text-xl font-bold text-purple-400"><?= $monthCount ?></p>
                        <p class="text-[9px] text-gray-500">30 Hari</p>
                    </div>
                    <div class="bg-black/30 rounded-lg p-3 text-center cursor-pointer hover:bg-black/50 transition" onclick="filterDownline('year')">
                        <p class="text-xl font-bold text-yellow-400"><?= $yearCount ?></p>
                        <p class="text-[9px] text-gray-500">1 Tahun</p>
                    </div>
                </div>

                <!-- Chart -->
                <div class="bg-black/20 rounded-xl p-3 mb-4">
                    <div class="h-40">
                        <canvas id="downlineChart"></canvas>
                    </div>
                </div>

                <!-- Summary -->
                <div class="grid grid-cols-2 gap-2">
                    <div class="bg-black/30 rounded-lg p-3 text-center">
                        <p class="text-lg font-bold text-accent"><?= $downlineCount ?></p>
                        <p class="text-[9px] text-gray-500">Total Downline</p>
                    </div>
                    <div class="bg-black/30 rounded-lg p-3 text-center">
                        <p class="text-lg font-bold text-green-400">+<?= end($chartData) ?: 0 ?></p>
                        <p class="text-[9px] text-gray-500">Bulan Ini</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Right Column - Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Account Info -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
            <h3 class="font-bold mb-4 flex items-center gap-2">
                <i class="fas fa-user text-accent"></i> Informasi Akun
            </h3>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500 mb-1">User ID</p>
                    <p class="font-medium text-gray-300">#<?= $user['id'] ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Nama Lengkap</p>
                    <p class="font-medium"><?= esc($user['name']) ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Email</p>
                    <p class="font-medium"><?= esc($user['email']) ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">No. WhatsApp</p>
                    <p class="font-medium"><?= esc($user['phone'] ?? '-') ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Role</p>
                    <p class="font-medium"><?= $roleDisplay ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Status</p>
                    <p class="font-medium"><?= ($user['status'] ?? 'active') === 'active' || ($user['status'] ?? 1) == 1 ? 'Active' : 'Inactive' ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Terdaftar</p>
                    <p class="font-medium"><?= date('d M Y, H:i', strtotime($user['created_at'])) ?></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Terakhir Update</p>
                    <p class="font-medium"><?= date('d M Y, H:i', strtotime($user['updated_at'])) ?></p>
                </div>
                <?php if (!empty($user['referred_by'])): ?>
                    <div class="sm:col-span-2">
                        <p class="text-xs text-gray-500 mb-1">Direferensikan Oleh</p>
                        <p class="font-medium text-accent"><?= esc($user['referred_by']) ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Riwayat Pembelian Layanan -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
            <h3 class="font-bold mb-4 flex items-center gap-2">
                <i class="fas fa-shopping-bag text-accent"></i> Riwayat Layanan
                <span class="px-2 py-1 bg-accent/20 text-accent rounded-full text-xs ml-2"><?= count($purchasedServices) ?> Produk</span>
            </h3>

            <?php if (empty($purchasedServices)): ?>
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-shopping-cart text-3xl mb-2"></i>
                    <p class="text-sm">Belum ada pembelian layanan</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-white/10 text-left">
                                <th class="py-3 px-2 text-[10px] font-medium text-gray-500 uppercase">Produk</th>
                                <th class="py-3 px-2 text-[10px] font-medium text-gray-500 uppercase">Invoice</th>
                                <th class="py-3 px-2 text-[10px] font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="py-3 px-2 text-[10px] font-medium text-gray-500 uppercase">Status</th>
                                <th class="py-3 px-2 text-[10px] font-medium text-gray-500 uppercase text-right">Dokumen</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <?php foreach ($purchasedServices as $service): ?>
                                <tr class="hover:bg-white/5 transition">
                                    <td class="py-3 px-2">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-gray-200"><?= esc($service['product_name']) ?></span>
                                            <span class="text-[10px] text-gray-500 uppercase"><?= esc($service['product_type']) ?></span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-2">
                                        <a href="<?= base_url('superadmin/transaksi/detail/' . $service['id']) ?>" class="text-xs font-mono text-accent hover:underline">
                                            #<?= esc($service['invoice_number']) ?>
                                        </a>
                                    </td>
                                    <td class="py-3 px-2">
                                        <span class="text-xs text-gray-400"><?= date('d/m/Y', strtotime($service['created_at'])) ?></span>
                                    </td>
                                    <td class="py-3 px-2">
                                        <?php
                                        $statusClass = 'bg-gray-500/20 text-gray-400';
                                        if ($service['status'] === 'confirmed') $statusClass = 'bg-accent/20 text-accent';
                                        elseif ($service['status'] === 'paid') $statusClass = 'bg-blue-500/20 text-blue-400';
                                        elseif ($service['status'] === 'refunded') $statusClass = 'bg-red-500/20 text-red-400';
                                        ?>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold <?= $statusClass ?>">
                                            <?= strtoupper($service['status']) ?>
                                        </span>
                                    </td>
                                    <td class="py-3 px-2 text-right">
                                        <?php if ($service['status'] === 'confirmed' || $service['status'] === 'paid'): ?>
                                            <div class="flex justify-end gap-2">
                                                <a href="<?= base_url('superadmin/users/download-agreement/' . $service['id'] . '/perjanjian') ?>"
                                                    class="p-1.5 bg-white/5 hover:bg-accent/20 text-gray-400 hover:text-accent rounded-lg transition"
                                                    title="Download Perjanjian">
                                                    <i class="fas fa-file-contract"></i>
                                                </a>
                                                <a href="<?= base_url('superadmin/users/download-agreement/' . $service['id'] . '/risiko') ?>"
                                                    class="p-1.5 bg-white/5 hover:bg-accent/20 text-gray-400 hover:text-accent rounded-lg transition"
                                                    title="Download Risiko">
                                                    <i class="fas fa-file-invoice"></i>
                                                </a>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-[10px] text-gray-600 italic">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- KYC Status -->
        <!-- Debug: kyc_status: <?= $user['kyc_status'] ?? 'null' ?>, kyc_count: <?= !empty($kyc) ? 'found' : 'empty' ?> -->
        <?php if (!empty($user['kyc_status']) || !empty($kyc) || $isUserPro): ?>
            <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
                <h3 class="font-bold mb-4 flex items-center gap-2">
                    <i class="fas fa-id-card text-accent"></i> Status KYC
                    <?php
                    $kycStatus = $user['kyc_status'] ?? 'pending';
                    if ($isUserPro) {
                        $kycStatus = 'approved';
                    }

                    if ($kycStatus === 'pending'):
                    ?>
                        <span class="px-2 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-xs ml-2">Pending</span>
                    <?php elseif ($kycStatus === 'approved'): ?>
                        <span class="px-2 py-1 bg-accent/20 text-accent rounded-full text-xs ml-2">Approved</span>
                    <?php else: ?>
                        <span class="px-2 py-1 bg-red-500/20 text-red-400 rounded-full text-xs ml-2">Rejected</span>
                    <?php endif; ?>
                </h3>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Tanggal Submit</p>
                        <p class="font-medium"><?= !empty($user['kyc_submitted_at']) ? date('d M Y, H:i', strtotime($user['kyc_submitted_at'])) : '-' ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Status PRO</p>
                        <p class="font-medium"><?= $isUserPro ? 'Aktif' : 'Tidak Aktif' ?></p>
                    </div>
                    <?php if ($isUserPro && !empty($user['pro_expires_at'])): ?>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">PRO Berlaku Hingga</p>
                            <p class="font-medium text-yellow-400"><?= date('d M Y', strtotime($user['pro_expires_at'])) ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($kyc)): ?>
                    <div class="mt-6 pt-6 border-t border-white/5">
                        <h4 class="text-sm font-bold mb-4 text-gray-400">Detail Identitas & Pekerjaan</h4>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 mb-1">No. Identitas (<?= esc($kyc['identity_type'] ?? 'KTP/NIK') ?>)</p>
                                <p class="font-medium"><?= esc($kyc['identity_number'] ?? ($kyc['nik'] ?? '-')) ?></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">NPWP / KTP Lainnya</p>
                                <p class="font-medium"><?= esc($kyc['npwp'] ?? (!empty($kyc['ktp_photo']) ? 'Tersedia' : '-')) ?></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Pekerjaan / Bidang</p>
                                <p class="font-medium"><?= esc($kyc['profession'] ?? ($kyc['experience'] ?? '-')) ?></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Perusahaan / Info Tambahan</p>
                                <p class="font-medium"><?= esc($kyc['company'] ?? ($kyc['investment_goal'] ?? '-')) ?></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Alamat Domisili</p>
                                <p class="font-medium"><?= esc($kyc['address'] ?? ($kyc['province'] ?? '-')) ?></p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Info Keuangan / Profil</p>
                                <p class="font-medium"><?= esc($kyc['annually_income'] ?? ($kyc['risk_profile'] ?? '-')) ?></p>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="mt-6 pt-6 border-t border-white/5 text-center py-4">
                        <i class="fas fa-exclamation-circle text-yellow-500/50 text-2xl mb-2"></i>
                        <p class="text-sm text-gray-500 italic">Data profil detail tidak ditemukan di tabel user_data maupun kyc_submissions</p>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- Daftar Downline Card -->
        <?php if (!empty($userRefCode)): ?>
            <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                    <h3 class="font-bold flex items-center gap-2">
                        <i class="fas fa-users text-accent"></i> Daftar Downline
                        <span id="filteredCount" class="px-2 py-1 bg-accent/20 text-accent rounded-full text-xs"><?= $downlineCount ?> User</span>
                    </h3>
                    <div class="flex flex-wrap items-center gap-2">
                        <select id="filterPeriod" onchange="filterDownline(this.value)" class="bg-black/50 border border-white/10 rounded-lg px-3 py-1.5 text-xs focus:border-accent focus:outline-none">
                            <option value="all">Semua</option>
                            <option value="today">Hari Ini</option>
                            <option value="week">7 Hari</option>
                            <option value="month">30 Hari</option>
                            <option value="year">1 Tahun</option>
                        </select>
                        <select id="sortDownline" onchange="sortDownlineList()" class="bg-black/50 border border-white/10 rounded-lg px-3 py-1.5 text-xs focus:border-accent focus:outline-none">
                            <option value="newest">Terbaru</option>
                            <option value="oldest">Terlama</option>
                            <option value="name_asc">A-Z</option>
                            <option value="name_desc">Z-A</option>
                        </select>
                    </div>
                </div>

                <?php if (empty($downlineList)): ?>
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-user-slash text-3xl mb-2"></i>
                        <p class="text-sm">Belum ada downline</p>
                    </div>
                <?php else: ?>

                    <!-- Downline Table -->
                    <div class="overflow-x-auto max-h-[400px] overflow-y-auto">
                        <table class="w-full" id="downlineTable">
                            <thead class="sticky top-0 bg-[#111]">
                                <tr class="border-b border-white/10">
                                    <th class="text-left py-2 px-2 text-[10px] font-medium text-gray-500 uppercase">User</th>
                                    <th class="text-left py-2 px-2 text-[10px] font-medium text-gray-500 uppercase hidden md:table-cell">Email</th>
                                    <th class="text-left py-2 px-2 text-[10px] font-medium text-gray-500 uppercase">Role</th>
                                    <th class="text-right py-2 px-2 text-[10px] font-medium text-gray-500 uppercase">Bergabung</th>
                                </tr>
                            </thead>
                            <tbody id="downlineBody" class="divide-y divide-white/5">
                                <?php foreach ($downlineList as $downline): ?>
                                    <?php
                                    $dlLevelId = $downline['level_id'];
                                    $dlRoleDisplay = 'User';
                                    $dlRoleBgClass = 'bg-accent/20 text-accent';

                                    if (in_array($dlLevelId, [\App\Models\LevelModel::LEVEL_ADMIN, \App\Models\LevelModel::LEVEL_ACCOUNTING, \App\Models\LevelModel::LEVEL_SUPER_ADMIN])) {
                                        $dlRoleDisplay = 'Admin';
                                        $dlRoleBgClass = 'bg-purple-500/20 text-purple-400';
                                    } elseif ($dlLevelId == \App\Models\LevelModel::LEVEL_WPA) {
                                        $dlRoleDisplay = 'WPA';
                                        $dlRoleBgClass = 'bg-blue-500/20 text-blue-400';
                                    } elseif ($dlLevelId == \App\Models\LevelModel::LEVEL_PRO) {
                                        $dlRoleDisplay = 'PRO';
                                        $dlRoleBgClass = 'bg-yellow-500/20 text-yellow-400';
                                    }
                                    ?>
                                    <tr class="downline-row hover:bg-white/5 transition"
                                        data-name="<?= esc($downline['name']) ?>"
                                        data-date="<?= strtotime($downline['created_at']) ?>">
                                        <td class="py-2 px-2">
                                            <div class="flex items-center gap-2">
                                                <div class="w-7 h-7 bg-gradient-to-br from-accent/30 to-green-600/30 rounded-full flex items-center justify-center text-accent text-[10px] font-bold flex-shrink-0">
                                                    <?= strtoupper(substr($downline['name'], 0, 2)) ?>
                                                </div>
                                                <a href="<?= base_url('superadmin/users/view/' . $downline['id']) ?>" class="font-medium text-xs hover:text-accent transition truncate"><?= esc($downline['name']) ?></a>
                                            </div>
                                        </td>
                                        <td class="py-2 px-2 hidden md:table-cell">
                                            <span class="text-xs text-gray-400"><?= esc($downline['email']) ?></span>
                                        </td>
                                        <td class="py-2 px-2">
                                            <span class="px-1.5 py-0.5 rounded-full text-[9px] <?= $dlRoleBgClass ?>"><?= $dlRoleDisplay ?></span>
                                        </td>
                                        <td class="py-2 px-2 text-right">
                                            <span class="text-[10px] text-gray-500"><?= date('d M Y', strtotime($downline['created_at'])) ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Chart.js for downline growth -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                // Chart initialization
                const ctx = document.getElementById('downlineChart');
                if (ctx) {
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: <?= json_encode($chartLabels) ?>,
                            datasets: [{
                                label: 'Total Downline',
                                data: <?= json_encode($cumulativeData) ?>,
                                borderColor: '#33e818',
                                backgroundColor: 'rgba(51, 232, 24, 0.1)',
                                fill: true,
                                tension: 0.4,
                                pointRadius: 2,
                                pointBackgroundColor: '#33e818'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                x: {
                                    grid: {
                                        color: 'rgba(255,255,255,0.05)'
                                    },
                                    ticks: {
                                        color: '#666',
                                        font: {
                                            size: 8
                                        },
                                        maxRotation: 45
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(255,255,255,0.05)'
                                    },
                                    ticks: {
                                        color: '#666',
                                        font: {
                                            size: 8
                                        }
                                    }
                                }
                            }
                        }
                    });
                }

                // Period timestamps
                const periodTimestamps = {
                    today: <?= $todayStart ?>,
                    week: <?= $weekStart ?>,
                    month: <?= $monthStart ?>,
                    year: <?= $yearStart ?>
                };

                let currentFilter = 'all';

                function filterDownline(period) {
                    currentFilter = period;
                    document.getElementById('filterPeriod').value = period;

                    const rows = document.querySelectorAll('.downline-row');
                    let visibleCount = 0;

                    rows.forEach(row => {
                        const rowDate = parseInt(row.dataset.date);
                        let show = period === 'all' || rowDate >= periodTimestamps[period];
                        row.style.display = show ? '' : 'none';
                        if (show) visibleCount++;
                    });

                    document.getElementById('filteredCount').textContent = visibleCount + ' User';
                }

                function sortDownlineList() {
                    const sortBy = document.getElementById('sortDownline').value;
                    const tbody = document.getElementById('downlineBody');
                    const rows = Array.from(tbody.querySelectorAll('.downline-row'));

                    rows.sort((a, b) => {
                        if (sortBy === 'newest') return parseInt(b.dataset.date) - parseInt(a.dataset.date);
                        if (sortBy === 'oldest') return parseInt(a.dataset.date) - parseInt(b.dataset.date);
                        if (sortBy === 'name_asc') return a.dataset.name.localeCompare(b.dataset.name);
                        if (sortBy === 'name_desc') return b.dataset.name.localeCompare(a.dataset.name);
                        return 0;
                    });

                    rows.forEach(row => tbody.appendChild(row));
                    filterDownline(currentFilter);
                }
            </script>
        <?php endif; ?>

        <!-- Actions -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
            <h3 class="font-bold mb-4 flex items-center gap-2">
                <i class="fas fa-cogs text-accent"></i> Aksi
            </h3>
            <div class="flex flex-col sm:flex-row flex-wrap gap-3">
                <a href="<?= base_url('superadmin/users/edit/' . $user['id']) ?>" class="w-full sm:w-auto px-4 py-2 bg-blue-500/20 text-blue-400 rounded-xl hover:bg-blue-500 hover:text-white transition text-sm text-center">
                    <i class="fas fa-edit mr-2"></i> Edit User
                </a>
                <a href="<?= base_url('superadmin/users/toggle-status/' . $user['id']) ?>" class="w-full sm:w-auto px-4 py-2 bg-yellow-500/20 text-yellow-400 rounded-xl hover:bg-yellow-500 hover:text-black transition text-sm text-center">
                    <i class="fas fa-power-off mr-2"></i> Toggle Status
                </a>
                <?php if (!empty($user['kyc_status']) || !empty($kyc)): ?>
                    <a href="<?= base_url('superadmin/kyc/detail/' . $user['id']) ?>" class="w-full sm:w-auto px-4 py-2 bg-purple-500/20 text-purple-400 rounded-xl hover:bg-purple-500 hover:text-white transition text-sm text-center">
                        <i class="fas fa-file-alt mr-2"></i> Lihat KYC Detail
                    </a>
                <?php endif; ?>
                <?php
                $isUserRole = in_array($user['level_id'], [\App\Models\LevelModel::LEVEL_USER, \App\Models\LevelModel::LEVEL_PRO, \App\Models\LevelModel::LEVEL_CWPA]);
                if ($isUserRole && $user['id'] != session()->get('userId') && !$isUserPro):
                ?>
                    <form id="deleteForm-<?= $user['id'] ?>" action="<?= base_url('superadmin/users/delete/' . $user['id']) ?>" method="post" class="w-full sm:w-auto inline">
                        <?= csrf_field() ?>
                        <button type="button" onclick="confirmDelete(<?= $user['id'] ?>, '<?= esc($user['name']) ?>')" class="w-full sm:w-auto px-4 py-2 bg-red-500/20 text-red-400 rounded-xl hover:bg-red-500 hover:text-white transition text-sm text-center">
                            <i class="fas fa-trash mr-2"></i> Hapus User
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Hapus User?',
            html: `Apakah Anda yakin ingin menghapus user <strong>${name}</strong>?<br><span class="text-sm text-gray-400">Tindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait.</span>`,
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
                document.getElementById('deleteForm-' + id).submit();
            }
        })
    }
</script>
<?= $this->endSection() ?>