<?= $this->extend('keuangan/layouts/main') ?>
<?= $this->section('content') ?>
<?php $e = $entitas ?? []; ?>

<div class="mb-6">
    <h1 class="text-2xl font-bold">Profil Entitas</h1>
    <p class="text-gray-500 text-xs mt-1">Data perusahaan untuk header laporan keuangan (neraca, laba rugi, SAK ETAP)</p>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="bg-accent/10 border border-accent/20 text-accent p-4 rounded-xl mb-6"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<form action="<?= base_url('keuangan/entitas/save') ?>" method="post" class="bg-[#111] border border-white/10 rounded-2xl p-6 max-w-3xl space-y-4">
    <?= csrf_field() ?>
    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <label class="text-xs text-gray-500 uppercase font-bold">Nama Entitas *</label>
            <input name="nama_entitas" required value="<?= esc($e['nama_entitas'] ?? '') ?>" class="w-full mt-1 bg-black border border-white/10 rounded-xl px-4 py-3 text-white text-sm">
        </div>
        <div>
            <label class="text-xs text-gray-500 uppercase font-bold">Nama Pendek</label>
            <input name="nama_pendek" value="<?= esc($e['nama_pendek'] ?? '') ?>" class="w-full mt-1 bg-black border border-white/10 rounded-xl px-4 py-3 text-white text-sm">
        </div>
        <div>
            <label class="text-xs text-gray-500 uppercase font-bold">NPWP</label>
            <input name="npwp" value="<?= esc($e['npwp'] ?? '') ?>" class="w-full mt-1 bg-black border border-white/10 rounded-xl px-4 py-3 text-white text-sm">
        </div>
        <div>
            <label class="text-xs text-gray-500 uppercase font-bold">Mata Uang</label>
            <input name="mata_uang" value="<?= esc($e['mata_uang'] ?? 'IDR') ?>" class="w-full mt-1 bg-black border border-white/10 rounded-xl px-4 py-3 text-white text-sm">
        </div>
        <div>
            <label class="text-xs text-gray-500 uppercase font-bold">Telepon</label>
            <input name="telepon" value="<?= esc($e['telepon'] ?? '') ?>" class="w-full mt-1 bg-black border border-white/10 rounded-xl px-4 py-3 text-white text-sm">
        </div>
        <div>
            <label class="text-xs text-gray-500 uppercase font-bold">Email</label>
            <input name="email" type="email" value="<?= esc($e['email'] ?? '') ?>" class="w-full mt-1 bg-black border border-white/10 rounded-xl px-4 py-3 text-white text-sm">
        </div>
        <div>
            <label class="text-xs text-gray-500 uppercase font-bold">Kota</label>
            <input name="kota" value="<?= esc($e['kota'] ?? '') ?>" class="w-full mt-1 bg-black border border-white/10 rounded-xl px-4 py-3 text-white text-sm">
        </div>
        <div>
            <label class="text-xs text-gray-500 uppercase font-bold">Bulan Awal Fiskal</label>
            <select name="bulan_awal_fiskal" class="w-full mt-1 bg-black border border-white/10 rounded-xl px-4 py-3 text-white text-sm">
                <?php for ($m = 1; $m <= 12; $m++): ?>
                    <option value="<?= $m ?>" <?= (int)($e['bulan_awal_fiskal'] ?? 1) === $m ? 'selected' : '' ?>><?= date('F', mktime(0, 0, 0, $m, 1)) ?></option>
                <?php endfor; ?>
            </select>
        </div>
    </div>
    <div>
        <label class="text-xs text-gray-500 uppercase font-bold">Alamat</label>
        <textarea name="alamat" rows="3" class="w-full mt-1 bg-black border border-white/10 rounded-xl px-4 py-3 text-white text-sm"><?= esc($e['alamat'] ?? '') ?></textarea>
    </div>
    <div>
        <label class="text-xs text-gray-500 uppercase font-bold">Catatan Laporan (footer)</label>
        <textarea name="catatan_laporan" rows="2" class="w-full mt-1 bg-black border border-white/10 rounded-xl px-4 py-3 text-white text-sm"><?= esc($e['catatan_laporan'] ?? '') ?></textarea>
    </div>
    <button type="submit" class="px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">Simpan Profil</button>
</form>
<?= $this->endSection() ?>


