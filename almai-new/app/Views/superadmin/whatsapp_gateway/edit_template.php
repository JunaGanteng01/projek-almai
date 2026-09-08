<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6 max-w-4xl mx-auto">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold"><?= $title ?></h1>
            <p class="text-gray-400 text-sm">Edit format pesan notifikasi WhatsApp</p>
        </div>
        <a href="<?= base_url('superadmin/whatsapp-gateway/templates') ?>" class="px-4 py-2 bg-white/5 text-white font-medium rounded-lg hover:bg-white/10 transition text-sm flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="bg-[#111] border border-white/10 rounded-xl overflow-hidden">
        <form action="<?= base_url('superadmin/whatsapp-gateway/templates/update/' . $template['id']) ?>" method="post">
            <?= csrf_field() ?>
            <div class="p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Nama Template</label>
                    <input type="text" name="nama_template" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3 focus:outline-none focus:border-accent transition" placeholder="Contoh: Notifikasi Seminar" required value="<?= old('nama_template', $template['nama_template']) ?>">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Isi Pesan Notifikasi</label>
                    <textarea name="isi_pesan" rows="8" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3 focus:outline-none focus:border-accent transition" placeholder="Ketikkan format pesan notifikasi di sini..." required><?= old('isi_pesan', $template['isi_pesan']) ?></textarea>
                    <p class="mt-2 text-xs text-gray-500">Untuk notifikasi absensi, tersedia placeholder: {nama}, {event}, {tanggal}, {waktu_absen}, dan {poin}.</p>
                </div>
            </div>
            
            <div class="p-6 border-t border-white/10 bg-black/20 flex justify-end gap-3">
                <a href="<?= base_url('superadmin/whatsapp-gateway/templates') ?>" class="px-6 py-2.5 bg-white/5 hover:bg-white/10 rounded-xl transition text-sm font-medium">Batal</a>
                <button type="submit" class="px-6 py-2.5 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center gap-2 text-sm">
                    <i class="fas fa-save"></i> Perbarui Template
                </button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
