<?= $this->extend('keuangan/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div class="flex items-center gap-4">
        <a href="<?= base_url('keuangan/kas-bank') ?>" class="w-10 h-10 bg-white/5 rounded-xl flex items-center justify-center hover:bg-white/10 transition border border-white/10">
            <i class="fas fa-arrow-left text-gray-400"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-white">Mutasi: <?= esc($account['nama_akun']) ?></h1>
            <p class="text-gray-500 text-xs mt-1">Riwayat transaksi untuk kode <?= esc($account['kode_akun']) ?></p>
        </div>
    </div>
</div>

<!-- Account Summary -->
<div class="bg-[#111] border border-white/10 p-6 rounded-2xl mb-8 flex flex-col md:flex-row justify-between items-center gap-6">
    <div class="flex items-center gap-4 w-full md:w-auto">
        <div class="w-16 h-16 bg-accent/10 rounded-2xl flex items-center justify-center border border-accent/20">
            <i class="fas fa-university text-accent text-2xl"></i>
        </div>
        <div>
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Nama Akun</p>
            <h3 class="text-xl font-black text-white"><?= esc($account['nama_akun']) ?></h3>
            <p class="text-xs text-gray-500"><?= esc($account['kategori']) ?></p>
        </div>
    </div>

    <div class="h-px w-full md:h-12 md:w-px bg-white/10"></div>

    <div class="text-center md:text-right w-full md:w-auto">
        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Saldo Akhir</p>
        <?php 
            $totalDebit = array_sum(array_column($mutasi, 'debit'));
            $totalKredit = array_sum(array_column($mutasi, 'kredit'));
            $finalBalance = $totalDebit - $totalKredit;
        ?>
        <h3 class="text-3xl font-black <?= $finalBalance > 0 ? 'text-green-500' : ($finalBalance == 0 ? 'text-yellow-500' : 'text-red-500') ?>">
            Rp <?= number_format($finalBalance, 0, ',', '.') ?>
        </h3>
    </div>
</div>

<!-- Mutasi Table -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-black/50 border-b border-white/10">
                <tr>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tanggal</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Keterangan</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">No. Ref</th>
                    <th class="px-6 py-4 text-right text-[10px] font-bold text-gray-400 uppercase tracking-widest">Masuk (Debit)</th>
                    <th class="px-6 py-4 text-right text-[10px] font-bold text-gray-400 uppercase tracking-widest">Keluar (Kredit)</th>
                    <th class="px-6 py-4 text-right text-[10px] font-bold text-gray-400 uppercase tracking-widest">Saldo</th>
                    <th class="px-6 py-4 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($mutasi)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-folder-open text-4xl mb-3 opacity-20"></i>
                            <p>Belum ada riwayat transaksi pada akun ini.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php 
                    $currentRunningBalance = $finalBalance;
                    // Note: Since we ordered by DESC, we need to calculate running balance differently or reverse the data for calculation
                    // But for display, let's just show debit/credit clearly.
                    ?>
                    <?php foreach ($mutasi as $item): ?>
                        <tr class="hover:bg-white/5 transition">
                            <td class="px-6 py-4 text-sm text-gray-300">
                                <?= date('d M Y', strtotime($item['tanggal'])) ?>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-white font-medium text-sm"><?= esc($item['deskripsi']) ?></p>
                            </td>
                            <td class="px-6 py-4 text-xs font-mono text-gray-500">
                                <?= esc($item['no_reff']) ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <?php if ($item['debit'] > 0): ?>
                                    <span class="text-accent font-bold">Rp <?= number_format($item['debit'], 0, ',', '.') ?></span>
                                <?php else: ?>
                                    <span class="text-gray-700">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <?php if ($item['kredit'] > 0): ?>
                                    <span class="text-red-400 font-bold">Rp <?= number_format($item['kredit'], 0, ',', '.') ?></span>
                                <?php else: ?>
                                    <span class="text-gray-700">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-right text-gray-400 text-sm font-mono">
                                <!-- Calculating running balance in DESC order is tricky without knowing start balance -->
                                <span class="opacity-50 italic text-[10px]">Detail Jurnal</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <?php if (strpos($item['no_reff'], 'TRF-') === 0): ?>
                                    <button onclick="editTransfer('<?= esc($item['no_reff']) ?>')" class="w-8 h-8 rounded-lg bg-blue-500/10 hover:bg-blue-500 text-blue-500 hover:text-white transition inline-flex items-center justify-center" title="Edit Transfer">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <button onclick="detailTransfer('<?= esc($item['no_reff']) ?>')" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/20 text-gray-400 hover:text-white transition inline-flex items-center justify-center" title="Detail Transfer">
                                        <i class="fas fa-info-circle text-xs"></i>
                                    </button>
                                    <?php endif; ?>
                                    
                                    <a href="<?= base_url('keuangan/kas-bank/mutasi/delete/' . esc($item['no_reff'])) ?>" onclick="return confirm('Yakin ingin menghapus transaksi ini? Semua jurnal dengan No Ref <?= esc($item['no_reff']) ?> akan dihapus.')" class="w-8 h-8 rounded-lg bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white transition inline-flex items-center justify-center" title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Edit Transfer -->
<div id="editTransferModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeEditTransferModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-md">
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-white">Edit Transfer Saldo</h3>
                <button onclick="closeEditTransferModal()" class="text-gray-400 hover:text-white transition"><i class="fas fa-times"></i></button>
            </div>
            <form action="<?= base_url('keuangan/kas-bank/transfer/update') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="no_reff" id="edit_no_reff">
                
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Tanggal</label>
                    <input type="date" name="tanggal" id="edit_tanggal" required class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent">
                </div>
                
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Dari Akun (Asal)</label>
                    <select name="from_account_id" id="edit_from_account" required class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent">
                        <!-- Options will be populated by JS if we have accounts list, or just use hardcoded/ajax. For simplicity, we just keep the input as a text/readonly if we don't fetch all accounts, but users want to change it. So let's fetch accounts or just make them readonly for now if we can't easily populate. Actually, we should fetch accounts from the controller or just render them here. -->
                        <option value="" id="edit_from_opt" selected></option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Ke Akun (Tujuan)</label>
                    <select name="to_account_id" id="edit_to_account" required class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent">
                        <option value="" id="edit_to_opt" selected></option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Nominal</label>
                    <input type="number" name="amount" id="edit_amount" required class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent">
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Catatan</label>
                    <textarea name="catatan" id="edit_catatan" rows="2" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent"></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeEditTransferModal()" class="flex-1 py-3 bg-white/5 text-white font-bold rounded-xl hover:bg-white/10 transition border border-white/10">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-blue-600 to-blue-500 text-white font-black rounded-xl shadow-lg hover:shadow-blue-500/20 transition-all">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail Transfer -->
<div id="detailTransferModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeDetailTransferModal()"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-sm">
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-accent to-blue-500"></div>
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-white">Detail Transfer</h3>
                <button onclick="closeDetailTransferModal()" class="text-gray-400 hover:text-white transition"><i class="fas fa-times"></i></button>
            </div>
            
            <div class="space-y-4">
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">No. Reff</p>
                    <p class="text-white font-mono text-sm" id="det_no_reff"></p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Tanggal</p>
                    <p class="text-white" id="det_tanggal"></p>
                </div>
                <div class="bg-black/40 p-4 rounded-xl border border-white/5 relative">
                    <div class="flex flex-col gap-3">
                        <div>
                            <p class="text-[10px] font-bold text-red-400 uppercase tracking-wider mb-1">Akun Asal (Kredit)</p>
                            <p class="text-white font-bold" id="det_from"></p>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-white/5 border border-white/10 flex items-center justify-center absolute right-4 top-1/2 -translate-y-1/2">
                            <i class="fas fa-arrow-down text-gray-500"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-accent uppercase tracking-wider mb-1">Akun Tujuan (Debit)</p>
                            <p class="text-white font-bold" id="det_to"></p>
                        </div>
                    </div>
                </div>
                <div class="bg-blue-500/10 p-4 rounded-xl border border-blue-500/20 text-center">
                    <p class="text-[10px] font-bold text-blue-400 uppercase tracking-wider mb-1">Nominal Transfer</p>
                    <p class="text-2xl font-black text-white" id="det_amount"></p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Deskripsi</p>
                    <p class="text-gray-300 text-sm" id="det_desc"></p>
                </div>
            </div>
            
            <div class="mt-6">
                <button onclick="closeDetailTransferModal()" class="w-full py-3 bg-white/5 text-white font-bold rounded-xl hover:bg-white/10 transition border border-white/10">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
async function fetchTransferDetail(no_reff) {
    try {
        const response = await fetch('<?= base_url('keuangan/kas-bank/transfer/detail/') ?>' + no_reff);
        const res = await response.json();
        if (res.status === 'success') {
            return res.data;
        } else {
            alert(res.message);
            return null;
        }
    } catch (e) {
        alert('Terjadi kesalahan.');
        return null;
    }
}

async function editTransfer(no_reff) {
    const data = await fetchTransferDetail(no_reff);
    if (data) {
        document.getElementById('edit_no_reff').value = data.no_reff;
        document.getElementById('edit_tanggal').value = data.tanggal;
        document.getElementById('edit_amount').value = data.amount;
        document.getElementById('edit_catatan').value = data.deskripsi;
        
        document.getElementById('edit_from_opt').value = data.from_account_id;
        document.getElementById('edit_from_opt').textContent = data.from_account_name;
        
        document.getElementById('edit_to_opt').value = data.to_account_id;
        document.getElementById('edit_to_opt').textContent = data.to_account_name;
        
        document.getElementById('editTransferModal').classList.remove('hidden');
    }
}

function closeEditTransferModal() {
    document.getElementById('editTransferModal').classList.add('hidden');
}

async function detailTransfer(no_reff) {
    const data = await fetchTransferDetail(no_reff);
    if (data) {
        document.getElementById('det_no_reff').textContent = data.no_reff;
        document.getElementById('det_tanggal').textContent = data.tanggal;
        document.getElementById('det_from').textContent = data.from_account_name;
        document.getElementById('det_to').textContent = data.to_account_name;
        document.getElementById('det_amount').textContent = 'Rp ' + parseInt(data.amount).toLocaleString('id-ID');
        document.getElementById('det_desc').textContent = data.deskripsi;
        
        document.getElementById('detailTransferModal').classList.remove('hidden');
    }
}

function closeDetailTransferModal() {
    document.getElementById('detailTransferModal').classList.add('hidden');
}
</script>

<?= $this->endSection() ?>
