<?= $this->extend('keuangan/layouts/main') ?>

<?= $this->section('content') ?>
<div class="px-6 py-8 w-full">
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-white">Xendit Dashboard</h1>
            <p class="text-gray-400">Monitoring Saldo & Transaksi Payment Gateway</p>
        </div>
        <div class="flex gap-2">
            <button onclick="openReportModal()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2">
                <i class="fas fa-file-export"></i> Request Report
            </button>
            <a href="https://dashboard.xendit.co" target="_blank" class="px-4 py-2 bg-[#111] border border-white/10 hover:bg-white/5 text-white rounded-lg transition flex items-center gap-2">
                <i class="fas fa-external-link-alt"></i> Xendit Dashboard
            </a>
        </div>
    </div>

    <?php if (isset($error) && $error): ?>
        <div class="bg-red-500/10 border border-red-500/30 text-red-400 p-4 rounded-xl mb-6 flex items-center gap-3">
            <i class="fas fa-exclamation-triangle"></i>
            <p>API Error: <?= esc($error) ?></p>
        </div>
    <?php endif; ?>

    <!-- Filter Bar -->
    <div class="bg-[#111] border border-white/10 p-6 rounded-2xl mb-8 shadow-xl">
        <form action="<?= base_url('keuangan/xendit') ?>" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 w-full">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Cari Reference ID</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
                    <input type="text" name="search" value="<?= esc($filters['search'] ?? '') ?>" placeholder="Misal: INV-12345..." class="w-full bg-black border border-white/10 rounded-xl pl-12 pr-4 py-2.5 text-white focus:border-accent outline-none transition">
                </div>
            </div>
            <div class="w-full md:w-48">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Dari Tanggal</label>
                <input type="date" name="start_date" value="<?= esc($filters['start_date'] ?? '') ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-2 text-white focus:border-accent outline-none [color-scheme:dark]">
            </div>
            <div class="w-full md:w-48">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Sampai Tanggal</label>
                <input type="date" name="end_date" value="<?= esc($filters['end_date'] ?? '') ?>" class="w-full bg-black border border-white/10 rounded-xl px-4 py-2 text-white focus:border-accent outline-none [color-scheme:dark]">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-6 py-2.5 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center gap-2">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <?php if (!empty($filters['start_date']) || !empty($filters['search'])): ?>
                    <a href="<?= base_url('keuangan/xendit') ?>" class="px-4 py-2.5 bg-white/5 text-gray-400 rounded-xl hover:text-white transition">Reset</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Balance Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <!-- Main Balance -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-accent/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-wallet text-accent text-sm"></i>
                </div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Saldo Utama (CASH)</p>
            </div>
            <h3 class="text-2xl font-bold text-white">Rp <?= number_format($balances['CASH'] ?? 0, 0, ',', '.') ?></h3>
            <p class="text-[10px] text-gray-500 mt-2">Dana siap ditarik</p>
        </div>

        <!-- Holding Balance -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-yellow-500/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-500 text-sm"></i>
                </div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Saldo Tertahan</p>
            </div>
            <h3 class="text-2xl font-bold text-white">Rp <?= number_format($balances['HOLDING'] ?? 0, 0, ',', '.') ?></h3>
            <p class="text-[10px] text-gray-500 mt-2">Proses settlement</p>
        </div>

        <!-- Total Withdraw -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-red-500/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-arrow-up text-red-500 text-sm"></i>
                </div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Withdraw</p>
            </div>
            <h3 class="text-2xl font-bold text-white">Rp <?= number_format($balances['WITHDRAW'] ?? 0, 0, ',', '.') ?></h3>
            <p class="text-[10px] text-gray-500 mt-2">Akumulasi keluar</p>
        </div>

        <!-- Total Assets -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-blue-500/20 rounded-xl flex items-center justify-center">
                    <i class="fas fa-coins text-blue-500 text-sm"></i>
                </div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Keseluruhan</p>
            </div>
            <h3 class="text-2xl font-bold text-white">Rp <?= number_format($balances['TOTAL'] ?? 0, 0, ',', '.') ?></h3>
            <p class="text-[10px] text-gray-500 mt-2">Saldo real-time</p>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex border-b border-white/10 mb-6">
        <button onclick="switchTab('transactions')" id="tab-transactions" class="px-6 py-3 text-sm font-bold border-b-2 border-accent text-accent transition-all">
            Semua Transaksi
        </button>
        <button onclick="switchTab('withdrawals')" id="tab-withdrawals" class="px-6 py-3 text-sm font-bold border-b-2 border-transparent text-gray-500 hover:text-white transition-all">
            Penarikan (Disbursement)
        </button>
    </div>

    <!-- Transactions Table -->
    <div id="section-transactions" class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
        <div class="p-6 border-b border-white/10 flex justify-between items-center">
            <h3 class="font-bold text-white">Transaksi Terbaru</h3>
            <span class="text-xs text-gray-500">Menampilkan 20 transaksi terakhir</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-black/50 text-gray-400 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold">ID / Reference</th>
                        <th class="px-6 py-4 font-semibold">Tipe</th>
                        <th class="px-6 py-4 font-semibold text-right">Jumlah</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                        <th class="px-6 py-4 font-semibold">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-sm">
                    <?php if (empty($transactions)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic">
                                Tidak ada data transaksi yang ditemukan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($transactions as $trx): ?>
                            <tr class="hover:bg-white/5 transition">
                                <td class="px-6 py-4">
                                    <p class="text-white font-mono text-xs"><?= esc($trx['id']) ?></p>
                                    <p class="text-gray-500 text-[10px]"><?= esc($trx['reference_id'] ?? '-') ?></p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase <?= $trx['type'] === 'DISBURSEMENT' ? 'bg-red-500/20 text-red-400' : 'bg-blue-500/20 text-blue-400' ?>">
                                        <?= esc($trx['type']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-bold <?= $trx['amount'] < 0 ? 'text-red-400' : 'text-accent' ?>">
                                    Rp <?= number_format(abs($trx['amount']), 0, ',', '.') ?>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase <?= $trx['status'] === 'SUCCESS' ? 'bg-accent/20 text-accent' : 'bg-yellow-500/20 text-yellow-400' ?>">
                                        <?= esc($trx['status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-400">
                                    <?= date('d M Y, H:i', strtotime($trx['created'])) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Withdrawals Table -->
    <div id="section-withdrawals" class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl hidden">
        <div class="p-6 border-b border-white/10 flex justify-between items-center">
            <h3 class="font-bold text-white">Status Penarikan (Disbursement)</h3>
            <span class="text-xs text-gray-500">Menampilkan 20 data penarikan terbaru</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-black/50 text-gray-400 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold">Bank / Account</th>
                        <th class="px-6 py-4 font-semibold">External ID</th>
                        <th class="px-6 py-4 font-semibold text-right">Amount</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold">Created</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-sm">
                    <?php if (empty($disbursements)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic">
                                Tidak ada data penarikan yang ditemukan.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($disbursements as $wd): ?>
                            <tr class="hover:bg-white/5 transition">
                                <td class="px-6 py-4">
                                    <p class="text-white font-bold"><?= esc($wd['bank_code']) ?></p>
                                    <p class="text-gray-500 text-xs"><?= esc($wd['account_number']) ?></p>
                                </td>
                                <td class="px-6 py-4 text-xs font-mono text-gray-400">
                                    <?= esc($wd['external_id']) ?>
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-red-400">
                                    Rp <?= number_format($wd['amount'], 0, ',', '.') ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <?php
                                    $wdStatusColors = [
                                        'COMPLETED' => 'bg-accent/20 text-accent',
                                        'PENDING' => 'bg-yellow-500/20 text-yellow-400',
                                        'FAILED' => 'bg-red-500/20 text-red-400',
                                    ];
                                    $wdColor = $wdStatusColors[$wd['status']] ?? 'bg-gray-500/20 text-gray-400';
                                    ?>
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase <?= $wdColor ?>">
                                        <?= esc($wd['status']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-400 text-xs">
                                    <?= date('d M Y, H:i', strtotime($wd['created'])) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Report Modal -->
<div id="reportModal" class="fixed inset-0 bg-black/80 z-[100] hidden items-center justify-center p-4 backdrop-blur-sm">
    <div class="bg-[#111] border border-white/20 rounded-2xl p-6 w-full max-w-md shadow-2xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-white">Request Report</h3>
            <button onclick="closeReportModal()" class="text-gray-500 hover:text-white"><i class="fas fa-times"></i></button>
        </div>
        <form action="<?= base_url('keuangan/xendit/report') ?>" method="POST">
            <?= csrf_field() ?>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Tipe Laporan</label>
                    <select name="type" class="w-full bg-black border border-white/20 rounded-xl px-4 py-2.5 focus:border-accent focus:outline-none text-white">
                        <option value="TRANSACTION">Transaction Report</option>
                        <option value="BALANCE_HISTORY">Balance History Report</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Dari</label>
                        <input type="date" name="from" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-2.5 focus:border-accent focus:outline-none text-white [color-scheme:dark]">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Sampai</label>
                        <input type="date" name="to" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-2.5 focus:border-accent focus:outline-none text-white [color-scheme:dark]">
                    </div>
                </div>
                <p class="text-[10px] text-gray-500 bg-blue-500/5 p-3 rounded-lg border border-blue-500/20 italic">
                    <i class="fas fa-info-circle mr-1"></i> Xendit akan memproses laporan Anda. Cek dashboard Xendit untuk mengunduh jika sudah siap.
                </p>
                <button type="submit" class="w-full py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition mt-4">
                    Generate Report
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openReportModal() {
        document.getElementById('reportModal').classList.remove('hidden');
        document.getElementById('reportModal').classList.add('flex');
    }
    function closeReportModal() {
        document.getElementById('reportModal').classList.remove('flex');
        document.getElementById('reportModal').classList.add('hidden');
    }

    function switchTab(tab) {
        // Sections
        const sectionTransactions = document.getElementById('section-transactions');
        const sectionWithdrawals = document.getElementById('section-withdrawals');
        
        // Buttons
        const btnTransactions = document.getElementById('tab-transactions');
        const btnWithdrawals = document.getElementById('tab-withdrawals');

        if (tab === 'transactions') {
            sectionTransactions.classList.remove('hidden');
            sectionWithdrawals.classList.add('hidden');
            
            btnTransactions.classList.add('border-accent', 'text-accent');
            btnTransactions.classList.remove('border-transparent', 'text-gray-500');
            
            btnWithdrawals.classList.remove('border-accent', 'text-accent');
            btnWithdrawals.classList.add('border-transparent', 'text-gray-500');
        } else {
            sectionTransactions.classList.add('hidden');
            sectionWithdrawals.classList.remove('hidden');
            
            btnWithdrawals.classList.add('border-accent', 'text-accent');
            btnWithdrawals.classList.remove('border-transparent', 'text-gray-500');
            
            btnTransactions.classList.remove('border-accent', 'text-accent');
            btnTransactions.classList.add('border-transparent', 'text-gray-500');
        }
    }
</script>
<?= $this->endSection() ?>
