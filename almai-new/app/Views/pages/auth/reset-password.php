<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Almai E-Learning</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: { extend: { colors: { 'accent': '#33e818' }, fontFamily: { sans: ['Montserrat', 'sans-serif'] } } }
        }
    </script>
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
        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl mb-4">
                <i class="fas fa-exclamation-circle mr-2"></i><?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl mb-4">
                <ul class="list-disc list-inside">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Reset Password Form -->
        <div class="bg-[#111] rounded-2xl border border-white/10 p-8">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-accent/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-lock text-2xl text-accent"></i>
                </div>
                <h2 class="text-2xl font-bold">Reset Password</h2>
                <p class="text-gray-400 text-sm mt-2">Masukkan password baru Anda.</p>
            </div>

            <form action="<?= base_url('reset-password/' . $token) ?>" method="post" class="space-y-4">
                <?= csrf_field() ?>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Password Baru</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required minlength="8"
                            class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 pl-12 focus:border-accent focus:outline-none" 
                            placeholder="Min. 8 karakter">
                        <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
                        <button type="button" onclick="togglePassword('password')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Konfirmasi Password</label>
                    <div class="relative">
                        <input type="password" name="password_confirm" id="password_confirm" required minlength="8"
                            class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 pl-12 focus:border-accent focus:outline-none" 
                            placeholder="Ulangi password baru">
                        <i class="fas fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-gray-500"></i>
                        <button type="button" onclick="togglePassword('password_confirm')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Password Strength Indicator -->
                <div class="space-y-2">
                    <div class="flex gap-1">
                        <div id="str1" class="h-1 flex-1 bg-white/10 rounded"></div>
                        <div id="str2" class="h-1 flex-1 bg-white/10 rounded"></div>
                        <div id="str3" class="h-1 flex-1 bg-white/10 rounded"></div>
                        <div id="str4" class="h-1 flex-1 bg-white/10 rounded"></div>
                    </div>
                    <p id="strText" class="text-xs text-gray-500">Masukkan password</p>
                </div>

                <button type="submit" class="w-full py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition shadow-[0_0_20px_rgba(51,232,24,0.3)]">
                    <i class="fas fa-check mr-2"></i> Reset Password
                </button>
            </form>
        </div>

        <!-- Back to Login -->
        <div class="text-center mt-6">
            <a href="<?= base_url('login') ?>" class="text-gray-400 hover:text-accent transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Login
            </a>
        </div>
    </div>

    <script>
        function togglePassword(id) {
            const input = document.getElementById(id);
            const icon = input.nextElementSibling.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            
            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/\d/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;

            const colors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-accent'];
            const texts = ['Sangat Lemah', 'Lemah', 'Sedang', 'Kuat'];
            
            for (let i = 1; i <= 4; i++) {
                const el = document.getElementById('str' + i);
                el.className = 'h-1 flex-1 rounded ' + (i <= strength ? colors[strength - 1] : 'bg-white/10');
            }
            
            document.getElementById('strText').textContent = strength > 0 ? texts[strength - 1] : 'Masukkan password';
            document.getElementById('strText').className = 'text-xs ' + (strength > 0 ? colors[strength - 1].replace('bg-', 'text-') : 'text-gray-500');
        });
    </script>
</body>
</html>
