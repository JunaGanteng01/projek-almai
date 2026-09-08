<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>
<?php $pageTitle = 'Edit Kelas'; $pageSubtitle = esc($kelas['title']); ?>

<div class="mb-6">
    <a href="<?= base_url('superadmin/kelas') ?>" class="inline-flex items-center gap-2 text-gray-400 hover:text-accent transition">
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

<!-- Preview Card -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden mb-6 max-w-4xl">
    <div class="flex flex-col md:flex-row">
        <img src="<?= esc($kelas['thumbnail']) ?>" alt="" class="w-full md:w-48 h-32 object-cover">
        <div class="p-4 flex-1">
            <div class="flex items-center gap-2 mb-2">
                <span class="px-2 py-1 bg-accent/20 text-accent rounded-full text-xs"><?= esc($kelas['category']) ?></span>
                <span class="px-2 py-1 bg-blue-500/20 text-blue-400 rounded-full text-xs"><?= esc($kelas['level']) ?></span>
                <span class="px-2 py-1 <?= $kelas['status'] === 'active' ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' ?> rounded-full text-xs"><?= ucfirst($kelas['status']) ?></span>
            </div>
            <h3 class="font-bold"><?= esc($kelas['title']) ?></h3>
            <div class="flex items-center gap-4 mt-2 text-sm text-gray-400">
                <span><i class="fas fa-users mr-1"></i> <?= number_format($kelas['students'] ?? 0) ?> students</span>
                <span><i class="fas fa-star text-yellow-500 mr-1"></i> <?= $kelas['rating'] ?? '0' ?></span>
                <span class="text-accent font-bold">Rp <?= number_format($kelas['price'], 0, ',', '.') ?></span>
            </div>
        </div>
    </div>
</div>

<form action="<?= base_url('superadmin/kelas/update/' . $kelas['id']) ?>" method="post" enctype="multipart/form-data" class="max-w-4xl">
    <?= csrf_field() ?>
    
    <!-- Basic Info -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-6">
        <h3 class="font-bold mb-4 flex items-center gap-2">
            <i class="fas fa-info-circle text-accent"></i> Informasi Dasar
        </h3>
        <div class="grid md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-400 mb-2">Judul Kelas *</label>
                <input type="text" name="title" required value="<?= old('title', $kelas['title']) ?>" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Pengajar (WPA) *</label>
                <select name="wpa_id" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
                    <?php foreach ($wpaList as $wpa): ?>
                    <option value="<?= $wpa['id'] ?>" <?= $kelas['wpa_id'] == $wpa['id'] ? 'selected' : '' ?>>
                        <?= esc(explode(',', $wpa['name'])[0]) ?> - <?= esc($wpa['specialty']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Kategori *</label>
                <select name="category" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat ?>" <?= $kelas['category'] === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-400 mb-2">Deskripsi *</label>
                <textarea name="description" required rows="3" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none resize-none"><?= old('description', $kelas['description']) ?></textarea>
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
                <label class="block text-sm text-gray-400 mb-2">Upload Gambar Baru</label>
                <input type="file" name="thumbnail_file" accept="image/*" onchange="previewImage(this)"
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-accent/20 file:text-accent file:cursor-pointer">
                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, WebP. Maks 2MB</p>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Atau URL Gambar</label>
                <input type="url" name="thumbnail" value="<?= old('thumbnail', $kelas['thumbnail']) ?>" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
            </div>
            <div class="md:col-span-2">
                <p class="text-sm text-gray-400 mb-2">Thumbnail Saat Ini:</p>
                <img src="<?= esc($kelas['thumbnail']) ?>" alt="Current thumbnail" class="max-h-32 rounded-xl border border-white/10">
            </div>
            <div class="md:col-span-2">
                <div id="imagePreview" class="hidden">
                    <p class="text-sm text-gray-400 mb-2">Preview Baru:</p>
                    <img id="previewImg" src="" alt="Preview" class="max-h-32 rounded-xl border border-accent/50">
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
                <input type="number" name="price" required value="<?= old('price', $kelas['price']) ?>" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Harga Asli (Rp)</label>
                <input type="number" name="original_price" value="<?= old('original_price', $kelas['original_price']) ?>" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
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
                <input type="text" name="duration" value="<?= old('duration', $kelas['duration']) ?>" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Jumlah Modul</label>
                <input type="number" name="modules" value="<?= old('modules', $kelas['modules']) ?>" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Level</label>
                <select name="level" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
                    <option value="Beginner" <?= $kelas['level'] === 'Beginner' ? 'selected' : '' ?>>Beginner</option>
                    <option value="Intermediate" <?= $kelas['level'] === 'Intermediate' ? 'selected' : '' ?>>Intermediate</option>
                    <option value="Advanced" <?= $kelas['level'] === 'Advanced' ? 'selected' : '' ?>>Advanced</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Mode</label>
                <select name="mode" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
                    <option value="Online" <?= $kelas['mode'] === 'Online' ? 'selected' : '' ?>>Online</option>
                    <option value="Offline" <?= $kelas['mode'] === 'Offline' ? 'selected' : '' ?>>Offline</option>
                    <option value="Hybrid" <?= $kelas['mode'] === 'Hybrid' ? 'selected' : '' ?>>Hybrid</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Tipe</label>
                <select name="type" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
                    <option value="recorded" <?= $kelas['type'] === 'recorded' ? 'selected' : '' ?>>Recorded</option>
                    <option value="live" <?= $kelas['type'] === 'live' ? 'selected' : '' ?>>Live</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Lokasi</label>
                <input type="text" name="location" value="<?= old('location', $kelas['location']) ?>" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
            </div>
            <div class="md:col-span-3">
                <label class="block text-sm text-gray-400 mb-2">Highlights</label>
                <?php 
                    $highlights = json_decode($kelas['highlights'] ?? '[]', true);
                    $highlightsStr = is_array($highlights) ? implode(', ', $highlights) : '';
                ?>
                <input type="text" name="highlights" value="<?= old('highlights', $highlightsStr) ?>" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" 
                    placeholder="Money Management, Risk Management (pisahkan dengan koma)">
            </div>
        </div>
    </div>

    <!-- Live Class Settings -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-6">
        <h3 class="font-bold mb-4 flex items-center gap-2">
            <i class="fas fa-video text-accent"></i> Pengaturan Live Class
        </h3>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-400 mb-2">Link Zoom</label>
                <input type="url" name="zoom_link" value="<?= old('zoom_link', $kelas['zoom_link']) ?>" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Jadwal</label>
                <input type="text" name="schedule" value="<?= old('schedule', $kelas['schedule']) ?>" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
            </div>
        </div>
    </div>

    <!-- Status -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-6">
        <h3 class="font-bold mb-4 flex items-center gap-2">
            <i class="fas fa-toggle-on text-accent"></i> Status
        </h3>
        <select name="status" class="w-full md:w-1/3 bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
            <option value="active" <?= $kelas['status'] === 'active' ? 'selected' : '' ?>>Active</option>
            <option value="inactive" <?= $kelas['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
        </select>
    </div>

    <div class="flex gap-4">
        <a href="<?= base_url('superadmin/kelas') ?>" class="flex-1 py-4 border border-white/20 rounded-xl text-center hover:bg-white/5 transition">
            Batal
        </a>
        <button type="submit" class="flex-1 py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
            <i class="fas fa-save mr-2"></i> Update Kelas
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
