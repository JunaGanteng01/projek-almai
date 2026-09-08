<?= $this->extend('keuangan/layouts/main') ?>

<?= $this->section('content') ?>
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white uppercase tracking-tighter">
            <i class="fas fa-book text-blue-400 mr-2"></i>Kelola Duplikat Jurnal Umum
        </h1>
        <p class="text-accent text-xs mt-1 font-bold">Review dan hapus jurnal duplikat (Khusus Saldo Awal)</p>
    </div>
    <div class="flex flex-wrap gap-3">
        <a href="<?= base_url('keuangan/jurnal-umum') ?>" class="px-4 py-2.5 bg-gray-500/20 text-gray-400 border border-gray-500/50 font-bold rounded-xl hover:bg-gray-500/30 transition flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
        <form action="<?= base_url('keuangan/jurnal-umum/hapus-duplikat') ?>" method="post" class="inline">
            <?= csrf_field() ?>
            <button type="button" onclick="if(confirm('Yakin ingin menghapus SEMUA entri duplikat Saldo Awal secara otomatis? (Menyisakan 1 tiap kelompok)')) { this.closest('form').submit(); }" class="px-5 py-2.5 bg-red-500/20 text-red-400 border border-red-500/50 font-bold rounded-xl hover:bg-red-500/30 transition flex items-center">
                <i class="fas fa-magic mr-2"></i> Bersihkan Semua Otomatis
            </button>
        </form>
    </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div class="bg-green-500/10 border border-green-500/30 text-green-400 p-4 rounded-xl mb-6">
        <i class="fas fa-check-circle mr-2"></i> <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if(session()->getFlashdata('error')): ?>
    <div class="bg-red-500/10 border border-red-500/30 text-red-400 p-4 rounded-xl mb-6">
        <i class="fas fa-exclamation-circle mr-2"></i> <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<?php if (empty($duplicateGroups)): ?>
    <div class="bg-[#111] border border-white/10 rounded-2xl p-8 text-center shadow-xl">
        <i class="fas fa-check-circle text-6xl text-green-500/50 mb-4 block"></i>
        <h2 class="text-xl font-bold text-white mb-2">Tidak Ada Duplikat Saldo Awal</h2>
        <p class="text-gray-400">Semua data jurnal Saldo Awal terlihat unik dan bersih.</p>
        <a href="<?= base_url('keuangan/jurnal-umum') ?>" class="inline-block mt-6 px-6 py-2.5 bg-accent/20 text-accent font-bold rounded-xl hover:bg-accent/30 transition border border-accent/50">Kembali ke Jurnal Umum</a>
    </div>
<?php else: ?>
    <div class="space-y-6">
        <?php $groupNo = 1; foreach ($duplicateGroups as $key => $group): ?>
            <div class="bg-[#111] border border-red-500/30 rounded-2xl overflow-hidden shadow-xl">
                <div class="bg-red-500/10 px-6 py-4 border-b border-red-500/20 flex justify-between items-center">
                    <h3 class="font-bold text-white">Kelompok Duplikat #<?= $groupNo++ ?></h3>
                    <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                        <?= count($group) ?> Entri Identik
                    </span>
                </div>
                <div class="p-0">
                    <table class="w-full text-sm">
                        <thead class="bg-white/5 border-b border-white/10">
                            <tr>
                                <th class="px-4 py-3 text-left font-bold text-gray-400 uppercase tracking-wider">Tanggal</th>
                                <th class="px-4 py-3 text-left font-bold text-gray-400 uppercase tracking-wider">No Reff</th>
                                <th class="px-4 py-3 text-left font-bold text-gray-400 uppercase tracking-wider">Akun</th>
                                <th class="px-4 py-3 text-right font-bold text-gray-400 uppercase tracking-wider">Debit</th>
                                <th class="px-4 py-3 text-right font-bold text-gray-400 uppercase tracking-wider">Kredit</th>
                                <th class="px-4 py-3 text-left font-bold text-gray-400 uppercase tracking-wider">Keterangan</th>
                                <th class="px-4 py-3 text-center font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <?php $itemNo = 0; foreach ($group as $item): ?>
                                <tr class="hover:bg-white/5 transition <?= $itemNo == 0 ? 'bg-green-500/5' : '' ?>">
                                    <td class="px-4 py-3 text-gray-300">
                                        <?= date('d M Y', strtotime($item['tanggal'])) ?>
                                        <?php if ($itemNo == 0): ?>
                                            <div class="text-[10px] text-green-400 font-bold mt-1 uppercase">★ Data Asli (Disarankan Disimpan)</div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-3 text-white font-mono text-xs"><?= esc($item['no_reff']) ?></td>
                                    <td class="px-4 py-3 text-white font-bold"><?= esc($item['kode_akun']) ?> - <?= esc($item['nama_akun']) ?></td>
                                    <td class="px-4 py-3 text-right font-mono text-green-400"><?= number_format($item['debit'], 0, ',', '.') ?></td>
                                    <td class="px-4 py-3 text-right font-mono text-red-400"><?= number_format($item['kredit'], 0, ',', '.') ?></td>
                                    <td class="px-4 py-3 text-gray-400 text-xs"><?= esc($item['deskripsi']) ?></td>
                                    <td class="px-4 py-3 text-center">
                                        <form action="<?= base_url('keuangan/jurnal-umum/hapus-duplikat-item') ?>" method="post" class="inline">
                                            <?= csrf_field() ?>
                                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                            <button type="button" onclick="if(confirm('Hapus entri jurnal ini secara permanen?')) { this.closest('form').submit(); }" class="px-3 py-1.5 bg-red-500/20 text-red-400 border border-red-500/50 rounded hover:bg-red-500/30 transition text-xs font-bold" title="Hapus">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php $itemNo++; endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>
