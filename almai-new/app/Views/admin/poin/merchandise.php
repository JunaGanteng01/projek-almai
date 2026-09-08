<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Tukar Merchandise</h1>
            <p class="text-gray-400">Tukarkan poin Anda dengan merchandise eksklusif</p>
        </div>
        <a href="<?= base_url('wpa/dashboard/poin') ?>" class="px-4 py-2 bg-white/10 rounded-xl hover:bg-white/20 transition">
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

    <!-- Merchandise Grid -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            <?php if (!empty($merchandise)): ?>
                <?php foreach ($merchandise as $item): ?>
                    <div class="bg-black/50 border border-white/10 rounded-xl p-3 md:p-4 hover:border-accent/50 transition cursor-pointer group <?= $item['points_required'] > $poinBalance ? 'opacity-50' : '' ?>" onclick="selectMerchandise(<?= $item['id'] ?>)">
                        <div class="aspect-square rounded-lg overflow-hidden mb-3 group-hover:scale-105 transition bg-gray-800">
                            <img src="<?= base_url($item['image']) ?>" alt="<?= esc($item['name']) ?>" class="w-full h-full object-cover">
                        </div>
                        <h4 class="font-bold text-sm md:text-base mb-1 truncate"><?= esc($item['name']) ?></h4>
                        <div class="flex items-center justify-between">
                            <span class="text-yellow-500 font-bold text-sm"><i class="fas fa-coins mr-0.5"></i> <?= number_format($item['points_required']) ?></span>
                            <span class="text-xs text-gray-500"><?= $item['unlimited_stock'] ? '∞' : number_format($item['stock']) ?> stok</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-12">
                    <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-box-open text-gray-600 text-2xl"></i>
                    </div>
                    <p class="text-gray-500">Belum ada merchandise tersedia saat ini.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="mt-8 pt-4 border-t border-white/10">
            <p class="text-sm text-gray-500 text-center">
                <i class="fas fa-info-circle mr-1"></i> Merchandise akan dikirim dalam 7-14 hari kerja setelah penukaran dikonfirmasi. Pastikan alamat Anda benar.
            </p>
        </div>
    </div>
</div>

<!-- Merchandise Confirm Modal -->
<div id="merchantConfirmModal" class="fixed inset-0 z-[110] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 max-w-sm w-full transform scale-95 opacity-0 transition-all duration-300" id="merchantConfirmContent">
        <div class="text-center mb-6">
            <div class="w-32 h-32 rounded-xl overflow-hidden mx-auto mb-4 bg-gray-800">
                <img id="confirmMerchImage" src="" alt="" class="w-full h-full object-cover">
            </div>
            <h3 class="text-xl font-bold mb-2" id="confirmMerchName">Merchandise</h3>
            <p class="text-yellow-500 font-bold text-lg"><i class="fas fa-coins mr-1"></i> <span id="confirmMerchPoints">0</span> Poin</p>
        </div>

        <div class="bg-black/50 rounded-xl p-4 mb-6">
            <h4 class="text-sm font-bold mb-3">Alamat Pengiriman</h4>
            <p class="text-sm text-gray-400" id="shippingAddress"><?= esc($user['address'] ?? 'Alamat belum diisi. Silakan lengkapi di profile.') ?></p>
            <a href="<?= base_url('user/profile') ?>" class="text-accent text-xs mt-2 hover:underline inline-block">
                <i class="fas fa-edit mr-1"></i> Ubah Alamat
            </a>
        </div>

        <div class="flex gap-3">
            <button onclick="closeMerchantConfirmModal()" class="flex-1 py-3 border border-white/20 rounded-xl hover:border-white/40 transition text-sm">
                Batal
            </button>
            <button onclick="confirmRedeemMerchandise()" id="confirmBtn" class="flex-1 py-3 bg-purple-500 text-white font-bold rounded-xl hover:bg-purple-400 transition text-sm">
                <i class="fas fa-check mr-2"></i> Konfirmasi
            </button>
        </div>
    </div>
</div>

<!-- Toast -->
<div id="toast" class="hidden fixed bottom-10 left-1/2 -translate-x-1/2 px-6 py-3 bg-accent text-black font-bold rounded-full shadow-lg z-[120] text-sm">
    <span id="toastMessage">Berhasil!</span>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const userPoints = <?= $poinBalance ?>;
    const merchandiseData = <?= json_encode(array_map(function ($item) {
                                return [
                                    'id' => (int)$item['id'],
                                    'name' => $item['name'],
                                    'points' => (int)$item['points_required'],
                                    'image' => base_url($item['image']),
                                    'stock' => $item['unlimited_stock'] ? -1 : (int)$item['stock'],
                                ];
                            }, $merchandise ?? [])) ?>;

    let selectedMerchandise = null;

    function selectMerchandise(id) {
        selectedMerchandise = merchandiseData.find(m => m.id === id);
        if (!selectedMerchandise) return;

        if (selectedMerchandise.points > userPoints) {
            showToast('Poin tidak mencukupi!', 'error');
            return;
        }

        if (selectedMerchandise.stock === 0) {
            showToast('Stok habis!', 'error');
            return;
        }

        document.getElementById('confirmMerchName').textContent = selectedMerchandise.name;
        document.getElementById('confirmMerchPoints').textContent = selectedMerchandise.points.toLocaleString();
        document.getElementById('confirmMerchImage').src = selectedMerchandise.image;

        openMerchantConfirmModal();
    }

    function openMerchantConfirmModal() {
        const modal = document.getElementById('merchantConfirmModal');
        const content = document.getElementById('merchantConfirmContent');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeMerchantConfirmModal() {
        const modal = document.getElementById('merchantConfirmModal');
        const content = document.getElementById('merchantConfirmContent');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    function confirmRedeemMerchandise() {
        if (!selectedMerchandise) return;

        const btn = document.getElementById('confirmBtn');
        const originalContent = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';

        fetch('<?= base_url('wpa/dashboard/poin/redeem-merchandise') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    merchandise_id: selectedMerchandise.id
                })
            })
            .then(response => response.json())
            .then(data => {
                closeMerchantConfirmModal();
                btn.disabled = false;
                btn.innerHTML = originalContent;

                if (data.success) {
                    showToast('Penukaran berhasil! Merchandise akan dikirim.', 'success');
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showToast(data.message || 'Gagal menukarkan poin', 'error');
                }
            })
            .catch(error => {
                btn.disabled = false;
                btn.innerHTML = originalContent;
                showToast('Terjadi kesalahan sistem', 'error');
                closeMerchantConfirmModal();
            });
    }

    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        const toastMsg = document.getElementById('toastMessage');

        toastMsg.textContent = message;
        if (type === 'error') {
            toast.className = 'fixed bottom-10 left-1/2 -translate-x-1/2 px-6 py-3 bg-red-500 text-white font-bold rounded-full shadow-lg z-[120] text-sm';
        } else {
            toast.className = 'fixed bottom-10 left-1/2 -translate-x-1/2 px-6 py-3 bg-accent text-black font-bold rounded-full shadow-lg z-[120] text-sm';
        }

        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 3000);
    }
</script>
<?= $this->endSection() ?>