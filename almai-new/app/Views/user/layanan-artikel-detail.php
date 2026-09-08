<?= $this->extend('user/partials/layout') ?>

<?= $this->section('content') ?>
<?php
$thumbnail = $kelas['thumbnail'] ?? 'https://via.placeholder.com/1200x500';
$articleTitle = $kelas['title'] ?? ($kelas['name'] ?? 'Artikel');
$articleContent = $kelas['description'] ?? '';
?>

<div class="mb-6 flex items-center justify-between gap-3">
    <a href="<?= base_url('user/layanan-saya') ?>" class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition">
        <i class="fas fa-arrow-left"></i>
        <span class="text-sm">Kembali ke Layanan Saya</span>
    </a>
    <?php if (!empty($transaksiId)): ?>
        <span class="text-xs px-3 py-1 bg-white/5 border border-white/10 rounded-full text-gray-400">
            Transaksi #<?= esc($transaksiId) ?>
        </span>
    <?php endif; ?>
</div>

<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden mb-6">
    <div class="relative">
        <img src="<?= esc($thumbnail) ?>" alt="<?= esc($articleTitle) ?>" class="w-full h-52 md:h-72 object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>
        <div class="absolute bottom-5 left-5 right-5">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-400 border border-amber-500/40 mb-3">
                <i class="fas fa-newspaper"></i> ARTIKEL PREMIUM
            </span>
            <h1 class="text-2xl md:text-3xl font-bold leading-tight"><?= esc($articleTitle) ?></h1>
        </div>
    </div>

    <div class="p-6 md:p-8">
        <?php if (!empty($wpa)): ?>
            <?php
            $wpaPhoto = $wpa['photo'] ?? '';
            if ($wpaPhoto && !str_starts_with($wpaPhoto, 'http')) {
                $wpaPhoto = base_url('file/' . $wpaPhoto);
            } elseif (!$wpaPhoto) {
                $wpaPhoto = 'https://via.placeholder.com/100';
            }
            ?>
            <div class="flex items-center gap-3 mb-6 pb-6 border-b border-white/10">
                <img src="<?= esc($wpaPhoto) ?>" alt="<?= esc($wpa['name'] ?? 'WPA') ?>" class="w-12 h-12 rounded-full object-cover border border-white/20">
                <div>
                    <p class="font-semibold text-white"><?= esc($wpa['name'] ?? 'Mentor ALMAI') ?></p>
                    <p class="text-xs text-gray-400">Penulis</p>
                </div>
            </div>
        <?php endif; ?>

        <article class="prose prose-invert max-w-none prose-headings:text-white prose-p:text-gray-200 prose-li:text-gray-200 prose-strong:text-white prose-a:text-accent">
            <?php if (!empty(trim(strip_tags($articleContent)))): ?>
                <?php
                // Process HTML content to fix relative image paths
                $processedContent = $articleContent;
                
                // Convert relative image paths to full URLs
                $processedContent = preg_replace_callback(
                    '/<img\s+([^>]*?\s)?src=["\']([^"\']+)["\']([^>]*)>/i',
                    function($matches) {
                        $prefix = $matches[1] ?? '';
                        $src = $matches[2];
                        $suffix = $matches[3] ?? '';
                        
                        // If not absolute URL, make it absolute
                        if (!str_starts_with($src, 'http') && !str_starts_with($src, '//')) {
                            $src = base_url('file/' . ltrim($src, '/'));
                        }
                        
                        return '<img ' . $prefix . 'src="' . esc($src) . '"' . $suffix . '>';
                    },
                    $processedContent
                );
                
                echo $processedContent;
                ?>
            <?php else: ?>
                <p>Konten artikel belum tersedia.</p>
            <?php endif; ?>
        </article>
    </div>
</div>

<?php if (!empty($materials)): ?>
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-6">
        <h3 class="font-bold mb-4 flex items-center gap-2">
            <i class="fas fa-folder-open text-accent"></i> File Pendukung
        </h3>

        <div class="space-y-3">
            <?php foreach ($materials as $material): ?>
                <?php
                $fileType = strtolower($material['file_type'] ?? '');
                $filePath = $material['file_path'] ?? '';
                $downloadUrl = $filePath ? base_url('file/' . ltrim($filePath, '/')) : '#';
                $icon = 'fa-file';
                if (in_array($fileType, ['pdf', 'doc', 'docx'])) $icon = 'fa-file-pdf';
                if (in_array($fileType, ['zip', 'rar', '7z'])) $icon = 'fa-file-archive';
                ?>
                <a href="<?= esc($downloadUrl) ?>" target="_blank" class="flex items-center justify-between p-3 bg-black/40 border border-white/10 rounded-xl hover:border-accent/40 transition">
                    <div class="flex items-center gap-3 min-w-0">
                        <i class="fas <?= esc($icon) ?> text-accent"></i>
                        <div class="min-w-0">
                            <p class="text-sm font-medium truncate"><?= esc($material['title'] ?? 'File') ?></p>
                            <p class="text-xs text-gray-400 uppercase"><?= esc($fileType ?: 'file') ?></p>
                        </div>
                    </div>
                    <span class="text-xs px-2 py-1 rounded bg-accent/20 text-accent">Download</span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>
