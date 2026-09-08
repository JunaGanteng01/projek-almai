<?= $this->extend('user/partials/layout') ?>

<?= $this->section('content') ?>
<div class="flex flex-col items-center justify-center py-20 px-6 text-center">
    <div class="w-24 h-24 bg-accent/20 rounded-full flex items-center justify-center text-accent text-4xl mb-8 shadow-2xl shadow-accent/20 animate-bounce">
        <i class="fas fa-crown"></i>
    </div>
    
    <h1 class="text-3xl font-black text-white mb-4 tracking-tight">Fitur Eksklusif PRO</h1>
    <p class="text-gray-400 max-w-md mx-auto leading-relaxed mb-10">
        Mohon maaf, fitur monitoring Portofolio hanya tersedia untuk member <span class="text-accent font-bold italic">Almai PRO</span>. 
        Tingkatkan akun Anda sekarang untuk mulai membagikan sinyal trading secara profesional.
    </p>

    <div class="space-y-4 w-full max-w-sm">
        <a href="<?= base_url('user/kyc') ?>" class="block w-full bg-accent hover:bg-green-400 text-black font-black py-4 rounded-2xl shadow-xl transition-all transform hover:scale-[1.02] active:scale-[0.98]">
            UPGRADE KE PRO SEKARANG
        </a>
        <a href="<?= base_url('user/dashboard') ?>" class="block w-full bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white font-bold py-4 rounded-2xl transition-all">
            KEMBALI KE DASHBOARD
        </a>
    </div>

    <!-- Benefits Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-6 mt-20 opacity-50">
        <div class="flex flex-col items-center gap-2">
            <i class="fas fa-chart-line text-accent"></i>
            <span class="text-[10px] uppercase tracking-widest font-bold">Live Stats</span>
        </div>
        <div class="flex flex-col items-center gap-2">
            <i class="fas fa-satellite-dish text-accent"></i>
            <span class="text-[10px] uppercase tracking-widest font-bold">Auto Sync</span>
        </div>
        <div class="flex flex-col items-center gap-2">
            <i class="fas fa-share-nodes text-accent"></i>
            <span class="text-[10px] uppercase tracking-widest font-bold">Public Link</span>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
