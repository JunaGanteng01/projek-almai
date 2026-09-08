<?= $this->extend('laporan-kegiatan/layouts/main') ?>

<?= $this->section('content') ?>
<?php 
$isEdit = isset($signal);
$actionUrl = $isEdit ? base_url('laporan-kegiatan/signal/update/'.$signal['id']) : base_url('laporan-kegiatan/signal/store');
?>

<div class="w-full pb-12">
    <!-- Header -->
    <div class="mb-6 flex flex-col md:flex-row justify-between md:items-center gap-4 px-6 md:px-0">
        <h1 class="text-xl md:text-2xl font-black text-white uppercase tracking-tight"><?= $isEdit ? 'Edit Signal' : 'Tambah Signal' ?></h1>
        <a href="<?= base_url('laporan-kegiatan/signal') ?>" class="bg-black/50 hover:bg-black border border-white/10 text-white px-6 py-2 rounded-lg text-xs font-bold uppercase tracking-widest shadow transition flex items-center justify-center">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    <form action="<?= $actionUrl ?>" method="POST" class="space-y-6">
        <?= csrf_field() ?>

        <div class="bg-[#111] border border-white/10 rounded-2xl p-6 shadow-xl space-y-6">
            <div class="border-b border-white/5 pb-4 mb-4">
                <h2 class="text-lg font-bold text-accent uppercase tracking-widest flex items-center gap-2">
                    <i class="fas fa-signal"></i> Data Signal
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- WPA -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Penyedia (WPA) *</label>
                    <select name="wpa_id" required class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors">
                        <option value="">-- Pilih WPA --</option>
                        <?php foreach($wpas as $w): ?>
                            <option value="<?= $w['id'] ?>" <?= ($signal['wpa_id'] ?? '') == $w['id'] ? 'selected' : '' ?>><?= esc($w['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Jml Peserta -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Jumlah Klien/Peserta</label>
                    <input type="number" name="jml_peserta" value="<?= esc($signal['jml_peserta'] ?? 0) ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors placeholder-gray-600" min="0">
                </div>
                
                <!-- Jml Nasihat -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Jumlah Nasihat</label>
                    <input type="number" name="jml_nasihat" value="<?= esc($signal['jml_nasihat'] ?? 0) ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors placeholder-gray-600" min="0">
                </div>

                <!-- Produk -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Produk yang Dibahas</label>
                    <input type="text" name="produk" value="<?= esc($signal['produk'] ?? '') ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors placeholder-gray-600" placeholder="Contoh: XAUUSD, Forex">
                </div>

                <!-- Media -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Media</label>
                    <input type="text" name="media" value="<?= esc($signal['media'] ?? '') ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors placeholder-gray-600" placeholder="Contoh: Telegram, WhatsApp">
                </div>

                <!-- Tanggal -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Tanggal</label>
                    <input type="date" name="tanggal" value="<?= esc($signal['tanggal'] ?? '') ?>" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors">
                </div>

                <!-- Keterangan -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Keterangan</label>
                    <textarea name="keterangan" rows="3" class="w-full bg-black/50 border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent focus:ring-1 focus:ring-accent transition-colors placeholder-gray-600" placeholder="Tambahkan keterangan..."><?= esc($signal['keterangan'] ?? '') ?></textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="bg-accent hover:bg-white text-black px-10 py-4 rounded-xl font-black uppercase tracking-widest shadow-lg transition-all flex items-center gap-3 group">
                <i class="fas fa-save group-hover:scale-110 transition-transform"></i>
                Simpan Data
            </button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>
