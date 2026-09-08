<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="min-h-[80vh] flex items-center justify-center pt-28 pb-12 px-4 md:pt-32">
    <div class="w-full max-w-md bg-[#111] border border-white/10 rounded-2xl p-8 text-center relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-accent/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-accent/5 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2 pointer-events-none"></div>

        <div class="relative z-10">
            <div class="w-20 h-20 bg-accent/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-clipboard-check text-accent text-4xl"></i>
            </div>

            <h1 class="text-2xl font-bold mb-2">Check-in Absensi</h1>
            <p class="text-gray-400 mb-6">Anda akan melakukan check-in untuk acara:</p>

            <?php if (!empty($event['banner_image'])): ?>
                <div class="mb-6 rounded-xl overflow-hidden border border-white/10 shadow-lg">
                    <img src="<?= base_url($event['banner_image']) ?>" alt="Banner" class="w-full h-auto object-cover max-h-64">
                </div>
            <?php endif; ?>

            <div class="bg-black/40 border border-white/10 rounded-xl p-4 mb-8 text-left">
                <div class="font-bold text-lg text-white mb-1"><?= esc($eventName) ?></div>
                <div class="text-xs text-accent uppercase tracking-wider mb-3"><?= str_replace('_', ' ', $kegiatanType) ?></div>
                <?php if (!empty($registrationRestricted)): ?>
                    <div class="inline-flex items-center gap-2 px-3 py-1 mb-3 rounded-full bg-purple-500/15 border border-purple-500/30 text-purple-300 text-xs font-bold">
                        <i class="fas fa-user-lock"></i> Khusus CWPA &amp; WPA
                    </div>
                <?php endif; ?>
                
                <div class="space-y-2 text-sm text-gray-400">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-calendar w-4 text-center"></i>
                        <span><?= date('d M Y', strtotime($event['tanggal'] ?? $event['created_at'])) ?></span>
                    </div>
                    <?php if(!empty($event['lokasi']) || !empty($event['media'])): ?>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-map-marker-alt w-4 text-center"></i>
                        <span><?= esc($event['lokasi'] ?? $event['media'] ?? '') ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php 
                        $keterangan = $event['keterangan'] ?? '';
                        if(!empty(trim($keterangan))): 
                    ?>
                    <div class="flex items-start gap-2 mt-3 pt-3 border-t border-white/5">
                        <i class="fas fa-info-circle w-4 text-center mt-1"></i>
                        <span class="whitespace-pre-wrap text-xs"><?= esc($keterangan) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($referrerName)): ?>
                <div class="bg-accent/10 border border-accent/20 rounded-xl p-3 mb-8 text-sm text-accent text-left">
                    <i class="fas fa-user-friends mr-2"></i> Direkomendasikan oleh: <strong><?= esc($referrerName) ?></strong>
                </div>
            <?php endif; ?>

            <div class="bg-green-500/10 border border-green-500/20 rounded-xl p-4 mb-8">
                <div class="flex items-center justify-center gap-3 text-green-400 font-bold mb-1">
                    <i class="fas fa-coins text-xl"></i>
                    <span>+100 Poin Almai</span>
                </div>
                <p class="text-xs text-green-500/70">Dapatkan reward poin setelah berhasil check-in</p>
            </div>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-3 rounded-lg text-sm mb-6">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (!session()->get('isLoggedIn')): ?>
                <?php 
                    $redirectParam = 'absensi/checkin/' . $kodeQr;
                    if (!empty($reff)) {
                        $redirectParam .= '?reff=' . $reff;
                    }
                ?>
                <div class="flex flex-col gap-4">
                    <a href="<?= base_url('login?redirect=' . urlencode($redirectParam)) ?>" class="w-full py-4 bg-accent text-black font-bold rounded-xl hover:bg-white hover:scale-105 transition-all flex items-center justify-center gap-2 shadow-[0_0_20px_rgba(208,255,0,0.3)]">
                        <i class="fas fa-sign-in-alt"></i> <?= !empty($registrationRestricted) ? 'Login CWPA/WPA untuk Check-in' : 'Login untuk Check-in' ?>
                    </a>
                    <?php if (empty($registrationRestricted)): ?>
                        <a href="<?= base_url('register?redirect=' . urlencode($redirectParam) . (!empty($reff) ? '&ref=' . urlencode($reff) : '')) ?>" class="text-sm text-gray-400 hover:text-white transition">
                            Belum punya akun? <span class="text-accent font-semibold hover:underline">Daftar sekarang</span>
                        </a>
                    <?php else: ?>
                        <p class="text-sm text-gray-400">Gunakan akun yang sudah berstatus CWPA atau WPA.</p>
                    <?php endif; ?>
                </div>
            <?php elseif (empty($canCheckIn)): ?>
                <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-4 text-red-300">
                    <div class="font-bold mb-1"><i class="fas fa-lock mr-2"></i>Check-in Tidak Tersedia</div>
                    <p class="text-xs text-red-300/80">Kegiatan ini hanya dapat diikuti oleh akun CWPA dan WPA.</p>
                </div>
            <?php else: ?>
                <form action="<?= current_url() ?>" method="POST">
                    <?= csrf_field() ?>
                    <button type="submit" class="w-full py-4 bg-accent text-black font-bold rounded-xl hover:bg-white hover:scale-105 transition-all flex items-center justify-center gap-2 shadow-[0_0_20px_rgba(208,255,0,0.3)]">
                        <i class="fas fa-check-circle"></i> Check-in Sekarang
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
