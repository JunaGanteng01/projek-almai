<?php 
$this->setVar('pageTitle', 'Audit Log');
$this->setVar('pageSubtitle', 'Log aktivitas administratif sistem');
?>
<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Filters -->
<div class="bg-[#111] border border-white/10 rounded-2xl p-4 mb-6 shadow-xl">
    <form action="<?= base_url('admin/audit-log') ?>" method="get" class="flex flex-col sm:flex-row gap-3">
        <div class="w-full sm:w-48">
            <select name="module" onchange="this.form.submit()" class="w-full px-4 py-2.5 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm cursor-pointer appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:20px_20px] bg-right-4 bg-no-repeat">
                <option value="all" <?= $currentModule === 'all' || !$currentModule ? 'selected' : '' ?>>Semua Modul</option>
                <option value="poin" <?= $currentModule === 'poin' ? 'selected' : '' ?>>Poin</option>
                <option value="users" <?= $currentModule === 'users' ? 'selected' : '' ?>>Users</option>
                <option value="layanan" <?= $currentModule === 'layanan' ? 'selected' : '' ?>>Layanan</option>
                <option value="transaksi" <?= $currentModule === 'transaksi' ? 'selected' : '' ?>>Transaksi</option>
            </select>
        </div>
        <div class="relative flex-1">
            <input type="text" name="search" value="<?= esc($currentSearch) ?>" placeholder="Cari aktivitas, detail, atau nama user..." class="w-full px-4 py-2.5 pl-10 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm transition">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-xs"></i>
        </div>
        <button type="submit" class="px-6 py-2.5 bg-white/5 border border-white/10 text-white font-bold rounded-xl hover:bg-white/10 transition">
            <i class="fas fa-filter mr-2"></i> Filter
        </button>
    </form>
</div>

<!-- Logs Table -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto scrollbar-hide">
        <table class="w-full min-w-[800px]">
            <thead class="bg-black/80">
                <tr>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 w-12 text-center uppercase tracking-widest">No</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Waktu</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Admin</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Modul</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Aktivitas</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Target ID</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">IP Address</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($logs)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-20 text-center text-gray-500">
                        <div class="flex flex-col items-center gap-4">
                            <i class="fas fa-history text-2xl opacity-20"></i>
                            <p class="text-sm font-medium">Belum ada log audit</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php $no = 1 + (20 * (($pager->getCurrentPage() ?? 1) - 1)); foreach ($logs as $log): ?>
                <tr class="hover:bg-white/5 transition group">
                    <td class="px-4 py-4 text-gray-500 text-sm text-center font-mono"><?= sprintf('%02d', $no++) ?></td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <p class="text-[11px] text-gray-200"><?= date('d M Y', strtotime($log['created_at'])) ?></p>
                        <p class="text-[9px] text-gray-500 font-mono"><?= date('H:i:s', strtotime($log['created_at'])) ?> WIB</p>
                    </td>
                    <td class="px-4 py-4">
                        <div class="min-w-0">
                            <p class="font-bold text-sm text-gray-200"><?= esc($log['user_name'] ?? 'System') ?></p>
                            <p class="text-[10px] text-gray-500 truncate"><?= esc($log['user_email'] ?? '-') ?></p>
                        </div>
                    </td>
                    <td class="px-4 py-4">
                        <span class="px-2 py-0.5 bg-blue-500/10 border border-blue-500/20 text-blue-400 rounded text-[10px] uppercase font-bold tracking-wider">
                            <?= esc($log['module']) ?>
                        </span>
                    </td>
                    <td class="px-4 py-4">
                        <p class="text-sm text-white font-medium"><?= esc($log['action']) ?></p>
                        <?php if ($log['details']): ?>
                        <button onclick='showDetails(<?= json_encode($log['details']) ?>)' class="text-[10px] text-accent hover:underline mt-1">
                            Lihat Detail
                        </button>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-4 font-mono text-xs text-gray-500">
                        <?= esc($log['target_id'] ?? '-') ?>
                    </td>
                    <td class="px-4 py-4 text-[10px] text-gray-500">
                        <?= esc($log['ip_address']) ?>
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

<!-- Details Modal -->
<div id="detailsModal" class="fixed inset-0 z-[70] hidden items-center justify-center bg-black/90 backdrop-blur-sm p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 md:p-8 max-w-2xl w-full transform transition-all shadow-2xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-black uppercase tracking-wide">Detail Aktivitas</h3>
            <button onclick="closeDetails()" class="text-gray-500 hover:text-white transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="bg-black/50 border border-white/5 rounded-xl p-4 overflow-auto max-h-[60vh]">
            <pre id="detailsContent" class="text-xs text-gray-400 font-mono whitespace-pre-wrap"></pre>
        </div>
    </div>
</div>

<script>
    function showDetails(details) {
        let content = details;
        try {
            const parsed = JSON.parse(details);
            content = JSON.stringify(parsed, null, 4);
        } catch (e) {
            // Not JSON, use as is
        }
        document.getElementById('detailsContent').textContent = content;
        const modal = document.getElementById('detailsModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeDetails() {
        const modal = document.getElementById('detailsModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    window.onclick = function(e) {
        if (e.target === document.getElementById('detailsModal')) closeDetails();
    }
</script>

<?= $this->endSection() ?>
