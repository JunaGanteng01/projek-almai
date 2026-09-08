<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Tambah User - Admin Dashboard') ?></title>
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
        <?= $this->include('admin/partials/sidebar') ?>

        <main class="flex-1 md:ml-64 pt-14 md:pt-0">
            <header class="bg-[#0a0a0a] border-b border-white/10 px-4 md:px-8 py-4 flex items-center gap-4 sticky top-14 md:top-0 z-10">
                <a href="<?= base_url('admin/users') ?>" class="p-2 hover:bg-white/10 rounded-lg transition">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-lg md:text-xl font-bold">Tambah User Baru</h1>
                    <p class="text-xs md:text-sm text-gray-500 hidden md:block">Buat akun pengguna baru</p>
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

                <form action="<?= base_url('admin/users/store') ?>" method="post" class="max-w-xl">
                    <?= csrf_field() ?>

                    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-6">
                        <h3 class="font-bold mb-4 flex items-center gap-2">
                            <i class="fas fa-user text-accent"></i> Informasi User
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Nama Lengkap *</label>
                                <input type="text" name="name" required value="<?= old('name') ?>"
                                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                                    placeholder="Nama lengkap">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Email *</label>
                                <input type="email" name="email" required value="<?= old('email') ?>"
                                    class="w-full bg-black border <?= (session()->getFlashdata('errors')['email'] ?? false) ? 'border-red-500' : 'border-white/20' ?> rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                                    placeholder="email@example.com">
                                <?php if ($emailError = session()->getFlashdata('errors')['email'] ?? false): ?>
                                    <p class="text-red-400 text-xs mt-1">
                                        <i class="fas fa-exclamation-circle mr-1"></i><?= esc($emailError) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">No. WhatsApp *</label>
                                <input type="tel" name="phone" required value="<?= old('phone') ?>"
                                    class="w-full bg-black border <?= (session()->getFlashdata('errors')['phone'] ?? false) ? 'border-red-500' : 'border-white/20' ?> rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                                    placeholder="08xxxxxxxxxx">
                                <?php if ($phoneError = session()->getFlashdata('errors')['phone'] ?? false): ?>
                                    <p class="text-red-400 text-xs mt-1">
                                        <i class="fas fa-exclamation-circle mr-1"></i><?= esc($phoneError) ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            <div class="bg-blue-500/10 border border-blue-500/30 rounded-xl p-4">
                                <p class="text-blue-400 text-sm">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <strong>Password Otomatis:</strong> Sistem akan membuat kode OTP 6 digit sebagai password dan mengirimkannya via WhatsApp & Email ke user.
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Role *</label>
                                <?php if (isset($isRoleReadOnly) && $isRoleReadOnly): ?>
                                    <input type="hidden" name="role" value="<?= esc($defaultRole) ?>">
                                    <input type="text" value="<?= strtoupper($defaultRole) ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-gray-500 cursor-not-allowed" readonly>
                                    <p class="text-xs text-gray-500 mt-1">Role dikunci ke User untuk Admin.</p>
                                <?php else: ?>
                                    <select name="role" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
                                        <option value="user" <?= old('role') === 'user' ? 'selected' : '' ?>>User</option>
                                        <option value="wpa" <?= old('role') === 'wpa' ? 'selected' : '' ?>>WPA</option>
                                        <option value="cwpa" <?= old('role') === 'cwpa' ? 'selected' : '' ?>>CWPA</option>
                                        <option value="admin-wpa" <?= old('role') === 'admin-wpa' ? 'selected' : '' ?>>Admin WPA</option>
                                        <option value="admin-partnership" <?= old('role') === 'admin-partnership' ? 'selected' : '' ?>>Admin Partnership</option>
                                        <option value="accounting" <?= old('role') === 'accounting' ? 'selected' : '' ?>>Keuangan (Accounting)</option>
                                        <option value="admin" <?= old('role') === 'admin' ? 'selected' : '' ?>>Admin</option>
                                    </select>
                                <?php endif; ?>
                            </div>

                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Afilator / Upline (Opsional)</label>
                                <?php if (isset($isAffiliatorReadOnly) && $isAffiliatorReadOnly && isset($defaultAffiliatorId)): ?>
                                    <input type="hidden" name="affiliator_id" value="<?= esc($defaultAffiliatorId) ?>">
                                    <?php
                                    $affName = '-';
                                    $affCode = '-';
                                    if (!empty($affiliators)) {
                                        foreach ($affiliators as $af) {
                                            if ($af['id'] == $defaultAffiliatorId) {
                                                $affName = $af['name'];
                                                $affCode = $af['code_referral'] ?? '-';
                                                break;
                                            }
                                        }
                                    }
                                    ?>
                                    <input type="text" value="<?= esc($affName) ?> (<?= esc($affCode) ?>)" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-gray-500 cursor-not-allowed" readonly>
                                    <p class="text-xs text-gray-500 mt-1">User baru otomatis menjadi downline Anda.</p>
                                <?php else: ?>
                                    <select name="affiliator_id" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
                                        <option value="">-- Pilih Afilator --</option>
                                        <?php if (!empty($affiliators)): ?>
                                            <?php foreach ($affiliators as $af): ?>
                                                <option value="<?= $af['id'] ?>" <?= (old('affiliator_id') == $af['id'] || (isset($defaultAffiliatorId) && $defaultAffiliatorId == $af['id'])) ? 'selected' : '' ?>>
                                                    <?= esc($af['name']) ?> (<?= esc($af['code_referral'] ?? '-') ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Pilih bebas siapa yang akan menjadi upline/affiliator dari user baru. Kode referral user akan mengikuti affiliator yang dipilih.</p>
                                <?php endif; ?>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Status</label>
                                <select name="status" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <a href="<?= base_url('admin/users') ?>" class="flex-1 py-4 border border-white/20 rounded-xl text-center hover:bg-white/5 transition">Batal</a>
                        <button type="submit" class="flex-1 py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                            <i class="fas fa-save mr-2"></i> Simpan
                        </button>
                    </div>
                </form>
            </section>
        </main>
    </div>
</body>

</html>