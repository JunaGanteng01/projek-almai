<?= $this->extend('admin_wpa/layouts/main') ?>

<?= $this->section('content') ?>


<?php if (isset($message)): ?>
    <div class="bg-blue-500/10 border border-blue-500/20 rounded-xl p-6 text-center mb-6">
        <i class="fas fa-info-circle text-3xl text-blue-500 mb-2"></i>
        <p class="text-white"><?= $message ?></p>
        <p class="text-gray-400 text-sm mt-1">Hubungi Super Admin untuk penugasan WPA.</p>
    </div>
<?php else: ?>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-accent/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-wallet text-accent text-xl"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-white">Rp <?= number_format($totalEarnings, 0, ',', '.') ?></h3>
            <p class="text-gray-500 text-sm">Total Pendapatan (Confirmed)</p>
        </div>

        <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-accent/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-accent text-xl"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-white"><?= number_format($totalSales) ?></h3>
            <p class="text-gray-500 text-sm">Total Penjualan</p>
        </div>

        <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-accent/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-accent text-xl"></i>
                </div>
            </div>
            <h3 class="text-2xl font-bold text-white"><?= number_format($totalUsers) ?></h3>
            <p class="text-gray-500 text-sm">Total User</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Revenue Chart -->
        <div class="lg:col-span-1 bg-[#111] border border-white/10 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-white">Grafik Pendapatan</h3>
                <select id="revenueFilter" class="bg-black border border-white/20 rounded-lg px-2 py-1 text-xs text-white focus:border-accent outline-none">
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
        <div class="lg:col-span-1 bg-[#111] border border-white/10 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-white">Pertumbuhan User</h3>
                <select id="userGrowthFilter" class="bg-black border border-white/20 rounded-lg px-2 py-1 text-xs text-white focus:border-accent outline-none">
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
        <div class="lg:col-span-1 bg-[#111] border border-white/10 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-white">Pendapatan per Layanan</h3>
            </div>
            <div class="h-64">
                <canvas id="categoryRevenueChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Managed WPAs List -->
        <div class="lg:col-span-1 bg-[#111] border border-white/10 rounded-2xl p-6">
            <h3 class="font-bold mb-4 text-white">WPA yang Dikelola (<?= count($assignedWPAs) ?>)</h3>
            <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                <?php foreach ($wpaStats as $stat): ?>
                    <div class="flex items-center gap-3 p-3 bg-black/40 rounded-xl border border-white/5">
                        <img src="<?= esc($stat['photo'] ? (strpos($stat['photo'], 'http') === 0 ? $stat['photo'] : base_url('file/' . $stat['photo'])) : 'https://via.placeholder.com/100') ?>" 
                             class="w-10 h-10 rounded-full object-cover">
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-sm text-white truncate"><?= esc($stat['name']) ?></p>
                            <p class="text-xs text-accent">Rp <?= number_format($stat['earnings'], 0, ',', '.') ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-500"><?= $stat['sales'] ?> Sales</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="lg:col-span-2 bg-[#111] border border-white/10 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-bold text-white">Transaksi Terbaru</h3>
                <a href="<?= base_url('admin-wpa/transaksi') ?>" class="text-accent text-sm hover:underline">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-gray-500 text-sm border-b border-white/5">
                            <th class="pb-3 px-2">Tanggal</th>
                            <th class="pb-3 px-2">Produk</th>
                            <th class="pb-3 px-2">User</th>
                            <th class="pb-3 px-2">Nominal</th>
                            <th class="pb-3 px-2">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <?php if (empty($recentTransactions)): ?>
                            <tr>
                                <td colspan="5" class="py-10 text-center text-gray-500">Belum ada transaksi</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($recentTransactions as $trx): ?>
                                <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                                    <td class="py-4 px-2 text-gray-400">
                                        <?= date('d M Y', strtotime($trx['created_at'])) ?><br>
                                        <span class="text-[10px]"><?= date('H:i', strtotime($trx['created_at'])) ?></span>
                                    </td>
                                    <td class="py-4 px-2">
                                        <p class="font-medium text-white"><?= esc($trx['product_name']) ?></p>
                                        <p class="text-[10px] text-gray-500 uppercase"><?= $trx['product_type'] ?></p>
                                    </td>
                                    <td class="py-4 px-2">
                                        <p class="text-white"><?= esc($trx['user_name']) ?></p>
                                        <p class="text-[10px] text-gray-500"><?= $trx['payment_method'] ?></p>
                                    </td>
                                    <td class="py-4 px-2">
                                        <span class="font-bold text-accent">Rp <?= number_format($trx['total'], 0, ',', '.') ?></span>
                                    </td>
                                    <td class="py-4 px-2">
                                        <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase <?= $trx['status'] === 'confirmed' ? 'bg-accent/10 text-accent border border-accent/20' : 'bg-yellow-500/10 text-yellow-500 border border-yellow-500/20' ?>">
                                            <?= $trx['status'] ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Store chart instances for filtering
        let revenueChartInstance = null;
        let userGrowthChartInstance = null;

        document.addEventListener('DOMContentLoaded', function() {
            // Revenue Chart
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            revenueChartInstance = new Chart(revenueCtx, {
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
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#111',
                            callbacks: {
                                label: (context) => 'Rp ' + context.raw.toLocaleString('id-ID')
                            }
                        }
                    },
                    scales: {
                        x: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#666' } },
                        y: { 
                            grid: { color: 'rgba(255,255,255,0.05)' }, 
                            ticks: { 
                                color: '#666',
                                callback: (value) => value >= 1000000 ? 'Rp ' + (value/1000000) + 'jt' : 'Rp ' + (value/1000) + 'rb'
                            } 
                        }
                    }
                }
            });

            // User Growth Chart
            const userCtx = document.getElementById('userGrowthChart').getContext('2d');
            userGrowthChartInstance = new Chart(userCtx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($userGrowthData['labels']) ?>,
                    datasets: [{
                        label: 'User Baru',
                        data: <?= json_encode($userGrowthData['data']) ?>,
                        backgroundColor: 'rgba(51, 232, 24, 0.6)',
                        borderColor: '#33e818',
                        borderWidth: 1,
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#111',
                            callbacks: {
                                label: (context) => context.raw + ' user baru'
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: '#666' } },
                        y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#666', stepSize: 1 } }
                    }
                }
            });

            // Category Revenue Chart
            const categoryCtx = document.getElementById('categoryRevenueChart').getContext('2d');
            new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: <?= json_encode($categoryRevenueData['labels']) ?>,
                    datasets: [{
                        data: <?= json_encode($categoryRevenueData['data']) ?>,
                        backgroundColor: <?= json_encode($categoryRevenueData['colors']) ?>,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { color: '#666', boxWidth: 10, usePointStyle: true, font: { size: 10 } }
                        },
                        tooltip: {
                            backgroundColor: '#111',
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

            // Filter Handlers
            document.getElementById('revenueFilter').addEventListener('change', async function() {
                const days = this.value;
                try {
                    const response = await fetch(`<?= base_url('admin-wpa/dashboard/revenue-data') ?>?days=${days}`);
                    const data = await response.json();
                    revenueChartInstance.data.labels = data.labels;
                    revenueChartInstance.data.datasets[0].data = data.data;
                    revenueChartInstance.update();
                } catch (e) { console.error('Error:', e); }
            });

            document.getElementById('userGrowthFilter').addEventListener('change', async function() {
                const days = this.value;
                try {
                    const response = await fetch(`<?= base_url('admin-wpa/dashboard/user-growth-data') ?>?days=${days}`);
                    const data = await response.json();
                    userGrowthChartInstance.data.labels = data.labels;
                    userGrowthChartInstance.data.datasets[0].data = data.data;
                    userGrowthChartInstance.update();
                } catch (e) { console.error('Error:', e); }
            });
        });
    </script>
<?php endif; ?>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(51, 232, 24, 0.2); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(51, 232, 24, 0.4); }
</style>

<?= $this->endSection() ?>
