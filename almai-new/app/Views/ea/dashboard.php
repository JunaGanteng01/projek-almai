<?= $this->extend('ea/layouts/main') ?>

<?= $this->section('content') ?>

<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js'></script>

<style>
    /* Dark Theme Calendar Styles for Dashboard */
    .fc-theme-standard td, .fc-theme-standard th, .fc-theme-standard .fc-scrollgrid { border: none !important; background: transparent !important; }
    .fc-header-toolbar { margin-bottom: 1.5rem !important; }
    .fc-toolbar-title { font-size: 1.1rem !important; font-weight: 700 !important; color: white !important; }
    .fc-button-primary {
        background-color: #1a1a1a !important;
        border: 1px solid rgba(255,255,255,0.1) !important;
        color: #fff !important;
        text-transform: capitalize !important;
        border-radius: 8px !important;
        padding: 0.4rem 0.8rem !important;
        font-size: 0.8rem !important;
        font-weight: 600 !important;
        box-shadow: none !important;
        transition: all 0.2s ease;
    }
    .fc-button-primary:hover { background-color: #2a2a2a !important; border-color: rgba(255,255,255,0.2) !important; }
    .fc-button-primary:not(:disabled).fc-button-active, .fc-button-primary:not(:disabled):active {
        background-color: rgba(16, 185, 129, 0.2) !important;
        border-color: #33e818 !important;
        color: #33e818 !important;
    }
    .fc-daygrid-day-frame {
        border: 1px solid rgba(255,255,255,0.03) !important;
        background-color: #121212;
        border-radius: 12px;
        margin: 2px;
        min-height: 80px !important;
        transition: background-color 0.2s;
        cursor: pointer;
    }
    .fc-daygrid-day-frame:hover { background-color: rgba(255,255,255,0.04); }
    
    /* Remove box for dates outside current month */
    .fc-day-other .fc-daygrid-day-frame {
        background-color: transparent !important;
        border-color: transparent !important;
        box-shadow: none !important;
        cursor: default;
    }
    .fc-day-other .fc-daygrid-day-frame:hover {
        background-color: transparent !important;
    }
    .fc-day-today .fc-daygrid-day-frame {
        background-color: rgba(16, 185, 129, 0.05) !important;
        border: 1px solid rgba(16, 185, 129, 0.3) !important;
        box-shadow: 0 0 10px rgba(16, 185, 129, 0.05) inset;
    }
    .fc-day-today .fc-daygrid-day-number { color: #33e818 !important; font-weight: 700 !important; }
    .fc-daygrid-day-number {
        font-size: 0.85rem; padding: 8px !important;
        text-decoration: none !important; margin: auto; display: block; text-align: center; width: 100%;
    }
    .fc-daygrid-event-harness { margin-top: 2px !important; }
    .fc-daygrid-dot-event {
        justify-content: center !important; padding: 2px !important;
        background: transparent !important; border: none !important;
    }
    .fc-daygrid-event-dot {
        border-width: 4px !important; border-radius: 50% !important; margin: 0 2px !important;
    }
    .fc-event-title { display: none !important; }
    .fc-event-time { display: none !important; }
    .fc-col-header-cell { padding-bottom: 12px !important; }
    .fc .fc-toolbar.fc-header-toolbar { display: none !important; /* Hide default toolbar as we use custom header */ }
</style>

<div class="flex flex-col gap-8 pb-10">
    
    <!-- AI Executive Summary -->
    <div class="bg-[#161616] border border-white/10 rounded-[20px] p-6 relative overflow-hidden">
        <i class="fas fa-robot absolute -top-10 -right-10 text-[180px] text-white/[0.02] transform rotate-12"></i>
        
        <div class="flex items-center gap-4 mb-5 relative z-10">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#8b5cf6] to-[#6366f1] shadow-[0_0_20px_rgba(139,92,246,0.3)]"></div>
            <h2 class="text-2xl font-bold text-white tracking-wide">AI Executive Summary</h2>
        </div>
        
        <div class="relative z-10 pl-5 border-l-4 border-white/20 mb-6">
            <p class="text-gray-300 italic text-[16px] leading-relaxed">
                "<?= esc($ai_summary) ?>"
            </p>
        </div>
        
        <div class="flex items-center gap-3 relative z-10">
            <button class="px-5 py-2.5 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl text-sm text-gray-300 transition-colors flex items-center gap-2 font-medium">
                <i class="fas fa-sync-alt text-xs"></i> Generate Ulang
            </button>
            <button class="px-5 py-2.5 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl text-sm text-gray-300 transition-colors flex items-center gap-2 font-medium">
                <i class="fas fa-search text-xs"></i> Detail Analisis
            </button>
        </div>
    </div>

    <!-- Key Performance Indicators -->
    <div>
        <div class="flex items-center gap-3 mb-5">
            <i class="fas fa-chart-line text-[#33e818] text-xl"></i>
            <h3 class="text-xl font-bold text-white">Key Performance Indicators</h3>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Card 1 -->
            <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 flex flex-col justify-between">
                <div class="w-12 h-12 rounded-xl bg-[#33e818]/10 flex items-center justify-center mb-5">
                    <i class="fas fa-wallet text-[#33e818] text-xl"></i>
                </div>
                <div>
                    <div class="text-[22px] font-bold text-white mb-1">Rp <?= number_format($summary['revenue'], 0, ',', '.') ?></div>
                    <div class="text-sm text-gray-500 font-medium">Total Pendapatan</div>
                </div>
            </div>
            
            <?php foreach ($kasBankAccounts as $acc): ?>
            <!-- Card Kas/Bank -->
            <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 flex flex-col justify-between">
                <div class="w-12 h-12 rounded-xl bg-[#33e818]/10 flex items-center justify-center mb-5">
                    <i class="fas fa-money-check-alt text-[#33e818] text-xl"></i>
                </div>
                <div>
                    <div class="text-[22px] font-bold text-white mb-1">
                        <?php if ($acc['balance'] < 0): ?>
                            <span class="text-red-500">-Rp <?= number_format(abs($acc['balance']), 0, ',', '.') ?></span>
                        <?php else: ?>
                            Rp <?= number_format($acc['balance'], 0, ',', '.') ?>
                        <?php endif; ?>
                    </div>
                    <div class="text-sm text-gray-500 font-medium"><?= esc($acc['nama_akun']) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
            <!-- Card 2 -->
            <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 flex flex-col justify-between">
                <div class="w-12 h-12 rounded-xl bg-purple-500/10 flex items-center justify-center mb-5">
                    <i class="fas fa-user-shield text-purple-500 text-xl"></i>
                </div>
                <div>
                    <div class="text-[22px] font-bold text-white mb-1"><?= number_format($summary['admin'], 0, ',', '.') ?></div>
                    <div class="text-sm text-gray-500 font-medium">Admin</div>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 flex flex-col justify-between">
                <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center mb-5">
                    <i class="fas fa-user-tie text-blue-500 text-xl"></i>
                </div>
                <div>
                    <div class="text-[22px] font-bold text-white mb-1"><?= number_format($summary['wpa'], 0, ',', '.') ?></div>
                    <div class="text-sm text-gray-500 font-medium">WPA</div>
                </div>
            </div>
            <!-- Card 4 -->
            <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 flex flex-col justify-between">
                <div class="w-12 h-12 rounded-xl bg-gray-500/10 flex items-center justify-center mb-5">
                    <i class="fas fa-user-graduate text-gray-300 text-xl"></i>
                </div>
                <div>
                    <div class="text-[22px] font-bold text-white mb-1"><?= number_format($summary['cwpa'], 0, ',', '.') ?></div>
                    <div class="text-sm text-gray-500 font-medium">CWPA</div>
                </div>
            </div>
            <!-- Card 5 -->
            <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 flex flex-col justify-between">
                <div class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center mb-5">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
                <div>
                    <div class="text-[22px] font-bold text-white mb-1"><?= number_format($summary['total_users'], 0, ',', '.') ?></div>
                    <div class="text-sm text-gray-500 font-medium">User</div>
                </div>
            </div>
            <!-- Card 6 -->
            <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 flex flex-col justify-between">
                <div class="w-12 h-12 rounded-xl bg-yellow-500/10 flex items-center justify-center mb-5">
                    <i class="fas fa-crown text-yellow-500 text-xl"></i>
                </div>
                <div>
                    <div class="text-[22px] font-bold text-white mb-1"><?= number_format($summary['user_pro'], 0, ',', '.') ?></div>
                    <div class="text-sm text-gray-500 font-medium">User PRO</div>
                </div>
            </div>
            <!-- Card 7 -->
            <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 flex flex-col justify-between">
                <div class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center mb-5">
                    <i class="fas fa-project-diagram text-blue-500 text-xl"></i>
                </div>
                <div>
                    <div class="text-[22px] font-bold text-white mb-1"><?= number_format($summary['active_projects'], 0, ',', '.') ?></div>
                    <div class="text-sm text-gray-500 font-medium">Active Project</div>
                </div>
            </div>
            <!-- Card 8 -->
            <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 flex flex-col justify-between">
                <div class="w-12 h-12 rounded-xl bg-red-500/10 flex items-center justify-center mb-5">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                </div>
                <div>
                    <div class="text-[22px] font-bold text-white mb-1"><?= number_format($summary['pending_tasks'], 0, ',', '.') ?></div>
                    <div class="text-sm text-gray-500 font-medium">Pending Strategic Task</div>
                </div>
            </div>
            
            <!-- Card 9: Total Kegiatan -->
            <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 flex flex-col justify-between">
                <div class="w-12 h-12 rounded-xl bg-orange-500/10 flex items-center justify-center mb-5">
                    <i class="fas fa-list-alt text-orange-500 text-xl"></i>
                </div>
                <div>
                    <div class="text-[22px] font-bold text-white mb-1"><?= number_format($summary['total_kegiatan'], 0, ',', '.') ?></div>
                    <div class="text-sm text-gray-500 font-medium">Total Kegiatan</div>
                </div>
            </div>
            <!-- Card 10: Total Signal -->
            <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 flex flex-col justify-between">
                <div class="w-12 h-12 rounded-xl bg-blue-400/10 flex items-center justify-center mb-5">
                    <i class="fas fa-signal text-blue-400 text-xl"></i>
                </div>
                <div>
                    <div class="text-[22px] font-bold text-white mb-1"><?= number_format($summary['total_signal'], 0, ',', '.') ?></div>
                    <div class="text-sm text-gray-500 font-medium">Total Signal</div>
                </div>
            </div>
            <!-- Card 11: Total Konsultasi -->
            <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 flex flex-col justify-between">
                <div class="w-12 h-12 rounded-xl bg-purple-400/10 flex items-center justify-center mb-5">
                    <i class="fas fa-comments text-purple-400 text-xl"></i>
                </div>
                <div>
                    <div class="text-[22px] font-bold text-white mb-1"><?= number_format($summary['total_konsultasi'], 0, ',', '.') ?></div>
                    <div class="text-sm text-gray-500 font-medium">Total Konsultasi</div>
                </div>
            </div>
            <!-- Card 12: Total EA -->
            <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 flex flex-col justify-between">
                <div class="w-12 h-12 rounded-xl bg-[#33e818]/10 flex items-center justify-center mb-5">
                    <i class="fas fa-robot text-[#33e818] text-xl"></i>
                </div>
                <div>
                    <div class="text-[22px] font-bold text-white mb-1"><?= number_format($summary['total_ea'], 0, ',', '.') ?></div>
                    <div class="text-sm text-gray-500 font-medium">Total EA</div>
                </div>
            </div>
            <!-- Card 13: Total Seminar -->
            <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 flex flex-col justify-between">
                <div class="w-12 h-12 rounded-xl bg-pink-500/10 flex items-center justify-center mb-5">
                    <i class="fas fa-chalkboard-teacher text-pink-500 text-xl"></i>
                </div>
                <div>
                    <div class="text-[22px] font-bold text-white mb-1"><?= number_format($summary['total_seminar'], 0, ',', '.') ?></div>
                    <div class="text-sm text-gray-500 font-medium">Total Seminar</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kalender & Jadwal -->
    <div class="bg-[#121212] border border-white/5 rounded-2xl p-6 mt-4 shadow-xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-white">Jadwal Hari Ini & Kalender</h3>
            <div class="flex gap-2">
                <button id="prevBtn" class="w-8 h-8 rounded bg-white/5 hover:bg-white/10 text-white flex items-center justify-center border border-white/10 transition-colors"><i class="fas fa-chevron-left text-xs"></i></button>
                <div id="calTitle" class="px-4 py-1.5 rounded bg-white/5 text-white font-semibold text-sm border border-white/10 flex items-center">Juli 2026</div>
                <button id="nextBtn" class="w-8 h-8 rounded bg-white/5 hover:bg-white/10 text-white flex items-center justify-center border border-white/10 transition-colors"><i class="fas fa-chevron-right text-xs"></i></button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <div id="calendar"></div>
                
                <!-- Legend -->
                <div class="flex flex-wrap gap-4 md:gap-6 justify-center border-t border-white/5 pt-5 mt-4">
                    <div class="flex items-center gap-1.5 md:gap-2">
                        <span class="w-2 h-2 rounded-full" style="background:#3b82f6"></span>
                        <span class="text-xs md:text-sm text-gray-400">Meeting</span>
                    </div>
                    <div class="flex items-center gap-1.5 md:gap-2">
                        <span class="w-2 h-2 rounded-full" style="background:#33e818"></span>
                        <span class="text-xs md:text-sm text-gray-400">Task/Deadline</span>
                    </div>
                    <div class="flex items-center gap-1.5 md:gap-2">
                        <span class="w-2 h-2 rounded-full" style="background:#f59e0b"></span>
                        <span class="text-xs md:text-sm text-gray-400">Reminder</span>
                    </div>
                    <div class="flex items-center gap-1.5 md:gap-2">
                        <span class="w-2 h-2 rounded-full" style="background:#a855f7"></span>
                        <span class="text-xs md:text-sm text-gray-400">Webinar</span>
                    </div>
                    <div class="flex items-center gap-1.5 md:gap-2">
                        <span class="w-2 h-2 rounded-full" style="background:#ef4444"></span>
                        <span class="text-xs md:text-sm text-gray-400">Holiday</span>
                    </div>
                </div>
            </div>
            
            <!-- Daftar Acara -->
            <div class="bg-[#161616] border border-white/5 rounded-[20px] p-6 flex flex-col shadow-inner">
                <div class="mb-5">
                    <h4 class="text-lg font-bold text-[#33e818] mb-1" id="selectedDateTitle">Selasa, 21 Juli 2026</h4>
                    <p class="text-gray-500 text-sm">2 Acara Terjadwal</p>
                </div>
                
                <div class="flex flex-col gap-4 flex-1">
                    <!-- Dummy Event 1 -->
                    <div class="bg-[#1a1a1a] rounded-xl p-4 flex gap-4 border-l-[3px] border-l-white/20 hover:border-l-[#33e818] transition-colors cursor-pointer group">
                        <div class="text-right shrink-0">
                            <div class="text-gray-400 text-xs font-semibold">11:00</div>
                            <div class="text-white text-sm font-bold group-hover:text-[#33e818] transition-colors">12:00</div>
                        </div>
                        <div>
                            <h5 class="text-white font-semibold text-sm mb-1 leading-tight group-hover:text-[#33e818] transition-colors">Meeting dengan Investor (Q3 Review)</h5>
                            <div class="flex items-center gap-1.5 text-gray-500 text-xs">
                                <i class="fas fa-video"></i> Zoom
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dummy Event 2 -->
                    <div class="bg-[#1a1a1a] rounded-xl p-4 flex gap-4 border-l-[3px] border-l-white/20 hover:border-l-blue-500 transition-colors cursor-pointer group">
                        <div class="text-right shrink-0">
                            <div class="text-gray-400 text-xs font-semibold">13:00</div>
                            <div class="text-white text-sm font-bold group-hover:text-blue-500 transition-colors">14:30</div>
                        </div>
                        <div>
                            <h5 class="text-white font-semibold text-sm mb-1 leading-tight group-hover:text-blue-500 transition-colors">Evaluasi Kinerja WPA & Partnership</h5>
                            <div class="flex items-center gap-1.5 text-gray-500 text-xs">
                                <i class="fas fa-map-marker-alt"></i> Ruang Rapat Utama
                            </div>
                        </div>
                    </div>
                </div>

                <a href="/ea/calenders" class="mt-6 w-full py-3 bg-transparent border border-white/10 rounded-xl text-center text-sm font-semibold text-gray-400 hover:text-white hover:bg-white/5 transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-plus"></i> Tambah Acara
                </a>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mt-4">
        <!-- Revenue Chart -->
        <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 lg:col-span-1 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-sm md:text-base text-white">Grafik Pendapatan</h3>
                <select id="revenueFilter" class="bg-black border border-white/20 rounded-lg px-3 py-1.5 text-xs focus:border-[#33e818] focus:outline-none text-white">
                    <option value="7">7 Hari</option>
                    <option value="30">30 Hari</option>
                    <option value="90">3 Bulan</option>
                    <option value="180">6 Bulan</option>
                    <option value="365" selected>1 Tahun</option>
                </select>
            </div>
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <!-- User Growth Chart -->
        <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 lg:col-span-1 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-sm md:text-base text-white">Pertumbuhan User</h3>
                <select id="userGrowthFilter" class="bg-black border border-white/20 rounded-lg px-3 py-1.5 text-xs focus:border-[#33e818] focus:outline-none text-white">
                    <option value="7">7 Hari</option>
                    <option value="30">30 Hari</option>
                    <option value="90">3 Bulan</option>
                    <option value="180">6 Bulan</option>
                    <option value="365" selected>1 Tahun</option>
                </select>
            </div>
            <div class="h-64">
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>

        <!-- Category Revenue Pie Chart -->
        <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 lg:col-span-1 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-sm md:text-base text-white">Pendapatan per Layanan</h3>
                <span class="text-xs text-gray-500">Berdasarkan Subkategori</span>
            </div>
            <div class="h-64 flex items-center justify-center">
                <canvas id="categoryRevenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Bottom Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mt-4">
        <!-- Recent Transactions -->
        <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-sm md:text-base text-white">Transaksi Terbaru</h3>
                <a href="<?= base_url('admin/transaksi') ?>" class="text-[#33e818] text-xs hover:underline">Lihat Semua</a>
            </div>
            <div class="space-y-3">
                <?php if (empty($recentTransactions)): ?>
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-receipt text-3xl mb-2"></i>
                        <p class="text-sm">Belum ada transaksi</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($recentTransactions as $trx): ?>
                        <div class="flex items-center justify-between p-3 bg-black/50 rounded-xl gap-3 border border-white/5">
                            <div class="flex items-center gap-3 min-w-0 flex-1">
                                <div class="w-8 h-8 md:w-10 md:h-10 shrink-0 <?= $trx['status'] === 'confirmed' ? 'bg-[#33e818]/20' : ($trx['status'] === 'paid' ? 'bg-blue-500/20' : 'bg-yellow-500/20') ?> rounded-full flex items-center justify-center">
                                    <i class="fas fa-<?= $trx['product_type'] === 'kelas' ? 'graduation-cap' : ($trx['product_type'] === 'tools' ? 'tools' : 'newspaper') ?> <?= $trx['status'] === 'confirmed' ? 'text-[#33e818]' : ($trx['status'] === 'paid' ? 'text-blue-500' : 'text-yellow-500') ?> text-sm"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="font-medium text-sm text-white line-clamp-1 truncate"><?= esc($trx['product_name']) ?></p>
                                    <p class="text-xs text-gray-500 truncate"><?= esc($trx['user_name'] ?? 'Unknown') ?> • <?= date('d M H:i', strtotime($trx['created_at'])) ?></p>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="font-bold text-xs md:text-sm whitespace-nowrap">
                                    <?php if (($trx['payment_method'] ?? '') === 'poin'): ?>
                                        <span class="text-yellow-400"><?= number_format($trx['total'], 0, ',', '.') ?> <i class="fas fa-coins ml-1"></i></span>
                                    <?php else: ?>
                                        <span class="text-[#33e818]">Rp <?= number_format($trx['total'], 0, ',', '.') ?></span>
                                    <?php endif; ?>
                                </span>
                                <p class="text-xs <?= $trx['status'] === 'confirmed' ? 'text-[#33e818]' : ($trx['status'] === 'paid' ? 'text-blue-400' : 'text-yellow-400') ?>"><?= ucfirst($trx['status']) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Top WPA -->
        <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-sm md:text-base text-white">WPA Terpopuler</h3>
                <a href="<?= base_url('admin/wpa') ?>" class="text-[#33e818] text-xs hover:underline">Lihat Semua</a>
            </div>
            <div class="space-y-3">
                <?php if (empty($wpaList)): ?>
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-user-tie text-3xl mb-2"></i>
                        <p class="text-sm">Belum ada WPA</p>
                    </div>
                <?php else: ?>
                    <?php foreach (array_slice($wpaList, 0, 5) as $index => $wpa): ?>
                        <?php
                        $wpaPhoto = $wpa['photo'] ?? '';
                        if ($wpaPhoto && !str_starts_with($wpaPhoto, 'http')) {
                            $wpaPhoto = preg_replace('/^writable\//', '', $wpaPhoto);
                            $wpaPhoto = base_url('file/' . $wpaPhoto);
                        } elseif (!$wpaPhoto) {
                            $wpaPhoto = 'https://via.placeholder.com/100';
                        }
                        ?>
                        <div class="flex items-center justify-between p-3 bg-black/50 rounded-xl gap-3 border border-white/5">
                            <div class="flex items-center gap-3 min-w-0 flex-1">
                                <span class="w-6 h-6 shrink-0 bg-[#33e818]/20 rounded-full flex items-center justify-center text-[#33e818] text-xs font-bold"><?= $index + 1 ?></span>
                                <img src="<?= esc($wpaPhoto) ?>" alt="<?= esc($wpa['name']) ?>" class="w-10 h-10 shrink-0 rounded-full object-cover">
                                <div class="min-w-0 flex-1">
                                    <p class="font-medium text-sm text-white truncate"><?= esc(explode(',', $wpa['name'])[0]) ?></p>
                                    <p class="text-xs text-gray-500 truncate"><?= esc($wpa['specialty']) ?></p>
                                </div>
                            </div>
                            <span class="text-yellow-500 font-bold text-sm shrink-0"><i class="fas fa-star mr-1"></i><?= esc($wpa['rating']) ?></span>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Rekap Kegiatan Bulanan -->
    <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 mt-4 shadow-lg overflow-hidden flex flex-col">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-sm md:text-base flex items-center gap-2 text-white">
                <i class="fas fa-list-alt text-[#33e818]"></i> Rekap Kegiatan Bulanan Terbaru
            </h3>
        </div>
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-gray-500 text-[10px] md:text-xs uppercase tracking-wider border-b border-white/5">
                        <th class="pb-3 px-2">No</th>
                        <th class="pb-3 px-2">Jenis Kegiatan</th>
                        <th class="pb-3 px-2 text-center">Jml Kegiatan</th>
                        <th class="pb-3 px-2 text-center">Total Klien/Peserta</th>
                        <th class="pb-3 px-2 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php foreach ($rekapKegiatan as $index => $item): ?>
                    <tr class="hover:bg-white/[0.02] transition-colors">
                        <td class="py-3 px-2 text-xs md:text-sm"><?= $index + 1 ?></td>
                        <td class="py-3 px-2 text-xs md:text-sm font-medium text-white"><?= esc($item['name']) ?></td>
                        <td class="py-3 px-2 text-xs md:text-sm text-center"><?= $item['data']['count'] ?></td>
                        <td class="py-3 px-2 text-xs md:text-sm text-center text-blue-400"><?= esc($item['data']['total']) ?></td>
                        <td class="py-3 px-2 text-right">
                            <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase bg-[#33e818]/10 text-[#33e818]">
                                Selesai
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($revenueData['labels']) ?>,
            datasets: [{
                label: 'Pendapatan',
                data: <?= json_encode($revenueData['data']) ?>,
                borderColor: '#33e818',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#33e818',
                pointBorderColor: '#33e818',
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#111', titleColor: '#fff', bodyColor: '#33e818', borderColor: '#333', borderWidth: 1,
                    callbacks: { label: function(context) { return 'Rp ' + context.raw.toLocaleString('id-ID'); } }
                }
            },
            scales: {
                x: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#666' } },
                y: {
                    grid: { color: 'rgba(255,255,255,0.05)' },
                    ticks: {
                        color: '#666',
                        callback: function(value) {
                            if (value >= 1000000) return 'Rp ' + (value / 1000000) + 'jt';
                            if (value >= 1000) return 'Rp ' + (value / 1000) + 'rb';
                            return 'Rp ' + value;
                        }
                    }
                }
            }
        }
    });

    // User Growth Chart
    const userCtx = document.getElementById('userGrowthChart').getContext('2d');
    new Chart(userCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($userGrowthData['labels']) ?>,
            datasets: [{
                label: 'User Baru',
                data: <?= json_encode($userGrowthData['data']) ?>,
                backgroundColor: 'rgba(16, 185, 129, 0.6)',
                borderColor: '#33e818',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#111', titleColor: '#fff', bodyColor: '#33e818', borderColor: '#333', borderWidth: 1,
                    callbacks: { label: function(context) { return context.raw + ' user baru'; } }
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#666' } },
                y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#666', stepSize: 1 }, beginAtZero: true }
            }
        }
    });

    // Category Revenue Pie Chart
    const categoryCtx = document.getElementById('categoryRevenueChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($categoryRevenueData['labels']) ?>,
            datasets: [{
                data: <?= json_encode($categoryRevenueData['data']) ?>,
                backgroundColor: <?= json_encode($categoryRevenueData['colors']) ?>,
                borderColor: '#111',
                borderWidth: 2,
                hoverOffset: 15
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false, cutout: '65%',
            plugins: {
                legend: { position: 'bottom', labels: { color: '#666', padding: 15, boxWidth: 10, usePointStyle: true, font: { size: 10 } } },
                tooltip: {
                    backgroundColor: '#111', titleColor: '#fff', bodyColor: '#fff', borderColor: '#333', borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const value = context.raw;
                            const percentage = ((value / total) * 100).toFixed(1);
                            return ` Rp ${value.toLocaleString('id-ID')} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // Filter Event Handlers
    let revenueChartInstance = null;
    let userGrowthChartInstance = null;

    window.addEventListener('DOMContentLoaded', () => {
        revenueChartInstance = Chart.getChart('revenueChart');
        userGrowthChartInstance = Chart.getChart('userGrowthChart');
    });

    document.getElementById('revenueFilter').addEventListener('change', async function() {
        const days = this.value;
        try {
            const response = await fetch(`<?= base_url('ea/dashboard/revenue-data') ?>?days=${days}`);
            const data = await response.json();
            if (revenueChartInstance) {
                revenueChartInstance.data.labels = data.labels;
                revenueChartInstance.data.datasets[0].data = data.data;
                revenueChartInstance.update();
            }
        } catch (error) {}
    });

    document.getElementById('userGrowthFilter').addEventListener('change', async function() {
        const days = this.value;
        try {
            const response = await fetch(`<?= base_url('ea/dashboard/user-growth-data') ?>?days=${days}`);
            const data = await response.json();
            if (userGrowthChartInstance) {
                userGrowthChartInstance.data.labels = data.labels;
                userGrowthChartInstance.data.datasets[0].data = data.data;
                userGrowthChartInstance.update();
            }
        } catch (error) {}
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            headerToolbar: false, // Disembunyikan karena kita pakai custom header
            height: 'auto',
            // Dummy events for display
            events: [
                { title: 'Meeting', start: '2026-07-21', color: '#33e818', display: 'list-item' },
                { title: 'Evaluasi', start: '2026-07-21', color: '#3b82f6', display: 'list-item' },
                { title: 'Event', start: '2026-07-09', color: '#a855f7', display: 'list-item' },
                { title: 'Event', start: '2026-07-23', color: '#a855f7', display: 'list-item' },
                { title: 'Event', start: '2026-07-07', color: '#3b82f6', display: 'list-item' }
            ],
            dayHeaderContent: function(arg) {
                var days = ['MIN','SEN','SEL','RAB','KAM','JUM','SAB'];
                var idx   = arg.date.getDay();
                var color = (idx === 0) ? '#ef4444' : '#6b7280';
                return { html: '<span style="color:' + color + ';font-size:0.75rem;font-weight:700;letter-spacing:0.05em;">' + days[idx] + '</span>' };
            },
            datesSet: function(info) {
                // Update custom title
                document.getElementById('calTitle').innerText = info.view.title;
                applyDateColors();
            }
        });
        calendar.render();

        // Custom Buttons
        document.getElementById('prevBtn').addEventListener('click', function() { calendar.prev(); });
        document.getElementById('nextBtn').addEventListener('click', function() { calendar.next(); });

        function applyDateColors() {
            document.querySelectorAll('.fc-daygrid-day').forEach(function(cell) {
                var dateStr = cell.getAttribute('data-date');
                if (!dateStr) return;
                var isSun    = (new Date(dateStr + 'T12:00:00').getDay() === 0);
                var isOther  = cell.classList.contains('fc-day-other');
                var isToday  = cell.classList.contains('fc-day-today');
                var num = cell.querySelector('a.fc-daygrid-day-number, .fc-daygrid-day-number');
                if (!num) return;
                num.style.textDecoration = 'none';
                if (isToday) {
                    num.style.color = '#33e818';
                    num.style.fontWeight = '700';
                } else if (isOther) {
                    num.style.color = isSun ? 'rgba(239,68,68,0.15)' : '#282828';
                } else {
                    num.style.color = isSun ? '#ef4444' : '#6b7280';
                    num.style.fontWeight = '500';
                }
            });
        }
    });
</script>

<?= $this->endSection() ?>