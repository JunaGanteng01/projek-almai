<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<section class="relative pt-32 pb-16 overflow-hidden bg-black min-h-[60vh] flex items-center">
    <!-- Grid Pattern Overlay -->
    <div class="absolute inset-0 bg-grid z-[1] pointer-events-none opacity-50"></div>
    
    <!-- Glow Effect -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[500px] bg-accent/20 blur-[120px] rounded-full pointer-events-none z-[1]"></div>
    
    <div class="container mx-auto px-6 relative z-10 text-center mt-10">
        <div class="inline-block px-4 py-1.5 mb-6 text-xs font-semibold tracking-widest uppercase text-accent border border-accent/40 rounded-full bg-accent/10 backdrop-blur-md">
            Mitra & Ekosistem
        </div>
        <h1 class="text-4xl md:text-6xl font-black mb-6 text-white leading-tight">
            Asosiasi & <span class="text-accent">Kemitraan</span>
        </h1>
        <p class="text-xl text-gray-400 max-w-2xl mx-auto">
            Daftar asosiasi dan institusi yang bekerja sama dengan Almai dalam membangun ekosistem trading dan investasi yang sehat, legal, dan profesional di Indonesia.
        </p>
    </div>
</section>

<!-- Content Section -->
<section class="py-16 bg-[#050505] relative overflow-hidden">
    <div class="container mx-auto px-6">
        <div class="text-center py-12 text-gray-400 border border-white/10 rounded-2xl bg-[#0a0a0a]">
            <i class="fas fa-handshake text-5xl mb-4 text-accent/50"></i>
            <h3 class="text-2xl font-bold text-white mb-2">Halaman Sedang Dalam Pengembangan</h3>
            <p>Informasi detail mengenai Asosiasi dan Mitra kami akan segera hadir di sini.</p>
            <br>
            <a href="<?= base_url() ?>" class="inline-block px-6 py-3 bg-accent text-black font-bold rounded-full hover:bg-white transition">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
