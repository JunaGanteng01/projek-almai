<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\LevelModel;

class SuperadminFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/admin/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $levelId = LevelModel::resolveLevelId($session);

        // Hanya Super Admin (level 7) yang boleh akses area /superadmin
        if ($levelId !== LevelModel::LEVEL_SUPER_ADMIN) {
            return redirect()->to('/admin/dashboard')->with('error', 'Akses ditolak. Area ini hanya untuk Super Admin.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
