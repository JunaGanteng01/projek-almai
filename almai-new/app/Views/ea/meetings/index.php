<?= $this->extend('ea/layouts/main') ?>

<?= $this->section('content') ?>
<?php 
$pageTitle = $title ?? 'Manajemen Meeting';
$activeMenu = 'meetings'; 
?>

<!-- Header -->
<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold mb-2"><?= $pageTitle ?></h2>
        <p class="text-gray-400">Kelola jadwal meeting tim dan klien.</p>
    </div>
    <a href="<?= base_url('ea/meetings/create') ?>" class="px-4 py-2 rounded-lg transition shadow-lg text-black font-semibold" style="background-color: #33e818;" onmouseover="this.style.backgroundColor='#2ebd15'" onmouseout="this.style.backgroundColor='#33e818'">
        <i class="fas fa-video mr-2"></i> Jadwalkan Meeting
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="bg-emerald-500/10 border border-emerald-500 text-emerald-500 p-4 rounded-xl mb-6 flex items-center gap-3">
        <i class="fas fa-check-circle"></i>
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="bg-red-500/10 border border-red-500 text-red-500 p-4 rounded-xl mb-6 flex items-center gap-3">
        <i class="fas fa-exclamation-circle"></i>
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<!-- Today's Metrics -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-8 h-8 rounded bg-blue-500/20 text-blue-400 flex items-center justify-center">
                <i class="fas fa-calendar-day"></i>
            </div>
            <h3 class="text-gray-400 font-medium">Meeting Hari Ini</h3>
        </div>
        <p class="text-3xl font-bold text-white"><?= $today_total ?></p>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-8 h-8 rounded bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
                <i class="fas fa-check-double"></i>
            </div>
            <h3 class="text-gray-400 font-medium">Selesai</h3>
        </div>
        <p class="text-3xl font-bold text-white"><?= $today_completed ?></p>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-8 h-8 rounded bg-yellow-500/20 text-yellow-400 flex items-center justify-center">
                <i class="fas fa-hourglass-half"></i>
            </div>
            <h3 class="text-gray-400 font-medium">Belum Dilaksanakan</h3>
        </div>
        <p class="text-3xl font-bold text-white"><?= $today_pending ?></p>
    </div>
</div>

<!-- Data Table -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-xl">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-black/50 text-gray-400">
                <tr>
                    <th class="px-6 py-4 font-medium">No</th>
                    <th class="px-6 py-4 font-medium">Agenda</th>
                    <th class="px-6 py-4 font-medium">Waktu Mulai</th>
                    <th class="px-6 py-4 font-medium">Lokasi / Link</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if(empty($meetings)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-video-slash text-4xl mb-3"></i>
                        <p>Belum ada jadwal meeting.</p>
                    </td>
                </tr>
                <?php else: ?>
                    <?php $no = 1; foreach($meetings as $m): ?>
                    <tr class="hover:bg-white/5 transition">
                        <td class="px-6 py-4 text-gray-500"><?= $no++ ?></td>
                        <td class="px-6 py-4 text-white font-medium"><?= esc($m['title']) ?></td>
                        <td class="px-6 py-4 text-gray-400"><?= date('d M Y, H:i', strtotime($m['start_time'])) ?></td>
                        <td class="px-6 py-4">
                            <?php if (filter_var($m['location'], FILTER_VALIDATE_URL)): ?>
                                <a href="<?= esc($m['location']) ?>" target="_blank" class="text-blue-400 hover:underline"><i class="fas fa-external-link-alt mr-1"></i> Buka Link</a>
                            <?php else: ?>
                                <span class="text-gray-400"><?= esc($m['location']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4">
                            <?php if(strtolower($m['status']) == 'completed'): ?>
                                <span class="bg-emerald-500/20 text-emerald-400 px-2 py-1 rounded text-xs font-bold">Completed</span>
                            <?php elseif(strtolower($m['status']) == 'cancelled'): ?>
                                <span class="bg-red-500/20 text-red-400 px-2 py-1 rounded text-xs font-bold">Cancelled</span>
                            <?php else: ?>
                                <span class="bg-blue-500/20 text-blue-400 px-2 py-1 rounded text-xs font-bold"><?= esc($m['status']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 flex items-center justify-center gap-2">
                            <a href="<?= base_url('ea/meetings/edit/'.$m['id']) ?>" class="bg-blue-500/10 hover:bg-blue-500/20 text-blue-400 px-3 py-1.5 rounded transition text-xs">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?= base_url('ceo/meetings/delete/'.$m['id']) ?>" method="post" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus jadwal ini?');">
                                <?= csrf_field() ?><button type="submit" class="bg-red-500/10 hover:bg-red-500/20 text-red-400 px-3 py-1.5 rounded transition text-xs"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
