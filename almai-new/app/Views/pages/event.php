<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Custom Styles -->
<style>
    .no-scrollbar::-webkit-scrollbar {
        display: none;
    }

    .no-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0.5rem;
    }

    .calendar-day {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        border-radius: 0.5rem;
        color: #9ca3af;
        cursor: pointer;
        transition: all 0.2s;
    }

    .calendar-day:hover {
        background-color: rgba(255, 255, 255, 0.1);
        color: white;
    }

    .calendar-day.active {
        background-color: #33E818;
        color: black;
        font-weight: bold;
        box-shadow: 0 0 15px rgba(51, 232, 24, 0.4);
    }
</style>

<!-- Main Container -->
<div class="min-h-screen bg-black text-white font-sans pb-20">

    <!-- Header Section -->
    <div class="max-w-7xl mx-auto px-4 pt-24 pb-8 relative z-10">

        <!-- Hero Carousel -->
        <div class="relative w-full h-[300px] md:h-[450px] rounded-3xl overflow-hidden mb-12 group shadow-2xl shadow-[#33E818]/10 border border-white/5 bg-[#0a0a0a]">
            <div id="heroCarousel" class="h-full w-full relative">
                <!-- Slide 1 -->
                <div class="absolute inset-0 transition-all duration-1000 ease-in-out opacity-100 visible slide active" data-index="0">
                    <img src="https://images.unsplash.com/photo-1639762681485-074b7f938ba0?q=80&w=2832&auto=format&fit=crop" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-r from-black via-black/60 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-6 md:p-12 max-w-2xl">
                        <span class="px-3 py-1 bg-[#33E818] text-black text-xs font-bold rounded mb-4 inline-block uppercase tracking-wider">Featured Event</span>
                        <h2 class="text-2xl md:text-5xl font-black text-[#33E818] mb-3 leading-tight">Crypto Insight 2026</h2>
                        <p class="text-gray-300 text-sm md:text-lg mb-6 line-clamp-2 md:line-clamp-none">Konferensi kripto terbesar tahun ini. Temukan strategi trading terbaik.</p>
                        <a href="<?= base_url('event/detail/crypto-insight-2026') ?>" class="px-6 py-3 bg-[#33E818] text-black font-bold rounded-lg hover:bg-white hover:text-black transition inline-flex items-center gap-2 transform active:scale-95">
                            Daftar Sekarang <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="absolute inset-0 transition-opacity duration-1000 opacity-0 invisible slide" data-index="1">
                    <img src="https://images.unsplash.com/photo-1642543492481-44e81e3914a7?q=80&w=2832&auto=format&fit=crop" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-r from-black via-black/60 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-6 md:p-12 max-w-2xl">
                        <span class="px-3 py-1 bg-blue-500 text-white text-xs font-bold rounded mb-4 inline-block uppercase tracking-wider">New Workshop</span>
                        <h2 class="text-2xl md:text-5xl font-black text-[#33E818] mb-3 leading-tight">Algorithmic Trading</h2>
                        <p class="text-gray-300 text-sm md:text-lg mb-6 line-clamp-2 md:line-clamp-none">Buat robot trading otomatis yang profitable tanpa pusing pantau chart.</p>
                        <a href="#" class="px-6 py-3 bg-white text-black font-bold rounded-lg hover:bg-blue-500 hover:text-white transition inline-flex items-center gap-2">
                            Lihat Detail <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="absolute inset-0 transition-opacity duration-1000 opacity-0 invisible slide" data-index="2">
                    <img src="https://images.unsplash.com/photo-1590283603385-17ffb3a7f29f?q=80&w=2832&auto=format&fit=crop" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-r from-black via-black/60 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-6 md:p-12 max-w-2xl">
                        <span class="px-3 py-1 bg-yellow-500 text-black text-xs font-bold rounded mb-4 inline-block uppercase tracking-wider">Trending</span>
                        <h2 class="text-2xl md:text-5xl font-black text-[#33E818] mb-3 leading-tight">Gold Trading Mastery</h2>
                        <p class="text-gray-300 text-sm md:text-lg mb-6 line-clamp-2 md:line-clamp-none">Rahasia trading emas dengan win-rate di atas 80%.</p>
                        <a href="#" class="px-6 py-3 bg-white text-black font-bold rounded-lg hover:bg-yellow-500 transition inline-flex items-center gap-2">
                            Ikuti Kelas <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Navigation Controls -->
            <button onclick="prevSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/50 hover:bg-[#33E818] hover:text-black text-white flex items-center justify-center transition border border-white/10 z-20">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button onclick="nextSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-black/50 hover:bg-[#33E818] hover:text-black text-white flex items-center justify-center transition border border-white/10 z-20">
                <i class="fas fa-chevron-right"></i>
            </button>

            <!-- Indicators -->
            <div class="absolute bottom-6 right-8 flex gap-2 z-20">
                <button onclick="goToSlide(0)" class="w-8 h-1 rounded-full bg-[#33E818] transition-all indicator" data-index="0"></button>
                <button onclick="goToSlide(1)" class="w-2 h-1 rounded-full bg-white/50 hover:bg-white transition-all indicator" data-index="1"></button>
                <button onclick="goToSlide(2)" class="w-2 h-1 rounded-full bg-white/50 hover:bg-white transition-all indicator" data-index="2"></button>
            </div>
        </div>

        <div class="text-center">
            <h1 class="text-3xl md:text-5xl font-black text-white mb-4">
                Jelajahi <span class="text-[#33E818]">Event</span>
            </h1>
            <p class="text-gray-400 text-sm md:text-base max-w-2xl mx-auto mb-10">
                Jelajahi event populer di sekitarmu, temukan kategori favoritmu, dan ikuti workshop menarik yang sedang tren.
            </p>

            <!-- Search Bar -->
            <form action="<?= base_url('event') ?>" method="get" class="max-w-2xl mx-auto mb-10 relative group">
                <div class="absolute left-6 top-1/2 -translate-y-1/2 pointer-events-none">
                    <i class="fas fa-search text-gray-500 text-lg group-focus-within:text-[#33E818] transition"></i>
                </div>
                <!-- Preserve existing filters -->
                <?php if ($selectedCategory): ?>
                    <input type="hidden" name="category" value="<?= esc($selectedCategory) ?>">
                <?php endif; ?>

                <input type="text" name="q" value="<?= esc($searchQuery) ?>"
                    class="w-full bg-[#111] border border-white/10 text-white text-sm rounded-full py-3.5 pl-14 pr-6 focus:outline-none focus:border-[#33E818] focus:ring-1 focus:ring-[#33E818] transition placeholder-gray-600 shadow-lg shadow-black/50"
                    placeholder="Cari event, topik, atau pembicara...">
            </form>

            <!-- Category Pills -->
            <div class="flex flex-wrap justify-center gap-3">
                <a href="<?= base_url('event') ?>"
                    class="px-6 py-2 rounded-[4px] font-bold text-xs md:text-sm hover:opacity-90 transition shadow-[0_0_15px_rgba(51,232,24,0.3)] <?= (!$selectedCategory || $selectedCategory === 'Semua') ? 'bg-[#33E818] text-black' : 'bg-[#1A1A1A] border border-white/10 text-gray-400 hover:text-white' ?>"
                    style="<?= (!$selectedCategory || $selectedCategory === 'Semua') ? 'background-color: #33E818 !important; color: #000000 !important;' : '' ?>">
                    Semua
                </a>
                <?php
                $categories = ['Gold', 'Saham', 'Kripto', 'Forex', 'Commodity', 'Index', 'Algorithm', 'Technical Analysis', 'Risk Management'];
                foreach ($categories as $cat):
                    $isActive = ($selectedCategory === $cat);
                ?>
                    <a href="<?= base_url('event') ?>?category=<?= urlencode($cat) ?><?= $searchQuery ? '&q=' . urlencode($searchQuery) : '' ?>"
                        class="px-6 py-2 rounded-[4px] font-bold text-xs md:text-sm transition <?= $isActive ? 'bg-[#33E818] text-black shadow-[0_0_15px_rgba(51,232,24,0.3)]' : 'bg-[#1A1A1A] border border-white/10 text-gray-400 hover:text-white hover:border-[#33E818]/50 hover:bg-white/5' ?>"
                        style="<?= $isActive ? 'background-color: #33E818 !important; color: #000000 !important;' : '' ?>">
                        <?= $cat ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Main Content Layout -->
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Left Column: Upcoming Events List -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-white/5">
                        <h2 class="text-xl font-bold text-white">Event Upcoming</h2>
                        <a href="<?= base_url('event') ?>" class="text-[#33E818] text-xs font-bold hover:underline flex items-center gap-1">
                            Lihat Semua <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <!-- Event Cards Loop -->
                    <?php foreach ($events as $event): ?>
                        <a href="<?= base_url('event/detail/' . $event['slug']) ?>" class="block group">
                            <div class="bg-[#0f0f0f] border border-white/5 rounded-2xl p-4 md:p-5 hover:border-[#33E818]/50 transition relative overflow-hidden">

                                <!-- Hover Glow Effect -->
                                <div class="absolute inset-0 bg-gradient-to-r from-[#33E818]/0 via-[#33E818]/5 to-[#33E818]/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></div>

                                <div class="flex flex-col md:flex-row gap-5 relative z-10">
                                    <!-- Image -->
                                    <div class="w-full md:w-32 md:h-32 flex-shrink-0 rounded-xl overflow-hidden relative border border-white/5">
                                        <img src="<?= $event['image'] ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                        <!-- Category Badge on Image -->
                                        <div class="absolute top-2 left-2 px-2 py-1 bg-black/80 backdrop-blur-md rounded-md border border-white/10">
                                            <div class="flex items-center gap-1.5">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#33E818] animate-pulse"></span>
                                                <span class="text-[9px] font-bold text-white uppercase tracking-wider"><?= $event['category'] ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 flex flex-col justify-between">
                                        <div>
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="flex gap-2">
                                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-[#1ca1f2]/10 text-[#1ca1f2] border border-[#1ca1f2]/20">
                                                        Upcoming Event
                                                    </span>
                                                    <?php if ($event['id'] == 1 || $event['id'] == 2): ?>
                                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-red-500/10 text-red-500 border border-red-500/20">
                                                            Full Booked
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                                <span class="text-xs text-gray-400 font-mono text-[#33E818]"><?= $event['short_date'] ?> • <?= substr($event['time'], 0, 5) ?> WITA</span>
                                            </div>

                                            <h3 class="text-lg font-bold !text-[#33E818] leading-tight mb-2 group-hover:!text-white transition">
                                                <?= esc($event['title']) ?>
                                            </h3>

                                            <div class="flex items-center gap-4 text-xs text-gray-500 mb-2">
                                                <span class="flex items-center gap-1.5">
                                                    <i class="fas fa-user-circle text-gray-400"></i> <?= esc($event['hosted_by']) ?>
                                                </span>
                                                <span class="flex items-center gap-1.5">
                                                    <i class="fas fa-map-marker-alt text-gray-400"></i> <?= esc($event['city']) ?>
                                                </span>
                                                <span class="flex items-center gap-1.5">
                                                    <i class="fas fa-users text-gray-400"></i> <?= $event['participants'] ?? '0/0' ?> Peserta
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Footer Stats -->
                                        <div class="flex items-center justify-end gap-4 text-[10px] text-gray-600 border-t border-white/5 pt-3 mt-1 group-hover:text-gray-400 transition">
                                            <span class="flex items-center gap-1"><i class="fas fa-eye"></i> <?= $event['views'] ?? '0' ?></span>
                                            <span class="flex items-center gap-1"><i class="fas fa-share-alt"></i> <?= $event['shares'] ?? '0' ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>

                </div>

                <!-- Right Column: Calendar & Sidebar -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Tabs -->
                    <div class="flex border-b border-white/10 mb-4">
                        <button class="flex-1 pb-3 text-sm font-bold text-[#33E818] border-b-2 border-[#33E818]">Kalender & Jadwal</button>
                        <button class="flex-1 pb-3 text-sm font-bold text-gray-500 hover:text-white transition">Event Upcoming</button>
                    </div>

                    <!-- Calendar Widget -->
                    <div class="bg-[#0f0f0f] border border-white/5 rounded-2xl p-6 relative">
                        <!-- Month Nav -->
                        <div class="flex items-center justify-between mb-6">
                            <button class="w-8 h-8 rounded-full bg-white/5 hover:bg-white/10 flex items-center justify-center text-gray-400 hover:text-white transition">
                                <i class="fas fa-chevron-left text-xs"></i>
                            </button>
                            <span class="text-white font-bold">Februari 2026</span>
                            <button class="w-8 h-8 rounded-full bg-white/5 hover:bg-white/10 flex items-center justify-center text-gray-400 hover:text-white transition">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </button>
                        </div>

                        <!-- Days Header -->
                        <div class="calendar-grid mb-2">
                            <?php foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $d): ?>
                                <div class="text-center text-[10px] font-bold text-gray-500 uppercase"><?= $d ?></div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Days Grid -->
                        <div class="calendar-grid" id="calendarDays">
                            <!-- Dates (Prev Month) -->
                            <?php for ($i = 26; $i <= 31; $i++): ?>
                                <div class="calendar-day text-white/10"><?= $i ?></div>
                            <?php endfor; ?>

                            <!-- Current Month Dummy -->
                            <?php
                            $currentYearMonth = date('Y-m'); // Should ideally match the calendar month view
                            // Hardcoded for demo to match "Januari 2026"
                            $calendarYearMonth = '2026-02';

                            for ($i = 1; $i <= 31; $i++):
                                $dayString = sprintf('%02d', $i);
                                $dateString = $calendarYearMonth . '-' . $dayString;

                                // Check if any event falls on this date
                                $hasEvent = false;
                                // In a real app pass $eventsByDate to avoid loop inside loop

                                // Highlight logic from before (24, 27)
                                $isHighlight = in_array($i, [24, 27]);

                                $isSelected = ($selectedDate === $dateString);
                            ?>
                                <a href="<?= base_url('event') ?>?date=<?= $dateString ?>"
                                    class="calendar-day <?= ($isHighlight || $isSelected) ? 'active flex flex-col items-center justify-center leading-none gap-0.5' : '' ?> <?= $isSelected ? '!bg-white !text-black' : '' ?>">
                                    <span><?= $i ?></span>
                                    <?php if ($isHighlight): ?>
                                        <span class="text-[7px] font-black uppercase tracking-tight">Event</span>
                                    <?php endif; ?>
                                </a>
                            <?php endfor; ?>

                            <!-- Next Day -->
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <div class="calendar-day text-white/10"><?= $i ?></div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Section: Jelajahi Event Lainnya -->
            <div class="mt-20">
                <h2 class="text-xl font-bold text-white mb-8 border-l-4 border-[#33E818] pl-4">Jelajahi Event Lainnya</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-12">

                    <!-- Helper to prevent code duplication -->
                    <?php
                    $sections = [
                        'Kripto' => array_slice($events, 0, 4),
                        'Saham' => array_slice($events, 1, 4),
                        'Gold' => array_slice($events, 0, 4),
                        'Index' => array_slice($events, 1, 4)
                    ];

                    foreach ($sections as $title => $subEvents):
                    ?>
                        <!-- Section: <?= $title ?> -->
                        <div>
                            <div class="flex justify-between items-center mb-6 pb-2 border-b border-white/5">
                                <h3 class="font-bold text-lg text-white"><?= $title ?></h3>
                                <a href="#" class="text-[#33E818] text-[10px] font-bold hover:underline uppercase tracking-wider">Lihat Semua Event</a>
                            </div>
                            <div class="space-y-4">
                                <?php foreach ($subEvents as $subEvent): ?>
                                    <a href="<?= base_url('event/detail/' . $subEvent['slug']) ?>" class="group block">
                                        <div class="bg-[#0f0f0f] p-3 rounded-lg flex gap-4 border border-white/5 hover:border-[#33E818]/30 transition hover:bg-white/5">
                                            <img src="<?= $subEvent['image'] ?>" class="w-16 h-16 rounded md:w-20 md:h-20 object-cover bg-gray-800 transition">
                                            <div class="flex-1 min-w-0">
                                                <span class="text-[9px] font-bold text-[#33E818] block mb-1 uppercase tracking-wide">
                                                    <?= $title ?> Event
                                                </span>
                                                <h4 class="text-sm font-bold !text-[#33E818] group-hover:!text-white transition leading-tight mb-1 truncate">
                                                    <?= $subEvent['title'] ?>
                                                </h4>
                                                <p class="text-[10px] text-gray-500 mb-2 truncate">
                                                    <?= $subEvent['hosted_by'] ?> • <?= $subEvent['short_date'] ?>
                                                </p>
                                                <div class="flex items-center gap-3 text-[9px] text-gray-600">
                                                    <span><i class="fas fa-eye"></i> <?= $subEvent['views'] ?? 0 ?></span>
                                                    <span><i class="fas fa-share-alt"></i> <?= $subEvent['shares'] ?? 0 ?></span>
                                                    <span class="ml-auto text-white font-bold">
                                                        <?= $subEvent['participants'] ?? '0' ?>/200
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>

        </div>
    </div>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const indicators = document.querySelectorAll('.indicator');
        const totalSlides = slides.length;
        let slideInterval;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                if (i === index) {
                    slide.classList.remove('opacity-0');
                    slide.classList.add('opacity-100', 'active', 'scale-100');
                } else {
                    slide.classList.remove('opacity-100', 'active', 'scale-100');
                    slide.classList.add('opacity-0');
                }
            });

            indicators.forEach((indicator, i) => {
                if (i === index) {
                    indicator.classList.remove('bg-white/50', 'w-2');
                    indicator.classList.add('bg-[#33E818]', 'w-8');
                } else {
                    indicator.classList.remove('bg-[#33E818]', 'w-8');
                    indicator.classList.add('bg-white/50', 'w-2');
                }
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            showSlide(currentSlide);
        }

        function goToSlide(index) {
            currentSlide = index;
            showSlide(currentSlide);
            resetInterval();
        }

        function resetInterval() {
            clearInterval(slideInterval);
            slideInterval = setInterval(nextSlide, 5000);
        }

        // Auto slide
        resetInterval();

        // Pause on hover
        const carousel = document.getElementById('heroCarousel');
        carousel.addEventListener('mouseenter', () => clearInterval(slideInterval));
        carousel.addEventListener('mouseleave', resetInterval);
    </script>

    <?= $this->endSection() ?>