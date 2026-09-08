<?= $this->extend('keuangan/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 print:hidden">
    <div>
        <h1 class="text-2xl font-bold text-white">Laporan Neraca</h1>
        <p class="text-gray-500 text-xs mt-1">Posisi Keuangan (Balance Sheet) per <?= date('d M Y', strtotime($date)) ?></p>
    </div>
    
</div>

<!-- Filters -->
<form action="<?= base_url('keuangan/neraca') ?>" method="get" class="flex flex-col md:flex-row gap-4 items-end mb-8 print:hidden">
    <div class="flex-1 md:max-w-[200px]">
        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Per Tanggal</label>
        <input type="date" name="date" value="<?= esc($date) ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-2.5 focus:border-accent focus:outline-none text-white text-sm">
    </div>
    <div class="flex gap-2 w-full md:w-auto">
        <button type="submit" class="flex-1 md:flex-none px-6 py-2.5 bg-accent hover:bg-white text-black font-bold rounded-xl transition shadow-lg shadow-accent/20 flex items-center justify-center">
            <i class="fas fa-filter mr-2"></i> Filter
        </button>
        <a href="<?= base_url('keuangan/neraca/export-excel?' . http_build_query(service('request')->getGet())) ?>" target="_blank" class="flex-1 md:flex-none px-6 py-2.5 bg-green-500/20 text-green-400 border border-green-500/50 hover:bg-green-500/30 font-bold rounded-xl transition flex items-center justify-center whitespace-nowrap">
            <i class="fas fa-file-excel mr-2"></i> Export Excel
        </a>
        <a href="<?= base_url('keuangan/neraca/export-pdf?' . http_build_query(service('request')->getGet())) ?>" target="_blank" class="flex-1 md:flex-none px-6 py-2.5 bg-red-500/20 text-red-400 border border-red-500/50 hover:bg-red-500/30 font-bold rounded-xl transition flex items-center justify-center whitespace-nowrap">
            <i class="fas fa-file-pdf mr-2"></i> Export PDF
        </a>
    </div>
</form>

<!-- Report Content Card -->
<div class="bg-[#111] border border-white/10 rounded-3xl overflow-hidden shadow-2xl print:bg-white print:text-black print:rounded-none print:border-none print:shadow-none">
    
    <!-- Report Header (Visual) -->
    <div class="p-8 md:p-12 border-b border-white/5 bg-gradient-to-br from-white/5 to-transparent print:bg-none print:border-gray-200">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-blue-500/10 rounded-2xl flex items-center justify-center border border-blue-500/20 print:hidden">
                    <i class="fas fa-balance-scale text-blue-500 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-white uppercase tracking-tighter print:text-black">PT ALMA INDONESIA RAYA</h2>
                    <p class="text-blue-500 font-bold text-sm uppercase tracking-widest">Neraca (Balance Sheet)</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Posisi Keuangan Per</p>
                <p class="text-lg font-bold text-white print:text-black"><?= date('d F Y', strtotime($date)) ?></p>
            </div>
        </div>
    </div>

    <!-- Main Report Body -->
    <div class="p-8 md:p-12">
        <div class="flex flex-col lg:flex-row gap-12">
            
            <!-- LEFT COLUMN: ASSETS -->
            <div class="flex-1 space-y-10">
                <div>
                    <div class="flex justify-between items-center mb-6 border-b-2 border-white/20 pb-2 print:border-gray-800">
                        <h4 class="text-sm font-black text-white uppercase tracking-widest print:text-black">AKTIVA (ASSETS)</h4>
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">IDR</span>
                    </div>

                    <!-- Aset Lancar -->
                    <div class="mb-8">
                        <h5 class="text-xs font-bold text-blue-400 uppercase tracking-widest mb-4 border-b border-white/5 pb-1">Aset Lancar</h5>
                        <div class="space-y-3">
                            <?php foreach ($neracaData['aset_lancar'] as $row): ?>
                                <div class="flex justify-between text-sm group">
                                    <span class="text-gray-300 print:text-black"><?= esc($row['nama_akun']) ?></span>
                                    <span class="font-bold text-white print:text-black"><?= number_format($row['total'], 0, ',', '.') ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Aset Tetap -->
                    <div class="mb-8">
                        <h5 class="text-xs font-bold text-blue-400 uppercase tracking-widest mb-4 border-b border-white/5 pb-1">Aset Tetap</h5>
                        <div class="space-y-3">
                            <?php foreach ($neracaData['aset_tetap'] as $row): ?>
                                <div class="flex justify-between text-sm group">
                                    <span class="text-gray-300 print:text-black"><?= esc($row['nama_akun']) ?></span>
                                    <span class="font-bold text-white print:text-black"><?= number_format($row['total'], 0, ',', '.') ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Total Assets Summary -->
                <div class="bg-blue-500/5 p-6 rounded-2xl border border-blue-500/10 flex justify-between items-center print:bg-gray-100 print:border-gray-800">
                    <span class="text-sm font-black uppercase tracking-widest text-blue-500 print:text-black">TOTAL ASET</span>
                    <span class="text-2xl font-black text-white print:text-black"><?= number_format($totals['aset'], 0, ',', '.') ?></span>
                </div>
            </div>

            <!-- RIGHT COLUMN: LIABILITIES & EQUITY -->
            <div class="flex-1 space-y-10">
                <div>
                    <div class="flex justify-between items-center mb-6 border-b-2 border-white/20 pb-2 print:border-gray-800">
                        <h4 class="text-sm font-black text-white uppercase tracking-widest print:text-black">PASIVA (LIABILITIES & EQUITY)</h4>
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">IDR</span>
                    </div>

                    <!-- Kewajiban -->
                    <div class="mb-8">
                        <h5 class="text-xs font-bold text-orange-400 uppercase tracking-widest mb-4 border-b border-white/5 pb-1">Kewajiban</h5>
                        <div class="space-y-3">
                            <?php foreach ($neracaData['kewajiban'] as $row): ?>
                                <div class="flex justify-between text-sm group">
                                    <span class="text-gray-300 print:text-black"><?= esc($row['nama_akun']) ?></span>
                                    <span class="font-bold text-white print:text-black"><?= number_format($row['total'], 0, ',', '.') ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Ekuitas -->
                    <div class="mb-8">
                        <h5 class="text-xs font-bold text-green-400 uppercase tracking-widest mb-4 border-b border-white/5 pb-1">Ekuitas</h5>
                        <div class="space-y-3">
                            <?php foreach ($neracaData['ekuitas'] as $row): ?>
                                <div class="flex justify-between text-sm group">
                                    <span class="text-gray-300 print:text-black"><?= esc($row['nama_akun']) ?></span>
                                    <span class="font-bold text-white print:text-black"><?= number_format($row['total'], 0, ',', '.') ?></span>
                                </div>
                            <?php endforeach; ?>
                            
                            <div class="flex justify-between text-sm font-bold text-accent italic pt-2 border-t border-white/5 mt-2 print:text-black print:border-gray-200">
                                <span>Laba Tahun Berjalan</span>
                                <span><?= number_format($labaBerjalan, 0, ',', '.') ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Liabilities & Equity Summary -->
                <div class="bg-accent/5 p-6 rounded-2xl border border-accent/10 flex justify-between items-center print:bg-black print:text-white">
                    <span class="text-sm font-black uppercase tracking-widest text-accent print:text-white">TOTAL PASIVA</span>
                    <span class="text-2xl font-black text-white print:text-white"><?= number_format($totals['kewajiban'] + $totals['ekuitas'], 0, ',', '.') ?></span>
                </div>

                <!-- Balance Check -->
                <?php if (abs($totals['aset'] - ($totals['kewajiban'] + $totals['ekuitas'])) > 1): ?>
                    <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-xl flex items-center gap-3">
                        <i class="fas fa-exclamation-triangle text-xl"></i>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest">Neraca Tidak Seimbang</p>
                            <p class="text-[10px] opacity-70">Selisih: Rp <?= number_format($totals['aset'] - ($totals['kewajiban'] + $totals['ekuitas']), 0, ',', '.') ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="bg-accent/10 border border-accent/20 text-accent p-4 rounded-xl flex items-center gap-3 print:hidden">
                        <i class="fas fa-check-circle text-xl"></i>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest">Neraca Seimbang (Balanced)</p>
                            <p class="text-[10px] opacity-70">Aktiva dan Pasiva telah sinkron sempurna.</p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="text-center pt-12 print:text-gray-400">
            <p class="text-[10px] font-bold text-gray-700 uppercase tracking-widest mb-2">Laporan ini dibuat secara otomatis oleh sistem</p>
            <p class="text-[9px] text-gray-800">ALMA WPA Accounting Module v2.0 • <?= date('d/m/Y H:i') ?></p>
        </div>
    </div>
</div>

<script>
    function toggleFilter() {
        const section = document.getElementById('filterSection');
        section.classList.toggle('hidden');
    }
</script>

<style>
@media print {
    body { background: white !important; color: black !important; }
    .md\:ml-64 { margin-left: 0 !important; }
    nav, aside, header, .print\:hidden { display: none !important; }
    main { padding: 0 !important; margin: 0 !important; }
}
</style>

<?= $this->endSection() ?>