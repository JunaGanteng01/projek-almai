<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="pt-32 pb-20 bg-dark-bg min-h-screen">
    <div class="container mx-auto px-6 max-w-4xl">
        <!-- Header -->
        <div class="text-center mb-16" data-aos="fade-up">
            <h1 class="text-3xl md:text-5xl font-bold mb-4 bg-clip-text text-transparent bg-gradient-to-r from-white to-gray-400">
                KEBIJAKAN PRIVASI
            </h1>
            <p class="text-xl text-accent font-medium tracking-widest uppercase">
                PT. ALMA INDONESIA RAYA
            </p>
        </div>

        <!-- Document Content -->
        <div class="bg-[#111] border border-white/10 rounded-3xl p-8 md:p-12 shadow-2xl space-y-12" data-aos="fade-up" data-aos-delay="100">
            <?php if (!empty($privacy)): ?>
                <div class="prose prose-invert max-w-none text-gray-300">
                    <?= $privacy ?>
                </div>
            <?php else: ?>
                <!-- Default Content -->
                <div class="text-gray-300 leading-relaxed text-justify space-y-6">
                    <p>
                        PT. Alma Indonesia Raya ("Kami" atau "Almai") menghargai privasi Anda dan berkomitmen untuk melindungi data pribadi yang Anda berikan kepada kami. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, mengungkapkan, dan melindungi informasi pribadi Anda saat mengakses dan menggunakan Portal Penasihat Berjangka Almai.id dan layanan terkait ("Layanan").
                    </p>
                    <p>
                        Dengan menggunakan Layanan kami, Anda menyetujui pengumpulan dan penggunaan informasi sesuai dengan kebijakan ini.
                    </p>

                    <div class="w-full h-px bg-white/10 my-8"></div>

                    <!-- 1. Informasi yang Kami Kumpulkan -->
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-white mb-4">1. Informasi yang Kami Kumpulkan</h2>
                        <ul class="list-disc pl-5 space-y-2 text-gray-400">
                            <li><strong>Informasi Identitas Pribadi:</strong> Nama lengkap, alamat email, nomor telepon (WhatsApp), alamat domisili, dan data verifikasi lainnya (KYC) seperti KTP/Paspor untuk kepatuhan regulasi.</li>
                            <li><strong>Data Transaksi:</strong> Riwayat pembelian layanan, bukti transfer, dan status keanggotaan.</li>
                            <li><strong>Data Teknis:</strong> Alamat IP, jenis browser, data log, dan informasi perangkat yang digunakan untuk mengakses layanan demi keamanan dan peningkatan sistem.</li>
                        </ul>
                    </div>

                    <!-- 2. Penggunaan Informasi -->
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-white mb-4">2. Penggunaan Informasi</h2>
                        <p class="mb-2">Kami menggunakan informasi yang dikumpulkan untuk tujuan:</p>
                        <ul class="list-disc pl-5 space-y-2 text-gray-400">
                            <li>Menyediakan, mengoperasikan, dan memelihara layanan kami.</li>
                            <li>Memproses transaksi dan mengelola akun pengguna serta keanggotaan.</li>
                            <li>Memenuhi kewajiban hukum dan regulasi yang berlaku di Indonesia (BAPPEBTI, OJK, dan instansi terkait).</li>
                            <li>Mengirimkan notifikasi, pembaruan layanan, dan materi promosi yang relevan (Anda dapat berhenti berlangganan kapan saja).</li>
                            <li>Mencegah penipuan dan aktivitas ilegal lainnya.</li>
                        </ul>
                    </div>

                    <!-- 3. Perlindungan Data -->
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-white mb-4">3. Perlindungan & Keamanan Data</h2>
                        <p class="mb-4">
                            Kami menerapkan langkah-langkah keamanan teknis dan organisasional yang sesuai untuk melindungi data pribadi Anda dari akses, penggunaan, perubahan, atau pengungkapan yang tidak sah. Data sensitif dilindungi menggunakan enkripsi standar industri.
                        </p>
                        <div class="bg-blue-500/10 border border-blue-500/20 p-5 rounded-xl text-sm text-blue-200">
                            <i class="fas fa-shield-alt mr-2"></i>
                            Meskipun kami berusaha melindungi data Anda, tidak ada metode transmisi melalui internet atau penyimpanan elektronik yang 100% aman. Kami terus meningkatkan standar keamanan kami untuk meminimalkan risiko.
                        </div>
                    </div>

                    <!-- 4. Berbagi Informasi -->
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-white mb-4">4. Pengungkapan kepada Pihak Ketiga</h2>
                        <p>
                            Kami tidak menjual, menyewakan, atau menukar data pribadi Anda kepada pihak ketiga. Kami hanya dapat mengungkapkan informasi Anda kepada:
                        </p>
                        <ul class="list-disc pl-5 space-y-2 text-gray-400 mt-2">
                            <li>Otoritas hukum atau regulator (seperti BAPPEBTI) jika diwajibkan oleh undang-undang.</li>
                            <li>Penyedia layanan pihak ketiga yang bekerja sama dengan kami (misalnya: gateway pembayaran) yang terikat oleh perjanjian kerahasiaan.</li>
                        </ul>
                    </div>

                    <!-- 5. Hak Pengguna -->
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-white mb-4">5. Hak Anda</h2>
                        <p>
                            Anda memiliki hak untuk mengakses, memperbarui, atau meminta penghapusan data pribadi Anda (sesuai ketentuan hukum). Anda dapat mengelola informasi dasar Anda melalui menu Profil di dashboard pengguna atau menghubungi layanan pelanggan kami untuk bantuan lebih lanjut.
                        </p>
                    </div>

                    <!-- 6. Perubahan Kebijakan -->
                    <div>
                        <h2 class="text-xl md:text-2xl font-bold text-white mb-4">6. Perubahan Kebijakan Privasi</h2>
                        <p>
                            Kami dapat memperbarui kebijakan ini dari waktu ke waktu. Setiap perubahan akan diberitahukan melalui halaman ini atau notifikasi email. Kami menyarankan Anda untuk meninjau halaman ini secara berkala.
                        </p>
                    </div>

                    <div class="w-full h-px bg-white/10 my-8"></div>

                    <div class="text-center md:text-left">
                        <p class="text-sm text-gray-500">
                            Terakhir diperbarui: Januari 2026<br>
                            PT. ALMA INDONESIA RAYA
                        </p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Footer Action -->
        <div class="text-center mt-12 pb-12">
            <a href="<?= base_url() ?>" class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
<?= $this->endSection() ?>