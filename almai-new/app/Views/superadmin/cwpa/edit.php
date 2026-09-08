<?php
$this->setVar('pageTitle', 'Edit CWPA');
$this->setVar('pageSubtitle', esc($cwpa['name'] ?? 'Kandidat'));
?>
<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="w-full max-w-full mx-auto space-y-6">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm">
        <a href="<?= base_url('superadmin/cwpa') ?>" class="text-gray-400 hover:text-accent transition flex items-center gap-1">
            <i class="fas fa-arrow-left"></i> CWPA
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

    <form id="mainEditForm" action="<?= base_url('superadmin/cwpa/update/' . $cwpa['id']) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="photo" value="<?= esc($cwpa['photo']) ?>">

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- MAIN CONTENT COLUMN (LEFT) -->
            <div class="lg:col-span-9 space-y-6">

                <!-- MAIN FORM CARD -->
                <div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
                    <div class="p-6 md:p-8 space-y-8">

                        <!-- Section: Basic Info + Foto Profil -->
                        <div>
                            <h3 class="font-bold mb-4 flex items-center gap-2 text-white">
                                <i class="fas fa-user text-accent"></i> Informasi Dasar
                            </h3>
                            <div class="flex flex-col md:flex-row gap-6 md:gap-8">
                                <!-- Foto Profil (di dalam Informasi Dasar) -->
                                <div class="flex-shrink-0">
                                    <label class="block text-sm text-gray-400 mb-2">Foto Profil</label>
                                    <div class="relative w-28 h-28 rounded-xl border-2 border-dashed border-white/20 overflow-hidden bg-black flex items-center justify-center group hover:border-accent transition-colors">
                                        <?php
                                        $photoUrl = $cwpa['photo'];
                                        if (!empty($photoUrl)) {
                                            if (strpos($photoUrl, 'uploads/') === 0) {
                                                $photoUrl = base_url('file/' . $photoUrl);
                                            } elseif (!filter_var($photoUrl, FILTER_VALIDATE_URL)) {
                                                $photoUrl = base_url($photoUrl);
                                            }
                                        }
                                        ?>
                                        <img id="previewHelper" src="<?= esc($photoUrl) ?>" class="<?= $cwpa['photo'] ? '' : 'hidden' ?> w-full h-full object-cover">
                                        <div id="placeholderHelper" class="<?= $cwpa['photo'] ? 'hidden' : '' ?>">
                                            <i class="fas fa-image text-2xl text-gray-600"></i>
                                        </div>
                                        <input type="file" name="photo_file" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer" onchange="previewImage(this)">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">JPG, PNG. Maks. 2MB</p>
                                </div>
                                <!-- Field teks -->
                                <div class="flex-1 min-w-0 space-y-4">
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                                        <input type="text" name="name" value="<?= old('name', $cwpa['name']) ?>" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 text-white focus:border-accent focus:ring-1 focus:ring-accent outline-none transition" placeholder="Masukkan nama lengkap">
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm text-gray-400 mb-2">Specialty <span class="text-red-500">*</span></label>
                                            <select name="specialty" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 text-white focus:border-accent focus:ring-1 focus:ring-accent outline-none transition appearance-none cursor-pointer">
                                                <option value="">Pilih Specialty</option>
                                                <option value="Gold Specialist" <?= (isset($cwpa['specialty']) && $cwpa['specialty'] === 'Gold Specialist') ? 'selected' : '' ?>>Gold Specialist</option>
                                                <option value="Forex Specialist" <?= (isset($cwpa['specialty']) && $cwpa['specialty'] === 'Forex Specialist') ? 'selected' : '' ?>>Forex Specialist</option>
                                                <option value="Crypto Specialist" <?= (isset($cwpa['specialty']) && $cwpa['specialty'] === 'Crypto Specialist') ? 'selected' : '' ?>>Crypto Specialist</option>
                                                <option value="Stock Specialist" <?= (isset($cwpa['specialty']) && $cwpa['specialty'] === 'Stock Specialist') ? 'selected' : '' ?>>Stock Specialist</option>
                                                <option value="Index Specialist" <?= (isset($cwpa['specialty']) && $cwpa['specialty'] === 'Index Specialist') ? 'selected' : '' ?>>Index Specialist</option>
                                                <option value="EA Specialist" <?= (isset($cwpa['specialty']) && $cwpa['specialty'] === 'EA Specialist') ? 'selected' : '' ?>>EA Specialist</option>
                                                <option value="AI Specialist" <?= (isset($cwpa['specialty']) && $cwpa['specialty'] === 'AI Specialist') ? 'selected' : '' ?>>AI Specialist</option>
                                                <option value="Propfirm Specialist" <?= (isset($cwpa['specialty']) && $cwpa['specialty'] === 'Propfirm Specialist') ? 'selected' : '' ?>>Propfirm Specialist</option>
                                                <option value="Risk Management" <?= (isset($cwpa['specialty']) && $cwpa['specialty'] === 'Risk Management') ? 'selected' : ''?>>Risk Management</option>
                                                <option value="AMANDANA" <?= (isset($cwpa['specialty']) && $cwpa['specialty'] === 'AMANDANA') ? 'selected' : ''?>>AMANDANA</option>
                                                <option value="METAVULUS" <?= (isset($cwpa['specialty']) && $cwpa['specialty'] === 'METAVULUS') ? 'selected' : ''?>>METAVULUS</option>
                                                <option value="REPUBLIC" <?= (isset($cwpa['specialty']) && $cwpa['specialty'] === 'REPUBLIC') ? 'selected' : ''?>>REPUBLIC</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-400 mb-2">Institution / University</label>
                                            <input type="text" name="university" value="<?= old('university', $cwpa['university']) ?>" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 text-white focus:border-accent focus:ring-1 focus:ring-accent outline-none transition" placeholder="Contoh: Universitas Indonesia">
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm text-gray-400 mb-2">Pengalaman</label>
                                            <input type="text" name="experience" value="<?= old('experience', $cwpa['experience'] ?? '') ?>" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 text-white focus:border-accent focus:outline-none transition" placeholder="Contoh: 5 Tahun">
                                        </div>
                                        <div class="hidden">
                                            <label class="block text-sm text-gray-400 mb-2">Nomor Batch</label>
                                            <input type="text" name="batch" value="<?= old('batch', $cwpa['batch']) ?>" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 text-white focus:border-accent focus:outline-none transition">
                                        </div>
                                        <div>
                                            <label class="block text-sm text-gray-400 mb-2">Akun User Terkait</label>
                                            <div class="relative">
                                                <select name="user_id" class="w-full bg-black border border-white/20 rounded-xl pl-4 pr-10 py-3 text-white focus:border-accent outline-none transition appearance-none cursor-pointer">
                                                    <option value="">-- Hubungkan Akun --</option>
                                                    <?php
                                                    $userModel = new \App\Models\UserModel();
                                                    $users = $userModel->orderBy('name', 'ASC')->findAll();
                                                    foreach ($users as $user):
                                                    ?>
                                                        <option value="<?= $user['id'] ?>" <?= old('user_id', $cwpa['user_id'] ?? '') == $user['id'] ? 'selected' : '' ?>>
                                                            <?= esc($user['name']) ?> (<?= esc($user['email']) ?>)
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 text-xs pointer-events-none"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Profil Singkat (Bio)</label>
                                        <textarea name="bio" rows="3" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 text-white focus:border-accent outline-none transition" placeholder="Tulis bio singkat..."><?= old('bio', $cwpa['bio'] ?? '') ?></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Sertifikasi (Format JSON: ["A", "B"])</label>
                                        <input type="text" name="certifications" value='<?= old('certifications', $cwpa['certifications'] ?? '["BAPPEBTI", "LSP PBK", "OJK"]') ?>' class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 text-white focus:border-accent outline-none transition" placeholder='["BAPPEBTI", "LSP PBK", "OJK"]'>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="h-px bg-white/10"></div>

                        <!-- Section: Eksistensi Digital -->
                        <div>
                            <h3 class="font-bold mb-4 flex items-center gap-2 text-white">
                                <i class="fas fa-share-alt text-accent w-5 text-center"></i> Eksistensi Digital
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">Instagram</label>
                                    <div class="relative flex">
                                        <span class="inline-flex w-12 flex-shrink-0 items-center justify-center rounded-l-xl border border-r-0 border-white/20 bg-white/5 text-gray-400">
                                            <i class="fab fa-instagram text-lg"></i>
                                        </span>
                                        <input type="text" name="instagram" value="<?= old('instagram', $cwpa['instagram'] ?? '') ?>" class="min-w-0 flex-1 rounded-r-xl rounded-l-none border border-white/20 bg-black py-3 pr-4 pl-3 text-white focus:border-accent focus:outline-none transition" placeholder="@username atau https://instagram.com/username/">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">YouTube Channel ID</label>
                                    <div class="relative flex">
                                        <span class="inline-flex w-12 flex-shrink-0 items-center justify-center rounded-l-xl border border-r-0 border-white/20 bg-white/5 text-gray-400">
                                            <i class="fab fa-youtube text-lg"></i>
                                        </span>
                                        <input type="text" name="youtube" value="<?= old('youtube', $cwpa['youtube'] ?? '') ?>" class="min-w-0 flex-1 rounded-r-xl rounded-l-none border border-white/20 bg-black py-3 pr-4 pl-3 text-white focus:border-accent focus:outline-none transition" placeholder="Channel ID">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">TikTok Username</label>
                                    <div class="relative flex">
                                        <span class="inline-flex w-12 flex-shrink-0 items-center justify-center rounded-l-xl border border-r-0 border-white/20 bg-white/5 text-gray-400">
                                            <i class="fab fa-tiktok text-lg"></i>
                                        </span>
                                        <input type="text" name="tiktok" value="<?= old('tiktok', $cwpa['tiktok'] ?? '') ?>" class="min-w-0 flex-1 rounded-r-xl rounded-l-none border border-white/20 bg-black py-3 pr-4 pl-3 text-white focus:border-accent focus:outline-none transition" placeholder="@username">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">TikTok SecUid</label>
                                    <div class="relative flex">
                                        <span class="inline-flex w-12 flex-shrink-0 items-center justify-center rounded-l-xl border border-r-0 border-white/20 bg-white/5 text-gray-400">
                                            <i class="fas fa-key text-lg"></i>
                                        </span>
                                        <input type="text" name="tiktok_secuid" value="<?= old('tiktok_secuid', $cwpa['tiktok_secuid'] ?? '') ?>" class="min-w-0 flex-1 rounded-r-xl rounded-l-none border border-white/20 bg-black py-3 pr-4 pl-3 text-white focus:border-accent focus:outline-none transition" placeholder="MS4wLjABAAAA...">
                                    </div>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm text-gray-400 mb-2">MQL5 Signal URL</label>
                                    <div class="relative flex">
                                        <span class="inline-flex w-12 flex-shrink-0 items-center justify-center rounded-l-xl border border-r-0 border-white/20 bg-white/5 text-gray-400">
                                            <i class="fas fa-chart-line text-lg"></i>
                                        </span>
                                        <input type="url" name="mql5_widget_url" value="<?= old('mql5_widget_url', $cwpa['mql5_widget_url'] ?? '') ?>" class="min-w-0 flex-1 rounded-r-xl rounded-l-none border border-white/20 bg-black py-3 pr-4 pl-3 text-white focus:border-accent focus:outline-none transition" placeholder="https://www.mql5.com/en/signals/...">
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Gunakan URL halaman signal MQL5 lengkap.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- CERTIFICATES SECTION -->
                <div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
                    <div class="border-b border-white/10 px-6 md:px-8 py-4">
                        <h3 class="font-bold text-white flex items-center gap-2">
                            <i class="fas fa-graduation-cap text-accent"></i> Dokumen Sertifikasi
                        </h3>
                        <p class="text-gray-500 text-xs mt-1">Kelengkapan dokumen fase bimbingan</p>
                    </div>

                    <div class="divide-y divide-white/5">
                        <?php
                        $phaseCertificates = [];
                        if (!empty($cwpa['phase_certificates'])) {
                            $phaseCertificates = is_string($cwpa['phase_certificates']) ? json_decode($cwpa['phase_certificates'], true) : $cwpa['phase_certificates'];
                        }
                        
                        $phaseCertificateNumbers = [];
                        if (!empty($cwpa['phase_certificate_numbers'])) {
                            $phaseCertificateNumbers = is_string($cwpa['phase_certificate_numbers']) ? json_decode($cwpa['phase_certificate_numbers'], true) : $cwpa['phase_certificate_numbers'];
                        }
                        
                        $phases = \App\Models\CwpaModel::PHASES;
                        foreach ($phases as $num => $title):
                            $hasCert = isset($phaseCertificates[$num]) && !empty($phaseCertificates[$num]);
                        ?>
                            <div class="hover:bg-white/[0.02] transition-colors group">
                                <div class="px-6 md:px-8 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-8 h-8 rounded-lg <?= $hasCert ? 'bg-accent/20 text-accent border border-accent/30' : 'bg-white/5 text-gray-500 border border-white/10' ?> flex items-center justify-center font-bold text-xs shrink-0">
                                            <?= $num ?>
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-300 group-hover:text-white transition"><?= esc($title) ?></h4>
                                        </div>
                                        <?php if ($hasCert): ?>
                                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded text-xs font-medium bg-accent/20 text-accent border border-accent/30">
                                                <i class="fas fa-check-circle"></i> Ada
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="flex items-center gap-2 pl-12 sm:pl-0">
                                        <button type="button" onclick="document.getElementById('phaseCert<?= $num ?>').click()" class="px-3 py-2 rounded-xl border border-white/20 bg-white/5 text-gray-300 hover:text-white hover:bg-white/10 text-xs font-medium transition flex items-center gap-2">
                                            <i class="fas <?= $hasCert ? 'fa-pen' : 'fa-upload' ?>"></i>
                                            <?= $hasCert ? 'Ubah' : 'Upload' ?>
                                        </button>

                                        <?php if ($hasCert): ?>
                                            <button type="button" onclick="viewCertificate('<?= esc($phaseCertificates[$num], 'js') ?>', '<?= esc($title, 'js') ?>')" class="w-9 h-9 rounded-xl border border-white/10 bg-black text-gray-400 hover:text-white hover:bg-white/10 flex items-center justify-center transition">
                                                <i class="fas fa-eye text-xs"></i>
                                            </button>
                                            <button type="button" onclick="removePhaseCert(<?= $num ?>)" class="w-9 h-9 rounded-xl border border-white/10 bg-black text-red-400 hover:text-white hover:bg-red-500/20 flex items-center justify-center transition">
                                                <i class="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>

                                    <div class="hidden">
                                        <input type="file" name="phase_cert_<?= $num ?>" id="phaseCert<?= $num ?>" accept="image/*" class="hidden" onchange="previewPhaseCert(<?= $num ?>, this)">
                                        <?php if ($hasCert): ?>
                                            <input type="hidden" name="existing_cert_<?= $num ?>" value="<?= esc($phaseCertificates[$num]) ?>">
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- Input Nomor Sertifikat -->
                                <div class="px-6 md:px-8 pb-4 ml-12">
                                    <label class="text-xs text-gray-400 mb-1 block">Nomor Sertifikat</label>
                                    <input type="text" name="phase_cert_number_<?= $num ?>" value="<?= esc($phaseCertificateNumbers[$num] ?? '') ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-2 text-white text-xs focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors" placeholder="Masukkan nomor sertifikat...">
                                </div>
                                <div id="certPreviewRow<?= $num ?>" class="hidden px-6 md:px-8 pb-4 ml-12">
                                    <div class="relative w-32 h-20 rounded-xl overflow-hidden border border-white/10 group">
                                        <img id="certPreviewImg<?= $num ?>" src="" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                            <p class="text-xs text-white font-medium">New File</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>

            <!-- SIDEBAR COLUMN (RIGHT) -->
            <div class="lg:col-span-3 space-y-6">

                <!-- Status Card -->
                <div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
                    <div class="p-4 border-b border-white/10">
                        <h3 class="font-bold text-white text-sm">Status Kandidat</h3>
                    </div>
                    <div class="p-4">
                        <div class="relative">
                            <select name="status" class="w-full bg-black border border-white/20 rounded-xl px-4 py-2.5 text-white focus:border-accent outline-none transition text-sm appearance-none cursor-pointer">
                                <option value="active" <?= old('status', $cwpa['status']) == 'active' ? 'selected' : '' ?>>Published (Active)</option>
                                <option value="inactive" <?= old('status', $cwpa['status']) == 'inactive' ? 'selected' : '' ?>>Draft (Inactive)</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 text-xs pointer-events-none"></i>
                        </div>
                    </div>
                </div>

                <!-- Phase Card -->
                <div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
                    <div class="p-4 border-b border-white/10">
                        <h3 class="font-bold text-white text-sm">Fase / Progress</h3>
                    </div>
                    <div class="p-4 space-y-4">
                        <div class="relative">
                            <label class="text-xs font-medium text-gray-500 mb-1 block">Fase Saat Ini</label>
                            <select name="current_phase" class="w-full bg-black border border-white/20 rounded-xl px-4 py-2.5 text-white focus:border-accent outline-none transition text-sm appearance-none cursor-pointer">
                                <?php foreach (\App\Models\CwpaModel::PHASES as $id => $label): ?>
                                    <option value="<?= $id ?>" <?= old('current_phase', $cwpa['current_phase']) == $id ? 'selected' : '' ?>><?= esc($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 bottom-3 text-gray-500 text-xs pointer-events-none"></i>
                        </div>

                        <div>
                            <?php
                            $phaseNum = (int) ($cwpa['current_phase'] ?? 1);
                            $progressPct = $phaseNum >= 9 ? 100 : round(($phaseNum / 9) * 100);
                            ?>
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

        <!-- Aksi (paling bawah) -->
        <div class="mt-8 pt-6 border-t border-white/10">
            <div class="flex flex-col sm:flex-row gap-3 justify-end">
                <a href="<?= base_url('superadmin/cwpa') ?>" class="order-2 sm:order-1 px-6 py-3 bg-transparent border border-white/20 text-white font-medium text-sm rounded-xl hover:bg-white/5 transition text-center">
                    Kembali / Batal
                </a>
                <button type="submit" class="order-1 sm:order-2 px-6 py-3 bg-accent text-black font-bold text-sm rounded-xl hover:bg-accent/90 transition">
                    <i class="fas fa-save mr-2"></i> Simpan Perubahan
                </button>
                <?php if (($cwpa['current_phase'] ?? 0) >= 8): ?>
                    <button type="button" onclick="confirmPromotion()" class="order-3 px-6 py-3 bg-white text-black font-bold text-sm rounded-xl hover:bg-gray-100 transition flex items-center justify-center gap-2">
                        <i class="fas fa-trophy"></i> Promosikan ke WPA
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<!-- Modal: Promote -->
<div id="promoteConfirmModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
    <div class="bg-[#111] border border-white/10 rounded-2xl max-w-md w-full p-6 text-center shadow-2xl animate-fade-in-up">
        <div class="w-16 h-16 bg-accent/20 rounded-full flex items-center justify-center text-accent mx-auto mb-4">
            <i class="fas fa-trophy text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold text-white mb-2">Konfirmasi Promosi</h3>
        <p class="text-sm text-gray-400 mb-6 px-4">
            Apakah Anda yakin ingin memindahkan <span class="text-white font-semibold"><?= esc($cwpa['name']) ?></span> ke database WPA? <br>Level akun user akan diperbarui otomatis.
        </p>
        <div class="flex gap-3 justify-center">
            <button type="button" onclick="closePromoteModal()" class="px-5 py-2.5 bg-transparent border border-gray-700 text-gray-300 rounded-lg text-sm font-medium hover:bg-gray-800 transition">Batal</button>
            <button type="button" onclick="executePromotion()" class="px-5 py-2.5 bg-accent text-black font-bold rounded-lg text-sm hover:shadow-lg transition">Ya, Lanjutkan</button>
        </div>
    </div>
</div>

<!-- Modal: Certificate Preview -->
<div id="certModal" class="fixed inset-0 z-[70] hidden items-center justify-center p-4 bg-black/90 backdrop-blur-sm">
    <div class="relative w-full max-w-4xl max-h-[90vh] flex flex-col items-center">
        <button type="button" onclick="closeCertModal()" class="absolute -top-10 right-0 w-10 h-10 bg-[#111] border border-white/10 rounded-full flex items-center justify-center text-gray-400 hover:text-white transition z-10">
            <i class="fas fa-times"></i>
        </button>
        <div class="w-full flex-1 flex items-center justify-center overflow-hidden rounded-2xl border border-white/10 bg-[#111] p-4">
            <img id="certModalImg" src="" alt="Sertifikat" class="max-w-full max-h-[80vh] object-contain rounded-xl">
        </div>
        <p id="certModalTitle" class="text-center text-accent font-medium mt-4 text-sm"></p>
    </div>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('previewHelper');
                const placeholder = document.getElementById('placeholderHelper');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewPhaseCert(num, input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            const previewRow = document.getElementById('certPreviewRow' + num);
            const img = document.getElementById('certPreviewImg' + num);

            reader.onload = function(e) {
                img.src = e.target.result;
                previewRow.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removePhaseCert(num) {
        if (confirm('Tandai dokumen untuk dihapus?')) {
            const input = document.getElementById('phaseCert' + num);
            input.value = '';

            const existingInput = document.querySelector('input[name="existing_cert_' + num + '"]');
            if (existingInput) existingInput.value = '';

            const del = document.createElement('input');
            del.type = 'hidden';
            del.name = 'delete_cert_' + num;
            del.value = '1';
            input.parentElement.appendChild(del);

            alert('File ditandai hapus. Simpan perubahan untuk memproses.');
        }
    }

    function viewCertificate(url, title) {
        let modal = document.getElementById('certModal');
        let fixedUrl = url.startsWith('uploads/') ? '<?= base_url('file/') ?>' + url : url;
        document.getElementById('certModalImg').src = fixedUrl;
        document.getElementById('certModalTitle').textContent = title;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeCertModal() {
        const modal = document.getElementById('certModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    function confirmPromotion() {
        const m = document.getElementById('promoteConfirmModal');
        m.classList.remove('hidden');
        m.classList.add('flex');
    }

    function closePromoteModal() {
        const m = document.getElementById('promoteConfirmModal');
        m.classList.add('hidden');
        m.classList.remove('flex');
    }

    function executePromotion() {
        const b = event.currentTarget;
        b.disabled = true;
        b.innerHTML = '<i class="fas fa-circle-notch animate-spin mr-2"></i>';
        const f = document.createElement('form');
        f.method = 'POST';
        f.action = '<?= base_url('superadmin/cwpa/promote/' . $cwpa['id']) ?>';
        f.innerHTML = '<?= csrf_field() ?>';
        document.body.appendChild(f);
        f.submit();
    }
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeCertModal();
            closePromoteModal();
        }
    });
</script>
<style>
    .animate-fade-in-up {
        animation: fadeInUp 0.3s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
<?= $this->endSection() ?>
