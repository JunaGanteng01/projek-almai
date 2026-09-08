<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-black text-white uppercase tracking-tight">Pengaturan Program Advokasi</h2>
        <p class="text-gray-500 text-sm">Konfigurasi materi, harga, dan akses webinar program</p>
    </div>
    <a href="<?= base_url('superadmin/advokasi') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/5 border border-white/10 text-white font-bold rounded-xl hover:bg-white/10 transition text-sm">
        <i class="fas fa-arrow-left text-xs"></i> Kembali ke Daftar Laporan
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="mb-6 p-4 bg-accent/10 border border-accent/20 text-accent rounded-2xl flex items-center gap-3">
        <i class="fas fa-check-circle"></i>
        <p class="text-sm font-bold"><?= session()->getFlashdata('success') ?></p>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-500 rounded-2xl flex items-center gap-3">
        <i class="fas fa-exclamation-circle"></i>
        <p class="text-sm font-bold"><?= session()->getFlashdata('error') ?></p>
    </div>
<?php endif; ?>

<form action="<?= base_url('superadmin/advokasi/save-setting') ?>" method="post" enctype="multipart/form-data">
    <?= csrf_field() ?>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Left Column: Basic Info -->
        <div class="space-y-8">
            <!-- Program Info Card -->
            <div class="bg-[#111] border border-white/10 rounded-3xl overflow-hidden shadow-xl">
                <div class="px-6 py-4 border-b border-white/5 bg-white/5">
                    <h3 class="text-sm font-black text-white uppercase tracking-widest flex items-center gap-2">
                        <i class="fas fa-info-circle text-accent"></i> Informasi Utama
                    </h3>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2 ml-1">Nama Program</label>
                        <input type="text" name="description" value="<?= esc($settings['description'] ?? '') ?>" required 
                            class="w-full bg-black border border-white/10 rounded-2xl px-5 py-3.5 text-sm text-white placeholder-gray-600 focus:border-accent focus:outline-none transition">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2 ml-1">Harga Program (IDR)</label>
                            <div class="relative">
                                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                                <input type="number" name="price" value="<?= esc($settings['price'] ?? 0) ?>" required 
                                    class="w-full bg-black border border-white/10 rounded-2xl pl-12 pr-5 py-3.5 text-sm text-white focus:border-accent focus:outline-none transition">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2 ml-1">Durasi Lisensi EA (Hari)</label>
                            <div class="relative">
                                <input type="number" name="ea_duration" value="<?= esc($settings['ea_duration'] ?? 30) ?>" required 
                                    class="w-full bg-black border border-white/10 rounded-2xl px-5 py-3.5 text-sm text-white focus:border-accent focus:outline-none transition">
                                <span class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-500 text-xs">Hari</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2 ml-1">Thumbnail Program</label>
                        <div class="flex flex-col gap-4">
                            <?php if (!empty($settings['thumbnail'])): ?>
                                <img src="<?= base_url($settings['thumbnail']) ?>" class="w-full aspect-video object-cover rounded-2xl border border-white/10">
                            <?php endif; ?>
                            <input type="file" name="thumbnail" class="text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-accent file:text-black hover:file:bg-white cursor-pointer">
                        </div>
                    </div>
                </div>
            </div>

            <!-- EA Content Card -->
            <div class="bg-[#111] border border-white/10 rounded-3xl overflow-hidden shadow-xl">
                <div class="px-6 py-4 border-b border-white/5 bg-white/5 flex items-center justify-between">
                    <h3 class="text-sm font-black text-white uppercase tracking-widest flex items-center gap-2">
                        <i class="fas fa-robot text-accent"></i> Expert Advisor (EA)
                    </h3>
                    <button type="button" onclick="addEa()" class="p-2 bg-accent/10 text-accent rounded-lg hover:bg-accent hover:text-black transition">
                        <i class="fas fa-plus text-xs"></i>
                    </button>
                </div>
                <div class="p-6">
                    <div id="ea-container" class="space-y-4">
                        <?php 
                        $eaList = $settings['ea_list'] ?? [];
                        foreach ($eaList as $index => $item): 
                        ?>
                        <div class="ea-item p-4 bg-black border border-white/5 rounded-2xl relative group">
                            <button type="button" onclick="removeEa(this)" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow-lg">
                                <i class="fas fa-times text-[10px]"></i>
                            </button>
                            <div class="grid grid-cols-1 gap-3">
                                <input type="text" name="ea_titles[]" placeholder="Nama EA (ex: EA Almai v1 MT4)" value="<?= esc($item['title']) ?>" 
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-xs text-white focus:border-accent focus:outline-none transition">
                                
                                <div class="space-y-2">
                                    <input type="hidden" name="existing_ea_files[]" value="<?= esc($item['file'] ?? '#') ?>">
                                    <input type="file" name="ea_upload_files[]" accept=".ex4,.ex5,.zip" class="block w-full text-[10px] text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-[9px] file:font-black file:bg-white/10 file:text-white hover:file:bg-accent hover:file:text-black transition cursor-pointer">
                                    <?php if (!empty($item['file']) && $item['file'] !== '#'): ?>
                                        <p class="text-[9px] text-accent truncate"><i class="fas fa-check-circle mr-1"></i> File: <?= basename($item['file']) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <p class="text-[9px] text-gray-500 mt-4 italic">* Filename akan dipertahankan sesuai aslinya. Support .ex4, .ex5, .zip</p>
                </div>
            </div>

            <!-- Software Card -->
            <div class="bg-[#111] border border-white/10 rounded-3xl overflow-hidden shadow-xl">
                <div class="px-6 py-4 border-b border-white/5 bg-white/5 flex items-center justify-between">
                    <h3 class="text-sm font-black text-white uppercase tracking-widest flex items-center gap-2">
                        <i class="fas fa-laptop-code text-accent"></i> Software
                    </h3>
                    <button type="button" onclick="addSoftware()" class="p-2 bg-accent/10 text-accent rounded-lg hover:bg-accent hover:text-black transition">
                        <i class="fas fa-plus text-xs"></i>
                    </button>
                </div>
                <div class="p-6">
                    <div id="software-container" class="space-y-4">
                        <?php 
                        $software = $settings['software'] ?? [];
                        foreach ($software as $index => $item): 
                        ?>
                        <div class="software-item p-4 bg-black border border-white/5 rounded-2xl relative group">
                            <button type="button" onclick="removeSoftware(this)" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow-lg">
                                <i class="fas fa-times text-[10px]"></i>
                            </button>
                            <div class="grid grid-cols-1 gap-3">
                                <input type="text" name="software_titles[]" placeholder="Nama Software" value="<?= esc($item['title']) ?>" 
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-xs text-white focus:border-accent focus:outline-none transition">
                                
                                <div class="space-y-2">
                                    <input type="hidden" name="existing_software_files[]" value="<?= esc($item['file'] ?? '#') ?>">
                                    <input type="file" name="software_upload_files[]" accept=".exe" class="block w-full text-[10px] text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-[9px] file:font-black file:bg-white/10 file:text-white hover:file:bg-accent hover:file:text-black transition cursor-pointer">
                                    <?php if (!empty($item['file']) && $item['file'] !== '#'): ?>
                                        <p class="text-[9px] text-accent truncate"><i class="fas fa-check-circle mr-1"></i> File: <?= basename($item['file']) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Webinar & Materials -->
        <div class="space-y-8">
            <!-- Zoom Config Card -->
            <div class="bg-[#111] border border-white/10 rounded-3xl overflow-hidden shadow-xl">
                <div class="px-6 py-4 border-b border-white/5 bg-white/5">
                    <h3 class="text-sm font-black text-white uppercase tracking-widest flex items-center gap-2">
                        <i class="fas fa-video text-accent"></i> Link Webinar (Zoom)
                    </h3>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2 ml-1">Link Zoom Meeting</label>
                        <input type="url" name="zoom_link" value="<?= esc($settings['zoom_link'] ?? '') ?>" placeholder="https://zoom.us/j/..." 
                            class="w-full bg-black border border-white/10 rounded-2xl px-5 py-3.5 text-sm text-white focus:border-accent focus:outline-none transition">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2 ml-1">Meeting ID</label>
                            <input type="text" name="zoom_meeting_id" value="<?= esc($settings['zoom_meeting_id'] ?? '') ?>" 
                                class="w-full bg-black border border-white/10 rounded-2xl px-5 py-3.5 text-sm text-white focus:border-accent focus:outline-none transition">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2 ml-1">Passcode</label>
                            <input type="text" name="zoom_password" value="<?= esc($settings['zoom_password'] ?? '') ?>" 
                                class="w-full bg-black border border-white/10 rounded-2xl px-5 py-3.5 text-sm text-white focus:border-accent focus:outline-none transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-4 ml-1">Materi Interaktif (Canva Embed) per Hari</label>
                        <?php 
                        $canvaLinks = json_decode($settings['canva_embed_url'] ?? '[]', true) ?: [];
                        $days = [
                            'senin' => 'Senin',
                            'selasa' => 'Selasa',
                            'rabu' => 'Rabu',
                            'kamis' => 'Kamis',
                            'jumat' => 'Jumat',
                            'sabtu' => 'Sabtu',
                            'minggu' => 'Minggu'
                        ];
                        foreach ($days as $key => $label):
                        ?>
                        <div class="mb-4">
                            <label class="block text-[9px] font-bold text-gray-400 uppercase mb-1 ml-1"><?= $label ?></label>
                            <input type="text" name="canva_links[<?= $key ?>]" value="<?= esc($canvaLinks[$key] ?? '') ?>" placeholder="Paste link atau embed code Canva di sini..." 
                                class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-xs text-white focus:border-accent focus:outline-none transition">
                        </div>
                        <?php endforeach; ?>
                        <p class="text-[9px] text-gray-500 mt-2 italic">* Pastikan menggunakan link 'Embed' dari Canva agar materi dapat tampil.</p>
                    </div>
                </div>
            </div>

            <!-- Materials Card -->
            <div class="bg-[#111] border border-white/10 rounded-3xl overflow-hidden shadow-xl">
                <div class="px-6 py-4 border-b border-white/5 bg-white/5 flex items-center justify-between">
                    <h3 class="text-sm font-black text-white uppercase tracking-widest flex items-center gap-2">
                        <i class="fas fa-folder text-accent"></i> Materi & Resources
                    </h3>
                    <button type="button" onclick="addMaterial()" class="p-2 bg-accent/10 text-accent rounded-lg hover:bg-accent hover:text-black transition">
                        <i class="fas fa-plus text-xs"></i>
                    </button>
                </div>
                <div class="p-6">
                    <div id="material-container" class="space-y-4">
                        <?php 
                        $materials = $settings['materials'] ?? [];
                        if (empty($materials)) $materials = [['title' => '', 'subtitle' => '', 'icon' => 'fa-file-pdf', 'file' => '#']];
                        foreach ($materials as $index => $item): 
                        ?>
                        <div class="material-item p-4 bg-black border border-white/5 rounded-2xl relative group">
                            <button type="button" onclick="removeMaterial(this)" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow-lg">
                                <i class="fas fa-times text-[10px]"></i>
                            </button>
                            <div class="grid grid-cols-1 gap-3">
                                <input type="text" name="material_titles[]" placeholder="Judul Materi" value="<?= esc($item['title']) ?>" 
                                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-xs text-white focus:border-accent focus:outline-none transition">
                                <div class="grid grid-cols-2 gap-3">
                                    <input type="text" name="material_subtitles[]" placeholder="Subtitle (ex: PDF 2.4MB)" value="<?= esc($item['subtitle'] ?? '') ?>" 
                                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-[10px] text-gray-400 focus:border-accent focus:outline-none transition">
                                    <select name="material_icons[]" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-[10px] text-gray-400 focus:border-accent focus:outline-none transition appearance-none">
                                        <option value="fa-file-pdf" class="bg-[#111]" <?= ($item['icon'] ?? '') == 'fa-file-pdf' ? 'selected' : '' ?>>PDF Icon</option>
                                        <option value="fa-video" class="bg-[#111]" <?= ($item['icon'] ?? '') == 'fa-video' ? 'selected' : '' ?>>Video Icon</option>
                                        <option value="fa-book" class="bg-[#111]" <?= ($item['icon'] ?? '') == 'fa-book' ? 'selected' : '' ?>>Book Icon</option>
                                        <option value="fa-link" class="bg-[#111]" <?= ($item['icon'] ?? '') == 'fa-link' ? 'selected' : '' ?>>Link Icon</option>
                                    </select>
                                </div>
                                
                                <div class="space-y-2">
                                    <input type="hidden" name="existing_material_files[]" value="<?= esc($item['file'] ?? '#') ?>">
                                    <input type="file" name="material_upload_files[]" class="block w-full text-[10px] text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-[9px] file:font-black file:bg-white/10 file:text-white hover:file:bg-accent hover:file:text-black transition cursor-pointer">
                                    <?php if (!empty($item['file']) && $item['file'] !== '#'): ?>
                                        <p class="text-[9px] text-accent truncate"><i class="fas fa-check-circle mr-1"></i> File: <?= basename($item['file']) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-12 mb-20 flex justify-end">
        <button type="submit" class="w-full md:w-auto px-12 py-4 bg-accent text-black font-black uppercase tracking-widest rounded-2xl hover:shadow-[0_0_40px_rgba(51,232,24,0.4)] hover:scale-[1.02] transition-all text-sm">
            <i class="fas fa-save mr-2 text-xs"></i> Simpan Semua Pengaturan
        </button>
    </div>
</form>

<script>
function addMaterial() {
    const container = document.getElementById('material-container');
    const newItem = document.createElement('div');
    newItem.className = 'material-item p-4 bg-black border border-white/5 rounded-2xl relative group';
    newItem.innerHTML = `
        <button type="button" onclick="removeMaterial(this)" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow-lg">
            <i class="fas fa-times text-[10px]"></i>
        </button>
        <div class="grid grid-cols-1 gap-3">
            <input type="text" name="material_titles[]" placeholder="Judul Materi" 
                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-xs text-white focus:border-accent focus:outline-none transition">
            <div class="grid grid-cols-2 gap-3">
                <input type="text" name="material_subtitles[]" placeholder="Subtitle (ex: PDF 2.4MB)" 
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-[10px] text-gray-400 focus:border-accent focus:outline-none transition">
                <select name="material_icons[]" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-[10px] text-gray-400 focus:border-accent focus:outline-none transition appearance-none">
                    <option value="fa-file-pdf" class="bg-[#111]">PDF Icon</option>
                    <option value="fa-video" class="bg-[#111]">Video Icon</option>
                    <option value="fa-book" class="bg-[#111]">Book Icon</option>
                    <option value="fa-link" class="bg-[#111]">Link Icon</option>
                </select>
            </div>
            <div class="space-y-2">
                <input type="hidden" name="existing_material_files[]" value="#">
                <input type="file" name="material_upload_files[]" class="block w-full text-[10px] text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-[9px] file:font-black file:bg-white/10 file:text-white hover:file:bg-accent hover:file:text-black transition cursor-pointer">
            </div>
        </div>
    `;
    container.appendChild(newItem);
}

function removeMaterial(btn) {
    btn.closest('.material-item').remove();
}

function addSoftware() {
    const container = document.getElementById('software-container');
    const newItem = document.createElement('div');
    newItem.className = 'software-item p-4 bg-black border border-white/5 rounded-2xl relative group';
    newItem.innerHTML = `
        <button type="button" onclick="removeSoftware(this)" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow-lg">
            <i class="fas fa-times text-[10px]"></i>
        </button>
        <div class="grid grid-cols-1 gap-3">
            <input type="text" name="software_titles[]" placeholder="Nama Software" 
                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-xs text-white focus:border-accent focus:outline-none transition">
            <div class="space-y-2">
                <input type="hidden" name="existing_software_files[]" value="#">
                <input type="file" name="software_upload_files[]" accept=".exe" class="block w-full text-[10px] text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-[9px] file:font-black file:bg-white/10 file:text-white hover:file:bg-accent hover:file:text-black transition cursor-pointer">
            </div>
        </div>
    `;
    container.appendChild(newItem);
}

function removeSoftware(btn) {
    btn.closest('.software-item').remove();
}

function addEa() {
    const container = document.getElementById('ea-container');
    const newItem = document.createElement('div');
    newItem.className = 'ea-item p-4 bg-black border border-white/5 rounded-2xl relative group';
    newItem.innerHTML = `
        <button type="button" onclick="removeEa(this)" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow-lg">
            <i class="fas fa-times text-[10px]"></i>
        </button>
        <div class="grid grid-cols-1 gap-3">
            <input type="text" name="ea_titles[]" placeholder="Nama EA (ex: EA Almai v1 MT4)" 
                class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-2 text-xs text-white focus:border-accent focus:outline-none transition">
            <div class="space-y-2">
                <input type="hidden" name="existing_ea_files[]" value="#">
                <input type="file" name="ea_upload_files[]" accept=".ex4,.ex5,.zip" class="block w-full text-[10px] text-gray-500 file:mr-4 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-[9px] file:font-black file:bg-white/10 file:text-white hover:file:bg-accent hover:file:text-black transition cursor-pointer">
            </div>
        </div>
    `;
    container.appendChild(newItem);
}

function removeEa(btn) {
    btn.closest('.ea-item').remove();
}
</script>

<?= $this->endSection() ?>
