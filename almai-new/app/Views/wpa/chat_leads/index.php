<?= $this->extend('wpa/layouts/main') ?>

<?= $this->section('styles') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="mb-8">
    <h1 class="text-3xl font-bold mb-2">Chat Leads</h1>
    <p class="text-gray-400">Kelola dan hubungi calon pembeli atau referral Anda.</p>
</div>

<!-- Stats Overview -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center text-blue-400">
                <i class="fas fa-comment-dots text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-400 uppercase tracking-wider">Anonymous Leads</p>
                <h3 class="text-2xl font-bold"><?= count($anonymousLeads) ?></h3>
            </div>
        </div>
        <p class="text-xs text-gray-500">Orang yang berinteraksi dengan AI Assistant Anda</p>
    </div>
    
    <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center text-purple-400">
                <i class="fas fa-user-check text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-400 uppercase tracking-wider">Registered Leads</p>
                <h3 class="text-2xl font-bold"><?= count($registeredUsers) ?></h3>
            </div>
        </div>
        <p class="text-xs text-gray-500">User yang sudah terdaftar dalam network Anda</p>
    </div>

    <div class="bg-accent/10 border border-accent/20 rounded-2xl p-6">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 bg-accent/20 rounded-xl flex items-center justify-center text-accent">
                <i class="fas fa-bullhorn text-xl"></i>
            </div>
            <div>
                <p class="text-sm text-gray-400 uppercase tracking-wider">Target Broadcast</p>
                <h3 class="text-2xl font-bold" id="selectedCount">0</h3>
            </div>
        </div>
        <button onclick="openBlastModal()" class="w-full py-2 bg-accent text-black font-bold rounded-lg hover:bg-white transition text-sm">
            Kirim Blast
        </button>
    </div>
</div>

<!-- Tabs -->
<div class="flex gap-4 mb-6 border-b border-white/10">
    <button onclick="switchTab('registered')" id="tab-registered" class="px-6 py-3 text-sm font-medium transition border-b-2 border-accent text-accent">
        User Terdaftar (<?= count($registeredUsers) ?>)
    </button>
    <button onclick="switchTab('anonymous')" id="tab-anonymous" class="px-6 py-3 text-sm font-medium transition border-b-2 border-transparent text-gray-400 hover:text-white">
        Anonymous Leads (<?= count($anonymousLeads) ?>)
    </button>
</div>

<!-- Registered Users Table -->
<div id="content-registered" class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-black/50">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider w-10">
                        <input type="checkbox" id="selectAllRegistered" onchange="toggleSelectAll('registered')" class="rounded bg-black border-white/20 text-accent focus:ring-accent">
                    </th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">User</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($registeredUsers)): ?>
                    <tr><td colspan="4" class="px-6 py-12 text-center text-gray-500">Belum ada user terdaftar dalam network Anda.</td></tr>
                <?php else: ?>
                    <?php foreach ($registeredUsers as $user): ?>
                        <tr class="hover:bg-white/5 transition group">
                            <td class="px-6 py-4">
                                <input type="checkbox" name="user_select" value="<?= $user['id'] ?>" onchange="updateSelectedCount()" class="registered-checkbox rounded bg-black border-white/20 text-accent focus:ring-accent">
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium"><?= esc($user['name']) ?></div>
                                <div class="text-[10px] text-accent font-mono"><?= esc($user['phone']) ?></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-1">
                                    <?php if ($user['is_buyer']): ?>
                                        <span class="px-2 py-0.5 bg-purple-500/20 text-purple-400 rounded-full text-[10px] font-bold">BUYER</span>
                                    <?php endif; ?>
                                    <?php if ($user['is_referral']): ?>
                                        <span class="px-2 py-0.5 bg-blue-500/20 text-blue-400 rounded-full text-[10px] font-bold">REFERRAL</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $user['phone']) ?>" target="_blank" class="text-green-500 hover:text-green-400 transition">
                                    <i class="fab fa-whatsapp text-xl"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Anonymous Leads Table (Hidden by default) -->
<div id="content-anonymous" class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-black/50">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">WhatsApp</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Waktu</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                <?php if (empty($anonymousLeads)): ?>
                    <tr><td colspan="4" class="px-6 py-12 text-center text-gray-500">Belum ada leads dari AI Assistant.</td></tr>
                <?php else: ?>
                    <?php foreach ($anonymousLeads as $lead): ?>
                        <tr class="hover:bg-white/5 transition">
                            <td class="px-6 py-4">
                                <div class="font-medium"><?= esc($lead->name) ?></div>
                                <div class="text-[10px] text-gray-500"><?= esc($lead->ip_address) ?></div>
                            </td>
                            <td class="px-6 py-4 font-mono text-sm text-accent">
                                <?= esc($lead->whatsapp) ?>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500">
                                <?= date('d M Y, H:i', strtotime($lead->created_at)) ?>
                            </td>
                            <td class="px-6 py-4">
                                <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $lead->whatsapp) ?>" target="_blank" class="text-green-500 hover:text-green-400 transition">
                                    <i class="fab fa-whatsapp text-xl"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Blast Modal -->
<div id="blastModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeBlastModal()"></div>
    <div class="bg-[#111] border border-white/10 w-full max-w-lg rounded-2xl overflow-hidden relative z-10 shadow-2xl">
        <div class="p-6 border-b border-white/10 flex justify-between items-center bg-accent/5">
            <h3 class="text-lg font-bold">Kirim Notifikasi Blast</h3>
            <button onclick="closeBlastModal()" class="text-gray-400 hover:text-white transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6">
            <div class="mb-4 p-3 bg-blue-500/10 border border-blue-500/20 rounded-lg text-sm text-blue-400">
                <i class="fas fa-info-circle mr-2"></i> Akan dikirim ke <span id="modalTargetCount" class="font-bold">0</span> user terpilih via notifikasi dashboard.
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Judul</label>
                    <input type="text" id="blastTitle" placeholder="Contoh: Kabar Gembira!" class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-accent outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Pesan</label>
                    <textarea id="blastMessage" rows="4" placeholder="Tuliskan pesan Anda di sini..." class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-accent outline-none resize-none"></textarea>
                </div>
            </div>
        </div>
        <div class="p-6 border-t border-white/10 bg-black/30 flex justify-end gap-3">
            <button onclick="closeBlastModal()" class="px-6 py-2 rounded-lg font-bold text-sm hover:bg-white/5 transition border border-white/10">Batal</button>
            <button onclick="submitBlast()" id="submitBlastBtn" class="px-8 py-2 bg-accent text-black font-bold rounded-lg hover:bg-white transition text-sm">
                Kirim Sekarang
            </button>
        </div>
    </div>
</div>

<script>
    function switchTab(tab) {
        document.getElementById('content-registered').classList.toggle('hidden', tab !== 'registered');
        document.getElementById('content-anonymous').classList.toggle('hidden', tab !== 'anonymous');
        
        document.getElementById('tab-registered').classList.toggle('border-accent', tab === 'registered');
        document.getElementById('tab-registered').classList.toggle('text-accent', tab === 'registered');
        document.getElementById('tab-registered').classList.toggle('border-transparent', tab !== 'registered');
        document.getElementById('tab-registered').classList.toggle('text-gray-400', tab !== 'registered');
        
        document.getElementById('tab-anonymous').classList.toggle('border-accent', tab === 'anonymous');
        document.getElementById('tab-anonymous').classList.toggle('text-accent', tab === 'anonymous');
        document.getElementById('tab-anonymous').classList.toggle('border-transparent', tab !== 'anonymous');
        document.getElementById('tab-anonymous').classList.toggle('text-gray-400', tab !== 'anonymous');
    }

    function toggleSelectAll(type) {
        const selectAll = document.getElementById('selectAllRegistered');
        const checkboxes = document.querySelectorAll('.registered-checkbox');
        checkboxes.forEach(cb => cb.checked = selectAll.checked);
        updateSelectedCount();
    }

    function updateSelectedCount() {
        const selected = document.querySelectorAll('input[name="user_select"]:checked').length;
        document.getElementById('selectedCount').textContent = selected;
    }

    function openBlastModal() {
        const selected = document.querySelectorAll('input[name="user_select"]:checked').length;
        if (selected === 0) {
            alert('Silakan pilih minimal satu user terdaftar.');
            return;
        }
        document.getElementById('modalTargetCount').textContent = selected;
        document.getElementById('blastModal').classList.remove('hidden');
    }

    function closeBlastModal() {
        document.getElementById('blastModal').classList.add('hidden');
    }

    function submitBlast() {
        const userIds = Array.from(document.querySelectorAll('input[name="user_select"]:checked')).map(cb => cb.value);
        const title = document.getElementById('blastTitle').value;
        const message = document.getElementById('blastMessage').value;
        const btn = document.getElementById('submitBlastBtn');

        if (!title || !message) {
            Swal.fire({
                icon: 'warning',
                title: 'Oops...',
                text: 'Judul dan Pesan wajib diisi!',
                background: '#111',
                color: '#fff',
                confirmButtonColor: '#33e818'
            });
            return;
        }

        btn.disabled = true;
        btn.textContent = 'Mengirim...';

        fetch('<?= base_url('wpa/dashboard/chat-leads/send-notification') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: new URLSearchParams({
                'user_ids[]': userIds,
                'title': title,
                'message': message,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    background: '#111',
                    color: '#fff',
                    confirmButtonColor: '#33e818'
                });
                closeBlastModal();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message,
                    background: '#111',
                    color: '#fff',
                    confirmButtonColor: '#33e818'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan sistem.',
                background: '#111',
                color: '#fff',
                confirmButtonColor: '#33e818'
            });
        })
        .finally(() => {
            btn.disabled = false;
            btn.textContent = 'Kirim Sekarang';
        });
    }
</script>
<?= $this->endSection() ?>
