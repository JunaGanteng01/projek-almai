<?= $this->extend('wpa/layouts/main') ?>

<?= $this->section('content') ?>
<?php $pageTitle = 'Layanan Saya'; $pageSubtitle = 'Kelola layanan yang Anda buat'; ?>

<!-- Stats Cards -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-[#111] border border-white/10 rounded-xl p-4 md:p-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-accent/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-graduation-cap text-accent"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold"><?= $totalKelas ?></h3>
        <p class="text-gray-500 text-xs">Total Layanan</p>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-4 md:p-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-check-circle text-green-400"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold"><?= $activeKelas ?></h3>
        <p class="text-gray-500 text-xs">Layanan Aktif</p>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-4 md:p-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-blue-400"></i>
            </div>
        </div>
        <h3 class="text-2xl font-bold"><?= number_format($totalStudents) ?></h3>
        <p class="text-gray-500 text-xs">Total Students</p>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-4 md:p-6">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-10 h-10 bg-orange-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-wallet text-orange-400"></i>
            </div>
        </div>
        <h3 class="text-lg md:text-xl font-bold">Rp <?= number_format($totalRevenue, 0, ',', '.') ?></h3>
        <p class="text-gray-500 text-xs">Total Revenue</p>
    </div>
</div>

<!-- Header Actions -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <!-- Search & Filter -->
    <form action="<?= base_url('wpa/dashboard/layanan') ?>" method="get" class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
        <div class="relative flex-1 md:w-64">
            <input type="text" name="search" value="<?= esc($search ?? '') ?>" placeholder="Cari layanan..." 
                class="w-full bg-black border border-white/20 rounded-lg px-4 py-2 pl-10 text-sm focus:border-accent focus:outline-none">
            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm"></i>
        </div>
        <select name="status" onchange="this.form.submit()" class="bg-black border border-white/20 rounded-lg px-4 py-2 text-sm focus:border-accent focus:outline-none">
            <option value="all" <?= ($currentStatus ?? '') === 'all' ? 'selected' : '' ?>>Semua Status</option>
            <option value="active" <?= ($currentStatus ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
            <option value="draft" <?= ($currentStatus ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
            <option value="inactive" <?= ($currentStatus ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
        </select>
    </form>
    
    <!--
    <a href="<?= base_url('wpa/dashboard/layanan/create') ?>" class="w-full md:w-auto px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-center">
        <i class="fas fa-plus mr-2"></i> Buat Layanan Baru
    </a>
    -->
</div>

<!-- Kelas Grid -->
<?php if (empty($kelasList)): ?>
<div class="bg-[#111] border border-white/10 rounded-2xl p-12 text-center">
    <div class="text-gray-500">
        <i class="fas fa-graduation-cap text-5xl mb-4"></i>
        <p class="text-lg mb-2">Belum ada layanan</p>
        <p class="text-sm">Layanan Anda akan muncul di sini setelah dibuat oleh Admin.</p>
    </div>
</div>
<?php else: ?>
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
    <?php foreach ($kelasList as $kelas): ?>
    <div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden hover:border-accent/50 transition group">
        <!-- Thumbnail -->
        <div class="relative aspect-video overflow-hidden">
            <?php 
            $thumbUrl = $kelas['thumbnail'];
            if (strpos($thumbUrl, 'uploads/') === 0) {
                $thumbUrl = base_url('file/' . $thumbUrl);
            }
            ?>
            <img src="<?= esc($thumbUrl) ?>" alt="<?= esc($kelas['title']) ?>" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
            
            <!-- Status Badge -->
            <div class="absolute top-3 left-3">
                <?php if ($kelas['status'] === 'active'): ?>
                <span class="px-2 py-1 bg-accent/90 text-black text-xs font-bold rounded-full">Active</span>
                <?php elseif ($kelas['status'] === 'draft'): ?>
                <span class="px-2 py-1 bg-yellow-500/90 text-black text-xs font-bold rounded-full">Draft</span>
                <?php else: ?>
                <span class="px-2 py-1 bg-gray-500/90 text-white text-xs font-bold rounded-full">Inactive</span>
                <?php endif; ?>
            </div>
            
            <!-- Mode Badge -->
            <div class="absolute top-3 right-3">
                <span class="px-2 py-1 bg-black/70 text-white text-xs rounded-full">
                    <i class="fas <?= $kelas['mode'] === 'Online' ? 'fa-video' : 'fa-building' ?> mr-1"></i>
                    <?= esc($kelas['mode']) ?>
                </span>
            </div>
        </div>
        
        <!-- Content -->
        <div class="p-4">
            <div class="flex items-center gap-2 mb-2">
                <span class="px-2 py-0.5 bg-accent/20 text-accent text-xs rounded-full"><?= esc($kelas['category']) ?></span>
                <span class="text-gray-500 text-xs"><?= esc($kelas['level'] ?? 'All Level') ?></span>
            </div>
            
            <h3 class="font-bold mb-2 line-clamp-2"><?= esc($kelas['title']) ?></h3>
            
            <!-- Stats -->
            <div class="flex items-center gap-4 text-sm text-gray-400 mb-3">
                <span><i class="fas fa-users mr-1"></i> <?= $kelas['students'] ?? 0 ?></span>
                <span><i class="fas fa-star text-yellow-500 mr-1"></i> <?= $kelas['rating'] ?? '0.0' ?></span>
                <span><i class="fas fa-clock mr-1"></i> <?= esc($kelas['duration'] ?? '-') ?></span>
            </div>
            
            <!-- Price & Revenue -->
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-accent font-bold">Rp <?= number_format($kelas['price'], 0, ',', '.') ?></p>
                    <?php if (!empty($kelas['original_price']) && $kelas['original_price'] > $kelas['price']): ?>
                    <p class="text-xs text-gray-500 line-through">Rp <?= number_format($kelas['original_price'], 0, ',', '.') ?></p>
                    <?php endif; ?>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500">Revenue</p>
                    <p class="text-sm font-medium text-green-400">Rp <?= number_format($kelas['total_revenue'] ?? 0, 0, ',', '.') ?></p>
                </div>
            </div>
            
            <!-- Sales Info -->
            <div class="flex items-center justify-between text-xs text-gray-500 mb-4 pb-4 border-b border-white/10">
                <span><i class="fas fa-shopping-cart mr-1"></i> <?= $kelas['total_sales'] ?? 0 ?> terjual</span>
                <?php if (!empty($kelas['next_session'])): ?>
                <span><i class="fas fa-calendar mr-1"></i> <?= date('d M Y', strtotime($kelas['next_session'])) ?></span>
                <?php endif; ?>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center gap-2">
                <a href="<?= base_url('layanan/' . $kelas['slug']) ?>" target="_blank" class="w-full py-2 text-center bg-white/5 border border-white/10 rounded-lg text-sm hover:bg-white/10 transition">
                    <i class="fas fa-eye mr-1"></i> Lihat Detail Layanan
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php if ($pager && $pager->getPageCount() > 1): ?>
<div class="mt-6">
    <?= $pager->links('default', 'default_full') ?>
</div>
<?php endif; ?>
<?php endif; ?>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 md:p-8 max-w-sm w-full text-center transform scale-95 opacity-0 transition-all duration-300" id="deleteModalContent">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center bg-red-500/20">
            <i class="fas fa-trash text-3xl text-red-500"></i>
        </div>
        <h3 class="text-xl font-bold mb-2">Hapus Layanan?</h3>
        <p class="text-gray-400 mb-6">Apakah Anda yakin ingin menghapus <span id="deleteKelasName" class="text-white font-medium"></span>?</p>
        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 py-3 border border-white/20 rounded-xl hover:bg-white/5 transition">Batal</button>
            <form id="deleteForm" method="post" class="flex-1">
                <?= csrf_field() ?>
                <button type="submit" class="w-full py-3 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600 transition">Hapus</button>
            </form>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, name) {
    document.getElementById('deleteKelasName').textContent = name;
    document.getElementById('deleteForm').action = '<?= base_url('wpa/dashboard/layanan/delete/') ?>' + id;
    
    const modal = document.getElementById('deleteModal');
    const content = document.getElementById('deleteModalContent');
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    const content = document.getElementById('deleteModalContent');
    
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
}

document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
</script>
<?= $this->endSection() ?>
