<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>

<div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
    <div>
        <h1 class="text-2xl font-bold text-white mb-2">Laporan Daftar Wakil Penasihat Derivatif PUVA dan Kepemilikan Sertifikasi Kompetensi</h1>
        <p class="text-gray-400 text-sm">Laporan rutin terkait kepatuhan regulasi Bank Indonesia.</p>
    </div>
</div>

<div class="bg-[#111] rounded-2xl border border-white/5 overflow-hidden">
    <div class="p-6 border-b border-white/5">
        <h3 class="text-lg font-bold text-white">Daftar WPA / Sertifikasi</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-black/50 border-b border-white/10">
                    <th class="p-4 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">No</th>
                    <th class="p-4 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">Nama Lengkap</th>
                    <th class="p-4 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">Jabatan</th>
                    <th class="p-4 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">Tanggal Menjabat</th>
                    <th class="p-4 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">Nomor WPA Bank Indonesia</th>
                    <th class="p-4 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">Nomor Sertifikat</th>
                    <th class="p-4 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">Tanggal Kadaluarsa Sertifikat</th>
                    <th class="p-4 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">Penyelenggara Sertifikasi</th>
                    <th class="p-4 text-xs font-semibold text-gray-400 uppercase tracking-wider whitespace-nowrap">Nomor Anggota Asosiasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($wpaList)): ?>
                <tr class="hover:bg-white/[0.02] transition-colors">
                    <td colspan="9" class="p-8 text-center text-gray-500 text-sm">
                        Belum ada data laporan Bank Indonesia
                    </td>
                </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($wpaList as $wpa): ?>
                    <tr class="hover:bg-white/[0.02] transition-colors">
                        <td class="p-4 text-sm text-gray-300"><?= $no++ ?></td>
                        <td class="p-4 text-sm text-white font-medium"><?= esc($wpa['name'] ?? '-') ?></td>
                        <td class="p-4 text-sm text-gray-300"><?= esc($wpa['jabatan'] ?? '-') ?></td>
                        <td class="p-4 text-sm text-gray-300"><?= (!empty($wpa['tanggal_menjabat']) && $wpa['tanggal_menjabat'] !== '0000-00-00') ? date('d-m-Y', strtotime($wpa['tanggal_menjabat'])) : '-' ?></td>
                        <td class="p-4 text-sm text-gray-300"><?= esc($wpa['no_sertifikat_bi'] ?? '-') ?></td>
                        <td class="p-4 text-sm text-gray-300"><?= esc($wpa['nomor_izin_wpa'] ?? '-') ?></td>
                        <td class="p-4 text-sm text-gray-300"><?= (!empty($wpa['masa_berlaku']) && $wpa['masa_berlaku'] !== '0000-00-00') ? date('d-m-Y', strtotime($wpa['masa_berlaku'])) : '-' ?></td>
                        <td class="p-4 text-sm text-gray-300">Bappebti / Aspebtindo</td>
                        <td class="p-4 text-sm text-gray-300"><?= esc($wpa['no_sertifikat_bnsp'] ?? $wpa['no_sertifikat_aspebtindo'] ?? '-') ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
