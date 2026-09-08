<?php
$this->setVar('pageTitle', 'Kelola WPA');
$this->setVar('pageSubtitle', 'Manage Wakil Penasihat Berjangka');
?>
<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 mb-6">
    <div class="bg-[#111] border border-white/10 rounded-xl p-3 md:p-4">
        <div class="flex items-center gap-2 md:gap-3">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-blue-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-user-tie text-blue-500 text-sm md:text-base"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-lg md:text-2xl font-bold truncate leading-tight"><?= $stats['total'] ?></p>
                <p class="text-[9px] md:text-xs text-gray-400 truncate uppercase tracking-tighter md:tracking-normal">Total WPA</p>
            </div>
        </div>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-3 md:p-4">
        <div class="flex items-center gap-2 md:gap-3">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-green-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-check-circle text-green-500 text-sm md:text-base"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-lg md:text-2xl font-bold text-green-500 truncate leading-tight"><?= $stats['active'] ?></p>
                <p class="text-[9px] md:text-xs text-gray-400 truncate uppercase tracking-tighter md:tracking-normal">Active</p>
            </div>
        </div>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-3 md:p-4">
        <div class="flex items-center gap-2 md:gap-3">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-red-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-times-circle text-red-500 text-sm md:text-base"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-lg md:text-2xl font-bold text-red-500 truncate leading-tight"><?= $stats['inactive'] ?></p>
                <p class="text-[9px] md:text-xs text-gray-400 truncate uppercase tracking-tighter md:tracking-normal">Inactive</p>
            </div>
        </div>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-3 md:p-4">
        <div class="flex items-center gap-2 md:gap-3">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-yellow-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-star text-yellow-500 text-sm md:text-base"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-lg md:text-2xl font-bold text-yellow-500 truncate leading-tight"><?= number_format($stats['avg_rating'] ?? 0, 1) ?></p>
                <p class="text-[9px] md:text-xs text-gray-400 truncate uppercase tracking-tighter md:tracking-normal">Rating</p>
            </div>
        </div>
    </div>
</div>

<!-- Header Actions & Filters -->
<div class="flex flex-col gap-4 mb-6">
    <div class="flex items-center gap-2 overflow-x-auto scrollbar-hide -mx-4 px-4">
        <a href="?status=all" class="px-6 py-2 rounded-full text-[11px] md:text-sm font-bold whitespace-nowrap transition <?= ($currentStatus === 'all' || empty($currentStatus)) ? 'bg-accent text-black' : 'bg-white/5 text-gray-400 hover:bg-white/10' ?>">SEMUA</a>
        <a href="?status=active" class="px-6 py-2 rounded-full text-[11px] md:text-sm font-bold whitespace-nowrap transition <?= $currentStatus === 'active' ? 'bg-accent text-black' : 'bg-white/5 text-gray-400 hover:bg-white/10' ?>">AKTIF</a>
        <a href="?status=inactive" class="px-6 py-2 rounded-full text-[11px] md:text-sm font-bold whitespace-nowrap transition <?= $currentStatus === 'inactive' ? 'bg-accent text-black' : 'bg-white/5 text-gray-400 hover:bg-white/10' ?>">NONAKTIF</a>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <form action="<?= base_url('admin/wpa') ?>" method="get" class="w-full relative">
            <input type="hidden" name="status" value="<?= esc($currentStatus ?? 'all') ?>">
            <input type="text" name="search" value="<?= esc($search ?? '') ?>" placeholder="Cari nama, specialty..." class="w-full bg-[#111] border border-white/20 rounded-xl px-4 py-3 pl-10 text-sm focus:border-accent focus:outline-none">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-xs"></i>
        </form>
        <?php if ($canWrite ?? false): ?>
            <a href="<?= base_url('admin/wpa/create') ?>" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-center text-sm whitespace-nowrap shadow-lg shadow-accent/10">
                <i class="fas fa-plus"></i> Tambah WPA
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- WPA Table -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto scrollbar-hide">
        <table class="w-full min-w-[750px]">
            <thead class="bg-black/80">
                <tr>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 w-12 text-center uppercase tracking-widest">No</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">WPA</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Specialty</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Linked User</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest hide-mobile">Rating</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Status</th>
                    <th class="text-center px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($wpaList)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center text-gray-500">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-tie text-2xl"></i>
                                </div>
                                <p class="text-sm font-medium">Tidak ada data WPA ditemukan</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($wpaList as $index => $wpa): ?>
                        <tr class="hover:bg-white/5 transition group">
                            <td class="px-4 py-4 text-gray-500 text-sm text-center font-mono"><?= sprintf('%02d', ($currentPage - 1) * $perPage + $index + 1) ?></td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <?php
                                    $photoUrl = $wpa['photo'];
                                    if (strpos($photoUrl, 'uploads/') === 0) {
                                        $photoUrl = base_url('file/' . $photoUrl);
                                    } elseif (strpos($photoUrl, 'images/') === 0) {
                                        $photoUrl = base_url($photoUrl);
                                    }
                                    ?>
                                    <img src="<?= esc($photoUrl) ?>" alt="" class="w-11 h-11 rounded-full object-cover border border-white/20 group-hover:border-accent/30 transition flex-shrink-0">
                                    <div class="min-w-0">
                                        <p class="font-bold text-sm truncate text-gray-200 group-hover:text-white transition"><?= esc($wpa['name']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="px-2.5 py-1 text-[10px] font-black rounded-lg uppercase tracking-wider bg-accent/10 text-accent">
                                    <?= esc($wpa['specialty']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <?php if (!empty($wpa['user_name'])): ?>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-white"><?= esc($wpa['user_name']) ?></span>
                                        <span class="text-[10px] text-accent font-mono"><?= esc($wpa['user_referral'] ?? '-') ?></span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-xs text-red-400 italic">Not Linked</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-4 hide-mobile">
                                <div class="flex items-center gap-1.5 text-sm">
                                    <i class="fas fa-star text-yellow-500 text-xs"></i>
                                    <span class="font-black"><?= number_format($wpa['rating'] ?? 0, 1) ?></span>
                                    <span class="text-[10px] text-gray-600 font-bold">(<?= $wpa['total_classes'] ?? 0 ?>)</span>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <?php if ($wpa['status'] === 'active'): ?>
                                    <span class="px-2.5 py-1 bg-accent/20 text-accent rounded-lg text-[10px] font-black uppercase tracking-wider">Active</span>
                                <?php else: ?>
                                    <span class="px-2.5 py-1 bg-red-500/20 text-red-400 rounded-lg text-[10px] font-black uppercase tracking-wider">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?= base_url('wpa/' . $wpa['id']) ?>" target="_blank" class="w-10 h-10 flex items-center justify-center bg-blue-500/10 text-blue-400 rounded-xl hover:bg-blue-500 hover:text-white transition shadow-sm" title="Lihat Profil">
                                        <i class="fas fa-external-link-alt text-xs"></i>
                                    </a>
                                    <?php if ($canWrite ?? false): ?>
                                        <a href="<?= base_url('admin/wpa/edit/' . $wpa['id']) ?>" class="w-10 h-10 flex items-center justify-center bg-white/5 text-gray-400 rounded-xl hover:bg-white/10 hover:text-white transition shadow-sm" title="Edit">
                                            <i class="fas fa-pencil-alt text-xs"></i>
                                        </a>
                                        <button onclick="confirmDelete(<?= $wpa['id'] ?>, '<?= esc($wpa['name']) ?>')" class="w-10 h-10 flex items-center justify-center bg-red-500/10 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition shadow-sm" title="Hapus">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<?php if (!empty($wpaList) && $stats['total'] > $perPage): ?>
    <div class="mt-6">
        <?= $pager->makeLinks($currentPage, $perPage, $stats['total'], 'admin_pagination') ?>
    </div>
<?php endif; ?>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl max-w-md w-full p-6 shadow-2xl">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 bg-red-500/20 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold">Konfirmasi Hapus</h3>
                <p class="text-sm text-gray-400">Tindakan ini tidak dapat dibatalkan</p>
            </div>
        </div>
        <p class="text-gray-300 mb-6">
            Apakah Anda yakin ingin menghapus WPA <span id="deleteWpaName" class="font-bold text-accent"></span>?
            <br><br>
            <span class="text-sm text-gray-400">Catatan: Akun user terkait tidak akan dihapus, hanya status WPA yang akan diubah.</span>
        </p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 px-4 py-3 bg-white/5 text-gray-300 rounded-xl hover:bg-white/10 transition font-bold">
                Batal
            </button>
            <form id="deleteForm" method="post" class="flex-1">
                <?= csrf_field() ?>
                <button type="submit" class="w-full px-4 py-3 bg-red-500 text-white rounded-xl hover:bg-red-600 transition font-bold">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmDelete(wpaId, wpaName) {
        document.getElementById('deleteWpaName').textContent = wpaName;
        document.getElementById('deleteForm').action = '<?= base_url('admin/wpa/delete/') ?>' + wpaId;
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
        }
    });

    // Close modal on backdrop click
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDeleteModal();
        }
    });
</script>

<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
<?= $this->endSection() ?>