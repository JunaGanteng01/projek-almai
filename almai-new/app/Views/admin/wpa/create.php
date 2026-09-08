<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<?php $pageTitle = 'Tambah WPA';
$pageSubtitle = 'Daftarkan Wakil Penasihat Berjangka baru'; ?>

<!-- Back Button -->
<div class="mb-6">
    <a href="<?= base_url('admin/wpa') ?>" class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar WPA
    </a>
</div>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('errors')): ?>
    <div class="bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl mb-4">
        <ul class="list-disc list-inside">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form action="<?= base_url('admin/wpa/store') ?>" method="post" enctype="multipart/form-data" class="max-w-3xl">
    <?= csrf_field() ?>

    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 md:p-8 mb-6">
        <h3 class="font-bold mb-6 flex items-center justify-between gap-2">
            <span class="flex items-center gap-2">
                <i class="fas fa-link text-accent"></i> Linked User
            </span>
        </h3>
        <input type="hidden" name="user_id" id="userIdInput" value="<?= old('user_id') ?>">
        <div class="grid md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-400 mb-2">Akun User Terkait</label>
                <div class="relative">
                    <select id="userSelector" class="w-full bg-black border border-white/20 rounded-xl pl-4 pr-10 py-3 text-white focus:border-accent outline-none transition appearance-none cursor-pointer" onchange="selectUser(this)">
                        <option value="">-- Hubungkan Akun User --</option>
                        <?php if (!empty($wpaUsers)): ?>
                            <?php foreach ($wpaUsers as $u): ?>
                                <option value="<?= $u['id'] ?>" <?= old('user_id') == $u['id'] ? 'selected' : '' ?> data-name="<?= esc($u['name']) ?>" data-email="<?= esc($u['email']) ?>" data-phone="<?= esc($u['phone'] ?? '') ?>">
                                    <?= esc($u['name']) ?> (<?= esc($u['email']) ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 text-xs pointer-events-none"></i>
                </div>
                <p class="text-[10px] text-gray-500 mt-1">Pilih user untuk otomatis mengisi data nama, email, dan WhatsApp.</p>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Nama Lengkap *</label>
                <input type="text" name="name" id="nameInput" required value="<?= old('name') ?>"
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                    placeholder="Nama lengkap dengan gelar">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Email *</label>
                <input type="email" name="email" id="emailInput" required value="<?= old('email') ?>"
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                    placeholder="email@almai.id">
                <p id="emailHint" class="text-xs text-gray-500 mt-1">Password default: wpa123</p>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">No. WhatsApp</label>
                <input type="tel" name="phone" id="phoneInput" value="<?= old('phone') ?>"
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                    placeholder="08xxxxxxxxxx">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Instagram</label>
                <input type="text" name="instagram" value="<?= old('instagram') ?>"
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                    placeholder="@username">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">YouTube Channel ID</label>
                <input type="text" name="youtube" value="<?= old('youtube') ?>"
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                    placeholder="UC1234567890abcdefghijk">
                <p class="text-xs text-gray-500 mt-1">Channel ID dari YouTube (bukan username)</p>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">MQL5 Widget URL</label>
                <input type="url" name="mql5_widget_url" value="<?= old('mql5_widget_url') ?>"
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                    placeholder="https://www.mql5.com/en/signals/widget/signal/79mv?t=green?fw=html">
                <p class="text-xs text-gray-500 mt-1">URL widget MQL5 Signal untuk ditampilkan di profil WPA</p>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">TikTok Username</label>
                <input type="text" name="tiktok" value="<?= old('tiktok') ?>"
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                    placeholder="@username">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">TikTok SecUid</label>
                <input type="text" name="tiktok_secuid" value="<?= old('tiktok_secuid') ?>"
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                    placeholder="MS4wLjABAAAA...">
            </div>
        </div>
    </div>

    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 md:p-8 mb-6">
        <h3 class="font-bold mb-6 flex items-center gap-2">
            <i class="fas fa-image text-accent"></i> Foto Profil
        </h3>

        <!-- Photo Preview -->
        <div class="flex items-center gap-6 mb-4">
            <div class="relative">
                <img id="photoPreview" src="https://via.placeholder.com/120" alt="Preview" class="w-24 h-24 rounded-full object-cover border-2 border-white/20">
                <div class="absolute inset-0 bg-black/50 rounded-full flex items-center justify-center opacity-0 hover:opacity-100 transition cursor-pointer" onclick="document.getElementById('photoInput').click()">
                    <i class="fas fa-camera text-white"></i>
                </div>
            </div>
            <div class="flex-1">
                <label class="block text-sm text-gray-400 mb-2">Upload Foto</label>
                <input type="file" id="photoInput" name="photo_file" accept="image/*" class="hidden" onchange="previewPhoto(this)">
                <button type="button" onclick="document.getElementById('photoInput').click()" class="px-4 py-2 bg-white/10 border border-white/20 rounded-xl hover:bg-white/20 transition text-sm">
                    <i class="fas fa-upload mr-2"></i> Pilih Foto
                </button>
                <p class="text-xs text-gray-500 mt-2">JPG, PNG max 2MB. Atau gunakan URL di bawah.</p>
            </div>
        </div>

        <div>
            <label class="block text-sm text-gray-400 mb-2">Atau URL Foto</label>
            <input type="text" name="photo" id="photoUrl" value="<?= old('photo') ?>"
                class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                placeholder="https://example.com/photo.jpg" onchange="previewPhotoUrl(this.value)">
        </div>
    </div>

    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 md:p-8 mb-6">
        <h3 class="font-bold mb-6 flex items-center gap-2">
            <i class="fas fa-briefcase text-accent"></i> Informasi Profesional
        </h3>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm text-gray-400 mb-2">Specialty *</label>
                <select name="specialty" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
                    <option value="">Pilih Specialty</option>
                    <option value="Gold Specialist" <?= old('specialty') === 'Gold Specialist' ? 'selected' : '' ?>>Gold Specialist</option>
                    <option value="Forex Specialist" <?= old('specialty') === 'Forex Specialist' ? 'selected' : '' ?>>Forex Specialist</option>
                    <option value="Crypto Specialist" <?= old('specialty') === 'Crypto Specialist' ? 'selected' : '' ?>>Crypto Specialist</option>
                    <option value="Stock Specialist" <?= old('specialty') === 'Stock Specialist' ? 'selected' : '' ?>>Stock Specialist</option>
                    <option value="Index Specialist" <?= old('specialty') === 'Index Specialist' ? 'selected' : '' ?>>Index Specialist</option>
                    <option value="EA Specialist" <?= old('specialty') === 'EA Specialist' ? 'selected' : '' ?>>EA Specialist</option>
                    <option value="AI Specialist" <?= old('specialty') === 'AI Specialist' ? 'selected' : '' ?>>AI Specialist</option>
                    <option value="Propfirm Specialist" <?= old('specialty') === 'Propfirm Specialist' ? 'selected' : '' ?>>Propfirm Specialist</option>
                    <option value="Risk Management" <?= old('specialty') === 'Risk Management' ? 'selected' : '' ?>>Risk Management</option>
                    <option value="AMANDANA" <?= old('specialty') === 'AMANDANA' ? 'selected' : '' ?>>AMANDANA</option>
                    <option value="METAVULUS" <?= old('specialty') === 'METAVULUS' ? 'selected' : '' ?>>METAVULUS</option>
                    <option value="REPUBLIC" <?= old('specialty') === 'REPUBLIC' ? 'selected' : '' ?>>REPUBLIC</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Experience *</label>
                <select name="experience" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
                    <option value="">Pilih Experience</option>
                    <option value="1 Tahun" <?= old('experience') === '1 Tahun' ? 'selected' : '' ?>>1 Tahun</option>
                    <option value="2 Tahun" <?= old('experience') === '2 Tahun' ? 'selected' : '' ?>>2 Tahun</option>
                    <option value="3 Tahun" <?= old('experience') === '3 Tahun' ? 'selected' : '' ?>>3 Tahun</option>
                    <option value="5 Tahun" <?= old('experience') === '5 Tahun' ? 'selected' : '' ?>>5 Tahun</option>
                    <option value="7 Tahun" <?= old('experience') === '7 Tahun' ? 'selected' : '' ?>>7 Tahun</option>
                    <option value="10 Tahun" <?= old('experience') === '10 Tahun' ? 'selected' : '' ?>>10+ Tahun</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-400 mb-2">Sertifikasi</label>
                <input type="text" name="certifications" value="<?= old('certifications') ?>"
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                    placeholder="BAPPEBTI, LSP PBK, OJK (pisahkan dengan koma)">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm text-gray-400 mb-2">Bio *</label>
                <textarea name="bio" required rows="4"
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none resize-none"
                    placeholder="Deskripsi singkat tentang WPA..."><?= old('bio') ?></textarea>
            </div>
        </div>
    </div>

    <div class="flex gap-4">
        <a href="<?= base_url('admin/wpa') ?>" class="flex-1 py-4 border border-white/20 rounded-xl text-center hover:bg-white/5 transition">
            Batal
        </a>
        <button type="submit" class="flex-1 py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
            <i class="fas fa-save mr-2"></i> Simpan WPA
        </button>
    </div>
</form>

<script>
    function previewPhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photoPreview').src = e.target.result;
                document.getElementById('photoUrl').value = '';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewPhotoUrl(url) {
        if (url) {
            document.getElementById('photoPreview').src = url;
            document.getElementById('photoInput').value = '';
        }
    }

    function selectUser(select) {
        const option = select.options[select.selectedIndex];
        if (!option.value) {
            // Reset form
            document.getElementById('userIdInput').value = '';
            document.getElementById('nameInput').value = '';
            document.getElementById('emailInput').value = '';
            document.getElementById('phoneInput').value = '';
            document.getElementById('emailInput').readOnly = false;
            document.getElementById('emailHint').classList.remove('hidden');
            return;
        }

        // Fill form
        document.getElementById('userIdInput').value = option.value;
        document.getElementById('nameInput').value = option.getAttribute('data-name');
        document.getElementById('emailInput').value = option.getAttribute('data-email');
        document.getElementById('phoneInput').value = option.getAttribute('data-phone');

        // Mark as read-only for email since it's an existing user
        document.getElementById('emailInput').readOnly = true;
        document.getElementById('emailHint').classList.add('hidden');
    }
</script>
<?= $this->endSection() ?>