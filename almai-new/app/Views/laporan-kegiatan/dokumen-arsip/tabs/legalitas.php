<div class="p-4 border-b border-white/5 flex justify-between items-center bg-white/[0.02]">
    <h2 class="text-lg font-bold text-white">Dokumen Legalitas Perusahaan</h2>
    <button onclick="openModal('modalLegalitas')" class="bg-accent hover:bg-accent/80 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
        <i class="fas fa-plus"></i> Tambah Dokumen
    </button>
</div>
<div class="overflow-x-auto">
    <table class="w-full text-sm text-left">
        <thead class="text-xs text-gray-400 uppercase bg-white/5 border-b border-white/10">
            <tr>
                <th class="p-4">No</th>
                <th class="p-4">Nama Dokumen</th>
                <th class="p-4">Nomor Dokumen</th>
                <th class="p-4">Tanggal Terbit</th>
                <th class="p-4">Tanggal Expired</th>
                <th class="p-4">Diterbitkan Oleh</th>
                <th class="p-4">Status</th>
                <th class="p-4">Download File</th>
                <th class="p-4">Keterangan</th>
                <th class="p-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($legalitasData)): ?>
                <tr><td colspan="10" class="p-6 text-center text-gray-500 italic">Data kosong.</td></tr>
            <?php else: ?>
                <?php $no=1; foreach($legalitasData as $row): ?>
                <tr class="border-b border-white/5 hover:bg-white/[0.02]">
                    <td class="p-4"><?= $no++ ?></td>
                    <td class="p-4 font-bold text-white"><?= esc($row['nama_dokumen']) ?></td>
                    <td class="p-4"><?= esc($row['nomor_dokumen']) ?></td>
                    <td class="p-4"><?= $row['tanggal_terbit'] ? date('d/m/Y', strtotime($row['tanggal_terbit'])) : '-' ?></td>
                    <td class="p-4"><?= $row['tanggal_expired'] ? date('d/m/Y', strtotime($row['tanggal_expired'])) : '-' ?></td>
                    <td class="p-4"><?= esc($row['diterbitkan_oleh']) ?></td>
                    <td class="p-4">
                        <?php if($row['status'] === 'Aktif') echo '<span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs rounded-full">Aktif</span>'; ?>
                        <?php if($row['status'] === 'Proses Perpanjangan') echo '<span class="px-2 py-1 bg-yellow-500/20 text-yellow-400 text-xs rounded-full">Proses Perpanjangan</span>'; ?>
                        <?php if($row['status'] === 'Tidak Aktif') echo '<span class="px-2 py-1 bg-red-500/20 text-red-400 text-xs rounded-full">Tidak Aktif</span>'; ?>
                    </td>
                    <td class="p-4">
                        <?php if($row['file_path']): ?>
                            <a href="<?= base_url($row['file_path']) ?>" target="_blank" class="text-blue-400 hover:text-blue-300 underline"><i class="fas fa-download"></i> PDF</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td class="p-4 max-w-xs truncate" title="<?= esc($row['keterangan']) ?>"><?= esc($row['keterangan']) ?></td>
                    <td class="p-4 text-center">
                        <button onclick="openModal('modalLegalitas<?= $row['id'] ?>')" class="text-blue-400 hover:text-blue-300 mr-2"><i class="fas fa-edit"></i></button>
                        <a href="<?= base_url('laporan-kegiatan/dokumen-arsip/delete-legalitas/'.$row['id']) ?>" onclick="return confirm('Yakin ingin menghapus?')" class="text-red-400 hover:text-red-300"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div id="modalLegalitas<?= $row['id'] ?>" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
                    <div class="bg-[#1a1a1a] border border-white/10 rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                        <form action="<?= base_url('laporan-kegiatan/dokumen-arsip/update-legalitas/'.$row['id']) ?>" method="POST" enctype="multipart/form-data">
                            <div class="p-6 border-b border-white/5 flex justify-between items-center">
                                <h3 class="text-xl font-bold text-white">Edit Dokumen Legalitas</h3>
                                <button type="button" onclick="closeModal('modalLegalitas<?= $row['id'] ?>')" class="text-gray-400 hover:text-white"><i class="fas fa-times text-xl"></i></button>
                            </div>
                            <div class="p-6 space-y-4">
                                <div><label class="block text-sm text-gray-400 mb-1">Nama Dokumen</label><input type="text" name="nama_dokumen" value="<?= esc($row['nama_dokumen']) ?>" required class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                                <div><label class="block text-sm text-gray-400 mb-1">Nomor Dokumen</label><input type="text" name="nomor_dokumen" value="<?= esc($row['nomor_dokumen']) ?>" required class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div><label class="block text-sm text-gray-400 mb-1">Tanggal Terbit</label><input type="date" name="tanggal_terbit" value="<?= $row['tanggal_terbit'] ?>" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                                    <div><label class="block text-sm text-gray-400 mb-1">Tanggal Expired</label><input type="date" name="tanggal_expired" value="<?= $row['tanggal_expired'] ?>" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                                </div>
                                <div><label class="block text-sm text-gray-400 mb-1">Diterbitkan Oleh</label><input type="text" name="diterbitkan_oleh" value="<?= esc($row['diterbitkan_oleh']) ?>" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                                <div>
                                    <label class="block text-sm text-gray-400 mb-1">Status</label>
                                    <select name="status" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white">
                                        <option value="Aktif" <?= $row['status'] == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                                        <option value="Proses Perpanjangan" <?= $row['status'] == 'Proses Perpanjangan' ? 'selected' : '' ?>>Proses Perpanjangan</option>
                                        <option value="Tidak Aktif" <?= $row['status'] == 'Tidak Aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                                    </select>
                                </div>
                                <div><label class="block text-sm text-gray-400 mb-1">Upload File Baru (Opsional)</label><input type="file" name="file_path" accept=".pdf,.doc,.docx" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                                <div><label class="block text-sm text-gray-400 mb-1">Keterangan</label><textarea name="keterangan" rows="3" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"><?= esc($row['keterangan']) ?></textarea></div>
                            </div>
                            <div class="p-6 border-t border-white/5 flex justify-end gap-3">
                                <button type="button" onclick="closeModal('modalLegalitas<?= $row['id'] ?>')" class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-white/5 transition">Batal</button>
                                <button type="submit" class="bg-accent hover:bg-accent/80 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div id="modalLegalitas" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
    <div class="bg-[#1a1a1a] border border-white/10 rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <form action="<?= base_url('laporan-kegiatan/dokumen-arsip/store-legalitas') ?>" method="POST" enctype="multipart/form-data">
            <div class="p-6 border-b border-white/5 flex justify-between items-center">
                <h3 class="text-xl font-bold text-white">Tambah Dokumen Legalitas</h3>
                <button type="button" onclick="closeModal('modalLegalitas')" class="text-gray-400 hover:text-white"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6 space-y-4">
                <div><label class="block text-sm text-gray-400 mb-1">Nama Dokumen</label><input type="text" name="nama_dokumen" required class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                <div><label class="block text-sm text-gray-400 mb-1">Nomor Dokumen</label><input type="text" name="nomor_dokumen" required class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm text-gray-400 mb-1">Tanggal Terbit</label><input type="date" name="tanggal_terbit" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                    <div><label class="block text-sm text-gray-400 mb-1">Tanggal Expired</label><input type="date" name="tanggal_expired" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                </div>
                <div><label class="block text-sm text-gray-400 mb-1">Diterbitkan Oleh</label><input type="text" name="diterbitkan_oleh" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Status</label>
                    <select name="status" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white">
                        <option value="Aktif">Aktif</option>
                        <option value="Proses Perpanjangan">Proses Perpanjangan</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                    </select>
                </div>
                <div><label class="block text-sm text-gray-400 mb-1">Upload File</label><input type="file" name="file_path" accept=".pdf,.doc,.docx" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                <div><label class="block text-sm text-gray-400 mb-1">Keterangan</label><textarea name="keterangan" rows="3" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></textarea></div>
            </div>
            <div class="p-6 border-t border-white/5 flex justify-end gap-3">
                <button type="button" onclick="closeModal('modalLegalitas')" class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-white/5 transition">Batal</button>
                <button type="submit" class="bg-accent hover:bg-accent/80 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition">Simpan Dokumen</button>
            </div>
        </form>
    </div>
</div>
