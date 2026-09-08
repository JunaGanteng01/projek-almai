<?= $this->extend('cwpa/layouts/main') ?>

<?= $this->section('content') ?>

<div class="mb-8 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-black text-white uppercase tracking-tight">Partner Report Detail #<?= $report['id'] ?></h2>
        <p class="text-gray-500 text-sm">Review detailed information for your submitted advocacy case</p>
    </div>
    <a href="<?= base_url('cwpa/dashboard/advokasi') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/5 border border-white/10 text-white font-bold rounded-xl hover:bg-white/10 transition text-sm">
        <i class="fas fa-arrow-left text-xs"></i> Kembali
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-[#111] border border-white/10 rounded-3xl overflow-hidden shadow-2xl">
            <div class="p-6 md:p-8">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-10 pb-6 border-b border-white/5">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-accent/10 rounded-2xl flex items-center justify-center text-accent text-2xl">
                            <i class="fas fa-file-shield"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-white uppercase tracking-tight"><?= esc($report['broker_name']) ?></h3>
                            <p class="text-xs text-gray-500 uppercase font-bold tracking-widest"><?= date('d M Y', strtotime($report['created_at'])) ?></p>
                        </div>
                    </div>
                    <div class="px-5 py-2 rounded-2xl bg-white/5 border border-white/10">
                        <span class="text-xs font-black uppercase tracking-widest <?= $report['status'] == 'pending' ? 'text-yellow-500' : 'text-accent' ?>">
                            <?= str_replace('_', ' ', $report['status']) ?>
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10 text-white">
                    <div>
                        <label class="block text-[10px] text-gray-500 uppercase font-black mb-2 tracking-widest">Kategori Masalah</label>
                        <p class="text-sm font-bold"><?= esc($report['category_problem'] ?: '-') ?></p>
                    </div>
                    <div>
                        <label class="block text-[10px] text-gray-500 uppercase font-black mb-2 tracking-widest">Sub Kategori</label>
                        <p class="text-sm font-bold"><?= esc($report['sub_category'] ?: '-') ?></p>
                    </div>
                    <div>
                        <label class="block text-[10px] text-gray-500 uppercase font-black mb-2 tracking-widest">Jenis Trading</label>
                        <p class="text-sm font-bold"><?= esc($report['trading_type'] ?: '-') ?></p>
                    </div>
                    <div>
                        <label class="block text-[10px] text-gray-500 uppercase font-black mb-2 tracking-widest">Estimasi Loss</label>
                        <p class="text-lg font-black text-accent">Rp <?= number_format($report['loss_amount'], 0, ',', '.') ?></p>
                    </div>
                </div>

                <div class="mb-10">
                    <label class="block text-[10px] text-gray-500 uppercase font-black mb-4 tracking-widest">Kronologi Kejadian</label>
                    <div class="bg-black/40 border border-white/5 rounded-2xl p-6">
                        <p class="text-sm text-gray-300 leading-relaxed whitespace-pre-wrap"><?= esc($report['chronology']) ?></p>
                    </div>
                </div>

                <?php if ($report['file_attachment']): ?>
                <div>
                    <label class="block text-[10px] text-gray-500 uppercase font-black mb-4 tracking-widest">Bukti Terlampir</label>
                    <a href="<?= base_url($report['file_attachment']) ?>" target="_blank" class="inline-flex items-center gap-3 px-6 py-4 bg-white/5 border border-white/10 rounded-2xl hover:bg-white/10 transition-all group">
                        <i class="fas fa-paperclip text-accent text-xl"></i>
                        <span class="text-xs font-black text-white uppercase tracking-widest">Download Evidence</span>
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-[#111] border border-white/10 rounded-3xl p-8">
            <h4 class="text-xs font-black text-accent uppercase tracking-[0.2em] mb-6">Akun Info</h4>
            <div class="space-y-4">
                <div>
                    <label class="block text-[9px] text-gray-600 uppercase font-black">Account ID</label>
                    <p class="text-xs font-bold text-white"><?= esc($report['trading_account'] ?: '-') ?></p>
                </div>
                <div>
                    <label class="block text-[9px] text-gray-600 uppercase font-black">Server</label>
                    <p class="text-xs font-bold text-white"><?= esc($report['broker_server'] ?: '-') ?></p>
                </div>
            </div>
        </div>
        
        <?php if ($report['admin_note']): ?>
        <div class="bg-white/5 border border-white/10 rounded-3xl p-8">
            <h4 class="text-xs font-black text-accent uppercase tracking-[0.2em] mb-4">Official Response</h4>
            <p class="text-xs text-gray-400 leading-relaxed italic"><?= esc($report['admin_note']) ?></p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
