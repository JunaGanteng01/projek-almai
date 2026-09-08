<?= $this->extend('keuangan/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div class="flex items-center gap-4">
        <a href="<?= base_url('keuangan/aset') ?>" class="w-10 h-10 bg-white/5 rounded-xl flex items-center justify-center hover:bg-white/10 transition border border-white/10">
            <i class="fas fa-arrow-left text-gray-400"></i>
        </a>
        <h1 class="text-2xl font-bold text-white"><?= $title ?></h1>
    </div>
    <div class="flex gap-2">
        <button class="px-4 py-2 bg-white/5 text-gray-400 font-bold rounded-xl border border-white/10 hover:bg-white/10 transition flex items-center gap-2">
            <i class="fas fa-question-circle"></i> Panduan <i class="fas fa-chevron-down text-[10px]"></i>
        </button>
    </div>
</div>

<form action="<?= base_url('keuangan/aset/save') ?>" method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= $asset['id'] ?? '' ?>">

    <div class="bg-[#111] border border-white/10 rounded-2xl p-8 shadow-2xl mb-6">
        <h3 class="text-xl font-bold text-white mb-6">Detil</h3>
        
        <div class="mb-6">
            <button type="button" class="text-blue-400 text-sm font-bold flex items-center gap-2 hover:text-blue-300 transition">
                <i class="fas fa-plus"></i> Tampilkan gambar aset tetap
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
            <!-- Left Column -->
            <div class="space-y-6">
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2"><span class="text-red-500">*</span> Nama Aset</label>
                    <input type="text" name="nama_aset" value="<?= $asset['nama_aset'] ?? '' ?>" required class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none transition text-white text-sm" placeholder="Nama Aset">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2"><span class="text-red-500">*</span> Tanggal Pembelian <i class="fas fa-question-circle text-gray-700 ml-1"></i></label>
                    <input type="date" name="tanggal_pembelian" value="<?= $asset['tanggal_pembelian'] ?? date('Y-m-d') ?>" required class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none transition text-white text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2"><span class="text-red-500">*</span> Akun Aset Tetap <i class="fas fa-question-circle text-gray-700 ml-1"></i></label>
                    <select name="akun_aset_kode" required class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none transition text-white text-sm">
                        <option value="">Pilih Akun Aset</option>
                        <?php foreach ($akunAset as $akun): ?>
                            <option value="<?= $akun['kode_akun'] ?>" <?= ($asset['akun_aset_kode'] ?? '') == $akun['kode_akun'] ? 'selected' : '' ?>><?= $akun['kode_akun'] ?> <?= $akun['nama_akun'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Deskripsi</label>
                    <input type="text" name="deskripsi" value="<?= $asset['deskripsi'] ?? '' ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none transition text-white text-sm" placeholder="Deskripsi">
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Nomor <i class="fas fa-question-circle text-gray-700 ml-1"></i></label>
                    <input type="text" name="nomor_aset" value="<?= $asset['nomor_aset'] ?? 'FA/'.date('YmdHis') ?>" required class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none transition text-white text-sm" placeholder="Nomor">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Harga Beli <i class="fas fa-question-circle text-gray-700 ml-1"></i></label>
                    <div class="relative">
                        <input type="number" name="harga_beli" value="<?= (int)($asset['harga_beli'] ?? 0) ?>" required class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-right focus:border-accent focus:outline-none transition text-white text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2"><span class="text-red-500">*</span> Dikreditkan Dari Akun <i class="fas fa-question-circle text-gray-700 ml-1"></i></label>
                    <select name="akun_kredit_kode" required class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none transition text-white text-sm">
                        <option value="">Silakan pilih dikreditkan dari akun</option>
                        <?php foreach ($akunKredit as $akun): ?>
                            <option value="<?= $akun['kode_akun'] ?>" <?= ($asset['akun_kredit_kode'] ?? '') == $akun['kode_akun'] ? 'selected' : '' ?>><?= $akun['kode_akun'] ?> <?= $akun['nama_akun'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Tag <i class="fas fa-question-circle text-gray-700 ml-1"></i></label>
                    <select name="tag" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none transition text-white text-sm">
                        <option value="">Pilih Tag</option>
                        <option value="Kantor" <?= ($asset['tag'] ?? '') == 'Kantor' ? 'selected' : '' ?>>Kantor</option>
                        <option value="Produksi" <?= ($asset['tag'] ?? '') == 'Produksi' ? 'selected' : '' ?>>Produksi</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Referensi</label>
            <input type="text" name="referensi" value="<?= $asset['referensi'] ?? '' ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none transition text-white text-sm" placeholder="Referensi">
        </div>

        <div class="border-t border-white/10 pt-8 mt-8">
            <h3 class="text-xl font-bold text-white mb-6">Penyusutan</h3>
            
            <div class="flex items-center gap-4 mb-8">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_penyusutan" id="isPenyusutan" value="1" <?= ($asset['is_penyusutan'] ?? 1) ? 'checked' : '' ?> class="sr-only peer" onchange="toggleDepreciation()">
                    <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-accent"></div>
                </label>
                <span class="text-sm font-bold text-white">Hitung Penyusutan Aset</span>
            </div>

            <div id="depreciationFields" class="<?= ($asset['is_penyusutan'] ?? 1) ? '' : 'hidden' ?> space-y-6 animate-in fade-in slide-in-from-top-2">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Metode Penyusutan</label>
                        <select name="metode_penyusutan" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none transition text-white text-sm">
                            <option value="Straight Line" <?= ($asset['metode_penyusutan'] ?? '') == 'Straight Line' ? 'selected' : '' ?>>Straight Line (Garis Lurus)</option>
                            <option value="Double Declining" <?= ($asset['metode_penyusutan'] ?? '') == 'Double Declining' ? 'selected' : '' ?>>Double Declining (Saldo Menurun Ganda)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Masa Manfaat (Tahun)</label>
                        <input type="number" name="masa_manfaat" value="<?= $asset['masa_manfaat'] ?? 4 ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none transition text-white text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Akun Akumulasi Penyusutan</label>
                        <select name="akun_akumulasi_kode" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none transition text-white text-sm">
                            <option value="">Pilih Akun Akumulasi</option>
                            <?php foreach ($akunAkumulasi as $akun): ?>
                                <option value="<?= $akun['kode_akun'] ?>" <?= ($asset['akun_akumulasi_kode'] ?? '') == $akun['kode_akun'] ? 'selected' : '' ?>><?= $akun['kode_akun'] ?> <?= $akun['nama_akun'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Akun Beban Penyusutan</label>
                        <select name="akun_penyusutan_kode" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none transition text-white text-sm">
                            <option value="">Pilih Akun Beban</option>
                            <?php foreach ($akunBeban as $akun): ?>
                                <option value="<?= $akun['kode_akun'] ?>" <?= ($asset['akun_penyusutan_kode'] ?? '') == $akun['kode_akun'] ? 'selected' : '' ?>><?= $akun['kode_akun'] ?> <?= $akun['nama_akun'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Nilai Residu</label>
                        <input type="number" name="nilai_residu" value="<?= (int)($asset['nilai_residu'] ?? 0) ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-right focus:border-accent focus:outline-none transition text-white text-sm">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-end gap-4 mb-12">
        <button type="button" onclick="history.back()" class="px-8 py-3 bg-white/5 hover:bg-white/10 text-white font-bold rounded-xl transition border border-white/10">Batal</button>
        <div class="flex">
            <button type="submit" class="px-12 py-3 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-l-xl transition shadow-lg shadow-blue-600/20 flex items-center gap-2">
                <i class="fas fa-save"></i> Simpan
            </button>
            <button type="button" class="px-3 py-3 bg-blue-700 hover:bg-blue-600 text-white rounded-r-xl border-l border-blue-500/50">
                <i class="fas fa-chevron-down text-[10px]"></i>
            </button>
        </div>
    </div>
</form>

<script>
    function toggleDepreciation() {
        const isChecked = document.getElementById('isPenyusutan').checked;
        const fields = document.getElementById('depreciationFields');
        if (isChecked) {
            fields.classList.remove('hidden');
        } else {
            fields.classList.add('hidden');
        }
    }
</script>

<?= $this->endSection() ?>
