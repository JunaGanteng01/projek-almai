<?= $this->extend('ea/layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="<?= base_url('ea/tasks') ?>" class="text-gray-400 hover:text-white transition flex items-center gap-2 mb-2">
            <i class="fas fa-arrow-left"></i> Kembali ke List
        </a>
        <h2 class="text-2xl font-bold tracking-tight">Detail Task: <?= esc($task['title']) ?></h2>
    </div>
    <span class="px-3 py-1 rounded text-xs font-bold <?= $task['priority'] == 'High' ? 'bg-red-500/20 text-red-500' : ($task['priority'] == 'Medium' ? 'bg-yellow-500/20 text-yellow-500' : 'bg-blue-500/20 text-blue-500') ?>">
        <?= esc($task['priority']) ?>
    </span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Kolom Kiri: Deskripsi & Komentar -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-[#111]/80 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-xl">
            <h3 class="text-lg font-bold mb-4 border-b border-white/10 pb-2">Deskripsi Task</h3>
            <div class="text-gray-300 text-sm whitespace-pre-wrap leading-relaxed">
                <?= !empty($task['description']) ? esc($task['description']) : '<em class="text-gray-500">Tidak ada deskripsi.</em>' ?>
            </div>
            
            <?php if(!empty($task['attachment'])): ?>
            <div class="mt-6 pt-4 border-t border-white/10">
                <p class="text-sm text-gray-400 mb-2">Lampiran (Attachment)</p>
                <div class="p-3 bg-white/5 border border-white/10 rounded-xl flex items-center justify-between inline-block">
                    <span class="text-sm"><i class="fas fa-file-alt text-accent mr-2"></i> <?= basename($task['attachment']) ?></span>
                    <a href="<?= base_url($task['attachment']) ?>" target="_blank" class="text-xs bg-blue-500/20 text-blue-500 hover:bg-blue-500/30 px-3 py-1 rounded transition ml-4">Lihat / Download</a>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Kolom Komentar -->
        <div class="bg-[#111]/80 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-xl">
            <h3 class="text-lg font-bold mb-4 border-b border-white/10 pb-2"><i class="fas fa-comments text-accent mr-2"></i> Diskusi & Komentar</h3>
            
            <div class="space-y-4 mb-6 max-h-[300px] overflow-y-auto pr-2 custom-scrollbar">
                <?php if(empty($comments)): ?>
                    <p class="text-gray-500 text-sm italic text-center py-4">Belum ada komentar pada task ini.</p>
                <?php else: ?>
                    <?php foreach($comments as $c): ?>
                        <div class="flex gap-3 bg-black/40 p-3 rounded-xl border border-white/5">
                            <div class="w-8 h-8 rounded-full bg-accent/20 flex items-center justify-center shrink-0">
                                <i class="fas fa-user text-accent text-xs"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-bold text-sm text-white"><?= esc($c['user_name']) ?></span>
                                    <span class="text-[10px] text-gray-500"><?= date('d M Y, H:i', strtotime($c['created_at'])) ?></span>
                                </div>
                                <p class="text-gray-300 text-sm"><?= nl2br(esc($c['comment'])) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <form action="<?= base_url('ea/tasks/add-comment/' . $task['id']) ?>" method="POST">
                <?= csrf_field() ?>
                <textarea name="comment" rows="3" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 text-white focus:outline-none focus:border-accent mb-3 text-sm placeholder-gray-500" placeholder="Tulis komentar..."></textarea>
                <div class="flex justify-end">
                    <button type="submit" class="text-black px-4 py-2 rounded-xl text-sm font-bold transition flex items-center gap-2 shadow-lg" style="background-color: #33E818;">
                        <i class="fas fa-paper-plane"></i> Kirim
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Kolom Kanan: Activity Log & Meta -->
    <div class="space-y-6">
        <div class="bg-[#111]/80 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-xl">
            <h3 class="text-lg font-bold mb-4 border-b border-white/10 pb-2"><i class="fas fa-info-circle text-accent mr-2"></i> Detail Task</h3>
            <div class="space-y-4 text-sm">
                <div>
                    <p class="text-gray-400 text-xs mb-1">Status</p>
                    <span class="px-2 py-1 rounded text-xs font-bold <?= strtolower(trim($task['status'])) == 'completed' ? 'bg-emerald-500/20 text-emerald-400' : (strtolower(trim($task['status'])) == 'pending' ? 'bg-yellow-500/20 text-yellow-500' : 'bg-blue-500/20 text-blue-500') ?>">
                        <?= esc($task['status']) ?>
                    </span>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-1">Assigned To</p>
                    <p class="font-bold text-white"><?= !empty($task['assigned_to']) ? esc($task['assigned_to']) : '-' ?></p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-1">Deadline</p>
                    <p class="font-bold text-white"><?= date('d M Y, H:i', strtotime($task['deadline'])) ?></p>
                </div>
                <div>
                    <p class="text-gray-400 text-xs mb-1">Dibuat Pada</p>
                    <p class="font-bold text-white"><?= date('d M Y, H:i', strtotime($task['created_at'])) ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-[#111]/80 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-xl">
            <h3 class="text-lg font-bold mb-4 border-b border-white/10 pb-2"><i class="fas fa-history text-accent mr-2"></i> Activity Log</h3>
            <div class="space-y-4">
                <?php if(empty($activityLog)): ?>
                    <p class="text-gray-500 text-sm italic py-2">Belum ada aktivitas tercatat.</p>
                <?php else: ?>
                    <?php foreach(array_reverse($activityLog) as $log): ?>
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5"><?= date('d M, H:i', strtotime($log['time'])) ?></p>
                            <p class="text-sm text-gray-200"><?= esc($log['action']) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05); 
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2); 
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3); 
}
</style>
<?= $this->endSection() ?>
