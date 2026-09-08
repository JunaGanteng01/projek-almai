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
        <form action="<?= base_url('laporan-kegiatan/dokumen-arsip/' . ($row ? 'update-bahan-kegiatan/'.$row['id'] : 'store-bahan-kegiatan')) ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
            <?= csrf_field() ?>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Judul Bahan</label>
                <input type="text" name="judul_bahan" value="<?= esc($row['judul_bahan'] ?? '') ?>" required class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Jenis Kegiatan</label>
                <input type="text" name="jenis_kegiatan" value="<?= esc($row['jenis_kegiatan'] ?? '') ?>" required class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Tanggal Pengajuan</label>
                    <input type="date" name="tgl_pengajuan" value="<?= $row['tgl_pengajuan'] ?? '' ?>" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Tanggal Persetujuan</label>
                    <input type="date" name="tgl_persetujuan" value="<?= $row['tgl_persetujuan'] ?? '' ?>" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white">
                </div>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">No Surat Persetujuan</label>
                <input type="text" name="no_surat_persetujuan" value="<?= esc($row['no_surat_persetujuan'] ?? '') ?>" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Status</label>
                <select name="status" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white">
                    <option value="Disetujui" <?= ($row['status'] ?? '') == 'Disetujui' ? 'selected' : '' ?>>Disetujui</option>
                    <option value="Dalam Proses" <?= ($row['status'] ?? 'Dalam Proses') == 'Dalam Proses' ? 'selected' : '' ?>>Dalam Proses</option>
                    <option value="Ditolak" <?= ($row['status'] ?? '') == 'Ditolak' ? 'selected' : '' ?>>Ditolak</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Upload File <?= $row ? '(Opsional)' : '' ?></label>
                <input type="file" name="file_path" accept=".pdf,.doc,.docx" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white text-sm">
                <?php if($row && $row['file_path']): ?>
                    <p class="text-xs text-gray-500 mt-2">File saat ini: <a href="<?= base_url($row['file_path']) ?>" target="_blank" class="text-blue-400 hover:underline">Lihat PDF</a></p>
                <?php endif; ?>
            </div>
            
            <div class="pt-4 flex justify-end gap-3 border-t border-white/5">
                <a href="<?= base_url('laporan-kegiatan/dokumen-arsip') ?>" class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-white/5 transition">Batal</a>
                <button type="submit" class="bg-accent hover:bg-accent/80 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition">Simpan</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
