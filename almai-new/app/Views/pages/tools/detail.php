<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    .bg-grid { background-size: 40px 40px; background-image: linear-gradient(to right, rgba(255,255,255,0.05) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,0.05) 1px, transparent 1px); mask-image: linear-gradient(to bottom, black 40%, transparent 100%); }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

    <!-- Tool Detail -->
    <section class="pt-32 pb-16">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-3 gap-12">
                <!-- Main Content -->
                <div class="md:col-span-2">
                    <img src="<?= esc($tool['thumbnail']) ?>" alt="<?= esc($tool['name']) ?>" class="w-full h-80 object-cover rounded-2xl mb-8">
                    
                    <div class="flex items-center gap-4 mb-4">
                        <span class="<?= getCategoryColor($tool['category']) ?> text-white px-4 py-1 rounded-full text-sm font-bold">
                            <i class="fas <?= getCategoryIcon($tool['category']) ?> mr-1"></i><?= esc($tool['category']) ?>
                        </span>
                        <span class="border border-white/20 px-4 py-1 rounded-full text-sm"><?= esc($tool['platform']) ?></span>
                    </div>
                    
                    <h1 class="text-4xl font-bold mb-6"><?= esc($tool['name']) ?></h1>
                    
                    <div class="flex items-center gap-6 mb-8 text-gray-400">
                        <span>⭐ <?= esc($tool['rating']) ?></span>
                        <span><i class="fas fa-shopping-cart mr-1"></i> <?= number_format($tool['sales']) ?> terjual</span>
                    </div>

                    <!-- Description -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold mb-4">Deskripsi</h3>
                        <p class="text-gray-400 leading-relaxed"><?= esc($tool['description']) ?></p>
                    </div>

                    <!-- Features -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold mb-4">Fitur Utama</h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <?php foreach ($tool['features'] as $feature): ?>
                            <div class="flex items-center gap-3 bg-black/50 p-4 rounded-xl border border-white/10">
                                <i class="fas fa-check text-accent"></i>
                                <span><?= esc($feature) ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Compatibility -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold mb-4">Kompatibilitas</h3>
                        <div class="flex flex-wrap gap-3">
                            <?php foreach ($tool['compatibility'] as $comp): ?>
                            <span class="bg-white/10 px-4 py-2 rounded-full text-sm"><?= esc($comp) ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Sidebar - Purchase Card -->
                <div class="md:col-span-1">
                    <div class="bg-[#111] rounded-2xl border border-white/10 p-6 sticky top-28">
                        <div class="mb-4">
                            <span class="text-gray-500 line-through text-lg"><?= formatRupiah($tool['original_price']) ?></span>
                            <span class="text-accent font-bold text-3xl ml-2"><?= formatRupiah($tool['price']) ?></span>
                        </div>
                        
                        <?php $discount = $tool['original_price'] - $tool['price']; ?>
                        <div class="bg-accent/10 border border-accent/30 rounded-xl p-3 mb-6">
                            <p class="text-accent text-sm text-center"><i class="fas fa-tag mr-1"></i> Hemat <?= formatRupiah($discount) ?></p>
                        </div>
                        
                        <div class="space-y-3 mb-6 text-sm text-gray-400">
                            <div class="flex items-center gap-2"><i class="fas fa-infinity text-accent"></i> Lisensi Selamanya</div>
                            <div class="flex items-center gap-2"><i class="fas fa-sync text-accent"></i> Update Gratis</div>
                            <div class="flex items-center gap-2"><i class="fas fa-headset text-accent"></i> Support 24/7</div>
                            <div class="flex items-center gap-2"><i class="fas fa-book text-accent"></i> Dokumentasi Lengkap</div>
                            <div class="flex items-center gap-2"><i class="fas fa-video text-accent"></i> Video Tutorial</div>
                        </div>

                        <?php 
                        $isLoggedIn = session()->get('isLoggedIn');
                        $userRole   = session()->get('role');
                        $isPro      = session()->get('is_pro');
                        $canBuy     = !$isLoggedIn || $userRole === 'admin' || $userRole === 'wpa' || $isPro;
                        ?>
                        
                        <?php if (!$isLoggedIn): ?>
                        <button onclick="handleBuyTool()" class="w-full py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition shadow-[0_0_20px_rgba(51,232,24,0.3)]">
                            <i class="fas fa-shopping-cart mr-2"></i> Beli Sekarang
                        </button>
                        <?php elseif ($canBuy): ?>
                        <button onclick="handleBuyTool()" class="w-full py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition shadow-[0_0_20px_rgba(51,232,24,0.3)]">
                            <i class="fas fa-shopping-cart mr-2"></i> Beli Sekarang
                        </button>
                        <?php else: ?>
                        <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-xl p-4 mb-4">
                            <div class="flex items-center gap-3 text-yellow-400 mb-2">
                                <i class="fas fa-crown text-xl"></i>
                                <span class="font-bold">Khusus User PRO</span>
                            </div>
                            <p class="text-sm text-gray-400">Untuk membeli tools, Anda harus menjadi user PRO terlebih dahulu.</p>
                        </div>
                        <a href="<?= base_url('user/kyc') ?>" class="block w-full py-4 bg-yellow-500 text-black font-bold rounded-xl hover:bg-yellow-400 transition text-center">
                            <i class="fas fa-arrow-up mr-2"></i> Upgrade ke PRO
                        </a>
                        <p class="text-xs text-gray-500 text-center mt-3">Verifikasi KYC untuk menjadi user PRO</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <!-- Alert Modal -->
    <div id="alertModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-sm">
        <div class="bg-[#111] border border-white/10 rounded-2xl p-8 max-w-sm mx-4 text-center transform scale-95 opacity-0 transition-all duration-300" id="alertContent">
            <div id="alertIcon" class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center"></div>
            <h3 id="alertTitle" class="text-xl font-bold mb-2"></h3>
            <p id="alertMessage" class="text-gray-400 mb-6"></p>
            <button onclick="closeAlert()" class="px-8 py-3 bg-accent text-black font-bold rounded-full hover:bg-white transition">OK</button>
        </div>
    </div>

    <script>
        const toolId    = <?= $tool['id'] ?>;
        const toolName  = "<?= esc($tool['name']) ?>";
        const toolPrice = "<?= formatRupiah($tool['price']) ?>";

        function showAlert(type, title, message, callback = null) {
            const modal   = document.getElementById('alertModal');
            const content = document.getElementById('alertContent');
            const icon    = document.getElementById('alertIcon');
            const titleEl = document.getElementById('alertTitle');
            const msgEl   = document.getElementById('alertMessage');
            
            titleEl.textContent = title;
            msgEl.textContent   = message;
            
            const types = {
                success: { bg: 'bg-accent/20',       ico: 'fa-check',                 color: 'text-accent'       },
                warning: { bg: 'bg-yellow-500/20',   ico: 'fa-exclamation-triangle',  color: 'text-yellow-500'   },
                error:   { bg: 'bg-red-500/20',      ico: 'fa-times',                 color: 'text-red-500'      },
                info:    { bg: 'bg-blue-500/20',     ico: 'fa-info',                  color: 'text-blue-500'     },
            };
            const t = types[type] || types.info;
            icon.className = `w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center ${t.bg}`;
            icon.innerHTML = `<i class="fas ${t.ico} text-3xl ${t.color}"></i>`;
            
            window.alertCallback = callback;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }
        
        function closeAlert() {
            const modal   = document.getElementById('alertModal');
            const content = document.getElementById('alertContent');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                if (window.alertCallback) {
                    window.alertCallback();
                    window.alertCallback = null;
                }
            }, 300);
        }

        function handleBuyTool() {
            <?php if (session()->get('isLoggedIn')): ?>
                window.location.href = '<?= base_url('checkout/tools/' . $tool['id']) ?>';
            <?php else: ?>
                showAlert('warning', 'Login Diperlukan', 'Silakan login terlebih dahulu untuk membeli tools.', () => {
                    window.location.href = '<?= base_url('login') ?>';
                });
            <?php endif; ?>
        }
    </script>
<?= $this->endSection() ?>

<?php
function getCategoryIcon($category) {
    $icons = [
        'Expert Advisor' => 'fa-robot',
        'Copier'         => 'fa-copy',
        'Signal'         => 'fa-signal',
        'Indicator'      => 'fa-chart-line',
        'Toolkit'        => 'fa-toolbox'
    ];
    return $icons[$category] ?? 'fa-cube';
}

function getCategoryColor($category) {
    $colors = [
        'Expert Advisor' => 'bg-blue-500',
        'Copier'         => 'bg-purple-500',
        'Signal'         => 'bg-orange-500',
        'Indicator'      => 'bg-cyan-500',
        'Toolkit'        => 'bg-pink-500'
    ];
    return $colors[$category] ?? 'bg-gray-500';
}

function formatRupiah($number) {
    return 'Rp ' . number_format($number, 0, ',', '.');
}
?>
