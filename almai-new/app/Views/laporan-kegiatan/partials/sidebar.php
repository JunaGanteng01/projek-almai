<!-- Sidebar Overlay -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<aside id="sidebar" class="w-64 bg-[#0a0a0a] border-r border-white/10 fixed h-full z-50 -translate-x-full md:translate-x-0 transition-transform top-0 left-0 flex flex-col">
    <div class="p-6 border-b border-white/10 flex-shrink-0">
        <a href="<?= base_url('/') ?>" class="flex items-center gap-2">
            <img src="https://almai.id/images/alma.gif" alt="ALMAI" class="h-8">
            <span class="font-bold text-lg">ALMAI</span>
        </a>
        <p class="text-xs text-gray-500 mt-1">Laporan Kegiatan Dashboard</p>
    </div>
<?php
$poinModel = new \App\Models\PoinModel();
$adminPoin = $poinModel->getUserBalance(session()->get('userId'));
?>
    <nav class="p-4 flex-1 overflow-y-auto">
        <div class="space-y-6">
            
            <!-- Ringkasan Utama -->
            <div>
                <p class="px-4 text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2"> Ringkasan Utama</p>
                <ul class="space-y-1">
                    <li><a href="<?= base_url('laporan-kegiatan/dashboard') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'dashboard' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition text-gray-300 hover:text-white"><i class="fas fa-chart-pie w-5 text-center text-accent"></i> Dashboard</a></li>
                    
                    <li><a href="<?= base_url('laporan-kegiatan/profile') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'profile' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition text-gray-300 hover:text-white"><i class="fas fa-building w-5 text-center text-blue-400"></i> Profil Perusahaan</a></li>
                    
                    <li><a href="<?= base_url('laporan-kegiatan/data-perusahaan') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'data-perusahaan' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition text-gray-300 hover:text-white"><i class="fas fa-city w-5 text-center text-indigo-400"></i> Data Perusahaan</a></li>
                </ul>
            </div>
            
            <!-- Data & Klien -->
            <div>
                <p class="px-4 text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2"> Data & Klien</p>
                <ul class="space-y-1">
                    <li><a href="<?= base_url('laporan-kegiatan/wpa') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'wpa' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition text-gray-300 hover:text-white"><i class="fas fa-user-tie w-5 text-center text-purple-400"></i> Data WPA</a></li>
                    <li><a href="<?= base_url('laporan-kegiatan/cwpa') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'cwpa' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition text-gray-300 hover:text-white"><i class="fas fa-user-graduate w-5 text-center text-pink-400"></i> Data CWPA</a></li>
                    
                    <li><a href="<?= base_url('laporan-kegiatan/klien') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'users' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition text-gray-300 hover:text-white"><i class="fas fa-users w-5 text-center text-cyan-300"></i> User Aktif</a></li>
                    
                    <li><a href="<?= base_url('laporan-kegiatan/absensi') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'absensi' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition text-gray-300 hover:text-white"><i class="fas fa-qrcode w-5 text-center text-teal-400"></i> Absensi Kegiatan</a></li>
                    
                    <li><a href="<?= base_url('laporan-kegiatan/broadcast') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'broadcast' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition text-gray-300 hover:text-white"><i class="fas fa-bullhorn w-5 text-center text-blue-400"></i> Broadcast</a></li>
                </ul>
            </div>
            
            <!-- Layanan & Produk -->
            <div>
                <p class="px-4 text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2"> Layanan & Produk</p>
                <?php
                    // Mapping properties
                    $modulMap = [
                        'Seminar FGD' => ['route' => 'seminar', 'icon' => 'fas fa-microphone', 'color' => 'text-orange-400', 'active' => 'seminar'],
                        'Pelatihan Simulasi' => ['route' => 'pelatihan', 'icon' => 'fas fa-chalkboard-teacher', 'color' => 'text-yellow-300', 'active' => 'pelatihan'],
                        'Signal' => ['route' => 'signal', 'icon' => 'fas fa-signal', 'color' => 'text-green-400', 'active' => 'signal'],
                        'Konsultasi' => ['route' => 'konsultasi', 'icon' => 'fas fa-comments', 'color' => 'text-pink-400', 'active' => 'konsultasi'],
                        'Expert Advisor' => ['route' => 'expert-advisor', 'icon' => 'fas fa-robot', 'color' => 'text-indigo-400', 'active' => 'expert-advisor'],
                        'Kegiatan Lainnya' => ['route' => 'kegiatan-lainnya', 'icon' => 'fas fa-briefcase', 'color' => 'text-teal-400', 'active' => 'kegiatan-lainnya'],
                    ];
                ?>
                <ul class="space-y-1">
                    <?php foreach ($modulMap as $modulName => $map): ?>
                        <li>
                            <a href="<?= base_url('laporan-kegiatan/' . $map['route']) ?>" class="sidebar-link <?= ($activeMenu ?? '') === $map['active'] ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition text-gray-300 hover:text-white">
                                <i class="<?= $map['icon'] ?> w-5 text-center <?= $map['color'] ?>"></i> <?= $modulName ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <!-- Regulasi & Dokumen -->
            <div>
                <p class="px-4 text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2"> Regulasi & Dokumen</p>
                <ul class="space-y-1">
                    <li><a href="<?= base_url('laporan-kegiatan/pedoman-perilaku') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'pedoman-perilaku' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition text-gray-300 hover:text-white"><i class="fas fa-balance-scale w-5 text-center text-gray-300"></i> Pedoman Perilaku</a></li>
                    
                    <li><a href="<?= base_url('laporan-kegiatan/laporan-regulasi') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'laporan-regulasi' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition text-gray-300 hover:text-white"><i class="fas fa-file-alt w-5 text-center text-gray-300"></i> Laporan Bappebti</a></li>


                    
                    <li><a href="<?= base_url('laporan-kegiatan/laporan-bank-indonesia') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'laporan-bank-indonesia' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition text-gray-300 hover:text-white"><i class="fas fa-university w-5 text-center text-gray-300"></i> Laporan Bank Indonesia</a></li>
                    
                    <li><a href="<?= base_url('laporan-kegiatan/dokumen-arsip') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'dokumen-arsip' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition text-gray-300 hover:text-white"><i class="fas fa-archive w-5 text-center text-gray-300"></i> Dokumen Arsip</a></li>
                </ul>
            </div>
            
            <!-- LOGOUT -->
            <div class="mt-10 border-t border-white/5 pt-4">
                <a href="<?= base_url('admin/logout') ?>" class="flex items-center gap-3 px-4 py-2 text-sm rounded-lg text-red-400 hover:bg-red-500/10 transition">
                    <i class="fas fa-sign-out-alt w-4 text-center"></i> Keluar
                </a>
            </div>
        </div>
    </nav>


</aside>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('-translate-x-full');
        document.getElementById('sidebarOverlay').classList.toggle('hidden');
    }
</script>
