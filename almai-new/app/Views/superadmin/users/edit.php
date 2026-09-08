<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Edit User - Admin Dashboard') ?></title>
    <link rel="stylesheet" href="<?= base_url('css/tailwind.min.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        html,
        body {
            background-color: #050505;
            color: #ffffff;
        }

        .sidebar-link.active {
            background: linear-gradient(90deg, rgba(51, 232, 24, 0.2) 0%, transparent 100%);
            border-left: 3px solid #33e818;
        }
    </style>
</head>

<body class="antialiased">
    <div class="flex min-h-screen">
        <?= $this->include('superadmin/partials/sidebar') ?>

        <main class="flex-1 md:ml-64 pt-14 md:pt-0">
            <header class="bg-[#0a0a0a] border-b border-white/10 px-4 md:px-8 py-4 flex items-center gap-4 sticky top-14 md:top-0 z-10">
                <a href="<?= base_url('superadmin/users') ?>" class="p-2 hover:bg-white/10 rounded-lg transition">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-lg md:text-xl font-bold">Edit User</h1>
                    <p class="text-xs md:text-sm text-gray-500 hidden md:block"><?= esc($user['name']) ?></p>
                </div>
            </header>

            <section class="p-4 md:p-8">
                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl mb-4">
                        <ul class="list-disc list-inside">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('superadmin/users/update/' . $user['id']) ?>" method="post" class="max-w-4xl">
                    <?= csrf_field() ?>

                    <div class="grid md:grid-cols-3 gap-6">
                        <!-- Main Content -->
                        <div class="md:col-span-2 space-y-6">
                            <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
                                <h3 class="font-bold mb-4 flex items-center gap-2">
                                    <i class="fas fa-user text-accent"></i> Informasi User
                                </h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Nama Lengkap *</label>
                                        <input type="text" name="name" required value="<?= old('name', $user['name']) ?>"
                                            class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                                            placeholder="Nama lengkap">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Email *</label>
                                        <input type="email" name="email" required value="<?= old('email', $user['email']) ?>"
                                            class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                                            placeholder="email@example.com">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">No. WhatsApp *</label>
                                        <input type="tel" name="phone" required value="<?= old('phone', $user['phone']) ?>"
                                            class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                                            placeholder="08xxxxxxxxxx">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Kode Referral</label>
                                        <input type="text" name="code_referral" value="<?= old('code_referral', $user['code_referral'] ?? '') ?>"
                                            class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                                            placeholder="Kode Referral (Opsional)">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Password Baru</label>
                                        <input type="password" name="password"
                                            class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                                            placeholder="Kosongkan jika tidak ingin mengubah">
                                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah password</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="space-y-6">
                            <!-- User Preview -->
                            <?php
                            // Avatar URL - handle different storage formats
                            $avatarUrl = '';
                            if (!empty($user['avatar'])) {
                                if (strpos($user['avatar'], 'http') === 0) {
                                    // External URL
                                    $avatarUrl = $user['avatar'];
                                } elseif (strpos($user['avatar'], 'uploads/') === 0) {
                                    // Already has uploads/ prefix (e.g., uploads/avatars/avatar_xxx.png)
                                    $avatarUrl = base_url('file/' . $user['avatar']);
                                } else {
                                    // Just filename (e.g., avatar_xxx.png)
                                    $avatarUrl = base_url('file/uploads/avatars/' . $user['avatar']);
                                }
                            }

                            $roleDisplay = 'User';
                            $roleBgClass = 'bg-accent/20 text-accent';
                            $levelId = $user['level_id'];

                            if (in_array($levelId, [\App\Models\LevelModel::LEVEL_ADMIN, \App\Models\LevelModel::LEVEL_ACCOUNTING, \App\Models\LevelModel::LEVEL_SUPER_ADMIN])) {
                                $roleDisplay = 'Admin';
                                $roleBgClass = 'bg-purple-500/20 text-purple-400';
                            } elseif ($levelId == \App\Models\LevelModel::LEVEL_ADMIN_WPA) {
                                $roleDisplay = 'Admin WPA';
                                $roleBgClass = 'bg-yellow-100/20 text-yellow-500';
                            } elseif ($levelId == \App\Models\LevelModel::LEVEL_PARTNERSHIP) {
                                $roleDisplay = 'Partnership';
                                $roleBgClass = 'bg-blue-100/20 text-blue-500';
                            } elseif ($levelId == \App\Models\LevelModel::LEVEL_WPA) {
                                $roleDisplay = 'WPA';
                                $roleBgClass = 'bg-blue-500/20 text-blue-400';
                            } elseif ($levelId == \App\Models\LevelModel::LEVEL_PRO || (!empty($user['is_pro']) && $user['is_pro'] == 1)) {
                                $roleDisplay = 'User PRO';
                                $roleBgClass = 'bg-yellow-500/20 text-yellow-400';
                            }
                            ?>
                            <div class="bg-[#111] border border-white/10 rounded-2xl p-6 text-center">
                                <?php if ($avatarUrl): ?>
                                    <img src="<?= esc($avatarUrl) ?>" alt="" class="w-20 h-20 rounded-full object-cover mx-auto mb-4">
                                <?php else: ?>
                                    <div class="w-20 h-20 rounded-full bg-accent/20 flex items-center justify-center text-accent font-bold text-2xl mx-auto mb-4">
                                        <?= strtoupper(substr($user['name'], 0, 2)) ?>
                                    </div>
                                <?php endif; ?>
                                <h3 class="font-bold mb-1"><?= esc($user['name']) ?></h3>
                                <p class="text-sm text-gray-500 mb-3"><?= esc($user['email']) ?></p>
                                <div class="flex justify-center gap-2">
                                    <span class="px-3 py-1 rounded-full text-xs <?= $roleBgClass ?>">
                                        <?php if ($roleDisplay === 'User PRO'): ?><i class="fas fa-crown mr-1"></i><?php endif; ?>
                                        <?= $roleDisplay ?>
                                    </span>
                                    <span class="px-3 py-1 rounded-full text-xs <?= ($user['status'] ?? 'active') === 'active' || ($user['status'] ?? 1) == 1 ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' ?>">
                                        <?= ($user['status'] ?? 'active') === 'active' || ($user['status'] ?? 1) == 1 ? 'Active' : 'Inactive' ?>
                                    </span>
                                </div>
                                <?php if (!empty($user['is_pro']) && $user['is_pro'] == 1): ?>
                                    <div class="mt-4 p-3 bg-yellow-500/10 border border-yellow-500/20 rounded-xl">
                                        <p class="text-xs text-yellow-400"><i class="fas fa-crown mr-1"></i> User PRO aktif</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
                                <h3 class="font-bold mb-4 flex items-center gap-2">
                                    <i class="fas fa-cog text-accent"></i> Pengaturan
                                </h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Role (Multi Select)</label>
                                        <div class="space-y-2 bg-black border border-white/20 rounded-xl px-4 py-3">
                                            <?php
                                            $allRoles = [
                                                'user_pro' => 'User Pro',
                                                'cwpa' => 'CWPA',
                                                'wpa' => 'WPA',
                                                'admin-wpa' => 'Admin WPA',
                                                'admin-partnership' => 'Admin Partnership',
                                                'accounting' => 'Keuangan / Accounting',
                                                'admin' => 'Admin'
                                            ];

                                            $currentRoles = [];
                                            // Primary
                                            if ($user['level_id'] == \App\Models\LevelModel::LEVEL_PRO) $currentRoles[] = 'user_pro';
                                            elseif ($user['level_id'] == \App\Models\LevelModel::LEVEL_CWPA) $currentRoles[] = 'cwpa';
                                            elseif ($user['level_id'] == \App\Models\LevelModel::LEVEL_WPA) $currentRoles[] = 'wpa';
                                            elseif ($user['level_id'] == \App\Models\LevelModel::LEVEL_ADMIN_WPA) $currentRoles[] = 'admin-wpa';
                                            elseif ($user['level_id'] == \App\Models\LevelModel::LEVEL_PARTNERSHIP) $currentRoles[] = 'admin-partnership';
                                            elseif ($user['level_id'] == \App\Models\LevelModel::LEVEL_ACCOUNTING) $currentRoles[] = 'accounting';
                                            elseif ($user['level_id'] == \App\Models\LevelModel::LEVEL_ADMIN) $currentRoles[] = 'admin';

                                            // Secondary
                                            if (!empty($user['secondary_level_ids'])) {
                                                $secondary = json_decode($user['secondary_level_ids'], true) ?? [];
                                                foreach ($secondary as $lid) {
                                                    if ($lid == \App\Models\LevelModel::LEVEL_PRO) $currentRoles[] = 'user_pro';
                                                    if ($lid == \App\Models\LevelModel::LEVEL_CWPA) $currentRoles[] = 'cwpa';
                                                    if ($lid == \App\Models\LevelModel::LEVEL_WPA) $currentRoles[] = 'wpa';
                                                    if ($lid == \App\Models\LevelModel::LEVEL_ADMIN_WPA) $currentRoles[] = 'admin-wpa';
                                                    if ($lid == \App\Models\LevelModel::LEVEL_PARTNERSHIP) $currentRoles[] = 'admin-partnership';
                                                    if ($lid == \App\Models\LevelModel::LEVEL_ACCOUNTING) $currentRoles[] = 'accounting';
                                                    if ($lid == \App\Models\LevelModel::LEVEL_ADMIN) $currentRoles[] = 'admin';
                                                }
                                            }

                                            // Pro flag check
                                            if (!empty($user['is_pro']) && $user['is_pro'] == 1) {
                                                if (!in_array('user_pro', $currentRoles)) $currentRoles[] = 'user_pro';
                                            }
                                            ?>
                                            <?php foreach ($allRoles as $key => $label): ?>
                                                <label class="flex items-center gap-2 cursor-pointer">
                                                    <input type="checkbox" name="roles[]" value="<?= $key ?>"
                                                        class="rounded bg-gray-800 border-gray-600 text-accent focus:ring-accent"
                                                        <?= in_array($key, $currentRoles) ? 'checked' : '' ?>>
                                                    <span><?= $label ?></span>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Level User (Standard) selalu aktif.</p>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Status</label>
                                        <select name="status" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
                                            <option value="active" <?= ($user['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Active</option>
                                            <option value="inactive" <?= ($user['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <a href="<?= base_url('superadmin/users') ?>" class="flex-1 py-3 border border-white/20 rounded-xl text-center hover:bg-white/5 transition">
                                    Batal
                                </a>
                                <button type="submit" class="flex-1 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                                    <i class="fas fa-save mr-2"></i> Update
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </section>
        </main>
    </div>
</body>

</html>