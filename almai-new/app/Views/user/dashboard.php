<?= $this->extend('user/partials/layout') ?>

<?= $this->section('content') ?>
<?php $isPro = !empty($currentUser['level_id']) && (int)$currentUser['level_id'] === \App\Models\LevelModel::LEVEL_PRO; ?>

<!-- WELCOME DIALOG (ALMAI REGISTRATION SYSTEM v2.0 FINAL RELEASE) -->
<?php if (session()->getFlashdata('welcome_modal') || (session()->get('must_change_password') && service('request')->getGet('welcome') == '1')): ?>
<div id="welcomeModal" class="fixed inset-0 bg-black/90 backdrop-blur-md z-50 flex items-center justify-center p-4">
    <div class="bg-slate-900 border border-emerald-500/40 w-full max-w-sm rounded-2xl p-6 text-center relative shadow-2xl space-y-4">
        <div class="text-4xl">🎉</div>
        <h3 class="text-lg font-extrabold text-white">Selamat datang di ALMAI.</h3>
        <p class="text-xs text-gray-300 leading-relaxed">
            Registrasi Anda berhasil.<br>
            Demi keamanan akun, silakan segera ubah Password Anda.
        </p>

        <div class="flex flex-col gap-2 pt-2">
            <a href="<?= base_url('user/profile') ?>?change_password=1" class="w-full py-3 bg-accent text-slate-950 font-extrabold rounded-xl text-xs hover:bg-white transition shadow-lg">
                Ubah Password
            </a>
            <button type="button" onclick="document.getElementById('welcomeModal').classList.add('hidden')" class="w-full py-2.5 bg-white/10 text-gray-400 hover:text-white rounded-xl text-xs font-bold transition">
                Nanti
            </button>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- STICKY CHANGE PASSWORD REMINDER BANNER -->
<?php if (session()->get('must_change_password')): ?>
<div class="bg-amber-500/20 border border-amber-500/40 rounded-xl p-3.5 mb-4 flex items-center justify-between text-xs text-amber-300">
    <div class="flex items-center gap-2">
        <i class="fas fa-key text-amber-400 text-sm"></i>
        <span><strong>Pengingat Keamanan:</strong> Silakan segera ubah kata sandi default Anda.</span>
    </div>
    <a href="<?= base_url('user/profile') ?>?change_password=1" class="px-3 py-1.5 bg-amber-500 text-slate-950 font-bold rounded-lg hover:bg-amber-400 transition text-xs shadow-md">
        Ubah Password
    </a>
</div>
<?php endif; ?>

<!-- Welcome Text -->
<div class="mb-4">
    <p class="text-gray-400 text-xs mb-0.5">Selamat datang kembali,</p>
    <h1 class="text-lg font-bold text-white"><?= esc(session()->get('userName')) ?></h1>
</div>

<?php if (empty($currentUser['email'])): ?>
<div class="bg-yellow-500/10 border border-yellow-500/20 rounded-xl p-3 mb-4 flex items-start gap-3 relative" id="emailOptionalAlert">
    <div class="mt-0.5"><i class="fas fa-exclamation-triangle text-yellow-500 text-sm"></i></div>
    <div class="flex-1">
        <h4 class="text-sm font-semibold text-yellow-500">Email Belum Ditambahkan</h4>
        <p class="text-xs text-gray-400 mt-0.5">Tambahkan email Anda di profil untuk memudahkan pemulihan akun dan pembayaran.</p>
        <a href="<?= base_url('user/profile/edit') ?>" class="text-xs text-yellow-500 hover:text-yellow-400 underline mt-1 inline-block">Update Profil</a>
    </div>
    <button type="button" class="text-gray-500 hover:text-white" onclick="document.getElementById('emailOptionalAlert').remove()">
        <i class="fas fa-times"></i>
    </button>
</div>
<?php endif; ?>

<!-- Main Wallet Card -->
<div class="relative w-full bg-gradient-to-br from-[#0a3d1f] via-[#0c4a24] to-[#0a3d1f] rounded-2xl p-4 mb-4 overflow-hidden shadow-xl border border-[#1a5c32]">
    <div class="absolute top-0 right-0 w-full h-full opacity-10 pointer-events-none">
        <div class="absolute top-2 right-2 w-24 h-24 bg-accent/20 rounded-full blur-2xl"></div>
        <div class="absolute bottom-0 right-0 w-32 h-32 bg-accent/10 rounded-full blur-2xl"></div>
    </div>

    <div class="absolute top-0 right-0 opacity-20 pointer-events-none">
        <img src="<?= base_url('images/dompet.png') ?>" alt="Wallet" class="w-32 h-32 object-contain">
    </div>

    <div class="relative z-10 mb-4">
        <div class="flex items-center gap-2 mb-1">
            <span class="text-gray-300 text-xs">Total Saldo</span>
            <button onclick="toggleBalanceVisibility()" class="text-gray-400 hover:text-white transition">
                <i class="fas fa-eye text-xs" id="balanceIcon"></i>
            </button>
        </div>
        <div class="flex items-center gap-2">
            <h2 class="text-2xl font-bold text-white tracking-tight" id="balanceDisplay">
                Rp <?= number_format($stats['balance'] ?? 0, 0, ',', '.') ?>
            </h2>
            <h2 class="text-2xl font-bold text-white tracking-tight hidden" id="balanceHidden">Rp ••••••</h2>
        </div>
    </div>

    <div class="relative z-10 grid grid-cols-3 md:flex md:flex-wrap gap-2 mb-4">
        <a href="<?= base_url('user/withdraw') ?>" class="flex items-center justify-center gap-1.5 bg-accent hover:bg-accent/90 text-black font-bold text-xs py-2 md:px-4 rounded-lg transition shadow-lg">
            <i class="fas fa-arrow-up text-sm"></i>
            <span>Tarik Dana</span>
        </a>
        <button onclick="performCheckin()" id="btnDailyCheckin" <?= !$canCheckin ? 'disabled' : '' ?> class="flex items-center justify-center gap-1.5 <?= $canCheckin ? 'bg-white/10 hover:bg-white/20' : 'bg-white/5 cursor-not-allowed opacity-50' ?> text-white font-bold text-xs py-2 md:px-4 rounded-lg transition border border-white/20">
            <i class="fas fa-plus text-sm"></i>
            <span><?= $canCheckin ? 'Checkin' : 'Sudah Checkin' ?></span>
        </button>
        <a href="<?= base_url('user/dashboard/absen') ?>" class="relative flex items-center justify-center gap-1.5 bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 font-bold text-xs py-2 md:px-4 rounded-lg transition border border-blue-500/30">
            <i class="fas fa-calendar-check text-sm"></i>
            <span>Event</span>
        </a>
    </div>
</div>
<?= $this->endSection() ?>
