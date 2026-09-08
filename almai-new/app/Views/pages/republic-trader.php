<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    .rt-bg {
        background-color: #050505;
        background-image: linear-gradient(135deg, rgba(0, 0, 0, 0.9) 0%, rgba(5, 5, 5, 0.95) 50%, rgba(10, 10, 10, 0.9) 100%);
    }
    
    .green-gradient-text {
        background: linear-gradient(to bottom, #4ade80, #16a34a, #14532d);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .red-gradient-text {
        background: linear-gradient(to bottom, #f87171, #dc2626, #7f1d1d);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .project-text {
        font-family: 'Impact', 'Arial Black', sans-serif;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .feature-box {
        background: rgba(15, 5, 5, 0.75);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(212, 175, 55, 0.5);
        box-shadow: 0 4px 25px rgba(0, 0, 0, 0.6);
        transition: all 0.3s ease;
    }

    .feature-box:hover {
        border-color: rgba(212, 175, 55, 0.8);
        box-shadow: 0 10px 30px -10px rgba(212, 175, 55, 0.4);
        transform: translateY(-5px);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<main class="rt-bg min-h-screen pt-24 pb-12 relative overflow-x-hidden font-montserrat">
    <!-- Video Background (Absolute to Main) -->
    <div class="absolute inset-0 z-0">
        <!-- Desktop Video -->
        <video autoplay muted loop playsinline class="hidden md:block w-full h-full object-cover opacity-40 object-center">
            <source src="<?= base_url('images/republic.mp4') ?>" type="video/mp4">
        </video>
        
        <!-- Mobile Video -->
        <video autoplay muted loop playsinline class="block md:hidden w-full h-full object-cover opacity-40 object-top">
            <source src="<?= base_url('images/republic-vertikal.mp4') ?>" type="video/mp4">
        </video>
        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-[#050505]/80 to-[#050505]"></div>
    </div>

    <!-- Background overlay textures (Neutral) -->
    <div class="absolute inset-0 z-0 opacity-10 pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

<div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
    <!-- Top Logo Section -->
    <div class="flex flex-col items-center justify-center text-center mb-6 sm:mb-8 animate-fade-in-up">
        <div class="w-24 sm:w-32 md:w-40 lg:w-44 mb-2 flex items-center justify-center">
            <img src="<?= base_url('images/logo-republic-trader.png') ?>" 
                 alt="Republic Trader Logo" 
                 class="w-full h-auto object-contain drop-shadow-[0_0_25px_rgba(255,215,0,0.2)]">
        </div>
    </div>

    <!-- Main Headline -->
    <div class="text-center mb-8 sm:mb-10 animate-fade-in-up" style="animation-delay: 0.1s;">
<h2 class="project-text font-extrabold text-white mb-2 leading-none tracking-tight
           text-5xl sm:text-6xl md:text-7xl lg:text-8xl
           drop-shadow-[0_8px_25px_rgba(0,0,0,0.9)]">
    PROJECT
</h2>

        <div class="project-text font-bold leading-none 
                    drop-shadow-[0_5px_10px_rgba(0,0,0,0.9)] 
                    flex flex-wrap sm:flex-nowrap items-center justify-center 
                    gap-2 sm:gap-3 md:gap-6">

            <span class="red-gradient-text"
                  style="font-size: clamp(1.8rem, 7vw, 6rem);">
                $200
            </span>

            <span class="text-yellow-500"
                  style="font-size: clamp(1.2rem, 4vw, 4rem);">
                <i class="fas fa-arrow-right"></i>
            </span>

            <span class="green-gradient-text"
                  style="font-size: clamp(1.8rem, 7vw, 6rem);">
                $100,000
            </span>
        </div>
    </div>

    <!-- Subheading -->
    <div class="text-center mb-10 sm:mb-12 animate-fade-in-up" style="animation-delay: 0.2s;">
        <p class="text-gray-300 font-bold tracking-widest text-center
                  text-[clamp(0.7rem,3.2vw,1.4rem)] 
                  flex flex-wrap justify-center gap-1 sm:gap-2">
            <span>12 TRADERS</span>
            <span class="text-red-500">•</span>
            <span>6 PAIRS</span>
            <span class="text-red-500">•</span>
            <span>1 VISION</span>
        </p>
    </div>

<!-- Call to Action Button -->
<div class="flex justify-center animate-fade-in-up mt-6 mb-8 px-4 sm:px-0">
    <a href="<?= base_url('referral/republictrader') ?>" 
       class="inline-flex items-center justify-center 
              w-full max-w-xs sm:max-w-sm md:max-w-md
              py-3 sm:py-4 
              text-sm sm:text-base md:text-lg 
              font-bold text-black rounded-xl 
              transition-all duration-300
              shadow-[0_0_20px_rgba(30,215,96,0.4)]
              hover:shadow-[0_0_40px_rgba(30,215,96,0.9)]"
       
       style="
       background: linear-gradient(
           to top, 
           rgba(0,0,0,0.35) 0%, 
           rgba(30,215,96,1) 60%
       );
       "
       
       onmouseover="this.style.background='#1ed760'"
       onmouseout="this.style.background='linear-gradient(to top, rgba(0,0,0,0.35) 0%, rgba(30,215,96,1) 60%)'">
       
        Gabung Komunitas
    </a>
</div>

    <!-- Quote -->
    <div class="text-center max-w-4xl mx-auto mb-14 sm:mb-16 mt-12 sm:mt-20 animate-fade-in-up" style="animation-delay: 0.4s;">
        <div class="flex items-center justify-center gap-2 sm:gap-4 mb-4 px-2">
            <div class="h-px bg-gradient-to-r from-transparent to-red-600 flex-1"></div>

            <h3 class="text-gray-300 font-bold tracking-wide text-center leading-relaxed
                       text-[clamp(0.9rem,3.5vw,1.6rem)] px-2 sm:px-4">
                KEKUATAN TERBESAR BUKAN ADA DI MODAL,<br class="hidden sm:block">
                MELAINKAN PADA <span class="text-red-500">MANUSIANYA.</span>
            </h3>

            <div class="h-px bg-gradient-to-l from-transparent to-red-600 flex-1"></div>
        </div>
    </div>

</div>

        <!-- Features Bottom Bar -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-3 md:gap-4 max-w-6xl mx-auto px-4 sm:px-6 animate-fade-in-up" style="animation-delay: 0.5s;">
            
            <!-- Feature 1 -->
            <div class="feature-box rounded-xl p-4 flex flex-col items-center justify-center text-center group cursor-default">
                <div class="text-yellow-500 text-2xl md:text-3xl mb-3 opacity-90 group-hover:opacity-100 transition-opacity drop-shadow-[0_0_12px_rgba(212,175,55,0.6)]">
                    <i class="fas fa-users"></i>
                </div>
                <h4 class="text-yellow-400 font-bold text-sm md:text-base uppercase mb-1 group-hover:text-yellow-300 transition-colors drop-shadow-md">Power Circle</h4>
                <p class="text-gray-200 text-xs md:text-sm drop-shadow-md">Bersatu dalam visi.</p>
            </div>

            <!-- Feature 2 -->
            <div class="feature-box rounded-xl p-4 flex flex-col items-center justify-center text-center group cursor-default">
                <div class="text-yellow-500 text-2xl md:text-3xl mb-3 opacity-90 group-hover:opacity-100 transition-opacity drop-shadow-[0_0_12px_rgba(212,175,55,0.6)]">
                    <i class="fas fa-bullseye"></i>
                </div>
                <h4 class="text-yellow-400 font-bold text-sm md:text-base uppercase mb-1 group-hover:text-yellow-300 transition-colors drop-shadow-md">Prinsip</h4>
                <p class="text-gray-200 text-xs md:text-sm drop-shadow-md">Satu aturan.</p>
            </div>

            <!-- Feature 3 -->
            <div class="feature-box rounded-xl p-4 flex flex-col items-center justify-center text-center group cursor-default">
                <div class="text-yellow-500 text-2xl md:text-3xl mb-3 opacity-90 group-hover:opacity-100 transition-opacity drop-shadow-[0_0_12px_rgba(212,175,55,0.6)]">
                    <i class="fas fa-chess-king"></i>
                </div>
                <h4 class="text-yellow-400 font-bold text-sm md:text-base uppercase mb-1 group-hover:text-yellow-300 transition-colors drop-shadow-md">Leadership</h4>
                <p class="text-gray-200 text-xs md:text-sm drop-shadow-md">Arah yang jelas.</p>
            </div>

            <!-- Feature 4 -->
            <div class="feature-box rounded-xl p-4 flex flex-col items-center justify-center text-center group cursor-default">
                <div class="text-yellow-500 text-2xl md:text-3xl mb-3 opacity-90 group-hover:opacity-100 transition-opacity drop-shadow-[0_0_12px_rgba(212,175,55,0.6)]">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h4 class="text-yellow-400 font-bold text-sm md:text-base uppercase mb-1 group-hover:text-yellow-300 transition-colors drop-shadow-md">Mentorship</h4>
                <p class="text-gray-200 text-xs md:text-sm drop-shadow-md">Bimbingan yang tepat.</p>
            </div>

            <!-- Feature 5 -->
            <div class="feature-box rounded-xl p-4 flex flex-col items-center justify-center text-center group cursor-default col-span-2 md:col-span-1">
                <div class="text-yellow-500 text-2xl md:text-3xl mb-3 opacity-90 group-hover:opacity-100 transition-opacity drop-shadow-[0_0_12px_rgba(212,175,55,0.6)]">
                    <i class="fas fa-bolt"></i>
                </div>
                <h4 class="text-yellow-400 font-bold text-sm md:text-base uppercase mb-1 group-hover:text-yellow-300 transition-colors drop-shadow-md">Powerful Result</h4>
                <p class="text-gray-200 text-xs md:text-sm drop-shadow-md">Akun kecil bukan masalah.</p>
            </div>


        </div>

        </div>
    </div>
</main>

<!-- Live Portfolio Section (After Hero) -->
<section class="py-24 bg-[#050505] relative z-20 border-t border-white/5">
    <div class="container mx-auto px-4">
        
        <!-- Portfolio Iframe -->
        <div class="max-w-7xl mx-auto mb-4 w-full animate-fade-in-up">
            <div class="text-center mb-10">
                <h3 class="text-2xl md:text-3xl font-black text-white tracking-widest uppercase mb-2">Live Portofolio</h3>
                <p class="text-sm md:text-base text-gray-400">Pantau performa real-time tim Republic Trader di bawah ini</p>
            </div>
            <a href="https://www.myfxbook.com/members/suryagede/scalpers-circle-republic/12096504" target="_blank" rel="noopener noreferrer" class="block w-full max-w-4xl mx-auto hover:opacity-90 transition-opacity">
                <img src="https://widgets.myfxbook.com/widgets/12096504/large.jpg" alt="Portfolio Republic Trader MyFxBook" class="w-full h-auto rounded-lg shadow-2xl">
                <p class="text-gray-400 text-sm mt-4 text-center hover:text-white transition-colors">Klik gambar untuk melihat detail lengkap di website MyFxBook</p>
            </a>
        </div>

<!-- Call to Action Button -->
<div class="flex justify-center animate-fade-in-up mt-6 mb-8 px-4 sm:px-0">
    <a href="<?= base_url('referral/republictrader') ?>" 
       class="inline-flex items-center justify-center 
              w-full max-w-xs sm:max-w-sm md:max-w-md
              py-3 sm:py-4 
              text-sm sm:text-base md:text-lg 
              font-bold text-black rounded-xl 
              transition-all duration-300
              shadow-[0_0_20px_rgba(30,215,96,0.4)]
              hover:shadow-[0_0_40px_rgba(30,215,96,0.9)]"
       
       style="
       background: linear-gradient(
           to top, 
           rgba(0,0,0,0.35) 0%, 
           rgba(30,215,96,1) 60%
       );
       "
       
       onmouseover="this.style.background='#1ed760'"
       onmouseout="this.style.background='linear-gradient(to top, rgba(0,0,0,0.35) 0%, rgba(30,215,96,1) 60%)'">
       
        Gabung Komunitas
    </a>
</div>
        
    </div>
</section>
<?= $this->endSection() ?>
