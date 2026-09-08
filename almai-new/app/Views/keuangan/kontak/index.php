<?= $this->extend('keuangan/layouts/main') ?>
<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold">Buku Kontak</h1>
        <p class="text-gray-500 text-xs mt-1">Pelanggan & pemasok — terhubung ke penjualan/pembelian</p>
    </div>
    <button onclick="openModal()" class="px-4 py-2 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center gap-2">
        <i class="fas fa-plus"></i> Tambah Kontak
    </button>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="bg-accent/10 border border-accent/20 text-accent p-4 rounded-xl mb-6"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="bg-[#111] border border-white/10 rounded-2xl p-4 mb-6">
    <form method="get" class="flex flex-col md:flex-row gap-3">
        <input type="text" name="search" value="<?= esc($search) ?>" placeholder="Cari nama, perusahaan, email..." class="flex-1 px-4 py-2.5 bg-black border border-white/10 rounded-xl text-sm text-gray-300">
        <select name="tipe" class="px-4 py-2.5 bg-black border border-white/10 rounded-xl text-sm text-gray-300 md:w-48">
            <option value="all">Semua Tipe</option>
            <option value="pelanggan" <?= $tipe === 'pelanggan' ? 'selected' : '' ?>>Pelanggan</option>
            <option value="pemasok" <?= $tipe === 'pemasok' ? 'selected' : '' ?>>Pemasok</option>
            <option value="keduanya" <?= $tipe === 'keduanya' ? 'selected' : '' ?>>Keduanya</option>
        </select>
        <button type="submit" class="px-6 py-2.5 bg-white/5 border border-white/10 rounded-xl text-white font-bold">Filter</button>
    </form>
</div>

<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-black/50 border-b border-white/10 text-xs text-gray-400 uppercase">
            <tr>
                <th class="px-4 py-3 text-left">Nama</th>
                <th class="px-4 py-3 text-left">Tipe</th>
                <th class="px-4 py-3 text-left">Kontak</th>
                <th class="px-4 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-white/5">
            <?php if (empty($kontakList)): ?>
                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Belum ada kontak</td></tr>
            <?php else: ?>
                <?php foreach ($kontakList as $k): ?>
                <tr class="hover:bg-white/5">
                    <td class="px-4 py-3">
                        <p class="text-white font-medium"><?= esc($k['nama']) ?></p>
                        <?php if ($k['perusahaan']): ?><p class="text-gray-500 text-xs"><?= esc($k['perusahaan']) ?></p><?php endif; ?>
                    </td>
                    <td class="px-4 py-3 capitalize text-gray-300"><?= esc($k['tipe']) ?></td>
                    <td class="px-4 py-3 text-gray-400 text-xs"><?= esc($k['email'] ?: $k['telepon'] ?: '-') ?></td>
                    <td class="px-4 py-3 text-right">
                        <button type="button" onclick="openViewModal('<?= $k['id'] ?>')" class="text-blue-400 hover:text-blue-300 transition mr-2" title="Lihat Kontak"><i class="fas fa-eye"></i></button>
                        <button type="button" onclick='editKontak(<?= json_encode($k) ?>)' class="text-yellow-400 hover:text-yellow-300 transition mr-2" title="Edit Kontak"><i class="fas fa-edit"></i></button>
                        <a href="<?= base_url('keuangan/kontak/delete/' . $k['id']) ?>" onclick="return confirm('Nonaktifkan kontak ini?')" class="text-red-400 hover:text-red-300 transition" title="Hapus Kontak"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <?php if ($pager && $pager->getPageCount() > 1): ?>
        <div class="p-4 border-t border-white/10 bg-black/20">
            <?= $pager->links('default', 'tailwind_full') ?>
        </div>
    <?php endif; ?>
</div>

<div id="kontakModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/70">
    <div class="bg-[#111] border border-white/10 rounded-2xl w-full max-w-lg p-6 max-h-[90vh] overflow-y-auto">
        <h3 id="modalTitle" class="text-lg font-bold mb-4">Tambah Kontak</h3>
        <form action="<?= base_url('keuangan/kontak/save') ?>" method="post">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="kontakId">
            <div class="space-y-3">
                <select name="tipe" id="kontakTipe" required class="w-full bg-black border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white">
                    <option value="pelanggan">Pelanggan</option>
                    <option value="pemasok">Pemasok</option>
                    <option value="keduanya">Keduanya</option>
                </select>
                <input name="nama" id="kontakNama" required placeholder="Nama *" class="w-full bg-black border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white">
                <input name="perusahaan" id="kontakPerusahaan" placeholder="Perusahaan" class="w-full bg-black border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white">
                <input name="email" id="kontakEmail" type="email" placeholder="Email" class="w-full bg-black border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white">
                <input name="telepon" id="kontakTelepon" placeholder="Telepon" class="w-full bg-black border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white">
                <textarea name="alamat" id="kontakAlamat" rows="2" placeholder="Alamat" class="w-full bg-black border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white"></textarea>
                <input name="npwp" id="kontakNpwp" placeholder="NPWP" class="w-full bg-black border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white">
            </div>
            <div class="flex gap-3 mt-6">
                <button type="button" onclick="closeModal()" class="flex-1 py-2.5 border border-white/10 rounded-xl text-gray-400">Batal</button>
                <button type="submit" class="flex-1 py-2.5 bg-accent text-black font-bold rounded-xl">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- View Modal -->
<div id="viewModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-4 flex">
    <div class="bg-[#111] border border-white/10 rounded-2xl max-w-md w-full hidden" id="viewModalInner">
        <div class="p-6 border-b border-white/10 flex items-center justify-between">
            <h2 class="text-xl font-bold text-white uppercase"><i class="fas fa-eye text-blue-400 mr-2"></i> Detail Kontak</h2>
            <button onclick="closeViewModal()" class="text-gray-400 hover:text-white transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6" id="viewModalContent">
            <!-- Content -->
        </div>
        <div class="p-4 border-t border-white/10 text-right">
            <button onclick="closeViewModal()" class="px-5 py-2.5 bg-white/10 text-white font-bold rounded-xl hover:bg-white/20 transition">Tutup</button>
        </div>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('modalTitle').textContent = 'Tambah Kontak';
    document.getElementById('kontakId').value = '';
    ['kontakNama','kontakPerusahaan','kontakEmail','kontakTelepon','kontakAlamat','kontakNpwp'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('kontakTipe').value = 'pelanggan';
    document.getElementById('kontakModal').classList.remove('hidden');
    document.getElementById('kontakModal').classList.add('flex');
}
function closeModal() {
    document.getElementById('kontakModal').classList.add('hidden');
    document.getElementById('kontakModal').classList.remove('flex');
}
function editKontak(k) {
    document.getElementById('modalTitle').textContent = 'Edit Kontak';
    document.getElementById('kontakId').value = k.id;
    document.getElementById('kontakTipe').value = k.tipe;
    document.getElementById('kontakNama').value = k.nama || '';
    document.getElementById('kontakPerusahaan').value = k.perusahaan || '';
    document.getElementById('kontakEmail').value = k.email || '';
    document.getElementById('kontakTelepon').value = k.telepon || '';
    document.getElementById('kontakAlamat').value = k.alamat || '';
    document.getElementById('kontakNpwp').value = k.npwp || '';
    document.getElementById('kontakModal').classList.remove('hidden');
    document.getElementById('kontakModal').classList.add('flex');
}

async function openViewModal(id) {
    try {
        const res = await fetch(`<?= base_url('keuangan/kontak/get') ?>/${id}`);
        const data = await res.json();
        if (data.status === 'success') {
            const kontak = data.data;
            let content = `
            <div class="space-y-3 text-sm">
                <p><span class="text-gray-500 inline-block w-32">Nama</span>: <span class="text-white font-bold">${kontak.nama}</span></p>
                <p><span class="text-gray-500 inline-block w-32">Perusahaan</span>: <span class="text-white">${kontak.perusahaan || '-'}</span></p>
                <p><span class="text-gray-500 inline-block w-32">Tipe</span>: <span class="text-white capitalize">${kontak.tipe}</span></p>
                <p><span class="text-gray-500 inline-block w-32">Email</span>: <span class="text-white">${kontak.email || '-'}</span></p>
                <p><span class="text-gray-500 inline-block w-32">Telepon</span>: <span class="text-white">${kontak.telepon || '-'}</span></p>
                <p><span class="text-gray-500 inline-block w-32">Alamat</span>: <span class="text-white">${kontak.alamat || '-'}</span></p>
                <p><span class="text-gray-500 inline-block w-32">NPWP</span>: <span class="text-white font-mono">${kontak.npwp || '-'}</span></p>
            </div>
            `;
            document.getElementById('viewModalContent').innerHTML = content;
            document.getElementById('viewModalInner').classList.remove('hidden');
            document.getElementById('viewModal').classList.remove('hidden');
            document.getElementById('viewModal').classList.add('flex');
        } else {
            alert('Data tidak ditemukan');
        }
    } catch (e) {
        alert('Terjadi kesalahan');
    }
}

function closeViewModal() {
    document.getElementById('viewModal').classList.add('hidden');
    document.getElementById('viewModal').classList.remove('flex');
    document.getElementById('viewModalInner').classList.add('hidden');
}
</script>
<?= $this->endSection() ?>

