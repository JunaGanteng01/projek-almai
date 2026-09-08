<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<?php 
$pageTitle = 'Verifikasi KYC PRO'; 
$pageSubtitle = 'Kelola pengajuan upgrade PRO'; 
$currentPage = $pager->getCurrentPage();
$perPage = 20;
?>

<!-- Stats -->
<div class="grid grid-cols-2 lg:grid-cols-3 gap-3 md:gap-6 mb-6">
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-yellow-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= $totalPending ?></p>
                <p class="text-xs text-gray-500">Pending</p>
            </div>
        </div>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-accent/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-accent"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= $totalApproved ?></p>
                <p class="text-xs text-gray-500">Approved</p>
            </div>
        </div>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-red-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-times-circle text-red-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= $totalRejected ?></p>
                <p class="text-xs text-gray-500">Rejected</p>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
    <form action="<?= base_url('admin/kyc') ?>" method="get" class="flex flex-col sm:flex-row flex-wrap gap-2 w-full lg:w-auto">
        <select name="status" onchange="this.form.submit()" class="px-4 py-2 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm w-full sm:w-auto">
            <option value="all" <?= $currentStatus === 'all' || !$currentStatus ? 'selected' : '' ?>>Semua Status</option>
            <option value="pending" <?= $currentStatus === 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="approved" <?= $currentStatus === 'approved' ? 'selected' : '' ?>>Approved</option>
            <option value="rejected" <?= $currentStatus === 'rejected' ? 'selected' : '' ?>>Rejected</option>
        </select>
        <div class="flex gap-2 w-full sm:w-auto">
            <input type="text" name="search" value="<?= esc($currentSearch ?? '') ?>" placeholder="Cari nama/email..." 
                class="flex-1 sm:w-48 px-4 py-2 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm">
            <button type="submit" class="px-4 py-2 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                <i class="fas fa-search"></i>
            </button>
        </div>
    </form>
</div>

<div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl overflow-hidden">
    <div class="table-responsive">
        <table class="w-full min-w-[700px]">
            <thead class="bg-black/50">
                <tr>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400 w-12">No</th>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400">User</th>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400 hide-mobile">Email</th>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400">Status KYC</th>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400 hide-mobile">Tanggal Submit</th>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400">PRO Status</th>
                    <th class="text-left px-4 md:px-6 py-3 md:py-4 text-xs md:text-sm font-medium text-gray-400">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($kycList)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-3 block"></i>
                        Tidak ada pengajuan KYC
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($kycList as $index => $user): ?>
                <tr class="border-t border-white/10 hover:bg-white/5">
                    <td class="px-4 md:px-6 py-3 md:py-4 text-gray-400 text-sm"><?= ($currentPage - 1) * $perPage + $index + 1 ?></td>
                    <td class="px-4 md:px-6 py-3 md:py-4">
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-gradient-to-br from-yellow-500/20 to-orange-500/20">
                                    <i class="fas fa-user text-yellow-500"></i>
                                </div>
                                <?php if ($user['level_id'] == \App\Models\LevelModel::LEVEL_PRO): ?>
                                <div class="absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-crown text-black text-xs"></i>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="font-medium text-sm"><?= esc($user['name']) ?></p>
                                <p class="text-xs text-gray-500 md:hidden"><?= esc($user['email']) ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 md:px-6 py-3 md:py-4 text-gray-400 text-sm hide-mobile"><?= esc($user['email']) ?></td>
                    <td class="px-4 md:px-6 py-3 md:py-4">
                        <?php if ($user['kyc_status'] === 'pending'): ?>
                            <span class="px-2 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-xs">
                                <i class="fas fa-clock mr-1"></i>Pending
                            </span>
                        <?php elseif ($user['kyc_status'] === 'approved'): ?>
                            <span class="px-2 py-1 bg-accent/20 text-accent rounded-full text-xs">
                                <i class="fas fa-check mr-1"></i>Approved
                            </span>
                        <?php else: ?>
                            <span class="px-2 py-1 bg-red-500/20 text-red-400 rounded-full text-xs">
                                <i class="fas fa-times mr-1"></i>Rejected
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 md:px-6 py-3 md:py-4 text-gray-400 text-sm hide-mobile">
                        <?= $user['kyc_submitted_at'] ? date('d M Y H:i', strtotime($user['kyc_submitted_at'])) : '-' ?>
                    </td>
                    <td class="px-4 md:px-6 py-3 md:py-4">
                        <?php if ($user['level_id'] == \App\Models\LevelModel::LEVEL_PRO): ?>
                            <span class="px-2 py-1 bg-gradient-to-r from-yellow-500/20 to-orange-500/20 text-yellow-500 rounded-full text-xs border border-yellow-500/30">
                                <i class="fas fa-crown mr-1"></i>PRO
                            </span>
                        <?php else: ?>
                            <span class="px-2 py-1 bg-white/10 text-gray-400 rounded-full text-xs">Basic</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 md:px-6 py-3 md:py-4">
                        <div class="flex items-center gap-1">
                            <a href="<?= base_url('admin/kyc/detail/' . $user['id']) ?>" 
                                class="p-2 text-blue-400 hover:bg-blue-500/20 rounded-lg transition" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <?php if ($user['kyc_status'] === 'pending'): ?>
                                <button onclick="approveKyc(<?= $user['id'] ?>, '<?= esc($user['name']) ?>')" 
                                    class="p-2 text-accent hover:bg-accent/20 rounded-lg transition" title="Approve">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button onclick="rejectKyc(<?= $user['id'] ?>, '<?= esc($user['name']) ?>')" 
                                    class="p-2 text-red-400 hover:bg-red-500/20 rounded-lg transition" title="Reject">
                                    <i class="fas fa-times"></i>
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
    
    <?php if ($pager->getPageCount() > 1): ?>
    <div class="px-6 py-4 border-t border-white/10">
        <?= $pager->links('default', 'admin_pagination') ?>
    </div>
    <?php endif; ?>
</div>

<!-- Approve Modal -->
<div id="approveModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 md:p-8 max-w-sm w-full text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center bg-gradient-to-br from-yellow-500/20 to-orange-500/20">
            <i class="fas fa-crown text-3xl text-yellow-500"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Approve KYC?</h3>
        <p class="text-gray-400 mb-6 text-sm">User "<span id="approveUserName" class="text-white font-medium"></span>" akan menjadi Member PRO selama 1 tahun.</p>
        <div class="flex gap-3">
            <button onclick="closeApproveModal()" class="flex-1 py-3 border border-white/20 rounded-xl hover:bg-white/5 transition">Batal</button>
            <form id="approveForm" method="post" class="flex-1">
                <?= csrf_field() ?>
                <button type="submit" class="w-full py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-black font-bold rounded-xl hover:from-yellow-400 hover:to-orange-400 transition">
                    <i class="fas fa-check mr-2"></i>Approve
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 md:p-8 max-w-sm w-full text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center bg-red-500/20">
            <i class="fas fa-times text-3xl text-red-500"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Tolak KYC?</h3>
        <p class="text-gray-400 mb-4 text-sm">Pengajuan KYC dari "<span id="rejectUserName" class="text-white font-medium"></span>" akan ditolak.</p>
        <form id="rejectForm" method="post">
            <?= csrf_field() ?>
            <textarea name="reason" rows="2" class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-red-500 focus:outline-none text-sm mb-4" placeholder="Alasan penolakan (opsional)"></textarea>
            <div class="flex gap-3">
                <button type="button" onclick="closeRejectModal()" class="flex-1 py-3 border border-white/20 rounded-xl hover:bg-white/5 transition">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600 transition">
                    <i class="fas fa-times mr-2"></i>Tolak
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function approveKyc(id, name) {
        document.getElementById('approveUserName').textContent = name;
        document.getElementById('approveForm').action = '<?= base_url('admin/kyc/approve/') ?>' + id;
        document.getElementById('approveModal').classList.remove('hidden');
        document.getElementById('approveModal').classList.add('flex');
    }
    function closeApproveModal() {
        document.getElementById('approveModal').classList.add('hidden');
        document.getElementById('approveModal').classList.remove('flex');
    }

    function rejectKyc(id, name) {
        document.getElementById('rejectUserName').textContent = name;
        document.getElementById('rejectForm').action = '<?= base_url('admin/kyc/reject/') ?>' + id;
        document.getElementById('rejectModal').classList.remove('hidden');
        document.getElementById('rejectModal').classList.add('flex');
    }
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
        document.getElementById('rejectModal').classList.remove('flex');
    }
</script>
<?= $this->endSection() ?>
