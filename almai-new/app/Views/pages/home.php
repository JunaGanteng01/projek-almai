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
                ✦ PLATFORM PENASIHAT
            </span>
        </div>

<!-- Main Combined Title (STATIC VERSION - NO ANIMATION) -->

<h1 class="font-black tracking-tighter text-white uppercase text-center flex flex-col items-center mt-6 md:mt-10">

    <?php 
    $fontSize = "clamp(1.8rem, 6.5vw, 4.2rem)";
    $lineHeight = "1.05";
    ?>

    <span class="block drop-shadow-lg"
        style="font-size: <?= $fontSize ?>; line-height: <?= $lineHeight ?>;">
        Derivatif Keuangan
    </span>

    <span
        class="block drop-shadow-lg text-transparent bg-clip-text bg-gradient-to-r from-accent via-green-400 to-emerald-500"
        style="font-size: <?= $fontSize ?>; line-height: <?= $lineHeight ?>;">
        & Aset Digital
    </span>

</h1>

<!-- Description -->
<p class="text-xs sm:text-base md:text-lg text-gray-400 mt-6 max-w-4xl mx-auto leading-relaxed font-medium" data-aos=" data-aos-delay="200">
    Platform resmi rujukan Trader Indonesia untuk pendampingan transaksi Derivatif, Forex dan Crypto bersama mentor profesional
    bersertifikat— <span class="text-white font-semibold">  Wakil Penasihat Berjangka (WPA)</span>
</p>

<!-- Buttons Area -->
<div class="flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-6 mt-12 md:mt-16" data-aos="fade-up" data-aos-delay="300">
    <?php 
    $refCode = (isset($referralWpa) && $referralWpa && !empty($referralWpa['code_referral'])) ? $referralWpa['code_referral'] : '';
    $refUrl = !empty($refCode) ? '?ref=' . urlencode($refCode) : '';
    ?>
<a href="https://ceklegalitas.bappebti.go.id/" 
                   target="_blank" 
                   rel="noopener noreferrer"
                   class="w-full sm:w-auto px-6 py-3 sm:px-8 sm:py-4 text-sm sm:text-base bg-accent/10 text-accent font-bold rounded-xl border border-accent/20 hover:bg-accent hover:text-black transition-all flex items-center justify-center gap-2">
                    <i class="fas fa-search-dollar"></i> Cek Broker dan Penasihat Anda
                </a>

<a href="https://almai.id/daftar-wpa" target="_blank" 
   class="w-full sm:w-auto px-6 py-3 sm:px-10 sm:py-4 bg-white/5 border border-white/10 text-white font-bold text-sm rounded-xl hover:bg-white/10 transition backdrop-blur-md flex items-center justify-center gap-2 tracking-tighter sm:tracking-widest">
    <i class="fas fa-graduation-cap text-xs"></i> 
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
                    <!-- Original -->
                    <img src="<?= base_url('images/legal/bank-indonesia.png') ?>" alt="Bank Indonesia" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/ojk.png') ?>" alt="OJK" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/bappebti.svg') ?>" alt="BAPPEBTI" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/komdigi.png') ?>" alt="KOMDIGI" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/aspebtindo.avif') ?>" alt="ASPEBTINDO" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/cfx.webp') ?>" alt="CFX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/jfx.png') ?>" alt="JFX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/icdx.png') ?>" alt="ICDX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/lpk.png') ?>" alt="LPK PBK" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    
                    <!-- Duplicated to prevent cut-off on wide screens -->
                    <img src="<?= base_url('images/legal/bank-indonesia.png') ?>" alt="Bank Indonesia" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/ojk.png') ?>" alt="OJK" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/bappebti.svg') ?>" alt="BAPPEBTI" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/komdigi.png') ?>" alt="KOMDIGI" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/aspebtindo.avif') ?>" alt="ASPEBTINDO" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/cfx.webp') ?>" alt="CFX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/jfx.png') ?>" alt="JFX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/icdx.png') ?>" alt="ICDX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/lpk.png') ?>" alt="LPK PBK" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                </div>

                <!-- SET 2 -->
                <div class="marquee-content" aria-hidden="true">
                    <!-- Original -->
                    <img src="<?= base_url('images/legal/bank-indonesia.png') ?>" alt="Bank Indonesia" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/ojk.png') ?>" alt="OJK" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/bappebti.svg') ?>" alt="BAPPEBTI" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/komdigi.png') ?>" alt="KOMDIGI" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/aspebtindo.avif') ?>" alt="ASPEBTINDO" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/cfx.webp') ?>" alt="CFX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/jfx.png') ?>" alt="JFX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/icdx.png') ?>" alt="ICDX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/lpk.png') ?>" alt="LPK PBK" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">

                    <!-- Duplicated to prevent cut-off on wide screens -->
                    <img src="<?= base_url('images/legal/bank-indonesia.png') ?>" alt="Bank Indonesia" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/ojk.png') ?>" alt="OJK" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/bappebti.svg') ?>" alt="BAPPEBTI" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/komdigi.png') ?>" alt="KOMDIGI" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/aspebtindo.avif') ?>" alt="ASPEBTINDO" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/cfx.webp') ?>" alt="CFX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/jfx.png') ?>" alt="JFX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/icdx.png') ?>" alt="ICDX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/lpk.png') ?>" alt="LPK PBK" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                </div>
            </div>
        </div>
    </div>


</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Pass user names only (no avatars, using initials)
        let recentUsers = <?= json_encode(array_map(function ($u) {
                                return [
                                    'name' => $u['name'],
                                    'isNew' => false // Default flag
                                ];
                            }, $recentUsers)) ?>;

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

                // Loop every 3 seconds for faster rotation
                loopTimeout = setTimeout(showNextUser, 3000);
            }
        }
    });
</script>

<!-- SECTION - STORY FAQ -->
<section class="py-20 relative bg-[#0a0a0a] border-t border-white/5 overflow-hidden">
    <div class="container mx-auto px-6 max-w-4xl">

        <!-- HEADER -->
        <div class="text-center mb-14 max-w-3xl mx-auto" data-aos="fade-up">
            
            <h3 class="text-red-500 font-semibold uppercase tracking-widest text-sm mb-3">
                Realita Dunia Trading
            </h3>

            <h2 class="text-2xl md:text-4xl font-bold text-white mb-5 leading-snug">
                "Industri berkembang sangat cepat, —
                <span class="text-red-500">namun perlindungan trader masih tertinggal."</span>
            </h2>
<br>
            <p class="text-gray-400 text-sm md:text-base leading-relaxed">
                Di balik aktivitas trading yang terlihat normal, terdapat berbagai risiko tersembunyi yang sering tidak disadari trader — 
                <span class="text-white font-semibold">
                    mulai dari broker bermasalah, manipulasi sistem, hingga tidak adanya perlindungan saat terjadi sengketa.
                </span>
                <br>
                Banyak trader baru menyadari hal ini setelah mengalami kerugian yang tidak bisa diklaim.
            </p>

        </div>
<br>

<!-- BOX -->
<div class="bg-gradient-to-br from-green-900/20 to-black p-6 md:p-8 rounded-3xl border border-accent/20 text-center" data-aos=>
    <img src="<?= base_url('images/almai-full.png') ?>" alt="Almai" class="h-10 mx-auto mb-6 opacity-80" onerror="this.src=''; this.className='hidden'">
    
    <h3 class="text-2xl md:text-3xl font-black text-white mb-4 leading-tight">
        The First Trading Protection<br>Ecosystem in Indonesia
    </h3>

<!-- GREEN DIVIDER (ANTI GAGAL) -->
<hr style="
    width:100px;
    height:4px;
    margin:20px auto 24px auto;
    border:none;
    border-radius:9999px;
    background:#22c55e;
    box-shadow:0 0 10px #22c55e, 0 0 20px rgba(34,197,94,0.8);
    position:relative;
    z-index:10;
">

    <p class="text-base md:text-lg text-gray-300 leading-relaxed mb-5">
        Almai hadir bukan sekadar sebagai penyedia teknologi bersertifikasi, tetapi sebagai 
        <span class="text-white font-semibold">ekosistem perlindungan trader</span> 
        yang menggabungkan:
    </p>

    <p class="text-base md:text-lg text-white font-semibold leading-relaxed mb-6">
        • Edukasi terstruktur <br>
        • Expert Advisor
        • Advokasi & perlindungan hukum
    </p>

    <p class="text-base md:text-lg text-gray-300 leading-relaxed mb-6">
        Semua dalam satu sistem yang terintegrasi untuk memastikan Anda tidak lagi menghadapi risiko sendirian.
    </p>

    <a href="https://almai.id/advokasi" class="text-accent text-base md:text-lg font-bold hover:underline inline-flex items-center gap-2">
        Pelajari Program Advokasi 
        <i class="fas fa-chevron-right text-sm"></i>
    </a>
</div>

</section>

<!-- Why Almai Section -->
<section class="py-24 relative overflow-hidden bg-black">
    <div class="container mx-auto px-4 sm:px-6 relative z-10">
        <div class="text-center max-w-4xl mx-auto mb-16">
            <h3 class="text-accent font-semibold uppercase tracking-widest text-sm mb-3" data-aos="fade-up">
                Almai Value
            </h3>
        
        <!-- HEADER -->
        <div class="text-center max-w-4xl mx-auto mb-16">
            <h2 class="text-3xl md:text-5xl font-black text-white mb-6 uppercase tracking-tight" data-aos="fade-up">
                Kenapa <span class="text-accent">Almai</span> Menjadi Pilihan Tepat untuk Trader
            </h2>

            <p class="text-gray-400 text-sm sm:text-base md:text-lg leading-relaxed" data-aos="fade-up" data-aos-delay="100">
                Trading bukan hanya tentang mencari peluang profit, tetapi juga tentang 
                <span class="text-white font-bold">keamanan, kepastian, dan pendampingan yang tepat saat menghadapi risiko market.</span> 
                Ketika terjadi kendala sistem, broker bermasalah, hingga sengketa, Anda membutuhkan pihak yang 
                <span class="text-white font-bold">berpengalaman, berpihak, dan siap melindungi kepentingan Anda.</span>
            </p>

<!-- GREEN DIVIDER (ANTI GAGAL) -->
<hr style="
    width:100px;
    height:4px;
    margin:20px auto 24px auto;
    border:none;
    border-radius:9999px;
    background:#22c55e;
    box-shadow:0 0 10px #22c55e, 0 0 20px rgba(34,197,94,0.8);
    position:relative;
    z-index:10;
">
        </div>
        <!-- CARDS -->
        <div class="flex justify-center">
            <div class="grid md:grid-cols-3 gap-6 lg:gap-8 max-w-5xl w-full">
                
                <!-- Card 1 -->
                <div class="bg-[#0f0f0f] border border-white/5 p-8 rounded-3xl 
                hover:border-accent/30 transition-all duration-500 hover:-translate-y-2 hover:shadow-xl group 
                flex flex-col items-center text-center gap-6" 
                data-aos="fade-up" data-aos-delay="200">

                    <div class="w-14 h-14 rounded-full bg-accent/10 flex items-center justify-center text-accent group-hover:scale-110 transition-transform duration-500">
                        <i class="fas fa-certificate text-2xl"></i>
                    </div>

                    <div>
                        <h3 class="text-white font-black text-xl uppercase tracking-wider mb-4">TRUSTED</h3>
                        <p class="text-gray-500 text-sm leading-relaxed group-hover:text-gray-300 transition-colors">
                            Berbasis kepatuhan regulasi, transparansi layanan, dan sistem pendampingan yang membantu menjaga keamanan aktivitas trading Anda.
                        </p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-[#0f0f0f] border border-white/5 p-8 rounded-3xl 
                hover:border-accent/30 transition-all duration-500 hover:-translate-y-2 hover:shadow-xl group 
                flex flex-col items-center text-center gap-6" 
                data-aos="fade-up" data-aos-delay="300">

                    <div class="w-14 h-14 rounded-full bg-accent/10 flex items-center justify-center text-accent group-hover:scale-110 transition-transform duration-500">
                        <i class="fas fa-chart-line text-2xl"></i>
                    </div>

                    <div>
                        <h3 class="text-white font-black text-xl uppercase tracking-wider mb-4">SPECIALIST</h3>
                        <p class="text-gray-500 text-sm leading-relaxed group-hover:text-gray-300 transition-colors">
                            Didukung tenaga profesional berpengalaman dengan pendekatan strategis, edukatif, dan fokus pada kebutuhan trader modern.
                        </p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-[#0f0f0f] border border-white/5 p-8 rounded-3xl 
                hover:border-accent/30 transition-all duration-500 hover:-translate-y-2 hover:shadow-xl group 
                flex flex-col items-center text-center gap-6" 
                data-aos="fade-up" data-aos-delay="400">

                    <div class="w-14 h-14 rounded-full bg-accent/10 flex items-center justify-center text-accent group-hover:scale-110 transition-transform duration-500">
                        <i class="fas fa-shield-halved text-2xl"></i>
                    </div>

                    <div>
                        <h3 class="text-white font-black text-xl uppercase tracking-wider mb-4">EFFICIENT</h3>
                        <p class="text-gray-500 text-sm leading-relaxed group-hover:text-gray-300 transition-colors">
                            Respons cepat, proses pendampingan yang efektif, serta solusi yang tepat untuk membantu meminimalkan risiko dan menghindari masalah yang lebih besar.
                        </p>
                    </div>

                </div>

            </div>
        </div>


</section>




<script>
    function toggleRiskAccordion(id) {
        const content = document.getElementById(`risk-content-${id}`);
        const chevron = document.getElementById(`risk-chevron-${id}`);
        const allContents = [1, 2, 3];
        
        allContents.forEach(num => {
            const currentContent = document.getElementById(`risk-content-${num}`);
            const currentChevron = document.getElementById(`risk-chevron-${num}`);
            
            if (num === id) {
                // Toggle target
                if (currentContent.style.maxHeight && currentContent.style.maxHeight !== "0px") {
                    currentContent.style.maxHeight = "0px";
                    currentChevron.style.transform = "rotate(0deg)";
                } else {
                    currentContent.style.maxHeight = currentContent.scrollHeight + "px";
                    currentChevron.style.transform = "rotate(180deg)";
                }
            } else {
                // Close others
                currentContent.style.maxHeight = "0px";
                currentChevron.style.transform = "rotate(0deg)";
            }
        });
    }
</script>

<!-- Event Calendar Section -->
<section class="py-24 relative overflow-hidden bg-black">
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-accent/5 blur-[120px] rounded-full pointer-events-none"></div>
    
    <div class="container mx-auto px-4 sm:px-6 relative z-10">
        <!-- Section Header -->
        <div class="text-center max-w-4xl mx-auto mb-16">
            <h3 class="text-accent font-semibold uppercase tracking-widest text-sm mb-3" data-aos="fade-up">
                Jadwal Mingguan
            </h3>
            <h2 class="text-3xl md:text-5xl font-black text-white mb-6 uppercase tracking-tight" data-aos="fade-up" data-aos-delay="100">
                Event dan <span class="text-accent">Workshop</span> Terdekat
            </h2>
            <p class="text-gray-400 text-sm sm:text-base md:text-lg leading-relaxed" data-aos="fade-up" data-aos-delay="200">
                Jelajahi event dan workshop <span class="text-white font-bold uppercase">ALMAI</span> terdekat yang dapat <span class="text-white font-bold">membimbing</span> dan <span class="text-white font-bold">mendampingi</span> Anda tentang skill trading, pemahaman risiko, yang dapat meningkatkan level anda - sebelum market menguji tanpa ampun.
            </p>
            <!-- GREEN DIVIDER (ANTI GAGAL) -->
<hr style="
    width:100px;
    height:4px;
    margin:20px auto 24px auto;
    border:none;
    border-radius:9999px;
    background:#22c55e;
    box-shadow:0 0 10px #22c55e, 0 0 20px rgba(34,197,94,0.8);
    position:relative;
    z-index:10;
">
            <!-- 3-CARD EVENT DISPLAY (WITH DECORATIVE ARROWS) -->
        <div class="relative pt-12 mb-20 max-w-[1400px] mx-auto px-4">
            <div class="relative w-full">
                <!-- Grid Container: 8 Columns on Large Screens -->
                <!-- Guaranteed Horizontal Container: Scrollable on Mobile, Fixed Row on Desktop -->
<div id="event-scroll" class="flex flex-row flex-nowrap gap-2 md:gap-3 relative z-10 overflow-x-auto md:overflow-x-visible no-scrollbar pb-8 md:pb-0 w-full">
                <?php
                $currentDayNum = (int)date('N'); // 1 (Mon) - 7 (Sun)
                
                $allEvents = [

                    1 => [
                        'day' => 1,
                        'name' => 'SENIN',
                        'title' => 'PAHAM  REGULASI & PERLINDUNGAN TRADER',
                        'desc' => 'Topik : Legalitas, Keamanan Margin, Advokasi, dan Perlindungan Trader ',
                        'points' => ['Legalitas Derivatif Keuangan & Aset Digital', 'Struktur Regulator dan Pengawasan di Indonesia ', 'Advokasi dan Perlindungan Trader '],
                        'outcome' => 'Aman'
                    ],
                    2 => [
                        'day' => 2,
                        'name' => 'SELASA',
                        'title' => 'PAHAM  DERIVATIF  TRADING  & 
AUTOMATED TOOLS (EA AIWE)',
                        'desc' => 'Topik : Struktur Market Derivatif, Platform Trading, Analisis Teknikal, dan EA AIWE ',
                        'points' => ['Struktur Market Derivatif ', 'Analisis Teknikal Dasar', 'Automated Tools EA AIWE '],
                        'outcome' => 'Paham'
                    ],
                    3 => [
                        'day' => 3,
                        'name' => 'RABU',
                        'title' => 'PAHAM  ASET  KEUANGAN  DIGITAL  & SMART TOOLS (EA BIDBOX)',
                        'desc' => 'Topik : Crypto, Blockchain, Market Digital, dan EA BIDBOX',
                        'points' => ['Dasar Crypto dan Blockchain ', 'Karakteristik Market Crypto ', 'Smart Tools EA BIDBOX '],
                        'outcome' => 'Rasional'
                    ],
                    4 => [
                        'day' => 4,
                        'name' => 'KAMIS',
                        'title' => 'MEMBANGUN  BUDAYA  TRADING  YANG SEHAT ',
                        'desc' => 'Topik 
Disiplin, Psikologi Trading, dan Etika Trader. ',
                        'points' => ['Mindset dan Psikologi Trading', 'Manajemen Risiko dan Pengendalian Diri ', 'Etika dan Profesionalisme Trader '],
                        'outcome' => 'Teruji'
                    ],
                    5 => [
                        'day' => 5,
                        'name' => 'JUMAT',
                        'title' => 'Optimize — Strategi & Konsistensi',
                        'desc' => 'Mengoptimalkan strategi trading agar lebih stabil, konsisten, dan terukur.',
                        'points' => ['Optimasi risk-reward', 'Refinement strategi', 'Konsistensi profit'],
                        'outcome' => 'Stabil'
                    ],
                    6 => [
                        'day' => 6,
                        'name' => 'SABTU',
                        'title' => 'Defend — Proteksi & Advokasi',
                        'desc' => 'Mempersiapkan langkah perlindungan dan advokasi jika terjadi kendala atau sengketa.',
                        'points' => ['Prosedur komplain', 'Legal protection', 'Escalation strategy'],
                        'outcome' => 'Terlindungi'
                    ],
                    7 => [
                        'day' => 7,
                        'name' => 'MINGGU',
                        'title' => 'Recap — Evaluasi & Outlook',
                        'desc' => 'Menyusun evaluasi mingguan dan mempersiapkan strategi untuk market berikutnya.',
                        'points' => ['Review performa', 'Evaluasi kesalahan', 'Rencana minggu depan'],
                        'outcome' => 'Siap'
                    ]
                ];

                foreach ($allEvents as $id => $event): 
                    $isActive = ($id === $currentDayNum);
                ?>
                    <!-- Better Proportioned Card -->
                    <div class="relative group flex-shrink-0 w-28 md:w-full md:flex-1 min-w-0 <?= $isActive ? 'event-active' : '' ?>">
                        <?php if ($isActive): ?>
                            <!-- Active Glow Effect -->
                            <div class="absolute inset-0 bg-accent/20 blur-2xl rounded-3xl animate-pulse"></div>
                        <?php endif; ?>

                        <div class="relative h-48 w-full bg-[#0a0a0a] backdrop-blur-xl border <?= $isActive ? 'border-accent shadow-[0_0_20px_rgba(51,232,24,0.15)]' : 'border-white/5' ?> rounded-2xl p-4 flex flex-col items-center justify-between text-center group-hover:border-accent/40 transition-all duration-500">
                            
                            <!-- Background Pattern -->
                            <div class="absolute inset-0 opacity-10 group-hover:opacity-20 transition-opacity pointer-events-none">
                                <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_50%_50%,rgba(51,232,24,0.1),transparent_70%)]"></div>
                            </div>

                            <div class="flex flex-col items-center gap-0.5">
                                <?php if ($id !== 8): ?>
                                    <div class="text-[9px] font-black text-accent/60 uppercase tracking-wider">Day <?= $id ?></div>
                                <?php else: ?>
                                    <div class="text-[9px] font-black text-transparent select-none">.</div>
                                <?php endif; ?>
                                <h4 class="text-xs md:text-sm font-black <?= $isActive ? 'text-accent' : 'text-white' ?> uppercase tracking-tighter leading-tight"><?= $event['name'] ?></h4>
                            </div>

                            <!-- Icon Area -->
                            <div class="h-10 flex items-center justify-center">
                                <?php if ($isActive): ?>
                                    <img src="<?= base_url('images/lari.gif') ?>" alt="Running" class="w-10 h-10 object-contain drop-shadow-[0_0_8px_rgba(51,232,24,0.3)]">
                                <?php else: ?>
                                    <div class="w-10 h-10"></div>
                                <?php endif; ?>
                            </div>
                            
                            <button onclick="openEventModal(<?= $id ?>)" class="relative z-20 w-full py-2 bg-accent/10 hover:bg-accent text-accent hover:text-black text-[8px] md:text-[10px] font-black border border-accent/20 rounded-xl transition-all duration-300 uppercase tracking-widest">
                                Detail
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Event Detail Modal -->
        <div id="event-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-2 md:p-4 bg-black/90 backdrop-blur-md transition-all duration-300 opacity-0">
            <div class="bg-[#0a0a0a] border border-accent/20 rounded-[2.5rem] max-w-lg w-full p-8 md:p-10 relative transform scale-95 transition-all duration-300 shadow-[0_0_50px_rgba(51,232,24,0.1)]">
                <button onclick="closeEventModal()" class="absolute top-6 right-6 w-10 h-10 rounded-full bg-white/5 flex items-center justify-center text-gray-400 hover:text-white hover:bg-white/10 transition-all">
                    <i class="fas fa-times"></i>
                </button>
<div id="modal-content" class="text-center text-sm md:text-base"></div>
            </div>
        </div>

        <script>
            const eventData = <?= json_encode($allEvents) ?>;
            function openEventModal(id) {
                const event = eventData[id];
                const modal = document.getElementById('event-modal');
                const content = document.getElementById('modal-content');
                
                content.innerHTML = `
                    <h4 class="text-accent text-sm font-black tracking-[0.3em] uppercase mb-3">${id === 8 ? '' : `Day ${id} — `}${event.name}</h4>
<h2 class="text-white text-base md:text-lg font-black mb-3 uppercase tracking-tight leading-tight">${event.title}</h2>
                    <div class="w-12 h-1 bg-accent mx-auto mb-8 rounded-full shadow-[0_0_10px_#33e818]"></div>
<p class="text-gray-400 text-sm md:text-base italic mb-6 leading-relaxed px-2 md:px-4">"${event.desc}"</p>
                    
                    <div class="space-y-4 mb-10">
                        ${event.points.map(p => `
                            <div class="flex items-center gap-4 bg-white/5 py-4 px-6 rounded-2xl border border-white/5 text-left group/item hover:border-accent/20 transition-all">
                                <div class="w-2.5 h-2.5 rounded-full bg-accent shadow-[0_0_8px_#33e818] shrink-0"></div>
<span class="text-gray-200 text-xs md:text-sm font-medium">${p}</span>
                            </div>
                        `).join('')}
                    </div>
                    
                    <div class="pt-6 border-t border-white/5">
                        <div class="inline-block px-8 py-4 bg-accent text-black font-black rounded-2xl text-sm uppercase tracking-[0.2em] shadow-lg shadow-accent/20">
                            Outcome: ${event.outcome}
                        </div>
                    </div>
                `;
                
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    modal.querySelector('div').classList.remove('scale-95');
                }, 10);
            }

            function closeEventModal() {
                const modal = document.getElementById('event-modal');
                modal.classList.add('opacity-0');
                modal.querySelector('div').classList.add('scale-95');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 300);
            }

            document.getElementById('event-modal').addEventListener('click', (e) => {
                if(e.target === document.getElementById('event-modal')) closeEventModal();
            });
        </script>

            
            <!-- Custom Scrollbar Hide -->
            <style>
                .no-scrollbar::-webkit-scrollbar { display: none; }
                .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
            </style>
        </div>
    </div>

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

        <?php if (!empty($eventBanners)): ?>
            <div class="relative w-full overflow-hidden rounded-3xl group">
                <!-- 🔥 BORDER PULSE -->
                <div class="border-pulse"></div>

                <div class="flex transition-transform duration-500 ease-in-out" id="bannerSliderContainer">
                    <?php foreach ($eventBanners as $banner): ?>
                    <div class="w-full flex-none flex" style="flex: 0 0 100%; max-width: 100%;">
                        <!-- CARD -->
                        <div class="relative bg-zinc-900 border border-accent/30 
                                    flex flex-col md:flex-row items-center z-10 w-full min-h-[350px] rounded-3xl">

                            <!-- IMAGE -->
                            <div class="md:w-[45%] lg:w-[42%] flex items-center justify-center p-4 md:p-6 lg:p-8">
                                <img
                                    src="<?= base_url($banner['image']) ?>"
                                    alt="<?= esc($banner['title']) ?>"
                                    class="max-h-[320px] w-auto object-contain rounded-xl">
                            </div>

                            <!-- CONTENT -->
                            <div class="md:w-[55%] lg:w-[58%] p-5 sm:p-6 lg:p-8 flex flex-col justify-center">
                                <h3 class="font-bold text-white leading-tight mb-3 text-xl sm:text-2xl">
                                    <?= esc($banner['title']) ?>
                                </h3>

                                <div class="text-gray-300 mb-5 text-xs md:text-sm leading-normal line-clamp-5">
                                    <?= nl2br(esc($banner['content'])) ?>
                                </div>

                                <?php if(!empty($banner['url'])): ?>
                                <div class="flex flex-col gap-2 max-w-xs mt-2">
                                    <a href="<?= esc($banner['url']) ?>"
                                       target="_blank"
                                       class="w-full px-3 py-2 bg-accent text-black font-bold rounded-xl hover:bg-green-500 transition-all text-xs sm:text-sm uppercase tracking-wider text-center">
                                        Ikuti Event
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php if(count($eventBanners) > 1): ?>
                <!-- Left Arrow -->
                <button id="bannerPrev" class="absolute left-2 md:left-4 top-1/2 -translate-y-1/2 bg-black/60 hover:bg-accent text-white hover:text-black w-10 h-10 md:w-12 md:h-12 flex items-center justify-center rounded-full transition-all z-20 opacity-0 group-hover:opacity-100">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <!-- Right Arrow -->
                <button id="bannerNext" class="absolute right-2 md:right-4 top-1/2 -translate-y-1/2 bg-black/60 hover:bg-accent text-white hover:text-black w-10 h-10 md:w-12 md:h-12 flex items-center justify-center rounded-full transition-all z-20 opacity-0 group-hover:opacity-100">
                    <i class="fas fa-chevron-right"></i>
                </button>

                <!-- Dots -->
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-20">
                    <?php foreach ($eventBanners as $index => $banner): ?>
                        <button class="banner-dot w-2.5 h-2.5 rounded-full transition-all <?= $index === 0 ? 'bg-accent w-6' : 'bg-white/30' ?>" data-index="<?= $index ?>"></button>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <?php if(count($eventBanners) > 1): ?>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const container = document.getElementById('bannerSliderContainer');
                const totalSlides = <?= count($eventBanners) ?>;
                const dots = document.querySelectorAll('.banner-dot');
                
                let currentSlide = 0;
                let autoSlideInterval;

                function updateSlider() {
                    container.style.transform = `translateX(-${currentSlide * 100}%)`;
                    // Update dots
                    dots.forEach((dot, index) => {
                        if (index === currentSlide) {
                            dot.classList.remove('bg-white/30');
                            dot.classList.add('bg-accent', 'w-6');
                        } else {
                            dot.classList.remove('bg-accent', 'w-6');
                            dot.classList.add('bg-white/30');
                        }
                    });
                }

                function nextSlide() {
                    currentSlide = (currentSlide + 1) % totalSlides;
                    updateSlider();
                }

                function prevSlide() {
                    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                    updateSlider();
                }

                document.getElementById('bannerNext')?.addEventListener('click', () => {
                    nextSlide();
                    resetAutoSlide();
                });

                document.getElementById('bannerPrev')?.addEventListener('click', () => {
                    prevSlide();
                    resetAutoSlide();
                });

                dots.forEach((dot, index) => {
                    dot.addEventListener('click', () => {
                        currentSlide = index;
                        updateSlider();
                        resetAutoSlide();
                    });
                });

                function startAutoSlide() {
                    autoSlideInterval = setInterval(nextSlide, 3000);
                }

                function resetAutoSlide() {
                    clearInterval(autoSlideInterval);
                    startAutoSlide();
                }

                startAutoSlide();
            });
            </script>
            <?php endif; ?>

        <?php else: ?>
            <div class="text-center text-gray-500 py-10">Belum ada event saat ini.</div>
        <?php endif; ?>

    </div>
</section>
                            </div>

                        </div>

                    </div>

                </div>

            </div>



        </div>
    </div>
</section>

<style>
.event-card{
    background:#0A0A0A;
    padding:24px;
    border-radius:12px;
    border:1px solid #33e818;
    box-shadow:0 0 12px rgba(51,232,24,0.35);
    display:flex;
    flex-direction:column;
    gap:20px;
}

@media(min-width:768px){
    .event-card{
        flex-direction:row;
        align-items:center;
    }
}

.event-img img{
    width:100%;
    border-radius:10px;
}

@media(min-width:768px){
    .event-img{width:50%;}
    .event-content{width:50%;}
}

.event-content{
    text-align:center;
}

@media(min-width:768px){
    .event-content{
        text-align:left;
    }
}

.event-content h2{
    color:white;
    font-weight:800;
    font-size:clamp(22px,3vw,40px);
    line-height:1.2;
}

.date{
    color:#33e818;
    margin-top:6px;
    font-weight:600;
}

.desc{
    color:#9CA3AF;
    margin-top:10px;
}

.btn-wrap{
    margin-top:16px;
    display:flex;
    justify-content:center;
}

@media(min-width:768px){
    .btn-wrap{
        justify-content:flex-start;
    }
}

.btn-primary{
    width:100%;
    text-align:center;
    padding:10px;
    background:#33e818;
    color:black;
    border-radius:8px;
    font-weight:600;
}

@media(min-width:640px){
    .btn-primary{
        width:auto;
        padding:10px 24px;
    }
}

/* NAV */
.nav-btn{
    position:absolute;
    top:50%;
    transform:translateY(-50%);
    background:#33e818;
    padding:8px 12px;
    border-radius:999px;
}

.nav-btn.left{left:8px;}
.nav-btn.right{right:8px;}
</style>

<script>
let index = 0;

function slide(direction){
    const track = document.getElementById('sliderTrack');
    const total = track.children.length;

    index += direction;

    if(index < 0) index = total - 1;
    if(index >= total) index = 0;

    track.style.transform = `translateX(-${index * 100}%)`;
}
</script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("event-scroll");
    const activeCard = document.querySelector(".event-active");

    if (container && activeCard) {
        // Delay biar render selesai
        setTimeout(() => {
            const containerWidth = container.offsetWidth;
            const cardOffset = activeCard.offsetLeft;
            const cardWidth = activeCard.offsetWidth;

            // Center posisi card aktif
            const scrollPosition = cardOffset - (containerWidth / 2) + (cardWidth / 2);

            container.scrollTo({
                left: scrollPosition,
                behavior: "smooth"
            });
        }, 300);
    }
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        function updateTradingSessions() {
            // Get current time in WIB (UTC+7)
            const now = new Date();
            const utc = now.getTime() + (now.getTimezoneOffset() * 60000);
            const wib = new Date(utc + (3600000 * 7));

            // Update time display
            const timeStr = wib.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            const timeDisplayEl = document.getElementById('current-time-display');
            if (timeDisplayEl) timeDisplayEl.innerText = timeStr + ' WIB';

            const wibHour = wib.getHours();
            const wibMinute = wib.getMinutes();
            const currentTimeVal = wibHour + (wibMinute / 60);

            // Define sessions in WIB (Start, End) - Simplified for 24h cycle
            // Adjust ranges as needed for standard/DST
            const sessions = {
                'sydney': {
                    start: 5,
                    end: 14
                }, // 05:00 - 14:00
                'tokyo': {
                    start: 7,
                    end: 16
                }, // 07:00 - 16:00
                'london': {
                    start: 14,
                    end: 23
                }, // 14:00 - 23:00
                'newyork': {
                    start: 19,
                    end: 4
                } // 19:00 - 04:00 (next day)
            };

            for (const [key, range] of Object.entries(sessions)) {
                let isOpen = false;

                if (range.start < range.end) {
                    // Normal range (e.g., 07:00 - 16:00)
                    isOpen = currentTimeVal >= range.start && currentTimeVal < range.end;
                } else {
                    // Span midnight range (e.g., 19:00 - 04:00)
                    isOpen = currentTimeVal >= range.start || currentTimeVal < range.end;
                }

                const card = document.querySelector(`.trading-session-card[data-session="${key}"]`);
                if (card) {
                    const badge = card.querySelector('.status-badge');

                    if (isOpen) {
                        badge.className = 'status-badge inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-500/10 text-green-500 text-[10px] font-bold border border-green-500/20 uppercase tracking-wider scale-110 shadow-[0_0_10px_rgba(34,197,94,0.2)] transition-all duration-300';
                        badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span> OPEN';
                    } else {
                        badge.className = 'status-badge inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-red-500/10 text-red-500 text-[10px] font-bold border border-red-500/20 uppercase tracking-wider transition-all duration-300';
                        badge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> CLOSED';
                    }
                }
            }
        }

        setInterval(updateTradingSessions, 1000);
        updateTradingSessions();


    });
</script>
<?= $this->endSection() ?>