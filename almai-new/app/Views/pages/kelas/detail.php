<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php 
$highlights = is_string($kelas['highlights']) ? json_decode($kelas['highlights'], true) : ($kelas['highlights'] ?? []);
$modeColor = $kelas['mode'] === 'Online' ? 'bg-blue-500' : ($kelas['mode'] === 'Offline' ? 'bg-orange-500' : 'bg-purple-500');
$modeIcon = $kelas['mode'] === 'Online' ? 'fa-video' : ($kelas['mode'] === 'Offline' ? 'fa-building' : 'fa-arrows-rotate');
?>
<!-- Hero -->
<section class="relative pt-32 pb-12 overflow-hidden">
    <div class="absolute inset-0 bg-grid z-0 pointer-events-none"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-accent/20 blur-[120px] rounded-full pointer-events-none"></div>
    <div class="container mx-auto px-6 relative z-10">
        <div class="grid md:grid-cols-3 gap-12">
            <!-- Main Content -->
            <div class="md:col-span-2" data-aos="fade-up">
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="bg-accent text-black px-4 py-1 rounded-full text-sm font-bold"><?= esc($kelas['level']) ?></span>
                    <span class="<?= $modeColor ?> text-white px-4 py-1 rounded-full text-sm font-bold">
                        <i class="fas <?= $modeIcon ?> mr-1"></i><?= esc($kelas['mode']) ?>
                    </span>
                    <span class="bg-white/10 text-white px-4 py-1 rounded-full text-sm"><?= esc($kelas['category']) ?></span>
                </div>
                
                <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tighter"><?= esc($kelas['title']) ?></h1>
                
                <div class="flex items-center gap-4 mb-6">
                    <img src="<?= esc($wpa['photo']) ?>" alt="<?= esc($wpa['name']) ?>" class="w-12 h-12 rounded-full object-cover">
                    <div>
                        <a href="<?= base_url('wpa/' . $wpa['id']) ?>" class="font-bold hover:text-accent transition"><?= esc($wpa['name']) ?></a>
                        <p class="text-gray-400 text-sm"><?= esc($wpa['specialty']) ?></p>
                    </div>
                </div>

                <img src="<?= esc($kelas['thumbnail']) ?>" alt="<?= esc($kelas['title']) ?>" class="w-full h-80 object-cover rounded-2xl mb-8">

                <div class="bg-[#111] rounded-2xl border border-white/10 p-8 mb-8">
                    <h2 class="text-2xl font-bold mb-4">Tentang Kelas</h2>
                    <p class="text-gray-400 leading-relaxed"><?= esc($kelas['description']) ?></p>
                </div>

                <div class="bg-[#111] rounded-2xl border border-white/10 p-8 mb-8">
                    <h2 class="text-2xl font-bold mb-4">Yang Akan Dipelajari</h2>
                    <div class="grid md:grid-cols-2 gap-4">
                        <?php foreach ($highlights as $highlight): ?>
                            <div class="flex items-center gap-3">
                                <i class="fas fa-check-circle text-accent"></i>
                                <span><?= esc($highlight) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Ulasan Section -->
                <div class="bg-[#111] rounded-2xl border border-white/10 p-8" id="ulasan-section">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold">Ulasan</h2>
                        <div class="flex items-center gap-2">
                            <span class="text-accent text-2xl font-bold"><?= $averageRating ?></span>
                            <div class="flex text-yellow-400">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?= $i <= round($averageRating) ? '' : 'opacity-30' ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <span class="text-gray-400 text-sm">(<?= $reviewCount ?> ulasan)</span>
                        </div>
                    </div>

                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="bg-green-500/20 border border-green-500/50 text-green-400 px-4 py-3 rounded-xl mb-4">
                            <i class="fas fa-check-circle mr-2"></i><?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-xl mb-4">
                            <i class="fas fa-exclamation-circle mr-2"></i><?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <!-- Form Ulasan (hanya untuk pembeli yang belum review) -->
                    <?php if (session()->get('isLoggedIn') && $hasPurchased && !$hasReviewed): ?>
                        <form action="<?= base_url('kelas/' . $kelas['id'] . '/ulasan') ?>" method="POST" class="bg-black/50 rounded-xl p-6 mb-6 border border-white/10">
                            <?= csrf_field() ?>
                            <h3 class="font-bold mb-4"><i class="fas fa-pen mr-2 text-accent"></i>Tulis Ulasan Anda</h3>
                            
                            <div class="mb-4">
                                <label class="block text-sm text-gray-400 mb-2">Rating</label>
                                <div class="flex gap-2" id="rating-stars">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <button type="button" onclick="setRating(<?= $i ?>)" class="text-3xl star-btn text-gray-600 hover:text-yellow-400 transition" data-rating="<?= $i ?>">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    <?php endfor; ?>
                                </div>
                                <input type="hidden" name="rating" id="rating-input" value="5" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm text-gray-400 mb-2">Ulasan</label>
                                <textarea name="ulasan" rows="4" class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none transition resize-none" placeholder="Bagikan pengalaman Anda mengikuti kelas ini..." required minlength="10" maxlength="1000"><?= old('ulasan') ?></textarea>
                                <p class="text-xs text-gray-500 mt-1">Minimal 10 karakter</p>
                            </div>

                            <button type="submit" class="px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                                <i class="fas fa-paper-plane mr-2"></i>Kirim Ulasan
                            </button>
                        </form>
                    <?php elseif (session()->get('isLoggedIn') && !$hasPurchased): ?>
                        <div class="bg-blue-500/10 border border-blue-500/30 text-blue-400 px-4 py-3 rounded-xl mb-6 text-sm">
                            <i class="fas fa-info-circle mr-2"></i>Beli kelas ini untuk dapat memberikan ulasan
                        </div>
                    <?php elseif (session()->get('isLoggedIn') && $hasReviewed): ?>
                        <div class="bg-green-500/10 border border-green-500/30 text-green-400 px-4 py-3 rounded-xl mb-6 text-sm">
                            <i class="fas fa-check-circle mr-2"></i>Anda sudah memberikan ulasan untuk kelas ini
                        </div>
                    <?php elseif (!session()->get('isLoggedIn')): ?>
                        <div class="bg-gray-500/10 border border-gray-500/30 text-gray-400 px-4 py-3 rounded-xl mb-6 text-sm">
                            <i class="fas fa-lock mr-2"></i><a href="<?= base_url('login') ?>" class="text-accent hover:underline">Login</a> untuk memberikan ulasan
                        </div>
                    <?php endif; ?>

                    <!-- Daftar Ulasan -->
                    <?php if (!empty($ulasan)): ?>
                        <div class="space-y-4">
                            <?php foreach ($ulasan as $review): ?>
                                <div class="bg-black/30 rounded-xl p-4 border border-white/5">
                                    <div class="flex items-start gap-4">
                                        <?php 
                                        $avatarUrl = base_url('images/default-avatar.png');
                                        if (!empty($review['user_photo'])) {
                                            $avatarUrl = base_url('file/' . $review['user_photo']);
                                        }
                                        ?>
                                        <img src="<?= $avatarUrl ?>" alt="<?= esc($review['user_name']) ?>" class="w-12 h-12 rounded-full object-cover">
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="font-bold"><?= esc($review['user_name']) ?></span>
                                                <span class="text-xs text-gray-500"><?= date('d M Y', strtotime($review['created_at'])) ?></span>
                                            </div>
                                            <div class="flex text-yellow-400 text-sm mb-2">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <i class="fas fa-star <?= $i <= $review['rating'] ? '' : 'opacity-30' ?>"></i>
                                                <?php endfor; ?>
                                            </div>
                                            <p class="text-gray-300 text-sm"><?= esc($review['ulasan']) ?></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-comments text-4xl mb-3 opacity-30"></i>
                            <p>Belum ada ulasan untuk kelas ini</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sidebar -->
            <div data-aos="fade-up" data-aos-delay="100">
                <div class="bg-[#111] rounded-2xl border border-white/10 p-6 sticky top-28">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-gray-500 line-through text-lg">Rp <?= number_format($kelas['original_price'], 0, ',', '.') ?></span>
                        <span class="bg-red-500/20 text-red-400 px-3 py-1 rounded-full text-sm">
                            -<?= round((($kelas['original_price'] - $kelas['price']) / $kelas['original_price']) * 100) ?>%
                        </span>
                    </div>
                    <div class="text-4xl font-bold text-accent mb-6">Rp <?= number_format($kelas['price'], 0, ',', '.') ?></div>
                    
                    <div class="space-y-4 mb-6 text-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400"><i class="fas fa-clock mr-2"></i>Durasi</span>
                            <span class="font-bold"><?= esc($kelas['duration']) ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400"><i class="fas fa-book mr-2"></i>Modul</span>
                            <span class="font-bold"><?= esc($kelas['modules']) ?> Modul</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400"><i class="fas fa-users mr-2"></i>Peserta</span>
                            <span class="font-bold"><?= number_format($kelas['students']) ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400"><i class="fas fa-star mr-2"></i>Rating</span>
                            <span class="font-bold text-accent">⭐ <?= esc($kelas['rating']) ?></span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400"><i class="fas fa-map-marker-alt mr-2"></i>Lokasi</span>
                            <span class="font-bold"><?= esc($kelas['location']) ?></span>
                        </div>
                        <?php if ($kelas['type'] === 'live' && !empty($kelas['schedule'])): ?>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400"><i class="fas fa-calendar mr-2"></i>Jadwal</span>
                            <span class="font-bold text-xs"><?= esc($kelas['schedule']) ?></span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if (session()->get('isLoggedIn')): ?>
                    <a href="<?= base_url('checkout/' . $kelas['id']) ?>" class="block text-center py-4 bg-accent text-black rounded-xl font-bold hover:bg-white transition mb-3 shadow-[0_0_20px_rgba(51,232,24,0.3)]">
                        <i class="fas fa-shopping-cart mr-2"></i> Beli Sekarang
                    </a>
                    <?php else: ?>
                    <a href="<?= base_url('login?redirect=' . urlencode('checkout/' . $kelas['id'])) ?>" class="block text-center py-4 bg-accent text-black rounded-xl font-bold hover:bg-white transition mb-3 shadow-[0_0_20px_rgba(51,232,24,0.3)]">
                        <i class="fas fa-shopping-cart mr-2"></i> Beli Sekarang
                    </a>
                    <?php endif; ?>
                    
                    <button onclick="openShareModal()" class="w-full py-3 border border-white/20 text-white font-medium rounded-xl hover:border-accent hover:text-accent transition flex items-center justify-center gap-2">
                        <i class="fas fa-share-alt"></i> Bagikan Kelas
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Share Modal -->
<div id="shareModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 max-w-md w-full transform scale-95 opacity-0 transition-all duration-300" id="shareModalContent">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold">Bagikan Kelas</h3>
            <button onclick="closeShareModal()" class="text-gray-400 hover:text-white">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <?php if (session()->get('isLoggedIn')): ?>
        <!-- Referral Info (shown if logged in) -->
        <div class="bg-gradient-to-r from-accent/20 to-green-600/20 border border-accent/30 rounded-xl p-4 mb-6">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 bg-accent/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-gift text-accent"></i>
                </div>
                <div>
                    <p class="font-bold text-sm">Kode Referral Anda</p>
                    <p class="text-xs text-gray-400">Dapatkan poin dari setiap pembelian via link Anda</p>
                </div>
            </div>
            <div class="flex items-center gap-2 mt-3">
                <input type="text" id="referralCodeDisplay" readonly value="<?= esc(session()->get('referralCode') ?? strtoupper(substr(md5(session()->get('userId')), 0, 8))) ?>" class="flex-1 px-4 py-2 bg-black border border-white/20 rounded-lg text-sm font-mono text-accent">
                <button onclick="copyReferralCode()" class="px-4 py-2 bg-accent text-black rounded-lg hover:bg-white transition">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Share Link -->
        <div class="mb-6">
            <label class="block text-sm text-gray-400 mb-2">Link Kelas</label>
            <div class="flex items-center gap-2">
                <input type="text" id="shareLink" readonly value="" class="flex-1 px-4 py-3 bg-black border border-white/20 rounded-xl text-sm truncate">
                <button onclick="copyShareLink()" class="px-4 py-3 bg-white/10 hover:bg-accent hover:text-black rounded-xl transition" title="Copy Link">
                    <i class="fas fa-copy"></i>
                </button>
            </div>
        </div>

        <!-- Share Buttons -->
        <div class="grid grid-cols-4 gap-3">
            <button onclick="shareToWhatsApp()" class="flex flex-col items-center p-4 bg-[#25D366]/20 hover:bg-[#25D366]/30 rounded-xl transition group">
                <div class="w-12 h-12 bg-[#25D366] rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <i class="fab fa-whatsapp text-white text-xl"></i>
                </div>
                <span class="text-xs">WhatsApp</span>
            </button>
            <button onclick="shareToTelegram()" class="flex flex-col items-center p-4 bg-[#0088CC]/20 hover:bg-[#0088CC]/30 rounded-xl transition group">
                <div class="w-12 h-12 bg-[#0088CC] rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <i class="fab fa-telegram-plane text-white text-xl"></i>
                </div>
                <span class="text-xs">Telegram</span>
            </button>
            <button onclick="shareToFacebook()" class="flex flex-col items-center p-4 bg-[#1877F2]/20 hover:bg-[#1877F2]/30 rounded-xl transition group">
                <div class="w-12 h-12 bg-[#1877F2] rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <i class="fab fa-facebook-f text-white text-xl"></i>
                </div>
                <span class="text-xs">Facebook</span>
            </button>
            <button onclick="shareToTwitter()" class="flex flex-col items-center p-4 bg-[#1DA1F2]/20 hover:bg-[#1DA1F2]/30 rounded-xl transition group">
                <div class="w-12 h-12 bg-[#1DA1F2] rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition">
                    <i class="fab fa-twitter text-white text-xl"></i>
                </div>
                <span class="text-xs">Twitter</span>
            </button>
        </div>
    </div>
</div>

<!-- Copy Toast -->
<div id="copyToast" class="hidden fixed bottom-4 left-1/2 -translate-x-1/2 px-6 py-3 bg-accent text-black font-bold rounded-full shadow-lg z-[110]">
    <i class="fas fa-check mr-2"></i> Link berhasil disalin!
</div>

<!-- Related Kelas -->
<?php if (!empty($relatedKelas)): ?>
<section class="py-16 bg-card-bg">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold mb-8 tracking-tighter" data-aos="fade-up">Kelas <span class="text-accent">Terkait</span></h2>
        <div class="grid md:grid-cols-3 gap-6">
            <?php foreach ($relatedKelas as $related): ?>
                <?= view('partials/cards/kelas_card', ['kelas' => $related]) ?>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<script>
const kelasTitle = <?= json_encode($kelas['title']) ?>;
const baseUrl = '<?= current_url() ?>';
const isLoggedIn = <?= session()->get('isLoggedIn') ? 'true' : 'false' ?>;
const referralCode = '<?= esc(session()->get('referralCode') ?? (session()->get('userId') ? strtoupper(substr(md5(session()->get('userId')), 0, 8)) : '')) ?>';

function getShareLink() {
    if (isLoggedIn && referralCode) {
        return `${baseUrl}?ref=${referralCode}`;
    }
    return baseUrl;
}

function openShareModal() {
    const modal = document.getElementById('shareModal');
    const content = document.getElementById('shareModalContent');
    const shareLinkInput = document.getElementById('shareLink');
    
    // Set share link with referral code if logged in
    shareLinkInput.value = getShareLink();
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function closeShareModal() {
    const modal = document.getElementById('shareModal');
    const content = document.getElementById('shareModalContent');
    
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
}

function showToast(message) {
    const toast = document.getElementById('copyToast');
    toast.innerHTML = `<i class="fas fa-check mr-2"></i> ${message}`;
    toast.classList.remove('hidden');
    
    setTimeout(() => {
        toast.classList.add('hidden');
    }, 2000);
}

function copyShareLink() {
    const shareLink = document.getElementById('shareLink');
    shareLink.select();
    document.execCommand('copy');
    showToast('Link berhasil disalin!');
}

function copyReferralCode() {
    const refCode = document.getElementById('referralCodeDisplay');
    refCode.select();
    document.execCommand('copy');
    showToast('Kode referral disalin!');
}

function shareToWhatsApp() {
    const text = `Cek kelas keren ini: ${kelasTitle}\n${getShareLink()}`;
    window.open(`https://wa.me/?text=${encodeURIComponent(text)}`, '_blank');
}

function shareToTelegram() {
    window.open(`https://t.me/share/url?url=${encodeURIComponent(getShareLink())}&text=${encodeURIComponent(kelasTitle)}`, '_blank');
}

function shareToFacebook() {
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(getShareLink())}`, '_blank');
}

function shareToTwitter() {
    const text = `Cek kelas keren ini: ${kelasTitle}`;
    window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(getShareLink())}`, '_blank');
}

// Close modal when clicking outside
document.getElementById('shareModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeShareModal();
    }
});

// Rating stars functionality
function setRating(rating) {
    document.getElementById('rating-input').value = rating;
    const stars = document.querySelectorAll('#rating-stars .star-btn');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('text-gray-600');
            star.classList.add('text-yellow-400');
        } else {
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-600');
        }
    });
}

// Initialize rating to 5 stars
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('rating-stars')) {
        setRating(5);
    }
});
</script>
<?= $this->endSection() ?>
