<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="<?= base_url('superadmin/layanan/create') ?>" class="p-2 hover:bg-white/10 rounded-lg">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold">Tambah Layanan Event & Webinar</h1>
            <p class="text-gray-400 text-sm">Buat layanan berupa Webinar, Workshop, atau Live Trade</p>
        </div>
    </div>

    <form action="<?= base_url('superadmin/layanan/store') ?>" method="POST" enctype="multipart/form-data" id="layananForm">
        <?= csrf_field() ?>
        <input type="hidden" name="kategori" value="advokasi">

        <!-- INFORMASI UMUM -->
        <div class="bg-[#111] border border-white/10 rounded-xl p-6 mb-6">
            <h3 class="font-bold mb-6 flex items-center gap-2">
                <i class="fas fa-info-circle text-accent"></i>
                Informasi Umum
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Jenis Event <span class="text-red-400">*</span></label>
                    <select name="subcategory" required class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        <?php $reqSub = $_GET['sub'] ?? ''; ?>
                        <option value="webinar" <?= $reqSub == 'webinar' ? 'selected' : '' ?>>Webinar</option>
                        <option value="workshop" <?= $reqSub == 'workshop' ? 'selected' : '' ?>>Workshop</option>
                        <option value="live_trade" <?= $reqSub == 'live_trade' ? 'selected' : '' ?>>Live Trade</option>
                    </select>
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
            </div>

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Judul Event <span class="text-red-400">*</span></label>
                        <input type="text" name="name" required class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Slug (Opsional)</label>
                        <input type="text" name="slug" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3" placeholder="contoh-judul-event">
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Deskripsi Lengkap</label>
                    <textarea name="description" id="editor_description" rows="4" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"></textarea>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Layanan Utama (Materi yang akan dipelajari)</label>
                    <textarea name="layanan_utama" rows="3" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3" placeholder="Sebutkan 3-5 materi utama..."></textarea>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Fitur Unggulan (JSON Format)</label>
                    <textarea name="fitur_unggulan" rows="3" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3 font-mono text-sm" placeholder='[{"name": "Sertifikat", "link": ""}]'></textarea>
                </div>
            </div>
        </div>

        <!-- JADWAL DAN LOKASI -->
        <div class="bg-[#111] border border-white/10 rounded-xl p-6 mb-6">
            <h3 class="font-bold mb-4 flex items-center gap-2">
                <i class="fas fa-calendar text-purple-500"></i>
                Jadwal & Lokasi
            </h3>
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

        <!-- MEETING INFO -->
        <div class="bg-[#111] border border-white/10 rounded-xl p-6 mb-6">
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

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <?= $this->include('admin/layanan/partials/pricing_referral') ?>
            <?= $this->include('admin/layanan/partials/partner_media') ?>
        </div>

        <div class="flex justify-end pt-6 border-t border-white/10">
            <button type="submit" class="px-8 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center gap-2">
                <i class="fas fa-save"></i> Simpan & Terbitkan
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<?= $this->include('admin/layanan/partials/common_scripts') ?>
<?= $this->endSection() ?>
