<?= $this->extend('laporan-kegiatan/layouts/main') ?>

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
$levelId = $user['level_id'];
$roleDisplay = 'User';
$roleBgClass = 'bg-accent/20 text-accent';
$isUserPro = !empty($user['level_id']) && $user['level_id'] == \App\Models\LevelModel::LEVEL_PRO;

if (in_array($levelId, [\App\Models\LevelModel::LEVEL_ADMIN, \App\Models\LevelModel::LEVEL_ACCOUNTING, \App\Models\LevelModel::LEVEL_SUPER_ADMIN, \App\Models\LevelModel::LEVEL_PARTNERSHIP, \App\Models\LevelModel::LEVEL_ADMIN_WPA])) {
    $roleDisplay = 'Staff/Admin';
    $roleBgClass = 'bg-purple-500/20 text-purple-400';
} elseif ($levelId == \App\Models\LevelModel::LEVEL_WPA) {
    $roleDisplay = 'WPA';
    $roleBgClass = 'bg-blue-500/20 text-blue-400';
} elseif ($levelId == \App\Models\LevelModel::LEVEL_PRO) {
    $roleDisplay = 'User PRO';
    $roleBgClass = 'bg-yellow-500/20 text-yellow-400';
    $isUserPro = true;
} elseif ($levelId == \App\Models\LevelModel::LEVEL_CWPA) {
    $roleDisplay = 'CWPA';
    $roleBgClass = 'bg-green-500/20 text-green-400';
}
?>

<div class="mb-6">
    <a href="<?= base_url('laporan-kegiatan/klien') ?>" class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Klien
    </a>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <!-- Left Column - Profile Card -->
    <div class="space-y-6">
        <!-- Profile Card -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 text-center">
            <div class="w-24 h-24 rounded-full bg-accent/20 flex items-center justify-center text-accent font-bold text-3xl mx-auto mb-4 border-4 border-accent/20">
                <?= strtoupper(substr($user['name'], 0, 2)) ?>
            </div>

            <h2 class="text-xl font-bold mb-1"><?= esc($user['name']) ?></h2>
            <p class="text-gray-500 text-sm mb-4"><?= esc($user['email']) ?></p>

            <div class="flex justify-center gap-2 flex-wrap mb-4">
                <span class="px-3 py-1 rounded-full text-xs <?= $roleBgClass ?>">
                    <?= $roleDisplay ?>
                </span>
                <span class="px-3 py-1 rounded-full text-xs <?= ($user['status'] ?? 'active') === 'active' || ($user['status'] ?? 1) == 1 ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' ?>">
                    <?= ($user['status'] ?? 'active') === 'active' || ($user['status'] ?? 1) == 1 ? 'Active' : 'Inactive' ?>
                </span>
            </div>

            <div class="text-left space-y-2 text-sm pt-4 border-t border-white/5">
                <div class="flex items-center gap-2 text-gray-400">
                    <i class="fas fa-phone w-4 text-accent"></i>
                    <span><?= esc($user['phone'] ?? '-') ?></span>
                </div>
                <div class="flex items-center gap-2 text-gray-400">
                    <i class="fas fa-calendar w-4 text-accent"></i>
                    <span>Bergabung <?= date('d M Y', strtotime($user['created_at'])) ?></span>
                </div>
                <?php $userRefCode = $user['code_referral'] ?? ''; if (!empty($userRefCode)): ?>
                    <div class="flex items-center gap-2 text-gray-400">
                        <i class="fas fa-link w-4 text-accent"></i>
                        <span>Kode: <span class="text-accent font-mono"><?= esc($userRefCode) ?></span></span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Stats Card -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
            <h3 class="font-bold mb-4 flex items-center gap-2">
                <i class="fas fa-chart-pie text-accent"></i> Statistik (Read Only)
            </h3>
            <div class="space-y-3">
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-black/50 rounded-xl p-3">
                        <span class="text-gray-400 text-[10px] block mb-1">Total Poin</span>
                        <p class="text-lg font-bold text-accent"><?= number_format($poinBalance ?? 0) ?></p>
                    </div>
                    <div class="bg-black/50 rounded-xl p-3">
                        <span class="text-gray-400 text-[10px] block mb-1">Downline</span>
                        <p class="text-lg font-bold text-accent"><?= number_format($downlineCount ?? 0) ?></p>
                    </div>
                </div>
                <div class="bg-black/50 rounded-xl p-4">
                    <span class="text-gray-400 text-[10px] block mb-1">Total Pembelian</span>
                    <p class="text-2xl font-bold text-accent">Rp <?= number_format($totalPembelian ?? 0, 0, ',', '.') ?></p>
                </div>
                <div class="bg-black/50 rounded-xl p-3 text-center">
                    <p class="text-lg font-bold text-blue-400"><?= $totalTransaksi ?? 0 ?></p>
                    <p class="text-[10px] text-gray-500 uppercase font-medium">Transaksi Terdaftar</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Account Info -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
            <h3 class="font-bold mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-accent"></i> Informasi Detail
            </h3>
            <div class="grid sm:grid-cols-2 gap-x-6 gap-y-4">
                <div>
                    <label class="text-xs text-gray-500 block mb-1">Nama Lengkap</label>
                    <p class="font-medium text-gray-200"><?= esc($user['name']) ?></p>
                </div>
                <div>
                    <label class="text-xs text-gray-500 block mb-1">Status Keanggotaan</label>
                    <p class="font-medium text-gray-200"><?= $roleDisplay ?></p>
                </div>
                <div>
                    <label class="text-xs text-gray-500 block mb-1">ID Pengguna</label>
                    <p class="font-medium text-gray-400">#<?= $user['id'] ?></p>
                </div>
                <div>
                    <label class="text-xs text-gray-500 block mb-1">Tanggal Buat</label>
                    <p class="font-medium text-gray-400"><?= date('d F Y, H:i', strtotime($user['created_at'])) ?></p>
                </div>
            </div>
        </div>

        <!-- Table: Riwayat Pembelian -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
            <h3 class="font-bold mb-4 flex items-center justify-between">
                <span class="flex items-center gap-2"><i class="fas fa-shopping-cart text-accent"></i> Aktivitas Transaksi</span>
                <span class="text-xs text-gray-500 font-normal">Read Only</span>
            </h3>

            <?php if (empty($purchasedServices)): ?>
                <div class="text-center py-8 text-gray-500 bg-black/20 rounded-xl">
                    <p class="text-sm">Tidak ada riwayat transaksi</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-white/5">
                                <th class="py-3 px-2 text-[10px] text-gray-500 uppercase">Produk</th>
                                <th class="py-3 px-2 text-[10px] text-gray-500 uppercase">Invoice</th>
                                <th class="py-3 px-2 text-[10px] text-gray-500 uppercase">Status</th>
                                <th class="py-3 px-2 text-[10px] text-gray-500 uppercase text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <?php foreach ($purchasedServices as $service): ?>
                                <tr class="hover:bg-white/5 transition">
                                    <td class="py-3 px-2">
                                        <p class="text-sm font-medium text-gray-200"><?= esc($service['product_name']) ?></p>
                                        <p class="text-[9px] text-gray-500 uppercase"><?= esc($service['product_type']) ?></p>
                                    </td>
                                    <td class="py-3 px-2">
                                        <span class="text-xs font-mono text-gray-400">#<?= esc($service['invoice_number']) ?></span>
                                    </td>
                                    <td class="py-3 px-2">
                                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold <?= $service['status'] === 'confirmed' ? 'bg-accent/20 text-accent' : 'bg-gray-500/20 text-gray-400' ?>">
                                            <?= strtoupper($service['status']) ?>
                                        </span>
                                    </td>
                                    <td class="py-3 px-2 text-right">
                                        <span class="text-sm font-bold text-accent">Rp <?= number_format($service['total'], 0, ',', '.') ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- KYC / User Data Summary -->
        <?php if (!empty($kyc)): ?>
            <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
                <h3 class="font-bold mb-4 flex items-center gap-2">
                    <i class="fas fa-id-badge text-accent"></i> Data Profil Lengkap
                </h3>
                <div class="grid sm:grid-cols-2 gap-4 bg-black/20 p-4 rounded-xl border border-white/5">
                    <div>
                        <p class="text-[10px] text-gray-500 uppercase mb-1">Identitas (KTP/Lainnya)</p>
                        <p class="font-medium text-sm"><?= esc($kyc['identity_number'] ?? ($kyc['nik'] ?? '-')) ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-500 uppercase mb-1">Pekerjaan</p>
                        <p class="font-medium text-sm"><?= esc($kyc['profession'] ?? ($kyc['experience'] ?? '-')) ?></p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-[10px] text-gray-500 uppercase mb-1">Alamat Domisili</p>
                        <p class="font-medium text-sm text-gray-400"><?= esc($kyc['address'] ?? ($kyc['province'] ?? '-')) ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
