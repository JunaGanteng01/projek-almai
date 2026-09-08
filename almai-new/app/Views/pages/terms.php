<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="pt-32 pb-20 bg-dark-bg min-h-screen">
    <div class="container mx-auto px-6 max-w-4xl">
        <!-- Header -->
        <div class="text-center mb-16" data-aos="fade-up">
            <h1 class="text-3xl md:text-5xl font-bold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-white to-gray-400">
                SYARAT DAN KETENTUAN
            </h1>
            <p class="text-xl text-accent font-medium tracking-widest uppercase">
                <b>PENGGUNAAN LAYANAN PT. ALMA INDONESIA RAYA</b>
            </p>
        </div>

        <!-- Document Content -->
        <div class="bg-[#111] border border-white/10 rounded-3xl p-8 md:p-12 shadow-2xl space-y-12" data-aos="fade-up" data-aos-delay="100">
            <?php if (!empty($terms)): ?>
                <div class="prose prose-invert max-w-none text-gray-300">
                    <?= $terms ?>
                </div>
            <?php else: ?>
                <!-- Introduction & Company Info -->
                <div class="space-y-12">
                    <div class="border-b border-white/10 pb-8">
                        <p class="text-gray-400 italic mb-8">Diperbarui pada 1 Januari 2026</p>
                        <h2 class="text-3xl font-bold text-white mb-6">Informasi Perusahaan</h2>
                        <div class="text-gray-300 space-y-4 leading-relaxed">
                            <p>
                                PT Alma Indonesia Raya (selanjutnya disebut sebagai “PT AIR” atau “Almai” atau “Kami”) adalah perusahaan penasihat berjangka. PT AIR didirikan pada tahun 2021 dan secara resmi memperoleh izin usaha sebagai Perusahaan Penasihat Berjangka pada tahun 2024 dengan Nomor Izin 02/BAPPEBTI/SI-PNB/02/2024.
                            </p>
                            <p>
                                Almai.id adalah situs resmi milik PT AIR yang digunakan sebagai “one-stop platform” untuk mengakses segala layanan yang disediakan PT AIR sebagai perusahaan penasihat berjangka, informasi dan edukasi di bidang Perdagangan Berjangka Komoditi serta Aset Digital termasuk Aset Kripto, derivatif keuangan seperti komoditi, efek, pasar uang dan valuta asing, kripto dan aset digital.
                            </p>
                            <div class="grid md:grid-cols-2 gap-4 text-sm bg-white/5 p-6 rounded-2xl border border-white/5 mt-6">
                                <div>
                                    <p class="text-gray-500 text-xs font-bold mb-1 uppercase">Nama Perusahaan</p>
                                    <p class="text-white">PT Alma Indonesia Raya</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs font-bold mb-1 uppercase">Alamat</p>
                                    <p class="text-white">Jl. Badak Agung No. 22 Kav. 3, Kel. Renon. Denpasar - Bali 80226</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs font-bold mb-1 uppercase">Situs</p>
                                    <p class="text-white">https://almai.id/</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs font-bold mb-1 uppercase">Email / CS</p>
                                    <p class="text-white">almaindonesia.io@gmail.com / cs@almai.id</p>
                                </div>
                                <div class="md:col-span-2">
                                    <p class="text-gray-500 text-xs font-bold mb-1 uppercase">Perizinan</p>
                                    <ul class="list-disc pl-5 text-white/80 space-y-1">
                                        <li>01/BAPPEBTI/SI-PNB/02/2024 (izin Penasihat Berjangka)</li>
                                        <li>01/BAPPEBTI/SP-PBEA/06/2024 (izin Expert Advisor)</li>
                                        <li>010946.01/DJAI.PSE/08/2023 (Tanda Daftar PSE)</li>
                                        <li>S-128/PM.02/2025 (Persetujuan Prinsip Penasihat Investasi)</li>
                                    </ul>
                                </div>
                                <div>
                                    <p class="text-gray-500 text-xs font-bold mb-1 uppercase">Nomor Telepon</p>
                                    <p class="text-white">03613610019 / 085183390019 / 085183231800</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ketentuan Umum -->
                    <section class="space-y-6">
                        <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="w-2 h-8 bg-accent rounded-full"></span>
                            Ketentuan Umum
                        </h2>
                        <div class="text-gray-300 space-y-4 text-sm leading-relaxed text-justify">
                            <p>Syarat dan Ketentuan Penggunaan Layanan Almai.id (“Syarat dan Ketentuan”) adalah perjanjian antara Anda (“Anda” atau “Pengguna”) dengan PT AIR yang mengatur tentang bagaimana Anda menggunakan produk, jasa, teknologi, dan layanan yang disediakan oleh PT AIR termasuk, namun tidak terbatas pada layanan ketika Anda mendaftarkan akun melalui situs www.almai.id, situs terkait yang merupakan situs resmi PT AIR, layanan API, dan aplikasi seluler milik PT AIR. Seluruh layanan, edukasi, dan informasi, yang disediakan oleh PT AIR, khususnya dalam hal ini melalui situs Almai.id, dioperasikan dan disediakan sesuai dengan hukum Republik Indonesia. Dengan mengakses, melakukan pemesanan/pembelian, dan/atau menggunakannya, Anda dianggap telah membaca, memahami, dan menyetujui seluruh ketentuan dalam Syarat dan Ketentuan ini. Jika Anda tidak setuju, mohon untuk tidak menggunakan Layanan Almai. Istilah "Anda" merujuk pada Anda sebagai individu maupun entitas yang Anda wakili. Jika Anda melanggar ketentuan ini, kami berhak membatalkan akun Anda atau memblokir akses ke akun Anda tanpa pemberitahuan. Syarat dan Ketentuan ini berlaku pula untuk seluruh situs web serta komunikasi email atau jenis komunikasi lainnya antara Anda dan Almai.</p>
                            <p>Almai tidak bertanggung jawab atas kerugian langsung, tidak langsung, khusus, insidental, atau konsekuensial, termasuk namun tidak terbatas pada, kehilangan data atau keuntungan yang timbul dari penggunaan, atau ketidakmampuan menggunakan materi yang tersedia pada situs ini. Jika penggunaan materi dari situs ini mengakibatkan perlunya perbaikan atau koreksi peralatan atau data, Anda menanggung seluruh biayanya.</p>
                            <p>Almai tidak bertanggung jawab atas hasil apa pun yang mungkin terjadi selama penggunaan sumber daya Kami. Kami berhak mengubah harga dan merevisi kebijakan termasuk Syarat dan Ketentuan ini kapan saja tanpa persetujuan Anda terlebih dahulu.</p>
                        </div>
                    </section>

                    <!-- 3. Definisi -->
                    <section class="space-y-6">
                        <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="w-1.5 h-8 bg-accent rounded-full"></span>
                            Definisi
                        </h2>
                        <div class="space-y-4">
                            <?php
                            $full_defs = [
                                "1. Akun" => "adalah akun atau akses yang diberikan kepada Pengguna untuk menagkses layanan pada situs almai.id.",
                                "2. Almai Poin" => "adalah istem penilaian dan penghargaan dalam situs almai.id yang dirancang untuk meningkatkan keterlibatan dan kinerja Pengguna Portal Penasihat Berjangka almai.id, yang dapat digunakan/ditukarkan untuk akses Layanan.",
                                "3. Aset Keuangan Digital" => "adalah aset keuangan yang disimpan atau direpresentasikan secara digital, termasuk di dalamnya aset kripto.",
                                "4. Aset Kripto" => "adalah adalah representasi digital dari nilai yang dapat disimpan dan ditransfer menggunakan teknologi yang memungkinkan penggunaan buku besar terdistribusi seperti blockchain untuk memverifikasi transaksinya dan memastikan keamanan dan validitas informasi yang tersimpan, tidak dijamin oleh otoritas pusat seperti bank sentral tetapi diterbitkan oleh pihak swasta, dapat ditransaksikan, disimpan, dan dipindahkan atau dialihkan secara elektronik, dan dapat berupa koin digital, token, atau representasi aset lainnya yang mencakup aset kripto terdukung (backed crypto asset) dan aset kripto tidak terdukung (unbacked crypto-asset).",
                                "5. Calon Wakil Penasihat Berjangka (“CWPA”)" => "adalah orang perseorangan yang sedang dalam proses untuk memperoleh izin sebagai WPA.",
                                "6. Informasi" => "adalah keterangan yang dapat berupa analisis mengenai harga dan volume perdagangan, risiko harga dan likuiditas, faktor faktor yang mempengaruhi pergerakan harga, kegiatan, mekanisme dan institusi Perdagangan Berjangka dan/atau penyelenggaraan Pasar Fisik Komoditi di Bursa Berjangka.",
                                "7. Informasi Rahasia" => "adalah setiap informasi dan/atau data yang diberikan oleh Almai kepada Pengguna dan/atau data yang diperoleh oleh Pengguna sebagai pelaksanaan dari Syarat dan Ketentuan, baik yang diberikan atau disampaikan secara lisan, tertulis, grafis, atau disampaikan melalui media elektronik, atau informasi dan/atau data dalam bentuk lain selama berlangsungnya Layanan atau selama Pengguna mengakses Layanan dan situs Almai yang sifatnya tidak diketahui umum, atau hanya diperoleh sebagai hasil penyediaan Layanan yang tidak diketahui publik.",
                                "8. Klien" => "adalah pihak yang telah melakukan pembelian Layanan dan mempergunakan jasa Perusahaan sebagai Penasihat Berjangka untuk mendapatkan Nasihat.",
                                "9. Komoditi" => "adalah semua barang, jasa, hak dan kepentingan lainnya, dan setiap derivatif dari Komoditi, yang dapat diperdagangkan dan menjadi subjek Kontrak Berjangka, Kontrak Derivatif Syariah, dan/atau Kontrak Derivatif lainnya.",
                                "10. Kontrak Berjangka" => "adalah suatu bentuk kontrak standar untuk membeli atau menjual Komoditi dalam jumlah, mutu, jenis, tempat, dan waktu penyerahan di kemudian hari yang telah ditetapkan, dan termasuk dalam pengertian Kontrak Berjangka ini adalah Opsi atas Kontrak Berjangka.",
                                "11. Kontrak Derivatif" => "adalah kontrak yang nilai dan harganya bergantung pada subjek Komoditi.",
                                "12. Layanan" => "adalah layanan yang disediakan oleh Perusahaan sebagai Penasihat Berjangka sebagaimana dijelaskan dalam Syarat dan Ketentuan ini.",
                                "13. Layanan Kepenasihatan" => "adalah Layanan Almai yang diberikan kepada Klien berupa Nasihat dan Rekomendasi di bidang PBK dan Aset Digital, termasuk pula pemberian Nasihat Berbasis Teknologi Informasi Berupa Expert Advisor.",
                                "14. Layanan Pendampingan CWPA" => "yaitu Layanan Almai yang memfasilitasi pihak-pihak yang memenuhi syarat dan ingin menjadi WPA.",
                                "15. Layanan Pihak Ketiga" => "adalah pengiklan, sponsor kontes, mitra promosi/pemasaran, dan pihak lain yang bekerja sama dengan Perusahaan, yang menyediakan konten atau produk mungkin menarik bagi Anda.",
                                "16. Nasihat Berbasis Teknologi Informasi berupa Expert Advisor (“Expert Advisor”)" => "adalah alat bantu berbasis Teknologi Informasi yang didalamnya tersusun berdasarkan algoritma yang ditanamkan pada baris-baris programnya yang ditentukan berdasarkan karakteristik, tipe, kebutuhan,dan harapan Klien.",
                                "17. Pasar Fisik Komoditi di Bursa Berjangka" => "yang selanjutnya disebut,  Pasar Fisik adalah pasar fisik terorganisir yang dilaksanakan menggunakan sarana elektronik yang difasilitasi oleh Bursa Berjangka atau sarana elektronik yang dimiliki oleh Pedagang Fisik Komoditi.",
                                "18. Penasihat Perdagangan Berjangka" => "adalah orang perseorangan atau Badan Usaha yang memberikan Nasihat kepada pihak lain mengenai jual beli Komoditi berdasarkan Kontrak Berjangka, Kontrak Derivatif Syariah, dan/atau Kontrak Derivatif lainnya dengan menerima imbalan.",
                                "19. Pengguna" => "adalah perorangan maupun badan hukum yang mendaftar pada situs almai.id namun belum melakukan pembelian Layanan.",
                                "20. Perangkat" => "adalah Perangkat apa pun yang terhubung ke internet seperti ponsel, tablet, atau komputer yang digunakan untuk mengunjungi situs almai.id.",
                                "21. Perdagangan Berjangka Komoditi" => "adalah yang selanjutnya disebut Perdagangan Berjangka adalah segala sesuatu yang berkaitan dengan jual beli Komoditi dengan penarikan Margin dan penyelesaian kemudian berdasarkan Kontrak Berjangka, Kontrak Derivatif Syariah, dan/atau Kontrak Derivatif lainnya.",
                                "22. Rekomendasi" => "adalah masukan yang disampaikan oleh Penasihat Berjangka kepada Klien yang tidak bersifat memaksa dengan risiko pengambilan keputusan ada di pihak Klien yang dapat berupa masukan keputusan yang perlu dilakukan oleh Klien untuk dapat mengambil manfaat dari jual-beli Komoditi berdasarkan Kontrak Berjangka, Kontrak Derivatif Syariah, dan/atau Kontrak Derivatif lainnya berbasis risiko dengan mempertimbangkan risk profile, risk appetite, dan risk objective Klien dalam melakukan transaksi Perdagangan Berjangka.",
                                "23. Situs" => "adalah Situs Perusahaan yang dapat diakses melalui URL: almai.id.",
                                "24. Wakil Penasihat Berjangka (“WPA”)" => "adalah orang perseorangan yang berdasarkan kesepakatan dengan Penasihat Berjangka, melaksanakan sebagian fungsi Penasihat Berjangka."
                            ];
                            foreach ($full_defs as $term => $def): ?>
                                <div class="text-sm border-l-2 border-accent/30 pl-4 py-1">
                                    <p class="text-gray-300"><strong class="text-white"><?= $term ?></strong> <?= $def ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>

                    <!-- 4. Pendaftaran Akun pada Situs -->
                    <section class="space-y-6">
                        <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="w-1.5 h-8 bg-accent rounded-full"></span>
                            Pendaftaran Akun pada Situs
                        </h2>
                        <div class="text-gray-300 text-sm space-y-4 leading-relaxed">
                            <p><b>1. Untuk dapat menggunakan Layanan Kami, Anda wajib terdaftar sebagai Pengguna dengan memenuhi persyaratan sebagai berikut:</b></p>
                            <ul class="pl-6 space-y-2 list-none">
                                <li>1.1. Telah berusia 18 (delapan belas) tahun atau lebih;</li>
                                <li>1.2. Memiliki KTP;</li>
                                <li>1.3. Dalam menggunakan Layanan, Anda hanya akan menggunakan dana milik sendiri dan bukan dana yang bersumber atau milik dari orang lain, atau hasil tindak pidana, pencucian uang, pendanaan terorisme dan/atau senjata pemusnah massal;</li>
                                <li>1.4. Cakap secara jasmani dan rohani, serta memiliki akal serta memiliki akal sehat untuk melakukan tindakan hukum (persetujuan perjanjian);</li>
                                <li>1.5. Melakukan pendaftaran melalui situs almai.id;</li>
                                <li>1.6. Apabila Anda mewakili entitas badan usaha, maka Anda juga perlu memiliki perizinan usaha yang sesuai berdasarkan ketentuan hukum yang berlaku; dan</li>
                                <li>1.7. Menyatakan persetujuan untuk tunduk pada Syarat dan Ketentuan dan Kebijakan Privasi Almai.</li>
                            </ul>
                            <p><b>2. Setelah melakukan pendaftaran Akun, Anda wajib memberikan informasi Anda, termasuk namun tidak terbatas pada:</b></p>
                            <ul class="pl-6 space-y-2 list-none">
                                <li>2.1. Identitas Diri: Nama lengkap sesuai dengan Kartu Tanda Penduduk (KTP), Paspor, atau identitas resmi lainnya yang dikeluarkan oleh pemerintah;</li>
                                <li>2.2. Informasi Kontak: Alamat surat elektronik (e-mail) yang aktif dan/atau nomor telepon seluler;</li>
                                <li>2.3. Data Kependudukan: Alamat tempat tinggal saat ini dan/atau alamat sesuai identitas;</li>
                                <li>2.4. Verifikasi Identitas (KYC): Foto identitas resmi dan foto diri (selfie) untuk keperluan verifikasi identitas guna mematuhi peraturan perundang-undangan mengenai Anti Pencucian Uang dan Pencegahan Pendanaan Terorisme (APU-PPT); dan</li>
                            </ul>
                            <p>3. Almai berhak untuk meminta informasi atau dokumen lainnya sebagaimana disebutkan dalam Syarat dan Ketentuan ini yang diperlukan sehubungan dengan informasi Pengguna. Anda dengan ini menyatakan dan menjamin bahwa setiap data/penjelasan/dokumen/informasi/pernyataan yang diberikan sehubungan dengan diri Anda dan/atau entitas yang Anda wakili, termasuk namun tidak terbatas pada proses pendaftarannya sebagai Pengguna, adalah lengkap, asli, benar, dan sesuai dengan keadaan yang sebenarnya, serta bahwa setiap data/penjelasan/dokumen/informasi/pernyataan tersebut merupakan data terbaru yang belum diubah dan masih berlaku/tidak kedaluwarsa.</p>
                            <p>4. Pengguna dengan ini setuju bahwa setiap data, penjelasan, informasi, pernyataan, dokumen yang diperoleh Almai mengenai Pengguna, akan menjadi milik Almai dan Almai memiliki hak untuk melakukan verifikasi, pencocokan, penilaian, menjaga kerahasiaannya atau menggunakannya untuk kepentingan Almai sesuai dengan ketentuan hukum yang berlaku tanpa kewajiban untuk memberitahukan atau meminta persetujuan, memberikan jaminan atau ganti rugi dan untuk alasan apapun kepada Pengguna.</p>
                            <p>5. Pengguna dengan ini memberikan persetujuan kepada Almai untuk dapat mengungkapkan informasi mengenai Pengguna kepada aparat penegak hukum atau instansi pemerintah yang berwenang, apabila diharuskan oleh peraturan perundang-undangan yang berlaku atau atas dasar perintah pengadilan yang sah.</p>
                        </div>
                    </section>

                    <!-- 5. Perihal Akun -->
                    <section class="space-y-6">
                        <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="w-1.5 h-8 bg-accent rounded-full"></span>
                            Perihal Akun
                        </h2>
                        <div class="text-gray-300 text-sm space-y-4 leading-relaxed">
                            <p>1. Setiap Akun yang dibuka akan dikelola oleh Almai. Pengguna dilarang keras menggunakan, mengakses, atau membantu pihak lain untuk memperoleh akses tanpa hak atas Akun milik orang lain. Seluruh penggunaan Akun merupakan tanggung jawab penuh dari masing-masing pemilik Akun yang terdaftar dalam sistem basis data Almai.</p>
                            <p>2. Ketika melakukan login Pengguna wajib menggunakan kombinasi email/username dan kata sandi yang telah terverifikasi untuk mengakses Akun. Sistem Almai akan menolak akses secara otomatis jika kredensial tersebut tidak sesuai.</p>
                            <p>3. Pengguna bertanggung jawab penuh atas kerahasiaan kata sandi, akses email, serta seluruh aktivitas transaksi dalam Akun. Segala penyalahgunaan Akun dan kata sandi, baik dengan atau tanpa sepengetahuan Pengguna, merupakan tanggung jawab Pengguna sepenuhnya dan Almai dibebaskan dari segala kerugian yang timbul. Jika terjadi dugaan pelanggaran atau penggunaan tanpa izin, Pengguna wajib segera menghubungi Almai melalui e-mail ke cs@almai.id dengan melampirkan informasi pendukung.</p>
                            <p>4. Pengguna dilarang menggunakan Layanan untuk segala jenis tindak pidana, termasuk namun tidak terbatas pada pencucian uang, perjudian, pembelian barang ilegal, aktivitas terorisme, atau peretasan. Setiap pelanggaran akan mengakibatkan pemberhentian akses, dan Pengguna wajib bertanggung jawab penuh atas kerugian yang diderita oleh Almai maupun pengguna lain.</p>
                            <p>5. Almai berhak menutup dan/atau membekukan Akun, membatasi dan/atau menarik akses, membekukan Almai Poin yang telah maupun yang akan diterima, untuk jangka waktu yang tidak ditentukan apabila ditemukan aktivitas mencurigakan atau pelanggaran terhadap Syarat dan Ketentuan ini.</p>
                            <p>6. Pengguna dapat mengubah Nama Pengguna (Username) dan email yang telah terdaftar lewat pengajuan yang dikirimkan melalui cs@almai.id. Untuk menjaga kemungkinan penyalahgunaan akun, Almai memiliki kewenangan penuh untuk menyetujui ataupun menolak pengajuan tersebut.</p>
                        </div>
                    </section>

                    <!-- 6. Ruang Lingkup Layanan -->
                    <section class="space-y-6">
                        <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="w-1.5 h-8 bg-accent rounded-full"></span>
                            Ruang Lingkup Layanan
                        </h2>
                        <div class="text-gray-300 text-sm space-y-4 leading-relaxed">
                            <p>Layanan-Layanan Almai yang disediakan oleh Almai adalah:</p>
                            <p>1. Layanan Kepenasihatan yang terbagi menjadi:</p>
                            <ul class="pl-6 space-y-2 list-none">
                                <li>1.1. Layanan Webinar, yaitu Layanan berbayar untuk mengikuti satu kali webinar perkenalan dasar-dasar PBK dan Aset Digital;</li>
                                <li>1.2. Layanan Workshop, yaitu Layanan berbayar untuk memperoleh Pelatihan dan/atau Simulasi Perdagangan yang mencakup:
                                    <ul class="pl-6 mt-2 space-y-1 text-gray-300 italic">
                                        <li>- Online Group Discussion;</li>
                                        <li>- Pemberian Signal, Entry-Exit Setup;</li>
                                        <li>- Materi terkait trading, seperti namun tidak terbatas pada psikologi, manajemen keuangan, manajemen risiko.</li>
                                    </ul>
                                </li>
                                <li>1.3. Layanan Expert Advisor, yaitu penjualan software dan/atau hak akses atas aplikasi Expert Advisor Almai yang disertai pendampingan dan pelatihan cara penggunaannya oleh WPA.</li>
                            </ul>
                            <p>2. Layanan Pendampingan CWPA yaitu Layanan Almai yang memfasilitasi pihak-pihak yang memenuhi syarat dan ingin menjadi WPA. Almai menyediakan platform dibawah domain resmi Almai untuk masing-masing CWPA yang menampilkan tahapan proses sertifikasi CWPA. Layanan Pendampingan CWPA yang diberikan Almai adalah:
                            <ul class="pl-6 mt-2 space-y-1 list-none">
                                <li>a. Pendampingan dan pembekalan materi-materi khususnya terkait PBK dan Aset Digital;</li>
                                <li>b. Pengurusan pendaftaran kelas-kelas sertifikasi;</li>
                                <li>c. Pengajuan Izin WPA dibawah naungan PT AIR; dan</li>
                                <li>d. Penyelenggaraan acara (seminar, sosialisasi, webinar)
                                    Penyelenggaraan acara (seminar, sosialisasi, webinar) mengenai namun tidak terbatas pada : </li>
                                    <ol type="i">
                                        <li>i.      Seputar Izin WPA</li>
                                        <li>ii.     Seputar pentingnya memperoleh nasihat PBK dan Aset Digital dari pihak yang memiliki Izin, dan</li>
                                        <li>iii.    Kelas-kelas atau Layanan dari WPA</li>
                                    </ol>
                            </ul>
                            </p>
                            <p>3. Almai Poin. Almai Poin adalah fitur yang memungkinkan Pengguna untuk mengumpulkan poin-poin melalui penyelesaian kegiatan tertentu yang dapat ditukarkan untuk mengakses Layanan atau fitur-fitur pada almai.id.</p>
                        </div>
                    </section>

                    <!-- 7. Pembayaran -->
                    <section class="space-y-6">
                        <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="w-1.5 h-8 bg-accent rounded-full"></span>
                            Pembayaran
                        </h2>
                        <div class="text-gray-300 text-sm space-y-4 leading-relaxed text-justify">
                            <p>1. 
                                Untuk paket pembayaran satu kali, Anda setuju untuk membayar semua biaya sesuai dengan ketentuan penagihan yang berlaku pada saat 
                                biaya tersebut jatuh tempo. Penggunaan kartu kredit Anda diatur oleh perjanjian Penyedia Pembayaran Anda, bukan Ketentuan ini. 
                                Dengan memberikan informasi kartu kredit, Anda mengizinkan Almai untuk memverifikasi informasi dan menagih akun Anda tanpa memerlukan 
                                pemberitahuan tambahan. Anda wajib segera memberi tahu Almai jika ada perubahan alamat penagihan atau kartu kredit. Almai berhak 
                                mengubah harga dan metode penagihan kapan saja. Kontrak untuk Layanan baru dianggap ada setelah Almai mengirimkan email konfirmasi, 
                                SMS/MMS, atau komunikasi resmi lainnya.
                            </p>
                            <p>2.
                                Dengan melakukan pemesanan di Almai, Anda menyetujui persyaratan ini beserta Kebijakan Privasi Almai. 
                                Almai tidak mengakomodasi pengembalian dan atas Layanan yang telah dibeli oleh pengguna (refund). 
                                Jika Anda tidak puas dengan produk atau layanan kami, silakan hubungi kami untuk mendiskusikan masalah yang Anda alami.
                            </p>
                        </div>
                    </section>

                    <!-- 8. HKI -->
                    <section class="space-y-6">
                        <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="w-1.5 h-8 bg-accent rounded-full"></span>
                            Hak Kekayaan Intelektual
                        </h2>
                        <p class="text-gray-300 text-sm leading-relaxed text-justify">
                            Situs web dan seluruh isinya (perangkat lunak, teks, gambar, video, dll.) dimiliki oleh Almai atau pemberi lisensinya dan dilindungi oleh undang-undang hak cipta Indonesia serta internasional. Materi tidak boleh disalin, dimodifikasi, atau didistribusikan tanpa izin tertulis sebelumnya dari Almai.
                        </p>
                    </section>

                    <!-- 9. Arbitrase -->
                    <section class="space-y-6">
                        <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="w-1.5 h-8 bg-accent rounded-full"></span>
                            Arbitrase dan Penyelesaian Sengketa
                        </h2>
                        <p class="text-gray-300 text-sm leading-relaxed text-justify">Bagian ini berlaku untuk setiap sengketa KECUALI TIDAK TERMASUK SENGKETA YANG BERKAITAN DENGAN KLAIM UNTUK PEMULIHAN INJUNKTIF ATAU ADIL TERKAIT PENEGAKAN ATAU VALIDITAS HAK KEKAYAAN INTELEKTUAL ANDA ATAU Almai. Istilah "sengketa" berarti sengketa, tindakan, atau kontroversi lainnya antara Anda dan Almai mengenai Layanan atau perjanjian ini, baik dalam kontrak, jaminan, gugatan, undang-undang, peraturan, tata cara, atau dasar hukum atau keadilan lainnya. "Sengketa" akan diberikan arti seluas-luasnya yang diperbolehkan menurut hukum.</p>
                        <p class="text-gray-300 text-sm leading-relaxed text-justify">Dalam hal terjadi sengketa, Anda atau Almai harus memberikan Pemberitahuan Sengketa kepada pihak lainnya, yang merupakan pernyataan tertulis yang mencantumkan nama, alamat, dan informasi kontak pihak yang memberikannya, fakta-fakta yang menimbulkan sengketa, dan bantuan yang diminta. Anda harus mengirimkan Pemberitahuan Sengketa melalui email ke: cs@almai.id. Almai akan mengirimkan Pemberitahuan Sengketa kepada Anda melalui pos ke alamat Anda jika kami memilikinya, atau ke alamat email Anda. Anda dan Almai akan mencoba menyelesaikan sengketa apa pun melalui negosiasi informal dalam waktu enam puluh (60) hari sejak tanggal Pemberitahuan Sengketa dikirimkan. Setelah enam puluh (60) hari, Anda atau Almai dapat memulai arbitrase.</p>
                    </section>

                    <!-- 10. Pengaduan -->
                    <section class="space-y-6">
                        <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="w-1.5 h-8 bg-accent rounded-full"></span>
                            Tanggapan, Pernyataan, dan Pengaduan Penyelesaian Masalah
                        </h2>
                        <p class="text-gray-300 text-sm leading-relaxed text-justify">
                            Keluhan diajukan melalui cs@almai.id dengan melampirkan identitas resmi. 
                            Petugas akan memberikan surat resolusi dalam jangka waktu 15 hingga 35 hari kerja setelah keluhan diterima.
                        </p>

                       <!-- 11-13. Pernyataan dan Jaminan -->
                    <section class="space-y-6">
                        <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="w-1.5 h-8 bg-accent rounded-full"></span>
                            Pernyataan dan Jaminan
                        </h2>
                        <div class="text-gray-300 text-sm space-y-4 leading-relaxed text-justify">
                            <p>1. Almai tidak menjamin sistem selalu bebas dari gangguan teknis atau pemeliharaan berkala.</p>
                            <p>2. Pengguna setuju untuk tidak, dan tidak akan mengizinkan orang lain untuk:
                                <ul class="list-disc list-outside pl-5 space-y-2">
                                    <li>Melisensikan, menjual, menyewakan, mengalihkan, mendistribusikan, menghosting, atau mengeksploitasi situs web secara komersial kepada pihak ketiga.</li>
                                    <li>Memodifikasi, membuat karya turunan, membongkar (disassemble), mendekripsi, atau merekayasa balik (reverse engineer) bagian apa pun dari situs web.</li>
                                    <li>Menghapus atau mengaburkan pemberitahuan hak milik (termasuk hak cipta atau merek dagang) milik Almai atau mitranya.</li>
                                    </ul>
                            </p>
                            <p>3. Pengguna dengan ini menyatakan dan menjamin bahwa Pengguna hanya akan menggunakan Layanan sesuai hukum Republik Indonesia dan dilarang memanfaatkannya untuk narkotika, perjudian, senjata, skema Ponzi, atau aktivitas ilegal lainnya. </p>
                            <p>4. Pengguna menjamin bahwa seluruh data yang diberikan kepada Almai adalah asli dan benar, serta bersedia dituntut secara pidana maupun digugat secara perdata jika terbukti memberikan data palsu, tidak benar/tidak sepenuhnya benar. Pengguna bersedia untuk melakukan pembaharuan data/informasi (profile update) apabila sewaktu-waktu diminta pihak Almai.</p>
                            <p>5. Pengguna memberikan persetujuan dan kuasa kepada Almai untuk menggunakan seluruh data dan informasi Pengguna untuk tujuan apa pun yang diizinkan oleh peraturan perundang-undangan, termasuk untuk keperluan pemasaran Almai maupun mitra pihak ketiganya. Dalam hal penggunaan data memerlukan persetujuan pihak lain, Pengguna menjamin bahwa persetujuan tertulis telah diperoleh dari pihak ketiga tersebut. Oleh karenanya, Almai dibebaskan dari segala tanggung jawab, ganti rugi, klaim, atau gugatan hukum yang timbul di kemudian hari terkait penggunaan data dan informasi yang telah mendapatkan persetujuan tertulis tersebut.</p>
                            <p>6. Pengguna menyatakan dan menjamin bahwa risiko terhadap penggunaan layanan, produk dan promosi pihak ketiga dengan Pengguna (apabila ada), ditanggung oleh Pengguna, dan Pengguna menyatakan bahwa Almai tidak bertanggung jawab atas layanan dan kinerja layanan pihak ketiga.</p>
                            <p>7. Pengguna dengan ini bertanggung jawab sepenuhnya dan setuju bahwa Almai tidak akan memberikan ganti rugi dan atau pertanggungjawaban dalam bentuk apapun kepada Pengguna atau pihak manapun atas segala kerugian dan atau klaim dan atau tuntutan yang timbul atau mungkin dialami oleh Pengguna sebagai akibat dari kelalaian Pengguna.</p>
                            <p>8. Pengguna dengan ini memberikan jaminan kepada Almai bahwa Pengguna beserta dengan seluruh karyawannya, afiliasinya, dan atau pihak lain yang bekerja sama dengan Pengguna tidak akan memperbanyak dan atau membuat, memberikan, menyewakan, menjual, memindahkan, mengalihkan, dan atau mengalih-fungsikan Layanan baik sebagian atau seluruhnya kepada pihak lain dengan alasan apapun, termasuk namun tidak terbatas pada penyalahgunaan Situs almai.id untuk melakukan transaksi selain dari yang telah ditentukan dalam Syarat dan Ketentuan dengan maksud apapun, termasuk namun tidak terbatas untuk kejahatan/penipuan/kecurangan. Apabila Pengguna melanggar ketentuan tersebut, maka Pengguna wajib bertanggung jawab atas segala kerugian, tuntutan dan atau gugatan yang timbul akibat dari pelanggaran tersebut dan dengan ini setuju bahwa Almai tidak akan memberikan ganti rugi dan atau pertanggungjawaban dalam bentuk apapun kepada Pengguna atau pihak manapun atas segala klaim dan atau tuntutan dan atau gugatan yang timbul akibat pelanggaran tersebut, termasuk gugatan dan/atau tuntutan dari pihak Almai.</p>
                        </div>
                    </section>
                       

                    <!-- 11-13. Tanggung Jawab -->
                    <section class="space-y-6">
                        <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="w-1.5 h-8 bg-accent rounded-full"></span>
                            Tanggung Jawab
                        </h2>
                        <div class="text-gray-300 text-sm space-y-4 leading-relaxed text-justify">
                            <p>1. Pengguna setuju bahwa segala risiko akibat kebocoran kata sandi, kesalahan komunikasi, atau kelalaian operasional yang disebabkan oleh Pengguna adalah sepenuhnya menjadi beban Pengguna.</p>
                            <p>2. Perdagangan Berjangka Komoditi dan Aset Digital memiliki fluktuasi harga yang ekstrem dan tidak dijamin oleh pemerintah. Almai tidak bertanggung jawab atas kerugian finansial akibat pergerakan pasar.</p>
                            <p>3. Layanan yang disediakan Almai, khususnya terkait Layanan Kepenasihatan adalah berupa Nasihat dan informasi yang tidak bersifat mengikat. Pengguna wajib melakukan riset pribadi dan mengambil keputusan transaksi secara sadar tanpa paksaan. </p>
                    </section>

                     <!-- 11-13. Batasan Tanggung Jawab -->
                    <section class="space-y-6">
                        <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="w-1.5 h-8 bg-accent rounded-full"></span>
                            Batasan Tanggung Jawab
                        </h2>
                        <div class="text-gray-300 text-sm space-y-4 leading-relaxed text-justify">
                            <p>1. Almai adalah Penasihat Berjangka dan penyedia Expert Advisor dan bukan pihak dalam transaksi bursa. Almai tidak bertanggung jawab atas kerugian yang timbul dari kegagalan sistem bursa pihak ketiga atau kelalaian Pengguna dalam menjaga keamanan perangkatnya. Almai tidak pula bertanggung jawab atas kerugian yang timbul dari trading yang Anda lakukan. Segala bentuk Nasihat dan Rekomendasi khususnya Trading Plan dari Almai akan selalu menyertakan entry dan exit plan, apabila Anda melanggar Trading Plan, maka segala kerugian yang timbul adalah menjadi tanggung jawab Anda, dan dengan ini Anda membebaskan Almai dari segala tanggung jawab, kewajiban, tuntutan, dan/atau gugatan apapun. </p>
                            <p>2. Almai senantiasa berupaya untuk menjaga Layanan Almai tetap aman, nyaman, dan berfungsi dengan baik, namun Almai tidak menjamin operasional yang berkelanjutan atau akses yang selalu sempurna terhadap Layanan kami. Terdapat kemungkinan bahwa informasi dan data pada situs web dan aplikasi Almai tidak tersedia secara real-time. Almai menyarankan Pengguna untuk selalu melakukan riset mandiri dan berkelanjutan.</p>
                            <p>3. Pengguna memahami bahwa seluruh dana yang ditujukan untuk jual beli (trading) berada di dalam akun/exchange/akun broker masing-masing Pengguna pada Platform Bursa/Pasar, yang hanya dapat ditarik, disetor, dan dikendalikan oleh Pengguna serta tunduk pada syarat dan ketentuan Platform Bursa/Pasar terkait. Dengan demikian, Almai tidak memiliki akses apapun terhadap dana Pengguna yang ditempatkan di Platform Bursa/Pasar. Almai tidak bertanggung jawab dan tidak dapat dimintai pertanggungjawaban atas dana Pengguna yang disetorkan ke atau ditarik dari platform yang bukan milik Almai.</p>
                            <p>4. Semua keputusan dalam penggunaan Aplikasi adalah keputusan yang bersifat sukarela atau independen yang dibuat oleh Pengguna tanpa paksaan dari Almai dan Pengembangnya. Untuk hal ini, Pengguna membebaskan Almai dan Pengembangnya dari segala bentuk klaim, ganti rugi, dan seluruh tanggung jawab dalam bentuk apa pun.</p>
                            <p>5. Pengguna menyatakan bahwa mereka memahami batasan keamanan dan privasi namun tidak terbatas pada:
                            <ol type="i">
                                <li>i.  Batasan pada ukuran dan fitur keamanan, privasi, dan autentikasi dalam layanan</li>
                                <li>ii. Seluruh data dan informasi dalam layanan dapat menyebabkan penyadapan, pemalsuan, spam, sabotase, pembajakan kata sandi, gangguan, penipuan, penyalahgunaan elektronik, peretasan, dan kontaminasi sistem, termasuk namun tidak terbatas pada, virus, worms, dan Trojan horses, yang menyebabkan ketidakabsahan, kerusakan, atau akses berbahaya, dan/atau pemulihan informasi atau data pada komputer Pengguna atau bahaya keamanan dan privasi lainnya. Jika Pengguna tidak ingin menanggung risiko-risiko tersebut, Pengguna disarankan untuk tidak menggunakan Aplikasi atau Layanan kami.</li>
                            </ol>
                            <p>6.   Sepanjang diizinkan oleh hukum yang berlaku, Almai (termasuk Perusahaan Induk, direktur, dan karyawan) tidak bertanggung jawab, dan Pengguna setuju untuk tidak menuntut pertanggungjawaban Almai, atas kerusakan atau kerugian apa pun (termasuk namun tidak terbatas pada hilangnya uang, reputasi, keuntungan, atau kerugian tak berwujud lainnya) yang secara langsung atau tidak langsung disebabkan oleh hal-hal berikut:</p>
                             <ul class="list-disc list-outside pl-5 space-y-2">
                                <li>Ketidakmampuan Pengguna dalam menggunakan layanan Almai yang merupakan risiko pribadi.</li>
                                <li>Kehilangan Penggunaan, Kehilangan Keuntungan, Kehilangan Pendapatan, Kehilangan Data, Kehilangan Goodwill, atau Kegagalan untuk merealisasikan simpanan yang diharapkan, untuk kasus apa pun secara langsung atau tidak langsung.</li>
                                <li>Setiap kerugian tidak langsung, insidental, khusus, atau konsekuensial, yang timbul dari atau sehubungan dengan penggunaan atau ketidakmampuan untuk menggunakan situs web atau Layanan Almai termasuk, namun tidak terbatas pada, kerugian apa pun yang disebabkan olehnya, bahkan jika Almai telah diberitahu tentang kemungkinan kerugian tersebut.</li>
                                <li>Setiap kerugian yang disebabkan oleh kelalaian Pengguna termasuk namun tidak terbatas pada kelalaian dalam masuk (login) melalui perangkat pihak ketiga dan/atau kegagalan untuk menjaga kerahasiaan perangkat yang digunakan untuk masuk.</li>
                                <li>Kondisi dan kualitas produk atau Aset Kripto yang diperdagangkan di Platform Bursa/Pasar dan dipilih oleh Pengguna untuk dijalankan strateginya melalui Almai.</li>
                                <li>Pelanggaran Hak Kekayaan Intelektual.</li>
                                <li>Perselisihan antar Pengguna.</li>
                                <li>Pencemaran nama baik pihak lain.</li>
                                <li>Kerugian akibat pembayaran tidak resmi kepada pihak lain selain ke Rekening Resmi Almai yang, dengan cara apa pun, menggunakan nama Almai atau kelalaian dalam penulisan rekening dan/atau informasi lainnya dan/atau kelalaian di pihak bank.</li>
                                <li>Kesesuaian untuk tujuan tertentu, daya tahan, hak milik, dan non-pelanggaran.</li>
                                <li>Virus atau perangkat lunak berbahaya lainnya (bot, script, automation tools) yang diperoleh dengan mengakses atau menghubungkan ke Layanan.</li>
                                <li>Proses skimming atau peretasan, yang menyebabkan kerugian bagi Pengguna dalam Layanan Almai.</li>
                                <li>Setiap gangguan, bug, kesalahan, atau ketidakakuratan dalam Layanan.</li>
                                <li>Kerusakan pada perangkat keras dan/atau perangkat lunak Anda dari penggunaan Layanan apa pun.</li>
                                <li>Tindakan penegakan yang diambil sehubungan dengan akun Pengguna.</li>
                                <li>Setiap peretasan yang dilakukan oleh pihak ketiga terhadap akun Pengguna.</li>
                             </ul>
                             <p>Pengguna mengakui dan setuju bahwa satu-satunya hak yang dimiliki Pengguna sehubungan dengan masalah atau ketidakpuasan Layanan adalah berhenti menggunakan Layanan tersebut.</p>
                            </p>
                        </div>
                    </section>

                       <!-- 11-13. Pernyataan Risiko-->
                    <section class="space-y-6">
                        <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="w-1.5 h-8 bg-accent rounded-full"></span>
                            Pernyataan Risiko
                        </h2>
                        <div class="text-gray-300 text-sm space-y-4 leading-relaxed text-justify">
                            <p>Pemberian Nasihat Perdagangan Berjangka pada transaksi produk Perdagangan Berjangka yakni Kontrak Derivatif, Kontrak Derivatif Syariah, Kontrak Derivatif lainnya, Aset Digital dan/atau penyelenggaraan pasar fisik Komoditi di Bursa Berjangka adalah aktivitas berisiko tinggi dan selalu memiliki kemungkinan kerugian maupun keuntungan.  Anda harus membaca dengan seksama dan memahami Dokumen Pemberitahuan Adanya Risiko. Dengan menyetujui Syarat dan Ketentuan ini, Anda dianggap telah pula menyetujui ketentuan yang terdapat dalam Dokumen Pernyataan Adanya Risiko.</p>
                    </section>

                      <!-- 11-13. Informasi Rahasia-->
                    <section class="space-y-6">
                        <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="w-1.5 h-8 bg-accent rounded-full"></span>
                            Informasi Rahasia
                        </h2>
                        <div class="text-gray-300 text-sm space-y-4 leading-relaxed text-justify">
                            <p>1. Pengguna setuju dan berjanji kapan pun untuk menjaga kerahasiaan setiap Informasi Rahasia yang diperoleh sebagai pelaksanaan kerja sama dengan siapa pun, atau tidak akan menggunakannya untuk kepentingan Pengguna atau kepentingan pihak lain, tanpa terlebih dahulu mendapatkan persetujuan tertulis dari pejabat berwenang dari Almai atau pihak berwenang lainnya sesuai dengan ketentuan hukum yang berlaku.</p>
                            <p>2. Kewajiban untuk menjaga kerahasiaan informasi sebagaimana dimaksud dalam angka 1 dan 2 mengenai kerahasiaan menjadi tidak berlaku apabila:
                                <ol class="list-[lower-alpha] pl-6">
                                    <li>a.  Informasi rahasia tersebut telah tersedia untuk masyarakat umum.</li>
                                    <li>b.  Informasi rahasia diperintahkan untuk dibuka guna memenuhi perintah pengadilan atau badan pemerintah berwenang lainnya.</li>
                                    <li>c.  Informasi rahasia diberikan sesuai dengan ketentuan hukum yang berlaku.</li>
                                </ol>
                            <p>3.   Apabila Pengguna melanggar ketentuan mengenai kerahasiaan ini, maka seluruh kerugian, tuntutan, dan/atau gugatan yang dialami oleh Almai sepenuhnya menjadi tanggung jawab Pengguna, dan atas permintaan dari Almai, Pengguna wajib menyelesaikannya sesuai dengan ketentuan hukum dan perundang-undangan yang berlaku serta memberikan ganti rugi yang mungkin timbul akibat pelanggaran tersebut kepada Almai. Pengguna dengan ini setuju bahwa Almai tidak akan memberikan bentuk kompensasi dan/atau pertanggungjawaban apa pun kepada Pengguna atau pihak mana pun atas klaim dan/atau gugatan yang mungkin timbul di kemudian hari sehubungan dengan pelanggaran tersebut.</p>
                           
                    </section>

                    <!-- 11-13. Cidera Janji (Default)-->
                    <section class="space-y-6">
                        <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="w-1.5 h-8 bg-accent rounded-full"></span>
                            Cidera Janji (Default)
                        </h2>
                        <div class="text-gray-300 text-sm space-y-4 leading-relaxed text-justify">
                            <p>Pengguna dianggap telah melakukan Cidera Janji (Default) apabila terjadi satu atau lebih peristiwa berikut:
                                <ol>
                                    <li>1. <b>Kelalaian :</b> Pengguna lalai, kurang hati-hati, atau gagal dalam melaksanakan kewajiban, janji, atau ketentuan apa pun yang diatur dalam Syarat dan Ketentuan  ini</li>
                                    <li>2. <b>Pelanggaran Aktif : </b> Pengguna melakukan tindakan yang dilarang atau melanggar batasan-batasan penggunaan layanan Almai, atau</li>
                                    <li>3. <b>Pernyataan Tidak Benar :</b> Ditemukannya ketidaksesuaian antara pernyataan atau jaminan yang diberikan Pengguna dengan fakta yang sebenarnya.</li>
                                </ol>
                            <p>Dalam hal terjadi Cidera Janji oleh Pengguna, maka Almai dapat memilih untuk melanjutkan atau menutup Akun Pengguna. Jika Almai hendak menutup Akun Pengguna, maka Almai dapat memberikan pemberitahuan terlebih dahulu kepada Pengguna dalam waktu yang wajar menurut Almai. Pengguna dengan ini setuju bahwa Almai tidak akan memberikan ganti rugi dan/atau pertanggungjawaban dalam bentuk apa pun kepada Pengguna atau pihak mana pun atas segala tuntutan, klaim, gugatan, dan/atau permintaan ganti rugi dari pihak-pihak yang mungkin timbul sehubungan dengan terjadinya kelalaian tersebut.</p>
                    </section>

                    <!-- 11-13. Penangguhan, Pengakhiran, dan Pembatalan -->
                    <section class="space-y-6">
                        <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="w-1.5 h-8 bg-accent rounded-full"></span>
                           Penangguhan, Pengakhiran, dan Pembatalan
                        </h2>
                        <div class="text-gray-300 text-sm space-y-4 leading-relaxed text-justify">
                            <p> Almai berhak menangguhkan atau menutup akses Akun baik dengan pemberitahuan terlebih dahulu ataupun secara sepihak jika:
                                <ol>
                                    <li>1.  Diperlukan oleh hukum/perintah pengadilan.</li>
                                    <li>2.  Terjadi pelanggaran Syarat dan Ketentuan, Kebijakan Privasi, atau Kode Etik.</li>
                                    <li>3.  Ditemukan aktivitas mencurigakan, penipuan, pencucian uang, pendanaan terorisme, dan/atau kejahatan lainnya.</li>
                                    <li>4.  Terjadi penyalahgunaan promosi atau pembuatan akun ganda. Keputusan pembatasan akses dapat didasarkan pada kriteria manajemen risiko rahasia Almai.</li>
                                </ol>
                            </p>
                              </div>
                     </section>

                    <!-- 11-13. Hak Kekayaan Intelektual -->
                     <section class="space-y-6">
                        <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="w-1.5 h-8 bg-accent rounded-full"></span>
                            Hak Kekayaan Intelektual
                        </h2>
                        <div class="text-gray-300 text-sm space-y-4 leading-relaxed text-justify">
                          <p>Pengguna menyatakan dan setuju bahwa Almai adalah pemegang hak kepemilikan atas layanan, perangkat lunak, alat teknologi, konten, situs, dan bahan lain termasuk Hak Kekayaan Intelektual yang terkait dengan Layanan Almai. Tidak ada satu pun ketentuan dalam Syarat dan Ketentuan ini yang dapat ditafsirkan sebagai pengalihan hak kepemilikan tersebut kepada Pengguna.</p>
                          <p>Pengguna hanya diperbolehkan untuk melihat, mencetak dan/atau mengunduh salinan material dari Situs Almai untuk penggunaan pribadi dan non-komersial. Seluruh penggunaan komersial perlu mendapatkan izin dari Almai. Setiap kegiatan komersial tanpa seizin Almai diartikan sebagai pelanggaran atas Hak Kekayaan Intelektual Almai dan dapat mengakibatkan pemberhentian Akun Almai pada Pelanggan.</p>

                        </div>
                     </section>

                     <!-- 11-13. Pernyataan Pajak -->
                     <section class="space-y-6">
                        <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="w-1.5 h-8 bg-accent rounded-full"></span>
                            Pernyataan Pajak
                        </h2>
                        <div class="text-gray-300 text-sm space-y-4 leading-relaxed text-justify">
                          <p>Pajak atas segala aktivitas penggunaan Layanan yang disediakan oleh Almai adalah pajak yang ditanggung oleh masing-masing Pihak, dalam hal ini Almai dan Anda, sesuai bagiannya masing-masing, sesuai dengan hukum dan peraturan yang berlaku (terutama hukum dan peraturan yang berkaitan dengan perpajakan). Almai tidak menanggung pajak Pelanggan kecuali ditentukan lain dalam Syarat dan Ketentuan ini. </p>
                        </div>
                     </section>

                     <!-- 11-13. Keamanan -->
                     <section class="space-y-6">
                        <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="w-1.5 h-8 bg-accent rounded-full"></span>
                            Keamanan
                        </h2>
                        <div class="text-gray-300 text-sm space-y-4 leading-relaxed text-justify">
                          <p>Almai telah menerapkan standar keamanan sistem sesuai peraturan perundang-undangan untuk mencegah akses tanpa hak. Segala risiko atas akses, penggunaan, atau perubahan akun oleh pihak ketiga yang timbul akibat kelalaian atau kesalahan Anda menjadi tanggung jawab penuh Anda. Oleh karenanya, Almai dibebaskan dari segala tuntutan ganti rugi dan pertanggungjawaban hukum dalam bentuk apa pun atas risiko yang timbul dari kelalaian tersebut.</p>
                        </div>
                     </section>

                      <!-- 11-13. Keadaan Kahar -->
                     <section class="space-y-6">
                        <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="w-1.5 h-8 bg-accent rounded-full"></span>
                            Keadaan Kahar
                        </h2>
                        <div class="text-gray-300 text-sm space-y-4 leading-relaxed text-justify">
                          <p>Yang dimaksud dengan Keadaan Kahar adalah kejadian-kejadian yang terjadi di luar kemampuan dan kekuasaan Almai sehingga mempengaruhi pelaksanaan transaksi antara lain namun tidak terbatas pada bencana alam banjir, tanah longsor, kebakaran dan hal-hal lain yang berada di luar kuasa manusia, termasuk tetapi tidak terbatas pada kebijakan-kebijakan pemerintah di bidang moneter, aksi mogok kerja, peperangan, terorisme, pandemi, epidemi dan huru-hara. Almai dibebaskan dari tanggung jawab atas kegagalan dan/atau tertundanya kewajiban dan/atau Layanan yang disebabkan oleh Keadaan Kahar  dan terlepas dari tanggung gugat yang seharusnya mungkin dapat dibebankan atasnya.</p>
                        </div>
                     </section>

                      <!-- 11-13. Lain-lain -->
                     <section class="space-y-6">
                        <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                            <span class="w-1.5 h-8 bg-accent rounded-full"></span>
                            Lain-lain
                        </h2>
                        <div class="text-gray-300 text-sm space-y-4 leading-relaxed text-justify">
                          <ul>
                            <li>1.  Syarat dan Ketentuan ini dioperasikan dan dikendalikan oleh Almai dari kantornya di Indonesia. Jika ada ketentuan yang dianggap tidak dapat dilaksanakan oleh pengadilan, sisa ketentuan lainnya akan tetap berlaku sepenuhnya. Segala tindakan hukum yang timbul dari Layanan harus dimulai dalam waktu satu (1) tahun setelah penyebab tindakan tersebut muncul, jika tidak, maka hak tersebut dianggap gugur secara permanen. </li>
                            <li>2.  Pengguna setuju untuk berkomunikasi dengan Almai dalam format elektronik, dan setuju bahwa seluruh syarat, ketentuan, perjanjian, pemberitahuan, pengungkapan, atau bentuk komunikasi lainnya yang diberikan oleh Almai kepada Pengguna secara elektronik dianggap sah secara hukum sebagai dokumen tertulis.</li>
                            <li>3.  Pengguna setuju untuk menerima e-mail dari Almai. E-mail yang dikirimkan dapat berisi informasi mengenai Akun, transaksi, sistem, promosi, dan lain sebagainya. Pengguna dapat berhenti berlangganan (unsubscribe) untuk email dari Almai yang bersifat promosi.</li>
                            <li>4.  Setiap konflik, perselisihan, atau perbedaan pendapat (selanjutnya disebut sebagai “Perselisihan”) yang timbul sehubungan dengan Syarat dan Ketentuan, Kebijakan Privasi, dan Layanan, sejauh mungkin akan diselesaikan secara musyawarah mufakat.</li>
                            <li>5.  Setiap Perselisihan yang tidak dapat diselesaikan melalui musyawarah dalam waktu 30 (tiga puluh) hari kerja setelah pemberitahuan masalah kepada seluruh pihak terkait, maka masing-masing Pihak dapat, melalui pemberitahuan tertulis kepada Pihak lainnya, mengajukan masalah tersebut untuk penyelesaian akhir melalui arbitrase di Indonesia sesuai dengan aturan Badan Arbitrase Nasional Indonesia (“BANI”). Tempat arbitrase adalah di Denpasar, Indonesia, kecuali para Pihak menyetujui secara tertulis mengenai tempat arbitrase lainnya. Jumlah arbiter adalah 1 (satu) orang (tunggal) yang disetujui oleh para Pihak, atau jika tidak tercapai kesepakatan, salah satu Pihak dapat meminta Ketua Pengadilan Negeri Denpasar untuk menunjuk arbiter tunggal atas permohonan tersebut. Sidang arbitrase akan dilakukan dalam Bahasa Indonesia. Hukum yang berlaku dalam perjanjian arbitrase ini adalah hukum negara Republik Indonesia. Seluruh biaya yang timbul akibat Perselisihan menjadi tanggungjawab masing-masing Pihak, Almai tidak dapat dibebankan biaya perkara atau biaya-biaya lain yang timbul dari Perselisihan, baik yang dibayarkan atau ditagihkan kepada Pengguna.</li>
                            <li>6.  Syarat dan Ketentuan ini tunduk pada dan diberlakukan berdasarkan <b>Undang-Undang Nomor 8 Tahun 2010 tentang Pencegahan dan Pemberantasan Tindak Pidana Pencucian Uang dan Undang-Undang Nomor 9 Tahun 2013 tentang Pencegahan dan Pemberantasan Tindak Pidana Pendanaan Terorisme.</b></li>
                            <li>7.  Untuk hal-hal yang belum diatur dalam Syarat dan Ketentuan ini, akan berlaku seluruh ketentuan dalam <b>Kitab Undang-Undang Hukum Perdata (KUHPerdata) serta ketentuan peraturan perundang-undangan lain yang relevan.</b></li>
                            <li>8.  Apabila Almai mengubah isi Syarat dan Ketentuan ini, maka Almai akan memberitahukan perubahan tersebut kepada Pengguna sesuai dengan peraturan perundang-undangan yang berlaku melalui media pemberitahuan yang dianggap baik oleh Almai, dan selanjutnya Pengguna akan tunduk pada perubahan Syarat dan Ketentuan tersebut. Perubahan pada setiap lampiran dari Syarat dan Ketentuan akan disepakati dan selanjutnya merupakan satu kesatuan serta bagian yang tidak terpisahkan dari Syarat dan Ketentuan.</li>
                            <li>9.  Apabila Pengguna melakukan tindakan di luar ketentuan Syarat dan Ketentuan ini, maka Pengguna bertanggung jawab sepenuhnya dan dengan ini setuju bahwa Almai tidak akan memberikan ganti rugi dan/atau pertanggungjawaban dalam bentuk apa pun kepada Pengguna, atau pihak mana pun atas segala tuntutan dan/atau klaim dan/atau gugatan yang diajukan oleh pihak lain sehubungan dengan tindakan yang dilakukan oleh Pengguna tersebut.</li>
                            <li>10. Pengguna wajib mematuhi seluruh persyaratan yang tercantum dalam Syarat dan Ketentuan ini. Kelalaian Pengguna untuk mematuhi atau melaksanakan isi Syarat dan Ketentuan pada satu atau beberapa kesempatan tidak akan menghapuskan kewajiban Pengguna untuk memenuhi seluruh persyaratan yang terkandung dalam Syarat dan Ketentuan.</li>
                          </ul></b>
                        </div>
                     </section>

                    <!-- 14-22. Final Sections -->
                    <section class="space-y-6">
                        <div class="text-gray-300 text-sm leading-relaxed space-y-4 text-justify">
                            <p>Setiap pertanyaan atau hal-hal lain mengenai Syarat dan Ketentuan ini harus disampaikan melalui cs@almai.id.</p>
                    </section>


                </div>
            <?php endif; ?>
        </div>

        <!-- Footer Action -->
        <div class="text-center mt-12">
            <a href="<?= base_url('register') ?>" class="inline-block px-8 py-3 bg-accent text-black font-bold rounded-full hover:bg-white transition shadow-lg shadow-accent/20">
                Setuju & Daftar Sekarang
            </a>
            <p class="mt-4 text-gray-300 text-sm">Dengan mendaftar, Anda menyetujui Syarat & Ketentuan di atas.</p>
        </div>
    </div>
</div>
<?= $this->endSection() ?>