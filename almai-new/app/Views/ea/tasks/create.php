<?= $this->extend('ea/layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-6 flex justify-between items-center">
    <div>
        <a href="<?= base_url('ea/tasks') ?>" class="text-gray-400 hover:text-white transition flex items-center gap-2 mb-2">
            <i class="fas fa-arrow-left"></i> Kembali ke Manajemen Task
        </a>
        <h2 class="text-2xl font-bold"><?= $title ?></h2>
    </div>
</div>

<div class="bg-[#111] border border-white/10 rounded-2xl p-6 mb-8 w-full">
    <form action="<?= base_url('ea/tasks/store') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="mb-4">
            <label class="block text-gray-400 text-sm font-bold mb-2">Judul Task *</label>
            <input type="text" name="title" required class="w-full bg-black/40 border border-white/10 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500 transition" placeholder="Masukkan judul task...">
        </div>

        <div class="mb-4">
            <label class="block text-gray-400 text-sm font-bold mb-2">Deskripsi</label>
            <textarea name="description" rows="3" class="w-full bg-black/40 border border-white/10 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500 transition" placeholder="Tambahkan deskripsi atau detail task..."></textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-gray-400 text-sm font-bold mb-2">Assigned To</label>
                <select name="assigned_to" class="w-full bg-black/40 border border-white/10 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500 transition">
                    <option value="">Pilih Divisi / Tim</option>
                    <option class="bg-[#222] text-white" value="Finance">Finance</option>
                    <option class="bg-[#222] text-white" value="Legal">Legal</option>
                    <option class="bg-[#222] text-white" value="Marketing">Marketing</option>
                    <option class="bg-[#222] text-white" value="IT">IT</option>
                </select>
            </div>
            <div>
                <label class="block text-gray-400 text-sm font-bold mb-2">Deadline *</label>
                <input type="datetime-local" name="due_date" required class="w-full bg-black/40 border border-white/10 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500 transition" style="color-scheme: dark;">
            </div>
        </div>

        <div class="mb-4">
            <label class="block text-gray-400 text-sm font-bold mb-2">Prioritas</label>
            <select name="priority" class="w-full bg-black/40 border border-white/10 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500 transition">
                <option class="bg-[#222] text-white" value="Low">Low</option>
                <option class="bg-[#222] text-white" value="Medium" selected>Medium</option>
                <option class="bg-[#222] text-white" value="High">High</option>
            </select>
        </div>

        <div class="mb-6">
            <label class="block text-gray-400 text-sm font-bold mb-2">Attachment (Opsional)</label>
            <input type="file" name="attachment" class="w-full bg-black/40 border border-white/10 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-blue-500 transition">
            <p class="text-xs text-gray-500 mt-1">Maksimal 5MB. Format: PDF, JPG, PNG, DOCX, ZIP.</p>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="text-black font-bold py-2 px-6 rounded-lg transition shadow-lg" style="background-color: #33E818;">
                <i class="fas fa-save mr-2"></i> Simpan Task
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
