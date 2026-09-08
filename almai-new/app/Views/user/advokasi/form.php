<?= $this->extend('user/partials/layout') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto py-8">
    <!-- Header Section -->
    <div class="mb-10 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-accent/20 rounded-2xl flex items-center justify-center text-accent">
                <i class="fas fa-file-signature text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-white uppercase tracking-tight">Buat Laporan Advokasi</h1>
                <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mt-1">Lengkapi data pengaduan Anda</p>
            </div>
        </div>
        <a href="<?= base_url('user/advokasi') ?>" class="px-5 py-2.5 bg-white/5 border border-white/10 text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-white/10 transition flex items-center gap-2">
            <i class="fas fa-arrow-left text-[10px]"></i> Kembali
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-[#111] border border-white/10 rounded-[2.5rem] p-8 md:p-12 shadow-2xl relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-accent/5 blur-3xl pointer-events-none"></div>

        <form id="reportForm" action="<?= base_url('user/advokasi/submit-laporan') ?>" method="POST" enctype="multipart/form-data" class="relative z-10">
            <?= csrf_field() ?>

            <!-- Bagian 1: Identitas User (Locked) -->
            <div class="border-b border-white/5 pb-10 mb-10">
                <div class="flex items-center gap-3 mb-8">
                    <h3 class="text-xs font-black text-accent uppercase tracking-[0.2em]">01. Data Identitas Terverifikasi</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] text-gray-600 uppercase font-black mb-3 ml-1 tracking-widest">Nomor KTP (Locked)</label>
                        <div class="relative">
                            <input type="text" name="ktp_number" value="<?= esc($user['ktp_number'] ?? '') ?>" readonly 
                                class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-gray-400 focus:outline-none cursor-not-allowed">
                            <i class="fas fa-lock absolute right-6 top-1/2 -translate-y-1/2 text-gray-700 text-xs"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] text-gray-600 uppercase font-black mb-3 ml-1 tracking-widest">Alamat (Locked)</label>
                        <div class="relative">
                            <input type="text" name="address" value="<?= esc($user['address'] ?? '') ?>" readonly 
                                class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-gray-400 focus:outline-none cursor-not-allowed">
                            <i class="fas fa-lock absolute right-6 top-1/2 -translate-y-1/2 text-gray-700 text-xs"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bagian 2: Akun Trading -->
            <div class="border-b border-white/5 pb-10 mb-10">
                <div class="flex items-center gap-3 mb-8">
                    <h3 class="text-xs font-black text-accent uppercase tracking-[0.2em]">02. Informasi Akun & Broker</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] text-gray-500 uppercase font-black mb-3 ml-1 tracking-widest">Nama Broker / Platform <span class="text-red-500">*</span></label>
                        <input type="text" name="broker_name" required placeholder="Masukan Nama Broker" 
                            class="w-full bg-black/40 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-white focus:border-accent focus:outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] text-gray-500 uppercase font-black mb-3 ml-1 tracking-widest">Jenis Trading</label>
                        <select name="trading_type" class="w-full bg-black/40 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-white focus:border-accent focus:outline-none transition-all appearance-none cursor-pointer">
                            <option value="">Pilih Jenis</option>
                            <option value="Forex">Forex</option>
                            <option value="Crypto">Crypto</option>
                            <option value="Stock">Stock</option>
                            <option value="Gold">Gold</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] text-gray-500 uppercase font-black mb-3 ml-1 tracking-widest">ID Akun Trading</label>
                        <input type="text" name="trading_account" placeholder="Nomor Akun" 
                            class="w-full bg-black/40 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-white focus:border-accent focus:outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] text-gray-500 uppercase font-black mb-3 ml-1 tracking-widest">Server Broker</label>
                        <input type="text" name="broker_server" placeholder="Masukan Server Broker" 
                            class="w-full bg-black/40 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-white focus:border-accent focus:outline-none transition-all">
                    </div>
                    <div>
                        <label class="block text-[10px] text-gray-500 uppercase font-black mb-3 ml-1 tracking-widest">Password Trading (Opsional)</label>
                        <input type="password" name="trading_password" placeholder="Password" 
                            class="w-full bg-black/40 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-white focus:border-accent focus:outline-none transition-all">
                    </div>
                </div>
            </div>

            <!-- Bagian 3: Detail Masalah -->
            <div class="border-b border-white/5 pb-10 mb-10">
                <div class="flex items-center gap-3 mb-8">
                    <h3 class="text-xs font-black text-accent uppercase tracking-[0.2em]">03. Detail Permasalahan</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] text-gray-500 uppercase font-black mb-3 ml-1 tracking-widest">Kategori Masalah</label>
                        <select name="category_problem" id="mainCategory" onchange="updateSubCategories()" class="w-full bg-black/40 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-white focus:border-accent focus:outline-none transition-all appearance-none cursor-pointer">
                            <option value="">Pilih Kategori</option>
                            <option value="Masalah Transaksi">Masalah Transaksi</option>
                            <option value="Masalah Deposit & Withdraw">Masalah Deposit & Withdraw</option>
                            <option value="Masalah Platform / Sistem">Masalah Platform / Sistem</option>
                            <option value="Masalah Akun">Masalah Akun</option>
                            <option value="Masalah Broker / Legalitas">Masalah Broker / Legalitas</option>
                            <option value="Masalah Lainnya">Masalah Lainnya</option>
                        </select>
                    </div>
                    <div id="subCategoryArea" class="hidden">
                        <label class="block text-[10px] text-gray-500 uppercase font-black mb-3 ml-1 tracking-widest">Sub Kategori</label>
                        <select name="sub_category" id="subCategory" class="w-full bg-black/40 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-white focus:border-accent focus:outline-none transition-all appearance-none cursor-pointer"></select>
                    </div>
                    <div>
                        <label class="block text-[10px] text-gray-500 uppercase font-black mb-3 ml-1 tracking-widest text-white">Tanggal Kejadian</label>
                        <input type="date" name="incident_date" 
                            class="w-full bg-black/40 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-white focus:border-accent focus:outline-none transition-all [color-scheme:dark]">
                    </div>
                    <div>
                        <label class="block text-[10px] text-gray-500 uppercase font-black mb-3 ml-1 tracking-widest">Estimasi Kerugian</label>
                        <div class="relative">
                            <span class="absolute left-6 top-1/2 -translate-y-1/2 text-accent font-bold text-sm">Rp</span>
                            <input type="text" name="loss_amount" id="loss_amount" onkeyup="formatRupiah(this)" placeholder="0"
                                class="w-full bg-black/40 border border-white/10 rounded-2xl pl-14 pr-6 py-4 text-sm font-bold text-accent focus:border-accent focus:outline-none transition-all">
                        </div>
                    </div>
                </div>
                <div class="mt-8">
                    <label class="block text-[10px] text-gray-500 uppercase font-black mb-3 ml-1 tracking-widest">Kronologi Kejadian (minimal 10 kata) <span class="text-red-500">*</span></label>
                    <textarea name="chronology" required rows="5" placeholder="Jelaskan detail kejadian secara urut (minimal 10 kata)..."
                        class="w-full bg-black/40 border border-white/10 rounded-2xl px-6 py-6 text-sm font-medium text-white focus:border-accent focus:outline-none transition-all resize-none"></textarea>
                </div>
            </div>

            <!-- Bagian 4: Lampiran -->
            <div class="mb-12">
                <div class="flex items-center gap-3 mb-8">
                    <h3 class="text-xs font-black text-accent uppercase tracking-[0.2em]">04. Lampiran Bukti</h3>
                </div>
                <div class="relative">
                    <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-white/10 rounded-3xl bg-white/5 hover:border-accent hover:bg-accent/5 transition-all cursor-pointer group">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <i class="fas fa-cloud-upload-alt text-2xl text-gray-600 mb-2 group-hover:text-accent transition-all"></i>
                            <p class="text-xs font-bold text-gray-500 group-hover:text-white" id="file_name_display">Klik untuk unggah screenshot atau PDF bukti</p>
                        </div>
                        <input type="file" name="evidence" accept="image/*,application/pdf" class="hidden" onchange="handleFileChange(this)">
                    </label>
                </div>
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full py-5 bg-accent text-black font-black uppercase tracking-[0.3em] rounded-2xl hover:bg-white hover:shadow-[0_0_50px_rgba(51,232,24,0.3)] transition-all flex items-center justify-center gap-3 text-xs">
                Kirim Laporan Pengaduan
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>
    </div>
</div>

<script>
    const subCategories = {
        "Masalah Transaksi": ["Order tidak tereksekusi", "Slippage terlalu besar", "Price manipulation", "Requote berulang"],
        "Masalah Deposit & Withdraw": ["Deposit tidak masuk", "Withdraw tertunda", "Penolakan penarikan", "Biaya tidak transparan"],
        "Masalah Platform / Sistem": ["Aplikasi error", "Tidak bisa login", "Server down", "Data chart tidak akurat"],
        "Masalah Akun": ["Akun diblokir / suspend", "Verifikasi (KYC) ditolak", "Akun diretas", "Perubahan data tanpa izin"],
        "Masalah Broker / Legalitas": ["Broker tidak teregulasi", "Indikasi scam", "Manipulasi harga", "Konflik kepentingan"],
        "Masalah Lainnya": ["Lain-lain"]
    };

    function updateSubCategories() {
        const main = document.getElementById('mainCategory').value;
        const subArea = document.getElementById('subCategoryArea');
        const subSelect = document.getElementById('subCategory');
        if (subCategories[main]) {
            subSelect.innerHTML = '<option value="">Pilih Sub Kategori (Opsional)</option>' + subCategories[main].map(c => `<option value="${c}">${c}</option>`).join('');
            subArea.classList.remove('hidden');
        } else { subArea.classList.add('hidden'); }
    }

    function formatRupiah(input) {
        let value = input.value.replace(/[^0-9]/g, '');
        if (value) { input.value = new Intl.NumberFormat('id-ID').format(value); }
    }

    function handleFileChange(input) {
        const display = document.getElementById('file_name_display');
        if (input.files && input.files[0]) {
            display.textContent = '✓ ' + input.files[0].name;
            display.classList.replace('text-gray-500', 'text-accent');
        }
    }
</script>

<?= $this->endSection() ?>
