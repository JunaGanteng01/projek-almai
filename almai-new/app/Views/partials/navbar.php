<?php
$currentUrl = current_url();
$session = session();
$isLoggedIn = $session->get('isLoggedIn');
$userName = $session->get('userName');
$userRole = $session->get('userRole');
$userAvatar = $session->get('userAvatar');
$userPoin = $session->get('userPoin') ?? 0;

// Get avatar URL
$avatarUrl = base_url('images/default-avatar.png');
if (!empty($userAvatar)) {
    $avatarUrl = base_url('file/' . $userAvatar);
}

// Fetch fresh data if logged in
if ($isLoggedIn) {
    $userId = $session->get('userId');
    $poinModel = new \App\Models\PoinModel();
    $userModel = new \App\Models\UserModel();

    // Get fresh points
    $userPoin = $poinModel->getUserBalance($userId);

    // Get unread notifications
    $notifModel = new \App\Models\NotificationModel();
    $unreadNotifCount = $notifModel->getUnreadCount($userId);

    // Get fresh balance & avatar
    $currentUser = $userModel->find($userId);
    $userBalance = $currentUser['balance'] ?? 0;

    // RE-CALCULATE Avatar URL from fresh DB data
    if (!empty($currentUser['avatar'])) {
        $avatarUrl = base_url('file/' . $currentUser['avatar']);
    }
} else {
    $userPoin = 0;
    $userBalance = 0;
}
?>
<nav class="fixed top-0 w-full z-50 px-4 py-4">
    <div class="max-w-7xl mx-auto bg-black/80 backdrop-blur-md border border-white/10 rounded-full px-6 py-3 flex justify-between items-center">
        <a href="<?= base_url('/') ?>" class="flex items-center gap-2">
            <img src="<?= base_url('images/alma.gif') ?>" alt="ALMAI" class="h-8">
            <span class="font-bold tracking-tighter text-lg">ALMAI</span>
        </a>
        <ul class="hidden md:flex gap-8 text-sm font-medium">
            <li><a href="<?= base_url('/') ?>" class="<?= $currentUrl == base_url('/') ? 'text-accent' : 'hover:text-accent transition' ?>">Home</a></li>
            <li><a href="<?= base_url('advokasi') ?>" class="<?= strpos($currentUrl, 'advokasi') !== false ? 'text-accent' : 'hover:text-accent transition' ?>">Advokasi</a></li>
            <li><a href="<?= base_url('wpa') ?>" class="<?= strpos($currentUrl, '/wpa') !== false && strpos($currentUrl, '/wpa/dashboard') === false ? 'text-accent' : 'hover:text-accent transition' ?>">WPA</a></li>
            <li><a href="<?= base_url('tools') ?>" class="<?= strpos($currentUrl, 'tools') !== false ? 'text-accent' : 'hover:text-accent transition' ?>">Tools</a></li>

         
            
            
        </ul>

        <?php if ($isLoggedIn): ?>
            <div class="hidden md:flex items-center gap-4 relative">
                <!-- Notification Bell -->
                <a href="<?= base_url('user/notifications') ?>" class="relative p-2 hover:bg-white/10 rounded-full transition group">
                    <i class="fas fa-bell text-gray-400 group-hover:text-accent transition"></i>
                    <?php if (isset($unreadNotifCount) && $unreadNotifCount > 0): ?>
                        <span class="absolute top-0 right-0 w-4 h-4 bg-red-500 text-white text-[10px] font-bold flex items-center justify-center rounded-full">
                            <?= $unreadNotifCount > 9 ? '9+' : $unreadNotifCount ?>
                        </span>
                    <?php endif; ?>
                </a>

                <!-- User Dropdown -->
                <div class="relative" id="userDropdown">
                    <button onclick="toggleUserDropdown()" class="flex items-center gap-2 px-3 py-2 rounded-full hover:bg-white/10 transition">
                        <img src="<?= $avatarUrl ?>" alt="<?= esc($userName) ?>" class="w-8 h-8 rounded-full object-cover border-2 border-accent">
                        <span class="text-sm font-medium"><?= esc($userName) ?></span>
                        <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div id="userDropdownMenu" class="hidden absolute right-0 top-full mt-2 w-56 bg-[#111] border border-white/10 rounded-xl shadow-xl overflow-hidden z-50">
                        <!-- User Info -->
                        <div class="px-4 py-3 border-b border-white/10 bg-black/50">
                            <p class="text-sm font-bold"><?= esc($userName) ?></p>
                            <div class="flex items-center gap-1 text-accent text-sm mt-1">
                                <i class="fas fa-coins"></i>
                                <span class="font-bold"><?= number_format($userPoin) ?></span>
                                <span class="text-gray-400">Poin</span>
                            </div>
                            <div class="flex items-center gap-1 text-green-500 text-sm mt-1">
                                <i class="fas fa-wallet"></i>
                                <span class="font-bold">Rp <?= number_format($userBalance ?? 0, 0, ',', '.') ?></span>
                            </div>
                        </div>

                        <!-- Menu Items -->
                        <div class="py-2">
                            <?php
                            $dashboardLink = base_url(\App\Models\LevelModel::getDashboardPath(\App\Models\LevelModel::resolveLevelId($session)));
                            ?>
                            <a href="<?= $dashboardLink ?>" class="flex items-center gap-3 px-4 py-2 hover:bg-white/5 transition">
                                <i class="fas fa-th-large text-gray-400 w-5"></i>
                                <span class="text-sm">Dashboard</span>
                            </a>
                            <a href="<?= base_url('user/poin') ?>" class="flex items-center gap-3 px-4 py-2 hover:bg-white/5 transition">
                                <i class="fas fa-coins text-accent w-5"></i>
                                <span class="text-sm">Poin Saya</span>
                            </a>
                            <a href="<?= base_url('user/profile') ?>" class="flex items-center gap-3 px-4 py-2 hover:bg-white/5 transition">
                                <i class="fas fa-user text-gray-400 w-5"></i>
                                <span class="text-sm">Profile</span>
                            </a>
                        </div>

                        <!-- Logout -->
                        <div class="border-t border-white/10 py-2">
                            <a href="<?= base_url('logout') ?>" class="flex items-center gap-3 px-4 py-2 hover:bg-red-500/10 text-red-400 transition">
                                <i class="fas fa-sign-out-alt w-5"></i>
                                <span class="text-sm">Logout</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <a href="<?= base_url('login') ?>" class="hidden md:inline-block px-6 py-2 bg-accent text-black font-bold rounded-full text-sm hover:bg-white transition">
                Login
            </a>
        <?php endif; ?>

        <button class="md:hidden text-xl" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')">
            <i class="fas fa-bars"></i>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden md:hidden mt-2 bg-black/95 backdrop-blur-md border border-white/10 rounded-2xl p-4">
        <a href="<?= base_url('/') ?>" class="block py-2 <?= $currentUrl == base_url('/') ? 'text-accent' : '' ?>">Home</a>
          <a href="<?= base_url('advokasi') ?>" class="block py-2 <?= strpos($currentUrl, 'advokasi') !== false ? 'text-accent' : '' ?>">Advokasi</a>
        <a href="<?= base_url('wpa') ?>" class="block py-2 <?= strpos($currentUrl, '/wpa') !== false && strpos($currentUrl, '/wpa/dashboard') === false ? 'text-accent' : '' ?>">WPA</a>
        <a href="<?= base_url('tools') ?>" class="block py-2 <?= strpos($currentUrl, 'tools') !== false ? 'text-accent' : '' ?>">Tools</a>

   
        <?php if ($isLoggedIn): ?>
            <div class="border-t border-white/10 mt-2 pt-2">
                <div class="flex items-center gap-3 py-2">
                    <img src="<?= $avatarUrl ?>" alt="<?= esc($userName) ?>" class="w-10 h-10 rounded-full object-cover border-2 border-accent">
                    <div>
                        <p class="font-bold text-sm"><?= esc($userName) ?></p>
                        <p class="text-accent text-xs"><i class="fas fa-coins mr-1"></i><?= number_format($userPoin) ?> Poin</p>
                        <p class="text-green-500 text-xs mt-0.5"><i class="fas fa-wallet mr-1"></i>Rp <?= number_format($userBalance ?? 0, 0, ',', '.') ?></p>
                    </div>
                </div>
                <a href="<?= $dashboardLink ?>" class="block py-2"><i class="fas fa-th-large mr-2 text-gray-400"></i>Dashboard</a>
                <a href="<?= base_url('user/poin') ?>" class="block py-2"><i class="fas fa-coins mr-2 text-accent"></i>Poin Saya</a>
                <a href="<?= base_url('user/profile') ?>" class="block py-2"><i class="fas fa-user mr-2 text-gray-400"></i>Profile</a>
                <a href="<?= base_url('logout') ?>" class="block py-2 text-red-400"><i class="fas fa-sign-out-alt mr-2"></i>Logout</a>
            </div>
        <?php else: ?>
            <a href="<?= base_url('login') ?>" class="block py-2 text-accent font-bold">Login</a>
        <?php endif; ?>
    </div>
</nav>

<script>
    function toggleUserDropdown() {
        const menu = document.getElementById('userDropdownMenu');
        menu.classList.toggle('hidden');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const dropdown = document.getElementById('userDropdown');
        const menu = document.getElementById('userDropdownMenu');
        if (dropdown && menu && !dropdown.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });
</script>