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
        <form action="<?= base_url('laporan-kegiatan/dokumen-arsip/' . ($row ? 'update-legalitas/'.$row['id'] : 'store-legalitas')) ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
            <?= csrf_field() ?>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Nama Dokumen</label>
                <input type="text" name="nama_dokumen" value="<?= esc($row['nama_dokumen'] ?? '') ?>" required class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Nomor Dokumen</label>
                <input type="text" name="nomor_dokumen" value="<?= esc($row['nomor_dokumen'] ?? '') ?>" required class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Tanggal Terbit</label>
                    <input type="date" name="tanggal_terbit" value="<?= $row['tanggal_terbit'] ?? '' ?>" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Tanggal Expired</label>
                    <input type="date" name="tanggal_expired" value="<?= $row['tanggal_expired'] ?? '' ?>" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white">
                </div>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Diterbitkan Oleh</label>
                <input type="text" name="diterbitkan_oleh" value="<?= esc($row['diterbitkan_oleh'] ?? '') ?>" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Status</label>
                <select name="status" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white">
                    <option value="Aktif" <?= ($row['status'] ?? '') == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                    <option value="Proses Perpanjangan" <?= ($row['status'] ?? '') == 'Proses Perpanjangan' ? 'selected' : '' ?>>Proses Perpanjangan</option>
                    <option value="Tidak Aktif" <?= ($row['status'] ?? '') == 'Tidak Aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Upload File <?= $row ? '(Opsional)' : '' ?></label>
                <input type="file" name="file_path" accept=".pdf,.doc,.docx" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white text-sm">
                <?php if($row && $row['file_path']): ?>
                    <p class="text-xs text-gray-500 mt-2">File saat ini: <a href="<?= base_url($row['file_path']) ?>" target="_blank" class="text-blue-400 hover:underline">Lihat PDF</a></p>
                <?php endif; ?>
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
