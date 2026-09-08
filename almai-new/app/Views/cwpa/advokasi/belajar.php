<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-5MXZW7NP');</script>
    <!-- End Google Tag Manager -->
    <?= $this->include('partials/gtm_datalayer') ?>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-JXVYLB3Z2W"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-JXVYLB3Z2W');
    </script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - Almai</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: { extend: { colors: { 'accent': '#33e818' }, fontFamily: { sans: ['Montserrat', 'sans-serif'] } } }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        html, body { background-color: #050505; color: #ffffff; }
        .module-item.completed { border-left: 3px solid #33e818; }
        .module-item.active { background: rgba(51,232,24,0.1); border-left: 3px solid #33e818; }
        .module-item.locked { opacity: 0.5; }
        
        /* Custom scrollbar for module list */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #333; border-radius: 10px; }
        
        /* Material content styling */
        .material-content h2 { font-size: 1.5rem; font-weight: 800; color: #33e818; margin-top: 2rem; margin-bottom: 1rem; }
        .material-content p { color: #9ca3af; line-height: 1.8; margin-bottom: 1.5rem; }
        .material-content ul { margin-bottom: 1.5rem; }
        .material-content li { color: #9ca3af; display: flex; align-items: flex-start; gap: 0.5rem; margin-bottom: 0.8rem; }
        .material-content li i { color: #33e818; margin-top: 0.25rem; font-size: 0.75rem; }
        .material-content blockquote { border-left: 4px solid #33e818; background: rgba(51,232,24,0.05); padding: 1.5rem; border-radius: 0 1rem 1rem 0; margin: 2rem 0; font-style: italic; color: #d1d5db; }
        .material-img { width: 100%; border-radius: 2rem; margin: 2rem 0; border: 1px solid rgba(255,255,255,0.05); }
    </style>
</head>
<body class="antialiased">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-XXXXXXX"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div class="flex flex-col lg:flex-row min-h-screen relative overflow-hidden">
        
        <!-- Mobile Header -->
        <div class="lg:hidden fixed top-0 left-0 right-0 z-50 bg-[#0a0a0a] border-b border-white/10 px-4 py-3 flex justify-between items-center">
            <a href="<?= base_url('cwpa/dashboard/advokasi') ?>" class="flex items-center gap-2 text-gray-400 hover:text-white">
                <i class="fas fa-arrow-left"></i>
                <span class="text-sm">Kembali</span>
            </a>
            <button onclick="toggleSidebar()" class="p-2 hover:bg-white/10 rounded-lg">
                <i class="fas fa-list text-xl"></i>
            </button>
        </div>

        <!-- Sidebar - Module List -->
        <aside id="sidebar" class="w-full lg:w-80 bg-[#0a0a0a] border-r border-white/10 fixed lg:relative h-full z-40 transform -translate-x-full lg:translate-x-0 transition-transform overflow-y-auto custom-scrollbar">
            <div class="p-4 border-b border-white/10 sticky top-0 bg-[#0a0a0a] z-10">
                <a href="<?= base_url('cwpa/dashboard/advokasi') ?>" class="hidden lg:flex items-center gap-2 text-gray-400 hover:text-white mb-4">
                    <i class="fas fa-arrow-left"></i>
                    <span class="text-sm">Kembali ke Advokasi</span>
                </a>
                <h2 class="font-bold text-lg leading-tight" id="kelasTitle">PROGRAM ADVOKASI TRADER PROFESIONAL (7 HARI)</h2>
                <div class="flex items-center gap-2 mt-2">
                    <div class="w-6 h-6 rounded-full bg-accent/20 flex items-center justify-center">
                        <i class="fas fa-shield-halved text-accent text-[10px]"></i>
                    </div>
                    <span class="text-[10px] text-gray-400 font-medium tracking-tight">Legalitas • Market • Risiko • Teknologi</span>
                </div>
            </div>
            
            <!-- Modules List -->
            <div class="p-2" id="modulesList"></div>
        </aside>

        <!-- Sidebar Overlay -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-30 hidden lg:hidden" onclick="toggleSidebar()"></div>

        <!-- Main Content -->
        <main class="flex-1 pt-14 lg:pt-0 relative">
            <!-- Header Image / Banner -->
            <div class="h-64 md:h-80 w-full relative overflow-hidden">
                <img id="headerBanner" src="https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=2070&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-[#050505] via-[#050505]/40 to-transparent"></div>
            </div>

            <!-- Content Area Container -->
            <div class="relative min-h-[600px]">
                <?php if (!$isPaid): ?>
                <!-- Local Content Lock Overlay -->
                <div class="absolute inset-0 z-30 bg-black/40 backdrop-blur-2xl flex items-center justify-center p-6">
                    <div class="w-full max-w-lg bg-[#0a0a0a]/80 rounded-[3rem] border border-white/10 p-12 text-center shadow-3xl">
                        <div class="w-20 h-20 bg-accent/10 rounded-full flex items-center justify-center mx-auto mb-6 relative">
                            <div class="absolute inset-0 bg-accent/20 rounded-full animate-pulse opacity-25"></div>
                            <i class="fas fa-lock text-accent text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-black text-white mb-3 uppercase tracking-tighter italic">Konten Premium</h3>
                        <p class="text-gray-500 text-[11px] mb-8 leading-relaxed px-4">
                            Selesaikan pembayaran administrasi advokasi untuk membuka akses materi edukasi lengkap pilar **Core & Advanced**. 
                        </p>
                        <div class="space-y-3">
                            <a href="<?= base_url('daftar-advokasi') ?>" class="block w-full py-4 bg-accent text-black font-black uppercase tracking-widest rounded-2xl hover:bg-white transition-all shadow-lg text-xs">
                                <i class="fas fa-wallet mr-2"></i> Bayar Biaya Advokasi
                            </a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Content Area -->
                <div class="max-w-4xl mx-auto p-6 md:p-12 <?= !$isPaid ? 'blur-md grayscale' : '' ?>">
                    <!-- Module Navigation -->
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12 border-b border-white/5 pb-8">
                        <div>
                            <span class="inline-block px-3 py-1 bg-accent/10 border border-accent/20 text-accent text-[10px] font-black uppercase tracking-widest rounded-full mb-3" id="currentModuleNumber">Modul 1.1</span>
                            <h1 class="text-2xl md:text-3xl font-black mt-1" id="currentModuleTitle">Dasar Legalitas Trading di Indonesia</h1>
                        </div>
                        <div class="flex gap-3">
                            <button onclick="markComplete()" id="completeBtn" class="flex-1 md:flex-none px-8 py-4 bg-accent text-black font-black uppercase tracking-widest rounded-2xl hover:bg-white transition-all text-sm shadow-lg">
                                <i class="fas fa-check mr-2"></i> Tandai Selesai
                            </button>
                            <button onclick="nextModule()" class="flex-1 md:flex-none px-8 py-4 border border-white/10 text-white font-black uppercase tracking-widest rounded-2xl hover:border-accent hover:text-accent transition-all text-sm">
                                Lanjut <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Tabs -->
                    <div class="flex gap-8 border-b border-white/5 mb-10">
                        <button onclick="showTab('materi')" class="tab-btn active pb-4 border-b-2 border-accent text-accent font-black uppercase tracking-widest text-[11px]" data-tab="materi">Isi Materi</button>
                        <button onclick="showTab('resources')" class="tab-btn pb-4 border-b-2 border-transparent text-gray-500 font-black uppercase tracking-widest text-[11px] hover:text-white transition-all" data-tab="resources">E-Book & Lampiran</button>
                        <button onclick="showTab('catatan')" class="tab-btn pb-4 border-b-2 border-transparent text-gray-500 font-black uppercase tracking-widest text-[11px] hover:text-white transition-all" data-tab="catatan">Catatan Riset</button>
                    </div>

                    <!-- Tab Content: Materi -->
                    <div id="tab-materi" class="tab-content">
                        <div id="tab-header-info" class="mb-8 p-6 bg-accent/[0.03] border-l-4 border-accent rounded-r-2xl">
                            <p class="text-accent text-[10px] font-black uppercase tracking-widest mb-2">Target Capaian (Output):</p>
                            <p class="text-white text-sm font-medium leading-relaxed italic" id="moduleOutput">Trader berada di lingkungan trading yang aman dan terproteksi.</p>
                        </div>

                        <div class="material-content" id="moduleMateriContent">
                            <!-- Dynamic content here -->
                        </div>

                        <img id="moduleContentImage" src="" class="material-img hidden" alt="Illustration">
                        
                        <div class="mt-8 p-10 bg-white/5 rounded-[2.5rem] border border-white/5 overflow-hidden relative" id="highlightsContainer">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-accent/5 rounded-full -mr-16 -mt-16 blur-3xl"></div>
                            <h4 class="text-white font-black uppercase tracking-widest text-xs mb-6 flex items-center gap-3">
                                <i class="fas fa-microchip text-accent" id="highlightsIcon"></i> Key Focus / Integrasi
                            </h4>
                            <ul class="space-y-4" id="moduleHighlights">
                                <!-- Dynamic highlights -->
                            </ul>
                        </div>
                    </div>

                    <!-- Tab Content: Resources -->
                    <div id="tab-resources" class="tab-content hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="group p-6 bg-white/5 border border-white/5 rounded-3xl hover:border-accent/30 transition-all">
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="w-12 h-12 bg-red-500/10 rounded-2xl flex items-center justify-center text-red-500">
                                        <i class="fas fa-file-pdf text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-bold text-sm text-white">Modul Advokasi Lengkap</p>
                                        <p class="text-[10px] text-gray-500">PDF • 4.2 MB</p>
                                    </div>
                                </div>
                                <button class="w-full py-3 bg-white/5 border border-white/10 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-white/10 transition-all">
                                    <i class="fas fa-download mr-1"></i> Download
                                </button>
                            </div>
                            <div class="group p-6 bg-white/5 border border-white/5 rounded-3xl hover:border-accent/30 transition-all">
                                <div class="flex items-center gap-4 mb-4">
                                    <div class="w-12 h-12 bg-blue-500/10 rounded-2xl flex items-center justify-center text-blue-500">
                                        <i class="fas fa-gavel text-xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-bold text-sm text-white">Draft Surat Somasi</p>
                                        <p class="text-[10px] text-gray-500">DOCX • 124 KB</p>
                                    </div>
                                </div>
                                <button class="w-full py-3 bg-white/5 border border-white/10 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-white/10 transition-all">
                                    <i class="fas fa-download mr-1"></i> Download
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Content: Catatan -->
                    <div id="tab-catatan" class="tab-content hidden">
                        <div class="bg-white/5 border border-white/5 rounded-[2rem] p-8">
                            <h3 class="text-white font-bold mb-2 text-sm uppercase tracking-widest">Lembar Catatan</h3>
                            <p class="text-[10px] text-gray-500 mb-6">Tuliskan poin penting atau analisis Anda mengenai materi ini.</p>
                            <textarea id="notesArea" class="w-full h-64 bg-black/40 border-2 border-white/5 rounded-2xl p-6 md:p-8 focus:border-accent focus:outline-none text-gray-300 leading-relaxed text-sm transition-all" placeholder="Ketik catatan analisis hukum Anda di sini..."></textarea>
                            <div class="flex justify-end mt-6">
                                <button onclick="saveNotes()" class="px-10 py-4 bg-accent text-black font-black uppercase tracking-widest rounded-2xl text-xs hover:bg-white transition-all shadow-lg">
                                    <i class="fas fa-floppy-disk mr-2"></i> Simpan Catatan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        const isPaid = <?= $isPaid ? 'true' : 'false' ?>;
        // Materials Data
        const modules = [
            { 
                id: 1, 
                title: 'SENIN — Protect | Aman', 
                duration: '45 Menit', 
                completed: false,
                banner: 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?q=80&w=1200', 
                image: 'https://images.unsplash.com/photo-1563986768609-322da13575f3?q=80&w=1200',
                handbook: 'Legalitas & Keamanan Trading. Memahami dasar legalitas, mengenali broker legal vs ilegal, serta sistem keamanan akun dan data.',
                output: 'Trader berada di lingkungan trading yang aman dan terproteksi.',
                topics: [
                    {
                        title: 'Dasar Legalitas Trading di Indonesia',
                        content: `<h2>Dasar Legalitas Trading di Indonesia</h2><p>Trading di Indonesia diatur secara ketat oleh pemerintah untuk melindungi masyarakat dari praktik investasi ilegal. Payung hukum utama adalah UU No. 32 Tahun 1997 tentang Perdagangan Berjangka Komoditi.</p><p>Wewenang pengawasan berada di bawah <b>Bappebti</b> (Badan Pengawas Perdagangan Berjangka Komoditi). Memahami aspek hukum ini adalah langkah awal agar dana Anda terlindungi oleh negara.</p>`
                    },
                    {
                        title: 'Mengenal Broker Legal vs Ilegal',
                        content: `<h2>Mengenal Broker Legal vs Ilegal</h2><p>Broker legal wajib memiliki izin pialang berjangka dari Bappebti dan menggunakan rekening terpisah (Segregated Account) yang diawasi Lembaga Kliring Berjangka.</p><p>Ciri broker ilegal biasanya menawarkan robot trading dengan profit tetap, skema titip dana, atau website yang sering berganti domain karena blokir Kominfo.</p>`
                    },
                    {
                        title: 'Struktur Regulasi & Pengawasan',
                        content: `<h2>Struktur Regulasi & Pengawasan</h2><p>Sistem perdagangan berjangka di Indonesia melibatkan beberapa lembaga utama:</p><ul><li><i class="fas fa-university"></i> <b>Bappebti:</b> Regulator dan pengawas utama.</li><li><i class="fas fa-exchange-alt"></i> <b>Bursa Berjangka (ICDX/JFX):</b> Tempat transaksi dilakukan secara transparan.</li><li><i class="fas fa-file-contract"></i> <b>Lembaga Kliring:</b> Penjamin transaksi dan pengelola dana nasabah.</li></ul>`
                    }
                ],
                highlights: [
                    'Memastikan broker berada dalam jalur legal Bappebti',
                    'Mengamankan akun dengan 2FA dan Manajemen Password',
                    'Memahami jalur resmi penyelesaian sengketa'
                ]
            },
            { 
                id: 2, 
                title: 'SELASA — Understand | Paham', 
                duration: '40 Menit', 
                completed: false,
                banner: 'https://images.unsplash.com/photo-1611974714851-bc20fba05c6d?q=80&w=1200',
                image: 'https://images.unsplash.com/photo-1642104704074-907c0698cbd9?q=80&w=1200',
                handbook: 'Struktur Market & Integrasi Teknologi (AIWE). Memahami cara kerja market, supply & demand, serta bagaimana teknologi membantu membaca dan mengeksekusi market.',
                output: 'Trader memahami market sekaligus mampu memanfaatkan teknologi untuk meningkatkan akurasi dan efisiensi.',
                topics: [
                    {
                        title: 'Cara Kerja Market & Supply Demand',
                        content: `<h2>Cara Kerja Market</h2><p>Market digerakkan oleh hukum permintaan dan penawaran. Peserta pasar mulai dari bank sentral, institusi keuangan, hingga retail trader bersaing untuk mendapatkan harga terbaik.</p>`
                    },
                    {
                        title: 'Integrasi Teknologi: AIWE',
                        content: `<h2>AIWE (Expert Advisor Trading System)</h2><p>AIWE adalah sistem automation trading yang dirancang untuk membantu trader mengeksekusi posisi dengan tingkat disiplin yang tinggi.</p><p>Sistem ini mengeliminasi hambatan emosional dan memastikan manajemen risiko berjalan sesuai rencana secara otomatis.</p>`
                    }
                ],
                highlights: [
                    'AIWE: Eksekusi posisi lebih disiplin',
                    'AIWE: Manajemen risiko lebih terkontrol',
                    'AIWE: Konsistensi strategi tanpa emosi'
                ]
            },
            { 
                id: 3, 
                title: 'RABU — Control | Rasional', 
                duration: '50 Menit', 
                completed: false,
                banner: 'https://images.unsplash.com/photo-1590283603385-17ffb3a7f29f?q=80&w=1200',
                image: 'https://images.unsplash.com/photo-1526628953301-3e589a6a8b74?q=80&w=1200',
                handbook: 'Risk Management & Mindset. Mengelola risiko, menentukan lot, serta mengendalikan emosi dalam trading.',
                output: 'Trader mampu trading secara rasional and terukur.',
                topics: [
                    {
                        title: 'Manajemen Risiko (Risk per Trade)',
                        content: `<h2>Manajemen Risiko</h2><p>Tentukan batas maksimal kerugian yang siap Anda terima dalam satu transaksi (biasanya 1-2% dari modal). Ini mencegah satu kesalahan menghancurkan seluruh akun Anda.</p>`
                    },
                    {
                        title: 'Psikologi Trading',
                        content: `<h2>Mindset Trader Pro</h2><p>Kestabilan mental lebih penting daripada keunggulan teknikal. Kedisiplinan adalah pembeda antara trader profesional dan spekulan amatir.</p>`
                    }
                ],
                highlights: [
                    'Penerapan batas risiko per transaksi secara ketat',
                    'Kalkulasi ukuran lot sesuai kapasitas akun',
                    'Pengendalian emosi Fear & Greed'
                ]
            },
            { 
                id: 4, 
                title: 'KAMIS — Audit | Teruji', 
                duration: '40 Menit', 
                completed: false,
                banner: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=1200',
                image: 'https://images.unsplash.com/photo-1551288049-bbbda536687a?q=80&w=1200',
                handbook: 'Evaluasi & Pengujian Sistem. Backtest, jurnal trading, serta evaluasi performa berbasis data.',
                output: 'Trader memiliki sistem trading yang teruji.',
                topics: [
                    {
                        title: 'Pentingnya Evaluasi Data',
                        content: `<h2>Audit Performa</h2><p>Uji strategi Anda menggunakan data masa lalu (Backtest) dan akun demo sebelum menggunakan uang sungguhan untuk melihat probabilitas keberhasilannya secara statistik.</p>`
                    }
                ],
                highlights: [
                    'Evaluasi performa berdasarkan data objektif',
                    'Pencatatan rutin dalam Jurnal Trading',
                    'Pengukuran efektivitas strategi sistematis'
                ]
            },
            { 
                id: 5, 
                title: 'JUMAT — Optimize | Stabil', 
                duration: '45 Menit', 
                completed: false,
                banner: 'https://images.unsplash.com/photo-1543286386-713bdd548da4?q=80&w=1200',
                image: 'https://images.unsplash.com/photo-1551288049-bbbda536687a?q=80&w=1200',
                handbook: 'Strategi & Konsistensi Trading. Optimasi strategi, konsistensi entry & exit, serta pengembangan sistem trading.',
                output: 'Trader memiliki strategi yang stabil dan berkelanjutan.',
                topics: [
                    {
                        title: 'Optimasi Strategi',
                        content: `<h2>Strategi Stabil</h2><p>Fokuslah menguasai satu atau dua metode hingga ahli. Strategi yang stabil adalah kunci sukses jangka panjang.</p>`
                    }
                ],
                highlights: [
                    'Pengembangan strategi yang stabil',
                    'Konsistensi aturan entry dan exit',
                    'Manajemen pertumbuhan akun berkelanjutan'
                ]
            },
            { 
                id: 6, 
                title: 'SABTU — Defend | Terlindungi', 
                duration: '50 Menit', 
                completed: false,
                banner: 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?q=80&w=1200', 
                image: 'https://images.unsplash.com/photo-1505664194779-8beaceb93744?q=80&w=1200',
                handbook: 'Proteksi, Advokasi & Tools Trading (BIDBOX). Memahami perlindungan trader, mekanisme advokasi, serta penggunaan tools pendukung analisa.',
                output: 'Trader terlindungi secara sistem dan memiliki alat bantu untuk meningkatkan kualitas keputusan trading.',
                topics: [
                    {
                        title: 'Hak Trader & Advokasi',
                        content: `<h2>Perlindungan Tradres</h2><p>Memahami hak Anda sebagai trader dan mekanisme perlindungan hukum yang tersedia di Indonesia.</p>`
                    },
                    {
                        title: 'Integrasi Teknologi: BIDBOX',
                        content: `<h2>BIDBOX (Decision Support System)</h2><p>BIDBOX adalah tools berbasis data yang membantu trader memvalidasi analisa dan memonitor peluang market secara real-time.</p><p>Sistem ini memberikan rekomendasi berdasarkan metrik teknikal dan fundamental yang teruji.</p>`
                    }
                ],
                highlights: [
                    'BIDBOX: Validasi analisa presisi',
                    'BIDBOX: Pengambilan keputusan berbasis data',
                    'BIDBOX: Monitoring peluang market real-time'
                ]
            },
            { 
                id: 7, 
                title: 'MINGGU — Recap | Siap', 
                duration: '35 Menit', 
                completed: false,
                banner: 'https://images.unsplash.com/photo-1519834785169-98be25ec3f84?q=80&w=1200',
                image: 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?q=80&w=1200',
                handbook: 'Evaluasi & Perencanaan Trading. Review mingguan, penyusunan trading plan, and outlook market.',
                output: 'Trader siap trading dengan rencana yang jelas and terarah.',
                topics: [
                    {
                        title: 'Review & Market Outlook',
                        content: `<h2>Weekly Planning</h2><p>Menyusun rencana trading untuk minggu depan berdasarkan evaluasi minggu sebelumnya dan outlook market terkini.</p>`
                    }
                ],
                highlights: [
                    'Penyusunan Trading Plan mingguan terstruktur',
                    'Analisis peluang market jangka pendek-menengah',
                    'Penetapan target profit yang terukur'
                ]
            }
        ];

        let currentModuleIndex = -1;
        let currentTopicIndex = 0;

        function updateProgress() {
            if (!isPaid) return;
            const completed = modules.filter(m => m.completed).length;
            const percent = Math.round((completed / modules.length) * 100);
            const pBar = document.getElementById('progressBar');
            if(pBar) pBar.style.width = percent + '%';
        }

        function renderModules() {
            document.getElementById('modulesList').innerHTML = modules.map((module, mIndex) => {
                let statusClass = '';
                let statusIcon = '';
                const isActive = mIndex === currentModuleIndex;
                
                if (module.completed) {
                    statusClass = 'completed';
                    statusIcon = `<i class="fas fa-check-circle text-accent"></i>`;
                } else if (!isPaid) {
                    statusIcon = `<i class="fas fa-lock text-gray-500"></i>`;
                    statusClass = 'locked';
                } else if (isActive) {
                    statusClass = 'active';
                    statusIcon = `<i class="fas fa-play-circle text-accent animate-pulse"></i>`;
                } else {
                    statusIcon = `<i class="far fa-circle text-gray-700"></i>`;
                }
                
                let topicsHtml = '';
                if (isActive && isPaid) {
                    topicsHtml = `
                        <div class="mt-4 ml-8 space-y-2 border-l border-white/5 pl-4">
                            ${module.topics.map((topic, tIndex) => `
                                <div onclick="selectTopic(${tIndex}, event)" class="py-2 px-3 rounded-lg cursor-pointer transition-all hover:bg-white/5 ${tIndex === currentTopicIndex ? 'text-accent font-bold bg-accent/5' : 'text-gray-500 text-[10px]'} flex items-center gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full ${tIndex === currentTopicIndex ? 'bg-accent shadow-[0_0_8px_rgba(51,232,24,0.8)]' : 'bg-gray-700'}"></div>
                                    ${topic.title}
                                </div>
                            `).join('')}
                        </div>
                    `;
                }
                
                return `
                    <div class="mb-4">
                        <div class="module-item ${statusClass} p-4 rounded-2xl cursor-pointer hover:bg-white/5 transition-all" onclick="selectModule(${mIndex})">
                            <div class="flex items-start gap-4">
                                <div class="mt-1 text-xs">${statusIcon}</div>
                                <div class="flex-1">
                                    <p class="text-[8px] text-gray-600 font-black uppercase tracking-widest mb-1">Framework: ${['AMAN', 'PAHAM', 'RASIONAL', 'TERUJI', 'STABIL', 'TERLINDUNGI', 'SIAP'][mIndex]}</p>
                                    <p class="font-bold text-xs ${isActive ? 'text-white' : 'text-gray-400'}">${module.title}</p>
                                </div>
                            </div>
                        </div>
                        ${topicsHtml}
                    </div>
                `;
            }).join('');
        }

        function selectModule(index) {
            if (currentModuleIndex === index) return;
            currentModuleIndex = index;
            currentTopicIndex = 0; // Reset topic when changing module
            updateContent();
        }

        function selectTopic(tIndex, event) {
            if (event) event.stopPropagation();
            currentTopicIndex = tIndex;
            updateContent();
        }

        function updateContent() {
            const module = modules[currentModuleIndex];
            const topic = module.topics[currentTopicIndex];
            
            // Update Headers
            document.getElementById('headerBanner').src = module.banner;
            document.getElementById('currentModuleNumber').textContent = `Modul ${currentModuleIndex + 1}.${currentTopicIndex + 1}`;
            document.getElementById('currentModuleTitle').textContent = topic.title;
            
            if (isPaid) {
                // Update Output
                document.getElementById('moduleOutput').textContent = module.output;

                // Update Content
                let contentHtml = topic.content;
                
                // Add handbook text if on first topic
                if (currentTopicIndex === 0) {
                    contentHtml = `<blockquote>"${module.handbook}"</blockquote>` + contentHtml;
                }
                
                document.getElementById('moduleMateriContent').innerHTML = contentHtml;
                
                // Update Content Image
                const contentImg = document.getElementById('moduleContentImage');
                if (module.image) {
                    contentImg.src = module.image;
                    contentImg.classList.remove('hidden');
                } else {
                    contentImg.classList.add('hidden');
                }
                
                // Render Highlights
                document.getElementById('moduleHighlights').innerHTML = module.highlights.map(h => `
                    <li class="flex items-start gap-3 text-sm text-gray-400">
                        <div class="mt-1.5 w-1.5 h-1.5 rounded-full bg-accent shadow-[0_0_8px_rgba(51,232,24,0.8)] flex-shrink-0"></div>
                        ${h}
                    </li>
                `).join('');
                
                // Update complete button
                const completeBtn = document.getElementById('completeBtn');
                if (module.completed) {
                    completeBtn.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Sudah Dikuasai';
                    completeBtn.classList.add('bg-gray-600', 'cursor-default');
                    completeBtn.classList.remove('bg-accent', 'hover:bg-white');
                } else {
                    completeBtn.innerHTML = '<i class="fas fa-check mr-2"></i> Tandai Selesai';
                    completeBtn.classList.remove('bg-gray-600', 'cursor-default');
                    completeBtn.classList.add('bg-accent', 'hover:bg-white');
                }
                loadNotes();
            }
            
            renderModules();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function markComplete() {
            if (!isPaid || modules[currentModuleIndex].completed) return;
            
            modules[currentModuleIndex].completed = true;
            updateProgress();
            renderModules();
            
            const completeBtn = document.getElementById('completeBtn');
            completeBtn.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Materi Selesai';
            completeBtn.classList.add('bg-gray-600');
            completeBtn.classList.remove('bg-accent');
            
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                icon: 'success',
                title: 'Materi Berhasil Dikuasai!',
                background: '#0a0a0a',
                color: '#ffffff'
            });
        }

        function nextModule() {
            if (currentTopicIndex < modules[currentModuleIndex].topics.length - 1) {
                // Go to next topic in same module
                selectTopic(currentTopicIndex + 1);
            } else if (currentModuleIndex < modules.length - 1) {
                // Go to next module
                selectModule(currentModuleIndex + 1);
            } else {
                Swal.fire({
                    title: 'Congratulations!',
                    text: 'Program ini dirancang untuk membentuk trader secara menyeluruh: tidak hanya profit-oriented, tetapi juga legal, terstruktur, berbasis sistem, didukung teknologi, dan memiliki perlindungan.',
                    icon: 'success',
                    background: '#0a0a0a',
                    color: '#ffffff',
                    confirmButtonColor: '#33e818',
                    confirmButtonText: 'Selesai',
                    footer: '<span style="color: #33e818">Anda telah bertransformasi dari trader spekulatif menjadi profesional.</span>'
                });
            }
        }

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function showTab(tab) {
            document.querySelectorAll('.tab-content').forEach(t => t.classList.add('hidden'));
            document.getElementById('tab-' + tab).classList.remove('hidden');
            
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active', 'border-accent', 'text-accent');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            const clickedBtn = document.querySelector(`[data-tab="${tab}"]`);
            clickedBtn.classList.add('active', 'border-accent', 'text-accent');
            clickedBtn.classList.remove('border-transparent', 'text-gray-500');
        }

        function saveNotes() {
            const notes = document.getElementById('notesArea').value;
            localStorage.setItem(`adv_notes_${currentModuleIndex}`, notes);
            
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
                icon: 'success',
                title: 'Catatan Tersimpan',
                background: '#0a0a0a',
                color: '#ffffff'
            });
        }

        function loadNotes() {
            const saved = localStorage.getItem(`adv_notes_${currentModuleIndex}`);
            document.getElementById('notesArea').value = saved || '';
        }

        // Initialize
        updateProgress();
        renderModules();
        selectModule(0);
    </script>
</body>
</html>
