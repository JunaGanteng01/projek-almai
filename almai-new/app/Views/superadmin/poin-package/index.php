<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold text-white uppercase tracking-tight">Kelola Harga Poin</h1>
        <p class="text-gray-500 text-sm mt-1">Atur paket pembelian poin untuk pengguna platform ALMAI</p>
    </div>
    <button onclick="openModal('modalAdd')" class="bg-accent hover:bg-accent/80 text-black px-6 py-2.5 rounded-xl font-bold flex items-center justify-center gap-2 transition-all duration-300">
        <i class="fas fa-plus"></i>
        <span>TAMBAH PAKET</span>
    </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    <?php if (empty($packages)): ?>
        <div class="col-span-full py-20 text-center bg-[#111] border border-white/10 rounded-2xl">
            <i class="fas fa-tags text-5xl text-gray-700 mb-4"></i>
            <p class="text-gray-400">Belum ada paket poin yang terdaftar.</p>
        </div>
    <?php else: ?>
        <?php foreach ($packages as $pkg): ?>
            <div class="group bg-[#111] border border-white/10 rounded-2xl overflow-hidden hover:border-accent/50 transition-all duration-300">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-accent/10 rounded-xl flex items-center justify-center">
                            <i class="fas fa-coins text-accent text-xl"></i>
                        </div>
                        <div class="flex items-center gap-2">
                             <a href="<?= base_url('superadmin/poin-package/toggle/' . $pkg['id']) ?>" class="p-2 <?= $pkg['is_enabled'] ? 'text-accent' : 'text-gray-500' ?> hover:bg-white/5 rounded-lg transition" title="Toggle Aktif">
                                <i class="fas <?= $pkg['is_enabled'] ? 'fa-toggle-on' : 'fa-toggle-off' ?> text-lg"></i>
                            </a>
                            <button onclick='openEditModal(<?= json_encode($pkg) ?>)' class="p-2 text-blue-400 hover:bg-blue-400/10 rounded-lg transition">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="confirmDelete('<?= base_url('superadmin/poin-package/delete/' . $pkg['id']) ?>')" class="p-2 text-red-400 hover:bg-red-400/10 rounded-lg transition">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    
                    <h3 class="text-3xl font-black text-white mb-1"><?= number_format($pkg['amount']) ?> <span class="text-sm font-normal text-gray-400 uppercase tracking-widest">Poin</span></h3>
                    <p class="text-accent font-bold text-lg mb-4">Rp <?= number_format($pkg['price'], 0, ',', '.') ?></p>
                    
                    <div class="flex items-center gap-2">
                        <?php if ($pkg['is_enabled']): ?>
                            <span class="px-3 py-1 bg-accent/10 text-accent text-[10px] font-bold uppercase rounded-full">Aktif</span>
                        <?php else: ?>
                            <span class="px-3 py-1 bg-red-500/10 text-red-400 text-[10px] font-bold uppercase rounded-full">Nonaktif</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Modal Add -->
<div id="modalAdd" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeModal('modalAdd')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-6">
        <div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
            <div class="p-6 border-b border-white/10 flex items-center justify-between">
                <h3 class="font-bold text-white uppercase">Tambah Paket Baru</h3>
                <button onclick="closeModal('modalAdd')" class="text-gray-500 hover:text-white"><i class="fas fa-times"></i></button>
            </div>
            <form action="<?= base_url('superadmin/poin-package/create') ?>" method="post" class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Jumlah Poin</label>
                    <input type="number" name="amount" required class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent outline-none transition" placeholder="Contoh: 1000">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Harga (Rp)</label>
                    <input type="number" name="price" required class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent outline-none transition" placeholder="Contoh: 100000">
                </div>
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_enabled" value="1" id="add_enabled" checked class="w-4 h-4 rounded accent-accent">
                    <label for="add_enabled" class="text-sm text-gray-400">Aktifkan paket ini segera</label>
                </div>
                <div class="pt-4">
                    <button type="submit" class="w-full bg-accent hover:bg-accent/80 text-black py-3 rounded-xl font-bold uppercase tracking-wider transition">Simpan Paket</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div id="modalEdit" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeModal('modalEdit')"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md p-6">
        <div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
            <div class="p-6 border-b border-white/10 flex items-center justify-between">
                <h3 class="font-bold text-white uppercase">Edit Paket</h3>
                <button onclick="closeModal('modalEdit')" class="text-gray-500 hover:text-white"><i class="fas fa-times"></i></button>
            </div>
            <form id="editForm" method="post" class="p-6 space-y-4">
                <input type="hidden" name="id" id="edit_id">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Jumlah Poin</label>
                    <input type="number" name="amount" id="edit_amount" required class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent outline-none transition">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Harga (Rp)</label>
                    <input type="number" name="price" id="edit_price" required class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent outline-none transition">
                </div>
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_enabled" value="1" id="edit_enabled" class="w-4 h-4 rounded accent-accent">
                    <label for="edit_enabled" class="text-sm text-gray-400">Paket ini Aktif</label>
                </div>
                <div class="pt-4">
                    <button type="submit" class="w-full bg-accent hover:bg-accent/80 text-black py-3 rounded-xl font-bold uppercase tracking-wider transition">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
    }

    function openEditModal(pkg) {
        document.getElementById('edit_id').value = pkg.id;
        document.getElementById('edit_amount').value = pkg.amount;
        document.getElementById('edit_price').value = pkg.price;
        document.getElementById('edit_enabled').checked = pkg.is_enabled == 1;
        document.getElementById('editForm').action = '<?= base_url('superadmin/poin-package/update') ?>/' + pkg.id;
        openModal('modalEdit');
    }

    function confirmDelete(url) {
        if (confirm('Apakah Anda yakin ingin menghapus paket poin ini?')) {
            window.location.href = url;
        }
    }
</script>
<?= $this->endSection() ?>
