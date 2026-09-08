<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Hero -->
<section class="relative pt-32 pb-12 overflow-hidden">
    <div class="absolute inset-0 bg-grid z-0 pointer-events-none"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-accent/20 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto">
            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-accent/20 border border-accent/30 text-accent px-4 py-3 rounded-xl mb-6">
                <?= session()->getFlashdata('success') ?>
            </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl mb-6">
                <?= session()->getFlashdata('error') ?>
            </div>
            <?php endif; ?>

            <div class="flex items-center gap-4 mb-6 flex-wrap">
                <span class="bg-accent text-black px-4 py-1 rounded-full text-sm font-bold"><?= esc($artikel['category']) ?></span>
                <span class="text-gray-400 text-sm"><?= date('d M Y', strtotime($artikel['created_at'])) ?></span>
                <span class="text-gray-400 text-sm"><?= esc($artikel['read_time']) ?> read</span>
                <?php if ($artikel['is_free'] || $artikel['poin_price'] == 0): ?>
                <span class="bg-accent/20 text-accent px-3 py-1 rounded-full text-xs font-bold">GRATIS</span>
                <?php else: ?>
                <span class="bg-yellow-500/20 text-yellow-500 px-3 py-1 rounded-full text-xs font-bold">
                    <i class="fas fa-coins mr-1"></i><?= number_format($artikel['poin_price']) ?> Poin
                </span>
                <?php endif; ?>
            </div>
            
            <h1 class="text-4xl md:text-5xl font-bold mb-6 tracking-tighter"><?= esc($artikel['title']) ?></h1>
            
            <div class="flex items-center gap-4 mb-8">
                <img src="<?= esc($artikel['wpa_photo']) ?>" alt="<?= esc($artikel['wpa_name']) ?>" class="w-12 h-12 rounded-full object-cover">
                <div>
                    <p class="font-bold"><?= esc($artikel['wpa_name']) ?></p>
                    <p class="text-gray-400 text-sm">Penulis</p>
                </div>
            </div>

            <img src="<?= esc($artikel['thumbnail']) ?>" alt="<?= esc($artikel['title']) ?>" class="w-full h-96 object-cover rounded-2xl mb-8">

            <?php if ($canRead): ?>
            <!-- Full Content -->
            <div class="prose prose-invert max-w-none">
                <?= $artikel['content'] ?>
            </div>

            <!-- Share -->
            <div class="mt-12 pt-8 border-t border-white/10">
                <p class="text-gray-400 mb-4">Bagikan artikel ini:</p>
                <div class="flex gap-4">
                    <a href="https://wa.me/?text=<?= urlencode($artikel['title'] . ' - ' . current_url()) ?>" target="_blank" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-accent hover:text-black transition">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?text=<?= urlencode($artikel['title']) ?>&url=<?= urlencode(current_url()) ?>" target="_blank" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-accent hover:text-black transition">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(current_url()) ?>" target="_blank" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-accent hover:text-black transition">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <button onclick="navigator.clipboard.writeText('<?= current_url() ?>')" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-accent hover:text-black transition">
                        <i class="fas fa-link"></i>
                    </button>
                </div>
            </div>
            <?php else: ?>
            <!-- Locked Content - Preview Only -->
            <div class="prose prose-invert max-w-none">
                <p class="text-gray-400 text-lg"><?= esc($artikel['excerpt']) ?></p>
            </div>
            
            <!-- Paywall -->
            <div class="mt-8 bg-gradient-to-br from-yellow-500/10 to-orange-500/10 border border-yellow-500/30 rounded-2xl p-8 text-center">
                <div class="w-20 h-20 bg-yellow-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-lock text-yellow-500 text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2">Artikel Premium</h3>
                <p class="text-gray-400 mb-6">Buka artikel ini dengan <?= number_format($artikel['poin_price']) ?> poin untuk membaca selengkapnya</p>
                
                <?php if (session()->get('isLoggedIn')): ?>
                    <div class="mb-6 p-4 bg-black/30 rounded-xl inline-block">
                        <p class="text-sm text-gray-400">Poin Anda saat ini</p>
                        <p class="text-2xl font-bold text-yellow-500"><?= number_format($userPoin) ?> <i class="fas fa-coins text-sm"></i></p>
                    </div>
                    
                    <?php if ($userPoin >= $artikel['poin_price']): ?>
                    <form action="<?= base_url('artikel/purchase/' . $artikel['id']) ?>" method="post">
                        <?= csrf_field() ?>
                        <button type="submit" class="px-8 py-4 bg-yellow-500 text-black font-bold rounded-xl hover:bg-yellow-400 transition shadow-[0_0_20px_rgba(234,179,8,0.3)]">
                            <i class="fas fa-unlock mr-2"></i> Buka dengan <?= number_format($artikel['poin_price']) ?> Poin
                        </button>
                    </form>
                    <?php else: ?>
                    <div class="space-y-3">
                        <p class="text-red-400 text-sm">Poin Anda tidak cukup. Butuh <?= number_format($artikel['poin_price'] - $userPoin) ?> poin lagi.</p>
                        <a href="<?= base_url('user/poin') ?>" class="inline-block px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                            <i class="fas fa-coins mr-2"></i> Cara Dapat Poin
                        </a>
                    </div>
                    <?php endif; ?>
                <?php else: ?>
                <div class="space-y-3">
                    <p class="text-gray-400 text-sm">Login untuk membuka artikel ini</p>
                    <a href="<?= base_url('login') ?>" class="inline-block px-8 py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login Sekarang
                    </a>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Related Articles -->
<?php if (!empty($relatedArtikel)): ?>
<section class="py-16 bg-[#0a0a0a]">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold mb-8 tracking-tighter">Artikel <span class="text-accent">Terkait</span></h2>
        <div class="grid md:grid-cols-3 gap-6">
            <?php 
            $count = 0;
            foreach ($relatedArtikel as $related): 
                if ($related['id'] != $artikel['id'] && $count < 3): 
                    $count++;
            ?>
            <div class="bg-[#111] rounded-2xl border border-white/10 overflow-hidden hover:border-accent transition group">
                <div class="relative">
                    <img src="<?= esc($related['thumbnail']) ?>" alt="<?= esc($related['title']) ?>" class="w-full h-48 object-cover group-hover:scale-105 transition duration-500">
                    <div class="absolute top-4 left-4 bg-accent text-black px-3 py-1 rounded-full text-xs font-bold"><?= esc($related['category']) ?></div>
                    <?php if (!($related['is_free'] ?? false) && ($related['poin_price'] ?? 0) > 0): ?>
                    <div class="absolute top-4 right-4 bg-yellow-500/90 text-black px-2 py-1 rounded-full text-xs font-bold">
                        <i class="fas fa-coins mr-1"></i><?= number_format($related['poin_price']) ?>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-3 text-xs text-gray-400">
                        <span><?= date('d M Y', strtotime($related['created_at'])) ?></span>
                        <span>•</span>
                        <span><?= esc($related['read_time']) ?></span>
                    </div>
                    <h3 class="font-bold mb-2 line-clamp-2"><?= esc($related['title']) ?></h3>
                    <p class="text-gray-400 text-sm mb-4 line-clamp-2"><?= esc($related['excerpt']) ?></p>
                    <a href="<?= base_url('artikel/' . $related['id']) ?>" class="text-accent text-sm font-medium hover:underline">
                        Baca Selengkapnya <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            <?php 
                endif;
            endforeach; 
            ?>
        </div>
    </div>
</section>
<?php endif; ?>
<?= $this->endSection() ?>
