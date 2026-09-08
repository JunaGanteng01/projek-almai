<?= $this->extend('wpa/layouts/main') ?>

<?= $this->section('content') ?>

<h2 class="text-lg md:text-xl font-bold mb-6">Profile Saya</h2>

<div class="grid md:grid-cols-3 gap-4 md:gap-6">
    <!-- Profile Card -->
    <div class="md:col-span-1">
        <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 text-center">
            <!-- Photo -->
            <div class="relative inline-block mb-4">
                <?php
                $photo = $wpa['photo'] ?? '';
                if (!empty($photo)) {
                    // Check if path starts with writable/ (legacy/bug fix)
                    if (strpos($photo, 'writable/') === 0) {
                        $photo = str_replace('writable/', '', $photo);
                    }
                    // If it's a local file relative path, use file controller
                    if (strpos($photo, 'http') !== 0) {
                        $photoUrl = base_url('file/' . $photo);
                    } else {
                        $photoUrl = $photo;
                    }
                } else {
                    $photoUrl = 'https://via.placeholder.com/150';
                }
                ?>
                <img src="<?= esc($photoUrl) ?>" alt="<?= esc($wpa['name']) ?>" class="w-24 h-24 md:w-32 md:h-32 rounded-full mx-auto object-cover border-4 border-accent/30">
                <button onclick="document.getElementById('photoInput').click()" class="absolute bottom-0 right-0 w-8 h-8 bg-accent text-black rounded-full flex items-center justify-center hover:bg-white transition">
                    <i class="fas fa-camera text-sm"></i>
                </button>
            </div>

            <h3 class="text-lg md:text-xl font-bold"><?= esc($wpa['name']) ?></h3>
            <p class="text-accent text-sm"><?= esc($wpa['specialty']) ?></p>

            <!-- Stats -->
            <div class="flex justify-center gap-6 mt-4 text-xs md:text-sm text-gray-400">
                <span><i class="fas fa-star text-yellow-500 mr-1"></i> <?= number_format($wpa['rating'] ?? 0, 1) ?></span>
                <span><i class="fas fa-graduation-cap text-accent mr-1"></i> <?= $totalLayanan ?> Layanan</span>
            </div>

            <!-- Status Badge -->
            <div class="mt-4">
                <?php if (($wpa['status'] ?? 'active') === 'active'): ?>
                    <span class="px-3 py-1 bg-accent/20 text-accent rounded-full text-xs">Active</span>
                <?php else: ?>
                    <span class="px-3 py-1 bg-red-500/20 text-red-400 rounded-full text-xs">Inactive</span>
                <?php endif; ?>
            </div>

            <!-- Hidden Photo Upload Form -->
            <form action="<?= base_url('wpa/dashboard/profile/photo') ?>" method="post" enctype="multipart/form-data" id="photoForm" class="hidden">
                <?= csrf_field() ?>
                <input type="file" name="photo" id="photoInput" accept="image/*" onchange="document.getElementById('photoForm').submit()">
            </form>
        </div>

        <!-- Quick Stats -->
        <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 mt-4">
            <h4 class="font-bold text-sm mb-4">Statistik</h4>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-sm">Total Layanan</span>
                    <span class="font-bold"><?= $totalLayanan ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-sm">Rating</span>
                    <span class="font-bold text-yellow-500"><?= number_format($wpa['rating'] ?? 0, 1) ?> <i class="fas fa-star text-xs"></i></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-sm">Experience</span>
                    <span class="font-bold"><?= esc($wpa['experience'] ?? '-') ?></span>
                </div>
            </div>
        </div>

        <!-- Referral Section -->
        <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 mt-4">
            <h4 class="font-bold text-sm mb-4">Referral</h4>
            <div class="space-y-4">
                <!-- Kode Referral -->
                <div>
                    <label class="block text-xs text-gray-400 mb-2">Kode Referral Anda</label>
                    <div class="flex items-center gap-2">
                        <input type="text" value="<?= esc($wpa['code_referral'] ?? '-') ?>" readonly
                            class="flex-1 px-3 py-2 bg-black border border-white/20 rounded-lg text-sm font-bold text-accent focus:outline-none">
                        <button onclick="copyToClipboard('<?= esc($wpa['code_referral'] ?? '') ?>', this)"
                            class="w-10 h-10 flex items-center justify-center bg-white/5 border border-white/10 rounded-lg hover:bg-white/10 transition">
                            <i class="fas fa-copy text-gray-400"></i>
                        </button>
                    </div>
                </div>

                <!-- Link Referral -->
                <div>
                    <label class="block text-xs text-gray-400 mb-2">Link Referral</label>
                    <?php $referralLink = base_url('referral/' . ($wpa['code_referral'] ?? '')); ?>
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

            <form action="<?= base_url('wpa/dashboard/profile/update') ?>" method="post">
                <?= csrf_field() ?>

                <div class="space-y-4">
                    <!-- Name & Specialty -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs md:text-sm text-gray-400 mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="<?= esc($wpa['name']) ?>" required
                                class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-xs md:text-sm text-gray-400 mb-2">Specialty</label>
                            <select name="specialty" required
                                class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm">
                                <?php foreach ($specialties as $spec): ?>
                                    <option value="<?= esc($spec) ?>" <?= ($wpa['specialty'] ?? '') === $spec ? 'selected' : '' ?>><?= esc($spec) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Experience & Instagram -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs md:text-sm text-gray-400 mb-2">Experience</label>
                            <input type="text" name="experience" value="<?= esc($wpa['experience'] ?? '') ?>" placeholder="e.g. 5+ Years"
                                class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm">
                        </div>
                        <div>
                            <label class="block text-xs md:text-sm text-gray-400 mb-2">Instagram</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">@</span>
                                <input type="text" name="instagram" value="<?= esc($wpa['instagram'] ?? '') ?>" placeholder="username"
                                    class="w-full pl-8 pr-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- YouTube & MQL5 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs md:text-sm text-gray-400 mb-2">YouTube Channel ID</label>
                            <input type="text" name="youtube" value="<?= esc($wpa['youtube'] ?? '') ?>" placeholder="UC1234567890abcdefghijk"
                                class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm">
                            <p class="text-[10px] text-gray-500 mt-1">Channel ID dari YouTube (bukan username)</p>
                        </div>
                        <div>
                            <label class="block text-xs md:text-sm text-gray-400 mb-2">MQL5 Widget URL</label>
                            <input type="url" name="mql5_widget_url" value="<?= esc($wpa['mql5_widget_url'] ?? '') ?>" placeholder="https://www.mql5.com/..."
                                class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm">
                            <p class="text-[10px] text-gray-500 mt-1">URL widget MQL5 Signal untuk ditampilkan di profil</p>
                        </div>
                    </div>

                    <!-- Bio -->
                    <div>
                        <label class="block text-xs md:text-sm text-gray-400 mb-2">Bio</label>
                        <textarea name="bio" rows="4" placeholder="Ceritakan tentang diri Anda..."
                            class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm resize-none"><?= esc($wpa['bio'] ?? '') ?></textarea>
                        <p class="text-xs text-gray-500 mt-1">Maksimal 1000 karakter</p>
                    </div>

                    <!-- Certifications -->
                    <div>
                        <label class="block text-xs md:text-sm text-gray-400 mb-2">Sertifikasi</label>
                        <textarea name="certifications" rows="3" placeholder="Masukkan sertifikasi (satu per baris)&#10;Contoh:&#10;Certified Financial Planner&#10;Trading License"
                            class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm resize-none"><?= implode("\n", $certifications) ?></textarea>
                        <p class="text-xs text-gray-500 mt-1">Satu sertifikasi per baris</p>
                    </div>

                    <!-- Current Certifications Display -->
                    <?php if (!empty($certifications)): ?>
                        <div>
                            <label class="block text-xs text-gray-400 mb-2">Sertifikasi Saat Ini</label>
                            <div class="flex flex-wrap gap-2">
                                <?php foreach ($certifications as $cert): ?>
                                    <span class="px-3 py-1 bg-accent/10 text-accent rounded-full text-xs"><?= esc($cert) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Submit -->
                    <div class="flex justify-end pt-4">
                        <button type="submit" class="px-8 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Public Profile Link -->
        <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 mt-4">
            <h4 class="font-bold text-sm mb-3">Profile Publik</h4>
            <p class="text-gray-400 text-sm mb-3">Link profile Anda yang bisa dilihat oleh user:</p>
            <div class="flex items-center gap-2">
                <?php $profileLink = base_url('wpa/' . ($wpa['slug'] ?? $wpa['id'])); ?>
                <input type="text" value="<?= $profileLink ?>" readonly
                    class="flex-1 px-4 py-2 bg-black border border-white/20 rounded-xl text-sm text-gray-400">
                <button onclick="copyToClipboard('<?= $profileLink ?>', this)" class="px-4 py-2 bg-white/10 rounded-xl hover:bg-white/20 transition">
                    <i class="fas fa-copy"></i>
                </button>
                <a href="<?= $profileLink ?>" target="_blank" class="px-4 py-2 bg-accent text-black rounded-xl hover:bg-white transition">
                    <i class="fas fa-external-link-alt"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function copyToClipboard(text, btn) {
        // Try to use modern clipboard API
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(() => showSuccess(btn));
        } else {
            // Fallback
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