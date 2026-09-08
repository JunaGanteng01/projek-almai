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
        tailwind.config = {
            theme: { extend: { colors: { 'accent': '#33e818' }, fontFamily: { sans: ['Montserrat', 'sans-serif'] } } }
        }
    </script>
    <style>
        html, body { background-color: #050505; color: #ffffff; }
        .module-item.completed { border-left: 3px solid #33e818; }
        .module-item.active { background: rgba(51,232,24,0.1); border-left: 3px solid #33e818; }
    </style>
</head>
<body class="antialiased">
    <div class="flex flex-col lg:flex-row min-h-screen">
        <!-- Mobile Header -->
        <div class="lg:hidden fixed top-0 left-0 right-0 z-50 bg-[#0a0a0a] border-b border-white/10 px-4 py-3 flex justify-between items-center">
            <a href="<?= base_url('user/dashboard#kelas') ?>" class="flex items-center gap-2 text-gray-400 hover:text-white">
                <i class="fas fa-arrow-left"></i>
                <span class="text-sm">Kembali</span>
            </a>
            <button onclick="toggleSidebar()" class="p-2 hover:bg-white/10 rounded-lg">
                <i class="fas fa-list text-xl"></i>
            </button>
        </div>

        <!-- Sidebar - Module List -->
        <aside id="sidebar" class="w-full lg:w-80 bg-[#0a0a0a] border-r border-white/10 fixed lg:relative h-full z-40 transform -translate-x-full lg:translate-x-0 transition-transform overflow-y-auto">
            <div class="p-4 border-b border-white/10 sticky top-0 bg-[#0a0a0a] z-10">
                <a href="<?= base_url('user/dashboard#kelas') ?>" class="hidden lg:flex items-center gap-2 text-gray-400 hover:text-white mb-4">
                    <i class="fas fa-arrow-left"></i>
                    <span class="text-sm">Kembali ke Dashboard</span>
                </a>
                <h2 class="font-bold text-lg line-clamp-2"><?= esc($kelas['title']) ?></h2>
                <div class="flex items-center gap-2 mt-2">
                    <img src="<?= esc($wpa['photo'] ?? '') ?>" class="w-6 h-6 rounded-full object-cover">
                    <span class="text-sm text-gray-400"><?= esc($wpa['name'] ?? 'WPA') ?></span>
                </div>
                <div class="mt-4">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-gray-400">Progress</span>
                        <span class="text-accent font-bold" id="progressPercent">0%</span>
                    </div>
                    <div class="w-full bg-white/10 rounded-full h-2">
                        <div class="bg-accent h-2 rounded-full transition-all" id="progressBar" style="width: 0%"></div>
                    </div>
                </div>
            </div>
            
            <!-- Modules List -->
            <div class="p-4" id="modulesList">
                <!-- Demo modules -->
                <div class="module-item active p-3 rounded-lg cursor-pointer hover:bg-white/5 transition mb-2">
                    <div class="flex items-start gap-3">
                        <div class="mt-1"><i class="fas fa-play-circle text-accent"></i></div>
                        <div class="flex-1">
                            <p class="text-xs text-gray-500">Modul 1</p>
                            <p class="font-medium text-sm">Pengenalan</p>
                            <p class="text-xs text-gray-500 mt-1"><i class="fas fa-clock mr-1"></i> 45 menit</p>
                        </div>
                    </div>
                </div>
                <div class="module-item p-3 rounded-lg cursor-pointer hover:bg-white/5 transition mb-2">
                    <div class="flex items-start gap-3">
                        <div class="mt-1"><i class="far fa-circle text-gray-600"></i></div>
                        <div class="flex-1">
                            <p class="text-xs text-gray-500">Modul 2</p>
                            <p class="font-medium text-sm">Dasar-Dasar Trading</p>
                            <p class="text-xs text-gray-500 mt-1"><i class="fas fa-clock mr-1"></i> 60 menit</p>
                        </div>
                    </div>
                </div>
                <div class="module-item p-3 rounded-lg cursor-pointer hover:bg-white/5 transition mb-2">
                    <div class="flex items-start gap-3">
                        <div class="mt-1"><i class="far fa-circle text-gray-600"></i></div>
                        <div class="flex-1">
                            <p class="text-xs text-gray-500">Modul 3</p>
                            <p class="font-medium text-sm">Technical Analysis</p>
                            <p class="text-xs text-gray-500 mt-1"><i class="fas fa-clock mr-1"></i> 55 menit</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Sidebar Overlay -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden" onclick="toggleSidebar()"></div>

        <!-- Main Content -->
        <main class="flex-1 pt-14 lg:pt-0">
            <!-- Video Player -->
            <div class="bg-black aspect-video w-full relative">
                <div class="absolute inset-0 flex items-center justify-center bg-[#111]">
                    <div class="text-center">
                        <div class="w-20 h-20 bg-accent/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-play text-accent text-3xl"></i>
                        </div>
                        <p class="text-gray-400">Pilih modul untuk mulai belajar</p>
                    </div>
                </div>
            </div>

            <!-- Content Below Video -->
            <div class="p-4 md:p-8">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-6">
                    <div>
                        <span class="text-accent text-sm font-medium">Modul 1</span>
                        <h1 class="text-xl md:text-2xl font-bold mt-1">Pengenalan</h1>
                        <p class="text-gray-400 text-sm mt-2"><i class="fas fa-clock mr-1"></i> 45 menit</p>
                    </div>
                    <div class="flex gap-3">
                        <button class="px-6 py-3 bg-accent text-black font-bold rounded-xl hover:bg-white transition text-sm">
                            <i class="fas fa-check mr-2"></i> Tandai Selesai
                        </button>
                        <button class="px-6 py-3 border border-white/20 rounded-xl hover:border-accent hover:text-accent transition text-sm">
                            Selanjutnya <i class="fas fa-arrow-right ml-2"></i>
                        </button>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="flex gap-4 border-b border-white/10 mb-6">
                    <button class="pb-3 border-b-2 border-accent text-accent font-medium text-sm">Materi</button>
                    <button class="pb-3 border-b-2 border-transparent text-gray-400 font-medium text-sm hover:text-white">Catatan</button>
                    <button class="pb-3 border-b-2 border-transparent text-gray-400 font-medium text-sm hover:text-white">Diskusi</button>
                </div>

                <!-- Tab Content -->
                <div class="bg-[#111] border border-white/10 rounded-xl p-6">
                    <h3 class="font-bold mb-4">Deskripsi Modul</h3>
                    <p class="text-gray-400 text-sm leading-relaxed"><?= esc($kelas['description'] ?? 'Deskripsi modul akan ditampilkan di sini.') ?></p>
                    
                    <?php 
                    $highlights = is_string($kelas['highlights'] ?? '') ? json_decode($kelas['highlights'], true) : ($kelas['highlights'] ?? []);
                    if (!empty($highlights)): 
                    ?>
                    <h4 class="font-bold mt-6 mb-3">Yang Akan Dipelajari:</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <?php foreach ($highlights as $highlight): ?>
                        <li class="flex items-start gap-2"><i class="fas fa-check text-accent mt-1"></i> <?= esc($highlight) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('-translate-x-full');
            document.getElementById('sidebarOverlay').classList.toggle('hidden');
        }
    </script>
</body>
</html>
