<?= $this->extend('ea/layouts/main') ?>
<?= $this->section('content') ?>
<?php $pageTitle = 'Pusat Notifikasi'; $activeMenu = 'notifications'; ?>
<div class="max-w-4xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 mb-6">
        <div><p class="text-xs uppercase tracking-[.18em] text-gray-500 font-bold">Executive inbox</p><h2 class="text-2xl font-extrabold mt-1">Notifikasi & Peringatan</h2><p class="text-sm text-gray-400 mt-2">Hanya notifikasi milik akun CEO yang ditampilkan.</p></div>
        <form method="post" action="<?= base_url('ceo/notifications/read-all') ?>"><?= csrf_field() ?><button class="px-4 py-2 rounded-xl bg-white/5 border border-white/10 hover:border-[#33e818]/50 text-sm" type="submit"><i class="fas fa-check-double mr-2 text-[#33e818]"></i>Tandai semua dibaca</button></form>
    </div>
    <div class="bg-[#0d0f0d] border border-white/10 rounded-2xl overflow-hidden">
        <?php if (empty($alerts)): ?>
            <div class="p-12 text-center text-gray-500"><i class="far fa-bell text-4xl mb-3"></i><p>Belum ada notifikasi.</p></div>
        <?php else: foreach ($alerts as $alert): ?>
            <div class="p-4 border-b border-white/5 flex gap-3 <?= !empty($alert['is_read']) ? 'opacity-60' : '' ?>">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 <?= ($alert['type'] ?? '') === 'error' ? 'bg-red-500/15 text-red-300' : (($alert['type'] ?? '') === 'success' ? 'bg-[#33e818]/15 text-[#33e818]' : 'bg-blue-500/15 text-blue-300') ?>"><i class="fas fa-bell"></i></div>
                <div class="flex-1 min-w-0"><p class="font-bold text-sm"><?= esc($alert['title']) ?></p><p class="text-sm text-gray-400 mt-1"><?= esc($alert['message']) ?></p><p class="text-xs text-gray-600 mt-2"><?= date('d M Y, H:i', strtotime($alert['created_at'])) ?></p></div>
                <?php if (empty($alert['is_read'])): ?><form method="post" action="<?= base_url('ceo/notifications/' . $alert['id'] . '/read') ?>"><?= csrf_field() ?><button type="submit" class="p-2 text-[#33e818]" title="Tandai dibaca"><i class="fas fa-check"></i></button></form><?php endif; ?>
            </div>
        <?php endforeach; endif; ?>
    </div>
    <?php if (isset($pager)): ?><div class="mt-4"><?= $pager->links() ?></div><?php endif; ?>
</div>
<?= $this->endSection() ?>
