<?= $this->extend('keuangan/layouts/main') ?>

<?= $this->section('content') ?>

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white">Aset Tetap</h1>
        <p class="text-gray-500 text-xs mt-1">Kelola aset tetap, penyusutan, dan pelepasan aset</p>
    </div>
    <div class="flex flex-wrap gap-2">
        <div class="flex bg-[#111] border border-white/10 rounded-xl p-1">
            <button class="px-4 py-1.5 text-xs font-bold text-white bg-white/5 rounded-lg border border-white/10">Laporan <i class="fas fa-chevron-down ml-1 text-[10px]"></i></button>
        </div>
        <button class="px-4 py-2 bg-accent/20 text-accent font-bold rounded-xl hover:bg-accent hover:text-black transition flex items-center gap-2 border border-accent/20">
            <i class="fas fa-sync-alt"></i> Penyusutan
        </button>
        <a href="<?= base_url('keuangan/aset/create') ?>" class="px-4 py-2 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-500 transition flex items-center gap-2 shadow-lg shadow-blue-600/20">
            <i class="fas fa-plus"></i> Tambah
        </a>
        <button class="px-4 py-2 bg-white/5 text-gray-400 font-bold rounded-xl border border-white/10 hover:bg-white/10 transition">
            <i class="fas fa-file-import"></i> Import
        </button>
        <button class="px-4 py-2 bg-white/5 text-gray-400 font-bold rounded-xl border border-white/10 hover:bg-white/10 transition">
            <i class="fas fa-print"></i>
        </button>
    </div>
</div>

<!-- Summary Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-[#111] border border-white/10 p-5 rounded-2xl">
        <div class="flex justify-between items-start mb-4">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Nilai Aset</p>
            <i class="fas fa-ellipsis-v text-gray-600 text-xs"></i>
        </div>
        <h3 class="text-xl font-black text-white">Rp <?= number_format($summary['total_nilai'] ?? 0, 0, ',', '.') ?></h3>
        <div class="mt-4 flex items-center justify-between">
            <span class="text-[10px] text-gray-500 italic">Hari ini vs 365 hari lalu</span>
            <div class="flex items-center gap-1 text-accent text-[10px] font-bold">
                <i class="fas fa-chart-line"></i> 100%
            </div>
        </div>
    </div>

    <div class="bg-[#111] border border-white/10 p-5 rounded-2xl">
        <div class="flex justify-between items-start mb-4">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Depresiasi Aset</p>
        </div>
        <h3 class="text-xl font-black text-white">Rp <?= number_format($summary['total_depresiasi'] ?? 0, 0, ',', '.') ?></h3>
        <div class="mt-4 flex items-center justify-between">
            <span class="text-[10px] text-gray-500 italic">Tahun ini vs tanggal sama tahun lalu</span>
            <div class="flex items-center gap-1 text-accent text-[10px] font-bold">
                <i class="fas fa-chart-line"></i> 100%
            </div>
        </div>
    </div>

    <div class="bg-[#111] border border-white/10 p-5 rounded-2xl text-gray-600">
        <div class="flex justify-between items-start mb-4">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Laba/Rugi Pelepasan</p>
        </div>
        <h3 class="text-xl font-black text-white">0</h3>
        <div class="mt-4 flex items-center justify-between">
            <span class="text-[10px] text-gray-500 italic">Tahun ini vs tanggal sama tahun lalu</span>
            <div class="flex items-center gap-1 text-[10px] font-bold">
                <i class="fas fa-minus"></i> 0%
            </div>
        </div>
    </div>

    <div class="bg-[#111] border border-white/10 p-5 rounded-2xl">
        <div class="flex justify-between items-start mb-4">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Aset Baru</p>
        </div>
        <h3 class="text-xl font-black text-white">Rp <?= number_format($summary['total_nilai'] ?? 0, 0, ',', '.') ?></h3>
        <div class="mt-4 flex items-center justify-between">
            <span class="text-[10px] text-gray-500 italic">Tahun ini vs tanggal sama tahun lalu</span>
            <div class="flex items-center gap-1 text-accent text-[10px] font-bold">
                <i class="fas fa-chart-line"></i> 100%
            </div>
        </div>
    </div>
</div>

<!-- Filters & Tabs -->
<div class="bg-[#111] border border-white/10 rounded-2xl p-4 mb-6 shadow-xl">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center gap-2">
            <button class="px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-xs font-bold text-gray-400 flex items-center gap-2">
                <i class="fas fa-filter"></i> Filter
            </button>
            <button class="p-2 bg-white/5 border border-white/10 rounded-xl text-gray-400">
                <i class="fas fa-th-list"></i>
            </button>
            <div class="flex bg-black border border-white/10 rounded-xl p-1 ml-4">
                <a href="?status=Draft" class="px-4 py-1.5 text-xs font-bold <?= $currentStatus === 'Draft' ? 'bg-white/10 text-white rounded-lg shadow-sm' : 'text-gray-500' ?>">Draft</a>
                <a href="?status=Terdaftar" class="px-4 py-1.5 text-xs font-bold <?= $currentStatus === 'Terdaftar' ? 'bg-white/10 text-white rounded-lg shadow-sm' : 'text-gray-500' ?>">Terdaftar</a>
                <a href="?status=Terjual/Dilepaskan" class="px-4 py-1.5 text-xs font-bold <?= $currentStatus === 'Terjual/Dilepaskan' ? 'bg-white/10 text-white rounded-lg shadow-sm' : 'text-gray-500' ?>">Terjual/Dilepaskan</a>
            </div>
        </div>
        <div class="relative w-full md:w-64">
            <input type="text" placeholder="Cari..." class="w-full bg-black border border-white/10 rounded-xl px-4 py-2 pl-10 text-xs focus:border-accent focus:outline-none transition text-white">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-600 text-xs"></i>
        </div>
    </div>
</div>

<!-- Assets Table -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-black/50 border-b border-white/10">
                <tr>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest w-10">No</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">Nama Aset</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">Nomor</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">Referensi</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">Tag</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 uppercase tracking-widest">Tanggal Pembelian</th>
                    <th class="px-6 py-4 text-right text-[10px] font-bold text-gray-500 uppercase tracking-widest">Harga Beli</th>
                    <th class="px-6 py-4 text-right text-[10px] font-bold text-gray-500 uppercase tracking-widest">Nilai Buku</th>
                    <th class="px-6 py-4 text-center text-[10px] font-bold text-gray-500 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($assets)): ?>
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-box-open text-4xl mb-3 opacity-20"></i>
                            <p>Tidak ada aset yang ditemukan.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; $perPage = 20; $no = ($page - 1) * $perPage + 1; foreach ($assets as $asset): ?>
                        <tr class="hover:bg-white/5 transition group">
                            <td class="px-6 py-4 text-sm text-gray-500 font-mono"><?= $no++ ?></td>
                            <td class="px-6 py-4">
                                <a href="<?= base_url('keuangan/aset/edit/'.$asset['id']) ?>" class="text-white font-bold text-sm hover:text-accent transition"><?= esc($asset['nama_aset']) ?></a>
                            </td>
                            <td class="px-6 py-4">
                                <a href="#" class="text-blue-400 font-mono text-xs hover:underline"><?= esc($asset['nomor_aset']) ?></a>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500">
                                <?= esc($asset['referensi'] ?: '-') ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($asset['tag']): ?>
                                    <span class="px-2 py-0.5 bg-white/5 border border-white/10 rounded text-[9px] text-gray-400"><?= esc($asset['tag']) ?></span>
                                <?php else: ?>
                                    <span class="text-gray-700">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-300">
                                <?= date('d/m/Y', strtotime($asset['tanggal_pembelian'])) ?>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-white text-sm">
                                <?= number_format($asset['harga_beli'], 0, ',', '.') ?>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-accent text-sm">
                                <?= number_format($asset['harga_beli'] - $asset['akumulasi_penyusutan'], 0, ',', '.') ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if (!empty($asset['is_penyusutan']) && ($asset['status'] ?? '') === 'Terdaftar'): ?>
                                <form action="<?= base_url('keuangan/aset/penyusutan/' . $asset['id']) ?>" method="post" class="inline" onsubmit="return confirm('Posting penyusutan bulan ini?')">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="text-xs px-2 py-1 bg-accent/10 text-accent border border-accent/20 rounded-lg hover:bg-accent hover:text-black mr-2" title="Jurnal penyusutan"><i class="fas fa-calculator"></i></button>
                                </form>
                                <?php endif; ?>
                                <button onclick="openViewModal('<?= $asset['id'] ?>')" class="text-blue-400 hover:text-blue-300 transition mr-2" title="Lihat Aset">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="<?= base_url('keuangan/aset/edit/' . $asset['id']) ?>" class="text-yellow-400 hover:text-yellow-300 transition mr-2" title="Edit Aset">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= base_url('keuangan/aset/delete/' . $asset['id']) ?>" class="text-red-400 hover:text-red-300 transition" onclick="return confirm('Apakah Anda yakin ingin menghapus aset ini?')" title="Hapus Aset">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if (isset($pager) && $pager->getPageCount() > 1): ?>
        <div class="p-4 border-t border-white/10 bg-black/20">
            <?= $pager->links('default', 'tailwind_full') ?>
        </div>
    <?php endif; ?>
</div>

<!-- View Modal -->
<div id="viewModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl max-w-md w-full">
        <div class="p-6 border-b border-white/10 flex items-center justify-between">
            <h2 class="text-xl font-bold text-white uppercase"><i class="fas fa-eye text-blue-400 mr-2"></i> Detail Aset</h2>
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
    async function openViewModal(id) {
        try {
            const res = await fetch(`<?= base_url('keuangan/aset/get') ?>/${id}`);
            const data = await res.json();
            if (data.status === 'success') {
                const asset = data.data;
                let penyusutanInfo = '';
                if (asset.is_penyusutan == '1') {
                    penyusutanInfo = `
                    <div class="mt-4 pt-4 border-t border-white/10 space-y-3 text-sm">
                        <p class="font-bold text-accent mb-2">Info Penyusutan</p>
                        <p><span class="text-gray-500 inline-block w-32">Metode</span>: <span class="text-white">${asset.metode_penyusutan}</span></p>
                        <p><span class="text-gray-500 inline-block w-32">Masa Manfaat</span>: <span class="text-white">${asset.masa_manfaat} Tahun</span></p>
                        <p><span class="text-gray-500 inline-block w-32">Nilai Residu</span>: <span class="text-white font-mono">Rp ${parseInt(asset.nilai_residu).toLocaleString('id-ID')}</span></p>
                        <p><span class="text-gray-500 inline-block w-32">Akumulasi Dep.</span>: <span class="text-red-400 font-mono">Rp ${parseInt(asset.akumulasi_penyusutan || 0).toLocaleString('id-ID')}</span></p>
                    </div>`;
                }

                let content = `
                <div class="space-y-3 text-sm">
                    <p><span class="text-gray-500 inline-block w-32">Nama Aset</span>: <span class="text-white font-bold">${asset.nama_aset}</span></p>
                    <p><span class="text-gray-500 inline-block w-32">Nomor Aset</span>: <span class="text-blue-400 font-mono">${asset.nomor_aset}</span></p>
                    <p><span class="text-gray-500 inline-block w-32">Tanggal Beli</span>: <span class="text-white">${asset.tanggal_pembelian}</span></p>
                    <p><span class="text-gray-500 inline-block w-32">Harga Beli</span>: <span class="text-green-400 font-bold font-mono">Rp ${parseInt(asset.harga_beli).toLocaleString('id-ID')}</span></p>
                    <p><span class="text-gray-500 inline-block w-32">Status</span>: <span class="text-white">${asset.status}</span></p>
                    <p><span class="text-gray-500 inline-block w-32">Deskripsi</span>: <span class="text-white">${asset.deskripsi || '-'}</span></p>
                </div>
                ${penyusutanInfo}
                `;
                document.getElementById('viewModalContent').innerHTML = content;
                document.getElementById('viewModal').classList.remove('hidden');
            } else {
                alert('Data tidak ditemukan');
            }
        } catch (e) {
            alert('Terjadi kesalahan');
        }
    }
    
    function closeViewModal() {
        document.getElementById('viewModal').classList.add('hidden');
    }
</script>

<?= $this->endSection() ?>
