<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Hero -->
<section class="relative pt-32 pb-12 overflow-hidden">
    <div class="absolute inset-0 bg-grid z-0 pointer-events-none"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-accent/20 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="container mx-auto px-6 relative z-10 text-center">
        <p class="text-accent font-bold tracking-widest text-sm mb-4 uppercase">Video Edukasi Singkat</p>
        <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tighter">ALMAI <span class="text-accent">Shorts</span></h1>
        <p class="text-gray-400 max-w-2xl mx-auto text-sm">
            Tingkatkan pengetahuan trading dan investasi Anda melalui video singkat informatif.
        </p>
    </div>
</section>

<!-- Shorts Grid -->
<section class="py-12 relative bg-black">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 max-w-7xl mx-auto">
            
            <?php
            // $shorts passed from controller
            foreach ($shorts as $video):
            ?>
            <div class="bg-[#080808] border border-white/10 rounded-2xl overflow-hidden hover:border-accent/60 transition-all duration-300 shadow-lg group flex flex-col">
                <!-- Wrapper for 9:16 aspect ratio (YouTube Shorts) -->
                <div class="relative w-full bg-black cursor-pointer" style="padding-bottom: 177.77%;" onclick="playVideo(this, '<?= esc($video['id']) ?>')">
                    <!-- Thumbnail Image -->
                    <img src="<?= esc($video['thumbnail']) ?>" alt="<?= esc($video['title']) ?>" class="absolute inset-0 w-full h-full object-cover opacity-80 group-hover:opacity-100 transition-opacity">
                    <!-- Play Button Overlay -->
                    <div class="absolute inset-0 flex items-center justify-center">
                        <div class="w-16 h-16 bg-black/60 rounded-full flex items-center justify-center group-hover:bg-accent/80 transition-colors border border-white/20">
                            <i class="fas fa-play text-white text-xl ml-1"></i>
                        </div>
                    </div>
                </div>
                <div class="p-5 flex-1 flex flex-col justify-between">
                    <h3 class="text-white font-semibold text-sm leading-tight mb-2 group-hover:text-accent transition-colors"><?= esc($video['title']) ?></h3>
                    <p class="text-xs text-gray-500"><i class="fas fa-user-circle mr-1"></i> <?= esc($video['author']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>

        </div>
        
        <!-- Pagination Controls -->
        <?php if (isset($totalPages) && $totalPages > 1): ?>
        <div class="mt-12 flex justify-center items-center space-x-2">
            <?php if ($currentPage > 1): ?>
                <a href="?page=<?= $currentPage - 1 ?>" class="px-4 py-2 bg-[#111] text-gray-300 border border-white/10 rounded-lg hover:border-accent hover:text-accent transition-colors text-sm">&laquo; Prev</a>
            <?php endif; ?>

            <div class="flex space-x-1">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>" class="w-10 h-10 flex items-center justify-center rounded-lg text-sm transition-colors border <?= $i == $currentPage ? 'bg-accent text-black font-bold border-accent shadow-[0_0_15px_rgba(51,232,24,0.3)]' : 'bg-[#111] text-gray-300 border-white/10 hover:border-accent hover:text-accent' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
            </div>

            <?php if ($currentPage < $totalPages): ?>
                <a href="?page=<?= $currentPage + 1 ?>" class="px-4 py-2 bg-[#111] text-gray-300 border border-white/10 rounded-lg hover:border-accent hover:text-accent transition-colors text-sm">Next &raquo;</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    </div>
</section>

<!-- Script to handle lazy load iframe -->
<script>
function playVideo(element, videoId) {
    element.innerHTML = `<iframe class="absolute top-0 left-0 w-full h-full" src="https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0&modestbranding=1" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>`;
}
</script>
<?= $this->endSection() ?>
