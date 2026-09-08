<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>
<?php $pageTitle = 'Riwayat Penggunaan'; $pageSubtitle = 'Voucher: ' . $voucher['code']; ?>

<div class="mb-6">
    <a href="<?= base_url('superadmin/voucher') ?>" class="inline-flex items-center gap-2 text-gray-400 hover:text-accent transition">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<!-- Voucher Info -->
<div class="bg-gradient-to-br from-accent/20 to-accent/5 border border-accent/30 rounded-2xl p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold font-mono text-accent"><?= esc($voucher['code']) ?></h2>
            <p class="text-gray-400"><?= esc($voucher['name']) ?></p>
        </div>
        <div class="flex items-center gap-6">
            <div class="text-center">
                <p class="text-3xl font-bold"><?= $voucher['used_count'] ?></p>
                <p class="text-xs text-gray-500">Digunakan</p>
            </div>
            <?php if ($voucher['usage_limit']): ?>
            <div class="text-center">
                <p class="text-3xl font-bold text-gray-500"><?= $voucher['usage_limit'] ?></p>
                <p class="text-xs text-gray-500">Kuota</p>
            </div>
            <?php endif; ?>
            <div class="text-center">
                <?php if ($voucher['discount_type'] === 'percentage'): ?>
                <p class="text-3xl font-bold text-yellow-500"><?= $voucher['discount_value'] ?>%</p>
                <?php else: ?>
                <p class="text-xl font-bold text-yellow-500">Rp <?= number_format($voucher['discount_value'], 0, ',', '.') ?></p>
                <?php endif; ?>
                <p class="text-xs text-gray-500">Diskon</p>
            </div>
        </div>
    </div>
</div>

<!-- Usage List -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-black/50">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400 w-12">No</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">User</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Invoice</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Diskon</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($usages)): ?>
                <tr>
                    <td colspan="5" class="px-4 py-12 text-center text-gray-500">
                        <i class="fas fa-history text-4xl mb-4"></i>
                        <p>Belum ada yang menggunakan voucher ini</p>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($usages as $index => $usage): ?>
                <tr class="border-t border-white/5 hover:bg-white/5">
                    <td class="px-4 py-3 text-gray-400 text-sm"><?= $index + 1 ?></td>
                    <td class="px-4 py-3">
                        <p class="font-medium text-sm"><?= esc($usage['user_name'] ?? 'Unknown') ?></p>
                        <p class="text-xs text-gray-500"><?= esc($usage['user_email'] ?? '-') ?></p>
                    </td>
                    <td class="px-4 py-3">
                        <?php if ($usage['invoice_number']): ?>
                        <a href="<?= base_url('superadmin/transaksi/detail/' . $usage['transaksi_id']) ?>" class="text-accent hover:underline font-mono text-sm">
                            <?= esc($usage['invoice_number']) ?>
                        </a>
                        <?php else: ?>
                        <span class="text-gray-500">-</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-yellow-500 font-bold">- Rp <?= number_format($usage['discount_amount'], 0, ',', '.') ?></span>
                    </td>
                    <td class="px-4 py-3 text-gray-400 text-sm">
                        <?= date('d M Y H:i', strtotime($usage['created_at'])) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
