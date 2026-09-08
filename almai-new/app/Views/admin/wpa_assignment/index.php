<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <h1 class="text-2xl font-bold text-white">Penugasan WPA ke Admin</h1>
    <p class="text-gray-400">Atur Admin Level 5 mana yang mengelola WPA tertentu.</p>
</div>

<div class="grid grid-cols-1 gap-6">
    <?php foreach ($admins as $admin): ?>
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-shield text-purple-500 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white"><?= esc($admin['name']) ?></h3>
                        <p class="text-sm text-gray-500"><?= esc($admin['email']) ?></p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 bg-white/5 border border-white/10 rounded-full text-xs text-gray-400">
                        <?= count($adminAssignments[$admin['id']] ?? []) ?> WPA Ditugaskan
                    </span>
                </div>
            </div>

            <form action="<?= base_url('admin/wpa-assignment/store') ?>" method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="admin_id" value="<?= $admin['id'] ?>">
                
                <p class="text-sm text-gray-400 mb-4 font-medium">Pilih WPA untuk dikelola:</p>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 mb-6">
                    <?php foreach ($wpas as $wpa): ?>
                        <?php 
                        $isChecked = in_array($wpa['id'], $adminAssignments[$admin['id']] ?? []); 
                        ?>
                        <label class="relative flex items-center gap-3 p-3 rounded-xl border transition-all cursor-pointer group <?= $isChecked ? 'bg-accent/10 border-accent/50 text-accent' : 'bg-black/40 border-white/5 text-gray-400 hover:border-white/20' ?>">
                            <input type="checkbox" name="wpa_ids[]" value="<?= $wpa['id'] ?>" class="hidden peer" <?= $isChecked ? 'checked' : '' ?> onchange="this.parentElement.classList.toggle('bg-accent/10'); this.parentElement.classList.toggle('border-accent/50'); this.parentElement.classList.toggle('text-accent'); this.parentElement.classList.toggle('bg-black/40'); this.parentElement.classList.toggle('border-white/5'); this.parentElement.classList.toggle('text-gray-400')">
                            
                            <img src="<?= esc($wpa['photo'] ? (strpos($wpa['photo'], 'http') === 0 ? $wpa['photo'] : base_url('file/' . $wpa['photo'])) : 'https://via.placeholder.com/100') ?>" 
                                 class="w-8 h-8 rounded-full object-cover">
                            
                            <div class="min-w-0">
                                <p class="text-xs font-bold truncate"><?= esc($wpa['name']) ?></p>
                                <p class="text-[10px] opacity-60 truncate"><?= esc($wpa['specialty']) ?></p>
                            </div>
                            
                            <?php if ($isChecked): ?>
                                <div class="absolute top-2 right-2 w-4 h-4 bg-accent rounded-full flex items-center justify-center text-black text-[10px]">
                                    <i class="fas fa-check"></i>
                                </div>
                            <?php endif; ?>
                        </label>
                    <?php endforeach; ?>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2 bg-accent hover:bg-accent/80 text-black font-bold rounded-xl transition-all flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    <?php endforeach; ?>

    <?php if (empty($admins)): ?>
        <div class="bg-yellow-500/10 border border-yellow-500/20 rounded-2xl p-10 text-center">
            <i class="fas fa-exclamation-triangle text-4xl text-yellow-500 mb-4"></i>
            <h3 class="text-xl font-bold text-white mb-2">Tidak ada Admin Level 5</h3>
            <p class="text-gray-400">Anda perlu membuat user dengan level Admin (5) terlebih dahulu sebelum dapat melakukan penugasan.</p>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
