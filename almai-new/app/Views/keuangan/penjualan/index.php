<?= $this->extend('keuangan/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 print:hidden">
    <div>
        <h1 class="text-2xl font-bold text-white">Daftar Penjualan</h1>
        <p class="text-gray-500 text-xs mt-1">Manajemen penjualan dari data CSV</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <button onclick="toggleFilter()" class="px-4 py-2 bg-white/5 text-gray-400 font-bold rounded-xl hover:bg-white/10 transition border border-white/10 flex items-center gap-2">
            <i class="fas fa-sliders-h"></i> Filter
        </button>
        <button onclick="document.getElementById('importModal').showModal()" class="px-4 py-2 bg-blue-500 text-white font-bold rounded-xl hover:bg-blue-600 transition flex items-center gap-2 shadow-lg shadow-blue-500/20">
            <i class="fas fa-upload"></i> Import CSV
        </button>
        <a href="<?= base_url('keuangan/penjualan/form') ?>" class="px-4 py-2 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center gap-2 shadow-lg shadow-accent/20">
            <i class="fas fa-plus"></i> Tambah
        </a>
        <a href="<?= base_url('keuangan/penjualan/export-csv') ?>" class="px-4 py-2 bg-green-500/10 text-green-400 font-bold rounded-xl hover:bg-green-500/20 transition border border-green-500/30 flex items-center gap-2">
            <i class="fas fa-download"></i> Export
        </a>
    </div>
</div>

<!-- Filters -->
<div id="filterSection" class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-8 shadow-xl hidden animate-in fade-in slide-in-from-top-4 print:hidden">
    <form action="<?= base_url('keuangan/penjualan') ?>" method="get" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Cari</label>
            <input type="text" name="search" value="<?= esc($search) ?>" placeholder="Nomor tagihan, nama..." class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none text-white text-sm">
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Status Pembayaran</label>
            <select name="status_pembayaran" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none text-white text-sm">
                <option value="">Semua</option>
                <option value="belum_dibayar" <?= $status_pembayaran === 'belum_dibayar' ? 'selected' : '' ?>>Belum Dibayar</option>
                <option value="sebagian_dibayar" <?= $status_pembayaran === 'sebagian_dibayar' ? 'selected' : '' ?>>Sebagian Dibayar</option>
                <option value="lunas" <?= $status_pembayaran === 'lunas' ? 'selected' : '' ?>>Lunas</option>
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Tanggal Dari</label>
            <input type="date" name="tanggal_dari" value="<?= esc($tanggal_dari) ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none text-white text-sm">
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Tanggal Sampai</label>
            <input type="date" name="tanggal_sampai" value="<?= esc($tanggal_sampai) ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none text-white text-sm">
        </div>
        <button type="submit" class="w-full py-3 bg-accent hover:bg-white text-black font-bold rounded-xl transition shadow-lg shadow-accent/20">
            Cari
        </button>
    </form>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-[#111] border border-blue-500/20 rounded-2xl p-4 shadow-xl">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center border border-blue-500/20">
                <i class="fas fa-file-invoice-dollar text-blue-400 text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Total Transaksi</p>
                <p class="text-xl font-black text-blue-400"><?= isset($summary['total_transaksi']) ? $summary['total_transaksi'] : 0 ?></p>
            </div>
        </div>
    </div>
    <div class="bg-[#111] border border-green-500/20 rounded-2xl p-4 shadow-xl">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-green-500/10 rounded-xl flex items-center justify-center border border-green-500/20">
                <i class="fas fa-coins text-green-400 text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Total Nilai</p>
                <p class="text-xl font-black text-green-400">Rp <?= isset($summary['total_nilai']) ? number_format($summary['total_nilai'], 0, ',', '.') : '0' ?></p>
            </div>
        </div>
    </div>
    <div class="bg-[#111] border border-emerald-500/20 rounded-2xl p-4 shadow-xl">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-emerald-500/10 rounded-xl flex items-center justify-center border border-emerald-500/20">
                <i class="fas fa-check-circle text-emerald-400 text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Lunas</p>
                <p class="text-xl font-black text-emerald-400">Rp <?= isset($summary['total_lunas']) ? number_format($summary['total_lunas'], 0, ',', '.') : '0' ?></p>
            </div>
        </div>
    </div>
    <div class="bg-[#111] border border-orange-500/20 rounded-2xl p-4 shadow-xl">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-orange-500/10 rounded-xl flex items-center justify-center border border-orange-500/20">
                <i class="fas fa-hourglass text-orange-400 text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Piutang</p>
                <p class="text-xl font-black text-orange-400">Rp <?= number_format($piutang_total, 0, ',', '.') ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Table -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl print:shadow-none print:border-gray-400">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-white/5 border-b border-white/10 print:bg-gray-100 print:border-gray-400">
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest print:text-black">No. Tagihan</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest print:text-black">Nama Kontak</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest print:text-black">Produk</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-widest print:text-black">Total</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-widest print:text-black">Status</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-widest print:text-black hide-mobile">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5 print:divide-gray-400">
                <?php if (!empty($penjualan)): ?>
                    <?php foreach ($penjualan as $row): ?>
                    <tr class="hover:bg-white/5 transition print:hover:bg-transparent">
                        <td class="px-6 py-4 text-sm font-mono text-gray-300 print:text-black"><?= esc($row['nomor_tagihan']) ?></td>
                        <td class="px-6 py-4 text-sm text-gray-300 print:text-black"><?= esc($row['nama_kontak']) ?></td>
                        <td class="px-6 py-4 text-sm text-gray-400 print:text-black"><?= esc($row['nama_produk']) ?></td>
                        <td class="px-6 py-4 text-sm font-bold text-white text-right print:text-black">Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                        <td class="px-6 py-4 text-sm text-center">
                            <?php
                            $statusClass = match($row['status_pembayaran']) {
                                'lunas' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                                'sebagian_dibayar' => 'bg-amber-500/20 text-amber-400 border-amber-500/30',
                                default => 'bg-red-500/20 text-red-400 border-red-500/30',
                            };
                            ?>
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold <?= $statusClass ?> border print:bg-transparent print:text-black print:border-black">
                                <?= ucfirst(str_replace('_', ' ', $row['status_pembayaran'])) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-center hide-mobile print:hidden">
                            <div class="flex justify-center gap-2">
                                <a href="<?= base_url('keuangan/penjualan/detail/' . $row['id']) ?>" class="text-blue-400 hover:text-blue-300 transition font-bold text-xs" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?= base_url('keuangan/penjualan/form/' . $row['id']) ?>" class="text-amber-400 hover:text-amber-300 transition font-bold text-xs" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?= base_url('keuangan/penjualan/delete/' . $row['id']) ?>" method="post" style="display:inline;" onsubmit="return confirm('Yakin hapus?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="text-red-400 hover:text-red-300 transition font-bold text-xs" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500 text-sm italic">
                            Tidak ada data penjualan
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<?php if ($pager): ?>
<div class="mt-6">
    <?= $pager->links('penjualan', 'tailwind_full') ?>
</div>
<?php endif; ?>

<!-- Import Modal -->
<dialog id="importModal" class="modal">
    <div class="modal-box bg-[#111] border border-white/10">
        <h3 class="font-bold text-lg text-white mb-4">Import Data Penjualan dari CSV</h3>
        <form action="<?= base_url('keuangan/penjualan/import-csv') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="block text-sm font-bold text-gray-400 mb-2">Pilih File CSV</label>
                <input type="file" name="csv_file" accept=".csv" required class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-white text-sm">
                <p class="text-xs text-gray-500 mt-2">Format: CSV dengan kolom: Nomor Tagihan, Nama Kontak, Perusahaan, Email, dst.</p>
            </div>
            <div class="modal-action">
                <button type="submit" class="px-4 py-2 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                    Import
                </button>
                <button type="button" onclick="document.getElementById('importModal').close()" class="px-4 py-2 bg-white/10 text-gray-400 font-bold rounded-xl hover:bg-white/20 transition">
                    Batal
                </button>
            </div>
        </form>
    </div>
</dialog>

<script>
function toggleFilter() {
    document.getElementById('filterSection').classList.toggle('hidden');
}
</script>

<?= $this->endSection() ?>
