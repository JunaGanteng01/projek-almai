<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="<?= base_url('superadmin/layanan') ?>" class="p-2 hover:bg-white/10 rounded-lg">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold">Tambah Layanan</h1>
            <p class="text-gray-400 text-sm">Pilih kategori dan subcategory untuk menampilkan form yang sesuai</p>
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
            <button type="button" onclick="goToStep(2)" id="step-btn-2" class="flex items-center gap-3 px-6 py-3 rounded-xl bg-white/5 text-gray-500 font-bold transition-all whitespace-nowrap opacity-50 cursor-not-allowed" disabled>
                <span class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-sm">2</span>
                Aktivasi EA
            </button>
            <div class="w-12 h-px bg-white/10 mx-2"></div>
            <button type="button" onclick="goToStep(3)" id="step-btn-3" class="flex items-center gap-3 px-6 py-3 rounded-xl bg-white/5 text-gray-500 font-bold transition-all whitespace-nowrap opacity-50 cursor-not-allowed" disabled>
                <span class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-sm">3</span>
                Pelatihan
            </button>
            <div class="w-12 h-px bg-white/10 mx-2"></div>
            <button type="button" onclick="goToStep(4)" id="step-btn-4" class="flex items-center gap-3 px-6 py-3 rounded-xl bg-white/5 text-gray-500 font-bold transition-all whitespace-nowrap opacity-50 cursor-not-allowed" disabled>
                <span class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-sm">4</span>
                Selesai
            </button>
        </div>
    </div>

    <form action="<?= base_url('superadmin/layanan/store') ?>" method="POST" enctype="multipart/form-data" id="wizardForm">
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
                        <select name="kategori" id="kategoriSelect" required class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="advokasi">Advokasi</option>
                            <option value="expert-advisor">Expert Advisor</option>
                            <option value="ultimate">Ultimate</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Subcategory <span class="text-red-400">*</span></label>
                        <select name="subcategory" id="subcategorySelect" required class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3" disabled>
                            <option value="">-- Pilih Subcategory --</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Jenis Modul Laporan <span class="text-xs text-gray-500">(Opsional, untuk menu Sidebar Laporan)</span></label>
                        <select name="jenis_modul" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                            <option value="">-- Tidak Tampil di Laporan --</option>
                            <option value="Seminar FGD">Seminar FGD</option>
                            <option value="Pelatihan Simulasi">Pelatihan Simulasi</option>
                            <option value="Signal">Signal</option>
                            <option value="Konsultasi">Konsultasi</option>
                            <option value="Expert Advisor">Expert Advisor</option>
                            <option value="Kegiatan Lainnya">Kegiatan Lainnya</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Nama/Judul <span class="text-red-400">*</span></label>
                            <input type="text" name="name" required class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Slug (Opsional)</label>
                            <input type="text" name="slug" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3" placeholder="contoh-slug-layanan">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Spesialis</label>
                        <select name="specialist" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                            <option value="">-- Pilih Spesialis --</option>
                            <option value="Gold">Gold</option>
                            <option value="Forex">Forex</option>
                            <option value="Crypto">Crypto</option>
                            <option value="Stock">Stock</option>
                            <option value="Index">Index</option>
                            <option value="EA">EA</option>
                            <option value="AI">AI</option>
                            <option value="Profirm">Profirm</option>
                            <option value="Algorithm">Algorithm</option>
                            <option value="Technical Analyst">Technical Analyst</option>
                            <option value="Risk Management">Risk Management</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Deskripsi</label>
                        <textarea name="description" id="editor_description" rows="4" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Layanan Utama</label>
                        <textarea name="layanan_utama" rows="3" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3" placeholder="Sebutkan 3-5 layanan utama..."></textarea>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Fitur Unggulan (JSON Format)</label>
                        <textarea name="fitur_unggulan" rows="5" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3 font-mono text-sm" placeholder='[{"name": "Software", "link": "https://..."}]'></textarea>
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
                            <input type="datetime-local" name="event_date" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Tanggal & Waktu Selesai</label>
                            <input type="datetime-local" name="event_end_date" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Max Peserta</label>
                            <input type="number" name="max_participants" value="0" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Jumlah Sesi</label>
                            <input type="number" name="total_sessions" value="1" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Lokasi / Info Jadwal</label>
                            <input type="text" name="location" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        </div>
                    </div>

                    <!-- Recurring -->
                    <div class="border-t border-white/10 pt-4 mt-4">
                        <label class="flex items-center gap-2 mb-4 cursor-pointer">
                            <input type="checkbox" name="is_recurring" id="isRecurring" value="1" class="w-5 h-5 rounded bg-[#0a0a0a] border-white/10 text-accent">
                            <span class="font-medium">Jadwal Rutin (Recurring)</span>
                        </label>
                        <div id="recurringOptions" class="hidden grid grid-cols-1 md:grid-cols-3 gap-4 pl-7">
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Frekuensi</label>
                                <select name="recurring_frequency" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                                    <option value="weekly">Mingguan</option>
                                    <option value="daily">Harian</option>
                                    <option value="monthly">Bulanan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Hari</label>
                                <select name="recurring_day" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                                    <option value="Senin">Senin</option>
                                    <option value="Selasa">Selasa</option>
                                    <option value="Rabu">Rabu</option>
                                    <option value="Kamis">Kamis</option>
                                    <option value="Jumat">Jumat</option>
                                    <option value="Sabtu">Sabtu</option>
                                    <option value="Minggu">Minggu</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Jam</label>
                                <input type="time" name="recurring_time" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
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
                        <input type="text" name="version" placeholder="1.0.0" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">URL Dokumentasi</label>
                        <input type="url" name="documentation_url" placeholder="https://..." class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm text-gray-400 mb-2">Changelog</label>
                    <textarea name="changelog" rows="3" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"></textarea>
                </div>
            </div>

            <!-- Detail Subscription (Conditional) -->
            <div id="subscriptionFields" class="hidden bg-[#111] border border-white/10 rounded-xl p-6">
                <h3 class="font-bold mb-4">Detail Langganan/Konsultasi</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Durasi (Hari)</label>
                        <input type="number" name="duration_days" value="30" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Max Slot Peserta</label>
                        <input type="number" name="max_slots" value="0" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Info Jadwal</label>
                        <input type="text" name="schedule_info" placeholder="Senin - Jumat, 09:00 - 17:00" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                    </div>
                </div>
            </div>

            <!-- Detail Artikel (Conditional) -->
            <div id="artikelFields" class="hidden bg-[#111] border border-white/10 rounded-xl p-6">
                <h3 class="font-bold mb-4">Detail Artikel/E-Book</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Excerpt (Ringkasan Singkat)</label>
                        <textarea name="excerpt" rows="2" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Konten Lengkap</label>
                        <textarea name="content" id="editor_content" rows="6" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"></textarea>
                    </div>
                </div>
            </div>

            <!-- Primary File Upload (Conditional) -->
            <div id="fileUploadField" class="hidden bg-[#111] border border-white/10 rounded-xl p-6">
                <h3 class="font-bold mb-4">File Utama Layanan</h3>
                <label class="block text-sm text-gray-400 mb-2">Pilih File <span id="fileTypeHint" class="text-xs text-accent"></span></label>
                <input type="file" name="layanan_file" id="layananFile" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-2 text-sm">
                <p class="text-[10px] text-gray-500 mt-2">File ini adalah produk utama yang akan diunduh oleh pembeli.</p>
            </div>

            <!-- Pricing & Partners -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-[#111] border border-white/10 rounded-xl p-6">
                    <h3 class="font-bold mb-4">Harga & Poin</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 mb-2">
                            <input type="checkbox" name="has_packages" id="hasPackages" value="1" class="w-5 h-5 rounded bg-[#0a0a0a] border-white/10 text-accent">
                            <label for="hasPackages" class="text-sm font-medium">Aktifkan Paket Harga</label>
                        </div>
                        
                        <div id="singlePriceField" class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">Harga Jual (IDR)</label>
                                    <input type="number" name="price" id="priceInput" value="0" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">Harga Poin</label>
                                    <input type="number" name="poin_price" id="poinPrice" value="0" readonly class="w-full bg-[#0a0a0a]/50 text-gray-500 cursor-not-allowed border border-white/10 rounded-lg px-4 py-3">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Harga Diskon (Original)</label>
                                <input type="number" name="original_price" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                            </div>
                        </div>

                        <div id="multiPackageField" class="hidden space-y-4">
                            <div id="packagesContainer" class="space-y-3"></div>
                            <button type="button" onclick="addPackageRow()" class="w-full py-2 border border-dashed border-white/20 rounded-lg text-xs text-gray-400 hover:text-white transition">
                                <i class="fas fa-plus mr-1"></i> Tambah Paket
                            </button>
                        </div>
                    </div>

                    <div class="border-t border-white/10 pt-4 mt-6">
                        <h3 class="font-bold mb-1">Skema Referal</h3>
                        <p class="text-xs text-gray-500 mb-4">Atur distribusi komisi referral. Pool dihitung otomatis dari harga × %. Kedalaman distribusi tetap 8 level.</p>

                        <!-- Hidden fields — auto-calculated by JS before submit -->
                        <input type="hidden" name="referral_user_cash" id="calc_referral_user_cash" value="0">
                        <input type="hidden" name="referral_user_poin" id="calc_referral_user_poin" value="0">
                        <input type="hidden" name="referral_max_depth" value="8">

                        <!-- Toggle Aktifkan Referral -->
                        <div class="flex items-center gap-3 mb-4">
                            <input type="checkbox" id="enableReferral"
                                class="w-5 h-5 rounded bg-[#0a0a0a] border-white/10 text-accent cursor-pointer"
                                onchange="toggleReferralSection(this.checked)">
                            <label for="enableReferral" class="text-sm font-medium cursor-pointer">Aktifkan Skema Referral</label>
                        </div>

                        <div id="referralSection" class="hidden">
                            <div class="mb-4 max-w-xs">
                                <label class="block text-sm text-gray-400 mb-2">Distribusi per Level (%)</label>
                                <div class="relative">
                                    <input type="number" step="0.01" min="0" max="100" id="referral_dist_pct" name="referral_distribution_percentage" value="25"
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
                        <input type="hidden" id="referral_dist_pct_disabled" name="referral_distribution_percentage" value="0">
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
                                        <option value="<?= $wpa['id'] ?>"><?= esc($wpa['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-400 mb-2">Pilih CWPA</label>
                                <select name="cwpa_id" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                                    <option value="">-- Pilih CWPA --</option>
                                    <?php foreach ($cwpaList as $cwpa): ?>
                                        <option value="<?= $cwpa['id'] ?>"><?= esc($cwpa['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Media Thumnail</label>
                            <input type="file" name="thumbnail" accept="image/*" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-2 text-sm">
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
                    <input type="checkbox" name="is_license_product" id="isLicenseProduct" value="1" class="w-6 h-6 rounded bg-[#0a0a0a] border-white/20 text-accent">
                    <label for="isLicenseProduct" class="text-lg font-bold">Berikan Lisensi EA</label>
                </div>

                <div id="licenseFields" class="hidden space-y-6 text-left max-w-lg mx-auto border-t border-white/10 pt-8 mt-4">
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
                            * Kirim request dengan parameter <code>account_number</code> dan <code>license_key</code>.
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
                        <input type="text" name="license_prefix" value="ALMAI-{id_akun}-{random4}" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3 text-sm text-white font-mono">
                        <p class="text-[10px] text-gray-500 mt-2 italic">Format: <strong>ALMAI-{id_akun}-{random4}</strong>. Gunakan placeholder {id_akun} dan {random4} untuk custom.</p>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Durasi Lisensi (Hari)</label>
                        <input type="number" name="license_duration" value="0" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        <p class="text-[10px] text-gray-500 mt-1">Kosongkan atau isi 0 untuk lifetime.</p>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Upload File EA (.ex4, .ex5)</label>
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
                    <input type="hidden" name="youtube_tutorials" id="ytHidden">
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
                    <div id="newResourcesContainer" class="space-y-3"></div>
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
                        <input type="url" name="zoom_link" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Meeting ID</label>
                            <input type="text" name="meeting_id" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Meeting Password</label>
                            <input type="text" name="meeting_password" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
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
                <div class="w-24 h-24 bg-green-500/10 rounded-full flex items-center justify-center mx-auto mb-8 animate-bounce">
                    <i class="fas fa-check-circle text-green-500 text-5xl"></i>
                </div>
                <h3 class="text-3xl font-bold mb-4">Layanan Siap Terbit!</h3>
                <p class="text-gray-400 mb-8">Semua data telah diisi. Pastikan kembali informasi yang Anda masukkan sudah benar sebelum menyimpan.</p>
                
                <div class="bg-black/40 border border-white/5 rounded-xl p-6 text-left mb-8 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Status Awal:</span>
                        <span class="text-accent font-bold">Aktif</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">Target Visibilitas:</span>
                        <span class="text-white">Semua User</span>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <button type="submit" class="w-full py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-lg shadow-lg shadow-accent/20">
                        <i class="fas fa-save mr-2"></i> Simpan & Terbitkan Layanan
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
                btn.removeAttribute('disabled');
            } else if (i < step) {
                btn.classList.remove('bg-white/5', 'text-gray-500', 'opacity-50', 'bg-accent', 'text-black');
                btn.classList.add('bg-green-500/20', 'text-green-500');
                btn.removeAttribute('disabled');
            } else {
                if (btn.hasAttribute('disabled') && i <= step) {
                   btn.removeAttribute('disabled');
                }
            }
        }
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    // Subcategory Mapping
    const subcategoryMap = {
        'advokasi': [
            { value: 'artikel', label: 'Artikel' },
            { value: 'webinar', label: 'Webinar' },
            { value: 'workshop', label: 'Workshop' },
            { value: 'live_trade', label: 'Live Trade' },
            { value: 'pendampingan', label: 'Pendampingan CWPA' },
            { value: 'profirm', label: 'Profirm' }
        ],
        'expert-advisor': [
            { value: 'ea', label: 'Expert Advisor (EA)' }
        ],
        'ultimate': [
            { value: 'toolkit', label: 'Almai Toolkits' },
            { value: 'private_konsultan', label: 'Private Konsultan' },
            { value: 'vip_member', label: 'VIP Member' }
        ]
    };

    // Category Change
    document.getElementById('kategoriSelect').addEventListener('change', function() {
        const subSelect = document.getElementById('subcategorySelect');
        subSelect.innerHTML = '<option value="">-- Pilih Subcategory --</option>';
        if (this.value && subcategoryMap[this.value]) {
            subSelect.disabled = false;
            subcategoryMap[this.value].forEach(sub => {
                const opt = document.createElement('option');
                opt.value = sub.value;
                opt.textContent = sub.label;
                subSelect.appendChild(opt);
            });
        } else {
            subSelect.disabled = true;
        }
    });

    // Subcategory Change
    document.getElementById('subcategorySelect').addEventListener('change', function() {
        const sub = this.value;
        const eventFields = document.getElementById('eventFields');
        const webinarFields = document.getElementById('webinarFields');
        const toolsFields = document.getElementById('toolsFields');
        const subscriptionFields = document.getElementById('subscriptionFields');
        const artikelFields = document.getElementById('artikelFields');
        const fileUploadField = document.getElementById('fileUploadField');
        const fileTypeHint = document.getElementById('fileTypeHint');
        const layananFile = document.getElementById('layananFile');
        
        // Hide all first
        [eventFields, webinarFields, toolsFields, subscriptionFields, artikelFields, fileUploadField].forEach(el => el.classList.add('hidden'));

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

    // Packages Logic
    let packageCounter = 0;

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
    // Init: referral off by default on create
    toggleReferralSection(false);

    function getDefaultDistPct() {
        return parseFloat(document.getElementById('referral_dist_pct')?.value || 25);
    }
    window.addPackageRow = function() {
        const container = document.getElementById('packagesContainer');
        const isEnabled = document.getElementById('hasPackages').checked;
        const id = `pkg-${packageCounter}`;
        container.insertAdjacentHTML('beforeend', `
            <div id="${id}" class="bg-black/30 p-3 rounded-lg border border-white/5 relative group">
                <button type="button" onclick="document.getElementById('${id}').remove();updateReferralPreview();" class="absolute top-2 right-2 text-gray-500 hover:text-red-400 opacity-0 group-hover:opacity-100 transition"><i class="fas fa-times"></i></button>
                <input type="text" name="packages[${packageCounter}][name]" placeholder="Nama Paket" class="w-full bg-[#0a0a0a] border border-white/10 rounded px-3 py-2 text-sm mb-2" ${isEnabled ? 'required' : 'disabled'}>
                <div class="grid grid-cols-2 gap-3">
                    <input type="number" name="packages[${packageCounter}][price]" placeholder="Harga" class="w-full bg-[#0a0a0a] border border-white/10 rounded px-3 py-2 text-sm" ${isEnabled ? 'required' : 'disabled'}>
                    <input type="number" name="packages[${packageCounter}][original_price]" placeholder="Harga Coret" class="w-full bg-[#0a0a0a] border border-white/10 rounded px-3 py-2 text-sm" ${isEnabled ? '' : 'disabled'}>
                </div>
            </div>
        `);
        packageCounter++;
        updateReferralPreview();
    };

    // Toggle Multi Package
    if (document.getElementById('hasPackages')) {
        const togglePackages = function() {
            const isChecked = document.getElementById('hasPackages').checked;
            const singleSection = document.getElementById('singlePriceField');
            const multiSection = document.getElementById('multiPackageField');
            
            if (singleSection) singleSection.classList.toggle('hidden', isChecked);
            if (multiSection) multiSection.classList.toggle('hidden', !isChecked);
            
            // Disable inputs in hidden section so they don't trigger validation
            if (singleSection) {
                singleSection.querySelectorAll('input').forEach(input => {
                    input.disabled = isChecked;
                });
            }
            
            if (multiSection) {
                multiSection.querySelectorAll('input').forEach(input => {
                    input.disabled = !isChecked;
                });
            }

            if (isChecked && document.getElementById('packagesContainer') && document.getElementById('packagesContainer').children.length === 0) {
                if (typeof window.addPackageRow === 'function') addPackageRow();
            }
            if (typeof updateReferralPreview === 'function') updateReferralPreview();
        };

        document.getElementById('hasPackages').addEventListener('change', togglePackages);
        
        // Ensure state is correct on load
        togglePackages();
    }

    // Auto calculate Poin Price (IDR / 100)
    const priceInput = document.querySelector('input[name="price"]');
    const poinInput = document.getElementById('poinPrice');
    if (priceInput && poinInput) {
        priceInput.addEventListener('input', function() {
            poinInput.value = Math.floor(this.value / 100);
        });
    }

    // Resources
    window.addResourceRow = function() {
        const container = document.getElementById('newResourcesContainer');
        const div = document.createElement('div');
        div.className = 'p-3 bg-white/5 border border-white/10 rounded-lg relative';
        div.innerHTML = `
            <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-gray-500 hover:text-red-400"><i class="fas fa-times"></i></button>
            <input type="text" name="resource_titles[]" placeholder="Judul Materi" class="w-full bg-black/50 border border-white/10 rounded px-3 py-2 text-sm mb-2">
            <input type="file" name="resources[]" required class="text-xs">
        `;
        container.appendChild(div);
    };

    // YouTube rows
    window.addYtRow = function() {
        const container = document.getElementById('ytContainer');
        const div = document.createElement('div');
        div.className = 'p-3 bg-white/5 border border-white/10 rounded-lg space-y-2 relative';
        div.innerHTML = `
            <button type="button" onclick="this.parentElement.remove()" class="absolute top-2 right-2 text-gray-500 hover:text-red-400"><i class="fas fa-times"></i></button>
            <input type="text" placeholder="Judul Video" class="yt-title w-full bg-black/50 border border-white/10 rounded px-3 py-2 text-sm">
            <input type="url" placeholder="URL YouTube" class="yt-url w-full bg-black/50 border border-white/10 rounded px-3 py-2 text-sm">
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
    const DEPTH = 8;
    const POIN_RATE = 1000; // 1 poin = Rp 1.000

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

    // Trigger preview on price / package name / referral_distribution_percentage change
    document.addEventListener('input', function(e) {
        if (e.target.name === 'price' || e.target.name?.includes('[price]') || e.target.name?.includes('[name]') || e.target.name?.includes('[referral_distribution_percentage]')) {
            updateReferralPreview();
        }
    });

    // Init preview on load
    updateReferralPreview();
</script>
<?= $this->endSection() ?>
