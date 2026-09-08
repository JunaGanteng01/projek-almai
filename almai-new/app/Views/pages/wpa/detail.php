<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    /* TIMELINE STYLES FOR WPA */
    .phase-timeline-wpa {
        display: flex;
        flex-direction: column;
        gap: 0;
        padding: 1rem 0;
    }

    .phase-item-wpa {
        display: flex;
        flex-direction: row;
        align-items: flex-start;
        gap: 1rem;
        position: relative;
        padding-bottom: 2rem;
        width: 100%;
        text-align: left;
    }

    .phase-item-wpa:last-child {
        padding-bottom: 0;
    }

    .phase-circle-wpa {
        position: relative;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #1a1a1a;
        border: 2px solid #2a2a2a;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: all 0.3s;
        z-index: 10;
    }

    .phase-connector-wpa {
        position: absolute;
        background: #2a2a2a;
        transition: background 0.3s;
        z-index: 0;
        top: 40px;
        left: 20px;
        width: 2px;
        height: calc(100% - 40px);
        transform: translateX(-50%);
    }

    .phase-title-wpa {
        font-size: 0.9rem;
        color: #888;
        padding-top: 0.5rem;
        font-weight: 500;
        line-height: 1.2;
    }

    .phase-connector-wpa.active {
        background: #33E818;
    }

    .phase-item-wpa.active .phase-circle-wpa {
        background: #1a1a1a;
        border-color: #33E818;
        box-shadow: 0 0 15px rgba(51, 232, 24, 0.3);
    }

    .phase-item-wpa.completed .phase-circle-wpa {
        border-color: #33E818;
    }

    .phase-item-wpa.completed .phase-circle-wpa .icon {
        color: #33E818;
    }

    .phase-item-wpa.active .phase-title-wpa {
        color: #33E818;
        font-weight: 700;
    }

    .phase-item-wpa.completed .phase-title-wpa {
        color: #fff;
    }

    .phase-circle-wpa .number {
        font-size: 0.75rem;
        font-weight: 700;
        color: #666;
    }

    .phase-item-wpa.active .phase-circle-wpa .number {
        color: #fff;
    }

    .phase-circle-wpa .icon {
        font-size: 0.9rem;
        color: #666;
    }

    .running-gif {
        width: 24px;
        height: 24px;
        object-fit: contain;
    }

    /* Certificate Badge */
    .certificate-badge {
        position: absolute;
        top: -4px;
        right: -4px;
        width: 18px;
        height: 18px;
        background: #000;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        border: 2px solid #111;
    }

    .has-certificate {
        cursor: pointer;
        transition: transform 0.2s;
    }

    .has-certificate:hover {
        transform: scale(1.05);
    }

    .has-certificate:hover .phase-circle-wpa {
        box-shadow: 0 0 20px rgba(51, 232, 24, 0.4);
    }

    /* Certificate Modal */
    .certificate-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.95);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
        animation: fadeIn 0.3s;
    }

    .certificate-modal.active {
        display: flex;
    }

    .certificate-modal-content {
        position: relative;
        max-width: 90%;
        max-height: 90vh;
        animation: zoomIn 0.3s;
    }

    .certificate-modal-content img {
        max-width: 100%;
        max-height: 90vh;
        object-fit: contain;
        border-radius: 12px;
        box-shadow: 0 20px 60px rgba(51, 232, 24, 0.3);
    }

    .certificate-modal-close {
        position: absolute;
        top: -40px;
        right: 0;
        background: #33E818;
        color: #000;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 20px;
        font-weight: bold;
        transition: all 0.3s;
    }

    .certificate-modal-close:hover {
        background: #fff;
        transform: rotate(90deg);
    }

    .certificate-modal-title {
        position: absolute;
        bottom: -50px;
        left: 0;
        right: 0;
        text-align: center;
        color: #33E818;
        font-weight: bold;
        font-size: 1.1rem;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes zoomIn {
        from {
            transform: scale(0.8);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    @media (min-width: 768px) {
        .phase-timeline-wpa {
            flex-direction: row;
            overflow-x: auto;
            padding-bottom: 20px;
            gap: 0;
        }

        .phase-timeline-wpa::-webkit-scrollbar {
            height: 4px;
        }

        .phase-timeline-wpa::-webkit-scrollbar-track {
            background: #1a1a1a;
            border-radius: 10px;
        }

        .phase-timeline-wpa::-webkit-scrollbar-thumb {
            background: #33E818;
            border-radius: 10px;
        }

        .phase-item-wpa {
            flex-direction: column;
            align-items: center;
            text-align: center;
            flex: 1;
            min-width: 0;
            padding-bottom: 0;
            gap: 0.5rem;
        }

        .phase-circle-wpa {
            width: 60px;
            height: 60px;
            border-width: 3px;
        }

        .phase-connector-wpa {
            top: 30px;
            left: 50%;
            width: 100%;
            height: 3px;
            transform: none;
        }

        .phase-title-wpa {
            font-size: 0.75rem;
            padding-top: 0;
            min-height: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .phase-circle-wpa .icon {
            font-size: 1.25rem;
        }

        .running-gif {
            width: 35px;
            height: 35px;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$certifications = is_string($wpa['certifications']) ? json_decode($wpa['certifications'], true) : ($wpa['certifications'] ?? []);

// Parse phase certificates
$phaseCertificates = [];
if (!empty($wpa['phase_certificates'])) {
    $phaseCertificates = is_string($wpa['phase_certificates'])
        ? json_decode($wpa['phase_certificates'], true)
        : $wpa['phase_certificates'];
}

$photoUrl = $wpa['photo'];
if (strpos($photoUrl, 'uploads/') === 0) {
    $photoUrl = base_url('file/' . $photoUrl);
} elseif (strpos($photoUrl, 'images/') === 0) {
    $photoUrl = base_url($photoUrl);
}
?>
<!-- Hero -->
<section class="relative pt-24 md:pt-40 pb-12 overflow-hidden">
    <div class="absolute inset-0 bg-grid z-0 pointer-events-none opacity-20"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-[400px] aspect-square md:w-[800px] md:h-[500px] bg-accent/5 blur-[80px] md:blur-[120px] rounded-full pointer-events-none z-0"></div>
    <div class="container mx-auto px-6 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-10 md:gap-8 items-start">
            <!-- Profile Card (Left - 4 cols) -->
            <div class="w-full md:col-span-4 lg:col-span-3 relative md:sticky md:top-24 mb-6 md:mb-0">
                <div class="bg-[#111] rounded-2xl border border-white/10 overflow-hidden shadow-2xl">
                    <div class="aspect-[4/5] relative group">
                        <img src="<?= esc($photoUrl) ?>" alt="<?= esc($wpa['name']) ?>" class="w-full h-full object-cover object-top transition duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-60"></div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <h2 class="text-xl font-bold truncate leading-tight"><?= esc($wpa['name']) ?></h2>
                            <p class="text-accent text-xs font-bold uppercase tracking-wider mt-1"><?= esc($wpa['specialty']) ?></p>
                        </div>
                        <!-- Rating Badge (New Position) -->
                        <div class="absolute top-4 right-4 bg-black/60 backdrop-blur-md border border-white/10 px-3 py-1 rounded-full flex items-center gap-1">
                            <i class="fas fa-star text-yellow-500 text-xs"></i>
                            <span class="text-white font-bold text-sm"><?= (float)($wpa['rating'] ?? 5.0) ?></span>
                        </div>
                    </div>
                    <div class="p-5">
                        <!-- Stats for Mobile (Moved inside card for better mobile view or keep layout consistent?) 
                              The user wants stats box separate. I'll stick to original layout but refined widths. -->



                        <!-- Stats Compact with Icons -->
                        <div class="grid grid-cols-4 gap-3 mb-6 border-t border-b border-white/10 py-4">
                            <div class="text-center group" title="Pengalaman">
                                <div class="flex items-center justify-center gap-1.5">
                                    <i class="fas fa-briefcase text-accent/70 text-sm"></i>
                                    <h3 class="font-bold text-accent text-base leading-none group-hover:scale-110 transition"><?php
                                                                                                                                $exp = $wpa['experience'];
                                                                                                                                // Extract only numbers
                                                                                                                                preg_match('/\d+/', $exp, $matches);
                                                                                                                                echo $matches[0] ?? $exp;
                                                                                                                                ?></h3>
                                </div>
                            </div>
                            <div class="text-center border-l border-white/10 group" title="Total Layanan">
                                <div class="flex items-center justify-center gap-1.5">
                                    <i class="fas fa-layer-group text-accent/70 text-sm"></i>
                                    <h3 class="font-bold text-accent text-base leading-none group-hover:scale-110 transition"><?= is_countable($layananByWpa) ? count($layananByWpa) : 0 ?></h3>
                                </div>
                            </div>
                            <div class="text-center border-l border-white/10 group" title="Total User">
                                <div class="flex items-center justify-center gap-1.5">
                                    <i class="fas fa-users text-accent/70 text-sm"></i>
                                    <h3 class="font-bold text-accent text-base leading-none group-hover:scale-110 transition"><?= number_format($referralCount ?? 0) ?></h3>
                                </div>
                            </div>
                            <div class="text-center border-l border-white/10 group" title="Total Follower">
                                <div class="flex items-center justify-center gap-1.5">
                                    <i class="fas fa-heart text-accent/70 text-sm"></i>
                                    <h3 id="followerCount" class="font-bold text-accent text-base leading-none group-hover:scale-110 transition"><?= number_format($followerCount ?? 0) ?></h3>
                                </div>
                            </div>
                        </div>

                        <p class="text-sm text-gray-400 mb-6 leading-relaxed"><?= esc($wpa['bio']) ?></p>

                        <div class="space-y-3">
                            <!-- Follow Button -->
                            <button onclick="toggleFollow(<?= $wpa['id'] ?>)"
                                id="followBtn"
                                class="w-full py-3 rounded-xl font-bold transition flex items-center justify-center gap-2 <?= ($isFollowing ?? false) ? 'bg-white/10 text-white border border-white/20 hover:bg-red-500/20 hover:border-red-500 hover:text-red-500' : 'bg-accent text-black hover:bg-white' ?>">
                                <i class="<?= ($isFollowing ?? false) ? 'fas fa-check' : 'fas fa-plus' ?>" id="followIcon"></i>
                                <span id="followText"><?= ($isFollowing ?? false) ? 'Mengikuti' : 'Ikuti' ?></span>
                            </button>
                            <button onclick="openContactModal()"
                                class="flex items-center justify-center gap-2 w-full py-3 bg-accent text-black rounded-xl font-bold hover:bg-white transition">
                                <i class="fab fa-whatsapp"></i> Hubungi WPA
                            </button>
                            <a href="<?= base_url('advokasi/' . $wpa['slug']) ?>"
                                class="flex items-center justify-center gap-2 w-full py-3 bg-accent text-black rounded-xl font-bold hover:bg-white transition">
                                <i class="fas fa-shield-alt"></i> Program Advokasi
                            </a>
                        </div>

                        <!-- Social Media Embeds -->
                        <?php if (!empty($wpa['instagram']) || !empty($wpa['youtube']) || $wpa['slug'] === 'agi' || $wpa['slug'] === 'eka'): ?>
                            <div class="mt-6 border-t border-white/10 pt-6">
                                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Social Media Profile</h3>
                                <div class="space-y-4">
                                    <!-- Instagram Feed (RapidAPI) -->
                                    <?php if (!empty($instagramData)): ?>
                                        <?php $igService = new \App\Libraries\InstagramService(); ?>
                                        <div class="bg-[#1a1a1a] border border-white/10 rounded-xl overflow-hidden hover:border-pink-500/50 transition-all duration-300">
                                            <!-- Header -->
                                            <div class="flex items-center justify-between p-4 border-b border-white/10">
                                                <div class="flex items-center gap-3">
                                                    <div class="relative w-12 h-12 rounded-full overflow-hidden flex-shrink-0">
                                                        <?php
                                                        // Get original URL
                                                        $originalPic = $instagramData['profile_pic_url_hd']
                                                            ?? $instagramData['profile_pic_url']
                                                            ?? '';

                                                        if ($originalPic === '' && !empty($tiktokData['user'])) {
                                                            $originalPic = $tiktokData['user']['avatarLarger']
                                                                ?? $tiktokData['user']['avatarMedium']
                                                                ?? $tiktokData['user']['avatarThumb']
                                                                ?? '';
                                                        }

                                                        // Use wsrv.nl PROXY to bypass Instagram CORS
                                                        // We must encode the URL
                                                        $profilePic = $originalPic
                                                            ? 'https://wsrv.nl/?url=' . urlencode($originalPic) . '&output=jpg'
                                                            : '';

                                                        // Debug log
                                                        log_message('debug', 'Instagram Profile Pic Proxy: ' . $profilePic);
                                                        ?>
                                                        <?php if ($profilePic !== ''): ?>
                                                            <img src="<?= esc($profilePic) ?>"
                                                                alt="Instagram <?= esc($instagramData['username'] ?? '') ?>"
                                                                class="w-full h-full object-cover"
                                                                onerror="this.hidden=true;this.nextElementSibling.hidden=false">
                                                        <?php endif; ?>
                                                        <span class="w-full h-full bg-gradient-to-br from-purple-600 via-pink-500 to-orange-400 text-white flex items-center justify-center"<?= $profilePic !== '' ? ' hidden' : '' ?>><i class="fab fa-instagram text-xl"></i></span>
                                                        <?php if ($instagramData['is_verified'] ?? false): ?>
                                                            <i class="fas fa-check-circle text-blue-500 absolute -bottom-1 -right-1 text-sm bg-[#1a1a1a] rounded-full"></i>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div>
                                                        <a href="https://instagram.com/<?= esc($instagramData['username'] ?? '') ?>" target="_blank" class="font-bold text-white hover:text-accent transition">@<?= esc($instagramData['username'] ?? '') ?></a>
                                                        <p class="text-xs text-gray-500"><?= esc($instagramData['full_name'] ?? '') ?></p>
                                                    </div>
                                                </div>
                                                <a href="https://instagram.com/<?= esc($instagramData['username'] ?? '') ?>"
                                                    target="_blank"
                                                    class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition">
                                                    <i class="fab fa-instagram"></i> Follow
                                                </a>
                                            </div>

                                            <!-- Stats (Only Followers and Following) -->
                                            <div class="grid grid-cols-2 gap-4 p-4">
                                                <div class="text-center">
                                                    <div class="font-bold text-white text-lg"><?= esc($igService->formatFollowerCount($instagramData['edge_followed_by']['count'] ?? null)) ?></div>
                                                    <div class="text-xs text-gray-500">Followers</div>
                                                </div>
                                                <div class="text-center border-l border-white/10">
                                                    <div class="font-bold text-white text-lg"><?= esc($igService->formatFollowerCount($instagramData['edge_follow']['count'] ?? null)) ?></div>
                                                    <div class="text-xs text-gray-500">Following</div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- YouTube Channel (RapidAPI) -->
                                    <?php if (!empty($youtubeData)): ?>
                                        <div class="bg-[#1a1a1a] border border-white/10 rounded-xl overflow-hidden hover:border-red-600/50 transition-all duration-300">
                                            <!-- Header -->
                                            <div class="flex items-center justify-between p-4 border-b border-white/10">
                                                <div class="flex items-center gap-3">
                                                    <div class="relative w-12 h-12 rounded-full overflow-hidden flex-shrink-0">
                                                        <?php
                                                        $ytService = new \App\Libraries\YoutubeService();
                                                        $avatarUrl = $ytService->getAvatarUrl($youtubeData['avatar'] ?? [], 120);
                                                        ?>
                                                        <img src="<?= esc($avatarUrl) ?>"
                                                            alt="<?= esc($youtubeData['title'] ?? '') ?>"
                                                            class="w-full h-full object-cover"
                                                            onerror="this.src='https://via.placeholder.com/100?text=YT'">
                                                        <?php if ($youtubeData['verified'] ?? false): ?>
                                                            <i class="fas fa-check-circle text-gray-400 absolute -bottom-1 -right-1 text-sm bg-[#1a1a1a] rounded-full"></i>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div>
                                                        <a href="https://youtube.com/channel/<?= esc($youtubeData['channel_id'] ?? '') ?>" target="_blank" class="font-bold text-white hover:text-accent transition"><?= esc($youtubeData['title'] ?? '') ?></a>
                                                        <p class="text-xs text-gray-500"><?= esc($youtubeData['subscriber_count'] ?? '0 subscribers') ?></p>
                                                    </div>
                                                </div>
                                                <a href="https://youtube.com/channel/<?= esc($youtubeData['channel_id'] ?? '') ?>"
                                                    target="_blank"
                                                    class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition">
                                                    <i class="fab fa-youtube"></i> Subscribe
                                                </a>
                                            </div>

                                            <!-- Stats -->
                                            <div class="grid grid-cols-2 gap-4 p-4">
                                                <div class="text-center">
                                                    <div class="font-bold text-white text-lg"><?= esc($ytService->formatSubscriberCount($youtubeData['subscriber_count'] ?? '0')) ?></div>
                                                    <div class="text-xs text-gray-500">Subscribers</div>
                                                </div>
                                                <div class="text-center border-l border-white/10">
                                                    <div class="font-bold text-white text-lg"><?= esc($youtubeData['video_count'] ?? '0 videos') ?></div>
                                                    <div class="text-xs text-gray-500">Videos</div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- TikTok Widget Card -->
                                    <?php if (!empty($tiktokData)): ?>
                                        <div class="bg-[#1a1a1a] border border-white/10 rounded-xl overflow-hidden hover:border-[#ff0050]/50 transition-all duration-300">
                                            <!-- Header -->
                                            <div class="flex items-center justify-between p-4 border-b border-white/10">
                                                <div class="flex items-center gap-3">
                                                    <div class="relative w-12 h-12 rounded-full overflow-hidden flex-shrink-0 border border-white/10">
                                                        <?php
                                                        $originalPic = $tiktokData['user']['avatarThumb'] ?? $tiktokData['user']['avatarMedium'] ?? '';
                                                        $profilePic = $originalPic ? 'https://wsrv.nl/?url=' . urlencode($originalPic) . '&output=jpg' : 'https://via.placeholder.com/100?text=TikTok';
                                                        ?>
                                                        <img src="<?= esc($profilePic) ?>" alt="TikTok" class="w-full h-full object-cover">
                                                        <?php if ($tiktokData['user']['verified'] ?? false): ?>
                                                            <i class="fas fa-check-circle text-blue-400 absolute -bottom-1 -right-1 text-sm bg-[#1a1a1a] rounded-full border border-[#1a1a1a]"></i>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div>
                                                        <a href="https://tiktok.com/@<?= esc($tiktokData['user']['uniqueId'] ?? '') ?>" target="_blank" class="font-bold text-white hover:text-[#ff0050] transition">@<?= esc($tiktokData['user']['uniqueId'] ?? '') ?></a>
                                                        <p class="text-xs text-gray-500"><?= esc($tiktokData['user']['nickname'] ?? '') ?></p>
                                                    </div>
                                                </div>
                                                <a href="https://tiktok.com/@<?= esc($tiktokData['user']['uniqueId'] ?? '') ?>" target="_blank" class="inline-flex items-center gap-2 bg-[#ff0050] hover:bg-white text-white hover:text-black text-xs font-bold px-4 py-2 rounded-lg transition">
                                                    <i class="fab fa-tiktok"></i> Follow
                                                </a>
                                            </div>
                                            <!-- Stats -->
                                            <div class="grid grid-cols-2 gap-4 p-4">
                                                <div class="text-center">
                                                    <div class="font-bold text-white text-lg"><?= number_format($tiktokData['stats']['followerCount'] ?? 0) ?></div>
                                                    <div class="text-xs text-gray-500">Followers</div>
                                                </div>
                                                <div class="text-center">
                                                    <div class="font-bold text-white text-lg"><?= number_format($tiktokData['stats']['heartCount'] ?? 0) ?></div>
                                                    <div class="text-xs text-gray-500">Hearts</div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- MQL5 Widget -->
                                    <?php if (!empty($wpa['mql5_widget_url'])): ?>
                                        <div class="bg-[#1a1a1a] border border-white/10 rounded-xl overflow-hidden hover:border-accent/50 transition-all duration-300">
                                            <!-- Header -->
                                            <div class="flex items-center justify-between p-4 border-b border-white/10">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-accent to-green-600 flex items-center justify-center flex-shrink-0">
                                                        <i class="fas fa-chart-line text-black text-xl"></i>
                                                    </div>
                                                    <div>
                                                        <span class="font-bold text-white">MQL5 Signal</span>
                                                        <p class="text-xs text-gray-500">Live Trading Performance</p>
                                                    </div>
                                                </div>
                                                <a href="<?= esc($wpa['mql5_widget_url']) ?>"
                                                    target="_blank"
                                                    class="inline-flex items-center gap-2 bg-accent hover:bg-white text-black text-xs font-bold px-4 py-2 rounded-lg transition">
                                                    <i class="fas fa-external-link-alt"></i> View Signal
                                                </a>
                                            </div>

                                            <!-- Widget Iframe -->
                                            <div class="p-4">
                                                <iframe
                                                    frameborder="0"
                                                    width="100%"
                                                    height="150"
                                                    src="<?= esc($wpa['mql5_widget_url']) ?>"
                                                    class="rounded-lg">
                                                </iframe>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>

            <!-- Profile Info & Layanan (Right - 8 cols) -->
            <div class="md:col-span-8 lg:col-span-9">
                <!-- 8 Tahapan Sertifikasi -->
                <div class="mb-8">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold mb-2">Perjalanan Sertifikasi <span class="text-accent">WPA</span></h2>
                    </div>

                    <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                            <i class="fas fa-list-check text-accent"></i>
                            <span>8 Tahapan Sertifikasi</span>
                        </h3>

                        <div class="phase-timeline-wpa">
                            <?php
                            $phases = [
                                ['number' => 1, 'title' => 'Pembekalan & Pendampingan'],
                                ['number' => 2, 'title' => 'Sertifikasi Multilateral'],
                                ['number' => 3, 'title' => 'Sertifikasi Pelatihan LPK'],
                                ['number' => 4, 'title' => 'Sertifikasi LSP PBK'],
                                ['number' => 5, 'title' => 'Sertifikasi TLUP'],
                                ['number' => 6, 'title' => 'Izin WPA'],
                                ['number' => 7, 'title' => 'Persetujuan Derivatif Keuangan'],
                                ['number' => 8, 'title' => 'Persetujuan Derivatif PUVA'],
                            ];

                            $currentPhase = $wpa['current_phase'] ?? 1;

                            foreach ($phases as $index => $phase):
                                $isActive = $phase['number'] == $currentPhase;
                                $isCompleted = $phase['number'] < $currentPhase;
                                $statusClass = $isActive ? 'active' : ($isCompleted ? 'completed' : '');
                                $hasCertificate = isset($phaseCertificates[$phase['number']]);
                                $certificateUrl = $hasCertificate ? $phaseCertificates[$phase['number']] : null;

                                // Fix certificate URL
                                if ($certificateUrl && strpos($certificateUrl, 'uploads/') === 0) {
                                    $certificateUrl = base_url('file/' . $certificateUrl);
                                } elseif ($certificateUrl && strpos($certificateUrl, 'http') !== 0) {
                                    $certificateUrl = base_url($certificateUrl);
                                }
                            ?>
                                <div class="phase-item-wpa <?= $statusClass ?> <?= $hasCertificate ? 'has-certificate cursor-pointer' : '' ?>"
                                    <?= $hasCertificate ? 'onclick="showCertificate(\'' . esc($certificateUrl, 'js') . '\', \'' . esc($phase['title'], 'js') . '\')"' : '' ?>>
                                    <?php if ($index < count($phases) - 1): ?>
                                        <div class="phase-connector-wpa <?= $isCompleted ? 'active' : '' ?>"></div>
                                    <?php endif; ?>

                                    <div class="phase-circle-wpa">
                                        <?php if ($isCompleted): ?>
                                            <i class="fas fa-graduation-cap icon"></i>
                                        <?php elseif ($isActive): ?>
                                            <img src="<?= base_url('images/lari.gif') ?>" alt="Running" class="running-gif">
                                        <?php else: ?>
                                            <div class="icon"><?= $phase['number'] ?></div>
                                        <?php endif; ?>

                                        <?php if ($hasCertificate): ?>
                                            <div class="certificate-badge">
                                                <i class="fas fa-certificate text-yellow-500"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="phase-title-wpa">
                                        <?= esc($phase['title']) ?>
                                        <?php if ($hasCertificate): ?>
                                            <i class="fas fa-eye text-accent text-xs ml-1"></i>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <?php if (isset($currentPhase)): ?>
                            <?php $phaseLabel = \App\Models\WpaModel::getPhaseLabel($currentPhase); ?>
                            <div class="mt-6 p-4 bg-accent/10 border border-accent/30 rounded-xl">
                                <div class="flex items-center gap-2 text-accent">
                                    <i class="fas fa-info-circle"></i>
                                    <span class="text-sm font-bold">
                                        <?php if ($currentPhase >= 9): ?>
                                            Sudah menyelesaikan semua sertifikasi dan memiliki izin Wakil Penasihat Berjangka
                                        <?php else: ?>
                                            Sedang di Fase <?= (int) $currentPhase ?>: <?= esc($phaseLabel) ?>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Promo Banner -->
                <div class="mt-8 bg-gradient-to-br from-[#111] to-black border border-white/10 rounded-2xl p-8 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 w-96 h-96 bg-accent/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                    <div class="relative z-10 flex flex-col md:flex-row items-center gap-8">
                        <div class="w-20 h-20 rounded-2xl bg-accent/10 flex items-center justify-center text-accent border border-accent/20 rotate-3 group-hover:rotate-0 transition duration-500">
                            <i class="fas fa-gift text-3xl"></i>
                        </div>
                        <div class="flex-1 text-center md:text-left">
                            <h3 class="text-2xl font-bold text-white mb-2">Follow WPA & Dapatkan Produk Gratis!</h3>
                            <p class="text-gray-400">Ikuti profil ini untuk mendapatkan akses eksklusif ke <span class="text-white font-bold">Kelas Trading Basic & Software Almai</span> secara gratis.</p>
                        </div>
                        <a href="<?= base_url('register?ref=' . urlencode($referralCode ?? '')) ?>" class="px-8 py-4 bg-accent text-black font-black rounded-xl hover:bg-white transition shadow-xl whitespace-nowrap">
                            Daftar Sekarang
                        </a>
                    </div>
                </div>

                <!-- Layanan List (Grid) -->
                <?php if (!empty($layananByWpa)): ?>
                    <div>
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold mb-1">Layanan Tersedia</h2>
                            <p class="text-sm text-gray-400">Pilih layanan bimbingan dari mentor ini</p>
                        </div>

                        <!-- Grid Layout: 1 col mobile, 2 cols tablet, 3 cols desktop -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <?php foreach ($layananByWpa as $layanan): ?>
                                <?php
                                if (empty($layanan['wpa_name'])) {
                                    $layanan['wpa_name'] = $wpa['name'];
                                    $layanan['wpa_photo'] = $wpa['photo'];
                                }
                                if (empty($layanan['thumbnail'])) {
                                    $placeholders = [
                                        'Advokasi' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71',
                                        'Expert Advisor' => 'https://images.unsplash.com/photo-1642790106117-e829e14a795f',
                                        'Ultimate' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3',
                                    ];
                                    $layanan['thumbnail'] = $placeholders[$layanan['category']] ?? $placeholders['Advokasi'];
                                    $layanan['thumbnail'] .= '?w=800&h=450&fit=crop';
                                } elseif (strpos($layanan['thumbnail'], 'uploads/') === 0) {
                                    $layanan['thumbnail'] = base_url('file/' . $layanan['thumbnail']);
                                }
                                ?>
                                <?= view('partials/cards/layanan_card', ['layanan' => $layanan]) ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Swiper initialization removed - now using grid layout

    function toggleFollow(wpaId) {
        const btn = document.getElementById('followBtn');
        const icon = document.getElementById('followIcon');
        const text = document.getElementById('followText');
        const count = document.getElementById('followerCount');
        const originalContent = btn.innerHTML;
        const originalClasses = btn.className;

        // Loading state
        btn.disabled = true;
        btn.style.opacity = '0.7';

        fetch('<?= base_url('wpa/follow') ?>/' + wpaId, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                })
            })
            .then(response => response.json())
            .then(data => {
                btn.disabled = false;
                btn.style.opacity = '1';

                if (data.status === 'success') {
                    // Update count
                    count.innerText = data.count_formatted;

                    // Update Button UI
                    if (data.action === 'followed') {
                        // Change to Unfollow/Following style
                        btn.className = "w-full py-3 rounded-xl font-bold transition flex items-center justify-center gap-2 bg-white/10 text-white border border-white/20 hover:bg-red-500/20 hover:border-red-500 hover:text-red-500";
                        icon.className = "fas fa-check";
                        text.innerText = "Mengikuti";

                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            background: '#111',
                            color: '#fff',
                            iconColor: '#33e818'
                        });
                        Toast.fire({
                            icon: 'success',
                            title: 'Berhasil mengikuti WPA'
                        });
                    } else {
                        // Change to Follow style
                        btn.className = "w-full py-3 rounded-xl font-bold transition flex items-center justify-center gap-2 bg-accent text-black hover:bg-white";
                        icon.className = "fas fa-plus";
                        text.innerText = "Ikuti";
                    }
                } else if (data.status === 'error' && data.message.includes('login')) {
                    // Pretty Register Alert
                    Swal.fire({
                        title: 'Daftar Sekarang',
                        text: "Daftar sekarang untuk mengikuti WPA ini dan mendapatkan update terbaru.",
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#33e818',
                        cancelButtonColor: '#333',
                        confirmButtonText: 'Daftar Sekarang',
                        cancelButtonText: 'Nanti Saja',
                        background: '#111',
                        color: '#fff',
                        iconColor: '#33e818',
                        customClass: {
                            popup: 'border border-white/10 rounded-2xl',
                            confirmButton: 'text-black font-bold rounded-xl px-6 py-2.5',
                            cancelButton: 'text-gray-400 hover:text-white rounded-xl px-6 py-2.5'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            <?php if (!empty($referralCode)): ?>
                                // Redirect to referral link which will auto-fill affiliate code
                                window.location.href = '<?= base_url('referral/' . $referralCode) ?>';
                            <?php else: ?>
                                // Fallback to regular register page
                                window.location.href = '<?= base_url('register?redirect=wpa/' . $wpa['slug']) ?>';
                            <?php endif; ?>
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: data.message || 'Terjadi kesalahan',
                        background: '#111',
                        color: '#fff',
                        confirmButtonColor: '#33e818',
                        confirmButtonText: 'OK',
                        customClass: {
                            popup: 'border border-white/10 rounded-2xl',
                            confirmButton: 'text-black font-bold'
                        }
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btn.disabled = false;
                btn.style.opacity = '1';
                Swal.fire({
                    icon: 'error',
                    title: 'Koneksi Bermasalah',
                    text: 'Gagal menghubungkan ke server.',
                    background: '#111',
                    color: '#fff',
                    confirmButtonColor: '#33e818',
                    confirmButtonText: 'Tutup',
                    customClass: {
                        popup: 'border border-white/10 rounded-2xl',
                        confirmButton: 'text-black font-bold'
                    }
                });
            });
    }

    // Certificate Modal Functions
    function showCertificate(imageUrl, title) {
        const modal = document.getElementById('certificateModal');
        const modalImg = document.getElementById('certificateImage');
        const modalTitle = document.getElementById('certificateTitle');

        modalImg.src = imageUrl;
        modalTitle.textContent = title;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeCertificateModal() {
        const modal = document.getElementById('certificateModal');
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCertificateModal();
            closeContactModal();
        }
    });

    // Close modal on background click
    document.getElementById('certificateModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeCertificateModal();
        }
    });

    // Contact Modal Functions
    function openContactModal() {
        const modal = document.getElementById('contactModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeContactModal() {
        const modal = document.getElementById('contactModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    // Close contact modal on background click
    document.getElementById('contactModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeContactModal();
        }
    });

    // Handle Contact Form Submit
    document.getElementById('contactForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();

        const name = document.getElementById('contactName').value.trim();
        const phone = document.getElementById('contactPhone').value.trim();
        const purpose = document.getElementById('contactPurpose').value.trim();

        if (!name || !phone || !purpose) {
            alert('Mohon lengkapi semua field');
            return;
        }

        // Save lead to database
        try {
            const response = await fetch('<?= base_url('api/chat/save-lead') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    name: name,
                    phone: phone,
                    message: purpose,
                    source: 'wpa_contact',
                    wpa_name: '<?= esc($wpa['name']) ?>',
                    referral_code: '<?= $referralCode ?? '' ?>'
                })
            });

            const result = await response.json();
            console.log('Lead saved:', result);
        } catch (error) {
            console.error('Error saving lead:', error);
        }

        // Redirect to WhatsApp
        const message = `Halo, saya ingin konsultasi dengan <?= esc($wpa['name']) ?>.\n\nNama: ${name}\nNo. HP: ${phone}\nTujuan: ${purpose}`;
        const waUrl = `https://wa.me/6285156789700?text=${encodeURIComponent(message)}`;

        window.open(waUrl, '_blank');
        closeContactModal();

        // Reset form
        document.getElementById('contactForm').reset();
    });
</script>
<?= $this->endSection() ?>

<!-- Certificate Modal -->
<div id="certificateModal" class="certificate-modal">
    <div class="certificate-modal-content">
        <div class="certificate-modal-close" onclick="closeCertificateModal()">
            <i class="fas fa-times"></i>
        </div>
        <img id="certificateImage" src="" alt="Certificate">
        <div id="certificateTitle" class="certificate-modal-title"></div>
    </div>
</div>

<!-- Contact WPA Modal -->
<div id="contactModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-8 max-w-md mx-4 w-full">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold">Hubungi WPA</h3>
            <button onclick="closeContactModal()" class="text-gray-400 hover:text-white">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form id="contactForm" class="space-y-4">
            <div>
                <label class="block text-sm text-gray-400 mb-2">Nama Lengkap</label>
                <input type="text" id="contactName" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" placeholder="Nama Anda">
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-2">No. WhatsApp</label>
                <input type="tel" id="contactPhone" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" placeholder="08xxxxxxxxxx">
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-2">Tujuan Konsultasi</label>
                <textarea id="contactPurpose" required rows="3" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none resize-none" placeholder="Jelaskan tujuan konsultasi Anda..."></textarea>
            </div>

            <p class="text-xs text-gray-500">
                <i class="fas fa-info-circle mr-1"></i> Data Anda akan disimpan dan Anda akan terdaftar dengan kode referral WPA ini.
            </p>

            <button type="submit" class="w-full py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition shadow-[0_0_20px_rgba(51,232,24,0.3)]">
                <i class="fab fa-whatsapp mr-2"></i> Lanjut ke WhatsApp
            </button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
