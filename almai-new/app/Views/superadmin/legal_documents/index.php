<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold mb-2">Dokumen Legal</h1>
            <p class="text-gray-400 text-sm">Kelola dokumen legal untuk checkout dan transaksi</p>
        </div>
        <a href="<?= base_url('superadmin/legal-documents/create') ?>" class="px-6 py-3 bg-accent hover:bg-accent/80 text-black font-semibold rounded-lg transition-colors">
            <i class="fas fa-plus mr-2"></i>Tambah Dokumen
        </a>
    </div>

    <!-- Documents List -->
    <div class="bg-[#111] border border-white/10 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-black/50 border-b border-white/10">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Tipe</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Slug</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Dibuat</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    <?php if (empty($documents)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <i class="fas fa-file-alt text-4xl mb-3 opacity-50"></i>
                                <p>Belum ada dokumen legal</p>
                                <a href="<?= base_url('superadmin/legal-documents/create') ?>" class="inline-block mt-3 text-accent hover:underline">
                                    <i class="fas fa-plus mr-1"></i>Tambah dokumen pertama
                                </a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($documents as $doc): ?>
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-file-contract text-blue-400"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold"><?= esc($doc['title']) ?></p>
                                            <p class="text-xs text-gray-500">ID: <?= $doc['id'] ?></p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php
                                    $typeColors = [
                                        'perjanjian' => 'bg-blue-500/20 text-blue-400',
                                        'risiko' => 'bg-yellow-500/20 text-yellow-400',
                                        'other' => 'bg-gray-500/20 text-gray-400',
                                    ];
                                    $typeLabels = [
                                        'perjanjian' => 'Perjanjian',
                                        'risiko' => 'Risiko',
                                        'other' => 'Lainnya',
                                    ];
                                    $colorClass = $typeColors[$doc['type']] ?? $typeColors['other'];
                                    $label = $typeLabels[$doc['type']] ?? 'Lainnya';
                                    ?>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium <?= $colorClass ?>">
                                        <?= $label ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <code class="text-xs bg-black/50 px-2 py-1 rounded text-gray-400"><?= esc($doc['slug']) ?></code>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick="toggleStatus(<?= $doc['id'] ?>, <?= $doc['is_active'] ?>)" 
                                            class="px-3 py-1 rounded-full text-xs font-medium transition-colors <?= $doc['is_active'] ? 'bg-green-500/20 text-green-400 hover:bg-green-500/30' : 'bg-red-500/20 text-red-400 hover:bg-red-500/30' ?>">
                                        <?= $doc['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-400">
                                    <?= date('d M Y', strtotime($doc['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <button onclick="previewDocument(<?= $doc['id'] ?>)" 
                                                class="p-2 bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 rounded-lg transition-colors" 
                                                title="Preview">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="<?= base_url('superadmin/legal-documents/edit/' . $doc['id']) ?>" 
                                           class="p-2 bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-400 rounded-lg transition-colors" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="deleteDocument(<?= $doc['id'] ?>, '<?= esc($doc['title']) ?>')" 
                                                class="p-2 bg-red-500/20 hover:bg-red-500/30 text-red-400 rounded-lg transition-colors" 
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
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

<!-- Preview Modal -->
<div id="previewModal" class="hidden fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4">
    <div class="bg-[#111] border border-white/10 rounded-xl w-full max-w-4xl max-h-[90vh] flex flex-col">
        <div class="flex justify-between items-center p-6 border-b border-white/10">
            <h3 id="previewTitle" class="text-xl font-bold">Preview Dokumen</h3>
            <button type="button" onclick="closePreview()" class="text-gray-400 hover:text-white transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-6">
            <div id="previewContent" class="prose prose-invert max-w-none bg-white text-black p-8 rounded-lg">
                <div class="text-center py-8">
                    <i class="fas fa-spinner fa-spin text-4xl text-gray-400"></i>
                    <p class="mt-3 text-gray-500">Loading...</p>
                </div>
            </div>
        </div>
        <div class="p-6 border-t border-white/10">
            <button type="button" onclick="closePreview()" class="px-6 py-2 bg-white/10 hover:bg-white/20 text-white rounded-lg transition-colors">
                <i class="fas fa-times mr-2"></i>Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function toggleStatus(id, currentStatus) {
        if (confirm('Ubah status dokumen ini?')) {
            fetch(`<?= base_url('superadmin/legal-documents/toggle-active/') ?>${id}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Gagal mengubah status');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan');
            });
        }
    }

    function previewDocument(id) {
        document.getElementById('previewModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        fetch(`<?= base_url('superadmin/legal-documents/preview/') ?>${id}`)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const content = doc.querySelector('#previewContent');
                if (content) {
                    document.getElementById('previewContent').innerHTML = content.innerHTML;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('previewContent').innerHTML = '<p class="text-red-500">Gagal memuat preview</p>';
            });
    }

    function closePreview() {
        document.getElementById('previewModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function deleteDocument(id, title) {
        if (confirm(`Hapus dokumen "${title}"?\n\nTindakan ini tidak dapat dibatalkan.`)) {
            window.location.href = `<?= base_url('superadmin/legal-documents/delete/') ?>${id}`;
        }
    }

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closePreview();
        }
    });

    document.getElementById('previewModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePreview();
        }
    });
</script>
<?= $this->endSection() ?>
