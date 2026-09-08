<?php
$pageTitle = 'Kelola Hero Banner Event';
$pageSubtitle = 'Manage slide banner pada halaman Event';
?>

<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
    <div class="flex items-center gap-4">
        <span class="text-gray-400 text-sm">Total: <?= count($banners) ?> Banner</span>
    </div>
    <a href="<?= base_url('admin/event-banner/create') ?>" class="w-full lg:w-auto px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-center whitespace-nowrap">
        <i class="fas fa-plus mr-2"></i> Tambah Banner
    </a>
</div>

<div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl overflow-hidden">
    <div class="table-responsive">
        <table class="w-full min-w-[800px]">
            <thead class="bg-black/50">
                <tr>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400 w-12">No</th>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400">Banner</th>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400">Content</th>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400">Order</th>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400">Status</th>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($banners)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada banner</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($banners as $index => $banner): ?>
                        <tr class="border-t border-white/10 hover:bg-white/5">
                            <td class="px-4 md:px-6 py-3 md:py-4 text-gray-400 text-sm"><?= $index + 1 ?></td>
                            <td class="px-4 md:px-6 py-3 md:py-4">
                                <div class="flex items-center gap-3">
                                    <img src="<?= base_url($banner['image']) ?>" alt="" class="w-20 h-10 rounded-lg object-cover">
                                    <div class="max-w-[200px]">
                                        <p class="font-medium text-sm line-clamp-1"><?= esc($banner['title']) ?></p>
                                        <p class="text-xs text-gray-500 line-clamp-1"><?= esc($banner['url']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 md:px-6 py-3 md:py-4">
                                <p class="text-sm text-gray-400 line-clamp-2"><?= esc($banner['content']) ?></p>
                            </td>
                            <td class="px-4 md:px-6 py-3 md:py-4">
                                <span class="text-sm text-gray-400"><?= $banner['order'] ?></span>
                            </td>
                            <td class="px-4 md:px-6 py-3 md:py-4">
                                <?php if ($banner['is_active']): ?>
                                    <span class="px-2 py-1 bg-accent/20 text-accent rounded-full text-xs">Active</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 bg-red-500/20 text-red-400 rounded-full text-xs">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 md:px-6 py-3 md:py-4">
                                <div class="flex items-center gap-2">
                                    <a href="<?= base_url('admin/event-banner/edit/' . $banner['id']) ?>" class="p-2 text-gray-400 hover:text-white hover:bg-white/10 rounded-lg transition">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="confirmDelete(<?= $banner['id'] ?>, '<?= esc($banner['title']) ?>')" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition">
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
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 md:p-8 max-w-sm w-full text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center bg-red-500/20">
            <i class="fas fa-trash text-3xl text-red-500"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Hapus Banner?</h3>
        <p class="text-gray-400 mb-6 text-sm">Apakah Anda yakin ingin menghapus banner "<span id="deleteTitle" class="text-white font-medium"></span>"?</p>
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
        document.getElementById('deleteTitle').textContent = title;
        document.getElementById('deleteForm').action = '<?= base_url('admin/event-banner/delete/') ?>' + id;
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        document.getElementById('deleteModal').classList.remove('flex');
    }
</script>

<?= $this->endSection() ?>