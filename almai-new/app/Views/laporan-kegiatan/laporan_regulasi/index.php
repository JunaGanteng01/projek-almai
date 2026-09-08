<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>
<?php
/**
 * @var string $jenis
 * @var string $bulan
 * @var string $tahun
 * @var string $namaPerusahaan
 * @var string $noIzin
 * @var array $rekap
 * @var array $klien
 * @var array $rekapTahunan
 * @var array $klienBaru
 * @var int $klienAktif
 */

$bulanList = [
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
    '04' => 'April', '05' => 'Mei', '06' => 'Juni',
    '07' => 'Juli', '08' => 'Agustus', '09' => 'September',
    '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
];
$tahunList = range(date('Y') - 5, date('Y') + 1);
?>

<div class="w-full pb-12">
    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row justify-between md:items-center gap-4 px-6 md:px-0">
        <h1 class="text-xl md:text-2xl font-black text-white uppercase tracking-tight">Laporan Regulasi</h1>
        <a href="<?= base_url('laporan-kegiatan/laporan-regulasi/exportPdf?jenis='.$jenis.'&bulan='.$bulan.'&tahun='.$tahun) ?>" target="_blank" class="bg-red-600 hover:bg-red-500 text-white px-6 py-2 rounded-lg text-xs font-bold uppercase tracking-widest shadow transition text-center flex items-center gap-2">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </div>

    <!-- Filter Form -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl mb-6">
        <form method="GET" action="<?= base_url('laporan-kegiatan/laporan-regulasi') ?>" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:w-1/4">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Jenis Laporan</label>
                <select name="jenis" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:border-accent" onchange="this.form.submit()">
                    <option value="bulanan" <?= $jenis === 'bulanan' ? 'selected' : '' ?>>Laporan Bulanan</option>
                    <option value="tahunan" <?= $jenis === 'tahunan' ? 'selected' : '' ?>>Laporan Tahunan</option>
                </select>
            </div>
            <?php if($jenis === 'bulanan'): ?>
            <div class="w-full md:w-1/4">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Bulan</label>
                <select name="bulan" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:border-accent">
                    <?php foreach($bulanList as $k => $v): ?>
                        <option value="<?= $k ?>" <?= $bulan === $k ? 'selected' : '' ?>><?= $v ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
            <div class="w-full md:w-1/4">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Tahun</label>
                <select name="tahun" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:border-accent">
                    <?php foreach($tahunList as $t): ?>
                        <option value="<?= $t ?>" <?= $tahun == $t ? 'selected' : '' ?>><?= $t ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="w-full md:w-1/4">
                <button type="submit" class="w-full bg-accent hover:bg-white text-black px-6 py-2 rounded-xl text-sm font-bold uppercase tracking-widest transition">
                    Tampilkan
                </button>
            </div>
        </form>
    </div>

    <?php if($jenis === 'bulanan'): ?>
    <!-- LAPORAN BULANAN -->
    <div class="space-y-6">
        <!-- Identitas & Periode -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl">
            <h2 class="text-lg font-bold text-accent uppercase tracking-widest mb-4 border-b border-white/5 pb-2">Identitas & Periode Laporan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div><span class="text-gray-400">Nama Perusahaan:</span> <strong class="text-white"><?= esc($namaPerusahaan) ?></strong></div>
                <div><span class="text-gray-400">No Izin Bappebti:</span> <strong class="text-white"><?= esc($noIzin) ?></strong></div>
                <div><span class="text-gray-400">Bulan Laporan:</span> <strong class="text-white"><?= $bulanList[$bulan] ?></strong></div>
                <div><span class="text-gray-400">Tahun Laporan:</span> <strong class="text-white"><?= $tahun ?></strong></div>
                <div><span class="text-gray-400">Periode Laporan:</span> <strong class="text-white">1 - <?= date('t', strtotime("$tahun-$bulan-01")) ?> <?= $bulanList[$bulan] ?> <?= $tahun ?></strong></div>
                <div><span class="text-gray-400">Tanggal Dibuat:</span> <strong class="text-white"><?= date('d F Y, H:i') ?> WITA</strong></div>
                <div><span class="text-gray-400">Status Laporan:</span> <strong class="text-green-500">Tepat Waktu</strong></div>
            </div>
        </div>

        <!-- A. WPA -->
        <div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-xl mb-6">
            <div class="p-6 border-b border-white/5">
                <h2 class="text-lg font-bold text-accent uppercase tracking-widest">a. Daftar Wakil Penasihat Berjangka (WPA)</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="bg-black/50 text-gray-400 text-center font-bold text-xs uppercase tracking-wider">
                            <th class="p-4 border-b border-r border-white/10 w-16">No.</th>
                            <th class="p-4 border-b border-r border-white/10 text-left">Nama Wakil Penasihat Berjangka</th>
                            <th class="p-4 border-b border-r border-white/10">Nomor Perizinan</th>
                            <th class="p-4 border-b border-white/10">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-sm text-center">
                        <?php if(!empty($listWpa)): ?>
                            <?php foreach($listWpa as $i => $row): ?>
                            <tr class="hover:bg-white/[0.02]">
                                <td class="p-4 border-r border-white/5 text-gray-300"><?= $i+1 ?>.</td>
                                <td class="p-4 border-r border-white/5 text-left text-white font-bold"><?= esc($row['nama_wpa']) ?></td>
                                <td class="p-4 border-r border-white/5 text-gray-300"><?= esc($row['nomor_izin']) ?></td>
                                <td class="p-4 text-gray-400"><?= esc($row['keterangan']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="p-4 text-gray-500 italic">Belum ada data</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
        </div>

        <!-- B. KLIEN -->
        <div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-xl mb-6">
            <div class="p-6 border-b border-white/5">
                <h2 class="text-lg font-bold text-accent uppercase tracking-widest">b. Jumlah Klien aktif sesuai jenis atau kategori Klien</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="bg-black/50 text-gray-400 text-center font-bold text-xs uppercase tracking-wider">
                            <th class="p-4 border-b border-r border-white/10 w-16">No.</th>
                            <th class="p-4 border-b border-r border-white/10 text-left">Kategori Klien</th>
                            <th class="p-4 border-b border-r border-white/10">Jumlah Klien</th>
                            <th class="p-4 border-b border-white/10">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-sm text-center">
                        <tr class="hover:bg-white/[0.02]">
                            <td class="p-4 border-r border-white/5 text-gray-300">1.</td>
                            <td class="p-4 border-r border-white/5 text-left text-white font-bold">Perorangan</td>
                            <td class="p-4 border-r border-white/5 text-gray-300"><?= $klien['perorangan'] ?></td>
                            <td class="p-4 text-gray-400"><?= $klien['perorangan'] ?> Klien aktif (berbayar)</td>
                        </tr>
                        <tr class="hover:bg-white/[0.02]">
                            <td class="p-4 border-r border-white/5 text-gray-300">2.</td>
                            <td class="p-4 border-r border-white/5 text-left text-white font-bold">Perusahaan</td>
                            <td class="p-4 border-r border-white/5 text-gray-300"><?= $klien['perusahaan'] ?></td>
                            <td class="p-4 text-gray-400"><?= $klien['perusahaan_baru'] ?> Perusahaan baru bulan ini</td>
                        </tr>
                        <tr class="hover:bg-white/[0.02]">
                            <td class="p-4 border-r border-white/5 text-gray-300">3.</td>
                            <td class="p-4 border-r border-white/5 text-left text-white font-bold">User</td>
                            <td class="p-4 border-r border-white/5 text-gray-300"><?= $klien['total_aktif'] ?></td>
                            <td class="p-4 text-gray-400"><?= $klien['baru'] ?> User baru bulan ini</td>
                        </tr>
                    </tbody>
                </table>
        </div>

        <!-- C. SEMINAR -->
        <div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-xl mb-6">
            <div class="p-6 border-b border-white/5">
                <h2 class="text-lg font-bold text-accent uppercase tracking-widest">c. Kegiatan Seminar/Sosialisasi/Focus Group Discussion (FGD)</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[1000px]">
                    <thead>
                        <tr class="bg-black/50 text-gray-400 text-center font-bold text-xs uppercase tracking-wider">
                            <th class="p-4 border-b border-r border-white/10 w-12">No.</th>
                            <th class="p-4 border-b border-r border-white/10 text-left">Judul</th>
                            <th class="p-4 border-b border-r border-white/10">Jumlah Klien/Peserta</th>
                            <th class="p-4 border-b border-r border-white/10">Produk</th>
                            <th class="p-4 border-b border-r border-white/10">Nama WPA</th>
                            <th class="p-4 border-b border-r border-white/10">Lokasi</th>
                            <th class="p-4 border-b border-r border-white/10">Tanggal</th>
                            <th class="p-4 border-b border-white/10">Topik Seminar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-sm text-center">
                        <?php if(!empty($detailSeminar)): ?>
                            <?php foreach($detailSeminar as $i => $row): ?>
                            <tr class="hover:bg-white/[0.02]">
                                <td class="p-4 border-r border-white/5 text-gray-300"><?= $i+1 ?>.</td>
                                <td class="p-4 border-r border-white/5 text-left text-white font-bold"><?= esc($row['judul']) ?></td>
                                <td class="p-4 border-r border-white/5 text-gray-300"><?= $row['jml_peserta'] ?></td>
                                <td class="p-4 border-r border-white/5 text-gray-400"><?= esc($row['produk'] ?? '') ?></td>
                                <td class="p-4 border-r border-white/5 text-gray-400"><?= esc($row['nama_wpa'] ?? '-') ?></td>
                                <td class="p-4 border-r border-white/5 text-gray-400"><?= esc($row['lokasi'] ?? '-') ?></td>
                                <td class="p-4 border-r border-white/5 text-gray-400">
                                    <?php if (!empty($row['is_recurring'])): 
                                        $dMap = ['Senin'=>'Monday','Selasa'=>'Tuesday','Rabu'=>'Wednesday','Kamis'=>'Thursday','Jumat'=>'Friday','Sabtu'=>'Saturday','Minggu'=>'Sunday'];
                                        $dEn = $dMap[ucfirst(strtolower($row['recurring_day'] ?? ''))] ?? 'Monday';
                                        $dArr = [];
                                        $ts = strtotime("first $dEn of $tahun-$bulan");
                                        while (date('m', $ts) == $bulan) {
                                            $dArr[] = date('d', $ts);
                                            $ts = strtotime('+1 week', $ts);
                                        }
                                        $mIndo = $bulanList[$bulan] ?? date('M', strtotime("$tahun-$bulan-01"));
                                    ?>
                                        <?= implode(', ', $dArr) ?> <?= $mIndo ?> <?= $tahun ?><br>
                                        <span class="text-[10px] opacity-75"><?= esc($row['recurring_time']) ?></span>
                                    <?php else: ?>
                                        <?= (!empty($row['tanggal']) && strpos($row['tanggal'], '0000-00-00') === false) ? date('d M Y, H:i', strtotime($row['tanggal'])) : '-' ?>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4 text-gray-400"><?= esc($row['topik']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="8" class="p-4 text-gray-500 italic">Belum ada data</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- D. PELATIHAN -->
        <div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-xl mb-6">
            <div class="p-6 border-b border-white/5">
                <h2 class="text-lg font-bold text-accent uppercase tracking-widest">d. Kegiatan Pelatihan atau Simulasi Perdagangan</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[1000px]">
                    <thead>
                        <tr class="bg-black/50 text-gray-400 text-center font-bold text-xs uppercase tracking-wider">
                            <th class="p-4 border-b border-r border-white/10 w-12">No.</th>
                            <th class="p-4 border-b border-r border-white/10 text-left">Judul</th>
                            <th class="p-4 border-b border-r border-white/10">Jumlah Klien</th>
                            <th class="p-4 border-b border-r border-white/10">Produk</th>
                            <th class="p-4 border-b border-r border-white/10">Nama WPA</th>
                            <th class="p-4 border-b border-r border-white/10">Lokasi</th>
                            <th class="p-4 border-b border-r border-white/10">Tanggal</th>
                            <th class="p-4 border-b border-white/10">Topik Seminar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-sm text-center">
                        <?php if(!empty($detailPelatihan)): ?>
                            <?php foreach($detailPelatihan as $i => $row): ?>
                            <tr class="hover:bg-white/[0.02]">
                                <td class="p-4 border-r border-white/5 text-gray-300"><?= $i+1 ?>.</td>
                                <td class="p-4 border-r border-white/5 text-left text-white font-bold"><?= esc($row['judul']) ?></td>
                                <td class="p-4 border-r border-white/5 text-gray-300"><?= $row['jml_peserta'] ?></td>
                                <td class="p-4 border-r border-white/5 text-gray-400"><?= esc($row['produk'] ?? '') ?></td>
                                <td class="p-4 border-r border-white/5 text-gray-400"><?= esc($row['nama_wpa'] ?? '-') ?></td>
                                <td class="p-4 border-r border-white/5 text-gray-400"><?= esc($row['lokasi'] ?? '-') ?></td>
                                <td class="p-4 border-r border-white/5 text-gray-400">
                                    <?php if (!empty($row['is_recurring'])): 
                                        $dMap = ['Senin'=>'Monday','Selasa'=>'Tuesday','Rabu'=>'Wednesday','Kamis'=>'Thursday','Jumat'=>'Friday','Sabtu'=>'Saturday','Minggu'=>'Sunday'];
                                        $dEn = $dMap[ucfirst(strtolower($row['recurring_day'] ?? ''))] ?? 'Monday';
                                        $dArr = [];
                                        $ts = strtotime("first $dEn of $tahun-$bulan");
                                        while (date('m', $ts) == $bulan) {
                                            $dArr[] = date('d', $ts);
                                            $ts = strtotime('+1 week', $ts);
                                        }
                                        $mIndo = $bulanList[$bulan] ?? date('M', strtotime("$tahun-$bulan-01"));
                                    ?>
                                        <?= implode(', ', $dArr) ?> <?= $mIndo ?> <?= $tahun ?><br>
                                        <span class="text-[10px] opacity-75"><?= esc($row['recurring_time']) ?></span>
                                    <?php else: ?>
                                        <?= (!empty($row['tanggal']) && strpos($row['tanggal'], '0000-00-00') === false) ? date('d M Y, H:i', strtotime($row['tanggal'])) : '-' ?>
                                    <?php endif; ?>
                                </td>
                                <td class="p-4 text-gray-400"><?= esc($row['topik']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="8" class="p-4 text-gray-500 italic">Belum ada data</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- E. SIGNAL -->
        <div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-xl mb-6">
            <div class="p-6 border-b border-white/5">
                <h2 class="text-lg font-bold text-accent uppercase tracking-widest">e. Kegiatan pemberian Signal</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="bg-black/50 text-gray-400 text-center font-bold text-xs uppercase tracking-wider">
                            <th class="p-4 border-b border-r border-white/10 w-12">No.</th>
                            <th class="p-4 border-b border-r border-white/10">Jumlah Klien</th>
                            <th class="p-4 border-b border-r border-white/10">Jumlah Nasihat</th>
                            <th class="p-4 border-b border-r border-white/10">Produk</th>
                            <th class="p-4 border-b border-r border-white/10">Nama WPA</th>
                            <th class="p-4 border-b border-white/10">Media</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-sm text-center">
                        <?php if(!empty($detailSignal)): ?>
                            <?php foreach($detailSignal as $i => $row): ?>
                            <tr class="hover:bg-white/[0.02]">
                                <td class="p-4 border-r border-white/5 text-gray-300"><?= $i+1 ?>.</td>
                                <td class="p-4 border-r border-white/5 text-gray-300"><?= $row['jml_peserta'] ?></td>
                                <td class="p-4 border-r border-white/5 text-gray-300"><?= $row['jml_nasihat'] ?></td>
                                <td class="p-4 border-r border-white/5 text-gray-400"><?= esc($row['produk'] ?? '') ?></td>
                                <td class="p-4 border-r border-white/5 text-gray-400"><?= esc($row['nama_wpa'] ?? '') ?></td>
                                <td class="p-4 text-gray-400"><?= esc($row['media'] ?? '') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="p-4 text-gray-500 italic">Belum ada data</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- F. KONSULTASI -->
        <div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-xl mb-6">
            <div class="p-6 border-b border-white/5">
                <h2 class="text-lg font-bold text-accent uppercase tracking-widest">f. Kegiatan pemberian Konsultasi</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="bg-black/50 text-gray-400 text-center font-bold text-xs uppercase tracking-wider">
                            <th class="p-4 border-b border-r border-white/10 w-12">No.</th>
                            <th class="p-4 border-b border-r border-white/10 text-left">Nama Klien</th>
                            <th class="p-4 border-b border-r border-white/10">Jumlah Nasihat</th>
                            <th class="p-4 border-b border-r border-white/10">Produk</th>
                            <th class="p-4 border-b border-r border-white/10">Nama WPA</th>
                            <th class="p-4 border-b border-white/10">Media</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-sm text-center">
                        <?php if(!empty($detailKonsultasi)): ?>
                            <?php foreach($detailKonsultasi as $i => $row): ?>
                            <tr class="hover:bg-white/[0.02]">
                                <td class="p-4 border-r border-white/5 text-gray-300"><?= $i+1 ?>.</td>
                                <td class="p-4 border-r border-white/5 text-left text-white font-bold"><?= esc($row['nama_klien'] ?? '') ?></td>
                                <td class="p-4 border-r border-white/5 text-gray-300"><?= $row['jml_nasihat'] ?></td>
                                <td class="p-4 border-r border-white/5 text-gray-400"><?= esc($row['produk'] ?? '') ?></td>
                                <td class="p-4 border-r border-white/5 text-gray-400"><?= esc($row['nama_wpa'] ?? '') ?></td>
                                <td class="p-4 text-gray-400"><?= esc($row['media'] ?? '') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="p-4 text-gray-500 italic">Belum ada data</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php else: ?>
    <!-- LAPORAN TAHUNAN -->
    <div class="space-y-6">
        <!-- Identitas & Periode -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl">
            <h2 class="text-lg font-bold text-accent uppercase tracking-widest mb-4 border-b border-white/5 pb-2">Identitas & Periode Laporan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div><span class="text-gray-400">Nama Perusahaan:</span> <strong class="text-white"><?= esc($namaPerusahaan) ?></strong></div>
                <div><span class="text-gray-400">No Izin Bappebti:</span> <strong class="text-white"><?= esc($noIzin) ?></strong></div>
                <div><span class="text-gray-400">Tahun Laporan:</span> <strong class="text-white"><?= $tahun ?></strong></div>
                <div><span class="text-gray-400">Periode Laporan:</span> <strong class="text-white">Januari - Desember <?= $tahun ?></strong></div>
            </div>
        </div>

        <!-- Table Tahunan -->
        <div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-xl">
            <div class="p-6 border-b border-white/5">
                <h2 class="text-lg font-bold text-accent uppercase tracking-widest">Rekap Kegiatan Tahunan</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[1000px]">
                    <thead>
                        <tr class="bg-black/50 text-gray-400 text-center font-bold text-[10px] uppercase tracking-wider">
                            <th class="p-3 border-b border-r border-white/10 text-left">Jenis Kegiatan</th>
                            <?php foreach(array_values($bulanList) as $b): ?>
                                <th class="p-3 border-b border-r border-white/10"><?= substr($b, 0, 3) ?></th>
                            <?php endforeach; ?>
                            <th class="p-3 border-b border-white/10 text-accent">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-sm text-center">
                        <?php 
                        $types = [
                            'seminar' => 'Seminar/Sosialisasi/FGD',
                            'pelatihan' => 'Pelatihan/Simulasi Perdagangan',
                            'signal' => 'Pemberian Signal',
                            'konsultasi' => 'Pemberian Konsultasi',
                            'ea' => 'Expert Advisor (EA)',
                            'lainnya' => 'Kegiatan Lainnya'
                        ];
                        foreach($types as $key => $label): 
                            $rowTotal = 0;
                        ?>
                        <tr class="hover:bg-white/[0.02]">
                            <td class="p-3 border-r border-white/5 text-left text-white font-bold"><?= $label ?></td>
                            <?php for($i=1; $i<=12; $i++): 
                                $b = str_pad($i, 2, '0', STR_PAD_LEFT);
                                $val = $rekapTahunan[$b][$key]['kegiatan'];
                                $rowTotal += $val;
                            ?>
                                <td class="p-3 border-r border-white/5 text-gray-300"><?= $val ?: '-' ?></td>
                            <?php endfor; ?>
                            <td class="p-3 text-accent font-bold"><?= $rowTotal ?></td>
                        </tr>
                        <?php endforeach; ?>
                        
                        <!-- Klien Baru -->
                        <tr class="bg-white/5">
                            <td class="p-3 border-r border-white/5 text-left text-white font-bold">Klien Baru</td>
                            <?php 
                            $totBaru = 0;
                            for($i=1; $i<=12; $i++): 
                                $b = str_pad($i, 2, '0', STR_PAD_LEFT);
                                $val = $klienBaru[$b];
                                $totBaru += $val;
                            ?>
                                <td class="p-3 border-r border-white/5 text-gray-300"><?= $val ?: '-' ?></td>
                            <?php endfor; ?>
                            <td class="p-3 text-accent font-bold"><?= $totBaru ?></td>
                        </tr>
                        
                        <!-- Total Klien Aktif -->
                        <tr class="bg-accent/10 text-accent font-bold">
                            <td class="p-3 border-r border-white/5 text-left" colspan="13">Total Klien Aktif (Hingga Saat Ini)</td>
                            <td class="p-3"><?= $klienAktif ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- G. Expert Advisor -->
    <div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-xl mb-6">
        <div class="p-6 border-b border-white/5">
            <h2 class="text-lg font-bold text-accent uppercase tracking-widest">g. Kegiatan pelaksanaan penyampaian Nasihat berbasis teknologi informasi berupa <i class="italic">Expert Advisor</i></h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                    <thead>
                        <tr class="bg-black/50 text-accent text-center font-bold text-[10px] uppercase tracking-wider">
                            <th class="p-4 border-b border-r border-white/10 w-12">No.</th>
                            <th class="p-4 border-b border-r border-white/10">Nama Layanan</th>
                            <th class="p-4 border-b border-r border-white/10">Penjelasan terkait Layanan</th>
                            <th class="p-4 border-b border-r border-white/10">Jumlah Klien</th>
                            <th class="p-4 border-b border-r border-white/10">Produk</th>
                            <th class="p-4 border-b border-r border-white/10">Nama WPA</th>
                            <th class="p-4 border-b border-white/10">Winning Rate</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <?php if (empty($klien['expert_advisor'])): ?>
                        <tr>
                            <td colspan="7" class="p-6 text-center text-gray-500 italic">Tidak ada data untuk bulan ini</td>
                        </tr>
                        <?php else: ?>
                        <?php $no = 1; foreach ($klien['expert_advisor'] as $ea): ?>
                        <tr class="hover:bg-white/[0.02] text-center">
                            <td class="p-4 border-r border-white/5 text-gray-300"><?= $no++ ?>.</td>
                            <td class="p-4 border-r border-white/5 text-left text-gray-300"><?= esc($ea['judul'] ?? '-') ?></td>
                            <td class="p-4 border-r border-white/5 text-left text-gray-300 max-w-xs break-words"><?= esc($ea['topik'] ?? '-') ?></td>
                            <td class="p-4 border-r border-white/5 text-gray-300"><?= esc($ea['jml_klien'] ?? '-') ?></td>
                            <td class="p-4 border-r border-white/5 text-gray-300"><?= esc($ea['produk'] ?? '-') ?></td>
                            <td class="p-4 border-r border-white/5 text-gray-300"><?= esc($ea['nama_wpa'] ?? '-') ?></td>
                            <td class="p-4 text-gray-300"><?= esc($ea['winning_rate'] ?? '-') ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
        </div>
    </div>

    <!-- H. Pedoman Perilaku -->
    <div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-xl mb-6">
        <div class="p-6 border-b border-white/5">
            <h2 class="text-lg font-bold text-accent uppercase tracking-widest">h. Pelaksanaan pedoman perilaku</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1200px]">
                    <thead>
                        <tr class="bg-black/50 text-accent text-center font-bold text-[10px] uppercase tracking-wider">
                            <th rowspan="2" class="p-4 border-b border-r border-white/10 w-12 align-middle">No.</th>
                            <th rowspan="2" class="p-4 border-b border-r border-white/10 align-middle">Nama Klien</th>
                            <th rowspan="2" class="p-4 border-b border-r border-white/10 align-middle">Nomor Akun</th>
                            <th colspan="3" class="p-4 border-b border-r border-white/10">dokumen pernyataan adanya risiko</th>
                            <th colspan="3" class="p-4 border-b border-r border-white/10">Dokumen Perjanjian Pemberian Jasa</th>
                            <th colspan="2" class="p-4 border-b border-r border-white/10">Dokumen Keterangan Perusahaan</th>
                            <th colspan="2" class="p-4 border-b border-white/10">dokumen pernyataan adanya risiko</th>
                        </tr>
                        <tr class="bg-black/50 text-accent text-center font-bold text-[10px] uppercase tracking-wider">
                            <th class="p-2 border-b border-r border-white/10">Latar Belakang*</th>
                            <th class="p-2 border-b border-r border-white/10">Keadaan Keuangan*</th>
                            <th class="p-2 border-b border-r border-white/10">Profil Risiko*</th>
                            <th class="p-2 border-b border-r border-white/10">Apakah telah dilakukan penjelasan*</th>
                            <th class="p-2 border-b border-r border-white/10">Apakah telah disetujui/ditandatangani*</th>
                            <th class="p-2 border-b border-r border-white/10">Nomor Dokumen</th>
                            <th class="p-2 border-b border-r border-white/10">Apakah telah dilakukan penjelasan*</th>
                            <th class="p-2 border-b border-r border-white/10">Apakah telah disetujui/ditandatangani*</th>
                            <th class="p-2 border-b border-r border-white/10">Apakah telah dilakukan penjelasan*</th>
                            <th class="p-2 border-b border-white/10">Apakah telah disetujui/ditandatangani*</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <?php if (empty($klien['pedoman_perilaku'])): ?>
                        <tr>
                            <td colspan="14" class="p-6 text-center text-gray-500 italic">Tidak ada data untuk bulan ini</td>
                        </tr>
                        <?php else: ?>
                        <?php $no = 1; foreach ($klien['pedoman_perilaku'] as $pp): ?>
                        <tr class="hover:bg-white/[0.02] text-center">
                            <td class="p-3 border-r border-white/5 text-gray-300"><?= $no++ ?>.</td>
                            <td class="p-3 border-r border-white/5 text-left text-gray-300"><?= esc($pp['nama_klien'] ?? '-') ?></td>
                            <td class="p-3 border-r border-white/5 text-gray-300"><?= esc($pp['nomor_akun'] ?? '-') ?></td>
                            <td class="p-3 border-r border-white/5 text-gray-300"><?= !empty($pp['latar_belakang']) ? '√' : '-' ?></td>
                            <td class="p-3 border-r border-white/5 text-gray-300"><?= !empty($pp['keadaan_keuangan']) ? '√' : '-' ?></td>
                            <td class="p-3 border-r border-white/5 text-gray-300"><?= !empty($pp['profil_risiko']) ? '√' : '-' ?></td>
                            <td class="p-3 border-r border-white/5 text-gray-300"><?= !empty($pp['jasa_penjelasan']) ? '√' : '-' ?></td>
                            <td class="p-3 border-r border-white/5 text-gray-300"><?= !empty($pp['jasa_disetujui']) ? '√' : '-' ?></td>
                            <td class="p-3 border-r border-white/5 text-gray-300"><?= esc($pp['nomor_dokumen'] ?? '-') ?></td>
                            <td class="p-3 border-r border-white/5 text-gray-300"><?= !empty($pp['ket_penjelasan']) ? '√' : '-' ?></td>
                            <td class="p-3 border-r border-white/5 text-gray-300"><?= !empty($pp['ket_disetujui']) ? '√' : '-' ?></td>
                            <td class="p-3 border-r border-white/5 text-gray-300"><?= !empty($pp['risiko_penjelasan']) ? '√' : '-' ?></td>
                            <td class="p-3 text-gray-300"><?= !empty($pp['risiko_disetujui']) ? '√' : '-' ?></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
        </div>
    </div>

</div>
<?= $this->endSection() ?>
