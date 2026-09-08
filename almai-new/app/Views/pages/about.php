<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Hero -->
<section class="relative pt-32 pb-8 overflow-hidden">
    <div class="absolute inset-0 bg-grid z-0 pointer-events-none"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-accent/20 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="container mx-auto px-6 relative z-10 text-center">
        <p class="text-accent font-bold tracking-widest text-sm mb-4 uppercase">Tentang Perusahaan</p>
        <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tighter">PT. ALMA INDONESIA RAYA | <span class="text-accent">ALMAI</span></h1>
<blockquote 
    class="text-[11px] sm:text-xs md:text-sm lg:text-base 
           text-gray-400 
           max-w-3xl sm:max-w-5xl lg:max-w-6xl mx-auto
           leading-tight lg:leading-snug px-4 py-3 
           border-l-2 border-green-500 text-left"
>
    <span class="text-white italic">
PT. Alma Indonesia Raya (ALMAI) merupakan perusahaan penasihat perdagangan berjangka dan aset keuangan digital yang berfokus pada pengembangan ekosistem trading yang aman, terstruktur, dan berbasis regulasi di Indonesia, dengan mengintegrasikan edukasi, teknologi, serta perlindungan hukum dalam satu sistem yang komprehensif.
<br> <br>
<strong class="block text-white not-italic font-bold mb-2">Latar Belakang</strong>

<p class="mb-4">
Industri perdagangan berjangka dan aset digital di Indonesia mengalami pertumbuhan signifikan, namun juga diiringi dengan berbagai tantangan, seperti:
</p>

<ul class="list-disc pl-5 space-y-1 mb-4">
    <li>Minimnya literasi trading yang terstruktur</li>
    <li>Kurangnya pendampingan profesional</li>
    <li>Tidak adanya perlindungan yang jelas bagi trader</li>
</ul>

<p>
Melihat kondisi tersebut, ALMAI didirikan untuk menghadirkan sebuah sistem yang tidak hanya berorientasi pada profit, tetapi juga pada keamanan, edukasi, dan keberlanjutan aktivitas trading.
</p>

            </blockquote>
        </div>

    </div>
</section>

<!-- Visi & Misi -->
<section class="py-20 relative bg-black">
    <div class="container mx-auto px-6">

        <!-- Main Title -->
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-3xl font-bold mb-6 text-white">
                VISI <span class="text-accent">& MISI</span>
            </h2>
            <p class="text-gray-400 max-w-2xl mx-auto text-sm">
                Arah strategis ALMAI dalam membangun ekosistem profesional dan berkelanjutan.
            </p>
        </div>

        <!-- Vision Section -->
        <div class="relative max-w-3xl mx-auto mb-24 px-4" data-aos="fade-up">
            <div class="relative group">

                <!-- Badge VISI -->
                <div class="absolute -top-6 left-1/2 -translate-x-1/2 z-20">
                    <div class="relative">
                        <div class="absolute inset-0 bg-accent blur-md rounded-full opacity-50"></div>
                        <span class="relative block bg-accent text-black font-extrabold px-8 py-2 rounded-full uppercase tracking-widest text-xs shadow-lg">
                            VISI
                        </span>
                    </div>
                </div>

                <!-- Glow -->
                <div class="absolute -inset-1 bg-gradient-to-r from-accent/20 via-green-500/20 to-accent/20 rounded-[2rem] blur-xl opacity-40"></div>

                <!-- Card -->
                <div class="relative bg-[#080808] border border-white/10 rounded-[2rem] p-8 md:p-12 text-center shadow-xl z-10">
                    <p class="text-lg md:text-xl text-gray-200 leading-relaxed">
                        Menjadi platform resmi rujukan Trader Indonesia untuk pendampingan transaksi Derivatif dan Aset Keuangan Digital bersama  
                        <span class="text-accent font-semibold">Wakil Penasihat Berjangka (WPA)</span>.
                    </p>
                </div>
            </div>
        </div>

        <!-- Mission Section -->
        <div class="relative max-w-5xl mx-auto px-4" data-aos="fade-up">

            <!-- Badge MISI -->
            <div class="absolute -top-6 left-1/2 -translate-x-1/2 z-20">
                <div class="relative">
                    <div class="absolute inset-0 bg-accent blur-md rounded-full opacity-50"></div>
                    <span class="relative block bg-accent text-black font-extrabold px-8 py-2 rounded-full uppercase tracking-widest text-xs shadow-lg">
                        MISI
                    </span>
                </div>
            </div>

            <!-- Glow -->
            <div class="absolute -inset-1 bg-gradient-to-r from-accent/10 via-green-500/10 to-accent/10 rounded-[2rem] blur-xl opacity-30"></div>

            <!-- Card Wrapper -->
            <div class="relative bg-[#080808] border border-white/10 rounded-[2rem] p-8 md:p-12 shadow-xl z-10">
                
                <div class="grid md:grid-cols-3 gap-6">

                    <!-- Misi 1 -->
                    <div class="bg-[#0f0f0f] p-6 rounded-xl border border-white/5 hover:border-accent/40 transition group text-center">
                        <div class="w-10 h-10 mx-auto bg-white/5 rounded-lg flex items-center justify-center mb-5 text-gray-400 font-bold group-hover:bg-accent group-hover:text-black">
                            1
                        </div>
                        <h4 class="text-white font-semibold mb-3">Edukasi & Literasi</h4>
                        <p class="text-sm text-gray-400 leading-relaxed">
                            Meningkatkan pemahaman masyarakat dalam perdagangan berjangka dan aset digital melalui edukasi yang terstruktur dan berkelanjutan.
                        </p>
                    </div>

                    <!-- Misi 2 -->
                    <div class="bg-[#0f0f0f] p-6 rounded-xl border border-white/5 hover:border-accent/40 transition group text-center">
                        <div class="w-10 h-10 mx-auto bg-white/5 rounded-lg flex items-center justify-center mb-5 text-gray-400 font-bold group-hover:bg-accent group-hover:text-black">
                            2
                        </div>
                        <h4 class="text-white font-semibold mb-3">Teknologi & Pendampingan</h4>
                        <p class="text-sm text-gray-400 leading-relaxed">
                            Menyediakan alat bantu sistem trading yang adaptif dan teruji, serta memberikan pendampingan profesional kepada setiap klien.
                        </p>
                    </div>

                    <!-- Misi 3 -->
                    <div class="bg-[#0f0f0f] p-6 rounded-xl border border-white/5 hover:border-accent/40 transition group text-center">
                        <div class="w-10 h-10 mx-auto bg-white/5 rounded-lg flex items-center justify-center mb-5 text-gray-400 font-bold group-hover:bg-accent group-hover:text-black">
                            3
                        </div>
                        <h4 class="text-white font-semibold mb-3">Ekosistem & Perlindungan</h4>
                        <p class="text-sm text-gray-400 leading-relaxed">
                            Membangun sinergi dengan regulator dan pelaku industri, serta menyediakan program advokasi dan perlindungan bagi trader.
                        </p>
                    </div>

                </div>

            </div>
        </div>

    </div>
</section>


<!-- Produk & Layanan -->
<section class="py-20 bg-black/30">
    <div class="max-w-6xl mx-auto px-4">

        <!-- Title -->
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="text-accent font-semibold tracking-widest text-xs uppercase mb-2 block">
                Pilar Layanan
            </span>
            <h2 class="text-2xl md:text-3xl font-bold text-white">
                Produk & <span class="text-accent">Layanan</span>
            </h2>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-3xl mx-auto mb-14 border-b border-white/5 pb-8">
            <div class="text-center">
                <h3 class="text-xl font-bold text-accent">18+</h3>
                <p class="text-[10px] text-gray-400 uppercase mt-1">WPA Bersertifikat</p>
            </div>
            <div class="text-center">
                <h3 class="text-xl font-bold text-accent">88+</h3>
                <p class="text-[10px] text-gray-400 uppercase mt-1">Kelas Trading</p>
            </div>
            <div class="text-center">
                <h3 class="text-xl font-bold text-accent"><?= number_format($totalUsers ?? 3769) ?>+</h3>
                <p class="text-[10px] text-gray-400 uppercase mt-1">Trader Aktif</p>
            </div>
            <div class="text-center">
                <h3 class="text-xl font-bold text-accent">98%</h3>
                <p class="text-[10px] text-gray-400 uppercase mt-1">Kepuasan Klien</p>
            </div>
        </div>

<!-- Cards -->
<div class="grid md:grid-cols-3 gap-5 max-w-5xl mx-auto">

    <!-- Advokasi -->
    <div class="bg-[#0A0A0A] p-5 rounded-xl border border-white/10 hover:border-accent/40 transition">
        <div class="w-10 h-10 bg-accent/20 text-accent rounded-md flex items-center justify-center mb-4 text-sm mx-auto">
            <i class="fas fa-user-tie"></i>
        </div>
        <h3 class="text-base font-semibold text-white text-center mb-2">Advokasi</h3>
        <p class="text-gray-400 text-[11px] text-center leading-relaxed mb-5">
            Program edukasi dan onboarding resmi untuk memberikan pemahaman legalitas, risiko, mekanisme layanan, serta perlindungan konsumen sebelum menggunakan layanan WPA.
        </p>
        <div class="space-y-1 text-center">
            <p class="text-[11px] text-gray-400">✔ Webinar & Workshop</p>
            <p class="text-[11px] text-gray-400">✔ Pelatihan & Pendampingan</p>
        </div>
        <a href="https://almai.id/advokasi" class="mt-5 block text-center text-accent text-xs font-semibold hover:text-white">
            Lihat Detail →
        </a>
    </div>

    <!-- Expert Advisor -->
    <div class="bg-[#0A0A0A] p-5 rounded-xl border border-white/10 hover:border-accent/40 transition">
        <div class="w-10 h-10 bg-accent/20 text-accent rounded-md flex items-center justify-center mb-4 text-sm mx-auto">
            <i class="fas fa-robot"></i>
        </div>
        <h3 class="text-base font-semibold text-white text-center mb-2">Expert Advisor</h3>
        <p class="text-gray-400 text-[11px] text-center leading-relaxed mb-4">
            Perangkat lunak berbasis algoritma untuk membantu analisis dan eksekusi terbatas secara non-discretionary, dengan kendali tetap pada klien.
        </p>
        <div class="flex justify-center gap-2 mb-3 flex-wrap">
            <span class="px-2 py-0.5 bg-white/5 border border-white/10 text-[10px] rounded text-accent">AIWE</span>
            <span class="px-2 py-0.5 bg-white/5 border border-white/10 text-[10px] rounded text-accent">BIDBOX</span>
        </div>
        <div class="space-y-1 text-center">
            <p class="text-[11px] text-gray-400">✔ Analisis Otomatis</p>
            <p class="text-[11px] text-gray-400">✔ Eksekusi Terbatas</p>
        </div>
        <a href="<?= base_url('layanan/kategori/Expert+Advisor') ?>" class="mt-5 block text-center text-accent text-xs font-semibold hover:text-white">
            Lihat Detail →
        </a>
    </div>

    <!-- Layanan WPA -->
    <div class="bg-[#0A0A0A] p-5 rounded-xl border border-white/10 hover:border-accent/40 transition relative">
        <div class="absolute top-3 right-3 bg-accent text-black text-[9px] font-bold px-2 py-0.5 rounded-full">
            PREMIUM
        </div>
        <div class="w-10 h-10 bg-accent/20 text-accent rounded-md flex items-center justify-center mb-4 text-sm mx-auto">
            <i class="fas fa-crown"></i>
        </div>
        <h3 class="text-base font-semibold text-white text-center mb-2">Layanan WPA</h3>
        <p class="text-gray-400 text-[11px] text-center leading-relaxed mb-5">
            Layanan profesional lanjutan yang menggabungkan edukasi, mentoring, analisis, dan teknologi dalam satu ekosistem terintegrasi berbasis WPA.
        </p>
        <div class="space-y-1 text-center">
            <p class="text-[11px] text-gray-400">✔ Personal Mentor</p>
            <p class="text-[11px] text-gray-400">✔ Trading Plan</p>
            <p class="text-[11px] text-gray-400">✔ Portfolio Monitoring</p>
        </div>
        <a href="<?= base_url('layanan/kategori/Advokasi') ?>" class="mt-5 block text-center text-accent text-xs font-semibold hover:text-white">
            Lihat Detail →
        </a>
    </div>

</div>
</section>

<!-- Penasihat Berjangka Definition -->
<section class="py-20 bg-black/50">
    <div class="container mx-auto px-6 text-center">
        <div class="max-w-4xl mx-auto" data-aos="fade-up">
            <span class="text-accent font-bold tracking-widest text-xs uppercase mb-2 block">LEGALITAS</span>
            <h2 class="text-3xl font-bold mb-8 text-white">Penasihat <span class="text-accent">Berjangka</span></h2>

<blockquote 
    class="text-[11px] sm:text-xs md:text-sm lg:text-base 
           text-gray-400 
           max-w-3xl sm:max-w-5xl lg:max-w-6xl mx-auto
           leading-tight lg:leading-snug px-4 py-3 
           border-l-2 border-green-500 text-left"
>
    <span class="text-white italic">
Penasihat Perdagangan Berjangka yang selanjutnya disebut Penasihat Berjangka adalah
orang perseorangan atau Badan Usaha yang memberikan Nasihat kepada pihak lain mengenai
jual beli Komoditi berdasarkan Kontrak Berjangka, Kontrak Derivatif Syariah, dan/atau
Kontrak Derivatif lainnya dengan menerima imbalan. <br><br>

Wakil Penasihat Berjangka adalah orang perseorangan yang berdasarkan kesepakatan
dengan Penasihat Berjangka, melaksanakan sebagian fungsi Penasihat Berjangka.
    </span>
</blockquote>
                                <span class="font-mono text-sm text-accent font-bold tracking-wide">
                Izin Usaha Nomor: 02/BAPPEBTI/SI-PNB/02/2024</span>
            </p>
        </div>
    </div>
</section>

<!-- AIWE & BIDBOX -->
<section class="py-9 relative overflow-hidden">
    <div class="absolute inset-0 bg-accent/5 skew-y-1 transform origin-top-left -z-10"></div>

    <div class="container mx-auto px-4 max-w-5xl">

        <!-- Title -->
        <div class="text-center mb-5">
            <span class="text-accent font-medium tracking-widest text-xs uppercase block">
                EXPERT ADVISOR
            </span>
            <h2 class="text-xl md:text-2xl lg:text-3xl font-bold text-white mb-2">
                AIWE & <span class="text-accent">BIDBOX</span>
            </h2>

            <!-- PENJELASAN -->
<blockquote 
    class="text-[11px] sm:text-xs md:text-sm lg:text-base 
           text-gray-400 
           max-w-3xl sm:max-w-5xl lg:max-w-6xl mx-auto
           leading-tight lg:leading-snug px-4 py-3 
           border-l-2 border-green-500 text-left"
>
    <span class="text-white italic">
Nasihat Berbasis Teknologi Informasi berupa Expert Advisor adalah alat bantu berbasis Teknologi Informasi yang di dalamnya tersusun berdasarkan algoritma yang ditanamkan pada baris-baris programnya yang ditentukan berdasarkan karakteristik, tipe, kebutuhan, dan harapan Klien
    </span>
</blockquote>
                                <span class="font-mono text-sm text-accent font-bold tracking-wide">
                Izin Usaha Nomor: 01/BAPPEBTI/SP-PBEA/06/2024</span>
            </p>
<br>

<!-- AIWE & BIDBOX -->

        <!-- Grid -->
        <div class="grid md:grid-cols-2 gap-6">

            <!-- PERSETUJUAN -->
            <div>
                <h3 class="text-sm md:text-base font-semibold text-white mb-3 border-b border-green/10 pb-2 text-center">
                    Perizinan & Persetujuan
                </h3>

                <div class="space-y-3">

                    <div class="bg-[#151515] p-3 rounded-lg border border-white/5 hover:border-accent/40 transition">
                        <p class="text-sm text-gray-400 mb-1">Izin Penasihat Berjangka | BAPPEBTI</p>
                        <span class="font-mono text-sm text-accent font-semibold">No. 02/BAPPEBTI/SI-PNB/02/2024</span>
                    </div>

                    <div class="bg-[#151515] p-3 rounded-lg border border-white/5 hover:border-accent/40 transition">
                        <p class="text-sm text-gray-400 mb-1">Persetujuan Penasihat Keuangan Derivatif | OJK</p>
                        <span class="font-mono text-sm text-accent font-semibold">No. S-128/PM.02/2025</span>
                    </div>

                    <div class="bg-[#151515] p-3 rounded-lg border border-white/5 hover:border-accent/40 transition">
                        <p class="text-sm text-gray-400 mb-1">Persetujuan Penasihat Derivatif PUVA | Bank Indonesia</p>
                        <span class="font-mono text-sm text-accent font-semibold">No. 27/285/DPPK/Srt/B</span>
                    </div>

                    <div class="bg-[#151515] p-3 rounded-lg border border-white/5 hover:border-accent/40 transition">
                        <p class="text-sm text-gray-400 mb-1">Terdaftar Penyelenggara Sistem Elektronik | KOMDIGI</p>
                        <span class="font-mono text-sm text-accent font-semibold">PSE No. 010946.01/DJAI.PSE/08/2023</span>
                    </div>

                </div>
            </div>

            <!-- REKOMENDASI -->
            <div>
                <h3 class="text-sm md:text-base font-semibold text-white mb-3 border-b border-green/10 pb-2 text-center">
                    Rekomendasi & Registrasi
                </h3>


                    <div class="bg-[#151515] p-3 rounded-lg border border-white/5 hover:border-accent/40 transition">
                        <p class="text-sm text-gray-400 mb-1">Persetujuan BIDBOX</p>
                        <span class="font-mono text-sm text-accent font-semibold">No. 01/BAPPEBTI/SP-PBEA/06/2024</span>
                    </div>
                <div class="space-y-3">

                    <div class="bg-[#151515] p-3 rounded-lg border border-white/5 hover:border-accent/40 transition">
                        <p class="text-sm text-gray-400 mb-1">Rekomendasi Bursa Kripto | CFX</p>
                        <span class="font-mono text-sm text-accent font-semibold">No. CFX/AUD-SR/172/IV/2024</span>
                    </div>


                    <div class="bg-[#151515] p-3 rounded-lg border border-white/5 hover:border-accent/40 transition">
                        <p class="text-sm text-gray-400 mb-1">Persetujuan AIWE</p>
                        <span class="font-mono text-sm text-accent font-semibold">No. KB.00.00/60/BAPPEBTI/SD/02/2025</span>
                    </div>
                    <div class="bg-[#151515] p-3 rounded-lg border border-white/5 hover:border-accent/40 transition">
                        <p class="text-sm text-gray-400 mb-1">Rekomendasi Bursa Komoditi | JFX</p>
                        <span class="font-mono text-sm text-accent font-semibold">No. L/JFX/DIR/08-24/525</span>
                    </div>

                </div>
            </div>

        </div>

    </div>
</section>

<!-- Struktur Organisasi & WPA -->
<section class="py-20">
    <div class="container mx-auto px-6">
        
        <!-- HEADER -->
        <div class="text-center mb-12">
            <span class="text-accent font-bold tracking-widest text-xs uppercase mb-2 block">Direksi</span>
            <h2 class="text-3xl font-bold text-white">
                Struktur <span class="text-accent">Organisasi</span>
            </h2>
        </div>

<!-- ORG STRUCTURE WITH PROFILE -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center mb-16 max-w-5xl mx-auto">

    <!-- ITEM -->
    <div class="flex flex-col items-center group">
        <img src="/images/profile/direksi/Aldo.svg" 
             onclick="openImage(this.src)"
             class="cursor-pointer w-24 h-24 rounded-full object-cover border-2 border-accent shadow-md mb-3 
                    transition duration-300 group-hover:scale-105"
             alt="Komisaris">

        <p class="text-white font-semibold text-sm">Kadek Aldo Nagata, S.E, M.M</p>
        <div class="w-10 h-[2px] bg-accent my-2"></div>
        <h4 class="text-accent text-sm font-medium">Komisaris</h4>
    </div>

    <!-- ITEM -->
    <div class="flex flex-col items-center group">
        <img src="/images/profile/direksi/Rendy.svg" 
             onclick="openImage(this.src)"
             class="cursor-pointer w-24 h-24 rounded-full object-cover border-2 border-accent shadow-md mb-3 
                    transition duration-300 group-hover:scale-105"
             alt="Direktur Utama">

        <p class="text-white font-semibold text-sm">Rendy M Prayogie</p>
        <div class="w-10 h-[2px] bg-accent my-2"></div>
        <h4 class="text-accent text-sm font-medium">Direktur Utama</h4>
    </div>

    <!-- ITEM -->
    <div class="flex flex-col items-center group">
        <img src="/images/profile/direksi/Alit.svg" 
             onclick="openImage(this.src)"
             class="cursor-pointer w-24 h-24 rounded-full object-cover border-2 border-accent shadow-md mb-3 
                    transition duration-300 group-hover:scale-105"
             alt="Direktur">

        <p class="text-white font-semibold text-sm">Alit Widiastika, S.E, M.H</p>
        <div class="w-10 h-[2px] bg-accent my-2"></div>
        <h4 class="text-accent text-sm font-medium">Direktur</h4>
    </div>

    <!-- ITEM -->
    <div class="flex flex-col items-center group">
        <img src="/images/profile/direksi/Jihan.svg" 
             onclick="openImage(this.src)"
             class="cursor-pointer w-24 h-24 rounded-full object-cover border-2 border-accent shadow-md mb-3 
                    transition duration-300 group-hover:scale-105"
             alt="Legal & Compliance">

        <p class="text-white font-semibold text-sm">Jihan Avida, S.H.</p>
        <div class="w-10 h-[2px] bg-accent my-2"></div>
        <h4 class="text-accent text-sm font-medium">Legal & Compliance</h4>
    </div>

</div>

<!-- MODAL ZOOM IMAGE -->
<div id="imageModal" class="fixed inset-0 bg-black/80 flex items-center justify-center z-50 hidden">
    <span onclick="closeImage()" class="absolute top-5 right-8 text-white text-3xl cursor-pointer">&times;</span>
    <img id="modalImg" class="max-w-[90%] max-h-[85%] rounded-lg shadow-2xl">
</div>

<!-- SCRIPT -->
<script>
function openImage(src) {
    document.getElementById("imageModal").classList.remove("hidden");
    document.getElementById("modalImg").src = src;
}

function closeImage() {
    document.getElementById("imageModal").classList.add("hidden");
}
</script>

<!-- WPA -->
        <div class="text-center mb-12">
            <span class="text-accent font-bold tracking-widest text-xs uppercase mb-2 block">Mentor Bersertifikat</span>
            <h2 class="text-3xl font-bold text-white">
                Wakil Penasihat <span class="text-accent">Berjangka</span>
            </h2>
        </div>

<!-- WRAPPER TENGAH -->
<div class="max-w-6xl mx-auto">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-12 justify-center">
        <?php foreach ($featuredWpa as $wpa): ?>
            <div class="flex justify-center">
                <?= view('partials/cards/wpa_card', ['wpa' => $wpa]) ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<!-- BUTTON -->
<div class="text-center">
    <a href="<?= base_url('wpa') ?>" 
       target="_blank" 
       rel="noopener noreferrer"
       class="inline-flex items-center justify-center px-8 py-3 border border-white/10 bg-[#111] rounded-lg hover:border-accent hover:text-accent transition-all duration-300">
        <span>Lihat Seluruh WPA</span>
    </a>
</div>
</section>

<!-- Almai Value -->
<section class="py-20 bg-black/50 border-t border-white/5">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold tracking-tighter">Almai<span class="text-accent"> Value</span></h2>
        </div>
        <div class="grid md:grid-cols-3 gap-6 max-w-5xl mx-auto">
            <div class="bg-[#111] p-8 rounded-lg border border-white/5 hover:border-accent/40 transition group">
                <div class="w-12 h-12 bg-accent/20 rounded-full flex items-center justify-center mb-6 text-accent">
                    <i class="fas fa-certificate"></i>
                </div>
                <h3 class="text-lg font-bold mb-3 text-white">TRUSTED</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Prioritas pada kepatuhan regulasi dan perlindungan kepentingan klien.</p>
            </div>
            <div class="bg-[#111] p-8 rounded-lg border border-white/5 hover:border-accent/40 transition group">
                <div class="w-12 h-12 bg-accent/20 rounded-full flex items-center justify-center mb-6 text-accent">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="text-lg font-bold mb-3 text-white">SPECIALIST</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Tenaga ahli dan teknologi yang terspesialisasi dengan portofolio transparan.</p>
            </div>
            <div class="bg-[#111] p-8 rounded-lg border border-white/5 hover:border-accent/40 transition group">
                <div class="w-12 h-12 bg-accent/20 rounded-full flex items-center justify-center mb-6 text-accent">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <h3 class="text-lg font-bold mb-3 text-white">EFFICIENT</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Proses cepat dan akurat berkat integrasi teknologi.</p>
            </div>
        </div>
    </div>
</section>

<!-- Placeholders for Bottom Sections -->
<!-- Metode & Standar Kami (Proses Kerja) -->
<section class="py-20 bg-gradient-to-b from-black to-[#0a0a0a] border-t border-white/5">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-accent font-bold tracking-widest text-xs uppercase mb-2 block">METODE & STANDAR KAMI</span>
            <h2 class="text-3xl md:text-4xl font-bold text-white">Proses <span class="text-accent">Kerja</span></h2>
            <p class="text-gray-400 mt-4 max-w-2xl mx-auto">Pendekatan sistematis kami untuk memastikan setiap keputusan investasi Anda terukur dan terarah.</p>
        </div>

        <div class="relative max-w-5xl mx-auto">


            <div class="grid md:grid-cols-5 gap-8 relative z-10">
                <!-- Step 1 -->
                <div class="group text-center" data-aos="fade-up">
                    <div class="w-16 h-16 bg-[#111] border border-accent/20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-accent group-hover:scale-110 transition-all duration-300 shadow-[0_0_20px_rgba(51,232,24,0.1)]">
                        <span class="text-xl font-bold text-accent group-hover:text-black">01</span>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-3">Analisis Kebutuhan</h3>
                    <p class="text-sm text-gray-400 leading-relaxed">Mengidentifikasi profil risiko dan tujuan investasi klien.</p>
                </div>

                <!-- Step 2 -->
                <div class="group text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-[#111] border border-accent/20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-accent group-hover:scale-110 transition-all duration-300 shadow-[0_0_20px_rgba(51,232,24,0.1)]">
                        <span class="text-xl font-bold text-accent group-hover:text-black">02</span>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-3">Perjanjian Pemberian Jasa</h3>
                    <p class="text-sm text-gray-400 leading-relaxed">Menetapkan kesepakatan kerja sama profesional.</p>
                </div>

                <!-- Step 3 -->
                <div class="group text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-[#111] border border-accent/20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-accent group-hover:scale-110 transition-all duration-300 shadow-[0_0_20px_rgba(51,232,24,0.1)]">
                        <span class="text-xl font-bold text-accent group-hover:text-black">03</span>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-3">Perencanaan Strategi</h3>
                    <p class="text-sm text-gray-400 leading-relaxed">Menyusun rencana trading dan memilih instrumen yang sesuai.</p>
                </div>

                <!-- Step 4 -->
                <div class="group text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-[#111] border border-accent/20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-accent group-hover:scale-110 transition-all duration-300 shadow-[0_0_20px_rgba(51,232,24,0.1)]">
                        <span class="text-xl font-bold text-accent group-hover:text-black">04</span>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-3">Pelaksanaan Pendampingan</h3>
                    <p class="text-sm text-gray-400 leading-relaxed">Implementasi strategi dengan pendampingan intensif oleh WPA.</p>
                </div>

                <!-- Step 5 -->
                <div class="group text-center" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 bg-[#111] border border-accent/20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-accent group-hover:scale-110 transition-all duration-300 shadow-[0_0_20px_rgba(51,232,24,0.1)]">
                        <span class="text-xl font-bold text-accent group-hover:text-black">05</span>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-3">Monitoring & Evaluasi</h3>
                    <p class="text-sm text-gray-400 leading-relaxed">Meninjau dan menyesuaikan strategi secara berkala.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Komitmen (Kepatuhan & Manajemen Risiko) -->
<section class="py-20 bg-black relative overflow-hidden">
    
    <!-- Background Decor -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-accent/5 blur-[100px] rounded-full"></div>

    <!-- WRAPPER CENTER -->
    <div class="max-w-5xl mx-auto px-6">
        
        <div class="flex flex-col items-center text-center">

            <!-- HEADER -->
            <div class="w-full md:w-3/4" data-aos="fade-up">
                <span class="text-accent font-bold tracking-widest text-xs uppercase mb-2 block">
                    KOMITMEN KAMI
                </span>

                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                    Kepatuhan & <span class="text-accent">Manajemen Risiko</span>
                </h2>

                <!-- GARIS AKSEN -->
                <div class="w-16 h-[2px] bg-accent mx-auto mb-6"></div>

                <p class="text-gray-300 mb-10 leading-loose">
                    Kepercayaan adalah fondasi utama kami. Almai berkomitmen menerapkan standar kepatuhan tinggi dan sistem manajemen risiko berlapis untuk memastikan setiap Klien bertransaksi secara 
                    <span class="text-white font-semibold">aman, transparan, dan terukur</span>.
                </p>
            </div>

            <!-- LIST (CARD STYLE BIAR LEBIH MENARIK) -->
            <div class="w-full max-w-4xl grid sm:grid-cols-2 md:grid-cols-3 gap-6">

                <!-- ITEM -->
                <div class="bg-white/5 border border-white/10 rounded-xl p-6 text-left hover:border-accent transition duration-300">
                    <div class="w-10 h-10 bg-accent/10 rounded-lg flex items-center justify-center text-accent mb-4">
                        <i class="fas fa-gavel"></i>
                    </div>
                    <h4 class="text-white font-bold mb-2">Regulasi & Legalitas</h4>
                    <p class="text-sm text-gray-400 leading-relaxed">
                        Seluruh operasional tunduk pada regulasi resmi dan berada di bawah pengawasan 
                        <span class="text-white">BAPPEBTI | OJK | BANK INDONESIA</span>.
                    </p>
                </div>

                <!-- ITEM -->
                <div class="bg-white/5 border border-white/10 rounded-xl p-6 text-left hover:border-accent transition duration-300">
                    <div class="w-10 h-10 bg-accent/10 rounded-lg flex items-center justify-center text-accent mb-4">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <h4 class="text-white font-bold mb-2">Edukasi Risiko</h4>
                    <p class="text-sm text-gray-400 leading-relaxed">
                        Setiap Klien wajib memahami risiko melalui proses 
                        <span class="text-white">Risk Disclosure</span> sebelum memulai transaksi.
                    </p>
                </div>

                <!-- ITEM -->
                <div class="bg-white/5 border border-white/10 rounded-xl p-6 text-left hover:border-accent transition duration-300">
                    <div class="w-10 h-10 bg-accent/10 rounded-lg flex items-center justify-center text-accent mb-4">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <h4 class="text-white font-bold mb-2">Transparansi Penuh</h4>
                    <p class="text-sm text-gray-400 leading-relaxed">
                        Informasi, biaya, dan laporan transaksi disajikan secara terbuka untuk menjaga kepercayaan Klien.
                    </p>
                </div>

            </div>

        </div>
    </div>
</section>




<!-- Tim Kami -->
<section class="py-16 pb-24 relative" id="team-section">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl md:text-5xl font-bold mb-12 text-center" data-aos="fade-up">TIM <span class="text-accent">Almai</span></h2>


        <style>
            .team-col-width {
                width: 20%;
                /* Mobile: 5 columns */
            }

            @media (min-width: 768px) {
                .team-col-width {
                    width: 12.5%;
                }

                /* Tablet: 8 columns */
            }

            @media (min-width: 1024px) {
                .team-col-width {
                    width: 6.666666%;
                }

                /* Desktop: 15 columns */
            }
        </style>

        <div class="flex flex-wrap justify-center gap-0 max-w-4xl mx-auto" data-aos="fade-up">
            <?php
            // Team data now comes from database via controller
            // If no team data, show empty state
            if (empty($team)):
            ?>
                <div class="w-full text-center py-12">
                    <p class="text-gray-400">Belum ada data tim</p>
                </div>
            <?php else: ?>
                <?php foreach ($team as $index => $member):
                    // Use photo from database or fallback to UI Avatars
                    $photoUrl = !empty($member['photo'])
                        ? base_url('file/' . $member['photo'])
                        : 'https://ui-avatars.com/api/?name=' . urlencode($member['name']) . '&background=33E818&color=000&size=150';
                ?>
                    <div class="relative group aspect-square team-member team-col-width overflow-hidden transition-all duration-300 ease-out outline outline-1 outline-black/20">
                        <img src="<?= $photoUrl ?>" alt="<?= esc($member['name']) ?>" class="w-full h-full object-cover filter grayscale group-hover:grayscale-0 transition-all duration-300">
                        <div class="absolute inset-0 bg-black/90 opacity-0 group-hover:opacity-100 transition-all duration-300 flex flex-col items-center justify-center px-1 py-1 text-center">
                            <h3 class="text-white font-bold uppercase tracking-tight mb-0.5" style="font-size: 5px; line-height: 1.3;"><?= esc($member['name']) ?></h3>
                            <p class="text-[#33E818]" style="font-size: 4px; line-height: 1.2;"><?= esc($member['role']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <style>
            .team-member {
                transition: all 0.3s ease-out;
            }

            .team-member:hover,
            .team-member.active {
                transform: scale(2.0);
                z-index: 50;
                box-shadow: 0 0 25px rgba(51, 232, 24, 0.6);
                border-radius: 12px 12px 0 0;
                /* Subtle rounded corners */
            }

            /* Also round the image and overlay inside */
            .team-member:hover img,
            .team-member.active img,
            .team-member:hover .absolute,
            .team-member.active .absolute {
                border-radius: 12px 12px 0 0;
            }
        </style>

        <script>
            // Add click/tap support for mobile devices
            document.addEventListener('DOMContentLoaded', function() {
                const teamMembers = document.querySelectorAll('.team-member');

                teamMembers.forEach(member => {
                    member.addEventListener('click', function(e) {
                        // Remove active class from all other members
                        teamMembers.forEach(m => {
                            if (m !== this) m.classList.remove('active');
                        });

                        // Toggle active class on clicked member
                        this.classList.toggle('active');
                    });
                });

                // Close on outside click
                document.addEventListener('click', function(e) {
                    if (!e.target.closest('.team-member')) {
                        teamMembers.forEach(m => m.classList.remove('active'));
                    }
                });
            });
        </script>
    </div>
</section>

<!-- Hidden Sections (kept as requested previously) -->
<?php /*
<!-- Artikel Terbaru (Hidden) -->
...
*/ ?>

<?= $this->endSection() ?>