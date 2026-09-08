<?= $this->extend('admin_wpa/layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center gap-4">
        <a href="<?= base_url('admin-wpa/transaksi') ?>" class="w-10 h-10 flex items-center justify-center bg-white/5 rounded-xl hover:bg-white/10 transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-white">Detail Transaksi</h1>
            <p class="text-gray-400">#<?= esc($transaksi['invoice_number'] ?? $transaksi['external_id'] ?? 'INV-000') ?></p>
        </div>
    </div>
    
    <?php if ($transaksi['status'] === 'pending' || $transaksi['status'] === 'paid'): ?>
        <button onclick="confirmTransaction(<?= $transaksi['id'] ?>)" class="px-6 py-2 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition flex items-center gap-2">
            <i class="fas fa-check-circle"></i> Konfirmasi Transaksi
        </button>
    <?php endif; ?>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
        <!-- Main Details -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 md:p-8">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-white/5">
                <h3 class="font-bold text-lg">Informasi Produk</h3>
                <span class="px-3 py-1 bg-blue-500/10 text-blue-400 rounded-lg text-xs font-bold uppercase tracking-wider"><?= esc($transaksi['product_type']) ?></span>
            </div>
            <div class="flex items-start gap-4 mb-8">
                <div class="w-16 h-16 bg-blue-500/10 rounded-2xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-box text-2xl text-blue-500"></i>
                </div>
                <div>
                    <h4 class="text-xl font-bold text-white"><?= esc($transaksi['product_name']) ?></h4>
                    <p class="text-gray-500 text-sm mt-1">Layanan ID: <?= esc($transaksi['layanan_id']) ?></p>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-[10px] text-gray-500 uppercase font-black mb-1">Status Pembayaran</p>
                    <?php 
                    $statusClass = 'text-gray-400';
                    if ($transaksi['status'] === 'confirmed') $statusClass = 'text-green-400';
                    if ($transaksi['status'] === 'pending') $statusClass = 'text-yellow-400';
                    if ($transaksi['status'] === 'expired' || $transaksi['status'] === 'failed') $statusClass = 'text-red-400';
                    ?>
                    <p class="text-sm font-bold <?= $statusClass ?> uppercase"><?= esc($transaksi['status']) ?></p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-500 uppercase font-black mb-1">Metode Bayar</p>
                    <p class="text-sm font-bold uppercase"><?= esc($transaksi['payment_method']) ?></p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-500 uppercase font-black mb-1">Tanggal Transaksi</p>
                    <p class="text-sm font-bold"><?= date('d M Y H:i', strtotime($transaksi['created_at'])) ?></p>
                </div>
            </div>
        </div>

        <!-- Financial Breakdown -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 md:p-8">
            <h3 class="font-bold text-lg mb-6 pb-4 border-b border-white/5">Rincian Biaya</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-400">Harga Produk</span>
                    <span class="text-white font-medium">Rp <?= number_format($transaksi['amount'], 0, ',', '.') ?></span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-400">Diskon / Potongan</span>
                    <span class="text-red-400 font-medium">- Rp <?= number_format($transaksi['discount'] ?? 0, 0, ',', '.') ?></span>
                </div>
                <div class="pt-4 border-t border-white/5 flex justify-between items-center">
                    <span class="text-lg font-bold">Total Pembayaran</span>
                    <span class="text-2xl font-bold text-blue-400">Rp <?= number_format($transaksi['total'], 0, ',', '.') ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar: Customer & Notes -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
            <h3 class="font-bold text-base mb-4 border-b border-white/5 pb-2">Informasi Pembeli</h3>
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-accent/20 rounded-full flex items-center justify-center text-accent font-bold">
                    <?= strtoupper(substr($transaksi['user_name'] ?? 'G', 0, 2)) ?>
                </div>
                <div class="min-w-0">
                    <p class="text-white font-bold truncate"><?= esc($transaksi['user_name'] ?? 'Guest') ?></p>
                    <p class="text-gray-500 text-xs truncate"><?= esc($transaksi['user_email'] ?? '-') ?></p>
                </div>
            </div>
            <div class="space-y-4">
                <a href="mailto:<?= esc($transaksi['user_email']) ?>" class="flex items-center gap-3 text-sm text-gray-400 hover:text-white transition">
                    <i class="fas fa-envelope w-4 text-center"></i> Kirim Email
                </a>
                <?php if (!empty($transaksi['user_phone'])): ?>
                <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $transaksi['user_phone']) ?>" target="_blank" class="flex items-center gap-3 text-sm text-gray-400 hover:text-white transition">
                    <i class="fab fa-whatsapp w-4 text-center"></i> Hubungi WhatsApp
                </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
            <h3 class="font-bold text-base mb-4 border-b border-white/5 pb-2">Catatan / Bukti</h3>
            <p class="text-sm text-gray-400 italic mb-4">
                <?= !empty($transaksi['notes']) ? nl2br(esc((string)$transaksi['notes'])) : 'Tidak ada catatan.' ?>
            </p>
            <?php if (!empty($transaksi['payment_proof'])): ?>
                <div class="mt-4">
                    <a href="<?= base_url($transaksi['payment_proof']) ?>" target="_blank" class="block w-full text-center py-2 bg-white/5 rounded-lg border border-white/10 text-xs text-gray-400 hover:bg-white/10 transition">
                        Lihat Bukti Transfer
                    </a>
                </div>
            <?php endif; ?>

            <!-- Payment Link (Xendit) -->
            <?php 
            $xenditId = null;
            if (!empty($transaksi['notes']) && preg_match('/Xendit ID:\s*([a-f0-9]+)/i', $transaksi['notes'], $matches)) {
                $xenditId = $matches[1];
            }
            ?>
            <?php if ($xenditId): ?>
                <div class="mt-2">
                    <a href="https://checkout.xendit.co/web/<?= $xenditId ?>" target="_blank" class="block w-full text-center py-2 bg-blue-500/10 rounded-lg border border-blue-500/20 text-xs text-blue-400 hover:bg-blue-500 hover:text-white transition">
                        <i class="fas fa-external-link-alt mr-1"></i> Buka Link Pembayaran
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmTransaction(id) {
    Swal.fire({
        title: 'Konfirmasi Transaksi?',
        text: "Pastikan pembayaran telah diterima dengan benar.",
        icon: 'question',
        background: '#111',
        color: '#fff',
        showCancelButton: true,
        confirmButtonColor: '#059669',
        cancelButtonColor: '#374151',
        confirmButtonText: 'Ya, Konfirmasi!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?= base_url('admin-wpa/transaksi/confirm/') ?>' + id;
        }
    })
}
</script>
<?= $this->endSection() ?>
