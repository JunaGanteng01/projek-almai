<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero -->
<section class="pt-32 pb-20 relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-accent/10 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-purple-500/10 rounded-full blur-[100px] translate-y-1/2 -translate-x-1/2"></div>
    </div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="max-w-4xl mx-auto text-center mb-16" data-aos="fade-up">
            <h1 class="text-4xl md:text-6xl font-bold tracking-tighter mb-6 relative inline-block">
                Halaman <span class="text-accent underline decoration-4 underline-offset-8">Almai</span>
                <i class="fas fa-sparkles text-2xl text-yellow-400 absolute -top-6 -right-8 animate-pulse"></i>
            </h1>
            <p class="text-lg text-gray-400">
                Pusat informasi dan referensi lengkap untuk mendukung perjalanan trading Anda.
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Glosarium Card -->
            <a href="<?= base_url('glosarium') ?>" class="group bg-[#111] border border-white/10 rounded-2xl p-8 hover:bg-white/5 hover:border-accent transition duration-500 relative overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                <div class="absolute top-0 right-0 w-32 h-32 bg-accent/10 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2 group-hover:bg-accent/20 transition duration-500"></div>
                <div class="relative z-10 text-center">
                    <div class="w-20 h-20 bg-accent/10 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition duration-500">
                        <i class="fas fa-book text-4xl text-accent"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-3 group-hover:text-accent transition">Glosarium</h3>
                    <p class="text-gray-400 text-sm mb-6">
                        Kamus lengkap istilah-istilah dalam dunia trading dan investasi. Pelajari definisi dari A sampai Z.
                    </p>
                    <span class="inline-flex items-center text-accent font-bold group-hover:translate-x-2 transition">
                        Buka Glosarium <i class="fas fa-arrow-right ml-2"></i>
                    </span>
                </div>
            </a>

            <!-- Kalender Ekonomi Card -->
            <a href="<?= base_url('kalender-ekonomi') ?>" class="group bg-[#111] border border-white/10 rounded-2xl p-8 hover:bg-white/5 hover:border-blue-500 transition duration-500 relative overflow-hidden" data-aos="fade-up" data-aos-delay="200">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2 group-hover:bg-blue-500/20 transition duration-500"></div>
                <div class="relative z-10 text-center">
                    <div class="w-20 h-20 bg-blue-500/10 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition duration-500">
                        <i class="fas fa-calendar-alt text-4xl text-blue-500"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-3 group-hover:text-blue-500 transition">Kalender Ekonomi</h3>
                    <p class="text-gray-400 text-sm mb-6">
                        Jadwal rilis data ekonomi penting yang mempengaruhi pasar keuangan global.
                    </p>
                    <span class="inline-flex items-center text-blue-500 font-bold group-hover:translate-x-2 transition">
                        Lihat Kalender <i class="fas fa-arrow-right ml-2"></i>
                    </span>
                </div>
            </a>

            <!-- Almai Poin Card -->
            <a href="<?= base_url('almai-poin') ?>" class="group bg-[#111] border border-white/10 rounded-2xl p-8 hover:bg-white/5 hover:border-green-500 transition duration-500 relative overflow-hidden" data-aos="fade-up" data-aos-delay="300">
                <div class="absolute top-0 right-0 w-32 h-32 bg-green-500/10 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2 group-hover:bg-green-500/20 transition duration-500"></div>
                <div class="relative z-10 text-center">
                    <div class="w-20 h-20 bg-green-500/10 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition duration-500">
                        <i class="fas fa-coins text-4xl text-green-500"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-3 group-hover:text-green-500 transition">Almai Poin</h3>
                    <p class="text-gray-400 text-sm mb-6">
                        Informasi mengenai sistem reward poin Almai, cara mendapatkan, dan menukarnya.
                    </p>
                    <span class="inline-flex items-center text-green-500 font-bold group-hover:translate-x-2 transition">
                        Buka Almai Poin <i class="fas fa-arrow-right ml-2"></i>
                    </span>
                </div>
            </a>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
