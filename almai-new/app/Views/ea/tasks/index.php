<?= $this->extend('ea/layouts/main') ?>

<?= $this->section('content') ?>
<?php 
$pageTitle = $title ?? 'Manajemen Task';
$activeMenu = 'tasks'; 
?>

<!-- Header -->
<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold mb-2"><?= $pageTitle ?></h2>
        <p class="text-gray-400">Kelola semua task Anda (Tambah, Edit, Hapus).</p>
    </div>
    <a href="<?= base_url('ea/tasks/create') ?>" class="text-black font-bold px-4 py-2 rounded-lg transition shadow-lg" style="background-color: #33E818;">
        <i class="fas fa-plus mr-2"></i> Tambah Task
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
                <i class="fas fa-tasks"></i>
            </div>
            <h3 class="text-gray-400 font-medium">Task Hari Ini</h3>
        </div>
        <p class="text-3xl font-bold text-white"><?= $today_total ?></p>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-8 h-8 rounded bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
                <i class="fas fa-check"></i>
            </div>
            <h3 class="text-gray-400 font-medium">Selesai Hari Ini</h3>
        </div>
        <p class="text-3xl font-bold text-white"><?= $today_completed ?></p>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-8 h-8 rounded bg-yellow-500/20 text-yellow-400 flex items-center justify-center">
                <i class="fas fa-clock"></i>
            </div>
            <h3 class="text-gray-400 font-medium">Tertunda Hari Ini</h3>
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
                    <th class="px-6 py-4 font-medium">Judul Task</th>
                    <th class="px-6 py-4 font-medium">Deadline</th>
                    <th class="px-6 py-4 font-medium">Prioritas</th>
                    <th class="px-6 py-4 font-medium">Status</th>
                    <th class="px-6 py-4 font-medium text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if(empty($tasks)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-3"></i>
                        <p>Belum ada task tersedia.</p>
                    </td>
                </tr>
                <?php else: ?>
                    <?php $no = 1; foreach($tasks as $t): ?>
                    <tr class="hover:bg-white/5 transition">
                        <td class="px-6 py-4 text-gray-500"><?= $no++ ?></td>
                        <td class="px-6 py-4">
                            <span class="text-white font-medium block"><?= esc($t['title']) ?></span>
                            <?php if(!empty($t['assigned_to'])): ?>
                                <span class="text-xs text-gray-400 mt-1 block"><i class="fas fa-user text-[10px] mr-1"></i> <?= esc($t['assigned_to']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-gray-400"><?= date('d M Y, H:i', strtotime($t['deadline'])) ?></td>
                        <td class="px-6 py-4">
                            <form action="<?= base_url('ea/tasks/quick-update/' . $t['id']) ?>" method="POST" class="inline-block">
                                <?= csrf_field() ?>
                                <input type="hidden" name="field" value="priority">
                                <?php 
                                    $prioColor = '';
                                    if(strtolower(trim($t['priority'])) == 'high') $prioColor = 'color: #ef4444;'; // red-500
                                    else if(strtolower(trim($t['priority'])) == 'medium') $prioColor = 'color: #eab308;'; // yellow-500
                                    else $prioColor = 'color: #33E818;'; // user custom green
                                ?>
                                <select name="value" onchange="this.form.submit()" class="bg-black/40 border border-white/10 rounded px-2 py-1 text-xs font-bold focus:outline-none cursor-pointer" style="<?= $prioColor ?>">
                                    <option class="bg-[#222] text-white" value="High" <?= strtolower(trim($t['priority'])) == 'high' ? 'selected' : '' ?>>High</option>
                                    <option class="bg-[#222] text-white" value="Medium" <?= strtolower(trim($t['priority'])) == 'medium' ? 'selected' : '' ?>>Medium</option>
                                    <option class="bg-[#222] text-white" value="Low" <?= strtolower(trim($t['priority'])) == 'low' ? 'selected' : '' ?>>Low</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4">
                            <form action="<?= base_url('ea/tasks/quick-update/' . $t['id']) ?>" method="POST" class="inline-block">
                                <?= csrf_field() ?>
                                <input type="hidden" name="field" value="status">
                                <?php 
                                    $statColor = '';
                                    if(strtolower(trim($t['status'])) == 'completed') $statColor = 'color: #33E818;'; // user custom green
                                    else if(strtolower(trim($t['status'])) == 'pending') $statColor = 'color: #eab308;'; // yellow-500
                                    else $statColor = 'color: #3b82f6;'; // blue-500
                                ?>
                                <select name="value" onchange="this.form.submit()" class="bg-black/40 border border-white/10 rounded px-2 py-1 text-xs font-bold focus:outline-none cursor-pointer" style="<?= $statColor ?>">
                                    <option class="bg-[#222] text-white" value="Pending" <?= strtolower(trim($t['status'])) == 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option class="bg-[#222] text-white" value="In Progress" <?= strtolower(trim($t['status'])) == 'in progress' ? 'selected' : '' ?>>In Progress</option>
                                    <option class="bg-[#222] text-white" value="Completed" <?= strtolower(trim($t['status'])) == 'completed' ? 'selected' : '' ?>>Completed</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-6 py-4 flex items-center justify-center gap-2">
                            <a href="<?= base_url('ea/tasks/show/' . $t['id']) ?>" class="text-blue-500 hover:text-blue-400 bg-blue-500/10 p-2 rounded transition" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <form action="<?= base_url('ceo/tasks/delete/'.$t['id']) ?>" method="post" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus task ini?');">
                                <?= csrf_field() ?><button type="submit" class="bg-red-500/10 hover:bg-red-500/20 text-red-400 p-2 rounded transition" title="Hapus"><i class="fas fa-trash"></i></button>
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
