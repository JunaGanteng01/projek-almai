<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h2 class="text-lg md:text-xl font-bold">Penukaran Merchandise</h2>
        <p class="text-gray-500 text-sm">Kelola penukaran poin ke merchandise</p>
    </div>
    <a href="<?= base_url('admin/merchandise') ?>" class="px-4 py-2 border border-white/20 rounded-xl hover:border-accent hover:text-accent transition text-sm">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Merchandise
    </a>
</div>

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6">
    <a href="<?= base_url('admin/merchandise/redemptions') ?>" class="bg-[#111] border <?= !$filter || $filter === 'all' ? 'border-accent' : 'border-white/10' ?> rounded-xl p-4 hover:border-accent/50 transition">
        <p class="text-2xl font-bold"><?= $stats['total'] ?></p>
        <p class="text-xs text-gray-500">Total</p>
    </a>
    <a href="<?= base_url('admin/merchandise/redemptions?status=pending') ?>" class="bg-[#111] border <?= $filter === 'pending' ? 'border-yellow-500' : 'border-white/10' ?> rounded-xl p-4 hover:border-yellow-500/50 transition">
        <p class="text-2xl font-bold text-yellow-500"><?= $stats['pending'] ?></p>
        <p class="text-xs text-gray-500">Pending</p>
    </a>
    <a href="<?= base_url('admin/merchandise/redemptions?status=processing') ?>" class="bg-[#111] border <?= $filter === 'processing' ? 'border-blue-500' : 'border-white/10' ?> rounded-xl p-4 hover:border-blue-500/50 transition">
        <p class="text-2xl font-bold text-blue-500"><?= $stats['processing'] ?></p>
        <p class="text-xs text-gray-500">Diproses</p>
    </a>
    <a href="<?= base_url('admin/merchandise/redemptions?status=shipped') ?>" class="bg-[#111] border <?= $filter === 'shipped' ? 'border-purple-500' : 'border-white/10' ?> rounded-xl p-4 hover:border-purple-500/50 transition">
        <p class="text-2xl font-bold text-purple-500"><?= $stats['shipped'] ?></p>
        <p class="text-xs text-gray-500">Dikirim</p>
    </a>
    <a href="<?= base_url('admin/merchandise/redemptions?status=completed') ?>" class="bg-[#111] border <?= $filter === 'completed' ? 'border-accent' : 'border-white/10' ?> rounded-xl p-4 hover:border-accent/50 transition">
        <p class="text-2xl font-bold text-accent"><?= $stats['completed'] ?></p>
        <p class="text-xs text-gray-500">Selesai</p>
    </a>
</div>

<!-- Redemptions Table -->
<div class="bg-[#111] border border-white/10 rounded-xl overflow-hidden">
    <?php if (empty($redemptions)): ?>
    <div class="p-12 text-center">
        <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-exchange-alt text-gray-600 text-2xl"></i>
        </div>
        <p class="text-gray-500">Belum ada penukaran merchandise</p>
    </div>
    <?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full min-w-[800px]">
            <thead class="bg-black/50">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Tanggal</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">User</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Merchandise</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Poin</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Status</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Resi</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($redemptions as $item): ?>
                <tr class="border-t border-white/5 hover:bg-white/5">
                    <td class="px-4 py-3 text-sm text-gray-400"><?= date('d M Y H:i', strtotime($item['created_at'])) ?></td>
                    <td class="px-4 py-3">
                        <p class="text-sm font-medium"><?= esc($item['user_name']) ?></p>
                        <p class="text-xs text-gray-500"><?= esc($item['user_email']) ?></p>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <div class="w-10 h-10 bg-gray-800 rounded-lg overflow-hidden">
                                <?php if (!empty($item['merchandise_image'])): ?>
                                <img src="<?= base_url($item['merchandise_image']) ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class="fas fa-image text-gray-600 text-sm"></i>
                                </div>
                                <?php endif; ?>
                            </div>
                            <span class="text-sm"><?= esc($item['merchandise_name']) ?></span>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-yellow-500 font-bold text-sm"><i class="fas fa-coins mr-1"></i> <?= number_format($item['points_used']) ?></span>
                    </td>
                    <td class="px-4 py-3">
                        <?php
                        $statusColors = [
                            'pending' => 'bg-yellow-500/20 text-yellow-500',
                            'processing' => 'bg-blue-500/20 text-blue-400',
                            'shipped' => 'bg-purple-500/20 text-purple-400',
                            'completed' => 'bg-accent/20 text-accent',
                            'cancelled' => 'bg-red-500/20 text-red-400',
                        ];
                        $statusLabels = [
                            'pending' => 'Pending',
                            'processing' => 'Diproses',
                            'shipped' => 'Dikirim',
                            'completed' => 'Selesai',
                            'cancelled' => 'Dibatalkan',
                        ];
                        ?>
                        <span class="px-2 py-1 rounded-full text-xs <?= $statusColors[$item['status']] ?? 'bg-gray-500/20 text-gray-400' ?>">
                            <?= $statusLabels[$item['status']] ?? $item['status'] ?>
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-400">
                        <?= esc($item['tracking_number'] ?? '-') ?>
                    </td>
                    <td class="px-4 py-3">
                        <button onclick="openUpdateModal(<?= htmlspecialchars(json_encode($item)) ?>)" class="px-3 py-1 border border-white/20 rounded-lg text-xs hover:border-accent hover:text-accent transition">
                            <i class="fas fa-edit mr-1"></i> Update
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<!-- Update Status Modal -->
<div id="updateModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 max-w-md w-full">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold">Update Status</h3>
            <button onclick="closeUpdateModal()" class="text-gray-400 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="updateForm" method="post">
            <?= csrf_field() ?>
            
            <div class="mb-4">
                <p class="text-sm text-gray-400 mb-1">User</p>
                <p class="font-medium" id="modalUserName">-</p>
            </div>
            
            <div class="mb-4">
                <p class="text-sm text-gray-400 mb-1">Merchandise</p>
                <p class="font-medium" id="modalMerchName">-</p>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm text-gray-400 mb-2">Status</label>
                <select name="status" id="modalStatus" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
                    <option value="pending">Pending</option>
                    <option value="processing">Diproses</option>
                    <option value="shipped">Dikirim</option>
                    <option value="completed">Selesai</option>
                    <option value="cancelled">Dibatalkan</option>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm text-gray-400 mb-2">No. Resi (opsional)</label>
                <input type="text" name="tracking_number" id="modalTracking"
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                    placeholder="JNE123456789">
            </div>
            
            <div class="mb-6">
                <label class="block text-sm text-gray-400 mb-2">Catatan (opsional)</label>
                <textarea name="notes" id="modalNotes" rows="2"
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                    placeholder="Catatan..."></textarea>
            </div>
            
            <div class="flex gap-3">
                <button type="button" onclick="closeUpdateModal()" class="flex-1 py-3 border border-white/20 rounded-xl hover:border-white/40 transition">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                    <i class="fas fa-save mr-2"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openUpdateModal(item) {
    document.getElementById('updateForm').action = '<?= base_url('admin/merchandise/redemptions/update/') ?>' + item.id;
    document.getElementById('modalUserName').textContent = item.user_name;
    document.getElementById('modalMerchName').textContent = item.merchandise_name;
    document.getElementById('modalStatus').value = item.status;
    document.getElementById('modalTracking').value = item.tracking_number || '';
    document.getElementById('modalNotes').value = item.notes || '';
    
    const modal = document.getElementById('updateModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeUpdateModal() {
    const modal = document.getElementById('updateModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>
<?= $this->endSection() ?>
