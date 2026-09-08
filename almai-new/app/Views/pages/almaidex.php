<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
    <style>
        .glossary-card { transition: all 0.3s ease; }
        .glossary-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(51, 232, 24, 0.2); }
        
        /* Modal content styling */
        #modalContent h1, #modalContent h2, #modalContent h3 { 
            color: #33e818; 
            font-weight: bold; 
            margin-top: 1.5rem; 
            margin-bottom: 1rem; 
        }
        #modalContent h1 { font-size: 1.875rem; }
        #modalContent h2 { font-size: 1.5rem; }
        #modalContent h3 { font-size: 1.25rem; }
        #modalContent p { margin-bottom: 1rem; line-height: 1.75; }
        #modalContent ul, #modalContent ol { 
            margin-left: 1.5rem; 
            margin-bottom: 1rem; 
            list-style-position: outside;
        }
        #modalContent ul { list-style-type: disc; }
        #modalContent ol { list-style-type: decimal; }
        #modalContent li { margin-bottom: 0.5rem; }
        #modalContent strong { color: #33e818 !important; font-weight: 600; }
        #modalContent a { color: #33e818 !important; text-decoration: underline; }
    </style>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-16 overflow-hidden">
        <div class="absolute inset-0 bg-grid z-0 pointer-events-none"></div>
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-accent/20 blur-[120px] rounded-full pointer-events-none"></div>
        <div class="container mx-auto px-6 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                <span class="text-accent">Almai</span>Dex
            </h1>
            <p class="text-gray-400 text-lg">Kamus Istilah Trading & Kalender Ekonomi Global</p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="pb-24 bg-card-bg relative z-10">
        <div class="container mx-auto px-4">
            
            <!-- Glosarium Section -->
            <div id="glossary-section" class="mb-20">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-3xl font-bold text-accent flex items-center gap-3">
                        <i class="fas fa-book"></i>
                        Glosarium Trading
                    </h2>
                </div>

                <!-- Search & Filter -->
                <div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-8">
                    <form method="get" class="mb-6">
                        <div class="relative">
                            <input 
                                type="text" 
                                name="search" 
                                value="<?= esc($search ?? '') ?>"
                                placeholder="Cari istilah trading..." 
                                class="w-full px-6 py-3 bg-black border border-white/20 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-accent transition"
                            >
                            <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 px-4 py-1.5 bg-accent text-black rounded-lg font-medium hover:bg-accent/90 transition text-sm">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>

                    <!-- A-Z Filter -->
                    <div class="flex flex-wrap gap-2 justify-center">
                        <?php foreach (range('A', 'Z') as $char): ?>
                            <a href="<?= base_url('almaidex') ?>?letter=<?= $char ?>" 
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
                        <a href="<?= base_url('almaidex') ?>" class="inline-block mt-4 text-accent hover:underline">Reset Filter</a>
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
                            <div class="group bg-[#111] border border-white/10 rounded-xl p-4 hover:border-accent/50 transition-all duration-300 hover:transform hover:-translate-y-1 cursor-pointer flex items-center justify-between"
                                 onclick="openGlossaryModal(this)">
                                <h3 class="font-bold text-white group-hover:text-accent transition truncate pr-4"><?= esc($item->term) ?></h3>
                                <i class="fas fa-chevron-right text-white/20 group-hover:text-accent transition text-sm flex-shrink-0"></i>
                                
                                <!-- Hidden Data for Modal -->
                                <div class="hidden glossary-data">
                                    <div class="term"><?= esc($item->term) ?></div>
                                    <div class="definition"><?= $item->definition // Allow HTML ?></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Economic Calendar Section -->
            <div id="economic-section" class="mb-20">
                <div class="bg-[#111] border border-white/10 rounded-2xl p-8">
                    <h2 class="text-3xl font-bold text-accent mb-8 flex items-center gap-3">
                        <i class="fas fa-chart-line"></i>
                        Kalender Ekonomi Global
                    </h2>
                    
                    <div class="bg-[#000] rounded-xl overflow-hidden shadow-2xl shadow-accent/5 border border-white/10" style="min-height: 700px;">
                        <!-- TradingView Widget BEGIN -->
                        <div class="tradingview-widget-container">
                            <div class="tradingview-widget-container__widget"></div>
                            <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-events.js" async>
                            {
                                "width": "100%",
                                "height": "700",
                                "colorTheme": "dark",
                                "isTransparent": true,
                                "locale": "id",
                                "importanceFilter": "0,1",
                                "currencyFilter": "USD,IDR,EUR,GBP,JPY,AUD,CAD,CHF,CNY"
                            }
                            </script>
                        </div>
                        <!-- TradingView Widget END -->
                    </div>
                    
                    <div class="mt-6 text-center">
                        <p class="text-gray-500 text-sm">
                            Powered by <a href="https://id.tradingview.com/" target="_blank" class="text-accent hover:underline font-bold">TradingView</a>
                        </p>
                    </div>
                </div>
            </div>
            
        </div>
    </section>

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
                    #modalContent, #modalContent * {
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
