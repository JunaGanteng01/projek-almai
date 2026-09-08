<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-[#111] p-6 rounded-2xl border border-white/10">
    <div>
        <h1 class="text-2xl font-black text-white mb-1 tracking-tight">Manajemen Portofolio EA</h1>
        <p class="text-xs text-gray-500 uppercase tracking-widest font-bold">Monitor Semua Akun Trading Terkoneksi (Global)</p>
    </div>
</div>

<!-- Portofolio Table -->
<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-black/50 border-b border-white/5">
                <tr>
                    <th class="text-center px-4 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">No</th>
                    <th class="text-left px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Pemilik</th>
                    <th class="text-left px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Detail Akun</th>
                    <th class="text-left px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Broker & Server</th>
                    <th class="text-right px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Equity ($)</th>
                    <th class="text-center px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                    <th class="text-center px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if(empty($accounts)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-chart-line text-4xl mb-4 opacity-20"></i>
                                <p class="text-sm font-bold uppercase tracking-widest">Belum ada portofolio terdaftar</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($accounts as $index => $acc): ?>
                        <tr class="hover:bg-white/[0.02] transition-colors">
                            <td class="px-4 py-4 text-center text-xs text-gray-600 font-mono italic"><?= $index + 1 ?></td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-white font-black text-sm"><?= esc($acc['owner_name'] ?? 'N/A') ?></span>
                                    <span class="text-[10px] text-gray-500 font-bold"><?= esc($acc['owner_email'] ?? 'N/A') ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-accent font-black text-sm leading-none mb-1"><?= esc($acc['account_name']) ?></span>
                                    <span class="text-[10px] text-gray-500 font-mono tracking-wider">ID: <?= esc($acc['account_login']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-gray-300 font-bold text-xs"><?= esc($acc['broker']) ?></span>
                                    <span class="text-[9px] text-gray-600 font-medium uppercase tracking-widest"><?= esc($acc['server']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-white font-mono font-black text-sm tracking-tighter">$<?= number_format($acc['equity'], 2) ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center">
                                    <?php if($acc['is_online']): ?>
                                        <div class="flex items-center gap-2 bg-accent/10 text-accent px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border border-accent/20">
                                            <span class="w-1.5 h-1.5 bg-accent rounded-full animate-pulse shadow-[0_0_8px_rgba(34,255,100,0.5)]"></span>
                                            Online
                                        </div>
                                    <?php else: ?>
                                        <div class="flex items-center gap-2 bg-red-500/10 text-red-500 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest border border-red-500/20 opacity-50">
                                            <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                            Offline
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?= base_url('portofolio/' . $acc['account_login']) ?>" target="_blank" class="w-8 h-8 flex items-center justify-center bg-white/5 text-gray-400 rounded-lg hover:bg-white hover:text-black transition" title="Lihat Publik">
                                        <i class="fas fa-external-link-alt text-xs"></i>
                                    </a>
                                    <button onclick="confirmDelete(<?= $acc['id'] ?>, '<?= esc($acc['account_login']) ?>')" class="w-8 h-8 flex items-center justify-center bg-red-500/10 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition" title="Hapus">
                                        <i class="fas fa-trash-alt text-xs"></i>
                                    </button>
                                </div>
                                <form id="delete-form-<?= $acc['id'] ?>" action="<?= base_url('admin/portfolio/delete/' . $acc['id']) ?>" method="POST" class="hidden">
                                    <?= csrf_field() ?>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id, accountId) {
        Swal.fire({
            title: 'Hapus Portofolio?',
            text: "Akun login " + accountId + " akan dihapus dari sistem.",
            icon: 'warning',
            background: '#111',
            color: '#fff',
            showCancelButton: true,
            confirmButtonColor: '#ff2255',
            cancelButtonColor: '#333',
            confirmButtonText: 'Ya, Hapus Data',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
<?= $this->endSection() ?>
