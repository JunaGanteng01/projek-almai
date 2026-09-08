<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white mb-2">Dokumen — Arsip & Referensi Perizinan</h1>
            <p class="text-gray-400 text-sm">Kelola arsip dokumen legalitas, izin WPA, bahan kegiatan, dan referensi regulasi.</p>
        </div>
    </div>

    <!-- Alert Success -->
    <?php if(session()->getFlashdata('success')): ?>
    <div class="bg-green-500/10 border border-green-500/20 text-green-400 p-4 rounded-lg mb-6">
        <?= session()->getFlashdata('success') ?>
    </div>
    <?php endif; ?>

    <!-- Bagian 1: Dokumen Legalitas Perusahaan -->
    <div class="bg-[#111] rounded-xl border border-white/5 shadow-xl mb-8">
        <div class="p-4 border-b border-white/5 flex justify-between items-center bg-white/[0.02]">
            <h2 class="text-lg font-bold text-white">Dokumen Legalitas Perusahaan</h2>
            <a href="<?= base_url('laporan-kegiatan/dokumen-arsip/create-legalitas') ?>" class="bg-accent hover:bg-accent/80 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Dokumen
            </a>
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
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($legalitasData)): ?>
                        <tr><td colspan="9" class="p-6 text-center text-gray-500 italic">Data kosong.</td></tr>
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
                            <td class="p-4 text-center">
                                <a href="<?= base_url('laporan-kegiatan/dokumen-arsip/edit-legalitas/'.$row['id']) ?>" class="text-blue-400 hover:text-blue-300 mr-2"><i class="fas fa-edit"></i></a>
                                <a href="<?= base_url('laporan-kegiatan/dokumen-arsip/delete-legalitas/'.$row['id']) ?>" onclick="return confirm('Yakin ingin menghapus?')" class="text-red-400 hover:text-red-300"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bagian 2: Dokumen Izin WPA -->
    <div class="bg-[#111] rounded-xl border border-white/5 shadow-xl mb-8">
        <div class="p-4 border-b border-white/5 flex justify-between items-center bg-white/[0.02]">
            <h2 class="text-lg font-bold text-white">Dokumen Izin Wakil Penasihat Berjangka (WPA)</h2>
            <a href="<?= base_url('laporan-kegiatan/dokumen-arsip/create-izin-wpa') ?>" class="bg-accent hover:bg-accent/80 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Dokumen Izin
            </a>
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
                                <a href="<?= base_url('laporan-kegiatan/dokumen-arsip/edit-izin-wpa/'.$row['id']) ?>" class="text-blue-400 hover:text-blue-300 mr-2"><i class="fas fa-edit"></i></a>
                                <a href="<?= base_url('laporan-kegiatan/dokumen-arsip/delete-izin-wpa/'.$row['id']) ?>" onclick="return confirm('Yakin ingin menghapus?')" class="text-red-400 hover:text-red-300"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bagian 3: Dokumen Bahan Kegiatan -->
    <div class="bg-[#111] rounded-xl border border-white/5 shadow-xl mb-8">
        <div class="p-4 border-b border-white/5 flex justify-between items-center bg-white/[0.02]">
            <h2 class="text-lg font-bold text-white">Dokumen Bahan Kegiatan (Persetujuan Bappebti)</h2>
            <a href="<?= base_url('laporan-kegiatan/dokumen-arsip/create-bahan-kegiatan') ?>" class="bg-accent hover:bg-accent/80 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Bahan Kegiatan
            </a>
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
                                <a href="<?= base_url('laporan-kegiatan/dokumen-arsip/edit-bahan-kegiatan/'.$row['id']) ?>" class="text-blue-400 hover:text-blue-300 mr-2"><i class="fas fa-edit"></i></a>
                                <a href="<?= base_url('laporan-kegiatan/dokumen-arsip/delete-bahan-kegiatan/'.$row['id']) ?>" onclick="return confirm('Yakin ingin menghapus?')" class="text-red-400 hover:text-red-300"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bagian 4: Referensi Regulasi -->
    <div class="bg-[#111] rounded-xl border border-white/5 shadow-xl mb-8">
        <div class="p-4 border-b border-white/5 flex justify-between items-center bg-white/[0.02]">
            <h2 class="text-lg font-bold text-white">Referensi Regulasi</h2>
            <a href="<?= base_url('laporan-kegiatan/dokumen-arsip/create-regulasi') ?>" class="bg-accent hover:bg-accent/80 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Regulasi
            </a>
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
                                <a href="<?= base_url('laporan-kegiatan/dokumen-arsip/edit-regulasi/'.$row['id']) ?>" class="text-blue-400 hover:text-blue-300 mr-2"><i class="fas fa-edit"></i></a>
                                <a href="<?= base_url('laporan-kegiatan/dokumen-arsip/delete-regulasi/'.$row['id']) ?>" onclick="return confirm('Yakin ingin menghapus?')" class="text-red-400 hover:text-red-300"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
