<?php
$this->setVar('pageTitle', 'Transaksi');
$this->setVar('pageSubtitle', 'Kelola semua transaksi platform');
?>
<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>
<?php
$currentPage = $pager->getCurrentPage();
$perPage = 20;
?>

<!-- Income Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-4">
    <div class="bg-emerald-500/10 border border-emerald-500/20 rounded-xl p-3 md:p-4 shadow-sm">
        <p class="text-[9px] md:text-xs text-emerald-500/70 uppercase tracking-widest font-black mb-1">Pendapatan Hari Ini</p>
        <p class="text-lg md:text-xl font-black text-emerald-400">Rp <?= number_format($incomeToday, 0, ',', '.') ?></p>
    </div>
    <div class="bg-blue-500/10 border border-blue-500/20 rounded-xl p-3 md:p-4 shadow-sm">
        <p class="text-[9px] md:text-xs text-blue-500/70 uppercase tracking-widest font-black mb-1">Minggu Ini</p>
        <p class="text-lg md:text-xl font-black text-blue-400">Rp <?= number_format($incomeWeek, 0, ',', '.') ?></p>
    </div>
    <div class="bg-purple-500/10 border border-purple-500/20 rounded-xl p-3 md:p-4 shadow-sm">
        <p class="text-[9px] md:text-xs text-purple-500/70 uppercase tracking-widest font-black mb-1">Bulan Ini</p>
        <p class="text-lg md:text-xl font-black text-purple-400">Rp <?= number_format($incomeMonth, 0, ',', '.') ?></p>
    </div>
    <div class="bg-accent/10 border border-accent/20 rounded-xl p-3 md:p-4 shadow-sm">
        <p class="text-[9px] md:text-xs text-accent/70 uppercase tracking-widest font-black mb-1">Tahun Ini</p>
        <p class="text-lg md:text-xl font-black text-accent">Rp <?= number_format($incomeYear, 0, ',', '.') ?></p>
    </div>
</div>

<!-- Transaction Status Stats -->
<div class="grid grid-cols-2 lg:grid-cols-5 gap-3 md:gap-4 mb-6">
    <!-- Total Pendapatan (Full Width on Mobile) -->
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

    <!-- Pending Card -->
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

    <!-- Paid Card -->
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

    <!-- Confirmed Card -->
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

    <!-- Refund Card -->
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

<!-- Filters & Actions -->
<div class="bg-[#111] border border-white/10 rounded-2xl p-4 mb-6 shadow-xl">
    <form action="<?= base_url('superadmin/transaksi') ?>" method="get" class="flex flex-col gap-4">
        <!-- Main Filters Group -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <select name="status" onchange="this.form.submit()" class="w-full px-4 py-2.5 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm cursor-pointer appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:20px_20px] bg-right-4 bg-no-repeat">
                <option value="all" <?= $currentStatus === 'all' || !$currentStatus ? 'selected' : '' ?>>Semua Status</option>
                <option value="pending" <?= $currentStatus === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="paid" <?= $currentStatus === 'paid' ? 'selected' : '' ?>>Paid</option>
                <option value="confirmed" <?= $currentStatus === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                <option value="cancelled" <?= $currentStatus === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                <option value="refunded" <?= $currentStatus === 'refunded' ? 'selected' : '' ?>>Refunded</option>
            </select>
            <select name="product_type" onchange="this.form.submit()" class="w-full px-4 py-2.5 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm cursor-pointer appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:20px_20px] bg-right-4 bg-no-repeat">
                <option value="all" <?= $currentProductType === 'all' || !$currentProductType ? 'selected' : '' ?>>Semua Tipe</option>
                <?php foreach ($productTypes as $pt): ?>
                    <option value="<?= esc($pt['product_type']) ?>" <?= $currentProductType === $pt['product_type'] ? 'selected' : '' ?>><?= ucfirst(esc($pt['product_type'])) ?></option>
                <?php endforeach; ?>
            </select>
            <select name="product_name" onchange="this.form.submit()" class="w-full px-4 py-2.5 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm cursor-pointer appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:20px_20px] bg-right-4 bg-no-repeat">
                <option value="all" <?= $currentProductName === 'all' || !$currentProductName ? 'selected' : '' ?>>Semua Layanan</option>
                <?php foreach ($productsList as $p): ?>
                    <option value="<?= esc($p['product_name']) ?>" <?= $currentProductName === $p['product_name'] ? 'selected' : '' ?>><?= esc($p['product_name']) ?></option>
                <?php endforeach; ?>
            </select>
            <select name="creator" onchange="this.form.submit()" class="w-full px-4 py-2.5 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm cursor-pointer appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:20px_20px] bg-right-4 bg-no-repeat">
                <option value="all" <?= $currentCreator === 'all' || !$currentCreator ? 'selected' : '' ?>>Semua Creator</option>
                <optgroup label="WPA">
                    <?php foreach ($wpaList as $wpa): ?>
                        <option value="wpa:<?= $wpa['id'] ?>" <?= $currentCreator === "wpa:{$wpa['id']}" ? 'selected' : '' ?>><?= esc($wpa['name']) ?></option>
                    <?php endforeach; ?>
                </optgroup>
                <optgroup label="CWPA">
                    <?php foreach ($cwpaList as $cwpa): ?>
                        <option value="cwpa:<?= $cwpa['id'] ?>" <?= $currentCreator === "cwpa:{$cwpa['id']}" ? 'selected' : '' ?>><?= esc($cwpa['name']) ?></option>
                    <?php endforeach; ?>
                </optgroup>
            </select>
            <div class="relative w-full">
                <input type="date" name="date_from" value="<?= esc($dateFrom) ?>" class="w-full px-4 py-2.5 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm [color-scheme:dark]">
                <span class="absolute right-10 top-1/2 -translate-y-1/2 text-[10px] text-gray-500 uppercase font-bold pointer-events-none">Dari</span>
            </div>
            <div class="relative w-full">
                <input type="date" name="date_to" value="<?= esc($dateTo) ?>" class="w-full px-4 py-2.5 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm [color-scheme:dark]">
                <span class="absolute right-10 top-1/2 -translate-y-1/2 text-[10px] text-gray-500 uppercase font-bold pointer-events-none">Sampai</span>
            </div>
        </div>

        <!-- Search & Export Group -->
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <input type="text" name="search" value="<?= esc($currentSearch) ?>" placeholder="Cari invoice, produk, atau nama user..." class="w-full px-4 py-2.5 pl-10 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm transition">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-xs"></i>
            </div>
            <div class="flex flex-wrap gap-2">
                <button type="submit" class="px-6 py-2.5 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center gap-2 shadow-lg shadow-accent/10">
                    <i class="fas fa-filter"></i> Filter
                </button>
                
                <?php if ($isSuperAdmin): ?>
                <a href="<?= base_url('superadmin/transaksi/create-manual') ?>" class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-500 transition flex items-center gap-2 shadow-lg shadow-blue-500/20">
                    <i class="fas fa-plus-circle"></i> Transaksi Manual
                </a>
                <?php endif; ?>

                <a href="<?= base_url('superadmin/transaksi/export-excel') ?>?status=<?= esc($currentStatus) ?>&product_type=<?= esc($currentProductType) ?>&product_name=<?= urlencode($currentProductName) ?>&creator=<?= esc($currentCreator) ?>&date_from=<?= esc($dateFrom) ?>&date_to=<?= esc($dateTo) ?>" class="p-2.5 bg-green-600/20 text-green-500 border border-green-600/30 rounded-xl hover:bg-green-600 hover:text-white transition shadow-sm" title="Export Excel">
                    <i class="fas fa-file-excel"></i>
                </a>
                <a href="<?= base_url('superadmin/transaksi/export-pdf') ?>?status=<?= esc($currentStatus) ?>&product_type=<?= esc($currentProductType) ?>&product_name=<?= urlencode($currentProductName) ?>&creator=<?= esc($currentCreator) ?>&date_from=<?= esc($dateFrom) ?>&date_to=<?= esc($dateTo) ?>" target="_blank" class="p-2.5 bg-red-600/20 text-red-500 border border-red-600/30 rounded-xl hover:bg-red-600 hover:text-white transition shadow-sm" title="Export PDF">
                    <i class="fas fa-file-pdf"></i>
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Transactions Table -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto scrollbar-hide">
        <table class="w-full min-w-[850px]">
            <thead class="bg-black/80">
                <tr>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 w-12 text-center uppercase tracking-widest">No</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Invoice / Tanggal</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">User / Akun</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Layanan</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Total</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Status</th>
                    <th class="text-center px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($transaksiList)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center text-gray-500">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center">
                                    <i class="fas fa-receipt text-2xl"></i>
                                </div>
                                <p class="text-sm font-medium">Tidak ada transaksi ditemukan</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($transaksiList as $index => $trx): ?>
                        <tr class="hover:bg-white/5 transition group">
                            <td class="px-4 py-4 text-gray-500 text-sm text-center font-mono"><?= sprintf('%02d', ($currentPage - 1) * $perPage + $index + 1) ?></td>
                            <td class="px-4 py-4">
                                <div class="min-w-0">
                                    <p class="font-bold text-sm text-accent group-hover:text-white transition font-mono"><?= esc($trx['invoice_number']) ?></p>
                                    <p class="text-[10px] text-gray-500 mt-0.5"><?= date('d M Y, H:i', strtotime($trx['created_at'])) ?></p>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="min-w-0">
                                    <p class="font-bold text-sm text-gray-200 truncate"><?= esc($trx['user_name'] ?? 'Guest') ?></p>
                                    <p class="text-[10px] text-gray-500 truncate mt-0.5 opacity-70"><?= esc($trx['user_email'] ?? '-') ?></p>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="min-w-0">
                                    <p class="font-medium text-sm text-gray-300"><?= esc($trx['layanan_name'] ?? $trx['product_name']) ?></p>
                                    <span class="text-[9px] font-black uppercase tracking-widest text-gray-600 bg-white/5 px-1.5 py-0.5 rounded"><?= esc($trx['layanan_subcategory'] ?? $trx['product_type']) ?></span>
                                </div>
                            </td>
                            <td class="px-4 py-4 font-black text-sm">
                                <?php if (($trx['payment_method'] ?? '') === 'poin'): ?>
                                    <span class="text-yellow-400"><?= number_format($trx['total'], 0, ',', '.') ?> <i class="fas fa-coins ml-1"></i></span>
                                <?php else: ?>
                                    <span class="text-accent">Rp <?= number_format($trx['total'], 0, ',', '.') ?></span>
                                <?php endif; ?>
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
                                <span class="px-2.5 py-1 text-[10px] font-black rounded-lg uppercase tracking-wider border <?= $colorClass ?>">
                                    <?= ucfirst($trx['status']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?= base_url('superadmin/transaksi/detail/' . $trx['id']) ?>" class="w-9 h-9 flex items-center justify-center bg-white/5 text-gray-400 rounded-xl hover:bg-white/10 hover:text-white transition shadow-sm" title="Lihat Detail">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    <?php if ($canWrite ?? false): ?>
                                        <button onclick="openStatusModal(<?= $trx['id'] ?>, '<?= $trx['status'] ?>')" class="w-9 h-9 flex items-center justify-center bg-accent/10 text-accent rounded-xl hover:bg-accent hover:text-black transition shadow-sm" title="Update Status">
                                            <i class="fas fa-edit text-xs"></i>
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
            <p class="text-[10px] md:text-xs text-gray-500">Menampilkan <?= count($transaksiList) ?> dari <?= $pager->getTotal() ?> data</p>
            <div class="flex gap-1 scale-90 origin-right">
                <?= $pager->links('default', 'admin_pagination') ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Status Modal -->
<div id="statusModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/90 backdrop-blur-sm p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 md:p-8 max-w-sm w-full transform transition-all shadow-2xl">
        <div class="w-16 h-16 bg-accent/20 rounded-full flex items-center justify-center mx-auto mb-4 text-accent text-2xl">
            <i class="fas fa-sync-alt"></i>
        </div>
        <h3 class="text-xl font-black text-center mb-2 uppercase tracking-wide">Update Status</h3>
        <p class="text-gray-500 text-xs text-center mb-6">Pilih status terbaru untuk transaksi ini.</p>

        <form id="statusForm" method="post">
            <?= csrf_field() ?>
            <div class="relative mb-6">
                <select name="status" id="statusSelect" class="w-full px-4 py-4 bg-black border border-white/20 rounded-2xl focus:border-accent focus:outline-none text-sm font-bold cursor-pointer appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:24px_24px] bg-[right_16px_center] bg-no-repeat">
                    <option value="pending">PENDING</option>
                    <option value="paid">PAID</option>
                    <option value="confirmed">CONFIRMED</option>
                    <option value="cancelled">CANCELLED</option>
                    <option value="refunded">REFUNDED</option>
                </select>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeStatusModal()" class="flex-1 py-3.5 border border-white/10 rounded-2xl hover:bg-white/5 transition text-xs font-black tracking-widest">BATAL</button>
                <button type="submit" class="flex-1 py-3.5 bg-accent text-black font-black rounded-2xl hover:bg-white transition text-xs tracking-widest shadow-lg shadow-accent/20">UPDATE</button>
            </div>
        </form>
    </div>
</div>

<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<script>
    function openStatusModal(id, currentStatus) {
        document.getElementById('statusForm').action = '<?= base_url('superadmin/transaksi/update-status/') ?>' + id;
        document.getElementById('statusSelect').value = currentStatus;
        const modal = document.getElementById('statusModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeStatusModal() {
        const modal = document.getElementById('statusModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    document.getElementById('statusModal').addEventListener('click', function(e) {
        if (e.target === this) closeStatusModal();
    });
</script>
<?= $this->endSection() ?>