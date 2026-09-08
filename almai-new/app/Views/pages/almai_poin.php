<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="pt-32 pb-20 bg-[#050505] min-h-screen relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0 bg-grid z-0 pointer-events-none opacity-20"></div>
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-accent/5 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-accent/5 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="container mx-auto px-6 relative z-10">

        <!-- Hero Section -->
        <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-20 mb-24">
            <div class="lg:w-1/2 flex justify-center" data-aos="fade-right">
                <div class="relative w-full max-w-md aspect-square">
                    <div class="absolute inset-0 bg-gradient-to-tr from-accent/20 to-emerald-500/20 rounded-full blur-3xl animate-pulse"></div>
                    <img src="<?= base_url('images/almai.gif') ?>" alt="Almai Poin Coin" class="relative z-10 w-full h-full object-contain animate-float drop-shadow-2xl">
                </div>
            </div>

            <div class="lg:w-1/2" data-aos="fade-left">
                <span class="text-accent font-bold tracking-widest uppercase text-sm mb-4 block">Loyalty & Reward System</span>
                <h1 class="text-4xl md:text-6xl font-black text-white mb-6 leading-tight">
                    ALMAI <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-emerald-500">POIN</span>
                </h1>
                <p class="text-gray-300 text-lg md:text-xl leading-relaxed mb-8 text-justify">
                    Sistem penilaian dan penghargaan yang dirancang untuk meningkatkan keterlibatan dan kinerja pengguna Portal Penasihat Berjangka Almai.id. Almai Poin memberikan penghargaan atas partisipasi dan pencapaian Anda, mendorong motivasi belajar, serta menciptakan transparansi dan kompetisi sehat dalam komunitas.
                </p>

                <?php if (!session()->get('isLoggedIn')): ?>
                    <a href="<?= base_url('register') ?>" class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-accent to-emerald-600 text-black font-bold rounded-full hover:shadow-[0_0_20px_rgba(51,232,24,0.4)] transition-all transform hover:-translate-y-1">
                        <span>Mulai Kumpulkan Poin</span>
                        <i class="fas fa-coins"></i>
                    </a>
                <?php else: ?>
                    <a href="<?= base_url('user/dashboard') ?>" class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-accent to-emerald-600 text-black font-bold rounded-full hover:shadow-[0_0_20px_rgba(51,232,24,0.4)] transition-all transform hover:-translate-y-1">
                        <span>Cek Poin Saya</span>
                        <i class="fas fa-wallet"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- ALMAI EVOLUTION - Bar Chart with Trend Line -->
        <div class="mb-48 px-4 md:px-0 relative overflow-hidden">
            <!-- Section Header -->
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-accent font-bold tracking-widest uppercase text-xs mb-2 block">Career Trajectory</span>
                <h2 class="text-4xl md:text-6xl font-black text-white mb-4">
                    ALMAI <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent via-emerald-400 to-lime-400">EVOLUTION</span>
                </h2>
                <p class="text-gray-400 max-w-2xl mx-auto text-sm">
                    Grafik pertumbuhan karir dari pemula hingga branch manager
                </p>
            </div>

            <!-- Chart Container -->
            <div class="max-w-6xl mx-auto relative">
                <div class="relative h-[500px] md:h-[600px] bg-gradient-to-br from-[#0a1a0f] via-[#0d1b12] to-[#000814] rounded-3xl border border-accent/20 shadow-[0_0_80px_rgba(51,232,24,0.15)] overflow-hidden p-8 md:p-12">
                    
                    <!-- Background Grid -->
                    <div class="absolute inset-0 pointer-events-none opacity-20">
                        <!-- Horizontal lines -->
                        <?php for ($i = 0; $i <= 5; $i++): ?>
                            <div class="absolute w-full border-t border-accent/20" style="bottom: <?= $i * 20 ?>%;"></div>
                        <?php endfor; ?>
                        <!-- Vertical lines -->
                        <?php for ($i = 0; $i <= 5; $i++): ?>
                            <div class="absolute h-full border-l border-accent/20" style="left: <?= $i * 20 ?>%;"></div>
                        <?php endfor; ?>
                    </div>

                    <!-- Bars and Trend Line Container -->
                    <div class="relative h-full flex items-end justify-start gap-4 md:gap-8 px-4 md:px-8 z-10">
                        <?php
                        $levels = [
                            ['name' => 'USER', 'height' => '25%', 'color' => '#10b981', 'icon' => 'fa-user'],
                            ['name' => 'PRO', 'height' => '40%', 'color' => '#22c55e', 'icon' => 'fa-crown'],
                            ['name' => 'CWPA', 'height' => '60%', 'color' => '#33e818', 'icon' => 'fa-user-graduate'],
                            ['name' => 'WPA', 'height' => '80%', 'color' => '#4ade80', 'icon' => 'fa-user-tie'],
                            ['name' => 'BRANCH', 'height' => '100%', 'color' => '#84cc16', 'icon' => 'fa-building'],
                        ];
                        
                        foreach ($levels as $index => $level):
                            $delay = $index * 0.2;
                        ?>
                            <div class="flex-1 flex flex-col items-center justify-end group">
                                <!-- Bar -->
                                <div class="relative w-full max-w-[140px] chart-bar rounded-t-lg overflow-hidden cursor-pointer transition-all duration-300 hover:scale-105"
                                     style="height: 0; 
                                            background: linear-gradient(to top, <?= $level['color'] ?>, <?= $level['color'] ?>cc);
                                            box-shadow: 0 0 30px <?= $level['color'] ?>60;
                                            --target-height: <?= $level['height'] ?>;
                                            --bar-delay: <?= $delay ?>s;">
                                    
                                    <!-- Glow effect on hover -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-white/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    
                                    <!-- Icon at top of bar -->
                                    <div class="absolute -top-12 left-1/2 -translate-x-1/2 w-10 h-10 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300"
                                         style="background: <?= $level['color'] ?>40; box-shadow: 0 0 20px <?= $level['color'] ?>;">
                                        <i class="fas <?= $level['icon'] ?> text-white text-lg"></i>
                                    </div>
                                </div>
                                
                                <!-- Label -->
                                <div class="mt-4 text-center">
                                    <div class="text-sm md:text-base font-black tracking-wider transition-all duration-300 group-hover:scale-110"
                                         style="color: <?= $level['color'] ?>; text-shadow: 0 0 10px <?= $level['color'] ?>80;">
                                        <?= $level['name'] ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Trend Line SVG -->
                    <svg class="absolute inset-0 w-full h-full pointer-events-none z-20" viewBox="0 0 1000 600" preserveAspectRatio="none">
                        <defs>
                            <linearGradient id="lineGradient" x1="0%" y1="100%" x2="100%" y2="0%">
                                <stop offset="0%" style="stop-color:#10b981;stop-opacity:1" />
                                <stop offset="25%" style="stop-color:#22c55e;stop-opacity:1" />
                                <stop offset="50%" style="stop-color:#33e818;stop-opacity:1" />
                                <stop offset="75%" style="stop-color:#4ade80;stop-opacity:1" />
                                <stop offset="100%" style="stop-color:#84cc16;stop-opacity:1" />
                            </linearGradient>
                            <filter id="lineGlow">
                                <feGaussianBlur stdDeviation="5" result="coloredBlur"/>
                                <feMerge>
                                    <feMergeNode in="coloredBlur"/>
                                    <feMergeNode in="coloredBlur"/>
                                    <feMergeNode in="SourceGraphic"/>
                                </feMerge>
                            </filter>
                        </defs>
                        
                        <!-- Trend line path -->
                        <path class="trend-line" 
                              d="M 100 480 L 250 400 L 400 320 L 550 240 L 700 160 L 900 80" 
                              stroke="url(#lineGradient)" 
                              stroke-width="4" 
                              fill="none" 
                              filter="url(#lineGlow)" />
                        
                        <!-- Glow points on line -->
                        <circle cx="100" cy="480" r="8" fill="#10b981" class="glow-point" style="animation-delay: 0.5s;">
                            <animate attributeName="r" values="6;12;6" dur="2s" repeatCount="indefinite" />
                            <animate attributeName="opacity" values="1;0.5;1" dur="2s" repeatCount="indefinite" />
                        </circle>
                        <circle cx="250" cy="400" r="8" fill="#22c55e" class="glow-point" style="animation-delay: 0.7s;">
                            <animate attributeName="r" values="6;12;6" dur="2s" repeatCount="indefinite" />
                            <animate attributeName="opacity" values="1;0.5;1" dur="2s" repeatCount="indefinite" />
                        </circle>
                        <circle cx="400" cy="320" r="8" fill="#33e818" class="glow-point" style="animation-delay: 0.9s;">
                            <animate attributeName="r" values="6;12;6" dur="2s" repeatCount="indefinite" />
                            <animate attributeName="opacity" values="1;0.5;1" dur="2s" repeatCount="indefinite" />
                        </circle>
                        <circle cx="550" cy="240" r="8" fill="#4ade80" class="glow-point" style="animation-delay: 1.1s;">
                            <animate attributeName="r" values="6;12;6" dur="2s" repeatCount="indefinite" />
                            <animate attributeName="opacity" values="1;0.5;1" dur="2s" repeatCount="indefinite" />
                        </circle>
                        <circle cx="900" cy="80" r="10" fill="#84cc16" class="glow-point" style="animation-delay: 1.3s;">
                            <animate attributeName="r" values="8;14;8" dur="1.5s" repeatCount="indefinite" />
                            <animate attributeName="opacity" values="1;0.5;1" dur="1.5s" repeatCount="indefinite" />
                        </circle>
                    </svg>


                </div>
            </div>

            <!-- Welcome Audio -->
            <audio id="welcomeAudio" preload="auto">
                <source src="<?= base_url('audio/welcome.mp3') ?>" type="audio/mpeg">
            </audio>

            <style>
                @keyframes growBar {
                    from {
                        height: 0;
                        opacity: 0;
                    }
                    to {
                        height: var(--target-height);
                        opacity: 1;
                    }
                }

                .chart-bar {
                    animation: growBar 1.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
                    animation-delay: var(--bar-delay);
                }

                .trend-line {
                    stroke-dasharray: 2000;
                    stroke-dashoffset: 2000;
                    animation: drawLine 3s ease-out forwards;
                    animation-delay: 0.5s;
                }

                @keyframes drawLine {
                    to {
                        stroke-dashoffset: 0;
                    }
                }

                .glow-point {
                    opacity: 0;
                    animation: fadeInPoint 0.5s ease-out forwards;
                }

                @keyframes fadeInPoint {
                    to {
                        opacity: 1;
                    }
                }

                .stat-card {
                    opacity: 0;
                    transform: translateY(-10px);
                    animation: slideDown 0.6s ease-out forwards;
                    animation-delay: 0.3s;
                }

                @keyframes slideDown {
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
            </style>

            <script>
                // Auto-play welcome audio when section is visible
                document.addEventListener('DOMContentLoaded', function() {
                    const audio = document.getElementById('welcomeAudio');
                    
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                audio.play().catch(e => {
                                    console.log('Audio autoplay prevented:', e);
                                    document.addEventListener('click', function playOnClick() {
                                        audio.play();
                                        document.removeEventListener('click', playOnClick);
                                    }, { once: true });
                                });
                                observer.unobserve(entry.target);
                            }
                        });
                    }, { threshold: 0.3 });

                    const evolutionSection = document.querySelector('.mb-48');
                    if (evolutionSection) {
                        observer.observe(evolutionSection);
                    }
                });
            </script>
        </div>
    </div>



    <!-- Game Promo Section -->
    <!-- Mini Game Section - DISABLED -->
    <?php /* 
    <div class="mb-24 relative rounded-3xl overflow-hidden border border-accent/30 group" data-aos="fade-up">
        <div class="absolute inset-0 bg-[#111]"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-accent/10 to-transparent opacity-50"></div>
        <div class="relative z-10 p-8 md:p-12 flex flex-col md:flex-row items-center justify-between gap-8">
            <div class="text-center md:text-left">
                <span class="text-accent font-bold tracking-widest uppercase text-xs mb-2 block">Mini Game</span>
                <h2 class="text-3xl md:text-4xl font-black text-white mb-4">ALMA'S <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-emerald-500">CRYPTO RUSH</span></h2>
                <p class="text-gray-400 max-w-xl">Mainkan game seru ini! Bantu Alma mengumpulkan koin Forex & Crypto sambil menghindari bom. Seberapa tinggi skor yang bisa kamu dapatkan?</p>
            </div>
            <button onclick="openGame()" class="group/btn relative px-8 py-4 bg-accent text-black font-bold rounded-xl overflow-hidden transition-all hover:scale-105 hover:shadow-[0_0_30px_rgba(51,232,24,0.4)]">
                <div class="absolute inset-0 bg-white/20 translate-y-full group-hover/btn:translate-y-0 transition-transform duration-300"></div>
                <div class="relative flex items-center gap-3">
                    <i class="fas fa-gamepad text-xl"></i>
                    <span class="text-lg">Main Sekarang</span>
                </div>
            </button>
        </div>
        <!-- Decorative Elements -->
        <i class="fab fa-bitcoin absolute -bottom-8 -right-8 text-9xl text-white/5 rotate-12 group-hover:rotate-0 transition-transform duration-700"></i>
        <i class="fas fa-gamepad absolute top-8 left-1/2 text-9xl text-white/5 -translate-x-1/2 blur-sm"></i>
    </div>
    */ ?>

    <!-- Functions Grid -->
    <div class="mb-24">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-3xl font-bold mb-4">Fungsi Utama <span class="text-accent">Almai Poin</span></h2>
            <div class="w-24 h-1 bg-gradient-to-r from-accent to-emerald-500 mx-auto rounded-full"></div>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Card 1 -->
            <div class="bg-[#111] border border-white/10 p-6 rounded-2xl hover:border-accent/50 transition-colors group" data-aos="fade-up" data-aos-delay="0">
                <div class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center mb-4 group-hover:bg-accent group-hover:text-black transition-colors">
                    <i class="fas fa-trophy text-accent text-xl group-hover:text-black"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Penghargaan Partisipasi</h3>
                <p class="text-sm text-gray-400">Reward untuk pengguna aktif dalam pelatihan, seminar, dan diskusi komunitas.</p>
            </div>

            <!-- Card 2 -->
            <div class="bg-[#111] border border-white/10 p-6 rounded-2xl hover:border-accent/50 transition-colors group" data-aos="fade-up" data-aos-delay="100">
                <div class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center mb-4 group-hover:bg-accent group-hover:text-black transition-colors">
                    <i class="fas fa-graduation-cap text-accent text-xl group-hover:text-black"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Motivasi Belajar</h3>
                <p class="text-sm text-gray-400">Mendorong pengguna untuk terus belajar dan mengasah keterampilan trading.</p>
            </div>

            <!-- Card 3 -->
            <div class="bg-[#111] border border-white/10 p-6 rounded-2xl hover:border-purple-500/50 transition-colors group" data-aos="fade-up" data-aos-delay="200">
                <div class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center mb-4 group-hover:bg-purple-500 group-hover:text-black transition-colors">
                    <i class="fas fa-medal text-purple-500 text-xl group-hover:text-black"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Pengakuan Prestasi</h3>
                <p class="text-sm text-gray-400">Bukti pencapaian nyata atas usaha dan kemajuan dalam trading.</p>
            </div>

            <!-- Card 4 -->
            <div class="bg-[#111] border border-white/10 p-6 rounded-2xl hover:border-blue-500/50 transition-colors group" data-aos="fade-up" data-aos-delay="300">
                <div class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-500 group-hover:text-black transition-colors">
                    <i class="fas fa-gift text-blue-500 text-xl group-hover:text-black"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Insentif Menguntungkan</h3>
                <p class="text-sm text-gray-400">Tukarkan poin dengan hadiah, sertifikasi, atau akses sumber daya eksklusif.</p>
            </div>

            <!-- Card 5 -->
            <div class="bg-[#111] border border-white/10 p-6 rounded-2xl hover:border-green-500/50 transition-colors group" data-aos="fade-up" data-aos-delay="400">
                <div class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center mb-4 group-hover:bg-green-500 group-hover:text-black transition-colors">
                    <i class="fas fa-search-dollar text-green-500 text-xl group-hover:text-black"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Transparansi</h3>
                <p class="text-sm text-gray-400">Sistem terukur dimana semua orang dapat melihat kemajuan kontribusi mereka.</p>
            </div>

            <!-- Card 6 -->
            <div class="bg-[#111] border border-white/10 p-6 rounded-2xl hover:border-red-500/50 transition-colors group" data-aos="fade-up" data-aos-delay="500">
                <div class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center mb-4 group-hover:bg-red-500 group-hover:text-black transition-colors">
                    <i class="fas fa-chart-line text-red-500 text-xl group-hover:text-black"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Kompetisi Sehat</h3>
                <p class="text-sm text-gray-400">Mengukur kinerja trading dan memacu motivasi antar pengguna.</p>
            </div>

            <!-- Card 7 -->
            <div class="bg-[#111] border border-white/10 p-6 rounded-2xl hover:border-orange-500/50 transition-colors group" data-aos="fade-up" data-aos-delay="600">
                <div class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center mb-4 group-hover:bg-orange-500 group-hover:text-black transition-colors">
                    <i class="fas fa-compass text-orange-500 text-xl group-hover:text-black"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Dasar Pengembangan</h3>
                <p class="text-sm text-gray-400">Membantu mengetahui kekuatan dan kelemahan untuk pengembangan diri.</p>
            </div>

            <!-- Card 8 -->
            <div class="bg-[#111] border border-white/10 p-6 rounded-2xl hover:border-teal-500/50 transition-colors group" data-aos="fade-up" data-aos-delay="700">
                <div class="w-12 h-12 bg-white/5 rounded-xl flex items-center justify-center mb-4 group-hover:bg-teal-500 group-hover:text-black transition-colors">
                    <i class="fas fa-users text-teal-500 text-xl group-hover:text-black"></i>
                </div>
                <h3 class="text-lg font-bold text-white mb-2">Komunitas & Kolaborasi</h3>
                <p class="text-sm text-gray-400">Memperkuat rasa kebersamaan dan berbagai pengalaman trading.</p>
            </div>
        </div>
    </div>

    <!-- Referral Section -->
    <div class="bg-gradient-to-br from-[#111] to-black border border-white/10 rounded-3xl p-8 md:p-12 relative overflow-hidden" data-aos="fade-up">
        <div class="absolute top-0 right-0 w-96 h-96 bg-accent/5 rounded-full blur-3xl -mr-32 -mt-32 pointer-events-none"></div>

        <div class="text-center mb-12 relative z-10">
            <span class="bg-white/10 text-white px-4 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-2 inline-block">Program Referral</span>
            <h2 class="text-3xl font-bold mb-4">Dapatkan Poin Tambahan</h2>
            <p class="text-gray-400 max-w-2xl mx-auto">
                Almai memberikan Reward berupa Almai Poin untuk Klien yang merekomendasikan layanan kami.
                Program ini khusus untuk USER & USER PRO yang telah berpengalaman.
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-8 relative z-10">
            <!-- User Tier -->
            <div class="bg-card-bg border border-white/10 rounded-2xl p-8 hover:border-accent/30 transition-all duration-300">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center font-bold text-xl text-white">U</div>
                    <div>
                        <h3 class="text-xl font-bold text-white">USER</h3>
                        <p class="text-xs text-accent uppercase tracking-wider">Level Basic</p>
                    </div>
                </div>
                <ul class="space-y-4 mb-8">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check-circle text-accent mt-1"></i>
                        <span class="text-gray-300 text-sm">Telah mengikuti Pendampingan min. <strong class="text-white">5 Bulan</strong></span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-share-alt text-accent mt-1"></i>
                        <span class="text-gray-300 text-sm">Dapat membagikan <strong>Link Referral Standar</strong></span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-coins text-accent mt-1"></i>
                        <span class="text-gray-300 text-sm">Mendapatkan Poin Reward setiap referensi sukses</span>
                    </li>
                </ul>
            </div>

            <!-- User Pro Tier -->
            <div class="bg-gradient-to-b from-white/5 to-transparent border border-accent/20 rounded-2xl p-8 hover:border-accent/50 transition-all duration-300 relative">
                <div class="absolute top-4 right-4 text-accent animate-pulse">
                    <i class="fas fa-crown text-2xl"></i>
                </div>
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-accent/20 rounded-full flex items-center justify-center font-bold text-xl text-accent">P</div>
                    <div>
                        <h3 class="text-xl font-bold text-white">USER PRO</h3>
                        <p class="text-xs text-accent uppercase tracking-wider">Level Expert</p>
                    </div>
                </div>
                <ul class="space-y-4 mb-8">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check-circle text-accent mt-1"></i>
                        <span class="text-gray-300 text-sm">Telah mengikuti Pendampingan min. <strong class="text-white">12 Bulan</strong></span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-pen text-accent mt-1"></i>
                        <span class="text-gray-300 text-sm">Dapat <strong>Custom Link Referral</strong> (cth: almai.id/refferal/namaanda)</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-coins text-accent mt-1"></i>
                        <span class="text-gray-300 text-sm">Mendapatkan <strong>Poin Reward Lebih Besar</strong> & Prioritas</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-12 text-center border-t border-white/10 pt-8">
            <p class="text-gray-400 mb-6">Poin yang terkumpul dapat ditukarkan dengan berbagai layanan premium di Portal Almai.id</p>
            <div class="flex flex-wrap justify-center gap-4">
                <span class="px-4 py-2 bg-white/5 rounded-full border border-white/10 text-sm text-gray-300"><i class="fas fa-robot text-accent mr-2"></i>Expert Advisor</span>
                <span class="px-4 py-2 bg-white/5 rounded-full border border-white/10 text-sm text-gray-300"><i class="fas fa-chalkboard-teacher text-accent mr-2"></i>Kelas Advanced</span>
                <span class="px-4 py-2 bg-white/5 rounded-full border border-white/10 text-sm text-gray-300"><i class="fas fa-certificate text-accent mr-2"></i>Sertifikat</span>
                <span class="px-4 py-2 bg-white/5 rounded-full border border-white/10 text-sm text-gray-300"><i class="fas fa-gift text-accent mr-2"></i>Merchandise</span>
            </div>
        </div>
    </div>


</div>
</div>

<!-- Game Overlay Container -->
<div id="gameContainer" class="fixed inset-0 z-[100] bg-[#050505] hidden flex-col overflow-hidden">
    <!-- Video Background -->
    <div class="absolute inset-0 z-0 opacity-40">
        <video autoplay loop muted playsinline class="w-full h-full object-cover">
            <source src="<?= base_url('images/home.mp4') ?>" type="video/mp4">
        </video>
        <!-- Overlay Gradient to darken video for gameplay visibility -->
        <div class="absolute inset-0 bg-gradient-to-t from-[#050505] via-[#050505]/80 to-[#050505]/60"></div>
    </div>

    <!-- Game Controls -->
    <div class="absolute top-4 right-4 z-[110] flex gap-2">
        <button onclick="toggleMute()" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center text-white hover:bg-white/20 transition-colors backdrop-blur-md">
            <i id="muteIcon" class="fas fa-volume-up"></i>
        </button>
        <button onclick="closeGame()" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center text-white hover:bg-red-500/20 hover:text-red-500 transition-colors backdrop-blur-md">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <canvas id="gameCanvas" class="relative z-10 block w-full h-full"></canvas>

    <div class="absolute inset-0 pointer-events-none flex flex-col justify-between p-6 z-[105]">
        <div class="text-accent font-bold text-2xl drop-shadow-[0_2px_0_rgba(0,0,0,1)]">
            Score: <span id="scoreVal">0</span>
        </div>
    </div>

    <!-- Start Screen -->
    <div id="startScreen" class="absolute inset-0 bg-black/80 backdrop-blur-md flex flex-col items-center justify-center z-[110]">
        <h1 class="text-5xl md:text-7xl font-black text-white mb-2 text-center drop-shadow-[0_4px_0_#28c210]">
            ALMA'S<br><span class="text-accent">RUSH</span>
        </h1>
        <p class="text-gray-300 mb-8 text-center px-4 max-w-md">Gerakkan Alma ke Kanan/Kiri untuk menangkap koin yang jatuh!<br>Hindari Bom!</p>
        <button onclick="initGameSequence()" class="px-8 py-3 bg-accent text-black font-bold rounded-full text-xl hover:scale-110 transition-transform shadow-[0_0_20px_rgba(51,232,24,0.4)]">
            Mulai Main
        </button>
    </div>

    <!-- Game Over Screen -->
    <div id="gameOverScreen" class="absolute inset-0 bg-black/90 backdrop-blur-md flex flex-col items-center justify-center z-[110] hidden">
        <h2 class="text-5xl font-black text-red-500 mb-4 drop-shadow-[0_2px_0_rgba(255,255,255,0.2)]">GAME OVER</h2>
        <p class="text-white text-2xl mb-8">Skor Akhir: <span id="finalScore" class="text-accent font-bold">0</span></p>
        <div class="flex gap-4">
            <button onclick="closeGame()" class="px-6 py-3 border-2 border-white/20 text-white font-bold rounded-xl hover:bg-white/10 transition-colors">
                Keluar
            </button>
            <button onclick="initGameSequence()" class="px-8 py-3 bg-accent text-black font-bold rounded-xl hover:scale-105 transition-transform shadow-[0_0_20px_rgba(51,232,24,0.4)]">
                Main Lagi
            </button>
        </div>
    </div>
</div>

<!-- Audio Assets -->
<audio id="sfxCoin" src="<?= base_url('audio/coin.mp3') ?>" preload="auto"></audio>
<audio id="sfxGameOver" src="<?= base_url('audio/gameover.mp3') ?>" preload="auto"></audio>

<script>
    // Game Logic
    const canvas = document.getElementById('gameCanvas');
    const ctx = canvas.getContext('2d');
    const scoreEl = document.getElementById('scoreVal');
    const finalScoreEl = document.getElementById('finalScore');
    const startScreen = document.getElementById('startScreen');
    const gameOverScreen = document.getElementById('gameOverScreen');
    const gameContainer = document.getElementById('gameContainer');

    // Audio
    const sfxCoin = document.getElementById('sfxCoin');
    const sfxGameOver = document.getElementById('sfxGameOver');
    const bgMusic = new Audio('<?= base_url('audio/backsound.mp3') ?>');
    bgMusic.loop = true;
    bgMusic.volume = 0.5;

    let width, height;
    let score = 0;

    // Game States
    let gameRunning = false;
    let isCountingDown = false;
    let countdownVal = 3;
    let isMuted = false;

    let animationId;
    let frames = 0;
    let difficultyMultiplier = 1;

    // Combo System
    let combo = 0;
    let floatingTexts = []; // Popups

    const useImage = true;
    const almaImage = new Image();
    almaImage.src = '<?= base_url('images/game.png') ?>';

    // Platform settings
    const platformHeight = 80;

    const player = {
        x: 0,
        y: 0,
        radius: 50, // Increased size
        width: 100,
        height: 100,
        speed: 0.15
    };

    // Input state
    const input = {
        x: 0
    };

    let coins = [];
    let bombs = [];
    let particles = [];

    // Preload Images
    const coinImages = {};
    const iconPath = '<?= base_url('images/crypto_icons') ?>/';

    // Expanded Coin List (Forex & Crypto) - Updated for Single Unit Points (1-5 range)
    const coinTypes = [
        // Forex
        {
            symbol: 'XAU',
            name: 'Gold',
            value: 1,
            color: '#FFD700',
            img: 'XAUT.png'
        },
        {
            symbol: 'EUR',
            name: 'Euro',
            value: 1,
            color: '#3b82f6',
            img: null
        },
        {
            symbol: 'USD',
            name: 'Dollar',
            value: 1,
            color: '#22c55e',
            img: 'USDT.png'
        },
        {
            symbol: 'GBP',
            name: 'Pound',
            value: 1,
            color: '#8b5cf6',
            img: null
        },
        {
            symbol: 'JPY',
            name: 'Yen',
            value: 1,
            color: '#fb7185',
            img: null
        },
        {
            symbol: 'AUD',
            name: 'Aussie',
            value: 1,
            color: '#0ea5e9',
            img: null
        },
        // Crypto
        {
            symbol: 'BTC',
            name: 'Bitcoin',
            value: 3,
            color: '#F7931A',
            img: 'BTC.png'
        },
        {
            symbol: 'ETH',
            name: 'Ethereum',
            value: 2,
            color: '#627EEA',
            img: 'ETH.png'
        },
        {
            symbol: 'SOL',
            name: 'Solana',
            value: 2,
            color: '#14F195',
            img: 'SOL.png'
        },
        {
            symbol: 'BNB',
            name: 'Binance',
            value: 2,
            color: '#F3BA2F',
            img: 'BNB.png'
        },
        {
            symbol: 'XRP',
            name: 'Ripple',
            value: 1,
            color: '#23292F',
            img: 'XRP.png'
        },
        {
            symbol: 'DOGE',
            name: 'Doge',
            value: 1,
            color: '#C2A633',
            img: 'DOGE.png'
        },
        {
            symbol: 'ADA',
            name: 'Cardano',
            value: 1,
            color: '#0033AD',
            img: 'ADA.png'
        },
        {
            symbol: 'AVAX',
            name: 'Avalanche',
            value: 2,
            color: '#E84142',
            img: 'AVAX.png'
        },
        {
            symbol: 'SHIB',
            name: 'Shiba',
            value: 1,
            color: '#FFA409',
            img: 'SHIB.png'
        },
        {
            symbol: 'TRX',
            name: 'Tron',
            value: 1,
            color: '#EF0027',
            img: 'TRX.png'
        },
        {
            symbol: 'LTC',
            name: 'Litecoin',
            value: 1,
            color: '#345D9D',
            img: 'LTC.png'
        },
        {
            symbol: 'DOT',
            name: 'Polkadot',
            value: 2,
            color: '#E6007A',
            img: 'DOT.png'
        },
        // Special
        {
            symbol: 'WPA',
            name: 'WPA',
            value: 5,
            color: '#33e818',
            img: null
        },
        {
            symbol: 'ALMA',
            name: 'Alma',
            value: 5,
            color: '#ffffff',
            img: null
        }
    ];

    // Load available images
    coinTypes.forEach(type => {
        if (type.img) {
            const img = new Image();
            img.src = iconPath + type.img;
            coinImages[type.symbol] = img;
        }
    });

    function openGame() {
        gameContainer.classList.remove('hidden');
        gameContainer.classList.add('flex');
        document.body.style.overflow = 'hidden';
        resize();
        resetGameVars();
        // Show start screen
        startScreen.classList.remove('hidden');
        gameOverScreen.classList.add('hidden');
    }

    function closeGame() {
        gameRunning = false;
        isCountingDown = false;
        cancelAnimationFrame(animationId);

        bgMusic.pause();
        bgMusic.currentTime = 0;

        gameContainer.classList.add('hidden');
        gameContainer.classList.remove('flex');
        document.body.style.overflow = '';
    }

    function resize() {
        width = canvas.width = window.innerWidth;
        height = canvas.height = window.innerHeight;
        // Fix player Y position to be on top of the platform
        player.y = height - platformHeight - player.radius - 10;
        player.x = width / 2;
        input.x = width / 2;
    }
    window.addEventListener('resize', resize);

    // Input Handling (Mouse & Touch - X axis only)
    function updateInput(x) {
        input.x = x;
    }

    window.addEventListener('mousemove', (e) => {
        if (gameRunning || isCountingDown) updateInput(e.clientX);
    });

    window.addEventListener('touchmove', (e) => {
        if (gameRunning || isCountingDown) {
            e.preventDefault();
            updateInput(e.touches[0].clientX);
        }
    }, {
        passive: false
    });

    window.addEventListener('touchstart', (e) => {
        if (gameRunning || isCountingDown) {
            updateInput(e.touches[0].clientX);
        }
    }, {
        passive: false
    });

    // Entities
    class Coin {
        constructor() {
            this.radius = 22; // Slightly bigger for icons
            this.x = Math.random() * (width - 2 * this.radius) + this.radius;
            this.y = -50; // Start above screen
            this.speed = (Math.random() * 2 + 3) * difficultyMultiplier; // Faster initial speed

            const randomType = coinTypes[Math.floor(Math.random() * coinTypes.length)];
            this.symbol = randomType.symbol;
            this.value = randomType.value;
            this.color = randomType.color;
            this.img = randomType.img;
            this.floatOffset = Math.random() * Math.PI * 2;
            this.markedForDeletion = false;
        }

        update() {
            this.y += this.speed;
            if (this.y > height + 50) this.markedForDeletion = true;
        }

        draw() {
            const floatX = Math.sin(frames * 0.05 + this.floatOffset) * 2;
            ctx.save();
            ctx.translate(this.x + floatX, this.y);

            if (this.img && coinImages[this.symbol] && coinImages[this.symbol].complete) {
                // Draw Image
                // Shadow
                ctx.shadowBlur = 10;
                ctx.shadowColor = this.color;

                ctx.beginPath();
                ctx.arc(0, 0, this.radius, 0, Math.PI * 2);
                ctx.closePath();

                // Bevel/Mask effect for round icon
                ctx.clip();
                ctx.drawImage(coinImages[this.symbol], -this.radius, -this.radius, this.radius * 2, this.radius * 2);

                // Border ring
                ctx.strokeStyle = 'rgba(255,255,255,0.2)';
                ctx.lineWidth = 2;
                ctx.stroke();

            } else {
                // Fallback Draw
                // Outer Ring
                ctx.beginPath();
                ctx.arc(0, 0, this.radius, 0, Math.PI * 2);
                ctx.fillStyle = this.color;
                ctx.fill();

                // Inner Shine
                ctx.beginPath();
                ctx.arc(-5, -5, 5, 0, Math.PI * 2);
                ctx.fillStyle = 'rgba(255,255,255,0.3)';
                ctx.fill();

                ctx.fillStyle = (['WPA', 'ALMA', 'XRP'].includes(this.symbol)) ? 'black' : 'white';
                ctx.font = 'bold 9px Montserrat, sans-serif';
                ctx.textAlign = 'center';
                ctx.textBaseline = 'middle';
                ctx.fillText(this.symbol, 0, 0);
            }

            ctx.restore();
        }
    }

    class Bomb {
        constructor() {
            this.radius = 25; // Slightly bigger bombs
            this.x = Math.random() * (width - 2 * this.radius) + this.radius;
            this.y = -50;
            // Bombs are faster generally
            this.speed = (Math.random() * 3 + 4) * difficultyMultiplier;
            this.rotation = 0;
            this.rotationSpeed = (Math.random() - 0.5) * 0.3;
            this.markedForDeletion = false;
        }

        update() {
            this.y += this.speed;
            this.rotation += this.rotationSpeed;
            if (this.y > height + 50) this.markedForDeletion = true;
        }

        draw() {
            ctx.save();
            ctx.translate(this.x, this.y);
            ctx.rotate(this.rotation);

            // Spikes
            ctx.beginPath();
            const spikes = 12;
            for (let i = 0; i < spikes; i++) {
                const angle = (Math.PI * 2 / spikes) * i;
                const outer = this.radius + 5;
                const inner = this.radius - 2;
                ctx.lineTo(Math.cos(angle) * outer, Math.sin(angle) * outer);
                ctx.lineTo(Math.cos(angle + Math.PI / spikes) * inner, Math.sin(angle + Math.PI / spikes) * inner);
            }
            ctx.closePath();
            ctx.fillStyle = '#ef4444';
            ctx.fill();
            ctx.strokeStyle = '#7f1d1d';
            ctx.lineWidth = 2;
            ctx.stroke();

            // Body
            ctx.fillStyle = '#111';
            ctx.beginPath();
            ctx.arc(0, 0, this.radius * 0.6, 0, Math.PI * 2);
            ctx.fill();

            // Skull eyes
            ctx.fillStyle = 'red';
            ctx.beginPath();
            ctx.moveTo(-5, -2);
            ctx.lineTo(-8, 2);
            ctx.lineTo(-2, 2);
            ctx.fill();
            ctx.beginPath();
            ctx.moveTo(5, -2);
            ctx.lineTo(2, 2);
            ctx.lineTo(8, 2);
            ctx.fill();

            ctx.restore();
        }
    }

    class Particle {
        constructor(x, y, color) {
            this.x = x;
            this.y = y;
            this.color = color;
            this.radius = Math.random() * 3 + 1;
            const angle = Math.random() * Math.PI * 2;
            const speed = Math.random() * 4 + 2;
            this.vx = Math.cos(angle) * speed;
            this.vy = Math.sin(angle) * speed;
            this.alpha = 1;
        }
        update() {
            this.x += this.vx;
            this.y += this.vy;
            this.alpha -= 0.04;
        }
        draw() {
            ctx.save();
            ctx.globalAlpha = this.alpha;
            ctx.fillStyle = this.color;
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.radius, 0, Math.PI * 2);
            ctx.fill();
            ctx.restore();
        }
    }

    function drawPlatform() {
        // Draw a "Ground" or "Platform"
        ctx.save();

        // Gradient for platform
        const grad = ctx.createLinearGradient(0, height - platformHeight, 0, height);
        grad.addColorStop(0, '#1a1a1a');
        grad.addColorStop(0.1, '#33e818'); // Neon line on top
        grad.addColorStop(0.2, '#1a1a1a');
        grad.addColorStop(1, '#000000');

        ctx.fillStyle = grad;
        ctx.fillRect(0, height - platformHeight, width, platformHeight);

        // Tech details on platform
        ctx.fillStyle = 'rgba(51, 232, 24, 0.1)';
        const patternSize = 40;
        for (let x = (frames % patternSize); x < width; x += patternSize) {
            ctx.fillRect(x, height - platformHeight + 5, 2, 10);
        }

        ctx.restore();
    }

    function drawPlayer() {
        ctx.save();
        ctx.translate(player.x, player.y);

        // Tilt animation
        const deltaX = input.x - player.x;
        const tilt = Math.max(Math.min(deltaX * 0.05, 20), -20) * (Math.PI / 180);
        ctx.rotate(tilt);

        if (useImage && almaImage.complete) {
            // Shadow under player on platform
            ctx.save();
            ctx.translate(0, player.radius + 10);
            ctx.scale(1, 0.3);
            ctx.beginPath();
            ctx.arc(0, 0, player.radius, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(0,0,0,0.6)';
            ctx.fill();
            ctx.restore();

            ctx.drawImage(almaImage, -player.radius, -player.radius, player.radius * 2, player.radius * 2);
        } else {
            ctx.beginPath();
            ctx.arc(0, 0, player.radius, 0, Math.PI * 2);
            ctx.fillStyle = '#33e818';
            ctx.fill();
        }

        // Shield
        ctx.beginPath();
        ctx.arc(0, 0, player.radius + 5, 0, Math.PI * 2);
        ctx.strokeStyle = 'rgba(51, 232, 24, 0.4)';
        ctx.lineWidth = 2;
        ctx.stroke();

        ctx.restore();
    }

    function resetGameVars() {
        score = 0;
        frames = 0;
        difficultyMultiplier = 1;
        combo = 0;
        scoreEl.innerText = '0';
        coins = [];
        bombs = [];
        particles = [];
        floatingTexts = [];
        player.x = width / 2;
        input.x = width / 2;
    }

    function initGameSequence() {
        startScreen.classList.add('hidden');
        gameOverScreen.classList.add('hidden');
        resetGameVars();

        // Start Music
        bgMusic.currentTime = 0;
        bgMusic.play().catch(e => console.log('Audio autoplay prevented', e));

        isCountingDown = true;
        countdownVal = 3;

        animate(); // Start render loop

        const timer = setInterval(() => {
            countdownVal--;
            if (countdownVal <= 0) {
                clearInterval(timer);
                isCountingDown = false;
                gameRunning = true;
            }
        }, 1000);
    }

    // Floating Text Class
    class FloatingText {
        constructor(x, y, text, color) {
            this.x = x;
            this.y = y;
            this.text = text;
            this.color = color;
            this.dy = -2;
            this.alpha = 1;
            this.life = 40;
        }
        update() {
            this.y += this.dy;
            this.alpha -= 0.02;
            this.life--;
        }
        draw() {
            ctx.save();
            ctx.globalAlpha = Math.max(0, this.alpha);
            ctx.fillStyle = this.color;
            ctx.shadowColor = 'black';
            ctx.shadowBlur = 4;
            ctx.font = 'bold 24px Montserrat, sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText(this.text, this.x, this.y);
            ctx.restore();
        }
    }

    function drawUI() {
        if (combo > 1) {
            ctx.save();
            ctx.textAlign = 'right';
            ctx.textBaseline = 'top';
            ctx.font = 'italic 700 40px Montserrat, sans-serif';
            ctx.fillStyle = '#fbbf24';
            ctx.shadowColor = '#d97706';
            ctx.shadowBlur = 10;
            const scale = 1 + Math.sin(frames * 0.2) * 0.1;
            ctx.translate(width - 30, 90);
            ctx.scale(scale, scale);
            ctx.fillText(combo + "x COMBO!", 0, 0);
            ctx.restore();
        }
    }

    function update() {
        // Player Movement
        player.x += (input.x - player.x) * 0.2;
        if (player.x < player.radius) player.x = player.radius;
        if (player.x > width - player.radius) player.x = width - player.radius;

        if (isCountingDown) return;

        // Dynamic Difficulty Scaling
        difficultyMultiplier = 1 + (score / 2000);

        // Spawning Logic
        let spawnRate = Math.max(10, 50 - Math.floor(score / 200));
        if (frames % spawnRate === 0) {
            let bombChance = Math.min(0.6, 0.3 + (score / 10000));
            if (Math.random() < bombChance) {
                bombs.push(new Bomb());
            } else {
                coins.push(new Coin());
            }
        }

        // Entities
        for (let i = coins.length - 1; i >= 0; i--) {
            coins[i].update();
            coins[i].draw();

            // Check if missed (combo breaker)
            if (coins[i].y > height + 50 && !coins[i].markedForDeletion) {
                if (combo > 0) {
                    combo = 0;
                    floatingTexts.push(new FloatingText(coins[i].x, height - 50, "MISS", "#ef4444"));
                }
                coins[i].markedForDeletion = true;
            }

            const dist = Math.hypot(player.x - coins[i].x, player.y - coins[i].y);
            if (dist < player.radius + coins[i].radius) {
                // Collect
                combo++;
                let points = coins[i].value;
                let bonus = 0;
                if (combo > 5) bonus = Math.floor(points * Math.min(combo, 20) * 0.1);

                score += points + bonus;
                scoreEl.innerText = score.toLocaleString();
                sfxCoin.currentTime = 0;
                sfxCoin.play().catch(() => {});

                for (let k = 0; k < 5; k++) particles.push(new Particle(coins[i].x, coins[i].y, coins[i].color));

                let text = "+" + points;
                if (bonus > 0) text += " (" + combo + "x)";
                floatingTexts.push(new FloatingText(coins[i].x, coins[i].y - 30, text, "#33e818"));

                coins.splice(i, 1);
            } else if (coins[i].markedForDeletion) {
                coins.splice(i, 1);
            }
        }

        for (let i = bombs.length - 1; i >= 0; i--) {
            bombs[i].update();
            bombs[i].draw();

            const dist = Math.hypot(player.x - bombs[i].x, player.y - bombs[i].y);
            if (dist < player.radius + bombs[i].radius - 10) {
                gameOver();
            } else if (bombs[i].markedForDeletion) {
                bombs.splice(i, 1);
            }
        }

        for (let i = particles.length - 1; i >= 0; i--) {
            particles[i].update();
            particles[i].draw();
            if (particles[i].alpha <= 0) particles.splice(i, 1);
        }

        for (let i = floatingTexts.length - 1; i >= 0; i--) {
            floatingTexts[i].update();
            floatingTexts[i].draw();
            if (floatingTexts[i].life <= 0) floatingTexts.splice(i, 1);
        }

        frames++;
    }

    function animate() {
        ctx.clearRect(0, 0, width, height);

        drawPlatform();
        drawPlayer();

        if (isCountingDown) {
            ctx.save();
            ctx.fillStyle = 'rgba(0,0,0,0.5)';
            ctx.fillRect(0, 0, width, height);

            ctx.fillStyle = '#33e818';
            ctx.font = 'black 300px Montserrat, sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';
            ctx.shadowColor = 'rgba(51,232,24,0.8)';
            ctx.shadowBlur = 60;
            let text = countdownVal;
            if (countdownVal <= 0) text = "GO!";
            ctx.fillText(text, width / 2, height / 2);
            ctx.restore();
        } else if (gameRunning) {
            update();
            drawUI();
        }

        if (gameRunning || isCountingDown) {
            animationId = requestAnimationFrame(animate);
        }
    }

    function gameOver() {
        gameRunning = false;
        bgMusic.pause();
        sfxGameOver.currentTime = 0;
        sfxGameOver.play().catch(() => {});
        finalScoreEl.innerText = score.toLocaleString();
        gameOverScreen.classList.remove('hidden');
        gameOverScreen.classList.add('flex');
    }
</script>

<style>
    @keyframes float {
        0% {
            transform: translateY(0px) rotate(0deg);
        }

        50% {
            transform: translateY(-20px) rotate(5deg);
        }

        100% {
            transform: translateY(0px) rotate(0deg);
        }
    }

    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
</style>
<?= $this->endSection() ?>