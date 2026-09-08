<?= $this->extend('admin_wpa/layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="<?= base_url('admin-wpa/layanan') ?>" class="p-2 hover:bg-white/10 rounded-lg">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold">Edit Layanan</h1>
            <p class="text-gray-400 text-sm">Update informasi untuk layanan: <span class="text-white"><?= esc($layanan['name'] ?? $layanan['title']) ?></span></p>
        </div>
    </div>

    <!-- Multi-step Wizard Header -->
    <div class="flex items-center justify-between mb-8 overflow-x-auto pb-4 gap-4 no-scrollbar">
        <div class="flex items-center min-w-max">
            <button type="button" onclick="goToStep(1)" id="step-btn-1" class="flex items-center gap-3 px-6 py-3 rounded-xl bg-accent text-black font-bold transition-all whitespace-nowrap">
                <span class="w-8 h-8 rounded-full bg-black/20 flex items-center justify-center text-sm">1</span>
                Informasi
            </button>
            <div class="w-12 h-px bg-white/10 mx-2"></div>
            <button type="button" onclick="goToStep(2)" id="step-btn-2" class="flex items-center gap-3 px-6 py-3 rounded-xl bg-white/5 text-gray-500 font-bold transition-all whitespace-nowrap">
                <span class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-sm">2</span>
                Aktivasi EA
            </button>
            <div class="w-12 h-px bg-white/10 mx-2"></div>
            <button type="button" onclick="goToStep(3)" id="step-btn-3" class="flex items-center gap-3 px-6 py-3 rounded-xl bg-white/5 text-gray-500 font-bold transition-all whitespace-nowrap">
                <span class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-sm">3</span>
                Pelatihan
            </button>
            <div class="w-12 h-px bg-white/10 mx-2"></div>
            <button type="button" onclick="goToStep(4)" id="step-btn-4" class="flex items-center gap-3 px-6 py-3 rounded-xl bg-white/5 text-gray-500 font-bold transition-all whitespace-nowrap">
                <span class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-sm">4</span>
                Selesai
            </button>
        </div>
    </div>

    <form action="<?= base_url('admin-wpa/layanan/update/' . $type . '/' . $layanan['id']) ?>" method="POST" enctype="multipart/form-data" id="wizardForm">
        <?= csrf_field() ?>
        
        <!-- STEP 1: INFORMASI -->
        <div id="step-content-1" class="space-y-6">
            <div class="bg-[#111] border border-white/10 rounded-xl p-6">
                <h3 class="font-bold mb-6 flex items-center gap-2">
                    <i class="fas fa-info-circle text-accent"></i>
                    Informasi Layanan
                </h3>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Nama/Judul <span class="text-red-400">*</span></label>
                            <input type="text" name="name" value="<?= esc($layanan['name'] ?? $layanan['title']) ?>" required class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Slug</label>
                            <input type="text" name="slug" value="<?= esc($layanan['slug']) ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Spesialis</label>
                        <select name="specialist" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                            <option value="">-- Pilih Spesialis --</option>
                            <?php $specs = ['Gold', 'Forex', 'Crypto', 'Stock', 'Index', 'EA', 'AI', 'Profirm', 'Algorithm', 'Technical Analyst', 'Risk Management']; 
                            foreach($specs as $s): ?>
                                <option value="<?= $s ?>" <?= $layanan['specialist'] == $s ? 'selected' : '' ?>><?= $s ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Deskripsi</label>
                        <textarea name="description" id="editor_description" rows="4" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"><?= esc($layanan['description'] ?? $layanan['content'] ?? '') ?></textarea>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Layanan Utama</label>
                        <textarea name="layanan_utama" rows="3" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"><?= esc($layanan['layanan_utama']) ?></textarea>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Fitur Unggulan (JSON)</label>
                        <textarea name="fitur_unggulan" rows="5" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3 font-mono text-sm"><?= esc($layanan['fitur_unggulan']) ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Detail Sections based on type -->
            <?php if (in_array($type, ['webinar', 'workshop', 'live_trade'])): ?>
            <div class="bg-[#111] border border-white/10 rounded-xl p-6">
                <h3 class="font-bold mb-4">Detail Event</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Tanggal Mulai</label>
                            <input type="datetime-local" name="event_date" value="<?= date('Y-m-d\TH:i', strtotime($layanan['event_date'])) ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Tanggal Selesai</label>
                            <input type="datetime-local" name="event_end_date" value="<?= $layanan['event_end_date'] ? date('Y-m-d\TH:i', strtotime($layanan['event_end_date'])) : '' ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($type === 'artikel'): ?>
            <div class="bg-[#111] border border-white/10 rounded-xl p-6">
                <h3 class="font-bold mb-4">Ringkasan Artikel</h3>
                <textarea name="excerpt" rows="3" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"><?= esc($layanan['excerpt']) ?></textarea>
            </div>
            <?php endif; ?>

            <!-- Pricing Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-[#111] border border-white/10 rounded-xl p-6">
                    <h3 class="font-bold mb-4">Harga & Skema</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 mb-2">
                            <input type="checkbox" name="has_packages" id="hasPackages" value="1" <?= !empty($packages) ? 'checked' : '' ?> class="w-5 h-5 rounded bg-[#0a0a0a] border-white/10 text-accent">
                            <label for="hasPackages" class="text-sm font-medium">Aktifkan Paket Harga</label>
                        </div>
                        
                        <div id="singlePriceField" class="<?= !empty($packages) ? 'hidden' : '' ?> space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">Harga Jual</label>
                                    <input type="number" name="price" value="<?= $layanan['price'] ?? 0 ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">Harga Poin</label>
                                    <input type="number" name="poin_price" id="poinPrice" value="<?= $layanan['poin_price'] ?? 0 ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                                </div>
                            </div>
                        </div>

                        <div id="multiPackageField" class="<?= empty($packages) ? 'hidden' : '' ?> space-y-4">
                            <div id="packagesContainer" class="space-y-3">
                                <?php foreach($packages as $idx => $pkg): ?>
                                    <div id="pkg-<?= $pkg['id'] ?>" class="bg-black/30 p-3 rounded-lg border border-white/5 relative group">
                                        <button type="button" onclick="document.getElementById('pkg-<?= $pkg['id'] ?>').remove()" class="absolute top-2 right-2 text-gray-500 hover:text-red-400 opacity-0 group-hover:opacity-100 transition"><i class="fas fa-times"></i></button>
                                        <input type="hidden" name="packages[<?= $idx ?>][id]" value="<?= $pkg['id'] ?>">
                                        <input type="text" name="packages[<?= $idx ?>][name]" value="<?= esc($pkg['name']) ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded px-3 py-2 text-sm mb-2">
                                        <div class="grid grid-cols-2 gap-3 mb-2">
                                            <input type="number" name="packages[<?= $idx ?>][price]" value="<?= $pkg['price'] ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded px-3 py-2 text-sm">
                                            <input type="number" name="packages[<?= $idx ?>][original_price]" value="<?= $pkg['original_price'] ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded px-3 py-2 text-sm">
                                        </div>
                                        <textarea name="packages[<?= $idx ?>][description]" rows="2" class="w-full bg-[#0a0a0a] border border-white/10 rounded px-3 py-2 text-sm"><?= esc($pkg['description']) ?></textarea>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button type="button" onclick="addPackageRow()" class="w-full py-2 border border-dashed border-white/20 rounded-lg text-xs text-gray-400">
                                <i class="fas fa-plus mr-1"></i> Tambah Paket
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-[#111] border border-white/10 rounded-xl p-6">
                    <h3 class="font-bold mb-4">Partner & Media</h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">WPA Pengelola</label>
                                <select name="wpa_id" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                                    <?php foreach ($wpaList as $wpa): ?>
                                        <option value="<?= $wpa['id'] ?>" <?= $layanan['wpa_id'] == $wpa['id'] ? 'selected' : '' ?>><?= esc($wpa['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">CWPA (Optional)</label>
                                <select name="cwpa_id" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                                    <option value="">-- Pilih CWPA --</option>
                                    <?php foreach ($cwpaList as $cwpa): ?>
                                        <option value="<?= $cwpa['id'] ?>" <?= $layanan['cwpa_id'] == $cwpa['id'] ? 'selected' : '' ?>><?= esc($cwpa['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Thumbnail Baru (Opsional)</label>
                            <input type="file" name="thumbnail" accept="image/*" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-2 text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-6">
                <button type="button" onclick="goToStep(2)" class="px-8 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center gap-2">
                    Lanjut ke Aktivasi EA <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- STEP 2 - 4 (Simplified for placeholder, normally would contain same as create but with current data) -->
        <!-- For brevity, I'll keep them consistent with create structure -->
        <div id="step-content-2" class="hidden space-y-6">
            <div class="bg-[#111] border border-white/10 rounded-xl p-6 text-center max-w-2xl mx-auto py-12">
                <i class="fas fa-robot text-accent text-4xl mb-6"></i>
                <h3 class="text-2xl font-bold mb-2">Aktivasi EA</h3>
                <div class="inline-flex items-center gap-4 bg-black/40 border border-white/5 p-4 rounded-xl mb-8">
                    <input type="checkbox" name="is_license_product" id="isLicenseProduct" value="1" <?= $layanan['is_license_product'] ? 'checked' : '' ?> class="w-6 h-6 rounded bg-[#0a0a0a] border-white/20 text-accent">
                    <label for="isLicenseProduct" class="text-lg font-bold">Produk Lisensi</label>
                </div>
                <div id="licenseFields" class="<?= $layanan['is_license_product'] ? '' : 'hidden' ?> space-y-6 text-left max-w-lg mx-auto border-t border-white/10 pt-8 mt-4">
                     <div>
                        <label class="block text-sm text-gray-400 mb-2">Prefix Lisensi</label>
                        <input type="text" name="license_prefix" value="<?= esc($layanan['license_prefix']) ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3 font-mono">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Durasi (Hari, 0 = Lifetime)</label>
                        <input type="number" name="license_duration" value="<?= $layanan['license_duration'] ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                    </div>
                </div>
            </div>
            <div class="flex justify-between pt-6">
                <button type="button" onclick="goToStep(1)" class="px-8 py-3 bg-white/5 text-white font-bold rounded-xl">Kembali</button>
                <button type="button" onclick="goToStep(3)" class="px-8 py-3 bg-accent text-black font-bold rounded-xl">Lanjut</button>
            </div>
        </div>

        <div id="step-content-3" class="hidden space-y-6">
             <!-- Training/Tutorials (YouTube) -->
             <div class="bg-[#111] border border-white/10 rounded-xl p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold">Video Tutorial</h3>
                    <button type="button" onclick="addYtRow()" class="px-3 py-1 bg-white/5 border border-white/10 rounded text-xs">Tambah Video</button>
                </div>
                <div id="ytContainer" class="space-y-3">
                    <?php 
                    $yts = json_decode($layanan['youtube_tutorials'] ?? '[]', true);
                    if ($yts): foreach($yts as $yt): ?>
                        <div class="p-3 bg-white/5 border border-white/10 rounded-lg space-y-2 relative">
                            <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-gray-500 hover:text-red-400"><i class="fas fa-times"></i></button>
                            <input type="text" placeholder="Judul" class="yt-title w-full bg-black/50 border border-white/10 rounded px-3 py-2 text-sm" value="<?= esc($yt['title']) ?>">
                            <input type="url" placeholder="URL" class="yt-url w-full bg-black/50 border border-white/10 rounded px-3 py-2 text-sm" value="<?= esc($yt['url']) ?>">
                        </div>
                    <?php endforeach; endif; ?>
                </div>
                <input type="hidden" name="youtube_tutorials" id="ytHidden">
            </div>
            <!-- Resources -->
             <div class="bg-[#111] border border-white/10 rounded-xl p-6 mt-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold">Materi & Resources</h3>
                    <button type="button" onclick="addResourceRow()" class="px-3 py-1 bg-white/5 border border-white/10 rounded text-xs">Tambah File</button>
                </div>
                <div class="space-y-3">
                    <?php foreach($resources as $res): ?>
                        <div class="flex items-center justify-between p-3 bg-white/5 rounded-lg">
                            <span class="text-sm"><?= esc($res['title']) ?></span>
                            <label class="flex items-center gap-2 text-xs text-red-400 cursor-pointer">
                                <input type="checkbox" name="delete_resources[]" value="<?= $res['id'] ?>" class="rounded bg-black">
                                Hapus
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div id="newResourcesContainer" class="space-y-3 mt-4"></div>
            </div>
            <div class="flex justify-between pt-6">
                <button type="button" onclick="goToStep(2)" class="px-8 py-3 bg-white/5 text-white font-bold rounded-xl">Kembali</button>
                <button type="button" onclick="goToStep(4)" class="px-8 py-3 bg-accent text-black font-bold rounded-xl">Lanjut</button>
            </div>
        </div>

        <div id="step-content-4" class="hidden space-y-6">
            <div class="bg-[#111] border border-white/10 rounded-xl p-12 text-center max-w-2xl mx-auto">
                <i class="fas fa-check-circle text-green-500 text-5xl mb-8"></i>
                <h3 class="text-3xl font-bold mb-4">Update Siap Disimpan</h3>
                <p class="text-gray-400 mb-8">Pastikan semua data sudah benar.</p>
                <button type="submit" class="w-full py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-lg">
                    <i class="fas fa-save mr-2"></i> Update Layanan
                </button>
            </div>
            <div class="flex justify-start pt-6">
                <button type="button" onclick="goToStep(3)" class="px-8 py-3 bg-white/5 text-white font-bold rounded-xl">Kembali</button>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.ckeditor.com/ckeditor5/41.2.0/classic/ckeditor.js"></script>
<script>
     // Wizard Navigation (Same as Create)
     window.goToStep = function(step) {
        document.querySelectorAll('[id^="step-content-"]').forEach(el => el.classList.add('hidden'));
        document.getElementById(`step-content-${step}`).classList.remove('hidden');
        for (let i = 1; i <= 4; i++) {
            const btn = document.getElementById(`step-btn-${i}`);
            if (i === step) {
                btn.classList.add('bg-accent', 'text-black');
                btn.classList.remove('bg-white/5', 'text-gray-500');
            } else {
                btn.classList.remove('bg-accent', 'text-black');
                btn.classList.add('bg-white/5', 'text-gray-500');
            }
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    // CKEditor
    const commonConfig = { toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'undo', 'redo'] };
    if (typeof ClassicEditor !== 'undefined') {
        if (document.querySelector('#editor_description')) {
            ClassicEditor.create(document.querySelector('#editor_description'), commonConfig).catch(console.error);
        }
    } else {
        console.warn('ClassicEditor is not loaded. Skipping initialization.');
    }

    // Toggle Multi Package
    if (document.getElementById('hasPackages')) {
        const togglePackagesEdit = function() {
            const isChecked = document.getElementById('hasPackages').checked;
            const singleSection = document.getElementById('singlePriceField');
            const multiSection = document.getElementById('multiPackageField');
            
            if (singleSection) singleSection.classList.toggle('hidden', isChecked);
            if (multiSection) multiSection.classList.toggle('hidden', !isChecked);
            
            if (singleSection) {
                singleSection.querySelectorAll('input').forEach(input => input.disabled = isChecked);
            }
            if (multiSection) {
                multiSection.querySelectorAll('input').forEach(input => input.disabled = !isChecked);
            }
            
            if (isChecked && document.getElementById('packagesContainer') && document.getElementById('packagesContainer').children.length === 0) {
                if (typeof window.addPackageRow === 'function') addPackageRow();
            }
        };

        document.getElementById('hasPackages').addEventListener('change', togglePackagesEdit);
        
        // Ensure state is correct on load
        togglePackagesEdit();
    }

    // Dynamic field logic (same as create but simplified for edit context)
    let packageCounter = <?= count($packages) ?>;
    window.addPackageRow = function() {
        const container = document.getElementById('packagesContainer');
        const id = `pkg-new-${packageCounter}`;
        container.insertAdjacentHTML('beforeend', `
            <div id="${id}" class="bg-black/30 p-3 rounded-lg border border-white/5 relative group">
                <button type="button" onclick="document.getElementById('${id}').remove()" class="absolute top-2 right-2 text-gray-500 hover:text-red-400 group-hover:opacity-100 transition"><i class="fas fa-times"></i></button>
                <input type="text" name="packages[${packageCounter}][name]" placeholder="Nama Paket" class="w-full bg-[#0a0a0a] border border-white/10 rounded px-3 py-2 text-sm mb-2" required>
                <div class="grid grid-cols-2 gap-3 mb-2">
                    <input type="number" name="packages[${packageCounter}][price]" placeholder="Harga" class="w-full bg-[#0a0a0a] border border-white/10 rounded px-3 py-2 text-sm" required>
                    <input type="number" name="packages[${packageCounter}][original_price]" placeholder="Harga Coret" class="w-full bg-[#0a0a0a] border border-white/10 rounded px-3 py-2 text-sm">
                </div>
            </div>
        `);
        packageCounter++;
    };

    window.addResourceRow = function() {
        const container = document.getElementById('newResourcesContainer');
        const div = document.createElement('div');
        div.className = 'p-3 bg-white/5 border border-white/10 rounded-lg relative';
        div.innerHTML = `<button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-gray-400"><i class="fas fa-times"></i></button><input type="text" name="resource_titles[]" placeholder="Judul" class="w-full bg-black/50 border border-white/10 rounded px-3 py-2 text-sm mb-2"><input type="file" name="resources[]" required class="text-xs">`;
        container.appendChild(div);
    };

    window.addYtRow = function() {
        const container = document.getElementById('ytContainer');
        const div = document.createElement('div');
        div.className = 'p-3 bg-white/5 border border-white/10 rounded-lg space-y-2 relative';
        div.innerHTML = `<button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-gray-400"><i class="fas fa-times"></i></button><input type="text" placeholder="Judul" class="yt-title w-full bg-black/50 border border-white/10 rounded px-3 py-2 text-sm"><input type="url" placeholder="URL" class="yt-url w-full bg-black/50 border border-white/10 rounded px-3 py-2 text-sm">`;
        container.appendChild(div);
    };

    document.getElementById('wizardForm').addEventListener('submit', function() {
        const data = [];
        document.querySelectorAll('#ytContainer > div').forEach(div => {
            const title = div.querySelector('.yt-title').value;
            const url = div.querySelector('.yt-url').value;
            if (title || url) data.push({ title, url });
        });
        document.getElementById('ytHidden').value = JSON.stringify(data);
    });

    goToStep(1);
</script>
<?= $this->endSection() ?>
