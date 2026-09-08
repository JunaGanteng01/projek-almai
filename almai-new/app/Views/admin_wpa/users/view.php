<?= $this->extend('admin_wpa/layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-6 flex items-center gap-4">
    <a href="<?= base_url('admin-wpa/users') ?>" class="w-10 h-10 flex items-center justify-center bg-white/5 rounded-xl hover:bg-white/10 transition">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-white">Profil User</h1>
        <p class="text-gray-400">Informasi detail pengguna di bawah naungan WPA Anda.</p>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <!-- Left Column - Profile Card -->
    <div class="space-y-6">
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 text-center">
            <div class="w-24 h-24 rounded-full bg-accent/20 flex items-center justify-center text-accent font-bold text-3xl mx-auto mb-4 border-4 border-accent/10">
                <?= strtoupper(substr($user['name'], 0, 2)) ?>
            </div>
            <h2 class="text-xl font-bold mb-1"><?= esc($user['name']) ?></h2>
            <p class="text-gray-500 text-sm mb-4"><?= esc($user['email']) ?></p>

            <div class="flex justify-center gap-2 flex-wrap mb-4">
                <span class="px-3 py-1 bg-accent/20 text-accent rounded-full text-xs">
                    <?= esc($user['level_id'] == 2 ? 'User PRO' : 'User') ?>
                </span>
                <span class="px-3 py-1 rounded-full text-xs <?= ($user['status'] ?? 'active') === 'active' ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' ?>">
                    <?= esc(ucfirst($user['status'] ?? 'Active')) ?>
                </span>
            </div>

            <div class="text-left space-y-3 text-sm pt-4 border-t border-white/5">
                <div class="flex items-center gap-3 text-gray-400">
                    <i class="fas fa-phone w-4 text-center"></i>
                    <span><?= esc($user['phone'] ?? '-') ?></span>
                </div>
                <div class="flex items-center gap-3 text-gray-400">
                    <i class="fas fa-calendar w-4 text-center"></i>
                    <span>Bergabung <?= date('d M Y', strtotime($user['created_at'])) ?></span>
                </div>
                <div class="flex items-center gap-3 text-gray-400">
                    <i class="fas fa-link w-4 text-center"></i>
                    <span>Ref: <span class="text-accent font-mono"><?= esc($user['affiliator_code'] ?? '-') ?></span></span>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
            <h3 class="font-bold mb-4 flex items-center gap-2">
                <i class="fas fa-chart-bar text-accent"></i> Ringkasan Akun
            </h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-black/40 rounded-xl p-3">
                    <p class="text-[10px] text-gray-500 uppercase font-black mb-1">Poin</p>
                    <p class="text-lg font-bold text-yellow-500"><?= number_format($poinBalance) ?></p>
                </div>
                <div class="bg-black/40 rounded-xl p-3">
                    <p class="text-[10px] text-gray-500 uppercase font-black mb-1">Saldo</p>
                    <p class="text-lg font-bold text-green-500">Rp <?= number_format($rupiahBalance, 0, ',', '.') ?></p>
                </div>
                <div class="bg-black/40 rounded-xl p-3">
                    <p class="text-[10px] text-gray-500 uppercase font-black mb-1">Downline</p>
                    <p class="text-lg font-bold text-accent"><?= $downlineCount ?></p>
                </div>
                <div class="bg-black/40 rounded-xl p-3">
                    <p class="text-[10px] text-gray-500 uppercase font-black mb-1">Transaksi</p>
                    <p class="text-lg font-bold text-purple-400"><?= $totalTransaksi ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column - Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Purchased Services -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 sm:p-8">
            <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
                <i class="fas fa-shopping-bag text-accent"></i> Riwayat Layanan
            </h3>
            <?php if (empty($purchasedServices)): ?>
                <div class="text-center py-12 text-gray-600">
                    <i class="fas fa-box-open text-4xl mb-4"></i>
                    <p>Belum ada riwayat pembelian layanan.</p>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="text-gray-500 border-b border-white/5">
                            <tr>
                                <th class="text-left font-medium pb-4">Layanan</th>
                                <th class="text-left font-medium pb-4">Invoice</th>
                                <th class="text-left font-medium pb-4">Status</th>
                                <th class="text-right font-medium pb-4">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <?php foreach ($purchasedServices as $trx): ?>
                                <tr class="hover:bg-white/5 transition">
                                    <td class="py-4 font-bold text-white"><?= esc($trx['product_name']) ?></td>
                                    <td class="py-4 font-mono text-xs text-accent">#<?= esc($trx['invoice_number']) ?></td>
                                    <td class="py-4">
                                        <span class="px-2 py-0.5 bg-green-500/10 text-green-500 rounded text-[10px] font-bold uppercase"><?= esc($trx['status']) ?></span>
                                    </td>
                                    <td class="py-4 text-right font-bold">Rp <?= number_format($trx['total'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Downline List -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 sm:p-8">
            <h3 class="text-lg font-bold mb-6 flex items-center gap-2">
                <i class="fas fa-users text-accent"></i> Downline (Tier 1)
            </h3>
            <?php if (empty($downlineList)): ?>
                <div class="text-center py-12 text-gray-600">
                    <i class="fas fa-user-friends text-4xl mb-4"></i>
                    <p>Belum ada downline terdaftar.</p>
                </div>
            <?php else: ?>
                <div class="grid sm:grid-cols-2 gap-4">
                    <?php foreach ($downlineList as $dl): ?>
                        <div class="bg-white/5 border border-white/5 p-4 rounded-xl flex items-center gap-3">
                            <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center font-bold text-black text-xs">
                                <?= strtoupper(substr($dl['name'], 0, 2)) ?>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-white truncate"><?= esc($dl['name']) ?></p>
                                <p class="text-[10px] text-gray-500"><?= date('d/m/Y', strtotime($dl['created_at'])) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if ($downlineCount > 100): ?>
                    <p class="mt-4 text-center text-xs text-gray-500">Dan <?= $downlineCount - 100 ?> downline lainnya...</p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
