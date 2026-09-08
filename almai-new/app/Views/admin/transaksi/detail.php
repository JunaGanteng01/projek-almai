<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<?php $pageTitle = 'Detail Transaksi';
$pageSubtitle = $transaksi['invoice_number']; ?>

<div class="mb-6">
    <a href="<?= base_url('admin/transaksi') ?>" class="inline-flex items-center gap-2 text-gray-400 hover:text-accent transition">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Transaksi
    </a>
</div>

<div class="grid md:grid-cols-3 gap-6">
    <!-- Main Info -->
    <div class="md:col-span-2 bg-[#111] border border-white/10 rounded-2xl p-6">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-accent"><?= esc($transaksi['invoice_number']) ?></h1>
                <p class="text-gray-500 text-sm"><?= date('d M Y H:i:s', strtotime($transaksi['created_at'])) ?></p>
            </div>
            <?php
            $statusColors = [
                'pending' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                'paid' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                'confirmed' => 'bg-accent/20 text-accent border-accent/30',
                'cancelled' => 'bg-red-500/20 text-red-400 border-red-500/30',
                'refunded' => 'bg-gray-500/20 text-gray-400 border-gray-500/30',
            ];
            $color = $statusColors[$transaksi['status']] ?? 'bg-gray-500/20 text-gray-400 border-gray-500/30';
            ?>
            <span class="px-4 py-2 rounded-full text-sm font-bold border <?= $color ?>"><?= ucfirst($transaksi['status']) ?></span>
        </div>

        <div class="space-y-4">
            <div class="flex justify-between py-3 border-b border-white/10">
                <span class="text-gray-400">User</span>
                <span class="font-medium"><?= esc($transaksi['user_name'] ?? 'Unknown') ?></span>
            </div>
            <div class="flex justify-between py-3 border-b border-white/10">
                <span class="text-gray-400">Email</span>
                <span><?= esc($transaksi['user_email'] ?? '-') ?></span>
            </div>
            <div class="flex justify-between py-3 border-b border-white/10">
                <span class="text-gray-400">Produk</span>
                <span class="font-medium"><?= esc($transaksi['product_name']) ?></span>
            </div>
            <div class="flex justify-between py-3 border-b border-white/10">
                <span class="text-gray-400">Tipe Produk</span>
                <span class="px-2 py-1 bg-purple-500/20 text-purple-400 rounded-full text-xs"><?= ucfirst($transaksi['product_type']) ?></span>
            </div>
            <div class="flex justify-between py-3 border-b border-white/10">
                <span class="text-gray-400">Harga</span>
                <span>
                    <?php if (($transaksi['payment_method'] ?? '') === 'poin'): ?>
                        <span class="text-yellow-400"><?= number_format($transaksi['amount'], 0, ',', '.') ?> <i class="fas fa-coins ml-1"></i></span>
                    <?php else: ?>
                        Rp <?= number_format($transaksi['amount'], 0, ',', '.') ?>
                    <?php endif; ?>
                </span>
            </div>
            <?php if ($transaksi['discount'] > 0): ?>
                <div class="flex justify-between py-3 border-b border-white/10">
                    <span class="text-gray-400">Diskon</span>
                    <span class="text-red-400">- 
                        <?php if (($transaksi['payment_method'] ?? '') === 'poin'): ?>
                            <span class="text-yellow-400"><?= number_format($transaksi['discount'], 0, ',', '.') ?> <i class="fas fa-coins ml-1"></i></span>
                        <?php else: ?>
                            Rp <?= number_format($transaksi['discount'], 0, ',', '.') ?>
                        <?php endif; ?>
                    </span>
                </div>
            <?php endif; ?>
            <div class="flex justify-between py-3 border-b border-white/10">
                <span class="text-gray-400 font-bold">Total Bayar</span>
                <span class="font-bold text-xl">
                    <?php if (($transaksi['payment_method'] ?? '') === 'poin'): ?>
                        <span class="text-yellow-400"><?= number_format($transaksi['total'], 0, ',', '.') ?> <i class="fas fa-coins ml-1"></i></span>
                    <?php else: ?>
                        <span class="text-accent">Rp <?= number_format($transaksi['total'], 0, ',', '.') ?></span>
                    <?php endif; ?>
                </span>
            </div>
        </div>

        <?php if ($transaksi['notes']): ?>
            <div class="bg-black/50 rounded-xl p-4 mt-6">
                <p class="text-gray-400 text-sm mb-1"><i class="fas fa-sticky-note mr-2"></i>Catatan:</p>
                <p class="text-sm"><?= esc($transaksi['notes']) ?></p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar Info -->
    <div class="space-y-4">
        <!-- Payment Info -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
            <h3 class="font-bold mb-4"><i class="fas fa-credit-card mr-2 text-accent"></i>Info Pembayaran</h3>
            <div class="space-y-3 text-sm">
                <?php if ($transaksi['payment_method']): ?>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Metode</span>
                        <span><?= esc($transaksi['payment_method']) ?></span>
                    </div>
                <?php endif; ?>
                <?php if ($transaksi['paid_at']): ?>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Dibayar</span>
                        <span class="text-accent"><?= date('d M Y H:i', strtotime($transaksi['paid_at'])) ?></span>
                    </div>
                <?php endif; ?>
                <?php if ($transaksi['confirmed_at']): ?>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Dikonfirmasi</span>
                        <span class="text-accent"><?= date('d M Y H:i', strtotime($transaksi['confirmed_at'])) ?></span>
                    </div>
                <?php endif; ?>
                <?php if (!$transaksi['paid_at'] && !$transaksi['confirmed_at']): ?>
                    <p class="text-gray-500 text-center py-2">Belum ada pembayaran</p>
                <?php endif; ?>

                <?php
                // Extract Xendit Link from Notes if available
                $xenditLink = null;
                if (strpos($transaksi['notes'], 'Xendit ID:') !== false) {
                    preg_match('/Xendit ID: ([a-z0-9]+)/', $transaksi['notes'], $matches);
                    if (isset($matches[1])) {
                        $xenditId = $matches[1];
                        $domain = (ENVIRONMENT === 'production') ? 'checkout.xendit.co' : 'checkout-staging.xendit.co';
                        $xenditLink = "https://{$domain}/web/{$xenditId}";
                    }
                }
                ?>

                <?php if ($xenditLink): ?>
                    <div class="pt-4 mt-4 border-t border-white/10 text-center">
                        <a href="<?= $xenditLink ?>" target="_blank" class="inline-flex items-center gap-2 text-accent hover:underline font-bold">
                            <i class="fas fa-external-link-alt"></i>
                            Link Pembayaran Xendit
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Update Status -->
        <?php if ($canWrite ?? false): ?>
            <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
                <h3 class="font-bold mb-4"><i class="fas fa-edit mr-2 text-accent"></i>Update Status</h3>
                <form action="<?= base_url('admin/transaksi/update-status/' . $transaksi['id']) ?>" method="post">
                    <?= csrf_field() ?>
                    <select name="status" class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none mb-3">
                        <option value="pending" <?= $transaksi['status'] === 'pending' ? 'selected' : '' ?>>⏳ Pending</option>
                        <option value="paid" <?= $transaksi['status'] === 'paid' ? 'selected' : '' ?>>💳 Paid</option>
                        <option value="confirmed" <?= $transaksi['status'] === 'confirmed' ? 'selected' : '' ?>>✅ Confirmed</option>
                        <option value="cancelled" <?= $transaksi['status'] === 'cancelled' ? 'selected' : '' ?>>❌ Cancelled</option>
                        <option value="refunded" <?= $transaksi['status'] === 'refunded' ? 'selected' : '' ?>>↩️ Refunded</option>
                    </select>
                    <button type="submit" class="w-full py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                        <i class="fas fa-save mr-2"></i>Update Status
                    </button>
                </form>
            </div>
        <?php endif; ?>

        <!-- Email Status -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
            <h3 class="font-bold mb-4"><i class="fas fa-envelope-open-text mr-2 text-accent"></i>Email & Status Dokumen</h3>
            <div class="space-y-4">
                <?php
                $emails = [
                    'mail_invoice_pending' => ['label' => 'Invoice Pembayaran', 'icon' => 'fa-file-invoice-dollar', 'type' => 'invoice_pending'],
                    'mail_invoice_success' => ['label' => 'Invoice Sukses', 'icon' => 'fa-check-circle', 'type' => 'invoice_success'],
                    'mail_legal_pemberian_jasa' => ['label' => 'Legal & Dokumen', 'icon' => 'fa-file-contract', 'type' => 'legal', 'is_group' => true],
                ];

                foreach ($emails as $key => $info):
                    $status = $transaksi[$key] ?? 'not_sent';
                    $statusColor = 'text-gray-500';
                    $statusIcon = 'fa-clock';

                    if ($status === 'sent') {
                        $statusColor = 'text-accent';
                        $statusIcon = 'fa-check-circle';
                    } elseif ($status === 'failed') {
                        $statusColor = 'text-red-400';
                        $statusIcon = 'fa-times-circle';
                    }
                ?>
                    <div class="space-y-2 pb-4 border-b border-white/5 last:border-0 last:pb-0">
                        <div class="flex items-center justify-between group">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-gray-400 group-hover:text-accent transition">
                                    <i class="fas <?= $info['icon'] ?> text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-300"><?= $info['label'] ?></p>
                                    <p class="text-[10px] <?= $statusColor ?>"><?= ucfirst($status) ?></p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="<?= $statusColor ?> <?= $status === 'failed' ? 'animate-pulse' : '' ?>">
                                    <i class="fas <?= $statusIcon ?>"></i>
                                </div>
                                <form action="<?= base_url('admin/transaksi/resend-email/' . $transaksi['id']) ?>" method="post" class="inline">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="type" value="<?= $info['type'] ?>">
                                    <button type="submit" class="p-1.5 rounded-lg bg-white/5 hover:bg-accent hover:text-black text-gray-400 transition" title="Kirim Ulang Email">
                                        <i class="fas fa-redo-alt text-[10px]"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <?php if (isset($info['is_group'])): ?>
                            <div class="pl-11 grid grid-cols-2 gap-2 mt-1">
                                <?php
                                $subDocs = [
                                    'mail_legal_profil' => 'Profil',
                                    'mail_legal_risiko' => 'Risiko',
                                ];
                                if ($transaksi['product_type'] === 'cwpa') {
                                    $subDocs['mail_legal_wpa'] = 'WPA';
                                }
                                foreach ($subDocs as $subKey => $subLabel):
                                    $subStatus = $transaksi[$subKey] ?? 'not_sent';
                                    $subColor = $subStatus === 'sent' ? 'text-accent' : ($subStatus === 'failed' ? 'text-red-400' : 'text-gray-600');
                                ?>
                                    <div class="flex items-center gap-1.5">
                                        <i class="fas fa-circle text-[4px] <?= $subColor ?>"></i>
                                        <span class="text-[9px] <?= $subColor ?>"><?= $subLabel ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Quick Actions (Super Admin Only) -->
        <?php if ($isSuperAdmin ?? false): ?>
            <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
                <h3 class="font-bold mb-4"><i class="fas fa-bolt mr-2 text-accent"></i>Aksi Cepat</h3>
                <div class="space-y-2">
                    <?php if ($transaksi['user_id']): ?>
                        <a href="<?= base_url('admin/users/view/' . $transaksi['user_id']) ?>" class="flex items-center gap-3 p-3 bg-black/50 rounded-xl hover:bg-white/5 transition">
                            <i class="fas fa-user text-blue-400"></i>
                            <span class="text-sm">Lihat Profil User</span>
                        </a>
                    <?php endif; ?>
                    <?php if ($canWrite ?? false): ?>
                        <form action="<?= base_url('admin/transaksi/delete/' . $transaksi['id']) ?>" method="post" onsubmit="return confirm('Hapus transaksi ini?')">
                            <?= csrf_field() ?>
                            <button type="submit" class="w-full flex items-center gap-3 p-3 bg-black/50 rounded-xl hover:bg-red-500/10 transition text-left">
                                <i class="fas fa-trash text-red-400"></i>
                                <span class="text-sm text-red-400">Hapus Transaksi</span>
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>