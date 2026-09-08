<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Detail KYC - Admin Dashboard') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: { extend: { colors: { 'accent': '#33e818' }, fontFamily: { sans: ['Montserrat', 'sans-serif'] } } }
        }
    </script>
    <style>
        html, body { background-color: #050505; color: #ffffff; }
        .sidebar-link.active { background: linear-gradient(90deg, rgba(51,232,24,0.2) 0%, transparent 100%); border-left: 3px solid #33e818; }
    </style>
</head>
<body class="antialiased">
    <div class="flex min-h-screen">
        <?php $pageTitle = 'Detail KYC'; $pageSubtitle = 'Review data pengajuan PRO'; $activeMenu = 'kyc'; ?>
        <?= $this->include('admin/partials/sidebar') ?>

        <main class="flex-1 md:ml-64 pt-14 md:pt-0">
            <?= $this->include('admin/partials/header') ?>

            <section class="p-4 md:p-8">
                <!-- Back Button -->
                <a href="<?= base_url('admin/kyc') ?>" class="inline-flex items-center gap-2 text-gray-400 hover:text-white mb-6 transition">
                    <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                </a>

                <!-- User Info Header -->
                <div class="bg-[#111] border border-white/10 rounded-xl p-6 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <div class="w-16 h-16 rounded-full flex items-center justify-center bg-gradient-to-br from-yellow-500/20 to-orange-500/20">
                                    <i class="fas fa-user text-yellow-500 text-2xl"></i>
                                </div>
                                <?php if ($user['level_id'] == \App\Models\LevelModel::LEVEL_PRO): ?>
                                <div class="absolute -top-1 -right-1 w-6 h-6 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-crown text-black text-xs"></i>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold"><?= esc($user['name']) ?></h2>
                                <p class="text-gray-400 text-sm"><?= esc($user['email']) ?></p>
                                <div class="flex items-center gap-2 mt-2">
                                    <?php 
                                    $displayStatus = $user['kyc_status'];
                                    // If user is already PRO, they should be considered approved
                                    if ($user['level_id'] == \App\Models\LevelModel::LEVEL_PRO) {
                                        $displayStatus = 'approved';
                                    }
                                    
                                    if ($displayStatus === 'pending'): 
                                    ?>
                                        <span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-xs">
                                            <i class="fas fa-clock mr-1"></i>Pending Review
                                        </span>
                                    <?php elseif ($displayStatus === 'approved'): ?>
                                        <span class="px-3 py-1 bg-accent/20 text-accent rounded-full text-xs">
                                            <i class="fas fa-check mr-1"></i>Approved
                                        </span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 bg-red-500/20 text-red-400 rounded-full text-xs">
                                            <i class="fas fa-times mr-1"></i>Rejected
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($user['kyc_submitted_at']) && $user['kyc_submitted_at'] !== '0000-00-00 00:00:00'): ?>
                                    <span class="text-xs text-gray-500">
                                        Diajukan: <?= date('d M Y H:i', strtotime($user['kyc_submitted_at'])) ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <?php if ($user['kyc_status'] === 'pending'): ?>
                        <div class="flex gap-2">
                            <button onclick="approveKyc()" class="px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-black font-bold rounded-xl hover:from-yellow-400 hover:to-orange-400 transition">
                                <i class="fas fa-check mr-2"></i>Approve
                            </button>
                            <button onclick="rejectKyc()" class="px-6 py-3 bg-red-500/20 text-red-400 border border-red-500/30 font-bold rounded-xl hover:bg-red-500/30 transition">
                                <i class="fas fa-times mr-2"></i>Reject
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($kyc): ?>
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Data Pribadi -->
                    <div class="bg-[#111] border border-white/10 rounded-xl p-6">
                        <h3 class="font-bold mb-4 flex items-center gap-2 text-lg">
                            <i class="fas fa-user text-accent"></i> Data Pribadi
                        </h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Nama Lengkap</p>
                                    <p class="font-medium"><?= esc($kyc['full_name'] ?? ($kyc['name'] ?? '-')) ?></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">NIK / No. Identitas</p>
                                    <p class="font-medium font-mono"><?= esc($kyc['nik'] ?? ($kyc['identity_number'] ?? '-')) ?></p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">No. NPWP</p>
                                    <p class="font-medium font-mono"><?= esc($kyc['npwp'] ?? '-') ?></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Pekerjaan</p>
                                    <p class="font-medium">
                                        <?php
                                        $professionLabels = [
                                            'pelajar' => 'Pelajar/Mahasiswa',
                                            'swasta' => 'Karyawan Swasta',
                                            'pns' => 'Pegawai Negeri Sipil',
                                            'wiraswasta' => 'Wiraswasta',
                                            'profesional' => 'Profesional',
                                            'lainnya' => 'Lainnya'
                                        ];
                                        echo esc($professionLabels[$kyc['profession'] ?? ''] ?? ($kyc['profession'] ?? '-'));
                                        ?>
                                    </p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Tempat Lahir</p>
                                    <p class="font-medium"><?= esc($kyc['birth_place'] ?? ($kyc['birth_place_and_date'] ?? '-')) ?></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Tanggal Lahir</p>
                                    <p class="font-medium"><?= !empty($kyc['birth_date']) ? date('d M Y', strtotime($kyc['birth_date'])) : '-' ?></p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Jenis Kelamin</p>
                                    <p class="font-medium"><?= ($kyc['gender'] ?? '') === 'male' ? 'Laki-laki' : (($kyc['gender'] ?? '') === 'female' ? 'Perempuan' : '-') ?></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">No. WhatsApp</p>
                                    <p class="font-medium"><?= esc($kyc['phone'] ?? '-') ?></p>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Alamat</p>
                                <p class="font-medium"><?= esc($kyc['address'] ?? '-') ?></p>
                            </div>
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Provinsi</p>
                                    <p class="font-medium text-sm"><?= esc($kyc['province'] ?? '-') ?></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Kota</p>
                                    <p class="font-medium text-sm"><?= esc($kyc['city'] ?? '-') ?></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Kode Pos</p>
                                    <p class="font-medium text-sm"><?= esc($kyc['postal_code'] ?? '-') ?></p>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Tujuan Pembukaan Akun</p>
                                <p class="font-medium">
                                    <?php
                                    $purposeLabels = [
                                        'pelatihan' => 'Pelatihan',
                                        'pendampingan' => 'Pendampingan',
                                        'lainnya' => 'Lainnya'
                                    ];
                                    echo esc($purposeLabels[$kyc['registration_purpose'] ?? ''] ?? ($kyc['registration_purpose'] ?? '-'));
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Foto KTP -->
                    <div class="bg-[#111] border border-white/10 rounded-xl p-6">
                        <h3 class="font-bold mb-4 flex items-center gap-2 text-lg">
                            <i class="fas fa-id-card text-accent"></i> Foto KTP
                        </h3>
                        <?php 
                        $ktpPhoto = $kyc['ktp_photo'] ?? ($kyc['photo_identity_card'] ?? null);
                        if (!empty($ktpPhoto)): 
                        ?>
                        <div class="border border-white/10 rounded-xl overflow-hidden">
                            <?php 
                                $imagePath = $ktpPhoto;
                                if (strpos($ktpPhoto, 'user_data') !== false && strpos($ktpPhoto, 'uploads') === false) {
                                    $imagePath = 'uploads/' . $ktpPhoto;
                                }
                            ?>
                            <img src="<?= base_url('file/' . $imagePath) ?>" alt="KTP" class="w-full h-auto">
                        </div>
                        <a href="<?= base_url('file/' . $imagePath) ?>" target="_blank" class="inline-flex items-center gap-2 mt-3 text-accent hover:underline text-sm">
                            <i class="fas fa-external-link-alt"></i> Lihat ukuran penuh
                        </a>
                        <?php else: ?>
                        <div class="border-2 border-dashed border-white/10 rounded-xl p-8 text-center">
                            <i class="fas fa-image text-4xl text-gray-600 mb-2"></i>
                            <p class="text-gray-500 text-sm">Foto KTP tidak diupload</p>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Data Rekening Bank -->
                    <div class="bg-[#111] border border-white/10 rounded-xl p-6">
                        <h3 class="font-bold mb-4 flex items-center gap-2 text-lg">
                            <i class="fas fa-university text-accent"></i> Data Rekening Bank
                        </h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Nama Bank</p>
                                    <p class="font-medium"><?= esc($kyc['bank_name'] ?? '-') ?></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Cabang</p>
                                    <p class="font-medium"><?= esc($kyc['bank_branch'] ?? '-') ?></p>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">No. Rekening</p>
                                    <p class="font-medium font-mono"><?= esc($kyc['account_number'] ?? '-') ?></p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Nama Pemilik</p>
                                    <p class="font-medium"><?= esc($kyc['account_name'] ?? '-') ?></p>
                                </div>
                            </div>
                            
                            <!-- Verification Check -->
                            <div class="mt-4 p-3 rounded-lg <?= isset($kyc['full_name'], $kyc['account_name']) && strtoupper($kyc['full_name']) === strtoupper($kyc['account_name']) ? 'bg-accent/10 border border-accent/30' : 'bg-yellow-500/10 border border-yellow-500/30' ?>">
                                <?php if (isset($kyc['full_name'], $kyc['account_name']) && strtoupper($kyc['full_name']) === strtoupper($kyc['account_name'])): ?>
                                <p class="text-sm text-accent"><i class="fas fa-check-circle mr-2"></i>Nama pemilik rekening sesuai dengan nama KTP</p>
                                <?php else: ?>
                                <p class="text-sm text-yellow-400"><i class="fas fa-exclamation-triangle mr-2"></i>Nama pemilik rekening berbeda dengan nama KTP</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Data Pengalaman Trading -->
                    <div class="bg-[#111] border border-white/10 rounded-xl p-6">
                        <h3 class="font-bold mb-4 flex items-center gap-2 text-lg">
                            <i class="fas fa-chart-line text-accent"></i> Pengalaman Trading
                        </h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Pengalaman Trading</p>
                                    <p class="font-medium">
                                        <?php
                                        $expLabels = [
                                            'none' => 'Belum pernah',
                                            'less_1_year' => 'Kurang dari 1 tahun',
                                            '1_3_years' => '1-3 tahun',
                                            'more_3_years' => 'Lebih dari 3 tahun',
                                            // Map tinyint values
                                            '0' => 'Belum pernah',
                                            '1' => 'Kurang dari 1 tahun',
                                            '2' => '1-3 tahun',
                                            '3' => 'Lebih dari 3 tahun',
                                        ];
                                        $exp = $kyc['investment_experience'] ?? ($kyc['experience'] ?? '');
                                        echo esc($expLabels[$exp] ?? ($exp ?: '-'));
                                        ?>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Tujuan Trading</p>
                                    <p class="font-medium">
                                        <?php
                                        $goalLabels = [
                                            'hedging' => 'Hedging',
                                            'spekulasi' => 'Spekulasi',
                                            'investasi' => 'Investasi',
                                            // Legacy labels
                                            'short_term' => 'Jangka Pendek',
                                            'medium_term' => 'Jangka Menengah',
                                            'long_term' => 'Jangka Panjang'
                                        ];
                                        $goal = $kyc['investment_goals'] ?? ($kyc['investment_goal'] ?? '');
                                        echo esc($goalLabels[$goal] ?? ($goal ?: '-'));
                                        ?>
                                    </p>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Profil Risiko</p>
                                <p class="font-medium">
                                    <?php
                                    $riskLabels = [
                                        'conservative' => 'Konservatif',
                                        'moderate' => 'Moderat',
                                        'aggressive' => 'Agresif'
                                    ];
                                    $riskColors = [
                                        'conservative' => 'text-blue-400',
                                        'moderate' => 'text-yellow-400',
                                        'aggressive' => 'text-red-400'
                                    ];
                                    $risk = $kyc['type_of_risk'] ?? ($kyc['risk_profile'] ?? '');
                                    ?>
                                    <span class="<?= $riskColors[$risk] ?? '' ?>"><?= esc($riskLabels[$risk] ?? ($risk ?: '-')) ?></span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="bg-[#111] border border-white/10 rounded-xl p-8 text-center">
                    <i class="fas fa-file-alt text-4xl text-gray-600 mb-3"></i>
                    <p class="text-gray-400">Data KYC detail tidak ditemukan</p>
                    <p class="text-gray-500 text-sm mt-1">User mungkin submit sebelum sistem menyimpan data lengkap</p>
                </div>
                <?php endif; ?>
            </section>
        </main>
    </div>

    <!-- Approve Modal -->
    <div id="approveModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 md:p-8 max-w-sm w-full text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center bg-gradient-to-br from-yellow-500/20 to-orange-500/20">
                <i class="fas fa-crown text-3xl text-yellow-500"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Approve KYC?</h3>
            <p class="text-gray-400 mb-6 text-sm">User "<?= esc($user['name']) ?>" akan menjadi Member PRO selama 1 tahun.</p>
            <div class="flex gap-3">
                <button onclick="closeApproveModal()" class="flex-1 py-3 border border-white/20 rounded-xl hover:bg-white/5 transition">Batal</button>
                <form action="<?= base_url('admin/kyc/approve/' . $user['id']) ?>" method="post" class="flex-1">
                    <?= csrf_field() ?>
                    <button type="submit" class="w-full py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-black font-bold rounded-xl hover:from-yellow-400 hover:to-orange-400 transition">
                        <i class="fas fa-check mr-2"></i>Approve
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/80 backdrop-blur-sm p-4">
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 md:p-8 max-w-sm w-full text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center bg-red-500/20">
                <i class="fas fa-times text-3xl text-red-500"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Tolak KYC?</h3>
            <p class="text-gray-400 mb-4 text-sm">Pengajuan KYC dari "<?= esc($user['name']) ?>" akan ditolak.</p>
            <form action="<?= base_url('admin/kyc/reject/' . $user['id']) ?>" method="post">
                <?= csrf_field() ?>
                <textarea name="reason" rows="2" class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-red-500 focus:outline-none text-sm mb-4" placeholder="Alasan penolakan (opsional)"></textarea>
                <div class="flex gap-3">
                    <button type="button" onclick="closeRejectModal()" class="flex-1 py-3 border border-white/20 rounded-xl hover:bg-white/5 transition">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600 transition">
                        <i class="fas fa-times mr-2"></i>Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function approveKyc() {
            document.getElementById('approveModal').classList.remove('hidden');
            document.getElementById('approveModal').classList.add('flex');
        }
        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
            document.getElementById('approveModal').classList.remove('flex');
        }

        function rejectKyc() {
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectModal').classList.add('flex');
        }
        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectModal').classList.remove('flex');
        }
    </script>
</body>
</html>
