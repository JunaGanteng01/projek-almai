<?php
$pageTitle = 'Manajemen Tim';
$pageSubtitle = 'Kelola daftar anggota tim yang ditampilkan di halaman About';
?>
<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Header & Actions -->
<div class="flex flex-col lg:flex-row justify-between items-center gap-4 mb-6">
    <!-- Search Form -->
    <form action="<?= base_url('admin/tim') ?>" method="get" class="flex flex-row flex-wrap items-center gap-2 w-full lg:w-auto flex-1">
        <div class="flex gap-2 w-full sm:w-auto sm:flex-1 lg:flex-none">
            <input type="text" name="search" value="<?= esc($search ?? '') ?>" placeholder="Cari nama atau jabatan..."
                class="flex-1 min-w-[200px] px-4 py-2 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm text-white placeholder-gray-500">
            <button type="submit" class="px-4 py-2 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                <i class="fas fa-search"></i>
            </button>
            <?php if (!empty($search)): ?>
                <a href="<?= base_url('admin/tim') ?>" class="px-4 py-2 bg-red-500/20 text-red-400 border border-red-500/50 rounded-xl hover:bg-red-500 hover:text-white transition flex items-center justify-center">
                    <i class="fas fa-times"></i>
                </a>
            <?php endif; ?>
        </div>
    </form>

    <a href="<?= base_url('admin/tim/create') ?>" class="w-full sm:w-auto px-6 py-2 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-center whitespace-nowrap">
        <i class="fas fa-plus mr-2"></i> Tambah Anggota
    </a>
</div>

<!-- Team Table -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-black/50">
                <tr>
                    <th class="text-center px-4 py-3 text-xs font-medium text-gray-400 w-20">No. Urut</th>
                    <th class="text-center px-4 py-3 text-xs font-medium text-gray-400 w-24">Foto</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Nama</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Jabatan</th>
                    <th class="text-center px-4 py-3 text-xs font-medium text-gray-400">Status</th>
                    <th class="text-center px-4 py-3 text-xs font-medium text-gray-400 w-32">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($team)): ?>
                    <tr>
                        <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                            <i class="fas fa-users text-4xl mb-4 text-gray-600"></i>
                            <p>Belum ada data anggota tim</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($team as $member): ?>
                        <tr class="border-t border-white/5 hover:bg-white/5 transition">
                            <td class="px-4 py-3 text-center text-gray-400 font-mono"><?= esc($member['order_number']) ?></td>
                            <td class="px-4 py-3 text-center">
                                <?php
                                $photoUrl = !empty($member['photo'])
                                    ? base_url('file/' . $member['photo'])
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($member['name']) . '&background=33E818&color=000&size=150';
                                ?>
                                <img src="<?= $photoUrl ?>" alt="<?= esc($member['name']) ?>" class="w-12 h-12 rounded-xl object-cover mx-auto border border-white/10">
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-bold text-white"><?= esc($member['name']) ?></p>
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-gray-400 text-sm"><?= esc($member['role']) ?></p>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <?php if ($member['is_active']): ?>
                                    <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded-lg text-xs font-medium border border-green-500/20">Aktif</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 bg-gray-500/20 text-gray-400 rounded-lg text-xs font-medium border border-gray-500/20">Nonaktif</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?= base_url('admin/tim/edit/' . $member['id']) ?>" class="w-8 h-8 flex items-center justify-center bg-blue-500/20 text-blue-400 rounded-lg hover:bg-blue-500 hover:text-white transition" title="Edit">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    <button type="button" onclick="confirmDelete(<?= $member['id'] ?>, '<?= esc($member['name']) ?>')" class="w-8 h-8 flex items-center justify-center bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500 hover:text-white transition" title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                                <form id="deleteForm-<?= $member['id'] ?>" action="<?= base_url('admin/tim/delete/' . $member['id']) ?>" method="get" class="hidden"></form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($pager && $pager->getPageCount() > 1): ?>
        <?php
        $page = $pager->getCurrentPage();
        $totalPages = $pager->getPageCount();
        // Preserve search query if exists
        $queryString = !empty($search) ? '&search=' . urlencode($search) : '';
        ?>
        <div class="px-4 py-4 border-t border-white/5 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-sm text-gray-500">
                Halaman <?= $page ?> dari <?= $totalPages ?>
            </p>
            <div class="flex items-center gap-1 flex-wrap justify-center">
                <!-- Previous Page -->
                <?php if ($page > 1): ?>
                    <a href="?page=1<?= $queryString ?>" class="px-3 py-2 bg-white/10 rounded-lg hover:bg-white/20 transition text-sm">
                        <i class="fas fa-angle-double-left"></i>
                    </a>
                    <a href="?page=<?= $page - 1 ?><?= $queryString ?>" class="px-3 py-2 bg-white/10 rounded-lg hover:bg-white/20 transition text-sm">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                <?php endif; ?>

                <!-- Page Numbers -->
                <?php
                $startPage = max(1, $page - 2);
                $endPage = min($totalPages, $page + 2);
                for ($i = $startPage; $i <= $endPage; $i++):
                ?>
                    <a href="?page=<?= $i ?><?= $queryString ?>" class="px-3 py-2 rounded-lg text-sm <?= $i === $page ? 'bg-accent text-black font-bold' : 'bg-white/10 hover:bg-white/20' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <!-- Next Page -->
                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?><?= $queryString ?>" class="px-3 py-2 bg-white/10 rounded-lg hover:bg-white/20 transition text-sm">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <a href="?page=<?= $totalPages ?><?= $queryString ?>" class="px-3 py-2 bg-white/10 rounded-lg hover:bg-white/20 transition text-sm">
                        <i class="fas fa-angle-double-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id, name) {
        Swal.fire({
            title: 'Hapus Anggota Tim?',
            html: `Apakah Anda yakin ingin menghapus <strong>${name}</strong>?<br><span class="text-sm text-gray-400">Tindakan ini tidak dapat dibatalkan.</span>`,
            icon: 'warning',
            background: '#111',
            color: '#fff',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'border border-white/10 rounded-2xl'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm-' + id).submit(); // Changed to simple GET request via form submit or just link? 
                // The route says 'get' for delete: $routes->get('tim/delete/(:num)', ...)
                // So window.location is enough, but hidden form is safer against crawlers if it was POST.
                // My route definition was GET: $routes->get('tim/delete/(:num)', 'Admin\TeamManagement::delete/$1');
                window.location.href = `<?= base_url('admin/tim/delete/') ?>/${id}`;
            }
        })
    }
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>