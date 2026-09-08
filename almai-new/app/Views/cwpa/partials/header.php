<?php
$cwpaPhoto = session()->get('cwpaPhoto') ?? 'https://via.placeholder.com/40';
$cwpaName = session()->get('cwpaName') ?? session()->get('userName');
$cwpaPhase = session()->get('cwpaPhase') ?? 1;
$userEmail = session()->get('userEmail') ?? '';

// Check if photo is local file
if (!empty($cwpaPhoto) && strpos($cwpaPhoto, 'http') !== 0) {
    if (strpos($cwpaPhoto, 'writable/') === 0) {
        $cwpaPhoto = str_replace('writable/', '', $cwpaPhoto);
    }
    $cwpaPhoto = base_url('file/' . ltrim($cwpaPhoto, '/'));
}

$notifModel = new \App\Models\NotificationModel();
$currentUserId = session()->get('userId');
$unreadCount = $notifModel->getUnreadCount($currentUserId);
$notifications = $notifModel->getByUserId($currentUserId, 5);
?>
<!-- Header Desktop -->
<header class="hidden md:flex bg-[#0a0a0a]/95 backdrop-blur-lg border-b border-white/10 px-8 py-4 justify-between items-center fixed top-0 left-64 right-0 z-[60]">
    <div>
        <h1 class="text-lg md:text-xl font-bold tracking-wide">ALMAI</h1>
        <div class="flex items-center gap-2 text-xs md:text-sm text-gray-400 mt-0.5">
            <?php if (($activeMenu ?? '') === 'dashboard'): ?>
                <span>Selamat Datang, <span class="text-white"><?= esc($cwpaName) ?></span></span>
            <?php else: ?>
                <a href="<?= base_url('cwpa/dashboard') ?>" class="hover:text-white transition">Dashboard</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span class="text-white"><?= esc($pageTitle ?? '') ?></span>
            <?php endif; ?>
        </div>
    </div>
    <div class="flex items-center gap-4">
        <!-- Notification Bell -->
        <div class="relative">
            <button onclick="toggleNotifMenu(event)" class="notif-btn relative p-2 hover:bg-white/10 rounded-lg transition">
                <i class="fas fa-bell"></i>
                <?php if ($unreadCount > 0): ?>
                    <span class="absolute top-0 right-0 w-2 h-2 bg-accent rounded-full"></span>
                <?php endif; ?>
            </button>
        </div>
        
        <!-- Profile Menu Desktop -->
        <div class="relative">
            <button onclick="toggleProfileMenu(event)" class="profile-btn flex items-center gap-2 p-1 hover:bg-white/5 rounded-lg transition group">
                <img src="<?= esc($cwpaPhoto) ?>" alt="<?= esc($cwpaName) ?>" class="w-10 h-10 rounded-full object-cover ring-2 ring-transparent group-hover:ring-accent/50 transition">
                <span class="font-medium text-sm hidden md:block"><?= esc($cwpaName) ?></span>
                <i class="fas fa-chevron-down text-xs text-gray-400 hidden md:block"></i>
            </button>
        </div>
    </div>
</header>

<!-- Header Mobile -->
<header class="md:hidden flex bg-[#0a0a0a]/95 backdrop-blur-lg border-b border-white/10 px-4 py-3 justify-between items-center fixed top-0 left-0 right-0 z-[60]">
    <button onclick="toggleSidebar()" class="p-2 text-white">
        <i class="fas fa-bars text-xl"></i>
    </button>
    
    <h1 class="text-lg font-black tracking-tighter text-white">ALMAI</h1>

    <div class="flex items-center gap-3">
        <button onclick="toggleNotifMenu(event)" class="notif-btn relative p-2 text-white">
            <i class="fas fa-bell"></i>
            <?php if ($unreadCount > 0): ?>
                <span class="absolute top-1 right-1 w-2 h-2 bg-accent rounded-full border-2 border-[#0a0a0a]"></span>
            <?php endif; ?>
        </button>
        <button onclick="toggleProfileMenu(event)" class="profile-btn relative">
            <img src="<?= esc($cwpaPhoto) ?>" alt="<?= esc($cwpaName) ?>" class="w-9 h-9 rounded-full object-cover border-2 border-white/10">
        </button>
    </div>
</header>

<?php
// Fetch for Dropdown Data mapping
$poinModel = new \App\Models\PoinModel();
$currentUserId = session()->get('userId');
$userBalance = $poinModel->getUserBalance($currentUserId);
$cwpaModel = new \App\Models\CwpaModel();
$cwpaData = $cwpaModel->where('user_id', $currentUserId)->first();
$cwpaBalance = $cwpaData['balance'] ?? 0;
?>

<!-- Universal Notification Dropdown -->
<div id="notifMenu" class="hidden fixed top-[65px] md:top-[75px] right-4 md:right-24 w-80 bg-[#111] border border-white/10 rounded-xl shadow-xl overflow-hidden z-50" style="z-index: 9999;">
    <div class="p-3 border-b border-white/10 flex justify-between items-center">
        <span class="font-medium text-sm">Notifikasi</span>
        <?php if ($unreadCount > 0): ?>
            <a href="<?= base_url('cwpa/dashboard/notifications/read-all') ?>" class="text-xs text-accent hover:underline">Tandai semua dibaca</a>
        <?php endif; ?>
    </div>
    <div class="max-h-80 overflow-y-auto">
        <?php if (empty($notifications)): ?>
            <div class="p-4 text-center text-gray-500 text-sm">Tidak ada notifikasi</div>
        <?php else: ?>
            <?php foreach ($notifications as $notif): ?>
                <a href="<?= base_url('cwpa/dashboard/notifications/read/' . $notif['id']) ?>" class="block px-4 py-3 hover:bg-white/5 transition border-b border-white/5 <?= $notif['is_read'] ? 'opacity-60' : '' ?>">
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
    <a href="<?= base_url('cwpa/dashboard/notifications') ?>" class="block p-3 text-center text-sm text-accent hover:bg-white/5 border-t border-white/10">Lihat Semua Notifikasi</a>
</div>

<!-- Universal Profile Dropdown -->
<div id="profileMenu" class="hidden fixed top-[65px] md:top-[75px] right-4 md:right-8 w-56 bg-[#111] border border-white/10 rounded-xl shadow-xl overflow-hidden z-50" style="z-index: 9999;">
    <div class="p-4 border-b border-white/10 bg-white/5">
        <p class="font-bold text-white text-base"><?= esc($cwpaName) ?></p>
        <div class="flex items-center gap-2 mt-2 text-accent font-bold text-sm">
            <i class="fas fa-coins w-4"></i>
            <span><?= number_format($userBalance ?? 0) ?> Poin</span>
        </div>
        <div class="flex items-center gap-2 mt-1 text-green-500 font-bold text-sm">
            <i class="fas fa-wallet w-4"></i>
            <span>Rp <?= number_format($cwpaBalance, 0, ',', '.') ?></span>
        </div>
    </div>

    <div class="py-2">
        <a href="<?= base_url('cwpa/dashboard') ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition text-sm text-gray-300 hover:text-white">
            <i class="fas fa-th-large w-5 text-center text-gray-400"></i> Dashboard
        </a>
        <a href="<?= base_url('cwpa/dashboard/earnings') ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition text-sm text-gray-300 hover:text-white">
            <i class="fas fa-coins w-5 text-center text-accent"></i> Poin Saya
        </a>
        <a href="<?= base_url('cwpa/dashboard/profile') ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition text-sm text-gray-300 hover:text-white">
            <i class="fas fa-user w-5 text-center text-gray-400"></i> Profile
        </a>
    </div>

    <div class="border-t border-white/10 py-2">
        <a href="<?= base_url('cwpa/logout') ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-red-500/10 transition text-sm text-red-400 group">
            <i class="fas fa-sign-out-alt w-5 text-center group-hover:translate-x-1 transition-transform"></i> Logout
        </a>
    </div>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if (sidebar) sidebar.classList.toggle('-translate-x-full');
        if (overlay) overlay.classList.toggle('hidden');
    }

    function toggleNotifMenu(e) {
        if(e) e.stopPropagation();
        const notif = document.getElementById('notifMenu');
        const profile = document.getElementById('profileMenu');
        if (notif) notif.classList.toggle('hidden');
        if (profile) profile.classList.add('hidden');
    }

    function toggleProfileMenu(e) {
        if(e) e.stopPropagation();
        const profile = document.getElementById('profileMenu');
        const notif = document.getElementById('notifMenu');
        if (notif) notif.classList.add('hidden');
        if (profile) profile.classList.toggle('hidden');
    }

    // Auto-close on outside click
    document.addEventListener('click', function(e) {
        const profileMenu = document.getElementById('profileMenu');
        const notifMenu = document.getElementById('notifMenu');
        const profileBtn = e.target.closest('.profile-btn');
        const notifBtn = e.target.closest('.notif-btn');

        if (!profileBtn && profileMenu && !profileMenu.contains(e.target)) {
            profileMenu.classList.add('hidden');
        }
        if (!notifBtn && notifMenu && !notifMenu.contains(e.target)) {
            notifMenu.classList.add('hidden');
        }
    });
</script>
