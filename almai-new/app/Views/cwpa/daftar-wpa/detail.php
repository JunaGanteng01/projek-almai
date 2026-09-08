<?= $this->extend('cwpa/layouts/main') ?>

<?= $this->section('styles') ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&display=swap');

    .wpa-profile-content {
        font-family: 'Public Sans', sans-serif;
    }

    .wpa-profile-content h1, 
    .wpa-profile-content h2, 
    .wpa-profile-content h3, 
    .wpa-profile-content h4,
    .wpa-profile-content .font-heading {
        font-family: 'Montserrat', sans-serif;
    }

    /* TIMELINE STYLES FOR WPA (Exact match from public detail) */
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
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes zoomIn {
        from { transform: scale(0.8); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    @media (min-width: 768px) {
        .phase-timeline-wpa {
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            overflow-x: auto !important;
            padding-bottom: 20px !important;
            gap: 0 !important;
        }

        .phase-timeline-wpa::-webkit-scrollbar { height: 4px; }
        .phase-timeline-wpa::-webkit-scrollbar-track { background: #1a1a1a; border-radius: 10px; }
        .phase-timeline-wpa::-webkit-scrollbar-thumb { background: #33E818; border-radius: 10px; }

        .phase-item-wpa {
            flex-direction: column !important;
            align-items: center !important;
            text-align: center !important;
            flex: 1 !important;
            min-width: 0 !important;
            padding-bottom: 0 !important;
            gap: 0.5rem !important;
        }

        .phase-circle-wpa {
            width: 60px !important;
            height: 60px !important;
            border-width: 3px !important;
            margin-bottom: 10px !important;
        }

        .phase-connector-wpa {
            top: 30px !important;
            left: 50% !important;
            width: 100% !important;
            height: 3px !important;
            transform: none !important;
        }

        .phase-title-wpa {
            font-size: 0.75rem !important;
            padding-top: 0 !important;
            min-height: 2.5rem !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .phase-circle-wpa .icon { font-size: 1.25rem !important; }
        .running-gif { width: 35px !important; height: 35px !important; }
    }

    /* Circle colors fix for better visibility */
    .phase-circle-wpa {
        background: #151515 !important;
        border-color: #333 !important;
    }
    .phase-item-wpa.active .phase-circle-wpa {
        border-color: #33E818 !important;
    }
    .phase-item-wpa.completed .phase-circle-wpa {
        border-color: #33E818 !important;
    }

    .bg-grid {
        background-size: 30px 30px;
        background-image: linear-gradient(to right, rgba(255,255,255,0.02) 1px, transparent 1px),
                          linear-gradient(to bottom, rgba(255,255,255,0.02) 1px, transparent 1px);
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$phaseCertificates = is_string($wpa['phase_certificates'] ?? '[]') ? json_decode($wpa['phase_certificates'], true) ?? [] : ($wpa['phase_certificates'] ?? []);
$photoUrl = $wpa['photo'];
if ($photoUrl && strpos($photoUrl, 'uploads/') === 0) {
    $photoUrl = base_url('file/' . $photoUrl);
} elseif ($photoUrl && strpos($photoUrl, 'images/') === 0) {
    $photoUrl = base_url($photoUrl);
} elseif ($photoUrl && strpos($photoUrl, 'http') !== 0) {
    $photoUrl = base_url('file/' . ltrim($photoUrl, '/'));
} else {
    $photoUrl = $photoUrl ?? 'https://almai.id/images/alma.gif';
}
?>

<!-- WPA Detail Wrapper (Same style as public) -->
<section class="wpa-profile-content relative pt-6 md:pt-12 pb-12 overflow-hidden">
    <!-- Visual background elements from public detail -->
    <div class="absolute inset-0 bg-grid z-0 pointer-events-none opacity-20"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full max-w-[400px] aspect-square md:w-[800px] md:h-[500px] bg-accent/5 blur-[80px] md:blur-[120px] rounded-full pointer-events-none z-0"></div>

    <div class="px-4 md:px-8 relative z-10">
        <!-- Breadcrumb & Back matching context -->
        <div class="mb-8 flex items-center gap-4">
            <a href="<?= base_url('cwpa/dashboard/daftar-wpa') ?>" class="w-10 h-10 bg-black/40 border border-white/10 rounded-xl flex items-center justify-center text-gray-400 hover:text-white hover:bg-white/10 transition shadow-lg">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <nav class="flex items-center gap-2 text-[10px] text-gray-500 uppercase tracking-widest font-bold">
                    <a href="<?= base_url('cwpa/dashboard') ?>" class="hover:text-accent">Dashboard</a>
                    <i class="fas fa-chevron-right text-[8px]"></i>
                    <a href="<?= base_url('cwpa/dashboard/daftar-wpa') ?>" class="hover:text-accent">Daftar WPA</a>
                    <i class="fas fa-chevron-right text-[8px]"></i>
                    <span class="text-white">Profile</span>
                </nav>
                <h1 class="text-xl font-black uppercase tracking-tight text-white"><?= esc($wpa['name']) ?></h1>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-10 md:gap-8 items-start">
            <!-- Profile Card (Left - 4 cols) -->
            <div class="w-full md:col-span-4 lg:col-span-3 relative md:sticky md:top-24 mb-6 md:mb-0">
                <div class="bg-[#111] rounded-2xl border border-white/10 overflow-hidden shadow-2xl">
                    <div class="aspect-[4/5] relative group bg-black/40">
                        <img src="<?= esc($photoUrl) ?>" alt="<?= esc($wpa['name']) ?>" class="w-full h-full object-cover object-top transition duration-500 group-hover:scale-105" onerror="this.src='https://almai.id/images/alma.gif'">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-60"></div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <h2 class="text-xl font-bold truncate leading-tight"><?= esc($wpa['name']) ?></h2>
                            <p class="text-accent text-xs font-bold uppercase tracking-wider mt-1"><?= esc($wpa['specialty']) ?></p>
                        </div>
                        <!-- Rating Badge -->
                        <div class="absolute top-4 right-4 bg-black/60 backdrop-blur-md border border-white/10 px-3 py-1 rounded-full flex items-center gap-1">
                            <i class="fas fa-star text-yellow-500 text-xs"></i>
                            <span class="text-white font-bold text-sm"><?= esc($wpa['rating']) ?></span>
                        </div>
                    </div>
                    <div class="p-5">
                        <!-- Stats Compact with Icons -->
                        <div class="grid grid-cols-4 gap-3 mb-6 border-t border-b border-white/10 py-4">
                            <div class="text-center group" title="Pengalaman">
                                <div class="flex items-center justify-center gap-1.5">
                                    <i class="fas fa-briefcase text-accent/70 text-sm"></i>
                                    <h3 class="font-bold text-accent text-base leading-none group-hover:scale-110 transition"><?php
                                                                                                                                $exp = $wpa['experience'];
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
                                <span id="followText"><?= ($isFollowing ?? false) ? 'Mengikuti' : 'Ikuti WPA' ?></span>
                            </button>
                            <!-- Contact WhatsApp -->
                            <button onclick="openContactModal()"
                                class="flex items-center justify-center gap-2 w-full py-3 bg-accent text-black rounded-xl font-bold hover:bg-white transition shadow-lg shadow-accent/5">
                                <i class="fab fa-whatsapp"></i> Hubungi WPA
                            </button>
                            <a href="<?= base_url('advokasi/' . $wpa['slug']) ?>"
                                class="flex items-center justify-center gap-2 w-full py-3 bg-accent text-black rounded-xl font-bold hover:bg-white transition shadow-lg shadow-accent/5">
                                <i class="fas fa-shield-alt"></i> Advokasi
                            </a>
                        </div>

                        <!-- Social Media Embeds -->
                        <?php if (!empty($wpa['instagram']) || !empty($wpa['youtube']) || ($wpa['slug'] ?? '') === 'agi' || ($wpa['slug'] ?? '') === 'eka'): ?>
                            <div class="mt-6 border-t border-white/10 pt-6">
                                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Social Media Profile</h3>
                                <div class="space-y-4">
                                    <!-- Instagram Feed -->
                                    <?php if (!empty($instagramData)): ?>
                                        <?php $igService = new \App\Libraries\InstagramService(); ?>
                                        <div class="bg-[#1a1a1a] border border-white/10 rounded-xl overflow-hidden hover:border-pink-500/50 transition-all duration-300">
                                            <div class="flex items-center justify-between p-4 border-b border-white/10">
                                                <div class="flex items-center gap-3">
                                                    <div class="relative w-12 h-12 rounded-full overflow-hidden flex-shrink-0">
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
                                                <a href="https://instagram.com/<?= esc($instagramData['username'] ?? '') ?>" target="_blank" class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition">
                                                    <i class="fab fa-instagram"></i> Follow
                                                </a>
                                            </div>
                                            <div class="grid grid-cols-2 gap-4 p-4 text-center">
                                                <div>
                                                    <div class="font-bold text-white text-lg"><?= esc($igService->formatFollowerCount($instagramData['edge_followed_by']['count'] ?? null)) ?></div>
                                                    <div class="text-xs text-gray-500">Followers</div>
                                                </div>
                                                <div class="border-l border-white/10">
                                                    <div class="font-bold text-white text-lg"><?= esc($igService->formatFollowerCount($instagramData['edge_follow']['count'] ?? null)) ?></div>
                                                    <div class="text-xs text-gray-500">Following</div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <!-- YouTube Channel -->
                                    <?php if (!empty($youtubeData)): ?>
                                        <div class="bg-[#1a1a1a] border border-white/10 rounded-xl overflow-hidden hover:border-red-600/50 transition-all duration-300">
                                            <div class="flex items-center justify-between p-4 border-b border-white/10">
                                                <div class="flex items-center gap-3">
                                                    <div class="relative w-12 h-12 rounded-full overflow-hidden flex-shrink-0">
                                                        <?php
                                                        $ytService = new \App\Libraries\YoutubeService();
                                                        $avatarUrl = $ytService->getAvatarUrl($youtubeData['avatar'] ?? [], 120);
                                                        ?>
                                                        <img src="<?= esc($avatarUrl) ?>" alt="<?= esc($youtubeData['title'] ?? '') ?>" class="w-full h-full object-cover" onerror="this.src='https://via.placeholder.com/100?text=YT'">
                                                        <?php if ($youtubeData['verified'] ?? false): ?>
                                                            <i class="fas fa-check-circle text-gray-400 absolute -bottom-1 -right-1 text-sm bg-[#1a1a1a] rounded-full"></i>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div>
                                                        <a href="https://youtube.com/channel/<?= esc($youtubeData['channel_id'] ?? '') ?>" target="_blank" class="font-bold text-white hover:text-accent transition"><?= esc($youtubeData['title'] ?? '') ?></a>
                                                        <p class="text-xs text-gray-500"><?= esc($youtubeData['subscriber_count'] ?? '0 subscribers') ?></p>
                                                    </div>
                                                </div>
                                                <a href="https://youtube.com/channel/<?= esc($youtubeData['channel_id'] ?? '') ?>" target="_blank" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition">
                                                    <i class="fab fa-youtube"></i> Subscribe
                                                </a>
                                            </div>
                                            <div class="grid grid-cols-2 gap-4 p-4 text-center">
                                                <div>
                                                    <div class="font-bold text-white text-lg"><?= esc($ytService->formatSubscriberCount($youtubeData['subscriber_count'] ?? '0')) ?></div>
                                                    <div class="text-xs text-gray-500">Subscribers</div>
                                                </div>
                                                <div class="border-l border-white/10">
                                                    <div class="font-bold text-white text-lg"><?= esc($youtubeData['video_count'] ?? '0 videos') ?></div>
                                                    <div class="text-xs text-gray-500">Videos</div>
                                                </div>
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
            <div class="md:col-span-8 lg:col-span-9 space-y-12">
                <!-- 8 Tahapan SertifikasiSection (Exact from public detail) -->
                <div class="bg-[#111] border border-white/10 rounded-[2.5rem] p-8 md:p-12 shadow-2xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-accent/5 blur-[100px] pointer-events-none"></div>
                    
                    <div class="relative z-10 mb-12">
                        <div class="flex items-center gap-4 mb-4">
                            <i class="fas fa-certificate text-accent text-3xl"></i>
                            <h2 class="text-3xl font-black uppercase tracking-tight text-white leading-none">Perjalanan Sertifikasi <span class="text-accent">WPA</span></h2>
                        </div>
                        <p class="text-sm text-gray-400 max-w-2xl leading-relaxed uppercase tracking-widest text-[10px] opacity-60">Program persiapan sertifikasi Wakil Penasihat Berjangka melalui 8 tahapan terstruktur Almai Indonesia Raya.</p>
                    </div>

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
                                        <i class="fas fa-eye text-accent text-[8px] ml-1 opacity-50"></i>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php if (isset($currentPhase)): ?>
                        <?php $phaseLabel = \App\Models\WpaModel::getPhaseLabel($currentPhase); ?>
                        <div class="mt-12 p-6 bg-accent/5 border border-accent/20 rounded-[1.5rem] flex items-center justify-between group">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-accent/10 flex items-center justify-center text-accent">
                                    <i class="fas fa-info-circle text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1 leading-none">Status Sertifikasi</p>
                                    <p class="text-sm md:text-base font-black text-white leading-none">
                                        <?php if ($currentPhase >= 9): ?>
                                            TELAH MENYELESAIKAN SELURUH PROGRAM SERTIFIKASI
                                        <?php else: ?>
                                            Sedang di Fase <?= (int) $currentPhase ?>: <span class="text-accent underline underline-offset-4 decoration-accent/30"><?= esc($phaseLabel) ?></span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                            <div class="hidden md:flex flex-col items-end opacity-40 group-hover:opacity-100 transition duration-500">
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest leading-none">Verifikasi Oleh</p>
                                <p class="text-[10px] font-bold text-accent uppercase leading-none mt-1">Alma Indonesia Raya</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- MQL5 Widget Section (Copy from public) -->
                <?php if (!empty($wpa['mql5_widget_url'])): ?>
                    <div class="bg-[#111] border border-white/10 rounded-[2.5rem] p-8 md:p-12 shadow-2xl overflow-hidden group">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                            <div>
                                <div class="flex items-center gap-4 mb-2">
                                    <i class="fas fa-chart-line text-accent text-2xl"></i>
                                    <h2 class="text-2xl md:text-3xl font-black uppercase tracking-tight text-white leading-none">Live Performance</h2>
                                </div>
                                <p class="text-[10px] text-gray-600 uppercase tracking-[0.3em] font-bold opacity-60">Verified MetaTrader Signal Statistics from MQL5</p>
                            </div>
                            <a href="<?= esc($wpa['mql5_widget_url']) ?>" target="_blank" class="px-8 py-4 bg-accent text-black rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-white hover:scale-105 transition shadow-lg shadow-accent/20">Full Analytics</a>
                        </div>
                        <div class="rounded-[1.5rem] overflow-hidden border border-white/5 bg-black/40 group-hover:border-accent/30 transition duration-500">
                            <iframe frameborder="0" width="100%" height="160" src="<?= esc($wpa['mql5_widget_url']) ?>" class="grayscale opacity-60 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-700"></iframe>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Layanan List (Grid) -->
                <?php if (!empty($layananByWpa)): ?>
                    <div class="space-y-8">
                        <div class="flex items-center justify-between px-2">
                            <div>
                                <h2 class="text-2xl md:text-3xl font-black uppercase tracking-tight text-white leading-none">Layanan <span class="text-accent">Unggulan</span></h2>
                                <p class="text-[10px] text-gray-500 uppercase tracking-widest mt-3 font-bold opacity-60">Program Bimbingan & Tools Eksklusif Mentor</p>
                            </div>
                            <div class="hidden md:block w-32 h-1 bg-accent/20 rounded-full"></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
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
                                } elseif ($layanan['thumbnail'] && strpos($layanan['thumbnail'], 'http') !== 0) {
                                    $layanan['thumbnail'] = base_url('file/' . ltrim($layanan['thumbnail'], '/'));
                                }
                                ?>
                                <?= view('partials/cards/layanan_card', ['layanan' => $layanan, 'use_dashboard_path' => true]) ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Contact WPA Modal (Exact match from public) -->
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
                <label class="block text-sm text-gray-400 mb-2 font-bold uppercase tracking-widest text-[10px]">Nama Lengkap</label>
                <input type="text" id="contactName" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none text-sm" placeholder="Nama Anda">
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-2 font-bold uppercase tracking-widest text-[10px]">No. WhatsApp</label>
                <input type="tel" id="contactPhone" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none text-sm" placeholder="08xxxxxxxxxx">
            </div>

            <div>
                <label class="block text-sm text-gray-400 mb-2 font-bold uppercase tracking-widest text-[10px]">Tujuan Konsultasi</label>
                <textarea id="contactPurpose" required rows="3" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none resize-none text-sm" placeholder="Jelaskan tujuan konsultasi Anda..."></textarea>
            </div>

            <p class="text-[10px] text-gray-500 font-medium">
                <i class="fas fa-info-circle mr-1"></i> Data Anda akan disimpan dan Anda akan terdaftar dengan kode referral WPA ini.
            </p>

            <button type="submit" class="w-full py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition shadow-[0_0_20px_rgba(51,232,24,0.3)]">
                <i class="fab fa-whatsapp mr-2"></i> Lanjut ke WhatsApp
            </button>
        </form>
    </div>
</div>

<!-- Certificate Modal (Exact match from public) -->
<div id="certificateModal" class="certificate-modal">
    <div class="certificate-modal-content">
        <div class="certificate-modal-close" onclick="closeCertificate()">&times;</div>
        <img id="modalImg" src="" alt="Certificate View">
        <div id="modalTitle" class="certificate-modal-title"></div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showCertificate(url, title) {
        const modal = document.getElementById('certificateModal');
        const img = document.getElementById('modalImg');
        const tit = document.getElementById('modalTitle');
        img.src = url;
        tit.innerText = title;
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeCertificate() {
        document.getElementById('certificateModal').classList.remove('active');
        document.body.style.overflow = '';
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
        document.body.style.overflow = '';
    }

    // Close on bg click
    document.getElementById('contactModal').addEventListener('click', function(e) {
        if (e.target === this) closeContactModal();
    });

    // Handle Contact Form Submit
    document.getElementById('contactForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();

        const name = document.getElementById('contactName').value.trim();
        const phone = document.getElementById('contactPhone').value.trim();
        const purpose = document.getElementById('contactPurpose').value.trim();

        if (!name || !phone || !purpose) {
            Swal.fire({
                icon: 'warning',
                text: 'Mohon lengkapi semua field',
                background: '#111',
                color: '#fff',
                confirmButtonColor: '#33e818'
            });
            return;
        }

        // Save lead to database
        try {
            await fetch('<?= base_url('api/chat/save-lead') ?>', {
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
        } catch (error) {
            console.error('Error saving lead:', error);
        }

        // Redirect to WhatsApp (Central Tracking Number)
        const message = `Halo, saya ingin konsultasi dengan <?= esc($wpa['name']) ?>.\n\nNama: ${name}\nNo. HP: ${phone}\nTujuan: ${purpose}`;
        const waUrl = `https://wa.me/6285183231800?text=${encodeURIComponent(message)}`;

        window.open(waUrl, '_blank');
        closeContactModal();
        document.getElementById('contactForm').reset();
    });

    // Close on escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeCertificate();
    });

    function toggleFollow(wpaId) {
        const btn = document.getElementById('followBtn');
        const icon = document.getElementById('followIcon');
        const text = document.getElementById('followText');
        const count = document.getElementById('followerCount');

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
                        btn.className = "w-full py-4 rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] transition flex items-center justify-center gap-2 bg-white/5 text-white border border-white/20 hover:bg-red-500/10 hover:border-red-500 hover:text-red-500";
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
                        btn.className = "w-full py-4 rounded-2xl font-black uppercase tracking-[0.2em] text-[10px] transition flex items-center justify-center gap-2 bg-accent text-black hover:bg-white hover:scale-[1.02]";
                        icon.className = "fas fa-plus";
                        text.innerText = "Ikuti WPA";
                    }
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
                    confirmButtonText: 'Tutup'
                });
            });
    }
</script>
<?= $this->endSection() ?>
