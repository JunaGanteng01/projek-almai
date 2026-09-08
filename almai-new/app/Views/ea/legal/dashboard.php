<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>
<?php 
$pageTitle = 'Laporan Kegiatan Dashboard';
$activeMenu = 'dashboard'; 
?>

<!-- Stats Cards -->
<div class="grid grid-cols-2 md:grid-cols-5 gap-3 md:gap-4 mb-6">
    <!-- Nama Perusahaan -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-4 flex flex-col justify-center">
        <p class="text-gray-500 text-[10px] uppercase tracking-widest mb-1">Nama Perusahaan</p>
        <h3 class="text-sm md:text-base font-bold text-accent truncate" title="<?= esc($namaPerusahaan) ?>"><?= esc($namaPerusahaan) ?></h3>
    </div>
    <!-- Nomor Izin Bappebti -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-4 flex flex-col justify-center">
        <p class="text-gray-500 text-[10px] uppercase tracking-widest mb-1">Izin Bappebti</p>
        <h3 class="text-sm md:text-base font-bold truncate" title="<?= esc($nomorIzinBappebti) ?>"><?= esc($nomorIzinBappebti) ?></h3>
    </div>
    <!-- Klien Aktif -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-4 flex flex-col justify-center">
        <p class="text-gray-500 text-[10px] uppercase tracking-widest mb-1">Klien Aktif</p>
        <h3 class="text-xl md:text-2xl font-bold text-blue-500"><?= number_format($klienAktif) ?></h3>
    </div>
    <!-- Klien Baru -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-4 flex flex-col justify-center">
        <p class="text-gray-500 text-[10px] uppercase tracking-widest mb-1">Klien Baru (Bulan Ini)</p>
        <h3 class="text-xl md:text-2xl font-bold text-green-500"><?= number_format($klienBaru) ?></h3>
    </div>
    <!-- WPA Aktif -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-4 flex flex-col justify-center">
        <p class="text-gray-500 text-[10px] uppercase tracking-widest mb-1">WPA Aktif</p>
        <h3 class="text-xl md:text-2xl font-bold text-purple-500"><?= number_format($wpaAktif) ?></h3>
    </div>
    <!-- Total Kegiatan -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-4 flex flex-col justify-center">
        <p class="text-gray-500 text-[10px] uppercase tracking-widest mb-1">Total Kegiatan</p>
        <h3 class="text-xl md:text-2xl font-bold text-yellow-500"><?= number_format($totalKegiatan) ?></h3>
    </div>
    <!-- Total Signal -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-4 flex flex-col justify-center">
        <p class="text-gray-500 text-[10px] uppercase tracking-widest mb-1">Total Signal</p>
        <h3 class="text-xl md:text-2xl font-bold"><?= number_format($totalSignal) ?></h3>
    </div>
    <!-- Total Konsultasi -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-4 flex flex-col justify-center">
        <p class="text-gray-500 text-[10px] uppercase tracking-widest mb-1">Total Konsultasi</p>
        <h3 class="text-xl md:text-2xl font-bold"><?= number_format($totalKonsultasi) ?></h3>
    </div>
    <!-- Total EA -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-4 flex flex-col justify-center">
        <p class="text-gray-500 text-[10px] uppercase tracking-widest mb-1">Total EA</p>
        <h3 class="text-xl md:text-2xl font-bold"><?= number_format($totalEA) ?></h3>
    </div>
    <!-- Total Seminar -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-4 flex flex-col justify-center">
        <p class="text-gray-500 text-[10px] uppercase tracking-widest mb-1">Total Seminar</p>
        <h3 class="text-xl md:text-2xl font-bold"><?= number_format($totalSeminar) ?></h3>
    </div>
</div>

<!-- Tables Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6">
    
    <!-- Rekap Kegiatan Bulanan Terbaru -->
    <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 overflow-hidden flex flex-col">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-sm md:text-base flex items-center gap-2 text-white">
                <i class="fas fa-list-alt text-accent"></i> Rekap Kegiatan Bulanan Terbaru
            </h3>
        </div>
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-gray-500 text-[10px] md:text-xs uppercase tracking-wider border-b border-white/5">
                        <th class="pb-3 px-2">No</th>
                        <th class="pb-3 px-2">Jenis Kegiatan</th>
                        <th class="pb-3 px-2 text-center">Jml Kegiatan</th>
                        <th class="pb-3 px-2 text-center">Total Klien/Peserta</th>
                        <th class="pb-3 px-2 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php foreach ($rekapKegiatan as $item): ?>
                    <tr class="hover:bg-white/[0.02] transition-colors">
                        <td class="py-3 px-2 text-xs md:text-sm"><?= $item['no'] ?></td>
                        <td class="py-3 px-2 text-xs md:text-sm font-medium text-white"><?= esc($item['jenis_kegiatan']) ?></td>
                        <td class="py-3 px-2 text-xs md:text-sm text-center"><?= $item['jml_kegiatan'] ?></td>
                        <td class="py-3 px-2 text-xs md:text-sm text-center text-blue-400"><?= esc($item['total_peserta']) ?></td>
                        <td class="py-3 px-2 text-right">
                            <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase <?= $item['status'] === 'Selesai' ? 'bg-accent/10 text-accent' : 'bg-yellow-500/10 text-yellow-500' ?>">
                                <?= esc($item['status']) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- KPI DIRESKI -->
    <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6 overflow-hidden flex flex-col">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-sm md:text-base flex items-center gap-2 text-white">
                <i class="fas fa-chart-line text-blue-500"></i> KPI DIRESKI
            </h3>
        </div>
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-gray-500 text-[10px] md:text-xs uppercase tracking-wider border-b border-white/5">
                        <th class="pb-3 px-2">No</th>
                        <th class="pb-3 px-2">KPI</th>
                        <th class="pb-3 px-2">Target</th>
                        <th class="pb-3 px-2">Realisasi</th>
                        <th class="pb-3 px-2 text-center">Pencapaian %</th>
                        <th class="pb-3 px-2 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php foreach ($kpiDireski as $item): ?>
                    <tr class="hover:bg-white/[0.02] transition-colors">
                        <td class="py-3 px-2 text-xs md:text-sm"><?= $item['no'] ?></td>
                        <td class="py-3 px-2 text-xs md:text-sm font-medium text-white"><?= esc($item['kpi']) ?></td>
                        <td class="py-3 px-2 text-xs md:text-sm text-gray-400"><?= esc($item['target']) ?></td>
                        <td class="py-3 px-2 text-xs md:text-sm font-bold"><?= esc($item['realisasi']) ?></td>
                        <td class="py-3 px-2 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <div class="w-16 h-1.5 bg-gray-800 rounded-full overflow-hidden">
                                    <div class="h-full <?= $item['pencapaian'] >= 100 ? 'bg-accent' : ($item['pencapaian'] >= 70 ? 'bg-blue-500' : 'bg-red-500') ?>" style="width: <?= min(100, $item['pencapaian']) ?>%"></div>
                                </div>
                                <span class="text-xs font-bold"><?= $item['pencapaian'] ?>%</span>
                            </div>
                        </td>
                        <td class="py-3 px-2 text-right">
                            <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase 
                                <?= $item['status'] === 'Achieved' ? 'bg-accent/10 text-accent' : 
                                   ($item['status'] === 'On Track' ? 'bg-blue-500/10 text-blue-500' : 'bg-red-500/10 text-red-500') ?>">
                                <?= esc($item['status']) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
<?= $this->endSection() ?>
