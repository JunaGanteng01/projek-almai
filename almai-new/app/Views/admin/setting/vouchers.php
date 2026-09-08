<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Kelola Voucher') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = { theme: { extend: { colors: { 'accent': '#33e818' }, fontFamily: { sans: ['Montserrat', 'sans-serif'] } } } }
    </script>
    <style>html, body { background-color: #050505; color: #ffffff; }</style>
</head>
<body class="antialiased">
    <div class="min-h-screen p-4 md:p-8">
        <div class="max-w-2xl mx-auto">
            <a href="<?= base_url('admin/setting') ?>" class="inline-flex items-center gap-2 text-gray-400 hover:text-white mb-6">
                <i class="fas fa-arrow-left"></i> Kembali ke Settings
            </a>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="bg-accent/20 border border-accent/30 text-accent px-4 py-3 rounded-xl mb-4"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <div class="bg-[#111] border border-white/10 rounded-2xl p-6 md:p-8 mb-6">
                <h1 class="text-2xl font-bold mb-6">Tambah Voucher</h1>
                <form action="<?= base_url('admin/setting/vouchers/save') ?>" method="post" class="space-y-4">
                    <?= csrf_field() ?>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Kode Voucher</label>
                            <input type="text" name="code" required class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none uppercase" placeholder="ALMAI10">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Tipe Diskon</label>
                            <select name="type" class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none">
                                <option value="percent">Persentase (%)</option>
                                <option value="fixed">Nominal (Rp)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Nilai Diskon</label>
                            <input type="number" name="discount" required class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none" placeholder="10">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-400 mb-2">Max Penggunaan</label>
                            <input type="number" name="max_use" class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none" placeholder="100">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm text-gray-400 mb-2">Berlaku Sampai</label>
                            <input type="date" name="expiry" class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none">
                        </div>
                    </div>
                    <button type="submit" class="w-full py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                        <i class="fas fa-plus mr-2"></i> Tambah Voucher
                    </button>
                </form>
            </div>

            <div class="bg-[#111] border border-white/10 rounded-2xl p-6 md:p-8">
                <h2 class="text-xl font-bold mb-4">Daftar Voucher</h2>
                <?php if (empty($vouchers)): ?>
                    <p class="text-gray-500 text-center py-8">Belum ada voucher</p>
                <?php else: ?>
                    <div class="space-y-3">
                        <?php foreach ($vouchers as $code => $voucher): ?>
                        <div class="flex items-center justify-between p-4 bg-black/50 rounded-xl">
                            <div>
                                <p class="font-mono font-bold text-accent"><?= esc($code) ?></p>
                                <p class="text-sm text-gray-400">
                                    <?= $voucher['type'] === 'percent' ? $voucher['discount'] . '%' : 'Rp ' . number_format($voucher['discount'], 0, ',', '.') ?>
                                    <?php if (!empty($voucher['expiry'])): ?>
                                        • Exp: <?= date('d M Y', strtotime($voucher['expiry'])) ?>
                                    <?php endif; ?>
                                    <?php if (!empty($voucher['max_use'])): ?>
                                        • <?= $voucher['used'] ?? 0 ?>/<?= $voucher['max_use'] ?> used
                                    <?php endif; ?>
                                </p>
                            </div>
                            <form action="<?= base_url('admin/setting/vouchers/delete/' . urlencode($code)) ?>" method="post" onsubmit="return confirm('Hapus voucher ini?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-500/10 rounded-lg transition">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
