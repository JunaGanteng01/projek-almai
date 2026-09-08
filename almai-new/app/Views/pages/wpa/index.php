<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Hero Section -->
<section class="relative pt-32 pb-14 overflow-hidden bg-[#030805]">
    <!-- Green Grid & Radial Glow Background -->
    <div class="absolute inset-0 pointer-events-none z-0">
        <!-- Radial Glow Center -->
        <div class="absolute inset-0" style="background: radial-gradient(ellipse 70% 60% at 50% 40%, rgba(34, 197, 94, 0.22) 0%, rgba(6, 24, 12, 0.6) 48%, #030805 90%);"></div>
        <!-- Distinct Green Grid Lines -->
        <div class="absolute inset-0 opacity-85" style="background-image: linear-gradient(to right, rgba(34, 197, 94, 0.18) 1px, transparent 1px), linear-gradient(to bottom, rgba(34, 197, 94, 0.18) 1px, transparent 1px); background-size: 44px 44px; mask-image: radial-gradient(ellipse 85% 70% at 50% 45%, black 60%, transparent 98%); -webkit-mask-image: radial-gradient(ellipse 85% 70% at 50% 45%, black 60%, transparent 98%);"></div>
        <!-- Central Ambient Glow -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[720px] h-[360px] bg-[#22c55e]/15 blur-[120px] rounded-full pointer-events-none"></div>
    </div>

    <div class="container mx-auto px-6 relative z-10 text-center max-w-5xl">
        <!-- Badge / Eyebrow -->
        <p class="text-[#22c55e] font-extrabold tracking-[0.25em] text-xs sm:text-sm mb-3.5 uppercase inline-block">
            TRADER PROFESIONAL
        </p>

        <!-- Main Title -->
        <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-[62px] font-extrabold tracking-tight mb-5 leading-tight">
            <span class="text-white">WPA — </span><span class="text-[#22c55e]">Mitra Almai</span>
        </h1>

        <!-- Subtitle / Lead Paragraph -->
        <p class="text-white font-bold text-base sm:text-lg md:text-xl lg:text-2xl max-w-4xl mx-auto mb-8 leading-snug sm:leading-relaxed">
            Para WPA Almai adalah trader profesional independen yang bermitra untuk mendukung edukasi, pendampingan, dan pengembangan trader Indonesia.
        </p>

        <!-- Legal Definition Callout Box -->
        <div class="max-w-3xl mx-auto text-left">
            <blockquote class="border-l-2 border-[#22c55e] pl-4 py-1">
                <p class="text-emerald-100/75 italic text-xs sm:text-sm md:text-[15px] leading-relaxed font-normal">
                    Wakil Penasihat Berjangka (WPA) adalah orang perseorangan yang berdasarkan kesepakatan dengan Penasihat Berjangka melaksanakan sebagian fungsi Penasihat Berjangka.
                </p>
            </blockquote>
        </div>
    </div>
</section>

<!-- Action Buttons -->
<section class="py-6">
    <div class="container mx-auto px-6 text-center">
        <div class="flex flex-wrap sm:flex-nowrap gap-4 justify-center">
            <a href="<?= base_url('daftar-wpa') ?>" class="w-full sm:w-auto px-8 py-3 bg-accent text-black font-bold rounded-full hover:bg-white transition shadow-[0_0_15px_rgba(51,232,24,0.3)]">
                Daftar Menjadi WPA
            </a>
            <a href="<?= base_url('cwpa') ?>" class="w-full sm:w-auto px-8 py-3 border border-accent text-accent font-bold rounded-full hover:bg-accent/10 transition">
                Lihat CWPA
            </a>
        </div>
    </div>
</section>

<!-- WPA Grid -->
<section class="py-12">
    <div class="container mx-auto px-6">
        <?php if (empty($wpaList)): ?>
            <div class="text-center py-12 text-gray-400">
                <i class="fas fa-search text-4xl mb-4"></i>
                <p>Tidak ada WPA yang ditemukan.</p>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-6">
                <?php foreach ($wpaList as $wpa): ?>
                    <?= view('partials/cards/wpa_card', ['wpa' => $wpa]) ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Pengurus: managed through Super Admin > Tim -->
<section id="pengurus" class="pt-8 pb-24" aria-labelledby="pengurus-heading">
    <div class="container mx-auto px-6">
        <div class="h-px max-w-3xl mx-auto mb-10 bg-gradient-to-r from-transparent via-accent to-transparent"></div>
        <h2 id="pengurus-heading" class="text-4xl md:text-5xl font-bold text-accent text-center mb-12">Pengurus Almai</h2>
        <?php if (empty($team)): ?>
            <p class="text-center text-gray-400 py-12">Informasi pengurus akan segera tersedia.</p>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-6">
                <?php foreach ($team as $member): ?>
                    <article class="group bg-[#111] border border-white/10 rounded-2xl overflow-hidden hover:border-accent/50 transition">
                        <div class="relative aspect-square overflow-hidden bg-black">
                            <div class="absolute inset-0 bg-accent/10 rounded-full blur-3xl pointer-events-none"></div>
                            <?php if (!empty($member['photo'])): ?>
                                <img src="<?= esc(base_url('file/' . $member['photo']), 'attr') ?>"
                                     alt="<?= esc($member['name'], 'attr') ?>" loading="lazy" width="400" height="400"
                                     class="relative w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            <?php else: ?>
                                <div class="relative w-full h-full flex items-center justify-center text-accent text-6xl" aria-hidden="true">
                                    <i class="fas fa-user"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-4 border-t border-white/5">
                            <h3 class="text-white font-bold leading-snug break-words"><?= esc($member['name']) ?></h3>
                            <p class="text-accent text-sm mt-2 break-words"><?= esc($member['role']) ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?= $this->endSection() ?>
