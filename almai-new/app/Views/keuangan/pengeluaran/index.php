<?= $this->extend('keuangan/layouts/main') ?>

<?= $this->section('content') ?>
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white mb-1">Pengeluaran</h1>
        <p class="text-gray-400">Manajemen Pengeluaran & Stok</p>
    </div>
    <button onclick="openAddModal()" class="px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition shadow-[0_0_20px_rgba(51,232,24,0.3)]">
        <i class="fas fa-plus mr-2"></i> Tambah Pengeluaran
    </button>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-shopping-bag text-blue-400 text-xl"></i>
            </div>
        </div>
        <h3 class="text-gray-400 text-sm mb-1">Total Pengeluaran</h3>
        <p class="text-2xl font-bold text-white">Rp <?= number_format($totalPengeluaran, 0, ',', '.') ?></p>
    </div>

    <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-yellow-500/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-clock text-yellow-400 text-xl"></i>
            </div>
        </div>
        <h3 class="text-gray-400 text-sm mb-1">Tagihan Pending</h3>
        <p class="text-2xl font-bold text-white">Rp <?php echo number_format($totalPending, 0, ',', '.') ?></p>
        <p class="text-xs text-yellow-400 mt-1"><?= $countPending ?> transaksi</p>
    </div>

    <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-accent/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-check-circle text-accent text-xl"></i>
            </div>
        </div>
        <h3 class="text-gray-400 text-sm mb-1">Lunas</h3>
        <p class="text-2xl font-bold text-white">Rp <?= number_format($totalLunas, 0, ',', '.') ?></p>
    </div>

    <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-purple-400 text-xl"></i>
            </div>
        </div>
        <h3 class="text-gray-400 text-sm mb-1">Total Supplier</h3>
        <p class="text-2xl font-bold text-white"><?= count($suppliers) ?></p>
    </div>
</div>

<!-- Filters -->
<div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-6">
    <form method="get" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-sm text-gray-400 mb-2">Pencarian</label>
            <input type="text" name="search" value="<?= esc($currentSearch ?? '') ?>" placeholder="No Faktur, Supplier..." class="w-full bg-black border border-white/20 rounded-xl px-4 py-2 text-white focus:border-accent focus:outline-none">
        </div>
        <div>
            <label class="block text-sm text-gray-400 mb-2">Status</label>
            <select name="status" class="w-full bg-black border border-white/20 rounded-xl px-4 py-2 text-white focus:border-accent focus:outline-none">
                <option value="all" <?= ($currentStatus ?? '') === 'all' || empty($currentStatus) ? 'selected' : '' ?>>Semua Status</option>
                <option value="pending" <?= ($currentStatus ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="lunas" <?= ($currentStatus ?? '') === 'lunas' ? 'selected' : '' ?>>Lunas</option>
                <option value="dibatalkan" <?= ($currentStatus ?? '') === 'dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
            </select>
        </div>
        <div>
            <label class="block text-sm text-gray-400 mb-2">Dari Tanggal</label>
            <input type="date" name="date_from" value="<?= esc($dateFrom ?? '') ?>" class="w-full bg-black border border-white/20 rounded-xl px-4 py-2 text-white focus:border-accent focus:outline-none">
        </div>
        <div>
            <label class="block text-sm text-gray-400 mb-2">Sampai Tanggal</label>
            <input type="date" name="date_to" value="<?= esc($dateTo ?? '') ?>" class="w-full bg-black border border-white/20 rounded-xl px-4 py-2 text-white focus:border-accent focus:outline-none">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 py-2 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                <i class="fas fa-search mr-2"></i> Filter
            </button>
            <a href="<?= base_url('keuangan/pengeluaran') ?>" class="px-4 py-2 bg-white/10 text-white rounded-xl hover:bg-white/20 transition">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </form>
</div>

<!-- Table -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-black/50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase">No Faktur</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase">Tanggal</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase">Supplier</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase">Subtotal</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase">PPN</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase">Total</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase">Status</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($pengeluaranList)): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-3 opacity-50"></i>
                            <p>Belum ada data pengeluaran</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($pengeluaranList as $item): ?>
                        <tr class="hover:bg-white/5 transition">
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm text-accent"><?= esc($item['no_faktur']) ?></span>
                            </td>
                            <td class="px-6 py-4 text-gray-300"><?= date('d M Y', strtotime($item['tanggal'])) ?></td>
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-white font-medium"><?= esc($item['supplier_name'] ?? '-') ?></p>
                                    <?php if (!empty($item['keterangan'])): ?>
                                        <p class="text-xs text-gray-400"><?= esc($item['keterangan']) ?></p>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right text-white font-mono">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                            <td class="px-6 py-4 text-right text-gray-400 font-mono">Rp <?= number_format($item['ppn'] ?? 0, 0, ',', '.') ?></td>
                            <td class="px-6 py-4 text-right text-accent font-bold font-mono">Rp <?= number_format($item['total'], 0, ',', '.') ?></td>
                            <td class="px-6 py-4 text-center">
                                <?php
                                $statusColors = [
                                    'pending' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                                    'lunas' => 'bg-green-500/20 text-green-400 border-green-500/30',
                                    'dibatalkan' => 'bg-red-500/20 text-red-400 border-red-500/30'
                                ];
                                $color = $statusColors[$item['status']] ?? 'bg-gray-500/20 text-gray-400 border-gray-500/30';
                                ?>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border <?= $color ?>">
                                    <?= ucfirst($item['status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="viewDetail(<?= $item['id'] ?>)" class="text-blue-400 hover:text-blue-300 mx-1" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="editPengeluaran(<?= $item['id'] ?>)" class="text-accent hover:text-white mx-1" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deletePengeluaran(<?= $item['id'] ?>)" class="text-red-400 hover:text-red-300 mx-1" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if (!empty($pager)): ?>
        <div class="px-6 py-4 border-t border-white/10">
            <?= $pager->links() ?>
        </div>
    <?php endif; ?>
</div>

<!-- Add/Edit Modal -->
<div id="pengeluaranModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-white/10 flex items-center justify-between sticky top-0 bg-[#111]">
            <h2 class="text-xl font-bold text-white">Tambah Pengeluaran</h2>
            <button onclick="closeModal()" class="text-gray-400 hover:text-white">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="pengeluaranForm" method="post" action="<?= base_url('keuangan/pengeluaran/save') ?>" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="pengeluaran_id">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">No Faktur *</label>
                    <input type="text" name="no_faktur" id="no_faktur" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-2 text-white focus:border-accent focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Tanggal *</label>
                    <input type="date" name="tanggal" id="tanggal" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-2 text-white focus:border-accent focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-2">Supplier *</label>
                <input type="text" name="supplier_name" id="supplier_name" required list="supplier_list" class="w-full bg-black border border-white/20 rounded-xl px-4 py-2 text-white focus:border-accent focus:outline-none" placeholder="Ketik nama supplier...">
                <datalist id="supplier_list">
                    <?php foreach ($suppliers as $supplier): ?>
                        <option value="<?= esc($supplier['name']) ?>"></option>
                    <?php endforeach; ?>
                </datalist>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Subtotal *</label>
                    <input type="number" name="subtotal" id="subtotal" required min="0" step="0.01" class="w-full bg-black border border-white/20 rounded-xl px-4 py-2 text-white focus:border-accent focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">PPN (%)</label>
                    <input type="number" name="ppn" id="ppn" min="0" step="0.01" value="0" class="w-full bg-black border border-white/20 rounded-xl px-4 py-2 text-white focus:border-accent focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-2">Total *</label>
                <input type="number" name="total" id="total" required min="0" step="0.01" readonly class="w-full bg-black border border-white/20 rounded-xl px-4 py-2 text-white focus:border-accent focus:outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Status *</label>
                    <select name="status" id="status" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-2 text-white focus:border-accent focus:outline-none">
                        <option value="pending">Pending</option>
                        <option value="lunas">Lunas</option>
                        <option value="dibatalkan">Dibatalkan</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Jatuh Tempo</label>
                    <input type="date" name="jatuh_tempo" id="jatuh_tempo" class="w-full bg-black border border-white/20 rounded-xl px-4 py-2 text-white focus:border-accent focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-2">Keterangan</label>
                <textarea name="keterangan" id="keterangan" rows="3" class="w-full bg-black border border-white/20 rounded-xl px-4 py-2 text-white focus:border-accent focus:outline-none"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-white/10">
                <button type="button" onclick="closeModal()" class="px-6 py-2 bg-white/10 text-white rounded-xl hover:bg-white/20 transition">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                    <i class="fas fa-save mr-2"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddModal() {
        document.getElementById('pengeluaran_id').value = '';
        document.getElementById('pengeluaranForm').reset();
        document.getElementById('tanggal').valueAsDate = new Date();
        generateNoFaktur();
        document.getElementById('pengeluaranModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('pengeluaranModal').classList.add('hidden');
    }

    // Auto calculate total
    document.getElementById('subtotal').addEventListener('input', calculateTotal);
    document.getElementById('ppn').addEventListener('input', calculateTotal);
    
    // Auto generate invoice number on date change
    document.getElementById('tanggal').addEventListener('change', generateNoFaktur);

    function calculateTotal() {
        const subtotal = parseFloat(document.getElementById('subtotal').value) || 0;
        const ppnPercent = parseFloat(document.getElementById('ppn').value) || 0;
        const ppnAmount = (subtotal * ppnPercent) / 100;
        const total = subtotal + ppnAmount;
        document.getElementById('total').value = total.toFixed(2);
    }

    function generateNoFaktur() {
        const id = document.getElementById('pengeluaran_id').value;
        if (id) {
            return; // Don't overwrite when editing
        }
        
        const tanggalVal = document.getElementById('tanggal').value;
        if (!tanggalVal) return;
        
        fetch('<?= base_url('keuangan/pengeluaran/generate-faktur') ?>?date=' + tanggalVal)
            .then(response => response.json())
            .then(res => {
                if (res.success) {
                    document.getElementById('no_faktur').value = res.no_faktur;
                }
            })
            .catch(error => console.error('Error generating no_faktur:', error));
    }

    function viewDetail(id) {
        window.location.href = '<?= base_url('keuangan/pengeluaran/detail/') ?>' + id;
    }

    function editPengeluaran(id) {
        fetch('<?= base_url('keuangan/pengeluaran/get/') ?>' + id)
            .then(response => response.json())
            .then(res => {
                if(res.success) {
                    const data = res.data;
                    document.getElementById('pengeluaran_id').value = data.id;
                    document.getElementById('no_faktur').value = data.no_faktur;
                    document.getElementById('tanggal').value = data.tanggal;
                    document.getElementById('supplier_name').value = data.supplier_name;
                    document.getElementById('subtotal').value = data.subtotal;
                    document.getElementById('ppn').value = data.ppn;
                    document.getElementById('total').value = data.total;
                    document.getElementById('status').value = data.status;
                    document.getElementById('jatuh_tempo').value = data.jatuh_tempo;
                    document.getElementById('keterangan').value = data.keterangan;
                    
                    document.getElementById('pengeluaranModal').classList.remove('hidden');
                } else {
                    alert('Gagal mengambil data: ' + res.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengambil data.');
            });
    }

    function deletePengeluaran(id) {
        if (confirm('Yakin ingin menghapus pengeluaran ini?')) {
            window.location.href = '<?= base_url('keuangan/pengeluaran/delete/') ?>' + id;
        }
    }
</script>

<?= $this->endSection() ?>