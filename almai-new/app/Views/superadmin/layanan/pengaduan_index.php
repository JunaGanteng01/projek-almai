<?php 
$this->setVar('pageTitle', 'Data Pengaduan Advokasi');
$this->setVar('pageSubtitle', 'Kelola seluruh laporan dan pengaduan trader');
?>
<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Header Actions -->
<div class="flex justify-end mb-6">
    <a href="<?= base_url('superadmin/advokasi/setting') ?>" class="px-6 py-3 bg-accent text-black font-black uppercase tracking-widest rounded-2xl hover:shadow-[0_0_20px_rgba(51,232,24,0.3)] hover:scale-[1.02] transition-all text-xs">
        <i class="fas fa-cog mr-2"></i> Pengaturan Program
    </a>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <?php
    $statusMap = [
        'pending_payment' => ['label' => 'Menunggu Pembayaran', 'color' => 'yellow', 'icon' => 'clock'],
        'review' => ['label' => 'Dalam Review', 'color' => 'blue', 'icon' => 'search'],
        'investigating' => ['label' => 'Investigasi', 'color' => 'purple', 'icon' => 'user-secret'],
        'resolved' => ['label' => 'Selesai', 'color' => 'accent', 'icon' => 'check-circle'],
    ];
    ?>
    <div class="bg-[#111] border border-white/10 p-5 rounded-2xl shadow-xl">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 bg-white/5 rounded-xl flex items-center justify-center">
                <i class="fas fa-file-invoice text-gray-400"></i>
            </div>
            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Total</span>
        </div>
        <h3 class="text-2xl font-black text-white"><?= count($complaints) ?></h3>
        <p class="text-[10px] text-gray-500 mt-1">Laporan Masuk</p>
    </div>
    <!-- You can add more specific counters here if passed from controller -->
</div>

<!-- Filters -->
<div class="bg-[#111] border border-white/10 rounded-2xl p-4 mb-6 shadow-xl">
    <form action="<?= base_url('superadmin/advokasi') ?>" method="get" class="flex flex-col lg:flex-row gap-3">
        <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
            <div class="w-full sm:w-48">
                <select name="status" onchange="this.form.submit()" class="w-full px-4 py-2.5 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm cursor-pointer appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:20px_20px] bg-right-4 bg-no-repeat">
                    <option value="">Semua Status</option>
                    <?php foreach ($statusMap as $key => $val): ?>
                        <option value="<?= $key ?>" <?= $currentStatus === $key ? 'selected' : '' ?>><?= $val['label'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="w-full sm:w-48">
                <select name="category" onchange="this.form.submit()" class="w-full px-4 py-2.5 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm cursor-pointer appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:20px_20px] bg-right-4 bg-no-repeat">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= esc($cat) ?>" <?= $currentCategory === $cat ? 'selected' : '' ?>><?= ucwords(esc($cat)) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="w-full sm:w-48">
                <select name="subcategory" onchange="this.form.submit()" class="w-full px-4 py-2.5 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm cursor-pointer appearance-none bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20fill%3D%22none%22%20viewBox%3D%220%200%2020%2020%22%3E%3Cpath%20stroke%3D%22%236b7280%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%20stroke-width%3D%221.5%22%20d%3D%22m6%208%204%204%204-4%22%2F%3E%3C%2Fsvg%3E')] bg-[length:20px_20px] bg-right-4 bg-no-repeat">
                    <option value="">Semua Sub Kategori</option>
                    <?php foreach ($subcategories as $sub): ?>
                        <option value="<?= esc($sub) ?>" <?= $currentSubcategory === $sub ? 'selected' : '' ?>><?= esc($sub) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="relative flex-1">
            <input type="text" name="search" value="<?= esc($currentSearch) ?>" placeholder="Cari nama, email, broker, kategori, atau sub..." class="w-full px-4 py-2.5 pl-10 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm transition">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-xs"></i>
        </div>
        <button type="submit" class="px-6 py-2.5 bg-white/5 border border-white/10 text-white font-bold rounded-xl hover:bg-white/10 transition">
            <i class="fas fa-filter mr-2"></i> Filter
        </button>
    </form>
</div>

<!-- Complaints Table -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto scrollbar-hide">
        <table class="w-full min-w-[1000px]">
            <thead class="bg-black/80">
                <tr>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 w-12 text-center uppercase tracking-widest">No</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Pelapor</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Kasus</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest text-center">Kerugian</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest text-center">Status</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest text-center">Tanggal</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($complaints)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-20 text-center text-gray-500">
                        <div class="flex flex-col items-center gap-4">
                            <i class="fas fa-folder-open text-2xl opacity-20"></i>
                            <p class="text-sm font-medium">Belum ada data pengaduan</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php $no = 1 + (15 * (($pager->getCurrentPage() ?? 1) - 1)); foreach ($complaints as $c): ?>
                <tr class="hover:bg-white/5 transition group">
                    <td class="px-4 py-4 text-gray-500 text-sm text-center font-mono"><?= sprintf('%02d', $no++) ?></td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-xs font-bold text-accent uppercase">
                                <?= substr($c['name'], 0, 1) ?>
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-sm text-gray-200"><?= esc($c['name']) ?></p>
                                <p class="text-[10px] text-gray-500 italic"><?= esc($c['whatsapp']) ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <p class="text-sm font-bold text-white"><?= esc($c['broker_name']) ?></p>
                        <p class="text-[10px] text-gray-500 uppercase tracking-tighter"><?= esc($c['category_problem']) ?> - <?= esc($c['trading_type']) ?></p>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <p class="text-sm font-black text-accent tracking-tighter">Rp <?= number_format($c['loss_amount'], 0, ',', '.') ?></p>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <?php 
                        $currStatus = $statusMap[$c['status']] ?? ['label' => $c['status'], 'color' => 'gray', 'icon' => 'info-circle'];
                        ?>
                        <span class="px-2 py-1 bg-<?= $currStatus['color'] ?>-500/10 border border-<?= $currStatus['color'] ?>-500/20 text-<?= $currStatus['color'] ?>-400 rounded-full text-[9px] uppercase font-black italic tracking-widest inline-flex items-center gap-1">
                            <i class="fas fa-<?= $currStatus['icon'] ?> text-[8px]"></i>
                            <?= $currStatus['label'] ?>
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <p class="text-[11px] text-gray-200"><?= date('d M Y', strtotime($c['created_at'])) ?></p>
                        <p class="text-[9px] text-gray-600 font-mono italic">Submitted</p>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="<?= base_url('superadmin/advokasi/view/' . $c['id']) ?>" class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-gray-400 hover:bg-accent hover:text-black transition">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <?php if ($canWrite): ?>
                            <button onclick="confirmDelete(<?= $c['id'] ?>)" class="w-8 h-8 rounded-lg bg-white/5 flex items-center justify-center text-red-500/50 hover:bg-red-500 hover:text-white transition">
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

    <!-- Pagination -->
    <?php if ($pager && $pager->getPageCount() > 1): ?>
    <div class="px-4 py-4 border-t border-white/5 flex flex-col md:flex-row items-center justify-between gap-4">
        <p class="text-sm text-gray-500">Menampilkan halaman <?= $pager->getCurrentPage() ?> dari <?= $pager->getPageCount() ?></p>
        <div class="flex items-center gap-1 flex-wrap justify-center">
            <?= $pager->links('default', 'admin_pagination') ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
    function confirmDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus data pengaduan ini? Tindakan ini tidak dapat dibatalkan.')) {
            window.location.href = `<?= base_url('superadmin/advokasi/delete') ?>/${id}`;
        }
    }
</script>

<?= $this->endSection() ?>
