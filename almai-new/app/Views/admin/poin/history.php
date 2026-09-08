<?php 
$this->setVar('pageTitle', 'Riwayat Poin');
$this->setVar('pageSubtitle', 'Log seluruh transaksi poin user');
?>
<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Back & Actions -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <a href="<?= base_url('admin/poin') ?>" class="flex items-center gap-2 text-gray-400 hover:text-white transition group">
        <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
        <span>Kembali ke Ringkasan Poin</span>
    </a>
</div>

<!-- Filters & Actions -->
<div class="bg-[#111] border border-white/10 rounded-2xl p-4 mb-6 shadow-xl">
    <form action="<?= base_url('admin/poin/history') ?>" method="get" class="flex flex-col gap-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="w-full sm:w-48">
                <select name="type" onchange="this.form.submit()" class="w-full px-4 py-2.5 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm cursor-pointer appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:20px_20px] bg-right-4 bg-no-repeat">
                    <option value="all" <?= $currentType === 'all' || !$currentType ? 'selected' : '' ?>>Semua Tipe</option>
                    <option value="earn" <?= $currentType === 'earn' ? 'selected' : '' ?>>EARN</option>
                    <option value="redeem" <?= $currentType === 'redeem' ? 'selected' : '' ?>>REDEEM</option>
                    <option value="bonus" <?= $currentType === 'bonus' ? 'selected' : '' ?>>BONUS</option>
                    <option value="expired" <?= $currentType === 'expired' ? 'selected' : '' ?>>EXPIRED</option>
                </select>
            </div>
            <div class="relative flex-1">
                <input type="text" name="search" value="<?= esc($currentSearch) ?>" placeholder="Cari nama user atau email..." class="w-full px-4 py-2.5 pl-10 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm transition">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-xs"></i>
            </div>
            <button type="submit" class="px-6 py-2.5 bg-white/5 border border-white/10 text-white font-bold rounded-xl hover:bg-white/10 transition flex items-center justify-center gap-2">
                <i class="fas fa-filter"></i> <span class="sm:hidden">Filter</span>
            </button>
        </div>
    </form>
</div>

<!-- Poin Table -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto scrollbar-hide">
        <table class="w-full min-w-[750px]">
            <thead class="bg-black/80">
                <tr>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 w-12 text-center uppercase tracking-widest">No</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">User / Anggota</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Tipe</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Jumlah</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest hide-mobile">Deskripsi</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Tanggal</th>
                    <th class="text-center px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($poinList)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-20 text-center text-gray-500">
                        <div class="flex flex-col items-center gap-4">
                            <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center">
                                <i class="fas fa-coins text-2xl"></i>
                            </div>
                            <p class="text-sm font-medium">Belum ada aktivitas poin</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php $no = 1; foreach ($poinList as $poin): ?>
                <tr class="hover:bg-white/5 transition group">
                    <td class="px-4 py-4 text-gray-500 text-sm text-center font-mono"><?= sprintf('%02d', $no++) ?></td>
                    <td class="px-4 py-4">
                        <div class="min-w-0">
                            <p class="font-bold text-sm text-gray-200 group-hover:text-accent transition"><?= esc($poin['user_name'] ?? 'Unknown') ?></p>
                            <p class="text-[10px] text-gray-500 truncate mt-0.5 opacity-70"><?= esc($poin['user_email'] ?? '-') ?></p>
                        </div>
                    </td>
                    <td class="px-4 py-4">
                        <?php
                        $typeColors = [
                            'earn' => 'bg-green-500/20 text-green-400 border-green-500/30',
                            'redeem' => 'bg-red-500/20 text-red-400 border-red-500/30',
                            'bonus' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                            'bonus_registration' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                            'bonus_referral_registration' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                            'expired' => 'bg-gray-500/20 text-gray-400 border-gray-500/30',
                        ];
                        $colorClass = $typeColors[$poin['type']] ?? 'bg-gray-500/20 text-gray-400 border-gray-500/30';
                        ?>
                        <span class="px-2.5 py-1 text-[10px] font-black rounded-lg uppercase tracking-wider border <?= $colorClass ?>">
                            <?= esc($poin['type']) ?>
                        </span>
                    </td>
                    <td class="px-4 py-4">
                        <span class="font-black text-sm <?= $poin['point'] > 0 ? 'text-accent' : 'text-red-400' ?>">
                            <?= ($poin['point'] > 0 ? '+' : '') . number_format($poin['point']) ?>
                        </span>
                    </td>
                    <td class="px-4 py-4 text-gray-400 text-[11px] hide-mobile max-w-[200px] truncate" title="<?= esc($poin['description']) ?>">
                        <?= esc($poin['description']) ?>
                    </td>
                    <td class="px-4 py-4">
                        <p class="text-[11px] text-gray-500"><?= date('d M Y', strtotime($poin['created_at'])) ?></p>
                        <p class="text-[9px] text-gray-700 font-mono"><?= date('H:i', strtotime($poin['created_at'])) ?> WIB</p>
                    </td>
                    <td class="px-4 py-4">
                        <div class="flex items-center justify-center gap-2">
                            <a href="<?= base_url('admin/poin/user/' . $poin['user_id']) ?>" class="w-9 h-9 flex items-center justify-center bg-blue-500/10 text-blue-400 rounded-xl hover:bg-blue-500 hover:text-white transition shadow-sm" title="User History">
                                <i class="fas fa-history text-xs"></i>
                            </a>
                            <button onclick="confirmDelete(<?= $poin['id'] ?>)" class="w-9 h-9 flex items-center justify-center bg-red-500/10 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition shadow-sm" title="Hapus">
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

    <!-- Pagination -->
    <?php if ($pager && $pager->getPageCount() > 1): ?>
        <div class="px-4 py-4 border-t border-white/5 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-sm text-gray-500">
                Menampilkan halaman <?= $pager->getCurrentPage() ?> dari <?= $pager->getPageCount() ?>
            </p>
            <div class="flex items-center gap-1 flex-wrap justify-center">
                <?= $pager->links('default', 'admin_pagination') ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/90 backdrop-blur-sm p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 md:p-8 max-w-sm w-full transform transition-all shadow-2xl text-center">
        <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4 text-red-500 text-2xl animate-pulse">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <h3 class="text-xl font-black mb-2 uppercase tracking-wide">Hapus Data?</h3>
        <p class="text-gray-500 text-xs mb-6">Tindakan ini tidak dapat dibatalkan. Poin user akan langsung diperbarui.</p>
        
        <form id="deleteForm" method="post">
            <?= csrf_field() ?>
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteModal()" class="flex-1 py-3.5 border border-white/10 rounded-2xl hover:bg-white/5 transition text-xs font-black tracking-widest">BATAL</button>
                <button type="submit" class="flex-1 py-3.5 bg-red-500 text-white font-black rounded-2xl hover:bg-red-600 transition text-xs tracking-widest shadow-lg shadow-red-500/20">HAPUS</button>
            </div>
        </form>
    </div>
</div>

<style>
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<script>
    function confirmDelete(id) {
        document.getElementById('deleteForm').action = '<?= base_url('admin/poin/delete/') ?>' + id;
        const modal = document.getElementById('deleteModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeDeleteModal() {
        const modal = document.getElementById('deleteModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    window.onclick = function(e) {
        if (e.target === document.getElementById('deleteModal')) closeDeleteModal();
    }
</script>

<?= $this->endSection() ?>
