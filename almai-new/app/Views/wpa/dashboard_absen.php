<?= $this->extend('wpa/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Header & Back Button -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-lg font-bold text-white mb-0.5">Riwayat Check-in Event</h1>
        <p class="text-gray-400 text-xs">Daftar kegiatan yang telah Anda hadiri</p>
    </div>
    <a href="<?= base_url('wpa/dashboard') ?>" class="flex items-center gap-1.5 bg-[#111] hover:bg-[#1a1a1a] text-gray-300 hover:text-white border border-white/10 rounded-lg px-3 py-1.5 transition text-xs font-medium">
        <i class="fas fa-arrow-left text-[10px]"></i>
        <span>Kembali</span>
    </a>
</div>

<!-- Active Events Section -->
<?php if (!empty($activeEvents)): ?>
<div class="mb-6 bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-xl relative">
    <div class="p-4 border-b border-white/5 flex items-center gap-3">
        <div class="w-10 h-10 bg-orange-500/20 rounded-xl flex items-center justify-center">
            <i class="fas fa-bolt text-orange-500 text-lg animate-pulse"></i>
        </div>
        <div>
            <h2 class="text-white font-semibold text-sm">Kegiatan Berlangsung</h2>
            <p class="text-gray-500 text-[10px]">Kegiatan yang tersedia untuk check-in saat ini</p>
        </div>
    </div>
    
    <div class="p-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach ($activeEvents as $event): ?>
            <div class="bg-black/20 border border-white/5 rounded-xl p-4 hover:border-accent/30 transition group flex flex-col justify-between">
                <div>
                    <div class="flex items-start justify-between mb-2">
                        <span class="px-2 py-1 text-[10px] font-semibold rounded-md bg-accent/10 text-accent border border-accent/20">
                            <?= esc(ucwords(str_replace('_', ' ', $event['kegiatan_type']))) ?>
                        </span>
                        <?php if(!empty($event['tanggal'])): ?>
                            <span class="text-[10px] text-gray-500 flex items-center gap-1">
                                <i class="far fa-calendar-alt"></i> <?= date('d M Y', strtotime($event['tanggal'])) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <h3 class="text-white font-medium text-sm mb-1 group-hover:text-accent transition">
                        <?= esc($event['kegiatan_name']) ?>
                    </h3>
                    
                    <?php if (!empty($event['location_name'])): ?>
                        <p class="text-gray-400 text-[11px] mb-1">
                            <i class="fas fa-map-marker-alt text-gray-500 mr-1"></i> <?= esc($event['location_name']) ?>
                        </p>
                    <?php else: ?>
                        <p class="text-gray-400 text-[11px] line-clamp-1 mb-1">
                            <?= esc($event['topik'] ?? ($event['keterangan'] ?? 'Silakan lakukan check-in event.')) ?>
                        </p>
                    <?php endif; ?>
                    
                    <p class="text-gray-500 text-[10px] mb-3 flex items-center gap-1.5">
                        <i class="fas fa-users text-gray-600"></i>
                        <span><?= esc($event['checkin_count'] ?? 0) ?> / <?= esc($event['target_clients'] ?? 0) ?> Klien Check-in</span>
                    </p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 w-full mt-auto pt-3">
                    <?php if (!empty($event['alreadyCheckedIn'])): ?>
                        <span class="sm:col-span-2 w-full inline-flex justify-center items-center gap-1.5 bg-gray-700 text-gray-400 font-bold text-xs px-3 py-2 rounded-lg cursor-not-allowed shadow-lg">
                            <i class="fas fa-check-circle"></i> Sudah Check-in
                        </span>
                    <?php else: ?>
                        <a href="<?= base_url('absensi/checkin/' . $event['kode_qr']) ?>" class="sm:col-span-2 w-full inline-flex justify-center items-center gap-1.5 bg-accent hover:bg-accent/90 text-black font-bold text-xs px-3 py-2 rounded-lg transition shadow-lg">
                            <i class="fas fa-qrcode"></i> Check-in
                        </a>
                    <?php endif; ?>
                    
                    <button onclick="copyShareLink('<?= base_url('absensi/checkin/' . $event['kode_qr']) ?>?reff=<?= $user['code_referral'] ?? '' ?>', this)" class="sm:col-span-1 w-full inline-flex justify-center items-center gap-1.5 bg-blue-500/10 text-blue-400 hover:bg-blue-500 hover:text-white border border-blue-500/20 font-bold text-xs px-3 py-2 rounded-lg transition shadow-lg">
                        <i class="fas fa-share-alt"></i> Share
                    </button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Main Content (Riwayat) -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-xl relative">
    <!-- Background Decor -->
    <div class="absolute top-0 right-0 w-32 h-32 bg-accent/5 rounded-full blur-2xl pointer-events-none"></div>

    <div class="p-4 border-b border-white/5 flex items-center gap-3">
        <div class="w-10 h-10 bg-accent/20 rounded-xl flex items-center justify-center">
            <i class="fas fa-qrcode text-accent text-lg"></i>
        </div>
        <div>
            <h2 class="text-white font-semibold text-sm">Daftar Check-in Event</h2>
            <p class="text-gray-500 text-[10px]">Menampilkan semua riwayat check-in event Anda</p>
        </div>
    </div>

    <!-- Responsive Table Wrapper -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap min-w-[500px]">
            <thead>
                <tr class="bg-black/20 border-b border-white/5 text-[11px] uppercase tracking-wider text-gray-500">
                    <th class="p-3 font-semibold w-12 text-center">No</th>
                    <th class="p-3 font-semibold">Nama Kegiatan</th>
                    <th class="p-3 font-semibold">Jenis Kegiatan</th>
                    <th class="p-3 font-semibold text-right">Tanggal Check-in</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($absensiList)): ?>
                    <tr>
                        <td colspan="4" class="p-8 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-14 h-14 bg-white/5 rounded-full flex items-center justify-center mb-3">
                                    <i class="fas fa-history text-xl text-gray-600"></i>
                                </div>
                                <p class="text-gray-400 text-sm font-medium">Belum Ada Check-in Event</p>
                                <p class="text-gray-500 text-xs mt-1">Anda belum pernah melakukan check-in kegiatan.</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($absensiList as $index => $absen): ?>
                        <tr class="hover:bg-white/5 transition group">
                            <td class="p-3 text-gray-500 text-xs text-center"><?= $index + 1 ?></td>
                            <td class="p-3">
                                <span class="text-sm font-medium text-white group-hover:text-accent transition">
                                    <?= esc($absen['kegiatan_name']) ?>
                                </span>
                            </td>
                            <td class="p-3">
                                <span class="px-2 py-1 text-[10px] font-semibold rounded-md bg-accent/10 text-accent border border-accent/20">
                                    <?= esc(ucwords(str_replace('_', ' ', $absen['kegiatan_type']))) ?>
                                </span>
                            </td>
                            <td class="p-3 text-right">
                                <div class="flex items-center justify-end gap-1.5 text-xs text-gray-400 group-hover:text-gray-300 transition">
                                    <i class="far fa-calendar-alt text-[10px]"></i>
                                    <?= date('d M Y, H:i', strtotime($absen['created_at'])) ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function copyShareLink(text, btn) {
    const originalHtml = btn.innerHTML;
    const textarea = document.createElement("textarea");
    textarea.value = text;
    document.body.appendChild(textarea);
    textarea.select();
    try {
        document.execCommand("copy");
        btn.innerHTML = '<i class="fas fa-check"></i> Tersalin';
        btn.classList.add('bg-green-500', 'text-white', 'border-green-500');
        btn.classList.remove('bg-blue-500/10', 'text-blue-400', 'border-blue-500/20');
        setTimeout(() => {
            btn.innerHTML = originalHtml;
            btn.classList.remove('bg-green-500', 'text-white', 'border-green-500');
            btn.classList.add('bg-blue-500/10', 'text-blue-400', 'border-blue-500/20');
        }, 2000);
    } catch (err) {
        alert("Gagal menyalin link.");
    }
    document.body.removeChild(textarea);
}
</script>

<?= $this->endSection() ?>
