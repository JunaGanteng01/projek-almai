<?php 
$this->setVar('pageTitle', 'Chat Leads');
$this->setVar('pageSubtitle', 'Data leads dari AI Chatbot (Read Only)');
?>
<?= $this->extend('laporan-kegiatan/layouts/main') ?>

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
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-black/50">
                <tr>
                    <th class="text-center px-3 py-3 text-xs font-medium text-gray-400 w-12">No</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Nama</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">WhatsApp</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Level</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Budget</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Minat Layanan</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Tanggal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($leads)): ?>
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                        <i class="fas fa-robot text-4xl mb-4 opacity-20"></i>
                        <p>Belum ada leads dari chatbot</p>
                    </td>
                </tr>
                <?php else: ?>
                <?php foreach ($leads as $index => $lead): ?>
                <tr class="hover:bg-white/5 transition">
                    <td class="px-3 py-3 text-center text-gray-500 text-sm"><?= $index + 1 ?></td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-accent/20 flex items-center justify-center text-accent font-bold text-xs">
                                <?= strtoupper(substr($lead->name, 0, 2)) ?>
                            </div>
                            <p class="font-medium text-sm text-gray-200"><?= esc($lead->name) ?></p>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-accent text-sm font-mono"><?= esc($lead->whatsapp) ?></span>
                    </td>
                    <td class="px-4 py-3">
                        <?php if ($lead->level): ?>
                        <span class="px-2 py-0.5 bg-blue-500/10 text-blue-400 rounded text-[10px] font-bold uppercase tracking-wider border border-blue-500/20">
                            <?= esc($lead->level) ?>
                        </span>
                        <?php else: ?>
                        <span class="text-gray-600 text-xs">-</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <?php if ($lead->budget): ?>
                        <span class="px-2 py-0.5 bg-green-500/10 text-green-400 rounded text-[10px] font-bold uppercase tracking-wider border border-green-500/20">
                            <?php 
                            $budgetText = $lead->budget === 'budget_low' ? '< 500K' : ($lead->budget === 'budget_mid' ? '500K - 2M' : '> 2M');
                            echo $budgetText;
                            ?>
                        </span>
                        <?php else: ?>
                        <span class="text-gray-600 text-xs">-</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3">
                        <span class="text-gray-400 text-xs truncate max-w-[150px] inline-block"><?= esc($lead->service_interested ?: '-') ?></span>
                    </td>
                    <td class="px-4 py-3 text-gray-500 text-xs">
                        <?= date('d M Y H:i', strtotime($lead->created_at)) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
