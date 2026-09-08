<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Hero -->
<section class="relative pt-32 pb-8 overflow-hidden">
    <div class="absolute inset-0 bg-grid z-0 pointer-events-none"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-accent/20 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="container mx-auto px-6 relative z-10 text-center">
        <p class="text-accent font-bold tracking-widest text-sm mb-4 uppercase">Insight & Analysis</p>
        <h1 class="text-5xl md:text-6xl font-bold mb-4 tracking-tighter">Semua <span class="text-accent">Artikel</span></h1>
        <p class="text-gray-400">Baca artikel terbaru seputar trading dan investasi</p>
    </div>
</section>

<!-- Search -->
<section class="py-6">
    <div class="container mx-auto px-6">
        <div class="max-w-xl mx-auto">
            <form action="<?= base_url('artikel') ?>" method="get" class="relative">
                <input type="text" name="search" value="<?= esc($searchQuery ?? '') ?>" 
                    placeholder="Cari artikel..." 
                    class="w-full bg-[#111] border border-white/20 rounded-full px-6 py-4 pl-14 focus:border-accent focus:outline-none">
                <i class="fas fa-search absolute left-6 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </form>
        </div>
    </div>
</section>

<!-- Filter -->
<section class="py-4">
    <div class="container mx-auto px-6">
        <div class="flex flex-wrap gap-3 justify-center">
            <a href="<?= base_url('artikel') ?>" 
               class="px-6 py-2 rounded-full border text-sm font-medium transition <?= !$currentCategory ? 'bg-accent text-black border-accent' : 'border-white/20 hover:border-accent hover:text-accent' ?>">
                Semua
            </a>
            <?php foreach ($categories as $category): ?>
                <a href="<?= base_url('artikel?category=' . urlencode($category)) ?>" 
                   class="px-6 py-2 rounded-full border text-sm font-medium transition <?= $currentCategory === $category ? 'bg-accent text-black border-accent' : 'border-white/20 hover:border-accent hover:text-accent' ?>">
                    <?= esc($category) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Artikel Grid -->
<section class="py-12">
    <div class="container mx-auto px-6">
        <?php if (empty($artikelList)): ?>
            <div class="text-center py-12 text-gray-400">
                <i class="fas fa-search text-4xl mb-4"></i>
                <p>Tidak ada artikel yang ditemukan.</p>
            </div>
        <?php else: ?>
            <div class="grid md:grid-cols-3 gap-6">
                <?php foreach ($artikelList as $artikel): ?>
                    <?= view('partials/cards/artikel_card', ['artikel' => $artikel]) ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?= $this->endSection() ?>
