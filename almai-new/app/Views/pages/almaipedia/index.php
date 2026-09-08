<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Hero -->
<section class="relative pt-32 pb-16 overflow-hidden">
    <div class="absolute inset-0 bg-grid z-0 pointer-events-none"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-accent/20 blur-[120px] rounded-full pointer-events-none"></div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center max-w-3xl mx-auto" data-aos="fade-up">
            <span class="inline-block px-4 py-2 bg-accent/10 border border-accent/30 rounded-full text-accent text-sm font-medium mb-4">
                <i class="fas fa-book-open mr-2"></i>Glosarium Trading
            </span>
            <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tighter">
                Almai <span class="text-accent">Dex</span>
            </h1>
            <p class="text-gray-400 text-lg mb-8">
                Kamus lengkap istilah-istilah dalam dunia trading dan perdagangan berjangka. 
                Pelajari <?= $totalTerms ?>+ istilah penting untuk meningkatkan pemahaman Anda.
            </p>
            
            <!-- Search -->
            <form action="<?= base_url('almaidex') ?>" method="GET" class="max-w-xl mx-auto">
                <div class="relative">
                    <input type="text" name="search" value="<?= esc($searchQuery ?? '') ?>" 
                           placeholder="Cari istilah trading..." 
                           class="w-full px-6 py-4 pl-14 bg-[#111] border border-white/10 rounded-full text-white placeholder-gray-500 focus:border-accent focus:outline-none transition">
                    <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-gray-500"></i>
                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 px-6 py-2 bg-accent text-black font-bold rounded-full hover:bg-white transition">
                        Cari
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Alphabet Navigation -->
<section class="py-4 sticky top-20 z-40 bg-black/80 backdrop-blur-md border-y border-white/10">
    <div class="container mx-auto px-6">
        <div class="flex flex-wrap justify-center gap-2">
            <a href="<?= base_url('almaidex') ?>" 
               class="px-3 py-2 rounded-lg text-sm font-medium transition <?= empty($currentLetter) && empty($searchQuery) ? 'bg-accent text-black' : 'bg-white/5 hover:bg-white/10' ?>">
                Semua
            </a>
            <?php foreach ($allLetters as $letter): ?>
            <a href="<?= base_url('almaidex?letter=' . $letter) ?>" 
               class="px-3 py-2 rounded-lg text-sm font-medium transition <?= $currentLetter === $letter ? 'bg-accent text-black' : 'bg-white/5 hover:bg-white/10' ?>">
                <?= $letter ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Glosarium Content -->
<section class="py-12">
    <div class="container mx-auto px-6">
        <?php if ($searchQuery && empty($glosarium)): ?>
            <div class="text-center py-16">
                <i class="fas fa-search text-6xl text-gray-700 mb-4"></i>
                <h3 class="text-xl font-bold mb-2">Tidak ditemukan</h3>
                <p class="text-gray-400">Tidak ada istilah yang cocok dengan "<?= esc($searchQuery) ?>"</p>
                <a href="<?= base_url('almaidex') ?>" class="inline-block mt-4 px-6 py-2 bg-accent text-black font-bold rounded-full hover:bg-white transition">
                    Lihat Semua
                </a>
            </div>
        <?php else: ?>
            <?php if ($searchQuery): ?>
                <div class="mb-8 p-4 bg-accent/10 border border-accent/30 rounded-xl">
                    <p class="text-sm">
                        <i class="fas fa-info-circle text-accent mr-2"></i>
                        Menampilkan hasil pencarian untuk "<span class="text-accent font-bold"><?= esc($searchQuery) ?></span>"
                        <a href="<?= base_url('almaidex') ?>" class="ml-2 text-accent hover:underline">Reset</a>
                    </p>
                </div>
            <?php endif; ?>
            
            <div class="space-y-12">
                <?php foreach ($glosarium as $letter => $terms): ?>
                <div id="letter-<?= $letter ?>" data-aos="fade-up">
                    <!-- Letter Header -->
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 bg-accent/20 border border-accent/30 rounded-2xl flex items-center justify-center">
                            <span class="text-3xl font-bold text-accent"><?= $letter ?></span>
                        </div>
                        <div class="flex-1 h-px bg-gradient-to-r from-accent/50 to-transparent"></div>
                        <span class="text-sm text-gray-500"><?= count($terms) ?> istilah</span>
                    </div>
                    
                    <!-- Terms Grid -->
                    <div class="grid md:grid-cols-2 gap-4">
                        <?php foreach ($terms as $term): ?>
                        <div class="group bg-[#111] border border-white/10 rounded-xl p-5 hover:border-accent/50 transition-all duration-300">
                            <h3 class="font-bold text-lg mb-2 group-hover:text-accent transition">
                                <?= esc($term['term']) ?>
                            </h3>
                            <p class="text-gray-400 text-sm leading-relaxed">
                                <?= esc($term['definition']) ?>
                            </p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Quick Links -->
<section class="py-16 bg-[#111] border-t border-white/10">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-2xl font-bold mb-2">Pelajari Lebih Lanjut</h2>
            <p class="text-gray-400">Tingkatkan pengetahuan trading Anda dengan sumber belajar dari Almai</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-6" data-aos="fade-up" data-aos-delay="100">
            <a href="<?= base_url('kelas') ?>" class="group bg-black border border-white/10 rounded-2xl p-6 hover:border-accent/50 transition-all duration-300">
                <div class="w-14 h-14 bg-accent/20 rounded-xl flex items-center justify-center mb-4 group-hover:bg-accent/30 transition">
                    <i class="fas fa-graduation-cap text-accent text-2xl"></i>
                </div>
                <h3 class="font-bold text-lg mb-2 group-hover:text-accent transition">Kelas Trading</h3>
                <p class="text-gray-400 text-sm">Ikuti kelas dari WPA bersertifikat untuk belajar trading secara terstruktur.</p>
            </a>
            
            <a href="<?= base_url('tools') ?>" class="group bg-black border border-white/10 rounded-2xl p-6 hover:border-accent/50 transition-all duration-300">
                <div class="w-14 h-14 bg-accent/20 rounded-xl flex items-center justify-center mb-4 group-hover:bg-accent/30 transition">
                    <i class="fas fa-tools text-accent text-2xl"></i>
                </div>
                <h3 class="font-bold text-lg mb-2 group-hover:text-accent transition">Tools Trading</h3>
                <p class="text-gray-400 text-sm">Gunakan tools profesional untuk membantu analisis dan keputusan trading.</p>
            </a>
            
            <a href="<?= base_url('artikel') ?>" class="group bg-black border border-white/10 rounded-2xl p-6 hover:border-accent/50 transition-all duration-300">
                <div class="w-14 h-14 bg-accent/20 rounded-xl flex items-center justify-center mb-4 group-hover:bg-accent/30 transition">
                    <i class="fas fa-newspaper text-accent text-2xl"></i>
                </div>
                <h3 class="font-bold text-lg mb-2 group-hover:text-accent transition">Artikel & Analisis</h3>
                <p class="text-gray-400 text-sm">Baca artikel dan analisis pasar terbaru dari tim analis Almai.</p>
            </a>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
