<?php
$isArtikel = $layananType === 'artikel';
$isEvent = in_array($layananType, ['webinar', 'workshop', 'live_trade']);
$isTool = in_array($layananType, ['toolkit', 'ea']);
$isSubscription = in_array($layananType, ['pendampingan', 'profirm', 'private_konsultan', 'vip_member']);
?>
<?= $this->extend('cwpa/layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="<?= base_url('cwpa/dashboard/layanan') ?>" class="p-2 hover:bg-white/10 rounded-lg">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold">Edit Layanan (CWPA)</h1>
            <p class="text-gray-400 text-sm"><?= esc($layanan['name'] ?? $layanan['title'] ?? '') ?></p>
        </div>
    </div>

    <form action="<?= base_url('cwpa/dashboard/layanan/update/' . $layananType . '/' . $layanan['id']) ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
        <?= csrf_field() ?>
        <div class="grid grid-cols-1 gap-6">
            <!-- Main Content -->
            <div class="space-y-6">
                <!-- Basic Info -->
                <div class="bg-[#111] border border-white/10 rounded-xl p-6">
                    <h3 class="font-bold mb-4">Informasi Dasar</h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
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
                            <textarea name="description" rows="4" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"><?= esc($layanan['description'] ?? '') ?></textarea>
                        </div>

                        <!-- Layanan Utama -->
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">
                                Layanan Utama
                                <span class="text-xs text-gray-500">(Akan ditampilkan di landing page)</span>
                            </label>
                            <textarea name="layanan_utama" rows="3" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3" placeholder="Sebutkan 3-5 layanan utama dari produk ini..."><?= esc($layanan['layanan_utama'] ?? '') ?></textarea>
                        </div>
                        
                        <!-- Video Tutorial (YouTube) -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm text-gray-400">
                                    Tutorial Penggunaan (YouTube Embeds)
                                    <span class="text-xs text-gray-500 font-normal">Isi Judul & URL Video</span>
                                </label>
                                <button type="button" onclick="addYtRow()" class="text-xs text-accent hover:text-white transition px-2 py-1 rounded bg-white/5 border border-white/10 hover:bg-white/10 cursor-pointer">
                                    <i class="fas fa-plus mr-1"></i> Tambah Embed
                                </button>
                            </div>
                            <div id="ytContainer" class="space-y-3"></div>
                            <input type="hidden" name="youtube_tutorials" id="ytHidden" value="<?= htmlspecialchars($layanan['youtube_tutorials'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const ytContainer = document.getElementById('ytContainer');
                                const ytHidden = document.getElementById('ytHidden');
                                if (!ytContainer || !ytHidden) return;
                                
                                let initialYt = [];
                                try {
                                    if (ytHidden.value) {
                                        initialYt = JSON.parse(ytHidden.value);
                                        if (!Array.isArray(initialYt)) initialYt = [];
                                    }
                                } catch(e) {
                                    const oldLines = ytHidden.value.split('\n');
                                    initialYt = oldLines.map(url => ({ title: 'Tutorial', url: url.trim() })).filter(i => i.url);
                                }

                                window.addYtRow = function(title = '', url = '') {
                                    const row = document.createElement('div');
                                    row.className = 'grid grid-cols-1 md:grid-cols-12 gap-3 p-3 bg-black/20 border border-white/5 rounded-lg relative group items-start';
                                    row.innerHTML = `
                                        <div class="md:col-span-4">
                                            <input type="text" placeholder="Nama Judul (Cth: Cara Login)" class="yt-title w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-3 py-2 text-sm outline-none focus:border-accent" value="${title.replace(/"/g, '&quot;')}">
                                        </div>
                                        <div class="md:col-span-7">
                                            <input type="url" placeholder="URL YouTube (Cth: https://youtube.com/...)" class="yt-url w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-3 py-2 text-sm outline-none focus:border-accent" value="${url.replace(/"/g, '&quot;')}">
                                        </div>
                                        <div class="md:col-span-1 flex items-center justify-center pt-1 md:pt-0">
                                            <button type="button" onclick="this.closest('.grid').remove()" class="text-gray-500 hover:text-red-400 transition p-2 cursor-pointer bg-white/5 rounded hover:bg-red-400/10">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    `;
                                    ytContainer.appendChild(row);
                                };

                                if (initialYt.length > 0) {
                                    initialYt.forEach(item => addYtRow(item.title, item.url));
                                }

                                const form = ytHidden.closest('form');
                                if (form) {
                                    form.addEventListener('submit', function() {
                                        const rows = ytContainer.children;
                                        const ytData = [];
                                        for(let i=0; i<rows.length; i++) {
                                            const title = rows[i].querySelector('.yt-title').value.trim();
                                            const url = rows[i].querySelector('.yt-url').value.trim();
                                            if (title || url) ytData.push({ title, url });
                                        }
                                        ytHidden.value = ytData.length ? JSON.stringify(ytData) : '';
                                    });
                                }
                            });
                        </script>

                        <!-- Fitur Unggulan -->
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">
                                Fitur Unggulan
                                <span class="text-xs text-gray-500">(Tombol Kustom)</span>
                            </label>
                            <textarea name="fitur_unggulan" rows="5" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3 font-mono text-sm" placeholder='[{"name": "Software", "link": "https://..."}]'><?= esc($layanan['fitur_unggulan'] ?? '') ?></textarea>
                            <p class="text-xs text-gray-500 mt-1">
                                Format JSON: <code class="bg-black px-2 py-0.5 rounded">[{"name": "Button Name", "link": "https://url"}]</code>
                                <br>Kosongkan <strong>link</strong> jika hanya ingin menampilkan label (tanpa link).
                            </p>
                        </div>

                        <?php if ($isArtikel): ?>
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Excerpt</label>
                                <textarea name="excerpt" rows="2" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"><?= esc($layanan['excerpt'] ?? '') ?></textarea>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Konten</label>
                                <textarea name="content" rows="8" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"><?= esc($layanan['content'] ?? '') ?></textarea>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($isEvent): ?>
                    <!-- Event Fields -->
                    <div class="bg-[#111] border border-white/10 rounded-xl p-6">
                        <h3 class="font-bold mb-4">Detail Event</h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">Tanggal Mulai</label>
                                    <input type="datetime-local" name="event_date" value="<?= !empty($layanan['event_date']) ? date('Y-m-d\TH:i', strtotime($layanan['event_date'])) : '' ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">Tanggal Selesai</label>
                                    <input type="datetime-local" name="event_end_date" value="<?= !empty($layanan['event_end_date']) ? date('Y-m-d\TH:i', strtotime($layanan['event_end_date'])) : '' ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Maks Peserta</label>
                                <input type="number" name="max_participants" value="<?= $layanan['max_participants'] ?? 0 ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Jumlah Sesi</label>
                                <input type="number" name="total_sessions" value="<?= $layanan['total_sessions'] ?? 1 ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                            </div>

                            <!-- Recurring Settings -->
                            <div class="border-t border-white/10 pt-4 mt-4">
                                <label class="flex items-center gap-2 mb-4 cursor-pointer">
                                    <input type="checkbox" name="is_recurring" id="isRecurring" value="1" <?= !empty($layanan['is_recurring']) ? 'checked' : '' ?> class="w-5 h-5 rounded bg-[#0a0a0a] border-white/10 text-accent">
                                    <span class="font-medium">Jadwal Rutin (Recurring)</span>
                                </label>

                                <div id="recurringOptions" class="<?= !empty($layanan['is_recurring']) ? '' : 'hidden' ?> space-y-4 pl-7">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm text-gray-400 mb-2">Frekuensi</label>
                                            <select name="recurring_frequency" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                                                <option value="weekly" <?= ($layanan['recurring_frequency'] ?? '') === 'weekly' ? 'selected' : '' ?>>Mingguan (Weekly)</option>
                                                <option value="daily" <?= ($layanan['recurring_frequency'] ?? '') === 'daily' ? 'selected' : '' ?>>Harian (Daily)</option>
                                                <option value="monthly" <?= ($layanan['recurring_frequency'] ?? '') === 'monthly' ? 'selected' : '' ?>>Bulanan (Monthly)</option>
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
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Jam (Waktu)</label>
                                        <input type="time" name="recurring_time" value="<?= $layanan['recurring_time'] ?? '' ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                                    </div>
                                </div>
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const isRecurring = document.getElementById('isRecurring');
                                    if (isRecurring) {
                                        isRecurring.addEventListener('change', function() {
                                            const options = document.getElementById('recurringOptions');
                                            if (this.checked) {
                                                options.classList.remove('hidden');
                                            } else {
                                                options.classList.add('hidden');
                                            }
                                        });
                                    }
                                });
                            </script>

                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Lokasi / Info Jadwal</label>
                                <input type="text" name="location" value="<?= esc($layanan['location'] ?? '') ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3" placeholder="Contoh: Zoom, Hotel A, atau 'Setiap Selasa via Zoom'">
                            </div>

                            <?php if ($layananType === 'webinar' || $layananType === 'live_trade'): ?>
                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">Zoom Link</label>
                                    <input type="url" name="zoom_link" value="<?= esc($layanan['zoom_link'] ?? '') ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Meeting ID</label>
                                        <input type="text" name="meeting_id" value="<?= esc($layanan['meeting_id'] ?? '') ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Password</label>
                                        <input type="text" name="meeting_password" value="<?= esc($layanan['meeting_password'] ?? '') ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" name="requires_agreement" id="requiresAgreement" <?= !empty($layanan['requires_agreement']) ? 'checked' : '' ?> class="w-5 h-5 rounded bg-[#0a0a0a] border-white/10 text-accent">
                                    <label for="requiresAgreement" class="text-sm">Memerlukan Agreement</label>
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">Teks Agreement</label>
                                    <textarea name="agreement_text" rows="4" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"><?= esc($layanan['agreement_text'] ?? '') ?></textarea>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($isTool): ?>
                    <!-- Tools Fields -->
                    <div class="bg-[#111] border border-white/10 rounded-xl p-6">
                        <h3 class="font-bold mb-4">Detail Tools</h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">Versi</label>
                                    <input type="text" name="version" value="<?= esc($layanan['version'] ?? '') ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">Compatibility</label>
                                    <?php $compat = is_string($layanan['compatibility'] ?? '') ? json_decode($layanan['compatibility'], true) : ($layanan['compatibility'] ?? []); ?>
                                    <div class="flex gap-4 pt-2">
                                        <label class="flex items-center gap-2">
                                            <input type="checkbox" name="compatibility[]" value="MT4" <?= in_array('MT4', $compat ?? []) ? 'checked' : '' ?> class="rounded bg-[#0a0a0a] border-white/10 text-accent">
                                            <span>MT4</span>
                                        </label>
                                        <label class="flex items-center gap-2">
                                            <input type="checkbox" name="compatibility[]" value="MT5" <?= in_array('MT5', $compat ?? []) ? 'checked' : '' ?> class="rounded bg-[#0a0a0a] border-white/10 text-accent">
                                            <span>MT5</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Changelog</label>
                                <textarea name="changelog" rows="3" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"><?= esc($layanan['changelog'] ?? '') ?></textarea>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">URL Dokumentasi</label>
                                <input type="url" name="documentation_url" value="<?= esc($layanan['documentation_url'] ?? '') ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                            </div>
                            <?php $features = is_string($layanan['features'] ?? '') ? json_decode($layanan['features'], true) : ($layanan['features'] ?? []); ?>
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Features</label>
                                <textarea name="features" rows="4" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"><?= implode("\n", $features ?? []) ?></textarea>
                            </div>
                            <?php $requirements = is_string($layanan['requirements'] ?? '') ? json_decode($layanan['requirements'], true) : ($layanan['requirements'] ?? []); ?>
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Requirements</label>
                                <textarea name="requirements" rows="3" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"><?= implode("\n", $requirements ?? []) ?></textarea>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($isSubscription): ?>
                    <!-- Subscription Fields -->
                    <div class="bg-[#111] border border-white/10 rounded-xl p-6">
                        <h3 class="font-bold mb-4">Detail Subscription</h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">Durasi (hari)</label>
                                    <input type="number" name="duration_days" value="<?= $layanan['duration_days'] ?? 30 ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">Jumlah Sesi</label>
                                    <input type="number" name="total_sessions" value="<?= $layanan['total_sessions'] ?? 0 ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">Maks Slot</label>
                                    <input type="number" name="max_slots" value="<?= $layanan['max_slots'] ?? 0 ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                                </div>
                            </div>
                            <?php if ($layananType === 'private_konsultan'): ?>
                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">Info Jadwal</label>
                                    <textarea name="schedule_info" rows="3" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"><?= esc($layanan['schedule_info'] ?? '') ?></textarea>
                                </div>
                            <?php endif; ?>
                            <?php $benefits = is_string($layanan['benefits'] ?? '') ? json_decode($layanan['benefits'], true) : ($layanan['benefits'] ?? []); ?>
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Benefits</label>
                                <textarea name="benefits" rows="4" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"><?= implode("\n", $benefits ?? []) ?></textarea>
                            </div>
                            <?php $includes = is_string($layanan['includes'] ?? '') ? json_decode($layanan['includes'], true) : ($layanan['includes'] ?? []); ?>
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Includes</label>
                                <textarea name="includes" rows="3" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"><?= implode("\n", $includes ?? []) ?></textarea>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Pricing -->
                <div class="bg-[#111] border border-white/10 rounded-xl p-6">
                    <h3 class="font-bold mb-4">Harga</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Harga (Poin)</label>
                            <input type="number" name="poin_price" value="<?= $layanan['poin_price'] ?? 0 ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                            <p class="text-xs text-gray-500 mt-1">Layanan CWPA hanya dapat dibayar menggunakan Poin.</p>
                        </div>

                        <?php if (!$isArtikel): ?>
                            <div class="border-t border-white/10 pt-4 mt-4">
                                <label class="flex items-center gap-2 cursor-pointer mb-4">
                                    <input type="checkbox" name="has_packages" id="hasPackages" value="1" class="w-5 h-5 rounded bg-[#0a0a0a] border-white/10 text-accent" <?= !empty($packages) ? 'checked' : '' ?>>
                                    <span class="font-medium text-sm">Aktifkan Paket Harga (Poin)</span>
                                </label>

                                <div id="multiPackageField" class="<?= !empty($packages) ? '' : 'hidden' ?> space-y-4">
                                    <div id="packagesContainer" class="space-y-3">
                                        <?php if (!empty($packages)): ?>
                                            <?php foreach ($packages as $i => $pkg): ?>
                                                <div id="package-<?= $i ?>" class="bg-black/30 p-3 rounded-lg border border-white/5 relative group">
                                                    <button type="button" onclick="removePackageRow('package-<?= $i ?>')" class="absolute top-2 right-2 text-gray-500 hover:text-red-400 opacity-0 group-hover:opacity-100 transition">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                    <div class="space-y-3">
                                                        <div>
                                                            <input type="text" name="packages[<?= $i ?>][name]" value="<?= esc($pkg['name']) ?>" placeholder="Nama Paket (Misal: Basic, Pro)" class="w-full bg-[#0a0a0a] border border-white/10 rounded px-3 py-2 text-sm" required>
                                                        </div>
                                                        <div>
                                                            <input type="number" name="packages[<?= $i ?>][poin_price]" value="<?= esc($pkg['poin_price']) ?>" placeholder="Harga Poin" class="w-full bg-[#0a0a0a] border border-white/10 rounded px-3 py-2 text-sm" required>
                                                        </div>
                                                        <div>
                                                            <textarea name="packages[<?= $i ?>][description]" rows="2" placeholder="Deskripsi Singkat" class="w-full bg-[#0a0a0a] border border-white/10 rounded px-3 py-2 text-sm"><?= esc($pkg['description'] ?? '') ?></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                    <button type="button" onclick="addPackageRow()" class="w-full py-2 border border-dashed border-white/20 rounded-lg text-sm text-gray-400 hover:text-white hover:border-white/40 transition">
                                        <i class="fas fa-plus mr-2"></i> Tambah Paket
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Referral Scheme -->
                <div class="bg-[#111] border border-white/10 rounded-xl p-6">
                    <h3 class="font-bold mb-4">Skema Referral</h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">User Reward (Poin)</label>
                                <input type="number" name="referral_user_poin" value="<?= $layanan['referral_user_poin'] ?? 0 ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Persentase (%)</label>
                                <input type="number" step="0.01" name="referral_distribution_percentage" value="<?= $layanan['referral_distribution_percentage'] ?? 50.00 ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Max Depth</label>
                                <input type="number" name="referral_max_depth" value="<?= $layanan['referral_max_depth'] ?? 8 ?>" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Media -->
                <div class="bg-[#111] border border-white/10 rounded-xl p-6">
                    <h3 class="font-bold mb-4">Media</h3>
                    <div class="space-y-4">
                        <?php if (!empty($layanan['thumbnail'])): ?>
                            <div>
                                <img src="<?= base_url('file/' . $layanan['thumbnail']) ?>" class="w-full h-32 object-cover rounded-lg">
                            </div>
                        <?php endif; ?>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Update Thumbnail</label>
                            <input type="file" name="thumbnail" accept="image/*" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        </div>

                        <?php if ($isArtikel || $isTool): ?>
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Update File</label>
                                <input type="file" name="layanan_file" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Materi & Resources -->
                <div class="bg-[#111] border border-white/10 rounded-xl p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold">Materi & Resources</h3>
                        <button type="button" onclick="addResourceRow()" class="text-xs bg-white/5 hover:bg-white/10 px-3 py-1.5 rounded-lg transition">
                            <i class="fas fa-plus mr-1"></i> Tambah File
                        </button>
                    </div>

                    <div class="space-y-4">
                        <?php if (!empty($resources)): ?>
                            <div class="space-y-2">
                                <?php foreach ($resources as $res): ?>
                                    <div class="flex items-center justify-between p-3 bg-black/40 border border-white/5 rounded-lg">
                                        <div class="flex items-center gap-3 overflow-hidden">
                                            <i class="fas fa-file text-accent"></i>
                                            <p class="text-sm truncate"><?= esc($res['title']) ?></p>
                                        </div>
                                        <label class="cursor-pointer">
                                            <input type="checkbox" name="delete_resources[]" value="<?= $res['id'] ?>" class="hidden peer">
                                            <i class="fas fa-trash-alt text-gray-500 peer-checked:text-red-500"></i>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <div id="newResourcesContainer" class="space-y-3"></div>
                    </div>

                    <script>
                        function addResourceRow() {
                            const container = document.getElementById('newResourcesContainer');
                            const div = document.createElement('div');
                            div.className = 'p-3 bg-accent/5 border border-dashed border-accent/20 rounded-lg space-y-3 relative';
                            div.innerHTML = `
                                <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-gray-400">
                                    <i class="fas fa-times"></i>
                                </button>
                                <input type="text" name="resource_titles[]" placeholder="Judul Materi" class="w-full bg-black/50 border border-white/10 rounded px-3 py-2 text-sm">
                                <input type="file" name="resources[]" required class="w-full text-xs">
                            `;
                            container.appendChild(div);
                        }
                    </script>
                </div>

                <!-- Status -->
                <div class="bg-[#111] border border-white/10 rounded-xl p-6">
                    <h3 class="font-bold mb-4">Pengaturan</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" name="is_pro_only" id="isProOnly" <?= !empty($layanan['is_pro_only']) ? 'checked' : '' ?> class="w-5 h-5 rounded bg-[#0a0a0a] border-white/10 text-accent">
                            <label for="isProOnly" class="text-sm">Khusus PRO</label>
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="checkbox" name="is_featured" id="isFeatured" <?= !empty($layanan['is_featured']) ? 'checked' : '' ?> class="w-5 h-5 rounded bg-[#0a0a0a] border-white/10 text-accent">
                            <label for="isFeatured" class="text-sm">Featured</label>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Status Saat Ini</label>
                            <div class="px-4 py-3 bg-white/5 border border-white/10 rounded-lg">
                                <?php 
                                $status = $layanan['status'] ?? 'pending';
                                $statusClass = 'text-gray-400';
                                if ($status === 'aktif' || $status === 'active' || $status === 'published') $statusClass = 'text-green-400';
                                if ($status === 'menunggu verifikasi' || $status === 'pending') $statusClass = 'text-yellow-400';
                                if ($status === 'tidak aktif' || $status === 'draft') $statusClass = 'text-red-400';
                                ?>
                                <span class="font-bold <?= $statusClass ?> uppercase text-xs"><?= esc($status) ?></span>
                                <p class="text-[10px] text-gray-500 mt-1">Status dikelola oleh Admin.</p>
                            </div>
                            <input type="hidden" name="status" value="<?= esc($status) ?>">
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                    <i class="fas fa-save mr-2"></i> Update Layanan
                </button>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.ckeditor.com/ckeditor5/41.2.0/classic/ckeditor.js"></script>
<script>
    const commonConfig = {
        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', '|', 'uploadImage', 'undo', 'redo'],
        simpleUpload: {
            uploadUrl: '<?= base_url('cwpa/dashboard/layanan/upload-image') ?>',
            headers: { 'X-CSRF-TOKEN': '<?= csrf_hash() ?>' }
        }
    };
    document.querySelectorAll('textarea[name="description"], textarea[name="content"]').forEach(el => {
        ClassicEditor.create(el, commonConfig).catch(err => console.error(err));
    });

    const hasPackagesCheckbox = document.getElementById('hasPackages');
    if (hasPackagesCheckbox) {
        hasPackagesCheckbox.addEventListener('change', function() {
            const multiField = document.getElementById('multiPackageField');
            if (this.checked) multiField.classList.remove('hidden');
            else multiField.classList.add('hidden');
        });
    }

    let packageCounter = <?= count($packages ?? []) ?>;
    function addPackageRow() {
        const container = document.getElementById('packagesContainer');
        const id = 'new-pkg-' + packageCounter++;
        const div = document.createElement('div');
        div.id = id;
        div.className = 'bg-black/30 p-3 rounded-lg border border-white/5 relative group';
        div.innerHTML = `
            <button type="button" onclick="removePackageRow('${id}')" class="absolute top-2 right-2 text-gray-500 hover:text-red-400 opacity-0 group-hover:opacity-100 transition">
                <i class="fas fa-times"></i>
            </button>
            <div class="space-y-3">
                <input type="text" name="packages[${id}][name]" placeholder="Nama Paket" class="w-full bg-[#0a0a0a] border border-white/10 rounded px-3 py-2 text-sm" required>
                <input type="number" name="packages[${id}][poin_price]" placeholder="Harga Poin" class="w-full bg-[#0a0a0a] border border-white/10 rounded px-3 py-2 text-sm" required>
                <textarea name="packages[${id}][description]" rows="2" placeholder="Deskripsi" class="w-full bg-[#0a0a0a] border border-white/10 rounded px-3 py-2 text-sm"></textarea>
            </div>
        `;
        container.appendChild(div);
    }
    function removePackageRow(id) { document.getElementById(id).remove(); }
</script>
<style>
    :root { --ck-color-base-background: #0a0a0a; --ck-color-base-border: #333; --ck-color-toolbar-background: #111; --ck-color-text: #e5e7eb; }
    .ck-editor__editable_inline { min-height: 200px; background: #0a0a0a !important; color: #e5e7eb !important; }
    input[type="datetime-local"]::-webkit-calendar-picker-indicator { filter: invert(1); }
</style>
<?= $this->endSection() ?>
