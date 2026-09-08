<?= $this->extend('admin_wpa/layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-6 flex items-center gap-4">
    <a href="<?= base_url('admin-wpa/users') ?>" class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-xl hover:bg-white/20 transition">
        <i class="fas fa-arrow-left"></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-white">Tambah User Baru</h1>
        <p class="text-gray-400">Daftarkan pengguna baru ke dalam jaringan WPA Anda.</p>
    </div>
</div>

<div class="max-w-2xl">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 md:p-8">
        <form action="<?= base_url('admin-wpa/users/store') ?>" method="post">
            <?= csrf_field() ?>
            
            <div class="space-y-6">
                <!-- Data Personal -->
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-white border-b border-white/5 pb-2">Informasi Akun</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="<?= old('name') ?>" required
                            class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-white transition"
                            placeholder="Masukkan nama lengkap">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Email</label>
                        <input type="email" name="email" value="<?= old('email') ?>" required
                            class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-white transition"
                            placeholder="email@contoh.com">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Nomor WhatsApp</label>
                        <input type="text" name="phone" value="<?= old('phone') ?>" required
                            class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-white transition"
                            placeholder="08123456789">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Password</label>
                        <input type="password" name="password" required
                            class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-white transition"
                            placeholder="Minimal 6 karakter">
                    </div>
                </div>

                <!-- Afiliasi -->
                <div class="space-y-4 pt-4">
                    <h3 class="text-lg font-bold text-white border-b border-white/5 pb-2">Afiliasi WPA</h3>
                    <p class="text-xs text-gray-500">Pilih WPA mana yang akan menjadi upline langsung user ini.</p>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Daftar Lewat WPA</label>
                        <select name="affiliator_code" required
                            class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-white transition">
                            <option value="">Pilih WPA</option>
                            <?php foreach ($wpaUsers as $wu): ?>
                                <option value="<?= $wu['code_referral'] ?>" <?= old('affiliator_code') == $wu['code_referral'] ? 'selected' : '' ?>>
                                    <?= esc($wu['name']) ?> (<?= $wu['code_referral'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i>
                        Simpan User
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
