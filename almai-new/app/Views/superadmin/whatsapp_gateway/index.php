<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>
<?php 
$pageTitle = 'WhatsApp Gateway';
$activeMenu = 'whatsapp_gateway'; 
?>

<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold"><?= $title ?></h1>
            <p class="text-gray-400 text-sm">Scan QR Code untuk menghubungkan WhatsApp ke sistem OTP & Notifikasi</p>
        </div>
    </div>

    <!-- WhatsApp Gateway Status & QR Section -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-6 md:p-8">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fab fa-whatsapp text-3xl text-green-500"></i>
            </div>
            <h2 class="text-xl font-bold mb-2">WhatsApp Gateway Koneksi</h2>
        </div>

        <div class="flex flex-col items-center justify-center p-6 border border-white/10 rounded-xl bg-black/50">
            <div class="mb-6 text-center">
                <p class="text-sm text-gray-400 mb-1">Status Koneksi</p>
                <div id="statusBadge" class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-gray-500/20 text-gray-400 font-bold">
                    <i class="fas fa-spinner fa-spin"></i> Mengecek status...
                </div>
                <div class="mt-4">
                    <button id="startGatewayBtn" class="px-4 py-2 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-sm flex items-center gap-2 mx-auto">
                        <i class="fas fa-power-off"></i> Start / Generate QR Code
                    </button>
                </div>
            </div>

            <!-- QR Code Container -->
            <div id="qrContainer" class="hidden flex-col items-center">
                <div class="bg-white p-4 rounded-xl mb-4">
                    <img id="qrImage" src="" alt="WhatsApp QR Code" class="w-64 h-64 object-contain">
                </div>
                <p class="text-sm text-yellow-500 text-center"><i class="fas fa-info-circle mr-1"></i> Buka WhatsApp di HP Anda > Perangkat Tautkan > Scan QR di atas</p>
                <button id="refreshBtn" class="mt-4 px-6 py-2 bg-white/10 hover:bg-white/20 rounded-xl transition text-sm flex items-center gap-2">
                    <i class="fas fa-sync-alt"></i> Refresh QR Code
                </button>
            </div>

            <!-- Connected State -->
            <div id="connectedContainer" class="hidden flex-col items-center py-8">
                <div class="w-24 h-24 bg-accent/20 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-check text-5xl text-accent"></i>
                </div>
                <h3 class="text-xl font-bold text-accent mb-2">WhatsApp Terhubung</h3>
                <p class="text-gray-400 text-center mb-6">Sistem saat ini aktif dan siap digunakan untuk pengiriman pesan otomatis.</p>
                <div class="flex gap-4">
                    <button id="testMsgBtn" class="px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center gap-2">
                        <i class="fas fa-paper-plane"></i> Test Kirim Pesan
                    </button>
                    <button id="logoutBtn" class="px-6 py-3 bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white font-bold rounded-xl border border-red-500/30 transition flex items-center gap-2">
                        <i class="fas fa-sign-out-alt"></i> Hapus Device
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Templates Section -->
    <div class="bg-[#111] border border-white/10 rounded-xl overflow-hidden mt-8">
        <div class="p-6 border-b border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-xl">Daftar Template Notifikasi</h3>
                <p class="text-sm text-gray-400">Template yang bisa digunakan saat absensi</p>
            </div>
            <a href="<?= base_url('superadmin/whatsapp-gateway/templates/create') ?>" class="px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Template
            </a>
        </div>
        
        <?php if (session()->getFlashdata('success')): ?>
            <div class="m-6 bg-green-500/10 border border-green-500/20 text-green-400 p-4 rounded-xl flex items-start gap-3">
                <i class="fas fa-check-circle mt-0.5"></i>
                <div><?= esc(session()->getFlashdata('success')) ?></div>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="m-6 bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-xl flex items-start gap-3">
                <i class="fas fa-times-circle mt-0.5"></i>
                <div><?= esc(session()->getFlashdata('error')) ?></div>
            </div>
        <?php endif; ?>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-black/40 text-gray-400">
                    <tr>
                        <th class="px-6 py-4 font-medium w-16">No</th>
                        <th class="px-6 py-4 font-medium w-64">Nama Template</th>
                        <th class="px-6 py-4 font-medium">Isi Pesan</th>
                        <th class="px-6 py-4 font-medium w-32 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php if (empty($templates)): ?>
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-3 block opacity-50"></i>
                                Belum ada template notifikasi
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($templates as $key => $template): ?>
                            <tr class="hover:bg-white/5 transition">
                                <td class="px-6 py-4 text-gray-400"><?= $key + 1 ?></td>
                                <td class="px-6 py-4 font-medium"><?= esc($template['nama_template']) ?></td>
                                <td class="px-6 py-4 text-gray-400 text-xs">
                                    <div class="whitespace-pre-wrap line-clamp-3"><?= esc($template['isi_pesan']) ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="<?= base_url('superadmin/whatsapp-gateway/templates/edit/' . $template['id']) ?>" class="p-2 text-yellow-400 hover:bg-yellow-400/10 rounded transition flex items-center gap-2" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= base_url('superadmin/whatsapp-gateway/templates/delete/' . $template['id']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus template ini?')" class="p-2 text-red-400 hover:bg-red-400/10 rounded transition flex items-center gap-2" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const API_URL = '<?= $api_url ?>';
    const csrfName = '<?= csrf_token() ?>';
    const csrfHash = '<?= csrf_hash() ?>';
    let pollingInterval;

    function updateStatusBadge(statusStr, isConnected) {
        const badge = document.getElementById('statusBadge');
        const startBtn = document.getElementById('startGatewayBtn');
        
        if (isConnected) {
            badge.className = 'inline-flex items-center gap-2 px-4 py-2 rounded-full bg-accent/20 text-accent font-bold';
            badge.innerHTML = '<i class="fas fa-check-circle"></i> Connected';
            startBtn.classList.add('hidden');
        } else if (statusStr === 'NEEDS_SCAN') {
            badge.className = 'inline-flex items-center gap-2 px-4 py-2 rounded-full bg-yellow-500/20 text-yellow-500 font-bold';
            badge.innerHTML = '<i class="fas fa-qrcode"></i> Menunggu Scan';
            startBtn.classList.add('hidden');
        } else if (statusStr === 'INITIALIZING') {
            badge.className = 'inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-500/20 text-blue-500 font-bold';
            badge.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Starting up...';
            startBtn.classList.add('hidden');
        } else if (statusStr === 'AUTHENTICATED') {
            badge.className = 'inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-500/20 text-blue-500 font-bold';
            badge.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Authenticating...';
            startBtn.classList.add('hidden');
        } else {
            badge.className = 'inline-flex items-center gap-2 px-4 py-2 rounded-full bg-red-500/20 text-red-500 font-bold';
            badge.innerHTML = '<i class="fas fa-times-circle"></i> Disconnected';
            startBtn.classList.remove('hidden');
        }
    }

    async function checkStatus() {
        try {
            const res = await fetch(`${API_URL}/status`);
            const data = await res.json();
            
            updateStatusBadge(data.status, data.connected);

            if (data.connected) {
                document.getElementById('qrContainer').classList.add('hidden');
                document.getElementById('connectedContainer').classList.remove('hidden');
                document.getElementById('connectedContainer').classList.add('flex');
            } else {
                document.getElementById('connectedContainer').classList.add('hidden');
                document.getElementById('connectedContainer').classList.remove('flex');
                
                if (data.status === 'NEEDS_SCAN') {
                    fetchQrCode();
                } else {
                    document.getElementById('qrContainer').classList.add('hidden');
                }
            }
        } catch (err) {
            console.error('Error fetching status:', err);
            updateStatusBadge('OFFLINE', false);
            document.getElementById('qrContainer').classList.add('hidden');
            document.getElementById('connectedContainer').classList.add('hidden');
        }
    }

    async function fetchQrCode() {
        try {
            const res = await fetch(`${API_URL}/qr`);
            const data = await res.json();
            if (data.success && data.qr) {
                document.getElementById('qrImage').src = data.qr;
                document.getElementById('qrContainer').classList.remove('hidden');
                document.getElementById('qrContainer').classList.add('flex');
            }
        } catch (err) {
            console.error('Error fetching QR:', err);
        }
    }

    document.getElementById('refreshBtn').addEventListener('click', () => {
        checkStatus();
    });

    document.getElementById('startGatewayBtn').addEventListener('click', async () => {
        const btn = document.getElementById('startGatewayBtn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Starting...';
        btn.disabled = true;

        try {
            // Need to pass CSRF token for POST requests
            const formData = new FormData();
            formData.append(csrfName, csrfHash);
            
            const res = await fetch(`${API_URL}/start`, { 
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            if (data.success) {
                updateStatusBadge('INITIALIZING', false);
            } else {
                alert('Gagal memulai gateway: ' + data.message);
            }
        } catch (err) {
            console.error('Error starting gateway:', err);
            alert('Tidak dapat terhubung ke Node Server. Pastikan terminal "node index.js" sudah berjalan. (Lihat console untuk detail error)');
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    });

    document.getElementById('testMsgBtn').addEventListener('click', async () => {
        const phone = prompt("Masukkan nomor WhatsApp untuk test (Cth: 08123456789):");
        if (!phone) return;

        const msg = "Halo! Ini pesan test dari sistem ALMAI WhatsApp Gateway.";
        try {
            const formData = new URLSearchParams();
            formData.append('phone', phone);
            formData.append('message', msg);
            formData.append(csrfName, csrfHash);

            const res = await fetch(`${API_URL}/send`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData.toString()
            });
            const data = await res.json();
            if (data.success) {
                alert('Pesan berhasil dikirim!');
            } else {
                alert('Gagal mengirim pesan: ' + data.message);
            }
        } catch (err) {
            alert('Gagal terhubung ke gateway Node.js.');
        }
    });

    document.getElementById('logoutBtn').addEventListener('click', async () => {
        if (!confirm('Apakah Anda yakin ingin menghapus perangkat (Logout dari WhatsApp)?')) return;

        const btn = document.getElementById('logoutBtn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        btn.disabled = true;

        try {
            const formData = new FormData();
            formData.append(csrfName, csrfHash);

            const res = await fetch(`${API_URL}/logout`, {
                method: 'POST',
                body: formData
            });
            const data = await res.json();
            if (data.success) {
                alert('Device berhasil dihapus (Logout).');
                updateStatusBadge('INITIALIZING', false);
            } else {
                alert('Gagal menghapus device: ' + data.message);
            }
        } catch (err) {
            alert('Terjadi kesalahan saat menghubungi server.');
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    });

    // Mulai polling setiap 5 detik
    checkStatus();
    pollingInterval = setInterval(checkStatus, 5000);
</script>

<?= $this->endSection() ?>
