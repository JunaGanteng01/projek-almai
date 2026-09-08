<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Manajemen EA Lisensi</h1>
    </div>

    <!-- Search & Filters -->
    <div class="bg-[#111] p-4 rounded-xl border border-white/10 mb-6">
        <form action="" method="get" class="flex gap-4">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
                <input type="text" name="search" value="<?= esc($search) ?>" placeholder="Cari lisensi, akun trading, broker, atau user..."
                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-2 pl-12 focus:border-accent focus:outline-none">
            </div>
            <button type="submit" class="bg-accent text-black px-6 py-2 rounded-xl font-bold hover:bg-white transition">
                Cari
            </button>
        </form>
    </div>

    <!-- License Table -->
    <div class="bg-[#111] rounded-2xl border border-white/10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-black/50 text-left border-b border-white/10">
                        <th class="p-4">No</th>
                        <th class="p-4">User</th>
                        <th class="p-4">License Key</th>
                        <th class="p-4">Broker / Akun</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Expired</th>
                        <th class="p-4">Dibuat</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php if (empty($licenses)): ?>
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-500">Belum ada data lisensi.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($licenses as $i => $lic): ?>
                            <tr class="hover:bg-white/5 transition">
                                <td class="p-4"><?= $i + 1 + (20 * ($currentPage - 1)) ?></td>
                                <td class="p-4">
                                    <div class="font-bold"><?= esc($lic['user_name']) ?></div>
                                    <div class="text-xs text-gray-500"><?= esc($lic['user_email']) ?></div>
                                </td>
                                <td class="p-4 font-mono text-accent"><?= esc($lic['license_key']) ?></td>
                                <td class="p-4">
                                    <div><?= esc($lic['broker_name'] ?: '-') ?></div>
                                    <div class="text-xs text-gray-500">Acc: <?= esc($lic['account_trading_number']) ?></div>
                                </td>
                                <td class="p-4">
                                    <?php if ($lic['status'] === 'active'): ?>
                                        <span class="bg-green-500/20 text-green-400 px-2 py-1 rounded text-xs border border-green-500/30">Active</span>
                                    <?php else: ?>
                                        <span class="bg-red-500/20 text-red-400 px-2 py-1 rounded text-xs border border-red-500/30"><?= esc(ucfirst($lic['status'])) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4">
                                    <?php if ($lic['expires_at']): ?>
                                        <?= date('d M Y', strtotime($lic['expires_at'])) ?>
                                    <?php else: ?>
                                        <span class="text-infinity">Lifetime</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4 text-gray-400 text-sm">
                                    <?= date('d M Y H:i', strtotime($lic['created_at'])) ?>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <button type="button" onclick="editLicense('<?= $lic['id'] ?>', '<?= esc($lic['license_key']) ?>', '<?= esc($lic['account_trading_number']) ?>')" 
                                            class="w-8 h-8 rounded-lg bg-accent/20 text-accent flex items-center justify-center hover:bg-accent hover:text-black transition"
                                            title="Edit Lisensi">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" onclick="extendLicense('<?= $lic['id'] ?>', '<?= esc($lic['account_trading_number']) ?>')" 
                                            class="w-8 h-8 rounded-lg bg-blue-500/20 text-blue-500 flex items-center justify-center hover:bg-blue-500 hover:text-white transition"
                                            title="Perpanjang Lisensi">
                                            <i class="fas fa-calendar-plus"></i>
                                        </button>
                                        <button type="button" onclick="confirmDelete('<?= base_url('superadmin/ea-license/delete/' . $lic['id']) ?>')" 
                                            class="w-8 h-8 rounded-lg bg-red-500/20 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition"
                                            title="Hapus Lisensi">
                                            <i class="fas fa-trash"></i>
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
        <?php if ($pager): ?>
            <div class="p-4 border-t border-white/10">
                <?= $pager->links('ea_license', 'admin_pagination') ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Edit License Modal -->
<div id="editModal" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeEditModal()"></div>
    <div class="relative bg-[#0a0a0a] border border-white/10 rounded-2xl w-full max-w-md p-6 transform transition-all scale-95 opacity-0 duration-300" id="editModalContent">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-full bg-accent/20 text-accent flex items-center justify-center flex-shrink-0">
                <i class="fas fa-edit text-xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-white">Edit Lisensi EA</h3>
                <p class="text-sm text-gray-400">Ubah detail lisensi key dan akun</p>
            </div>
        </div>
        
        <form id="editForm" method="POST" action="">
            <?= csrf_field() ?>
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">License Key</label>
                    <input type="text" name="license_key" id="editLicenseKey" required
                        class="w-full bg-[#111] border border-white/20 rounded-xl px-4 py-3 text-white font-mono focus:border-accent focus:outline-none transition">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Account Trading Number</label>
                    <input type="text" name="account_trading_number" id="editAccNumber" required
                        class="w-full bg-[#111] border border-white/20 rounded-xl px-4 py-3 text-white font-bold focus:border-accent focus:outline-none transition">
                </div>
            </div>
            
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 rounded-xl border border-white/10 text-white hover:bg-white/5 transition font-medium">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-accent text-black hover:bg-white transition font-bold shadow-[0_0_15px_rgba(255,215,0,0.3)]">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Extend License Modal -->
<div id="extendModal" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeExtendModal()"></div>
    <div class="relative bg-[#0a0a0a] border border-white/10 rounded-2xl w-full max-w-md p-6 transform transition-all scale-95 opacity-0 duration-300" id="extendModalContent">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-full bg-blue-500/20 text-blue-500 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-calendar-plus text-xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-white">Perpanjang Lisensi</h3>
                <p class="text-sm text-gray-400">Tambah masa aktif EA Lisensi</p>
            </div>
        </div>
        
        <form id="extendForm" method="POST" action="">
            <?= csrf_field() ?>
            <div class="mb-6">
                <label class="block text-sm text-gray-400 mb-2">Berapa hari masa aktif tambahan untuk lisensi akun <strong id="extendAccNumber" class="text-accent text-lg block mt-1"></strong>?</label>
                <div class="relative">
                    <input type="number" name="days" id="extendDays" value="30" min="1" required
                        class="w-full bg-[#111] border border-white/20 rounded-xl px-4 py-3 text-white font-bold focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent transition">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Hari</span>
                </div>
            </div>
            
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeExtendModal()" class="px-5 py-2.5 rounded-xl border border-white/10 text-white hover:bg-white/5 transition font-medium">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-accent text-black hover:bg-white transition font-bold shadow-[0_0_15px_rgba(255,215,0,0.3)]">
                    Update & Perpanjang
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete License Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
    <div class="relative bg-[#0a0a0a] border border-red-500/30 rounded-2xl w-full max-w-md p-6 transform transition-all scale-95 opacity-0 duration-300" id="deleteModalContent">
        <div class="flex flex-col items-center text-center">
            <div class="w-16 h-16 rounded-full bg-red-500/20 text-red-500 flex items-center justify-center mb-4">
                <i class="fas fa-exclamation-triangle text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Hapus Lisensi?</h3>
            <p class="text-gray-400 mb-6">Tindakan ini tidak dapat dibatalkan. Data lisensi akan dihapus secara permanen dari sistem.</p>
            
            <form id="deleteForm" method="POST" action="" class="w-full flex gap-3">
                <?= csrf_field() ?>
                <button type="button" onclick="closeDeleteModal()" class="flex-1 px-4 py-2.5 rounded-xl border border-white/10 text-white hover:bg-white/5 transition font-medium">
                    Batal
                </button>
                <button type="submit" class="flex-1 px-4 py-2.5 rounded-xl bg-red-500 text-white hover:bg-red-600 transition font-bold shadow-[0_0_15px_rgba(239,68,68,0.4)]">
                    Ya, Hapus!
                </button>
            </form>
        </div>
    </div>
</div>

<script>
// Prevent form resubmission styling
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scaling animation to modals
    setTimeout(() => {
        document.getElementById('editModalContent').classList.remove('scale-95', 'opacity-0');
        document.getElementById('extendModalContent').classList.remove('scale-95', 'opacity-0');
        document.getElementById('deleteModalContent').classList.remove('scale-95', 'opacity-0');
    }, 50);
});

// Edit Modal Logic
function editLicense(id, licenseKey, acc) {
    const modal = document.getElementById('editModal');
    const content = document.getElementById('editModalContent');
    const form = document.getElementById('editForm');
    const keyInput = document.getElementById('editLicenseKey');
    const accInput = document.getElementById('editAccNumber');
    
    form.action = '<?= base_url('superadmin/ea-license/update/') ?>' + id;
    keyInput.value = licenseKey;
    accInput.value = acc;
    
    modal.classList.remove('hidden');
    
    // Animate in
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
        keyInput.focus();
    }, 10);
}

function closeEditModal() {
    const modal = document.getElementById('editModal');
    const content = document.getElementById('editModalContent');
    
    // Animate out
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Delete Modal Logic
function confirmDelete(url) {
    const modal = document.getElementById('deleteModal');
    const content = document.getElementById('deleteModalContent');
    const form = document.getElementById('deleteForm');
    
    form.action = url;
    modal.classList.remove('hidden');
    
    // Animate in
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    const content = document.getElementById('deleteModalContent');
    
    // Animate out
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Extend Modal Logic
function extendLicense(id, acc) {
    const modal = document.getElementById('extendModal');
    const content = document.getElementById('extendModalContent');
    const form = document.getElementById('extendForm');
    const accLabel = document.getElementById('extendAccNumber');
    const daysInput = document.getElementById('extendDays');
    
    form.action = '<?= base_url('superadmin/ea-license/extend/') ?>' + id;
    accLabel.textContent = acc;
    daysInput.value = 30; // Reset to default
    
    modal.classList.remove('hidden');
    
    // Animate in
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
        daysInput.focus();
    }, 10);
}

function closeExtendModal() {
    const modal = document.getElementById('extendModal');
    const content = document.getElementById('extendModalContent');
    
    // Animate out
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}
</script>
<?= $this->endSection() ?>