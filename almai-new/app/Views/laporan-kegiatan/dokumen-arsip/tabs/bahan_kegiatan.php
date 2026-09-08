<div class="p-4 border-b border-white/5 flex justify-between items-center bg-white/[0.02]">
    <h2 class="text-lg font-bold text-white">Dokumen Bahan Kegiatan (Persetujuan Bappebti)</h2>
    <button onclick="openModal('modalBahanKegiatan')" class="bg-accent hover:bg-accent/80 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
        <i class="fas fa-plus"></i> Tambah Bahan Kegiatan
    </button>
</div>
<div class="overflow-x-auto">
    <table class="w-full text-sm text-left">
        <thead class="text-xs text-gray-400 uppercase bg-white/5 border-b border-white/10">
            <tr>
                <th class="p-4">No</th>
                <th class="p-4">Judul Bahan</th>
                <th class="p-4">Jenis Kegiatan</th>
                <th class="p-4">Tanggal Pengajuan</th>
                <th class="p-4">Tanggal Persetujuan</th>
                <th class="p-4">No Surat Persetujuan</th>
                <th class="p-4">Status</th>
                <th class="p-4">Download File</th>
                <th class="p-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($bahanKegiatanData)): ?>
                <tr><td colspan="9" class="p-6 text-center text-gray-500 italic">Data kosong.</td></tr>
            <?php else: ?>
                <?php $no=1; foreach($bahanKegiatanData as $row): ?>
                <tr class="border-b border-white/5 hover:bg-white/[0.02]">
                    <td class="p-4"><?= $no++ ?></td>
                    <td class="p-4 font-bold text-white"><?= esc($row['judul_bahan']) ?></td>
                    <td class="p-4"><?= esc($row['jenis_kegiatan']) ?></td>
                    <td class="p-4"><?= $row['tgl_pengajuan'] ? date('d/m/Y', strtotime($row['tgl_pengajuan'])) : '-' ?></td>
                    <td class="p-4"><?= $row['tgl_persetujuan'] ? date('d/m/Y', strtotime($row['tgl_persetujuan'])) : '-' ?></td>
                    <td class="p-4"><?= esc($row['no_surat_persetujuan']) ?></td>
                    <td class="p-4">
                        <?php if($row['status'] === 'Disetujui') echo '<span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs rounded-full">Disetujui</span>'; ?>
                        <?php if($row['status'] === 'Dalam Proses') echo '<span class="px-2 py-1 bg-yellow-500/20 text-yellow-400 text-xs rounded-full">Dalam Proses</span>'; ?>
                        <?php if($row['status'] === 'Ditolak') echo '<span class="px-2 py-1 bg-red-500/20 text-red-400 text-xs rounded-full">Ditolak</span>'; ?>
                    </td>
                    <td class="p-4">
                        <?php if($row['file_path']): ?>
                            <a href="<?= base_url($row['file_path']) ?>" target="_blank" class="text-blue-400 hover:text-blue-300 underline"><i class="fas fa-download"></i> PDF</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td class="p-4 text-center">
                        <button onclick="openModal('modalBahanKegiatan<?= $row['id'] ?>')" class="text-blue-400 hover:text-blue-300 mr-2"><i class="fas fa-edit"></i></button>
                        <a href="<?= base_url('laporan-kegiatan/dokumen-arsip/delete-bahan-kegiatan/'.$row['id']) ?>" onclick="return confirm('Yakin ingin menghapus?')" class="text-red-400 hover:text-red-300"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div id="modalBahanKegiatan<?= $row['id'] ?>" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
                    <div class="bg-[#1a1a1a] border border-white/10 rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                        <form action="<?= base_url('laporan-kegiatan/dokumen-arsip/update-bahan-kegiatan/'.$row['id']) ?>" method="POST" enctype="multipart/form-data">
                            <div class="p-6 border-b border-white/5 flex justify-between items-center">
                                <h3 class="text-xl font-bold text-white">Edit Bahan Kegiatan</h3>
                                <button type="button" onclick="closeModal('modalBahanKegiatan<?= $row['id'] ?>')" class="text-gray-400 hover:text-white"><i class="fas fa-times text-xl"></i></button>
                            </div>
                            <div class="p-6 space-y-4 text-left">
                                <div><label class="block text-sm text-gray-400 mb-1">Judul Bahan</label><input type="text" name="judul_bahan" value="<?= esc($row['judul_bahan']) ?>" required class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                                <div><label class="block text-sm text-gray-400 mb-1">Jenis Kegiatan</label><input type="text" name="jenis_kegiatan" value="<?= esc($row['jenis_kegiatan']) ?>" required class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div><label class="block text-sm text-gray-400 mb-1">Tanggal Pengajuan</label><input type="date" name="tgl_pengajuan" value="<?= $row['tgl_pengajuan'] ?>" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                                    <div><label class="block text-sm text-gray-400 mb-1">Tanggal Persetujuan</label><input type="date" name="tgl_persetujuan" value="<?= $row['tgl_persetujuan'] ?>" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                                </div>
                                <div><label class="block text-sm text-gray-400 mb-1">No Surat Persetujuan</label><input type="text" name="no_surat_persetujuan" value="<?= esc($row['no_surat_persetujuan']) ?>" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                                <div>
                                    <label class="block text-sm text-gray-400 mb-1">Status</label>
                                    <select name="status" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white">
                                        <option value="Disetujui" <?= $row['status'] == 'Disetujui' ? 'selected' : '' ?>>Disetujui</option>
                                        <option value="Dalam Proses" <?= $row['status'] == 'Dalam Proses' ? 'selected' : '' ?>>Dalam Proses</option>
                                        <option value="Ditolak" <?= $row['status'] == 'Ditolak' ? 'selected' : '' ?>>Ditolak</option>
                                    </select>
                                </div>
                                <div><label class="block text-sm text-gray-400 mb-1">Upload File Baru (Opsional)</label><input type="file" name="file_path" accept=".pdf,.doc,.docx" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                            </div>
                            <div class="p-6 border-t border-white/5 flex justify-end gap-3">
                                <button type="button" onclick="closeModal('modalBahanKegiatan<?= $row['id'] ?>')" class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-white/5 transition">Batal</button>
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
<div id="modalBahanKegiatan" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
    <div class="bg-[#1a1a1a] border border-white/10 rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <form action="<?= base_url('laporan-kegiatan/dokumen-arsip/store-bahan-kegiatan') ?>" method="POST" enctype="multipart/form-data">
            <div class="p-6 border-b border-white/5 flex justify-between items-center">
                <h3 class="text-xl font-bold text-white">Tambah Bahan Kegiatan</h3>
                <button type="button" onclick="closeModal('modalBahanKegiatan')" class="text-gray-400 hover:text-white"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6 space-y-4">
                <div><label class="block text-sm text-gray-400 mb-1">Judul Bahan</label><input type="text" name="judul_bahan" required class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                <div><label class="block text-sm text-gray-400 mb-1">Jenis Kegiatan</label><input type="text" name="jenis_kegiatan" required class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm text-gray-400 mb-1">Tanggal Pengajuan</label><input type="date" name="tgl_pengajuan" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                    <div><label class="block text-sm text-gray-400 mb-1">Tanggal Persetujuan</label><input type="date" name="tgl_persetujuan" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                </div>
                <div><label class="block text-sm text-gray-400 mb-1">No Surat Persetujuan</label><input type="text" name="no_surat_persetujuan" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Status</label>
                    <select name="status" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white">
                        <option value="Disetujui">Disetujui</option>
                        <option value="Dalam Proses" selected>Dalam Proses</option>
                        <option value="Ditolak">Ditolak</option>
                    </select>
                </div>
                <div><label class="block text-sm text-gray-400 mb-1">Upload File</label><input type="file" name="file_path" accept=".pdf,.doc,.docx" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
            </div>
            <div class="p-6 border-t border-white/5 flex justify-end gap-3">
                <button type="button" onclick="closeModal('modalBahanKegiatan')" class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-white/5 transition">Batal</button>
                <button type="submit" class="bg-accent hover:bg-accent/80 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition">Simpan Bahan Kegiatan</button>
            </div>
        </form>
    </div>
</div>
