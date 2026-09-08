<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<?php 
$pageTitle = 'Handbook Karyawan';
$activeMenu = 'handbook'; 

$sections = [
    [
        'id' => 'kata-pengantar',
        'title' => 'Kata Pengantar',
        'icon' => 'fas fa-hand-holding-heart',
        'content' => 'Selamat datang di ALMAI. Kami percaya bahwa trading bukan sekadar aktivitas mencari profit, melainkan sebuah proses yang membutuhkan pengetahuan, disiplin, pendampingan profesional, serta perlindungan yang tepat.',
        'subsections' => [
            ['title' => 'Visi Kami', 'desc' => 'ALMAI hadir sebagai platform penasihat perdagangan berjangka dan aset keuangan digital berbasis teknologi yang mengintegrasikan edukasi, advokasi, serta layanan profesional melalui Wakil Penasihat Berjangka (WPA).'],
            ['title' => 'Nilai Utama', 'desc' => 'Trusted, Specialist, Efficient. Seluruh tim diharapkan berperan aktif dalam membangun kepercayaan dan meningkatkan kualitas layanan.']
        ]
    ],
    [
        'id' => 'pendahuluan',
        'title' => '1. Pendahuluan',
        'icon' => 'fas fa-info-circle',
        'content' => 'Handbook ini merupakan pedoman resmi bagi seluruh Karyawan, Calon Wakil Penasihat Berjangka (C-WPA), Wakil Penasihat Berjangka (WPA), serta Mitra & tim operasional.',
        'subsections' => [
            ['title' => 'Tujuan Utama', 'desc' => 'Menyamakan standar kerja dan menjaga profesionalisme.'],
            ['title' => 'Kepatuhan', 'desc' => 'Menjamin kepatuhan dan menciptakan sistem kerja terstruktur.']
        ]
    ],
    [
        'id' => 'profil-perusahaan',
        'title' => '2. Profil Perusahaan',
        'icon' => 'fas fa-building',
        'content' => 'Almai adalah platform penasihat perdagangan berjangka berbasis teknologi yang menyediakan edukasi trading dan pendampingan profesional.',
        'subsections' => [
            ['title' => 'Layanan', 'desc' => 'Advokasi, perlindungan user, dan teknologi Expert Advisor (AIWE & BIDBOX).'],
            ['title' => 'Prinsip', 'desc' => 'Bukan broker, tidak mengelola dana, dan tidak menjanjikan profit.']
        ]
    ],
    [
        'id' => 'visi-misi',
        'title' => '3. Visi & Misi',
        'icon' => 'fas fa-bullseye',
        'content' => 'Visi: Menjadi platform terpercaya dalam pendampingan trader berbasis profesional dan teknologi.',
        'subsections' => [
            ['title' => 'Misi Literasi', 'desc' => 'Meningkatkan literasi trading dan menyediakan tenaga ahli profesional.'],
            ['title' => 'Misi Teknologi', 'desc' => 'Mengembangkan teknologi trading dan memberikan perlindungan kepada trader.']
        ]
    ],
    [
        'id' => 'nilai-perusahaan',
        'title' => '4. Nilai Perusahaan',
        'icon' => 'fas fa-gem',
        'content' => 'Budaya kerja kami berlandaskan pada tiga pilar utama:',
        'subsections' => [
            ['title' => 'TRUSTED', 'desc' => 'Integritas dan transparansi.'],
            ['title' => 'SPECIALIST', 'desc' => 'Tenaga ahli tersertifikasi.'],
            ['title' => 'EFFICIENT', 'desc' => 'Layanan cepat berbasis teknologi terintegrasi.']
        ]
    ],
    [
        'id' => 'program-layanan',
        'title' => '5. Program Layanan ALMAI',
        'icon' => 'fas fa-concierge-bell',
        'content' => 'Layanan unggulan platform:',
        'subsections' => [
            ['title' => 'Advokasi & Mentoring', 'desc' => 'Pendampingan kasus, konsultasi, dan edukasi live trading.'],
            ['title' => 'Ultimate & Teknologi', 'desc' => 'Mentor personal, trading plan, AIWE, dan BIDBOX.']
        ]
    ],
    [
        'id' => 'struktur-level',
        'title' => '6. Struktur Level User',
        'icon' => 'fas fa-layer-group',
        'content' => 'Jenjang karir: User → User Pro → CWPA → WPA.',
        'subsections' => [
            ['title' => 'Manajemen', 'desc' => 'Tingkat Admin dan Super Admin untuk tata kelola platform.']
        ]
    ],
    [
        'id' => 'skema-operasional',
        'title' => '7. Skema Operasional',
        'icon' => 'fas fa-cogs',
        'content' => 'Alur kerja pelayanan:',
        'subsections' => [
            ['title' => 'Tahapan Awal', 'desc' => 'Registrasi user dan analisa kebutuhan.'],
            ['title' => 'Tahapan Eksekusi', 'desc' => 'Perjanjian layanan, implementasi strategi, pendampingan, dan evaluasi berkala.']
        ]
    ],
    [
        'id' => 'sistem-event',
        'title' => '8. Sistem Event',
        'icon' => 'fas fa-calendar-alt',
        'content' => 'Jadwal rutin mingguan:',
        'subsections' => [
            ['title' => 'Kamis', 'desc' => 'Audit (Strategi).'],
            ['title' => 'Jumat', 'desc' => 'Control (Psikologi & Risk).'],
            ['title' => 'Sabtu', 'desc' => 'Defend (Legal & Perlindungan).']
        ]
    ],
    [
        'id' => 'struktur-tata-kelola',
        'title' => '9. Struktur Kepemimpinan',
        'icon' => 'fas fa-sitemap',
        'content' => 'Tata kelola PT. ALMA INDONESIA RAYA.',
        'subsections' => [
            ['title' => 'Direksi', 'desc' => 'Direktur Utama, Operasional, Teknologi, dan Legal.'],
            ['title' => 'Divisi', 'desc' => 'Advokasi, Mentoring, Teknologi, WPA, Marketing, dan Customer Support.']
        ]
    ],
    [
        'id' => 'peran-wpa',
        'title' => '10. Peran WPA',
        'icon' => 'fas fa-user-tie',
        'content' => 'Tugas Wakil Penasihat Berjangka:',
        'subsections' => [
            ['title' => 'Tanggung Jawab', 'desc' => 'Memberikan nasihat, menyusun strategi, dan mendampingi klien.'],
            ['title' => 'Larangan', 'desc' => 'Tidak menjanjikan profit dan tidak mengelola dana.']
        ]
    ],
    [
        'id' => 'tenaga-ahli',
        'title' => '11. Tenaga Ahli & Mentor',
        'icon' => 'fas fa-users-cog',
        'content' => 'Dukungan profesional:',
        'subsections' => [
            ['title' => 'Expertise', 'desc' => 'Mentor trading, analis market, dan spesialis instrumen.']
        ]
    ],
    [
        'id' => 'tata-kelola',
        'title' => '12. Tata Kelola Perusahaan',
        'icon' => 'fas fa-balance-scale',
        'content' => 'Prinsip manajemen:',
        'subsections' => [
            ['title' => 'Pilar GCG', 'desc' => 'Transparansi, Akuntabilitas, Profesionalisme, dan Kepatuhan regulasi.']
        ]
    ],
    [
        'id' => 'pemegang-saham',
        'title' => '13. Pemegang Saham',
        'icon' => 'fas fa-handshake',
        'content' => 'Dukungan strategis:',
        'subsections' => [
            ['title' => 'Stakeholders', 'desc' => 'Ahli di bidang Keuangan, Teknologi, dan Hukum.']
        ]
    ],
    [
        'id' => 'kebijakan-sdm',
        'title' => '14. Kebijakan SDM',
        'icon' => 'fas fa-user-check',
        'content' => 'Manajemen talenta:',
        'subsections' => [
            ['title' => 'Siklus Kerja', 'desc' => 'Rekrutmen, Masa percobaan, Jam kerja, dan Evaluasi kinerja.']
        ]
    ],
    [
        'id' => 'okr',
        'title' => '15. OKR (Objectives & Key Results)',
        'icon' => 'fas fa-tasks',
        'content' => 'Target performa divisi:',
        'subsections' => [
            ['title' => 'Fokus', 'desc' => 'Penyelesaian kasus (Advokasi), Progress user (Mentoring), Stabilitas sistem (Teknologi).']
        ]
    ],
    [
        'id' => 'kode-etik',
        'title' => '16. Kode Etik',
        'icon' => 'fas fa-user-shield',
        'content' => 'Standar perilaku:',
        'subsections' => [
            ['title' => 'Etika Dasar', 'desc' => 'Menjaga kerahasiaan data, transparan, dan tidak manipulatif.']
        ]
    ],
    [
        'id' => 'kebijakan-risiko',
        'title' => '17. Kebijakan Risiko',
        'icon' => 'fas fa-exclamation-triangle',
        'content' => 'Manajemen risiko trading:',
        'subsections' => [
            ['title' => 'Edukasi Risiko', 'desc' => 'Volatilitas pasar dan risiko finansial adalah tanggung jawab user.']
        ]
    ],
    [
        'id' => 'dokumen-perjanjian',
        'title' => '18. Dokumen Perjanjian',
        'icon' => 'fas fa-file-contract',
        'content' => 'Legalitas operasional:',
        'subsections' => [
            ['title' => 'Kontrak', 'desc' => 'Partnership, WPA, Produk Layanan, dan Pemberian Jasa.']
        ]
    ],
    [
        'id' => 'platform-almai',
        'title' => '19. Platform ALMAI',
        'icon' => 'fas fa-desktop',
        'content' => 'Teknologi ekosistem:',
        'subsections' => [
            ['title' => 'Fitur', 'desc' => 'Dashboard, Sistem Mentoring, Integrasi EA, dan Almai Poin.']
        ]
    ],
    [
        'id' => 'kompensasi',
        'title' => '20. Kompensasi & Benefit',
        'icon' => 'fas fa-money-bill-wave',
        'content' => 'Kesejahteraan tim:',
        'subsections' => [
            ['title' => 'Remunerasi', 'desc' => 'Gaji/fee, Komisi, dan Bonus performa.']
        ]
    ],
    [
        'id' => 'disiplin-sanksi',
        'title' => '21. Disiplin & Sanksi',
        'icon' => 'fas fa-gavel',
        'content' => 'Kepatuhan aturan:',
        'subsections' => [
            ['title' => 'Tingkatan', 'desc' => 'Pelanggaran ringan hingga berat (Fraud/Manipulasi).']
        ]
    ],
    [
        'id' => 'pengembangan-karir',
        'title' => '22. Pengembangan Karir',
        'icon' => 'fas fa-user-graduate',
        'content' => 'Peningkatan kompetensi:',
        'subsections' => [
            ['title' => 'Fasilitas', 'desc' => 'Sertifikasi WPA, Training internal, dan Upgrade skill.']
        ]
    ],
    [
        'id' => 'keamanan-data',
        'title' => '23. Keamanan Data',
        'icon' => 'fas fa-lock',
        'content' => 'Perlindungan informasi:',
        'subsections' => [
            ['title' => 'Sistem', 'desc' => 'Perlindungan data user, akses terbatas, dan enkripsi.']
        ]
    ],
    [
        'id' => 'penutup',
        'title' => '24. Penutup',
        'icon' => 'fas fa-door-closed',
        'content' => 'Komitmen bersama:',
        'subsections' => [
            ['title' => 'Kepatuhan', 'desc' => 'Seluruh anggota wajib mematuhi handbook dan menjaga integritas Almai.']
        ]
    ]
];
?>

<div class="flex flex-col lg:flex-row gap-8 relative min-h-screen items-start overflow-visible">
    <!-- Mobile ToC Toggle -->
    <button onclick="toggleToC()" class="lg:hidden fixed bottom-6 right-6 z-40 bg-accent text-black w-14 h-14 rounded-full shadow-lg flex items-center justify-center text-xl">
        <i class="fas fa-list-ul"></i>
    </button>

    <!-- Sidebar Navigation -->
    <div id="tocSidebarContainer" class="hidden lg:block w-72 shrink-0 h-full">
        <aside id="tocSidebarDesktop" class="sticky top-28 bg-[#0a0a0a]/50 backdrop-blur-xl border border-white/10 rounded-3xl p-6 transition-all duration-300">
            <div class="mb-4 flex items-center gap-3">
                 <img src="https://almai.id/images/alma.gif" alt="ALMAI" class="h-6">
                 <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Bab 1 - 24</h4>
            </div>
            <nav class="space-y-1 max-h-[55vh] overflow-y-auto pr-2 custom-scrollbar">
                <?php foreach ($sections as $index => $section): ?>
                    <button 
                        onclick="switchSection('<?= $section['id'] ?>')" 
                        id="nav-<?= $section['id'] ?>"
                        class="toc-nav-btn w-full text-left px-4 py-2 text-sm text-gray-500 hover:text-accent hover:bg-white/5 rounded-2xl transition-all duration-300 flex items-center gap-3 border-l-2 border-transparent <?= $index === 0 ? 'active' : '' ?>"
                    >
                        <i class="<?= $section['icon'] ?> text-[10px] w-4 text-center"></i>
                        <span class="truncate font-medium"><?= $section['title'] ?></span>
                    </button>
                <?php endforeach; ?>
            </nav>
            
            <div class="mt-6 pt-6 border-t border-white/5">
                <a href="<?= base_url('Almai_Handbook.pdf') ?>" download class="flex items-center gap-3 px-4 py-3 bg-accent/10 border border-accent/20 hover:bg-accent/20 rounded-2xl transition-all group">
                    <i class="fas fa-file-pdf text-accent text-lg"></i>
                    <div class="text-left">
                        <p class="text-[9px] text-accent/70 font-bold uppercase tracking-widest">Download Dokumen</p>
                        <p class="text-xs font-bold text-white">Full PDF Version</p>
                    </div>
                </a>
            </div>
        </aside>
    </div>

    <!-- Mobile Off-canvas Sidebar -->
    <aside id="tocSidebarMobile" class="lg:hidden fixed inset-y-0 left-0 w-80 bg-[#050505] border-r border-white/10 p-6 z-50 -translate-x-full transition-transform duration-500 ease-[cubic-bezier(0.16, 1, 0.3, 1)]">
        <div class="flex items-center justify-between mb-8">
            <img src="https://almai.id/images/alma.gif" alt="ALMAI" class="h-8">
            <button onclick="toggleToC()" class="text-gray-500 hover:text-white"><i class="fas fa-times text-xl"></i></button>
        </div>
        <nav class="space-y-2 overflow-y-auto max-h-[80vh]">
            <?php foreach ($sections as $section): ?>
                <button onclick="switchSection('<?= $section['id'] ?>')" class="w-full text-left text-gray-400 hover:text-accent block px-4 py-3 rounded-xl border-l-2 border-transparent flex items-center gap-3">
                    <i class="<?= $section['icon'] ?> opacity-50"></i>
                    <?= $section['title'] ?>
                </button>
            <?php endforeach; ?>
        </nav>
    </aside>
    <div id="sidebarOverlay" onclick="toggleToC()" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-40 hidden lg:hidden"></div>

    <!-- Content Area (Dynamic) -->
    <div class="flex-1 w-full min-h-[70vh]">
        <div class="mb-8 p-8 md:p-10 rounded-[2rem] bg-[#0c0c0c] border border-white/5 shadow-2xl relative overflow-hidden">
             <div class="relative z-10 flex items-center justify-between gap-6">
                <div>
                    <h1 class="text-3xl md:text-5xl font-black text-white tracking-tighter mb-1">Handbook <span class="text-accent">Karyawan</span></h1>
                    <p class="text-gray-500 font-medium text-sm">PT. ALMA INDONESIA RAYA (ALMAI)</p>
                </div>
                <div class="hidden md:block">
                    <img src="https://almai.id/images/alma.gif" alt="ALMAI" class="h-10">
                </div>
             </div>
             <div class="absolute -top-20 -right-20 w-64 h-64 bg-accent/10 rounded-full blur-[100px]"></div>
        </div>

        <div id="handbook-content-container" class="relative">
            <?php foreach ($sections as $index => $section): ?>
                <div 
                    id="content-<?= $section['id'] ?>" 
                    class="handbook-section <?= $index === 0 ? 'active' : 'hidden' ?> transition-all duration-500"
                >
                    <div class="flex items-center gap-5 mb-8">
                        <div class="w-14 h-14 rounded-2xl bg-accent text-black flex items-center justify-center shadow-[0_0_30px_rgba(51,232,24,0.3)]">
                            <i class="<?= $section['icon'] ?> text-2xl"></i>
                        </div>
                        <h2 class="text-2xl md:text-4xl font-black text-white tracking-tighter leading-none"><?= $section['title'] ?></h2>
                    </div>

                    <div class="bg-gradient-to-br from-[#0e0e0e] to-[#080808] border border-white/10 rounded-[2.5rem] p-8 md:p-12 shadow-2xl relative overflow-hidden group">
                        <div class="relative z-10">
                            <p class="text-gray-300 leading-relaxed text-lg md:text-xl font-medium mb-10"><?= $section['content'] ?></p>
                            
                            <?php if (isset($section['subsections'])): ?>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <?php foreach ($section['subsections'] as $sub): ?>
                                        <div class="bg-black/40 border border-white/5 p-8 rounded-[1.5rem] hover:bg-white/[0.03] hover:border-accent/30 transition-all duration-500">
                                            <h4 class="font-bold text-white text-base mb-3 flex items-center gap-3">
                                                <div class="w-1.5 h-1.5 rounded-full bg-accent"></div>
                                                <?= $sub['title'] ?>
                                            </h4>
                                            <p class="text-gray-500 text-sm leading-relaxed"><?= $sub['desc'] ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <i class="<?= $section['icon'] ?> absolute -bottom-16 -right-16 text-white/[0.02] text-[250px] pointer-events-none transform -rotate-12"></i>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <footer class="mt-20 pt-10 border-t border-white/5 flex flex-col md:flex-row items-center justify-between gap-6 text-gray-600">
            <p class="text-sm">© 2026 PT. ALMA INDONESIA RAYA. Seluruh hak cipta dilindungi.</p>
            <p class="text-[10px] font-bold uppercase tracking-widest italic opacity-50">Trusted • Specialist • Efficient</p>
        </footer>
    </div>
</div>

<style>
    /* CSS FORCING VISIBILITY */
    #tocSidebarContainer {
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }

    @media (max-width: 1023px) {
        #tocSidebarContainer {
            display: none !important;
        }
    }

    main, main section, .flex-1.md\:ml-64 { 
        overflow: visible !important; 
    }

    /* Section Switching Animations */
    .handbook-section {
        opacity: 0;
        transform: translateX(30px);
        pointer-events: none;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        visibility: hidden;
    }
    
    .handbook-section.active {
        opacity: 1;
        transform: translateX(0);
        pointer-events: auto;
        position: relative;
        visibility: visible;
        transition: all 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* Tab Button Styles */
    .toc-nav-btn.active {
        color: #33e818 !important;
        background: rgba(51, 232, 24, 0.08) !important;
        border-left-color: #33e818 !important;
        padding-left: 1.5rem;
    }
    
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.05); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(51, 232, 24, 0.2); }
</style>

<script>
    function toggleToC() {
        const sidebar = document.getElementById('tocSidebarMobile');
        const overlay = document.getElementById('sidebarOverlay');
        
        if(sidebar.classList.contains('-translate-x-full')) {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        } else {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }
    }

    function switchSection(sectionId) {
        const sections = document.querySelectorAll('.handbook-section');
        sections.forEach(s => {
            s.classList.remove('active');
            setTimeout(() => {
                if(!s.classList.contains('active')) s.classList.add('hidden');
            }, 500);
        });

        const target = document.getElementById('content-' + sectionId);
        target.classList.remove('hidden');
        setTimeout(() => target.classList.add('active'), 50);

        document.querySelectorAll('.toc-nav-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        
        const activeNav = document.getElementById('nav-' + sectionId);
        if (activeNav) activeNav.classList.add('active');

        window.scrollTo({ top: 0, behavior: 'smooth' });

        if (window.innerWidth < 1024) {
            toggleToC();
        }
    }
</script>

<?= $this->endSection() ?>
