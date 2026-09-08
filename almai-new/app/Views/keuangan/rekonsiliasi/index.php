<?= $this->extend('keuangan/layouts/main') ?>
<?= $this->section('content') ?>

<div class="mb-6">
    <h1 class="text-2xl font-bold">Rekonsiliasi Bank</h1>
    <p class="text-gray-500 text-xs mt-1">Cocokkan saldo rekening bank dengan mutasi buku kas di jurnal</p>
</div>

<form method="get" class="bg-[#111] border border-white/10 rounded-2xl p-4 mb-6 flex flex-wrap gap-3 items-end">
    <div>
        <label class="text-xs text-gray-500 block mb-1">Rekening Kas/Bank</label>
        <select name="akun_id" class="bg-black border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white min-w-[200px]">
            <?php foreach ($kasAccounts as $a): ?>
                <option value="<?= $a['id'] ?>" <?= $akunId == $a['id'] ? 'selected' : '' ?>><?= esc($a['kode_akun'] . ' - ' . $a['nama_akun']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label class="text-xs text-gray-500 block mb-1">Bulan</label>
        <select name="bulan" class="bg-black border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white">
            <?php for ($m = 1; $m <= 12; $m++): ?>
                <option value="<?= $m ?>" <?= $bulan == $m ? 'selected' : '' ?>><?= date('F', mktime(0,0,0,$m,1)) ?></option>
            <?php endfor; ?>
        </select>
    </div>
    <div>
        <label class="text-xs text-gray-500 block mb-1">Tahun</label>
        <input type="number" name="tahun" value="<?= $tahun ?>" class="bg-black border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white w-24">
    </div>
    <div>
        <label class="text-xs text-gray-500 block mb-1">Saldo Bank (mutasi)</label>
        <input type="number" step="0.01" name="saldo_bank" value="<?= $saldoBank ?>" class="bg-black border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white w-40">
    </div>
    <button type="submit" class="px-6 py-2.5 bg-accent text-black font-bold rounded-xl">Tampilkan</button>
</form>

<div class="grid md:grid-cols-3 gap-4 mb-6">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-4">
        <p class="text-xs text-gray-500 uppercase">Saldo Buku (jurnal)</p>
        <p class="text-xl font-bold text-white mt-1">Rp <?= number_format($saldoBuku, 0, ',', '.') ?></p>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-2xl p-4">
        <p class="text-xs text-gray-500 uppercase">Saldo Bank</p>
        <p class="text-xl font-bold text-blue-400 mt-1">Rp <?= number_format($saldoBank, 0, ',', '.') ?></p>
    </div>
    <div class="bg-[#111] border <?= abs($selisih) < 0.01 ? 'border-accent/30' : 'border-red-500/30' ?> rounded-2xl p-4">
        <p class="text-xs text-gray-500 uppercase">Selisih</p>
        <p class="text-xl font-bold <?= abs($selisih) < 0.01 ? 'text-accent' : 'text-red-400' ?> mt-1">Rp <?= number_format($selisih, 0, ',', '.') ?></p>
        <?php if (abs($selisih) < 0.01 && $saldoBank > 0): ?>
            <p class="text-xs text-accent mt-1"><i class="fas fa-check-circle"></i> Balance</p>
        <?php endif; ?>
    </div>
</div>

<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
    <div class="px-4 py-3 border-b border-white/10 text-sm text-gray-400">
        Mutasi <?= esc($startDate) ?> s/d <?= esc($endDate) ?>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-black/50 text-xs text-gray-400 uppercase">
            <tr>
                <th class="px-4 py-3 text-left">Tanggal</th>
                <th class="px-4 py-3 text-left">Ref</th>
                <th class="px-4 py-3 text-left">Deskripsi</th>
                <th class="px-4 py-3 text-right">Debit</th>
                <th class="px-4 py-3 text-right">Kredit</th>
                <th class="px-4 py-3 text-right">Saldo</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
            <?php if (empty($mutasi)): ?>
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-500">Tidak ada mutasi pada periode ini</td></tr>
            <?php else: ?>
                <?php foreach ($mutasi as $row): ?>
                <tr>
                    <td class="px-4 py-2 text-gray-300"><?= esc($row['tanggal']) ?></td>
                    <td class="px-4 py-2 text-gray-500 text-xs"><?= esc($row['no_reff']) ?></td>
                    <td class="px-4 py-2 text-gray-300"><?= esc($row['deskripsi']) ?></td>
                    <td class="px-4 py-2 text-right text-green-400"><?= $row['debit'] > 0 ? number_format($row['debit'], 0, ',', '.') : '-' ?></td>
                    <td class="px-4 py-2 text-right text-red-400"><?= $row['kredit'] > 0 ? number_format($row['kredit'], 0, ',', '.') : '-' ?></td>
                    <td class="px-4 py-2 text-right font-medium text-white"><?= number_format($row['saldo_berjalan'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    
    <?php if (isset($pagerLinks) && $pagerLinks): ?>
        <div class="p-4 border-t border-white/10 bg-black/20">
            <?= $pagerLinks ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

