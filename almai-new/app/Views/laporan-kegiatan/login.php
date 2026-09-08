<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Login Laporan Kegiatan - Almai') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'accent': '#33e818',
                        'accent-hover': '#2bc214',
                    },
                    fontFamily: {
                        sans: ['Montserrat', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Cloudflare Turnstile -->
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

    <style>
        html,
        body {
            background-color: #050505;
            color: #ffffff;
        }

        .bg-grid {
            background-size: 40px 40px;
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px), linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        }

        .turnstile-wrap {
            width: 100%;
            max-width: 100%;
            overflow: visible;
        }

        .cf-turnstile {
            width: 100% !important;
            display: flex;
            justify-content: center;
            overflow: visible;
        }

        .cf-turnstile > div {
            width: 100% !important;
        }

        .cf-turnstile iframe {
            width: 100% !important;
            max-width: 100% !important;
            border-radius: 0.75rem !important;
        }
    </style>
</head>

<body class="antialiased min-h-screen relative">
    <div class="absolute inset-0 bg-grid z-0"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[500px] h-[400px] bg-accent/20 blur-[150px] rounded-full"></div>

    <div class="relative z-10 min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <!-- Logo & Title -->
            <div class="text-center mb-8">
                <a href="<?= base_url('/') ?>" class="inline-flex items-center gap-2 mb-4">
                    <img src="https://almai.id/images/alma.gif" alt="ALMAI" class="h-12">
                    <span class="text-2xl font-bold">ALMAI</span>
                </a>
                <div class="mt-3">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-accent/20 rounded-full">
                        <i class="fas fa-handshake text-accent"></i>
                        <span class="text-accent font-medium text-sm">Laporan Kegiatan</span>
                    </div>
                </div>
            </div>

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="bg-accent/20 border border-accent/30 text-accent px-4 py-3 rounded-xl mb-4">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl mb-4">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <!-- Login Card -->
            <div class="bg-[#111] border border-white/10 rounded-2xl p-8">
                <h2 class="text-2xl font-bold mb-2">Login Laporan Kegiatan</h2>
                <p class="text-gray-500 text-sm mb-6">Masuk ke dashboard laporan kegiatan untuk memantau aktivitas</p>

                <form action="<?= base_url('laporan-kegiatan/login') ?>" method="post" class="space-y-4">
                    <?= csrf_field() ?>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Username / Email</label>
                        <div class="relative">
                            <input type="email" name="email" required value="<?= old('email') ?>"
                                class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 pl-12 focus:border-accent focus:outline-none"
                                placeholder="Masukan Email">
                            <i class="fas fa-user absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="adminPassword" required
                                class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 pl-12 focus:border-accent focus:outline-none"
                                placeholder="Masukan Password">
                            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
                            <button type="button" onclick="togglePassword('adminPassword')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Cloudflare Turnstile Widget -->
                    <div class="turnstile-wrap">
                        <div class="cf-turnstile w-full"
                            data-sitekey="<?= env('TURNSTILE_SITE_KEY', '0x4AAAAAAAzM8JmJ8PGJ_YbS') ?>"
                            data-theme="dark"
                            data-size="flexible">
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition shadow-[0_0_30px_rgba(51,232,24,0.3)]">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login Laporan Kegiatan
                    </button>
                </form>

                <!-- Back Link -->
                <div class="text-center mt-6">
                    <a href="<?= base_url('/') ?>" class="text-gray-400 hover:text-white transition">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Website
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>

</html>
