<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Main Details Section -->
<?php $isEmbed = isset($_GET['embed']) && $_GET['embed'] == '1'; ?>
<section class="py-16 bg-transparent min-h-screen font-sans <?= $isEmbed ? 'mt-0 py-8' : 'mt-20' ?>">
    <div class="w-full px-4 sm:px-6 lg:px-8 max-w-full">

        <!-- Main Card Section (Horizontal Layout MQL5 Style) -->
        <div class="mb-8 relative">
            <!-- Identity Top Bar -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-lg border border-white/10 overflow-hidden bg-[#222]">
                        <?php 
                        // Debug: uncomment to see logo value
                        // echo '<!-- Logo value: ' . ($porto['logo'] ?? 'NULL') . ' -->';
                        
                        $logoUrl = '';
                        if (!empty($porto['logo'])) {
                            // Remove 'writable/' prefix if exists
                            $logoUrl = base_url(str_replace('writable/', '', $porto['logo']));
                            // Debug: uncomment to see final URL
                            // echo '<!-- Logo URL: ' . $logoUrl . ' -->';
                        }
                        ?>
                        <?php if ($logoUrl): ?>
                            <img src="<?= $logoUrl ?>" alt="<?= esc($porto['name']) ?>" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($porto['author']) ?>&background=random'">
                        <?php else: ?>
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($porto['author']) ?>&background=random" class="w-full h-full object-cover">
                        <?php endif; ?>
                    </div>
                    <div>
                        <h1 class="text-xl font-black text-blue-500 leading-tight"><?= esc($porto['name']) ?></h1>
                        <div class="flex items-center gap-3 text-[11px] text-gray-400 mt-1 uppercase tracking-wider font-bold">
                            <span class="flex items-center gap-1"><i class="fas fa-users text-accent"></i> <?= esc($porto['community_name'] ?? 'ALMAI Community') ?></span>
                            <span class="text-gray-700">|</span>
                            <span><?= esc($porto['author']) ?></span>
                            <span class="text-gray-700">|</span>
                            <span class="text-yellow-500 flex items-center gap-0.5"><i class="fas fa-star text-[9px]"></i><i class="fas fa-star text-[9px]"></i><i class="fas fa-star text-[9px]"></i><i class="fas fa-star text-[9px]"></i><i class="fas fa-star text-[9px]"></i> <span class="ml-1 text-gray-500">0 reviews</span></span>
                            <span class="text-gray-700">|</span>
                            <span class="text-green-500 flex items-center gap-1"><i class="fas fa-bars"></i> Reliability</span>
                            <span class="text-gray-700">|</span>
                            <span class="text-green-500"><?= $porto['weeks'] ?> weeks</span>
                            <span class="text-gray-700">|</span>
                            <span class="text-blue-500">1:<?= $porto['leverage'] ?></span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <button id="sharePortoBtn" class="flex items-center gap-2 px-4 py-2 bg-blue-600/10 border border-blue-500/20 text-blue-500 rounded-lg hover:bg-blue-600 hover:text-white transition-all duration-300 font-bold text-xs uppercase tracking-wider">
                        <i class="fas fa-share-alt"></i> Share
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <!-- 1. LEFT: Growth Chart -->
                <div class="flex flex-col bg-black/30 rounded-xl p-6 border border-white/5">
                    <div class="mb-4 text-right">
                        <div class="text-[11px] text-gray-500 uppercase font-bold mb-2 tracking-wider">growth since <?= $porto['growth_since'] ?></div>
                        <div class="<?= $porto['growth'] >= 0 ? 'text-green-500' : 'text-red-500' ?> text-5xl font-black leading-none mb-1">
                            <?= $porto['growth'] >= 0 ? '+' : '-' ?><?= number_format(abs($porto['growth']), 2) ?>%
                        </div>
                        <div class="text-[10px] text-gray-600 uppercase tracking-widest">
                            <?= $porto['growth'] >= 0 ? 'Profit' : 'Loss' ?>: $<?= number_format(abs($porto['profit']), 2) ?>
                        </div>
                    </div>
                    <div class="h-[180px] w-full relative">
                        <canvas id="growthChart"></canvas>
                    </div>
                    <div class="flex justify-between items-center text-[10px] text-gray-400 mt-4 pt-4 border-t border-white/10 font-bold uppercase tracking-wider">
                        <div class="flex items-center gap-1.5 text-green-500">
                            <i class="fas fa-check-circle"></i> 
                            <span class="truncate max-w-[150px]"><?= esc($porto['broker']) ?></span>
                        </div>
                        <div class="text-blue-500">1:<?= $porto['leverage'] ?></div>
                    </div>
                </div>

                <!-- 2. CENTER: Radar Chart -->
                <div class="flex justify-center items-center">
                    <div class="w-[220px] h-[220px] relative">
                        <canvas id="radarChart"></canvas>
                    </div>
                </div>

                <!-- 3. RIGHT: Financial Bars -->
                <div class="flex flex-col gap-4 bg-black/30 rounded-xl p-6 border border-white/5">
                    <?php 
                        $max_val = max($porto['equity'], $porto['initial_deposit'], $porto['profit'], 100); 
                    ?>
                    <div class="space-y-4">
                        <!-- Stat Row -->
                        <div class="flex items-center justify-end gap-3 h-7">
                            <span class="text-[11px] text-gray-400 font-bold w-28 text-right">Equity</span>
                            <span class="text-[11px] text-white font-mono w-28 text-right"><?= number_format($porto['equity'], 2) ?> USD</span>
                            <div class="w-36 h-5 bg-white/5 rounded-sm overflow-hidden">
                                <div class="stat-bar h-full bg-gradient-to-r from-cyan-500 to-cyan-400" style="width: <?= ($porto['equity'] / $max_val) * 100 ?>%"></div>
                            </div>
                        </div>
                        <!-- Stat Row -->
                        <div class="flex items-center justify-end gap-3 h-7">
                            <span class="text-[11px] text-gray-400 font-bold w-28 text-right">Profit</span>
                            <span class="text-[11px] <?= $porto['profit'] >= 0 ? 'text-green-500' : 'text-red-500' ?> font-mono w-28 text-right font-bold">
                                <?= $porto['profit'] >= 0 ? '+' : '' ?><?= number_format($porto['profit'], 2) ?> USD
                            </span>
                            <div class="w-36 h-5 bg-white/5 rounded-sm overflow-hidden">
                                <div class="stat-bar h-full <?= $porto['profit'] >= 0 ? 'bg-gradient-to-r from-green-500 to-green-400' : 'bg-gradient-to-r from-red-500 to-red-400' ?>" 
                                     style="width: <?= max(5, (abs($porto['profit']) / $max_val) * 100) ?>%"></div>
                            </div>
                        </div>
                        <!-- Stat Row -->
                        <div class="flex items-center justify-end gap-3 h-7">
                            <span class="text-[11px] text-gray-400 font-bold w-28 text-right">Initial Deposit</span>
                            <span class="text-[11px] text-white font-mono w-28 text-right"><?= number_format($porto['initial_deposit'], 2) ?> USD</span>
                            <div class="w-36 h-5 bg-white/5 rounded-sm overflow-hidden">
                                <div class="stat-bar h-full bg-gradient-to-r from-blue-500 to-blue-400" style="width: <?= ($porto['initial_deposit'] / $max_val) * 100 ?>%"></div>
                            </div>
                        </div>
                        <!-- Stat Row -->
                        <div class="flex items-center justify-end gap-3 h-7 pt-3 border-t border-white/10">
                            <span class="text-[11px] text-gray-400 font-bold w-28 text-right">Withdrawals</span>
                            <span class="text-[11px] text-white font-mono w-28 text-right"><?= number_format($porto['withdrawals'], 2) ?> USD</span>
                            <div class="w-36 h-5 bg-white/5 rounded-sm overflow-hidden">
                                <div class="stat-bar h-full bg-gradient-to-r from-orange-500 to-orange-400" style="width: <?= min(100, ($porto['withdrawals'] / max($porto['deposits'], $porto['withdrawals'], 1)) * 100) ?>%"></div>
                            </div>
                        </div>
                        <!-- Stat Row -->
                        <div class="flex items-center justify-end gap-3 h-7">
                            <span class="text-[11px] text-gray-400 font-bold w-28 text-right">Deposits</span>
                            <span class="text-[11px] text-white font-mono w-28 text-right"><?= number_format($porto['deposits'], 2) ?> USD</span>
                            <div class="w-36 h-5 bg-white/5 rounded-sm overflow-hidden">
                                <div class="stat-bar h-full bg-gradient-to-r from-cyan-500 to-cyan-400" style="width: <?= min(100, ($porto['deposits'] / max($porto['deposits'], $porto['withdrawals'], 1)) * 100) ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Bottom Tabs & Detail Area -->
        <div class="bg-[#111] rounded-2xl border border-white/10 overflow-hidden shadow-xl" id="portfolio-tabs">
            <!-- Tab Navigation -->
            <div class="flex overflow-x-auto border-b border-white/10 hide-scrollbar" role="tablist">
                <button class="tab-btn px-6 py-4 border-b-2 border-accent text-accent font-bold whitespace-nowrap" data-target="tab-account">Akun</button>
                <button class="tab-btn px-6 py-4 border-b-2 border-transparent text-gray-400 hover:text-white transition whitespace-nowrap" data-target="tab-history">Riwayat Trading</button>
                <button class="tab-btn px-6 py-4 border-b-2 border-transparent text-gray-400 hover:text-white transition whitespace-nowrap" data-target="tab-stats">Statistik</button>
                <button class="tab-btn px-6 py-4 border-b-2 border-transparent text-gray-400 hover:text-white transition whitespace-nowrap" data-target="tab-risks">Risiko</button>
                <button class="tab-btn px-6 py-4 border-b-2 border-transparent text-gray-400 hover:text-white transition whitespace-nowrap" data-target="tab-slippage">Slippage</button>

            </div>
            
            <!-- Tab Contents -->
            <div class="p-6 md:p-8 min-h-[300px] relative">
                
                <!-- ACCOUNT TAB -->
                <div id="tab-account" class="tab-content active">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-white">Ringkasan Grafik Ekuitas Berjalan</h3>
                        
                        <!-- Toggle Buttons like MQL5 -->
                        <div class="flex gap-2">
                            <button id="btnGrowth" class="chart-toggle-btn active px-4 py-2 rounded-lg text-sm font-bold transition-all">
                                Growth
                            </button>
                            <button id="btnBalance" class="chart-toggle-btn px-4 py-2 rounded-lg text-sm font-bold transition-all">
                                Balance
                            </button>
                        </div>
                    </div>
                    
                    <!-- Summary Boxes (like MQL5) -->
                    <div class="grid grid-cols-4 gap-4 mb-6">
                        <!-- Growth Box -->
                        <div class="bg-white/5 border border-white/10 rounded-lg p-3">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-2 h-2 rounded-full <?= $porto['growth'] >= 0 ? 'bg-blue-500' : 'bg-red-500' ?>"></div>
                                <div class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Growth</div>
                            </div>
                            <div class="<?= $porto['growth'] >= 0 ? 'text-blue-500' : 'text-red-500' ?> text-2xl font-black mb-0.5">
                                <?= $porto['growth'] >= 0 ? '' : '-' ?><?= number_format(abs($porto['growth']), 2) ?>%
                            </div>
                            <div class="text-[10px] text-gray-500">Since <?= $porto['growth_since'] ?></div>
                        </div>
                        
                        <!-- Average Box -->
                        <div class="bg-white/5 border border-white/10 rounded-lg p-3">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-2 h-2 rounded-full bg-gray-400"></div>
                                <div class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Average</div>
                            </div>
                            <div class="text-gray-300 text-2xl font-black mb-0.5">
                                <?= number_format($porto['stats']['win_rate'], 2) ?>%
                            </div>
                            <div class="text-[10px] text-gray-500"><?= $porto['stats']['profit_trades'] ?> / <?= $porto['stats']['total_trades'] ?> trades</div>
                        </div>
                        
                        <!-- Deposits Box -->
                        <div class="bg-white/5 border border-white/10 rounded-lg p-3">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-2 h-2 rounded-full bg-orange-500"></div>
                                <div class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Deposits</div>
                            </div>
                            <div class="text-orange-500 text-2xl font-black mb-0.5">
                                <?= number_format($porto['deposits'], 0) ?>
                            </div>
                            <div class="text-[10px] text-gray-500"><?= $porto['currency'] ?></div>
                        </div>
                        
                        <!-- Withdrawals Box -->
                        <div class="bg-white/5 border border-white/10 rounded-lg p-3">
                            <div class="flex items-center gap-2 mb-2">
                                <div class="w-2 h-2 rounded-full bg-red-500"></div>
                                <div class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">Withdrawals</div>
                            </div>
                            <div class="text-red-500 text-2xl font-black mb-0.5">
                                <?= number_format($porto['withdrawals'], 0) ?>
                            </div>
                            <div class="text-[10px] text-gray-500"><?= $porto['currency'] ?></div>
                        </div>
                    </div>
                    
                    <div class="mt-4 relative w-full border border-white/5 bg-black/50 rounded-xl p-6">
                        <!-- Chart Container -->
                        <div class="h-[450px] w-full">
                            <canvas id="historyChart"></canvas>
                        </div>
                    </div>
                    
                    <!-- Second Chart: Equity & Drawdown -->
                    <div class="mt-6 relative w-full border border-white/5 bg-black/50 rounded-xl p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-lg font-bold text-white">Equity & Drawdown</h4>
                            <div class="flex gap-2">
                                <button id="btnEquity" class="chart-toggle-btn active px-4 py-2 rounded-lg text-sm font-bold transition-all">
                                    Equity
                                </button>
                                <button id="btnDrawdown" class="chart-toggle-btn px-4 py-2 rounded-lg text-sm font-bold transition-all">
                                    Drawdown
                                </button>
                            </div>
                        </div>
                        <div class="h-[350px] w-full">
                            <canvas id="equityDrawdownChart"></canvas>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-8 p-6 bg-white/5 border border-white/5 rounded-xl">
                        <div>
                            <p class="text-gray-500 text-xs uppercase font-bold mb-1">Mata Uang Dasar</p>
                            <p class="text-white text-lg font-mono font-bold"><?= esc($porto['currency'] ?? 'USD') ?></p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs uppercase font-bold mb-1">Margin Level</p>
                            <p class="text-blue-500 text-lg font-mono font-bold"><?= number_format($porto['margin_level'], 2) ?>%</p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs uppercase font-bold mb-1">Open Trades</p>
                            <p class="text-yellow-500 text-lg font-mono font-bold"><?= $porto['open_trades'] ?></p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-xs uppercase font-bold mb-1">Koneksi EA</p>
                            <?php 
                                // Anggap online jika update kurang dari 5 menit lalu
                                $isOnline = (time() - strtotime($porto['updated_at'])) < 300; 
                            ?>
                            <p class="<?= $isOnline ? 'text-green-500' : 'text-gray-500' ?> text-lg font-mono font-bold">
                                <i class="fas fa-circle text-[10px] <?= $isOnline ? 'animate-pulse' : '' ?>"></i> 
                                <?= $isOnline ? 'Online' : 'Offline' ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- HISTORY TAB -->
                <div id="tab-history" class="tab-content hidden">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-white">Riwayat Trading</h3>
                        <div class="bg-[#111] border border-white/10 text-gray-400 px-3 py-1.5 rounded-lg text-sm">
                            Menampilkan <?= count($porto['history']) ?> transaksi (Terbaru di atas)
                        </div>
                    </div>

                    <div class="overflow-x-auto relative shadow-md sm:rounded-lg border border-white/10">
                        <table class="w-full text-sm text-left text-gray-400">
                            <thead class="text-xs text-gray-300 uppercase bg-white/5 border-b border-white/10">
                                <tr>
                                    <th scope="col" class="py-4 px-6">Symbol</th>
                                    <th scope="col" class="py-4 px-6">Open Time</th>
                                    <th scope="col" class="py-4 px-6">Close Time</th>
                                    <th scope="col" class="py-4 px-6 text-center">Type</th>
                                    <th scope="col" class="py-4 px-6 text-right">Volume</th>
                                    <th scope="col" class="py-4 px-6 text-right">Open Price</th>
                                    <th scope="col" class="py-4 px-6 text-right">S/L</th>
                                    <th scope="col" class="py-4 px-6 text-right">T/P</th>
                                    <th scope="col" class="py-4 px-6 text-right">Close Price</th>
                                    <th scope="col" class="py-4 px-6 text-right">Swap</th>
                                    <th scope="col" class="py-4 px-6 text-right">Profit</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($porto['history'])): ?>
                                <tr>
                                    <td colspan="11" class="py-12 text-center text-gray-500">
                                        <i class="fas fa-history text-3xl mb-3 block"></i>
                                        Belum ada riwayat transaksi terekam untuk akun ini.
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach ($porto['history'] as $trade): 
                                        $isProfit = $trade['profit'] > 0;
                                    ?>
                                    <tr class="bg-[#111] border-b border-white/5 hover:bg-white/5 transition">
                                        <td class="py-4 px-6 font-bold text-white"><?= esc($trade['symbol']) ?></td>
                                        <td class="py-4 px-6 font-mono text-xs"><?= $trade['open_time'] ?></td>
                                        <td class="py-4 px-6 font-mono text-xs"><?= $trade['close_time'] ?? '-' ?></td>
                                        <td class="py-4 px-6 text-center">
                                            <span class="px-2.5 py-1 rounded text-xs font-bold <?= $trade['type'] === 'BUY' ? 'bg-blue-500/10 text-blue-500' : 'bg-red-500/10 text-red-500' ?>">
                                                <?= $trade['type'] ?>
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-right font-mono"><?= number_format($trade['lots'], 2) ?></td>
                                        <td class="py-4 px-6 text-right font-mono"><?= number_format($trade['open_price'], 5) ?></td>
                                        <td class="py-4 px-6 text-right font-mono text-gray-500"><?= isset($trade['sl']) && $trade['sl'] > 0 ? number_format($trade['sl'], 5) : '-' ?></td>
                                        <td class="py-4 px-6 text-right font-mono text-gray-500"><?= isset($trade['tp']) && $trade['tp'] > 0 ? number_format($trade['tp'], 5) : '-' ?></td>
                                        <td class="py-4 px-6 text-right font-mono"><?= number_format($trade['close_price'], 5) ?></td>
                                        <td class="py-4 px-6 text-right font-mono text-gray-400"><?= isset($trade['swap']) ? number_format($trade['swap'], 2) : '0.00' ?></td>
                                        <td class="py-4 px-6 text-right font-mono font-bold <?= $isProfit ? 'text-green-500' : 'text-red-500' ?>">
                                            <?= $isProfit ? '+' : '' ?><?= number_format($trade['profit'], 2) ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if (!empty($porto['history'])): ?>
                    <div class="mt-6">
                        <?= $porto['pager']->links('history', 'tailwind_pagination') ?>
                    </div>
                    <?php endif; ?>
                </div>

                 <!-- STATISTICS TAB (MQL5 STYLE) -->
                <div id="tab-stats" class="tab-content hidden animate-fade-in text-[12px] text-gray-300">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-0 mb-12">
                        
                        <!-- Left Column -->
                        <div class="space-y-0.5 pr-12 lg:border-r lg:border-white/10">
                            <div class="flex justify-between items-center border-b border-white/5 py-1.5">
                                <span class="text-gray-400">Trades:</span>
                                <span class="font-bold text-white"><?= $porto['stats']['total_trades'] ?></span>
                            </div>
                            <div class="flex justify-between items-center border-b border-white/5 py-1.5">
                                <span class="text-gray-400">Profit Trades:</span>
                                <span class="text-white"><span class="font-bold"><?= $porto['stats']['profit_trades'] ?></span> (<?= number_format($porto['stats']['win_rate'], 2) ?>%)</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-white/5 py-1.5">
                                <span class="text-gray-400">Loss Trades:</span>
                                <span class="text-white"><span class="font-bold"><?= $porto['stats']['loss_trades'] ?></span> (<?= number_format(100 - $porto['stats']['win_rate'], 2) ?>%)</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-white/5 py-1.5 mt-3">
                                <span class="text-gray-400">Best trade:</span>
                                <span class="font-bold text-blue-400"><?= number_format($porto['stats']['best_trade'], 2) ?> <?= $porto['currency'] ?></span>
                            </div>
                            <div class="flex justify-between items-center border-b border-white/5 py-1.5">
                                <span class="text-gray-400">Worst trade:</span>
                                <span class="font-bold text-red-500"><?= number_format($porto['stats']['worst_trade'], 2) ?> <?= $porto['currency'] ?></span>
                            </div>
                            <div class="flex justify-between items-center border-b border-white/5 py-1.5 mt-3">
                                <span class="text-gray-400">Gross Profit:</span>
                                <span class="font-bold text-white"><?= number_format($porto['stats']['gross_profit'], 2) ?> <?= $porto['currency'] ?></span>
                            </div>
                            <div class="flex justify-between items-center border-b border-white/5 py-1.5">
                                <span class="text-gray-400">Gross Loss:</span>
                                <span class="font-bold text-red-400"><?= number_format($porto['stats']['gross_loss'], 2) ?> <?= $porto['currency'] ?></span>
                            </div>
                            <div class="flex justify-between items-center border-b border-white/5 py-1.5 mt-3">
                                <span class="text-gray-400">Maximum consecutive wins:</span>
                                <span class="text-white"><span class="font-bold"><?= $porto['stats']['max_cons_wins'] ?></span> (69.85 <?= $porto['currency'] ?>)</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-white/5 py-1.5">
                                <span class="text-gray-400">Maximal consecutive profit:</span>
                                <span class="text-white"><span class="font-bold">69.85 <?= $porto['currency'] ?></span> (<?= $porto['stats']['max_cons_wins'] ?>)</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-white/5 py-1.5 mt-3">
                                <span class="text-gray-400">Sharpe Ratio:</span>
                                <span class="font-bold text-white">0.20</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-white/5 py-1.5">
                                <span class="text-gray-400">Trading activity:</span>
                                <span class="font-bold text-white"><?= number_format($porto['trading_activity'], 2) ?>%</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-white/5 py-1.5">
                                <span class="text-gray-400">Max deposit load:</span>
                                <span class="font-bold text-white"><?= number_format($porto['max_deposit_load'], 2) ?>%</span>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-0.5 pl-12">
                            <div class="flex justify-between items-center border-b border-white/5 py-1.5">
                                <span class="text-gray-400">Recovery Factor:</span>
                                <span class="font-bold text-white">8.88</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-white/5 py-1.5">
                                <span class="text-gray-400">Long Trades:</span>
                                <span class="text-white"><span class="font-bold"><?= $porto['stats']['long_trades'] ?></span> (<?= $porto['stats']['total_trades'] > 0 ? number_format($porto['stats']['long_trades']/$porto['stats']['total_trades']*100, 2) : 0 ?>%)</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-white/5 py-1.5">
                                <span class="text-gray-400">Short Trades:</span>
                                <span class="text-white"><span class="font-bold"><?= $porto['stats']['short_trades'] ?></span> (<?= $porto['stats']['total_trades'] > 0 ? number_format($porto['stats']['short_trades']/$porto['stats']['total_trades']*100, 2) : 0 ?>%)</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-white/5 py-1.5 mt-3">
                                <span class="text-gray-400">Profit Factor:</span>
                                <span class="font-bold text-white"><?= number_format($porto['stats']['profit_factor'], 2) ?></span>
                            </div>
                            <div class="flex justify-between items-center border-b border-white/5 py-1.5">
                                <span class="text-gray-400">Expected Payoff:</span>
                                <span class="font-bold text-white"><?= $porto['stats']['total_trades'] > 0 ? number_format($porto['profit'] / $porto['stats']['total_trades'], 2) : 0 ?> <?= $porto['currency'] ?></span>
                            </div>
                            <div class="flex justify-between items-center border-b border-white/5 py-1.5 mt-3">
                                <span class="text-gray-400">Average Profit:</span>
                                <span class="font-bold text-white"><?= number_format($porto['stats']['gross_profit'] / max(1, $porto['stats']['profit_trades']), 2) ?> <?= $porto['currency'] ?></span>
                            </div>
                            <div class="flex justify-between items-center border-b border-white/5 py-1.5">
                                <span class="text-gray-400">Average Loss:</span>
                                <span class="font-bold text-red-400"><?= number_format($porto['stats']['gross_loss'] / max(1, $porto['stats']['loss_trades']), 2) ?> <?= $porto['currency'] ?></span>
                            </div>
                            <div class="flex justify-between items-center border-b border-white/5 py-1.5 mt-3">
                                <span class="text-gray-400">Maximum consecutive losses:</span>
                                <span class="text-white"><span class="font-bold"><?= $porto['stats']['max_cons_loss'] ?></span> (-61.12 <?= $porto['currency'] ?>)</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-white/5 py-1.5">
                                <span class="text-gray-400">Maximal consecutive loss:</span>
                                <span class="text-white"><span class="font-bold">-61.12 <?= $porto['currency'] ?></span> (<?= $porto['stats']['max_cons_loss'] ?>)</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-white/5 py-1.5 mt-3">
                                <span class="text-gray-400">Monthly growth:</span>
                                <span class="font-bold text-white"><?= number_format($porto['growth'] / max(1, $porto['weeks'] / 4), 2) ?>%</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-white/5 py-1.5">
                                <span class="text-gray-400">Annual Forecast:</span>
                                <span class="font-bold text-white"><?= number_format(($porto['growth'] / max(1, $porto['weeks'] / 4)) * 12, 2) ?>%</span>
                            </div>
                            <div class="flex justify-between items-center border-b border-white/5 py-1.5">
                                <span class="text-gray-400">Algo trading:</span>
                                <span class="font-bold text-white"><?= $porto['algo_trading'] ?>%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Distribution Section -->
                    <div class="mt-16">
                        <h4 class="text-center font-bold text-white mb-12 text-lg uppercase tracking-widest">Distribution</h4>
                        
                        <div class="space-y-12 max-w-5xl mx-auto">
                            <?php foreach($porto['stats']['distribution'] as $dist): 
                                $maxCount = max(current(array_column($porto['stats']['distribution'], 'count')), 1);
                                $dealsPercent = ($dist['count'] / $maxCount) * 100;
                                
                                $totalBuySell = max($dist['buy'] + $dist['sell'], 1);
                                $buyPercent = ($dist['buy'] / $totalBuySell) * 50; // Max 50% for one side
                                $sellPercent = ($dist['sell'] / $totalBuySell) * 50;
                                
                                $profitUSD = max(0, $dist['profit']);
                                $lossUSD = abs(min(0, $dist['profit']));
                                $maxProfitUSD = max(max(array_column($porto['stats']['distribution'], 'profit')), 1);
                            ?>
                            <div class="grid grid-cols-[100px_1fr] gap-x-8 items-start">
                                <!-- Symbol Column -->
                                <div class="space-y-1 pr-4 border-r border-white/10 h-full flex flex-col justify-center">
                                    <span class="text-gray-500 text-[10px] uppercase font-bold">Symbol</span>
                                    <span class="text-white font-black text-xs truncate"><?= esc($dist['symbol']) ?></span>
                                </div>

                                <!-- Bars Column -->
                                <div class="space-y-4">
                                    <!-- Deals Bar -->
                                    <div class="relative">
                                        <div class="flex justify-between text-[9px] mb-1 font-bold">
                                            <span class="text-gray-300">Deals</span>
                                            <span class="text-white"><?= $dist['count'] ?></span>
                                        </div>
                                        <div class="h-6 w-full flex items-center">
                                            <div class="h-full bg-blue-500/20 border border-blue-500/40 rounded-sm relative" style="width: <?= $dealsPercent ?>%">
                                                <div class="absolute inset-y-0 left-0 bg-blue-500/10 w-full h-full"></div>
                                            </div>
                                        </div>
                                        <div class="flex justify-between text-[8px] text-gray-600 mt-0.5">
                                            <span>0</span><span>50</span><span>100</span><span>150</span><span>200</span><span>250</span><span>300</span>
                                        </div>
                                    </div>

                                    <!-- Sell Buy Comparison Bar -->
                                    <div class="relative">
                                        <div class="flex justify-between text-[9px] mb-1 font-bold">
                                            <span class="text-gray-400">Sell Buy</span>
                                            <div class="flex gap-4">
                                                <span class="text-red-500">Sell: <?= $dist['sell'] ?></span>
                                                <span class="text-green-500">Buy: <?= $dist['buy'] ?></span>
                                            </div>
                                        </div>
                                        <div class="h-6 w-full flex items-center relative">
                                            <!-- Center Line -->
                                            <div class="absolute inset-y-0 left-1/2 w-px bg-white/20 z-10"></div>
                                            
                                            <!-- Sell (Red - Left) -->
                                            <div class="h-full bg-red-500/20 border border-red-500/40 rounded-sm absolute right-1/2" style="width: <?= $sellPercent ?>%"></div>
                                            
                                            <!-- Buy (Green - Right) -->
                                            <div class="h-full bg-green-500/20 border border-green-500/40 rounded-sm absolute left-1/2" style="width: <?= $buyPercent ?>%"></div>
                                        </div>
                                        <div class="flex justify-between text-[8px] text-gray-600 mt-0.5 px-[25%] relative">
                                            <div class="absolute left-0 w-full flex justify-around">
                                                <span>200</span><span>150</span><span>100</span><span>50</span><span>0</span><span>50</span><span>100</span><span>150</span><span>200</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Profit USD Comparison Bar -->
                                    <div class="relative">
                                        <div class="flex justify-between text-[9px] mb-1 font-bold">
                                            <span class="text-gray-400">Profit, <?= $porto['currency'] ?></span>
                                            <div class="flex gap-4">
                                                <span class="text-red-500">Loss: <?= number_format($lossUSD, 2) ?></span>
                                                <span class="text-green-500">Profit: <?= number_format($profitUSD, 2) ?></span>
                                            </div>
                                        </div>
                                        <div class="h-6 w-full flex items-center relative">
                                            <!-- Center Line -->
                                            <div class="absolute inset-y-0 left-1/2 w-px bg-white/20 z-10"></div>
                                            
                                            <!-- Loss (Red - Left) -->
                                            <?php 
                                            $maxProfitOverall = 1000; // Mock scale or calculate
                                            $lossUSDPercent = min(50, ($lossUSD / $maxProfitOverall) * 50);
                                            $profitUSDPercent = min(50, ($profitUSD / $maxProfitOverall) * 50);
                                            ?>
                                            <div class="h-full bg-red-500/20 border border-red-500/40 rounded-sm absolute right-1/2" style="width: <?= $lossUSDPercent ?>%"></div>
                                            
                                            <!-- Profit (Green - Right) -->
                                            <div class="h-full bg-green-500/20 border border-green-500/40 rounded-sm absolute left-1/2" style="width: <?= $profitUSDPercent ?>%"></div>
                                        </div>
                                        <div class="flex justify-between text-[8px] text-gray-600 mt-0.5 px-[25%] relative">
                                            <div class="absolute left-0 w-full flex justify-around">
                                                <span>1k</span><span>750</span><span>500</span><span>250</span><span>0</span><span>250</span><span>500</span><span>750</span><span>1k</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            
                            <?php if(empty($porto['stats']['distribution'])): ?>
                                <p class="text-center text-gray-600 italic">No distribution data available yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- RISKS TAB (MQL5 STYLE) -->
                <div id="tab-risks" class="tab-content hidden animate-fade-in text-gray-300">
                    
                    <!-- Header Chart section -->
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex gap-8">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                                <span class="text-xs font-bold"><?= number_format($porto['max_deposit_load'], 2) ?>% <span class="text-gray-500 font-normal">Deposit load</span></span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-blue-400"></div>
                                <span class="text-xs font-bold text-white"><?= number_format($porto['balance'], 2) ?> USD <span class="text-gray-500 font-normal">Balance</span></span>
                            </div>
                        </div>
                        <div class="flex bg-white/5 p-1 rounded-lg border border-white/10 text-[10px] font-bold uppercase tracking-tighter">
                            <button class="px-3 py-1 bg-blue-600 text-white rounded">Deposit load</button>
                            <button class="px-3 py-1 hover:bg-white/5">Drawdown</button>
                        </div>
                    </div>

                    <!-- Main Risk Chart -->
                    <div class="w-full h-[250px] bg-black/40 border border-white/5 rounded-xl p-4 mb-8">
                        <canvas id="riskMainChart"></canvas>
                    </div>

                    <!-- Middle Progress Bars (Consecutive Stats) -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 text-[11px] uppercase font-bold tracking-wider">
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-500">Best Trade: <span class="text-green-500">+<?= number_format($porto['stats']['best_trade'], 2) ?> USD</span></span>
                                <span class="text-gray-500">Worst Trade: <span class="text-red-500"><?= number_format($porto['stats']['worst_trade'], 2) ?> USD</span></span>
                            </div>
                            <div class="h-1.5 w-full bg-white/5 rounded-full overflow-hidden flex">
                                <div class="bg-green-500 h-full" style="width: 60%"></div>
                                <div class="bg-red-500 h-full" style="width: 40%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-500">Max Consecutive Wins: <span class="text-white">7</span></span>
                                <span class="text-gray-500">Consecutive Losses: <span class="text-red-400">4</span></span>
                            </div>
                            <div class="h-1.5 w-full bg-white/5 rounded-full overflow-hidden flex">
                                <div class="bg-green-400 h-full" style="width: 75%"></div>
                                <div class="bg-red-400 h-full" style="width: 25%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-gray-500">Max Consecutive Profit: <span class="text-white">69.85 USD</span></span>
                                <span class="text-gray-500">Consecutive Loss: <span class="text-red-400">61.12 USD</span></span>
                            </div>
                            <div class="h-1.5 w-full bg-white/5 rounded-full overflow-hidden flex">
                                <div class="bg-green-300 h-full" style="width: 55%"></div>
                                <div class="bg-red-300 h-full" style="width: 45%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Bar Chart (Monthly Perf) -->
                    <div class="flex gap-8 mb-4">
                        <div class="text-[10px]"><span class="text-white font-bold">9.14 USD</span> <span class="text-gray-500">MFE (Max. Profit)</span></div>
                        <div class="text-[10px]"><span class="text-white font-bold">2.80 USD</span> <span class="text-gray-500">Avg. Profit</span></div>
                        <div class="text-[10px]"><span class="text-white font-bold">0.00 USD</span> <span class="text-gray-500">Avg. Loss</span></div>
                        <div class="text-[10px]"><span class="text-white font-bold">0.00 USD</span> <span class="text-gray-500">MAE (Max. DD)</span></div>
                    </div>
                    <div class="w-full h-[180px] bg-black/40 border border-white/5 rounded-xl p-4">
                        <canvas id="riskBarChart"></canvas>
                    </div>

                </div>
                
                 <div id="tab-slippage" class="tab-content hidden animate-fade-in">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                        <div>
                            <h3 class="text-xl font-bold text-white mb-1 tracking-tight">Analisa Slippage & Eksekusi</h3>
                        <p class="text-xs text-gray-500 uppercase tracking-widest font-extrabold font-bold">Performa eksekusi antar broker terhubung</p>
                        </div>
                        <div class="bg-blue-500/10 border border-blue-500/20 text-blue-400 px-4 py-2 rounded-lg text-xs font-bold">
                            <i class="fas fa-info-circle mr-1"></i> Data diperbarui setiap 24 jam
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                        <!-- Execution Speed Card -->
                        <div class="bg-white/5 border border-white/5 rounded-xl p-6">
                            <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest mb-4">Avg. Speed (Real)</p>
                            <div class="flex items-end gap-2">
                                <span class="text-3xl font-black text-white"><?= $porto['stats']['avg_speed'] > 0 ? (int)$porto['stats']['avg_speed'] : 'N/A' ?></span>
                                <span class="text-gray-500 font-bold mb-1">ms</span>
                            </div>
                            <div class="mt-4 flex items-center gap-2 text-green-500 text-[10px] font-bold">
                                <i class="fas fa-check-circle"></i> Source: Trade History
                            </div>
                        </div>

                        <!-- Avg Slippage Card -->
                        <div class="bg-white/5 border border-white/5 rounded-xl p-6">
                            <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest mb-4">Avg. Slippage (Real)</p>
                            <div class="flex items-end gap-2">
                                <span class="text-3xl font-black text-white"><?= number_format($porto['stats']['avg_slippage'], 2) ?></span>
                                <span class="text-gray-500 font-bold mb-1">pips</span>
                            </div>
                            <div class="mt-4 flex items-center gap-2 text-yellow-500 text-[10px] font-bold">
                                <i class="fas fa-satellite-dish"></i> Analysis Active
                            </div>
                        </div>

                        <!-- Max Slippage Card -->
                        <div class="bg-white/5 border border-white/5 rounded-xl p-6">
                            <p class="text-[10px] text-gray-500 uppercase font-black tracking-widest mb-4">Broker Performance</p>
                            <div class="flex items-end gap-2 text-white text-xl font-black">
                                <?= esc($porto['broker']) ?>
                            </div>
                            <div class="mt-6 flex items-center gap-2 text-gray-500 text-[10px] font-bold uppercase">
                                <i class="fas fa-server"></i> <?= esc($porto['porto']['server'] ?? 'Live Server') ?>
                            </div>
                        </div>
                    </div>

                    <!-- Broker Comparison Table -->
                    <div class="bg-black/30 border border-white/10 rounded-xl overflow-hidden">
                        <table class="w-full text-xs text-left">
                            <thead class="bg-white/5 text-gray-400 uppercase font-bold tracking-widest text-[9px]">
                                <tr>
                                    <th class="px-6 py-4">Ticket / Symbol</th>
                                    <th class="px-6 py-4 text-center">Tipe</th>
                                    <th class="px-6 py-4">Execution Speed</th>
                                    <th class="px-6 py-4 text-center">Slippage</th>
                                    <th class="px-6 py-4 text-right">Execution Time</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5 font-bold">
                                <?php if (empty($porto['history'])): ?>
                                <tr>
                                    <td colspan="5" class="py-12 text-center text-gray-600 italic">No execution data available yet.</td>
                                </tr>
                                <?php else: ?>
                                    <?php foreach ($porto['history'] as $trade): ?>
                                    <tr class="hover:bg-white/5 transition">
                                        <td class="px-6 py-4">
                                            <div class="text-white">#<?= $trade['ticket'] ?></div>
                                            <div class="text-[10px] text-gray-500 font-black uppercase tracking-widest"><?= esc($trade['symbol']) ?></div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-2 py-0.5 rounded text-[9px] <?= $trade['type'] === 'BUY' ? 'bg-blue-500/10 text-blue-500' : 'bg-red-500/10 text-red-500' ?>">
                                                <?= $trade['type'] ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-blue-400"><?= $trade['execution_speed'] > 0 ? (int)$trade['execution_speed'] . ' ms' : 'N/A' ?></td>
                                        <td class="px-6 py-4 text-center <?= $trade['slippage'] > 0 ? 'text-yellow-500' : 'text-green-500' ?>">
                                            <?= $trade['slippage'] > 0 ? '+' . number_format($trade['slippage'], 2) . ' pips' : '0.00 pips' ?>
                                        </td>
                                        <td class="px-6 py-4 text-right text-gray-500 text-[10px]">
                                            <?= date('d M, H:i:s', strtotime($trade['open_time'])) ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8 text-center p-8 border border-dashed border-white/10 rounded-xl bg-white/5">
                        <i class="fas fa-microchip text-2xl text-gray-600 mb-3 block"></i>
                        <h4 class="text-white font-bold mb-1">Mekanisme Perekaman Slippage</h4>
                        <p class="text-xs text-gray-500 leading-relaxed max-w-lg mx-auto">Sistem kami merekam setiap titik harga eksekusi (Open/Close) pada terminal MT5 dan membandingkannya dengan Feed harga standar Almai Global untuk menghitung perbedaan presisi (slippage).</p>
                    </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<!-- Share Modal -->
<div id="shareModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-sm transition-all duration-300 opacity-0 px-4">
    <div class="bg-[#111] border border-white/10 w-full max-w-md rounded-2xl p-6 shadow-2xl transform scale-95 transition-all duration-300">
        <!-- Modal Header -->
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-black text-white">Bagikan Portofolio</h3>
            <button id="closeShareModal" class="text-gray-500 hover:text-white transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Link Section -->
        <div class="space-y-4">
            <label class="text-xs text-gray-500 uppercase font-black tracking-widest">Link Portofolio</label>
            <div class="flex gap-2">
                <div class="flex-1 bg-black/50 border border-white/10 rounded-xl px-4 py-4 text-sm text-gray-400 font-mono truncate">
                    <?= current_url() ?>
                </div>
                <button id="copyLinkBtn" class="bg-white/5 hover:bg-white/10 border border-white/10 text-white w-14 h-14 flex items-center justify-center rounded-xl transition group shrink-0">
                    <i class="far fa-copy text-xl group-active:scale-95"></i>
                </button>
            </div>
        </div>

        <!-- Embed Section -->
        <div class="space-y-4 mt-6">
            <label class="text-xs text-gray-500 uppercase font-black tracking-widest">Embed Iframe Code</label>
            <div class="flex gap-2">
                <div class="flex-1 bg-black/50 border border-white/10 rounded-xl px-4 py-4 text-xs text-gray-400 font-mono overflow-x-auto whitespace-nowrap hide-scrollbar">
                    &lt;iframe src="<?= current_url() ?>?embed=1" width="100%" height="650" style="border:none; border-radius:1rem;" title="<?= esc($porto['name']) ?>"&gt;&lt;/iframe&gt;
                </div>
                <button id="copyEmbedBtn" class="bg-white/5 hover:bg-white/10 border border-white/10 text-white w-14 h-14 flex items-center justify-center rounded-xl transition group shrink-0">
                    <i class="far fa-copy text-xl group-active:scale-95"></i>
                </button>
            </div>
        </div>

        <!-- Social Grid -->
        <div class="grid grid-cols-4 gap-3 mt-8">
            <!-- WhatsApp -->
            <button onclick="window.open('https://wa.me/?text=' + encodeURIComponent('Lihat portofolio trading ini: ' + window.location.href), '_blank')" class="flex flex-col items-center gap-3 p-4 bg-[#075E54]/10 border border-[#075E54]/20 rounded-2xl hover:bg-[#075E54] hover:scale-[1.02] transition-all duration-300 group">
                <div class="w-14 h-14 bg-[#25D366] rounded-2xl flex items-center justify-center shadow-lg shadow-[#25D366]/20 text-white">
                    <i class="fab fa-whatsapp text-3xl"></i>
                </div>
                <span class="text-[10px] text-gray-400 group-hover:text-white font-black uppercase tracking-widest">WhatsApp</span>
            </button>
            <!-- Telegram -->
            <button onclick="window.open('https://t.me/share/url?url=' + encodeURIComponent(window.location.href) + '&text=' + encodeURIComponent('Lihat portofolio trading ini'), '_blank')" class="flex flex-col items-center gap-3 p-4 bg-[#0088cc]/10 border border-[#0088cc]/20 rounded-2xl hover:bg-[#0088cc] hover:scale-[1.02] transition-all duration-300 group">
                <div class="w-14 h-14 bg-[#0088cc] rounded-2xl flex items-center justify-center shadow-lg shadow-[#0088cc]/20 text-white">
                    <i class="fab fa-telegram-plane text-3xl"></i>
                </div>
                <span class="text-[10px] text-gray-400 group-hover:text-white font-black uppercase tracking-widest">Telegram</span>
            </button>
            <!-- Facebook -->
            <button onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(window.location.href), '_blank')" class="flex flex-col items-center gap-3 p-4 bg-[#1877F2]/10 border border-[#1877F2]/20 rounded-2xl hover:bg-[#1877F2] hover:scale-[1.02] transition-all duration-300 group">
                <div class="w-14 h-14 bg-[#1877F2] rounded-2xl flex items-center justify-center shadow-lg shadow-[#1877F2]/20 text-white">
                    <i class="fab fa-facebook-f text-3xl"></i>
                </div>
                <span class="text-[10px] text-gray-400 group-hover:text-white font-black uppercase tracking-widest">Facebook</span>
            </button>
            <!-- Twitter -->
            <button onclick="window.open('https://twitter.com/intent/tweet?url=' + encodeURIComponent(window.location.href) + '&text=' + encodeURIComponent('Lihat portofolio trading ini'), '_blank')" class="flex flex-col items-center gap-3 p-4 bg-[#1DA1F2]/10 border border-[#1DA1F2]/20 rounded-2xl hover:bg-[#1DA1F2] hover:scale-[1.02] transition-all duration-300 group">
                <div class="w-14 h-14 bg-[#1DA1F2] rounded-2xl flex items-center justify-center shadow-lg shadow-[#1DA1F2]/20 text-white">
                    <i class="fab fa-twitter text-3xl"></i>
                </div>
                <span class="text-[10px] text-gray-400 group-hover:text-white font-black uppercase tracking-widest">Twitter</span>
            </button>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        try {
        // --- 1. TAB SWITCHING LOGIC --- //
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                // Remove active styling from all buttons
                tabBtns.forEach(b => {
                    b.classList.remove('border-accent', 'text-accent');
                    b.classList.add('border-transparent', 'text-gray-400');
                });
                
                // Hide all contents
                tabContents.forEach(c => c.classList.add('hidden'));

                // Add active styling to clicked button
                btn.classList.remove('border-transparent', 'text-gray-400');
                btn.classList.add('border-accent', 'text-accent');

                // Show target content
                const targetId = btn.getAttribute('data-target');
                document.getElementById(targetId).classList.remove('hidden');
            });
        });

        // Auto-switch to relevant tab if any page_* param is present
        const urlParams = new URLSearchParams(window.location.search);
        const paramToTabMap = {
            'page_history': 'tab-history',
            'page_akun': 'tab-account',
            'page_account': 'tab-account',
            'page_statistik': 'tab-stats',
            'page_stats': 'tab-stats',
            'page_risiko': 'tab-risks',
            'page_risks': 'tab-risks',
            'page_slippage': 'tab-slippage'
        };

        for (const [param, tabId] of Object.entries(paramToTabMap)) {
            if (urlParams.has(param)) {
                const targetBtn = document.querySelector(`.tab-btn[data-target="${tabId}"]`);
                if (targetBtn) {
                    targetBtn.click();
                    setTimeout(() => {
                        document.getElementById('portfolio-tabs').scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 100);
                    break; // Stop checking once we found a match
                }
            }
        }
        // --- 2. CHART CONFIGURATIONS --- //
        Chart.defaults.color = '#9ca3af';
        Chart.defaults.font.family = 'Montserrat';

        // Extract real data from PHP to JS
        const balance = <?= $porto['balance'] ?>;
        const equity = <?= $porto['equity'] ?>;
        const initial_deposit = <?= $porto['initial_deposit'] ?>;
        const total_profit = <?= $porto['profit'] ?>;
        const growth_percent = <?= $porto['growth'] ?>;
        
        // Real chart data from trading history
        const chartData = <?= json_encode($porto['chartData']) ?>;
        
        // Determine if profit or loss
        const isProfit = growth_percent >= 0;
        
        // Line color always BLUE like MQL5
        const lineColor = '#3b82f6'; 
        
        // Background gradient changes: BLUE for profit, RED for loss
        const bgGradientStart = isProfit ? 'rgba(59, 130, 246, 0.3)' : 'rgba(239, 68, 68, 0.3)';
        const bgGradientEnd = isProfit ? 'rgba(59, 130, 246, 0)' : 'rgba(239, 68, 68, 0)';

        // 1. Growth Line Chart Mini (Top Left - using real data)
        const ctxGrowth = document.getElementById('growthChart').getContext('2d');
        let gradientColor = ctxGrowth.createLinearGradient(0, 0, 0, 180);
        gradientColor.addColorStop(0, bgGradientStart);
        gradientColor.addColorStop(1, bgGradientEnd);

        // Use real balance data for mini chart (last 10 points or all if less)
        const miniChartData = chartData.balance.slice(-10);
        
        // Cek apakah ada trading (jika ada lebih dari 1 data point)
        const hasTradingActivity = chartData.balance.length > 1;

        new Chart(ctxGrowth, {
            type: 'line',
            data: {
                labels: Array(miniChartData.length).fill(''),
                datasets: [{
                    data: miniChartData,
                    borderColor: lineColor, // Always BLUE
                    borderWidth: 3,
                    backgroundColor: gradientColor, // Changes based on profit/loss
                    fill: true,
                    tension: hasTradingActivity ? 0.4 : 0, // Tension 0 untuk garis lurus
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: lineColor,
                    pointHoverBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        enabled: true,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '$' + context.parsed.y.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            }
                        }
                    }
                },
                scales: {
                    x: { display: false },
                    y: { display: false }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });

        // 2. Radar Chart
        const ctxRadar = document.getElementById('radarChart').getContext('2d');
        new Chart(ctxRadar, {
            type: 'radar',
            data: {
                labels: [
                    ['Algo trading:', '<?= $porto['algo_trading'] ?>%'], 
                    ['Profit Trades:', '<?= number_format($porto['profit_trades'], 1) ?>%'], 
                    ['Loss Trades:', '<?= number_format($porto['loss_trades'], 1) ?>%'], 
                    ['Trading act.:', '<?= number_format($porto['trading_activity'], 1) ?>%'], 
                    ['Max dep. load:', '<?= number_format(min(100, $porto['max_deposit_load']), 1) ?>%'], 
                    ['Max drawdown:', '<?= $porto['max_drawdown'] ?>%']
                ],
                datasets: [{
                    label: 'Stats',
                    data: [
                        <?= $porto['algo_trading'] ?>, 
                        <?= $porto['profit_trades'] ?>, 
                        <?= $porto['loss_trades'] ?>, 
                        <?= $porto['trading_activity'] ?>, 
                        <?= min(100, $porto['max_deposit_load']) ?>, 
                        <?= $porto['max_drawdown'] ?>
                    ],
                    backgroundColor: 'rgba(59, 130, 246, 0.3)',
                    borderColor: '#3b82f6',
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        enabled: true,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.r.toFixed(1) + '%';
                            }
                        }
                    }
                },
                scales: {
                    r: {
                        angleLines: { 
                            color: 'rgba(255, 255, 255, 0.2)',
                            lineWidth: 1
                        },
                        grid: { 
                            color: 'rgba(255, 255, 255, 0.2)',
                            lineWidth: 1
                        },
                        pointLabels: {
                            color: '#ffffff',
                            font: { 
                                size: 12, 
                                family: 'Montserrat',
                                weight: 'bold'
                            },
                            padding: 15
                        },
                        ticks: { 
                            display: true,
                            color: '#9ca3af',
                            backdropColor: 'transparent',
                            font: { size: 10 },
                            min: 0, 
                            max: 100,
                            stepSize: 25,
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });

        // 3. History Linear Chart (Bottom) - MQL5 Style with REAL DATA PER DATE
        const ctxHistory = document.getElementById('historyChart').getContext('2d');
        
        // Use REAL data from trading history (per date)
        const historyLabels = chartData.labels;
        const historyBalanceData = chartData.balance;
        const historyGrowthData = chartData.growth;
        const dailyProfits = chartData.daily;
        
        // Create gradient that changes color based on balance vs initial deposit
        let historyGradient = ctxHistory.createLinearGradient(0, 0, 0, 400);
        
        // Check current balance vs initial deposit
        const currentBalance = historyBalanceData[historyBalanceData.length - 1];
        const isCurrentProfit = currentBalance >= initial_deposit;
        
        if (isCurrentProfit) {
            // BLUE gradient when balance > initial deposit
            historyGradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
            historyGradient.addColorStop(1, 'rgba(59, 130, 246, 0)');
        } else {
            // RED gradient when balance < initial deposit
            historyGradient.addColorStop(0, 'rgba(239, 68, 68, 0.3)');
            historyGradient.addColorStop(1, 'rgba(239, 68, 68, 0)');
        }

        const historyChartInstance = new Chart(ctxHistory, {
            type: 'line',
            data: {
                labels: historyLabels,
                datasets: [{
                    label: 'Balance',
                    data: historyBalanceData,
                    borderColor: '#3b82f6', // Always BLUE line
                    backgroundColor: historyGradient,
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointRadius: 0,
                    pointHoverRadius: 5,
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: '#3b82f6',
                    pointHoverBorderWidth: 2,
                    segment: {
                        // Change line color based on position relative to initial deposit
                        borderColor: ctx => {
                            const value = ctx.p1.parsed.y;
                            return value >= initial_deposit ? '#3b82f6' : '#ef4444'; // Blue if above, red if below
                        }
                    }
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        enabled: true,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                const dailyData = dailyProfits[context.dataIndex];
                                const dailyProfit = dailyData ? dailyData.profit : 0;
                                const profitText = dailyProfit >= 0 ? '+' + dailyProfit.toFixed(2) : dailyProfit.toFixed(2);
                                const vsDeposit = context.parsed.y - initial_deposit;
                                const vsDepositText = vsDeposit >= 0 ? '+' + vsDeposit.toFixed(2) : vsDeposit.toFixed(2);
                                return [
                                    'Balance: $' + context.parsed.y.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}),
                                    'Daily P/L: ' + profitText + ' USD',
                                    'vs Deposit: ' + vsDepositText + ' USD'
                                ];
                            },
                            title: function(context) {
                                return context[0].label;
                            }
                        }
                    }
                },
                scales: {
                    x: { 
                        display: true, 
                        grid: { 
                            color: 'rgba(255, 255, 255, 0.1)',
                            drawBorder: true,
                            borderColor: 'rgba(255, 255, 255, 0.2)'
                        },
                        ticks: { 
                            display: true,
                            color: '#9ca3af',
                            font: { size: 11 },
                            maxRotation: 45,
                            autoSkip: true,
                            maxTicksLimit: 20
                        }
                    },
                    y: { 
                        display: true, 
                        grid: { 
                            color: 'rgba(255, 255, 255, 0.1)',
                            drawBorder: true,
                            borderColor: 'rgba(255, 255, 255, 0.2)'
                        }, 
                        position: 'right',
                        ticks: {
                            color: '#9ca3af',
                            font: { size: 11 },
                            callback: function(value) {
                                return '$' + value.toLocaleString('en-US', {minimumFractionDigits: 0, maximumFractionDigits: 0});
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
        
        // 3.5. Equity & Drawdown Chart - REAL DATA (MQL5 Style)
        const ctxEquityDrawdown = document.getElementById('equityDrawdownChart').getContext('2d');
        
        // Use REAL equity and drawdown data
        const equityData = chartData.equity;
        const drawdownData = chartData.drawdown;
        
        const equityDrawdownChartInstance = new Chart(ctxEquityDrawdown, {
            type: 'line',
            data: {
                labels: historyLabels,
                datasets: [
                    {
                        label: 'Balance',
                        data: historyBalanceData,
                        borderColor: '#3b82f6', // BLUE for balance
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.3,
                        pointRadius: 0,
                        yAxisID: 'y',
                        segment: {
                            borderColor: ctx => {
                                const value = ctx.p1.parsed.y;
                                return value >= initial_deposit ? '#3b82f6' : '#ef4444';
                            }
                        }
                    },
                    {
                        label: 'Drawdown',
                        data: drawdownData,
                        type: 'bar',
                        backgroundColor: 'rgba(239, 107, 68, 0.5)', // Orange/Red bars
                        borderColor: 'rgba(239, 107, 68, 0.8)',
                        borderWidth: 1,
                        yAxisID: 'y1',
                        barPercentage: 1.0,
                        categoryPercentage: 1.0
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        enabled: true,
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        padding: 12,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                if (context.datasetIndex === 0) {
                                    const vsDeposit = context.parsed.y - initial_deposit;
                                    const vsDepositText = vsDeposit >= 0 ? '+' + vsDeposit.toFixed(2) : vsDeposit.toFixed(2);
                                    return [
                                        'Balance: $' + context.parsed.y.toLocaleString('en-US', {minimumFractionDigits: 2}),
                                        'vs Deposit: ' + vsDepositText + ' USD'
                                    ];
                                } else {
                                    return 'Drawdown: ' + context.parsed.y.toFixed(2) + '%';
                                }
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        display: true,
                        grid: { 
                            color: 'rgba(255, 255, 255, 0.1)', 
                            drawBorder: true,
                            borderColor: 'rgba(255, 255, 255, 0.2)'
                        },
                        ticks: {
                            color: '#9ca3af',
                            font: { size: 11 },
                            maxRotation: 45,
                            autoSkip: true,
                            maxTicksLimit: 20
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        grid: { 
                            color: 'rgba(255, 255, 255, 0.1)', 
                            drawBorder: true,
                            borderColor: 'rgba(255, 255, 255, 0.2)'
                        },
                        ticks: {
                            color: '#3b82f6',
                            font: { size: 11 },
                            callback: function(value) {
                                return '$' + value.toLocaleString('en-US', {minimumFractionDigits: 0});
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: { display: false },
                        ticks: {
                            color: '#ef6b44',
                            font: { size: 11 },
                            callback: function(value) {
                                return value.toFixed(1) + '%';
                            }
                        },
                        min: 0,
                        max: Math.max(...drawdownData, 1) * 1.2,
                        reverse: false
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
        
        // Toggle Equity/Drawdown
        const btnEquity = document.getElementById('btnEquity');
        const btnDrawdown = document.getElementById('btnDrawdown');
        
        function updateEquityDrawdownChart(mode) {
            if (!equityDrawdownChartInstance) return;
            
            if (mode === 'equity') {
                btnEquity.classList.add('active');
                btnDrawdown.classList.remove('active');
                
                // Show only balance line
                equityDrawdownChartInstance.data.datasets[0].hidden = false;
                equityDrawdownChartInstance.data.datasets[1].hidden = true;
                equityDrawdownChartInstance.options.scales.y.display = true;
                equityDrawdownChartInstance.options.scales.y1.display = false;
            } else {
                btnDrawdown.classList.add('active');
                btnEquity.classList.remove('active');
                
                // Show both balance and drawdown
                equityDrawdownChartInstance.data.datasets[0].hidden = false;
                equityDrawdownChartInstance.data.datasets[1].hidden = false;
                equityDrawdownChartInstance.options.scales.y.display = true;
                equityDrawdownChartInstance.options.scales.y1.display = true;
            }
            
            equityDrawdownChartInstance.update('none');
        }
        
        if (btnEquity) {
            btnEquity.addEventListener('click', () => updateEquityDrawdownChart('equity'));
        }
        
        if (btnDrawdown) {
            btnDrawdown.addEventListener('click', () => updateEquityDrawdownChart('drawdown'));
        }
        
        // 4. RISK MAIN CHART (Mixed Load & Balance)
        const ctxRiskMain = document.getElementById('riskMainChart').getContext('2d');
        new Chart(ctxRiskMain, {
            data: {
                labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                datasets: [
                    {
                        type: 'line',
                        label: 'Balance',
                        data: [<?= $porto['balance'] * 0.95 ?>, <?= $porto['balance'] * 0.97 ?>, <?= $porto['balance'] ?>],
                        borderColor: '#3b82f6',
                        borderWidth: 2,
                        pointRadius: 0,
                        tension: 0.3
                    },
                    {
                        type: 'bar',
                        label: 'Deposit Load',
                        data: [2, 5, <?= $porto['max_deposit_load'] ?>],
                        backgroundColor: 'rgba(255,255,255, 0.15)',
                        barThickness: 5
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false } },
                    y: { position: 'right', grid: { color: 'rgba(255,255,255,0.05)' } }
                }
            }
        });

        // 5. RISK BAR CHART (Stability)
        const ctxRiskBar = document.getElementById('riskBarChart').getContext('2d');
        new Chart(ctxRiskBar, {
            type: 'bar',
            data: {
                labels: Array(20).fill(''),
                datasets: [
                    {
                        label: 'Profit',
                        data: Array(20).fill(0).map(() => Math.random() * 20),
                        backgroundColor: '#65a30d'
                    },
                    {
                        label: 'Loss',
                        data: Array(20).fill(0).map(() => Math.random() * -10),
                        backgroundColor: '#dc2626'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { display: false, stacked: true },
                    y: { position: 'right', stacked: true, grid: { color: 'rgba(255,255,255,0.05)' } }
                }
            }
        });
        
        // --- 6. TOGGLE GROWTH/BALANCE CHART --- //
        const btnGrowth = document.getElementById('btnGrowth');
        const btnBalance = document.getElementById('btnBalance');
        
        function updateHistoryChart(mode) {
            if (!historyChartInstance) return;
            
            // Toggle button states
            if (mode === 'growth') {
                btnGrowth.classList.add('active');
                btnBalance.classList.remove('active');
            } else {
                btnBalance.classList.add('active');
                btnGrowth.classList.remove('active');
            }
            
            // Update chart data based on mode
            if (mode === 'growth') {
                // Show growth percentage data
                historyChartInstance.data.datasets[0].data = historyGrowthData;
                historyChartInstance.data.datasets[0].label = 'Growth';
                historyChartInstance.options.scales.y.ticks.callback = function(value) {
                    return value.toFixed(2) + '%';
                };
                historyChartInstance.options.plugins.tooltip.callbacks.label = function(context) {
                    const dailyData = dailyProfits[context.dataIndex];
                    const dailyProfit = dailyData ? dailyData.profit : 0;
                    const prevBalance = context.dataIndex > 0 ? historyBalanceData[context.dataIndex - 1] : initial_deposit;
                    const profitPercent = prevBalance > 0 ? (dailyProfit / prevBalance) * 100 : 0;
                    const profitText = profitPercent >= 0 ? '+' + profitPercent.toFixed(2) : profitPercent.toFixed(2);
                    return [
                        'Growth: ' + context.parsed.y.toFixed(2) + '%',
                        'Daily P/L: ' + profitText + '%'
                    ];
                };
            } else {
                // Show balance data
                historyChartInstance.data.datasets[0].data = historyBalanceData;
                historyChartInstance.data.datasets[0].label = 'Balance';
                historyChartInstance.options.scales.y.ticks.callback = function(value) {
                    return '$' + value.toLocaleString('en-US', {minimumFractionDigits: 0, maximumFractionDigits: 0});
                };
                historyChartInstance.options.plugins.tooltip.callbacks.label = function(context) {
                    const dailyData = dailyProfits[context.dataIndex];
                    const dailyProfit = dailyData ? dailyData.profit : 0;
                    const profitText = dailyProfit >= 0 ? '+' + dailyProfit.toFixed(2) : dailyProfit.toFixed(2);
                    return [
                        'Balance: $' + context.parsed.y.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}),
                        'Daily P/L: ' + profitText + ' USD'
                    ];
                };
            }
            
            historyChartInstance.update('none'); // Update without animation
        }
        
        if (btnGrowth) {
            btnGrowth.addEventListener('click', () => updateHistoryChart('growth'));
        }
        
        if (btnBalance) {
            btnBalance.addEventListener('click', () => updateHistoryChart('balance'));
        }
        
        // --- 7. SHARE MODAL LOGIC --- //
        const shareModal = document.getElementById('shareModal');
        const shareBtn = document.getElementById('sharePortoBtn');
        const closeShareBtn = document.getElementById('closeShareModal');
        const copyLinkBtn = document.getElementById('copyLinkBtn');
        const modalContent = shareModal ? shareModal.querySelector('div') : null;

        function openModal() {
            if (!shareModal) return;
            shareModal.classList.remove('hidden');
            shareModal.classList.add('flex');
            setTimeout(() => {
                shareModal.classList.remove('opacity-0');
                if (modalContent) {
                    modalContent.classList.remove('scale-95');
                    modalContent.classList.add('scale-100');
                }
            }, 10);
        }

        function closeModal() {
            if (!shareModal) return;
            shareModal.classList.add('opacity-0');
            if (modalContent) {
                modalContent.classList.add('scale-95');
                modalContent.classList.remove('scale-100');
            }
            setTimeout(() => {
                shareModal.classList.add('hidden');
                shareModal.classList.remove('flex');
            }, 300);
        }

        if (shareBtn) shareBtn.addEventListener('click', openModal);
        if (closeShareBtn) closeShareBtn.addEventListener('click', closeModal);
        if (shareModal) {
            shareModal.addEventListener('click', (e) => {
                if (e.target === shareModal) closeModal();
            });
        }

        // Copy Link
        if (copyLinkBtn) {
            copyLinkBtn.addEventListener('click', function() {
                const url = window.location.href;
                navigator.clipboard.writeText(url).then(() => {
                    const icon = copyLinkBtn.querySelector('i');
                    icon.className = 'fas fa-check text-green-500';
                    
                    setTimeout(() => {
                        icon.className = 'far fa-copy';
                    }, 2000);
                });
            });
        }

        // Copy Embed Code
        const copyEmbedBtn = document.getElementById('copyEmbedBtn');
        if (copyEmbedBtn) {
            copyEmbedBtn.addEventListener('click', function() {
                const embedCode = `<iframe src="${window.location.href.split('?')[0]}?embed=1" width="100%" height="650" style="border:none; border-radius:1rem;" title="<?= esc($porto['name']) ?>"></iframe>`;
                navigator.clipboard.writeText(embedCode).then(() => {
                    const icon = copyEmbedBtn.querySelector('i');
                    icon.className = 'fas fa-check text-green-500';
                    
                    setTimeout(() => {
                        icon.className = 'far fa-copy';
                    }, 2000);
                });
            });
        }
        
        } catch (error) {
            console.error('Error initializing portfolio detail:', error);
        }
    });
</script>
<style>
    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    
    /* Pulse animation for growth percentage - DISABLED */
    /*
    @keyframes pulse-glow {
        0%, 100% {
            text-shadow: 0 0 10px currentColor;
        }
        50% {
            text-shadow: 0 0 20px currentColor, 0 0 30px currentColor;
        }
    }
    
    .text-green-500, .text-red-500 {
        animation: pulse-glow 3s ease-in-out infinite;
    }
    */
    
    /* Smooth fade in for tab content */
    .tab-content {
        animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Hover effect for stat bars */
    .stat-bar {
        transition: all 0.3s ease;
    }
    
    .stat-bar:hover {
        transform: scaleX(1.02);
        filter: brightness(1.2);
    }
    
    /* Toggle button styles like MQL5 */
    .chart-toggle-btn {
        background: transparent;
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #9ca3af;
    }
    
    .chart-toggle-btn.active {
        background: #3b82f6;
        color: #fff;
        border-color: #3b82f6;
    }
    
    .chart-toggle-btn:hover:not(.active) {
        background: rgba(255, 255, 255, 0.05);
        border-color: rgba(255, 255, 255, 0.2);
    }
</style>
<?= $this->endSection() ?>
