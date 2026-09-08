<?= $this->extend('user/partials/layout') ?>
<?= $this->section('content') ?>

<div class="mb-8 bg-[#111] p-4 md:p-6 rounded-xl border border-white/10">
    <!-- Judul -->
    <div class="mb-4">
        <h1 class="text-xl md:text-2xl font-black text-white mb-1 tracking-tight">Manajemen Portofolio</h1>
        <p class="text-xs text-gray-500 uppercase tracking-widest font-bold">Monitor Performa & Bagikan Sinyal Trading</p>
    </div>
    <!-- 3 Tombol Sejajar — full width di mobile -->
    <div class="grid grid-cols-3 gap-2">
        <a href="<?= base_url('user/dashboard/rwa/portoflio') ?>"
           class="flex flex-col md:flex-row items-center justify-center gap-1 md:gap-2 px-2 md:px-5 py-3 bg-black border border-white/10 hover:border-accent/50 text-gray-400 hover:text-accent font-bold rounded-xl transition text-center">
            <i class="fas fa-chart-line text-sm md:text-base"></i>
            <span class="text-[10px] md:text-sm leading-tight">Porto Aset Digital</span>
        </a>
        <a href="<?= base_url('user/dashboard/portofolio') ?>"
           class="flex flex-col md:flex-row items-center justify-center gap-1 md:gap-2 px-2 md:px-5 py-3 bg-accent text-black font-bold rounded-xl transition text-center">
            <i class="fas fa-satellite-dish text-sm md:text-base"></i>
            <span class="text-[10px] md:text-sm leading-tight">Porto Derivatif</span>
        </a>
        <a href="<?= base_url('user/dashboard/portofolio/create') ?>"
           class="flex flex-col md:flex-row items-center justify-center gap-1 md:gap-2 px-2 md:px-5 py-3 bg-white/10 hover:bg-white/20 text-white font-bold rounded-xl transition text-center <?= $count >= 3 ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' ?>">
            <i class="fas fa-plus text-sm md:text-base"></i>
            <span class="text-[10px] md:text-sm leading-tight">Tambah Portofolio</span>
        </a>
    </div>
</div>



<?php if(session()->getFlashdata('download_ea')): ?>
    <div class="mb-8 bg-accent/20 border border-accent/50 p-6 rounded-2xl flex flex-col md:flex-row items-center justify-between gap-6 animate-pulse">
        <div class="flex items-center gap-4 text-center md:text-left">
            <div class="w-14 h-14 bg-accent/20 rounded-full flex items-center justify-center text-accent text-2xl">
                <i class="fas fa-download"></i>
            </div>
            <div>
                <h4 class="text-white font-black text-lg leading-tight">Yess! Portofolio Berhasil Dibuat</h4>
                <p class="text-accent/80 text-xs font-bold uppercase tracking-widest">Silakan download file EA MT5 atau MT4 yang sudah kami kunci untuk akun Anda.</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="<?= base_url('portofolio_eas/portofolio-almai.ex5') ?>" download class="bg-accent text-black font-black px-8 py-4 rounded-2xl shadow-xl hover:scale-105 transition-all text-center text-sm">
                DOWNLOAD MT5 (.EX5)
            </a>
            <a href="<?= base_url('portofolio_eas/portofolio-almai.ex4') ?>" download class="bg-accent text-black font-black px-8 py-4 rounded-2xl shadow-xl hover:scale-105 transition-all text-center text-sm">
                DOWNLOAD MT4 (.EX4)
            </a>
        </div>
    </div>
<?php endif; ?>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
    <?php if(!isset($accounts) || empty($accounts)): ?>
        <div class="lg:col-span-3 py-20 bg-[#111] border border-dashed border-white/10 rounded-xl flex flex-col items-center justify-center text-center">
            <div class="w-20 h-20 bg-white/5 rounded-3xl flex items-center justify-center text-gray-700 mb-6 border border-white/5">
                <i class="fas fa-folder-open text-3xl"></i>
            </div>
            <h3 class="text-white font-black text-xl mb-2">Belum Ada Portofolio</h3>
            <p class="text-gray-500 text-xs mb-8 max-w-xs uppercase tracking-widest font-bold">Mulai dengan menambahkan akun trading Anda untuk memonitor performa secara live</p>
            <a href="<?= base_url('user/dashboard/portofolio/create') ?>" class="bg-accent text-black font-black px-10 py-4 rounded-2xl shadow-xl hover:scale-105 transition-all">
                TAMBAH AKUN SEKARANG
            </a>
        </div>
    <?php else: ?>
        <?php foreach($accounts as $acc): ?>
            <div class="bg-[#111] rounded-xl border border-white/5 p-8 transition-all group relative overflow-hidden">
                <!-- Status Badge -->
                <div class="absolute top-0 right-0 p-4">
                    <?php if($acc['is_online']): ?>
                        <div class="flex items-center gap-2 bg-accent/10 text-accent px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border border-accent/20">
                            <span class="w-2 h-2 bg-accent rounded-full animate-ping"></span>
                            Online
                        </div>
                    <?php else: ?>
                        <div class="flex items-center gap-2 bg-red-500/10 text-red-500 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border border-red-500/20 opacity-50">
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                            Offline
                        </div>
                    <?php endif; ?>
                </div>

                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h3 class="text-white font-black text-2xl leading-none group-hover:text-accent transition"><?= esc($acc['account_name']) ?></h3>
                        <p class="text-gray-600 text-[10px] font-bold uppercase tracking-[0.2em] mt-2 italic"><?= esc($acc['broker']) ?></p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4 p-5 bg-white/5 rounded-xl">
                    <div>
                        <span class="text-[9px] text-gray-500 uppercase font-black tracking-widest mb-1 block">Account ID</span>
                        <p class="text-white font-mono font-bold text-base"><?= esc($acc['account_login']) ?></p>
                    </div>
                    <div>
                        <span class="text-[9px] text-gray-500 uppercase font-black tracking-widest mb-1 block">Equity</span>
                        <p class="text-accent font-mono font-bold text-base tracking-tighter">$<?= number_format($acc['equity'], 2) ?></p>
                    </div>
                </div>

                <!-- Mini Chart -->
                <div class="h-[60px] w-full mb-8 relative">
                    <canvas id="chart-<?= $acc['account_login'] ?>" 
                            class="portfolio-mini-chart"
                            data-initial="<?= $acc['total_deposits'] ?: $acc['balance'] ?>"
                            data-balance="<?= $acc['balance'] ?>"
                            data-equity="<?= $acc['equity'] ?>"></canvas>
                </div>

                <div class="space-y-3">
                    <a href="<?= base_url('portofolio/' . $acc['account_login']) ?>" target="_blank" class="w-full bg-white/5 hover:bg-white text-gray-400 hover:text-black font-black py-3 rounded-xl text-center text-[10px] transition uppercase tracking-[0.2em] block border border-white/5">
                        <i class="fas fa-external-link-alt mr-1"></i> Lihat Publik
                    </a>
                    <div class="flex gap-2">
                        <a href="<?= base_url('portofolio_eas/portofolio-almai.ex5') ?>" download class="flex-1 bg-white/5 hover:bg-white/10 text-white font-black py-3 rounded-xl text-center text-[10px] transition uppercase tracking-[0.2em] block border border-white/5">
                            <i class="fas fa-download mr-1"></i> MT5 (.ex5)
                        </a>
                        <a href="<?= base_url('portofolio_eas/portofolio-almai.ex4') ?>" download class="flex-1 bg-white/5 hover:bg-white/10 text-white font-black py-3 rounded-xl text-center text-[10px] transition uppercase tracking-[0.2em] block border border-white/5">
                            <i class="fas fa-download mr-1"></i> MT4 (.ex4)
                        </a>
                        <a href="<?= base_url('user/dashboard/portofolio/delete/' . $acc['id']) ?>" onclick="return confirm('Hapus portofolio ini?')" class="w-12 bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white rounded-xl transition flex items-center justify-center border border-red-500/10">
                            <i class="fas fa-trash-alt text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="bg-[#111] rounded-xl border border-white/10 p-10 relative overflow-hidden">
    <div class="flex items-center gap-5 mb-8 relative z-10">
        <div class="w-16 h-16 bg-accent/20 rounded-xl flex items-center justify-center border border-accent/20 shadow-2xl shadow-accent/10">
            <i class="fas fa-satellite-dish text-accent text-2xl"></i>
        </div>
        <div>
            <h4 class="text-white font-black text-xl tracking-tight leading-none mb-2">Sinkronisasi Data LIVE</h4>
            <p class="text-[10px] text-gray-500 uppercase tracking-[0.2em] font-bold">Ikuti Langkah Ini Agar Status Menjadi Online</p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 text-sm text-gray-400 relative z-10">
        <div class="space-y-6">
            <div class="flex gap-4">
                <span class="flex-shrink-0 w-8 h-8 bg-white/5 rounded-xl border border-white/10 flex items-center justify-center text-xs font-bold text-white shadow-xl">1</span>
                <div>
                    <p class="text-white font-bold mb-1">Download & Pasang EA</p>
                    <p class="text-xs leading-relaxed opacity-70">Download file <strong>.ex5 (MT5)</strong> atau <strong>.ex4 (MT4)</strong> di atas, copy dan paste ke folder <span class="text-accent font-mono">MQL5/Experts</span> (MT5) atau <span class="text-accent font-mono">MQL4/Experts</span> (MT4) terminal MetaTrader Anda.</p>
                </div>
            </div>
            <div class="flex gap-4">
                <span class="flex-shrink-0 w-8 h-8 bg-white/5 rounded-xl border border-white/10 flex items-center justify-center text-xs font-bold text-white shadow-xl">2</span>
                <div>
                    <p class="text-white font-bold mb-1">Attach EA ke Chart</p>
                    <p class="text-xs leading-relaxed opacity-70">Buka sembarang chart (XAUUSD/Forex), klik kanan EA <span class="italic">"Portfolio-Almai"</span> dan pilih <strong>Attach to Chart</strong>.</p>
                </div>
            </div>
        </div>
        <div class="space-y-6">
            <div class="flex gap-4">
                <span class="flex-shrink-0 w-8 h-8 bg-white/5 rounded-xl border border-white/10 flex items-center justify-center text-xs font-bold text-white shadow-xl">3</span>
                <div>
                    <p class="text-white font-bold mb-1">Izinkan WebRequest</p>
                    <p class="text-xs leading-relaxed opacity-70">Di Tools > Options > Expert Advisors, centang <span class="text-accent italic">"Allow WebRequest..."</span> dan tambahkan URL: <br><span class="font-mono bg-black/50 px-2 py-0.5 rounded border border-white/10 mt-1 inline-block">https://almai.id/</span></p>
                </div>
            </div>
            <div class="flex gap-4 text-accent/90">
                <div class="w-8 h-8 bg-accent/20 rounded-xl flex items-center justify-center flex-shrink-0 border border-accent/20">
                    <i class="fas fa-check text-[10px]"></i>
                </div>
                <p class="text-xs leading-relaxed font-bold">Status akan berubah <span class="underline">Online</span> secara otomatis dalam 60 detik setelah EA berhasil terhubung.</p>
            </div>
        </div>
    </div>

    <!-- Decorative Glow -->
    <div class="absolute -top-24 -right-24 w-80 h-80 bg-accent/5 blur-[120px] rounded-full"></div>
</div>
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
            
            const isProfit = equity >= balance;
            const lineColor = isProfit ? '#33e818' : '#ef4444';
            
            let gradient = ctx.createLinearGradient(0, 0, 0, 60);
            gradient.addColorStop(0, isProfit ? 'rgba(51, 232, 24, 0.2)' : 'rgba(239, 68, 68, 0.2)');
            gradient.addColorStop(1, 'rgba(0, 0, 0, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Start', 'Balance', 'Equity'],
                    datasets: [{
                        data: [initial * 0.95, balance, equity],
                        borderColor: lineColor,
                        borderWidth: 1.5,
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false }
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
