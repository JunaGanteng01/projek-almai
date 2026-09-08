<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Files\File;

class Sitemap extends BaseController
{
    public function index()
    {
        $urls = [];
        
        // Static Pages
        $staticUrls = [
            '/',
            '/about',
            '/about/role',
            '/layanan',
            '/wpa',
            '/kelas',
            '/artikel',
            '/tools',
            '/glosarium',
            '/kalender-ekonomi',
            '/kontak',
            '/syarat-ketentuan',
        ];

        foreach ($staticUrls as $url) {
            $urls[] = [
                'loc' => base_url($url),
                'lastmod' => date('Y-m-d'),
                'priority' => ($url === '/') ? '1.0' : '0.8',
                'changefreq' => 'weekly'
            ];
        }

        // --- Dynamic Content ---
        
        // 1. Artikel
        $artikelModel = new \App\Models\LayananArtikelModel();
        $artikels = $artikelModel->where('status', 'published')->findAll();
        foreach ($artikels as $item) {
            $urls[] = [
                'loc' => base_url('artikel/' . $item['id']), // Or slug `layanan/artikel-slug`
                'lastmod' => date('Y-m-d', strtotime($item['updated_at'] ?? $item['created_at'])),
                'priority' => '0.7',
                'changefreq' => 'monthly'
            ];
        }

        // 2. WPA Profiles
        $wpaModel = new \App\Models\WpaModel();
        $wpas = $wpaModel->where('status', 'active')->findAll();
        foreach ($wpas as $item) {
             // Assuming WPA use ID or Slug. Using Wpa::detail route `wpa/(:segment)`
             // If slug exists use it, else ID
             $segment = $item['slug'] ?? $item['id'];
             $urls[] = [
                'loc' => base_url('wpa/' . $segment),
                'lastmod' => date('Y-m-d', strtotime($item['updated_at'] ?? $item['created_at'])),
                'priority' => '0.6',
                'changefreq' => 'monthly'
            ];
        }
        
        // 3. Layanan Categories (SEO Friendly)
        $categories = ['advokasi', 'expert-advisor', 'almai-ultimate'];
        foreach ($categories as $cat) {
            $urls[] = [
                'loc' => base_url('layanan/kategori/' . $cat),
                'lastmod' => date('Y-m-d'),
                'priority' => '0.8',
                'changefreq' => 'weekly'
            ];
        }
        
        // Generate XML
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        $xml .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
        
        foreach ($urls as $url) {
            $xml .= "\t<url>\n";
            $xml .= "\t\t<loc>" . $url['loc'] . "</loc>\n";
            $xml .= "\t\t<lastmod>" . $url['lastmod'] . "</lastmod>\n";
            $xml .= "\t\t<changefreq>" . $url['changefreq'] . "</changefreq>\n";
            $xml .= "\t\t<priority>" . $url['priority'] . "</priority>\n";
            $xml .= "\t</url>\n";
        }
        
        $xml .= "</urlset>";
        
        return $this->response->setContentType('text/xml')->setBody($xml);
    }
}
