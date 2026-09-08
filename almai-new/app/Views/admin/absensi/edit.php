<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<style>
    input[type="date"]::-webkit-calendar-picker-indicator,
    input[type="datetime-local"]::-webkit-calendar-picker-indicator {
        filter: invert(1);
    }
</style>

<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="<?= base_url('admin/absensi') ?>" class="p-2 hover:bg-white/10 rounded-lg">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold">Edit Absensi</h1>
            <p class="text-gray-400 text-sm">Edit data absensi acara/kegiatan</p>
        </div>
    </div>

    <div class="bg-[#111] border border-white/10 rounded-xl p-6">
        <form action="<?= base_url('admin/absensi/update/' . urlencode($kegiatanType) . '/' . $kegiatan['id']) ?>" method="POST" class="space-y-6">
            <?= csrf_field() ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Kegiatan (Tipe) <span class="text-red-400">*</span></label>
                    <input type="text" readonly value="<?= ucwords(str_replace('_', ' ', $kegiatanType)) ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3 text-gray-500 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Judul/Nama Layanan <span class="text-red-400">*</span></label>
                    <input type="text" name="judul" required value="<?= esc($kegiatan['judul'] ?? $kegiatan['nama_layanan'] ?? $kegiatan['nama_kegiatan'] ?? $kegiatan['nama'] ?? '') ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3" placeholder="Masukkan judul acara">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Jumlah Klien (Estimasi)</label>
                    <input type="number" name="jumlah_klien" value="<?= esc($kegiatan['jml_peserta'] ?? $kegiatan['jml_klien'] ?? 0) ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Produk</label>
                    <input type="text" name="produk" value="<?= esc($kegiatan['produk'] ?? '') ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3" placeholder="Nama produk (opsional)">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Nama WPA (Mitra) <span class="text-red-400">*</span></label>
                    <select name="wpa_id" required class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        <option value="">-- Pilih WPA --</option>
                        <?php foreach ($wpa_list as $wpa): ?>
                            <option value="<?= $wpa['id'] ?>" <?= ($kegiatan['wpa_id'] ?? '') == $wpa['id'] ? 'selected' : '' ?>><?= esc($wpa['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Nama CWPA</label>
                    <select name="cwpa_id" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        <option value="">-- Pilih CWPA (Opsional) --</option>
                        <?php foreach ($cwpa_list as $cwpa): ?>
                            <option value="<?= $cwpa['id'] ?>" <?= ($kegiatan['cwpa_id'] ?? '') == $cwpa['id'] ? 'selected' : '' ?>><?= esc($cwpa['user_name']) ?> - <?= esc($cwpa['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Lokasi / Media</label>
                    <input type="text" name="lokasi" value="<?= esc($kegiatan['lokasi'] ?? $kegiatan['media'] ?? '') ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3" placeholder="Zoom, Google Meet, Hotel, dll">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Topik</label>
                    <?php
                        $topik = $kegiatan['topik'] ?? $kegiatan['penjelasan_layanan'] ?? '';
                    ?>
                    <input type="text" name="topik" value="<?= esc($topik) ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3" placeholder="Topik pembahasan">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Tanggal Kegiatan <span class="text-red-400">*</span></label>
                    <input type="date" name="tanggal_kegiatan" value="<?= !empty($kegiatan['tanggal']) ? date('Y-m-d', strtotime($kegiatan['tanggal'])) : '' ?>" required class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Waktu Expired Link Absensi</label>
                    <input type="datetime-local" name="expired_link_kode_qr" value="<?= !empty($kegiatan['expired_link_kode_qr']) ? date('Y-m-d\TH:i', strtotime($kegiatan['expired_link_kode_qr'])) : '' ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                    <p class="text-[10px] text-gray-500 mt-1">Biarkan kosong jika link berlaku selamanya</p>
                </div>
            </div>

            <?php $isCwpaWpaOnly = trim((string) ($kegiatan['registration_roles'] ?? '')) === 'cwpa,wpa'; ?>
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Audiens Peserta</label>
                    <select name="registration_audience" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        <option value="public" <?= !$isCwpaWpaOnly ? 'selected' : '' ?>>Semua pengguna</option>
                        <option value="cwpa_wpa" <?= $isCwpaWpaOnly ? 'selected' : '' ?>>Khusus CWPA dan WPA</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-2">Informasi kegiatan tetap publik; hanya check-in yang dibatasi.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Keterangan Tambahan</label>
                    <textarea name="keterangan" rows="3" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3" placeholder="Keterangan opsional..."><?= esc($kegiatan['keterangan'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Format Notif WA</label>
                    <select name="format_notif_wa" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3 text-sm">
                        <option value="">-- Tidak ada Notifikasi --</option>
                        <?php if (isset($whatsapp_templates)) : ?>
                            <?php foreach ($whatsapp_templates as $tpl) : ?>
                                <option value="<?= $tpl['id'] ?>" <?= (isset($kegiatan['format_notif_wa']) && $kegiatan['format_notif_wa'] == $tpl['id']) ? 'selected' : '' ?>><?= esc($tpl['nama_template']) ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <p class="text-xs text-gray-500 mt-2">Template ini dibalas via BalesOtomatis setelah peserta mengirim konfirmasi WhatsApp. Placeholder: {nama}, {event}, {tanggal}, {waktu_absen}, {poin}.</p>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-white/10">
                <button type="submit" class="px-8 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center gap-2">
                    <i class="fas fa-save"></i> Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
