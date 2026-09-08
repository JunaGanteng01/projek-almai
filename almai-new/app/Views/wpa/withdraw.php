<?= $this->extend('wpa/layouts/main') ?>

<?= $this->section('content') ?>
<?php $pageTitle = 'Penarikan Dana'; $pageSubtitle = 'Tarik penghasilan Anda ke rekening bank'; ?>

<div class="grid md:grid-cols-3 gap-6">
    <!-- Left: Info & Stats -->
    <div class="md:col-span-1 space-y-6">
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
            <p class="text-gray-500 text-sm mb-1">Saldo Tersedia</p>
            <h3 class="text-3xl font-bold text-accent mb-4">Rp <?= number_format($availableBalance, 0, ',', '.') ?></h3>
            
            <div class="space-y-3 pt-4 border-t border-white/10">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-400">Total Penghasilan</span>
                    <span class="font-medium">Rp <?= number_format($totalEarnings, 0, ',', '.') ?></span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-400">Telah Ditarik</span>
                    <span class="font-medium text-blue-400">Rp <?= number_format($totalWithdrawn, 0, ',', '.') ?></span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-400">Dalam Proses</span>
                    <span class="font-medium text-yellow-500">Rp <?= number_format($pendingWithdrawal, 0, ',', '.') ?></span>
                </div>
            </div>
        </div>

        <div class="bg-blue-500/10 border border-blue-500/20 rounded-2xl p-6">
            <h4 class="font-bold text-blue-400 mb-3 flex items-center gap-2">
                <i class="fas fa-info-circle"></i> Informasi Penarikan
            </h4>
            <ul class="text-xs text-gray-400 space-y-2 list-disc pl-4">
                <li>Minimal penarikan adalah Rp 1.000.000</li>
                <li>Proses penarikan membutuhkan waktu 1-3 hari kerja</li>
                <li>Pastikan data rekening bank sudah benar</li>
                <li>Biaya admin mungkin dikenakan tergantung bank tujuan</li>
            </ul>
        </div>
    </div>

    <!-- Right: Withdraw Form & History -->
    <div class="md:col-span-2 space-y-6">
        <!-- Form -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
            <h3 class="font-bold mb-6">Formulir Penarikan</h3>
            
            <?php if (session()->getFlashdata('error')): ?>
                <div class="bg-red-500/10 border border-red-500/20 text-red-500 p-4 rounded-xl mb-6 text-sm">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="bg-accent/10 border border-accent/20 text-accent p-4 rounded-xl mb-6 text-sm">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('wpa/dashboard/withdraw/store') ?>" method="post" class="space-y-4">
                <?= csrf_field() ?>
                
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Jumlah Penarikan (Rp)</label>
                    <input type="number" name="amount" required min="1000000" max="<?= $availableBalance ?>"
                        class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 focus:border-accent focus:outline-none transition"
                        placeholder="Contoh: 1000000">
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Nama Bank</label>
                        <input type="text" name="bank_name" value="<?= esc($userBank['bank_name']) ?>" readonly
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-gray-400 cursor-not-allowed">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Nomor Rekening</label>
                        <input type="text" name="account_number" value="<?= esc($userBank['account_number']) ?>" readonly
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-gray-400 cursor-not-allowed">
                    </div>
                </div>

                <div>
                    <label class="block text-sm text-gray-400 mb-2">Nama Pemilik Rekening</label>
                    <input type="text" name="account_holder" value="<?= esc($userBank['account_holder']) ?>" readonly
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-gray-400 cursor-not-allowed">
                </div>

                <button type="submit" 
                    <?= $availableBalance < 1000000 ? 'disabled' : '' ?>
                    class="w-full py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition disabled:opacity-50 disabled:cursor-not-allowed">
                    Ajukan Penarikan
                </button>
            </form>
        </div>

        <!-- History -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
            <h3 class="font-bold mb-6">Riwayat Penarikan</h3>
            <div class="table-responsive">
                <table class="w-full min-w-[500px]">
                    <thead>
                        <tr class="text-left text-gray-500 text-xs border-b border-white/10">
                            <th class="pb-3 px-2">Tanggal</th>
                            <th class="pb-3 px-2">Bank</th>
                            <th class="pb-3 px-2 text-right">Jumlah</th>
                            <th class="pb-3 px-2 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($withdrawals)): ?>
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-500 text-sm">Belum ada riwayat penarikan</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($withdrawals as $wd): ?>
                        <tr class="border-b border-white/5 hover:bg-white/5 transition">
                            <td class="py-3 px-2 text-xs">
                                <p class="text-white"><?= date('d M Y', strtotime($wd['created_at'])) ?></p>
                                <p class="text-[10px] text-gray-500"><?= date('H:i', strtotime($wd['created_at'])) ?> WIB</p>
                            </td>
                            <td class="py-3 px-2 text-xs">
                                <p class="text-white"><?= esc($wd['bank_name']) ?></p>
                                <p class="text-[10px] text-gray-500"><?= esc($wd['account_number']) ?></p>
                            </td>
                            <td class="py-3 px-2 text-xs text-white font-bold text-right">
                                Rp <?= number_format($wd['amount'], 0, ',', '.') ?>
                            </td>
                            <td class="py-3 px-2 text-center">
                                <?php
                                $statusMap = [
                                    'pending' => ['bg-yellow-500/20', 'text-yellow-400', 'Pending'],
                                    'approved' => ['bg-blue-500/20', 'text-blue-400', 'Disetujui'],
                                    'completed' => ['bg-accent/20', 'text-accent', 'Selesai'],
                                    'rejected' => ['bg-red-500/20', 'text-red-400', 'Ditolak'],
                                ];
                                $st = $statusMap[$wd['status']] ?? ['bg-gray-500/20', 'text-gray-400', $wd['status']];
                                ?>
                                <span class="px-2 py-0.5 <?= $st[0] ?> <?= $st[1] ?> rounded-full text-[10px]">
                                    <?= $st[2] ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
