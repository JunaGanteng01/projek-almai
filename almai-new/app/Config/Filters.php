<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseFilters
{
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     *
     * @var array<string, class-string|list<class-string>>
     */
    public array $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'cors'          => Cors::class,
        'forcehttps'    => ForceHTTPS::class,
        'pagecache'     => PageCache::class,
        'performance'   => PerformanceMetrics::class,
        'auth'          => \App\Filters\AuthFilter::class,
        'admin'         => \App\Filters\AdminFilter::class,
        'wpa'           => \App\Filters\WpaFilter::class,
        'user'          => \App\Filters\UserFilter::class,
        'accounting'    => \App\Filters\AccountingFilter::class,
        'admin_wpa'     => \App\Filters\AdminWpaFilter::class,
        'superadmin'    => \App\Filters\SuperadminFilter::class,
        'ceo'           => \App\Filters\CeoFilter::class,
        'partnership_admin' => \App\Filters\LaporanKegiatanFilter::class,
        'throttler'     => \App\Filters\Throttler::class,
        'closingPeriod' => \App\Filters\ClosingPeriodFilter::class,
        'jwtfilter'     => \App\Filters\JwtFilter::class,
        'appSecHeaders' => \App\Filters\SecurityHeaders::class, // Custom security headers
    ];

    /**
     * List of special required filters.
     *
     * @var array{before: list<string>, after: list<string>}
     */
    public array $required = [
        'before' => [
            'forcehttps',
            'pagecache',
        ],
        'after' => [
            'pagecache',
            'performance',
        ],
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     *
     * @var array<string, array<string, array<string, string>>>|array<string, list<string>>
     */
    public array $globals = [
        'before' => [
            'honeypot' => ['except' => [
                'register',
                'referral/*',
                'api/mobile/*',
            ]],
            'csrf' => ['except' => [
                'api/chat/*',
                'api/license/*',  // EA License API - external access
                'api/profirm',    // EA Profirm Dashboard update
                'api/webhook/*',  // External Webhooks (WA, FB, IG, etc.)
                'api/webhook/wa', // WA Gateway Webhook Exact Match
                'api/wawebhook/*', // WA Gateway Webhook
                'api/wawebhook',
                'checkout/xendit-callback',
                'webhook/xendit',
                'webhook/balesotomatis',
                'user/poin/purchase',
                'ocr-ktp',
                'user/kyc/ocr-proxy',
                'register/*',
                'api/mobile/*',
            ]],
            'invalidchars',
        ],
        'after' => [
            'honeypot',
            'secureheaders',   // CI4 built-in security headers
            'appSecHeaders',   // Custom security headers (X-Frame, HSTS, dll)
            'toolbar' => ['except' => [
                'api/mobile/*'
            ]],
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * @var array<string, list<string>>
     */
    public array $methods = [];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     *
     * @var array<string, array<string, list<string>>>
     */
    public array $filters = [
        'user'     => ['before' => ['user/*']],
        'auth'     => ['before' => ['user/profile*', 'user/advokasi*']],
        // SECURITY: Throttling mencakup semua endpoint auth sensitif termasuk
        // forgot-password dan reset-password untuk mencegah email enumeration & flooding.
        'throttler' => ['before' => [
            'register/*',
            'login*',
            'auth/*',
            'otp/*',
            'checkout/send-otp',
            'checkout/verify-otp',
            'checkout/send-user-otp',
            'checkout/verify-dual-otp',
            'forgot-password',      // SECURITY: Cegah email enumeration & spam reset
            'reset-password/*',     // SECURITY: Cegah brute-force token reset
        ]],
    ];
}
