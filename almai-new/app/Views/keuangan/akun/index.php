<?= $this->extend('keuangan/layouts/main') ?>

<?= $this->section('content') ?>
<?php
// Helper for toggle sort order
$getSortUrl = function ($field) use ($sortBy, $sortOrder, $currentSearch, $currentKategori) {
    $newOrder = ($sortBy === $field && $sortOrder === 'ASC') ? 'DESC' : 'ASC';
    return base_url('keuangan/akun') . '?' . http_build_query([
        'search' => $currentSearch,
        'kategori' => $currentKategori,
        'sort' => $field,
        'order' => $newOrder
    ]);
};

$getSortIcon = function ($field) use ($sortBy, $sortOrder) {
    if ($sortBy !== $field) return '<i class="fas fa-sort text-gray-600 ml-1"></i>';
    return $sortOrder === 'ASC'
        ? '<i class="fas fa-sort-up text-accent ml-1"></i>'
        : '<i class="fas fa-sort-down text-accent ml-1"></i>';
};
?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold">Daftar Akun (Chart of Accounts)</h1>
        <p class="text-gray-500 text-xs mt-1">Kelola akun-akun untuk pencatatan keuangan</p>
    </div>
    <button onclick="openModal()" class="px-4 py-2 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center gap-2 shadow-lg shadow-accent/20">
        <i class="fas fa-plus"></i> Tambah Akun
    </button>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="bg-accent/10 border border-accent/20 text-accent p-4 rounded-xl mb-6 flex items-center gap-3">
        <i class="fas fa-check-circle"></i>
        <span><?= session()->getFlashdata('success') ?></span>
    </div>
<?php endif; ?>

<!-- Filters -->
<div class="bg-[#111] border border-white/10 rounded-2xl p-4 mb-6 shadow-xl">
    <form action="<?= base_url('keuangan/akun') ?>" method="get" class="flex flex-col md:flex-row gap-4">
        <input type="hidden" name="sort" value="<?= esc($sortBy) ?>">
        <input type="hidden" name="order" value="<?= esc($sortOrder) ?>">
        <div class="flex-1 relative">
            <input type="text" name="search" value="<?= esc($currentSearch) ?>" placeholder="Cari Kode atau Nama Akun..." class="w-full px-4 py-2.5 pl-10 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm transition text-gray-300">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-xs"></i>
        </div>
        <select name="kategori" onchange="this.form.submit()" class="px-4 py-2.5 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm text-gray-300 md:w-64">
            <option value="all">Semua Kategori</option>
            <?php foreach ($kategoriList as $cat): ?>
                <option value="<?= esc($cat) ?>" <?= $currentKategori === $cat ? 'selected' : '' ?>><?= esc($cat) ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="px-6 py-2.5 bg-white/5 hover:bg-white/10 text-white font-bold rounded-xl transition border border-white/10">
            Filter
        </button>
    </form>
</div>

<!-- Table -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-black/50 border-b border-white/10">
                <tr>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider w-16">No</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                        <a href="<?= $getSortUrl('kode_akun') ?>" class="flex items-center hover:text-white transition">
                            Kode Akun <?= $getSortIcon('kode_akun') ?>
                        </a>
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                        <a href="<?= $getSortUrl('nama_akun') ?>" class="flex items-center hover:text-white transition">
                            Nama Akun <?= $getSortIcon('nama_akun') ?>
                        </a>
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                        <a href="<?= $getSortUrl('kategori') ?>" class="flex items-center hover:text-white transition">
                            Kategori <?= $getSortIcon('kategori') ?>
                        </a>
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">
                        <a href="<?= $getSortUrl('kode_sub_akun') ?>" class="flex items-center hover:text-white transition">
                            Sub Akun <?= $getSortIcon('kode_sub_akun') ?>
                        </a>
                    </th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($akunList)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-book-open text-4xl mb-3 opacity-20"></i>
                            <p>Belum ada akun terdaftar.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($akunList as $index => $akun): ?>
                        <tr class="hover:bg-white/5 transition group">
                            <td class="px-6 py-4 text-center text-gray-500 text-sm font-mono">
                                <?= ($currentPage - 1) * $perPage + $index + 1 ?>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-mono text-accent font-bold"><?= esc($akun['kode_akun']) ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-bold text-white"><?= esc($akun['nama_akun']) ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-white/5 border border-white/10 rounded text-[10px] text-gray-300 uppercase tracking-wide">
                                    <?= esc($akun['kategori']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-400">
                                <?php if ($akun['kode_sub_akun'] || $akun['nama_sub_akun']): ?>
                                    <div class="flex flex-col">
                                        <span class="font-mono text-xs"><?= esc($akun['kode_sub_akun']) ?></span>
                                        <span class="text-[10px]"><?= esc($akun['nama_sub_akun']) ?></span>
                                    </div>
                                <?php else: ?>
                                    <span class="opacity-30">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button onclick='editAkun(<?= json_encode($akun) ?>)' class="w-8 h-8 rounded-lg bg-blue-500/10 hover:bg-blue-500 text-blue-500 hover:text-white transition flex items-center justify-center">
                                        <i class="fas fa-edit text-xs"></i>
                                    </button>
                                    <a href="<?= base_url('keuangan/akun/delete/' . $akun['id']) ?>" onclick="return confirm('Yakin ingin menghapus akun ini?')" class="w-8 h-8 rounded-lg bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white transition flex items-center justify-center">
                                        <i class="fas fa-trash text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Improved Pagination -->
    <?php if ($pager && $pager->getPageCount() > 1): ?>
        <div class="p-4 border-t border-white/5 flex items-center justify-between bg-black/20">
            <p class="text-[10px] md:text-xs text-gray-500">
                Menampilkan <?= count($akunList) ?> dari <?= $pager->getTotal() ?> data
            </p>
            <div class="flex gap-1 scale-90 origin-right">
                <?= $pager->links('default', 'admin_pagination') ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Form -->
<div id="akunModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 hidden backdrop-blur-sm transition-all">
    <div class="bg-[#111] border border-white/10 rounded-2xl w-full max-w-2xl p-6 shadow-2xl overflow-y-auto max-h-[90vh]">
        <div class="flex items-center justify-between mb-6 pb-6 border-b border-white/10">
            <h3 class="text-xl font-bold" id="modalTitle">Tambah Akun Baru</h3>
            <button onclick="closeModal()" class="w-8 h-8 rounded-full bg-white/5 hover:bg-white/10 flex items-center justify-center transition">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form action="<?= base_url('keuangan/akun/save') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="akunId">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Nama Akun</label>
                        <input type="text" name="nama_akun" id="namaAkun" required class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none transition text-white text-sm" placeholder="Contoh: Kas Besar">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Kode Akun</label>
                        <input type="text" name="kode_akun" id="kodeAkun" required class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none transition text-white text-sm" placeholder="Contoh: 1-1001">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Kategori</label>
                        <select name="kategori" id="kategoriAkun" required class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none transition text-white text-sm">
                            <option value="">Pilih Kategori</option>
                            <?php foreach ($kategoriList as $cat): ?>
                                <option value="<?= esc($cat) ?>"><?= esc($cat) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Right Column (Sub Account) -->
                <div class="space-y-4 bg-white/5 p-4 rounded-xl border border-white/5 border-dashed">
                    <div class="mb-2">
                        <span class="text-xs font-bold text-accent uppercase tracking-wider block">Opsi Sub Akun</span>
                        <p class="text-[10px] text-gray-500">Isi jika ini adalah sub-akun dari akun lain</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Kode Sub Akun</label>
                        <input type="text" name="kode_sub_akun" id="kodeSubAkun" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none transition text-white text-sm" placeholder="Contoh: 01">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Nama Sub Akun</label>
                        <input type="text" name="nama_sub_akun" id="namaSubAkun" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none transition text-white text-sm" placeholder="Contoh: Kas Harian">
                    </div>
                </div>
            </div>

            <div class="flex gap-4 pt-4 border-t border-white/10">
                <button type="button" onclick="closeModal()" class="flex-1 py-3 bg-white/5 hover:bg-white/10 text-white font-bold rounded-xl transition border border-white/10">
                    Batal
                </button>
                <button type="submit" class="flex-1 py-3 bg-accent hover:bg-white text-black font-bold rounded-xl transition shadow-lg shadow-accent/20">
                    Simpan Akun
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('modalTitle').innerText = 'Tambah Akun Baru';
        document.getElementById('akunId').value = '';
        document.getElementById('namaAkun').value = '';
        document.getElementById('kodeAkun').value = '';
        document.getElementById('kategoriAkun').value = '';
        document.getElementById('kodeSubAkun').value = '';
        document.getElementById('namaSubAkun').value = '';

        document.getElementById('akunModal').classList.remove('hidden');
        document.getElementById('akunModal').classList.add('flex');
    }

    function editAkun(data) {
        document.getElementById('modalTitle').innerText = 'Edit Akun';
        document.getElementById('akunId').value = data.id;
        document.getElementById('namaAkun').value = data.nama_akun;
        document.getElementById('kodeAkun').value = data.kode_akun;
        document.getElementById('kategoriAkun').value = data.kategori;
        document.getElementById('kodeSubAkun').value = data.kode_sub_akun;
        document.getElementById('namaSubAkun').value = data.nama_sub_akun;

        document.getElementById('akunModal').classList.remove('hidden');
        document.getElementById('akunModal').classList.add('flex');
    }

    function closeModal() {
        document.getElementById('akunModal').classList.add('hidden');
        document.getElementById('akunModal').classList.remove('flex');
    }

    window.onclick = function(event) {
        const modal = document.getElementById('akunModal');
        if (event.target == modal) {
            closeModal();
        }
    }
</script>
<?= $this->endSection() ?>