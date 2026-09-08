<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<style>
    .legal-dynamic-content, .legal-dynamic-content *, .prose-invert, .prose-invert * {
        color: white !important;
    }
    .legal-dynamic-content a {
        color: #33e818 !important;
        text-decoration: underline;
    }
</style>
<?php
$price = $kelas['price'];
$poinNeeded = ($kelas['poin_price'] ?? 0) > 0 ? $kelas['poin_price'] : ceil($price / 100);
$canPayWithPoin = $poinBalance >= $poinNeeded;
$poinValue = $poinBalance * 100; // Nilai poin dalam rupiah

// Determine product type label
$productLabel = 'kelas';
if (isset($productType)) {
    if ($productType === 'tools') $productLabel = 'tools';
    elseif ($productType === 'layanan') $productLabel = 'layanan';
}

// Placeholder images for layanan
$layananPlaceholders = [
    'Advokasi' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=400&h=225&fit=crop',
    'Expert Advisor' => 'https://images.unsplash.com/photo-1642790106117-e829e14a795f?w=400&h=225&fit=crop',
    'Almai Ultimate' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=400&h=225&fit=crop',
];
$thumbnail = $kelas['thumbnail'] ?? ($layananPlaceholders[$kelas['category'] ?? ''] ?? $layananPlaceholders['Advokasi']);
?>
<!-- Checkout -->
<section class="pt-24 md:pt-32 pb-8 md:pb-16 min-h-screen">
    <div class="container mx-auto px-4 md:px-6 max-w-4xl">
        <h1 class="text-2xl md:text-4xl font-bold mb-1 md:mb-2 text-center">Checkout</h1>
        <p class="text-gray-400 text-center mb-6 md:mb-8 text-sm md:text-base">Selesaikan pembayaran untuk mengakses <?= $productLabel ?></p>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-xl mb-6">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('checkout/process') ?>" method="POST" id="checkoutForm">
            <?= csrf_field() ?>
            <div class="grid md:grid-cols-2 gap-4 md:gap-8">
                <!-- Order Summary -->
                <div class="bg-[#111] rounded-2xl border border-white/10 p-4 md:p-6">
                    <h2 class="text-lg md:text-xl font-bold mb-4 md:mb-6">Ringkasan Pesanan</h2>

                    <!-- Product Info -->
                    <div class="flex gap-3 md:gap-4 mb-4 md:mb-6 pb-4 md:pb-6 border-b border-white/10">
                        <img src="<?= esc($thumbnail) ?>" alt="<?= esc($kelas['title']) ?>" class="w-20 h-20 md:w-24 md:h-24 object-cover rounded-xl flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold mb-1 text-sm md:text-base line-clamp-2"><?= esc($kelas['title']) ?></h3>
                            <p class="text-gray-400 text-xs md:text-sm mb-2">oleh <?= esc($kelas['wpa_name']) ?></p>
                            <div class="flex flex-wrap gap-2">
                                <?php if (isset($productType) && $productType === 'tools'): ?>
                                    <span class="px-2 py-1 bg-purple-500/20 text-purple-400 text-xs rounded-full"><?= esc($kelas['category'] ?? 'Tools') ?></span>
                                <?php elseif (isset($productType) && $productType === 'layanan'): ?>
                                    <span class="px-2 py-1 bg-accent/20 text-accent text-xs rounded-full"><?= esc($kelas['category'] ?? '') ?></span>
                                    <?php if (!empty($kelas['subcategory'])): ?>
                                        <span class="px-2 py-1 bg-blue-500/20 text-blue-400 text-xs rounded-full"><?= esc($kelas['subcategory']) ?></span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="px-2 py-1 bg-accent/20 text-accent text-xs rounded-full"><?= esc($kelas['level'] ?? '') ?></span>
                                    <span class="px-2 py-1 bg-blue-500/20 text-blue-400 text-xs rounded-full"><?= esc($kelas['mode'] ?? '') ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <?php if ($showAdvocacyBundle ?? false): ?>
                        <!-- Bundled Advocacy Item Info -->
                        <div class="flex gap-3 md:gap-4 mb-4 md:mb-6 pb-4 md:pb-6 border-b border-white/10 bg-accent/5 p-3 rounded-xl border-dashed border-accent/30">
                            <div class="w-16 h-16 md:w-20 md:h-20 bg-white rounded-xl flex items-center justify-center flex-shrink-0 border border-accent/20 overflow-hidden">
                                <img src="<?= base_url('images/adv.png') ?>" alt="Advokasi Membership" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="font-bold text-sm md:text-base">Membership Advokasi</h3>
                                    <span class="px-1.5 py-0.5 bg-accent text-black text-[10px] font-black rounded uppercase">Included</span>
                                </div>
                                <p class="text-gray-400 text-xs mb-2">Akses Premium & Pendampingan</p>
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-2 py-1 bg-accent/20 text-accent text-[10px] rounded-full">Legal</span>
                                    <span class="px-2 py-1 bg-blue-500/20 text-blue-400 text-[10px] rounded-full">Membership</span>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- User Info -->
                    <div class="mb-4 md:mb-6 pb-4 md:pb-6 border-b border-white/10">
                        <h3 class="text-xs md:text-sm font-medium text-gray-400 mb-2 md:mb-3">Informasi Pembeli</h3>
                        <?php if (session()->get('isLoggedIn')): ?>
                            <div class="bg-black/50 rounded-xl p-3 md:p-4 space-y-2">
                                <div class="flex items-center gap-2 md:gap-3">
                                    <i class="fas fa-user w-4 text-accent text-sm"></i>
                                    <span class="text-sm md:text-base truncate"><?= esc($user['name']) ?></span>
                                </div>
                                <div class="flex items-center gap-2 md:gap-3">
                                    <i class="fas fa-envelope w-4 text-accent text-sm"></i>
                                    <span class="text-sm md:text-base truncate"><?= esc($user['email']) ?></span>
                                </div>
                                <div class="flex items-center gap-2 md:gap-3">
                                    <i class="fas fa-phone w-4 text-accent text-sm"></i>
                                    <span class="text-sm md:text-base"><?= esc($user['phone'] ?? '-') ?></span>
                                </div>
                            </div>

                            <!-- OTP Verification Section for Logged-in Users -->
                            <!-- HIDDEN: OTP verification is not required for logged-in users -->
                            <div id="otpVerificationSection" class="hidden mt-6 pt-6 border-t border-white/10">
                                <h3 class="text-sm font-bold text-white mb-4 flex items-center gap-2">
                                    <i class="fas fa-shield-alt text-accent"></i> Verifikasi OTP
                                </h3>
                                <p class="text-xs text-gray-400 mb-4">Untuk keamanan transaksi, silakan verifikasi WhatsApp dan Email Anda.</p>

                                <div class="space-y-3">
                                    <!-- WhatsApp Row -->
                                    <div class="bg-black/50 rounded-xl p-3 border border-white/10">
                                        <div class="flex items-center gap-3 flex-wrap">
                                            <div class="w-8 h-8 bg-green-500/20 rounded-full flex items-center justify-center flex-shrink-0">
                                                <i class="fab fa-whatsapp text-green-500 text-sm"></i>
                                            </div>
                                            <div class="flex-1 min-w-[100px]">
                                                <p class="text-xs font-bold text-white">WhatsApp</p>
                                                <p class="text-[10px] text-green-400"><?= esc($user['phone'] ?? '-') ?></p>
                                            </div>

                                            <!-- OTP Input Section (inline) -->
                                            <div id="waOtpInputSection" class="hidden flex-col gap-2 mt-2">
                                                <a href="#" id="verifyWaOtpBtn" target="_blank" class="px-4 py-2 bg-green-500 text-black text-xs font-bold rounded hover:bg-green-400 transition text-center flex items-center justify-center">
                                                    <i class="fab fa-whatsapp mr-2"></i> Kirim Pesan
                                                </a>
                                                <div class="text-center">
                                                    <div class="flex items-center justify-center gap-2 text-[10px] text-accent">
                                                        <i class="fas fa-spinner fa-spin"></i> Menunggu...
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Verified Badge -->
                                            <div id="waVerifiedBadge" class="hidden">
                                                <span class="bg-green-500/20 text-green-500 text-[10px] font-bold px-2 py-1 rounded-full">
                                                    <i class="fas fa-check mr-1"></i>Terverifikasi
                                                </span>
                                            </div>

                                            <!-- Send OTP Button -->
                                            <button type="button" onclick="sendSingleOtp('whatsapp')" id="sendWaOtpBtn" class="px-3 py-1.5 bg-green-500/20 text-green-500 text-[10px] font-bold rounded hover:bg-green-500/30 transition whitespace-nowrap">
                                                Kirim OTP
                                            </button>
                                        </div>
                                        <p id="waError" class="text-red-500 text-[10px] mt-1 ml-11"></p>
                                    </div>

                                    <!-- Email Row -->
                                    <div class="bg-black/50 rounded-xl p-3 border border-white/10">
                                        <div class="flex items-center gap-3 flex-wrap">
                                            <div class="w-8 h-8 bg-blue-500/20 rounded-full flex items-center justify-center flex-shrink-0">
                                                <i class="fas fa-envelope text-blue-500 text-sm"></i>
                                            </div>
                                            <div class="flex-1 min-w-[100px]">
                                                <p class="text-xs font-bold text-white">Email</p>
                                                <p class="text-[10px] text-blue-400"><?= esc($user['email']) ?></p>
                                            </div>

                                            <!-- OTP Input Section (inline) -->
                                            <div id="emailOtpInputSection" class="hidden flex items-center gap-2">
                                                <div class="flex gap-1">
                                                    <input type="text" maxlength="1" class="otp-email-input w-7 h-8 bg-[#1a1a1a] border border-white/20 rounded text-center text-xs font-bold focus:border-blue-500 focus:outline-none" data-index="0">
                                                    <input type="text" maxlength="1" class="otp-email-input w-7 h-8 bg-[#1a1a1a] border border-white/20 rounded text-center text-xs font-bold focus:border-blue-500 focus:outline-none" data-index="1">
                                                    <input type="text" maxlength="1" class="otp-email-input w-7 h-8 bg-[#1a1a1a] border border-white/20 rounded text-center text-xs font-bold focus:border-blue-500 focus:outline-none" data-index="2">
                                                    <input type="text" maxlength="1" class="otp-email-input w-7 h-8 bg-[#1a1a1a] border border-white/20 rounded text-center text-xs font-bold focus:border-blue-500 focus:outline-none" data-index="3">
                                                    <input type="text" maxlength="1" class="otp-email-input w-7 h-8 bg-[#1a1a1a] border border-white/20 rounded text-center text-xs font-bold focus:border-blue-500 focus:outline-none" data-index="4">
                                                    <input type="text" maxlength="1" class="otp-email-input w-7 h-8 bg-[#1a1a1a] border border-white/20 rounded text-center text-xs font-bold focus:border-blue-500 focus:outline-none" data-index="5">
                                                </div>
                                                <button type="button" onclick="verifySingleOtp('email')" id="verifyEmailOtpBtn" class="px-3 py-1.5 bg-blue-500 text-white text-[10px] font-bold rounded hover:bg-blue-400 transition whitespace-nowrap">
                                                    Verifikasi
                                                </button>
                                            </div>

                                            <!-- Verified Badge -->
                                            <div id="emailVerifiedBadge" class="hidden">
                                                <span class="bg-blue-500/20 text-blue-500 text-[10px] font-bold px-2 py-1 rounded-full">
                                                    <i class="fas fa-check mr-1"></i>Terverifikasi
                                                </span>
                                            </div>

                                            <!-- Send OTP Button -->
                                            <button type="button" onclick="sendSingleOtp('email')" id="sendEmailOtpBtn" class="px-3 py-1.5 bg-blue-500/20 text-blue-500 text-[10px] font-bold rounded hover:bg-blue-500/30 transition whitespace-nowrap">
                                                Kirim OTP
                                            </button>
                                        </div>
                                        <p id="emailError" class="text-red-500 text-[10px] mt-1 ml-11"></p>
                                    </div>
                                </div>

                                <!-- All Verified Message -->
                                <div id="allVerifiedMessage" class="hidden mt-4 bg-accent/10 border border-accent/30 rounded-xl p-4 text-center">
                                    <i class="fas fa-check-circle text-accent text-2xl mb-2"></i>
                                    <p class="text-accent font-bold text-sm">Verifikasi Berhasil!</p>
                                    <p class="text-gray-400 text-xs">Silakan lanjutkan ke pembayaran.</p>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="space-y-4">
                                <div class="bg-blue-500/10 border border-blue-500/30 rounded-xl p-4 mb-4">
                                    <p class="text-xs text-blue-400">
                                        <i class="fas fa-info-circle mr-1"></i> Akun akan dibuat otomatis setelah pembayaran.
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1 ml-1">Nama Lengkap</label>
                                    <input type="text" name="guest_name" required placeholder="Masukkan nama sesuai KTP"
                                        class="w-full bg-black/50 border border-white/10 rounded-xl px-3 md:px-4 py-2.5 md:py-3 text-sm md:text-base focus:border-accent outline-none transition">
                                </div>

                                <!-- Email (Info Only) -->
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1 ml-1">Email (Pastikan aktif)</label>
                                    <input type="email" name="guest_email" id="guestEmailInput" required placeholder="alamat@email.com"
                                        class="w-full bg-black/50 border border-white/10 rounded-xl px-3 md:px-4 py-2.5 md:py-3 text-sm md:text-base focus:border-accent outline-none transition">
                                </div>

                                <!-- Password -->
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1 ml-1">Buat Password</label>
                                    <input type="password" name="password" required placeholder="Minimal 6 karakter" minlength="6"
                                        class="w-full bg-black/50 border border-white/10 rounded-xl px-3 md:px-4 py-2.5 md:py-3 text-sm md:text-base focus:border-accent outline-none transition">
                                </div>

                                <!-- WhatsApp with OTP -->
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1 ml-1">WhatsApp (62xxx)</label>
                                    <div class="flex gap-2">
                                        <input type="tel" name="guest_phone" id="guestPhoneInput" required placeholder="628123456789"
                                            class="flex-1 bg-black/50 border border-white/10 rounded-xl px-3 md:px-4 py-2.5 md:py-3 text-sm md:text-base focus:border-accent outline-none transition">
                                        <button type="button" onclick="sendSingleOtp('whatsapp')" id="guestSendWaOtpBtn" class="px-4 py-2.5 bg-green-500/20 text-green-500 text-xs font-bold rounded-xl hover:bg-green-500/30 transition whitespace-nowrap">
                                            Kirim OTP
                                        </button>
                                        <div id="guestWaVerifiedBadge" class="hidden flex items-center">
                                            <span class="bg-green-500/20 text-green-500 text-xs font-bold px-3 py-2.5 rounded-xl">
                                                <i class="fas fa-check"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <!-- OTP Verification for WhatsApp (appears after send) -->
                                    <div id="guestWaOtpInputSection" class="hidden mt-2">
                                        <a href="#" id="guestVerifyWaOtpBtn" target="_blank" class="inline-flex items-center justify-center w-full px-4 py-3 bg-green-500 text-black text-sm font-bold rounded-xl hover:bg-green-400 transition mb-2">
                                            <i class="fab fa-whatsapp text-lg mr-2"></i>
                                            Buka WhatsApp & Kirim Pesan
                                        </a>
                                        <div class="text-center">
                                            <p class="text-[10px] text-gray-400 mb-1">Status Verifikasi:</p>
                                            <div class="flex items-center justify-center gap-2 text-xs text-accent">
                                                <i class="fas fa-spinner fa-spin"></i> Menunggu pesan masuk...
                                            </div>
                                        </div>
                                    </div>
                                    <p id="guestWaError" class="text-red-500 text-[10px] mt-1"></p>
                                </div>

                                <!-- All Verified Message for Guest -->
                                <div id="guestAllVerifiedMessage" class="hidden bg-accent/10 border border-accent/30 rounded-xl p-4 text-center">
                                    <i class="fas fa-check-circle text-accent text-2xl mb-2"></i>
                                    <p class="text-accent font-bold text-sm">Verifikasi Berhasil!</p>
                                    <p class="text-gray-400 text-xs">Silakan lanjutkan ke pembayaran.</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if (!session()->get('isLoggedIn')): ?>
                        <?php if ($referrer): ?>
                            <div class="mb-6 pb-6 border-b border-white/10">
                                <label class="block text-sm font-medium text-gray-400 mb-2">Kode Referral Terpasang</label>
                                <div class="bg-accent/10 border border-accent/30 rounded-xl p-3 flex items-center gap-3">
                                    <div class="w-10 h-10 bg-accent/20 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="fas <?= (isset($referralReadOnly) && $referralReadOnly) ? 'fa-lock' : 'fa-check' ?> text-accent"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-xs text-gray-400"><?= (isset($referralReadOnly) && $referralReadOnly) ? 'Referral Wajib:' : 'Direferensikan oleh:' ?></p>
                                        <p class="font-bold text-sm text-white"><?= esc($referrer['name']) ?></p>
                                    </div>
                                    <input type="text" value="<?= esc($refCode) ?>" readonly
                                        class="w-24 bg-black/50 border border-white/10 rounded-lg px-3 py-2 text-center text-accent font-mono font-bold text-sm focus:outline-none <?= (isset($referralReadOnly) && $referralReadOnly) ? 'cursor-not-allowed text-gray-400' : '' ?>">
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="mb-6 pb-6 border-b border-white/10">
                                <label class="block text-sm font-medium text-gray-400 mb-2">Kode Referral (Opsional)</label>
                                <input type="text" name="referral_code" placeholder="Masukkan kode referral"
                                    class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none uppercase font-mono text-sm">
                                <p class="text-xs text-gray-500 mt-2">Masukan kode referral jika ada.</p>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- Price Breakdown -->
                    <div class="space-y-2 md:space-y-3 mb-4 md:mb-6">
                        <?php if ($showAdvocacyBundle ?? false): ?>
                            <div class="flex justify-between items-center text-sm mb-2">
                                <span class="text-gray-400"><?= esc($kelas['title']) ?></span>
                                <span>Rp <?= number_format($mainItemPrice, 0, ',', '.') ?></span>
                            </div>
                            <div class="flex justify-between items-center text-sm mb-2 text-accent">
                                <div class="flex items-center">
                                    <i class="fas fa-shield-alt mr-2 text-[10px]"></i>
                                    <span>Advokasi Membership</span>
                                    <span class="ml-2 text-[10px] bg-accent/20 px-1.5 py-0.5 rounded text-accent font-bold uppercase">Termasuk</span>
                                </div>
                                <span>Rp <?= number_format($bundlePrice, 0, ',', '.') ?></span>
                            </div>
                        <?php else: ?>
                            <div class="flex justify-between text-gray-400 text-sm md:text-base">
                                <span>Harga <?= ucfirst($productLabel) ?></span>
                                <span>Rp <?= number_format($kelas['original_price'] ?? $kelas['price'], 0, ',', '.') ?></span>
                            </div>
                            <?php if (isset($kelas['original_price']) && $kelas['original_price'] > $kelas['price']): ?>
                                <div class="flex justify-between text-accent text-sm md:text-base">
                                    <span>Diskon</span>
                                    <span>-Rp <?= number_format($kelas['original_price'] - $kelas['price'], 0, ',', '.') ?></span>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <div id="voucherDiscountRow" class="hidden flex justify-between text-accent text-sm md:text-base">
                            <span>Diskon Voucher</span>
                            <span id="voucherDiscountAmount">-Rp 0</span>
                        </div>
                    </div>

                    <div class="flex flex-col border-t border-white/10 pt-3 md:pt-4">
                        <div class="flex justify-between text-lg md:text-xl font-bold">
                            <span>Total Bayar</span>
                            <span id="totalPrice" class="text-accent">Rp <?= number_format($kelas['price'], 0, ',', '.') ?></span>
                        </div>
                        <?php if (($kelas['poin_price'] ?? 0) > 0): ?>
                            <div class="flex justify-between text-sm text-green-400 font-medium mt-1">
                                <span>Atau bayar dengan Poin</span>
                                <span><i class="fas fa-coins mr-1"></i><?= number_format($kelas['poin_price'], 0, ',', '.') ?> Almai Poin</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Payment Form -->
                <div class="bg-[#111] rounded-2xl border border-white/10 p-4 md:p-6">
                    <h2 class="text-lg md:text-xl font-bold mb-4 md:mb-6">Metode Pembayaran</h2>

                    <div>
                        <input type="hidden" name="product_type" value="<?= $productType ?? 'kelas' ?>">
                        <?php if (isset($productType) && $productType === 'tools'): ?>
                            <input type="hidden" name="tool_id" value="<?= $kelas['id'] ?>">
                        <?php elseif (isset($productType) && $productType === 'layanan'): ?>
                            <input type="hidden" name="layanan_id" value="<?= $kelas['id'] ?>">
                            <input type="hidden" name="layanan_type" value="<?= $kelas['type'] ?? '' ?>">
                        <?php else: ?>
                            <input type="hidden" name="kelas_id" value="<?= $kelas['id'] ?>">
                        <?php endif; ?>
                        <input type="hidden" name="poin_amount" id="poinAmountInput" value="0">
                        <input type="hidden" name="package_id" value="<?= isset($kelas['package_id']) ? $kelas['package_id'] : '' ?>">
                        <input type="hidden" name="include_advocacy" value="<?= ($showAdvocacyBundle ?? false) ? '1' : '0' ?>">

                        <div class="space-y-2 md:space-y-3 mb-4 md:mb-6">
                            <!-- Almai Poin (Full Payment) -->
                            <label class="payment-option flex items-center gap-2 md:gap-4 bg-black border-2 border-white/20 rounded-xl px-3 md:px-4 py-3 md:py-4 cursor-pointer hover:border-yellow-500 transition <?= !$canPayWithPoin ? 'opacity-50 cursor-not-allowed' : '' ?>">
                                <input type="radio" name="payment_method" value="poin" class="accent-yellow-500 w-4 md:w-5 h-4 md:h-5 flex-shrink-0" <?= !$canPayWithPoin ? 'disabled' : '' ?> <?= $price == 0 ? 'checked' : '' ?>>
                                <div class="w-10 h-10 md:w-12 md:h-12 bg-yellow-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-coins text-yellow-500 text-lg md:text-xl"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-sm md:text-base">Bayar pakai Saldo Poin</p>
                                    <p class="text-[10px] md:text-xs text-gray-400 truncate">
                                        Butuh <span class="text-white font-bold"><?= number_format($poinNeeded) ?></span> Poin
                                        • Saldo: <span class="<?= $canPayWithPoin ? 'text-yellow-400' : 'text-red-400' ?> font-bold"><?= number_format($poinBalance) ?></span>
                                    </p>
                                </div>
                                <?php if ($canPayWithPoin): ?>
                                    <span class="px-2 md:px-3 py-1 bg-yellow-500/20 text-yellow-400 text-[10px] md:text-xs rounded-full flex-shrink-0">Cukup!</span>
                                <?php else: ?>
                                    <span class="px-2 md:px-3 py-1 bg-red-500/20 text-red-400 text-[10px] md:text-xs rounded-full flex-shrink-0">Kurang</span>
                                <?php endif; ?>
                            </label>

                            <!-- Specific Payment Methods (Xendit) -->
                            <?php
                            $allVaMethods = [
                                ['code' => 'BNI', 'name' => 'BNI Virtual Account', 'icon' => base_url('images/bank/bni.png')],
                                ['code' => 'BRI', 'name' => 'BRI Virtual Account', 'icon' => base_url('images/bank/bri.png')],
                                ['code' => 'BSI', 'name' => 'BSI Virtual Account', 'icon' => base_url('images/bank/bsi.png')],
                                ['code' => 'MANDIRI', 'name' => 'Mandiri Virtual Account', 'icon' => base_url('images/bank/mandiri.webp')],
                            ];

                            $paymentMethods = [];

                            // QRIS for all (greater than 0, up to 10jt)
                            if ($price > 0 && $price <= 10000000) {
                                $paymentMethods[] = ['code' => 'QRIS', 'name' => 'QRIS', 'icon' => base_url('images/bank/qris.webp')];
                            }

                            // VA only for 1jt+
                            if ($price >= 1000000) {
                                $paymentMethods = array_merge($paymentMethods, $allVaMethods);
                            }
                            ?>

                            <?php foreach ($paymentMethods as $index => $method): ?>
                                <label class="payment-option flex items-center gap-2 md:gap-4 bg-black border-2 border-white/20 rounded-xl px-3 md:px-4 py-3 md:py-4 cursor-pointer hover:border-accent transition">
                                    <input type="radio" name="payment_method" value="<?= $method['code'] ?>" class="accent-accent w-4 md:w-5 h-4 md:h-5 flex-shrink-0" <?= $index === 0 ? 'checked' : '' ?>>
                                    <div class="w-10 h-10 md:w-12 md:h-12 bg-white rounded-xl flex items-center justify-center p-1.5 md:p-2 overflow-hidden flex-shrink-0">
                                        <img src="<?= $method['icon'] ?>" alt="<?= $method['code'] ?>" class="w-full h-full object-contain">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-sm md:text-base"><?= $method['name'] ?></p>
                                        <p class="text-[10px] md:text-xs text-gray-400">Verifikasi Otomatis</p>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        </div>



                        <!-- Voucher Code -->
                        <div class="mb-4 md:mb-6">
                            <label class="block text-xs md:text-sm font-medium text-gray-400 mb-2">Kode Voucher</label>
                            <div class="flex gap-2">
                                <input type="text" name="voucher_code" id="voucherCode" placeholder="Masukkan kode voucher" value="<?= esc($voucher ?? '') ?>" <?= ($voucher ?? '') ? 'readonly' : '' ?>
                                    class="flex-1 px-3 md:px-4 py-2.5 md:py-3 bg-black border border-white/20 rounded-xl text-sm md:text-base focus:border-accent focus:outline-none uppercase <?= ($voucher ?? '') ? 'cursor-not-allowed opacity-75 text-gray-400' : '' ?>">
                                <button type="button" onclick="applyVoucher()" id="applyVoucherBtn" class="px-3 md:px-4 py-2.5 md:py-3 bg-white/10 rounded-xl hover:bg-white/20 transition font-medium text-sm md:text-base">
                                    Terapkan
                                </button>
                            </div>
                            <div id="voucherMessage" class="hidden mt-2 text-xs md:text-sm"></div>
                            <input type="hidden" name="voucher_id" id="voucherIdInput" value="">
                            <input type="hidden" name="voucher_discount" id="voucherDiscountInput" value="0">

                            <!-- Hidden Product Info -->
                            <input type="hidden" name="product_type" value="<?= esc($productType ?? 'kelas') ?>">
                            <?php if (isset($productType) && $productType === 'layanan'): ?>
                                <input type="hidden" name="layanan_id" value="<?= esc($kelas['id']) ?>">
                                <input type="hidden" name="layanan_type" value="<?= esc($kelas['type'] ?? '') ?>">
                            <?php elseif (isset($productType) && $productType === 'tools'): ?>
                                <input type="hidden" name="tool_id" value="<?= esc($kelas['id']) ?>">
                            <?php else: ?>
                                <input type="hidden" name="kelas_id" value="<?= esc($kelas['id']) ?>">
                            <?php endif; ?>

                            <?php $request = \Config\Services::request(); ?>
                            <?php if ($request->getGet('package')): ?>
                                <input type="hidden" name="package_id" value="<?= esc($request->getGet('package')) ?>">
                            <?php endif; ?>

                        </div>

                        <!-- Checkbox Persetujuan -->
                        <div class="mb-4 p-4 bg-red-500/10 border border-red-500/30 rounded-xl space-y-3">
                            <p class="text-white font-medium text-sm">Saya telah memahami dan menyetujui:</p>

                            <?php if (isset($legalProfil) && $legalProfil): ?>
                                <!-- Checkbox 1: Profil Perusahaan -->
                                <div class="flex items-start gap-3">
                                    <input type="checkbox" id="agreementProfil" required disabled class="mt-1 accent-accent w-5 h-5 flex-shrink-0 cursor-not-allowed opacity-50">
                                    <div class="flex-1">
                                        <button type="button" onclick="openProfilModal()" class="text-accent hover:underline font-medium text-sm text-left">
                                            <i class="fas fa-building mr-1"></i> Profil Perusahaan
                                        </button>
                                        <span class="text-xs text-gray-500 block mt-1">Klik untuk membaca dan menyetujui</span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Checkbox 2: Perjanjian Pemberian Jasa -->
                            <div class="flex items-start gap-3">
                                <input type="checkbox" id="agreementPerjanjian" required disabled class="mt-1 accent-accent w-5 h-5 flex-shrink-0 cursor-not-allowed opacity-50">
                                <div class="flex-1">
                                    <button type="button" onclick="openPerjanjianModal()" class="text-accent hover:underline font-medium text-sm text-left">
                                        <i class="fas fa-file-contract mr-1"></i> Perjanjian Pemberian Jasa
                                    </button>
                                    <span class="text-xs text-gray-500 block mt-1">Klik untuk membaca dan menyetujui</span>
                                </div>
                            </div>

                            <!-- Checkbox 3: Pemberitahuan Adanya Risiko -->
                            <div class="flex items-start gap-3">
                                <input type="checkbox" id="agreementRisiko" required disabled class="mt-1 accent-accent w-5 h-5 flex-shrink-0 cursor-not-allowed opacity-50">
                                <div class="flex-1">
                                    <button type="button" onclick="openRisikoModal()" class="text-accent hover:underline font-medium text-sm text-left">
                                        <i class="fas fa-exclamation-triangle mr-1"></i> Pemberitahuan Adanya Risiko
                                    </button>
                                    <span class="text-xs text-gray-500 block mt-1">Klik untuk membaca dan menyetujui</span>
                                </div>
                            </div>

                            <?php if (isset($showAdvocacyBundle) && $showAdvocacyBundle && isset($legalAdvokasi) && $legalAdvokasi): ?>
                                <!-- Checkbox 4: Ketentuan Program Advokasi -->
                                <div class="flex items-start gap-3">
                                    <input type="checkbox" id="agreementAdvokasi" required disabled class="mt-1 accent-accent w-5 h-5 flex-shrink-0 cursor-not-allowed opacity-50">
                                    <div class="flex-1">
                                        <button type="button" onclick="openAdvokasiModal()" class="text-accent hover:underline font-medium text-sm text-left">
                                            <i class="fas fa-shield-alt mr-1"></i> Ketentuan Program Advokasi
                                        </button>
                                        <span class="text-xs text-gray-500 block mt-1">Klik untuk membaca dan menyetujui</span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Tombol Setujui Semua di bawah Pemberitahuan Risiko -->
                            <div class="flex justify-end pt-2">
                                <button type="button" onclick="toggleAgreeAll()" class="text-xs bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg transition" id="agreeAllBtn">
                                    Setujui Semua
                                </button>
                            </div>
                        </div>

                        <div class="space-y-2 md:space-y-3">
                            <?php if (!session()->get('isLoggedIn')): ?>
                                <button type="submit" id="payButton" disabled class="w-full py-3 md:py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition shadow-[0_0_20px_rgba(51,232,24,0.3)] text-sm md:text-base disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i class="fas fa-lock mr-2"></i> Bayar Sekarang
                                </button>
                                <p id="payButtonHint" class="text-center text-xs text-yellow-500">
                                    <i class="fas fa-info-circle mr-1"></i>Selesaikan verifikasi OTP terlebih dahulu
                                </p>
                            <?php else: ?>
                                <button type="submit" id="payButton" disabled class="w-full py-3 md:py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition shadow-[0_0_20px_rgba(51,232,24,0.3)] text-sm md:text-base disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i class="fas fa-lock mr-2"></i> Bayar Sekarang
                                </button>
                                <p id="payButtonHint" class="text-center text-xs text-yellow-500">
                                    <i class="fas fa-info-circle mr-1"></i>Selesaikan verifikasi OTP terlebih dahulu
                                </p>
                            <?php endif; ?>

                            <button type="button" onclick="askFirst()" class="w-full py-3 md:py-4 border border-white/20 text-white font-bold rounded-xl hover:border-white hover:bg-white/5 transition text-sm md:text-base">
                                <i class="fab fa-whatsapp mr-2"></i> Tanya Dulu
                            </button>
                        </div>

                        <p class="text-center text-gray-500 text-[10px] md:text-xs mt-3 md:mt-4">
                            <i class="fas fa-shield-alt mr-1"></i> Pembayaran aman & terenkripsi
                        </p>
                    </div>
                </div>
        </form>
    </div>
</section>

<!-- Modal Tanya Dulu -->
<div id="tanyaModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
    <div class="bg-[#111] border border-white/10 rounded-3xl p-8 max-w-sm w-full transform scale-95 opacity-0 transition-all duration-300 shadow-[0_0_50px_rgba(51,232,24,0.1)]" id="tanyaModalContent">
        <div class="text-center">
            <div class="w-20 h-20 bg-accent/20 rounded-full flex items-center justify-center mx-auto mb-6 relative">
                <i class="fab fa-whatsapp text-accent text-4xl"></i>
                <div class="absolute inset-0 bg-accent rounded-full animate-ping opacity-20"></div>
            </div>

            <h3 class="text-2xl font-bold mb-4">Halo Calon Trader!</h3>
            <p class="text-gray-400 mb-8 leading-relaxed">
                Silakan isi <strong>Info Pembeli</strong> di atas terlebih dahulu agar CS bisa membantu menjawab pertanyaan Anda dengan lebih maksimal :)
            </p>

            <button onclick="closeTanyaModal()" class="w-full py-4 bg-accent text-black font-bold rounded-2xl hover:bg-white transition shadow-lg shadow-accent/20">
                Siap, Isi Sekarang!
            </button>
        </div>
    </div>
</div>


<!-- Modal Perjanjian Pemberian Jasa -->
<div id="perjanjianModal" class="fixed inset-0 z-[120] hidden items-center justify-center bg-black/90 backdrop-blur-md p-4">
    <div class="bg-[#111] border border-white/10 rounded-3xl max-w-3xl w-full max-h-[90vh] overflow-hidden flex flex-col">
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-white/10">
            <h3 class="text-xl font-bold text-white"><?= isset($legalPerjanjian) ? esc($legalPerjanjian['title']) : 'Perjanjian Pemberian Jasa' ?></h3>
            <button onclick="closePerjanjianModal()" class="text-gray-400 hover:text-white transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Content -->
        <div class="flex-1 overflow-y-auto p-6 space-y-6 text-sm">
            <div class="bg-accent/10 border border-accent/30 rounded-xl p-4">
                <p class="text-accent text-xs">
                    <i class="fas fa-info-circle mr-2"></i>
                    Silakan baca dengan seksama sebelum menyetujui
                </p>
            </div>

            <?php if ($legalPerjanjian): ?>
                <!-- Dynamic Content from Database -->
                <div id="perjanjianContent" class="prose prose-invert prose-sm max-w-none text-gray-300 legal-dynamic-content">
                    <?= $legalPerjanjian['content'] ?>
                </div>
            <?php else: ?>
                <!-- Fallback: Default Hardcoded Content -->
                <div class="space-y-4">
                    <div class="text-center mb-4">
                        <h4 class="text-white font-bold text-base mb-2">PERJANJIAN PEMBERIAN JASA PENASIHAT BERJANGKA</h4>
                        <p class="text-gray-400 text-xs mb-2">PT. ALMA INDONESIA RAYA SECARA ELEKTRONIK ONLINE</p>
                        <p class="text-accent text-xs font-mono">Nomor Perjanjian: <span id="modalNomorPerjanjian" class="font-bold">-</span></p>
                    </div>

                    <div class="bg-[#0a0a0a] rounded-xl p-4 space-y-3">
                        <h5 class="text-white font-bold text-sm mb-2">Perjanjian Antara</h5>
                        <div class="space-y-1 text-xs">
                            <p class="text-white font-bold">PT. ALMA INDONESIA RAYA</p>
                            <p class="text-gray-400">Alamat: ALMAI | Jl. Badak Agung No. 22 Kav. 3 Kelurahan Renon, Denpasar - Bali. 80226</p>
                            <p class="text-gray-400">Nomor Telepon: 0361 361 0019</p>
                            <p class="text-gray-400">Chat Support: 0851 8339 0019 / 085183231800 / 085156789700</p>
                            <p class="text-gray-400">Nomor NPWP: 53.630.590.7-903.000</p>
                            <p class="text-gray-400">Email: cs@almai.id | Website: www.almai.id</p>
                            <p class="text-gray-400 mt-3">Dalam hal ini diwakili oleh <span class="text-white font-medium"><?= isset($kelas['wpa_name']) ? esc($kelas['wpa_name']) : 'Tim Almai' ?></span> selaku Wakil Penasihat Berjangka,</p>
                            <p class="text-gray-400 italic">Selanjutnya disebut <strong class="text-white">Pihak Pertama</strong></p>
                        </div>
                    </div>

                    <div class="bg-[#0a0a0a] rounded-xl p-4 space-y-2">
                        <h5 class="text-white font-bold text-sm mb-2">Bersama</h5>
                        <div class="space-y-1 text-xs">
                            <p class="text-gray-400">Nama Lengkap: <span class="text-white font-medium" id="modalUserName"><?= session()->get('isLoggedIn') ? esc($user['name']) : '-' ?></span></p>
                            <p class="text-gray-400">Email: <span class="text-white font-medium" id="modalUserEmail"><?= session()->get('isLoggedIn') ? esc($user['email']) : '-' ?></span></p>
                            <p class="text-gray-400">Nomor Telefon: <span class="text-white font-medium" id="modalUserPhone"><?= session()->get('isLoggedIn') ? esc($user['phone'] ?? '-') : '-' ?></span></p>
                            <p class="text-gray-400 mt-2 italic">Selanjutnya disebut <strong class="text-white">Pihak Kedua (Klien)</strong></p>
                        </div>
                    </div>

                    <div class="bg-blue-500/10 border border-blue-500/30 rounded-xl p-3">
                        <p class="text-gray-400 text-xs">
                            Pihak Pertama dan Pihak Kedua selanjutnya secara bersama–sama akan disebut sebagai <strong class="text-white">"Para Pihak"</strong> dan masing-masing akan disebut sebagai <strong class="text-white">"Pihak"</strong>.
                        </p>
                    </div>

                    <div class="bg-[#0a0a0a] rounded-xl p-4 space-y-2">
                        <h5 class="text-white font-bold text-sm mb-2">Layanan</h5>
                        <div class="space-y-1 text-xs">
                            <p class="text-gray-400">Kegiatan: <span class="text-white font-medium"><?= esc($kelas['title']) ?></span></p>
                            <p class="text-gray-400">Harga Layanan: <span class="text-white font-medium">Rp <?= number_format($kelas['original_price'] ?? $kelas['price'], 0, ',', '.') ?></span></p>
                            <p class="text-gray-400">Harga Bayar: <span class="text-accent font-bold">Rp <?= number_format($kelas['price'], 0, ',', '.') ?></span></p>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-white font-bold mb-2">BAHWA:</h4>
                        <div class="space-y-2 text-gray-400">
                            <p class="text-xs">1. Pihak Pertama merupakan perusahaan Penasihat Perdagangan Berjangka yang telah mendapatkan izin Resmi dari Bappebti dengan Nomor: <strong class="text-white">02/BAPPEBTI/SI-PNB/02/2024</strong>, Penasihat Berjangka EA dengan izin Nomor: <strong class="text-white">01/BAPPEBTI/SP-PBEA/06/2024</strong>, Penasihat Investasi OJK Nomor: <strong class="text-white">S-128/PM.02/2025</strong>, dan Penasihat Berjangka EA untuk PUVA dari Bank Indonesia Nomor: <strong class="text-white">27/DPPK/Srt/B</strong>.</p>

                            <p class="text-xs">2. Pihak Kedua adalah Klien yang menggunakan atau menyatakan keinginan untuk menggunakan layanan Penasihat Berjangka.</p>

                            <p class="text-xs">3. Para Pihak menyepakati untuk mengatur syarat dan ketentuan yang meliputi hubungan diantara Para Pihak terkait dengan jasa yang akan diberikan.</p>

                            <p class="text-xs">4. Pihak Kedua telah memahami bahwa <strong class="text-white">Perdagangan Derivatif memiliki potensi keuntungan yang sangat tinggi, namun juga disertai risiko signifikan</strong>; PT. Alma Indonesia Raya bukan lembaga keuangan yang menerima pembayaran/margin jaminan untuk transaksi; layanan nasihat bersifat informasi dan rekomendasi, keputusan akhir sepenuhnya berada di tangan klien; PT. Alma Indonesia Raya tidak dapat dituntut atas kerugian yang timbul; <strong class="text-white">Pihak Pertama tidak menyediakan layanan pengembalian pembayaran atau reimburse</strong>.</p>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-white font-bold mb-2">PASAL 1 - RUANG LINGKUP JASA</h4>
                        <ul class="list-decimal list-inside text-gray-400 space-y-1 ml-4 text-xs">
                            <li>Pihak Pertama memberikan jasa penasihat berjangka berupa pelatihan, edukasi, dan penyampaian pandangan pasar.</li>
                            <li>Jasa bersifat non-eksekutorial, tidak melakukan transaksi, tidak mengelola dana, dan tidak memiliki akses terhadap akun trading Klien.</li>
                            <li>Seluruh materi dan rekomendasi merupakan pandangan profesional yang tidak menjamin hasil tertentu.</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-white font-bold mb-2">PASAL 2 - HAK DAN KEWAJIBAN PIHAK PERTAMA</h4>
                        <ul class="list-decimal list-inside text-gray-400 space-y-1 ml-4 text-xs">
                            <li>Berhak memberikan nasihat dan rekomendasi sesuai ketentuan peraturan perundang-undangan.</li>
                            <li>Berkewajiban menyampaikan informasi secara jujur, profesional, dan proporsional.</li>
                            <li>Wajib menjaga kerahasiaan data dan informasi Klien.</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-white font-bold mb-2">PASAL 3 - HAK DAN KEWAJIBAN PIHAK KEDUA</h4>
                        <ul class="list-decimal list-inside text-gray-400 space-y-1 ml-4 text-xs">
                            <li>Berhak memperoleh jasa penasihat berjangka sesuai ruang lingkup yang disepakati.</li>
                            <li>Berkewajiban memberikan data dan informasi yang benar dan lengkap.</li>
                            <li>Bertanggung jawab penuh atas setiap keputusan transaksi dan risiko kerugian.</li>
                        </ul>
                    </div>

                    <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4">
                        <h4 class="text-white font-bold mb-2">PASAL 4 - RISIKO DAN TANGGUNG JAWAB</h4>
                        <ul class="list-decimal list-inside text-gray-400 space-y-1 ml-4 text-xs">
                            <li>Klien memahami bahwa perdagangan derivatif mengandung risiko tinggi yang dapat mengakibatkan kerugian sebagian atau seluruh dana.</li>
                            <li>Pihak Pertama tidak bertanggung jawab atas kerugian finansial yang timbul akibat keputusan transaksi Klien.</li>
                            <li>Klien melepaskan Pihak Pertama dari segala tuntutan hukum yang timbul akibat penggunaan rekomendasi.</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-white font-bold mb-2">PASAL 5 - BIAYA DAN PEMBAYARAN</h4>
                        <ul class="list-decimal list-inside text-gray-400 space-y-1 ml-4 text-xs">
                            <li>Klien wajib melakukan pembayaran jasa sesuai nilai, metode, dan waktu yang ditentukan.</li>
                            <li>Pembayaran yang telah dilakukan bersifat final dan tidak dapat dikembalikan.</li>
                            <li>Biaya jasa tidak terkait dengan hasil perdagangan atau keuntungan maupun kerugian Klien.</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-white font-bold mb-2">PASAL 6 - KERAHASIAAN DAN DATA PRIBADI</h4>
                        <ul class="list-decimal list-inside text-gray-400 space-y-1 ml-4 text-xs">
                            <li>Para Pihak sepakat untuk menjaga kerahasiaan seluruh data dan informasi.</li>
                            <li>Data pribadi Klien hanya digunakan untuk kepentingan penyediaan jasa dan pemenuhan kewajiban hukum.</li>
                            <li>Kewajiban kerahasiaan tetap berlaku meskipun Perjanjian berakhir.</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-white font-bold mb-2">PASAL 7 - JANGKA WAKTU DAN PENGAKHIRAN</h4>
                        <ul class="list-decimal list-inside text-gray-400 space-y-1 ml-4 text-xs">
                            <li>Perjanjian berlaku sejak disetujui secara elektronik hingga jangka waktu layanan berakhir.</li>
                            <li>Pihak Pertama berhak mengakhiri Perjanjian apabila Klien melanggar ketentuan hukum.</li>
                            <li>Pengakhiran Perjanjian tidak menghapus kewajiban pembayaran yang telah timbul.</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-white font-bold mb-2">PASAL 8 - PENYELESAIAN SENGKETA</h4>
                        <ul class="list-decimal list-inside text-gray-400 space-y-1 ml-4 text-xs">
                            <li>Setiap perselisihan diselesaikan terlebih dahulu secara musyawarah untuk mufakat.</li>
                            <li>Apabila musyawarah tidak tercapai, diselesaikan melalui mekanisme alternatif penyelesaian sengketa.</li>
                            <li>Para Pihak sepakat memilih domisili hukum di wilayah hukum Republik Indonesia.</li>
                        </ul>
                    </div>

                    <div class="bg-accent/10 border border-accent/30 rounded-xl p-4">
                        <h4 class="text-white font-bold mb-2">PASAL 9 - PENUTUP</h4>
                        <p class="text-gray-400 text-xs">
                            Perjanjian ini dibuat dan disepakati secara elektronik serta memiliki kekuatan hukum yang sah dan mengikat.
                            Dengan menyetujui Perjanjian ini, Klien menyatakan telah membaca, memahami, dan menyetujui seluruh isi Perjanjian tanpa paksaan dari pihak mana pun.
                        </p>
                    </div>

                    <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-xl p-4">
                        <p class="text-gray-400 text-xs leading-relaxed">
                            Dengan mengisi kolom <strong class="text-white">"YA"</strong> di bawah ini, menyatakan bahwa <strong class="text-white">"Saya telah membaca, memahami, setuju terhadap semua ketentuan yang tercantum dalam PERJANJIAN PEMBERIAN JASA PENASIHAT BERJANGKA PT. ALMA INDONESIA RAYA"</strong>, serta menerima.
                        </p>
                    </div>

                    <div class="bg-[#0a0a0a] rounded-xl p-4">
                        <p class="text-gray-400 text-xs mb-3">
                            Dengan menyetujui perjanjian ini, <strong class="text-white">"Klien"</strong> dan <strong class="text-white">"Penasihat Berjangka"</strong> menyatakan bahwa mereka telah membaca, memahami, dan setuju untuk terikat oleh ketentuan-ketentuan yang diatur dalam perjanjian ini. Perjanjian ini berlaku sejak tanggal ditandatangani oleh kedua belah pihak.
                        </p>

                        <div class="border-t border-white/10 pt-3 space-y-2">
                            <p class="text-gray-400 text-xs">Nomor Kontrak: <span class="text-accent font-mono font-bold" id="modalNomorKontrak">-</span></p>
                            <p class="text-gray-400 text-xs">Tanggal: <span class="text-white font-medium" id="modalTanggal"><?= date('d F Y') ?></span></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-[#0a0a0a] rounded-xl p-4 text-center">
                            <p class="text-gray-400 text-xs mb-2">Wakil Penasihat Berjangka</p>
                            <p class="text-white font-bold text-sm"><?= isset($kelas['wpa_name']) ? esc($kelas['wpa_name']) : 'Tim Almai' ?></p>
                        </div>
                        <div class="bg-[#0a0a0a] rounded-xl p-4 text-center">
                            <p class="text-gray-400 text-xs mb-2">Klien</p>
                            <p class="text-white font-bold text-sm" id="modalKlienSignature"><?= session()->get('isLoggedIn') ? esc($user['name']) : '-' ?></p>
                        </div>
                    </div>

                    <div class="bg-[#0a0a0a] rounded-xl p-4 text-center">
                        <p class="text-gray-400 text-xs mb-2">Mengetahui,</p>
                        <p class="text-white font-bold">Rendy M Prayogie</p>
                        <p class="text-gray-400 text-xs">Direktur Utama</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Footer -->
        <div class="p-6 border-t border-white/10 flex gap-3">
            <button onclick="closePerjanjianModal()" class="flex-1 py-3 border border-white/20 text-white font-bold rounded-xl hover:bg-white/5 transition">
                Tutup
            </button>
            <button onclick="acceptPerjanjian()" class="flex-1 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                Saya Setuju
            </button>
        </div>
    </div>
</div>

<!-- Modal Dokumen Pemberitahuan Risiko -->
<div id="risikoModal" class="fixed inset-0 z-[120] hidden items-center justify-center bg-black/90 backdrop-blur-md p-4">
    <div class="bg-[#111] border border-white/10 rounded-3xl max-w-3xl w-full max-h-[90vh] overflow-hidden flex flex-col">
        <!-- Header -->
        <div class="flex items-center justify-between p-6 border-b border-white/10">
            <h3 class="text-xl font-bold text-white"><?= isset($legalRisiko) ? esc($legalRisiko['title']) : 'Dokumen Pemberitahuan Adanya Risiko' ?></h3>
            <button onclick="closeRisikoModal()" class="text-gray-400 hover:text-white transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Content -->
        <div class="flex-1 overflow-y-auto p-6 space-y-6 text-sm">
            <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-triangle text-red-500 text-xl mt-0.5"></i>
                    <div>
                        <h4 class="text-red-400 font-bold mb-1">PERINGATAN PENTING</h4>
                        <p class="text-gray-400 text-xs">
                            Perdagangan derivatif memiliki potensi keuntungan yang sangat tinggi, namun juga disertai risiko signifikan sehingga tidak cocok untuk semua investor; PT. Alma Indonesia Raya bukan lembaga keuangan yang menerima dana margin trading namun hanya menerima pembayaran untuk jasa nasihat; layanan nasihat dari Penasihat Berjangka atau Wakil Penasihat Berjangka serta konten yang terkandung pada platform/website www.almai.id bersifat informasi dan rekomendasi, keputusan akhir sepenuhnya berada di tangan klien; kami tidak dapat dituntut atas kerugian yang timbul dari penggunaan informasi maupun rekomendasi yang diberikan; oleh karena itu pelajari, pahami, dan lakukan transaksi kontrak derivatif sesuai dengan profil risiko Anda.
                        </p>
                    </div>
                </div>
            </div>

            <?php if ($legalRisiko): ?>
                <!-- Dynamic Content from Database -->
                <div id="risikoContent" class="prose prose-invert prose-sm max-w-none text-gray-300 legal-dynamic-content">
                    <?= $legalRisiko['content'] ?>
                </div>
            <?php else: ?>
                <!-- Fallback: Default Hardcoded Content -->
                <div class="space-y-4">
                    <div>
                        <h4 class="text-white font-bold mb-2">1. RISIKO UMUM</h4>
                        <ul class="list-disc list-inside text-gray-400 space-y-1 ml-4">
                            <li><strong class="text-white">Risiko Kerugian Modal</strong>: Dapat kehilangan sebagian atau seluruh modal</li>
                            <li><strong class="text-white">Volatilitas Pasar</strong>: Harga dapat berubah cepat dan tidak terduga</li>
                            <li><strong class="text-white">Leverage</strong>: Dapat memperbesar keuntungan maupun kerugian</li>
                            <li><strong class="text-white">Likuiditas</strong>: Tidak semua posisi dapat ditutup dengan mudah</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-white font-bold mb-2">2. RISIKO TEKNOLOGI</h4>
                        <ul class="list-disc list-inside text-gray-400 space-y-1 ml-4">
                            <li>Gangguan sistem atau koneksi dapat mempengaruhi eksekusi order</li>
                            <li>Expert Advisor (EA) dapat mengalami error</li>
                            <li>Keamanan akun adalah tanggung jawab pengguna</li>
                        </ul>
                    </div>

                    <div>
                        <h4 class="text-white font-bold mb-2">3. RISIKO PASAR</h4>
                        <ul class="list-disc list-inside text-gray-400 space-y-1 ml-4">
                            <li>Pergerakan harga tidak sesuai prediksi</li>
                            <li>Gap harga saat pembukaan pasar</li>
                            <li>Slippage pada eksekusi order</li>
                            <li>Kondisi pasar tidak normal (force majeure)</li>
                        </ul>
                    </div>

                    <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-xl p-4">
                        <h4 class="text-white font-bold mb-2">4. TIDAK ADA JAMINAN PROFIT</h4>
                        <p class="text-gray-400">
                            Tidak ada strategi, sistem, atau EA yang dapat menjamin keuntungan.
                            Performa masa lalu tidak menjamin hasil di masa depan.
                        </p>
                    </div>

                    <div>
                        <h4 class="text-white font-bold mb-2">5. TANGGUNG JAWAB INVESTOR</h4>
                        <ul class="list-disc list-inside text-gray-400 space-y-1 ml-4">
                            <li>Memahami sepenuhnya risiko yang ada</li>
                            <li>Hanya gunakan dana yang siap untuk hilang</li>
                            <li>Membuat keputusan trading sendiri</li>
                            <li>Mengelola risiko dengan baik</li>
                        </ul>
                    </div>

                    <div class="bg-blue-500/10 border border-blue-500/30 rounded-xl p-4">
                        <h4 class="text-white font-bold mb-2">6. REKOMENDASI</h4>
                        <ul class="list-disc list-inside text-gray-400 space-y-1 ml-4">
                            <li>Pelajari dengan baik sebelum trading</li>
                            <li>Gunakan akun demo terlebih dahulu</li>
                            <li>Mulai dengan modal kecil</li>
                            <li>Jangan trading dengan uang pinjaman</li>
                            <li>Selalu gunakan manajemen risiko</li>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Footer -->
        <div class="p-6 border-t border-white/10 flex gap-3">
            <button onclick="closeRisikoModal()" class="flex-1 py-3 border border-white/20 text-white font-bold rounded-xl hover:bg-white/5 transition">
                Tutup
            </button>
            <button onclick="acceptRisiko()" class="flex-1 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                Saya Setuju
            </button>
        </div>
    </div>
</div>

<!-- Modal Profil Perusahaan -->
<?php if (isset($legalProfil) && $legalProfil): ?>
    <div id="profilModal" class="fixed inset-0 z-[120] hidden items-center justify-center bg-black/90 backdrop-blur-md p-4">
        <div class="bg-[#111] border border-white/10 rounded-3xl max-w-3xl w-full max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-white/10">
                <h3 class="text-xl font-bold text-white"><?= esc($legalProfil['title']) ?></h3>
                <button onclick="closeProfilModal()" class="text-gray-400 hover:text-white transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6 text-sm">
                <div id="profilContent" class="prose prose-invert prose-sm max-w-none text-gray-300 legal-dynamic-content">
                    <?= $legalProfil['content'] ?>
                </div>
            </div>

            <!-- Footer -->
            <div class="p-6 border-t border-white/10 flex gap-3">
                <button onclick="closeProfilModal()" class="flex-1 py-3 border border-white/20 text-white font-bold rounded-xl hover:bg-white/5 transition">
                    Tutup
                </button>
                <button onclick="acceptProfil()" class="flex-1 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                    Saya Setuju
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Modal Ketentuan Program Advokasi -->
<?php if (isset($showAdvocacyBundle) && $showAdvocacyBundle && isset($legalAdvokasi) && $legalAdvokasi): ?>
    <div id="advokasiModal" class="fixed inset-0 z-[120] hidden items-center justify-center bg-black/90 backdrop-blur-md p-4">
        <div class="bg-[#111] border border-white/10 rounded-3xl max-w-3xl w-full max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-white/10">
                <h3 class="text-xl font-bold text-white"><?= esc($legalAdvokasi['title']) ?></h3>
                <button onclick="closeAdvokasiModal()" class="text-gray-400 hover:text-white transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6 text-sm">
                <div class="bg-accent/10 border border-accent/30 rounded-xl p-4">
                    <p class="text-accent text-xs">
                        <i class="fas fa-info-circle mr-2"></i>
                        Silakan baca dengan seksama sebelum menyetujui Ketentuan Program Advokasi
                    </p>
                </div>

                <div class="prose prose-invert prose-sm max-w-none text-gray-300 legal-dynamic-content">
                    <?= $legalAdvokasi['content'] ?>
                </div>
            </div>

            <!-- Footer -->
            <div class="p-6 border-t border-white/10 flex gap-3">
                <button onclick="closeAdvokasiModal()" class="flex-1 py-3 border border-white/20 text-white font-bold rounded-xl hover:bg-white/5 transition">
                    Tutup
                </button>
                <button onclick="acceptAdvokasi()" class="flex-1 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                    Saya Setuju
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Modal Account Exists (Login Required) -->
<div id="accountExistsModal" class="fixed inset-0 z-[150] hidden items-center justify-center bg-black/90 backdrop-blur-md p-4">
    <div class="bg-[#111] border border-white/10 rounded-3xl p-8 max-w-sm w-full transform transition-all duration-300 shadow-[0_0_50px_rgba(255,50,50,0.2)] text-center">
        <div class="w-20 h-20 bg-red-500/20 rounded-full flex items-center justify-center mx-auto mb-6 relative">
            <i class="fas fa-user-lock text-red-500 text-3xl"></i>
            <div class="absolute inset-0 bg-red-500 rounded-full animate-ping opacity-20"></div>
        </div>

        <h3 class="text-2xl font-bold mb-3 text-white">Akun Sudah Terdaftar</h3>
        <p class="text-gray-400 mb-8 leading-relaxed text-sm" id="accountExistsMessage">
            <?= session()->getFlashdata('login_required_error') ?>
        </p>

        <div class="space-y-3">
            <a href="<?= base_url('login?redirect=' . uri_string()) ?>" class="block w-full py-4 bg-accent text-black font-bold rounded-2xl hover:bg-white transition shadow-lg shadow-accent/20">
                Login Sekarang
            </a>
            <button onclick="document.getElementById('accountExistsModal').classList.add('hidden')" class="block w-full py-4 border border-white/10 text-gray-400 font-bold rounded-2xl hover:bg-white/5 transition">
                Gunakan Data Lain
            </button>
        </div>
    </div>
</div>

<script>
    // Check if we need to show the Account Exists modal
    <?php if (session()->getFlashdata('login_required_error')): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('accountExistsModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    <?php endif; ?>

    const basePrice = <?= $price ?>;
    const poinBalance = <?= $poinBalance ?>;
    const productType = '<?= $productType ?? 'kelas' ?>';
    const productId = <?= $kelas['id'] ?>;
    const productSlug = '<?= isset($kelas['slug']) ? esc($kelas['slug']) : 'layanan' ?>';
    let currentPrice = basePrice;
    let selectedPoin = 0;
    let voucherDiscount = 0;
    let appliedVoucherId = null;

    // OTP Checkout State
    const isLoggedIn = <?= session()->get('isLoggedIn') ? 'true' : 'false' ?>;
    let userData = {
        email: '<?= isset($user) && $user ? esc($user['email']) : '' ?>',
        phone: '<?= isset($user) && $user ? esc($user['phone'] ?? '') : '' ?>',
        name: '<?= isset($user) && $user ? esc($user['name']) : '' ?>'
    };
    let guestData = {};
    // OTP is NOT required for logged-in users, so set otpVerified = true for them
    let otpVerified = isLoggedIn ? true : false;
    let otpCountdown = 0;
    let otpTimerInterval = null;


    function formatPrice(price) {
        return 'Rp ' + price.toLocaleString('id-ID');
    }

    function updatePrice() {
        currentPrice = Math.max(0, basePrice - voucherDiscount);

        document.getElementById('totalPrice').textContent = formatPrice(currentPrice);
        document.getElementById('poinAmountInput').value = 0;

        if (voucherDiscount > 0) {
            document.getElementById('voucherDiscountRow').classList.remove('hidden');
            document.getElementById('voucherDiscountRow').classList.add('flex');
            document.getElementById('voucherDiscountAmount').textContent = '-' + formatPrice(voucherDiscount);
        } else {
            document.getElementById('voucherDiscountRow').classList.add('hidden');
            document.getElementById('voucherDiscountRow').classList.remove('flex');
        }

        // Update Button Text if exists
        const payButton = document.getElementById('payButton');
        if (payButton && !payButton.disabled) {
            if (isLoggedIn) {
                payButton.innerHTML = '<i class="fas fa-lock mr-2"></i> Bayar ' + formatPrice(currentPrice);
            }
        }
    }

    // Get guest data from form inputs
    function getGuestDataFromForm() {
        const nameEl = document.getElementsByName('guest_name')[0];
        const emailEl = document.getElementsByName('guest_email')[0];
        const phoneEl = document.getElementsByName('guest_phone')[0];

        if (!nameEl || !emailEl || !phoneEl) return null;

        const name = nameEl.value.trim();
        const email = emailEl.value.trim();
        const phone = phoneEl.value.trim();

        if (!name || !email || !phone) {
            return {
                error: 'Silakan lengkapi data diri Anda terlebih dahulu.',
                field: 'guest_name'
            };
        }

        if (!email.includes('@')) {
            return {
                error: 'Format email tidak valid.',
                field: 'guest_email'
            };
        }

        // Update target displays
        const waTarget = document.getElementById('guestWaTarget');
        const emailTarget = document.getElementById('guestEmailTarget');
        if (waTarget) waTarget.textContent = phone;
        if (emailTarget) emailTarget.textContent = email;

        return {
            name,
            email,
            phone
        };
    }

    // Send OTP for a single channel (inline on page)
    async function sendSingleOtp(channel) {
        // Determine if guest or logged-in user based on available elements
        const isGuestFlow = !isLoggedIn;
        const prefixId = isGuestFlow ? 'guest' : '';

        const btnId = channel === 'whatsapp' ?
            (prefixId ? 'guestSendWaOtpBtn' : 'sendWaOtpBtn') :
            (prefixId ? 'guestSendEmailOtpBtn' : 'sendEmailOtpBtn');
        const btn = document.getElementById(btnId);

        const errorId = channel === 'whatsapp' ?
            (prefixId ? 'guestWaError' : 'waError') :
            (prefixId ? 'guestEmailError' : 'emailError');
        const errorEl = document.getElementById(errorId);

        const inputSectionId = channel === 'whatsapp' ?
            (prefixId ? 'guestWaOtpInputSection' : 'waOtpInputSection') :
            (prefixId ? 'guestEmailOtpInputSection' : 'emailOtpInputSection');
        const inputSection = document.getElementById(inputSectionId);

        if (!btn) return;

        // For guest users, get data from form
        if (isGuestFlow) {
            const formData = getGuestDataFromForm();
            if (!formData) {
                if (errorEl) errorEl.textContent = 'Form tidak ditemukan';
                return;
            }
            if (formData.error) {
                if (errorEl) errorEl.textContent = formData.error;
                if (formData.field) {
                    const fieldEl = document.getElementsByName(formData.field)[0];
                    if (fieldEl) fieldEl.focus();
                }
                return;
            }
            guestData = formData;
        }

        // Reset error
        if (errorEl) errorEl.textContent = '';

        // Update button state
        const originalText = btn.textContent;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        try {
            // Choose endpoint based on login status
            const endpoint = isLoggedIn ?
                '<?= base_url('checkout/send-user-otp') ?>' :
                '<?= base_url('checkout/send-otp') ?>';

            const bodyData = isLoggedIn ? {
                channel: channel
            } : {
                name: guestData.name,
                email: guestData.email,
                phone: guestData.phone,
                channel: channel
            };

            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
                },
                body: JSON.stringify(bodyData)
            });

            const result = await response.json();

            if (result.success) {
                if (result.otp_dev) console.log('OTP (Dev) ' + channel + ':', result.otp_dev);

                if (channel === 'whatsapp') {
                    // Manual WA Verification
                    if (inputSection) {
                        inputSection.classList.remove('hidden');
                        inputSection.classList.add('flex', 'flex-col');
                    }
                    
                    const verifyBtn = isGuestFlow ? document.getElementById('guestVerifyWaOtpBtn') : document.getElementById('verifyWaOtpBtn');
                    if (verifyBtn && result.admin_phone && result.otp) {
                        // Create the wa.me link with prefilled text
                        let targetDisplay = '';
                        if (isGuestFlow) {
                            targetDisplay = document.getElementById('guestPhoneInput').value.trim();
                        } else {
                            targetDisplay = '<?= esc($user['phone'] ?? '') ?>';
                        }
                        const waText = `Halo Tim ALMAI,\n\nBerikut kode OTP saya untuk proses verifikasi:\n\nOTP: ${result.otp}\nNo WhatsApp : ${targetDisplay}\n\nMohon diproses. Terima kasih.`;
                        verifyBtn.href = `https://wa.me/${result.admin_phone}?text=${encodeURIComponent(waText)}`;
                    }

                    // Start polling
                    checkWaStatus(isGuestFlow ? document.getElementById('guestPhoneInput').value.trim() : '<?= esc($user['phone'] ?? '') ?>', isGuestFlow);
                    
                    btn.style.display = 'none'; // Hide the send button
                } else {
                    // Show Email OTP input section
                    if (inputSection) {
                        inputSection.classList.remove('hidden');
                        // Focus first input
                        const firstInput = inputSection.querySelector('.otp-email-input');
                        if (firstInput) setTimeout(() => firstInput.focus(), 100);
                    }

                    // Start cooldown timer for this button
                    let countdown = 60;
                    btn.disabled = true;
                    const timer = setInterval(() => {
                        countdown--;
                        btn.innerHTML = `Kirim Ulang (${countdown}s)`;
                        if (countdown <= 0) {
                            clearInterval(timer);
                            btn.disabled = false;
                            btn.innerHTML = 'Kirim Ulang';
                        }
                    }, 1000);
                }
            } else {
                if (result.login_required) {
                    const existsModal = document.getElementById('accountExistsModal');
                    const existsMsg = document.getElementById('accountExistsMessage');
                    if (existsModal && existsMsg) {
                        existsMsg.textContent = result.message;
                        existsModal.classList.remove('hidden');
                        existsModal.classList.add('flex');
                    } else {
                        if (errorEl) errorEl.textContent = result.message;
                    }
                } else {
                    if (errorEl) errorEl.textContent = result.message || 'Gagal mengirim OTP';
                }
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        } catch (e) {
            console.error(e);
            if (errorEl) errorEl.textContent = 'Gagal terhubung ke server';
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }

    // Track verification state
    // Track verification state
    let waVerified = false;
    // Email considered verified by default (user just inputs it)
    let emailVerified = true;

    // WA Polling Logic for Manual Verification
    async function checkWaStatus(phone, isGuest) {
        try {
            const response = await fetch('/register/check-wa?phone=' + encodeURIComponent(phone));
            const result = await response.json();
            
            if (result.verified) {
                // Verified! Update UI based on Guest/Logged-in
                if (isGuest) {
                    const waInputSection = document.getElementById('guestWaOtpInputSection');
                    const waVerifiedBadge = document.getElementById('guestWaVerifiedBadge');
                    const allVerifiedMessage = document.getElementById('guestAllVerifiedMessage');
                    
                    if (waInputSection) waInputSection.style.display = 'none';
                    if (waVerifiedBadge) waVerifiedBadge.classList.remove('hidden');
                    if (allVerifiedMessage) allVerifiedMessage.classList.remove('hidden');
                } else {
                    const waInputSection = document.getElementById('waOtpInputSection');
                    const waVerifiedBadge = document.getElementById('waVerifiedBadge');
                    
                    if (waInputSection) waInputSection.style.display = 'none';
                    if (waVerifiedBadge) waVerifiedBadge.classList.remove('hidden');
                }

                // Global checkout variables update
                otpVerified = true;
                waVerified = true;
                console.log('WA Verified successfully via checkWaStatus polling.');
                updateCheckoutButton();
            } else {
                // Not verified yet, check again after 2.5 seconds
                setTimeout(() => checkWaStatus(phone, isGuest), 2500);
            }
        } catch (e) {
            console.error('Error checking WA status:', e);
            setTimeout(() => checkWaStatus(phone, isGuest), 5000);
        }
    }


    // Verify single channel OTP
    async function verifySingleOtp(channel) {
        const isGuestFlow = !isLoggedIn;
        const prefixId = isGuestFlow ? 'guest' : '';

        // For guest users, ensure guestData is updated from form
        if (isGuestFlow) {
            const formData = getGuestDataFromForm();
            if (formData && !formData.error) {
                guestData = formData;
            }
        }

        // Get OTP value
        const inputClass = channel === 'whatsapp' ? '.otp-wa-input' : '.otp-email-input';
        const inputSection = document.getElementById(
            channel === 'whatsapp' ?
            (prefixId ? 'guestWaOtpInputSection' : 'waOtpInputSection') :
            (prefixId ? 'guestEmailOtpInputSection' : 'emailOtpInputSection')
        );
        const inputs = inputSection ? inputSection.querySelectorAll(inputClass) : [];
        const otpValue = Array.from(inputs).map(i => i.value).join('');

        const errorId = channel === 'whatsapp' ?
            (prefixId ? 'guestWaError' : 'waError') :
            (prefixId ? 'guestEmailError' : 'emailError');
        const errorEl = document.getElementById(errorId);

        const verifyBtnId = channel === 'whatsapp' ?
            (prefixId ? 'guestVerifyWaOtpBtn' : 'verifyWaOtpBtn') :
            (prefixId ? 'guestVerifyEmailOtpBtn' : 'verifyEmailOtpBtn');
        const verifyBtn = document.getElementById(verifyBtnId);

        const badgeId = channel === 'whatsapp' ?
            (prefixId ? 'guestWaVerifiedBadge' : 'waVerifiedBadge') :
            (prefixId ? 'guestEmailVerifiedBadge' : 'emailVerifiedBadge');
        const badge = document.getElementById(badgeId);

        const sendBtnId = channel === 'whatsapp' ?
            (prefixId ? 'guestSendWaOtpBtn' : 'sendWaOtpBtn') :
            (prefixId ? 'guestSendEmailOtpBtn' : 'sendEmailOtpBtn');
        const sendBtn = document.getElementById(sendBtnId);

        // Clear error
        if (errorEl) errorEl.textContent = '';

        if (otpValue.length < 6) {
            if (errorEl) errorEl.textContent = 'Kode OTP belum lengkap (6 digit)';
            return;
        }

        // Disable button
        if (verifyBtn) {
            verifyBtn.disabled = true;
            verifyBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        }

        try {
            const email = isLoggedIn ? userData.email : guestData.email;
            const phone = isLoggedIn ? userData.phone : guestData.phone;

            const response = await fetch('<?= base_url('checkout/verify-single-otp') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
                },
                body: JSON.stringify({
                    channel: channel,
                    otp: otpValue,
                    email: email,
                    phone: phone
                })
            });

            const result = await response.json();

            if (result.success) {
                // Mark as verified
                if (channel === 'whatsapp') {
                    waVerified = true;
                } else {
                    emailVerified = true;
                }

                // Hide input section and show badge
                if (inputSection) inputSection.classList.add('hidden');
                if (badge) badge.classList.remove('hidden');
                if (sendBtn) sendBtn.classList.add('hidden');

                // Check if both verified
                checkBothVerified();
            } else {
                if (errorEl) errorEl.textContent = result.message || 'Kode OTP salah';
                if (verifyBtn) {
                    verifyBtn.disabled = false;
                    verifyBtn.innerHTML = 'Verifikasi';
                }
            }
        } catch (e) {
            console.error(e);
            if (errorEl) errorEl.textContent = 'Gagal terhubung ke server';
            if (verifyBtn) {
                verifyBtn.disabled = false;
                verifyBtn.innerHTML = 'Verifikasi';
            }
        }
    }

    // Check if both channels are verified
    function checkBothVerified() {
        const isGuestFlow = !isLoggedIn;

        // For guest: only need WhatsApp verification
        // For logged-in: need both WhatsApp and Email verification
        const isVerified = isGuestFlow ? waVerified : (waVerified && emailVerified);

        console.log('checkBothVerified:', {
            isGuestFlow,
            waVerified,
            emailVerified,
            isVerified,
            otpVerified
        });

        if (isVerified) {
            const prefixId = isGuestFlow ? 'guest' : '';

            // Show success message
            const successMsgId = prefixId ? 'guestAllVerifiedMessage' : 'allVerifiedMessage';
            const successMsg = document.getElementById(successMsgId);
            if (successMsg) successMsg.classList.remove('hidden');

            otpVerified = true;
            console.log('otpVerified set to true');

            // Trigger Background Registration for Guest (Speed Optimization)
            if (!isLoggedIn && typeof guestData !== 'undefined') {
                fetch('<?= base_url('checkout/register-guest-account') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
                    },
                    body: JSON.stringify(guestData)
                }).then(res => res.json()).then(data => {
                    console.log('Background registration:', data);
                }).catch(err => console.error('Bg reg error:', err));
            }

            // Re-check agreements to enable button if agreements are also checked
            if (typeof checkAgreements === 'function') {
                checkAgreements();
            }
        }
    }


    // Auto focus next input Logic
    function setupAutoTab(className) {
        document.querySelectorAll(className).forEach((input, index, inputs) => {
            input.addEventListener('input', function() {
                this.value = this.value.replace(/\D/g, '');
                if (this.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' && this.value === '' && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        setupAutoTab('.otp-wa-input');
        setupAutoTab('.otp-email-input');
    });

    function submitFinalCheckout() {
        document.getElementById('checkoutForm').submit();
    }

    // Original Functions
    function openTanyaModal() {
        const modal = document.getElementById('tanyaModal');
        const content = document.getElementById('tanyaModalContent');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeTanyaModal() {
        const modal = document.getElementById('tanyaModal');
        modal.classList.add('hidden');
    }

    function askFirst() {
        const nameInput = document.getElementsByName('guest_name')[0];
        const emailInput = document.getElementsByName('guest_email')[0];
        const phoneInput = document.getElementsByName('guest_phone')[0];

        let message = "Hai Angel, mau tanya tentang <?= esc($kelas['title']) ?>. ";

        if (nameInput) {
            if (!nameInput.value || !emailInput.value || !phoneInput.value) {
                openTanyaModal(); // Show requirement modal instead of just alert
                return;
            }
            message += `\n\nData Calon Pembeli:\n- Nama: ${nameInput.value}\n- Email: ${emailInput.value}\n- WhatsApp: ${phoneInput.value}`;
        } else {
            message += `\n(Akun: <?= session()->get('userEmail') ?>)`;
        }

        const waUrl = `https://wa.me/6285183231800?text=${encodeURIComponent(message)}`;
        window.open(waUrl, '_blank');
    }

    async function applyVoucher() {
        const code = document.getElementById('voucherCode').value.trim().toUpperCase();
        const messageEl = document.getElementById('voucherMessage');
        const btn = document.getElementById('applyVoucherBtn');

        if (!code) return;

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        const requestData = {
            code,
            product_type: productType,
            product_id: productId,
            amount: basePrice
        };

        try {
            const response = await fetch('<?= base_url('checkout/validate-voucher') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
                },
                body: JSON.stringify(requestData)
            });

            const result = await response.json();
            if (result.valid) {
                voucherDiscount = result.discount;
                appliedVoucherId = result.voucher_id;
                document.getElementById('voucherIdInput').value = appliedVoucherId;
                document.getElementById('voucherDiscountInput').value = voucherDiscount;

                messageEl.className = 'mt-2 text-sm text-accent';
                messageEl.innerHTML = '<i class="fas fa-check-circle mr-1"></i> ' + result.message + ' (-' + formatPrice(voucherDiscount) + ')';

                document.getElementById('voucherCode').disabled = true;
                btn.innerHTML = '<i class="fas fa-times"></i>';
                btn.onclick = removeVoucher;
            } else {
                messageEl.className = 'mt-2 text-sm text-red-500';
                messageEl.innerHTML = '<i class="fas fa-times-circle mr-1"></i> ' + result.message;
                btn.innerHTML = 'Terapkan';
            }

            messageEl.classList.remove('hidden');
            updatePrice();
        } catch (error) {
            console.error(error);
        }

        btn.disabled = false;
    }

    function removeVoucher() {
        voucherDiscount = 0;
        appliedVoucherId = null;
        document.getElementById('voucherIdInput').value = '';
        document.getElementById('voucherDiscountInput').value = '0';
        document.getElementById('voucherCode').value = '';
        document.getElementById('voucherCode').disabled = false;
        document.getElementById('voucherMessage').classList.add('hidden');

        const btn = document.getElementById('applyVoucherBtn');
        btn.innerHTML = 'Terapkan';
        btn.onclick = applyVoucher;

        updatePrice();
    }

    // Payment method selection styling
    document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.payment-option').forEach(opt => {
                opt.classList.remove('border-accent', 'border-yellow-500');
                opt.classList.add('border-white/20');
            });

            if (this.checked) {
                const color = this.value === 'poin' ? 'border-yellow-500' : 'border-accent';
                this.closest('.payment-option').classList.remove('border-white/20');
                this.closest('.payment-option').classList.add(color);
            }


        });
    });



    // OTP Inputs behavior
    document.querySelectorAll('.otp-check-input').forEach((input, index, inputs) => {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
            if (this.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
            }
            if (Array.from(inputs).every(i => i.value.length === 1)) {
                verifyCheckOtp();
            }
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && this.value === '' && index > 0) {
                inputs[index - 1].focus();
            }
        });
    });

    // Agreement Checkbox Handler
    const agreementPerjanjian = document.getElementById('agreementPerjanjian');
    const agreementRisiko = document.getElementById('agreementRisiko');
    const agreementProfil = document.getElementById('agreementProfil');
    const agreementAdvokasi = document.getElementById('agreementAdvokasi');
    const payButton = document.getElementById('payButton');

    function checkAgreements() {
        if (payButton && agreementPerjanjian && agreementRisiko) {
            let isAllChecked = agreementPerjanjian.checked && agreementRisiko.checked;
            if (agreementProfil) {
                isAllChecked = isAllChecked && agreementProfil.checked;
            }
            if (agreementAdvokasi) {
                isAllChecked = isAllChecked && agreementAdvokasi.checked;
            }


            // Button enabled ONLY if Agreements Checked AND OTP Verified
            if (isAllChecked && otpVerified) {
                payButton.disabled = false;
                payButton.innerHTML = '<i class="fas fa-lock-open mr-2"></i> Bayar Sekarang';

                // Hide hint
                const hint = document.getElementById('payButtonHint');
                if (hint) hint.classList.add('hidden');
            } else {
                payButton.disabled = true;
                payButton.innerHTML = '<i class="fas fa-lock mr-2"></i> Bayar Sekarang';

                // Show hint if OTP not verified
                const hint = document.getElementById('payButtonHint');
                if (hint && !otpVerified) {
                    hint.classList.remove('hidden');
                    hint.innerHTML = '<i class="fas fa-info-circle mr-1"></i>Selesaikan verifikasi OTP terlebih dahulu';
                } else if (hint && !isAllChecked) {
                    hint.classList.remove('hidden');
                    hint.innerHTML = '<i class="fas fa-info-circle mr-1"></i>Harap setujui semua persyaratan layanan terlebih dahulu';
                }
            }
        }
    }

    function toggleAgreeAll() {
        if (!agreementPerjanjian || !agreementRisiko) return;

        const newState = !agreementPerjanjian.checked;

        agreementPerjanjian.checked = newState;
        agreementPerjanjian.disabled = false;

        agreementRisiko.checked = newState;
        agreementRisiko.disabled = false;

        if (agreementProfil) {
            agreementProfil.checked = newState;
            agreementProfil.disabled = false;
        }

        if (agreementAdvokasi) {
            agreementAdvokasi.checked = newState;
            agreementAdvokasi.disabled = false;
        }

        // Update styling or text if needed
        const btn = document.getElementById('agreeAllBtn');
        if (btn) {
            btn.textContent = newState ? 'Batal Setujui' : 'Setujui Semua';
            btn.className = newState ?
                'text-xs bg-accent/20 hover:bg-accent/30 text-accent px-3 py-1.5 rounded-lg transition' :
                'text-xs bg-white/10 hover:bg-white/20 text-white px-3 py-1.5 rounded-lg transition';
        }

        checkAgreements();
    }

    if (agreementPerjanjian) {
        agreementPerjanjian.addEventListener('change', checkAgreements);
    }

    if (agreementRisiko) {
        agreementRisiko.addEventListener('change', checkAgreements);
    }

    if (agreementProfil) {
        agreementProfil.addEventListener('change', checkAgreements);
    }

    if (agreementAdvokasi) {
        agreementAdvokasi.addEventListener('change', checkAgreements);
    }

    // Modal Functions - Global scope
    function updateLegalPlaceholders() {
        const isLoggedIn = <?= session()->get('isLoggedIn') ? 'true' : 'false' ?>;
        let userName = '-';
        let userEmail = '-';
        let userPhone = '-';

        if (!isLoggedIn) {
            userName = document.getElementsByName('guest_name')[0]?.value || '-';
            userEmail = document.getElementsByName('guest_email')[0]?.value || '-';
            userPhone = document.getElementsByName('guest_phone')[0]?.value || '-';

            if (document.getElementById('modalUserName')) document.getElementById('modalUserName').textContent = userName;
            if (document.getElementById('modalUserEmail')) document.getElementById('modalUserEmail').textContent = userEmail;
            if (document.getElementById('modalUserPhone')) document.getElementById('modalUserPhone').textContent = userPhone;
            if (document.getElementById('modalKlienSignature')) document.getElementById('modalKlienSignature').textContent = userName;
        } else {
            userName = '<?= session()->get('isLoggedIn') ? esc($user['name']) : '-' ?>';
            userEmail = '<?= session()->get('isLoggedIn') ? esc($user['email']) : '-' ?>';
            userPhone = '<?= session()->get('isLoggedIn') ? esc($user['phone'] ?? '-') : '-' ?>';
        }

        // Generate nomor kontrak
        const today = new Date();
        const year = today.getFullYear();
        const month = today.getMonth() + 1;
        const random = Math.floor(Math.random() * 10000).toString().padStart(4, '0');
        const romanMonths = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        const romanMonth = romanMonths[month - 1];
        const nomorKontrak = `${random}/ALMAI/${productSlug.toUpperCase()}/${romanMonth}/${year}`;

        if (document.getElementById('modalNomorKontrak')) document.getElementById('modalNomorKontrak').textContent = nomorKontrak;
        if (document.getElementById('modalNomorPerjanjian')) document.getElementById('modalNomorPerjanjian').textContent = nomorKontrak;

        // Replace Placeholders in Dynamic Content
        const dynamicContents = document.querySelectorAll('.legal-dynamic-content');
        const hariTanggal = new Intl.DateTimeFormat('id-ID', {
            dateStyle: 'full'
        }).format(today);

        const replacements = {
            '{NAMA_USER}': userName,
            '{EMAIL_USER}': userEmail,
            '{NO_TELP_USER}': userPhone,
            '{NOMOR_KONTRAK}': nomorKontrak,
            '{NAMA_WPA}': '<?= esc($kelas['wpa_name']) ?>',
            '{NAMA_PRODUK}': '<?= esc($kelas['title']) ?>',
            '{NAMA_LAYANAN}': '<?= esc($kelas['title']) ?>',
            '{HARGA_RUPIAH_PRODUK}': 'Rp <?= number_format($kelas['original_price'] ?? $kelas['price'], 0, ',', '.') ?>',
            '{HARGA_LAYANAN}': 'Rp <?= number_format($kelas['original_price'] ?? $kelas['price'], 0, ',', '.') ?>',
            '{HARGA_YANG_DI_BAYAR}': 'Rp ' + (typeof currentPrice !== 'undefined' ? currentPrice.toLocaleString('id-ID') : '0'),
            '{TOTAL_BAYAR}': 'Rp ' + (typeof currentPrice !== 'undefined' ? currentPrice.toLocaleString('id-ID') : '0'),
            '{TANGGAL}': '<?= date('d F Y') ?>',
            '{HARI_TANGGAL}': hariTanggal,
            '{LAYANAN_UTAMA}': <?= json_encode($kelas['layanan_utama'] ?? '-') ?>.replace(/\n/g, '<br>')
        };

        dynamicContents.forEach(content => {
            let html = content.getAttribute('data-original-html');
            if (!html) {
                html = content.innerHTML;
                content.setAttribute('data-original-html', html);
            }

            let newHtml = html;
            for (const [placeholder, value] of Object.entries(replacements)) {
                const regex = new RegExp(placeholder.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'g');
                newHtml = newHtml.replace(regex, value);
            }
            content.innerHTML = newHtml;
        });
    }

    window.openPerjanjianModal = function() {
        updateLegalPlaceholders();
        const modal = document.getElementById('perjanjianModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }



    window.closePerjanjianModal = function() {
        const modal = document.getElementById('perjanjianModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    window.acceptPerjanjian = function() {
        const checkbox = document.getElementById('agreementPerjanjian');
        checkbox.disabled = false;
        checkbox.checked = true;
        checkbox.classList.remove('opacity-50', 'cursor-not-allowed');
        checkAgreements();
        closePerjanjianModal();
    }

    window.openRisikoModal = function() {
        updateLegalPlaceholders();
        const modal = document.getElementById('risikoModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    window.closeRisikoModal = function() {
        const modal = document.getElementById('risikoModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    window.acceptRisiko = function() {
        const checkbox = document.getElementById('agreementRisiko');
        checkbox.disabled = false;
        checkbox.checked = true;
        checkbox.classList.remove('opacity-50', 'cursor-not-allowed');
        checkAgreements();
        closeRisikoModal();
    }

    window.openProfilModal = function() {
        updateLegalPlaceholders();
        const modal = document.getElementById('profilModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
    }

    window.closeProfilModal = function() {
        const modal = document.getElementById('profilModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }
    }

    window.acceptProfil = function() {
        const checkbox = document.getElementById('agreementProfil');
        if (checkbox) {
            checkbox.disabled = false;
            checkbox.checked = true;
            checkbox.classList.remove('opacity-50', 'cursor-not-allowed');
            checkAgreements();
            closeProfilModal();
        }
    }

    // Advocacy Modal Functions
    window.openAdvokasiModal = function() {
        updateLegalPlaceholders();
        const modal = document.getElementById('advokasiModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
    }

    window.closeAdvokasiModal = function() {
        const modal = document.getElementById('advokasiModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }
    }

    window.acceptAdvokasi = function() {
        const checkbox = document.getElementById('agreementAdvokasi');
        if (checkbox) {
            checkbox.disabled = false;
            checkbox.checked = true;
            checkbox.classList.remove('opacity-50', 'cursor-not-allowed');
            checkAgreements();
            closeAdvokasiModal();
        }
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        const checkedRadio = document.querySelector('input[name="payment_method"]:checked');
        if (checkedRadio) checkedRadio.dispatchEvent(new Event('change'));
        updatePrice();

        // Ensure pay button is disabled initially
        checkAgreements();

        // Form submit loading state
        const checkoutForm = document.getElementById('checkoutForm');
        if (checkoutForm) {
            checkoutForm.addEventListener('submit', function() {
                const btn = document.getElementById('payButton');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i> Memproses Pembayaran...';

                    // If paying with Poin (full payment), logic is fast. Usually no need for extensive wait.
                    // But for Xendit, it might redirect or wait for API response.
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>
