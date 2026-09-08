<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold"><?= $title ?></h1>
            <p class="text-gray-400 text-sm">Kelola daftar template format notifikasi WhatsApp</p>
        </div>
        <a href="<?= base_url('admin/whatsapp-gateway/templates/create') ?>" class="px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Template
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-500/10 border border-green-500/20 text-green-400 p-4 rounded-xl flex items-start gap-3">
            <i class="fas fa-check-circle mt-0.5"></i>
            <div><?= esc(session()->getFlashdata('success')) ?></div>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-500/10 border border-red-500/20 text-red-400 p-4 rounded-xl flex items-start gap-3">
            <i class="fas fa-times-circle mt-0.5"></i>
            <div><?= esc(session()->getFlashdata('error')) ?></div>
        </div>
    <?php endif; ?>

    <div class="bg-[#111] border border-white/10 rounded-xl overflow-hidden">
        <div class="p-6 border-b border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="font-bold">Daftar Template Notifikasi</h3>
        </div>
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
                                        <a href="<?= base_url('admin/whatsapp-gateway/templates/edit/' . $template['id']) ?>" class="p-2 text-yellow-400 hover:bg-yellow-400/10 rounded transition flex items-center gap-2" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= base_url('admin/whatsapp-gateway/templates/delete/' . $template['id']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus template ini?')" class="p-2 text-red-400 hover:bg-red-400/10 rounded transition flex items-center gap-2" title="Hapus">
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
<?= $this->endSection() ?>
