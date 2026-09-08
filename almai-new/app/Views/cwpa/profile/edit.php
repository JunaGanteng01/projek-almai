<?= $this->extend('cwpa/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Notification -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="mb-6 p-4 bg-accent/20 border border-accent/20 rounded-2xl text-accent text-sm font-bold animate-pulse">
        <i class="fas fa-check-circle mr-2"></i> <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div class="relative mb-8">
    <!-- Banner Header -->
    <div class="h-32 md:h-48 w-full bg-gradient-to-r from-accent/20 via-black to-accent/5 rounded-3xl border border-white/5 relative overflow-hidden">
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 2px 2px, rgba(212, 175, 55, 0.2) 1px, transparent 0); background-size: 24px 24px;"></div>
    </div>
    
    <!-- Profile Photo Overlap -->
    <div class="px-6 md:px-10 -mt-12 md:-mt-16 flex flex-col md:flex-row items-end md:items-center gap-6 relative z-10">
        <div class="relative group">
            <?php
            $photo = $cwpa['photo'] ?? '';
            if (!empty($photo)) {
                if (strpos($photo, 'writable/') === 0) $photo = str_replace('writable/', '', $photo);
                $photoUrl = (strpos($photo, 'http') !== 0) ? base_url('file/' . $photo) : $photo;
            } else {
                $photoUrl = 'https://ui-avatars.com/api/?name=' . urlencode($cwpa['name']) . '&background=D4AF37&color=000&size=200';
            }
            ?>
            <img src="<?= esc($photoUrl) ?>" alt="<?= esc($cwpa['name']) ?>" class="w-24 h-24 md:w-32 md:h-32 rounded-3xl object-cover border-4 border-[#0a0a0a] shadow-2xl transition group-hover:scale-105">
            <button onclick="document.getElementById('photoInput').click()" class="absolute -bottom-2 -right-2 w-10 h-10 bg-accent text-black rounded-xl flex items-center justify-center hover:bg-white transition shadow-xl active:scale-95">
                <i class="fas fa-camera"></i>
            </button>
        </div>
        <div class="flex-grow text-right md:text-left pb-2">
            <div class="flex flex-wrap items-center justify-end md:justify-start gap-3 mb-1">
                <h2 class="text-2xl md:text-3xl font-black text-white tracking-tighter">Edit Profil</h2>
            </div>
            <div class="flex flex-wrap justify-end md:justify-start gap-4">
                <a href="<?= base_url('cwpa/dashboard/profile') ?>" class="text-gray-500 text-[10px] md:text-xs uppercase font-black tracking-widest hover:text-white transition"><i class="fas fa-arrow-left mr-1"></i> Kembali ke Profile</a>
            </div>
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">
    <!-- Left: Instructions/Tips -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-[#111] border border-white/10 rounded-3xl p-6">
            <h4 class="text-[10px] uppercase font-black tracking-widest text-accent mb-6">Tips Profil</h4>
            <div class="space-y-6">
                <div class="flex gap-4">
                    <div class="w-8 h-8 shrink-0 rounded-lg bg-white/5 flex items-center justify-center text-accent">1</div>
                    <p class="text-xs text-gray-400 leading-relaxed font-bold">Gunakan <span class="text-white">Nama Lengkap</span> asli untuk meningkatkan kepercayaan klien.</p>
                </div>
                <div class="flex gap-4">
                    <div class="w-8 h-8 shrink-0 rounded-lg bg-white/5 flex items-center justify-center text-accent">2</div>
                    <p class="text-xs text-gray-400 leading-relaxed font-bold">Tulis <span class="text-white">Bio</span> yang fokus pada hasil dan gaya trading Anda.</p>
                </div>
                <div class="flex gap-4">
                    <div class="w-8 h-8 shrink-0 rounded-lg bg-white/5 flex items-center justify-center text-accent">3</div>
                    <p class="text-xs text-gray-400 leading-relaxed font-bold">Sertakan link <span class="text-white">MQL5</span> jika Anda ingin menunjukkan portofolio live.</p>
                </div>
            </div>
        </div>

        <div class="bg-black/40 border border-white/5 rounded-3xl p-6 p-y-8 text-center">
            <i class="fas fa-shield-alt text-gray-700 text-3xl mb-4"></i>
            <p class="text-[9px] uppercase font-black tracking-widest text-gray-500">Data Anda Aman</p>
            <p class="text-[10px] text-gray-600 mt-2 font-bold leading-relaxed px-4">Kami melindungi data pribadi Anda dan hanya menampilkan apa yang Anda izinkan di profil publik.</p>
        </div>
    </div>

    <!-- Right: Edit Form -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-[#111] border border-white/10 rounded-3xl p-6 md:p-8 shadow-2xl">
            <h3 class="text-xl font-black text-white tracking-widest uppercase mb-8 border-b border-white/5 pb-4"><i class="fas fa-user-edit text-accent mr-3"></i>Formulir Perubahan</h3>

            <form action="<?= base_url('cwpa/dashboard/profile/update') ?>" method="post" class="space-y-6">
                <?= csrf_field() ?>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] uppercase font-black tracking-widest text-gray-500 mb-2">Nama Lengkap</label>
                        <input type="text" name="name" value="<?= esc($cwpa['name']) ?>" required
                            class="w-full px-5 py-4 bg-black border border-white/10 rounded-2xl focus:border-accent focus:outline-none text-sm text-white transition placeholder-gray-800">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-black tracking-widest text-gray-500 mb-2">Batch Angkatan</label>
                        <input type="text" name="batch" value="<?= esc($cwpa['batch'] ?? '') ?>" placeholder="e.g. Batch 12"
                            class="w-full px-5 py-4 bg-black border border-white/10 rounded-2xl focus:border-accent focus:outline-none text-sm text-white transition placeholder-gray-800">
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] uppercase font-black tracking-widest text-gray-500 mb-2">Email Address</label>
                        <input type="email" name="email" value="<?= esc($cwpa['email']) ?>" required
                            class="w-full px-5 py-4 bg-black border border-white/10 rounded-2xl focus:border-accent focus:outline-none text-sm text-white transition placeholder-gray-800">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-black tracking-widest text-gray-500 mb-2">WhatsApp / Phone</label>
                        <input type="text" name="phone" value="<?= esc($cwpa['phone'] ?? '') ?>" placeholder="0812..."
                            class="w-full px-5 py-4 bg-black border border-white/10 rounded-2xl focus:border-accent focus:outline-none text-sm text-white transition placeholder-gray-800">
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-[10px] uppercase font-black tracking-widest text-gray-500 mb-2">Specialty / Keahlian</label>
                        <select name="specialty" 
                            class="w-full px-5 py-4 bg-black border border-white/10 rounded-2xl focus:border-accent focus:outline-none text-sm text-white transition cursor-pointer appearance-none">
                            <option value="">Pilih Keahlian Utama</option>
                            <?php foreach ($specialties as $spec): ?>
                                <option value="<?= esc($spec) ?>" <?= ($cwpa['specialty'] ?? '') === $spec ? 'selected' : '' ?>><?= esc($spec) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-black tracking-widest text-gray-500 mb-2">Pengalaman (Tahun)</label>
                        <input type="text" name="experience" value="<?= esc($cwpa['experience'] ?? '') ?>" placeholder="e.g. 5+ Years"
                            class="w-full px-5 py-4 bg-black border border-white/10 rounded-2xl focus:border-accent focus:outline-none text-sm text-white transition placeholder-gray-800">
                    </div>
                </div>

                <div class="grid lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-[10px] uppercase font-black tracking-widest text-gray-500 mb-2">Instagram</label>
                        <div class="relative">
                            <span class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-500 font-bold">@</span>
                            <input type="text" name="instagram" value="<?= esc($cwpa['instagram'] ?? '') ?>" placeholder="username"
                                class="w-full pl-10 pr-5 py-4 bg-black border border-white/10 rounded-2xl focus:border-accent focus:outline-none text-sm text-white transition">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-black tracking-widest text-gray-500 mb-2">YouTube Channel ID</label>
                        <input type="text" name="youtube" value="<?= esc($cwpa['youtube'] ?? '') ?>" placeholder="e.g. UCJGrPUjY8SnuaZyA6pyltAg"
                            class="w-full px-5 py-4 bg-black border border-white/10 rounded-2xl focus:border-accent focus:outline-none text-sm text-white transition">
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-black tracking-widest text-gray-500 mb-2">MQL5 Signal URL</label>
                        <input type="text" name="mql5_widget_url" value="<?= esc($cwpa['mql5_widget_url'] ?? '') ?>" placeholder="https://www.mql5.com/..."
                            class="w-full px-5 py-4 bg-black border border-white/10 rounded-2xl focus:border-accent focus:outline-none text-sm text-white transition">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] uppercase font-black tracking-widest text-gray-500 mb-2">Bio Singkat (Deskripsi Profil)</label>
                    <textarea name="bio" rows="4" placeholder="Tuliskan pengalaman atau moto trading Anda..."
                        class="w-full px-5 py-4 bg-black border border-white/10 rounded-2xl focus:border-accent focus:outline-none text-sm text-white transition resize-none"><?= esc($cwpa['bio'] ?? '') ?></textarea>
                </div>

                <div>
                    <label class="block text-[10px] uppercase font-black tracking-widest text-gray-500 mb-2">Sertifikasi Lainnya (Satu per baris)</label>
                    <textarea name="certifications" rows="3" placeholder="Contoh:&#10;Certified Technical Analyst&#10;Professional Fund Manager"
                        class="w-full px-5 py-4 bg-black border border-white/10 rounded-2xl focus:border-accent focus:outline-none text-sm text-white transition resize-none font-mono"><?= esc($cwpa['certifications'] ?? '') ?></textarea>
                </div>

                <!-- Account Security -->
                <div class="pt-6 border-t border-white/5">
                    <h4 class="text-[10px] uppercase font-black tracking-widest text-accent mb-6"><i class="fas fa-shield-alt mr-2"></i> Pengaturan Keamanan</h4>
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] uppercase font-black tracking-widest text-gray-500 mb-2">Ganti Password (Opsional)</label>
                            <input type="password" name="password" placeholder="Isi hanya jika ingin mengganti"
                                class="w-full px-5 py-4 bg-black border border-white/10 rounded-2xl focus:border-accent focus:outline-none text-sm text-white transition">
                            <p class="text-[9px] text-gray-600 mt-2 italic font-bold">Kosongkan jika tidak ingin mengubah password.</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-6">
                    <a href="<?= base_url('cwpa/dashboard/profile') ?>" class="text-[10px] font-black uppercase tracking-widest text-gray-500 hover:text-white transition">
                        Batal
                    </a>
                    <button type="submit" class="px-12 py-4 bg-accent text-black font-black text-xs uppercase tracking-widest rounded-2xl hover:bg-white transition shadow-xl shadow-accent/10 active:scale-95">
                        <i class="fas fa-save mr-2"></i> Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Hidden Photo Upload Form -->
<form action="<?= base_url('cwpa/dashboard/profile/photo') ?>" method="post" enctype="multipart/form-data" id="photoForm" class="hidden">
    <?= csrf_field() ?>
    <input type="file" name="photo" id="photoInput" accept="image/*" onchange="document.getElementById('photoForm').submit()">
</form>

<?= $this->endSection() ?>
