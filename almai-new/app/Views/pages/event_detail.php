<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-screen bg-black text-white font-sans pb-20 pt-24">

    <!-- Container -->
    <div class="max-w-5xl mx-auto px-6">

        <!-- Page Title -->
        <h1 class="text-4xl md:text-5xl font-bold text-center mb-8" data-aos="fade-down">
            Detail <span class="text-[#33E818]">Event</span>
        </h1>

        <!-- Hero Image -->
        <div class="relative w-full aspect-video md:aspect-[21/9] rounded-3xl overflow-hidden mb-8 border border-white/10 group" data-aos="fade-up">
            <img src="<?= $event['image'] ?>" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700 ease-in-out">
            <!-- Badge overlay -->
            <div class="absolute top-4 left-4">
                <span class="px-3 py-1.5 rounded-full bg-black/60 backdrop-blur-md border border-white/10 text-white text-[10px] font-bold uppercase tracking-wider flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#33E818] animate-pulse"></span>
                    <?= $event['category'] ?>
                </span>
            </div>
        </div>

        <!-- Title & Action -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-4" data-aos="fade-up">
            <h2 class="text-2xl md:text-3xl font-bold leading-tight max-w-2xl">
                <?= esc($event['title']) ?>
            </h2>
            <a href="<?= base_url('checkout/event/' . $event['slug']) ?>" class="px-6 py-3 bg-accent hover:bg-[#2ed615] text-black font-bold rounded-lg transition transform active:scale-95 shadow-[0_0_15px_rgba(51,232,24,0.4)] whitespace-nowrap flex items-center justify-center" style="background-color: #33E818;">
                Daftar Sekarang
            </a>
        </div>

        <!-- Meta Info -->
        <div class="flex flex-wrap items-center gap-6 text-sm text-gray-300 mb-10 border-b border-white/10 pb-8" data-aos="fade-up">
            <div class="flex items-center gap-2">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($event['hosted_by']) ?>&background=33E818&color=000" class="w-6 h-6 rounded-full border border-[#33E818]">
                <span class="font-bold text-white"><?= esc($event['hosted_by']) ?></span>
            </div>
            <div class="flex items-center gap-2">
                <i class="fas fa-map-marker-alt text-gray-500"></i>
                <span><?= esc($event['city']) ?></span>
            </div>
            <div class="flex items-center gap-2">
                <i class="fas fa-users text-gray-500"></i>
                <span><?= $event['participants'] ?> Peserta</span>
            </div>
        </div>

        <!-- Detail Stats Card -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-8" data-aos="fade-up">
            <h3 class="text-gray-400 font-bold mb-4 text-sm">Detail Event</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                <!-- Date -->
                <div class="flex items-center gap-4">
                    <div class="bg-[#222] rounded-xl p-3 text-center min-w-[70px] border border-white/5">
                        <span class="block text-xs text-gray-400 uppercase"><?= date('M', strtotime($event['date'])) ?></span>
                        <span class="block text-2xl font-bold text-white"><?= date('d', strtotime($event['date'])) ?></span>
                    </div>
                </div>

                <!-- Lockdown / Countdown -->
                <div class="flex justify-center md:border-l md:border-r border-white/10 py-2">
                    <div class="flex gap-6 text-center">
                        <div>
                            <span class="block text-2xl font-bold text-white font-mono">1</span>
                            <span class="text-[10px] text-gray-500 uppercase">Hours</span>
                        </div>
                        <div>
                            <span class="block text-2xl font-bold text-white font-mono">59</span>
                            <span class="text-[10px] text-gray-500 uppercase">Minutes</span>
                        </div>
                        <div>
                            <span class="block text-2xl font-bold text-white font-mono">59</span>
                            <span class="text-[10px] text-gray-500 uppercase">Seconds</span>
                        </div>
                    </div>
                </div>

                <!-- Price -->
                <div class="text-right">
                    <p class="text-xs text-gray-400 mb-1">Harga Tiket</p>
                    <p class="text-3xl font-bold text-white">
                        <?= $event['price'] == 0 ? 'FREE' : 'Rp ' . number_format($event['price'], 0, ',', '.') ?>
                    </p>
                    <p class="text-[10px] text-[#33E818]">Sudah termasuk pajak & snack</p>
                </div>
            </div>
        </div>

        <!-- Benefits & Package Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8" data-aos="fade-up">
            <!-- Benefits -->
            <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
                <h3 class="font-bold text-white mb-4">Benefits You'll Get</h3>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check text-[#33E818] mt-1 text-sm"></i>
                        <span class="text-sm text-gray-400">In-depth insights into crypto trends and digital assets in 2026</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check text-[#33E818] mt-1 text-sm"></i>
                        <span class="text-sm text-gray-400">Practical strategies for navigating the evolving crypto market</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check text-[#33E818] mt-1 text-sm"></i>
                        <span class="text-sm text-gray-400">Risk management knowledge for smarter investment decisions</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check text-[#33E818] mt-1 text-sm"></i>
                        <span class="text-sm text-gray-400">Real-world perspectives from industry practitioners</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check text-[#33E818] mt-1 text-sm"></i>
                        <span class="text-sm text-gray-400">Expanded network with crypto enthusiasts and professionals</span>
                    </li>
                </ul>
            </div>

            <!-- Packages -->
            <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
                <h3 class="font-bold text-white mb-4">Event Package Includes</h3>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check text-[#33E818] mt-1 text-sm"></i>
                        <span class="text-sm text-gray-400">E-certificate</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check text-[#33E818] mt-1 text-sm"></i>
                        <span class="text-sm text-gray-400">Lunch & coffee break</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check text-[#33E818] mt-1 text-sm"></i>
                        <span class="text-sm text-gray-400">Event materials</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check text-[#33E818] mt-1 text-sm"></i>
                        <span class="text-sm text-gray-400">Access to speakers & sessions</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <i class="fas fa-check text-[#33E818] mt-1 text-sm"></i>
                        <span class="text-sm text-gray-400">Networking</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- About Event -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-8" data-aos="fade-up">
            <h3 class="font-bold text-white mb-4">About Event</h3>
            <div class="prose prose-invert prose-sm max-w-none text-gray-400 leading-relaxed">
                <?= nl2br(esc($event['description'])) ?>
                <br><br>
                Smart Strategies in the Digital Asset Era adalah sebuah acara edukatif yang membahas perkembangan terkini dunia aset digital serta strategi cerdas dalam menghadapi ekosistem kripto yang terus berkembang. Melalui sesi diskusi dan insight dari praktisi berpengalaman, peserta akan mendapatkan perspektif strategis untuk mengambil keputusan yang lebih bijak.
            </div>
        </div>

        <!-- Location -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-8" data-aos="fade-up">
            <h3 class="font-bold text-white mb-4">Location</h3>
            <div class="rounded-xl overflow-hidden border border-white/10 h-[300px]">
                <?php if (!empty($event['map_url'])): ?>
                    <iframe src="<?= $event['map_url'] ?>" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                <?php else: ?>
                    <div class="w-full h-full bg-[#1a1a1a] flex flex-col items-center justify-center text-gray-500">
                        <i class="fas fa-map-marked-alt text-4xl mb-4"></i>
                        <p>Peta lokasi tidak tersedia untuk event online/webinar.</p>
                        <p class="text-sm text-[#33E818] mt-2"><?= esc($event['address']) ?></p>
                    </div>
                <?php endif; ?>
            </div>
            <p class="mt-4 text-gray-400 text-sm flex items-start gap-2">
                <i class="fas fa-map-pin text-[#33E818] mt-1"></i>
                <?= esc($event['address']) ?>
            </p>
        </div>

    </div>
</div>

<?= $this->endSection() ?>