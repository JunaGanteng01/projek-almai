<?php
$pageTitle = 'Edit Hero Banner Event';
$pageSubtitle = 'Update data slide banner';
?>

<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl">
    <div class="bg-[#111] border border-white/10 rounded-2xl md:rounded-3xl overflow-hidden p-6 md:p-10">
        <form action="<?= base_url('superadmin/event-banner/update/' . $banner['id']) ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="space-y-6">
                <!-- Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Judul Banner</label>
                    <input type="text" name="title" value="<?= old('title', $banner['title']) ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent outline-none transition" placeholder="Masukkan judul banner (Opsional)">
                    <?php if (session('errors.title')): ?>
                        <p class="text-red-500 text-xs mt-1"><?= session('errors.title') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Ganti Gambar Banner</label>
                    <div class="flex flex-col md:flex-row gap-4">
                        <div id="image-preview" class="w-full md:w-60 h-32 rounded-xl bg-black/50 border border-white/10 flex items-center justify-center overflow-hidden">
                            <img src="<?= base_url($banner['image']) ?>" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <input type="file" name="image" id="image-input" class="hidden" accept="image/*">
                            <label for="image-input" class="inline-flex items-center px-6 py-3 bg-white/5 border border-white/10 text-white rounded-xl cursor-pointer hover:bg-white/10 transition mb-2">
                                <i class="fas fa-upload mr-2"></i> Pilih Gambar Baru
                            </label>
                            <p class="text-xs text-gray-500">Biarkan kosong jika tidak ingin mengganti gambar. Max 2MB (JPG, PNG, WebP).</p>
                            <?php if (session('errors.image')): ?>
                                <p class="text-red-500 text-xs mt-1"><?= session('errors.image') ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Deskripsi / Konten</label>
                    <textarea name="content" rows="3" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent outline-none transition" placeholder="Masukkan deskripsi singkat banner"><?= old('content', $banner['content']) ?></textarea>
                </div>

                <!-- URL -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">URL Tujuan (Link)</label>
                    <input type="text" name="url" value="<?= old('url', $banner['url']) ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent outline-none transition" placeholder="Contoh: /event/nama-event atau https://example.com">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
                    <!-- Order -->
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Urutan Tampil</label>
                        <input type="number" name="order" value="<?= old('order', $banner['order']) ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent outline-none transition">
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Status</label>
                        <select name="is_active" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent outline-none transition">
                            <option value="1" <?= old('is_active', $banner['is_active']) == '1' ? 'selected' : '' ?>>Aktif</option>
                            <option value="0" <?= old('is_active', $banner['is_active']) == '0' ? 'selected' : '' ?>>Tidak Aktif</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 pt-10 border-t border-white/5">
                    <a href="<?= base_url('superadmin/event-banner') ?>" class="px-8 py-3 border border-white/10 text-gray-400 rounded-xl hover:bg-white/5 text-center transition">Batal</a>
                    <button type="submit" class="px-10 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition shadow-lg shadow-accent/20">Update Banner</button>
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
            }
            reader.readAsDataURL(file);
        }
    });
</script>

<?= $this->endSection() ?>