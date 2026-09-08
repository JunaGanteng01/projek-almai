<?= $this->extend('user/partials/layout') ?>

<?= $this->section('styles') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    /* Force SweetAlert2 Button Colors */
    .swal2-confirm { 
        background-color: #33e818 !important;
        color: #000 !important; 
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>



<!-- Back Button + Tab Navigation -->
<div class="mb-6 flex items-center justify-between flex-wrap gap-3">
    <a href="<?= base_url('user/dashboard') ?>" class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition">
        <i class="fas fa-arrow-left"></i>
        <span class="text-sm">Kembali ke Dashboard</span>
    </a>
    <!-- Tab ADVOKASI | WPA -->
    <div class="flex bg-[#111] border border-white/10 rounded-xl p-1 gap-1">
        <a href="<?= base_url('user/advokasi') ?>" 
           class="px-5 py-2 rounded-lg text-sm font-bold transition <?= (strpos(current_url(), 'daftar-wpa') === false) ? 'bg-accent text-black' : 'text-gray-400 hover:text-white' ?>">
            <i class="fas fa-shield-halved mr-1.5"></i>Advokasi
        </a>
        <a href="<?= base_url('user/dashboard/daftar-wpa') ?>" 
           class="px-5 py-2 rounded-lg text-sm font-bold transition <?= (strpos(current_url(), 'daftar-wpa') !== false) ? 'bg-accent text-black' : 'text-gray-400 hover:text-white' ?>">
            <i class="fas fa-id-badge mr-1.5"></i>DAFTAR WPA
        </a>
    </div>
</div>

    <!-- Header Section -->
    <div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden mb-8">
        <div class="relative">
            <?php 
            $headerThumb = !empty($settings['thumbnail']) ? base_url('file/' . $settings['thumbnail']) : 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&h=450&fit=crop';
            ?>
            <img src="<?= $headerThumb ?>" alt="Advokasi" class="w-full h-48 md:h-64 object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
            <div class="absolute bottom-4 left-4 right-4">
                <span class="px-3 py-1 bg-accent text-black text-xs font-bold rounded-full mb-2 inline-block uppercase tracking-widest">
                    <i class="fas fa-shield-halved mr-1"></i> PROGRAM ADVOKASI
                </span>
                <h1 class="text-2xl md:text-3xl font-bold uppercase tracking-tighter"><?= esc($settings['description'] ?? 'PROGRAM ADVOKASI TRADER BASIC (7 HARI)') ?></h1>
            </div>
        </div>
    </div>

    <?php
    $currentStep = $currentStep ?? 0;
    $roadmapSteps = [
        ['id' => 'informasi', 'label' => 'Informasi', 'icon' => 'fa-info-circle'],
        ['id' => 'pelatihan', 'label' => 'Pelatihan', 'icon' => 'fa-graduation-cap'],
        ['id' => 'aktivasi', 'label' => 'Aktivasi EA', 'icon' => 'fa-key'],
        ['id' => 'selesai', 'label' => 'Selesai', 'icon' => 'fa-check-circle'],
        ['id' => 'sertifikat', 'label' => 'Sertifikat', 'icon' => 'fa-certificate'],
    ];
    ?>

    <!-- 5 Tahap Pembelajaran Card -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-8 w-full">
        <h3 class="font-bold mb-6 flex items-center gap-2 text-white">
            <i class="fas fa-layer-group text-accent"></i> 5 Tahap Pembelajaran
        </h3>

        <div class="relative w-full overflow-x-auto pb-4 md:pb-2 no-scrollbar">
            <div class="roadmap-container relative">
                <?php foreach ($roadmapSteps as $index => $step): 
                    $isStepCompleted = $index < $currentStep;
                    $isActive = $index == $currentStep;
                    $statusClass = $isStepCompleted ? 'completed' : '';
                ?>
                <div class="relative flex flex-col items-center flex-1 cursor-pointer group roadmap-step-item <?= $statusClass ?>" 
                     onclick="handleRoadmapClick('<?= $step['id'] ?>', <?= $index ?>, <?= count($roadmapSteps) ?>)" 
                     data-id="<?= $step['id'] ?>">
                    
                    <div id="tab-<?= $step['id'] ?>" class="w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 lg:w-14 lg:h-14 rounded-full bg-[#111] border-2 sm:border-[2.5px] md:border-[3px] <?= $isStepCompleted || $isActive ? 'border-accent shadow-[0_0_15px_rgba(51,232,24,0.3)]' : 'border-white/10' ?> flex items-center justify-center transition-all duration-300 relative z-10 mx-auto">
                        <?php if ($isActive): ?>
                            <img src="https://almai.id/images/lari.gif" alt="On Progress" class="running-gif" id="icon-<?= $step['id'] ?>">
                        <?php else: ?>
                            <i class="fas <?= $step['icon'] ?> <?= $isStepCompleted || $isActive ? 'text-accent' : 'text-gray-600' ?> text-xs sm:text-sm md:text-base lg:text-xl group-hover:text-accent transition-colors" id="icon-<?= $step['id'] ?>"></i>
                        <?php endif; ?>
                        
                        <div class="absolute -top-0.5 -right-0.5 sm:-top-1 sm:-right-1 w-3 h-3 sm:w-3.5 sm:h-3.5 md:w-4 md:h-4 rounded-full bg-yellow-500 border border-black flex items-center justify-center <?= $isStepCompleted ? 'opacity-100' : 'opacity-0' ?> transition-opacity duration-300" id="badge-<?= $step['id'] ?>">
                            <i class="fas fa-star text-black text-[5px] sm:text-[6px] md:text-[8px]"></i>
                        </div>
                    </div>

                    <div id="label-<?= $step['id'] ?>" class="mt-2 sm:mt-3 md:mt-4 text-center <?= $isStepCompleted || $isActive ? 'font-bold text-white' : 'font-medium text-gray-500' ?> text-[9px] sm:text-[10px] md:text-xs lg:text-sm leading-tight transition-colors duration-300 px-1">
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
                <span class="font-medium" id="progress-text">Mulai dengan membaca informasi layanan</span>
            </div>
        </div>
    </div>
                        <!-- Beli Layanan -->
                    <button onclick="showProgramInfo()" 
                        class="inline-flex items-center gap-2 px-6 py-3 bg-white/5 border border-white/10 text-white font-bold rounded-xl hover:bg-white/10 transition-all text-xs sm:text-sm">
                        <i class="fas fa-shopping-cart text-accent"></i>
                        <span>Beli Layanan</span>
                    </button>
                                    <!-- Note -->
                <p class="text-[10px] sm:text-xs text-gray-400 mt-3 flex items-center gap-1">
                    <i class="fas fa-shield-check text-accent"></i>
                    <span>Untuk mendapatkan akses layanan dan materi lengkap, serta software Expert Advisor AIWE dan BIDBOX silakan klik tombol Beli Layanan.</span>
                </p>

<!-- ROADMAP CONTENT: INFORMASI -->
<div id="content-informasi" class="roadmap-section block">
    
    <!-- Peringatan Pendaftaran Akun -->
    <div class="bg-gradient-to-r from-blue-500/20 to-accent/20 border border-blue-500/30 rounded-2xl p-4 sm:p-6 mb-8">
        
        <div class="flex flex-col sm:flex-row items-start gap-4">
            
            <!-- Icon -->
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-500/30 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fas fa-user-plus text-blue-400 text-lg sm:text-xl"></i>
            </div>

            <!-- Content -->
            <div class="flex-1">
                
                <h3 class="font-bold text-base sm:text-lg mb-2 text-white">
                    Persiapan Sebelum Webinar
                </h3>
                
                <!-- CTA -->
                <div class="flex flex-wrap gap-3">

                        
                    <!-- Demo Account -->
                    <button onclick="showBrokerModal()" 
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-accent text-black font-bold rounded-xl hover:shadow-lg hover:scale-105 transition-all text-xs sm:text-sm">
                        <i class="fas fa-external-link-alt"></i>
                        <span>Buat Akun Demo</span>
                    </button> 
                    
                    <!-- Signal -->
                    <button onclick="showSignalPopup()" 
                        class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-xl hover:shadow-lg hover:scale-105 transition transition-all text-xs sm:text-sm">
                        <i class="fas fa-signal"></i>
                        <span>Signal</span>
                    </button>

                    <!-- Highlight Info -->
                    <div class="bg-white/5 border border-white/10 rounded-xl p-4 mb-5 w-full">
                        <p class="text-gray-300 text-xs sm:text-sm mb-4 leading-relaxed">
                            Pastikan Anda telah memiliki akun trading  
                            <span class="text-accent font-semibold">Aktif</span> dan sudah mendownload 
                            <span class="text-accent font-semibold">silabus materi </span> sebelum mengikuti webinar. 
                            Selama pelatihan, gunakan 
                            <span class="text-accent font-semibold">Akun Demo</span> sebagai media praktek.
                            Untuk keamanan, gunakan broker resmi yang terdaftar dan diawasi oleh BAPPEBTI, OJK, dan Bank Indonesia.
                        </p>
                    </div>



            </div>
        </div>
    </div>

</div>

        <!-- Software Section -->
        <?php if (!empty($settings['software'])): ?>
        <div class="bg-[#111] border border-white/10 rounded-2xl p-4 sm:p-6 mb-8">
            <h3 class="font-bold mb-3 sm:mb-4 flex items-center gap-2 text-white text-sm sm:text-base">
                <i class="fas fa-laptop-code text-accent"></i> Software
            </h3>
            <p class="text-gray-400 text-xs sm:text-sm mb-4">Software pendukung Program Advokasi</p>
            <div class="space-y-3">
                <?php foreach ($settings['software'] as $sw): ?>
                <div class="flex items-center justify-between p-4 bg-black/50 rounded-xl border border-white/5 hover:border-accent/30 transition group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-accent/10 rounded-lg flex items-center justify-center">
                            <i class="fas fa-file-code text-accent"></i>
                        </div>
                        <div>
                            <p class="font-medium text-sm text-white"><?= esc($sw['title']) ?></p>
                            <p class="text-[10px] text-gray-500 uppercase tracking-wider">Executable (.exe)</p>
                        </div>
                    </div>
                    <a href="<?= base_url('file/' . ($sw['file'] ?? '#')) ?>" target="_blank" class="px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-xs hover:bg-accent hover:text-black hover:border-accent transition font-bold" download>
                        <i class="fas fa-download mr-1"></i> Download
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Materials & Resources -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-4 sm:p-6 mb-8">
            <h3 class="font-bold mb-3 sm:mb-4 flex items-center gap-2 text-white text-sm sm:text-base">
                <i class="fas fa-folder text-accent"></i> Materi & Resources
            </h3>
            <p class="text-gray-400 text-xs sm:text-sm mb-4">Materi pendukung untuk layanan ini</p>
            <div class="space-y-3">
                <?php if (!empty($settings['materials'])): ?>
                    <?php foreach ($settings['materials'] as $material): ?>
                    <div class="flex items-center justify-between p-4 bg-black/50 rounded-xl border border-white/5 hover:border-accent/30 transition group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-accent/10 rounded-lg flex items-center justify-center">
                                <i class="fas <?= $material['icon'] ?? 'fa-file' ?> text-accent"></i>
                            </div>
                            <div>
                                <p class="font-medium text-sm text-white"><?= esc($material['title']) ?></p>
                                <p class="text-[10px] text-gray-500 uppercase tracking-wider"><?= esc($material['subtitle'] ?? 'Resource') ?></p>
                            </div>
                        </div>
                        <a href="<?= base_url('file/' . ($material['file'] ?? '#')) ?>" target="_blank" class="px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-xs hover:bg-accent hover:text-black hover:border-accent transition font-bold">
                            <i class="fas fa-download mr-1"></i> Download
                        </a>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="flex items-center justify-between p-4 bg-black/50 rounded-xl border border-white/5 hover:border-accent/30 transition group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-red-500/10 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-pdf text-red-500"></i>
                            </div>
                            <div>
                                <p class="font-medium text-sm text-white">Modul Advokasi Lengkap</p>
                                <p class="text-[10px] text-gray-500 uppercase tracking-wider">PDF • 2.4 MB</p>
                            </div>
                        </div>
                        <a href="#" class="px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-xs hover:bg-accent hover:text-black hover:border-accent transition font-bold">
                            <i class="fas fa-download mr-1"></i> Download
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ROADMAP CONTENT: AKTIVASI EA -->
    <div id="content-aktivasi" class="roadmap-section hidden">
        <div class="bg-[#111] border border-white/10 rounded-2xl p-4 sm:p-6 mb-8">
            <h3 class="font-bold mb-4 flex items-center gap-2 text-white text-sm sm:text-base">
                <i class="fas fa-key text-accent"></i> Aktivasi Lisensi EA
            </h3>
            <div class="bg-black/40 border border-white/10 rounded-xl p-4 sm:p-6">
                
                <?php if (!empty($licenses)): ?>
                    <!-- License Active View - Premium Design (Matched to Layanan Detail) -->
                    <div class="space-y-6 text-left" style="font-family: 'Public Sans', sans-serif;">
                        <!-- Header Banner -->
                        <div class="bg-gradient-to-r from-green-500/10 to-accent/20 border border-green-500/20 rounded-2xl p-4 sm:p-6 flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-4">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-green-500/20 rounded-2xl flex items-center justify-center border border-green-500/30 flex-shrink-0">
                                <i class="fas fa-check-circle text-green-500 text-2xl sm:text-3xl"></i>
                            </div>
                            <div>
                                <h4 class="text-green-500 font-black text-base sm:text-lg mb-1 sm:mb-0" style="font-family: 'Montserrat', sans-serif;">Lisensi Aktif</h4>
                                <p class="text-gray-400 text-[10px] sm:text-xs">Lisensi EA Anda telah aktif dan siap digunakan pada akun trading yang terdaftar.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <?php 
                        $activatedPlatforms = [];
                        foreach ($licenses as $license): 
                            $isMt4 = strpos($license['broker_name'], '[MT4]') !== false;
                            $isMt5 = strpos($license['broker_name'], '[MT5]') !== false;
                            if ($isMt4) $activatedPlatforms[] = 'MT4';
                            if ($isMt5) $activatedPlatforms[] = 'MT5';
                        ?>
                            <!-- Detail Lisensi Card -->
                            <div class="bg-black/40 border border-white/10 rounded-2xl p-4 sm:p-6">
                                <p class="text-[10px] sm:text-xs text-gray-400 mb-4 font-black uppercase tracking-widest" style="font-family: 'Montserrat', sans-serif;">
                                    <i class="fas fa-id-card mr-1 text-accent"></i> Lisensi <?= $isMt4 ? 'MT4' : ($isMt5 ? 'MT5' : '') ?>
                                </p>
                                
                                <div class="space-y-4">
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
                                        <span class="text-xs sm:text-sm text-gray-400">License Key</span>
                                        <div class="flex items-center gap-2 w-full sm:w-auto bg-black/30 p-2 sm:p-0 rounded-lg sm:bg-transparent">
                                            <code class="break-all w-full text-xs sm:text-sm"><?= esc($license['license_key']) ?></code>
                                            <button onclick="copyToClipboard('<?= esc($license['license_key']) ?>')" class="p-2 hover:bg-white/10 rounded-lg text-gray-400 hover:text-white transition flex-shrink-0">
                                                <i class="far fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center border-t border-white/5 pt-4 gap-1 sm:gap-0">
                                        <span class="text-xs sm:text-sm text-gray-400">Broker</span>
                                        <span class="text-white font-bold text-xs sm:text-sm"><?= esc(str_replace(['[MT4] ', '[MT5] '], '', $license['broker_name'])) ?></span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center border-t border-white/5 pt-4 gap-1 sm:gap-0">
                                        <span class="text-xs sm:text-sm text-gray-400">Akun Trading</span>
                                        <span class="text-white font-mono font-bold text-xs sm:text-sm"><?= esc($license['account_trading_number']) ?></span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center border-t border-white/5 pt-4 gap-1 sm:gap-0">
                                        <span class="text-xs sm:text-sm text-gray-400">Masa Berlaku</span>
                                        <span class="text-accent font-bold text-xs sm:text-sm">
                                            <?php 
                                            if (!empty($license['expires_at'])) {
                                                $diff = strtotime($license['expires_at']) - time();
                                                $days = ceil($diff / 86400);
                                                if ($days > 0) {
                                                    echo '<span class="text-yellow-500">' . date('d M Y', strtotime($license['expires_at'])) . ' (' . $days . ' Hari Lagi)</span>';
                                                } else {
                                                    echo '<span class="text-red-500"><i class="fas fa-exclamation-circle mr-1"></i> Telah Berakhir</span>';
                                                }
                                            } else {
                                                echo '<span class="text-green-500"><i class="fas fa-infinity mr-1"></i> Lifetime (Selamanya)</span>';
                                            }
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        </div>

                        <!-- Expert Advisor File(s) -->
                        <div class="space-y-4">
                            <?php 
                            $eaItems = !empty($settings['ea_list']) ? $settings['ea_list'] : ( !empty($settings['ea_file']) ? [['title' => 'Standard Expert Advisor', 'file' => $settings['ea_file']]] : [] );
                            foreach ($eaItems as $ea): 
                                $isMt4File = stripos($ea['title'], 'MT4') !== false;
                                $isMt5File = stripos($ea['title'], 'MT5') !== false;
                                
                                $shouldShow = false;
                                if ($isMt4File && in_array('MT4', $activatedPlatforms)) $shouldShow = true;
                                if ($isMt5File && in_array('MT5', $activatedPlatforms)) $shouldShow = true;
                                if (!$isMt4File && !$isMt5File && !empty($activatedPlatforms)) $shouldShow = true;
                                
                                if (!$shouldShow) continue;
                            ?>
                            <div class="bg-accent/10 border border-accent/20 rounded-2xl p-4 sm:p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 sm:gap-6">
                                <div class="flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-3 sm:gap-4">
                                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-accent/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-robot text-accent text-xl sm:text-2xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-white font-bold text-sm sm:text-base mb-1" style="font-family: 'Montserrat', sans-serif;"><?= esc($ea['title']) ?></h4>
                                        <p class="text-[10px] sm:text-xs text-gray-400 font-mono break-all"><?= basename($ea['file'] ?? '#') ?></p>
                                    </div>
                                </div>
                                <a href="<?= base_url('file/' . ($ea['file'] ?? '#')) ?>" class="w-full md:w-auto px-6 sm:px-8 py-3 sm:py-4 bg-accent text-black font-black uppercase tracking-widest rounded-xl hover:bg-white transition text-[10px] sm:text-xs flex items-center justify-center gap-2 shadow-lg shadow-accent/20" style="font-family: 'Montserrat', sans-serif;" download>
                                    <i class="fas fa-download"></i> Download EA
                                </a>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Cara Menggunakan -->
                        <div class="bg-blue-500/10 border border-blue-500/20 rounded-2xl p-4 sm:p-6">
                            <h5 class="text-white font-bold text-xs sm:text-sm mb-3 sm:mb-4 flex items-center gap-2" style="font-family: 'Montserrat', sans-serif;">
                                <i class="fas fa-info-circle text-blue-400"></i> Cara Menggunakan:
                            </h5>
                            <div class="text-[10px] sm:text-xs text-blue-300 leading-relaxed">
                                <ol class="space-y-2">
                                    <li>1. Download file EA di atas</li>
                                    <li>2. Install EA ke folder <strong>MQL5/Experts (MT5)</strong> atau <strong>MQL4/Experts (MT4)</strong></li>
                                    <li>3. Restart MetaTrader</li>
                                    <li>4. Pasang EA pada chart dan masukkan License Key pada parameter input</li>
                                    <li>5. Pastikan <strong>Allow WebRequest</strong> sudah diaktifkan di Tools → Options → Expert Advisors</li>
                                    <li>6. Ceklis Allow Webrequest for listed URL dan tambahkan url:</li>
                                </ol>
                                <div class="mt-4 flex flex-col sm:flex-row sm:items-center justify-between bg-black/40 p-3 rounded-xl border border-blue-500/30 gap-2 sm:gap-0">
                                    <code class="text-accent text-[10px] sm:text-[11px] font-mono select-all break-all text-center sm:text-left">https://almai.id/api/license/validate</code>
                                    <button onclick="copyToClipboard('https://almai.id/api/license/validate')" class="text-gray-400 hover:text-white transition bg-white/5 sm:bg-transparent p-2 sm:p-0 rounded-lg flex items-center justify-center gap-2">
                                        <i class="far fa-copy"></i> <span class="sm:hidden text-[10px]">Copy URL</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Penting Footer -->
                        <div class="bg-yellow-500/10 border border-yellow-500/20 rounded-2xl p-4 sm:p-6 flex items-start gap-3">
                            <i class="fas fa-key text-yellow-500 text-base sm:text-lg mt-0.5 flex-shrink-0"></i>
                            <div class="text-xs sm:text-sm text-yellow-500 leading-relaxed">
                                <strong>Penting:</strong> Simpan License Key Anda dengan aman. Masukkan License Key pada parameter input EA saat pemasangan di MetaTrader untuk aktivasi otomatis.
                            </div>
                        </div>

                        <div class="pt-2 sm:pt-4">
                            <button onclick="switchRoadmap('pelatihan', 1, 5)" class="w-full py-3 sm:py-4 bg-white/5 border border-white/10 text-white font-black uppercase tracking-widest rounded-2xl hover:bg-white/10 transition text-[10px] sm:text-xs shadow-lg" style="font-family: 'Montserrat', sans-serif;">
                                Lanjut ke Pelatihan <i class="fas fa-arrow-right ml-2"></i>
                            </button>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-3 sm:gap-4 mb-6">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 bg-accent/20 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-robot text-accent text-xl sm:text-2xl"></i>
                        </div>
                        <div>
                            <p class="font-bold text-base sm:text-lg mb-1 sm:mb-2 text-white">Aktivasi Expert Advisor (Trial)</p>
                            <p class="text-xs sm:text-sm text-gray-400">
                                Layanan ini memberikan akses ke Expert Advisor (EA). Silakan aktivasi lisensi dengan memasukkan nomor Demo Akun trading Anda.
                            </p>
                        </div>
                    </div>

                    <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-xl p-4 mb-6">
                        <div class="flex items-start gap-2 sm:gap-3 text-yellow-400">
                            <i class="fas fa-exclamation-triangle text-base sm:text-lg mt-0.5 flex-shrink-0"></i>
                            <div class="text-[10px] sm:text-sm">
                                <p class="font-bold mb-1 uppercase tracking-widest">PENTING:</p>
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Akun trading yang Anda daftarkan akan di-<strong>lock</strong> dan tidak dapat diubah</li>
                                    <li>Pastikan nomor akun sudah benar dan masih aktif sebelum aktivasi</li>
                                    <li>License Key akan digenerate otomatis setelah aktivasi</li>
                                    <li>Durasi Lisensi: <strong><?= esc($settings['ea_duration'] ?? 30) ?> Hari</strong></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <form id="activationForm" class="space-y-4">
                        <?= csrf_field() ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            <!-- MT4 Section -->
                            <div class="space-y-4 bg-white/5 p-4 rounded-xl border border-white/10">
                                <div>
                                    <label class="block text-[10px] sm:text-xs font-bold text-gray-400 uppercase mb-2">
                                        <i class="fas fa-building mr-1 text-accent"></i> Nama Broker Legal
                                    </label>
                                    <input type="text" name="broker_name_mt4" 
                                        placeholder="Kosongkan jika broker tidak terdaftar"
                                        class="w-full bg-black/50 border border-white/20 rounded-xl px-3 py-2.5 sm:px-4 sm:py-3 text-white placeholder-gray-500 focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent transition text-xs sm:text-sm">
                                    <p class="text-[9px] sm:text-[10px] text-gray-500 mt-1 sm:mt-2">Masukkan nama broker tempat akun trading Anda terdaftar</p>
                                </div>
                                <div>
                                    <label class="block text-[10px] sm:text-xs font-bold text-gray-400 uppercase mb-2">
                                        <i class="fas fa-hashtag mr-1 text-accent"></i> Demo Akun Trading MT4
                                    </label>
                                    <input type="text" name="account_number_mt4" 
                                        placeholder="Masukkan Nomor Akun MT4"
                                        class="w-full bg-black/50 border border-white/20 rounded-xl px-3 py-2.5 sm:px-4 sm:py-3 text-white placeholder-gray-500 focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent transition text-xs sm:text-sm">
                                </div>
                            </div>

                            <!-- MT5 Section -->
                            <div class="space-y-4 bg-white/5 p-4 rounded-xl border border-white/10">
                                <div>
                                    <label class="block text-[10px] sm:text-xs font-bold text-gray-400 uppercase mb-2">
                                        <i class="fas fa-building mr-1 text-accent"></i> Nama Broker Legal
                                    </label>
                                    <input type="text" name="broker_name_mt5" 
                                        placeholder="Kosongkan jika broker tidak terdaftar"
                                        class="w-full bg-black/50 border border-white/20 rounded-xl px-3 py-2.5 sm:px-4 sm:py-3 text-white placeholder-gray-500 focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent transition text-xs sm:text-sm">
                                    <p class="text-[9px] sm:text-[10px] text-gray-500 mt-1 sm:mt-2">Masukkan nama broker tempat akun trading Anda terdaftar</p>
                                </div>
                                <div>
                                    <label class="block text-[10px] sm:text-xs font-bold text-gray-400 uppercase mb-2">
                                        <i class="fas fa-hashtag mr-1 text-accent"></i> Demo Akun Trading MT5
                                    </label>
                                    <input type="text" name="account_number_mt5" 
                                        placeholder="Masukkan Nomor Akun MT5"
                                        class="w-full bg-black/50 border border-white/20 rounded-xl px-3 py-2.5 sm:px-4 sm:py-3 text-white placeholder-gray-500 focus:border-accent focus:outline-none focus:ring-1 focus:ring-accent transition text-xs sm:text-sm">
                                </div>
                            </div>
                        </div>
                        <p class="text-[9px] sm:text-[10px] text-gray-500 mt-2 text-center sm:text-left">Anda bisa mengisi salah satu atau keduanya sekaligus.</p>

                        <button type="submit"
                            class="w-full py-3 sm:py-4 bg-gradient-to-r from-accent to-green-600 text-black font-black uppercase tracking-widest rounded-xl hover:from-green-500 hover:to-accent transition shadow-lg shadow-accent/20 flex items-center justify-center gap-2 group text-[10px] sm:text-xs">
                            <i class="fas fa-check-circle group-hover:scale-110 transition-transform"></i>
                            <span>Aktivasi Lisensi Sekarang</span>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ROADMAP CONTENT: PELATIHAN -->
    <div id="content-pelatihan" class="roadmap-section hidden">
        <div class="space-y-6">
<!-- Zoom Meeting Button -->
<div class="p-8 bg-gradient-to-br from-blue-600/20 to-blue-400/20 rounded-3xl border border-white/10 text-center">
    
    <div class="w-20 h-20 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
        <i class="fas fa-video text-blue-400 text-3xl"></i>
    </div>

<h3 class="text-xl font-bold text-white mb-4 uppercase tracking-tight">
    PROGRAM ADVOKASI<br>
    START 1 JUNI 2026 • LIVE SETIAP HARI 20.00 WIB
</h3>

    <p class="text-gray-400 text-sm mb-8 max-w-md mx-auto">
        Untuk mendapatkan akses layanan dan materi lengkap, silakan klik tombol Beli Layanan.
Atau langsung ikuti webinar dengan klik tombol Join Zoom Webinar.
    </p>

    <!-- CTA Buttons -->
    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
        
        <!-- Button 1: Zoom -->
        <a href="<?= $settings['zoom_link'] ?? '#' ?>" 
           target="_blank"
           class="inline-flex items-center justify-center gap-3 bg-[#2D8CFF] hover:bg-white hover:text-[#2D8CFF] text-white font-black uppercase tracking-widest px-8 py-4 rounded-2xl transition-all shadow-xl text-xs shadow-blue-500/20 w-full sm:w-auto">
            Join Zoom Webinar 
            <i class="fas fa-external-link-alt text-[10px]"></i>
        </a>

        <!-- Button 2: Beli Layanan -->
        <a href="<?= base_url('layanan-advokasi') ?>" 
           class="inline-flex items-center justify-center gap-3 bg-accent hover:bg-white hover:text-black text-black font-black uppercase tracking-widest px-8 py-4 rounded-2xl transition-all shadow-xl text-xs shadow-accent/30 w-full sm:w-auto">
            Beli Layanan 
            <i class="fas fa-arrow-right text-[10px]"></i>
        </a>

    </div>

</div>

            <!-- Webinar Schedule -->
            <div class="space-y-4">
                <div class="flex items-center gap-2 px-2">
                    <i class="fas fa-video text-blue-400 text-sm"></i>
                    <h3 class="text-sm font-bold text-white uppercase tracking-widest">Jadwal Webinar Advokasi</h3>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    <?php
                    $dayMap = [
                        'Monday' => 'Senin',
                        'Tuesday' => 'Selasa',
                        'Wednesday' => 'Rabu',
                        'Thursday' => 'Kamis',
                        'Friday' => 'Jumat',
                        'Saturday' => 'Sabtu',
                        'Sunday' => 'Minggu',
                    ];
                    $todayIndo = $dayMap[date('l')] ?? '';

                    $webinarSchedule = [
    ['day' => 'Senin', 'id' => 'senin', 'title' => 'ADVOKASI | PAHAM REGULASI & PERLINDUNGAN TRADER', 'topic' => 'Ekosistem Regulasi, Keamanan Margin, Hak & Perlindungan Trader', 'icon' => 'fa-shield-halved'],

    ['day' => 'Selasa', 'id' => 'selasa', 'title' => 'ATOMIC HABITS — TRANSFORMASI MENJADI TRADER', 'topic' => 'Mindset, Kebiasaan, Disiplin, dan Transformasi Trader', 'icon' => 'fa-arrows-spin'],

    ['day' => 'Rabu', 'id' => 'rabu', 'title' => 'BUDAYA TRADING YANG SEHAT', 'topic' => 'Etika dan tanggung jawab trader, Profesionalisme, transparansi, dan tanggung jawab', 'icon' => 'fa-handshake'],

    ['day' => 'Kamis', 'id' => 'kamis', 'title' => 'PAHAM DERIVATIF TRADING & AUTOMATED TOOLS (EA AIWE)', 'topic' => 'Struktur market derivatif, Jenis Akun Trading, Analisa Teknikal, Penggunaan EA AIWE', 'icon' => 'fa-chart-simple'],

    ['day' => 'Jumat', 'id' => 'jumat', 'title' => 'PAHAM ASET KEUANGAN DIGITAL & SMART TOOLS (EA BIDBOX)', 'topic' => 'Crypto, Blockchain, Market Digital, dan EA BIDBOX', 'icon' => 'fa-cubes'],

    ['day' => 'Sabtu', 'id' => 'sabtu', 'title' => 'FUNDAMENTAL & NEWS READING', 'topic' => 'Economic Calendar, News Impact Market', 'icon' => 'fa-newspaper'],

    ['day' => 'Minggu', 'id' => 'minggu', 'title' => 'WEEKLY REVIEW & EVALUATION', 'topic' => 'Jurnal, Evaluasi, Perbaikan Strategi', 'icon' => 'fa-clipboard-check'],
];
                    ?>
                    <?php foreach ($webinarSchedule as $item): ?>
                    <?php $isToday = ($item['day'] === $todayIndo); ?>
                    <div class="p-6 bg-[#121212] border <?= $isToday ? 'border-blue-500/40 shadow-[0_0_20px_rgba(59,130,246,0.1)]' : 'border-white/5' ?> rounded-3xl hover:border-blue-500/30 transition-all relative overflow-hidden flex flex-col h-full group">
                        <?php if ($isToday): ?>
                            <div class="absolute top-0 right-0">
                                <span class="bg-blue-500 text-black text-[8px] font-black px-3 py-1 rounded-bl-xl uppercase tracking-widest animate-pulse">Hari Ini</span>
                            </div>
                        <?php endif; ?>

                        <div class="w-10 h-10 <?= $isToday ? 'bg-blue-500/20 text-blue-400' : 'bg-white/5 text-gray-500' ?> rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                            <i class="fas <?= $item['icon'] ?>"></i>
                        </div>
                        
                        <div class="mb-4">
                            <span class="text-[9px] font-black <?= $isToday ? 'text-blue-400' : 'text-gray-600' ?> uppercase tracking-widest mb-1"><?= $item['day'] ?></span>
                            <h4 class="text-white font-bold text-base mb-1 uppercase leading-tight"><?= $item['title'] ?></h4>
                            <p class="text-accent text-[10px] font-bold uppercase tracking-wider mb-2"><?= $item['topic'] ?></p>
                            <?php if ($isToday): ?>
                                <div class="mt-3 flex items-center justify-center bg-white/5 py-2 rounded-xl">
                                    <img src="https://almai.id/images/lari.gif" alt="Today" class="h-10 object-contain">
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mt-auto pt-4 border-t border-white/5">
                            <button onclick="showSyllabus('<?= $item['id'] ?>')" class="w-full py-2.5 <?= $isToday ? 'bg-blue-500 text-black' : 'bg-white/5 text-gray-400 hover:bg-blue-500 hover:text-black' ?> text-[10px] font-black uppercase tracking-widest rounded-xl transition-all flex items-center justify-center gap-2">
                                <i class="fas fa-list-ul"></i> Detail Silabus
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- ROADMAP CONTENT: SELESAI -->
    <div id="content-selesai" class="roadmap-section hidden">
        <div class="bg-[#111] border border-white/10 rounded-3xl p-8 md:p-12 text-center">
            <?php if (($user['level_id'] ?? 0) < 2): ?>
                <!-- Alert Upgrade PRO -->
                <div class="bg-red-500/10 border border-red-500/30 rounded-3xl p-6 md:p-8 text-left">
                    <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                        <div class="w-16 h-16 bg-red-500/20 rounded-2xl flex items-center justify-center flex-shrink-0 border border-red-500/30">
                            <i class="fas fa-user-shield text-red-500 text-3xl"></i>
                        </div>
                        <div class="flex-1 text-center md:text-left">
                            <h4 class="text-white font-bold text-xl mb-2">Upgrade Akun PRO Diperlukan</h4>
                            <p class="text-gray-400 text-sm mb-6 leading-relaxed">
                                Untuk menyelesaikan program ini dan mengunduh sertifikat, Anda wajib melakukan verifikasi akun menjadi <b>PRO</b>. Ini diperlukan untuk memastikan validitas data peserta program advokasi.
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <a href="<?= base_url('user/kyc') ?>" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-red-500 text-white font-black uppercase tracking-widest rounded-2xl hover:bg-red-600 transition shadow-lg shadow-red-500/20 text-xs">
                                    Verifikasi Akun PRO Sekarang <i class="fas fa-arrow-right"></i>
                                </a>
                                <button onclick="switchRoadmap('pelatihan', 1, 5)" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white/5 border border-white/10 text-white font-black uppercase tracking-widest rounded-2xl hover:bg-white/10 transition text-xs">
                                    Kembali ke Pelatihan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="w-20 h-20 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-check-circle text-green-500 text-4xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-white mb-2">Layanan Selesai</h3>
                <p class="text-gray-400 mb-10 max-w-md mx-auto text-sm">
                    <?= $isCompleted ? 'Selamat! Anda telah menyelesaikan seluruh rangkaian program advokasi.' : 'Selamat! Anda telah menyelesaikan seluruh rangkaian program advokasi. Silakan isi testimoni singkat sebelum mengunduh sertifikat.' ?>
                </p>


            <?php if (!$isCompleted): ?>
            <!-- Form Testimoni -->
            <form id="testimonialForm" class="max-w-xl mx-auto text-left space-y-6 bg-black/30 p-6 md:p-8 rounded-[2rem] border border-white/5">
                <?= csrf_field() ?>
                <h4 class="text-white font-bold text-lg flex items-center gap-2 mb-2">
                    <i class="fas fa-comment-dots text-accent"></i> Form Testimoni
                </h4>
                
                <div class="space-y-6">
                    <div class="p-4 bg-accent/5 border border-accent/10 rounded-2xl">
                        <label class="flex items-start gap-3 cursor-pointer group">
                            <div class="mt-0.5">
                                <input type="checkbox" name="confirmed" required class="w-4 h-4 rounded border-white/20 bg-black text-accent focus:ring-accent focus:ring-offset-black">
                            </div>
                            <span class="text-[11px] leading-relaxed text-gray-400 group-hover:text-gray-200 transition">
                                Saya menyatakan bahwa saya telah mengikuti seluruh rangkaian **PROGRAM ADVOKASI TRADER BASIC (7 HARI)** dengan sungguh-sungguh dan telah memahami setiap materi yang disampaikan.
                            </span>
                        </label>
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-[0.2em] mb-3 ml-1">Kesan & Pesan Anda</label>
                        <textarea name="testimonial" rows="4" required 
                            class="w-full bg-black border border-white/10 rounded-2xl px-5 py-4 text-sm text-white placeholder-gray-600 focus:border-accent focus:outline-none transition-all resize-none" 
                            placeholder="Ceritakan pengalaman Anda mengikuti program ini..."></textarea>
                    </div>

                    <button type="submit" class="w-full py-4 bg-accent text-black font-black uppercase tracking-widest rounded-2xl hover:bg-white hover:scale-[1.02] transition-all shadow-xl text-[10px]">
                        Kirim Testimoni & Selesaikan
                    </button>
                </div>
            </form>
            <?php endif; ?>

            <div id="btn-sertifikat-container" class="<?= $isCompleted ? 'block' : 'hidden' ?> mt-8">
                <button onclick="switchRoadmap('sertifikat', 4, 5)" class="px-10 py-4 bg-white/5 border border-white/10 text-white font-black uppercase tracking-widest rounded-2xl hover:bg-white hover:text-black transition-all text-xs">
                    Lihat Sertifikat <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- ROADMAP CONTENT: SERTIFIKAT -->
    <div id="content-sertifikat" class="roadmap-section hidden">
        <div class="bg-[#111] border border-white/10 rounded-[2.5rem] p-8 md:p-12">
            <?php if ($isCompleted && $certificate): ?>
                <div class="flex flex-col lg:flex-row items-center gap-12">
                    <div class="w-full lg:w-1/2">
                        <div class="aspect-[1.414/1] bg-black/40 rounded-3xl border-4 border-accent/20 flex items-center justify-center relative overflow-hidden group shadow-2xl">
                            <img src="https://images.unsplash.com/photo-1589330694653-ded6df03f754?w=800" alt="Certificate Preview" class="w-full h-full object-cover opacity-40 group-hover:scale-105 transition-transform duration-1000">
                            <div class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center bg-black/40 backdrop-blur-[2px]">
                                <i class="fas fa-certificate text-accent text-7xl mb-6 drop-shadow-[0_0_15px_rgba(51,232,24,0.5)]"></i>
                                <h4 class="text-white font-black text-2xl mb-2 tracking-tighter uppercase">SERTIFIKAT ADVOKASI</h4>
                                <p class="text-accent text-[10px] font-black uppercase tracking-[0.4em]"><?= esc($certificate['certificate_number']) ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="w-full lg:w-1/2 space-y-8 text-center lg:text-left">
                        <div>
                            <span class="inline-block px-4 py-1.5 bg-accent/10 text-accent text-[10px] font-black uppercase tracking-widest rounded-full mb-4">Sertifikat Terbit</span>
                            <h3 class="text-3xl md:text-4xl font-black text-white uppercase tracking-tight mb-4 leading-none">Selamat Atas Keberhasilan Anda!</h3>
                            <p class="text-gray-400 text-sm leading-relaxed max-w-md mx-auto lg:mx-0">
                                Sertifikat ini merupakan bukti resmi bahwa Anda telah menyelesaikan PROGRAM ADVOKASI TRADER BASIC (7 HARI) dan memiliki pemahaman mendalam tentang keamanan serta legalitas trading.
                            </p>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 p-6 bg-white/5 rounded-3xl border border-white/10">
                            <div class="text-left">
                                <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">Nomor Sertifikat</p>
                                <p class="text-white font-bold text-sm truncate"><?= esc($certificate['certificate_number']) ?></p>
                            </div>
                            <div class="text-left border-l border-white/10 pl-4">
                                <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">Tanggal Terbit</p>
                                <p class="text-white font-bold text-sm"><?= date('d M Y', strtotime($certificate['issued_at'])) ?></p>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="<?= base_url('certificate/' . $certificate['certificate_number']) ?>" target="_blank" class="flex-1 py-4 bg-accent text-black font-black uppercase tracking-widest rounded-2xl hover:shadow-[0_0_30px_rgba(51,232,24,0.3)] hover:scale-[1.02] transition-all text-center text-xs">
                                <i class="fas fa-download mr-2"></i> Download (PDF)
                            </a>
                            <button onclick="Swal.fire('Fitur Segera Datang', 'Fitur bagikan sertifikat sedang dikembangkan.', 'info')" class="flex-1 py-4 bg-white/5 border border-white/10 text-white font-black uppercase tracking-widest rounded-2xl hover:bg-white hover:text-black transition-all text-center text-xs">
                                <i class="fas fa-share-alt mr-2"></i> Bagikan
                            </button>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="py-16 text-center max-w-lg mx-auto">
                    <div class="w-24 h-24 bg-red-500/10 rounded-full flex items-center justify-center mx-auto mb-8 border border-red-500/20">
                        <i class="fas fa-lock text-red-500 text-3xl"></i>
                    </div>
                    <h4 class="text-white font-black text-2xl uppercase tracking-tight mb-4">Sertifikat Belum Tersedia</h4>
                    <p class="text-gray-400 text-sm leading-relaxed mb-10">
                        Anda belum menyelesaikan seluruh tahapan program. Silakan selesaikan tahap <b>Aktivasi EA</b> dan berikan <b>Testimoni</b> pada tab sebelumnya untuk mendapatkan sertifikat secara otomatis.
                    </p>
                    <button onclick="switchRoadmap('selesai', 3, 5)" class="px-10 py-4 bg-white/5 border border-white/10 text-white font-black uppercase tracking-widest rounded-2xl hover:bg-white hover:text-black transition-all text-xs">
                        Kembali ke Form Testimoni <i class="fas fa-arrow-left ml-2 order-first"></i>
                    </button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Reports Section (Moved to Bottom) -->
    <div class="pt-8 border-t border-white/5">
        <div class="flex items-center justify-between mb-8">
            <h3 class="text-sm font-black text-white uppercase tracking-widest">Riwayat Laporan Anda</h3>
            <a href="javascript:void(0)" onclick="checkProAndRedirect()" class="text-[10px] text-accent font-bold hover:underline">Buat Laporan Baru <i class="fas fa-arrow-right ml-1"></i></a>
        </div>

        <div class="space-y-4 px-0.5">
            <?php 
                // Filter: Jangan tampilkan data registrasi (pending_payment)
                $realReports = array_filter($advokasiList, function($r) {
                    return $r['status'] !== 'pending_payment';
                });
            ?>

            <?php if (empty($realReports)): ?>
                <!-- Empty State -->
                <div class="bg-[#121212] border border-white/5 rounded-[2.5rem] p-16 text-center shadow-2xl">
                    <div class="w-24 h-24 bg-accent/10 rounded-full flex items-center justify-center mx-auto mb-6 text-accent">
                        <i class="fas fa-file-invoice text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-black text-white uppercase tracking-tight mb-2">Belum Ada Laporan</h3>
                    <p class="text-gray-500 text-sm mb-8 max-w-sm mx-auto">Anda belum pernah membuat laporan pengaduan trading. Klik tombol di bawah untuk mulai.</p>
                    <a href="javascript:void(0)" onclick="checkProAndRedirect()" class="inline-flex items-center gap-3 px-8 py-4 bg-accent text-black font-black uppercase tracking-widest rounded-2xl hover:bg-white transition-all shadow-[0_0_30px_rgba(51,232,24,0.2)]">
                        <i class="fas fa-plus-circle"></i>
                        Buat Laporan
                    </a>
                </div>
            <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($realReports as $adv): ?>
                        <div class="bg-[#121212] border border-white/5 rounded-[2rem] p-6 hover:border-accent/30 transition-all group relative overflow-hidden">
                            <!-- Background Decor -->
                            <div class="absolute -top-10 -right-10 w-32 h-32 bg-accent/5 blur-3xl group-hover:bg-accent/10 transition-all"></div>
                            
                            <div class="flex items-center justify-between mb-6 relative z-10">
                                <div>
                                    <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest block mb-1">ID Laporan #<?= $adv['id'] ?></span>
                                    <span class="text-[10px] text-accent font-black uppercase tracking-widest"><?= date('d F Y', strtotime($adv['created_at'])) ?></span>
                                </div>
                                <div class="px-3 py-1 bg-white/5 border border-white/10 rounded-full">
                                    <span class="text-[9px] font-black uppercase tracking-widest <?= $adv['status'] == 'pending' ? 'text-yellow-500' : 'text-accent' ?>">
                                        <?= str_replace('_', ' ', $adv['status']) ?>
                                    </span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-6 relative z-10">
                                <div class="p-4 bg-black/40 rounded-2xl border border-white/5">
                                    <p class="text-[8px] text-gray-600 uppercase font-black tracking-widest mb-1">Broker</p>
                                    <p class="text-[11px] text-white font-bold truncate"><?= esc($adv['broker_name'] ?? '-') ?></p>
                                </div>
                                <div class="p-4 bg-black/40 rounded-2xl border border-white/5">
                                    <p class="text-[8px] text-gray-600 uppercase font-black tracking-widest mb-1">Estimasi Loss</p>
                                    <p class="text-[11px] text-accent font-bold">Rp <?= number_format($adv['loss_amount'] ?? 0, 0, ',', '.') ?></p>
                                </div>
                            </div>

                            <a href="<?= base_url('user/advokasi/detail/' . $adv['id']) ?>" class="w-full py-3 bg-white/5 border border-white/10 rounded-xl text-[10px] font-black uppercase tracking-widest text-white hover:bg-white hover:text-black transition-all flex items-center justify-center gap-2 relative z-10">
                                Lihat Detail <i class="fas fa-arrow-right text-[8px]"></i>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function handleRoadmapClick(tabId, index, totalSteps) {
        const hasPurchased = <?= $purchase ? 'true' : 'false' ?>;
        
        // Allow 'informasi' and 'pelatihan' tabs regardless of purchase
        if (tabId === 'informasi' || tabId === 'pelatihan') {
            switchRoadmap(tabId, index, totalSteps);
            return;
        }

        // Restrict other tabs if not purchased
        if (!hasPurchased) {
            Swal.fire({
                title: '<span class="text-white uppercase tracking-tighter font-bold">Akses Terkunci</span>',
                text: "Anda harus mendaftar Program Advokasi terlebih dahulu untuk mengakses tahap ini.",
                icon: 'lock',
                showCancelButton: true,
                confirmButtonColor: '#33E818',
                cancelButtonColor: '#444',
                confirmButtonText: 'Daftar Sekarang',
                cancelButtonText: 'Tutup',
                background: '#111',
                color: '#fff',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= base_url('daftar-advokasi') ?>';
                }
            });
            return;
        }

        switchRoadmap(tabId, index, totalSteps);
    }

    function checkPurchaseAndRedirect(url) {
        const hasPurchased = <?= $purchase ? 'true' : 'false' ?>;
        if (!hasPurchased) {
            Swal.fire({
                title: '<span class="text-white uppercase tracking-tighter font-bold">Akses Terkunci</span>',
                text: "Anda harus mendaftar Program Advokasi terlebih dahulu untuk mengakses materi ini.",
                icon: 'lock',
                showCancelButton: true,
                confirmButtonColor: '#33E818',
                cancelButtonColor: '#444',
                confirmButtonText: 'Daftar Sekarang',
                cancelButtonText: 'Tutup',
                background: '#111',
                color: '#fff',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= base_url('daftar-advokasi') ?>';
                }
            });
            return;
        }
        window.location.href = url;
    }

    function checkProAndRedirect() {
        const hasPurchased = <?= $purchase ? 'true' : 'false' ?>;
        const isPro = <?= ($user['level_id'] ?? 0) >= 2 ? 'true' : 'false' ?>;

        if (!hasPurchased) {
            Swal.fire({
                title: '<span class="text-white uppercase tracking-tighter font-bold">Akses Terkunci</span>',
                text: "Anda harus mendaftar Program Advokasi terlebih dahulu untuk membuat laporan.",
                icon: 'lock',
                showCancelButton: true,
                confirmButtonColor: '#33E818',
                cancelButtonColor: '#444',
                confirmButtonText: 'Daftar Sekarang',
                cancelButtonText: 'Tutup',
                background: '#111',
                color: '#fff',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= base_url('daftar-advokasi') ?>';
                }
            });
            return;
        }
        
        if (!isPro) {
            Swal.fire({
                title: '<span class="text-white uppercase tracking-tighter font-bold">Akun Belum Terverifikasi</span>',
                text: "Laporan Advokasi hanya tersedia untuk Akun PRO. Silakan selesaikan verifikasi KYC terlebih dahulu.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#33E818',
                cancelButtonColor: '#444',
                confirmButtonText: 'Verifikasi Sekarang',
                cancelButtonText: 'Nanti Saja',
                background: '#111',
                color: '#fff',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= base_url('user/kyc') ?>';
                }
            });
        } else {
            window.location.href = '<?= base_url('user/advokasi/buat-laporan') ?>';
        }
    }

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

        // Update progress text
        const progressTexts = [
            'Untuk memulai klik tombol : Pelatihan',
            'Untuk aktifkan Expert Advisor klik tombol : Aktivasi EA',
            'Untuk menyelesaikan pelatihan klik tombol : Selesai',
            'Untuk melihat sertifikat pelatihan klik tombol : Sertifikat',
            'Selamat! Sertifikat Anda telah tersedia'
        ];
        document.getElementById('progress-text').textContent = progressTexts[index] || 'Tahap ' + (index + 1);

        // Update styles
        const allSteps = document.querySelectorAll('.roadmap-step-item');
        allSteps.forEach((el, i) => {
            const stepId = el.getAttribute('data-id');
            const tabEl = document.getElementById('tab-' + stepId);
            const iconEl = document.getElementById('icon-' + stepId);
            const badgeEl = document.getElementById('badge-' + stepId);
            const labelEl = document.getElementById('label-' + stepId);

            const isCompleted = i < index;
            const isActive = i === index;

            if (isCompleted) {
                el.classList.add('completed');
            } else {
                el.classList.remove('completed');
            }

            if (tabEl) {
                if (isCompleted || isActive) {
                    tabEl.className = "w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 lg:w-14 lg:h-14 rounded-full bg-[#111] border-2 sm:border-[2.5px] md:border-[3px] border-accent flex items-center justify-center transition-all duration-300 relative z-10 mx-auto shadow-[0_0_15px_rgba(51,232,24,0.3)]";
                } else {
                    tabEl.className = "w-8 h-8 sm:w-10 sm:h-10 md:w-12 md:h-12 lg:w-14 lg:h-14 rounded-full bg-[#111] border-2 sm:border-[2.5px] md:border-[3px] border-white/10 flex items-center justify-center transition-all duration-300 relative z-10 mx-auto";
                }
            }

            if (iconEl) {
                if (isActive) {
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
                    if (iconEl.tagName === 'IMG') {
                        const parent = iconEl.parentElement;
                        const i = document.createElement('i');
                        const roadmapSteps = <?= json_encode($roadmapSteps) ?>;
                        const stepData = roadmapSteps.find(s => s.id === stepId);
                        i.className = `fas ${stepData.icon} ${isCompleted ? 'text-accent' : 'text-gray-600'} text-xs sm:text-sm md:text-base lg:text-xl group-hover:text-accent transition-colors`;
                        i.id = 'icon-' + stepId;
                        parent.replaceChild(i, iconEl);
                    } else {
                        iconEl.classList.remove('text-accent', 'text-gray-600');
                        iconEl.classList.add(isCompleted ? 'text-accent' : 'text-gray-600');
                    }
                }
            }

            if (badgeEl) {
                badgeEl.classList.toggle('opacity-100', isCompleted);
                badgeEl.classList.toggle('opacity-0', !isCompleted);
            }

            if (labelEl) {
                labelEl.className = `mt-2 sm:mt-3 md:mt-4 text-center ${isCompleted || isActive ? 'font-bold text-white' : 'font-medium text-gray-500'} text-[9px] sm:text-[10px] md:text-xs lg:text-sm leading-tight transition-colors duration-300 px-1`;
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const roadmapSteps = <?= json_encode($roadmapSteps) ?>;
        const currentStep = <?= $currentStep ?>;
        // Always default to 'informasi' even if they are in further steps
        switchRoadmap('informasi', 0, roadmapSteps.length);

        // Auto trigger buy popup if parameter exists
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('buy') === '1') {
            showProgramInfo();
        }
    });

    function showBrokerModal() {
        Swal.fire({
            title: '<span class="text-white uppercase tracking-tighter font-bold">Pilih Broker Resmi</span>',
            html: `
                <div class="grid grid-cols-1 gap-4 mt-6">

                    <!-- Broker 3: PLUS 500-->
                    <a href="https://www.plus500.com/id/?id=139621&tags=DEMOAKUN&pl=2" target="_blank" class="flex items-center justify-between p-4 bg-white/5 border border-white/10 rounded-2xl hover:border-accent hover:bg-accent/5 transition-all group">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center p-2">
                                <img src="https://companieslogo.com/img/orig/PLUS.L_BIG-cae87325.png?t=1683878536" class="w-full h-full object-contain" alt="PLUS500">
                            </div>
                            <div class="text-left">
                                <h4 class="text-white font-bold text-sm group-hover:text-accent transition-colors">Plus 500</h4>
                                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-black">LEGALITAS • BAPPEBTI • OJK • BANK INDONESIA | WebApp</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-600 group-hover:text-accent"></i>
                    </a>
                    
                    <!-- Broker 1: Gatra Mega -->
                    <a href="https://clientarea.moneymallfutures.com/register?ref=ICJQNG2R" target="_blank" class="flex items-center justify-between p-4 bg-white/5 border border-white/10 rounded-2xl hover:border-accent hover:bg-accent/5 transition-all group">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center p-2">
                                <img src="https://www.moneymallfutures.com/fe/assets/img/favicon-money.png" class="w-full h-full object-contain" alt="Gatra Mega">
                            </div>
                            <div class="text-left">
                                <h4 class="text-white font-bold text-sm group-hover:text-accent transition-colors">Money Mall</h4>
                                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-black">LEGALITAS • BAPPEBTI • OJK • BANK INDONESIA | METATRADER 4</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-600 group-hover:text-accent"></i>
                    </a>

                    <!-- Broker 2: ICDX | Multilateral -->
                    <a href="https://www.icdx.co.id/" target="_blank" class="flex items-center justify-between p-4 bg-white/5 border border-white/10 rounded-2xl hover:border-accent hover:bg-accent/5 transition-all group">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center p-2">
                                <img src="https://www.icdx.co.id/cms/img/437a4e69-b5d7-42ba-8c79-cc651b61bfe7/logo-icdx.png?fm=&q=80&fit=max&crop=2494%2C872%2C0%2C0&w=">
                            </div>
                            <div class="text-left">
                                <h4 class="text-white font-bold text-sm group-hover:text-accent transition-colors">ICDX | Multilateral</h4>
                                <p class="text-[10px] text-gray-500 uppercase tracking-widest font-black">LEGALITAS • BAPPEBTI • OJK • BANK INDONESIA | METATRADER 5</p>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-gray-600 group-hover:text-accent"></i>
                    </a>
                </div>
            `,
            showConfirmButton: false,
            showCloseButton: true,
            background: '#111',
            color: '#fff',
            customClass: {
                popup: 'rounded-[2rem] border border-white/10 shadow-2xl',
                closeButton: 'text-gray-500 hover:text-white transition'
            }
        });
    }

    function showProgramInfo() {
        Swal.fire({
            title: '<div class="text-left"><p class="text-[10px] text-accent font-black uppercase tracking-widest mb-1">Program Details</p><h3 class="text-xl font-black text-white uppercase tracking-tighter">PROGRAM ADVOKASI TRADER (BASIC)</h3></div>',
            html: `
                <div class="text-left space-y-6">
                    <div class="bg-accent/10 border border-accent/20 p-4 rounded-2xl">
                        <p class="text-white text-[11px] font-bold italic">Aman → Mindset → Etika → Teknikal→ Expansi → Bertumbuh → Siap Menjadi Trader</p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <h4 class="text-white font-black text-[10px] uppercase tracking-widest mb-2 flex items-center gap-2">
                                <i class="fas fa-id-card text-blue-400"></i> I. Identitas Program
                            </h4>
                            <ul class="space-y-1.5 text-[11px] text-gray-400">
                                <li class="flex items-start gap-2"><i class="fas fa-circle text-[4px] mt-1.5 text-accent"></i> <span><strong>Nama:</strong> Program Advokasi Trader (Basic)</span></li>
                                <li class="flex items-start gap-2"><i class="fas fa-circle text-[4px] mt-1.5 text-accent"></i> <span><strong>Jenis:</strong> Pelatihan Dasar Trading & Perlindungan Konsumen</span></li>
                                <li class="flex items-start gap-2"><i class="fas fa-circle text-[4px] mt-1.5 text-accent"></i> <span><strong>Durasi:</strong> ± 7 Hari (± 21–28 Jam Pelatihan)</span></li>
                                <li class="flex items-start gap-2"><i class="fas fa-circle text-[4px] mt-1.5 text-accent"></i> <span><strong>Metode:</strong> Blended Learning (Teori, Studi Kasus, Simulasi, Mentoring)</span></li>
                                <li class="flex items-start gap-2"><i class="fas fa-circle text-[4px] mt-1.5 text-accent"></i> <span><strong>Target:</strong> Calon Trader, Trader / Investor, Calon WPA</span></li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="text-white font-black text-[10px] uppercase tracking-widest mb-2 flex items-center gap-2">
                                <i class="fas fa-bullseye text-red-400"></i> II. Tujuan Program
                            </h4>
                            <ul class="space-y-1.5 text-[11px] text-gray-400">
                                <li class="flex items-start gap-2"><i class="fas fa-check text-accent mt-0.5 text-[10px]"></i> <span>Memahami legalitas dan keamanan trading</span></li>
                                <li class="flex items-start gap-2"><i class="fas fa-check text-accent mt-0.5 text-[10px]"></i> <span>Memahami Ekosistem Legal di Indonesia</span></li>
                                <li class="flex items-start gap-2"><i class="fas fa-check text-accent mt-0.5 text-[10px]"></i> <span>Mampu mengelola risiko secara rasional</li>
                                <li class="flex items-start gap-2"><i class="fas fa-check text-accent mt-0.5 text-[10px]"></i> <span>Siap mengikuti program pendampingan WPA tingkat lanjut</span></li>
                            </ul>
                        </div>

                        <div>
                            <h4 class="text-white font-black text-[10px] uppercase tracking-widest mb-2 flex items-center gap-2">
                                <i class="fas fa-stream text-purple-400"></i> III. Manfaat Untuk Peserta
                            </h4>
                            <ul class="space-y-1.5 text-[11px] text-gray-400">
                                <li class="flex items-start gap-2"><i class="fas fa-check text-accent mt-0.5 text-[10px]"></i> <span>Advokasi Basic (online)</span></li>
                                <li class="flex items-start gap-2"><i class="fas fa-check text-accent mt-0.5 text-[10px]"></i> <span>Modul Edukasi</span></li>
                                <li class="flex items-start gap-2"><i class="fas fa-check text-accent mt-0.5 text-[10px]"></i> <span>Software Expert Advisor AIWE</span></li>
                                <li class="flex items-start gap-2"><i class="fas fa-check text-accent mt-0.5 text-[10px]"></i> <span>Perlindungan Konsumen (trader)</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            `,
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonText: 'Daftar Advokasi',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#33e818',
            cancelButtonColor: 'rgba(255,255,255,0.05)',
            width: '550px',
            background: '#111',
            color: '#fff',
            customClass: {
                popup: 'rounded-[2rem] border border-white/10 shadow-2xl',
                confirmButton: 'rounded-xl px-6 py-3 text-xs font-black uppercase tracking-widest !text-black',
                cancelButton: 'rounded-xl px-6 py-3 text-xs font-black uppercase tracking-widest',
                closeButton: 'text-gray-500 hover:text-white transition'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '<?= base_url('daftar-advokasi') ?>';
            }
        });
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            Swal.fire({
                title: 'Copied!',
                text: 'License key copied to clipboard',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false,
                background: '#111',
                color: '#fff'
            });
        });
    }

    const activationForm = document.getElementById('activationForm');
    if (activationForm) {
        activationForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const brokerMt4 = formData.get('broker_name_mt4') ? formData.get('broker_name_mt4').trim() : '';
            const accountMt4 = formData.get('account_number_mt4') ? formData.get('account_number_mt4').trim() : '';
            const brokerMt5 = formData.get('broker_name_mt5') ? formData.get('broker_name_mt5').trim() : '';
            const accountMt5 = formData.get('account_number_mt5') ? formData.get('account_number_mt5').trim() : '';

            const hasMt4 = brokerMt4 !== '' && accountMt4 !== '';
            const hasMt5 = brokerMt5 !== '' && accountMt5 !== '';

            if (!hasMt4 && !hasMt5) {
                Swal.fire({
                    title: 'Form Tidak Lengkap',
                    text: 'Harap lengkapi setidaknya satu form aktivasi (Broker dan Nomor Akun) untuk MT4 atau MT5.',
                    icon: 'warning',
                    confirmButtonColor: '#33E818',
                    background: '#111',
                    color: '#fff',
                });
                return;
            }

            if ((brokerMt4 !== '' && accountMt4 === '') || (brokerMt4 === '' && accountMt4 !== '')) {
                 Swal.fire({
                    title: 'Form MT4 Tidak Lengkap',
                    text: 'Nama broker dan nomor akun MT4 harus diisi keduanya jika ingin mengaktifkan MT4.',
                    icon: 'warning',
                    confirmButtonColor: '#33E818',
                    background: '#111',
                    color: '#fff',
                });
                return;
            }

            if ((brokerMt5 !== '' && accountMt5 === '') || (brokerMt5 === '' && accountMt5 !== '')) {
                 Swal.fire({
                    title: 'Form MT5 Tidak Lengkap',
                    text: 'Nama broker dan nomor akun MT5 harus diisi keduanya jika ingin mengaktifkan MT5.',
                    icon: 'warning',
                    confirmButtonColor: '#33E818',
                    background: '#111',
                    color: '#fff',
                });
                return;
            }
            
            Swal.fire({
                title: 'Konfirmasi Aktivasi',
                text: "Pastikan nomor akun trading sudah benar. Akun akan di-lock setelah aktivasi.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#33E818',
                cancelButtonColor: '#444',
                confirmButtonText: 'Ya, Aktivasi!',
                background: '#111',
                color: '#fff',
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses Aktivasi...',
                        didOpen: () => { Swal.showLoading(); },
                        background: '#111',
                        color: '#fff',
                        allowOutsideClick: false
                    });

                    fetch('<?= base_url('user/advokasi/activate-license') ?>', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            '<?= config('Security')->headerName ?>': '<?= csrf_hash() ?>'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonColor: '#33E818',
                                background: '#111',
                                color: '#fff',
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Gagal!',
                                text: data.message,
                                icon: 'error',
                                confirmButtonColor: '#33E818',
                                background: '#111',
                                color: '#fff',
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Terjadi kesalahan sistem.',
                            icon: 'error',
                            background: '#111',
                            color: '#fff',
                        });
                    });
                }
            });
        });
    }

    const syllabusData = {
        'senin': {
            day: 'SENIN',
            title: 'PAHAM REGULASI DAN PERLINDUNGAN TRADER',
            topic: 'Ekosistem Regulasi, Keamanan Margin, Hak & Perlindungan Trader',
            duration: '2–3 Jam',
            core: ['Struktur regulasi Derivatif dan Aset Keuangan Digital di Indonesia', 'Peran regulator dan lembaga pengawas industri', 'Broker Legal vs Broker Offshore', 'Risiko penipuan, investasi bodong, dan manipulasi market', 'Advokasi & Perlindungan Trader'],
            method: ['Lecture (40%)', 'Studi Kasus (20%)', 'Diskusi (40%)'],
            evaluation: ['Quiz identifikasi broker legal dan ilegal', 'Simulasi pengaduan ke broker'],
            indicators: ['Memahami struktur regulasi trading dan aset digital di Indonesia', 'Mampu mengidentifikasi jenis permasalahan trading', 'Memahami hak serta perlindungan trader dalam aktivitas transaksi']
        },
        'selasa': {
            day: 'SELASA',
            title: 'ATOMIC HABITS — TRANSFORMASI MENJADI TRADER',
            topic: 'Mindset, Kebiasaan, Disiplin, dan Transformasi Trader',
            duration: '2–3 Jam',
            core: ['Dasar Atomic Habits dan mindset trader', 'Pembentukan kebiasaan dan disiplin trading', 'Transformasi kebiasaan buruk menjadi kebiasaan produktif', 'Membangun sistem trading, jurnal, dan evaluasi konsisten', 'Pengendalian emosi, risiko, dan transformasi mental trader'],
            method: ['Lecture (40%)', 'Studi Kasus (20%)', 'Diskusi (40%)'],
            evaluation: ['Quiz mindset dan kebiasaan trader', 'Analisa studi kasus kebiasaan trading yang merugikan', 'Simulasi jurnal dan trading plan'],
            indicators: ['Memahami konsep Atomic Habits dalam trading', 'Mampu memahami dasar pengendalian emosi dan manajemen risiko', 'Mampu menggunakan jurnal dan evaluasi sederhana dalam aktivitas trading']
        },
        'rabu': {
            day: 'RABU',
            title: 'MEMBANGUN BUDAYA TRADING YANG SEHAT',
            topic: 'Strategi & Konsistensi',
            duration: '2–3 Jam',
            core: ['Trading Plan', 'Risk Management', 'Eksekusi Trading', 'Psikologi Saat Eksekusi', 'Simulasi dan Evaluasi Trading'],
            method: ['Lecture (25%)', 'Simulasi (50%)', 'Mentoring (25%)'],
            evaluation: ['Penyusunan trading plan', 'Simulasi eksekusi trading'],
            indicators: ['Mampu menyusun trading plan sederhana', 'Mampu menentukan entry & exit sesuai rule', 'Mampu menjalankan strategi secara disiplin']
        },
        'kamis': {
            day: 'KAMIS',
            title: 'PAHAM DERIVATIF TRADING & AUTOMATED TOOLS (EA AIWE)',
            topic: 'Struktur Market, Platform Trading, dan EA AIWE',
            duration: '2–3 Jam',
            core: ['Dasar struktur market derivatif dan faktor pergerakan harga', 'Jenis akun trading, leverage, dan profil risiko trader', 'Penggunaan platform trading dan manajemen posisi dasar', 'Analisis Teknikal Dasar', 'Integrasi Expert Advisor (AIWE)'],
            method: ['Lecture (40%)', 'Chart Analysis (20%)', 'Simulasi Trading (40%)'],
            evaluation: ['Quiz dasar market derivatif dan platform trading', 'Analisa sederhana trend market dan support-resistance', 'Simulasi entry, stop loss, dan take profit', 'Simulasi Penggunaan EA AIWE'],
            indicators: ['Memahami struktur market derivatif dasar', 'Memahami jenis akun trading Multilateral dan SPA', 'Memahami fungsi EA dalam trading.']
        },
        'jumat': {
            day: 'JUMAT',
            title: 'PAHAM ASET KEUANGAN DIGITAL & SMART TOOLS (EA BIDBOX)',
            topic: 'Crypto, Blockchain, dan Market Digital, EA BIDBOX',
            duration: '2–3 Jam',
            core: ['Dasar Crypto dan Blockchain', 'Karakteristik market crypto: volatilitas, sentimen, dan 24/7 market', 'Perbedaan Forex dan Crypto', 'Risiko keamanan aset digital: scam, hacking, dan perlindungan akun', 'Spot, derivatif, diversifikasi, serta penggunaan smart tools EA BIDBOX'],
            method: ['Lecture (40%)', 'Workshop & Simulasi (40%)', 'Studi Kasus (20%)'],
            evaluation: ['Quiz dasar crypto, blockchain, dan market digital', 'Analisa kasus risiko keamanan aset digital', 'Evaluasi penggunaan EA BIDBOX dan manajemen risiko dasar'],
            indicators: ['Memahami dasar crypto dan blockchain.', 'Mampu mengidentifikasi risiko dan keamanan aset digital.', 'Memahami Diversifikasi Market dan penggunaan Smart Tools Bidbox']
        },
        'sabtu': {
            day: 'SABTU',
            title: 'FUNDAMENTAL MARKET & NEWS READING',
            topic: 'Economic Calendar, News Impact Market',
            duration: '2–3 Jam',
            core: ['Cara membaca berita fundamental market', 'Dampak news terhadap pergerakan harga', 'Pengembangan Sistem Trading', 'Jenis news penting (interest rate, inflation, NFP, CPI)', 'Reaksi market terhadap berita high impact'],
            method: ['Lecture (40%)', 'Studi Kasus (40%)', 'Simulasi (20%)'],
            evaluation: ['Quiz dasar news dan economic calendar', 'Analisa dampak berita terhadap market', 'Studi kasus pergerakan harga saat news release'],
            indicators: ['Memahami dasar news dan pengaruhnya terhadap market;', 'Mampu membaca economic calendar sederhana;', 'Mampu menganalisis reaksi market terhadap news.']
        },
        'minggu': {
            day: 'MINGGU',
            title: 'WEEKLY REVIEW & TRADING EVALUATION',
            topic: 'Jurnal, Evaluasi, Perbaikan Strategi',
            duration: '2–3 Jam',
            core: ['Review jurnal trading mingguan', 'Evaluasi hasil profit dan loss secara objektif', 'Penetapan target dan rencana minggu berikutnya'],
            method: ['Review (40%)', 'Diskusi (30%)', 'Coaching (30%)'],
            evaluation: ['Quiz evaluasi pemahaman trading plan', 'Review jurnal trading peserta', 'Studi kasus kesalahan trading dan perbaikan sistem'],
            indicators: ['Mampu melakukan evaluasi trading secara objektif;', 'Mampu membaca kesalahan dan memperbaiki sistem trading;', 'Mampu menyusun perencanaan trading mingguan.']
        }
    };

    function showSyllabus(dayId) {
        const data = syllabusData[dayId];
        if (!data) return;

        let contentHtml = `
            <div class="space-y-6 text-left">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white/5 p-3 rounded-xl border border-white/10">
                        <p class="text-[10px] text-gray-500 uppercase font-black">Durasi</p>
                        <p class="text-sm text-white font-bold">${data.duration}</p>
                    </div>
                    <div class="bg-white/5 p-3 rounded-xl border border-white/10">
                        <p class="text-[10px] text-gray-500 uppercase font-black">Topik</p>
                        <p class="text-sm text-accent font-bold">${data.topic}</p>
                    </div>
                </div>
                
                <div>
                    <h5 class="text-xs font-black text-white uppercase tracking-widest mb-3 flex items-center gap-2">
                        <i class="fas fa-book text-blue-400"></i> Materi Inti
                    </h5>
                    <ul class="space-y-2">
                        ${data.core.map(item => `<li class="text-xs text-gray-400 flex items-start gap-2"><i class="fas fa-circle text-[6px] mt-1.5 text-accent"></i> <span>${item}</span></li>`).join('')}
                    </ul>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h5 class="text-xs font-black text-white uppercase tracking-widest mb-3 flex items-center gap-2">
                            <i class="fas fa-chalkboard-teacher text-purple-400"></i> Metode Belajar
                        </h5>
                        <ul class="space-y-2">
                            ${data.method.map(item => `<li class="text-[11px] text-gray-400 flex items-center gap-2"><i class="fas fa-check text-green-500 text-[10px]"></i> ${item}</li>`).join('')}
                        </ul>
                    </div>
                    <div>
                        <h5 class="text-xs font-black text-white uppercase tracking-widest mb-3 flex items-center gap-2">
                            <i class="fas fa-tasks text-orange-400"></i> Evaluasi
                        </h5>
                        <ul class="space-y-2">
                            ${data.evaluation.map(item => `<li class="text-[11px] text-gray-400 flex items-center gap-2"><i class="fas fa-edit text-orange-500 text-[10px]"></i> ${item}</li>`).join('')}
                        </ul>
                    </div>
                </div>

                <div class="bg-accent/5 border border-accent/20 p-4 rounded-2xl">
                    <h5 class="text-xs font-black text-accent uppercase tracking-widest mb-3">Indikator Kompetensi</h5>
                    <ul class="space-y-2">
                        ${data.indicators.map(item => `<li class="text-[11px] text-gray-300 flex items-start gap-2"><i class="fas fa-star text-accent text-[8px] mt-1"></i> <span>${item}</span></li>`).join('')}
                    </ul>
                </div>

                <div class="pt-4">
                    <a href="javascript:void(0)" onclick="checkPurchaseAndRedirect('<?= base_url('user/advokasi/materi') ?>?day=${dayId}')" class="w-full py-4 bg-white text-black font-black uppercase tracking-widest rounded-2xl hover:bg-accent transition flex items-center justify-center gap-3">
                        <i class="fas fa-external-link-alt text-xs"></i> Lihat Materi Interaktif
                    </a>
                </div>
            </div>
        `;

        Swal.fire({
            title: `<div class="text-left"><p class="text-[10px] text-gray-500 font-black uppercase tracking-widest mb-1">${data.day}</p><h3 class="text-xl font-black text-white uppercase tracking-tighter">${data.title}</h3></div>`,
            html: contentHtml,
            showConfirmButton: false,
            showCloseButton: true,
            width: '600px',
            background: '#111',
            color: '#fff',
            customClass: {
                popup: 'rounded-3xl border border-white/10 shadow-2xl',
                closeButton: 'text-gray-500 hover:text-white transition'
            }
        });
    }

    const testimonialForm = document.getElementById('testimonialForm');
    if (testimonialForm) {
        testimonialForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            Swal.fire({
                title: 'Kirim Testimoni?',
                text: "Anda menyatakan telah menyelesaikan seluruh rangkaian program.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#33E818',
                cancelButtonColor: '#444',
                confirmButtonText: 'Ya, Selesaikan!',
                cancelButtonText: 'Batal',
                background: '#111',
                color: '#fff',
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        didOpen: () => { Swal.showLoading(); },
                        background: '#111',
                        color: '#fff',
                        allowOutsideClick: false
                    });

                    fetch('<?= base_url('user/advokasi/complete') ?>', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            '<?= config('Security')->headerName ?>': '<?= csrf_hash() ?>'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonColor: '#33E818',
                                background: '#111',
                                color: '#fff',
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Gagal!',
                                text: data.message,
                                icon: 'error',
                                confirmButtonColor: '#33E818',
                                background: '#111',
                                color: '#fff',
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Terjadi kesalahan sistem.',
                            icon: 'error',
                            background: '#111',
                            color: '#fff',
                        });
                    });
                }
            });
        });
    }
        function showSignalPopup() {
        Swal.fire({
            title: 'ALMAI AI SIGNAL',
            html: `
                <div class="flex flex-col gap-3 mt-4">
                    <a href="https://whatsapp.com/channel/0029Vb8F4Uc9WtC4cJHudt2k" class="px-6 py-4 bg-gradient-to-r from-blue-500 to-blue-700 text-white font-bold rounded-xl hover:shadow-lg transition">PLUS 500 | ALMAI SIGNAL</a>
                </div>
            `,
            showConfirmButton: false,
            showCloseButton: true,
            background: '#111',
            color: '#fff',
            customClass: {
                popup: 'border border-white/10 rounded-2xl'
            }
        });
    }
</script>

<?= $this->endSection() ?>
