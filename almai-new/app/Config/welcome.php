<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Selamat Datang di ALMAI') ?></title>
    <link rel="stylesheet" href="<?= base_url('css/tailwind.min.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #030712; color: #f9fafb; font-family: 'Montserrat', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-x-hidden">

    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-emerald-900/20 via-slate-950 to-black pointer-events-none"></div>

    <div class="w-full max-w-xl bg-slate-900/90 border border-emerald-500/30 rounded-3xl p-8 md:p-10 text-center relative z-10 shadow-2xl backdrop-blur-xl">
        <!-- Success Icon -->
        <div class="w-20 h-20 bg-emerald-500/10 border border-emerald-500/40 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner shadow-emerald-500/30 animate-bounce">
            <i class="fas fa-check-circle text-4xl text-emerald-400"></i>
        </div>

        <h1 class="text-2xl md:text-3xl font-extrabold text-white mb-2">🎉 Selamat Datang di ALMAI!</h1>
        <p class="text-sm text-slate-300 mb-8">Pendaftaran akun Anda telah **terverifikasi 100% via WhatsApp**.</p>

        <!-- Card Profile Summary -->
        <div class="bg-slate-950/80 border border-slate-800 rounded-2xl p-6 text-left space-y-4 mb-8">
            <div class="flex items-center justify-between border-b border-slate-800/80 pb-3">
                <span class="text-xs text-slate-400 font-medium">Nama Lengkap</span>
                <span class="text-sm font-bold text-white"><?= esc($userName) ?></span>
            </div>
            <div class="flex items-center justify-between border-b border-slate-800/80 pb-3">
                <span class="text-xs text-slate-400 font-medium">Email Terdaftar</span>
                <span class="text-sm font-semibold text-emerald-400"><?= esc($userEmail) ?></span>
            </div>
            <div class="flex items-center justify-between border-b border-slate-800/80 pb-3">
                <span class="text-xs text-slate-400 font-medium">WhatsApp Terverifikasi</span>
                <span class="text-sm font-semibold text-emerald-400"><?= esc($userPhone) ?></span>
            </div>
            <div class="flex items-center justify-between border-b border-slate-800/80 pb-3">
                <span class="text-xs text-slate-400 font-medium">Affiliator / WPA Pendamping</span>
                <span class="text-sm font-semibold text-slate-200"><?= esc($affiliatorName) ?></span>
            </div>
            <div class="flex items-center justify-between pt-1">
                <span class="text-xs text-slate-400 font-medium">Bonus Registrasi</span>
                <span class="px-3 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded-full text-xs font-extrabold">🎁 +100 ALMAI Poin</span>
            </div>
        </div>

        <!-- Call to Action Buttons -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="<?= base_url('user/dashboard') ?>" class="py-4 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-bold rounded-2xl transition-all shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2">
                <i class="fas fa-th-large"></i> Masuk Dashboard
            </a>
            <a href="<?= base_url('edukasi') ?>" class="py-4 bg-slate-800 hover:bg-slate-700 text-white font-semibold rounded-2xl transition-all border border-slate-700 flex items-center justify-center gap-2">
                <i class="fas fa-graduation-cap"></i> Mulai Belajar
            </a>
        </div>
    </div>

</body>
</html>
