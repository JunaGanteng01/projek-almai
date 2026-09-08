<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>
<div class="w-full max-w-4xl pb-12">
    <!-- Header -->
    <div class="mb-6 flex items-center gap-4 px-6 md:px-0">
        <a href="<?= base_url('laporan-kegiatan/pedoman-perilaku') ?>" class="text-gray-400 hover:text-white transition">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-xl md:text-2xl font-black text-white uppercase tracking-tight">Edit Pedoman Perilaku</h1>
    </div>

    <!-- Form -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl">
        <div class="mb-6 pb-6 border-b border-white/5">
            <h2 class="text-accent font-bold uppercase tracking-wider mb-2">Informasi Transaksi</h2>
            <div class="grid grid-cols-2 gap-4 text-sm text-gray-300">
                <div>
                    <span class="block text-xs text-gray-500 uppercase">No. Invoice</span>
                    <strong><?= esc($transaksi['invoice_number']) ?></strong>
                </div>
                <div>
                    <span class="block text-xs text-gray-500 uppercase">Layanan</span>
                    <strong><?= esc($transaksi['product_name']) ?></strong>
                </div>
            </div>
        </div>

        <form action="<?= base_url('laporan-kegiatan/pedoman-perilaku/update/'.$transaksi['id']) ?>" method="POST" class="space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- No Akun -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">No. Akun</label>
                    <input type="text" name="no_akun" value="<?= esc($pedoman['no_akun']) ?>" placeholder="Masukkan No. Akun..." class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-accent">
                </div>

                <!-- Latar Belakang -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Form & Kuesioner (Latar Belakang)*</label>
                    <input type="text" name="kuesioner_latar_belakang" value="<?= esc($pedoman['kuesioner_latar_belakang']) ?>" placeholder="Contoh: Selesai / 12 Jan 2024" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-accent">
                </div>
            </div>

            <!-- Profil Risiko & Perjanjian -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Form (Profil Risiko)*</label>
                    <input type="text" name="kuesioner_profil_risiko" value="<?= esc($pedoman['kuesioner_profil_risiko']) ?>" placeholder="Status/Tanggal" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-accent">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Perjanjian (Penjelasan)*</label>
                    <input type="text" name="perjanjian_jasa_penjelasan" value="<?= esc($pedoman['perjanjian_jasa_penjelasan']) ?>" placeholder="Tanggal Penjelasan" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-accent">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Perjanjian (TTD)*</label>
                    <input type="text" name="perjanjian_jasa_ttd" value="<?= esc($pedoman['perjanjian_jasa_ttd']) ?>" placeholder="Tanggal TTD" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-accent">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">No. Dok. Perjanjian</label>
                    <input type="text" name="no_dok_perjanjian" value="<?= esc($pedoman['no_dok_perjanjian']) ?>" placeholder="Nomor Dokumen" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-accent">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Ket. Perusahaan (Penjelasan)*</label>
                    <input type="text" name="ket_perusahaan_penjelasan" value="<?= esc($pedoman['ket_perusahaan_penjelasan']) ?>" placeholder="Tanggal Penjelasan" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-accent">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Ket. Perusahaan (TTD)*</label>
                    <input type="text" name="ket_perusahaan_ttd" value="<?= esc($pedoman['ket_perusahaan_ttd']) ?>" placeholder="Tanggal TTD" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-accent">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Pernyataan Risiko (Penjelasan)*</label>
                    <input type="text" name="pernyataan_risiko_penjelasan" value="<?= esc($pedoman['pernyataan_risiko_penjelasan']) ?>" placeholder="Tanggal Penjelasan" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-accent">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Pernyataan Risiko (TTD)*</label>
                    <input type="text" name="pernyataan_risiko_ttd" value="<?= esc($pedoman['pernyataan_risiko_ttd']) ?>" placeholder="Tanggal TTD" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-accent">
                </div>
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Keterangan</label>
                <textarea name="keterangan" rows="4" placeholder="Keterangan tambahan..." class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-accent"><?= esc($pedoman['keterangan']) ?></textarea>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-4 pt-6 border-t border-white/10">
                <a href="<?= base_url('laporan-kegiatan/pedoman-perilaku') ?>" class="px-6 py-3 rounded-xl text-sm font-bold text-gray-400 hover:text-white transition uppercase tracking-widest">Batal</a>
                <button type="submit" class="bg-accent hover:bg-white text-black px-8 py-3 rounded-xl text-sm font-bold uppercase tracking-widest transition shadow-[0_0_20px_rgba(51,232,24,0.3)]">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
