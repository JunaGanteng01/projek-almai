<?php
$notifModel = new \App\Models\NotificationModel();
$userModel = new \App\Models\UserModel();
$currentUserId = session()->get('userId');
$notifications = $notifModel->getByUserId($currentUserId, 5);
$unreadCount = $notifModel->getUnreadCount($currentUserId);
$currentUser = $userModel->find($currentUserId);
$avatarUrl = $currentUser['avatar'] ?? null;
$hasAvatar = $avatarUrl && file_exists(WRITEPATH . $avatarUrl);
?>
<!-- Header -->
<header class="hidden md:flex bg-[#0a0a0a]/95 backdrop-blur-lg border-b border-white/10 px-8 py-4 justify-between items-center fixed top-0 left-64 right-0 z-[60]">
    <div>
        <h1 class="text-lg md:text-xl font-bold tracking-wide">ALMAI</h1>
        <div class="flex items-center gap-2 text-xs md:text-sm text-gray-400 mt-0.5">
            <?php if (($pageTitle ?? '') === 'Dashboard'): ?>
                <span>Selamat Datang, <span class="text-white"><?= esc(session()->get('userName')) ?></span></span>
            <?php else: ?>
                <a href="<?= base_url('user/dashboard') ?>" class="hover:text-white transition">Dashboard</a>
                <i class="fas fa-chevron-right text-[10px]"></i>
                <span class="text-white"><?= esc($pageTitle ?? '') ?></span>
            <?php endif; ?>
        </div>
    </div>
    <div class="hidden md:flex items-center gap-4">
        <!-- Notification Bell -->
        <div class="relative">
            <button onclick="toggleNotifMenu()" class="relative p-2 hover:bg-white/10 rounded-lg transition">
                <i class="fas fa-bell"></i>
                <?php if ($unreadCount > 0): ?>
                    <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center"><?= $unreadCount > 9 ? '9+' : $unreadCount ?></span>
                <?php endif; ?>
            </button>
            <div id="notifMenu" class="hidden absolute right-0 top-full mt-2 w-80 bg-[#111] border border-white/10 rounded-xl shadow-xl overflow-hidden z-50">
                <div class="p-3 border-b border-white/10 flex justify-between items-center">
                    <span class="font-medium text-sm">Notifikasi</span>
                    <?php if ($unreadCount > 0): ?>
                        <a href="<?= base_url('user/notifications/read-all') ?>" class="text-xs text-accent hover:underline">Tandai semua dibaca</a>
                    <?php endif; ?>
                </div>
                <div class="max-h-80 overflow-y-auto">
                    <?php if (empty($notifications)): ?>
                        <div class="p-4 text-center text-gray-500 text-sm">Tidak ada notifikasi</div>
                    <?php else: ?>
                        <?php foreach ($notifications as $notif): ?>
                            <a href="<?= base_url('user/notifications/read/' . $notif['id']) ?>" class="block px-4 py-3 hover:bg-white/5 transition border-b border-white/5 <?= $notif['is_read'] ? 'opacity-60' : '' ?>">
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
                <a href="<?= base_url('user/notifications') ?>" class="block p-3 text-center text-sm text-accent hover:bg-white/5 border-t border-white/10">Lihat Semua Notifikasi</a>
            </div>
        </div>

        <!-- Profile Menu -->
        <div class="relative">
            <button onclick="toggleProfileMenu()" class="flex items-center gap-2 p-1 hover:bg-white/5 rounded-lg transition group">
                <?php if ($hasAvatar): ?>
                    <img src="<?= base_url('file/' . $avatarUrl) ?>" alt="Avatar" class="w-10 h-10 rounded-full object-cover ring-2 ring-transparent group-hover:ring-accent/50 transition">
                <?php else: ?>
                    <div class="w-10 h-10 bg-accent/20 rounded-full flex items-center justify-center ring-2 ring-transparent group-hover:ring-accent/50 transition">
                        <i class="fas fa-user text-accent text-sm"></i>
                    </div>
                <?php endif; ?>
                <span class="font-medium text-sm hidden md:block"><?= esc(session()->get('userName')) ?></span>
                <i class="fas fa-chevron-down text-xs text-gray-400 hidden md:block"></i>
            </button>

            <?php
            // Fetch point balance
            $poinModel = new \App\Models\PoinModel();
            $userBalance = $poinModel->getUserBalance($currentUserId);
            ?>

            <div id="profileMenu" class="hidden absolute right-0 top-full mt-2 w-56 bg-[#111] border border-white/10 rounded-xl shadow-xl overflow-hidden z-50">
                <div class="p-4 border-b border-white/10 bg-white/5">
                    <p class="font-bold text-white text-base"><?= esc($currentUser['name']) ?></p>
                    <div class="flex items-center gap-2 mt-2 text-accent font-bold text-sm">
                        <i class="fas fa-coins w-4"></i>
                        <span><?= number_format($userBalance ?? 0) ?> Poin</span>
                    </div>
                    <div class="flex items-center gap-2 mt-1 text-green-500 font-bold text-sm">
                        <i class="fas fa-wallet w-4"></i>
                        <span>Rp <?= number_format($currentUser['balance'] ?? 0, 0, ',', '.') ?></span>
                    </div>
                </div>

                <div class="py-2">
                    <?php
                    $dashboardLink = base_url(\App\Models\LevelModel::getDashboardPath(\App\Models\LevelModel::resolveLevelId(session())));
                    ?>
                    <a href="<?= $dashboardLink ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition text-sm text-gray-300 hover:text-white">
                        <i class="fas fa-th-large w-5 text-center text-gray-400"></i> Dashboard
                    </a>
                    <a href="<?= base_url('user/poin') ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition text-sm text-gray-300 hover:text-white">
                        <i class="fas fa-coins w-5 text-center text-accent"></i> Poin Saya
                    </a>
                    <a href="<?= base_url('user/profile') ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition text-sm text-gray-300 hover:text-white">
                        <i class="fas fa-user w-5 text-center text-gray-400"></i> Profile
                    </a>
                </div>

                <div class="border-t border-white/10 py-2">
                    <a href="<?= base_url('logout') ?>" class="flex items-center gap-3 px-4 py-3 hover:bg-red-500/10 transition text-sm text-red-400 group">
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
</script>