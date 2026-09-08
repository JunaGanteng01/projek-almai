<?php
$wpaPhoto = session()->get('wpaPhoto') ?? 'https://via.placeholder.com/40';
$wpaName = session()->get('wpaName') ?? session()->get('userName');
$wpaSpecialty = session()->get('wpaSpecialty') ?? 'WPA';
$wpaEmail = session()->get('userEmail') ?? '';
$currentUserId = session()->get('userId');

$poinModel = new \App\Models\PoinModel();
$userBalance = $poinModel->getUserBalance($currentUserId);

$wpaModel = new \App\Models\WpaModel();
$wpaData = $wpaModel->where('user_id', $currentUserId)->first();
$wpaBalance = $wpaData['balance'] ?? 0;

// Check if photo is local file
if (!empty($wpaPhoto) && strpos($wpaPhoto, 'http') !== 0) {
    // Strip writable/ prefix if exists
    if (strpos($wpaPhoto, 'writable/') === 0) {
        $wpaPhoto = str_replace('writable/', '', $wpaPhoto);
    }
    $wpaPhoto = base_url('file/' . $wpaPhoto);
}
?>
<!-- Header -->
<header class="bg-[#0a0a0a] border-b border-white/10 px-4 md:px-8 py-4 flex justify-between items-center fixed top-0 left-0 md:left-64 right-0 z-50 bg-[#0a0a0a]/95 backdrop-blur-md">
    <div class="flex items-center gap-3">
        <!-- Hamburger (Mobile Only) -->
        <button onclick="toggleSidebar()" class="md:hidden p-2 hover:bg-white/10 rounded-lg">
            <i class="fas fa-bars text-xl"></i>
        </button>
        <div>
            <h1 class="text-lg md:text-xl font-bold"><?= $pageTitle ?? 'Dashboard' ?></h1>
            <p class="text-xs md:text-sm text-gray-500 hidden md:block"><?= $pageSubtitle ?? 'Selamat datang, ' . esc($wpaName) ?></p>
        </div>
    </div>
    
    <!-- Mobile Profile & Notification -->
    <div class="flex md:hidden items-center gap-3">
        <?php
        $notifModel = new \App\Models\NotificationModel();
        $unreadCount = $notifModel->getUnreadCount(session()->get('userId'));
        $notifications = $notifModel->getByUserId(session()->get('userId'), 5);
        ?>
        <!-- Mobile Notification Bell -->
        <div class="relative">
            <button onclick="toggleNotifMenuMobile()" class="relative p-2 hover:bg-white/10 rounded-lg transition">
                <i class="fas fa-bell text-lg"></i>
                <?php if ($unreadCount > 0): ?>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-accent rounded-full border-2 border-[#0a0a0a]"></span>
                <?php endif; ?>
            </button>
            <div id="notifMenuMobile" class="hidden absolute right-0 top-full mt-2 w-80 max-w-[calc(100vw-2rem)] bg-[#111] border border-white/10 rounded-xl shadow-2xl overflow-hidden z-50">
                <div class="p-3 border-b border-white/10 flex justify-between items-center">
                    <span class="font-medium text-sm">Notifikasi</span>
                    <?php if ($unreadCount > 0): ?>
                        <a href="<?= base_url('wpa/dashboard/notifications/read-all') ?>" class="text-xs text-accent hover:underline">Tandai semua dibaca</a>
                    <?php endif; ?>
                </div>
                <div class="max-h-80 overflow-y-auto">
                    <?php if (empty($notifications)): ?>
                        <div class="p-4 text-center text-gray-500 text-sm">Tidak ada notifikasi</div>
                    <?php else: ?>
                        <?php foreach ($notifications as $notif): ?>
                            <a href="<?= base_url('wpa/dashboard/notifications/read/' . $notif['id']) ?>" class="block px-4 py-3 hover:bg-white/5 transition border-b border-white/5 <?= $notif['is_read'] ? 'opacity-60' : '' ?>">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 <?= $notif['type'] === 'success' ? 'bg-accent/20 text-accent' : ($notif['type'] === 'error' ? 'bg-red-500/20 text-red-400' : 'bg-blue-500/20 text-blue-400') ?>">
                                        <i class="fas <?= $notif['type'] === 'success' ? 'fa-check' : ($notif['type'] === 'error' ? 'fa-times' : 'fa-info') ?> text-xs"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium"><?= esc($notif['title']) ?></p>
                                        <p class="text-xs text-gray-500 mt-1 line-clamp-2"><?= esc($notif['message']) ?></p>
                                        <p class="text-xs text-gray-600 mt-1"><?= date('d M H:i', strtotime($notif['created_at'])) ?></p>
                                    </div>
                                    <?php if (!$notif['is_read']): ?>
                                        <div class="w-2 h-2 bg-accent rounded-full mt-3 shrink-0"></div>
                                    <?php endif; ?>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <a href="<?= base_url('wpa/dashboard/notifications') ?>" class="block p-3 text-center text-sm text-accent hover:bg-white/5 border-t border-white/10">Lihat Semua Notifikasi</a>
            </div>
        </div>

        <!-- Mobile Profile Photo with Dropdown -->
        <div class="relative">
            <button onclick="toggleProfileMenuMobile()" class="relative focus:outline-none">
                <img src="<?= esc($wpaPhoto) ?>" alt="<?= esc($wpaName) ?>" class="w-10 h-10 rounded-full object-cover border-2 border-white/20 hover:border-accent transition">
            </button>
            <div id="profileMenuMobile" class="hidden absolute right-0 top-full mt-2 w-56 bg-[#111] border border-white/10 rounded-xl shadow-2xl overflow-hidden" style="z-index: 9999;">
                <div class="p-4 border-b border-white/10 bg-white/5">
                    <p class="font-bold text-white text-base"><?= esc($wpaName) ?></p>
                    <div class="flex items-center gap-2 mt-2 text-accent font-bold text-sm">
                        <i class="fas fa-coins w-4"></i>
                        <span><?= number_format($userBalance ?? 0) ?> Poin</span>
                    </div>
                    <div class="flex items-center gap-2 mt-1 text-green-500 font-bold text-sm">
                        <i class="fas fa-wallet w-4"></i>
                        <span>Rp <?= number_format($wpaBalance, 0, ',', '.') ?></span>
                    </div>
                </div>

                <div class="py-2">
                    <a href="<?= base_url('wpa/dashboard') ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition text-sm text-gray-300 hover:text-white">
                        <i class="fas fa-th-large w-5 text-center text-gray-400"></i> Dashboard
                    </a>
                    <a href="<?= base_url('wpa/dashboard/poin') ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition text-sm text-gray-300 hover:text-white">
                        <i class="fas fa-coins w-5 text-center text-accent"></i> Poin Saya
                    </a>
                    <a href="<?= base_url('wpa/dashboard/profile') ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition text-sm text-gray-300 hover:text-white">
                        <i class="fas fa-user w-5 text-center text-gray-400"></i> Profile
                    </a>
                </div>

                <div class="border-t border-white/10 py-2">
                    <a href="<?= base_url('wpa/logout') ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-red-500/10 transition text-sm text-red-400 group">
                        <i class="fas fa-sign-out-alt w-5 text-center group-hover:translate-x-1 transition-transform"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Profile & Notification -->
    <div class="hidden md:flex items-center gap-4">
        <!-- Notification Bell -->
        <?php
        $notifModel = new \App\Models\NotificationModel();
        $unreadCount = $notifModel->getUnreadCount(session()->get('userId'));
        $notifications = $notifModel->getByUserId(session()->get('userId'), 5);
        ?>
        <div class="relative">
            <button onclick="toggleNotifMenu()" class="relative p-2 hover:bg-white/10 rounded-lg transition">
                <i class="fas fa-bell"></i>
                <?php if ($unreadCount > 0): ?>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-accent rounded-full border-2 border-[#0a0a0a]"></span>
                <?php endif; ?>
            </button>
            <div id="notifMenu" class="hidden absolute right-0 top-full mt-2 w-80 bg-[#111] border border-white/10 rounded-xl shadow-2xl overflow-hidden z-50">
                <div class="p-3 border-b border-white/10 flex justify-between items-center">
                    <span class="font-medium text-sm">Notifikasi</span>
                    <?php if ($unreadCount > 0): ?>
                        <a href="<?= base_url('wpa/dashboard/notifications/read-all') ?>" class="text-xs text-accent hover:underline">Tandai semua dibaca</a>
                    <?php endif; ?>
                </div>
                <div class="max-h-80 overflow-y-auto">
                    <?php if (empty($notifications)): ?>
                        <div class="p-4 text-center text-gray-500 text-sm">Tidak ada notifikasi</div>
                    <?php else: ?>
                        <?php foreach ($notifications as $notif): ?>
                            <a href="<?= base_url('wpa/dashboard/notifications/read/' . $notif['id']) ?>" class="block px-4 py-3 hover:bg-white/5 transition border-b border-white/5 <?= $notif['is_read'] ? 'opacity-60' : '' ?>">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 <?= $notif['type'] === 'success' ? 'bg-accent/20 text-accent' : ($notif['type'] === 'error' ? 'bg-red-500/20 text-red-400' : 'bg-blue-500/20 text-blue-400') ?>">
                                        <i class="fas <?= $notif['type'] === 'success' ? 'fa-check' : ($notif['type'] === 'error' ? 'fa-times' : 'fa-info') ?> text-xs"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium"><?= esc($notif['title']) ?></p>
                                        <p class="text-xs text-gray-500 mt-1 line-clamp-2"><?= esc($notif['message']) ?></p>
                                        <p class="text-xs text-gray-600 mt-1"><?= date('d M H:i', strtotime($notif['created_at'])) ?></p>
                                    </div>
                                    <?php if (!$notif['is_read']): ?>
                                        <div class="w-2 h-2 bg-accent rounded-full mt-3 shrink-0"></div>
                                    <?php endif; ?>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <a href="<?= base_url('wpa/dashboard/notifications') ?>" class="block p-3 text-center text-sm text-accent hover:bg-white/5 border-t border-white/10">Lihat Semua Notifikasi</a>
            </div>
        </div>

        <!-- Profile Menu -->
        <div class="relative">
            <button onclick="toggleProfileMenu()" class="flex items-center gap-3 p-1 hover:bg-white/5 rounded-lg transition">
                <img src="<?= esc($wpaPhoto) ?>" alt="<?= esc($wpaName) ?>" class="w-10 h-10 rounded-full object-cover border border-white/20">
                <div class="text-left">
                    <span class="font-medium text-sm block"><?= esc($wpaName) ?></span>
                    <span class="text-xs text-accent"><?= esc($wpaSpecialty) ?></span>
                </div>
                <i class="fas fa-chevron-down text-xs text-gray-400 ml-1"></i>
            </button>
            <div id="profileMenu" class="hidden absolute right-0 top-full mt-2 w-56 bg-[#111] border border-white/10 rounded-xl shadow-xl overflow-hidden z-50">
                <div class="p-4 border-b border-white/10 bg-white/5">
                    <p class="font-bold text-white text-base"><?= esc($wpaName) ?></p>
                    <div class="flex items-center gap-2 mt-2 text-accent font-bold text-sm">
                        <i class="fas fa-coins w-4"></i>
                        <span><?= number_format($userBalance ?? 0) ?> Poin</span>
                    </div>
                    <div class="flex items-center gap-2 mt-1 text-green-500 font-bold text-sm">
                        <i class="fas fa-wallet w-4"></i>
                        <span>Rp <?= number_format($wpaBalance, 0, ',', '.') ?></span>
                    </div>
                </div>

                <div class="py-2">
                    <a href="<?= base_url('wpa/dashboard') ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition text-sm text-gray-300 hover:text-white">
                        <i class="fas fa-th-large w-5 text-center text-gray-400"></i> Dashboard
                    </a>
                    <a href="<?= base_url('wpa/dashboard/poin') ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition text-sm text-gray-300 hover:text-white">
                        <i class="fas fa-coins w-5 text-center text-accent"></i> Poin Saya
                    </a>
                    <a href="<?= base_url('wpa/dashboard/profile') ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition text-sm text-gray-300 hover:text-white">
                        <i class="fas fa-user w-5 text-center text-gray-400"></i> Profile
                    </a>
                </div>

                <div class="border-t border-white/10 py-2">
                    <a href="<?= base_url('wpa/logout') ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-red-500/10 transition text-sm text-red-400 group">
                        <i class="fas fa-sign-out-alt w-5 text-center group-hover:translate-x-1 transition-transform"></i> Logout
                    </a>
                </div>
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

    function toggleNotifMenuMobile() {
        document.getElementById('notifMenuMobile').classList.toggle('hidden');
        document.getElementById('profileMenuMobile')?.classList.add('hidden');
    }

    function toggleProfileMenuMobile() {
        document.getElementById('profileMenuMobile').classList.toggle('hidden');
        document.getElementById('notifMenuMobile')?.classList.add('hidden');
    }

    // Close menus when clicking outside
    document.addEventListener('click', function(e) {
        // Desktop menus
        if (!e.target.closest('#profileMenu') && !e.target.closest('button[onclick="toggleProfileMenu()"]')) {
            document.getElementById('profileMenu')?.classList.add('hidden');
        }
        if (!e.target.closest('#notifMenu') && !e.target.closest('button[onclick="toggleNotifMenu()"]')) {
            document.getElementById('notifMenu')?.classList.add('hidden');
        }
        
        // Mobile menus
        if (!e.target.closest('#profileMenuMobile') && !e.target.closest('button[onclick="toggleProfileMenuMobile()"]')) {
            document.getElementById('profileMenuMobile')?.classList.add('hidden');
        }
        if (!e.target.closest('#notifMenuMobile') && !e.target.closest('button[onclick="toggleNotifMenuMobile()"]')) {
            document.getElementById('notifMenuMobile')?.classList.add('hidden');
        }
    });
</script>