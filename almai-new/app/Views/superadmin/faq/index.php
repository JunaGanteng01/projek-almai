<?= $this->extend('superadmin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold">Kelola FAQ</h1>
        <p class="text-sm text-gray-500">Daftar semua pertanyaan dan jawaban umum.</p>
    </div>
    <a href="<?= base_url('superadmin/faq/create') ?>" class="w-full sm:w-auto px-6 py-2 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-center whitespace-nowrap">
        <i class="fas fa-plus mr-2"></i> Tambah FAQ
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 text-green-500 rounded-xl">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 text-red-500 rounded-xl">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-black/50">
                <tr>
                    <th class="text-center px-4 py-3 text-xs font-medium text-gray-400 w-16">ID</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Pertanyaan</th>
                    <th class="text-left px-4 py-3 text-xs font-medium text-gray-400">Jawaban</th>
                    <th class="text-center px-4 py-3 text-xs font-medium text-gray-400 w-32">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($faqs)): ?>
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-question-circle text-4xl mb-4"></i>
                            <p>Tidak ada FAQ ditemukan</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($faqs as $faq): ?>
                        <tr class="border-t border-white/5 hover:bg-white/5 transition-colors">
                            <td class="px-4 py-4 text-center text-gray-400 text-sm"><?= $faq['id'] ?></td>
                            <td class="px-4 py-4">
                                <p class="font-bold text-white text-sm"><?= esc($faq['question']) ?></p>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm text-gray-400 line-clamp-2">
                                    <?= strip_tags($faq['answer']) ?>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="<?= base_url('superadmin/faq/edit/' . $faq['id']) ?>" class="w-8 h-8 flex items-center justify-center bg-blue-500/20 text-blue-400 rounded-lg hover:bg-blue-500 hover:text-white transition" title="Edit">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    <button type="button" onclick="confirmDelete(<?= $faq['id'] ?>)" class="w-8 h-8 flex items-center justify-center bg-red-500/20 text-red-400 rounded-lg hover:bg-red-500 hover:text-white transition" title="Hapus">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                    <form id="delete-form-<?= $faq['id'] ?>" action="<?= base_url('superadmin/faq/delete/' . $faq['id']) ?>" method="POST" class="hidden">
                                        <?= csrf_field() ?>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus FAQ?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            background: '#111',
            color: '#fff',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'border border-white/10 rounded-2xl'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }
</script>
<?= $this->endSection() ?>
<?= $this->endSection() ?>
