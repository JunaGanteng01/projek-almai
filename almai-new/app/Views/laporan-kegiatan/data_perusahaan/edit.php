<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>
<div class="w-full pb-12 max-w-3xl mx-auto">
    <div class="mb-6 flex justify-between items-center gap-4 px-6 md:px-0">
        <h1 class="text-xl md:text-2xl font-black text-white uppercase tracking-tight">Edit Data Perusahaan</h1>
        <a href="<?= base_url('laporan-kegiatan/data-perusahaan') ?>" class="text-gray-400 hover:text-white transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form action="<?= base_url('laporan-kegiatan/data-perusahaan/update/'.$perusahaan['id']) ?>" method="post" class="space-y-6">
        <?= csrf_field() ?>
        
        <div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-xl">
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Nama Perusahaan <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_perusahaan" value="<?= esc($perusahaan['nama_perusahaan']) ?>" class="w-full bg-[#1a1a1a] border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Nomor Izin</label>
                    <input type="text" name="nomor_izin" value="<?= esc($perusahaan['nomor_izin']) ?>" class="w-full bg-[#1a1a1a] border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Direktur Utama</label>
                    <input type="text" name="direktur_utama" value="<?= esc($perusahaan['direktur_utama']) ?>" class="w-full bg-[#1a1a1a] border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Website</label>
                    <input type="text" name="website" value="<?= esc($perusahaan['website']) ?>" class="w-full bg-[#1a1a1a] border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Alamat Lengkap</label>
                    <textarea name="alamat" rows="3" class="w-full bg-[#1a1a1a] border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors"><?= esc($perusahaan['alamat']) ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Status</label>
                    <select name="status" class="w-full bg-[#1a1a1a] border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors">
                        <option value="active" <?= $perusahaan['status'] === 'active' ? 'selected' : '' ?>>Aktif</option>
                        <option value="inactive" <?= $perusahaan['status'] === 'inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Keterangan</label>
                    <textarea name="keterangan" rows="2" class="w-full bg-[#1a1a1a] border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors"><?= esc($perusahaan['keterangan']) ?></textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <button type="submit" class="px-6 py-3 bg-accent text-white font-medium rounded-xl hover:bg-accent/80 transition-colors shadow-lg shadow-accent/20 flex items-center gap-2">
                <i class="fas fa-save"></i> Perbarui Data
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
