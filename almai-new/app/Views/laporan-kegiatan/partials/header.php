<?php
$notifModel = new \App\Models\NotificationModel();
$currentUserId = session()->get('userId');
$notifications = $notifModel->getByUserId($currentUserId, 5);
$unreadCount = $notifModel->getUnreadCount($currentUserId);
?>
<!-- Header -->
<header class="bg-[#0a0a0a] border-b border-white/10 px-4 md:px-8 py-4 flex justify-between items-center fixed top-0 left-0 right-0 z-40" style="left: 0;">
    <style>
        @media (min-width: 768px) {
            header { left: 256px !important; }
        }
    </style>
    <div class="flex items-center gap-3">
        <button onclick="toggleSidebar()" class="md:hidden p-2 hover:bg-white/10 rounded-lg">
            <i class="fas fa-bars text-xl"></i>
        </button>
        <div>
            <h1 class="text-lg md:text-xl font-bold truncate max-w-[150px] sm:max-w-none"><?= $pageTitle ?? $title ?? 'Dashboard' ?></h1>
            <p class="text-xs md:text-sm text-gray-500 hidden md:block"><?= $pageSubtitle ?? 'Selamat datang, ' . esc(str_replace('Partnership ', '', session()->get('userName'))) ?></p>
        </div>
    </div>
    <div class="flex items-center gap-2 md:gap-4">
        <!-- Notification Bell -->
        <div class="relative">
            <button onclick="toggleNotifMenu()" class="relative p-2 hover:bg-white/10 rounded-lg transition">
                <i class="fas fa-bell"></i>
                <?php if ($unreadCount > 0): ?>
                <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center"><?= $unreadCount > 9 ? '9+' : $unreadCount ?></span>
                <?php endif; ?>
            </button>
            <div id="notifMenu" class="hidden absolute right-0 top-full mt-2 w-[calc(100vw-2rem)] sm:w-80 bg-[#111] border border-white/10 rounded-xl shadow-xl overflow-hidden z-50">
                <div class="p-3 border-b border-white/10 flex justify-between items-center">
                    <span class="font-medium text-sm">Notifikasi</span>
                    <?php if ($unreadCount > 0): ?>
                    <a href="<?= base_url('laporan-kegiatan/notifications/read-all') ?>" class="text-xs text-accent hover:underline">Tandai semua dibaca</a>
                    <?php endif; ?>
                </div>
                <div class="max-h-80 overflow-y-auto">
                    <?php if (empty($notifications)): ?>
                    <div class="p-4 text-center text-gray-500 text-sm">Tidak ada notifikasi</div>
                    <?php else: ?>
                    <?php foreach ($notifications as $notif): ?>
                    <a href="<?= base_url('laporan-kegiatan/notifications/read/' . $notif['id']) ?>" class="block px-4 py-3 hover:bg-white/5 transition border-b border-white/5 <?= $notif['is_read'] ? 'opacity-60' : '' ?>">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center <?= $notif['type'] === 'success' ? 'bg-accent/20 text-accent' : ($notif['type'] === 'error' ? 'bg-red-500/20 text-red-400' : 'bg-blue-500/20 text-blue-400') ?>">
                                <i class="fas <?= $notif['type'] === 'success' ? 'fa-check' : ($notif['type'] === 'error' ? 'fa-times' : 'fa-info') ?> text-xs"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium"><?= esc($notif['title']) ?></p>
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2"><?= esc($notif['message']) ?></p>
                                <p class="text-xs text-gray-600 mt-1"><?= date('d M H:i', strtotime($notif['created_at'])) ?></p>
                            </div>
                            <?php if (!$notif['is_read']): ?>
                            <div class="w-2 h-2 bg-accent rounded-full"></div>
                            <?php endif; ?>
                        </div>
                    </a>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <a href="<?= base_url('laporan-kegiatan/notifications') ?>" class="block p-3 text-center text-sm text-accent hover:bg-white/5 border-t border-white/10">Lihat Semua Notifikasi</a>
            </div>
        </div>
        
        <div class="relative">
            <button onclick="toggleProfileMenu()" class="flex items-center gap-2 p-1 hover:bg-white/5 rounded-lg transition">
                <div class="w-8 h-8 md:w-10 md:h-10 bg-accent/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-accent text-sm"></i>
                </div>
                <span class="font-medium text-sm hidden md:block"><?= esc(str_replace('Partnership ', '', session()->get('userName'))) ?></span>
                <i class="fas fa-chevron-down text-xs text-gray-400 hidden md:block"></i>
            </button>
            <!-- Profile Dropdown -->
            <div id="profileMenu" class="hidden absolute right-0 top-full mt-2 w-48 bg-[#111] border border-white/10 rounded-xl shadow-xl overflow-hidden z-50">
                <div class="p-3 border-b border-white/10 block md:hidden">
                    <p class="font-medium text-sm"><?= esc(str_replace('Partnership ', '', session()->get('userName'))) ?></p>
                    <p class="text-xs text-gray-500"><?= esc(session()->get('userEmail')) ?></p>
                </div>
                <a href="<?= base_url('laporan-kegiatan/logout') ?>" class="w-full flex items-center gap-3 px-4 py-3 hover:bg-red-500/10 transition text-sm text-red-400">
                    <i class="fas fa-sign-out-alt w-4"></i> Logout
                </a>
            </div>
        </div>
    </div>
</header>

<script>
function toggleNotifMenu() {
    document.getElementById('notifMenu').classList.toggle('hidden');
    document.getElementById('profileMenu').classList.add('hidden');
}

function toggleProfileMenu() {
    document.getElementById('profileMenu').classList.toggle('hidden');
    document.getElementById('notifMenu').classList.add('hidden');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
    const profileMenu = document.getElementById('profileMenu');
    const notifMenu = document.getElementById('notifMenu');
    if (!e.target.closest('button[onclick="toggleProfileMenu()"]') && profileMenu && !profileMenu.contains(e.target)) {
        profileMenu.classList.add('hidden');
    }
    if (!e.target.closest('button[onclick="toggleNotifMenu()"]') && notifMenu && !notifMenu.contains(e.target)) {
        notifMenu.classList.add('hidden');
    }
});
</script>
