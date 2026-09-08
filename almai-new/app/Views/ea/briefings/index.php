<?= $this->extend('ea/layouts/main') ?>
<?= $this->section('content') ?>
<?php $pageTitle = 'CEO Daily Briefing'; $pageSubtitle = 'Ringkasan deterministik dari data ALMAI, tanpa angka rekaan.'; $s = $dashboard['summary']; ?>
<div class="max-w-6xl mx-auto space-y-5">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-3">
        <div><p class="text-xs uppercase tracking-[.18em] text-[#33e818] font-bold"><?= esc($briefing_date) ?></p><h2 class="text-3xl font-extrabold mt-2"><?= esc($greeting) ?></h2></div>
        <a href="<?= base_url('ceo/dashboard') ?>" class="px-4 py-2 rounded-xl bg-[#33e818] text-black font-bold text-sm"><i class="fas fa-gauge-high mr-2"></i>Buka Dashboard</a>
    </div>
    <section class="bg-gradient-to-br from-[#101810] to-[#080908] border border-[#33e818]/20 rounded-2xl p-6 md:p-8">
        <div class="flex items-center gap-3 mb-4"><div class="w-10 h-10 rounded-xl bg-[#33e818]/15 text-[#33e818] flex items-center justify-center"><i class="fas fa-wand-magic-sparkles"></i></div><div><p class="text-xs uppercase tracking-widest text-gray-500">Ringkasan otomatis</p><h3 class="font-bold">Fokus eksekutif hari ini</h3></div></div>
        <p class="text-gray-200 leading-8"><?= esc($briefing) ?></p>
        <p class="text-xs text-gray-600 mt-4">Dihasilkan <?= date('d M Y, H:i', strtotime($dashboard['generated_at'])) ?> dari transaksi, approval, CRM, invoice, dan alert. Fallback ini tetap berjalan tanpa layanan AI eksternal.</p>
    </section>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-[#111] border border-white/10 rounded-xl p-4"><p class="text-xs text-gray-500 uppercase">Revenue MTD</p><p class="text-xl font-bold mt-2">Rp <?= number_format($s['revenue'],0,',','.') ?></p></div>
        <div class="bg-[#111] border border-white/10 rounded-xl p-4"><p class="text-xs text-gray-500 uppercase">Approval</p><p class="text-xl font-bold mt-2 text-amber-300"><?= $s['approvals']['pending'] ?></p></div>
        <div class="bg-[#111] border border-white/10 rounded-xl p-4"><p class="text-xs text-gray-500 uppercase">CRM SLA</p><p class="text-xl font-bold mt-2 text-red-300"><?= $s['crm']['sla_breach'] ?></p></div>
        <div class="bg-[#111] border border-white/10 rounded-xl p-4"><p class="text-xs text-gray-500 uppercase">Event</p><p class="text-xl font-bold mt-2 text-purple-300"><?= $s['events']['upcoming'] ?></p></div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <section class="bg-[#111] border border-white/10 rounded-2xl p-5">
            <div class="flex justify-between mb-4"><h3 class="font-bold">Agenda 7 Hari</h3><a href="<?= base_url('ceo/calendar') ?>" class="text-xs text-[#33e818]">Kalender →</a></div>
            <?php if(empty($events)): ?><p class="text-gray-500 text-sm py-8 text-center">Tidak ada agenda dalam 7 hari.</p><?php else: foreach($events as $event): ?>
                <div class="flex gap-3 py-3 border-b border-white/5"><span class="w-2 h-2 rounded-full mt-1.5" style="background:<?= esc($event['colorCode']) ?>"></span><div><p class="font-bold text-sm"><?= esc($event['title']) ?></p><p class="text-xs text-gray-500 mt-1"><?= date('d M Y, H:i',strtotime($event['start'])) ?> · <?= esc($event['type']) ?></p></div></div>
            <?php endforeach; endif; ?>
        </section>
        <section class="bg-[#111] border border-white/10 rounded-2xl p-5">
            <h3 class="font-bold mb-4">Perhatian Utama</h3>
            <?php if(empty($dashboard['alerts'])): ?><p class="text-gray-500 text-sm py-8 text-center">Tidak ada alert aktif.</p><?php else: foreach($dashboard['alerts'] as $alert): ?>
                <a href="<?= esc($alert['link']) ?>" class="block p-3 bg-black/40 rounded-xl border border-white/5 mb-2"><p class="font-bold text-sm text-red-300"><?= esc($alert['title']) ?></p><p class="text-xs text-gray-400 mt-1"><?= esc($alert['reason']) ?></p></a>
            <?php endforeach; endif; ?>
        </section>
    </div>
</div>
<?= $this->endSection() ?>
