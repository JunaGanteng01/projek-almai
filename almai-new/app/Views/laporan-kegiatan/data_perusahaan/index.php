<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>
<div class="w-full pb-12">
    <div class="mb-6 flex flex-col md:flex-row justify-between md:items-center gap-4 px-6 md:px-0">
        <h1 class="text-xl md:text-2xl font-black text-white uppercase tracking-tight">Data Klien Perusahaan</h1>
        <a href="<?= base_url('laporan-kegiatan/data-perusahaan/create') ?>" class="bg-accent hover:bg-white text-black px-6 py-2 rounded-lg text-xs font-bold uppercase tracking-widest shadow transition text-center">
            <i class="fas fa-plus mr-2"></i> Tambah Perusahaan
        </a>
    </div>

    <!-- Alert Success/Error -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="mb-6 bg-accent/10 border border-accent/20 text-accent px-4 py-3 rounded-xl flex items-center gap-3">
            <i class="fas fa-check-circle"></i>
            <span class="text-sm font-bold"><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-500 px-4 py-3 rounded-xl flex items-center gap-3">
            <i class="fas fa-exclamation-circle"></i>
            <span class="text-sm font-bold"><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <!-- Table Data -->
    <div class="bg-[#111] border border-white/10 overflow-hidden text-sm rounded-2xl shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead>
                    <tr class="bg-black/50 text-accent text-center font-bold text-[10px] uppercase tracking-wider">
                        <th class="p-3 border-b border-r border-white/10 w-12">No</th>
                        <th class="p-3 border-b border-r border-white/10">Nama Perusahaan</th>
                        <th class="p-3 border-b border-r border-white/10">Nomor Izin</th>
                        <th class="p-3 border-b border-r border-white/10">Direktur Utama</th>
                        <th class="p-3 border-b border-r border-white/10">Website</th>
                        <th class="p-3 border-b border-r border-white/10">Status</th>
                        <th class="p-3 border-b border-r border-white/10">Keterangan</th>
                        <th class="p-3 border-b border-white/10 w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php if(empty($perusahaans)): ?>
                        <tr>
                            <td colspan="8" class="p-6 text-center text-gray-500 italic">Belum ada data perusahaan mitra.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach($perusahaans as $p): ?>
                        <tr class="hover:bg-white/[0.02] text-center transition-colors">
                            <td class="p-3 border-r border-white/5 text-gray-400"><?= $no++ ?></td>
                            <td class="p-3 border-r border-white/5 text-left text-white font-bold"><?= esc($p['nama_perusahaan']) ?: '-' ?></td>
                            <td class="p-3 border-r border-white/5 text-gray-400 text-xs"><?= esc($p['nomor_izin'] ?? '-') ?: '-' ?></td>
                            <td class="p-3 border-r border-white/5 text-gray-400 text-xs"><?= esc($p['direktur_utama'] ?? '-') ?: '-' ?></td>
                            <td class="p-3 border-r border-white/5 text-gray-400 text-xs"><?= esc($p['website'] ?? '-') ?: '-' ?></td>
                            <td class="p-3 border-r border-white/5">
                                <?php if(($p['status'] ?? '') === 'active'): ?>
                                    <span class="bg-accent/10 text-accent px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider">Aktif</span>
                                <?php else: ?>
                                    <span class="bg-red-500/10 text-red-500 px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider">Tidak Aktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-3 border-r border-white/5 text-gray-400 text-xs text-left max-w-[150px] truncate" title="<?= esc($p['keterangan'] ?? '') ?>"><?= esc($p['keterangan'] ?? '-') ?: '-' ?></td>
                            <td class="p-3 flex items-center justify-center gap-2">
                                <a href="<?= base_url('laporan-kegiatan/data-perusahaan/edit/'.$p['id']) ?>" class="text-blue-400 hover:text-blue-300 transition-colors" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= base_url('laporan-kegiatan/data-perusahaan/delete/'.$p['id']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data perusahaan ini?');" class="text-red-500 hover:text-red-400 transition-colors" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
