<?php 
$this->setVar('pageTitle', 'Verifikasi PRO Member');
$this->setVar('pageSubtitle', 'Monitor pengajuan status PRO oleh user (Read Only)');
$currentPage = $pager->getCurrentPage();
$perPage = 20;
?>
<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Performance Grid -->
<div class="grid grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-2xl relative overflow-hidden group">
        <div class="absolute -right-5 -bottom-5 opacity-10 blur-xl w-32 h-32 bg-yellow-500 transition duration-700 pointer-events-none group-hover:scale-110"></div>
        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 italic">Pending Approval</p>
        <p class="text-3xl font-black text-yellow-500 italic"><?= $totalPending ?></p>
        <p class="text-[9px] text-gray-600 font-black mt-1">Verification Queue</p>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-2xl relative overflow-hidden group">
        <div class="absolute -right-5 -bottom-5 opacity-10 blur-xl w-32 h-32 bg-accent transition duration-700 pointer-events-none group-hover:scale-110"></div>
        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 italic">Verified PRO</p>
        <p class="text-3xl font-black text-accent italic"><?= $totalApproved ?></p>
        <p class="text-[9px] text-accent/50 font-black mt-1">Trusted Members</p>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-2xl relative overflow-hidden group">
        <div class="absolute -right-5 -bottom-5 opacity-10 blur-xl w-32 h-32 bg-red-500 transition duration-700 pointer-events-none group-hover:scale-110"></div>
        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 italic">Rejected Logs</p>
        <p class="text-3xl font-black text-red-500 italic"><?= $totalRejected ?></p>
        <p class="text-[9px] text-red-500/50 font-black mt-1">Audit Trail Entry</p>
    </div>
</div>

<div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6">
    <div class="flex gap-2 overflow-x-auto pb-2 lg:pb-0 scrollbar-hide">
        <a href="?status=all" class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest whitespace-nowrap transition <?= !$currentStatus || $currentStatus === 'all' ? 'bg-accent text-black shadow-lg shadow-accent/20' : 'bg-white/5 text-gray-500 border border-white/10 opacity-70' ?>">ALL STATUS</a>
        <a href="?status=pending" class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest whitespace-nowrap transition <?= $currentStatus === 'pending' ? 'bg-accent text-black shadow-lg shadow-accent/20' : 'bg-white/5 text-gray-500 border border-white/10 opacity-70' ?>">PENDING</a>
        <a href="?status=approved" class="px-5 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest whitespace-nowrap transition <?= $currentStatus === 'approved' ? 'bg-accent text-black shadow-lg shadow-accent/20' : 'bg-white/5 text-gray-500 border border-white/10 opacity-70' ?>">APPROVED</a>
    </div>
</div>

<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto scrollbar-hide">
        <table class="w-full min-w-[1000px]">
            <thead class="bg-black/50 border-b border-white/10">
                <tr>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest w-12 text-center italic">ID</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-left italic">User Identity</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-left italic">Communication Channel</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-center italic">KYC Status Tag</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-left italic">System Timestamp</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-center italic">Live Profile</th>
                    <th class="px-6 py-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-center italic">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($kycList)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-20 text-center text-gray-600">
                        <i class="fas fa-fingerprint text-4xl mb-4 opacity-10"></i>
                        <p class="text-sm font-black uppercase tracking-widest italic">Verification dataset is empty</p>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($kycList as $index => $user): ?>
                <tr class="hover:bg-white/[0.02] transition group">
                    <td class="px-6 py-4 text-center font-mono text-[10px] text-gray-600">
                        <?= ($currentPage - 1) * $perPage + $index + 1 ?>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center bg-black transition group-hover:border-accent">
                                <i class="fas fa-user-shield text-gray-700 group-hover:text-accent transition"></i>
                            </div>
                            <span class="text-white font-black text-sm uppercase tracking-tighter"><?= esc($user['name']) ?></span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-[11px] text-gray-300 font-bold"><?= esc($user['email']) ?></p>
                        <p class="text-[9px] text-gray-600 font-black italic"><?= esc($user['phone']) ?></p>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <?php
                        $statusMap = [
                            'pending' => 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20',
                            'approved' => 'bg-accent/10 text-accent border-accent/20',
                            'rejected' => 'bg-red-500/10 text-red-500 border-red-500/20',
                        ];
                        $st = $statusMap[$user['kyc_status']] ?? 'bg-white/5 text-gray-500';
                        ?>
                        <span class="px-3 py-1 <?= $st ?> border rounded text-[9px] font-black uppercase tracking-widest italic leading-none">
                            <i class="fas fa-circle text-[6px] mr-1 opacity-50"></i>
                            <?= esc($user['kyc_status'] ?: 'N/A') ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-left font-mono italic">
                        <span class="text-[10px] text-gray-500 font-bold"><?= $user['kyc_submitted_at'] ? date('D, d.m.Y', strtotime($user['kyc_submitted_at'])) : '--' ?></span>
                        <p class="text-[8px] text-gray-700 font-black uppercase tracking-tighter italic"><?= $user['kyc_submitted_at'] ? date('H:i:s T', strtotime($user['kyc_submitted_at'])) : 'VOID' ?></p>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <?php if ($user['level_id'] == \App\Models\LevelModel::LEVEL_PRO): ?>
                            <div class="flex flex-col items-center gap-1">
                                <span class="px-2 py-0.5 bg-yellow-500 text-black rounded-sm text-[8px] font-black uppercase tracking-widest">LIVE PRO</span>
                                <div class="flex gap-0.5 text-[8px] text-yellow-500 animate-pulse">
                                    <i class="fas fa-star size-1"></i><i class="fas fa-star size-1"></i><i class="fas fa-star size-1"></i>
                                </div>
                            </div>
                        <?php else: ?>
                            <span class="px-2 py-0.5 bg-white/5 text-gray-700 rounded-sm text-[8px] font-black uppercase tracking-widest border border-white/5">BASIC USER</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="<?= base_url('laporan-kegiatan/kyc/detail/' . $user['id']) ?>" 
                            class="w-9 h-9 mx-auto flex items-center justify-center bg-blue-500/10 text-blue-400 rounded-xl hover:bg-blue-500 hover:text-black transition border border-blue-500/20" title="Inspect Documents">
                            <i class="fas fa-file-invoice text-xs"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination Integration -->
    <?php if ($pager->getPageCount() > 1): ?>
        <div class="px-6 py-4 border-t border-white/10 bg-black/40">
            <?= $pager->links('default', 'admin_pagination') ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
