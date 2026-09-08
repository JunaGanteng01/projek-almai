<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>
<div class="w-full pb-12">
    <div class="mb-6 flex flex-col md:flex-row justify-between md:items-center gap-4 px-6 md:px-0">
        <h1 class="text-xl md:text-2xl font-black text-white uppercase tracking-tight">Detail CWPA</h1>
        <a href="<?= base_url('laporan-kegiatan/cwpa') ?>" class="bg-black/50 hover:bg-black border border-white/10 text-white px-6 py-2 rounded-lg text-xs font-bold uppercase tracking-widest shadow transition flex items-center justify-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl space-y-6">
        <div class="border-b border-white/5 pb-4 mb-4 flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-accent/20 flex items-center justify-center text-accent text-xl font-bold overflow-hidden border border-white/10">
                <?php if (!empty($cwpa['photo'])): ?>
                    <img src="<?= base_url('file/' . ltrim(preg_replace('/^writable\//', '', $cwpa['photo']), '/')) ?>" class="w-full h-full object-cover">
                <?php else: ?>
                    <?= strtoupper(substr($cwpa['name'], 0, 2)) ?>
                <?php endif; ?>
            </div>
            <div>
                <h2 class="text-lg font-bold text-accent uppercase tracking-widest">
                    <?= esc($cwpa['name']) ?>
                </h2>
                <p class="text-sm text-gray-400"><?= esc($cwpa['user_email'] ?? 'Tidak ada email') ?></p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Basic Info -->
            <div class="space-y-4">
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Spesialisasi</p>
                    <p class="text-white text-sm"><?= esc($cwpa['specialty'] ?? '-') ?></p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Universitas</p>
                    <p class="text-white text-sm"><?= esc($cwpa['university'] ?? '-') ?></p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Batch / Angkatan</p>
                    <p class="text-white text-sm"><?= esc($cwpa['batch'] ?? '-') ?></p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Status</p>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider <?= $cwpa['status'] === 'active' ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' ?>">
                        <?= esc($cwpa['status']) ?>
                    </span>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="space-y-4">
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Fase Saat Ini</p>
                    <p class="text-white text-sm"><?= esc(\App\Models\CwpaModel::getPhaseLabel($cwpa['current_phase'] ?? 1)) ?></p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">No. HP / Telepon</p>
                    <p class="text-white text-sm"><?= esc($cwpa['user_phone'] ?? '-') ?></p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Nama Asli (User)</p>
                    <p class="text-white text-sm"><?= esc($cwpa['user_real_name'] ?? '-') ?></p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Rating</p>
                    <p class="text-white text-sm">
                        <i class="fas fa-star text-yellow-500 text-xs mr-1"></i>
                        <?= esc($cwpa['rating'] ?? '0') ?>
                    </p>
                </div>
            </div>
            
            <div class="col-span-1 md:col-span-2 space-y-4">
                <div class="border-b border-white/5 pb-2 mb-4 mt-4">
                    <h3 class="text-sm font-bold text-accent uppercase tracking-widest flex items-center gap-2">
                        <i class="fas fa-certificate"></i> Lisensi & Sertifikasi Tambahan
                    </h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">NIK CWPA</p>
                        <p class="text-white text-sm"><?= esc($cwpa['nik_cwpa'] ?? '-') ?: '-' ?></p>
                    </div>
                    <div class="min-w-0 overflow-hidden">
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Almai Pendampingan</p>
                        <p class="text-white text-sm mb-2"><?= esc($cwpa['almai_pendampingan'] ?? '-') ?: '-' ?></p>
                        <?php if(!empty($cwpa['almai_pendampingan_file'])): ?>
                            <?php $imgSrc = strpos($cwpa['almai_pendampingan_file'], 'uploads/certificates/') === 0 ? base_url('file/' . $cwpa['almai_pendampingan_file']) : base_url($cwpa['almai_pendampingan_file']); ?>
                            <a href="<?= $imgSrc ?>" target="_blank" class="relative block w-72 h-40 bg-black/50 border border-white/10 rounded-lg p-2 hover:bg-black transition-colors overflow-hidden">
                                <img src="<?= $imgSrc ?>" class="absolute inset-0 w-full h-full object-contain p-2 hover:opacity-80 transition-opacity">
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="min-w-0 overflow-hidden">
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Bursa ICDX Sertifikasi Multilateral</p>
                        <p class="text-white text-sm mb-2"><?= esc($cwpa['bursa_icdx_sertifikasi_multilateral'] ?? '-') ?: '-' ?></p>
                        <?php if(!empty($cwpa['bursa_icdx_sertifikasi_multilateral_file'])): ?>
                            <?php $imgSrc = strpos($cwpa['bursa_icdx_sertifikasi_multilateral_file'], 'uploads/certificates/') === 0 ? base_url('file/' . $cwpa['bursa_icdx_sertifikasi_multilateral_file']) : base_url($cwpa['bursa_icdx_sertifikasi_multilateral_file']); ?>
                            <a href="<?= $imgSrc ?>" target="_blank" class="relative block w-72 h-40 bg-black/50 border border-white/10 rounded-lg p-2 hover:bg-black transition-colors overflow-hidden">
                                <img src="<?= $imgSrc ?>" class="absolute inset-0 w-full h-full object-contain p-2 hover:opacity-80 transition-opacity">
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="min-w-0 overflow-hidden">
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">LPK Sertifikasi Pelatihan PBK</p>
                        <p class="text-white text-sm mb-2"><?= esc($cwpa['lpk_sertifikasi_pelatihan_pbk'] ?? '-') ?: '-' ?></p>
                        <?php if(!empty($cwpa['lpk_sertifikasi_pelatihan_pbk_file'])): ?>
                            <?php $imgSrc = strpos($cwpa['lpk_sertifikasi_pelatihan_pbk_file'], 'uploads/certificates/') === 0 ? base_url('file/' . $cwpa['lpk_sertifikasi_pelatihan_pbk_file']) : base_url($cwpa['lpk_sertifikasi_pelatihan_pbk_file']); ?>
                            <a href="<?= $imgSrc ?>" target="_blank" class="relative block w-72 h-40 bg-black/50 border border-white/10 rounded-lg p-2 hover:bg-black transition-colors overflow-hidden">
                                <img src="<?= $imgSrc ?>" class="absolute inset-0 w-full h-full object-contain p-2 hover:opacity-80 transition-opacity">
                            </a>
                        <?php endif; ?>
                    </div>
                    <div class="min-w-0 overflow-hidden">
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">BNSP Sertifikasi Kompetensi</p>
                        <p class="text-white text-sm mb-2"><?= esc($cwpa['bnsp_sertifikasi_kompetensi'] ?? '-') ?: '-' ?></p>
                        <?php if(!empty($cwpa['bnsp_sertifikasi_kompetensi_file'])): ?>
                            <?php $imgSrc = strpos($cwpa['bnsp_sertifikasi_kompetensi_file'], 'uploads/certificates/') === 0 ? base_url('file/' . $cwpa['bnsp_sertifikasi_kompetensi_file']) : base_url($cwpa['bnsp_sertifikasi_kompetensi_file']); ?>
                            <a href="<?= $imgSrc ?>" target="_blank" class="relative block w-72 h-40 bg-black/50 border border-white/10 rounded-lg p-2 hover:bg-black transition-colors overflow-hidden">
                                <img src="<?= $imgSrc ?>" class="absolute inset-0 w-full h-full object-contain p-2 hover:opacity-80 transition-opacity">
                            </a>
                        <?php endif; ?>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Keterangan</p>
                        <p class="text-white text-sm"><?= esc($cwpa['keterangan'] ?? '-') ?: '-' ?></p>
                    </div>
                </div>
            </div>

            <div class="col-span-1 md:col-span-2 space-y-4">
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Bio / Pengalaman</p>
                    <p class="text-white text-sm bg-black/30 p-4 rounded-xl border border-white/5">
                        <?= nl2br(esc($cwpa['bio'] ?? $cwpa['experience'] ?? '-')) ?>
                    </p>
                </div>
                
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Sosial Media</p>
                    <div class="flex gap-4">
                        <?php if(!empty($cwpa['instagram'])): ?>
                            <a href="<?= esc($cwpa['instagram']) ?>" target="_blank" class="text-pink-500 hover:text-pink-400"><i class="fab fa-instagram fa-lg"></i></a>
                        <?php endif; ?>
                        <?php if(!empty($cwpa['youtube'])): ?>
                            <a href="<?= esc($cwpa['youtube']) ?>" target="_blank" class="text-red-500 hover:text-red-400"><i class="fab fa-youtube fa-lg"></i></a>
                        <?php endif; ?>
                        <?php if(!empty($cwpa['tiktok'])): ?>
                            <a href="<?= esc($cwpa['tiktok']) ?>" target="_blank" class="text-white hover:text-gray-300"><i class="fab fa-tiktok fa-lg"></i></a>
                        <?php endif; ?>
                        <?php if(empty($cwpa['instagram']) && empty($cwpa['youtube']) && empty($cwpa['tiktok'])): ?>
                            <p class="text-sm text-gray-500">-</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
