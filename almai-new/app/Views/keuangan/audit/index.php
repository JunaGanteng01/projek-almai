<?= $this->extend('keuangan/layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-8">
    <h1 class="text-3xl font-bold text-white uppercase tracking-tighter flex items-center gap-3">
        <i class="fas fa-search-dollar text-yellow-400"></i> Audit Keuangan
    </h1>
    <p class="text-gray-400 mt-2">Deteksi otomatis anomali, kesalahan input, dan potensi duplikasi pada data keuangan.</p>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Unbalanced Journals -->
    <div class="bg-[#111] border border-<?= !empty($unbalanced) ? 'red' : 'green' ?>-500/30 rounded-2xl p-6 shadow-xl relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-<?= !empty($unbalanced) ? 'red' : 'green' ?>-500/10 rounded-full blur-2xl group-hover:bg-<?= !empty($unbalanced) ? 'red' : 'green' ?>-500/20 transition-all"></div>
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Jurnal Tidak Balance</p>
                <h3 class="text-3xl font-black text-white"><?= count($unbalanced) ?></h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-<?= !empty($unbalanced) ? 'red' : 'green' ?>-500/20 flex items-center justify-center text-<?= !empty($unbalanced) ? 'red' : 'green' ?>-400">
                <i class="fas <?= !empty($unbalanced) ? 'fa-balance-scale-right' : 'fa-balance-scale' ?> text-xl"></i>
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-4">Debit != Kredit</p>
    </div>

    <!-- Negative Cash -->
    <div class="bg-[#111] border border-<?= !empty($negativeCash) ? 'red' : 'green' ?>-500/30 rounded-2xl p-6 shadow-xl relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-<?= !empty($negativeCash) ? 'red' : 'green' ?>-500/10 rounded-full blur-2xl group-hover:bg-<?= !empty($negativeCash) ? 'red' : 'green' ?>-500/20 transition-all"></div>
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Kas Minus</p>
                <h3 class="text-3xl font-black text-white"><?= count($negativeCash) ?></h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-<?= !empty($negativeCash) ? 'red' : 'green' ?>-500/20 flex items-center justify-center text-<?= !empty($negativeCash) ? 'red' : 'green' ?>-400">
                <i class="fas fa-wallet text-xl"></i>
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-4">Saldo Kas < 0</p>
    </div>

    <!-- Zero Nominal -->
    <div class="bg-[#111] border border-<?= !empty($zeroNominal) ? 'yellow' : 'green' ?>-500/30 rounded-2xl p-6 shadow-xl relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-<?= !empty($zeroNominal) ? 'yellow' : 'green' ?>-500/10 rounded-full blur-2xl group-hover:bg-<?= !empty($zeroNominal) ? 'yellow' : 'green' ?>-500/20 transition-all"></div>
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Transaksi Nol</p>
                <h3 class="text-3xl font-black text-white"><?= count($zeroNominal) ?></h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-<?= !empty($zeroNominal) ? 'yellow' : 'green' ?>-500/20 flex items-center justify-center text-<?= !empty($zeroNominal) ? 'yellow' : 'green' ?>-400">
                <i class="fas fa-ban text-xl"></i>
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-4">Nominal = 0</p>
    </div>

    <!-- Orphan Journals -->
    <div class="bg-[#111] border border-<?= !empty($orphan) ? 'orange' : 'green' ?>-500/30 rounded-2xl p-6 shadow-xl relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-<?= !empty($orphan) ? 'orange' : 'green' ?>-500/10 rounded-full blur-2xl group-hover:bg-<?= !empty($orphan) ? 'orange' : 'green' ?>-500/20 transition-all"></div>
        <div class="flex justify-between items-start">
            <div>
                <p class="text-gray-400 text-xs font-bold uppercase tracking-wider mb-1">Akun Terhapus</p>
                <h3 class="text-3xl font-black text-white"><?= count($orphan) ?></h3>
            </div>
            <div class="w-12 h-12 rounded-xl bg-<?= !empty($orphan) ? 'orange' : 'green' ?>-500/20 flex items-center justify-center text-<?= !empty($orphan) ? 'orange' : 'green' ?>-400">
                <i class="fas fa-ghost text-xl"></i>
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-4">Jurnal dgn Akun Invalid</p>
    </div>
</div>

<!-- Detailed Tables -->
<div class="space-y-8">

    <!-- 1. Unbalanced Journals -->
    <?php if (!empty($unbalanced)): ?>
    <div class="bg-[#111] border border-red-500/30 rounded-2xl overflow-hidden shadow-xl">
        <div class="bg-red-500/10 px-6 py-4 border-b border-red-500/20 flex justify-between items-center">
            <h3 class="font-bold text-white"><i class="fas fa-balance-scale-right text-red-400 mr-2"></i> Jurnal Tidak Balance</h3>
            <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">Kritis</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-white/5 border-b border-white/10">
                    <tr>
                        <th class="px-6 py-3 text-left font-bold text-gray-400 uppercase tracking-wider">No Reff</th>
                        <th class="px-6 py-3 text-left font-bold text-gray-400 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-right font-bold text-gray-400 uppercase tracking-wider">Total Debit</th>
                        <th class="px-6 py-3 text-right font-bold text-gray-400 uppercase tracking-wider">Total Kredit</th>
                        <th class="px-6 py-3 text-right font-bold text-gray-400 uppercase tracking-wider">Selisih</th>
                        <th class="px-6 py-3 text-center font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php foreach ($unbalanced as $u): ?>
                    <tr class="hover:bg-white/5 transition">
                        <td class="px-6 py-4 text-white font-mono"><?= esc($u['no_reff']) ?></td>
                        <td class="px-6 py-4 text-gray-300"><?= date('d M Y', strtotime($u['tanggal'])) ?></td>
                        <td class="px-6 py-4 text-right font-mono text-green-400">Rp <?= number_format($u['total_debit'], 0, ',', '.') ?></td>
                        <td class="px-6 py-4 text-right font-mono text-red-400">Rp <?= number_format($u['total_kredit'], 0, ',', '.') ?></td>
                        <td class="px-6 py-4 text-right font-mono font-bold text-yellow-400">Rp <?= number_format(abs($u['total_debit'] - $u['total_kredit']), 0, ',', '.') ?></td>
                        <td class="px-6 py-4 text-center">
                            <a href="<?= base_url('keuangan/jurnal-umum?search=' . $u['no_reff']) ?>" class="text-accent hover:text-white transition">Periksa <i class="fas fa-arrow-right ml-1"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- 2. Negative Cash -->
    <?php if (!empty($negativeCash)): ?>
    <div class="bg-[#111] border border-red-500/30 rounded-2xl overflow-hidden shadow-xl">
        <div class="bg-red-500/10 px-6 py-4 border-b border-red-500/20 flex justify-between items-center">
            <h3 class="font-bold text-white"><i class="fas fa-wallet text-red-400 mr-2"></i> Akun Kas Minus</h3>
            <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">Kritis</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-white/5 border-b border-white/10">
                    <tr>
                        <th class="px-6 py-3 text-left font-bold text-gray-400 uppercase tracking-wider">Kode Akun</th>
                        <th class="px-6 py-3 text-left font-bold text-gray-400 uppercase tracking-wider">Nama Akun</th>
                        <th class="px-6 py-3 text-right font-bold text-gray-400 uppercase tracking-wider">Saldo Saat Ini</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php foreach ($negativeCash as $nc): ?>
                    <tr class="hover:bg-white/5 transition">
                        <td class="px-6 py-4 text-gray-400 font-mono"><?= esc($nc['kode_akun']) ?></td>
                        <td class="px-6 py-4 text-white font-bold"><?= esc($nc['nama_akun']) ?></td>
                        <td class="px-6 py-4 text-right font-mono text-red-400 font-bold">Rp <?= number_format($nc['saldo'], 0, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- 3. Orphan Journals -->
    <?php if (!empty($orphan)): ?>
    <div class="bg-[#111] border border-orange-500/30 rounded-2xl overflow-hidden shadow-xl">
        <div class="bg-orange-500/10 px-6 py-4 border-b border-orange-500/20 flex justify-between items-center">
            <h3 class="font-bold text-white"><i class="fas fa-ghost text-orange-400 mr-2"></i> Jurnal Yatim (Akun Terhapus)</h3>
            <span class="bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded">Peringatan</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-white/5 border-b border-white/10">
                    <tr>
                        <th class="px-6 py-3 text-left font-bold text-gray-400 uppercase tracking-wider">No Reff</th>
                        <th class="px-6 py-3 text-left font-bold text-gray-400 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left font-bold text-gray-400 uppercase tracking-wider">Keterangan</th>
                        <th class="px-6 py-3 text-center font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php foreach ($orphan as $o): ?>
                    <tr class="hover:bg-white/5 transition">
                        <td class="px-6 py-4 text-white font-mono"><?= esc($o['no_reff']) ?></td>
                        <td class="px-6 py-4 text-gray-300"><?= date('d M Y', strtotime($o['tanggal'])) ?></td>
                        <td class="px-6 py-4 text-gray-400 text-xs"><?= esc($o['deskripsi']) ?></td>
                        <td class="px-6 py-4 text-center">
                            <a href="<?= base_url('keuangan/jurnal-umum?search=' . $o['no_reff']) ?>" class="text-accent hover:text-white transition">Periksa <i class="fas fa-arrow-right ml-1"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- 4. Zero Nominal -->
    <?php if (!empty($zeroNominal)): ?>
    <div class="bg-[#111] border border-yellow-500/30 rounded-2xl overflow-hidden shadow-xl">
        <div class="bg-yellow-500/10 px-6 py-4 border-b border-yellow-500/20 flex justify-between items-center">
            <h3 class="font-bold text-white"><i class="fas fa-ban text-yellow-400 mr-2"></i> Transaksi Nol</h3>
            <span class="bg-yellow-500 text-black text-xs font-bold px-2 py-1 rounded">Info</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-white/5 border-b border-white/10">
                    <tr>
                        <th class="px-6 py-3 text-left font-bold text-gray-400 uppercase tracking-wider">No Reff</th>
                        <th class="px-6 py-3 text-left font-bold text-gray-400 uppercase tracking-wider">Akun</th>
                        <th class="px-6 py-3 text-left font-bold text-gray-400 uppercase tracking-wider">Keterangan</th>
                        <th class="px-6 py-3 text-center font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php foreach ($zeroNominal as $z): ?>
                    <tr class="hover:bg-white/5 transition">
                        <td class="px-6 py-4 text-white font-mono"><?= esc($z['no_reff']) ?></td>
                        <td class="px-6 py-4 text-gray-300 font-bold"><?= esc($z['nama_akun']) ?></td>
                        <td class="px-6 py-4 text-gray-400 text-xs"><?= esc($z['deskripsi']) ?></td>
                        <td class="px-6 py-4 text-center">
                            <a href="<?= base_url('keuangan/jurnal-umum?search=' . $z['no_reff']) ?>" class="text-accent hover:text-white transition">Periksa <i class="fas fa-arrow-right ml-1"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <?php if (empty($unbalanced) && empty($negativeCash) && empty($orphan) && empty($zeroNominal)): ?>
        <div class="bg-green-500/10 border border-green-500/30 rounded-2xl p-12 text-center shadow-xl">
            <i class="fas fa-shield-check text-6xl text-green-400 mb-6"></i>
            <h2 class="text-2xl font-black text-white mb-2 uppercase">Luar Biasa!</h2>
            <p class="text-gray-400 text-lg">Tidak ada anomali atau masalah yang terdeteksi dalam pembukuan Anda.<br>Data keuangan Anda bersih dan konsisten.</p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
