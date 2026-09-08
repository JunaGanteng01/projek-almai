<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>
<div class="w-full pb-12">
    <div class="mb-6 flex flex-col md:flex-row justify-between md:items-center gap-4 px-6 md:px-0">
        <h1 class="text-xl md:text-2xl font-black text-white uppercase tracking-tight">Detail WPA</h1>
        <a href="<?= base_url('laporan-kegiatan/wpa') ?>" class="bg-black/50 hover:bg-black border border-white/10 text-white px-6 py-2 rounded-lg text-xs font-bold uppercase tracking-widest shadow transition flex items-center justify-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl space-y-6">
        <div class="border-b border-white/5 pb-4 mb-4 flex items-center gap-4">
            <div class="w-16 h-16 rounded-full bg-accent/20 flex items-center justify-center text-accent text-xl font-bold overflow-hidden border border-white/10">
                <?= strtoupper(substr($wpa['name'], 0, 2)) ?>
            </div>
            <div>
                <h2 class="text-lg font-bold text-accent uppercase tracking-widest">
                    <?= esc($wpa['name']) ?>
                </h2>
                <p class="text-sm text-gray-400">Wakil Pialang Berjangka (WPA)</p>
            </div>
            <div class="ml-auto">
                <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider <?= $wpa['status'] === 'active' ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' ?>">
                    <?= esc($wpa['status'] === 'active' ? 'Aktif' : 'Tidak Aktif') ?>
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="col-span-1 md:col-span-2 space-y-4">
                <div class="border-b border-white/5 pb-2 mb-4">
                    <h3 class="text-sm font-bold text-accent uppercase tracking-widest flex items-center gap-2">
                        <i class="fas fa-certificate"></i> Lisensi & Sertifikasi
                    </h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">NIK WPA</p>
                        <p class="text-white text-sm font-mono"><?= esc($wpa['nik_wpa'] ?? '-') ?: '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">No. Izin WPA</p>
                        <p class="text-white text-sm font-mono"><?= esc($wpa['nomor_izin_wpa'] ?? '-') ?: '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Tgl Pemberian Izin</p>
                        <p class="text-white text-sm"><?= esc($wpa['tanggal_izin_wpa'] ?? '-') ?: '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Masa Berlaku</p>
                        <p class="text-white text-sm"><?= esc($wpa['masa_berlaku'] ?? '-') ?: '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Sertifikat ASPEBTINDO</p>
                        <p class="text-white text-sm font-mono"><?= esc($wpa['no_sertifikat_aspebtindo'] ?? '-') ?: '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Sertifikat BI</p>
                        <p class="text-white text-sm font-mono"><?= esc($wpa['no_sertifikat_bi'] ?? '-') ?: '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Sertifikat Aspebtindo/BNSP</p>
                        <p class="text-white text-sm font-mono"><?= esc($wpa['no_sertifikat_bnsp'] ?? '-') ?: '-' ?></p>
                    </div>
                </div>
            </div>

            <div class="col-span-1 md:col-span-2 space-y-4">
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Keterangan</p>
                    <div class="text-white text-sm bg-black/30 p-4 rounded-xl border border-white/5 whitespace-pre-wrap">
                        <?= esc($wpa['keterangan'] ?? '-') ?: '-' ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
