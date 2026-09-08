<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'CWPA Dashboard - Almai') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous">

    <script>
        // Aggressively suppress Tailwind CDN warning
        (function() {
            const _warn = console.warn;
            console.warn = function() {
                // Convert all arguments to string and check
                const args = Array.from(arguments);
                const message = args.join(' ');

                // Block Tailwind production warnings
                if (message.includes('tailwind') ||
                    message.includes('cdn.tailwindcss') ||
                    message.includes('production')) {
                    return;
                }

                // Allow other warnings
                _warn.apply(console, arguments);
            };
        })();
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'accent': '#33e818'
                    },
                    fontFamily: {
                        sans: ['Montserrat', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <style>
        html, body { 
            background-color: #050505; 
            color: #ffffff;
            overflow-x: hidden;
        }
        .sidebar-link.active { 
            background: linear-gradient(90deg, rgba(51,232,24,0.2) 0%, transparent 100%); 
            border-left: 3px solid #33e818; 
        }
        .bottom-nav-item.active {
            color: #33e818;
        }
        .table-responsive { overflow-x: auto; }
        @media (max-width: 767px) { 
            main { padding-bottom: 64px; } 
        }
        @media (max-width: 768px) { 
            .hide-mobile { display: none !important; } 
        }
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        /* Better mobile navigation */
        @media (max-width: 767px) {
            .mobile-nav-item {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
        }
        /* Prevent content jump when headers become fixed */
        .header-spacer {
            transition: height 0.3s ease;
        }
    </style>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?= $this->renderSection('styles') ?>
</head>
<body class="antialiased">
    <div class="flex min-h-screen">
        <?= $this->include('cwpa/partials/sidebar') ?>

        <!-- Main Content -->
        <main class="flex-1 w-full max-w-[100vw] overflow-x-hidden md:ml-64 pt-16 md:pt-20 pb-20 md:pb-8">
            <?= $this->include('cwpa/partials/header') ?>

            <section class="px-3 py-4 md:px-8">
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
    </div>

    <script>
        // toggleProfileMenu has been moved to header.php for better mobile/desktop handling


        document.addEventListener('click', function(e) {
            if (!e.target.closest('#profileMenu') && !e.target.closest('button[onclick="toggleProfileMenu()"]')) {
                document.getElementById('profileMenu')?.classList.add('hidden');
            }
        });

        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = `flex items-center gap-3 px-6 py-4 rounded-2xl border backdrop-blur-md shadow-2xl transition-all duration-500 transform translate-x-full opacity-0 pointer-events-auto min-w-[280px] max-w-sm`;
            
            let icon = 'fa-check-circle';
            if (type === 'success') {
                toast.className += ' bg-accent/10 border-accent/20 text-accent';
                icon = 'fa-check-circle';
            } else {
                toast.className += ' bg-red-500/10 border-red-500/20 text-red-500';
                icon = 'fa-exclamation-circle';
            }

            toast.innerHTML = `
                <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center">
                    <i class="fas ${icon} text-lg"></i>
                </div>
                <div class="flex-1 mr-2">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] opacity-50 mb-0.5">Notification</p>
                    <p class="text-xs font-semibold text-white/90 leading-tight">${message}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-white/20 hover:text-white transition flex-shrink-0">
                    <i class="fas fa-times text-xs"></i>
                </button>
            `;

            container.appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
            }, 10);

            // Animate out and remove
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 500);
            }, 4000);
        }
    </script>
    <div id="toast-container" class="fixed bottom-20 md:bottom-10 right-5 z-[200] flex flex-col gap-3 pointer-events-none"></div>
    <?= $this->include('cwpa/partials/mobile_nav') ?>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
