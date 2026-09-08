<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="pt-32 pb-16 min-h-screen flex items-center">
    <div class="container mx-auto px-6 max-w-lg text-center">
        <div class="bg-[#111] border border-white/10 rounded-2xl p-8">
            <div class="w-20 h-20 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-user-check text-blue-400 text-3xl"></i>
            </div>
            <h1 class="text-2xl font-bold mb-4">Link Sudah Digunakan</h1>
            <p class="text-gray-400 mb-6">Link berbagi poin ini sudah diklaim oleh user lain.</p>
            <a href="<?= base_url('/') ?>" class="inline-block px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                <i class="fas fa-home mr-2"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
