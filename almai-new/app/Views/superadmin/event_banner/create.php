<?php
$pageTitle = 'Tambah Hero Banner Event';
$pageSubtitle = 'Buat slide banner baru untuk halaman Event';
?>

<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl">
    <div class="bg-[#111] border border-white/10 rounded-2xl md:rounded-3xl overflow-hidden p-6 md:p-10">
        <form action="<?= base_url('superadmin/event-banner/store') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="space-y-6">
                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Judul Banner</label>
                    <input type="text" name="title" value="<?= old('title') ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent outline-none transition" placeholder="Masukkan judul banner (Opsional)">
                    <?php if (session('errors.title')): ?>
                        <p class="text-red-500 text-xs mt-1"><?= session('errors.title') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Upload Banner <span class="text-red-500">*</span></label>
                    <div class="flex flex-col md:flex-row gap-4">
                        <div id="image-preview" class="w-full md:w-60 h-32 rounded-xl bg-black/50 border border-white/10 flex items-center justify-center overflow-hidden border-dashed">
                            <i class="fas fa-image text-4xl text-white/10"></i>
                        </div>
                        <div class="flex-1">
                            <input type="file" name="image" id="image-input" class="hidden" accept="image/*" required>
                            <label for="image-input" class="inline-flex items-center px-6 py-3 bg-white/5 border border-white/10 text-white rounded-xl cursor-pointer hover:bg-white/10 transition mb-2">
                                <i class="fas fa-upload mr-2"></i> Pilih Gambar
                            </label>
                            <p class="text-xs text-gray-500">Rekomendasi ukuran: 1920x800px atau rasionya. Max 2MB (JPG, PNG, WebP).</p>
                            <?php if (session('errors.image')): ?>
                                <p class="text-red-500 text-xs mt-1"><?= session('errors.image') ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Deskripsi / Konten</label>
                    <textarea name="content" rows="3" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent outline-none transition" placeholder="Masukkan deskripsi singkat banner"><?= old('content') ?></textarea>
                </div>

                <!-- URL -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">URL Tujuan (Link)</label>
                    <input type="text" name="url" value="<?= old('url') ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent outline-none transition" placeholder="Contoh: /event/nama-event atau https://example.com">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                    <!-- Order -->
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Urutan Tampil</label>
                        <input type="number" name="order" value="<?= old('order', 0) ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent outline-none transition">
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Status</label>
                        <select name="is_active" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent outline-none transition">
                            <option value="1" <?= old('is_active') == '1' ? 'selected' : '' ?>>Aktif</option>
                            <option value="0" <?= old('is_active') == '0' ? 'selected' : '' ?>>Tidak Aktif</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 pt-10 border-t border-white/5">
                    <a href="<?= base_url('superadmin/event-banner') ?>" class="px-8 py-3 border border-white/10 text-gray-400 rounded-xl hover:bg-white/5 text-center transition">Batal</a>
                    <button type="submit" class="px-10 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition shadow-lg shadow-accent/20">Simpan Banner</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const imageInput = document.getElementById('image-input');
    const imagePreview = document.getElementById('image-preview');

    imageInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                imagePreview.classList.remove('border-dashed');
            }
            reader.readAsDataURL(file);
        }
    });
</script>

<?= $this->endSection() ?>