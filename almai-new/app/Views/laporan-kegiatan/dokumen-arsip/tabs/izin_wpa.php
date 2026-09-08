<div class="p-4 border-b border-white/5 flex justify-between items-center bg-white/[0.02]">
    <h2 class="text-lg font-bold text-white">Dokumen Izin Wakil Penasihat Berjangka (WPA)</h2>
    <button onclick="openModal('modalIzinWpa')" class="bg-accent hover:bg-accent/80 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
        <i class="fas fa-plus"></i> Tambah Dokumen Izin
    </button>
</div>
<div class="overflow-x-auto">
    <table class="w-full text-sm text-left">
        <thead class="text-xs text-gray-400 uppercase bg-white/5 border-b border-white/10">
            <tr>
                <th class="p-4">No</th>
                <th class="p-4">Nama WPA</th>
                <th class="p-4">Nomor Izin</th>
                <th class="p-4">Tanggal Izin</th>
                <th class="p-4">Tanggal Expired</th>
                <th class="p-4">Gelar/Sertifikat</th>
                <th class="p-4">Status</th>
                <th class="p-4">Catatan</th>
                <th class="p-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($izinWpaData)): ?>
                <tr><td colspan="9" class="p-6 text-center text-gray-500 italic">Data kosong.</td></tr>
            <?php else: ?>
                <?php $no=1; foreach($izinWpaData as $row): ?>
                <tr class="border-b border-white/5 hover:bg-white/[0.02]">
                    <td class="p-4"><?= $no++ ?></td>
                    <td class="p-4 font-bold text-accent"><?= esc($row['wpa_name']) ?: 'WPA Terhapus' ?></td>
                    <td class="p-4"><?= esc($row['nomor_izin']) ?></td>
                    <td class="p-4"><?= $row['tanggal_izin'] ? date('d/m/Y', strtotime($row['tanggal_izin'])) : '-' ?></td>
                    <td class="p-4"><?= $row['tanggal_expired'] ? date('d/m/Y', strtotime($row['tanggal_expired'])) : '-' ?></td>
                    <td class="p-4"><?= esc($row['gelar_sertifikat']) ?></td>
                    <td class="p-4">
                        <?php if($row['status'] === 'Aktif') echo '<span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs rounded-full">Aktif</span>'; ?>
                        <?php if($row['status'] === 'Proses Perpanjangan') echo '<span class="px-2 py-1 bg-yellow-500/20 text-yellow-400 text-xs rounded-full">Proses Perpanjangan</span>'; ?>
                        <?php if($row['status'] === 'Tidak Aktif') echo '<span class="px-2 py-1 bg-red-500/20 text-red-400 text-xs rounded-full">Tidak Aktif</span>'; ?>
                    </td>
                    <td class="p-4 max-w-xs truncate" title="<?= esc($row['catatan']) ?>"><?= esc($row['catatan']) ?></td>
                    <td class="p-4 text-center">
                        <button onclick="openModal('modalIzinWpa<?= $row['id'] ?>')" class="text-blue-400 hover:text-blue-300 mr-2"><i class="fas fa-edit"></i></button>
                        <a href="<?= base_url('laporan-kegiatan/dokumen-arsip/delete-izin-wpa/'.$row['id']) ?>" onclick="return confirm('Yakin ingin menghapus?')" class="text-red-400 hover:text-red-300"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <div id="modalIzinWpa<?= $row['id'] ?>" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
                    <div class="bg-[#1a1a1a] border border-white/10 rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                        <form action="<?= base_url('laporan-kegiatan/dokumen-arsip/update-izin-wpa/'.$row['id']) ?>" method="POST">
                            <div class="p-6 border-b border-white/5 flex justify-between items-center">
                                <h3 class="text-xl font-bold text-white">Edit Dokumen Izin WPA</h3>
                                <button type="button" onclick="closeModal('modalIzinWpa<?= $row['id'] ?>')" class="text-gray-400 hover:text-white"><i class="fas fa-times text-xl"></i></button>
                            </div>
                            <div class="p-6 space-y-4 text-left">
                                <div>
                                    <label class="block text-sm text-gray-400 mb-1">Pilih WPA</label>
                                    <select name="wpa_id" required class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white">
                                        <option value="">-- Pilih WPA --</option>
                                        <?php foreach($wpaList as $w): ?>
                                            <option value="<?= $w['id'] ?>" <?= $row['wpa_id'] == $w['id'] ? 'selected' : '' ?>><?= esc($w['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div><label class="block text-sm text-gray-400 mb-1">Nomor Izin</label><input type="text" name="nomor_izin" value="<?= esc($row['nomor_izin']) ?>" required class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div><label class="block text-sm text-gray-400 mb-1">Tanggal Izin</label><input type="date" name="tanggal_izin" value="<?= $row['tanggal_izin'] ?>" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                                    <div><label class="block text-sm text-gray-400 mb-1">Tanggal Expired</label><input type="date" name="tanggal_expired" value="<?= $row['tanggal_expired'] ?>" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                                </div>
                                <div><label class="block text-sm text-gray-400 mb-1">Gelar/Sertifikat</label><input type="text" name="gelar_sertifikat" value="<?= esc($row['gelar_sertifikat']) ?>" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                                <div>
                                    <label class="block text-sm text-gray-400 mb-1">Status</label>
                                    <select name="status" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white">
                                        <option value="Aktif" <?= $row['status'] == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
                                        <option value="Proses Perpanjangan" <?= $row['status'] == 'Proses Perpanjangan' ? 'selected' : '' ?>>Proses Perpanjangan</option>
                                        <option value="Tidak Aktif" <?= $row['status'] == 'Tidak Aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                                    </select>
                                </div>
                                <div><label class="block text-sm text-gray-400 mb-1">Catatan</label><textarea name="catatan" rows="3" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"><?= esc($row['catatan']) ?></textarea></div>
                            </div>
                            <div class="p-6 border-t border-white/5 flex justify-end gap-3">
                                <button type="button" onclick="closeModal('modalIzinWpa<?= $row['id'] ?>')" class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-white/5 transition">Batal</button>
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
<div id="modalIzinWpa" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
    <div class="bg-[#1a1a1a] border border-white/10 rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <form action="<?= base_url('laporan-kegiatan/dokumen-arsip/store-izin-wpa') ?>" method="POST">
            <div class="p-6 border-b border-white/5 flex justify-between items-center">
                <h3 class="text-xl font-bold text-white">Tambah Dokumen Izin WPA</h3>
                <button type="button" onclick="closeModal('modalIzinWpa')" class="text-gray-400 hover:text-white"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Pilih WPA</label>
                    <select name="wpa_id" required class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white">
                        <option value="">-- Pilih WPA --</option>
                        <?php foreach($wpaList as $w): ?>
                            <option value="<?= $w['id'] ?>"><?= esc($w['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div><label class="block text-sm text-gray-400 mb-1">Nomor Izin</label><input type="text" name="nomor_izin" required class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                <div class="grid grid-cols-2 gap-4">
                    <div><label class="block text-sm text-gray-400 mb-1">Tanggal Izin</label><input type="date" name="tanggal_izin" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                    <div><label class="block text-sm text-gray-400 mb-1">Tanggal Expired</label><input type="date" name="tanggal_expired" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                </div>
                <div><label class="block text-sm text-gray-400 mb-1">Gelar/Sertifikat</label><input type="text" name="gelar_sertifikat" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></div>
                <div>
                    <label class="block text-sm text-gray-400 mb-1">Status</label>
                    <select name="status" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white">
                        <option value="Aktif">Aktif</option>
                        <option value="Proses Perpanjangan">Proses Perpanjangan</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
                    </select>
                </div>
                <div><label class="block text-sm text-gray-400 mb-1">Catatan</label><textarea name="catatan" rows="3" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2.5 text-white"></textarea></div>
            </div>
            <div class="p-6 border-t border-white/5 flex justify-end gap-3">
                <button type="button" onclick="closeModal('modalIzinWpa')" class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-300 hover:bg-white/5 transition">Batal</button>
                <button type="submit" class="bg-accent hover:bg-accent/80 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition">Simpan Izin WPA</button>
            </div>
        </form>
    </div>
</div>
