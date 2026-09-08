<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <h1 class="text-2xl font-bold">Manajemen Withdraw</h1>
    <div class="flex flex-wrap gap-2">
        <a href="?status=pending" class="px-4 py-2 bg-yellow-500/10 text-yellow-500 rounded-lg text-sm border <?= $currentStatus === 'pending' ? 'border-yellow-500' : 'border-transparent' ?> hover:bg-yellow-500/20 transition">
            Pending (<?= $stats['count_pending'] ?>)
        </a>
        <a href="?status=approved" class="px-4 py-2 bg-blue-500/10 text-blue-500 rounded-lg text-sm border <?= $currentStatus === 'approved' ? 'border-blue-500' : 'border-transparent' ?> hover:bg-blue-500/20 transition">
            Approved (<?= $stats['count_approved'] ?>)
        </a>
        <a href="?status=completed" class="px-4 py-2 bg-accent/10 text-accent rounded-lg text-sm border <?= $currentStatus === 'completed' ? 'border-accent' : 'border-transparent' ?> hover:bg-accent/20 transition">
            Completed (<?= $stats['count_completed'] ?>)
        </a>
        <a href="<?= base_url('superadmin/withdrawals') ?>" class="px-4 py-2 bg-white/5 text-gray-400 rounded-lg text-sm border <?= empty($currentStatus) ? 'border-white/20' : 'border-transparent' ?> hover:bg-white/10 transition">
            Semua
        </a>
    </div>
</div>

<!-- Stats Dashboard -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
        <div class="flex items-center gap-4 mb-2">
            <div class="w-10 h-10 bg-yellow-500/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-clock text-yellow-500"></i>
            </div>
            <p class="text-gray-500 text-sm font-medium">Total Pending</p>
        </div>
        <h3 class="text-2xl font-bold">Rp <?= number_format($stats['total_pending'], 0, ',', '.') ?></h3>
        <p class="text-xs text-gray-500 mt-1"><?= $stats['count_pending'] ?> pengajuan perlu dicek</p>
    </div>

    <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
        <div class="flex items-center gap-4 mb-2">
            <div class="w-10 h-10 bg-blue-500/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-check-circle text-blue-500"></i>
            </div>
            <p class="text-gray-500 text-sm font-medium">Total Disetujui</p>
        </div>
        <h3 class="text-2xl font-bold">Rp <?= number_format($stats['total_approved'], 0, ',', '.') ?></h3>
        <p class="text-xs text-gray-500 mt-1"><?= $stats['count_approved'] ?> pengajuan siap ditransfer</p>
    </div>

    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 text-accent">
        <div class="flex items-center gap-4 mb-2">
            <div class="w-10 h-10 bg-accent/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-hand-holding-usd text-accent"></i>
            </div>
            <p class="text-accent/60 text-sm font-medium">Total Berhasil</p>
        </div>
        <h3 class="text-2xl font-bold text-accent">Rp <?= number_format($stats['total_completed'], 0, ',', '.') ?></h3>
        <p class="text-xs text-accent/50 mt-1"><?= $stats['count_completed'] ?> penarikan telah selesai</p>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
<div class="bg-accent/10 border border-accent/20 text-accent p-4 rounded-xl mb-6 flex items-center gap-3">
    <i class="fas fa-check-circle"></i>
    <span><?= session()->getFlashdata('success') ?></span>
</div>
<?php endif; ?>

<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-xl">
    <div class="table-responsive">
        <table class="w-full">
            <thead>
                <tr class="text-left bg-black">
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">User / Profile</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Rekening Tujuan</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Jumlah (IDR)</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-center">Status</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-center">Tindakan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5 bg-white/[0.02]">
                <?php if (empty($withdrawals)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <i class="fas fa-money-bill-wave text-4xl mb-3 opacity-20"></i>
                        <p>Tidak ada data penarikan ditemukan.</p>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($withdrawals as $wd): ?>
                <tr class="hover:bg-white/5 transition group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <?php 
                            $displayName = $wd['wpa_name'] ?: ($wd['cwpa_name'] ?: 'Unknown');
                            $typeLabel = $wd['wpa_id'] ? 'WPA' : ($wd['cwpa_id'] ? 'CWPA' : '');
                            $labelColor = $wd['wpa_id'] ? 'bg-blue-500/10 text-blue-400' : 'bg-purple-500/10 text-purple-400';
                            ?>
                            <div class="w-8 h-8 rounded-full bg-accent/10 flex items-center justify-center font-bold text-accent text-xs">
                                <?= substr($displayName, 0, 1) ?>
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-white group-hover:text-accent transition"><?= esc($displayName) ?></span>
                                <?php if ($typeLabel): ?>
                                <span class="text-[9px] px-1.5 py-0.5 rounded-md font-bold uppercase w-fit mt-0.5 <?= $labelColor ?>"><?= $typeLabel ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm">
                            <p class="text-white font-medium mb-0.5"><?= esc($wd['bank_name']) ?></p>
                            <p class="text-gray-500 font-mono text-xs"><?= esc($wd['account_number']) ?></p>
                            <p class="text-[10px] text-gray-500 font-bold mt-1"><?= esc($wd['account_holder']) ?></p>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <span class="text-accent font-bold">Rp <?= number_format($wd['amount'], 0, ',', '.') ?></span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <?php
                        $statusMap = [
                            'pending' => ['bg-yellow-500/10', 'text-yellow-500', 'border-yellow-500/20'],
                            'approved' => ['bg-blue-500/10', 'text-blue-500', 'border-blue-500/20'],
                            'completed' => ['bg-accent/10', 'text-accent', 'border-accent/20'],
                            'rejected' => ['bg-red-500/10', 'text-red-500', 'border-red-500/20'],
                        ];
                        $st = $statusMap[$wd['status']] ?? ['bg-gray-500/10', 'text-gray-400', 'border-gray-500/20'];
                        ?>
                        <span class="px-3 py-1 <?= $st[0] ?> <?= $st[1] ?> border <?= $st[2] ?> rounded-full text-[10px] font-bold uppercase tracking-wider">
                            <?= ucfirst($wd['status']) ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-xs text-gray-500">
                        <p class="text-gray-300"><?= date('d M Y', strtotime($wd['created_at'])) ?></p>
                        <p class="text-[10px]"><?= date('H:i', strtotime($wd['created_at'])) ?> WIB</p>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button onclick="openModal(<?= $wd['id'] ?>, '<?= $wd['status'] ?>', '<?= esc($wd['admin_notes']) ?>')" 
                            class="px-3 py-1.5 bg-white/5 hover:bg-accent hover:text-black rounded-lg transition text-xs font-bold flex items-center justify-center gap-2 mx-auto">
                            <i class="fas fa-cog"></i> Kelola
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if ($pager && $pager->getPageCount() > 1): ?>
    <div class="p-6 border-t border-white/10 bg-black/20">
        <?= $pager->links() ?>
    </div>
    <?php endif; ?>
</div>

<!-- Verification Modal -->
<div id="statusModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 hidden transition-all">
    <div class="bg-[#111] border border-white/10 rounded-3xl w-full max-w-md p-8 shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold italic">Verifikasi Penarikan</h3>
            <button onclick="closeModal()" class="text-gray-500 hover:text-white transition">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="updateForm" method="post" action="">
            <?= csrf_field() ?>
            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Ubah Status</label>
                    <select name="status" id="modalStatus" class="w-full bg-black border border-white/10 rounded-2xl px-5 py-4 focus:border-accent focus:outline-none transition text-white text-sm">
                        <option value="pending">Pending (Menunggu)</option>
                        <option value="approved">Approved (Disetujui/Proses Transfer)</option>
                        <option value="completed">Completed (Berhasil Ditransfer)</option>
                        <option value="rejected">Rejected (Ditolak)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Catatan Verifikasi</label>
                    <textarea name="admin_notes" id="modalNotes" rows="4" 
                        class="w-full bg-black border border-white/10 rounded-2xl px-5 py-4 focus:border-accent focus:outline-none transition text-white text-sm"
                        placeholder="Contoh: Transfer via BRI #TRX123 atau Alasan penolakan..."></textarea>
                </div>
                <div class="flex gap-4 pt-4">
                    <button type="button" onclick="closeModal()" 
                        class="flex-1 py-4 bg-white/5 hover:bg-white/10 text-white font-bold rounded-2xl transition border border-white/10">
                        Batal
                    </button>
                    <button type="submit" 
                        class="flex-1 py-4 bg-accent hover:bg-white text-black font-bold rounded-2xl transition shadow-lg shadow-accent/20">
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id, currentStatus, notes) {
    const modal = document.getElementById('statusModal');
    const form = document.getElementById('updateForm');
    const statusSelect = document.getElementById('modalStatus');
    const notesText = document.getElementById('modalNotes');

    form.action = `<?= base_url('superadmin/withdrawals/update') ?>/${id}`;
    statusSelect.value = currentStatus;
    notesText.value = notes;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal() {
    const modal = document.getElementById('statusModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close on outside click
window.onclick = function(event) {
    const modal = document.getElementById('statusModal');
    if (event.target == modal) {
        closeModal();
    }
}
</script>
<?= $this->endSection() ?>
