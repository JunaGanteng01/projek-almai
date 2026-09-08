<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    .bg-grid { background-size: 40px 40px; background-image: linear-gradient(to right, rgba(255,255,255,0.05) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,0.05) 1px, transparent 1px); mask-image: linear-gradient(to bottom, black 40%, transparent 100%); }
    .filter-btn.active { background-color: #33e818; color: black; border-color: #33e818; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <!-- Hero -->
    <section class="relative pt-32 pb-8 overflow-hidden">
        <div class="absolute inset-0 bg-grid z-0 pointer-events-none"></div>
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[300px] md:w-[800px] h-[300px] md:h-[500px] bg-accent/20 blur-[120px] rounded-full pointer-events-none"></div>
        <div class="container mx-auto px-6 relative z-10 text-center">
            <p class="text-accent font-bold tracking-widest text-sm mb-4 uppercase">Trading Tools & Software</p>
            <h1 class="text-5xl md:text-6xl font-bold mb-4 tracking-tighter">Trading <span class="text-accent">Tools</span></h1>
            <p class="text-gray-400 max-w-2xl mx-auto">Expert Advisor, Copier, Signal, Indicator, dan Toolkit untuk meningkatkan performa trading Anda</p>
            
            <!-- PRO Badge Info -->
            <div class="mt-6 inline-flex items-center gap-2 bg-yellow-500/10 border border-yellow-500/30 px-4 py-2 rounded-full">
                <i class="fas fa-crown text-yellow-400"></i>
                <span class="text-sm text-yellow-400">Pembelian tools khusus untuk User PRO</span>
            </div>
        </div>
    </section>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('error')): ?>
    <div class="container mx-auto px-6 mb-4">
        <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-xl flex items-center gap-3">
            <i class="fas fa-exclamation-circle"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('success')): ?>
    <div class="container mx-auto px-6 mb-4">
        <div class="bg-accent/20 border border-accent/50 text-accent px-4 py-3 rounded-xl flex items-center gap-3">
            <i class="fas fa-check-circle"></i>
            <?= session()->getFlashdata('success') ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Search -->
    <section class="py-6">
        <div class="container mx-auto px-6">
            <div class="max-w-xl mx-auto">
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Cari tools berdasarkan nama atau kategori..." 
                        class="w-full bg-[#111] border border-white/20 rounded-full px-6 py-4 pl-14 focus:border-accent focus:outline-none">
                    <i class="fas fa-search absolute left-6 top-1/2 -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Filter -->
    <section class="py-4">
        <div class="container mx-auto px-6">
            <div class="flex flex-wrap gap-3 justify-center" id="toolsFilters">
                <button class="filter-btn active px-6 py-2 rounded-full border border-white/20 text-sm font-medium hover:border-accent hover:text-accent transition" data-filter="all">Semua</button>
                <?php foreach ($categories as $cat): ?>
                    <?php if ($cat === 'Copier' || $cat === 'Indicator'): ?>
                    <?php continue; ?>
                    <?php endif; ?>
                <button class="filter-btn px-6 py-2 rounded-full border border-white/20 text-sm font-medium hover:border-accent hover:text-accent transition" data-filter="<?= esc($cat) ?>"><?= esc($cat) ?></button>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Category Landing Banners (muncul saat filter aktif) -->
    <section class="py-6 hidden" id="categoryBanner">
        <div class="container mx-auto px-6">
            <div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
            </div>
        </div>
    </section>

    <!-- All Categories Grid (muncul saat "Semua" aktif) -->
    <section class="py-6" id="allCategoriesBanner">
        <div class="max-w-6xl mx-auto px-6">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4" id="allCategoriesGrid">
            </div>
        </div>
    </section>

    <!-- Tools Grid -->
    <section class="py-12">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-3 gap-6" id="toolsGrid">
                <?php foreach ($tools as $tool): ?>
                <div class="tool-card bg-[#111] rounded-2xl border border-white/10 overflow-hidden hover:border-accent transition group" data-category="<?= esc($tool['category']) ?>" data-aos="fade-up">
                    <div class="relative">
                        <img src="<?= esc($tool['thumbnail']) ?>" alt="<?= esc($tool['name']) ?>" class="w-full h-48 object-cover group-hover:scale-105 transition duration-500">
                        <div class="absolute top-4 left-4 <?= getCategoryColor($tool['category']) ?> text-white px-3 py-1 rounded-full text-xs font-bold">
                            <i class="fas <?= getCategoryIcon($tool['category']) ?> mr-1"></i><?= esc($tool['category']) ?>
                        </div>
                        <div class="absolute top-4 right-4 bg-black/80 text-white px-3 py-1 rounded-full text-xs">
                            ⭐ <?= esc($tool['rating']) ?>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-accent text-xs font-medium"><?= esc($tool['platform']) ?></span>
                            <span class="text-gray-500 text-xs">• <?= number_format($tool['sales']) ?> terjual</span>
                        </div>
                        <h3 class="text-lg font-bold mb-2 line-clamp-1"><?= esc($tool['name']) ?></h3>
                        <p class="text-gray-400 text-sm mb-4 line-clamp-2"><?= esc($tool['description']) ?></p>
                        <div class="flex flex-wrap gap-1 mb-4">
                            <?php foreach (array_slice($tool['compatibility'], 0, 3) as $comp): ?>
                            <span class="bg-white/10 text-xs px-2 py-1 rounded"><?= esc($comp) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <span class="text-gray-500 line-through text-sm"><?= formatRupiah($tool['original_price']) ?></span>
                                <span class="text-accent font-bold text-lg ml-2"><?= formatRupiah($tool['price']) ?></span>
                            </div>
                        </div>
                        <a href="<?= base_url('tools/' . $tool['id']) ?>" class="block text-center py-3 bg-accent text-black rounded-lg font-bold hover:bg-white transition">
                            Lihat Detail
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div id="noResults" class="hidden text-center py-12 text-gray-400">
                <i class="fas fa-search text-4xl mb-4"></i>
                <p>Tidak ada tools yang ditemukan.</p>
            </div>
        </div>
    </section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const categoryData = {
        'Expert Advisor': {
            icon: 'fas fa-robot',
            color: 'bg-blue-500',
            badge: 'bg-blue-500/20 text-blue-400',
            title: 'Expert Advisor',
            desc: 'Robot trading otomatis yang bekerja 24/7 untuk mengeksekusi strategi Anda tanpa emosi. Dibangun dengan algoritma canggih untuk berbagai kondisi market.',
            url: '<?= base_url("expert-advisor") ?>'
        },
        'Signal': {
            icon: 'fas fa-signal',
            color: 'bg-orange-500',
            badge: 'bg-orange-500/20 text-orange-400',
            title: 'Trading Signal',
            desc: 'Notifikasi entry dan exit point yang akurat berdasarkan analisis teknikal dan fundamental. Tingkatkan win rate Anda dengan signal terverifikasi.',
            url: '<?= base_url("signal") ?>'
        },
        'Toolkit': {
            icon: 'fas fa-toolbox',
            color: 'bg-pink-500',
            badge: 'bg-pink-500/20 text-pink-400',
            title: 'Trading Toolkit',
            desc: 'Kumpulan utility, copier, indicator, dan tools pendukung untuk manajemen risiko dan optimasi trading Anda.',
            url: '<?= base_url("toolkit") ?>',
            includes: ['Copier', 'Indicator', 'Toolkit']
        },
        'Prop Firm': {
            icon: 'fas fa-building-columns',
            color: 'bg-emerald-500',
            badge: 'bg-emerald-500/20 text-emerald-400',
            title: 'Prop Firm Tools',
            desc: 'Tools khusus untuk lulus challenge prop firm. Dilengkapi dengan fitur risk management ketat dan strategi yang sudah terbukti lolos evaluasi.',
            url: '<?= base_url("prop-firm") ?>'
        }
    };

    let currentFilter = 'all';

    function showCategoryBanner(category) {
        const data = categoryData[category];
        if (!data) return;

        const banner = document.getElementById('categoryBanner');
        const allBanner = document.getElementById('allCategoriesBanner');
        allBanner.classList.add('hidden');

        // Khusus Expert Advisor — tampilkan 2 produk (AIWE & BIDBOX)
        if (category === 'Expert Advisor') {
            banner.classList.remove('hidden');
            banner.querySelector('.container > div').innerHTML = `
                <div class="grid md:grid-cols-2 gap-6 p-6 md:p-8">
                    <!-- AIWE -->
                    <div class="bg-black/40 border border-blue-500/20 rounded-2xl p-6 hover:border-accent transition">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-xl flex items-center justify-center text-3xl bg-blue-500/20">
                                <i class="fas fa-robot text-blue-400"></i>
                            </div>
                            <div>
                                <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-500/20 text-blue-400">Expert Advisor</span>
                                <h3 class="text-xl font-bold text-white mt-1">AIWE</h3>
                            </div>
                        </div>
                        <p class="text-gray-400 text-sm mb-5">AI-powered Expert Advisor dengan machine learning untuk analisis market otomatis dan eksekusi trading presisi tinggi.</p>
                        <a href="${data.url}/aiwe" class="inline-flex items-center gap-2 px-6 py-3 bg-accent text-black text-sm font-bold rounded-lg hover:bg-white transition">
                            <i class="fas fa-arrow-right text-xs"></i> Lihat AIWE
                        </a>
                    </div>
                    <!-- BIDBOX -->
                    <div class="bg-black/40 border border-blue-500/20 rounded-2xl p-6 hover:border-accent transition">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-16 h-16 rounded-xl flex items-center justify-center text-3xl bg-blue-500/20">
                                <i class="fas fa-robot text-blue-400"></i>
                            </div>
                            <div>
                                <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-500/20 text-blue-400">Expert Advisor</span>
                                <h3 class="text-xl font-bold text-white mt-1">BIDBOX</h3>
                            </div>
                        </div>
                        <p class="text-gray-400 text-sm mb-5">Expert Advisor multi-strategy dengan risk management ketat. Dirancang untuk konsistensi profit di berbagai kondisi market.</p>
                        <a href="${data.url}/bidbox" class="inline-flex items-center gap-2 px-6 py-3 bg-accent text-black text-sm font-bold rounded-lg hover:bg-white transition">
                            <i class="fas fa-arrow-right text-xs"></i> Lihat BIDBOX
                        </a>
                    </div>
                </div>
            `;
            return;
        }

// Khusus Signal — tampilkan 2 channel signal
if (category === 'Signal') {
    banner.classList.remove('hidden');
    banner.querySelector('.container > div').innerHTML = `
        <div class="p-6 md:p-8">
            <h3 class="text-sm font-bold text-accent uppercase tracking-widest mb-4">
                <i class="fas fa-broadcast-tower mr-2"></i>Channel Signal
            </h3>

            <div class="grid md:grid-cols-2 gap-4">

                <!-- Signal Plus500 -->
                <div class="bg-black/40 border border-orange-500/20 rounded-2xl p-5 hover:border-accent transition text-center">
                    <div class="w-14 h-14 mx-auto rounded-xl flex items-center justify-center text-2xl bg-orange-500/20 mb-3">
                        <i class="fas fa-signal text-orange-400"></i>
                    </div>
                    <h4 class="text-white font-bold mb-1">Signal Plus 500</h4>
                    <p class="text-gray-400 text-xs mb-4">Channel signal harian dari Plus 500</p>
                    
                    <a href="https://almai.id/signalplus500"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-accent text-black text-xs font-bold rounded-lg hover:bg-white transition">
                        <i class="fas fa-arrow-right text-xs"></i> Join
                    </a>
                </div>

                <!-- Signal Money Mall -->
                <div class="bg-black/40 border border-orange-500/20 rounded-2xl p-5 hover:border-accent transition text-center">
                    <div class="w-14 h-14 mx-auto rounded-xl flex items-center justify-center text-2xl bg-orange-500/20 mb-3">
                        <i class="fas fa-signal text-orange-400"></i>
                    </div>
                    <h4 class="text-white font-bold mb-1">Signal Money Mall</h4>
                    <p class="text-gray-400 text-xs mb-4">Channel signal harian dari Money Mall</p>
                    
                    <a href="https://almai.id/signalmoneymall"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 px-4 py-2 bg-accent text-black text-xs font-bold rounded-lg hover:bg-white transition">
                        <i class="fas fa-arrow-right text-xs"></i> Join
                    </a>
                </div>

            </div>
        </div>
    `;
    return;
}

        // Khusus Toolkit — tampilkan Copier, Indicator, dan Toolkit
        if (category === 'Toolkit') {
            banner.classList.remove('hidden');
            banner.querySelector('.container > div').innerHTML = `
                <div class="p-6 md:p-8">
                    <h3 class="text-sm font-bold text-accent uppercase tracking-widest mb-4"><i class="fas fa-toolbox mr-2"></i>Trading Toolkit</h3>
                    <div class="grid md:grid-cols-3 gap-4">
                        <div class="bg-black/40 border border-purple-500/20 rounded-2xl p-6 hover:border-accent transition">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-14 h-14 rounded-xl flex items-center justify-center text-2xl bg-purple-500/20">
                                    <i class="fas fa-copy text-purple-400"></i>
                                </div>
                                <div>
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-purple-500/20 text-purple-400">Copier</span>
                                    <h4 class="text-lg font-bold text-white mt-1">Trade Copier</h4>
                                </div>
                            </div>
                            <p class="text-gray-400 text-sm mb-5">Salin trade dari master trader ke akun Anda secara real-time tanpa repot.</p>
                            <a href="<?= base_url("copier") ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-accent text-black text-sm font-bold rounded-lg hover:bg-white transition">
                                <i class="fas fa-arrow-right text-xs"></i> Lihat Copier
                            </a>
                        </div>
                        <div class="bg-black/40 border border-cyan-500/20 rounded-2xl p-6 hover:border-accent transition">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-14 h-14 rounded-xl flex items-center justify-center text-2xl bg-cyan-500/20">
                                    <i class="fas fa-chart-line text-cyan-400"></i>
                                </div>
                                <div>
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-cyan-500/20 text-cyan-400">Indicator</span>
                                    <h4 class="text-lg font-bold text-white mt-1">Custom Indicator</h4>
                                </div>
                            </div>
                            <p class="text-gray-400 text-sm mb-5">Indikator teknikal custom untuk analisis market dan identifikasi peluang entry.</p>
                            <a href="<?= base_url("indicator") ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-accent text-black text-sm font-bold rounded-lg hover:bg-white transition">
                                <i class="fas fa-arrow-right text-xs"></i> Lihat Indicator
                            </a>
                        </div>
                        <div class="bg-black/40 border border-pink-500/20 rounded-2xl p-6 hover:border-accent transition">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-14 h-14 rounded-xl flex items-center justify-center text-2xl bg-pink-500/20">
                                    <i class="fas fa-toolbox text-pink-400"></i>
                                </div>
                                <div>
                                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-pink-500/20 text-pink-400">Toolkit</span>
                                    <h4 class="text-lg font-bold text-white mt-1">Utility Tools</h4>
                                </div>
                            </div>
                            <p class="text-gray-400 text-sm mb-5">Kumpulan utility untuk manajemen risiko, journaling, dan optimasi trading.</p>
                            <a href="<?= base_url("toolkit") ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-accent text-black text-sm font-bold rounded-lg hover:bg-white transition">
                                <i class="fas fa-arrow-right text-xs"></i> Lihat Toolkit
                            </a>
                        </div>
                    </div>
                </div>
            `;
            return;
        }

        // Kategori lainnya — tampilan banner tunggal
        banner.classList.remove('hidden');
        banner.querySelector('.container > div').innerHTML = `
            <div class="flex flex-col md:flex-row items-center gap-8 p-8 md:p-12">
                <div class="flex-shrink-0">
                    <div class="w-32 h-32 md:w-40 md:h-40 rounded-2xl flex items-center justify-center text-5xl md:text-6xl ${data.color}/20">
                        <i class="${data.icon} ${data.color.replace('bg-', 'text-')}"></i>
                    </div>
                </div>
                <div class="flex-1 text-center md:text-left">
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider mb-3 ${data.badge}">${category}</span>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-3">${data.title}</h2>
                    <p class="text-gray-400 text-sm md:text-base mb-6 max-w-xl">${data.desc}</p>
                    <a href="${data.url}" class="inline-flex items-center gap-2 px-8 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition shadow-[0_0_15px_rgba(51,232,24,0.3)]">
                        <i class="fas fa-arrow-right"></i> Lihat ${data.title}
                    </a>
                </div>
            </div>
        `;
    }

    function hideCategoryBanner() {
        document.getElementById('categoryBanner').classList.add('hidden');
    }

    function showAllCategories() {
        hideCategoryBanner();
        const grid = document.getElementById('allCategoriesGrid');
        grid.innerHTML = '';

        Object.keys(categoryData).forEach(cat => {
            const data = categoryData[cat];

            if (cat === 'Expert Advisor') {
                // AIWE Card
                const aiweCard = document.createElement('div');
                aiweCard.className = 'bg-[#111] border border-white/10 rounded-2xl p-6 hover:border-accent transition group';
                aiweCard.innerHTML = `
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 rounded-xl flex items-center justify-center text-2xl bg-blue-500/20">
                            <i class="fas fa-robot text-blue-400"></i>
                        </div>
                        <div>
                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-500/20 text-blue-400">Expert Advisor</span>
                            <h3 class="text-lg font-bold text-white mt-1">AIWE</h3>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm mb-5 line-clamp-2">AI-powered Expert Advisor dengan machine learning untuk analisis market otomatis.</p>
                    <a href="${data.url}/aiwe" class="inline-flex items-center gap-2 px-5 py-2.5 bg-accent text-black text-sm font-bold rounded-lg hover:bg-white transition">
                        <i class="fas fa-arrow-right text-xs"></i> Lihat AIWE
                    </a>
                `;
                grid.appendChild(aiweCard);

                // BIDBOX Card
                const bidboxCard = document.createElement('div');
                bidboxCard.className = 'bg-[#111] border border-white/10 rounded-2xl p-6 hover:border-accent transition group';
                bidboxCard.innerHTML = `
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 rounded-xl flex items-center justify-center text-2xl bg-blue-500/20">
                            <i class="fas fa-robot text-blue-400"></i>
                        </div>
                        <div>
                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-blue-500/20 text-blue-400">Expert Advisor</span>
                            <h3 class="text-lg font-bold text-white mt-1">BIDBOX</h3>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm mb-5 line-clamp-2">Expert Advisor multi-strategy dengan risk management ketat untuk konsistensi profit.</p>
                    <a href="${data.url}/bidbox" class="inline-flex items-center gap-2 px-5 py-2.5 bg-accent text-black text-sm font-bold rounded-lg hover:bg-white transition">
                        <i class="fas fa-arrow-right text-xs"></i> Lihat BIDBOX
                    </a>
                `;
                grid.appendChild(bidboxCard);
} else if (cat === 'Signal') {

    const channels = [
        { 
            name: 'Signal Plus 500', 
            desc: 'Channel signal Trading AI Real-Time & Akurat untuk Platform Plus 500', 
            link: 'https://almai.id/signalplus500' 
        },
        { 
            name: 'Signal Money Mall', 
            desc: 'Channel signal Trading AI Real-Time & Akurat untuk Platform  Money Mall', 
            link: 'https://almai.id/signalmoneymall' 
        }
    ];

    channels.forEach(c => {
        const card = document.createElement('div');
        card.className = 'bg-[#111] border border-white/10 rounded-2xl p-6 hover:border-accent transition group';
        card.innerHTML = `
            <div class="flex items-center gap-4 mb-4">
                <div class="w-14 h-14 rounded-xl flex items-center justify-center text-2xl bg-orange-500/20">
                    <i class="fas fa-signal text-orange-400"></i>
                </div>
                <div>
                    <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-orange-500/20 text-orange-400">Channel</span>
                    <h3 class="text-lg font-bold text-white mt-1">${c.name}</h3>
                </div>
            </div>
            <p class="text-gray-400 text-sm mb-5 line-clamp-2">${c.desc}</p>
            <a href="${c.link}" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-accent text-black text-sm font-bold rounded-lg hover:bg-white transition">
                <i class="fas fa-arrow-right text-xs"></i> Join
            </a>
        `;
        grid.appendChild(card);
    });
            } else if (cat === 'Toolkit') {
                // Toolkit — tampilkan Copier, Indicator, Toolkit
                const toolkitItems = [
                    { name: 'Trade Copier', icon: 'fas fa-copy', color: 'bg-purple-500', badge: 'bg-purple-500/20 text-purple-400', desc: 'Salin trade dari master trader ke akun Anda secara real-time.', url: '<?= base_url("copier") ?>' }
                ];
                toolkitItems.forEach(item => {
                    const card = document.createElement('div');
                    card.className = 'bg-[#111] border border-white/10 rounded-2xl p-6 hover:border-accent transition group';
                    card.innerHTML = `
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-14 h-14 rounded-xl flex items-center justify-center text-2xl ${item.color}/20">
                                <i class="${item.icon} ${item.color.replace('bg-', 'text-')}"></i>
                            </div>
                            <div>
                                <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider ${item.badge}">Toolkit</span>
                                <h3 class="text-lg font-bold text-white mt-1">${item.name}</h3>
                            </div>
                        </div>
                        <p class="text-gray-400 text-sm mb-5 line-clamp-2">${item.desc}</p>
                        <a href="${item.url}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-accent text-black text-sm font-bold rounded-lg hover:bg-white transition">
                            <i class="fas fa-arrow-right text-xs"></i> Lihat ${item.name}
                        </a>
                    `;
                    grid.appendChild(card);
                });
            } else {
                const card = document.createElement('div');
                card.className = 'bg-[#111] border border-white/10 rounded-2xl p-6 hover:border-accent transition group';
                card.innerHTML = `
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 rounded-xl flex items-center justify-center text-2xl ${data.color}/20">
                            <i class="${data.icon} ${data.color.replace('bg-', 'text-')}"></i>
                        </div>
                        <div>
                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider ${data.badge}">${cat}</span>
                            <h3 class="text-lg font-bold text-white mt-1">${data.title}</h3>
                        </div>
                    </div>
                    <p class="text-gray-400 text-sm mb-5 line-clamp-2">${data.desc}</p>
                    <a href="${data.url}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-accent text-black text-sm font-bold rounded-lg hover:bg-white transition">
                        <i class="fas fa-arrow-right text-xs"></i> Lihat ${data.title}
                    </a>
                `;
                grid.appendChild(card);
            }
        });

        document.getElementById('allCategoriesBanner').classList.remove('hidden');
    }

    // Tampilkan semua kategori saat halaman pertama kali dimuat
    showAllCategories();

    function handleSearch() {
        const query = document.getElementById('searchInput').value.toLowerCase();
        const cards = document.querySelectorAll('.tool-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.querySelector('h3').textContent.toLowerCase();
            const category = card.dataset.category.toLowerCase();
            const description = card.querySelector('p').textContent.toLowerCase();
            
            const matchesSearch = !query || name.includes(query) || category.includes(query) || description.includes(query);
            const toolkitCategories = ['Copier', 'Indicator', 'Toolkit'];
            const matchesFilter = currentFilter === 'all' || card.dataset.category === currentFilter || 
                (currentFilter === 'Toolkit' && toolkitCategories.includes(card.dataset.category));
            
            if (matchesSearch && matchesFilter) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        document.getElementById('noResults').classList.toggle('hidden', visibleCount > 0);
    }

    document.getElementById('searchInput').addEventListener('input', handleSearch);

    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.dataset.filter;

            if (currentFilter === 'all') {
                hideCategoryBanner();
                showAllCategories();
            } else {
                document.getElementById('allCategoriesBanner').classList.add('hidden');
                showCategoryBanner(currentFilter);
            }

            handleSearch();
        });
    });
</script>
<?= $this->endSection() ?>

<?php
function getCategoryIcon($category) {
    $icons = [
        'Expert Advisor' => 'fa-robot',
        'Copier'         => 'fa-copy',
        'Signal'         => 'fa-signal',
        'Indicator'      => 'fa-chart-line',
        'Toolkit'        => 'fa-toolbox',
        'Prop Firm'      => 'fa-building-columns'
    ];
    return $icons[$category] ?? 'fa-cube';
}

function getCategoryColor($category) {
    $colors = [
        'Expert Advisor' => 'bg-blue-500',
        'Copier'         => 'bg-purple-500',
        'Signal'         => 'bg-orange-500',
        'Indicator'      => 'bg-cyan-500',
        'Toolkit'        => 'bg-pink-500',
        'Prop Firm'      => 'bg-emerald-500'
    ];
    return $colors[$category] ?? 'bg-gray-500';
}

function formatRupiah($number) {
    return 'Rp ' . number_format($number, 0, ',', '.');
}
?>
