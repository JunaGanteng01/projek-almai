<?php
$this->setVar('pageTitle', 'Detail Verifikasi CWPA');
$this->setVar('pageSubtitle', 'Detail pengajuan CWPA: ' . esc($submission['user_name']));
?>
<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="<?= base_url('superadmin/cwpa/verification') ?>" class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Verifikasi
        </a>
    </div>

    <!-- Main Content -->
    <div class="bg-[#111] border border-white/10 rounded-xl p-6">
        <!-- Re-use the modal content here directly -->
        <?= $this->include('admin/cwpa/detail_verification_modal') ?>
    </div>
</div>

<!-- Verify/Reject Modal -->
<div id="verifyModal" class="fixed inset-0 bg-black/80 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-[#111] border border-white/10 rounded-2xl max-w-md w-full">
        <div class="border-b border-white/10 p-6">
            <h3 id="verifyModalTitle" class="text-xl font-bold text-white">Verifikasi CWPA</h3>
        </div>

        <form id="verifyForm" class="p-6 space-y-4">
            <input type="hidden" id="verifyCwpaId" name="cwpa_id">
            <input type="hidden" id="verifyAction" name="action" value="verify">

            <div>
                <p class="text-sm text-gray-400 mb-2">Nama CWPA:</p>
                <p id="verifyCwpaName" class="font-bold text-white"></p>
            </div>

            <div>
                <label id="verifyNoteLabel" class="block text-sm font-medium text-gray-300 mb-2">Catatan Verifikasi (Opsional)</label>
                <textarea name="verification_notes" id="verifyNotes" placeholder="Masukkan catatan..."
                    class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-3 text-white placeholder-gray-500 focus:border-accent focus:outline-none transition resize-none"
                    rows="4"></textarea>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeVerifyModal()"
                    class="flex-1 px-4 py-3 bg-white/5 border border-white/10 rounded-lg text-white font-medium hover:bg-white/10 transition">
                    Batal
                </button>
                <button type="submit" id="verifySubmitBtn"
                    class="flex-1 px-4 py-3 bg-accent text-black font-bold rounded-lg hover:bg-white transition">
                    <i class="fas fa-check mr-2"></i> Verifikasi
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openVerifyModal(cwpaId, cwpaName, action = 'verify') {
        document.getElementById('verifyCwpaId').value = cwpaId;
        document.getElementById('verifyCwpaName').textContent = cwpaName;
        document.getElementById('verifyAction').value = action;
        document.getElementById('verifyModal').classList.remove('hidden');

        const title = document.getElementById('verifyModalTitle');
        const btn = document.getElementById('verifySubmitBtn');
        const noteLabel = document.getElementById('verifyNoteLabel');
        const noteInput = document.getElementById('verifyNotes');

        if (action === 'reject') {
            title.textContent = 'Tolak Pengajuan CWPA';
            title.className = 'text-xl font-bold text-red-500';

            btn.innerHTML = '<i class="fas fa-times mr-2"></i> Tolak Pengajuan';
            btn.className = 'flex-1 px-4 py-3 bg-red-500 text-white font-bold rounded-lg hover:bg-red-600 transition';

            noteLabel.textContent = 'Alasan Penolakan (Wajib)';
            noteInput.placeholder = 'Masukkan alasan penolakan...';
            noteInput.required = true;
        } else {
            title.textContent = 'Verifikasi CWPA';
            title.className = 'text-xl font-bold text-white';

            btn.innerHTML = '<i class="fas fa-check mr-2"></i> Verifikasi';
            btn.className = 'flex-1 px-4 py-3 bg-accent text-black font-bold rounded-lg hover:bg-white transition';

            noteLabel.textContent = 'Catatan Verifikasi (Opsional)';
            noteInput.placeholder = 'Masukkan catatan verifikasi...';
            noteInput.required = false;
        }
    }

    function closeVerifyModal() {
        document.getElementById('verifyModal').classList.add('hidden');
        document.getElementById('verifyForm').reset();
    }

    document.getElementById('verifyForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const cwpaId = document.getElementById('verifyCwpaId').value;
        const action = document.getElementById('verifyAction').value;
        const notes = document.getElementById('verifyNotes').value;

        // Create form data
        const formData = new FormData();
        formData.append('verification_notes', notes);

        const endpoint = action === 'reject' ? 'unverify' : 'verify';

        fetch(`<?= base_url('superadmin/cwpa/') ?>${endpoint}/${cwpaId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeVerifyModal();
                    window.location.reload();
                } else {
                    alert('Gagal memproses: ' + data.message);
                }
            })
            .catch(error => {
                alert('Terjadi kesalahan: ' + error.message);
            });
    });

    // Document Viewer Logic (Copied from verification page)
    function viewDocument(url, title) {
        const ext = url.split('.').pop().toLowerCase();
        const images = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (images.includes(ext)) {
            let modal = document.getElementById('docViewerModal');
            if (!modal) {
                modal = document.createElement('div');
                modal.id = 'docViewerModal';
                modal.className = 'fixed inset-0 bg-black/95 z-[9999] flex items-center justify-center p-4';
                modal.innerHTML = `
            <div class="relative max-w-5xl max-h-[90vh]">
                <button onclick="closeDocModal()" class="absolute -top-12 right-0 px-4 py-2 bg-white/10 rounded-full text-white hover:bg-white/20 transition">
                    <i class="fas fa-times mr-2"></i> Close
                </button>
                <img id="docViewerImg" src="" class="max-w-full max-h-[90vh] object-contain rounded border border-white/10 text-white">
                <p id="docViewerTitle" class="text-center text-white mt-4 font-bold"></p>
            </div>
        `;
                document.body.appendChild(modal);
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) closeDocModal();
                });
            }

            document.getElementById('docViewerImg').src = url;
            document.getElementById('docViewerTitle').textContent = title;
            modal.classList.remove('hidden');
            modal.style.display = 'flex';
        } else {
            window.open(url, '_blank');
        }
    }

    function closeDocModal() {
        const modal = document.getElementById('docViewerModal');
        if (modal) modal.style.display = 'none';
    }

    // Close modals on background click/ESC
    document.getElementById('verifyModal').addEventListener('click', function(e) {
        if (e.target === this) closeVerifyModal();
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeVerifyModal();
            closeDocModal();
        }
    });
</script>
<?= $this->endSection() ?>