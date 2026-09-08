<?= $this->extend('user/partials/layout') ?>
<?= $this->section('content') ?>
<div class="flex items-center justify-between mb-6">
    <h2 class="text-lg md:text-xl font-bold">Notifikasi</h2>
    <?php if (!empty($notifications) && array_sum(array_column($notifications, 'is_read')) < count($notifications)): ?>
        <a href="<?= base_url('user/notifications/read-all') ?>" class="text-xs md:text-sm text-accent hover:underline">
            <i class="fas fa-check-double mr-1"></i> Tandai semua dibaca
        </a>
    <?php endif; ?>
</div>

<div class="bg-[#111] border border-white/10 rounded-xl overflow-hidden">
    <?php if (empty($notifications)): ?>
    <div class="p-8 text-center">
        <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-bell-slash text-2xl text-gray-600"></i>
        </div>
        <p class="text-gray-500">Tidak ada notifikasi</p>
    </div>
    <?php else: ?>
    <div class="divide-y divide-white/5">
        <?php foreach ($notifications as $notif): ?>
        <a href="<?= base_url('user/notifications/read/' . $notif['id']) ?>" class="block px-6 py-4 hover:bg-white/5 transition <?= $notif['is_read'] ? 'opacity-60' : '' ?>">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 <?= $notif['type'] === 'success' ? 'bg-accent/20 text-accent' : ($notif['type'] === 'error' ? 'bg-red-500/20 text-red-400' : 'bg-blue-500/20 text-blue-400') ?>">
                    <i class="fas <?= $notif['type'] === 'success' ? 'fa-check-circle' : ($notif['type'] === 'error' ? 'fa-times-circle' : 'fa-info-circle') ?>"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="font-medium"><?= esc($notif['title']) ?></p>
                        <?php if (!$notif['is_read']): ?>
                        <span class="w-2 h-2 bg-accent rounded-full"></span>
                        <?php endif; ?>
                    </div>
                    <p class="text-sm text-gray-400 mt-1"><?= esc($notif['message']) ?></p>
                    <p class="text-xs text-gray-600 mt-2"><?= date('d M Y, H:i', strtotime($notif['created_at'])) ?></p>
                </div>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
