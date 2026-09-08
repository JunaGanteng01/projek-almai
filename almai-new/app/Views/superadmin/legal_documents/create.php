<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="max-w-5xl mx-auto">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold mb-2">Tambah Dokumen Legal</h1>
        <p class="text-gray-400 text-sm">Buat dokumen legal baru untuk checkout dan transaksi</p>
    </div>

    <?php if (session()->has('errors')): ?>
        <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-xl mb-6">
            <ul class="list-disc list-inside">
                <?php foreach (session('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('superadmin/legal-documents/store') ?>" method="POST">
        <?= csrf_field() ?>

        <div class="bg-[#111] border border-white/10 rounded-xl p-6 mb-6">
            <!-- Title -->
            <div class="mb-6">
                <label class="block text-sm font-semibold mb-2">Judul Dokumen <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="<?= old('title') ?>"
                    class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-accent focus:outline-none"
                    placeholder="Contoh: Perjanjian Pemberian Jasa" required>
                <p class="text-xs text-gray-500 mt-1">Slug akan dibuat otomatis dari judul</p>
            </div>

            <!-- Type -->
            <div class="mb-6">
                <label class="block text-sm font-semibold mb-2">Tipe Dokumen <span class="text-red-500">*</span></label>
                <select name="type" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-accent focus:outline-none" required>
                    <option value="">Pilih Tipe</option>
                    <option value="perjanjian" <?= old('type') === 'perjanjian' ? 'selected' : '' ?>>Perjanjian</option>
                    <option value="risiko" <?= old('type') === 'risiko' ? 'selected' : '' ?>>Risiko</option>
                    <option value="other" <?= old('type') === 'other' ? 'selected' : '' ?>>Lainnya</option>
                </select>
            </div>

            <!-- Status -->
            <div class="mb-6">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" <?= old('is_active') ? 'checked' : 'checked' ?>
                        class="w-5 h-5 bg-black/50 border border-white/10 rounded text-accent focus:ring-accent">
                    <span class="text-sm font-semibold">Aktifkan dokumen</span>
                </label>
            </div>

            <!-- Content -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-sm font-semibold">Konten Dokumen <span class="text-red-500">*</span></label>
                    <button type="button" onclick="previewContent()"
                        class="px-4 py-2 bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 rounded-lg text-sm transition-colors">
                        <i class="fas fa-eye mr-2"></i>Preview
                    </button>
                </div>
                <textarea name="content" id="contentEditor"><?= old('content') ?></textarea>
                <p class="text-xs text-gray-500 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    Variabel tersedia: {NAMA_USER}, {EMAIL_USER}, {NO_TELP_USER}, {NAMA_WPA}, {NAMA_PRODUK}, {HARGA_RUPIAH_PRODUK}, {HARGA_YANG_DI_BAYAR}, {NOMOR_KONTRAK}, {TANGGAL}, {HARI_TANGGAL}, {LAYANAN_UTAMA}
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3">
            <button type="submit" class="px-6 py-3 bg-accent hover:bg-accent/80 text-black font-semibold rounded-lg transition-colors">
                <i class="fas fa-save mr-2"></i>Simpan Dokumen
            </button>
            <a href="<?= base_url('superadmin/legal-documents') ?>" class="px-6 py-3 bg-white/10 hover:bg-white/20 text-white rounded-lg transition-colors">
                <i class="fas fa-times mr-2"></i>Batal
            </a>
        </div>
    </form>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="hidden fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4">
    <div class="bg-[#111] border border-white/10 rounded-xl w-full max-w-4xl max-h-[90vh] flex flex-col">
        <div class="flex justify-between items-center p-6 border-b border-white/10">
            <h3 class="text-xl font-bold">Preview Dokumen</h3>
            <button type="button" onclick="closePreview()" class="text-gray-400 hover:text-white transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-6">
            <div id="previewContent" class="prose prose-invert max-w-none bg-white text-black p-8 rounded-lg"></div>
        </div>
        <div class="p-6 border-t border-white/10">
            <button type="button" onclick="closePreview()" class="px-6 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition-colors">
                <i class="fas fa-times mr-2"></i>Tutup
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/4.22.1/standard-all/ckeditor.js"></script>
<script>
    CKEDITOR.replace('contentEditor', {
        height: 500,
        toolbar: [{
                name: 'document',
                items: ['Source', '-', 'Preview']
            },
            {
                name: 'clipboard',
                items: ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo']
            },
            {
                name: 'editing',
                items: ['Find', 'Replace', '-', 'SelectAll']
            },
            '/',
            {
                name: 'basicstyles',
                items: ['Bold', 'Italic', 'Underline', 'Strike', '-', 'RemoveFormat']
            },
            {
                name: 'paragraph',
                items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote']
            },
            {
                name: 'links',
                items: ['Link', 'Unlink']
            },
            '/',
            {
                name: 'styles',
                items: ['Styles', 'Format', 'Font', 'FontSize']
            },
            {
                name: 'colors',
                items: ['TextColor', 'BGColor']
            },
            {
                name: 'align',
                items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']
            },
            {
                name: 'insert',
                items: ['Table', 'HorizontalRule', 'SpecialChar']
            },
            {
                name: 'tools',
                items: ['Maximize']
            }
        ],
        removeButtons: '',
        extraPlugins: 'font,colorbutton,justify',
        allowedContent: true
    });

    function previewContent() {
        const content = CKEDITOR.instances.contentEditor.getData();
        document.getElementById('previewContent').innerHTML = content;
        document.getElementById('previewModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closePreview() {
        document.getElementById('previewModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closePreview();
    });

    document.getElementById('previewModal').addEventListener('click', function(e) {
        if (e.target === this) closePreview();
    });
</script>
<?= $this->endSection() ?>