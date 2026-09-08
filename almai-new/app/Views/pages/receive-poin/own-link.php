<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="pt-32 pb-16 min-h-screen flex items-center">
    <div class="container mx-auto px-6 max-w-lg text-center">
        <div class="bg-[#111] border border-white/10 rounded-2xl p-8">
            <div class="w-20 h-20 bg-accent/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-user text-accent text-3xl"></i>
            </div>
            <h1 class="text-2xl font-bold mb-4">Ini Link Milik Anda</h1>
            <p class="text-gray-400 mb-6">Anda tidak bisa mengklaim link berbagi poin milik sendiri. Bagikan link ini ke user lain.</p>
            <div class="flex gap-3 justify-center">
                <a href="<?= base_url('user/poin/share') ?>" class="px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                    <i class="fas fa-share-alt mr-2"></i> Kelola Link
                </a>
                <a href="<?= base_url('/') ?>" class="px-6 py-3 bg-white/10 rounded-xl hover:bg-white/20 transition">
                    <i class="fas fa-home mr-2"></i> Beranda
                </a>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
