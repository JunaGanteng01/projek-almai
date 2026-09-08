<?php
$this->setVar('pageTitle', 'Tambah CWPA');
$this->setVar('pageSubtitle', 'Add New CWPA Profile');
?>
<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="w-full max-w-full mx-auto space-y-6">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm">
        <a href="<?= base_url('admin/cwpa') ?>" class="text-gray-400 hover:text-accent transition flex items-center gap-1">
            <i class="fas fa-arrow-left"></i> CWPA
        </a>
        <span class="text-gray-600">/</span>
        <span class="text-gray-500">Tambah Baru</span>
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
    <?php if (session()->getFlashdata('error')): ?>
    <div class="bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl">
        <?= esc(session()->getFlashdata('error')) ?>
    </div>
    <?php endif; ?>

    <form action="<?= base_url('admin/cwpa/store') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

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
                                <!-- Foto Profil -->
                                <div class="flex-shrink-0">
                                    <label class="block text-sm text-gray-400 mb-2">Foto Profil</label>
                                    <div class="relative w-28 h-28 rounded-xl border-2 border-dashed border-white/20 overflow-hidden bg-black flex items-center justify-center group hover:border-accent transition-colors">
                                        <img id="previewHelper" src="" class="hidden w-full h-full object-cover">
                                        <div id="placeholderHelper">
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
                                        <input type="text" name="name" value="<?= old('name') ?>" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 text-white focus:border-accent focus:ring-1 focus:ring-accent outline-none transition" placeholder="Masukkan nama lengkap" required>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                         <div>
                                             <label class="block text-sm text-gray-400 mb-2">Specialty <span class="text-red-500">*</span></label>
                                             <select name="specialty" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 text-white focus:border-accent focus:ring-1 focus:ring-accent outline-none transition appearance-none cursor-pointer">
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
                                             <label class="block text-sm text-gray-400 mb-2">Institution / University</label>
                                             <input type="text" name="university" value="<?= old('university') ?>" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 text-white focus:border-accent focus:ring-1 focus:ring-accent outline-none transition" placeholder="Contoh: Universitas Indonesia">
                                         </div>
                                     </div>

                                     <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                         <div>
                                             <label class="block text-sm text-gray-400 mb-2">Pengalaman</label>
                                             <input type="text" name="experience" value="<?= old('experience') ?>" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 text-white focus:border-accent focus:outline-none transition" placeholder="Contoh: 5 Tahun">
                                         </div>
                                         <div class="hidden">
                                             <label class="block text-sm text-gray-400 mb-2">Nomor Batch</label>
                                             <input type="text" name="batch" value="<?= old('batch') ?>" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 text-white focus:border-accent focus:outline-none transition">
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
                                                         <option value="<?= $user['id'] ?>" <?= old('user_id') == $user['id'] ? 'selected' : '' ?>>
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
                                         <textarea name="bio" rows="3" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 text-white focus:border-accent outline-none transition" placeholder="Tulis bio singkat..."><?= old('bio') ?></textarea>
                                     </div>
                                     <div>
                                         <label class="block text-sm text-gray-400 mb-2">Sertifikasi (Format JSON: ["A", "B"])</label>
                                         <input type="text" name="certifications" value='<?= old('certifications', '["BAPPEBTI", "LSP PBK", "OJK"]') ?>' class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 text-white focus:border-accent outline-none transition" placeholder='["BAPPEBTI", "LSP PBK", "OJK"]'>
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
                                        <input type="text" name="instagram" value="<?= old('instagram') ?>" class="min-w-0 flex-1 rounded-r-xl rounded-l-none border border-white/20 bg-black py-3 pr-4 pl-3 text-white focus:border-accent focus:outline-none transition" placeholder="@username">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">YouTube Channel ID</label>
                                    <div class="relative flex">
                                        <span class="inline-flex w-12 flex-shrink-0 items-center justify-center rounded-l-xl border border-r-0 border-white/20 bg-white/5 text-gray-400">
                                            <i class="fab fa-youtube text-lg"></i>
                                        </span>
                                        <input type="text" name="youtube" value="<?= old('youtube') ?>" class="min-w-0 flex-1 rounded-r-xl rounded-l-none border border-white/20 bg-black py-3 pr-4 pl-3 text-white focus:border-accent focus:outline-none transition" placeholder="Channel ID">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">TikTok Username</label>
                                    <div class="relative flex">
                                        <span class="inline-flex w-12 flex-shrink-0 items-center justify-center rounded-l-xl border border-r-0 border-white/20 bg-white/5 text-gray-400">
                                            <i class="fab fa-tiktok text-lg"></i>
                                        </span>
                                        <input type="text" name="tiktok" value="<?= old('tiktok') ?>" class="min-w-0 flex-1 rounded-r-xl rounded-l-none border border-white/20 bg-black py-3 pr-4 pl-3 text-white focus:border-accent focus:outline-none transition" placeholder="@username">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">TikTok SecUid</label>
                                    <div class="relative flex">
                                        <span class="inline-flex w-12 flex-shrink-0 items-center justify-center rounded-l-xl border border-r-0 border-white/20 bg-white/5 text-gray-400">
                                            <i class="fas fa-key text-lg"></i>
                                        </span>
                                        <input type="text" name="tiktok_secuid" value="<?= old('tiktok_secuid') ?>" class="min-w-0 flex-1 rounded-r-xl rounded-l-none border border-white/20 bg-black py-3 pr-4 pl-3 text-white focus:border-accent focus:outline-none transition" placeholder="MS4wLjABAAAA...">
                                    </div>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm text-gray-400 mb-2">MQL5 Signal URL</label>
                                    <div class="relative flex">
                                        <span class="inline-flex w-12 flex-shrink-0 items-center justify-center rounded-l-xl border border-r-0 border-white/20 bg-white/5 text-gray-400">
                                            <i class="fas fa-chart-line text-lg"></i>
                                        </span>
                                        <input type="url" name="mql5_widget_url" value="<?= old('mql5_widget_url') ?>" class="min-w-0 flex-1 rounded-r-xl rounded-l-none border border-white/20 bg-black py-3 pr-4 pl-3 text-white focus:border-accent focus:outline-none transition" placeholder="https://www.mql5.com/en/signals/...">
                                    </div>
                                </div>
                            </div>
                        </div>

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
                                <option value="active" <?= old('status', 'active') === 'active' ? 'selected' : '' ?>>Published (Active)</option>
                                <option value="inactive" <?= old('status') === 'inactive' ? 'selected' : '' ?>>Draft (Inactive)</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 text-xs pointer-events-none"></i>
                        </div>
                    </div>
                </div>

                <!-- Fase Card -->
                <div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
                    <div class="p-4 border-b border-white/10">
                        <h3 class="font-bold text-white text-sm">Fase Bimbingan</h3>
                    </div>
                    <div class="p-4">
                        <div class="relative">
                            <label class="text-xs font-medium text-gray-500 mb-1 block">Fase Awal</label>
                            <select name="current_phase" class="w-full bg-black border border-white/20 rounded-xl px-4 py-2.5 text-white focus:border-accent outline-none transition text-sm appearance-none cursor-pointer">
                                <?php foreach (\App\Models\CwpaModel::PHASES as $id => $label): ?>
                                    <option value="<?= $id ?>" <?= old('current_phase', 1) == $id ? 'selected' : '' ?>><?= esc($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 bottom-3 text-gray-500 text-xs pointer-events-none"></i>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Aksi (paling bawah) -->
        <div class="mt-8 pt-6 border-t border-white/10">
            <div class="flex flex-col sm:flex-row gap-3 justify-end">
                <a href="<?= base_url('admin/cwpa') ?>" class="order-2 sm:order-1 px-6 py-3 bg-transparent border border-white/20 text-white font-medium text-sm rounded-xl hover:bg-white/5 transition text-center">
                    Batal
                </a>
                <button type="submit" class="order-1 sm:order-2 px-6 py-3 bg-accent text-black font-bold text-sm rounded-xl hover:bg-accent/90 transition">
                    <i class="fas fa-save mr-2"></i> Simpan CWPA
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var preview = document.getElementById('previewHelper');
                var placeholder = document.getElementById('placeholderHelper');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
<?= $this->endSection() ?>
