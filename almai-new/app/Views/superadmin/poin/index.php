<?php 
$this->setVar('pageTitle', 'Almai Poin Dashboard');
$this->setVar('pageSubtitle', 'Statistik Ekonomi & Distribusi Poin Digital');
?>
<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Almai Poin Dashboard Stats -->
<div class="mb-8">
    <div class="bg-[#050505] border border-white/10 rounded-2xl overflow-hidden shadow-2xl overflow-x-auto">
        <table class="w-full border-collapse">
            <!-- Header Stats -->
            <tr>
                <td class="w-1/2 p-6 md:p-8 border-b border-r border-white/10 align-middle relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent transition-opacity opacity-0 group-hover:opacity-100"></div>
                    <div class="flex flex-col items-center justify-center text-center relative z-10">
                         <div class="flex items-center gap-3 mb-2">
                            <span class="text-xs md:text-sm font-bold text-gray-400 uppercase tracking-widest bg-black/50 px-3 py-1 rounded-full border border-white/5 backdrop-blur-sm">Sisa Total Poin (Pool)</span>
                        </div>
                        <div class="flex items-baseline gap-2 mb-3">
                            <span class="text-4xl md:text-6xl font-black text-white drop-shadow-sm"><?= number_format((($maxSupply - $totalActive) / $maxSupply) * 100, 2) ?>%</span>
                        </div>
                        <div class="flex items-center justify-center gap-3 bg-emerald-500/10 px-6 py-3 rounded-2xl border border-emerald-500/20 shadow-[0_0_20px_rgba(16,185,129,0.1)]">
                            <div class="flex items-center justify-center text-[#FFD700] drop-shadow-[0_0_8px_rgba(255,215,0,0.6)] text-2xl md:text-3xl">
                                <i class="fas fa-coins"></i>
                            </div>
                            <div class="text-xl md:text-3xl font-black text-white font-mono tracking-tight"><?= number_format($maxSupply - $totalActive) ?> <span class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Limit Poin</span></div>
                        </div>
                    </div>
                </td>
                <td class="w-1/2 p-6 md:p-8 border-b border-white/10 align-middle relative overflow-hidden group">
                     <div class="absolute inset-0 bg-gradient-to-br from-accent/5 to-transparent transition-opacity opacity-0 group-hover:opacity-100"></div>
                     <div class="flex flex-col items-center justify-center text-center relative z-10">
                        <div class="flex items-center gap-3 mb-2">
                             <span class="text-xs md:text-sm font-bold text-gray-400 uppercase tracking-widest bg-black/50 px-3 py-1 rounded-full border border-white/5 backdrop-blur-sm">Poin Beredar (Circulating)</span>
                        </div>
                        <div class="flex items-baseline gap-2 mb-3">
                            <span class="text-4xl md:text-6xl font-black text-accent drop-shadow-sm"><?= number_format(($totalActive / $maxSupply) * 100, 3) ?><span class="text-2xl md:text-4xl ml-1">%</span></span>
                        </div>
                        <div class="flex items-center justify-center gap-3 bg-accent/10 px-6 py-3 rounded-2xl border border-accent/20 shadow-[0_0_20px_rgba(255,215,0,0.1)]">
                            <div class="flex items-center justify-center text-[#FFD700] drop-shadow-[0_0_8px_rgba(255,215,0,0.6)] text-2xl md:text-3xl">
                                <i class="fas fa-coins"></i>
                            </div>
                            <div class="text-xl md:text-3xl font-black text-white font-mono tracking-tight"><?= number_format($totalActive) ?> <span class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Almai Poin</span></div>
                        </div>
                    </div>
                </td>
            </tr>
            
            <!-- Detailed Stats Table (Left: Usage/Redeem, Right: Earn/Bonus) -->
            <tr>
                <td class="border-r border-white/10 p-0">
                    <table class="w-full">
                        <tr class="border-b border-white/5">
                            <td class="p-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-r border-white/5 w-[65%]">Total Redeem</td>
                            <td class="p-4 text-sm font-black text-red-400 text-right">: <?= number_format($stats['redeem']) ?></td>
                        </tr>
                        <tr class="border-b border-white/5">
                            <td class="p-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-r border-white/5">Artikel</td>
                            <td class="p-4 text-sm font-black text-gray-300 text-right">: <?= number_format($stats['artikel']) ?></td>
                        </tr>
                        <tr class="border-b border-white/5">
                            <td class="p-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-r border-white/5">Layanan</td>
                            <td class="p-4 text-sm font-black text-gray-300 text-right">: <?= number_format($stats['layanan_usage']) ?></td>
                        </tr>
                        <tr class="border-b border-white/5">
                            <td class="p-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-r border-white/5">Merchandise</td>
                            <td class="p-4 text-sm font-black text-gray-300 text-right">: <?= number_format($stats['merchandise']) ?></td>
                        </tr>
                        <tr class="">
                            <td class="p-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-r border-white/5">Berbagi Poin</td>
                            <td class="p-4 text-sm font-black text-gray-300 text-right">: <?= number_format($stats['share_out']) ?></td>
                        </tr>
                    </table>
                </td>
                <td class="p-0">
                    <table class="w-full">
                         <tr class="border-b border-white/5">
                            <td class="p-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-r border-white/5 w-[65%]">Total Bonus Poin</td>
                            <td class="p-4 text-sm font-black text-emerald-400 text-right">: <?= number_format($stats['bonus_total']) ?></td>
                        </tr>
                        <tr class="border-b border-white/5">
                            <td class="p-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-r border-white/5">Poin Register</td>
                            <td class="p-4 text-sm font-black text-gray-300 text-right">: <?= number_format($stats['register']) ?></td>
                        </tr>
                        <tr class="border-b border-white/5">
                            <td class="p-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-r border-white/5">Poin Refferal</td>
                            <td class="p-4 text-sm font-black text-gray-300 text-right">: <?= number_format($stats['referral']) ?></td>
                        </tr>
                        <tr class="border-b border-white/5">
                            <td class="p-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-r border-white/5">Poin Layanan</td>
                            <td class="p-4 text-sm font-black text-gray-300 text-right">: <?= number_format($stats['layanan_earn']) ?></td>
                        </tr>
                        <tr class="">
                            <td class="p-4 text-xs font-bold text-gray-400 uppercase tracking-widest border-r border-white/5">Terima Poin</td>
                            <td class="p-4 text-sm font-black text-gray-300 text-right">: <?= number_format($stats['share_in']) ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div>

<!-- Extra Info (Keep relevant existing metrics) -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="bg-[#111] border border-emerald-500/20 rounded-xl p-4 flex items-center justify-between">
        <div class="flex items-center gap-4">
             <div class="w-10 h-10 bg-emerald-500/10 rounded-lg flex items-center justify-center text-emerald-500">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div>
                <p class="text-[10px] text-gray-500 uppercase font-black">Total Saldo (IDR)</p>
                <p class="text-xl font-black text-white">Rp <?= number_format($totalRupiahBalance, 0, ',', '.') ?></p>
            </div>
        </div>
        <div class="text-emerald-500 text-xs font-bold bg-emerald-500/10 px-3 py-1 rounded-full">Active</div>
    </div>
    <div class="bg-[#111] border border-blue-500/20 rounded-xl p-4 flex items-center justify-between">
         <div class="flex items-center gap-4">
             <div class="w-10 h-10 bg-blue-500/10 rounded-lg flex items-center justify-center text-blue-500">
                <i class="fas fa-history"></i>
            </div>
            <div>
                <p class="text-[10px] text-gray-500 uppercase font-black">Total Transaksi Poin</p>
                <p class="text-xl font-black text-white"><?= number_format($totalDistributed + $totalRedeemed) ?></p>
            </div>
        </div>
        <div class="text-blue-500 text-xs font-bold bg-blue-500/10 px-3 py-1 rounded-full">All Time</div>
    </div>
</div>

<!-- Filters & Actions -->
<div class="bg-[#111] border border-white/10 rounded-2xl p-4 mb-6 shadow-xl">
    <form action="<?= base_url('superadmin/poin') ?>" method="get" class="flex flex-col gap-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <input type="text" name="search" value="<?= esc($currentSearch) ?>" placeholder="Cari nama user atau email..." class="w-full px-4 py-2.5 pl-10 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm transition">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-xs"></i>
            </div>
            <div class="flex flex-wrap gap-2">
                <button type="submit" class="px-6 py-2.5 bg-white/5 border border-white/10 text-white font-bold rounded-xl hover:bg-white/10 transition flex items-center justify-center gap-2">
                    <i class="fas fa-search"></i> Cari
                </button>
                <a href="<?= base_url('superadmin/poin/history') ?>" class="px-6 py-2.5 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center justify-center gap-2">
                    <i class="fas fa-history"></i> Riwayat Poin
                </a>
                <button type="button" onclick="confirmSync()" class="px-6 py-2.5 bg-blue-500/10 text-blue-500 border border-blue-500/20 rounded-xl font-bold text-sm hover:bg-blue-500 hover:text-white transition flex items-center justify-center gap-2">
                    <i class="fas fa-sync"></i> Sinkronisasi Poin
                </button>
            </div>
        </div>
    </form>
</div>

<!-- User Poin Table -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden shadow-2xl">
    <div class="overflow-x-auto scrollbar-hide">
        <table class="w-full min-w-[600px]">
            <thead class="bg-black/80">
                <tr>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 w-12 text-center uppercase tracking-widest">No</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">User / Anggota</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest text-center">Saldo Rupiah</th>
                    <th class="text-left px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest text-center">Saldo Poin</th>
                    <th class="text-center px-4 py-4 text-xs font-semibold text-gray-500 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($userBalances)): ?>
                <tr>
                    <td colspan="4" class="px-6 py-20 text-center text-gray-500">
                        <div class="flex flex-col items-center gap-4">
                            <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center">
                                <i class="fas fa-users text-2xl"></i>
                            </div>
                            <p class="text-sm font-medium">Tidak ada user ditemukan</p>
                        </div>
                    </td>
                </tr>
                <?php else: ?>
                <?php $no = 1; foreach ($userBalances as $user): ?>
                <tr class="hover:bg-white/5 transition group">
                    <td class="px-4 py-4 text-gray-500 text-sm text-center font-mono"><?= sprintf('%02d', $no++) ?></td>
                    <td class="px-4 py-4">
                        <div class="min-w-0">
                            <p class="font-bold text-sm text-gray-200 group-hover:text-accent transition"><?= esc($user['name'] ?? 'Unknown') ?></p>
                            <p class="text-[10px] text-gray-500 truncate mt-0.5 opacity-70"><?= esc($user['email'] ?? '-') ?></p>
                        </div>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="text-emerald-400 font-bold text-sm">
                            Rp <?= number_format($user['rupiah_balance'] ?? 0, 0, ',', '.') ?>
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="px-4 py-1.5 bg-accent/10 border border-accent/20 text-accent font-black rounded-lg text-sm">
                            <?= number_format($user['balance']) ?> Poin
                        </span>
                    </td>
                    <td class="px-4 py-4">
                        <div class="flex items-center justify-center">
                            <a href="<?= base_url('superadmin/poin/user/' . $user['id']) ?>" class="flex items-center gap-2 px-4 py-2 bg-blue-500/10 text-blue-400 rounded-xl hover:bg-blue-500 hover:text-white transition text-xs font-bold">
                                <i class="fas fa-eye"></i> Detail Poin
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if ($pager && $pager->getPageCount() > 1): ?>
        <div class="px-4 py-4 border-t border-white/5 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-sm text-gray-500">
                Menampilkan halaman <?= $pager->getCurrentPage() ?> dari <?= $pager->getPageCount() ?>
            </p>
            <div class="flex items-center gap-1 flex-wrap justify-center">
                <?= $pager->links('default', 'admin_pagination') ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Sync Modal -->
<div id="syncModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/90 backdrop-blur-sm p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 md:p-8 max-w-sm w-full transform transition-all shadow-2xl text-center">
        <div class="w-16 h-16 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-4 text-blue-500 text-2xl animate-pulse">
            <i class="fas fa-sync"></i>
        </div>
        <h3 class="text-xl font-black mb-2 uppercase tracking-wide text-blue-500">Sinkronisasi Poin?</h3>
        <div id="syncFormContainer">
            <p class="text-gray-500 text-xs mb-6">Tindakan ini akan mengkalkulasi ulang poin pendaftaran dan upgrade PRO ke sistem 8-level. Poin yang telah dibeli/dipakai TIDAK akan terpengaruh.</p>
            <div class="flex gap-3">
                <button type="button" onclick="closeSyncModal()" class="flex-1 py-3.5 border border-white/10 rounded-2xl hover:bg-white/5 transition text-xs font-black tracking-widest text-gray-400">BATAL</button>
                <button type="button" onclick="startSync()" class="flex-1 py-3.5 bg-blue-600 text-white font-black rounded-2xl hover:bg-blue-700 transition text-xs tracking-widest shadow-lg shadow-blue-600/30">SINKRONISASI</button>
            </div>
        </div>
        
        <div id="syncProgressContainer" class="hidden">
            <p class="text-gray-400 text-xs mb-4 font-bold" id="syncStatusText">Memulai sinkronisasi...</p>
            <div class="w-full bg-black/50 border border-white/10 rounded-full h-4 mb-2 overflow-hidden">
                <div id="syncProgressBar" class="bg-blue-500 h-4 rounded-full transition-all duration-300" style="width: 0%"></div>
            </div>
            <p class="text-blue-500 text-xs font-black tracking-widest" id="syncPercentageText">0%</p>
        </div>
    </div>
</div>

<input type="hidden" id="csrf_token_hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>">

<script>
    let syncTotalUsers = 0;
    let syncProcessedUsers = 0;
    const syncLimit = 50;

    function getCsrfToken() {
        const csrfInput = document.getElementById('csrf_token_hidden');
        return csrfInput ? csrfInput.value : '';
    }

    async function startSync() {
        document.getElementById('syncFormContainer').classList.add('hidden');
        document.getElementById('syncProgressContainer').classList.remove('hidden');
        document.getElementById('syncStatusText').innerText = "Menghapus poin lama & menghitung total user...";
        
        try {
            let formData = new FormData();
            formData.append('<?= csrf_token() ?>', getCsrfToken());

            let response = await fetch('<?= base_url('superadmin/poin/sync/init') ?>', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            let result = await response.json();
            
            if (result.status !== 'success') throw new Error("Gagal inisialisasi");
            
            syncTotalUsers = result.total;
            syncProcessedUsers = 0;
            
            if (syncTotalUsers === 0) {
                await completeSync();
                return;
            }

            // 2. Process chunks
            await processSyncChunk();

        } catch (error) {
            alert("Terjadi kesalahan: " + error.message);
            location.reload();
        }
    }

    async function processSyncChunk() {
        document.getElementById('syncStatusText').innerText = `Memproses user ${syncProcessedUsers} / ${syncTotalUsers}...`;
        
        try {
            let formData = new FormData();
            formData.append('offset', syncProcessedUsers);
            formData.append('limit', syncLimit);
            formData.append('<?= csrf_token() ?>', getCsrfToken());

            let response = await fetch('<?= base_url('superadmin/poin/sync/process') ?>', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            let result = await response.json();

            if (result.status !== 'success') throw new Error(result.message || "Gagal memproses batch");

            syncProcessedUsers += syncLimit;
            if (syncProcessedUsers > syncTotalUsers) syncProcessedUsers = syncTotalUsers;

            let percentage = Math.round((syncProcessedUsers / syncTotalUsers) * 100);
            document.getElementById('syncProgressBar').style.width = percentage + '%';
            document.getElementById('syncPercentageText').innerText = percentage + '%';

            if (syncProcessedUsers < syncTotalUsers) {
                setTimeout(processSyncChunk, 100); // small delay to prevent browser freeze
            } else {
                await completeSync();
            }

        } catch (error) {
            alert("Terjadi kesalahan saat memproses data: " + error.message);
            location.reload();
        }
    }

    async function completeSync() {
        document.getElementById('syncStatusText').innerText = "Menyelesaikan proses...";
        try {
            let formData = new FormData();
            formData.append('<?= csrf_token() ?>', getCsrfToken());

            await fetch('<?= base_url('superadmin/poin/sync/complete') ?>', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            location.reload();
        } catch (error) {
            alert("Gagal menyelesaikan sinkronisasi");
            location.reload();
        }
    }

    function confirmSync() {
        const modal = document.getElementById('syncModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeSyncModal() {
        const modal = document.getElementById('syncModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    window.onclick = function(e) {
        if (e.target === document.getElementById('syncModal')) closeSyncModal();
    }
</script>

<?= $this->endSection() ?>
