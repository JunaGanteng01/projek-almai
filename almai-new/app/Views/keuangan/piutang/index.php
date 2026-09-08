<?= $this->extend('keuangan/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 print:hidden">
    <div>
        <h1 class="text-2xl font-bold text-white">Daftar Piutang</h1>
        <p class="text-gray-500 text-xs mt-1">Manajemen piutang pelanggan yang belum lunas</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <button onclick="toggleFilter()" class="px-4 py-2 bg-white/5 text-gray-400 font-bold rounded-xl hover:bg-white/10 transition border border-white/10 flex items-center gap-2">
            <i class="fas fa-calendar"></i> Filter
        </button>
        <button onclick="window.print()" class="px-4 py-2 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center gap-2 shadow-lg shadow-accent/20">
            <i class="fas fa-file-pdf"></i> Cetak
        </button>
    </div>
</div>

<!-- Filters -->
<div id="filterSection" class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-8 shadow-xl hidden animate-in fade-in slide-in-from-top-4 print:hidden">
    <form action="<?= base_url('keuangan/piutang') ?>" method="get" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
        <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Tanggal Mulai</label>
            <input type="date" name="start_date" value="<?= esc($startDate) ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none text-white text-sm">
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Tanggal Akhir</label>
            <input type="date" name="end_date" value="<?= esc($endDate) ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none text-white text-sm">
        </div>
        <button type="submit" class="w-full py-3 bg-accent hover:bg-white text-black font-bold rounded-xl transition shadow-lg shadow-accent/20">
            Tampilkan
        </button>
    </form>
</div>

<!-- Summary Card -->
<div class="bg-[#111] border border-blue-500/20 rounded-2xl p-6 mb-8 shadow-xl print:bg-white print:text-black print:border-gray-400">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-blue-500/10 rounded-2xl flex items-center justify-center border border-blue-500/20 print:hidden">
                <i class="fas fa-file-invoice-dollar text-blue-400 text-2xl"></i>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Total Piutang</p>
                <p class="text-3xl font-black text-blue-400 print:text-black">Rp <?= number_format($totalPiutang, 0, ',', '.') ?></p>
            </div>
        </div>
        <div class="text-right">
            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Periode</p>
            <p class="text-lg font-bold text-white print:text-black"><?= date('d M Y', strtotime($startDate)) ?> – <?= date('d M Y', strtotime($endDate)) ?></p>
        </div>
    </div>
</div>

<!-- Table -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl print:shadow-none print:border-gray-400">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-white/5 border-b border-white/10 print:bg-gray-100 print:border-gray-400">
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest print:text-black">Invoice</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest print:text-black">Pelanggan</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest print:text-black">Tanggal</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-widest print:text-black">Jumlah</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-widest print:text-black">Status</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-widest print:text-black hide-mobile">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5 print:divide-gray-400">
                <?php if (!empty($piutangData)): ?>
                    <?php foreach ($piutangData as $row): ?>
                    <tr class="hover:bg-white/5 transition print:hover:bg-transparent">
                        <td class="px-6 py-4 text-sm font-mono text-gray-300 print:text-black"><?= esc($row['invoice_number'] ?? '-') ?></td>
                        <td class="px-6 py-4 text-sm text-gray-300 print:text-black"><?= esc($row['customer_name'] ?? 'Unknown') ?></td>
                        <td class="px-6 py-4 text-sm text-gray-400 print:text-black"><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                        <td class="px-6 py-4 text-sm font-bold text-white text-right print:text-black">Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                        <td class="px-6 py-4 text-sm text-center">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30 print:bg-transparent print:text-black print:border-black">
                                <?= ucfirst($row['status']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-center hide-mobile print:hidden">
                            <a href="#" class="text-blue-400 hover:text-blue-300 transition font-bold text-xs">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500 text-sm italic">
                            Tidak ada data piutang pada periode ini
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<?php if (!empty($piutangData)): ?>
<div class="mt-6 flex justify-center print:hidden">
    <?= $pager->links('default', 'tailwind_pagination') ?>
</div>
<?php endif; ?>

<!-- Info Banner -->
<div class="mt-8 p-5 bg-blue-500/5 border border-blue-500/20 rounded-2xl flex items-start gap-4 print:hidden">
    <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center flex-shrink-0">
        <i class="fas fa-info-circle text-blue-400"></i>
    </div>
    <div>
        <p class="font-bold text-blue-400 text-sm mb-1">Informasi Piutang</p>
        <ul class="text-xs text-gray-400 space-y-1 list-disc list-inside">
            <li>Piutang adalah tagihan kepada pelanggan yang belum dibayar.</li>
            <li>Pantau piutang secara berkala untuk memastikan cash flow yang sehat.</li>
            <li>Hubungi pelanggan jika pembayaran melampaui jatuh tempo.</li>
        </ul>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function toggleFilter() {
    const filterSection = document.getElementById('filterSection');
    filterSection.classList.toggle('hidden');
}
</script>
<?= $this->endSection() ?>
