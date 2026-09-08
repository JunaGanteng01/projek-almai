<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="flex items-center gap-4">
        <a href="<?= base_url('admin/layanan/create') ?>" class="p-2 hover:bg-white/10 rounded-lg">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold">Tambah Layanan Subscription</h1>
            <p class="text-gray-400 text-sm">Buat layanan langganan, Private Konsultan, Pendampingan, atau VIP Member.</p>
        </div>
    </div>

    <form action="<?= base_url('admin/layanan/store') ?>" method="POST" enctype="multipart/form-data" id="layananForm">
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
                        <option value="private_konsultan" <?= $reqSub == 'private_konsultan' ? 'selected' : '' ?>>Private Konsultan (Ultimate)</option>
                        <option value="vip_member" <?= $reqSub == 'vip_member' ? 'selected' : '' ?>>VIP Member (Ultimate)</option>
                        <option value="pendampingan" <?= $reqSub == 'pendampingan' ? 'selected' : '' ?>>Pendampingan CWPA (Advokasi)</option>
                        <option value="profirm" <?= $reqSub == 'profirm' ? 'selected' : '' ?>>Profirm (Advokasi)</option>
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
                        <label class="block text-sm text-gray-400 mb-2">Nama Layanan <span class="text-red-400">*</span></label>
                        <input type="text" name="name" required class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Slug (Opsional)</label>
                        <input type="text" name="slug" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3" placeholder="contoh-nama-layanan">
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Deskripsi Lengkap</label>
                    <textarea name="description" id="editor_description" rows="4" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3"></textarea>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Benefit Layanan (Poin-poin)</label>
                    <textarea name="layanan_utama" rows="3" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3" placeholder="Sebutkan 3-5 benefit utama..."></textarea>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Fitur Tambahan (JSON Format)</label>
                    <textarea name="fitur_unggulan" rows="3" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3 font-mono text-sm" placeholder='[{"name": "Grup VIP", "link": ""}]'></textarea>
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
                    <input type="number" name="duration_days" value="30" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                    <p class="text-[10px] text-gray-500 mt-1">30 = 1 Bulan</p>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Max Slot Peserta</label>
                    <input type="number" name="max_slots" value="0" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                    <p class="text-[10px] text-gray-500 mt-1">0 = Unlimited</p>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Info Jadwal Konsultasi</label>
                    <input type="text" name="schedule_info" placeholder="Senin - Jumat, 09:00 - 17:00" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
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
