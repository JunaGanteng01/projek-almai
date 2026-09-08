<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="pt-32 pb-20 bg-dark-bg min-h-screen">
    <div class="container mx-auto px-6">
        <!-- Breadcrumb -->
        <a href="<?= base_url('about') ?>" class="inline-flex items-center gap-2 text-gray-400 hover:text-accent mb-12 transition group text-sm font-medium">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Tentang Kami
        </a>

        <!-- Header -->
        <div class="text-center max-w-5xl mx-auto mb-20" data-aos="fade-up">
            <h1 class="text-3xl md:text-5xl lg:text-6xl font-black mb-6 leading-tight">
                PERAN PENTING ALMAI<br>
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-accent via-green-400 to-emerald-500">
                    SEBAGAI PENASIHAT BERJANGKA
                </span>
            </h1>
            <p class="text-xl md:text-2xl text-gray-400 font-light tracking-wide">
                DALAM TRANSFORMASI PERDAGANGAN BERJANGKA KOMODITI DI ERA DIGITAL
            </p>
        </div>

        <!-- Content Wrapper -->
        <div class="max-w-7xl mx-auto">
            
            <!-- Intro Section with Image -->
            <div class="grid lg:grid-cols-2 gap-12 items-center mb-24">
                <!-- Image Column -->
                <div class="relative group" data-aos="fade-right" data-aos-delay="100">
                    <div class="absolute -inset-1 bg-gradient-to-r from-accent to-blue-600 rounded-3xl blur opacity-25 group-hover:opacity-75 transition duration-1000 group-hover:duration-200"></div>
                    <div class="relative rounded-3xl overflow-hidden border border-white/10 shadow-2xl">
                        <img src="<?= base_url('images/penasihat.jpg') ?>" alt="Digital Commodity Trading" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-60"></div>
                        <div class="absolute bottom-6 left-6 right-6">
                            <p class="text-white font-bold text-lg">Transformasi Digital</p>
                            <p class="text-accent text-sm">Masa Depan Perdagangan Berjangka</p>
                        </div>
                    </div>
                </div>

                <!-- Text Column -->
                <div class="bg-[#111] border border-white/10 rounded-3xl p-8 md:p-10 relative overflow-hidden group" data-aos="fade-left" data-aos-delay="200">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-accent/5 rounded-full blur-3xl -mr-32 -mt-32 pointer-events-none"></div>
                    
                    <div class="prose prose-lg prose-invert max-w-none text-gray-300 leading-relaxed font-light">
                        <p class="mb-4">
                            <strong class="text-white font-bold">Indonesia Raya</strong>, merupakan negara produsen utama berbagai komoditas unggulan seperti Minyak kelapa sawit (CPO), kakao, kopi, rempah-rempah dan pertambangan, menjadikan Indonesia sebagai pemain penting di pasar Komoditi global.
                        </p>
                        <p class="mb-4">
                            Selain itu, dengan kemunculan serta pertumbuhan komoditas digital seperti Kripto, Pasar Berjangka akan mengalami transformasi secara signifikan. Keduanya, baik komoditas tradisional maupun komoditas digital menawarkan peluang besar bagi investor serta pelaku pasar.
                        </p>
                        <p class="mb-4">
                            Apalagi, dengan diresmikannya Bursa Kripto satu-satunya didunia yang diakui oleh sebuah negara yakni <strong>CFX</strong> dan baru-baru ini juga diresmikannya Bursa CPO oleh Kementerian Perdagangan Republik Indonesia, Pasar Berjangka di Indonesia berpotensi mengalami pertumbuhan yang sangat menarik.
                        </p>
                        
                        <div class="mt-6 p-5 bg-white/5 rounded-xl border-l-4 border-accent">
                            <p class="italic text-white text-base">
                                "PT. Alma Indonesia Raya (Almai) adalah perusahaan Penasihat Berjangka yang berkomitmen untuk berperan dalam transformasi Perdagangan Berjangka Komoditi di Indonesia, khususnya di era digital saat ini."
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Roles Section -->
            <div class="mb-24">
                <div class="flex items-center gap-4 mb-10" data-aos="fade-right">
                    <div class="h-px bg-white/20 flex-1"></div>
                    <h2 class="text-3xl font-bold text-white uppercase tracking-wider">Peran Utama Kami</h2>
                    <div class="h-px bg-white/20 flex-1"></div>
                </div>

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <!-- Role 1 -->
                    <div class="bg-[#111] p-8 rounded-2xl border border-white/10 hover:border-accent/50 hover:bg-white/5 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="100">
                        <div class="w-14 h-14 bg-accent/10 rounded-xl flex items-center justify-center mb-6 group-hover:bg-accent group-hover:scale-110 transition-all duration-500">
                            <i class="fas fa-book-reader text-2xl text-accent group-hover:text-black transition-colors"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-white">Literasi Pasar Berjangka</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">
                            Almai berperan sebagai pendidik bagi investor dan pelaku pasar melalui seminar dan lokakarya untuk meningkatkan pemahaman mengenai mekanisme dan risiko pasar.
                        </p>
                    </div>

                    <!-- Role 2 -->
                    <div class="bg-[#111] p-8 rounded-2xl border border-white/10 hover:border-accent/50 hover:bg-white/5 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="200">
                        <div class="w-14 h-14 bg-accent/10 rounded-xl flex items-center justify-center mb-6 group-hover:bg-accent group-hover:scale-110 transition-all duration-500">
                            <i class="fas fa-robot text-2xl text-accent group-hover:text-black transition-colors"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-white">Expert Advisor (Robot Trading)</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">
                            Mengembangkan alat otomatisasi perdagangan berbasis algoritma canggih untuk meningkatkan efisiensi dan memaksimalkan potensi profit trader.
                        </p>
                    </div>

                    <!-- Role 3 -->
                    <div class="bg-[#111] p-8 rounded-2xl border border-white/10 hover:border-accent/50 hover:bg-white/5 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="300">
                        <div class="w-14 h-14 bg-accent/10 rounded-xl flex items-center justify-center mb-6 group-hover:bg-accent group-hover:scale-110 transition-all duration-500">
                            <i class="fas fa-shield-alt text-2xl text-accent group-hover:text-black transition-colors"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-white">Sosialisasi Hedging</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">
                            Memberikan pelatihan tentang strategi lindung nilai (hedging) untuk mengurangi risiko kerugian akibat fluktuasi harga komoditas yang tidak menentu.
                        </p>
                    </div>

                    <!-- Role 4 -->
                    <div class="bg-[#111] p-8 rounded-2xl border border-white/10 hover:border-accent/50 hover:bg-white/5 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="400">
                        <div class="w-14 h-14 bg-accent/10 rounded-xl flex items-center justify-center mb-6 group-hover:bg-accent group-hover:scale-110 transition-all duration-500">
                            <i class="fas fa-cube text-2xl text-accent group-hover:text-black transition-colors"></i>
                        </div>
                        <h3 class="text-xl font-bold mb-3 text-white">Pemanfaatan Blockchain</h3>
                        <p class="text-gray-400 text-sm leading-relaxed">
                            Memanfaatkan teknologi blockchain dan big data untuk meningkatkan transparansi dan efisiensi serta mendorong digitalisasi layanan.
                        </p>
                    </div>

                    <!-- Role 5 -->
                    <div class="bg-[#111] p-8 rounded-2xl border border-white/10 hover:border-accent/50 hover:bg-white/5 transition-all duration-300 group col-span-1 md:col-span-2 lg:col-span-2" data-aos="fade-up" data-aos-delay="500">
                        <div class="flex flex-col md:flex-row gap-6 items-start md:items-center">
                            <div class="w-14 h-14 bg-accent/10 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-accent group-hover:scale-110 transition-all duration-500">
                                <i class="fas fa-university text-2xl text-accent group-hover:text-black transition-colors"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold mb-2 text-white">Advokasi Pelaku Pasar</h3>
                                <p class="text-gray-400 text-sm leading-relaxed">
                                    Berperan sebagai penghubung antara klien dan regulator, menyampaikan aspirasi untuk menciptakan kebijakan yang mendukung pertumbuhan industri dan meningkatkan daya saing Indonesia di pasar global.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Core Values -->
            <div class="relative bg-gradient-to-r from-[#111] to-black rounded-3xl p-10 border border-white/10 overflow-hidden" data-aos="fade-up">
                <div class="absolute top-0 right-0 w-full h-full bg-[url('/img/grid.png')] opacity-10"></div>
                
                <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 relative z-10">CORE VALUE INNOVATION ALMAI</h2>
                
                <div class="grid gap-10 md:grid-cols-3 relative z-10">
                    <!-- Value 1 -->
                    <div class="text-center group">
                        <div class="w-20 h-20 mx-auto bg-gradient-to-br from-accent to-green-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-accent/20 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-handshake text-3xl text-black"></i>
                        </div>
                        <h3 class="text-xl font-black mb-3 text-white tracking-widest">TRUSTED</h3>
                        <p class="text-gray-400 text-sm leading-relaxed px-4">
                            Menciptakan lingkungan aman dengan nasihat yang transparan dan jujur. Informasi jelas dan layanan berkualitas tinggi.
                        </p>
                    </div>

                    <!-- Value 2 -->
                    <div class="text-center group">
                        <div class="w-20 h-20 mx-auto bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-blue-500/20 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-user-tie text-3xl text-white"></i>
                        </div>
                        <h3 class="text-xl font-black mb-3 text-white tracking-widest">SPECIALIST</h3>
                        <p class="text-gray-400 text-sm leading-relaxed px-4">
                            Tenaga profesional bersertifikasi dengan keahlian mendalam yang terus diperbarui sesuai tren terkini.
                        </p>
                    </div>

                    <!-- Value 3 -->
                    <div class="text-center group">
                        <div class="w-20 h-20 mx-auto bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mb-6 shadow-lg shadow-purple-500/20 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-bolt text-3xl text-white"></i>
                        </div>
                        <h3 class="text-xl font-black mb-3 text-white tracking-widest">EFFICIENT</h3>
                        <p class="text-gray-400 text-sm leading-relaxed px-4">
                            Pemanfaatan teknologi EA, blockchain, dan big data untuk layanan akurat, cepat, dan aktif 24 jam.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Closing CTA -->
            <div class="text-center mt-20" data-aos="fade-up">
                <p class="text-xl text-gray-300 mb-8 max-w-3xl mx-auto">
                    "Melalui dukungan Almai, masyarakat tidak hanya akan melihat Perdagangan Berjangka sebagai sarana investasi, tetapi juga sebagai metode untuk mengelola risiko dan mencapai kesejahteraan ekonomi yang lebih luas."
                </p>
                <a href="<?= base_url('kontak') ?>" class="inline-block px-10 py-4 bg-accent text-black font-bold rounded-full hover:bg-white hover:scale-105 transition-all duration-300 shadow-[0_0_20px_rgba(51,232,24,0.4)]">
                    Hubungi Kami
                </a>
            </div>

        </div>
    </div>
</div>
<?= $this->endSection() ?>
