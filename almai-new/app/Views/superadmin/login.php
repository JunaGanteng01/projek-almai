<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin Login - Almai') ?></title>
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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Cloudflare Turnstile -->
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>

    <style>
        html,
        body {
            background-color: #050505;
            color: #ffffff;
            font-family: 'Montserrat', sans-serif;
        }

        .bg-grid {
            background-size: 40px 40px;
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px), linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        }

        /* Turnstile Full Width */
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

        @media (max-width: 420px) {
            .cf-turnstile {
                min-height: 70px;
            }

            .turnstile-wrap {
                transform: scale(0.9);
                transform-origin: top left;
                width: calc(100% / 0.9);
            }
        }

        @media (max-width: 360px) {
            .turnstile-wrap {
                transform: scale(0.85);
                transform-origin: top left;
                width: calc(100% / 0.85);
            }
        }
    </style>
</head>

<body class="antialiased min-h-screen relative flex items-center justify-center p-4 selection:bg-accent/30 selection:text-white">
    <div class="fixed inset-0 bg-grid z-0 pointer-events-none"></div>
    <div class="fixed top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[550px] h-[450px] bg-accent/15 blur-[160px] rounded-full pointer-events-none"></div>

    <div class="relative z-10 w-full max-w-md my-8">
        <!-- Top Logo -->
        <div class="text-center mb-7">
            <a href="<?= base_url('/') ?>" class="inline-flex items-center gap-3 group">
                <img src="https://almai.id/images/alma.gif" alt="ALMAI" class="h-11 w-auto">
                <span class="text-2xl font-black text-white tracking-wide">ALMAI</span>
            </a>
        </div>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-accent/20 border border-accent/40 text-accent px-4 py-3 rounded-xl mb-4 text-xs font-semibold shadow-lg">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-500/20 border border-red-500/40 text-red-400 px-4 py-3 rounded-xl mb-4 text-xs font-semibold shadow-lg">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <!-- Login Card -->
        <div class="bg-[#121212] border border-white/[0.08] rounded-2xl p-6 sm:p-8 shadow-2xl backdrop-blur-sm">
            <h2 class="text-2xl font-black text-white mb-1.5">Admin Login</h2>
            <p class="text-gray-400 text-xs sm:text-sm mb-6">Masuk ke dashboard admin untuk mengelola platform</p>

            <form action="<?= base_url('superadmin/login') ?>" method="post" class="space-y-4">
                <?= csrf_field() ?>

                <!-- Username / Email Input -->
                <div>
                    <label class="block text-xs sm:text-sm text-gray-300 font-medium mb-2">Username / Email</label>
                    <div class="flex items-center bg-[#eef3fb] rounded-xl px-4 py-3 border border-transparent focus-within:ring-2 focus-within:ring-accent transition shadow-inner">
                        <i class="fas fa-user text-gray-500 text-sm mr-3 shrink-0"></i>
                        <input type="text" name="email" required value="<?= old('email') ?>"
                            class="w-full bg-transparent text-gray-900 text-sm font-semibold placeholder-gray-400 focus:outline-none"
                            placeholder="almai.branding@gmail.com">
                    </div>
                </div>

                <!-- Password Input -->
                <div>
                    <label class="block text-xs sm:text-sm text-gray-300 font-medium mb-2">Password</label>
                    <div class="flex items-center bg-[#eef3fb] rounded-xl px-4 py-3 border border-transparent focus-within:ring-2 focus-within:ring-accent transition shadow-inner">
                        <i class="fas fa-lock text-gray-500 text-sm mr-3 shrink-0"></i>
                        <input type="password" name="password" id="adminPassword" required
                            class="w-full bg-transparent text-gray-900 text-sm font-semibold placeholder-gray-400 focus:outline-none"
                            placeholder="••••••••">
                        <button type="button" onclick="togglePassword('adminPassword')" class="text-gray-500 hover:text-gray-800 ml-2 focus:outline-none shrink-0" tabindex="-1">
                            <i class="fas fa-eye text-sm" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="pt-0.5">
                    <label class="inline-flex items-center gap-2 text-gray-300 cursor-pointer text-xs sm:text-sm font-medium select-none">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-white/10 border-white/20 accent-accent cursor-pointer">
                        <span>Ingat saya</span>
                    </label>
                </div>

                <!-- Cloudflare Turnstile Widget -->
                <div class="turnstile-wrap pt-1">
                    <div class="cf-turnstile w-full"
                        data-sitekey="<?= env('TURNSTILE_SITE_KEY', '0x4AAAAAAAzM8JmJ8PGJ_YbS') ?>"
                        data-theme="dark"
                        data-size="flexible"
                        style="width: 100%;">
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full py-3.5 bg-accent hover:bg-accent-hover text-black font-black text-sm sm:text-base rounded-xl transition shadow-[0_4px_25px_rgba(51,232,24,0.35)] flex items-center justify-center gap-2 active:scale-[0.99] cursor-pointer mt-2">
                    <i class="fas fa-sign-in-alt text-base"></i>
                    <span>Login Admin</span>
                </button>
            </form>

            <!-- Back Link -->
            <div class="text-center mt-6">
                <a href="<?= base_url('/') ?>" class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition text-xs sm:text-sm font-medium">
                    <i class="fas fa-arrow-left text-xs"></i>
                    <span>Kembali ke Website</span>
                </a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const icon = document.getElementById('toggleIcon');
            if (input.type === 'password') {
                input.type = 'text';
                if (icon) {
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                }
            } else {
                input.type = 'password';
                if (icon) {
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            }
        }
    </script>
</body>

</html>