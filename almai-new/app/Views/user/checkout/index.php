<?= $this->extend('user/partials/layout') ?>

<?= $this->section('content') ?>
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
<section class="pb-8  min-h-screen">
    <div class="container mx-auto px-4 md:px-6 max-w-4xl">
        <h1 class="text-2xl md:text-4xl font-bold mb-1 md:mb-2 text-center">Checkout</h1>
        <p class="text-gray-400 text-center mb-6 md:mb-8 text-sm md:text-base">Selesaikan pembayaran untuk mengakses <?= $productLabel ?></p>



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
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1 ml-1">Email</label>
                                    <input type="email" name="guest_email" required placeholder="alamat@email.com"
                                        class="w-full bg-black/50 border border-white/10 rounded-xl px-3 md:px-4 py-2.5 md:py-3 text-sm md:text-base focus:border-accent outline-none transition">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1 ml-1">WhatsApp (62xxx)</label>
                                    <input type="tel" name="guest_phone" required placeholder="628123456789"
                                        class="w-full bg-black/50 border border-white/10 rounded-xl px-3 md:px-4 py-2.5 md:py-3 text-sm md:text-base focus:border-accent outline-none transition">
                                </div>
                                <p class="text-[10px] text-gray-500 italic mt-2">* Password akan dikirimkan ke email/WA setelah aktivasi.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if (!session()->get('isLoggedIn')): ?>
                        <?php if ($referrer): ?>
                            <div class="mb-6 pb-6 border-b border-white/10">
                                <label class="block text-sm font-medium text-gray-400 mb-2">Kode Referral Terpasang</label>
                                <div class="bg-accent/10 border border-accent/30 rounded-xl p-3 flex flex-col sm:flex-row items-start sm:items-center gap-3">
                                    <div class="flex items-center gap-3 w-full sm:w-auto">
                                        <div class="w-10 h-10 bg-accent/20 rounded-full flex items-center justify-center flex-shrink-0">
                                            <i class="fas <?= (isset($referralReadOnly) && $referralReadOnly) ? 'fa-lock' : 'fa-check' ?> text-accent"></i>
                                        </div>
                                        <div class="flex-1 min-w-0 sm:hidden">
                                            <p class="text-xs text-gray-400"><?= (isset($referralReadOnly) && $referralReadOnly) ? 'Referral Wajib:' : 'Direferensikan oleh:' ?></p>
                                            <p class="font-bold text-sm text-white truncate"><?= esc($referrer['name']) ?></p>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0 hidden sm:block">
                                        <p class="text-xs text-gray-400"><?= (isset($referralReadOnly) && $referralReadOnly) ? 'Referral Wajib:' : 'Direferensikan oleh:' ?></p>
                                        <p class="font-bold text-sm text-white truncate"><?= esc($referrer['name']) ?></p>
                                    </div>
                                    <input type="text" value="<?= esc($refCode) ?>" readonly
                                        class="w-full sm:w-24 bg-black/50 border border-white/10 rounded-lg px-3 py-2 text-center text-accent font-mono font-bold text-sm focus:outline-none <?= (isset($referralReadOnly) && $referralReadOnly) ? 'cursor-not-allowed text-gray-400' : '' ?>">
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
                        <div id="poinDiscountRow" class="hidden flex justify-between text-yellow-400 text-sm md:text-base">
                            <span>Potongan Poin</span>
                            <span id="poinDiscountAmount">-Rp 0</span>
                        </div>
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
                        <?php elseif (isset($productType) && $productType === 'cwpa'): ?>
                            <input type="hidden" name="cwpa_id" value="0">
                        <?php else: ?>
                            <input type="hidden" name="kelas_id" value="<?= $kelas['id'] ?>">
                        <?php endif; ?>
                        <input type="hidden" name="poin_amount" id="poinAmountInput" value="0">
                        <input type="hidden" name="package_id" value="<?= isset($kelas['package_id']) ? $kelas['package_id'] : '' ?>">

                        <div class="space-y-2 md:space-y-3 mb-4 md:mb-6">
                            <!-- Almai Poin (Full Payment) -->
                            <label class="payment-option flex items-start sm:items-center gap-3 md:gap-4 bg-black border-2 border-white/20 rounded-xl px-3 md:px-4 py-3 md:py-4 cursor-pointer hover:border-yellow-500 transition <?= !$canPayWithPoin ? 'opacity-50 cursor-not-allowed' : '' ?>">
                                <input type="radio" name="payment_method" value="poin" class="mt-1 sm:mt-0 accent-yellow-500 w-4 md:w-5 h-4 md:h-5 flex-shrink-0" <?= !$canPayWithPoin ? 'disabled' : '' ?>>
                                <div class="w-10 h-10 md:w-12 md:h-12 bg-yellow-500/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-coins text-yellow-500 text-lg md:text-xl"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-sm md:text-base leading-tight">Bayar pakai Saldo Poin</p>
                                    <p class="text-[10px] md:text-xs text-gray-400 mt-0.5">
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
                                ['code' => 'BNI', 'name' => 'BNI Virtual Account', 'icon' => 'https://assets.xendit.co/payment-channels/logos/bni-logo.svg'],
                                ['code' => 'BRI', 'name' => 'BRI Virtual Account', 'icon' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/68/BANK_BRI_logo.svg/2560px-BANK_BRI_logo.svg.png'],
                                ['code' => 'BSI', 'name' => 'BSI Virtual Account', 'icon' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a0/Bank_Syariah_Indonesia.svg/1200px-Bank_Syariah_Indonesia.svg.png'],
                                ['code' => 'MANDIRI', 'name' => 'Mandiri Virtual Account', 'icon' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/2560px-Bank_Mandiri_logo_2016.svg.png'],
                            ];

                            $paymentMethods = [];

                            // VA only for 1jt+
                            if ($price >= 1000000) {
                                $paymentMethods = $allVaMethods;
                            }

                            // QRIS for all (up to 10jt)
                            if ($price <= 10000000) {
                                $paymentMethods[] = ['code' => 'QRIS', 'name' => 'QRIS', 'icon' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Logo_QRIS.svg/1200px-Logo_QRIS.svg.png'];
                            }
                            ?>

                            <?php foreach ($paymentMethods as $index => $method): ?>
                                <label class="payment-option flex items-center gap-2 md:gap-4 bg-black border-2 border-white/20 rounded-xl px-3 md:px-4 py-3 md:py-4 cursor-pointer hover:border-accent transition">
                                    <input type="radio" name="payment_method" value="<?= $method['code'] ?>" class="accent-accent w-4 md:w-5 h-4 md:h-5 flex-shrink-0" <?= $index === 0 ? 'checked' : '' ?>>
                                    <div class="w-10 h-10 md:w-12 md:h-12 bg-white rounded-xl flex items-center justify-center p-1.5 md:p-2 overflow-hidden flex-shrink-0">
                                        <img src="<?= $method['icon'] ?>" alt="<?= $method['code'] ?>" class="w-full h-full object-contain">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-sm md:text-base leading-tight"><?= $method['name'] ?></p>
                                        <p class="text-[10px] md:text-xs text-gray-400">Verifikasi Otomatis</p>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        </div>

                        <!-- Use Poin as Discount (if not full poin payment) -->
                        <?php if ($poinBalance > 0): ?>
                            <div id="usePoinSection" class="mb-6 p-4 bg-yellow-500/10 border border-yellow-500/20 rounded-xl">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="use_poin" id="usePoinCheckbox" class="accent-yellow-500 w-5 h-5">
                                    <div>
                                        <p class="font-medium">Gunakan poin sebagai potongan</p>
                                        <p class="text-xs text-gray-400">Maksimal <?= number_format(min($poinBalance, ceil($price / 100))) ?> poin (Rp <?= number_format(min($poinValue, $price), 0, ',', '.') ?>)</p>
                                    </div>
                                </label>
                                <div id="poinSliderSection" class="hidden mt-4">
                                    <input type="range" id="poinSlider" min="0" max="<?= min($poinBalance, ceil($price / 100)) ?>" value="0" class="w-full accent-yellow-500">
                                    <div class="flex justify-between text-sm mt-2">
                                        <span class="text-gray-400">0 poin</span>
                                        <span id="poinSliderValue" class="text-yellow-400 font-bold">0 poin = Rp 0</span>
                                        <span class="text-gray-400"><?= number_format(min($poinBalance, ceil($price / 100))) ?> poin</span>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

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
                        </div>

                        <!-- Checkbox Persetujuan -->
                        <div class="mb-4 p-4 bg-red-500/10 border border-red-500/30 rounded-xl space-y-3">
                            <p class="text-white font-medium text-sm mb-3">Saya telah memahami dan menyetujui:</p>

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
                        </div>

                        <div class="space-y-2 md:space-y-3">
                            <?php if (!session()->get('isLoggedIn')): ?>
                                <button type="button" onclick="startGuestVerification()" id="payButton" disabled class="w-full py-3 md:py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition shadow-[0_0_20px_rgba(51,232,24,0.3)] text-sm md:text-base disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i class="fas fa-shield-alt mr-2"></i> Verifikasi Data & Bayar
                                </button>
                            <?php else: ?>
                                <button type="submit" id="payButton" disabled class="w-full py-3 md:py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition shadow-[0_0_20px_rgba(51,232,24,0.3)] text-sm md:text-base disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i class="fas fa-lock mr-2"></i> Bayar Sekarang
                                </button>
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

<!-- Modal OTP Checkout -->
<div id="checkoutOtpModal" class="fixed inset-0 z-[110] hidden items-center justify-center bg-black/90 backdrop-blur-md p-4">
    <div class="bg-[#111] border border-white/10 rounded-3xl p-8 max-w-md w-full relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-white/10">
            <div id="otpProgress" class="h-full bg-accent transition-all duration-500" style="width: 0%"></div>
        </div>

        <button onclick="closeCheckoutOtpModal()" class="absolute top-4 right-4 text-gray-500 hover:text-white">
            <i class="fas fa-times"></i>
        </button>

        <div id="otpStep1" class="text-center">
            <div class="w-16 h-16 bg-accent/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <i id="otpStepIcon" class="fab fa-whatsapp text-accent text-3xl"></i>
            </div>
            <h3 id="otpStepTitle" class="text-xl font-bold mb-2">Verifikasi WhatsApp</h3>
            <p id="otpStepDesc" class="text-gray-400 text-sm mb-6">Masukan kode 6 digit yang dikirim ke <br><strong id="otpStepTarget" class="text-white"></strong></p>

            <div class="flex gap-2 justify-center mb-6">
                <input type="text" maxlength="1" class="otp-check-input w-10 md:w-12 h-12 md:h-14 bg-black border border-white/20 rounded-xl text-center text-xl md:text-2xl font-bold focus:border-accent focus:outline-none" data-index="0">
                <input type="text" maxlength="1" class="otp-check-input w-10 md:w-12 h-12 md:h-14 bg-black border border-white/20 rounded-xl text-center text-xl md:text-2xl font-bold focus:border-accent focus:outline-none" data-index="1">
                <input type="text" maxlength="1" class="otp-check-input w-10 md:w-12 h-12 md:h-14 bg-black border border-white/20 rounded-xl text-center text-xl md:text-2xl font-bold focus:border-accent focus:outline-none" data-index="2">
                <input type="text" maxlength="1" class="otp-check-input w-10 md:w-12 h-12 md:h-14 bg-black border border-white/20 rounded-xl text-center text-xl md:text-2xl font-bold focus:border-accent focus:outline-none" data-index="3">
                <input type="text" maxlength="1" class="otp-check-input w-10 md:w-12 h-12 md:h-14 bg-black border border-white/20 rounded-xl text-center text-xl md:text-2xl font-bold focus:border-accent focus:outline-none" data-index="4">
                <input type="text" maxlength="1" class="otp-check-input w-10 md:w-12 h-12 md:h-14 bg-black border border-white/20 rounded-xl text-center text-xl md:text-2xl font-bold focus:border-accent focus:outline-none" data-index="5">
            </div>

            <p id="otpCheckError" class="text-red-400 text-xs mb-6 hidden"></p>

            <button onclick="verifyCheckOtp()" id="verifyCheckOtpBtn" class="w-full py-4 bg-accent text-black font-bold rounded-2xl hover:bg-white transition mb-6 shadow-lg shadow-accent/20">
                Verifikasi
            </button>

            <div class="text-center">
                <p class="text-gray-500 text-xs">Tidak menerima kode?</p>
                <button onclick="resendCheckOtp()" id="resendCheckBtn" disabled class="text-accent text-sm font-bold mt-1 opacity-50 cursor-not-allowed">
                    Kirim Ulang (<span id="otpTimer">60</span>s)
                </button>
            </div>
        </div>

        <div id="otpWaSuccess" class="hidden text-center py-8">
            <div class="w-20 h-20 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-6 relative">
                <i class="fab fa-whatsapp text-green-500 text-4xl"></i>
                <div class="absolute inset-0 bg-green-500 rounded-full animate-ping opacity-20"></div>
            </div>
            <h3 class="text-2xl font-bold mb-2 text-white">WhatsApp Terverifikasi!</h3>
            <p class="text-gray-400 mb-8">Satu langkah lagi! <br>Silakan verifikasi Email Anda untuk memproses pesanan.</p>
            <button onclick="continueToEmailVerification()" class="w-full py-4 bg-accent text-black font-bold rounded-2xl hover:bg-white transition shadow-lg shadow-accent/20">
                Verifikasi Email Sekarang <i class="fas fa-arrow-right ml-2"></i>
            </button>
        </div>

        <div id="otpSuccess" class="hidden text-center py-8">
            <div class="w-20 h-20 bg-accent/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-check text-accent text-4xl"></i>
            </div>
            <h3 class="text-2xl font-bold mb-2">Verifikasi Berhasil!</h3>
            <p class="text-gray-400 mb-8">Data Anda telah terverifikasi. <br>Lanjutkan ke pembayaran.</p>
            <button onclick="submitFinalCheckout()" class="w-full py-4 bg-accent text-black font-bold rounded-2xl hover:bg-white transition">
                Bayar Sekarang
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
    let otpChannel = 'whatsapp'; // whatsapp then email
    let guestData = {};
    let otpCountdown = 0;
    let otpTimerInterval = null;

    function formatPrice(price) {
        return 'Rp ' + price.toLocaleString('id-ID');
    }

    function updatePrice() {
        const poinDiscount = selectedPoin * 100;
        currentPrice = Math.max(0, basePrice - voucherDiscount - poinDiscount);

        document.getElementById('totalPrice').textContent = formatPrice(currentPrice);
        document.getElementById('poinAmountInput').value = selectedPoin;

        if (selectedPoin > 0) {
            document.getElementById('poinDiscountRow').classList.remove('hidden');
            document.getElementById('poinDiscountRow').classList.add('flex');
            document.getElementById('poinDiscountAmount').textContent = '-' + formatPrice(poinDiscount);
        } else {
            document.getElementById('poinDiscountRow').classList.add('hidden');
            document.getElementById('poinDiscountRow').classList.remove('flex');
        }

        if (voucherDiscount > 0) {
            document.getElementById('voucherDiscountRow').classList.remove('hidden');
            document.getElementById('voucherDiscountRow').classList.add('flex');
            document.getElementById('voucherDiscountAmount').textContent = '-' + formatPrice(voucherDiscount);
        } else {
            document.getElementById('voucherDiscountRow').classList.add('hidden');
            document.getElementById('voucherDiscountRow').classList.remove('flex');
        }

        const payButton = document.getElementById('payButton');
        const isLoggedIn = <?= session()->get('isLoggedIn') ? 'true' : 'false' ?>;

        if (payButton) {
            if (!isLoggedIn) {
                payButton.innerHTML = '<i class="fas fa-shield-alt mr-2"></i> Verifikasi Data & Bayar ' + (currentPrice === 0 ? 'Gratis' : formatPrice(currentPrice));
            } else {
                if (currentPrice === 0) {
                    payButton.innerHTML = '<i class="fas fa-check mr-2"></i> Bayar Gratis';
                } else {
                    payButton.innerHTML = '<i class="fas fa-lock mr-2"></i> Bayar ' + formatPrice(currentPrice);
                }
            }
        }
    }

    // Guest Verification Flow
    async function startGuestVerification() {
        const name = document.getElementsByName('guest_name')[0].value.trim();
        const email = document.getElementsByName('guest_email')[0].value.trim();
        const phone = document.getElementsByName('guest_phone')[0].value.trim();

        if (!name || !email || !phone) {
            alert('Silakan lengkapi data diri Anda terlebih dahulu.');
            document.getElementsByName('guest_name')[0].focus();
            return;
        }

        if (!email.includes('@')) {
            alert('Format email tidak valid.');
            return;
        }

        guestData = {
            name,
            email,
            phone
        };
        otpChannel = 'whatsapp'; // Always start with whatsapp

        const btn = document.getElementById('payButton');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyiapkan...';

        // Try sending first OTP (WhatsApp)
        const success = await sendCheckoutOtp('whatsapp');

        btn.disabled = false;
        updatePrice();

        if (success) {
            openCheckoutOtpModal();
        }
    }

    async function sendCheckoutOtp(channel) {
        otpChannel = channel;
        const errorEl = document.getElementById('otpCheckError');
        if (errorEl) errorEl.classList.add('hidden');

        try {
            const response = await fetch('<?= base_url('checkout/send-otp') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
                },
                body: JSON.stringify({
                    name: guestData.name,
                    email: guestData.email,
                    phone: guestData.phone,
                    channel: channel
                })
            });

            const result = await response.json();
            if (result.success) {
                updateOtpModalUI();
                startOtpTimer();
                if (result.otp_dev) console.log('OTP (Dev):', result.otp_dev);
                return true;
            } else {
                if (result.login_required) {
                    const existsModal = document.getElementById('accountExistsModal');
                    const existsMsg = document.getElementById('accountExistsMessage');
                    if (existsModal && existsMsg) {
                        existsMsg.textContent = result.message;
                        existsModal.classList.remove('hidden');
                        existsModal.classList.add('flex');
                    } else {
                        alert(result.message);
                    }
                } else {
                    alert(result.message || 'Gagal mengirim OTP');
                }
                return false;
            }
        } catch (e) {
            console.error(e);
            alert('Terjadi kesalahan koneksi.');
            return false;
        }
    }

    function updateOtpModalUI() {
        const icon = document.getElementById('otpStepIcon');
        const title = document.getElementById('otpStepTitle');
        const target = document.getElementById('otpStepTarget');
        const progress = document.getElementById('otpProgress');

        if (otpChannel === 'whatsapp') {
            icon.className = 'fab fa-whatsapp text-accent text-3xl';
            title.textContent = 'Verifikasi WhatsApp';
            target.textContent = guestData.phone;
            progress.style.width = '30%';
        } else {
            icon.className = 'fas fa-envelope text-accent text-3xl';
            title.textContent = 'Verifikasi Email';
            target.textContent = guestData.email;
            progress.style.width = '60%';
        }

        // Clear inputs
        document.querySelectorAll('.otp-check-input').forEach(i => i.value = '');
        setTimeout(() => document.querySelector('.otp-check-input').focus(), 300);
    }

    async function verifyCheckOtp() {
        const otp = Array.from(document.querySelectorAll('.otp-check-input')).map(i => i.value).join('');
        if (otp.length < 6) return;

        const btn = document.getElementById('verifyCheckOtpBtn');
        const errorEl = document.getElementById('otpCheckError');

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Verifikasi...';
        errorEl.classList.add('hidden');

        try {
            const response = await fetch('<?= base_url('checkout/verify-otp') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
                },
                body: JSON.stringify({
                    email: guestData.email,
                    phone: guestData.phone,
                    otp: otp,
                    channel: otpChannel
                })
            });

            const result = await response.json();
            if (result.success) {
                if (result.all_verified) {
                    // Show Success
                    document.getElementById('otpStep1').classList.add('hidden');
                    document.getElementById('otpSuccess').classList.remove('hidden');
                    document.getElementById('otpProgress').style.width = '100%';
                } else {
                    // Verified WhatsApp, show cool intermediate success screen
                    document.getElementById('otpStep1').classList.add('hidden');
                    document.getElementById('otpWaSuccess').classList.remove('hidden');
                    document.getElementById('otpProgress').style.width = '50%';
                }
            } else {
                errorEl.textContent = result.message || 'OTP salah';
                errorEl.classList.remove('hidden');
            }
        } catch (e) {
            errorEl.textContent = 'Gagal terhubung ke server.';
            errorEl.classList.remove('hidden');
        }

        btn.disabled = false;
        btn.innerHTML = 'Verifikasi';
    }

    async function continueToEmailVerification() {
        document.getElementById('otpWaSuccess').classList.add('hidden');
        document.getElementById('otpStep1').classList.remove('hidden');
        await sendCheckoutOtp('email');
    }

    function startOtpTimer() {
        otpCountdown = 60;
        const btn = document.getElementById('resendCheckBtn');
        const timer = document.getElementById('otpTimer');

        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');

        if (otpTimerInterval) clearInterval(otpTimerInterval);

        otpTimerInterval = setInterval(() => {
            otpCountdown--;
            timer.textContent = otpCountdown;
            if (otpCountdown <= 0) {
                clearInterval(otpTimerInterval);
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
                timer.parentElement.textContent = 'Kirim Ulang';
            }
        }, 1000);
    }

    function openCheckoutOtpModal() {
        const modal = document.getElementById('checkoutOtpModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeCheckoutOtpModal() {
        const modal = document.getElementById('checkoutOtpModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    function submitFinalCheckout() {
        const form = document.getElementById('checkoutForm');
        if (form.dataset.submitting) return;
        form.dataset.submitting = true;

        // Disable button visually
        const btn = document.querySelector('button[onclick="submitFinalCheckout()"]');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
        }

        form.submit();
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

            const usePoinSection = document.getElementById('usePoinSection');
            if (usePoinSection) {
                if (this.value === 'poin') {
                    usePoinSection.classList.add('hidden');
                    selectedPoin = 0;
                    updatePrice();
                } else {
                    usePoinSection.classList.remove('hidden');
                }
            }
        });
    });

    // Use poin checkbox
    const usePoinCheckbox = document.getElementById('usePoinCheckbox');
    if (usePoinCheckbox) {
        usePoinCheckbox.addEventListener('change', function() {
            const sliderSection = document.getElementById('poinSliderSection');
            if (this.checked) {
                sliderSection.classList.remove('hidden');
                const slider = document.getElementById('poinSlider');
                selectedPoin = parseInt(slider.value);
                updatePrice();
            } else {
                sliderSection.classList.add('hidden');
                selectedPoin = 0;
                updatePrice();
            }
        });
    }

    // Poin slider
    const poinSlider = document.getElementById('poinSlider');
    if (poinSlider) {
        poinSlider.addEventListener('input', function() {
            selectedPoin = parseInt(this.value);
            const poinValue = selectedPoin * 100;
            document.getElementById('poinSliderValue').textContent =
                selectedPoin.toLocaleString('id-ID') + ' poin = ' + formatPrice(poinValue);
            updatePrice();
        });
    }

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
    const payButton = document.getElementById('payButton');

    function checkAgreements() {
        if (payButton && agreementPerjanjian && agreementRisiko) {
            let isAllChecked = agreementPerjanjian.checked && agreementRisiko.checked;
            if (agreementProfil) {
                isAllChecked = isAllChecked && agreementProfil.checked;
            }
            payButton.disabled = !isAllChecked;
        }
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

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        const checkedRadio = document.querySelector('input[name="payment_method"]:checked');
        if (checkedRadio) checkedRadio.dispatchEvent(new Event('change'));
        updatePrice();

        // Ensure pay button is disabled initially
        checkAgreements();
    });
</script>
<?= $this->endSection() ?>
