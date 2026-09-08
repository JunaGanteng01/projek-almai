<?= $this->extend('wpa/layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">Signal Center</h1>
            <p class="text-gray-400 text-sm">Kelola dan publikasikan setup trading Anda ke pengguna</p>
        </div>
        <a href="<?= base_url('wpa/dashboard/signal-center/create') ?>" class="px-5 py-2.5 bg-accent text-white rounded-xl hover:bg-accent/90 transition shadow-lg inline-flex items-center gap-2 font-medium">
            <i class="fas fa-plus"></i> Buat Signal Baru
        </a>
    </div>

    <?php if (session()->has('success')): ?>
        <div class="bg-green-500/10 border border-green-500/20 text-green-400 p-4 rounded-xl">
            <?= session('success') ?>
        </div>
    <?php endif; ?>

    <div class="bg-[#111] border border-white/10 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-white/5 border-b border-white/10 text-xs uppercase text-gray-400">
                    <tr>
                        <th class="px-6 py-4">Waktu</th>
                        <th class="px-6 py-4">Pair & TF</th>
                        <th class="px-6 py-4">Type & Harga</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 text-sm">
                    <?php if (empty($signals)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            Belum ada signal yang dibuat.
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($signals as $signal): ?>
                        <tr class="hover:bg-white/5 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-300">
                                <?= date('d M Y H:i', strtotime($signal['created_at'])) ?>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-white"><?= esc($signal['pair']) ?></div>
                                <div class="text-xs text-gray-400">TF: <?= esc($signal['timeframe']) ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <?php if($signal['type'] === 'BUY'): ?>
                                    <span class="inline-flex items-center gap-1 text-green-400 font-bold mb-1"><i class="fas fa-arrow-up"></i> BUY</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 text-red-400 font-bold mb-1"><i class="fas fa-arrow-down"></i> SELL</span>
                                <?php endif; ?>
                                <div class="text-xs text-gray-300">Entry: <?= (float)$signal['entry_price'] ?></div>
                                <div class="text-xs text-gray-400">SL: <?= (float)$signal['sl'] ?> | TP: <?= (float)$signal['tp1'] ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <?php if($signal['status'] === 'ACTIVE'): ?>
                                    <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-medium">ACTIVE</span>
                                <?php else: ?>
                                    <span class="px-3 py-1 bg-gray-500/20 text-gray-400 rounded-full text-xs font-medium">CLOSED</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="<?= base_url('wpa/dashboard/signal-center/delete/'.$signal['id']) ?>" method="POST" class="inline-block" onsubmit="return confirm('Hapus signal ini?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="text-red-400 hover:text-red-300 px-3 py-1">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
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
