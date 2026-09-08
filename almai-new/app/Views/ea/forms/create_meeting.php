<?= $this->extend('ea/layouts/main') ?>

<?= $this->section('content') ?>
<?php 
$pageTitle = $title ?? 'Jadwalkan Meeting';
$activeMenu = 'quick_actions'; 
?>

<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold mb-2"><?= $pageTitle ?></h2>
        <p class="text-gray-400">Atur jadwal meeting baru dengan tim atau klien.</p>
    </div>
    <a href="<?= base_url('ea/quick-actions') ?>" class="bg-white/5 border border-white/10 px-4 py-2 rounded-lg hover:bg-white/10 transition">
        <i class="fas fa-arrow-left mr-2"></i> Kembali
    </a>
</div>

<div class="bg-gradient-to-br from-[#111] to-black border border-white/10 rounded-2xl p-6 shadow-xl max-w-2xl">
    <form action="<?= base_url('ea/quick-actions/store-meeting') ?>" method="post" class="space-y-6">
        <?= csrf_field() ?>
        
        <div>
            <label class="block text-sm font-medium text-gray-400 mb-2">Agenda / Judul Meeting</label>
            <input type="text" name="title" required class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-accent text-white placeholder-gray-600" placeholder="Contoh: Pembahasan Target Q4">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Tanggal</label>
                <input type="date" name="date" required class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-accent text-white" min="<?= date('Y-m-d') ?>">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Waktu (WIB)</label>
                <input type="time" name="time" required class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-accent text-white">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-400 mb-2">Partisipan</label>
            <input type="text" name="participants" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-accent text-white placeholder-gray-600" placeholder="Contoh: Budi, Andi, Siska (Pisahkan dengan koma)">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-400 mb-2">Link Meeting (Zoom/Meet) - Opsional</label>
            <input type="url" name="link" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-accent text-white placeholder-gray-600" placeholder="https://zoom.us/j/123456789">
        </div>

        <div class="pt-4 border-t border-white/10 flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-xl font-bold transition flex items-center shadow-lg shadow-blue-500/20">
                <i class="fas fa-video mr-2"></i> Jadwalkan Meeting
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
