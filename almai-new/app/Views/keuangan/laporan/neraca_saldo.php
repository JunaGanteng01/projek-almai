<?= $this->extend('keuangan/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 print:hidden">
    <div>
        <h1 class="text-2xl font-bold text-white">Neraca Saldo</h1>
        <p class="text-gray-500 text-xs mt-1">Ringkasan saldo kumulatif seluruh akun akuntansi</p>
    </div>
    
</div>

<!-- Filters -->
<form action="<?= base_url('keuangan/neraca-saldo') ?>" method="get" class="flex flex-col md:flex-row gap-4 items-end mb-8 print:hidden">
    <div class="flex-1 md:max-w-[200px]">
        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Per Tanggal</label>
        <input type="date" name="date" value="<?= esc($endDate) ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-2.5 focus:border-accent focus:outline-none text-white text-sm">
    </div>
    <div class="flex gap-2 w-full md:w-auto">
        <button type="submit" class="flex-1 md:flex-none px-6 py-2.5 bg-accent hover:bg-white text-black font-bold rounded-xl transition shadow-lg shadow-accent/20 flex items-center justify-center">
            <i class="fas fa-filter mr-2"></i> Filter
        </button>
        <a href="<?= base_url('keuangan/neraca-saldo/export-pdf?' . http_build_query(service('request')->getGet())) ?>" target="_blank" class="flex-1 md:flex-none px-6 py-2.5 bg-red-500/20 text-red-400 border border-red-500/50 hover:bg-red-500/30 font-bold rounded-xl transition flex items-center justify-center whitespace-nowrap">
            <i class="fas fa-file-pdf mr-2"></i> Export PDF
        </a>
    </div>
</form>

<!-- Report Content Card -->
<div class="bg-[#111] border border-white/10 rounded-3xl overflow-hidden shadow-2xl print:bg-white print:text-black print:rounded-none print:border-none print:shadow-none">
    
    <!-- Report Header -->
    <div class="p-8 md:p-12 border-b border-white/5 bg-gradient-to-br from-white/5 to-transparent print:bg-none print:border-gray-200">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-accent/10 rounded-2xl flex items-center justify-center border border-accent/20 print:hidden">
                    <i class="fas fa-balance-scale text-accent text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-white uppercase tracking-tighter print:text-black">PT ALMA INDONESIA RAYA</h2>
                    <p class="text-accent font-bold text-sm uppercase tracking-widest">Neraca Saldo (Trial Balance)</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Per Tanggal</p>
                <p class="text-lg font-bold text-white print:text-black"><?= date('d F Y', strtotime($endDate)) ?></p>
            </div>
        </div>
    </div>

    <!-- Main Report Body -->
    <div class="p-8 md:p-12">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b-2 border-white/10 print:border-black">
                        <th class="py-4 text-xs font-black text-gray-400 uppercase tracking-widest print:text-black" style="width: 15%;">Kode Akun</th>
                        <th class="py-4 text-xs font-black text-gray-400 uppercase tracking-widest print:text-black" style="width: 35%;">Nama Akun</th>
                        <th class="py-4 text-xs font-black text-gray-400 uppercase tracking-widest print:text-black" style="width: 20%;">Kategori</th>
                        <th class="py-4 text-xs font-black text-gray-400 uppercase tracking-widest text-right print:text-black" style="width: 15%;">Debit (Rp)</th>
                        <th class="py-4 text-xs font-black text-gray-400 uppercase tracking-widest text-right print:text-black" style="width: 15%;">Kredit (Rp)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 print:divide-gray-200">
                    <?php 
                    $totalDebitSum = 0;
                    $totalKreditSum = 0;
                    
                    foreach ($reportData as $row): 
                        $deb = (float)($row['total_debit'] ?? 0);
                        $kred = (float)($row['total_kredit'] ?? 0);
                        
                        if ($deb == 0 && $kred == 0) {
                            continue; // Skip accounts with no balance/activity to keep report clean
                        }

                        $kategori = strtolower($row['kategori']);
                        $isDebitNormal = in_array($kategori, ['kas & bank', 'akun piutang', 'persediaan', 'aktiva lancar lainya', 'aktiva tetap', 'aktiva lainya', 'harga pokok penjualan', 'beban', 'beban lainya']);
                        if ($kategori === 'depresiasi & amortisasi') {
                            $isDebitNormal = false;
                        }

                        $debitVal = 0;
                        $kreditVal = 0;

                        if ($isDebitNormal) {
                            $net = $deb - $kred;
                            if ($net >= 0) {
                                $debitVal = $net;
                            } else {
                                $kreditVal = abs($net);
                            }
                        } else {
                            $net = $kred - $deb;
                            if ($net >= 0) {
                                $kreditVal = $net;
                            } else {
                                $debitVal = abs($net);
                            }
                        }

                        $totalDebitSum += $debitVal;
                        $totalKreditSum += $kreditVal;
                    ?>
                        <tr class="hover:bg-white/5 transition print:hover:bg-transparent">
                            <td class="py-4 font-mono text-xs text-gray-500"><?= esc($row['kode_akun']) ?></td>
                            <td class="py-4 text-sm text-gray-300 print:text-black font-bold"><?= esc($row['nama_akun']) ?></td>
                            <td class="py-4 text-xs text-gray-500 uppercase tracking-wider"><?= esc($row['kategori']) ?></td>
                            <td class="py-4 text-sm font-mono text-right text-white print:text-black">
                                <?= $debitVal > 0 ? number_format($debitVal, 0, ',', '.') : '-' ?>
                            </td>
                            <td class="py-4 text-sm font-mono text-right text-white print:text-black">
                                <?= $kreditVal > 0 ? number_format($kreditVal, 0, ',', '.') : '-' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (empty($reportData) || ($totalDebitSum == 0 && $totalKreditSum == 0)): ?>
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500 italic text-sm">
                                Tidak ada transaksi atau saldo aktif pada periode ini.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-white/10 print:border-black font-black bg-white/5 print:bg-gray-100">
                        <td colspan="3" class="py-6 px-4 text-sm text-white print:text-black uppercase tracking-wider">Total Neraca Saldo</td>
                        <td class="py-6 text-right font-mono text-lg text-accent print:text-black border-double-bottom">
                            <?= number_format($totalDebitSum, 0, ',', '.') ?>
                        </td>
                        <td class="py-6 text-right font-mono text-lg text-accent print:text-black border-double-bottom">
                            <?= number_format($totalKreditSum, 0, ',', '.') ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <?php if (abs($totalDebitSum - $totalKreditSum) < 0.05 && $totalDebitSum > 0): ?>
            <div class="mt-8 p-4 bg-emerald-950/20 border border-emerald-500/20 rounded-2xl flex items-center gap-3 text-emerald-400 text-xs print:hidden animate-pulse">
                <i class="fas fa-check-circle text-lg"></i>
                <div>
                    <span class="font-bold">STATUS BALANCED:</span> Total Debit dan Kredit seimbang sempurna. Sistem akuntansi Anda dalam keadaan sehat.
                </div>
            </div>
        <?php elseif ($totalDebitSum != $totalKreditSum): ?>
            <div class="mt-8 p-4 bg-red-950/20 border border-red-500/20 rounded-2xl flex items-center gap-3 text-red-400 text-xs print:hidden">
                <i class="fas fa-triangle-exclamation text-lg"></i>
                <div>
                    <span class="font-bold">STATUS UNBALANCED:</span> Terdapat selisih sebesar Rp <?= number_format(abs($totalDebitSum - $totalKreditSum), 0, ',', '.') ?> antara Debit dan Kredit. Harap audit entri Jurnal Umum Anda.
                </div>
            </div>
        <?php endif; ?>

        <div class="text-center pt-12 print:text-gray-400">
            <p class="text-[10px] font-bold text-gray-700 uppercase tracking-widest mb-2">Laporan ini dibuat secara otomatis oleh sistem</p>
            <p class="text-[9px] text-gray-800 font-mono">ALMA WPA Accounting Module v2.0 • <?= date('d/m/Y H:i') ?></p>
        </div>
    </div>
</div>

<script>
    function toggleFilter() {
        document.getElementById('filterSection').classList.toggle('hidden');
    }
</script>

<style>
@media print {
    body { background: white !important; color: black !important; }
    .md\:ml-64 { margin-left: 0 !important; }
    nav, aside, header, .print\:hidden { display: none !important; }
    main { padding: 0 !important; margin: 0 !important; }
    .border-double-bottom { border-bottom: 4px double #000; }
}
</style>

<?= $this->endSection() ?>
