<?= $this->extend('user/partials/layout') ?>

<?= $this->section('styles') ?>
<style>
    /* CKEditor Content List Fixes matching public view */
    .prose ul, .prose ol { margin-top: 1em !important; margin-bottom: 1em !important; margin-left: 1.5rem !important; }
    .prose ul { list-style-type: disc !important; }
    .prose ol { list-style-type: decimal !important; }
    .prose li { margin-bottom: 0.5em !important; padding-left: 0.5em !important; }
    .prose blockquote { border-left: 4px solid #33e818; padding-left: 1rem; font-style: italic; color: #9ca3af; }
    .prose img { border-radius: 0.5rem; margin-top: 1em; margin-bottom: 1em; }
    
    /* Custom Scrollbar for Popup */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(51, 232, 24, 0.2); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(51, 232, 24, 0.4); }
    
    /* Force SweetAlert2 Button Colors */
    .swal2-confirm { 
        background-color: #33e818 !important;
        color: #000 !important; 
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$placeholders = [
    'Advokasi' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=450&fit=crop',
    'Expert Advisor' => 'https://images.unsplash.com/photo-1642790106117-e829e14a795f?w=800&h=450&fit=crop',
    'Ultimate' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800&h=450&fit=crop',
];
$thumbnail = !empty($layanan['thumbnail']) ? $layanan['thumbnail'] : ($placeholders[$layanan['category']] ?? $placeholders['Advokasi']);
$color = 'accent';
?>

<!-- Breadcrumb matching public depth but dashboard context -->
<div class="mb-6">
    <nav class="flex items-center gap-2 text-sm text-gray-400">
        <a href="<?= base_url('user/dashboard') ?>" class="hover:text-white transition">Dashboard</a>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <?php if (($activeMenu ?? '') === 'daftar-wpa'): ?>
            <a href="<?= base_url('user/dashboard/daftar-wpa') ?>" class="hover:text-white transition">Daftar WPA</a>
        <?php else: ?>
            <a href="<?= base_url('user/dashboard/daftar-layanan') ?>" class="hover:text-white transition">Layanan</a>
        <?php endif; ?>
        <i class="fas fa-chevron-right text-[10px]"></i>
        <span class="text-white"><?= esc($layanan['name']) ?></span>
    </nav>
</div>

<!-- Main Content Grid matching Public Layout -->
<div class="grid lg:grid-cols-3 gap-8">
    <!-- Left Content (2 Columns) -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Thumbnail -->
        <div class="relative rounded-2xl overflow-hidden shadow-2xl">
            <img src="<?= $thumbnail ?>" alt="<?= esc($layanan['name']) ?>" class="w-full aspect-video object-cover">

            <!-- Badges -->
            <?php
            $refUserCash = 0; $refUserPoin = 0;
            if (!empty($packages)) {
                foreach ($packages as $_pkg) {
                    if (($_pkg['referral_user_cash'] ?? 0) > $refUserCash) {
                        $refUserCash = (float)$_pkg['referral_user_cash'];
                        $refUserPoin = (float)($_pkg['referral_user_poin'] ?? 0);
                    }
                }
            } else {
                $refUserCash = (float)($layanan['referral_user_cash'] ?? 0);
                $refUserPoin = (float)($layanan['referral_user_poin'] ?? 0);
            }
            $l1Cash = (int) floor($refUserCash * 0.5);
            $l1Poin = (int) floor($refUserPoin * 0.5);
            ?>
            <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                <span class="px-3 py-1 bg-black/70 backdrop-blur-sm rounded-full text-xs font-medium text-white"><?= esc($layanan['level'] ?? 'All Level') ?></span>
                <?php
                $modeColors = ['Online' => 'bg-accent', 'Offline' => 'bg-red-500', 'Hybrid' => 'bg-purple-500'];
                $modeColor = $modeColors[$layanan['mode'] ?? 'Online'] ?? 'bg-gray-500';
                ?>
                <span class="px-3 py-1 <?= $modeColor ?> text-black rounded-full text-xs font-medium">
                    <i class="fas <?= ($layanan['mode'] ?? 'Online') === 'Online' ? 'fa-video' : (($layanan['mode'] ?? '') === 'Offline' ? 'fa-building' : 'fa-arrows-rotate') ?> mr-1"></i>
                    <?= esc($layanan['mode'] ?? 'Online') ?>
                </span>
            </div>
            <?php if (($layanan['referral_distribution_percentage'] ?? 0) > 0): ?>
            <div class="absolute bottom-4 left-4 right-4">
                <div class="flex items-center gap-2 px-3 py-2 bg-black/75 backdrop-blur-sm border border-accent/30 rounded-xl">
                    <i class="fas fa-gift text-accent text-xs flex-shrink-0"></i>
                    <span class="text-[11px] font-bold text-accent">Bonus Referral Tersedia:</span>
                    <span class="text-[11px] font-black text-white"><?= number_format($layanan['referral_distribution_percentage'], 0) ?>%</span>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Title & Category -->
        <div>
            <p class="text-accent text-sm font-medium mb-2 uppercase tracking-wider"><?= esc($layanan['category']) ?> • <?= esc($layanan['subcategory']) ?></p>
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-6"><?= esc($layanan['name']) ?></h1>
            
            <!-- Provider Info matching public card -->
            <div class="flex items-center gap-4 p-4 bg-[#111] border border-white/10 rounded-2xl">
                <?php if ($wpa): ?>
                    <img src="<?= !empty($wpa['photo']) ? base_url('file/' . $wpa['photo']) : 'https://almai.id/images/alma.gif' ?>" class="w-14 h-14 rounded-full object-cover shrink-0 border-2 border-accent/20">
                    <div>
                        <p class="text-xs text-gray-500">Dibimbing oleh</p>
                        <p class="font-bold text-white"><?= esc($wpa['name']) ?></p>
                    </div>
                <?php else: ?>
                    <div class="w-14 h-14 rounded-full bg-accent/10 flex items-center justify-center">
                        <i class="fas fa-user-tie text-accent text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Dibimbing oleh</p>
                        <p class="font-bold text-white">Tim Almai</p>
                    </div>
                <?php endif; ?>
                <div class="ml-auto flex items-center gap-2 text-yellow-500">
                    <i class="fas fa-star"></i>
                    <span class="font-bold text-lg"><?= number_format($averageRating, 1) ?></span>
                </div>
            </div>
        </div>

        <!-- Description -->
        <div class="text-gray-300 leading-relaxed text-lg prose prose-invert max-w-none">
            <?= $layanan['description'] ?>
        </div>

        <!-- Features / Layanan Utama -->
        <?php if (!empty($layanan['layanan_utama'])): ?>
            <div class="p-8 bg-accent/5 border border-accent/20 rounded-3xl">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                    <i class="fas fa-check-circle text-accent"></i>
                    Layanan Utama
                </h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <?php
                    $points = explode("\n", $layanan['layanan_utama']);
                    foreach ($points as $p): if (trim($p)):
                    ?>
                        <div class="flex items-start gap-3">
                            <div class="mt-2 w-1.5 h-1.5 rounded-full bg-accent shrink-0"></div>
                            <span class="text-gray-300 text-sm"><?= esc(trim($p)) ?></span>
                        </div>
                    <?php endif; endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Reviews Section matching Public -->
        <div class="bg-[#111] border border-white/10 rounded-3xl p-8">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-white">Ulasan</h2>
                <div class="flex items-center gap-3">
                    <span class="text-accent text-3xl font-black"><?= number_format($averageRating, 1) ?></span>
                    <div class="flex text-yellow-500 text-sm">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star <?= $i <= round($averageRating) ? '' : 'opacity-20' ?>"></i>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <?php if (empty($reviews)): ?>
                    <p class="text-gray-500 text-center py-8 italic">Belum ada ulasan untuk layanan ini.</p>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                        <div class="p-5 bg-black/40 rounded-2xl border border-white/5">
                            <div class="flex justify-between items-start mb-3">
                                <span class="font-bold text-white"><?= esc($review['user_name']) ?></span>
                                <span class="text-[10px] text-gray-600 uppercase"><?= date('d M Y', strtotime($review['created_at'])) ?></span>
                            </div>
                            <div class="flex text-yellow-500 text-[10px] mb-3">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?= $i <= $review['rating'] ? '' : 'opacity-20' ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <p class="text-gray-400 text-sm italic">"<?= esc($review['ulasan']) ?>"</p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Right Sidebar - Sticky matching Public Sidebar -->
    <div class="lg:col-span-1">
        <div class="sticky top-6 space-y-6">
            <div class="bg-[#111] border border-white/10 rounded-3xl p-6 shadow-2xl">
                <!-- Price Section -->
                <div class="mb-8 p-4 bg-black/40 rounded-2xl border border-white/5">
                    <?php if (!empty($packages)): ?>
                        <p class="text-xs text-gray-500 mb-3 uppercase tracking-tighter">Pilih Paket:</p>
                        <div class="space-y-3">
                            <?php foreach ($packages as $pkg): ?>
                                <div onclick='openPackageModal(<?= json_encode($pkg) ?>)' 
                                     title="Klik untuk detail paket"
                                     class="p-3 bg-white/5 border border-white/10 rounded-xl flex justify-between items-center cursor-pointer hover:bg-white/10 hover:border-accent/50 transition-all group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-2 h-2 rounded-full bg-accent group-hover:scale-125 transition"></div>
                                        <span class="text-sm font-bold text-white"><?= esc($pkg['name']) ?></span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="text-sm font-bold text-accent">Rp <?= number_format($pkg['price'], 0, ',', '.') ?></span>
                                        <i class="fas fa-info-circle text-gray-600 group-hover:text-accent transition-colors text-xs"></i>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="mb-4">
                            <p class="text-3xl font-black text-accent mb-1">Rp <?= number_format($layanan['price'] ?? 0, 0, ',', '.') ?></p>
                            <?php if (!empty($layanan['poin_price'])): ?>
                                <p class="text-sm font-bold text-accent/80"><i class="fas fa-coins mr-1"></i><?= number_format($layanan['poin_price']) ?> Poin</p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Stats Grid matching screenshot and public detail -->
                <div class="grid grid-cols-2 gap-3 mb-8">
                    <div class="text-center p-4 bg-black/40 border border-white/5 rounded-2xl">
                        <i class="fas fa-clock text-white text-lg mb-2"></i>
                        <p class="text-sm font-bold text-white leading-none mb-1"><?= esc($layanan['duration'] ?? 'Lifetime') ?></p>
                        <p class="text-[10px] text-gray-500 uppercase">Durasi</p>
                    </div>
                    <div class="text-center p-4 bg-black/40 border border-white/5 rounded-2xl">
                        <i class="fas fa-users text-white text-lg mb-2"></i>
                        <p class="text-sm font-bold text-white leading-none mb-1"><?= number_format($layanan['students'] ?? 0) ?>+</p>
                        <p class="text-[10px] text-gray-500 uppercase">Peserta</p>
                    </div>
                    <div class="text-center p-4 bg-black/40 border border-white/5 rounded-2xl">
                        <i class="fas fa-map-marker-alt text-white text-lg mb-2"></i>
                        <p class="text-sm font-bold text-white leading-none mb-1"><?= esc($layanan['location'] ?? 'Online') ?></p>
                        <p class="text-[10px] text-gray-500 uppercase">Lokasi</p>
                    </div>
                    <div class="text-center p-4 bg-black/40 border border-white/5 rounded-2xl">
                        <i class="fas fa-calendar-alt text-white text-lg mb-2"></i>
                        <p class="text-sm font-bold text-white leading-none mb-1">Aktif</p>
                        <p class="text-[10px] text-gray-500 uppercase">Status</p>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="space-y-4">
                        <a href="<?= base_url('checkout/layanan/' . $layanan['slug']) ?>?ref=<?= esc($referralCode) ?>" 
                           id="buyBtn"
                           data-has-advokasi="<?= $hasAdvokasi ? '1' : '0' ?>"
                           data-requires-advokasi="<?= ($requiresAdvokasi ?? true) ? '1' : '0' ?>"
                           data-layanan-id="<?= $layanan['id'] ?>"
                           data-category="<?= esc($layanan['category']) ?>"
                           class="block text-center py-4 bg-accent text-black font-black rounded-2xl hover:brightness-110 transition shadow-lg shadow-accent/20 uppercase tracking-widest text-sm">
                            <i class="fas fa-shopping-cart mr-2"></i> Beli Sekarang
                        </a>
                    <button id="shareBtn" class="w-full py-4 border border-white/10 text-white font-bold rounded-2xl hover:bg-white/5 transition flex items-center justify-center gap-2 text-sm uppercase tracking-widest">
                        <i class="fas fa-share-alt"></i> Bagikan
                    </button>
                    <p class="text-[10px] text-center text-gray-500 italic pb-2">
                        <i class="fas fa-info-circle text-accent mr-1"></i> Transaksi resmi melalui gerbang pembayaran terenkripsi.
                    </p>
                </div>
            </div>
            
            <!-- Safe Badge Section -->
            <div class="p-6 bg-accent/5 border border-accent/20 rounded-3xl flex items-start gap-4">
                <i class="fas fa-shield-alt text-accent text-2xl mt-1"></i>
                <p class="text-xs text-gray-400 leading-relaxed">
                    Layanan resmi dari <span class="text-white font-bold">PT. Alma Indonesia Raya</span>. Seluruh transaksi dan data pribadi Anda dilindungi oleh enkripsi SSL.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Share Modal -->
<div id="shareModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-black/80 backdrop-blur-sm transition-all duration-300">
    <div class="relative w-full max-w-md bg-[#121212] border border-white/10 rounded-[2.5rem] p-8 shadow-2xl transform scale-95 opacity-0 transition-all duration-300" id="shareModalContent">
        <!-- Close Button -->
        <button onclick="closeShareModal()" class="absolute top-6 right-8 text-gray-500 hover:text-white transition-colors">
            <i class="fas fa-times text-xl"></i>
        </button>

        <h2 class="text-2xl font-black text-white mb-8">Bagikan Layanan</h2>

        <!-- Referral Link Section -->
        <div class="space-y-4 mb-8">
            <p class="text-[10px] font-bold text-gray-500 uppercase tracking-[0.2em]">Link Referral Anda</p>
            <div class="flex gap-2">
                <div class="flex-1 bg-black rounded-2xl border border-white/5 p-4 overflow-hidden">
                    <p class="text-gray-400 text-xs truncate font-mono" id="referralUrlText">
                        <?= base_url('layanan/' . $layanan['slug']) ?>?ref=<?= esc($referralCode) ?>
                    </p>
                </div>
                <button onclick="copyReferralLink()" class="w-14 h-14 bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center hover:bg-white/10 transition group">
                    <i class="far fa-copy text-white group-hover:scale-110 transition"></i>
                </button>
            </div>
            <p class="text-[10px] font-bold text-accent uppercase tracking-wider flex items-center gap-2">
                <i class="fas fa-gift"></i>
                Dapatkan Almai Poin dari setiap pembelian via link ini
            </p>
        </div>

        <!-- Social Grid -->
        <div class="grid grid-cols-4 gap-3">
            <!-- WhatsApp -->
            <button onclick="shareSocial('whatsapp')" class="flex flex-col items-center justify-center gap-3 p-4 bg-transparent border border-white/10 rounded-[1.5rem] hover:bg-white/5 transition-all group">
                <div class="w-12 h-12 bg-[#25D366] rounded-[1rem] flex items-center justify-center shadow-lg shadow-[#25D366]/20 group-hover:scale-110 transition">
                    <i class="fab fa-whatsapp text-white text-2xl"></i>
                </div>
                <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">WhatsApp</span>
            </button>
            <!-- Telegram -->
            <button onclick="shareSocial('telegram')" class="flex flex-col items-center justify-center gap-3 p-4 bg-transparent border border-white/10 rounded-[1.5rem] hover:bg-white/5 transition-all group">
                <div class="w-12 h-12 bg-white rounded-[1rem] flex items-center justify-center shadow-lg shadow-white/10 group-hover:scale-110 transition">
                    <i class="fab fa-telegram-plane text-black text-2xl"></i>
                </div>
                <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Telegram</span>
            </button>
            <!-- Facebook -->
            <button onclick="shareSocial('facebook')" class="flex flex-col items-center justify-center gap-3 p-4 bg-transparent border border-white/10 rounded-[1.5rem] hover:bg-white/5 transition-all group">
                <div class="w-12 h-12 bg-[#1877F2] rounded-[1rem] flex items-center justify-center shadow-lg shadow-[#1877F2]/20 group-hover:scale-110 transition">
                    <i class="fab fa-facebook-f text-white text-2xl"></i>
                </div>
                <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Facebook</span>
            </button>
            <!-- Twitter -->
            <button onclick="shareSocial('twitter')" class="flex flex-col items-center justify-center gap-3 p-4 bg-transparent border border-white/10 rounded-[1.5rem] hover:bg-white/5 transition-all group">
                <div class="w-12 h-12 bg-[#1DA1F2] rounded-[1rem] flex items-center justify-center shadow-lg shadow-[#1DA1F2]/20 group-hover:scale-110 transition">
                    <i class="fab fa-twitter text-white text-2xl"></i>
                </div>
                <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Twitter</span>
            </button>
        </div>
    </div>
</div>

<!-- Package Detail Modal -->
<div id="packageModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
    <div class="bg-[#111] border border-white/10 rounded-[2.5rem] p-8 max-w-md w-full transform scale-95 opacity-0 transition-all duration-300" id="packageModalContent">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h3 class="text-2xl font-black text-white" id="pkgModalName">Package Name</h3>
                <div class="flex flex-col gap-1">
                    <p class="text-accent font-black text-xl" id="pkgModalPrice">Rp 0</p>
                    <p class="text-sm text-gray-500 line-through hidden" id="pkgModalOriginalPrice">Rp 0</p>
                    <p class="text-sm text-green-400 font-bold hidden" id="pkgModalPoinPrice">
                        <i class="fas fa-coins mr-1"></i>0 Poin
                    </p>
                </div>
            </div>
            <button onclick="closePackageModal()" class="text-gray-500 hover:text-white bg-white/5 rounded-full w-10 h-10 flex items-center justify-center transition">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <div class="bg-black/30 rounded-3xl p-6 border border-white/5 mb-8 max-h-[50vh] overflow-y-auto custom-scrollbar">
            <h4 class="text-[10px] font-black text-gray-500 mb-3 uppercase tracking-[0.2em]">Deskripsi Paket</h4>
            <div class="text-gray-300 text-sm leading-relaxed whitespace-pre-line prose prose-invert prose-sm" id="pkgModalDesc">Description goes here...</div>
        </div>

        <button id="pkgModalSelectBtn" class="w-full py-4 bg-accent text-black font-black rounded-2xl hover:brightness-110 transition uppercase tracking-widest text-sm shadow-lg shadow-accent/20">
            Pilih Paket Ini
        </button>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    const shareModal = document.getElementById('shareModal');
    const shareModalContent = document.getElementById('shareModalContent');
    const referralLink = "<?= base_url('layanan/' . $layanan['slug']) ?>?ref=<?= esc($referralCode) ?>";
    const serviceName = "<?= esc($layanan['name']) ?>";

    function openShareModal() {
        shareModal.classList.remove('hidden');
        shareModal.classList.add('flex');
        setTimeout(() => {
            shareModalContent.classList.remove('scale-95', 'opacity-0');
            shareModalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeShareModal() {
        const shareModal = document.getElementById('shareModal');
        const shareModalContent = document.getElementById('shareModalContent');
        shareModalContent.classList.remove('scale-100', 'opacity-100');
        shareModalContent.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            shareModal.classList.add('hidden');
            shareModal.classList.remove('flex');
        }, 300);
    }

    // Close on overlay click
    shareModal.addEventListener('click', (e) => {
        if (e.target === shareModal) closeShareModal();
    });

    // Update shareBtn to always open the custom modal as requested
    document.getElementById('shareBtn').addEventListener('click', () => {
        openShareModal();
    });

    // Advocacy Prerequisite Alert REMOVED - Bundling is now handled at checkout

    async function copyReferralLink() {
        try {
            await navigator.clipboard.writeText(referralLink);
            if (typeof showToast === 'function') {
                showToast('Link referral berhasil disalin!');
            } else {
                alert('Link referral berhasil disalin!');
            }
        } catch (err) {
            console.error('Failed to copy!', err);
        }
    }

    function shareSocial(platform) {
        const text = encodeURIComponent(`Halo! Cek layanan ${serviceName} ini di Almai: ${referralLink}`);
        let url = '';

        switch (platform) {
            case 'whatsapp':
                url = `https://api.whatsapp.com/send?text=${text}`;
                break;
            case 'telegram':
                url = `https://t.me/share/url?url=${encodeURIComponent(referralLink)}&text=${encodeURIComponent('Cek layanan ' + serviceName)}`;
                break;
            case 'facebook':
                url = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(referralLink)}`;
                break;
            case 'twitter':
                url = `https://twitter.com/intent/tweet?text=${text}`;
                break;
        }

        if (url) window.open(url, '_blank');
    }

    // Package Modal Logic
    function openPackageModal(pkg) {
        const modal = document.getElementById('packageModal');
        const content = document.getElementById('packageModalContent');

        document.getElementById('pkgModalName').textContent = pkg.name;
        document.getElementById('pkgModalPrice').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(pkg.price);

        const oriPriceEl = document.getElementById('pkgModalOriginalPrice');
        if (pkg.original_price && pkg.original_price > pkg.price) {
            oriPriceEl.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(pkg.original_price);
            oriPriceEl.classList.remove('hidden');
        } else {
            oriPriceEl.classList.add('hidden');
        }

        const poinPriceEl = document.getElementById('pkgModalPoinPrice');
        if (pkg.poin_price && pkg.poin_price > 0) {
            poinPriceEl.innerHTML = `<i class="fas fa-coins mr-1"></i>${new Intl.NumberFormat('id-ID').format(pkg.poin_price)} Poin`;
            poinPriceEl.classList.remove('hidden');
        } else {
            poinPriceEl.classList.add('hidden');
        }

        document.getElementById('pkgModalDesc').innerHTML = pkg.description || '<p class="italic text-gray-500">Tidak ada deskripsi tambahan.</p>';
        
        // Update select button behavior
        const selectBtn = document.getElementById('pkgModalSelectBtn');
        selectBtn.onclick = () => selectPackage(pkg);

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closePackageModal() {
        const modal = document.getElementById('packageModal');
        const content = document.getElementById('packageModalContent');

        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    function selectPackage(pkg) {
        const buyBtn = document.getElementById('buyBtn');
        if (buyBtn) {
            const url = new URL(buyBtn.href);
            url.searchParams.set('package', pkg.id);
            buyBtn.href = url.toString();
            
            // Highlight selected package in UI
            document.querySelectorAll('[onclick^="openPackageModal"]').forEach(el => {
                el.classList.remove('border-accent', 'bg-accent/10');
                if (el.textContent.includes(pkg.name)) {
                    el.classList.add('border-accent', 'bg-accent/10');
                }
            });

            closePackageModal();
            
            // Scroll to buy button
            buyBtn.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Visual feedback
            buyBtn.classList.add('scale-105');
            setTimeout(() => buyBtn.classList.remove('scale-105'), 300);
        }
    }

    // Close on overlay click for package modal
    document.getElementById('packageModal').addEventListener('click', (e) => {
        if (e.target === document.getElementById('packageModal')) closePackageModal();
    });
</script>
<?= $this->endSection() ?>
