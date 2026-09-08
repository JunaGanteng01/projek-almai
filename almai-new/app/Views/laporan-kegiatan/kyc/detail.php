<?php 
$this->setVar('pageTitle', 'Detail KYC PRO');
$this->setVar('pageSubtitle', 'Review data pengajuan upgrade PRO (Read Only)');
?>
<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Back Button -->
<a href="<?= base_url('laporan-kegiatan/kyc') ?>" class="inline-flex items-center gap-2 text-gray-500 hover:text-white mb-6 transition text-xs font-black uppercase tracking-widest">
    <i class="fas fa-arrow-left"></i> Kembali ke Daftar
</a>

<!-- User Info Header -->
<div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-8 shadow-2xl relative overflow-hidden group">
    <div class="absolute -right-20 -top-20 opacity-5 blur-3xl w-60 h-60 bg-accent rounded-full transition duration-1000 group-hover:scale-125"></div>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative">
        <div class="flex items-center gap-6">
            <div class="relative">
                <div class="w-16 h-16 rounded-full flex items-center justify-center bg-black border border-white/10 shadow-xl group-hover:border-accent transition duration-500">
                    <i class="fas fa-user-shield text-gray-700 text-2xl group-hover:text-accent transition duration-500"></i>
                </div>
                <?php if ($user['level_id'] == \App\Models\LevelModel::LEVEL_PRO): ?>
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-gradient-to-r from-yellow-500 to-orange-500 rounded-full flex items-center justify-center shadow-lg">
                        <i class="fas fa-crown text-black text-[10px]"></i>
                    </div>
                <?php endif; ?>
            </div>
            <div>
                <h2 class="text-2xl font-black text-white uppercase tracking-tighter mb-1"><?= esc($user['name']) ?></h2>
                <p class="text-sm text-gray-500 font-mono italic"><?= esc($user['email']) ?></p>
                <div class="flex items-center gap-2 mt-3">
                    <?php if ($user['kyc_status'] === 'pending'): ?>
                        <span class="px-3 py-1 bg-yellow-500/10 text-yellow-500 border border-yellow-500/20 rounded text-[9px] font-black uppercase tracking-widest italic">
                            <i class="fas fa-clock mr-1 animate-pulse"></i>Pending Review
                        </span>
                    <?php elseif ($user['kyc_status'] === 'approved'): ?>
                        <span class="px-3 py-1 bg-accent/10 text-accent border border-accent/20 rounded text-[9px] font-black uppercase tracking-widest italic">
                            <i class="fas fa-check-double mr-1"></i>Approved System
                        </span>
                    <?php else: ?>
                        <span class="px-3 py-1 bg-red-500/10 text-red-400 border border-red-500/20 rounded text-[9px] font-black uppercase tracking-widest italic">
                            <i class="fas fa-times mr-1"></i>Rejected Record
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if ($kyc): ?>
    <div class="grid md:grid-cols-2 gap-8">
        <!-- Data Pribadi -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-8 shadow-2xl">
            <h3 class="font-black mb-8 flex items-center gap-3 text-lg uppercase tracking-widest text-accent italic">
                <i class="fas fa-user-circle"></i> Data Pribadi
            </h3>
            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-[9px] font-black text-gray-600 uppercase tracking-widest mb-1 italic">Full Identity Name</p>
                        <p class="font-bold text-white text-sm"><?= esc($kyc['full_name'] ?? '-') ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-600 uppercase tracking-widest mb-1 italic">ID / NIK Record</p>
                        <p class="font-mono text-gray-300 text-sm"><?= esc($kyc['nik'] ?? '-') ?></p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-[9px] font-black text-gray-600 uppercase tracking-widest mb-1 italic">Tax ID (NPWP)</p>
                        <p class="font-mono text-gray-300 text-sm"><?= esc($kyc['npwp'] ?? '-') ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-600 uppercase tracking-widest mb-1 italic">Professional Background</p>
                        <p class="font-bold text-white text-sm uppercase"><?= esc($kyc['profession'] ?? '-') ?></p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-[9px] font-black text-gray-600 uppercase tracking-widest mb-1 italic">Place & Date of Birth</p>
                        <p class="font-bold text-white text-sm"><?= esc($kyc['birth_place'] ?? '-') ?>, <?= !empty($kyc['birth_date']) ? date('d M Y', strtotime($kyc['birth_date'])) : '-' ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-600 uppercase tracking-widest mb-1 italic">Gender Node</p>
                        <p class="font-bold text-white text-sm uppercase"><?= ($kyc['gender'] ?? '') === 'male' ? 'Laki-laki' : (($kyc['gender'] ?? '') === 'female' ? 'Perempuan' : '-') ?></p>
                    </div>
                </div>
                <div>
                    <p class="text-[9px] font-black text-gray-600 uppercase tracking-widest mb-1 italic">Full Residential Address</p>
                    <p class="font-bold text-white text-sm italic"><?= esc($kyc['address'] ?? '-') ?>, <?= esc($kyc['city'] ?? '-') ?>, <?= esc($kyc['province'] ?? '-') ?> (<?= esc($kyc['postal_code'] ?? '-') ?>)</p>
                </div>
            </div>
        </div>

        <!-- Foto KTP -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-8 shadow-2xl">
            <h3 class="font-black mb-8 flex items-center gap-3 text-lg uppercase tracking-widest text-accent italic">
                <i class="fas fa-id-card"></i> Dokumentasi KTP
            </h3>
            <?php
            $ktpPhoto = $kyc['ktp_photo'] ?? ($kyc['photo_identity_card'] ?? null);
            if (!empty($ktpPhoto)):
                $imagePath = (strpos($ktpPhoto, 'user_data') !== false && strpos($ktpPhoto, 'uploads') === false) ? 'uploads/' . $ktpPhoto : $ktpPhoto;
            ?>
                <div class="border border-white/5 rounded-2xl overflow-hidden shadow-inner group relative">
                    <img src="<?= base_url('file/' . $imagePath) ?>" alt="KTP" class="w-full h-auto grayscale hover:grayscale-0 transition duration-1000">
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition duration-500 flex items-center justify-center">
                         <a href="<?= base_url('file/' . $imagePath) ?>" target="_blank" class="px-6 py-3 bg-accent text-black font-black uppercase text-[10px] tracking-widest rounded-xl shadow-2xl shadow-accent/20">Expand Image</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="border-2 border-dashed border-white/5 rounded-2xl p-12 text-center opacity-20">
                    <i class="fas fa-image text-5xl mb-4"></i>
                    <p class="text-xs font-black uppercase tracking-widest">No Image Data</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Data Rekening Bank -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-8 shadow-2xl">
            <h3 class="font-black mb-8 flex items-center gap-3 text-lg uppercase tracking-widest text-accent italic">
                <i class="fas fa-university"></i> Rekening Clearing
            </h3>
            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-[9px] font-black text-gray-600 uppercase tracking-widest mb-1 italic">Institution</p>
                        <p class="font-bold text-white text-sm uppercase italic"><?= esc($kyc['bank_name'] ?? '-') ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-600 uppercase tracking-widest mb-1 italic">Branch Region</p>
                        <p class="font-bold text-white text-sm uppercase italic"><?= esc($kyc['bank_branch'] ?? '-') ?></p>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-[9px] font-black text-gray-600 uppercase tracking-widest mb-1 italic">Account Serial Number</p>
                        <p class="font-mono text-accent text-sm font-black"><?= esc($kyc['account_number'] ?? '-') ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-600 uppercase tracking-widest mb-1 italic">Account Holder Name</p>
                        <p class="font-bold text-white text-sm uppercase"><?= esc($kyc['account_name'] ?? '-') ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Pengalaman Trading -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-8 shadow-2xl">
            <h3 class="font-black mb-8 flex items-center gap-3 text-lg uppercase tracking-widest text-accent italic">
                <i class="fas fa-microchip"></i> Profil Investasi
            </h3>
            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-[9px] font-black text-gray-600 uppercase tracking-widest mb-1 italic">Trading seniority</p>
                        <p class="font-bold text-white text-sm italic">
                            <?php 
                                $exp = $kyc['investment_experience'] ?? ($kyc['experience'] ?? '');
                                echo esc($expLabel[$exp] ?? $exp ?: '-');
                            ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-600 uppercase tracking-widest mb-1 italic">Strategy Goal</p>
                        <p class="font-bold text-white text-sm italic uppercase"><?= esc($kyc['investment_goals'] ?? '-') ?></p>
                    </div>
                </div>
                <div>
                    <p class="text-[9px] font-black text-gray-600 uppercase tracking-widest mb-1 italic">Risk Appetite Vector</p>
                    <?php $risk = $kyc['type_of_risk'] ?? ($kyc['risk_profile'] ?? ''); ?>
                    <span class="px-3 py-1 bg-white/5 border border-white/10 rounded font-black text-[10px] uppercase tracking-widest <?= $risk === 'aggressive' ? 'text-red-500' : ($risk === 'moderate' ? 'text-yellow-500' : 'text-blue-500') ?>">
                        <?= esc($risk ?: 'UNDEFINED') ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="bg-[#111] border border-white/10 rounded-2xl p-20 text-center shadow-2xl">
        <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6 opacity-20">
            <i class="fas fa-database text-4xl"></i>
        </div>
        <p class="text-sm font-black uppercase tracking-widest text-gray-600">No KYC dataset available for this UID</p>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
