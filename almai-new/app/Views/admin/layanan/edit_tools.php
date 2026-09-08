<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="<?= base_url('admin/layanan/create') ?>" class="p-2 hover:bg-white/10 rounded-lg">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold">Edit Layanan Software / EA</h1>
            <p class="text-gray-400 text-sm">Buat layanan untuk Expert Advisor (EA), Custom Indicator, atau Toolkits Trading.</p>
        </div>
    </div>

    <form action="<?= base_url('admin/layanan/update/' . $layananType . '/' . $layanan['id']) ?>" method="POST" enctype="multipart/form-data" id="layananForm">
        <?= csrf_field() ?>
        <input type="hidden" name="kategori" id="hiddenKategori" value="expert-advisor">

        <!-- INFORMASI UMUM -->
        <div class="bg-[#111] border border-white/10 rounded-xl p-6 mb-6">
            <h3 class="font-bold mb-6 flex items-center gap-2">
                <i class="fas fa-info-circle text-accent"></i>
                Informasi Umum
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Jenis Layanan Software <span class="text-red-400">*</span></label>
                    <select name="subcategory" id="subcategorySelect" required class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        <?php $reqSub = $_GET['sub'] ?? ''; ?>
                        <option value="ea" <?= $layananType == 'ea' ? 'selected' : '' ?>>>Expert Advisor (EA)</option>
                        <option value="toolkit" <?= $layananType == 'toolkit' ? 'selected' : '' ?>>>Almai Toolkits</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Sub Kategori</label>
                    <select id="subKategoriSelect" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        <?php $reqName = $_GET['name'] ?? ''; ?>
                        <option value="">-- Pilih --</option>
                        <option value="BIDBOX" <?= $reqName == 'BIDBOX' ? 'selected' : '' ?>>BIDBOX</option>
                        <option value="AIWE" <?= $reqName == 'AIWE' ? 'selected' : '' ?>>AIWE</option>
                        <option value="Custom" <?= !in_array($reqName, ['BIDBOX', 'AIWE', '']) ? 'selected' : '' ?>>Lainnya...</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Spesialis</label>
                    <select name="specialist" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        <option value="">-- Pilih Spesialis --</option>
                        <option value="Gold" <?= (isset($layanan['specialist']) && $layanan['specialist'] == 'Gold') ? 'selected' : '' ?>>Gold</option>
                        <option value="Forex" <?= (isset($layanan['specialist']) && $layanan['specialist'] == 'Forex') ? 'selected' : '' ?>>Forex</option>
                        <option value="Crypto" <?= (isset($layanan['specialist']) && $layanan['specialist'] == 'Crypto') ? 'selected' : '' ?>>Crypto</option>
                        <option value="Stock" <?= (isset($layanan['specialist']) && $layanan['specialist'] == 'Stock') ? 'selected' : '' ?>>Stock</option>
                        <option value="Index" <?= (isset($layanan['specialist']) && $layanan['specialist'] == 'Index') ? 'selected' : '' ?>>Index</option>
                        <option value="EA" <?= (isset($layanan['specialist']) && $layanan['specialist'] == 'EA') ? 'selected' : '' ?>>EA</option>
                        <option value="AI" <?= (isset($layanan['specialist']) && $layanan['specialist'] == 'AI') ? 'selected' : '' ?>>AI</option>
                        <option value="Profirm" <?= (isset($layanan['specialist']) && $layanan['specialist'] == 'Profirm') ? 'selected' : '' ?>>Profirm</option>
                        <option value="Algorithm" <?= (isset($layanan['specialist']) && $layanan['specialist'] == 'Algorithm') ? 'selected' : '' ?>>Algorithm</option>
                        <option value="Technical Analyst" <?= (isset($layanan['specialist']) && $layanan['specialist'] == 'Technical Analyst') ? 'selected' : '' ?>>Technical Analyst</option>
                        <option value="Risk Management" <?= (isset($layanan['specialist']) && $layanan['specialist'] == 'Risk Management') ? 'selected' : '' ?>>Risk Management</option>
                    </select>
                </div>
            </div>

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Nama Software/EA <span class="text-red-400">*</span></label>
                        <input type="text" id="namaSoftware" name="name" value="<?= esc($reqName) ?>" required class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Slug (Opsional)</label>
                        <input type="text" name="slug" value="<?= esc($layanan['slug'] ?? '') ?>"   class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3" placeholder="contoh-nama-ea">
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Deskripsi Lengkap</label>
                    <textarea name="description" id="editor_description" rows="4" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"><?= esc($layanan['description'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Layanan Utama (Poin-poin Penjualan)</label>
                    <textarea name="layanan_utama" rows="3" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3" placeholder="Sebutkan 3-5 layanan utama..."><?= esc($layanan['layanan_utama'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Fitur Unggulan (JSON Format)</label>
                    <textarea name="fitur_unggulan" rows="3" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3 font-mono text-sm" placeholder='[{"name": "Auto Trading", "link": ""}]'><?= esc($layanan['fitur_unggulan'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- DETAIL SOFTWARE / TOOLS -->
        <div class="bg-[#111] border border-white/10 rounded-xl p-6 mb-6">
            <h3 class="font-bold mb-4 flex items-center gap-2">
                <i class="fas fa-robot text-accent"></i>
                Detail Software/Tool & License
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Versi</label>
                    <input type="text" name="version" value="<?= esc($layanan['version'] ?? '') ?>"   placeholder="1.0.0" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">URL Dokumentasi</label>
                    <input type="url" name="documentation_url" value="<?= esc($layanan['documentation_url'] ?? '') ?>"   placeholder="https://..." class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                </div>
            </div>
            
            <div class="mb-6">
                <label class="block text-sm text-gray-400 mb-2">Changelog</label>
                <textarea name="changelog" rows="3" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"><?= esc($layanan['changelog'] ?? '') ?></textarea>
            </div>

            <!-- EA LICENSE -->
            <div class="inline-flex items-center gap-4 bg-black/40 border border-white/5 p-4 rounded-xl mb-4">
                <input type="checkbox" name="is_license_product" <?= (isset($layanan['is_license_product']) && $layanan['is_license_product']) ? 'checked' : '' ?> id="isLicenseProduct" value="1" class="w-6 h-6 rounded bg-[#0a0a0a] border-white/20 text-accent">
                <label for="isLicenseProduct" class="text-lg font-bold">Berikan Lisensi EA</label>
            </div>

            <div id="licenseFields" class="hidden space-y-6 pt-4 border-t border-white/10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Generate Kode Lisensi (Prefix)</label>
                        <input type="text" name="license_prefix" value="<?= esc($layanan['license_prefix'] ?? 'ALMAI-{id_akun}-{random4}') ?>"  class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3 text-sm text-white font-mono">
                        <p class="text-[10px] text-gray-500 mt-1">Format: ALMAI-{id_akun}-{random4}</p>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Durasi Lisensi (Hari)</label>
                        <input type="number" name="license_duration" value="<?= esc($layanan['license_duration'] ?? '0') ?>"  class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        <p class="text-[10px] text-gray-500 mt-1">0 untuk lifetime.</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-white/10">
                <label class="block text-sm text-gray-400 mb-2">Upload File EA (.ex4, .ex5, .zip)</label>
                <input type="file" name="layanan_file" accept=".ex4,.ex5,.mq4,.mq5,.zip" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-2 text-sm">
                <p class="text-[10px] text-gray-500 mt-2">Atau `ea_file` jika input name sebelumnya menggunakan itu. (di form baru ini bisa via layanan_file)</p>
            </div>
        </div>

        <!-- VIDEO & RESOURCE PELATIHAN -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Video Tutorials -->
            <div class="bg-[#111] border border-white/10 rounded-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold flex items-center gap-2">
                        <i class="fab fa-youtube text-red-500"></i>
                        Tutorial Penggunaan
                    </h3>
                    <button type="button" onclick="addYtRow()" class="px-3 py-1.5 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg text-xs transition">
                        <i class="fas fa-plus mr-1"></i> Tambah Video
                    </button>
                </div>
                <div id="ytContainer" class="space-y-3"></div>
                <input type="hidden" name="youtube_tutorials" id="ytHidden">
            </div>

            <!-- Materi & Resources -->
            <div class="bg-[#111] border border-white/10 rounded-xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold flex items-center gap-2">
                        <i class="fas fa-folder-open text-yellow-500"></i>
                        Materi & Resources Tambahan
                    </h3>
                    <button type="button" onclick="addResourceRow()" class="px-3 py-1.5 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg text-xs transition">
                        <i class="fas fa-plus mr-1"></i> Tambah File
                    </button>
                </div>
                <div id="newResourcesContainer" class="space-y-3"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <?= $this->include('admin/layanan/partials/pricing_referral') ?>
            <?= $this->include('admin/layanan/partials/partner_media') ?>
        </div>

        <div class="flex justify-end pt-6 border-t border-white/10">
            <button type="button" onclick="history.back()" class="px-6 py-2.5 rounded-xl border border-white/10 hover:bg-white/5 transition-colors mr-4">Batal</button>
            <button type="submit" class="px-8 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center gap-2">
                <i class="fas fa-save"></i> Simpan & Terbitkan
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?= $this->include('admin/layanan/partials/common_scripts') ?>
<script>
    document.getElementById('subcategorySelect').addEventListener('change', function() {
        const cat = document.getElementById('hiddenKategori');
        if(this.value === 'toolkit') {
            cat.value = 'ultimate';
        } else {
            cat.value = 'expert-advisor';
        }
    });

    document.getElementById('subKategoriSelect').addEventListener('change', function() {
        const namaInput = document.getElementById('namaSoftware');
        if(this.value && this.value !== 'Custom') {
            namaInput.value = this.value;
        } else if(this.value === 'Custom') {
            namaInput.value = '';
            namaInput.focus();
        }
    });
</script>
<?= $this->endSection() ?>
