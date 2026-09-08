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
        <form action="<?= base_url('laporan-kegiatan/dokumen-arsip/' . ($row ? 'update-izin-wpa/'.$row['id'] : 'store-izin-wpa')) ?>" method="POST" class="space-y-4">
            <?= csrf_field() ?>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Pilih WPA</label>
                <select name="wpa_id" required class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white">
                    <option value="">-- Pilih WPA --</option>
                    <?php foreach($wpaList as $w): ?>
                        <option value="<?= $w['id'] ?>" <?= ($row['wpa_id'] ?? '') == $w['id'] ? 'selected' : '' ?>><?= esc($w['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Nomor Izin</label>
                <input type="text" name="nomor_izin" value="<?= esc($row['nomor_izin'] ?? '') ?>" required class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Tanggal Izin</label>
                    <input type="date" name="tanggal_izin" value="<?= $row['tanggal_izin'] ?? '' ?>" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Tanggal Expired</label>
                    <input type="date" name="tanggal_expired" value="<?= $row['tanggal_expired'] ?? '' ?>" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white">
                </div>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Gelar/Sertifikat</label>
                <input type="text" name="gelar_sertifikat" value="<?= esc($row['gelar_sertifikat'] ?? '') ?>" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white">
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
                <label class="block text-sm text-gray-400 mb-1">Catatan</label>
                <textarea name="catatan" rows="3" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"><?= esc($row['catatan'] ?? '') ?></textarea>
            </div>
            
            <div class="pt-4 flex justify-end gap-3 border-t border-white/5">
                <a href="<?= base_url('laporan-kegiatan/dokumen-arsip') ?>" class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-white/5 transition">Batal</a>
                <button type="submit" class="bg-accent hover:bg-accent/80 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition">Simpan</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
