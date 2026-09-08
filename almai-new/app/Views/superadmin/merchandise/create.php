<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <a href="<?= base_url('superadmin/merchandise') ?>" class="text-gray-400 hover:text-white text-sm">
        <i class="fas fa-arrow-left mr-2"></i> Kembali
    </a>
</div>

<div class="max-w-2xl">
    <div class="bg-[#111] border border-white/10 rounded-xl p-6">
        <h2 class="text-lg font-bold mb-6">Tambah Merchandise</h2>

        <?php if (session()->getFlashdata('errors')): ?>
        <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-xl mb-6">
            <ul class="list-disc list-inside text-sm">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

        <form action="<?= base_url('superadmin/merchandise/store') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Nama Merchandise *</label>
                    <input type="text" name="name" value="<?= old('name') ?>" required
                        class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                        placeholder="Polo ALMAI Official">
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Deskripsi</label>
                    <textarea name="description" rows="3"
                        class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                        placeholder="Deskripsi merchandise..."><?= old('description') ?></textarea>
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Gambar</label>
                    <div class="flex items-start gap-4">
                        <div class="w-32 h-32 bg-gray-800 rounded-xl overflow-hidden flex items-center justify-center" id="imagePreview">
                            <i class="fas fa-image text-gray-600 text-3xl"></i>
                        </div>
                        <div class="flex-1">
                            <input type="file" name="image" id="imageInput" accept="image/*"
                                class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none text-sm">
                            <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG. Maks 2MB</p>
                            <p class="text-xs text-gray-500 mt-1">Atau masukkan path gambar:</p>
                            <input type="text" name="image_path" value="<?= old('image_path') ?>"
                                class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none text-sm mt-2"
                                placeholder="/images/merchandise/nama-file.jpg">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Poin Dibutuhkan *</label>
                        <input type="number" name="points_required" value="<?= old('points_required') ?>" required min="1"
                            class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                            placeholder="500">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Stok</label>
                        <input type="number" name="stock" value="<?= old('stock', 0) ?>" min="0" id="stockInput"
                            class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                            placeholder="100">
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" name="unlimited_stock" id="unlimited_stock" value="1" 
                        class="w-5 h-5 rounded border-white/20 bg-black text-accent focus:ring-accent"
                        <?= old('unlimited_stock') ? 'checked' : '' ?>>
                    <label for="unlimited_stock" class="text-sm">Stok Tidak Terbatas (untuk voucher)</label>
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Status</label>
                    <select name="status" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
                        <option value="active" <?= old('status') === 'active' ? 'selected' : '' ?>>Aktif</option>
                        <option value="inactive" <?= old('status') === 'inactive' ? 'selected' : '' ?>>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <a href="<?= base_url('superadmin/merchandise') ?>" class="flex-1 py-3 border border-white/20 rounded-xl text-center hover:border-white/40 transition">
                    Batal
                </a>
                <button type="submit" class="flex-1 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                    <i class="fas fa-save mr-2"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('imageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').innerHTML = '<img src="' + e.target.result + '" class="w-full h-full object-cover">';
        };
        reader.readAsDataURL(file);
    }
});
document.getElementById('unlimited_stock').addEventListener('change', function() {
    document.getElementById('stockInput').disabled = this.checked;
});
</script>
<?= $this->endSection() ?>
