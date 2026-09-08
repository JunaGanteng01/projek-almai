<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="<?= base_url('superadmin/layanan/create') ?>" class="p-2 hover:bg-white/10 rounded-lg">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold">Tambah Layanan Artikel / E-Book</h1>
            <p class="text-gray-400 text-sm">Buat layanan berupa artikel, berita, atau dokumen</p>
        </div>
    </div>

    <form action="<?= base_url('superadmin/layanan/store') ?>" method="POST" enctype="multipart/form-data" id="layananForm">
        <?= csrf_field() ?>
        <input type="hidden" name="kategori" value="advokasi">
        <input type="hidden" name="subcategory" value="artikel">

        <!-- INFORMASI UMUM -->
        <div class="bg-[#111] border border-white/10 rounded-xl p-6 mb-6">
            <h3 class="font-bold mb-6 flex items-center gap-2">
                <i class="fas fa-info-circle text-accent"></i>
                Informasi Umum
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Judul Artikel <span class="text-red-400">*</span></label>
                    <input type="text" name="name" required class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Slug (Opsional)</label>
                    <input type="text" name="slug" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3" placeholder="contoh-judul-artikel">
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Spesialis</label>
                    <select name="specialist" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        <option value="">-- Pilih Spesialis --</option>
                        <option value="Gold">Gold</option>
                        <option value="Forex">Forex</option>
                        <option value="Crypto">Crypto</option>
                        <option value="Stock">Stock</option>
                        <option value="Index">Index</option>
                        <option value="EA">EA</option>
                        <option value="AI">AI</option>
                        <option value="Profirm">Profirm</option>
                        <option value="Algorithm">Algorithm</option>
                        <option value="Technical Analyst">Technical Analyst</option>
                        <option value="Risk Management">Risk Management</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Deskripsi Singkat (SEO/Meta)</label>
                    <textarea name="description" rows="3" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"></textarea>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Layanan Utama (Poin-poin)</label>
                    <textarea name="layanan_utama" rows="3" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3" placeholder="Sebutkan 3-5 poin utama..."></textarea>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Fitur Unggulan (JSON Format)</label>
                    <textarea name="fitur_unggulan" rows="4" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3 font-mono text-sm" placeholder='[{"name": "Materi PDF", "link": "https://..."}]'></textarea>
                </div>
            </div>
        </div>

        <!-- DETAIL ARTIKEL -->
        <div class="bg-[#111] border border-white/10 rounded-xl p-6 mb-6">
            <h3 class="font-bold mb-4 flex items-center gap-2">
                <i class="fas fa-file-alt text-blue-500"></i>
                Detail Artikel & Konten
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Excerpt (Ringkasan Singkat untuk Card)</label>
                    <textarea name="excerpt" rows="2" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"></textarea>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Konten Lengkap</label>
                    <textarea name="content" id="editor_content" rows="6" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"></textarea>
                </div>
            </div>
        </div>

        <!-- FILE UPLOAD -->
        <div class="bg-[#111] border border-white/10 rounded-xl p-6 mb-6">
            <h3 class="font-bold mb-4 flex items-center gap-2">
                <i class="fas fa-upload text-accent"></i>
                File Utama Layanan (E-Book/Dokumen)
            </h3>
            <label class="block text-sm text-gray-400 mb-2">Pilih File <span class="text-xs text-accent">(PDF, DOC, DOCX)</span></label>
            <input type="file" name="layanan_file" accept=".pdf,.doc,.docx" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-2 text-sm">
            <p class="text-[10px] text-gray-500 mt-2">File ini adalah produk utama yang akan diunduh oleh pembeli jika layanan berbayar.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <?= $this->include('admin/layanan/partials/pricing_referral') ?>
            <?= $this->include('admin/layanan/partials/partner_media') ?>
        </div>

        <div class="flex justify-end pt-6 border-t border-white/10">
            <button type="submit" class="px-8 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center gap-2">
                <i class="fas fa-save"></i> Simpan & Terbitkan
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?= $this->include('admin/layanan/partials/common_scripts') ?>
<?= $this->endSection() ?>
