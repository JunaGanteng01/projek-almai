<?= $this->extend('keuangan/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 print:hidden">
    <div>
        <h1 class="text-2xl font-bold text-white">Laporan Laba Rugi</h1>
        <p class="text-gray-500 text-xs mt-1">Ringkasan performa keuangan periode berjalan</p>
    </div>
    
</div>

<!-- Filters -->
<form action="<?= base_url('keuangan/laba-rugi') ?>" method="get" class="flex flex-col md:flex-row gap-4 items-end mb-8 print:hidden">
    <div class="flex-1 md:max-w-[200px]">
        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Tanggal Mulai</label>
        <input type="date" name="start_date" value="<?= esc($startDate) ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-2.5 focus:border-accent focus:outline-none text-white text-sm">
    </div>
    <div class="flex-1 md:max-w-[200px]">
        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Tanggal Akhir</label>
        <input type="date" name="end_date" value="<?= esc($endDate) ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-2.5 focus:border-accent focus:outline-none text-white text-sm">
    </div>
    <div class="flex gap-2 w-full md:w-auto">
        <button type="submit" class="flex-1 md:flex-none px-6 py-2.5 bg-accent hover:bg-white text-black font-bold rounded-xl transition shadow-lg shadow-accent/20 flex items-center justify-center">
            <i class="fas fa-filter mr-2"></i> Filter
        </button>
        <a href="<?= base_url('keuangan/laba-rugi/export-pdf?' . http_build_query(service('request')->getGet())) ?>" target="_blank" class="flex-1 md:flex-none px-6 py-2.5 bg-red-500/20 text-red-400 border border-red-500/50 hover:bg-red-500/30 font-bold rounded-xl transition flex items-center justify-center whitespace-nowrap">
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
                <div class="w-16 h-16 bg-accent/10 rounded-2xl flex items-center justify-center border border-accent/20 print:hidden">
                    <i class="fas fa-chart-pie text-accent text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-white uppercase tracking-tighter print:text-black">PT ALMA INDONESIA RAYA</h2>
                    <p class="text-accent font-bold text-sm uppercase tracking-widest">Laporan Laba Rugi</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Periode Laporan</p>
                <p class="text-lg font-bold text-white print:text-black"><?= date('d F Y', strtotime($startDate)) ?> – <?= date('d F Y', strtotime($endDate)) ?></p>
            </div>
        </div>
    </div>

    <!-- Main Report Body -->
    <div class="p-8 md:p-12 space-y-12">
        
        <!-- 1. PENDAPATAN -->
        <section>
            <div class="flex justify-between items-end mb-6 border-b border-white/10 pb-2 print:border-gray-800">
                <h4 class="text-sm font-black text-white uppercase tracking-widest print:text-black">I. PENDAPATAN USAHA</h4>
                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">IDR</span>
            </div>
            <div class="space-y-4">
                <?php foreach ($groupedData['pendapatan'] as $row): ?>
                    <div class="flex justify-between group">
                        <div class="flex gap-4">
                            <span class="text-xs font-mono text-gray-600"><?= esc($row['kode_akun']) ?></span>
                            <span class="text-sm text-gray-300 print:text-black"><?= esc($row['nama_akun']) ?></span>
                        </div>
                        <span class="text-sm font-bold text-white print:text-black"><?= number_format($row['net'], 0, ',', '.') ?></span>
                    </div>
                <?php endforeach; ?>
                
                <?php if (empty($groupedData['pendapatan'])): ?>
                    <div class="text-center py-4 text-gray-600 italic text-xs">Belum ada transaksi pendapatan pada periode ini</div>
                <?php endif; ?>

                <div class="flex justify-between items-center pt-4 border-t border-white/5 mt-4 print:border-gray-200">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Subtotal Pendapatan</span>
                    <span class="text-lg font-black text-white print:text-black"><?= number_format($totals['pendapatan'], 0, ',', '.') ?></span>
                </div>
            </div>
        </section>

        <!-- 2. HPP -->
        <section>
            <div class="flex justify-between items-end mb-6 border-b border-white/10 pb-2 print:border-gray-800">
                <h4 class="text-sm font-black text-white uppercase tracking-widest print:text-black">II. HARGA POKOK PENJUALAN</h4>
            </div>
            <div class="space-y-4">
                <?php foreach ($groupedData['hpp'] as $row): ?>
                    <div class="flex justify-between group">
                        <div class="flex gap-4">
                            <span class="text-xs font-mono text-gray-600"><?= esc($row['kode_akun']) ?></span>
                            <span class="text-sm text-gray-300 print:text-black"><?= esc($row['nama_akun']) ?></span>
                        </div>
                        <span class="text-sm font-bold text-gray-400 print:text-black">(<?= number_format($row['net'], 0, ',', '.') ?>)</span>
                    </div>
                <?php endforeach; ?>

                <div class="flex justify-between items-center pt-4 border-t border-white/5 mt-4 print:border-gray-200">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Subtotal HPP</span>
                    <span class="text-lg font-bold text-gray-400 print:text-black">(<?= number_format($totals['hpp'], 0, ',', '.') ?>)</span>
                </div>
            </div>
        </section>

        <!-- LABA KOTOR -->
        <div class="bg-white/5 p-6 rounded-2xl flex justify-between items-center border border-white/10 print:bg-gray-100 print:border-gray-800 print:text-black">
            <span class="text-sm font-black uppercase tracking-widest">LABA KOTOR (GROSS PROFIT)</span>
            <span class="text-2xl font-black text-accent print:text-black">Rp <?= number_format($grossProfit, 0, ',', '.') ?></span>
        </div>

        <!-- 3. BEBAN OPERASIONAL -->
        <section>
            <div class="flex justify-between items-end mb-6 border-b border-white/10 pb-2 print:border-gray-800">
                <h4 class="text-sm font-black text-white uppercase tracking-widest print:text-black">III. BEBAN OPERASIONAL</h4>
            </div>
            <div class="space-y-4">
                <?php foreach ($groupedData['beban'] as $row): ?>
                    <div class="flex justify-between group">
                        <div class="flex gap-4">
                            <span class="text-xs font-mono text-gray-600"><?= esc($row['kode_akun']) ?></span>
                            <span class="text-sm text-gray-300 print:text-black"><?= esc($row['nama_akun']) ?></span>
                        </div>
                        <span class="text-sm font-bold text-gray-400 print:text-black"><?= number_format($row['net'], 0, ',', '.') ?></span>
                    </div>
                <?php endforeach; ?>

                <div class="flex justify-between items-center pt-4 border-t border-white/5 mt-4 print:border-gray-200">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Subtotal Beban Operasional</span>
                    <span class="text-lg font-bold text-gray-400 print:text-black">(<?= number_format($totals['beban'], 0, ',', '.') ?>)</span>
                </div>
            </div>
        </section>

        <!-- LABA OPERASIONAL -->
        <div class="flex justify-between items-center border-y border-white/10 py-6 print:border-gray-400 print:text-black">
            <span class="text-sm font-bold uppercase tracking-widest text-gray-500 print:text-black">LABA OPERASIONAL</span>
            <span class="text-xl font-black text-white print:text-black">Rp <?= number_format($operatingProfit, 0, ',', '.') ?></span>
        </div>

        <!-- 4. LAIN-LAIN -->
        <section>
            <div class="flex justify-between items-end mb-6 border-b border-white/10 pb-2 print:border-gray-800">
                <h4 class="text-sm font-black text-white uppercase tracking-widest print:text-black">IV. PENDAPATAN & BEBAN LAIN</h4>
            </div>
            <div class="space-y-4">
                <?php foreach ($groupedData['pendapatan_lain'] as $row): ?>
                    <div class="flex justify-between">
                        <div class="flex gap-4">
                            <span class="text-xs font-mono text-gray-600"><?= esc($row['kode_akun']) ?></span>
                            <span class="text-sm text-gray-300 print:text-black"><?= esc($row['nama_akun']) ?></span>
                        </div>
                        <span class="text-sm font-bold text-white print:text-black"><?= number_format($row['net'], 0, ',', '.') ?></span>
                    </div>
                <?php endforeach; ?>
                <?php foreach ($groupedData['beban_lain'] as $row): ?>
                    <div class="flex justify-between">
                        <div class="flex gap-4">
                            <span class="text-xs font-mono text-gray-600"><?= esc($row['kode_akun']) ?></span>
                            <span class="text-sm text-gray-300 print:text-black"><?= esc($row['nama_akun']) ?></span>
                        </div>
                        <span class="text-sm font-bold text-red-400 print:text-black">(<?= number_format($row['net'], 0, ',', '.') ?>)</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- FINAL NET PROFIT -->
        <div class="mt-12 bg-accent text-black p-8 rounded-[2rem] shadow-2xl shadow-accent/20 flex flex-col md:flex-row justify-between items-center gap-6 print:bg-black print:text-white print:rounded-none">
            <div>
                <h3 class="text-xs font-black uppercase tracking-[0.3em] mb-2 opacity-60">Laba Bersih (Net Profit)</h3>
                <p class="text-sm italic opacity-50">Setelah dikurangi seluruh beban operasional dan pajak</p>
            </div>
            <div class="text-center md:text-right">
                <span class="text-4xl md:text-5xl font-black tracking-tighter">Rp <?= number_format($netProfit, 0, ',', '.') ?></span>
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