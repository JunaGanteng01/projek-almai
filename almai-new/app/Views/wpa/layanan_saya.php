<?= $this->extend('wpa/layouts/main') ?>

<?= $this->section('content') ?>
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold"><?= esc($pageTitle) ?></h1>
    <a href="<?= base_url('wpa/dashboard/daftar-layanan') ?>" class="px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-sm">
        <i class="fas fa-shopping-bag mr-2"></i> Beli Layanan
    </a>
</div>

<?php if (empty($myKelas)): ?>
    <div class="bg-[#111] border border-white/10 rounded-xl p-12 text-center">
        <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-graduation-cap text-gray-600 text-3xl"></i>
        </div>
        <h3 class="font-bold mb-2">Belum Ada Layanan</h3>
        <p class="text-gray-500 text-sm mb-4">Anda belum membeli layanan apapun</p>
        <a href="<?= base_url('wpa/dashboard/daftar-layanan') ?>" class="inline-block px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-sm">Jelajahi Layanan</a>
    </div>
<?php else: ?>

    <!-- Kelas List -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-4">
        <?php foreach ($myKelas as $kelas): ?>
            <?php
            $isTool = ($kelas['type_label'] ?? '') === 'Tools';
            $isLive = ($kelas['type'] ?? '') === 'live';

            if ($isTool) {
                $modeColor = 'bg-purple-500/20 text-purple-400 border-purple-500/30';
                $modeIcon = 'fa-robot';
                $modeText = 'Expert Advisor';
            } else {
                $modeColor = $isLive ? 'bg-blue-500/20 text-blue-400 border-blue-500/30' : 'bg-accent/20 text-accent border-accent/30';
                $modeIcon = $isLive ? 'fa-video' : 'fa-play-circle';
                $modeText = $isLive ? 'Live Class' : 'Video Course';
            }
            ?>
            <div class="group bg-[#111] border border-white/5 rounded-2xl overflow-hidden hover:border-accent/30 transition-all duration-300 flex flex-col shadow-lg">
                <!-- Thumbnail -->
                <div class="relative aspect-video overflow-hidden">
                    <img src="<?= esc($kelas['thumbnail'] ?? 'https://via.placeholder.com/800x450') ?>"
                        alt="<?= esc($kelas['title'] ?? '') ?>"
                        class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">

                    <div class="absolute top-2 left-2 flex flex-col gap-1.5 z-10">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 border <?= $modeColor ?> backdrop-blur-md text-[8px] font-bold rounded-full uppercase tracking-wider">
                            <i class="fas <?= $modeIcon ?>"></i> <?= $modeText ?>
                        </span>
                    </div>

                    <?php
                    $nextSession = $kelas['schedule'] ?? '';
                    $showSchedule = false;
                    if ($isLive) {
                        if (!empty($kelas['next_session'])) {
                            $nextSession = $kelas['next_session'];
                            $showSchedule = true;
                        } elseif (!empty($nextSession) && $nextSession !== '0000-00-00 00:00:00' && strtotime($nextSession) > 0) {
                            $nextSession = date('d M Y, H:i', strtotime($nextSession));
                            $showSchedule = true;
                        }
                    }
                    ?>
                    <?php if ($showSchedule): ?>
                        <div class="absolute bottom-2 left-2 right-2 z-10">
                            <div class="bg-black/70 backdrop-blur-md border border-white/10 rounded-lg p-1.5 flex items-center gap-2">
                                <i class="fas fa-calendar-alt text-accent text-[10px]"></i>
                                <p class="text-[9px] font-bold text-white truncate"><?= esc($nextSession) ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </div>

                <!-- Content -->
                <div class="p-4 flex flex-col flex-1">
                    <div class="mb-3">
                        <div class="flex flex-col gap-1 mb-1.5">
                            <h3 class="text-sm font-bold text-white line-clamp-1 group-hover:text-accent transition-colors leading-tight">
                                <?= esc($kelas['title'] ?? $kelas['product_name']) ?>
                            </h3>
                            <?php if (!empty($kelas['package_name'])): ?>
                                <span class="self-start px-1.5 py-0.5 bg-yellow-500/10 border border-yellow-500/20 text-yellow-500 text-[8px] font-bold rounded uppercase">
                                    <?= esc($kelas['package_name']) ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="flex items-center gap-3 text-[9px] text-gray-500">
                            <span class="flex items-center gap-1"><i class="fas fa-calendar-alt opacity-50"></i> <?= date('d M Y', strtotime($kelas['created_at'])) ?></span>
                            <?php if (!empty($kelas['level'])): ?>
                                <span class="flex items-center gap-1"><i class="fas fa-signal opacity-50"></i> <?= esc($kelas['level']) ?></span>
                            <?php endif; ?>
                            <?php if (!empty($kelas['wpa_name'])): ?>
                                <span class="flex items-center gap-1 truncate"><i class="fas fa-user-tie opacity-50"></i> <?= esc($kelas['wpa_name']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-auto flex gap-2">
                        <!-- Redirect to User Dashboard logic for details for now -->
                        <?php if ($isTool): ?>
                            <a href="<?= base_url('wpa/dashboard/layanan-detail/' . ($kelas['layanan_id'] ?? $kelas['kelas_id']) . '?trx=' . $kelas['id']) ?>"
                                class="flex-1 h-9 flex items-center justify-center bg-purple-600 text-white font-bold rounded-lg hover:bg-purple-500 transition-all text-[10px] uppercase tracking-wider">
                                Access Tool
                            </a>
                        <?php elseif ($isLive): ?>
                            <a href="<?= base_url('wpa/dashboard/layanan-detail/' . ($kelas['layanan_id'] ?? $kelas['kelas_id']) . '?trx=' . $kelas['id']) ?>"
                                class="flex-1 h-9 flex items-center justify-center bg-accent text-black font-bold rounded-lg hover:bg-white transition-all text-[10px] uppercase tracking-wider">
                                Lihat Layanan
                            </a>
                        <?php else: ?>
                            <a href="<?= base_url('wpa/dashboard/layanan-detail/' . ($kelas['layanan_id'] ?? $kelas['kelas_id']) . '?trx=' . $kelas['id']) ?>"
                                class="flex-1 h-9 flex items-center justify-center bg-accent text-black font-bold rounded-lg hover:bg-white transition-all text-[10px] uppercase tracking-wider">
                                Mulai Belajar
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>