<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section class="pt-32 pb-16 min-h-screen">
    <div class="container mx-auto px-6 max-w-lg">
        
        <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-500/20 border border-green-500/30 text-green-400 px-4 py-3 rounded-xl mb-6">
            <?= session()->getFlashdata('success') ?>
        </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl mb-6">
            <?= session()->getFlashdata('error') ?>
        </div>
        <?php endif; ?>

        <div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-accent/20 to-yellow-500/20 p-6 text-center border-b border-white/10">
                <div class="w-16 h-16 bg-accent/30 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-gift text-accent text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold mb-2">Anda Menerima Berbagi Poin</h1>
                <p class="text-gray-400">Dari <?= esc($sharing['sender_name']) ?></p>
            </div>

            <!-- Details -->
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="bg-black/50 rounded-xl p-4">
                        <p class="text-gray-500 mb-1">Hari/Tanggal</p>
                        <p class="font-medium"><?= date('l, d F Y', strtotime($sharing['created_at'])) ?></p>
                    </div>
                    <div class="bg-black/50 rounded-xl p-4">
                        <p class="text-gray-500 mb-1">Jam</p>
                        <p class="font-medium"><?= date('H:i', strtotime($sharing['created_at'])) ?> WITA</p>
                    </div>
                    <div class="bg-black/50 rounded-xl p-4">
                        <p class="text-gray-500 mb-1">Nomor Pengirim</p>
                        <p class="font-medium"><?= esc($sharing['sender_phone'] ?? '-') ?></p>
                    </div>
                    <div class="bg-black/50 rounded-xl p-4">
                        <p class="text-gray-500 mb-1">Nama</p>
                        <p class="font-medium"><?= esc($sharing['sender_name']) ?></p>
                    </div>
                </div>

                <!-- Poin Amount -->
                <div class="bg-gradient-to-r from-yellow-500/20 to-orange-500/20 rounded-xl p-6 text-center border border-yellow-500/30">
                    <p class="text-gray-400 mb-2">Jumlah Poin</p>
                    <p class="text-4xl font-bold text-yellow-400"><?= number_format($sharing['poin_amount']) ?> <span class="text-xl">Poin</span></p>
                    <p class="text-gray-400 mt-2">Harga: <span class="text-white font-bold">Rp <?= number_format($sharing['price'], 0, ',', '.') ?></span></p>
                </div>

                <?php if (!$sharing['receiver_id']): ?>
                    <!-- Not claimed yet -->
                    <?php if ($user): ?>
                        <form action="<?= base_url('receive-poin/' . $sharing['share_code'] . '/claim') ?>" method="post">
                            <?= csrf_field() ?>
                            <button type="submit" class="w-full py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                                <i class="fas fa-hand-holding-heart mr-2"></i> Klaim Poin Ini
                            </button>
                        </form>
                    <?php else: ?>
                        <a href="<?= base_url('login?redirect=receive-poin/' . $sharing['share_code']) ?>" 
                            class="block w-full py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-center">
                            <i class="fas fa-sign-in-alt mr-2"></i> Login untuk Klaim
                        </a>
                        <p class="text-center text-gray-500 text-sm">
                            Belum punya akun? <a href="<?= base_url('register') ?>" class="text-accent hover:underline">Daftar</a>
                        </p>
                    <?php endif; ?>

                <?php elseif ($sharing['status'] === 'pending' && $sharing['receiver_id'] == ($user['id'] ?? 0)): ?>
                    <!-- Claimed, waiting for payment -->
                    <div class="bg-blue-500/10 border border-blue-500/30 rounded-xl p-4">
                        <h3 class="font-bold text-blue-400 mb-3"><i class="fas fa-university mr-2"></i>Rekening Transfer</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Bank</span>
                                <span class="font-medium"><?= esc($sharing['bank_name']) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Nama Rekening</span>
                                <span class="font-medium"><?= esc($sharing['bank_account_name']) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Nomor Rekening</span>
                                <span class="font-medium font-mono"><?= esc($sharing['bank_account_number']) ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Proof -->
                    <form action="<?= base_url('receive-poin/' . $sharing['share_code'] . '/upload-proof') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <div class="mb-4">
                            <label class="block text-sm text-gray-400 mb-2">Unggah Bukti Transfer</label>
                            <input type="file" name="transfer_proof" accept="image/*" required
                                class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-accent/20 file:text-accent file:cursor-pointer">
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Maks 2MB</p>
                        </div>
                        <button type="submit" class="w-full py-4 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                            <i class="fas fa-upload mr-2"></i> Upload Bukti Transfer
                        </button>
                    </form>

                <?php elseif ($sharing['status'] === 'paid'): ?>
                    <!-- Waiting verification -->
                    <div class="bg-orange-500/10 border border-orange-500/30 rounded-xl p-6 text-center">
                        <i class="fas fa-hourglass-half text-orange-400 text-3xl mb-3"></i>
                        <h3 class="font-bold text-orange-400 mb-2">Menunggu Verifikasi</h3>
                        <p class="text-gray-400 text-sm">Bukti transfer Anda sedang diperiksa oleh pengirim. Poin akan masuk setelah diverifikasi.</p>
                    </div>

                <?php elseif ($sharing['status'] === 'verified'): ?>
                    <!-- Verified -->
                    <div class="bg-green-500/10 border border-green-500/30 rounded-xl p-6 text-center">
                        <i class="fas fa-check-circle text-green-400 text-3xl mb-3"></i>
                        <h3 class="font-bold text-green-400 mb-2">Poin Berhasil Diterima!</h3>
                        <p class="text-gray-400 text-sm"><?= number_format($sharing['poin_amount']) ?> poin telah masuk ke akun Anda.</p>
                        <a href="<?= base_url('user/poin') ?>" class="inline-block mt-4 px-6 py-2 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                            Lihat Saldo Poin
                        </a>
                    </div>

                <?php elseif ($sharing['status'] === 'rejected'): ?>
                    <!-- Rejected -->
                    <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-6 text-center">
                        <i class="fas fa-times-circle text-red-400 text-3xl mb-3"></i>
                        <h3 class="font-bold text-red-400 mb-2">Pembayaran Ditolak</h3>
                        <p class="text-gray-400 text-sm"><?= esc($sharing['notes'] ?? 'Bukti transfer tidak valid') ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
