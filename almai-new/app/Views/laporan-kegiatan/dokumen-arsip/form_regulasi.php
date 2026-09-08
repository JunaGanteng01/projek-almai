<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white mb-2"><?= esc($title) ?></h1>
            <p class="text-gray-400 text-sm">Kembali ke halaman <a href="<?= base_url('laporan-kegiatan/dokumen-arsip') ?>" class="text-accent hover:underline">Dokumen Arsip</a></p>
        </div>
    </div>

    <div class="bg-[#111] rounded-xl border border-white/5 shadow-xl p-6 max-w-3xl">
        <form action="<?= base_url('laporan-kegiatan/dokumen-arsip/' . ($row ? 'update-regulasi/'.$row['id'] : 'store-regulasi')) ?>" method="POST" class="space-y-4">
            <?= csrf_field() ?>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Nomor Regulasi</label>
                <input type="text" name="nomor_regulasi" value="<?= esc($row['nomor_regulasi'] ?? '') ?>" required class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Tentang</label>
                <input type="text" name="tentang" value="<?= esc($row['tentang'] ?? '') ?>" required class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Keterangan</label>
                <textarea name="keterangan" rows="3" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"><?= esc($row['keterangan'] ?? '') ?></textarea>
            </div>
            
            <div class="pt-4 flex justify-end gap-3 border-t border-white/5">
                <a href="<?= base_url('laporan-kegiatan/dokumen-arsip') ?>" class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-white/5 transition">Batal</a>
                <button type="submit" class="bg-accent hover:bg-accent/80 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition">Simpan</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
