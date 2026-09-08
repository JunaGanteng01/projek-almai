<?= $this->extend('ea/layouts/main') ?>

<?= $this->section('content') ?>
<?php 
$pageTitle = $title ?? 'Buat Task Baru';
$activeMenu = 'quick_actions'; 
?>

<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold mb-2"><?= $pageTitle ?></h2>
        <p class="text-gray-400">Buat instruksi atau task strategis baru.</p>
    </div>
    <a href="<?= base_url('ea/quick-actions') ?>" class="bg-white/5 border border-white/10 px-4 py-2 rounded-lg hover:bg-white/10 transition">
        <i class="fas fa-arrow-left mr-2"></i> Kembali
    </a>
</div>

<div class="bg-gradient-to-br from-[#111] to-black border border-white/10 rounded-2xl p-6 shadow-xl max-w-2xl">
    <form action="<?= base_url('ea/quick-actions/store-task') ?>" method="post" class="space-y-6">
        <?= csrf_field() ?>
        
        <div>
            <label class="block text-sm font-medium text-gray-400 mb-2">Judul Task</label>
            <input type="text" name="title" required class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-accent text-white placeholder-gray-600" placeholder="Contoh: Evaluasi Laporan Keuangan Q3">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-400 mb-2">Tenggat Waktu (Due Date)</label>
            <input type="date" name="due_date" required class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-accent text-white" min="<?= date('Y-m-d') ?>">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-400 mb-2">Prioritas</label>
            <div class="flex gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="priority" value="low" class="text-accent focus:ring-accent"> 
                    <span class="text-sm text-gray-300">Rendah</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="priority" value="medium" checked class="text-accent focus:ring-accent"> 
                    <span class="text-sm text-yellow-500">Menengah</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="priority" value="high" class="text-accent focus:ring-accent"> 
                    <span class="text-sm text-red-500">Tinggi</span>
                </label>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-400 mb-2">Catatan Eksekutif / Deskripsi</label>
            <textarea name="description" rows="4" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-accent text-white placeholder-gray-600" placeholder="Detail instruksi spesifik..."></textarea>
        </div>

        <div class="pt-4 border-t border-white/10 flex justify-end">
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white px-6 py-3 rounded-xl font-bold transition flex items-center shadow-lg shadow-emerald-500/20">
                <i class="fas fa-save mr-2"></i> Simpan Task
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
