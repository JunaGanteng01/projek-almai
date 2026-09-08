<!-- Sidebar Overlay -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-40 hidden md:hidden transition-opacity" onclick="toggleSidebar()"></div>

<?php
$currentLevelId = session()->get('level_id');
$isAdmin = $currentLevelId == \App\Models\LevelModel::LEVEL_ADMIN; // Level 5
$isSuperAdmin = $currentLevelId == \App\Models\LevelModel::LEVEL_SUPER_ADMIN; // Level 7
$isAccounting = $currentLevelId == \App\Models\LevelModel::LEVEL_ACCOUNTING; // Level 6
?>

<!-- Sidebar -->
<aside id="sidebar" class="w-64 bg-[#080808] border-r border-white/[0.08] fixed h-full z-50 -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out top-0 left-0 flex flex-col shadow-2xl">
    <!-- Brand Header -->
    <div class="px-5 py-4 border-b border-white/[0.08] flex-shrink-0 flex items-center justify-between bg-black/40">
        <a href="<?= base_url('/') ?>" class="flex items-center gap-2.5 group">
            <div class="relative flex items-center justify-center">
                <img src="<?= base_url('images/alma.gif') ?>" onerror="this.src='https://almai.id/images/alma.gif'" alt="ALMAI" class="h-8 w-auto">
                <span class="absolute -top-0.5 -right-0.5 w-2 h-2 bg-accent rounded-full animate-pulse"></span>
            </div>
            <div>
                <span class="font-extrabold text-base tracking-tight text-white group-hover:text-accent transition">ALMAI</span>
                <span class="block text-[10px] font-medium text-gray-400">
                    <?php if ($isSuperAdmin): ?>Super Admin
                    <?php elseif ($isAccounting): ?>Accounting
                    <?php else: ?>Admin Panel<?php endif; ?>
                </span>
            </div>
        </a>
        <button type="button" onclick="toggleSidebar()" class="md:hidden text-gray-400 hover:text-white p-1 rounded-lg">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Navigation List -->
    <nav class="p-3 flex-1 overflow-y-auto space-y-4 custom-scrollbar pb-24">
        <!-- Menu Utama -->
        <div>
            <p class="px-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Menu Utama</p>
            <ul class="space-y-0.5 text-xs font-medium">
                <li><a href="<?= base_url('superadmin/dashboard') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'dashboard' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fas fa-chart-pie w-4 text-center text-sm"></i> <span>Dashboard</span></a></li>
                <li><a href="<?= base_url('superadmin/users') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'users' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fas fa-users w-4 text-center text-sm"></i> <span>Kelola User</span></a></li>
                <li><a href="<?= base_url('superadmin/bot-users') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'bot_users' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fas fa-robot w-4 text-center text-sm text-accent"></i> <span>RWA BOT</span></a></li>
                <li><a href="<?= base_url('superadmin/advokasi') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'advokasi' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fas fa-shield-halved w-4 text-center text-sm text-accent"></i> <span>Kelola Advokasi</span></a></li>
                <li><a href="<?= base_url('superadmin/faq') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'faq' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fas fa-question-circle w-4 text-center text-sm"></i> <span>Kelola FAQ</span></a></li>
                <li><a href="<?= base_url('superadmin/handbook') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'handbook' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fas fa-book-open w-4 text-center text-sm text-accent"></i> <span>Handbook</span></a></li>
            </ul>
        </div>

        <!-- Layanan & Keuangan -->
        <div>
            <p class="px-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Layanan & Keuangan</p>
            <ul class="space-y-0.5 text-xs font-medium">
                <li><a href="<?= base_url('superadmin/layanan') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'layanan' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fas fa-concierge-bell w-4 text-center text-sm"></i> <span>Layanan</span></a></li>
                <li><a href="<?= base_url('superadmin/event-banner') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'event_banner' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fas fa-images w-4 text-center text-sm"></i> <span>Banner Event</span></a></li>
                <li><a href="<?= base_url('superadmin/absensi') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'absensi' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fas fa-qrcode w-4 text-center text-sm text-accent"></i> <span>Absensi Kegiatan</span></a></li>
                <li><a href="<?= base_url('superadmin/transaksi') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'transaksi' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fas fa-receipt w-4 text-center text-sm"></i> <span>Transaksi</span></a></li>
                <li><a href="<?= base_url('superadmin/referral') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'referral' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fas fa-share-alt w-4 text-center text-sm"></i> <span>Referral</span></a></li>
                <li><a href="<?= base_url('superadmin/merchandise') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'merchandise' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fas fa-store w-4 text-center text-sm text-blue-400"></i> <span>Merchandise</span></a></li>
                <li><a href="<?= base_url('superadmin/voucher') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'voucher' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fas fa-ticket-alt w-4 text-center text-sm text-accent"></i> <span>Voucher</span></a></li>
                <li><a href="<?= base_url('superadmin/chat-leads') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'chat_leads' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fas fa-comments w-4 text-center text-sm"></i> <span>Chat Leads</span></a></li>
                <li><a href="<?= base_url('superadmin/crm') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'crm' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fas fa-headset w-4 text-center text-sm text-accent"></i> <span>CRM Chat</span></a></li>
            </ul>
        </div>

        <!-- Almai Poin -->
        <div>
            <p class="px-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Almai Poin</p>
            <ul class="space-y-0.5 text-xs font-medium">
                <li><a href="<?= base_url('superadmin/poin') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'poin' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fas fa-coins w-4 text-center text-sm"></i> <span>Poin</span></a></li>
                <li><a href="<?= base_url('superadmin/poin-package') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'poin_package' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fas fa-tag w-4 text-center text-sm text-accent"></i> <span>Harga Poin</span></a></li>
                <li><a href="<?= base_url('superadmin/poin/share') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'poin_share' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fas fa-share-alt w-4 text-center text-sm text-yellow-500"></i> <span>Bagi Poin</span></a></li>
            </ul>
        </div>

        <!-- Kemitraan -->
        <div>
            <p class="px-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Kemitraan</p>
            <ul class="space-y-0.5 text-xs font-medium">
                <li><a href="<?= base_url('superadmin/wpa') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'wpa' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fas fa-user-tie w-4 text-center text-sm"></i> <span>WPA</span></a></li>
                <li><a href="<?= base_url('superadmin/cwpa') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'cwpa' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fas fa-user-graduate w-4 text-center text-sm"></i> <span>CWPA</span></a></li>
                <li><a href="<?= base_url('superadmin/cwpa/verification') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'cwpa_verification' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fas fa-check-double w-4 text-center text-sm"></i> <span>Verifikasi CWPA</span></a></li>
                <li><a href="<?= base_url('superadmin/wpa-assignment') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'wpa_assignment' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fas fa-link w-4 text-center text-sm text-blue-400"></i> <span>Penugasan WPA</span></a></li>
                <li><a href="<?= base_url('superadmin/withdrawals') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'withdrawals' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fas fa-money-bill-wave w-4 text-center text-sm"></i> <span>Withdraw</span></a></li>
            </ul>
        </div>

        <!-- Sistem -->
        <div>
            <p class="px-3 text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1.5">Sistem</p>
            <ul class="space-y-0.5 text-xs font-medium">
                <li><a href="<?= base_url('superadmin/kyc') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'kyc' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fas fa-crown w-4 text-center text-sm text-yellow-500"></i> <span>Verifikasi PRO</span></a></li>
                <li><a href="<?= base_url('superadmin/tim') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'tim' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fas fa-users-cog w-4 text-center text-sm"></i> <span>Kelola Tim</span></a></li>
                <li><a href="<?= base_url('superadmin/legal-documents') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'legal_documents' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fas fa-file-contract w-4 text-center text-sm"></i> <span>Dokumen Legal</span></a></li>
                <li><a href="<?= base_url('superadmin/setting') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'setting' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fas fa-cog w-4 text-center text-sm"></i> <span>Setting</span></a></li>
                <li><a href="<?= base_url('superadmin/broadcast') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'broadcast' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fas fa-bullhorn w-4 text-center text-sm"></i> <span>Broadcast</span></a></li>
                <li><a href="<?= base_url('superadmin/feedback') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'feedback' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fas fa-star w-4 text-center text-sm text-yellow-400"></i> <span>Feedback User</span></a></li>
                <li><a href="<?= base_url('superadmin/whatsapp-gateway') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'whatsapp_gateway' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fab fa-whatsapp w-4 text-center text-sm text-[#25D366]"></i> <span>WhatsApp Gateway</span></a></li>
                <li><a href="<?= base_url('superadmin/audit-log') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'audit_log' ? 'active' : '' ?> flex items-center gap-2.5 px-3 py-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/[0.04] transition"><i class="fas fa-history w-4 text-center text-sm text-accent"></i> <span>Audit Log</span></a></li>
            </ul>
        </div>
    </nav>
</aside>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if (sidebar && overlay) {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    }
</script>
