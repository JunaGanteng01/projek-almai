<?= $this->extend('cwpa/layouts/main') ?>

<?= $this->section('content') ?>
<?php
$isTool = ($kelas['type'] ?? '') === 'tool';
$isLive = $isLive ?? (($kelas['type'] ?? 'recorded') === 'live');

if ($isTool) {
    $badgeColor = 'bg-purple-600';
    $badgeIcon = 'fa-robot';
    $badgeText = 'EXPERT ADVISOR';
    $termLayanan = 'Expert Advisor';
} else {
    $badgeColor = $isLive ? 'bg-blue-500' : 'bg-purple-500';
    $badgeIcon = $isLive ? 'fa-video' : 'fa-play-circle';
    $badgeText = $isLive ? 'LIVE SESSION' : 'VIDEO GUIDE';
    $termLayanan = $isLive ? 'Sesi Live' : 'Layanan';
}
?>

<!-- Back Button -->
<div class="mb-6">
    <?php if ($isPublic ?? false): ?>
        <a href="<?= base_url('layanan') ?>" class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition">
            <i class="fas fa-arrow-left"></i>
            <span class="text-sm">Kembali ke Daftar Layanan</span>
        </a>
    <?php else: ?>
        <a href="<?= base_url('cwpa/dashboard/layanan') ?>" class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition">
            <i class="fas fa-arrow-left"></i>
            <span class="text-sm">Kembali ke Layanan Saya</span>
        </a>
    <?php endif; ?>
</div>

<!-- Layanan Info Card -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden mb-8">
    <div class="relative">
        <img src="<?= esc($kelas['thumbnail'] ?? 'https://via.placeholder.com/800x400') ?>" alt="<?= esc($kelas['title']) ?>" class="w-full h-48 md:h-64 object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
        <div class="absolute bottom-4 left-4 right-4">
            <span class="px-3 py-1 <?= $badgeColor ?> text-white text-xs font-bold rounded-full mb-2 inline-block">
                <i class="fas <?= $badgeIcon ?> mr-1"></i> <?= $badgeText ?>
            </span>
            <h1 class="text-2xl md:text-3xl font-bold"><?= esc($kelas['title']) ?></h1>
        </div>
    </div>
    <div class="p-6">
        <?php if ($wpa): ?>
            <div class="flex items-center gap-4 mb-6">
                <?php
                $wpaPhoto = $wpa['photo'] ?? '';
                if ($wpaPhoto && !str_starts_with($wpaPhoto, 'http')) {
                    $wpaPhoto = base_url('file/' . $wpaPhoto);
                } elseif (!$wpaPhoto) {
                    $wpaPhoto = 'https://via.placeholder.com/100';
                }
                ?>
                <img src="<?= esc($wpaPhoto) ?>" alt="<?= esc($wpa['name']) ?>" class="w-14 h-14 rounded-full object-cover border-2 border-accent">
                <div>
                    <p class="font-bold"><?= esc($wpa['name']) ?></p>
                    <p class="text-sm text-gray-400"><?= esc($wpa['specialty'] ?? '') ?></p>
                </div>
            </div>
        <?php endif; ?>
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 text-center">
            <div class="bg-black/50 rounded-xl p-3 sm:p-4">
                <i class="fas fa-clock text-accent text-lg sm:text-xl mb-2"></i>
                <p class="text-xs sm:text-sm text-gray-400">Durasi</p>
                <p class="font-bold text-sm sm:text-base"><?= esc($kelas['duration'] ?? '-') ?></p>
            </div>
            <div class="bg-black/50 rounded-xl p-3 sm:p-4">
                <i class="fas fa-signal text-accent text-lg sm:text-xl mb-2"></i>
                <p class="text-xs sm:text-sm text-gray-400">Level</p>
                <p class="font-bold text-sm sm:text-base"><?= esc($kelas['level'] ?? '-') ?></p>
            </div>
            <div class="bg-black/50 rounded-xl p-3 sm:p-4">
                <i class="fas fa-users text-accent text-lg sm:text-xl mb-2"></i>
                <p class="text-xs sm:text-sm text-gray-400">Peserta</p>
                <p class="font-bold text-sm sm:text-base"><?= number_format($kelas['students'] ?? 0) ?></p>
            </div>
            <div class="bg-black/50 rounded-xl p-3 sm:p-4">
                <i class="fas fa-star text-yellow-500 text-lg sm:text-xl mb-2"></i>
                <p class="text-xs sm:text-sm text-gray-400">Rating</p>
                <p class="font-bold text-sm sm:text-base"><?= esc($kelas['rating'] ?? '-') ?></p>
            </div>
        </div>
    </div>
    
    <!-- Public Access CTA Section -->
    <?php if ($isPublic ?? false): ?>
    <div class="border-t border-white/10 p-6">
        <div class="space-y-3">
            <?php if (!empty($packages)): ?>
                <?php
                $isLoggedIn = session()->get('isLoggedIn');
                
                // Prepare checkout URL - use layanan checkout with slug
                $slug = $kelas['slug'] ?? (isset($kelas['id']) ? 'layanan-' . $kelas['id'] : 'cwpa');
                $buyUrl = base_url('checkout/layanan/' . $slug);
                if (!empty($packages)) {
                    $buyUrl .= '?package=' . $packages[0]['id'];
                }
                
                if (!$isLoggedIn) {
                    // For non-logged-in users, show login/register CTA
                    $buyUrl = base_url('register?redirect=' . urlencode('checkout/layanan/' . $slug));
                    $buttonText = 'Daftar & Checkout';
                    $buttonIcon = 'fa-user-plus';
                    $warningText = 'Silakan daftar atau login terlebih dahulu untuk melakukan pembelian';
                } else {
                    $buttonText = 'Lanjut ke Checkout';
                    $buttonIcon = 'fa-shopping-cart';
                    $warningText = '';
                }
                ?>
                <a href="<?= $buyUrl ?>" class="block w-full text-center py-4 bg-gradient-to-r from-accent to-green-600 text-black font-bold rounded-xl hover:from-green-500 hover:to-accent transition shadow-lg shadow-accent/30 flex items-center justify-center gap-2">
                    <i class="fas <?= $buttonIcon ?>"></i>
                    <?= $buttonText ?>
                </a>
                <?php if ($warningText): ?>
                    <p class="text-xs text-center text-gray-400 mt-2">
                        <i class="fas fa-info-circle mr-1"></i> <?= $warningText ?>
                    </p>
                <?php endif; ?>
            <?php else: ?>
                <a href="https://wa.me/6285183231800?text=Halo, saya tertarik dengan layanan <?= urlencode($kelas['name'] ?? $kelas['title'] ?? '') ?>" target="_blank" class="block w-full text-center py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                    <i class="fab fa-whatsapp mr-2"></i> Hubungi Kami
                </a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-8">
        <h3 class="font-bold mb-4 flex items-center gap-2">
            <i class="fas fa-info-circle text-accent"></i> Detail Layanan
        </h3>
        <div class="prose prose-invert max-w-none text-gray-300">
            <?= $kelas['description'] ?>
        </div>
    </div>
    */ ?>

    <!-- Requirements & Changelog (Tools only) -->
    <?php if ($isTool): ?>
        <div class="grid md:grid-cols-2 gap-6 mb-8">
            <?php if (!empty($kelas['requirements']) && is_array($kelas['requirements'])): ?>
                <div class="bg-[#111] border border-white/10 rounded-2xl p-6 h-full">
                    <h4 class="text-white font-bold mb-4 flex items-center gap-2">
                        <i class="fas fa-microchip text-accent"></i> System Requirements
                    </h4>
                    <ul class="space-y-2">
                        <?php foreach ($kelas['requirements'] as $req): ?>
                            <li class="flex items-start gap-2 text-xs text-gray-400">
                                <i class="fas fa-circle text-[6px] text-accent mt-1.5"></i>
                                <span><?= esc($req) ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!empty($kelas['changelog'])): ?>
                <div class="bg-[#111] border border-white/10 rounded-2xl p-6 h-full">
                    <h4 class="text-white font-bold mb-4 flex items-center gap-2">
                        <i class="fas fa-history text-accent"></i> Changelog
                    </h4>
                    <div class="prose prose-sm prose-invert max-w-none text-gray-400 text-xs">
                        <?php 
                        $base_changelog = $kelas['changelog'] ?? '';
                        if (is_array($base_changelog)) {
                            $base_changelog = implode("\n", array_map(function($item) {
                                return is_array($item) ? json_encode($item) : $item;
                            }, $base_changelog));
                        }
                        echo nl2br(esc($base_changelog));
                        ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    
<?php
$hasLicense = isset($tool) && isset($tool['is_license_product']) && $tool['is_license_product'] == 1;
$tahapCount = $hasLicense ? 5 : 4;

// Determine current active step based on completion status
$currentStep = 0; // 0 = Informasi (default)
if (isset($license) && !empty($license)) {
    $currentStep = 2; // Pelatihan (license already activated)
}
if (!empty($isCompleted)) {
    $currentStep = 4; // Sertifikat (completed)
} elseif (isset($license) && !empty($license)) {
    $currentStep = 2; // Pelatihan
} elseif ($hasLicense && empty($license)) {
    $currentStep = 1; // Aktivasi EA (needs activation)
}
?>
<!-- 5 Tahap Pembelajaran Card -->
<div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-8 w-full">
    <h3 class="font-bold mb-6 flex items-center gap-2 text-white">
        <i class="fas fa-layer-group text-accent"></i> <?= $tahapCount ?> Tahap Pembelajaran
    </h3>

    <!-- Roadmap Tabs -->
    <style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    .running-gif {
        width: 20px;
        height: 20px;
        object-fit: contain;
    }
    
    @media (min-width: 640px) {
        .running-gif {
            width: 24px;
            height: 24px;
        }
    }
    
    @media (min-width: 768px) {
        .running-gif {
            width: 28px;
            height: 28px;
        }
    }
    
    @media (min-width: 1024px) {
        .running-gif {
            width: 35px;
            height: 35px;
        }
    }
    
    /* Roadmap container with lines */
    .roadmap-container {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 0;
        padding: 0 0.5rem;
        position: relative;
    }
    
    @media (min-width: 640px) {
        .roadmap-container {
            padding: 0 1rem;
        }
    }
    
    @media (min-width: 768px) {
        .roadmap-container {
            padding: 0 1.5rem;
        }
    }
    
    @media (min-width: 1024px) {
        .roadmap-container {
            padding: 0 2rem;
        }
    }
    
    /* Roadmap step item */
    .roadmap-step-item {
        position: relative;
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    /* Connector line - using before pseudo element */
    .roadmap-step-item:not(:first-child)::before {
        content: '';
        position: absolute;
        top: 16px;
        left: 0;
        right: 50%;
        height: 2px;
        background: #2a2a2a;
        z-index: 0;
    }
    
    @media (min-width: 640px) {
        .roadmap-step-item:not(:first-child)::before {
            top: 20px;
        }
    }
    
    @media (min-width: 768px) {
        .roadmap-step-item:not(:first-child)::before {
            top: 24px;
            height: 2.5px;
        }
    }
    
    @media (min-width: 1024px) {
        .roadmap-step-item:not(:first-child)::before {
            top: 28px;
            height: 3px;
        }
    }
    
    /* Right side of connector */
    .roadmap-step-item:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 16px;
        left: 50%;
        right: 0;
        height: 2px;
        background: #2a2a2a;
        z-index: 0;
    }
    
    @media (min-width: 640px) {
        .roadmap-step-item:not(:last-child)::after {
            top: 20px;
        }
    }
    
    @media (min-width: 768px) {
        .roadmap-step-item:not(:last-child)::after {
            top: 24px;
            height: 2.5px;
        }
    }
    
    @media (min-width: 1024px) {
        .roadmap-step-item:not(:last-child)::after {
            top: 28px;
            height: 3px;
        }
    }
    
    /* Completed state - green line */
    .roadmap-step-item.completed:not(:first-child)::before {
        background: #33E818;
        box-shadow: 0 0 10px rgba(51, 232, 24, 0.5);
    }
    
    .roadmap-step-item.completed:not(:last-child)::after {
        background: #33E818;
        box-shadow: 0 0 10px rgba(51, 232, 24, 0.5);
    }
    </style>

    <div class="relative w-full overflow-x-auto pb-4 md:pb-2 no-scrollbar">
        <div class="roadmap-container relative">
            <?php
            $roadmapSteps = [
                ['id' => 'informasi', 'label' => 'Informasi', 'icon' => 'fa-info-circle'],
            ];
            if ($hasLicense) {
                $roadmapSteps[] = ['id' => 'aktivasi', 'label' => 'Aktivasi EA', 'icon' => 'fa-key'];
            }
            $roadmapSteps[] = ['id' => 'pelatihan', 'label' => 'Pelatihan', 'icon' => 'fa-graduation-cap'];
            $roadmapSteps[] = ['id' => 'selesai', 'label' => 'Selesai', 'icon' => 'fa-check-circle'];
            $roadmapSteps[] = ['id' => 'sertifikat', 'label' => 'Sertifikat', 'icon' => 'fa-certificate'];
            ?>

            <?php foreach ($roadmapSteps as $index => $step): 
                $isCompleted = $index < $currentStep;
                $isActive = $index == $currentStep;
                $statusClass = $isCompleted ? 'completed' : '';
            ?>
            <div class="relative flex flex-col items-center flex-1 cursor-pointer group roadmap-step-item <?= $statusClass ?>" 
                 onclick="switchRoadmap('<?= $step['id'] ?>', <?= $index ?>, <?= count($roadmapSteps) ?>)" 
                 data-id="<?= $step['id'] ?>">
                
                <div id="tab-<?= $step['id'] ?>" class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 lg:w-14 lg:h-14 rounded-full bg-[#111] border-2 sm:border-[2.5px] md:border-[3px] <?= $isCompleted || $isActive ? 'border-accent shadow-[0_0_15px_rgba(51,232,24,0.3)]' : 'border-white/10' ?> flex items-center justify-center transition-all duration-300 relative z-10 mx-auto">
                    <?php if ($isActive): ?>
                        <!-- Show running GIF for active step -->
                        <img src="https://almai.id/images/lari.gif" alt="On Progress" class="running-gif" id="icon-<?= $step['id'] ?>">
                    <?php else: ?>
                        <i class="fas <?= $step['icon'] ?> <?= $isCompleted || $isActive ? 'text-accent' : 'text-gray-600' ?> text-xs sm:text-sm md:text-base lg:text-xl group-hover:text-accent transition-colors" id="icon-<?= $step['id'] ?>"></i>
                    <?php endif; ?>
                    
                    <?php if ($isCompleted): ?>
                    <div class="absolute -top-0.5 -right-0.5 sm:-top-1 sm:-right-1 w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 rounded-full bg-yellow-500 border border-black flex items-center justify-center transition-opacity duration-300" id="badge-<?= $step['id'] ?>">
                        <i class="fas fa-star text-black text-[5px] sm:text-[6px] md:text-[8px]"></i>
                    </div>
                    <?php else: ?>
                    <div class="absolute -top-0.5 -right-0.5 sm:-top-1 sm:-right-1 w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 rounded-full bg-yellow-500 border border-black flex items-center justify-center opacity-0 transition-opacity duration-300" id="badge-<?= $step['id'] ?>">
                        <i class="fas fa-star text-black text-[5px] sm:text-[6px] md:text-[8px]"></i>
                    </div>
                    <?php endif; ?>
                </div>

                <div id="label-<?= $step['id'] ?>" class="mt-2 sm:mt-3 md:mt-4 text-center <?= $isCompleted || $isActive ? 'font-bold text-white' : 'font-medium text-gray-500' ?> text-[9px] sm:text-[10px] md:text-xs lg:text-sm leading-tight transition-colors duration-300 px-1">
                    <?= $step['label'] ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- Progress Indicator Text -->
    <div class="mt-4 p-3 sm:p-4 bg-accent/10 border border-accent/30 rounded-xl">
        <div class="flex items-center gap-2 text-accent text-xs sm:text-sm">
            <i class="fas fa-info-circle flex-shrink-0"></i>
            <span class="font-medium">
                <?php if ($currentStep == 0): ?>
                    Mulai dengan membaca informasi layanan
                <?php elseif ($currentStep == 1): ?>
                    Sedang di tahap: Aktivasi Lisensi EA
                <?php elseif ($currentStep == 2): ?>
                    Sedang di tahap: Pelatihan & Pembelajaran
                <?php elseif ($currentStep == 3): ?>
                    Sedang di tahap: Penyelesaian & Testimoni
                <?php else: ?>
                    Selamat! Anda telah menyelesaikan semua tahap
                <?php endif; ?>
            </span>
        </div>
    </div>
</div>

<!-- ROADMAP CONTENT: INFORMASI -->
<div id="content-informasi" class="roadmap-section block">
<?php if (!empty($kelas['layanan_utama'])): ?>
<div class="bg-gradient-to-r from-accent/20 to-purple-500/20 border border-accent/30 rounded-2xl p-6 mb-8 text-white">
    <h3 class="font-bold mb-2 flex items-center gap-2 text-lg text-accent">
        <i class="fas fa-star"></i> Layanan Utama
    </h3>
    <div class="text-gray-300 text-sm leading-relaxed">
        <?php 
        $layanan_utama = $kelas['layanan_utama'] ?? '';
        if (is_array($layanan_utama)) {
            $layanan_utama = implode("\n", array_map(function($item) {
                return is_array($item) ? json_encode($item) : $item;
            }, $layanan_utama));
        }
        echo nl2br(esc($layanan_utama));
        ?>
    </div>
</div>
<?php endif; ?>

<!-- Peringatan Pendaftaran Akun -->
<div class="bg-gradient-to-r from-blue-500/20 to-accent/20 border border-blue-500/30 rounded-2xl p-4 sm:p-6 mb-8">
    <div class="flex flex-col sm:flex-row items-start gap-4">
        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-500/30 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fas fa-user-plus text-blue-400 text-lg sm:text-xl"></i>
        </div>
        <div class="flex-1">
            <h3 class="font-bold text-base sm:text-lg mb-2 flex items-center gap-2">
                <span class="text-blue-400">Belum Punya Akun Trading?</span>
            </h3>
            <p class="text-gray-300 text-xs sm:text-sm mb-4 leading-relaxed">
                Untuk menggunakan Expert Advisor dan layanan trading lainnya, Anda memerlukan akun trading aktif. 
                Daftar sekarang di broker resmi  dan dapatkan akses penuh ke semua fitur.
            </p>
            <a href="https://mifx.com/demo/r/almai" 
               target="_blank"
               class="inline-flex items-center gap-2 px-4 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-r from-blue-500 to-accent text-black font-bold rounded-xl hover:from-accent hover:to-blue-500 transition shadow-lg shadow-blue-500/20 text-xs sm:text-sm">
                <i class="fas fa-external-link-alt"></i>
                <span>Daftar Akun Trading Sekarang</span>
            </a>
            <p class="text-[10px] sm:text-xs text-gray-400 mt-3 flex items-center gap-1">
                <i class="fas fa-shield-check text-accent"></i>
                <span>Broker resmi terdaftar dan diawasi BAPPEBTI</span>
            </p>
        </div>
    </div>
</div>

<!-- Materials & Resources -->
<div class="bg-[#111] border border-white/10 rounded-2xl p-4 sm:p-6 mb-8">
    <h3 class="font-bold mb-3 sm:mb-4 flex items-center gap-2 text-sm sm:text-base">
        <i class="fas fa-folder text-accent"></i> Materi & Resources
    </h3>
    <p class="text-gray-400 text-xs sm:text-sm mb-3 sm:mb-4">Materi pendukung untuk layanan ini</p>
    <div class="space-y-2 sm:space-y-3">
        <?php if (!empty($materials)): ?>
            <?php foreach ($materials as $material):
                $fileExt = strtolower($material['file_type'] ?? '');
                $icon = 'fa-file';
                $colorClass = 'text-gray-400';
                $bgColorClass = 'bg-gray-500/10';

                if (in_array($fileExt, ['pdf', 'doc', 'docx'])) {
                    $icon = 'fa-file-pdf';
                    $colorClass = 'text-red-500';
                    $bgColorClass = 'bg-red-500/10';
                } elseif (in_array($fileExt, ['zip', 'rar', '7z'])) {
                    $icon = 'fa-file-archive';
                    $colorClass = 'text-orange-500';
                    $bgColorClass = 'bg-orange-500/10';
                } elseif (in_array($fileExt, ['jpg', 'png', 'jpeg'])) {
                    $icon = 'fa-file-image';
                    $colorClass = 'text-blue-500';
                    $bgColorClass = 'bg-blue-500/10';
                } elseif (in_array($fileExt, ['ex4', 'ex5', 'mq4', 'mq5'])) {
                    $icon = 'fa-robot';
                    $colorClass = 'text-accent';
                    $bgColorClass = 'bg-accent/10';
                }
            ?>
                <div class="flex items-center justify-between p-3 sm:p-4 bg-black/50 rounded-xl border border-white/5 hover:border-white/20 transition group">
                    <div class="flex items-center gap-2 sm:gap-3 flex-1 min-w-0">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 <?= $bgColorClass ?> rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas <?= $icon ?> <?= $colorClass ?> text-sm sm:text-base"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="font-medium text-xs sm:text-sm truncate"><?= esc($material['title']) ?></p>
                            <p class="text-[9px] sm:text-[10px] text-gray-500 uppercase tracking-wider"><?= esc($material['file_type']) ?> • <?= number_format($material['file_size'] / 1024, 1) ?> KB</p>
                        </div>
                    </div>
                    <a href="<?= base_url('file/' . $material['file_path']) ?>" target="_blank" class="px-3 sm:px-4 py-1.5 sm:py-2 bg-white/5 border border-white/10 rounded-lg text-[10px] sm:text-sm hover:bg-accent hover:text-black hover:border-accent transition font-bold flex-shrink-0 ml-2">
                        <i class="fas fa-download mr-0 sm:mr-1"></i> <span class="hidden sm:inline">Download</span>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="p-6 sm:p-8 bg-black/30 rounded-xl text-center border border-dashed border-white/10">
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                    <i class="fas fa-folder-open text-gray-600 text-xl sm:text-2xl"></i>
                </div>
                <p class="text-gray-400 text-xs sm:text-sm">Belum ada materi tambahan tersedia untuk saat ini.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

   

        
</div>

<!-- ROADMAP CONTENT: AKTIVASI EA -->
<?php if ($hasLicense): ?>
<div id="content-aktivasi" class="roadmap-section hidden">
<!-- License Activation Section (for Events & Tools with EA License) -->
<?php if (isset($tool) && isset($tool['is_license_product']) && $tool['is_license_product'] == 1 && !isset($license)): ?>
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-8">
        <h3 class="font-bold mb-4 flex items-center gap-2">
            <i class="fas fa-key text-accent"></i> Aktivasi Lisensi EA
        </h3>
        <div class="bg-black/40 border border-white/10 rounded-xl p-6">
            <div class="flex items-start gap-4 mb-6">
                <div class="w-16 h-16 bg-accent/20 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-robot text-accent text-2xl"></i>
                </div>
                <div>
                    <p class="font-bold text-lg mb-2">Aktivasi Expert Advisor</p>
                    <p class="text-sm text-gray-400">
                        Layanan ini memberikan akses ke Expert Advisor (EA). Silakan aktivasi lisensi dengan memasukkan nomor akun trading Anda.
                    </p>
                </div>
            </div>

            <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-4 mb-6">
                <div class="flex items-start gap-2 text-yellow-400">
                    <i class="fas fa-exclamation-triangle text-lg mt-0.5"></i>
                    <div class="text-sm">
                        <p class="font-bold mb-1">PENTING:</p>
                        <ul class="list-disc list-inside space-y-1 text-xs">
                            <li>Akun trading yang Anda daftarkan akan di-<strong>lock</strong> dan tidak dapat diubah</li>
                            <li>Pastikan nomor akun sudah benar sebelum aktivasi</li>
                            <li>License Key akan digenerate otomatis setelah aktivasi</li>
                            <?php if (!empty($tool['license_duration']) && $tool['license_duration'] > 0): ?>
                                <li>Durasi Lisensi: <strong><?= $tool['license_duration'] ?> Hari</strong></li>
                            <?php else: ?>
                                <li>Durasi Lisensi: <strong>Lifetime (Selamanya)</strong></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <form action="<?= base_url('cwpa/dashboard/layanan-detail/' . $kelas['id'] . '/activate-license') ?>" method="post" class="space-y-4">
                <?= csrf_field() ?>
                <input type="hidden" name="transaksi_id" value="<?= esc($transaksiId ?? '') ?>">

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-building mr-1"></i> Nama Broker
                    </label>
                    <input type="text" name="broker_name" required
                        placeholder="Contoh: Panen Kapital Berjanka, RRFX, Java FX, Gatra Mega  Berjangka , Monex , dll"
                        class="w-full bg-black/50 border border-white/20 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent transition">
                    <p class="text-xs text-gray-500 mt-1">Masukkan nama broker tempat akun trading Anda terdaftar</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        <i class="fas fa-hashtag mr-1"></i> Nomor Akun Trading (MT5/MT4)
                    </label>
                    <input type="text" name="account_number" required
                        placeholder="Contoh: 12345678"
                        class="w-full bg-black/50 border border-white/20 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent transition">
                    <p class="text-xs text-gray-500 mt-1">Masukkan nomor akun trading yang akan digunakan untuk EA</p>
                </div>

                <button type="submit"
                    class="w-full py-4 bg-gradient-to-r from-accent to-green-600 text-black font-bold rounded-xl hover:from-green-500 hover:to-accent transition shadow-lg shadow-accent/30 flex items-center justify-center gap-2 group">
                    <i class="fas fa-check-circle group-hover:scale-110 transition-transform"></i>
                    <span>Aktivasi Lisensi Sekarang</span>
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-white/10">
                <p class="text-xs text-gray-400 flex items-start gap-2">
                    <i class="fas fa-info-circle text-blue-400 mt-0.5"></i>
                    <span>Setelah aktivasi, License Key akan ditampilkan di halaman ini dan dapat Anda gunakan pada parameter input EA di MetaTrader.</span>
                </p>
            </div>
        </div>
    </div>
<?php endif; ?>


 <!-- License Details & EA Download (After Activation) -->
<?php if (isset($tool) && isset($tool['is_license_product']) && $tool['is_license_product'] == 1 && isset($license)): ?>
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-8">
        <h3 class="font-bold mb-4 flex items-center gap-2">
            <i class="fas fa-robot text-accent"></i> Lisensi & Expert Advisor Anda
        </h3>

        <!-- License Info Card -->
        <div class="bg-gradient-to-r from-accent/20 to-green-600/20 border border-accent/30 rounded-xl p-6 mb-6">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 bg-accent/30 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-check-circle text-accent text-3xl"></i>
                </div>
                <div class="flex-1">
                    <h4 class="text-lg font-bold text-accent mb-2">Lisensi Aktif</h4>
                    <p class="text-sm text-gray-300">
                        Lisensi EA Anda telah aktif dan siap digunakan pada akun trading yang terdaftar.
                    </p>
                </div>
            </div>
        </div>

        <!-- License Details -->
        <div class="bg-black/40 border border-white/10 rounded-xl p-5 mb-6">
            <p class="text-xs text-gray-400 mb-3 font-medium">Detail Lisensi:</p>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-400">License Key</span>
                    <div class="flex items-center gap-2">
                        <code class="font-mono text-accent bg-accent/10 px-3 py-1 rounded text-sm"><?= esc($license['license_key'] ?? '-') ?></code>
                        <button onclick="copyToClipboard('<?= esc($license['license_key'] ?? '') ?>', this)"
                            class="p-2 hover:bg-white/10 rounded-lg transition" title="Copy License Key">
                            <i class="far fa-copy text-gray-400 hover:text-white"></i>
                        </button>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-400">Broker</span>
                    <span class="font-medium"><?= esc($license['broker_name'] ?? '-') ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-400">Akun Trading</span>
                    <span class="font-mono font-medium"><?= esc($license['account_trading_number'] ?? '-') ?></span>
                </div>
                <?php if (!empty($license['expires_at'])): ?>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">Berlaku Sampai</span>
                        <span class="font-medium text-yellow-500"><?= date('d M Y, H:i', strtotime($license['expires_at'])) ?> WIB</span>
                    </div>
                <?php else: ?>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-400">Masa Berlaku</span>
                        <span class="font-medium text-green-500 flex items-center gap-1">
                            <i class="fas fa-infinity"></i> Lifetime (Selamanya)
                        </span>
                    </div>
                <?php endif; ?>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-400">Status</span>
                    <span class="font-bold <?= ($license['status'] ?? 'active') === 'active' ? 'text-green-500' : 'text-red-500' ?>">
                        <i class="fas fa-circle text-[8px] mr-1"></i> <?= strtoupper($license['status'] ?? 'ACTIVE') ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Download EA File -->
        <?php if (!empty($tool['ea_file_path'])): ?>
            <div class="bg-accent/10 border border-accent/30 rounded-xl p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-accent/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-robot text-accent text-xl"></i>
                        </div>
                        <div>
                            <p class="font-bold">Expert Advisor File</p>
                            <p class="text-xs text-gray-400"><?= basename($tool['ea_file_path']) ?></p>
                        </div>
                    </div>
                    <a href="<?= base_url('file/' . $tool['ea_file_path']) ?>"
                        download
                        class="px-6 py-3 bg-accent text-black font-bold rounded-lg hover:bg-white transition flex items-center gap-2 shadow-lg shadow-accent/20">
                        <i class="fas fa-download"></i>
                        <span>Download EA</span>
                    </a>
                </div>
                <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-3">
                    <p class="text-xs text-blue-300 flex items-start gap-2">
                        <i class="fas fa-info-circle text-sm mt-0.5"></i>
                        <span>
                            <strong>Cara Menggunakan:</strong><br>
                            1. Download file EA di atas<br>
                            2. Install EA ke folder MQL5/Experts (MT5) atau MQL4/Experts (MT4)<br>
                            3. Restart MetaTrader<br>
                            4. Pasang EA pada chart dan masukkan License Key pada parameter input<br>
                            5. Pastikan Allow WebRequest sudah diaktifkan di Tools &rarr; Options &rarr; Expert Advisors<br>
                            6. Ceklis Allow Webrequest for listed URL dan tambahkan url: <br>
                            <span class="inline-flex items-center gap-2 mt-1">
                                <code class="bg-black/40 px-2 py-1 rounded text-accent text-[10px] font-mono select-all">https://almai.id/api/license/validate</code>
                                <button onclick="copyToClipboard('https://almai.id/api/license/validate', this)" class="hover:text-white transition" title="Copy URL">
                                    <i class="far fa-copy text-[10px]"></i>
                                </button>
                            </span>
                        </span>
                    </p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Guide -->
        <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-4">
            <p class="text-sm text-yellow-400 flex items-start gap-2">
                <i class="fas fa-key text-lg mt-0.5"></i>
                <span>
                    <strong>Penting:</strong> Simpan License Key Anda dengan aman.
                    Masukkan License Key pada parameter input EA saat pemasangan di MetaTrader untuk aktivasi otomatis.
                </span>
            </p>
        </div>
    </div>
<?php endif; ?>


</div>
<?php endif; ?>

<!-- ROADMAP CONTENT: PELATIHAN -->
<div id="content-pelatihan" class="roadmap-section hidden">
<!-- Tutorial Section -->
<?php
$youtube_tutorials_json = $kelas['youtube_tutorials'] ?? null;
$youtube_tutorials = [];
if ($youtube_tutorials_json) {
    if (strpos(trim($youtube_tutorials_json), '[') === 0 || strpos(trim($youtube_tutorials_json), '{') === 0) {
        $decoded = json_decode($youtube_tutorials_json, true);
        if (is_array($decoded)) {
            $youtube_tutorials = $decoded;
        }
    } else {
        $urls = array_filter(array_map('trim', explode("\n", $youtube_tutorials_json)));
        foreach ($urls as $url) {
            $youtube_tutorials[] = ['title' => 'Tutorial Penggunaan', 'url' => $url];
        }
    }
}
?>

<?php if (!empty($youtube_tutorials)): ?>
    <div class="bg-[#111] border border-white/10 rounded-2xl p-4 sm:p-6 mb-8 shadow-xl relative overflow-hidden group hover:border-[#33E818]/30 transition-colors duration-300">
        <div class="absolute -right-10 -top-10 w-32 h-32 bg-[#33E818]/5 rounded-full blur-3xl group-hover:bg-[#33E818]/10 transition"></div>
        <h3 class="font-bold mb-4 sm:mb-6 flex items-center gap-2 sm:gap-3 text-white text-sm sm:text-base">
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-[#33E818]/10 flex items-center justify-center">
                <i class="fab fa-youtube text-[#33E818] text-base sm:text-lg"></i>
            </div>
            Video Tutorial
        </h3>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 relative z-10">
            <?php foreach ($youtube_tutorials as $index => $tutorial): 
                $embedUrl = $tutorial['url'] ?? '';
                $videoId = '';
                
                // Simple regex to extract 11 char ID
                if (preg_match('/(?:v=|embed\/|youtu\.be\/)([^&?\"\'\s]{11})/', $embedUrl, $match)) {
                    $videoId = $match[1];
                }
                
                $finalUrl = $videoId ? "https://www.youtube.com/embed/" . $videoId : $embedUrl;
            ?>
            <div class="space-y-2 sm:space-y-3 p-2 sm:p-3 bg-black/40 border border-white/5 rounded-xl hover:border-[#33E818]/40 transition group/item">
                <p class="text-xs sm:text-sm font-bold text-gray-300 group-hover/item:text-[#33E818] transition line-clamp-1 px-1"><?= esc($tutorial['title'] ?? 'Tutorial ' . ($index + 1)) ?></p>
                <div class="aspect-video rounded-lg overflow-hidden border border-white/5 bg-black relative shadow-[0_5px_15px_rgba(0,0,0,0.5)]">
                    <iframe class="w-full h-full absolute inset-0" src="<?= esc($finalUrl) ?>" title="<?= esc($tutorial['title'] ?? 'Tutorial') ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen loading="lazy"></iframe>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>


<!-- Join/Access Section -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
            <?php if ($isTool): ?>
                <!-- Tool Access - Download -->
                <h3 class="font-bold mb-4 flex items-center gap-2">
                    <i class="fas fa-download text-accent"></i> Download & Instalasi
                </h3>

                <div class="bg-accent/10 border border-accent/30 rounded-xl p-6 text-center mb-6">
                    <div class="w-20 h-20 bg-accent/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-file-archive text-accent text-3xl"></i>
                    </div>
                    <p class="text-gray-400 mb-4">Silakan unduh file Expert Advisor dan baca panduan instalasi</p>
                    <div class="flex flex-col gap-3">
                        <a href="<?= esc($kelas['download_url'] ?? '#') ?>" target="_blank" class="inline-block w-full py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-lg shadow-lg shadow-accent/20">
                            <i class="fas fa-download mr-2"></i> Download File (<?= strtoupper($kelas['file_type'] ?? 'ZIP') ?>)
                        </a>
                        <?php if (!empty($kelas['guide_url']) && $kelas['guide_url'] !== '#'): ?>
                            <a href="<?= esc($kelas['guide_url']) ?>" target="_blank" class="inline-block w-full py-3 border border-white/20 text-white font-medium rounded-xl hover:border-accent hover:text-accent transition">
                                <i class="fas fa-book mr-2"></i> Buka Panduan Instalasi
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-400">Versi Terbaru</span>
                        <span class="font-mono text-white"><?= esc($kelas['version'] ?? '1.0.0') ?></span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-400">Kompatibilitas</span>
                        <span class="font-mono text-accent">
                            <?php
                            if (!empty($kelas['compatibility']) && is_array($kelas['compatibility'])) {
                                echo implode(", ", $kelas['compatibility']);
                            } else {
                                echo "MT4 / MT5";
                            }
                            ?>
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-400">Lisensi</span>
                        <span class="font-mono text-accent">Lifetime Access</span>
                    </div>

                    <?php if (isset($license)): ?>
                        <div class="mt-4 pt-4 border-t border-white/10">
                            <p class="text-xs text-gray-400 mb-2">Detail Lisensi EA:</p>
                            <div class="bg-black/40 border border-white/10 rounded-lg p-3 space-y-2">
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-gray-400">License Key</span>
                                    <div class="flex items-center gap-2">
                                        <code class="font-mono text-accent bg-accent/10 px-2 py-0.5 rounded"><?= esc($license['license_key'] ?? '-') ?></code>
                                        <button onclick="copyToClipboard('<?= esc($license['license_key'] ?? '') ?>', this)" class="text-gray-400 hover:text-white" title="Copy">
                                            <i class="far fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-gray-400">Akun MT5</span>
                                    <span class="font-mono text-white"><?= esc($license['account_trading_number'] ?? '-') ?></span>
                                </div>
                                <?php if (!empty($license['expires_at'])): ?>
                                    <div class="flex justify-between items-center text-xs">
                                        <span class="text-gray-400">Berlaku Sampai</span>
                                        <span class="font-mono text-yellow-500"><?= date('d M Y', strtotime($license['expires_at'])) ?></span>
                                    </div>
                                <?php else: ?>
                                    <div class="flex justify-between items-center text-xs">
                                        <span class="text-gray-400">Masa Berlaku</span>
                                        <span class="font-mono text-green-500">Selamanya (Lifetime)</span>
                                    </div>
                                <?php endif; ?>
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-gray-400">Status</span>
                                    <span class="font-bold <?= ($license['status'] ?? 'active') === 'active' ? 'text-green-500' : 'text-red-500' ?>"><?= strtoupper($license['status'] ?? 'ACTIVE') ?></span>
                                </div>
                            </div>

                            <i class="fas fa-info-circle mr-1"></i>
                            Masukkan License Key di atas pada parameter input EA saat pemasangan di MT5 untuk aktivasi.
                        </div>
                </div>

                <!-- EXPLICIT DEBUG -->
                <div style="display:none; color:red; border:1px solid red; padding:5px; margin-top:10px;">
                    DEBUG START<br>
                    Tool Set: <?= isset($tool) ? 'Yes' : 'No' ?><br>
                    Tool Data: <?= json_encode($tool ?? []) ?><br>
                    Is License Product: <?= $tool['is_license_product'] ?? 'NULL' ?><br>
                    Purchase: <?= isset($purchase) ? 'Yes' : 'No' ?><br>
                    License Set: <?= isset($license) ? 'Yes' : 'No' ?><br>
                    DEBUG END
                </div>
            <?php elseif (isset($tool) && isset($tool['is_license_product']) && $tool['is_license_product'] == 1): ?>
                <!-- DEBUG INFO: Form Lisensi Aktif. Tool ID: <?= $tool['id'] ?? 'null' ?>, Flag: <?= $tool['is_license_product'] ?? 'null' ?> -->
                <!-- Form Aktivasi Lisensi -->
                <div class="mt-6 pt-6 border-t border-white/10">
                    <h4 class="text-sm font-bold mb-3 flex items-center gap-2">
                        <i class="fas fa-key text-accent"></i> Aktivasi Lisensi
                    </h4>
                    <div class="bg-black/40 border border-white/10 rounded-xl p-4">
                        <p class="text-xs text-gray-400 mb-4">
                            Silakan masukkan nomor akun trading MT5/MT4 Anda untuk mengaktifkan lisensi.
                            <br>
                            <span class="text-yellow-500 flex items-center gap-1 mt-1">
                                <i class="fas fa-exclamation-triangle"></i>
                                PENTING: Akun trading yang (Lock) tidak bisa diubah setelah aktivasi.
                            </span>
                        </p>

                        <form action="<?= base_url('cwpa/dashboard/layanan-detail/' . $kelas['id'] . '/activate-license') ?>" method="post" class="space-y-3">
                            <?= csrf_field() ?>
                            <input type="hidden" name="transaksi_id" value="<?= esc($transaksiId) ?>">

                            <div>
                                <label class="block text-xs text-gray-400 mb-1">Nama Broker</label>
                                <input type="text" name="broker_name" required placeholder="Contoh: Panen Kapital Berjanka, RRFX, Java FX, Gatra Mega  Berjangka , Monex , dll"
                                    class="w-full bg-black/50 border border-white/20 rounded-lg px-3 py-2 text-sm text-white focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
                            </div>

                            <div>
                                <label class="block text-xs text-gray-400 mb-1">Nomor Akun Trading (MT5/MT4)</label>
                                <input type="text" name="account_number" required placeholder="Contoh: 12345678"
                                    class="w-full bg-black/50 border border-white/20 rounded-lg px-3 py-2 text-sm text-white focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent">
                            </div>

                            <button type="submit" class="w-full py-2 bg-gradient-to-r from-accent to-yellow-500 text-black font-bold rounded-lg hover:from-yellow-500 hover:to-accent transition text-sm shadow-lg shadow-accent/20 mt-2">
                                <i class="fas fa-check-circle mr-1"></i> Aktivasi Lisensi Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($kelas['features']) && is_array($kelas['features'])): ?>
            <div class="mt-6 pt-6 border-t border-white/10">
                <p class="text-sm font-bold mb-3">Fitur Utama:</p>
                <ul class="space-y-2">
                    <?php foreach ($kelas['features'] as $feature): ?>
                        <li class="flex items-start gap-2 text-xs text-gray-400">
                            <i class="fas fa-check text-accent mt-0.5"></i>
                            <span><?= esc($feature) ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="mt-6 p-4 bg-blue-500/10 border border-blue-500/30 rounded-xl">
            <p class="text-blue-400 text-sm flex items-start gap-2">
                <i class="fas fa-info-circle mt-0.5"></i>
                <span>Pastikan Anda membaca dokumentasi sebelum menjalankan <?= $termLayanan ?>.</span>
            </p>
        </div>
    <?php elseif ($isLive): ?>
        <!-- Live Class - Zoom -->
        <h3 class="font-bold mb-4 flex items-center gap-2">
            <i class="fas fa-video text-blue-500"></i> Join Live Session
        </h3>

        <div class="bg-blue-500/10 border border-blue-500/30 rounded-xl p-6 text-center mb-6">
            <div class="w-20 h-20 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-video text-blue-500 text-3xl"></i>
            </div>
            <p class="text-gray-400 mb-4">Klik tombol di bawah untuk bergabung via Zoom</p>
            <a href="<?= esc($kelas['zoom_link'] ?? '#') ?>" target="_blank" class="inline-block w-full py-4 bg-blue-500 text-white font-bold rounded-xl hover:bg-blue-600 transition text-lg">
                <i class="fas fa-external-link-alt mr-2"></i> Join Zoom Meeting
            </a>
        </div>

        <div class="space-y-3">
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-400">Meeting ID</span>
                <span class="font-mono"><?= esc($kelas['zoom_meeting_id'] ?? '123 456 789') ?></span>
            </div>
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-400">Password</span>
                <span class="font-mono"><?= esc($kelas['zoom_password'] ?? 'almai123') ?></span>
            </div>
        </div>

        <div class="mt-6 p-4 bg-yellow-500/10 border border-yellow-500/30 rounded-xl">
            <p class="text-yellow-500 text-sm flex items-start gap-2">
                <i class="fas fa-info-circle mt-0.5"></i>
                <span>Pastikan Anda sudah menginstall aplikasi Zoom dan bergabung 10 menit sebelum sesi dimulai.</span>
            </p>
        </div>
    <?php else: ?>
        <!-- Video Course - Mulai Belajar -->
        <h3 class="font-bold mb-4 flex items-center gap-2">
            <i class="fas fa-play-circle text-purple-500"></i> Akses Konten
        </h3>

        <div class="bg-purple-500/10 border border-purple-500/30 rounded-xl p-6 text-center mb-6">
            <div class="w-20 h-20 bg-purple-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-play text-purple-500 text-3xl"></i>
            </div>
            <p class="text-gray-400 mb-4">Akses semua konten pembelajaran kapan saja</p>
            <a href="<?= base_url('cwpa/dashboard/belajar/' . $kelas['id']) ?>" class="inline-block w-full py-4 bg-purple-500 text-white font-bold rounded-xl hover:bg-purple-600 transition text-lg">
                <i class="fas fa-play mr-2"></i> Mulai Akses
            </a>
        </div>

        </div>

        <!-- Schedule & Access Section -->
        <div class="grid md:grid-cols-2 gap-6 mt-8">
            <!-- Schedule -->
            <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
                <h3 class="font-bold mb-4 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-accent"></i> Jadwal & Akses
                </h3>
                <div class="space-y-4">
                    <?php if ($isLive): ?>
                        <div class="flex items-center justify-between p-4 bg-black/50 rounded-xl">
                            <div>
                                <p class="text-sm text-gray-400">Jadwal Rutin</p>
                                <p class="font-bold"><?= esc($kelas['schedule'] ?? 'TBA') ?></p>
                            </div>
                            <i class="fas fa-repeat text-accent"></i>
                        </div>
                        <?php
                        $nextSaturday = new DateTime('next saturday');
                        $nextSessionDate = $nextSaturday->format('d M Y');
                        ?>
                        <div class="flex items-center justify-between p-4 bg-accent/10 border border-accent/30 rounded-xl">
                            <div>
                                <p class="text-sm text-accent">Sesi Berikutnya</p>
                                <p class="font-bold text-lg" id="nextSession"><?= esc($kelas['next_session'] ?? $nextSessionDate) ?></p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-400">Dalam</p>
                                <p class="font-bold text-accent" id="countdown">-</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- Video Course - Akses Selamanya -->
                        <div class="flex items-center justify-between p-4 bg-black/50 rounded-xl">
                            <div>
                                <p class="text-sm text-gray-400">Akses Layanan</p>
                                <p class="font-bold">Selamanya</p>
                            </div>
                            <i class="fas fa-infinity text-accent"></i>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-accent/10 border border-accent/30 rounded-xl">
                            <div>
                                <p class="text-sm text-accent">Total Modul</p>
                                <p class="font-bold text-lg"><?= esc($kelas['modules'] ?? '0') ?> Modul</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-400">Durasi</p>
                                <p class="font-bold text-accent"><?= esc($kelas['duration'] ?? '-') ?></p>
                            </div>
                        </div>
                        <div class="p-4 bg-black/30 rounded-xl">
                            <p class="text-sm text-gray-400 mb-2">Mode Layanan</p>
                            <p class="font-medium"><?= esc($kelas['mode'] ?? 'Online') ?> - <?= esc($kelas['location'] ?? 'Zoom') ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    </div>
</div>


<!-- ROADMAP CONTENT: SELESAI -->
<div id="content-selesai" class="roadmap-section hidden">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-8">
        <h3 class="font-bold mb-4 flex items-center gap-2">
            <i class="fas fa-check-circle text-[#33E818]"></i> Selesai
        </h3>
        
        <?php if ($hasLicense): ?>
        <div class="mb-6 border-b border-white/10 pb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <p class="text-gray-300">Apakah ingin perpanjang Lisensi EA 1 Bulan (demo akun)?</p>
                <p class="text-xs text-gray-500 mt-1 italic">*Masa aktif akan otomatis bertambah 30 hari saat diklik</p>
            </div>
            <form id="renew_license_form" action="<?= base_url('cwpa/dashboard/layanan-detail/' . $kelas['id'] . '/renew-license') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="transaksi_id" value="<?= esc($transaksiId ?? '') ?>">
                <button type="submit" class="px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition shadow-lg shadow-accent/20 flex items-center gap-2">
                    <i class="fas fa-history"></i>
                    <span>Perpanjang Lisensi EA (30 Hari)</span>
                </button>
            </form>
        </div>

        <div class="bg-[#33E818]/10 border border-[#33E818]/30 rounded-xl p-6 mb-8">
            <h4 class="font-bold text-lg mb-2 text-[#33E818]">Aktivasi Real Akun</h4>
            <p class="text-gray-300 text-sm mb-4">
                - Dapat melakukan aktivasi Aiwe Expert Advisor untuk Real Akun dengan masa aktif 12 bulan
            </p>
            <div class="mb-4">
                <span class="font-bold text-white text-sm">Kriteria:</span>
                <ul class="list-disc list-inside text-sm text-gray-400 mt-2 space-y-1">
                    <li>Demo akun berhasil simulasi penggunaan dan profit</li>
                </ul>
            </div>
        <?php else: ?>
        <div class="bg-black/20 border border-white/5 rounded-xl p-6 mb-8 text-center">
            <h4 class="font-bold text-lg mb-2 text-accent">Langkah Terakhir</h4>
            <p class="text-gray-400 text-sm">
                Silakan isi testimoni di bawah untuk menyelesaikan layanan ini dan mengklaim sertifikat Anda.
            </p>
        </div>
        <?php endif; ?>
            
            <form action="<?= base_url('cwpa/dashboard/layanan-detail/' . $kelas['id'] . '/complete') ?>" method="post" class="space-y-6">
                <?= csrf_field() ?>
                
                <?php if ($hasLicense): ?>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Broker Server</label>
                        <input type="text" name="real_broker" value="<?= esc($license['broker_name'] ?? '') ?>" required class="w-full bg-black/50 border border-white/20 rounded-lg px-4 py-3 text-white focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent transition" placeholder="Contoh: MIFX-Live">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Nomor Akun Trading</label>
                        <input type="text" name="real_account" value="<?= esc($license['account_trading_number'] ?? '') ?>" required class="w-full bg-black/50 border border-white/20 rounded-lg px-4 py-3 text-white focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent transition" placeholder="Contoh: 12345678">
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Modal Awal ($)</label>
                        <input type="number" step="0.01" id="modal_awal" name="modal_awal" required class="w-full bg-black/50 border border-white/20 rounded-lg px-4 py-3 text-white focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Modal Saat Ini ($)</label>
                        <input type="number" step="0.01" id="modal_sekarang" name="modal_sekarang" required class="w-full bg-black/50 border border-white/20 rounded-lg px-4 py-3 text-white focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent transition">
                    </div>
                </div>
                <?php endif; ?>

                <div class="bg-black/30 border border-white/5 rounded-xl p-4 mt-6">
                    <h5 class="font-bold text-white mb-4">Form Testimoni</h5>
                    <div class="space-y-4">
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Jenis Layanan</label>
                                <input type="text" value="<?= esc($kelas['name'] ?? $kelas['title'] ?? '') ?>" readonly class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-3 text-gray-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Nama WPA</label>
                                <input type="text" value="<?= esc($wpa['name'] ?? 'ALMAI Team') ?>" readonly class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-3 text-gray-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Beri Rating Layanan</label>
                            <div class="flex items-center gap-2 mb-4" id="star_rating_container">
                                <?php for($i=1; $i<=5; $i++): ?>
                                    <button type="button" onclick="setRating(<?= $i ?>)" class="text-2xl text-gray-600 hover:text-yellow-500 transition-colors rating-star" data-value="<?= $i ?>">
                                        <i class="fas fa-star"></i>
                                    </button>
                                <?php endfor; ?>
                                <input type="hidden" name="rating" id="input_rating" value="0">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Sebutkan kesan dan pesan selama pelatihan</label>
                            <textarea name="testimoni" id="testimoni" required rows="3" class="w-full bg-black/50 border border-white/20 rounded-lg px-4 py-3 text-white focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent transition" placeholder="Tuliskan pengalaman Anda..."></textarea>
                        </div>
                    </div>
                </div>
                
                <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-4 flex items-start gap-4">
                    <input type="checkbox" required id="pernyataan" class="mt-1 w-4 h-4 rounded bg-black border-white/20 text-accent focus:ring-accent">
                    <label for="pernyataan" class="text-sm text-gray-300">
                        <?php if ($hasLicense): ?>
                            Klik pernyataan bahwa saya telah berhasil dan berkompeten dalam pengunaan AIWE Expert Advisor dengan keberhasilan sebesar <span id="persentase_keberhasilan" class="font-bold text-accent">0</span> %
                        <?php else: ?>
                            Saya menyatakan bahwa saya telah menyelesaikan semua materi pelatihan dengan baik dan benar.
                        <?php endif; ?>
                    </label>
                </div>
                
                <?php if ($hasLicense): ?>
                <div id="error_keberhasilan" class="hidden text-red-500 text-sm italic font-medium p-2 bg-red-500/10 border border-red-500/20 rounded-lg">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Tingkat keberhasilan minus, Anda tidak bisa membuat aktivasi real. Silahkan menggunakan lagi demo akun perpanjang lisensi EA.
                </div>
                <?php endif; ?>

                <button type="submit" id="btn_aktivasi_real" disabled class="w-full py-4 bg-gray-600 text-gray-400 font-bold rounded-xl transition shadow-lg flex items-center justify-center gap-2 group cursor-not-allowed">
                    <i class="fas fa-arrow-right"></i>
                    <span>Lanjut ke Sertifikat</span>
                </button>
            </form>
            
            <?php if ($hasLicense): ?>
            </div>
            <?php endif; ?>
            
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const modalAwal = document.getElementById('modal_awal');
                    const modalSekarang = document.getElementById('modal_sekarang');
                    const persentaseSpan = document.getElementById('persentase_keberhasilan');
                    const errorDiv = document.getElementById('error_keberhasilan');
                    const btnSubmit = document.getElementById('btn_aktivasi_real');
                    const checkbox = document.getElementById('pernyataan');
                    const inputRating = document.getElementById('input_rating');
                    const inputTestimoni = document.getElementById('testimoni');
                    
                    window.setRating = function(value) {
                        inputRating.value = value;
                        const stars = document.querySelectorAll('.rating-star');
                        stars.forEach((star, i) => {
                            if (i < value) {
                                star.classList.remove('text-gray-600');
                                star.classList.add('text-yellow-500');
                            } else {
                                star.classList.remove('text-yellow-500');
                                star.classList.add('text-gray-600');
                            }
                        });
                        updateButtonState();
                    }

                    function calculateSuccess() {
                        if (!modalAwal || !modalSekarang) return;
                        const awal = parseFloat(String(modalAwal?.value || '').replace(',', '.')) || 0;
                        const sekarang = parseFloat(String(modalSekarang?.value || '').replace(',', '.')) || 0;
                        
                        let percent = 0;
                        if(awal > 0) {
                            percent = ((sekarang - awal) / awal) * 100;
                            let displayPercent = Math.min(100, Math.max(-100, percent));
                            if (persentaseSpan) persentaseSpan.textContent = Math.round(displayPercent);
                            
                            if (percent < 0) {
                                if (errorDiv) errorDiv.classList.remove('hidden');
                            } else {
                                if (errorDiv) errorDiv.classList.add('hidden');
                            }
                        } else {
                            if (persentaseSpan) persentaseSpan.textContent = '0';
                            if (errorDiv) errorDiv.classList.add('hidden');
                        }
                        updateButtonState();
                    }
                    
                    function updateButtonState() {
                        const hasLicense = <?= $hasLicense ? 'true' : 'false' ?>;
                        const rating = parseInt(inputRating.value) || 0;
                        const testimoni = inputTestimoni.value.trim();
                        
                        let canSubmit = false;

                        let percent = 0;
                        if (hasLicense) {
                            const awal = parseFloat(String(modalAwal?.value || '').replace(',', '.')) || 0;
                            const sekarang = parseFloat(String(modalSekarang?.value || '').replace(',', '.')) || 0;
                            if(awal > 0) {
                                percent = ((sekarang - awal) / awal) * 100;
                            }
                            canSubmit = checkbox.checked && percent >= 0 && rating > 0 && testimoni.length > 0;
                        } else {
                            canSubmit = checkbox.checked && rating > 0 && testimoni.length > 0;
                        }

                        if (canSubmit) {
                            btnSubmit.disabled = false;
                            btnSubmit.className = "w-full py-4 bg-gradient-to-r from-accent to-green-600 text-black font-bold rounded-xl hover:from-green-500 hover:to-accent transition shadow-lg shadow-accent/30 flex items-center justify-center gap-2 group";
                        } else {
                            btnSubmit.disabled = true;
                            btnSubmit.className = "w-full py-4 bg-gray-600 text-gray-400 font-bold rounded-xl transition shadow-lg flex items-center justify-center gap-2 group cursor-not-allowed";
                        }
                    }

                    function checkMinusStatus() {
                        if (checkbox.checked) {
                            const awal = parseFloat(String(modalAwal?.value || '').replace(',', '.')) || 0;
                            const sekarang = parseFloat(String(modalSekarang?.value || '').replace(',', '.')) || 0;
                            let percent = 0;
                            if(awal > 0) {
                                percent = ((sekarang - awal) / awal) * 100;
                            }

                            if (percent < 0) {
                                checkbox.checked = false;
                                updateButtonState();
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Tingkat Keberhasilan Minus',
                                    text: 'Tingkat keberhasilan minus, Anda tidak bisa membuat aktivasi real. Silahkan menggunakan lagi demo akun perpanjang lisensi EA.',
                                    background: '#111',
                                    color: '#fff',
                                    showCancelButton: true,
                                    confirmButtonText: '<i class="fas fa-history mr-2"></i> Perpanjang Lisensi',
                                    cancelButtonText: 'Tutup',
                                    confirmButtonColor: '#33E818',
                                    confirmButtonTextColor: '#000',
                                    cancelButtonColor: '#4b5563',
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        document.getElementById('renew_license_form').submit();
                                    }
                                });
                            }
                        }
                    }
                    
                    if (modalAwal) modalAwal.addEventListener('input', calculateSuccess);
                    if (modalSekarang) modalSekarang.addEventListener('input', calculateSuccess);
                    checkbox.addEventListener('change', () => {
                        checkMinusStatus();
                        updateButtonState();
                    });
                    inputTestimoni.addEventListener('input', updateButtonState);
                });
            </script>
        </div>
    </div>
</div>

<!-- ROADMAP CONTENT: SERTIFIKAT -->
<div id="content-sertifikat" class="roadmap-section hidden">
<!-- Completion & Certificate Section -->
<div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-8">
    <h3 class="font-bold mb-4 flex items-center gap-2">
        <i class="fas fa-award text-yellow-500"></i> Status Penyelesaian
    </h3>

    <?php if (!empty($isCompleted) && !empty($certificate)): ?>
        <!-- Already Completed - Show Certificate -->
        <div class="bg-gradient-to-r from-yellow-500/20 to-orange-500/20 border border-yellow-500/30 rounded-xl p-6 text-center">
            <div class="w-20 h-20 bg-yellow-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-certificate text-yellow-500 text-3xl"></i>
            </div>
            <h4 class="text-xl font-bold text-yellow-500 mb-2">Selamat! Layanan Selesai</h4>
            <p class="text-gray-400 mb-4">Anda telah menyelesaikan layanan ini dan mendapatkan sertifikat.</p>

            <div class="bg-black/30 rounded-lg p-4 mb-4">
                <p class="text-sm text-gray-400 mb-1">Nomor Sertifikat</p>
                <p class="font-mono font-bold text-lg"><?= esc($certificate['certificate_number']) ?></p>
                <p class="text-xs text-gray-500 mt-1">Diterbitkan: <?= date('d M Y', strtotime($certificate['issued_at'])) ?></p>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="<?= base_url('cwpa/dashboard/sertifikat') ?>" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-yellow-500 text-black font-bold rounded-xl hover:bg-yellow-400 transition">
                    <i class="fas fa-eye"></i> Lihat Sertifikat
                </a>
                <a href="<?= base_url('certificate/' . $certificate['certificate_number']) ?>" target="_blank" class="inline-flex items-center justify-center gap-2 px-6 py-3 border border-yellow-500/50 text-yellow-500 font-bold rounded-xl hover:bg-yellow-500/10 transition">
                    <i class="fas fa-download"></i> Download PDF
                </a>
            </div>
        </div>
    <?php else: ?>
        <!-- Not Yet Completed - Direct message -->
        <div class="bg-black/30 rounded-xl p-8 text-center border border-white/5">
            <div class="w-16 h-16 bg-red-500/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-lock text-red-500/50 text-2xl"></i>
            </div>
            <h4 class="text-white font-bold mb-2">Sertifikat Belum Terbit</h4>
            <p class="text-gray-400">
                Anda belum menyelesaikan semuanya. Silahkan selesaikan tahap <b><?= $hasLicense ? 'Aktivasi Real Akun' : 'Selesai' ?></b> pada tab sebelumnya untuk mendapatkan sertifikat secara otomatis.
            </p>
        </div>
    <?php endif; ?>
</div>


</div>
<script>
    function switchRoadmap(tabId, index = 0, totalSteps = 5) {
        // Toggle view
        document.querySelectorAll('.roadmap-section').forEach(el => {
            el.classList.remove('block');
            el.classList.add('hidden');
        });
        const target = document.getElementById('content-' + tabId);
        if(target) {
            target.classList.remove('hidden');
            target.classList.add('block');
        }

        // Update connector lines
        const allSteps = document.querySelectorAll('.roadmap-step-item');
        allSteps.forEach((el, i) => {
            if (i < index) {
                el.classList.add('completed');
            } else {
                el.classList.remove('completed');
            }
        });

        // Update styles
        allSteps.forEach((el, i) => {
            const stepId = el.getAttribute('data-id');
            const tabEl = document.getElementById('tab-' + stepId);
            const iconEl = document.getElementById('icon-' + stepId);
            const badgeEl = document.getElementById('badge-' + stepId);
            const labelEl = document.getElementById('label-' + stepId);

            const isCompleted = i < index;
            const isActive = i === index;

            if (tabEl) {
                if (isCompleted || isActive) {
                    tabEl.className = "w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 lg:w-14 lg:h-14 rounded-full bg-[#111] border-2 sm:border-[2.5px] md:border-[3px] border-accent flex items-center justify-center transition-all duration-300 relative z-10 mx-auto shadow-[0_0_15px_rgba(51,232,24,0.3)]";
                } else {
                    tabEl.className = "w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 lg:w-14 lg:h-14 rounded-full bg-[#111] border-2 sm:border-[2.5px] md:border-[3px] border-white/10 flex items-center justify-center transition-all duration-300 relative z-10 mx-auto";
                }
            }

            // Handle icon - show running GIF for active step
            if (iconEl) {
                if (isActive) {
                    // Replace with running GIF
                    if (iconEl.tagName !== 'IMG') {
                        const parent = iconEl.parentElement;
                        const img = document.createElement('img');
                        img.src = 'https://almai.id/images/lari.gif';
                        img.alt = 'On Progress';
                        img.className = 'running-gif';
                        img.id = 'icon-' + stepId;
                        parent.replaceChild(img, iconEl);
                    }
                } else {
                    // Show icon
                    if (iconEl.tagName === 'IMG') {
                        const parent = iconEl.parentElement;
                        const i = document.createElement('i');
                        const stepData = Array.from(allSteps).find(s => s.getAttribute('data-id') === stepId);
                        const iconClass = stepData ? stepData.querySelector('i')?.className.split(' ').find(c => c.startsWith('fa-')) : 'fa-circle';
                        i.className = `fas ${iconClass} ${isCompleted ? 'text-accent' : 'text-gray-600'} text-xs sm:text-sm md:text-base lg:text-xl group-hover:text-accent transition-colors`;
                        i.id = 'icon-' + stepId;
                        parent.replaceChild(i, iconEl);
                    } else {
                        iconEl.className = `fas ${iconEl.className.split(' ').find(c => c.startsWith('fa-'))} ${isCompleted ? 'text-accent' : 'text-gray-600'} text-xs sm:text-sm md:text-base lg:text-xl group-hover:text-accent transition-colors`;
                    }
                }
            }

            // Handle badge
            if (badgeEl) {
                if (isCompleted) {
                    badgeEl.classList.remove('opacity-0');
                    badgeEl.classList.add('opacity-100');
                } else {
                    badgeEl.classList.remove('opacity-100');
                    badgeEl.classList.add('opacity-0');
                }
            }

            // Handle label
            if (labelEl) {
                if (isCompleted || isActive) {
                    labelEl.className = "mt-2 sm:mt-3 md:mt-4 text-center font-bold text-white text-[9px] sm:text-[10px] md:text-xs lg:text-sm leading-tight transition-colors duration-300 px-1";
                } else {
                    labelEl.className = "mt-2 sm:mt-3 md:mt-4 text-center font-medium text-gray-500 text-[9px] sm:text-[10px] md:text-xs lg:text-sm leading-tight transition-colors duration-300 px-1";
                }
            }
        });
    }

    // Initialize the first tab on load visually
    document.addEventListener('DOMContentLoaded', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const tabId = urlParams.get('tab') || 'informasi';
        
        let targetIndex = 0;
        const allSteps = document.querySelectorAll('.roadmap-step-item');
        
        allSteps.forEach((el, i) => {
            if (el.getAttribute('data-id') === tabId) {
                targetIndex = i;
            }
        });

        switchRoadmap(tabId, targetIndex, allSteps.length);
    });
</script>
<?php if ($isLive): ?>
    <script>
        const nextSessionElement = document.getElementById('nextSession');
        if (nextSessionElement) {
            const nextSessionText = nextSessionElement.textContent;
            const nextDate = new Date(nextSessionText);
            const today = new Date();
            const diffTime = nextDate - today;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            const countdownElement = document.getElementById('countdown');
            if (countdownElement) {
                if (diffDays > 0) {
                    countdownElement.textContent = diffDays + ' Hari';
                } else if (diffDays === 0) {
                    countdownElement.textContent = 'Hari Ini!';
                    countdownElement.classList.add('animate-pulse');
                } else {
                    countdownElement.textContent = 'Sudah Lewat';
                }
            }
        }
    </script>
<?php endif; ?>

<!-- Complete Service Confirmation Modal -->
<div id="completeModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 sm:p-8 max-w-md w-full text-center transform scale-95 opacity-0 transition-all duration-300" id="completeModalContent">
        <!-- Success Icon with Animation -->
        <div class="relative mb-4 sm:mb-6">
            <div class="w-16 h-16 sm:w-20 sm:h-20 mx-auto rounded-full bg-gradient-to-br from-accent/20 to-green-600/20 border-2 border-accent/30 flex items-center justify-center">
                <i class="fas fa-certificate text-accent text-2xl sm:text-3xl"></i>
            </div>
            <div class="absolute -top-2 -right-2 w-6 h-6 sm:w-8 sm:h-8 bg-yellow-500 rounded-full flex items-center justify-center animate-bounce">
                <i class="fas fa-star text-black text-xs sm:text-sm"></i>
            </div>
        </div>

        <!-- Title -->
        <h3 class="text-xl sm:text-2xl font-bold mb-2 sm:mb-3 bg-gradient-to-r from-accent to-green-400 bg-clip-text text-transparent">
            Selesaikan Layanan?
        </h3>

        <!-- Description -->
        <div class="mb-4 sm:mb-6">
            <p class="text-gray-300 mb-3 sm:mb-4 leading-relaxed text-sm sm:text-base">
                Apakah Anda yakin sudah menyelesaikan layanan ini?
            </p>
            <div class="bg-accent/10 border border-accent/30 rounded-xl p-3 sm:p-4">
                <div class="flex items-start gap-2 sm:gap-3">
                    <i class="fas fa-award text-accent text-base sm:text-lg mt-0.5"></i>
                    <div class="text-left">
                        <p class="text-accent font-medium text-xs sm:text-sm mb-1">Setelah dikonfirmasi:</p>
                        <ul class="text-[10px] sm:text-xs text-gray-400 space-y-1">
                            <li>• Sertifikat akan diterbitkan otomatis</li>
                            <li>• Status layanan berubah menjadi "Selesai"</li>
                            <li>• Anda dapat mengunduh sertifikat</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Warning -->
        <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-xl p-2.5 sm:p-3 mb-4 sm:mb-6">
            <div class="flex items-center gap-2 text-yellow-400">
                <i class="fas fa-exclamation-triangle text-xs sm:text-sm"></i>
                <p class="text-[10px] sm:text-xs">Pastikan Anda sudah mengikuti semua sesi/materi</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
            <button onclick="closeCompleteModal()" class="flex-1 py-2.5 sm:py-3 px-4 border border-white/20 rounded-xl hover:bg-white/5 transition text-gray-300 text-sm sm:text-base">
                <i class="fas fa-times mr-2"></i> Batal
            </button>
            <form id="completeForm" action="<?= base_url('cwpa/dashboard/layanan-detail/' . $kelas['id'] . '/complete') ?>" method="post" class="flex-1">
                <?= csrf_field() ?>
                <button type="submit" class="w-full py-2.5 sm:py-3 px-4 bg-gradient-to-r from-accent to-green-600 text-black font-bold rounded-xl hover:from-green-500 hover:to-accent transition shadow-lg shadow-accent/20 text-sm sm:text-base">
                    <i class="fas fa-check-circle mr-2"></i> Ya, Selesaikan
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function openCompleteModal() {
        const modal = document.getElementById('completeModal');
        const content = document.getElementById('completeModalContent');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeCompleteModal() {
        const modal = document.getElementById('completeModal');
        const content = document.getElementById('completeModalContent');

        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    // Close modal when clicking outside
    document.getElementById('completeModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeCompleteModal();
        }
    });

    function copyToClipboard(text, btn) {
        navigator.clipboard.writeText(text).then(() => {
            if (btn) {
                const icon = btn.querySelector('i');
                if (icon) {
                    const originalClass = icon.className;
                    icon.className = 'fas fa-check text-accent';
                    setTimeout(() => {
                        icon.className = originalClass;
                    }, 2000);
                }
            }
            showToast('Berhasil disalin ke clipboard');
        }).catch(err => {
            console.error('Gagal menyalin: ', err);
            // Fallback
            const input = document.createElement('textarea');
            input.value = text;
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
            showToast('Berhasil disalin ke clipboard');
        });
    }
</script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?= $this->endSection() ?>
