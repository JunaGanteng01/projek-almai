<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>
<?php 
$isEdit = isset($cwpa);
$actionUrl = $isEdit ? base_url('laporan-kegiatan/cwpa/update/'.$cwpa['id']) : base_url('laporan-kegiatan/cwpa/store');
?>

<div class="w-full pb-12">
    <div class="mb-6 flex flex-col md:flex-row justify-between md:items-center gap-4 px-6 md:px-0">
        <h1 class="text-xl md:text-2xl font-black text-white uppercase tracking-tight"><?= $isEdit ? 'Edit CWPA' : 'Tambah CWPA' ?></h1>
        <a href="<?= base_url('laporan-kegiatan/cwpa') ?>" class="bg-black/50 hover:bg-black border border-white/10 text-white px-6 py-2 rounded-lg text-xs font-bold uppercase tracking-widest shadow transition flex items-center justify-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form action="<?= $actionUrl ?>" method="POST" class="space-y-6">
        <?= csrf_field() ?>

        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl space-y-6">
            <div class="border-b border-white/5 pb-4 mb-4">
                <h2 class="text-lg font-bold text-accent uppercase tracking-widest flex items-center gap-2">
                    <i class="fas fa-user-graduate"></i> Informasi CWPA
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama CWPA -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Nama Calon WPA *</label>
                    <input type="text" name="name" value="<?= esc($cwpa['name'] ?? '') ?>" required class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors placeholder-gray-600" placeholder="Masukkan nama lengkap">
                </div>

                <!-- Spesialisasi -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Spesialisasi</label>
                    <input type="text" name="specialty" value="<?= esc($cwpa['specialty'] ?? '') ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors placeholder-gray-600" placeholder="Misal: Trading, Saham, dll">
                </div>

                <!-- Universitas -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Universitas</label>
                    <input type="text" name="university" value="<?= esc($cwpa['university'] ?? '') ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors placeholder-gray-600" placeholder="Nama Universitas">
                </div>

                <!-- Batch -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Batch / Angkatan</label>
                    <input type="number" name="batch" value="<?= esc($cwpa['batch'] ?? '') ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors placeholder-gray-600" placeholder="Contoh: 1">
                </div>

                <!-- Phase -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Fase Saat Ini</label>
                    <select name="current_phase" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors">
                        <?php foreach (\App\Models\CwpaModel::PHASES as $key => $label): ?>
                            <option value="<?= $key ?>" <?= ($cwpa['current_phase'] ?? '') == $key ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Status CWPA</label>
                    <select name="status" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors">
                        <option value="active" <?= ($cwpa['status'] ?? '') === 'active' ? 'selected' : '' ?>>Aktif</option>
                        <option value="inactive" <?= ($cwpa['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl space-y-6">
            <div class="border-b border-white/5 pb-4 mb-4">
                <h2 class="text-lg font-bold text-accent uppercase tracking-widest flex items-center gap-2">
                    <i class="fas fa-certificate"></i> Informasi Lisensi & Sertifikasi
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- NIK CWPA -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">NIK CWPA</label>
                    <input type="text" name="nik_cwpa" value="<?= esc($cwpa['nik_cwpa'] ?? '') ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors placeholder-gray-600" placeholder="Masukkan NIK CWPA">
                </div>
                
                <!-- Spacer for alignment -->
                <div class="hidden md:block"></div>

                <!-- Almai Pendampingan -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Almai Pendampingan (Nomor Sertifikat)</label>
                    <input type="text" name="almai_pendampingan" value="<?= esc($cwpa['almai_pendampingan'] ?? '') ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-2 text-white text-xs focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors mb-2" placeholder="Nomor Sertifikat">
                    
                    <?php if(!empty($cwpa['almai_pendampingan_file'])): ?>
                        <?php $imgSrc = strpos($cwpa['almai_pendampingan_file'], 'uploads/certificates/') === 0 ? base_url('file/' . $cwpa['almai_pendampingan_file']) : base_url($cwpa['almai_pendampingan_file']); ?>
                        <div class="mt-2 relative w-32 h-20 rounded-xl overflow-hidden border border-white/10 group mb-2">
                            <img src="<?= $imgSrc ?>" class="w-full h-full object-cover">
                        </div>
                    <?php endif; ?>
                    
                    <input type="file" name="almai_pendampingan_file" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-2 text-white text-xs focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors">
                    <?php if(!empty($cwpa['almai_pendampingan_file'])): ?>
                        <p class="text-[10px] text-green-400 mt-1"><i class="fas fa-check-circle"></i> File sudah diunggah. Pilih file baru untuk mengganti.</p>
                    <?php endif; ?>
                </div>

                <!-- Bursa ICDX -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Bursa ICDX Sertifikasi Multilateral</label>
                    <input type="text" name="bursa_icdx_sertifikasi_multilateral" value="<?= esc($cwpa['bursa_icdx_sertifikasi_multilateral'] ?? '') ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-2 text-white text-xs focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors mb-2" placeholder="Nomor Sertifikat">
                    
                    <?php if(!empty($cwpa['bursa_icdx_sertifikasi_multilateral_file'])): ?>
                        <?php $imgSrc = strpos($cwpa['bursa_icdx_sertifikasi_multilateral_file'], 'uploads/certificates/') === 0 ? base_url('file/' . $cwpa['bursa_icdx_sertifikasi_multilateral_file']) : base_url($cwpa['bursa_icdx_sertifikasi_multilateral_file']); ?>
                        <div class="mt-2 relative w-32 h-20 rounded-xl overflow-hidden border border-white/10 group mb-2">
                            <img src="<?= $imgSrc ?>" class="w-full h-full object-cover">
                        </div>
                    <?php endif; ?>

                    <input type="file" name="bursa_icdx_sertifikasi_multilateral_file" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-2 text-white text-xs focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors">
                    <?php if(!empty($cwpa['bursa_icdx_sertifikasi_multilateral_file'])): ?>
                        <p class="text-[10px] text-green-400 mt-1"><i class="fas fa-check-circle"></i> File sudah diunggah. Pilih file baru untuk mengganti.</p>
                    <?php endif; ?>
                </div>

                <!-- LPK Sertifikasi -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">LPK Sertifikasi Pelatihan PBK</label>
                    <input type="text" name="lpk_sertifikasi_pelatihan_pbk" value="<?= esc($cwpa['lpk_sertifikasi_pelatihan_pbk'] ?? '') ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-2 text-white text-xs focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors mb-2" placeholder="Nomor Sertifikat">
                    
                    <?php if(!empty($cwpa['lpk_sertifikasi_pelatihan_pbk_file'])): ?>
                        <?php $imgSrc = strpos($cwpa['lpk_sertifikasi_pelatihan_pbk_file'], 'uploads/certificates/') === 0 ? base_url('file/' . $cwpa['lpk_sertifikasi_pelatihan_pbk_file']) : base_url($cwpa['lpk_sertifikasi_pelatihan_pbk_file']); ?>
                        <div class="mt-2 relative w-32 h-20 rounded-xl overflow-hidden border border-white/10 group mb-2">
                            <img src="<?= $imgSrc ?>" class="w-full h-full object-cover">
                        </div>
                    <?php endif; ?>

                    <input type="file" name="lpk_sertifikasi_pelatihan_pbk_file" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-2 text-white text-xs focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors">
                    <?php if(!empty($cwpa['lpk_sertifikasi_pelatihan_pbk_file'])): ?>
                        <p class="text-[10px] text-green-400 mt-1"><i class="fas fa-check-circle"></i> File sudah diunggah. Pilih file baru untuk mengganti.</p>
                    <?php endif; ?>
                </div>

                <!-- BNSP Sertifikasi -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">BNSP Sertifikasi Kompetensi</label>
                    <input type="text" name="bnsp_sertifikasi_kompetensi" value="<?= esc($cwpa['bnsp_sertifikasi_kompetensi'] ?? '') ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-2 text-white text-xs focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors mb-2" placeholder="Nomor Sertifikat">
                    
                    <?php if(!empty($cwpa['bnsp_sertifikasi_kompetensi_file'])): ?>
                        <?php $imgSrc = strpos($cwpa['bnsp_sertifikasi_kompetensi_file'], 'uploads/certificates/') === 0 ? base_url('file/' . $cwpa['bnsp_sertifikasi_kompetensi_file']) : base_url($cwpa['bnsp_sertifikasi_kompetensi_file']); ?>
                        <div class="mt-2 relative w-32 h-20 rounded-xl overflow-hidden border border-white/10 group mb-2">
                            <img src="<?= $imgSrc ?>" class="w-full h-full object-cover">
                        </div>
                    <?php endif; ?>

                    <input type="file" name="bnsp_sertifikasi_kompetensi_file" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-2 text-white text-xs focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors">
                    <?php if(!empty($cwpa['bnsp_sertifikasi_kompetensi_file'])): ?>
                        <p class="text-[10px] text-green-400 mt-1"><i class="fas fa-check-circle"></i> File sudah diunggah. Pilih file baru untuk mengganti.</p>
                    <?php endif; ?>
                    </div>

<!-- BAPPEBTI-TLUP Sertifikasi Profesi -->
<div>
    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
        BAPPEBTI-TLUP Sertifikasi Profesi
    </label>

    <input type="text"
        name="bappebti_tlup_sertifikasi_profesi"
        value="<?= esc($cwpa['bappebti_tlup_sertifikasi_profesi'] ?? '') ?>"
        class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-2 text-white text-xs focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors mb-2"
        placeholder="Nomor Sertifikat">

    <?php if(!empty($cwpa['bappebti_tlup_sertifikasi_profesi_file'])): ?>
        <?php
        $imgSrc = strpos($cwpa['bappebti_tlup_sertifikasi_profesi_file'], 'uploads/certificates/') === 0
            ? base_url('file/' . $cwpa['bappebti_tlup_sertifikasi_profesi_file'])
            : base_url($cwpa['bappebti_tlup_sertifikasi_profesi_file']);
        ?>
        <div class="mt-2 relative w-32 h-20 rounded-xl overflow-hidden border border-white/10 group mb-2">
            <img src="<?= $imgSrc ?>" class="w-full h-full object-cover">
        </div>
    <?php endif; ?>

    <input type="file"
        name="bappebti_tlup_sertifikasi_profesi_file"
        class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-2 text-white text-xs focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors">

    <?php if(!empty($cwpa['bappebti_tlup_sertifikasi_profesi_file'])): ?>
        <p class="text-[10px] text-green-400 mt-1">
            <i class="fas fa-check-circle"></i>
            File sudah diunggah. Pilih file baru untuk mengganti.
        </p>
    <?php endif; ?>
</div>
            <!-- Keterangan -->
            <div class="mt-6">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Keterangan</label>
                <textarea name="keterangan" rows="3" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors placeholder-gray-600" placeholder="Tambahan keterangan..."><?= esc($cwpa['keterangan'] ?? '') ?></textarea>
            </div>


        <div class="flex justify-end pt-4">
            <button type="submit" class="bg-accent hover:bg-white text-black px-10 py-4 rounded-xl font-black uppercase tracking-widest shadow-lg transition-all flex items-center gap-3 group">
                <i class="fas fa-save group-hover:scale-110 transition-transform"></i>
                Simpan Data CWPA
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
