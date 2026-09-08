<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>
<?php 
$isEdit = isset($wpa);
$actionUrl = $isEdit ? base_url('laporan-kegiatan/wpa/update/'.$wpa['id']) : base_url('laporan-kegiatan/wpa/store');
?>

<style>
    input[type="date"]::-webkit-calendar-picker-indicator {
        filter: invert(1);
    }
</style>

<div class="w-full pb-12">
    <div class="mb-6 flex flex-col md:flex-row justify-between md:items-center gap-4 px-6 md:px-0">
        <h1 class="text-xl md:text-2xl font-black text-white uppercase tracking-tight"><?= $isEdit ? 'Edit WPA' : 'Tambah WPA' ?></h1>
        <a href="<?= base_url('laporan-kegiatan/wpa') ?>" class="bg-black/50 hover:bg-black border border-white/10 text-white px-6 py-2 rounded-lg text-xs font-bold uppercase tracking-widest shadow transition flex items-center justify-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form action="<?= $actionUrl ?>" method="POST" class="space-y-6">
        <?= csrf_field() ?>

        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl space-y-6">
            <div class="border-b border-white/5 pb-4 mb-4">
                <h2 class="text-lg font-bold text-accent uppercase tracking-widest flex items-center gap-2">
                    <i class="fas fa-user-tie"></i> Informasi WPA
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama WPA -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Nama Wakil Penasihat Berjangka *</label>
                    <input type="text" name="name" value="<?= esc($wpa['name'] ?? '') ?>" required class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors placeholder-gray-600" placeholder="Masukkan nama lengkap">
                </div>

                <!-- Jabatan -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Jabatan</label>
                    <input type="text" name="jabatan" value="<?= esc($wpa['jabatan'] ?? '') ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors placeholder-gray-600" placeholder="Contoh: Direktur">
                </div>

                <!-- Tanggal Menjabat -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Tanggal Menjabat</label>
                    <input type="date" name="tanggal_menjabat" value="<?= esc($wpa['tanggal_menjabat'] ?? '') ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors">
                </div>

                <!-- NIK WPA -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">NIK WPA</label>
                    <input type="text" name="nik_wpa" value="<?= esc($wpa['nik_wpa'] ?? '') ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors placeholder-gray-600" placeholder="16 digit NIK" pattern="\d{16}" minlength="16" maxlength="16" title="NIK harus terdiri dari 16 digit angka" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>

                <!-- Nomor Izin WPA -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Nomor Izin WPA</label>
                    <input type="text" name="nomor_izin_wpa" value="<?= esc($wpa['nomor_izin_wpa'] ?? '') ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors placeholder-gray-600" placeholder="Contoh: 0004/UPTP/SI-WPA/2/2024">
                </div>

                <!-- Tanggal Izin WPA -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Tanggal Pemberian Izin</label>
                    <input type="date" name="tanggal_izin_wpa" value="<?= esc($wpa['tanggal_izin_wpa'] ?? '') ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors">
                </div>

                <!-- No Sertifikat Aspebtindo -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">No. Sertifikat ASPEBTINDO</label>
                    <input type="text" name="no_sertifikat_aspebtindo" value="<?= esc($wpa['no_sertifikat_aspebtindo'] ?? '') ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors placeholder-gray-600">
                </div>

                <!-- No Sertifikat BI -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">No. Sertifikat Bank Indonesia</label>
                    <input type="text" name="no_sertifikat_bi" value="<?= esc($wpa['no_sertifikat_bi'] ?? '') ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors placeholder-gray-600">
                </div>

                <!-- No Sertifikat BNSP -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">No. Sertifikat ASPEBTINDO/BNSP</label>
                    <input type="text" name="no_sertifikat_bnsp" value="<?= esc($wpa['no_sertifikat_bnsp'] ?? '') ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors placeholder-gray-600">
                </div>

                <!-- Masa Berlaku -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Masa Berlaku</label>
                    <input type="date" name="masa_berlaku" value="<?= esc($wpa['masa_berlaku'] ?? '') ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Status WPA</label>
                    <select name="status" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors">
                        <option value="active" <?= ($wpa['status'] ?? '') === 'active' ? 'selected' : '' ?>>Aktif</option>
                        <option value="inactive" <?= ($wpa['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
                    </select>
                </div>

                <!-- Keterangan -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Keterangan</label>
                    <textarea name="keterangan" rows="3" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors placeholder-gray-600" placeholder="Catatan atau keterangan tambahan (opsional)"><?= esc($wpa['keterangan'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="bg-accent hover:bg-white text-black px-10 py-4 rounded-xl font-black uppercase tracking-widest shadow-lg transition-all flex items-center gap-3 group">
                <i class="fas fa-save group-hover:scale-110 transition-transform"></i>
                Simpan Data WPA
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
