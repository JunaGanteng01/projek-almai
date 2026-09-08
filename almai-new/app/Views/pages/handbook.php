<?= $this->extend($layout) ?>

<?= $this->section('content') ?>
<?php 
$activeMenu = 'handbook'; 

$sections = [
    [
        'id' => '1-kata-pengantar',
        'title' => '1. Kata Pengantar',
        'icon' => 'fas fa-hand-holding-heart',
        'content' => 'Selamat datang di ekosistem ALMAI.<br><br>Handbook ini disusun sebagai panduan umum untuk membantu member, komunitas, partner, dan publik memahami sistem, budaya, layanan, serta arah pengembangan ALMAI. Kami percaya bahwa perkembangan dunia trading dan teknologi harus dibarengi dengan edukasi yang benar, disiplin, serta pengembangan mindset yang sehat.<br><br>ALMAI hadir bukan hanya sebagai platform edukasi, tetapi sebagai ekosistem pengembangan trader yang mengedepankan nilai TRUSTED, SPECIALIST, dan EFFICIENT dalam setiap proses pembelajaran, komunitas, dan pengembangan teknologi.<br><br>Melalui handbook ini, diharapkan seluruh pihak dapat memahami bagaimana ALMAI membangun komunitas yang profesional, bertanggung jawab, dan berorientasi pada pertumbuhan jangka panjang.',
    ],
    [
        'id' => '2-pendahuluan',
        'title' => '2. Pendahuluan',
        'icon' => 'fas fa-info-circle',
        'content' => 'Perkembangan industri trading, aset digital, artificial intelligence (AI), dan teknologi digital telah membuka peluang baru bagi masyarakat untuk belajar dan berkembang di era modern. Namun, di balik peluang tersebut, terdapat tantangan besar berupa kurangnya edukasi, disiplin, pengelolaan risiko, dan pemahaman psikologi trading.<br><br>ALMAI dibangun untuk menjadi bagian dari solusi tersebut melalui pendekatan edukasi, komunitas, teknologi, dan pengembangan karakter trader secara berkelanjutan.<br><br>Kami percaya bahwa trader yang bertumbuh bukan hanya dibentuk dari kemampuan analisa market, tetapi juga dari mindset, kebiasaan, disiplin, dan sistem yang konsisten.',
    ],
    [
        'id' => '3-profil-perusahaan',
        'title' => '3. Profil Perusahaan',
        'icon' => 'fas fa-building',
        'content' => 'ALMAI adalah ekosistem edukasi dan pengembangan trader yang berfokus pada pembelajaran trading, pengembangan komunitas, integrasi teknologi, serta penguatan mindset dan disiplin trader.<br><br>ALMAI hadir untuk membantu trader berkembang secara bertahap melalui sistem yang lebih terarah, terukur, dan berkelanjutan.',
        'subsections' => [
            ['title' => 'LAYANAN', 'desc' => '<ul class=\'list-disc pl-5 mt-2 space-y-1\'><li>Edukasi trading terstruktur</li><li>Webinar dan workshop</li><li>Trading camp dan mentoring</li><li>Program advokasi trader</li><li>Community development</li><li>Integrasi AI dan teknologi digital</li><li>Pengembangan leadership komunitas</li></ul>'],
        ]
    ],
    [
        'id' => '4-visi-misi',
        'title' => '4. Visi & Misi',
        'icon' => 'fas fa-bullseye',
        'content' => 'Visi<br>Menjadi ekosistem edukasi dan pengembangan trader yang Trusted, Specialist, dan Efficient berbasis teknologi dan pertumbuhan berkelanjutan.',
        'subsections' => [
            ['title' => 'MISI', 'desc' => '<ul class=\'list-disc pl-5 mt-2 space-y-1\'><li>Membangun sistem edukasi trading yang terpercaya dan terstruktur.</li><li>Mengembangkan komunitas trader yang sehat dan bertanggung jawab.</li><li>Mendorong pengembangan SDM berbasis spesialisasi dan kompetensi.</li><li>Mengintegrasikan teknologi dan AI secara efisien dalam ekosistem pembelajaran.</li><li>Membentuk budaya disiplin, growth mindset, dan pengembangan jangka panjang.</li></ul>'],
        ]
    ],
    [
        'id' => '5-nilai-perusahaan',
        'title' => '5. Nilai Perusahaan',
        'icon' => 'fas fa-gem',
        'content' => 'Core value ini menjadi dasar budaya kerja, komunikasi, pelayanan, dan pengembangan seluruh ekosistem ALMAI.',
        'subsections' => [
            ['title' => 'TRUSTED', 'desc' => 'ALMAI mengedepankan integritas, transparansi, dan tanggung jawab dalam setiap aktivitas edukasi, komunikasi, dan pengembangan komunitas.'],
            ['title' => 'SPECIALIST', 'desc' => 'ALMAI fokus membangun kompetensi, spesialisasi, dan kualitas SDM dalam bidang trading, edukasi, teknologi, dan leadership.'],
            ['title' => 'EFFICIENT', 'desc' => 'ALMAI mengembangkan sistem kerja, teknologi, dan proses pembelajaran yang efektif, terstruktur, dan berorientasi pada pertumbuhan berkelanjutan.'],
        ]
    ],
    [
        'id' => '6-program-layanan-almai',
        'title' => '6. Program Layanan ALMAI',
        'icon' => 'fas fa-concierge-bell',
        'content' => 'ALMAI menyediakan berbagai program dan layanan pengembangan trader.',
        'subsections' => [
            ['title' => 'Edukasi Trading', 'desc' => 'Pembelajaran dasar hingga lanjutan mengenai market, analisa, risk management, dan psikologi trading.'],
            ['title' => 'Webinar & Workshop', 'desc' => 'Kegiatan edukasi berkala yang membahas market, mindset, teknologi, dan pengembangan trader.'],
            ['title' => 'Mentoring & Trading Camp', 'desc' => 'Program pendampingan intensif untuk membantu trader membangun sistem dan disiplin trading.'],
            ['title' => 'Community Development', 'desc' => 'Pengembangan komunitas trader yang sehat, aktif, dan suportif.'],
            ['title' => 'Program Advokasi Trader', 'desc' => 'Program edukasi dan pendampingan untuk meningkatkan pemahaman risiko dan perlindungan trader.'],
            ['title' => 'AI & Technology Development', 'desc' => 'Pengembangan sistem digital, AI, dan integrasi teknologi untuk mendukung pertumbuhan ekosistem ALMAI.'],
        ]
    ],
    [
        'id' => '7-struktur-level-user',
        'title' => '7. Struktur Level User',
        'icon' => 'fas fa-layer-group',
        'content' => 'Ekosistem ALMAI memiliki beberapa level pengembangan user.',
        'subsections' => [
            ['title' => 'Visitor', 'desc' => 'Pengunjung umum yang mengakses informasi dan layanan ALMAI.'],
            ['title' => 'Member', 'desc' => 'User yang telah bergabung dalam komunitas dan sistem edukasi ALMAI.'],
            ['title' => 'Active Trader', 'desc' => 'Member aktif yang mengikuti program edukasi dan pengembangan trading.'],
            ['title' => 'Community Leader', 'desc' => 'Member yang berkontribusi dalam pengembangan komunitas dan aktivitas edukasi.'],
            ['title' => 'WPA & Mentor', 'desc' => 'Partner edukasi dan pendamping komunitas yang membantu proses pembelajaran dan advokasi.'],
            ['title' => 'Management', 'desc' => 'Tim internal yang mengelola sistem, operasional, dan pengembangan ekosistem ALMAI.'],
        ]
    ],
    [
        'id' => '8-sistem-event-edukasi',
        'title' => '8. Sistem Event & Edukasi',
        'icon' => 'fas fa-calendar-alt',
        'content' => 'ALMAI menjalankan sistem pembelajaran melalui berbagai kegiatan edukatif. Seluruh kegiatan dirancang untuk mendukung pembelajaran yang lebih terstruktur, interaktif, dan berkelanjutan.',
        'subsections' => [
            ['title' => 'KEGIATAN', 'desc' => '<ul class=\'list-disc pl-5 mt-2 space-y-1\'><li>Webinar online</li><li>Workshop dan seminar</li><li>Live trading session</li><li>Mentoring class</li><li>Trading camp</li><li>Community gathering</li><li>Program advokasi</li><li>Evaluasi dan pengembangan trader</li></ul>'],
        ]
    ],
    [
        'id' => '9-platform-ekosistem-almai',
        'title' => '9. Platform & Ekosistem ALMAI',
        'icon' => 'fas fa-desktop',
        'content' => 'ALMAI mengembangkan ekosistem berbasis teknologi untuk mendukung pembelajaran dan pengembangan komunitas secara efisien.<br><br>Pengembangan teknologi dilakukan untuk meningkatkan akses edukasi, efektivitas komunikasi, dan pertumbuhan komunitas secara scalable.',
        'subsections' => [
            ['title' => 'EKOSISTEM', 'desc' => '<ul class=\'list-disc pl-5 mt-2 space-y-1\'><li>Website dan landing page</li><li>Dashboard user</li><li>Sistem komunitas</li><li>Learning management system</li><li>Event management system</li><li>AI integration</li><li>Digital content ecosystem</li></ul>'],
        ]
    ],
    [
        'id' => '10-atomic-habits-dalam-trading',
        'title' => '10. Atomic Habits dalam Trading',
        'icon' => 'fas fa-atom',
        'content' => 'ALMAI mengadopsi konsep Atomic Habits sebagai bagian dari pengembangan karakter trader. Fokus utama pendekatan ini adalah membangun perubahan kecil yang konsisten untuk menghasilkan pertumbuhan jangka panjang.<br><br>ALMAI percaya bahwa trader yang bertahan lama dibangun melalui kebiasaan yang sehat, bukan hanya hasil jangka pendek.',
        'subsections' => [
            ['title' => 'MATERI', 'desc' => '<ul class=\'list-disc pl-5 mt-2 space-y-1\'><li>Disiplin trading</li><li>Konsistensi harian</li><li>Fokus pada proses</li><li>Pengelolaan emosi</li><li>Kebiasaan evaluasi</li><li>Risk management</li><li>Growth mindset</li><li>Sistem dan rutinitas trader</li></ul>'],
        ]
    ],
    [
        'id' => '11-faq-tanya-jawab',
        'title' => '11. FAQ (Tanya Jawab)',
        'icon' => 'fas fa-question-circle',
        'content' => 'Bagian ini berisi pertanyaan umum yang disusun untuk membantu user memahami sistem ALMAI secara lebih mudah dan praktis.',
        'subsections' => [
            ['title' => 'TOPIK', 'desc' => '<ul class=\'list-disc pl-5 mt-2 space-y-1\'><li>Program ALMAI</li><li>Sistem komunitas</li><li>Edukasi trading</li><li>Event dan webinar</li><li>Level user</li><li>Benefit program</li><li>Sistem reward</li><li>Penggunaan platform</li><li>Kebijakan umum</li></ul>'],
        ]
    ],
    [
        'id' => '12-penutup',
        'title' => '12. Penutup',
        'icon' => 'fas fa-door-closed',
        'content' => 'ALMAI berkomitmen membangun ekosistem edukasi dan pengembangan trader yang sehat, profesional, dan berkelanjutan melalui pendekatan komunitas, teknologi, dan pengembangan karakter.<br><br>Dengan mengedepankan nilai TRUSTED, SPECIALIST, dan EFFICIENT, ALMAI berharap dapat menjadi wadah pertumbuhan bagi trader, komunitas, dan generasi digital yang ingin berkembang secara disiplin, bertanggung jawab, dan konsisten dalam jangka panjang.<br><br>"Trusted System. Specialist Mindset. Efficient Growth."',
    ],
    [
        'id' => '13-struktur-kepemimpinan',
        'title' => '13. Struktur Kepemimpinan',
        'icon' => 'fas fa-sitemap',
        'content' => 'ALMAI menerapkan sistem kepemimpinan yang terstruktur untuk memastikan koordinasi, pengembangan komunitas, dan operasional berjalan secara efektif dan profesional. Setiap posisi memiliki peran, tanggung jawab, dan jalur koordinasi yang jelas untuk menjaga efektivitas komunikasi serta pengambilan keputusan dalam ekosistem ALMAI.',
        'subsections' => [
            ['title' => 'STRUKTUR', 'desc' => '<ul class=\'list-disc pl-5 mt-2 space-y-1\'><li>Management</li><li>Operational Team</li><li>Mentor & Educator</li><li>WPA (Wakil Penasihat Berjangka)</li><li>Community Leader</li><li>Partner & Collaboration Team</li></ul>'],
        ]
    ],
    [
        'id' => '14-peran-wpa',
        'title' => '14. Peran WPA',
        'icon' => 'fas fa-user-tie',
        'content' => 'WPA (Wakil Penasihat Berjangka) memiliki peran penting dalam membangun edukasi, pendampingan, dan komunikasi yang sehat kepada user dan komunitas.<br><br>WPA diharapkan menjadi representasi nilai TRUSTED, SPECIALIST, dan EFFICIENT dalam aktivitas edukasi dan komunitas.',
        'subsections' => [
            ['title' => 'Tanggung Jawab', 'desc' => '<ul class=\'list-disc pl-5 mt-2 space-y-1\'><li>Memberikan edukasi dan advokasi kepada user.</li><li>Mendampingi proses pembelajaran dan pengembangan trader.</li><li>Menjalankan komunikasi prosedural sesuai standar perusahaan.</li><li>Menjaga kepatuhan terhadap kebijakan dan regulasi.</li><li>Membantu membangun komunitas yang sehat, profesional, dan bertanggung jawab.</li></ul>'],
        ]
    ],
    [
        'id' => '15-tenaga-ahli-mentor',
        'title' => '15. Tenaga Ahli & Mentor',
        'icon' => 'fas fa-users-cog',
        'content' => 'ALMAI bekerja sama dengan mentor, trader profesional, dan tenaga ahli yang memiliki pengalaman, kompetensi, dan integritas dalam bidangnya masing-masing.<br><br>Seluruh mentor diharapkan mampu membantu member berkembang secara disiplin, objektif, dan terukur.',
        'subsections' => [
            ['title' => 'Standar Mentor', 'desc' => '<ul class=\'list-disc pl-5 mt-2 space-y-1\'><li>Memiliki kemampuan komunikasi dan edukasi yang baik.</li><li>Menguasai materi dan bidang spesialisasi.</li><li>Menjunjung etika profesional dan tanggung jawab.</li><li>Mampu membangun lingkungan pembelajaran yang sehat.</li><li>Berorientasi pada pengembangan jangka panjang komunitas.</li></ul>'],
        ]
    ],
    [
        'id' => '16-skema-operasional',
        'title' => '16. Skema Operasional',
        'icon' => 'fas fa-cogs',
        'content' => 'ALMAI menjalankan sistem operasional berbasis koordinasi, efisiensi, dan monitoring untuk mendukung pertumbuhan ekosistem secara berkelanjutan.<br><br>Sistem operasional dirancang agar seluruh aktivitas berjalan lebih terstruktur, scalable, dan efisien.',
        'subsections' => [
            ['title' => 'Skema', 'desc' => '<ul class=\'list-disc pl-5 mt-2 space-y-1\'><li>Sistem kerja dan pembagian tugas.</li><li>Jalur komunikasi internal dan partner.</li><li>Standar operasional prosedur (SOP).</li><li>Sistem monitoring aktivitas dan performa.</li><li>Pelaporan dan evaluasi berkala.</li><li>Pengelolaan program dan event.</li></ul>'],
        ]
    ],
    [
        'id' => '17-tata-kelola-perusahaan',
        'title' => '17. Tata Kelola Perusahaan',
        'icon' => 'fas fa-balance-scale',
        'content' => 'ALMAI menerapkan prinsip tata kelola perusahaan yang profesional dan bertanggung jawab.<br><br>Tata kelola ini menjadi dasar dalam membangun ekosistem yang Trusted dan berkelanjutan.',
        'subsections' => [
            ['title' => 'Prinsip Utama', 'desc' => '<ul class=\'list-disc pl-5 mt-2 space-y-1\'><li>Transparansi dalam komunikasi dan operasional.</li><li>Akuntabilitas terhadap tugas dan tanggung jawab.</li><li>Kepatuhan terhadap kebijakan dan regulasi.</li><li>Profesionalisme dalam pelayanan dan edukasi.</li><li>Pengawasan internal untuk menjaga kualitas sistem.</li></ul>'],
        ]
    ],
    [
        'id' => '18-pemegang-saham-kemitraan',
        'title' => '18. Pemegang Saham & Kemitraan',
        'icon' => 'fas fa-handshake',
        'content' => 'ALMAI membuka peluang kerja sama strategis dengan berbagai pihak yang memiliki visi pertumbuhan dan pengembangan ekosistem digital.<br><br>Seluruh kerja sama dilakukan berdasarkan prinsip profesionalisme, transparansi, dan pertumbuhan bersama.',
        'subsections' => [
            ['title' => 'Kemitraan', 'desc' => '<ul class=\'list-disc pl-5 mt-2 space-y-1\'><li>Edukasi dan pelatihan</li><li>Teknologi dan AI</li><li>Komunitas dan media</li><li>Event dan kolaborasi</li><li>Pengembangan bisnis dan branding</li></ul>'],
        ]
    ],
    [
        'id' => '19-okr-objectives-key-results',
        'title' => '19. OKR (Objectives & Key Results)',
        'icon' => 'fas fa-tasks',
        'content' => 'ALMAI menggunakan sistem OKR sebagai alat pengukuran pertumbuhan dan evaluasi performa tim maupun partner.<br><br>Sistem ini membantu seluruh tim bekerja lebih fokus, terukur, dan efisien.',
        'subsections' => [
            ['title' => 'Fokus OKR', 'desc' => '<ul class=\'list-disc pl-5 mt-2 space-y-1\'><li>Target pertumbuhan komunitas.</li><li>Target edukasi dan engagement.</li><li>KPI performa tim dan partner.</li><li>Kualitas pelayanan dan aktivitas.</li><li>Evaluasi hasil dan pengembangan berkelanjutan.</li></ul>'],
        ]
    ],
    [
        'id' => '20-kode-etik-kepatuhan',
        'title' => '20. Kode Etik & Kepatuhan',
        'icon' => 'fas fa-user-shield',
        'content' => 'Seluruh partner, mentor, dan komunitas ALMAI wajib menjaga etika komunikasi dan profesionalitas dalam setiap aktivitas.<br><br>Pelanggaran terhadap kode etik dapat memengaruhi status kemitraan dan akses dalam ekosistem ALMAI.',
        'subsections' => [
            ['title' => 'Kode Etik', 'desc' => '<ul class=\'list-disc pl-5 mt-2 space-y-1\'><li>Menjaga komunikasi yang sehat dan profesional.</li><li>Tidak melakukan manipulasi atau penyesatan informasi.</li><li>Menghindari klaim berlebihan dan misleading.</li><li>Mematuhi regulasi dan kebijakan perusahaan.</li><li>Menjaga integritas dan tanggung jawab sosial.</li></ul>'],
        ]
    ],
    [
        'id' => '21-kebijakan-risiko-perlindungan-user',
        'title' => '21. Kebijakan Risiko & Perlindungan User',
        'icon' => 'fas fa-exclamation-triangle',
        'content' => 'ALMAI mengedepankan edukasi risiko dan perlindungan user sebagai bagian penting dalam pengembangan trader.<br><br>ALMAI tidak menjanjikan keuntungan pasti dan selalu mendorong pengambilan keputusan yang objektif dan mandiri.',
        'subsections' => [
            ['title' => 'Kebijakan Risiko', 'desc' => '<ul class=\'list-disc pl-5 mt-2 space-y-1\'><li>Edukasi risk awareness dan money management.</li><li>Penjelasan risiko trading dan aset digital.</li><li>Perlindungan data dan privasi user.</li><li>Pencegahan konflik kepentingan.</li><li>Penggunaan komunikasi yang bertanggung jawab.</li></ul>'],
        ]
    ],
    [
        'id' => '22-dokumen-perjanjian-legalitas',
        'title' => '22. Dokumen Perjanjian & Legalitas',
        'icon' => 'fas fa-file-contract',
        'content' => 'Setiap aktivitas dan kerja sama dalam ekosistem ALMAI mengikuti dokumen dan kebijakan yang berlaku.<br><br>Seluruh partner dan user diharapkan membaca serta memahami dokumen yang berlaku sebelum mengikuti program atau kerja sama.',
        'subsections' => [
            ['title' => 'Dokumen Utama', 'desc' => '<ul class=\'list-disc pl-5 mt-2 space-y-1\'><li>Terms & Conditions</li><li>Disclaimer Risiko</li><li>Perjanjian Kemitraan</li><li>Kebijakan Privasi</li><li>Persetujuan User</li><li>SOP dan kebijakan internal</li></ul>'],
        ]
    ],
    [
        'id' => '23-kompensasi-benefit',
        'title' => '23. Kompensasi & Benefit',
        'icon' => 'fas fa-money-bill-wave',
        'content' => 'ALMAI menyediakan sistem benefit dan penghargaan berdasarkan kontribusi, aktivitas, dan performa dalam ekosistem.<br><br>Sistem benefit dirancang untuk mendorong pertumbuhan yang sehat dan berkelanjutan.',
        'subsections' => [
            ['title' => 'Benefit', 'desc' => '<ul class=\'list-disc pl-5 mt-2 space-y-1\'><li>Bonus performa dan pencapaian.</li><li>Reward komunitas dan event.</li><li>Komisi program dan kemitraan.</li><li>Sertifikasi internal.</li><li>Akses edukasi dan fasilitas premium.</li><li>Kesempatan pengembangan leadership dan networking.</li></ul>'],
        ]
    ],
    [
        'id' => '24-pengembangan-karir-leadership-path',
        'title' => '24. Pengembangan Karir & Leadership Path',
        'icon' => 'fas fa-user-graduate',
        'content' => 'ALMAI menyediakan jalur pengembangan bagi member dan partner yang ingin bertumbuh dalam ekosistem.<br><br>ALMAI percaya bahwa pertumbuhan komunitas dimulai dari pengembangan kualitas individu.',
        'subsections' => [
            ['title' => 'Tahapan', 'desc' => '<ul class=\'list-disc pl-5 mt-2 space-y-1\'><li>Member → Leader</li><li>Leader → WPA</li><li>WPA → Mentor</li><li>Mentor → Management</li></ul>'],
            ['title' => 'Fokus', 'desc' => '<ul class=\'list-disc pl-5 mt-2 space-y-1\'><li>Leadership</li><li>Komunikasi</li><li>Kompetensi edukasi</li><li>Pengembangan karakter</li><li>Profesionalisme</li><li>Manajemen komunitas</li></ul>'],
        ]
    ],
    [
        'id' => '25-sistem-almai-poin-reward',
        'title' => '25. Sistem Almai Poin & Reward',
        'icon' => 'fas fa-coins',
        'content' => 'ALMAI mengembangkan sistem apresiasi berbasis aktivitas dan kontribusi komunitas melalui Almai Poin.<br><br>Tujuan sistem ini adalah membangun komunitas yang aktif, suportif, dan bertumbuh secara positif dalam ekosistem ALMAI.',
        'subsections' => [
            ['title' => 'Sistem', 'desc' => '<ul class=\'list-disc pl-5 mt-2 space-y-1\'><li>Poin aktivitas dan partisipasi.</li><li>Reward komunitas dan event.</li><li>Ranking dan achievement user.</li><li>Reward kontribusi edukasi.</li><li>Program apresiasi partner dan leader.</li></ul>'],
        ]
    ],
    [
        'id' => '26-kebijakan-sdm',
        'title' => '26. Kebijakan SDM',
        'icon' => 'fas fa-user-check',
        'content' => 'ALMAI menerapkan kebijakan sumber daya manusia yang berfokus pada profesionalisme, pertumbuhan kompetensi, dan budaya kerja yang sehat dalam mendukung perkembangan ekosistem perusahaan.<br><br>ALMAI mendorong seluruh tim untuk bertumbuh secara profesional, adaptif, dan kolaboratif.',
        'subsections' => [
            ['title' => 'Kebijakan', 'desc' => '<ul class=\'list-disc pl-5 mt-2 space-y-1\'><li>Sistem rekrutmen dan seleksi tim.</li><li>Penempatan posisi berdasarkan kompetensi dan kebutuhan operasional.</li><li>Penilaian kerja dan evaluasi performa berkala.</li><li>Pengembangan kompetensi, leadership, dan skill internal.</li><li>Penerapan budaya kerja berbasis TRUSTED, SPECIALIST, dan EFFICIENT.</li><li>Monitoring perkembangan individu dan tim secara berkelanjutan.</li></ul>'],
        ]
    ],
    [
        'id' => '27-disiplin-evaluasi-sanksi',
        'title' => '27. Disiplin, Evaluasi & Sanksi',
        'icon' => 'fas fa-gavel',
        'content' => 'ALMAI menerapkan sistem disiplin internal untuk menjaga profesionalisme, integritas, dan kualitas operasional perusahaan.<br><br>Seluruh proses dilakukan secara objektif, profesional, dan berdasarkan kebijakan internal perusahaan.',
        'subsections' => [
            ['title' => 'Sistem', 'desc' => '<ul class=\'list-disc pl-5 mt-2 space-y-1\'><li>Teguran terhadap pelanggaran prosedur atau etika kerja.</li><li>Evaluasi performa dan tanggung jawab kerja.</li><li>Penanganan pelanggaran kode etik dan kebijakan perusahaan.</li><li>Suspensi sementara terhadap aktivitas tertentu jika diperlukan.</li><li>Penghentian kerja sama untuk pelanggaran berat atau berulang.</li></ul>'],
        ]
    ],
    [
        'id' => '28-keamanan-data-privasi',
        'title' => '28. Keamanan Data & Privasi',
        'icon' => 'fas fa-lock',
        'content' => 'ALMAI berkomitmen menjaga keamanan data, privasi user, serta perlindungan sistem digital dalam seluruh aktivitas operasional perusahaan.<br><br>Seluruh tim internal wajib menjaga keamanan informasi dan menggunakan data sesuai prosedur perusahaan.',
        'subsections' => [
            ['title' => 'Kebijakan', 'desc' => '<ul class=\'list-disc pl-5 mt-2 space-y-1\'><li>Perlindungan akun dan akses sistem internal.</li><li>Pengelolaan data user secara bertanggung jawab.</li><li>Keamanan platform dan infrastruktur digital.</li><li>Kerahasiaan dokumen dan informasi perusahaan.</li><li>Kepatuhan terhadap kebijakan privasi digital dan penggunaan data.</li></ul>'],
        ]
    ],
];
?>

<div class="flex flex-col lg:flex-row gap-8 relative min-h-screen items-start overflow-visible">
    <!-- Mobile ToC Toggle -->
    <button onclick="toggleToC()" class="lg:hidden fixed bottom-24 left-1/2 -translate-x-1/2 z-[100] bg-accent text-black border-2 border-[#111] px-6 py-3 rounded-full shadow-[0_10px_30px_rgba(51,232,24,0.3)] flex items-center justify-center gap-2 font-black uppercase tracking-widest text-xs whitespace-nowrap">
        <i class="fas fa-list-ul"></i>
        <span>Daftar Isi</span>
    </button>

    <!-- Sidebar Navigation -->
    <div id="tocSidebarContainer" class="hidden lg:block w-72 shrink-0">
        <aside id="tocSidebarDesktop" class="sticky top-28 bg-[#0a0a0a]/50 backdrop-blur-xl border border-white/10 rounded-3xl p-6 transition-all duration-300">
            <div class="mb-4 flex items-center gap-3">
                 <img src="https://almai.id/images/alma.gif" alt="ALMAI" class="h-6">
                 <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Daftar Bab 1-26</h4>
            </div>
            <nav class="space-y-1 max-h-[65vh] overflow-y-auto pr-2 custom-scrollbar">
                <?php foreach ($sections as $index => $section): ?>
                    <button 
                        onclick="switchSection('<?= $section['id'] ?>')" 
                        id="nav-<?= $section['id'] ?>"
                        class="toc-nav-btn w-full text-left px-4 py-3 text-sm text-gray-400 hover:text-accent hover:bg-white/5 rounded-2xl transition-all duration-300 flex items-center gap-3 border-l-2 border-transparent <?= $index === 0 ? 'active' : '' ?>"
                    >
                        <i class="<?= $section['icon'] ?> text-[10px] w-4 text-center"></i>
                        <span class="truncate font-medium"><?= $section['title'] ?></span>
                    </button>
                <?php endforeach; ?>
            </nav>
            
            <div class="mt-8 pt-6 border-t border-white/5">
                <a href="<?= $pdfUrl ?>" download class="flex items-center gap-3 px-4 py-4 bg-accent/10 border border-accent/20 hover:bg-accent/20 rounded-2xl transition-all group">
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
        <div class="mb-10 p-10 rounded-[2.5rem] bg-[#0c0c0c] border border-white/5 shadow-2xl relative overflow-hidden">
             <div class="relative z-10 flex items-center justify-between gap-6">
                <div>
                    <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter mb-2">Handbook <span class="text-accent">Almai</span></h1>
                    <p class="text-gray-500 font-medium">PT. ALMA INDONESIA RAYA (ALMAI)</p>
                </div>
                <div class="hidden md:block">
                    <img src="https://almai.id/images/alma.gif" alt="ALMAI" class="h-12">
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
                        <div class="w-16 h-16 rounded-2xl bg-accent text-black flex items-center justify-center shadow-[0_0_30px_rgba(51,232,24,0.3)]">
                            <i class="<?= $section['icon'] ?> text-2xl"></i>
                        </div>
                        <h2 class="text-3xl md:text-5xl font-black text-white tracking-tighter leading-none"><?= $section['title'] ?></h2>
                    </div>

                    <div class="bg-gradient-to-br from-[#0e0e0e] to-[#080808] border border-white/10 rounded-[2.5rem] p-8 md:p-14 shadow-2xl relative overflow-hidden group">
                        <div class="relative z-10">
                            <p class="text-gray-300 leading-relaxed text-base md:text-lg mb-12"><?= $section['content'] ?></p>
                            
                            <?php if (isset($section['subsections'])): ?>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <?php foreach ($section['subsections'] as $sub): ?>
                                        <div class="bg-black/40 border border-white/5 p-8 rounded-[1.5rem] hover:bg-white/[0.03] hover:border-accent/30 transition-all duration-500">
                                            <h4 class="font-bold text-white text-base mb-4 flex items-center gap-3">
                                                <div class="w-2 h-2 rounded-full bg-accent"></div>
                                                <?= $sub['title'] ?>
                                            </h4>
                                            <div class="text-gray-400 text-sm leading-relaxed"><?= $sub['desc'] ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <i class="<?= $section['icon'] ?> absolute -bottom-16 -right-16 text-white/[0.02] text-[300px] pointer-events-none transform -rotate-12"></i>
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
    
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.05); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(51, 232, 24, 0.4); }
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
