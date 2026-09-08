<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>
<?php
$currentKategori = '';
foreach ([
    'advokasi' => ['artikel', 'webinar', 'workshop', 'live_trade', 'pendampingan', 'profirm'],
    'expert-advisor' => ['ea'],
    'ultimate' => ['toolkit', 'private_konsultan', 'vip_member']
] as $cat => $subs) {
    if (in_array($layananType, $subs)) {
        $currentKategori = $cat;
        break;
    }
}

// Decode JSON fields safely
$compatibility = (isset($layanan['compatibility']) && is_string($layanan['compatibility'])) ? json_decode($layanan['compatibility'], true) : ($layanan['compatibility'] ?? []);
$features = (isset($layanan['features']) && is_string($layanan['features'])) ? json_decode($layanan['features'], true) : ($layanan['features'] ?? []);
$requirements = (isset($layanan['requirements']) && is_string($layanan['requirements'])) ? json_decode($layanan['requirements'], true) : ($layanan['requirements'] ?? []);
$benefits = (isset($layanan['benefits']) && is_string($layanan['benefits'])) ? json_decode($layanan['benefits'], true) : ($layanan['benefits'] ?? []);
$includes = (isset($layanan['includes']) && is_string($layanan['includes'])) ? json_decode($layanan['includes'], true) : ($layanan['includes'] ?? []);
?>

<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="<?= base_url('superadmin/layanan') ?>" class="p-2 hover:bg-white/10 rounded-lg">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold">Edit Layanan</h1>
            <p class="text-gray-400 text-sm"><?= esc($layanan['name'] ?? $layanan['title'] ?? '') ?></p>
        </div>
    </div>

    <div class="flex items-center justify-between mb-8 overflow-x-auto pb-4 gap-4 no-scrollbar">
        <div class="flex items-center min-w-max">
            <!-- Step Buttons -->
            <button type="button" onclick="goToStep(1)" id="step-btn-1" class="flex items-center gap-3 px-6 py-3 rounded-xl bg-accent text-black font-bold transition-all whitespace-nowrap">
                <span class="w-8 h-8 rounded-full bg-black/20 flex items-center justify-center text-sm">1</span>
                Informasi
            </button>
            <div class="w-12 h-px bg-white/10 mx-2"></div>
            <button type="button" onclick="goToStep(2)" id="step-btn-2" class="flex items-center gap-3 px-6 py-3 rounded-xl bg-white/5 text-gray-500 font-bold transition-all whitespace-nowrap">
                <span class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-sm">2</span>
                Aktivasi EA
            </button>
            <div class="w-12 h-px bg-white/10 mx-2"></div>
            <button type="button" onclick="goToStep(3)" id="step-btn-3" class="flex items-center gap-3 px-6 py-3 rounded-xl bg-white/5 text-gray-500 font-bold transition-all whitespace-nowrap">
                <span class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-sm">3</span>
                Pelatihan
            </button>
            <div class="w-12 h-px bg-white/10 mx-2"></div>
            <button type="button" onclick="goToStep(4)" id="step-btn-4" class="flex items-center gap-3 px-6 py-3 rounded-xl bg-white/5 text-gray-500 font-bold transition-all whitespace-nowrap">
                <span class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-sm">4</span>
                Selesai
            </button>
        </div>
    </div>

    <form action="<?= base_url('superadmin/layanan/update/' . $layananType . '/' . $layanan['id']) ?>" method="POST" enctype="multipart/form-data" id="wizardForm">
        <?= csrf_field() ?>
        
        <!-- STEP 1: INFORMASI -->
        <div id="step-content-1" class="space-y-6">
            <div class="bg-[#111] border border-white/10 rounded-xl p-6">
                <h3 class="font-bold mb-6 flex items-center gap-2">
                    <i class="fas fa-info-circle text-accent"></i>
                    Informasi Layanan
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Kategori <span class="text-red-400">*</span></label>
                        <select name="kategori" id="kategoriSelect" required class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3" disabled>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="advokasi" <?= $currentKategori === 'advokasi' ? 'selected' : '' ?>>Advokasi</option>
                            <option value="expert-advisor" <?= $currentKategori === 'expert-advisor' ? 'selected' : '' ?>>Expert Advisor</option>
                            <option value="ultimate" <?= $currentKategori === 'ultimate' ? 'selected' : '' ?>>Ultimate</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Subcategory <span class="text-red-400">*</span></label>
                        <select name="subcategory" id="subcategorySelect" required class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3" disabled>
                            <option value="<?= $layananType ?>"><?= ucfirst(str_replace('_', ' ', $layananType)) ?></option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Jenis Modul Laporan <span class="text-xs text-gray-500">(Opsional)</span></label>
                        <select name="jenis_modul" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                            <option value="">-- Tidak Tampil di Laporan --</option>
                            <option value="Seminar FGD" <?= ($layanan['jenis_modul'] ?? '') === 'Seminar FGD' ? 'selected' : '' ?>>Seminar FGD</option>
                            <option value="Pelatihan Simulasi" <?= ($layanan['jenis_modul'] ?? '') === 'Pelatihan Simulasi' ? 'selected' : '' ?>>Pelatihan Simulasi</option>
                            <option value="Signal" <?= ($layanan['jenis_modul'] ?? '') === 'Signal' ? 'selected' : '' ?>>Signal</option>
                            <option value="Konsultasi" <?= ($layanan['jenis_modul'] ?? '') === 'Konsultasi' ? 'selected' : '' ?>>Konsultasi</option>
                            <option value="Expert Advisor" <?= ($layanan['jenis_modul'] ?? '') === 'Expert Advisor' ? 'selected' : '' ?>>Expert Advisor</option>
                            <option value="Kegiatan Lainnya" <?= ($layanan['jenis_modul'] ?? '') === 'Kegiatan Lainnya' ? 'selected' : '' ?>>Kegiatan Lainnya</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Nama/Judul <span class="text-red-400">*</span></label>
                            <input type="text" name="name" value="<?= esc($layanan['name'] ?? $layanan['title'] ?? '') ?>" required class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Slug</label>
                            <input type="text" name="slug" value="<?= esc($layanan['slug'] ?? '') ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Spesialis</label>
                        <select name="specialist" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                            <option value="">-- Pilih Spesialis --</option>
                            <?php foreach (['Gold', 'Forex', 'Crypto', 'Stock', 'Index', 'EA', 'AI', 'Profirm', 'Algorithm', 'Technical Analyst', 'Risk Management'] as $spec): ?>
                                <option value="<?= $spec ?>" <?= ($layanan['specialist'] ?? '') === $spec ? 'selected' : '' ?>><?= $spec ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Deskripsi</label>
                        <textarea name="description" id="editor_description" rows="4" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"><?= esc($layanan['description'] ?? '') ?></textarea>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Layanan Utama</label>
                        <textarea name="layanan_utama" rows="3" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3" placeholder="Sebutkan 3-5 layanan utama..."><?= esc($layanan['layanan_utama'] ?? '') ?></textarea>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Fitur Unggulan (JSON Format)</label>
                        <textarea name="fitur_unggulan" rows="5" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3 font-mono text-sm" placeholder='[{"name": "Software", "link": "https://..."}]'><?= esc($layanan['fitur_unggulan'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Detail Event (Conditional) -->
            <div id="eventFields" class="hidden bg-[#111] border border-white/10 rounded-xl p-6">
                <h3 class="font-bold mb-4">Detail Event</h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Tanggal & Waktu Mulai</label>
                            <input type="datetime-local" name="event_date" value="<?= !empty($layanan['event_date']) ? date('Y-m-d\TH:i', strtotime($layanan['event_date'])) : '' ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Tanggal & Waktu Selesai</label>
                            <input type="datetime-local" name="event_end_date" value="<?= !empty($layanan['event_end_date']) ? date('Y-m-d\TH:i', strtotime($layanan['event_end_date'])) : '' ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Max Peserta</label>
                            <input type="number" name="max_participants" value="<?= $layanan['max_participants'] ?? 0 ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Jumlah Sesi</label>
                            <input type="number" name="total_sessions" value="<?= $layanan['total_sessions'] ?? 1 ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Lokasi / Info Jadwal</label>
                            <input type="text" name="location" value="<?= esc($layanan['location'] ?? '') ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        </div>
                    </div>

                    <!-- Recurring -->
                    <div class="border-t border-white/10 pt-4 mt-4">
                        <label class="flex items-center gap-2 mb-4 cursor-pointer">
                            <input type="checkbox" name="is_recurring" id="isRecurring" value="1" <?= !empty($layanan['is_recurring']) ? 'checked' : '' ?> class="w-5 h-5 rounded bg-[#0a0a0a] border-white/10 text-accent">
                            <span class="font-medium">Jadwal Rutin (Recurring)</span>
                        </label>
                        <div id="recurringOptions" class="<?= !empty($layanan['is_recurring']) ? '' : 'hidden' ?> grid grid-cols-1 md:grid-cols-3 gap-4 pl-7">
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Frekuensi</label>
                                <select name="recurring_frequency" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                                    <option value="weekly" <?= ($layanan['recurring_frequency'] ?? '') === 'weekly' ? 'selected' : '' ?>>Mingguan</option>
                                    <option value="daily" <?= ($layanan['recurring_frequency'] ?? '') === 'daily' ? 'selected' : '' ?>>Harian</option>
                                    <option value="monthly" <?= ($layanan['recurring_frequency'] ?? '') === 'monthly' ? 'selected' : '' ?>>Bulanan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Hari</label>
                                <select name="recurring_day" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                                    <?php foreach (['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $day): ?>
                                        <option value="<?= $day ?>" <?= ($layanan['recurring_day'] ?? '') === $day ? 'selected' : '' ?>><?= $day ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Jam</label>
                                <input type="time" name="recurring_time" value="<?= $layanan['recurring_time'] ?? '' ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Tools (Conditional) -->
            <div id="toolsFields" class="hidden bg-[#111] border border-white/10 rounded-xl p-6">
                <h3 class="font-bold mb-4">Detail Software/Tool</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Versi</label>
                        <input type="text" name="version" value="<?= esc($layanan['version'] ?? '') ?>" placeholder="1.0.0" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">URL Dokumentasi</label>
                        <input type="url" name="documentation_url" value="<?= esc($layanan['documentation_url'] ?? '') ?>" placeholder="https://..." class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm text-gray-400 mb-2">Changelog</label>
                    <textarea name="changelog" rows="3" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"><?= esc($layanan['changelog'] ?? '') ?></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Features</label>
                        <textarea name="features" rows="4" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"><?= implode("\n", $features ?? []) ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Requirements</label>
                        <textarea name="requirements" rows="4" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"><?= implode("\n", $requirements ?? []) ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Detail Subscription (Conditional) -->
            <div id="subscriptionFields" class="hidden bg-[#111] border border-white/10 rounded-xl p-6">
                <h3 class="font-bold mb-4">Detail Langganan/Konsultasi</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Durasi (Hari)</label>
                        <input type="number" name="duration_days" value="<?= $layanan['duration_days'] ?? 30 ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Max Slot Peserta</label>
                        <input type="number" name="max_slots" value="<?= $layanan['max_slots'] ?? 0 ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Info Jadwal</label>
                        <input type="text" name="schedule_info" value="<?= esc($layanan['schedule_info'] ?? '') ?>" placeholder="Senin - Jumat, 09:00 - 17:00" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Benefits</label>
                        <textarea name="benefits" rows="4" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"><?= implode("\n", $benefits ?? []) ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Includes</label>
                        <textarea name="includes" rows="4" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"><?= implode("\n", $includes ?? []) ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Detail Artikel (Conditional) -->
            <div id="artikelFields" class="hidden bg-[#111] border border-white/10 rounded-xl p-6">
                <h3 class="font-bold mb-4">Detail Artikel/E-Book</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Excerpt (Ringkasan Singkat)</label>
                        <textarea name="excerpt" rows="2" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"><?= esc($layanan['excerpt'] ?? '') ?></textarea>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Konten Lengkap</label>
                        <textarea name="content" id="editor_content" rows="6" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"><?= esc($layanan['content'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Primary File Upload (Conditional) -->
            <div id="fileUploadField" class="hidden bg-[#111] border border-white/10 rounded-xl p-6">
                <h3 class="font-bold mb-4">File Utama Layanan</h3>
                <?php if (!empty($layanan['file_path'])): ?>
                    <div class="p-3 bg-accent/10 border border-accent/30 rounded-lg mb-4 flex items-center justify-between">
                        <p class="text-sm text-accent"><i class="fas fa-file mr-2"></i>File Saat Ini: <?= basename($layanan['file_path']) ?></p>
                        <a href="<?= base_url('file/' . $layanan['file_path']) ?>" target="_blank" class="text-xs text-accent hover:underline">Download</a>
                    </div>
                <?php endif; ?>
                <label class="block text-sm text-gray-400 mb-2">Ganti File <span id="fileTypeHint" class="text-xs text-accent"></span></label>
                <input type="file" name="layanan_file" id="layananFile" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-2 text-sm">
                <p class="text-[10px] text-gray-500 mt-2">Biarkan kosong jika tidak ingin mengganti file.</p>
            </div>

            <!-- Pricing & Partners -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-[#111] border border-white/10 rounded-xl p-6">
                    <h3 class="font-bold mb-4">Harga & Poin</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 mb-2">
                            <input type="checkbox" name="has_packages" id="hasPackages" value="1" <?= (!empty($packages) || !empty($layanan['has_packages'])) ? 'checked' : '' ?> class="w-5 h-5 rounded bg-[#0a0a0a] border-white/10 text-accent" onchange="if(typeof togglePackagesEdit === 'function') togglePackagesEdit();">
                            <label for="hasPackages" class="text-sm font-medium">Aktifkan Paket Harga</label>
                        </div>
                        
                        <div id="singlePriceField" class="<?= !empty($packages) ? 'hidden' : '' ?> space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">Harga Jual (IDR)</label>
                                    <input type="number" name="price" value="<?= $layanan['price'] ?? 0 ?>" oninput="if(document.getElementById('poinPrice')) document.getElementById('poinPrice').value = Math.floor(this.value / 100);" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">Harga Poin</label>
                                    <input type="number" name="poin_price" id="poinPrice" value="<?= $layanan['poin_price'] ?? 0 ?>" readonly class="w-full bg-black/50 text-gray-500 cursor-not-allowed border border-white/10 rounded-lg px-4 py-3">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Harga Diskon (Original)</label>
                                <input type="number" name="original_price" value="<?= $layanan['original_price'] ?? '' ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                            </div>
                        </div>

                        <div id="multiPackageField" class="<?= !empty($packages) ? '' : 'hidden' ?> space-y-4">
                            <div id="packagesContainer" class="space-y-3">
                                <?php if (!empty($packages)): ?>
                                    <?php foreach ($packages as $i => $pkg): ?>
                                        <div id="pkg-existing-<?= $i ?>" class="bg-black/30 p-3 rounded-lg border border-white/5 relative group">
                                            <button type="button" onclick="document.getElementById('pkg-existing-<?= $i ?>').remove()" class="absolute top-2 right-2 text-gray-500 hover:text-red-400 opacity-0 group-hover:opacity-100 transition"><i class="fas fa-times"></i></button>
                                            <input type="text" name="packages[<?= $i ?>][name]" value="<?= esc($pkg['name']) ?>" placeholder="Nama Paket" class="w-full bg-[#0a0a0a] border border-white/10 rounded px-3 py-2 text-sm mb-2" required>
                                            <div class="grid grid-cols-3 gap-3 mb-2">
                                                <input type="number" name="packages[<?= $i ?>][price]" value="<?= esc($pkg['price']) ?>" placeholder="Harga" class="w-full bg-[#0a0a0a] border border-white/10 rounded px-3 py-2 text-sm" required>
                                                <input type="number" name="packages[<?= $i ?>][original_price]" value="<?= esc($pkg['original_price'] ?? '') ?>" placeholder="Harga Coret" class="w-full bg-[#0a0a0a] border border-white/10 rounded px-3 py-2 text-sm">
                                                <input type="number" name="packages[<?= $i ?>][duration_days]" value="<?= esc($pkg['duration_days'] ?? '') ?>" placeholder="Durasi (Hari)" class="w-full bg-[#0a0a0a] border border-white/10 rounded px-3 py-2 text-sm">
                                            </div>
                                            <textarea name="packages[<?= $i ?>][description]" rows="2" placeholder="Deskripsi Singkat" class="w-full bg-[#0a0a0a] border border-white/10 rounded px-3 py-2 text-sm"><?= esc($pkg['description'] ?? '') ?></textarea>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                            <button type="button" onclick="addPackageRow()" class="w-full py-2 border border-dashed border-white/20 rounded-lg text-xs text-gray-400 hover:text-white transition">
                                <i class="fas fa-plus mr-1"></i> Tambah Paket
                            </button>
                        </div>
                    </div>

                    <div class="border-t border-white/10 pt-4 mt-6">
                        <h3 class="font-bold mb-1">Skema Referal</h3>
                        <p class="text-xs text-gray-500 mb-4">Atur distribusi komisi referral. Pool dihitung otomatis dari harga × %. Kedalaman distribusi tetap 8 level.</p>

                        <!-- Hidden fields — auto-calculated by JS before submit -->
                        <input type="hidden" name="referral_user_cash" id="calc_referral_user_cash" value="<?= $layanan['referral_user_cash'] ?? 0 ?>">
                        <input type="hidden" name="referral_user_poin" id="calc_referral_user_poin" value="<?= $layanan['referral_user_poin'] ?? 0 ?>">
                        <input type="hidden" name="referral_max_depth" value="8">

                        <!-- Toggle Aktifkan Referral -->
                        <?php $refActive = (float)($layanan['referral_distribution_percentage'] ?? 0) > 0; ?>
                        <div class="flex items-center gap-3 mb-4">
                            <input type="checkbox" id="enableReferral" <?= $refActive ? 'checked' : '' ?>
                                class="w-5 h-5 rounded bg-[#0a0a0a] border-white/10 text-accent cursor-pointer"
                                onchange="toggleReferralSection(this.checked)">
                            <label for="enableReferral" class="text-sm font-medium cursor-pointer">Aktifkan Skema Referral</label>
                        </div>

                        <div id="referralSection" class="<?= $refActive ? '' : 'hidden' ?>">
                            <div class="mb-4 max-w-xs">
                                <label class="block text-sm text-gray-400 mb-2">Distribusi per Level (%)</label>
                                <div class="relative">
                                    <input type="number" step="0.01" min="0" max="100" id="referral_dist_pct" name="referral_distribution_percentage"
                                        value="<?= $refActive ? ($layanan['referral_distribution_percentage'] ?? 25) : 25 ?>"
                                        class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3 pr-10"
                                        oninput="updateReferralPreview()">
                                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">%</span>
                                </div>
                                <p class="text-[10px] text-gray-600 mt-1">Berapa % dari harga yang jadi total pool referral (maks 8 level)</p>
                            </div>

                            <!-- Live Preview -->
                            <div id="referralPreviewWrap" class="bg-[#0a0a0a] border border-white/10 rounded-xl p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <p class="text-xs font-bold text-gray-300 uppercase tracking-widest flex items-center gap-2">
                                        <i class="fas fa-eye text-accent"></i> Preview Distribusi
                                    </p>
                                    <span class="text-[10px] text-gray-500">Pool = Harga × Distribusi%</span>
                                </div>
                                <div id="referralPreviewTable" class="space-y-3 text-xs">
                                    <!-- Rendered by JS -->
                                </div>
                            </div>
                        </div>

                        <!-- Hidden input saat referral dinonaktifkan -->
                        <input type="hidden" id="referral_dist_pct_disabled" name="referral_distribution_percentage" value="0" <?= $refActive ? 'disabled' : '' ?>>
                    </div>
                </div>

                <div class="bg-[#111] border border-white/10 rounded-xl p-6">
                    <h3 class="font-bold mb-4">Partner & Media</h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Pilih WPA</label>
                                <select name="wpa_id" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                                    <option value="">-- Pilih WPA --</option>
                                    <?php foreach ($wpaList as $wpa): ?>
                                        <option value="<?= $wpa['id'] ?>" <?= ($layanan['wpa_id'] ?? '') == $wpa['id'] ? 'selected' : '' ?>><?= esc($wpa['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Pilih CWPA</label>
                                <select name="cwpa_id" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                                    <option value="">-- Pilih CWPA --</option>
                                    <?php foreach ($cwpaList as $cwpa): ?>
                                        <option value="<?= $cwpa['id'] ?>" <?= ($layanan['cwpa_id'] ?? '') == $cwpa['id'] ? 'selected' : '' ?>><?= esc($cwpa['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div>
                            <?php if (!empty($layanan['thumbnail'])): ?>
                                <div class="mb-4">
                                    <p class="text-xs text-gray-500 mb-2">Thumbnail Saat Ini:</p>
                                    <img src="<?= base_url('file/' . $layanan['thumbnail']) ?>" class="w-full h-32 object-cover rounded-lg border border-white/10">
                                </div>
                            <?php endif; ?>
                            <label class="block text-sm text-gray-400 mb-2">Ganti Media Thumnail</label>
                            <input type="file" name="thumbnail" accept="image/*" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-2 text-sm">
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="font-bold mb-4">Pengaturan</h3>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="is_pro_only" id="isProOnly" value="1" <?= !empty($layanan['is_pro_only']) ? 'checked' : '' ?> class="w-5 h-5 rounded bg-[#0a0a0a] border-white/10 text-accent">
                                <label for="isProOnly" class="text-sm">Khusus Member PRO</label>
                            </div>
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="is_featured" id="isFeatured" value="1" <?= !empty($layanan['is_featured']) ? 'checked' : '' ?> class="w-5 h-5 rounded bg-[#0a0a0a] border-white/10 text-accent">
                                <label for="isFeatured" class="text-sm">Featured</label>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Status</label>
                                <select name="status" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                                    <option value="aktif" <?= ($layanan['status'] ?? '') === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                                    <option value="tidak aktif" <?= ($layanan['status'] ?? '') === 'tidak aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                                    <option value="menunggu verifikasi" <?= ($layanan['status'] ?? '') === 'menunggu verifikasi' ? 'selected' : '' ?>>Menunggu Verifikasi</option>
                                    <option value="rejected" <?= ($layanan['status'] ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                </select>
                            </div>
                            <div id="rejectionReasonField" class="<?= ($layanan['status'] ?? '') === 'rejected' ? '' : 'hidden' ?> mt-4">
                                <label class="block text-sm text-red-400 mb-2">Alasan Penolakan</label>
                                <textarea name="rejection_reason" rows="3" class="w-full bg-[#0a0a0a] border border-red-500/30 rounded-lg px-4 py-3 text-red-300" placeholder="Jelaskan alasan penolakan..."><?= esc($layanan['rejection_reason'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-6">
                <button type="button" onclick="goToStep(2)" class="px-8 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center gap-2">
                    Lanjut ke Aktivasi EA <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- STEP 2: AKTIVASI EA (OPTIONAL) -->
        <div id="step-content-2" class="hidden space-y-6">
            <div class="bg-[#111] border border-white/10 rounded-xl p-6 text-center max-w-2xl mx-auto py-12">
                <div class="w-20 h-20 bg-accent/10 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-robot text-accent text-4xl"></i>
                </div>
                <h3 class="text-2xl font-bold mb-2">Aktivasi EA (Optional)</h3>
                <p class="text-gray-400 mb-8">Konfigurasi ini hanya diperlukan jika layanan menyertakan Expert Advisor berlisensi.</p>
                
                <div class="inline-flex items-center gap-4 bg-black/40 border border-white/5 p-4 rounded-xl mb-8">
                    <input type="checkbox" name="is_license_product" id="isLicenseProduct" value="1" <?= !empty($layanan['is_license_product']) ? 'checked' : '' ?> class="w-6 h-6 rounded bg-[#0a0a0a] border-white/20 text-accent">
                    <label for="isLicenseProduct" class="text-lg font-bold">Berikan Lisensi EA</label>
                </div>

                <div id="licenseFields" class="<?= !empty($layanan['is_license_product']) ? '' : 'hidden' ?> space-y-6 text-left max-w-lg mx-auto border-t border-white/10 pt-8 mt-4">
                    <!-- Documentation Box -->
                    <div class="bg-blue-500/10 border border-blue-500/20 rounded-lg p-4 mb-6">
                        <h4 class="text-blue-400 font-bold text-sm mb-2 flex items-center gap-2">
                            <i class="fas fa-book"></i> Dokumentasi Validasi Lisensi
                        </h4>
                        <p class="text-[11px] text-gray-400 leading-relaxed mb-3">
                            Untuk pengembang EA, gunakan Base URL berikut untuk melakukan validasi lisensi melalui API Almai:
                        </p>
                        <div class="bg-black/40 p-2 rounded border border-white/5 font-mono text-[10px] text-accent mb-3 select-all">
                            https://almai.id/api/license/validate
                        </div>
                        <p class="text-[11px] text-gray-500 italic">
                            * Kirim request dengan parameter <code>account_number</code> and <code>license_key</code>.
                        </p>

                        <!-- Postman Example -->
                        <div class="mt-4 pt-4 border-t border-white/5">
                            <h5 class="text-[10px] text-gray-400 font-bold uppercase mb-2">Contoh Request (Postman):</h5>
                            <div class="space-y-2">
                                <div class="flex items-center gap-2">
                                    <span class="px-2 py-0.5 bg-green-500/20 text-green-500 text-[9px] font-bold rounded">POST</span>
                                    <span class="text-[10px] text-gray-300">https://almai.id/api/license/validate</span>
                                </div>
                                <div class="bg-black/60 p-3 rounded text-[10px] font-mono leading-relaxed">
                                    <span class="text-blue-400">{</span><br>
                                    &nbsp;&nbsp;<span class="text-accent">"account_number"</span>: <span class="text-green-400">"12345678"</span>,<br>
                                    &nbsp;&nbsp;<span class="text-accent">"license_key"</span>: <span class="text-green-400">"ALMAI-12345678-ABCD"</span><br>
                                    <span class="text-blue-400">}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Generate Kode Lisensi (Prefix)</label>
                        <input type="text" name="license_prefix" value="<?= esc($layanan['license_prefix'] ?? 'ALMAI-{id_akun}-{random4}') ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3 text-sm text-white font-mono">
                        <p class="text-[10px] text-gray-500 mt-2 italic">Format: <strong>ALMAI-{id_akun}-{random4}</strong>. Gunakan placeholder {id_akun} dan {random4} untuk custom.</p>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Durasi Lisensi (Hari)</label>
                        <input type="number" name="license_duration" value="<?= $layanan['license_duration'] ?? 0 ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        <p class="text-[10px] text-gray-500 mt-1">Kosongkan atau isi 0 untuk lifetime.</p>
                    </div>
                    <div>
                        <?php if (!empty($layanan['ea_file_path'])): ?>
                            <div class="p-3 bg-accent/10 border border-accent/30 rounded-lg mb-4 flex items-center justify-between">
                                <p class="text-sm text-accent"><i class="fas fa-robot mr-2"></i>File EA Saat Ini: <?= basename($layanan['ea_file_path']) ?></p>
                            </div>
                        <?php endif; ?>
                        <label class="block text-sm text-gray-400 mb-2">Ganti File EA (.ex4, .ex5)</label>
                        <input type="file" name="ea_file" accept=".ex4,.ex5,.zip" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-2 text-sm">
                    </div>
                </div>
            </div>

            <div class="flex justify-between pt-6">
                <button type="button" onclick="goToStep(1)" class="px-8 py-3 bg-white/5 text-white font-bold rounded-xl hover:bg-white/10 transition flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Kembali
                </button>
                <button type="button" onclick="goToStep(3)" class="px-8 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center gap-2">
                    Lanjut ke Pelatihan <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- STEP 3: PELATIHAN -->
        <div id="step-content-3" class="hidden space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Video Tutorials -->
                <div class="bg-[#111] border border-white/10 rounded-xl p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-bold flex items-center gap-2">
                            <i class="fab fa-youtube text-red-500"></i>
                            Tutorial Penggunaan
                        </h3>
                        <button type="button" onclick="addYtRow()" class="px-3 py-1.5 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg text-xs transition">
                            <i class="fas fa-plus mr-1"></i> Tambah Video
                        </button>
                    </div>
                    <div id="ytContainer" class="space-y-3"></div>
                    <input type="hidden" name="youtube_tutorials" id="ytHidden" value="<?= htmlspecialchars($layanan['youtube_tutorials'] ?? '[]', ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <!-- Materi & Resources -->
                <div class="bg-[#111] border border-white/10 rounded-xl p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-bold flex items-center gap-2">
                            <i class="fas fa-folder-open text-yellow-500"></i>
                            Materi & Resources
                        </h3>
                        <button type="button" onclick="addResourceRow()" class="px-3 py-1.5 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg text-xs transition">
                            <i class="fas fa-plus mr-1"></i> Tambah File
                        </button>
                    </div>

                    <div class="space-y-4">
                        <?php if (!empty($resources)): ?>
                            <div class="space-y-2">
                                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">File Terupload:</p>
                                <?php foreach ($resources as $res): ?>
                                    <div class="flex items-center justify-between p-3 bg-black/40 border border-white/5 rounded-lg group">
                                        <div class="flex items-center gap-3 overflow-hidden">
                                            <div class="w-8 h-8 rounded bg-accent/10 flex items-center justify-center text-accent shrink-0">
                                                <i class="fas fa-file"></i>
                                            </div>
                                            <div class="overflow-hidden">
                                                <p class="text-sm font-medium truncate"><?= esc($res['title']) ?></p>
                                                <p class="text-[10px] text-gray-500 uppercase"><?= esc($res['file_type']) ?> • <?= number_format($res['file_size'] / 1024, 1) ?> KB</p>
                                            </div>
                                        </div>
                                        <label class="flex items-center gap-2 cursor-pointer text-gray-500 hover:text-red-400 transition">
                                            <input type="checkbox" name="delete_resources[]" value="<?= $res['id'] ?>" class="hidden peer">
                                            <i class="fas fa-trash-alt peer-checked:text-red-500"></i>
                                            <span class="text-[10px] hidden peer-checked:inline">Hapus</span>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div id="newResourcesContainer" class="space-y-3"></div>
                    </div>
                </div>
            </div>

            <!-- Zoom / Meeting Info -->
            <div id="webinarFields" class="bg-[#111] border border-white/10 rounded-xl p-6">
                <h3 class="font-bold mb-6 flex items-center gap-2">
                    <i class="fas fa-video text-blue-500"></i>
                    Informasi Meeting (Zoom/Live)
                </h3>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Zoom Link / URL Meeting</label>
                        <input type="url" name="zoom_link" value="<?= esc($layanan['zoom_link'] ?? '') ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Meeting ID</label>
                            <input type="text" name="meeting_id" value="<?= esc($layanan['meeting_id'] ?? '') ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Meeting Password</label>
                            <input type="text" name="meeting_password" value="<?= esc($layanan['meeting_password'] ?? '') ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between pt-6">
                <button type="button" onclick="goToStep(2)" class="px-8 py-3 bg-white/5 text-white font-bold rounded-xl hover:bg-white/10 transition flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Kembali
                </button>
                <button type="button" onclick="goToStep(4)" class="px-8 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center gap-2">
                    Lanjut ke Selesai <i class="fas fa-arrow-right"></i>
                </button>
            </div>
        </div>

        <!-- STEP 4: SELESAI -->
        <div id="step-content-4" class="hidden space-y-6">
            <div class="bg-[#111] border border-white/10 rounded-xl p-12 text-center max-w-2xl mx-auto">
                <div class="w-24 h-24 bg-blue-500/10 rounded-full flex items-center justify-center mx-auto mb-8 animate-pulse">
                    <i class="fas fa-edit text-blue-500 text-5xl"></i>
                </div>
                <h3 class="text-3xl font-bold mb-4">Update Layanan</h3>
                <p class="text-gray-400 mb-8">Pastikan kembali informasi yang Anda ubah sudah benar sebelum menyimpan pembaruan.</p>
                
                <div class="bg-black/40 border border-white/5 rounded-xl p-6 text-left mb-8 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Layanan:</span>
                        <span class="text-white font-bold"><?= esc($layanan['name'] ?? $layanan['title'] ?? '') ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Tipe:</span>
                        <span class="text-accent"><?= ucfirst($layananType) ?></span>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <button type="submit" class="w-full py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-lg shadow-lg shadow-accent/20">
                        <i class="fas fa-save mr-2"></i> Update & Simpan Layanan
                    </button>
                    <button type="button" onclick="goToStep(1)" class="w-full py-3 bg-white/5 text-gray-400 hover:text-white rounded-xl transition">
                        Tinjau Kembali
                    </button>
                </div>
            </div>

            <div class="flex justify-start pt-6">
                <button type="button" onclick="goToStep(3)" class="px-8 py-3 bg-white/5 text-white font-bold rounded-xl hover:bg-white/10 transition flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Kembali
                </button>
            </div>
        </div>
    </form>
</div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.ckeditor.com/ckeditor5/41.2.0/classic/ckeditor.js"></script>
<style>
    :root {
        --ck-color-base-background: #0a0a0a;
        --ck-color-base-border: #333;
        --ck-color-toolbar-background: #111;
        --ck-color-text: #e5e7eb;
        --ck-color-base-foreground: #111;
        --ck-color-button-default-background: transparent;
        --ck-color-button-default-hover-background: #333;
        --ck-color-button-on-background: #333;
        --ck-color-button-on-hover-background: #444;
        --ck-color-list-background: #111;
        --ck-color-list-button-hover-background: #333;
        --ck-color-dropdown-panel-background: #111;
        --ck-color-dropdown-panel-border: #333;
        --ck-color-input-background: #111;
        --ck-color-input-border: #333;
        --ck-color-panel-background: #111;
        --ck-color-panel-border: #333;
    }
    .ck-editor__editable_inline { min-height: 200px; }
    .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) { border-color: #333; }
    input[type="time"]::-webkit-calendar-picker-indicator,
    input[type="date"]::-webkit-calendar-picker-indicator,
    input[type="datetime-local"]::-webkit-calendar-picker-indicator {
        filter: invert(1);
        cursor: pointer;
    }
</style>

<script>
    // Wizard Navigation
    window.goToStep = function(step) {
        document.querySelectorAll('[id^="step-content-"]').forEach(el => el.classList.add('hidden'));
        document.getElementById(`step-content-${step}`).classList.remove('hidden');

        for (let i = 1; i <= 4; i++) {
            const btn = document.getElementById(`step-btn-${i}`);
            if (i === step) {
                btn.classList.remove('bg-white/5', 'text-gray-500', 'opacity-50', 'bg-green-500/20', 'text-green-500');
                btn.classList.add('bg-accent', 'text-black');
            } else if (i < step) {
                btn.classList.remove('bg-white/5', 'text-gray-500', 'opacity-50', 'bg-accent', 'text-black');
                btn.classList.add('bg-green-500/20', 'text-green-500');
            } else {
                btn.classList.remove('bg-accent', 'text-black', 'bg-green-500/20', 'text-green-500');
                btn.classList.add('bg-white/5', 'text-gray-500');
            }
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    // Subcategory logic (simplified for EDIT)
    document.addEventListener('DOMContentLoaded', function() {
        const sub = '<?= $layananType ?>';
        const eventFields = document.getElementById('eventFields');
        const webinarFields = document.getElementById('webinarFields');
        const toolsFields = document.getElementById('toolsFields');
        const subscriptionFields = document.getElementById('subscriptionFields');
        const artikelFields = document.getElementById('artikelFields');
        const fileUploadField = document.getElementById('fileUploadField');
        const fileTypeHint = document.getElementById('fileTypeHint');
        const layananFile = document.getElementById('layananFile');
        
        if (['webinar', 'workshop', 'live_trade'].includes(sub)) {
            eventFields.classList.remove('hidden');
        }

        if (['webinar', 'live_trade'].includes(sub)) {
            webinarFields.classList.remove('hidden');
        }

        if (['toolkit', 'ea'].includes(sub)) {
            toolsFields.classList.remove('hidden');
        }

        if (['pendampingan', 'profirm', 'vip_member', 'private_konsultan'].includes(sub)) {
            subscriptionFields.classList.remove('hidden');
        }

        if (sub === 'artikel') {
            artikelFields.classList.remove('hidden');
        }

        if (['artikel', 'toolkit', 'ea'].includes(sub)) {
            fileUploadField.classList.remove('hidden');
            if (sub === 'artikel') {
                fileTypeHint.textContent = '(PDF, DOC)';
                layananFile.accept = '.pdf,.doc,.docx';
            } else {
                fileTypeHint.textContent = '(ZIP, EX4, EX5)';
                layananFile.accept = '.zip,.ex4,.ex5,.mq4,.mq5';
            }
        }

        // Initialize YouTube rows
        const ytHidden = document.getElementById('ytHidden');
        if (ytHidden && ytHidden.value) {
            try {
                const data = JSON.parse(ytHidden.value);
                if (Array.isArray(data)) {
                    data.forEach(item => addYtRow(item.title, item.url));
                }
            } catch(e) {
                console.error("Failed to parse YouTube data", e);
            }
        }
    });

    // CKEditor Initialization
    const commonConfig = {
        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'mediaEmbed', 'undo', 'redo', 'imageUpload'],
    };

    function UploadAdapterPlugin(editor) {
        editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
            return {
                upload: () => loader.file.then(file => new Promise((resolve, reject) => {
                    const data = new FormData();
                    data.append('upload', file);
                    data.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
                    fetch('<?= base_url('superadmin/layanan/uploadImage') ?>', { method: 'POST', body: data })
                        .then(res => res.json())
                        .then(res => res.error ? reject(res.error.message) : resolve({ default: res.url }))
                        .catch(reject);
                }))
            };
        };
    }

    if (typeof ClassicEditor !== 'undefined') {
        if (document.querySelector('#editor_description')) {
            ClassicEditor.create(document.querySelector('#editor_description'), { ...commonConfig, extraPlugins: [UploadAdapterPlugin] }).catch(console.error);
        }
        if (document.querySelector('#editor_content')) {
            ClassicEditor.create(document.querySelector('#editor_content'), { ...commonConfig, extraPlugins: [UploadAdapterPlugin] }).catch(console.error);
        }
    } else {
        console.warn('ClassicEditor is not loaded. Skipping initialization.');
    }

    // Toggle Recurring
    if (document.getElementById('isRecurring')) {
        document.getElementById('isRecurring').addEventListener('change', function() {
            document.getElementById('recurringOptions').classList.toggle('hidden', !this.checked);
        });
    }

    // Toggle License
    if (document.getElementById('isLicenseProduct')) {
        document.getElementById('isLicenseProduct').addEventListener('change', function() {
            document.getElementById('licenseFields').classList.toggle('hidden', !this.checked);
        });
    }

    // Status Rejection Toggle
    const statusSelect = document.querySelector('select[name="status"]');
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            document.getElementById('rejectionReasonField').classList.toggle('hidden', this.value !== 'rejected');
        });
    }

    // Packages Logic
    let packageCounter = <?= !empty($packages) ? count($packages) : 0 ?>;

    // ── Referral Preview Constants ─────────────────────────────────────
    const DEPTH = 8;
    const POIN_RATE = 1000; // 1 poin = Rp 1.000

    // Toggle Referral Section
    function toggleReferralSection(enabled) {
        const section  = document.getElementById('referralSection');
        const inputOn  = document.getElementById('referral_dist_pct');
        const inputOff = document.getElementById('referral_dist_pct_disabled');

        if (enabled) {
            section.classList.remove('hidden');
            if (inputOn)  inputOn.disabled  = false;
            if (inputOff) inputOff.disabled = true;
            updateReferralPreview();
        } else {
            section.classList.add('hidden');
            if (inputOn)  inputOn.disabled  = true;
            if (inputOff) inputOff.disabled = false;
        }
    }
    // Init on load
    toggleReferralSection(document.getElementById('enableReferral')?.checked ?? false);

    function getDefaultDistPct() {
        return parseFloat(document.getElementById('referral_dist_pct')?.value || 25);
    }
    window.addPackageRow = function() {
        const container = document.getElementById('packagesContainer');
        const isEnabled = document.getElementById('hasPackages').checked;
        const id = `pkg-new-${packageCounter}`;
        container.insertAdjacentHTML('beforeend', `
            <div id="${id}" class="bg-black/30 p-3 rounded-lg border border-white/5 relative group">
                <button type="button" onclick="document.getElementById('${id}').remove();updateReferralPreview();" class="absolute top-2 right-2 text-gray-500 hover:text-red-400 opacity-0 group-hover:opacity-100 transition"><i class="fas fa-times"></i></button>
                <input type="text" name="packages[${packageCounter}][name]" placeholder="Nama Paket" class="w-full bg-[#0a0a0a] border border-white/10 rounded px-3 py-2 text-sm mb-2" ${isEnabled ? 'required' : 'disabled'}>
                <div class="grid grid-cols-3 gap-3 mb-2">
                    <input type="number" name="packages[${packageCounter}][price]" placeholder="Harga" class="w-full bg-[#0a0a0a] border border-white/10 rounded px-3 py-2 text-sm" ${isEnabled ? 'required' : 'disabled'}>
                    <input type="number" name="packages[${packageCounter}][original_price]" placeholder="Harga Coret" class="w-full bg-[#0a0a0a] border border-white/10 rounded px-3 py-2 text-sm" ${isEnabled ? '' : 'disabled'}>
                    <input type="number" name="packages[${packageCounter}][duration_days]" placeholder="Durasi (Hari)" class="w-full bg-[#0a0a0a] border border-white/10 rounded px-3 py-2 text-sm" ${isEnabled ? '' : 'disabled'}>
                </div>
                <textarea name="packages[${packageCounter}][description]" rows="2" placeholder="Deskripsi Singkat" class="w-full bg-[#0a0a0a] border border-white/10 rounded px-3 py-2 text-sm"></textarea>
            </div>
        `);
        packageCounter++;
        updateReferralPreview();
    };

    // Toggle Multi Package
    // Toggle Multi Package
    if (document.getElementById('hasPackages')) {
        const togglePackagesEdit = function() {
            const isChecked = document.getElementById('hasPackages').checked;
            const singleSection = document.getElementById('singlePriceField');
            const multiSection = document.getElementById('multiPackageField');
            
            if (isChecked) {
                if (singleSection) singleSection.classList.add('hidden');
                if (multiSection) multiSection.classList.remove('hidden');
            } else {
                if (singleSection) singleSection.classList.remove('hidden');
                if (multiSection) multiSection.classList.add('hidden');
            }
            
            if (singleSection) {
                singleSection.querySelectorAll('input').forEach(input => input.disabled = isChecked);
            }
            if (multiSection) {
                multiSection.querySelectorAll('input').forEach(input => {
                    input.disabled = !isChecked;
                    // Remove required when hidden, restore when visible
                    if (!isChecked) {
                        input.removeAttribute('required');
                    } else if (input.name && (input.name.includes('[name]') || input.name.includes('[price]'))) {
                        input.setAttribute('required', 'required');
                    }
                });
            }

            if (isChecked && document.getElementById('packagesContainer') && document.getElementById('packagesContainer').children.length === 0) {
                if (typeof window.addPackageRow === 'function') addPackageRow();
            }
            if (typeof updateReferralPreview === 'function') updateReferralPreview();
        };

        // Ensure togglePackagesEdit is accessible globally for inline onchange
        window.togglePackagesEdit = togglePackagesEdit;
        document.getElementById('hasPackages').addEventListener('change', togglePackagesEdit);

        // On page load: if has_packages is unchecked, remove required from hidden package fields
        if (!document.getElementById('hasPackages').checked) {
            document.querySelectorAll('#multiPackageField input').forEach(input => {
                input.removeAttribute('required');
                input.disabled = true;
            });
        }
        
        // Ensure state is correct on load
        togglePackagesEdit();
    }

    // Auto calculate Poin Price
    const priceInput = document.querySelector('input[name="price"]');
    const poinInput = document.getElementById('poinPrice');
    const cwpaSelect = document.querySelector('select[name="cwpa_id"]');

    function updatePriceVisibility() {
        const sub = '<?= $layananType ?>';
        const singleSection = document.getElementById('singlePriceField');
        const hasPackages = document.getElementById('hasPackages');
        const isPkgChecked = hasPackages ? hasPackages.checked : false;
        
        if (sub === 'artikel') {
            if (singleSection) singleSection.classList.add('hidden');
        } else {
            if (singleSection && !isPkgChecked) {
                singleSection.classList.remove('hidden');
            } else if (singleSection && isPkgChecked) {
                singleSection.classList.add('hidden');
            }
        }

        if(poinInput) {
            poinInput.setAttribute('readonly', 'readonly');
            poinInput.classList.add('bg-[#0a0a0a]/50', 'text-gray-500', 'cursor-not-allowed');
        }
    }

    // Removed priceInput directly because we use delegated listener
    document.addEventListener('input', function(e) {
        if (e.target.name === 'price') {
            if (poinInput) {
                poinInput.value = Math.floor(e.target.value / 100);
            }
        }
    });

    // Initial run
    updatePriceVisibility();

    // Resources
    window.addResourceRow = function() {
        const container = document.getElementById('newResourcesContainer');
        const div = document.createElement('div');
        div.className = 'p-3 bg-white/5 border border-white/10 rounded-lg relative';
        div.innerHTML = `
            <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-gray-400 hover:text-red-400"><i class="fas fa-times"></i></button>
            <input type="text" name="resource_titles[]" placeholder="Judul Materi" class="w-full bg-black/50 border border-white/10 rounded px-3 py-2 text-sm mb-2">
            <input type="file" name="resources[]" required class="text-xs">
        `;
        container.appendChild(div);
    };

    // YouTube rows
    window.addYtRow = function(title = '', url = '') {
        const container = document.getElementById('ytContainer');
        const div = document.createElement('div');
        div.className = 'p-3 bg-white/5 border border-white/10 rounded-lg space-y-2 relative';
        div.innerHTML = `
            <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-gray-400 hover:text-red-400"><i class="fas fa-times"></i></button>
            <input type="text" placeholder="Judul Video" class="yt-title w-full bg-black/50 border border-white/10 rounded px-3 py-2 text-sm" value="${title}">
            <input type="url" placeholder="URL YouTube" class="yt-url w-full bg-black/50 border border-white/10 rounded px-3 py-2 text-sm" value="${url}">
        `;
        container.appendChild(div);
    };

    // Collect YT data before submit
    document.getElementById('wizardForm').addEventListener('submit', function() {
        const data = [];
        document.querySelectorAll('#ytContainer > div').forEach(div => {
            const title = div.querySelector('.yt-title').value;
            const url = div.querySelector('.yt-url').value;
            if (title || url) data.push({ title, url });
        });
        const ytHidden = document.getElementById('ytHidden');
        if (ytHidden) ytHidden.value = JSON.stringify(data);
    });

    // Initial first step
    goToStep(1);

    // ── Referral Preview ──────────────────────────────────────────────

    function getLevelLabel(i) {
        if (i === 1) return 'Referrer (Level 1)';
        if (i === 2) return 'Upline Level 2';
        return `Upline Level ${i}`;
    }

    function getAllPrices() {
        const hasPackages = document.getElementById('hasPackages')?.checked;
        const globalPct   = parseFloat(document.getElementById('referral_dist_pct')?.value || 25);
        if (hasPackages) {
            const inputs = document.querySelectorAll('#packagesContainer input[name*="[price]"]');
            const names  = document.querySelectorAll('#packagesContainer input[name*="[name]"]');
            const prices = [];
            inputs.forEach((inp, idx) => {
                const val = parseFloat(inp.value || 0);
                if (val > 0) {
                    prices.push({ label: names[idx]?.value || `Paket ${idx + 1}`, price: val, pct: globalPct });
                }
            });
            return prices.length ? prices : null;
        }
        const single = parseFloat(document.querySelector('input[name="price"]')?.value || 0);
        return single > 0 ? [{ label: 'Harga Tunggal', price: single, pct: globalPct }] : null;
    }

    function buildLevels(pool, pct) {
        const DECAY = 50; // tiap level dapat 50% dari sisa pool (fixed)
        const rows = [];
        let remaining = pool;
        for (let i = 1; i <= DEPTH; i++) {
            if (remaining < 1) break;
            const share = Math.floor(remaining * DECAY / 100);
            if (share < 1) break;
            const poin = Math.floor(share / POIN_RATE);
            const barW = pool > 0 ? Math.round((share / pool) * 100) : 0;
            const pct_of_price = pool > 0 ? (share / pool * pct).toFixed(2) : '0.00';
            rows.push({ label: getLevelLabel(i), share, poin, barW, pct_of_price });
            remaining -= share;
        }
        return { rows, remaining };
    }

    function renderPreviewBlock(pkgLabel, pool, pct, colors) {
        const { rows, remaining } = buildLevels(pool, pct);
        const poin_pool = Math.floor(pool / POIN_RATE);

        const header = pkgLabel
            ? `<div class="flex items-center justify-between mb-2 pb-2 border-b border-white/5">
                <span class="font-bold text-white text-[11px] uppercase tracking-widest">${pkgLabel}</span>
                <span class="text-[10px] text-gray-500">Pool: <span class="text-accent font-bold">Rp ${pool.toLocaleString('id-ID')}</span> · <span class="text-yellow-400 font-bold">${poin_pool.toLocaleString('id-ID')} poin</span></span>
               </div>`
            : `<div class="flex justify-between mb-2 pb-2 border-b border-white/5 text-[10px] text-gray-500">
                <span>Pool komisi</span>
                <span>Rp ${pool.toLocaleString('id-ID')} · <span class="text-yellow-400">${poin_pool.toLocaleString('id-ID')} poin</span></span>
               </div>`;

        if (rows.length === 0) {
            return `${header}<p class="text-gray-600 text-center py-2 text-[11px]">Masukkan harga untuk melihat preview</p>`;
        }

        const levelRows = rows.map((r, i) => `
            <div class="flex items-center gap-2 py-1">
                <div class="w-32 shrink-0 text-gray-400 text-[11px] truncate" title="${r.label}">${r.label}</div>
                <div class="w-14 shrink-0 text-gray-600 text-[10px] text-right">${r.pct_of_price}%</div>
                <div class="flex-1 bg-white/5 rounded-full h-1.5 overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-300" style="width:${r.barW}%;background:${colors[i] || '#052e16'}"></div>
                </div>
                <div class="text-right shrink-0 leading-tight min-w-[90px]">
                    <div class="font-bold text-[11px]" style="color:${colors[i] || '#052e16'}">Rp ${r.share.toLocaleString('id-ID')}</div>
                    <div class="text-yellow-400 text-[10px]">🪙 ${r.poin.toLocaleString('id-ID')} poin</div>
                </div>
            </div>
        `).join('');

        const footer = remaining > 0
            ? `<div class="mt-2 pt-2 border-t border-white/5 flex justify-between text-[10px] text-gray-500">
                <span>Sisa tidak terdistribusi</span>
                <span class="text-yellow-500 font-bold">Rp ${remaining.toLocaleString('id-ID')}</span>
               </div>`
            : `<div class="mt-2 pt-2 border-t border-white/5 text-[10px] text-accent text-right">Pool habis terdistribusi ✓</div>`;

        return `${header}${levelRows}${footer}`;
    }

    function updateReferralPreview() {
        const prices = getAllPrices();
        const colors = ['#33E818','#22c55e','#16a34a','#15803d','#166534','#14532d','#052e16','#022c22'];

        const container = document.getElementById('referralPreviewTable');
        if (!container) return;

        if (!prices) {
            container.innerHTML = '<p class="text-gray-600 text-center py-2 text-[11px]">Masukkan harga layanan untuk melihat preview</p>';
            document.getElementById('calc_referral_user_cash').value = 0;
            document.getElementById('calc_referral_user_poin').value = 0;
            return;
        }

        const isMulti   = prices.length > 1;
        const firstPool = Math.floor(prices[0].price * prices[0].pct / 100);

        // Update hidden fields using first/only price
        document.getElementById('calc_referral_user_cash').value = firstPool;
        document.getElementById('calc_referral_user_poin').value = Math.floor(firstPool / POIN_RATE);

        if (isMulti) {
            container.innerHTML = prices.map((pkg, idx) => {
                const pool  = Math.floor(pkg.price * pkg.pct / 100);
                const block = renderPreviewBlock(pkg.label, pool, pkg.pct, colors);
                return `<div class="${idx > 0 ? 'mt-4 pt-4 border-t border-white/5' : ''}">${block}</div>`;
            }).join('');
        } else {
            container.innerHTML = renderPreviewBlock(null, firstPool, prices[0].pct, colors);
        }
    }

    // Trigger preview on price / package name change
    document.addEventListener('input', function(e) {
        if (e.target.name === 'price' || e.target.name?.includes('[price]') || e.target.name?.includes('[name]')) {
            updateReferralPreview();
        }
    });

    // Init preview on load
    updateReferralPreview();
</script>
<?= $this->endSection() ?>
