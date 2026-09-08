<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<?php $pageTitle = 'Dashboard';
$activeMenu = 'dashboard'; ?>

<!-- Stats Cards -->
<div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3 md:gap-4 mb-6">
    <!-- Total Revenue -->
    <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6">
        <div class="flex items-center justify-between mb-2 md:mb-4">
            <div class="w-10 h-10 md:w-12 md:h-12 bg-accent/20 rounded-lg md:rounded-xl flex items-center justify-center">
                <i class="fas fa-wallet text-accent text-lg md:text-xl"></i>
            </div>
        </div>
        <h3 class="text-lg md:text-2xl font-bold truncate" title="Rp <?= number_format($totalRevenue, 0, ',', '.') ?>">
            Rp <?= number_format($totalRevenue, 0, ',', '.') ?>
        </h3>
        <p class="text-gray-500 text-xs md:text-sm">Total Pendapatan</p>
    </div>

    <!-- Total Admin -->
    <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6">
        <div class="flex items-center justify-between mb-2 md:mb-4">
            <div class="w-10 h-10 md:w-12 md:h-12 bg-purple-500/20 rounded-lg md:rounded-xl flex items-center justify-center">
                <i class="fas fa-user-shield text-purple-500 text-lg md:text-xl"></i>
            </div>
        </div>
        <h3 class="text-xl md:text-3xl font-bold"><?= $totalAdmin ?></h3>
        <p class="text-gray-500 text-xs md:text-sm">Admin</p>
    </div>

    <!-- Total WPA -->
    <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6">
        <div class="flex items-center justify-between mb-2 md:mb-4">
            <div class="w-10 h-10 md:w-12 md:h-12 bg-blue-500/20 rounded-lg md:rounded-xl flex items-center justify-center">
                <i class="fas fa-user-tie text-blue-500 text-lg md:text-xl"></i>
            </div>
        </div>
        <h3 class="text-xl md:text-3xl font-bold"><?= $totalWpa ?></h3>
        <p class="text-gray-500 text-xs md:text-sm">WPA</p>
    </div>

    <!-- Total CWPA -->
    <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6">
        <div class="flex items-center justify-between mb-2 md:mb-4">
            <div class="w-10 h-10 md:w-12 md:h-12 bg-cyan-500/20 rounded-lg md:rounded-xl flex items-center justify-center">
                <i class="fas fa-user-graduate text-cyan-500 text-lg md:text-xl"></i>
            </div>
        </div>
        <h3 class="text-xl md:text-3xl font-bold"><?= $totalCwpa ?></h3>
        <p class="text-gray-500 text-xs md:text-sm">CWPA</p>
    </div>

    <!-- Total Users -->
    <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6">
        <div class="flex items-center justify-between mb-2 md:mb-4">
            <div class="w-10 h-10 md:w-12 md:h-12 bg-green-500/20 rounded-lg md:rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-green-500 text-lg md:text-xl"></i>
            </div>
        </div>
        <h3 class="text-xl md:text-3xl font-bold"><?= number_format($totalUsers) ?></h3>
        <p class="text-gray-500 text-xs md:text-sm">User</p>
    </div>

    <!-- Total User PRO -->
    <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6">
        <div class="flex items-center justify-between mb-2 md:mb-4">
            <div class="w-10 h-10 md:w-12 md:h-12 bg-yellow-500/20 rounded-lg md:rounded-xl flex items-center justify-center">
                <i class="fas fa-crown text-yellow-500 text-lg md:text-xl"></i>
            </div>
        </div>
        <h3 class="text-xl md:text-3xl font-bold"><?= $totalUserPro ?></h3>
        <p class="text-gray-500 text-xs md:text-sm">User PRO</p>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mb-6">
    <!-- Revenue Chart -->
    <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 lg:col-span-1">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-sm md:text-base">Grafik Pendapatan</h3>
            <select id="revenueFilter" class="bg-black border border-white/20 rounded-lg px-3 py-1.5 text-xs focus:border-accent focus:outline-none">
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
    <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 lg:col-span-1">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-sm md:text-base">Pertumbuhan User</h3>
            <select id="userGrowthFilter" class="bg-black border border-white/20 rounded-lg px-3 py-1.5 text-xs focus:border-accent focus:outline-none">
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
    <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 lg:col-span-1">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-sm md:text-base">Pendapatan per Layanan</h3>
            <span class="text-xs text-gray-500">Berdasarkan Subkategori</span>
        </div>
        <div class="h-64 flex items-center justify-center">
            <canvas id="categoryRevenueChart"></canvas>
        </div>
    </div>
</div>

<!-- Bottom Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6">
    <!-- Recent Transactions -->
    <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-sm md:text-base">Transaksi Terbaru</h3>
            <a href="<?= base_url('admin/transaksi') ?>" class="text-accent text-xs hover:underline">Lihat Semua</a>
        </div>
        <div class="space-y-3">
            <?php if (empty($recentTransactions)): ?>
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-receipt text-3xl mb-2"></i>
                    <p class="text-sm">Belum ada transaksi</p>
                </div>
            <?php else: ?>
                <?php foreach ($recentTransactions as $trx): ?>
                    <div class="flex items-center justify-between p-3 bg-black/50 rounded-xl gap-3">
                        <div class="flex items-center gap-3 min-w-0 flex-1">
                            <div class="w-8 h-8 md:w-10 md:h-10 shrink-0 <?= $trx['status'] === 'confirmed' ? 'bg-accent/20' : ($trx['status'] === 'paid' ? 'bg-blue-500/20' : 'bg-yellow-500/20') ?> rounded-full flex items-center justify-center">
                                <i class="fas fa-<?= $trx['product_type'] === 'kelas' ? 'graduation-cap' : ($trx['product_type'] === 'tools' ? 'tools' : 'newspaper') ?> <?= $trx['status'] === 'confirmed' ? 'text-accent' : ($trx['status'] === 'paid' ? 'text-blue-500' : 'text-yellow-500') ?> text-sm"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-sm line-clamp-1 truncate"><?= esc($trx['product_name']) ?></p>
                                <p class="text-xs text-gray-500 truncate"><?= esc($trx['user_name'] ?? 'Unknown') ?> • <?= date('d M H:i', strtotime($trx['created_at'])) ?></p>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <span class="font-bold text-xs md:text-sm whitespace-nowrap">
                                <?php if (($trx['payment_method'] ?? '') === 'poin'): ?>
                                    <span class="text-yellow-400"><?= number_format($trx['total'], 0, ',', '.') ?> <i class="fas fa-coins ml-1"></i></span>
                                <?php else: ?>
                                    <span class="text-accent">Rp <?= number_format($trx['total'], 0, ',', '.') ?></span>
                                <?php endif; ?>
                            </span>
                            <p class="text-xs <?= $trx['status'] === 'confirmed' ? 'text-accent' : ($trx['status'] === 'paid' ? 'text-blue-400' : 'text-yellow-400') ?>"><?= ucfirst($trx['status']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Top WPA -->
    <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-sm md:text-base">WPA Terpopuler</h3>
            <a href="<?= base_url('admin/wpa') ?>" class="text-accent text-xs hover:underline">Lihat Semua</a>
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
                        // Remove 'writable/' prefix if exists, then add base_url with file/
                        $wpaPhoto = preg_replace('/^writable\//', '', $wpaPhoto);
                        $wpaPhoto = base_url('file/' . $wpaPhoto);
                    } elseif (!$wpaPhoto) {
                        $wpaPhoto = 'https://via.placeholder.com/100';
                    }
                    ?>
                    <div class="flex items-center justify-between p-3 bg-black/50 rounded-xl gap-3">
                        <div class="flex items-center gap-3 min-w-0 flex-1">
                            <span class="w-6 h-6 shrink-0 bg-accent/20 rounded-full flex items-center justify-center text-accent text-xs font-bold"><?= $index + 1 ?></span>
                            <img src="<?= esc($wpaPhoto) ?>" alt="<?= esc($wpa['name']) ?>" class="w-10 h-10 shrink-0 rounded-full object-cover">
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-sm truncate"><?= esc(explode(',', $wpa['name'])[0]) ?></p>
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
                backgroundColor: 'rgba(51, 232, 24, 0.1)',
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
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#111',
                    titleColor: '#fff',
                    bodyColor: '#33e818',
                    borderColor: '#333',
                    borderWidth: 1,
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
                        color: 'rgba(255,255,255,0.05)'
                    },
                    ticks: {
                        color: '#666'
                    }
                },
                y: {
                    grid: {
                        color: 'rgba(255,255,255,0.05)'
                    },
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
                backgroundColor: 'rgba(51, 232, 24, 0.6)',
                borderColor: '#33e818',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#111',
                    titleColor: '#fff',
                    bodyColor: '#33e818',
                    borderColor: '#333',
                    borderWidth: 1,
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
                        color: '#666'
                    }
                },
                y: {
                    grid: {
                        color: 'rgba(255,255,255,0.05)'
                    },
                    ticks: {
                        color: '#666',
                        stepSize: 1
                    },
                    beginAtZero: true
                }
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
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#666',
                        padding: 15,
                        boxWidth: 10,
                        usePointStyle: true,
                        font: {
                            size: 10
                        }
                    }
                },
                tooltip: {
                    backgroundColor: '#111',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#333',
                    borderWidth: 1,
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

    // Store initial chart instances
    window.addEventListener('DOMContentLoaded', () => {
        revenueChartInstance = Chart.getChart('revenueChart');
        userGrowthChartInstance = Chart.getChart('userGrowthChart');
    });

    // Revenue Filter
    document.getElementById('revenueFilter').addEventListener('change', async function() {
        const days = this.value;
        try {
            const response = await fetch(`<?= base_url('admin/dashboard/revenue-data') ?>?days=${days}`);
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

    // User Growth Filter
    document.getElementById('userGrowthFilter').addEventListener('change', async function() {
        const days = this.value;
        try {
            const response = await fetch(`<?= base_url('admin/dashboard/user-growth-data') ?>?days=${days}`);
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