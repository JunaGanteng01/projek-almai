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
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        border-radius: 0.35rem;
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
        box-shadow: 0 0 10px rgba(51, 232, 24, 0.4);
    }

    @media (min-width: 640px) {
        .calendar-day {
            font-size: 0.8rem;
            border-radius: 0.5rem;
        }

        .calendar-day.active {
            box-shadow: 0 0 15px rgba(51, 232, 24, 0.4);
        }
    }
</style>

<!-- Main Container -->
<div class="min-h-screen bg-black text-white font-sans pb-20">

    <!-- Header Section -->
    <div class="max-w-7xl mx-auto px-4 pt-24 pb-8 relative z-10">

        <?php if (!empty($banners)): ?>
            <!-- Hero Carousel -->
            <style>
                .custom-banner-height {
                    height: 220px;
                }

                @media (min-width: 768px) {
                    .custom-banner-height {
                        height: 450px;
                    }
                }
            </style>
            <div class="custom-banner-height relative w-full rounded-3xl overflow-hidden mb-12 group shadow-2xl shadow-[#33E818]/10 border border-white/5 bg-[#0a0a0a]">
                <div id="heroCarousel" class="h-full w-full relative">
                    <?php foreach ($banners as $index => $banner): ?>
                        <!-- Slide <?= $index + 1 ?> -->
                        <div class="absolute inset-0 transition-all duration-1000 ease-in-out <?= $index === 0 ? 'opacity-100 visible slide active' : 'opacity-0 invisible slide' ?>" data-index="<?= $index ?>">
                            <?php if (!empty($banner['url'])): 
                                $bUrl = trim($banner['url']);
                                if (strpos($bUrl, 'http') === 0) {
                                    $finalUrl = $bUrl;
                                    $target = '_blank';
                                } elseif (strpos($bUrl, 'www.') === 0) {
                                    $finalUrl = 'https://' . $bUrl;
                                    $target = '_blank';
                                } else {
                                    $finalUrl = base_url($bUrl);
                                    $target = '_self';
                                }
                            ?>
                                <a href="<?= $finalUrl ?>" class="absolute inset-0 z-10" target="<?= $target ?>"></a>
                            <?php endif; ?>

                            <img src="<?= base_url($banner['image']) ?>" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-r from-black via-black/60 to-transparent"></div>

                            <?php if (!empty($banner['title']) || !empty($banner['content'])): ?>
                                <div class="absolute bottom-0 left-0 p-4 md:p-12 max-w-2xl z-20 pointer-events-none">
                                    <?php if (!empty($banner['title'])): ?>
                                        <h2 class="text-2xl md:text-5xl font-black text-[#33E818] mb-3 leading-tight"><?= esc($banner['title']) ?></h2>
                                    <?php endif; ?>

                                    <?php if (!empty($banner['content'])): ?>
                                        <p class="text-gray-300 text-sm md:text-lg mb-3 line-clamp-2 md:line-clamp-none"><?= esc($banner['content']) ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
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
                    <?php foreach ($banners as $index => $banner): ?>
                        <button onclick="goToSlide(<?= $index ?>)" class="<?= $index === 0 ? 'w-8 h-1 bg-[#33E818]' : 'w-2 h-1 bg-white/50' ?> rounded-full transition-all indicator" data-index="<?= $index ?>"></button>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- JELAJAHI EVENT HEADER & SEARCH -->
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-5xl font-black text-white mb-4">
                Jelajahi <span class="text-[#33E818]">Event</span>
            </h1>
            <p class="text-gray-400 text-sm md:text-base max-w-2xl mx-auto mb-10">
                Jelajahi event populer di sekitarmu, temukan kategori favoritmu, dan ikuti workshop menarik yang sedang tren.
            </p>

            <!-- Search Bar -->
            <form action="<?= base_url('event') ?>" method="get" class="max-w-2xl mx-auto relative group">
                <div class="absolute left-6 top-1/2 -translate-y-1/2 pointer-events-none">
                    <i class="fas fa-search text-gray-500 text-lg group-focus-within:text-[#33E818] transition"></i>
                </div>
                <?php if ($selectedCategory): ?>
                    <input type="hidden" name="category" value="<?= esc($selectedCategory) ?>">
                <?php endif; ?>
                <input type="text" name="q" id="searchInput" value="<?= esc($searchQuery) ?>" autocomplete="off"
                    class="w-full bg-[#111] border border-white/10 text-white text-sm rounded-full py-3.5 pl-14 pr-6 focus:outline-none focus:border-[#33E818] focus:ring-1 focus:ring-[#33E818] transition placeholder-gray-600 shadow-lg shadow-black/50"
                    placeholder="Cari event">

                <!-- Search Suggestions Dropdown -->
                <div id="searchResults" class="hidden absolute top-full left-0 right-0 mt-2 bg-[#111] border border-white/10 rounded-2xl shadow-2xl z-50 overflow-hidden max-h-[400px] overflow-y-auto">
                    <!-- Results will be injected here -->
                </div>
            </form>
        </div>

        <!-- Advanced Filter Section -->
        <div class="mb-10">


            <!-- Category Pills -->
            <div class="flex flex-wrap gap-2 justify-center items-center">
                <span class="text-white font-bold text-xs">Kategori :</span>
                <a href="<?= base_url('event') ?>"
                    class="px-4 py-1 rounded-full border text-xs font-medium transition <?= !$selectedCategory ? 'bg-accent text-black border-accent' : 'border-white/20 text-white hover:border-accent hover:text-accent' ?>">
                    Semua Kategori
                </a>
                <?php
                $categories = ['Gold', 'Forex', 'Crypto', 'Stock', 'Index', 'EA', 'AI', 'Propfirm', 'Algorithm', 'Technical Analysis', 'Risk Management'];
                foreach ($categories as $cat):
                    $isActive = ($selectedCategory === $cat);
                ?>
                    <a href="<?= base_url('event') ?>?category=<?= urlencode($cat) ?>"
                        class="px-4 py-1 rounded-full border text-xs font-medium transition <?= $isActive ? 'bg-accent text-black border-accent' : 'border-white/20 text-white hover:border-accent hover:text-accent' ?>">
                        <?= $cat ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Main Content Layout -->
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- Left Column: Upcoming Events List -->
                <div class="space-y-6 order-1 w-full lg:pr-4 lg:border-r border-white/5">
                    <div class="flex items-center justify-between pb-4 border-b border-white/5">
                        <h2 class="text-xl font-bold text-white">Event Yang Akan Datang</h2>
                    </div>

                    <!-- Event Cards Loop -->
                    <?php 
                    $uniqueUpcomingEvents = [];
                    $seenUpcomingIds = [];
                    
                    // Semua event mendatang, termasuk Event Gratis dari Absensi Kegiatan.
                    foreach ($events as $evt) {
                        if (!in_array($evt['id'], $seenUpcomingIds, true)) {
                            $uniqueUpcomingEvents[] = $evt;
                            $seenUpcomingIds[] = $evt['id'];
                        }
                    }
                    
                    // Simple Pagination Logic
                    $perPage = 5;
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    if ($page < 1) $page = 1;
                    $totalItems = count($uniqueUpcomingEvents);
                    $totalPages = ceil($totalItems / $perPage);
                    $paginatedEvents = array_slice($uniqueUpcomingEvents, ($page - 1) * $perPage, $perPage);

                    foreach ($paginatedEvents as $event): 
                    ?>
                        <?php $eventUrl = !empty($event['attendance_code'])
                            ? base_url('absensi/checkin/' . rawurlencode($event['attendance_code']))
                            : base_url('event/' . $event['slug']); ?>
                        <a href="<?= esc($eventUrl, 'attr') ?>" class="block group">
                            <div class="bg-[#0f0f0f] border border-white/5 rounded-xl p-4 flex gap-6 hover:border-accent/50 transition relative overflow-hidden">

                                <!-- Hover Glow Effect -->
                                <div class="absolute inset-0 bg-gradient-to-r from-accent/0 via-accent/5 to-accent/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></div>

                                <!-- Image -->
                                <div class="w-20 h-20 flex-shrink-0 rounded-lg overflow-hidden relative border border-white/5">
                                    <img src="<?= $event['image'] ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                </div>

                                <!-- Content -->
                                <div class="flex-1 flex flex-col justify-between relative z-10">
                                    <!-- Top: Badges and Date -->
                                    <div>
                                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                                            <span class="inline-block px-2 py-0.5 rounded text-[9px] font-bold bg-blue-500/20 text-blue-400 border border-blue-500/30">
                                                Upcoming Event
                                            </span>
                                            <?php if (isset($event['is_paid']) && (int) $event['is_paid'] === 0): ?>
                                                <span class="inline-block px-2 py-0.5 rounded text-[9px] font-bold bg-accent/20 text-accent border border-accent/30">
                                                    Gratis
                                                </span>
                                            <?php endif; ?>
                                            <span class="inline-block px-2 py-0.5 rounded text-[9px] font-bold bg-accent/20 text-accent border border-accent/30">
                                                <?= $event['category'] ?>
                                            </span>
                                            <?php if (\App\Services\AttendanceAccessService::isRestricted($event)): ?>
                                                <span class="inline-block px-2 py-0.5 rounded text-[9px] font-bold bg-purple-500/20 text-purple-300 border border-purple-500/30">
                                                    Khusus CWPA &amp; WPA
                                                </span>
                                            <?php endif; ?>
                                            <?php if ($event['id'] == 1 || $event['id'] == 2): ?>
                                                <span class="inline-block px-2 py-0.5 rounded text-[9px] font-bold bg-red-500/20 text-red-400 border border-red-500/30">
                                                    Full Booked
                                                </span>
                                            <?php endif; ?>
                                            <span class="text-[9px] text-gray-400 ml-auto"><?= $event['short_date'] ?> <?= substr($event['time'], 0, 5) ?> WITA</span>
                                        </div>

                                        <!-- Title -->
                                        <h3 class="text-sm font-bold text-white group-hover:text-accent transition leading-tight mb-1 line-clamp-2">
                                            <?= esc($event['title']) ?>
                                        </h3>
                                    </div>

                                    <!-- Bottom: Info and Stats -->
                                    <div class="flex items-center gap-2 text-[9px] text-gray-400">
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-user-circle"></i> <?= esc($event['hosted_by']) ?>
                                        </span>
                                        <span>•</span>
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-map-marker-alt"></i> <?= esc($event['city']) ?>
                                        </span>
                                        <span>•</span>
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-users"></i> <?= $event['participants'] ?? '0/0' ?>
                                        </span>
                                        <span class="ml-auto flex items-center gap-2">
                                            <?php if (!empty($event['price']) && $event['price'] > 0): ?>
                                                <span class="font-bold" style="color: #33E818;">Rp <?= number_format($event['price'], 0, ',', '.') ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($event['poin_price']) && $event['poin_price'] > 0): ?>
                                                <span class="font-bold" style="color: #FFD700;"><i class="fas fa-coins mr-1"></i><?= number_format($event['poin_price'], 0, ',', '.') ?> Poin</span>
                                            <?php endif; ?>
                                            <?php if (empty($event['price']) && empty($event['poin_price'])): ?>
                                                <span class="font-bold" style="color: #33E818;">Gratis</span>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>

                    <!-- Pagination Links -->
                    <?php if ($totalPages > 1): ?>
                        <div class="flex items-center justify-center gap-2 mt-8">
                            <?php if ($page > 1): ?>
                                <a href="?page=<?= $page - 1 ?>" class="px-4 py-2 border border-white/10 rounded-lg text-sm text-gray-400 hover:text-white hover:border-[#33E818] transition"><i class="fas fa-chevron-left"></i> Prev</a>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <a href="?page=<?= $i ?>" class="px-4 py-2 border <?= $i == $page ? 'border-[#33E818] text-[#33E818] bg-[#33E818]/10' : 'border-white/10 text-gray-400 hover:text-white' ?> rounded-lg text-sm transition">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>

                            <?php if ($page < $totalPages): ?>
                                <a href="?page=<?= $page + 1 ?>" class="px-4 py-2 border border-white/10 rounded-lg text-sm text-gray-400 hover:text-white hover:border-[#33E818] transition">Next <i class="fas fa-chevron-right"></i></a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                </div>

                <!-- Bottom Section: Event Gratis -->
                <div class="space-y-6 order-2 w-full">
                <div class="flex items-center justify-between pb-4 border-b border-white/5">
                        <h2 class="text-xl font-bold text-white">Event Gratis</h2>
                    </div>

                <div class="space-y-6">
                    <?php 
                    $uniqueEvents = [];
                    $seenIds = [];
                    foreach ($events as $evt) {
                        $isFree = isset($evt['is_paid']) && (int) $evt['is_paid'] === 0;

                        if ($isFree && !in_array($evt['id'], $seenIds, true)) {
                            $uniqueEvents[] = $evt;
                            $seenIds[] = $evt['id'];
                        }
                    }
                    foreach ($uniqueEvents as $subEvent): 
                    ?>
                        <?php $eventUrl = !empty($subEvent['attendance_code'])
                            ? base_url('absensi/checkin/' . rawurlencode($subEvent['attendance_code']))
                            : base_url('event/' . $subEvent['slug']); ?>
                        <a href="<?= esc($eventUrl, 'attr') ?>" class="group block">
                            <div class="bg-[#0f0f0f] border border-white/5 rounded-xl p-4 flex gap-6 hover:border-accent/50 transition relative overflow-hidden">
                                <!-- Hover Glow Effect -->
                                <div class="absolute inset-0 bg-gradient-to-r from-accent/0 via-accent/5 to-accent/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></div>

                                <!-- Image -->
                                <div class="w-20 h-20 flex-shrink-0 rounded-lg overflow-hidden relative border border-white/5">
                                    <img src="<?= $subEvent['image'] ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0 flex flex-col justify-between relative z-10">
                                    <div>
                                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                                            <span class="inline-block px-2 py-0.5 rounded text-[9px] font-bold bg-accent/20 text-accent border border-accent/30">
                                                Gratis
                                            </span>
                                            <span class="inline-block px-2 py-0.5 rounded text-[9px] font-bold bg-accent/20 text-accent border border-accent/30">
                                                <?= $subEvent['category'] ?? 'Event' ?>
                                            </span>
                                            <?php if (\App\Services\AttendanceAccessService::isRestricted($subEvent)): ?>
                                                <span class="inline-block px-2 py-0.5 rounded text-[9px] font-bold bg-purple-500/20 text-purple-300 border border-purple-500/30">
                                                    Khusus CWPA &amp; WPA
                                                </span>
                                            <?php endif; ?>
                                            <span class="text-[9px] text-gray-400 ml-auto"><?= $subEvent['short_date'] ?></span>
                                        </div>
                                        <h4 class="text-sm font-bold text-white group-hover:text-accent transition leading-tight mb-1 line-clamp-2">
                                            <?= $subEvent['title'] ?>
                                        </h4>
                                    </div>

                                    <!-- Bottom Info -->
                                    <div class="flex items-center gap-2 text-[9px] text-gray-400">
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-user-circle"></i> <?= $subEvent['hosted_by'] ?>
                                        </span>
                                        <span>•</span>
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-map-marker-alt"></i> <?= $subEvent['city'] ?? 'Online' ?>
                                        </span>
                                        <span class="ml-auto flex items-center gap-2">
                                            <?php if (!empty($subEvent['price']) && $subEvent['price'] > 0): ?>
                                                <span class="font-bold" style="color: #33E818;">Rp <?= number_format($subEvent['price'], 0, ',', '.') ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($subEvent['poin_price']) && $subEvent['poin_price'] > 0): ?>
                                                <span class="font-bold" style="color: #FFD700;"><i class="fas fa-coins mr-1"></i><?= number_format($subEvent['poin_price'], 0, ',', '.') ?> Poin</span>
                                            <?php endif; ?>
                                            <?php if (empty($subEvent['price']) && empty($subEvent['poin_price'])): ?>
                                                <span class="font-bold" style="color: #33E818;">Gratis</span>
                                            <?php endif; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- End of main grid -->
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
        if (carousel) {
            carousel.addEventListener('mouseenter', () => clearInterval(slideInterval));
            carousel.addEventListener('mouseleave', resetInterval);
        }

        // Search Autosuggest Logic (Client-Side)
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');

        // Available events from PHP
        const availableEvents = <?= json_encode($events ?? []) ?>;

        searchInput.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase().trim();

            if (query.length < 2) {
                searchResults.classList.add('hidden');
                searchResults.innerHTML = '';
                return;
            }

            const filteredEvents = availableEvents.filter(event => {
                return (event.title && event.title.toLowerCase().includes(query)) ||
                    (event.category && event.category.toLowerCase().includes(query)) ||
                    (event.hosted_by && event.hosted_by.toLowerCase().includes(query));
            });

            if (filteredEvents.length > 0) {
                searchResults.classList.remove('hidden');
                searchResults.innerHTML = filteredEvents.map(event => {
                    const imageSrc = event.image || 'https://placehold.co/100x100?text=Event';
                    return `
                    <a href="<?= base_url('event/') ?>/${event.slug}" class="flex items-center gap-4 p-4 border-b border-white/5 hover:bg-white/5 transition group">
                        <img src="${imageSrc}" class="w-12 h-12 rounded bg-gray-800 object-cover flex-shrink-0">
                        <div>
                            <h4 class="text-sm font-bold text-white group-hover:text-[#33E818] transition line-clamp-1">${event.title}</h4>
                            <p class="text-xs text-gray-500 line-clamp-1">${event.short_date} • ${event.category}</p>
                        </div>
                    </a>
                    `;
                }).join('');
            } else {
                searchResults.classList.remove('hidden');
                searchResults.innerHTML = `
                    <div class="p-4 text-center text-gray-500 text-sm">
                        Tidak ada event ditemukan.
                    </div>
                `;
            }
        });

        // Hide when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.add('hidden');
            }
        });
    </script>

    <?= $this->endSection() ?>
