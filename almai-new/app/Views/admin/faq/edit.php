<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="mb-6">
    <a href="<?= base_url('admin/faq') ?>" class="inline-flex items-center text-gray-400 hover:text-accent transition mb-4">
        <i class="fas fa-arrow-left mr-2 text-sm"></i> Kembali ke Daftar
    </a>
    <h1 class="text-2xl font-bold">Edit FAQ</h1>
    <p class="text-sm text-gray-500">Perbarui pertanyaan atau jawaban FAQ.</p>
</div>

<div class="max-w-4xl">
    <form action="<?= base_url('admin/faq/update/' . $faq['id']) ?>" method="POST" class="bg-[#111] border border-white/10 rounded-2xl overflow-hidden p-6 md:p-8">
        <?= csrf_field() ?>
        
        <div class="space-y-6">
            <!-- Pertanyaan -->
            <div>
                <label for="question" class="block text-sm font-bold text-gray-400 mb-2">Pertanyaan</label>
                <input type="text" name="question" id="question" value="<?= old('question', $faq['question']) ?>" 
                    class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-white <?= session('errors.question') ? 'border-red-500' : '' ?>"
                    placeholder="Masukkan pertanyaan..." required>
                <?php if (session('errors.question')): ?>
                    <p class="text-xs text-red-500 mt-1"><?= session('errors.question') ?></p>
                <?php endif; ?>
            </div>

            <!-- Jawaban -->
            <div>
                <label for="answer" class="block text-sm font-bold text-gray-400 mb-2">Jawaban</label>
                <textarea name="answer" id="answer" rows="8" 
                    class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none text-white <?= session('errors.answer') ? 'border-red-500' : '' ?>"
                    placeholder="Masukkan jawaban..." required><?= old('answer', $faq['answer']) ?></textarea>
                <p class="text-xs text-gray-500 mt-2">Halaman FAQ mendukung format HTML sederhana.</p>
                <?php if (session('errors.answer')): ?>
                    <p class="text-xs text-red-500 mt-1"><?= session('errors.answer') ?></p>
                <?php endif; ?>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 flex items-center gap-4">
                <button type="submit" class="px-8 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition flex items-center gap-2">
                    <i class="fas fa-save"></i> Perbarui FAQ
                </button>
                <a href="<?= base_url('admin/faq') ?>" class="px-8 py-3 bg-white/5 text-white font-bold rounded-xl hover:bg-white/10 transition">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
