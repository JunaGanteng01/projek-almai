<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-[60vh] flex items-center justify-center py-12 px-4">
    <div class="w-full max-w-md bg-[#111] border border-red-500/20 rounded-2xl p-8 text-center relative overflow-hidden">
        <div class="w-20 h-20 bg-red-500/10 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-times-circle text-red-500 text-4xl"></i>
        </div>

        <h1 class="text-2xl font-bold mb-2"><?= esc($title) ?></h1>
        <p class="text-gray-400 mb-8"><?= esc($message) ?></p>

        <a href="<?= base_url('user/dashboard') ?>" class="inline-flex items-center justify-center w-full py-3 bg-white/5 hover:bg-white/10 text-white font-bold rounded-xl transition">
            Kembali ke Dashboard
        </a>
    </div>
</div>

<?= $this->endSection() ?>
