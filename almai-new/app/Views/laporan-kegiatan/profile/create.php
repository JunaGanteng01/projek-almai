<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>
<div class="w-full">
    <div class="mb-6 flex flex-col md:flex-row justify-between md:items-center gap-4 px-6 md:px-0">
        <h1 class="text-xl md:text-2xl font-black text-white uppercase tracking-tight">Edit Profil</h1>
        <a href="<?= base_url('laporan-kegiatan/profile') ?>" class="bg-black/50 hover:bg-black border border-white/10 text-white px-6 py-2 rounded-lg text-xs font-bold uppercase tracking-widest shadow transition flex items-center justify-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form action="<?= base_url('laporan-kegiatan/profile/updateAll') ?>" method="POST" id="form-profile" class="space-y-8">
        <?= csrf_field() ?>
        
        <!-- Identitas Perusahaan -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl">
            <h3 class="text-lg font-black text-accent uppercase tracking-wider mb-6 flex items-center gap-2">
                <i class="fas fa-building"></i> Identitas Perusahaan
            </h3>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Nama Perusahaan</label>
                    <input type="text" name="nama_perusahaan" value="<?= esc($profile['identitas']['nama_perusahaan'] ?? '') ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-accent focus:ring-1 focus:ring-accent outline-none">
                </div>
                <div>
                    <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">BAPPEBTI : Izin Penasihat Berjangka</label>
                    <input type="text" name="no_izin_bappebti" value="<?= esc($profile['identitas']['no_izin_bappebti'] ?? '') ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-accent focus:ring-1 focus:ring-accent outline-none">
                </div>
                <!-- Izin Bappebti - 2 -->
<div>
    <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">
        BAPPEBTI | Persetujuan Penasihat Berjangka EA
    </label>
    <input type="text"
        name="no_izin_bappebti_2"
        value="<?= esc($profile['identitas']['no_izin_bappebti_2'] ?? '') ?>"
        class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-accent focus:ring-1 focus:ring-accent outline-none">


                </div>
                <!-- Persetujuan OJK -->
<div>
    <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">
        OJK | Persetujuan Penasihat Investasi Derivatif & Aset Digital
    </label>
    <input type="text"
        name="persetujuan_ojk"
        value="<?= esc($profile['identitas']['persetujuan_ojk'] ?? '') ?>"
        class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm text-white">
</div>

<!-- Persetujuan Bank Indonesia -->
<div>
    <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">
        Bank Indonesia | Persetujuan Pelaku Usaha Derivatif PUVA
    </label>
    <input type="text"
        name="persetujuan_bi"
        value="<?= esc($profile['identitas']['persetujuan_bi'] ?? '') ?>"
        class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm text-white">
</div>

<!-- Izin Komdigi -->
<div>
    <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">
        Komdigi | Izin Penyelenggara Sistem Elektronik
    </label>
    <input type="text"
        name="izin_komdigi"
        value="<?= esc($profile['identitas']['izin_komdigi'] ?? '') ?>"
        class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm text-white">
</div>
                <div class="md:col-span-2">
                    <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Alamat Kantor</label>
                    <textarea name="alamat" rows="2" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-accent focus:ring-1 focus:ring-accent outline-none"><?= esc($profile['identitas']['alamat'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Kota</label>
                    <input type="text" name="kota" value="<?= esc($profile['identitas']['kota'] ?? '') ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-accent focus:ring-1 focus:ring-accent outline-none">
                </div>
                <div>
                    <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Provinsi</label>
                    <input type="text" name="provinsi" value="<?= esc($profile['identitas']['provinsi'] ?? '') ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-accent focus:ring-1 focus:ring-accent outline-none">
                </div>
                <div>
                    <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Kode Pos</label>
                    <input type="text" name="kode_pos" value="<?= esc($profile['identitas']['kode_pos'] ?? '') ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-accent focus:ring-1 focus:ring-accent outline-none">
                </div>
                <div>
                    <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">No. Telepon</label>
                    <input type="text" name="no_telp" value="<?= esc($profile['identitas']['no_telp'] ?? '') ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-accent focus:ring-1 focus:ring-accent outline-none">
                </div>
                <div>
                    <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Email Perusahaan</label>
                    <input type="email" name="email" value="<?= esc($profile['identitas']['email'] ?? '') ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-accent focus:ring-1 focus:ring-accent outline-none">
                </div>
                <div>
                    <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Website / Platform</label>
                    <input type="text" name="website" value="<?= esc($profile['identitas']['website'] ?? '') ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-accent focus:ring-1 focus:ring-accent outline-none">
                </div>
            </div>
<!-- ================= Rekomendasi Bursa ================= -->
<div class="md:col-span-2 border-t border-white/10 pt-6 mt-2">
    <h4 class="text-accent text-sm font-black uppercase tracking-wider flex items-center gap-2 mb-4">
        <i class="fas fa-chart-line"></i>
        Rekomendasi Bursa
    </h4>
</div>

<!-- ================= Rekomendasi Bursa ================= -->
<div class="md:col-span-2">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Bursa Berjangka -->
        <div>
            <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-2">
                Bursa Berjangka
            </label>
            <input
                type="text"
                name="rekomendasi_bursa_berjangka"
                value="<?= esc($profile['identitas']['rekomendasi_bursa_berjangka'] ?? '') ?>"
                placeholder="Contoh: ICDX"
                class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-accent focus:ring-1 focus:ring-accent outline-none">
        </div>

        <!-- Bursa Kripto -->
        <div>
            <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-2">
                Bursa Kripto
            </label>
            <input
                type="text"
                name="rekomendasi_bursa_kripto"
                value="<?= esc($profile['identitas']['rekomendasi_bursa_kripto'] ?? '') ?>"
                placeholder="Contoh: CFX"
                class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-accent focus:ring-1 focus:ring-accent outline-none">
        </div>

    </div>
</div>

        <!-- Pejabat -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl">
            <h3 class="text-lg font-black text-accent uppercase tracking-wider mb-6 flex items-center gap-2">
                <i class="fas fa-users-tie"></i> Pejabat & Kontak Perusahaan
            </h3>
            <div class="grid md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Nama Direktur Utama</label>
                    <input type="text" name="nama_dirut" value="<?= esc($profile['pejabat']['nama_dirut'] ?? '') ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-accent focus:ring-1 focus:ring-accent outline-none">
                </div>
                <div>
                    <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">No. Telepon Direktur</label>
                    <input type="text" name="telp_dirut" value="<?= esc($profile['pejabat']['telp_dirut'] ?? '') ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-accent focus:ring-1 focus:ring-accent outline-none">
                </div>
                <div>
                    <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Email Direktur</label>
                    <input type="text" name="email_dirut" value="<?= esc($profile['pejabat']['email_dirut'] ?? '') ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-accent focus:ring-1 focus:ring-accent outline-none">
                </div>
                <div class="col-span-1 md:col-span-3 border-t border-white/5 my-2"></div>
                <div>
                    <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Nama Kontak Person</label>
                    <input type="text" name="nama_kontak" value="<?= esc($profile['pejabat']['nama_kontak'] ?? '') ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-accent focus:ring-1 focus:ring-accent outline-none">
                </div>
                <div>
                    <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">No. HP Kontak Person</label>
                    <input type="text" name="hp_kontak" value="<?= esc($profile['pejabat']['hp_kontak'] ?? '') ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-accent focus:ring-1 focus:ring-accent outline-none">
                </div>
                <div>
                    <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Email Kontak Person</label>
                    <input type="text" name="email_kontak" value="<?= esc($profile['pejabat']['email_kontak'] ?? '') ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-accent focus:ring-1 focus:ring-accent outline-none">
                </div>
            </div>
        </div>

        <!-- Produk & Layanan (Dynamic list without popups) -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-black text-accent uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-box"></i> Produk & Layanan
                </h3>
                <button type="button" onclick="addProduct()" class="bg-white/10 border border-white/20 text-white px-4 py-2 rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-white/20 transition">
                    <i class="fas fa-plus mr-1"></i> Tambah Produk
                </button>
            </div>
            <div id="product-list" class="space-y-6">
                <!-- Rendered via JS -->
            </div>
            <input type="hidden" name="produk_layanan" id="produk_layanan_input">
        </div>

        <!-- Kualifikasi Pengguna (Dynamic list without popups) -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-black text-accent uppercase tracking-wider flex items-center gap-2">
                    <i class="fas fa-id-card"></i> Kualifikasi Pengguna
                </h3>
                <button type="button" onclick="addQual()" class="bg-white/10 border border-white/20 text-white px-4 py-2 rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-white/20 transition">
                    <i class="fas fa-plus mr-1"></i> Tambah Kualifikasi
                </button>
            </div>
            <div id="qual-list" class="space-y-6">
                <!-- Rendered via JS -->
            </div>
            <input type="hidden" name="kualifikasi_pengguna" id="kualifikasi_pengguna_input">
        </div>

        <!-- Penjelasan WPA -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl">
            <h3 class="text-lg font-black text-accent uppercase tracking-wider mb-6 flex items-center gap-2">
                <i class="fas fa-info-circle"></i> Penjelasan WPA & CWPA
            </h3>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Wakil Penasihat Berjangka (WPA)</label>
                    <textarea name="wpa_desc" rows="6" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm text-gray-300 focus:border-accent focus:ring-1 focus:ring-accent outline-none"><?= esc($profile['penjelasan_wpa_cwpa']['wpa'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Calon Wakil Penasihat Berjangka (CWPA)</label>
                    <textarea name="cwpa_desc" rows="6" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm text-gray-300 focus:border-accent focus:ring-1 focus:ring-accent outline-none"><?= esc($profile['penjelasan_wpa_cwpa']['cwpa'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-4 pb-12">
            <a href="<?= base_url('laporan-kegiatan/profile') ?>" class="bg-white/5 border border-white/10 text-white px-8 py-3 rounded-full text-xs font-bold uppercase tracking-widest hover:bg-white/10 transition-colors">
                Batal
            </a>
            <button type="button" onclick="submitForm()" class="bg-accent text-black px-8 py-3 rounded-full text-xs font-bold uppercase tracking-widest hover:bg-white transition-colors">
                <i class="fas fa-save mr-2"></i> Simpan Semua Perubahan
            </button>
        </div>
    </form>
</div>

<script>
    // Initial data from server
    let products = <?= json_encode($profile['produk_layanan'] ?? []) ?>;
    let quals = <?= json_encode($profile['kualifikasi_pengguna'] ?? []) ?>;

    function renderProducts() {
        const container = document.getElementById('product-list');
        container.innerHTML = '';
        if (products.length === 0) {
            container.innerHTML = '<p class="text-gray-500 text-sm italic">Belum ada produk. Klik tombol tambah untuk menambahkan.</p>';
            return;
        }

        products.forEach((p, i) => {
            const el = document.createElement('div');
            el.className = 'p-5 bg-black border border-white/10 rounded-xl relative group';
            el.innerHTML = `
                <button type="button" onclick="removeProduct(${i})" class="absolute top-4 right-4 text-red-500 opacity-50 hover:opacity-100 transition"><i class="fas fa-trash"></i></button>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mr-8">
                    <div>
                        <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Kategori Layanan</label>
                        <input type="text" value="${p.kategori || ''}" onchange="updateProduct(${i}, 'kategori', this.value)" class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-sm text-white">
                    </div>
                    <div>
                        <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Nama Produk / Layanan</label>
                        <input type="text" value="${p.nama || ''}" onchange="updateProduct(${i}, 'nama', this.value)" class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-sm text-white">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Deskripsi Singkat</label>
                        <textarea rows="2" onchange="updateProduct(${i}, 'deskripsi', this.value)" class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-sm text-white">${p.deskripsi || ''}</textarea>
                    </div>
                    <div>
                        <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Regulasi / Dasar Hukum</label>
                        <input type="text" value="${p.regulasi || ''}" onchange="updateProduct(${i}, 'regulasi', this.value)" class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-sm text-white">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Status</label>
                            <select onchange="updateProduct(${i}, 'status', this.value)" class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-sm text-white [&>option]:bg-black">
                                <option value="Aktif" ${p.status === 'Aktif' ? 'selected' : ''}>Aktif</option>
                                <option value="Non-Aktif" ${p.status === 'Non-Aktif' ? 'selected' : ''}>Non-Aktif</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Keterangan</label>
                            <input type="text" value="${p.keterangan || ''}" onchange="updateProduct(${i}, 'keterangan', this.value)" class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-sm text-white">
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(el);
        });
    }

    function renderQuals() {
        const container = document.getElementById('qual-list');
        container.innerHTML = '';
        if (quals.length === 0) {
            container.innerHTML = '<p class="text-gray-500 text-sm italic">Belum ada kualifikasi. Klik tombol tambah untuk menambahkan.</p>';
            return;
        }

        quals.forEach((q, i) => {
            const el = document.createElement('div');
            el.className = 'p-5 bg-black border border-white/10 rounded-xl relative group';
            el.innerHTML = `
                <button type="button" onclick="removeQual(${i})" class="absolute top-4 right-4 text-red-500 opacity-50 hover:opacity-100 transition"><i class="fas fa-trash"></i></button>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mr-8">
                    <div>
                        <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Tipe Pengguna</label>
                        <input type="text" value="${q.tipe || ''}" onchange="updateQual(${i}, 'tipe', this.value)" class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-sm text-white">
                    </div>
                    <div>
                        <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Hak Akses</label>
                        <input type="text" value="${q.akses || ''}" onchange="updateQual(${i}, 'akses', this.value)" class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-sm text-white">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Kewajiban / Prasyarat</label>
                        <textarea rows="2" onchange="updateQual(${i}, 'kewajiban', this.value)" class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-sm text-white">${q.kewajiban || ''}</textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Dapat Nasihat?</label>
                            <select onchange="updateQual(${i}, 'nasihat', this.value)" class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-sm text-white [&>option]:bg-black">
                                <option value="Ya" ${q.nasihat === 'Ya' ? 'selected' : ''}>Ya</option>
                                <option value="Tidak" ${q.nasihat === 'Tidak' ? 'selected' : ''}>Tidak</option>
                                <option value="Terbatas" ${q.nasihat === 'Terbatas' ? 'selected' : ''}>Terbatas</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Diawasi Oleh</label>
                            <input type="text" value="${q.diawasi || ''}" onchange="updateQual(${i}, 'diawasi', this.value)" class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-sm text-white">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] text-gray-500 uppercase tracking-widest mb-1">Keterangan</label>
                        <input type="text" value="${q.keterangan || ''}" onchange="updateQual(${i}, 'keterangan', this.value)" class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-sm text-white">
                    </div>
                </div>
            `;
            container.appendChild(el);
        });
    }

    // Handlers
    function addProduct() {
        products.push({kategori:'', nama:'', deskripsi:'', regulasi:'', status:'Aktif', keterangan:''});
        renderProducts();
    }
    function updateProduct(index, field, value) { products[index][field] = value; }
    function removeProduct(index) { products.splice(index, 1); renderProducts(); }

    function addQual() {
        quals.push({tipe:'', akses:'', kewajiban:'', nasihat:'Tidak', diawasi:'', keterangan:''});
        renderQuals();
    }
    function updateQual(index, field, value) { quals[index][field] = value; }
    function removeQual(index) { quals.splice(index, 1); renderQuals(); }

    function submitForm() {
        document.getElementById('produk_layanan_input').value = JSON.stringify(products);
        document.getElementById('kualifikasi_pengguna_input').value = JSON.stringify(quals);
        document.getElementById('form-profile').submit();
    }

    // Initial render
    renderProducts();
    renderQuals();
</script>
<?= $this->endSection() ?>
