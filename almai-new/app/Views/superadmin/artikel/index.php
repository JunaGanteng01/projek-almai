<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-6">
    <a href="?filter=pending" class="bg-yellow-500/10 border <?= $currentFilter === 'pending' ? 'border-yellow-500' : 'border-yellow-500/30' ?> rounded-xl p-4 hover:border-yellow-500 transition">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-yellow-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-yellow-500"><?= $pendingCount ?></p>
                <p class="text-xs text-gray-400">Menunggu Verifikasi</p>
            </div>
        </div>
    </a>
    <a href="?filter=approved" class="bg-accent/10 border <?= $currentFilter === 'approved' ? 'border-accent' : 'border-accent/30' ?> rounded-xl p-4 hover:border-accent transition">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-accent/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-check text-accent"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-accent"><?= $approvedCount ?></p>
                <p class="text-xs text-gray-400">Disetujui</p>
            </div>
        </div>
    </a>
    <a href="?filter=rejected" class="bg-red-500/10 border <?= $currentFilter === 'rejected' ? 'border-red-500' : 'border-red-500/30' ?> rounded-xl p-4 hover:border-red-500 transition">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-red-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-times text-red-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-red-500"><?= $rejectedCount ?></p>
                <p class="text-xs text-gray-400">Ditolak</p>
            </div>
        </div>
    </a>
    <a href="?filter=all" class="bg-white/10 border <?= $currentFilter === 'all' ? 'border-white' : 'border-white/30' ?> rounded-xl p-4 hover:border-white transition">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-list text-white"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-white"><?= $totalCount ?></p>
                <p class="text-xs text-gray-400">Total Artikel</p>
            </div>
        </div>
    </a>
</div>

<!-- Filter Tabs -->
<div class="flex flex-col md:flex-row gap-4 mb-6 items-start md:items-center justify-between">
    <div class="flex gap-2 w-full md:w-auto overflow-x-auto pb-2 md:pb-0">
        <a href="?filter=all" class="flex-1 md:flex-none px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap text-center <?= $currentFilter === 'all' ? 'bg-accent text-black' : 'bg-white/10 hover:bg-white/20' ?>">Semua</a>
        <a href="?filter=pending" class="flex-1 md:flex-none px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap text-center <?= $currentFilter === 'pending' ? 'bg-yellow-500 text-black' : 'bg-white/10 hover:bg-white/20' ?>">
            Pending <?php if ($pendingCount > 0): ?><span class="ml-1 px-2 py-0.5 bg-black/30 rounded-full text-xs"><?= $pendingCount ?></span><?php endif; ?>
        </a>
        <a href="?filter=approved" class="flex-1 md:flex-none px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap text-center <?= $currentFilter === 'approved' ? 'bg-accent text-black' : 'bg-white/10 hover:bg-white/20' ?>">Approved</a>
        <a href="?filter=rejected" class="flex-1 md:flex-none px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap text-center <?= $currentFilter === 'rejected' ? 'bg-red-500 text-white' : 'bg-white/10 hover:bg-white/20' ?>">Rejected</a>
    </div>
    <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
        <select onchange="window.location.href='?filter=<?= $currentFilter ?>&category='+this.value" class="w-full sm:w-auto bg-[#111] border border-white/20 rounded-xl px-4 py-2 text-sm focus:border-accent focus:outline-none">
            <option value="">Semua Kategori</option>
            <?php foreach ($categories as $cat): ?>
            <option value="<?= esc($cat) ?>" <?= $currentCategory === $cat ? 'selected' : '' ?>><?= esc($cat) ?></option>
            <?php endforeach; ?>
        </select>
        <a href="<?= base_url('superadmin/artikel/create') ?>" class="w-full sm:w-auto px-4 py-2 bg-accent text-black rounded-xl font-bold text-sm hover:bg-white transition text-center whitespace-nowrap">
            <i class="fas fa-plus mr-2"></i> Tambah Artikel
        </a>
    </div>
</div>

<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
    <?php if (empty($artikelList)): ?>
    <div class="p-8 text-center text-gray-500">
        <i class="fas fa-newspaper text-4xl mb-4"></i>
        <p>Tidak ada artikel</p>
    </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-black/50">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400 w-12">No</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Artikel</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">WPA</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Harga Poin</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Status</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Verifikasi</th>
                    <th class="text-center px-4 py-3 text-xs font-medium text-gray-400">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = ($pager->getCurrentPage() - 1) * 20 + 1; ?>
                <?php foreach ($artikelList as $artikel): ?>
                <tr class="border-t border-white/5 hover:bg-white/5">
                    <td class="px-4 py-3 text-sm text-gray-400"><?= $no++ ?></td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <img src="<?= esc($artikel['thumbnail']) ?>" alt="" class="w-16 h-12 object-cover rounded-lg">
                            <div>
                                <p class="font-medium text-sm line-clamp-1"><?= esc($artikel['title']) ?></p>
                                <p class="text-xs text-gray-500"><?= esc($artikel['category']) ?> • <?= esc($artikel['read_time']) ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <img src="<?= esc($artikel['wpa_photo'] ?? 'https://ui-avatars.com/api/?name=WPA') ?>" alt="" class="w-8 h-8 rounded-full object-cover">
                            <span class="text-sm"><?= esc($artikel['wpa_name'] ?? '-') ?></span>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <?php if ($artikel['is_free'] || $artikel['poin_price'] == 0): ?>
                        <span class="px-2 py-1 bg-accent/20 text-accent rounded-full text-xs">GRATIS</span>
                        <?php else: ?>
                        <span class="text-yellow-500 font-bold"><?= number_format($artikel['poin_price']) ?> <i class="fas fa-coins text-xs"></i></span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <?php if ($artikel['status'] === 'published'): ?>
                        <span class="px-2 py-1 bg-accent/20 text-accent rounded-full text-xs">Published</span>
                        <?php else: ?>
                        <span class="px-2 py-1 bg-gray-500/20 text-gray-400 rounded-full text-xs">Draft</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <?php if ($artikel['verification_status'] === 'approved'): ?>
                        <span class="px-2 py-1 bg-accent/20 text-accent rounded-full text-xs"><i class="fas fa-check mr-1"></i>Approved</span>
                        <?php elseif ($artikel['verification_status'] === 'rejected'): ?>
                        <span class="px-2 py-1 bg-red-500/20 text-red-400 rounded-full text-xs" title="<?= esc($artikel['rejection_reason']) ?>"><i class="fas fa-times mr-1"></i>Rejected</span>
                        <?php else: ?>
                        <span class="px-2 py-1 bg-yellow-500/20 text-yellow-500 rounded-full text-xs"><i class="fas fa-clock mr-1"></i>Pending</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-2">
                            <?php if ($artikel['verification_status'] === 'pending'): ?>
                            <form action="<?= base_url('superadmin/artikel/approve/' . $artikel['id']) ?>" method="post" class="inline">
                                <?= csrf_field() ?>
                                <button type="submit" class="p-2 bg-accent/20 text-accent rounded-lg hover:bg-accent hover:text-black transition" title="Setujui">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            <button onclick="showRejectModal(<?= $artikel['id'] ?>, '<?= esc($artikel['title']) ?>')" class="p-2 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500 hover:text-white transition" title="Tolak">
                                <i class="fas fa-times"></i>
                            </button>
                            <?php endif; ?>
                            <a href="<?= base_url('superadmin/artikel/edit/' . $artikel['id']) ?>" class="p-2 bg-blue-500/20 text-blue-400 rounded-lg hover:bg-blue-500 hover:text-white transition" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?= base_url('superadmin/artikel/delete/' . $artikel['id']) ?>" method="post" class="inline" onsubmit="return confirm('Hapus artikel ini?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="p-2 bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500 hover:text-white transition" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if ($pager->getPageCount() > 1): ?>
    <div class="p-4 border-t border-white/10 flex items-center justify-between">
        <p class="text-sm text-gray-500">Menampilkan <?= count($artikelList) ?> dari <?= $pager->getTotal() ?> data</p>
        <div class="flex gap-1">
            <?= $pager->links('default', 'admin_pagination') ?>
        </div>
    </div>
    <?php endif; ?>
    <?php endif; ?>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-sm">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 max-w-md mx-4 w-full">
        <h3 class="text-lg font-bold mb-4">Tolak Artikel</h3>
        <p class="text-gray-400 text-sm mb-4">Artikel: <span id="rejectArtikelTitle" class="text-white"></span></p>
        <form id="rejectForm" method="post">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="block text-sm text-gray-400 mb-2">Alasan Penolakan</label>
                <textarea name="reason" rows="3" required class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm" placeholder="Jelaskan alasan penolakan..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeRejectModal()" class="flex-1 py-3 border border-white/20 rounded-xl font-bold hover:bg-white/10 transition">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-red-500 text-white rounded-xl font-bold hover:bg-red-600 transition">Tolak</button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal(id, title) {
    document.getElementById('rejectArtikelTitle').textContent = title;
    document.getElementById('rejectForm').action = '<?= base_url('superadmin/artikel/reject/') ?>' + id;
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectModal').classList.add('flex');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectModal').classList.remove('flex');
}
</script>
<?= $this->endSection() ?>
