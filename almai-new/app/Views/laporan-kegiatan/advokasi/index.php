<?= $this->extend('laporan-kegiatan/layouts/main') ?>
<?= $this->section('styles') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="space-y-8">
    <!-- Header Banner -->
    <div class="p-6 md:p-8 bg-gradient-to-br from-accent/20 to-transparent rounded-[2rem] border border-accent/10 relative overflow-hidden shadow-2xl">
        <div class="relative z-10">
            <h2 class="text-2xl md:text-3xl font-black text-white mb-2 uppercase tracking-tight">Pusat Advokasi Almai</h2>
            <p class="text-gray-400 text-sm max-w-lg leading-relaxed">
                Seluruh laporan investigasi dan pendampingan hukum Anda dikelola di sini. Mohon lengkapi detail bukti agar tim kami dapat segera memproses laporan Anda.
            </p>
        </div>
        <div class="absolute -right-10 -bottom-10 opacity-10">
            <i class="fas fa-shield-halved text-[150px] text-accent"></i>
        </div>
    </div>

    <?php 
    $isAuthorized = $user && (
        (($user['is_pro'] ?? 0) == 1) || 
        (($user['level_id'] ?? 0) >= 3) || // WPA/CWPA/Admin
        (in_array(($user['crm_role'] ?? ''), ['admin', 'wpa', 'cwpa']))
    );
    ?>

    <!-- Learning Section Banner -->
    <div class="p-[1px] bg-gradient-to-r from-accent/30 to-blue-500/30 rounded-[2rem] shadow-xl">
        <div class="bg-[#0b0b0b] rounded-[1.95rem] p-6 md:p-8 relative overflow-hidden">
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="text-center md:text-left">
                    <div class="flex items-center justify-center md:justify-start gap-2 mb-3">
                        <span class="inline-block px-2 py-0.5 bg-accent/20 text-accent text-[8px] font-black uppercase tracking-widest rounded-md">New Academy</span>
                        <div class="flex -space-x-1.5">
                            <div class="w-4 h-4 rounded-full border border-[#0b0b0b] bg-gray-600"></div>
                            <div class="w-4 h-4 rounded-full border border-[#0b0b0b] bg-gray-500"></div>
                        </div>
                    </div>
                    <h3 class="text-xl md:text-2xl font-black text-white mb-1 leading-tight tracking-tight uppercase">SILABUS KELAS ADVOKASI TRADING</h3>
                    <p class="text-gray-500 text-xs max-w-sm mb-6">Akses modul investigasi & perlindungan dana eksklusif.</p>
                    <div class="flex justify-center md:justify-start">
                        <a href="<?= base_url('user/advokasi/belajar') ?>" class="px-6 py-3 bg-accent text-black font-black uppercase tracking-widest rounded-2xl hover:bg-white transition-all text-[10px] shadow-lg flex items-center gap-2">
                            Mulai Belajar <i class="fas fa-arrow-right text-[8px]"></i>
                        </a>
                    </div>
                </div>
                <div class="hidden md:block w-36 relative">
                    <div class="aspect-square bg-accent/5 rounded-3xl border border-white/5 flex items-center justify-center relative overflow-hidden group">
                        <i class="fas fa-graduation-cap text-[40px] text-accent/20 group-hover:scale-110 transition-transform"></i>
                        <div class="absolute inset-0 bg-gradient-to-t from-[#0b0b0b] to-transparent"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Multimedia & Interactive Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Webinar Zoom -->
        <div class="group relative p-6 bg-gradient-to-br from-blue-600/10 to-transparent border border-blue-500/10 rounded-[2rem] hover:border-blue-500/30 transition-all duration-300">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 bg-blue-500/20 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fas fa-video text-blue-400 text-2xl"></i>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-1">Webinar Nasional Interaktif</h4>
                    <p class="text-gray-500 text-xs leading-relaxed mb-4 text-justify">Sesi Zoom eksklusif bersama pakar hukum dan advisor ALMAI. Tanya jawab langsung mengenai kasus Anda.</p>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Live via Zoom Meeting</span>
                    </div>
                </div>
            </div>
            <a href="https://zoom.us" target="_blank" class="absolute top-6 right-6 w-10 h-10 bg-blue-500/10 rounded-full flex items-center justify-center hover:bg-blue-500 hover:text-white transition-colors text-blue-500">
                <i class="fas fa-external-link-alt text-sm"></i>
            </a>
        </div>

        <!-- Micro Learning Social Media -->
        <div class="group relative p-6 bg-gradient-to-br from-pink-600/10 to-transparent border border-pink-500/10 rounded-[2rem] hover:border-pink-500/30 transition-all duration-300">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 bg-pink-500/20 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fas fa-play text-pink-400 text-2xl"></i>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-1">Micro Learning Content</h4>
                    <p class="text-gray-500 text-xs leading-relaxed mb-4 text-justify">Edukasi cepat melalui TikTok, IG Reels, dan YouTube Shorts. Paham hukum dalam 60 detik.</p>
                    <div class="flex gap-3">
                        <i class="fab fa-tiktok text-gray-500 text-xs hover:text-white transition-colors"></i>
                        <i class="fab fa-instagram text-gray-500 text-xs hover:text-white transition-colors"></i>
                        <i class="fab fa-youtube text-gray-500 text-xs hover:text-white transition-colors"></i>
                    </div>
                </div>
            </div>
            <div class="absolute top-6 right-6 flex gap-2">
                <a href="#" class="w-10 h-10 bg-white/5 rounded-full flex items-center justify-center hover:bg-pink-500 hover:text-white transition-colors text-gray-500">
                    <i class="fas fa-link text-sm"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- E-learning Modules Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- PDF Module -->
        <div class="group relative p-6 bg-[#111] border border-white/5 rounded-[2rem] hover:border-accent/30 transition-all duration-300">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 bg-red-500/10 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-1">E-Book Modul Advokasi</h4>
                    <p class="text-gray-500 text-xs leading-relaxed mb-4 text-justify">Panduan komprehensif mengenai prosedur hukum, hak nasabah, dan pelaporan secara prosedural.</p>
                    <span class="text-[10px] text-gray-400 italic">PDF Gratifikatif & Komprehensif</span>
                </div>
            </div>
            <a href="#" class="absolute top-6 right-6 w-10 h-10 bg-white/5 rounded-full flex items-center justify-center hover:bg-accent hover:text-black transition-colors text-gray-500">
                <i class="fas fa-download text-sm"></i>
            </a>
        </div>

        <!-- Case Study -->
        <div class="group relative p-6 bg-[#111] border border-white/5 rounded-[2rem] hover:border-accent/30 transition-all duration-300">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 bg-blue-500/10 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fas fa-magnifying-glass-chart text-blue-500 text-2xl"></i>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-1">Review & Bedah Studi Kasus</h4>
                    <p class="text-gray-500 text-xs leading-relaxed mb-4 text-justify">Kumpulan sengketa nyata yang pernah ditangani tim ALMAI. Belajar dari pola kesalahan umum.</p>
                    <span class="text-[10px] text-gray-400 italic">Bedah Kasus Sengketa Nyata</span>
                </div>
            </div>
            <a href="<?= base_url('user/advokasi/belajar') ?>" class="absolute top-6 right-6 w-10 h-10 bg-white/5 rounded-full flex items-center justify-center hover:bg-accent hover:text-black transition-colors text-gray-500">
                <i class="fas fa-arrow-right text-sm"></i>
            </a>
        </div>
    </div>

    <!-- Reports Section -->
    <div class="space-y-6">
        <div class="flex items-center justify-between px-2">
            <h3 class="text-sm font-black text-white uppercase tracking-widest">Monitoring Seluruh Laporan Advokasi</h3>
            <div class="flex items-center gap-3">
                <span class="text-[10px] text-gray-500 italic">Oversight Panel</span>
            </div>
        </div>

        <div class="space-y-4 px-0.5">
            <?php if (empty($advokasiList)): ?>
                <div class="bg-[#121212] border border-white/5 rounded-[2.5rem] p-16 text-center">
                    <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-folder-open text-gray-600 text-3xl"></i>
                    </div>
                    <h3 class="text-white font-bold mb-2">Belum Ada Laporan</h3>
                    <p class="text-gray-500 text-xs mb-8 max-w-xs mx-auto">Anda belum memiliki riwayat pendaftaran advokasi untuk platform manapun.</p>
                    <a href="<?= base_url('daftar-advokasi') ?>" class="inline-flex items-center gap-3 bg-accent hover:bg-white text-black font-black uppercase tracking-widest px-8 py-4 rounded-2xl transition-all shadow-xl text-[10px]">
                        Daftar Advokasi Sekarang
                    </a>
                </div>
            <?php else: ?>
                <?php foreach ($advokasiList as $adv): ?>
                    <div class="bg-[#121212] border border-white/5 rounded-[2rem] p-6 hover:border-white/10 transition-all group">
                        <div class="flex flex-wrap justify-between items-start gap-4 mb-6">
                            <div class="flex gap-4 items-center">
                                <div class="w-12 h-12 rounded-2xl bg-white/5 flex items-center justify-center group-hover:bg-accent/10 transition-colors">
                                    <i class="fas fa-file-shield text-gray-400 group-hover:text-accent text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] text-accent font-bold mb-1 uppercase tracking-tighter">User: <?= esc($adv['user_name'] ?? 'Guest') ?></p>
                                    <h3 class="text-white font-bold leading-none mb-2 text-sm uppercase">Laporan #<?= $adv['id'] ?></h3>
                                    <div class="flex items-center gap-2">
                                        <p class="text-[10px] text-gray-500 tracking-wider"><?= date('d F Y', strtotime($adv['created_at'])) ?></p>
                                        <span class="w-1 h-1 bg-white/10 rounded-full"></span>
                                        <p class="text-[10px] text-gray-500 font-bold"><?= date('H:i', strtotime($adv['created_at'])) ?> WIB</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <?php if ($adv['status'] === 'pending_payment'): ?>
                                    <span class="inline-block px-3 py-1 bg-yellow-400/10 text-yellow-400 text-[9px] font-black rounded-full border border-yellow-400/20 uppercase tracking-widest">Pending Payment</span>
                                <?php elseif ($adv['status'] === 'pending'): ?>
                                    <span class="inline-block px-3 py-1 bg-blue-400/10 text-blue-400 text-[9px] font-black rounded-full border border-blue-400/20 uppercase tracking-widest">Investigating</span>
                                <?php else: ?>
                                    <span class="inline-block px-3 py-1 bg-green-400/10 text-green-400 text-[9px] font-black rounded-full border border-green-400/20 uppercase tracking-widest"><?= esc(str_replace('_', ' ', $adv['status'])) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                            <div class="p-4 bg-black/40 rounded-2xl border border-white/5">
                                <p class="text-[8px] text-gray-600 uppercase font-black tracking-widest mb-1.5">Claimant</p>
                                <p class="text-[11px] text-white font-bold truncate"><?= esc($adv['name']) ?></p>
                            </div>
                            <div class="p-4 bg-black/40 rounded-2xl border border-white/5">
                                <p class="text-[8px] text-gray-600 uppercase font-black tracking-widest mb-1.5">Contact</p>
                                <p class="text-[11px] text-white font-bold"><?= esc($adv['whatsapp']) ?></p>
                            </div>
                            <div class="p-4 bg-black/40 rounded-2xl border border-white/5">
                                <p class="text-[8px] text-gray-600 uppercase font-black tracking-widest mb-1.5">Identity</p>
                                <p class="text-[11px] text-white font-bold"><?= esc($adv['ktp_number'] ?: 'REQUIRED') ?></p>
                            </div>
                            <div class="p-4 bg-black/40 rounded-2xl border border-white/5">
                                <p class="text-[8px] text-gray-600 uppercase font-black tracking-widest mb-1.5">Loss Amount</p>
                                <p class="text-[11px] text-accent font-bold">Rp <?= number_format($adv['loss_amount'] ?: 0, 0, ',', '.') ?></p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button onclick="viewDetail(<?= $adv['id'] ?>)" class="flex-1 bg-white/5 hover:bg-white/10 text-accent font-black text-[9px] uppercase tracking-widest py-4 rounded-2xl transition border border-white/10 flex items-center justify-center gap-2">
                                <i class="fas fa-eye"></i> Lihat Detail Laporan
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if ($pager && $pager->getPageCount() > 1): ?>
            <div class="mt-8 px-2">
                <?= $pager->links('default', 'tailwind_pagination') ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Detail (Read Only) -->
<div id="modalDetail" class="fixed inset-0 z-50 hidden bg-black/90 backdrop-blur-xl p-4 overflow-y-auto">
    <div class="relative w-full max-w-2xl mx-auto my-8 bg-[#0a0a0a] rounded-[3rem] border border-white/10 overflow-hidden shadow-2xl">
        <div class="p-8 border-b border-white/5 flex justify-between items-center bg-gradient-to-r from-accent/10 to-transparent">
            <div>
                <h3 class="text-white font-black uppercase tracking-widest text-sm">Informasi Detail Laporan</h3>
                <p class="text-[10px] text-gray-500 mt-1" id="detail-ticket-id">TR-000</p>
            </div>
            <button onclick="closeModalDetail()" class="text-gray-400 hover:text-white transition w-10 h-10 bg-white/5 rounded-full flex items-center justify-center">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>
        
        <div class="p-8 space-y-8">
            <!-- Pelapor Grid -->
            <div class="grid grid-cols-2 gap-8">
                <div class="space-y-1">
                    <p class="text-[9px] text-gray-500 font-black uppercase tracking-widest">Nama Pelapor</p>
                    <p class="text-white font-bold text-sm" id="detail-name">-</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[9px] text-gray-500 font-black uppercase tracking-widest">WhatsApp</p>
                    <p class="text-white font-bold text-sm" id="detail-contact">-</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[9px] text-gray-500 font-black uppercase tracking-widest">Alamat Domisili</p>
                    <p class="text-white font-medium text-xs leading-relaxed" id="detail-address">-</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[9px] text-gray-500 font-black uppercase tracking-widest">NIK / KTP</p>
                    <p class="text-white font-bold text-sm font-mono" id="detail-ktp">-</p>
                </div>
            </div>

            <!-- Case Summary -->
            <div class="p-6 bg-white/5 rounded-[2rem] border border-white/5 space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[9px] text-gray-500 font-black uppercase tracking-widest mb-1.5">Broker / Platform</p>
                        <p class="text-white font-bold text-sm" id="detail-broker">-</p>
                    </div>
                    <div>
                        <p class="text-[9px] text-gray-500 font-black uppercase tracking-widest mb-1.5">Total Kerugian</p>
                        <p class="text-accent font-black text-sm" id="detail-loss">-</p>
                    </div>
                </div>
                <div>
                    <p class="text-[9px] text-gray-500 font-black uppercase tracking-widest mb-1.5">Kronologi Kejadian</p>
                    <p class="text-gray-400 text-xs leading-relaxed text-justify italic" id="detail-chronology">Belum ada kronologi detail yang dilaporkan oleh user.</p>
                </div>
            </div>

            <!-- Attachment -->
            <div id="detail-attachment-container" class="hidden space-y-4">
                 <p class="text-[9px] text-gray-500 font-black uppercase tracking-widest">Dokumen Bukti / Lampiran</p>
                 <a id="detail-attachment-link" href="#" target="_blank" class="flex items-center justify-between p-4 bg-accent/10 border border-accent/20 rounded-2xl text-accent hover:bg-accent hover:text-black transition-all group">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-file-pdf text-xl"></i>
                        <span class="text-[10px] font-black uppercase tracking-widest">Lihat Berkas Pendukung</span>
                    </div>
                    <i class="fas fa-arrow-right text-[10px] group-hover:translate-x-1 transition-transform"></i>
                 </a>
            </div>
            
            <div class="pt-4">
                <button onclick="closeModalDetail()" class="w-full py-4 bg-white/5 border border-white/10 text-white font-black uppercase tracking-widest rounded-2xl hover:bg-white/10 transition-all text-[10px]">
                    Tutup Detail
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const advokasiData = <?= json_encode($advokasiList) ?>;

    function viewDetail(id) {
        const adv = advokasiData.find(a => a.id == id);
        if(!adv) return;

        document.getElementById('detail-ticket-id').innerText = 'REPORT ID #' + adv.id + ' • ' + (adv.created_at);
        document.getElementById('detail-name').innerText = adv.name || '-';
        document.getElementById('detail-contact').innerText = adv.whatsapp || '-';
        document.getElementById('detail-address').innerText = adv.address || '-';
        document.getElementById('detail-ktp').innerText = adv.ktp_number || '-';
        document.getElementById('detail-broker').innerText = adv.broker_name || '-';
        document.getElementById('detail-loss').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(adv.loss_amount || 0);
        
        if (adv.chronology) {
             document.getElementById('detail-chronology').innerText = adv.chronology;
        } else {
             document.getElementById('detail-chronology').innerText = 'User belum mengisi kronologi detail.';
        }

        const container = document.getElementById('detail-attachment-container');
        if (adv.file_attachment) {
            container.classList.remove('hidden');
            document.getElementById('detail-attachment-link').href = '<?= base_url('uploads/pengaduan') ?>/' + adv.file_attachment;
        } else {
            container.classList.add('hidden');
        }

        document.getElementById('modalDetail').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModalDetail() {
        document.getElementById('modalDetail').classList.add('hidden');
        document.body.style.overflow = '';
    }
</script>

<?= $this->endSection() ?>
