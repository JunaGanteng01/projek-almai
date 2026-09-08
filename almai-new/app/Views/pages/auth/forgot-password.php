<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Almai E-Learning</title>
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
    <style>
        html, body { background-color: #050505; color: #ffffff; overflow-x: hidden; }
        .bg-grid { background-size: 40px 40px; background-image: linear-gradient(to right, rgba(255,255,255,0.05) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,0.05) 1px, transparent 1px); }
    </style>
</head>
<body class="antialiased min-h-screen relative overflow-x-hidden">
    <?= $this->include('partials/navbar') ?>

    <div class="absolute inset-0 bg-grid z-0 pointer-events-none"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[300px] md:w-[800px] h-[300px] md:h-[500px] bg-accent/20 blur-[120px] rounded-full pointer-events-none"></div>

    <div class="relative z-10 w-full max-w-md px-6 mx-auto pt-28 pb-12 min-h-screen flex flex-col justify-center">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="<?= base_url('/') ?>" class="inline-flex items-center gap-2 text-2xl font-bold">
                <img src="<?= base_url('images/alma.gif') ?>" alt="ALMAI" class="h-10">
                <span>ALMAI</span>
            </a>
        </div>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="bg-accent/20 border border-accent/30 text-accent px-4 py-3 rounded-xl mb-4">
                <i class="fas fa-check-circle mr-2"></i><?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl mb-4">
                <i class="fas fa-exclamation-circle mr-2"></i><?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        
        <!-- Development: Show reset link -->
        <?php if (session()->getFlashdata('reset_link')): ?>
            <div class="bg-yellow-500/20 border border-yellow-500/30 text-yellow-400 px-4 py-3 rounded-xl mb-4">
                <p class="text-sm mb-2"><i class="fas fa-code mr-2"></i><strong>Development Mode:</strong> Link reset password:</p>
                <a href="<?= session()->getFlashdata('reset_link') ?>" class="text-accent hover:underline break-all text-xs">
                    <?= session()->getFlashdata('reset_link') ?>
                </a>
            </div>
        <?php endif; ?>

        <!-- Forgot Password Form -->
        <div class="bg-[#111] rounded-2xl border border-white/10 p-8">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-accent/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-key text-2xl text-accent"></i>
                </div>
                <h2 class="text-2xl font-bold">Lupa Password?</h2>
                <p class="text-gray-400 text-sm mt-2">Masukkan email Anda dan kami akan mengirimkan link untuk reset password.</p>
            </div>

            <form action="<?= base_url('forgot-password') ?>" method="post" class="space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Email</label>
                    <div class="relative">
                        <input type="email" name="email" required value="<?= old('email') ?>" 
                            class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 pl-12 focus:border-accent focus:outline-none" 
                            placeholder="email@example.com">
                        <i class="fas fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
                    </div>
                </div>
                <button type="submit" class="w-full py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition shadow-[0_0_20px_rgba(51,232,24,0.3)]">
                    <i class="fas fa-paper-plane mr-2"></i> Kirim Link Reset
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="<?= base_url('login') ?>" class="text-gray-400 hover:text-accent transition text-sm">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Login
                </a>
            </div>
        </div>

        <!-- Info -->
        <div class="mt-6 bg-blue-500/10 border border-blue-500/20 rounded-xl p-4">
            <p class="text-blue-400 text-sm text-center">
                <i class="fas fa-info-circle mr-2"></i>
                Link reset password akan dikirim ke email Anda dan berlaku selama 1 jam.
            </p>
        </div>
    </div>
</body>
</html>
