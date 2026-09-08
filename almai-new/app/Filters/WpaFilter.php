<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class WpaFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (!$session->get('isLoggedIn')) {
            $session->set('redirectAfterLogin', current_url());
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $levelId = \App\Models\LevelModel::resolveLevelId($session);
        $isWpa = $levelId === \App\Models\LevelModel::LEVEL_WPA;

        if (!$isWpa && !\App\Models\LevelModel::isAdminLevel($levelId)) {
            return redirect()->to('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
