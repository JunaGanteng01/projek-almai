<?php
$this->setVar('pageTitle', 'Kelola CWPA');
$this->setVar('pageSubtitle', 'Manage Calon Wakil Penasihat Berjangka');
?>
<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4 mb-6">
    <div class="bg-[#111] border border-white/10 rounded-xl p-3 md:p-4">
        <div class="flex items-center gap-2 md:gap-3">
            <div class="w-8 h-8 md:w-10 md:h-10 bg-blue-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-user-graduate text-blue-500 text-sm md:text-base"></i>
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-lg md:text-2xl font-bold truncate leading-tight"><?= $stats['total'] ?></p>
                <p class="text-[9px] md:text-xs text-gray-400 truncate uppercase tracking-tighter md:tracking-normal">Total CWPA</p>
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
</div>

<!-- Header Actions & Filters -->
<div class="flex flex-col gap-4 mb-6">
    <div class="flex items-center gap-2 overflow-x-auto scrollbar-hide -mx-4 px-4">
        <a href="?status=all" class="px-6 py-2 rounded-full text-[11px] md:text-sm font-bold whitespace-nowrap transition <?= ($currentStatus === 'all' || empty($currentStatus)) ? 'bg-accent text-black' : 'bg-white/5 text-gray-400 hover:bg-white/10' ?>">SEMUA</a>
        <a href="?status=active" class="px-6 py-2 rounded-full text-[11px] md:text-sm font-bold whitespace-nowrap transition <?= $currentStatus === 'active' ? 'bg-accent text-black' : 'bg-white/5 text-gray-400 hover:bg-white/10' ?>">AKTIF</a>
        <a href="?status=inactive" class="px-6 py-2 rounded-full text-[11px] md:text-sm font-bold whitespace-nowrap transition <?= $currentStatus === 'inactive' ? 'bg-accent text-black' : 'bg-white/5 text-gray-400 hover:bg-white/10' ?>">NONAKTIF</a>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <form action="<?= base_url('superadmin/cwpa') ?>" method="get" class="w-full relative">
            <input type="hidden" name="status" value="<?= esc($currentStatus ?? 'all') ?>">
            <input type="text" name="search" value="<?= esc($search ?? '') ?>" placeholder="Cari nama, perusahaan..." class="w-full bg-[#111] border border-white/20 rounded-xl px-4 py-3 pl-10 text-sm focus:border-accent focus:outline-none">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-xs"></i>
        </form>
        <a href="<?= base_url('superadmin/cwpa/create') ?>" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-center text-sm whitespace-nowrap shadow-lg shadow-accent/10">
            <i class="fas fa-plus"></i> Tambah CWPA
        </a>
    </div>
</div>

<!-- CWPA Table -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto scrollbar-hide">
        <table class="w-full min-w-[750px]">
            <thead class="bg-black/80">
                <tr>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 w-12 text-center uppercase tracking-widest">No</th>
                                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">CWPA</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Specialist</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Linked User</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Phase</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Status</th>
                    <th class="text-center px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($cwpaList)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center text-gray-500">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user-graduate text-2xl"></i>
                                </div>
                                <p class="text-sm font-medium">Tidak ada data CWPA ditemukan</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($cwpaList as $index => $cwpa): ?>
                        <tr class="hover:bg-white/5 transition group">
                            <td class="px-4 py-4 text-gray-500 text-sm text-center font-mono"><?= sprintf('%02d', ($currentPage - 1) * $perPage + $index + 1) ?></td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <?php
                                    $photoUrl = $cwpa['photo'];
                                    if (!empty($photoUrl)) {
                                        if (strpos($photoUrl, 'uploads/') === 0) {
                                            $photoUrl = base_url('file/' . $photoUrl);
                                        } elseif (!filter_var($photoUrl, FILTER_VALIDATE_URL)) {
                                            $photoUrl = base_url($photoUrl);
                                        }
                                    } else {
                                        $photoUrl = base_url('images/default-avatar.png'); // Fallback or keep empty
                                    }
                                    ?>
                                    <img src="<?= esc($photoUrl) ?>" alt="" class="w-11 h-11 rounded-full object-cover border border-white/20 group-hover:border-accent/30 transition flex-shrink-0">
                                    <div class="min-w-0">
                                        <p class="font-bold text-sm truncate text-gray-200 group-hover:text-white transition"><?= esc($cwpa['name']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-white"><?= esc($cwpa['specialty'] ?? '-') ?></span>
                                    <span class="text-[10px] text-gray-500 uppercase tracking-tighter"><?= esc($cwpa['university'] ?? '-') ?></span>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <?php if (!empty($cwpa['user_name'])): ?>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-white"><?= esc($cwpa['user_name']) ?></span>
                                        <span class="text-[10px] text-accent font-mono"><?= esc($cwpa['user_referral'] ?? '-') ?></span>
                                    </div>
                                <?php else: ?>
                                    <span class="text-xs text-red-400 italic">Not Linked</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-4">
                                <span class="text-sm text-gray-400">
                                    <?= \App\Models\CwpaModel::getPhaseLabel($cwpa['current_phase']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <?php if ($cwpa['status'] === 'active'): ?>
                                    <span class="px-2.5 py-1 bg-accent/20 text-accent rounded-lg text-[10px] font-black uppercase tracking-wider">Active</span>
                                <?php else: ?>
                                    <span class="px-2.5 py-1 bg-red-500/20 text-red-400 rounded-lg text-[10px] font-black uppercase tracking-wider">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?= base_url('cwpa/' . $cwpa['slug']) ?>" target="_blank" class="w-10 h-10 flex items-center justify-center bg-blue-500/10 text-blue-400 rounded-xl hover:bg-blue-500 hover:text-white transition shadow-sm" title="Lihat Profil">
                                        <i class="fas fa-external-link-alt text-xs"></i>
                                    </a>
                                    <a href="<?= base_url('superadmin/cwpa/edit/' . $cwpa['id']) ?>" class="w-10 h-10 flex items-center justify-center bg-white/5 text-gray-400 rounded-xl hover:bg-white/10 hover:text-white transition shadow-sm" title="Edit">
                                        <i class="fas fa-pencil-alt text-xs"></i>
                                    </a>
                                    <button type="button" onclick="openCwpaDeleteModal(<?= (int) $cwpa['id'] ?>, '<?= esc($cwpa['name'], 'attr') ?>')" class="w-10 h-10 flex items-center justify-center bg-red-500/10 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition shadow-sm" title="Hapus">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="px-6 py-6 border-t border-white/5">
        <?= $pager->makeLinks($currentPage, $perPage, $stats['total'], 'admin_pagination') ?>
    </div>
</div>

<!-- Modal Hapus CWPA -->
<form id="cwpaDeleteForm" method="post" action="" class="hidden">
    <?= csrf_field() ?>
</form>
<div id="cwpaDeleteModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/70 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="cwpaDeleteTitle">
    <div class="absolute inset-0" onclick="closeCwpaDeleteModal()"></div>
    <div class="relative w-full max-w-md rounded-2xl border border-white/10 bg-[#111] shadow-2xl overflow-hidden animate-modal-in">
        <div class="p-6 md:p-8 text-center">
            <div class="w-16 h-16 mx-auto mb-5 rounded-full bg-red-500/20 border border-red-500/30 flex items-center justify-center">
                <i class="fas fa-user-minus text-2xl text-red-400"></i>
            </div>
            <h3 id="cwpaDeleteTitle" class="text-xl font-bold text-white mb-2">Hapus CWPA?</h3>
            <p class="text-gray-400 text-sm leading-relaxed mb-1">Apakah Anda yakin ingin menghapus CWPA ini?</p>
            <p id="cwpaDeleteName" class="text-accent font-semibold text-sm mb-6"></p>
            <div class="flex gap-3 justify-center flex-wrap">
                <button type="button" onclick="closeCwpaDeleteModal()" class="px-5 py-2.5 rounded-xl border border-white/20 text-gray-300 font-medium text-sm hover:bg-white/5 transition">
                    Batal
                </button>
                <button type="button" onclick="submitCwpaDelete()" class="px-5 py-2.5 rounded-xl bg-red-500 text-white font-bold text-sm hover:bg-red-600 transition shadow-lg shadow-red-500/20">
                    <i class="fas fa-trash-alt mr-2 text-xs"></i> Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .animate-modal-in {
        animation: modalIn 0.25s ease-out;
    }

    @keyframes modalIn {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(-10px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
</style>
<script>
    function openCwpaDeleteModal(id, name) {
        var form = document.getElementById('cwpaDeleteForm');
        var modal = document.getElementById('cwpaDeleteModal');
        var nameEl = document.getElementById('cwpaDeleteName');
        if (!form || !modal) return;
        form.action = '<?= base_url('superadmin/cwpa/delete/') ?>' + id;
        form.classList.remove('hidden');
        nameEl.textContent = name || '';
        nameEl.style.display = name ? 'block' : 'none';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeCwpaDeleteModal() {
        var modal = document.getElementById('cwpaDeleteModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }
    }

    function submitCwpaDelete() {
        document.getElementById('cwpaDeleteForm').submit();
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeCwpaDeleteModal();
    });
</script>
<?= $this->endSection() ?>