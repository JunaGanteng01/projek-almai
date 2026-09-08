<?= $this->extend('wpa/layouts/main') ?>

<?= $this->section('content') ?>

<div class="max-w-4xl mx-auto py-8">
    <div class="mb-10 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-accent/20 rounded-2xl flex items-center justify-center text-accent">
                <i class="fas fa-file-shield text-xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-white uppercase tracking-tight">Partner Advocacy Report</h1>
                <p class="text-gray-500 text-[10px] font-black uppercase tracking-[0.2em] mt-1">Submit New Case for Partner Client</p>
            </div>
        </div>
        <a href="<?= base_url('wpa/dashboard/advokasi') ?>" class="px-5 py-2.5 bg-white/5 border border-white/10 text-white text-xs font-bold uppercase tracking-widest rounded-xl hover:bg-white/10 transition flex items-center gap-2">
            <i class="fas fa-arrow-left text-[10px]"></i> Kembali
        </a>
    </div>

    <div class="bg-[#111] border border-white/10 rounded-[2.5rem] p-8 md:p-12 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-accent/5 blur-3xl pointer-events-none"></div>

        <form id="reportForm" action="<?= base_url('wpa/dashboard/advokasi/submit-laporan') ?>" method="POST" enctype="multipart/form-data" class="relative z-10">
            <?= csrf_field() ?>

            <!-- Data Identitas -->
            <div class="border-b border-white/5 pb-10 mb-10">
                <h3 class="text-xs font-black text-accent uppercase tracking-[0.2em] mb-8">01. Partner Identification</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] text-gray-600 uppercase font-black mb-3 ml-1">Verified KTP Number</label>
                        <input type="text" name="ktp_number" value="<?= esc($user['ktp_number'] ?? '') ?>" readonly 
                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-gray-400 cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-[10px] text-gray-600 uppercase font-black mb-3 ml-1">Verified Address</label>
                        <input type="text" name="address" value="<?= esc($user['address'] ?? '') ?>" readonly 
                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-gray-400 cursor-not-allowed">
                    </div>
                </div>
            </div>

            <!-- Akun Trading -->
            <div class="border-b border-white/5 pb-10 mb-10">
                <h3 class="text-xs font-black text-accent uppercase tracking-[0.2em] mb-8">02. Trading Account Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="md:col-span-2">
                        <label class="block text-[10px] text-gray-500 uppercase font-black mb-3 ml-1">Broker Name <span class="text-red-500">*</span></label>
                        <input type="text" name="broker_name" required placeholder="Masukan Nama Broker" 
                            class="w-full bg-black/40 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-white focus:border-accent">
                    </div>
                    <div>
                        <label class="block text-[10px] text-gray-500 uppercase font-black mb-3 ml-1">Account Number</label>
                        <input type="text" name="trading_account" placeholder="ID Akun" 
                            class="w-full bg-black/40 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-white focus:border-accent">
                    </div>
                    <div>
                        <label class="block text-[10px] text-gray-500 uppercase font-black mb-3 ml-1">Server Broker</label>
                        <input type="text" name="broker_server" placeholder="Masukan Server Broker" 
                            class="w-full bg-black/40 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-white focus:border-accent">
                    </div>
                </div>
            </div>

            <!-- Detail Laporan -->
            <div class="border-b border-white/5 pb-10 mb-10">
                <h3 class="text-xs font-black text-accent uppercase tracking-[0.2em] mb-8">03. Issue Description</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <label class="block text-[10px] text-gray-500 uppercase font-black mb-3 ml-1">Problem Category</label>
                        <select name="category_problem" id="mainCategory" onchange="updateSubCategories()" class="w-full bg-black/40 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-white">
                            <option value="">Select Category</option>
                            <option value="Masalah Transaksi">Masalah Transaksi</option>
                            <option value="Masalah Deposit & Withdraw">Masalah Deposit & Withdraw</option>
                            <option value="Masalah Platform / Sistem">Masalah Platform / Sistem</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] text-gray-500 uppercase font-black mb-3 ml-1 text-white">Incident Date</label>
                        <input type="date" name="incident_date" 
                            class="w-full bg-black/40 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-white [color-scheme:dark]">
                    </div>
                    <div>
                        <label class="block text-[10px] text-gray-500 uppercase font-black mb-3 ml-1">Estimated Loss (Rp)</label>
                        <input type="text" name="loss_amount" id="loss_amount" onkeyup="formatRupiah(this)" placeholder="0"
                            class="w-full bg-black/40 border border-white/10 rounded-2xl px-6 py-4 text-sm font-bold text-accent">
                    </div>
                </div>
                <label class="block text-[10px] text-gray-500 uppercase font-black mb-3 ml-1">Full Chronology (minimal 10 kata) <span class="text-red-500">*</span></label>
                <textarea name="chronology" required rows="5" placeholder="Explain the situation clearly (minimal 10 kata)..."
                    class="w-full bg-black/40 border border-white/10 rounded-2xl px-6 py-6 text-sm text-white resize-none"></textarea>
            </div>

            <!-- Evidence -->
            <div class="mb-12">
                <h3 class="text-xs font-black text-accent uppercase tracking-[0.2em] mb-8">04. Evidence Attachment</h3>
                <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-white/10 rounded-3xl bg-white/5 hover:border-accent cursor-pointer group">
                    <i class="fas fa-upload text-xl text-gray-600 mb-2 group-hover:text-accent transition-all"></i>
                    <span class="text-xs font-bold text-gray-500" id="file_name_display">Click to upload proof (Image/PDF)</span>
                    <input type="file" name="evidence" accept="image/*,application/pdf" class="hidden" onchange="handleFileChange(this)">
                </label>
            </div>

            <button type="submit" class="w-full py-5 bg-accent text-black font-black uppercase tracking-[0.3em] rounded-2xl hover:bg-white hover:shadow-[0_0_50px_rgba(51,232,24,0.3)] transition-all flex items-center justify-center gap-3 text-xs">
                Submit Partner Case
                <i class="fas fa-paper-plane"></i>
            </button>
        </form>
    </div>
</div>

<script>
    function formatRupiah(input) {
        let value = input.value.replace(/[^0-9]/g, '');
        if (value) { input.value = new Intl.NumberFormat('id-ID').format(value); }
    }
    function handleFileChange(input) {
        const display = document.getElementById('file_name_display');
        if (input.files && input.files[0]) { display.textContent = '✓ ' + input.files[0].name; display.classList.add('text-accent'); }
    }
</script>

<?= $this->endSection() ?>
