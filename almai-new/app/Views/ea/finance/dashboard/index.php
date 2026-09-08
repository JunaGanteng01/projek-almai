<?= $this->extend('keuangan/layouts/main') ?>

<?= $this->section('content') ?>
<?php $pageTitle = 'Dashboard Keuangan';
$activeMenu = 'dashboard'; ?>

<!-- Top Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <!-- Total Saldo -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 relative overflow-hidden group hover:border-green-500/30 transition duration-300">
        <div class="flex flex-col z-10 relative">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Total Saldo Keseluruhan</span>
            <h3 class="text-2xl font-black text-white mb-1">Rp <?= number_format($totalSaldoKasBank, 0, ',', '.') ?></h3>
            <span class="text-[10px] text-green-500 flex items-center gap-1 font-bold bg-green-500/10 w-fit px-2 py-0.5 rounded-full">
                <i class="fas fa-wallet text-[8px]"></i> Kas & Bank Total
            </span>
        </div>

    </div>

    <!-- Total In/Out -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 relative overflow-hidden group hover:border-blue-500/30 transition duration-300">
        <div class="flex flex-col z-10 relative h-full justify-between">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Arus Kas (Cashflow)</span>
            <div class="flex items-center gap-4">
                <div class="flex-1 p-2 bg-white/5 rounded-lg border border-white/5">
                    <span class="text-[9px] text-gray-400 block uppercase mb-0.5">Masuk</span>
                    <p class="text-sm font-bold text-accent">Rp <?= number_format($totalCashInOut['in'], 0, ',', '.') ?></p>
                </div>
                <div class="flex-1 p-2 bg-white/5 rounded-lg border border-white/5 text-right">
                    <span class="text-[9px] text-gray-400 block uppercase mb-0.5">Keluar</span>
                    <p class="text-sm font-bold text-red-500">Rp <?= number_format($totalCashInOut['out'], 0, ',', '.') ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Outstanding -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 relative overflow-hidden group hover:border-yellow-500/30 transition duration-300">
        <div class="flex flex-col z-10 relative">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Piutang (Invoice Pending)</span>
            <h3 class="text-2xl font-black text-white mb-1">Rp <?= number_format($outstandingInvoices, 0, ',', '.') ?></h3>
            <span class="text-[10px] text-yellow-500 flex items-center gap-1 font-bold bg-yellow-500/10 w-fit px-2 py-0.5 rounded-full">
                <i class="fas fa-clock text-[8px]"></i> Menunggu Pembayaran
            </span>
        </div>

    </div>
</div>

<!-- Xendit Stats Section -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <!-- Xendit Balance -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 relative overflow-hidden group hover:border-accent/30 transition duration-300">
        <div class="flex flex-col z-10 relative">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Saldo Xendit Saat Ini</span>
            <h3 class="text-2xl font-black text-white mb-1">Rp <?= number_format($xendit_stats['balance'], 0, ',', '.') ?></h3>
            <span class="text-[10px] text-accent flex items-center gap-1 font-bold bg-accent/10 w-fit px-2 py-0.5 rounded-full">
                <i class="fab fa-stripe-s text-[8px]"></i> Real-time API
            </span>
        </div>

    </div>

    <!-- Total Withdraw -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 relative overflow-hidden group hover:border-red-500/30 transition duration-300">
        <div class="flex flex-col z-10 relative">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Total Withdraw (Xendit)</span>
            <h3 class="text-2xl font-black text-white mb-1">Rp <?= number_format($xendit_stats['total_withdraw'], 0, ',', '.') ?></h3>
            <span class="text-[10px] text-red-400 flex items-center gap-1 font-bold bg-red-500/10 w-fit px-2 py-0.5 rounded-full">
                <i class="fas fa-external-link-alt text-[8px]"></i> Payouts Completed
            </span>
        </div>

    </div>

    <!-- Total Overall -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 relative overflow-hidden group hover:border-blue-500/30 transition duration-300">
        <div class="flex flex-col z-10 relative">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2">Total Volume Xendit</span>
            <h3 class="text-2xl font-black text-white mb-1">Rp <?= number_format($xendit_stats['total_volume'], 0, ',', '.') ?></h3>
            <span class="text-[10px] text-blue-400 flex items-center gap-1 font-bold bg-blue-500/10 w-fit px-2 py-0.5 rounded-full">
                <i class="fas fa-chart-bar text-[8px]"></i> Transaction Volume
            </span>
        </div>

    </div>
</div>

<!-- Detailed Metrics Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Assets Breakdown -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 lg:col-span-2 flex flex-col">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-lg flex items-center gap-2">
                <i class="fas fa-wallet text-accent"></i> Posisi Keuangan
            </h3>
            <span class="px-2 py-1 bg-white/5 rounded text-[10px] text-gray-400 font-mono"><?= date('F Y') ?></span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-auto">
            <?php if (empty($kasBankAccounts)): ?>
                <div class="col-span-2 md:col-span-3 text-center text-gray-500 py-4 text-xs">Belum ada akun Kas & Bank</div>
            <?php else: ?>
                <?php foreach ($kasBankAccounts as $account): ?>
                <div class="bg-gradient-to-br from-white/5 to-transparent rounded-xl p-5 border border-white/5 hover:border-accent/30 transition group flex flex-col justify-between">
                    <div class="flex items-start justify-between mb-3">
                        <div class="w-8 h-8 rounded-lg bg-accent/20 flex items-center justify-center text-accent group-hover:scale-110 transition">
                            <i class="fas fa-wallet text-sm"></i>
                        </div>
                    </div>
                    <div>
                        <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wider block mb-1 truncate" title="<?= esc($account['nama_akun']) ?>"><?= esc($account['nama_akun']) ?></span>
                        <p class="text-sm font-black text-white">Rp <?= number_format($account['balance'], 0, ',', '.') ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="mt-6 pt-6 border-t border-white/5 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Hutang -->
            <div class="flex items-center justify-between p-4 bg-red-500/5 border border-red-500/10 rounded-xl hover:bg-red-500/10 transition">
                <div>
                    <h4 class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Hutang (Liabilities)</h4>
                    <p class="text-lg font-black text-white">Rp <?= number_format($payables, 0, ',', '.') ?></p>
                </div>
                <div class="w-8 h-8 rounded bg-red-500/20 flex items-center justify-center text-red-500 text-xs">
                    <i class="fas fa-arrow-down"></i>
                </div>
            </div>
            <!-- Biaya -->
            <div class="flex items-center justify-between p-4 bg-orange-500/5 border border-orange-500/10 rounded-xl hover:bg-orange-500/10 transition">
                <div>
                    <h4 class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Biaya Operasional (Last Month)</h4>
                    <p class="text-lg font-black text-white">Rp <?= number_format($expensesLastMonth, 0, ',', '.') ?></p>
                </div>
                <div class="w-8 h-8 rounded bg-orange-500/20 flex items-center justify-center text-orange-500 text-xs">
                    <i class="fas fa-receipt"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue per Service -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 flex flex-col">
        <h3 class="font-bold text-lg mb-6 flex items-center gap-2">
            <i class="fas fa-chart-pie text-accent"></i> Pendapatan per Layanan
        </h3>
        <div class="space-y-5 overflow-y-auto max-h-[300px] scrollbar-hide flex-1">
            <?php if (empty($revenuePerService)): ?>
                <div class="flex flex-col items-center justify-center h-40 text-gray-500">
                    <i class="fas fa-chart-bar text-4xl mb-2 opacity-20"></i>
                    <p class="text-xs">Belum ada data pendapatan</p>
                </div>
            <?php else: ?>
                <?php
                $maxRev = 0;
                foreach ($revenuePerService as $rps) $maxRev += $rps['total'];
                ?>
                <?php foreach ($revenuePerService as $rps):
                    $percent = $maxRev > 0 ? ($rps['total'] / $maxRev) * 100 : 0;
                ?>
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <span class="font-bold text-xs text-gray-300 uppercase tracking-wide flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-accent"></span>
                                <?= esc($rps['product_type']) ?>
                            </span>
                            <span class="font-mono text-xs text-white">Rp <?= number_format($rps['total'], 0, ',', '.') ?></span>
                        </div>
                        <div class="w-full bg-white/5 rounded-full h-2">
                            <div class="bg-accent h-2 rounded-full transition-all duration-1000 shadow-[0_0_10px_rgba(51,232,24,0.3)]" style="width: <?= $percent ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="mt-8 pt-6 border-t border-white/5">
            <div class="flex items-center justify-between">
                <p class="text-xs text-gray-500 uppercase tracking-widest font-bold">Total Gross</p>
                <p class="text-xl font-black text-white">Rp <?= number_format($totalRevenue, 0, ',', '.') ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-1 gap-6 mb-6">
    <!-- Revenue Chart -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="font-bold text-lg">Tren Pendapatan Bulanan</h3>
            <select class="bg-black border border-white/10 rounded-lg px-3 py-1 text-xs text-gray-400 focus:outline-none">
                <option>Tahun Ini</option>
                <option>Tahun Lalu</option>
            </select>
        </div>
        <div class="h-80">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Transactions -->
<div class="bg-[#111] border border-white/10 rounded-2xl p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="font-bold text-lg">Transaksi Terbaru</h3>
       
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-gray-500">
            <thead class="text-xs text-gray-400 uppercase bg-black/50 border-b border-white/10">
                <tr>
                    <th scope="col" class="px-4 py-4">Keterangan</th>
                    <th scope="col" class="px-4 py-4">User / Entitas</th>
                    <th scope="col" class="px-4 py-4">Tanggal</th>
                    <th scope="col" class="px-4 py-4 text-right">Nominal</th>
                    <th scope="col" class="px-4 py-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($recentTransactions)): ?>
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center">Belum ada transaksi</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recentTransactions as $trx): ?>
                        <tr class="hover:bg-white/5 transition">
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center">
                                        <i class="fas fa-receipt text-gray-400"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-white"><?= esc($trx['product_name']) ?></p>
                                        <p class="text-xs text-gray-500"><?= esc($trx['product_type']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4"><?= esc($trx['user_name'] ?? 'Unknown') ?></td>
                            <td class="px-4 py-4 font-mono text-xs"><?= date('d M Y H:i', strtotime($trx['created_at'])) ?></td>
                            <td class="px-4 py-4 text-right font-black text-white">Rp <?= number_format($trx['total'], 0, ',', '.') ?></td>
                            <td class="px-4 py-4 text-center">
                                <span class="px-2 py-1 text-[10px] font-bold uppercase rounded-lg border <?= $trx['status'] === 'confirmed' ? 'bg-accent/10 text-accent border-accent/20' : ($trx['status'] === 'paid' ? 'bg-blue-500/10 text-blue-500 border-blue-500/20' : 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20') ?>">
                                    <?= ucfirst($trx['status']) ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');

    // Gradient
    let gradient = revenueCtx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(51, 232, 24, 0.2)');
    gradient.addColorStop(1, 'rgba(51, 232, 24, 0)');

    new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode($revenueData['labels']) ?>,
            datasets: [{
                label: 'Gross Revenue',
                data: <?= json_encode($revenueData['data']) ?>,
                borderColor: '#33e818',
                backgroundColor: gradient,
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#000',
                pointBorderColor: '#33e818',
                pointBorderWidth: 2,
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
                    backgroundColor: '#000',
                    titleColor: '#fff',
                    bodyColor: '#33e818',
                    borderColor: '#333',
                    borderWidth: 1,
                    padding: 10,
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
                        color: 'rgba(255,255,255,0.02)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#666',
                        font: {
                            size: 10
                        }
                    }
                },
                y: {
                    grid: {
                        color: 'rgba(255,255,255,0.05)',
                        borderDash: [5, 5],
                        drawBorder: false
                    },
                    ticks: {
                        color: '#666',
                        font: {
                            size: 10
                        },
                        callback: function(value) {
                            if (value >= 1000000) return (value / 1000000) + 'M';
                            if (value >= 1000) return (value / 1000) + 'K';
                            return value;
                        }
                    }
                }
            }
        }
    });
</script>
<?= $this->endSection() ?>