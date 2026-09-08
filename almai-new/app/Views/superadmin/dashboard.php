<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>
<?php 
$pageTitle = 'Dashboard Overview';
$pageSubtitle = 'Ringkasan performa dan metrik utama sistem ALMAI';
$activeMenu = 'dashboard'; 
?>

<!-- Quick Stats Cards (6 Uniform Metrics) -->
<div class="grid grid-cols-2 sm:grid-cols-3 2xl:grid-cols-6 gap-3 sm:gap-4 mb-6">
    <!-- Total Revenue -->
    <div class="dash-card dash-card-interactive p-3.5 sm:p-4 flex flex-col justify-between group min-w-0">
        <div class="flex items-center justify-between mb-2 sm:mb-2.5">
            <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-gray-400 truncate">Total Pendapatan</span>
            <div class="w-8 h-8 rounded-lg bg-accent/15 border border-accent/30 text-accent flex items-center justify-center group-hover:scale-110 transition-transform shrink-0">
                <i class="fas fa-wallet text-xs"></i>
            </div>
        </div>
        <div class="min-w-0">
            <h3 class="text-sm sm:text-base xl:text-lg font-black text-white tracking-tight truncate group-hover:text-accent transition-colors" title="Rp <?= number_format($totalRevenue, 0, ',', '.') ?>">
                Rp <?= number_format($totalRevenue, 0, ',', '.') ?>
            </h3>
            <div class="flex items-center gap-1 mt-1 text-[10px] sm:text-[11px] text-accent font-medium truncate">
                <i class="fas fa-arrow-trend-up text-[9px] shrink-0"></i>
                <span class="truncate">Akumulasi Total</span>
            </div>
        </div>
    </div>

    <!-- Total Admin -->
    <div class="dash-card dash-card-interactive p-3.5 sm:p-4 flex flex-col justify-between group min-w-0">
        <div class="flex items-center justify-between mb-2 sm:mb-2.5">
            <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-gray-400 truncate">Admin</span>
            <div class="w-8 h-8 rounded-lg bg-purple-500/15 border border-purple-500/30 text-purple-400 flex items-center justify-center group-hover:scale-110 transition-transform shrink-0">
                <i class="fas fa-user-shield text-xs"></i>
            </div>
        </div>
        <div class="min-w-0">
            <h3 class="text-base sm:text-xl font-black text-white tracking-tight group-hover:text-purple-400 transition-colors">
                <?= number_format($totalAdmin) ?>
            </h3>
            <div class="flex items-center gap-1 mt-1 text-[10px] sm:text-[11px] text-gray-400 font-medium truncate">
                <i class="fas fa-users-gear text-[9px] shrink-0"></i>
                <span class="truncate">Pengelola Sistem</span>
            </div>
        </div>
    </div>

    <!-- Total WPA -->
    <div class="dash-card dash-card-interactive p-3.5 sm:p-4 flex flex-col justify-between group min-w-0">
        <div class="flex items-center justify-between mb-2 sm:mb-2.5">
            <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-gray-400 truncate">WPA Aktif</span>
            <div class="w-8 h-8 rounded-lg bg-blue-500/15 border border-blue-500/30 text-blue-400 flex items-center justify-center group-hover:scale-110 transition-transform shrink-0">
                <i class="fas fa-user-tie text-xs"></i>
            </div>
        </div>
        <div class="min-w-0">
            <h3 class="text-base sm:text-xl font-black text-white tracking-tight group-hover:text-blue-400 transition-colors">
                <?= number_format($totalWpa) ?>
            </h3>
            <div class="flex items-center gap-1 mt-1 text-[10px] sm:text-[11px] text-gray-400 font-medium truncate">
                <i class="fas fa-certificate text-[9px] text-blue-400 shrink-0"></i>
                <span class="truncate">Mentor Resmi</span>
            </div>
        </div>
    </div>

    <!-- Total CWPA -->
    <div class="dash-card dash-card-interactive p-3.5 sm:p-4 flex flex-col justify-between group min-w-0">
        <div class="flex items-center justify-between mb-2 sm:mb-2.5">
            <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-gray-400 truncate">CWPA</span>
            <div class="w-8 h-8 rounded-lg bg-cyan-500/15 border border-cyan-500/30 text-cyan-400 flex items-center justify-center group-hover:scale-110 transition-transform shrink-0">
                <i class="fas fa-user-graduate text-xs"></i>
            </div>
        </div>
        <div class="min-w-0">
            <h3 class="text-base sm:text-xl font-black text-white tracking-tight group-hover:text-cyan-400 transition-colors">
                <?= number_format($totalCwpa) ?>
            </h3>
            <div class="flex items-center gap-1 mt-1 text-[10px] sm:text-[11px] text-gray-400 font-medium truncate">
                <i class="fas fa-hourglass-half text-[9px] text-cyan-400 shrink-0"></i>
                <span class="truncate">Kandidat WPA</span>
            </div>
        </div>
    </div>

    <!-- Total Users -->
    <div class="dash-card dash-card-interactive p-3.5 sm:p-4 flex flex-col justify-between group min-w-0">
        <div class="flex items-center justify-between mb-2 sm:mb-2.5">
            <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-gray-400 truncate">Total User</span>
            <div class="w-8 h-8 rounded-lg bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 flex items-center justify-center group-hover:scale-110 transition-transform shrink-0">
                <i class="fas fa-users text-xs"></i>
            </div>
        </div>
        <div class="min-w-0">
            <h3 class="text-base sm:text-xl font-black text-white tracking-tight group-hover:text-emerald-400 transition-colors">
                <?= number_format($totalUsers) ?>
            </h3>
            <div class="flex items-center gap-1 mt-1 text-[10px] sm:text-[11px] text-gray-400 font-medium truncate">
                <i class="fas fa-id-card text-[9px] shrink-0"></i>
                <span class="truncate">Member Terdaftar</span>
            </div>
        </div>
    </div>

    <!-- Total User PRO -->
    <div class="dash-card dash-card-interactive p-3.5 sm:p-4 flex flex-col justify-between group min-w-0">
        <div class="flex items-center justify-between mb-2 sm:mb-2.5">
            <span class="text-[10px] sm:text-[11px] font-bold uppercase tracking-wider text-gray-400 truncate">User PRO</span>
            <div class="w-8 h-8 rounded-lg bg-amber-500/15 border border-amber-500/30 text-yellow-400 flex items-center justify-center group-hover:scale-110 transition-transform shrink-0">
                <i class="fas fa-crown text-xs"></i>
            </div>
        </div>
        <div class="min-w-0">
            <h3 class="text-base sm:text-xl font-black text-white tracking-tight group-hover:text-yellow-400 transition-colors">
                <?= number_format($totalUserPro) ?>
            </h3>
            <div class="flex items-center gap-1 mt-1 text-[10px] sm:text-[11px] text-yellow-400 font-medium truncate">
                <i class="fas fa-star text-[9px] shrink-0"></i>
                <span class="truncate">Member Premium</span>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section (3 Balanced Columns on XL, Clean Wrap on 1024px) -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-4 sm:gap-6 mb-6">
    <!-- Revenue Line Chart -->
    <div class="dash-card p-4 sm:p-5 flex flex-col justify-between">
        <div class="flex items-center justify-between mb-3">
            <div>
                <h3 class="font-bold text-xs sm:text-sm text-white flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-accent animate-pulse"></span>
                    Grafik Pendapatan
                </h3>
                <p class="text-[10px] sm:text-[11px] text-gray-400 mt-0.5">Tren arus kas masuk</p>
            </div>
            <select id="revenueFilter" class="bg-[#141414] hover:bg-[#1a1a1a] border border-white/[0.1] hover:border-accent/40 rounded-lg px-2.5 py-1 text-[11px] text-gray-200 font-semibold focus:border-accent focus:outline-none transition cursor-pointer">
                <option value="7">7 Hari</option>
                <option value="30">30 Hari</option>
                <option value="90">3 Bulan</option>
                <option value="180">6 Bulan</option>
                <option value="365" selected>1 Tahun</option>
            </select>
        </div>
        <div class="h-52 sm:h-60 lg:h-64 w-full relative">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- User Growth Bar Chart -->
    <div class="dash-card p-4 sm:p-5 flex flex-col justify-between">
        <div class="flex items-center justify-between mb-3">
            <div>
                <h3 class="font-bold text-xs sm:text-sm text-white flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    Pertumbuhan User
                </h3>
                <p class="text-[10px] sm:text-[11px] text-gray-400 mt-0.5">Registrasi member baru</p>
            </div>
            <select id="userGrowthFilter" class="bg-[#141414] hover:bg-[#1a1a1a] border border-white/[0.1] hover:border-accent/40 rounded-lg px-2.5 py-1 text-[11px] text-gray-200 font-semibold focus:border-accent focus:outline-none transition cursor-pointer">
                <option value="7">7 Hari</option>
                <option value="30">30 Hari</option>
                <option value="90">3 Bulan</option>
                <option value="180">6 Bulan</option>
                <option value="365" selected>1 Tahun</option>
            </select>
        </div>
        <div class="h-52 sm:h-60 lg:h-64 w-full relative">
            <canvas id="userGrowthChart"></canvas>
        </div>
    </div>

    <!-- Category Revenue Doughnut Chart -->
    <div class="dash-card p-4 sm:p-5 flex flex-col justify-between">
        <div class="flex items-center justify-between mb-3">
            <div>
                <h3 class="font-bold text-xs sm:text-sm text-white flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                    Pendapatan per Layanan
                </h3>
                <p class="text-[10px] sm:text-[11px] text-gray-400 mt-0.5">Distribusi kategori</p>
            </div>
            <span class="px-2 py-0.5 bg-white/[0.05] border border-white/[0.08] text-[9px] font-bold text-gray-300 rounded-md">
                Subkategori
            </span>
        </div>
        <div class="h-52 sm:h-60 lg:h-64 w-full relative flex items-center justify-center">
            <canvas id="categoryRevenueChart"></canvas>
        </div>
    </div>
</div>

<!-- Bottom Section: Recent Activity & Top WPA -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-4 sm:gap-6">
    <!-- Recent Transactions -->
    <div class="dash-card p-3.5 sm:p-5">
        <div class="flex items-center justify-between mb-3 pb-2.5 border-b border-white/[0.06]">
            <div>
                <h3 class="font-bold text-xs sm:text-sm text-white">Transaksi Terbaru</h3>
                <p class="text-[10px] sm:text-[11px] text-gray-400 mt-0.5">Aktivitas pembayaran & transaksi produk</p>
            </div>
            <a href="<?= base_url('superadmin/transaksi') ?>" class="px-2.5 py-1 bg-white/[0.05] hover:bg-accent/10 text-accent hover:text-white border border-accent/20 hover:border-accent/40 rounded-lg text-[11px] font-bold transition flex items-center gap-1">
                <span>Lihat Semua</span>
                <i class="fas fa-chevron-right text-[9px]"></i>
            </a>
        </div>
        <div class="space-y-2">
            <?php if (empty($recentTransactions)): ?>
                <div class="text-center py-8 text-gray-500">
                    <div class="w-10 h-10 mx-auto rounded-xl bg-white/[0.03] border border-white/[0.06] flex items-center justify-center mb-2">
                        <i class="fas fa-receipt text-gray-400 text-sm"></i>
                    </div>
                    <p class="text-xs">Belum ada transaksi tercatat</p>
                </div>
            <?php else: ?>
                <?php foreach (array_slice($recentTransactions, 0, 5) as $trx): ?>
                    <div class="flex items-center justify-between p-2.5 sm:p-3 bg-black/40 hover:bg-black/70 border border-white/[0.05] hover:border-white/[0.12] rounded-xl gap-2.5 transition">
                        <div class="flex items-center gap-2.5 min-w-0 flex-1">
                            <div class="w-8 h-8 shrink-0 <?= $trx['status'] === 'confirmed' ? 'bg-accent/15 text-accent border border-accent/30' : ($trx['status'] === 'paid' ? 'bg-blue-500/15 text-blue-400 border border-blue-500/30' : 'bg-yellow-500/15 text-yellow-400 border border-yellow-500/30') ?> rounded-lg flex items-center justify-center">
                                <i class="fas fa-<?= $trx['product_type'] === 'kelas' ? 'graduation-cap' : ($trx['product_type'] === 'tools' ? 'tools' : 'newspaper') ?> text-xs"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-xs text-white truncate"><?= esc($trx['product_name']) ?></p>
                                <p class="text-[10px] sm:text-[11px] text-gray-400 truncate mt-0.5"><?= esc($trx['user_name'] ?? 'Pengguna') ?> • <span class="text-gray-500"><?= date('d M Y, H:i', strtotime($trx['created_at'])) ?></span></p>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="font-bold text-xs block">
                                <?php if (($trx['payment_method'] ?? '') === 'poin'): ?>
                                    <span class="text-yellow-400"><?= number_format($trx['total'], 0, ',', '.') ?> <i class="fas fa-coins text-[10px] ml-0.5"></i></span>
                                <?php else: ?>
                                    <span class="text-accent font-mono font-bold">Rp <?= number_format($trx['total'], 0, ',', '.') ?></span>
                                <?php endif; ?>
                            </span>
                            <span class="inline-block mt-0.5 px-1.5 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider <?= $trx['status'] === 'confirmed' ? 'bg-accent/15 text-accent border border-accent/30' : ($trx['status'] === 'paid' ? 'bg-blue-500/15 text-blue-400 border border-blue-500/30' : 'bg-yellow-500/15 text-yellow-400 border border-yellow-500/30') ?>">
                                <?= esc($trx['status']) ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Top WPA Mentors -->
    <div class="dash-card p-3.5 sm:p-5">
        <div class="flex items-center justify-between mb-3 pb-2.5 border-b border-white/[0.06]">
            <div>
                <h3 class="font-bold text-xs sm:text-sm text-white">WPA Terpopuler</h3>
                <p class="text-[10px] sm:text-[11px] text-gray-400 mt-0.5">Peringkat performa dan rating mentor WPA</p>
            </div>
            <a href="<?= base_url('superadmin/wpa') ?>" class="px-2.5 py-1 bg-white/[0.05] hover:bg-accent/10 text-accent hover:text-white border border-accent/20 hover:border-accent/40 rounded-lg text-[11px] font-bold transition flex items-center gap-1">
                <span>Lihat Semua</span>
                <i class="fas fa-chevron-right text-[9px]"></i>
            </a>
        </div>
        <div class="space-y-2">
            <?php if (empty($wpaList)): ?>
                <div class="text-center py-8 text-gray-500">
                    <div class="w-10 h-10 mx-auto rounded-xl bg-white/[0.03] border border-white/[0.06] flex items-center justify-center mb-2">
                        <i class="fas fa-user-tie text-gray-400 text-sm"></i>
                    </div>
                    <p class="text-xs">Belum ada WPA terdaftar</p>
                </div>
            <?php else: ?>
                <?php foreach (array_slice($wpaList, 0, 5) as $index => $wpa): ?>
                    <?php
                    $wpaPhoto = $wpa['photo'] ?? '';
                    if ($wpaPhoto && !str_starts_with($wpaPhoto, 'http')) {
                        $wpaPhoto = preg_replace('/^writable\//', '', $wpaPhoto);
                        $wpaPhoto = base_url('file/' . $wpaPhoto);
                    } elseif (!$wpaPhoto) {
                        $wpaPhoto = base_url('images/default-avatar.png');
                    }
                    ?>
                    <div class="flex items-center justify-between p-2.5 sm:p-3 bg-black/40 hover:bg-black/70 border border-white/[0.05] hover:border-white/[0.12] rounded-xl gap-2.5 transition">
                        <div class="flex items-center gap-2.5 min-w-0 flex-1">
                            <!-- Rank Badge -->
                            <span class="w-5 h-5 shrink-0 rounded flex items-center justify-center text-[10px] font-extrabold <?= $index === 0 ? 'bg-amber-400 text-black shadow-[0_0_8px_rgba(251,191,36,0.5)]' : ($index === 1 ? 'bg-slate-300 text-black' : ($index === 2 ? 'bg-amber-700 text-white' : 'bg-white/10 text-gray-400')) ?>">
                                <?= $index + 1 ?>
                            </span>
                            <img src="<?= esc($wpaPhoto) ?>" onerror="this.src='<?= base_url('images/default-avatar.png') ?>'" alt="<?= esc($wpa['name']) ?>" class="w-8 h-8 shrink-0 rounded-lg object-cover border border-white/10">
                            <div class="min-w-0 flex-1">
                                <p class="font-bold text-xs text-white truncate"><?= esc(explode(',', $wpa['name'])[0]) ?></p>
                                <p class="text-[10px] sm:text-[11px] text-accent truncate mt-0.5"><?= esc($wpa['specialty'] ?? 'Wakil Penasihat Berjangka') ?></p>
                            </div>
                        </div>
                        <div class="shrink-0 flex items-center gap-1 px-2 py-0.5 bg-yellow-500/10 border border-yellow-500/20 rounded-md">
                            <i class="fas fa-star text-yellow-400 text-[10px]"></i>
                            <span class="text-yellow-400 font-extrabold text-[11px]"><?= number_format((float)($wpa['rating'] ?? 5.0), 1) ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Chart.js Engine & Scripting -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Global Chart.js Configuration for Dark Mode
    Chart.defaults.color = '#777777';
    Chart.defaults.font.family = "'Montserrat', sans-serif";

    // 1. Revenue Line Chart
    const revenueCanvas = document.getElementById('revenueChart');
    const revenueCtx = revenueCanvas.getContext('2d');

    // Create Gradient Fill
    const revGradient = revenueCtx.createLinearGradient(0, 0, 0, 300);
    revGradient.addColorStop(0, 'rgba(51, 232, 24, 0.28)');
    revGradient.addColorStop(1, 'rgba(51, 232, 24, 0.0)');

    let revenueChartInstance = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($revenueData['labels']) ?>,
            datasets: [{
                label: 'Pendapatan',
                data: <?= json_encode($revenueData['data']) ?>,
                borderColor: '#33e818',
                backgroundColor: revGradient,
                borderWidth: 2.5,
                fill: true,
                tension: 0.35,
                pointBackgroundColor: '#33e818',
                pointBorderColor: '#0c0c0c',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                pointHoverBackgroundColor: '#ffffff',
                pointHoverBorderColor: '#33e818',
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index',
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#111111',
                    titleColor: '#ffffff',
                    titleFont: { weight: 'bold', size: 12 },
                    bodyColor: '#33e818',
                    bodyFont: { weight: 'bold', size: 13 },
                    borderColor: 'rgba(51, 232, 24, 0.4)',
                    borderWidth: 1,
                    padding: 10,
                    boxPadding: 4,
                    cornerRadius: 10,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.raw.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.04)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#666',
                        font: { size: 10 }
                    }
                },
                y: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.04)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#666',
                        font: { size: 10 },
                        callback: function(value) {
                            if (value >= 1000000) return (value / 1000000).toFixed(0) + 'jt';
                            if (value >= 1000) return (value / 1000).toFixed(0) + 'rb';
                            return value;
                        }
                    }
                }
            }
        }
    });

    // 2. User Growth Bar Chart
    const userCanvas = document.getElementById('userGrowthChart');
    const userCtx = userCanvas.getContext('2d');

    let userGrowthChartInstance = new Chart(userCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($userGrowthData['labels']) ?>,
            datasets: [{
                label: 'User Baru',
                data: <?= json_encode($userGrowthData['data']) ?>,
                backgroundColor: 'rgba(51, 232, 24, 0.75)',
                hoverBackgroundColor: '#33e818',
                borderColor: '#33e818',
                borderWidth: 1,
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index',
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#111111',
                    titleColor: '#ffffff',
                    titleFont: { weight: 'bold', size: 12 },
                    bodyColor: '#33e818',
                    bodyFont: { weight: 'bold', size: 13 },
                    borderColor: 'rgba(51, 232, 24, 0.4)',
                    borderWidth: 1,
                    padding: 10,
                    cornerRadius: 10,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return context.raw + ' user baru';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#666',
                        font: { size: 10 }
                    }
                },
                y: {
                    grid: {
                        color: 'rgba(255, 255, 255, 0.04)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#666',
                        font: { size: 10 },
                        stepSize: 1
                    },
                    beginAtZero: true
                }
            }
        }
    });

    // 3. Category Revenue Doughnut Chart
    const categoryCtx = document.getElementById('categoryRevenueChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($categoryRevenueData['labels']) ?>,
            datasets: [{
                data: <?= json_encode($categoryRevenueData['data']) ?>,
                backgroundColor: <?= json_encode($categoryRevenueData['colors']) ?>,
                borderColor: '#0c0c0c',
                borderWidth: 3,
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '72%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#888',
                        padding: 14,
                        boxWidth: 8,
                        boxHeight: 8,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            size: 10,
                            weight: '600'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: '#111111',
                    titleColor: '#ffffff',
                    bodyColor: '#33e818',
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    padding: 10,
                    cornerRadius: 10,
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const value = context.raw;
                            const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return ` Rp ${value.toLocaleString('id-ID')} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // Dynamic Filter Event Handlers
    document.getElementById('revenueFilter').addEventListener('change', async function() {
        const days = this.value;
        try {
            const response = await fetch(`<?= base_url('superadmin/dashboard/revenue-data') ?>?days=${days}`);
            const data = await response.json();

            if (revenueChartInstance) {
                revenueChartInstance.data.labels = data.labels;
                revenueChartInstance.data.datasets[0].data = data.data;
                revenueChartInstance.update();
            }
        } catch (error) {
            console.error('Error loading revenue data:', error);
        }
    });

    document.getElementById('userGrowthFilter').addEventListener('change', async function() {
        const days = this.value;
        try {
            const response = await fetch(`<?= base_url('superadmin/dashboard/user-growth-data') ?>?days=${days}`);
            const data = await response.json();

            if (userGrowthChartInstance) {
                userGrowthChartInstance.data.labels = data.labels;
                userGrowthChartInstance.data.datasets[0].data = data.data;
                userGrowthChartInstance.update();
            }
        } catch (error) {
            console.error('Error loading user growth data:', error);
        }
    });
</script>
<?= $this->endSection() ?>