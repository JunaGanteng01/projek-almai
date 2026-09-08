<?= $this->extend('keuangan/layouts/main') ?>

<?= $this->section('content') ?>
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-white uppercase tracking-tighter"><i class="fas fa-arrow-up text-red-400 mr-2"></i>Kas Keluar</h1>
        <p class="text-accent text-xs mt-1 font-bold">Pencatatan Pengeluaran Kas Terintegrasi COA</p>
    </div>
    <div class="flex flex-wrap gap-3">
        <a href="<?= base_url('keuangan/kas-masuk-keluar/export-excel') ?>" class="px-4 py-2.5 bg-blue-500/20 text-blue-400 border border-blue-500/50 font-bold rounded-xl hover:bg-blue-500/30 transition flex items-center">
            <i class="fas fa-file-excel mr-2"></i> Export
        </a>
        <button onclick="openImportModal()" class="px-4 py-2.5 bg-yellow-500/20 text-yellow-400 border border-yellow-500/50 font-bold rounded-xl hover:bg-yellow-500/30 transition flex items-center">
            <i class="fas fa-file-import mr-2"></i> Import
        </button>
        
        <a href="<?= base_url('keuangan/kas-keluar/delete-all') ?>" onclick="return confirm('APAKAH ANDA YAKIN INGIN MENGHAPUS SEMUA DATA KAS KELUAR?\n\nData yang sudah dihapus tidak bisa dikembalikan lagi.')" class="px-4 py-2.5 bg-red-500/20 text-red-400 border border-red-500/50 font-bold rounded-xl hover:bg-red-500/30 transition flex items-center">
            <i class="fas fa-trash-alt mr-2"></i> Hapus Semua
        </a>
        
        <a href="<?= base_url('keuangan/kas-keluar/duplikat') ?>" class="px-4 py-2.5 bg-yellow-500/20 text-yellow-500 border border-yellow-500/50 font-bold rounded-xl hover:bg-yellow-500/30 transition flex items-center" title="Kelola duplikat berdasarkan Akun Kas, Akun Tujuan, Nominal, dan Keterangan">
            <i class="fas fa-magic mr-2"></i> Kelola Duplikat
        </a>

        <button onclick="openModal()" class="px-5 py-2.5 bg-red-500/20 text-red-400 border border-red-500/50 font-bold rounded-xl hover:bg-red-500/30 transition flex items-center">
            <i class="fas fa-plus mr-2"></i> Tambah Kas Keluar
        </button>
    </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div class="bg-green-500/10 border border-green-500/30 text-green-400 p-4 rounded-xl mb-6">
        <i class="fas fa-check-circle mr-2"></i> <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if(session()->getFlashdata('error')): ?>
    <div class="bg-red-500/10 border border-red-500/30 text-red-400 p-4 rounded-xl mb-6">
        <i class="fas fa-exclamation-circle mr-2"></i> <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl mb-6">
    <form action="" method="GET" class="flex flex-col md:flex-row gap-4" style="color-scheme: dark;">
        <div class="flex-1">
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Cari Data</label>
            <input type="text" name="search" value="<?= $_GET['search'] ?? '' ?>" placeholder="No. Reff, Keterangan..." class="w-full bg-[#222] border border-white/10 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-green-500/50 outline-none">
        </div>
        <div class="flex-1">
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Filter Bulan</label>
            <input type="month" name="bulan" value="<?= $_GET['bulan'] ?? '' ?>" class="w-full bg-[#222] border border-white/10 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-green-500/50 outline-none">
        </div>
        <div class="flex-1">
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Filter Akun</label>
            <select name="akun_id" class="w-full bg-[#222] border border-white/10 rounded-xl px-4 py-2.5 text-white focus:ring-2 focus:ring-green-500/50 outline-none">
                <option value="" class="bg-[#222] text-white">-- Semua Akun --</option>
                <optgroup label="Kas & Bank" class="bg-[#333] text-gray-300">
                    <?php foreach($kasBankAccounts as $akun): ?>
                        <option class="bg-[#222] text-white" value="<?= $akun['id'] ?>" <?= (($_GET['akun_id'] ?? '') == $akun['id']) ? 'selected' : '' ?>><?= $akun['kode_akun'] ?> - <?= $akun['nama_akun'] ?></option>
                    <?php endforeach; ?>
                </optgroup>
                <optgroup label="Beban / Pengeluaran" class="bg-[#333] text-gray-300">
                    <?php foreach($pengeluaranAccounts as $akun): ?>
                        <option class="bg-[#222] text-white" value="<?= $akun['id'] ?>" <?= (($_GET['akun_id'] ?? '') == $akun['id']) ? 'selected' : '' ?>><?= $akun['kode_akun'] ?> - <?= $akun['nama_akun'] ?></option>
                    <?php endforeach; ?>
                </optgroup>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="px-5 py-2.5 bg-green-500/20 text-green-400 border border-green-500/50 font-bold rounded-xl hover:bg-green-500/30 transition">
                <i class="fas fa-search mr-2"></i> Cari
            </button>
            <a href="<?= current_url() ?>" class="px-5 py-2.5 bg-gray-500/20 text-gray-400 border border-gray-500/50 font-bold rounded-xl hover:bg-gray-500/30 transition">
                Reset
            </a>
        </div>
    </form>
</div>

<div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-white/5 border-b border-white/10">
                <tr>
                    <th class="px-4 py-3 text-left font-bold text-gray-400 uppercase tracking-wider">No</th>
                    <th class="px-4 py-3 text-left font-bold text-gray-400 uppercase tracking-wider">Tanggal</th>
                    <th class="px-4 py-3 text-left font-bold text-gray-400 uppercase tracking-wider">Akun Kas/Bank</th>
                    <th class="px-4 py-3 text-left font-bold text-gray-400 uppercase tracking-wider">Akun Beban/Biaya</th>
                    <th class="px-4 py-3 text-right font-bold text-gray-400 uppercase tracking-wider">Nominal</th>
                    <th class="px-4 py-3 text-left font-bold text-gray-400 uppercase tracking-wider">Keterangan</th>
                    <th class="px-4 py-3 text-center font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($transactions)): ?>
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">Belum ada transaksi Kas Keluar.</td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($transactions as $tx): ?>
                        <tr class="hover:bg-white/5 transition">
                            <td class="px-4 py-3 text-gray-400"><?= $no++ ?></td>
                            <td class="px-4 py-3 text-gray-300"><?= date('d M Y', strtotime($tx['tanggal'])) ?></td>
                            <td class="px-4 py-3"><span class="font-bold text-white"><?= esc($tx['akun_kas']) ?></span></td>
                            <td class="px-4 py-3 text-white"><?= esc($tx['akun_tujuan']) ?></td>
                            <td class="px-4 py-3 text-right font-mono font-bold text-red-400">- Rp <?= number_format($tx['nominal'], 0, ',', '.') ?></td>
                            <td class="px-4 py-3 text-gray-400 text-xs">
                                <?= esc($tx['keterangan']) ?>
                                <?php if (isset($tx['is_duplicate']) && $tx['is_duplicate']): ?>
                                    <div class="inline-flex items-center gap-1 bg-red-500/20 text-red-400 border border-red-500/30 px-2 py-0.5 rounded text-[10px] uppercase font-bold ml-2">
                                        <i class="fas fa-exclamation-triangle"></i> Duplikat
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <button onclick="openViewModal('<?= $tx['no_reff'] ?>')" class="text-blue-400 hover:text-blue-300 transition mr-2" title="Lihat Transaksi">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="openEditModal('<?= $tx['no_reff'] ?>')" class="text-yellow-400 hover:text-yellow-300 transition mr-2" title="Edit Transaksi">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <a href="<?= base_url('keuangan/kas-masuk-keluar/delete/' . $tx['no_reff']) ?>" class="text-red-400 hover:text-red-300 transition" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini? (Jurnal juga akan dihapus)')" title="Hapus Transaksi">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if (isset($pagerLinks) && $pagerLinks): ?>
        <div class="p-4 border-t border-white/10 bg-black/20">
            <?= $pagerLinks ?>
        </div>
    <?php endif; ?>
</div>

<!-- Modal Form -->
<div id="kasModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-white/10 flex items-center justify-between sticky top-0 bg-[#111]">
            <h2 id="modalTitle" class="text-xl font-bold text-white uppercase"><i class="fas fa-arrow-up text-red-400 mr-2"></i>Tambah Kas Keluar</h2>
            <button onclick="closeModal()" class="text-gray-400 hover:text-white transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form method="post" action="<?= base_url('keuangan/kas-masuk-keluar/save') ?>" class="p-6 space-y-5">
            <?= csrf_field() ?>
            <input type="hidden" name="tipe" value="keluar">
            <input type="hidden" name="no_reff" id="form_no_reff" value="">

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Tanggal Transaksi</label>
                <input type="date" id="form_tanggal" name="tanggal" required value="<?= date('Y-m-d') ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Akun Kas / Bank</label>
                <select id="form_akun_kas_id" name="akun_kas_id" required class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent focus:outline-none transition">
                    <option value="">-- Pilih Akun Kas/Bank --</option>
                    <?php foreach ($kasBankAccounts as $akun): ?>
                        <option value="<?= $akun['id'] ?>"><?= esc($akun['kode_akun']) ?> - <?= esc($akun['nama_akun']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Akun Beban / Biaya (Tujuan Dana)</label>
                <select id="form_akun_lawan_id" name="akun_lawan_id" required class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent focus:outline-none transition">
                    <option value="">-- Pilih Akun Beban/Biaya --</option>
                    <?php if (!empty($pindahDanaAccounts)): ?>
                        <optgroup label="Pindah Dana">
                            <?php foreach ($pindahDanaAccounts as $akun): ?>
                                <option value="<?= $akun['id'] ?>"><?= esc($akun['nama_akun']) ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endif; ?>
                    <optgroup label="Akun Beban / Biaya">
                        <?php foreach ($pengeluaranAccounts as $akun): ?>
                            <option value="<?= $akun['id'] ?>"><?= esc($akun['kode_akun']) ?> - <?= esc($akun['nama_akun']) ?></option>
                        <?php endforeach; ?>
                    </optgroup>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Nominal (Rp)</label>
                <input type="number" id="form_nominal" name="nominal" required min="1" step="1" placeholder="Masukkan nominal" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-white font-mono focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent transition">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Keterangan / Deskripsi</label>
                <textarea id="form_deskripsi" name="deskripsi" rows="3" required placeholder="Contoh: Pembayaran tagihan listrik bulan Mei..." class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent transition"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-white/10">
                <button type="button" onclick="closeModal()" class="px-5 py-2.5 bg-white/10 text-white font-bold rounded-xl hover:bg-white/20 transition">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600 transition shadow-[0_0_15px_rgba(239,68,68,0.3)]">Simpan Kas Keluar</button>
            </div>
        </form>
    </div>
</div>

<!-- Import Modal -->
<div id="importModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl max-w-md w-full">
        <div class="p-6 border-b border-white/10 flex items-center justify-between">
            <h2 class="text-xl font-bold text-white uppercase"><i class="fas fa-file-excel text-yellow-400 mr-2"></i> Import Excel</h2>
            <button onclick="closeImportModal()" class="text-gray-400 hover:text-white transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6">
            <div class="mb-6 bg-blue-500/10 border border-blue-500/30 rounded-xl p-4 text-sm text-blue-300">
                <p class="font-bold mb-2">Panduan Import:</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Gunakan format template yang telah disediakan.</li>
                    <li>Pastikan Kode Akun Kas dan Kode Akun Tujuan terisi dengan benar.</li>
                    <li>Jangan merubah header (baris pertama) pada template.</li>
                </ul>
            </div>
            
            <a href="<?= base_url('keuangan/kas-masuk-keluar/download-template') ?>" class="w-full block text-center mb-6 px-4 py-3 bg-white/10 text-white border border-white/20 font-bold rounded-xl hover:bg-white/20 transition">
                <i class="fas fa-download mr-2"></i> Download Template
            </a>

            <form method="post" action="<?= base_url('keuangan/kas-masuk-keluar/import-excel') ?>" enctype="multipart/form-data" class="space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Upload File (.xlsx)</label>
                    <input type="file" name="file_excel" accept=".xlsx" required class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent focus:outline-none transition">
                </div>
                
                <div class="flex justify-end gap-3 pt-4 border-t border-white/10">
                    <button type="button" onclick="closeImportModal()" class="px-5 py-2.5 bg-white/10 text-white font-bold rounded-xl hover:bg-white/20 transition">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600 transition shadow-[0_0_15px_rgba(239,68,68,0.3)]">Proses Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Modal -->
<div id="viewModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl max-w-md w-full">
        <div class="p-6 border-b border-white/10 flex items-center justify-between">
            <h2 class="text-xl font-bold text-white uppercase"><i class="fas fa-eye text-blue-400 mr-2"></i> Detail Transaksi</h2>
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
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-arrow-up text-red-400 mr-2"></i>Tambah Kas Keluar';
        document.getElementById('form_no_reff').value = '';
        document.getElementById('form_tanggal').value = '<?= date('Y-m-d') ?>';
        document.getElementById('form_akun_kas_id').value = '';
        document.getElementById('form_akun_lawan_id').value = '';
        document.getElementById('form_nominal').value = '';
        document.getElementById('form_deskripsi').value = '';
        document.getElementById('kasModal').classList.remove('hidden');
    }

    async function openEditModal(no_reff) {
        try {
            const res = await fetch(`<?= base_url('keuangan/kas-masuk-keluar/get') ?>/${no_reff}`);
            const data = await res.json();
            if (data.status === 'success') {
                document.getElementById('modalTitle').innerHTML = '<i class="fas fa-arrow-up text-red-400 mr-2"></i>Edit Kas Keluar';
                document.getElementById('form_no_reff').value = data.data.no_reff;
                document.getElementById('form_tanggal').value = data.data.tanggal;
                document.getElementById('form_akun_kas_id').value = data.data.akun_kas_id;
                document.getElementById('form_akun_lawan_id').value = data.data.akun_lawan_id;
                document.getElementById('form_nominal').value = data.data.nominal;
                document.getElementById('form_deskripsi').value = data.data.deskripsi;
                document.getElementById('kasModal').classList.remove('hidden');
            } else {
                alert('Data tidak ditemukan');
            }
        } catch (e) {
            alert('Terjadi kesalahan');
        }
    }

    async function openViewModal(no_reff) {
        try {
            const res = await fetch(`<?= base_url('keuangan/kas-masuk-keluar/get') ?>/${no_reff}`);
            const data = await res.json();
            if (data.status === 'success') {
                let content = `
                <div class="space-y-3 text-sm">
                    <p><span class="text-gray-500 inline-block w-32">No. Reff</span>: <span class="text-white font-mono">${data.data.no_reff}</span></p>
                    <p><span class="text-gray-500 inline-block w-32">Tanggal</span>: <span class="text-white">${data.data.tanggal}</span></p>
                    <p><span class="text-gray-500 inline-block w-32">Nominal</span>: <span class="text-red-400 font-bold font-mono">Rp ${parseInt(data.data.nominal).toLocaleString('id-ID')}</span></p>
                    <p><span class="text-gray-500 inline-block w-32">Deskripsi</span>: <span class="text-white">${data.data.deskripsi}</span></p>
                </div>
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

    function closeModal() {
        document.getElementById('kasModal').classList.add('hidden');
    }

    function openImportModal() {
        document.getElementById('importModal').classList.remove('hidden');
    }

    function closeImportModal() {
        document.getElementById('importModal').classList.add('hidden');
    }
</script>

<?= $this->endSection() ?>
