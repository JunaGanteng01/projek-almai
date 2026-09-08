<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="mb-6 lg:mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold bg-gradient-to-r from-white to-gray-400 bg-clip-text text-transparent">
            <?= esc($pageTitle) ?>
        </h1>
        <p class="text-sm text-gray-400 mt-1"><?= esc($pageSubtitle) ?></p>
    </div>
    
    <div class="flex flex-col sm:flex-row gap-3">
        <form action="<?= base_url('superadmin/feedback') ?>" method="GET" class="flex items-center">
            <div class="relative">
                <input type="text" name="search" value="<?= isset($search) ? esc($search) : '' ?>" placeholder="Cari pesan atau user..." class="bg-[#1a1a1a] border border-white/10 rounded-lg pl-10 pr-4 py-2 text-sm text-white focus:outline-none focus:border-white/30 transition w-full sm:w-64">
                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                    <i class="fas fa-search"></i>
                </div>
            </div>
            <button type="submit" class="hidden"></button>
        </form>
        <a href="<?= base_url('superadmin/feedback/export' . (isset($search) && $search ? '?search=' . urlencode($search) : '')) ?>" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center justify-center gap-2">
            <i class="fas fa-file-excel"></i> Export xlsx
        </a>
    </div>
</div>

<div class="bg-[#111] border border-white/10 rounded-2xl p-6 relative">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-500/10 border border-green-500/30 text-green-500 p-4 rounded-xl text-sm mb-4">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-500/10 border border-red-500/30 text-red-500 p-4 rounded-xl text-sm mb-4">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead>
                <tr class="border-b border-white/10 text-gray-400">
                    <th class="px-4 py-3 font-medium">Tanggal</th>
                    <th class="px-4 py-3 font-medium">User</th>
                    <th class="px-4 py-3 font-medium">Rating</th>
                    <th class="px-4 py-3 font-medium">Pesan (Kritik & Saran)</th>
                    <th class="px-4 py-3 font-medium text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($feedbacks)): ?>
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">Belum ada data feedback.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($feedbacks as $item): ?>
                        <tr class="hover:bg-white/5 transition">
                            <td class="px-4 py-4 text-gray-300">
                                <?= date('d M Y, H:i', strtotime($item['created_at'])) ?>
                            </td>
                            <td class="px-4 py-4">
                                <div class="font-medium text-white"><?= esc($item['name']) ?></div>
                                <div class="text-xs text-gray-500"><?= esc($item['email']) ?></div>
                                <div class="text-xs text-gray-500"><?= esc($item['phone']) ?></div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-1 text-yellow-400">
                                    <?php for ($i = 0; $i < $item['rating']; $i++): ?>
                                        <i class="fas fa-star text-xs"></i>
                                    <?php endfor; ?>
                                    <?php for ($i = $item['rating']; $i < 5; $i++): ?>
                                        <i class="fas fa-star text-xs text-gray-700"></i>
                                    <?php endfor; ?>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-gray-300 min-w-[200px] max-w-sm">
                                    <div class="line-clamp-2 text-sm">
                                        <?= esc($item['message']) ?>
                                    </div>
                                    <?php if (strlen($item['message']) > 80): ?>
                                        <button type="button" onclick="showFeedbackDetail(`<?= htmlspecialchars($item['name'], ENT_QUOTES) ?>`, `<?= htmlspecialchars(nl2br($item['message']), ENT_QUOTES) ?>`)" class="text-blue-400 hover:text-blue-300 text-xs mt-1 underline">Lihat Detail</button>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" onclick="showFeedbackDetail(`<?= htmlspecialchars($item['name'], ENT_QUOTES) ?>`, `<?= htmlspecialchars(nl2br($item['message']), ENT_QUOTES) ?>`)" class="text-blue-500 hover:text-blue-400 transition" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <form action="<?= base_url('superadmin/feedback/delete/' . $item['id']) ?>" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus feedback ini?');">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="text-red-500 hover:text-red-400 transition" title="Hapus Feedback">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Detail Feedback -->
<div id="feedbackModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl transform scale-95 opacity-0 transition-all duration-300" id="feedbackModalContent">
        <div class="flex items-center justify-between p-5 border-b border-white/10 bg-[#1a1a1a]">
            <h3 class="text-lg font-semibold text-white">Detail Feedback</h3>
            <button type="button" onclick="closeFeedbackDetail()" class="text-gray-400 hover:text-white transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <div class="mb-2 text-sm text-gray-400">Dari: <span id="feedbackModalUser" class="text-white font-medium"></span></div>
            <div class="bg-[#1a1a1a] p-4 rounded-xl border border-white/5 text-gray-300 text-sm leading-relaxed max-h-64 overflow-y-auto" id="feedbackModalMessage">
                
            </div>
        </div>
        <div class="p-5 border-t border-white/10 flex justify-end">
            <button type="button" onclick="closeFeedbackDetail()" class="bg-white/10 hover:bg-white/20 text-white px-5 py-2 rounded-lg text-sm font-medium transition">Tutup</button>
        </div>
    </div>
</div>

<script>
function showFeedbackDetail(user, message) {
    const modal = document.getElementById('feedbackModal');
    const modalContent = document.getElementById('feedbackModalContent');
    
    document.getElementById('feedbackModalUser').textContent = user;
    document.getElementById('feedbackModalMessage').innerHTML = message;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    // Trigger animation
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeFeedbackDetail() {
    const modal = document.getElementById('feedbackModal');
    const modalContent = document.getElementById('feedbackModalContent');
    
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }, 300);
}

// Close on outside click
document.getElementById('feedbackModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeFeedbackDetail();
    }
});
</script>

<?= $this->endSection() ?>
