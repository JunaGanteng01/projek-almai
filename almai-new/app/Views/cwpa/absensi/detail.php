<?= $this->extend('cwpa/layouts/main') ?>

<?= $this->section('content') ?>

<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="<?= base_url('cwpa/dashboard/absensi') ?>" class="w-10 h-10 bg-white/5 hover:bg-white/10 rounded-xl flex items-center justify-center transition">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold">Detail Absensi</h1>
            <p class="text-gray-400 text-sm"><?= esc($kegiatan['nama']) ?> (<?= esc($kegiatanType) ?>)</p>
        </div>
    </div>

    <div class="bg-[#111] border border-white/10 rounded-xl overflow-hidden">
        <div class="p-6 border-b border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="font-bold">Daftar Peserta Absen</h3>
            <div class="flex items-center gap-3">
                <span class="px-3 py-1 bg-accent/20 text-accent text-sm rounded-lg font-medium">
                    Total: <?= count($peserta) ?> Peserta
                </span>
                <a href="<?= base_url('cwpa/dashboard/absensi/export-csv/' . urlencode($kegiatan['kegiatan_type_id'] ?? $kegiatanType) . '/' . $kegiatan['id']) ?>" class="px-4 py-2 bg-green-500/20 text-green-400 font-medium rounded-lg hover:bg-green-500/30 transition text-sm flex items-center gap-2">
                    <i class="fas fa-file-csv"></i> Export CSV
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
