<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>

<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="<?= base_url('laporan-kegiatan/absensi') ?>" class="w-10 h-10 bg-white/5 hover:bg-white/10 rounded-xl flex items-center justify-center transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold">Detail Absensi</h1>
            <p class="text-gray-400 text-sm"><?= esc($kegiatan['nama']) ?> (<?= esc($kegiatanType) ?>)</p>
        </div>
    </div>

    <div class="bg-[#111] border border-white/10 rounded-xl overflow-hidden mb-6">
        <div class="p-6 border-b border-white/10">
            <h3 class="font-bold text-lg mb-4">Detail Kegiatan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="text-gray-400 block mb-1">Kegiatan</span>
                    <span class="font-medium"><?= esc($kegiatan['nama'] ?? '-') ?></span>
                </div>
                <div>
                    <span class="text-gray-400 block mb-1">Tipe</span>
                    <span class="font-medium text-accent"><?= esc($kegiatanType) ?></span>
                </div>
                <div>
                    <span class="text-gray-400 block mb-1">Tanggal</span>
                    <span class="font-medium"><?= !empty($kegiatan['tanggal']) ? date('d M Y', strtotime($kegiatan['tanggal'])) : '-' ?></span>
                </div>
                <div>
                    <span class="text-gray-400 block mb-1">WPA</span>
                    <span class="font-medium"><?= esc($kegiatan['wpa_name'] ?? '-') ?></span>
                </div>
                <div>
                    <span class="text-gray-400 block mb-1">CWPA</span>
                    <span class="font-medium"><?= esc($kegiatan['cwpa_name'] ?? '-') ?></span>
                </div>
                <div>
                    <span class="text-gray-400 block mb-1">Jumlah Klien</span>
                    <span class="font-medium"><?= count($peserta) ?></span>
                </div>
                <div>
                    <span class="text-gray-400 block mb-1">Produk</span>
                    <span class="font-medium"><?= esc($kegiatan['produk'] ?? '-') ?></span>
                </div>
                <div>
                    <span class="text-gray-400 block mb-1">Lokasi / Media</span>
                    <span class="font-medium"><?= esc($kegiatan['lokasi'] ?? $kegiatan['media'] ?? '-') ?></span>
                </div>
                <div>
                    <span class="text-gray-400 block mb-1">Topik</span>
                    <span class="font-medium"><?= esc($kegiatan['topik'] ?? $kegiatan['penjelasan_layanan'] ?? '-') ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-[#111] border border-white/10 rounded-xl overflow-hidden">
        <div class="p-6 border-b border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="font-bold">Daftar Peserta Absen</h3>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 bg-accent/20 text-accent text-sm rounded-lg font-medium">
                    Total: <?= count($peserta) ?> Peserta
                </span>
                <a href="<?= base_url('laporan-kegiatan/absensi/export-pdf/' . urlencode($kegiatan['kegiatan_type_id'] ?? $kegiatanType) . '/' . $kegiatan['id']) ?>" target="_blank" class="px-4 py-2 bg-red-500/20 text-red-400 font-medium rounded-lg hover:bg-red-500/30 transition text-sm flex items-center gap-2">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-black/40 text-gray-400">
                    <tr>
                        <th class="px-6 py-4 font-medium">No</th>
                        <th class="px-6 py-4 font-medium">Nama Peserta</th>
                        <th class="px-6 py-4 font-medium">Email</th>
                        <th class="px-6 py-4 font-medium">No WhatsApp</th>
                        <th class="px-6 py-4 font-medium">Referral</th>
                        <th class="px-6 py-4 font-medium">Waktu Absen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php if (empty($peserta)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-users-slash text-3xl mb-3 block opacity-50"></i>
                                Belum ada peserta yang melakukan absensi
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($peserta as $p): ?>
                            <tr class="hover:bg-white/5 transition">
                                <td class="px-6 py-4 text-gray-400">
                                    <?= $no++ ?>
                                </td>
                                <td class="px-6 py-4 font-medium">
                                    <?= esc($p['user_name']) ?>
                                </td>
                                <td class="px-6 py-4 text-gray-400">
                                    <?= esc($p['email']) ?>
                                </td>
                                <td class="px-6 py-4 text-gray-400">
                                    <?= esc($p['phone'] ?? '-') ?>
                                </td>
                                <td class="px-6 py-4 text-gray-400">
                                    <?= esc($p['referrer_name'] ?? $p['affiliator_code'] ?? '-') ?>
                                </td>
                                <td class="px-6 py-4 text-gray-400">
                                    <?= date('d M Y, H:i', strtotime($p['created_at'])) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

