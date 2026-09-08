<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\LevelModel;

class AdminWpaFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        
        if (!$session->get('isLoggedIn')) {
            $session->set('redirectAfterLogin', current_url());
            return redirect()->to('/admin-wpa/login')->with('error', 'Silakan login terlebih dahulu untuk mengakses Portal Admin WPA');
        }

        // Must be Level 8 (Admin WPA) or Level 7 (Super Admin)
        $levelId = LevelModel::resolveLevelId($session);
        if (!in_array($levelId, [LevelModel::LEVEL_ADMIN_WPA, LevelModel::LEVEL_SUPER_ADMIN], true)) {
            return redirect()->to('/admin-wpa/login')->with('error', 'Anda tidak memiliki akses ke Portal Admin WPA');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
