<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="pt-32 pb-20 bg-[#050505] min-h-screen relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0 bg-grid z-0 pointer-events-none opacity-20"></div>
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-accent/5 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="container mx-auto px-6 relative z-10">
        
        <!-- Hero Section -->
        <div class="text-center max-w-4xl mx-auto mb-20" data-aos="fade-up">
            <span class="text-accent font-bold tracking-widest uppercase text-sm mb-4 block">PENDAMPINGAN</span>
            <h1 class="text-3xl md:text-6xl font-black text-white mb-6 leading-tight">
                CALON
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-emerald-500"> WPA (CWPA)</span>
            </h1>
            <p class="text-gray-300 text-lg md:text-xl leading-relaxed mb-8">
                Jadilah Wakil Penasihat Berjangka yang berkompeten dan terakreditasi. Dapatkan bimbingan intensif dan materi komprehensif bersama mentor berpengalaman.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?= base_url('daftar-wpa') ?>" class="px-8 py-4 bg-accent text-black font-bold rounded-full hover:shadow-[0_0_20px_rgba(51,232,24,0.4)] transition-all flex items-center justify-center gap-2">
                    
                    <span>Daftar Sekarang</span>
                </a>

            </div>
        </div>



        <!-- CWPA List Section -->
        <div class="mb-24">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl font-bold mb-4">Daftar Peserta CWPA</h2>
                <div class="w-24 h-1 bg-gradient-to-r from-accent to-emerald-500 mx-auto rounded-full mb-4"></div>
                <p class="text-gray-400">Trader profesional yang sedang menempuh tahap sertifikasi, <br>Ikuti profil CWPA ini untuk mendapatkan akses eksklusif ke <strong>Kelas Trading Basic & Software Almai</strong> secara gratis.</p>
            </div>
            
            <?php if (!empty($cwpaList)): ?>
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
                    <?php foreach ($cwpaList as $client): ?>
                        <?= view('partials/cards/cwpa_card', ['client' => $client]) ?>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-12 bg-white/5 rounded-2xl border border-white/10">
                    <i class="fas fa-user-graduate text-4xl text-gray-500 mb-4"></i>
                    <p class="text-gray-400">Belum ada peserta yang ditampilkan saat ini.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- CTA Section -->
        <div class="bg-gradient-to-r from-accent to-emerald-600 rounded-3xl p-12 text-center text-black relative overflow-hidden" data-aos="zoom-in">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            <div class="relative z-10">
                <h2 class="text-3xl md:text-4xl font-black mb-6">SIAP MENJADI WPA PROFESIONAL?</h2>
                <p class="text-xl mb-8 max-w-2xl mx-auto font-medium opacity-90">Bergabunglah dengan program bimbingan kami dan tingkatkan peluang kelulusan Anda dalam ujian sertifikasi.</p>
                <a href="<?= base_url('daftar-wpa') ?>" class="inline-block bg-black text-white px-10 py-4 rounded-full font-bold hover:scale-105 transition-transform shadow-xl">
                    Daftar Sekarang
                </a>
            </div>
        </div>

    </div>
</div>
<?= $this->endSection() ?>
