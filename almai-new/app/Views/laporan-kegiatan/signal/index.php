<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>
<div class="w-full pb-12">
    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row justify-between md:items-center gap-4 px-6 md:px-0">
        <h1 class="text-xl md:text-2xl font-black text-white uppercase tracking-tight">Signal</h1>
    </div>

    <!-- Alert Success/Error -->
    <?php if (session()->getFlashdata('success')) : ?>
        <div class="mb-6 bg-accent/10 border border-accent/20 text-accent px-4 py-3 rounded-xl flex items-center gap-3">
            <i class="fas fa-check-circle"></i>
            <span class="text-sm font-bold"><?= session()->getFlashdata('success') ?></span>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-500 px-4 py-3 rounded-xl flex items-center gap-3">
            <i class="fas fa-exclamation-circle"></i>
            <span class="text-sm font-bold"><?= session()->getFlashdata('error') ?></span>
        </div>
    <?php endif; ?>

    <!-- Filter & Search -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-4 shadow-xl mb-6">
        <form method="GET" action="<?= base_url('laporan-kegiatan/signal') ?>" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="<?= esc($search ?? '') ?>" placeholder="Cari nama layanan atau lokasi..." class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-2 text-sm text-white focus:outline-none focus:border-accent">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-white/10 hover:bg-white/20 text-white px-6 py-2 rounded-xl text-xs font-bold uppercase tracking-widest transition">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                <?php if(!empty($search)): ?>
                    <a href="<?= base_url('laporan-kegiatan/signal') ?>" class="bg-red-500/20 hover:bg-red-500/30 text-red-500 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-widest transition flex items-center justify-center">
                        <i class="fas fa-times"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Table Data -->
    <div class="bg-[#111] border border-white/10 overflow-hidden text-sm rounded-2xl shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[1000px]">
                <thead>
                    <tr class="bg-black/50 text-accent text-center font-bold text-[10px] uppercase tracking-wider">
                        <th class="p-3 border-b border-r border-white/10 w-12">No</th>
                        <th class="p-3 border-b border-r border-white/10">Jml Klien/Peserta</th>
                        <th class="p-3 border-b border-r border-white/10">Jml Nasihat</th>
                        <th class="p-3 border-b border-r border-white/10">Produk</th>
                        <th class="p-3 border-b border-r border-white/10">Nama WPA</th>
                        <th class="p-3 border-b border-r border-white/10">Media</th>
                        <th class="p-3 border-b border-r border-white/10">Tanggal</th>
                        <th class="p-3 border-b border-r border-white/10">Keterangan</th>
                        <th class="p-3 border-b border-white/10 w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php if(empty($signals)): ?>
                        <tr>
                            <td colspan="9" class="p-6 text-center text-gray-500 italic">Belum ada layanan untuk modul ini dari Superadmin.</td>
                        </tr>
                    <?php else: ?>
                        <?php 
                        $page = isset($_GET['page']) ? $_GET['page'] : 1;
                        $no = 1 + (20 * ($page - 1));
                        foreach($signals as $s): 
                        ?>
                        <tr class="hover:bg-white/[0.02] text-center transition-colors">
                            <td class="p-3 border-r border-white/5 text-gray-400"><?= $no++ ?></td>
                            <td class="p-3 border-r border-white/5 text-gray-400 text-xs"><?= esc($s['students']) ?></td>
                            <td class="p-3 border-r border-white/5 text-gray-400 text-xs">-</td>
                            <td class="p-3 border-r border-white/5 text-gray-400 text-xs"><?= esc($s['category']) ?></td>
                            <td class="p-3 border-r border-white/5 text-gray-400 text-xs"><?= esc($s['wpa_name']) ?: '-' ?></td>
                            <td class="p-3 border-r border-white/5 text-gray-400 text-xs text-left"><?= esc($s['location']) ?: '-' ?></td>
                            <td class="p-3 border-r border-white/5 text-gray-400 text-xs whitespace-nowrap">
                                <?php if (!empty($s['is_recurring'])): ?>
                                    Setiap <?= esc($s['recurring_day']) ?><br>
                                    <span class="text-[10px] opacity-75"><?= esc($s['recurring_time']) ?></span>
                                <?php else: ?>
                                    <?= (!empty($s['event_date']) && $s['event_date'] != '0000-00-00 00:00:00') ? date('d M Y, H:i', strtotime($s['event_date'])) : '-' ?>
                                <?php endif; ?>
                            </td>
                            <td class="p-3 border-r border-white/5 text-gray-400 text-xs text-left">
                                <?php
                                    $desc = strip_tags($s['description'] ?? '');
                                    echo esc(strlen($desc) > 50 ? substr($desc, 0, 50) . '...' : ($desc ?: '-'));
                                ?>
                            </td>
                            <td class="p-3 flex items-center justify-center gap-2">
                                <a href="<?= base_url('superadmin/layanan/edit/'.($s['table_name'] === 'layanan_artikel' ? 'artikel' : strtolower($s['subcategory'] ?? 'event')).'/'.$s['id']) ?>" class="text-blue-400 hover:text-blue-300 transition-colors" title="Lihat di Superadmin">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <!-- Pagination -->
        <?php if ($pager) : ?>
            <div class="p-4 border-t border-white/5 flex justify-center pagination-dark">
                <?= $pager->links('default', 'default_full') ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Styling for dark mode pagination */
.pagination-dark .pagination { display: flex; gap: 0.5rem; list-style: none; margin: 0; padding: 0; }
.pagination-dark .page-item .page-link { background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff; padding: 0.5rem 0.75rem; border-radius: 0.5rem; font-size: 0.75rem; text-decoration: none; transition: all 0.2s; }
.pagination-dark .page-item .page-link:hover { background: rgba(255,255,255,0.1); }
.pagination-dark .page-item.active .page-link { background: var(--accent-color, #d4af37); border-color: var(--accent-color, #d4af37); color: #000; font-weight: bold; }
</style>
<?= $this->endSection() ?>
