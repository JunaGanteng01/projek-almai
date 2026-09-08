<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<section class="relative pt-32 pb-20 overflow-hidden bg-[#050505]">
    <div class="absolute inset-0 bg-grid z-0 pointer-events-none opacity-20"></div>
    <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-accent/5 rounded-full blur-[150px] pointer-events-none"></div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="text-center max-w-4xl mx-auto mb-16" data-aos="fade-up">
            <span class="text-accent font-bold tracking-widest uppercase text-sm mb-4 block">Transparansi & Kepatuhan</span>
            <h1 class="text-4xl md:text-6xl font-black text-white mb-6 leading-tight">
                LEGALITAS & <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-emerald-500">IZIN USAHA</span>
            </h1>
            <p class="text-gray-400 text-lg leading-relaxed">
                Platform kami beroperasi di bawah pengawasan ketat regulator Indonesia untuk menjamin keamanan dan kenyamanan Anda. Berikut adalah daftar legalitas resmi yang kami miliki.
            </p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($documents as $index => $doc): ?>
                <div class="bg-[#111] border border-white/5 rounded-3xl overflow-hidden hover:border-accent/30 transition-all duration-300 group h-full flex flex-col" data-aos="fade-up" data-aos-delay="<?= $index * 100 ?>">
                    <!-- Document Preview Area - Image Only -->
                    <div class="relative w-full overflow-hidden bg-[#0a0a0a] group-hover:bg-[#1a1a1a] transition-colors" style="aspect-ratio: 1414/2000;">
                        <!-- Full Image -->
                        <img src="<?= base_url('images/legalitas/' . $doc['image']) ?>"
                            alt="<?= esc($doc['title']) ?>"
                            class="w-full h-full object-cover object-top transition-transform duration-700 group-hover:scale-105"
                            onerror="this.src='https://placehold.co/1414x2000/000000/33e818?text=<?= urlencode($doc['title']) ?>&font=roboto'">

                        <!-- Overlay Badge -->
                        <div class="absolute top-4 right-4 bg-accent/20 text-accent border border-accent/20 px-3 py-1 rounded-full text-xs font-bold uppercase backdrop-blur-sm z-10">
                            Resmi
                        </div>
                    </div>

                    <!-- Content Below Image -->
                    <div class="p-6 md:p-8 flex-1 flex flex-col border-t border-white/5 bg-[#111]">
                        <h3 class="text-xl font-bold text-white mb-3 leading-tight group-hover:text-accent transition-colors">
                            <?= esc($doc['title']) ?>
                        </h3>

                        <?php if (!empty($doc['number']) && $doc['number'] !== '-'): ?>
                            <div class="flex items-center gap-2 mb-4 text-gray-400 text-sm font-mono bg-white/5 inline-flex px-3 py-1.5 rounded-lg w-fit">
                                <i class="fas fa-barcode text-accent"></i>
                                <?= esc($doc['number']) ?>
                            </div>
                        <?php endif; ?>

                        <p class="text-gray-400 text-sm leading-relaxed">
                            <?= esc($doc['description']) ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

      

    </div>
</section>

<!-- CSS to hide broken images in footer nicely -->
<style>
    img.onerror-hidden[src*="png"] {
        display: block;
        /* Placeholder behavior handled by alt text or just hide if missing */
    }

    img:not([src]):not([srcset]) {
        visibility: hidden;
    }
</style>

<script>
    // Simple script to handle broken validation logos by hiding them or showing text
    document.querySelectorAll('.onerror-hidden').forEach(img => {
        img.onerror = function() {
            this.style.display = 'none';
        };
    });
</script>
<?= $this->endSection() ?>