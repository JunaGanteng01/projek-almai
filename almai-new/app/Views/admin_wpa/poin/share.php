<?= $this->extend('admin_wpa/layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Berbagi Poin</h1>
            <p class="text-gray-400">Bagikan poin ke user lain dengan link atau QR Code</p>
        </div>
        <a href="<?= base_url('admin-admin-wpa/poin') ?>" class="px-4 py-2 bg-white/10 rounded-xl hover:bg-white/20 transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>



    <!-- Saldo Poin -->
    <div class="bg-gradient-to-r from-yellow-500/20 to-orange-500/20 border border-yellow-500/30 rounded-2xl p-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-yellow-500/30 rounded-xl flex items-center justify-center">
                <i class="fas fa-coins text-yellow-400 text-2xl"></i>
            </div>
            <div>
                <p class="text-gray-400 text-sm">Saldo Poin Anda</p>
                <p class="text-3xl font-bold text-yellow-400"><?= number_format($poinBalance) ?> <span class="text-lg">Poin</span></p>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Form Buat Link -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
            <h2 class="font-bold text-lg mb-4"><i class="fas fa-share-alt text-accent mr-2"></i>Buat Link Berbagi</h2>

            <form action="<?= base_url('admin-admin-wpa/poin/share/store') ?>" method="post">
                <?= csrf_field() ?>

                <!-- Pilih Paket -->
                <div class="mb-4">
                    <label class="block text-sm text-gray-400 mb-2">Pilih Paket Service Fee</label>
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <?php foreach ($packages as $pkg): ?>
                            <label class="package-option cursor-pointer">
                                <input type="radio" name="package_id" value="<?= $pkg['id'] ?>" class="hidden peer" onchange="selectPackage(<?= $pkg['amount'] ?>, <?= $pkg['price'] ?>)">
                                <div class="p-3 bg-black border-2 border-white/20 rounded-xl peer-checked:border-accent peer-checked:bg-accent/10 transition">
                                    <p class="font-bold text-accent"><?= number_format($pkg['amount']) ?> Poin</p>
                                    <p class="text-xs text-gray-400">Rp <?= number_format($pkg['price'], 0, ',', '.') ?></p>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Custom Amount -->
                <div class="mb-4 p-4 bg-black/50 rounded-xl border border-white/10">
                    <label class="flex items-center gap-2 mb-3 cursor-pointer">
                        <input type="checkbox" id="customToggle" onchange="toggleCustom()" class="w-4 h-4 accent-accent">
                        <span class="text-sm">Atau masukkan jumlah custom</span>
                    </label>
                    <div id="customFields" class="hidden grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Jumlah Poin</label>
                            <input type="number" name="custom_poin" id="customPoin" min="100" placeholder="Min 100"
                                class="w-full px-3 py-2 bg-black border border-white/20 rounded-lg focus:border-accent focus:outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">Harga (Rp)</label>
                            <input type="number" name="custom_price" id="customPrice" min="0" placeholder="Opsional"
                                class="w-full px-3 py-2 bg-black border border-white/20 rounded-lg focus:border-accent focus:outline-none text-sm">
                        </div>
                    </div>
                </div>

                <!-- Bank Info -->
                <div class="mb-4">
                    <label class="block text-sm text-gray-400 mb-2">Rekening Transfer <span class="text-red-400">*</span></label>
                    <div class="space-y-3">
                        <?php if(empty($userData['bank_name']) || empty($userData['account_name']) || empty($userData['account_number'])): ?>
                            <div class="p-3 bg-red-500/20 text-red-400 border border-red-500/30 rounded-lg text-sm mb-3">
                                <i class="fas fa-exclamation-triangle mr-2"></i> Data rekening Anda belum lengkap. Silakan lengkapi di menu Profil.
                            </div>
                        <?php endif; ?>
                        <input type="text" name="bank_name" readonly required placeholder="Nama Bank / E-Wallet" value="<?= esc($userData['bank_name'] ?? '') ?>"
                            class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl opacity-70 cursor-not-allowed focus:outline-none">
                        <input type="text" name="bank_account_name" readonly required placeholder="Nama Pemilik Rekening" value="<?= esc($userData['account_name'] ?? '') ?>"
                            class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl opacity-70 cursor-not-allowed focus:outline-none">
                        <input type="text" name="bank_account_number" readonly required placeholder="Nomor Rekening" value="<?= esc($userData['account_number'] ?? '') ?>"
                            class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl opacity-70 cursor-not-allowed focus:outline-none">
                        <p class="text-xs text-gray-500 mt-1">Data rekening diambil otomatis dari data profil Anda.</p>
                    </div>
                </div>

                <button type="submit" class="w-full py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                    <i class="fas fa-link mr-2"></i> Buat Link Berbagi
                </button>
            </form>
        </div>

        <!-- Link Aktif -->
        <div class="space-y-6">
            <!-- Pending Links -->
            <?php if (!empty($pendingSharings)): ?>
                <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
                    <h2 class="font-bold text-lg mb-4"><i class="fas fa-clock text-yellow-400 mr-2"></i>Link Aktif</h2>
                    <div class="space-y-3">
                        <?php foreach ($pendingSharings as $share): ?>
                            <div class="bg-black/50 rounded-xl p-4 border border-white/10">
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <p class="font-bold text-accent"><?= number_format($share['poin_amount']) ?> Poin</p>
                                        <p class="text-sm text-gray-400">Rp <?= number_format($share['price'], 0, ',', '.') ?></p>
                                    </div>
                                    <span class="px-2 py-1 bg-yellow-500/20 text-yellow-400 text-xs rounded-full">Menunggu</span>
                                </div>

                                <!-- QR Code -->
                                <div class="flex justify-center mb-3">
                                    <div class="bg-white p-2 rounded-xl">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=<?= urlencode(base_url('receive-poin/' . $share['share_code'])) ?>"
                                            alt="QR Code" class="w-28 h-28">
                                    </div>
                                </div>

                                <!-- Link -->
                                <div class="flex gap-2 mb-3">
                                    <input type="text" value="<?= base_url('receive-poin/' . $share['share_code']) ?>" readonly
                                        class="flex-1 px-3 py-2 bg-black border border-white/20 rounded-lg text-xs" id="link-<?= $share['id'] ?>">
                                    <button onclick="copyLink('link-<?= $share['id'] ?>')" class="px-3 py-2 bg-accent/20 text-accent rounded-lg hover:bg-accent/30 transition text-sm">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>

                                <div class="flex gap-2">
                                    <a href="<?= base_url('receive-poin/' . $share['share_code']) ?>" target="_blank"
                                        class="flex-1 py-2 bg-white/10 rounded-lg text-center text-sm hover:bg-white/20 transition">
                                        <i class="fas fa-external-link-alt mr-1"></i> Buka
                                    </a>
                                    <button onclick="downloadQR('<?= base_url('receive-poin/' . $share['share_code']) ?>', '<?= $share['share_code'] ?>')"
                                        class="flex-1 py-2 bg-blue-500/20 text-blue-400 rounded-lg text-sm hover:bg-blue-500/30 transition">
                                        <i class="fas fa-download mr-1"></i> Download QR
                                    </button>
                                    <form action="<?= base_url('admin-admin-wpa/poin/share/delete/' . $share['id']) ?>" method="post" class="flex-1">
                                        <?= csrf_field() ?>
                                        <button type="submit" onclick="return confirm('Hapus link ini?')"
                                            class="w-full py-2 bg-red-500/20 text-red-400 rounded-lg text-sm hover:bg-red-500/30 transition">
                                            <i class="fas fa-trash mr-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>

                                <p class="text-xs text-gray-500 mt-2 text-center">
                                    Berlaku sampai: <?= date('d M Y H:i', strtotime($share['expired_at'])) ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Waiting Verification -->
            <?php if (!empty($waitingVerification)): ?>
                <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
                    <h2 class="font-bold text-lg mb-4"><i class="fas fa-check-circle text-orange-400 mr-2"></i>Menunggu Verifikasi</h2>
                    <div class="space-y-3">
                        <?php foreach ($waitingVerification as $share): ?>
                            <div class="bg-black/50 rounded-xl p-4 border border-orange-500/30">
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <p class="font-bold"><?= esc($share['receiver_name'] ?? 'User #' . $share['receiver_id']) ?></p>
                                        <p class="text-sm text-gray-400"><?= esc($share['receiver_email'] ?? '') ?></p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-accent"><?= number_format($share['poin_amount']) ?> Poin</p>
                                        <p class="text-sm text-gray-400">Rp <?= number_format($share['price'], 0, ',', '.') ?></p>
                                    </div>
                                </div>

                                <!-- Transfer Proof -->
                                <?php if ($share['transfer_proof']): ?>
                                    <div class="mb-3">
                                        <p class="text-xs text-gray-400 mb-2">Bukti Transfer:</p>
                                        <a href="<?= base_url('file/uploads/transfer_proofs/' . $share['transfer_proof']) ?>" target="_blank">
                                            <img src="<?= base_url('file/uploads/transfer_proofs/' . $share['transfer_proof']) ?>"
                                                alt="Bukti Transfer" class="max-h-32 rounded-lg border border-white/10">
                                        </a>
                                    </div>
                                <?php endif; ?>

                                <p class="text-xs text-gray-500 mb-3">
                                    Dibayar: <?= date('d M Y H:i', strtotime($share['paid_at'])) ?>
                                </p>

                                <div class="flex gap-2">
                                    <form action="<?= base_url('admin-admin-wpa/poin/share/verify/' . $share['id']) ?>" method="post" class="flex-1">
                                        <?= csrf_field() ?>
                                        <button type="submit" onclick="return confirm('Verifikasi pembayaran ini? Poin akan ditransfer ke penerima.')"
                                            class="w-full py-2 bg-accent text-black font-bold rounded-lg hover:bg-white transition">
                                            <i class="fas fa-check mr-1"></i> Verifikasi
                                        </button>
                                    </form>
                                    <button onclick="showRejectModal(<?= $share['id'] ?>)"
                                        class="flex-1 py-2 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500/30 transition">
                                        <i class="fas fa-times mr-1"></i> Tolak
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (empty($pendingSharings) && empty($waitingVerification)): ?>
                <div class="bg-[#111] border border-white/10 rounded-2xl p-8 text-center">
                    <i class="fas fa-share-alt text-4xl text-gray-600 mb-4"></i>
                    <p class="text-gray-400">Belum ada link berbagi poin aktif</p>
                    <p class="text-sm text-gray-500">Buat link untuk mulai berbagi poin</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-black/80 z-50 hidden items-center justify-center p-4">
    <div class="bg-[#111] rounded-2xl p-6 max-w-md w-full border border-white/10">
        <h3 class="font-bold text-lg mb-4">Tolak Pembayaran</h3>
        <form id="rejectForm" method="post">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="block text-sm text-gray-400 mb-2">Alasan Penolakan</label>
                <textarea name="reason" rows="3" required placeholder="Contoh: Bukti transfer tidak valid, nominal tidak sesuai, dll"
                    class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none"></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="hideRejectModal()" class="flex-1 py-3 bg-white/10 rounded-xl hover:bg-white/20 transition">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-3 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600 transition">
                    Tolak
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function selectPackage(poin, price) {
        document.getElementById('customToggle').checked = false;
        document.getElementById('customFields').classList.add('hidden');
        document.getElementById('customPoin').value = '';
        document.getElementById('customPrice').value = '';
    }

    function toggleCustom() {
        const checked = document.getElementById('customToggle').checked;
        document.getElementById('customFields').classList.toggle('hidden', !checked);

        if (checked) {
            // Uncheck all packages
            document.querySelectorAll('input[name="package_id"]').forEach(r => r.checked = false);
        }
    }

    function copyLink(inputId) {
        const input = document.getElementById(inputId);
        input.select();
        document.execCommand('copy');

        // Show feedback
        const btn = input.nextElementSibling;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        setTimeout(() => btn.innerHTML = originalHtml, 1500);
    }

    function downloadQR(url, code) {
        const qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' + encodeURIComponent(url);
        const link = document.createElement('a');
        link.href = qrUrl;
        link.download = 'qr-poin-' + code + '.png';
        link.click();
    }

    function showRejectModal(id) {
        document.getElementById('rejectForm').action = '<?= base_url('admin-admin-wpa/poin/share/reject/') ?>' + id;
        document.getElementById('rejectModal').classList.remove('hidden');
        document.getElementById('rejectModal').classList.add('flex');
    }

    function hideRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('rejectModal').classList.remove('flex');
    }
</script>
<?= $this->endSection() ?>