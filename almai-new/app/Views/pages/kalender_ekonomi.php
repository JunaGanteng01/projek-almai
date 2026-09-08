<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <!-- Hero Section -->
    <section class="relative pt-32 pb-16 overflow-hidden">
        <div class="absolute inset-0 bg-grid z-0 pointer-events-none"></div>
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-accent/20 blur-[120px] rounded-full pointer-events-none"></div>
        <div class="container mx-auto px-6 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">
                Kalender <span class="text-accent">Ekonomi</span>
            </h1>
            <p class="text-gray-400 text-lg">Jadwal Rilis Data Ekonomi Global & Event Penting</p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="pb-24 bg-card-bg relative z-10">
        <div class="container mx-auto px-4">
            
            <!-- Economic Calendar Section -->
            <div id="economic-section" class="mb-20">
                <div class="bg-[#111] border border-white/10 rounded-2xl p-8">
                    
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

<?= $this->endSection() ?>
