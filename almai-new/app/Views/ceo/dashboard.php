<?= $this->extend('ea/layouts/main') ?>

<?= $this->section('content') ?>
<?php
$pageTitle = 'CEO Executive Dashboard';
$pageSubtitle = 'Satu sumber data untuk memantau, memutuskan, dan menindaklanjuti.';
$activeMenu = 'dashboard';
$s = $dashboard['summary'];
$range = $dashboard['range'];
$rupiah = static fn($value) => $value === null ? 'Belum tersedia' : 'Rp ' . number_format((float) $value, 0, ',', '.');
$growth = static function ($value): string {
    if ($value === null) return 'Belum ada pembanding';
    if ((float) $value === 0.0) return 'Tidak berubah';
    return ($value > 0 ? '+' : '') . number_format((float) $value, 1, ',', '.') . '% vs periode lalu';
};
$maxTrend = max(array_map(static fn($item) => (float) $item['revenue'], $dashboard['trend']) ?: [1]);
?>

<style>
    .ceo-shell { --ceo-green:#33e818; --ceo-card:#0d0f0d; --ceo-muted:#94a3b8; }
    .ceo-card { background:linear-gradient(145deg,rgba(18,22,18,.97),rgba(7,8,7,.98)); border:1px solid rgba(255,255,255,.09); border-radius:1.15rem; box-shadow:0 18px 45px rgba(0,0,0,.2); }
    .ceo-card:hover { border-color:rgba(51,232,24,.22); }
    .ceo-kpi { min-height:154px; position:relative; overflow:hidden; }
    .ceo-kpi::after { content:""; position:absolute; width:110px; height:110px; right:-48px; top:-48px; border-radius:999px; background:rgba(51,232,24,.05); }
    .ceo-label { color:#94a3b8; font-size:.72rem; text-transform:uppercase; letter-spacing:.12em; font-weight:700; }
    .ceo-value { font-size:clamp(1.35rem,2.6vw,2rem); line-height:1.05; font-weight:800; letter-spacing:-.04em; }
    .ceo-chip { display:inline-flex; align-items:center; gap:.35rem; padding:.32rem .6rem; border-radius:999px; font-size:.7rem; border:1px solid rgba(255,255,255,.1); background:rgba(255,255,255,.04); }
    .ceo-action { display:inline-flex; align-items:center; justify-content:center; gap:.45rem; min-height:42px; padding:.65rem .9rem; border-radius:.75rem; font-size:.78rem; font-weight:750; border:1px solid rgba(255,255,255,.1); transition:.2s ease; }
    .ceo-action:hover,.ceo-action:focus-visible { transform:translateY(-1px); border-color:rgba(51,232,24,.55); outline:none; }
    .ceo-action-primary { color:#050505; background:#33e818; border-color:#33e818; }
    .ceo-section-title { font-weight:800; letter-spacing:-.025em; }
    .trend-column { min-width:34px; height:150px; display:flex; flex-direction:column; justify-content:flex-end; gap:.45rem; }
    .trend-bar { min-height:3px; border-radius:.45rem .45rem .15rem .15rem; background:linear-gradient(180deg,#33e818,#178d08); box-shadow:0 0 18px rgba(51,232,24,.16); }
    .status-dot { width:.55rem; height:.55rem; border-radius:50%; display:inline-block; }
    .command-panel { backdrop-filter:blur(24px); }
    @media(max-width:640px){ .ceo-kpi{min-height:132px}.trend-column{height:110px}.desktop-copy{display:none} }
</style>

<div class="ceo-shell space-y-6" id="dashboardTop">
    <section class="ceo-card p-5 md:p-7 relative overflow-hidden">
        <div class="absolute inset-y-0 right-0 w-1/2 bg-[radial-gradient(circle_at_center,rgba(51,232,24,.09),transparent_62%)] pointer-events-none"></div>
        <div class="relative flex flex-col xl:flex-row xl:items-end justify-between gap-5">
            <div>
                <div class="flex flex-wrap gap-2 mb-3">
                    <span class="ceo-chip text-[#33e818]"><span class="status-dot bg-[#33e818]"></span> Data aktual</span>
                    <span class="ceo-chip"><?= esc($range['label']) ?> · <?= date('d M', strtotime($range['start'])) ?>–<?= date('d M Y', strtotime($range['end'])) ?></span>
                </div>
                <p class="ceo-label mb-2">Executive command center</p>
                <h2 class="text-2xl md:text-4xl font-extrabold tracking-tight max-w-3xl">Keputusan penting terlihat jelas, tanpa tenggelam dalam data operasional.</h2>
                <p class="text-gray-400 text-sm mt-3 max-w-2xl">Diperbarui <?= date('d M Y, H:i', strtotime($dashboard['generated_at'])) ?>. Semua angka berasal dari database ALMAI.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <button type="button" class="ceo-action" onclick="openCommandPalette()"><i class="fas fa-search"></i> Cari <kbd class="text-[10px] text-gray-500">Ctrl K</kbd></button>
                <a class="ceo-action" href="<?= base_url('ceo/calendar') ?>"><i class="far fa-calendar"></i> Kalender</a>
                <a class="ceo-action ceo-action-primary" href="<?= base_url('ceo/reports/export-xlsx?' . http_build_query(['period'=>$range['period'],'start'=>$range['start'],'end'=>$range['end']])) ?>"><i class="fas fa-file-excel"></i> Ekspor Excel</a>
            </div>
        </div>

        <form method="get" action="<?= base_url('ceo/dashboard') ?>" class="relative mt-6 pt-5 border-t border-white/10 flex flex-col lg:flex-row lg:items-end gap-3" id="periodFilter">
            <div class="flex-1">
                <label for="period" class="ceo-label block mb-2">Periode analisis</label>
                <select id="period" name="period" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-[#33e818] focus:outline-none" onchange="toggleCustomRange(this.value)">
                    <?php foreach (['today'=>'Hari ini','7d'=>'7 hari','30d'=>'30 hari','mtd'=>'Bulan berjalan','quarter'=>'Kuartal','year'=>'Tahun','custom'=>'Rentang khusus'] as $value=>$label): ?>
                        <option value="<?= $value ?>" <?= $range['period'] === $value ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div id="customRange" class="contents <?= $range['period'] === 'custom' ? '' : 'hidden' ?>">
                <div class="flex-1"><label class="ceo-label block mb-2" for="start">Mulai</label><input id="start" name="start" type="date" value="<?= esc($range['start']) ?>" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm"></div>
                <div class="flex-1"><label class="ceo-label block mb-2" for="end">Selesai</label><input id="end" name="end" type="date" value="<?= esc($range['end']) ?>" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm"></div>
            </div>
            <button class="ceo-action ceo-action-primary" type="submit"><i class="fas fa-filter"></i> Terapkan</button>
        </form>
    </section>

    <section aria-labelledby="pulseTitle">
        <div class="flex items-end justify-between gap-4 mb-3">
            <div><p class="ceo-label">Ringkasan 10 detik</p><h3 id="pulseTitle" class="ceo-section-title text-xl mt-1">Executive Pulse</h3></div>
            <p class="text-xs text-gray-500 desktop-copy">Arahkan kursor ke kartu untuk melihat definisi.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3">
            <a href="#performance" class="ceo-card ceo-kpi p-5 block" title="<?= esc($dashboard['definitions']['revenue']) ?>">
                <div class="flex justify-between"><span class="ceo-label">Pendapatan</span><i class="fas fa-arrow-trend-up text-[#33e818]"></i></div>
                <p class="ceo-value mt-5"><?= $rupiah($s['revenue']) ?></p>
                <p class="text-xs mt-3 <?= ($s['revenue_growth'] ?? 0) < 0 ? 'text-red-400' : 'text-[#33e818]' ?>"><?= esc($growth($s['revenue_growth'])) ?></p>
            </a>
            <a href="#finance" class="ceo-card ceo-kpi p-5 block" title="<?= esc($dashboard['definitions']['cash_balance']) ?>">
                <div class="flex justify-between"><span class="ceo-label">Kas & bank</span><i class="fas fa-building-columns text-blue-400"></i></div>
                <p class="ceo-value mt-5"><?= $rupiah($s['cash_balance']) ?></p>
                <p class="text-xs text-gray-500 mt-3">Saldo buku jurnal saat ini</p>
            </a>
            <a href="#approvals" class="ceo-card ceo-kpi p-5 block">
                <div class="flex justify-between"><span class="ceo-label">Butuh keputusan</span><i class="fas fa-signature text-amber-400"></i></div>
                <p class="ceo-value mt-5"><?= number_format($s['approvals']['pending']) ?> approval</p>
                <p class="text-xs text-amber-300 mt-3"><?= $rupiah($s['approvals']['amount']) ?> · <?= $s['approvals']['overdue'] ?> overdue</p>
            </a>
            <a href="#risk" class="ceo-card ceo-kpi p-5 block">
                <div class="flex justify-between"><span class="ceo-label">Risiko aktif</span><i class="fas fa-triangle-exclamation text-red-400"></i></div>
                <p class="ceo-value mt-5"><?= count($dashboard['alerts']) ?> alert</p>
                <p class="text-xs text-red-300 mt-3"><?= $s['crm']['sla_breach'] ?> SLA CRM · <?= $s['invoices']['overdue'] ?> invoice overdue</p>
            </a>
            <a href="#customers" class="ceo-card ceo-kpi p-5 block">
                <div class="flex justify-between"><span class="ceo-label">Pengguna baru</span><i class="fas fa-users text-cyan-400"></i></div>
                <p class="ceo-value mt-5"><?= number_format($s['new_users'], 0, ',', '.') ?></p>
                <p class="text-xs text-cyan-300 mt-3"><?= esc($growth($s['user_growth'])) ?></p>
            </a>
            <a href="#customers" class="ceo-card ceo-kpi p-5 block" title="<?= esc($dashboard['definitions']['conversion_rate']) ?>">
                <div class="flex justify-between"><span class="ceo-label">Konversi</span><i class="fas fa-bullseye text-purple-400"></i></div>
                <p class="ceo-value mt-5"><?= $s['conversion_rate'] === null ? 'Belum tersedia' : number_format($s['conversion_rate'], 1, ',', '.') . '%' ?></p>
                <p class="text-xs text-gray-500 mt-3"><?= number_format($s['transactions_confirmed']) ?> transaksi confirmed</p>
            </a>
            <a href="#events" class="ceo-card ceo-kpi p-5 block">
                <div class="flex justify-between"><span class="ceo-label">Event mendatang</span><i class="fas fa-microphone-lines text-fuchsia-400"></i></div>
                <p class="ceo-value mt-5"><?= number_format($s['events']['upcoming']) ?> event</p>
                <p class="text-xs text-fuchsia-300 mt-3"><?= number_format($s['events']['participants']) ?> peserta<?= $s['events']['occupancy'] !== null ? ' · ' . number_format($s['events']['occupancy'], 1, ',', '.') . '% kapasitas' : '' ?></p>
            </a>
            <a href="#finance" class="ceo-card ceo-kpi p-5 block">
                <div class="flex justify-between"><span class="ceo-label">Pencairan</span><i class="fas fa-money-bill-transfer text-orange-400"></i></div>
                <p class="ceo-value mt-5"><?= number_format($s['withdrawals']['pending']) ?> pending</p>
                <p class="text-xs text-orange-300 mt-3"><?= $rupiah($s['withdrawals']['amount']) ?></p>
            </a>
        </div>
    </section>

    <section class="grid grid-cols-1 xl:grid-cols-3 gap-4" id="performance">
        <div class="ceo-card p-5 md:p-6 xl:col-span-2">
            <div class="flex items-start justify-between gap-4 mb-5">
                <div><p class="ceo-label">Performa periode</p><h3 class="ceo-section-title text-lg mt-1">Tren pendapatan confirmed</h3></div>
                <span class="ceo-chip"><?= count($dashboard['trend']) ?> hari dengan transaksi</span>
            </div>
            <?php if (empty($dashboard['trend'])): ?>
                <div class="h-44 flex flex-col items-center justify-center text-gray-500"><i class="fas fa-chart-column text-3xl mb-3"></i><p>Belum ada transaksi confirmed pada periode ini.</p></div>
            <?php else: ?>
                <div class="flex items-end gap-2 overflow-x-auto pb-2" role="img" aria-label="Grafik pendapatan harian">
                    <?php foreach ($dashboard['trend'] as $point): $height = max(3, ((float)$point['revenue'] / max(1, $maxTrend)) * 128); ?>
                        <div class="trend-column flex-1" title="<?= date('d M Y', strtotime($point['date'])) ?>: <?= $rupiah($point['revenue']) ?>">
                            <div class="trend-bar" style="height:<?= (float)$height ?>px"></div>
                            <span class="text-[9px] text-gray-500 text-center"><?= date('d/m', strtotime($point['date'])) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <div class="grid grid-cols-3 gap-3 mt-5 pt-5 border-t border-white/10">
                <div><p class="ceo-label">Confirmed</p><p class="font-bold mt-1"><?= number_format($s['transactions_confirmed']) ?></p></div>
                <div><p class="ceo-label">Semua transaksi</p><p class="font-bold mt-1"><?= number_format($s['transactions_total']) ?></p></div>
                <div><p class="ceo-label">Rata-rata nilai</p><p class="font-bold mt-1"><?= $s['transactions_confirmed'] ? $rupiah($s['revenue']/$s['transactions_confirmed']) : '—' ?></p></div>
            </div>
        </div>

        <div class="ceo-card p-5 md:p-6" id="risk">
            <div class="flex items-start justify-between mb-4"><div><p class="ceo-label">Pengecualian</p><h3 class="ceo-section-title text-lg mt-1">Risk & Alert Center</h3></div><span class="ceo-chip text-red-300"><?= count($dashboard['alerts']) ?></span></div>
            <div class="space-y-3">
                <?php if (empty($dashboard['alerts'])): ?>
                    <div class="py-8 text-center text-gray-500"><i class="fas fa-shield-check text-[#33e818] text-3xl mb-3"></i><p>Tidak ada alert berdasarkan aturan aktif.</p></div>
                <?php else: foreach ($dashboard['alerts'] as $alert): ?>
                    <a href="<?= esc($alert['link']) ?>" class="block p-3 rounded-xl bg-black/35 border border-white/5 hover:border-red-500/40">
                        <div class="flex items-center gap-2"><span class="status-dot <?= $alert['severity']==='critical'?'bg-red-500':'bg-amber-400' ?>"></span><p class="font-bold text-sm"><?= esc($alert['title']) ?></p></div>
                        <p class="text-xs text-gray-400 mt-2 leading-relaxed"><?= esc($alert['reason']) ?></p>
                        <p class="text-[10px] text-gray-600 mt-2">Sumber: <?= esc($alert['source']) ?></p>
                    </a>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </section>

    <section class="ceo-card p-5 md:p-6" id="approvals">
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 mb-5">
            <div><p class="ceo-label">Antrean keputusan</p><h3 class="ceo-section-title text-xl mt-1">Approval Center</h3><p class="text-xs text-gray-500 mt-1">Keputusan tersimpan dengan version check dan audit trail.</p></div>
            <span class="ceo-chip text-amber-300"><?= $s['approvals']['pending'] ?> menunggu · <?= $rupiah($s['approvals']['amount']) ?></span>
        </div>
        <?php if (empty($dashboard['pending_approvals'])): ?>
            <div class="py-10 text-center text-gray-500"><i class="fas fa-circle-check text-[#33e818] text-3xl mb-3"></i><p>Tidak ada approval yang menunggu keputusan.</p></div>
        <?php else: ?>
            <form id="batchApprovalForm" method="post" action="<?= base_url('ceo/approvals/batch') ?>" class="mb-4 p-4 rounded-xl bg-amber-500/5 border border-amber-500/20 grid grid-cols-1 lg:grid-cols-[1fr_auto_auto] gap-2 items-end">
                <?= csrf_field() ?>
                <div><label for="batchReason" class="ceo-label block mb-2">Alasan keputusan massal</label><input id="batchReason" name="reason" minlength="5" maxlength="1000" required class="w-full bg-black/60 border border-white/10 rounded-lg p-3 text-sm" placeholder="Berlaku untuk semua item yang dipilih"></div>
                <button type="submit" name="decision" value="rejected" class="ceo-action text-red-300 border-red-500/30" onclick="return confirmBatchApproval('menolak')"><i class="fas fa-xmark"></i>Tolak pilihan</button>
                <button type="submit" name="decision" value="approved" class="ceo-action ceo-action-primary" onclick="return confirmBatchApproval('menyetujui')"><i class="fas fa-check-double"></i>Setujui pilihan</button>
            </form>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                <?php foreach ($dashboard['pending_approvals'] as $approval): ?>
                    <article class="p-4 rounded-xl bg-black/35 border border-white/10">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start gap-3"><input form="batchApprovalForm" type="checkbox" name="approval_ids[]" value="<?= (int)$approval['id'] ?>" class="mt-1 accent-[#33e818]" aria-label="Pilih <?= esc($approval['title']) ?>"><div><p class="text-[10px] text-gray-500 uppercase tracking-widest"><?= esc($approval['approval_code'] ?? 'APP-' . $approval['id']) ?> · <?= esc($approval['module'] ?? 'Umum') ?></p><h4 class="font-bold mt-1"><?= esc($approval['title']) ?></h4></div></div>
                            <span class="ceo-chip <?= strtolower($approval['priority'] ?? '')==='urgent'?'text-red-300':'text-amber-300' ?>"><?= esc(strtoupper($approval['priority'] ?? 'medium')) ?></span>
                        </div>
                        <p class="text-sm text-gray-400 mt-3 leading-relaxed"><?= esc($approval['description'] ?? 'Tidak ada deskripsi.') ?></p>
                        <div class="flex flex-wrap gap-3 mt-3 text-xs text-gray-500"><span><i class="fas fa-user mr-1"></i><?= esc($approval['requested_by'] ?? 'Tidak diketahui') ?></span><span><i class="far fa-clock mr-1"></i><?= !empty($approval['due_date']) ? date('d M Y H:i', strtotime($approval['due_date'])) : 'Tanpa tenggat' ?></span></div>
                        <p class="font-extrabold text-[#33e818] mt-3"><?= $rupiah($approval['amount'] ?? 0) ?></p>
                        <form method="post" action="<?= base_url('ceo/approvals/' . $approval['id'] . '/decision') ?>" class="mt-4 pt-4 border-t border-white/10">
                            <?= csrf_field() ?>
                            <input type="hidden" name="version" value="<?= (int)($approval['version'] ?? 1) ?>">
                            <label class="ceo-label block mb-2" for="reason-<?= $approval['id'] ?>">Alasan keputusan</label>
                            <textarea id="reason-<?= $approval['id'] ?>" name="reason" minlength="5" maxlength="1000" required rows="2" class="w-full bg-black/60 border border-white/10 rounded-lg p-3 text-sm focus:border-[#33e818] focus:outline-none" placeholder="Tuliskan dasar keputusan..."></textarea>
                            <div class="grid grid-cols-2 gap-2 mt-2">
                                <button class="ceo-action border-red-500/30 text-red-300" type="submit" name="decision" value="rejected" onclick="return confirm('Tolak pengajuan ini?')"><i class="fas fa-xmark"></i> Tolak</button>
                                <button class="ceo-action ceo-action-primary" type="submit" name="decision" value="approved" onclick="return confirm('Setujui pengajuan ini?')"><i class="fas fa-check"></i> Setujui</button>
                            </div>
                        </form>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <section class="grid grid-cols-1 xl:grid-cols-2 gap-4">
        <div class="ceo-card p-5 md:p-6" id="events">
            <div class="flex items-end justify-between mb-4"><div><p class="ceo-label">Agenda publik</p><h3 class="ceo-section-title text-lg mt-1">Event & Webinar Mendatang</h3></div><a href="<?= base_url('ceo/calendar') ?>" class="text-xs text-[#33e818]">Buka kalender →</a></div>
            <div class="space-y-3">
                <?php if (empty($dashboard['upcoming_events'])): ?><p class="py-8 text-center text-gray-500">Belum ada event berjadwal.</p>
                <?php else: foreach ($dashboard['upcoming_events'] as $event): ?>
                    <div class="p-3 rounded-xl bg-black/35 border border-white/5 flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-purple-500/15 text-purple-300 flex flex-col items-center justify-center shrink-0"><b class="text-sm"><?= date('d', strtotime($event['event_date'])) ?></b><span class="text-[9px] uppercase"><?= date('M', strtotime($event['event_date'])) ?></span></div>
                        <div class="min-w-0 flex-1"><p class="font-bold text-sm truncate"><?= esc($event['title']) ?></p><p class="text-xs text-gray-500 mt-1"><?= date('H:i', strtotime($event['event_date'])) ?> · <?= esc($event['location'] ?: ucfirst($event['type'])) ?> · <?= number_format($event['current_participants']) ?>/<?= $event['max_participants'] ? number_format($event['max_participants']) : '∞' ?> peserta</p></div>
                        <?php $eventUrl = $event['zoom_link'] ?? ''; if ($eventUrl && filter_var($eventUrl, FILTER_VALIDATE_URL) && parse_url($eventUrl, PHP_URL_SCHEME)==='https'): ?><a href="<?= esc($eventUrl) ?>" target="_blank" rel="noopener noreferrer" class="ceo-action p-2" aria-label="Buka ruang <?= esc($event['title']) ?>"><i class="fas fa-video"></i></a><?php endif; ?>
                    </div>
                <?php endforeach; endif; ?>
            </div>
        </div>

        <div class="ceo-card p-5 md:p-6" id="crm">
            <div class="flex items-end justify-between mb-4"><div><p class="ceo-label">Customer operations</p><h3 class="ceo-section-title text-lg mt-1">CRM Executive Snapshot</h3></div><span class="ceo-chip"><?= $s['crm']['open'] ?> aktif</span></div>
            <div class="grid grid-cols-3 gap-2 mb-4"><div class="bg-black/35 rounded-xl p-3"><p class="ceo-label">Open</p><p class="text-xl font-bold mt-2"><?= $s['crm']['open'] ?></p></div><div class="bg-black/35 rounded-xl p-3"><p class="ceo-label">Unassigned</p><p class="text-xl font-bold mt-2 text-amber-300"><?= $s['crm']['unassigned'] ?></p></div><div class="bg-black/35 rounded-xl p-3"><p class="ceo-label">SLA &gt;24j</p><p class="text-xl font-bold mt-2 text-red-300"><?= $s['crm']['sla_breach'] ?></p></div></div>
            <div class="space-y-2">
                <?php if (empty($dashboard['critical_conversations'])): ?><p class="py-6 text-center text-gray-500">Tidak ada percakapan aktif.</p>
                <?php else: foreach ($dashboard['critical_conversations'] as $conversation): ?>
                    <div class="p-3 rounded-xl bg-black/35 border border-white/5"><div class="flex justify-between gap-3"><p class="font-bold text-sm truncate"><?= esc($conversation['customer_name'] ?: 'Pelanggan tanpa nama') ?></p><span class="text-[10px] text-amber-300"><?= esc($conversation['status']) ?></span></div><p class="text-xs text-gray-500 mt-1 truncate"><?= esc($conversation['last_message'] ?: 'Belum ada ringkasan pesan') ?></p><p class="text-[10px] text-gray-600 mt-1"><?= esc($conversation['platform']) ?> · <?= !empty($conversation['last_message_at']) ? date('d M H:i', strtotime($conversation['last_message_at'])) : 'tanpa waktu' ?></p></div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 xl:grid-cols-3 gap-4" id="finance">
        <div class="ceo-card p-5 md:p-6"><p class="ceo-label">Piutang</p><h3 class="ceo-section-title text-lg mt-1">Invoice terbuka</h3><p class="ceo-value mt-5"><?= $rupiah($s['invoices']['amount']) ?></p><div class="flex gap-2 mt-4"><span class="ceo-chip"><?= $s['invoices']['open'] ?> terbuka</span><span class="ceo-chip text-red-300"><?= $s['invoices']['overdue'] ?> overdue</span></div></div>
        <div class="ceo-card p-5 md:p-6"><p class="ceo-label">Hutang operasional</p><h3 class="ceo-section-title text-lg mt-1">Tagihan perusahaan</h3><p class="ceo-value mt-5"><?= $rupiah($s['bills']['amount']) ?></p><div class="flex gap-2 mt-4"><span class="ceo-chip"><?= $s['bills']['due_soon'] ?> jatuh tempo ≤7 hari</span><span class="ceo-chip text-red-300"><?= $s['bills']['overdue'] ?> overdue</span></div><details class="mt-4 pt-4 border-t border-white/10"><summary class="cursor-pointer text-sm font-bold text-[#33e818]">+ Ajukan tagihan</summary><form method="post" action="<?= base_url('ceo/bills') ?>" class="grid grid-cols-2 gap-2 mt-4"><?= csrf_field() ?><input name="vendor" required maxlength="190" placeholder="Vendor" class="col-span-2 bg-black/50 border border-white/10 rounded-lg p-2 text-sm"><input name="reference" maxlength="100" placeholder="Referensi" class="col-span-2 bg-black/50 border border-white/10 rounded-lg p-2 text-sm"><input name="amount" type="number" min="0.01" step="0.01" required placeholder="Nominal" class="bg-black/50 border border-white/10 rounded-lg p-2 text-sm"><select name="currency" class="bg-black/50 border border-white/10 rounded-lg p-2 text-sm"><option>IDR</option><option>USD</option><option>SGD</option></select><input name="due_date" type="date" required class="bg-black/50 border border-white/10 rounded-lg p-2 text-sm"><select name="priority" class="bg-black/50 border border-white/10 rounded-lg p-2 text-sm"><option value="medium">Medium</option><option value="high">High</option><option value="urgent">Urgent</option></select><textarea name="description" maxlength="2000" placeholder="Deskripsi dan dasar tagihan" class="col-span-2 bg-black/50 border border-white/10 rounded-lg p-2 text-sm"></textarea><button type="submit" class="col-span-2 ceo-action ceo-action-primary">Simpan & buat approval</button></form></details></div>
        <div class="ceo-card p-5 md:p-6" id="customers"><p class="ceo-label">Kualitas funnel</p><h3 class="ceo-section-title text-lg mt-1">Status transaksi</h3><div class="mt-4 space-y-2"><?php if(empty($dashboard['transaction_statuses'])):?><p class="text-gray-500 text-sm">Belum ada transaksi.</p><?php else: foreach($dashboard['transaction_statuses'] as $status=>$total):?><div class="flex justify-between text-sm"><span class="text-gray-400"><?= esc(ucfirst($status ?: 'unknown')) ?></span><b><?= number_format((int)$total) ?></b></div><?php endforeach; endif;?></div></div>
    </section>

    <section class="grid grid-cols-1 xl:grid-cols-2 gap-4">
        <div class="ceo-card p-5 md:p-6" id="goals"><div class="flex justify-between items-end mb-4"><div><p class="ceo-label">Eksekusi strategi</p><h3 class="ceo-section-title text-lg mt-1">OKR & Sasaran</h3></div><span class="ceo-chip"><?= $s['goals']['active'] ?> aktif</span></div><?php if(empty($dashboard['goals'])):?><div class="py-5 text-center text-gray-500"><p>Belum ada sasaran strategis.</p><p class="text-xs mt-1">Empty state ini menggantikan data target palsu.</p></div><?php else: foreach($dashboard['goals'] as $goal): $progress=(float)$goal['target_value']>0?min(100,max(0,((float)$goal['actual_value']/(float)$goal['target_value'])*100)):0;?><div class="mb-4"><div class="flex justify-between gap-3"><div><p class="font-bold text-sm"><?= esc($goal['objective']) ?></p><p class="text-xs text-gray-500 mt-1"><?= esc($goal['owner']) ?> · sampai <?= date('d M Y',strtotime($goal['period_end'])) ?></p></div><b class="text-sm"><?= number_format($progress,0) ?>%</b></div><div class="h-2 bg-white/5 rounded-full mt-2 overflow-hidden"><div class="h-full bg-[#33e818]" style="width:<?= $progress ?>%"></div></div></div><?php endforeach; endif;?><details class="mt-4 pt-4 border-t border-white/10"><summary class="cursor-pointer text-sm font-bold text-[#33e818]">+ Tambah sasaran</summary><form method="post" action="<?= base_url('ceo/goals') ?>" class="grid grid-cols-2 gap-2 mt-4"><?= csrf_field() ?><input name="objective" required maxlength="255" placeholder="Objective" class="col-span-2 bg-black/50 border border-white/10 rounded-lg p-2 text-sm"><input name="owner" required maxlength="190" placeholder="Owner" class="col-span-2 bg-black/50 border border-white/10 rounded-lg p-2 text-sm"><input name="period_start" type="date" required class="bg-black/50 border border-white/10 rounded-lg p-2 text-sm"><input name="period_end" type="date" required class="bg-black/50 border border-white/10 rounded-lg p-2 text-sm"><input name="target_value" type="number" min="0.01" step="0.01" required placeholder="Target" class="bg-black/50 border border-white/10 rounded-lg p-2 text-sm"><input name="actual_value" type="number" min="0" step="0.01" required value="0" placeholder="Aktual" class="bg-black/50 border border-white/10 rounded-lg p-2 text-sm"><select name="unit" class="bg-black/50 border border-white/10 rounded-lg p-2 text-sm"><option value="number">Angka</option><option value="percent">Persen</option><option value="IDR">Rupiah</option><option value="users">Pengguna</option><option value="events">Event</option></select><input name="confidence" type="number" min="0" max="100" value="50" required class="bg-black/50 border border-white/10 rounded-lg p-2 text-sm"><select name="status" class="col-span-2 bg-black/50 border border-white/10 rounded-lg p-2 text-sm"><option value="on_track">On track</option><option value="at_risk">At risk</option><option value="off_track">Off track</option><option value="completed">Completed</option></select><textarea name="update_note" maxlength="2000" placeholder="Catatan terbaru" class="col-span-2 bg-black/50 border border-white/10 rounded-lg p-2 text-sm"></textarea><button type="submit" class="col-span-2 ceo-action ceo-action-primary">Simpan sasaran</button></form></details></div>
        <div class="ceo-card p-5 md:p-6"><div class="flex justify-between items-end mb-4"><div><p class="ceo-label">Aktivitas terbaru</p><h3 class="ceo-section-title text-lg mt-1">Transaksi Terkini</h3></div><span class="ceo-chip"><?= count($dashboard['recent_transactions']) ?></span></div><div class="space-y-2"><?php if(empty($dashboard['recent_transactions'])):?><p class="py-8 text-center text-gray-500">Tidak ada transaksi pada periode ini.</p><?php else: foreach($dashboard['recent_transactions'] as $trx):?><div class="p-3 rounded-xl bg-black/35 border border-white/5 flex justify-between gap-3"><div class="min-w-0"><p class="font-bold text-sm truncate"><?= esc($trx['product_name'] ?: $trx['invoice_number']) ?></p><p class="text-xs text-gray-500 mt-1 truncate"><?= esc($trx['user_name'] ?: 'Pengguna') ?> · <?= date('d M H:i',strtotime($trx['created_at'])) ?></p></div><div class="text-right shrink-0"><p class="font-bold text-sm text-[#33e818]"><?= $rupiah($trx['total']) ?></p><p class="text-[10px] text-gray-500 uppercase"><?= esc($trx['status']) ?></p></div></div><?php endforeach; endif;?></div></div>
    </section>
</div>

<div id="commandOverlay" class="hidden fixed inset-0 bg-black/75 z-[90] p-4 command-panel" role="dialog" aria-modal="true" aria-labelledby="commandTitle" onclick="if(event.target===this)closeCommandPalette()">
    <div class="max-w-xl mx-auto mt-[12vh] bg-[#101310] border border-white/10 rounded-2xl shadow-2xl overflow-hidden">
        <div class="p-4 border-b border-white/10 flex gap-3"><i class="fas fa-search text-[#33e818] mt-1"></i><input id="commandInput" class="flex-1 bg-transparent outline-none" placeholder="Cari KPI, approval, kalender, CRM..." oninput="filterCommands(this.value)"><button onclick="closeCommandPalette()" aria-label="Tutup pencarian"><i class="fas fa-xmark"></i></button></div>
        <div id="commandList" class="p-2 max-h-80 overflow-y-auto">
            <?php foreach ([['Executive Pulse','#pulseTitle','gauge-high'],['Approval Center','#approvals','signature'],['Kalender Terpadu',base_url('ceo/calendar'),'calendar'],['Risk & Alert','#risk','triangle-exclamation'],['Event & Webinar','#events','microphone'],['CRM Snapshot','#crm','comments'],['Invoice & Tagihan','#finance','file-invoice-dollar'],['OKR & Sasaran','#goals','bullseye']] as $command): ?>
                <a data-command="<?= strtolower($command[0]) ?>" href="<?= esc($command[1]) ?>" class="command-item flex items-center gap-3 p-3 rounded-xl hover:bg-white/5"><i class="fas fa-<?= $command[2] ?> w-5 text-[#33e818]"></i><span><?= esc($command[0]) ?></span></a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
function toggleCustomRange(value){document.getElementById('customRange').classList.toggle('hidden',value!=='custom');}
function openCommandPalette(){const o=document.getElementById('commandOverlay');o.classList.remove('hidden');setTimeout(()=>document.getElementById('commandInput').focus(),20);}
function closeCommandPalette(){document.getElementById('commandOverlay').classList.add('hidden');}
function filterCommands(value){const q=value.toLowerCase().trim();document.querySelectorAll('.command-item').forEach(el=>el.classList.toggle('hidden',!el.dataset.command.includes(q)));}
function confirmBatchApproval(action){const selected=document.querySelectorAll('input[name="approval_ids[]"]:checked').length;if(!selected){alert('Pilih minimal satu approval.');return false;}return confirm(`Anda akan ${action} ${selected} approval sekaligus. Lanjutkan?`);}
document.addEventListener('keydown',e=>{if((e.ctrlKey||e.metaKey)&&e.key.toLowerCase()==='k'){e.preventDefault();openCommandPalette();}if(e.key==='Escape')closeCommandPalette();});
</script>
<?= $this->endSection() ?>
