<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>
<div class="w-full pb-12">
    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row justify-between md:items-center gap-4 px-6 md:px-0">
        <h1 class="text-xl md:text-2xl font-black text-white uppercase tracking-tight">Pedoman Perilaku</h1>
    </div>

    <!-- Filter Form -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl mb-6">
        <form method="GET" action="<?= base_url('laporan-kegiatan/pedoman-perilaku') ?>" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:w-1/3">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Pilih WPA</label>
                <select name="wpa_id" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:border-accent">
                    <option value="">Semua WPA</option>
                    <?php foreach($wpaList as $wpa): ?>
                        <option value="<?= $wpa['id'] ?>" <?= $selectedWpa == $wpa['id'] ? 'selected' : '' ?>><?= esc($wpa['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="w-full md:w-1/3">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Cari Klien / No Transaksi</label>
                <input type="text" name="keyword" value="<?= esc($keyword) ?>" placeholder="Nama Klien..." class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:border-accent">
            </div>
            <div class="w-full md:w-1/3">
                <button type="submit" class="w-full bg-accent hover:bg-white text-black px-6 py-2 rounded-xl text-sm font-bold uppercase tracking-widest transition">
                    Tampilkan
                </button>
            </div>
        </form>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="mb-6 bg-green-500/10 border border-green-500/50 text-green-500 px-4 py-3 rounded-xl text-sm">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <!-- Table -->
    <div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[2000px]">
                <thead>
                    <tr class="bg-black/50 text-gray-400 text-[10px] uppercase tracking-wider text-center">
                        <th class="p-4 border-b border-r border-white/10 w-12">No</th>
                        <th class="p-4 border-b border-r border-white/10">Aksi</th>
                        <th class="p-4 border-b border-r border-white/10">Nama Klien</th>
                        <th class="p-4 border-b border-r border-white/10">Nama WPA</th>
                        <th class="p-4 border-b border-r border-white/10">Nomor Perjanjian</th>
                        <th class="p-4 border-b border-r border-white/10">Profil Klien</th>
                        <th class="p-4 border-b border-r border-white/10">Dokumen Penyampaian Profil Perusahaan</th>
                        <th class="p-4 border-b border-r border-white/10">Dokumen Penyampaian Adanya Resiko (DPAR)</th>
                        <th class="p-4 border-b border-r border-white/10">Dokumen Perjanjian Pemberian Jasa (DPPJ)</th>
                        <th class="p-4 border-b border-white/10">Quesioner (Testimoni / Aduan)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-sm">
                    <?php if (empty($pedomanData)): ?>
                        <tr>
                            <td colspan="10" class="p-8 text-center text-gray-500 italic">Belum ada data transaksi yang dikonfirmasi.</td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1 + (($current_page - 1) * 20); foreach($pedomanData as $row): ?>
                        <tr class="hover:bg-white/[0.02] text-center">
                            <td class="p-4 border-r border-white/5 text-gray-400"><?= $no++ ?></td>
                            <td class="p-4 border-r border-white/5">
                                <a href="<?= base_url('laporan-kegiatan/pedoman-perilaku/edit/'.$row['transaksi_id']) ?>" class="bg-blue-600 hover:bg-blue-500 text-white px-3 py-1 rounded text-xs font-bold uppercase transition">
                                    Edit
                                </a>
                            </td>
                            <td class="p-4 border-r border-white/5 text-white font-bold"><?= esc($row['nama_klien']) ?></td>
                            <td class="p-4 border-r border-white/5 text-accent font-bold"><?= esc($row['wpa_name']) ?: '-' ?></td>
                            <td class="p-4 border-r border-white/5 text-gray-300"><?= esc($row['no_dok_perjanjian']) ?: '-' ?></td>
                            
                            <td class="p-4 border-r border-white/5 text-gray-300">
                                <div class="flex flex-col gap-1 items-center">
                                    <span class="text-xs mb-1"><?= esc($row['kuesioner_profil_risiko']) ?: '-' ?></span>
                                    <a href="<?= base_url('laporan-kegiatan/pedoman-perilaku/download-profil-risiko/'.$row['invoice_number']) ?>" target="_blank" class="text-[10px] bg-white/10 hover:bg-white/20 px-2 py-1 rounded text-white flex items-center gap-1 transition"><i class="fas fa-download"></i> PDF</a>
                                </div>
                            </td>
                            
                            <td class="p-4 border-r border-white/5 text-gray-300">
                                <div class="flex flex-col gap-1 items-center">
                                    <span class="text-xs mb-1"><?= esc($row['ket_perusahaan_ttd']) ?: '-' ?></span>
                                    <a href="<?= base_url('laporan-kegiatan/pedoman-perilaku/download-profil-risiko/'.$row['invoice_number']) ?>" target="_blank" class="text-[10px] bg-white/10 hover:bg-white/20 px-2 py-1 rounded text-white flex items-center gap-1 transition"><i class="fas fa-download"></i> PDF</a>
                                </div>
                            </td>
                            
                            <td class="p-4 border-r border-white/5 text-gray-300">
                                <div class="flex flex-col gap-1 items-center">
                                    <span class="text-xs mb-1"><?= esc($row['pernyataan_risiko_ttd']) ?: '-' ?></span>
                                    <a href="<?= base_url('laporan-kegiatan/pedoman-perilaku/download-pernyataan-risiko/'.$row['invoice_number']) ?>" target="_blank" class="text-[10px] bg-white/10 hover:bg-white/20 px-2 py-1 rounded text-white flex items-center gap-1 transition"><i class="fas fa-download"></i> PDF</a>
                                </div>
                            </td>
                            
                            <td class="p-4 border-r border-white/5 text-gray-300">
                                <div class="flex flex-col gap-1 items-center">
                                    <span class="text-xs mb-1"><?= esc($row['perjanjian_jasa_ttd']) ?: '-' ?></span>
                                    <a href="<?= base_url('laporan-kegiatan/pedoman-perilaku/download-perjanjian/'.$row['invoice_number']) ?>" target="_blank" class="text-[10px] bg-white/10 hover:bg-white/20 px-2 py-1 rounded text-white flex items-center gap-1 transition"><i class="fas fa-download"></i> PDF</a>
                                </div>
                            </td>
                            
                            <td class="p-4 text-gray-400 text-xs text-left">
                                <div class="flex flex-col gap-1 items-center">
                                    <span class="text-xs"><?= esc($row['kuesioner_latar_belakang']) ?: '-' ?></span>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if (!empty($pedomanData)): ?>
        <div class="p-4 border-t border-white/5 flex justify-center">
            <?= $pager_links ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Custom Pagination Dark Mode */
.pagination { display: flex; gap: 0.5rem; }
.pagination li a, .pagination li span {
    display: flex; align-items: center; justify-content: center;
    width: 2.5rem; height: 2.5rem;
    border-radius: 0.5rem;
    background: rgba(255,255,255,0.05);
    color: #9ca3af;
    font-size: 0.875rem; font-weight: bold;
    transition: all 0.2s;
}
.pagination li a:hover { background: rgba(255,255,255,0.1); color: #fff; }
.pagination li.active span { background: #33e818; color: #000; }
</style>
<?= $this->endSection() ?>
