<!-- Sidebar Overlay -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<aside id="sidebar" class="w-64 bg-[#0a0a0a] border-r border-white/10 fixed h-full z-50 -translate-x-full md:translate-x-0 transition-transform top-0 left-0 flex flex-col">
    <div class="p-6 border-b border-white/10 flex-shrink-0">
        <a href="<?= base_url('/') ?>" class="flex items-center gap-2">
            <img src="https://almai.id/images/alma.gif" alt="ALMAI" class="h-8">
            <span class="font-bold text-lg text-white">ALMAI</span>
        </a>
        <p class="text-xs text-accent mt-1 uppercase font-bold tracking-wider">
            Laporan Keuangan
        </p>
    </div>
    
    <nav class="p-4 flex-1 overflow-y-auto">
        <div class="space-y-6">
            <!-- Dashboard -->
            <div>
                <p class="px-4 text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2"> Ringkasan Utama</p>
                <ul class="space-y-1">
                    <li>
                        <a href="<?= base_url('keuangan') ?>" class="sidebar-link <?= url_is('keuangan') ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg text-gray-300 hover:text-white hover:bg-white/5 transition">
                            <i class="fas fa-home w-4 text-center text-accent"></i> Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('keuangan/xendit') ?>" class="sidebar-link <?= url_is('keuangan/xendit*') ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg text-gray-300 hover:text-white hover:bg-white/5 transition">
                            <i class="fab fa-stripe-s w-4 text-center text-blue-400"></i> Xendit Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('keuangan/audit') ?>" class="sidebar-link <?= url_is('keuangan/audit*') ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg text-gray-300 hover:text-white hover:bg-white/5 transition">
                            <i class="fas fa-search-dollar w-4 text-center text-yellow-400"></i> Audit Keuangan
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Master Data -->
            <div>
                <p class="px-4 text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2"> Master Data</p>
                <ul class="space-y-1">
                    <li>
                        <a href="<?= base_url('keuangan/akun') ?>" class="sidebar-link <?= url_is('keuangan/akun*') ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg text-gray-300 hover:text-white hover:bg-white/5 transition">
                            <i class="fas fa-list-ol w-4 text-center text-purple-400"></i> Akun (COA)
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('keuangan/kontak') ?>" class="sidebar-link <?= url_is('keuangan/kontak*') ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg text-gray-300 hover:text-white hover:bg-white/5 transition">
                            <i class="fas fa-address-book w-4 text-center text-purple-300"></i> Kontak
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('keuangan/aset') ?>" class="sidebar-link <?= url_is('keuangan/aset*') ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg text-gray-300 hover:text-white hover:bg-white/5 transition">
                            <i class="fas fa-building w-4 text-center text-cyan-300"></i> Daftar Aset
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Kas & Bank -->
            <div>
                <p class="px-4 text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2"> Kas & Bank</p>
                <ul class="space-y-1">
                    <li>
                        <a href="<?= base_url('keuangan/kas-bank') ?>" class="sidebar-link <?= url_is('keuangan/kas-bank*') ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg text-gray-300 hover:text-white hover:bg-white/5 transition">
                            <i class="fas fa-university w-4 text-center text-blue-400"></i> Bank
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('keuangan/kas-masuk') ?>" class="sidebar-link <?= url_is('keuangan/kas-masuk*') ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg text-gray-300 hover:text-white hover:bg-white/5 transition">
                            <i class="fas fa-arrow-down w-4 text-center text-green-400"></i> Kas Masuk
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('keuangan/kas-keluar') ?>" class="sidebar-link <?= url_is('keuangan/kas-keluar*') ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg text-gray-300 hover:text-white hover:bg-white/5 transition">
                            <i class="fas fa-arrow-up w-4 text-center text-red-400"></i> Kas Keluar
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('keuangan/rekonsiliasi') ?>" class="sidebar-link <?= url_is('keuangan/rekonsiliasi*') ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg text-gray-300 hover:text-white hover:bg-white/5 transition">
                            <i class="fas fa-check-double w-4 text-center text-green-300"></i> Rekonsiliasi
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Transaksi -->
            <div>
                <p class="px-4 text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2"> Transaksi</p>
                <ul class="space-y-1">
                    <li>
                        <a href="<?= base_url('keuangan/jurnal-umum') ?>" class="sidebar-link <?= url_is('keuangan/jurnal-umum*') ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg text-gray-300 hover:text-white hover:bg-white/5 transition">
                            <i class="fas fa-book-open w-4 text-center text-indigo-400"></i> Jurnal Umum
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('keuangan/invoices') ?>" class="sidebar-link <?= url_is('keuangan/invoices*') ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg text-gray-300 hover:text-white hover:bg-white/5 transition">
                            <i class="fas fa-file-invoice-dollar w-4 text-center text-green-300"></i> Invoice
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Laporan -->
            <div>
                <p class="px-4 text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2"> Laporan</p>
                <ul class="space-y-1">
                    <li>
                        <a href="<?= base_url('keuangan/neraca-saldo') ?>" class="sidebar-link <?= url_is('keuangan/neraca-saldo*') ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg text-gray-300 hover:text-white hover:bg-white/5 transition">
                            <i class="fas fa-balance-scale w-4 text-center text-yellow-300"></i> Neraca Saldo
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('keuangan/laba-rugi') ?>" class="sidebar-link <?= url_is('keuangan/laba-rugi*') ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg text-gray-300 hover:text-white hover:bg-white/5 transition">
                            <i class="fas fa-chart-line w-4 text-center text-green-400"></i> Laba Rugi
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('keuangan/perubahan-modal') ?>" class="sidebar-link <?= url_is('keuangan/perubahan-modal*') ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg text-gray-300 hover:text-white hover:bg-white/5 transition">
                            <i class="fas fa-chart-pie w-4 text-center text-purple-400"></i> Perubahan Ekuitas
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('keuangan/neraca') ?>" class="sidebar-link <?= url_is('keuangan/neraca*') ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg text-gray-300 hover:text-white hover:bg-white/5 transition">
                            <i class="fas fa-landmark w-4 text-center text-blue-400"></i> Neraca (Balance)
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('keuangan/arus-kas') ?>" class="sidebar-link <?= url_is('keuangan/arus-kas*') ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg text-gray-300 hover:text-white hover:bg-white/5 transition">
                            <i class="fas fa-money-bill-transfer w-4 text-center text-amber-400"></i> Arus Kas
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('keuangan/sak-etap') ?>" class="sidebar-link <?= url_is('keuangan/sak-etap*') ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg text-gray-300 hover:text-white hover:bg-white/5 transition">
                            <i class="fas fa-clipboard-list w-4 text-center text-cyan-300"></i> Laporan Keuangan
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('keuangan/audit-parser') ?>" class="sidebar-link <?= url_is('keuangan/audit-parser*') ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg text-gray-300 hover:text-white hover:bg-white/5 transition">
                            <i class="fas fa-robot w-4 text-center text-blue-300"></i> Audit KAP (AI)
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Pengaturan -->
            <div>
                <p class="px-4 text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2"> Pengaturan</p>
                <ul class="space-y-1">
                    <li>
                        <a href="<?= base_url('keuangan/entitas') ?>" class="sidebar-link <?= url_is('keuangan/entitas*') ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg text-gray-300 hover:text-white hover:bg-white/5 transition">
                            <i class="fas fa-building-columns w-4 text-center text-gray-300"></i> Profil Entitas
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('keuangan/periode-akuntansi') ?>" class="sidebar-link <?= url_is('keuangan/periode-akuntansi*') ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg text-gray-300 hover:text-white hover:bg-white/5 transition">
                            <i class="fas fa-lock w-4 text-center text-red-400"></i> Tutup Buku
                        </a>
                    </li>
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
