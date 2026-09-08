            <!-- Kelas Section -->
            <section id="section-kelas" class="p-4 md:p-8 hidden">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                    <div>
                        <h2 class="text-lg md:text-xl font-bold">Kelas Saya</h2>
                        <p class="text-gray-500 text-sm">Kelas yang sudah Anda beli</p>
                    </div>
                    <a href="<?= base_url('kelas') ?>" class="px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-sm">
                        <i class="fas fa-plus mr-2"></i> Beli Kelas Baru
                    </a>
                </div>
                <?php if (empty($myKelas)): ?>
                <div class="bg-[#111] border border-white/10 rounded-xl p-12 text-center">
                    <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-graduation-cap text-gray-600 text-3xl"></i>
                    </div>
                    <h3 class="font-bold mb-2">Belum Ada Kelas</h3>
                    <p class="text-gray-500 text-sm mb-4">Anda belum membeli kelas apapun</p>
                    <a href="<?= base_url('kelas') ?>" class="inline-block px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-sm">Jelajahi Kelas</a>
                </div>
                <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                    <?php foreach ($myKelas as $kelas): ?>
                    <div class="bg-[#111] border border-white/10 rounded-xl overflow-hidden hover:border-accent/50 transition">
                        <img src="<?= esc($kelas['thumbnail'] ?? 'https://via.placeholder.com/400x200') ?>" class="w-full h-40 object-cover">
                        <div class="p-4">
                            <h3 class="font-bold mb-2"><?= esc($kelas['title'] ?? $kelas['product_name']) ?></h3>
                            <p class="text-gray-500 text-sm mb-4">Dibeli: <?= date('d M Y', strtotime($kelas['created_at'])) ?></p>
                            <a href="<?= base_url('user/belajar/' . $kelas['kelas_id']) ?>" class="block text-center py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-sm">
                                <i class="fas fa-play mr-2"></i> Mulai Belajar
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </section>

            <!-- Transaksi Section -->
            <section id="section-transaksi" class="p-4 md:p-8 hidden">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                    <div>
                        <h2 class="text-lg md:text-xl font-bold">Riwayat Transaksi</h2>
                        <p class="text-gray-500 text-sm">Semua pembelian Anda</p>
                    </div>
                </div>
                <?php if (empty($transaksiList)): ?>
                <div class="bg-[#111] border border-white/10 rounded-xl p-12 text-center">
                    <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-receipt text-gray-600 text-3xl"></i>
                    </div>
                    <h3 class="font-bold mb-2">Belum Ada Transaksi</h3>
                    <p class="text-gray-500 text-sm">Anda belum memiliki riwayat transaksi</p>
                </div>
                <?php else: ?>
                <div class="bg-[#111] border border-white/10 rounded-xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[600px]">
                            <thead class="bg-black/50">
                                <tr>
                                    <th class="text-left px-4 md:px-6 py-3 text-xs font-medium text-gray-400">Invoice</th>
                                    <th class="text-left px-4 md:px-6 py-3 text-xs font-medium text-gray-400">Produk</th>
                                    <th class="text-left px-4 md:px-6 py-3 text-xs font-medium text-gray-400">Total</th>
                                    <th class="text-left px-4 md:px-6 py-3 text-xs font-medium text-gray-400">Tanggal</th>
                                    <th class="text-left px-4 md:px-6 py-3 text-xs font-medium text-gray-400">Status</th>
                                    <th class="text-left px-4 md:px-6 py-3 text-xs font-medium text-gray-400">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transaksiList as $trx): ?>
                                <?php
                                $statusColors = ['pending' => 'bg-yellow-500/20 text-yellow-500', 'paid' => 'bg-blue-500/20 text-blue-500', 'confirmed' => 'bg-accent/20 text-accent', 'cancelled' => 'bg-red-500/20 text-red-500'];
                                ?>
                                <tr class="border-t border-white/5">
                                    <td class="px-4 md:px-6 py-4 text-sm font-mono text-accent"><?= esc($trx['invoice_number']) ?></td>
                                    <td class="px-4 md:px-6 py-4 text-sm"><?= esc($trx['product_name']) ?></td>
                                    <td class="px-4 md:px-6 py-4 text-sm">Rp <?= number_format($trx['total'], 0, ',', '.') ?></td>
                                    <td class="px-4 md:px-6 py-4 text-sm text-gray-400"><?= date('d M Y', strtotime($trx['created_at'])) ?></td>
                                    <td class="px-4 md:px-6 py-4"><span class="px-3 py-1 rounded-full text-xs font-bold <?= $statusColors[$trx['status']] ?? 'bg-gray-500/20 text-gray-500' ?>"><?= strtoupper($trx['status']) ?></span></td>
                                    <td class="px-4 md:px-6 py-4"><a href="<?= base_url('user/invoice/' . $trx['invoice_number']) ?>" class="text-accent text-sm hover:underline">Detail</a></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
            </section>

            <!-- Sertifikat Section -->
            <section id="section-sertifikat" class="p-4 md:p-8 hidden">
                <div class="mb-6">
                    <h2 class="text-lg md:text-xl font-bold">Sertifikat Saya</h2>
                    <p class="text-gray-500 text-sm">Sertifikat yang Anda peroleh setelah menyelesaikan kelas</p>
                </div>
                <div class="bg-[#111] border border-white/10 rounded-xl p-12 text-center">
                    <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-certificate text-gray-600 text-3xl"></i>
                    </div>
                    <h3 class="font-bold mb-2">Belum Ada Sertifikat</h3>
                    <p class="text-gray-500 text-sm mb-4">Selesaikan kelas untuk mendapatkan sertifikat</p>
                    <a href="<?= base_url('kelas') ?>" class="inline-block px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-sm">Jelajahi Kelas</a>
                </div>
            </section>

            <!-- Poin Section -->
            <section id="section-poin" class="p-4 md:p-8 hidden">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                    <div>
                        <h2 class="text-lg md:text-xl font-bold">ALMAI Poin</h2>
                        <p class="text-gray-500 text-sm">Kumpulkan dan tukarkan poin Anda</p>
                    </div>
                </div>

                <!-- Points Overview -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6">
                    <div class="bg-gradient-to-br from-yellow-500/20 to-orange-500/20 border border-yellow-500/30 rounded-xl p-4 md:p-6">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-coins text-yellow-500"></i>
                            <span class="text-xs text-gray-400">Total Poin</span>
                        </div>
                        <h3 class="text-2xl md:text-3xl font-bold text-yellow-500"><?= number_format($stats['totalPoin'] ?? 0) ?></h3>
                        <p class="text-xs text-gray-500 mt-1">= Rp <?= number_format(($stats['totalPoin'] ?? 0) * 1000, 0, ',', '.') ?></p>
                    </div>
                    <div class="bg-[#111] border border-white/10 rounded-xl p-4 md:p-6">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-arrow-up text-accent"></i>
                            <span class="text-xs text-gray-400">Diterima</span>
                        </div>
                        <h3 class="text-2xl md:text-3xl font-bold"><?= number_format($stats['poinEarned'] ?? 0) ?></h3>
                        <p class="text-xs text-gray-500 mt-1">Total poin masuk</p>
                    </div>
                    <div class="bg-[#111] border border-white/10 rounded-xl p-4 md:p-6">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-arrow-down text-red-400"></i>
                            <span class="text-xs text-gray-400">Digunakan</span>
                        </div>
                        <h3 class="text-2xl md:text-3xl font-bold"><?= number_format($stats['poinUsed'] ?? 0) ?></h3>
                        <p class="text-xs text-gray-500 mt-1">Total poin keluar</p>
                    </div>
                    <div class="bg-[#111] border border-white/10 rounded-xl p-4 md:p-6">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-users text-accent"></i>
                            <span class="text-xs text-gray-400">Referral</span>
                        </div>
                        <h3 class="text-2xl md:text-3xl font-bold"><?= $stats['totalReferral'] ?? 0 ?></h3>
                        <p class="text-xs text-gray-500 mt-1">Orang bergabung</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-3 gap-4 md:gap-6">
                    <!-- Referral Code Card -->
                    <div class="md:col-span-1">
                        <div class="bg-gradient-to-br from-accent/10 to-blue-500/10 border border-accent/30 rounded-xl p-6">
                            <div class="text-center mb-4">
                                <div class="w-16 h-16 bg-accent/20 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-gift text-accent text-2xl"></i>
                                </div>
                                <h3 class="font-bold">Kode Referral Anda</h3>
                                <p class="text-xs text-gray-500 mt-1">Bagikan dan dapatkan poin!</p>
                            </div>
                            <div class="bg-black/50 rounded-xl p-4 text-center mb-4">
                                <p class="text-2xl font-bold font-mono text-accent" id="referralCodeDisplay"><?= esc(session()->get('referralCode') ?? 'N/A') ?></p>
                            </div>
                            <button onclick="copyReferralCode()" class="w-full py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-sm">
                                <i class="fas fa-copy mr-2"></i> Copy Kode
                            </button>
                        </div>

                        <!-- How to Earn Points -->
                        <div class="bg-[#111] border border-white/10 rounded-xl p-6 mt-4">
                            <h4 class="font-bold mb-4 text-sm">Cara Dapat Poin</h4>
                            <div class="space-y-3">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 bg-accent/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-shopping-bag text-accent text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium">Setiap Pembelian</p>
                                        <p class="text-xs text-gray-500">1% dari total transaksi</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 bg-yellow-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-hand-holding-usd text-yellow-500 text-xs"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium">Referral Beli Produk</p>
                                        <p class="text-xs text-gray-500">Komisi dari pembelian referral</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Points History -->
                    <div class="md:col-span-2">
                        <div class="bg-[#111] border border-white/10 rounded-xl overflow-hidden">
                            <div class="p-4 border-b border-white/10">
                                <h4 class="font-bold">Riwayat Poin</h4>
                            </div>
                            <?php if (empty($poinHistory)): ?>
                            <div class="p-8 text-center">
                                <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-coins text-gray-600 text-2xl"></i>
                                </div>
                                <p class="text-gray-500 text-sm">Belum ada riwayat poin</p>
                            </div>
                            <?php else: ?>
                            <div class="overflow-x-auto max-h-[400px] overflow-y-auto">
                                <table class="w-full min-w-[500px]">
                                    <thead class="bg-black/50 sticky top-0">
                                        <tr>
                                            <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Tanggal</th>
                                            <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Keterangan</th>
                                            <th class="text-right px-4 py-3 text-xs font-medium text-gray-400">Poin</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($poinHistory as $poin): ?>
                                        <tr class="border-t border-white/5">
                                            <td class="px-4 py-3 text-sm text-gray-400"><?= date('d M Y', strtotime($poin['created_at'])) ?></td>
                                            <td class="px-4 py-3 text-sm"><?= esc($poin['description']) ?></td>
                                            <td class="px-4 py-3 text-sm text-right font-bold <?= $poin['type'] === 'earn' ? 'text-accent' : 'text-red-400' ?>">
                                                <?= $poin['type'] === 'earn' ? '+' : '-' ?><?= number_format($poin['amount']) ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Profile Section -->
            <section id="section-profile" class="p-4 md:p-8 hidden">
                <h2 class="text-lg md:text-xl font-bold mb-6">Profile Saya</h2>
                <div class="grid md:grid-cols-3 gap-4 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="bg-[#111] border border-white/10 rounded-xl p-6 text-center">
                            <div class="w-24 h-24 bg-accent/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-user text-accent text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-bold"><?= esc(session()->get('userName')) ?></h3>
                            <p class="text-gray-500 text-sm"><?= esc(session()->get('userEmail')) ?></p>
                            <div class="mt-2">
                                <span class="px-3 py-1 bg-accent/20 text-accent rounded-full text-xs">Member</span>
                            </div>
                        </div>

                        <!-- Referral Card -->
                        <div class="bg-[#111] border border-white/10 rounded-xl p-6 mt-4">
                            <h4 class="font-bold mb-3 text-sm">Kode Referral Anda</h4>
                            <div class="flex items-center gap-2">
                                <input type="text" value="<?= esc(session()->get('referralCode') ?? 'N/A') ?>" readonly class="flex-1 px-4 py-2 bg-black border border-white/20 rounded-lg text-sm font-mono">
                                <button onclick="copyReferralCode()" class="px-4 py-2 bg-accent text-black rounded-lg hover:bg-white transition">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Bagikan dan dapatkan komisi</p>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <div class="bg-[#111] border border-white/10 rounded-xl p-6">
                            <h3 class="font-bold mb-4">Edit Profile</h3>
                            <form action="<?= base_url('user/profile/update') ?>" method="POST" class="space-y-4">
                                <?= csrf_field() ?>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs text-gray-400 mb-2">Nama Lengkap</label>
                                        <input type="text" name="name" value="<?= esc($user['name'] ?? session()->get('userName')) ?>" class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-400 mb-2">Email</label>
                                        <input type="email" value="<?= esc($user['email'] ?? session()->get('userEmail')) ?>" readonly class="w-full px-4 py-3 bg-black/50 border border-white/10 rounded-xl text-sm text-gray-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-400 mb-2">No. WhatsApp</label>
                                    <input type="tel" name="phone" value="<?= esc($user['phone'] ?? '') ?>" class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm">
                                </div>
                                <button type="submit" class="w-full py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-sm">Simpan Perubahan</button>
                            </form>
                        </div>
                        <!-- Change Password -->
                        <div class="bg-[#111] border border-white/10 rounded-xl p-6 mt-4">
                            <h3 class="font-bold mb-4">Ubah Password</h3>
                            <form action="<?= base_url('user/profile/password') ?>" method="POST" class="space-y-4">
                                <?= csrf_field() ?>
                                <div>
                                    <label class="block text-xs text-gray-400 mb-2">Password Lama</label>
                                    <input type="password" name="old_password" class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm" placeholder="••••••••">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs text-gray-400 mb-2">Password Baru</label>
                                        <input type="password" name="new_password" class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm" placeholder="••••••••">
                                    </div>
                                    <div>
                                        <label class="block text-xs text-gray-400 mb-2">Konfirmasi Password</label>
                                        <input type="password" name="confirm_password" class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-sm" placeholder="••••••••">
                                    </div>
                                </div>
                                <button type="submit" class="w-full py-3 border border-white/20 rounded-xl hover:border-accent hover:text-accent transition text-sm">Ubah Password</button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Copy Toast -->
    <div id="copyToast" class="hidden fixed bottom-20 md:bottom-4 left-1/2 -translate-x-1/2 px-6 py-3 bg-accent text-black font-bold rounded-full shadow-lg z-[110]">
        <i class="fas fa-check mr-2"></i> Berhasil disalin!
    </div>

    <script>
        // Section Navigation
        function showSection(section) {
            document.querySelectorAll('section[id^="section-"]').forEach(s => s.classList.add('hidden'));
            document.getElementById('section-' + section).classList.remove('hidden');
            
            // Update sidebar active state
            document.querySelectorAll('.sidebar-link').forEach(link => link.classList.remove('active'));
            document.querySelector(`.sidebar-link[href="#${section}"]`)?.classList.add('active');
            
            // Update bottom nav active state
            document.querySelectorAll('.bottom-nav-item').forEach(btn => btn.classList.remove('active'));
            document.querySelector(`.bottom-nav-item[data-section="${section}"]`)?.classList.add('active');
            
            // Update page title
            const titles = {
                'overview': 'Dashboard',
                'kelas': 'Kelas Saya',
                'transaksi': 'Riwayat Transaksi',
                'sertifikat': 'Sertifikat',
                'poin': 'ALMAI Poin',
                'profile': 'Profile'
            };
            document.getElementById('pageTitle').textContent = titles[section] || 'Dashboard';
            
            // Update URL hash
            window.location.hash = section;
        }

        // Profile Menu
        function toggleProfileMenu() {
            document.getElementById('profileMenu').classList.toggle('hidden');
        }
        function closeProfileMenu() {
            document.getElementById('profileMenu').classList.add('hidden');
        }
        function toggleMobileProfileMenu() {
            document.getElementById('mobileProfileMenu').classList.toggle('hidden');
        }
        function closeMobileProfileMenu() {
            document.getElementById('mobileProfileMenu').classList.add('hidden');
        }

        // Mobile Menu
        function showMobileMenu() {
            document.getElementById('mobileMoreMenu').classList.remove('hidden');
        }
        function closeMobileMenu() {
            document.getElementById('mobileMoreMenu').classList.add('hidden');
        }

        // More Actions Menu
        function showMobileMoreActions() {
            document.getElementById('moreActionsMenu').classList.toggle('hidden');
        }
        function hideMoreActions() {
            document.getElementById('moreActionsMenu').classList.add('hidden');
        }

        // Refresh Balance
        function refreshBalance() {
            const refreshIcon = document.querySelector('.fa-sync-alt');
            refreshIcon.classList.add('fa-spin');
            
            setTimeout(() => {
                refreshIcon.classList.remove('fa-spin');
            }, 1000);
        }

        // Copy Referral Code
        function copyReferralCode() {
            const code = document.getElementById('referralCodeDisplay')?.textContent || '<?= esc(session()->get('referralCode') ?? '') ?>';
            navigator.clipboard.writeText(code);
            showToast('Kode referral disalin!');
        }

        function showToast(message) {
            const toast = document.getElementById('copyToast');
            toast.innerHTML = `<i class="fas fa-check mr-2"></i> ${message}`;
            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.add('hidden'), 2000);
        }

        // Close menus when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#profileMenu') && !e.target.closest('button[onclick="toggleProfileMenu()"]')) {
                closeProfileMenu();
            }
            if (!e.target.closest('#mobileProfileMenu') && !e.target.closest('button[onclick="toggleMobileProfileMenu()"]')) {
                closeMobileProfileMenu();
            }
            if (!e.target.closest('#moreActionsMenu') && !e.target.closest('button[onclick="showMobileMoreActions()"]')) {
                hideMoreActions();
            }
        });

        // Handle URL hash on load
        document.addEventListener('DOMContentLoaded', function() {
            const hash = window.location.hash.replace('#', '');
            if (hash && document.getElementById('section-' + hash)) {
                showSection(hash);
            }
        });
    </script>
</body>
</html>
