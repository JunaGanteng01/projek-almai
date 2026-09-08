<?= $this->extend('keuangan/layouts/main') ?>
<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white"><?= $pembelian ? 'Edit Faktur Pembelian' : 'Faktur Pembelian Baru' ?></h1>
        <p class="text-gray-500 text-xs mt-1">Buat tagihan pembelian — terintegrasi jurnal double-entry</p>
    </div>
    <a href="<?= base_url('keuangan/pembelian') ?>" class="px-4 py-2 bg-white/10 text-gray-400 font-bold rounded-xl hover:bg-white/20 transition flex items-center gap-2">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<?php if (session()->getFlashdata('error')): ?>
    <div class="bg-red-500/10 border border-red-500/30 text-red-400 p-4 rounded-xl mb-4"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

<?= view('keuangan/shared/faktur_form', [
    'mode' => 'pembelian',
    'record' => $pembelian,
    'kontakList' => $kontakList,
    'saveUrl' => base_url('keuangan/pembelian/save'),
    'backUrl' => base_url('keuangan/pembelian'),
    'nomorLabel' => 'Nomor PI',
    'nomorName' => 'nomor_pi',
    'kontakLabel' => 'Pemasok',
]) ?>

<?= $this->endSection() ?>
