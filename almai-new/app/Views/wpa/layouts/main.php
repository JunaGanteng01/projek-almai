<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-5MXZW7NP');</script>
    <!-- End Google Tag Manager -->
    <?= $this->include('partials/gtm_datalayer') ?>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-JXVYLB3Z2W"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-JXVYLB3Z2W');
    </script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'WPA Dashboard - Almai') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        accent: '#33e818',
                    },
                    fontFamily: {
                        sans: ['Montserrat', 'sans-serif']
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
        }
        .sidebar-link.active { background: linear-gradient(90deg, rgba(51,232,24,0.2) 0%, transparent 100%); border-left: 3px solid #33e818; }
        .table-responsive { overflow-x: auto; }
        @media (max-width: 767px) { main { padding-bottom: 64px; } }
        @media (max-width: 768px) { .hide-mobile { display: none !important; } }
        html { scroll-behavior: smooth; }
    </style>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?= $this->renderSection('styles') ?>
</head>
<body class="antialiased font-sans">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-XXXXXXX"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div class="flex min-h-screen">
        <?= $this->include('wpa/partials/sidebar') ?>

        <!-- Main Content -->
        <main class="flex-1 w-full max-w-[100vw] overflow-x-hidden md:ml-64 pt-16 md:pt-20 pb-20 md:pb-8">
            <?= $this->include('wpa/partials/header') ?>

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
    
    <!-- Mobile Bottom Navigation -->
    <?= $this->include('wpa/partials/mobile_nav') ?>

    <div id="toast-container" class="fixed bottom-10 right-5 z-[200] flex flex-col gap-3 pointer-events-none"></div>
    <script>
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
    <?= $this->renderSection('scripts') ?>
</body>
</html>
