<?php
$this->setVar('pageTitle', 'Edit WPA');
$this->setVar('pageSubtitle', esc($wpa['name'] ?? 'Profil'));
?>
<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="w-full max-w-full mx-auto space-y-6">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm">
        <a href="<?= base_url('admin/wpa') ?>" class="text-gray-400 hover:text-accent transition flex items-center gap-1">
            <i class="fas fa-arrow-left"></i> WPA
        </a>
        <span class="text-gray-600">/</span>
        <span class="text-gray-500">Edit Profile</span>
    </div>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl">
            <ul class="list-disc list-inside text-sm">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('admin/wpa/update/' . $wpa['id']) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- MAIN COLUMN (9 cols) -->
            <div class="lg:col-span-9 space-y-6">

                <div class="bg-[#111] border border-white/10 rounded-2xl p-6 md:p-8 mb-6">
                    <h3 class="font-bold mb-6 flex items-center justify-between gap-2">
                        <span class="flex items-center gap-2">
                            <i class="fas fa-link text-accent"></i> Linked User
                        </span>
                    </h3>
                    <input type="hidden" name="user_id" id="userIdInput" value="<?= old('user_id', $wpa['user_id'] ?? '') ?>">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm text-gray-400 mb-2">Akun User Terkait</label>
                            <div class="relative">
                                <select id="userSelector" class="w-full bg-black border border-white/20 rounded-xl pl-4 pr-10 py-3 text-white focus:border-accent outline-none transition appearance-none cursor-pointer" onchange="selectUser(this)">
                                    <option value="">-- Hubungkan Akun User --</option>
                                    <?php if (!empty($wpaUsers)): ?>
                                        <?php foreach ($wpaUsers as $u): ?>
                                            <option value="<?= $u['id'] ?>" <?= old('user_id', $wpa['user_id'] ?? '') == $u['id'] ? 'selected' : '' ?> data-name="<?= esc($u['name']) ?>" data-email="<?= esc($u['email']) ?>" data-phone="<?= esc($u['phone'] ?? '') ?>">
                                                <?= esc($u['name']) ?> (<?= esc($u['email']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 text-xs pointer-events-none"></i>
                            </div>
                            <p class="text-[10px] text-gray-500 mt-1">Pilih user untuk memperbarui data nama, email, dan WhatsApp. Akun user yang terhubung akan membuat email menjadi read-only.</p>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Nama Lengkap *</label>
                            <input type="text" name="name" id="nameInput" required value="<?= old('name', $wpa['name']) ?>"
                                class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                                placeholder="Nama lengkap dengan gelar">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Email</label>
                            <input type="email" name="email" id="emailInput" value="<?= old('email', $wpa['user_email'] ?? '') ?>"
                                class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none <?= !empty($wpa['user_id']) ? 'bg-gray-900' : '' ?>"
                                placeholder="email@almai.id" <?= !empty($wpa['user_id']) ? 'readonly' : '' ?>>
                            <p id="emailHint" class="text-xs text-gray-500 mt-1 <?= !empty($wpa['user_id']) ? 'hidden' : '' ?>">Password default: wpa123</p>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">No. WhatsApp</label>
                            <input type="tel" name="phone" id="phoneInput" value="<?= old('phone', $wpa['user_phone'] ?? '') ?>"
                                class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                                placeholder="08xxxxxxxxxx">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Instagram</label>
                            <input type="text" name="instagram" value="<?= old('instagram', $wpa['instagram']) ?>"
                                class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                                placeholder="@username">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">YouTube Channel ID</label>
                            <input type="text" name="youtube" value="<?= old('youtube', $wpa['youtube'] ?? '') ?>"
                                class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                                placeholder="UC1234567890abcdefghijk">
                            <p class="text-xs text-gray-500 mt-1">Channel ID dari YouTube (bukan username)</p>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">MQL5 Widget URL</label>
                            <input type="url" name="mql5_widget_url" value="<?= old('mql5_widget_url', $wpa['mql5_widget_url'] ?? '') ?>"
                                class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                                placeholder="https://www.mql5.com/en/signals/widget/signal/79mv?t=green?fw=html">
                            <p class="text-xs text-gray-500 mt-1">URL widget MQL5 Signal untuk ditampilkan di profil WPA</p>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">TikTok Username</label>
                            <input type="text" name="tiktok" value="<?= old('tiktok', $wpa['tiktok'] ?? '') ?>"
                                class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                                placeholder="@username">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">TikTok SecUid</label>
                            <input type="text" name="tiktok_secuid" value="<?= old('tiktok_secuid', $wpa['tiktok_secuid'] ?? '') ?>"
                                class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                                placeholder="MS4wLjABAAAA...">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Slug (URL)</label>
                            <div class="flex items-center gap-2">
                                <span class="text-gray-500 text-sm">/wpa/</span>
                                <input type="text" name="slug" value="<?= old('slug', $wpa['slug'] ?? '') ?>"
                                    class="flex-1 bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                                    placeholder="nama-pendek">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Kosongkan untuk generate otomatis dari nama</p>
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
                            <?php
                            $previewUrl = $wpa['photo'];
                            if (strpos($previewUrl, 'uploads/') === 0) {
                                $previewUrl = base_url('file/' . $previewUrl);
                            }
                            ?>
                            <img id="photoPreview" src="<?= esc($previewUrl) ?>" alt="Preview" class="w-24 h-24 rounded-full object-cover border-2 border-white/20">
                            <div class="absolute inset-0 bg-black/50 rounded-full flex items-center justify-center opacity-0 hover:opacity-100 transition cursor-pointer" onclick="document.getElementById('photoInput').click()">
                                <i class="fas fa-camera text-white"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm text-gray-400 mb-2">Upload Foto Baru</label>
                            <input type="file" id="photoInput" name="photo_file" accept="image/*" class="hidden" onchange="previewPhoto(this)">
                            <button type="button" onclick="document.getElementById('photoInput').click()" class="px-4 py-2 bg-white/10 border border-white/20 rounded-xl hover:bg-white/20 transition text-sm">
                                <i class="fas fa-upload mr-2"></i> Pilih Foto
                            </button>
                            <p class="text-xs text-gray-500 mt-2">JPG, PNG max 2MB. Kosongkan jika tidak ingin mengubah.</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Atau URL Foto</label>
                        <input type="text" name="photo" id="photoUrl" value="<?= old('photo', $wpa['photo']) ?>"
                            class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                            placeholder="https://example.com/photo.jpg atau uploads/wpa/foto.png" onchange="previewPhotoUrl(this.value)">
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
                                <option value="Gold Specialist" <?= $wpa['specialty'] === 'Gold Specialist' ? 'selected' : '' ?>>Gold Specialist</option>
                                <option value="Forex Specialist" <?= $wpa['specialty'] === 'Forex Specialist' ? 'selected' : '' ?>>Forex Specialist</option>
                                <option value="Crypto Specialist" <?= $wpa['specialty'] === 'Crypto Specialist' ? 'selected' : '' ?>>Crypto Specialist</option>
                                <option value="Stock Specialist" <?= $wpa['specialty'] === 'Stock Specialist' ? 'selected' : '' ?>>Stock Specialist</option>
                                <option value="Index Specialist" <?= $wpa['specialty'] === 'Index Specialist' ? 'selected' : '' ?>>Index Specialist</option>
                                <option value="EA Specialist" <?= $wpa['specialty'] === 'EA Specialist' ? 'selected' : '' ?>>EA Specialist</option>
                                <option value="AI Specialist" <?= $wpa['specialty'] === 'AI Specialist' ? 'selected' : '' ?>>AI Specialist</option>
                                <option value="Propfirm Specialist" <?= $wpa['specialty'] === 'Propfirm Specialist' ? 'selected' : '' ?>>Propfirm Specialist</option>
                                <option value="Risk Management" <?= $wpa['specialty'] === 'Risk Management' ? 'selected' : '' ?>>Risk Management</option>
                                <option value="AMANDANA" <?= $wpa['specialty'] === 'AMANDANA' ? 'selected' : '' ?>>AMANDANA</option>
                                <option value="METAVULUS" <?= $wpa['specialty'] === 'METAVULUS' ? 'selected' : '' ?>>METAVULUS</option>
                                <option value="REPUBLIC" <?= $wpa['specialty'] === 'REPUBLIC' ? 'selected' : '' ?>>REPUBLIC</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Experience *</label>
                            <select name="experience" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
                                <option value="1 Tahun" <?= $wpa['experience'] === '1 Tahun' ? 'selected' : '' ?>>1 Tahun</option>
                                <option value="2 Tahun" <?= $wpa['experience'] === '2 Tahun' ? 'selected' : '' ?>>2 Tahun</option>
                                <option value="3 Tahun" <?= $wpa['experience'] === '3 Tahun' ? 'selected' : '' ?>>3 Tahun</option>
                                <option value="5 Tahun" <?= $wpa['experience'] === '5 Tahun' ? 'selected' : '' ?>>5 Tahun</option>
                                <option value="7 Tahun" <?= $wpa['experience'] === '7 Tahun' ? 'selected' : '' ?>>7 Tahun</option>
                                <option value="8 Tahun" <?= $wpa['experience'] === '8 Tahun' ? 'selected' : '' ?>>8 Tahun</option>
                                <option value="9 Tahun" <?= $wpa['experience'] === '9 Tahun' ? 'selected' : '' ?>>9 Tahun</option>
                                <option value="10 Tahun" <?= $wpa['experience'] === '10 Tahun' ? 'selected' : '' ?>>10+ Tahun</option>
                                <option value="12 Tahun" <?= $wpa['experience'] === '12 Tahun' ? 'selected' : '' ?>>12 Tahun</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Rating</label>
                            <input type="number" name="rating" step="0.1" min="0" max="5" value="<?= old('rating', $wpa['rating'] ?? '0') ?>"
                                class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                                placeholder="4.5">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm text-gray-400 mb-2">Sertifikasi</label>
                            <?php
                            $certs = json_decode($wpa['certifications'] ?? '[]', true);
                            $certsStr = is_array($certs) ? implode(', ', $certs) : '';
                            ?>
                            <input type="text" name="certifications" value="<?= old('certifications', $certsStr) ?>"
                                class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                                placeholder="BAPPEBTI, LSP PBK, OJK (pisahkan dengan koma)">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm text-gray-400 mb-2">Bio *</label>
                            <textarea name="bio" required rows="4"
                                class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none resize-none"
                                placeholder="Deskripsi singkat tentang WPA..."><?= old('bio', $wpa['bio']) ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- 8 Tahapan Sertifikasi Section (dropdown Fase ada di sidebar) -->
                <div class="bg-[#111] border border-white/10 rounded-2xl p-6 md:p-8 mb-6">
                    <h3 class="font-bold mb-6 flex items-center gap-2">
                        <i class="fas fa-graduation-cap text-accent"></i> 8 Tahapan Sertifikasi WPA
                    </h3>

                    <div class="border-t border-white/10 pt-6">
                        <h4 class="font-bold mb-4 text-sm">Upload Foto Sertifikat per Fase</h4>
                        <p class="text-xs text-gray-400 mb-4">Upload foto sertifikat untuk setiap fase yang sudah diselesaikan. Fase yang memiliki sertifikat akan menampilkan badge dan dapat diklik untuk melihat sertifikat.</p>

                        <?php
                        $phaseCertificates = [];
                        if (!empty($wpa['phase_certificates'])) {
                            $phaseCertificates = is_string($wpa['phase_certificates'])
                                ? json_decode($wpa['phase_certificates'], true)
                                : $wpa['phase_certificates'];
                        }
                        $phasesCert = array_slice(\App\Models\WpaModel::PHASES, 0, 8, true); // 1-8 saja untuk sertifikat
                        ?>

                        <div class="grid md:grid-cols-2 gap-4">
                            <?php foreach ($phasesCert as $num => $title): ?>
                                <div class="bg-black/50 border border-white/10 rounded-xl p-4">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1">
                                            <label class="block text-sm font-medium mb-1"><?= esc($title) ?></label>
                                            <?php if (isset($phaseCertificates[$num])): ?>
                                                <p class="text-xs text-accent flex items-center gap-1">
                                                    <i class="fas fa-check-circle"></i> Sertifikat tersedia
                                                </p>
                                            <?php else: ?>
                                                <p class="text-xs text-gray-500">Belum ada sertifikat</p>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (isset($phaseCertificates[$num])): ?>
                                            <button type="button" onclick="viewCertificate('<?= esc($phaseCertificates[$num], 'js') ?>', '<?= esc($title, 'js') ?>')"
                                                class="text-accent hover:text-white transition text-xs">
                                                <i class="fas fa-eye"></i> Lihat
                                            </button>
                                        <?php endif; ?>
                                    </div>

                                    <input type="file" name="phase_cert_<?= $num ?>" id="phaseCert<?= $num ?>" accept="image/*" class="hidden" onchange="previewPhaseCert(<?= $num ?>, this)">
                                    <button type="button" onclick="document.getElementById('phaseCert<?= $num ?>').click()"
                                        class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-lg hover:bg-white/10 transition text-xs flex items-center justify-center gap-2">
                                        <i class="fas fa-upload"></i>
                                        <?= isset($phaseCertificates[$num]) ? 'Ganti Sertifikat' : 'Upload Sertifikat' ?>
                                    </button>

                                    <?php if (isset($phaseCertificates[$num])): ?>
                                        <input type="hidden" name="existing_cert_<?= $num ?>" value="<?= esc($phaseCertificates[$num]) ?>">
                                        <button type="button" onclick="removePhaseCert(<?= $num ?>)"
                                            class="w-full mt-2 px-3 py-1.5 text-red-400 hover:text-red-300 transition text-xs">
                                            <i class="fas fa-trash"></i> Hapus Sertifikat
                                        </button>
                                    <?php endif; ?>

                                    <div id="certPreview<?= $num ?>" class="mt-2 hidden">
                                        <img src="" alt="Preview" class="w-full h-20 object-cover rounded-lg border border-white/20">
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

            </div>
            <!-- END MAIN COLUMN -->

            <!-- SIDEBAR (3 cols) -->
            <div class="lg:col-span-3 space-y-6">

                <!-- Status -->
                <div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
                    <div class="p-4 border-b border-white/10">
                        <h3 class="font-bold text-white text-sm">Status</h3>
                    </div>
                    <div class="p-4">
                        <select name="status" class="w-full bg-black border border-white/20 rounded-xl px-4 py-2.5 text-white focus:border-accent outline-none transition text-sm appearance-none cursor-pointer">
                            <option value="active" <?= ($wpa['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= ($wpa['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                </div>

                <!-- Fase / Progress -->
                <?php
                $currentPhase = (int) old('current_phase', $wpa['current_phase'] ?? 1);
                $progressPct = $currentPhase >= 9 ? 100 : round(($currentPhase / 9) * 100);
                ?>
                <div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
                    <div class="p-4 border-b border-white/10">
                        <h3 class="font-bold text-white text-sm">Fase / Progress</h3>
                    </div>
                    <div class="p-4 space-y-4">
                        <div class="relative">
                            <label class="text-xs font-medium text-gray-500 mb-1 block">Fase Saat Ini</label>
                            <select name="current_phase" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-2.5 text-white focus:border-accent outline-none transition text-sm appearance-none cursor-pointer">
                                <?php foreach (\App\Models\WpaModel::PHASES as $id => $label): ?>
                                    <option value="<?= $id ?>" <?= $currentPhase == $id ? 'selected' : '' ?>><?= esc($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs text-gray-500 mb-1 font-medium">
                                <span>Progress</span>
                                <span><?= $progressPct ?>%</span>
                            </div>
                            <div class="w-full bg-white/10 rounded-full h-2 overflow-hidden">
                                <div class="bg-accent h-full rounded-full transition-all duration-500" style="width: <?= $progressPct ?>%"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Aksi paling bawah -->
        <div class="mt-8 pt-6 border-t border-white/10">
            <div class="flex flex-col sm:flex-row gap-3 justify-end">
                <a href="<?= base_url('admin/wpa') ?>" class="order-2 sm:order-1 px-6 py-3 bg-transparent border border-white/20 text-white font-medium text-sm rounded-xl hover:bg-white/5 transition text-center">
                    Batal
                </a>
                <button type="submit" class="order-1 sm:order-2 px-6 py-3 bg-accent text-black font-bold text-sm rounded-xl hover:bg-accent/90 transition">
                    <i class="fas fa-save mr-2"></i> Update WPA
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function previewPhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photoPreview').src = e.target.result;
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
            // Reset to manual entry
            document.getElementById('userIdInput').value = '';
            document.getElementById('emailInput').value = '';
            document.getElementById('phoneInput').value = '';
            document.getElementById('emailInput').readOnly = false;
            document.getElementById('emailInput').classList.remove('bg-gray-900');
            document.getElementById('emailHint').classList.remove('hidden');
            return;
        }

        // Fill form with selected user data
        document.getElementById('userIdInput').value = option.value;
        document.getElementById('nameInput').value = option.getAttribute('data-name');
        document.getElementById('emailInput').value = option.getAttribute('data-email');
        document.getElementById('phoneInput').value = option.getAttribute('data-phone') || '';

        // Mark email as read-only since it's an existing user
        document.getElementById('emailInput').readOnly = true;
        document.getElementById('emailInput').classList.add('bg-gray-900');
        document.getElementById('emailHint').classList.add('hidden');
    }

    function previewPhaseCert(phaseNum, input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            const preview = document.getElementById('certPreview' + phaseNum);
            const img = preview.querySelector('img');

            reader.onload = function(e) {
                img.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removePhaseCert(phaseNum) {
        if (confirm('Hapus sertifikat fase ini?')) {
            const input = document.getElementById('phaseCert' + phaseNum);
            const preview = document.getElementById('certPreview' + phaseNum);
            const existingInput = document.querySelector('input[name="existing_cert_' + phaseNum + '"]');

            input.value = '';
            preview.classList.add('hidden');

            if (existingInput) {
                existingInput.value = '';
            }

            // Add hidden input to mark for deletion
            const deleteInput = document.createElement('input');
            deleteInput.type = 'hidden';
            deleteInput.name = 'delete_cert_' + phaseNum;
            deleteInput.value = '1';
            input.parentElement.appendChild(deleteInput);
        }
    }

    function viewCertificate(url, title) {
        // Create modal if not exists
        let modal = document.getElementById('certModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'certModal';
            modal.className = 'fixed inset-0 bg-black/95 z-[9999] flex items-center justify-center p-4';
            modal.style.display = 'none';
            modal.innerHTML = `
            <div class="relative max-w-4xl max-h-[90vh]">
                <button onclick="closeCertModal()" class="absolute -top-10 right-0 w-10 h-10 bg-accent text-black rounded-full flex items-center justify-center hover:bg-white transition">
                    <i class="fas fa-times"></i>
                </button>
                <img id="certModalImg" src="" alt="Certificate" class="max-w-full max-h-[90vh] object-contain rounded-xl border-2 border-accent/30">
                <div id="certModalTitle" class="text-center text-accent font-bold mt-4"></div>
            </div>
        `;
            document.body.appendChild(modal);

            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeCertModal();
                }
            });
        }

        // Fix URL if needed
        let fixedUrl = url;
        if (url.startsWith('uploads/')) {
            fixedUrl = '<?= base_url('file/') ?>' + url;
        }

        document.getElementById('certModalImg').src = fixedUrl;
        document.getElementById('certModalTitle').textContent = title;
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeCertModal() {
        const modal = document.getElementById('certModal');
        if (modal) {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }

    // Close modal on ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCertModal();
        }
    });
</script>
<?= $this->endSection() ?>