<aside id="sidebar" class="w-64 bg-[#0a0a0a] border-r border-white/10 fixed h-full z-50 -translate-x-full md:translate-x-0 transition-transform top-0 left-0 flex flex-col">
    <div class="p-6 border-b border-white/10 flex-shrink-0">
        <a href="<?= base_url('admin-wpa/dashboard') ?>" class="flex items-center gap-2">
            <img src="https://almai.id/images/alma.gif" alt="ALMAI" class="h-8">
            <span class="font-bold text-lg">ALMAI</span>
        </a>
        <p class="text-xs text-accent mt-1 uppercase font-bold tracking-wider">
            Admin WPA
        </p>
    </div>
    <nav class="p-4 flex-1 overflow-y-auto">
        <div class="space-y-6">
            <div>
                <p class="px-4 text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Main Menu</p>
                <ul class="space-y-1">
                    <li><a href="<?= base_url('admin-wpa/dashboard') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'admin_wpa' || ($activeMenu ?? '') === 'dashboard' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-chart-pie w-4 text-center"></i> Dashboard</a></li>

                    <li><a href="<?= base_url('admin-wpa/users') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'users' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-users w-4 text-center"></i> User</a></li>
                </ul>
            </div>

            <div>
                <p class="px-4 text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Layanan & Transaksi</p>
                <ul class="space-y-1">
                    <li><a href="<?= base_url('admin-wpa/layanan') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'layanan' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-concierge-bell w-4 text-center"></i> Layanan</a></li>
                    <li><a href="<?= base_url('admin-wpa/absensi') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'absensi' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-calendar-check w-4 text-center"></i> Absensi</a></li>
                    <li><a href="<?= base_url('admin-wpa/transaksi') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'transaksi' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-receipt w-4 text-center"></i> Transaksi</a></li>
                </ul>
            </div>
            
            <div class="mt-4">
                <p class="px-4 text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Pengaturan</p>
                <ul class="space-y-1">
                    <li><a href="<?= base_url('admin-wpa/profile') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'profile' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-user-cog w-4 text-center"></i> Profile</a></li>
                </ul>
            </div>
            
            <div class="mt-10 border-t border-white/5 pt-4">
                <a href="<?= base_url('admin-wpa/logout') ?>" class="flex items-center gap-3 px-4 py-2 text-sm rounded-lg text-red-400 hover:bg-red-500/10 transition">
                    <i class="fas fa-sign-out-alt w-4 text-center"></i> Keluar
                </a>
            </div>
        </div>
    </nav>
</aside>
