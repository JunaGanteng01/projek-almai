<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'CWPA Login - Almai') ?></title>
    <link rel="stylesheet" href="<?= base_url('css/tailwind.min.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
    </style>
</head>

<body class="antialiased min-h-screen relative">
    <div class="absolute inset-0 bg-grid z-0"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[500px] h-[400px] bg-accent/20 blur-[150px] rounded-full"></div>

    <div class="relative z-10 min-h-screen flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            <!-- Logo & Title -->
            <div class="text-center mb-8">
                <a href="<?= base_url('/') ?>" class="inline-block mb-3">
                    <img src="https://almai.id/images/alma.gif" alt="ALMAI" class="h-16 mx-auto">
                </a>
                <p class="text-accent font-medium text-sm">CWPA Portal</p>
                <p class="text-xs text-gray-500 mt-1">Calon Wakil Penasihat Berjangka</p>
            </div>

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="bg-accent/20 border border-accent/30 text-accent px-4 py-3 rounded-xl mb-4">
                    <?= esc(session()->getFlashdata('success')) ?>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl mb-4">
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <!-- Login Card -->
            <div class="bg-[#111] border border-white/10 rounded-2xl p-8">
                <h2 class="text-2xl font-bold mb-2">CWPA Login</h2>
                <p class="text-gray-500 text-sm mb-6">Masuk untuk melihat progress sertifikasi Anda</p>

                <form action="<?= base_url('cwpa/login') ?>" method="post" class="space-y-4">
                    <?= csrf_field() ?>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Email</label>
                        <div class="relative">
                            <input type="email" name="email" required value="<?= old('email') ?>"
                                class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 pl-12 focus:border-accent focus:outline-none"
                                placeholder="email@almai.id">
                            <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="cwpaPassword" required
                                class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 pl-12 focus:border-accent focus:outline-none"
                                placeholder="••••••••">
                            <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
                            <button type="button" onclick="togglePassword('cwpaPassword')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center gap-2 text-gray-400 cursor-pointer">
                            <input type="checkbox" name="remember" class="accent-accent"> Ingat saya
                        </label>
                        <a href="<?= base_url('forgot-password') ?>" class="text-accent hover:underline">Lupa password?</a>
                    </div>

                    <button type="submit" class="w-full py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition shadow-[0_0_30px_rgba(51,232,24,0.3)]">
                        <i class="fas fa-sign-in-alt mr-2"></i> Login CWPA
                    </button>
                </form>

            </div>

            <!-- Back Link -->
            <div class="text-center mt-6 space-y-2">
                <a href="<?= base_url('/') ?>" class="block text-gray-400 hover:text-white transition">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Website
                </a>
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