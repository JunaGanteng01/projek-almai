<?= $this->extend('wpa/layouts/main') ?>

<?= $this->section('content') ?>
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-xl md:text-2xl font-bold">Semua Notifikasi</h2>
        <p class="text-sm text-gray-500">Pantau semua aktivitas dan update terbaru Anda</p>
    </div>
    <?php if (!empty($notifications)): ?>
        <a href="<?= base_url('wpa/dashboard/notifications/read-all') ?>" class="text-xs md:text-sm bg-accent/20 text-accent px-4 py-2 rounded-lg border border-accent/30 hover:bg-accent hover:text-black transition font-bold">
            <i class="fas fa-check-double mr-1"></i> Tandai Semua Dibaca
        </a>
    <?php endif; ?>
</div>

<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
    <?php if (empty($notifications)): ?>
        <div class="py-20 text-center">
            <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-bell-slash text-4xl text-gray-700"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-400 mb-2">Belum ada notifikasi</h3>
            <p class="text-gray-500 text-sm max-w-xs mx-auto">Kami akan memberitahu Anda lewat sini jika ada update atau aktivitas baru.</p>
        </div>
    <?php else: ?>
        <div class="divide-y divide-white/5">
            <?php foreach ($notifications as $notif): ?>
                <a href="<?= base_url('wpa/dashboard/notifications/read/' . $notif['id']) ?>" class="block px-6 py-5 hover:bg-white/5 transition group <?= $notif['is_read'] ? 'opacity-60' : 'bg-accent/5' ?>">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 <?= $notif['type'] === 'success' ? 'bg-accent/20 text-accent' : ($notif['type'] === 'error' ? 'bg-red-500/20 text-red-400' : 'bg-blue-500/20 text-blue-400') ?>">
                            <i class="fas <?= $notif['type'] === 'success' ? 'fa-check-circle' : ($notif['type'] === 'error' ? 'fa-times-circle' : 'fa-info-circle') ?> text-lg"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <p class="font-bold text-base <?= !$notif['is_read'] ? 'text-accent' : 'text-gray-200' ?> group-hover:text-accent transition">
                                    <?= esc($notif['title']) ?>
                                </p>
                                <span class="text-[10px] text-gray-500 whitespace-nowrap bg-black/50 px-2 py-1 rounded">
                                    <?= date('d M Y, H:i', strtotime($notif['created_at'])) ?>
                                </span>
                            </div>
                            <p class="text-sm text-gray-400 line-clamp-2 leading-relaxed"><?= esc($notif['message']) ?></p>

                            <?php if (!$notif['is_read']): ?>
                                <div class="mt-3 flex items-center gap-2">
                                    <span class="w-2 h-2 bg-accent rounded-full pulse"></span>
                                    <span class="text-[10px] text-accent font-bold uppercase tracking-widest">Baru</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 1;
        }

        50% {
            transform: scale(1.5);
            opacity: 0.5;
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .pulse {
        animation: pulse 2s infinite;
    }
</style>
<?= $this->endSection() ?>