<?= $this->extend('keuangan/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6 print:hidden">
    <div>
        <h1 class="text-2xl font-bold text-white">Buku Besar Detail</h1>
        <p class="text-gray-500 text-xs mt-1">Rincian mutasi transaksi detail per rekening perkiraan</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <button onclick="toggleFilter()" class="px-4 py-2 bg-white/5 text-gray-400 font-bold rounded-xl hover:bg-white/10 transition border border-white/10 flex items-center gap-2">
            <i class="fas fa-search"></i> Cari Akun & Filter
        </button>
        <?php if ($selectedAkun): ?>
        <button onclick="window.print()" class="px-4 py-2 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center gap-2 shadow-lg shadow-accent/20">
            <i class="fas fa-print"></i> Cetak Buku Besar
        </button>
        <?php endif; ?>
    </div>
</div>

<!-- Filters & Search Form -->
<div id="filterSection" class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-8 shadow-xl <?= !$selectedAkun ? '' : 'hidden' ?> print:hidden">
    <form action="<?= base_url('keuangan/buku-besar') ?>" method="get" class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
        <div class="md:col-span-2">
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Pilih Rekening Akun (COA)</label>
            <select name="akun_id" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none text-white text-sm" required>
                <option value="">-- Pilih Akun --</option>
                <?php foreach ($akunList as $ak): ?>
                    <option value="<?= $ak['id'] ?>" <?= $akunId == $ak['id'] ? 'selected' : '' ?>>
                        <?= esc($ak['kode_akun']) ?> - <?= esc($ak['nama_akun']) ?> (<?= esc($ak['kategori']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Mulai Tanggal</label>
            <input type="date" name="start_date" value="<?= esc($startDate) ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none text-white text-sm">
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Sampai Tanggal</label>
            <input type="date" name="end_date" value="<?= esc($endDate) ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none text-white text-sm">
        </div>
        <div class="md:col-span-4 flex justify-end">
            <button type="submit" class="px-8 py-3 bg-accent hover:bg-white text-black font-bold rounded-xl transition shadow-lg shadow-accent/20">
                Cari & Tampilkan Mutasi
            </button>
        </div>
    </form>
</div>

<?php if ($selectedAkun): 
    $kategori = strtolower($selectedAkun['kategori']);
    $isDebitNormal = in_array($kategori, ['kas & bank', 'akun piutang', 'persediaan', 'aktiva lancar lainya', 'aktiva tetap', 'aktiva lainya', 'harga pokok penjualan', 'beban', 'beban lainya']);
    if ($kategori === 'depresiasi & amortisasi') {
        $isDebitNormal = false;
    }
?>
    <!-- Report Content Card -->
    <div class="bg-[#111] border border-white/10 rounded-3xl overflow-hidden shadow-2xl print:bg-white print:text-black print:rounded-none print:border-none print:shadow-none animate-in fade-in slide-in-from-bottom-4">
        
        <!-- Report Header -->
        <div class="p-8 md:p-12 border-b border-white/5 bg-gradient-to-br from-white/5 to-transparent print:bg-none print:border-gray-200">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-accent/10 rounded-2xl flex items-center justify-center border border-accent/20 print:hidden">
                        <i class="fas fa-book-open text-accent text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-white uppercase tracking-tighter print:text-black">PT ALMA INDONESIA RAYA</h2>
                        <p class="text-accent font-bold text-sm uppercase tracking-widest">
                            Buku Besar: <?= esc($selectedAkun['kode_akun']) ?> – <?= esc($selectedAkun['nama_akun']) ?>
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Periode Mutasi</p>
                    <p class="text-lg font-bold text-white print:text-black">
                        <?= date('d M Y', strtotime($startDate)) ?> – <?= date('d M Y', strtotime($endDate)) ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Report Body -->
        <div class="p-8 md:p-12">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-white/10 print:border-black">
                            <th class="py-4 text-xs font-black text-gray-400 uppercase tracking-widest print:text-black" style="width: 12%;">Tanggal</th>
                            <th class="py-4 text-xs font-black text-gray-400 uppercase tracking-widest print:text-black" style="width: 15%;">No. Reff</th>
                            <th class="py-4 text-xs font-black text-gray-400 uppercase tracking-widest print:text-black" style="width: 28%;">Keterangan / Deskripsi</th>
                            <th class="py-4 text-xs font-black text-gray-400 uppercase tracking-widest text-right print:text-black" style="width: 15%;">Debit (Rp)</th>
                            <th class="py-4 text-xs font-black text-gray-400 uppercase tracking-widest text-right print:text-black" style="width: 15%;">Kredit (Rp)</th>
                            <th class="py-4 text-xs font-black text-gray-400 uppercase tracking-widest text-right print:text-black" style="width: 15%;">Saldo (Rp)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 print:divide-gray-200">
                        
                        <!-- Opening Balance Row -->
                        <tr class="bg-white/[0.02] print:bg-gray-50">
                            <td class="py-4 text-xs text-gray-500 font-mono"><?= date('d/m/Y', strtotime($startDate)) ?></td>
                            <td class="py-4 text-xs text-gray-500 font-mono">-</td>
                            <td class="py-4 text-sm text-gray-300 print:text-black font-bold uppercase tracking-wider text-xs">Saldo Awal Periode</td>
                            <td class="py-4 text-right text-gray-500 font-mono">-</td>
                            <td class="py-4 text-right text-gray-500 font-mono">-</td>
                            <td class="py-4 text-sm font-mono text-right text-white print:text-black font-bold">
                                <?= number_format($openingBalance, 0, ',', '.') ?>
                            </td>
                        </tr>

                        <?php 
                        $runningBalance = $openingBalance;
                        $totalDebit = 0;
                        $totalKredit = 0;

                        foreach ($mutations as $row): 
                            $deb = (float)$row['debit'];
                            $kred = (float)$row['kredit'];
                            
                            $totalDebit += $deb;
                            $totalKredit += $kred;

                            if ($isDebitNormal) {
                                $runningBalance += ($deb - $kred);
                            } else {
                                $runningBalance += ($kred - $deb);
                            }
                        ?>
                            <tr class="hover:bg-white/5 transition print:hover:bg-transparent">
                                <td class="py-4 text-xs text-gray-400 font-mono"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                                <td class="py-4 text-xs text-gray-400 font-mono font-bold"><?= esc($row['no_reff']) ?></td>
                                <td class="py-4 text-sm text-gray-300 print:text-black"><?= esc($row['deskripsi']) ?></td>
                                <td class="py-4 text-sm font-mono text-right text-white print:text-black">
                                    <?= $deb > 0 ? number_format($deb, 0, ',', '.') : '-' ?>
                                </td>
                                <td class="py-4 text-sm font-mono text-right text-white print:text-black">
                                    <?= $kred > 0 ? number_format($kred, 0, ',', '.') : '-' ?>
                                </td>
                                <td class="py-4 text-sm font-mono text-right text-white print:text-black font-bold">
                                    <?= number_format($runningBalance, 0, ',', '.') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                    <tfoot>
                        <!-- Totals of Period Mutations -->
                        <tr class="border-t border-white/10 font-bold bg-white/5 print:bg-gray-100">
                            <td colspan="3" class="py-4 px-4 text-xs text-gray-400 uppercase tracking-wider">Total Pergerakan / Mutasi Periode</td>
                            <td class="py-4 text-right font-mono text-sm text-white print:text-black border-bottom">
                                <?= number_format($totalDebit, 0, ',', '.') ?>
                            </td>
                            <td class="py-4 text-right font-mono text-sm text-white print:text-black border-bottom">
                                <?= number_format($totalKredit, 0, ',', '.') ?>
                            </td>
                            <td class="py-4 text-right font-mono text-sm text-gray-400">-</td>
                        </tr>
                        <!-- Final Balanced Closing -->
                        <tr class="border-t-2 border-white/10 font-black bg-white/[0.08] print:bg-gray-200">
                            <td colspan="3" class="py-6 px-4 text-sm text-white print:text-black uppercase tracking-widest">Saldo Akhir Kumulatif (Closing Balance)</td>
                            <td class="py-6 text-right font-mono text-gray-500">-</td>
                            <td class="py-6 text-right font-mono text-gray-500">-</td>
                            <td class="py-6 text-right font-mono text-xl text-accent print:text-black border-double-bottom">
                                <?= number_format($runningBalance, 0, ',', '.') ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="text-center pt-12 print:text-gray-400">
                <p class="text-[10px] font-bold text-gray-700 uppercase tracking-widest mb-2">Laporan ini dibuat secara otomatis oleh sistem</p>
                <p class="text-[9px] text-gray-800 font-mono">ALMA WPA Accounting Module v2.0 • <?= date('d/m/Y H:i') ?></p>
            </div>
        </div>
    </div>
<?php elseif ($akunId): ?>
    <div class="text-center p-12 bg-[#111] border border-white/10 rounded-3xl">
        <i class="fas fa-triangle-exclamation text-red-400 text-3xl mb-4"></i>
        <h3 class="text-white font-bold text-lg mb-2">Akun Tidak Ditemukan</h3>
        <p class="text-gray-500 text-sm">Rekening perkiraan yang Anda cari tidak tersedia atau sudah dihapus.</p>
    </div>
<?php else: ?>
    <!-- Visual Placeholder inviting to search -->
    <div class="text-center p-16 bg-[#111] border border-white/10 rounded-3xl flex flex-col items-center justify-center">
        <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mb-6 border border-white/10">
            <i class="fas fa-book-open text-accent text-3xl"></i>
        </div>
        <h3 class="text-white font-bold text-lg mb-2">Pencarian Buku Besar Detail</h3>
        <p class="text-gray-500 text-sm max-w-md mx-auto leading-relaxed mb-6">
            Silakan pilih salah satu rekening akun dari bagan akun (COA) untuk menganalisis dan meninjau mutasi jurnal secara mendetail dalam rentang periode tertentu.
        </p>
        <button onclick="toggleFilter()" class="px-6 py-3 bg-accent hover:bg-white text-black font-bold rounded-xl transition">
            Buka Form Pencarian
        </button>
    </div>
<?php endif; ?>

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
