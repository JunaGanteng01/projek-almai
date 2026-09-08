<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>
<div class="p-0">
    <?php if (session()->getFlashdata('success')): ?>
    <div class="bg-accent/20 border border-accent/30 text-accent px-4 py-3 rounded-lg mb-6">
        <?= session()->getFlashdata('success') ?>
    </div>
    <?php endif; ?>

    <div class="bg-[#0a0a0a] border border-white/10 rounded-2xl overflow-hidden">
        <div class="p-4 border-b border-white/10 flex justify-between items-center">
            <h2 class="font-semibold">Semua Notifikasi</h2>
            <?php 
            /** @var array $notifications */
            $unreadCount = 0;
            foreach ($notifications as $n) {
                if (!$n['is_read']) $unreadCount++;
            }
            if ($unreadCount > 0): 
            ?>
            <a href="<?= base_url('laporan-kegiatan/notifications/read-all') ?>" class="text-sm text-accent hover:underline">Tandai semua dibaca</a>
            <?php endif; ?>
        </div>
        
        <?php if (empty($notifications)): ?>
        <div class="p-8 text-center text-gray-500">
            <i class="fas fa-bell-slash text-4xl mb-4"></i>
            <p>Tidak ada notifikasi</p>
        </div>
        <?php else: ?>
        <div class="divide-y divide-white/5">
            <?php foreach ($notifications as $notif): ?>
            <a href="<?= base_url('laporan-kegiatan/notifications/read/' . $notif['id']) ?>" class="block px-4 py-4 hover:bg-white/5 transition <?= $notif['is_read'] ? 'opacity-60' : '' ?>">
                <div class="flex items-start gap-3 md:gap-4">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 <?= $notif['type'] === 'success' ? 'bg-accent/20 text-accent' : ($notif['type'] === 'error' ? 'bg-red-500/20 text-red-400' : 'bg-blue-500/20 text-blue-400') ?>">
                        <i class="fas <?= $notif['type'] === 'success' ? 'fa-check' : ($notif['type'] === 'error' ? 'fa-times' : 'fa-info') ?> text-sm"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-4">
                            <div class="min-w-0">
                                <p class="font-medium text-sm md:text-base truncate"><?= esc($notif['title']) ?></p>
                                <p class="text-sm text-gray-400 mt-1 line-clamp-2"><?= esc($notif['message']) ?></p>
                            </div>
                            <?php if (!$notif['is_read']): ?>
                            <div class="w-3 h-3 bg-accent rounded-full flex-shrink-0 mt-1"></div>
                            <?php endif; ?>
                        </div>
                        <p class="text-xs text-gray-600 mt-2"><?= date('d M Y, H:i', strtotime($notif['created_at'])) ?></p>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
