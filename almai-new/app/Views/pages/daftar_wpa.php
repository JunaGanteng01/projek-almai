<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
.phase-timeline-wpa {
    display: flex;
    gap: 12px;
}

/* ITEM */
.phase-item-wpa {
    position: relative;
    text-align: center;
}

/* CONNECTOR */
.phase-connector-wpa {
    position: absolute;
    top: 20px;
    right: -50%;
    width: 100%;
    height: 2px;
    background: #33e818;
    z-index: 0;
}

/* CIRCLE */
.phase-circle-wpa {
    width: 42px;
    height: 42px;
    border-radius: 999px;
    background: #000;
    border: 2px solid #33e818;
    box-shadow: 0 0 10px rgba(51,232,24,0.4);

    display: flex;
    align-items: center;
    justify-content: center;

    font-weight: bold;
    font-size: 13px;
    color: #33e818;

    margin: 0 auto;
    position: relative;
    z-index: 2;
}

/* TITLE */
.phase-title-wpa {
    font-size: 12px;
    margin-top: 8px;
    color: #ccc;
    line-height: 1.3;
}

/* ========================= */
/* DESKTOP */
/* ========================= */
@media (min-width: 1024px) {
    .phase-timeline-wpa {
        justify-content: space-between;
        overflow: visible;
    }

    .phase-item-wpa {
        flex: 1;
        min-width: auto;
    }
}

/* ========================= */
/* TABLET */
/* ========================= */
@media (max-width: 1023px) {
    .phase-timeline-wpa {
        overflow-x: auto;
        flex-wrap: nowrap;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
    }

    .phase-item-wpa {
        flex: 0 0 auto;
        min-width: 140px;
        scroll-snap-align: center;
    }

    .phase-timeline-wpa::-webkit-scrollbar {
        display: none;
    }
}

/* ========================= */
/* MOBILE FIX (INI KUNCI) */
/* ========================= */
@media (max-width: 640px) {

    .phase-timeline-wpa {
        gap: 8px;
        padding-left: 10px;
        padding-right: 10px;
    }

    .phase-item-wpa {
        min-width: 75%;   /* bikin 1 item dominan */
    }

    .phase-title-wpa {
        font-size: 11px;
    }

    /* OPTIONAL: connector dipendekin biar gak berantakan */
    .phase-connector-wpa {
        right: -40%;
    }
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center pt-20 overflow-hidden">
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

    <div class="container mx-auto px-4 sm:px-6 relative z-10 text-center">
<br><br>
        <!-- BADGE -->
        <div class="mb-4 sm:mb-6" data-aos="fade-up">
            <span class="inline-block px-4 py-1.5 sm:px-5 sm:py-2 text-[10px] sm:text-xs md:text-sm font-semibold tracking-widest uppercase text-accent border border-accent/40 rounded-full bg-accent/10 backdrop-blur-md shadow-[0_0_20px_rgba(51,232,24,0.2)]">
                Program Pendampingan
            </span>
        </div>

        <h1 class="text-8xl sm:text-9xl md:text-[12rem] lg:text-[16rem] font-black leading-none mb-1 sm:mb-2 lg:mb-3 tracking-tighter text-white" data-aos="fade-up">
            <span class="blur-text-container">
                <span>W</span><span>P</span><span>A</span>
            </span>
        </h1>

        <p class="text-base sm:text-lg md:text-2xl lg:text-3xl text-gray-300 max-w-2xl mx-auto -mt-4 sm:-mt-6 mb-2 sm:mb-4 lg:mb-6 leading-tight px-1 font-bold">
            WAKIL PENASIHAT BERJANGKA
        </p>

<p class="text-base sm:text-lg md:text-xl lg:text-2xl text-gray-300 max-w-3xl mx-auto mb-3 sm:mb-4 leading-tight px-1">
    <span class="text-white italic">
        "Saatnya Menjadi Penasihat Perdagangan Berjangka & Aset Keuangan Digital → Legal dan Terpercaya"
    </span>
</p>

<p class="relative text-xs sm:text-sm max-w-2xl mx-auto px-4 py-2 text-green-400 border border-green-500/40 rounded-lg text-center animate-pulse">
    ⚠️ Hanya untuk trader profesional yang ingin menjadi Penasihat Investasi secara legal
</p>
<br>

        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center items-center" data-aos="fade-up" data-aos-delay="200">
            <a href="<?= base_url('daftar-cwpa') ?>" class="px-6 sm:px-8 lg:px-10 py-3 sm:py-4 lg:py-5 bg-accent text-black font-bold text-sm sm:text-base lg:text-lg rounded-full hover:bg-white hover:scale-105 transition duration-300 shadow-[0_0_20px_rgba(51,232,24,0.4)]">
                Daftar Sekarang
            </a>
        </div>
    </div>
</section>

    </script>

    <!-- Scroll Indicator -->

</section>
<style>
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
<!-- SECTION 2 : MARQUE -->
<section class="py-6 sm:py-8 lg:py-10 border-y border-white/10 bg-black/50 backdrop-blur-sm overflow-hidden">
    <div class="marquee-container">
        <div class="marquee-track">

            <!-- SET 1 -->
            <div class="marquee-content">
                <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500 uppercase flex items-center gap-2">
                    <i class="fa-solid fa-building-columns"></i> BANK INDONESIA
                </span>
                <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500 uppercase flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved"></i> OJK
                </span>
                <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500 uppercase flex items-center gap-2">
                    <i class="fa-solid fa-landmark"></i> BAPPEBTI
                </span>
                <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500 uppercase flex items-center gap-2">
                    <i class="fa-solid fa-globe"></i> ASPEBTINDO
                </span>
                <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500 uppercase flex items-center gap-2">
                    <i class="fa-solid fa-landmark"></i> BNSP PBK
                </span>
                <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500 uppercase flex items-center gap-2">
                    <i class="fa-solid fa-globe"></i> LPK PBK
                </span>
            </div>

            <!-- SET 2 (DUPLIKAT WAJIB) -->
            <div class="marquee-content">
                <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500 uppercase flex items-center gap-2">
                    <i class="fa-solid fa-building-columns"></i> BANK INDONESIA
                </span>
                <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500 uppercase flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved"></i> OJK
                </span>
                <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500 uppercase flex items-center gap-2">
                    <i class="fa-solid fa-landmark"></i> BAPPEBTI
                </span>
                <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500 uppercase flex items-center gap-2">
                    <i class="fa-solid fa-globe"></i> KOMDIGI
                </span>
                <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500 uppercase flex items-center gap-2">
                    <i class="fa-solid fa-globe"></i> ASPEBTINDO
                </span>
                <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500 uppercase flex items-center gap-2">
                    <i class="fa-solid fa-landmark"></i> BNSP PBK
                </span>
                <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500 uppercase flex items-center gap-2">
                    <i class="fa-solid fa-globe"></i> LPK PBK
                </span>
            </div>
            <!-- SET 2 (DUPLIKAT WAJIB) -->
            <div class="marquee-content">
                <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500 uppercase flex items-center gap-2">
                    <i class="fa-solid fa-building-columns"></i> BANK INDONESIA
                </span>
                <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500 uppercase flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved"></i> OJK
                </span>
                <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500 uppercase flex items-center gap-2">
                    <i class="fa-solid fa-landmark"></i> BAPPEBTI
                </span>
                <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500 uppercase flex items-center gap-2">
                    <i class="fa-solid fa-globe"></i> KOMDIGI
                </span>
                <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500 uppercase flex items-center gap-2">
                    <i class="fa-solid fa-globe"></i> ASPEBTINDO
                </span>
                <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500 uppercase flex items-center gap-2">
                    <i class="fa-solid fa-landmark"></i> BNSP PBK
                </span>
                <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500 uppercase flex items-center gap-2">
                    <i class="fa-solid fa-globe"></i> LPK PBK
                </span>
            </div>

            <!-- SET 2 (DUPLIKAT WAJIB) -->
            <div class="marquee-content">
                <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500 uppercase flex items-center gap-2">
                    <i class="fa-solid fa-building-columns"></i> BANK INDONESIA
                </span>
                <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500 uppercase flex items-center gap-2">
                    <i class="fa-solid fa-shield-halved"></i> OJK
                </span>
                <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500 uppercase flex items-center gap-2">
                    <i class="fa-solid fa-landmark"></i> BAPPEBTI
                </span>
                <span class="text-base sm:text-xl lg:text-2xl font-bold text-gray-500 uppercase flex items-center gap-2">
                    <i class="fa-solid fa-globe"></i> KOMDIGI
                </span>
            </div>
        </div>
    </div>
</section>

<!-- WHY WPA SECTION -->
<section class="py-12 sm:py-16 lg:py-24 bg-[#050505] relative overflow-hidden">
    <div class="container mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 sm:gap-10 lg:gap-12 items-center">
            
            <!-- LEFT COLUMN: MASCOT & TITLE -->
            <div class="flex flex-row items-center justify-center md:justify-start gap-4 sm:gap-5 text-left" data-aos="fade-right">
                <img src="<?= base_url('images/angel.png') ?>" alt="Almai Mascot" class="w-20 h-20 sm:w-32 sm:h-32 md:w-40 md:h-40 lg:w-48 lg:h-48 object-contain">
                <div>
                    <p class="text-white text-base sm:text-xl md:text-2xl lg:text-3xl font-semibold leading-tight">Mengapa Harus</p>
                    <h2 class="text-xl sm:text-3xl md:text-4xl lg:text-5xl font-bold leading-tight">Menjadi <span class="text-accent">WPA</span></h2>
                    <p class="text-base sm:text-xl md:text-2xl lg:text-3xl font-semibold leading-tight">Melalui <span class="text-accent">Almai</span></p>
                </div>
            </div>

            <!-- RIGHT COLUMN: CONTENT BOX -->
            <div class="rounded-2xl sm:rounded-3xl p-4 sm:p-6 lg:p-8" data-aos="fade-left">
                <h4 class="text-lg sm:text-xl lg:text-2xl font-bold mb-1 text-white">
                    PT. ALMA INDONESIA RAYA
                </h4>
                <p class="text-accent text-sm sm:text-base lg:text-lg mb-4">
                    PENASIHAT BERJANGKA <br><strong><em>EXPERT ADVISOR</em></strong>
                </p>
                

                        <p>PT. Alma Indonesia Raya (Almai) didirikan pada tahun 2021 di Denpasar oleh tim berpengalaman global di bidang transaksi berjangka, meliputi komoditi, pasar uang, valuta asing, kripto, dan aset digital. Kami mengundang Anda para profesional untuk bertumbuh dalam ekosistem yang dibangun di atas 3 pilar utama:
                        </p>
                <blockquote class="text-[11px] sm:text-xs md:text-sm lg:text-base text-gray-400 max-w-full leading-tight lg:leading-snug px-4 py-3 border-l-2 border-green-500 text-left">
                    <div class="text-gray-300 text-xs sm:text-sm leading-relaxed italic">                        
                        <p class="mt-4">
                            <span class="text-accent"><b>|</b></span><span class="text-white font-bold">Trusted:</span> Bangun reputasi Anda di atas fondasi legalitas dan transparansi yang kokoh, memberikan kepastian hukum dalam mengelola portofolio klien secara profesional.
                        </p>
                        
                        <p class="mt-4">
                            <span class="text-accent"><b>|</b></span><span class="text-white font-bold">Specialist:</span> Melalui <span class="text-accent">almai.id</span>, Anda bebas mengembangkan personal brand dan menerbitkan layanan sesuai keahlian spesifik Anda sebagai penasihat investasi bersertifikat.
                        </p>
                        
                        <p class="mt-4">
                            <span class="text-accent"><b>|</b></span><span class="text-white font-bold">Efficient:</span> Tingkatkan produktivitas dengan dukungan teknologi Expert Advisor. Kami memastikan operasional berjalan sistematis agar Anda bisa fokus pada strategi dan klien.
                        </p>
                    </div>
                </blockquote>
            </div>
        </div>

<section class="py-12 sm:py-16 lg:py-24 bg-[#050505] relative overflow-hidden">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">

        <!-- TITLE -->
        <div class="mt-12 sm:mt-16 lg:mt-20">
            <div class="text-center mb-10 sm:mb-12" data-aos="fade-up">
                <p class="text-white text-base sm:text-xl md:text-2xl lg:text-3xl font-semibold leading-tight">Satu-Satunya</p>
                <h2 class="text-xl sm:text-3xl md:text-4xl lg:text-5xl font-bold leading-tight">
                    Penasihat <span class="text-accent">Berjangka</span>
                </h2>
                <p class="text-base sm:text-xl md:text-2xl lg:text-3xl font-semibold leading-tight">
                    Izin Terlengkap di <span class="text-accent">Indonesia</span>
                </p>
            </div>

<!-- TIMELINE -->
<div class="relative overflow-hidden p-4 space-y-10">

    <!-- JUDUL -->
    <div class="text-left mb-6">
        <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-white">
            Perizinan <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-white">PT. Alma Indonesia Raya</span>
        </h2>
    </div>

    <!-- ITEM 01 -->
    <div class="relative pl-14 sm:pl-16">
        <div class="absolute left-5 sm:left-6 -translate-x-1/2 top-1 
                    w-8 h-8 sm:w-10 sm:h-10 
                    flex items-center justify-center 
                    bg-black border-2 border-accent 
                    rounded-full shadow-[0_0_15px_rgba(51,232,24,0.5)]">
            <span class="text-accent font-semibold">01</span>
        </div>

        <div data-aos="fade-right">
            <h3 class="mb-2 font-bold text-base sm:text-lg lg:text-xl text-white">
                Penasihat <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-white"> Berjangka</span>
            </h3>

            <p class="text-accent font-bold text-xs sm:text-sm mb-2">
                BAPPEBTI NOMOR : 02/BAPPEBTI/SI-PNB/02/2024
            </p>

            <p class="text-accent font-bold text-xs sm:text-sm mb-2">
                Peraturan BAPPEBTI NOMOR 1 TAHUN 2023 perubahan NOMOR 6 TAHUN 2020.
            </p>

            <ul class="text-xs sm:text-sm leading-snug text-gray-400 space-y-1">
                <li><span class="text-accent font-bold">|</span> Pasal I (1) Penasihat Berjangka adalah Pihak yang memberikan nasihat kepada pihak lain mengenai jual beli Komoditi berdasarkan Kontrak Berjangka, Kontrak Derivatif Syariah dan/atau Kontrak Derivatif lainnya dengan menerima imbalan.</li>
                <li><span class="text-accent font-bold">|</span> Pasal I (2) Wakil Penasihat Berjangka adalah orang perseorangan yang berdasarkan kesepakatan dengan Penasihat Berjangka melaksanakan sebagian fungsi Penasihat Berjangka.</li>
                <li><span class="text-accent font-bold">|</span> Pasal 10 (4) Penasihat Berjangka wajib membuat perjanjian kerja dengan Wakil Penasihat Berjangka.</li>
                <li><span class="text-accent font-bold">|</span> Pasal 10 (7) Wakil Penasihat Berjangka tidak boleh bekerja untuk lebih dari satu badan usaha Penasihat Berjangka atau juga pada perusahaan lain yang bergerak di bidang Perdagangan Berjangka.</li>
                <li><span class="text-accent font-bold">|</span> Pasal 18 (1)Penasihat Berjangka wajib membuat dan menyampaikan laporan berkala terkait dengan keadaan dan perkembangan usaha dan/atau laporan sewaktu-waktu apabila diminta oleh Bappebti</li>
            </ul>
        </div>
    </div>

    <!-- ITEM 02 -->
    <div class="relative pl-14 sm:pl-16">
        <div class="absolute left-5 sm:left-6 -translate-x-1/2 top-1 
                    w-8 h-8 sm:w-10 sm:h-10 
                    flex items-center justify-center 
                    bg-black border-2 border-accent 
                    rounded-full shadow-[0_0_15px_rgba(51,232,24,0.5)]">
            <span class="text-accent font-semibold">02</span>
        </div>

        <div data-aos="fade-right">
            <h3 class="mb-2 font-bold text-base sm:text-lg lg:text-xl text-white">
                Penasihat Berjangka
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-white"> Expert Advisor</span>
            </h3>

            <p class="text-accent font-bold text-xs sm:text-sm mb-2">
                BAPPEBTI NOMOR : No: 01/BAPPEBTI/SP-PBEA/06/2024
            </p>


            </span>

            <ul class="text-xs sm:text-sm leading-relaxed text-gray-400 mb-4 space-y-2">
                <li>
                    <span class="text-accent font-bold">|</span> Nasihat Berbasis Teknologi Informasi berupa Expert Advisor (“EA”) adalah alat bantu berbasis Teknologi Informasi yang di dalamnya tersusun berdasarkan algoritma yang ditanamkan pada baris-baris programnya yang ditentukan berdasarkan karakteristik, tipe, kebutuhan, dan harapan Klien. 
                </li>
            </ul>

<div class="space-y-4 text-xs sm:text-sm">

    <!-- AIWE -->
    <div class="bg-white/5 border border-white/10 rounded-xl p-3">
        <div class="flex items-center gap-2 mb-1">
            <i class="fas fa-chart-line text-accent"></i>
            <p class="text-accent font-semibold">AIWE | Pasar Uang & Valuta Asing</p>
        </div>
        <p class="text-gray-300">BAPPEBTI: No. KB.00.00/60/BAPPEBTI/SD/02/2025</p>
        <p class="text-gray-300">Rekomendasi Bursa Komoditi JFX: No. L/JFX/DIR/08-24/525</p>
    </div>

    <!-- BIDBOX -->
    <div class="bg-white/5 border border-white/10 rounded-xl p-3">
        <div class="flex items-center gap-2 mb-1">
            <i class="fas fa-bitcoin text-yellow-400"></i>
            <p class="text-accent font-semibold">BIDBOX | KRIPTO</p>
        </div>
        <p class="text-gray-300">No: 01/BAPPEBTI/SP-PBEA/06/2024</p>
        <p class="text-gray-300">Rekomendasi Bursa Kripto CFX: No. CFX/AUD-SR/172/IV/2024</p>
    </div>

</div>        </div>
    </div>

    <!-- ITEM 03 -->
    <div class="relative pl-14 sm:pl-16">
        <div class="absolute left-5 sm:left-6 -translate-x-1/2 top-1 
                    w-8 h-8 sm:w-10 sm:h-10 
                    flex items-center justify-center 
                    bg-black border-2 border-accent 
                    rounded-full shadow-[0_0_15px_rgba(51,232,24,0.5)]">
            <span class="text-accent font-semibold">03</span>
        </div>

        <div data-aos="fade-right">
            <h3 class="mb-2 font-bold text-base sm:text-lg lg:text-xl text-white">
                Penasihat Investasi 
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-white">
                    Derivatif Keuangan & Aset Digital
                </span>
            </h3>

            <p class="text-accent font-bold text-xs sm:text-sm mb-2">
                OJK NOMOR : S-128/PM.02/2025
            </p>

            <p class="text-xs sm:text-sm text-gray-400">
                WPA Almai, juga terdaftar sebagai Wakil Penasihat Investasi melalui OJK
            </p>
        </div>
    </div>

    <!-- ITEM 04 -->
    <div class="relative pl-14 sm:pl-16">
        <div class="absolute left-5 sm:left-6 -translate-x-1/2 top-1 
                    w-8 h-8 sm:w-10 sm:h-10 
                    flex items-center justify-center 
                    bg-black border-2 border-accent 
                    rounded-full shadow-[0_0_15px_rgba(51,232,24,0.5)]">
            <span class="text-accent font-semibold">04</span>
        </div>

        <div data-aos="fade-right">
            <h3 class="mb-2 font-bold text-base sm:text-lg lg:text-xl text-white">
                Penasihat Derivatif
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-white">
                    Pasar Uang dan Valuta Asing (PUVA)
                </span>
            </h3>

            <p class="text-accent font-bold text-xs sm:text-sm mb-2">
                BANK INDONESIA NOMOR : 27/DPPKP/Srt/B
            </p>

            <p class="text-xs sm:text-sm text-gray-400">
                WPA Almai, juga terdaftar sebagai Penasihat PUVA dari Bank Indonesia
            </p>
        </div>
    </div>

    <!-- ITEM 05 -->
    <div class="relative pl-14 sm:pl-16">
        <div class="absolute left-5 sm:left-6 -translate-x-1/2 top-1 
                    w-8 h-8 sm:w-10 sm:h-10 
                    flex items-center justify-center 
                    bg-black border-2 border-accent 
                    rounded-full shadow-[0_0_15px_rgba(51,232,24,0.5)]">
            <span class="text-accent font-semibold">05</span>
        </div>

        <div data-aos="fade-right">
            <h3 class="mb-2 font-bold text-base sm:text-lg lg:text-xl text-white">
                ALMAI
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-white">
                    Penyelenggara Sistem Elektronik (PSE)
                </span>
            </h3>

            <p class="text-accent font-bold text-xs sm:text-sm mb-2">
                KOMDIGI NOMOR PSE : 010946.01/DJA.PSE/2023
            </p>

            <p class="text-xs sm:text-sm text-gray-400">
                WPA Almai dapat memberikan layanan nasihat melalui sistem elektronik (daring)
            </p>
        </div>
    </div>

</div>
</section>

<!-- TITLE -->
<div class="mt-12 sm:mt-16 lg:mt-20">
    <div class="text-center mb-10 sm:mb-12" data-aos="fade-up">
        
        <p class="text-white text-base sm:text-xl md:text-2xl lg:text-3xl font-semibold leading-tight">
            Roadmaps
        </p>

        <h2 class="text-xl sm:text-3xl md:text-4xl lg:text-5xl font-bold leading-tight">
            8 Tahap <span class="text-accent">WPA</span>
        </h2>

        <p class="text-base sm:text-xl md:text-2xl lg:text-3xl font-leading-tight">
            Penasihat Derivatif & <span class="text-accent">Aset Keuangan Digital</span>
        </p>

    </div>
</div>


<style>
/* ===== TIMELINE CONTAINER ===== */
.phase-timeline-wpa {
    display: flex;
    align-items: flex-start;
    gap: 10px;
}

/* ===== ITEM ===== */
.phase-item-wpa {
    flex: 1;
    min-width: 90px;
    text-align: center;
    position: relative;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.phase-item-wpa:hover {
    transform: translateY(-5px);
}

.phase-item-wpa:hover .phase-circle-wpa {
    background-image: url('<?= base_url("images/lari.gif") ?>');
    background-size: 85%;
    background-position: center;
    background-repeat: no-repeat;
    color: transparent !important;
    border-color: #33e818;
    box-shadow: 0 0 20px rgba(51,232,24,0.8);
    background-color: #000;
}

/* ===== CONNECTOR ===== */
.phase-connector-wpa {
    position: absolute;
    top: 18px;
    right: -50%;
    width: 100%;
    height: 2px;
    background: #33e818;
    z-index: 0;
}

/* ===== CIRCLE ===== */
.phase-circle-wpa {
    width: 38px;
    height: 38px;
    border-radius: 999px;
    background: #000;
    border: 2px solid #33e818;
    box-shadow: 0 0 10px rgba(51,232,24,0.4);

    display: flex;
    align-items: center;
    justify-content: center;

    font-weight: bold;
    font-size: 12px;
    color: #33e818;

    margin: 0 auto;
    position: relative;
    z-index: 2;
    transition: all 0.3s ease;
}

/* ===== TITLE ===== */
.phase-title-wpa {
    font-size: 11px;
    line-height: 1.3;
    margin-top: 8px;
    color: #ccc;
    transition: color 0.3s ease;
}

.phase-item-wpa:hover .phase-title-wpa {
    color: #fff;
}

/* ===== DESKTOP (FULL WIDTH, NO SCROLL) ===== */
@media (min-width: 1024px) {
    .phase-timeline-wpa {
        justify-content: space-between;
        overflow: visible;
    }

    .phase-item-wpa {
        min-width: auto;
    }
}

/* ===== TABLET & MOBILE (SCROLL) ===== */
@media (max-width: 1023px) {
    .phase-timeline-wpa {
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        padding-bottom: 10px;
    }

    .phase-item-wpa {
        flex: 0 0 auto;
        min-width: 140px;
        scroll-snap-align: center;
    }

    /* Hapus override global di mobile yang tadi */
    .phase-circle-wpa {
        /* tetep default dulu */
    }

    /* Efek GIF hanya saat aktif (saat di-scroll ke tengah) */
    .phase-item-wpa.is-active .phase-circle-wpa {
        background-image: url('<?= base_url("images/lari.gif") ?>');
        background-size: 85%; /* ukuran dikecilkan agar pas di dalam */
        background-position: center;
        background-repeat: no-repeat;
        color: transparent !important;
        border-color: #33e818;
        box-shadow: 0 0 15px rgba(51,232,24,0.6);
        background-color: #000; /* pastikan bg hitam agar kontras */
    }

    .phase-item-wpa.is-active .phase-title-wpa {
        color: #fff;
        font-weight: bold;
    }

    /* hide scrollbar */
    .phase-timeline-wpa::-webkit-scrollbar {
        display: none;
    }
}

/* ===== MOBILE SMALL ===== */
@media (max-width: 640px) {
    .phase-item-wpa {
        min-width: 120px;
    }

    .phase-title-wpa {
        font-size: 10px;
    }
}

/* MODAL STYLES */
#phaseModal {
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

#phaseModal.hidden {
    opacity: 0;
    visibility: hidden;
    display: flex !important; /* Keep flex but hidden */
    pointer-events: none;
}

.modal-content-glow {
    box-shadow: 0 0 40px rgba(51,232,24,0.15);
}

</style>

<div class="mb-20 max-w-6xl mx-auto" data-aos="fade-up">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 md:p-10 shadow-2xl">

        <div class="phase-timeline-wpa">

            <?php
            $phases = [
                [
                    'number' => '01', 
                    'title' => 'Bursa ICDX | Sertifikasi Multilateral', 
                    'desc' => 'Bergabung WGA ICDX, Pelatihan & sertifikasi Bursa Berjangka (multilateral), Mengerjakan tugas quesioner. (Online)',
                    'img' => 'images/2.jpg'
                ],
                [
                    'number' => '02', 
                    'title' => 'LPK | Pelatihan & Sertifikasi PBK', 
                    'desc' => 'Bergabung WAG LPK, Pelatihan & sertifikasi Perdagangan Berjangka secara online selama 5 hari, Quesioner dan presentasi. (Online)',
                    'img' => 'images/3.jpg'
                ],
                [
                    'number' => '03', 
                    'title' => 'BNSP | Sertifikasi Kompetensi PBK', 
                    'desc' => 'Bergabung WAG LPK, Wawancara Sertifikasi kompetensi Nasional Perdagangan Berjangka - tatap muka sesuai jadwal',
                    'img' => 'images/4.jpg'
                ],
                [
                    'number' => '04', 
                    'title' => 'BAPPEBTI | Sertifikasi Profesi-TLUP', 
                    'desc' => 'Sertifikasi profesi, Tanda Lulus Uji Profesi - tatap muka sesuai jadwal.',
                    'img' => 'images/5.jpg'
                ],
                [
                    'number' => '05', 
                    'title' => 'Almai | BAPPEBTI | Izin WPA', 
                    'desc' => 'Pengesahan resmi sebagai WPA diajukan oleh perusahaan → Almai',
                    'img' => 'images/6.jpg'
                ],
                [
                    'number' => '06', 
                    'title' => 'Almai | ASPEBTINDO | ASOSIASI PBK', 
                    'desc' => 'Pendaftaran keanggotaan Asosiasi Perdagangan Berjangka Komoditi Indonesia',
                    'img' => 'images/1.jpg'
                ],
                [
                    'number' => '07', 
                    'title' => 'Almai | OJK | Penasihat Investasi', 
                    'desc' => 'Pengesahan resmi sebagai Penasihat Derivatif dan Aset Keuangan DIgital diajukan oleh perusahaan → Almai',
                    'img' => 'images/7.jpg'
                ],
                [
                    'number' => '08', 
                    'title' => 'Almai | BI | Penasihat Derivatif PUVA', 
                    'desc' => 'Pengesahan resmi sebagai Penasihat Derivatif Pasar Uang dan Valuta Asing diajukan oleh perusahaan → Almai',
                    'img' => 'images/8.jpg'
                ],
            ];

            foreach ($phases as $index => $phase):
            ?>
                <div class="phase-item-wpa" 
                     onclick="openPhaseModal('<?= esc($phase['title']) ?>', '<?= esc($phase['desc']) ?>', '<?= esc($phase['number']) ?>', '<?= base_url($phase['img']) ?>')">

                    <?php if ($index < count($phases) - 1): ?>
                        <div class="phase-connector-wpa"></div>
                    <?php endif; ?>

                    <!-- NUMBER -->
                    <div class="phase-circle-wpa">
                        <?= esc($phase['number']) ?>
                    </div>

                    <!-- TITLE -->
                    <div class="phase-title-wpa">
                        <?= esc($phase['title']) ?>
                    </div>

                </div>
            <?php endforeach; ?>

        </div>

        <!-- HINT MOBILE -->
        <p class="text-xs text-gray-500 mt-4 text-center md:hidden">
            Klik icon & Geser ke samping → 
        </p>        </div>

        <!-- INFO -->
        <div class="mt-10 p-4 bg-accent/10 border border-accent/30 rounded-xl text-center">
            <p class="text-accent text-sm font-bold">
                <i class="fas fa-info-circle mr-2"></i> 
                Alur pendampingan hingga pengesahan.
            </p>
        </div>

    </div>
</div>

        <!-- TITLE -->
        <div class="mt-12 sm:mt-16 lg:mt-20">
            <div class="text-center mb-10 sm:mb-12" data-aos="fade-up">
                <p class="text-white text-base sm:text-xl md:text-2xl lg:text-3xl font-semibold leading-tight">Tenaga Ahli</p>
                <h2 class="text-xl sm:text-3xl md:text-4xl lg:text-5xl font-bold leading-tight">
                    Persyaratan & <span class="text-accent">Biaya</span>
                </h2>
                <p class="text-base sm:text-xl md:text-2xl lg:text-3xl font-semibold leading-tight">
                    Menjadi CWPA sampai <span class="text-accent">WPA</span>
                </p>
            </div>

    <!-- 2 COLUMN GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 max-w-6xl mx-auto items-stretch">

<!-- ================= REGULER ================= -->
<div class="bg-[#111] border border-white/10 rounded-xl p-1 hover:border-accent transition duration-300 flex">
  <div class="bg-black rounded-xl p-10 flex flex-col w-full text-center">

    <h3 class="text-2xl font-bold text-white mb-4">REGULER</h3>

    <!-- PRICE -->
    <div class="text-accent font-bold mb-4 
                text-3xl sm:text-4xl md:text-4xl lg:text-5xl">
      IDR 23,5 Juta
    </div>

    <!-- SUBTITLE -->
    <p class="text-gray-400 text-sm mb-6">
      Biaya sudah termasuk:
    </p>

    <!-- LIST UTAMA -->
    <ol class="space-y-4 text-sm flex-grow text-left pl-6 text-gray-300 leading-relaxed list-decimal">

      <li>1. <span class="text-accent font-bold uppercase">ICDX</span> : Pelatihan & sertifikasi Bursa Berjangka (multilateral)</li>
      <li>2. <span class="text-accent font-bold uppercase">LPK-PBK</span> : Pelatihan & sertifikasi Perdagangan Berjangka</li>
      <li>3. <span class="text-accent font-bold uppercase">BNSP-PBK</span> : Wawancara & Sertifikasi kompetensi PBK</li>
      <li>4. <span class="text-accent font-bold uppercase">BAPPEBTI</span> : Wawancara & Sertifikasi profesi (TLUP)</li>
      <li>5. <span class="text-accent font-bold uppercase">ALMAI | BAPPEBTI</span> : Pengesahan resmi sebagai WPA</li>
      <li>6. <span class="text-accent font-bold uppercase">ALMAI | ASPEBTINDO</span> : Registrasi Asosiasi PBK</li>
      <li>7. <span class="text-accent font-bold uppercase">ALMAI | OJK</span> : Persetujuan Penasihat Derivatif Aset Digital</li>
      <li>8. <span class="text-accent font-bold uppercase">BANK INDONESIA</span> : Persetujuan Penasihat Derivatif PUVA</li>

    </ol>

    <!-- NOTES (RELEVAN & TERSTRUKTUR) -->
<div class="mt-8 space-y-4 text-xs text-left">

  <div class="flex items-start gap-2">
    <span>✨</span>
    <p class="text-gray-400 leading-relaxed">
      <span class="text-accent font-semibold">Akses Profil Bio CWPA</span><br>
Setiap peserta mendapatkan akses 
<a href="https://almai.id/cwpa" target="_blank" class="font-bold text-accent underline hover:text-white transition">
  Profil Bio CWPA
</a> 
untuk mulai membangun audiens, meningkatkan kredibilitas, dan mengembangkan komunitas trader sejak awal program.
</p>
  </div>

  <div class="flex items-start gap-2">
    <span>🚀</span>
    <p class="text-gray-400 leading-relaxed">
      <span class="text-accent font-semibold">Peluang Pengesahan WPA (Tahap 5)</span><br>
      Dapat diproses setelah mencapai minimal 
      <span class="text-accent font-semibold">1.000+ audiens</span> sebagai validasi aktivitas dan profesionalitas.
    </p>
  </div>



<p class="text-green-400 leading-relaxed border border-green-500/30 rounded-lg px-4 py-3">
  <span class="font-semibold">Persyaratan:</span><br>
  1. KTP & NPWP<br>
  2. Ijasah pendidikan formal S1<br>
  3. Bukti pelaporan SPT Pribadi<br>
  4. Surat Keterangan Catatan Kepolisian (SKCK)<br>
  5. Surat Keterangan tidak pernah dipidana dari Pengadilan
</p>

    </div>

    <!-- CTA -->
    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center items-center mt-8" data-aos="fade-up" data-aos-delay="200">
      <a href="<?= base_url('daftar-cwpa') ?>" 
         class="px-6 sm:px-8 lg:px-10 py-3 sm:py-4 lg:py-5 bg-accent text-black font-bold text-sm sm:text-base lg:text-lg rounded-full hover:bg-white hover:scale-105 transition duration-300 shadow-[0_0_20px_rgba(51,232,24,0.4)]">
        Daftar Sekarang
      </a>
    </div>

  </div>
</div>

<!-- ================= RIGHT COLUMN (STACKED) ================= -->
<div class="flex flex-col gap-8">

  <!-- INSENTIF 50% -->
  <div class="bg-[#111] border border-white/10 rounded-xl p-1 hover:border-accent transition duration-300 flex">
    <div class="bg-black rounded-xl p-10 flex flex-col w-full text-center h-full">

      <h3 class="text-2xl font-bold text-white mb-4">INSENTIF 50%</h3>

      <!-- PRICE -->
      <div class="text-accent font-bold mb-4 text-3xl sm:text-4xl md:text-4xl lg:text-5xl">
        IDR 11,5 Juta
      </div>

      <!-- SUB -->
      <div class="bg-accent/20 border border-accent/50 rounded-lg p-3 mb-6">
        <p class="text-accent text-xs font-semibold">
          <i class="fa-solid fa-users mr-1"></i>
          CWPA dengan 3.000+ audiens.
        </p>
      </div>

      <!-- LIST -->
      <ol class="space-y-4 text-sm flex-grow text-left pl-6 text-gray-300 leading-relaxed list-decimal">

        <li>
          <span class="text-accent font-semibold">Pengembalian biaya 50%</span> 
          diberikan kepada peserta yang mencapai 3.000+ audiens sebelum tahap Pengesahan WPA (5) diproses.
        </li>

        <li>
          <span class="text-accent font-semibold">Validasi pertumbuhan audiens</span> 
          sebagai indikator awal kredibilitas dan konsistensi edukasi trading.
        </li>

        <li>
          <span class="text-accent font-semibold">Tahap menuju insentif maksimal</span> 
          dengan peluang upgrade ke 100% insentif pada 5.000+ audiens.
        </li>

      </ol>

    </div>
  </div>

  <!-- INSENTIF 100% -->
  <div class="bg-[#111] border border-white/10 rounded-xl p-1 hover:border-accent transition duration-300 flex">
    <div class="bg-black rounded-xl p-10 flex flex-col w-full text-center h-full">

      <h3 class="text-2xl font-bold text-white mb-4">INSENTIF 100%</h3>

      <!-- PRICE -->
      <div class="text-accent font-bold mb-4 text-3xl sm:text-4xl md:text-4xl lg:text-5xl">
        IDR 0 → GRATIS!
      </div>

      <!-- SUB -->
      <div class="bg-accent/20 border border-accent/50 rounded-lg p-3 mb-6">
        <p class="text-accent text-xs font-semibold">
          <i class="fa-solid fa-users mr-1"></i>
          CWPA dengan 5.000+ audiens.
        </p>
      </div>

      <!-- LIST -->
      <ol class="space-y-4 text-sm flex-grow text-left pl-6 text-gray-300 leading-relaxed list-decimal">

        <li>
          <span class="text-accent font-semibold">Pengembalian biaya 100%</span> 
          bagi peserta yang mencapai 5.000+ audiens sebelum Tahap Pengesahan WPA (5) diproses.
        </li>

        <li>
          <span class="text-accent font-semibold">Status profesional penuh</span> 
          sebagai Penasihat dengan komunitas aktif dan terverifikasi.
        </li>

        <li>
          <span class="text-accent font-semibold">Potensi monetisasi maksimal</span> 
          melalui edukasi, komunitas, dan aktivitas trading resmi.
        </li>

      </ol>

    </div>
  </div>

</div>
</section>



<!-- PHASE MODAL -->
<div id="phaseModal" class="fixed inset-0 z-[999] hidden flex items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closePhaseModal()"></div>
    
    <!-- Modal Box -->
    <div class="relative bg-[#0a0a0a] border border-accent/30 rounded-2xl w-full max-w-md overflow-hidden modal-content-glow" data-aos="zoom-in">
        <!-- Close Button -->
        <button onclick="closePhaseModal()" class="absolute top-4 right-4 text-gray-500 hover:text-white transition">
            <i class="fas fa-times text-xl"></i>
        </button>

        <div class="p-8">
            <div id="modalPhaseNumber" class="w-12 h-12 bg-accent/10 border border-accent rounded-full flex items-center justify-center text-accent font-bold text-xl mb-6 shadow-[0_0_15px_rgba(51,232,24,0.3)]">
                01
            </div>
            
            <h3 id="modalPhaseTitle" class="text-2xl font-bold text-white mb-4 leading-tight">
                Pembekalan & Pendampingan
            </h3>
            
            <div class="w-12 h-1 bg-accent mb-6 rounded-full"></div>
            
            <p id="modalPhaseDesc" class="text-gray-400 text-lg leading-relaxed italic mb-8">
                Program persiapan dan pendampingan terpadu hingga kompeten sebagai WPA.
            </p>

            <button id="modalPhaseBtn" onclick="openImageModal()" class="inline-flex items-center gap-2 px-6 py-3 bg-accent/20 border border-accent text-accent rounded-full font-bold hover:bg-accent hover:text-black transition duration-300">
                <i class="fas fa-image"></i>
                Lihat Foto
            </button>
        </div>

        <!-- Footer Decoration -->
        <div class="h-1 w-full bg-gradient-to-r from-transparent via-accent/50 to-transparent"></div>
    </div>
</div>

<!-- IMAGE MODAL (LIGHTBOX) -->
<div id="imageModal" class="fixed inset-0 z-[1000] hidden flex items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/95 backdrop-blur-md" onclick="closeImageModal()"></div>
    
    <!-- Close Button -->
    <button onclick="closeImageModal()" class="absolute top-6 right-6 text-white/50 hover:text-white transition z-10">
        <i class="fas fa-times text-3xl"></i>
    </button>

    <!-- Image Container -->
    <div class="relative max-w-5xl w-full max-h-[90vh] flex items-center justify-center" data-aos="zoom-in">
        <img id="modalFullImage" src="" alt="Phase Image" class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl border border-white/10">
    </div>
</div>


<script>
let currentImgUrl = '';

function openPhaseModal(title, desc, number, imgUrl) {
    const modal = document.getElementById('phaseModal');
    const modalTitle = document.getElementById('modalPhaseTitle');
    const modalDesc = document.getElementById('modalPhaseDesc');
    const modalNumber = document.getElementById('modalPhaseNumber');

    modalTitle.innerText = title;
    modalDesc.innerText = desc;
    modalNumber.innerText = number;
    currentImgUrl = imgUrl; // Store for image modal

    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // Prevent scroll
}

function openImageModal() {
    const imgModal = document.getElementById('imageModal');
    const fullImg = document.getElementById('modalFullImage');
    
    fullImg.src = currentImgUrl;
    imgModal.classList.remove('hidden');
}

function closeImageModal() {
    const imgModal = document.getElementById('imageModal');
    imgModal.classList.add('hidden');
}

function closePhaseModal() {
    const modal = document.getElementById('phaseModal');
    modal.classList.add('hidden');
    if (document.getElementById('imageModal').classList.contains('hidden')) {
        document.body.style.overflow = ''; // Restore scroll if both closed
    }
}

// Active phase detection for mobile scroll
const timeline = document.querySelector('.phase-timeline-wpa');
const items = document.querySelectorAll('.phase-item-wpa');

if (timeline) {
    const observerOptions = {
        root: timeline,
        threshold: 0.6, // item must be 60% visible
        rootMargin: '0px -25% 0px -25%' // narrow the detection area to the center
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Remove active class from all
                items.forEach(item => item.classList.remove('is-active'));
                // Add to the centered one
                entry.target.classList.add('is-active');
            }
        });
    }, observerOptions);

    items.forEach(item => observer.observe(item));
    
    // Set first item as active by default on mobile
    if (window.innerWidth < 1024) {
        items[0].classList.add('is-active');
    }
}
</script>



<?= $this->endSection() ?>