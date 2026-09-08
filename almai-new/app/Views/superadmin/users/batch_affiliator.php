<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <a href="<?= base_url('superadmin/users') ?>" class="inline-flex items-center text-gray-400 hover:text-white mb-4 transition text-sm">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar User
    </a>
    <h1 class="text-2xl font-bold text-white mb-2">Ubah Affiliator (Root Access)</h1>
    <p class="text-gray-400 text-sm">Update kode affiliator untuk user yang mendaftar secara organik (tanpa kode referral).</p>
</div>

<!-- Search & Info -->
<div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-6">
    <div class="md:col-span-8">
        <div class="bg-[#111] border border-white/10 rounded-2xl p-4">
            <form action="<?= base_url('superadmin/users/batch-affiliator') ?>" method="get" class="flex gap-2">
                <input type="text" name="search" value="<?= esc($search) ?>" placeholder="Cari nama atau email user..." 
                    class="flex-1 bg-black border border-white/20 rounded-xl px-4 py-2 text-white focus:border-accent focus:outline-none">
                <button type="submit" class="px-6 py-2 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                    <i class="fas fa-search mr-2"></i> Cari
                </button>
            </form>
        </div>
    </div>
    <div class="md:col-span-4">
        <div class="bg-blue-500/10 border border-blue-500/20 rounded-2xl p-4 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-500/20 flex items-center justify-center shrink-0">
                <i class="fas fa-info-circle text-blue-400"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Total Antrian</p>
                <p class="text-xl font-bold text-white"><?= count($users) ?> User</p>
            </div>
        </div>
    </div>
</div>

<!-- Users List -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-black/50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">User Tanpa Affiliator</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal Daftar</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Pilih Affiliator Baru</th>
                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-500">
                                <i class="fas fa-check-circle text-2xl"></i>
                            </div>
                            <p class="text-gray-400 font-medium">Semua user sudah memiliki affiliator code!</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr id="row-<?= $user['id'] ?>" class="hover:bg-white/[0.02] transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center text-white font-bold shrink-0">
                                        <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-white"><?= esc($user['name']) ?></span>
                                        <span class="text-xs text-gray-500"><?= esc($user['email']) ?></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-xs text-gray-400"><?= date('d M Y, H:i', strtotime($user['created_at'])) ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <select id="select-<?= $user['id'] ?>" class="w-full bg-black border border-white/20 rounded-xl px-4 py-2 text-sm text-gray-300 focus:border-accent focus:outline-none appearance-none cursor-pointer">
                                    <option value="">-- Pilih Affiliator --</option>
                                    <optgroup label="Custom / Manual">
                                        <option value="SPI">SPI (Manual)</option>
                                        <option value="Kampus">Kampus (Manual)</option>
                                    </optgroup>
                                    <optgroup label="WPA / CWPA / Admin">
                                        <?php foreach ($affiliators as $aff): ?>
                                            <option value="<?= esc(!empty($aff['code_referral']) ? $aff['code_referral'] : $aff['name']) ?>">
                                                <?= esc($aff['name']) ?> (<?= esc(!empty($aff['code_referral']) ? $aff['code_referral'] : 'No Code') ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                </select>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="updateAffiliator(<?= $user['id'] ?>)" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold rounded-lg transition disabled:opacity-50 disabled:cursor-not-allowed btn-update">
                                    <i class="fas fa-save"></i> Update
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    async function updateAffiliator(userId) {
        const select = document.getElementById('select-' + userId);
        const code = select.value;
        const row = document.getElementById('row-' + userId);
        const btn = row.querySelector('.btn-update');

        if (!code) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Silakan pilih kode affiliator terlebih dahulu.',
                background: '#111',
                color: '#fff'
            });
            return;
        }

        const result = await Swal.fire({
            title: 'Konfirmasi Root',
            text: `Apakah Anda yakin ingin memberikan kode "${code}" ke user ini?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Update',
            cancelButtonText: 'Batal',
            background: '#111',
            color: '#fff',
            confirmButtonColor: '#3B82F6'
        });

        if (result.isConfirmed) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            try {
                const response = await fetch('<?= base_url('superadmin/users/process-batch-affiliator') ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `user_id=${userId}&affiliator_code=${encodeURIComponent(code)}&<?= csrf_token() ?>=<?= csrf_hash() ?>`
                });

                const data = await response.json();

                if (data.success) {
                    row.classList.add('bg-green-500/10', 'opacity-0');
                    setTimeout(() => {
                        row.remove();
                        if (document.querySelectorAll('tbody tr').length === 0) {
                            location.reload();
                        }
                    }, 500);
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: data.message,
                        timer: 1500,
                        showConfirmButton: false,
                        background: '#111',
                        color: '#fff'
                    });
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: error.message || 'Terjadi kesalahan sistem.',
                    background: '#111',
                    color: '#fff'
                });
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-save"></i> Update';
            }
        }
    }
</script>
<?= $this->endSection() ?>
