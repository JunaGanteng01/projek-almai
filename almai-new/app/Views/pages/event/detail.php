<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    /* CKEditor Content List Fixes */
    .prose ul,
    .prose ol {
        margin-top: 1em !important;
        margin-bottom: 1em !important;
        margin-left: 1.5rem !important;
    }

    .prose ul {
        list-style-type: disc !important;
    }

    .prose ol {
        list-style-type: decimal !important;
    }

    .prose li {
        margin-bottom: 0.5em !important;
        padding-left: 0.5em !important;
    }

    .prose blockquote {
        border-left: 4px solid #33e818;
        padding-left: 1rem;
        font-style: italic;
        color: #9ca3af;
    }

    .prose img {
        border-radius: 0.5rem;
        margin-top: 1em;
        margin-bottom: 1em;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$placeholders = [
    'Webinar' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=450&fit=crop',
    'Workshop' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=800&h=450&fit=crop',
    'Event' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800&h=450&fit=crop',
];
$thumbnail = !empty($layanan['thumbnail']) ? $layanan['thumbnail'] : ($placeholders[$layanan['subcategory']] ?? $placeholders['Event']);

$color = 'accent';
?>

<!-- Breadcrumb -->
<section class="pt-28 pb-4">
    <div class="container mx-auto px-6">
        <nav class="flex items-center gap-2 text-sm text-gray-400">
            <a href="<?= base_url('/') ?>" class="hover:text-white transition">Home</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <a href="<?= base_url('event') ?>" class="hover:text-white transition">Event</a>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-white"><?= esc($layanan['name']) ?></span>
        </nav>
    </div>
</section>

<!-- Main Content -->
<section class="py-8">
    <div class="container mx-auto px-6">
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Left Content -->
            <div class="lg:col-span-2">
                <!-- Thumbnail -->
                <div class="relative rounded-2xl overflow-hidden mb-6">
                    <img src="<?= $thumbnail ?>" alt="<?= esc($layanan['name']) ?>" class="w-full aspect-video object-cover">

                    <!-- Badges -->
                    <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                        <span class="px-3 py-1 bg-black/70 backdrop-blur-sm rounded-full text-sm font-medium"><?= esc($layanan['category']) ?></span>
                        <?php
                        $modeColors = ['Online' => 'bg-accent', 'Offline' => 'bg-red-500', 'Hybrid' => 'bg-purple-500'];
                        $modeColor = $modeColors[$layanan['mode']] ?? 'bg-gray-500';
                        ?>
                        <span class="px-3 py-1 <?= $modeColor ?> text-black rounded-full text-sm font-medium">
                            <i class="fas <?= $layanan['mode'] === 'Online' ? 'fa-video' : ($layanan['mode'] === 'Offline' ? 'fa-building' : 'fa-arrows-rotate') ?> mr-1"></i>
                            <?= esc($layanan['mode']) ?>
                        </span>
                    </div>

                    <?php if (!empty($layanan['is_premium'])): ?>
                        <div class="absolute top-4 right-4">
                            <span class="px-3 py-1 bg-accent text-black rounded-full text-sm font-bold">PRO</span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Category & Title -->
                <div class="mb-6">
                    <p class="text-<?= $color ?> text-sm font-medium mb-2"><?= esc($layanan['category']) ?> • <?= esc($layanan['subcategory']) ?></p>
                    <h1 class="text-3xl md:text-4xl font-bold mb-4"><?= esc($layanan['name']) ?></h1>
                </div>

                <!-- Provider Info -->
                <div class="flex items-center gap-4 p-4 bg-[#111] border border-white/10 rounded-xl mb-6">
                    <?php 
                        $providerPhoto = '';
                        $providerName = 'Tim Almai';
                        $providerUrl = '';
                        
                        if (!empty($layanan['cwpa_name'])) {
                            $providerName = $layanan['cwpa_name'];
                            $providerPhoto = $layanan['cwpa_photo'] ?? '';
                            $providerUrl = !empty($layanan['cwpa_slug']) ? base_url('cwpa/' . $layanan['cwpa_slug']) : '';
                        } else {
                            $providerName = $layanan['wpa_name'] ?? 'Tim Almai';
                            $providerPhoto = $layanan['wpa_photo'] ?? '';
                            $providerUrl = !empty($layanan['wpa_slug']) ? base_url('wpa/' . $layanan['wpa_slug']) : '';
                        }

                        // Append base_url if the photo doesn't start with http
                        if (!empty($providerPhoto) && strpos($providerPhoto, 'http') !== 0) {
                            $providerPhoto = base_url($providerPhoto);
                        }
                    ?>
                    
                    <?php if (!empty($providerPhoto)): ?>
                        <img src="<?= esc($providerPhoto) ?>" alt="" class="w-14 h-14 rounded-full object-cover shrink-0">
                    <?php else: ?>
                        <div class="w-14 h-14 rounded-full bg-accent/20 flex items-center justify-center shrink-0">
                            <i class="fas fa-user text-accent text-2xl"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div>
                        <p class="text-sm text-gray-400">Dibimbing oleh</p>
                        <?php if (!empty($providerUrl)): ?>
                            <a href="<?= esc($providerUrl) ?>" class="font-bold hover:text-accent transition">
                                <?= esc($providerName) ?>
                            </a>
                        <?php else: ?>
                            <p class="font-bold"><?= esc($providerName) ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="ml-auto flex items-center gap-1 text-yellow-500">
                        <i class="fas fa-star"></i>
                        <span class="font-bold"><?= number_format($averageRating, 1) ?></span>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-8 text-gray-300 leading-relaxed text-lg prose prose-invert max-w-none">
                    <?= $layanan['description'] ?>
                </div>

                <!-- Layanan Utama / Benefit -->
                <?php if (!empty($extendedInfo['layanan_utama'])): ?>
                    <div class="mb-8 p-6 bg-accent/5 border border-accent/20 rounded-2xl">
                        <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                            <i class="fas fa-check-circle text-accent"></i>
                            Manfaat Event
                        </h3>
                        <div class="grid md:grid-cols-2 gap-4">
                            <?php
                            $layananUtama = explode("\n", $extendedInfo['layanan_utama']);
                            foreach ($layananUtama as $point):
                                if (trim($point)):
                            ?>
                                    <div class="flex items-start gap-3">
                                        <div class="mt-1.5 w-1.5 h-1.5 rounded-full bg-accent shrink-0"></div>
                                        <span class="text-gray-300"><?= esc(trim($point)) ?></span>
                                    </div>
                            <?php
                                    endif;
                            endforeach;
                            ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Fitur Unggulan (Featured Buttons) -->
                <?php
                $fiturUnggulan = [];
                if (!empty($layanan['fitur_unggulan'])) {
                    $fiturUnggulan = is_string($layanan['fitur_unggulan'])
                        ? json_decode($layanan['fitur_unggulan'], true)
                        : $layanan['fitur_unggulan'];
                }
                ?>
                <?php if (!empty($fiturUnggulan) && is_array($fiturUnggulan)): ?>
                    <div class="mb-8">
                        <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                            <i class="fas fa-star text-accent"></i>
                            Fitur Unggulan
                        </h3>
                        <div class="flex flex-wrap gap-3">
                            <?php foreach ($fiturUnggulan as $fitur): ?>
                                <?php if (!empty($fitur['name'])): ?>
                                    <?php if (!empty($fitur['link'])): ?>
                                        <a href="<?= esc($fitur['link']) ?>"
                                            target="_blank"
                                            class="px-5 py-2.5 bg-accent/10 border border-accent/30 text-accent rounded-lg hover:bg-accent hover:text-black transition font-medium flex items-center gap-2 text-sm">
                                            <i class="fas fa-external-link-alt text-xs"></i>
                                            <?= esc($fitur['name']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="px-5 py-2.5 bg-white/5 border border-white/10 text-gray-300 rounded-lg font-medium text-sm">
                                            <i class="fas fa-check text-accent mr-1"></i>
                                            <?= esc($fitur['name']) ?>
                                        </span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right Sidebar - Sticky -->
            <div class="lg:col-span-1">
                <div class="sticky top-24">
                    <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
                        <!-- Price -->
                        <div class="mb-6">
                            <?php if (!empty($packages)): ?>
                                <div class="space-y-3 mb-4">
                                    <p class="text-sm text-gray-400 mb-2">Pilih Paket:</p>
                                    <?php foreach ($packages as $index => $pkg): ?>
                                        <label class="block relative cursor-pointer group">
                                            <input type="radio" name="package_selection" value="<?= $pkg['id'] ?>"
                                                class="peer sr-only"
                                                <?= $index === 0 ? 'checked' : '' ?>
                                                data-package='<?= json_encode($pkg) ?>'>

                                            <div class="p-3 bg-black/30 border border-white/10 rounded-xl peer-checked:border-accent peer-checked:bg-accent/10 transition-all hover:bg-white/5"
                                                onclick='openPackageModal(<?= json_encode($pkg) ?>)'>
                                                <div class="flex justify-between items-center">
                                                    <div class="flex items-center gap-2">
                                                        <span class="font-bold text-sm peer-checked:text-accent"><?= esc($pkg['name']) ?></span>
                                                        <i class="fas fa-info-circle text-gray-500 text-xs hover:text-accent"></i>
                                                    </div>
                                                    <div class="text-right">
                                                        <?php if ($pkg['original_price'] && $pkg['original_price'] > $pkg['price']): ?>
                                                            <div class="text-[10px] text-gray-500 line-through">Rp <?= number_format($pkg['original_price'], 0, ',', '.') ?></div>
                                                        <?php endif; ?>
                                                        <div class="font-bold text-[#33e818] uppercase">
                                                            <?= $pkg['price'] > 0 ? 'Rp ' . number_format($pkg['price'], 0, ',', '.') : 'Rp 0' ?>
                                                        </div>
                                                        <?php if (!empty($pkg['poin_price'])): ?>
                                                            <div class="text-[10px] text-[#33e818] font-bold"><i class="fas fa-coins mr-1"></i><?= number_format($pkg['poin_price'], 0, ',', '.') ?> Almai Poin</div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            <?php elseif (isset($layanan['price']) || !empty($layanan['poin_price'])): ?>
                                <div class="mb-4">
                                    <?php if (empty($layanan['price']) || $layanan['price'] == 0): ?>
                                        <p class="text-3xl font-bold text-[#33e818] mb-1">Rp 0</p>
                                    <?php else: ?>
                                        <?php if (!empty($layanan['original_price']) && $layanan['original_price'] > $layanan['price']): ?>
                                            <?php $discount = round((($layanan['original_price'] - $layanan['price']) / $layanan['original_price']) * 100); ?>
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="px-2 py-1 bg-red-500 text-white text-xs font-bold rounded">-<?= $discount ?>%</span>
                                                <span class="text-gray-500 line-through text-sm">Rp <?= number_format($layanan['original_price'], 0, ',', '.') ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <p class="text-3xl font-bold text-[#33e818] mb-1">Rp <?= number_format($layanan['price'], 0, ',', '.') ?></p>
                                    <?php endif; ?>
                                </div>

                                <?php if (!empty($layanan['poin_price'])): ?>
                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 border border-white/20 rounded-lg text-xs font-bold text-[#33e818] mb-6">
                                        <i class="fas fa-coins text-accent"></i>
                                        <span><?= number_format($layanan['poin_price'], 0, ',', '.') ?> Almai Poin</span>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <p class="text-3xl font-bold text-[#33e818] mb-6">Rp 0</p>
                            <?php endif; ?>
                        </div>

                        <!-- Stats Grid -->
                        <div class="grid grid-cols-2 gap-3 mb-6">
                            <!-- Sesi -->
                            <div class="text-center p-3 bg-[#0c0c0c] border border-white/5 rounded-xl flex flex-col items-center justify-center min-h-[90px]">
                                <i class="fas fa-desktop text-white text-lg mb-2"></i>
                                <p class="text-base font-bold text-white leading-none mb-1">
                                    <?= !empty($layanan['total_sessions']) ? $layanan['total_sessions'] : '1' ?>
                                </p>
                                <p class="text-[10px] text-gray-500 font-medium">Sesi</p>
                            </div>
                            
                            <!-- Peserta -->
                            <div class="text-center p-3 bg-[#0c0c0c] border border-white/5 rounded-xl flex flex-col items-center justify-center min-h-[90px]">
                                <i class="fas fa-users text-white text-lg mb-2"></i>
                                <p class="text-base font-bold text-white leading-none mb-1">
                                    <?php if (!empty($layanan['max_participants']) && $layanan['max_participants'] > 0): ?>
                                        <?= number_format($layanan['students']) ?> <span class="text-gray-500 text-xs font-normal">/ <?= number_format($layanan['max_participants']) ?></span>
                                    <?php else: ?>
                                        <?= number_format($layanan['students']) ?>+
                                    <?php endif; ?>
                                </p>
                                <p class="text-[10px] text-gray-500 font-medium">Peserta</p>
                            </div>

                            <!-- Lokasi -->
                            <div class="text-center p-3 bg-[#0c0c0c] border border-white/5 rounded-xl flex flex-col items-center justify-center min-h-[90px]">
                                <i class="fas fa-map-marker-alt text-white text-lg mb-2"></i>
                                <p class="text-base font-bold text-white leading-none mb-1"><?= esc($layanan['mode'] ?? 'Online') ?></p>
                                <p class="text-[10px] text-gray-500 font-medium">Lokasi</p>
                            </div>

                            <!-- Tanggal -->
                            <div class="text-center p-3 bg-[#0c0c0c] border border-white/5 rounded-xl flex flex-col items-center justify-center min-h-[90px]">
                                <i class="fas fa-calendar-alt text-white text-lg mb-2"></i>
                                <p class="text-sm font-bold text-white leading-tight mb-1">
                                    <?= date('d M', strtotime($layanan['date'])) ?>
                                    <?php if (!empty($layanan['event_end_date'])): ?>
                                        - <?= date('d M', strtotime($layanan['event_end_date'])) ?>
                                    <?php endif; ?>
                                </p>
                                <p class="text-[10px] text-gray-500 font-medium">Tanggal</p>
                            </div>
                        </div>


                        <!-- CTA Buttons -->
                        <div class="space-y-3">
                            <?php
                            $isPro = !empty($layanan['is_premium']);
                            $isLoggedIn = session()->get('isLoggedIn');
                            $userLevelId = session()->get('level_id') ?? 1;

                            // User is considered PRO if level_id >= 2
                            $userIsPro = $userLevelId >= 2;

                            // Keep the selected product context across authentication.
                            $checkoutPath = 'checkout/event/' . $layanan['slug'];
                            $queryParams = [];
                            if (!empty($packages)) $queryParams['package'] = $packages[0]['id'];
                            if ($ref = service('request')->getGet('ref')) $queryParams['ref'] = $ref;
                            if ($voucher = service('request')->getGet('voucher')) $queryParams['voucher'] = $voucher;
                            if (!empty($queryParams)) $checkoutPath .= '?' . http_build_query($queryParams);

                            $buyUrl = base_url($checkoutPath);

                            // Button UI Defaults
                            $buttonText = 'Beli Sekarang';
                            $buttonIcon = 'fa-shopping-cart';
                            $showWarning = false;
                            $warningMessage = '';

                            // Authentication is required before every event purchase.
                            if (!$isLoggedIn) {
                                $buyUrl = base_url('login?redirect=' . rawurlencode($checkoutPath));
                                $buttonText = 'Login untuk Membeli';
                                $buttonIcon = 'fa-lock';
                            }

                            // PRO Logic Check
                            if ($isPro) {
                                if (!$isLoggedIn) {
                                    $buttonText = 'Login untuk Membeli (Wajib KYC)';
                                    $showWarning = true;
                                    $warningMessage = 'Layanan PRO memerlukan identifikasi (KYC)';
                                } elseif (!$userIsPro) {
                                    $buyUrl = base_url('user/kyc');
                                    $buttonText = 'Upgrade ke PRO (Wajib KYC)';
                                    $buttonIcon = 'fa-id-card';
                                    $showWarning = true;
                                    $warningMessage = 'Layanan ini khusus Member PRO. Silakan verifikasi identitas (KYC) Anda.';
                                }
                            }

                            ?>

                            <a href="<?= $buyUrl ?>"
                                id="buyBtn"
                                class="block text-center py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                                <i class="fas <?= $buttonIcon ?> mr-2"></i>
                                <?= $buttonText ?>
                            </a>
                            <?php if ($showWarning): ?>
                                <p class="text-xs text-center text-gray-400 mt-2">
                                    <i class="fas fa-info-circle mr-1"></i> <?= $warningMessage ?>
                                </p>
                            <?php endif; ?>
                            
                            <button onclick="openShareModal()"
                                class="w-full py-4 border border-white/20 rounded-xl hover:border-accent hover:text-accent transition flex items-center justify-center gap-2">
                                <i class="fas fa-share-alt"></i> Bagikan
                            </button>
                        </div>

                        <!-- Guarantee -->
                        <div class="mt-6 pt-6 border-t border-white/10">
                            <div class="flex items-center gap-3 text-sm text-gray-400">
                                <i class="fas fa-shield-alt text-accent"></i>
                                <span>Event resmi dari PT. Alma Indonesia Raya yang terdaftar di BAPPEBTI</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Ulasan Section -->
<section class="py-8">
    <div class="container mx-auto px-6">
        <div class="lg:w-2/3">
            <div class="bg-[#111] border border-white/10 rounded-2xl p-6" id="ulasan-section">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold">Ulasan</h2>
                    <div class="flex items-center gap-2">
                        <span class="text-accent text-2xl font-bold"><?= number_format($averageRating, 1) ?></span>
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

                <!-- Form Ulasan -->
                <?php if (session()->get('isLoggedIn') && $hasPurchased && !$hasReviewed): ?>
                    <div class="bg-white/5 rounded-xl p-6 mb-8 border border-white/10">
                        <h3 class="font-bold mb-4">Berikan Ulasan Anda</h3>
                        <form action="<?= base_url('event/submit-review') ?>" method="post">
                            <?= csrf_field() ?>
                            <input type="hidden" name="event_id" value="<?= esc($layanan['id']) ?>">
                            <input type="hidden" name="event_title" value="<?= esc($layanan['name']) ?>">

                            <div class="mb-4">
                                <label class="block text-sm text-gray-400 mb-2">Rating</label>
                                <div class="flex gap-2 text-2xl text-gray-600 hover:text-yellow-400 cursor-pointer" id="ratingStars">
                                    <i class="fas fa-star hover:text-yellow-400 transition" data-val="1"></i>
                                    <i class="fas fa-star hover:text-yellow-400 transition" data-val="2"></i>
                                    <i class="fas fa-star hover:text-yellow-400 transition" data-val="3"></i>
                                    <i class="fas fa-star hover:text-yellow-400 transition" data-val="4"></i>
                                    <i class="fas fa-star hover:text-yellow-400 transition" data-val="5"></i>
                                </div>
                                <input type="hidden" name="rating" id="ratingInput" required>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm text-gray-400 mb-2">Ulasan</label>
                                <textarea name="ulasan" rows="3" required
                                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none"
                                    placeholder="Ceritakan pengalaman Anda..."></textarea>
                            </div>

                            <button type="submit" class="px-6 py-2 bg-accent text-black font-bold rounded-lg hover:bg-white transition">
                                Kirim Ulasan
                            </button>
                        </form>
                    </div>

                    <script>
                        document.querySelectorAll('#ratingStars i').forEach(star => {
                            star.addEventListener('click', function() {
                                const val = this.dataset.val;
                                document.getElementById('ratingInput').value = val;

                                document.querySelectorAll('#ratingStars i').forEach(s => {
                                    if (s.dataset.val <= val) {
                                        s.classList.add('text-yellow-400');
                                        s.classList.remove('text-gray-600');
                                    } else {
                                        s.classList.remove('text-yellow-400');
                                        s.classList.add('text-gray-600');
                                    }
                                });
                            });
                        });
                    </script>
                <?php endif; ?>

                <!-- List Ulasan -->
                <div class="space-y-4">
                    <?php if (empty($reviews)): ?>
                        <p class="text-gray-500 text-center py-8">Belum ada ulasan. Jadilah yang pertama memberikan ulasan!</p>
                    <?php else: ?>
                        <?php foreach ($reviews as $review): ?>
                            <div class="bg-black/30 rounded-xl p-4 border border-white/5">
                                <div class="flex items-start gap-4">
                                    <?php if (!empty($review['user_photo']) && strpos($review['user_photo'], 'http') === 0): ?>
                                        <img src="<?= esc($review['user_photo']) ?>" class="w-12 h-12 rounded-full object-cover">
                                    <?php elseif (!empty($review['user_photo'])): ?>
                                        <img src="<?= base_url('file/uploads/avatars/' . $review['user_photo']) ?>" class="w-12 h-12 rounded-full object-cover">
                                    <?php else: ?>
                                        <div class="w-12 h-12 bg-accent/20 rounded-full flex items-center justify-center">
                                            <span class="text-accent font-bold"><?= strtoupper(substr($review['user_name'], 0, 1)) ?></span>
                                        </div>
                                    <?php endif; ?>

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
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Share Modal -->
<div id="shareModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 max-w-md w-full transform scale-95 opacity-0 transition-all duration-300" id="shareModalContent">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold">Bagikan Event</h3>
            <button onclick="closeShareModal()" class="text-gray-400 hover:text-white">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <?php if (session()->get('isLoggedIn')): ?>
            <!-- Referral Info -->
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
                    <input type="text" id="referralLinkInput" readonly value="" class="flex-1 px-4 py-2 bg-black border border-white/20 rounded-lg text-sm font-mono text-accent">
                    <button onclick="copyReferralLink()" class="px-4 py-2 bg-accent text-black rounded-lg hover:bg-white transition">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <!-- Share Link -->
        <div class="mb-6">
            <label class="block text-sm text-gray-400 mb-2">Link Event</label>
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

<!-- Package Detail Modal -->
<div id="packageModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 max-w-md w-full transform scale-95 opacity-0 transition-all duration-300" id="packageModalContent">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h3 class="text-xl font-bold" id="pkgModalName">Package Name</h3>
                <div class="flex flex-col gap-0.5">
                    <p class="text-accent font-bold text-lg" id="pkgModalPrice">Rp 0</p>
                    <p class="text-xs text-gray-500 line-through hidden" id="pkgModalOriginalPrice">Rp 0</p>
                    <p class="text-sm text-green-400 font-bold hidden" id="pkgModalPoinPrice">
                        <i class="fas fa-coins mr-1"></i>0 Poin
                    </p>
                </div>
            </div>
            <button onclick="closePackageModal()" class="text-gray-400 hover:text-white bg-white/5 rounded-full w-8 h-8 flex items-center justify-center transition">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="bg-black/30 rounded-xl p-4 border border-white/5 mb-6 max-h-[60vh] overflow-y-auto">
            <h4 class="text-sm font-bold text-gray-400 mb-2 uppercase tracking-wider">Deskripsi Paket</h4>
            <p class="text-gray-300 text-sm leading-relaxed whitespace-pre-line" id="pkgModalDesc">Description goes here...</p>
        </div>

        <button onclick="closePackageModal()" class="w-full py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
            Pilih Paket Ini
        </button>
    </div>
</div>

<!-- Copy Toast -->
<div id="copyToast" class="hidden fixed bottom-4 left-1/2 -translate-x-1/2 px-6 py-3 bg-accent text-black font-bold rounded-full shadow-lg z-[110]">
    <i class="fas fa-check mr-2"></i> Link berhasil disalin!
</div>

<?php
// Get correct referral code directly from DB
$jsReferralCode = '';
if (session()->get('isLoggedIn')) {
    $db = \Config\Database::connect();
    $uData = $db->table('users')->select('code_referral, referral_code')->where('id', session()->get('userId'))->get()->getRowArray();
    $jsReferralCode = $uData['code_referral'] ?? ($uData['referral_code'] ?? '');
}
// Get active voucher from URL
$activeVoucher = service('request')->getGet('voucher');
?>
<script>
    const layananTitle = <?= json_encode($layanan['name']) ?>;
    const baseUrl = '<?= current_url() ?>';
    const isLoggedIn = <?= session()->get('isLoggedIn') ? 'true' : 'false' ?>;
    const referralCode = '<?= esc($jsReferralCode) ?>';
    const activeVoucher = '<?= esc($activeVoucher) ?>';

    function getShareLink() {
        let url = new URL(baseUrl);

        // Add referral code
        if (isLoggedIn && referralCode) {
            url.searchParams.set('ref', referralCode);
        }

        // Add voucher if exists
        if (activeVoucher) {
            url.searchParams.set('voucher', activeVoucher);
        }

        return url.toString();
    }

    // Package Selection Listener
    document.querySelectorAll('input[name="package_selection"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const packageId = this.value;
            const buyBtn = document.getElementById('buyBtn');
            if (buyBtn) {
                const currentUrl = new URL(buyBtn.href);
                currentUrl.searchParams.set('package', packageId);
                buyBtn.href = currentUrl.toString();
            }
        });
    });

    function openShareModal() {
        const modal = document.getElementById('shareModal');
        const content = document.getElementById('shareModalContent');

        const referralInput = document.getElementById('referralLinkInput');
        if (referralInput) {
            referralInput.value = getShareLink();
        }

        const shareLinkInput = document.getElementById('shareLink');
        if (shareLinkInput) {
            shareLinkInput.value = getShareLink();
        }

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
        if (shareLink) {
            shareLink.select();
            document.execCommand('copy');
            showToast('Link berhasil disalin!');
        }
    }

    function copyReferralLink() {
        const refInput = document.getElementById('referralLinkInput');
        if (refInput) {
            refInput.select();
            document.execCommand('copy');
            showToast('Link referral disalin!');
        }
    }

    function shareToWhatsApp() {
        const text = `Cek event keren ini: ${layananTitle}\n${getShareLink()}`;
        window.open(`https://wa.me/?text=${encodeURIComponent(text)}`, '_blank');
    }

    function shareToTelegram() {
        window.open(`https://t.me/share/url?url=${encodeURIComponent(getShareLink())}&text=${encodeURIComponent(layananTitle)}`, '_blank');
    }

    function shareToFacebook() {
        window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(getShareLink())}`, '_blank');
    }

    function shareToTwitter() {
        const text = `Cek event keren ini: ${layananTitle}`;
        window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(getShareLink())}`, '_blank');
    }

    document.getElementById('shareModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeShareModal();
        }
    });
    
    document.getElementById('packageModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePackageModal();
        }
    });

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

        document.getElementById('pkgModalDesc').textContent = pkg.description || 'Tidak ada deskripsi tambahan.';

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
</script>
<?= $this->endSection() ?>
