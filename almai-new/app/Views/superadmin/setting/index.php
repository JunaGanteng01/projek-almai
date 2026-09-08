<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>
<section class="p-0">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-accent/20 border border-accent/30 text-accent px-4 py-3 rounded-xl mb-4"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <form action="<?= base_url('superadmin/setting/update') ?>" method="post">
        <?= csrf_field() ?>
        
        <div class="grid md:grid-cols-2 gap-4 md:gap-6">
            <!-- General Settings -->
            <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6">
                <h3 class="font-bold mb-4 flex items-center gap-2"><i class="fas fa-globe text-accent"></i> Informasi Website</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs md:text-sm text-gray-400 mb-2">Nama Website</label>
                        <input type="text" name="site_name" value="<?= esc($settings['site_name'] ?? 'ALMAI') ?>" class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm">
                    </div>
                    <div>
                        <label class="block text-xs md:text-sm text-gray-400 mb-2">Deskripsi</label>
                        <textarea name="site_description" rows="3" class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm resize-none"><?= esc($settings['site_description'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <!-- Contact Settings -->
            <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6">
                <h3 class="font-bold mb-4 flex items-center gap-2"><i class="fas fa-phone text-accent"></i> Kontak</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs md:text-sm text-gray-400 mb-2">WhatsApp CS</label>
                        <input type="text" name="whatsapp_cs" value="<?= esc($settings['whatsapp_cs'] ?? '6285183231800') ?>" class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm" placeholder="6281234567890">
                    </div>
                    <div>
                        <label class="block text-xs md:text-sm text-gray-400 mb-2">Email CS</label>
                        <input type="email" name="email_cs" value="<?= esc($settings['email_cs'] ?? '') ?>" class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm" placeholder="cs@almai.id">
                    </div>
                </div>
            </div>

            <!-- Social Media -->
            <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6">
                <h3 class="font-bold mb-4 flex items-center gap-2"><i class="fas fa-share-alt text-accent"></i> Social Media</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs md:text-sm text-gray-400 mb-2">Instagram</label>
                        <input type="text" name="instagram" value="<?= esc($settings['instagram'] ?? '') ?>" class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm" placeholder="@almai.id">
                    </div>
                    <div>
                        <label class="block text-xs md:text-sm text-gray-400 mb-2">YouTube</label>
                        <input type="text" name="youtube" value="<?= esc($settings['youtube'] ?? '') ?>" class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm" placeholder="https://youtube.com/@almai">
                    </div>
                    <div>
                        <label class="block text-xs md:text-sm text-gray-400 mb-2">Telegram</label>
                        <input type="text" name="telegram" value="<?= esc($settings['telegram'] ?? '') ?>" class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm" placeholder="@almai_official">
                    </div>
                </div>
            </div>

            <!-- Poin & MLM Settings -->
            <div class="bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6">
                <h3 class="font-bold mb-4 flex items-center gap-2"><i class="fas fa-coins text-accent"></i> Pengaturan Poin</h3>
                <div class="space-y-4">

                    <!-- User Bonuses -->
                    <div>
                        <h4 class="text-accent text-sm font-bold mb-3">Bonus User</h4>
                        <div class="grid gap-4">
                            <div>
                                <label class="block text-xs md:text-sm text-gray-400 mb-2">Bonus Welcome Poin (User Baru)</label>
                                <div class="relative">
                                    <input type="number" name="poin_new_user_referral_bonus" value="<?= esc($settings['poin_new_user_referral_bonus'] ?? '500') ?>" class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm" placeholder="500">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">poin</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Diberikan saat user mendaftar menggunakan kode referral</p>
                            </div>
                            <div>
                                <label class="block text-xs md:text-sm text-gray-400 mb-2">Bonus Ulasan Poin</label>
                                <div class="relative">
                                    <input type="number" name="poin_review_bonus" value="<?= esc($settings['poin_review_bonus'] ?? '100') ?>" class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm" placeholder="100">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">poin</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Diberikan saat user memberikan ulasan/review produk</p>
                            </div>
                            <div>
                                <label class="block text-xs md:text-sm text-gray-400 mb-2">Bonus Ajak Teman (Referrer)</label>
                                <div class="relative">
                                    <input type="number" name="poin_referral_registration" value="<?= esc($settings['poin_referral_registration'] ?? '1000') ?>" class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm" placeholder="1000">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">poin</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Diberikan ke Referrer saat teman yang diajak mendaftar (diluar komisi pembelian)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Other Commissions -->
                     <div class="border-t border-white/10 pt-4 mt-4">
                        <h4 class="text-accent text-sm font-bold mb-3">Lainnya</h4>
                        <div class="grid gap-4">

                             <div>
                                <label class="block text-xs md:text-sm text-gray-400 mb-2">Minimum Redeem Poin</label>
                                <div class="relative">
                                    <input type="number" name="poin_minimum_redeem" value="<?= esc($settings['poin_minimum_redeem'] ?? '10000') ?>" class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm" placeholder="10000">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">poin</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Poin Info Card -->
            <div class="bg-accent/10 border border-accent/30 rounded-xl md:rounded-2xl p-4 md:p-6 h-fit">
                <h3 class="font-bold mb-4 flex items-center gap-2 text-accent"><i class="fas fa-info-circle"></i> Sistem Poin</h3>
                <ul class="space-y-3 text-sm text-gray-300">
                    <li class="flex items-start gap-2">
                        <i class="fas fa-share-alt text-accent mt-1"></i>
                        <span><strong>Skema Referral:</strong> Distribusi komisi diatur per layanan (% dari harga), dengan decay 50% per level hingga 8 level. Konfigurasi ada di masing-masing layanan.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fas fa-gift text-yellow-500 mt-1"></i>
                        <span><strong>Welcome Poin:</strong> User baru mendapat poin saat mendaftar lewat link referral.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <i class="fas fa-star text-blue-400 mt-1"></i>
                        <span><strong>Ulasan Poin:</strong> User mendapat poin setelah memberikan review produk.</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                <i class="fas fa-save mr-2"></i> Simpan Settings
            </button>
        </div>
    </form>

    <!-- Voucher Section -->
    <div class="mt-6 bg-[#111] border border-white/10 rounded-xl md:rounded-2xl p-4 md:p-6">
        <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-4">
            <h3 class="font-bold flex items-center gap-2 text-center sm:text-left"><i class="fas fa-ticket-alt text-accent"></i> Voucher Codes</h3>
            <a href="<?= base_url('superadmin/setting/vouchers') ?>" class="w-full sm:w-auto px-4 py-2 bg-accent/20 text-accent rounded-xl hover:bg-accent hover:text-black transition text-sm text-center">
                Kelola Voucher <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
        <p class="text-gray-500 text-sm text-center sm:text-left">Kelola voucher diskon untuk transaksi.</p>
    </div>
</section>
<?= $this->endSection() ?>
