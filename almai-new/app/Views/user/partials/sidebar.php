<?php
$notifModel = new \App\Models\NotificationModel();
$userModel = new \App\Models\UserModel();
$currentUserId = session()->get('userId');
$unreadCount = $notifModel->getUnreadCount($currentUserId);
$currentUser = $userModel->find($currentUserId);
$avatarUrl = $currentUser['avatar'] ?? null;
$hasAvatar = $avatarUrl && file_exists(WRITEPATH . $avatarUrl);
$poinModel = new \App\Models\PoinModel();
$userBalance = $poinModel->getUserBalance($currentUserId);
?>
<!-- Mobile Sidebar Overlay -->
<div id="mobileSidebarOverlay" class="md:hidden fixed inset-0 bg-black/70 backdrop-blur-sm z-[70] hidden" onclick="closeMobileSidebar()"></div>

<!-- Mobile Header -->
<div class="md:hidden fixed top-0 left-0 right-0 z-[60] bg-[#0a0a0a]/95 backdrop-blur-lg border-b border-white/10 px-4 py-3 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <!-- Hamburger -->
        <button onclick="toggleMobileSidebar()" class="p-2 hover:bg-white/10 rounded-lg transition -ml-1" aria-label="Menu">
            <i class="fas fa-bars text-white"></i>
        </button>
        <a href="<?= base_url() ?>" class="flex items-center gap-2">
            <img src="https://almai.id/images/alma.gif" alt="ALMAI" class="h-6">
            <span class="font-bold whitespace-nowrap overflow-hidden text-ellipsis max-w-[120px]">ALMAI</span>
        </a>
    </div>
    <div class="flex items-center gap-2">
        <a href="<?= base_url('user/notifications') ?>" class="relative p-2 hover:bg-white/10 rounded-lg transition">
            <i class="fas fa-bell"></i>
            <?php if ($unreadCount > 0): ?>
                <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-[10px] rounded-full flex items-center justify-center"><?= $unreadCount > 9 ? '9+' : $unreadCount ?></span>
            <?php endif; ?>
        </a>
        <div class="relative">
            <button onclick="toggleMobileProfileMenu()" class="w-8 h-8 rounded-full overflow-hidden">
                <?php if ($hasAvatar): ?>
                    <img src="<?= base_url('file/' . $avatarUrl) ?>" alt="Avatar" class="w-full h-full object-cover">
                <?php else: ?>
                    <div class="w-full h-full bg-accent/20 flex items-center justify-center">
                        <i class="fas fa-user text-accent text-sm"></i>
                    </div>
                <?php endif; ?>
            </button>
            <div id="mobileProfileMenu" class="hidden absolute right-0 top-full mt-2 w-56 bg-[#111] border border-white/10 rounded-xl shadow-xl overflow-hidden z-50">
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
</div>

<!-- Sidebar (Desktop: fixed | Mobile: drawer) -->
<aside id="mainSidebar" class="w-64 bg-[#0a0a0a] border-r border-white/10 fixed h-full z-[75] top-0 left-0 flex flex-col
    -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out">
    <div class="p-6 border-b border-white/10 flex-shrink-0 flex items-center justify-between">
        <a href="<?= base_url() ?>" class="flex items-center gap-2">
            <img src="https://almai.id/images/alma.gif" alt="ALMAI" class="h-8">
            <span class="font-bold text-lg">ALMAI</span>
        </a>
        <button onclick="closeMobileSidebar()" class="md:hidden p-1.5 hover:bg-white/10 rounded-lg transition text-gray-400 hover:text-white">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <nav class="p-4 flex-1 overflow-y-auto space-y-6">

<!-- UTAMA -->
<div>
  <p class="text-[10px] text-gray-500 uppercase tracking-[2px] mb-3 px-4 font-bold">UTAMA</p>
  <ul class="space-y-1">
    <li>
      <a href="<?= base_url('user/dashboard') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'dashboard' ? 'active' : '' ?> flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/5 transition group">
        <i class="fas fa-home w-5 text-center group-hover:text-accent transition"></i>
        <span class="text-sm font-medium">Dashboard</span>
      </a>
    </li>
    <li>
      <a href="<?= base_url('user/advokasi') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'advokasi' ? 'active' : '' ?> flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/5 transition group">
        <i class="fas fa-shield-halved w-5 text-center group-hover:text-accent transition"></i>
        <span class="text-sm font-medium">Advokasi</span>
      </a>
    </li>
    <li>
<li><a href="<?= base_url('user/dashboard/rwa') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'aiwe' ? 'active' : '' ?> flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/5 transition group"><i class="fas fa-chart-line w-5 text-center text-accent group-hover:scale-110 transition"></i><span class="text-sm font-medium">AIWE</span><span class="ml-auto text-[8px] bg-accent/20 text-accent px-1.5 py-0.5 rounded-full font-black uppercase tracking-widest border border-accent/20">RWA</span></a></li>

    <li>
      <a href="<?= base_url('user/dashboard/daftar-wpa') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'daftar-wpa' ? 'active' : '' ?> flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/5 transition group">
        <i class="fas fa-id-badge w-5 text-center group-hover:text-accent transition"></i>
        <span class="text-sm font-medium">WPA</span>
      </a>
    </li>
    <li>
      <a href="<?= base_url('user/dashboard/portofolio') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'portofolio' ? 'active' : '' ?> flex items-center justify-between px-4 py-3 rounded-lg hover:bg-white/5 transition group">
        <span class="flex items-center gap-3">
          <i class="fas fa-satellite-dish w-5 text-center group-hover:text-accent transition"></i>
          <span class="text-sm font-medium">Portofolio</span>
        </span>
        <?php $isPro = ($currentUser['is_pro'] ?? false) || (isset($currentUser['level_id']) && $currentUser['level_id'] >= \App\Models\LevelModel::LEVEL_PRO);
        if (!$isPro): ?>
        <span class="text-[8px] bg-accent/20 text-accent px-1.5 py-0.5 rounded-full font-black uppercase tracking-widest border border-accent/20">PRO</span>
        <?php endif; ?>
      </a>
    </li>
  </ul>
</div>


        <!-- LAYANAN & AKTIFITAS -->
        <div>
            <p class="text-[10px] text-gray-500 uppercase tracking-[2px] mb-3 px-4 font-bold">LAYANAN & AKTIFITAS</p>
            <ul class="space-y-1">
                <li>
                    <a href="<?= base_url('user/layanan-saya') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'layanan_saya' ? 'active' : '' ?> flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/5 transition group">
                        <i class="fas fa-graduation-cap w-5 text-center group-hover:text-accent transition"></i> 
                        <span class="text-sm font-medium">Layanan Saya</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('user/transaksi') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'transaksi' ? 'active' : '' ?> flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/5 transition group">
                        <i class="fas fa-receipt w-5 text-center group-hover:text-accent transition"></i> 
                        <span class="text-sm font-medium">Transaksi</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('user/sertifikat') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'sertifikat' ? 'active' : '' ?> flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/5 transition group">
                        <i class="fas fa-certificate w-5 text-center group-hover:text-accent transition"></i> 
                        <span class="text-sm font-medium">Sertifikat</span>
                    </a>
                </li>
                <?php if (isset($currentUser['level_id']) && in_array($currentUser['level_id'], [\App\Models\LevelModel::LEVEL_USER, \App\Models\LevelModel::LEVEL_PRO, \App\Models\LevelModel::LEVEL_CWPA])): ?>
                    <li>
                        <a href="<?= base_url('user/user') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'user' ? 'active' : '' ?> flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/5 transition group">
                            <i class="fas fa-users w-5 text-center group-hover:text-accent transition"></i> 
                            <span class="text-sm font-medium">User</span>
                        </a>
                    </li>
                <?php endif; ?>
                <li>
                    <a href="<?= base_url('user/poin') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'poin' ? 'active' : '' ?> flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/5 transition group">
                        <i class="fas fa-coins w-5 text-center text-yellow-500 group-hover:scale-110 transition"></i> 
                        <span class="text-sm font-medium">Almai Poin</span>
                    </a>
                </li>
            </ul>
        </div>

   
        <!-- AKUN -->
        <div>
            <p class="text-[10px] text-gray-500 uppercase tracking-[2px] mb-3 px-4 font-bold">AKUN</p>
            <ul class="space-y-1">
                <li>
                    <a href="<?= base_url('user/profile') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'profile' ? 'active' : '' ?> flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/5 transition group">
                        <i class="fas fa-user w-5 text-center group-hover:text-accent transition"></i> 
                        <span class="text-sm font-medium">Profil Saya</span>
                    </a>
                </li>
                                <li>
                    <a href="<?= base_url('user/handbook') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'handbook' ? 'active' : '' ?> flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/5 transition group">
                        <i class="fas fa-book-open w-5 text-center group-hover:text-accent transition"></i> 
                        <span class="text-sm font-medium">Handbook</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('logout') ?>" class="flex items-center gap-3 px-4 py-3 text-red-400 hover:bg-red-500/10 rounded-lg transition group">
                        <i class="fas fa-sign-out-alt w-5 text-center group-hover:scale-110 transition"></i> 
                        <span class="text-sm font-medium">logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</aside>

<script>
    function toggleMobileSidebar() {
        const sidebar = document.getElementById('mainSidebar');
        const overlay = document.getElementById('mobileSidebarOverlay');
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
        document.body.classList.toggle('overflow-hidden');
    }

    function closeMobileSidebar() {
        const sidebar = document.getElementById('mainSidebar');
        const overlay = document.getElementById('mobileSidebarOverlay');
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }
</script>