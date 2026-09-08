<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    .blur-text-container {
        display: inline-flex;
    }

    .blur-text-container span {
        display: inline-block;
        animation: blur-wave 8s ease-in-out infinite;
    }

    .blur-text-container span:nth-child(n) {
        animation-delay: calc(0.2s * var(--i));
    }
    
    @keyframes blur-wave {
        0%, 100% {
            filter: blur(0px);
            opacity: 1;
            transform: scale(1);
        }
        50% {
            filter: blur(4px);
            opacity: 0.8;
            transform: scale(0.99);
        }
    }

    @keyframes blur-wave {

        0%,
        100% {
            filter: blur(0px);
            opacity: 1;
        }

        50% {
            filter: blur(8px);
            opacity: 0.6;
        }
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: 0.2rem;
        width: 100%;
    }

    @media (min-width: 640px) {
        .calendar-grid {
            gap: 0.5rem;
        }
    }

    .calendar-day {
        aspect-ratio: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        border-radius: 0.35rem;
        color: #9ca3af;
        cursor: pointer;
        transition: all 0.2s;
    }

    @media (min-width: 640px) {
        .calendar-day {
            font-size: 0.8rem;
            border-radius: 0.5rem;
        }
    }

    .calendar-day:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: white;
    }

    .calendar-day.active {
        background-color: #33E818;
        color: black;
        font-weight: bold;
        box-shadow: 0 0 10px rgba(51, 232, 24, 0.4);
    }

    @media (min-width: 640px) {
        .calendar-day.active {
            box-shadow: 0 0 15px rgba(51, 232, 24, 0.4);
        }
    }

    .font-public-sans {
        font-family: 'Public Sans', sans-serif !important;
    }

    .font-montserrat {
        font-family: 'Montserrat', sans-serif !important;
    }

    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
        border-radius: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 4px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    /* Marquee Styles */
    .marquee-container {
        overflow: hidden;
        width: 100%;
        position: relative;
    }

    .marquee-track {
        display: flex;
        width: max-content;
        animation: marquee 28s linear infinite;
    }

    .marquee-content {
        display: flex;
        align-items: center;
        gap: 3rem;
        padding-right: 3rem;
        white-space: nowrap;
    }

    @keyframes marquee {
        from {
            transform: translateX(0);
        }

        to {
            transform: translateX(-50%);
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Hero Section with Video Background -->
<section class="relative min-h-screen flex flex-col pt-20 overflow-hidden">
    <!-- Video Background -->
    <div class="absolute inset-0 z-0">
        <video autoplay muted loop playsinline class="w-full h-full object-cover">
            <source src="<?= base_url('images/home.mp4') ?>" type="video/mp4">
        </video>
        <!-- Audio Background (MP4 format for autoplay compatibility) -->
        <video id="introAudio" loop style="display:none;">
            <source src="<?= base_url('images/song.jpg.mp4') ?>" type="video/mp4">
        </video>
        <script>
            // Memastikan audio diputar setelah interaksi pertama jika autoplay diblokir
            document.addEventListener('click', function() {
                const audio = document.getElementById('introAudio');
                if (audio.paused) {
                    audio.play().catch(e => console.log("Audio play blocked until interaction"));
                }
            }, {
                once: true
            });

            // Mencoba autoplay saat load
            window.addEventListener('load', () => {
                const audio = document.getElementById('introAudio');
                audio.play().catch(e => {
                    console.log("Autoplay blocked, waiting for interaction.");
                });
            });
        </script>

        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-black/70"></div>
    </div>

    <!-- Grid Pattern Overlay -->
    <div class="absolute inset-0 bg-grid z-[1] pointer-events-none"></div>

    <!-- Glow Effect -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-accent/20 blur-[120px] rounded-full pointer-events-none z-[1]"></div>

    <!-- Content Wrapper -->
    <div class="flex-1 flex items-center justify-center relative z-10 px-0 sm:px-6"> 
        <div class="w-full max-w-none text-center px-4 sm:px-10 md:px-16 lg:px-20">
            <div class="flex flex-col items-center translate-y-8 sm:translate-y-10 md:translate-y-12">
                <!-- Referral Badge (if any) -->
                <?php if (isset($referralWpa) && $referralWpa): ?>
                    <div class="inline-flex items-center gap-3 bg-white/5 backdrop-blur-md px-4 py-2 rounded-full border border-accent/30 mb-6" data-aos="fade-down" data-aos-delay="400">
                        <?php 
                            $photo = $referralWpa['photo'];
                            if ($photo && strpos($photo, 'http') !== 0) {
                                $photo = base_url('file/' . ltrim(preg_replace('/^writable\//', '', $photo), '/'));
                            }
                        ?>
                        <img src="<?= $photo ?: base_url('images/almai-full.png') ?>" alt="<?= esc($referralWpa['name']) ?>" class="w-8 h-8 rounded-full border border-accent/50 object-cover">
                        <div class="flex flex-col items-start leading-none text-left">
                            <span class="text-[10px] text-accent font-bold uppercase tracking-widest">Referral Member</span>
                            <span class="text-xs text-white font-black"><?= esc($referralWpa['name']) ?></span>
                        </div>
                    </div>
                <?php endif; ?>
                
         <br><br>  <br>     
        <!-- Badge -->
        <div class="mb-4">
            <span class="px-5 py-2 text-xs md:text-sm font-semibold tracking-widest text-green-400 border border-green-500 rounded-full bg-black/60 backdrop-blur">
                ✦ AI AUTO SIGNAL
            </span>
        </div>

<!-- Main Combined Title (STATIC VERSION - NO ANIMATION) -->

<h1 class="font-black tracking-tighter uppercase text-center flex justify-center items-center gap-2 mt-6 md:mt-10 leading-none">

    <?php $fontSize = "clamp(2.8rem, 11vw, 8rem)"; ?>

    <span
        class="text-white"
        style="font-size: <?= $fontSize ?>;">
        Almai 
    </span>

    <span
        class="text-transparent bg-clip-text bg-gradient-to-r from-accent via-green-400 to-emerald-500"
        style="font-size: <?= $fontSize ?>;">
        Signal
    </span>

</h1>

<!-- Description -->
<p class="text-xs sm:text-base md:text-lg text-gray-400 mt-6 max-w-4xl mx-auto leading-relaxed font-medium" data-aos="fade-up" data-aos-delay="200">
    Almai Signal adalah platform AI trading otomatis dengan sinyal real-time berkualitas tinggi, dilengkapi kelas edukasi eksklusif bersama — <span class="text-white font-semibold">  Wakil Penasihat Berjangka (WPA).</span>
</p>

<!-- Buttons Area -->
<div class="flex flex-row items-center justify-center gap-4 sm:gap-6 mt-12 md:mt-16" data-aos="fade-up" data-aos-delay="300">
    <?php 
    $refCode = (isset($referralWpa) && $referralWpa && !empty($referralWpa['code_referral'])) ? $referralWpa['code_referral'] : '';
    $refUrl = !empty($refCode) ? '?ref=' . urlencode($refCode) : '';
    ?>
<a href="https://almai.id/advokasi" target="_blank" 
   class="flex-1 sm:flex-none px-4 sm:px-10 py-3.5 sm:py-4 bg-accent text-black font-black text-[10px] sm:text-sm rounded-xl hover:bg-white transition-all transform hover:scale-105 shadow-[0_0_20px_rgba(51,232,24,0.3)] flex items-center justify-center gap-2 tracking-tighter sm:tracking-widest">
    <i class="fas fa-laptop text-[8px] sm:text-xs"></i> 
    <span>Pelajari Program</span>
</a>

<a href="https://almai.id/daftar-wpa" target="_blank" 
   class="flex-1 sm:flex-none px-4 sm:px-10 py-3.5 sm:py-4 bg-white/5 border border-white/10 text-white font-bold text-[10px] sm:text-sm rounded-xl hover:bg-white/10 transition backdrop-blur-md flex items-center justify-center gap-2 tracking-tighter sm:tracking-widest">
    <i class="fas fa-graduation-cap text-[8px] sm:text-xs"></i> 
    <span class="whitespace-nowrap">Daftar Menjadi WPA</span>
</a>
</div>

                <!-- Balanced Spacer to prevent crowding -->
                <div class="h-10 md:h-16"></div>

                <!-- Social Proof / User Growth (Refined Size) -->
                <div class="flex flex-col md:flex-row items-center justify-center gap-6 mt-0" data-aos="fade-up" data-aos-delay="400">
                    <!-- Total Users Card -->
                    <div class="flex items-center gap-3 px-5 py-2.5 bg-white/5 backdrop-blur-md rounded-full border border-white/10 shadow-lg hover:bg-white/10 transition duration-300">
                        <div class="flex -space-x-3">
                            <!-- Badge Avatars (Dynamic) -->
                            <?php for ($i = 0; $i < 3; $i++): ?>
                                <div class="w-10 h-10 rounded-full border-2 border-black flex items-center justify-center text-sm text-white font-black transition-all duration-500" id="badge-initial-<?= $i ?>" style="background: linear-gradient(135deg, #33e818 0%, #1a7a0c 100%);"></div>
                            <?php endfor; ?>
                        </div>
                        <div class="flex flex-col items-start justify-center">
                            <p class="text-[9px] uppercase tracking-wider text-gray-400 font-bold mb-0.5 text-left leading-none">Total Users</p>
                            <p class="text-xl sm:text-2xl text-accent font-black leading-none">
                                <?= number_format($totalUsers) ?>+
                            </p>
                        </div>
                    </div>

                    <!-- Testimony Card (New - PERFECT PILL SHAPE) -->
                    <div id="testimony-container" class="flex items-center gap-4 px-8 py-3 bg-white/5 backdrop-blur-md rounded-full border border-white/10 shadow-lg hover:bg-white/10 transition-all duration-300 w-full max-w-[290px] h-[95px] overflow-hidden">
                        <div class="flex flex-col items-start w-full h-full justify-center">
                            <div class="flex items-center justify-between w-full mb-1">
                                <span id="testimony-name" class="text-white font-bold text-[10px] tracking-tight transition-all duration-500 uppercase">Andi Wijaya</span>
                                <div class="flex items-center gap-0.5 text-[6px] text-yellow-500">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                            <p id="testimony-text" class="text-[9px] text-gray-400 font-medium leading-[1.3] italic w-full text-left transition-all duration-500">
                                Bimbingan Almai sangat membantu saya<br>memahami market dengan lebih objektif.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Animated New User Toast -->
                <div id="user-toast" class="h-6 flex items-center justify-center gap-2 transition-all duration-500 opacity-0 transform translate-y-2 mt-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                    <p class="text-[10px] text-gray-400">
                        <span id="toast-name" class="font-bold text-white">User</span> <span class="opacity-70">sudah bergabung</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>

    <!-- SECTION 2 : MARQUE (Inside Hero Bottom) -->
    <div class="py-4 sm:py-6 border-t border-white/10 bg-black/40 backdrop-blur-md overflow-hidden relative z-10 w-full">
        <div class="marquee-container">
            <div class="marquee-track">

                <!-- SET 1 -->
                <div class="marquee-content">
                    <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500/50 uppercase flex items-center gap-2">
                        <i class="fa-solid fa-building-columns"></i> BANK INDONESIA
                    </span>
                    <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500/50 uppercase flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved"></i> OJK
                    </span>
                    <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500/50 uppercase flex items-center gap-2">
                        <i class="fa-solid fa-landmark"></i> BAPPEBTI
                    </span>
                    <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500/50 uppercase flex items-center gap-2">
                        <i class="fa-solid fa-globe"></i> ASPEBTINDO
                    </span>
                    <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500/50 uppercase flex items-center gap-2">
                        <i class="fa-solid fa-landmark"></i> BNSP PBK
                    </span>
                    <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500/50 uppercase flex items-center gap-2">
                        <i class="fa-solid fa-globe"></i> CFX
                    </span>
                    <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500/50 uppercase flex items-center gap-2">
                        <i class="fa-solid fa-globe"></i> JFX
                    </span>
                    <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500/50 uppercase flex items-center gap-2">
                        <i class="fa-solid fa-globe"></i> ICDX
                    </span>
                    <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500/50 uppercase flex items-center gap-2">
                        <i class="fa-solid fa-globe"></i> LPK PBK
                    </span>
                </div>

                <!-- SET 2 -->
                <div class="marquee-content">
                    <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500/50 uppercase flex items-center gap-2">
                        <i class="fa-solid fa-building-columns"></i> BANK INDONESIA
                    </span>
                    <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500/50 uppercase flex items-center gap-2">
                        <i class="fa-solid fa-shield-halved"></i> OJK
                    </span>
                    <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500/50 uppercase flex items-center gap-2">
                        <i class="fa-solid fa-landmark"></i> BAPPEBTI
                    </span>
                    <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500/50 uppercase flex items-center gap-2">
                        <i class="fa-solid fa-globe"></i> KOMDIGI
                    </span>
                    <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500/50 uppercase flex items-center gap-2">
                        <i class="fa-solid fa-globe"></i> ASPEBTINDO
                    </span>
                    <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500/50 uppercase flex items-center gap-2">
                        <i class="fa-solid fa-landmark"></i> BNSP PBK
                    </span>
                    <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500/50 uppercase flex items-center gap-2">
                        <i class="fa-solid fa-globe"></i> CFX
                    </span>
                    <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500/50 uppercase flex items-center gap-2">
                        <i class="fa-solid fa-globe"></i> JFX
                    </span>
                    <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500/50 uppercase flex items-center gap-2">
                        <i class="fa-solid fa-globe"></i> ICDX
                    </span>
                    <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500/50 uppercase flex items-center gap-2">
                        <i class="fa-solid fa-globe"></i> LPK PBK
                    </span>
                </div>
            </div>
        </div>
    </div>


</section>



<!-- SECTION 1: AI SIGNAL - DYNAMIC FROM BOT API -->
<section id="signals" class="py-20 relative bg-[#0a0a0a] border-t border-white/5 overflow-hidden">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
        <div class="text-center mb-10 max-w-3xl mx-auto" data-aos="fade-up">
            <span id="sigHeaderBadge" class="inline-flex items-center gap-2 px-4 py-1.5 text-[10px] font-bold bg-[#33E818]/10 text-[#33E818] border border-[#33E818]/20 rounded-full uppercase tracking-widest mb-4"><i class="fas fa-robot"></i> AI Auto Signal</span>
            <h2 id="sigHeaderTitle" class="text-2xl sm:text-4xl font-bold text-white mb-5 leading-tight">Signal Alert <span class="text-[#33E818]">Real-Time</span></h2>
            <p id="sigHeaderDesc" class="text-gray-400 text-xs sm:text-sm leading-relaxed">Menampilkan 3 sinyal aktif dengan performa profit tertinggi. Data terupdate otomatis setiap 30 detik dari AI Scanner.</p>
        </div>
        <div class="flex items-center justify-between bg-white/5 border border-white/10 rounded-2xl p-4 mb-8" data-aos="fade-up">
            <div class="flex items-center gap-3">
                <span class="w-2.5 h-2.5 rounded-full bg-[#33E818] animate-pulse" style="box-shadow:0 0 6px #33E818,0 0 12px #33E818"></span>
                <span class="text-xs sm:text-sm font-bold uppercase tracking-wider text-white">AI Scanner: <span class="text-[#33E818]" id="sigStatus">CONNECTING...</span></span>
            </div>
            <span id="sigLastUpdate" class="text-[10px] sm:text-xs text-gray-500 uppercase tracking-widest">--:--:--</span>
        </div>
        <div id="sigCardGrid" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6" data-aos="fade-up" data-aos-delay="100">
            <div style="background:linear-gradient(90deg,rgba(255,255,255,0.03) 25%,rgba(255,255,255,0.08) 50%,rgba(255,255,255,0.03) 75%);background-size:200% 100%;animation:shimmer 1.5s infinite" class="rounded-2xl h-[480px]"></div>
            <div style="background:linear-gradient(90deg,rgba(255,255,255,0.03) 25%,rgba(255,255,255,0.08) 50%,rgba(255,255,255,0.03) 75%);background-size:200% 100%;animation:shimmer 1.5s infinite" class="rounded-2xl h-[480px]"></div>
            <div style="background:linear-gradient(90deg,rgba(255,255,255,0.03) 25%,rgba(255,255,255,0.08) 50%,rgba(255,255,255,0.03) 75%);background-size:200% 100%;animation:shimmer 1.5s infinite" class="rounded-2xl h-[480px]"></div>
        </div>
        <div class="text-center mt-12"><p class="text-gray-500 text-xs sm:text-sm">*Sinyal real-time dari Almai AI Bot. Selalu terapkan manajemen risiko yang disiplin.</p></div>
    </div>
</section>
<style>@keyframes shimmer{0%{background-position:-200% 0}100%{background-position:200% 0}}</style>
<script>
(function(){
// Data portfolio di-embed langsung (update file ini di cPanel saat data berubah)
var EMBEDDED_PORTFOLIO={"XAUUSD":{"modalAwal":10000000,"modalSaatIni":15519330.000000034,"totalProfit":5519330.000000033,"totalSignals":7,"wins":5,"losses":2,"lastUpdated":"2026-07-02T00:27:32.385Z","resetDate":"2026-07-01"},"XAGUSD":{"modalAwal":10000000,"modalSaatIni":10000000,"totalProfit":0,"totalSignals":0,"wins":0,"losses":0,"lastUpdated":"2026-07-01T00:00:00.000Z","resetDate":"2026-07-01"},"USOIL":{"modalAwal":10000000,"modalSaatIni":9887009.499999998,"totalProfit":-112990.50000000173,"totalSignals":2,"wins":1,"losses":1,"lastUpdated":"2026-07-01T16:46:07.353Z","resetDate":"2026-07-01"},"XNGUSD":{"modalAwal":10000000,"modalSaatIni":7790595.000000002,"totalProfit":-2209404.9999999977,"totalSignals":5,"wins":2,"losses":3,"lastUpdated":"2026-07-02T04:52:00.381Z","resetDate":"2026-07-01"},"AUDUSD":{"modalAwal":10000000,"modalSaatIni":10107610.000000007,"totalProfit":107610.00000000806,"totalSignals":2,"wins":2,"losses":0,"lastUpdated":"2026-07-01T16:47:42.999Z","resetDate":"2026-07-01"},"GBPUSD":{"modalAwal":10000000,"modalSaatIni":10000000,"totalProfit":0,"totalSignals":0,"wins":0,"losses":0,"lastUpdated":"2026-07-01T00:00:00.000Z","resetDate":"2026-07-01"},"EURUSD":{"modalAwal":10000000,"modalSaatIni":9605430.000000004,"totalProfit":-394569.9999999964,"totalSignals":2,"wins":1,"losses":1,"lastUpdated":"2026-07-01T16:48:14.383Z","resetDate":"2026-07-01"},"JP225":{"modalAwal":10000000,"modalSaatIni":6857788.000000574,"totalProfit":-3142211.999999426,"totalSignals":5,"wins":1,"losses":4,"lastUpdated":"2026-07-02T00:37:50.803Z","resetDate":"2026-07-01"},"US30":{"modalAwal":10000000,"modalSaatIni":10000000,"totalProfit":0,"totalSignals":0,"wins":0,"losses":0,"lastUpdated":"2026-07-01T00:00:00.000Z","resetDate":"2026-07-01"},"SPX500":{"modalAwal":10000000,"modalSaatIni":10000000,"totalProfit":0,"totalSignals":0,"wins":0,"losses":0,"lastUpdated":"2026-07-01T00:00:00.000Z","resetDate":"2026-07-01"}};
var EMBEDDED_SIGNALS=[{"id":"SIG_1782968109139_6450xvudm","type":"AUTO_SIGNAL","pair":"XNGUSD","timeframe":"M15","confirmTimeframes":["M30","H1"],"order":"LONG","entry":3.195,"sl":3.145,"tp1":3.295,"tp2":0,"tp3":0,"confidence":80,"status":"ACTIVE","createdAt":"2026-07-02T04:55:09.139Z","analysis":{"m15":{"side":"LONG","confidence":80,"pattern":"Bullish Bollinger Bands Squeeze"},"m30":{"side":"LONG","confidence":80,"pattern":"Bullish Breakout"},"h1":{"side":"LONG","confidence":80,"pattern":"Bullish Breakout"}},"userId":"AUTO_SIGNAL","savedAt":"2026-07-02T04:55:09.140Z"},{"id":"SIG_1782951516599_4wugts8vc","type":"AUTO_SIGNAL","pair":"AUDUSD","timeframe":"M15","confirmTimeframes":["M30","H1"],"order":"LONG","entry":0.6886,"sl":0.6836,"tp1":0.6986,"tp2":0,"tp3":0,"confidence":80,"status":"ACTIVE","createdAt":"2026-07-02T00:18:36.599Z","analysis":{"m15":{"side":"LONG","confidence":80,"pattern":"Bullish Rebound"},"m30":{"side":"LONG","confidence":80,"pattern":"Bullish Trend Reversal"},"h1":{"side":"LONG","confidence":80,"pattern":"Bullish Bollinger Bands Squeeze"}},"userId":"AUTO_SIGNAL","savedAt":"2026-07-02T00:18:36.599Z"},{"id":"SIG_1782951496321_9byi2v3yw","type":"AUTO_SIGNAL","pair":"USOIL","timeframe":"M15","confirmTimeframes":["M30","H1"],"order":"LONG","entry":67.9,"sl":62.9,"tp1":77.9,"tp2":0,"tp3":0,"confidence":80,"status":"ACTIVE","createdAt":"2026-07-02T00:18:16.321Z","analysis":{"m15":{"side":"LONG","confidence":80,"pattern":"Bullish Rebound"},"m30":{"side":"LONG","confidence":80,"pattern":"Bullish Bollinger Bands Squeeze"},"h1":{"side":"LONG","confidence":80,"pattern":"Bullish Reversal"}},"userId":"AUTO_SIGNAL","savedAt":"2026-07-02T00:18:16.321Z"},{"id":"SIG_1782951248149_7vj238ir4","type":"AUTO_SIGNAL","pair":"EURUSD","timeframe":"M15","confirmTimeframes":["M30","H1"],"order":"LONG","entry":1.1382,"sl":1.1332,"tp1":1.1482,"tp2":0,"tp3":0,"confidence":80,"status":"ACTIVE","createdAt":"2026-07-02T00:14:08.149Z","analysis":{"m15":{"side":"LONG","confidence":80,"pattern":"Bullish Crossover"},"m30":{"side":"LONG","confidence":80,"pattern":"Bullish Bollinger Bands Squeeze"},"h1":{"side":"LONG","confidence":80,"pattern":"Bullish Breakout"}},"userId":"AUTO_SIGNAL","savedAt":"2026-07-02T00:14:08.149Z"}];
const API_SIGNALS='/signal-data/signals.json';
const API_PORTFOLIO='/signal-data/portfolio.json';
const PAIR_INFO={'XAUUSD':{name:'EMAS',display:'<svg style="display:inline;width:1.5rem;height:1.5rem;vertical-align:middle;margin-right:4px;margin-top:-2px" viewBox="0 0 24 24" fill="none"><rect x="2" y="10" width="20" height="8" rx="1" fill="#FFD700" stroke="#B8860B" stroke-width="0.5"/><rect x="4" y="5" width="16" height="7" rx="1" fill="#FFC107" stroke="#B8860B" stroke-width="0.5"/><rect x="7" y="1" width="10" height="6" rx="1" fill="#FFEB3B" stroke="#B8860B" stroke-width="0.5"/></svg> EMAS (XAUUSD)'},'XAGUSD':{name:'PERAK',display:'🥈 PERAK (XAGUSD)'},'USOIL':{name:'MINYAK',display:'🛢️ MINYAK (USOIL)'},'XNGUSD':{name:'GAS ALAM',display:'🌿 GAS ALAM (XNGUSD)'},'AUDUSD':{name:'AUDUSD',display:'🇦🇺 AUDUSD'},'GBPUSD':{name:'GBPUSD',display:'💷 GBPUSD'},'EURUSD':{name:'EURUSD',display:'💶 EURUSD'},'JP225':{name:'NIKKEI',display:'<img src="https://flagcdn.com/w40/jp.png" style="display:inline;width:1.5rem;height:1rem;vertical-align:middle;margin-right:4px;margin-top:-2px;border-radius:2px"> NIKKEI (JP225)'},'US30':{name:'DOW',display:'<img src="https://flagcdn.com/w40/us.png" style="display:inline;width:1.5rem;height:1rem;vertical-align:middle;margin-right:4px;margin-top:-2px;border-radius:2px"> DOW (US30)'}};
const CFG={modalAwal:10000000,lot:0.1};
function fp(p,pair){if(!p)return'0.00';var n=Number(p);if(['JP225','US30'].indexOf(pair)>=0)return n.toLocaleString('en-US',{minimumFractionDigits:2,maximumFractionDigits:2});if(['XAUUSD','XAGUSD','USOIL','XNGUSD'].indexOf(pair)>=0)return n.toFixed(2);return n.toFixed(5);}
function fr(a){return'Rp '+Math.abs(Math.round(a)).toLocaleString('id-ID');}
function frs(a){return(a>=0?'+':'-')+'Rp '+Math.abs(Math.round(a)).toLocaleString('id-ID');}
function fd(d){if(!d)return'--';var dt=new Date(d);return dt.toLocaleDateString('id-ID',{day:'2-digit',month:'2-digit',year:'numeric'})+', '+dt.toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit',hour12:false,timeZone:'Asia/Jakarta'})+' WIB';}
function gOT(s){var side=(s.side||s.order||'').toUpperCase();return(side==='LONG'||side==='BUY')?'BUY':'SELL';}
function gConf(s){if(s.confidence)return s.confidence;try{return JSON.parse(s.data_json||'{}').confidence||80;}catch(e){return 80;}}
function gTP(s,n){var v=s['tp'+n];return(typeof v==='object'&&v)?v.price||0:v||0;}
function gCA(s){try{var d=JSON.parse(s.data_json||'{}');return d.createdAt||s.timestamp||'';}catch(e){return s.timestamp||'';}}
function gAn(s){try{var d=JSON.parse(s.data_json||'{}');if(d.analysis){if(typeof d.analysis==='string')return d.analysis;if(d.analysis.m15){var dir=(d.analysis.m15.side==='LONG'||d.analysis.m15.side==='BUY')?'naik':'turun';return'Sinyal '+dir+' terdeteksi di M15 ('+(d.analysis.m15.confidence||gConf(s))+'%), dikonfirmasi searah oleh timeframe M30 dan H1 dengan pola '+(d.analysis.m15.pattern||'teknikal')+'.';}}}catch(e){}return'Sinyal '+(gOT(s)==='BUY'?'naik':'turun')+' terdeteksi di M15 ('+gConf(s)+'%), dikonfirmasi searah oleh timeframe M30 dan H1.';}
function renderSignal(sig,pf){var pair=sig.pair||'UNKNOWN',info=PAIR_INFO[pair]||{name:pair,display:pair};var order=gOT(sig),isBuy=order==='BUY',conf=gConf(sig);var entry=Number(sig.entry),sl=Number(sig.sl),tp1=Number(gTP(sig,1));var p=pf[pair]||{modalAwal:CFG.modalAwal,modalSaatIni:CFG.modalAwal,totalProfit:0};var badge=isBuy?'<span style="color:#4ade80;font-weight:900">BUY / LONG</span>':'<span style="color:#f87171;font-weight:900">SELL / SHORT</span>';var borderColor=isBuy?'#22c55e':'#ef4444';return'<div style="background:#0c0d0f;border:1px solid rgba(255,255,255,0.08);border-radius:1rem;overflow:hidden;border-top:2px solid '+borderColor+'"><div style="padding:1.25rem 1.25rem 1rem"><div style="display:flex;justify-content:space-between;margin-bottom:0.75rem"><span style="font-size:10px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.1em">🚨 SIGNAL ALERT</span><span style="font-size:10px;font-weight:700;color:#33E818;text-transform:uppercase">📈 '+conf+'%</span></div><h3 style="font-size:1.125rem;font-weight:900;color:white;margin-bottom:4px">'+info.display+'</h3><div style="font-size:11px;color:#6b7280">🕐 '+fd(gCA(sig))+'</div></div><div style="padding:0 1.25rem 1rem"><div style="margin-bottom:0.75rem">'+badge+'</div><div style="display:flex;justify-content:space-between;align-items:center;background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);border-radius:0.75rem;padding:0.625rem 1rem"><span style="font-size:10px;color:#6b7280;font-weight:700;text-transform:uppercase">✅ Price To Date</span><span style="font-family:monospace;font-weight:900;color:white;font-size:14px">'+fp(entry,pair)+'</span></div></div><div style="border-top:1px solid rgba(255,255,255,0.06);margin:0 1.25rem"></div><div style="padding:1.25rem;display:flex;flex-direction:column;gap:0.625rem"><span style="font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.5rem;display:block">📌 Level Trading</span><div style="display:flex;justify-content:space-between"><span style="color:#9ca3af;font-size:12px">🚀 Entry</span><span style="color:white;font-family:monospace;font-weight:700;font-size:14px">'+fp(entry,pair)+'</span></div><div style="display:flex;justify-content:space-between"><span style="color:#9ca3af;font-size:12px">🔴 Stop Loss</span><span style="color:#ef4444;font-family:monospace;font-weight:700;font-size:14px">'+fp(sl,pair)+' <span style="color:#4b5563;font-size:10px">(-Rp 1.000.000 | -10.0%)</span></span></div><div style="display:flex;justify-content:space-between"><span style="color:#9ca3af;font-size:12px">🎯 Take Profit</span><span style="color:#4ade80;font-family:monospace;font-weight:700;font-size:14px">'+fp(tp1,pair)+' <span style="color:#4b5563;font-size:10px">(+Rp 2.000.000 | +20.0%)</span></span></div></div><div style="border-top:1px solid rgba(255,255,255,0.06);margin:0 1.25rem"></div><div style="padding:1.25rem"><span style="font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;display:block;margin-bottom:0.5rem">🧠 Analisis AI</span><p style="font-size:11px;color:#9ca3af;line-height:1.5;font-style:italic">'+gAn(sig)+'</p></div><div style="border-top:1px solid rgba(255,255,255,0.06);margin:0 1.25rem"></div><div style="padding:1.25rem;background:rgba(255,255,255,0.01)"><span style="font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;display:block;margin-bottom:0.75rem">PORTOFOLIO</span><div style="display:flex;flex-direction:column;gap:0.5rem;font-size:12px"><div style="display:flex;justify-content:space-between"><span style="color:#6b7280">🪙 Modal Awal</span><span style="color:white;font-family:monospace;font-weight:600">'+fr(CFG.modalAwal)+' | Lot: '+CFG.lot+' | '+info.name+' ONLY</span></div><div style="display:flex;justify-content:space-between"><span style="color:#6b7280">💰 Modal Saat ini</span><span style="color:white;font-family:monospace;font-weight:600">'+fr(p.modalSaatIni)+'</span></div><div style="display:flex;justify-content:space-between"><span style="color:#6b7280">💱 Total Profit</span><span style="color:'+(p.totalProfit>=0?'#33E818':'#ef4444')+';font-family:monospace;font-weight:700">'+frs(p.totalProfit)+'</span></div></div></div></div>';}
function renderReport(pair,pf){var info=PAIR_INFO[pair]||{name:pair,display:pair};var pctReturn=((pf.modalSaatIni-pf.modalAwal)/pf.modalAwal*100).toFixed(1);var pctColor=pf.totalProfit>=0?'#33E818':'#ef4444';var winRate=pf.totalSignals>0?Math.round((pf.wins/pf.totalSignals)*100):0;return'<div style="background:#0c0d0f;border:1px solid rgba(255,255,255,0.08);border-radius:1rem;overflow:hidden;border-top:2px solid #33E818"><div style="padding:1.25rem 1.25rem 1rem"><div style="display:flex;justify-content:space-between;margin-bottom:0.75rem"><span style="font-size:10px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:0.1em">📊 UPDATE POSISI TERAKHIR</span><span style="font-size:10px;font-weight:700;color:'+pctColor+'">'+(pctReturn>=0?'+':'')+pctReturn+'%</span></div><h3 style="font-size:1.125rem;font-weight:900;color:white;margin-bottom:4px">'+info.display+'</h3><div style="font-size:11px;color:#6b7280">🕐 Update: '+fd(pf.lastUpdated)+'</div></div><div style="border-top:1px solid rgba(255,255,255,0.06);margin:0 1.25rem"></div><div style="padding:1.25rem;display:flex;flex-direction:column;gap:0.75rem"><div style="display:flex;justify-content:space-between;align-items:center;background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);border-radius:0.75rem;padding:0.75rem 1rem"><span style="font-size:10px;color:#6b7280;font-weight:700;text-transform:uppercase">💰 Modal Saat Ini</span><span style="font-family:monospace;font-weight:900;color:white;font-size:16px">'+fr(pf.modalSaatIni)+'</span></div><div style="display:flex;flex-direction:column;gap:0.625rem"><div style="display:flex;justify-content:space-between"><span style="color:#9ca3af;font-size:12px">🪙 Modal Awal</span><span style="color:white;font-family:monospace;font-weight:600;font-size:14px">'+fr(pf.modalAwal)+'</span></div><div style="display:flex;justify-content:space-between"><span style="color:#9ca3af;font-size:12px">💱 Total Profit</span><span style="color:'+pctColor+';font-family:monospace;font-weight:700;font-size:14px">'+frs(pf.totalProfit)+'</span></div><div style="display:flex;justify-content:space-between"><span style="color:#9ca3af;font-size:12px">📈 Return</span><span style="color:'+pctColor+';font-family:monospace;font-weight:700;font-size:14px">'+(pctReturn>=0?'+':'')+pctReturn+'%</span></div></div></div><div style="border-top:1px solid rgba(255,255,255,0.06);margin:0 1.25rem"></div><div style="padding:1.25rem;display:flex;flex-direction:column;gap:0.625rem"><span style="font-size:10px;font-weight:700;color:#9ca3af;text-transform:uppercase;letter-spacing:0.1em;display:block;margin-bottom:0.5rem">📋 Statistik Trading</span><div style="display:flex;justify-content:space-between"><span style="color:#9ca3af;font-size:12px">Total Signal</span><span style="color:white;font-family:monospace;font-weight:600;font-size:14px">'+pf.totalSignals+'</span></div><div style="display:flex;justify-content:space-between"><span style="color:#9ca3af;font-size:12px">✅ Win</span><span style="color:#4ade80;font-family:monospace;font-weight:700;font-size:14px">'+pf.wins+'</span></div><div style="display:flex;justify-content:space-between"><span style="color:#9ca3af;font-size:12px">❌ Loss</span><span style="color:#f87171;font-family:monospace;font-weight:700;font-size:14px">'+pf.losses+'</span></div><div style="display:flex;justify-content:space-between"><span style="color:#9ca3af;font-size:12px">🎯 Win Rate</span><span style="color:#33E818;font-family:monospace;font-weight:700;font-size:14px">'+winRate+'%</span></div></div></div>';}
function sigFetch(){
var ts=Date.now();
fetch(API_SIGNALS+'?t='+ts).then(function(r){if(!r.ok)throw new Error('not ok');return r.json()}).then(function(sigR){
return fetch(API_PORTFOLIO+'?t='+ts).then(function(r2){if(!r2.ok)throw new Error('not ok');return r2.json()}).then(function(pfR){
renderData(sigR.data||[],pfR.data||{});
});}).catch(function(e){
// Fallback: gunakan data embedded
console.log('Using embedded data');
renderData(EMBEDDED_SIGNALS,EMBEDDED_PORTFOLIO);
});}
function renderData(signals,portfolio){
var grid=document.getElementById('sigCardGrid');
document.getElementById('sigLastUpdate').textContent=new Date().toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit',second:'2-digit',hour12:false});
if(signals.length>0){document.getElementById('sigHeaderBadge').innerHTML='<i class="fas fa-robot"></i> AI Auto Signal';document.getElementById('sigHeaderTitle').innerHTML='Signal Alert <span style="color:#33E818">Real-Time</span>';document.getElementById('sigHeaderDesc').textContent='Menampilkan 3 sinyal aktif dengan performa profit tertinggi.';document.getElementById('sigStatus').textContent='ONLINE / ACTIVE ('+signals.length+' sinyal)';var sorted=signals.map(function(s){return Object.assign({},s,{_p:(portfolio[s.pair]||{totalProfit:0}).totalProfit})}).sort(function(a,b){return b._p-a._p}).slice(0,3);grid.innerHTML=sorted.map(function(s){return renderSignal(s,portfolio)}).join('');}
else{document.getElementById('sigHeaderBadge').innerHTML='<i class="fas fa-chart-pie"></i> Report Transaksi Terakhir';document.getElementById('sigHeaderTitle').innerHTML='Update Posisi <span style="color:#33E818">Terakhir</span>';document.getElementById('sigHeaderDesc').textContent='Tidak ada sinyal aktif. Berikut 3 pair dengan modal tertinggi.';document.getElementById('sigStatus').textContent='ONLINE (menunggu sinyal baru)';var entries=Object.keys(portfolio).filter(function(k){return portfolio[k].totalSignals>0}).sort(function(a,b){return portfolio[b].modalSaatIni-portfolio[a].modalSaatIni}).slice(0,3);if(entries.length>0){grid.innerHTML=entries.map(function(p){return renderReport(p,portfolio[p])}).join('');}else{grid.innerHTML='<div style="grid-column:span 3;text-align:center;padding:5rem 0"><h3 style="color:white;font-weight:700;font-size:1.125rem;margin-bottom:0.5rem">Belum Ada Data</h3><p style="color:#6b7280;font-size:14px">Bot belum melakukan transaksi.</p></div>';}}}
sigFetch();setInterval(sigFetch,30000);
})();
</script>


<!-- PILAR 3 LANGKAH MUDAH-->
<section class="py-20 relative bg-black border-t border-white/5 overflow-hidden">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl">
        
        <!-- Header -->
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-accent font-bold tracking-widest text-xs sm:text-sm uppercase mb-2 block">
                🌟 LAYANAN GRATIS
            </span>
            <h2 class="text-3xl sm:text-5xl font-black text-white uppercase tracking-tight">
                MULAI TRADING DALAM 3 LANGKAH MUDAH
            </h2>
        </div>

        <!-- Cards -->
        <div class="grid md:grid-cols-3 gap-6" data-aos="fade-up">
            
            <!-- CARD 1 (TETAP) -->
            <div class="bg-white/5 border border-white/10 rounded-3xl p-6 hover:border-accent/30 transition-all duration-300 flex flex-col justify-between h-full">
                
                <div>
                    <div class="w-11 h-11 flex items-center justify-center rounded-full bg-accent text-black font-bold mb-4 gap-1">
                        <i class="fas fa-user-plus text-sm"></i>
                        <span class="text-sm font-black">1</span>
                    </div>

                    <h3 class="text-white font-bold text-lg mb-3">Buat Demo Akun</h3>
                    <p class="text-gray-400 text-xs sm:text-sm leading-relaxed mb-6">
                        Mulai dengan membuat <span class="font-bold text-green-400">Akun Trading Demo</span> di platform Plus500 untuk mempelajari dan mempraktikkan penggunaan sinyal trading secara langsung.
                    </p>
                </div>

                <a href="https://www.plus500.com/en/multiplatformdownload?clt=Web&id=139621&tags=DEMOAKUN&pl=2" 
                   class="w-full px-4 py-3 bg-accent text-black font-bold rounded-xl hover:bg-green-500 transition-all text-xs sm:text-sm uppercase tracking-wider text-center">
                    Buat Demo Akun
                </a>

            </div>
            <!-- CARD 2 (SEBELUMNYA CARD 2 - DIPINDAH KE POSISI INI) -->
            <div class="bg-white/5 border border-white/10 rounded-3xl p-6 hover:border-accent/30 transition-all duration-300 flex flex-col justify-between h-full">
                
                <div>
                    <div class="w-11 h-11 flex items-center justify-center rounded-full bg-accent text-black font-bold mb-4 gap-1">
                        <i class="fas fa-broadcast-tower text-sm"></i>
                        <span class="text-sm font-black">2</span>
                    </div>

                    <h3 class="text-white font-bold text-lg mb-3">Ikuti Plus500 | Almai Signal</h3>
                    <p class="text-gray-400 text-xs sm:text-sm leading-relaxed mb-6">
                        Terima sinyal trading real-time langsung dari WhatsApp Chanel <span class="font-bold text-green-400">Plus500 | Almai Trading Signal</span>, dilengkap dengan grafik teknikal dan akses transaksi instan melalui platform Plus500.
                    </p>

                </div>

                <a href="https://whatsapp.com/channel/0029Vb8F4Uc9WtC4cJHudt2k" 
                   target="_blank" rel="noopener noreferrer"
                   class="w-full px-4 py-3 bg-white/5 border border-white/10 text-white font-bold rounded-xl hover:bg-white/10 transition-all text-xs sm:text-sm uppercase tracking-wider text-center">
                    Gabung Chanel
                </a>

            </div>

            <!-- CARD 3 (SEBELUMNYA CARD 3 - DIPINDAH KE POSISI INI) -->
            <div class="bg-white/5 border border-white/10 rounded-3xl p-6 hover:border-accent/30 transition-all duration-300 flex flex-col justify-between h-full">
                
                <div>
                    <div class="w-11 h-11 flex items-center justify-center rounded-full bg-accent text-black font-bold mb-4 gap-1">
                        <i class="fas fa-graduation-cap text-sm"></i>
                        <span class="text-sm font-black">3</span>
                    </div>

                    <h3 class="text-white font-bold text-lg mb-3">Gabung Kelas Advokasi</h3>
                    <p class="text-gray-400 text-xs sm:text-sm leading-relaxed mb-6">
                        Akses materi dan layanan melalui <span class="font-bold text-green-400">Dashboard Almai</span> (pilih: menu advokasi) dan ikuti pelatihan untuk memahami dasar trading, regulasi, risiko, serta cara menggunakan sinyal secara tepat.
                    </p>
                </div>

                <a href="#" target="_blank" rel="noopener noreferrer" 
                   class="w-full px-4 py-3 bg-accent text-black font-bold rounded-xl hover:bg-green-500 transition-all text-xs sm:text-sm uppercase tracking-wider text-center">
                    Gabung Webinar
                </a>

            </div>

        </div>

    </div>
</section>


<style>
@keyframes borderPulse {
    0% {
        opacity: 0.4;
        box-shadow: 0 0 0px #22c55e;
    }
    50% {
        opacity: 1;
        box-shadow: 0 0 12px #22c55e;
    }
    100% {
        opacity: 0.4;
        box-shadow: 0 0 0px #22c55e;
    }
}

.border-pulse {
    position: absolute;
    inset: 0;
    border-radius: 24px;
    border: 1.5px solid #22c55e;
    animation: borderPulse 2s infinite ease-in-out;
    pointer-events: none;
}
</style>

<section class="py-10 md:py-16 bg-black">
    <div class="container mx-auto px-4 max-w-6xl">

        <!-- Heading -->
        <div class="text-center mb-8 md:mb-12">
            <h2 class="font-bold text-white leading-tight"
                style="font-size:clamp(1.75rem,4vw,3rem)">
                Event & Webinar
            </h2>

            <p class="text-gray-400 mt-3"
                style="font-size:clamp(.875rem,1.5vw,1.1rem)">
                Belajar trading lebih terarah bersama mentor profesional.
            </p>
        </div>

        <!-- WRAPPER -->
        <div class="relative">

            <!-- 🔥 BORDER PULSE -->
            <div class="border-pulse"></div>

            <!-- CARD -->
            <div class="relative bg-zinc-900 rounded-3xl border border-zinc-800 
                        flex flex-col md:flex-row overflow-hidden items-center z-10">

                <!-- IMAGE -->
                <div class="md:w-[45%] lg:w-[42%] flex items-center justify-center p-4">
                    <img
                        src="https://almai.id/uploads/banners/banner_1782388992_1782388992_5e3fdd120b95c66611f9.png"
                        alt="Webinar Trading Basic"
                        class="max-h-[320px] w-auto object-contain">
                </div>

                <!-- CONTENT -->
                <div class="md:w-[55%] lg:w-[58%] p-5 sm:p-6 lg:p-8 flex flex-col justify-center">

                    <span class="inline-flex w-fit bg-green-500/20 text-green-400 rounded-full px-3 py-1 mb-3 text-xs">
                        Webinar
                    </span>

                    <p class="text-green-400 font-semibold mb-1 text-sm">
                        Bersama WPA — GRATIS & Terbatas
                    </p>

                    <h3 class="font-bold text-white leading-tight mb-3 text-xl sm:text-2xl">
                        Cara Mulai Trading Dengan Modal Kecil
                    </h3>

                    <p class="text-gray-400 mb-3 text-sm flex items-center gap-2">
                        📅 <span>Rabu, 08/07/2026 • 19.00 WIB</span>
                    </p>

                    <p class="text-gray-300 mb-5 text-sm max-w-md">
                        👉 Kuasai dasar trading, pahami risiko, dan praktik langsung menggunakan signal dengan percaya diri.
                    </p>
<br>
                    <div class="flex flex-col gap-2 max-w-xs">
                        <a href="https://almai.id/absensi/checkin/ABS-6A3D1A7A53810"
                           target="_blank"
                           class="w-full px-4 py-3 bg-green-500 text-black font-bold rounded-xl 
                                  hover:bg-green-400 transition-all text-xs uppercase text-center">
                            Konfirmasi Kehadiran
                        </a>

                        <p class="text-gray-400 text-center text-xs">
                            Terbatas hanya 100 peserta.
                        </p>
                    </div>

                </div>
            </div>

        </div>

    </div>
</section>


</section>

<!-- PREMIUM VISUAL CHART MODAL -->
<div id="chartModal" class="fixed inset-0 z-50 hidden bg-black/90 backdrop-blur-md flex items-center justify-center p-4">
    <div class="bg-[#0c0d0f] border border-white/10 rounded-[1.5rem] w-full max-w-5xl overflow-hidden shadow-2xl relative" data-aos="zoom-in">
        <!-- Close Button -->
        <button onclick="closeChartModal()" class="absolute top-4 right-4 z-10 w-8 h-8 rounded-full bg-black/50 border border-white/10 flex items-center justify-center text-gray-400 hover:text-white transition-all text-sm">
            <i class="fas fa-times"></i>
        </button>

        <div class="grid md:grid-cols-12">
            <!-- Left: Visual Chart -->
            <div class="md:col-span-8 p-6 bg-black flex flex-col justify-between border-r border-white/5">
                <div>
                    <!-- Header inside chart container -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <span id="modalOrderBadge" class="px-2.5 py-0.5 rounded text-[10px] font-black tracking-widest uppercase"></span>
                            <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Timeframe: <span id="modalTimeframe" class="text-white"></span></span>
                        </div>
                        <span id="modalPairName" class="text-sm font-bold text-accent tracking-widest"></span>
                    </div>
                    <!-- Chart Image wrapper with grid overlay -->
                    <div class="relative rounded-lg overflow-hidden bg-[#050505] border border-white/5 flex items-center justify-center min-h-[300px]">
                        <img id="modalChartImage" src="" alt="AI Chart Visual" class="w-full h-auto object-contain">
                    </div>
                </div>
                
                <!-- Chart Footer Meta -->
                <div class="mt-4 pt-4 border-t border-white/5 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
                    <span class="text-[10px] text-gray-500 uppercase tracking-widest">Live AI-Generated Chart â€” Almai X Plus500</span>
                    <span class="text-[10px] text-accent font-bold uppercase tracking-wider flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-accent animate-ping animate-pulse"></span> AI Confidence: <span id="modalConfidence"></span>
                    </span>
                </div>
            </div>
            
            <!-- Right: Order Ticket & AI Analysis -->
            <div class="md:col-span-4 p-6 flex flex-col justify-between space-y-6">
                <!-- Trade Setup details -->
                <div class="space-y-4">
                    <h3 class="text-white font-black text-sm uppercase tracking-wider border-b border-white/5 pb-2 flex items-center gap-2">
                        <i class="fas fa-receipt text-accent"></i> Order Ticket
                    </h3>
                    
                    <div class="space-y-3 font-mono text-xs">
                        <div class="flex justify-between items-center py-2 border-b border-white/5">
                            <span class="text-gray-400">Pair Name</span>
                            <span id="modalTicketPair" class="text-white font-bold"></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-white/5">
                            <span class="text-gray-400">Order Action</span>
                            <span id="modalTicketOrder" class="font-bold"></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-white/5">
                            <span class="text-gray-400">Entry Rate</span>
                            <span id="modalTicketEntry" class="text-white font-bold"></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-white/5">
                            <span class="text-gray-400">Stop Loss</span>
                            <span id="modalTicketSL" class="text-red-500 font-bold"></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-white/5">
                            <span class="text-gray-400">Take Profit</span>
                            <span id="modalTicketTP1" class="text-green-400 font-bold"></span>
                        </div>
                    </div>
                </div>

                <!-- AI Analysis -->
                <div class="bg-white/5 border border-white/5 rounded-xl p-4 space-y-2">
                    <span class="text-[9px] font-black text-accent uppercase tracking-widest flex items-center gap-1.5">
                        <i class="fas fa-brain"></i> AI Analysis
                    </span>
                    <p id="modalAnalysisText" class="text-gray-300 text-[11px] leading-relaxed"></p>
                </div>

                <!-- Action Button -->
                <div class="space-y-3">
                    <a id="modalTicketExecuteLink" href="#" target="_blank" class="w-full py-3 bg-accent hover:bg-green-500 text-black text-xs font-black rounded-xl transition-all flex items-center justify-center gap-2 shadow-[0_0_20px_rgba(51,232,24,0.2)] uppercase tracking-widest text-center">
                        Place Order on Plus500
                    </a>
                    <a href="https://www.plus500.com/id/multiplatformdownload?clt=Web&id=139621&pl=2" target="_blank" class="w-full py-2.5 bg-white/5 border border-white/10 hover:bg-white/10 text-white text-xs font-bold rounded-xl transition-all flex items-center justify-center gap-2 text-center">
                        Buka Akun Broker
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function showChartModal(pair, chartUrl, order, timeframe, entry, sl, tp1, tp2, tp3, confidence) {
    const modal = document.getElementById('chartModal');
    const img = document.getElementById('modalChartImage');
    
    // Set variables
    img.src = chartUrl;
    document.getElementById('modalPairName').innerText = pair;
    document.getElementById('modalTimeframe').innerText = timeframe;
    
    // Badges & text formats
    const isBuy = order.toUpperCase() === 'BUY';
    const badge = document.getElementById('modalOrderBadge');
    badge.innerText = order;
    badge.className = isBuy 
        ? 'bg-green-500/10 text-green-400 border border-green-500/20 px-2.5 py-0.5 rounded text-[10px] font-black tracking-widest uppercase' 
        : 'bg-red-500/10 text-red-400 border border-red-500/20 px-2.5 py-0.5 rounded text-[10px] font-black tracking-widest uppercase';
    
    document.getElementById('modalConfidence').innerText = confidence + '%';
    
    // Ticket values
    document.getElementById('modalTicketPair').innerText = pair;
    
    const ticketOrder = document.getElementById('modalTicketOrder');
    ticketOrder.innerText = order;
    ticketOrder.className = isBuy ? 'text-green-400 font-bold' : 'text-red-400 font-bold';
    
    document.getElementById('modalTicketEntry').innerText = Number(entry).toFixed(5);
    document.getElementById('modalTicketSL').innerText = Number(sl).toFixed(5);
    document.getElementById('modalTicketTP1').innerText = Number(tp1).toFixed(5);
    
    // Custom analysis descriptions depending on the pair
    let analysisText = "";
    if (pair === 'XNGUSD') {
        analysisText = "Sinyal naik terdeteksi di M15 (80%), dikonfirmasi searah oleh timeframe M30 dan H1 dengan pola Bullish Engulfing.";
    } else if (pair === 'XAUUSD') {
        analysisText = "Momentum bullish kuat pada timeframe H4 pasca penembusan resistensi dinamis.";
    } else {
        analysisText = "Penolakan harga pada area supply diiringi pola bearish divergence di timeframe H1.";
    }
    document.getElementById('modalAnalysisText').innerText = analysisText;
    
    // Set execution link
    let tradeLink = "https://app.plus500.com/buy/ng?hl=id&id=139621&product=CFD&IsRealMode=True";
    if (pair === 'XAUUSD') {
        tradeLink = "https://app.plus500.com/buy/gold?hl=id&id=139621&product=CFD&IsRealMode=True";
    } else if (pair === 'EURUSD') {
        tradeLink = "https://app.plus500.com/buy/eurusd?hl=id&id=139621&product=CFD&IsRealMode=True";
    }
    document.getElementById('modalTicketExecuteLink').href = tradeLink;
    
    // Show modal
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeChartModal() {
    const modal = document.getElementById('chartModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

window.addEventListener('click', function(e) {
    const modal = document.getElementById('chartModal');
    if (e.target === modal) {
        closeChartModal();
    }
});

document.addEventListener('DOMContentLoaded', () => {
    // Fetch or default recent users
    let recentUsers = [];
    <?php if (!empty($recentUsers)): ?>
        recentUsers = <?= json_encode(array_map(function ($u) {
            return [
                'name' => $u['name'],
                'isNew' => false
            ];
        }, $recentUsers)) ?>;
    <?php endif; ?>

    // Supplement with high-quality dummy users if empty or too few
    if (!recentUsers || recentUsers.length < 5) {
        recentUsers = [
            { name: "Andi Wijaya", isNew: false },
            { name: "Siti Nurhaliza", isNew: false },
            { name: "Budi Santoso", isNew: false },
            { name: "Dewi Lestari", isNew: false },
            { name: "Reza Pahlevi", isNew: false },
            { name: "Hendra Wijaya", isNew: false },
            { name: "Rina Kartika", isNew: false }
        ];
    }

    // Current total tracked in JS
    let currentTotalInfo = <?= $totalUsers ?>;

    const toastEl = document.getElementById('user-toast');
    const nameEl = document.getElementById('toast-name');
    const statusEl = document.querySelector('#user-toast .opacity-70'); // Span for "baru/sudah bergabung"

    // Dynamic Testimonials Data
    const testimonials = [
        { name: "Andi Wijaya", text: "Bimbingan Almai sangat membantu saya<br>memahami market dengan lebih objektif." },
        { name: "Siti Nurhaliza", text: "WPA di sini sangat sabar dan detail<br>dalam menjelaskan strategi trading." },
        { name: "Budi Santoso", text: "Fitur trailing stop-nya bener-bener<br>kasih ketenangan saat trading." },
        { name: "Dewi Lestari", text: "Platform trading dengan legalitas jelas<br>dan mentornya sangat kompeten." },
        { name: "Reza Pahlevi", text: "Belajar jadi lebih fun dan nggak pusing<br>berkat pendampingan WPA Almai." }
    ];

    const testimonyNameEl = document.getElementById('testimony-name');
    const testimonyTextEl = document.getElementById('testimony-text');
    
    let index = 0;
    let testimonyIndex = 0;
    let loopTimeout;

    if (recentUsers.length > 0) {
        // Update Badge Initial State
        updateBadgeAvatars(0);

        // Initial show toast
        loopTimeout = setTimeout(() => showNextUser(), 1000);
        
        // Initial testimony rotate
        setInterval(rotateTestimonials, 3000);

        // POLL for new users every 5 seconds
        setInterval(checkForUpdates, 5000);

        function rotateTestimonials() {
            if (testimonyNameEl && testimonyTextEl) {
                testimonyNameEl.classList.add('opacity-0', '-translate-y-1');
                testimonyTextEl.classList.add('opacity-0', 'translate-y-1');

                setTimeout(() => {
                    testimonyIndex = (testimonyIndex + 1) % testimonials.length;
                    const nextTesti = testimonials[testimonyIndex];
                    
                    testimonyNameEl.textContent = nextTesti.name.toUpperCase();
                    testimonyTextEl.innerHTML = nextTesti.text;
                    
                    testimonyNameEl.classList.remove('opacity-0', '-translate-y-1');
                    testimonyTextEl.classList.remove('opacity-0', 'translate-y-1');
                }, 500);
            }
        }

        function checkForUpdates() {
            fetch('<?= base_url('home/stats') ?>')
                .then(res => res.json())
                .then(data => {
                    if (data.total > currentTotalInfo) {
                        // NEW USER DETECTED!
                        currentTotalInfo = data.total;

                        // Update Total Count Text
                        const totalCountEl = document.querySelector('.text-2xl.text-accent');
                        if (totalCountEl) totalCountEl.innerText = new Intl.NumberFormat().format(data.total) + '+';

                        // Add new user to top of list
                        const newUser = {
                            name: data.latest.name,
                            avatar: data.latest.avatar,
                            isNew: true // Mark as NEW for specific text
                        };

                        // Prepend
                        recentUsers.unshift(newUser);

                        // Reset index to 0 to show this new user immediately next
                        index = 0;
                    }
                })
                .catch(err => console.error(err));
        }

        // Color gradients for initials
        const gradients = [
            'linear-gradient(135deg, #33e818 0%, #1a7a0c 100%)',
            'linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%)',
            'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
            'linear-gradient(135deg, #ef4444 0%, #b91c1c 100%)',
            'linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%)',
            'linear-gradient(135deg, #ec4899 0%, #be185d 100%)'
        ];

        function getGradientByName(name) {
            const charCode = name.charCodeAt(0);
            return gradients[charCode % gradients.length];
        }

        function updateBadgeAvatars(currentIndex) {
            for (let i = 0; i < 3; i++) {
                const userIndex = (currentIndex + i) % recentUsers.length;
                const user = recentUsers[userIndex];
                const initialEl = document.getElementById(`badge-initial-${i}`);

                if (initialEl && user) {
                    // Add fade transition
                    initialEl.classList.add('opacity-50', 'scale-90');

                    setTimeout(() => {
                        initialEl.textContent = user.name.charAt(0).toUpperCase();
                        initialEl.style.background = getGradientByName(user.name);
                        initialEl.classList.remove('opacity-50', 'scale-90');
                    }, 200);
                }
            }
        }

        function showNextUser() {
            const user = recentUsers[index];

            // Fade out toast
            if (toastEl && nameEl && statusEl) {
                toastEl.classList.remove('opacity-100', 'translate-y-0');
                toastEl.classList.add('opacity-0', 'translate-y-2');

                setTimeout(() => {
                    // Update Data
                    nameEl.textContent = user.name;

                    // Update Status Text based on isNew flag
                    if (user.isNew) {
                        statusEl.textContent = 'baru bergabung';
                        statusEl.classList.add('text-green-400', 'font-bold', 'opacity-100');
                        statusEl.classList.remove('opacity-70');

                        // Remove flag after showing once so next time it says "sudah bergabung"
                        user.isNew = false;
                    } else {
                        statusEl.textContent = 'sudah bergabung';
                        statusEl.classList.remove('text-green-400', 'font-bold', 'opacity-100');
                        statusEl.classList.add('opacity-70');
                    }

                    // Update Badges with animation
                    updateBadgeAvatars(index);

                    index = (index + 1) % recentUsers.length;

                    // Fade in toast
                    toastEl.classList.remove('opacity-0', 'translate-y-2');
                    toastEl.classList.add('opacity-100', 'translate-y-0');
                }, 500); // Wait for fade out
            }

            // Loop every 3 seconds for faster rotation
            loopTimeout = setTimeout(showNextUser, 3000);
        }
    }
});
</script>
<?= $this->endSection() ?>

