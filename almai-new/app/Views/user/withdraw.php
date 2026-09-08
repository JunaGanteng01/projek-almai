<?= $this->extend('user/partials/layout') ?>

<?= $this->section('content') ?>

<!-- Welcome Text -->
<div class="mb-4">
    <p class="text-gray-400 text-xs mb-0.5">Kelola keuangan Anda,</p>
    <h1 class="text-lg font-bold text-white">Tarik Dana</h1>
</div>

<!-- Saldo Card -->
<div class="relative w-full bg-gradient-to-br from-[#0a3d1f] via-[#0c4a24] to-[#0a3d1f] rounded-2xl p-4 mb-6 overflow-hidden shadow-xl border border-[#1a5c32]">
    <div class="absolute top-0 right-0 w-full h-full opacity-10 pointer-events-none">
        <div class="absolute top-2 right-2 w-24 h-24 bg-accent/20 rounded-full blur-2xl"></div>
        <div class="absolute bottom-0 right-0 w-32 h-32 bg-accent/10 rounded-full blur-2xl"></div>
    </div>
    <div class="absolute top-0 right-0 opacity-20 pointer-events-none">
        <img src="<?= base_url('images/dompet.png') ?>" alt="Wallet" class="w-32 h-32 object-contain">
    </div>

    <div class="relative z-10 mb-4">
        <div class="flex items-center gap-2 mb-1">
            <span class="text-gray-300 text-xs">Saldo Tersedia</span>
            <button onclick="toggleBalanceVisibility()" class="text-gray-400 hover:text-white transition">
                <i class="fas fa-eye text-xs" id="balanceIcon"></i>
            </button>
        </div>
        <div class="flex items-center gap-2">
            <h2 class="text-2xl font-bold text-accent tracking-tight" id="balanceDisplay">
                Rp <?= number_format($availableBalance, 0, ',', '.') ?>
            </h2>
            <h2 class="text-2xl font-bold text-accent tracking-tight hidden" id="balanceHidden">Rp ••••••</h2>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="relative z-10 grid grid-cols-2 gap-3">
        <div class="bg-black/20 rounded-xl p-3 border border-white/10">
            <p class="text-gray-400 text-[10px] mb-0.5">Telah Ditarik</p>
            <p class="text-blue-400 text-sm font-bold">Rp <?= number_format($totalWithdrawn, 0, ',', '.') ?></p>
        </div>
        <div class="bg-black/20 rounded-xl p-3 border border-white/10">
            <p class="text-gray-400 text-[10px] mb-0.5">Dalam Proses</p>
            <p class="text-yellow-400 text-sm font-bold">Rp <?= number_format($pendingWithdrawal, 0, ',', '.') ?></p>
        </div>
    </div>
</div>

<!-- Info Box -->
<div class="bg-blue-500/10 border border-blue-500/20 rounded-xl p-4 mb-6">
    <h4 class="font-bold text-blue-400 text-sm mb-2 flex items-center gap-2">
        <i class="fas fa-info-circle"></i> Informasi Penarikan
    </h4>
    <ul class="text-xs text-gray-400 space-y-1.5 list-disc pl-4">
        <li>Minimal penarikan adalah <strong class="text-white">Rp 1.000.000</strong></li>
        <li>Proses penarikan membutuhkan waktu <strong class="text-white">1–3 hari kerja</strong></li>
        <li>Pastikan data rekening bank sudah benar di profil KYC Anda</li>
        <li>Fitur ini hanya tersedia untuk <strong class="text-accent">Member PRO</strong></li>
    </ul>
</div>

<!-- Form Penarikan -->
<div class="bg-[#111] border border-white/10 rounded-2xl p-5 mb-6">
    <h3 class="text-base font-bold text-white mb-4">Formulir Penarikan</h3>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl mb-4 text-xs flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-accent/10 border border-accent/20 text-accent px-4 py-3 rounded-xl mb-4 text-xs flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (empty($userBank['bank_name']) || empty($userBank['account_number'])): ?>
        <!-- Belum ada data bank -->
        <div class="text-center py-6">
            <div class="w-14 h-14 bg-yellow-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-university text-yellow-400 text-xl"></i>
            </div>
            <p class="text-white text-sm font-bold mb-1">Data Rekening Belum Lengkap</p>
            <p class="text-gray-400 text-xs mb-4">Lengkapi data rekening bank Anda di halaman KYC terlebih dahulu.</p>
            <a href="<?= base_url('user/kyc') ?>" class="inline-flex items-center gap-2 bg-accent text-black font-bold text-xs px-5 py-2.5 rounded-xl hover:bg-accent/90 transition">
                <i class="fas fa-id-card"></i> Lengkapi KYC
            </a>
        </div>
    <?php else: ?>
        <form action="<?= base_url('user/withdraw/store') ?>" method="post" class="space-y-4">
            <?= csrf_field() ?>

            <!-- Jumlah -->
            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Jumlah Penarikan (Rp)</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-bold">Rp</span>
                    <input type="number" name="amount" required
                        min="1000000" max="<?= $availableBalance ?>"
                        value="<?= old('amount') ?>"
                        class="w-full bg-black border border-white/10 rounded-xl pl-10 pr-4 py-3 text-sm focus:border-accent focus:outline-none transition"
                        placeholder="1000000">
                </div>
                <p class="text-[10px] text-gray-500 mt-1">Saldo tersedia: <span class="text-accent font-bold">Rp <?= number_format($availableBalance, 0, ',', '.') ?></span></p>
            </div>

            <!-- Quick Amount Buttons -->
            <div class="flex gap-2 flex-wrap">
                <?php foreach ([1000000, 2000000, 5000000, 10000000] as $nominal): ?>
                    <?php if ($nominal <= $availableBalance): ?>
                        <button type="button" onclick="setAmount(<?= $nominal ?>)"
                            class="text-xs bg-white/5 hover:bg-accent/20 hover:text-accent border border-white/10 hover:border-accent/30 px-3 py-1.5 rounded-lg transition">
                            Rp <?= number_format($nominal, 0, ',', '.') ?>
                        </button>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <!-- Data Bank (readonly dari KYC) -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs text-gray-400 mb-1.5">Nama Bank</label>
                    <input type="text" name="bank_name" value="<?= esc($userBank['bank_name']) ?>" readonly
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-gray-300 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs text-gray-400 mb-1.5">Nomor Rekening</label>
                    <input type="text" name="account_number" value="<?= esc($userBank['account_number']) ?>" readonly
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-gray-300 cursor-not-allowed">
                </div>
            </div>

            <div>
                <label class="block text-xs text-gray-400 mb-1.5">Nama Pemilik Rekening</label>
                <input type="text" name="account_holder" value="<?= esc($userBank['account_holder']) ?>" readonly
                    class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-gray-300 cursor-not-allowed">
            </div>

            <p class="text-[10px] text-gray-500">
                <i class="fas fa-lock text-[9px] mr-1"></i>
                Data rekening diambil dari KYC Anda. 
                <a href="<?= base_url('user/kyc') ?>" class="text-accent hover:underline">Ubah di KYC</a>
            </p>

            <button type="submit"
                <?= $availableBalance < 1000000 ? 'disabled' : '' ?>
                class="w-full py-3.5 bg-accent text-black font-bold text-sm rounded-xl hover:bg-accent/90 transition disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                <i class="fas fa-arrow-up"></i>
                Ajukan Penarikan
            </button>
        </form>
    <?php endif; ?>
</div>

<!-- Riwayat Penarikan -->
<div class="bg-[#111] border border-white/10 rounded-2xl p-5 mb-6">
    <h3 class="text-base font-bold text-white mb-4">Riwayat Penarikan</h3>

    <?php if (empty($withdrawals)): ?>
        <div class="text-center py-8">
            <div class="w-12 h-12 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-history text-gray-600 text-lg"></i>
            </div>
            <p class="text-gray-500 text-xs">Belum ada riwayat penarikan.</p>
        </div>
    <?php else: ?>
        <div class="space-y-3">
            <?php foreach ($withdrawals as $wd): ?>
                <?php
                $statusMap = [
                    'pending'   => ['bg-yellow-500/20 text-yellow-400 border-yellow-500/20', 'Menunggu'],
                    'approved'  => ['bg-blue-500/20 text-blue-400 border-blue-500/20', 'Disetujui'],
                    'completed' => ['bg-accent/20 text-accent border-accent/20', 'Selesai'],
                    'rejected'  => ['bg-red-500/20 text-red-400 border-red-500/20', 'Ditolak'],
                ];
                $st = $statusMap[$wd['status']] ?? ['bg-gray-500/20 text-gray-400 border-gray-500/20', $wd['status']];
                ?>
                <div class="flex items-center justify-between p-3 bg-black/30 rounded-xl border border-white/5 hover:border-white/10 transition">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-white/5 flex items-center justify-center shrink-0">
                            <i class="fas fa-university text-gray-400 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-white text-xs font-semibold"><?= esc($wd['bank_name']) ?> — <?= esc($wd['account_number']) ?></p>
                            <p class="text-gray-500 text-[10px]"><?= date('d M Y, H:i', strtotime($wd['created_at'])) ?> WIB</p>
                            <?php if (!empty($wd['admin_notes'])): ?>
                                <p class="text-yellow-400 text-[10px] mt-0.5"><i class="fas fa-comment-alt text-[9px] mr-1"></i><?= esc($wd['admin_notes']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="text-right shrink-0 ml-3">
                        <p class="text-white text-sm font-bold">Rp <?= number_format($wd['amount'], 0, ',', '.') ?></p>
                        <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-[10px] border <?= $st[0] ?>">
                            <?= $st[1] ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let isBalanceVisible = true;

    function toggleBalanceVisibility() {
        const display = document.getElementById('balanceDisplay');
        const hidden  = document.getElementById('balanceHidden');
        const icon    = document.getElementById('balanceIcon');
        isBalanceVisible = !isBalanceVisible;
        if (isBalanceVisible) {
            display.classList.remove('hidden');
            hidden.classList.add('hidden');
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        } else {
            display.classList.add('hidden');
            hidden.classList.remove('hidden');
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        }
    }

    function setAmount(val) {
        const input = document.querySelector('input[name="amount"]');
        if (input) input.value = val;
    }
</script>
<?= $this->endSection() ?>
