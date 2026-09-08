<?php 
$this->setVar('pageTitle', 'Detail Pengaduan');
$this->setVar('pageSubtitle', 'Detail lengkap laporan tindak lanjut advokasi');
?>
<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Personal Info -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                <i class="fas fa-user-circle text-accent"></i> Informasi Pelapor
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <span class="text-[10px] text-gray-500 block">Nama Lengkap</span>
                    <p class="text-white font-bold"><?= esc($complaint['name']) ?></p>
                </div>
                <div>
                    <span class="text-[10px] text-gray-500 block">Email</span>
                    <p class="text-white font-bold"><?= esc($complaint['email']) ?></p>
                </div>
                <div>
                    <span class="text-[10px] text-gray-500 block">WhatsApp</span>
                    <p class="text-white font-bold"><?= esc($complaint['whatsapp']) ?></p>
                </div>
                <div>
                    <span class="text-[10px] text-gray-500 block">Nomor KTP</span>
                    <p class="text-white font-bold font-mono"><?= esc($complaint['ktp_number']) ?></p>
                </div>
                <div class="md:col-span-2">
                    <span class="text-[10px] text-gray-500 block">Alamat</span>
                    <p class="text-white text-sm"><?= esc($complaint['address']) ?></p>
                </div>
            </div>
        </div>

        <!-- Incident Info -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                <i class="fas fa-exclamation-triangle text-red-500"></i> Detail Masalah
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <span class="text-[10px] text-gray-500 block">Broker / Platform</span>
                    <p class="text-white font-bold"><?= esc($complaint['broker_name']) ?></p>
                </div>
                <div>
                    <span class="text-[10px] text-gray-500 block">Jenis Trading</span>
                    <p class="text-white font-bold uppercase"><?= esc($complaint['trading_type']) ?></p>
                </div>
                <div>
                    <span class="text-[10px] text-gray-500 block">Kategori Masalah</span>
                    <p class="text-white font-bold"><?= esc($complaint['category_problem']) ?></p>
                </div>
                <div>
                    <span class="text-[10px] text-gray-500 block">Sub Kategori</span>
                    <p class="text-white font-bold"><?= esc($complaint['sub_category'] ?? '-') ?></p>
                </div>
                <div>
                    <span class="text-[10px] text-gray-500 block text-red-400">Estimasi Kerugian</span>
                    <p class="text-xl font-black text-red-500">Rp <?= number_format($complaint['loss_amount'], 0, ',', '.') ?></p>
                </div>
                <div>
                    <span class="text-[10px] text-gray-500 block">Tanggal Kejadian</span>
                    <p class="text-white font-bold"><?= date('d F Y', strtotime($complaint['incident_date'])) ?></p>
                </div>
            </div>
            <div class="border-t border-white/5 pt-4">
                <span class="text-[10px] text-gray-500 block mb-2">Kronologi</span>
                <div class="p-4 bg-black/50 border border-white/5 rounded-xl text-sm text-gray-300 leading-relaxed whitespace-pre-wrap">
                    <?= esc($complaint['chronology']) ?>
                </div>
            </div>
        </div>

        <!-- Technical Trading Details -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4 flex items-center gap-2">
                <i class="fas fa-code text-blue-400"></i> Akun Trading (Teknis)
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <span class="text-[10px] text-gray-500 block">ID Akun</span>
                    <p class="text-white font-mono font-bold"><?= esc($complaint['trading_account'] ?? '-') ?></p>
                </div>
                <div>
                    <span class="text-[10px] text-gray-500 block">Password Pantau</span>
                    <p class="text-white font-mono font-bold"><?= esc($complaint['trading_password'] ?? '-') ?></p>
                </div>
                <div>
                    <span class="text-[10px] text-gray-500 block">Server Broker</span>
                    <p class="text-white font-mono font-bold"><?= esc($complaint['broker_server'] ?? '-') ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="space-y-6">
        <!-- Status & Update -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl overflow-hidden relative">
            <div class="absolute top-0 right-0 w-32 h-32 bg-accent/5 rounded-full -mr-16 -mt-16 blur-3xl"></div>
            
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-6">Tindak Lanjut</h3>
            
            <div class="mb-6">
                <span class="text-[10px] text-gray-500 block mb-2 uppercase tracking-widest">Status Saat Ini</span>
                <div id="statusBadgeContainer">
                    <?php 
                    $statusMap = [
                        'pending_payment' => ['label' => 'Menunggu Pembayaran', 'color' => 'yellow'],
                        'review' => ['label' => 'Review Berkas', 'color' => 'blue'],
                        'investigating' => ['label' => 'Investigasi', 'color' => 'purple'],
                        'legal_process' => ['label' => 'Proses Hukum', 'color' => 'orange'],
                        'resolved' => ['label' => 'Selesai', 'color' => 'accent'],
                        'rejected' => ['label' => 'Ditolak', 'color' => 'red'],
                    ];
                    $curr = $statusMap[$complaint['status']] ?? ['label' => $complaint['status'], 'color' => 'gray'];
                    ?>
                    <span class="px-4 py-2 bg-<?= $curr['color'] ?>-500/10 border border-<?= $curr['color'] ?>-500/20 text-<?= $curr['color'] ?>-400 rounded-xl text-xs uppercase font-black italic tracking-widest inline-block w-full text-center">
                        <?= $curr['label'] ?>
                    </span>
                </div>
            </div>

            <?php if ($canWrite): ?>
            <div class="space-y-3">
                <span class="text-[10px] text-gray-500 block uppercase tracking-widest">Update Status</span>
                <select id="statusSelect" class="w-full px-4 py-2.5 bg-black border border-white/10 rounded-xl focus:border-accent focus:outline-none text-sm transition appearance-none cursor-pointer">
                    <?php foreach ($statusMap as $key => $val): ?>
                    <option value="<?= $key ?>" <?= $complaint['status'] === $key ? 'selected' : '' ?>><?= $val['label'] ?></option>
                    <?php endforeach; ?>
                </select>
                <button onclick="updateStatus(<?= $complaint['id'] ?>)" class="w-full py-3 bg-accent text-black font-black uppercase text-xs rounded-xl hover:shadow-[0_0_20px_rgba(51,232,24,0.3)] transition transform hover:-translate-y-0.5">
                    Update Progress
                </button>
            </div>
            <?php endif; ?>
        </div>

        <!-- Attachment -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl">
            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4">Bukti Lampiran</h3>
            <?php if ($complaint['file_attachment']): ?>
                <div class="p-4 bg-black/50 border border-white/5 rounded-xl text-center group">
                    <i class="fas fa-file-invoice-dollar text-3xl text-accent mb-3 group-hover:scale-110 transition duration-300"></i>
                    <p class="text-xs text-gray-400 mb-4 truncate italic"><?= esc($complaint['file_attachment']) ?></p>
                    <a href="<?= base_url('uploads/pengaduan/' . $complaint['file_attachment']) ?>" target="_blank" class="block w-full py-2.5 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg text-[10px] font-bold uppercase transition">
                        Buka Lampiran
                    </a>
                </div>
            <?php else: ?>
                <div class="py-10 text-center opacity-30">
                    <i class="fas fa-file-excel text-4xl mb-3"></i>
                    <p class="text-xs">Tidak ada lampiran</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Meta Data -->
        <div class="bg-[#111] border border-white/10 rounded-2xl p-4 shadow-xl text-[10px] space-y-2">
            <div class="flex justify-between">
                <span class="text-gray-500">Submitted At</span>
                <span class="text-gray-300"><?= date('d/m/Y H:i', strtotime($complaint['created_at'])) ?></span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Complaint ID</span>
                <span class="text-gray-300 font-mono">#ADV-<?= str_pad($complaint['id'], 5, '0', STR_PAD_LEFT) ?></span>
            </div>
        </div>
    </div>
</div>

<script>
    async function updateStatus(id) {
        const status = document.getElementById('statusSelect').value;
        const btn = event.currentTarget;
        const originalText = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> UPDATING...';

        try {
            const formData = new FormData();
            formData.append('status', status);

            const response = await fetch(`<?= base_url('superadmin/advokasi/update-status') ?>/${id}`, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });

            const result = await response.json();
            if (result.success) {
                alert('Status berhasil diperbarui!');
                location.reload();
            } else {
                alert(result.message || 'Gagal memperbarui status.');
            }
        } catch (error) {
            console.error(error);
            alert('Terjadi kesalahan koneksi.');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    }
</script>

<?= $this->endSection() ?>
