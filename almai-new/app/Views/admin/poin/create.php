<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Tambah Poin') ?></title>
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
        <div class="max-w-xl mx-auto">
            <a href="<?= base_url('admin/poin') ?>" class="inline-flex items-center gap-2 text-gray-400 hover:text-white mb-6">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

            <div class="bg-[#111] border border-white/10 rounded-2xl p-6 md:p-8">
                <h1 class="text-2xl font-bold mb-6">Tambah Poin</h1>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl mb-4">
                        <ul class="list-disc list-inside text-sm">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('admin/poin/store') ?>" method="post" class="space-y-4">
                    <?= csrf_field() ?>
                    
                    <div>
                        <label class="block text-sm text-gray-400 mb-2">User</label>
                        <select name="user_id" required class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none">
                            <option value="">Pilih User</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= $user['id'] ?>" <?= old('user_id') == $user['id'] ? 'selected' : '' ?>>
                                    <?= esc($user['name']) ?> (<?= esc($user['email']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Tipe</label>
                        <select name="type" required class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none">
                            <option value="earn" <?= old('type') === 'earn' ? 'selected' : '' ?>>Earn (Tambah)</option>
                            <option value="bonus" <?= old('type') === 'bonus' ? 'selected' : '' ?>>Bonus (Tambah)</option>
                            <option value="redeem" <?= old('type') === 'redeem' ? 'selected' : '' ?>>Redeem (Kurang)</option>
                            <option value="expired" <?= old('type') === 'expired' ? 'selected' : '' ?>>Expired (Kurang)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Jumlah Poin</label>
                        <input type="number" name="amount" value="<?= old('amount') ?>" required min="1" class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none" placeholder="100">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400 mb-2">Deskripsi</label>
                        <input type="text" name="description" value="<?= old('description') ?>" required class="w-full px-4 py-3 bg-black border border-white/20 rounded-xl focus:border-accent focus:outline-none" placeholder="Bonus pendaftaran">
                    </div>

                    <div class="flex gap-3 pt-4">
                        <a href="<?= base_url('admin/poin') ?>" class="flex-1 py-3 border border-white/20 rounded-xl hover:bg-white/5 transition text-center">Batal</a>
                        <button type="submit" class="flex-1 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
