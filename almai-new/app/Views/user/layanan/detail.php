<?= $this->extend('user/partials/layout') ?>
<!-- DASHBOARD VIEW: user/layanan/detail -->

<?= $this->section('content') ?>
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

<?php
$placeholders = [
    'Advokasi' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=450&fit=crop',
    'Expert Advisor' => 'https://images.unsplash.com/photo-1642790106117-e829e14a795f?w=800&h=450&fit=crop',
    'Ultimate' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800&h=450&fit=crop',
];
$thumbnail = !empty($layanan['thumbnail']) ? $layanan['thumbnail'] : ($placeholders[$layanan['category']] ?? $placeholders['Advokasi']);

$categoryColors = [
    'Advokasi' => 'accent',
    'Expert Advisor' => 'accent',
    'Ultimate' => 'accent',
    'Almai Ultimate' => 'accent',
];
$color = $categoryColors[$layanan['category']] ?? 'accent';
?>

<!-- Breadcrumb -->
<div class="mb-6">
    <nav class="flex items-center gap-2 text-sm text-gray-400">
        <a href="<?= base_url('user/layanan') ?>" class="hover:text-white transition">Layanan</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-white"><?= esc($layanan['name']) ?></span>
    </nav>
</div>

<!-- Main Content -->
<div class="grid lg:grid-cols-3 gap-8">
    <!-- Left Content -->
    <div class="lg:col-span-2">
        <!-- Thumbnail -->
        <div class="relative rounded-2xl overflow-hidden mb-6">
            <img src="<?= $thumbnail ?>" alt="<?= esc($layanan['name']) ?>" class="w-full aspect-video object-cover">

            <!-- Badges -->
            <div class="absolute top-4 left-4 flex flex-wrap gap-2">
                <span class="px-3 py-1 bg-black/70 backdrop-blur-sm rounded-full text-sm font-medium"><?= esc($layanan['level']) ?></span>
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

            <?php if (!empty($layanan['badge'])): ?>
                <div class="absolute bottom-4 left-4">
                    <span class="px-3 py-1 bg-blue-500 text-white rounded-full text-sm font-medium">Rekomendasi <?= esc($layanan['badge']) ?></span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Category & Title -->
        <div class="mb-6">
            <p class="text-<?= $color ?>-<?= $color === 'accent' ? '' : '400' ?> text-sm font-medium mb-2"><?= esc($layanan['category']) ?> • <?= esc($layanan['subcategory']) ?></p>
            <h1 class="text-2xl md:text-3xl font-bold mb-4"><?= esc($layanan['name']) ?></h1>
        </div>

        <!-- WPA Info -->
        <div class="flex items-center gap-4 p-4 bg-[#111] border border-white/10 rounded-xl mb-6">
            <img src="<?= esc($layanan['wpa_photo']) ?>" alt="" class="w-14 h-14 rounded-full object-cover">
            <div>
                <p class="text-sm text-gray-400">Dibimbing oleh</p>
                <?php if (!empty($layanan['wpa_slug'])): ?>
                    <a href="<?= base_url('wpa/' . $layanan['wpa_slug']) ?>" class="font-bold hover:text-accent transition">
                        <?= esc($layanan['wpa_name']) ?>
                    </a>
                <?php else: ?>
                    <p class="font-bold"><?= esc($layanan['wpa_name']) ?></p>
                <?php endif; ?>
            </div>
            <div class="ml-auto flex items-center gap-1 text-yellow-500">
                <i class="fas fa-star"></i>
                <span class="font-bold"><?= number_format($layanan['rating'], 1) ?></span>
            </div>
        </div>

        <!-- Description -->
        <div class="mb-8 text-gray-300 leading-relaxed text-lg prose prose-invert max-w-none">
            <?= $layanan['description'] ?>
        </div>

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


        <!-- Warning -->
        <?php if (!empty($extendedInfo['warning'])): ?>
            <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-2xl p-6 mb-6">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-triangle text-yellow-500 mt-1"></i>
                    <div>
                        <h4 class="font-bold text-yellow-500 mb-2">Perhatian</h4>
                        <p class="text-gray-300 text-sm"><?= esc($extendedInfo['warning']) ?></p>
                    </div>
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
                                                    <div class="font-bold text-accent">Rp <?= number_format($pkg['price'], 0, ',', '.') ?></div>
                                                    <?php if (!empty($pkg['poin_price'])): ?>
                                                        <div class="text-[10px] text-green-400 font-medium"><i class="fas fa-coins mr-1"></i><?= number_format($pkg['poin_price'], 0, ',', '.') ?> Poin</div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        <?php elseif ($layanan['price'] || !empty($layanan['poin_price'])): ?>
                            <?php if ($layanan['price']): ?>
                                <?php if ($layanan['original_price'] && $layanan['original_price'] > $layanan['price']): ?>
                                    <?php $discount = round((($layanan['original_price'] - $layanan['price']) / $layanan['original_price']) * 100); ?>
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="px-2 py-1 bg-red-500 text-white text-xs font-bold rounded">-<?= $discount ?>%</span>
                                        <span class="text-gray-500 line-through">Rp <?= number_format($layanan['original_price'], 0, ',', '.') ?></span>
                                    </div>
                                <?php endif; ?>
                                <p class="text-3xl font-bold text-accent mb-1">Rp <?= number_format($layanan['price'], 0, ',', '.') ?></p>
                            <?php endif; ?>

                            <?php if (!empty($layanan['poin_price'])): ?>
                                <div class="flex items-center gap-2 text-green-400 font-bold <?= $layanan['price'] ? 'bg-green-400/10 px-3 py-2 rounded-lg inline-flex mt-1 border border-green-400/20' : 'text-xl' ?>">
                                    <i class="fas fa-coins"></i>
                                    <span><?= number_format($layanan['poin_price'], 0, ',', '.') ?> Almai Poin</span>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <p class="text-xl font-bold text-gray-400">Hubungi untuk harga</p>
                        <?php endif; ?>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="text-center p-3 bg-black/30 rounded-xl">
                            <?php if (!empty($layanan['total_sessions']) && $layanan['total_sessions'] > 0): ?>
                                <i class="fas fa-chalkboard-teacher text-<?= $color ?>-<?= $color === 'accent' ? '' : '400' ?> mb-1"></i>
                                <p class="text-sm font-bold"><?= $layanan['total_sessions'] ?></p>
                                <p class="text-xs text-gray-500">Sesi</p>
                            <?php else: ?>
                                <i class="fas fa-clock text-<?= $color ?>-<?= $color === 'accent' ? '' : '400' ?> mb-1"></i>
                                <p class="text-sm font-bold"><?= esc($layanan['duration'] ?? $layanan['duration_days'] . ' Hari') ?></p>
                                <p class="text-xs text-gray-500">Durasi</p>
                            <?php endif; ?>
                        </div>
                        <?php if ($layanan['modules'] > 0): ?>
                            <div class="text-center p-3 bg-black/30 rounded-xl">
                                <i class="fas fa-book text-<?= $color ?>-<?= $color === 'accent' ? '' : '400' ?> mb-1"></i>
                                <p class="text-sm font-bold"><?= $layanan['modules'] ?></p>
                                <p class="text-xs text-gray-500">Modul</p>
                            </div>
                        <?php endif; ?>
                        <div class="text-center p-3 bg-black/30 rounded-xl">
                            <i class="fas fa-users text-<?= $color ?>-<?= $color === 'accent' ? '' : '400' ?> mb-1"></i>
                            <p class="text-sm font-bold">
                                <?php if (!empty($layanan['max_participants']) && $layanan['max_participants'] > 0): ?>
                                    <?= number_format($layanan['students']) ?> <span class="text-gray-500 text-xs font-normal">/ <?= number_format($layanan['max_participants']) ?></span>
                                <?php else: ?>
                                    <?= number_format($layanan['students']) ?>+
                                <?php endif; ?>
                            </p>
                            <p class="text-xs text-gray-500">Peserta</p>
                        </div>
                        <div class="text-center p-3 bg-black/30 rounded-xl">
                            <i class="fas fa-map-marker-alt text-<?= $color ?>-<?= $color === 'accent' ? '' : '400' ?> mb-1"></i>
                            <p class="text-sm font-bold"><?= esc($layanan['location']) ?></p>
                            <p class="text-xs text-gray-500">Lokasi</p>
                        </div>

                        <?php if (!empty($layanan['is_recurring']) || !empty($layanan['event_date'])): ?>
                            <div class="text-center p-3 bg-black/30 rounded-xl flex flex-col justify-center items-center h-full">
                                <i class="fas fa-calendar-alt text-<?= $color ?>-<?= $color === 'accent' ? '' : '400' ?> mb-1"></i>
                                <?php if (!empty($layanan['is_recurring'])): ?>
                                    <div class="leading-tight">
                                        <p class="text-sm font-bold uppercase mb-0.5">
                                            <?= esc($layanan['recurring_frequency'] ?? 'Rutin') ?>
                                        </p>
                                        <p class="text-[10px] normal-case text-gray-300 leading-3">
                                            <?= esc($layanan['recurring_day'] ?? '-') ?>, <?= esc(substr($layanan['recurring_time'] ?? '', 0, 5)) ?> WIB
                                        </p>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Jadwal</p>
                                <?php elseif (!empty($layanan['event_date'])): ?>
                                    <p class="text-sm font-bold">
                                        <?= date('d M', strtotime($layanan['event_date'])) ?>
                                        <?php if (!empty($layanan['event_end_date'])): ?>
                                            - <?= date('d M', strtotime($layanan['event_end_date'])) ?>
                                        <?php endif; ?>
                                    </p>
                                    <p class="text-xs text-gray-500">Tanggal</p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="space-y-3">
                        <?php if ($layanan['price'] || !empty($packages)): ?>
                            <?php if ($hasPurchased): ?>
                                <div class="mb-4 bg-green-500/10 border border-green-500/20 rounded-xl p-3 flex items-start gap-3">
                                    <i class="fas fa-check-circle text-green-500 mt-0.5"></i>
                                    <div>
                                        <p class="text-sm font-bold text-green-500">Layanan Sudah Dimiliki</p>
                                        <p class="text-xs text-gray-400">Anda dapat membeli layanan ini lagi untuk menambah durasi atau unit.</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php
                            $isPro = !empty($layanan['is_premium']);
                            $isLoggedIn = session()->get('isLoggedIn');
                            $userLevelId = session()->get('level_id') ?? 1;

                            // If logged in, check real-time level from DB (in case session is outdated)
                            if ($isLoggedIn) {
                                $userId = session()->get('userId');
                                $userModel = new \App\Models\UserModel();
                                $currentUser = $userModel->find($userId);
                                if ($currentUser) {
                                    $userLevelId = $currentUser['level_id'];
                                    // Update session if different
                                    if ($userLevelId != session()->get('level_id')) {
                                        session()->set('level_id', $userLevelId);
                                    }
                                }
                            }

                            // User is considered PRO if level_id >= 2 (PRO=2, Trader Expert=3, etc)
                            $userIsPro = $userLevelId >= 2;

                            // Base Buy URL
                            $buyUrl = base_url(($isLoggedIn ? 'user/' : '') . 'checkout/layanan/' . $layanan['slug']);
                            $queryParams = [];
                            if (!empty($packages)) $queryParams['package'] = $packages[0]['id'];
                            if ($ref = service('request')->getGet('ref')) $queryParams['ref'] = $ref;
                            if ($voucher = service('request')->getGet('voucher')) $queryParams['voucher'] = $voucher;
                            if (!empty($queryParams)) $buyUrl .= '?' . http_build_query($queryParams);

                            // Button UI Defaults
                            $buttonText = 'Beli Sekarang';
                            $buttonIcon = 'fa-shopping-cart';
                            $showWarning = false;
                            $warningMessage = '';

                            // PRO Logic Check
                            // Force KYC check if controller flagged it
                            if (isset($needsKyc) && $needsKyc) {
                                $buyUrl = base_url('user/kyc');
                                $buttonText = 'WAJIB KYC';
                                $buttonIcon = 'fa-id-card';
                                $showWarning = true;
                                $warningMessage = 'Layanan ini wajib KYC. Silakan verifikasi identitas Anda untuk melanjutkan.';
                            } elseif ($isPro) {
                                if (!$isLoggedIn) {
                                    // Case 1: Not Logged In -> Login First
                                    $buyUrl = base_url('login?redirect=' . urlencode(current_url()));
                                    $buttonText = 'Login (Wajib KYC)';
                                    $buttonIcon = 'fa-lock';
                                    $showWarning = true;
                                    $warningMessage = 'Layanan PRO memerlukan identifikasi (KYC)';
                                } elseif (!$userIsPro) {
                                    // Case 2: Logged In but Regular User (Level 1) -> Must KYC Upgrade
                                    $buyUrl = base_url('user/kyc');
                                    $buttonText = 'WAJIB KYC';
                                    $buttonIcon = 'fa-id-card';
                                    $showWarning = true;
                                    $warningMessage = 'Layanan ini khusus Member PRO. Silakan verifikasi identitas (KYC) Anda.';
                                }
                                // Case 3: Logged In and Pro (Level >= 2) -> Allowed (Standard Buy URL)
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
                        <?php else: ?>
                            <a href="https://wa.me/6285183231800?text=Halo, saya tertarik dengan layanan <?= urlencode($layanan['name']) ?>" target="_blank"
                                class="block text-center py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                                <i class="fab fa-whatsapp mr-2"></i> Hubungi Kami
                            </a>
                        <?php endif; ?>

                        <!-- Share Button -->
                        <button onclick="openShareModal()" class="w-full py-4 border border-white/20 rounded-xl hover:border-accent hover:text-accent transition flex items-center justify-center gap-2">
                            <i class="fas fa-share-alt"></i> Bagikan
                        </button>
                    </div>

                    <!-- Guarantee -->
                    <div class="mt-6 pt-6 border-t border-white/10">
                        <div class="flex items-center gap-3 text-sm text-gray-400">
                            <i class="fas fa-shield-alt text-accent"></i>
                            <span>Layanan resmi dari PT. Alma Indonesia Raya yang terdaftar di BAPPEBTI</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Ulasan Section -->
<div class="py-8">
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

<!-- Share Modal -->
<div id="shareModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 max-w-md w-full transform scale-95 opacity-0 transition-all duration-300" id="shareModalContent">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-white">Bagikan Layanan</h3>
            <button onclick="closeShareModal()" class="text-gray-400 hover:text-white transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <?php if (session()->get('isLoggedIn')): ?>
            <!-- Referral Info -->
            <div class="bg-gradient-to-br from-green-900/40 to-black border border-green-500/20 rounded-2xl p-5 mb-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center shrink-0 shadow-lg shadow-green-600/20">
                        <i class="fas fa-gift text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="font-bold text-white text-base">Kode Referral Anda</p>
                        <p class="text-xs text-gray-400">Dapatkan poin dari setiap pembelian via link Anda</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <input type="text" id="referralLinkInput" readonly value="" class="flex-1 px-4 py-3 bg-black border border-white/10 rounded-xl text-sm font-mono text-[#33E818] focus:outline-none focus:border-[#33E818] transition">
                    <button onclick="copyReferralLink()" class="px-5 py-3 bg-[#33E818] hover:bg-[#2bc214] text-black rounded-xl transition shadow-lg shadow-[#33E818]/20 group">
                        <i class="fas fa-copy text-lg group-active:scale-90 transition"></i>
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <!-- Share Link (Standard - only if not logged in) -->
        <?php if (!session()->get('isLoggedIn')): ?>
            <div class="bg-[#1a1a1a] border border-white/10 rounded-2xl p-5 mb-6">
                <div class="mb-3">
                    <p class="font-bold text-white text-base">Link Layanan</p>
                    <p class="text-xs text-gray-400">Bagikan link ini ke teman Anda</p>
                </div>
                <div class="flex items-center gap-2">
                    <input type="text" id="shareLink" readonly value="" class="flex-1 px-4 py-3 bg-black border border-white/10 rounded-xl text-sm font-mono text-white focus:outline-none focus:border-white/30 transition">
                    <button onclick="copyShareLink()" class="px-5 py-3 bg-white/10 hover:bg-white/20 text-white rounded-xl transition group">
                        <i class="fas fa-copy text-lg group-active:scale-90 transition"></i>
                    </button>
                </div>
            </div>
        <?php endif; ?>

        <!-- Share Buttons -->
        <div class="grid grid-cols-4 gap-3">
            <button onclick="shareToWhatsApp()" class="aspect-square flex flex-col items-center justify-center bg-[#25D366]/10 hover:bg-[#25D366]/20 border border-[#25D366]/20 rounded-2xl transition group">
                <div class="w-10 h-10 bg-[#25D366] rounded-full flex items-center justify-center mb-2 shadow-lg shadow-[#25D366]/20 group-hover:scale-110 transition">
                    <i class="fab fa-whatsapp text-white text-xl"></i>
                </div>
                <span class="text-[10px] font-medium text-gray-300">WhatsApp</span>
            </button>
            <button onclick="shareToTelegram()" class="aspect-square flex flex-col items-center justify-center bg-[#0088CC]/10 hover:bg-[#0088CC]/20 border border-[#0088CC]/20 rounded-2xl transition group">
                <div class="w-10 h-10 bg-[#0088CC] rounded-full flex items-center justify-center mb-2 shadow-lg shadow-[#0088CC]/20 group-hover:scale-110 transition">
                    <i class="fab fa-telegram-plane text-white text-xl"></i>
                </div>
                <span class="text-[10px] font-medium text-gray-300">Telegram</span>
            </button>
            <button onclick="shareToFacebook()" class="aspect-square flex flex-col items-center justify-center bg-[#1877F2]/10 hover:bg-[#1877F2]/20 border border-[#1877F2]/20 rounded-2xl transition group">
                <div class="w-10 h-10 bg-[#1877F2] rounded-full flex items-center justify-center mb-2 shadow-lg shadow-[#1877F2]/20 group-hover:scale-110 transition">
                    <i class="fab fa-facebook-f text-white text-xl"></i>
                </div>
                <span class="text-[10px] font-medium text-gray-300">Facebook</span>
            </button>
            <button onclick="shareToTwitter()" class="aspect-square flex flex-col items-center justify-center bg-[#1DA1F2]/10 hover:bg-[#1DA1F2]/20 border border-[#1DA1F2]/20 rounded-2xl transition group">
                <div class="w-10 h-10 bg-[#1DA1F2] rounded-full flex items-center justify-center mb-2 shadow-lg shadow-[#1DA1F2]/20 group-hover:scale-110 transition">
                    <i class="fab fa-twitter text-white text-xl"></i>
                </div>
                <span class="text-[10px] font-medium text-gray-300">Twitter</span>
            </button>
        </div>
    </div>
</div>

<!-- Copy Toast -->
<div id="copyToast" class="hidden fixed bottom-4 left-1/2 -translate-x-1/2 px-6 py-3 bg-accent text-black font-bold rounded-full shadow-lg z-[110]">
    <i class="fas fa-check mr-2"></i> Link berhasil disalin!
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
        // Ensure we share the PUBLIC url, not the user one
        if (url.pathname.includes('/user/layanan/')) {
            url.pathname = url.pathname.replace('/user/layanan/', '/layanan/');
        }

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
                // Ref param is already preserved by URL object if present
                buyBtn.href = currentUrl.toString();
            }
        });
    });

    function openShareModal() {
        const modal = document.getElementById('shareModal');
        const content = document.getElementById('shareModalContent');

        // Set link for referral input (if logged in)
        const referralInput = document.getElementById('referralLinkInput');
        if (referralInput) {
            referralInput.value = getShareLink();
        }

        // Set link for regular share input (if not logged in)
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
        // This replaces copyReferralCode
        const refInput = document.getElementById('referralLinkInput');
        if (refInput) {
            refInput.select();
            document.execCommand('copy');
            showToast('Link referral disalin!');
        }
    }

    function shareToWhatsApp() {
        const text = `Cek layanan keren ini: ${layananTitle}\n${getShareLink()}`;
        window.open(`https://wa.me/?text=${encodeURIComponent(text)}`, '_blank');
    }

    function shareToTelegram() {
        window.open(`https://t.me/share/url?url=${encodeURIComponent(getShareLink())}&text=${encodeURIComponent(layananTitle)}`, '_blank');
    }

    function shareToFacebook() {
        window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(getShareLink())}`, '_blank');
    }

    function shareToTwitter() {
        const text = `Cek layanan keren ini: ${layananTitle}`;
        window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(getShareLink())}`, '_blank');
    }

    // Close modal when clicking outside
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

    // Package Modal
    function openPackageModal(pkg) {
        document.getElementById('pkgModalName').innerText = pkg.name;
        document.getElementById('pkgModalPrice').innerText = 'Rp ' + parseInt(pkg.price).toLocaleString('id-ID');

        const originalPriceElem = document.getElementById('pkgModalOriginalPrice');
        if (pkg.original_price && parseInt(pkg.original_price) > parseInt(pkg.price)) {
            originalPriceElem.innerText = 'Rp ' + parseInt(pkg.original_price).toLocaleString('id-ID');
            originalPriceElem.classList.remove('hidden');
        } else {
            originalPriceElem.classList.add('hidden');
        }

        const poinPriceElem = document.getElementById('pkgModalPoinPrice');
        if (pkg.poin_price) {
            poinPriceElem.innerHTML = '<i class="fas fa-coins mr-1"></i>' + parseInt(pkg.poin_price).toLocaleString('id-ID') + ' Poin';
            poinPriceElem.classList.remove('hidden');
        } else {
            poinPriceElem.classList.add('hidden');
        }

        document.getElementById('pkgModalDesc').innerText = pkg.description || 'Tidak ada deskripsi tambahan.';

        const modal = document.getElementById('packageModal');
        const content = document.getElementById('packageModalContent');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        // Select the radio button corresponding to this package
        const radio = document.querySelector(`input[name="package_selection"][value="${pkg.id}"]`);
        if (radio) {
            radio.checked = true;
            // Trigger change event to update buy button
            const event = new Event('change');
            radio.dispatchEvent(event);
        }

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