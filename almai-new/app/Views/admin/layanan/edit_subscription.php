<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="<?= base_url('admin/layanan/create') ?>" class="p-2 hover:bg-white/10 rounded-lg">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold">Edit Layanan Subscription</h1>
            <p class="text-gray-400 text-sm">Buat layanan langganan, Private Konsultan, Pendampingan, atau VIP Member.</p>
        </div>
    </div>

    <form action="<?= base_url('admin/layanan/update/' . $layananType . '/' . $layanan['id']) ?>" method="POST" enctype="multipart/form-data" id="layananForm">
        <?= csrf_field() ?>
        <input type="hidden" name="kategori" id="hiddenKategori" value="ultimate">

        <!-- INFORMASI UMUM -->
        <div class="bg-[#111] border border-white/10 rounded-xl p-6 mb-6">
            <h3 class="font-bold mb-6 flex items-center gap-2">
                <i class="fas fa-info-circle text-accent"></i>
                Informasi Umum
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Jenis Langganan <span class="text-red-400">*</span></label>
                    <select name="subcategory" id="subcategorySelect" required class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        <?php $reqSub = $_GET['sub'] ?? ''; ?>
                        <option value="private_konsultan" <?= $layananType == 'private_konsultan' ? 'selected' : '' ?>>>Private Konsultan (Ultimate)</option>
                        <option value="vip_member" <?= $layananType == 'vip_member' ? 'selected' : '' ?>>>VIP Member (Ultimate)</option>
                        <option value="pendampingan" <?= $layananType == 'pendampingan' ? 'selected' : '' ?>>>Pendampingan CWPA (Advokasi)</option>
                        <option value="profirm" <?= $layananType == 'profirm' ? 'selected' : '' ?>>>Profirm (Advokasi)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Spesialis</label>
                    <select name="specialist" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                        <option value="">-- Pilih Spesialis --</option>
                        <option value="Gold" <?= (isset($layanan['specialist']) && $layanan['specialist'] == 'Gold') ? 'selected' : '' ?>>Gold</option>
                        <option value="Forex" <?= (isset($layanan['specialist']) && $layanan['specialist'] == 'Forex') ? 'selected' : '' ?>>Forex</option>
                        <option value="Crypto" <?= (isset($layanan['specialist']) && $layanan['specialist'] == 'Crypto') ? 'selected' : '' ?>>Crypto</option>
                        <option value="Stock" <?= (isset($layanan['specialist']) && $layanan['specialist'] == 'Stock') ? 'selected' : '' ?>>Stock</option>
                        <option value="Index" <?= (isset($layanan['specialist']) && $layanan['specialist'] == 'Index') ? 'selected' : '' ?>>Index</option>
                        <option value="EA" <?= (isset($layanan['specialist']) && $layanan['specialist'] == 'EA') ? 'selected' : '' ?>>EA</option>
                        <option value="AI" <?= (isset($layanan['specialist']) && $layanan['specialist'] == 'AI') ? 'selected' : '' ?>>AI</option>
                        <option value="Profirm" <?= (isset($layanan['specialist']) && $layanan['specialist'] == 'Profirm') ? 'selected' : '' ?>>Profirm</option>
                        <option value="Algorithm" <?= (isset($layanan['specialist']) && $layanan['specialist'] == 'Algorithm') ? 'selected' : '' ?>>Algorithm</option>
                        <option value="Technical Analyst" <?= (isset($layanan['specialist']) && $layanan['specialist'] == 'Technical Analyst') ? 'selected' : '' ?>>Technical Analyst</option>
                        <option value="Risk Management" <?= (isset($layanan['specialist']) && $layanan['specialist'] == 'Risk Management') ? 'selected' : '' ?>>Risk Management</option>
                    </select>
                </div>
            </div>

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Nama Layanan <span class="text-red-400">*</span></label>
                        <input type="text" name="name" value="<?= esc($layanan['name'] ?? '') ?>"   required class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Slug (Opsional)</label>
                        <input type="text" name="slug" value="<?= esc($layanan['slug'] ?? '') ?>"   class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3" placeholder="contoh-nama-layanan">
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Deskripsi Lengkap</label>
                    <textarea name="description" id="editor_description" rows="4" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"><?= esc($layanan['description'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Benefit Layanan (Poin-poin)</label>
                    <textarea name="layanan_utama" rows="3" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3" placeholder="Sebutkan 3-5 benefit utama..."><?= esc($layanan['layanan_utama'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Fitur Tambahan (JSON Format)</label>
                    <textarea name="fitur_unggulan" rows="3" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3 font-mono text-sm" placeholder='[{"name": "Grup VIP", "link": ""}]'><?= esc($layanan['fitur_unggulan'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <!-- DETAIL SUBSCRIPTION -->
        <div class="bg-[#111] border border-white/10 rounded-xl p-6 mb-6">
            <h3 class="font-bold mb-4 flex items-center gap-2">
                <i class="fas fa-crown text-yellow-500"></i>
                Detail Langganan
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Durasi (Hari)</label>
                    <input type="number" name="duration_days" value="<?= esc($layanan['duration_days'] ?? '30') ?>"  class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                    <p class="text-[10px] text-gray-500 mt-1">30 = 1 Bulan</p>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Max Slot Peserta</label>
                    <input type="number" name="max_slots" value="<?= esc($layanan['max_slots'] ?? '0') ?>"  class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                    <p class="text-[10px] text-gray-500 mt-1">0 = Unlimited</p>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Info Jadwal Konsultasi</label>
                    <input type="text" name="schedule_info" value="<?= esc($layanan['schedule_info'] ?? '') ?>"   placeholder="Senin - Jumat, 09:00 - 17:00" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
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
<script>
    document.getElementById('subcategorySelect').addEventListener('change', function() {
        const cat = document.getElementById('hiddenKategori');
        if(['pendampingan', 'profirm'].includes(this.value)) {
            cat.value = 'advokasi';
        } else {
            cat.value = 'ultimate';
        }
    });
    // Trigger on load
    document.getElementById('subcategorySelect').dispatchEvent(new Event('change'));
</script>
<?= $this->endSection() ?>
