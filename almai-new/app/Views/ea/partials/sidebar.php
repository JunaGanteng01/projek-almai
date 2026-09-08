<!-- Sidebar Overlay -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<aside id="sidebar" class="w-64 bg-[#0a0a0a] border-r border-white/10 fixed h-full z-50 -translate-x-full md:translate-x-0 transition-transform top-0 left-0 flex flex-col">
    <div class="p-6 border-b border-white/10 flex-shrink-0">
        <a href="<?= base_url('/') ?>" class="flex items-center gap-2">
            <img src="https://almai.id/images/alma.gif" alt="ALMAI" class="h-8">
            <span class="font-bold text-lg">ALMAI</span>
        </a>
        <p class="text-xs text-gray-500 mt-1">
            CEO Executive Suite
        </p>
    </div>
    <nav class="p-4 flex-1 overflow-y-auto">
        <div class="space-y-6">
            
            <!-- Menu Utama -->
            <div>
                <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Menu Utama</p>
                <div class="space-y-1">
                    <a href="<?= base_url('ceo/dashboard') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'dashboard' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition">
                        <i class="fas fa-home w-4 text-center text-accent"></i> Dashboard
                    </a>
                    <a href="<?= base_url('ceo/briefing') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'briefing' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition">
                        <i class="fas fa-newspaper w-4 text-center text-blue-400"></i> CEO Briefing
                    </a>
                    <a href="<?= base_url('ceo/calendar') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'calenders' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition">
                        <i class="fas fa-calendar-alt w-4 text-center text-yellow-400"></i> Kalender
                    </a>
                    <a href="<?= base_url('ceo/tasks') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'tasks' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition">
                        <i class="fas fa-tasks w-4 text-center text-emerald-400"></i> Tugas
                    </a>
                    <a href="<?= base_url('ceo/dashboard#approvals') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'approval-waiting' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition">
                        <i class="fas fa-clipboard-check w-4 text-center text-emerald-400"></i> Approval
                    </a>
                    <a href="<?= base_url('ceo/reminders') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'reminders' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition">
                        <i class="fas fa-bell w-4 text-center text-yellow-500"></i> Reminder
                    </a>
                    <a href="<?= base_url('ceo/meetings') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'meetings' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition">
                        <i class="fas fa-video w-4 text-center text-blue-500"></i> Meeting
                    </a>
                    <a href="<?= base_url('ceo/ai-summary') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'ai-summary' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition">
                        <i class="fas fa-robot w-4 text-center text-purple-400"></i> AI Summary
                    </a>
                    <a href="<?= base_url('ceo/notifications') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'notifications' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition">
                        <i class="fas fa-bullhorn w-4 text-center text-red-400"></i> Notification
                    </a>
                </div>
            </div>

            <!-- Executive drill-down -->
            <div>
                <p class="px-4 text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Insight Eksekutif</p>
                <div class="space-y-1">
                    <a href="<?= base_url('ceo/dashboard#performance') ?>" class="sidebar-link flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition">
                        <i class="fas fa-chart-line w-4 text-center text-emerald-500"></i> Kinerja
                    </a>
                    <a href="<?= base_url('ceo/dashboard#finance') ?>" class="sidebar-link flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition">
                        <i class="fas fa-file-invoice-dollar w-4 text-center text-yellow-500"></i> Kas, Invoice & Tagihan
                    </a>
                    <a href="<?= base_url('ceo/dashboard#crm') ?>" class="sidebar-link flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition">
                        <i class="fas fa-comments w-4 text-center text-blue-500"></i> CRM Snapshot
                    </a>
                    <a href="<?= base_url('ceo/dashboard#goals') ?>" class="sidebar-link flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition">
                        <i class="fas fa-bullseye w-4 text-center text-purple-400"></i> OKR & Sasaran
                    </a>
                    <a href="<?= base_url('ceo/activity') ?>" class="sidebar-link <?= ($activeMenu ?? '') === 'activity' ? 'active' : '' ?> flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-white/5 transition">
                        <i class="fas fa-clock-rotate-left w-4 text-center text-orange-400"></i> Audit Trail
                    </a>
                </div>
            </div>

        </div>
        
        <!-- Divider & Logout -->
        <div class="mt-6 pt-4 border-t border-white/10">
            <a href="<?= base_url('ceo/logout') ?>" class="sidebar-link flex items-center gap-3 px-4 py-2 text-sm rounded-lg hover:bg-red-500/10 transition text-red-400">
                <i class="fas fa-sign-out-alt w-4 text-center"></i> Logout
            </a>
        </div>
    </nav>
</aside>

<script>
    function toggleSidebar() {
        document.getElementById('sidebar').classList.toggle('-translate-x-full');
        document.getElementById('sidebarOverlay').classList.toggle('hidden');
    }
</script>
