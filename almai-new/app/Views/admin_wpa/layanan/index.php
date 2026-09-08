<?= $this->extend('admin_wpa/layouts/main') ?>

<?= $this->section('content') ?>
<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-accent/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-concierge-bell text-accent"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= $stats['total'] ?></p>
                <p class="text-xs text-gray-500 uppercase tracking-widest font-black">Total Produk</p>
            </div>
        </div>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-2xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-green-500/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-file-alt text-green-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= $stats['artikel'] ?></p>
                <p class="text-xs text-gray-500 uppercase tracking-widest font-black">Artikel</p>
            </div>
        </div>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-2xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-yellow-500/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-calendar-alt text-yellow-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= $stats['event'] ?></p>
                <p class="text-xs text-gray-500 uppercase tracking-widest font-black">Webinar</p>
            </div>
        </div>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-2xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-purple-500/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-robot text-purple-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= $stats['tools'] ?></p>
                <p class="text-xs text-gray-500 uppercase tracking-widest font-black">Tools</p>
            </div>
        </div>
    </div>
</div>

<!-- Header Actions & Filters -->
<div class="flex flex-col gap-4 mb-6">
    <!-- Categories -->
    <div class="flex items-center gap-2 overflow-x-auto scrollbar-hide">
        <a href="<?= base_url('admin-wpa/layanan') ?>" class="px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition <?= empty($currentKategori) ? 'bg-accent text-black' : 'bg-white/5 text-gray-400 hover:bg-white/10' ?>">SEMUA</a>
        <?php foreach ($kategoriList as $kat): ?>
            <a href="?kategori=<?= $kat['slug'] ?>" class="px-4 py-2 rounded-full text-xs font-bold whitespace-nowrap transition <?= ($currentKategori ?? '') === $kat['slug'] ? 'bg-accent text-black' : 'bg-white/5 text-gray-400 hover:bg-white/10' ?>"><?= strtoupper(esc($kat['name'])) ?></a>
        <?php endforeach; ?>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <select onchange="window.location.href='?kategori=<?= (string)($currentKategori ?? '') ?>&subcategory='+this.value" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 text-sm focus:border-accent focus:outline-none cursor-pointer">
            <option value="">Semua Subkategori</option>
            <option value="artikel" <?= ($currentSubcategory ?? '') === 'artikel' ? 'selected' : '' ?>>Artikel</option>
            <option value="webinar" <?= ($currentSubcategory ?? '') === 'webinar' ? 'selected' : '' ?>>Webinar</option>
            <option value="workshop" <?= ($currentSubcategory ?? '') === 'workshop' ? 'selected' : '' ?>>Workshop</option>
            <option value="ea" <?= ($currentSubcategory ?? '') === 'ea' ? 'selected' : '' ?>>Expert Advisor</option>
            <option value="toolkit" <?= ($currentSubcategory ?? '') === 'toolkit' ? 'selected' : '' ?>>Almai Toolkits</option>
            <option value="vip_member" <?= ($currentSubcategory ?? '') === 'vip_member' ? 'selected' : '' ?>>VIP Member</option>
        </select>
        <a href="<?= base_url('admin-wpa/layanan/create') ?>" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-accent text-black rounded-xl font-bold text-sm hover:bg-white transition">
            <i class="fas fa-plus"></i> Tambah Layanan
        </a>
    </div>
</div>

<!-- Services List -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto scrollbar-hide">
        <table class="w-full min-w-[750px]">
            <thead class="bg-black/80">
                <tr>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 w-12 text-center uppercase tracking-widest">No</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Layanan</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Kategori</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">WPA</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Harga</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Status</th>
                    <th class="text-center px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($layananList)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center text-gray-500">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center">
                                    <i class="fas fa-concierge-bell text-2xl"></i>
                                </div>
                                <p class="text-sm font-medium">Tidak ada layanan ditemukan</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($layananList as $layanan): ?>
                        <tr class="hover:bg-white/5 transition group">
                            <td class="px-4 py-4 text-gray-500 text-sm text-center font-mono"><?= sprintf('%02d', $no++) ?></td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <?php if (!empty($layanan['thumbnail'])): ?>
                                        <img src="<?= base_url('file/' . $layanan['thumbnail']) ?>" class="w-11 h-11 rounded-xl object-cover border border-white/10 group-hover:border-accent/30 transition">
                                    <?php else: ?>
                                        <div class="w-11 h-11 rounded-xl bg-accent/10 flex items-center justify-center text-accent">
                                            <i class="fas fa-cube text-sm"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="min-w-0">
                                        <p class="font-bold text-sm truncate text-gray-200 group-hover:text-white transition"><?= esc($layanan['name'] ?? $layanan['title'] ?? '-') ?></p>
                                        <p class="text-[10px] text-gray-500 uppercase tracking-widest font-black mt-0.5 opacity-60"><?= esc($layanan['subcategory']) ?></p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="px-2.5 py-1 text-[10px] font-black rounded-lg uppercase tracking-wider <?= $layanan['kategori'] === 'Advokasi' ? 'bg-blue-500/20 text-blue-400' : ($layanan['kategori'] === 'Expert Advisor' ? 'bg-purple-500/20 text-purple-400' : 'bg-accent/20 text-accent') ?>">
                                    <?= esc($layanan['kategori']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-4 text-xs text-gray-400 italic">
                                <?= esc($layanan['wpa_name'] ?? '-') ?>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex flex-col gap-1">
                                    <?php if (!empty($layanan['has_packages'])): ?>
                                        <div class="text-sm">
                                            <span class="text-accent font-bold">Rp <?= number_format($layanan['min_price'], 0, ',', '.') ?></span>
                                            <?php if ($layanan['min_price'] != $layanan['max_price']): ?>
                                                <span class="text-gray-500 mx-1">-</span>
                                                <span class="text-accent font-bold">Rp <?= number_format($layanan['max_price'], 0, ',', '.') ?></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php elseif (!empty($layanan['price']) && $layanan['price'] > 0): ?>
                                        <span class="text-sm font-black text-accent">Rp <?= number_format($layanan['price'], 0, ',', '.') ?></span>
                                    <?php else: ?>
                                        <span class="text-[10px] font-black text-gray-600 uppercase tracking-widest">FREE</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="px-2.5 py-1 text-[10px] font-black rounded-lg uppercase tracking-wider <?= ($layanan['status'] === 'aktif') ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' ?>">
                                    <?= ucfirst($layanan['status']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?= base_url('admin-wpa/layanan/edit/' . $layanan['layanan_type'] . '/' . $layanan['id']) ?>" class="w-9 h-9 flex items-center justify-center bg-white/5 text-gray-400 rounded-xl hover:bg-white/10 hover:text-white transition" title="Edit">
                                        <i class="fas fa-pencil-alt text-xs"></i>
                                    </a>
                                    <form action="<?= base_url('admin-wpa/layanan/toggle-status/' . $layanan['layanan_type'] . '/' . $layanan['id']) ?>" method="POST" class="inline">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="w-9 h-9 flex items-center justify-center <?= ($layanan['status'] === 'aktif') ? 'bg-yellow-500/10 text-yellow-500' : 'bg-green-500/10 text-green-500' ?> rounded-xl hover:opacity-80 transition" title="Toggle Status">
                                            <i class="fas <?= ($layanan['status'] === 'aktif') ? 'fa-eye-slash' : 'fa-eye' ?> text-xs"></i>
                                        </button>
                                    </form>
                                    <button type="button" onclick="showDeleteModal('<?= base_url('admin-wpa/layanan/delete/' . $layanan['layanan_type'] . '/' . $layanan['id']) ?>', '<?= esc($layanan['name'] ?? $layanan['title'] ?? 'Layanan') ?>')" class="w-9 h-9 flex items-center justify-center bg-red-500/10 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition" title="Hapus">
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
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" onclick="hideDeleteModal()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 w-full max-w-md transform transition-all">
            <div class="w-16 h-16 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-trash-alt text-red-500 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-center mb-2">Hapus Layanan?</h3>
            <p class="text-white text-center font-medium mb-4 px-4 py-2 bg-white/5 rounded-lg" id="deleteItemName">-</p>
            <div class="flex gap-3">
                <button type="button" onclick="hideDeleteModal()" class="flex-1 py-3 border border-white/20 rounded-xl text-gray-300">Batal</button>
                <form id="deleteForm" method="POST" class="flex-1">
                    <?= csrf_field() ?>
                    <button type="submit" class="w-full py-3 bg-red-500 text-white rounded-xl font-bold">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function showDeleteModal(url, name) {
        document.getElementById('deleteForm').action = url;
        document.getElementById('deleteItemName').textContent = name;
        document.getElementById('deleteModal').classList.remove('hidden');
    }
    function hideDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>

<?= $this->endSection() ?>
