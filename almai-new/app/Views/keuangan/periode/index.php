<?= $this->extend('keuangan/layouts/main') ?>
<?= $this->section('content') ?>

<style>
    .month-card { transition: all 0.2s ease; }
    .month-card:hover { transform: translateY(-2px); }

    .status-open {
        border-color: rgba(51,232,24,0.3);
        background: rgba(51,232,24,0.04);
    }
    .status-open .status-dot { background: #33e818; }
    .status-open .status-text { color: #33e818; }

    .status-closed {
        border-color: rgba(239,68,68,0.3);
        background: rgba(239,68,68,0.04);
    }
    .status-closed .status-dot { background: #ef4444; }
    .status-closed .status-text { color: #ef4444; }

    .status-empty {
        border-color: rgba(255,255,255,0.06);
        background: rgba(255,255,255,0.01);
        opacity: 0.5;
    }
    .status-empty .status-dot { background: #374151; }
    .status-empty .status-text { color: #6b7280; }

    @keyframes lockBounce {
        0%,100% { transform: scale(1) rotate(0deg); }
        25% { transform: scale(1.2) rotate(-5deg); }
        75% { transform: scale(1.2) rotate(5deg); }
    }
    .lock-anim:hover i { animation: lockBounce 0.4s ease; }
</style>

<!-- Header -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-black text-white">Periode Akuntansi</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola pembukaan dan penutupan buku per periode bulanan. Periode tertutup tidak dapat dimodifikasi.</p>
    </div>
    <!-- Year Navigator -->
    <div class="flex items-center gap-2">
        <a href="?tahun=<?= $tahun - 1 ?>" class="w-9 h-9 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center hover:bg-white/10 transition">
            <i class="fas fa-chevron-left text-gray-400 text-xs"></i>
        </a>
        <div class="px-6 py-2 bg-[#111] border border-white/10 rounded-xl text-center min-w-24">
            <span class="font-black text-xl text-white"><?= $tahun ?></span>
        </div>
        <a href="?tahun=<?= $tahun + 1 ?>" class="w-9 h-9 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center hover:bg-white/10 transition <?= $tahun >= $currentYear ? 'opacity-30 pointer-events-none' : '' ?>">
            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
        </a>
    </div>
</div>

<!-- Flash Messages -->
<?php if (session()->getFlashdata('success')): ?>
<div class="mb-6 p-4 bg-accent/10 border border-accent/20 text-accent rounded-xl flex items-center gap-3">
    <i class="fas fa-check-circle"></i> <?= session()->getFlashdata('success') ?>
</div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
<div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-xl flex items-center gap-3">
    <i class="fas fa-exclamation-circle"></i> <?= session()->getFlashdata('error') ?>
</div>
<?php endif; ?>

<!-- Summary Stats -->
<?php
$totalClosed = 0;
$totalOpen   = 0;
$totalTrx    = 0;
for ($m = 1; $m <= 12; $m++) {
    $p = $periodMap[$m] ?? null;
    $hasJurnal = !empty($jurnalMap[$m]);
    if ($p && $p['is_closed']) $totalClosed++;
    elseif ($hasJurnal) $totalOpen++;
    if (!empty($jurnalMap[$m])) $totalTrx += $jurnalMap[$m]['total_transaksi'];
}
?>
<div class="grid grid-cols-3 gap-4 mb-8">
    <div class="bg-[#111] border border-white/8 rounded-2xl p-5 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-accent/10 flex items-center justify-center">
            <i class="fas fa-lock-open text-accent"></i>
        </div>
        <div>
            <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest">Periode Terbuka</p>
            <p class="text-2xl font-black text-white"><?= $totalOpen ?></p>
        </div>
    </div>
    <div class="bg-[#111] border border-red-500/15 rounded-2xl p-5 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-red-500/10 flex items-center justify-center">
            <i class="fas fa-lock text-red-400"></i>
        </div>
        <div>
            <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest">Periode Tertutup</p>
            <p class="text-2xl font-black text-white"><?= $totalClosed ?></p>
        </div>
    </div>
    <div class="bg-[#111] border border-white/8 rounded-2xl p-5 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-indigo-500/10 flex items-center justify-center">
            <i class="fas fa-receipt text-indigo-400"></i>
        </div>
        <div>
            <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest">Total Transaksi</p>
            <p class="text-2xl font-black text-white"><?= number_format($totalTrx) ?></p>
        </div>
    </div>
</div>

<!-- Month Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
    <?php for ($m = 1; $m <= 12; $m++):
        $period    = $periodMap[$m] ?? null;
        $isClosed  = $period && (bool)$period['is_closed'];
        $jurnalInfo = $jurnalMap[$m] ?? null;
        $hasJurnal = !empty($jurnalInfo);
        $isFuture  = ($m > (int)date('m') && $tahun == $currentYear) || $tahun > $currentYear;

        if ($isClosed) {
            $cardClass = 'status-closed';
        } elseif ($hasJurnal) {
            $cardClass = 'status-open';
        } else {
            $cardClass = 'status-empty';
        }
        $isCurrent = ($m == (int)date('m') && $tahun == $currentYear);
    ?>
    <div class="month-card bg-[#0d0d0d] border rounded-2xl p-5 <?= $cardClass ?> relative overflow-hidden">
        <?php if ($isCurrent): ?>
        <div class="absolute top-3 right-3">
            <span class="text-[9px] bg-accent/20 text-accent border border-accent/30 px-1.5 py-0.5 rounded-full font-bold uppercase tracking-wider">Bulan Ini</span>
        </div>
        <?php endif; ?>

        <!-- Month Name & Status -->
        <div class="flex items-start justify-between mb-4">
            <div>
                <p class="text-xs font-bold text-gray-600 uppercase tracking-widest"><?= $tahun ?></p>
                <h3 class="text-lg font-black text-white mt-0.5"><?= $bulanNames[$m] ?></h3>
            </div>
            <div class="flex items-center gap-1.5 mt-1">
                <div class="status-dot w-2 h-2 rounded-full"></div>
                <span class="status-text text-[10px] font-bold uppercase tracking-wider">
                    <?= $isClosed ? 'Tertutup' : ($hasJurnal ? 'Terbuka' : 'Kosong') ?>
                </span>
            </div>
        </div>

        <!-- Jurnal Stats -->
        <div class="space-y-1.5 mb-5">
            <div class="flex justify-between text-xs">
                <span class="text-gray-600">Transaksi Jurnal</span>
                <span class="font-bold text-gray-300"><?= $hasJurnal ? number_format($jurnalInfo['total_transaksi']) : '—' ?></span>
            </div>
            <div class="flex justify-between text-xs">
                <span class="text-gray-600">Total Debit</span>
                <span class="font-bold text-gray-300"><?= $hasJurnal ? 'Rp '.number_format($jurnalInfo['total_debit'], 0, ',', '.') : '—' ?></span>
            </div>
            <?php if ($isClosed && $period['closed_at']): ?>
            <div class="flex justify-between text-xs">
                <span class="text-gray-600">Ditutup pada</span>
                <span class="font-bold text-red-400"><?= date('d/m/Y H:i', strtotime($period['closed_at'])) ?></span>
            </div>
            <?php endif; ?>
        </div>

        <!-- Action Button -->
        <?php if (!$isFuture): ?>
            <?php if ($isClosed): ?>
                <!-- Buka Kembali -->
                <button onclick="confirmBuka(<?= $m ?>, <?= $tahun ?>, '<?= $bulanNames[$m] ?>')"
                    class="lock-anim w-full py-2 bg-white/5 hover:bg-red-500/10 border border-white/10 hover:border-red-500/30 text-gray-400 hover:text-red-400 text-xs font-bold rounded-xl transition flex items-center justify-center gap-2">
                    <i class="fas fa-lock-open"></i> Buka Kembali
                </button>
            <?php elseif ($hasJurnal): ?>
                <!-- Tutup Buku -->
                <button onclick="confirmTutup(<?= $m ?>, <?= $tahun ?>, '<?= $bulanNames[$m] ?>', <?= $jurnalInfo['total_transaksi'] ?>)"
                    class="lock-anim w-full py-2 bg-red-500/10 hover:bg-red-500/20 border border-red-500/20 hover:border-red-500/40 text-red-400 text-xs font-bold rounded-xl transition flex items-center justify-center gap-2">
                    <i class="fas fa-lock"></i> Tutup Buku
                </button>
            <?php else: ?>
                <div class="w-full py-2 text-center text-gray-700 text-xs font-bold">Tidak ada transaksi</div>
            <?php endif; ?>
        <?php else: ?>
            <div class="w-full py-2 text-center text-gray-700 text-xs font-bold italic">Periode mendatang</div>
        <?php endif; ?>
    </div>
    <?php endfor; ?>
</div>

<!-- Warning Banner -->
<div class="mt-8 p-5 bg-amber-500/5 border border-amber-500/20 rounded-2xl flex items-start gap-4">
    <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center flex-shrink-0">
        <i class="fas fa-exclamation-triangle text-amber-400"></i>
    </div>
    <div>
        <p class="font-bold text-amber-400 text-sm mb-1">Perhatian — Efek Tutup Buku</p>
        <ul class="text-xs text-gray-500 space-y-1 list-disc list-inside">
            <li>Setelah periode ditutup, tidak ada jurnal baru yang dapat dibuat atau dimodifikasi pada bulan tersebut.</li>
            <li>Tutup Buku <strong>tidak menghapus</strong> data — hanya mengunci periode dari perubahan.</li>
            <li>Periode dapat dibuka kembali oleh akuntan kapan saja jika diperlukan koreksi.</li>
            <li>Pastikan semua jurnal penyesuaian (adjustment) sudah dicatat <strong>sebelum</strong> menutup buku.</li>
        </ul>
    </div>
</div>

<!-- Hidden Forms -->
<form id="formTutup" method="POST" action="<?= base_url('keuangan/tutup-buku') ?>" class="hidden">
    <?= csrf_field() ?>
    <input type="hidden" name="bulan" id="inputBulan">
    <input type="hidden" name="tahun" id="inputTahun">
</form>
<form id="formBuka" method="POST" action="<?= base_url('keuangan/buka-buku') ?>" class="hidden">
    <?= csrf_field() ?>
    <input type="hidden" name="bulan" id="inputBulanBuka">
    <input type="hidden" name="tahun" id="inputTahunBuka">
</form>

<!-- Confirm Modal -->
<div id="confirmModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
    <div class="bg-[#111] border border-white/10 rounded-2xl w-full max-w-md p-6 shadow-2xl">
        <div id="modalIcon" class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-5"></div>
        <h3 id="modalTitle" class="text-xl font-black text-white text-center mb-2"></h3>
        <p id="modalBody" class="text-sm text-gray-400 text-center mb-6 leading-relaxed"></p>
        <div class="flex gap-3">
            <button onclick="closeModal()" class="flex-1 py-3 bg-white/5 hover:bg-white/10 text-white font-bold rounded-xl transition border border-white/10">
                Batal
            </button>
            <button id="modalConfirmBtn" class="flex-1 py-3 font-black rounded-xl transition" onclick="submitAction()"></button>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
<?= $this->section('scripts') ?>
<script>
let pendingAction = null;

function confirmTutup(bulan, tahun, namaBulan, totalTrx) {
    pendingAction = 'tutup';
    document.getElementById('inputBulan').value = bulan;
    document.getElementById('inputTahun').value = tahun;

    const modal = document.getElementById('confirmModal');
    document.getElementById('modalIcon').innerHTML = '<i class="fas fa-lock text-red-400 text-2xl"></i>';
    document.getElementById('modalIcon').className = 'w-14 h-14 rounded-2xl bg-red-500/10 border border-red-500/20 flex items-center justify-center mx-auto mb-5';
    document.getElementById('modalTitle').innerHTML = `Tutup Buku <span class="text-red-400">${namaBulan} ${tahun}</span>?`;
    document.getElementById('modalBody').innerHTML = `Tindakan ini akan mengunci <strong class="text-white">${totalTrx} transaksi jurnal</strong> pada periode ${namaBulan} ${tahun}.<br><br>Tidak ada entri jurnal baru yang dapat ditambahkan atau diubah setelah periode ditutup.`;
    const btn = document.getElementById('modalConfirmBtn');
    btn.className = 'flex-1 py-3 font-black rounded-xl transition bg-red-500 hover:bg-red-600 text-white';
    btn.textContent = '🔒 Ya, Tutup Buku';

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function confirmBuka(bulan, tahun, namaBulan) {
    pendingAction = 'buka';
    document.getElementById('inputBulanBuka').value = bulan;
    document.getElementById('inputTahunBuka').value = tahun;

    const modal = document.getElementById('confirmModal');
    document.getElementById('modalIcon').innerHTML = '<i class="fas fa-lock-open text-amber-400 text-2xl"></i>';
    document.getElementById('modalIcon').className = 'w-14 h-14 rounded-2xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center mx-auto mb-5';
    document.getElementById('modalTitle').innerHTML = `Buka Periode <span class="text-amber-400">${namaBulan} ${tahun}</span>?`;
    document.getElementById('modalBody').innerHTML = `Membuka kembali periode ini memungkinkan pengeditan dan penambahan jurnal. Pastikan ada alasan yang valid untuk membuka periode yang sudah ditutup.`;
    const btn = document.getElementById('modalConfirmBtn');
    btn.className = 'flex-1 py-3 font-black rounded-xl transition bg-amber-500 hover:bg-amber-600 text-black';
    btn.textContent = '🔓 Ya, Buka Kembali';

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal() {
    const modal = document.getElementById('confirmModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    pendingAction = null;
}

function submitAction() {
    if (pendingAction === 'tutup') {
        document.getElementById('formTutup').submit();
    } else if (pendingAction === 'buka') {
        document.getElementById('formBuka').submit();
    }
}

// Close modal on backdrop click
document.getElementById('confirmModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
<?= $this->endSection() ?>
