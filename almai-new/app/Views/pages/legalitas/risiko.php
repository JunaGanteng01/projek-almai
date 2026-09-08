<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="pt-24 md:pt-32 pb-16 min-h-screen">
    <div class="container mx-auto px-4 md:px-6 max-w-4xl">
        <!-- Header -->
        <div class="text-center mb-12">
            <span class="text-accent font-bold tracking-widest uppercase text-sm mb-4 block">Dokumen Legal</span>
            <h1 class="text-3xl md:text-5xl font-black text-white mb-4 leading-tight">
                DOKUMEN PEMBERITAHUAN<br>ADANYA RISIKO
            </h1>
            <p class="text-gray-400">Perdagangan Berjangka Komoditi</p>
        </div>

        <!-- Content -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 md:p-8 space-y-6">
            <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-6 mb-6">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-triangle text-red-500 text-2xl mt-1"></i>
                    <div>
                        <h3 class="text-xl font-bold text-red-400 mb-2">PERINGATAN PENTING</h3>
                        <p class="text-gray-400 leading-relaxed">
                            Perdagangan berjangka komoditi mengandung risiko tinggi dan tidak cocok untuk semua investor. 
                            Anda dapat kehilangan seluruh modal yang diinvestasikan.
                        </p>
                    </div>
                </div>
            </div>

            <div class="prose prose-invert max-w-none">
                <h2 class="text-xl font-bold text-white mb-4">1. RISIKO UMUM</h2>
                <p class="text-gray-400 leading-relaxed mb-4">
                    Perdagangan berjangka komoditi memiliki risiko yang harus dipahami:
                </p>
                <ul class="list-disc list-inside text-gray-400 space-y-2 ml-4">
                    <li><strong class="text-white">Risiko Kerugian Modal</strong>: Anda dapat kehilangan sebagian atau seluruh modal investasi</li>
                    <li><strong class="text-white">Volatilitas Pasar</strong>: Harga dapat berubah dengan cepat dan tidak terduga</li>
                    <li><strong class="text-white">Leverage</strong>: Penggunaan leverage dapat memperbesar keuntungan maupun kerugian</li>
                    <li><strong class="text-white">Likuiditas</strong>: Tidak semua posisi dapat ditutup dengan mudah</li>
                </ul>

                <h2 class="text-xl font-bold text-white mb-4 mt-8">2. RISIKO TEKNOLOGI</h2>
                <ul class="list-disc list-inside text-gray-400 space-y-2 ml-4">
                    <li>Gangguan sistem atau koneksi internet dapat mempengaruhi eksekusi order</li>
                    <li>Expert Advisor (EA) dapat mengalami error atau tidak berfungsi optimal</li>
                    <li>Keamanan akun trading adalah tanggung jawab pengguna</li>
                </ul>

                <h2 class="text-xl font-bold text-white mb-4 mt-8">3. RISIKO PASAR</h2>
                <ul class="list-disc list-inside text-gray-400 space-y-2 ml-4">
                    <li>Pergerakan harga yang tidak sesuai prediksi atau analisis</li>
                    <li>Gap harga saat pembukaan pasar</li>
                    <li>Slippage pada saat eksekusi order</li>
                    <li>Kondisi pasar yang tidak normal (force majeure)</li>
                </ul>

                <h2 class="text-xl font-bold text-white mb-4 mt-8">4. TIDAK ADA JAMINAN PROFIT</h2>
                <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-xl p-4">
                    <p class="text-gray-400 leading-relaxed">
                        <strong class="text-white">PENTING:</strong> Tidak ada strategi, sistem, atau EA yang dapat menjamin keuntungan. 
                        Performa masa lalu tidak menjamin hasil di masa depan. Semua layanan yang diberikan 
                        bersifat edukatif dan konsultatif, bukan jaminan profit.
                    </p>
                </div>

                <h2 class="text-xl font-bold text-white mb-4 mt-8">5. TANGGUNG JAWAB INVESTOR</h2>
                <p class="text-gray-400 leading-relaxed mb-4">
                    Sebagai investor, Anda bertanggung jawab untuk:
                </p>
                <ul class="list-disc list-inside text-gray-400 space-y-2 ml-4">
                    <li>Memahami sepenuhnya risiko yang ada</li>
                    <li>Hanya menggunakan dana yang siap untuk hilang</li>
                    <li>Membuat keputusan trading sendiri</li>
                    <li>Mengelola risiko dengan baik (stop loss, position sizing, dll)</li>
                    <li>Terus belajar dan meningkatkan pengetahuan</li>
                </ul>

                <h2 class="text-xl font-bold text-white mb-4 mt-8">6. BATASAN TANGGUNG JAWAB ALMAI</h2>
                <ul class="list-disc list-inside text-gray-400 space-y-2 ml-4">
                    <li>ALMAI tidak bertanggung jawab atas kerugian trading yang dialami</li>
                    <li>ALMAI tidak menjamin hasil atau performa tertentu</li>
                    <li>Semua keputusan trading adalah tanggung jawab penuh investor</li>
                    <li>ALMAI hanya menyediakan edukasi, tools, dan konsultasi</li>
                </ul>

                <h2 class="text-xl font-bold text-white mb-4 mt-8">7. REKOMENDASI</h2>
                <div class="bg-blue-500/10 border border-blue-500/30 rounded-xl p-4">
                    <ul class="list-disc list-inside text-gray-400 space-y-2 ml-4">
                        <li>Pelajari dengan baik sebelum memulai trading</li>
                        <li>Gunakan akun demo terlebih dahulu</li>
                        <li>Mulai dengan modal kecil</li>
                        <li>Jangan pernah trading dengan uang pinjaman</li>
                        <li>Selalu gunakan manajemen risiko yang ketat</li>
                        <li>Konsultasikan dengan penasihat keuangan jika perlu</li>
                    </ul>
                </div>

                <div class="mt-8 pt-6 border-t border-white/10">
                    <p class="text-gray-500 text-sm text-center">
                        Dengan melakukan pembayaran dan menggunakan layanan ALMAI, Anda menyatakan telah membaca, 
                        memahami, dan menerima seluruh risiko yang dijelaskan dalam dokumen ini.
                    </p>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="text-center mt-8">
            <a href="<?= base_url('legalitas') ?>" class="inline-flex items-center gap-2 text-accent hover:text-white transition">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Legalitas</span>
            </a>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
