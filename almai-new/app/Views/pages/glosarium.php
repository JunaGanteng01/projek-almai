<?= $this->extend('layouts/main') ?>

<?= $this->section('meta') ?>
<!-- SEO Meta Tags -->
<meta name="description" content="<?= esc($metaDescription ?? 'Glosarium Trading Lengkap A-Z') ?>">
<meta name="keywords" content="<?= esc($metaKeywords ?? 'glosarium trading, kamus trading') ?>">
<link rel="canonical" href="<?= esc($canonicalUrl ?? base_url('glosarium')) ?>">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="<?= current_url() ?>">
<meta property="og:title" content="<?= esc($title ?? 'Glosarium Trading') ?>">
<meta property="og:description" content="<?= esc($metaDescription ?? 'Kamus lengkap istilah trading') ?>">
<meta property="og:image" content="<?= base_url('images/og-glosarium.jpg') ?>">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="<?= current_url() ?>">
<meta name="twitter:title" content="<?= esc($title ?? 'Glosarium Trading') ?>">
<meta name="twitter:description" content="<?= esc($metaDescription ?? 'Kamus lengkap istilah trading') ?>">
<meta name="twitter:image" content="<?= base_url('images/og-glosarium.jpg') ?>">

<!-- Schema.org Markup -->
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "DefinedTermSet",
        "name": "Glosarium Trading Almai",
        "description": "Kamus lengkap istilah trading, forex, saham, cryptocurrency dan investasi dalam Bahasa Indonesia",
        "url": "<?= base_url('glosarium') ?>",
        "publisher": {
            "@type": "Organization",
            "name": "PT. Alma Indonesia Raya",
            "logo": {
                "@type": "ImageObject",
                "url": "<?= base_url('images/alma.gif') ?>"
            }
        }
    }
</script>

<!-- Breadcrumb Schema -->
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [{
                "@type": "ListItem",
                "position": 1,
                "name": "Home",
                "item": "<?= base_url() ?>"
            },
            {
                "@type": "ListItem",
                "position": 2,
                "name": "Halaman",
                "item": "<?= base_url('halaman') ?>"
            },
            {
                "@type": "ListItem",
                "position": 3,
                "name": "Glosarium Trading",
                "item": "<?= base_url('glosarium') ?>"
            }
        ]
    }
</script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
    .glossary-card {
        transition: all 0.3s ease;
    }

    .glossary-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(51, 232, 24, 0.2);
    }

    /* Modal content styling */
    #modalContent h1,
    #modalContent h2,
    #modalContent h3 {
        color: #33e818;
        font-weight: bold;
        margin-top: 1.5rem;
        margin-bottom: 1rem;
    }

    #modalContent h1 {
        font-size: 1.875rem;
    }

    #modalContent h2 {
        font-size: 1.5rem;
    }

    #modalContent h3 {
        font-size: 1.25rem;
    }

    #modalContent p {
        margin-bottom: 1rem;
        line-height: 1.75;
    }

    #modalContent ul,
    #modalContent ol {
        margin-left: 1.5rem;
        margin-bottom: 1rem;
        list-style-position: outside;
    }

    #modalContent ul {
        list-style-type: disc;
    }

    #modalContent ol {
        list-style-type: decimal;
    }

    #modalContent li {
        margin-bottom: 0.5rem;
    }

    #modalContent strong {
        color: #33e818 !important;
        font-weight: 600;
    }

    #modalContent a {
        color: #33e818 !important;
        text-decoration: underline;
    }
</style>

<!-- Breadcrumb Navigation -->
<nav class="container mx-auto px-6 pt-24 pb-4" aria-label="Breadcrumb">
    <ol class="flex items-center gap-2 text-sm text-gray-400" itemscope itemtype="https://schema.org/BreadcrumbList">
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a href="<?= base_url() ?>" class="hover:text-accent transition" itemprop="item">
                <span itemprop="name">Home</span>
            </a>
            <meta itemprop="position" content="1" />
        </li>
        <li><i class="fas fa-chevron-right text-xs text-gray-600"></i></li>
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a href="<?= base_url('halaman') ?>" class="hover:text-accent transition" itemprop="item">
                <span itemprop="name">Halaman</span>
            </a>
            <meta itemprop="position" content="2" />
        </li>
        <li><i class="fas fa-chevron-right text-xs text-gray-600"></i></li>
        <li class="text-accent" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <span itemprop="name">Glosarium Trading</span>
            <meta itemprop="position" content="3" />
        </li>
    </ol>
</nav>

<!-- Hero Section -->
<section class="relative pb-12 overflow-hidden">
    <div class="absolute inset-0 bg-grid z-0 pointer-events-none"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-accent/20 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center mb-8">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                <span class="text-accent">Glosarium Trading</span> Lengkap A-Z
            </h1>
            <p class="text-gray-400 text-lg max-w-3xl mx-auto">
                Kamus istilah trading forex, saham, cryptocurrency, dan investasi dalam Bahasa Indonesia. Pelajari 500+ definisi untuk pemula hingga profesional.
            </p>
        </div>

        <!-- SEO Intro Text -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 max-w-4xl mx-auto mb-8">
            <p class="text-gray-300 leading-relaxed mb-4">
                <strong class="text-accent">Glosarium Trading Almai</strong> adalah panduan lengkap berisi kumpulan istilah-istilah penting dalam dunia trading dan investasi. Dari <em>A</em> hingga <em>Z</em>, kami menyediakan definisi yang mudah dipahami untuk membantu Anda memahami terminologi pasar keuangan.
            </p>
            <p class="text-gray-300 leading-relaxed">
                Baik Anda seorang trader pemula yang baru mengenal <strong>forex</strong>, <strong>saham</strong>, atau <strong>cryptocurrency</strong>, maupun investor berpengalaman yang ingin memperdalam pengetahuan, glosarium ini akan menjadi referensi terpercaya Anda. Semua penjelasan disusun dalam <strong>Bahasa Indonesia</strong> yang jelas dan mudah dicerna.
            </p>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="pb-24 bg-card-bg relative z-10">
    <div class="container mx-auto px-4">

        <!-- Glosarium Section -->
        <div id="glossary-section" class="mb-20">
            <!-- Search & Filter -->
            <div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-8">
                <form method="get" class="mb-6">
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            value="<?= esc($search ?? '') ?>"
                            placeholder="Cari istilah trading..."
                            class="w-full px-6 py-3 bg-black border border-white/20 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-accent transition">
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 px-4 py-1.5 bg-accent text-black rounded-lg font-medium hover:bg-accent/90 transition text-sm">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>

                <!-- A-Z Filter -->
                <div class="flex flex-wrap gap-2 justify-center">
                    <?php foreach (range('A', 'Z') as $char): ?>
                        <a href="<?= base_url('glosarium') ?>?letter=<?= $char ?>"
                            class="w-10 h-10 flex items-center justify-center rounded-lg text-sm font-bold transition <?= ($selectedLetter === $char) ? 'bg-accent text-black' : 'bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white' ?>">
                            <?= $char ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Glossary Content -->
            <?php if (!$isFiltered): ?>
                <!-- State Awal: Belum memilih filter -->

            <?php elseif (empty($glossaries)): ?>
                <!-- Hasil Pencarian Kosong -->
                <div class="text-center py-20 bg-[#111] rounded-2xl border border-white/10">
                    <i class="fas fa-search text-6xl text-gray-600 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-400">Tidak ada istilah ditemukan</h3>
                    <p class="text-gray-500">Coba kata kunci lain atau reset filter</p>
                    <a href="<?= base_url('glosarium') ?>" class="inline-block mt-4 text-accent hover:underline">Reset Filter</a>
                </div>
            <?php else: ?>
                <!-- Ada Data -->
                <?php foreach ($glossaries as $letter => $items): ?>
                    <div class="mb-12">
                        <div class="flex items-center gap-4 mb-6 sticky top-[90px] z-30 bg-[#0a0a0a]/95 backdrop-blur py-4 rounded-xl px-4 border border-white/5">
                            <div class="w-10 h-10 bg-accent rounded-lg flex items-center justify-center text-black font-black text-xl shadow-lg shadow-accent/20">
                                <?= $letter ?>
                            </div>
                            <div class="h-px bg-white/10 flex-1"></div>
                        </div>

                        <div class="grid md:grid-cols-3 lg:grid-cols-4 gap-4">
                            <?php foreach ($items as $item): ?>
                                <!-- Item Card -->
                                <a href="<?= base_url('glosarium/' . $item->slug) ?>" class="group bg-[#111] border border-white/10 rounded-xl p-4 hover:border-accent/50 transition-all duration-300 hover:transform hover:-translate-y-1 cursor-pointer flex items-center justify-between block">
                                    <h3 class="font-bold text-white group-hover:text-accent transition truncate pr-4"><?= esc($item->term) ?></h3>
                                    <i class="fas fa-chevron-right text-white/20 group-hover:text-accent transition text-sm flex-shrink-0"></i>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</section>

<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-3886126299375636"
    crossorigin="anonymous"></script>
<!-- Ulfa -->
<ins class="adsbygoogle"
    style="display:block"
    data-ad-client="ca-pub-3886126299375636"
    data-ad-slot="2701324767"
    data-ad-format="auto"
    data-full-width-responsive="true"></ins>
<script>
    (adsbygoogle = window.adsbygoogle || []).push({});
</script>

<!-- Modal -->
<div id="definitionModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4" onclick="closeModal(event)">
    <div class="bg-[#111] border border-white/20 rounded-2xl max-w-2xl w-full max-h-[80vh] overflow-y-auto relative shadow-2xl shadow-accent/10 transform transition-all duration-300 scale-95 opacity-0 modal-content" onclick="event.stopPropagation()">
        <div class="p-8">
            <div class="flex justify-between items-start mb-6">
                <h3 id="modalTitle" class="text-3xl font-bold text-accent font-heading"></h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-white transition bg-white/5 hover:bg-white/10 p-2 rounded-lg">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="modalContent" class="prose prose-invert max-w-none text-white leading-relaxed text-lg [&_*]:!text-white">
                <!-- Content will be injected here -->
            </div>
            <!-- Force White Text Style -->
            <style>
                #modalContent,
                #modalContent * {
                    color: #ffffff !important;
                }
            </style>
        </div>
        <div class="bg-black/50 p-6 border-t border-white/10 flex justify-end">
            <button onclick="closeModal()" class="px-6 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl font-medium transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Script Placeholder for Layouts -->
<script>
    function openGlossaryModal(element) {
        const dataDiv = element.querySelector('.glossary-data');
        const term = dataDiv.querySelector('.term').textContent;
        const definition = dataDiv.querySelector('.definition').innerHTML;

        showDefinition(term, definition);
    }

    function showDefinition(term, definition) {
        const modal = document.getElementById('definitionModal');
        const modalContent = modal.querySelector('.modal-content');

        document.getElementById('modalTitle').textContent = term;
        document.getElementById('modalContent').innerHTML = definition;

        modal.classList.remove('hidden');
        // Small delay to allow display:block to apply before adding opacity/scale classes for animation
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);

        document.body.style.overflow = 'hidden';
    }

    function closeModal(event) {
        const modal = document.getElementById('definitionModal');
        const modalContent = modal.querySelector('.modal-content');

        if (!event || event.target.id === 'definitionModal' || !event.target.closest('.modal-content')) {
            modalContent.classList.remove('scale-100', 'opacity-100');
            modalContent.classList.add('scale-95', 'opacity-0');

            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 300); // Wait for transition
        }
    }

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
</script>
<?= $this->endSection() ?>