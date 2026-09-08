<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-black pt-24 pb-12">
    <div class="container mx-auto px-4 max-w-lg">
        
        <div class="bg-[#111] border border-white/10 p-6 md:p-8 rounded-2xl relative overflow-hidden group">
            <div class="absolute -inset-1 bg-gradient-to-r from-accent to-blue-600 opacity-20 blur-xl group-hover:opacity-30 transition duration-500"></div>
            
            <div class="relative z-10 text-center mb-8">
                <h1 class="text-3xl font-bold text-white mb-2">Daftar Webinar Plus500</h1>
                <p class="text-gray-400">Silakan isi formulir di bawah ini untuk mendaftar.</p>
            </div>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-xl mb-6 text-sm">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')) : ?>
                <div class="bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-xl mb-6 text-sm">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('daftar-webinar/process') ?>" method="POST" id="webinarForm" class="space-y-5 relative z-10">
                <?= csrf_field() ?>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-400 mb-1">Email <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <input type="email" id="email" name="email" required placeholder="Masukkan email aktif"
                            class="w-full bg-[#1a1a1a] border border-white/10 rounded-xl pl-10 pr-4 py-3 text-white placeholder-gray-600 focus:border-accent focus:ring-1 focus:ring-accent outline-none transition">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-400 mb-1">Password <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">
                            <i class="fas fa-lock"></i>
                        </div>
                        <input type="password" id="password" name="password" required placeholder="Buat password"
                            class="w-full bg-[#1a1a1a] border border-white/10 rounded-xl pl-10 pr-10 py-3 text-white placeholder-gray-600 focus:border-accent focus:ring-1 focus:ring-accent outline-none transition">
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-white transition" onclick="togglePassword('password')">
                            <i class="fas fa-eye" id="password_eye"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label for="referral" class="block text-sm font-medium text-gray-400 mb-1">Referral Code</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-500">
                            <i class="fas fa-ticket-alt"></i>
                        </div>
                        <input type="text" id="referral" name="referral" placeholder="Opsional"
                            class="w-full bg-[#1a1a1a] border border-white/10 rounded-xl pl-10 pr-4 py-3 text-white placeholder-gray-600 focus:border-accent focus:ring-1 focus:ring-accent outline-none transition uppercase">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" id="btnSubmit" class="w-full relative overflow-hidden group bg-accent text-white font-bold py-3.5 px-6 rounded-xl transition-all duration-300 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed">
                        <div class="absolute inset-0 w-full h-full bg-white/20 scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
                        <span class="relative flex items-center justify-center gap-2">
                            <i class="fas fa-paper-plane"></i> Daftar Sekarang
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(inputId + '_eye');
        
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

    document.getElementById('webinarForm').addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmit');
        btn.disabled = true;
        btn.querySelector('span').innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
    });
</script>
<?= $this->endSection() ?>
