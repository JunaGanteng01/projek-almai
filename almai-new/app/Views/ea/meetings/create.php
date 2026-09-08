<?= $this->extend('ea/layouts/main') ?>

<?= $this->section('content') ?>
<?php 
$pageTitle = $title ?? 'Buat Meeting Baru';
$activeMenu = 'meetings'; 
?>

<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold mb-2"><?= $pageTitle ?></h2>
        <p class="text-gray-400">Jadwalkan meeting baru.</p>
    </div>
    <a href="<?= base_url('ea/meetings') ?>" class="bg-white/5 border border-white/10 px-4 py-2 rounded-lg hover:bg-white/10 transition">
        <i class="fas fa-arrow-left mr-2"></i> Kembali
    </a>
</div>

<div class="bg-gradient-to-br from-[#111] to-black border border-white/10 rounded-2xl p-6 shadow-xl w-full">
    <form action="<?= base_url('ea/meetings/store') ?>" method="post" class="space-y-6">
        <?= csrf_field() ?>
        
        <div>
            <label class="block text-sm font-medium text-gray-400 mb-2">Agenda / Judul Meeting</label>
            <input type="text" name="title" required class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 text-white" placeholder="Contoh: Meeting Q3 Review">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Tanggal</label>
                <input type="date" name="date" required class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Waktu Mulai (WIB)</label>
                <input type="time" name="time" required class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 text-white">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-400 mb-2">Lokasi Fisik (Hotel, Kantor, dll) / Link Meeting (Zoom, GMeet)</label>
            <input type="text" name="link" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 text-white" placeholder="Contoh: Hotel Mulia / https://zoom.us/j/...">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-400 mb-2">Status</label>
            <select name="status" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-blue-500 text-white">
                <option value="Upcoming">Upcoming</option>
                <option value="Completed">Completed</option>
                <option value="Cancelled">Cancelled</option>
            </select>
        </div>

        <div class="pt-4 border-t border-white/10 flex justify-end">
            <button type="submit" class="px-6 py-3 rounded-xl font-bold transition flex items-center shadow-lg" style="background-color: #33e818; color: black;" onmouseover="this.style.backgroundColor='#2ebd15'" onmouseout="this.style.backgroundColor='#33e818'">
                <i class="fas fa-plus mr-2"></i> Buat Meeting
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
