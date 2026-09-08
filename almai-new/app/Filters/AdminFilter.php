<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (!$session->get('isLoggedIn')) {
            $session->set('redirectAfterLogin', current_url());
            return redirect()->to('/admin/login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Validasi level_id sebagai single source of truth (admin: 5, super admin: 7, accounting: 6)
        $levelId = \App\Models\LevelModel::resolveLevelId($session);

        if (!\App\Models\LevelModel::isAdminLevel($levelId)) {
            return redirect()->to('/admin/login')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
