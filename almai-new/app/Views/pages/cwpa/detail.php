<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
<style>
    /* TIMELINE STYLES - Matching WPA exactly */
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
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes zoomIn {
        from { transform: scale(0.8); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
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
            min-width: 120px;
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
$photoUrl = $cwpa['photo'];
if (filter_var($photoUrl, FILTER_VALIDATE_URL) || strpos($photoUrl, 'http') === 0) {
    // Keep as is
} else {
    $photoUrl = base_url('file/' . ltrim($photoUrl, '/\\'));
}
?>

<!-- Hero Section - Matching WPA Structure -->
<section class="relative pt-24 md:pt-40 pb-12 overflow-hidden">
    <div class="absolute inset-0 bg-grid z-0 pointer-events-none opacity-20"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-[400px] aspect-square md:w-[800px] md:h-[500px] bg-accent/5 blur-[80px] md:blur-[120px] rounded-full pointer-events-none z-0"></div>
    <div class="container mx-auto px-6 relative z-10">
        
        <a href="<?= base_url('cwpa') ?>" class="inline-flex items-center gap-2 text-gray-400 hover:text-accent transition mb-8 text-sm group">
            <i class="fas fa-arrow-left transition-transform group-hover:-translate-x-1"></i>
            <span>Kembali ke Daftar CWPA</span>
        </a>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-10 md:gap-8 items-start">
            <!-- Profile Card (Left - 3/4 cols) -->
            <div class="w-full md:col-span-4 lg:col-span-3 relative md:sticky md:top-24 mb-6 md:mb-0">
                <div class="bg-[#111] rounded-2xl border border-white/10 overflow-hidden shadow-2xl">
                    <div class="aspect-[4/5] relative group">
                        <img src="<?= esc($photoUrl) ?>" alt="<?= esc($cwpa['name']) ?>" class="w-full h-full object-cover object-top transition duration-500 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-60"></div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <h2 class="text-xl font-bold truncate leading-tight"><?= esc($cwpa['name']) ?></h2>
                            <p class="text-accent text-xs font-bold uppercase tracking-wider mt-1"><?= esc($cwpa['specialty'] ?? 'CALON WAKIL PENASIHAT BERJANGKA') ?></p>
                        </div>
                        <!-- Rating Badge -->
                        <div class="absolute top-4 right-4 bg-black/60 backdrop-blur-md border border-white/10 px-3 py-1 rounded-full flex items-center gap-1">
                            <i class="fas fa-star text-yellow-500 text-xs"></i>
                            <span class="text-white font-bold text-sm"><?= (float)($cwpa['rating'] ?? 5.0) ?></span>
                        </div>
                    </div>
                    <div class="p-5">
                        <!-- Stats Compact with Icons -->
                        <div class="grid grid-cols-2 gap-3 mb-6 border-t border-b border-white/10 py-4">
                            <div class="text-center group" title="Total User">
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

                        <p class="text-sm text-gray-400 mb-6 leading-relaxed">Sedang menjalani program persiapan sertifikasi Wakil Penasihat Berjangka melalui 8 tahapan terstruktur.</p>

                        <div class="space-y-3">
                            <!-- Follow Button -->
                            <button onclick="toggleFollow(<?= $cwpa['id'] ?>)"
                                id="followBtn"
                                class="w-full py-3 rounded-xl font-bold transition flex items-center justify-center gap-2 <?= ($isFollowing ?? false) ? 'bg-white/10 text-white border border-white/20 hover:bg-red-500/20 hover:border-red-500 hover:text-red-500' : 'bg-accent text-black hover:bg-white' ?>">
                                <i class="<?= ($isFollowing ?? false) ? 'fas fa-check' : 'fas fa-plus' ?>" id="followIcon"></i>
                                <span id="followText"><?= ($isFollowing ?? false) ? 'Mengikuti' : 'Ikuti' ?></span>
                            </button>
                            <button onclick="openContactModal()"
                                class="flex items-center justify-center gap-2 w-full py-3 bg-accent text-black rounded-xl font-bold hover:bg-white transition">
                                <i class="fab fa-whatsapp"></i> Hubungi CWPA
                            </button>
                            <a href="<?= base_url('advokasi/' . $cwpa['slug']) ?>"
                                class="flex items-center justify-center gap-2 w-full py-3 bg-accent text-black rounded-xl font-bold hover:bg-white transition">
                                <i class="fas fa-shield-alt"></i> Program Advokasi
                            </a>
                        </div>

                        <!-- Social Media Profiles -->
                        <?php if (!empty($cwpa['instagram']) || !empty($cwpa['youtube']) || !empty($cwpa['tiktok']) || !empty($cwpa['mql5_widget_url'])): ?>
                            <div class="mt-6 pt-6 border-t border-white/10">
                                <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Social Media Profile</h3>
                                <div class="grid grid-cols-1 gap-4">
                                    <!-- Instagram Widget Card -->
                                    <?php if (!empty($instagramData)): ?>
                                        <?php
                                        $igService = new \App\Libraries\InstagramService();
                                        $igFollowers = $instagramData['edge_followed_by']['count'] ?? null;
                                        $igFollowing = $instagramData['edge_follow']['count'] ?? null;
                                        ?>
                                        <div class="bg-[#1a1a1a] border border-white/10 rounded-xl overflow-hidden hover:border-pink-500/50 transition-all duration-300 flex flex-col h-full">
                                            <div class="p-3 flex items-center justify-between">
                                                <div class="flex items-center gap-2">
                                                    <div class="relative w-10 h-10 rounded-full overflow-hidden flex-shrink-0 border border-white/10">
                                                        <?php
                                                        $originalPic = $instagramData['profile_pic_url_hd'] ?? $instagramData['profile_pic_url'] ?? '';
                                                        if ($originalPic === '' && !empty($tiktokData['user'])) {
                                                            $originalPic = $tiktokData['user']['avatarLarger']
                                                                ?? $tiktokData['user']['avatarMedium']
                                                                ?? $tiktokData['user']['avatarThumb']
                                                                ?? '';
                                                        }
                                                        $profilePic = $originalPic ? 'https://wsrv.nl/?url=' . urlencode($originalPic) . '&output=jpg' : '';
                                                        ?>
                                                        <?php if ($profilePic !== ''): ?>
                                                            <img src="<?= esc($profilePic) ?>" alt="Instagram <?= esc($instagramData['username'] ?? '') ?>" class="w-full h-full object-cover" onerror="this.hidden=true;this.nextElementSibling.hidden=false">
                                                        <?php endif; ?>
                                                        <span class="w-full h-full bg-gradient-to-br from-purple-600 via-pink-500 to-orange-400 text-white flex items-center justify-center"<?= $profilePic !== '' ? ' hidden' : '' ?>><i class="fab fa-instagram text-lg"></i></span>
                                                        <?php if ($instagramData['is_verified'] ?? false): ?>
                                                            <i class="fas fa-check-circle text-blue-500 absolute -bottom-1 -right-1 text-[10px] bg-[#1a1a1a] rounded-full border border-[#1a1a1a]"></i>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div>
                                                        <a href="https://instagram.com/<?= esc($instagramData['username'] ?? '') ?>" target="_blank" class="font-bold text-white block text-xs leading-tight hover:text-accent transition">@<?= esc($instagramData['username'] ?? '') ?></a>
                                                        <p class="text-[10px] text-gray-500 line-clamp-1"><?= esc($instagramData['full_name'] ?? '') ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-auto border-t border-white/10 bg-black/20 p-2 grid grid-cols-2 divide-x divide-white/10">
                                                <div class="text-center px-1">
                                                    <div class="font-bold text-white text-xs"><?= esc($igService->formatFollowerCount($igFollowers)) ?></div>
                                                    <div class="text-[9px] text-gray-500 uppercase tracking-wider">Followers</div>
                                                </div>
                                                <div class="text-center px-1">
                                                    <div class="font-bold text-white text-xs"><?= esc($igService->formatFollowerCount($igFollowing)) ?></div>
                                                    <div class="text-[9px] text-gray-500 uppercase tracking-wider">Following</div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php elseif (!empty($instagramUrl)): ?>
                                        <a href="<?= esc($instagramUrl) ?>" target="_blank" rel="noopener noreferrer" class="bg-[#1a1a1a] border border-white/10 rounded-xl p-4 hover:border-pink-500/50 transition-all duration-300 flex items-center justify-between gap-3">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <div class="w-10 h-10 rounded-full bg-pink-500/10 text-pink-500 flex items-center justify-center flex-shrink-0">
                                                    <i class="fab fa-instagram text-lg"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-bold text-white text-xs truncate">@<?= esc($instagramUsername) ?></p>
                                                    <p class="text-[10px] text-gray-500">Lihat profil Instagram</p>
                                                </div>
                                            </div>
                                            <i class="fas fa-external-link-alt text-gray-500 text-xs"></i>
                                        </a>
                                    <?php endif; ?>

                                    <!-- YouTube Widget Card -->
                                    <?php if (!empty($youtubeData)): ?>
                                        <div class="bg-[#1a1a1a] border border-white/10 rounded-xl overflow-hidden hover:border-red-600/50 transition-all duration-300 flex flex-col h-full">
                                            <div class="p-3 flex items-center justify-between">
                                                <div class="flex items-center gap-2">
                                                    <div class="relative w-10 h-10 rounded-full overflow-hidden flex-shrink-0 border border-white/10">
                                                        <?php
                                                        $ytService = new \App\Libraries\YoutubeService();
                                                        $avatarUrl = $ytService->getAvatarUrl($youtubeData['avatar'] ?? [], 120);
                                                        ?>
                                                        <img src="<?= esc($avatarUrl) ?>" alt="YT" class="w-full h-full object-cover">
                                                    </div>
                                                    <div>
                                                        <a href="https://youtube.com/channel/<?= esc($youtubeData['channel_id'] ?? '') ?>" target="_blank" class="font-bold text-white block text-xs leading-tight line-clamp-1 hover:text-accent transition"><?= esc($youtubeData['title'] ?? '') ?></a>
                                                        <p class="text-[10px] text-gray-500">YouTube</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-auto border-t border-white/10 bg-black/20 p-2 grid grid-cols-2 divide-x divide-white/10">
                                                <div class="text-center px-1">
                                                    <div class="font-bold text-white text-xs"><?= esc($ytService->formatSubscriberCount($youtubeData['subscriber_count'] ?? '0')) ?></div>
                                                    <div class="text-[9px] text-gray-500 uppercase tracking-wider">Subs</div>
                                                </div>
                                                <div class="text-center px-1">
                                                    <div class="font-bold text-white text-xs"><?= esc($youtubeData['video_count'] ?? '0') ?></div>
                                                    <div class="text-[9px] text-gray-500 uppercase tracking-wider">Videos</div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <!-- TikTok Widget Card -->
                                    <?php if (!empty($tiktokData)): ?>
                                        <div class="bg-[#1a1a1a] border border-white/10 rounded-xl overflow-hidden hover:border-cyan-500/50 transition-all duration-300 flex flex-col h-full">
                                            <div class="p-3 flex items-center justify-between">
                                                <div class="flex items-center gap-2">
                                                    <div class="relative w-10 h-10 rounded-full overflow-hidden flex-shrink-0 border border-white/10">
                                                        <?php
                                                        $tiktokAvatar = $tiktokData['user']['avatarThumb'] ?? 'https://via.placeholder.com/100';
                                                        ?>
                                                        <img src="<?= esc($tiktokAvatar) ?>" alt="TT" class="w-full h-full object-cover">
                                                    </div>
                                                    <div>
                                                        <a href="https://tiktok.com/@<?= esc($tiktokData['user']['uniqueId'] ?? '') ?>" target="_blank" class="font-bold text-white block text-xs leading-tight hover:text-accent transition">@<?= esc($tiktokData['user']['uniqueId'] ?? '') ?></a>
                                                        <p class="text-[10px] text-gray-500 line-clamp-1"><?= esc($tiktokData['user']['nickname'] ?? '') ?></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-auto border-t border-white/10 bg-black/20 p-2 grid grid-cols-2 divide-x divide-white/10">
                                                <div class="text-center px-1">
                                                    <div class="font-bold text-white text-xs"><?= number_format($tiktokData['stats']['followerCount'] ?? 0) ?></div>
                                                    <div class="text-[9px] text-gray-500 uppercase tracking-wider">Followers</div>
                                                </div>
                                                <div class="text-center px-1">
                                                    <div class="font-bold text-white text-xs"><?= number_format($tiktokData['stats']['heartCount'] ?? 0) ?></div>
                                                    <div class="text-[9px] text-gray-500 uppercase tracking-wider">Hearts</div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($cwpa['mql5_widget_url'])): ?>
                                        <a href="<?= esc($cwpa['mql5_widget_url']) ?>" target="_blank" rel="noopener noreferrer" class="bg-[#1a1a1a] border border-white/10 rounded-xl p-4 hover:border-blue-500/50 transition-all duration-300 flex items-center justify-between gap-3">
                                            <div class="flex items-center gap-3 min-w-0">
                                                <div class="w-10 h-10 rounded-full bg-blue-500/10 text-blue-400 flex items-center justify-center flex-shrink-0">
                                                    <i class="fas fa-chart-line text-lg"></i>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-bold text-white text-xs">MQL5 Signal</p>
                                                    <p class="text-[10px] text-gray-500">Lihat performa trading</p>
                                                </div>
                                            </div>
                                            <i class="fas fa-external-link-alt text-gray-500 text-xs"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Content Area (Right - 8/9 cols) -->
            <div class="md:col-span-8 lg:col-span-9">
                <div class="mb-8">
                    <div class="mb-6">
                        <h2 class="text-xl font-bold mb-2">Perjalanan Sertifikasi <span class="text-accent">WPA</span></h2>
                    </div>
                    
                    <!-- 8 Tahapan Card -->
                    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl">
                        <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                            <i class="fas fa-list-check text-accent"></i>
                            <span>8 Tahapan Sertifikasi</span>
                        </h3>

                        <div class="phase-timeline-wpa">
                            <?php
                            $phases = [
                                ['number' => 1, 'title' => 'Almai | Pendampingan'],
                                ['number' => 2, 'title' => 'Bursa ICDX | Sertifikasi Multilateral'],
                                ['number' => 3, 'title' => 'LPK | Sertifikasi Pelatihan PBK'],
                                ['number' => 4, 'title' => 'BNSP | Sertifikasi Kompetensi'],
                                ['number' => 5, 'title' => 'BAPPEBTI | Sertifikasi Profesi - TLUP'],
                                ['number' => 6, 'title' => 'Almai | BAPPEBTI | Izin WPA'],
                                ['number' => 7, 'title' => 'Almai | OJK | Persetujuan Derivatif Keuangan'],
                                ['number' => 8, 'title' => 'Almai | BI | Persetujuan Derivatif PUVA'],
                            ];

                            $currentPhase = $cwpa['current_phase'] ?? 1;

                            // Parse phase certificates
                            $phaseCertificates = [];
                            if (!empty($cwpa['phase_certificates'])) {
                                $phaseCertificates = is_string($cwpa['phase_certificates'])
                                    ? json_decode($cwpa['phase_certificates'], true)
                                    : $cwpa['phase_certificates'];
                            }

                            foreach ($phases as $index => $phase):
                                $isActive = $phase['number'] == $currentPhase;
                                $isCompleted = $phase['number'] < $currentPhase;
                                $statusClass = $isActive ? 'active' : ($isCompleted ? 'completed' : '');
                                $hasCertificate = isset($phaseCertificates[$phase['number']]);
                                $certificateUrl = $hasCertificate ? $phaseCertificates[$phase['number']] : null;

                                if ($certificateUrl && strpos($certificateUrl, 'http') !== 0) {
                                    $certificateUrl = base_url('file/' . ltrim($certificateUrl, '/\\'));
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
                            <?php $phaseLabel = \App\Models\CwpaModel::getPhaseLabel($currentPhase); ?>
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
                            <h3 class="text-2xl font-bold text-white mb-2">Follow CWPA & Dapatkan Produk Gratis!</h3>
                            <p class="text-gray-400">Ikuti profil ini untuk mendapatkan <span class="text-white font-bold">Kelas Basic & Software Almai</span> secara gratis.</p>
                        <p class="text-xs text-gray-400 mt-1 leading-relaxed">*Akses Kelas melalui dashboard pada menu Advokasi</p>
                        </div>
                        <a href="<?= base_url('register?ref=' . urlencode($referralCode ?? '')) ?>" class="px-8 py-4 bg-accent text-black font-black rounded-xl hover:bg-white transition shadow-xl whitespace-nowrap">
                            Daftar Sekarang
                        </a>
                    </div>
                </div>

                <!-- Layanan Grid -->
                <?php if (!empty($layanan)): ?>
                    <div class="mt-12">
                        <div class="flex items-end justify-between mb-8">
                            <div>
                                <h2 class="text-2xl font-bold mb-1">Layanan Tersedia</h2>
                                <p class="text-sm text-gray-400 mt-2">Pilih layanan bimbingan dari CWPA ini</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <?php foreach ($layanan as $layananItem): ?>
                                <?php
                                $layananItem['wpa_name'] = $layananItem['cwpa_name'] ?? $cwpa['name'];
                                $layananItem['wpa_photo'] = $layananItem['cwpa_photo'] ?? $cwpa['photo'];
                                
                                if (empty($layananItem['thumbnail'])) {
                                    $placeholders = [
                                        'Advokasi' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71',
                                        'Expert Advisor' => 'https://images.unsplash.com/photo-1642790106117-e829e14a795f',
                                        'Ultimate' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3',
                                    ];
                                    $layananItem['thumbnail'] = $placeholders[$layananItem['category']] ?? $placeholders['Advokasi'];
                                    $layananItem['thumbnail'] .= '?w=800&h=450&fit=crop';
                                } elseif ($layananItem['thumbnail'] && strpos($layananItem['thumbnail'], 'http') !== 0) {
                                    $layananItem['thumbnail'] = base_url('file/' . ltrim($layananItem['thumbnail'], '/\\'));
                                }
                                ?>
                                <?= view('partials/cards/layanan_card', ['layanan' => $layananItem, 'is_cwpa' => true]) ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

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

<!-- Contact CWPA Modal -->
<div id="contactModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-8 max-w-md mx-4 w-full">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-white">Hubungi CWPA</h3>
            <button onclick="closeContactModal()" class="text-gray-400 hover:text-white transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form id="contactForm" class="space-y-4">
            <div>
                <label class="block text-sm text-gray-400 mb-2">Nama Lengkap</label>
                <input type="text" id="contactName" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 text-white focus:border-accent focus:outline-none transition" placeholder="Nama Anda">
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-2">No. WhatsApp</label>
                <input type="tel" id="contactPhone" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 text-white focus:border-accent focus:outline-none transition" placeholder="08xxxxxxxxxx">
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-2">Tujuan Konsultasi</label>
                <textarea id="contactPurpose" required rows="3" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 text-white focus:border-accent focus:outline-none resize-none transition" placeholder="Jelaskan tujuan konsultasi Anda..."></textarea>
            </div>

            <p class="text-xs text-gray-500">
                <i class="fas fa-info-circle mr-1"></i> Anda akan diarahkan ke WhatsApp Admin Almai untuk terhubung dengan CWPA ini.
            </p>

            <button type="submit" class="w-full py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition shadow-[0_0_20px_rgba(51,232,24,0.3)]">
                <i class="fab fa-whatsapp mr-2"></i> Lanjut ke WhatsApp
            </button>
        </form>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showCertificate(url, title) {
        const modal = document.getElementById('certificateModal');
        const img = document.getElementById('certificateImage');
        const titleEl = document.getElementById('certificateTitle');
        
        img.src = url;
        titleEl.textContent = title;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeCertificateModal() {
        const modal = document.getElementById('certificateModal');
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCertificateModal();
            closeContactModal();
        }
    });

    // Close on click outside
    document.getElementById('certificateModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeCertificateModal();
    });

    function toggleFollow(cwpaId) {
        const btn = document.getElementById('followBtn');
        const icon = document.getElementById('followIcon');
        const text = document.getElementById('followText');

        btn.disabled = true;
        btn.style.opacity = '0.7';

        fetch('<?= base_url('cwpa/toggleFollow') ?>/' + cwpaId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
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
                if (data.isFollowing) {
                    btn.className = 'w-full py-3 rounded-xl font-bold transition flex items-center justify-center gap-2 bg-white/10 text-white border border-white/20 hover:bg-red-500/20 hover:border-red-500 hover:text-red-500';
                    icon.className = 'fas fa-check';
                    text.textContent = 'Mengikuti';
                } else {
                    btn.className = 'w-full py-3 rounded-xl font-bold transition flex items-center justify-center gap-2 bg-accent text-black hover:bg-white';
                    icon.className = 'fas fa-plus';
                    text.textContent = 'Ikuti';
                }
                
                const followerCountEl = document.getElementById('followerCount');
                if (followerCountEl) {
                    followerCountEl.textContent = new Intl.NumberFormat().format(data.newCount);
                }
            } else if (data.status === 'error' && data.message === 'Silakan login terlebih dahulu') {
                Swal.fire({
                    title: 'Daftar Sekarang',
                    text: 'Daftar sekarang untuk mengikuti CWPA ini dan mendapatkan update terbaru.',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Daftar Sekarang',
                    cancelButtonText: 'Nanti Saja',
                    background: '#111',
                    color: '#fff',
                    confirmButtonColor: '#33E818',
                    cancelButtonColor: '#333'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= base_url('register?ref=' . urlencode($referralCode ?? '')) ?>';
                    }
                });
            }
        });
    }

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
    document.getElementById('contactForm')?.addEventListener('submit', function(e) {
        e.preventDefault();

        const name = document.getElementById('contactName').value.trim();
        const phone = document.getElementById('contactPhone').value.trim();
        const purpose = document.getElementById('contactPurpose').value.trim();

        if (!name || !phone || !purpose) {
            alert('Mohon lengkapi semua field');
            return;
        }

        // Redirect to WhatsApp
        const message = `Halo, saya ingin konsultasi dengan CWPA <?= esc($cwpa['name']) ?>.\n\nNama: ${name}\nNo. HP: ${phone}\nTujuan: ${purpose}`;
        const waUrl = `https://wa.me/6285183231800?text=${encodeURIComponent(message)}`;

        window.open(waUrl, '_blank');
        closeContactModal();

        // Reset form
        document.getElementById('contactForm').reset();
    });
</script>
<?= $this->endSection() ?>
