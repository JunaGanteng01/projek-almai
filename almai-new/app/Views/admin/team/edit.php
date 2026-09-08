<?php
$pageTitle = 'Edit Anggota Tim';
$pageSubtitle = 'Edit data anggota tim';
?>
<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-3xl">
    <!-- Back Button -->
    <a href="<?= base_url('admin/tim') ?>" class="inline-flex items-center gap-2 text-gray-400 hover:text-white mb-6 transition">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali ke Daftar Tim</span>
    </a>

    <!-- Form Card -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 md:p-8">
        <form action="<?= base_url('admin/tim/update/' . $team['id']) ?>" method="post" enctype="multipart/form-data" class="space-y-6">
            <?= csrf_field() ?>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                        <div>
                            <h4 class="text-white font-medium mb-1">Gagal mengupdate data</h4>
                            <ul class="text-sm text-red-400 list-disc list-inside">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Order Number -->
            <div>
                <label for="order_number" class="block text-sm font-medium text-gray-400 mb-2">
                    No. Urut <span class="text-red-500">*</span>
                </label>
                <input type="number" id="order_number" name="order_number" value="<?= old('order_number', $team['order_number']) ?>"
                    class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-white transition placeholder-gray-600"
                    placeholder="Contoh: 1, 2, 3..." required>
                <p class="mt-2 text-xs text-gray-500">Semakin kecil angkanya, semakin di depan posisinya.</p>
            </div>

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-400 mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" name="name" value="<?= old('name', $team['name']) ?>"
                    class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-white transition placeholder-gray-600"
                    placeholder="Masukkan nama lengkap" required>
            </div>

            <!-- Role -->
            <div>
                <label for="role" class="block text-sm font-medium text-gray-400 mb-2">
                    Jabatan <span class="text-red-500">*</span>
                </label>
                <input type="text" id="role" name="role" value="<?= old('role', $team['role']) ?>"
                    class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-white transition placeholder-gray-600"
                    placeholder="Contoh: Direktur Utama, Marketing, Staff" required>
            </div>

            <!-- Photo -->
            <div>
                <label for="photo" class="block text-sm font-medium text-gray-400 mb-2">
                    Foto
                </label>
                <div class="flex flex-col md:flex-row gap-4 items-start">
                    <?php if ($team['photo']): ?>
                        <div class="shrink-0">
                            <img src="<?= base_url('file/' . $team['photo']) ?>" alt="Foto saat ini" class="w-24 h-24 object-cover rounded-xl border border-white/10">
                            <p class="text-center text-xs text-gray-500 mt-2">Saat Ini</p>
                        </div>
                    <?php endif; ?>

                    <div class="flex-1 w-full relative group">
                        <input type="file" id="photo" name="photo" accept="image/*"
                            class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-white transition file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-accent file:text-black hover:file:bg-white cursor-pointer">
                        <p class="mt-2 text-xs text-gray-500">Format: JPG, PNG, GIF. Maks: 2MB. Disarankan rasio 1:1 (persegi). Kosongkan jika tidak ingin mengubah foto.</p>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="flex items-center gap-3 pt-2">
                <input type="checkbox" id="is_active" name="is_active" value="1" <?= old('is_active', $team['is_active']) ? 'checked' : '' ?>
                    class="w-5 h-5 bg-black border-white/20 rounded focus:ring-accent text-accent">
                <label for="is_active" class="text-sm font-medium text-white cursor-pointer">
                    Aktifkan Anggota Tim (Tampilkan di halaman About)
                </label>
            </div>

            <!-- Submit Buttons -->
            <div class="flex items-center gap-4 pt-6 mt-6 border-t border-white/10">
                <button type="submit" class="px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center gap-2">
                    <i class="fas fa-save"></i> Perbarui Data
                </button>
                <a href="<?= base_url('admin/tim') ?>" class="px-6 py-3 bg-white/5 text-white font-medium rounded-xl hover:bg-white/10 transition">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>

<?= $this->endSection() ?>