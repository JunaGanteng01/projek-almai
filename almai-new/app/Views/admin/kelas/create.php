<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<?php $pageTitle = 'Tambah Kelas'; $pageSubtitle = 'Buat program edukasi baru'; ?>

<div class="mb-6">
    <a href="<?= base_url('admin/kelas') ?>" class="inline-flex items-center gap-2 text-gray-400 hover:text-accent transition">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<?php if (session()->getFlashdata('errors')): ?>
<div class="bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl mb-6">
    <ul class="list-disc list-inside text-sm">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
        <li><?= esc($error) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form action="<?= base_url('admin/kelas/store') ?>" method="post" enctype="multipart/form-data" class="max-w-4xl">
    <?= csrf_field() ?>
    
    <!-- Basic Info -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-6">
        <h3 class="font-bold mb-4 flex items-center gap-2">
            <i class="fas fa-info-circle text-accent"></i> Informasi Dasar
        </h3>
        <div class="grid md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-400 mb-2">Judul Kelas *</label>
                <input type="text" name="title" required value="<?= old('title') ?>" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" 
                    placeholder="Gold Trading Masterclass">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Pengajar (WPA) *</label>
                <select name="wpa_id" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
                    <option value="">Pilih WPA</option>
                    <?php foreach ($wpaList as $wpa): ?>
                    <option value="<?= $wpa['id'] ?>" <?= old('wpa_id') == $wpa['id'] ? 'selected' : '' ?>>
                        <?= esc(explode(',', $wpa['name'])[0]) ?> - <?= esc($wpa['specialty']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Kategori *</label>
                <select name="category" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
                    <option value="">Pilih Kategori</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat ?>" <?= old('category') === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-400 mb-2">Deskripsi *</label>
                <textarea name="description" required rows="3" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none resize-none" 
                    placeholder="Deskripsi singkat tentang kelas..."><?= old('description') ?></textarea>
            </div>
        </div>
    </div>

    <!-- Thumbnail Upload -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-6">
        <h3 class="font-bold mb-4 flex items-center gap-2">
            <i class="fas fa-image text-accent"></i> Thumbnail
        </h3>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-400 mb-2">Upload Gambar</label>
                <input type="file" name="thumbnail_file" accept="image/*" onchange="previewImage(this)"
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-accent/20 file:text-accent file:cursor-pointer">
                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, WebP. Maks 2MB</p>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Atau URL Gambar</label>
                <input type="url" name="thumbnail" value="<?= old('thumbnail') ?>" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" 
                    placeholder="https://example.com/image.jpg">
            </div>
            <div class="md:col-span-2">
                <div id="imagePreview" class="hidden">
                    <p class="text-sm text-gray-400 mb-2">Preview:</p>
                    <img id="previewImg" src="" alt="Preview" class="max-h-48 rounded-xl border border-white/10">
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-6">
        <h3 class="font-bold mb-4 flex items-center gap-2">
            <i class="fas fa-tag text-accent"></i> Harga
        </h3>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-400 mb-2">Harga Jual (Rp) *</label>
                <input type="number" name="price" required value="<?= old('price') ?>" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" 
                    placeholder="18000000">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Harga Asli (Rp)</label>
                <input type="number" name="original_price" value="<?= old('original_price') ?>" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" 
                    placeholder="25000000">
                <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ada diskon</p>
            </div>
        </div>
    </div>

    <!-- Details -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-6">
        <h3 class="font-bold mb-4 flex items-center gap-2">
            <i class="fas fa-list text-accent"></i> Detail Kelas
        </h3>
        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm text-gray-400 mb-2">Durasi</label>
                <input type="text" name="duration" value="<?= old('duration', '10 Jam') ?>" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" 
                    placeholder="10 Jam">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Jumlah Modul</label>
                <input type="number" name="modules" value="<?= old('modules', 20) ?>" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" 
                    placeholder="20">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Level</label>
                <select name="level" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
                    <option value="Beginner" <?= old('level') === 'Beginner' ? 'selected' : '' ?>>Beginner</option>
                    <option value="Intermediate" <?= old('level') === 'Intermediate' ? 'selected' : '' ?>>Intermediate</option>
                    <option value="Advanced" <?= old('level') === 'Advanced' ? 'selected' : '' ?>>Advanced</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Mode</label>
                <select name="mode" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
                    <option value="Online" <?= old('mode') === 'Online' ? 'selected' : '' ?>>Online</option>
                    <option value="Offline" <?= old('mode') === 'Offline' ? 'selected' : '' ?>>Offline</option>
                    <option value="Hybrid" <?= old('mode') === 'Hybrid' ? 'selected' : '' ?>>Hybrid</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Tipe</label>
                <select name="type" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
                    <option value="recorded" <?= old('type') === 'recorded' ? 'selected' : '' ?>>Recorded</option>
                    <option value="live" <?= old('type') === 'live' ? 'selected' : '' ?>>Live</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Lokasi</label>
                <input type="text" name="location" value="<?= old('location', 'Zoom') ?>" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" 
                    placeholder="Zoom / Bali">
            </div>
            <div class="md:col-span-3">
                <label class="block text-sm text-gray-400 mb-2">Highlights</label>
                <input type="text" name="highlights" value="<?= old('highlights') ?>" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" 
                    placeholder="Money Management, Risk Management, Entry Strategy (pisahkan dengan koma)">
            </div>
        </div>
    </div>

    <!-- Live Class Settings -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-6">
        <h3 class="font-bold mb-4 flex items-center gap-2">
            <i class="fas fa-video text-accent"></i> Pengaturan Live Class (Opsional)
        </h3>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-400 mb-2">Link Zoom</label>
                <input type="url" name="zoom_link" value="<?= old('zoom_link') ?>" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" 
                    placeholder="https://zoom.us/j/123456789">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Jadwal</label>
                <input type="text" name="schedule" value="<?= old('schedule') ?>" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" 
                    placeholder="Setiap Sabtu, 09:00 - 12:00 WIB">
            </div>
        </div>
    </div>

    <!-- Status -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-6">
        <h3 class="font-bold mb-4 flex items-center gap-2">
            <i class="fas fa-toggle-on text-accent"></i> Status
        </h3>
        <select name="status" class="w-full md:w-1/3 bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>

    <div class="flex gap-4">
        <a href="<?= base_url('admin/kelas') ?>" class="flex-1 py-4 border border-white/20 rounded-xl text-center hover:bg-white/5 transition">
            Batal
        </a>
        <button type="submit" class="flex-1 py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
            <i class="fas fa-save mr-2"></i> Simpan Kelas
        </button>
    </div>
</form>

<script>
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.classList.add('hidden');
    }
}
</script>
<?= $this->endSection() ?>
