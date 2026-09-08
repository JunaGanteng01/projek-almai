<?php
$this->setVar('pageTitle', 'Verifikasi CWPA');
$this->setVar('pageSubtitle', 'Kelola dan verifikasi profil CWPA');
?>
<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-[#111] border border-white/10 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Total CWPA</p>
                    <p class="text-3xl font-bold text-white"><?= $stats['total'] ?></p>
                </div>
                <div class="w-12 h-12 bg-blue-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-500 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-[#111] border border-white/10 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Terverifikasi</p>
                    <p class="text-3xl font-bold text-accent"><?= $stats['approved'] ?></p>
                </div>
                <div class="w-12 h-12 bg-accent/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-accent text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-[#111] border border-white/10 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Belum Diverifikasi</p>
                    <p class="text-3xl font-bold text-yellow-500"><?= $stats['pending'] ?></p>
                </div>
                <div class="w-12 h-12 bg-yellow-500/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-500 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-6">
        <form method="get" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="<?= esc($search) ?>" placeholder="Cari nama, universitas, atau email..."
                    class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-accent focus:outline-none transition">
            </div>
            <select name="status" class="bg-black/50 border border-white/10 rounded-lg px-4 py-3 text-white focus:border-accent focus:outline-none transition">
                <option value="all" <?= $currentStatus === 'all' || !$currentStatus ? 'selected' : '' ?>>Semua Status</option>
                <option value="verified" <?= $currentStatus === 'verified' ? 'selected' : '' ?>>Terverifikasi</option>
                <option value="unverified" <?= $currentStatus === 'unverified' ? 'selected' : '' ?>>Belum Diverifikasi</option>
            </select>
            <button type="submit" class="px-6 py-3 bg-accent text-black font-bold rounded-lg hover:bg-white transition">
                <i class="fas fa-search mr-2"></i> Cari
            </button>
        </form>
    </div>

    <!-- CWPA List -->
    <div class="bg-[#111] border border-white/10 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/10 bg-black/50">
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-10">#</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">User Info</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">WhatsApp</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Payment</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal Daftar</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    <?php if (!empty($cwpaList)): ?>
                        <?php 
                        $page = request()->getGet('page') ?? 1;
                        $perPage = 20; 
                        $no = (($page - 1) * $perPage) + 1;
                        foreach ($cwpaList as $cwpa): ?>
                            <tr class="hover:bg-white/5 transition">
                                <td class="px-6 py-4 text-sm text-gray-500 font-medium"><?= $no++ ?></td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div>
                                            <p class="font-medium text-white"><?= esc($cwpa['user_name']) ?></p>
                                            <p class="text-xs text-gray-500"><?= esc($cwpa['user_email']) ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-300">
                                    <?= !empty($cwpa['whatsapp']) ? esc($cwpa['whatsapp']) : '-' ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($cwpa['payment_status'] === 'paid'): ?>
                                        <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-bold">
                                            Lunas
                                        </span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 bg-red-500/20 text-red-400 rounded-full text-xs font-bold">
                                            Belum Bayar
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($cwpa['status'] === 'approved'): ?>
                                        <div class="flex items-center gap-2">
                                            <span class="px-3 py-1 bg-accent/20 text-accent rounded-full text-xs font-bold flex items-center gap-1">
                                                <i class="fas fa-check-circle"></i> Approved
                                            </span>
                                        </div>
                                    <?php elseif ($cwpa['status'] === 'rejected'): ?>
                                        <span class="px-3 py-1 bg-red-500/20 text-red-400 rounded-full text-xs font-bold flex items-center gap-1 w-fit">
                                            <i class="fas fa-times-circle"></i> Rejected
                                        </span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full text-xs font-bold flex items-center gap-1 w-fit">
                                            <i class="fas fa-clock"></i> Pending
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-400">
                                    <?= date('d M Y H:i', strtotime($cwpa['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="<?= base_url('admin/cwpa/verification/' . $cwpa['id']) ?>"
                                        class="px-3 py-2 bg-blue-500/20 text-blue-400 rounded-lg hover:bg-blue-500/30 transition text-xs font-medium inline-flex items-center gap-1">
                                        <i class="fas fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <i class="fas fa-inbox text-4xl text-gray-600"></i>
                                    <p class="text-gray-400">Tidak ada data CWPA</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <?php if ($pager): ?>
        <div class="flex justify-center">
            <?= $pager->links('default', 'admin_pagination') ?>
        </div>
    <?php endif; ?>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="fixed inset-0 bg-black/80 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-[#111] border-b border-white/10 p-6 flex items-center justify-between">
            <h3 class="text-xl font-bold text-white">Detail CWPA</h3>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-white transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <div id="detailContent" class="p-6">
            <!-- Loading -->
            <div class="flex items-center justify-center py-12">
                <i class="fas fa-spinner fa-spin text-accent text-2xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Verify Modal -->
<div id="verifyModal" class="fixed inset-0 bg-black/80 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl max-w-md w-full">
        <div class="border-b border-white/10 p-6">
            <h3 class="text-xl font-bold text-white">Verifikasi CWPA</h3>
        </div>

        <form id="verifyForm" class="p-6 space-y-4">
            <input type="hidden" id="verifyCwpaId" name="cwpa_id">

            <div>
                <p class="text-sm text-gray-400 mb-2">Nama CWPA:</p>
                <p id="verifyCwpaName" class="font-bold text-white"></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Catatan Verifikasi (Opsional)</label>
                <textarea name="verification_notes" placeholder="Masukkan catatan verifikasi..."
                    class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-accent focus:outline-none transition resize-none"
                    rows="4"></textarea>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeVerifyModal()"
                    class="flex-1 px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white font-medium hover:bg-white/10 transition">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-3 bg-accent text-black font-bold rounded-lg hover:bg-white transition">
                    <i class="fas fa-check mr-2"></i> Verifikasi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openDetailModal(cwpaId) {
        const modal = document.getElementById('detailModal');
        const content = document.getElementById('detailContent');

        modal.classList.remove('hidden');

        // Fetch CWPA details
        fetch(`<?= base_url('admin/cwpa/') ?>${cwpaId}`)
            .then(response => response.text())
            .then(html => {
                content.innerHTML = html;
            })
            .catch(error => {
                content.innerHTML = '<p class="text-red-400">Gagal memuat detail CWPA</p>';
            });
    }

    function closeDetailModal() {
        document.getElementById('detailModal').classList.add('hidden');
    }

    function openVerifyModal(cwpaId, cwpaName) {
        document.getElementById('verifyCwpaId').value = cwpaId;
        document.getElementById('verifyCwpaName').textContent = cwpaName;
        document.getElementById('verifyModal').classList.remove('hidden');
    }

    function closeVerifyModal() {
        document.getElementById('verifyModal').classList.add('hidden');
        document.getElementById('verifyForm').reset();
    }

    document.getElementById('verifyForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const cwpaId = document.getElementById('verifyCwpaId').value;
        const notes = document.querySelector('textarea[name="verification_notes"]').value;

        fetch(`<?= base_url('admin/cwpa/verify/') ?>${cwpaId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `verification_notes=${encodeURIComponent(notes)}&<?= csrf_token() ?>=<?= csrf_hash() ?>`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeVerifyModal();
                    location.reload();
                } else {
                    alert('Gagal memverifikasi CWPA: ' + data.message);
                }
            })
            .catch(error => {
                alert('Terjadi kesalahan: ' + error.message);
            });
    });

    function unverify(cwpaId, cwpaName) {
        if (!confirm(`Batalkan verifikasi untuk ${cwpaName}?`)) return;

        fetch(`<?= base_url('admin/cwpa/unverify/') ?>${cwpaId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Gagal membatalkan verifikasi: ' + data.message);
                }
            })
            .catch(error => {
                alert('Terjadi kesalahan: ' + error.message);
            });
    }

    // Close modals on background click
    document.getElementById('detailModal').addEventListener('click', function(e) {
        if (e.target === this) closeDetailModal();
    });

    document.getElementById('verifyModal').addEventListener('click', function(e) {
        if (e.target === this) closeVerifyModal();
    });

    // Close on ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDetailModal();
            closeVerifyModal();
        }
    });

    function viewDocument(url, title) {
        const ext = url.split('.').pop().toLowerCase();
        const images = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (images.includes(ext)) {
            let modal = document.getElementById('docViewerModal');
            if (!modal) {
                modal = document.createElement('div');
                modal.id = 'docViewerModal';
                modal.className = 'fixed inset-0 bg-black/95 z-[9999] flex items-center justify-center p-4';
                modal.innerHTML = `
                <div class="relative max-w-5xl max-h-[90vh]">
                    <button onclick="closeDocModal()" class="absolute -top-12 right-0 px-4 py-2 bg-white/10 rounded-full text-white hover:bg-white/20 transition">
                        <i class="fas fa-times mr-2"></i> Close
                    </button>
                    <img id="docViewerImg" src="" class="max-w-full max-h-[90vh] object-contain rounded border border-white/10 text-white">
                    <p id="docViewerTitle" class="text-center text-white mt-4 font-bold"></p>
                </div>
            `;
                document.body.appendChild(modal);
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) closeDocModal();
                });
            }

            document.getElementById('docViewerImg').src = url;
            document.getElementById('docViewerTitle').textContent = title;
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
        } else {
            window.open(url, '_blank');
        }
    }

    function closeDocModal() {
        const modal = document.getElementById('docViewerModal');
        if (modal) modal.style.display = 'none';
    }
</script>
<?= $this->endSection() ?>