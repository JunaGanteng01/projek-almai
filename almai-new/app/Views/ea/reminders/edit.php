<?= $this->extend('ea/layouts/main') ?>

<?= $this->section('content') ?>
<?php 
$pageTitle = $title ?? 'Edit Reminder';
$activeMenu = 'reminders'; 
?>

<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold mb-2"><?= $pageTitle ?></h2>
        <p class="text-gray-400">Ubah data pengingat.</p>
    </div>
    <a href="<?= base_url('ea/reminders') ?>" class="bg-white/5 border border-white/10 px-4 py-2 rounded-lg hover:bg-white/10 transition">
        <i class="fas fa-arrow-left mr-2"></i> Kembali
    </a>
</div>

<div class="bg-gradient-to-br from-[#111] to-black border border-white/10 rounded-2xl p-6 shadow-xl max-w-2xl">
    <form action="<?= base_url('ea/reminders/update/'.$reminder['id']) ?>" method="post" class="space-y-6">
        <?= csrf_field() ?>
        
        <div>
            <label class="block text-sm font-medium text-gray-400 mb-2">Judul Reminder</label>
            <input type="text" name="title" value="<?= esc($reminder['title']) ?>" required class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-accent text-white">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Tanggal</label>
                <input type="date" name="date" value="<?= date('Y-m-d', strtotime($reminder['reminder_time'])) ?>" required class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-accent text-white">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Waktu (WIB)</label>
                <input type="time" name="time" value="<?= date('H:i', strtotime($reminder['reminder_time'])) ?>" required class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-accent text-white">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-400 mb-2">Tingkat Penting</label>
            <select name="priority" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-accent text-white">
                <option value="normal" <?= strtolower($reminder['type']) == 'normal' ? 'selected' : '' ?>>Normal</option>
                <option value="penting" <?= strtolower($reminder['type']) == 'penting' ? 'selected' : '' ?>>Penting</option>
                <option value="kritis" <?= strtolower($reminder['type']) == 'kritis' ? 'selected' : '' ?>>Sangat Penting / Kritis</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-400 mb-2">Status</label>
            <select name="status" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-accent text-white">
                <option value="Active" <?= $reminder['status'] == 'Active' ? 'selected' : '' ?>>Active</option>
                <option value="Completed" <?= $reminder['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                <option value="Archived" <?= $reminder['status'] == 'Archived' ? 'selected' : '' ?>>Archived</option>
            </select>
        </div>

        <div class="pt-4 border-t border-white/10 flex justify-end">
            <button type="submit" class="bg-yellow-600 hover:bg-yellow-500 text-white px-6 py-3 rounded-xl font-bold transition flex items-center shadow-lg shadow-yellow-500/20">
                <i class="fas fa-save mr-2"></i> Update Reminder
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
