<div class="space-y-6">
    <!-- User Info Section -->
    <div class="flex items-start gap-4">
        <div class="w-16 h-16 rounded-full bg-accent/20 flex items-center justify-center border border-accent/30">
            <i class="fas fa-user text-2xl text-accent"></i>
        </div>
        <div class="flex-1">
            <h4 class="text-lg font-bold text-white mb-1"><?= esc($submission['user_name']) ?></h4>
            <div class="flex flex-col gap-1">
                <p class="text-sm text-gray-400 flex items-center gap-2">
                    <i class="fas fa-envelope text-xs"></i> <?= esc($submission['user_email']) ?>
                </p>
                <?php if (!empty($submission['whatsapp'])): ?>
                    <p class="text-sm text-gray-400 flex items-center gap-2">
                        <i class="fab fa-whatsapp text-xs"></i> <?= esc($submission['whatsapp']) ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
        <div class="text-right">
            <span class="text-xs text-gray-500 block mb-1">Status Pembayaran</span>
            <?php if ($submission['payment_status'] === 'paid'): ?>
                <span class="px-3 py-1 bg-green-500/20 text-green-400 rounded-full text-xs font-bold inline-block mb-2">
                    Lunas
                </span>
                <?php if (isset($payment) && $payment): ?>
                    <p class="text-xs text-gray-400 mt-1">
                        <span class="text-white font-medium">Rp <?= number_format($payment['amount'], 0, ',', '.') ?></span>
                        via <?= strtoupper($payment['payment_method']) ?>
                    </p>
                    <?php if ($payment['payment_method'] === 'transfer' && !empty($payment['transfer_proof'])): ?>
                        <button onclick="viewDocument('<?= base_url('file/' . $payment['transfer_proof']) ?>', 'Bukti Transfer')" 
                            class="mt-2 text-[10px] bg-accent/10 text-accent px-2 py-1 rounded hover:bg-accent hover:text-black transition">
                            <i class="fas fa-eye mr-1"></i> Lihat Bukti
                        </button>
                    <?php endif; ?>
                    <p class="text-[10px] text-gray-500 mt-0.5">
                        <?= date('d M Y H:i', strtotime($payment['paid_at'] ?? $payment['created_at'])) ?>
                        <br>
                        #<?= esc($payment['invoice_number']) ?>
                    </p>
                <?php endif; ?>
            <?php elseif ($submission['payment_status'] === 'pending_verification'): ?>
                <span class="px-3 py-1 bg-blue-500/20 text-blue-400 rounded-full text-xs font-bold inline-block mb-2">
                    Verifikasi Manual
                </span>
                <?php if (!empty($submission['transfer_proof']) || (isset($payment) && !empty($payment['transfer_proof']))): ?>
                    <?php $proof = $submission['transfer_proof'] ?? $payment['transfer_proof']; ?>
                    <div class="mt-1">
                        <button onclick="viewDocument('<?= base_url('file/' . $proof) ?>', 'Bukti Transfer')" 
                            class="w-full text-xs bg-accent text-black font-bold py-1.5 rounded hover:bg-white transition shadow-[0_0_10px_rgba(51,232,24,0.2)]">
                            <i class="fas fa-file-invoice-dollar mr-1"></i> Bukti Bayar
                        </button>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <span class="px-3 py-1 bg-red-500/20 text-red-400 rounded-full text-xs font-bold inline-block">
                    Belum Bayar
                </span>
            <?php endif; ?>
        </div>
    </div>

    <!-- Verification Status & Admin Note -->
    <div class="bg-black/50 border border-white/10 rounded-lg p-4">
        <div class="flex justify-between items-start mb-2">
            <div>
                <p class="text-xs text-gray-400 mb-1">Status Verifikasi</p>
                <?php if ($submission['status'] === 'approved'): ?>
                    <span class="text-sm font-bold text-accent flex items-center gap-1">
                        <i class="fas fa-check-circle"></i> Approved
                    </span>
                <?php elseif ($submission['status'] === 'rejected'): ?>
                    <span class="text-sm font-bold text-red-500 flex items-center gap-1">
                        <i class="fas fa-times-circle"></i> Rejected
                    </span>
                <?php else: ?>
                    <span class="text-sm font-bold text-blue-500 flex items-center gap-1">
                        <i class="fas fa-clock"></i> Pending
                    </span>
                <?php endif; ?>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-400 mb-1">Tanggal Daftar</p>
                <p class="text-sm text-white"><?= date('d M Y H:i', strtotime($submission['created_at'])) ?></p>
            </div>
        </div>

        <?php if (!empty($submission['admin_note'])): ?>
            <div class="mt-3 p-3 bg-white/5 border border-white/10 rounded-lg">
                <p class="text-xs text-gray-400 mb-1">Catatan Admin:</p>
                <p class="text-sm text-white"><?= nl2br(esc($submission['admin_note'])) ?></p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Additional Details -->
    <div class="bg-black/50 border border-white/10 rounded-lg p-4">
        <p class="text-xs text-gray-400 mb-3 font-medium uppercase tracking-wider">Informasi Tambahan</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-gray-400 mb-2">Social Media</p>
                <?php
                $socialMedia = is_string($submission['social_media']) ? json_decode($submission['social_media'], true) : (is_array($submission['social_media']) ? $submission['social_media'] : []);
                $hasSocial = false;
                if (is_array($socialMedia)) {
                    foreach ($socialMedia as $platform => $handle) {
                        if (!empty($handle)) {
                            $hasSocial = true;
                            $icon = match ($platform) {
                                'ig' => 'fab fa-instagram',
                                'fb' => 'fab fa-facebook',
                                'youtube' => 'fab fa-youtube',
                                'tiktok' => 'fab fa-tiktok',
                                'linkedin' => 'fab fa-linkedin',
                                default => 'fas fa-link'
                            };
                            $label = match ($platform) {
                                'ig' => 'Instagram',
                                'fb' => 'Facebook',
                                'youtube' => 'YouTube',
                                'tiktok' => 'TikTok',
                                'linkedin' => 'LinkedIn',
                                default => ucfirst($platform)
                            };

                            // Logic to ensure correct redirect
                            $url = $handle;
                            // Remove @ if exists
                            $cleanHandle = ltrim($handle, '@');
                            
                            if (!filter_var($handle, FILTER_VALIDATE_URL)) {
                                $url = match ($platform) {
                                    'ig' => "https://instagram.com/" . $cleanHandle,
                                    'fb' => "https://facebook.com/" . $cleanHandle,
                                    'tiktok' => "https://tiktok.com/@" . $cleanHandle,
                                    'youtube' => "https://youtube.com/" . $cleanHandle,
                                    'linkedin' => "https://linkedin.com/in/" . $cleanHandle,
                                    default => "https://" . $cleanHandle
                                };
                            }

                            echo '<div class="flex items-center gap-2 mb-1">';
                            echo '<div class="w-6 text-center"><i class="' . $icon . ' text-gray-400"></i></div>';
                            echo '<span class="text-xs text-gray-500 w-20">' . $label . '</span>';
                            echo '<a href="' . esc($url) . '" target="_blank" class="text-sm text-accent hover:text-white truncate flex-1">' . esc($handle) . '</a>';
                            echo '</div>';
                        }
                    }
                }
                if (!$hasSocial) echo '<span class="text-sm text-gray-500">-</span>';
                ?>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Trading Experience</p>
                <p class="text-sm text-white"><?= !empty($submission['trading_experience']) ? esc($submission['trading_experience']) : '-' ?></p>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-2">Specialties</p>
                <div class="flex flex-wrap gap-2">
                    <?php
                    $specialties = is_string($submission['specialties']) ? json_decode($submission['specialties'], true) : (is_array($submission['specialties']) ? $submission['specialties'] : []);
                    if (is_array($specialties) && !empty($specialties)) {
                        foreach ($specialties as $specialty) {
                            // Specify might be comma separated string in some legacy data or multiple selection
                            // Normalize if needed, but assuming array of strings based on input
                            $parts = explode(',', $specialty);
                            foreach ($parts as $part) {
                                echo '<span class="px-2 py-1 bg-white/10 border border-white/5 rounded text-xs text-gray-300">' . esc(trim($part)) . '</span>';
                            }
                        }
                    } else {
                        echo '<span class="text-sm text-gray-500">-</span>';
                    }
                    ?>
                </div>
            </div>
            <div>
                <p class="text-xs text-gray-400 mb-1">Kelengkapan Dokumen Fisik</p>
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <i class="fas <?= $submission['has_ijazah_s1'] ? 'fa-check text-green-400' : 'fa-times text-red-400' ?> text-xs"></i>
                        <span class="text-sm text-gray-300">Ijazah S1</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas <?= $submission['has_skck_card'] ? 'fa-check text-green-400' : 'fa-times text-red-400' ?> text-xs"></i>
                        <span class="text-sm text-gray-300">Kartu SKCK</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas <?= $submission['has_clean_record'] ? 'fa-check text-green-400' : 'fa-times text-red-400' ?> text-xs"></i>
                        <span class="text-sm text-gray-300">Bebas Pidana</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents List -->
    <div class="bg-black/50 border border-white/10 rounded-lg p-4">
        <p class="text-xs text-gray-400 mb-3 font-medium uppercase tracking-wider">Dokumen Kelengkapan</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <?php foreach ($documents as $label => $path): ?>
                <?php if (!empty($path)): ?>
                    <div class="flex items-center justify-between p-3 bg-black/50 rounded border border-white/5 hover:border-accent/30 transition group">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded bg-gray-800 flex items-center justify-center">
                                <i class="fas fa-file-alt text-gray-400 group-hover:text-accent transition"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400"><?= esc($label) ?></p>
                                <p class="text-sm font-medium text-white truncate max-w-[150px]"><?= basename($path) ?></p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="<?= base_url('file/' . $path) ?>" target="_blank"
                                class="w-8 h-8 rounded flex items-center justify-center bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white transition"
                                title="Download">
                                <i class="fas fa-download text-xs"></i>
                            </a>
                            <button onclick="viewDocument('<?= base_url('file/' . $path) ?>', '<?= esc($label) ?>')"
                                class="w-8 h-8 rounded flex items-center justify-center bg-accent/10 hover:bg-accent/20 text-accent transition"
                                title="View">
                                <i class="fas fa-eye text-xs"></i>
                            </button>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="flex items-center justify-between p-3 bg-black/50 rounded border border-white/5 opacity-50">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded bg-gray-800 flex items-center justify-center">
                                <i class="fas fa-times text-gray-500"></i>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400"><?= esc($label) ?></p>
                                <p class="text-sm font-medium text-gray-500 italic">Tidak ada file</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Actions -->
<?php if ($submission['status'] === 'pending'): ?>
    <div class="pt-6 border-t border-white/10">
        <h4 class="text-sm font-bold text-white mb-4">Aksi Verifikasi</h4>
        <div class="flex gap-4">
            <button onclick="openVerifyModal(<?= $submission['id'] ?>, '<?= esc($submission['user_name'], 'js') ?>', 'verify')"
                class="flex-1 px-4 py-3 bg-accent text-black font-bold rounded-lg hover:bg-white transition flex items-center justify-center gap-2">
                <i class="fas fa-check"></i> Verifikasi CWPA
            </button>
            <button onclick="openVerifyModal(<?= $submission['id'] ?>, '<?= esc($submission['user_name'], 'js') ?>', 'reject')"
                class="flex-1 px-4 py-3 bg-red-500/20 text-red-500 font-bold rounded-lg hover:bg-red-500/30 transition flex items-center justify-center gap-2">
                <i class="fas fa-times"></i> Tolak Pengajuan
            </button>
        </div>
    </div>
<?php endif; ?>
</div>