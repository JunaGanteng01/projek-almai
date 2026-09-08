<?= $this->extend('user/partials/layout') ?>

<?= $this->section('content') ?>
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold">Aktivasi Layanan</h1>
            <p class="text-gray-400 text-sm">Masukkan kode aktivasi untuk mendapatkan akses ke layanan ALMAI.</p>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="bg-green-500/10 border border-green-500/50 text-green-500 px-4 py-3 rounded-xl mb-4">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="bg-red-500/10 border border-red-500/50 text-red-500 px-4 py-3 rounded-xl mb-4">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <div class="bg-[#111] border border-white/10 rounded-xl p-8 text-center">
        <div class="w-16 h-16 bg-accent/20 rounded-full flex items-center justify-center mx-auto mb-6 text-accent">
            <i class="fas fa-key text-2xl"></i>
        </div>
        <h2 class="text-xl font-bold mb-2">Punya Kode Aktivasi?</h2>
        <p class="text-gray-400 mb-8 max-w-md mx-auto">Masukkan kode 16 karakter yang Anda dapatkan dari pembelian atau event untuk mengaktifkan akses Anda.</p>

        <form action="<?= base_url('user/dashboard/aktivasi/redeem') ?>" method="POST" class="max-w-md mx-auto">
            <?= csrf_field() ?>
            <div class="mb-4">
                <input type="text" name="activation_code" required 
                       placeholder="ALMAI-XXXX-XXXX-XXXX" 
                       class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-4 text-center text-xl font-mono uppercase tracking-wider focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent transition-all"
                       <?= session()->getFlashdata('lock_until') > time() ? 'disabled' : '' ?>>
            </div>
            
            <?php if (session()->getFlashdata('lock_until') > time()): ?>
                <p class="text-red-400 text-sm mb-4">
                    Terlalu banyak percobaan gagal. Silakan coba lagi dalam <span id="countdown">beberapa</span> detik.
                </p>
                <script>
                    let timeLeft = <?= session()->getFlashdata('lock_until') - time() ?>;
                    setInterval(() => {
                        if (timeLeft <= 0) location.reload();
                        document.getElementById('countdown').innerText = timeLeft--;
                    }, 1000);
                </script>
            <?php else: ?>
                <button type="submit" class="w-full bg-accent hover:bg-accent-hover text-black font-bold py-3 px-6 rounded-lg transition-all">
                    Aktivasi Sekarang
                </button>
            <?php endif; ?>
        </form>
    </div>

    <!-- Riwayat Aktivasi -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-6">
        <h3 class="font-bold mb-4">Layanan Aktif Anda</h3>
        <?php if (empty($active_services)): ?>
            <p class="text-gray-400 text-sm text-center py-4">Belum ada layanan yang aktif.</p>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($active_services as $service): ?>
                    <div class="flex items-center justify-between p-4 bg-[#0a0a0a] border border-white/5 rounded-lg">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded bg-white/5 flex items-center justify-center">
                                <i class="fas fa-box text-gray-400"></i>
                            </div>
                            <div>
                                <h4 class="font-bold"><?= esc($service['product_name']) ?></h4>
                                <p class="text-xs text-gray-400 uppercase"><?= esc($service['product_type']) ?></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <?php if ($service['status'] === 'active'): ?>
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-green-500/20 text-green-500">Aktif</span>
                            <?php else: ?>
                                <span class="px-2 py-1 text-xs font-bold rounded-full bg-red-500/20 text-red-500">Kadaluarsa</span>
                            <?php endif; ?>
                            
                            <?php if ($service['expires_at']): ?>
                                <p class="text-xs text-gray-400 mt-1">s.d <?= date('d M Y', strtotime($service['expires_at'])) ?></p>
                            <?php else: ?>
                                <p class="text-xs text-gray-400 mt-1">Lifetime (Selamanya)</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
