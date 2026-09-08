<?= $this->extend('admin_wpa/layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Beli Poin</h1>
            <p class="text-gray-400">Top up poin Anda untuk berbagai transaksi</p>
        </div>
        <a href="<?= base_url('admin-admin-wpa/poin') ?>" class="px-4 py-2 bg-white/10 rounded-xl hover:bg-white/20 transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-500/20 border border-green-500/30 text-green-400 px-4 py-3 rounded-xl">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

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
        <!-- Package Selection -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
            <h2 class="font-bold text-lg mb-4"><i class="fas fa-box text-accent mr-2"></i>Pilih Paket Poin</h2>

            <div class="space-y-3">
                <?php if (!empty($packages)): ?>
                    <?php foreach ($packages as $pkg): ?>
                        <label class="flex items-center justify-between p-4 bg-black/50 border border-white/10 rounded-xl cursor-pointer hover:border-accent/50 transition has-[:checked]:border-accent has-[:checked]:bg-accent/10 group">
                            <div class="flex items-center gap-3">
                                <input type="radio" name="poin_package" value="<?= $pkg['id'] ?>"
                                    data-poin="<?= $pkg['amount'] ?>"
                                    data-bonus="0"
                                    data-price="<?= $pkg['price'] ?>"
                                    class="w-5 h-5 text-accent bg-black border-white/20 focus:ring-accent">
                                <div>
                                    <p class="font-bold text-lg group-hover:text-white transition"><?= number_format($pkg['amount']) ?> Poin</p>
                                    <p class="text-xs text-gray-400">Paket Hemat</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-accent text-lg">Rp <?= number_format($pkg['price'], 0, ',', '.') ?></p>
                            </div>
                        </label>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-6 text-gray-500">
                        <p class="text-sm">Belum ada paket tersedia</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Summary & Payment -->
        <div class="space-y-6">
            <div class="bg-[#111] border border-white/10 rounded-2xl p-6 sticky top-24">
                <h2 class="font-bold text-lg mb-4"><i class="fas fa-receipt text-gray-400 mr-2"></i>Ringkasan Pembayaran</h2>

                <div class="bg-black/50 rounded-xl p-4 mb-6 space-y-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-gray-400">Jumlah Poin:</span>
                        <span class="font-bold text-yellow-500 text-lg" id="selectedPoin">0</span>
                    </div>
                    <div class="flex justify-between items-center text-sm" id="bonusRow" style="display: none;">
                        <span class="text-gray-400">Bonus:</span>
                        <span class="font-bold text-accent" id="selectedBonus">0</span>
                    </div>
                    <div class="h-px bg-white/10 my-2"></div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Total Pembayaran:</span>
                        <span class="font-bold text-xl md:text-2xl" id="selectedPrice">Rp 0</span>
                    </div>
                </div>

                <button onclick="purchasePoin()" id="purchaseBtn" disabled class="w-full py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition disabled:opacity-50 disabled:cursor-not-allowed shadow-[0_0_15px_rgba(51,232,24,0.3)] hover:shadow-[0_0_25px_rgba(51,232,24,0.5)]">
                    <i class="fas fa-credit-card mr-2"></i> Bayar Sekarang
                </button>

                <p class="text-xs text-center text-gray-500 mt-4">
                    <i class="fas fa-lock mr-1"></i> Pembayaran aman & terenkripsi via Xendit
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="toast" class="hidden fixed bottom-10 left-1/2 -translate-x-1/2 px-6 py-3 bg-red-500 text-white font-bold rounded-full shadow-lg z-[120] text-sm">
    <span id="toastMessage">Error!</span>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let selectedPackage = null;

    // Handle package selection
    document.querySelectorAll('input[name="poin_package"]').forEach(radio => {
        radio.addEventListener('change', function() {
            selectedPackage = {
                id: parseInt(this.value),
                poin: parseInt(this.dataset.poin),
                bonus: parseInt(this.dataset.bonus),
                price: parseInt(this.dataset.price)
            };

            document.getElementById('selectedPoin').textContent = selectedPackage.poin.toLocaleString();
            document.getElementById('selectedBonus').textContent = '+' + selectedPackage.bonus.toLocaleString();
            document.getElementById('selectedPrice').textContent = 'Rp ' + selectedPackage.price.toLocaleString('id-ID');

            if (selectedPackage.bonus > 0) {
                document.getElementById('bonusRow').style.display = 'flex';
            } else {
                document.getElementById('bonusRow').style.display = 'none';
            }

            document.getElementById('purchaseBtn').disabled = false;
        });
    });

    function purchasePoin() {
        if (!selectedPackage) {
            showToast('Pilih paket terlebih dahulu');
            return;
        }

        const btn = document.getElementById('purchaseBtn');
        const originalContent = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';

        fetch('<?= base_url('admin-admin-wpa/poin/purchase') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    package_id: selectedPackage.id
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.redirect_url) {
                    window.location.href = data.redirect_url;
                } else {
                    showToast(data.message || 'Gagal memproses pembayaran');
                    btn.disabled = false;
                    btn.innerHTML = originalContent;
                }
            })
            .catch(error => {
                showToast('Terjadi kesalahan jaringan');
                btn.disabled = false;
                btn.innerHTML = originalContent;
            });
    }

    function showToast(message) {
        const toast = document.getElementById('toast');
        document.getElementById('toastMessage').textContent = message;
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 3000);
    }
</script>
<?= $this->endSection() ?>