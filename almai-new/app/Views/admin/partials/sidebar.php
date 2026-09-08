<!-- Sidebar Overlay -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden" onclick="toggleSidebar()"></div>

<?php
$currentLevelId = session()->get('level_id');
$isAdmin = $currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN; // Level 5
$isSuperAdmin = $currentLevelId == \App\Models\LevelModel::LEVEL_SUPER_ADMIN; // Level 7
$isAccounting = $currentLevelId == \App\Models\LevelModel::LEVEL_ACCOUNTING; // Level 6
?>

<!-- Sidebar -->
<aside id="sidebar" class="w-64 bg-[#0a0a0a] border-r border-white/10 fixed h-full z-50 -translate-x-full md:translate-x-0 transition-transform top-0 left-0 flex flex-col">
    <div class="p-6 border-b border-white/10 flex-shrink-0">
        <a href="<?= base_url('/') ?>" class="flex items-center gap-2">
            <img src="https://almai.id/images/alma.gif" alt="ALMAI" class="h-8">
            <span class="font-bold text-lg">ALMAI</span>
        </a>
        <p class="text-xs text-gray-500 mt-1">
            <?php if ($isSuperAdmin): ?>Super Admin Dashboard
            <?php elseif ($isAccounting): ?>Accounting Dashboard
            <?php else: ?>Admin Dashboard<?php endif; ?>
        </p>
    </div>
    <nav class="p-4 flex-1 overflow-y-auto">
        <div class="space-y-6">
            <?php if ($isAccounting): ?>
                <!-- Ringkasan -->
                <div class="mb-4">
                    <ul class="space-y-1">
                        <li><a href="<?= base_url('keuangan') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'dashboard' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-chart-line w-4 text-center"></i> Dashboard</a></li>
                        <li><a href="<?= base_url('keuangan/xendit') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'xendit' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fab fa-stripe-s w-4 text-center text-blue-400"></i> Xendit Dashboard</a></li>
                    </ul>
                </div>

                <div class="mb-4">
                    <p class="px-4 text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Transaksi & Operasional</p>
                    <ul class="space-y-1">
                        <li><a href="<?= base_url('keuangan/penjualan') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'penjualan' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-receipt w-4 text-center"></i> Penjualan</a></li>
                        <li><a href="<?= base_url('keuangan/invoices') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'invoices' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-file-invoice w-4 text-center text-blue-400"></i> Invoice</a></li>
                        <li><a href="<?= base_url('keuangan/pengeluaran') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'pengeluaran' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-shopping-cart w-4 text-center text-red-400"></i> Pengeluaran</a></li>
                        <li><a href="<?= base_url('keuangan/kas-masuk-keluar') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'kas_masuk_keluar' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-exchange-alt w-4 text-center text-green-400"></i> Kas Masuk / Keluar</a></li>
                        <li><a href="<?= base_url('keuangan/withdrawals') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'withdrawals' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-money-bill-transfer w-4 text-center text-accent"></i> Withdraw</a></li>
                    </ul>
                </div>

                <div class="mb-4">
                    <p class="px-4 text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Manajemen Kas & Aset</p>
                    <ul class="space-y-1">
                        <li><a href="<?= base_url('keuangan/kas-bank') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'kas_bank' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-university w-4 text-center text-blue-300"></i> Kas & Bank</a></li>
                        <li><a href="<?= base_url('keuangan/aset') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'aset' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-building w-4 text-center"></i> Aset Tetap</a></li>
                    </ul>
                </div>

                <div class="mb-4">
                    <p class="px-4 text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Buku Besar & Laporan</p>
                    <ul class="space-y-1">
                        <li><a href="<?= base_url('keuangan/akun') ?>" class="sidebar-link <?= url_is('keuangan/akun*') ? 'active' : (($activeMenu ?? '') === 'akun' ? 'active' : '') ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-list-ol w-4 text-center"></i> Daftar Akun</a></li>
                        <li><a href="<?= base_url('keuangan/jurnal-umum') ?>" class="sidebar-link <?= url_is('keuangan/jurnal-umum*') ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-book-open w-4 text-center"></i> Jurnal Umum</a></li>
                        <li><a href="<?= base_url('keuangan/laba-rugi') ?>" class="sidebar-link <?= url_is('keuangan/laba-rugi*') ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-file-invoice-dollar w-4 text-center"></i> Laba Rugi</a></li>
                        <li><a href="<?= base_url('keuangan/neraca') ?>" class="sidebar-link <?= url_is('keuangan/neraca*') ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-balance-scale w-4 text-center"></i> Neraca</a></li>
                        <li><a href="<?= base_url('keuangan/arus-kas') ?>" class="sidebar-link <?= url_is('keuangan/arus-kas*') ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-money-bill-transfer w-4 text-center"></i> Arus Kas</a></li>
                        <li><a href="<?= base_url('keuangan/perubahan-modal') ?>" class="sidebar-link <?= url_is('keuangan/perubahan-modal*') ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-chart-pie w-4 text-center"></i> Perubahan Modal</a></li>
                        <li><a href="<?= base_url('keuangan/calk') ?>" class="sidebar-link <?= url_is('keuangan/calk*') ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-clipboard-list w-4 text-center"></i> CALK</a></li>
                        <li><a href="<?= base_url('keuangan/sak-etap') ?>" class="sidebar-link <?= url_is('keuangan/sak-etap*') ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition text-accent"><i class="fas fa-book w-4 text-center"></i> Laporan SAK ETAP</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <!-- Menu Utama -->
                <div>
                    <p class="px-4 text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Menu Utama</p>
                    <ul class="space-y-1">
                        <li><a href="<?= base_url('admin/dashboard') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'dashboard' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-chart-pie w-4 text-center"></i> Dashboard</a></li>
                        <li><a href="<?= base_url('admin/users') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'users' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-users w-4 text-center"></i> Kelola User</a></li>
                        <li><a href="<?= base_url('admin/advokasi') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'advokasi' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-shield-halved w-4 text-center text-accent"></i> Kelola Advokasi</a></li>
                        <li><a href="<?= base_url('admin/faq') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'faq' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-question-circle w-4 text-center"></i> Kelola FAQ</a></li>
                        <li><a href="<?= base_url('admin/handbook') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'handbook' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-book-open w-4 text-center text-accent"></i> Handbook</a></li>
                    </ul>
                </div>

                <!-- Layanan -->
                <div>
                    <p class="px-4 text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Layanan</p>
                    <ul class="space-y-1">
                        <li><a href="<?= base_url('admin/layanan') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'layanan' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-concierge-bell w-4 text-center"></i> Layanan</a></li>
                        <li><a href="<?= base_url('admin/absensi') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'absensi' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-qrcode w-4 text-center text-accent"></i> Absensi Kegiatan</a></li>
                        <li><a href="<?= base_url('admin/transaksi') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'transaksi' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-receipt w-4 text-center"></i> Transaksi</a></li>
                        <li><a href="<?= base_url('admin/voucher') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'voucher' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-ticket-alt w-4 text-center text-accent"></i> Voucher</a></li>
                        <li><a href="<?= base_url('admin/crm') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'crm' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-headset w-4 text-center text-accent"></i> CRM Chat</a></li>
                    </ul>
                </div>

                <!-- Kemitraan -->
                <div>
                    <p class="px-4 text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Kemitraan</p>
                    <ul class="space-y-1">
                        <li><a href="<?= base_url('admin/wpa') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'wpa' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-user-tie w-4 text-center"></i> WPA</a></li>
                        <li><a href="<?= base_url('admin/cwpa') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'cwpa' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-user-graduate w-4 text-center"></i> CWPA</a></li>
                        <li><a href="<?= base_url('admin/cwpa/verification') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'cwpa_verification' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-check-double w-4 text-center"></i> Verifikasi CWPA</a></li>
                    </ul>
                </div>

                <!-- Poin & Profil -->
                <div>
                    <ul class="space-y-1">
                        <li><a href="<?= base_url('admin/poin') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'poin' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-coins w-4 text-center"></i>Almai Poin</a></li>
                      
                        <li><a href="<?= base_url('admin/profile') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'profile' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition"><i class="fas fa-user-cog w-4 text-center"></i> Profil</a></li>
                        <li><a href="<?= base_url('admin/logout') ?>" class="sidebar-link flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-red-500/10 text-red-400 transition"><i class="fas fa-sign-out-alt w-4 text-center"></i> Logout</a></li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </nav>

</aside>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('-translate-x-full');
        document.getElementById('sidebarOverlay').classList.toggle('hidden');
    }
</script>
