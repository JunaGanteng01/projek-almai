<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class UserFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (!$session->get('isLoggedIn')) {
            $session->set('redirectAfterLogin', current_url());
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        $levelId = \App\Models\LevelModel::resolveLevelId($session);

        // Get the current URI to check for exemptions
        $uri = service('uri')->getPath();

        // Restrict CWPA (3) and WPA (4) from accessing user dashboard areas (except Profile for bank data)
        $cleanUri = ltrim($uri, '/');
        if (($levelId === \App\Models\LevelModel::LEVEL_CWPA || $levelId === \App\Models\LevelModel::LEVEL_WPA)
            && !str_contains($cleanUri, 'user/profile')) {
            if (str_starts_with($cleanUri, 'user/')) {
                $target = ($levelId === \App\Models\LevelModel::LEVEL_CWPA) ? '/cwpa/dashboard' : '/wpa/dashboard';
                return redirect()->to($target)->with('error', 'Akses dialihkan ke dashboard Anda.');
            }
        }

        // Allow user (1,2), admin levels, cwpa (3), and wpa (4)
        $allowed = [1, 2, 3, 4];
        if (!in_array($levelId, $allowed, true) && !\App\Models\LevelModel::isAdminLevel($levelId)) {
            return redirect()->to('/')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
