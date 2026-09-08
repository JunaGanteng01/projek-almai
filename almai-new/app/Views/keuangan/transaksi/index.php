<?php $this->setVar('pageTitle', 'Transaksi Keuangan'); ?>
<?= $this->extend('keuangan/layouts/main') ?>

<?= $this->section('content') ?>
<?php
$currentPage = $pager->getCurrentPage();
$perPage = 20;
?>

<!-- Header & Add Button Section -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Daftar Transaksi & Penjualan</h1>
        <p class="text-sm text-gray-400">Manajemen pemasukan, penjualan layanan, dan tagihan invoice customer.</p>
    </div>
    <div>
        <button onclick="openAddInvoiceModal()" class="w-full md:w-auto px-6 py-2.5 bg-accent hover:bg-white text-black font-bold rounded-xl transition flex items-center justify-center gap-2 shadow-lg shadow-accent/20">
            <i class="fas fa-plus"></i> Tambah Invoice Customer
        </button>
    </div>
</div>

<!-- Alert Messages -->
<?php if (session()->getFlashdata('success')) : ?>
    <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 text-green-400 rounded-xl flex items-center gap-2">
        <i class="fas fa-check-circle"></i>
        <span><?= session()->getFlashdata('success') ?></span>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl flex items-center gap-2">
        <i class="fas fa-exclamation-circle"></i>
        <span><?= session()->getFlashdata('error') ?></span>
    </div>
<?php endif; ?>

<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-5 gap-3 md:gap-4 mb-6">
    <!-- Total Pendapatan -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-4 col-span-2 lg:col-span-1">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-wallet text-purple-500"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xl md:text-2xl font-bold text-purple-200 truncate leading-tight">Rp <?= number_format($totalRevenue, 0, ',', '.') ?></p>
                <p class="text-[10px] md:text-xs text-gray-400 truncate uppercase tracking-widest font-bold">Total Pendapatan</p>
            </div>
        </div>
    </div>

    <!-- Pending -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-3 md:p-4">
        <div class="flex items-center gap-2 md:gap-3">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-yellow-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-clock text-yellow-500 text-sm md:text-base"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-lg md:text-2xl font-bold truncate leading-tight"><?= $totalPending ?></p>
                <p class="text-[9px] md:text-xs text-gray-400 truncate uppercase tracking-tighter">Pending</p>
            </div>
        </div>
    </div>

    <!-- Paid -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-3 md:p-4">
        <div class="flex items-center gap-2 md:gap-3">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-blue-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-credit-card text-blue-500 text-sm md:text-base"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-lg md:text-2xl font-bold text-blue-500 truncate leading-tight"><?= $totalPaid ?></p>
                <p class="text-[9px] md:text-xs text-gray-400 truncate uppercase tracking-tighter">Paid</p>
            </div>
        </div>
    </div>

    <!-- Confirmed -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-3 md:p-4">
        <div class="flex items-center gap-2 md:gap-3">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-accent/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-check-circle text-accent text-sm md:text-base"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-lg md:text-2xl font-bold text-accent truncate leading-tight"><?= $totalConfirmed ?></p>
                <p class="text-[9px] md:text-xs text-gray-400 truncate uppercase tracking-tighter">Confirmed</p>
            </div>
        </div>
    </div>

    <!-- Refund -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-3 md:p-4">
        <div class="flex items-center gap-2 md:gap-3">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-red-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-undo text-red-500 text-sm md:text-base"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-lg md:text-2xl font-bold text-red-500 truncate leading-tight"><?= $totalRefunded ?? 0 ?></p>
                <p class="text-[9px] md:text-xs text-gray-400 truncate uppercase tracking-tighter">Refund</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-[#111] border border-white/10 rounded-2xl p-4 mb-6 shadow-xl">
    <form action="<?= base_url('keuangan/transaksi') ?>" method="get" class="flex flex-col gap-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <select name="status" onchange="this.form.submit()" class="w-full px-4 py-2.5 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-gray-300">
                <option value="all" <?= $currentStatus === 'all' || !$currentStatus ? 'selected' : '' ?>>Semua Status</option>
                <option value="pending" <?= $currentStatus === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="paid" <?= $currentStatus === 'paid' ? 'selected' : '' ?>>Paid</option>
                <option value="confirmed" <?= $currentStatus === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                <option value="cancelled" <?= $currentStatus === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                <option value="refunded" <?= $currentStatus === 'refunded' ? 'selected' : '' ?>>Refunded</option>
            </select>
            <select name="product_type" onchange="this.form.submit()" class="w-full px-4 py-2.5 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-gray-300">
                <option value="all" <?= $currentProductType === 'all' || !$currentProductType ? 'selected' : '' ?>>Semua Produk</option>
                <?php foreach ($productTypes as $pt): ?>
                    <option value="<?= esc($pt['product_type']) ?>" <?= $currentProductType === $pt['product_type'] ? 'selected' : '' ?>><?= ucfirst(esc($pt['product_type'])) ?></option>
                <?php endforeach; ?>
            </select>
            <input type="date" name="date_from" value="<?= esc($dateFrom) ?>" class="w-full px-4 py-2.5 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm [color-scheme:dark]">
            <input type="date" name="date_to" value="<?= esc($dateTo) ?>" class="w-full px-4 py-2.5 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm [color-scheme:dark]">
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <input type="text" name="search" value="<?= esc($currentSearch) ?>" placeholder="Cari invoice, produk, atau nama user..." class="w-full px-4 py-2.5 pl-10 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm transition text-gray-300">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-xs"></i>
            </div>
            <button type="submit" class="px-6 py-2.5 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center justify-center gap-2 shadow-lg shadow-accent/10">
                <i class="fas fa-filter"></i> Filter
            </button>
        </div>
    </form>
</div>

<!-- Table -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto scrollbar-hide">
        <table class="w-full min-w-[850px]">
            <thead class="bg-black/80">
                <tr>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 w-12 text-center uppercase tracking-widest">No</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Invoice / Tanggal</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">User</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Produk</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Total</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Status</th>
                    <th class="text-center px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($transaksi)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center text-gray-500">
                            <div class="flex flex-col items-center gap-4">
                                <i class="fas fa-receipt text-3xl"></i>
                                <p class="text-sm">Tidak ada transaksi ditemukan</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($transaksi as $index => $trx): ?>
                        <tr class="hover:bg-white/5 transition group">
                            <td class="px-4 py-4 text-gray-500 text-sm text-center font-mono"><?= sprintf('%02d', ($currentPage - 1) * $perPage + $index + 1) ?></td>
                            <td class="px-4 py-4">
                                <p class="font-bold text-sm text-accent font-mono"><?= esc($trx['invoice_number']) ?></p>
                                <p class="text-[10px] text-gray-500 mt-0.5"><?= date('d M Y, H:i', strtotime($trx['created_at'])) ?></p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-bold text-sm text-gray-200 truncate"><?= esc($trx['user_name'] ?? 'Guest') ?></p>
                                <p class="text-[10px] text-gray-500 truncate mt-0.5 opacity-70"><?= esc($trx['user_email'] ?? '-') ?></p>
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-medium text-sm text-gray-300 truncate max-w-[200px]"><?= esc($trx['layanan_name'] ?? $trx['product_name']) ?></p>
                                <span class="text-[9px] font-black uppercase tracking-widest text-gray-600 bg-white/5 px-1.5 py-0.5 rounded"><?= esc($trx['layanan_subcategory'] ?? $trx['product_type']) ?></span>
                            </td>
                            <td class="px-4 py-4 font-black text-accent text-sm">
                                Rp <?= number_format($trx['total'], 0, ',', '.') ?>
                            </td>
                            <td class="px-4 py-4">
                                <?php
                                $statusColors = [
                                    'pending' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                                    'paid' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                                    'confirmed' => 'bg-accent/20 text-accent border-accent/30',
                                    'cancelled' => 'bg-red-500/20 text-red-400 border-red-500/30',
                                    'refunded' => 'bg-gray-500/20 text-gray-400 border-gray-500/30',
                                ];
                                $colorClass = $statusColors[$trx['status']] ?? 'bg-gray-500/20 text-gray-400 border-gray-500/30';
                                ?>
                                <span class="px-2 py-1 text-[10px] font-bold rounded uppercase border <?= $colorClass ?>">
                                    <?= ucfirst($trx['status']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?= base_url('keuangan/invoice/' . $trx['invoice_number']) ?>" target="_blank" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center transition group/btn" title="Lihat Invoice">
                                        <i class="fas fa-eye text-gray-400 group-hover/btn:text-white transition"></i>
                                    </a>
                                    <?php if ($trx['payment_method'] === 'manual_invoice'): ?>
                                        <button onclick="editInvoice(<?= $trx['id'] ?>)" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center transition group/btn text-accent" title="Edit Tagihan">
                                            <i class="fas fa-edit text-accent"></i>
                                        </button>
                                        <button onclick="deleteInvoice(<?= $trx['id'] ?>)" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center transition group/btn text-red-400" title="Hapus Tagihan">
                                            <i class="fas fa-trash text-red-400"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($pager->getPageCount() > 1): ?>
        <div class="p-4 border-t border-white/5 flex items-center justify-between bg-black/20">
            <p class="text-[10px] md:text-xs text-gray-500">Menampilkan <?= count($transaksi) ?> dari <?= $pager->getTotal() ?> data</p>
            <div class="flex gap-1 scale-90 origin-right">
                <?= $pager->links('default', 'default_full') ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Add/Edit Customer Invoice -->
<div id="invoiceModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-white/10 flex items-center justify-between sticky top-0 bg-[#111] z-10">
            <h2 class="text-xl font-bold text-white" id="modalTitle">Tambah Invoice Customer</h2>
            <button onclick="closeInvoiceModal()" class="text-gray-400 hover:text-white">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="invoiceForm" method="post" action="<?= base_url('keuangan/transaksi/save') ?>" class="p-6 space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="invoice_id">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">No Faktur *</label>
                    <input type="text" name="invoice_number" id="invoice_number" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-2 text-white focus:border-accent focus:outline-none font-mono">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Tanggal *</label>
                    <input type="date" name="tanggal" id="tanggal" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-2 text-white focus:border-accent focus:outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Nama Customer *</label>
                    <input type="text" name="customer_name" id="customer_name" required list="customer_list" class="w-full bg-black border border-white/20 rounded-xl px-4 py-2 text-white focus:border-accent focus:outline-none" placeholder="Ketik nama customer...">
                    <datalist id="customer_list">
                        <?php foreach ($users as $user): ?>
                            <option value="<?= esc($user['name']) ?>" data-email="<?= esc($user['email']) ?>"></option>
                        <?php endforeach; ?>
                    </datalist>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Email Customer (Opsional)</label>
                    <input type="email" name="customer_email" id="customer_email" class="w-full bg-black border border-white/20 rounded-xl px-4 py-2 text-white focus:border-accent focus:outline-none" placeholder="customer@email.com">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm text-gray-400 mb-2">Nama Produk / Deskripsi Layanan *</label>
                    <input type="text" name="product_name" id="product_name" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-2 text-white focus:border-accent focus:outline-none" placeholder="Contoh: Konsultasi VIP Portofolio">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Jenis Produk *</label>
                    <select name="product_type" id="product_type" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-2 text-white focus:border-accent focus:outline-none">
                        <option value="custom">Custom Bill</option>
                        <option value="layanan">Layanan</option>
                        <option value="course">Course</option>
                        <option value="subscription">Subscription</option>
                        <option value="poin">Poin</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Total Tagihan (Rp) *</label>
                    <input type="number" name="total" id="total" required min="0" class="w-full bg-black border border-white/20 rounded-xl px-4 py-2 text-white focus:border-accent focus:outline-none font-bold text-accent">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Status Pembayaran *</label>
                    <select name="status" id="status" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-2 text-white focus:border-accent focus:outline-none">
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="refunded">Refunded</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-2">Keterangan Tambahan</label>
                <textarea name="notes" id="notes" rows="3" class="w-full bg-black border border-white/20 rounded-xl px-4 py-2 text-white focus:border-accent focus:outline-none" placeholder="Tulis catatan atau memo jika diperlukan..."></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-white/10">
                <button type="button" onclick="closeInvoiceModal()" class="px-6 py-2 bg-white/10 text-white rounded-xl hover:bg-white/20 transition">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                    <i class="fas fa-save mr-2"></i> Simpan Invoice
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openAddInvoiceModal() {
        document.getElementById('invoice_id').value = '';
        document.getElementById('invoiceForm').reset();
        document.getElementById('tanggal').valueAsDate = new Date();
        document.getElementById('modalTitle').innerText = 'Tambah Invoice Customer';
        generateNoFaktur();
        document.getElementById('invoiceModal').classList.remove('hidden');
    }

    function closeInvoiceModal() {
        document.getElementById('invoiceModal').classList.add('hidden');
    }

    // Auto generate dynamic invoice number on date change
    document.getElementById('tanggal').addEventListener('change', generateNoFaktur);

    function generateNoFaktur() {
        const id = document.getElementById('invoice_id').value;
        if (id) {
            return; // Don't overwrite when editing
        }
        
        const tanggalVal = document.getElementById('tanggal').value;
        if (!tanggalVal) return;
        
        fetch('<?= base_url('keuangan/transaksi/generate-faktur') ?>?date=' + tanggalVal)
            .then(response => response.json())
            .then(res => {
                if (res.success) {
                    document.getElementById('invoice_number').value = res.invoice_number;
                }
            })
            .catch(error => console.error('Error generating invoice number:', error));
    }

    // Autocomplete customer email matching selection
    document.getElementById('customer_name').addEventListener('input', function() {
        const val = this.value;
        const options = document.querySelectorAll('#customer_list option');
        for (let option of options) {
            if (option.value === val) {
                document.getElementById('customer_email').value = option.getAttribute('data-email') || '';
                break;
            }
        }
    });

    function editInvoice(id) {
        document.getElementById('modalTitle').innerText = 'Edit Invoice Customer';
        fetch('<?= base_url('keuangan/transaksi/get/') ?>' + id)
            .then(response => response.json())
            .then(res => {
                if(res.success) {
                    const data = res.data;
                    document.getElementById('invoice_id').value = data.id;
                    document.getElementById('invoice_number').value = data.invoice_number;
                    document.getElementById('tanggal').value = data.tanggal;
                    document.getElementById('customer_name').value = data.customer_name;
                    document.getElementById('customer_email').value = data.customer_email;
                    document.getElementById('product_name').value = data.product_name;
                    document.getElementById('product_type').value = data.product_type;
                    document.getElementById('total').value = data.total;
                    document.getElementById('status').value = data.status;
                    document.getElementById('notes').value = data.notes;
                    
                    document.getElementById('invoiceModal').classList.remove('hidden');
                } else {
                    alert('Gagal mengambil data: ' + res.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengambil data.');
            });
    }

    function deleteInvoice(id) {
        if (confirm('Yakin ingin menghapus invoice customer ini?')) {
            window.location.href = '<?= base_url('keuangan/transaksi/delete/') ?>' + id;
        }
    }
</script>

<?= $this->endSection() ?>