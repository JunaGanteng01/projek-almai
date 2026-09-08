<?php $this->setVar('pageTitle', 'Customer Invoices'); ?>
<?= $this->extend('keuangan/layouts/main') ?>

<?= $this->section('content') ?>
<?php
$currentPage = $pager->getCurrentPage();
$perPage = 20;
?>

<!-- Header & Add Button Section -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Kelola Invoice Customer</h1>
        <p class="text-sm text-gray-400">Buat, kelola, dan cetak invoice manual khusus untuk tagihan pelanggan.</p>
    </div>
    <div>
        <a href="<?= base_url('keuangan/invoices/create') ?>" class="w-full md:w-auto px-6 py-2.5 bg-accent hover:bg-white text-black font-bold rounded-xl transition flex items-center justify-center gap-2 shadow-lg shadow-accent/20">
            <i class="fas fa-plus"></i> Buat Invoice Baru
        </a>
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
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-6">
    <!-- Total Tagihan -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-purple-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-wallet text-purple-400"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-xl md:text-2xl font-bold text-purple-200 truncate leading-tight">Rp <?= number_format($totalTagihan, 0, ',', '.') ?></p>
                <p class="text-[10px] md:text-xs text-gray-400 truncate uppercase tracking-widest font-bold">Total Nilai Tagihan</p>
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
                <p class="text-lg md:text-2xl font-bold text-yellow-200 truncate leading-tight"><?= $totalPending ?></p>
                <p class="text-[9px] md:text-xs text-gray-400 truncate uppercase tracking-widest font-bold">Belum Terbayar (Pending)</p>
            </div>
        </div>
    </div>

    <!-- Paid -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-3 md:p-4">
        <div class="flex items-center gap-2 md:gap-3">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-green-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-check-circle text-green-400 text-sm md:text-base"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-lg md:text-2xl font-bold text-green-300 truncate leading-tight"><?= $totalPaid ?></p>
                <p class="text-[9px] md:text-xs text-gray-400 truncate uppercase tracking-widest font-bold">Lunas Terbayar (Paid)</p>
            </div>
        </div>
    </div>

    <!-- Cancelled -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-3 md:p-4">
        <div class="flex items-center gap-2 md:gap-3">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-red-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-times-circle text-red-400 text-sm md:text-base"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-lg md:text-2xl font-bold text-red-300 truncate leading-tight"><?= $totalCancelled ?></p>
                <p class="text-[9px] md:text-xs text-gray-400 truncate uppercase tracking-widest font-bold">Dibatalkan (Cancelled)</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-[#111] border border-white/10 rounded-2xl p-4 mb-6 shadow-xl">
    <form action="<?= base_url('keuangan/invoices') ?>" method="get" class="flex flex-col gap-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <select name="status" onchange="this.form.submit()" class="w-full px-4 py-2.5 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-gray-300">
                <option value="all" <?= $currentStatus === 'all' || !$currentStatus ? 'selected' : '' ?>>Semua Status</option>
                <option value="pending" <?= $currentStatus === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="paid" <?= $currentStatus === 'paid' ? 'selected' : '' ?>>Paid</option>
                <option value="cancelled" <?= $currentStatus === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
            <input type="date" name="date_from" value="<?= esc($dateFrom) ?>" class="w-full px-4 py-2.5 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm [color-scheme:dark]">
            <input type="date" name="date_to" value="<?= esc($dateTo) ?>" class="w-full px-4 py-2.5 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm [color-scheme:dark]">
            <div class="relative flex-1 col-span-1 sm:col-span-2 lg:col-span-1">
                <input type="text" name="search" value="<?= esc($currentSearch) ?>" placeholder="Cari invoice, deskripsi, atau nama customer..." class="w-full px-4 py-2.5 pl-10 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm transition text-gray-300">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-xs"></i>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <button type="submit" class="px-6 py-2.5 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center justify-center gap-2 shadow-lg shadow-accent/10">
                <i class="fas fa-filter"></i> Terapkan Filter
            </button>
        </div>
    </form>
</div>

<!-- Table -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto scrollbar-hide">
        <table class="w-full min-w-[900px]">
            <thead class="bg-black/80">
                <tr>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 w-12 text-center uppercase tracking-widest">No</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">No Faktur</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Tanggal / Jatuh Tempo</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Customer</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Nama Layanan / Produk</th>
                    <th class="text-right px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Subtotal</th>
                    <th class="text-center px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">PPN</th>
                    <th class="text-right px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Total Tagihan</th>
                    <th class="text-center px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Status</th>
                    <th class="text-center px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($invoices)): ?>
                    <tr>
                        <td colspan="10" class="px-6 py-20 text-center text-gray-500">
                            <div class="flex flex-col items-center gap-4">
                                <i class="fas fa-file-invoice text-4xl text-gray-600"></i>
                                <p class="text-sm">Belum ada data invoice customer yang dibuat.</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($invoices as $index => $inv): ?>
                        <tr class="hover:bg-white/5 transition group">
                            <td class="px-4 py-4 text-gray-500 text-sm text-center font-mono"><?= sprintf('%02d', ($currentPage - 1) * $perPage + $index + 1) ?></td>
                            <td class="px-4 py-4">
                                <p class="font-bold text-sm text-accent font-mono"><?= esc($inv['invoice_number']) ?></p>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-300">
                                <div class="font-semibold"><?= date('d M Y', strtotime($inv['tanggal'])) ?></div>
                                <?php if ($inv['jatuh_tempo']): ?>
                                    <div class="text-[10px] text-red-400 font-semibold mt-0.5"><i class="far fa-calendar-times mr-1"></i> Due: <?= date('d M Y', strtotime($inv['jatuh_tempo'])) ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-bold text-sm text-gray-200 truncate"><?= esc($inv['customer_name']) ?></p>
                                <?php if ($inv['customer_email']): ?>
                                    <p class="text-[10px] text-gray-500 truncate mt-0.5"><?= esc($inv['customer_email']) ?></p>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-medium text-sm text-gray-300 truncate max-w-[200px]"><?= esc($inv['product_name']) ?></p>
                            </td>
                            <td class="px-4 py-4 text-right font-mono text-sm text-gray-400">
                                <?= ($inv['currency'] ?? 'IDR') === 'USD' ? '$ ' . number_format($inv['subtotal'], 2, '.', ',') : 'Rp ' . number_format($inv['subtotal'], 0, ',', '.') ?>
                            </td>
                            <td class="px-4 py-4 text-center font-mono text-xs text-yellow-400">
                                <?= number_format($inv['ppn'], 0) ?>%
                            </td>
                            <td class="px-4 py-4 text-right font-black text-accent text-sm font-mono">
                                <?= ($inv['currency'] ?? 'IDR') === 'USD' ? '$ ' . number_format($inv['total'], 2, '.', ',') : 'Rp ' . number_format($inv['total'], 0, ',', '.') ?>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <?php
                                $statusColors = [
                                    'pending' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                                    'paid' => 'bg-green-500/20 text-green-400 border-green-500/30',
                                    'cancelled' => 'bg-red-500/20 text-red-400 border-red-500/30',
                                ];
                                $colorClass = $statusColors[$inv['status']] ?? 'bg-gray-500/20 text-gray-400 border-gray-500/30';
                                ?>
                                <span class="px-2 py-1 text-[10px] font-bold rounded uppercase border <?= $colorClass ?>">
                                    <?= ucfirst($inv['status']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?= base_url('keuangan/invoices/detail/' . $inv['id']) ?>" target="_blank" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center transition group/btn" title="Cetak / Detail Invoice">
                                        <i class="fas fa-eye text-gray-400 group-hover/btn:text-white transition"></i>
                                    </a>
                                    <a href="<?= base_url('keuangan/invoices/edit/' . $inv['id']) ?>" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center transition group/btn text-accent" title="Edit Invoice">
                                        <i class="fas fa-edit text-accent"></i>
                                    </a>
                                    <button onclick="deleteInvoice(<?= $inv['id'] ?>)" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/10 flex items-center justify-center transition group/btn text-red-400" title="Hapus Invoice">
                                        <i class="fas fa-trash text-red-400"></i>
                                    </button>
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
            <p class="text-[10px] md:text-xs text-gray-500">Menampilkan <?= count($invoices) ?> dari <?= $pager->getTotal() ?> data</p>
            <div class="flex gap-1 scale-90 origin-right">
                <?= $pager->links('default', 'default_full') ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
    function deleteInvoice(id) {
        if (confirm('Yakin ingin menghapus invoice customer ini secara permanen?')) {
            window.location.href = '<?= base_url('keuangan/invoices/delete/') ?>' + id;
        }
    }
</script>

<?= $this->endSection() ?>
