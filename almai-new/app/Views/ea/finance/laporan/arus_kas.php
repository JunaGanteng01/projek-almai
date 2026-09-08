<?= $this->extend('keuangan/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 print:hidden">
    <div>
        <h1 class="text-2xl font-bold text-white">Laporan Arus Kas</h1>
        <p class="text-gray-500 text-xs mt-1">Ringkasan aliran kas masuk dan keluar periode <?= date('d M Y', strtotime($startDate)) ?> - <?= date('d M Y', strtotime($endDate)) ?></p>
    </div>
    
</div>

<!-- Filters -->
<form action="<?= base_url('keuangan/arus-kas') ?>" method="get" class="flex flex-col md:flex-row gap-4 items-end mb-8 print:hidden">
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
        <a href="<?= base_url('keuangan/arus-kas/export-pdf?' . http_build_query(service('request')->getGet())) ?>" target="_blank" class="flex-1 md:flex-none px-6 py-2.5 bg-red-500/20 text-red-400 border border-red-500/50 hover:bg-red-500/30 font-bold rounded-xl transition flex items-center justify-center whitespace-nowrap">
            <i class="fas fa-file-pdf mr-2"></i> Export PDF
        </a>
    </div>
</form>

<!-- Report Content -->
<div class="bg-[#111] border border-white/10 rounded-3xl overflow-hidden shadow-2xl print:bg-white print:text-black print:rounded-none print:border-none print:shadow-none">
    
    <div class="p-8 md:p-12 border-b border-white/5 bg-gradient-to-br from-white/5 to-transparent">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-accent/10 rounded-2xl flex items-center justify-center border border-accent/20 print:hidden">
                    <i class="fas fa-money-bill-transfer text-accent text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-white uppercase tracking-tighter print:text-black">ALMA WPA PLATFORM</h2>
                    <p class="text-accent font-bold text-sm uppercase tracking-widest">Laporan Arus Kas (Cash Flow)</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Periode Laporan</p>
                <p class="text-lg font-bold text-white print:text-black"><?= date('d M Y', strtotime($startDate)) ?> – <?= date('d M Y', strtotime($endDate)) ?></p>
            </div>
        </div>
    </div>

    <div class="p-8 md:p-12 space-y-12">
        
        <!-- 1. OPERASIONAL -->
        <section>
            <div class="flex items-center gap-4 mb-6">
                <span class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-xs font-bold text-gray-400 border border-white/10">I</span>
                <h4 class="text-sm font-black text-white uppercase tracking-widest">Aktivitas Operasional</h4>
            </div>
            <div class="space-y-4 pl-12">
                <?php foreach ($cashData['operasional'] as $item): ?>
                <div class="flex justify-between items-center group py-1">
                    <span class="text-gray-400 group-hover:text-white transition cursor-default"><?= $item['nama'] ?></span>
                    <span class="font-mono text-sm text-white"><?= number_format($item['total'], 0, ',', '.') ?></span>
                </div>
                <?php endforeach; ?>
                <div class="pt-4 border-t border-white/5 flex justify-between items-center font-bold">
                    <span class="text-gray-300">Total Arus Kas dari Aktivitas Operasional</span>
                    <span class="text-white"><?= number_format($totalOperasional, 0, ',', '.') ?></span>
                </div>
            </div>
        </section>

        <!-- 2. INVESTASI -->
        <section>
            <div class="flex items-center gap-4 mb-6">
                <span class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-xs font-bold text-gray-400 border border-white/10">II</span>
                <h4 class="text-sm font-black text-white uppercase tracking-widest">Aktivitas Investasi</h4>
            </div>
            <div class="space-y-4 pl-12">
                <?php foreach ($cashData['investasi'] as $item): ?>
                <div class="flex justify-between items-center group py-1">
                    <span class="text-gray-400 group-hover:text-white transition cursor-default"><?= $item['nama'] ?></span>
                    <span class="font-mono text-sm text-white"><?= number_format($item['total'], 0, ',', '.') ?></span>
                </div>
                <?php endforeach; ?>
                <div class="pt-4 border-t border-white/5 flex justify-between items-center font-bold">
                    <span class="text-gray-300">Total Arus Kas dari Aktivitas Investasi</span>
                    <span class="text-white"><?= number_format($totalInvestasi, 0, ',', '.') ?></span>
                </div>
            </div>
        </section>

        <!-- 3. PENDANAAN -->
        <section>
            <div class="flex items-center gap-4 mb-6">
                <span class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-xs font-bold text-gray-400 border border-white/10">III</span>
                <h4 class="text-sm font-black text-white uppercase tracking-widest">Aktivitas Pendanaan</h4>
            </div>
            <div class="space-y-4 pl-12">
                <?php foreach ($cashData['pendanaan'] as $item): ?>
                <div class="flex justify-between items-center group py-1">
                    <span class="text-gray-400 group-hover:text-white transition cursor-default"><?= $item['nama'] ?></span>
                    <span class="font-mono text-sm text-white"><?= number_format($item['total'], 0, ',', '.') ?></span>
                </div>
                <?php endforeach; ?>
                <div class="pt-4 border-t border-white/5 flex justify-between items-center font-bold">
                    <span class="text-gray-300">Total Arus Kas dari Aktivitas Pendanaan</span>
                    <span class="text-white"><?= number_format($totalPendanaan, 0, ',', '.') ?></span>
                </div>
            </div>
        </section>

        <!-- REKAPITULASI -->
        <div class="pt-8 mt-12 border-t border-white/10 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white/5 rounded-2xl p-8 border border-white/10">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4">Ringkasan Perubahan Kas</p>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Kas & Setara Kas (Awal Periode)</span>
                        <span class="text-white"><?= number_format($openingBalance, 0, ',', '.') ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Perubahan Kas Neto</span>
                        <span class="text-white"><?= number_format($netChange, 0, ',', '.') ?></span>
                    </div>
                    <div class="flex justify-between items-center pt-4 border-t border-white/5 mt-4">
                        <span class="font-bold text-white uppercase text-xs tracking-widest">Kas & Setara Kas (Akhir Periode)</span>
                        <span class="text-2xl font-black text-accent"><?= number_format($openingBalance + $netChange, 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>
            <div class="flex flex-col justify-center">
                <div class="p-6 bg-accent/10 border border-accent/20 rounded-2xl">
                    <p class="text-sm font-bold text-accent mb-2 flex items-center gap-2">
                        <i class="fas fa-info-circle"></i> Catatan Laporan
                    </p>
                    <p class="text-xs text-gray-400 leading-relaxed">
                        Laporan ini disusun menggunakan metode tidak langsung yang menyajikan arus kas berdasarkan aktivitas operasional, investasi, dan pendanaan. Nilai negatif pada kas awal mencerminkan posisi kewajiban/overdraft pada periode pembukaan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleFilter() {
        document.getElementById('filterSection').classList.toggle('hidden');
    }
</script>

<?= $this->endSection() ?>
