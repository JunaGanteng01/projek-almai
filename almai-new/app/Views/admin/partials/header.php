<?php
$notifModel = new \App\Models\NotificationModel();
$currentUserId = session()->get('userId');
$notifications = $notifModel->getByUserId($currentUserId, 5);
$unreadCount = $notifModel->getUnreadCount($currentUserId);
?>
<!-- Header -->
<header class="bg-[#080808]/95 backdrop-blur-md border-b border-white/[0.08] px-4 sm:px-6 lg:px-8 h-16 sm:h-[68px] flex justify-between items-center fixed top-0 right-0 left-0 md:left-64 z-40 transition-all duration-300">
    <div class="flex items-center gap-3">
        <button type="button" onclick="toggleSidebar()" class="md:hidden p-2 text-gray-400 hover:text-white hover:bg-white/[0.06] rounded-xl transition">
            <i class="fas fa-bars text-lg"></i>
        </button>
        <div>
            <h1 class="text-base sm:text-lg lg:text-xl font-extrabold text-white tracking-tight truncate max-w-[180px] sm:max-w-none">
                <?= $pageTitle ?? $title ?? 'Dashboard' ?>
            </h1>
            <p class="text-xs text-gray-400 hidden sm:block">
                <?= $pageSubtitle ?? 'Selamat datang, <span class="text-accent font-medium">' . esc(session()->get('userName')) . '</span>' ?>
            </p>
        </div>
    </div>
    
    <div class="flex items-center gap-2.5 sm:gap-3">
        <!-- Notification Bell -->
        <div class="relative">
            <button type="button" onclick="toggleNotifMenu()" class="relative p-2.5 text-gray-300 hover:text-white bg-[#111] hover:bg-[#161616] border border-white/[0.08] hover:border-accent/40 rounded-xl transition shadow-sm">
                <i class="fas fa-bell text-sm"></i>
                <?php if ($unreadCount > 0): ?>
                <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center border-2 border-[#080808] animate-pulse">
                    <?= $unreadCount > 9 ? '9+' : $unreadCount ?>
                </span>
                <?php endif; ?>
            </button>
            <div id="notifMenu" class="hidden absolute right-0 top-full mt-2 w-[calc(100vw-2rem)] sm:w-80 bg-[#0e0e0e] border border-white/[0.1] rounded-2xl shadow-2xl overflow-hidden z-50">
                <div class="p-3.5 border-b border-white/[0.08] flex justify-between items-center bg-black/40">
                    <span class="font-bold text-sm text-white flex items-center gap-2">
                        <i class="fas fa-bell text-accent text-xs"></i> Notifikasi
                    </span>
                    <?php if ($unreadCount > 0): ?>
                    <a href="<?= base_url('admin/notifications/read-all') ?>" class="text-[11px] font-semibold text-accent hover:underline">Tandai semua dibaca</a>
                    <?php endif; ?>
                </div>
                <div class="max-h-80 overflow-y-auto custom-scrollbar divide-y divide-white/[0.04]">
                    <?php if (empty($notifications)): ?>
                    <div class="p-6 text-center text-gray-500 text-xs">
                        <i class="fas fa-bell-slash text-xl mb-2 text-gray-600"></i>
                        <p>Tidak ada notifikasi baru</p>
                    </div>
                    <?php else: ?>
                    <?php foreach ($notifications as $notif): ?>
                    <a href="<?= base_url('admin/notifications/read/' . $notif['id']) ?>" class="block px-4 py-3 hover:bg-white/[0.03] transition <?= $notif['is_read'] ? 'opacity-50' : '' ?>">
                        <div class="flex items-start gap-3">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0 <?= $notif['type'] === 'success' ? 'bg-accent/20 text-accent' : ($notif['type'] === 'error' ? 'bg-red-500/20 text-red-400' : 'bg-blue-500/20 text-blue-400') ?>">
                                <i class="fas <?= $notif['type'] === 'success' ? 'fa-check' : ($notif['type'] === 'error' ? 'fa-times' : 'fa-info') ?> text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-white truncate"><?= esc($notif['title']) ?></p>
                                <p class="text-[11px] text-gray-400 mt-0.5 line-clamp-2"><?= esc($notif['message']) ?></p>
                                <p class="text-[10px] text-gray-500 mt-1"><?= date('d M, H:i', strtotime($notif['created_at'])) ?></p>
                            </div>
                            <?php if (!$notif['is_read']): ?>
                            <div class="w-2 h-2 bg-accent rounded-full shrink-0 mt-1.5 shadow-[0_0_8px_rgba(51,232,24,0.8)]"></div>
                            <?php endif; ?>
                        </div>
                    </a>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <a href="<?= base_url('admin/notifications') ?>" class="block p-3 text-center text-xs font-bold text-accent hover:text-white hover:bg-accent/10 border-t border-white/[0.08] transition">
                    Lihat Semua Notifikasi <i class="fas fa-arrow-right ml-1 text-[10px]"></i>
                </a>
            </div>
        </div>
        
        <!-- Profile Menu Dropdown -->
        <div class="relative">
            <button type="button" onclick="toggleProfileMenu()" class="flex items-center gap-2.5 p-1.5 sm:px-3 sm:py-1.5 bg-[#111] hover:bg-[#161616] border border-white/[0.08] hover:border-accent/40 rounded-xl transition">
                <div class="w-7 h-7 sm:w-8 sm:h-8 bg-accent/20 border border-accent/40 rounded-lg flex items-center justify-center text-accent">
                    <i class="fas fa-user-shield text-xs"></i>
                </div>
                <div class="text-left hidden sm:block">
                    <span class="block text-xs font-bold text-white leading-tight truncate max-w-[100px]"><?= esc(session()->get('userName')) ?></span>
                    <span class="block text-[10px] text-accent font-medium leading-tight">Admin</span>
                </div>
                <i class="fas fa-chevron-down text-[10px] text-gray-400 hidden sm:block"></i>
            </button>
            <!-- Profile Dropdown -->
            <div id="profileMenu" class="hidden absolute right-0 top-full mt-2 w-52 bg-[#0e0e0e] border border-white/[0.1] rounded-2xl shadow-2xl overflow-hidden z-50">
                <div class="p-3.5 border-b border-white/[0.08] bg-black/40">
                    <p class="font-bold text-xs text-white truncate"><?= esc(session()->get('userName')) ?></p>
                    <p class="text-[11px] text-gray-400 truncate mt-0.5"><?= esc(session()->get('userEmail')) ?></p>
                </div>
                <div class="p-1.5 space-y-0.5 text-xs">
                    <a href="<?= base_url('admin/profile') ?>" class="flex items-center gap-2.5 px-3 py-2 text-gray-300 hover:text-white hover:bg-white/[0.06] rounded-xl transition">
                        <i class="fas fa-user-cog w-4 text-gray-400"></i> Profile
                    </a>
                    <a href="<?= base_url('admin/logout') ?>" class="w-full flex items-center gap-2.5 px-3 py-2 text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-xl transition">
                        <i class="fas fa-sign-out-alt w-4"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    function toggleNotifMenu() {
        const notif = document.getElementById('notifMenu');
        const profile = document.getElementById('profileMenu');
        if (notif) notif.classList.toggle('hidden');
        if (profile) profile.classList.add('hidden');
    }

    function toggleProfileMenu() {
        const notif = document.getElementById('notifMenu');
        const profile = document.getElementById('profileMenu');
        if (profile) profile.classList.toggle('hidden');
        if (notif) notif.classList.add('hidden');
    }

    // Close on outside click
    document.addEventListener('click', function(e) {
        const notifMenu = document.getElementById('notifMenu');
        const profileMenu = document.getElementById('profileMenu');
        if (notifMenu && !notifMenu.contains(e.target) && !e.target.closest('button[onclick="toggleNotifMenu()"]')) {
            notifMenu.classList.add('hidden');
        }
        if (profileMenu && !profileMenu.contains(e.target) && !e.target.closest('button[onclick="toggleProfileMenu()"]')) {
            profileMenu.classList.add('hidden');
        }
    });
</script>
