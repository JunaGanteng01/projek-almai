<?php
/**
 * @var string $startDate
 * @var string $endDate
 * @var int $tahun
 * @var array $calkData
 */
?>
<?= $this->extend('keuangan/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Catatan Atas Laporan Keuangan (CALK)</h1>
        <p class="text-gray-500 text-xs mt-1">Sesuai Standar SAK ETAP</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <button onclick="window.print()" class="px-4 py-2 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center gap-2 shadow-lg shadow-accent/20">
            <i class="fas fa-file-pdf"></i> Cetak CALK
        </button>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="bg-green-500/10 border border-green-500/20 text-green-400 p-4 rounded-xl mb-6 text-sm">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
    
    <!-- Form Edit CALK -->
    <div class="xl:col-span-1 print:hidden">
        <div class="bg-[#111] border border-white/10 rounded-3xl p-6">
            <h3 class="text-white font-bold mb-4 flex items-center gap-2">
                <i class="fas fa-edit text-accent"></i> Edit Narasi CALK
            </h3>
            
            <form action="<?= base_url('keuangan/calk/save') ?>" method="post" class="space-y-4">
                <?= csrf_field() ?>
                <input type="hidden" name="start_date" value="<?= esc($startDate) ?>">
                <input type="hidden" name="end_date" value="<?= esc($endDate) ?>">
                
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">1. Gambaran Umum</label>
                    <textarea name="gambaran_umum" rows="3" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none text-white text-sm"><?= esc($calkData['gambaran_umum'] ?? '') ?></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">2. Kebijakan Akuntansi</label>
                    <textarea name="kebijakan_akuntansi" rows="3" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none text-white text-sm"><?= esc($calkData['kebijakan_akuntansi'] ?? '') ?></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">3. Rincian Kas & Bank</label>
                    <textarea name="rincian_kas" rows="2" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none text-white text-sm"><?= esc($calkData['rincian_kas'] ?? '') ?></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">4. Rincian Piutang</label>
                    <textarea name="rincian_piutang" rows="2" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none text-white text-sm"><?= esc($calkData['rincian_piutang'] ?? '') ?></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">5. Rincian Aset Tetap</label>
                    <textarea name="rincian_aset_tetap" rows="2" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none text-white text-sm"><?= esc($calkData['rincian_aset_tetap'] ?? '') ?></textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">6. Rincian Hutang</label>
                    <textarea name="rincian_hutang" rows="2" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none text-white text-sm"><?= esc($calkData['rincian_hutang'] ?? '') ?></textarea>
                </div>

                <button type="submit" class="w-full py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>

    <!-- Preview / Print Area -->
    <div class="xl:col-span-2">
        <div class="bg-white text-black p-8 md:p-12 border border-white/10 rounded-3xl shadow-2xl print:border-none print:shadow-none print:p-0">
            <div class="text-center mb-8 border-b-2 border-black pb-6">
                <h2 class="text-2xl font-black uppercase tracking-widest">PT ALMA INDONESIA RAYA</h2>
                <h3 class="text-lg font-bold mt-2">CATATAN ATAS LAPORAN KEUANGAN</h3>
                <p class="text-sm mt-1">Untuk Periode yang Berakhir pada <?= date('d F Y') ?></p>
            </div>

            <div class="space-y-8 text-sm leading-relaxed text-justify preview-area">
                
                <section>
                    <h4 class="font-black text-base mb-2">1. GAMBARAN UMUM</h4>
                    <div><?= $calkData['gambaran_umum'] ?? '' ?></div>
                </section>

                <section>
                    <h4 class="font-black text-base mb-2">2. IKHTISAR KEBIJAKAN AKUNTANSI PENTING</h4>
                    <div><?= $calkData['kebijakan_akuntansi'] ?? '' ?></div>
                </section>

                <section>
                    <h4 class="font-black text-base mb-2">3. KAS DAN BANK</h4>
                    <div><?= $calkData['rincian_kas'] ?? '' ?></div>
                </section>

                <section>
                    <h4 class="font-black text-base mb-2">4. PIUTANG USAHA DAN LAINNYA</h4>
                    <div><?= $calkData['rincian_piutang'] ?? '' ?></div>
                </section>

                <section>
                    <h4 class="font-black text-base mb-2">5. ASET TETAP</h4>
                    <div><?= $calkData['rincian_aset_tetap'] ?? '' ?></div>
                </section>

                <section>
                    <h4 class="font-black text-base mb-2">6. HUTANG USAHA</h4>
                    <div><?= $calkData['rincian_hutang'] ?? '' ?></div>
                </section>

                <div class="mt-16 pt-8 border-t border-gray-300">
                    <p class="italic text-xs text-gray-500">Catatan atas laporan keuangan ini merupakan bagian yang tidak terpisahkan dari laporan keuangan secara keseluruhan sesuai dengan Standar Akuntansi Keuangan untuk Entitas Tanpa Akuntabilitas Publik (SAK ETAP).</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.preview-area ul { list-style-type: disc; margin-left: 1.5rem; margin-bottom: 0.5rem; }
.preview-area ol { list-style-type: decimal; margin-left: 1.5rem; margin-bottom: 0.5rem; }
.preview-area p { margin-bottom: 0.5rem; }

/* CKEditor Styles */
.ck-editor__editable {
    min-height: 150px;
    color: #000;
}
.ck-toolbar {
    border-radius: 0.75rem 0.75rem 0 0 !important;
}
.ck-editor__editable {
    border-radius: 0 0 0.75rem 0.75rem !important;
}

@media print {
    body { background: white !important; color: black !important; }
    .md\:ml-64 { margin-left: 0 !important; }
    nav, aside, header, .print\:hidden { display: none !important; }
    main { padding: 0 !important; margin: 0 !important; }
}
</style>

<?= $this->section('scripts') ?>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const textareas = document.querySelectorAll('textarea');
        textareas.forEach(textarea => {
            ClassicEditor
                .create(textarea, {
                    toolbar: [ 'heading', '|', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo' ]
                })
                .catch(error => {
                    console.error(error);
                });
        });
    });
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>
