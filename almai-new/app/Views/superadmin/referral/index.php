<?php
$this->setVar('pageTitle', 'Dashboard Referral');
$this->setVar('pageSubtitle', 'Histori distribusi komisi referral');
?>
<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4 mb-6">
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-accent/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-receipt text-accent"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= number_format($stats['total_trx']) ?></p>
                <p class="text-xs text-gray-400 uppercase tracking-wider">Transaksi via Referral</p>
            </div>
        </div>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-users text-blue-400"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= number_format($stats['total_referrers']) ?></p>
                <p class="text-xs text-gray-400 uppercase tracking-wider">Referrer Aktif</p>
            </div>
        </div>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-yellow-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-coins text-yellow-400"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= number_format($stats['total_poin']) ?></p>
                <p class="text-xs text-gray-400 uppercase tracking-wider">Total Poin Terdistribusi</p>
            </div>
        </div>
    </div>
</div>

<div class="grid lg:grid-cols-3 gap-6">

    <!-- Left: History Table -->
    <div class="lg:col-span-2 space-y-4">

        <!-- Filters -->
        <form method="GET" class="bg-[#111] border border-white/10 rounded-2xl p-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                <input type="text" name="search" value="<?= esc($search) ?>"
                    placeholder="Cari nama / invoice..."
                    class="col-span-2 bg-black border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:border-accent focus:outline-none">
                <input type="date" name="date_from" value="<?= esc($dateFrom) ?>"
                    class="bg-black border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:border-accent focus:outline-none">
                <input type="date" name="date_to" value="<?= esc($dateTo) ?>"
                    class="bg-black border border-white/10 rounded-xl px-4 py-2.5 text-sm focus:border-accent focus:outline-none">
            </div>
            <div class="flex items-center gap-3 mt-3">
                <button type="submit" class="px-5 py-2.5 bg-accent text-black rounded-xl text-sm font-bold hover:bg-white transition">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>
                <a href="<?= base_url('superadmin/referral') ?>" class="px-5 py-2.5 bg-white/5 text-gray-400 rounded-xl text-sm hover:bg-white/10 transition">
                    Reset
                </a>
                <span class="text-xs text-gray-500 ml-auto"><?= number_format($totalRows) ?> hasil</span>
            </div>
        </form>

        <!-- Table -->
        <div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[640px]">
                    <thead class="bg-black/80">
                        <tr>
                            <th class="text-left px-4 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Transaksi</th>
                            <th class="text-left px-4 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Buyer</th>
                            <th class="text-left px-4 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Referrer (L1)</th>
                            <th class="text-left px-4 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Distribusi</th>
                            <th class="text-left px-4 py-3 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <?php if (empty($history)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-gray-500">
                                    <i class="fas fa-share-alt text-3xl mb-3 block opacity-20"></i>
                                    Belum ada histori referral
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($history as $row): ?>
                            <tr class="hover:bg-white/5 transition cursor-pointer" onclick="showDetail(<?= htmlspecialchars(json_encode($row), ENT_QUOTES) ?>)">
                                <td class="px-4 py-3">
                                    <p class="text-xs font-bold text-white"><?= esc($row['invoice_number']) ?></p>
                                    <p class="text-[10px] text-gray-500 truncate max-w-[160px]"><?= esc($row['product_name']) ?></p>
                                    <p class="text-[10px] font-bold text-accent mt-0.5">Rp <?= number_format($row['total'], 0, ',', '.') ?></p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-xs font-bold text-white"><?= esc($row['buyer_name']) ?></p>
                                    <p class="text-[10px] text-gray-500"><?= esc($row['buyer_email']) ?></p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-xs font-bold text-white"><?= esc($row['referrer_name']) ?></p>
                                    <p class="text-[10px] text-gray-500"><?= esc($row['referrer_email']) ?></p>
                                    <span class="text-[9px] px-1.5 py-0.5 bg-accent/10 text-accent rounded font-mono"><?= esc($row['code_referral']) ?></span>
                                </td>
                                <td class="px-4 py-3">
                                    <?php if (!empty($row['chain'])): ?>
                                        <div class="flex flex-col gap-0.5">
                                            <?php foreach ($row['chain'] as $idx => $c): ?>
                                            <div class="flex items-center gap-1.5">
                                                <span class="text-[9px] text-gray-600 w-4">L<?= $idx + 1 ?></span>
                                                <span class="text-[9px] text-gray-300 truncate max-w-[80px]"><?= esc($c['user_name']) ?></span>
                                                <span class="text-[9px] font-bold text-yellow-400 ml-auto">+<?= number_format($c['point']) ?></span>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-[10px] text-gray-600">Belum diproses</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="text-[10px] text-gray-400"><?= date('d M Y', strtotime($row['confirmed_at'])) ?></p>
                                    <p class="text-[9px] text-gray-600"><?= date('H:i', strtotime($row['confirmed_at'])) ?></p>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <div class="px-4 py-3 border-t border-white/5 flex items-center justify-between">
                <span class="text-xs text-gray-500">Halaman <?= $page ?> dari <?= $totalPages ?></span>
                <div class="flex gap-2">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&date_from=<?= $dateFrom ?>&date_to=<?= $dateTo ?>"
                           class="px-3 py-1.5 bg-white/5 text-gray-400 rounded-lg text-xs hover:bg-white/10 transition">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    <?php endif; ?>
                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&date_from=<?= $dateFrom ?>&date_to=<?= $dateTo ?>"
                           class="px-3 py-1.5 bg-white/5 text-gray-400 rounded-lg text-xs hover:bg-white/10 transition">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right: Top Referrers -->
    <div class="space-y-4">
        <div class="bg-[#111] border border-white/10 rounded-2xl p-5">
            <h3 class="font-bold text-sm mb-4 flex items-center gap-2">
                <i class="fas fa-trophy text-yellow-400"></i> Top Referrers
            </h3>
            <?php if (empty($topReferrers)): ?>
                <p class="text-xs text-gray-500 text-center py-6">Belum ada data</p>
            <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($topReferrers as $idx => $ref): ?>
                <div class="flex items-center gap-3 p-3 bg-black/30 rounded-xl border border-white/5">
                    <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-black flex-shrink-0
                        <?= $idx === 0 ? 'bg-yellow-500/20 text-yellow-400' : ($idx === 1 ? 'bg-gray-400/20 text-gray-300' : ($idx === 2 ? 'bg-orange-500/20 text-orange-400' : 'bg-white/5 text-gray-500')) ?>">
                        <?= $idx + 1 ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-white truncate"><?= esc($ref['name']) ?></p>
                        <p class="text-[9px] text-gray-500 font-mono"><?= esc($ref['code_referral']) ?></p>
                    </div>
                    <div class="text-right flex-shrink-0">
                        <p class="text-xs font-black text-accent"><?= number_format($ref['total_referral']) ?>x</p>
                        <p class="text-[9px] text-yellow-400">+<?= number_format($ref['total_poin']) ?> poin</p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
    <div class="bg-[#111] border border-white/10 rounded-3xl p-6 max-w-lg w-full max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-5">
            <h3 class="font-black text-lg">Detail Distribusi Referral</h3>
            <button onclick="closeDetail()" class="w-8 h-8 flex items-center justify-center bg-white/5 rounded-full hover:bg-white/10 transition">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
        <div id="detailContent"></div>
    </div>
</div>

<script>
function showDetail(row) {
    const modal = document.getElementById('detailModal');
    const content = document.getElementById('detailContent');

    const chain = row.chain || [];
    let chainHtml = '';
    if (chain.length > 0) {
        chainHtml = `<div class="space-y-2 mt-3">`;
        chain.forEach((c, i) => {
            chainHtml += `
                <div class="flex items-center gap-3 p-2.5 bg-black/40 rounded-xl border border-white/5">
                    <span class="w-8 h-8 rounded-full bg-accent/10 text-accent text-xs font-black flex items-center justify-center flex-shrink-0">L${i+1}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate">${c.user_name}</p>
                        <p class="text-[10px] text-gray-500 truncate">${c.description}</p>
                    </div>
                    <span class="text-sm font-black text-yellow-400 flex-shrink-0">+${Number(c.point).toLocaleString('id-ID')} poin</span>
                </div>`;
        });
        chainHtml += `</div>`;
    } else {
        chainHtml = `<p class="text-sm text-gray-500 mt-3">Distribusi belum diproses.</p>`;
    }

    const totalPoin = chain.reduce((s, c) => s + parseInt(c.point), 0);

    content.innerHTML = `
        <div class="space-y-4">
            <div class="p-4 bg-black/40 rounded-2xl border border-white/5">
                <p class="text-xs text-gray-500 mb-1">Invoice</p>
                <p class="font-bold text-white">${row.invoice_number}</p>
                <p class="text-sm text-gray-400 mt-1">${row.product_name}</p>
                <p class="text-lg font-black text-accent mt-1">Rp ${Number(row.total).toLocaleString('id-ID')}</p>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="p-3 bg-black/40 rounded-xl border border-white/5">
                    <p class="text-[10px] text-gray-500 mb-1">Buyer</p>
                    <p class="text-sm font-bold text-white">${row.buyer_name}</p>
                    <p class="text-[10px] text-gray-500">${row.buyer_email}</p>
                </div>
                <div class="p-3 bg-black/40 rounded-xl border border-white/5">
                    <p class="text-[10px] text-gray-500 mb-1">Referrer (L1)</p>
                    <p class="text-sm font-bold text-white">${row.referrer_name}</p>
                    <p class="text-[10px] text-gray-500">${row.referrer_email}</p>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-2">
                    <p class="text-xs font-bold text-gray-300 uppercase tracking-widest">Chain Distribusi</p>
                    <span class="text-xs text-yellow-400 font-bold">Total: ${totalPoin.toLocaleString('id-ID')} poin</span>
                </div>
                ${chainHtml}
            </div>
        </div>`;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDetail() {
    const modal = document.getElementById('detailModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.getElementById('detailModal').addEventListener('click', function(e) {
    if (e.target === this) closeDetail();
});
</script>

<?= $this->endSection() ?>
