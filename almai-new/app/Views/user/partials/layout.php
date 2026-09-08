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
    <title><?= esc($title ?? 'Dashboard - Almai') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.10.1/sweetalert2.all.min.js"></script>

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
        html,
        body {
            background-color: #050505;
            color: #ffffff;
            overflow-x: hidden;
        }

        .sidebar-link.active {
            background: linear-gradient(90deg, rgba(51, 232, 24, 0.2) 0%, transparent 100%);
            border-left: 3px solid #33e818;
        }

        .bottom-nav-item.active {
            color: #33e818;
        }

        @media (max-width: 767px) {
            main {
                padding-bottom: 64px;
            }
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
    <?= $this->renderSection('styles') ?>
</head>

<body class="antialiased">
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-XXXXXXX"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div class="flex min-h-screen">
        <?= $this->include('user/partials/sidebar') ?>

        <main class="flex-1 w-full max-w-[100vw] overflow-x-hidden md:ml-64 pt-16 md:pt-20 pb-20 md:pb-8">
            <?= $this->include('user/partials/header') ?>

            <div class="px-3 py-4 md:px-8">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="bg-accent/20 border border-accent/50 text-accent px-4 py-3 rounded-xl mb-6">
                        <i class="fas fa-check-circle mr-2"></i><?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="bg-red-500/20 border border-red-500/50 text-red-400 px-4 py-3 rounded-xl mb-6">
                        <i class="fas fa-exclamation-circle mr-2"></i><?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>
            </div>
        </main>
    </div>

    <?= $this->include('user/partials/mobile_nav') ?>

    <script>
        function toggleProfileMenu() {
            document.getElementById('profileMenu').classList.toggle('hidden');
        }

        function toggleMobileProfileMenu() {
            document.getElementById('mobileProfileMenu').classList.toggle('hidden');
        }
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#profileMenu') && !e.target.closest('button[onclick="toggleProfileMenu()"]')) {
                document.getElementById('profileMenu')?.classList.add('hidden');
            }
            if (!e.target.closest('#mobileProfileMenu') && !e.target.closest('button[onclick="toggleMobileProfileMenu()"]')) {
                document.getElementById('mobileProfileMenu')?.classList.add('hidden');
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
    <script>
        // Global Flash Message Handlers
        <?php if (session()->getFlashdata('info')): ?>
            Swal.fire({
                title: 'Informasi',
                text: '<?= session()->getFlashdata('info') ?>',
                icon: 'info',
                confirmButtonColor: '#33E818',
                background: '#111',
                color: '#fff',
                customClass: {
                    popup: 'rounded-[2rem] border border-white/10 shadow-2xl',
                    confirmButton: 'rounded-xl px-6 py-3 text-xs font-black uppercase tracking-widest !text-black',
                }
            });
        <?php endif; ?>

        <?php if (session()->getFlashdata('message')): ?>
            showToast('<?= session()->getFlashdata('message') ?>', 'success');
        <?php endif; ?>
        
        <?php if (session()->getFlashdata('success')): ?>
            showToast('<?= session()->getFlashdata('success') ?>', 'success');
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            showToast('<?= session()->getFlashdata('error') ?>', 'error');
        <?php endif; ?>
    </script>
    <?= $this->renderSection('scripts') ?>
    <script>
        // Web Push Notification Setup
        const vapidPublicKey = 'BDyjVtPauiEycq8VgZoSWgTd9hd5zLwFQuzdNxOvFo-9HIiy8bZsyClxyvxcKLQpbMd2VHU5teDEFHfrsGBw0GY';

        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding)
                .replace(/\-/g, '+')
                .replace(/_/g, '/');

            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);

            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            return outputArray;
        }

        if ('serviceWorker' in navigator && 'PushManager' in window) {
            navigator.serviceWorker.register('/sw.js?v=3')
            .then(function(swReg) {
                
                function sendSubscriptionToServer(sub) {
                    fetch('/user/push/subscribe', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            '<?= csrf_header() ?>': '<?= csrf_hash() ?>'
                        },
                        body: JSON.stringify(sub)
                    })
                    .then(res => res.json())
                    .then(data => console.log('Push subscription:', data))
                    .catch(e => console.error(e));
                }

                swReg.pushManager.getSubscription()
                .then(function(subscription) {
                    if (subscription === null) {
                        Notification.requestPermission().then(function(permission) {
                            if (permission === 'granted') {
                                swReg.pushManager.subscribe({
                                    userVisibleOnly: true,
                                    applicationServerKey: urlBase64ToUint8Array(vapidPublicKey)
                                })
                                .then(function(newSubscription) {
                                    sendSubscriptionToServer(newSubscription);
                                });
                            }
                        });
                    } else {
                        // Already subscribed locally, ensure server has it
                        sendSubscriptionToServer(subscription);
                    }
                });
            })
            .catch(function(error) {
                console.error('Service Worker Error', error);
            });
        }
    </script>
</body>

</html>