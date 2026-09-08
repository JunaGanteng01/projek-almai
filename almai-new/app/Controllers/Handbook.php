<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class Handbook extends BaseController
{
    public function index()
    {
        // Determine the layout based on the current active segment or session
        $uri = service('uri');
        $segment = $uri->getSegment(1); // 'admin', 'wpa', 'cwpa', 'user', etc.

        $layout = 'admin/layouts/main'; // Default fallback

        if ($segment === 'admin') {
            $layout = 'admin/layouts/main';
        } elseif ($segment === 'wpa') {
            $layout = 'wpa/layouts/main';
        } elseif ($segment === 'cwpa') {
            $layout = 'cwpa/layouts/main';
        } elseif ($segment === 'admin-wpa') {
            $layout = 'admin_wpa/layouts/main';
        } elseif ($segment === 'admin-partnership') {
            $layout = 'admin-partnership/layouts/main';
        } elseif ($segment === 'user') {
            $layout = 'user/partials/layout';
        }

        $data = [
            'title' => 'Handbook Karyawan - Almai',
            'layout' => $layout,
            'pdfUrl' => base_url('Almai_Handbook.pdf'),
        ];

        return view('pages/handbook', $data);
    }
}
