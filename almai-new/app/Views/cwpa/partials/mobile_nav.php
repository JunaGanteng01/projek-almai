<?php
$notifModel = new \App\Models\NotificationModel();
$currentUserId = session()->get('userId');
$unreadCount = $notifModel->getUnreadCount($currentUserId);
?>

<!-- Mobile Bottom Navigation -->
<nav class="md:hidden fixed bottom-0 left-0 right-0 z-[100] bg-black/95 backdrop-blur-xl border-t border-white/10 shadow-2xl pb-safe">
    <div class="flex items-end justify-between px-2 pb-2 pt-3">

        <!-- Home -->
        <a href="<?= base_url('cwpa/dashboard') ?>" class="flex flex-col items-center gap-1 flex-1 group <?= (current_url() == base_url('cwpa/dashboard')) ? 'text-accent' : 'text-gray-400' ?>">
            <i class="fa-solid fa-house text-lg group-hover:scale-110 transition-transform duration-200"></i>
            <span class="text-[10px] font-medium text-center">Dashboard</span>
        </a>

        <!-- Layanan -->
        <a href="<?= base_url('cwpa/dashboard/portofolio') ?>" class="flex flex-col items-center gap-1 flex-1 group <?= (current_url() == base_url('cwpa/dashboard/portofolio')) ? 'text-accent' : 'text-gray-400' ?>">
            <i class="fa-solid fa-satellite-dish text-lg group-hover:scale-110 transition-transform duration-200"></i>
            <span class="text-[10px] font-medium text-center">Portofolio</span>
        </a>

        <!-- Poin (Middle Floating) -->
        <div class="relative flex flex-col items-center flex-1 -mt-8 pointer-events-none">
            <a href="<?= base_url('cwpa/dashboard/poin') ?>" class="pointer-events-auto absolute -top-8 flex flex-col items-center">
                <div class="w-14 h-14 rounded-full bg-[#1a1a1a] border-[4px] border-[#0a0a0a] flex items-center justify-center mb-1 hover:scale-105 transition-transform duration-200">
                    <img src="<?= base_url('images/almai.gif') ?>" alt="Poin" class="w-8 h-8 object-contain">
                </div>
                <span class="text-[10px] font-medium <?= (current_url() == base_url('cwpa/dashboard/poin')) ? 'text-accent' : 'text-white' ?>">Poin</span>
            </a>
            <div class="h-10 w-full"></div>
        </div>

        <!-- Advokasi -->
        <a href="<?= base_url('cwpa/dashboard/advokasi') ?>" class="flex flex-col items-center gap-1 flex-1 group <?= (current_url() == base_url('cwpa/dashboard/advokasi')) ? 'text-accent' : 'text-gray-400' ?>">
            <i class="fa-solid fa-shield-halved text-lg group-hover:scale-110 transition-transform duration-200"></i>
            <span class="text-[10px] font-medium text-center">Advokasi</span>
        </a>

        <!-- WPA -->
        <a href="<?= base_url('cwpa/dashboard/daftar-wpa') ?>" class="flex flex-col items-center gap-1 flex-1 group <?= (current_url() == base_url('cwpa/dashboard/daftar-wpa')) ? 'text-accent' : 'text-gray-400' ?>">
            <i class="fa-solid fa-bag-shopping text-lg group-hover:scale-110 transition-transform duration-200"></i>
            <span class="text-[10px] font-medium text-center">WPA</span>
        </a>

    </div>
</nav>
