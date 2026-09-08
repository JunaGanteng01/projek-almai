<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<section class="relative min-h-[40vh] flex items-center justify-center pt-20 overflow-hidden bg-gradient-to-br from-black via-gray-900 to-black">
    <!-- Grid Pattern Overlay -->
    <div class="absolute inset-0 bg-grid z-[1] pointer-events-none"></div>

    <!-- Glow Effect -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-accent/20 blur-[120px] rounded-full pointer-events-none z-[1]"></div>

    <!-- Content -->
    <div class="container mx-auto px-6 relative z-10 text-center py-16">
        <p class="text-accent font-bold tracking-widest uppercase text-sm mb-4" data-aos="fade-up">
            Kepercayaan Klien
        </p>
        <h1 class="text-4xl md:text-6xl font-bold tracking-tighter mb-6" data-aos="fade-up" data-aos-delay="100">
            Rekomendasi
        </h1>
        <p class="text-gray-400 max-w-2xl mx-auto text-lg" data-aos="fade-up" data-aos-delay="200">
           Dengan ini diberikan rekomendasi dan izin penggunaan atas aplikasi sistem Expert Advisor yang dikembangkan oleh WPA bersertifikat, untuk digunakan sesuai dengan ketentuan dan standar yang berlaku.
        </p>
    </div>
</section>

<!-- Gallery Section -->
<section class="py-24 relative">
    <div class="container mx-auto px-6">
        <?php if (empty($images)): ?>
            <!-- Empty State -->
            <div class="text-center py-20" data-aos="fade-up">
                <div class="w-24 h-24 bg-accent/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-images text-4xl text-accent"></i>
                </div>
                <h3 class="text-2xl font-bold mb-3">Belum Ada Rekomendasi</h3>
                <p class="text-gray-400">Rekomendasi dan testimoni akan segera ditampilkan di sini</p>
            </div>
        <?php else: ?>
            <!-- Masonry Grid Gallery -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach ($images as $index => $image): ?>
                    <div class="group relative overflow-hidden rounded-2xl border border-white/10 hover:border-accent/50 transition-all duration-300 cursor-pointer bg-[#111]"
                        data-aos="fade-up"
                        data-aos-delay="<?= $index * 50 ?>"
                        onclick="openLightbox(<?= $index ?>)">
                        <!-- Image Container with aspect ratio -->
                        <div class="relative aspect-[3/4] overflow-hidden">
                            <img src="<?= $image['url'] ?>"
                                alt="Rekomendasi <?= $index + 1 ?>"
                                class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500"
                                loading="lazy">
                            <!-- Overlay on hover -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6">
                                <div class="text-white">
                                    <p class="text-sm font-medium flex items-center gap-2">
                                        <i class="fas fa-search-plus text-accent"></i>
                                        Klik untuk memperbesar
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Stats -->
           
        <?php endif; ?>
    </div>
</section>

<!-- Lightbox Modal -->
<div id="lightbox" class="fixed inset-0 bg-black/95 z-50 hidden items-center justify-center p-4" onclick="closeLightbox()">
    <button class="absolute top-4 right-4 text-white/70 hover:text-white text-4xl w-12 h-12 flex items-center justify-center transition z-10" onclick="closeLightbox()">
        <i class="fas fa-times"></i>
    </button>

    <!-- Previous Button -->
    <button class="absolute left-4 top-1/2 -translate-y-1/2 text-white/70 hover:text-accent text-4xl w-12 h-12 flex items-center justify-center bg-black/50 rounded-full hover:bg-black/70 transition z-10" onclick="event.stopPropagation(); previousImage()">
        <i class="fas fa-chevron-left"></i>
    </button>

    <!-- Next Button -->
    <button class="absolute right-4 top-1/2 -translate-y-1/2 text-white/70 hover:text-accent text-4xl w-12 h-12 flex items-center justify-center bg-black/50 rounded-full hover:bg-black/70 transition z-10" onclick="event.stopPropagation(); nextImage()">
        <i class="fas fa-chevron-right"></i>
    </button>

    <!-- Image Container -->
    <div class="relative max-w-6xl max-h-[90vh] flex items-center justify-center" onclick="event.stopPropagation()">
        <img id="lightbox-image" src="" alt="Rekomendasi" class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl">

        <!-- Counter -->
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-black/70 px-4 py-2 rounded-full text-white text-sm">
            <span id="lightbox-counter"></span>
        </div>
    </div>
</div>

<script>
    const images = <?= json_encode(array_column($images, 'url')) ?>;
    let currentIndex = 0;

    function openLightbox(index) {
        currentIndex = index;
        updateLightboxImage();
        document.getElementById('lightbox').classList.remove('hidden');
        document.getElementById('lightbox').classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox() {
        document.getElementById('lightbox').classList.add('hidden');
        document.getElementById('lightbox').classList.remove('flex');
        document.body.style.overflow = '';
    }

    function previousImage() {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        updateLightboxImage();
    }

    function nextImage() {
        currentIndex = (currentIndex + 1) % images.length;
        updateLightboxImage();
    }

    function updateLightboxImage() {
        document.getElementById('lightbox-image').src = images[currentIndex];
        document.getElementById('lightbox-counter').textContent = `${currentIndex + 1} / ${images.length}`;
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (document.getElementById('lightbox').classList.contains('flex')) {
            if (e.key === 'ArrowLeft') previousImage();
            if (e.key === 'ArrowRight') nextImage();
            if (e.key === 'Escape') closeLightbox();
        }
    });
</script>

<?= $this->endSection() ?>