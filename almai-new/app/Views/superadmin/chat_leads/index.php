<?php 
$pageTitle = 'Chat Leads'; 
$pageSubtitle = 'Data leads dari AI Chatbot'; 
?>
<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-accent/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-users text-accent"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= count($leads) ?></p>
                <p class="text-xs text-gray-500">Total Leads</p>
            </div>
        </div>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-chart-line text-blue-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= count(array_filter($leads, fn($l) => !empty($l->level))) ?></p>
                <p class="text-xs text-gray-500">Dengan Level</p>
            </div>
        </div>
    </div>
    <div class="bg-[#111] border border-white/10 rounded-xl p-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-green-500/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-dollar-sign text-green-500"></i>
            </div>
            <div>
                <p class="text-2xl font-bold"><?= count(array_filter($leads, fn($l) => !empty($l->budget))) ?></p>
                <p class="text-xs text-gray-500">Dengan Budget</p>
            </div>
        </div>
    </div>
</div>

<!-- Leads Table -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-black/50">
                <tr>
                    <th class="text-center px-3 py-3 text-xs font-medium text-gray-400 w-12">No</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Nama</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">WhatsApp</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400 hide-mobile">Level</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400 hide-mobile">Budget</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Minat Layanan</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400 hide-mobile">Tanggal</th>
                    <th class="text-center px-4 py-3 text-xs font-medium text-gray-400">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($leads)): ?>
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                        <i class="fas fa-robot text-4xl mb-4"></i>
                        <p>Belum ada leads dari chatbot</p>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($leads as $index => $lead): ?>
                <tr class="border-t border-white/5 hover:bg-white/5">
                    <td class="px-3 py-3 text-center text-gray-500 text-sm"><?= $index + 1 ?></td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-accent/20 flex items-center justify-center text-accent font-bold">
                                <?= strtoupper(substr($lead->name, 0, 2)) ?>
                            </div>
                            <div>
                                <p class="font-medium text-sm"><?= esc($lead->name) ?></p>
                                <p class="text-xs text-gray-500 md:hidden"><?= esc($lead->whatsapp) ?></p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <a href="https://wa.me/<?= esc($lead->whatsapp) ?>" target="_blank" class="text-accent hover:underline text-sm">
                            <i class="fab fa-whatsapp mr-1"></i><?= esc($lead->whatsapp) ?>
                        </a>
                    </td>
                    <td class="px-4 py-3 hide-mobile">
                        <?php if ($lead->level): ?>
                        <span class="px-2 py-1 bg-blue-500/20 text-blue-400 rounded-full text-xs capitalize">
                            <?= esc($lead->level) ?>
                        </span>
                        <?php else: ?>
                        <span class="text-gray-500 text-xs">-</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 hide-mobile">
                        <?php if ($lead->budget): ?>
                        <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded-full text-xs">
                            <?php 
                            $budgetText = $lead->budget === 'budget_low' ? '< 500K' : ($lead->budget === 'budget_mid' ? '500K - 2M' : '> 2M');
                            echo $budgetText;
                            ?>
                        </span>
                        <?php else: ?>
                        <span class="text-gray-500 text-xs">-</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-white text-sm"><?= esc($lead->service_interested ?: '-') ?></span>
                    </td>
                    <td class="px-4 py-3 text-gray-400 text-xs hide-mobile">
                        <?= date('d M Y H:i', strtotime($lead->created_at)) ?>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1">
                            <a href="https://wa.me/<?= esc($lead->whatsapp) ?>?text=Halo%20<?= urlencode($lead->name) ?>,%20terima%20kasih%20telah%20menghubungi%20ALMAI" target="_blank" class="w-8 h-8 flex items-center justify-center bg-green-500/20 text-green-400 rounded-lg hover:bg-green-500 hover:text-white transition" title="Chat WA">
                                <i class="fab fa-whatsapp text-xs"></i>
                            </a>
                            <form action="<?= base_url('superadmin/chat-leads/delete/' . $lead->id) ?>" method="post" class="inline" onsubmit="return confirm('Hapus lead <?= esc($lead->name) ?>?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="w-8 h-8 flex items-center justify-center bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500 hover:text-white transition" title="Hapus">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
