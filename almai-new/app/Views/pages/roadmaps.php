<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    .timeline-arrow { clip-path: polygon(0% 0%, 90% 0%, 100% 50%, 90% 100%, 0% 100%); }
    .timeline-arrow-mid { clip-path: polygon(0% 50%, 10% 0%, 90% 0%, 100% 50%, 90% 100%, 10% 100%); }
    .scrollbar-thin::-webkit-scrollbar { height: 4px; }
    .scrollbar-thin::-webkit-scrollbar-track { bg: #000; }
    .scrollbar-thin::-webkit-scrollbar-thumb { background: #333; border-radius: 10px; }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .roadmap-scroll {
        -webkit-overflow-scrolling: touch;
        scroll-behavior: smooth;
        overscroll-behavior-x: contain;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<section class="relative pt-32 pb-16 overflow-hidden bg-black">
    <!-- Grid Pattern Overlay -->
    <div class="absolute inset-0 bg-grid z-[1] pointer-events-none opacity-50"></div>
    
    <!-- Glow Effect -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[400px] bg-accent/5 blur-[120px] rounded-full pointer-events-none z-[1]"></div>

    <div class="container mx-auto px-6 relative z-10 text-center">
        <div class="max-w-4xl mx-auto" data-aos="fade-up">
            <span class="inline-block px-4 py-1.5 bg-accent/10 border border-accent/20 rounded-full text-accent text-[10px] font-black uppercase tracking-[0.2em] mb-6">Future Vision</span>
            <h1 class="text-2xl md:text-4xl font-black leading-tight tracking-tighter text-white uppercase mb-8">
                ALMAI <span class="text-accent underline underline-offset-8 decoration-accent/30">ROADMAPS</span>
            </h1>
            
            <!-- Dedicated Video Player -->
            <div class="mt-12 mb-16 max-w-5xl mx-auto rounded-[2.5rem] overflow-hidden border border-white/10 shadow-2xl bg-[#0a0a0a] group" data-aos="zoom-in">
                <div class="aspect-video relative bg-black">
                    <iframe class="w-full h-full" src="https://www.youtube.com/embed/quEwM08pMOE?autoplay=1&si=r2a0SKDed5HDu0kL" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                    
                    <!-- Small Glassmorphism Label -->
                    <div class="absolute top-4 right-4 px-3 py-1 bg-black/40 backdrop-blur-md rounded-full border border-white/10 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                        <span class="text-[8px] text-white/60 font-medium tracking-[0.2em] uppercase">Official Preview</span>
                    </div>
                </div>
            </div>

            <p class="text-lg md:text-xl text-gray-400 max-w-2xl mx-auto leading-relaxed">
                Rencana strategis pengembangan ekosistem literasi dan teknologi perdagangan berjangka di Indonesia hingga tahun 2030.
            </p>
        </div>
    </div>
</section>

<!-- Timeline Section -->
<div class="relative bg-black py-24 overflow-hidden">
    <div class="container mx-auto px-4">
        
<!-- Roadmap Visual Wrapper (Iframe-style Scroll) -->
<div class="relative mt-10 flex justify-center">
    
    <!-- Scrollable Container -->
    <div class="relative overflow-x-auto pb-6 scrollbar-thin roadmap-scroll 
                rounded-[2rem] md:rounded-[2.5rem] 
                border border-white/10 bg-black shadow-2xl 
                transition-all hover:border-accent/30
                max-w-5xl mx-auto">

        <div class="min-w-[800px] md:min-w-[1200px] 
                    h-[350px] md:h-auto 
                    cursor-zoom-in group flex justify-center" 
             id="roadmapTrigger">

            <img src="<?= base_url('images/roadmap.jpg') ?>" 
                 alt="Almai Strategic Roadmap 2030" 
                 class="w-auto max-w-full h-full md:h-auto 
                        object-contain 
                        transition-transform duration-700 
                        group-hover:scale-[1.01]">
        </div>

    </div>
</div>

            <!-- Gradient Overlays for Scroll Hint -->
            <div class="absolute inset-y-0 left-0 w-20 bg-gradient-to-r from-black to-transparent pointer-events-none"></div>
            <div class="absolute inset-y-0 right-0 w-20 bg-gradient-to-l from-black to-transparent pointer-events-none"></div>
        </div>

            </div>
        </div>

        <!-- Vision Bottom Section -->
        <div class="mt-32 grid grid-cols-1 md:grid-cols-3 gap-10" data-aos="fade-up">
            <div class="p-10 bg-white/[0.02] border border-white/5 rounded-[2.5rem] hover:bg-white/[0.05] transition-all group">
                <div class="w-14 h-14 bg-accent/10 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-accent/20 transition-colors">
                    <i class="fas fa-book-open text-accent text-3xl"></i>
                </div>
                <h3 class="text-white font-black uppercase text-base mb-4 tracking-widest">Pendidikan Global</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Membangun kurikulum trading standar internasional yang mudah diakses untuk menciptakan kemandirian finansial masyarakat.</p>
            </div>
            <div class="p-10 bg-white/[0.02] border border-white/5 rounded-[2.5rem] hover:bg-white/[0.05] transition-all group border-accent/20">
                <div class="w-14 h-14 bg-accent/20 rounded-2xl flex items-center justify-center mb-8">
                    <i class="fas fa-shield-halved text-accent text-3xl"></i>
                </div>
                <h3 class="text-white font-black uppercase text-base mb-4 tracking-widest">Legalitas Utama</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Prioritas mutlak pada kepatuhan regulasi OJK & BAPPEBTI demi menjaga integritas ekosistem perdagangan berjangka.</p>
            </div>
            <div class="p-10 bg-white/[0.02] border border-white/5 rounded-[2.5rem] hover:bg-white/[0.05] transition-all group">
                <div class="w-14 h-14 bg-accent/10 rounded-2xl flex items-center justify-center mb-8 group-hover:bg-accent/20 transition-colors">
                    <i class="fas fa-network-wired text-accent text-3xl"></i>
                </div>
                <h3 class="text-white font-black uppercase text-base mb-4 tracking-widest">Digital Ecosystem</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Pemanfaatan AI, Big Data, dan Blockchain untuk menghadirkan transparansi luar biasa dalam setiap aspek layanan Almai.</p>
            </div>
        </div>

    </div>
</div>

    </div>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    }
</script>

<!-- Lightbox Modal -->
<div id="roadmapLightbox" class="fixed inset-0 z-[9999] bg-black/98 backdrop-blur-2xl hidden opacity-0 transition-opacity duration-500 flex items-center justify-center p-0 md:p-10">
    <button id="closeLightbox" class="absolute top-6 right-6 w-12 h-12 bg-white/10 border border-white/20 rounded-full text-white flex items-center justify-center hover:bg-white hover:text-black transition-all z-[10000]">
        <i class="fas fa-times text-xl"></i>
    </button>
    <div class="w-full h-full overflow-auto flex items-start justify-center cursor-zoom-out">
        <img src="<?= base_url('images/roadmap.jpg') ?>" id="lightboxImg" class="max-w-none w-[300%] md:w-auto h-auto md:h-full object-contain shadow-2xl" alt="Full Roadmap">
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const trigger = document.getElementById('roadmapTrigger');
        const modal = document.getElementById('roadmapLightbox');
        const closeBtn = document.getElementById('closeLightbox');
        const lightboxImg = document.getElementById('lightboxImg');

        if(trigger && modal) {
            trigger.addEventListener('click', () => {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.replace('opacity-0', 'opacity-100');
                }, 10);
                document.body.style.overflow = 'hidden'; 
            });

            const closeModal = () => {
                modal.classList.replace('opacity-100', 'opacity-0');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 500);
                document.body.style.overflow = 'auto'; 
            };

            closeBtn.addEventListener('click', closeModal);
            modal.addEventListener('click', (e) => {
                if(e.target !== lightboxImg) closeModal();
            });
            
            // Support ESC key
            document.addEventListener('keydown', (e) => {
                if(e.key === 'Escape') closeModal();
            });
        }
    });
</script>
<?= $this->endSection() ?>

