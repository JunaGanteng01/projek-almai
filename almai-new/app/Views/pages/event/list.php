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
</style>

<!-- Main Container -->
<div class="min-h-screen bg-black text-white font-sans pb-20">

    <!-- Header Section -->
    <div class="max-w-7xl mx-auto px-4 pt-24 pb-8 relative z-10">

        <!-- Breadcrumb -->
        <nav class="flex mb-8 text-sm text-gray-400">
            <a href="<?= base_url('event') ?>" class="hover:text-white transition">Event</a>
            <span class="mx-2">/</span>
            <span class="text-[#33E818]">Semua Event</span>
        </nav>

        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-5xl font-black text-white mb-4">
                Semua <span class="text-[#33E818]">Event</span>
            </h1>
            <p class="text-gray-400 text-sm md:text-base max-w-2xl mx-auto mb-10">
                Temukan dan ikuti berbagai event menarik untuk meningkatkan skill tradingmu.
            </p>

            <!-- Search Bar -->
            <form action="<?= base_url('event/all') ?>" method="get" class="max-w-2xl mx-auto relative group">
                <div class="absolute left-6 top-1/2 -translate-y-1/2 pointer-events-none">
                    <i class="fas fa-search text-gray-500 text-lg group-focus-within:text-[#33E818] transition"></i>
                </div>
                <?php if ($selectedCategory): ?>
                    <input type="hidden" name="category" value="<?= esc($selectedCategory) ?>">
                <?php endif; ?>
                <input type="text" name="q" value="<?= esc($searchQuery) ?>" autocomplete="off"
                    class="w-full bg-[#111] border border-white/10 text-white text-sm rounded-full py-3.5 pl-14 pr-6 focus:outline-none focus:border-[#33E818] focus:ring-1 focus:ring-[#33E818] transition placeholder-gray-600 shadow-lg shadow-black/50"
                    placeholder="Cari event...">
            </form>
        </div>

        <!-- Filter Section -->
        <div class="mb-10">
            <!-- Category Pills -->
            <div class="flex flex-wrap items-center justify-center gap-2">
                <a href="<?= base_url('event/all') ?>"
                    class="px-4 py-1.5 rounded-full font-bold text-xs transition border border-white/5 <?= !$selectedCategory ? 'bg-[#33E818] text-white shadow-[0_0_15px_rgba(51,232,24,0.3)]' : 'bg-white/5 text-gray-400 hover:text-white hover:bg-white/10' ?>">
                    Semua Kategori
                </a>
                <?php
                $categories = ['Workshop', 'Seminar', 'Live Trade', 'Webinar', 'Market Outlook', 'Roadshow', 'Study Group', 'Community Meetup', 'Competition'];
                foreach ($categories as $cat):
                    $isActive = ($selectedCategory === $cat);
                ?>
                    <a href="<?= base_url('event/all') ?>?category=<?= urlencode($cat) ?>"
                        class="px-4 py-1.5 rounded-full font-bold text-xs transition border border-white/5 <?= $isActive ? 'bg-[#33E818] text-white shadow-[0_0_15px_rgba(51,232,24,0.3)]' : 'bg-white/5 text-gray-400 hover:text-white hover:bg-white/10' ?>">
                        <?= $cat ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Events Grid -->
        <?php if (empty($events)): ?>
            <div class="text-center py-20 text-gray-500">
                <i class="fas fa-calendar-times text-4xl mb-4"></i>
                <p>Tidak ada event ditemukan.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($events as $event): ?>
                    <a href="<?= base_url('event/' . $event['slug']) ?>" class="group block h-full">
                        <div class="bg-[#0f0f0f] border border-white/5 rounded-2xl p-4 md:p-5 hover:border-[#33E818]/50 transition relative overflow-hidden h-full flex flex-col">

                            <!-- Hover Glow Effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-[#33E818]/0 via-[#33E818]/5 to-[#33E818]/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></div>

                            <!-- Image -->
                            <div class="w-full aspect-video rounded-xl overflow-hidden relative border border-white/5 mb-4">
                                <img src="<?= $event['image'] ?>" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                <div class="absolute top-2 left-2 px-2 py-1 bg-black/80 backdrop-blur-md rounded-md border border-white/10">
                                    <div class="flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#33E818] animate-pulse"></span>
                                        <span class="text-[9px] font-bold text-white uppercase tracking-wider"><?= $event['category'] ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 flex flex-col">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-[#1ca1f2]/10 text-[#1ca1f2] border border-[#1ca1f2]/20">
                                            Upcoming
                                        </span>
                                        <?php if (\App\Services\AttendanceAccessService::isRestricted($event)): ?>
                                            <span class="inline-block px-2 py-0.5 rounded text-[9px] font-bold bg-purple-500/20 text-purple-300 border border-purple-500/30">
                                                Khusus CWPA &amp; WPA
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <span class="text-xs text-gray-400 font-mono text-[#33E818]"><?= $event['short_date'] ?></span>
                                </div>

                                <h3 class="text-lg font-bold !text-[#33E818] leading-tight mb-2 group-hover:!text-white transition line-clamp-2">
                                    <?= esc($event['title']) ?>
                                </h3>

                                <div class="flex items-center gap-4 text-xs text-gray-500 mb-4 mt-auto">
                                    <span class="flex items-center gap-1.5">
                                        <i class="fas fa-user-circle text-gray-400"></i> <?= esc($event['hosted_by']) ?>
                                    </span>
                                    <span class="flex items-center gap-1.5">
                                        <i class="fas fa-map-marker-alt text-gray-400"></i> <?= esc($event['city']) ?>
                                    </span>
                                </div>

                                    <div class="flex items-center justify-between pt-3 border-t border-white/5 text-[10px] text-gray-600 group-hover:text-gray-400 transition">
                                        <span><?= substr($event['time'], 0, 5) ?> WITA</span>
                                        <div class="flex gap-3 ml-auto text-[10px]">
                                            <?php if (!empty($event['price']) && $event['price'] > 0): ?>
                                                <span class="font-bold text-xs" style="color: #33E818;">Rp <?= number_format($event['price'], 0, ',', '.') ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($event['poin_price']) && $event['poin_price'] > 0): ?>
                                                <span class="font-bold text-xs" style="color: #FFD700;"><i class="fas fa-coins mr-1"></i><?= number_format($event['poin_price'], 0, ',', '.') ?> Poin</span>
                                            <?php endif; ?>
                                            <?php if (empty($event['price']) && empty($event['poin_price'])): ?>
                                                <span class="font-bold text-xs" style="color: #33E818;">Gratis</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Simple Pagination (if needed later) -->
            <!-- <div class="mt-12 flex justify-center">
                ...
            </div> -->
        <?php endif; ?>

    </div>
</div>

<?= $this->endSection() ?>
