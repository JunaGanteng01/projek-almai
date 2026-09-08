<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin - Almai') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'accent': '#33e818',
                        'accent-hover': '#2bc214',
                        'dark-bg': '#050505',
                        'dark-card': '#0c0c0c',
                        'dark-card-hover': '#121212',
                        'dark-border': 'rgba(255, 255, 255, 0.08)',
                        'dark-border-hover': 'rgba(51, 232, 24, 0.35)',
                    },
                    fontFamily: {
                        sans: ['Montserrat', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    boxShadow: {
                        'glow': '0 0 20px rgba(51, 232, 24, 0.15)',
                        'glow-lg': '0 0 35px rgba(51, 232, 24, 0.25)',
                        'card': '0 4px 20px rgba(0, 0, 0, 0.5)',
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        html, body { 
            background-color: #050505; 
            color: #ffffff; 
            overflow-x: hidden;
            width: 100%;
            font-family: 'Montserrat', sans-serif;
        }
        
        /* Modern Sidebar Active Link */
        .sidebar-link {
            transition: all 0.2s ease-in-out;
        }
        .sidebar-link.active { 
            background: linear-gradient(90deg, rgba(51,232,24,0.15) 0%, rgba(51,232,24,0.02) 100%); 
            border-left: 3px solid #33e818;
            color: #33e818;
            font-weight: 600;
        }
        .sidebar-link.active i {
            color: #33e818;
            filter: drop-shadow(0 0 8px rgba(51,232,24,0.5));
        }

        /* Glassmorphism Card System */
        .dash-card {
            background-color: #0c0c0c;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 1rem;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .dash-card:hover {
            border-color: rgba(255, 255, 255, 0.16);
        }
        .dash-card-interactive:hover {
            border-color: rgba(51, 232, 24, 0.35);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.6), 0 0 15px rgba(51, 232, 24, 0.08);
            transform: translateY(-2px);
        }

        .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        @media (max-width: 640px) { .hide-mobile { display: none !important; } }
        @media (max-width: 1024px) { .hide-tablet { display: none !important; } }
        
        /* Custom scrollbar for dark mode */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #070707; }
        ::-webkit-scrollbar-thumb { background: #222; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #333; }

        /* Custom Date Picker Styling */
        input[type="date"] {
            color-scheme: dark;
            position: relative;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="%2333e818" viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2zm-7 5h5v5h-5v-5z"/></svg>');
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
        }
        input[type="date"]::-webkit-calendar-picker-indicator {
            opacity: 0;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            cursor: pointer;
        }
    </style>
</head>
<body class="antialiased bg-[#050505] text-white selection:bg-accent/30 selection:text-white min-h-screen">
    <div class="flex min-h-screen relative">
        <!-- Sidebar Inclusion -->
        <?= $this->include('superadmin/partials/sidebar') ?>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 md:pl-64 transition-all duration-300 w-full min-h-screen">
            <?= $this->include('superadmin/partials/header') ?>

            <main class="flex-1 p-4 sm:p-6 lg:p-8 pt-20 sm:pt-24 lg:pt-24 pb-20 sm:pb-28 max-w-[1600px] w-full mx-auto">
                <?php if (session()->getFlashdata('success')): ?>
                <div class="bg-accent/10 border border-accent/40 text-accent px-4 py-3 rounded-xl mb-5 flex items-center justify-between shadow-[0_0_15px_rgba(51,232,24,0.1)]">
                    <div class="flex items-center gap-2.5">
                        <i class="fas fa-check-circle text-lg"></i>
                        <span class="text-sm font-medium"><?= session()->getFlashdata('success') ?></span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-accent/60 hover:text-accent p-1 text-sm"><i class="fas fa-times"></i></button>
                </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                <div class="bg-red-500/10 border border-red-500/40 text-red-400 px-4 py-3 rounded-xl mb-5 flex items-center justify-between shadow-[0_0_15px_rgba(239,68,68,0.1)]">
                    <div class="flex items-center gap-2.5">
                        <i class="fas fa-exclamation-circle text-lg"></i>
                        <span class="text-sm font-medium"><?= session()->getFlashdata('error') ?></span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-red-400/60 hover:text-red-400 p-1 text-sm"><i class="fas fa-times"></i></button>
                </div>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>
            </main>
        </div>
    </div>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>
