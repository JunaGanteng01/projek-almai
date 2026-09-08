<!-- Pricing & Partners -->
<div class="bg-[#111] border border-white/10 rounded-xl p-6 mb-6">
    <h3 class="font-bold mb-4">Harga & Poin</h3>
    <div class="space-y-4">
        <div class="flex items-center gap-3 mb-2">
            <input type="checkbox" name="has_packages" id="hasPackages" value="1" class="w-5 h-5 rounded bg-[#0a0a0a] border-white/10 text-accent">
            <label for="hasPackages" class="text-sm font-medium">Aktifkan Paket Harga</label>
        </div>
        
        <div id="singlePriceField" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Harga Jual (IDR)</label>
                    <input type="number" name="price" id="priceInput" value="0" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
                </div>
                <div>
                    <label class="block text-sm text-gray-400 mb-2">Harga Poin</label>
                    <input type="number" name="poin_price" id="poinPrice" value="0" readonly class="w-full bg-[#0a0a0a]/50 text-gray-500 cursor-not-allowed border border-white/10 rounded-lg px-4 py-3">
                </div>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Harga Diskon (Original)</label>
                <input type="number" name="original_price" class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3">
            </div>
        </div>

        <div id="multiPackageField" class="hidden space-y-4">
            <div id="packagesContainer" class="space-y-3"></div>
            <button type="button" onclick="addPackageRow()" class="w-full py-2 border border-dashed border-white/20 rounded-lg text-xs text-gray-400 hover:text-white transition">
                <i class="fas fa-plus mr-1"></i> Tambah Paket
            </button>
        </div>
    </div>

    <div class="border-t border-white/10 pt-4 mt-6">
        <h3 class="font-bold mb-1">Akses & Keamanan</h3>
        <input type="hidden" name="requires_activation_code" value="0">
        <div class="flex items-center gap-3 mb-2">
            <input type="checkbox" name="requires_activation_code" id="requiresActivationCode" value="1" <?= (isset($layanan) && isset($layanan['requires_activation_code']) && $layanan['requires_activation_code']) ? 'checked' : '' ?> class="w-5 h-5 rounded bg-[#0a0a0a] border-white/10 text-accent cursor-pointer">
            <label for="requiresActivationCode" class="text-sm font-medium cursor-pointer">Gunakan Aktivasi Kode untuk Membuka Layanan</label>
        </div>
        <p class="text-xs text-gray-500 mb-6">Jika diaktifkan, pengguna akan mendapatkan kode aktivasi setelah pembayaran untuk mengklaim/membuka layanan secara mandiri.</p>
    </div>

    <div class="border-t border-white/10 pt-4 mt-6">
        <h3 class="font-bold mb-1">Skema Referal</h3>
        <p class="text-xs text-gray-500 mb-4">Atur distribusi komisi referral. Pool dihitung otomatis dari harga × %. Kedalaman distribusi tetap 8 level.</p>

        <!-- Hidden fields — auto-calculated by JS before submit -->
        <input type="hidden" name="referral_user_cash" id="calc_referral_user_cash" value="0">
        <input type="hidden" name="referral_user_poin" id="calc_referral_user_poin" value="0">
        <input type="hidden" name="referral_max_depth" value="8">

        <!-- Toggle Aktifkan Referral -->
        <div class="flex items-center gap-3 mb-4">
            <input type="checkbox" id="enableReferral"
                class="w-5 h-5 rounded bg-[#0a0a0a] border-white/10 text-accent cursor-pointer"
                onchange="toggleReferralSection(this.checked)">
            <label for="enableReferral" class="text-sm font-medium cursor-pointer">Aktifkan Skema Referral</label>
        </div>

        <div id="referralSection" class="hidden">
            <div class="mb-4 max-w-xs">
                <label class="block text-sm text-gray-400 mb-2">Distribusi per Level (%)</label>
                <div class="relative">
                    <input type="number" step="0.01" min="0" max="100" id="referral_dist_pct" name="referral_distribution_percentage" value="25"
                        class="w-full bg-[#0a0a0a] border border-white/10 rounded-lg px-4 py-3 pr-10"
                        oninput="updateReferralPreview()">
                    <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm">%</span>
                </div>
                <p class="text-[10px] text-gray-600 mt-1">Berapa % dari harga yang jadi total pool referral (maks 8 level)</p>
            </div>

            <!-- Live Preview -->
            <div id="referralPreviewWrap" class="bg-[#0a0a0a] border border-white/10 rounded-xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <p class="text-xs font-bold text-gray-300 uppercase tracking-widest flex items-center gap-2">
                        <i class="fas fa-eye text-accent"></i> Preview Distribusi
                    </p>
                    <span class="text-[10px] text-gray-500">Pool = Harga × Distribusi%</span>
                </div>
                <div id="referralPreviewTable" class="space-y-3 text-xs">
                    <!-- Rendered by JS -->
                </div>
            </div>
        </div>

        <!-- Hidden input saat referral dinonaktifkan -->
        <input type="hidden" id="referral_dist_pct_disabled" name="referral_distribution_percentage" value="0">
    </div>
</div>
