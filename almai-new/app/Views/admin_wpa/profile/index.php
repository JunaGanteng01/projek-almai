<?= $this->extend('admin_wpa/layouts/main') ?>

<?= $this->section('content') ?>

<h2 class="text-lg md:text-xl font-bold mb-6">Profile Saya</h2>

<div class="grid md:grid-cols-3 gap-4 md:gap-6">
    <!-- Profile Card -->
    <div class="md:col-span-1">
        <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 text-center">
            <!-- Photo -->
            <div class="relative inline-block mb-4">
                <?php
                $photo = $user['photo'] ?? '';
                if (!empty($photo)) {
                    if (strpos($photo, 'writable/') === 0) {
                        $photo = str_replace('writable/', '', $photo);
                    }
                    if (strpos($photo, 'http') !== 0) {
                        $photoUrl = base_url('file/' . $photo);
                    } else {
                        $photoUrl = $photo;
                    }
                } else {
                    $photoUrl = 'https://via.placeholder.com/150';
                }
                ?>
                <img src="<?= esc($photoUrl) ?>" alt="<?= esc($user['name']) ?>" class="w-24 h-24 md:w-32 md:h-32 rounded-full mx-auto object-cover border-4 border-accent/30">
                <button onclick="document.getElementById('photoInput').click()" class="absolute bottom-0 right-0 w-8 h-8 bg-accent text-black rounded-full flex items-center justify-center hover:bg-white transition">
                    <i class="fas fa-camera text-sm"></i>
                </button>
            </div>

            <h3 class="text-lg md:text-xl font-bold"><?= esc($user['name']) ?></h3>
            <p class="text-gray-400 text-sm mt-1"><?= esc($user['email']) ?></p>
            <div class="mt-4">
                <span class="px-3 py-1 bg-accent/20 text-accent rounded-full text-xs">Admin WPA</span>
            </div>

            <!-- Hidden Photo Upload Form -->
            <form action="<?= base_url('admin-wpa/profile/update-photo') ?>" method="post" enctype="multipart/form-data" id="photoForm" class="hidden">
                <?= csrf_field() ?>
                <input type="file" name="photo" id="photoInput" accept="image/*" onchange="document.getElementById('photoForm').submit()">
            </form>
        </div>

        <!-- Referral Section -->
        <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 mt-4">
            <h4 class="font-bold text-sm mb-4">Referral</h4>
            <div class="space-y-4">
                <!-- Kode Referral -->
                <div>
                    <label class="block text-xs text-gray-400 mb-2">Kode Referral Anda</label>
                    <div class="flex items-center gap-2">
                        <input type="text" value="<?= esc($user['code_referral'] ?? '-') ?>" readonly
                            class="flex-1 px-3 py-2 bg-black border border-white/20 rounded-lg text-sm font-bold text-accent focus:outline-none">
                        <button onclick="copyToClipboard('<?= esc($user['code_referral'] ?? '') ?>', this)"
                            class="w-10 h-10 flex items-center justify-center bg-white/5 border border-white/10 rounded-lg hover:bg-white/10 transition">
                            <i class="fas fa-copy text-gray-400"></i>
                        </button>
                    </div>
                </div>

                <!-- Link Referral -->
                <div>
                    <label class="block text-xs text-gray-400 mb-2">Link Referral</label>
                    <?php $referralLink = base_url('referral/' . ($user['code_referral'] ?? '')); ?>
                    <div class="flex items-center gap-2">
                        <input type="text" value="<?= esc($referralLink) ?>" readonly
                            class="flex-1 px-3 py-2 bg-black border border-white/20 rounded-lg text-sm text-gray-300 focus:outline-none overflow-hidden text-ellipsis">
                        <button onclick="copyToClipboard('<?= esc($referralLink) ?>', this)"
                            class="w-10 h-10 flex items-center justify-center bg-white/5 border border-white/10 rounded-lg hover:bg-white/10 transition">
                            <i class="fas fa-link text-gray-400"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Profile Form -->
    <div class="md:col-span-2">
        <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6">
            <h3 class="font-bold mb-6 text-sm md:text-base">Edit Profile</h3>

            <form action="<?= base_url('admin-wpa/profile/update') ?>" method="post">
                <?= csrf_field() ?>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs md:text-sm text-gray-400 mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="<?= esc($user['name']) ?>" required
                                class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-xs md:text-sm text-gray-400 mb-2">Email (Tidak bisa diubah)</label>
                            <input type="email" value="<?= esc($user['email']) ?>" readonly
                                class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:outline-none text-sm text-gray-500 cursor-not-allowed">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs md:text-sm text-gray-400 mb-2">Nomor Telepon</label>
                            <input type="text" name="phone" value="<?= esc($user['phone'] ?? '') ?>" placeholder="0812xxxxxx"
                                class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-xs md:text-sm text-gray-400 mb-2">Password Baru (Opsional)</label>
                            <input type="password" name="password" placeholder="Kosongkan jika tidak ingin diubah"
                                class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm">
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="flex justify-end pt-4">
                        <button type="submit" class="px-8 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function copyToClipboard(text, btn) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(() => showSuccess(btn));
        } else {
            const textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.position = "fixed";
            textArea.style.left = "-9999px";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
                showSuccess(btn);
            } catch (err) {
                console.error('Unable to copy', err);
            }
            document.body.removeChild(textArea);
        }
    }

    function showSuccess(btn) {
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check text-green-500"></i>';
        setTimeout(() => {
            btn.innerHTML = originalHtml;
        }, 2000);
    }
</script>

<?= $this->endSection() ?>