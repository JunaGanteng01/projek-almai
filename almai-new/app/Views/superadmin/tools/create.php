<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = { theme: { extend: { colors: { 'accent': '#33e818' } } } }
    </script>
    <style>
        html, body { background-color: #050505; color: #ffffff; }
        .sidebar-link.active { background: linear-gradient(90deg, rgba(51,232,24,0.2) 0%, transparent 100%); border-left: 3px solid #33e818; }
    </style>
</head>
<body class="antialiased">
    <div class="flex min-h-screen">
        <?= $this->include('superadmin/partials/sidebar') ?>

        <main class="flex-1 md:ml-64 pt-14 md:pt-0">
            <header class="bg-[#0a0a0a] border-b border-white/10 px-4 md:px-8 py-4 flex items-center gap-4 sticky top-14 md:top-0 z-10">
                <a href="<?= base_url('superadmin/tools') ?>" class="p-2 hover:bg-white/10 rounded-lg transition">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-lg md:text-xl font-bold">Tambah Tools Baru</h1>
                    <p class="text-xs md:text-sm text-gray-500 hidden md:block">Buat tools trading baru</p>
                </div>
            </header>

            <section class="p-4 md:p-8">
                <?php if (session()->getFlashdata('errors')): ?>
                <div class="bg-red-500/20 border border-red-500/30 text-red-400 px-4 py-3 rounded-xl mb-4">
                    <ul class="list-disc list-inside">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>

                <form action="<?= base_url('superadmin/tools/store') ?>" method="post" class="max-w-4xl">
                    <?= csrf_field() ?>
                    
                    <div class="grid md:grid-cols-3 gap-6">
                        <!-- Main Content -->
                        <div class="md:col-span-2 space-y-6">
                            <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
                                <h3 class="font-bold mb-4 flex items-center gap-2">
                                    <i class="fas fa-info-circle text-accent"></i> Informasi Tools
                                </h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Nama Tools *</label>
                                        <input type="text" name="name" required value="<?= old('name') ?>" 
                                            class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" 
                                            placeholder="Gold Scalper EA Pro">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Deskripsi *</label>
                                        <textarea name="description" required rows="4" 
                                            class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none resize-none" 
                                            placeholder="Deskripsi lengkap tentang tools..."><?= old('description') ?></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Fitur (satu per baris)</label>
                                        <textarea name="features" rows="5" 
                                            class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none resize-none" 
                                            placeholder="Auto Trading 24/7&#10;Risk Management Built-in&#10;Multi Timeframe Analysis"><?= old('features') ?></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Kompatibilitas (satu per baris)</label>
                                        <textarea name="compatibility" rows="3" 
                                            class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none resize-none" 
                                            placeholder="MT4&#10;MT5&#10;Windows"><?= old('compatibility') ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
                                <h3 class="font-bold mb-4 flex items-center gap-2">
                                    <i class="fas fa-link text-accent"></i> URL & Download
                                </h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">URL Thumbnail</label>
                                        <input type="url" name="thumbnail" value="<?= old('thumbnail') ?>" 
                                            class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" 
                                            placeholder="https://example.com/image.jpg">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">URL Download</label>
                                        <input type="url" name="download_url" value="<?= old('download_url') ?>" 
                                            class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" 
                                            placeholder="https://drive.google.com/...">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">URL Dokumentasi</label>
                                        <input type="url" name="documentation_url" value="<?= old('documentation_url') ?>" 
                                            class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" 
                                            placeholder="https://docs.example.com/...">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="space-y-6">
                            <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
                                <h3 class="font-bold mb-4 flex items-center gap-2">
                                    <i class="fas fa-cog text-accent"></i> Pengaturan
                                </h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">WPA (Opsional)</label>
                                        <select name="wpa_id" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
                                            <option value="">Tidak ada WPA</option>
                                            <?php foreach ($wpaList as $wpa): ?>
                                            <option value="<?= $wpa['id'] ?>" <?= old('wpa_id') == $wpa['id'] ? 'selected' : '' ?>>
                                                <?= esc(explode(',', $wpa['name'])[0]) ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Kategori *</label>
                                        <select name="category" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
                                            <option value="">Pilih Kategori</option>
                                            <?php foreach ($categories as $cat): ?>
                                            <option value="<?= esc($cat) ?>" <?= old('category') === $cat ? 'selected' : '' ?>><?= esc($cat) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Platform *</label>
                                        <select name="platform" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
                                            <option value="">Pilih Platform</option>
                                            <?php foreach ($platforms as $plat): ?>
                                            <option value="<?= esc($plat) ?>" <?= old('platform') === $plat ? 'selected' : '' ?>><?= esc($plat) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Status</label>
                                        <select name="status" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
                                            <option value="active">Aktif</option>
                                            <option value="inactive">Nonaktif</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
                                <h3 class="font-bold mb-4 flex items-center gap-2">
                                    <i class="fas fa-tag text-accent"></i> Harga
                                </h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Harga Jual (Rp) *</label>
                                        <input type="number" name="price" required value="<?= old('price') ?>" min="0"
                                            class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" 
                                            placeholder="5000000">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Harga Asli (Rp)</label>
                                        <input type="number" name="original_price" value="<?= old('original_price') ?>" min="0"
                                            class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" 
                                            placeholder="8000000">
                                        <p class="text-xs text-gray-500 mt-1">Untuk menampilkan diskon</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <a href="<?= base_url('superadmin/tools') ?>" class="flex-1 py-3 border border-white/20 rounded-xl text-center hover:bg-white/5 transition">
                                    Batal
                                </a>
                                <button type="submit" class="flex-1 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                                    <i class="fas fa-save mr-2"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </section>
        </main>
    </div>
</body>
</html>
