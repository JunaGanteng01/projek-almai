<?= $this->extend('keuangan/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-black text-white tracking-tight">Kas & Bank</h1>
        <p class="text-gray-400 text-sm mt-1 font-medium">Pantau saldo dan mutasi kas/bank perusahaan secara real-time</p>
    </div>
    <div class="flex gap-3">
        <button onclick="openTransferModal()" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-500 text-white font-bold rounded-xl hover:from-blue-500 hover:to-blue-400 transition-all flex items-center gap-2 shadow-lg shadow-blue-500/25">
            <i class="fas fa-exchange-alt"></i> Transfer Saldo
        </button>
        <a href="<?= base_url('keuangan/akun?kategori=kas+&bank') ?>" class="px-5 py-2.5 bg-white/5 text-white font-bold rounded-xl hover:bg-white/10 transition-all flex items-center gap-2 border border-white/10 backdrop-blur-sm">
            <i class="fas fa-cog"></i> Pengaturan
        </a>
    </div>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <div class="bg-gradient-to-br from-[#1a1a1a] to-[#0a0a0a] border border-white/10 p-6 rounded-2xl relative overflow-hidden group shadow-xl">
        <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-accent/20 rounded-full blur-3xl group-hover:bg-accent/30 transition-all duration-500"></div>
        <div class="relative z-10">
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-3">Total Saldo Keseluruhan</p>
            <h3 class="text-4xl font-black text-white tracking-tight">Rp <?= number_format($totalBalance, 0, ',', '.') ?></h3>
            <div class="mt-5 flex items-center gap-3">
                <span class="px-3 py-1 bg-accent/20 text-accent rounded-full text-[10px] font-bold tracking-widest flex items-center gap-1.5 border border-accent/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-accent animate-pulse"></span>
                    REAL-TIME
                </span>
                <span class="text-gray-500 text-xs font-medium italic">Update: <?= date('d/m H:i') ?></span>
            </div>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-[#1a1a1a] to-[#0a0a0a] border border-white/10 p-6 rounded-2xl relative overflow-hidden group shadow-xl">
        <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-blue-500/20 rounded-full blur-3xl group-hover:bg-blue-500/30 transition-all duration-500"></div>
        <div class="relative z-10">
            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-[0.2em] mb-3">Jumlah Akun Aktif</p>
            <h3 class="text-4xl font-black text-white tracking-tight"><?= count($accounts) ?> <span class="text-lg font-bold text-gray-500 ml-1">Akun</span></h3>
            <p class="mt-5 text-xs text-gray-500 font-medium">Semua akun terkoneksi ke Jurnal Umum</p>
        </div>
    </div>

    <div class="bg-gradient-to-br from-accent/10 to-[#0a0a0a] border border-accent/30 p-6 rounded-2xl relative overflow-hidden shadow-xl shadow-accent/5">
        <div class="absolute inset-0 bg-[url('<?= base_url('images/noise.png') ?>')] opacity-20 mix-blend-overlay pointer-events-none"></div>
        <div class="relative z-10 flex flex-col justify-between h-full">
            <p class="text-[11px] font-bold text-accent uppercase tracking-[0.2em] mb-3">Status Keuangan</p>
            <div class="flex items-center gap-4 mt-auto">
                <div class="w-14 h-14 bg-accent/20 rounded-2xl flex items-center justify-center border border-accent/20 shadow-inner">
                    <i class="fas fa-shield-alt text-accent text-2xl"></i>
                </div>
                <div>
                    <h4 class="text-lg font-black text-white">Sehat & Terkendali</h4>
                    <p class="text-xs text-gray-400 mt-1">Arus kas lancar sesuai budget</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="bg-accent/10 border border-accent/20 text-accent p-4 rounded-xl mb-6 flex items-center gap-3">
        <i class="fas fa-check-circle"></i>
        <span><?= session()->getFlashdata('success') ?></span>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-xl mb-6 flex items-center gap-3">
        <i class="fas fa-exclamation-circle"></i>
        <span><?= session()->getFlashdata('error') ?></span>
    </div>
<?php endif; ?>

<!-- Accounts List Table -->
<div class="bg-gradient-to-b from-[#1a1a1a] to-[#0a0a0a] border border-white/10 rounded-2xl p-6 shadow-xl relative overflow-hidden">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold text-white">Daftar Akun Kas & Bank</h2>
        <div class="flex gap-2">
            <button onclick="confirmResetData()" class="px-4 py-2 bg-red-500/10 text-red-400 font-bold rounded-xl hover:bg-red-500 hover:text-white transition-all flex items-center gap-2 border border-red-500/20 text-xs">
                <i class="fas fa-trash-alt"></i> Hapus Semua Data
            </button>
            <button onclick="openSetSaldoModal()" class="px-4 py-2 bg-purple-500/10 text-purple-400 font-bold rounded-xl hover:bg-purple-500 hover:text-white transition-all flex items-center gap-2 border border-purple-500/20 text-xs">
                <i class="fas fa-wallet"></i> Set Saldo Awal
            </button>
            <button onclick="openImportModal()" class="px-4 py-2 bg-green-500/10 text-green-400 font-bold rounded-xl hover:bg-green-500 hover:text-white transition-all flex items-center gap-2 border border-green-500/20 text-xs">
                <i class="fas fa-file-import"></i> Import Saldo Awal
            </button>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-white/10 text-[10px] uppercase tracking-widest text-gray-500">
                    <th class="py-3 px-4 font-bold">Kode</th>
                    <th class="py-3 px-4 font-bold">Nama Akun</th>
                    <th class="py-3 px-4 font-bold">Sub Akun</th>
                    <th class="py-3 px-4 font-bold text-right">Saldo Saat Ini</th>
                    <th class="py-3 px-4 font-bold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($accounts as $account): ?>
                <tr class="border-b border-white/5 hover:bg-white/5 transition-colors group">
                    <td class="py-4 px-4">
                        <span class="text-xs font-mono text-white/70 bg-black/40 px-2 py-1 rounded border border-white/10 shadow-sm"><?= esc($account['kode_akun']) ?></span>
                    </td>
                    <td class="py-4 px-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-white/5 rounded-lg flex items-center justify-center border border-white/10 text-gray-400 group-hover:text-accent transition-colors">
                                <i class="<?= strpos(strtolower($account['nama_akun']), 'bank') !== false ? 'fas fa-university' : 'fas fa-wallet' ?> text-sm"></i>
                            </div>
                            <span class="font-bold text-white"><?= esc($account['nama_akun']) ?></span>
                        </div>
                    </td>
                    <td class="py-4 px-4 text-sm text-gray-400">
                        <?= esc($account['nama_sub_akun'] ?? 'Akun Utama') ?>
                    </td>
                    <td class="py-4 px-4 text-right">
                        <span class="font-black <?= $account['balance'] > 0 ? 'text-green-500' : ($account['balance'] == 0 ? 'text-yellow-500' : 'text-red-500') ?>">
                            Rp <?= number_format($account['balance'], 0, ',', '.') ?>
                        </span>
                    </td>
                    <td class="py-4 px-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="<?= base_url('keuangan/kas-bank/mutasi/' . $account['id']) ?>" class="px-3 py-1.5 bg-white/5 hover:bg-white/10 text-white text-xs font-bold rounded-lg transition border border-white/10 hover:border-white/20" title="Mutasi">
                                <i class="fas fa-history"></i> Mutasi
                            </a>
                            <button onclick="openTransferModal('<?= $account['id'] ?>', '<?= esc($account['nama_akun']) ?>')" class="px-3 py-1.5 bg-blue-500/10 hover:bg-blue-500 text-blue-400 hover:text-white text-xs font-bold rounded-lg transition border border-blue-500/20" title="Transfer">
                                <i class="fas fa-paper-plane"></i> Transfer
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeImportModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md">
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-white">Import Saldo Awal (Excel)</h3>
                <button onclick="closeImportModal()" class="text-gray-400 hover:text-white transition"><i class="fas fa-times"></i></button>
            </div>
            <form action="<?= base_url('keuangan/kas-bank/import') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">File Excel (.xlsx)</label>
                    <input type="file" name="file_excel" accept=".xlsx,.xls,.csv" required class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent">
                </div>
                <div class="mb-6">
                    <a href="<?= base_url('keuangan/kas-bank/download-template') ?>" class="text-accent text-sm hover:underline"><i class="fas fa-download mr-1"></i> Download Template</a>
                </div>
                <div class="flex gap-3">
                    <button type="button" onclick="closeImportModal()" class="flex-1 py-3 bg-white/5 text-white font-bold rounded-xl hover:bg-white/10 transition border border-white/10">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-accent to-emerald-500 text-black font-black rounded-xl shadow-lg hover:shadow-accent/20 transition-all">Import Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Set Saldo Awal Modal -->
<div id="setSaldoModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeSetSaldoModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md">
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-white">Set Saldo Awal</h3>
                <button onclick="closeSetSaldoModal()" class="text-gray-400 hover:text-white transition"><i class="fas fa-times"></i></button>
            </div>
            <form action="<?= base_url('keuangan/kas-bank/set-saldo-awal') ?>" method="POST">
                <?= csrf_field() ?>
                
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Tanggal Saldo</label>
                    <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-purple-500">
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Akun Kas/Bank (Debit)</label>
                    <select name="akun_kas_id" required class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-purple-500">
                        <option value="">-- Pilih Kas/Bank --</option>
                        <?php foreach ($accounts as $a): ?>
                            <option value="<?= $a['id'] ?>"><?= esc($a['kode_akun'] . ' - ' . $a['nama_akun']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Sumber Dana / Ekuitas (Kredit)</label>
                    <select name="akun_ekuitas_id" required class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-purple-500">
                        <option value="">-- Pilih Akun Modal/Ekuitas --</option>
                        <?php if (isset($ekuitasAccounts)): foreach ($ekuitasAccounts as $e): ?>
                            <option value="<?= $e['id'] ?>"><?= esc($e['kode_akun'] . ' - ' . $e['nama_akun']) ?></option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Nominal Saldo Akhir</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold">Rp</span>
                        <input type="text" name="amount" required class="w-full bg-black/50 border border-white/10 rounded-xl pl-12 pr-4 py-3 text-white font-bold focus:outline-none focus:border-purple-500" placeholder="0">
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeSetSaldoModal()" class="flex-1 py-3 bg-white/5 text-white font-bold rounded-xl hover:bg-white/10 transition border border-white/10">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-purple-500 to-indigo-500 text-white font-black rounded-xl shadow-lg shadow-purple-500/20 hover:shadow-purple-500/40 transition-all">Simpan Saldo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmResetData() {
        Swal.fire({
            title: 'Apakah Anda Yakin?',
            html: "Anda akan <b>menghapus SEMUA riwayat transaksi</b> yang melibatkan Kas & Bank. <br><br>Data yang dihapus <b>tidak bisa dikembalikan</b>. Lanjutkan?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#374151',
            confirmButtonText: '<i class="fas fa-trash"></i> Ya, Hapus Semua!',
            cancelButtonText: 'Batal',
            background: '#111',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= base_url('keuangan/kas-bank/resetData') ?>';
            }
        });
    }

    function openImportModal() {
        document.getElementById('importModal').classList.remove('hidden');
    }
    function closeImportModal() {
        document.getElementById('importModal').classList.add('hidden');
    }

    // SET SALDO AWAL MODAL SCRIPT
    function openSetSaldoModal() {
        document.getElementById('setSaldoModal').classList.remove('hidden');
    }

    function closeSetSaldoModal() {
        document.getElementById('setSaldoModal').classList.add('hidden');
    }

    // Format angka saat mengetik
    document.querySelectorAll('input[name="amount"]').forEach(input => {
        input.addEventListener('keyup', function(e) {
            let value = this.value.replace(/[^,\d]/g, '').toString();
            let split = value.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);
            
            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            
            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            this.value = rupiah;
        });
    });
</script>

<!-- Modal Transfer -->
<div id="transferModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 hidden backdrop-blur-sm">
    <div class="bg-[#111] border border-white/10 rounded-2xl w-full max-w-md p-6 shadow-2xl">
        <div class="flex items-center justify-between mb-6 pb-6 border-b border-white/10">
            <h3 class="text-xl font-bold text-white">Transfer Antar Kas/Bank</h3>
            <button onclick="closeTransferModal()" class="w-8 h-8 rounded-full bg-white/5 hover:bg-white/10 flex items-center justify-center transition">
                <i class="fas fa-times text-gray-400"></i>
            </button>
        </div>

        <form action="<?= base_url('keuangan/kas-bank/transfer') ?>" method="post" class="space-y-4">
            <?= csrf_field() ?>
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Akun Asal (Sumber Dana)</label>
                <select name="from_account_id" id="fromAccountId" required class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none transition text-white text-sm">
                    <option value="">Pilih Akun Asal</option>
                    <?php foreach ($accounts as $acc): ?>
                        <option value="<?= $acc['id'] ?>"><?= esc($acc['nama_akun']) ?> (Rp <?= number_format($acc['balance'], 0, ',', '.') ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="flex justify-center py-2">
                <div class="w-8 h-8 bg-accent/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-arrow-down text-accent"></i>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Akun Tujuan (Penerima)</label>
                <select name="to_account_id" id="toAccountId" required class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none transition text-white text-sm">
                    <option value="">Pilih Akun Tujuan</option>
                    <?php foreach ($accounts as $acc): ?>
                        <option value="<?= $acc['id'] ?>"><?= esc($acc['nama_akun']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Jumlah Transfer</label>
                    <input type="number" name="amount" required min="1" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none transition text-white text-sm" placeholder="0">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Tanggal</label>
                    <input type="date" name="tanggal" value="<?= date('Y-m-d') ?>" required class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none transition text-white text-sm">
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Catatan</label>
                <textarea name="catatan" rows="2" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none transition text-white text-sm" placeholder="Contoh: Pemindahan saldo untuk operasional"></textarea>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="button" onclick="closeTransferModal()" class="flex-1 py-3 bg-white/5 hover:bg-white/10 text-white font-bold rounded-xl transition border border-white/10">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-accent hover:bg-white text-black font-bold rounded-xl transition shadow-lg shadow-accent/20">Proses Transfer</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openTransferModal(fromId = '', fromName = '') {
        const modal = document.getElementById('transferModal');
        if (fromId) {
            document.getElementById('fromAccountId').value = fromId;
        }
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeTransferModal() {
        const modal = document.getElementById('transferModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    window.onclick = function(event) {
        const modal = document.getElementById('transferModal');
        if (event.target == modal) {
            closeTransferModal();
        }
    }
</script>

<?= $this->endSection() ?>
