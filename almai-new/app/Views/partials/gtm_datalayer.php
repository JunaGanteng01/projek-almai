<?php
/**
 * Almai GTM DataLayer Tracking Script
 * ----------------------------------
 * Menyediakan data untuk GTM (GA4, FB Pixel, TikTok Pixel)
 */
?>
<script>
    window.dataLayer = window.dataLayer || [];

    // 1. Capture UTM & Traffic Source
    const urlParams = new URLSearchParams(window.location.search);
    const utmData = {
        'utm_source': urlParams.get('utm_source'),
        'utm_medium': urlParams.get('utm_medium'),
        'utm_campaign': urlParams.get('utm_campaign'),
        'utm_term': urlParams.get('utm_term'),
        'utm_content': urlParams.get('utm_content'),
        'referral_url': document.referrer || 'direct'
    };
    
    // Hanya push jika ada UTM atau dari referrer eksternal
    if (utmData.utm_source || utmData.referral_url !== 'direct') {
        window.dataLayer.push({
            'event': 'traffic_source_identified',
            'traffic_info': utmData
        });
    }

    // 2. User Identify (Jika Login)
    <?php if (session()->get('isLoggedIn')): ?>
    window.dataLayer.push({
        'user_data': {
            'user_id': '<?= session()->get('userId') ?>',
            'user_role': '<?= session()->get('role') ?>',
            'user_name': '<?= addslashes(session()->get('name') ?? '') ?>',
            'is_pro_member': '<?= session()->get('is_pro') ? 'yes' : 'no' ?>'
        }
    });
    <?php endif; ?>

    // 3. Purchase Tracking (Deteksi dari URL param 'trx' atau 'invoice')
    <?php 
    // Jika sedang di halaman detail layanan setelah checkout sukses
    if (isset($kelas) && isset($transaksiId) && !empty(service('request')->getGet('trx'))): 
    ?>
    window.dataLayer.push({
        'event': 'purchase',
        'ecommerce': {
            'transaction_id': 'TRX-<?= $transaksiId ?>',
            'value': <?= (float)($kelas['price'] ?? 0) ?>,
            'currency': 'IDR',
            'items': [{
                'item_name': '<?= addslashes($kelas['title'] ?? $kelas['name'] ?? 'Produk Almai') ?>',
                'item_id': '<?= $kelas['id'] ?? '0' ?>',
                'price': <?= (float)($kelas['price'] ?? 0) ?>,
                'item_category': '<?= $kelas['category'] ?? 'Advokasi' ?>',
                'quantity': 1
            }]
        }
    });
    <?php endif; ?>

    // 4. Scroll Depth Tracking Helper
    let scrollDepthTriggered = { '25': false, '50': false, '75': false, '90': false };
    window.addEventListener('scroll', function() {
        const h = document.documentElement, 
              b = document.body,
              st = 'scrollTop',
              sh = 'scrollHeight';
        const percent = (h[st]||b[st]) / ((h[sh]||b[sh]) - h.clientHeight) * 100;

        [25, 50, 75, 90].forEach(mark => {
            if (percent >= mark && !scrollDepthTriggered[mark]) {
                scrollDepthTriggered[mark] = true;
                window.dataLayer.push({
                    'event': 'scroll_depth',
                    'depth_percent': mark
                });
            }
        });
    });

    // 5. Time on Page Helper (Every 30s)
    let timeSpent = 0;
    const timeInterval = setInterval(() => {
        timeSpent += 30;
        window.dataLayer.push({
            'event': 'time_on_page',
            'seconds_elapsed': timeSpent
        });
        if (timeSpent >= 300) clearInterval(timeInterval); // Stop after 5 mins
    }, 30000);

</script>
