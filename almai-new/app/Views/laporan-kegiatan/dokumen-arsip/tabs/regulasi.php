<div class="p-4 border-b border-white/5 flex justify-between items-center bg-white/[0.02]">
    <h2 class="text-lg font-bold text-white">Referensi Regulasi</h2>
    <button onclick="openModal('modalRegulasi')" class="bg-accent hover:bg-accent/80 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
        <i class="fas fa-plus"></i> Tambah Regulasi
    </button>
</div>
<div class="overflow-x-auto">
    <table class="w-full text-sm text-left">
        <thead class="text-xs text-gray-400 uppercase bg-white/5 border-b border-white/10">
            <tr>
                <th class="p-4">No.</th>
                <th class="p-4">Nomor Regulasi</th>
                <th class="p-4">Tentang</th>
                <th class="p-4">Keterangan</th>
                <th class="p-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($regulasiData)): ?>
                <tr><td colspan="5" class="p-6 text-center text-gray-500 italic">Data kosong.</td></tr>
            <?php else: ?>
                <?php $no=1; foreach($regulasiData as $row): ?>
                <tr class="border-b border-white/5 hover:bg-white/[0.02]">
                    <td class="p-4"><?= $no++ ?></td>
                    <td class="p-4 font-bold text-white"><?= esc($row['nomor_regulasi']) ?></td>
                    <td class="p-4"><?= esc($row['tentang']) ?></td>
                    <td class="p-4 max-w-xs truncate" title="<?= esc($row['keterangan']) ?>"><?= esc($row['keterangan']) ?></td>
                    <td class="p-4 text-center">
                        <button onclick="openModal('modalRegulasi<?= $row['id'] ?>')" class="text-blue-400 hover:text-blue-300 mr-2"><i class="fas fa-edit"></i></button>
                        <a href="<?= base_url('laporan-kegiatan/dokumen-arsip/delete-regulasi/'.$row['id']) ?>" onclick="return confirm('Yakin ingin menghapus?')" class="text-red-400 hover:text-red-300"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div id="modalRegulasi<?= $row['id'] ?>" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
                    <div class="bg-[#1a1a1a] border border-white/10 rounded-xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
                        <form action="<?= base_url('laporan-kegiatan/dokumen-arsip/update-regulasi/'.$row['id']) ?>" method="POST">
                            <div class="p-6 border-b border-white/5 flex justify-between items-center">
                                <h3 class="text-xl font-bold text-white">Edit Referensi Regulasi</h3>
                                <button type="button" onclick="closeModal('modalRegulasi<?= $row['id'] ?>')" class="text-gray-400 hover:text-white"><i class="fas fa-times text-xl"></i></button>
                            </div>
                            <div class="p-6 space-y-4 text-left">
                                <div><label class="block text-sm text-gray-400 mb-1">Nomor Regulasi</label><input type="text" name="nomor_regulasi" value="<?= esc($row['nomor_regulasi']) ?>" required class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                                <div><label class="block text-sm text-gray-400 mb-1">Tentang</label><input type="text" name="tentang" value="<?= esc($row['tentang']) ?>" required class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                                <div><label class="block text-sm text-gray-400 mb-1">Keterangan</label><textarea name="keterangan" rows="3" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"><?= esc($row['keterangan']) ?></textarea></div>
                            </div>
                            <div class="p-6 border-t border-white/5 flex justify-end gap-3">
                                <button type="button" onclick="closeModal('modalRegulasi<?= $row['id'] ?>')" class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-white/5 transition">Batal</button>
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
<div id="modalRegulasi" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
    <div class="bg-[#1a1a1a] border border-white/10 rounded-xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <form action="<?= base_url('laporan-kegiatan/dokumen-arsip/store-regulasi') ?>" method="POST">
            <div class="p-6 border-b border-white/5 flex justify-between items-center">
                <h3 class="text-xl font-bold text-white">Tambah Referensi Regulasi</h3>
                <button type="button" onclick="closeModal('modalRegulasi')" class="text-gray-400 hover:text-white"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6 space-y-4">
                <div><label class="block text-sm text-gray-400 mb-1">Nomor Regulasi</label><input type="text" name="nomor_regulasi" required class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                <div><label class="block text-sm text-gray-400 mb-1">Tentang</label><input type="text" name="tentang" required class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                <div><label class="block text-sm text-gray-400 mb-1">Keterangan</label><textarea name="keterangan" rows="3" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></textarea></div>
            </div>
            <div class="p-6 border-t border-white/5 flex justify-end gap-3">
                <button type="button" onclick="closeModal('modalRegulasi')" class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-white/5 transition">Batal</button>
                <button type="submit" class="bg-accent hover:bg-accent/80 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition">Simpan Regulasi</button>
            </div>
        </form>
    </div>
</div>
