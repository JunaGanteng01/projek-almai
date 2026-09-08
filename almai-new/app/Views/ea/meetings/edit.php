<?= $this->extend('ea/layouts/main') ?>

<?= $this->section('content') ?>
<?php 
$pageTitle = $title ?? 'Edit Meeting';
$activeMenu = 'meetings'; 
?>

<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold mb-2"><?= $pageTitle ?></h2>
        <p class="text-gray-400">Ubah jadwal meeting.</p>
    </div>
    <a href="<?= base_url('ea/meetings') ?>" class="bg-white/5 border border-white/10 px-4 py-2 rounded-lg hover:bg-white/10 transition">
        <i class="fas fa-arrow-left mr-2"></i> Kembali
    </a>
</div>

<div class="bg-gradient-to-br from-[#111] to-black border border-white/10 rounded-2xl p-6 shadow-xl max-w-2xl">
    <form action="<?= base_url('ea/meetings/update/'.$meeting['id']) ?>" method="post" class="space-y-6">
        <?= csrf_field() ?>
        
        <div>
            <label class="block text-sm font-medium text-gray-400 mb-2">Agenda / Judul Meeting</label>
            <input type="text" name="title" value="<?= esc($meeting['title']) ?>" required class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-accent text-white">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Tanggal</label>
                <input type="date" name="date" value="<?= date('Y-m-d', strtotime($meeting['start_time'])) ?>" required class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-accent text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Waktu Mulai (WIB)</label>
                <input type="time" name="time" value="<?= date('H:i', strtotime($meeting['start_time'])) ?>" required class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-accent text-white">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-400 mb-2">Lokasi / Link Meeting</label>
            <input type="text" name="link" value="<?= esc($meeting['meet_url'] ?: $meeting['location']) ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-accent text-white">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-400 mb-2">Status</label>
            <select name="status" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-accent text-white">
                <option value="Upcoming" <?= $meeting['status'] == 'Upcoming' ? 'selected' : '' ?>>Upcoming</option>
                <option value="Completed" <?= $meeting['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                <option value="Cancelled" <?= $meeting['status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </div>

        <div class="pt-4 border-t border-white/10 flex justify-end">
            <button type="submit" class="px-6 py-3 rounded-xl font-bold transition flex items-center shadow-lg" style="background-color: #33e818; color: black;" onmouseover="this.style.backgroundColor='#2ebd15'" onmouseout="this.style.backgroundColor='#33e818'">
                <i class="fas fa-save mr-2"></i> Update Meeting
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
