<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>

    <!-- Meta SEO & Open Graph -->
    <meta name="description"
        content="Invitation Only: Bergabunglah dengan Liv Trade Angel Gold. Event trading eksklusif bersama Wakil Penasihat Berjangka. 30 Januari 2026 di Panen Point, Bali.">
    <meta property="og:title" content="Invitation: Liv Trade Angel Gold">
    <meta property="og:description"
        content="Bergabunglah dengan komunitas trading elit. Akses sinyal premium dan strategi eksklusif. Live di Panen Point, Bali.">
    <meta property="og:image" content="<?= base_url('images/invitation.png') ?>">
    <meta property="og:url" content="<?= base_url('invitation') ?>">
    <meta property="og:type" content="website">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Invitation: Liv Trade Angel Gold">
    <meta name="twitter:description" content="Event trading eksklusif di Bali. Reserve your spot now.">
    <meta name="twitter:image" content="<?= base_url('images/invitation_meta_image.png') ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        accent: '#33e818',
                        'accent-dark': '#1a7a0c',
                        'black-bg': '#050505',
                        'card-bg': '#121212',
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    animation: {
                        'blur-wave': 'blur-wave 3s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        'blur-wave': {
                            '0%, 100%': { filter: 'blur(0px)', opacity: '1' },
                            '50%': { filter: 'blur(8px)', opacity: '0.6' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: #050505;
            color: white;
            overflow-x: hidden;
        }

        .bg-grid {
            background-size: 40px 40px;
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            mask-image: radial-gradient(circle at center, black 40%, transparent 100%);
            -webkit-mask-image: radial-gradient(circle at center, black 40%, transparent 100%);
        }

        .blur-text-container {
            display: inline-flex;
        }

        .blur-text-container span {
            display: inline-block;
            animation: blur-wave 3s ease-in-out infinite;
        }

        .blur-text-container span:nth-child(1) { animation-delay: 0s; }
        .blur-text-container span:nth-child(2) { animation-delay: 0.2s; }
        .blur-text-container span:nth-child(3) { animation-delay: 0.4s; }
        .blur-text-container span:nth-child(4) { animation-delay: 0.6s; }
        .blur-text-container span:nth-child(5) { animation-delay: 0.8s; }
        .blur-text-container span:nth-child(6) { animation-delay: 1.0s; }
        .blur-text-container span:nth-child(7) { animation-delay: 1.2s; }
        .blur-text-container span:nth-child(8) { animation-delay: 1.4s; }
        .blur-text-container span:nth-child(9) { animation-delay: 1.6s; }
        .blur-text-container span:nth-child(n+10) { animation-delay: 1.8s; }
    </style>
</head>

<body class="min-h-screen flex flex-col font-sans relative selection:bg-accent selection:text-black">

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center relative py-8 md:py-10 px-4">

        <!-- Background Elements -->
        <div class="absolute inset-0 z-0">
            <!-- Video Background -->
            <video autoplay muted loop playsinline class="w-full h-full object-cover opacity-60">
                <source src="<?= base_url('images/home.mp4') ?>" type="video/mp4">
            </video>
            <div class="absolute inset-0 bg-black/70"></div>
        </div>

        <!-- Grid Overlay -->
        <div class="absolute inset-0 bg-grid z-[1] pointer-events-none opacity-40"></div>

        <!-- Glow Effect -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[40vh] h-[40vh] md:w-[60vh] md:h-[60vh] bg-accent/20 blur-[80px] md:blur-[120px] rounded-full pointer-events-none z-[1]"></div>

        <!-- Content Container -->
        <div class="container mx-auto px-4 sm:px-6 relative z-10 text-center flex flex-col items-center max-w-4xl">

            <!-- Main Title -->
            <h1 class="font-black leading-none mb-6 tracking-tighter">
                <div class="text-3xl sm:text-4xl md:text-5xl text-white mb-3">ANGEL GOLD</div>
                <div class="blur-text-container block whitespace-nowrap" style="font-size: clamp(2rem, 8vw, 6rem);">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent via-green-400 to-emerald-500">I</span>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent via-green-400 to-emerald-500">N</span>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent via-green-400 to-emerald-500">V</span>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent via-green-400 to-emerald-500">I</span>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent via-green-400 to-emerald-500">T</span>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent via-green-400 to-emerald-500">A</span>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent via-green-400 to-emerald-500">T</span>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent via-green-400 to-emerald-500">I</span>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent via-green-400 to-emerald-500">O</span>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent via-green-400 to-emerald-500">N</span>
                </div>
            </h1>

            <!-- Subtitle -->
            <p class="text-sm sm:text-base md:text-lg text-gray-400 max-w-xl mx-auto mb-8 font-light leading-relaxed px-4">
                Event trading eksklusif bersama <span class="text-accent font-semibold"><br>Wakil Penasihat Berjangka</span>
            </p>

            <!-- Event Info Cards -->
            <div class="flex flex-col sm:flex-row gap-3 mb-8 w-full max-w-2xl px-4">
                <!-- Date Card -->
                <div class="flex-1 bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-4 text-center hover:border-accent/40 transition-colors">
                    <i class="far fa-calendar-alt text-accent text-2xl mb-2"></i>
                    <p class="text-white font-bold text-lg">30 Januari 2026</p>
                    <p class="text-accent text-sm">18:00 WITA</p>
                </div>

                <!-- Location Card -->
                <div class="flex-1 bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-4 text-center hover:border-accent/40 transition-colors cursor-pointer"
                    onclick="openMapModal()">
                    <i class="fas fa-map-marker-alt text-accent text-2xl mb-2"></i>
                    <p class="text-white font-bold text-lg">Panen Point</p>
                    <p class="text-accent text-sm">Bali, Indonesia</p>
                </div>
            </div>

            <!-- MQL5 Widget -->
            <div class="w-full max-w-sm mb-8 px-4">
                <div class="relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-accent via-green-400 to-accent rounded-lg blur opacity-30 group-hover:opacity-60 animate-pulse-slow"></div>
                    <div class="relative overflow-hidden rounded-lg border border-accent/30 shadow-2xl bg-black/60 backdrop-blur-md">
                        <div class="p-4">
                            <iframe frameborder="0" width="100%" height="100"
                                src="https://www.mql5.com/en/signals/widget/signal/79mv?t=green"
                                class="w-full scale-90"
                                style="min-height: 100px; transform: scaleY(0.8);">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Button -->
            <a href="<?= base_url('checkout/layanan/angel-trial?ref=ardiansyah&voucher=ANGELINVITATION') ?>"
                class="group relative px-10 py-4 bg-accent hover:bg-white text-black font-bold text-lg rounded-full transition-all duration-300 shadow-lg shadow-accent/20 mb-8">
                <span class="relative z-10">ACCEPT INVITATION</span>
            </a>

            <!-- Countdown -->
            <div class="flex items-center gap-3 text-center mb-8">
                <div class="bg-white/5 border border-white/10 rounded-lg p-3 w-16 backdrop-blur-sm">
                    <div id="days" class="text-2xl font-bold text-white">00</div>
                    <div class="text-[9px] text-gray-500 uppercase font-bold">Days</div>
                </div>
                <div class="text-accent text-xl font-bold">:</div>
                <div class="bg-white/5 border border-white/10 rounded-lg p-3 w-16 backdrop-blur-sm">
                    <div id="hours" class="text-2xl font-bold text-white">00</div>
                    <div class="text-[9px] text-gray-500 uppercase font-bold">Hours</div>
                </div>
                <div class="text-accent text-xl font-bold">:</div>
                <div class="bg-white/5 border border-white/10 rounded-lg p-3 w-16 backdrop-blur-sm">
                    <div id="minutes" class="text-2xl font-bold text-white">00</div>
                    <div class="text-[9px] text-gray-500 uppercase font-bold">Mins</div>
                </div>
                <div class="text-accent text-xl font-bold">:</div>
                <div class="bg-white/5 border border-white/10 rounded-lg p-3 w-16 backdrop-blur-sm">
                    <div id="seconds" class="text-2xl font-bold text-white">00</div>
                    <div class="text-[9px] text-gray-500 uppercase font-bold">Secs</div>
                </div>
            </div>

            <!-- Total Users Section -->
            <div class="flex flex-col items-center justify-center gap-2">
                <div class="flex items-center gap-4 px-6 py-3 bg-white/5 backdrop-blur-md rounded-full border border-white/10 shadow-lg hover:bg-white/10 transition duration-300">
                    <div class="flex -space-x-3">
                        <!-- Badge Avatars (Dynamic) -->
                        <?php for($i=0; $i<3; $i++): ?>
                            <div class="w-10 h-10 rounded-full border-2 border-black flex items-center justify-center text-sm text-white font-bold transition-all duration-500" id="badge-initial-<?= $i ?>" style="background: linear-gradient(135deg, #33e818 0%, #1a7a0c 100%);"></div>
                        <?php endfor; ?>
                    </div>
                    <div class="flex flex-col items-start justify-center">
                        <p class="text-[10px] uppercase tracking-wider text-gray-400 font-bold mb-0.5">Total Users</p>
                        <p class="text-2xl text-accent font-bold leading-none">
                            <?= number_format($totalUsers) ?>+
                        </p>
                    </div>
                </div>
                
                <!-- Animated New User Toast -->
                <div id="user-toast" class="h-8 flex items-center gap-2 transition-all duration-500 opacity-0 transform translate-y-2 mt-2">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    <p class="text-xs text-gray-300">
                        <span id="toast-name" class="font-bold text-white text-sm">User</span> <span class="opacity-70" id="toast-status">baru bergabung</span>
                    </p>
                </div>
            </div>

        </div>
    </main>

    <!-- Map Modal -->
    <div id="mapModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div class="relative w-full max-w-4xl bg-[#111] border border-accent/30 rounded-2xl overflow-hidden shadow-2xl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-4 border-b border-white/10 bg-gradient-to-r from-accent/20 via-accent/10 to-transparent">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-accent/20 flex items-center justify-center">
                        <i class="fas fa-map-marker-alt text-accent"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Lokasi Event</h3>
                        <p class="text-xs text-gray-400">Panen Point, Bali</p>
                    </div>
                </div>
                <button onclick="closeMapModal()" class="w-10 h-10 rounded-full bg-white/5 hover:bg-red-500/20 text-gray-400 hover:text-red-400 transition-colors flex items-center justify-center">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Map Container -->
            <div class="relative w-full h-[60vh] md:h-[70vh]">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3944.1096567037425!2d115.19679487501466!3d-8.681121291366953!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd241001ef61f1d%3A0xf6a7067d9a4c1c56!2sPANEN%20POINT!5e0!3m2!1sid!2sid!4v1769073131764!5m2!1sid!2sid" 
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade"
                    class="w-full h-full">
                </iframe>
            </div>
            
            <!-- Modal Footer -->
            <div class="p-4 border-t border-white/10 bg-black/40">
                <a href="https://maps.app.goo.gl/your-google-maps-link" target="_blank" 
                   class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-accent hover:bg-white text-black font-bold rounded-xl transition-colors">
                    <i class="fas fa-directions"></i>
                    Buka di Google Maps
                </a>
            </div>
        </div>
    </div>

    <script>
        function openMapModal() {
            document.getElementById('mapModal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeMapModal() {
            document.getElementById('mapModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeMapModal();
        });

        document.getElementById('mapModal').addEventListener('click', function(e) {
            if (e.target === this) closeMapModal();
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Countdown Timer
            const countDownDate = new Date("2026-01-30T18:00:00+08:00").getTime();

            const x = setInterval(function () {
                const now = new Date().getTime();
                const distance = countDownDate - now;

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                const elDays = document.getElementById("days");
                const elHours = document.getElementById("hours");
                const elMinutes = document.getElementById("minutes");
                const elSeconds = document.getElementById("seconds");

                if (elDays) elDays.innerText = days < 10 ? "0" + days : days;
                if (elHours) elHours.innerText = hours < 10 ? "0" + hours : hours;
                if (elMinutes) elMinutes.innerText = minutes < 10 ? "0" + minutes : minutes;
                if (elSeconds) elSeconds.innerText = seconds < 10 ? "0" + seconds : seconds;

                if (distance < 0) {
                    clearInterval(x);
                    if (elDays) elDays.innerHTML = "00";
                    if (elHours) elHours.innerHTML = "00";
                    if (elMinutes) elMinutes.innerHTML = "00";
                    if (elSeconds) elSeconds.innerHTML = "00";
                }
            }, 1000);

            // User Stats Logic - Using PHP data
            let recentUsers = <?= json_encode(array_map(function($u) {
                return [
                    'name' => $u['name'],
                    'isNew' => false
                ];
            }, $recentUsers)) ?>;
            
            let currentTotalInfo = <?= $totalUsers ?>;
            
            const toastEl = document.getElementById('user-toast');
            const nameEl = document.getElementById('toast-name');
            const statusEl = document.getElementById('toast-status');
            
            let index = 0;
            let loopTimeout;

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
                for(let i=0; i<3; i++) {
                    const userIndex = (currentIndex + i) % recentUsers.length;
                    const user = recentUsers[userIndex];
                    const initialEl = document.getElementById(`badge-initial-${i}`);
                    
                    if(initialEl && user) {
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
                if (recentUsers.length === 0) return;
                
                const user = recentUsers[index];
                
                toastEl.classList.remove('opacity-100', 'translate-y-0');
                toastEl.classList.add('opacity-0', 'translate-y-2');

                setTimeout(() => {
                    nameEl.textContent = user.name;
                    
                    if (user.isNew) {
                        statusEl.textContent = 'baru bergabung';
                        statusEl.classList.add('text-green-400', 'font-bold', 'opacity-100');
                        statusEl.classList.remove('opacity-70');
                        user.isNew = false; 
                    } else {
                        statusEl.textContent = 'sudah bergabung';
                        statusEl.classList.remove('text-green-400', 'font-bold', 'opacity-100');
                        statusEl.classList.add('opacity-70');
                    }
                    
                    updateBadgeAvatars(index);
                    index = (index + 1) % recentUsers.length;

                    toastEl.classList.remove('opacity-0', 'translate-y-2');
                    toastEl.classList.add('opacity-100', 'translate-y-0');
                }, 500);

                loopTimeout = setTimeout(showNextUser, 3000);
            }

            function checkForUpdates() {
                fetch('<?= base_url('home/stats') ?>')
                    .then(res => res.json())
                    .then(data => {
                        if (data.total > currentTotalInfo) {
                            currentTotalInfo = data.total;
                            
                            const totalCountEl = document.querySelector('.text-2xl.text-accent');
                            if(totalCountEl) totalCountEl.innerText = new Intl.NumberFormat().format(data.total) + '+';
                            
                            const newUser = {
                                name: data.latest.name,
                                isNew: true
                            };
                            
                            recentUsers.unshift(newUser);
                            index = 0;
                        }
                    })
                    .catch(err => console.error('Error fetching stats:', err));
            }

            if (recentUsers.length > 0) {
                updateBadgeAvatars(0);
                setTimeout(() => showNextUser(), 1000);
                setInterval(checkForUpdates, 5000);
            }
        });
    </script>
</body>

</html>
