<?= $this->extend('user/partials/layout') ?>

<?= $this->section('content') ?>
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>

    </div>
</div>

<?php if (empty($transaksiList)): ?>
    <div class="bg-[#111] border border-white/10 rounded-xl p-12 text-center">
        <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-receipt text-gray-600 text-3xl"></i>
        </div>
        <h3 class="font-bold mb-2">Belum Ada Transaksi</h3>
        <p class="text-gray-500 text-sm mb-4">Anda belum memiliki riwayat transaksi</p>
        <a href="<?= base_url('kelas') ?>" class="inline-block px-6 py-2 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
            <i class="fas fa-graduation-cap mr-2"></i> Jelajahi Kelas
        </a>
    </div>
<?php else: ?>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
        <?php
        $totalAll = count($transaksiList);
        $totalPending = count(array_filter($transaksiList, fn($t) => $t['status'] === 'pending'));
        $totalConfirmed = count(array_filter($transaksiList, fn($t) => $t['status'] === 'confirmed'));
        $totalSpent = array_sum(array_map(fn($t) => $t['status'] === 'confirmed' ? $t['total'] : 0, $transaksiList));
        ?>
        <div class="bg-[#111] border border-white/10 rounded-xl p-4">
            <p class="text-2xl font-bold"><?= $totalAll ?></p>
            <p class="text-xs text-gray-500">Total Transaksi</p>
        </div>
        <div class="bg-[#111] border border-white/10 rounded-xl p-4">
            <p class="text-2xl font-bold text-yellow-400"><?= $totalPending ?></p>
            <p class="text-xs text-gray-500">Menunggu Bayar</p>
        </div>
        <div class="bg-[#111] border border-white/10 rounded-xl p-4">
            <p class="text-2xl font-bold text-accent"><?= $totalConfirmed ?></p>
            <p class="text-xs text-gray-500">Selesai</p>
        </div>
        <div class="bg-[#111] border border-white/10 rounded-xl p-4">
            <p class="text-lg font-bold text-purple-400">Rp <?= number_format($totalSpent / 1000000, 1) ?>jt</p>
            <p class="text-xs text-gray-500">Total Belanja</p>
        </div>
    </div>

    <div class="bg-[#111] border border-white/10 rounded-xl overflow-hidden">
        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-black/50">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-400">Invoice</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-400">Produk</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-400">Total</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-400">Metode</th>
                        <th class="text-left px-6 py-3 text-xs font-medium text-gray-400">Status</th>
                        <th class="text-center px-6 py-3 text-xs font-medium text-gray-400">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transaksiList as $trx): ?>
                        <?php
                        $statusColors = [
                            'pending' => 'bg-yellow-500/20 text-yellow-500',
                            'paid' => 'bg-blue-500/20 text-blue-500',
                            'confirmed' => 'bg-accent/20 text-accent',
                            'cancelled' => 'bg-red-500/20 text-red-500',
                            'refunded' => 'bg-purple-500/20 text-purple-500'
                        ];
                        $statusText = [
                            'pending' => 'Belum Bayar',
                            'paid' => 'Diproses',
                            'confirmed' => 'Selesai',
                            'cancelled' => 'Batal',
                            'refunded' => 'Refund'
                        ];
                        $methodLabels = [
                            'xendit' => 'Payment Gateway',
                            'transfer' => 'Transfer Bank',
                            'poin' => 'Almai Poin'
                        ];
                        ?>
                        <tr class="border-t border-white/5 hover:bg-white/5">
                            <td class="px-6 py-4">
                                <p class="text-sm font-mono text-accent"><?= esc($trx['invoice_number']) ?></p>
                                <p class="text-xs text-gray-500"><?= date('d M Y, H:i', strtotime($trx['created_at'])) ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium line-clamp-1"><?= esc($trx['product_name']) ?></p>
                                <p class="text-xs text-gray-500"><?= ucfirst($trx['product_type']) ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold">Rp <?= number_format($trx['total'], 0, ',', '.') ?></p>
                                <?php if ($trx['discount'] > 0): ?>
                                    <p class="text-xs text-red-400">-Rp <?= number_format($trx['discount'], 0, ',', '.') ?></p>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs text-gray-400">
                                    <?php if ($trx['payment_method'] === 'xendit'): ?>
                                        <i class="fas fa-credit-card mr-1 text-blue-400"></i>
                                    <?php elseif ($trx['payment_method'] === 'transfer'): ?>
                                        <i class="fas fa-university mr-1 text-accent"></i>
                                    <?php elseif ($trx['payment_method'] === 'poin'): ?>
                                        <i class="fas fa-coins mr-1 text-yellow-400"></i>
                                    <?php endif; ?>
                                    <?= $methodLabels[$trx['payment_method']] ?? ucfirst($trx['payment_method']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold <?= $statusColors[$trx['status']] ?? 'bg-gray-500/20 text-gray-500' ?>">
                                    <?= $statusText[$trx['status']] ?? strtoupper($trx['status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?= base_url('user/invoice/' . $trx['invoice_number']) ?>" class="p-2 bg-accent/20 text-accent rounded-lg hover:bg-accent hover:text-black transition" title="Lihat Invoice">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    <?php if ($trx['status'] === 'confirmed'): ?>
                                        <a href="<?= base_url('user/transaksi/download-perjanjian/' . $trx['invoice_number']) ?>" class="p-2 bg-purple-500/20 text-purple-400 rounded-lg hover:bg-purple-500 hover:text-white transition" title="Download Perjanjian">
                                            <i class="fas fa-file-contract text-xs"></i>
                                        </a>
                                        <a href="<?= base_url('user/transaksi/download-risiko/' . $trx['invoice_number']) ?>" class="p-2 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500 hover:text-white transition" title="Download Pemberitahuan Risiko">
                                            <i class="fas fa-triangle-exclamation text-xs"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($trx['status'] === 'pending'): ?>
                                        <?php
                                        $xenditLink = null;
                                        if (!empty($trx['notes']) && strpos($trx['notes'], 'Xendit ID:') !== false) {
                                            preg_match('/Xendit ID: ([a-z0-9]+)/', $trx['notes'], $matches);
                                            if (isset($matches[1])) {
                                                $xenditId = $matches[1];
                                                $domain = (ENVIRONMENT === 'production') ? 'checkout.xendit.co' : 'checkout-staging.xendit.co';
                                                $xenditLink = "https://{$domain}/web/{$xenditId}";
                                            }
                                        }
                                        ?>
                                        <?php if ($xenditLink): ?>
                                            <a href="<?= $xenditLink ?>" target="_blank" class="p-2 bg-blue-500/20 text-blue-400 rounded-lg hover:bg-blue-500 hover:text-white transition" title="Bayar Sekarang di Xendit">
                                                <i class="fas fa-credit-card text-xs"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= base_url('user/invoice/' . $trx['invoice_number']) ?>" class="p-2 bg-blue-500/20 text-blue-400 rounded-lg hover:bg-blue-500 hover:text-white transition" title="Lihat Invoice & Bayar">
                                                <i class="fas fa-credit-card text-xs"></i>
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden divide-y divide-white/5">
            <?php foreach ($transaksiList as $trx): ?>
                <?php
                $statusColors = [
                    'pending' => 'bg-yellow-500/20 text-yellow-500',
                    'paid' => 'bg-blue-500/20 text-blue-500',
                    'confirmed' => 'bg-accent/20 text-accent',
                    'cancelled' => 'bg-red-500/20 text-red-500',
                    'refunded' => 'bg-purple-500/20 text-purple-500'
                ];
                $statusText = [
                    'pending' => 'Belum Bayar',
                    'paid' => 'Diproses',
                    'confirmed' => 'Selesai',
                    'cancelled' => 'Batal',
                    'refunded' => 'Refund'
                ];
                ?>
                <div class="p-4">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <p class="text-xs font-mono text-accent"><?= esc($trx['invoice_number']) ?></p>
                            <p class="text-[10px] text-gray-500"><?= date('d M Y, H:i', strtotime($trx['created_at'])) ?></p>
                        </div>
                        <span class="px-2 py-1 rounded-full text-[10px] font-bold <?= $statusColors[$trx['status']] ?? 'bg-gray-500/20 text-gray-500' ?>">
                            <?= $statusText[$trx['status']] ?? strtoupper($trx['status']) ?>
                        </span>
                    </div>

                    <p class="font-medium text-sm mb-1"><?= esc($trx['product_name']) ?></p>
                    <p class="text-xs text-gray-500 mb-3"><?= ucfirst($trx['product_type']) ?></p>

                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-bold text-accent">Rp <?= number_format($trx['total'], 0, ',', '.') ?></p>
                            <?php if ($trx['discount'] > 0): ?>
                                <p class="text-[10px] text-red-400">Diskon -Rp <?= number_format($trx['discount'], 0, ',', '.') ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="<?= base_url('user/invoice/' . $trx['invoice_number']) ?>" class="px-3 py-2 bg-accent/20 text-accent rounded-lg text-xs font-medium">
                                <i class="fas fa-eye mr-1"></i> Detail
                            </a>
                            <?php if ($trx['status'] === 'confirmed'): ?>
                                <a href="<?= base_url('user/transaksi/download-perjanjian/' . $trx['invoice_number']) ?>" class="p-2 bg-purple-500/20 text-purple-400 rounded-lg" title="Perjanjian">
                                    <i class="fas fa-file-contract"></i>
                                </a>
                                <a href="<?= base_url('user/transaksi/download-risiko/' . $trx['invoice_number']) ?>" class="p-2 bg-red-500/20 text-red-400 rounded-lg" title="Risiko">
                                    <i class="fas fa-triangle-exclamation"></i>
                                </a>
                            <?php endif; ?>
                            <?php if ($trx['status'] === 'pending'): ?>
                                <?php
                                $xenditLink = null;
                                if (!empty($trx['notes']) && strpos($trx['notes'], 'Xendit ID:') !== false) {
                                    preg_match('/Xendit ID: ([a-z0-9]+)/', $trx['notes'], $matches);
                                    if (isset($matches[1])) {
                                        $xenditId = $matches[1];
                                        $domain = (ENVIRONMENT === 'production') ? 'checkout.xendit.co' : 'checkout-staging.xendit.co';
                                        $xenditLink = "https://{$domain}/web/{$xenditId}";
                                    }
                                }
                                ?>
                                <a href="<?= $xenditLink ?: base_url('user/invoice/' . $trx['invoice_number']) ?>" <?= $xenditLink ? 'target="_blank"' : '' ?> class="px-3 py-2 bg-blue-500 text-white rounded-lg text-xs font-medium">
                                    Bayar
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>