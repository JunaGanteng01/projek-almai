<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Security Headers Filter
 * 
 * Menambahkan HTTP Security Headers ke semua response
 * untuk melindungi dari XSS, Clickjacking, MIME sniffing, dll.
 * 
 * Aktifkan di app/Config/Filters.php:
 *   'aliases' => ['securityHeaders' => \App\Filters\SecurityHeaders::class]
 * Dan tambahkan ke 'globals' => ['after' => ['securityHeaders']]
 */
class SecurityHeaders implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Tidak ada aksi sebelum request
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Cegah Clickjacking
        $response->setHeader('X-Frame-Options', 'SAMEORIGIN');

        // Cegah MIME Type Sniffing
        $response->setHeader('X-Content-Type-Options', 'nosniff');

        // CATATAN: X-XSS-Protection sudah usang di browser modern (dihapus Chrome 78+).
        // Perlindungan XSS yang sebenarnya berasal dari CSP di bawah.
        // $response->setHeader('X-XSS-Protection', '1; mode=block'); // DIHAPUS

        // Paksa HTTPS (Strict Transport Security)
        $response->setHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');

        // Batasi informasi Referrer
        $response->setHeader('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions Policy — matikan fitur browser yang tidak dibutuhkan
        $response->setHeader('Permissions-Policy', 'geolocation=(), microphone=(), camera=(), payment=()');

        // SECURITY FIX: Aktifkan Content Security Policy
        // Sesuaikan domain di script-src / style-src / frame-src dengan CDN yang benar-benar dipakai.
        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://challenges.cloudflare.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://unpkg.com https://www.googletagmanager.com https://cdn.tailwindcss.com https://static.cloudflareinsights.com https://sin1.contabostorage.com https://widget.balesotomatis.id",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://unpkg.com https://cdn.tailwindcss.com https://widget.balesotomatis.id",
            "img-src 'self' data: blob: https:",
            "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com",
            "frame-src 'self' https://www.canva.com https://docs.google.com https://drive.google.com https://www.youtube.com https://player.vimeo.com https://challenges.cloudflare.com https://widget.balesotomatis.id",
            "connect-src 'self' https:",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self' https: http:",
            "upgrade-insecure-requests",
        ]);
        $response->setHeader('Content-Security-Policy', $csp);

        // Hapus header yang mengekspos informasi server
        $response->removeHeader('X-Powered-By');
        $response->removeHeader('Server');
    }
}
