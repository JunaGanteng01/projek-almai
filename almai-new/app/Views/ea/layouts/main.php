<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Admin - Almai') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'accent': '#33e818',
                        'accent-hover': '#2bc214',
                    },
                    fontFamily: {
                        sans: ['Montserrat', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    html, body { 
        background-color: #050505; 
        color: #ffffff; 
        overflow-x: hidden;
        width: 100%;
        position: relative;
    }
    .sidebar-link.active { background: linear-gradient(90deg, rgba(51,232,24,0.2) 0%, transparent 100%); border-left: 3px solid #33e818; }
    .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    @media (max-width: 640px) { .hide-mobile { display: none !important; } }
    @media (max-width: 1024px) { .hide-tablet { display: none !important; } }
    
    /* Custom scrollbar for dark mode */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: #0a0a0a; }
    ::-webkit-scrollbar-thumb { background: #333; border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: #444; }

    /* Fix Date Input visibility in Dark Mode */
    input[type="date"] {
        color-scheme: dark;
        position: relative;
        background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" viewBox="0 0 24 24"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2zm-7 5h5v5h-5v-5z"/></svg>');
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 18px;
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
<body class="antialiased">
    <div class="flex min-h-screen">
    <!-- Sidebar -->
    <?= $this->include('ea/partials/sidebar') ?>

    <!-- Main Content -->
    <main class="flex-1 md:ml-64 transition-all duration-300 w-full max-w-full overflow-x-hidden pt-20 md:pt-24">
        <?= $this->include('ea/partials/header') ?>

        <section class="p-4 md:p-8 w-full">
                <?php if (session()->getFlashdata('success')): ?>
                <div class="bg-accent/20 border border-accent/50 text-accent px-4 py-3 rounded-xl mb-6">
                    <i class="fas fa-check-circle mr-2"></i> <?= session()->getFlashdata('success') ?>
                </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-xl mb-6">
                    <i class="fas fa-exclamation-circle mr-2"></i> <?= session()->getFlashdata('error') ?>
                </div>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>
            </section>
        </main>
    </div>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>
