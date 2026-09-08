<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>
<?php
$currentPage = $pager->getCurrentPage();
$perPage = 20;
$startNumber = (($page - 1) * $perPage) + 1;
?>

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 md:gap-4 mb-6">
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                <i class="fas fa-clipboard-list text-white"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= $grandTotal ?? 0 ?></p>
                <p class="text-xs text-gray-500">Total Absensi</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="flex flex-col lg:flex-row justify-between items-center gap-4 mb-6">
    <form action="<?= base_url('laporan-kegiatan/klien') ?>" method="get" class="flex flex-row flex-wrap items-center gap-2 w-full lg:w-auto flex-1">
        <select name="kegiatan_type" onchange="this.form.submit()" class="px-4 py-2 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm cursor-pointer min-w-[120px]">
            <option value="all" <?= $currentKegiatanType === 'all' || !$currentKegiatanType ? 'selected' : '' ?>>Semua Kegiatan</option>
            <option value="seminar_fgd" <?= $currentKegiatanType === 'seminar_fgd' ? 'selected' : '' ?>>Seminar</option>
            <option value="pelatihan" <?= $currentKegiatanType === 'pelatihan' ? 'selected' : '' ?>>Pelatihan</option>
            <option value="konsultasi" <?= $currentKegiatanType === 'konsultasi' ? 'selected' : '' ?>>Konsultasi</option>
            <option value="expert_advisor" <?= $currentKegiatanType === 'expert_advisor' ? 'selected' : '' ?>>Expert Advisor</option>
            <option value="signal" <?= $currentKegiatanType === 'signal' ? 'selected' : '' ?>>Signal</option>
            <option value="kegiatan_lainnya" <?= $currentKegiatanType === 'kegiatan_lainnya' ? 'selected' : '' ?>>Lainnya</option>
        </select>

        <select name="time_filter" onchange="this.form.submit()" class="px-4 py-2 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm cursor-pointer min-w-[120px]">
            <option value="" <?= empty($currentTimeFilter) ? 'selected' : '' ?>>Semua Waktu</option>
            <option value="today" <?= $currentTimeFilter === 'today' ? 'selected' : '' ?>>Hari Ini</option>
            <option value="this_week" <?= $currentTimeFilter === 'this_week' ? 'selected' : '' ?>>Minggu Ini</option>
            <option value="this_month" <?= $currentTimeFilter === 'this_month' ? 'selected' : '' ?>>Bulan Ini</option>
            <option value="this_year" <?= $currentTimeFilter === 'this_year' ? 'selected' : '' ?>>Tahun Ini</option>
        </select>

        <select name="sort" onchange="this.form.submit()" class="px-4 py-2 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm cursor-pointer min-w-[120px]">
            <option value="newest" <?= $currentSort === 'newest' ? 'selected' : '' ?>>Terbaru</option>
            <option value="oldest" <?= $currentSort === 'oldest' ? 'selected' : '' ?>>Terlama</option>
        </select>

        <div class="flex gap-2 w-full sm:w-auto sm:flex-1 lg:flex-none">
            <input type="text" name="search" value="<?= esc($currentSearch) ?>" placeholder="Cari nama/email..."
                class="flex-1 min-w-[150px] px-4 py-2 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm">
            <button type="submit" class="px-4 py-2 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                <i class="fas fa-search"></i>
            </button>
            <a href="<?= base_url('laporan-kegiatan/klien/export-csv') ?>?kegiatan_type=<?= $currentKegiatanType ?>&search=<?= $currentSearch ?>&time_filter=<?= $currentTimeFilter ?>&sort=<?= $currentSort ?>" 
               class="px-4 py-2 bg-green-500/20 text-green-400 border border-green-500/30 font-bold rounded-xl hover:bg-green-500 hover:text-black transition flex items-center gap-2">
                <i class="fas fa-file-csv"></i> Export CSV
            </a>
            <a target="_blank" href="<?= base_url('laporan-kegiatan/klien/export-pdf') ?>?kegiatan_type=<?= $currentKegiatanType ?>&search=<?= $currentSearch ?>&time_filter=<?= $currentTimeFilter ?>&sort=<?= $currentSort ?>" 
               class="px-4 py-2 bg-red-500/20 text-red-400 border border-red-500/30 font-bold rounded-xl hover:bg-red-500 hover:text-white transition flex items-center gap-2">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </div>
    </form>
</div>

<!-- Users Table -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-xl text-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[1000px]">
            <thead>
                <tr class="bg-black/50 text-accent text-center font-bold text-[10px] uppercase tracking-wider">
                    <th class="p-3 border-b border-r border-white/10 w-12">No</th>
                    <th class="p-3 border-b border-r border-white/10 text-left">Nama Peserta</th>
                    <th class="p-3 border-b border-r border-white/10">Email / No. HP</th>
                    <th class="p-3 border-b border-r border-white/10">Jenis Kegiatan</th>
                    <th class="p-3 border-b border-r border-white/10 text-left">Nama Kegiatan</th>
                    <th class="p-3 border-b border-r border-white/10">Poin</th>
                    <th class="p-3 border-b border-r border-white/10">Waktu Check-in</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($usersList)): ?>
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-users text-4xl mb-4"></i>
                            <p>Tidak ada data absensi ditemukan</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($usersList as $index => $row): ?>
                        <tr class="border-t border-white/5 hover:bg-white/5">
                            <td class="px-3 py-3 text-center text-gray-500 text-sm"><?= $startNumber + $index ?></td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-accent/20 flex items-center justify-center text-accent font-bold">
                                        <?= strtoupper(substr($row['name'] ?? 'U', 0, 2)) ?>
                                    </div>
                                    <div>
                                        <p class="font-medium text-sm"><?= esc($row['name'] ?? 'Unknown') ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-400 text-sm text-center">
                                <?= esc($row['email'] ?? '-') ?><br>
                                <span class="text-xs text-gray-500"><?= esc($row['no_hp'] ?? '-') ?></span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 rounded-full text-xs bg-blue-500/20 text-blue-400 uppercase tracking-wider">
                                    <?= esc(str_replace('_', ' ', $row['kegiatan_type'])) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-400 text-sm">
                                <?= esc($row['kegiatan_name']) ?>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="text-accent font-bold">+<?= esc($row['poin_awarded']) ?></span>
                            </td>
                            <td class="px-4 py-3 text-gray-400 text-sm text-center">
                                <?= date('d M Y H:i', strtotime($row['created_at'])) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if (!empty($usersList) && $pager && $pager->getPageCount() > 1): ?>
        <?php
        $totalPages = ceil($total / $perPage);
        $queryParams = [];
        if ($currentKegiatanType) $queryParams['kegiatan_type'] = $currentKegiatanType;
        if ($currentSearch) $queryParams['search'] = $currentSearch;
        if ($currentTimeFilter) $queryParams['time_filter'] = $currentTimeFilter;
        if ($currentSort) $queryParams['sort'] = $currentSort;
        $queryString = $queryParams ? '&' . http_build_query($queryParams) : '';
        ?>
        <div class="px-4 py-4 border-t border-white/5 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-sm text-gray-500">
                Menampilkan <?= $startNumber ?> - <?= min($startNumber + count($usersList) - 1, $total) ?> dari <?= $total ?> data
            </p>
            <div class="flex items-center gap-1 flex-wrap justify-center">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?><?= $queryString ?>" class="px-3 py-2 bg-white/10 rounded-lg hover:bg-white/20 transition text-sm">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                <?php endif; ?>

                <?php
                $startPage = max(1, $page - 2);
                $endPage = min($totalPages, $page + 2);
                for ($i = $startPage; $i <= $endPage; $i++):
                ?>
                    <a href="?page=<?= $i ?><?= $queryString ?>" class="px-3 py-2 rounded-lg text-sm <?= $i === $page ? 'bg-accent text-black font-bold' : 'bg-white/10 hover:bg-white/20' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?><?= $queryString ?>" class="px-3 py-2 bg-white/10 rounded-lg hover:bg-white/20 transition text-sm">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>
