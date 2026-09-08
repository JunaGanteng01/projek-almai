<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- SECTION 1 - HERO -->
<section class="relative min-h-screen flex items-center justify-center pt-20 overflow-hidden bg-black">
    <!-- Grid Pattern Overlay -->
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-20 pointer-events-none mix-blend-screen"></div>
    <div class="absolute inset-0 bg-black/50 z-[1] pointer-events-none"></div>
    <div class="absolute inset-0 z-0">
        <img src="https://files-tr8.s3.ap-southeast-1.amazonaws.com/blog_images/riskofruinmeaning_1768201977.webp" class="w-full h-full object-cover opacity-30 grayscale mix-blend-luminosity" alt="Trading Background">
    </div>
    
    <!-- Glow Effect -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[500px] bg-accent/20 blur-[120px] rounded-full pointer-events-none z-[1]"></div>

    <!-- Hero Content -->
    <div class="container mx-auto px-6 relative z-10 text-center">
        <div class="inline-block relative mb-6" data-aos="fade-down">
            <span class="bg-black border border-accent/30 text-accent font-bold px-6 py-2 rounded-full uppercase tracking-widest text-xs flex items-center gap-2">
                <i class="fas fa-radiation"></i> Satuan Tugas Perlindungan Trader
            </span>
        </div>

        <h1 class="text-3xl md:text-5xl lg:text-6xl font-black leading-tight mb-8 tracking-tighter text-white max-w-5xl mx-auto" data-aos="fade-up">
            "Banyak Trader Rugi Bukan Karena Market.<br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-emerald-500">Tapi Karena Sistem yang Tidak Melindungi Mereka."</span>
        </h1>
        
        <p class="text-lg md:text-xl text-gray-300 max-w-3xl mx-auto mb-10 leading-relaxed" data-aos="fade-up" data-aos-delay="100">
            ALMAI hadir sebagai <strong class="text-white">platform advokasi & perlindungan trader pertama di Indonesia</strong> untuk membantu Anda trading dengan aman, transparan, dan terarah secara legal.
        </p>

        <!-- CTA -->
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16" data-aos="fade-up" data-aos-delay="200">
            <a href="<?= base_url('register') ?>" class="w-full sm:w-auto px-8 py-4 bg-accent text-black font-bold rounded-xl hover:bg-green-500 transition-all transform hover:scale-105 shadow-[0_0_20px_rgba(51,232,24,0.3)] flex items-center justify-center gap-2">
                <i class="fas fa-rocket"></i> Gabung Advokasi Sekarang
            </a>
            <a href="https://wa.me/628123456789" target="_blank" class="w-full sm:w-auto px-8 py-4 bg-[#111] text-white font-bold rounded-xl border border-white/10 hover:border-accent hover:text-accent transition-all flex items-center justify-center gap-2">
                <i class="fab fa-whatsapp"></i> Konsultasi Gratis
            </a>
            <a href="<?= base_url('pengaduan') ?>" class="w-full sm:w-auto px-8 py-4 bg-accent/10 text-accent font-bold rounded-xl border border-accent/20 hover:bg-accent hover:text-black transition-all flex items-center justify-center gap-2">
                <i class="fas fa-search-dollar"></i> Cek Broker Anda
            </a>
        </div>
    </div>
</section>

<!-- SECTION 2 - PROBLEM -->
<section class="py-20 relative bg-[#0a0a0a] border-t border-white/5 overflow-hidden">
    <div class="container mx-auto px-6 max-w-6xl">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-2xl md:text-4xl font-bold text-white mb-6">
                Realita Mengerikan di<br>
                <span class="text-accent">Industri Trading</span>
            </h2>
            <p class="text-gray-400 max-w-2xl mx-auto italic">“Anda tidak hanya melawan market. Anda juga melawan sistem yang tidak berpihak.”</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-black p-6 rounded-2xl border border-accent/20 hover:border-accent/50 transition flex flex-col items-center text-center" data-aos="fade-up" data-aos-delay="0">
                <img src="https://official-cms-images.s3.us-east-2.amazonaws.com/static/images/marketNews/20230918/revenge-trading.jpg" class="w-24 h-24 object-cover rounded-full border-2 border-accent/30 mb-4 filter grayscale" alt="Edukasi Sesat">
                <h4 class="text-white font-bold mb-2">Edukasi Sesat</h4>
                <p class="text-sm text-gray-400">Banyak trader loss karena edukasi yang salah dan overclaim.</p>
            </div>
            <div class="bg-black p-6 rounded-2xl border border-accent/20 hover:border-accent/50 transition flex flex-col items-center text-center" data-aos="fade-up" data-aos-delay="100">
                <img src="https://www.foreximf.com/upload/image/blog/trader_forex_penipu_modus_janji_tanpa_loss_yang_menjebak_trader_pemula/1_forex_penipu_scam.jpg" class="w-24 h-24 object-cover rounded-full border-2 border-accent/30 mb-4 filter grayscale" alt="Broker Manipulatif">
                <h4 class="text-white font-bold mb-2">Broker Manipulatif</h4>
                <p class="text-sm text-gray-400">Broker tidak transparan, sengaja menahan profit (WD).</p>
            </div>
            <div class="bg-black p-6 rounded-2xl border border-accent/20 hover:border-accent/50 transition flex flex-col items-center text-center" data-aos="fade-up" data-aos-delay="200">
                <img src="https://png.pngtree.com/thumb_back/fh260/background/20251204/pngtree-a-stressed-stock-trader-screams-in-frustration-as-he-watches-the-image_20731704.webp" class="w-24 h-24 object-cover rounded-full border-2 border-accent/30 mb-4 filter grayscale" alt="Dibungkam">
                <h4 class="text-white font-bold mb-2">Dibungkam Sendiri</h4>
                <p class="text-sm text-gray-400">Trader dibiarkan sendirian tanpa ada perlindungan.</p>
            </div>
            <div class="bg-black p-6 rounded-2xl border border-accent/20 hover:border-accent/50 transition flex flex-col items-center text-center" data-aos="fade-up" data-aos-delay="300">
                <img src="https://tradeciety.com/hubfs/Imported_Blog_Media/16498881_s1.jpg" class="w-24 h-24 object-cover rounded-full border-2 border-accent/30 mb-4 filter grayscale" alt="Buntu Mengadu">
                <h4 class="text-white font-bold mb-2">Buntu Mengadu</h4>
                <p class="text-sm text-gray-400">Tidak ada tempat mengadu yang independen dan responsif.</p>
            </div>
        </div>
    </div>
</section>

<!-- SECTION 3 - SOLUSI ALMAI -->
<section class="py-20 bg-black relative">
    <div class="absolute inset-0 bg-accent/5 skew-y-1 transform origin-top-left z-0"></div>
    <div class="container mx-auto px-6 max-w-6xl text-center relative z-10">
        <h2 class="text-3xl md:text-5xl font-bold text-white mb-6 uppercase tracking-tight" data-aos="fade-up">
            ALMAI Dirancang Sebagai <span class="text-accent">Solusi Terintegrasi</span>
        </h2>
        <p class="text-gray-400 text-lg mb-12 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
            Membangun ekosistem perlindungan trader tiga pilar utama:
        </p>

        <div class="grid md:grid-cols-3 gap-6 lg:gap-10 text-left">
            <div class="bg-[#111] border border-white/5 p-8 rounded-2xl shadow-xl hover:border-accent/30 transition group" data-aos="fade-up">
                <i class="fas fa-graduation-cap text-4xl text-accent mb-6"></i>
                <h4 class="text-xl font-bold text-white mb-3">1. Edukasi Trading Terarah</h4>
                <p class="text-gray-400 text-sm leading-relaxed mb-4">Membangun fundamen teknis yang terkalibrasi.</p>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><i class="fas fa-check-circle text-accent mr-2"></i> Kurikulum Jelas</li>
                    <li><i class="fas fa-check-circle text-accent mr-2"></i> Anti-Misleading Strategy</li>
                    <li><i class="fas fa-check-circle text-accent mr-2"></i> Eksekusi Risk Management</li>
                </ul>
            </div>
            <div class="bg-[#111] border border-white/5 p-8 rounded-2xl shadow-xl hover:border-accent/30 transition group" data-aos="fade-up" data-aos-delay="100">
                <i class="fas fa-balance-scale text-4xl text-accent mb-6"></i>
                <h4 class="text-xl font-bold text-white mb-3">2. Advokasi Perlindungan</h4>
                <p class="text-gray-400 text-sm leading-relaxed mb-4">Garda pelindung dan fasilitator hak nasabah.</p>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><i class="fas fa-check-circle text-accent mr-2"></i> Pendampingan Kasus</li>
                    <li><i class="fas fa-check-circle text-accent mr-2"></i> Mediasi Dengan Broker</li>
                    <li><i class="fas fa-check-circle text-accent mr-2"></i> Literasi Legal Trading</li>
                </ul>
            </div>
            <div class="bg-[#111] border border-white/5 p-8 rounded-2xl shadow-xl hover:border-accent/30 transition group" data-aos="fade-up" data-aos-delay="200">
                <i class="fas fa-chart-line text-4xl text-accent mb-6"></i>
                <h4 class="text-xl font-bold text-white mb-3">3. Monitoring Transparansi</h4>
                <p class="text-gray-400 text-sm leading-relaxed mb-4">Evaluasi pialang dengan AI dan verifikasi fakta.</p>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><i class="fas fa-check-circle text-accent mr-2"></i> Sistem Penilaian Independen</li>
                    <li><i class="fas fa-check-circle text-accent mr-2"></i> Review Berbasis Data</li>
                    <li><i class="fas fa-check-circle text-accent mr-2"></i> Early Warning System</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- SECTION 4 - ALMAI TRUST INDEX -->
<section class="py-24 relative bg-[#0a0a0a] border-t border-white/10 border-b overflow-hidden">
    <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-accent/10 blur-[150px] rounded-full pointing-events-none"></div>

    <div class="container mx-auto px-6 max-w-6xl relative z-10">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="text-accent font-bold tracking-widest text-xs uppercase mb-2 block">GAME CHANGER</span>
            <h2 class="text-3xl md:text-5xl font-bold text-white mb-4">Almai <span class="text-accent">Trust Index</span></h2>
            <p class="text-gray-400 max-w-2xl mx-auto">Sistem scoring untuk menilai broker secara objektif layaknya "Bloomberg"-nya broker trading Indonesia.</p>
        </div>

        <div class="grid md:grid-cols-2 gap-x-8 gap-y-12 lg:gap-x-12 lg:gap-y-16 items-center">
            
            <div data-aos="fade-right">
                <div class="bg-black border border-accent/30 p-8 rounded-3xl shadow-2xl relative overflow-hidden">
                    <h3 class="text-2xl font-bold text-white mb-6">Metrik Penilaian:</h3>
                    
                    <ul class="space-y-4 text-gray-300 font-medium mb-8">
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-accent"></i> Transparansi eksekusi & spread</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-accent"></i> Reputasi pengalaman user</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-accent"></i> Legalitas, izin & regulasi BAPPEBTI</li>
                        <li class="flex items-center gap-3"><i class="fas fa-check-circle text-accent"></i> Riwayat penyelesaian aduan / complaint</li>
                    </ul>

                    <div class="p-4 bg-[#111] border-l-4 border-accent rounded-r-xl">
                        <p class="text-white text-sm font-bold uppercase mb-2">Output Fitur:</p>
                        <ul class="text-accent space-y-1 text-sm">
                            <li>⭐ Skor Broker (0–100)</li>
                            <li>⚠️ Peringatan Otomatis (Warning System)</li>
                            <li>📈 Ranking Pialang Nasional</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div data-aos="fade-left">
                <div class="grid grid-cols-2 gap-4">
                    <img src="https://cdn.dribbble.com/userupload/17904890/file/original-e7e5eb024ff034296b960839e48b8e36.png?format=webp&resize=400x300&vertical=center" class="rounded-xl object-cover w-full h-32 border border-white/10 opacity-70 filter grayscale hover:grayscale-0 transition" alt="App">
                    <img src="https://linkurious.com/images/uploads/2025/03/criminal-network-graph.png" class="rounded-xl object-cover w-full h-32 border border-white/10 opacity-70 filter grayscale hover:grayscale-0 transition" alt="Graph">
                    <img src="https://www.edrawsoft.com/templates/images/analysis-radar-chart.png" class="rounded-xl object-cover w-full h-40 border border-white/10 col-span-2 filter invert opacity-80" alt="Radar">
                </div>
            </div>

        </div>
    </div>
</section>

<!-- SECTION 5 - GERAKAN NASIONAL -->
<section class="py-20 bg-black">
    <div class="container mx-auto px-6 max-w-5xl text-center">
        <h2 class="text-3xl font-bold text-white mb-6 uppercase tracking-widest" data-aos="fade-up">Menuju Gerakan <span class="text-accent">Nasional</span></h2>
        <p class="text-gray-400 mb-16 max-w-2xl mx-auto">ALMAI bukan sekadar platform. Ini adalah inisiatif menuju ekosistem trading yang sehat & berintegritas bekerja sama dengan ujung tombak negara.</p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
            <div class="p-6 border border-white/5 rounded-2xl bg-[#0a0a0a] hover:bg-accent/5 transition duration-500" data-aos="flip-left" data-aos-delay="0">
                <i class="fas fa-landmark text-4xl text-accent mb-4"></i>
                <h3 class="text-xl font-bold text-white mb-2">Regulator</h3>
                <p class="text-sm text-gray-400">Pembinaan BAPPEBTI & OJK</p>
            </div>
            <div class="p-6 border border-white/5 rounded-2xl bg-[#0a0a0a] hover:bg-accent/5 transition duration-500" data-aos="flip-left" data-aos-delay="100">
                <i class="fas fa-university text-4xl text-accent mb-4"></i>
                <h3 class="text-xl font-bold text-white mb-2">Akademisi</h3>
                <p class="text-sm text-gray-400">Pusat riset finansial kampus</p>
            </div>
            <div class="p-6 border border-white/5 rounded-2xl bg-[#0a0a0a] hover:bg-accent/5 transition duration-500" data-aos="flip-left" data-aos-delay="200">
                <i class="fas fa-building text-4xl text-accent mb-4"></i>
                <h3 class="text-xl font-bold text-white mb-2">Pelaku Industri</h3>
                <p class="text-sm text-gray-400">Broker resmi bersertifikasi</p>
            </div>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4" data-aos="zoom-in">
            <img src="https://cdn.antaranews.com/cache/1200x800/2024/10/07/publikasi_1728301139_6703c8534337a.jpeg" class="w-full h-32 object-cover rounded-xl border border-white/10 opacity-70 filter grayscale" alt="Activity">
            <img src="https://images.hukumonline.com/frontend/lt5eeb2c94e74cc/lt5eeb2d2072df9.jpg" class="w-full h-32 object-cover rounded-xl border border-white/10 opacity-70 filter grayscale" alt="News">
            <img src="https://klimg.com/merdeka.com/i/w/news/2021/08/17/1341930/540x270/komunitas-forex-nfc-edukasi-trader-indonesia-di-hut-ke-3.jpg" class="w-full h-32 object-cover rounded-xl border border-white/10 opacity-70 filter grayscale" alt="Community">
            <img src="https://www.lemon8-app.com/seo/image?index=4&item_id=7365061867431019009&sign=24a3549638c4ff20606834156bff77f2" class="w-full h-32 object-cover rounded-xl border border-white/10 opacity-70 filter grayscale" alt="Edukasi">
        </div>
    </div>
</section>

<!-- SECTION 6 - LEGALITAS -->
<section class="py-16 bg-[#0a0a0a] border-y border-white/10 relative overflow-hidden flex items-center justify-center text-center">
    <div class="container mx-auto px-6 max-w-6xl">
        <h2 class="text-sm font-bold text-gray-500 mb-8 uppercase tracking-[0.3em]">Legalitas & Kredibilitas</h2>
        <div class="flex flex-wrap items-center justify-center gap-10 md:gap-20 opacity-60">
            <span class="text-white font-black text-2xl md:text-3xl">BAPPEBTI</span>
            <span class="text-white font-black text-2xl md:text-3xl">OJK</span>
            <span class="text-white font-black text-2xl md:text-3xl">KOMINFO</span>
            <img src="<?= base_url('images/almai-full.png') ?>" alt="Almai" class="h-8 md:h-10 border-l border-white/20 pl-6 brightness-0 invert" onerror="this.src=''; this.className='hidden'">
        </div>
    </div>
</section>

<!-- SECTION 7 - SOCIAL PROOF -->
<section class="py-20 relative bg-black overflow-hidden">
    <div class="container mx-auto px-6 max-w-6xl">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-2xl md:text-4xl font-bold text-white mb-6">
                Sudah Dipercaya Ribuan<br>
                <span class="text-accent">Trader Indonesia</span>
            </h2>
            <div class="flex justify-center gap-1 text-accent text-xl mb-4">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
            <p class="text-gray-400 max-w-2xl mx-auto italic">"Testimoni nyata dari member yang terselamatkan."</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-[#111] p-6 rounded-2xl border border-accent/20 flex flex-col items-center text-center" data-aos="fade-up" data-aos-delay="0">
                <img src="https://d1csarkz8obe9u.cloudfront.net/posterpreviews/happy-customer-testimonial-design-template-9558d70461a1ed2f0cab61139eb8617f_screen.jpg?ts=1689072816" class="w-16 h-16 object-cover rounded-full mb-4 opacity-80 filter grayscale" alt="Testimoni">
                <h4 class="text-white font-bold mb-2 cursor-pointer hover:text-accent">Andika S.</h4>
                <p class="text-sm text-gray-400">"Sistem ALMAI sangat membantu saya memilah broker mana yang aman digunakan."</p>
            </div>
            <div class="bg-[#111] p-6 rounded-2xl border border-accent/20 flex flex-col items-center text-center" data-aos="fade-up" data-aos-delay="100">
                <img src="https://cdn.outrank.so/152c88fb-b876-4fa1-b7c4-18b395c01f85/9a82f3b4-670d-421a-b5d0-725c41728f29.jpg" class="w-16 h-16 object-cover rounded-full mb-4 opacity-80 filter grayscale" alt="Testimoni">
                <h4 class="text-white font-bold mb-2 cursor-pointer hover:text-accent">Dewi Lestari</h4>
                <p class="text-sm text-gray-400">"Berkat perlindungan ALMAI, profit saya yang sempat ditahan berhasil dicairkan!"</p>
            </div>
            <div class="bg-[#111] p-6 rounded-2xl border border-accent/20 flex flex-col items-center text-center" data-aos="fade-up" data-aos-delay="200">
                <img src="https://img.yumpu.com/37736343/1/500x640/spring-2012-feature-article-a-client-success-story-the-personal-.jpg" class="w-16 h-16 object-cover rounded-full mb-4 opacity-80 filter grayscale" alt="Testimoni">
                <h4 class="text-white font-bold mb-2 cursor-pointer hover:text-accent">Budi S.</h4>
                <p class="text-sm text-gray-400">"Edukasi di sini benar-benar beda dan tanpa jebakan afiliasi. Sangat berbobot."</p>
            </div>
            <div class="bg-accent text-black p-6 rounded-2xl flex flex-col items-center text-center" data-aos="fade-up" data-aos-delay="300">
                <i class="fas fa-users text-4xl mb-4 opacity-50"></i>
                <h4 class="font-black mb-2 text-2xl mt-2">10.000+</h4>
                <p class="text-sm font-bold opacity-80">Target Utilisasi Member Tahun Pertama</p>
            </div>
        </div>
    </div>
</section>

<!-- SECTION 8 - VALUE OFFER -->
<section class="py-20 bg-[#0a0a0a]">
    <div class="container mx-auto px-6 max-w-5xl text-center">
        <h2 class="text-2xl font-bold text-white mb-10 uppercase tracking-widest">Dengan ALMAI Anda <span class="text-accent">Mendapatkan:</span></h2>

        <div class="flex flex-wrap justify-center gap-4 md:gap-8">
            <div class="bg-black border border-white/10 px-6 py-4 rounded-xl flex items-center gap-3"><i class="fas fa-check-circle text-accent"></i> <span class="text-white text-sm font-bold">Akses Edukasi Logis</span></div>
            <div class="bg-black border border-white/10 px-6 py-4 rounded-xl flex items-center gap-3"><i class="fas fa-check-circle text-accent"></i> <span class="text-white text-sm font-bold">Perlindungan Hukum</span></div>
            <div class="bg-black border border-white/10 px-6 py-4 rounded-xl flex items-center gap-3"><i class="fas fa-check-circle text-accent"></i> <span class="text-white text-sm font-bold">Insight Data Pialang</span></div>
            <div class="bg-black border border-white/10 px-6 py-4 rounded-xl flex items-center gap-3"><i class="fas fa-check-circle text-accent"></i> <span class="text-white text-sm font-bold">Komunitas Sehat</span></div>
            <div class="bg-black border border-white/10 px-6 py-4 rounded-xl flex items-center gap-3"><i class="fas fa-check-circle text-accent"></i> <span class="text-white text-sm font-bold">Reward (Almai Poin)</span></div>
        </div>
    </div>
</section>

<!-- SECTION 9 - FINAL CTA -->
<section class="py-24 bg-accent relative overflow-hidden">
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 mix-blend-multiply"></div>
    <div class="container mx-auto px-6 max-w-4xl text-center relative z-10">
        <h2 class="text-3xl md:text-5xl font-black text-black mb-6 uppercase tracking-tight" data-aos="fade-up">
            "Trader Cerdas, Bukan Trader Nekat"
        </h2>
        <p class="text-black/70 text-lg md:text-xl font-medium mb-10 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
            Siap untuk trading dengan lebih aman maksimal? Jangan tunggu sampai saldo Anda lenyap karena sistem yang tidak adil.
        </p>
        
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4" data-aos="zoom-in" data-aos-delay="200">
            <a href="<?= base_url('register') ?>" class="w-full sm:w-auto px-8 py-4 bg-black text-white font-bold rounded-xl hover:bg-gray-900 hover:scale-105 transition-all shadow-xl flex items-center justify-center gap-2">
                <i class="fas fa-user-plus"></i> Gabung Sekarang
            </a>
            <a href="https://wa.me/628123456789" class="w-full sm:w-auto px-8 py-4 bg-transparent border-2 border-black text-black font-bold rounded-xl hover:bg-black hover:text-white transition-all flex items-center justify-center gap-2">
                <i class="fab fa-whatsapp"></i> Konsultasi Gratis
            </a>
            <a href="<?= base_url('pengaduan') ?>" class="w-full sm:w-auto px-8 py-4 bg-white/20 text-black border border-black/30 font-bold rounded-xl hover:bg-black hover:text-white transition-all flex items-center justify-center gap-2">
                <i class="fas fa-search-dollar"></i> Cek Broker Anda
            </a>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
