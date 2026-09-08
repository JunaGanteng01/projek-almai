<?= $this->extend('keuangan/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white uppercase tracking-tighter">Laporan SAK ETAP</h1>
        <p class="text-accent text-xs mt-1 font-bold">Standar Akuntansi Keuangan untuk Entitas Tanpa Akuntabilitas Publik</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-8 shadow-xl">
    <form action="<?= base_url('keuangan/sak-etap') ?>" method="get" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
        <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Tahun Awal</label>
            <select name="start_year" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none text-white text-sm">
                <?php
                $currentYear = date('Y');
                $selectedStart = isset($startDate) ? (is_object($startDate) ? $startDate->format('Y') : date('Y', strtotime($startDate))) : $currentYear - 1;
                for ($y = $currentYear - 5; $y <= $currentYear + 5; $y++): ?>
                    <option value="<?= $y ?>" <?= $y == $selectedStart ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Tahun Akhir</label>
            <select name="end_year" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none text-white text-sm">
                <?php
                $selectedEnd = isset($endDate) ? (is_object($endDate) ? $endDate->format('Y') : date('Y', strtotime($endDate))) : $currentYear;
                for ($y = $currentYear - 5; $y <= $currentYear + 5; $y++): ?>
                    <option value="<?= $y ?>" <?= $y == $selectedEnd ? 'selected' : '' ?>><?= $y ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <button type="submit" class="w-full py-3 bg-white/5 hover:bg-white/10 text-white border border-white/10 font-bold rounded-xl transition">
            <i class="fas fa-filter"></i> Filter Periode
        </button>
    </form>
</div>

<!-- Laporan Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
    <!-- Neraca -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 hover:border-accent/50 transition group">
        <div class="w-12 h-12 bg-emerald-500/10 text-emerald-400 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition">
            <i class="fas fa-balance-scale"></i>
        </div>
        <h3 class="text-lg font-bold text-white mb-2">Neraca</h3>
        <p class="text-xs text-gray-400 mb-4">Posisi keuangan: Aset, Kewajiban, dan Ekuitas</p>
        <div class="text-sm text-gray-300 mb-4 space-y-1">
            <div>Total Aset: <span class="text-emerald-400 font-bold"><?= number_format($neraca['total_aset'] ?? 0, 0, ',', '.') ?></span></div>
            <div>Total Liabilitas & Ekuitas: <span class="text-emerald-400 font-bold"><?= number_format($neraca['total_liabilitas_ekuitas'] ?? 0, 0, ',', '.') ?></span></div>
        </div>
        <a href="<?= base_url('keuangan/neraca?date=' . $endDate) ?>" class="inline-flex items-center gap-2 text-sm text-emerald-400 font-bold hover:text-white transition">
            Lihat Detail <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    <!-- Laba Rugi -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 hover:border-accent/50 transition group">
        <div class="w-12 h-12 bg-accent/10 text-accent rounded-xl flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition">
            <i class="fas fa-file-invoice-dollar"></i>
        </div>
        <h3 class="text-lg font-bold text-white mb-2">Laba Rugi</h3>
        <p class="text-xs text-gray-400 mb-4">Kinerja keuangan: Pendapatan dan Beban</p>
        <div class="text-sm text-gray-300 mb-4 space-y-1">
            <div>Pendapatan: <span class="text-accent font-bold"><?= number_format($labaRugi['totals']['pendapatan'] ?? 0, 0, ',', '.') ?></span></div>
            <div>Laba Bersih: <span class="text-accent font-bold"><?= number_format($labaRugi['laba_bersih'] ?? 0, 0, ',', '.') ?></span></div>
        </div>
        <a href="<?= base_url('keuangan/laba-rugi?start_date=' . $startDate . '&end_date=' . $endDate) ?>" class="inline-flex items-center gap-2 text-sm text-accent font-bold hover:text-white transition">
            Lihat Detail <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    <!-- Arus Kas -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 hover:border-accent/50 transition group">
        <div class="w-12 h-12 bg-purple-500/10 text-purple-400 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition">
            <i class="fas fa-money-bill-transfer"></i>
        </div>
        <h3 class="text-lg font-bold text-white mb-2">Arus Kas</h3>
        <p class="text-xs text-gray-400 mb-4">Penerimaan dan pengeluaran kas</p>
        <div class="text-sm text-gray-300 mb-4 space-y-1">
            <div>Akun Kas: <span class="text-purple-400 font-bold"><?= count($arusKas ?? []) ?> akun</span></div>
        </div>
        <a href="<?= base_url('keuangan/arus-kas?start_date=' . $startDate . '&end_date=' . $endDate) ?>" class="inline-flex items-center gap-2 text-sm text-purple-400 font-bold hover:text-white transition">
            Lihat Detail <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    <!-- Perubahan Ekuitas -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 hover:border-accent/50 transition group">
        <div class="w-12 h-12 bg-blue-500/10 text-blue-400 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition">
            <i class="fas fa-chart-pie"></i>
        </div>
        <h3 class="text-lg font-bold text-white mb-2">Perubahan Ekuitas</h3>
        <p class="text-xs text-gray-400 mb-4">Perubahan modal pemilik</p>
        <div class="text-sm text-gray-300 mb-4 space-y-1">
            <div>Akun Ekuitas: <span class="text-blue-400 font-bold"><?= count($perubahanEkuitas ?? []) ?> akun</span></div>
        </div>
        <a href="<?= base_url('keuangan/perubahan-modal?start_date=' . $startDate . '&end_date=' . $endDate) ?>" class="inline-flex items-center gap-2 text-sm text-blue-400 font-bold hover:text-white transition">
            Lihat Detail <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    <!-- CALK -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 hover:border-accent/50 transition group">
        <div class="w-12 h-12 bg-orange-500/10 text-orange-400 rounded-xl flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition">
            <i class="fas fa-clipboard-list"></i>
        </div>
        <h3 class="text-lg font-bold text-white mb-2">CALK</h3>
        <p class="text-xs text-gray-400 mb-4">Catatan atas Laporan Keuangan</p>
        <a href="<?= base_url('keuangan/calk?start_date=' . $startDate . '&end_date=' . $endDate) ?>" class="inline-flex items-center gap-2 text-sm text-orange-400 font-bold hover:text-white transition">
            Lihat Detail <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    <!-- Export PDF SAK ETAP -->
    <div class="bg-gradient-to-br from-accent/20 to-transparent border border-accent/30 rounded-2xl p-6 flex flex-col justify-center items-center text-center group">
        <div class="w-16 h-16 bg-accent text-black rounded-full flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition shadow-lg shadow-accent/20">
            <i class="fas fa-book"></i>
        </div>
        <h3 class="text-lg font-bold text-white mb-2">Buku SAK ETAP</h3>
        <p class="text-xs text-gray-400 mb-4">Export semua laporan dalam satu PDF</p>
        <a href="<?= base_url('keuangan/sak-etap/export-pdf?start_date=' . $startDate . '&end_date=' . $endDate) ?>" target="_blank" class="w-full py-2 bg-accent text-black font-bold rounded-xl hover:bg-white transition shadow-lg text-center inline-block">
            <i class="fas fa-download"></i> Export PDF
        </a>
    </div>

    <!-- Export PDF Bappebti -->
    <div class="bg-gradient-to-br from-red-500/20 to-transparent border border-red-500/30 rounded-2xl p-6 flex flex-col justify-center items-center text-center group">
        <div class="w-16 h-16 bg-red-500 text-white rounded-full flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition shadow-lg shadow-red-500/20">
            <i class="fas fa-file-pdf"></i>
        </div>
        <h3 class="text-lg font-bold text-white mb-2">Laporan KAP Bappebti</h3>
        <p class="text-xs text-gray-400 mb-4">Lengkap dengan Analisis Z-Score & Komparatif</p>
        <a href="<?= base_url('keuangan/bappebti/generate?start_date=' . $startDate . '&end_date=' . $endDate) ?>" target="_blank" class="w-full py-2 bg-red-600 text-white font-bold rounded-xl hover:bg-red-500 transition shadow-lg text-center inline-block">
            <i class="fas fa-download"></i> Download PDF Bappebti
        </a>
    </div>

    <!-- Export PDF Bank Indonesia -->
    <div class="bg-gradient-to-br from-blue-500/20 to-transparent border border-blue-500/30 rounded-2xl p-6 flex flex-col justify-center items-center text-center group">
        <div class="w-16 h-16 bg-blue-500 text-white rounded-full flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition shadow-lg shadow-blue-500/20">
            <i class="fas fa-landmark"></i>
        </div>
        <h3 class="text-lg font-bold text-white mb-2">Laporan Bank Indonesia</h3>
        <p class="text-xs text-gray-400 mb-4">Format standar pelaporan Bank Indonesia (BI)</p>
        <a href="<?= base_url('keuangan/bank-indonesia/generate?start_date=' . $startDate . '&end_date=' . $endDate) ?>" target="_blank" class="w-full py-2 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-500 transition shadow-lg text-center inline-block">
            <i class="fas fa-download"></i> Download PDF BI
        </a>
    </div>
</div>

<!-- Detail Neraca -->
<?php if (!empty($neraca['grouped'])): ?>
<div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-8 shadow-xl">
    <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
        <i class="fas fa-balance-scale text-emerald-400"></i> Neraca - Ringkasan
    </h2>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Aset -->
        <div>
            <h3 class="text-lg font-bold text-white mb-4 border-b border-white/10 pb-2">ASET</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Aset Lancar</span>
                    <span class="text-white font-bold"><?= number_format($neraca['totals']['aset_lancar'] ?? 0, 0, ',', '.') ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Aset Tidak Lancar</span>
                    <span class="text-white font-bold"><?= number_format($neraca['totals']['aset_tidak_lancar'] ?? 0, 0, ',', '.') ?></span>
                </div>
                <div class="flex justify-between border-t border-white/10 pt-2 mt-2">
                    <span class="text-white font-bold">Total Aset</span>
                    <span class="text-emerald-400 font-bold text-lg"><?= number_format($neraca['total_aset'] ?? 0, 0, ',', '.') ?></span>
                </div>
            </div>
        </div>

        <!-- Liabilitas & Ekuitas -->
        <div>
            <h3 class="text-lg font-bold text-white mb-4 border-b border-white/10 pb-2">LIABILITAS & EKUITAS</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Liabilitas Jangka Pendek</span>
                    <span class="text-white font-bold"><?= number_format($neraca['totals']['liabilitas_jangka_pendek'] ?? 0, 0, ',', '.') ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Liabilitas Jangka Panjang</span>
                    <span class="text-white font-bold"><?= number_format($neraca['totals']['liabilitas_jangka_panjang'] ?? 0, 0, ',', '.') ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Ekuitas</span>
                    <span class="text-white font-bold"><?= number_format($neraca['totals']['ekuitas'] ?? 0, 0, ',', '.') ?></span>
                </div>
                <div class="flex justify-between border-t border-white/10 pt-2 mt-2">
                    <span class="text-white font-bold">Total Liabilitas & Ekuitas</span>
                    <span class="text-emerald-400 font-bold text-lg"><?= number_format($neraca['total_liabilitas_ekuitas'] ?? 0, 0, ',', '.') ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Detail Laba Rugi -->
<?php if (!empty($labaRugi['totals'])): ?>
<div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-8 shadow-xl">
    <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
        <i class="fas fa-file-invoice-dollar text-accent"></i> Laba Rugi - Ringkasan
    </h2>
    
    <div class="space-y-3 text-sm">
        <div class="flex justify-between">
            <span class="text-gray-400">Pendapatan</span>
            <span class="text-white font-bold"><?= number_format($labaRugi['totals']['pendapatan'] ?? 0, 0, ',', '.') ?></span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-400">Harga Pokok Penjualan</span>
            <span class="text-white font-bold"><?= number_format($labaRugi['totals']['hpp'] ?? 0, 0, ',', '.') ?></span>
        </div>
        <div class="flex justify-between border-t border-white/10 pt-2">
            <span class="text-gray-400">Laba Kotor</span>
            <span class="text-white font-bold"><?= number_format($labaRugi['laba_kotor'] ?? 0, 0, ',', '.') ?></span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-400">Beban Operasional</span>
            <span class="text-white font-bold"><?= number_format($labaRugi['totals']['beban'] ?? 0, 0, ',', '.') ?></span>
        </div>
        <div class="flex justify-between border-t border-white/10 pt-2">
            <span class="text-gray-400">Laba Operasional</span>
            <span class="text-white font-bold"><?= number_format($labaRugi['laba_operasional'] ?? 0, 0, ',', '.') ?></span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-400">Pendapatan Lain-lain</span>
            <span class="text-white font-bold"><?= number_format($labaRugi['totals']['pendapatan_lain'] ?? 0, 0, ',', '.') ?></span>
        </div>
        <div class="flex justify-between">
            <span class="text-gray-400">Beban Lain-lain</span>
            <span class="text-white font-bold"><?= number_format($labaRugi['totals']['beban_lain'] ?? 0, 0, ',', '.') ?></span>
        </div>
        <div class="flex justify-between border-t border-white/10 pt-2 mt-2">
            <span class="text-white font-bold">Laba Bersih</span>
            <span class="text-accent font-bold text-lg"><?= number_format($labaRugi['laba_bersih'] ?? 0, 0, ',', '.') ?></span>
        </div>
    </div>
</div>
<?php endif; ?>

<?= $this->endSection() ?>
