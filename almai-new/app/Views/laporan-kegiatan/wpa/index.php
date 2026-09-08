<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>
<div class="w-full pb-12">
    <div class="mb-6 flex flex-col md:flex-row justify-between md:items-center gap-4 px-6 md:px-0">
        <h1 class="text-xl md:text-2xl font-black text-white uppercase tracking-tight">Data WPA</h1>
        <a href="<?= base_url('laporan-kegiatan/wpa/create') ?>" class="bg-accent hover:bg-white text-black px-6 py-2 rounded-lg text-xs font-bold uppercase tracking-widest shadow transition text-center">
            <i class="fas fa-plus mr-2"></i> Tambah WPA
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

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-4 mb-6">
        <div class="bg-[#111] border border-white/10 rounded-xl p-3 md:p-4 text-center">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Total WPA</p>
            <p class="text-2xl font-bold"><?= $stats['total'] ?></p>
        </div>
        <div class="bg-[#111] border border-white/10 rounded-xl p-3 md:p-4 text-center">
            <p class="text-xs text-green-500 uppercase tracking-widest mb-1">Aktif</p>
            <p class="text-2xl font-bold text-green-500"><?= $stats['active'] ?></p>
        </div>
        <div class="bg-[#111] border border-white/10 rounded-xl p-3 md:p-4 text-center">
            <p class="text-xs text-red-500 uppercase tracking-widest mb-1">Tidak Aktif</p>
            <p class="text-2xl font-bold text-red-500"><?= $stats['inactive'] ?></p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-4 mb-6 shadow-xl">
        <form action="<?= base_url('laporan-kegiatan/wpa') ?>" method="get" class="flex flex-col gap-4">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                 <select name="status" onchange="this.form.submit()" class="w-full px-4 py-2.5 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm appearance-none pr-10 bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:1.5em_1.5em] bg-[position:right_1rem_center] bg-no-repeat">
                    <option value="all" <?= ($currentStatus ?? '') === 'all' || !($currentStatus ?? '') ? 'selected' : '' ?>>Semua Status</option>
                    <option value="active" <?= ($currentStatus ?? '') === 'active' ? 'selected' : '' ?>>Aktif</option>
                    <option value="inactive" <?= ($currentStatus ?? '') === 'inactive' ? 'selected' : '' ?>>Tidak Aktif</option>
                </select>
                <div class="relative flex-1">
                    <input type="text" name="search" value="<?= esc($search ?? '') ?>" placeholder="Cari nama atau NIK..." class="w-full px-4 py-2.5 pl-10 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm transition">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-xs"></i>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-6 py-2.5 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center justify-center gap-2">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="<?= base_url('laporan-kegiatan/wpa/export-csv') ?>?status=<?= $currentStatus ?? '' ?>&search=<?= $search ?? '' ?>" 
                       class="px-4 py-2 bg-green-500/20 text-green-400 border border-green-500/30 font-bold rounded-xl hover:bg-green-500 hover:text-black transition flex items-center justify-center gap-2">
                        <i class="fas fa-file-csv"></i> Export CSV
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Table Data -->
    <div class="bg-[#111] border border-white/10 overflow-hidden text-sm rounded-2xl shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1200px]">
                <thead>
                    <tr class="bg-black/50 text-accent text-center font-bold text-[10px] uppercase tracking-wider">
                        <th class="p-3 border-b border-r border-white/10 w-12">No</th>
                        <th class="p-3 border-b border-r border-white/10">Nama WPA</th>
                        <th class="p-3 border-b border-r border-white/10">NIK WPA</th>
                        <th class="p-3 border-b border-r border-white/10">No. Izin WPA</th>
                        <th class="p-3 border-b border-r border-white/10">Tgl Pemberian Izin</th>
                        <th class="p-3 border-b border-r border-white/10">No. Sertifikat ASPEBTINDO</th>
                        <th class="p-3 border-b border-r border-white/10">No. Sertifikat BI</th>
                        <th class="p-3 border-b border-r border-white/10">No. Sertifikat Aspebtindo/BNSP</th>
                        <th class="p-3 border-b border-r border-white/10">Masa Berlaku</th>
                        <th class="p-3 border-b border-r border-white/10">Status</th>
                        <th class="p-3 border-b border-r border-white/10">Keterangan</th>
                        <th class="p-3 border-b border-white/10 w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php if(empty($wpas)): ?>
                        <tr>
                            <td colspan="12" class="p-6 text-center text-gray-500 italic">Belum ada data WPA.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach($wpas as $wpa): ?>
                        <tr class="hover:bg-white/[0.02] text-center transition-colors">
                            <td class="p-3 border-r border-white/5 text-gray-400"><?= $no++ ?></td>
                            <td class="p-3 border-r border-white/5 text-left text-white font-bold"><?= esc($wpa['name']) ?: '-' ?></td>
                            <td class="p-3 border-r border-white/5 text-gray-400 text-xs"><?= esc($wpa['nik_wpa'] ?? '-') ?: '-' ?></td>
                            <td class="p-3 border-r border-white/5 text-gray-400 text-xs"><?= esc($wpa['nomor_izin_wpa'] ?? '-') ?: '-' ?></td>
                            <td class="p-3 border-r border-white/5 text-gray-400 text-xs"><?= esc($wpa['tanggal_izin_wpa'] ?? '-') ?: '-' ?></td>
                            <td class="p-3 border-r border-white/5 text-gray-400 text-xs"><?= esc($wpa['no_sertifikat_aspebtindo'] ?? '-') ?: '-' ?></td>
                            <td class="p-3 border-r border-white/5 text-gray-400 text-xs"><?= esc($wpa['no_sertifikat_bi'] ?? '-') ?: '-' ?></td>
                            <td class="p-3 border-r border-white/5 text-gray-400 text-xs"><?= esc($wpa['no_sertifikat_bnsp'] ?? '-') ?: '-' ?></td>
                            <td class="p-3 border-r border-white/5 text-gray-400 text-xs"><?= esc($wpa['masa_berlaku'] ?? '-') ?: '-' ?></td>
                            <td class="p-3 border-r border-white/5">
                                <?php if(($wpa['status'] ?? '') === 'active'): ?>
                                    <span class="bg-accent/10 text-accent px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider">Aktif</span>
                                <?php else: ?>
                                    <span class="bg-red-500/10 text-red-500 px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider">Tidak Aktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-3 border-r border-white/5 text-gray-400 text-xs text-left max-w-[150px] truncate" title="<?= esc($wpa['keterangan'] ?? '') ?>"><?= esc($wpa['keterangan'] ?? '-') ?: '-' ?></td>
                            <td class="p-3 flex items-center justify-center gap-2">
                                <a href="<?= base_url('laporan-kegiatan/wpa/view/'.$wpa['id']) ?>" class="text-white hover:text-gray-300 transition-colors" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?= base_url('laporan-kegiatan/wpa/edit/'.$wpa['id']) ?>" class="text-blue-400 hover:text-blue-300 transition-colors" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= base_url('laporan-kegiatan/wpa/delete/'.$wpa['id']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus WPA ini?');" class="text-red-500 hover:text-red-400 transition-colors" title="Hapus">
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
