<?= $this->extend('keuangan/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Biaya Operasional</h1>
        <p class="text-gray-500 text-xs mt-1">Daftar pengeluaran biaya dan beban operasional</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <div class="relative">
            <form action="<?= base_url('keuangan/biaya') ?>" method="get" class="flex gap-2">
                <input type="date" name="start_date" value="<?= $startDate ?>" class="bg-[#111] border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:border-accent">
                <input type="date" name="end_date" value="<?= $endDate ?>" class="bg-[#111] border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:border-accent">
                <button type="submit" class="px-4 py-2 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl text-white transition">
                    <i class="fas fa-filter"></i>
                </button>
            </form>
        </div>
        <button class="px-4 py-2 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center gap-2 shadow-lg shadow-accent/20">
            <i class="fas fa-plus"></i> Tambah Biaya
        </button>
    </div>
</div>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl">
        <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2">Total Biaya Periode Ini</p>
        <p class="text-2xl font-black text-white">Rp <?= number_format($totalBiaya, 0, ',', '.') ?></p>
        <div class="mt-4 flex items-center gap-2 text-[10px] font-bold text-red-400">
            <i class="fas fa-arrow-up"></i>
            <span>BEBAN OPERASIONAL</span>
        </div>
    </div>
</div>

<!-- Table -->
<div class="bg-[#111] border border-white/10 rounded-3xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/5 border-b border-white/10">
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Tanggal</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Deskripsi</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-widest">Akun Biaya</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-widest">Jumlah</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($expenses)): ?>
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic">Tidak ada data biaya pada periode ini</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($expenses as $exp): ?>
                    <tr class="group hover:bg-white/[0.02] transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-400"><?= date('d/m/Y', strtotime($exp['tanggal'])) ?></td>
                        <td class="px-6 py-4">
                            <p class="text-white text-sm font-medium"><?= esc($exp['deskripsi']) ?></p>
                            <p class="text-[10px] text-gray-600 font-mono"><?= esc($exp['no_reff']) ?></p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-gray-300 text-sm"><?= esc($exp['nama_akun']) ?></p>
                            <p class="text-[10px] text-gray-600 font-mono"><?= esc($exp['kode_akun']) ?></p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="text-white font-bold text-sm">Rp <?= number_format($exp['debit'], 0, ',', '.') ?></span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-2">
                                <button class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-gray-400 hover:bg-white/10 hover:text-white transition">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <button class="w-8 h-8 rounded-lg bg-red-500/10 flex items-center justify-center text-red-500 hover:bg-red-500/20 transition">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
