<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="w-full">
    <!-- Header Area -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-black text-white tracking-tight">Tambah Transaksi Manual</h1>
            <p class="text-gray-400 mt-1">Input transaksi platform secara manual oleh Superadmin</p>
        </div>
        <a href="<?= base_url('superadmin/transaksi') ?>" class="px-5 py-2.5 bg-[#111] border border-white/10 rounded-xl text-gray-400 hover:text-white transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-500 rounded-2xl flex items-center gap-3">
            <i class="fas fa-exclamation-circle"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <!-- Form Area -->
    <form action="<?= base_url('superadmin/transaksi/store-manual') ?>" method="POST" class="space-y-6">
        <?= csrf_field() ?>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Side: User & Service -->
            <div class="space-y-6">
                <!-- Select User -->
                <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl">
                    <label class="block text-xs uppercase tracking-widest font-black text-accent mb-4">Pilih User</label>
                    <div class="relative group">
                        <select name="user_id" id="user_select" class="w-full px-4 py-3.5 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none transition group-hover:border-white/20 select2-dark" required>
                            <option value="">-- Pilih User --</option>
                            <?php foreach ($users as $u): ?>
                                <option value="<?= $u['id'] ?>" <?= old('user_id') == $u['id'] ? 'selected' : '' ?>>
                                    <?= esc($u['name']) ?> (<?= esc($u['email']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Select Service -->
                <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl">
                    <label class="block text-xs uppercase tracking-widest font-black text-accent mb-4">Pilih Layanan / Produk</label>
                    <div class="relative group">
                        <select name="service_full" id="service_select" class="w-full px-4 py-3.5 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none transition group-hover:border-white/20 select2-dark" required onchange="updatePrice(this)">
                            <option value="">-- Pilih Layanan --</option>
                            <?php foreach ($services as $s): ?>
                                <option value="<?= $s['type'] ?>:<?= $s['id'] ?>" data-price="<?= $s['price'] ?>" <?= old('service_full') == ($s['type'].':'.$s['id']) ? 'selected' : '' ?>>
                                    [<?= strtoupper($s['type']) ?>] <?= esc($s['title']) ?> - Rp <?= number_format($s['price'], 0, ',', '.') ?> <?= !empty($s['wpa_name']) ? '('.esc($s['wpa_name']).')' : '' ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <p class="mt-2 text-[10px] text-gray-500 italic">* Pilih layanan untuk mengisi harga otomatis</p>
                </div>
            </div>

            <!-- Right Side: Details -->
            <div class="space-y-6">
                <!-- Amount -->
                <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl">
                    <label class="block text-xs uppercase tracking-widest font-black text-accent mb-4">Nominal Transaksi (Rp)</label>
                    <div class="relative group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold">Rp</div>
                        <input type="number" name="amount" id="amount_input" value="<?= old('amount') ?>" placeholder="0" class="w-full pl-12 pr-4 py-3.5 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none transition group-hover:border-white/20" required>
                    </div>
                </div>

                <!-- Notes -->
                <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl">
                    <label class="block text-xs uppercase tracking-widest font-black text-accent mb-4">Catatan / Deskripsi (Opsional)</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-3.5 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none transition group-hover:border-white/20 placeholder:text-gray-700" placeholder="Keterangan tambahan untuk transaksi ini..."><?= old('description') ?></textarea>
                </div>
            </div>
        </div>

        <!-- Action Button -->
        <div class="flex justify-end pt-4">
            <button type="submit" class="w-full md:w-auto px-12 py-4 bg-accent text-black font-black uppercase tracking-widest rounded-2xl hover:bg-white hover:scale-[1.02] active:scale-95 transition-all shadow-2xl shadow-accent/20 flex items-center justify-center gap-3">
                <i class="fas fa-plus-circle"></i> Buat Transaksi & Generate Payment Link
            </button>
        </div>
    </form>
</div>

<!-- Load Select2 for better experience -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    .select2-container--default .select2-selection--single {
        background-color: black !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        height: 52px !important;
        border-radius: 12px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: white !important;
        line-height: 50px !important;
        padding-left: 16px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 50px !important;
    }
    .select2-dropdown {
        background-color: #111 !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: white !important;
        border-radius: 12px !important;
        margin-top: 4px;
        overflow: hidden;
    }
    .select2-search__field {
        background-color: black !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: white !important;
        border-radius: 8px !important;
    }
    .select2-results__option--highlighted[aria-selected] {
        background-color: var(--accent) !important;
        color: black !important;
    }
</style>

<script>
    $(document).ready(function() {
        $('#user_select').select2({
            placeholder: "-- Pilih User --"
        });
        $('#service_select').select2({
            placeholder: "-- Pilih Layanan --"
        });
    });

    function updatePrice(select) {
        const selectedOption = select.options[select.selectedIndex];
        const price = selectedOption.getAttribute('data-price');
        if (price) {
            document.getElementById('amount_input').value = price;
        }
    }
</script>

<?= $this->endSection() ?>
