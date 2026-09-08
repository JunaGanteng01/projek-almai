<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
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
    <title><?= esc($title ?? 'Almai ID - E-Learning WPA') ?></title>
    
    <!-- Favicon -->
    <link rel="icon" href="<?= base_url('favicon.ico') ?>" sizes="any">
    <link rel="icon" href="<?= base_url('images/logo.png') ?>" type="image/png">
    <link rel="apple-touch-icon" href="<?= base_url('images/logo.png') ?>">
    
    <!-- SEO & Social Media -->
    <meta name="description" content="<?= esc($meta_description ?? 'Platform E-Learning Trading Terbaik Bersama Mentor WPA Terverifikasi.') ?>">
    <meta property="og:title" content="<?= esc($meta_title ?? ($title ?? 'Almai ID - E-Learning WPA')) ?>">
    <meta property="og:description" content="<?= esc($meta_description ?? 'Platform E-Learning Trading Terbaik Bersama Mentor WPA Terverifikasi.') ?>">
    <meta property="og:image" itemprop="image" content="<?= esc($meta_image ?? base_url('images/logo.png?v=3')) ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:url" content="<?= current_url() ?>">
    <meta property="og:type" content="website">
    <meta name="google-adsense-account" content="ca-pub-3886126299375636">
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= esc($meta_title ?? ($title ?? 'Almai ID - E-Learning WPA')) ?>">
    <meta name="twitter:description" content="<?= esc($meta_description ?? 'Platform E-Learning Trading Terbaik Bersama Mentor WPA Terverifikasi.') ?>">
    <meta name="twitter:image" content="<?= esc($meta_image ?? base_url('images/logo.png?v=3')) ?>">

    <!-- WhatsApp specific (often uses OG, but good to ensure everything is covered) -->
    <meta itemprop="name" content="<?= esc($meta_title ?? ($title ?? 'Almai ID - E-Learning WPA')) ?>">
    <meta itemprop="description" content="<?= esc($meta_description ?? 'Platform E-Learning Trading Terbaik Bersama Mentor WPA Terverifikasi.') ?>">
    <meta itemprop="image" content="<?= esc($meta_image ?? base_url('images/logo.png?v=3')) ?>">

    <meta name="<?= csrf_token() ?>" content="<?= csrf_hash() ?>">
    <meta name="csrf-header" content="X-CSRF-TOKEN">
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
    <style>
        body { background-color: #050505; color: #ffffff; }
        .bg-grid { background-size: 40px 40px; background-image: linear-gradient(to right, rgba(255,255,255,0.05) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,0.05) 1px, transparent 1px); mask-image: linear-gradient(to bottom, black 40%, transparent 100%); }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0a0a0a; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #33e818; }
        .modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 100; align-items: center; justify-content: center; }
        .modal.active { display: flex; }
    </style>
    <?= $this->renderSection('styles') ?>
    <!-- JSON-LD Schema for SEO -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "Almai ID",
      "url": "<?= base_url() ?>",
      "potentialAction": {
        "@type": "SearchAction",
        "target": "<?= base_url('layanan?q={search_term_string}') ?>",
        "query-input": "required name=search_term_string"
      }
    }
    </script>
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
      "name": "Almai",
      "url": "<?= base_url() ?>",
      "logo": "<?= base_url('images/logo.png') ?>",
      "sameAs": [
        "https://www.instagram.com/almai_id"
      ]
    }
    </script>
</head>
<body class="antialiased overflow-x-hidden">
    <?php $isEmbed = isset($_GET['embed']) && $_GET['embed'] == '1'; ?>
    
    <?php if (!$isEmbed): ?>
        <?= $this->include('partials/navbar') ?>
    <?php endif; ?>
    
    <?= $this->renderSection('content') ?>
    
    <?php if (!$isEmbed && !isset($hideFooter)): ?>
        <?= $this->include('partials/footer') ?>
        <?= $this->include('partials/whatsapp_button') ?>
    <?php endif; ?>

    <script src="<?= base_url('js/aos.js') ?>"></script>
    <script>AOS.init({ duration: 1000, once: true });</script>
    <script>
        function formatPrice(price) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(price);
        }
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
