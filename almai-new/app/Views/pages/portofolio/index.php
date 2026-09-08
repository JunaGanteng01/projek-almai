<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- SECTION 1 - HERO -->
<section class="relative min-h-screen flex items-center justify-center py-20 sm:py-24 md:py-28 overflow-hidden bg-black">

    <!-- Grid Pattern -->
    <div class="absolute inset-0 bg-grid z-[1] pointer-events-none opacity-50"></div>
    
    <!-- Glow Effect -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 
                w-[800px] h-[500px] bg-accent/20 blur-[120px] rounded-full 
                pointer-events-none z-[1]"></div>

    <!-- CONTENT -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">

        <div class="flex flex-col items-center gap-5 sm:gap-6 md:gap-7 lg:gap-8">


            <!-- TITLE (Portofolio Almai ID) -->
            <h1 
                class="text-5xl sm:text-7xl md:text-8xl lg:text-[9rem] xl:text-[11rem] 
                       font-black leading-tight tracking-tight text-white mb-2"
                data-aos="fade-up"
            >
                <span class="blur-text-container">
                    <span>P</span><span>O</span><span>R</span><span>T</span><span>O</span><span>F</span><span>O</span><span>L</span><span>I</span><span>O</span>
                    <span class="inline-block w-4 sm:w-8"></span>
                    <span>A</span><span>L</span><span>M</span><span>A</span><span>I</span><span>.</span><span>I</span><span>D</span>
                </span>
            </h1>

            <!-- SUBTITLE/HEADLINE STYLE -->
            <div 
                class="text-sm sm:text-base md:text-lg lg:text-xl 
                       text-gray-400 
                       max-w-2xl sm:max-w-4xl lg:max-w-5xl mx-auto 
                       leading-snug lg:leading-relaxed px-2 space-y-4"
                data-aos="fade-up"
                data-aos-delay="100"
            >
                <p class="text-white italic">
                    "Halaman ini menampilkan rekam jejak performa trading dari para trader yang tergabung dalam program advokasi."
                </p>
                <p class="text-xs md:text-sm text-gray-500 max-w-2xl mx-auto">
                    Transparansi menjadi prioritas utama — Anda dapat mempelajari gaya trading, manajemen risiko, serta konsistensi performa setiap trader sebagai bahan edukasi dan referensi.
                </p>
            </div>

            <!-- CTA -->
            <div 
                class="flex flex-col sm:flex-row items-center justify-center 
                       gap-3 sm:gap-4 pt-3"
                data-aos="fade-up" 
                data-aos-delay="200"
            >
                <a href="<?= base_url('wpa') ?>" 
                   class="w-full sm:w-auto px-8 py-4 bg-accent text-black font-bold rounded-xl 
                          hover:bg-green-500 transition-all transform hover:scale-105 
                          shadow-[0_0_20px_rgba(51,232,24,0.3)] flex items-center justify-center gap-2">
                    <i class="fas fa-plus-circle"></i> Buat Portofolio
                </a>
            </div>

        </div>

    </div>
</section>

<!-- Search -->
<section class="py-6 border-b border-white/5 bg-black/50">
    <div class="container mx-auto px-6">
        <div class="max-w-xl mx-auto">
            <form action="<?= base_url('portofolio') ?>" method="get" class="relative">
                <input type="text" name="search" value="<?= esc($searchQuery ?? '') ?>"
                    placeholder="Cari portofolio atau nama trader..."
                    class="w-full bg-[#111] border border-white/20 rounded-full px-6 py-3 pl-14 focus:border-accent focus:outline-none text-white">
                <i class="fas fa-search absolute left-6 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </form>
        </div>
    </div>
</section>

<!-- Content Grid -->
<section class="py-12 bg-black min-h-screen">
    <div class="container mx-auto px-4 sm:px-6">

        <!-- Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach($portofolios as $p): ?>
            <a href="<?= base_url('portofolio/' . $p['id']) ?>" 
               style="border: 2px solid #33e818;"
               class="block bg-[#111] rounded-2xl shadow-2xl transition-all duration-300 overflow-hidden group relative <?= $p['growth'] >= 0 ? 'pulse-glow-green' : 'pulse-glow-red' ?>">
                
                <div class="p-5">
                    <!-- Top header -->
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl border border-white/10 overflow-hidden bg-[#222] shadow-inner">
                                <?php 
                                $logoUrl = '';
                                if (!empty($p['logo'])) {
                                    // Remove 'writable/' prefix if exists
                                    $logoUrl = base_url(str_replace('writable/', '', $p['logo']));
                                }
                                ?>
                                <?php if ($logoUrl): ?>
                                    <img src="<?= $logoUrl ?>" alt="<?= esc($p['name']) ?>" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=<?= urlencode($p['author']) ?>&background=random'">
                                <?php else: ?>
                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($p['author']) ?>&background=random" class="w-full h-full object-cover">
                                <?php endif; ?>
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($p['author']) ?>&background=random" class="w-full h-full object-cover">
                            </div>
                            <div class="overflow-hidden">
                                <h3 class="text-white font-black text-base leading-tight group-hover:text-accent transition truncate"><?= esc($p['name']) ?></h3>
                                <p class="text-gray-500 text-[11px] font-bold uppercase tracking-widest mt-1 truncate"><?= esc($p['author']) ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Growth Section & Mini Chart -->
                    <div class="flex flex-col gap-1 mb-2">
                        <div class="flex justify-between items-end mb-2">
                            <span class="text-[10px] text-gray-500 uppercase font-bold tracking-widest">Growth</span>
                            <div class="<?= $p['growth'] >= 0 ? 'text-green-500' : 'text-red-500' ?> font-black text-2xl tracking-tighter leading-none">
                                <?= $p['growth'] >= 0 ? '+' : '' ?><?= number_format($p['growth'], 2) ?>%
                            </div>
                        </div>
                        
                        <!-- Mini Chart Canvas -->
                        <div class="h-[70px] w-full relative">
                            <canvas id="chart-<?= $p['id'] ?>" 
                                    class="portfolio-mini-chart"
                                    data-initial="<?= $p['initial_deposit'] ?>"
                                    data-balance="<?= $p['balance'] ?>"
                                    data-equity="<?= $p['equity'] ?>"
                                    data-growth="<?= $p['growth'] ?>"></canvas>
                        </div>
                    </div>

                    <!-- Mini Stats Grid -->
                    <div class="grid grid-cols-2 gap-4 py-3 border-t border-white/5">
                        <div class="flex flex-col">
                            <span class="text-[9px] text-gray-600 uppercase font-bold">Reliability</span>
                            <div class="flex text-[9px] text-yellow-500 gap-0.5 mt-0.5">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star text-gray-700"></i>
                            </div>
                        </div>
                        <div class="flex flex-col text-right">
                            <span class="text-[9px] text-gray-600 uppercase font-bold">Algo Trading</span>
                            <span class="text-blue-400 font-black text-xs"><?= $p['algo_trading'] ?>%</span>
                        </div>
                    </div>

                </div>
                
                <!-- Action Footer -->
                <div class="px-5 py-3 bg-white/5 flex justify-between items-center group-hover:bg-accent/10 transition-colors">
                    <div class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                        <i class="fas fa-users mr-1"></i> <?= $p['subscribers'] ?> Follow
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

        <?php if(empty($portofolios)): ?>
            <div class="text-center py-12">
                <i class="fas fa-folder-open text-gray-600 text-4xl mb-4"></i>
                <h3 class="text-white text-xl font-bold">Portofolio tidak ditemukan</h3>
                <p class="text-gray-400">Pencarian Anda tidak membuahkan hasil.</p>
            </div>
        <?php endif; ?>

        <!-- Pagination -->
        <div class="mt-12 flex justify-center">
            <?= $pager->links('portofolios', 'tailwind_pagination') ?>
        </div>

        <!-- Disclaimer -->
        <div class="mt-20 pt-12 border-t border-white/5 text-center">
            <p class="text-yellow-500 font-bold mb-3 tracking-wide">⚠️ Bukan layanan copy trading atau auto trading.</p>
            <p class="text-gray-500 text-sm max-w-3xl mx-auto leading-relaxed italic">
                Kami tidak menyediakan penyalinan transaksi secara otomatis. Seluruh keputusan tetap berada di tangan Anda sebagai trader.
            </p>
        </div>

    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    @keyframes glow-green {
        0%, 100% { box-shadow: 0 0 5px rgba(51, 232, 24, 0.2), inset 0 0 5px rgba(51, 232, 24, 0.1); }
        50% { box-shadow: 0 0 20px rgba(51, 232, 24, 0.4), inset 0 0 10px rgba(51, 232, 24, 0.2); }
    }
    @keyframes glow-red {
        0%, 100% { box-shadow: 0 0 5px rgba(239, 68, 68, 0.2), inset 0 0 5px rgba(239, 68, 68, 0.1); }
        50% { box-shadow: 0 0 20px rgba(239, 68, 68, 0.4), inset 0 0 10px rgba(239, 68, 68, 0.2); }
    }
    .pulse-glow-green { animation: glow-green 3s infinite ease-in-out; }
    .pulse-glow-red { animation: glow-red 3s infinite ease-in-out; }

    /* Giant Title Animation */
    .blur-text-container {
        display: inline-flex;
    }
    .blur-text-container span {
        display: inline-block;
        animation: blur-wave 3s ease-in-out infinite;
    }
    @keyframes blur-wave {
        0%, 100% { filter: blur(0px); opacity: 1; }
        50% { filter: blur(8px); opacity: 0.6; }
    }
    /* Dynamic delays for "Portofolio Almai ID" */
    .blur-text-container span:nth-child(1) { animation-delay: 0s; }
    .blur-text-container span:nth-child(2) { animation-delay: 0.1s; }
    .blur-text-container span:nth-child(3) { animation-delay: 0.2s; }
    .blur-text-container span:nth-child(4) { animation-delay: 0.3s; }
    .blur-text-container span:nth-child(5) { animation-delay: 0.4s; }
    .blur-text-container span:nth-child(6) { animation-delay: 0.5s; }
    .blur-text-container span:nth-child(7) { animation-delay: 0.6s; }
    .blur-text-container span:nth-child(8) { animation-delay: 0.7s; }
    .blur-text-container span:nth-child(9) { animation-delay: 0.8s; }
    .blur-text-container span:nth-child(10) { animation-delay: 0.9s; }
    .blur-text-container span:nth-child(11) { animation-delay: 1.0s; }
    .blur-text-container span:nth-child(12) { animation-delay: 1.1s; }
    .blur-text-container span:nth-child(13) { animation-delay: 1.2s; }
    .blur-text-container span:nth-child(14) { animation-delay: 1.3s; }
    .blur-text-container span:nth-child(15) { animation-delay: 1.4s; }
    .blur-text-container span:nth-child(16) { animation-delay: 1.5s; }
    .blur-text-container span:nth-child(17) { animation-delay: 1.6s; }
    .blur-text-container span:nth-child(18) { animation-delay: 1.7s; }
    .blur-text-container span:nth-child(19) { animation-delay: 1.8s; }
    .blur-text-container span:nth-child(20) { animation-delay: 1.9s; }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const charts = document.querySelectorAll('.portfolio-mini-chart');
        
        charts.forEach(el => {
            const ctx = el.getContext('2d');
            const initial = parseFloat(el.dataset.initial);
            const balance = parseFloat(el.dataset.balance);
            const equity = parseFloat(el.dataset.equity);
            const growth = parseFloat(el.dataset.growth);
            
            const isProfit = growth >= 0;
            const lineColor = isProfit ? '#22c55e' : '#ef4444'; 
            
            let gradient = ctx.createLinearGradient(0, 0, 0, 70);
            gradient.addColorStop(0, isProfit ? 'rgba(34, 197, 94, 0.4)' : 'rgba(239, 68, 68, 0.4)');
            gradient.addColorStop(1, 'rgba(0,0,0, 0)');

            // Cek apakah ada trading (jika growth > 0.01% berarti ada trading)
            const hasTrading = Math.abs(growth) > 0.01;
            
            // Jika belum trading, buat garis lurus
            let chartData;
            if (!hasTrading) {
                // Garis lurus (flat) - gunakan balance saat ini
                const currentBalance = balance > 0 ? balance : initial;
                chartData = [currentBalance, currentBalance, currentBalance];
            } else {
                // Garis bergelombang (ada trading)
                chartData = [
                    initial,
                    initial + (balance - initial) * 0.5,
                    equity
                ];
            }

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Start', 'Mid', 'End'],
                    datasets: [{
                        data: chartData,
                        borderColor: lineColor,
                        borderWidth: 2,
                        backgroundColor: gradient,
                        fill: true,
                        tension: hasTrading ? 0.4 : 0, // Tension 0 untuk garis lurus
                        pointRadius: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: lineColor,
                        pointBorderWidth: 1,
                        pointHitRadius: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { 
                            enabled: true,
                            backgroundColor: 'rgba(0,0,0,0.8)',
                            titleFont: { size: 10 },
                            bodyFont: { size: 12, weight: 'bold' },
                            callbacks: {
                                label: function(context) {
                                    return '$' + context.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        x: { display: false },
                        y: { 
                            display: false,
                            suggestedMin: initial * 0.9,
                            suggestedMax: Math.max(balance, equity) * 1.05
                        }
                    }
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>
