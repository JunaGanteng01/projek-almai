<?= $this->extend('user/partials/layout') ?>

<?= $this->section('content') ?>
<div class="flex justify-end mb-6">
    <a href="<?= base_url('layanan') ?>" class="px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-sm">
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
        <a href="<?= base_url('layanan') ?>" class="inline-block px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-sm">Jelajahi Layanan</a>
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
                        <?php if (($kelas['type'] ?? '') === 'cwpa' || ($kelas['kelas_id'] ?? '') === 'cwpa' || stripos($kelas['title'] ?? '', 'cwpa') !== false): ?>
                            <a href="<?= base_url('user/layanan-detail/pendampingan-cwpa?trx=' . ($kelas['id'] ?? '')) ?>"
                                class="flex-1 h-9 flex items-center justify-center bg-accent text-black font-bold rounded-lg hover:bg-white transition-all text-[10px] uppercase tracking-wider">
                                Akses Kelas
                            </a>
                        <?php elseif ($isTool): ?>
                            <a href="<?= base_url('user/layanan-detail/' . ($kelas['layanan_id'] ?? $kelas['kelas_id']) . '?trx=' . $kelas['id'] . '&type=tools') ?>"
                                class="flex-1 h-9 flex items-center justify-center bg-purple-600 text-white font-bold rounded-lg hover:bg-purple-500 transition-all text-[10px] uppercase tracking-wider">
                                Access Tool
                            </a>
                        <?php elseif ($isLive): ?>
                            <a href="<?= base_url('user/layanan-detail/' . ($kelas['layanan_id'] ?? $kelas['kelas_id']) . '?trx=' . $kelas['id'] . '&type=event') ?>"
                                class="flex-1 h-9 flex items-center justify-center bg-accent text-black font-bold rounded-lg hover:bg-white transition-all text-[10px] uppercase tracking-wider">
                                Lihat Layanan
                            </a>
                        <?php else: ?>
                            <a href="<?= base_url('user/layanan-detail/' . ($kelas['layanan_id'] ?? $kelas['kelas_id']) . '?trx=' . $kelas['id'] . '&type=recorded') ?>"
                                class="flex-1 h-9 flex items-center justify-center bg-accent text-black font-bold rounded-lg hover:bg-white transition-all text-[10px] uppercase tracking-wider">
                                Mulai Belajar
                            </a>
                        <?php endif; ?>

                        <div class="flex gap-1.5">
                            <?php if (!($kelas['has_reviewed'] ?? false)): ?>
                                <button onclick="openUlasanModal(<?= $kelas['layanan_id'] ?>, '<?= $kelas['layanan_type_id'] ?? 'layanan' ?>', '<?= esc($kelas['title'] ?? $kelas['product_name'], 'js') ?>')"
                                    class="w-9 h-9 flex items-center justify-center border border-white/10 text-yellow-500 rounded-lg hover:bg-white/5 transition-all" title="Beri Ulasan">
                                    <i class="fas fa-star text-xs"></i>
                                </button>
                            <?php endif; ?>
                            <?php if (($kelas['type'] ?? '') === 'cwpa'): ?>
                                <a href="<?= base_url('user/layanan-detail/pendampingan-cwpa') ?>"
                                    class="w-9 h-9 flex items-center justify-center border border-white/10 text-gray-400 rounded-lg hover:bg-white/5 transition-all" title="Detail">
                                    <i class="fas fa-info-circle text-xs"></i>
                                </a>
                            <?php else: ?>
                                <a href="<?= base_url('layanan/' . ($kelas['layanan_id'] ?? $kelas['kelas_id'])) ?>"
                                    class="w-9 h-9 flex items-center justify-center border border-white/10 text-gray-400 rounded-lg hover:bg-white/5 transition-all" title="Detail">
                                    <i class="fas fa-info-circle text-xs"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Ulasan Modal -->
<div id="ulasanModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 max-w-md w-full transform scale-95 opacity-0 transition-all duration-300" id="ulasanModalContent">
        <h3 class="text-xl font-bold mb-1">Berikan Ulasan</h3>
        <p class="text-sm text-gray-400 mb-6" id="ulasanLayananTitle">Layanan Name</p>

        <form id="ulasanForm" class="space-y-4">
            <?= csrf_field() ?>
            <input type="hidden" name="layanan_id" id="ulasanLayananId">
            <input type="hidden" name="layanan_type" id="ulasanLayananType">

            <!-- Star Rating -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Rating</label>
                <div class="flex gap-2">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <button type="button" onclick="setRating(<?= $i ?>)" class="star-btn text-2xl text-gray-700 hover:text-yellow-500 transition-colors" data-value="<?= $i ?>">
                            <i class="fas fa-star"></i>
                        </button>
                    <?php endfor; ?>
                </div>
                <input type="hidden" name="rating" id="ratingInput" value="5">
            </div>

            <!-- Ulasan Text -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Ulasan Anda</label>
                <textarea name="ulasan" rows="4" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-accent outline-none transition" placeholder="Tuliskan pengalaman Anda menggunakan layanan ini..."></textarea>
            </div>

            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeUlasanModal()" class="flex-1 py-3 border border-white/10 rounded-xl hover:bg-white/5 transition">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center justify-center gap-2">
                    <span>Kirim Ulasan</span>
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 z-[110] hidden items-center justify-center bg-black/80 backdrop-blur-md p-4">
    <div class="bg-[#111] border border-white/10 rounded-3xl p-8 max-w-sm w-full text-center transform scale-95 opacity-0 transition-all duration-500" id="successModalContent">
        <div class="relative mb-6">
            <div class="w-24 h-24 mx-auto rounded-full bg-accent/20 border-2 border-accent/30 flex items-center justify-center animate-pulse">
                <i class="fas fa-gift text-accent text-4xl"></i>
            </div>
            <div class="absolute -top-2 -right-2 w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center animate-bounce shadow-lg shadow-yellow-500/50">
                <i class="fas fa-star text-black text-lg"></i>
            </div>
        </div>

        <h3 class="text-2xl font-bold mb-2 bg-gradient-to-r from-accent to-yellow-400 bg-clip-text text-transparent">Selamat!</h3>
        <p class="text-gray-400 mb-6 leading-relaxed">Ulasan Anda berhasil dikirim dan Anda baru saja mendapatkan <span class="text-accent font-bold" id="awardedPoints">10</span> Poin ALMAI.</p>

        <button onclick="location.reload()" class="w-full py-4 bg-accent text-black font-bold rounded-2xl hover:bg-white transition-all transform hover:scale-[1.02] active:scale-95 shadow-lg shadow-accent/20">
            Terima Kasih
        </button>
    </div>
</div>

<style>
    .star-btn.active {
        color: #ebff00;
    }

    @keyframes pulse-custom {
        0% {
            transform: scale(1);
            opacity: 0.5;
        }

        50% {
            transform: scale(1.05);
            opacity: 0.8;
        }

        100% {
            transform: scale(1);
            opacity: 0.5;
        }
    }
</style>

<script>
    let currentRating = 5;

    function openUlasanModal(id, type, title) {
        document.getElementById('ulasanLayananId').value = id;
        document.getElementById('ulasanLayananType').value = type;
        document.getElementById('ulasanLayananTitle').textContent = title;

        setRating(5);

        const modal = document.getElementById('ulasanModal');
        const content = document.getElementById('ulasanModalContent');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeUlasanModal() {
        const modal = document.getElementById('ulasanModal');
        const content = document.getElementById('ulasanModalContent');

        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');

        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    function showSuccessModal(points) {
        document.getElementById('awardedPoints').textContent = points;
        const modal = document.getElementById('successModal');
        const content = document.getElementById('successModalContent');

        modal.classList.remove('hidden');
        modal.classList.add('flex');

        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function setRating(val) {
        currentRating = val;
        document.getElementById('ratingInput').value = val;

        const stars = document.querySelectorAll('.star-btn');
        stars.forEach((star, index) => {
            if (index < val) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
    }

    document.getElementById('ulasanForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        try {
            const response = await fetch('<?= base_url('user/layanan/submit-ulasan') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (result.status === 'success') {
                closeUlasanModal();
                setTimeout(() => {
                    showSuccessModal(result.points || 10);
                }, 400);
            } else {
                alert(result.message);
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan sistem');
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        }
    });
</script>

<?= $this->endSection() ?>