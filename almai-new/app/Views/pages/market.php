<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="pt-[100px] pb-20 min-h-screen relative overflow-hidden bg-black text-white">
    <!-- Gradient Backgrounds -->
    <div class="absolute top-0 left-0 w-full h-[500px] bg-accent/20 blur-[120px] rounded-full pointer-events-none -translate-y-1/2"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-bold mb-4 font-outfit uppercase bg-clip-text text-transparent bg-gradient-to-r from-accent to-accent/50"><?= esc($pageTitle) ?></h1>
                <p class="text-gray-400 text-lg"><?= esc($pageSubtitle) ?></p>
            </div>

            <?php if (isset($apiError) && $apiError): ?>
                <div class="bg-red-500/20 border border-red-500 text-red-500 p-4 rounded-xl mb-8">
                    <?= esc($apiError) ?>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if (!empty($prices)): ?>
                    <?php foreach ($prices as $price): ?>
                        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 hover:border-accent/50 transition duration-300 relative overflow-hidden group">
                            <!-- Gradient Glow on hover -->
                            <div class="absolute inset-0 bg-gradient-to-br from-accent/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-300"></div>
                            
                            <div class="relative z-10">
                                <div class="flex justify-between items-center mb-4">
                                    <h2 class="text-2xl font-bold font-outfit text-accent"><?= esc($price['base_symbol']) ?></h2>
                                    <span class="text-xs text-gray-500 bg-white/5 px-2 py-1 rounded-full"><?= esc($price['actual_symbol']) ?></span>
                                </div>
                                
                                <div class="space-y-3">
                                    <div class="flex justify-between items-center pb-2 border-b border-white/5">
                                        <span class="text-gray-400">Bid</span>
                                        <span class="font-mono text-lg text-white font-medium"><?= esc($price['bid']) ?></span>
                                    </div>
                                    <div class="flex justify-between items-center pb-2 border-b border-white/5">
                                        <span class="text-gray-400">Ask</span>
                                        <span class="font-mono text-lg text-white font-medium"><?= esc($price['ask']) ?></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-400">Last</span>
                                        <span class="font-mono text-xl font-bold text-accent"><?= esc($price['last']) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full text-center py-10 text-gray-500 bg-[#111] border border-white/10 rounded-2xl">
                        Tidak ada data harga yang tersedia.
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-500">Harga diambil dari API MT5. Pembaruan akan dilakukan secara otomatis setiap halaman dimuat (dibatasi oleh rate limit).</p>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
