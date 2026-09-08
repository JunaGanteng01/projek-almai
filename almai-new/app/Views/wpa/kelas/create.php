<?= $this->extend('wpa/layouts/main') ?>

<?= $this->section('content') ?>
<?php $pageTitle = 'Buat Layanan Baru'; $pageSubtitle = 'Tambahkan layanan baru untuk dijual'; ?>

<!-- Back Button -->
<div class="mb-6">
    <a href="<?= base_url('wpa/dashboard/layanan') ?>" class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Layanan
    </a>
</div>

<?php if (session()->getFlashdata('errors')): ?>
<div class="bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl mb-6">
    <ul class="list-disc list-inside">
        <?php foreach (session()->getFlashdata('errors') as $error): ?>
            <li><?= esc($error) ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>

<form action="<?= base_url('wpa/dashboard/layanan/store') ?>" method="post" enctype="multipart/form-data" class="max-w-4xl">
    <?= csrf_field() ?>
    
    <!-- Basic Info -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-6">
        <h3 class="font-bold mb-6 flex items-center gap-2">
            <i class="fas fa-info-circle text-accent"></i> Informasi Dasar
        </h3>
        
        <div class="space-y-4">
            <div>
                <label class="block text-sm text-gray-400 mb-2">Judul Layanan *</label>
                <input type="text" name="title" required value="<?= old('title') ?>" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" 
                    placeholder="Contoh: Belajar Gold Trading dari Nol">
            </div>
            
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Kategori *</label>
                    <select name="category" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
                        <option value="">Pilih Kategori</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= esc($cat) ?>" <?= old('category') === $cat ? 'selected' : '' ?>><?= esc($cat) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Level</label>
                    <select name="level" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
                        <option value="Beginner" <?= old('level') === 'Beginner' ? 'selected' : '' ?>>Beginner</option>
                        <option value="Intermediate" <?= old('level') === 'Intermediate' ? 'selected' : '' ?>>Intermediate</option>
                        <option value="Advanced" <?= old('level') === 'Advanced' ? 'selected' : '' ?>>Advanced</option>
                        <option value="All Level" <?= old('level') === 'All Level' ? 'selected' : '' ?>>All Level</option>
                    </select>
                </div>
            </div>
            
            <div>
                <label class="block text-sm text-gray-400 mb-2">Deskripsi *</label>
                <textarea name="description" required rows="4" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none resize-none" 
                    placeholder="Jelaskan tentang layanan ini..."><?= old('description') ?></textarea>
            </div>
            
            <div>
                <label class="block text-sm text-gray-400 mb-2">Highlights (satu per baris)</label>
                <textarea name="highlights" rows="4" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none resize-none" 
                    placeholder="Materi lengkap dari dasar&#10;Sertifikat resmi&#10;Akses selamanya"><?= old('highlights') ?></textarea>
            </div>
        </div>
    </div>
    
    <!-- Thumbnail -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-6">
        <h3 class="font-bold mb-6 flex items-center gap-2">
            <i class="fas fa-image text-accent"></i> Thumbnail
        </h3>
        
        <div class="flex items-start gap-6">
            <div class="relative">
                <img id="thumbPreview" src="https://via.placeholder.com/400x300" alt="Preview" class="w-48 h-32 rounded-xl object-cover border border-white/20">
                <div class="absolute inset-0 bg-black/50 rounded-xl flex items-center justify-center opacity-0 hover:opacity-100 transition cursor-pointer" onclick="document.getElementById('thumbInput').click()">
                    <i class="fas fa-camera text-white text-xl"></i>
                </div>
            </div>
            <div class="flex-1">
                <input type="file" id="thumbInput" name="thumbnail_file" accept="image/*" class="hidden" onchange="previewThumb(this)">
                <button type="button" onclick="document.getElementById('thumbInput').click()" class="px-4 py-2 bg-white/10 border border-white/20 rounded-xl hover:bg-white/20 transition text-sm mb-3">
                    <i class="fas fa-upload mr-2"></i> Upload Thumbnail
                </button>
                <p class="text-xs text-gray-500 mb-3">JPG, PNG max 2MB. Rasio 4:3 disarankan.</p>
                <input type="url" name="thumbnail" id="thumbUrl" value="<?= old('thumbnail') ?>" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-2 text-sm focus:border-accent focus:outline-none" 
                    placeholder="Atau masukkan URL gambar" onchange="previewThumbUrl(this.value)">
            </div>
        </div>
    </div>
    
    <!-- Pricing -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-6">
        <h3 class="font-bold mb-6 flex items-center gap-2">
            <i class="fas fa-tag text-accent"></i> Harga
        </h3>
        
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-400 mb-2">Harga Jual *</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                    <input type="number" name="price" required value="<?= old('price') ?>" 
                        class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 pl-12 focus:border-accent focus:outline-none" 
                        placeholder="500000">
                </div>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Harga Coret (opsional)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                    <input type="number" name="original_price" value="<?= old('original_price') ?>" 
                        class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 pl-12 focus:border-accent focus:outline-none" 
                        placeholder="750000">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Schedule -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-6">
        <h3 class="font-bold mb-6 flex items-center gap-2">
            <i class="fas fa-calendar text-accent"></i> Jadwal & Mode
        </h3>
        
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-400 mb-2">Mode Layanan</label>
                <select name="mode" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
                    <option value="Online" <?= old('mode') === 'Online' ? 'selected' : '' ?>>Online</option>
                    <option value="Offline" <?= old('mode') === 'Offline' ? 'selected' : '' ?>>Offline</option>
                    <option value="Hybrid" <?= old('mode') === 'Hybrid' ? 'selected' : '' ?>>Hybrid</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Tipe</label>
                <select name="type" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
                    <option value="live" <?= old('type') === 'live' ? 'selected' : '' ?>>Live Class</option>
                    <option value="recorded" <?= old('type') === 'recorded' ? 'selected' : '' ?>>Recorded</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Durasi</label>
                <input type="text" name="duration" value="<?= old('duration') ?>" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" 
                    placeholder="2 Jam">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Jumlah Modul</label>
                <input type="number" name="modules" value="<?= old('modules', 1) ?>" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" 
                    placeholder="5">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Jadwal</label>
                <input type="text" name="schedule" value="<?= old('schedule') ?>" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" 
                    placeholder="Setiap Sabtu, 10:00 WIB">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Sesi Berikutnya</label>
                <input type="datetime-local" name="next_session" value="<?= old('next_session') ?>" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-400 mb-2">Lokasi (untuk offline)</label>
                <input type="text" name="location" value="<?= old('location') ?>" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" 
                    placeholder="Jakarta, Indonesia">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-400 mb-2">Link Zoom (untuk online)</label>
                <input type="url" name="zoom_link" value="<?= old('zoom_link') ?>" 
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" 
                    placeholder="https://zoom.us/j/xxx">
            </div>
        </div>
    </div>
    
    <!-- Submit -->
    <div class="flex gap-4">
        <a href="<?= base_url('wpa/dashboard/layanan') ?>" class="flex-1 py-4 border border-white/20 rounded-xl text-center hover:bg-white/5 transition">
            Batal
        </a>
        <button type="submit" class="flex-1 py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
            <i class="fas fa-save mr-2"></i> Simpan Layanan
        </button>
    </div>
</form>

<script>
function previewThumb(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('thumbPreview').src = e.target.result;
            document.getElementById('thumbUrl').value = '';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function previewThumbUrl(url) {
    if (url) {
        document.getElementById('thumbPreview').src = url;
        document.getElementById('thumbInput').value = '';
    }
}
</script>
<?= $this->endSection() ?>
