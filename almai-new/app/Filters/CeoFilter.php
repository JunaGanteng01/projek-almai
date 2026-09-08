<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\LevelModel;

class CeoFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Bypass filter for auth routes to prevent infinite loop
        $uriPath = trim($request->getUri()->getPath(), '/');
        if (in_array($uriPath, ['ea', 'ea/login', 'ea/logout', 'ceo', 'ceo/login', 'ceo/logout'], true)) {
            return;
        }

        $session = session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/ceo')->with('error', 'Silakan login terlebih dahulu.');
        }

        $levelId = LevelModel::resolveLevelId($session);

        // Hanya CEO (level 10) yang boleh akses area /ea
        if (!LevelModel::isCeoLevel($levelId)) {
            return redirect()->to('/login')->with('error', 'Akses ditolak. Area ini hanya untuk CEO.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
