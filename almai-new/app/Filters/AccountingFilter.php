<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AccountingFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (!$session->get('isLoggedIn')) {
            $session->set('redirectAfterLogin', current_url());
            // User requested to keep login at /login
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
        }

        // Check for accounting level (ID: 6)
        if ((int) $session->get('level_id') !== \App\Models\LevelModel::LEVEL_ACCOUNTING) {
            // If they are logged in but not accounting
            return redirect()->to('/login')->with('error', 'Anda tidak memiliki akses ke halaman ini');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
