<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>
<div class="w-full pb-12">
    <div class="mb-6 flex flex-col md:flex-row justify-between md:items-center gap-4 px-6 md:px-0">
        <h1 class="text-xl md:text-2xl font-black text-white uppercase tracking-tight">Profil Perusahaan</h1>
        <a href="<?= base_url('laporan-kegiatan/profile/create') ?>" class="bg-accent hover:bg-white text-black px-6 py-2 rounded-lg text-xs font-bold uppercase tracking-widest shadow transition text-center">
            <i class="fas fa-edit mr-2"></i> Edit Data
        </a>
    </div>

    <!-- Alert Success -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="mb-6 bg-accent/10 border border-accent/20 text-accent px-4 py-3 rounded-xl flex items-center gap-3">
            <i class="fas fa-check-circle"></i>
            <span class="text-sm font-bold"><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>

    <div class="p-6 space-y-8">
        
        <!-- Table 1: Identitas Perusahaan -->
        <div class="bg-[#111] border border-white/10 overflow-hidden text-sm rounded-2xl shadow-xl">
            <div class="bg-black/50 text-accent font-bold text-center py-3 border-b border-white/10 uppercase tracking-widest flex items-center justify-center gap-2">
                <i class="fas fa-building text-accent"></i> IDENTITAS PERUSAHAAN
            </div>
            <table class="w-full text-left border-collapse">
                <tbody>
                    <?php 
                    $labels = [
                        'Nama Perusahaan' => 'nama_perusahaan',
                        'Nomor Izin Bappebti' => 'no_izin_bappebti',
                        'Tanggal Izin' => 'tanggal_izin',
                        'Masa Berlaku Izin' => 'masa_berlaku',
                        'Alamat Kantor' => 'alamat',
                        'Kota' => 'kota',
                        'Provinsi' => 'provinsi',
                        'Kode Pos' => 'kode_pos',
                        'No. Telepon' => 'no_telp',
                        'Email Perusahaan' => 'email',
                        'Website / Platform' => 'website',
                    ];
                    foreach($labels as $label => $key): ?>
                    <tr class="border-b border-white/5 last:border-b-0 hover:bg-white/[0.02]">
                        <td class="w-1/3 p-4 font-bold text-gray-400 border-r border-white/5 bg-black/20"><?= $label ?></td>
                        <td class="w-2/3 p-4 text-white">
                            <?php 
                            $val = esc($profile['identitas'][$key] ?? '');
                            if ($key === 'website' && !empty($val)) {
                                echo '<a href="'.$val.'" target="_blank" class="text-accent hover:underline">'.$val.'</a>';
                            } else {
                                echo $val ?: '-';
                            }
                            ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Table 2: Pejabat & Kontak Perusahaan -->
        <div class="bg-[#111] border border-white/10 overflow-hidden text-sm rounded-2xl shadow-xl">
            <div class="bg-black/50 text-accent font-bold text-center py-3 border-b border-white/10 uppercase tracking-widest flex items-center justify-center gap-2">
                <i class="fas fa-users-tie text-accent"></i> PEJABAT & KONTAK PERUSAHAAN
            </div>
            <table class="w-full text-left border-collapse">
                <tbody>
                    <?php 
                    $pejabatLabels = [
                        'Nama Direktur Utama' => 'nama_dirut',
                        'No. Telepon Direktur' => 'telp_dirut',
                        'Email Direktur' => 'email_dirut',
                        'Nama Kontak Person' => 'nama_kontak',
                        'No. HP Kontak Person' => 'hp_kontak',
                        'Email Kontak Person' => 'email_kontak',
                    ];
                    foreach($pejabatLabels as $label => $key): ?>
                    <tr class="border-b border-white/5 last:border-b-0 hover:bg-white/[0.02]">
                        <td class="w-1/3 p-4 font-bold text-gray-400 border-r border-white/5 bg-black/20"><?= $label ?></td>
                        <td class="w-2/3 p-4 text-white"><?= esc($profile['pejabat'][$key] ?? '') ?: '-' ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Table 3: Produk & Layanan -->
        <div class="bg-[#111] border border-white/10 overflow-hidden text-sm rounded-2xl shadow-xl">
            <div class="bg-black/50 text-accent font-bold text-center py-3 border-b border-white/10 uppercase tracking-widest flex items-center justify-center gap-2">
                <i class="fas fa-box text-accent"></i> PRODUK & LAYANAN ALMAI TRADING PLATFORM
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="bg-black/20 text-gray-400 text-center font-bold text-[10px] uppercase tracking-wider">
                            <th class="p-3 border-b border-r border-white/5 w-1/6">Kategori Layanan</th>
                            <th class="p-3 border-b border-r border-white/5 w-1/6">Nama Produk / Layanan</th>
                            <th class="p-3 border-b border-r border-white/5 w-1/4">Deskripsi Singkat</th>
                            <th class="p-3 border-b border-r border-white/5 w-1/6">Regulasi / Dasar Hukum</th>
                            <th class="p-3 border-b border-r border-white/5 w-1/12">Status</th>
                            <th class="p-3 border-b border-white/5 w-1/6">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <?php if (empty($profile['produk_layanan'])): ?>
                            <tr>
                                <td colspan="6" class="p-4 text-center text-gray-500 italic">Belum ada data produk/layanan.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($profile['produk_layanan'] as $p): ?>
                                <tr class="hover:bg-white/[0.02] text-center">
                                    <td class="p-3 border-r border-white/5 text-left text-gray-300">
                                        <i class="fas fa-caret-right text-white/20 mr-2 text-[10px]"></i> <?= esc($p['kategori'] ?? '') ?: '-' ?>
                                    </td>
                                    <td class="p-3 border-r border-white/5 font-bold text-white text-left"><?= esc($p['nama'] ?? '') ?: '-' ?></td>
                                    <td class="p-3 border-r border-white/5 text-left text-xs text-gray-400"><?= esc($p['deskripsi'] ?? '') ?: '-' ?></td>
                                    <td class="p-3 border-r border-white/5 text-gray-500 text-xs"><?= esc($p['regulasi'] ?? '') ?: '-' ?></td>
                                    <td class="p-3 border-r border-white/5">
                                        <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase <?= ($p['status']??'')==='Aktif' ? 'bg-accent/10 text-accent' : 'bg-red-500/10 text-red-500' ?>"><?= esc($p['status'] ?? '') ?: '-' ?></span>
                                    </td>
                                    <td class="p-3 text-xs text-gray-500"><?= esc($p['keterangan'] ?? '') ?: '-' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Table 4: Kualifikasi Pengguna -->
        <div class="bg-[#111] border border-white/10 overflow-hidden text-sm rounded-2xl shadow-xl">
            <div class="bg-black/50 text-accent font-bold text-center py-3 border-b border-white/10 uppercase tracking-widest flex items-center justify-center gap-2">
                <i class="fas fa-id-card text-accent"></i> KUALIFIKASI PENGGUNA
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="bg-black/20 text-gray-400 text-center font-bold text-[10px] uppercase tracking-wider">
                            <th class="p-3 border-b border-r border-white/5">Tipe Pengguna</th>
                            <th class="p-3 border-b border-r border-white/5">Hak Akses</th>
                            <th class="p-3 border-b border-r border-white/5">Kewajiban / Prasyarat</th>
                            <th class="p-3 border-b border-r border-white/5">Dapat Memberikan Nasihat?</th>
                            <th class="p-3 border-b border-r border-white/5">Diawasi Oleh</th>
                            <th class="p-3 border-b border-white/5">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <?php if (empty($profile['kualifikasi_pengguna'])): ?>
                            <tr>
                                <td colspan="6" class="p-4 text-center text-gray-500 italic">Belum ada data kualifikasi pengguna.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($profile['kualifikasi_pengguna'] as $q): ?>
                                <tr class="hover:bg-white/[0.02] text-center">
                                    <td class="p-3 border-r border-white/5 font-bold text-left text-white"><?= esc($q['tipe'] ?? '') ?: '-' ?></td>
                                    <td class="p-3 border-r border-white/5 text-left text-xs text-gray-400"><?= esc($q['akses'] ?? '') ?: '-' ?></td>
                                    <td class="p-3 border-r border-white/5 text-left text-xs text-gray-400"><?= esc($q['kewajiban'] ?? '') ?: '-' ?></td>
                                    <td class="p-3 border-r border-white/5">
                                        <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase <?= ($q['nasihat']??'')==='Ya' ? 'bg-accent/10 text-accent' : (($q['nasihat']??'')==='Terbatas' ? 'bg-yellow-500/10 text-yellow-500' : 'bg-red-500/10 text-red-500') ?>"><?= esc($q['nasihat'] ?? '') ?: '-' ?></span>
                                    </td>
                                    <td class="p-3 border-r border-white/5 text-xs text-gray-500"><?= esc($q['diawasi'] ?? '') ?: '-' ?></td>
                                    <td class="p-3 text-xs text-gray-500"><?= esc($q['keterangan'] ?? '') ?: '-' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Table 5: Penjelasan WPA & CWPA -->
        <div class="bg-[#111] border border-white/10 overflow-hidden text-sm rounded-2xl shadow-xl">
            <div class="bg-black/50 text-accent font-bold text-center py-3 border-b border-white/10 uppercase tracking-widest flex items-center justify-center gap-2">
                <i class="fas fa-info-circle text-accent"></i> PENJELASAN WPA & CALON WPA (CWPA)
            </div>
            <table class="w-full text-left border-collapse">
                <tbody>
                    <tr class="border-b border-white/5 hover:bg-white/[0.02]">
                        <td class="w-1/4 p-4 font-bold text-gray-400 border-r border-white/5 bg-black/20 align-top text-center uppercase text-[10px] tracking-widest">WPA <br>(Wakil Penasihat Berjangka)</td>
                        <td class="w-3/4 p-4 text-gray-300 whitespace-pre-wrap text-xs leading-relaxed"><?= esc($profile['penjelasan_wpa_cwpa']['wpa'] ?? '') ?: '-' ?></td>
                    </tr>
                    <tr class="hover:bg-white/[0.02]">
                        <td class="w-1/4 p-4 font-bold text-gray-400 border-r border-white/5 bg-black/20 align-top text-center uppercase text-[10px] tracking-widest">CWPA <br>(Calon Wakil Penasihat Berjangka)</td>
                        <td class="w-3/4 p-4 text-gray-300 whitespace-pre-wrap text-xs leading-relaxed"><?= esc($profile['penjelasan_wpa_cwpa']['cwpa'] ?? '') ?: '-' ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>
<?= $this->endSection() ?>
