<?= $this->extend('keuangan/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 print:hidden">
    <div>
        <h1 class="text-2xl font-bold text-white">Laporan Perubahan Modal</h1>
        <p class="text-gray-500 text-xs mt-1">Laporan perubahan ekuitas pemilik periode <?= date('d M Y', strtotime($startDate)) ?> - <?= date('d M Y', strtotime($endDate)) ?></p>
    </div>
    
</div>

<!-- Filters -->
<form action="<?= base_url('keuangan/perubahan-modal') ?>" method="get" class="flex flex-col md:flex-row gap-4 items-end mb-8 print:hidden">
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
        <a href="<?= base_url('keuangan/perubahan-modal/export-pdf?' . http_build_query(service('request')->getGet())) ?>" target="_blank" class="flex-1 md:flex-none px-6 py-2.5 bg-red-500/20 text-red-400 border border-red-500/50 hover:bg-red-500/30 font-bold rounded-xl transition flex items-center justify-center whitespace-nowrap">
            <i class="fas fa-file-pdf mr-2"></i> Export PDF
        </a>
    </div>
</form>

<!-- Report Content -->
<div class="bg-[#111] border border-white/10 rounded-3xl overflow-hidden shadow-2xl print:bg-white print:text-black print:rounded-none print:border-none print:shadow-none">
    
    <div class="p-8 md:p-12 border-b border-white/5 bg-gradient-to-br from-white/5 to-transparent">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-blue-500/10 rounded-2xl flex items-center justify-center border border-blue-500/20 print:hidden">
                    <i class="fas fa-chart-pie text-blue-500 text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-white uppercase tracking-tighter print:text-black">ALMA WPA PLATFORM</h2>
                    <p class="text-blue-500 font-bold text-sm uppercase tracking-widest">Laporan Perubahan Modal</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Periode Laporan</p>
                <p class="text-lg font-bold text-white print:text-black"><?= date('d M Y', strtotime($startDate)) ?> – <?= date('d M Y', strtotime($endDate)) ?></p>
            </div>
        </div>
    </div>

    <div class="p-8 md:p-12">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-white/10">
                        <th class="py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Keterangan Akun</th>
                        <th class="py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-widest">Saldo Awal</th>
                        <th class="py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-widest">Perubahan</th>
                        <th class="py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-widest">Saldo Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php if (empty($equityData)): ?>
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-500 italic">Belum ada data ekuitas</td>
                        </tr>
                    <?php else: ?>
                        <?php 
                        $totalStart = 0;
                        $totalEnd = 0;
                        foreach ($equityData as $equity): 
                            $totalStart += $equity['total'];
                            $totalEnd += $equity['total'];
                        ?>
                        <tr class="group hover:bg-white/5 transition">
                            <td class="py-4">
                                <p class="text-white font-bold text-sm"><?= esc($equity['nama_akun']) ?></p>
                                <p class="text-[10px] text-gray-500 font-mono"><?= esc($equity['kode_akun']) ?></p>
                            </td>
                            <td class="py-4 text-right font-mono text-sm text-gray-300"><?= number_format($equity['total'], 0, ',', '.') ?></td>
                            <td class="py-4 text-right font-mono text-sm text-gray-500">0</td>
                            <td class="py-4 text-right font-mono text-sm text-white font-bold"><?= number_format($equity['total'], 0, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <!-- Laba Berjalan -->
                        <tr class="group hover:bg-white/5 transition italic">
                            <td class="py-4">
                                <p class="text-accent font-bold text-sm">Laba Bersih Tahun Berjalan</p>
                                <p class="text-[10px] text-gray-500 uppercase tracking-tighter">Current Earnings</p>
                            </td>
                            <td class="py-4 text-right font-mono text-sm text-gray-500">0</td>
                            <td class="py-4 text-right font-mono text-sm text-accent"><?= number_format($labaBerjalan, 0, ',', '.') ?></td>
                            <td class="py-4 text-right font-mono text-sm text-accent font-bold"><?= number_format($labaBerjalan, 0, ',', '.') ?></td>
                        </tr>

                        <!-- Total Footer -->
                        <tr class="bg-white/5 border-t-2 border-white/10">
                            <td class="py-6 text-sm font-black text-white uppercase tracking-widest">Total Ekuitas</td>
                            <td class="py-6 text-right font-mono text-sm text-gray-300"><?= number_format($totalStart, 0, ',', '.') ?></td>
                            <td class="py-6 text-right font-mono text-sm text-accent"><?= number_format($labaBerjalan, 0, ',', '.') ?></td>
                            <td class="py-6 text-right font-mono text-lg text-white font-black"><?= number_format($totalEnd + $labaBerjalan, 0, ',', '.') ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-12 p-6 bg-blue-500/5 border border-blue-500/10 rounded-2xl">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center text-blue-500 shrink-0">
                    <i class="fas fa-info-circle"></i>
                </div>
                <div>
                    <h5 class="text-sm font-bold text-white mb-1">Informasi Perubahan Modal</h5>
                    <p class="text-xs text-gray-400 leading-relaxed">
                        Laporan ini menunjukkan bagaimana ekuitas perusahaan berubah selama periode akuntansi. Perubahan dapat berasal dari laba/rugi bersih, setoran modal tambahan, atau pengambilan pribadi (prive). Data di atas disinkronkan secara real-time dengan Jurnal Umum.
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
