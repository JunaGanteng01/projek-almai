<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan | Almai</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { 'accent': '#33e818', 'accent-dark': '#26ad12', 'dark-bg': '#0a0a0a', 'card-bg': '#111111' },
                    fontFamily: { sans: ['Montserrat', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        body { background-color: #050505; color: #ffffff; }
        .bg-grid { background-size: 40px 40px; background-image: linear-gradient(to right, rgba(255,255,255,0.05) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,0.05) 1px, transparent 1px); mask-image: linear-gradient(to bottom, black 40%, transparent 100%); }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen relative overflow-hidden">
    <!-- Background Effects -->
    <div class="absolute inset-0 bg-grid pointer-events-none z-0"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-accent/10 blur-[120px] rounded-full pointer-events-none z-0"></div>

    <div class="container mx-auto px-6 relative z-10 text-center">
        <!-- Logo -->
        <div class="mb-8 animate-bounce">
            <img src="<?= base_url('images/Alma.png') ?>" alt="Almai Logo" class="h-32 mx-auto drop-shadow-[0_0_15px_rgba(51,232,24,0.5)]">
        </div>

        <!-- 404 Text -->
        <h1 class="text-9xl font-black text-transparent bg-clip-text bg-gradient-to-b from-white to-gray-600 mb-4 tracking-tighter">404</h1>
        
        <h2 class="text-3xl md:text-4xl font-bold mb-6 text-white">Oops! Halaman Tidak Ditemukan</h2>
        
        <p class="text-gray-400 text-lg mb-10 max-w-lg mx-auto">
            Maaf, halaman yang Anda cari mungkin telah dihapus, dipindahkan, atau alamat URL salah.
        </p>

        <!-- Buttons -->
        <div class="flex flex-col md:flex-row gap-4 justify-center items-center">
            <a href="<?= base_url() ?>" class="px-8 py-3 bg-accent text-black font-bold rounded-full hover:bg-white hover:scale-105 transition duration-300 shadow-[0_0_20px_rgba(51,232,24,0.4)] flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                Kembali ke Beranda
            </a>
            <a href="<?= base_url('kontak') ?>" class="px-8 py-3 border border-white/20 text-white font-semibold rounded-full hover:border-accent hover:text-accent transition duration-300 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                Hubungi Bantuan
            </a>
        </div>
    </div>
</body>
</html>
