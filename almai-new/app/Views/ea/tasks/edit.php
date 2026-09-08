<?= $this->extend('ea/layouts/main') ?>

<?= $this->section('content') ?>
<?php 
$pageTitle = $title ?? 'Edit Task';
$activeMenu = 'tasks'; 
?>

<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold mb-2"><?= $pageTitle ?></h2>
        <p class="text-gray-400">Ubah data task yang sudah ada.</p>
    </div>
    <a href="<?= base_url('ea/tasks') ?>" class="bg-white/5 border border-white/10 px-4 py-2 rounded-lg hover:bg-white/10 transition">
        <i class="fas fa-arrow-left mr-2"></i> Kembali
    </a>
</div>

<div class="bg-gradient-to-br from-[#111] to-black border border-white/10 rounded-2xl p-6 shadow-xl max-w-2xl">
    <form action="<?= base_url('ea/tasks/update/'.$task['id']) ?>" method="post" class="space-y-6">
        <?= csrf_field() ?>
        
        <div>
            <label class="block text-sm font-medium text-gray-400 mb-2">Judul Task</label>
            <input type="text" name="title" value="<?= esc($task['title']) ?>" required class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-accent text-white">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-400 mb-2">Tenggat Waktu (Due Date)</label>
            <input type="date" name="due_date" value="<?= date('Y-m-d', strtotime($task['deadline'])) ?>" required class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-accent text-white">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Prioritas</label>
                <select name="priority" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-accent text-white">
                    <option value="Low" <?= strtolower($task['priority']) == 'low' ? 'selected' : '' ?>>Low</option>
                    <option value="Medium" <?= strtolower($task['priority']) == 'medium' ? 'selected' : '' ?>>Medium</option>
                    <option value="High" <?= strtolower($task['priority']) == 'high' ? 'selected' : '' ?>>High</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Status</label>
                <select name="status" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 focus:outline-none focus:border-accent text-white">
                    <option value="Pending" <?= $task['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="Completed" <?= $task['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                </select>
            </div>
        </div>

        <div class="pt-4 border-t border-white/10 flex justify-end">
            <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-xl font-bold transition flex items-center shadow-lg shadow-blue-500/20">
                <i class="fas fa-save mr-2"></i> Update Task
            </button>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
