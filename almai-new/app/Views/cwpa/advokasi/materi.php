<?= $this->extend('cwpa/layouts/main') ?>

<?= $this->section('content') ?>

<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <div>
        <h2 class="text-2xl font-black text-white uppercase tracking-tight">Materi Pelatihan Advokasi</h2>
        <p class="text-gray-500 text-sm">Pelajari modul dan strategi untuk perlindungan trader</p>
    </div>
    <a href="<?= base_url('cwpa/dashboard/advokasi') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/5 border border-white/10 text-white font-bold rounded-xl hover:bg-white/10 transition text-sm">
        <i class="fas fa-arrow-left text-xs"></i> Kembali ke Dashboard
    </a>
</div>

<div class="bg-[#111] border border-white/10 rounded-3xl overflow-hidden shadow-2xl">
    <div class="p-4 bg-white/5 border-b border-white/10 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-accent/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-book-open text-accent"></i>
            </div>
            <div>
                <p class="text-xs font-black text-white uppercase tracking-widest">Interactive Material</p>
                <p class="text-[10px] text-gray-500 uppercase"><?= !empty($day) ? esc($day) : 'Full Course' ?></p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="toggleFullScreen()" class="p-2.5 bg-white/5 border border-white/10 text-gray-400 rounded-xl hover:bg-white/10 hover:text-white transition">
                <i class="fas fa-expand"></i>
            </button>
        </div>
    </div>
    
    <div class="relative w-full" style="padding-top: 56.25%;"> <!-- 16:9 Aspect Ratio -->
        <div id="canva-container" class="absolute inset-0 w-full h-full bg-black flex items-center justify-center">
            <?php if (!empty($activeLink)): ?>
                <?php 
                $embedUrl = $activeLink;
                
                // Auto-fix Canva standard links to embed links
                if (strpos($embedUrl, 'canva.com/design/') !== false && strpos($embedUrl, '<iframe') === false) {
                    if (strpos($embedUrl, '?') === false) {
                        $embedUrl .= '?embed';
                    } elseif (strpos($embedUrl, 'embed') === false) {
                        $embedUrl .= '&embed';
                    }
                }

                if (strpos($embedUrl, '<iframe') !== false): 
                    // SECURITY: Jika berupa iframe HTML, ekstrak src dan render ulang dengan esc()
                    // untuk mencegah XSS dari atribut berbahaya seperti onload, onclick, dsb.
                    preg_match('/src=["\']([^"\']+)["\']/', $embedUrl, $srcMatch);
                    $iframeSrc = $srcMatch[1] ?? '';
                    // Hanya izinkan domain embed yang dikenal
                    $allowedEmbedDomains = ['canva.com', 'docs.google.com', 'drive.google.com', 'youtube.com', 'youtu.be', 'vimeo.com', 'slides.com'];
                    $iframeSrcHost = parse_url($iframeSrc, PHP_URL_HOST) ?? '';
                    $isSafeEmbed = false;
                    foreach ($allowedEmbedDomains as $domain) {
                        if (str_ends_with($iframeSrcHost, $domain)) { $isSafeEmbed = true; break; }
                    }
                    if ($isSafeEmbed && !empty($iframeSrc)):
                ?>
                    <iframe loading="lazy" src="<?= esc($iframeSrc, 'attr') ?>"
                        class="absolute inset-0 w-full h-full border-none"
                        allowfullscreen="allowfullscreen" allow="fullscreen">
                    </iframe>
                <?php else: ?>
                    <div class="text-center p-8"><p class="text-red-400 text-sm">Sumber embed tidak diizinkan.</p></div>
                <?php
                    endif;
                else:
                    // SECURITY: Validasi URL langsung — hanya izinkan https:// dari domain yang dikenal
                    $urlHost = parse_url($embedUrl, PHP_URL_HOST) ?? '';
                    $urlScheme = parse_url($embedUrl, PHP_URL_SCHEME) ?? '';
                    $allowedEmbedDomains = ['canva.com', 'docs.google.com', 'drive.google.com', 'youtube.com', 'youtu.be', 'vimeo.com', 'slides.com'];
                    $isValidUrl = ($urlScheme === 'https');
                    foreach ($allowedEmbedDomains as $domain) {
                        if (str_ends_with($urlHost, $domain)) { $isValidUrl = true; break; }
                    }
                    if ($isValidUrl):
                ?>
                    <iframe loading="lazy" src="<?= esc($embedUrl, 'attr') ?>" 
                        class="absolute inset-0 w-full h-full border-none" 
                        allowfullscreen="allowfullscreen" allow="fullscreen">
                    </iframe>
                <?php else: ?>
                    <div class="text-center p-8"><p class="text-red-400 text-sm">Sumber embed tidak diizinkan.</p></div>
                <?php
                    endif;
                endif;
                ?>
            <?php else: ?>
                <div class="text-center p-8">
                    <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-file-powerpoint text-gray-700 text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Materi Belum Tersedia</h3>
                    <p class="text-gray-500 text-sm max-w-xs mx-auto">Admin belum mengunggah link materi interaktif untuk program ini. Silakan hubungi support.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-black/40 border border-white/10 rounded-2xl p-6 flex items-start gap-4">
        <div class="w-12 h-12 bg-blue-500/10 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fas fa-lightbulb text-blue-500"></i>
        </div>
        <div>
            <h4 class="text-white font-bold mb-1">Tips Belajar</h4>
            <p class="text-xs text-gray-500 leading-relaxed">Gunakan mode layar penuh untuk pengalaman membaca yang lebih fokus dan interaktif.</p>
        </div>
    </div>
    
    <div class="bg-black/40 border border-white/10 rounded-2xl p-6 flex items-start gap-4">
        <div class="w-12 h-12 bg-accent/10 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fas fa-question-circle text-accent"></i>
        </div>
        <div>
            <h4 class="text-white font-bold mb-1">Butuh Bantuan?</h4>
            <p class="text-xs text-gray-500 leading-relaxed">Jika materi tidak muncul, pastikan koneksi internet Anda stabil atau coba refresh halaman.</p>
        </div>
    </div>

    <div class="bg-black/40 border border-white/10 rounded-2xl p-6 flex items-start gap-4">
        <div class="w-12 h-12 bg-purple-500/10 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fas fa-certificate text-purple-500"></i>
        </div>
        <div>
            <h4 class="text-white font-bold mb-1">Lanjut Ujian</h4>
            <p class="text-xs text-gray-500 leading-relaxed">Selesaikan seluruh materi untuk mendapatkan sertifikat kelulusan Program Advokasi.</p>
        </div>
    </div>
</div>

<script>
function toggleFullScreen() {
    const elem = document.getElementById('canva-container');
    if (!document.fullscreenElement) {
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.webkitRequestFullscreen) { /* Safari */
            elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) { /* IE11 */
            elem.msRequestFullscreen();
        }
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        }
    }
}
</script>

<style>
#canva-container iframe {
    width: 100% !important;
    height: 100% !important;
    border: none !important;
}
</style>

<?= $this->endSection() ?>
