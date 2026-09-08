<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>

<div class="mb-6 lg:mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold bg-gradient-to-r from-white to-gray-400 bg-clip-text text-transparent">
            <?= esc($pageTitle) ?>
        </h1>
        <p class="text-sm text-gray-400 mt-1">Kirim notifikasi global ke berbagai grup pengguna</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Form Area -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 relative">
        <form id="broadcastForm" class="space-y-4">
            <?= csrf_field() ?>
            
            <?php if (session()->getFlashdata('success')): ?>
                <div class="bg-green-500/10 border border-green-500/30 text-green-500 p-4 rounded-xl text-sm mb-4">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="bg-red-500/10 border border-red-500/30 text-red-500 p-4 rounded-xl text-sm mb-4">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <!-- Target Role -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Pilih Role User</label>
                <select name="role" id="role_selector" required class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-accent focus:ring-1 focus:ring-accent outline-none">
                    <option value="" disabled selected>-- Pilih Role --</option>
                    <option value="all">Semua User (Global)</option>
                    <option value="wpa">WPA</option>
                    <option value="cwpa">CWPA</option>
                    <option value="pro">User PRO</option>
                    <option value="user">User Biasa</option>
                </select>
            </div>

            <!-- Target User -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Pilih Nama (Sumber Downline)</label>
                <select name="target_user_id" id="user_selector" required disabled class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-accent focus:ring-1 focus:ring-accent outline-none disabled:opacity-50">
                    <option value="" disabled selected>-- Pilih Role Terlebih Dahulu --</option>
                </select>
                <p class="text-xs text-gray-500 mt-1">Notifikasi akan dikirim ke seluruh downline dari user yang dipilih.</p>
            </div>

            <!-- Tipe Notifikasi -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Jenis Notifikasi</label>
                <select name="type" required class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-accent focus:ring-1 focus:ring-accent outline-none">
                    <option value="info">Info (Biru)</option>
                    <option value="success">Success (Hijau)</option>
                    <option value="warning">Warning (Kuning)</option>
                    <option value="error">Error / Darurat (Merah)</option>
                </select>
            </div>

            <!-- Judul -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Judul Notifikasi</label>
                <input type="text" name="title" required placeholder="Contoh: Pengumuman Webinar Terbaru!" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-accent focus:ring-1 focus:ring-accent outline-none transition">
            </div>

            <!-- Pesan -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Pesan Notifikasi</label>
                <textarea name="message" required rows="4" placeholder="Detail pengumuman..." class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-accent focus:ring-1 focus:ring-accent outline-none transition"></textarea>
            </div>

            <!-- Link URL (Opsional) -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Link Tujuan (Opsional)</label>
                <input type="text" name="link" placeholder="Contoh: https://almai.id/promo" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-accent focus:ring-1 focus:ring-accent outline-none transition">
                <p class="text-xs text-gray-500 mt-1">Kosongkan jika hanya pesan saja tanpa URL tujuan.</p>
            </div>

            <!-- Submit -->
            <div class="pt-4">
                <button type="submit" id="btnSubmitBroadcast" class="w-full bg-accent text-black font-bold rounded-xl px-4 py-3 hover:bg-white transition flex items-center justify-center gap-2">
                    <i class="fas fa-paper-plane"></i> Kirim Broadcast Notifikasi
                </button>
            </div>
        </form>
    </div>

    <!-- Progress Overlay -->
    <div id="broadcastOverlay" class="absolute inset-0 z-50 bg-black/90 backdrop-blur-sm hidden flex-col items-center justify-center rounded-2xl border border-accent/20">
        <div class="inline-block relative w-16 h-16 mb-4">
            <div class="absolute inset-0 border-4 border-accent/20 rounded-full"></div>
            <div class="absolute inset-0 border-4 border-accent rounded-full border-t-transparent animate-spin"></div>
        </div>
        <h3 class="text-xl font-bold text-white mb-2" id="broadcastStatusTitle">Menyiapkan Broadcast...</h3>
        <p class="text-gray-400 text-sm mb-6 max-w-[80%] text-center" id="broadcastStatusText">Mohon tunggu, sedang menghitung jumlah penerima target.</p>
        
        <div class="w-4/5 max-w-sm">
            <div class="w-full bg-[#111] rounded-full h-3 mb-2 border border-white/10 overflow-hidden">
                <div id="broadcastProgressBar" class="bg-accent h-full rounded-full transition-all duration-300 relative" style="width: 0%">
                    <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                </div>
            </div>
            <div class="flex justify-between text-xs text-gray-400 font-medium font-mono">
                <span id="broadcastProgressCount">0</span>
                <span id="broadcastProgressTotal">0 User</span>
            </div>
        </div>
    </div>

    <!-- Info/Petunjuk -->
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6 h-fit">
        <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
            <i class="fas fa-info-circle text-accent"></i> Petunjuk Penggunaan
        </h3>
        <p class="text-sm text-gray-400 leading-relaxed mb-4">
            Fitur <b>Broadcast Notifikasi</b> digunakan untuk mengirim pesan massal secara langsung ke dashboard para pengguna. Notifikasi ini akan memunculkan tanda bel berwarna merah di akun mereka.
        </p>

        <ul class="space-y-3">
            <li class="flex items-start gap-3">
                <div class="mt-1 w-6 h-6 rounded-full bg-accent/20 text-accent flex items-center justify-center shrink-0">
                    <i class="fas fa-sitemap text-xs text-black"></i>
                </div>
                <div>
                    <strong class="text-sm text-white">Broadcast ke Jaringan</strong>
                    <p class="text-xs text-gray-500 mt-0.5">Notifikasi tidak dikirim langsung ke user yang dipilih, melainkan ke SELURUH DOWNLINE (hingga kedalaman tak terbatas) di jaringan mereka.</p>
                </div>
            </li>
            <li class="flex items-start gap-3">
                <div class="mt-1 w-6 h-6 rounded-full bg-blue-500/20 text-blue-500 flex items-center justify-center shrink-0">
                    <i class="fas fa-search text-xs"></i>
                </div>
                <div>
                    <strong class="text-sm text-white">Pilih Berdasarkan Role</strong>
                    <p class="text-xs text-gray-500 mt-0.5">Filter nama pengguna agar lebih mudah dicari, mulai dari WPA, CWPA, hingga user biasa.</p>
                </div>
            </li>
        </ul>

        <div class="mt-6 p-4 bg-red-500/10 border border-red-500/30 rounded-xl flex items-start gap-3">
            <i class="fas fa-exclamation-triangle text-red-500 mt-1"></i>
            <p class="text-xs leading-relaxed text-gray-300">
                <strong class="text-red-400 block mb-1">Aksi ini tidak bisa dibatalkan!</strong>
                Pastikan judul dan isi pesan tidak ada salah ketik. Notifikasi akan langsung masuk dan bisa dibaca oleh para penerima target.
            </p>
        </div>
    </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelector = document.getElementById('role_selector');
    const userSelector = document.getElementById('user_selector');

    roleSelector.addEventListener('change', async function() {
        const role = this.value;
        if (!role) return;

        if (role === 'all') {
            userSelector.innerHTML = '<option value="all" selected>Kirim ke SELURUH USER di Sistem</option>';
            userSelector.disabled = false;
            return;
        }

        // Tampilkan state loading
        userSelector.disabled = true;
        userSelector.innerHTML = '<option value="" disabled selected>Loading data...</option>';

        try {
            const response = await fetch(`<?= base_url('superadmin/broadcast/get-users-by-role') ?>?role=${role}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) throw new Error('Gagal mengambil data pengguna');
            
            const users = await response.json();
            
            userSelector.innerHTML = '<option value="" disabled selected>-- Pilih Nama --</option>';
            
            if (users.length === 0) {
                userSelector.innerHTML = '<option value="" disabled selected>Tidak ada user ditemukan untuk role ini</option>';
            } else {
                users.forEach(user => {
                    const option = document.createElement('option');
                    option.value = user.id;
                    option.textContent = `${user.name} (${user.email})`;
                    userSelector.appendChild(option);
                });
                userSelector.disabled = false;
            }

        } catch (error) {
            console.error('Error fetching users:', error);
            userSelector.innerHTML = '<option value="" disabled selected>Error memuat data</option>';
        }
    });

    // Handle Form Submit with AJAX Chunking
    const broadcastForm = document.getElementById('broadcastForm');
    const overlay = document.getElementById('broadcastOverlay');
    const statusTitle = document.getElementById('broadcastStatusTitle');
    const statusText = document.getElementById('broadcastStatusText');
    const progressBar = document.getElementById('broadcastProgressBar');
    const progressCount = document.getElementById('broadcastProgressCount');
    const progressTotal = document.getElementById('broadcastProgressTotal');
    const btnSubmit = document.getElementById('btnSubmitBroadcast');

    broadcastForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        Swal.fire({
            title: 'Kirim Broadcast?',
            text: "Notifikasi akan dikirim ke target yang dipilih. Pastikan judul dan pesan sudah benar.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ffb700',
            cancelButtonColor: '#333',
            confirmButtonText: 'Ya, Kirim!',
            cancelButtonText: 'Batal',
            background: '#111',
            color: '#fff',
            customClass: {
                popup: 'border border-white/10 rounded-2xl',
                confirmButton: 'text-black font-bold rounded-xl px-6 py-2.5',
                cancelButton: 'text-white rounded-xl px-6 py-2.5'
            }
        }).then(async (result) => {
            if (result.isConfirmed) {
                const formData = new FormData(this);
                const submitUrl = '<?= base_url("superadmin/broadcast/prepare") ?>';
                
                // Show Overlay
                overlay.classList.remove('hidden');
                overlay.classList.add('flex');
                btnSubmit.disabled = true;
                
                statusTitle.textContent = "Menyiapkan Data...";
                statusText.textContent = "Mencari daftar target pengguna berdasarkan kriteria.";
                progressBar.style.width = '0%';
                progressCount.textContent = '0';
                progressTotal.textContent = 'Menghitung...';

                try {
                    // Step 1: Prepare
                    const prepResponse = await fetch(submitUrl, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const prepData = await prepResponse.json();

                    if (!prepData.success) {
                        throw new Error(prepData.message || 'Gagal menyiapkan data target.');
                    }

                    const targetIds = prepData.target_ids;
                    const total = prepData.total;
                    progressTotal.textContent = total + ' User';
                    
                    if (total === 0) {
                        throw new Error('Tidak ada user target yang ditemukan.');
                    }

                    statusTitle.textContent = "Mengirim Broadcast...";
                    
                    // Step 2: Process in Chunks
                    const chunkSize = 100; // Process 100 users at a time
                    let processed = 0;
                    
                    const title = formData.get('title');
                    const message = formData.get('message');
                    const type = formData.get('type');
                    const link = formData.get('link');
                    
                    const csrfName = '<?= csrf_token() ?>';
                    const csrfInput = document.querySelector(`input[name="${csrfName}"]`);
                    let csrfHash = csrfInput ? csrfInput.value : '';

            for (let i = 0; i < targetIds.length; i += chunkSize) {
                const chunk = targetIds.slice(i, i + chunkSize);
                
                statusText.textContent = `Mengirim ke user ${i + 1} sampai ${Math.min(i + chunkSize, total)}...`;
                
                const processResponse = await fetch('<?= base_url("superadmin/broadcast/process-batch") ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        [csrfName]: csrfHash,
                        target_ids: chunk,
                        title: title,
                        message: message,
                        type: type,
                        link: link
                    })
                });

                const processData = await processResponse.json();
                
                if (processResponse.ok && processData.success) {
                    processed += processData.processed;
                    const percent = Math.round((processed / total) * 100);
                    progressBar.style.width = percent + '%';
                    progressCount.textContent = processed;
                } else {
                    console.error('Batch error:', processData);
                    // Lanjut ke batch berikutnya meskipun error (atau bisa di-throw)
                }
            }

            // Selesai
            statusTitle.textContent = "Selesai!";
            statusTitle.classList.replace('text-white', 'text-accent');
            statusText.textContent = `Berhasil mengirim ${processed} notifikasi.`;
            progressBar.classList.replace('bg-accent', 'bg-green-500');
            
            setTimeout(() => {
                window.location.reload();
            }, 2000);

        } catch (error) {
            statusTitle.textContent = "Terjadi Kesalahan";
            statusTitle.classList.replace('text-white', 'text-red-500');
            statusText.textContent = error.message;
            progressBar.classList.replace('bg-accent', 'bg-red-500');
            
            setTimeout(() => {
                overlay.classList.add('hidden');
                overlay.classList.remove('flex');
                btnSubmit.disabled = false;
                // Reset styles
                statusTitle.classList.replace('text-red-500', 'text-white');
                progressBar.classList.replace('bg-red-500', 'bg-accent');
            }, 3000);
        }
            } // end if isConfirmed
        }); // end Swal.then
    });
});
</script>
<?= $this->endSection() ?>
