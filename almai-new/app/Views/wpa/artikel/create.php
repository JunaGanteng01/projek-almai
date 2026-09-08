<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = { theme: { extend: { colors: { 'accent': '#33e818' }, fontFamily: { sans: ['Montserrat', 'sans-serif'] } } } }
    </script>
    <style>
        html, body { background-color: #050505; color: #ffffff; }
        .sidebar-link.active { background: linear-gradient(90deg, rgba(51,232,24,0.2) 0%, transparent 100%); border-left: 3px solid #33e818; }
    </style>
</head>
<body class="antialiased">
    <div class="flex min-h-screen">
        <!-- Mobile Header -->
        <div class="md:hidden fixed top-0 left-0 right-0 z-50 bg-[#0a0a0a] border-b border-white/10 px-4 py-3 flex items-center gap-3">
            <a href="<?= base_url('wpa/dashboard/artikel') ?>" class="p-2 hover:bg-white/10 rounded-lg">
                <i class="fas fa-arrow-left"></i>
            </a>
            <span class="font-bold">Buat Artikel</span>
        </div>

        <!-- Sidebar (Desktop Only) -->
        <aside class="hidden md:block w-64 bg-[#0a0a0a] border-r border-white/10 fixed h-full z-50">
            <div class="p-6 border-b border-white/10">
                <a href="<?= base_url('/') ?>" class="flex items-center gap-2">
                    <img src="https://almai.id/wpa/alma.gif" alt="ALMAI" class="h-8">
                    <span class="font-bold text-lg">ALMAI</span>
                </a>
                <p class="text-xs text-gray-500 mt-1">WPA Dashboard</p>
            </div>
            <nav class="p-4">
                <ul class="space-y-2">
                    <li><a href="<?= base_url('wpa/dashboard') ?>" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/5 transition">
                        <i class="fas fa-chart-pie w-5"></i> Overview</a></li>
                    <li><a href="<?= base_url('wpa/dashboard/kelas') ?>" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/5 transition">
                        <i class="fas fa-graduation-cap w-5"></i> Kelas Saya</a></li>
                    <li><a href="<?= base_url('wpa/dashboard/artikel') ?>" class="sidebar-link active flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/5 transition">
                        <i class="fas fa-newspaper w-5"></i> Artikel Saya</a></li>
                    <li><a href="<?= base_url('wpa/dashboard/students') ?>" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/5 transition">
                        <i class="fas fa-users w-5"></i> Students</a></li>
                    <li><a href="<?= base_url('wpa/dashboard/earnings') ?>" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/5 transition">
                        <i class="fas fa-wallet w-5"></i> Earnings</a></li>
                    <li><a href="<?= base_url('wpa/dashboard/profile') ?>" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-white/5 transition">
                        <i class="fas fa-user w-5"></i> Profile</a></li>
                </ul>
            </nav>
            <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-white/10">
                <a href="<?= base_url('wpa/logout') ?>" class="w-full flex items-center gap-3 px-4 py-3 text-red-400 hover:bg-red-500/10 rounded-lg transition">
                    <i class="fas fa-sign-out-alt w-5"></i> Logout
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 md:ml-64 pt-14 md:pt-0">
            <header class="bg-[#0a0a0a] border-b border-white/10 px-4 md:px-8 py-4 flex items-center gap-4 sticky top-14 md:top-0 z-10">
                <a href="<?= base_url('wpa/dashboard/artikel') ?>" class="hidden md:flex p-2 hover:bg-white/10 rounded-lg transition">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="text-lg md:text-xl font-bold">Buat Artikel Baru</h1>
                    <p class="text-xs md:text-sm text-gray-500 hidden md:block">Artikel akan diverifikasi oleh admin sebelum dipublikasikan</p>
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

                <form action="<?= base_url('wpa/dashboard/artikel/store') ?>" method="post" class="max-w-4xl">
                    <?= csrf_field() ?>
                    
                    <div class="grid md:grid-cols-3 gap-6">
                        <!-- Main Content -->
                        <div class="md:col-span-2 space-y-6">
                            <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
                                <h3 class="font-bold mb-4 flex items-center gap-2">
                                    <i class="fas fa-edit text-accent"></i> Konten Artikel
                                </h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Judul Artikel *</label>
                                        <input type="text" name="title" required value="<?= old('title') ?>" 
                                            class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" 
                                            placeholder="Judul artikel yang menarik...">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Excerpt / Ringkasan *</label>
                                        <textarea name="excerpt" required rows="2" 
                                            class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none resize-none" 
                                            placeholder="Ringkasan singkat artikel (akan ditampilkan di list)..."><?= old('excerpt') ?></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Konten Artikel *</label>
                                        <textarea name="content" required rows="12" 
                                            class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none resize-none" 
                                            placeholder="Tulis konten artikel di sini... (mendukung HTML)"><?= old('content') ?></textarea>
                                        <p class="text-xs text-gray-500 mt-1">Mendukung tag HTML seperti &lt;h2&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;strong&gt;, dll.</p>
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
                                        <label class="block text-sm text-gray-400 mb-2">Kategori *</label>
                                        <select name="category" required class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
                                            <option value="">Pilih Kategori</option>
                                            <option value="Tips Trading" <?= old('category') === 'Tips Trading' ? 'selected' : '' ?>>Tips Trading</option>
                                            <option value="Fundamental" <?= old('category') === 'Fundamental' ? 'selected' : '' ?>>Fundamental</option>
                                            <option value="Technical" <?= old('category') === 'Technical' ? 'selected' : '' ?>>Technical</option>
                                            <option value="Strategy" <?= old('category') === 'Strategy' ? 'selected' : '' ?>>Strategy</option>
                                            <option value="Crypto" <?= old('category') === 'Crypto' ? 'selected' : '' ?>>Crypto</option>
                                            <option value="Commodity" <?= old('category') === 'Commodity' ? 'selected' : '' ?>>Commodity</option>
                                            <option value="Psychology" <?= old('category') === 'Psychology' ? 'selected' : '' ?>>Psychology</option>
                                            <option value="News" <?= old('category') === 'News' ? 'selected' : '' ?>>News</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Waktu Baca</label>
                                        <select name="read_time" class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none">
                                            <option value="3 min">3 min</option>
                                            <option value="5 min" selected>5 min</option>
                                            <option value="7 min">7 min</option>
                                            <option value="10 min">10 min</option>
                                            <option value="15 min">15 min</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
                                <h3 class="font-bold mb-4 flex items-center gap-2">
                                    <i class="fas fa-coins text-accent"></i> Harga Poin
                                </h3>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm text-gray-400 mb-2">Harga Poin *</label>
                                        <input type="number" name="poin_price" required value="<?= old('poin_price', 100) ?>" min="0"
                                            class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" 
                                            placeholder="100">
                                        <p class="text-xs text-gray-500 mt-1">Poin yang dibutuhkan untuk membaca</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <input type="checkbox" name="is_free" id="is_free" value="1" <?= old('is_free') ? 'checked' : '' ?>
                                            class="w-5 h-5 rounded bg-black border-white/20 text-accent focus:ring-accent">
                                        <label for="is_free" class="text-sm">Artikel Gratis</label>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-[#111] border border-white/10 rounded-2xl p-6">
                                <h3 class="font-bold mb-4 flex items-center gap-2">
                                    <i class="fas fa-image text-accent"></i> Thumbnail
                                </h3>
                                <div>
                                    <label class="block text-sm text-gray-400 mb-2">URL Gambar</label>
                                    <input type="url" name="thumbnail" value="<?= old('thumbnail') ?>" 
                                        class="w-full bg-black border border-white/20 rounded-xl px-4 py-3 focus:border-accent focus:outline-none" 
                                        placeholder="https://example.com/image.jpg">
                                    <p class="text-xs text-gray-500 mt-1">Kosongkan untuk gambar default</p>
                                </div>
                            </div>

                            <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-2xl p-4">
                                <p class="text-sm text-yellow-500"><i class="fas fa-info-circle mr-2"></i>Artikel akan diverifikasi oleh admin sebelum dipublikasikan.</p>
                            </div>

                            <div class="flex gap-3">
                                <a href="<?= base_url('wpa/dashboard/artikel') ?>" class="flex-1 py-3 border border-white/20 rounded-xl text-center hover:bg-white/5 transition">
                                    Batal
                                </a>
                                <button type="submit" class="flex-1 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition">
                                    <i class="fas fa-paper-plane mr-2"></i> Kirim
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
