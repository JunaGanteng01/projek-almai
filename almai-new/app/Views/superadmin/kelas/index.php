<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>
<?php 
$pageTitle = 'Kelola Kelas'; 
$pageSubtitle = 'Manage kelas dan program edukasi'; 
$currentPage = $pager->getCurrentPage();
$perPage = 20;
?>

<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
    <div class="flex items-center gap-4">
        <span class="text-gray-400 text-sm">Total: <?= $pager->getTotal() ?> Kelas</span>
    </div>
    <a href="<?= base_url('superadmin/kelas/create') ?>" class="w-full lg:w-auto px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-center whitespace-nowrap">
        <i class="fas fa-plus mr-2"></i> Tambah Kelas
    </a>
</div>

<div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl overflow-hidden">
    <div class="table-responsive">
        <table class="w-full min-w-[800px]">
            <thead class="bg-black/50">
                <tr>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400 w-12">No</th>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400">Kelas</th>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400 hide-mobile">WPA</th>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400">Harga</th>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400 hide-mobile">Mode</th>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400 hide-mobile">Students</th>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400">Status</th>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($kelasList)): ?>
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">Belum ada kelas</td>
                </tr>
                <?php else: ?>
                <?php foreach ($kelasList as $index => $kelas): ?>
                <tr class="border-t border-white/10 hover:bg-white/5">
                    <td class="px-4 md:px-6 py-3 md:py-4 text-gray-400 text-sm"><?= ($currentPage - 1) * $perPage + $index + 1 ?></td>
                    <td class="px-4 md:px-6 py-3 md:py-4">
                        <div class="flex items-center gap-3">
                            <img src="<?= esc($kelas['thumbnail']) ?>" alt="" class="w-16 h-10 rounded-lg object-cover">
                            <div class="max-w-[200px]">
                                <p class="font-medium text-sm line-clamp-1"><?= esc($kelas['title']) ?></p>
                                <p class="text-xs text-gray-500"><?= esc($kelas['category']) ?> • <?= esc($kelas['level']) ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 md:px-6 py-3 md:py-4 hide-mobile">
                        <div class="flex items-center gap-2">
                            <img src="<?= esc($kelas['wpa_photo'] ?? 'https://via.placeholder.com/32') ?>" alt="" class="w-8 h-8 rounded-full object-cover">
                            <span class="text-sm text-gray-400"><?= esc(explode(',', $kelas['wpa_name'] ?? 'Unknown')[0]) ?></span>
                        </div>
                    </td>
                    <td class="px-4 md:px-6 py-3 md:py-4">
                        <div>
                            <p class="text-accent font-bold text-sm">Rp <?= number_format($kelas['price'], 0, ',', '.') ?></p>
                            <?php if ($kelas['original_price'] > $kelas['price']): ?>
                            <p class="text-xs text-gray-500 line-through">Rp <?= number_format($kelas['original_price'], 0, ',', '.') ?></p>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="px-4 md:px-6 py-3 md:py-4 hide-mobile">
                        <span class="px-2 py-1 <?= $kelas['mode'] === 'Online' ? 'bg-blue-500/20 text-blue-400' : ($kelas['mode'] === 'Offline' ? 'bg-orange-500/20 text-orange-400' : 'bg-purple-500/20 text-purple-400') ?> rounded-full text-xs">
                            <?= esc($kelas['mode']) ?>
                        </span>
                    </td>
                    <td class="px-4 md:px-6 py-3 md:py-4 hide-mobile">
                        <span class="text-sm"><i class="fas fa-users text-gray-500 mr-1"></i> <?= number_format($kelas['real_students'] ?? 0) ?></span>
                    </td>
                    <td class="px-4 md:px-6 py-3 md:py-4">
                        <?php if ($kelas['status'] === 'active'): ?>
                            <span class="px-2 py-1 bg-accent/20 text-accent rounded-full text-xs">Active</span>
                        <?php else: ?>
                            <span class="px-2 py-1 bg-red-500/20 text-red-400 rounded-full text-xs">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 md:px-6 py-3 md:py-4">
                        <div class="flex items-center gap-2">
                            <a href="<?= base_url('kelas/' . $kelas['id']) ?>" target="_blank" class="p-2 text-gray-400 hover:text-accent hover:bg-accent/10 rounded-lg transition">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="<?= base_url('superadmin/kelas/edit/' . $kelas['id']) ?>" class="p-2 text-gray-400 hover:text-white hover:bg-white/10 rounded-lg transition">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button onclick="confirmDelete(<?= $kelas['id'] ?>, '<?= esc($kelas['title']) ?>')" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if ($pager->getPageCount() > 1): ?>
    <div class="px-6 py-4 border-t border-white/10">
        <?= $pager->links('default', 'admin_pagination') ?>
    </div>
    <?php endif; ?>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 md:p-8 max-w-sm w-full text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center bg-red-500/20">
            <i class="fas fa-trash text-3xl text-red-500"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Hapus Kelas?</h3>
        <p class="text-gray-400 mb-6 text-sm">Apakah Anda yakin ingin menghapus kelas "<span id="deleteKelasTitle" class="text-white font-medium"></span>"?</p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 py-3 border border-white/20 rounded-xl hover:bg-white/5 transition">Batal</button>
            <form id="deleteForm" method="post" class="flex-1">
                <?= csrf_field() ?>
                <button type="submit" class="w-full py-3 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600 transition">Hapus</button>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id, title) {
        document.getElementById('deleteKelasTitle').textContent = title;
        document.getElementById('deleteForm').action = '<?= base_url('superadmin/kelas/delete/') ?>' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
    }
</script>
<?= $this->endSection() ?>
