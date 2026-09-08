<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Header & Actions -->
<div class="flex flex-col lg:flex-row justify-between items-center gap-4 mb-6">
    <!-- Search Form -->
    <form action="<?= base_url('laporan-kegiatan/tim') ?>" method="get" class="flex flex-row flex-wrap items-center gap-2 w-full lg:w-auto flex-1">
        <div class="flex gap-2 w-full sm:w-auto sm:flex-1 lg:flex-none">
            <input type="text" name="search" value="<?= esc($search ?? '') ?>" placeholder="Cari nama atau jabatan..."
                class="flex-1 min-w-[200px] px-4 py-2 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm text-white placeholder-gray-500">
            <button type="submit" class="px-4 py-2 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                <i class="fas fa-search"></i>
            </button>
            <?php if (!empty($search)): ?>
                <a href="<?= base_url('laporan-kegiatan/tim') ?>" class="px-4 py-2 bg-red-500/20 text-red-400 border border-red-500/50 rounded-xl hover:bg-red-500 hover:text-white transition flex items-center justify-center">
                    <i class="fas fa-times text-xs"></i>
                </a>
            <?php endif; ?>
        </div>
    </form>

    <div class="hidden lg:block">
        <span class="text-xs text-gray-500 font-bold uppercase tracking-widest bg-white/5 px-4 py-2 rounded-xl border border-white/10">Read Only Mode</span>
    </div>
</div>

<!-- Team Table -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-black/50">
                <tr>
                    <th class="text-center px-4 py-3 text-xs font-medium text-gray-400 w-20">No. Urut</th>
                    <th class="text-center px-4 py-3 text-xs font-medium text-gray-400 w-24">Foto</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Nama</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Jabatan</th>
                    <th class="text-center px-4 py-3 text-xs font-medium text-gray-400">Status</th>
                    <th class="text-right px-4 py-3 text-xs font-medium text-gray-400 pr-8">Terdaftar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($team)): ?>
                    <tr>
                        <td colspan="6" class="px-4 py-16 text-center text-gray-600">
                            <i class="fas fa-users text-4xl mb-4 opacity-10"></i>
                            <p class="text-sm">Belum ada data anggota tim</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($team as $member): ?>
                        <tr class="hover:bg-white/5 transition group">
                            <td class="px-4 py-4 text-center text-gray-500 font-mono text-xs"><?= esc($member['order_number']) ?></td>
                            <td class="px-4 py-4 text-center">
                                <?php
                                $photoUrl = !empty($member['photo'])
                                    ? base_url('file/' . $member['photo'])
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($member['name']) . '&background=33E818&color=000&size=150';
                                ?>
                                <img src="<?= $photoUrl ?>" alt="<?= esc($member['name']) ?>" class="w-10 h-10 rounded-xl object-cover mx-auto border border-white/10 group-hover:border-accent transition duration-300">
                            </td>
                            <td class="px-4 py-4">
                                <p class="font-bold text-white text-sm tracking-tight"><?= esc($member['name']) ?></p>
                            </td>
                            <td class="px-4 py-4">
                                <span class="px-2 py-1 bg-white/5 rounded text-[10px] font-bold uppercase tracking-widest text-gray-400 border border-white/5">
                                    <?= esc($member['role']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <?php if ($member['is_active']): ?>
                                    <span class="px-2.5 py-1 bg-green-500/10 text-green-400 rounded-lg text-[10px] font-bold border border-green-500/20">Aktif</span>
                                <?php else: ?>
                                    <span class="px-2.5 py-1 bg-gray-500/10 text-gray-400 rounded-lg text-[10px] font-bold border border-gray-500/20">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-4 text-right pr-8">
                                <span class="text-[10px] text-gray-600 font-mono"><?= date('d M Y', strtotime($member['created_at'])) ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($pager && $pager->getPageCount() > 1): ?>
        <div class="px-4 py-4 border-t border-white/5 bg-black/20">
            <?php
            $page = $pager->getCurrentPage();
            $totalPages = $pager->getPageCount();
            $queryString = !empty($search) ? '&search=' . urlencode($search) : '';
            ?>
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-xs text-gray-500">
                    Menampilkan Halaman <?= $page ?> dari <?= $totalPages ?>
                </p>
                <div class="flex items-center gap-1 flex-wrap justify-center">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?><?= $queryString ?>" class="px-3 py-2 bg-white/5 rounded-lg hover:bg-white/10 transition text-xs text-white">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    <?php endif; ?>

                    <?php
                    $startPage = max(1, $page - 2);
                    $endPage = min($totalPages, $page + 2);
                    for ($i = $startPage; $i <= $endPage; $i++):
                    ?>
                        <a href="?page=<?= $i ?><?= $queryString ?>" class="px-3 py-2 rounded-lg text-xs <?= $i === $page ? 'bg-accent text-black font-bold' : 'bg-white/5 hover:bg-white/10 text-white' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?= $page + 1 ?><?= $queryString ?>" class="px-3 py-2 bg-white/5 rounded-lg hover:bg-white/10 transition text-xs text-white">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
