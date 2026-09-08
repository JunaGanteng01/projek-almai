<?= $this->extend('keuangan/layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-[80vh] flex flex-col items-center justify-center p-6 text-center">
    <div class="relative mb-8">
        <!-- Animation Container -->
        <div class="w-32 h-32 bg-accent/10 rounded-full flex items-center justify-center relative z-10">
            <i class="fas fa-tools text-5xl text-accent animate-pulse"></i>
        </div>
        <!-- Decorative Glow -->
        <div class="absolute inset-0 bg-accent/20 blur-[50px] rounded-full scale-150 opacity-50"></div>
    </div>

    <h1 class="text-3xl font-black text-white mb-4 uppercase tracking-tighter">
        Fitur <span class="text-accent"><?= esc($title) ?></span> Sedang Dikembangkan
    </h1>
    
    <p class="text-gray-400 max-w-lg mx-auto mb-10 leading-relaxed">
        Mohon maaf, modul <span class="text-white font-bold"><?= esc($title) ?></span> saat ini sedang dalam tahap pengembangan intensif oleh tim pengembang kami untuk memberikan pengalaman akuntansi yang terbaik bagi Anda.
    </p>

    <div class="flex flex-col sm:flex-row gap-4">
        <a href="<?= base_url('keuangan') ?>" class="px-8 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center gap-2 justify-center">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>
        <button onclick="window.history.back()" class="px-8 py-3 bg-white/5 text-white border border-white/10 font-bold rounded-xl hover:bg-white/10 transition">
            Kembali sebelumnya
        </button>
    </div>

    <!-- Progress Steps (Visual Only) -->
    <div class="mt-20 w-full max-w-2xl">
        <div class="flex justify-between items-center mb-4">
            <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Development Progress</span>
            <span class="text-[10px] font-bold text-accent uppercase tracking-widest">75% Complete</span>
        </div>
        <div class="w-full bg-white/5 rounded-full h-2 border border-white/5 overflow-hidden">
            <div class="bg-accent h-full w-3/4 shadow-[0_0_15px_rgba(51,232,24,0.5)]"></div>
        </div>
        <div class="grid grid-cols-3 mt-6 gap-4">
            <div class="flex items-center gap-2 opacity-100">
                <i class="fas fa-check-circle text-accent"></i>
                <span class="text-[10px] text-gray-400">Database Schema</span>
            </div>
            <div class="flex items-center gap-2 opacity-100">
                <i class="fas fa-check-circle text-accent"></i>
                <span class="text-[10px] text-gray-400">Core Logic</span>
            </div>
            <div class="flex items-center gap-2 opacity-50">
                <div class="w-3 h-3 border-2 border-gray-600 rounded-full animate-spin border-t-accent"></div>
                <span class="text-[10px] text-gray-400">Final UI Polish</span>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>