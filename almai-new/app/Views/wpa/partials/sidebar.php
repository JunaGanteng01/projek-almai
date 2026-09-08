
<!-- Sidebar Overlay -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden" onclick="toggleSidebar()"></div>

<!-- Sidebar (Desktop) -->
<aside id="sidebar" class="w-64 bg-[#0a0a0a] border-r border-white/10 fixed h-full z-50 -translate-x-full md:translate-x-0 transition-transform top-0 left-0 flex flex-col">
    <div class="p-6 border-b border-white/10 flex-shrink-0">
        <a href="<?= base_url('/') ?>" class="flex items-center gap-2">
            <img src="https://almai.id/images/alma.gif" alt="ALMAI" class="h-8">
            <span class="font-bold text-lg">ALMAI</span>
        </a>
        <p class="text-xs text-gray-500 mt-1">WPA Dashboard</p>
    </div>

    <nav class="p-4 flex-1 overflow-y-auto space-y-6" style="max-height: calc(100vh - 140px);">
        <!-- UTAMA -->
        <div>
            <p class="text-[10px] text-gray-500 uppercase tracking-[2px] mb-3 px-4 font-bold">UTAMA</p>
            <ul class="space-y-1">
                <li>
                    <a href="<?= base_url('wpa/dashboard') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'dashboard' ? 'active' : '' ?> flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/5 transition group">
                        <i class="fas fa-chart-pie w-5 text-center group-hover:text-accent transition"></i> 
                        <span class="text-sm font-medium">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('wpa/dashboard/advokasi') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'advokasi' ? 'active' : '' ?> flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/5 transition group">
                        <i class="fas fa-shield-halved w-5 text-center group-hover:text-accent transition"></i> 
                        <span class="text-sm font-medium">Advokasi</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('wpa/dashboard/daftar-wpa') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'daftar-wpa' ? 'active' : '' ?> flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/5 transition group">
                        <i class="fas fa-id-badge w-5 text-center group-hover:text-accent transition"></i> 
                        <span class="text-sm font-medium">WPA</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('wpa/dashboard/portofolio') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'portofolio' ? 'active' : '' ?> flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/5 transition group">
                        <i class="fas fa-satellite-dish w-5 text-center group-hover:text-accent transition"></i> 
                        <span class="text-sm font-medium">Portofolio</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- LAYANAN -->
        <div>
            <p class="text-[10px] text-gray-500 uppercase tracking-[2px] mb-3 px-4 font-bold">LAYANAN</p>
            <ul class="space-y-1">
                <li>
                    <a href="<?= base_url('wpa/dashboard/layanan') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'layanan' ? 'active' : '' ?> flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/5 transition group">
                        <i class="fas fa-plus-circle w-5 text-center group-hover:text-accent transition"></i> 
                        <span class="text-sm font-medium">Buat Layanan</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('wpa/dashboard/layanan-saya') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'layanan-saya' ? 'active' : '' ?> flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/5 transition group">
                        <i class="fas fa-shopping-bag w-5 text-center group-hover:text-accent transition"></i> 
                        <span class="text-sm font-medium">Layanan Saya</span>
                    </a>
                </li>

            </ul>
        </div>

        <!-- MANAJEMEN -->
        <div>
            <p class="text-[10px] text-gray-500 uppercase tracking-[2px] mb-3 px-4 font-bold">MANAJEMEN</p>
            <ul class="space-y-1">
                <li>
                    <a href="<?= base_url('wpa/dashboard/user') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'user' ? 'active' : '' ?> flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/5 transition group">
                        <i class="fas fa-users w-5 text-center group-hover:text-accent transition"></i> 
                        <span class="text-sm font-medium">Daftar User</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('wpa/dashboard/transaksi') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'transaksi' ? 'active' : '' ?> flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/5 transition group">
                        <i class="fas fa-receipt w-5 text-center group-hover:text-accent transition"></i> 
                        <span class="text-sm font-medium">Transaksi</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('wpa/dashboard/absensi') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'absensi' ? 'active' : '' ?> flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/5 transition group">
                        <i class="fas fa-qrcode w-5 text-center text-accent group-hover:scale-110 transition"></i> 
                        <span class="text-sm font-medium">Absensi Kegiatan</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('wpa/dashboard/poin') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'poin' ? 'active' : '' ?> flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/5 transition group">
                        <i class="fas fa-coins w-5 text-center text-yellow-500 group-hover:scale-110 transition"></i> 
                        <span class="text-sm font-medium">Almai Poin</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('wpa/dashboard/chat-leads') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'chat-leads' ? 'active' : '' ?> flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/5 transition group">
                        <i class="fas fa-comments w-5 text-center group-hover:text-accent transition"></i> 
                        <span class="text-sm font-medium">Chat Leads</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- AKUN -->
        <div>
            <p class="text-[10px] text-gray-500 uppercase tracking-[2px] mb-3 px-4 font-bold">AKUN</p>
            <ul class="space-y-1">
                <li>
                    <a href="<?= base_url('wpa/dashboard/profile') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'profile' ? 'active' : '' ?> flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/5 transition group">
                        <i class="fas fa-user-cog w-5 text-center group-hover:text-accent transition"></i> 
                        <span class="text-sm font-medium">Profil Saya</span>
                    </a>
                </li>
                                <li>
                    <a href="<?= base_url('wpa/dashboard/handbook') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'handbook' ? 'active' : '' ?> flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/5 transition group">
                        <i class="fas fa-book-open w-5 text-center group-hover:text-accent transition"></i> 
                        <span class="text-sm font-medium">Handbook</span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('wpa/logout') ?>" class="flex items-center gap-3 px-4 py-3 text-red-400 hover:bg-red-500/10 rounded-lg transition group">
                        <i class="fas fa-sign-out-alt w-5 text-center group-hover:scale-110 transition"></i> 
                        <span class="text-sm font-medium">logout</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</aside>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('-translate-x-full');
        document.getElementById('sidebarOverlay').classList.toggle('hidden');
    }
</script>