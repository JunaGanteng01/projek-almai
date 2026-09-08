<?= $this->extend('keuangan/layouts/main') ?>

<?= $this->section('content') ?>
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold">Jurnal Umum</h1>
        <p class="text-gray-500 text-xs mt-1">Pencatatan transaksi keuangan terintegrasi</p>
    </div>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="bg-accent/10 border border-accent/20 text-accent p-4 rounded-xl mb-6 flex items-center gap-3">
        <i class="fas fa-check-circle"></i>
        <span><?= session()->getFlashdata('success') ?></span>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="bg-red-500/10 border border-red-500/20 text-red-500 p-4 rounded-xl mb-6 flex items-center gap-3">
        <i class="fas fa-exclamation-circle"></i>
        <span><?= session()->getFlashdata('error') ?></span>
    </div>
<?php endif; ?>

<!-- Filters & Export -->
<div class="bg-[#111] border border-white/10 rounded-2xl p-4 mb-6 shadow-xl">
    <form action="<?= base_url('keuangan/jurnal-umum') ?>" method="get" class="flex flex-col md:flex-row gap-4 items-end">
        <div class="flex-1 md:max-w-xs">
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Pencarian</label>
            <input type="text" name="search" value="<?= esc($search ?? '') ?>" placeholder="Cari keterangan/reff..." class="w-full bg-black border border-white/10 rounded-xl px-4 py-2.5 focus:border-accent focus:outline-none text-white text-sm">
        </div>
        <div class="flex-1 md:max-w-[150px]">
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Dari Tanggal</label>
            <input type="date" name="start_date" value="<?= esc($startDate ?? '') ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-2.5 focus:border-accent focus:outline-none text-white text-sm">
        </div>
        <div class="flex-1 md:max-w-[150px]">
            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Sampai Tanggal</label>
            <input type="date" name="end_date" value="<?= esc($endDate ?? '') ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-2.5 focus:border-accent focus:outline-none text-white text-sm">
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <button type="submit" class="flex-1 md:flex-none px-6 py-2.5 bg-accent hover:bg-white text-black font-bold rounded-xl transition shadow-lg shadow-accent/20 flex items-center justify-center">
                <i class="fas fa-filter mr-2"></i> Filter
            </button>
            <a href="<?= base_url('keuangan/jurnal-umum/export-pdf?' . http_build_query(service('request')->getGet())) ?>" target="_blank" class="flex-1 md:flex-none px-6 py-2.5 bg-red-500/20 text-red-400 border border-red-500/50 hover:bg-red-500/30 font-bold rounded-xl transition flex items-center justify-center whitespace-nowrap">
                <i class="fas fa-file-pdf mr-2"></i> Export PDF
            </a>
            <a href="<?= base_url('keuangan/jurnal-umum/delete-all') ?>" onclick="return confirm('APAKAH ANDA YAKIN INGIN MENGHAPUS SEMUA DATA JURNAL UMUM?\n\nData yang sudah dihapus tidak bisa dikembalikan lagi.')" class="flex-1 md:flex-none px-6 py-2.5 bg-red-900/40 text-red-300 border border-red-500/50 hover:bg-red-700/60 font-bold rounded-xl transition flex items-center justify-center whitespace-nowrap" title="Hapus seluruh data jurnal">
                <i class="fas fa-dumpster mr-2"></i> Hapus Semua
            </a>
            <button type="button" onclick="openDeleteYearModal()" class="flex-1 md:flex-none px-6 py-2.5 bg-orange-500/20 text-orange-400 border border-orange-500/50 hover:bg-orange-500/30 font-bold rounded-xl transition flex items-center justify-center whitespace-nowrap" title="Hapus data jurnal per tahun">
                <i class="fas fa-calendar-times mr-2"></i> Hapus per Tahun
            </button>
    
        </div>
    </form>
</div>

<!-- Table -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-black/50 border-b border-white/10">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider w-16">No</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">No. Reff</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Akun</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Deskripsi</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Debit</th>
                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Kredit</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($jurnalData)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-book text-4xl mb-3 opacity-20"></i>
                            <p>Belum ada jurnal pada periode ini.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php 
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; 
                    $perPage = 50; 
                    $no = ($page - 1) * $perPage + 1; 
                    foreach ($jurnalData as $row): 
                    ?>
                        <tr class="hover:bg-white/5 transition group">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono"><?= $no++ ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                <?= date('d/m/Y', strtotime($row['tanggal'])) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-400">
                                <?= esc($row['no_reff']) ?>
                                <?php if (!empty($row['is_duplicate'])): ?>
                                    <span class="inline-flex items-center gap-1 ml-2 px-2 py-0.5 rounded text-[10px] font-bold bg-red-500/20 text-red-400 border border-red-500/30" title="Terdeteksi sebagai duplikat (Tanggal, Akun, dan Nominal sama)">
                                        <i class="fas fa-exclamation-triangle"></i> Duplikat
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex flex-col">
                                    <span class="font-bold text-white"><?= esc($row['nama_akun']) ?></span>
                                    <span class="text-xs text-gray-500 font-mono"><?= esc($row['kode_akun']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-400 truncate max-w-xs">
                                <?= esc($row['deskripsi']) ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-right font-mono text-gray-300">
                                <?= $row['debit'] > 0 ? 'Rp ' . number_format($row['debit'], 2, ',', '.') : '-' ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-right font-mono text-gray-300">
                                <?= $row['kredit'] > 0 ? 'Rp ' . number_format($row['kredit'], 2, ',', '.') : '-' ?>
                            </td>
                            <td class="px-6 py-4 text-center text-sm">
                                <button onclick="openEditModal(<?= $row['id'] ?>, '<?= esc($row['tanggal']) ?>', '<?= esc($row['no_reff']) ?>', '<?= esc($row['akun_id']) ?>', '<?= esc($row['deskripsi']) ?>', <?= $row['debit'] ?>, <?= $row['kredit'] ?>)" class="text-blue-400 hover:text-blue-300 transition-colors" title="Edit Jurnal">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if ($pager && $pager->getPageCount() > 1): ?>
        <div class="p-4 border-t border-white/10 bg-black/20">
            <?= $pager->links('default', 'tailwind_full') ?>
        </div>
    <?php endif; ?>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm">
    <div class="bg-[#111] border border-white/10 rounded-2xl w-full max-w-lg shadow-2xl overflow-hidden">
        <div class="p-4 border-b border-white/10 flex justify-between items-center bg-black/50">
            <h3 class="text-lg font-bold text-white">Edit Jurnal Umum</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-white transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="editForm" action="<?= base_url('keuangan/jurnal-umum/update') ?>" method="post" class="p-6">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="edit_id">
            
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Tanggal</label>
                    <input type="date" name="tanggal" id="edit_tanggal" required class="w-full bg-black border border-white/10 rounded-xl px-4 py-2.5 focus:border-accent focus:outline-none text-white text-sm">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">No. Reff</label>
                    <input type="text" name="no_reff" id="edit_no_reff" required class="w-full bg-black border border-white/10 rounded-xl px-4 py-2.5 focus:border-accent focus:outline-none text-white text-sm">
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Akun</label>
                    <select name="akun_id" id="edit_akun_id" required class="w-full bg-black border border-white/10 rounded-xl px-4 py-2.5 focus:border-accent focus:outline-none text-white text-sm">
                        <?php foreach($akunList as $akun): ?>
                            <option value="<?= $akun['id'] ?>"><?= esc($akun['kode_akun'] . ' - ' . $akun['nama_akun']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Deskripsi</label>
                    <textarea name="deskripsi" id="edit_deskripsi" rows="2" class="w-full bg-black border border-white/10 rounded-xl px-4 py-2.5 focus:border-accent focus:outline-none text-white text-sm"></textarea>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Debit</label>
                        <input type="number" name="debit" id="edit_debit" step="0.01" min="0" required class="w-full bg-black border border-white/10 rounded-xl px-4 py-2.5 focus:border-accent focus:outline-none text-white text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Kredit</label>
                        <input type="number" name="kredit" id="edit_kredit" step="0.01" min="0" required class="w-full bg-black border border-white/10 rounded-xl px-4 py-2.5 focus:border-accent focus:outline-none text-white text-sm">
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-white/10">
                <button type="button" onclick="closeEditModal()" class="px-6 py-2.5 bg-gray-800 hover:bg-gray-700 text-white font-bold rounded-xl transition">Batal</button>
                <button type="submit" class="px-6 py-2.5 bg-accent hover:bg-white text-black font-bold rounded-xl transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete by Year Modal -->
<div id="deleteYearModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm">
    <div class="bg-[#111] border border-white/10 rounded-2xl w-full max-w-sm shadow-2xl overflow-hidden">
        <div class="p-4 border-b border-white/10 flex justify-between items-center bg-black/50">
            <h3 class="text-lg font-bold text-white">Hapus Jurnal per Tahun</h3>
            <button type="button" onclick="closeDeleteYearModal()" class="text-gray-400 hover:text-white transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form action="<?= base_url('keuangan/jurnal-umum/delete-by-year') ?>" method="post" class="p-6">
            <?= csrf_field() ?>
            <div class="mb-4">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Pilih Tahun</label>
                <select name="year" required class="w-full bg-black border border-white/10 rounded-xl px-4 py-2.5 focus:border-accent focus:outline-none text-white text-sm">
                    <option value="">-- Pilih Tahun --</option>
                    <?php
                        $currentYear = date('Y');
                        for ($y = $currentYear + 1; $y >= $currentYear - 10; $y--) {
                            echo "<option value=\"$y\">$y</option>";
                        }
                    ?>
                </select>
            </div>
            <div class="bg-red-500/10 border border-red-500/20 p-3 rounded-lg mb-6 text-xs text-red-400 flex items-start gap-2">
                <i class="fas fa-exclamation-triangle mt-0.5"></i>
                <p>Data jurnal pada tahun yang dipilih akan dihapus secara permanen. Tindakan ini tidak bisa dibatalkan.</p>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-white/10">
                <button type="button" onclick="closeDeleteYearModal()" class="px-6 py-2.5 bg-gray-800 hover:bg-gray-700 text-white font-bold rounded-xl transition">Batal</button>
                <button type="submit" onclick="return confirm('Anda yakin ingin menghapus SEMUA data di tahun tersebut?');" class="px-6 py-2.5 bg-red-600 hover:bg-red-500 text-white font-bold rounded-xl transition">Hapus Data</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openDeleteYearModal() {
        document.getElementById('deleteYearModal').classList.remove('hidden');
        document.getElementById('deleteYearModal').classList.add('flex');
    }

    function closeDeleteYearModal() {
        document.getElementById('deleteYearModal').classList.add('hidden');
        document.getElementById('deleteYearModal').classList.remove('flex');
    }

    function openEditModal(id, tanggal, no_reff, akun_id, deskripsi, debit, kredit) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_tanggal').value = tanggal;
        document.getElementById('edit_no_reff').value = no_reff;
        document.getElementById('edit_akun_id').value = akun_id;
        document.getElementById('edit_deskripsi').value = deskripsi;
        document.getElementById('edit_debit').value = debit;
        document.getElementById('edit_kredit').value = kredit;
        
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editModal').classList.remove('flex');
    }
</script>

<?= $this->endSection() ?>