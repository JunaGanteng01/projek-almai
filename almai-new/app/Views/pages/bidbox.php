<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    /* Marquee Styles */
    .marquee-container {
        overflow: hidden;
        width: 100%;
        position: relative;
    }

    .marquee-track {
        display: flex;
        width: max-content;
        animation: marquee 28s linear infinite;
    }

    .marquee-content {
        display: flex;
        align-items: center;
        gap: 3rem;
        padding-right: 3rem;
        white-space: nowrap;
    }

    @keyframes marquee {
        from {
            transform: translateX(0);
        }

        to {
            transform: translateX(-50%);
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Hero Section with Video Background -->
<section class="relative min-h-screen flex flex-col pt-20 overflow-hidden">
    <!-- Video Background -->
    <div class="absolute inset-0 z-0">
        <video autoplay muted loop playsinline class="w-full h-full object-cover">
            <source src="<?= base_url('images/home.mp4') ?>" type="video/mp4">
        </video>
        <!-- Dark Overlay -->
        <div class="absolute inset-0 bg-black/70"></div>
    </div>

    <!-- Grid Pattern Overlay -->
    <div class="absolute inset-0 bg-grid z-[1] pointer-events-none"></div>

    <!-- Glow Effect -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-accent/20 blur-[120px] rounded-full pointer-events-none z-[1]"></div>

    <!-- Content Wrapper -->
    <div class="flex-1 flex items-center justify-center relative z-10 px-0 sm:px-6">
        <div class="w-full max-w-none text-center px-4 sm:px-10 md:px-16 lg:px-20">
            <div class="flex flex-col items-center translate-y-8 sm:translate-y-10 md:translate-y-12">
                <br><br><br>
                <!-- Badge -->
                <div class="mb-4">
                    <span class="px-5 py-2 text-xs md:text-sm font-semibold tracking-widest text-green-400 border border-green-500 rounded-full bg-black/60 backdrop-blur">
                        ✦ PLATFORM PENASIHAT
                    </span>
                </div>

                <!-- Main Combined Title -->
                <h1 class="font-black tracking-tighter text-white uppercase text-center flex flex-col items-center mt-6 md:mt-10">
                    <?php
                    $fontSize = "clamp(1.8rem, 6.5vw, 4.2rem)";
                    $lineHeight = "1.05";
                    ?>

                    <span class="block drop-shadow-lg"
                        style="font-size: <?= $fontSize ?>; line-height: <?= $lineHeight ?>;">
                        Derivatif Keuangan
                    </span>

                    <span
                        class="block drop-shadow-lg text-transparent bg-clip-text bg-gradient-to-r from-accent via-green-400 to-emerald-500"
                        style="font-size: <?= $fontSize ?>; line-height: <?= $lineHeight ?>;">
                        & Aset Digital
                    </span>
                </h1>

                <!-- Description -->
                <p class="text-xs sm:text-base md:text-lg text-gray-400 mt-6 max-w-4xl mx-auto leading-relaxed font-medium">
                    Platform resmi rujukan Trader Indonesia untuk pendampingan transaksi Derivatif, Forex dan Crypto bersama mentor profesional
                    bersertifikat— <span class="text-white font-semibold"> Wakil Penasihat Berjangka (WPA)</span>
                </p>

                <!-- Buttons Area -->
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-6 mt-12 md:mt-16">
                    <a href="referral/BIDBOX"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="w-full sm:w-auto px-6 py-3 sm:px-8 sm:py-4 text-sm sm:text-base bg-accent/10 text-accent font-bold rounded-xl border border-accent/20 hover:bg-accent hover:text-black transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-search-dollar"></i> Daftar User
                    </a>

                    <a href="https://almai.id/daftar-wpa" target="_blank"
                       class="w-full sm:w-auto px-6 py-3 sm:px-10 sm:py-4 bg-white/5 border border-white/10 text-white font-bold text-sm rounded-xl hover:bg-white/10 transition backdrop-blur-md flex items-center justify-center gap-2 tracking-tighter sm:tracking-widest">
                        <i class="fas fa-graduation-cap text-xs"></i>
                        <span class="whitespace-nowrap">Daftar Menjadi WPA</span>
                    </a>
                </div>

                <!-- Balanced Spacer -->
                <div class="h-10 md:h-16"></div>
            </div>
        </div>
    </div>

    <!-- Marquee (Inside Hero Bottom) -->
    <div class="py-4 sm:py-6 border-t border-white/10 bg-black/40 backdrop-blur-md overflow-hidden relative z-10 w-full">
        <div class="marquee-container">
            <div class="marquee-track">
                <!-- SET 1 -->
                <div class="marquee-content">
                    <img src="<?= base_url('images/legal/bank-indonesia.png') ?>" alt="Bank Indonesia" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/ojk.png') ?>" alt="OJK" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/bappebti.svg') ?>" alt="BAPPEBTI" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/komdigi.png') ?>" alt="KOMDIGI" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/aspebtindo.avif') ?>" alt="ASPEBTINDO" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/cfx.webp') ?>" alt="CFX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/jfx.png') ?>" alt="JFX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/icdx.png') ?>" alt="ICDX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/lpk.png') ?>" alt="LPK PBK" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">

                    <!-- Duplicated to prevent cut-off on wide screens -->
                    <img src="<?= base_url('images/legal/bank-indonesia.png') ?>" alt="Bank Indonesia" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/ojk.png') ?>" alt="OJK" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/bappebti.svg') ?>" alt="BAPPEBTI" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/komdigi.png') ?>" alt="KOMDIGI" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/aspebtindo.avif') ?>" alt="ASPEBTINDO" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/cfx.webp') ?>" alt="CFX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/jfx.png') ?>" alt="JFX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/icdx.png') ?>" alt="ICDX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/lpk.png') ?>" alt="LPK PBK" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                </div>

                <!-- SET 2 -->
                <div class="marquee-content" aria-hidden="true">
                    <img src="<?= base_url('images/legal/bank-indonesia.png') ?>" alt="Bank Indonesia" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/ojk.png') ?>" alt="OJK" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/bappebti.svg') ?>" alt="BAPPEBTI" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/komdigi.png') ?>" alt="KOMDIGI" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/aspebtindo.avif') ?>" alt="ASPEBTINDO" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/cfx.webp') ?>" alt="CFX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/jfx.png') ?>" alt="JFX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/icdx.png') ?>" alt="ICDX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/lpk.png') ?>" alt="LPK PBK" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">

                    <img src="<?= base_url('images/legal/bank-indonesia.png') ?>" alt="Bank Indonesia" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/ojk.png') ?>" alt="OJK" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/bappebti.svg') ?>" alt="BAPPEBTI" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/komdigi.png') ?>" alt="KOMDIGI" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/aspebtindo.avif') ?>" alt="ASPEBTINDO" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/cfx.webp') ?>" alt="CFX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/jfx.png') ?>" alt="JFX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/icdx.png') ?>" alt="ICDX" class="h-7 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                    <img src="<?= base_url('images/legal/lpk.png') ?>" alt="LPK PBK" class="h-10 w-auto object-contain filter grayscale brightness-0 invert opacity-50 hover:opacity-100 transition-all duration-300">
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
